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
use Backend\dto\BonoLog;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\PreUsuarioSorteo;
use Backend\dto\SorteoDetalle;
use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\CategoriaProducto;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\TransjuegoInfo;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioTorneo;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\dao\TransjuegoInfoDAO;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Exception;




require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');
ini_set("display_errors", "off");

/* asigna valores a variables según la conexión y parámetros existentes. */
$_ENV["enabledConnectionGlobal"] = 1;


for($i=0;$i<10;$i++) {
    $message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#dev2' > /dev/null & ");


    $hour = date('H');
    if (intval($hour) == 0) {
        sleep(900);
    }

    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='TORNEO'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));
    if ($_ENV['debug']) {

        $fechaL1 = '2022-11-17 18:55:00';
        $fechaL2 = '2022-11-17 18:55:59';
    } else {

        if ($fechaL1 >= date('Y-m-d H:i:00')) {
            exit();
        }
        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='TORNEO';

";

        $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
        $transaccion->commit();
    }

    $message = '*Corriendo Torneos Casino2*: ' . $fechaL1;
#exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . date('Y-m-d H:i:s');
    $fp = fopen(__DIR__ . '/logs/Slog_' . date("Y-m-d") . '.log', 'a');
//fwrite($fp, $log);
//fclose($fp);

    if (true) {

        $Tipos = array(
            'CASINO', 'LIVECASINO', 'VIRTUAL'
        );

        foreach ($Tipos as $tipo) {


            $TorneoInterno = new TorneoInterno();

            $rules = [];
            array_push($rules, array("field" => "torneo_interno.mandante", "data" => "19", "op" => "ne"));

            array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => $fechaL1, "op" => "le"));
            array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => $fechaL2, "op" => "ge"));

            if ($_ENV['debug']) {
                array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => 2009, "op" => "eq"));

            }
            if ($_REQUEST['test'] == '2') {
                array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => 3182, "op" => "eq"));

            }
            switch ($tipo) {
                case 'CASINO':
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "2", "op" => "eq"));

                    break;
                case 'LIVECASINO':
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "3", "op" => "eq"));

                    break;
                case 'VIRTUAL':
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "4", "op" => "eq"));

                    break;

            }

            if ($_REQUEST['test'] == '2') {

                print_r($rules);

            }
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 1000;

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $torneos = $TorneoInterno->getTorneosCustom(" torneo_interno.* ", "torneo_interno.torneo_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);


            $torneos = json_decode($torneos);

            $ActivacionTorneos = true;

            $ArrayTransaction = array('1');


            foreach ($torneos->data as $key => $value) {
                try {
                    if ($ActivacionTorneos) {

                        $condRank = array();
                        $condPaises = array();
                        $needSubscribe = false;
                        $condIdGame = array();
                        $condIdGameGeneral = array();
                        $condIdCategory = array();
                        $condIdProvider = array();
                        $condIdSubProvider = array();
                        print_r('condIdGame');
                        print_r($condIdGame);

                        $rules = array();

                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.mandante", "data" => $value->{"torneo_interno.mandante"}, "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $jsonfiltro = json_encode($filtro);

                        $TorneoDetalle = new TorneoDetalle();

                        $torneosdetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.orden", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, 'torneo_detalle.torneodetalle_id');

                        $torneosdetalles = json_decode($torneosdetalles);


                        foreach ($torneosdetalles->data as $key2 => $value2) {

                            switch ($value2->{"torneo_detalle.tipo"}) {
                                case "RANK":

                                    $arrayT = array(
                                        'valor' => $value2->{"torneo_detalle.valor"},
                                        'valor2' => $value2->{"torneo_detalle.valor2"},
                                        'valor3' => $value2->{"torneo_detalle.valor3"}
                                    );

                                    if ($condRank[$value2->{"torneo_detalle.moneda"}] == null) {
                                        $condRank[$value2->{"torneo_detalle.moneda"}] = array();
                                    }

                                    array_push($condRank[$value2->{"torneo_detalle.moneda"}], $arrayT);


                                    break;

                                case "USERSUBSCRIBE":


                                    if ($value2->{"torneo_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":


                                    if ($value2->{"torneo_detalle.valor"} == 1) {
                                        $needSubscribe = true;
                                    }

                                    break;


                                case "CONDPAISUSER":


                                    array_push($condPaises, $value2->{"torneo_detalle.valor"});

                                    break;

                                default:

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME') && $value2->{"torneo_detalle.tipo"} !== 'CONDGAME_ALL') {

                                        array_push($condIdGame, explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1]);

                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDCATEGORY')) {

                                        array_push($condIdCategory, explode("CONDCATEGORY", $value2->{"torneo_detalle.tipo"})[1]);

                                    }


                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER') && $value2->{"torneo_detalle.tipo"} !== 'CONDPROVIDER_ALL') {

                                        array_push($condIdProvider, explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1]);

                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER') && $value2->{"torneo_detalle.tipo"} !== 'CONDSUBPROVIDER_ALL') {

                                        array_push($condIdSubProvider, explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1]);

                                    }

                                    break;
                            }

                        }

                        if (oldCount($condIdCategory) > 0 && !in_array('ALL', $condIdCategory)) {


                            $sqlApuestasDeportivasUsuarioDiaCierre = "


SELECT producto.producto_id
FROM producto
         INNER JOIN categoria_producto
                    ON (categoria_producto.producto_id = producto.producto_id)

where categoria_producto.categoria_id IN (" . implode(',', $condIdCategory) . ")
";

                            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                                try {


                                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                                    $connDB5 = null;

                                    if ($_ENV['ENV_TYPE'] == 'prod') {

                                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                            , array(
                                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                            )
                                        );
                                    } else {
                                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                        );
                                    }


                                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                                    $connDB5->exec("set names utf8");


                                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                                    }


                                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                    }
                                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                    }

                                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                    }
                                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                        $connDB5->exec("SET NAMES utf8mb4");
                                    }

                                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                                } catch (\Exception $e) {
                                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                                }

                            }

                            $dataProductos = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);


                            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                                $connDB5 = null;
                                $_ENV["connectionGlobal"]->setConnection($connOriginal);
                            }

                            if ($dataProductos !== NULL && $dataProductos !== "" && $dataProductos[0] !== NULL) {

                                foreach ($dataProductos as $key4 => $datanum) {

                                    array_push($condIdGameGeneral, $datanum->{"producto.producto_id"});

                                }
                            }
                        }
                        $BonoInterno = new BonoInterno();

                        $sqlCondSubproveedor = '';

                        if (oldCount($condIdSubProvider) > 0 && !in_array('ALL', $condIdSubProvider)) {
                            $sqlCondSubproveedor = "AND  producto.subproveedor_id IN (" . implode(',', $condIdSubProvider) . ") ";
                        }
                        $sqlCondProveedor = '';

                        if (oldCount($condIdProvider) > 0 && !in_array('ALL', $condIdProvider)) {
                            $sqlCondProveedor = "AND  producto.proveedor_id IN (" . implode(',', $condIdProvider) . ")";
                        }

                        $sqlCondGame = '';

                        if (oldCount($condIdGame) > 0 && !in_array('ALL', $condIdSubProvider)) {
                            $sqlCondGame = "HAVING  producto_mandante.prodmandante_id IN (" . implode(',', $condIdGame) . ")";
                        }
                        if (oldCount($condIdGameGeneral) > 0 && !in_array('ALL', $condIdGameGeneral)) {
                            $sqlCondGame = "HAVING  producto.producto_id IN (" . implode(',', $condIdGameGeneral) . ")";
                        }
                        $sqlCondPaises = '';

                        if (oldCount($condPaises) > 0) {
                            $sqlCondPaises = "AND  usuario_mandante.pais_id IN (" . implode(',', $condPaises) . ") ";
                        }


                        $sqlApuestasDeportivasUsuarioDiaCierre = "

