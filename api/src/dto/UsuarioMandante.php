<?php namespace Backend\dto;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioMandante'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioMandante'
* 
* Ejemplo de uso: 
* $UsuarioMandante = new UsuarioMandante();
*
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioMandante
{

    /**
    * Representación de la columna 'usumandanteId' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $usumandanteId;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'usuarioMandante' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $usuarioMandante;

    /**
    * Representación de la columna 'nombres' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $nombres;

    /**
    * Representación de la columna 'apellidos' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $apellidos;

    /**
    * Representación de la columna 'email' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $email;

    /**
    * Representación de la columna 'saldo' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $saldo;

    /**
    * Representación de la columna 'moneda' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $moneda;

    /**
    * Representación de la columna 'dirIp' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $dirIp;

    /**
    * Representación de la columna 'paisId' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $paisId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'tokenInterno' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $tokenInterno="";

    /**
    * Representación de la columna 'tokenExterno' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $tokenExterno="";

    /**
    * Representación de la columna 'success' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $success;

    /**
    * Representación de la columna 'propio' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $propio;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioMandante'
    *
    * @var string
    */
    var $fechaCrea;

    /**
     *  Representación de la columna 'transaction' de la tabla 'UsuarioMandante'
     *  
     * @var string
     */
    public $transaction;



    /**
    * Constructor de clase
    *
    *
    * @param String $usumandanteId usumandanteId
    * @param String $usuarioMandante usuarioMandante
    * @param String $mandante mandante
    *
    * @return no
    * @throws Exception si UsuarioMandante no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usumandanteId="", $usuarioMandante="", $mandante="", $transaction = "")
    {
        $this->transaction = $transaction;

        if ($usumandanteId != "") 
        {

            $this->usumandanteId = $usumandanteId;

            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();

            $UsuarioMandante = $UsuarioMandanteMySqlDAO->load($this->usumandanteId);

            $this->success = false;

            if ($UsuarioMandante != null && $UsuarioMandante != "") 
            {
                $this->usumandanteId = $UsuarioMandante->usumandanteId;
                $this->mandante = $UsuarioMandante->mandante;
                $this->usuarioMandante = $UsuarioMandante->usuarioMandante;
                $this->nombres = $UsuarioMandante->nombres;
                $this->apellidos = $UsuarioMandante->apellidos;
                $this->email = $UsuarioMandante->email;
                $this->saldo = $UsuarioMandante->saldo;
                $this->moneda = $UsuarioMandante->moneda;
                $this->dirIp = $UsuarioMandante->dirIp;
                $this->paisId = $UsuarioMandante->paisId;
                $this->estado = $UsuarioMandante->estado;
                $this->usucreaId = $UsuarioMandante->usucreaId;
                $this->usumodifId = $UsuarioMandante->usumodifId;
                $this->tokenInterno = $UsuarioMandante->tokenInterno;
                $this->tokenExterno = $UsuarioMandante->tokenExterno;
                $this->propio = $UsuarioMandante->propio;
                $this->fechaModif = $UsuarioMandante->fechaModif;
                $this->fechaCrea = $UsuarioMandante->fechaCrea;
                $this->success = true;


                if($this->tokenInterno ==""){
                    $this->tokenInterno = '';

                }
                if($this->tokenExterno ==""){
                    $this->tokenExterno = '';

                }

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "22");
            }
        }
        elseif ($usuarioMandante != "" && $mandante != "") 
        {    

            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();

            $UsuarioMandante = $UsuarioMandanteMySqlDAO->queryUsuarioMandanteAndMandante($usuarioMandante, $mandante);


            if ($UsuarioMandante != null && $UsuarioMandante != "") 
            {
                $this->usumandanteId = $UsuarioMandante->usumandanteId;
                $this->mandante = $UsuarioMandante->mandante;
                $this->usuarioMandante = $UsuarioMandante->usuarioMandante;
                $this->nombres = $UsuarioMandante->nombres;
                $this->apellidos = $UsuarioMandante->apellidos;
                $this->email = $UsuarioMandante->email;
                $this->saldo = $UsuarioMandante->saldo;
                $this->moneda = $UsuarioMandante->moneda;
                $this->dirIp = $UsuarioMandante->dirIp;
                $this->paisId = $UsuarioMandante->paisId;
                $this->estado = $UsuarioMandante->estado;
                $this->usucreaId = $UsuarioMandante->usucreaId;
                $this->usumodifId = $UsuarioMandante->usumodifId;
                $this->tokenInterno = $UsuarioMandante->tokenInterno;
                $this->tokenInterno = $UsuarioMandante->tokenInterno;
                $this->tokenExterno = $UsuarioMandante->tokenExterno;
                $this->success = true;
                $this->propio = $UsuarioMandante->propio;
                $this->fechaModif = $UsuarioMandante->fechaModif;
                $this->fechaCrea = $UsuarioMandante->fechaCrea;


                if($this->tokenInterno =="")
                {
                    $this->tokenInterno = '';

                }

                if($this->tokenExterno =="")
                {
                    $this->tokenExterno = '';

                }

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "22");
            }

        }

    }

    /**
    * Obtener mensaje WS
    *
    *
    *
    * @return Array $data data
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getWSMessage()
    {
        $profile_id = array();
        $profile_id['id'] = 26678955;
        $profile_id['unique_id'] = 26678955;
        $profile_id['username'] = 26678955;
        $profile_id['name'] = 'TEST';
        $profile_id['first_name'] = 'TEST';
        $profile_id['last_name'] = 'TEST';
        $profile_id['gender'] = "";
        $profile_id['email'] = "";
        $profile_id['phone'] = "";
        $profile_id['reg_info_incomplete'] = false;
        $profile_id['address'] = "";

        $profile_id["reg_date"] = "";
        $profile_id["birth_date"] = "";
        $profile_id["doc_number"] = "";
        $profile_id["casino_promo"] = null;
        $profile_id["currency_name"] = 'USD';

        $profile_id["currency_id"] = 'USD';
        $profile_id["balance"] = '2200';
        $profile_id["casino_balance"] = '2200';
        $profile_id["exclude_date"] = null;
        $profile_id["bonus_id"] = -1;
        $profile_id["games"] = 0;
        $profile_id["super_bet"] = -1;
        $profile_id["country_code"] = '3';
        $profile_id["doc_issued_by"] = null;
        $profile_id["doc_issue_date"] = null;
        $profile_id["doc_issue_code"] = null;
        $profile_id["province"] = null;
        $profile_id["iban"] = null;
        $profile_id["active_step"] = null;
        $profile_id["active_step_state"] = null;
        $profile_id["subscribed_to_news"] = false;
        $profile_id["bonus_balance"] = 0.0;
        $profile_id["frozen_balance"] = 0.0;
        $profile_id["bonus_win_balance"] = 0.0;
        $profile_id["city"] = "Manizales";
        $profile_id["has_free_bets"] = false;
        $profile_id["loyalty_point"] = 0.0;
        $profile_id["loyalty_earned_points"] = 0.0;
        $profile_id["loyalty_exchanged_points"] = 0.0;
        $profile_id["loyalty_level_id"] = null;
        $profile_id["affiliate_id"] = null;
        $profile_id["is_verified"] = false;
        $profile_id["incorrect_fields"] = null;
        $profile_id["loyalty_point_usage_period"] = 0;
        $profile_id["loyalty_min_exchange_point"] = 0;
        $profile_id["loyalty_max_exchange_point"] = 0;
        $profile_id["active_time_in_casino"] = null;
        $profile_id["last_read_message"] = null;
        $profile_id["unread_count"] = 0;
        $profile_id["last_login_date"] = strtotime($fecha_ultima);

        $profile_id["last_login_ip"] = $ip_ultima;
        $profile_id["swift_code"] = null;
        $profile_id["bonus_money"] = 0.0;
        $profile_id["loyalty_last_earned_points"] = 0.0;





        $data = array(
            "7372873025621876707" => array(
                "profile" => array(
                    "26678955" => $profile_id
                )
            )

        );

        return $data;
    }

    /**
    * Obtener pefil del sitio WS
    *
    *
    *
    * @return Array $data data
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getWSProfileSite($sid){

        $saldo = $this->getSaldo();
        $saldoRecargas=0;
        $saldoRetiros=0;
        $saldoBonos=0;
        $saldoFreebet=0;

        $moneda = $this->getMoneda();
        $paisId = $this->getPaisId();
        $usuario_id = $this->getUsumandanteId();

        $jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $this->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';
        $usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);
        $usuarioMensajes = json_decode($usuarioMensajes);
        $mensajes_no_leidos = $usuarioMensajes->count[0]->{".count"};

        $jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $this->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "NOTIFICACION","op":"eq"}] ,"groupOp" : "AND"}';
        $usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);
        $usuarioMensajes = json_decode($usuarioMensajes);
        $notificacion_nuevas = $usuarioMensajes->count[0]->{".count"};

        $fecha_ultima = "";
        $ip_ultima = "";

        $Mandante = new Mandante($this->getMandante());

        if ($Mandante->propio === "S") {

            $Usuario = new Usuario($this->getUsuarioMandante());
            $Registro = new Registro("", $this->getUsuarioMandante());
            $primer_nombre = "$Registro->nombre1";
            $segundo_nombre = $Registro->nombre2;
            $primer_apellido = $Registro->apellido1;
            $segundo_apellido = $Registro->apellido2;
            $celular = $Registro->celular;

            $fecha_ultima = $Usuario->fechaUlt;
            $ip_ultima=$Usuario->dirIp;

            $UsuarioPerfil = new UsuarioPerfil($this->usuarioMandante);

            switch ($UsuarioPerfil->getPerfilId()){
                case "USUONLINE":

                    $saldo = $Usuario->getBalance();
                    $saldoRecargas = $Registro->getCreditosBase();
                    $saldoRetiros = $Registro->getCreditos();
                    $saldoBonos = $Registro->getCreditosBono();

                    break;

                case "MAQUINAANONIMA":


                    /*$PuntoVenta = new PuntoVenta("",$this->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $saldo = $SaldoJuego;*/

                    $saldo = $Usuario->getBalance();

                    break;
            }


        }

        $response = array();

        $response['code'] = 0;

        $data = array();
        $profile = array();
        $profile_id = array();

        $min_bet_stakes = array();


        $profile_id['id'] = $usuario_id;
        $profile_id['unique_id'] = $usuario_id;
        $profile_id['username'] = $usuario_id;
        $profile_id['name'] = $this->getNombres() . " " . $this->getApellidos();
        $profile_id['first_name'] = $primer_nombre . " " . $segundo_nombre;
        $profile_id['last_name'] = $primer_apellido . " " . $segundo_apellido;
        $profile_id['gender'] = "";
        $profile_id['email'] = "";
        $profile_id['phone'] = $celular;
        $profile_id['reg_info_incomplete'] = false;
        $profile_id['address'] = "";


        $profile_id["reg_date"] = "";
        $profile_id["birth_date"] = "";
        $profile_id["doc_number"] = "";
        $profile_id["casino_promo"] = null;
        $profile_id["currency_name"] = $moneda;

        $profile_id["currency_id"] = $moneda;
        $profile_id["balance"] = $saldo;

        $profile_id["balanceDeposit"] = floatval($saldoRecargas);
        $profile_id["balanceWinning"] = $saldoRetiros;
        $profile_id["balanceBonus"] = $saldoBonos;
        $profile_id["balanceFreebet"] = $saldoFreebet;


        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if($ConfigurationEnvironment->isDevelopment()) {

            $dniFrontBack = 0;
            $dniFront = 0;


            if ($Usuario->verifcedulaAnt == "S") {
                $dniFront = 3;
            }

            if ($Usuario->verifcedulaPost == "S") {
                $dniFrontBack = 3;
            }

            if ($dniFront == 0 || $dniFrontBack == 0) {
                $MaxRows = 10;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $whereStr = '';

                if ($dniFront == 0) {
                    $whereStr = "'USUDNIANTERIOR'";
                }

                if ($dniFrontBack == 0) {
                    if ($whereStr != '') {
                        $whereStr = $whereStr . ',';
                    }
                    $whereStr = $whereStr . "'USUDNIPOSTERIOR'";
                }


                $rules = [];

                array_push($rules, array("field" => "usuario.usuario_id", "data" => $this->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_log.tipo", "data" => "$whereStr", "op" => "in"));
                array_push($rules, array("field" => "usuario_log.estado", "data" => "P", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $jsonfiltro = json_encode($filtro);

                $UsuarioLog = new UsuarioLog();
                $data = $UsuarioLog->getUsuarioLogsCustom("usuario_log.*", "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, $grouping);
                $data = json_decode($data);

                foreach ($data->data as $key => $value) {

                    switch ($value->{'usuario_log.tipo'}) {
                        case "USUDNIANTERIOR":
                            if ($dniFront == 0) {
                                $dniFront = 2;
                            }

                            break;
                        case "USUDNIPOSTERIOR":
                            if ($dniFrontBack == 0) {
                                $dniFrontBack = 2;
                            }

                            break;
                    }
                }

                if ($dniFront == 0 || $dniFrontBack == 0) {

                    $MaxRows = 10;
                    $OrderedItem = 1;
                    $SkeepRows = 0;


                    $whereStr = '';

                    if ($dniFront == 0) {
                        $whereStr = "'USUDNIANTERIOR'";
                    }

                    if ($dniFrontBack == 0) {
                        if ($whereStr != '') {
                            $whereStr = $whereStr . ',';
                        }
                        $whereStr = $whereStr . "'USUDNIPOSTERIOR'";
                    }

                    $rules = [];

                    array_push($rules, array("field" => "usuario.usuario_id", "data" => $this->getUsuarioMandante(), "op" => "eq"));
                    array_push($rules, array("field" => "usuario_log2.tipo", "data" => $whereStr, "op" => "in"));
                    array_push($rules, array("field" => "usuario_log2.estado", "data" => "NA", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $jsonfiltro = json_encode($filtro);

                    $UsuarioLog = new UsuarioLog2();
                    $data = $UsuarioLog->getUsuarioLog2sCustom("usuario_log2.*", "usuario_log2.usuariolog_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, $grouping);
                    $data = json_decode($data);

                    foreach ($data->data as $key => $value) {

                        switch ($value->{'usuario_log2.tipo'}) {
                            case "USUDNIANTERIOR":
                                if ($dniFront == 0) {
                                    $dniFront = 1;
                                }
                                break;
                            case "USUDNIPOSTERIOR":
                                if ($dniFrontBack == 0) {
                                    $dniFrontBack = 1;
                                }
                                break;
                        }
                    }


                }
            }


            $profile_id["dniFront"] = $dniFront;
            $profile_id["dniFrontBack"] = $dniFrontBack;

        }
        /* $JOINSERVICES = new JOINSERVICES();

         $response2 = $JOINSERVICES->getBalance2($this->getUsumandanteId());

         $saldoXML = new SimpleXMLElement($response2);

         if ($saldoXML->RESPONSE->RESULT != "KO") {
             $saldo = $saldoXML->RESPONSE->BALANCE->__toString();
             $profile_id["casino_balance"] = $saldo;

         }*/


        //$profile_id["casino_balance"] = $saldo;
        $profile_id["exclude_date"] = null;
        $profile_id["bonus_id"] = -1;
        $profile_id["games"] = 0;
        $profile_id["super_bet"] = -1;
        $profile_id["country_code"] = $paisId;
        $profile_id["doc_issued_by"] = null;
        $profile_id["doc_issue_date"] = null;
        $profile_id["doc_issue_code"] = null;
        $profile_id["province"] = null;
        $profile_id["iban"] = null;
        $profile_id["active_step"] = null;
        $profile_id["active_step_state"] = null;
        $profile_id["subscribed_to_news"] = false;
        $profile_id["bonus_balance"] = 0.0;
        $profile_id["frozen_balance"] = 0.0;
        $profile_id["bonus_win_balance"] = 0.0;
        $profile_id["city"] = "city";



        $profile_id["has_free_bets"] = false;
        $profile_id["loyalty_point"] = 0.0;
        $profile_id["loyalty_earned_points"] = 0.0;
        $profile_id["loyalty_exchanged_points"] = 0.0;
        $profile_id["loyalty_level_id"] = null;
        $profile_id["affiliate_id"] = null;
        $profile_id["is_verified"] = false;
        $profile_id["incorrect_fields"] = null;
        $profile_id["loyalty_point_usage_period"] = 0;
        $profile_id["loyalty_min_exchange_point"] = 0;
        $profile_id["loyalty_max_exchange_point"] = 0;
        $profile_id["active_time_in_casino"] = null;
        $profile_id["last_read_message"] = null;
        $profile_id["unread_count"] = $mensajes_no_leidos;
        $profile_id["newnotification_count"] = $notificacion_nuevas;
        $profile_id["last_login_date"] = strtotime($fecha_ultima);
        $profile_id["last_login_ip"] = $ip_ultima;

        $profile_id["swift_code"] = null;
        $profile_id["bonus_money"] = 0.0;
        $profile_id["loyalty_last_earned_points"] = 0.0;



        $profile_id["state"] = 1;
        $profile_id["contingency"] = 0;
        $profile_id["contingencySports"] = 0;
        $profile_id["contingencyCasino"] = 0;
        $profile_id["contingencyLiveCasino"] = 0;
        $profile_id["contingencyVirtuals"] = 0;
        $profile_id["contingencyPoker"] = 0;


        if($Usuario != "" ){

            if($Usuario->estado == "I"){
                $profile_id["state"] = 0;
            }
            if($Usuario->contingencia == "A"){
                $profile_id["contingency"] = 1;
            }
            if($Usuario->contingenciaDeportes == "A"){
                $profile_id["contingencySports"] = 1;
            }
            if($Usuario->contingenciaCasino == "A"){
                $profile_id["contingencyCasino"] = 1;
            }
            if($Usuario->contingenciaCasvivo == "A"){
                $profile_id["contingencyLiveCasino"] = 1;
            }
            if($Usuario->contingenciaVirtuales == "A"){
                $profile_id["contingencyVirtuals"] = 1;
            }
            if($Usuario->contingenciaPoker == "A"){
                $profile_id["contingencyPoker"] = 1;
            }
        }

        //Para maquina
        /*
         * 1 -> readticket
         *
         */

        $MaxRows = 1;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];

        array_push($rules, array("field" => "usuario_log.usuario_id", "data" => $this->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "usuario_log.tipo", "data" => "ESTADOUSUARIO", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json2 = json_encode($filtro);

        $select = " usuario_log.* ";


        $UsuarioLog = new UsuarioLog();
        $data2 = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, "");
        $data2 = json_decode($data2);

        $stateM=0;

        if($data2->data[0]->{"usuario_log.valor_antes"} =="READTICKET"){
            if($data2->data[0]->{"usuario_log.estado"}=="A"){
                $profile_id["message"] = array();
                $profile_id["message"]["type"]="success";
                $profile_id["message"]["title"]="Ticket leido";
                $profile_id["message"]["content"]="Ticket leido satisfactoriamente";

                $stateM=2;

            }else{
                $stateM=1;

            }
        }

        if($data2->data[0]->{"usuario_log.valor_antes"} =="DEPOSIT"){
            if($data2->data[0]->{"usuario_log.estado"}=="A"){
                $profile_id["message"] = array();
                $profile_id["message"]["type"]="success";
                $profile_id["message"]["title"]="Deposito";
                $profile_id["message"]["content"]="Deposito satisfactorio";

                $stateM=2;

            }else{
                $stateM=1;

            }
        }


        $profile_id["StateM"] = $stateM;



        $limites = array();

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

        $limitesArray = $UsuarioConfiguracionMySqlDAO->queryByUsuarioId($this->getUsuarioMandante());

        foreach ($limitesArray as $item) {

            $tipo = "";

            switch ($item->getTipo()) {
                case "EXCTIME":
                    $profile_id["active_time_in_casino"] = intval($item->getValor());

                    break;


            }


        }

        $MaxRows = 1;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];

        array_push($rules, array("field" => "usuario_log.usuario_id", "data" => $this->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "usuario_log.tipo", "data" => "ESTADOUSUARIO", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json2 = json_encode($filtro);

        $select = " usuario_log.* ";


        $UsuarioLog = new UsuarioLog();
        $data2 = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, "");
        $data2 = json_decode($data2);

        $stateM=0;

        if($data2->data[0]->{"usuario_log.valor_antes"} =="READTICKET"){
            if($data2->data[0]->{"usuario_log.estado"}=="A"){
                $profile_id["message"] = array();
                $profile_id["message"]["type"]="success";
                $profile_id["message"]["title"]="Ticket leido";
                $profile_id["message"]["content"]="Ticket leido satisfactoriamente";

                $stateM=2;

            }else{
                $stateM=1;

            }
        }

        if($data2->data[0]->{"usuario_log.valor_antes"} =="DEPOSIT"){
            if($data2->data[0]->{"usuario_log.estado"}=="A"){
                $profile_id["message"] = array();
                $profile_id["message"]["type"]="success";
                $profile_id["message"]["title"]="Deposito";
                $profile_id["message"]["content"]="Deposito satisfactorio";

                $stateM=2;

            }else{
                $stateM=1;

            }
        }

        if($dniFront == 3 && $dniFrontBack ==  3){
            $profile_id["is_verified"] = true;
        }

        $profile_id["StateM"] = $stateM;


        $data = array(
            "7040" . $sid . "1" => array(
                "profile" => array(
                    $usuario_id => $profile_id,
                ),
            ),

        );

        return $data;

    }





    /**
     * Obtener el campo usumandanteId de un objeto
     *
     * @return String usumandanteId usumandanteId
     * 
     */
    public function getUsumandanteId()
    {
        return $this->usumandanteId;
    }

    /**
     * Modificar el campo 'usumandanteId' de un objeto
     *
     * @param String $usumandanteId usumandanteId
     *
     * @return no
     *
     */
    public function setUsumandanteId($usumandanteId)
    {
        $this->usumandanteId = $usumandanteId;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     * 
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo usuarioMandante de un objeto
     *
     * @return String usuarioMandante usuarioMandante
     * 
     */
    public function getUsuarioMandante()
    {
        return $this->usuarioMandante;
    }

    /**
     * Modificar el campo 'usuarioMandante' de un objeto
     *
     * @param String $usuarioMandante usuarioMandante
     *
     * @return no
     *
     */
    public function setUsuarioMandante($usuarioMandante)
    {
        $this->usuarioMandante = $usuarioMandante;
    }

    /**
     * Obtener el campo nombres de un objeto
     *
     * @return String nombres nombres
     * 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Modificar el campo 'nombres' de un objeto
     *
     * @param String $nombres nombres
     *
     * @return no
     *
     */    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    /**
     * Obtener el campo apellidos de un objeto
     *
     * @return String apellidos apellidos
     * 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Modificar el campo 'apellidos' de un objeto
     *
     * @param String $apellidos apellidos
     *
     * @return no
     *
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * Obtener el campo email de un objeto
     *
     * @return String email email
     * 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Modificar el campo 'email' de un objeto
     *
     * @param String $email email
     *
     * @return no
     *
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Obtener el campo saldo de un objeto
     *
     * @return String saldo saldo
     * 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Modificar el campo 'saldo' de un objeto
     *
     * @param String $saldo saldo
     *
     * @return no
     *
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    }

    /**
     * Obtener el campo moneda de un objeto
     *
     * @return String moneda moneda
     * 
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Modificar el campo 'moneda' de un objeto
     *
     * @param String $moneda moneda
     *
     * @return no
     *
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    }

    /**
     * Obtener el campo dirIp de un objeto
     *
     * @return String dirIp dirIp
     * 
     */
    public function getDirIp()
    {
        return $this->dirIp;
    }

    /**
     * Modificar el campo 'dirIp' de un objeto
     *
     * @param String $dirIp dirIp
     *
     * @return no
     *
     */
    public function setDirIp($dirIp)
    {
        $this->dirIp = $dirIp;
    }

    /**
     * Obtener el campo paisId de un objeto
     *
     * @return String paisId paisId
     * 
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Modificar el campo 'paisId' de un objeto
     *
     * @param String $paisId paisId
     *
     * @return no
     *
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     * 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo tokenInterno de un objeto
     *
     * @return String tokenInterno tokenInterno
     * 
     */
    public function getTokenInterno()
    {
        return $this->tokenInterno;
    }

    /**
     * Modificar el campo 'tokenInterno' de un objeto
     *
     * @param String $tokenInterno tokenInterno
     *
     * @return no
     *
     */
    public function setTokenInterno($tokenInterno)
    {
        $this->tokenInterno = $tokenInterno;
    }
    
    /**
     * Obtener el campo tokenExterno de un objeto
     *
     * @return String tokenExterno tokenExterno
     * 
     */
    public function getTokenExterno()
    {
        return $this->tokenExterno;
    }

    /**
     * Modificar el campo 'tokenExterno' de un objeto
     *
     * @param String $tokenExterno tokenExterno
     *
     * @return no
     *
     */
    public function setTokenExterno($tokenExterno)
    {
        $this->tokenExterno = $tokenExterno;
    }

    /**
     * Obtener el campo propio de un objeto
     *
     * @return String propio propio
     * 
     */
    public function getPropio()
    {
        return $this->propio;
    }

    /**
     * Modificar el campo 'propio' de un objeto
     *
     * @param String $propio propio
     *
     * @return no
     *
     */
    public function setPropio($propio)
    {
        $this->propio = $propio;
    }





    /**
    * Realizar una consulta en la tabla de UsuarioMandante 'UsuarioMandante'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    * @param String $grouping columna para agrupar
    *
    * @return Array resultado de la consulta
    * @throws Exception si las transacciones no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuariosMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($this->transaction);

        $transacciones = $UsuarioMandanteMySqlDAO->queryUsuariosMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") 
        {
            return $transacciones;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Obtiene el affiliationPath del proveedor Altenar.
     *
     * @return string El affiliationPath del proveedor Altenar.
     */
    public function getAffiliationPathAltenar()
    {
        $aff="";

        $Mandante = new Mandante($this->mandante);
        $Pais = new Pais($this->paisId);

        $pathPartner = $Mandante->pathItainment;
        $pathFixed = $Pais->codigoPath;
        $usermoneda = $this->moneda;
        $userpath = $pathFixed;

        $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;

        //Configuramos la mandante
        if ($Mandante->mandante != '') {
            if (is_numeric($Mandante->mandante)) {
                if (intval($Mandante->mandante) > 2) {

                    $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                    if (intval($Mandante->mandante) == 9) {

                        $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                    }

                }
            }
        }

        //Configuramos Partner si esta vacio
        if ($pathPartner == '') {

            $pathPartner = "1:colombia,S3";

            //Configuramos Partner por mandante
            if ($Mandante->mandante == 1) {
                $pathPartner = "1:ibet,S1";
            }


            if ($Mandante->mandante == 2) {
                $pathPartner = "1:justbetja,S2";
            }


            if ($Mandante->mandante == 3) {
                $pathPartner = "1:miravalle,S7";
            }


            if ($Mandante->mandante == 4) {
                $pathPartner = "1:casinogranpalacio,S20";
            }


            if ($Mandante->mandante == 5) {
                $pathPartner = "1:casinointercontinental,S9";
            }


            if ($Mandante->mandante == 6) {
                $pathPartner = "1:netabet,S10";
            }


            if ($Mandante->mandante == 7) {
                $pathPartner = "1:casinoastoria,S11";
            }


            if ($Mandante->mandante == 8) {
                $pathPartner = "1:ecuabet,S12";
            }

            if ($Mandante->mandante == 9) {
                $pathPartner = "1:winbet,S13";
            }


            if ($Mandante->mandante == 0 && $this->paisId == '60') {
                $pathPartner = "1:doradobet,S0-60";
            }

            if ($Mandante->mandante == '0') {
                $pathPartner = "1:doradobet,S" . $Mandante->mandante . "-" . $this->paisId;
            }
            if ($Mandante->mandante == '8') {
                $pathPartner = "1:ecuabet,S" . $Mandante->mandante;
            }

                $ConfigurationEnvironment = new ConfigurationEnvironment();


                if ($ConfigurationEnvironment->isDevelopment()) {

                    if ($Mandante->mandante == '0') {
                        $pathPartner = "1:doradobet,S" . '34';
                    }

                } else {

                    if ($Mandante->mandante == '0') {
                        $pathPartner = "1:doradobet,S0-" . $Pais->paisId;
                    }
                }


        }


        if ($Mandante->mandante == '12') {
            $pathPartner = "1:powerbet,S16-" . $this->paisId;
        }


        if ($Mandante->mandante == '18') {
            $pathPartner = "1:gangabet,S22-" . $this->paisId;
            if ($this->paisId == '173') {
                $pathPartner = "1:gangabet,S22-" . $this->paisId;
            }
        }

            if ($Mandante->mandante == '19') {
                $pathPartner = "1:vfst,vfst";
            }

        $UsuarioPerfil = new UsuarioPerfil($this->usuarioMandante);
        $Perfil = new Perfil($UsuarioPerfil->perfilId);

        switch ($Perfil->getTipo()) {
            case 'U':
                $perfil = 'USER';
                break;
            case 'A':
                $perfil = 'ADMIN';
                break;
            case 'M':
                $perfil = 'MACHINE';
                break;
            default:
                $perfil = 'COMERCIAL';
                break;

        }

        if ($perfil == 'COMERCIAL') {
            $Usuario = new Usuario($this->usuarioMandante);
            $PuntoVenta = new PuntoVenta('', $Usuario->puntoventaId);
            $suma = 50000 + intval($PuntoVenta->puntoventaId);
            $pathFixed = '2:Shop' . $suma . ',' . $suma;
        }

        $aff='0:Betlatam,L3|' . $pathPartner . '|' . $pathFixed;

        if($Mandante->mandante=='27'){
            if($this->paisId == '94'){
                $aff="0:Betlatam,L1|1:Ganaplay.gt,Ganaplay.gt|" . $pathFixed;
            }
            if($this->paisId == '68'){
                $aff="0:Betlatam,L1|1:Ganaplay.sv,Ganaplay.sv|" . $pathFixed;
            }
        }


        return $aff;

    }


}
?>
