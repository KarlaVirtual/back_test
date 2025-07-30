<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\sql\ConnectionProperty;
use Backend\utils\RedisConnectionTrait;
use Exception;
use Backend\utils\SlackVS;
use Backend\utils\BackgroundProcessVS;


use Backend\dto\SitioTracking;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\Pais;
use Backend\dto\UsuarioOtrainfo;
use Backend\mysql\UsuarioRecargaMySqlDAO;

use \CurlWrapper;

/**
 * Clase 'CronJobAMonitorServer'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobReportingGeneral
{

    private $SlackVS;
    private $BackgroundProcessVS;

    public function __construct()
    {
        $this->SlackVS = new SlackVS('monitor-server');
        $this->BackgroundProcessVS = new BackgroundProcessVS();

    }

    public function execute()
    {

        $redis = RedisConnectionTrait::getRedisInstance(true);

        $comandos = array();

        $datetie = date('s');
        $_ENV["NEEDINSOLATIONLEVEL"] = '1';

        $activeProcesses = array();
        $filename = __DIR__ . '/lastrunCronJobReportingGeneral';


        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $BonoInterno = new BonoInterno();

        $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='REPORTINGGENERAL'";

        $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
        $data = $data[0];
        $line = $data->{'proceso_interno2.fecha_ultima'};


        if ($line == '') {
            return;
        }
        $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+0 seconds'));
        $fechaL2 = date('Y-m-d H:i:00', strtotime($line . '+60 seconds'));


        $filename .= str_replace(' ', '-', str_replace(':', '-', $fechaL2));

        if ($fechaL1 >= date('Y-m-d H:i:00', strtotime('-1 minute'))) {
            return;
        }


        if (file_exists($filename)) {
            print_r('FILE_EXITS');
            print_r($filename);
            $datefilename = date("Y-m-d H:i:s", filemtime($filename));

            if ($datefilename <= date("Y-m-d H:i:s", strtotime('-10 minute'))) {
                unlink($filename);
            }

            return;
        }
        file_put_contents($filename, 'RUN');
        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        try{

            $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='REPORTINGGENERAL';";


            $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
            $transaccion->commit();
        }catch (\Exception $e){
            unlink($filename);

        }

        $_ENV['DB_HOST'] = $_ENV['DB_HOST_BACKUP'];
        $tasks = [
            'Registers' => "
        SELECT usuario.usuario_id
        FROM registro
        INNER JOIN usuario ON registro.usuario_id = usuario.usuario_id
        WHERE usuario.fecha_crea >= '{$fechaL1}' 
        AND usuario.fecha_crea < '{$fechaL2}'
    ",
            'Deposits' => "
        SELECT usuario_recarga.recarga_id, transaccion_producto.transproducto_id
        FROM usuario_recarga
        LEFT OUTER JOIN transaccion_producto ON transaccion_producto.final_id = usuario_recarga.recarga_id
        WHERE usuario_recarga.fecha_crea >= '{$fechaL1}'
        AND usuario_recarga.fecha_crea < '{$fechaL2}'
    "
        ];

        foreach ($tasks as $type => $sql) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                die("Error al bifurcar proceso");
            } elseif ($pid == 0) {
                print_r($sql);

                $BonoInterno = new BonoInterno();
                $data = $BonoInterno->execQuery('', $sql);

                print_r($sql);
                print_r($type);
                foreach ($data as $datanum) {
                    if ($type === 'Registers') {
                        $usuarioId = $datanum->{'usuario.usuario_id'};
                        $this->processRegister($usuarioId);
                    } elseif ($type === 'Deposits') {
                        $recarga_id = $datanum->{'usuario_recarga.recarga_id'};
                        $transproducto_id = $datanum->{'transaccion_producto.transproducto_id'};
                        $this->processDeposit($recarga_id, $transproducto_id);
                    }
                }
                exit();
            }
        }
        unlink($filename);



        $comandos = array('registro'
        , 'usuario_log'
        , 'usuario_log2'
        , 'usuario_recarga'
        , 'it_transaccion'
        , 'transjuego_log'
        , 'cuenta_cobro'
        , 'usuario_bono'
        , 'bono_log'
        );
        $comandos = array();
        foreach ($comandos as $comando) {
            // Crear un nuevo proceso hijo
            $pid = pcntl_fork();
            if ($pid == -1) {
            } elseif ($pid == 0) {
                // Código que ejecuta cada proceso hijo
                $BonoInterno = new BonoInterno();

                switch ($comando) {
                    case 'registro':
                        $sql = "select 'REGISTROCRM' type, CAST(registro.registro_id AS UNSIGNED) id, fecha_crea date
      from registro
               inner join usuario on usuario.usuario_id = registro.usuario_id
      where usuario.fecha_crea >= '{$fechaL1}'
        and usuario.fecha_crea < '{$fechaL2}'";
                        break;
                    case 'usuario_log':
                        $sql = "
      select CASE
                 WHEN (usuario_log.tipo LIKE 'LOGIN%'
                     AND usuario_log.tipo not LIKE '%INCORRECTO') THEN 'LOGINCRM'
                 WHEN (usuario_log.tipo = 'CAMBIOCLAVE') THEN 'CHANGEPASSWORDCRM'
                 ELSE '' END
                           type,
             CAST(usuariolog_id AS UNSIGNED) id,
             fecha_crea    date
      from usuario_log
      where usuario_log.fecha_crea >= '{$fechaL1}'
        and usuario_log.fecha_crea < '{$fechaL2}'
        and (usuario_log.tipo LIKE 'LOGIN%'
                     AND usuario_log.tipo not LIKE '%INCORRECTO')";
                        break;
                    case 'usuario_log2':
                        $sql = "
      select 'UPDATEINFOCRM'
                                         type,
             CAST(usuario_log2.usuariolog2_id AS UNSIGNED) id,
             fecha_crea                  date
      from usuario_log2
      where usuario_log2.fecha_crea >= '{$fechaL1}'
        and usuario_log2.fecha_crea < '{$fechaL2}'";
                        break;
                    case 'usuario_recarga':
                        $sql = "
      select 'DEPOSITOCRM' type, CAST(recarga_id AS UNSIGNED) id, fecha_crea date
      from usuario_recarga
      where usuario_recarga.fecha_crea >= '{$fechaL1}'
        and usuario_recarga.fecha_crea < '{$fechaL2}'
        AND usuario_recarga.estado = 'A'";
                        break;
                    case 'it_transaccion':
                        $sql = "
      select 'BETSPORTSBOOKCRM' type, CAST(it_cuentatrans_id AS UNSIGNED) id, fecha_crea date
      from it_transaccion
      where it_transaccion.fecha_crea_time >= '{$fechaL1}'
        and it_transaccion.fecha_crea_time < '{$fechaL2}'";
                        break;
                    case 'transjuego_log':
                        $sql = "
       select 'BETCASINOCRM' type, CAST(transjuegolog_id AS UNSIGNED) id, fecha_crea date
      from transjuego_log
      where transjuego_log.fecha_crea >= '{$fechaL1}'
        and transjuego_log.fecha_crea < '{$fechaL2}'";
                        print_r($sql);
                        break;
                    case 'cuenta_cobro':
                        $sql = "
       select 'RETIROCREADOCRM' type, CAST(cuenta_id AS UNSIGNED) id, fecha_crea date
      from cuenta_cobro
      where cuenta_cobro.fecha_crea >= '{$fechaL1}'
        and cuenta_cobro.fecha_crea < '{$fechaL2}'";
                        break;
                    case 'usuario_bono':
                        $sql = "
        select 'BONOCRM' type, CAST(usubono_id AS UNSIGNED) id, fecha_crea date
      from usuario_bono
      where (usuario_bono.fecha_crea >= '{$fechaL1}'
          and usuario_bono.fecha_crea < '{$fechaL2}')
         ";
                        break;
                    case 'bono_log':
                        $sql = "
        select 'REDEEMEDBONUSCRM' type, CAST(bonolog_id AS UNSIGNED) id, fecha_crea date
      from bono_log
      where (bono_log.fecha_crea >= '{$fechaL1}'
          and bono_log.fecha_crea < '{$fechaL2}')";
                        break;
                }
                $data = $BonoInterno->execQuery('', $sql);
                foreach ($data as $datanum) {
                    $Abreviado = $datanum->{'.type'};
                    $IdMovimiento = $datanum->{'.id'};
                    $isMobile = 0;
                    $UsuarioId = 0;


                    $redisParam = ['ex' => 18000];

                    $redisPrefix = "AGREGARCRM+UID" . '' . '+' . $Abreviado . '+' . $IdMovimiento;


                    if ($redis != null) {

                        $redis->set($redisPrefix, json_encode(array()), $redisParam);
                    }

                }

            } else {
                $activeProcesses[] = $pid; // Registrar proceso en ejecución
            }

        }




    }

    function Unaccent($string)
    {
        return preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
    }

    function encrypt($data, $encryption_key = "")
    {
        $passEncryt = 'li1296-151.members.linode.com|3232279913';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
        $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', $passEncryt, 0, $iv);
        $encrypted_string = str_replace("/", "vSfTp", $encrypted_string);
        return $encrypted_string;
    }

    function processRegister($usuarioId)
    {

        $user = $usuarioId;

        $Usuario = new \Backend\dto\Usuario($user);
        print_r($Usuario);
        $Registro = new \Backend\dto\Registro('', $Usuario->usuarioId);
        if ($Usuario->mandante == '0') {

            try {
                $campaignName = '';
                $campaignSource = '';
                $campaignContent = '';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {
                    $tvalue = json_decode($tvalue);

                    if ($tvalue->vs_utm_campaign != '') {
                        $campaignName = $tvalue->vs_utm_campaign;
                    }
                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    if ($tvalue->vs_utm_content != '') {
                        $campaignContent = $tvalue->vs_utm_content;
                    }

                }

                if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                    $campaignName = explode('_BQ_', $campaignName)[1];

                }


            } catch (Exception $e) {

            }
            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=64beb6049b64263a88b18743&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&campaign=' . $campaignName));

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 30
                ]);

                $response = $curl->execute();


            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '21') {

            try {
                $campaignName = '';
                $campaignSource = '';
                $campaignContent = '';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {
                    $tvalue = json_decode($tvalue);

                    if ($tvalue->vs_utm_campaign != '') {
                        $campaignName = $tvalue->vs_utm_campaign;
                    }
                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    if ($tvalue->vs_utm_content != '') {
                        $campaignContent = $tvalue->vs_utm_content;
                    }

                }

                if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                    $campaignName = explode('_BQ_', $campaignName)[1];

                }


            } catch (Exception $e) {

            }
            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=680ff71fe21f21a76d232930&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&campaign=' . $campaignName));

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 30
                ]);

                $response = $curl->execute();


            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '8') {

            try {
                $Pais = new Pais($Usuario->paisId);

                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper(
                    'https://ctag.containermedia.net/api/s2s/secure/?id=64da9663633b177007960afb&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $Pais->paisNom
                );

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 30
                ]);

                $response = $curl->execute();


            } catch (Exception $e) {

            }
        }
        if ($Usuario->mandante == '23') {

            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper(
                    'https://ctag.containermedia.net/api/s2s/secure/?id=65e71e4b74551688a6435fbb&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $Pais->paisNom
                );

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 30
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }


        if ($Usuario->mandante == '21') {
            $Pais = new Pais($Usuario->paisId);
            $ENCRYPTION_KEY = "D!@#$%^&*";

            $user_id = $Usuario->usuarioId;
            $email = $Usuario->login;
            $first_name = urlencode(trim(str_replace(" ", "", $Registro->nombre1)));
            $last_name = urlencode(trim(str_replace(" ", "", $Registro->apellido1)));
            $phone = urlencode(trim(str_replace(" ", "", $Registro->celular)));
            $affiliate = $Registro->afiliadorId;
            $btag = urlencode($this->encrypt($affiliate . "__" . $Registro->linkId, $ENCRYPTION_KEY));

            $country = $Pais->iso;


            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper('https://load.t.caman.vip/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate);

            $curl->setOptionsArray( array(
                CURLOPT_URL => 'https://load.t.caman.vip/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => 'user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                )
            ));

            $response = $curl->execute();
            print_r($response);
        }

        if ($Usuario->mandante == '27') {
            $Pais = new Pais($Usuario->paisId);
            $ENCRYPTION_KEY = "D!@#$%^&*";

            $user_id = $Usuario->usuarioId;
            $email = $Usuario->login;
            $first_name = urlencode(trim(str_replace(" ", "", $Registro->nombre1)));
            $last_name = urlencode(trim(str_replace(" ", "", $Registro->apellido1)));
            $phone = urlencode(trim(str_replace(" ", "", $Registro->celular)));
            $affiliate = $Registro->afiliadorId;
            $btag = urlencode($this->encrypt($affiliate . "__" . $Registro->linkId, $ENCRYPTION_KEY));

            $country = $Pais->iso;

            $urlDominio='https://load.t.ganaplay.sv';

            if($Usuario->paisId=='94'){
                $urlDominio='https://load.t.ganaplay.gt';

            }

            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper($urlDominio.'/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate);

            $curl->setOptionsArray( array(
                CURLOPT_URL => $urlDominio.'/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => 'user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                )
            ));

            $response = $curl->execute();
            print_r($response);
        }

        if ($Usuario->mandante == '0') {
            $Pais = new Pais($Usuario->paisId);
            $ENCRYPTION_KEY = "D!@#$%^&*";

            $user_id = $Usuario->usuarioId;
            $email = $Usuario->login;
            $first_name = urlencode(trim(str_replace(" ", "", $Registro->nombre1)));
            $last_name = urlencode(trim(str_replace(" ", "", $Registro->apellido1)));
            $phone = urlencode(trim(str_replace(" ", "", $Registro->celular)));
            $affiliate = $Registro->afiliadorId;
            $btag = urlencode($this->encrypt($affiliate . "__" . $Registro->linkId, $ENCRYPTION_KEY));

            $country = $Pais->iso;


            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper('https://load.t.doradobet.com/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate);

            $curl->setOptionsArray( array(
                CURLOPT_URL => 'https://load.t.doradobet.com/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => 'user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                )
            ));

            $response = $curl->execute();
            print_r($response);
        }
        if ($Usuario->mandante == '8') {
            $Pais = new Pais($Usuario->paisId);
            $ENCRYPTION_KEY = "D!@#$%^&*";

            $user_id = $Usuario->usuarioId;
            $email = $Usuario->login;
            $first_name = urlencode(trim(str_replace(" ", "", $Registro->nombre1)));
            $last_name = urlencode(trim(str_replace(" ", "", $Registro->apellido1)));
            $phone = urlencode(trim(str_replace(" ", "", $Registro->celular)));
            $affiliate = $Registro->afiliadorId;
            $btag = urlencode($this->encrypt($affiliate . "__" . $Registro->linkId, $ENCRYPTION_KEY));

            $country = $Pais->iso;


            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper('https://load.t.ecuabet.com/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate);

            // Configurar opciones
            $curl->setOptionsArray([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => 'user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                )
            ]);

            $response = $curl->execute();
            print_r($response);


        }
        print_r('entroaqui4');

        if ($Usuario->mandante == '23') {
            $Pais = new Pais($Usuario->paisId);
            $ENCRYPTION_KEY = "D!@#$%^&*";

            $user_id = $Usuario->usuarioId;
            $email = $Usuario->login;
            $first_name = urlencode(trim(str_replace(" ", "", $Registro->nombre1)));
            $last_name = urlencode(trim(str_replace(" ", "", $Registro->apellido1)));
            $phone = urlencode(trim(str_replace(" ", "", $Registro->celular)));
            $affiliate = $Registro->afiliadorId;
            $btag = urlencode($this->encrypt($affiliate . "__" . $Registro->linkId, $ENCRYPTION_KEY));

            $country = $Pais->iso;


            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper(
                'https://load.t.paniplay.com/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate
            );

            // Configurar opciones
            $curl->setOptionsArray([

                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => 'user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                )
            ]);

            $response = $curl->execute();

        }

        if ( $Usuario->mandante == '18' ) {
            $IdPixelFB = array();

            $eventName='CompleteRegistrarion';
            switch ($Usuario->mandante) {
                case '18':
                    switch ($Usuario->paisId) {
                        case '146':
                            $IdPixelFB['287183233632300']= 'EAACqvEWNsk0BO6JTFxtFWco0gIF3bZCsiMijcOMeyPIj9A2G30lTXUW0mxblS01ML82zD4YWlxnplhObTF2mv9f0RyTuh6Hqj2vKv1ey9GZA9J2oFzTuiWuprel6Xv4A1IaZCXvAaY5C0fD6B6rYAYqe1dmJdNRpYOkI0KsF98T6qMSG4ZCjV9mY5tr3KAZDZD';

                            break;
                        default:

                            break;
                    }

                    break;
            }
            if ( oldCount($IdPixelFB) > 0 ) {
                $Pais = new Pais($Usuario->paisId);

                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    $fb = '';
                    $fc = '';

                    if ($campaignName != '' && strpos($campaignName, 'fb.') !== false) {
                        $fb = '';
                        $fc = explode('_FFBB_', $campaignName)[1];


                    }


                    foreach ($IdPixelFB as $keyIdPixelFB => $valueIdPixelFB) {
                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://graph.facebook.com/v18.0/' . $keyIdPixelFB . '/events');

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => array('data' => '[
       {
         "event_name": "' .$eventName. '",
         "event_time": ' . time() . ',
         "user_data": {
           "fn": [
             "' . hash('sha256', trim(strtolower($Registro->getNombre1()))) . '"
           ], 
           "ln": [
             "' . hash('sha256', trim(strtolower($Registro->getApellido1()))) . '"
           ],  
           "em": [
             "' . hash('sha256', trim(strtolower($Usuario->login))) . '"
           ],
           "ph": [
             "' . hash('sha256', trim(strtolower($Pais->prefijoCelular))) . '",
             "' . hash('sha256', trim(strtolower($Registro->celular))) . '"
           ],
           "client_ip_address": "' . $Usuario->dirIp . '",
           "client_user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n 537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
           "fbc": "' . $fc . '",
           "fbp": "' . $fb . '"
         },
         "action_source": "website",
         "event_id": "'.$Usuario->usuarioId.'"
       }
     ]', 'access_token' => $valueIdPixelFB)
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }



                } catch (Exception $e) {

                }

            }

        }

        if ($Usuario->mandante == '27'  && $Usuario->paisId == '68') {

            try {
                $campaignName = '';
                $campaignSource = '';
                $campaignContent = '';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {
                    $tvalue = json_decode($tvalue);

                    if ($tvalue->vs_utm_campaign != '') {
                        $campaignName = $tvalue->vs_utm_campaign;
                    }
                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    if ($tvalue->vs_utm_content != '') {
                        $campaignContent = $tvalue->vs_utm_content;
                    }

                }

                if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                    $campaignName = explode('_BQ_', $campaignName)[1];

                }


            } catch (Exception $e) {

            }
            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=6848619d6c1c994f93a4bc52&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&campaign=' . $campaignName));

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 30
                ]);

                $response = $curl->execute();


            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '27'  && $Usuario->paisId == '94') {

            try {
                $campaignName = '';
                $campaignSource = '';
                $campaignContent = '';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {
                    $tvalue = json_decode($tvalue);

                    if ($tvalue->vs_utm_campaign != '') {
                        $campaignName = $tvalue->vs_utm_campaign;
                    }
                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    if ($tvalue->vs_utm_content != '') {
                        $campaignContent = $tvalue->vs_utm_content;
                    }

                }

                if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                    $campaignName = explode('_BQ_', $campaignName)[1];

                }


            } catch (Exception $e) {

            }
            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=684861546c1c994f93a4a3ae&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&campaign=' . $campaignName));

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 30
                ]);

                $response = $curl->execute();


            } catch (Exception $e) {

            }
        }


    }

    function processDeposit($usurecargaId, $transproductoId)
    {
        $UsuarioRecarga = new \Backend\dto\UsuarioRecarga($usurecargaId);
        $TransaccionProducto = new \Backend\dto\TransaccionProducto($transproductoId);

        $Usuario = new \Backend\dto\Usuario($UsuarioRecarga->usuarioId);
        $Registro = new \Backend\dto\Registro('', $Usuario->usuarioId);

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

        $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $Usuario->usuarioId . "'");

        $detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];

        $detalleDepositos = intval($detalleDepositos) - 1;


        if ($Usuario->mandante == '0') {

            try {
                $campaignName = '';
                $campaignSource = '';
                $campaignContent = '';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {
                    $tvalue = json_decode($tvalue);

                    if ($tvalue->vs_utm_campaign != '') {
                        $campaignName = $tvalue->vs_utm_campaign;
                    }
                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    if ($tvalue->vs_utm_content != '') {
                        $campaignContent = $tvalue->vs_utm_content;
                    }

                }

                if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                    $campaignName = explode('_BQ_', $campaignName)[1];

                }


            } catch (Exception $e) {

            }

            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=64beb60c9b64263a88b18746&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor . '&campaign=' . $campaignName));

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '0') {
            if (intval($detalleDepositos) == 0) {

                try {
                    $Pais = new Pais($Usuario->paisId);

                    $curl = new CurlWrapper('https://ctag.containermedia.net/api/s2s/secure/?id=654d00ab82b1baef64f5a5ee&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();


                } catch (Exception $e) {

                }
            }
        }



        if ($Usuario->mandante == '21') {

            try {
                $campaignName = '';
                $campaignSource = '';
                $campaignContent = '';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {
                    $tvalue = json_decode($tvalue);

                    if ($tvalue->vs_utm_campaign != '') {
                        $campaignName = $tvalue->vs_utm_campaign;
                    }
                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    if ($tvalue->vs_utm_content != '') {
                        $campaignContent = $tvalue->vs_utm_content;
                    }

                }

                if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                    $campaignName = explode('_BQ_', $campaignName)[1];

                }


            } catch (Exception $e) {

            }

            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=680ff728e21f21a76d232b55&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor . '&campaign=' . $campaignName));

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '21') {
            if (intval($detalleDepositos) == 0) {

                try {
                    $Pais = new Pais($Usuario->paisId);

                    $curl = new CurlWrapper('https://ctag.containermedia.net/api/s2s/secure/?id=680ff728e21f21a76d232b55&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();


                } catch (Exception $e) {

                }
            }
        }


        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {


                try {
                    $Pais = new Pais($Usuario->paisId);

                    $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=654d00bc82b1baef64f5a79a&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor));

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }
            }
        }
        if ($Usuario->mandante == '23') {
            if (intval($detalleDepositos) == 0) {


                try {
                    $Pais = new Pais($Usuario->paisId);

                    $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=65e71e5574551688a6436051&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor));

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }
            }
        }
        if ($Usuario->mandante == '14') {
            if (intval($detalleDepositos) == 0) {

                try {
                    $Pais = new Pais($Usuario->paisId);

                    $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=654cff9e82b1baef64f58af8&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor));

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();


                } catch (Exception $e) {

                }
            }
        }
        if ($Usuario->mandante == '13') {
            if (intval($detalleDepositos) == 0) {

                try {
                    $Pais = new Pais($Usuario->paisId);

                    $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=654d00d882b1baef64f5aa85&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor));

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }
            }
        }

        if ($Usuario->mandante == '21') {
            $first_deposit = 0;
            if (intval($detalleDepositos) == 0) {
                $first_deposit = 1;
            }
            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://load.t.caman.vip/payments?user_id=' . $Usuario->usuarioId . '&payment_type=' . $Registro->afiliadorId . '&country=' . $Pais->iso . '&transaction_id=' . $UsuarioRecarga->recargaId . '&currency=' . $Usuario->moneda . '&value=' . $UsuarioRecarga->valor . '&first_deposit=' . $first_deposit);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }
        if ($Usuario->mandante == '27') {
            $first_deposit = 0;
            if (intval($detalleDepositos) == 0) {
                $first_deposit = 1;
            }
            try {
                $Pais = new Pais($Usuario->paisId);

                $urlDominio='https://load.t.ganaplay.sv';

                if($Usuario->paisId=='94'){
                    $urlDominio='https://load.t.ganaplay.gt';

                }
                $curl = new CurlWrapper($urlDominio.'/payments?user_id=' . $Usuario->usuarioId . '&payment_type=' . $Registro->afiliadorId . '&country=' . $Pais->iso . '&transaction_id=' . $UsuarioRecarga->recargaId . '&currency=' . $Usuario->moneda . '&value=' . $UsuarioRecarga->valor . '&first_deposit=' . $first_deposit);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '0') {
            $first_deposit = 0;
            if (intval($detalleDepositos) == 0) {
                $first_deposit = 1;
            }
            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://load.t.doradobet.com/payments?user_id=' . $Usuario->usuarioId . '&payment_type=' . $Registro->afiliadorId . '&country=' . $Pais->iso . '&transaction_id=' . $UsuarioRecarga->recargaId . '&currency=' . $Usuario->moneda . '&value=' . $UsuarioRecarga->valor . '&first_deposit=' . $first_deposit);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }
        if ($Usuario->mandante == '14') {
            $first_deposit = 0;
            if (intval($detalleDepositos) == 0) {
                $first_deposit = 1;
            }
            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://load.t.lotosports.bet/payments?user_id=' . $Usuario->usuarioId . '&payment_type=' . $Registro->afiliadorId . '&country=' . $Pais->iso . '&transaction_id=' . $UsuarioRecarga->recargaId . '&currency=' . $Usuario->moneda . '&value=' . $UsuarioRecarga->valor . '&first_deposit=' . $first_deposit);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '8') {

            $first_deposit = 0;
            if (intval($detalleDepositos) == 0) {
                $first_deposit = 1;
            }

            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://load.t.ecuabet.com/payments?user_id=' . $Usuario->usuarioId . '&payment_type=' . $Registro->afiliadorId . '&country=' . $Pais->iso . '&transaction_id=' . $UsuarioRecarga->recargaId . '&currency=' . $Usuario->moneda . '&value=' . $UsuarioRecarga->valor . '&first_deposit=' . $first_deposit);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '23') {

            $first_deposit = 0;
            if (intval($detalleDepositos) == 0) {
                $first_deposit = 1;
            }

            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://load.t.paniplay.com/payments?user_id=' . $Usuario->usuarioId . '&payment_type=' . $Registro->afiliadorId . '&country=' . $Pais->iso . '&transaction_id=' . $UsuarioRecarga->recargaId . '&currency=' . $Usuario->moneda . '&value=' . $UsuarioRecarga->valor . '&first_deposit=' . $first_deposit);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '15') {

            $first_deposit = 0;
            if (intval($detalleDepositos) == 0) {
                $first_deposit = 1;
            }

            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://load.t.hondubet.co/payments?user_id=' . $Usuario->usuarioId . '&payment_type=' . $Registro->afiliadorId . '&country=' . $Pais->iso . '&transaction_id=' . $UsuarioRecarga->recargaId . '&currency=' . $Usuario->moneda . '&value=' . $UsuarioRecarga->valor . '&first_deposit=' . $first_deposit);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }
        if ($Usuario->mandante == '0' || $Usuario->mandante == '8' || $Usuario->mandante == '23' || $Usuario->mandante == '18' || $Usuario->mandante == '21' || ($Usuario->mandante == '27')) {
            $IdPixelFB = array();

            $eventNameFTD='Purchase';
            $eventNameDeposit='deposit';
            switch ($Usuario->mandante) {
                case '0':
                    switch ($Usuario->paisId) {
                        case '173':
                            $IdPixelFB['812989615789140']= 'EAAQkRyQd98ABOxpVZBx4H1aetTzSvtkkg3awwCyAvbU5jJU7ZAfszHhs9ScBGnEJANvSZCVMdUjxMrMxuqg5XjeaQwqHwDrbnwsUca2tra1SriIiQ3U9MPq2oSk4gZCiyuTSKjWAJtyRryZBPnY0gnDRZCdlTFoBe1ZAdSHypjNohrWM4QX8RzmOGLAG3BVlIbQ9wZDZD';

                            break;
                        case '46':
                            $IdPixelFB['1327606348340302'] = 'EAANgl4N9T9QBOxza9bASf3Gde00HwEwgJo6TlBqeCnAgbgwvaz8437cna8LpMBOGl4jbALOaByX0YlZCkKcWNYvIvM8xkDuPuMRk0E3zWoA02mY5rowEEyaS9syxfDs98QyeIq9NSKX7hTRcKa6zDqVniUBAq6LsltujUJZCXY5oyxpsmgtDc3n8KoFk3WjAZDZD';

                            break;
                        case '60':
                            $IdPixelFB['1105439247405541']= 'EAALPBXN9LmABO8jNGiXHXpNZCjc2fMrHiSRFsUDxfH3xZCrcZBBRAMpDmW3BZB0aFZBhTU4dbQnZBCJgnxLJBnEvtusdEgir1BwvJiWhMkI26c3KMQUCN6XnfZBuHydZApD6DzZAH2fsyWvH9goyTpZAWi3cUMLMZAjrHhT0hp4VCV0jFHGa9mxetkUEv9ymPzcwM5hkQZDZD';

                            break;
                        case '66':
                            $IdPixelFB ['461183879663921']= 'EAALPBXN9LmABO2RDdzjDJ7XOLw7a9Y4IwDzrVZBXdDSZAZAPNIFfCCFOUzx5TSdZAscpWTp9W9rpt6IypQCEtWYVsSYLjfQRW1NSZBe3ScXgULb62PBL4XkYIGjUikZCeckfDYXCHvkhDmA1L9CYi1EETaMkMZBGyY5ZAURSxa4fYOjczoNFS4B74hwVn0IJUoYLEQZDZD';

                            break;
                        case '2':
                            $IdPixelFB ['3721024701479063'] = 'EAALPBXN9LmABO50E73sdS5mfdi1cLMJFj7PFq71YlHAyFsExiNhYJhOaZCsuOJhbOfr7lv5TpYLFQ9k8to173zlWs5w9Daaj1cmapboWSGp9RvmB1vMAqZCdy4fCx9MkW4zhgpC38qReZChDevJ20xvyAllcZCp1fzNzL6HxdlRZApRsV9aLo4W7FZCNZCm2B1IdgZDZD';

                            break;
                        case '94':
                            $IdPixelFB ['1926958851376751']= 'EAANgl4N9T9QBOxza9bASf3Gde00HwEwgJo6TlBqeCnAgbgwvaz8437cna8LpMBOGl4jbALOaByX0YlZCkKcWNYvIvM8xkDuPuMRk0E3zWoA02mY5rowEEyaS9syxfDs98QyeIq9NSKX7hTRcKa6zDqVniUBAq6LsltujUJZCXY5oyxpsmgtDc3n8KoFk3WjAZDZD';

                            break;
                        case '68':
                            $IdPixelFB['1006755447642017'] = 'EAALPBXN9LmABO6Lzdj6g7YxbwGZBUqLU1rt6yPuLwRAuVZAppsCD5dUrLFzJGLdAN9HofHgewCnt4UeRl8aQHsvpphTZCknLRZB8mKnIjh10iHqdbkhTSZBx59ipj5DHtgdb3pZBNqUkcef45vB3KSf4QTVXwuhHvrz3GRVFItmrJwHQcok4OI23lpaclIvQ7pbwZDZD';

                            break;
                        case '21':
                            $IdPixelFB ['2339862716414991']= 'EAANgl4N9T9QBOZCL9DStzAHGfZClwlZB352WuPZCCkW9cQgxMZBB2Qnj6PWuqO49U2ZCRbJUrRyCxvi6mZCzTnk0OCVHL59AWHYuLd35gBEE15yXlnZBpGfCHHeFhoUevUNJOxOnPw8cmVEpdijH2Y3AtvvZBzYxY8g6y6ncj44UNp0ZAOmaGcZBrJUf30wJHSMIwZDZD';

                            break;
                        case '102':
                            $IdPixelFB [ '1441502740565794']= 'EAANgl4N9T9QBO6DK4yNAIpJCiVDeXBFpJndX6NfS1ZCWFBTH04F12rFUAYE0ObAwtCO8krxkgFMCxg6A56ZA13kAC5ovTlq7HSyEtuSfOVK4PVMKb7tu1xu5s9GK81AafUXK8ww90Xc4U8omzsKnOo1JhkEYnmSsQOZCHUKdSzcNO3M8wCfY8bsUf3w0AZDZD';

                            break;

                    }
                    break;
                case '8':
                    $IdPixelFB [ '737061788539519'] = 'EAALPBXN9LmABO2RFS919lZBpZAbKzhYALZBifOysjSgjW6ZBmZCsy6iE4RutqnrYdeB6YCYqTKleIJX9x4FOaceQrvjt47gw2V0aMI0ZCb42q5BZCKK9XXlXX1efPcfbgPOmJYtSAl0uGu6aZAvlPVjBGeZABkDBWMobloEfwHozisdxurP6OQvnix4Rzlj8ZAMOYDlwZDZD';
                    break;

                case '23':
                    $IdPixelFB['439588298959499']= 'EAALPBXN9LmABO41ZCCCWqgVgZCdTuxGcH818aM7RZBtnnQSk40dYZAY9CagpWQF9FHSjEyiL5ZBJBAokVO6XYZCYPAaJY6ZBzgZB0I4MCZAtVHlZBRnqSUKVa8X1HAXth3GTrfzaZCQUP52GMFZAndVgZB8hZAAFxRSPKHam3smVbRZAFmjHezKtZCVyE5MZBpLjbw0LEfZAUsuAZDZD';
                    break;
                case '18':
                    switch ($Usuario->paisId) {
                        case '146':
                            $IdPixelFB ['287183233632300']= 'EAACqvEWNsk0BO6JTFxtFWco0gIF3bZCsiMijcOMeyPIj9A2G30lTXUW0mxblS01ML82zD4YWlxnplhObTF2mv9f0RyTuh6Hqj2vKv1ey9GZA9J2oFzTuiWuprel6Xv4A1IaZCXvAaY5C0fD6B6rYAYqe1dmJdNRpYOkI0KsF98T6qMSG4ZCjV9mY5tr3KAZDZD';
                            $eventNameFTD='ftd';
                            $eventNameDeposit='deposit';

                            break;
                        default:
                            $IdPixelFB ['1222562095083880'] = 'EAAWRGpobUEABOZBiNMxbUCcfye3RwOVbbfVhZBlmVxFLwCdT3NH9PpAw4dQCKtlqLdGB67F6KF22pd2fVOawq7UHEVXZBgcb88TPeihIW5NCEYIIWe0WBgnNcrczr0Lks6YKKEoD6Ed3JIgskza4QyjGwOfpEaZCasZCUXhnrssOSrPBREfTr20yBIfo87amFIwZDZD';

                            break;
                    }

                    break;
                case '27':
                    switch ($Usuario->paisId) {
                        case '68':
                            $IdPixelFB['715810480807321'] = 'EAAIRsoir2wwBOZC2cQKyAkFnOVJdAo9OM6X7hJ6RdEBtOpRYiXtlvTQC6NRTHRWLO23b4dXVt98smtJxPz6VRuGLAeZCHOAp0zxPLU7syTWKH96GxIFMtMfUk12fQI4uVZAHBT2oa7utazGlZAwWVl99L5grcOKi3OswqlO4SZBl9fOrBg3PVl10ZByamauj6uDgZDZD';
                            $IdPixelFB['1167273957708577'] = 'EAAIRsoir2wwBO1nD75s9lqcUlTQb2RxJycA9iOPCLFDdcYhFZAZCusTU229iO35lzwZCc7GKfhzslZAqHeVbCDVHy5N22ZCsOeoAVXEALXadaLqRZCxSiPKWd4fZBlqChAAVHMCifVbxghv4hcgzYlR06W58FZC67NZCVApqWSIhT77NlIdZBASqsPPGFIDxBwZCoYOigZDZD';
                            $IdPixelFB['1612418202770627'] = 'EAAFlTNAhfzgBO3UZClF8HaXGD1O34NwkCECjgFmTPLrUjgogdJ8e6CtrZBkhAsFy7f1XI5WjZAKjdc1rjXEj7qvFeIcScBZBK0ZBfXEUYpeU43HtGHB45dyH2ZAGEWF1BClAtmHaBHN2GGHNz61ihDj1VWZBC5wDyaXbjHjWVmjHdm6djs6byZBAQdH4g39mbQYkogZDZD';

                            break;

                        case '94':
                            $IdPixelFB [ '1078708357615597'] = 'EAANgl4N9T9QBOzGa7LQbnpBHVScRM1ZBt1a0uhLZArYvG5TctFPKfyZCMcFO7wb13wfyeSZC9U9iv8c4ZCgSZByYFkInNyiSxvzeqt8AG4buGOw4pfZCwQRMA2FZAKBt3KwCZCaAd5ClZAI7FM8bifbNY6zuSDZA4pFrc8n7Gp4x1C97lkYGlM6vvnpestHk82kk85iHgZDZD';
                            $IdPixelFB [ '1669701520236995'] = 'EAAIRsoir2wwBO1nD75s9lqcUlTQb2RxJycA9iOPCLFDdcYhFZAZCusTU229iO35lzwZCc7GKfhzslZAqHeVbCDVHy5N22ZCsOeoAVXEALXadaLqRZCxSiPKWd4fZBlqChAAVHMCifVbxghv4hcgzYlR06W58FZC67NZCVApqWSIhT77NlIdZBASqsPPGFIDxBwZCoYOigZDZD';

                            break;
                    }

                    break;
            }
            if (intval($detalleDepositos) == 0 && ($Usuario->mandante == 8)) {
                $Pais = new Pais($Usuario->paisId);

                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    $fb = '';
                    $fc = '';

                    if ($campaignName != '' && strpos($campaignName, '_FBECMB_') !== false) {
                        $fb = '';
                        $fc = explode('_FBECMB_', $campaignName)[1];


                    }

                    try {


// Inicializar la clase CurlWrapper
                        $curl = new CurlWrapper('https://graph.facebook.com/v18.0/' . '1219297862908932' . '/events');

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array('data' => '[
       {
         "event_name": "Purchase",
         "event_time": ' . time() . ',
         "user_data": {
           "fn": [
             "' . hash('sha256', trim(strtolower($Registro->getNombre1()))) . '"
           ], 
           "ln": [
             "' . hash('sha256', trim(strtolower($Registro->getApellido1()))) . '"
           ],  
           "em": [
             "' . hash('sha256', trim(strtolower($Usuario->login))) . '"
           ],
           "ph": [
             "' . hash('sha256', trim(strtolower($Pais->prefijoCelular))) . '",
             "' . hash('sha256', trim(strtolower($Registro->celular))) . '"
           ],
           "client_ip_address": "' . $Usuario->dirIp . '",
           "client_user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n 537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
           "fbc": "' . $fc . '",
           "fbp": "' . $fb . '"
         },
         "custom_data": {
           "currency": "' . $Usuario->moneda . '",
           "value": ' . $TransaccionProducto->valor . ',
           "contents": [
             {
               "id": "' . $TransaccionProducto->transproductoId . '",
             }
           ]
         },
         "action_source": "website"
       }
     ]', 'access_token' => 'EAANgl4N9T9QBO1eas7NJfXd5zrsT43JyKnhUTJMD7cZASto0BUyikUAYhUdsxPUov1ZBqg3nhzpumeyWbDP4k8QwVUBrbfuDFqrkb2aiyobG1h8X1HuXTjM5h20yjaEQWZBDtnt9JaWJ7T4Tx27roZBN8TtGeZBQ1j5eMixna4i3v7rGZCZAdKutzezWZAuemAZDZD')
                        ]);

// Ejecutar la solicitud
                        $response = $curl->execute();


                    } catch (Exception $e) {

                    }


                } catch (Exception $e) {

                }

            }

            if (intval($detalleDepositos) == 0 && ($Usuario->mandante != 18 || ($Usuario->mandante == 18 ))) {
                $Pais = new Pais($Usuario->paisId);

                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    $fb = '';
                    $fc = '';

                    if ($campaignName != '' && strpos($campaignName, 'fb.') !== false) {
                        $fb = '';
                        $fc = explode('_FFBB_', $campaignName)[1];


                    }
                    foreach ($IdPixelFB as $keyIdPixelFB => $valueIdPixelFB) {

                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://graph.facebook.com/v18.0/' . $keyIdPixelFB . '/events');

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => array('data' => '[
       {
         "event_name": "' . $eventNameFTD . '",
         "event_time": ' . time() . ',
         "user_data": {
           "fn": [
             "' . hash('sha256', trim(strtolower($Registro->getNombre1()))) . '"
           ], 
           "ln": [
             "' . hash('sha256', trim(strtolower($Registro->getApellido1()))) . '"
           ],  
           "em": [
             "' . hash('sha256', trim(strtolower($Usuario->login))) . '"
           ],
           "ph": [
             "' . hash('sha256', trim(strtolower($Pais->prefijoCelular))) . '",
             "' . hash('sha256', trim(strtolower($Registro->celular))) . '"
           ],
           "client_ip_address": "' . $Usuario->dirIp . '",
           "client_user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n 537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
           "fbc": "' . $fc . '",
           "fbp": "' . $fb . '"
         },
         "custom_data": {
           "currency": "' . $Usuario->moneda . '",
           "value": ' . $TransaccionProducto->valor . ',
           "contents": [
             {
               "id": "' . $TransaccionProducto->transproductoId . '",
             }
           ]
         },
         "action_source": "website"
       }
     ]', 'access_token' => $valueIdPixelFB)
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }
            if ( $Usuario->mandante != 18 || ($Usuario->mandante == 18 ) ) {
                $Pais = new Pais($Usuario->paisId);

                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    $fb = '';
                    $fc = '';

                    if ($campaignName != '' && strpos($campaignName, 'fb.') !== false) {
                        $fb = '';
                        $fc = explode('_FFBB_', $campaignName)[1];


                    }
                    foreach ($IdPixelFB as $keyIdPixelFB => $valueIdPixelFB) {

                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://graph.facebook.com/v18.0/' . $keyIdPixelFB . '/events');

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => array('data' => '[
       {
         "event_name": "' . $eventNameDeposit . '",
         "event_time": ' . time() . ',
         "user_data": {
           "fn": [
             "' . hash('sha256', trim(strtolower($Registro->getNombre1()))) . '"
           ], 
           "ln": [
             "' . hash('sha256', trim(strtolower($Registro->getApellido1()))) . '"
           ],  
           "em": [
             "' . hash('sha256', trim(strtolower($Usuario->login))) . '"
           ],
           "ph": [
             "' . hash('sha256', trim(strtolower($Pais->prefijoCelular))) . '",
             "' . hash('sha256', trim(strtolower($Registro->celular))) . '"
           ],
           "client_ip_address": "' . $Usuario->dirIp . '",
           "client_user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n 537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
           "fbc": "' . $fc . '",
           "fbp": "' . $fb . '"
         },
         "custom_data": {
           "currency": "' . $Usuario->moneda . '",
           "value": ' . $TransaccionProducto->valor . ',
           "contents": [
             {
               "id": "' . $TransaccionProducto->transproductoId . '",
             }
           ]
         },
         "action_source": "website"
       }
     ]', 'access_token' => $valueIdPixelFB)
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }

            if (intval($detalleDepositos) == 0 && ($Usuario->mandante == 18 )) {
                $Pais = new Pais($Usuario->paisId);

                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    $fb = '';
                    $fc = '';

                    if ($campaignName != '' && strpos($campaignName, 'fb.') !== false) {
                        $fb = '';
                        $fc = explode('_FFBB_', $campaignName)[1];


                    }

                    foreach ($IdPixelFB as $keyIdPixelFB => $valueIdPixelFB) {
                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://graph.facebook.com/v18.0/' . $keyIdPixelFB . '/events');

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => array('data' => '[
       {
         "event_name": "Purchase",
         "event_time": ' . time() . ',
         "user_data": {
           "fn": [
             "' . hash('sha256', trim(strtolower($Registro->getNombre1()))) . '"
           ], 
           "ln": [
             "' . hash('sha256', trim(strtolower($Registro->getApellido1()))) . '"
           ],  
           "em": [
             "' . hash('sha256', trim(strtolower($Usuario->login))) . '"
           ],
           "ph": [
             "' . hash('sha256', trim(strtolower($Pais->prefijoCelular))) . '",
             "' . hash('sha256', trim(strtolower($Registro->celular))) . '"
           ],
           "client_ip_address": "' . $Usuario->dirIp . '",
           "client_user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n 537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
           "fbc": "' . $fc . '",
           "fbp": "' . $fb . '"
         },
         "custom_data": {
           "currency": "' . $Usuario->moneda . '",
           "value": ' . $TransaccionProducto->valor . ',
           "contents": [
             {
               "id": "' . $TransaccionProducto->transproductoId . '",
             }
           ]
         },
         "action_source": "website"
       }
     ]', 'access_token' => $valueIdPixelFB)
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }

                } catch (Exception $e) {

                }

            }

        }

        if ($Usuario->mandante == '0' && $Usuario->paisId == 68) {
            $IdPixelFB = '';
            $TokenPixelFB = '';
            $eventNameFTD='Purchase';
            $eventNameDeposit='deposit';
            switch ($Usuario->mandante) {
                case '0':
                    switch ($Usuario->paisId) {
                        case '68':
                            $IdPixelFB = '944417270905964';
                            $TokenPixelFB = 'EAAIXpeaxAMABO7kMZAmgGzmlBPUmZAKZBDeAmYZAVwpUb4ZClRWYzcOf8nKTnJ7dyiwE5lKHe7di090reNn6LCHg2sWvnYmPWXMcIGL2FIi8ZCRPGCTVxePghZCjzS3PS64bJQ8VyYQ55wJWDJqRVOuhIdamROwGjht6x3S9kCIyzt8l00qOZAsNejasagAtZApj03QZDZD';

                            break;

                    }
                    break;
            }
            if (intval($detalleDepositos) == 0 && ($Usuario->mandante != 18 || ($Usuario->mandante == 18 && $Usuario->paisId != 146))) {
                $Pais = new Pais($Usuario->paisId);

                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    $fb = '';
                    $fc = '';

                    if ($campaignName != '' && strpos($campaignName, 'fb.') !== false) {
                        $fb = '';
                        $fc = explode('_FFBB_', $campaignName)[1];


                    }

                    try {


// Inicializar la clase CurlWrapper
                        $curl = new CurlWrapper('https://graph.facebook.com/v18.0/' . $IdPixelFB . '/events');

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array('data' => '[
       {
         "event_name": "' .$eventNameFTD. '",
         "event_time": ' . time() . ',
         "user_data": {
           "fn": [
             "' . hash('sha256', trim(strtolower($Registro->getNombre1()))) . '"
           ], 
           "ln": [
             "' . hash('sha256', trim(strtolower($Registro->getApellido1()))) . '"
           ],  
           "em": [
             "' . hash('sha256', trim(strtolower($Usuario->login))) . '"
           ],
           "ph": [
             "' . hash('sha256', trim(strtolower($Pais->prefijoCelular))) . '",
             "' . hash('sha256', trim(strtolower($Registro->celular))) . '"
           ],
           "client_ip_address": "' . $Usuario->dirIp . '",
           "client_user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n 537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
           "fbc": "' . $fc . '",
           "fbp": "' . $fb . '"
         },
         "custom_data": {
           "currency": "' . $Usuario->moneda . '",
           "value": ' . $TransaccionProducto->valor . ',
           "contents": [
             {
               "id": "' . $TransaccionProducto->transproductoId . '",
             }
           ]
         },
         "action_source": "website"
       }
     ]', 'access_token' => $TokenPixelFB)
                        ]);

// Ejecutar la solicitud
                        $response = $curl->execute();


                    } catch (Exception $e) {

                    }


                } catch (Exception $e) {

                }

            }
            if ( $Usuario->mandante != 18 || ($Usuario->mandante == 18 && $Usuario->paisId != 146) ) {
                $Pais = new Pais($Usuario->paisId);

                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    $fb = '';
                    $fc = '';

                    if ($campaignName != '' && strpos($campaignName, 'fb.') !== false) {
                        $fb = '';
                        $fc = explode('_FFBB_', $campaignName)[1];


                    }

                    try {


// Inicializar la clase CurlWrapper
                        $curl = new CurlWrapper('https://graph.facebook.com/v18.0/' . $IdPixelFB . '/events');

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array('data' => '[
       {
         "event_name": "' .$eventNameDeposit. '",
         "event_time": ' . time() . ',
         "user_data": {
           "fn": [
             "' . hash('sha256', trim(strtolower($Registro->getNombre1()))) . '"
           ], 
           "ln": [
             "' . hash('sha256', trim(strtolower($Registro->getApellido1()))) . '"
           ],  
           "em": [
             "' . hash('sha256', trim(strtolower($Usuario->login))) . '"
           ],
           "ph": [
             "' . hash('sha256', trim(strtolower($Pais->prefijoCelular))) . '",
             "' . hash('sha256', trim(strtolower($Registro->celular))) . '"
           ],
           "client_ip_address": "' . $Usuario->dirIp . '",
           "client_user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n 537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
           "fbc": "' . $fc . '",
           "fbp": "' . $fb . '"
         },
         "custom_data": {
           "currency": "' . $Usuario->moneda . '",
           "value": ' . $TransaccionProducto->valor . ',
           "contents": [
             {
               "id": "' . $TransaccionProducto->transproductoId . '",
             }
           ]
         },
         "action_source": "website"
       }
     ]', 'access_token' => $TokenPixelFB)
                        ]);

// Ejecutar la solicitud
                        $response = $curl->execute();


                    } catch (Exception $e) {

                    }


                } catch (Exception $e) {

                }

            }
            if (intval($detalleDepositos) == 0 && ($Usuario->mandante == 18 && $Usuario->paisId != 146)) {
                $Pais = new Pais($Usuario->paisId);

                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    $fb = '';
                    $fc = '';

                    if ($campaignName != '' && strpos($campaignName, 'fb.') !== false) {
                        $fb = '';
                        $fc = explode('_FFBB_', $campaignName)[1];


                    }

                    try {


// Inicializar la clase CurlWrapper
                        $curl = new CurlWrapper('https://graph.facebook.com/v18.0/' . $IdPixelFB . '/events');

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array('data' => '[
       {
         "event_name": "Purchase",
         "event_time": ' . time() . ',
         "user_data": {
           "fn": [
             "' . hash('sha256', trim(strtolower($Registro->getNombre1()))) . '"
           ], 
           "ln": [
             "' . hash('sha256', trim(strtolower($Registro->getApellido1()))) . '"
           ],  
           "em": [
             "' . hash('sha256', trim(strtolower($Usuario->login))) . '"
           ],
           "ph": [
             "' . hash('sha256', trim(strtolower($Pais->prefijoCelular))) . '",
             "' . hash('sha256', trim(strtolower($Registro->celular))) . '"
           ],
           "client_ip_address": "' . $Usuario->dirIp . '",
           "client_user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n 537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
           "fbc": "' . $fc . '",
           "fbp": "' . $fb . '"
         },
         "custom_data": {
           "currency": "' . $Usuario->moneda . '",
           "value": ' . $TransaccionProducto->valor . ',
           "contents": [
             {
               "id": "' . $TransaccionProducto->transproductoId . '",
             }
           ]
         },
         "action_source": "website"
       }
     ]', 'access_token' => $TokenPixelFB)
                        ]);

// Ejecutar la solicitud
                        $response = $curl->execute();


                    } catch (Exception $e) {

                    }


                } catch (Exception $e) {

                }

            }

        }


        if ($Usuario->mandante == '8') {

            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://ctag.containermedia.net/api/s2s/secure/?id=64da966b633b177007960afe&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }
        if ($Usuario->mandante == '14') {

            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://ctag.containermedia.net/api/s2s/secure/?id=64da9822633b177007960b25&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }
        if ($Usuario->mandante == '23') {

            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://ctag.containermedia.net/api/s2s/secure/?id=65e71e5174551688a643601c&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '13') {

            try {
                $Pais = new Pais($Usuario->paisId);

                $curl = new CurlWrapper('https://ctag.containermedia.net/api/s2s/secure/?id=647661cd553bd9ff77d90e6b&uuid=' . $Registro->afiliadorId . '&afid=' . $Registro->afiliadorId . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }


        if ($Usuario->paisId == '173') {

            if (true) {
                $campaignName = 'Deposito-Directa';
                $campaignSource = 'Directa';
                $campaignContent = 'Deposito';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {

                    $campaignName = $tvalue;

                }

                $eventCategory = 'Deposito';
                $eventAction = 'Deposito Pagado';
                $eventLabel = '';
                $UACode = "UA-86923934-1";

                try {
                    //$events = new AnalyticsEvent($UACode, 'doradobet.com');
                    //$events->trackEvent($eventCategory, $eventAction, $eventLabel, 1, $campaignName, $campaignSource, $campaignContent);

                } catch (Exception $e) {

                }
            }

            if (intval($detalleDepositos) == 0 && $tvalue->vs_cid != null && $tvalue->vs_cid != '') {
                try {
                    $curl = new CurlWrapper('https://www.pixelhere.com/et/event.php?advertiser=145758&cid=' . $tvalue->vs_cid . '&id=6cad6f&variable=' . $Usuario->usuarioId);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            } else {

                if ($tvalue->vs_cid != null && $tvalue->vs_cid != '') {
                    try {
                        $curl = new CurlWrapper('https://www.pixelhere.com/et/event.php?advertiser=145758&cid=' . $tvalue->vs_cid . '&id=2d8e55&variable=' . $Usuario->usuarioId);

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_CUSTOMREQUEST => 'GET'
                        ]);

                        $response = $curl->execute();

                    } catch (Exception $e) {

                    }

                }
            }

        }

        if ($Usuario->mandante == '23') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=9069&aid=1251&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }


            try {
                $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=9070&aid=15652&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '27') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=1001987&aid=3151&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }


            try {
                $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=1001988&aid=3151&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }

        if ($Usuario->mandante == '0') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=3279&aid=1251&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }
            try {
                $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=3280&aid=1251&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
            try {
                $curl = new CurlWrapper('https://tagpro.adpromedia.net/api/admin/deposits?id=2&userid=' . $Usuario->usuarioId . '&value=' . $UsuarioRecarga->valor . '&orderid=' . $UsuarioRecarga->recargaId . '');


            } catch (Exception $e) {

            }
            try {
                $curl = new CurlWrapper('https://tagpro.adpromedia.net/api/admin/deposits?id=fDEoDrUrRhgfxaWd9XdLyi&userid=' . $Usuario->usuarioId . '&value=' . $UsuarioRecarga->valor . '&orderid=' . $UsuarioRecarga->recargaId . '');


            } catch (Exception $e) {

            }

        }
        if ($Usuario->mandante == '14') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=6468&aid=1565&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }
            try {
                $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=6469&aid=15652&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }


        }


        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=3290&aid=1252&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }
            try {
                $curl = new CurlWrapper('https://a.sportradarserving.com/pixel_s2s//?id=3291&aid=1252&auid=' . $Usuario->usuarioId . '&dval=' . $UsuarioRecarga->valor . '&cur=' . $Usuario->moneda);

// Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
            try {
                $curl = new CurlWrapper('https://tagpro.adpromedia.net/api/admin/deposits?id=3&userid=' . $Usuario->usuarioId . '&value=' . $UsuarioRecarga->valor . '&orderid=' . $UsuarioRecarga->usuarioId . '');


            } catch (Exception $e) {

            }

        }

        if ($Usuario->mandante == '8') {

            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://ssl.connextra.com/universalTag?client=Ecuabet&id=178748&page=firstdepositconfirm&ac=' . $Usuario->usuarioId . '&val=' . $UsuarioRecarga->valor);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }

        }


        if ($Usuario->mandante == '14') {

            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://zz.connextra.com/dcs/tagController/tag/a940b2d4a9d6/firstdepositconfirm?AccountID=' . $Usuario->usuarioId . '&Stake=' . $UsuarioRecarga->valor . '&Currency=' . $Usuario->moneda);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }

        }
        if ($Usuario->mandante == '13') {

            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://zz.connextra.com/dcs/tagController/tag/c1499718faca/firstdepositconfirm?AccountID=' . $Usuario->usuarioId . '&Stake=' . $UsuarioRecarga->valor . '&Currency=' . $Usuario->moneda);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }

        }
        if ($Usuario->mandante == '14') {

            if (intval($detalleDepositos) == 0) {
                $entroHike = false;
                try {
                    $campaignName = '';
                    $campaignName2 = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $tvalue = json_decode($tvalue);

                        if ($tvalue->vs_utm_campaign != '') {
                            $campaignName = $tvalue->vs_utm_campaign;
                        }
                        if ($tvalue->vs_utm_campaign2 != '') {
                            $campaignName2 = $tvalue->vs_utm_campaign2;
                        }
                        if ($tvalue->vs_utm_source != '') {
                            $campaignSource = $tvalue->vs_utm_source;
                        }
                        if ($tvalue->vs_utm_content != '') {
                            $campaignContent = $tvalue->vs_utm_content;
                        }

                    }

                    if ($campaignName != '' && strpos($campaignName, 'hikeBR') !== false) {
                        $campaignName = explode('_', $campaignName)[1];
                        $campaignContent = explode('_', $campaignContent)[1];
                        $entroHike = true;


// Inicializar la clase CurlWrapper
                        $curl = new CurlWrapper('https://tracking.linksegurohike.com/aff_lsr?offer_id=19&adv_unique1=' . $UsuarioRecarga->recargaId . '&transaction_id=' . $campaignContent . '&amount=' . $UsuarioRecarga->valor);

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET'
                        ]);

// Ejecutar la solicitud
                        $response = $curl->execute();


                    } elseif ($campaignName2 != '' && strpos($campaignName2, 'hikeBR') !== false) {
                        $campaignName2 = explode('_', $campaignName2)[1];
                        $campaignContent = explode('_', $campaignContent)[1];
                        $entroHike = true;


// Inicializar la clase CurlWrapper
                        $curl = new CurlWrapper('https://tracking.linksegurohike.com/aff_lsr?offer_id=19&adv_unique1=' . $UsuarioRecarga->recargaId . '&transaction_id=' . $campaignContent . '&amount=' . $UsuarioRecarga->valor);

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET'
                        ]);

// Ejecutar la solicitud
                        $response = $curl->execute();


                    } else {
                        if ($campaignName != '' && strpos($campaignName, 'HikeLoto') !== false) {
                            $campaignName = explode('_', $campaignName)[1];
                            $campaignContent = explode('afclick', $campaignContent)[1];
                            $entroHike = true;


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://tracking.linksegurohike.com/aff_lsr?offer_id=19&adv_unique1=' . $UsuarioRecarga->recargaId . '&transaction_id=' . $campaignContent . '&amount=' . $UsuarioRecarga->valor);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        }

                    }


                } catch (Exception $e) {

                }

                if (!$entroHike) {

                    try {
                        $campaignName = '';
                        $campaignSource = '';
                        $campaignContent = '';

                        $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                        $SitioTracking = new SitioTracking();
                        $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                        $sitiosTracking = json_decode($sitiosTracking);

                        $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                        if ($tvalue != '') {
                            $tvalue = json_decode($tvalue);

                            if ($tvalue->vs_utm_campaign != '') {
                                $campaignName = $tvalue->vs_utm_campaign;
                            }
                            if ($tvalue->vs_utm_source != '') {
                                $campaignSource = $tvalue->vs_utm_source;
                            }
                            if ($tvalue->vs_utm_content != '') {
                                $campaignContent = $tvalue->vs_utm_content;
                            }

                        }

                        if ($campaignName != '' && strpos($campaignName, 'hikeBR') !== false) {
                            $campaignName = explode('_', $campaignName)[1];
                            $campaignContent = explode('_', $campaignContent)[1];
                            $entroHike = true;


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://tracking.linksegurohike.com/aff_lsr?offer_id=19&adv_unique1=' . $UsuarioRecarga->recargaId . '&transaction_id=' . $campaignContent . '&amount=' . $UsuarioRecarga->valor);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } else {
                            if ($campaignName != '' && strpos($campaignName, 'HikeLoto') !== false) {
                                $campaignName = explode('_', $campaignName)[1];
                                $campaignContent = explode('afclick', $campaignContent)[1];
                                $entroHike = true;


// Inicializar la clase CurlWrapper
                                $curl = new CurlWrapper('https://tracking.linksegurohike.com/aff_lsr?offer_id=19&adv_unique1=' . $UsuarioRecarga->recargaId . '&transaction_id=' . $campaignContent . '&amount=' . $UsuarioRecarga->valor);

// Configurar opciones
                                $curl->setOptionsArray([
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 30,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'GET'
                                ]);

// Ejecutar la solicitud
                                $response = $curl->execute();


                            }

                        }


                    } catch (Exception $e) {

                    }
                }

            }


        }


        if ($Usuario->mandante == '14') {

            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    if ($campaignName != '') {
                        $campaignName = explode('_', $campaignName)[1];

                        $curl = new CurlWrapper('https://offers-gohknetwork.affise.com/postback?clickid=' . $campaignName . '&custom_field1=' . $UsuarioRecarga->recargaId . '&goal=conversao&custom_field2=' . $UsuarioRecarga->valor . '&Currency=' . $Usuario->moneda . '&secure=ece961cd4cb18b55a3431e3412f56fdd&status=1');

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_CUSTOMREQUEST => 'GET'
                        ]);

                        $response = $curl->execute();
                    }


                } catch (Exception $e) {

                }

            }

            if (intval($detalleDepositos) >= 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }
                    if ($campaignName != '') {
                        $campaignName = explode('_', $campaignName)[1];

                        $curl = new CurlWrapper('https://offers-gohknetwork.affise.com/postback?clickid=' . $campaignName . '&custom_field1=' . $UsuarioRecarga->recargaId . '&goal=recorrencia&custom_field2=' . $UsuarioRecarga->valor . '&Currency=' . $Usuario->moneda . '&secure=ece961cd4cb18b55a3431e3412f56fdd&status=1');

// Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_CUSTOMREQUEST => 'GET'
                        ]);

                        $response = $curl->execute();
                    }

                } catch (Exception $e) {

                }

            }
        }

        if ($Usuario->mandante == '14') {
            if (intval($detalleDepositos) == 0 || true) {


                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $tvalue = json_decode($tvalue);

                        if ($tvalue->vs_utm_campaign != '') {
                            $campaignName = $tvalue->vs_utm_campaign;
                        }
                        if ($tvalue->vs_utm_source != '') {
                            $campaignSource = $tvalue->vs_utm_source;
                        }
                        if ($tvalue->vs_utm_content != '') {
                            $campaignContent = $tvalue->vs_utm_content;
                        }

                    }

                    if ($campaignName != '') {

                        try {
                            $Pais = new Pais($Usuario->paisId);


                            $curl = new CurlWrapper('https://offers-gohknetwork.affise.com/postback?clickid=' . $campaignName . '&custom_field1=' . $UsuarioRecarga->recargaId . '&goal=conversao&custom_field2=' . $UsuarioRecarga->valor . '&Currency=' . $Usuario->moneda . '&secure=ece961cd4cb18b55a3431e3412f56fdd&status=1');

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }
            }
        }


        if ($Usuario->mandante == '17') {
            if (intval($detalleDepositos) == 0) {
                try {

                    $array = array(
                        'tags' => array("first-paid", "paid"),
                        'name' => $Usuario->nombre,
                        "email" => $Usuario->login,
                        "phone" => $Registro->celular,
                        "aff" => $Registro->afiliadorId,
                        "transaction_id" => $TransaccionProducto->transproductoId,
                        "transaction_value" => $TransaccionProducto->valor,
                        "creation_date" => date('Y-m-d H:i:s')
                    );

                    $payload = json_encode($array);
                    $curl = new CurlWrapper('https://n8n.casamilbets.com/webhook/mkt_casamilbets_com_depositos');


// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_RETURNTRANSFER => true,
                        CURLINFO_HEADER_OUT => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTPHEADER => [
                            'Content-Type' => 'application/json',
                            'Content-Length' => strlen($payload)
                        ],
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }
            } else {
                try {

                    $array = array(
                        'tags' => array("paid"),
                        'name' => $Usuario->nombre,
                        "email" => $Usuario->login,
                        "phone" => $Registro->celular,
                        "aff" => $Registro->afiliadorId,
                        "transaction_id" => $TransaccionProducto->transproductoId,
                        "transaction_value" => $TransaccionProducto->valor,
                        "creation_date" => date('Y-m-d H:i:s')
                    );

                    $payload = json_encode($array);
                    $curl = new CurlWrapper('https://n8n.casamilbets.com/webhook/mkt_casamilbets_com_depositos');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, [
                        'Content-Type' => 'application/json',
                        'Content-Length' => strlen($payload)
                    ]);

                    (curl_exec($curl));
                    curl_close($curl);
                } catch (Exception $e) {

                }
            }


        }
        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $curl = new CurlWrapper('https://clickserv.sitescout.com/conv/19682e75b9fdf91b?pb=' . $Usuario->usuarioId);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();

                } catch (Exception $e) {

                }

            }
        }

        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($campaignName != '' && strpos($campaignName, 'ADFORM') !== false) {
                        $campaignName = explode('ADFORM', $campaignName)[1];

                        $array1 = array(
                            "name" => "EcuaBet_FirstDeposit",
                            "identity" => array(
                                "cookieId" => ($campaignName),
                            ),
                            "userContext" => array(
                                "userAgent" =>
                                    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/\n" .
                                    '537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",',
                                "userIp" => explode(',', $Usuario->dirIp)[0],
                            ),
                            "variables" => array(
                                "sales" => floatval($UsuarioRecarga->valor),
                                "sv1" => $Usuario->usuarioId
                            )


                        );
                        $array = array();
                        array_push($array, $array1);


                        try {


                            $curl = new CurlWrapper('https://a2.adform.net/v2/sitetracking/3165968/trackingpoints/');


// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => json_encode($array),
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_CONNECTTIMEOUT => 30,
                                CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
                                CURLOPT_HTTPHEADER => [
                                    'Content-Type' => 'application/json',
                                    'Content-Length' => strlen($payload)
                                ],
                            ]);

                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }
        }

        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($campaignName != '' && strpos($campaignName, '_GENIUS_') !== false) {
                        $campaignName = explode('_GENIUS_', $campaignName)[1];

                        $array1 = array();
                        $array = array();
                        array_push($array, $array1);


                        try {

                            $curl = new CurlWrapper('https://event.fanhub.geniussports.com/track-event?evtGuid=01481196-de3e-4b6d-bb30-9ae998fd5ecf&trkGuid=6597e0bd-6aca-439a-b755-a4bab2f3c5e2&redir=https://ssa.media.geniussports.com/dsp/attribution?token=' . $campaignName);


// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => json_encode($array),
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_CONNECTTIMEOUT => 30,
                                CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
                                CURLOPT_HTTPHEADER => [
                                    'Content-Type' => 'application/json',
                                    'Content-Length' => strlen($payload)
                                ],
                            ]);

                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }
        }


        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($campaignName != '' && strpos($campaignName, '_TERRA_') !== false) {

                        $campaignName = explode('_TERRA_', $campaignName)[1];
                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('http://www.pbterra.com/name/QuotaMedia/at?subid_short=' . $campaignName . '&atpay=' . $UsuarioRecarga->valor);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }
        }

        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($campaignName != '' && strpos($campaignName, '_MGID_') !== false) {

                        $campaignName = explode('_MGID_', $campaignName)[1];
                        $campaignName = explode('","', $campaignName)[0];
                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://a.mgid.com/postback?c=' . $campaignName . '&e=deposito718831&r=' . $UsuarioRecarga->valor);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();

                        } catch (Exception $e) {

                        }
                    }

                    if ($campaignName != '' && strpos($campaignName, '_ROLLER_') !== false) {

                        $campaignName = explode('_ROLLER_', $campaignName)[1];
                        $campaignName = explode('","', $campaignName)[0];
                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://eu.rollerads.com/conversion/aid/87529/c03e37e5c4dcae77?click_id=' . $campaignName);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();

                        } catch (Exception $e) {

                        }
                    }

                } catch (Exception $e) {

                }

            }
        }

        if ($Usuario->mandante == '0' || $Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($campaignName != '' && strpos($campaignName, '_PROPELLER_') !== false) {

                        $campaignName = explode('_PROPELLER_', $campaignName)[1];
                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('http://ad.propellerads.com/conversion.php?aid=3520054&pid=&tid=129304&visitor_id=' . $campaignName . '&payout=' . $UsuarioRecarga->valor);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }
        }

        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($campaignName != '' && strpos($campaignName, '_POPCASH_') !== false) {

                        $campaignName = explode('_POPCASH_', $campaignName)[1];
                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://ct.popcash.net/click?aid=278905&type=2&clickid=' . $campaignName . '&payout=' . $UsuarioRecarga->valor);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }
        }


        if ($Usuario->mandante == '8') {
            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($campaignName != '' && strpos($campaignName, '_TABOOLA_') !== false) {

                        $campaignName = explode('_TABOOLA_', $campaignName)[1];
                        try {


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://trc.taboola.com/actions-handler/log/3/s2s-action?click-id=' . $campaignName . '&name=ftd_ec&revenue=' . $TransaccionProducto->valor . '&currency=' . $Usuario->moneda . '&orderid=' . $UsuarioRecarga->recargaId);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }
        }

        if ($Usuario->mandante == '0' && ($Usuario->paisId == '46' || $Usuario->paisId == '60')) {
            if (intval($detalleDepositos) == 0) {
                try {
                    $campaignName = '';
                    $campaignSource = '';
                    $campaignContent = '';

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($tvalue != '') {
                        $campaignName = $tvalue;

                    }

                    if ($campaignName != '' && strpos($campaignName, '_TABOOLA_') !== false) {

                        $campaignName = explode('_TABOOLA_', $campaignName)[1];
                        $campaignName = explode('","', $campaignName)[0];
                        try {

                            $name = '';

                            switch ($Usuario->paisId) {
                                case '46':
                                    $name = 'ftd_ch';

                                    break;
                                case '60':
                                    $name = 'ftd_cr';

                                    break;
                                case '66':
                                    $name = '';

                                    break;
                                case '94':
                                    $name = 'ftd_gt';

                                    break;
                            }


// Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper('https://trc.taboola.com/actions-handler/log/3/s2s-action?click-id=' . $campaignName . '&name=' . $name . '&revenue=' . $TransaccionProducto->valor . '&currency=' . $Usuario->moneda . '&orderid=' . $UsuarioRecarga->recargaId);

// Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET'
                            ]);

// Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }
                    }


                } catch (Exception $e) {

                }

            }
        }


        if ($Usuario->mandante == '27' && $Usuario->paisId == '68') {
            if (intval($detalleDepositos) == 0) {

                try {
                    $Pais = new Pais($Usuario->paisId);

                    $curl = new CurlWrapper('https://ctag.containermedia.net/api/s2s/secure/?id=684861aa6c1c994f93a4c09e&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();


                } catch (Exception $e) {

                }
            }
        }

        if ($Usuario->mandante == '27'  && $Usuario->paisId == '68') {

            try {
                $campaignName = '';
                $campaignSource = '';
                $campaignContent = '';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {
                    $tvalue = json_decode($tvalue);

                    if ($tvalue->vs_utm_campaign != '') {
                        $campaignName = $tvalue->vs_utm_campaign;
                    }
                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    if ($tvalue->vs_utm_content != '') {
                        $campaignContent = $tvalue->vs_utm_content;
                    }

                }

                if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                    $campaignName = explode('_BQ_', $campaignName)[1];

                }


            } catch (Exception $e) {

            }

            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=684861a46c1c994f93a4bed1&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor . '&campaign=' . $campaignName));

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }


        if ($Usuario->mandante == '27' && $Usuario->paisId == '94') {
            if (intval($detalleDepositos) == 0) {

                try {
                    $Pais = new Pais($Usuario->paisId);

                    $curl = new CurlWrapper('https://ctag.containermedia.net/api/s2s/secure/?id=6848615c6c1c994f93a4a684&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor);

// Configurar opciones
                    $curl->setOptionsArray([
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ]);

                    $response = $curl->execute();


                } catch (Exception $e) {

                }
            }
        }

        if ($Usuario->mandante == '27'  && $Usuario->paisId == '94') {

            try {
                $campaignName = '';
                $campaignSource = '';
                $campaignContent = '';

                $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "transaccion_producto","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $TransaccionProducto->transproductoId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $SitioTracking = new SitioTracking();
                $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                $sitiosTracking = json_decode($sitiosTracking);

                $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                if ($tvalue != '') {
                    $tvalue = json_decode($tvalue);

                    if ($tvalue->vs_utm_campaign != '') {
                        $campaignName = $tvalue->vs_utm_campaign;
                    }
                    if ($tvalue->vs_utm_source != '') {
                        $campaignSource = $tvalue->vs_utm_source;
                    }
                    if ($tvalue->vs_utm_content != '') {
                        $campaignContent = $tvalue->vs_utm_content;
                    }

                }

                if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                    $campaignName = explode('_BQ_', $campaignName)[1];

                }


            } catch (Exception $e) {

            }

            try {
                $Pais = new Pais($Usuario->paisId);


                // Inicializar la clase CurlWrapper
                $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=684861596c1c994f93a4a570&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $this->Unaccent(str_replace(' ', '', str_replace('ú', 'u', $Pais->paisNom))) . '&tid=' . $UsuarioRecarga->recargaId . '&value=' . $UsuarioRecarga->valor . '&campaign=' . $campaignName));

                // Configurar opciones
                $curl->setOptionsArray([
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);

                $response = $curl->execute();

            } catch (Exception $e) {

            }
        }



    }
}

