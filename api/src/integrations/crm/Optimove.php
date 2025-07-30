<?php

/**
 * Clase Optimove
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-19
 */

namespace Backend\integrations\crm;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioBono;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensajecampana;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\UsuarioMensajeMySqlDAO;

/**
 * Esta clase proporciona métodos para interactuar con la API de Optimove, incluyendo la autenticación de usuarios,
 * registro de eventos, gestión de promociones y detalles de ejecución de campañas.
 */
class Optimove
{

    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $URL = "";

    /**
     * Representación de 'key'
     * key del proveedor para realizar la integracion produccion
     * @var string
     */
    private $key = "";

    /**
     * Representación de 'metodo'
     * metodo del proveedor para realizar la integracion produccion
     * @var string
     */
    private $metodo = "";

    /**
     * Constructor de la clase Optimove.
     *
     * Inicializa las propiedades de la clase según el entorno de configuración.
     *
     * @throws Exception Si ocurre un error durante la inicialización.
     */
    function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }


    /**
     * Inicia sesión en el sistema Optimove.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     *
     * @return object Objeto con la respuesta de la autenticación.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    function Login($mandanteUsuario, $PaisId)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $this->metodo = "general/login";

        $Subproveedor = new Subproveedor("", "OPTIMOVE");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandanteUsuario, $PaisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->URL = $Credentials->URL;

        $array = array(
            "UserName" => $Credentials->USERNAME,
            "Password" => $Credentials->PASSWORD,
        );

        $Response = $this->connectionAutentica($array);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Agrega promociones para un usuario específico.
     *
     * @param string $BonoId          Identificador del bono.
     * @param string $BonoName        Nombre del bono.
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [mixed] $response: Respuesta de la operación.
     */
    function AddPromotions($BonoId, $BonoName, $mandanteUsuario, $PaisId)
    {
        if ($PaisId == '0') {
            $PaisId = '';
        }

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $this->metodo = "integrations/AddPromotions";

        $return = array(
            array(
                "PromoCode" => $BonoId,
                "PromotionName" => $BonoName
            )
        );

        syslog(LOG_WARNING, "OPTIMOVE AddPromotions DATA: " . json_encode($return));
        $Response = $this->connectionPOST($return);
        syslog(LOG_WARNING, "OPTIMOVE AddPromotions RESPONSE: " . ($Response));

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Agrega plantillas de canal para un usuario específico.
     *
     * @param string $TemplateID      Identificador de la plantilla.
     * @param string $ChannelID       Identificador del canal.
     * @param string $TemplateName    Nombre de la plantilla.
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [mixed] $response: Respuesta de la operación.
     */
    function AddChannelTemplates($TemplateID, $ChannelID, $TemplateName, $mandanteUsuario, $PaisId)
    {
        if ($PaisId == '0') {
            $PaisId = '';
        }

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $this->metodo = "integrations/AddChannelTemplates";
        //$this->token = $Token;
        $return = array(
            array(
                "TemplateID" => $TemplateID,
                "TemplateName" => $TemplateName

            )
        );

        $Response = $this->connectionPOSTTemplate($return, $ChannelID);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }


    /**
     * Elimina plantillas de canal para un usuario específico.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param array  $TemplateID      Identificadores de las plantillas a eliminar.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [mixed] $response: Respuesta de la operación.
     */
    function DeleteChannelTemplates($mandanteUsuario, $PaisId, $TemplateID)
    {
        if ($PaisId == '0') {
            $PaisId = '';
        }

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $this->metodo = "integrations/DeleteChannelTemplates";
        $arrayfinal = array();
        foreach ($TemplateID as $key => $value) {
            $return = array(
                "ChannelID" => 509,
                "TemplateID" => $value
            );
            array_push($arrayfinal, $return);
        }

        $Response = $this->connectionPOST($arrayfinal);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }


    /**
     * Obtiene las promociones para un usuario específico.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     *
     * @return object Objeto con la respuesta de las promociones.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [mixed] $response: Respuesta de las promociones.
     */
    function GetPromotions($mandanteUsuario, $PaisId)
    {
        if ($PaisId == '0') {
            $PaisId = '';
        }
        $Proveedor = new Proveedor("", "Optimove");

        $this->metodo = "integrations/GetPromotions";

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $Response = $this->connectionGET();

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }


    /**
     * Elimina promociones para un usuario específico.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param array  $BonoId          Identificadores de los bonos a eliminar.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [mixed] $response: Respuesta de la operación.
     */
    function DeletePromotions($mandanteUsuario, $PaisId, $BonoId)
    {
        if ($PaisId == '0') {
            $PaisId = '';
        }

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $this->metodo = "integrations/DeletePromotions";
        $arrayfinal = array();
        foreach ($BonoId as $key => $value) {
            $return = array(
                "PromoCode" => $value
            );
            array_push($arrayfinal, $return);
        }

        //$this->token = $Token;
        $Response = $this->connectionPOST($arrayfinal);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Crea un registro de log en la base de datos.
     *
     * @param string $tipo       Tipo de log.
     * @param string $usuario_id Identificador del usuario.
     * @param string $valor_id1  Primer valor del log.
     * @param string $valor_id2  Segundo valor del log.
     * @param string $valor_id3  Tercer valor del log.
     * @param string $valor1     Primer valor adicional del log.
     * @param string $valor2     Segundo valor adicional del log.
     * @param string $estado     Estado del log.
     *
     * @return mixed Resultado de la operación de inserción en la base de datos.
     */
    public function createLog2($tipo, $usuario_id, $valor_id1, $valor_id2, $valor_id3, $valor1, $valor2, $estado)
    {
        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $transaction = $BonoInternoMySqlDAO->getTransaction();

        if (strpos($valor_id1, '==') !== false) {
            $valor_id1 = base64_decode($valor_id2);
        }

        if (strpos($valor_id2, '==') !== false) {
            $valor_id2 = base64_decode($valor_id2);
        }

        $sql = "
INSERT INTO log_cron (tipo, usuario_id, valor_id1, valor_id2, valor_id3, valor1, valor2, fecha_crea, fecha_modif,
                             estado)
VALUES ('$tipo', '$usuario_id', '$valor_id1', '$valor_id2', '$valor_id3', '$valor1', '$valor2', DEFAULT, DEFAULT, '$estado');

";
        print_r($sql);
        $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
        $transaction->commit();
        return $resultsql;
    }

    /**
     * Obtiene los detalles de ejecución del cliente por campaña.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param mixed  $EventTypeID     Identificador del tipo de evento.
     * @param mixed  $CampaignID      Identificador de la campaña.
     * @param mixed  $Channel         Canal de la campaña.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [string] $response: Respuesta de la operación.
     */
    public function GetCustomerExecutionDetailsByCampaign($mandanteUsuario, $PaisId = "", $EventTypeID, $CampaignID, $Channel)
    {
        $redis = RedisConnectionTrait::getRedisInstance(true);

        if ($PaisId == '0') {
            $PaisId = '';
        }

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $this->metodo = "customers/GetCustomerExecutionDetailsByCampaign";

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $iterations = 10;
            $max_objects = 200000;
            $skip = 0;
            $all_data = [];

            foreach (range(1, $iterations) as $iteration) {
                $Response = $this->connectionGETCamp($CampaignID, $Channel, $skip);
                $data = json_decode($Response, true);

                $all_data = array_merge($all_data, $data);

                $current_count = oldCount($data);
                $skip += $current_count;

                if ($current_count < $max_objects) {
                    break;
                }
            }

            $all_data = json_decode(json_encode($all_data));

            try {
                $logID = $this->createLog2('agregarBonoBackground', '0', 'TOTAL' . $CampaignID, '0', '0', oldCount($all_data), '', 'TOTAL');
            } catch (Exception $e) {
            }

            $arrayBonus = array();
            foreach ($all_data as $key => $value) {
                $Bonos = explode(",", $value->{"PromoCode"});
                foreach ($Bonos as $value2) {
                    $UsuarioId = $value->{"CustomerID"};
                    $BonoId = str_replace('B', '', $value2);

                    try {
                        if (!in_array($BonoId, $arrayBonus)) {
                            $logID = $this->createLog2('agregarBonoBackground', '0', $CampaignID, $BonoId, '0', oldCount($all_data), '', 'TOTAL');
                            array_push($arrayBonus, $BonoId);
                        }
                    } catch (Exception $e) {
                    }

                    $UsuarioId = $ConfigurationEnvironment->DepurarCaracteres($UsuarioId);
                    $BonoId = $ConfigurationEnvironment->DepurarCaracteres($BonoId);
                    if ($BonoId == '') {
                        continue;
                    }

                    $Usuario = new Usuario($UsuarioId);
                    $Prefix = 'ADMIN2';
                    if ($Usuario->mandante == '0' && $Usuario->paisId == 173) {
                        $Prefix = 'ADMIN2';
                    } elseif ($Usuario->mandante != '8') {
                        $Prefix = 'ADMIN3';
                    }

                    $redisParam = ['ex' => 18000];

                    $redisPrefix = $Prefix . "F3BACK+AgregarBonoBackground+UID" . $UsuarioId . '+' . $BonoId . '+' . $CampaignID;

                    $redis = RedisConnectionTrait::getRedisInstance(true);

                    if ($redis != null) {
                        $argv = array();
                        $argv[0] = '';
                        $argv[1] = $UsuarioId;
                        $argv[2] = $BonoId;
                        $argv[3] = $CampaignID;

                        $redis->set($redisPrefix, json_encode($argv), $redisParam);
                    }
                }
            }
            $data = array();
            $data["success"] = true;
            $data["response"] = "Bonos Agregados";
            return json_decode(json_encode($data));
        } catch (Exception $e) {
            print_r($e);
        }
    }

    /**
     * Obtiene los detalles de ejecución del cliente por campaña en tiempo real.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param mixed  $EventTypeID     Identificador del tipo de evento.
     * @param mixed  $CampaignID      Identificador de la campaña.
     * @param mixed  $Channel         Canal de la campaña.
     * @param mixed  $IdBono          Identificador del bono.
     * @param mixed  $UserId          Identificador del usuario.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [string] $response: Respuesta de la operación.
     */
    public function GetCustomerExecutionDetailsByCampaignRealTime($mandanteUsuario, $PaisId = "", $EventTypeID, $CampaignID, $Channel, $IdBono, $UserId)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        try {
            $IdBono = base64_decode($IdBono);

            if (strpos($IdBono, '[') !== false) {
                $Bonos = json_decode($IdBono, true);
            } else {
                $Bonos = explode(",", $IdBono);
            }

            /**
             * Procesa los bonos para un usuario específico.
             */
            foreach ($Bonos as $value2) {
                $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
                $BonoId = explode("B", $value2);

                $BonoId = $BonoId[1];
                $BonoInterno = new BonoInterno($BonoId);

                $UsuarioId = $UserId;

                $Usuario = new Usuario($UsuarioId);
                if ($BonoInterno->estado == 'A' && $BonoInterno->mandante == $Usuario->mandante) {
                    if ($BonoInterno->tipo == "8") {
                        $Transaction = $BonoInternoMySqlDAO->getTransaction();
                        $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $BonoId . "' AND (moneda='' OR moneda='" . $Usuario->moneda . "') ";

                        $bonoDetalles = $BonoInterno->execQuery($Transaction, $sqlDetalleBono);
                        $CONDSUBPROVIDER = array();
                        $CONDGAME = array();
                        foreach ($bonoDetalles as $bonoDetalle) {
                            if (stristr($bonoDetalle->{'a.tipo'}, 'CONDSUBPROVIDER')) {
                                $idSub = explode("CONDSUBPROVIDER", $bonoDetalle->{'a.tipo'})[1];
                                if ($idSub == "") {
                                    if ($bonoDetalle->{'a.valor'} != '') {
                                        $idSub = $bonoDetalle->{'a.valor'};
                                    }
                                }
                                print_r($bonoDetalle);
                                array_push($CONDSUBPROVIDER, $idSub);
                            }

                            if (stristr($bonoDetalle->{'a.tipo'}, 'CONDGAME')) {
                                $idGame = explode("CONDGAME", $bonoDetalle->{'a.tipo'})[1];
                                array_push($CONDGAME, $idGame);
                            }

                            if (stristr($bonoDetalle->{'a.tipo'}, 'PREFIX')) {
                                $Prefix = explode("PREFIX", $bonoDetalle->{'a.tipo'})[1];
                                if ($Prefix == '') {
                                    if ($bonoDetalle->{'a.valor'} != '') {
                                        $Prefix = $bonoDetalle->{'a.valor'};
                                    }
                                }
                            }
                            if (stristr($bonoDetalle->{'a.tipo'}, 'MAXJUGADORES')) {
                                $MaxplayersCount = explode("MAXJUGADORES", $bonoDetalle->{'a.tipo'})[1];
                                if ($MaxplayersCount == '') {
                                    if ($bonoDetalle->{'a.valor'} != '') {
                                        $MaxplayersCount = $bonoDetalle->{'a.valor'};
                                    }
                                }
                            }
                        }

                        $Subproveedor = new Subproveedor($idSub);
                        $Proveedor = new Proveedor($Subproveedor->proveedorId);
                        syslog(LOG_WARNING, "CAMPA OPTIMOVE: " . ($Subproveedor->abreviado));
                        if (in_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)) {
                            $responseBonoGlobal = $BonoInterno->bonoGlobal(
                                $Proveedor,
                                $BonoId,
                                $CONDGAME,
                                $Usuario->mandante,
                                $Usuario->usuarioId,
                                $Transaction,
                                0,
                                false,
                                0,
                                $BonoInterno->nombre,
                                $Prefix,
                                $MaxplayersCount
                            );
                        }
                        if ($responseBonoGlobal["status"] != "ERROR") {
                            $Transaction->commit();
                        }
                    }
                    //Bonos Freebet
                    if ($BonoInterno->tipo == "6") {
                        $Transaction = $BonoInternoMySqlDAO->getTransaction();

                        $UsuarioBono = new UsuarioBono();
                        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                        $UsuarioBono->setBonoId($BonoInterno->bonoId);
                        $UsuarioBono->setValor(0);
                        $UsuarioBono->setValorBono(0);
                        $UsuarioBono->setValorBase(0);
                        $UsuarioBono->setEstado('A');
                        $UsuarioBono->setErrorId('0');
                        $UsuarioBono->setIdExterno('0');
                        $UsuarioBono->setMandante($BonoInterno->mandante);
                        $UsuarioBono->setUsucreaId('0');
                        $UsuarioBono->setUsumodifId('0');
                        $UsuarioBono->setApostado('0');
                        $UsuarioBono->setVersion('3');
                        $UsuarioBono->setRollowerRequerido('0');
                        $UsuarioBono->setCodigo('');
                        $UsuarioBono->setExternoId('0');
                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                        $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                        $Transaction->commit();
                    }
                    //Bonos Deposito
                    if ($BonoInterno->tipo == "2") {
                        $Transaction = $BonoInternoMySqlDAO->getTransaction();

                        $UsuarioBono = new UsuarioBono();
                        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                        $UsuarioBono->setBonoId($BonoInterno->bonoId);
                        $UsuarioBono->setValor(0);
                        $UsuarioBono->setValorBono(0);
                        $UsuarioBono->setValorBase(0);
                        $UsuarioBono->setEstado('P');
                        $UsuarioBono->setErrorId('0');
                        $UsuarioBono->setIdExterno('0');
                        $UsuarioBono->setMandante($BonoInterno->mandante);
                        $UsuarioBono->setUsucreaId('0');
                        $UsuarioBono->setUsumodifId('0');
                        $UsuarioBono->setApostado('0');
                        $UsuarioBono->setVersion('3');
                        $UsuarioBono->setRollowerRequerido('0');
                        $UsuarioBono->setCodigo('');
                        $UsuarioBono->setExternoId('0');
                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                        $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                        $Transaction->commit();
                    }
                    //Bonos No deposito  //Rollower Requerido
                    if ($BonoInterno->tipo == "3") {
                        $Transaction = $BonoInternoMySqlDAO->getTransaction();
                        $valor_bono = 0;
                        try {
                            $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'VALORBONO');
                            $valor_bono = $BonoDetalleVALORBONO->valor;
                        } catch (Exception $e) {
                        }

                        try {
                            $BonoDetalleWFACTORBONO = new BonoDetalle('', $BonoId, 'WFACTORBONO');
                            $rollowerBono = $BonoDetalleWFACTORBONO->valor;
                        } catch (Exception $e) {
                        }

                        try {
                            $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'VALORROLLOWER');
                            $rollowerValor = $BonoDetalleROUNDSFREE->valor;
                        } catch (Exception $e) {
                        }
                        $rollowerRequerido = 0;

                        if ($rollowerBono) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);
                        }
                        if ($rollowerValor) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerValor);
                        }

                        $UsuarioBono = new UsuarioBono();
                        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                        $UsuarioBono->setBonoId($BonoInterno->bonoId);
                        $UsuarioBono->setValor($valor_bono);
                        $UsuarioBono->setValorBono(0);
                        $UsuarioBono->setValorBase(0);
                        $UsuarioBono->setEstado('A');
                        $UsuarioBono->setErrorId('0');
                        $UsuarioBono->setIdExterno('0');
                        $UsuarioBono->setMandante($BonoInterno->mandante);
                        $UsuarioBono->setUsucreaId('0');
                        $UsuarioBono->setUsumodifId('0');
                        $UsuarioBono->setApostado('0');
                        $UsuarioBono->setVersion('3');
                        $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                        $UsuarioBono->setCodigo('');
                        $UsuarioBono->setExternoId('0');
                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                        $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                        $Transaction->commit();
                    }
                    //Bonos FreeCasino
                    if ($BonoInterno->tipo == "5") {
                        $Transaction = $BonoInternoMySqlDAO->getTransaction();


                        try {
                            $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'VALORBONO');
                            $valorBase = $BonoDetalleVALORBONO->valor;
                        } catch (Exception $e) {
                        }

                        try {
                            $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'MINAMOUNT');
                            $valorBono = $BonoDetalleVALORBONO->valor;
                        } catch (Exception $e) {
                        }
                        $UsuarioBono = new UsuarioBono;
                        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                        $UsuarioBono->setBonoId($BonoInterno->bonoId);
                        $UsuarioBono->setValor(0);
                        $UsuarioBono->setValorBono($valorBono);
                        $UsuarioBono->setValorBase($valorBase);
                        $UsuarioBono->setEstado('A');
                        $UsuarioBono->setErrorId('0');
                        $UsuarioBono->setIdExterno('0');
                        $UsuarioBono->setMandante($BonoInterno->mandante);
                        $UsuarioBono->setUsucreaId('0');
                        $UsuarioBono->setUsumodifId('0');
                        $UsuarioBono->setApostado('0');
                        $UsuarioBono->setVersion('3');
                        $UsuarioBono->setRollowerRequerido('0');
                        $UsuarioBono->setCodigo('');
                        $UsuarioBono->setExternoId('0');
                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                        $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                        $Transaction->commit();
                    }
                } else {
                    throw new Exception("Bono Inactivo", "300042");
                }
                //Bonos Freespin
            }

            $data = array();
            $data["success"] = true;
            $data["response"] = "Bonos Agregados";

            return json_decode(json_encode($data));
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene los detalles de ejecución del cliente por campaña y guarda los mensajes en Redis.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param mixed  $EventTypeID     Identificador del tipo de evento.
     * @param mixed  $CampaignID      Identificador de la campaña.
     * @param mixed  $Channel         Canal de la campaña.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [string] $response: Respuesta de la operación.
     */
    public function GetCustomerExecutionDetailsByCampaignMenssageText($mandanteUsuario, $PaisId, $EventTypeID, $CampaignID, $Channel)
    {
        $redis = RedisConnectionTrait::getRedisInstance(true);

        try {
            if ($PaisId == '0') {
                $PaisId = '';
            }

            $data = array();
            $data["success"] = false;
            $data["error"] = 1;

            // Obtener la key
            $this->key = $this->getKey($mandanteUsuario, $PaisId);

            $this->metodo = "customers/GetCustomerExecutionDetailsByCampaign";


            $iterations = 10;
            $max_objects = 200000;
            $skip = 0;
            $all_data = [];

            foreach (range(1, $iterations) as $iteration) {
                $Response = $this->connectionGETCamp($CampaignID, $Channel, $skip);
                $data = json_decode($Response, true);

                $all_data = array_merge($all_data, $data);

                $current_count = oldCount($data);
                $skip += $current_count;

                if ($current_count < $max_objects) {
                    break;
                }
            }
            $arrayTemplates = array();
            $arrayTemplatesUsers = array();
            $all_data = json_decode(json_encode($all_data));
            foreach ($all_data as $key => $value) {
                $UsuarioId = $value->{"CustomerID"};
                $TemplateId = $value->{"TemplateID"};

                try {
                    if ( ! in_array($TemplateId, $arrayTemplates)) {
                        $logID = $this->createLog2(
                            'XTEMPLATE',
                            '0',
                            $CampaignID,
                            $TemplateId,
                            '0',
                            oldCount($all_data),
                            '',
                            'TOTAL'
                        );
                        array_push($arrayTemplates, $TemplateId);
                    }
                } catch (Exception $e) {
                }


                $Usuario = new Usuario($UsuarioId);
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

                if ($arrayTemplatesUsers[$TemplateId] == null) {
                    $arrayTemplatesUsers[$TemplateId] = array();
                }
                array_push($arrayTemplatesUsers[$TemplateId], $UsuarioMandante->getUsumandanteId());
            }
            foreach ($arrayTemplates as $TemplateId) {
                $UsuarioMensajecampana = new UsuarioMensajecampana($TemplateId);
                $Contenido = $UsuarioMensajecampana->body;

                $ConfigurationEnvironment = new ConfigurationEnvironment();
                $ConfigurationEnvironment->EnviarMensajeMasivoApiBulk($Contenido, '', '', '', $UsuarioMensajecampana->getUsumencampanaId(), $arrayTemplatesUsers[$TemplateId]);
            }
            $data = array();
            $data["success"] = true;
            $data["response"] = "Mensajes Guardados";

            return json_decode(json_encode($data));
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene los detalles de ejecución del cliente por campaña y guarda los mensajes en Redis en tiempo real.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param mixed  $EventTypeID     Identificador del tipo de evento.
     * @param mixed  $CampaignID      Identificador de la campaña.
     * @param mixed  $Channel         Canal de la campaña.
     * @param mixed  $TemplateId      Identificador de la plantilla.
     * @param mixed  $UsuarioId       Identificador del usuario.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [string] $response: Respuesta de la operación.
     */
    public function GetCustomerExecutionDetailsByCampaignMenssageTextRealTime($mandanteUsuario, $PaisId, $EventTypeID, $CampaignID, $Channel, $TemplateId, $UsuarioId)
    {
        $redis = RedisConnectionTrait::getRedisInstance(true);

        try {

            if ($PaisId == '0') {
                $PaisId = '';
            }

            $data = array();
            $data["success"] = false;
            $data["error"] = 1;

            $CampaignId = '';

            $redisParam = ['ex' => 18000];

            $redisPrefix = "F3BACK+AgregarMensajeTextoBackground+UID" . $UsuarioId . '+' . $TemplateId . '+' . $CampaignId;

            $redis = RedisConnectionTrait::getRedisInstance(true);

            if ($redis != null) {
                $argv = array();
                $argv[0] = '';
                $argv[1] = $UsuarioId;
                $argv[2] = $TemplateId;
                $argv[3] = $CampaignId;
                $redis->set($redisPrefix, json_encode($argv), $redisParam);
            }

            $data = array();
            $data["success"] = true;
            $data["response"] = "Mensajes Guardados";

            return json_decode(json_encode($data));
        } catch (Exception $e) {
        }
    }

    /**
     * Obtiene los detalles de ejecución del cliente por campaña y notifica a ContainerMedia.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param mixed  $EventTypeID     Identificador del tipo de evento.
     * @param mixed  $CampaignID      Identificador de la campaña.
     * @param mixed  $Channel         Canal de la campaña.
     * @param mixed  $Json            Datos en formato JSON.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [string] $response: Respuesta de la operación.
     */
    public function GetCustomerExecutionDetailsByContainermediaNotification($mandanteUsuario, $PaisId = "", $EventTypeID, $CampaignID, $Channel, $Json = "")
    {
        if ($PaisId == '0') {
            $PaisId = '';
        }

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $this->metodo = "customers/GetCustomerExecutionDetailsByCampaign";

        $Pais = new Pais($PaisId);
        $Mandante = new Mandante($mandanteUsuario);

        $iterations = 10;
        $max_objects = 200000;
        $skip = 0;
        $all_data = [];

        foreach (range(1, $iterations) as $iteration) {
            $Response = $this->connectionGETCamp($CampaignID, $Channel, $skip);
            $data = json_decode($Response, true);

            $all_data = array_merge($all_data, $data);

            $current_count = oldCount($data);
            $skip += $current_count;

            if ($current_count < $max_objects) {
                break;
            }
        }

        $all_data = json_decode(json_encode($all_data));
        $ContainerMedia = new \Backend\integrations\crm\ContainerMedia();
        foreach ($all_data as $key => $value) {
            $UsuarioId = $value->{"CustomerID"};
            $ChannelID = $value->{"ChannelID"};
            $Usuario = new Usuario($UsuarioId);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);
            $Registro = new Registro("", $Usuario->usuarioId);

            $array = array(
                "campaignID" => $CampaignID,
                "channelID" => $ChannelID,
                "userId" => $Usuario->usuarioId,
                "name" => $this->quitar_tildes($Usuario->nombre),
                "casinoUserId" => $UsuarioMandante->usumandanteId,
                "email" => $Registro->email,
                "phone" => $Registro->celular,
                "country" => $this->quitar_tildes($Pais->paisNom),
                "partner" => $Mandante->nombre,
                "device" => ''
            );

            $response = $ContainerMedia->EventOptimove(json_decode(json_encode($array)));
            $response = json_encode($response);
        }

        $data = array();
        $data["success"] = true;
        $data["response"] = "Informacion Enviada";

        return json_decode(json_encode($data));
    }

    /**
     * Elimina las tildes de una cadena de texto.
     *
     * @param string $cadena La cadena de texto a procesar.
     *
     * @return string La cadena de texto sin tildes.
     */
    public function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

    /**
     * Obtiene los detalles de ejecución del cliente por campaña y notifica a ContainerMedia en tiempo real.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param mixed  $EventTypeID     Identificador del tipo de evento.
     * @param mixed  $CampaignID      Identificador de la campaña.
     * @param mixed  $Channel         Canal de la campaña.
     * @param mixed  $Json            Datos en formato JSON.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [int] $error: Código de error en caso de fallo.
     *  - [string] $response: Respuesta de la operación.
     */
    public function GetCustomerExecutionDetailsByContainermediaRealtime($mandanteUsuario, $PaisId = "", $EventTypeID, $CampaignID, $Channel, $Json = "")
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $Datos = base64_decode($Json);
        $Datos = json_decode($Datos);
        $Pais = new Pais($PaisId);
        $Mandante = new Mandante($mandanteUsuario);

        $ContainerMedia = new \Backend\integrations\crm\ContainerMedia();

        $UsuarioId = $Datos->CustomerID;
        $ChannelID = $Datos->ChannelID;
        $Device = $Datos->event_device_type;
        $Usuario = new Usuario($UsuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Mandante->mandante);
        $Registro = new Registro("", $Usuario->usuarioId);

        $array = array(
            "campaignID" => $CampaignID,
            "channelID" => $ChannelID,
            "userId" => $Usuario->usuarioId,
            "name" => $this->quitar_tildes($Usuario->nombre),
            "casinoUserId" => $UsuarioMandante->usumandanteId,
            "email" => $Registro->email,
            "phone" => $Registro->celular,
            "country" => $this->quitar_tildes($Pais->paisNom),
            "partner" => $Mandante->nombre,
            "device" => $Device
        );

        $response = $ContainerMedia->EventRealTimeOptimove(json_decode(json_encode($array)));
        $response = json_encode($response);

        $data = array();
        $data["success"] = true;
        $data["response"] = "Información Enviada";

        return json_decode(json_encode($data));
    }

    /**
     * Obtiene los detalles de ejecución del cliente por campaña y guarda los mensajes en la bandeja de entrada.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param mixed  $EventTypeID     Identificador del tipo de evento.
     * @param mixed  $CampaignID      Identificador de la campaña.
     * @param mixed  $Channel         Canal de la campaña.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [string] $response: Respuesta de la operación.
     */
    public function GetCustomerExecutionDetailsByCampaignMenssageInbox($mandanteUsuario, $PaisId, $EventTypeID, $CampaignID, $Channel)
    {
        if ($PaisId == '0') {
            $PaisId = '';
        }

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $this->metodo = "customers/GetCustomerExecutionDetailsByCampaign";

        $iterations = 10;
        $max_objects = 200000;
        $skip = 0;
        $all_data = [];

        foreach (range(1, $iterations) as $iteration) {
            $Response = $this->connectionGETCamp($CampaignID, $Channel, $skip);
            $data = json_decode($Response, true);

            $all_data = array_merge($all_data, $data);

            $current_count = oldCount($data);
            $skip += $current_count;

            if ($current_count < $max_objects) {
                break;
            }
        }

        syslog(LOG_WARNING, "CAMPA OPTIMOVE: " . ($CampaignID));
        $all_data = json_decode(json_encode($all_data));
        foreach ($all_data as $key => $value) {

            $UsuarioId = $value->{"CustomerID"};
            $TemplateID = $value->{"TemplateID"};
            $ChannelID = $value->{"ChannelID"};

            $redisParam = ['ex' => 18000];
            $redisPrefix = "I2BACK+AgregarMensajeInboxDirecto+UID" . '0' . '+' . $ChannelID . '+' . $TemplateID . '+' . $UsuarioId;

            $redis = RedisConnectionTrait::getRedisInstance(true);

            if ($redis != null) {
                $redis->set($redisPrefix, json_encode(array('0', $ChannelID, $TemplateID, $UsuarioId)), $redisParam);
            }

        }

        $data = array();
        $data["success"] = true;
        $data["response"] = "Inbox Enviados";

        return json_decode(json_encode($data));
    }

    /**
     * Actualiza las métricas de la campaña.
     *
     * @param int    $ChannelID       Identificador del canal.
     * @param int    $CampaignID      Identificador de la campaña.
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     * @param int    $TemplateID      Identificador de la plantilla.
     * @param int    $Enviados        Número de mensajes enviados.
     * @param int    $Fallidos        Número de mensajes fallidos.
     *
     * @return object Objeto con la respuesta de la operación.
     *  - [bool] $success: Indica si la operación fue exitosa.
     *  - [mixed] $response: Respuesta de la operación.
     */
    public function UpdateCampaignMetrics($ChannelID, $CampaignID, $mandanteUsuario, $PaisId, $TemplateID, $Enviados, $Fallidos)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        // Obtener la key
        $this->key = $this->getKey($mandanteUsuario, $PaisId);

        $Resultados = array(
            array(
                "name" => "Rebotado"
            ),
            array(
                "name" => "Enviado"
            ),
        );
        $Resultados = array_column($Resultados, "name");
        $final = array();

        foreach ($Resultados as $value) {
            $v1 = strpos($value, "Enviado");
            $arrayTemp = array();
            if ($v1 == "Enviado") {
                $arrayTemp["ChannelID"] = $ChannelID;
                $arrayTemp["CampaignID"] = $CampaignID;
                $arrayTemp["TemplateID"] = $TemplateID;
                $arrayTemp["MetricID"] = 0;
                $arrayTemp["MetricValue"] = $Enviados;
                array_push($final, $arrayTemp);
            }

            $v2 = strpos($value, "Rebotado");
            $arrayTemp = array();
            if ($v2 == "Rebotado") {
                $arrayTemp["ChannelID"] = $ChannelID;
                $arrayTemp["CampaignID"] = $CampaignID;
                $arrayTemp["TemplateID"] = $TemplateID;
                $arrayTemp["MetricID"] = 9;
                $arrayTemp["MetricValue"] = $Fallidos;
                array_push($final, $arrayTemp);
            }
        }

        $return = array(
            $final
        );
        $this->metodo = "integrations/UpdateCampaignMetrics";

        $Response = $this->connectionPOST($return);

        $Response = json_decode($Response);

        $data = array();
        $data["success"] = true;
        $data["response"] = $Response;

        return json_decode(json_encode($data));
    }

    /**
     * Obtiene la clave correspondiente al mandante y país especificados.
     *
     * @param string $mandanteUsuario Identificador del mandante del usuario.
     * @param string $PaisId          Identificador del país.
     *
     * @return string Clave correspondiente al mandante y país especificados.
     */
    private function getKey($mandanteUsuario, $PaisId)
    {
        $Subproveedor = new Subproveedor("", "OPTIMOVE");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandanteUsuario, $PaisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->URL = $Credentials->URL;

        return $Credentials->KEY;
    }


    /**
     * Realiza una solicitud POST autenticada a la URL especificada.
     *
     * @param mixed $data Los datos a enviar en la solicitud.
     *
     * @return string El resultado de la solicitud.
     */
    public function connectionAutentica($data)
    {
        $data = json_encode($data);

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }



    /**
     * Realiza una solicitud GET autenticada a la URL especificada.
     *
     * @return string El resultado de la solicitud.
     */
    public function connectionGET()
    {
        $headers = array(
            'x-api-key:  ' . $this->key,
            'Content-type: application/json',
            'Accept: application/json'
        );

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }


    /**
     * Realiza una solicitud GET autenticada para obtener detalles de la campaña.
     *
     * @param string $CampaignID Identificador de la campaña.
     * @param string $Channel    Canal de la campaña.
     * @param int    $skip       Número de elementos a omitir en la solicitud.
     *
     * @return string Resultado de la solicitud en formato JSON.
     */
    function connectionGETCamp($CampaignID, $Channel, $skip = 0)
    {
        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper(
            $this->URL . $this->metodo . "?CampaignID=" . $CampaignID . "&ChannelID=" . $Channel . "&$" . "top=2000000&$" . "skip=" . $skip
        );
        $headers = array(
            'x-api-key:  ' . $this->key,
            'Content-type: application/json',
            'Accept: application/json'
        );

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3000,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ]);

        // Ejecutar la solicitud
        $result = $curl->execute();

        syslog(LOG_WARNING, $CampaignID . " OPTIMOVE GetCustomerExecutionDetailsByCampaign REQUEST: " . $this->URL . $this->metodo . "?CampaignID=" . $CampaignID . "&ChannelID=" . $Channel . "&$" . "top=2000000");
        syslog(LOG_WARNING, $CampaignID . " OPTIMOVE GetCustomerExecutionDetailsByCampaign REQUEST: " . $this->key);

        return $result;
    }


    /**
     * Realiza una solicitud GET autenticada para obtener detalles de la plantilla.
     *
     * @param string $TemplateID Identificador de la plantilla.
     *
     * @return string Resultado de la solicitud en formato JSON.
     */
    public function connectionGETTemplate($TemplateID)
    {
        $curl = curl_init($this->URL . $this->metodo . "?TemplateID=" . $TemplateID);
        $headers = array(
            'x-api-key:  ' . $this->key,
            'Content-type: application/json',
            'Accept: application/json'
        );

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }


    /**
     * Realiza una solicitud POST autenticada para una plantilla específica.
     *
     * @param mixed  $data      Los datos a enviar en la solicitud.
     * @param string $ChannelID Identificador del canal.
     *
     * @return string El resultado de la solicitud en formato JSON.
     */
    public function connectionPOSTTemplate($data, $ChannelID)
    {
        $data = json_encode($data);

        $headers = array(
            'x-api-key:  ' . $this->key,
            'Content-type: application/json',
            'Accept: application/json'
        );

        $curl = curl_init($this->URL . $this->metodo . "?ChannelID=" . $ChannelID);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }


    /**
     * Realiza una solicitud POST autenticada a la URL especificada.
     *
     * @param mixed $data Los datos a enviar en la solicitud.
     *
     * @return string El resultado de la solicitud.
     */
    public function connectionPOST($data)
    {
        $data = json_encode($data);

        $headers = array(
            'x-api-key:  ' . $this->key,
            'Content-type: application/json',
            'Accept: application/json'
        );

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }


    /**
     * Realiza una solicitud PUT autenticada a la URL especificada.
     *
     * @param mixed $data Los datos a enviar en la solicitud.
     *
     * @return string El resultado de la solicitud.
     */
    public function connectionPUT($data)
    {
        $data = json_encode($data);

        $headers = array(
            'x-api-key:  ' . $this->key,
            'Content-type: application/json',
            'Accept: application/json'
        );

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);

        curl_close($result);
        return $result;
    }
}