select transjuego_log.transjuegolog_id,transjuego_log.transaccion_id,producto.subproveedor_id,transaccion_juego.usuario_id,transjuego_log.valor,
       producto.producto_id,
       producto.proveedor_id,
       producto.subproveedor_id,
       producto_mandante.prodmandante_id,
       subproveedor.tipo,
       
       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id
       
from transjuego_log
INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
    WHERE 
        subproveedor.tipo = '" . $tipo . "'
      " . $sqlCondSubproveedor . "
      " . $sqlCondProveedor . "
     
      " . $sqlCondPaises . "
       
       
      
      AND  transjuego_log.fecha_crea >= '" . $fechaL1 . "' 
    
    AND transjuego_log.fecha_crea <= '" . $fechaL2 . "' AND transjuego_log.transjuegolog_id NOT IN (" . implode(',', $ArrayTransaction) . ")
and transjuego_log.tipo LIKE 'DEBIT%' and usuario_mandante.mandante='" . $value->{"torneo_interno.mandante"} . "'  " . $sqlCondGame . "
";
                        print_r($sqlApuestasDeportivasUsuarioDiaCierre);
                        if ($_ENV['debug']) {

                            print_r($sqlApuestasDeportivasUsuarioDiaCierre);
                        }


                        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                            /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                            $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                            try {


                                /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                                $connDB5 = null;

                                if ($_ENV['ENV_TYPE'] == 'prod') {

                                    $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                        , array(
                                            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                        )
                                    );
                                } else {
                                    /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                                    $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                    );
                                }


                                /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                                $connDB5->exec("set names utf8");


                                if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                                    $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                                }


                                /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                                if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                }
                                if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                }

                                /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                                if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                }
                                if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                    $connDB5->exec("SET NAMES utf8mb4");
                                }

                                /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                                $_ENV["connectionGlobal"]->setConnection($connDB5);

                            } catch (\Exception $e) {
                                /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                            }

                        }
                        $dataUsuario = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);


                        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                            $connDB5 = null;
                            $_ENV["connectionGlobal"]->setConnection($connOriginal);
                        }


                        if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {

                            foreach ($dataUsuario as $key4 => $datanum) {
                                if ($datanum->{"usuario_mandante.usumandante_id"} == '16') {
                                    print_r($datanum);

                                }

                                try {

                                    $creditosConvert = 0;

                                    $cumpleCondicion = true;

                                    $cumpleCondicionCont = 0;
                                    $cumpleCondicionPais = false;

                                    $condicionesProducto = 0;
                                    $cumpleCondicionProd = false;

                                    $condicionesProveedor = 0;
                                    $cumpleCondicionProv = false;

                                    $condicionesSubProveedor = 0;
                                    $cumpleCondicionSubProv = false;

                                    $condicionesRank = 0;
                                    $cumpleCondicionRank = false;

                                    $valorTicket = floatval($datanum->{"transjuego_log.valor"});


                                    if (oldCount($condPaises) > 0) {
                                        if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                            $cumpleCondicionPais = true;
                                        }
                                        if ($cumpleCondicionPais == false) {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"it_ticket_enc.ticket_id"}}','CONDPAISUSER')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        }
                                        $cumpleCondicionCont++;
                                    }


                                    if (oldCount($condIdGame) > 0) {

                                        if (in_array($datanum->{"producto_mandante.prodmandante_id"}, $condIdGame)) {
                                            $cumpleCondicionProd = true;
                                        }
                                        if ($cumpleCondicionProd == false) {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDGAME')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (oldCount($condIdProvider) > 0) {

                                        if (in_array($datanum->{"producto.proveedor_id"}, $condIdProvider)) {
                                            $cumpleCondicionProv = true;
                                        }
                                        if ($cumpleCondicionProv == false) {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDPROVIDER')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        }
                                        $condicionesProveedor++;
                                    }

                                    if (oldCount($condIdSubProvider) > 0) {

                                        if (in_array($datanum->{"producto.subproveedor_id"}, $condIdSubProvider)) {
                                            $cumpleCondicionSubProv = true;
                                        }
                                        if ($cumpleCondicionSubProv == false) {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDSUBPROVIDER')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        }
                                        $condicionesSubProveedor++;
                                    }


                                    if (oldCount($condRank) > 0) {

                                        foreach ($condRank[$datanum->{"usuario_mandante.moneda"}] as $item) {

                                            if ($valorTicket >= $item["valor"]) {

                                                if ($valorTicket <= $item["valor2"]) {

                                                    $creditosConvert = $item["valor3"];
                                                }
                                            }
                                            $cumpleCondicionRank = true;
                                        }
                                        $condicionesRank++;

                                    }


                                    if ($condicionesProducto > 0 && !$cumpleCondicionProd) {
                                        $cumpleCondicion = false;
                                    }


                                    if ($condicionesProveedor > 0 && !$cumpleCondicionProv) {
                                        $cumpleCondicion = false;
                                    }

                                    if ($condicionesSubProveedor > 0 && !$cumpleCondicionSubProv) {
                                        $cumpleCondicion = false;
                                    }

                                    if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                                        $cumpleCondicion = false;
                                    }

                                    if ($cumpleCondicion) {
                                        if ($creditosConvert == 0) {
                                            $cumpleCondicion = false;
                                        }
                                    }

                                    if ($cumpleCondicion && !$needSubscribe && $creditosConvert > 0) {
                                        array_push($ArrayTransaction, $datanum->{"transjuego_log.transjuegolog_id"});

                                        $rules2 = [];

                                        array_push($rules2, array("field" => "usuario_torneo.usuario_id", "data" => $datanum->{"usuario_mandante.usumandante_id"}, "op" => "eq"));
                                        array_push($rules2, array("field" => "usuario_torneo.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));

                                        $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
                                        $json2 = json_encode($filtro2);

                                        $UsuarioTorneo = new UsuarioTorneo();
                                        $dataUsuarioTorneo = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json2, true, '');


                                        $dataUsuarioTorneo = json_decode($dataUsuarioTorneo);

                                        $dataUsuarioTorneo = $dataUsuarioTorneo->data[0];


                                        if ($dataUsuarioTorneo != null && $dataUsuarioTorneo != '') {
                                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                            $UsuarioTorneo = new UsuarioTorneo($dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"});
                                            $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                            $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $valorTicket);


                                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);


                                            $TransjuegoInfo = new TransjuegoInfo();
                                            $TransjuegoInfo->productoId = $datanum->{"producto_mandante.prodmandante_id"};
                                            $TransjuegoInfo->transaccionId = $datanum->{"transjuego_log.transaccion_id"};
                                            $TransjuegoInfo->transapiId = $datanum->{"transjuego_log.transjuegolog_id"};
                                            $TransjuegoInfo->tipo = "TORNEO";
                                            $TransjuegoInfo->descripcion = $dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"};
                                            $TransjuegoInfo->valor = $creditosConvert;
                                            $TransjuegoInfo->usucreaId = 0;
                                            $TransjuegoInfo->usumodifId = 0;

                                            $title = '';
                                            $messageBody = '';
                                            $tournamentName = $value->{"torneo_interno.nombre"};

                                            $UsuarioMandante = new UsuarioMandante($UsuarioTorneo->getUsuarioId());
                                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                            switch (strtolower($Usuario->idioma)) {
                                                case 'es':
                                                    $title = 'Notificacion';
                                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                    break;
                                                case 'en':
                                                    $title = 'Notification';
                                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$tournamentName} :clap:";
                                                    break;
                                                case 'pt':
                                                    $title = 'Notificação';
                                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                    break;
                                            }

                                            $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
                                            $UsuarioMensaje->isRead = 0;
                                            $UsuarioMensaje->body = $messageBody;
                                            $UsuarioMensaje->msubject = $title;
                                            $UsuarioMensaje->parentId = 0;
                                            $UsuarioMensaje->proveedorId = 0;
                                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                                            $UsuarioMensaje->paisId = 0;
                                            $UsuarioMensaje->fechaExpiracion = '';

                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                                            $mensajesRecibidos = [];
                                            $array = [];

                                            $array["body"] = $messageBody;

                                            array_push($mensajesRecibidos, $array);
                                            $data = array();
                                            $data["messages"] = $mensajesRecibidos;
                                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                                            /* $UsuarioSession = new UsuarioSession();
                                             $rules = [];

                                             array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                             array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                             $filtro = array("rules" => $rules, "groupOp" => "AND");
                                             $json = json_encode($filtro);


                                             $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                             $usuarios = json_decode($usuarios);

                                             $usuariosFinal = [];

                                             foreach ($usuarios->data as $key => $value) {

                                                 $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                 $WebsocketUsuario->sendWSMessage();

                                             }*/
                                            $UsuarioMandante = new UsuarioMandante($datanum->{"usuario_mandante.usumandante_id"});


                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);


                                        } else {


                                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                            $UsuarioTorneo = new UsuarioTorneo();
                                            $UsuarioTorneo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                            $UsuarioTorneo->torneoId = $value->{"torneo_interno.torneo_id"};
                                            $UsuarioTorneo->valor = 0;
                                            $UsuarioTorneo->posicion = 0;
                                            $UsuarioTorneo->valorBase = 0;
                                            $UsuarioTorneo->usucreaId = 0;
                                            $UsuarioTorneo->usumodifId = 0;
                                            $UsuarioTorneo->estado = "A";
                                            $UsuarioTorneo->errorId = 0;
                                            $UsuarioTorneo->idExterno = 0;
                                            $UsuarioTorneo->mandante = $datanum->{"usuario_mandante.mandante"};;
                                            $UsuarioTorneo->version = 0;
                                            $UsuarioTorneo->apostado = 0;
                                            $UsuarioTorneo->codigo = 0;
                                            $UsuarioTorneo->externoId = 0;
                                            $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                            $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $valorTicket);

                                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $idUsuTorneo = $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                                            $TransjuegoInfo = new TransjuegoInfo();
                                            $TransjuegoInfo->productoId = $datanum->{"producto_mandante.prodmandante_id"};
                                            $TransjuegoInfo->transaccionId = $datanum->{"transjuego_log.transaccion_id"};
                                            $TransjuegoInfo->transapiId = $datanum->{"transjuego_log.transjuegolog_id"};
                                            $TransjuegoInfo->tipo = "TORNEO";
                                            $TransjuegoInfo->descripcion = $idUsuTorneo;
                                            $TransjuegoInfo->valor = $creditosConvert;
                                            $TransjuegoInfo->usucreaId = 0;
                                            $TransjuegoInfo->usumodifId = 0;

                                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                                            $UsuarioMandante = new UsuarioMandante($datanum->{"usuario_mandante.usumandante_id"});

                                            $mensajesRecibidos = [];
                                            $array = [];

                                            $array["body"] = $messageBody;

                                            array_push($mensajesRecibidos, $array);
                                            $dataSend = array();
                                            $dataSend["messages"] = $mensajesRecibidos;

                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }

                                    }

                                    if ($cumpleCondicion && $needSubscribe && $creditosConvert > 0) {

                                        $rules2 = [];

                                        array_push($rules2, array("field" => "usuario_torneo.usuario_id", "data" => $datanum->{"usuario_mandante.usumandante_id"}, "op" => "eq"));
                                        array_push($rules2, array("field" => "usuario_torneo.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));

                                        $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
                                        $json2 = json_encode($filtro2);

                                        $UsuarioTorneo = new UsuarioTorneo();
                                        $dataUsuarioTorneo = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json2, true, '');


                                        $dataUsuarioTorneo = json_decode($dataUsuarioTorneo);

                                        $dataUsuarioTorneo = $dataUsuarioTorneo->data[0];


                                        if ($dataUsuarioTorneo != null && $dataUsuarioTorneo != '') {
                                            array_push($ArrayTransaction, $datanum->{"transjuego_log.transjuegolog_id"});
                                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                            $UsuarioTorneo = new UsuarioTorneo($dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"});
                                            $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                            $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $valorTicket);


                                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);


                                            $TransjuegoInfo = new TransjuegoInfo();
                                            $TransjuegoInfo->productoId = $datanum->{"producto_mandante.prodmandante_id"};
                                            $TransjuegoInfo->transaccionId = $datanum->{"transjuego_log.transaccion_id"};
                                            $TransjuegoInfo->transapiId = $datanum->{"transjuego_log.transjuegolog_id"};
                                            $TransjuegoInfo->tipo = "TORNEO";
                                            $TransjuegoInfo->descripcion = $dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"};
                                            $TransjuegoInfo->valor = $creditosConvert;
                                            $TransjuegoInfo->usucreaId = 0;
                                            $TransjuegoInfo->usumodifId = 0;

                                            $UsuarioMandante = new UsuarioMandante($UsuarioTorneo->getUsuarioId());
                                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                            switch (strtolower($Usuario->idioma)) {
                                                case 'es':
                                                    $title = 'Notificacion';
                                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                    break;
                                                case 'en':
                                                    $title = 'Notification';
                                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$tournamentName} :clap:";
                                                    break;
                                                case 'pt':
                                                    $title = 'Notificação';
                                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                    break;
                                            }

                                            $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
                                            $UsuarioMensaje->isRead = 0;
                                            $UsuarioMensaje->body = $messageBody;
                                            $UsuarioMensaje->msubject = $title;
                                            $UsuarioMensaje->parentId = 0;
                                            $UsuarioMensaje->proveedorId = 0;
                                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                                            $UsuarioMensaje->paisId = 0;
                                            $UsuarioMensaje->fechaExpiracion = '';

                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                                            $mensajesRecibidos = [];
                                            $array = [];

                                            $array["body"] = $messageBody;

                                            array_push($mensajesRecibidos, $array);
                                            $data = array();
                                            $data["messages"] = $mensajesRecibidos;
                                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                                            /* $UsuarioSession = new UsuarioSession();
                                             $rules = [];

                                             array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                             array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                             $filtro = array("rules" => $rules, "groupOp" => "AND");
                                             $json = json_encode($filtro);


                                             $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                             $usuarios = json_decode($usuarios);

                                             $usuariosFinal = [];

                                             foreach ($usuarios->data as $key => $value) {

                                                 $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                 $WebsocketUsuario->sendWSMessage();

                                             }*/
                                            $UsuarioMandante = new UsuarioMandante($datanum->{"usuario_mandante.usumandante_id"});


                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);


                                        }

                                    }

                                } catch (Exception $e) {

                                }
                            }

                        }

                    }
                } catch (Exception $e) {

                }
            }

        }


    }

    if (true) {
        print_r('Fecha Inicio: '.$fechaL1.' - Fecha Fin: '.$fechaL2);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "Inicia: " . date('Y-m-d H:i:s');
        $fp = fopen(__DIR__ . '/logs/Slog_' . date("Y-m-d") . '.log', 'a');
//fwrite($fp, $log);
//fclose($fp);

        $Tipos = array(
            'CASINO', 'LIVECASINO', 'VIRTUAL'
        );

        foreach ($Tipos as $tipo) {


            $TorneoInterno = new TorneoInterno();

            $rules = [];

            array_push($rules, array("field" => "torneo_interno.mandante", "data" => "19", "op" => "eq"));
            array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => $fechaL1, "op" => "le"));
            array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => $fechaL2, "op" => "ge"));

            if ($_ENV['debug']) {
                array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => 2009, "op" => "eq"));

            }
            if ($_REQUEST['test'] == '2') {
                array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => 3182, "op" => "eq"));

            }
            switch ($tipo) {
                case 'CASINO':
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "2", "op" => "eq"));

                    break;
                case 'LIVECASINO':
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "3", "op" => "eq"));

                    break;
                case 'VIRTUAL':
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "4", "op" => "eq"));

                    break;

            }

            if ($_REQUEST['test'] == '2') {

                print_r($rules);

            }
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 1000;

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $torneos = $TorneoInterno->getTorneosCustom(" torneo_interno.* ", "torneo_interno.torneo_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);


            $torneos = json_decode($torneos);

            $ActivacionTorneos = true;

            $ArrayTransaction = array('1');


            foreach ($torneos->data as $key => $value) {
                try {
                    if ($ActivacionTorneos) {

                        $condRank = array();
                        $condPaises = array();
                        $needSubscribe = false;
                        $condIdGame = array();
                        $condIdGameGeneral = array();
                        $condIdCategory = array();
                        $condIdProvider = array();
                        $condIdSubProvider = array();
                        $incomeNetPoint = 0;
                        print_r('condIdGame');
                        print_r($condIdGame);

                        $rules = array();

                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.mandante", "data" => $value->{"torneo_interno.mandante"}, "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $jsonfiltro = json_encode($filtro);

                        $TorneoDetalle = new TorneoDetalle();

                        $torneosdetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.orden", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, 'torneo_detalle.torneodetalle_id');

                        $torneosdetalles = json_decode($torneosdetalles);


                        foreach ($torneosdetalles->data as $key2 => $value2) {

                            switch ($value2->{"torneo_detalle.tipo"}) {
                                case "RANK":

                                    $arrayT = array(
                                        'valor' => $value2->{"torneo_detalle.valor"},
                                        'valor2' => $value2->{"torneo_detalle.valor2"},
                                        'valor3' => $value2->{"torneo_detalle.valor3"}
                                    );

                                    if ($condRank[$value2->{"torneo_detalle.moneda"}] == null) {
                                        $condRank[$value2->{"torneo_detalle.moneda"}] = array();
                                    }

                                    array_push($condRank[$value2->{"torneo_detalle.moneda"}], $arrayT);


                                    break;
                                case "RANKNETINCOME":
                                    //ES EL TORNEO_DETALLE PARA GANANCIA NETA
                                    $incomeNetPoint = $value2->{"torneo_detalle.valor"};

                                    break;

                                case "USERSUBSCRIBE":


                                    if ($value2->{"torneo_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":


                                    if ($value2->{"torneo_detalle.valor"} == 1) {
                                        $needSubscribe = true;
                                    }

                                    break;


                                case "CONDPAISUSER":


                                    array_push($condPaises, $value2->{"torneo_detalle.valor"});

                                    break;

                                default:

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME') && $value2->{"torneo_detalle.tipo"} !== 'CONDGAME_ALL') {

                                        array_push($condIdGame, explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1]);

                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDCATEGORY')) {

                                        array_push($condIdCategory, explode("CONDCATEGORY", $value2->{"torneo_detalle.tipo"})[1]);

                                    }


                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER') && $value2->{"torneo_detalle.tipo"} !== 'CONDPROVIDER_ALL') {

                                        array_push($condIdProvider, explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1]);

                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER') && $value2->{"torneo_detalle.tipo"} !== 'CONDSUBPROVIDER_ALL') {

                                        array_push($condIdSubProvider, explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1]);

                                    }

                                    break;
                            }

                        }

                        if (oldCount($condIdCategory) > 0 && !in_array('ALL', $condIdCategory)) {


                            $sqlApuestasDeportivasUsuarioDiaCierre = "


SELECT producto.producto_id
FROM producto
         INNER JOIN categoria_producto
                    ON (categoria_producto.producto_id = producto.producto_id)

where categoria_producto.categoria_id IN (" . implode(',', $condIdCategory) . ")
";


                            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                                try {


                                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                                    $connDB5 = null;

                                    if ($_ENV['ENV_TYPE'] == 'prod') {

                                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                            , array(
                                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                            )
                                        );
                                    } else {
                                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                        );
                                    }


                                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                                    $connDB5->exec("set names utf8");


                                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                                    }


                                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                    }
                                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                    }

                                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                    }
                                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                        $connDB5->exec("SET NAMES utf8mb4");
                                    }

                                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                                } catch (\Exception $e) {
                                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                                }

                            }
                            $dataProductos = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

                            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                                $connDB5 = null;
                                $_ENV["connectionGlobal"]->setConnection($connOriginal);
                            }


                            if ($dataProductos !== NULL && $dataProductos !== "" && $dataProductos[0] !== NULL) {

                                foreach ($dataProductos as $key4 => $datanum) {

                                    array_push($condIdGameGeneral, $datanum->{"producto.producto_id"});

                                }
                            }
                        }
                        $BonoInterno = new BonoInterno();

                        $sqlCondSubproveedor = '';

                        if (oldCount($condIdSubProvider) > 0 && !in_array('ALL', $condIdSubProvider)) {
                            $sqlCondSubproveedor = "AND  producto.subproveedor_id IN (" . implode(',', $condIdSubProvider) . ") ";
                        }
                        $sqlCondProveedor = '';

                        if (oldCount($condIdProvider) > 0 && !in_array('ALL', $condIdProvider)) {
                            $sqlCondProveedor = "AND  producto.proveedor_id IN (" . implode(',', $condIdProvider) . ")";
                        }

                        $sqlCondGame = '';

                        if (oldCount($condIdGame) > 0 && !in_array('ALL', $condIdSubProvider)) {
                            $sqlCondGame = "HAVING  producto_mandante.prodmandante_id IN (" . implode(',', $condIdGame) . ")";
                        }
                        if (oldCount($condIdGameGeneral) > 0 && !in_array('ALL', $condIdGameGeneral)) {
                            $sqlCondGame = "HAVING  producto.producto_id IN (" . implode(',', $condIdGameGeneral) . ")";
                        }
                        $sqlCondPaises = '';

                        if (oldCount($condPaises) > 0) {
                            $sqlCondPaises = "AND  usuario_mandante.pais_id IN (" . implode(',', $condPaises) . ") ";
                        }

                        if ($incomeNetPoint == 0) {

                            $sqlApuestasDeportivasUsuarioDiaCierre = "

                    select transjuego_log.transjuegolog_id,transjuego_log.transaccion_id,producto.subproveedor_id,transaccion_juego.usuario_id,transjuego_log.valor,
                           producto.producto_id,
                           producto.proveedor_id,
                           producto.subproveedor_id,
                           producto_mandante.prodmandante_id,
                           subproveedor.tipo,
                           
                           usuario_mandante.usumandante_id,
                           usuario_mandante.usuario_mandante,
                           usuario_mandante.mandante,
                           usuario_mandante.moneda,
                           usuario_mandante.pais_id
                           
                    from transjuego_log
                    INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
                    INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
                    INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
                    INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id
                    
                    INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
                        WHERE 
                            subproveedor.tipo = '" . $tipo . "'
                          " . $sqlCondSubproveedor . "
                          " . $sqlCondProveedor . "
                         
                          " . $sqlCondPaises . "
                           
                           
                          
                          AND  transjuego_log.fecha_crea >= '" . $fechaL1 . "' 
                        
                        AND transjuego_log.fecha_crea <= '" . $fechaL2 . "' AND transjuego_log.transjuegolog_id NOT IN (" . implode(',', $ArrayTransaction) . ")
                    and transjuego_log.tipo LIKE 'DEBIT%' and usuario_mandante.mandante='" . $value->{"torneo_interno.mandante"} . "'  " . $sqlCondGame . "
                    ";
                            print_r($sqlApuestasDeportivasUsuarioDiaCierre);
                            if ($_ENV['debug']) {

                                print_r($sqlApuestasDeportivasUsuarioDiaCierre);
                            }


                            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                                try {


                                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                                    $connDB5 = null;

                                    if ($_ENV['ENV_TYPE'] == 'prod') {

                                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                            , array(
                                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                            )
                                        );
                                    } else {
                                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                        );
                                    }


                                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                                    $connDB5->exec("set names utf8");


                                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                                    }


                                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                    }
                                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                    }

                                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                    }
                                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                        $connDB5->exec("SET NAMES utf8mb4");
                                    }

                                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                                } catch (\Exception $e) {
                                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                                }

                            }
                            $dataUsuario = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

                            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                                $connDB5 = null;
                                $_ENV["connectionGlobal"]->setConnection($connOriginal);
                            }

                            if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {

                                foreach ($dataUsuario as $key4 => $datanum) {
                                    if ($datanum->{"usuario_mandante.usumandante_id"} == '16') {
                                        print_r($datanum);

                                    }

                                    try {

                                        $creditosConvert = 0;

                                        $cumpleCondicion = true;

                                        $cumpleCondicionCont = 0;
                                        $cumpleCondicionPais = false;

                                        $condicionesProducto = 0;
                                        $cumpleCondicionProd = false;

                                        $condicionesProveedor = 0;
                                        $cumpleCondicionProv = false;

                                        $condicionesSubProveedor = 0;
                                        $cumpleCondicionSubProv = false;

                                        $condicionesRank = 0;
                                        $cumpleCondicionRank = false;

                                        $valorTicket = floatval($datanum->{"transjuego_log.valor"});


                                        if (oldCount($condPaises) > 0) {
                                            if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                                $cumpleCondicionPais = true;
                                            }
                                            if ($cumpleCondicionPais == false) {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"it_ticket_enc.ticket_id"}}','CONDPAISUSER')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            }
                                            $cumpleCondicionCont++;
                                        }


                                        if (oldCount($condIdGame) > 0) {

                                            if (in_array($datanum->{"producto_mandante.prodmandante_id"}, $condIdGame)) {
                                                $cumpleCondicionProd = true;
                                            }
                                            if ($cumpleCondicionProd == false) {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDGAME')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            }
                                            $condicionesProducto++;
                                        }

                                        if (oldCount($condIdProvider) > 0) {

                                            if (in_array($datanum->{"producto.proveedor_id"}, $condIdProvider)) {
                                                $cumpleCondicionProv = true;
                                            }
                                            if ($cumpleCondicionProv == false) {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDPROVIDER')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            }
                                            $condicionesProveedor++;
                                        }

                                        if (oldCount($condIdSubProvider) > 0) {

                                            if (in_array($datanum->{"producto.subproveedor_id"}, $condIdSubProvider)) {
                                                $cumpleCondicionSubProv = true;
                                            }
                                            if ($cumpleCondicionSubProv == false) {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDSUBPROVIDER')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            }
                                            $condicionesSubProveedor++;
                                        }


                                        if (oldCount($condRank) > 0) {

                                            foreach ($condRank[$datanum->{"usuario_mandante.moneda"}] as $item) {

                                                if ($valorTicket >= $item["valor"]) {

                                                    if ($valorTicket <= $item["valor2"]) {

                                                        $creditosConvert = $item["valor3"];
                                                    }
                                                }
                                                $cumpleCondicionRank = true;
                                            }
                                            $condicionesRank++;

                                        }


                                        if ($condicionesProducto > 0 && !$cumpleCondicionProd) {
                                            $cumpleCondicion = false;
                                        }


                                        if ($condicionesProveedor > 0 && !$cumpleCondicionProv) {
                                            $cumpleCondicion = false;
                                        }

                                        if ($condicionesSubProveedor > 0 && !$cumpleCondicionSubProv) {
                                            $cumpleCondicion = false;
                                        }

                                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                                            $cumpleCondicion = false;
                                        }

                                        if ($cumpleCondicion) {
                                            if ($creditosConvert == 0) {
                                                $cumpleCondicion = false;
                                            }
                                        }

                                        if ($cumpleCondicion && !$needSubscribe && $creditosConvert > 0) {
                                            array_push($ArrayTransaction, $datanum->{"transjuego_log.transjuegolog_id"});

                                            $rules2 = [];

                                            array_push($rules2, array("field" => "usuario_torneo.usuario_id", "data" => $datanum->{"usuario_mandante.usumandante_id"}, "op" => "eq"));
                                            array_push($rules2, array("field" => "usuario_torneo.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));

                                            $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
                                            $json2 = json_encode($filtro2);

                                            $UsuarioTorneo = new UsuarioTorneo();
                                            $dataUsuarioTorneo = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json2, true, '');


                                            $dataUsuarioTorneo = json_decode($dataUsuarioTorneo);

                                            $dataUsuarioTorneo = $dataUsuarioTorneo->data[0];


                                            if ($dataUsuarioTorneo != null && $dataUsuarioTorneo != '') {
                                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                                $UsuarioTorneo = new UsuarioTorneo($dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"});
                                                $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                                $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $valorTicket);


                                                $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                                $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);


                                                $TransjuegoInfo = new TransjuegoInfo();
                                                $TransjuegoInfo->productoId = $datanum->{"producto_mandante.prodmandante_id"};
                                                $TransjuegoInfo->transaccionId = $datanum->{"transjuego_log.transaccion_id"};
                                                $TransjuegoInfo->transapiId = $datanum->{"transjuego_log.transjuegolog_id"};
                                                $TransjuegoInfo->tipo = "TORNEO";
                                                $TransjuegoInfo->descripcion = $dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"};
                                                $TransjuegoInfo->valor = $creditosConvert;
                                                $TransjuegoInfo->usucreaId = 0;
                                                $TransjuegoInfo->usumodifId = 0;

                                                $title = '';
                                                $messageBody = '';
                                                $tournamentName = $value->{"torneo_interno.nombre"};

                                                $UsuarioMandante = new UsuarioMandante($UsuarioTorneo->getUsuarioId());
                                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                                switch (strtolower($Usuario->idioma)) {
                                                    case 'es':
                                                        $title = 'Notificacion';
                                                        $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                        break;
                                                    case 'en':
                                                        $title = 'Notification';
                                                        $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$tournamentName} :clap:";
                                                        break;
                                                    case 'pt':
                                                        $title = 'Notificação';
                                                        $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                        break;
                                                }

                                                $UsuarioMensaje = new UsuarioMensaje();
                                                $UsuarioMensaje->usufromId = 0;
                                                $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
                                                $UsuarioMensaje->isRead = 0;
                                                $UsuarioMensaje->body = $messageBody;
                                                $UsuarioMensaje->msubject = $title;
                                                $UsuarioMensaje->parentId = 0;
                                                $UsuarioMensaje->proveedorId = 0;
                                                $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                                                $UsuarioMensaje->paisId = 0;
                                                $UsuarioMensaje->fechaExpiracion = '';

                                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                                                $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                                $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                                                $mensajesRecibidos = [];
                                                $array = [];

                                                $array["body"] = '¡ Bien :thumbsup: ! Sumaste ' . $creditosConvert . ' puntos en ' . $value->{"torneo_interno.nombre"} . ' :clap:';

                                                array_push($mensajesRecibidos, $array);
                                                $data = array();
                                                $data["messages"] = $mensajesRecibidos;
                                                //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                                                /* $UsuarioSession = new UsuarioSession();
                                                 $rules = [];

                                                 array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                                 array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                                 $filtro = array("rules" => $rules, "groupOp" => "AND");
                                                 $json = json_encode($filtro);


                                                 $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                                 $usuarios = json_decode($usuarios);

                                                 $usuariosFinal = [];

                                                 foreach ($usuarios->data as $key => $value) {

                                                     $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                     $WebsocketUsuario->sendWSMessage();

                                                 }*/
                                                $UsuarioMandante = new UsuarioMandante($datanum->{"usuario_mandante.usumandante_id"});


                                                $dataSend = $data;
                                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);


                                            } else {


                                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                                $UsuarioTorneo = new UsuarioTorneo();
                                                $UsuarioTorneo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                                $UsuarioTorneo->torneoId = $value->{"torneo_interno.torneo_id"};
                                                $UsuarioTorneo->valor = 0;
                                                $UsuarioTorneo->posicion = 0;
                                                $UsuarioTorneo->valorBase = 0;
                                                $UsuarioTorneo->usucreaId = 0;
                                                $UsuarioTorneo->usumodifId = 0;
                                                $UsuarioTorneo->estado = "A";
                                                $UsuarioTorneo->errorId = 0;
                                                $UsuarioTorneo->idExterno = 0;
                                                $UsuarioTorneo->mandante = $datanum->{"usuario_mandante.mandante"};;
                                                $UsuarioTorneo->version = 0;
                                                $UsuarioTorneo->apostado = 0;
                                                $UsuarioTorneo->codigo = 0;
                                                $UsuarioTorneo->externoId = 0;
                                                $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                                $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $valorTicket);

                                                $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                                $idUsuTorneo = $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                                                $TransjuegoInfo = new TransjuegoInfo();
                                                $TransjuegoInfo->productoId = $datanum->{"producto_mandante.prodmandante_id"};
                                                $TransjuegoInfo->transaccionId = $datanum->{"transjuego_log.transaccion_id"};
                                                $TransjuegoInfo->transapiId = $datanum->{"transjuego_log.transjuegolog_id"};
                                                $TransjuegoInfo->tipo = "TORNEO";
                                                $TransjuegoInfo->descripcion = $idUsuTorneo;
                                                $TransjuegoInfo->valor = $creditosConvert;
                                                $TransjuegoInfo->usucreaId = 0;
                                                $TransjuegoInfo->usumodifId = 0;

                                                $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                                $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                                                $UsuarioMandante = new UsuarioMandante($datanum->{"usuario_mandante.usumandante_id"});

                                                $mensajesRecibidos = [];
                                                $array = [];

                                                $array["body"] = '¡ Bien :thumbsup: ! Sumaste ' . $creditosConvert . ' puntos en ' . $value->{"torneo_interno.nombre"} . ' :clap:';

                                                array_push($mensajesRecibidos, $array);
                                                $dataSend = array();
                                                $dataSend["messages"] = $mensajesRecibidos;

                                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                            }

                                        }

                                        if ($cumpleCondicion && $needSubscribe && $creditosConvert > 0) {

                                            $rules2 = [];

                                            array_push($rules2, array("field" => "usuario_torneo.usuario_id", "data" => $datanum->{"usuario_mandante.usumandante_id"}, "op" => "eq"));
                                            array_push($rules2, array("field" => "usuario_torneo.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));

                                            $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
                                            $json2 = json_encode($filtro2);

                                            $UsuarioTorneo = new UsuarioTorneo();
                                            $dataUsuarioTorneo = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json2, true, '');


                                            $dataUsuarioTorneo = json_decode($dataUsuarioTorneo);

                                            $dataUsuarioTorneo = $dataUsuarioTorneo->data[0];


                                            if ($dataUsuarioTorneo != null && $dataUsuarioTorneo != '') {
                                                array_push($ArrayTransaction, $datanum->{"transjuego_log.transjuegolog_id"});
                                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                                $UsuarioTorneo = new UsuarioTorneo($dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"});
                                                $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                                $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $valorTicket);


                                                $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                                $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);


                                                $TransjuegoInfo = new TransjuegoInfo();
                                                $TransjuegoInfo->productoId = $datanum->{"producto_mandante.prodmandante_id"};
                                                $TransjuegoInfo->transaccionId = $datanum->{"transjuego_log.transaccion_id"};
                                                $TransjuegoInfo->transapiId = $datanum->{"transjuego_log.transjuegolog_id"};
                                                $TransjuegoInfo->tipo = "TORNEO";
                                                $TransjuegoInfo->descripcion = $dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"};
                                                $TransjuegoInfo->valor = $creditosConvert;
                                                $TransjuegoInfo->usucreaId = 0;
                                                $TransjuegoInfo->usumodifId = 0;


                                                $title = '';
                                                $messageBody = '';
                                                $tournamentName = $value->{"torneo_interno.nombre"};

                                                $UsuarioMandante = new UsuarioMandante($UsuarioTorneo->getUsuarioId());
                                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                                switch (strtolower($Usuario->idioma)) {
                                                    case 'es':
                                                        $title = 'Notificacion';
                                                        $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                        break;
                                                    case 'en':
                                                        $title = 'Notification';
                                                        $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$tournamentName} :clap:";
                                                        break;
                                                    case 'pt':
                                                        $title = 'Notificação';
                                                        $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                        break;
                                                }

                                                $UsuarioMensaje = new UsuarioMensaje();
                                                $UsuarioMensaje->usufromId = 0;
                                                $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
                                                $UsuarioMensaje->isRead = 0;
                                                $UsuarioMensaje->body = $messageBody;
                                                $UsuarioMensaje->msubject = $title;
                                                $UsuarioMensaje->parentId = 0;
                                                $UsuarioMensaje->proveedorId = 0;
                                                $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                                                $UsuarioMensaje->paisId = 0;
                                                $UsuarioMensaje->fechaExpiracion = '';

                                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                                                $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                                $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                                                $mensajesRecibidos = [];
                                                $array = [];

                                                $array["body"] = '¡ Bien :thumbsup: ! Sumaste ' . $creditosConvert . ' puntos en ' . $value->{"torneo_interno.nombre"} . ' :clap:';

                                                array_push($mensajesRecibidos, $array);
                                                $data = array();
                                                $data["messages"] = $mensajesRecibidos;
                                                //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                                                /* $UsuarioSession = new UsuarioSession();
                                                 $rules = [];

                                                 array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                                 array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                                 $filtro = array("rules" => $rules, "groupOp" => "AND");
                                                 $json = json_encode($filtro);


                                                 $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                                 $usuarios = json_decode($usuarios);

                                                 $usuariosFinal = [];

                                                 foreach ($usuarios->data as $key => $value) {

                                                     $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                     $WebsocketUsuario->sendWSMessage();

                                                 }*/
                                                $UsuarioMandante = new UsuarioMandante($datanum->{"usuario_mandante.usumandante_id"});


                                                $dataSend = $data;
                                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);


                                            }

                                        }

                                    } catch (Exception $e) {

                                    }
                                }

                            }
                        }
                        //SE HACE NUEVA LOGICA CUANDO EL TORNEO ES POR GANANCIA NETA
                        if ($incomeNetPoint > 0) {

                            $sqlApuestasDeportivasUsuarioDiaCierre = "
                    select transjuego_log.transjuegolog_id,
                           transjuego_log.transaccion_id,
                           transjuego_log.tipo,
                           producto.subproveedor_id,
                           transaccion_juego.usuario_id,
                           transjuego_log.valor,
                           producto.producto_id,
                           producto.proveedor_id,
                           producto.subproveedor_id,
                           producto_mandante.prodmandante_id,
                           subproveedor.tipo,
                           
                           usuario_mandante.usumandante_id,
                           usuario_mandante.usuario_mandante,
                           usuario_mandante.mandante,
                           usuario_mandante.moneda,
                           usuario_mandante.pais_id
                           
                    from transjuego_log
                    INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
                    INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
                    INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
                    INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id
                    
                    INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
                        WHERE 
                            subproveedor.tipo = '" . $tipo . "'
                          " . $sqlCondSubproveedor . "
                          " . $sqlCondProveedor . "
                         
                          " . $sqlCondPaises . "
                           
                           
                          
                        AND  transjuego_log.fecha_crea >= '" . $fechaL1 . "' 
                        
                        AND transjuego_log.fecha_crea <= '" . $fechaL2 . "' AND transjuego_log.transjuegolog_id NOT IN (" . implode(',', $ArrayTransaction) . ")
                        AND valor > 0
                        AND usuario_mandante.mandante='" . $value->{"torneo_interno.mandante"} . "'  " . $sqlCondGame . "
                    ";


                            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                                try {


                                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                                    $connDB5 = null;

                                    if ($_ENV['ENV_TYPE'] == 'prod') {

                                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                            , array(
                                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                            )
                                        );
                                    } else {
                                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                        );
                                    }


                                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                                    $connDB5->exec("set names utf8");


                                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                                    }


                                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                    }
                                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                    }

                                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                    }
                                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                        $connDB5->exec("SET NAMES utf8mb4");
                                    }

                                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                                } catch (\Exception $e) {
                                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                                }

                            }

                            $dataUsuario = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);
                            $ArrayDebitCredit = [];


                            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                                $connDB5 = null;
                                $_ENV["connectionGlobal"]->setConnection($connOriginal);
                            }


                            if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {

                                foreach ($dataUsuario as $key4 => $datanum) {
                                    if ($datanum->{"usuario_mandante.usumandante_id"} == '16') {
                                        print_r($datanum);

                                    }
                                    try {

                                        $creditosConvert = 0;

                                        $cumpleCondicion = true;

                                        $cumpleCondicionCont = 0;
                                        $cumpleCondicionPais = false;

                                        $condicionesProducto = 0;
                                        $cumpleCondicionProd = false;

                                        $condicionesProveedor = 0;
                                        $cumpleCondicionProv = false;

                                        $condicionesSubProveedor = 0;
                                        $cumpleCondicionSubProv = false;

                                        $condicionesRank = 0;
                                        $cumpleCondicionRank = false;

                                        $valorTicket = floatval($datanum->{"transjuego_log.valor"});


                                        if (oldCount($condPaises) > 0) {
                                            if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                                $cumpleCondicionPais = true;
                                            }
                                            if ($cumpleCondicionPais == false) {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"it_ticket_enc.ticket_id"}}','CONDPAISUSER')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            }
                                            $cumpleCondicionCont++;
                                        }


                                        if (oldCount($condIdGame) > 0) {

                                            if (in_array($datanum->{"producto_mandante.prodmandante_id"}, $condIdGame)) {
                                                $cumpleCondicionProd = true;
                                            }
                                            if ($cumpleCondicionProd == false) {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDGAME')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            }
                                            $condicionesProducto++;
                                        }

                                        if (oldCount($condIdProvider) > 0) {

                                            if (in_array($datanum->{"producto.proveedor_id"}, $condIdProvider)) {
                                                $cumpleCondicionProv = true;
                                            }
                                            if ($cumpleCondicionProv == false) {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDPROVIDER')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            }
                                            $condicionesProveedor++;
                                        }

                                        if (oldCount($condIdSubProvider) > 0) {

                                            if (in_array($datanum->{"producto.subproveedor_id"}, $condIdSubProvider)) {
                                                $cumpleCondicionSubProv = true;
                                            }
                                            if ($cumpleCondicionSubProv == false) {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDSUBPROVIDER')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            }
                                            $condicionesSubProveedor++;
                                        }

                                        if ($condicionesProducto > 0 && !$cumpleCondicionProd) {
                                            $cumpleCondicion = false;
                                        }


                                        if ($condicionesProveedor > 0 && !$cumpleCondicionProv) {
                                            $cumpleCondicion = false;
                                        }

                                        if ($condicionesSubProveedor > 0 && !$cumpleCondicionSubProv) {
                                            $cumpleCondicion = false;
                                        }

                                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                                            $cumpleCondicion = false;
                                        }

                                        if ($cumpleCondicion && !$needSubscribe) {
                                            $user_id = $datanum->{"usuario_mandante.usumandante_id"};

                                            if ($datanum->{"transjuego_log.tipo"} == "CREDIT") {
                                                $ValueIncome = floatval($datanum->{"transjuego_log.valor"});
                                            } else {
                                                $ValueIncome = floatval($datanum->{"transjuego_log.valor"}) * -1;
                                            }
                                            if (!isset($ArrayDebitCredit["$user_id"])) {
                                                // Si el usuario_id no existe en el array, lo creamos
                                                $ArrayDebitCredit[$user_id] = [
                                                    'usuario_mandante' => $user_id,
                                                    "mandante" => $datanum->{"usuario_mandante.mandante"},
                                                    'transaction_information' => [
                                                        ['transaccion_id' => $datanum->{"transjuego_log.transaccion_id"},
                                                            'prodmandante_id' => $datanum->{"producto_mandante.prodmandante_id"},
                                                            'transjuegolog_id' => $datanum->{"transjuego_log.transjuegolog_id"}]
                                                    ],
                                                    'net_income' => $ValueIncome
                                                ];

                                            } else {
                                                // Si el usuario_id ya existe, sumamos los puntos
                                                $ArrayDebitCredit[$user_id]['net_income'] += $ValueIncome;
                                                $ArrayDebitCredit[$user_id]['transaction_information'][] = [
                                                    'transaccion_id' => $datanum->{"transjuego_log.transaccion_id"},
                                                    'prodmandante_id' => $datanum->{"producto_mandante.prodmandante_id"},
                                                    'transjuegolog_id' => $datanum->{"transjuego_log.transjuegolog_id"}
                                                ];
                                            }
                                        }

                                        if ($cumpleCondicion && $needSubscribe) {
                                            //Se consulta si el usuario ya esta inscrito en usuario torneo
                                            $rules2 = [];

                                            array_push($rules2, array("field" => "usuario_torneo.usuario_id", "data" => $datanum->{"usuario_mandante.usumandante_id"}, "op" => "eq"));
                                            array_push($rules2, array("field" => "usuario_torneo.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));

                                            $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
                                            $json2 = json_encode($filtro2);

                                            $UsuarioTorneo = new UsuarioTorneo();
                                            $dataUsuarioTorneo = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json2, true, '');


                                            $dataUsuarioTorneo = json_decode($dataUsuarioTorneo);

                                            $dataUsuarioTorneo = $dataUsuarioTorneo->data[0];

                                            if ($dataUsuarioTorneo != null && $dataUsuarioTorneo != '') {
                                                $user_id = $datanum->{"usuario_mandante.usumandante_id"};

                                                if ($datanum->{"transjuego_log.tipo"} == "CREDIT") {
                                                    $ValueIncome = floatval($datanum->{"transjuego_log.valor"});
                                                } else {
                                                    $ValueIncome = floatval($datanum->{"transjuego_log.valor"}) * -1;
                                                }
                                                if (!isset($ArrayDebitCredit["$user_id"])) {
                                                    // Si el usuario_id no existe en el array, lo creamos
                                                    $ArrayDebitCredit[$user_id] = [
                                                        'usuario_mandante' => $user_id,
                                                        "mandante" => $datanum->{"usuario_mandante.mandante"},
                                                        'transaction_information' => [
                                                            ['transaccion_id' => $datanum->{"transjuego_log.transaccion_id"},
                                                                'prodmandante_id' => $datanum->{"producto_mandante.prodmandante_id"},
                                                                'transjuegolog_id' => $datanum->{"transjuego_log.transjuegolog_id"}]
                                                        ],
                                                        'net_income' => $ValueIncome
                                                    ];

                                                } else {
                                                    // Si el usuario_id ya existe se agrega la informacion de la transaccion y se suma o se resta el valor de la apuesta
                                                    $ArrayDebitCredit[$user_id]['net_income'] += $ValueIncome;
                                                    $ArrayDebitCredit[$user_id]['transaction_information'][] = [
                                                        'transaccion_id' => $datanum->{"transjuego_log.transaccion_id"},
                                                        'prodmandante_id' => $datanum->{"producto_mandante.prodmandante_id"},
                                                        'transjuegolog_id' => $datanum->{"transjuego_log.transjuegolog_id"}
                                                    ];
                                                }
                                            }

                                        }

                                    } catch (Exception $e) {

                                    }
                                }

                                foreach ($ArrayDebitCredit as $i) {
                                    if ($i["net_income"] > 0) {
                                        $creditosConvert = floatval($i["net_income"]) / floatval($incomeNetPoint);

                                        $rules2 = [];

                                        array_push($rules2, array("field" => "usuario_torneo.usuario_id", "data" => $i["usuario_mandante"], "op" => "eq"));
                                        array_push($rules2, array("field" => "usuario_torneo.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));

                                        $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
                                        $json2 = json_encode($filtro2);

                                        $UsuarioTorneo = new UsuarioTorneo();
                                        $dataUsuarioTorneo = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json2, true, '');


                                        $dataUsuarioTorneo = json_decode($dataUsuarioTorneo);

                                        $dataUsuarioTorneo = $dataUsuarioTorneo->data[0];


                                        if ($dataUsuarioTorneo != null && $dataUsuarioTorneo != '') {

                                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                            $UsuarioTorneo = new UsuarioTorneo($dataUsuarioTorneo->{"usuario_torneo.usutorneo_id"});
                                            $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                            $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $i["net_income"]);


                                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);


                                            foreach ($i["transaction_information"] as $t) {
                                                $TransjuegoInfo = new TransjuegoInfo();
                                                $TransjuegoInfo->productoId = $t["prodmandante_id"];
                                                $TransjuegoInfo->transaccionId = $t["transaccion_id"];
                                                $TransjuegoInfo->transapiId = $t["transjuegolog_id"];
                                                $TransjuegoInfo->tipo = "TORNEO";
                                                $TransjuegoInfo->descripcion = $idUsuTorneo;
                                                $TransjuegoInfo->valor = $creditosConvert;
                                                $TransjuegoInfo->usucreaId = 0;
                                                $TransjuegoInfo->usumodifId = 0;

                                                $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                            }


                                            $title = '';
                                            $messageBody = '';
                                            $tournamentName = $value->{"torneo_interno.nombre"};

                                            $UsuarioMandante = new UsuarioMandante($UsuarioTorneo->getUsuarioId());
                                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                            switch (strtolower($Usuario->idioma)) {
                                                case 'es':
                                                    $title = 'Notificacion';
                                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                    break;
                                                case 'en':
                                                    $title = 'Notification';
                                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$tournamentName} :clap:";
                                                    break;
                                                case 'pt':
                                                    $title = 'Notificação';
                                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                                    break;
                                            }

                                            $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
                                            $UsuarioMensaje->isRead = 0;
                                            $UsuarioMensaje->body = $messageBody;
                                            $UsuarioMensaje->msubject = $title;
                                            $UsuarioMensaje->parentId = 0;
                                            $UsuarioMensaje->proveedorId = 0;
                                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                                            $UsuarioMensaje->paisId = 0;
                                            $UsuarioMensaje->fechaExpiracion = '';

                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                                            $mensajesRecibidos = [];
                                            $array = [];

                                            $array["body"] = '¡ Bien :thumbsup: ! Sumaste ' . $creditosConvert . ' puntos en ' . $value->{"torneo_interno.nombre"} . ' :clap:';

                                            array_push($mensajesRecibidos, $array);
                                            $data = array();
                                            $data["messages"] = $mensajesRecibidos;

                                            $UsuarioMandante = new UsuarioMandante($datanum->{"usuario_mandante.usumandante_id"});


                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);


                                        } else {

                                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                            $UsuarioTorneo = new UsuarioTorneo();
                                            $UsuarioTorneo->usuarioId = $i["usuario_mandante"];
                                            $UsuarioTorneo->torneoId = $value->{"torneo_interno.torneo_id"};
                                            $UsuarioTorneo->valor = 0;
                                            $UsuarioTorneo->posicion = 0;
                                            $UsuarioTorneo->valorBase = 0;
                                            $UsuarioTorneo->usucreaId = 0;
                                            $UsuarioTorneo->usumodifId = 0;
                                            $UsuarioTorneo->estado = "A";
                                            $UsuarioTorneo->errorId = 0;
                                            $UsuarioTorneo->idExterno = 0;
                                            $UsuarioTorneo->mandante = $i["mandante"];
                                            $UsuarioTorneo->version = 0;
                                            $UsuarioTorneo->apostado = 0;
                                            $UsuarioTorneo->codigo = 0;
                                            $UsuarioTorneo->externoId = 0;
                                            $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                            $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $i["net_income"]);

                                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $idUsuTorneo = $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                                            foreach ($i["transaction_information"] as $t) {
                                                $TransjuegoInfo = new TransjuegoInfo();
                                                $TransjuegoInfo->productoId = $t["prodmandante_id"];
                                                $TransjuegoInfo->transaccionId = $t["transaccion_id"];
                                                $TransjuegoInfo->transapiId = $t["transjuegolog_id"];
                                                $TransjuegoInfo->tipo = "TORNEO";
                                                $TransjuegoInfo->descripcion = $idUsuTorneo;
                                                $TransjuegoInfo->valor = $creditosConvert;
                                                $TransjuegoInfo->usucreaId = 0;
                                                $TransjuegoInfo->usumodifId = 0;

                                                $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                            }

                                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();
                                            $UsuarioMandante = new UsuarioMandante($datanum->{"usuario_mandante.usumandante_id"});

                                            $mensajesRecibidos = [];
                                            $array = [];

                                            $array["body"] = '¡ Bien :thumbsup: ! Sumaste ' . $creditosConvert . ' puntos en ' . $value->{"torneo_interno.nombre"} . ' :clap:';

                                            array_push($mensajesRecibidos, $array);
                                            $dataSend = array();
                                            $dataSend["messages"] = $mensajesRecibidos;

                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }
                            }
                        }

                    }
                } catch (Exception $e) {

                }
            }

        }

    }

    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . " FINAL' '#dev2' > /dev/null & ");

    sleep(3);

}












/**
 * Ejecutar un query
 *
 *
 * @param Objeto transaccion transaccion
 * @param String sql sql
 *
 * @return Array $result resultado de la verificación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function execQuery($transaccion, $sql)
{

    $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO($transaccion);
    $return = $TorneoInternoMySqlDAO->querySQL($sql);
    $return = json_decode(json_encode($return), FALSE);

    return $return;

}


