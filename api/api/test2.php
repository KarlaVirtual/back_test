<?php
/**
* Test
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
use Backend\dto\Consecutivo;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\integrations\casino\IES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\websocket\WebsocketUsuario;
error_reporting(E_ALL);
ini_set('display_errors', 'ON');
require_once __DIR__ . '../../vendor/autoload.php';
date_default_timezone_set('America/Bogota');

/**
* Resolver API
*
*
* @param array $json json
*
* @return String
* @throws Exception si el token está vacío
*
* @access public
*/
function resolverAPI($json)
{
    $claveEncrypt_Retiro = "12hur12b";


    $fecha_hoy = date('Y-m-d', time());
    try {

        switch ($json->command) {

            case "request_session":
                $cookie = $json->params->ck;

                $cookie = validarCampoSecurity($cookie, true);


                if ($cookie != "") {

                    $UsuarioToken = new UsuarioToken("", "0", "", $cookie);

                    $UsuarioToken->setRequestId($json->session->sid);

                    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->update($UsuarioToken);

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = array(
                        "sid" => $json->session->sid,
                        "ip" => get_client_ip(),
                        "skin" => "test",
                        "data_source" => 0,
                    );
                } else {
                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = array(
                        "sid" => $json->session->sid,
                        "ip" => get_client_ip(),
                        "skin" => "test",
                        "data_source" => 0,
                    );
                }
                //$json->session->sid

                break;

            case "login":

                $usuario = $json->params->username;
                $clave = $json->params->password;

                $usuario = validarCampoSecurity($usuario, true);

                $Usuario = new Usuario();

                $Usuario->dirIp = $json->session->usuarioip;

                $responseU = $Usuario->login($usuario, $clave);

                try {


                    $UsuarioToken = new UsuarioToken("", '1', $responseU->user_id);

                    $UsuarioToken->setRequestId($json->session->sid);
                    $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));

                    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {


                    if ($e->getCode() == "21") {

                        $UsuarioToken = new UsuarioToken();

                        $UsuarioToken->setRequestId($json->session->sid);
                        $UsuarioToken->setProveedorId(1);
                        $UsuarioToken->setUsuarioId($responseU->user_id);
                        $UsuarioToken->setToken($UsuarioToken->createToken());

                        $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));
                        $UsuarioToken->setUsumodifId(0);
                        $UsuarioToken->setUsucreaId(0);

                        $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                        $UsuarioTokenMySqlDAO->getTransaction()->commit();

                    }

                }

                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;

                $response["data"] = array(
                    "auth_token" => $UsuarioToken->getToken(),
                    "user_id" => $responseU->user_id,
                    "ck" => $UsuarioToken->getCookie(),
                );

                break;

            case "user_messages":

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 100;
                }

                $mensajesEnviados = [];
                $mensajesRecibidos = [];


                $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                $usuarios = json_decode($usuarios);


                foreach ($usuarios->data as $key => $value) {

                    $array = [];

                    $array["body"] = $value->{"usuario_mensaje.body"};
                    $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                    $array["open"] = false;
                    $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
                    $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
                    $array["subject"] = $value->{"usuario_mensaje.msubject"};
                    $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};

                    array_push($mensajesRecibidos, $array);

                }

                $json2 = '{"rules" : [{"field" : "usuario_mensaje.usufrom_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"}] ,"groupOp" : "AND"}';

                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                $usuarios = json_decode($usuarios);


                foreach ($usuarios->data as $key => $value) {

                    $array = [];

                    $array["body"] = $value->{"usuario_mensaje.body"};
                    $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                    $array["date"] = 1514649066;
                    $array["id"] = 123213213;
                    $array["subject"] = $value->{"usuario_mensaje.msubject"};
                    $array["thread_id"] = null;

                    array_push($mensajesEnviados, $array);

                }


                $response = array();


                $response["data"] = array(
                    "messages" => array()
                );
                if ($json->params->where->type == 1) {
                    $response["data"] = array(
                        "messages" => $mensajesEnviados
                    );
                } else {
                    $response["data"] = array(
                        "messages" => $mensajesRecibidos
                    );
                }
                $response["code"] = 0;
                $response["rid"] = $json->rid;


                break;

            case "add_user_message":

                $subject = $json->params->subject;
                $body = $json->params->body;

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                //$UsuarioMensaje = new UsuarioMensaje();
                //$UsuarioMensaje->usufromId = $UsuarioMandante->usumandanteId;
                //$UsuarioMensaje->usutoId = 0;
                //$UsuarioMensaje->isRead = 0;
                //$UsuarioMensaje->body = $body;
                //$UsuarioMensaje->msubject = $subject;

                //$UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                //$UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                //$UsuarioMensajeMySqlDAO->getTransaction()->commit();

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();


                $response = array();
                $response["data"] = array("result" => 0);
                $response["code"] = 0;
                $response["rid"] = $json->rid;

                break;

            case  "read_user_message":

                $mensaje_id = $json->params->message_id;

                $UsuarioMensaje = new UsuarioMensaje($mensaje_id);
                $UsuarioMensaje->isRead = 1;

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();


                $response = array();
                $response["data"] = array("result" => 0);
                $response["code"] = 0;
                $response["rid"] = $json->rid;

                break;

                break;

            case "register_user":


                $user_info = $json->params->user_info;

                $address = $user_info->address;
                $birth_date = $user_info->birth_date;
                $country_code = $user_info->country_code;
                $currency_name = $user_info->currency_name;
                $department_id = $user_info->department_id;
                $docnumber = $user_info->docnumber;
                $doctype_id = $user_info->doctype_id;
                $email = $user_info->email;
                $email2 = $user_info->email2;
                $expedition_day = $user_info->expedition_day;
                $expedition_month = $user_info->expedition_month;
                $expedition_year = $user_info->expedition_year;
                $first_name = $user_info->first_name;
                $gender = $user_info->gender;
                $landline_number = $user_info->landline_number;
                $lang_code = $user_info->lang_code;
                $language = $user_info->language;
                $last_name = $user_info->last_name;
                $limit_deposit_day = $user_info->limit_deposit_day;
                $limit_deposit_month = $user_info->limit_deposit_month;
                $limit_deposit_week = $user_info->limit_deposit_week;
                $middle_name = $user_info->middle_name;
                $nationality_id = $user_info->nationality_id;
                $password = $user_info->password;
                $phone = $user_info->phone;
                $second_last_name = $user_info->second_last_name;
                $site_id = $user_info->site_id;


                $nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;
                $clave_activa = GenerarClaveTicket(15);


                switch ($doctype_id) {
                    case 1:
                        $doctype_id = "C";
                        break;

                    case 2:
                        $doctype_id = "E";

                        break;

                    case 3:
                        $doctype_id = "P";

                        break;
                }


                $Registro = new Registro();
                $Registro->setCedula($docnumber);

                if (!$Registro->existeCedula()) {

                    $Consecutivo = new Consecutivo("", "USU", "");

                    $consecutivo_usuario = $Consecutivo->numero;

                    $consecutivo_usuario++;

                    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                    $Consecutivo->setNumero($consecutivo_usuario);


                    $ConsecutivoMySqlDAO->update($Consecutivo);

                    $ConsecutivoMySqlDAO->getTransaction()->commit();


                    $premio_max = "";
                    $premio_max1 = "";
                    $premio_max2 = "";
                    $premio_max3 = "";
                    $cant_lineas = "";
                    $lista_id = "";
                    $regalo_registro = "";
                    $valor_directo = "";
                    $valor_evento = "";
                    $valor_diario = "";
                    $destin1 = "";
                    $destin2 = "";
                    $destin3 = "";

                    $apuesta_min = "";
                    $moneda_default = "";

                    $token_itainment = GenerarClaveTicket2(12);

                    $dir_ip = $json->session->usuarioip;

                    $RegistroMySqlDAO = new RegistroMySqlDAO();
                    $Transaction = $RegistroMySqlDAO->getTransaction();


                    $Registro->setNombre($nombre);
                    $Registro->setEmail($email);
                    $Registro->setClaveActiva($clave_activa);
                    $Registro->setEstado("I");
                    $Registro->usuarioId = $consecutivo_usuario;
                    $Registro->setCelular($phone);
                    $Registro->setCreditosBase(0);
                    $Registro->setCreditos(0);
                    $Registro->setCreditosAnt(0);
                    $Registro->setCreditosBaseAnt(0);
                    //$Registro->setCiudadId($department_id->cities[0]->id);
                    $Registro->setCiudadId(1);
                    $Registro->setCasino(0);
                    $Registro->setCasinoBase(0);
                    $Registro->setMandante('0');
                    $Registro->setNombre1($first_name);
                    $Registro->setNombre2($middle_name);
                    $Registro->setApellido1($last_name);
                    $Registro->setApellido2($second_last_name);
                    $Registro->setSexo($gender);
                    $Registro->setTipoDoc($doctype_id);
                    $Registro->setDireccion($address);
                    $Registro->setTelefono($landline_number);
                    $Registro->setCiudnacimId(1);
                    $Registro->setNacionalidadId($nationality_id->code);
                    $Registro->setDirIp($dir_ip);
                    $Registro->setOcupacionId(0);
                    $Registro->setRangoingresoId(0);
                    $Registro->setOrigenfondosId(0);
                    $Registro->setPaisnacimId(1);
                    $Registro->setPuntoVentaId(0);
                    $Registro->setPreregistroId(0);
                    $Registro->setCreditosBono(0);
                    $Registro->setCreditosBonoAnt(0);
                    $Registro->setPreregistroId(0);


                    $RegistroMySqlDAO->insert($Registro);

                    $RegistroMySqlDAO->getTransaction()->commit();


                    $Usuario = new Usuario();


                    $Usuario->usuarioId = $consecutivo_usuario;

                    $Usuario->login = $email;

                    $Usuario->nombre = $nombre;

                    $Usuario->estado = 'I';

                    $Usuario->fechaUlt = date('Y-m-d H:i:s');

                    $Usuario->claveTv = '';

                    $Usuario->estadoAnt = 'I';

                    $Usuario->intentos = 0;

                    $Usuario->estadoEsp = 'I';

                    $Usuario->observ = '';

                    $Usuario->dirIp = $json->session->usuarioip;

                    $Usuario->eliminado = 'N';

                    $Usuario->mandante = '0';

                    $Usuario->usucreaId = '0';

                    $Usuario->usumodifId = '0';

                    $Usuario->claveCasino = '';

                    $Usuario->tokenItainment = $token_itainment;

                    $Usuario->fechaClave = '';

                    $Usuario->retirado = '';

                    $Usuario->fechaRetiro = '';

                    $Usuario->horaRetiro = '';

                    $Usuario->usuretiroId = '0';

                    $Usuario->bloqueoVentas = 'N';

                    $Usuario->infoEquipo = '';

                    $Usuario->estadoJugador = 'AC';

                    $Usuario->tokenCasino = '';

                    $Usuario->sponsorId = 0;

                    $Usuario->verifCorreo = 'N';

                    $Usuario->paisId = '1';

                    $Usuario->moneda = $moneda_default;

                    $Usuario->idioma = $idioma;

                    $Usuario->permiteActivareg = 'N';

                    $Usuario->test = 'N';

                    $Usuario->tiempoLimitedeposito = '';

                    $Usuario->tiempoAutoexclusion = '';

                    $Usuario->cambiosAprobacion = 'S';

                    $Usuario->timezone = '-5';

                    //$UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaccion);
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                    $UsuarioMySqlDAO->insert($Usuario);

                    $UsuarioMySqlDAO->getTransaction()->commit();

                    $UsuarioOtrainfo = new UsuarioOtrainfo();

                    $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
                    $UsuarioOtrainfo->fechaNacim = $birth_date;
                    $UsuarioOtrainfo->mandante = '0';
                    $UsuarioOtrainfo->bancoId = '0';
                    $UsuarioOtrainfo->numCuenta = '0';
                    $UsuarioOtrainfo->anexoDoc = 'N';



                    //$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaccion);
                    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();

                    $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);
                    $UsuarioOtrainfoMySqlDAO->getTransaction()->commit();

                    $UsuarioPerfil = new UsuarioPerfil();

                    $UsuarioPerfil->setUsuarioId($consecutivo_usuario);
                    $UsuarioPerfil->setPerfilId('USUONLINE');
                    $UsuarioPerfil->setMandante('0');


                    //$UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaccion);
                    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
                    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);
                    $UsuarioPerfilMySqlDAO->getTransaction()->commit();

                    $UsuarioPremiomax = new UsuarioPremiomax();

                    $UsuarioPremiomax->premiomaxId = $premio_max;

                    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

                    $UsuarioPremiomax->premioMax = $premio_max1;

                    $UsuarioPremiomax->usumodifId = '0';


                    $UsuarioPremiomax->cantLineas = $cant_lineas;

                    $UsuarioPremiomax->premioMax1 = $premio_max1;

                    $UsuarioPremiomax->premioMax2 = $premio_max2;

                    $UsuarioPremiomax->premioMax3 = $premio_max3;

                    $UsuarioPremiomax->apuestaMin = $apuesta_min;

                    $UsuarioPremiomax->valorDirecto = $valor_directo;
                    $UsuarioPremiomax->premioDirecto = $valor_directo;


                    $UsuarioPremiomax->mandante = '0';
                    $UsuarioPremiomax->optimizarParrilla = 'N';


                    $UsuarioPremiomax->valorEvento = $valor_evento;

                    $UsuarioPremiomax->valorDiario = $valor_diario;

                    //$UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaccion);
                    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
                    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);
                    $UsuarioPremiomaxMySqlDAO->getTransaction()->commit();

                    //$Transaccion->commit();

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = array(
                        "result" => "OK"

                    );

                }

                break;
            case "logout":

                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = null;

                break;


            case "user_code_bonus":

                $bonuscode = $json->params->bonuscode;

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 100;
                }

                $mensajesEnviados = [];
                $mensajesRecibidos = [];


                $json2 = '{"rules" : [{"field" : "promocional_log.codigo", "data": "' . $bonuscode . '","op":"eq"}] ,"groupOp" : "AND"}';

                $PromocionalLog = new PromocionalLog();
                $promociones = $PromocionalLog->getPromocionalLogsCustom(" promocional_log.*,bono_interno.* ", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                $promociones = json_decode($promociones);


                $existeBono=false;
                $bonoRedimido=false;


                foreach ($promociones->data as $key => $value) {

                    if($value->{'promocional_log.estado'} == "L"){
                        $PromocionalLog = new PromocionalLog('','',$value->{'promocional_log.promolog_id'});
                        $PromocionalLog->usuarioId = $UsuarioMandante->getUsuarioMandante();


                        if($value->{'bono_interno.tipo'} == 3){

                            $rollower=0;
                            $valorbono=0;

                            $rules = [];

                            array_push($rules, array("field" => "bono_detalle.bono_id", "data" => $value->{'bono_interno.bono_id'}, "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");

                            if ($SkeepRows == "") {
                                $SkeepRows = 0;
                            }

                            if ($OrderedItem == "") {
                                $OrderedItem = 1;
                            }

                            if ($MaxRows == "") {
                                $MaxRows = 100;
                            }

                            $json2 = json_encode($filtro);

                            $BonoDetalle = new BonoDetalle();

                            $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json2, TRUE);

                            $bonodetalles = json_decode($bonodetalles);

                            $final = [];


                            foreach ($bonodetalles->data as $key => $value) {
                                if($value->{"bono_detalle.tipo"} == "WFACTORBONO"){
                                    $rollower=$value->{"bono_detalle.valor"};

                                }
                                if($value->{"bono_detalle.tipo"} == "MAXPAGO"){
                                    if($value->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                        $valorbono = $value->{"bono_detalle.valor"};
                                    }

                                }

                            }


                            if($rollower == 0) {

                                $PromocionalLog->estado = 'A';


                            }else{
                                $PromocionalLog->rollowerRequerido = $rollower*$valorbono;
                                $PromocionalLog->valor = $valorbono;
                                $PromocionalLog->valorPromocional = $valorbono;
                                $PromocionalLog->valorBase = $valorbono;
                                $PromocionalLog->estado = 'P';

                            }
                        }else{
                            $PromocionalLog->estado = 'P';

                        }


                        $PromocionalLogMySqlDAO= new PromocionalLogMySqlDAO();
                        $PromocionalLogMySqlDAO->update($PromocionalLog);
                        $PromocionalLogMySqlDAO->getTransaction()->commit();
                        $PromocionalLog->verifyRollower();

                        $existeBono=true;
                    }else{
                        $bonoRedimido=true;
                    }


                }

                if($existeBono){
                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = array(
                        "result"=> true
                    );
                }else{
                    if($bonoRedimido){
                        $response = array();
                        $response["code"] = 200;
                        $response["rid"] = $json->rid;
                        $response["data"] = array(
                            "reason"=> "Bono ya redimido."
                        );
                    }else{
                        $response = array();
                        $response["code"] = 200;
                        $response["rid"] = $json->rid;
                        $response["data"] = array(
                            "reason"=> "Bono no existe."
                        );
                    }

                }

                break;

            case "unsubscribe":

                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["params"] = $json->params;
                $response["data"] = null;

                break;

            case "facebook_login":
                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["params"] = $json->params;
                $response["data"] = null;

                break;

            case "get_countries":

                $Pais = new Pais();

                $SkeepRows = 0;
                $MaxRows = 1000000;

                $json2 = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

                $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, false);

                $paises = json_decode($paises);

                $final = [];
                $arrayf = [];
                $monedas = [];

                $ciudades = [];
                $departamentos = [];

                foreach ($paises->data as $key => $value) {

                    $array = [];

                    $array["Id"] = $value->{"pais.pais_id"};
                    $array["Name"] = $value->{"pais.pais_nom"};

                    $departamento_id = $value->{"departamento.depto_id"};
                    $departamento_texto = $value->{"departamento.depto_nom"};

                    $ciudad_id = $value->{"ciudad.ciudad_id"};
                    $ciudad_texto = $value->{"ciudad.ciudad_nom"};

                    if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

                        $arrayf["currencies"] = array_unique($monedas);
                        $arrayf["departments"] = $departamentos;
                        array_push($final, $arrayf);

                        $arrayf = [];
                        $monedas = [];
                        $departamentos = [];
                        $ciudades = [];

                    }

                    $arrayf["Id"] = $value->{"pais.pais_id"};
                    $arrayf["Name"] = $value->{"pais.pais_nom"};

                    $moneda = [];
                    $moneda["Id"] = $value->{"pais_moneda.paismoneda_id"};
                    $moneda["Name"] = $value->{"pais_moneda.moneda"};

                    array_push($monedas, $moneda);

                    if ($departamento_idf != $departamento_id && $departamento_idf != "") {

                        $departamento = [];
                        $departamento["Id"] = $departamento_idf;
                        $departamento["Name"] = $departamento_textof;
                        $departamento["cities"] = $ciudades;

                        array_push($departamentos, $departamento);

                        $ciudades = [];

                        $ciudad = [];
                        $ciudad["Id"] = $ciudad_id;
                        $ciudad["Name"] = $ciudad_texto;

                        array_push($ciudades, $ciudad);

                    } else {
                        $ciudad = [];
                        $ciudad["Id"] = $ciudad_id;
                        $ciudad["Name"] = $ciudad_texto;

                        array_push($ciudades, $ciudad);
                    }

                    $departamento_idf = $value->{"departamento.depto_id"};
                    $departamento_textof = $value->{"departamento.depto_nom"};

                }

                $departamento = [];
                $departamento["Id"] = $departamento_idf;
                $departamento["Name"] = $departamento_textof;
                $departamento["cities"] = $ciudades;

                array_push($departamentos, $departamento);

                $ciudades = [];

                array_push($monedas, $moneda);
                $arrayf["currencies"] = array_unique($monedas);
                $arrayf["departments"] = $departamentos;

                array_push($final, $arrayf);

                $regiones = $final;


                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array(
                    array(
                        "Id" => "1",
                        "Name" => "Colombia",
                        "departments" =>
                            array(
                                "id" => 5,
                                "name" => "Antioquia",
                                "cities" => array(
                                    array(
                                        "id" => "551",
                                        "name" => "edellin"
                                    )
                                )
                            )
                    )

                );

                $response["data"] = $final;



                break;

            case "get_registration_data":

                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array(
                    "limitsDepositDefault" =>
                        array(
                            "LimitDay" => "1000",
                            "LimitWeek" => "10000",
                            "LimitMonth" => "100000"),

                    "checksumData" =>
                        array(
                            array(
                                "Name" => "Condiciones Generales",
                                "Crypt" => " SHA-1",
                                "Checksum" => " ADSADSADFHJSDAFDSHJF123J21H432J432"

                            ),
                            array(
                                "Name" => "Politica de privacidad",
                                "Crypt" => " SHA-1",
                                "Checksum" => " ADSADSADFHJSDAFDSHJF123J21H432J432"

                            )

                        )


                );


                break;


            case "get_departments":

                $Pais = new Pais();

                $SkeepRows = 0;
                $MaxRows = 1000000;

                $json2 = '{"rules" : [{"field" : "pais.iso", "data": "CO","op":"eq"}] ,"groupOp" : "AND"}';

                $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                $paises = json_decode($paises);

                $final = [];
                $arrayf = [];
                $monedas = [];

                $ciudades = [];
                $departamentos = [];

                foreach ($paises->data as $key => $value) {

                    $array = [];

                    $array["Id"] = $value->{"pais.pais_id"};
                    $array["Name"] = $value->{"pais.pais_nom"};

                    $departamento_id = $value->{"departamento.depto_id"};
                    $departamento_texto = $value->{"departamento.depto_nom"};

                    $ciudad_id = $value->{"ciudad.ciudad_id"};
                    $ciudad_texto = $value->{"ciudad.ciudad_nom"};

                    if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

                        $arrayf["currencies"] = array_unique($monedas);
                        $arrayf["departments"] = $departamentos;
                        array_push($final, $arrayf);

                        $arrayf = [];
                        $monedas = [];
                        $departamentos = [];
                        $ciudades = [];

                    }

                    $arrayf["Id"] = $value->{"pais.pais_id"};
                    $arrayf["Name"] = $value->{"pais.pais_nom"};

                    $moneda = [];
                    $moneda["Id"] = $value->{"pais_moneda.paismoneda_id"};
                    $moneda["Name"] = $value->{"pais_moneda.moneda"};

                    array_push($monedas, $moneda);

                    if ($departamento_idf != $departamento_id && $departamento_idf != "") {

                        $departamento = [];
                        $departamento["Id"] = $departamento_idf;
                        $departamento["Name"] = $departamento_textof;
                        $departamento["cities"] = $ciudades;

                        array_push($departamentos, $departamento);

                        $ciudades = [];

                        $ciudad = [];
                        $ciudad["Id"] = $ciudad_id;
                        $ciudad["Name"] = $ciudad_texto;


                    } else {
                        $ciudad = [];
                        $ciudad["Id"] = $ciudad_id;
                        $ciudad["Name"] = $ciudad_texto;

                        $ciudad["postalCodes"] = array();


                        $postalarray = array();
                        $postalarray["Id"] = "1";
                        $postalarray["Name"] = "Barrio";

                        array_push($ciudad["postalCodes"],$postalarray);

                        /*
                        $codigopostales = file_get_contents('https://www.datos.gov.co/resource/krpp-ufw8.json?$select=codigo_postal,barrios_contenidos_en_el_codigo_postal&$where=nombre_municipio="' . strtoupper( $ciudad_texto).'"');



                        foreach ($codigopostales as $codigopostale) {
                            $postalarray = array();
                            $postalarray["Id"] = $codigopostales.codigo_postal;
                            $postalarray["Name"] = $codigopostales.barrios_contenidos_en_el_codigo_postal;

                            array_push($ciudad["postalCodes"],$postalarray);
                        }
*/

                        array_push($ciudades, $ciudad);
                    }

                    $departamento_idf = $value->{"departamento.depto_id"};
                    $departamento_textof = $value->{"departamento.depto_nom"};

                }

                $departamento = [];
                $departamento["Id"] = $departamento_idf;
                $departamento["Name"] = $departamento_textof;
                $departamento["cities"] = $ciudades;

                array_push($departamentos, $departamento);

                $ciudades = [];

                array_push($monedas, $moneda);
                $arrayf["currencies"] = array_unique($monedas);
                $arrayf["departments"] = $departamentos;

                array_push($final, $arrayf);

                $regiones = $final;


                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array(
                    array(
                        "Id" => "1",
                        "Name" => "Colombia",
                        "departments" =>
                            array(
                                "id" => 5,
                                "name" => "Antioquia",
                                "cities" => array(
                                    array(
                                        "id" => "551",
                                        "name" => "edellin"
                                    )
                                )
                            )
                    )

                );

                $response["data"] = $final;


                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = $departamentos;

                break;

            case "get_bank_info":


                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array(
                    "details" => array("bank_info" => "asasasas")
                );

                break;
            case "video_url":

                $video_id = $json->params->video_id;
                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                //$response["data"] = "https://vcdata-st1.inseincvirtuals.com/inggWebViewer/?cust=ingg&ch=RushFootball2a";
                // $response["data"] = "rtmp://vcdata-st1.inseincvirtuals.com/inggWebViewer/?cust=ingg&ch=RushFootball2a";

                $rules = [];

                array_push($rules, array("field" => "int_evento_detalle.evento_id", "data" => $video_id, "op" => 'eq'));
                array_push($rules, array("field" => "int_evento_detalle.tipo", "data" => "VIDEOURL", "op" => 'eq'));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonfiltro = json_encode($filtro);


                $IntEventoDetalle = new IntEventoDetalle();
                $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);
                $eventos = json_decode($eventos);


                $videourl = $eventos->data[0]->{"int_evento_detalle.valor"};

                $response["data"] = $videourl;


                break;

            case "get":

                switch ($json->params->source) {

                    case "config.currency":

                        $response = array();
                        $response["code"] = 0;
                        $response["rid"] = $json->rid;
                        $response["data"] = array(
                            "data" => array(
                                "currency" => array(
                                    "840" => array(
                                        "id" => 840,
                                        "name" => "USD",
                                        "rounding" => 2,
                                        "rate" => 0,
                                    ),
                                ),
                            ),
                        );

                        break;


                    case "partner.config":

                        $response = array("code" => 0, "rid" => "15062809258173", "data" => array("subid" => "7040" . $json->session->sid . "2", "data" => array("partner" => array("4" => array("partner_id" => 4, "currency" => "USD", "is_cashout_live" => 1, "is_cashout_prematch" => 1, "cashout_percetage" => 10.0, "maximum_odd_for_cashout" => 51.0, "is_counter_offer_available" => 1, "sports_book_profile_ids=>" => [1, 2, 5], "odds_raised_percent" => 5.0, "minimum_offer_amount" => 200.0, "min_bet_stakes" => array("USD" => 0.1, "EUR" => 0.1, "RUB" => 0.1, "UAH" => 0.1, "CNY" => 0.1, "KZT" => 0.1, "PLN" => 0.1, "SEK" => 0.1, "GBP" => 0.1, "MXN" => 0.1, "GEL" => 0.1, "TRY" => 0.1), "user_password_min_length" => 6, "id" => 4)))));

                        break;

                    case "user_notifications":


                        $response = array();
                        $response["code"] = 0;
                        $response["rid"] = $json->rid;
                        $response["data"] = array(
                            "data" => [],
                            "subid" => "7040" . $json->session->sid . "5",
                        );
                        break;

                    case "user":

                        $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                        $saldo = $UsuarioMandante->getSaldo();
                        $moneda = $UsuarioMandante->getMoneda();
                        $paisId = $UsuarioMandante->getPaisId();
                        $usuario_id = $UsuarioMandante->getUsumandanteId();

                        $jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"}] ,"groupOp" : "AND"}';
                        // $usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);
                        // $usuarioMensajes = json_decode($usuarioMensajes);
                        $mensajes_no_leidos = $usuarioMensajes->count[0]->{".count"};


                        $Mandante = new Mandante($UsuarioMandante->getMandante());

                        if ($Mandante->propio === "S") {

                            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                            $Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());
                            $primer_nombre = "$Registro->nombre1";
                            $segundo_nombre = $Registro->nombre2;
                            $primer_apellido = $Registro->apellido1;
                            $segundo_apellido = $Registro->apellido2;

                            $saldo = $Usuario->getBalance();

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
                        $profile_id['name'] = $UsuarioMandante->getNombres() . " " . $UsuarioMandante->getApellidos();
                        $profile_id['first_name'] = $primer_nombre . " " . $segundo_nombre;
                        $profile_id['last_name'] = $primer_apellido . " " . $segundo_apellido;
                        $profile_id['gender'] = "";
                        $profile_id['email'] = "";
                        $profile_id['phone'] = "";
                        $profile_id['reg_info_incomplete'] = false;
                        $profile_id['address'] = "";


                        $profile_id["reg_date"] = "";
                        $profile_id["birth_date"] = "";
                        $profile_id["doc_number"] = "";
                        $profile_id["casino_promo"] = null;
                        $profile_id["currency_name"] = $moneda;

                        $profile_id["currency_id"] = $moneda;
                        $profile_id["balance"] = $saldo;

                        $JOINSERVICES = new JOINSERVICES();

                        $response2 = $JOINSERVICES->getBalance2($UsuarioMandante->getUsumandanteId());

                        $saldoXML = new SimpleXMLElement($response2);

                        if ($saldoXML->RESPONSE->RESULT != "KO") {
                            $saldo = $saldoXML->RESPONSE->BALANCE->__toString();
                            $profile_id["casino_balance"] = $saldo;

                        }


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
                        $profile_id["city"] = "";
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
                        $profile_id["last_login_date"] = 1506281782;
                        $profile_id["swift_code"] = null;
                        $profile_id["bonus_money"] = 0.0;
                        $profile_id["loyalty_last_earned_points"] = 0.0;

                        $UsuarioMandante = new UsuarioMandante($json->session->usuario);


                        $limites = array();

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

                        $limitesArray = $UsuarioConfiguracionMySqlDAO->queryByUsuarioId($UsuarioMandante->getUsuarioMandante());

                        foreach ($limitesArray as $item) {

                            $tipo = "";

                            switch ($item->getTipo()) {
                                case "EXCTIME":
                                    $profile_id["active_time_in_casino"] = intval($item->getValor());

                                    break;


                            }


                        }


                        $profile[$usuario_id] = $profile_id;


                        $data["profile"] = $profile;
                        $DocumentoUsuario = new DocumentoUsuario();
                        $DocumentoUsuario->usuarioId = $UsuarioMandante->getUsuarioMandante();
                        $Documentos = $DocumentoUsuario->getDocumentosNoProcesados();

                        if (oldCount($Documentos) > 0) {
                            $Documentos = json_decode(json_encode($Documentos))[0];
                            $data["document"] = array(
                                "accept" => false,
                                "slug" => $Documentos->{'descarga.ruta'},
                                "id" => $Documentos->{'descarga.descarga_id'},
                                "checksum" => $Documentos->{'descarga.descarga_id'}
                            );
                        } else {
                            $data["document"] = array(
                                "accept" => true
                            );
                        }


                        $response["data"] = array(
                            "data" => $data,
                            "subid" => "7040" . $json->session->sid . "1",
                        );

                        break;

                    case "messages":

                        $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                        $mensajesRecibidos = [];


                        $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"}] ,"groupOp" : "AND"}';

                        //$UsuarioMensaje = new UsuarioMensaje();
                        //$usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $json2, true);

                        $usuarios = json_decode($usuarios);


                        foreach ($usuarios->data as $key => $value) {

                            $array = [];

                            $array["body"] = $value->{"usuario_mensaje.body"};
                            $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                            $array["date"] = 1514649066;
                            $array["id"] = 123213213;
                            $array["subject"] = $value->{"usuario_mensaje.msubject"};
                            $array["thread_id"] = null;

                            array_push($mensajesRecibidos, $array);

                        }


                        $response = array();


                        $response["data"] = array(
                            "subid" => "7040" . $json->session->sid . "3",
                            "data" => array("messages" => $mensajesRecibidos)
                        );

                        $response["code"] = 0;
                        $response["rid"] = $json->rid;

                        break;

                    case "menus":

                        $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                        $saldo = $UsuarioMandante->getSaldo();
                        $moneda = $UsuarioMandante->getMoneda();
                        $paisId = $UsuarioMandante->getPaisId();

                        $Mandante = new Mandante($UsuarioMandante->getMandante());

                        if ($Mandante->propio == "S") {

                            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                            $menus = $Usuario->getMenus();

                        }

                        $menu = "";
                        $menu_contador = 0;

                        $menu_array_final = [];
                        $menu_array = [];
                        $submenus = [];

                        foreach ($menus as $row) {
                            $submenu = [];

                            if ($menu == "") {
                                $menu = $row["d.menu_id"];
                                $menu_provisional = array(
                                    "MENU_ID" => $row["d.menu_id"],
                                    "title" => $row["d.menu"],
                                    "icon" => 'ion-document',
                                    "subMenu" => []
                                );
                                $submenu = array(
                                    "fixedHref" => str_replace('.php', '', $row["b.pagina"]),
                                    "title" => $row["b.submenu"],
                                    "editar" => $row["a.editar"] === 'true' ? true : false,
                                    "eliminar" => $row["a.eliminar"] === 'true' ? true : false,
                                    "adicionar" => $row["a.adicionar"] === 'true' ? true : false,
                                );
                                array_push($submenus, $submenu);
                                $menu_array = $menu_provisional;

                            } else {
                                if ($menu == $row["d.menu_id"]) {
                                    $submenu = array(
                                        "fixedHref" => str_replace('.php', '', $row["b.pagina"]),
                                        "title" => $row["b.submenu"],
                                        "editar" => $row["a.editar"] === 'true' ? true : false,
                                        "eliminar" => $row["a.eliminar"] === 'true' ? true : false,
                                        "adicionar" => $row["a.adicionar"] === 'true' ? true : false,
                                    );
                                    array_push($submenus, $submenu);

                                } else {

                                    $menu_array["subMenu"] = $submenus;
                                    $menu = "";

                                    array_push($menu_array_final, $menu_array);
                                    $menu_array = [];
                                    $submenus = [];

                                    $menu_provisional = array(
                                        "MENU_ID" => $row["d.menu_id"],
                                        "title" => $row["d.menu"],
                                        "icon" => 'ion-document',
                                        "subMenu" => []
                                    );

                                    $submenu = array(
                                        "fixedHref" => str_replace('.php', '', $row["b.pagina"]),
                                        "title" => $row["b.submenu"],
                                        "editar" => $row["a.editar"] === 'true' ? true : false,
                                        "eliminar" => $row["a.eliminar"] === 'true' ? true : false,
                                        "adicionar" => $row["a.adicionar"] === 'true' ? true : false,
                                    );

                                    array_push($submenus, $submenu);
                                    $menu_array = $menu_provisional;
                                }

                            }

                        }
                        $menu_array["subMenu"] = $submenus;
                        array_push($menu_array_final, $menu_array);

                        $perm_punto_venta = !false;
                        $perm_concesionario = !false;
                        $perm_depto = !false;
                        $perm_ciudad = !false;
                        $perm_pais = !false;

                        /*if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "OPERCOM") {
                        $perm_punto_venta = true;
                        }

                        if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "OPERCOM") {
                        $perm_concesionario = true;
                        }

                        if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "OPERCOM") {
                        $perm_depto = true;
                        }

                        if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "OPERCOM") {
                        $perm_ciudad = true;
                        }

                        if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "OPERCOM") {
                        $perm_pais = true;
                        }*/

                        $response = array();

                        $response['code'] = 0;

                        $data = array();
                        $profile = array();
                        $profile_id = array();

                        $data["menus"] = $menu_array_final;

                        $response["data"] = array(
                            "data" => $data,
                            "subid" => "7040" . $json->session->sid . "4",
                        );

                        break;

                    case "betting":
                        /*                        {"code":0,"rid":"15183107607554","data":{"subid":"-8252782767092495715","data":{"sport":{"54":{"id":54,"name":"Carrera Virtual de Caballos","alias":"VirtualHorseRacing","order":176,"game":8},"55":{"id":55,"name":"Carrera de Galgos","alias":"VirtualGreyhoundRacing","order":175,"game":9},"56":{"id":56,"name":"Tenis Virtual","alias":"VirtualTennis","order":174,"game":5},"57":{"id":57,"name":"Fútbol Virtual","alias":"VirtualFootball","order":173,"game":5},"118":{"id":118,"name":"Carrera Virtual de Carros","alias":"VirtualCarRacing","order":177,"game":4},"150":{"id":150,"name":"Virtual Bicycle","alias":"VirtualBicycle","order":178,"game":5},"174":{"id":174,"name":"The Penalty Kicks","alias":"ThePenaltyKicks","order":128,"game":5}}}}}*/


                        $subid = "-";
                        $subidsum = 555555;

                        $objfin = "";
                        $objfirst = "";
                        $objinicio = array();

                        $response = array();
                        $response["code"] = 0;
                        $response["rid"] = $json->rid;
                        $response["data"] = array();

                        $what = $json->params->what;
                        $where = $json->params->where;
                        $result_array_final = array();


                        /*  if (false && is_array($what->event)) {
                              $campos = "";
                              $cont = 0;

                              $rules = [];


                              $filtro = array("rules" => $rules, "groupOp" => "AND");
                              $jsonfiltro = json_encode($filtro);


                              $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
                              $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta.*,int_apuesta_detalle.*,int_evento_apuesta.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
                              $apuestas = json_decode($apuestas);


                              $final = array();
                              $arrayd = array();
                              $apuestaid = "";

                              foreach ($apuestas->data as $apuesta) {

                                  $array = array();

                                  foreach ($what->market as $campo) {


                                      switch ($campo) {

                                          case "team1_name":

                                              if ($evento->{"int_evento_detalle.tipo"} === "TEAM1") {
                                                  $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};

                                              }


                                              break;

                                          case "team2_name":
                                              if ($evento->{"int_evento_detalle.tipo"} == "TEAM2") {
                                                  $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                              }
                                              break;

                                          case "text_info":
                                              if ($evento->{"int_evento_detalle.tipo"} == "TEAM1") {
                                                  // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                              }
                                              break;

                                      }


                                  }
                                  if (oldCount($what->market) == 0) {

                                      switch ($apuesta->{"int_evento_detalle.tipo"}) {

                                          case "TEAM1":

                                              $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};


                                              break;

                                          case "TEAM2":
                                              $arrayd["team2_name"] = $evento->{"int_evento_detalle.valor"};
                                              break;

                                      }
                                  }

                                  if ($apuestaid != intval($evento->{"int_apuesta.apuesta_id"}) && $apuestaid != "") {
                                      $arrayd["id"] = $apuestaid;
                                      $arrayd["market_type"] = $evento->{"int_apuesta.abreviado"};
                                      $arrayd["name"] = $evento->{"int_apuesta.nombre"};
                                      $arrayd["name_template"] = $evento->{"int_apuesta.nombre"};
                                      $arrayd["optimal"] = false;
                                      $arrayd["order"] = 1000;
                                      $arrayd["point_sequence"] = 0;
                                      $arrayd["sequence"] = 0;
                                      $arrayd["cashout"] = 0;
                                      $arrayd["col_count"] = 2;

                                      if (is_array($what->competition)) {

                                          $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                                      } else {
                                          $result_array["market"][$eventoid] = $arrayd;
                                      }
                                      $arrayd = array();
                                  }
                                  $eventoid = intval($evento->{"int_evento.evento_id"});


                                  //array_push($final, $array);

                              }

                              $arrayd["game_number"] = $eventoid;
                              $arrayd["id"] = $eventoid;
                              $arrayd["start_ts"] = $evento->{"int_evento.fecha"};


                              if (is_array($what->competition)) {

                                  $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                              } else {
                                  $result_array["game"][$eventoid] = $arrayd;
                              }

                              $result_array_final = $result_array;

                          }*/

                        if ($what->event != "" && $what->event != undefined) {
                            $result_array = array();

                            $campos = "";
                            $cont = 0;

                            $rules = [];

                            if ($where->event != "" && $where->event != undefined) {

                                foreach ($where->event as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_evento_apuesta_detalle.eventapudetalle_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;

                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }

                                    if (is_numeric($value)) {
                                        $op = "eq";
                                        $data = $value;
                                    }

                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }

                                }
                            }

                            if ($where->game != "" && $where->game != undefined) {

                                foreach ($where->game as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_evento.evento_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;

                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }

                                    if (is_numeric($value)) {
                                        $op = "eq";
                                        $data = $value;
                                    }

                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }

                                }
                            }

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $jsonfiltro = json_encode($filtro);


                            $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
                            $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
                            $apuestas = json_decode($apuestas);


                            $final = array();

                            foreach ($apuestas->data as $apuesta) {

                                $array = array();
                                $arrayd = array();

                                foreach ($what->event as $campo) {
                                    switch ($campo) {
                                        case "id":
                                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});

                                            break;

                                        case "externo_id":
                                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalleproveedor_id"});

                                            break;

                                        case "name":
                                            $arrayd[$campo] = traduccionMercado($apuesta->{"int_apuesta_detalle.opcion"});

                                            break;

                                        case "type":
                                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                                            break;

                                        case "type_1":
                                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                                            break;
                                        case "price":
                                            $arrayd[$campo] = $apuesta->{"int_evento_apuesta_detalle.valor"};

                                            break;

                                    }

                                }

                                if (oldCount($what->event) == 0) {
                                    $arrayd["id"] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                                    $arrayd["name"] = ucwords(strtolower(traduccionMercado($apuesta->{"int_apuesta_detalle.opcion"})));
                                    $arrayd["name_template"] = $apuesta->{"int_apuesta_detalle.opcion"};
                                    $arrayd["price"] = $apuesta->{"int_evento_apuesta_detalle.valor"};
                                    $arrayd["type"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                                    $arrayd["type_1"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                                    $arrayd["type_id"] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                                    if (strpos($arrayd["name"], 'Under') !== false) {
                                        $arrayd["base"] = str_replace("Under ", "", $arrayd["name"]);

                                        $arrayd["name"] = traduccionMercado("Under");
                                        $arrayd["type"] = traduccionMercado("Under ({h})");

                                    }

                                    if (strpos($arrayd["name"], 'Over') !== false) {
                                        $arrayd["base"] = str_replace("Over ", "", $arrayd["name"]);

                                        $arrayd["name"] = traduccionMercado("Over");
                                        $arrayd["type"] = traduccionMercado("Over ({h})");

                                    }
                                    // $arrayd["name"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                                    // $arrayd["name"] = "Francia";
                                    //  $arrayd["type"] = "{t1} ({-h})";
                                    // $arrayd["type_1"] = "Home";
                                    //$arrayd["type_id"] = 0;
                                    //$arrayd["base"] = 1;
                                    // $arrayd["order"] = 0;


                                }
                                array_push($objinicio, intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"}));
                                $subidsum = $subidsum + intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                                $objfirst = "event";

                                if ($apuesta->{"int_evento_apuesta_detalle.estado"} != "A") {
                                    $arrayd["price"] = "1";
                                }

                                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;


                                if (is_array($what->market)) {

                                    $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                                    $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] = $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] + 1;
                                } else {
                                    $result_array["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                                }

                                $objfin = "event";

                            }

                            $result_array_final = $result_array;

                        }


                        if ($what->market != "" && $what->market != undefined) {
                            $result_array = array();

                            $campos = "";
                            $cont = 0;

                            $rules = [];

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $jsonfiltro = json_encode($filtro);


                            $IntEventoApuesta = new IntEventoApuesta();
                            $apuestas = $IntEventoApuesta->getEventoApuestasCustom("int_evento_apuesta.*,int_apuesta.*", "int_evento_apuesta.eventoapuesta_id", "asc", 0, 10000, $jsonfiltro, true);
                            $apuestas = json_decode($apuestas);


                            $final = array();

                            foreach ($apuestas->data as $apuesta) {

                                $array = array();
                                $arrayd = array();

                                foreach ($what->market as $campo) {
                                    switch ($campo) {
                                        case "id":
                                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                                            break;

                                        case "name":
                                            $arrayd[$campo] = $apuesta->{"int_apuesta.nombre"};

                                            break;

                                        case "alias":
                                            $arrayd[$campo] = $apuesta->{"int_apuesta.abreviado"};

                                            break;

                                        case "order":
                                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                                            break;

                                        case "type":
                                            $arrayd[$campo] = ($apuesta->{"int_apuesta.abreviado"});


                                            break;

                                    }

                                }

                                if (oldCount($what->market) == 0) {
                                    $arrayd["id"] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});
                                    $arrayd["market_type"] = $apuesta->{"int_apuesta.abreviado"};
                                    $arrayd["name"] = $apuesta->{"int_apuesta.nombre"};
                                    $arrayd["name_template"] = $apuesta->{"int_apuesta.nombre"};
                                    $arrayd["optimal"] = false;
                                    $arrayd["order"] = 1000;
                                    $arrayd["point_sequence"] = 0;
                                    $arrayd["sequence"] = 0;
                                    $arrayd["cashout"] = 0;
                                }

                                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;
                                $seguir = true;
                                if (is_array($what->event)) {

                                    $arrayd["event"] = $result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"];
                                    $arrayd["col_count"] = $result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["col_count"];
                                    $arrayd["type"] = $apuesta->{"int_apuesta.abreviado"};
                                    if ($result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"] == "") {
                                        $seguir = true;

                                    }
                                }
                                if ($seguir) {
                                    if (oldCount($objinicio) == 0) {
                                        array_push($objinicio, intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"}));

                                        $objfirst = "market";
                                    }
                                    if (is_array($what->game)) {

                                        $result_array["game"][intval($apuesta->{"int_evento_apuesta.evento_id"})]["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})] = $arrayd;
                                    } else {
                                        $result_array["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})] = $arrayd;
                                    }
                                }


                            }


                            $result_array_final = $result_array;
                            $objfin = "market";

                        }

                        if (is_array($what->game)) {


                            if ($objfirst == "") {
                                $objfirst = "game";

                            }
                            $campos = "";
                            $cont = 0;

                            $rules = [];

                            if ($where->competition != "" && $where->competition != undefined) {

                                foreach ($where->competition as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_competencia.competencia_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;


                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }

                                    if (is_numeric($value)) {
                                        $op = "eq";
                                        $data = $value;
                                    }


                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }

                                }
                            }
                            if ($where->sport != "" && $where->sport != undefined) {

                                foreach ($where->sport as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_deporte.deporte_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;


                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }

                                    if (is_numeric($value)) {
                                        $op = "eq";
                                        $data = $value;
                                    }

                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }


                                }
                            }
                            if ($where->game != "" && $where->game != undefined) {

                                foreach ($where->game as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_evento.evento_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;

                                        case "promoted":
                                            $field = "int_evento.promocionado";
                                            $op = "eq";
                                            $data = "S";

                                            break;

                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }

                                    if (is_numeric($value)) {
                                        $op = "eq";
                                        $data = $value;
                                    }

                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }

                                }
                            }
                            if ($where->game != "" && $where->game != undefined) {

                                foreach ($where->game as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_evento.evento_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;

                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }

                                    if (is_numeric($value)) {
                                        $op = "eq";
                                        $data = $value;
                                    }

                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }


                                }
                            }

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $jsonfiltro = json_encode($filtro);


                            $IntEventoDetalle = new IntEventoDetalle();
                            $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);
                            $eventos = json_decode($eventos);


                            $final = array();
                            $arrayd = array();
                            $eventoid = "";
                            $arrayd["info"]["virtual"] = array();

                            foreach ($eventos->data as $evento) {

                                $array = array();
                                //$arrayd["info"]["virtual"] = $evento;

                                if ($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid != "") {
                                    $arrayd["game_number"] = $eventoid;
                                    $arrayd["id"] = $eventoid;
                                    $arrayd["start_ts"] = strtotime($eventoA->{"int_evento.fecha"});
                                    $arrayd["type"] = 0;
                                    $arrayd["tv_type"] = 29;
                                    $arrayd["video_id"] = $eventoid;
                                    $arrayd["type"] = 0;
                                    $arrayd["markets_count"] = 63;

                                    $is_blocked = 0;

                                    if ($eventoA->{"int_evento.estado"} != "A") {
                                        $is_blocked = true;
                                    }

                                    $arrayd["is_blocked"] = $is_blocked;

                                    if (is_array($what->market)) {

                                        $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];

                                    }


                                    if (is_array($what->competition)) {

                                        $result_array["competition"][intval($eventoA->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                                    } else {
                                        $result_array["game"][$eventoid] = $arrayd;


                                    }

                                    if ($objfirst == "game") {
                                        array_push($objinicio, intval($eventoA->{"int_evento.evento_id"}));

                                    }

                                    $arrayd = array();
                                }

                                foreach ($what->game as $campo) {


                                    switch ($campo) {

                                        case "team1_name":

                                            if ($evento->{"int_evento_detalle.tipo"} === "TEAM1") {
                                                $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};

                                            }


                                            break;

                                        case "team2_name":
                                            if ($evento->{"int_evento_detalle.tipo"} == "TEAM2") {
                                                $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                            }
                                            break;

                                        case "text_info":
                                            if ($evento->{"int_evento_detalle.tipo"} == "TEAM1") {
                                                // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                            }
                                            break;

                                        case "externo_id":
                                            $arrayd[$campo] = intval($apuesta->{"int_evento.eventoproveedor_id "});

                                            break;

                                    }


                                }
                                if (oldCount($what->game) == 0) {

                                    switch ($evento->{"int_evento_detalle.tipo"}) {

                                        case "TEAM1":

                                            $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                                            $arrayd["info"]["virtual"][0] = array(
                                                "AnimalName" => "",
                                                "Number" => 1,
                                                "PlayerName" => $evento->{"int_evento_detalle.valor"}
                                            );

                                            foreach ($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"] as $key => $item1) {

                                                foreach ($item1["event"] as $key2 => $item2) {

                                                    if (strtolower($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"]) == "win") {
                                                        $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"] = $evento->{"int_evento_detalle.valor"};
                                                        $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["type"] = $evento->{"int_evento_detalle.valor"};
                                                    }
                                                }
                                            }

                                            break;

                                        case "TEAM2":
                                            $arrayd["team2_name"] = $evento->{"int_evento_detalle.valor"};
                                            $arrayd["info"]["virtual"][1] = array(
                                                "AnimalName" => "",
                                                "Number" => 2,
                                                "PlayerName" => $evento->{"int_evento_detalle.valor"}
                                            );

                                            foreach ($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"] as $key => $item1) {

                                                foreach ($item1["event"] as $key2 => $item2) {

                                                    if (strtolower($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"]) == "lose") {
                                                        $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"] = $evento->{"int_evento_detalle.valor"};
                                                        $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["type"] = $evento->{"int_evento_detalle.valor"};
                                                    }
                                                }
                                            }

                                            break;

                                        case "RACER":
                                            /*
                                             * AnimalName

                                                                                        "Monumentous"
                                                                                        HumanTextureID
                                                                                        :
                                                                                        "0"
                                                                                        Number
                                                                                        :
                                                                                        1
                                                                                        PlayerName
                                                                                        :
                                                                                        "Tom Kunkle"
                                                                                        RacerTextureID
                                                                                        :
                                                                                        "2"

                                             */
                                            $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                                            array_push($arrayd["info"]["virtual"], array(
                                                "AnimalName" => str_replace("Racer", "", $evento->{"int_evento_detalle.valor"}),
                                                "Number" => 1,
                                                "PlayerName" => str_replace("Racer", "", $evento->{"int_evento_detalle.valor"}),
                                                "RacerTextureID" => str_replace("Racer", "", $evento->{"int_evento_detalle.id"}),
                                                "HumanTextureID" => str_replace("Racer", "", $evento->{"int_evento_detalle.id"})
                                            ));
                                            foreach ($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"] as $key => $item1) {

                                                foreach ($item1["event"] as $key2 => $item2) {

                                                    if ($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["type"] == str_replace("Racer", "", $evento->{"int_evento_detalle.id"})) {
                                                        $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"] = $evento->{"int_evento_detalle.valor"};
                                                        $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["type"] = $evento->{"int_evento_detalle.valor"};
                                                    }
                                                }
                                            }

                                            break;

                                        case "externo_id":
                                            $arrayd[$campo] = intval($apuesta->{"int_evento.eventoproveedor_id "});

                                            break;

                                    }
                                }


                                $eventoid = intval($evento->{"int_evento.evento_id"});
                                $eventoA = $evento;

                                //array_push($final, $array);

                            }

                            $arrayd["game_number"] = $eventoid;
                            $arrayd["id"] = $eventoid;
                            $arrayd["start_ts"] = strtotime($eventoA->{"int_evento.fecha"});
                            $arrayd["tv_type"] = 29;
                            $arrayd["video_id"] = $eventoid;
                            $arrayd["type"] = 0;
                            $arrayd["externo_id"] = ($eventoA->{"int_evento.eventoproveedor_id"});

                            $is_blocked = 0;

                            if ($evento->{"int_evento.estado"} != "A") {
                                $is_blocked = true;
                            }

                            $arrayd["is_blocked"] = $is_blocked;


                            if (is_array($what->market)) {

                                $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];

                            }

                            if (is_array($what->competition)) {

                                $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                                if (oldCount($result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"]) == 1) {
                                    //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                                }
                            } else {
                                $result_array["game"][$eventoid] = $arrayd;

                                if (oldCount($result_array["game"]) == 1) {
                                    //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                                }
                            }
                            if ($objfirst == "game") {
                                array_push($objinicio, intval($evento->{"int_evento.evento_id"}));

                            }

                            $objfin = "game";

                            $result_array_final = $result_array;

                        }

                        if ($what->competition != "" && $what->competition != undefined) {
                            $result_array = array();

                            $campos = "";
                            $cont = 0;

                            $rules = [];

                            if ($where->competition != "" && $where->competition != undefined) {

                                foreach ($where->competition as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_competencia.competencia_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;

                                        case "promoted":
                                            $field = "int_competencia.promocionado";
                                            $op = "eq";
                                            $data = "S";

                                            break;

                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }


                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }

                                }

                            }
                            if ($where->sport != "" && $where->sport != undefined) {

                                foreach ($where->sport as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_deporte.deporte_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;

                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }

                                    if (is_numeric($value)) {
                                        $op = "eq";
                                        $data = $value;
                                    }

                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }


                                }
                            }

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $jsonfiltro = json_encode($filtro);


                            $IntCompetencia = new IntCompetencia();
                            $competencias = $IntCompetencia->getCompetenciasCustom("int_competencia.*", "int_competencia.competencia_id", "asc", 0, 10000, $jsonfiltro, true);
                            $competencias = json_decode($competencias);


                            $final = array();

                            foreach ($competencias->data as $competencia) {

                                $array = array();
                                $arrayd = array();

                                foreach ($what->competition as $campo) {
                                    switch ($campo) {
                                        case "id":
                                            $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                                            break;

                                        case "name":
                                            $arrayd[$campo] = $competencia->{"int_competencia.nombre"};

                                            break;

                                        case "alias":
                                            $arrayd[$campo] = $competencia->{"int_competencia.abreviado"};

                                            break;

                                        case "order":
                                            $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                                            break;

                                    }

                                }

                                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;
                                $seguir = true;

                                if (is_array($what->game)) {

                                    $arrayd["game"] = $result_array_final["competition"][intval($competencia->{"int_competencia.competencia_id"})]["game"];
                                    if ($arrayd["game"] == null) {
                                        $seguir = false;
                                    }
                                }
                                if ($seguir) {


                                    if (is_array($what->region)) {

                                        $result_array["region"][intval($competencia->{"int_competencia.region_id"})]["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                                    } else {
                                        $result_array["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                                    }
                                    if (oldCount($objinicio) == 0) {
                                        array_push($objinicio, intval($competencia->{"int_competencia.competencia_id"}));

                                        $objfirst = "competition";
                                    }
                                }

                            }

                            if (oldCount($competencias->data) == 1) {
                                //$subid=$subid."401".$competencia->{"int_competencia.competencia_id"};

                            }

                            $objfin = "competition";

                            $result_array_final = $result_array;

                        }

                        if ($what->region != "" && $what->region != undefined) {
                            $result_array = array();
                            $campos = "";
                            $cont = 0;

                            $rules = [];

                            if ($where->region != "" && $where->region != undefined) {

                                foreach ($where->competition as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_region.region_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;

                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }


                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }

                                }
                            }

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $jsonfiltro = json_encode($filtro);


                            $IntRegion = new IntRegion();
                            $regiones = $IntRegion->getRegionesCustom("int_region.*", "int_region.region_id", "asc", 0, 10000, $jsonfiltro, true);
                            $regiones = json_decode($regiones);


                            $final = array();

                            foreach ($regiones->data as $region) {

                                $array = array();
                                $arrayd = array();

                                foreach ($what->region as $campo) {
                                    switch ($campo) {
                                        case "id":
                                            $arrayd[$campo] = intval($region->{"int_region.region_id"});

                                            break;

                                        case "name":
                                            $arrayd[$campo] = $region->{"int_region.nombre"};

                                            break;

                                        case "alias":
                                            $arrayd[$campo] = $region->{"int_region.abreviado"};

                                            break;

                                        case "order":
                                            $arrayd[$campo] = intval($region->{"int_region.region_id"});

                                            break;

                                    }

                                }
                                $seguir = true;


                                if (is_array($what->competition)) {

                                    $arrayd["competition"] = $result_array_final["region"][intval($region->{"int_region.region_id"})]["competition"];
                                    if ($arrayd["competition"] == null) {
                                        $seguir = false;
                                    }
                                }


                                if ($seguir) {

                                    if (is_array($what->sport)) {

                                        $result_array["sport"][intval($region->{"int_region.deporte_id"})]["region"][intval($region->{"int_region.region_id"})] = $arrayd;
                                    } else {
                                        $result_array["region"][intval($region->{"int_region.region_id"})] = $arrayd;

                                    }
                                    if (oldCount($objinicio) == 0) {
                                        array_push($objinicio, intval($region->{"int_region.region_id"}));

                                        $objfirst = "region";
                                    }
                                }
                            }

                            if (oldCount($regiones->data) == 1) {
                                //$subid=$subid."301".$region->{"int_region.region_id"};

                            }

                            $objfin = "region";

                            $result_array_final = $result_array;


                        }

                        if ($what->sport != "" && $what->sport != undefined) {
                            $campos = "";
                            $cont = 0;

                            $rules = [];

                            if ($where->sport != "" && $where->sport != undefined) {

                                foreach ($where->sport as $key => $value) {

                                    $field = "";
                                    $op = "";
                                    $data = "";

                                    switch ($key) {
                                        case "id":
                                            $field = "int_deporte.deporte_id";
                                            break;

                                        case "name":

                                            break;

                                        case "alias":

                                            break;

                                        case "order":

                                            break;

                                    }
                                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                        $op = "in";
                                        $data_array = $value->{'@in'};
                                        $data = "";

                                        foreach ($data_array as $item) {
                                            $data = $data . $item . ",";
                                        }
                                        $data = trim($data, ",");
                                    }


                                    if ($field != "") {
                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                    }

                                }
                            }

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $jsonfiltro = json_encode($filtro);


                            $IntDeporte = new IntDeporte();
                            $sports = $IntDeporte->getDeportesCustom("int_deporte.*", "int_deporte.deporte_id", "asc", 0, 10000, $jsonfiltro, true);
                            $sports = json_decode($sports);


                            $final = array();

                            foreach ($sports->data as $sport) {

                                $array = array();
                                $arrayd = array();

                                foreach ($what->sport as $campo) {
                                    switch ($campo) {
                                        case "id":
                                            $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                                            break;

                                        case "name":
                                            $arrayd[$campo] = $sport->{"int_deporte.nombre"};

                                            break;

                                        case "alias":
                                            $arrayd[$campo] = $sport->{"int_deporte.abreviado"};

                                            break;

                                        case "order":
                                            $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                                            break;

                                    }

                                }

                                $final[$sport->{"int_deporte.deporte_id"}] = $arrayd;

                                if (is_array($what->region)) {

                                    $arrayd["region"] = $result_array_final["sport"][intval($sport->{"int_deporte.deporte_id"})]["region"];
                                    if ($arrayd["region"] != null) {
                                        $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;

                                    }
                                } else {
                                    $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;

                                }

                                if (oldCount($objinicio) == 0) {
                                    array_push($objinicio, intval($sport->{"int_deporte.deporte_id"}));

                                    $objfirst = "sport";
                                }

                                //array_push($final, $array);

                            }

                            if (oldCount($sports->data) == 1) {
                                //$subid=$subid."201".$sport->{"int_deporte.deporte_id"};

                            }

                            $result_array_final = $result_array;

                            $objfin = "sport";

                        }

                        switch ($objfin) {
                            case "event":
                                $subid = $subid . "324" . $subidsum . "517";
                                break;

                            case "market":
                                $subid = $subid . "435" . $subidsum . "423";
                                break;

                            case "game":
                                $subid = $subid . "614" . $subidsum . "421";
                                break;

                            case "competition":
                                $subid = $subid . "241" . $subidsum . "172";
                                break;

                            case "region":
                                $subid = $subid . "843" . $subidsum . "495";
                                break;

                            case "sport":
                                $subid = $subid . "629" . $subidsum . "151";
                                break;
                        }

                        $response["data"]["subid"] = $subid;
                        $response["data"]["data"] = $result_array_final;
                        $response["data"]["dataSub"] = array(
                            "subid" => $subid,
                            "first" => $objfin,
                            "end" => $objfirst,
                            "id" => $objinicio
                        );


                        /*
                                                $SQLCustom= new \Backend\mysql\IntEventoApuestaDetalleMySqlDAO();
                                                $from="";
                                                $select="";
                                                $rules = [];


                                                if (is_array($what->game)){

                                                    if($from == ""){
                                                        $select = "int_evento_detalle.*,int_evento.*";
                                                        $from = " int_evento_detalle INNER JOIN int_evento ON (int_evento_detalle.evento_id=int_evento.evento_id) ";
                                                    }else{
                                                        $select = $select . ",int_evento_detalle.*,int_evento.*";
                                                        $from =$from. " int_evento_detalle INNER JOIN int_evento ON (int_evento_detalle.evento_id=int_evento.evento_id) ";
                                                    }
                                                }

                                                if (is_array($what->competition)){

                                                    if($from == ""){
                                                        $select = "int_competencia.*";
                                                        $from = " int_competencia ";
                                                    }else{
                                                        $select = $select . ",int_competencia.*";
                                                        $from =$from. " INNER JOIN int_competencia ON (int_competencia.competencia_id=int_evento.competencia_id)";
                                                    }
                                                }

                                                if (is_array($what->region)){

                                                    if($from == ""){
                                                        $select = "int_region.*";
                                                        $from = " int_region ";
                                                    }else{
                                                        $select = $select . ",int_region.*";
                                                        $from =$from. " INNER JOIN int_region ON (int_competencia.region_id=int_region.region_id)";
                                                    }
                                                }


                                                if (is_array($what->sport)){
                                                    if($from == ""){
                                                        $select = "int_deporte.*";
                                                        $from = " int_deporte ";
                                                    }else{
                                                        $select = $select . ",int_region.*";
                                                        $from =$from. " INNER JOIN int_region ON (int_deporte.region_id=int_region.region_id)";
                                                    }
                                                }

                                                if ($where->sport != "" && $where->sport != undefined) {

                                                    foreach ($where->sport as $key => $value) {

                                                        $field = "";
                                                        $op = "";
                                                        $data = "";

                                                        switch ($key) {
                                                            case "id":
                                                                $field = "int_deporte.deporte_id";
                                                                break;

                                                            case "name":

                                                                break;

                                                            case "alias":

                                                                break;

                                                            case "order":

                                                                break;

                                                        }
                                                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                                            $op = "in";
                                                            $data_array = $value->{'@in'};
                                                            $data = "";

                                                            foreach ($data_array as $item) {
                                                                $data = $data . $item . ",";
                                                            }
                                                            $data = trim($data, ",");
                                                        }

                                                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                                    }
                                                }



                                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                                $jsonfiltro = json_encode($filtro);



                                                    $result = $SQLCustom->queryCustom($select,$from, "int_deporte.deporte_id", "asc", 0, 10000, $jsonfiltro, true);
                                                $result = json_decode($result);
                                                $result_array=array();

                                                if (is_array($what->sport)) {
                                                    $sport=array();

                                                    foreach ($result->data as $result) {

                                                        $array = array();
                                                        $arrayd = array();

                                                        foreach ($what->sport as $campo) {
                                                            switch ($campo) {
                                                                case "id":
                                                                    $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                                                                    break;

                                                                case "name":
                                                                    $arrayd[$campo] = $sport->{"int_deporte.nombre"};

                                                                    break;

                                                                case "alias":
                                                                    $arrayd[$campo] = $sport->{"int_deporte.abreviado"};

                                                                    break;

                                                                case "order":
                                                                    $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                                                                    break;

                                                            }

                                                        }

                                                        $sport[$sport->{"int_deporte.deporte_id"}] = $arrayd;

                                                    }

                                                    $result_array["sport"]=$sport;

                                                }

                                                if (is_array($what->game)){

                                                    if($from == ""){
                                                        $select = "int_evento_detalle.*,int_evento.*";
                                                        $from = " int_evento ";
                                                    }else{
                                                        $select = $select . ",int_evento.*";
                                                        $from =$from. " INNER JOIN int_competencia ON (int_competencia.region_id=int_region.region_id)";
                                                    }
                                                }

                                                if (is_array($what->market)) {
                                                    $campos = "";
                                                    $cont = 0;

                                                    $rules = [];

                                                    if ($where->competition != "" && $where->competition != undefined) {

                                                        foreach ($where->competition as $key => $value) {

                                                            $field = "";
                                                            $op = "";
                                                            $data = "";

                                                            switch ($key) {
                                                                case "id":
                                                                    $field = "int_competencia.competencia_id";
                                                                    break;

                                                                case "name":

                                                                    break;

                                                                case "alias":

                                                                    break;

                                                                case "order":

                                                                    break;

                                                            }
                                                            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                                                $op = "in";
                                                                $data_array = $value->{'@in'};
                                                                $data = "";

                                                                foreach ($data_array as $item) {
                                                                    $data = $data . $item . ",";
                                                                }
                                                                $data = trim($data, ",");
                                                            }

                                                            if(is_numeric($value)){
                                                                $op = "eq";
                                                                $data = $value;
                                                            }

                                                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                                        }
                                                    }
                                                    if ($where->sport != "" && $where->sport != undefined) {

                                                        foreach ($where->sport as $key => $value) {

                                                            $field = "";
                                                            $op = "";
                                                            $data = "";

                                                            switch ($key) {
                                                                case "id":
                                                                    $field = "int_deporte.deporte_id";
                                                                    break;

                                                                case "name":

                                                                    break;

                                                                case "alias":

                                                                    break;

                                                                case "order":

                                                                    break;

                                                            }
                                                            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                                                $op = "in";
                                                                $data_array = $value->{'@in'};
                                                                $data = "";

                                                                foreach ($data_array as $item) {
                                                                    $data = $data . $item . ",";
                                                                }
                                                                $data = trim($data, ",");
                                                            }

                                                            if(is_numeric($value)){
                                                                $op = "eq";
                                                                $data = $value;
                                                            }

                                                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                                        }
                                                    }
                                                    if ($where->game != "" && $where->game != undefined) {

                                                        foreach ($where->game as $key => $value) {

                                                            $field = "";
                                                            $op = "";
                                                            $data = "";

                                                            switch ($key) {
                                                                case "id":
                                                                    $field = "int_evento.evento_id";
                                                                    break;

                                                                case "name":

                                                                    break;

                                                                case "alias":

                                                                    break;

                                                                case "order":

                                                                    break;

                                                            }
                                                            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                                                $op = "in";
                                                                $data_array = $value->{'@in'};
                                                                $data = "";

                                                                foreach ($data_array as $item) {
                                                                    $data = $data . $item . ",";
                                                                }
                                                                $data = trim($data, ",");
                                                            }

                                                            if(is_numeric($value)){
                                                                $op = "eq";
                                                                $data = $value;
                                                            }

                                                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                                        }
                                                    }

                                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                                    $jsonfiltro = json_encode($filtro);


                                                    $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
                                                    $eventos = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*,int_apuesta.*,int_evento_apuesta.*,int_evento.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
                                                    $eventos = json_decode($eventos);


                                                    $final = array();
                                                    $arrayd = array();
                                                    $eventoid="";

                                                    foreach ($eventos->data as $evento) {

                                                        $array = array();

                                                        foreach ($what->game as $campo) {



                                                            switch ($campo) {

                                                                case "team1_name":

                                                                    if($evento->{"int_evento_detalle.tipo"}=== "TEAM1"){
                                                                        $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};

                                                                    }


                                                                    break;

                                                                case "team2_name":
                                                                    if($evento->{"int_evento_detalle.tipo"}== "TEAM2"){
                                                                        $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                                                    }
                                                                    break;

                                                                case "text_info":
                                                                    if($evento->{"int_evento_detalle.tipo"}== "TEAM1"){
                                                                        // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                                                    }
                                                                    break;

                                                            }


                                                        }
                                                        if($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid!=""){
                                                            $arrayd["game_number"] = $eventoid;
                                                            $arrayd["id"] = $eventoid;
                                                            $arrayd["start_ts"] = $evento->{"int_evento.fecha"};

                                                            $final[$eventoid] = $arrayd;
                                                            $arrayd = array();
                                                        }
                                                        $eventoid=intval($evento->{"int_evento.evento_id"});


                                                        //array_push($final, $array);

                                                    }

                                                    $arrayd["game_number"] = $eventoid;
                                                    $arrayd["id"] = $eventoid;
                                                    $arrayd["start_ts"] = $evento->{"int_evento.fecha"};

                                                    $final[$eventoid] = $arrayd;

                                                    $Data = array();
                                                    $Data["game"] = $final;


                                                    $response["data"]["data"] = $Data;

                                                }



                                                if ($what->game != "" && $what->game != undefined && is_array($what->game)) {
                                                    $campos = "";
                                                    $cont = 0;

                                                    foreach ($what->game as $campo) {

                                                        switch ($campo) {

                                                            case "game_number":
                                                                $campo = "int_evento.evento_id";

                                                                break;

                                                            case "team1_name":
                                                                $campo = "int_evento_detalle.valor";

                                                                break;

                                                            case "team2_name":
                                                                $campo = "";

                                                                break;

                                                            case "id":
                                                                $campo = "int_evento.evento_id";

                                                                break;

                                                            case "start_ts":
                                                                $campo = "int_evento.fecha";

                                                                break;

                                                            case "text_info":
                                                                $campo = "int_competencia.nombre";

                                                                break;

                                                        }

                                                            if ($cont == 0) {
                                                                $campos = $campo;
                                                                $cont = $cont + 1;
                                                            } else {
                                                                $campos = $campos . "," . $campo;

                                                            }



                                                    }
                                                    $rules = [];

                                                    if ($where->competition != "" && $where->competition != undefined) {

                                                        foreach ($where->competition as $key => $value) {

                                                            $field = "";
                                                            $op = "";
                                                            $data = "";

                                                            switch ($key) {
                                                                case "id":
                                                                    $field = "int_competencia.competencia_id";
                                                                    break;

                                                                case "name":

                                                                    break;

                                                                case "alias":

                                                                    break;

                                                                case "order":

                                                                    break;

                                                            }
                                                            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                                                $op = "in";
                                                                $data_array = $value->{'@in'};
                                                                $data = "";

                                                                foreach ($data_array as $item) {
                                                                    $data = $data . $item . ",";
                                                                }
                                                                $data = trim($data, ",");
                                                            }

                                                            if(is_numeric($value)){
                                                                $op = "eq";
                                                                $data = $value;
                                                            }

                                                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                                        }
                                                    }
                                                    if ($where->sport != "" && $where->sport != undefined) {

                                                        foreach ($where->sport as $key => $value) {

                                                            $field = "";
                                                            $op = "";
                                                            $data = "";

                                                            switch ($key) {
                                                                case "id":
                                                                    $field = "int_deporte.deporte_id";
                                                                    break;

                                                                case "name":

                                                                    break;

                                                                case "alias":

                                                                    break;

                                                                case "order":

                                                                    break;

                                                            }
                                                            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                                                                $op = "in";
                                                                $data_array = $value->{'@in'};
                                                                $data = "";

                                                                foreach ($data_array as $item) {
                                                                    $data = $data . $item . ",";
                                                                }
                                                                $data = trim($data, ",");
                                                            }

                                                            if(is_numeric($value)){
                                                                $op = "eq";
                                                                $data = $value;
                                                            }

                                                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                                                        }
                                                    }

                                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                                    $jsonfiltro = json_encode($filtro);


                                                    $IntEventoDetalle = new IntEventoDetalle();
                                                    $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.eventodetalle_id", "asc", 0, 10000, $jsonfiltro, true);
                                                    $eventos = json_decode($eventos);


                                                    $final = array();
                                                    $arrayd = array();
                                                    $eventoid="";

                                                    foreach ($eventos->data as $evento) {

                                                        $array = array();

                                                        foreach ($what->game as $campo) {



                                                            switch ($campo) {

                                                                case "team1_name":

                                                                    if($evento->{"int_evento_detalle.tipo"}=== "TEAM1"){
                                                                        $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};

                                                                    }


                                                                    break;

                                                                case "team2_name":
                                                                    if($evento->{"int_evento_detalle.tipo"}== "TEAM2"){
                                                                        $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                                                    }
                                                                    break;

                                                                case "text_info":
                                                                    if($evento->{"int_evento_detalle.tipo"}== "TEAM1"){
                                                                       // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                                                    }
                                                                    break;

                                                            }


                                                        }
                                                        if($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid!=""){
                                                            $arrayd["game_number"] = $eventoid;
                                                            $arrayd["id"] = $eventoid;
                                                            $arrayd["start_ts"] = $evento->{"int_evento.fecha"};

                                                            $final[$eventoid] = $arrayd;
                                                            $arrayd = array();
                                                        }
                                                        $eventoid=intval($evento->{"int_evento.evento_id"});


                                                        //array_push($final, $array);

                                                    }

                                                    $arrayd["game_number"] = $eventoid;
                                                    $arrayd["id"] = $eventoid;
                                                    $arrayd["start_ts"] = $evento->{"int_evento.fecha"};

                                                    $final[$eventoid] = $arrayd;

                                                    $Data = array();
                                                    $Data["game"] = $final;


                                                    $response["data"]["data"] = $Data;

                                                }*/

                        break;


                    case "betting2":
                        /*                        {"code":0,"rid":"15183107607554","data":{"subid":"-8252782767092495715","data":{"sport":{"54":{"id":54,"name":"Carrera Virtual de Caballos","alias":"VirtualHorseRacing","order":176,"game":8},"55":{"id":55,"name":"Carrera de Galgos","alias":"VirtualGreyhoundRacing","order":175,"game":9},"56":{"id":56,"name":"Tenis Virtual","alias":"VirtualTennis","order":174,"game":5},"57":{"id":57,"name":"Fútbol Virtual","alias":"VirtualFootball","order":173,"game":5},"118":{"id":118,"name":"Carrera Virtual de Carros","alias":"VirtualCarRacing","order":177,"game":4},"150":{"id":150,"name":"Virtual Bicycle","alias":"VirtualBicycle","order":178,"game":5},"174":{"id":174,"name":"The Penalty Kicks","alias":"ThePenaltyKicks","order":128,"game":5}}}}}*/

                        $what = $json->params->what;


                        if ($what->sport[0] == "id") {

                            if ($what->sport[1] == "name") {
                                $response = array();
                                $response["code"] = 0;
                                $response["rid"] = $json->rid;
                                $response["data"] = array(
                                    "subid" => "-8252782767092495715",

                                    "data" => array(
                                        "sport" => array(
                                            "54" => array(
                                                "game" => 8,
                                                "alias" => "VirtualHorseRacing",
                                                "id" => 54,
                                                "name" => "Carrera Virtual de Caballos",
                                                "order" => 176,
                                            ),
                                            "174" => array(
                                                "game" => 5,
                                                "alias" => "VirtualFootball",
                                                "id" => 174,
                                                "name" => "The Penalty Kicks",
                                                "order" => 128,
                                            ),
                                        ),
                                    ),
                                );
                            }

                            if ($what->sport[1] == "alias") {
                                $response = array();
                                $response["code"] = 0;
                                $response["rid"] = $json->rid;
                                $response["data"] = array(
                                    "subid" => "-5754574707528464820",

                                    "data" => array(
                                        "sport" => array(
                                            "174" => array(
                                                "id" => 174,
                                                "name" => "ThePenaltyKicks",
                                                "region" => array(
                                                    10174 => array(
                                                        "competition" => array(
                                                            "27510" => array(
                                                                "game" => array(
                                                                    "9742278" => array(
                                                                        "game_number" => 12263,
                                                                        "id" => 9742278,
                                                                        "info" => array(
                                                                            "field" => 0,
                                                                            "virtual" => array(
                                                                                array(
                                                                                    "AnimalName" => "",
                                                                                    "Number" => 1,
                                                                                    "PlayerName" => "Spain"
                                                                                ),
                                                                                array(
                                                                                    "AnimalName" => "",
                                                                                    "Number" => 2,
                                                                                    "PlayerName" => "Armenia"
                                                                                )
                                                                            )
                                                                        ),
                                                                        "is_blocked" => 0,
                                                                        "is_live" => 1,
                                                                        "is_neutral_venue" => false,
                                                                        "is_reversed" => false,
                                                                        "is_started" => 0,
                                                                        "is_stat_available" => false,
                                                                        "live_available" => 0,
                                                                        "market" =>
                                                                            array(
                                                                                "142338819" => array(
                                                                                    "cashout" => 0,
                                                                                    "col_count" => 2,
                                                                                    "event" => array(
                                                                                        "472054085" => array(
                                                                                            "id" => 472054085,
                                                                                            "name" => "Belgium",
                                                                                            "order" => 0,
                                                                                            "price" => 1.77,
                                                                                            "type" => "{t1}",
                                                                                            "type_1" => "Home",
                                                                                            "type_id" => 15229
                                                                                        ),
                                                                                        "472054086" => array(
                                                                                            "id" => 472054086,
                                                                                            "name" => "Argentina",
                                                                                            "order" => 1,
                                                                                            "price" => 1.93,
                                                                                            "type" => "{t2}",
                                                                                            "type_1" => "Away",
                                                                                            "type_id" => 15230
                                                                                        )
                                                                                    ),
                                                                                    "id" => 142338819,
                                                                                    "market_type" => "MatchResult",
                                                                                    "name" => "Match Result",
                                                                                    "name_template" => "Match Result",
                                                                                    "name_template" => "Match Result",
                                                                                    "optimal" => false,
                                                                                    "order" => 100000,
                                                                                    "point_sequence" => 0,
                                                                                    "sequence" => 0
                                                                                )

                                                                            ),
                                                                        "markets_count" => 1,
                                                                        "not_in_sportsbook" => 0,
                                                                        "start_ts" => 1518311820,
                                                                        "team1_id" => 395250,
                                                                        "team1_name" => "España",
                                                                        "team2_id" => 395228,
                                                                        "team2_name" => "Armenia",
                                                                        "tv_type" => 15,
                                                                        "type" => 0,
                                                                        "video_id" => 10,
                                                                        "visible_in_prematch" => 1,
                                                                    )
                                                                ),
                                                                "id" => 27510,
                                                                "name" => "The Penalty Kicks"
                                                            ),
                                                            "id" => 10174
                                                        )
                                                    )
                                                ),
                                            ),
                                        ),
                                    ),
                                );

                                break;
                            }

                        }
                        if ($what->competition[0] == "id") {
                            $response = array();
                            $response["code"] = 0;
                            $response["rid"] = $json->rid;
                            $response["data"] = array(
                                "subid" => "-6444586264083712884",

                                "data" => array(
                                    "competition" => array(
                                        "27510" => array(
                                            "id" => 27510,
                                            "name" => "The Penalty Kicks",
                                            "order" => 1001
                                        ),
                                    ),
                                ),
                            );
                        }

                        if ($what->game[0] == "game_number") {
                            $response = array();
                            $response["code"] = 0;
                            $response["rid"] = $json->rid;
                            $response["data"] = array(
                                "subid" => "-713534352033310597",

                                "data" => array(
                                    "game" => array(
                                        "9742278" => array(
                                            "game_number" => 12263,
                                            "id" => 9742278,
                                            "start_ts" => 1518311820,
                                            "team1_name" => "España",
                                            "team2_name" => "Armenia"
                                        ),
                                        "9742304" => array(
                                            "game_number" => 9275,
                                            "id" => 9742304,
                                            "start_ts" => 1518312120,
                                            "team1_name" => "Inglaterra",
                                            "team2_name" => "Armenia"
                                        ),
                                        "9742328" => array(
                                            "game_number" => 2094,
                                            "id" => 9742328,
                                            "start_ts" => 1518311820,
                                            "team1_name" => "España",
                                            "team2_name" => "Armenia"
                                        ),
                                        "9742350" => array(
                                            "game_number" => 12199,
                                            "id" => 9742350,
                                            "start_ts" => 9742350,
                                            "team1_name" => "Bélgica",
                                            "team2_name" => "Argentina"
                                        ),
                                        "9742377" => array(
                                            "game_number" => 8714,
                                            "id" => 9742377,
                                            "start_ts" => 1518313020,
                                            "team1_name" => "Italy",
                                            "team2_name" => "Netherlands"
                                        ),
                                    ),
                                ),
                            );
                        }


                        break;

                }

                break;

            case "get_virtual_results_last_hour":


                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array();

                break;

            case "restore_login_site":

                $params = $json->params;

                $auth_token = $params->auth_token;

                $auth_token = validarCampoSecurity($auth_token, true);


                if ($auth_token == "") {

                    throw new Exception("Token vacio", "01");

                }

                $cumple = true;

                $UsuarioToken = new UsuarioToken($auth_token, '1');


                if ($cumple) {

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId(), "");

                    $saldo = $UsuarioMandante->getSaldo();
                    $moneda = $UsuarioMandante->getMoneda();
                    $paisId = $UsuarioMandante->getPaisId();

                    $UsuarioToken->setRequestId($json->session->sid);

                    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();

                    $response = array();

                    $response['code'] = 0;

                    $data = array();
                    $partner = array();
                    $partner_id = array();

                    $min_bet_stakes = array();

                    $partner_id['partner_id'] = $json->session->mandante;
                    $partner_id['currency'] = $moneda;
                    $partner_id['is_cashout_live'] = 0;
                    $partner_id['is_cashout_prematch'] = 0;
                    $partner_id['cashout_percetage'] = 0;
                    $partner_id['maximum_odd_for_cashout'] = 0;
                    $partner_id['is_counter_offer_available'] = 0;
                    $partner_id['sports_book_profile_ids'] = [1, 2, 5];
                    $partner_id['odds_raised_percent'] = 0;
                    $partner_id['minimum_offer_amount'] = 0;
                    $partner_id['minimum_offer_amount'] = 0;

                    $min_bet_stakes[$moneda] = 0.1;

                    $partner_id['user_password_min_length'] = 6;
                    $partner_id['id'] = $json->session->mandante;

                    $partner_id['min_bet_stakes'] = $min_bet_stakes;

                    $partner[$json->session->mandante] = $partner_id;

                    $data["partner"] = $partner;

                    $data["usuario"] = $UsuarioToken->getUsuarioId();

                    $response["data"] = $data;

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;

                    $response["data"] = array(
                        "auth_token" => $UsuarioToken->getToken(),
                        "user_id" => $UsuarioToken->getUsuarioId(),
                    );

                } else {

                    throw new Exception("Restringido", "01");

                }

                break;

            case "restore_login":

                $params = $json->params;

                $auth_token = $params->auth_token;

                $auth_token = validarCampoSecurity($auth_token, true);


                if ($auth_token == "") {

                    throw new Exception("Token vacio", "01");

                }

                $cumple = true;

                $ProdMandanteTipo = new ProdMandanteTipo('CASINO', '0');
                if ($ProdMandanteTipo->estado == "I") {
                    $cumple = false;

                } elseif ($ProdMandanteTipo->estado == "A") {

                    $UsuarioToken = new UsuarioToken($auth_token, '0');

                } else {

                    $UsuarioToken = new UsuarioToken($auth_token, '0');

                    if ($UsuarioToken->estado != "NR") {
                        $cumple = false;
                    }

                }


                if ($cumple) {

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId(), "");

                    $saldo = $UsuarioMandante->getSaldo();
                    $moneda = $UsuarioMandante->getMoneda();
                    $paisId = $UsuarioMandante->getPaisId();

                    $UsuarioToken->setRequestId($json->session->sid);

                    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();

                    $response = array();

                    $response['code'] = 0;

                    $data = array();
                    $partner = array();
                    $partner_id = array();

                    $min_bet_stakes = array();

                    $partner_id['partner_id'] = $json->session->mandante;
                    $partner_id['currency'] = $moneda;
                    $partner_id['is_cashout_live'] = 0;
                    $partner_id['is_cashout_prematch'] = 0;
                    $partner_id['cashout_percetage'] = 0;
                    $partner_id['maximum_odd_for_cashout'] = 0;
                    $partner_id['is_counter_offer_available'] = 0;
                    $partner_id['sports_book_profile_ids'] = [1, 2, 5];
                    $partner_id['odds_raised_percent'] = 0;
                    $partner_id['minimum_offer_amount'] = 0;
                    $partner_id['minimum_offer_amount'] = 0;

                    $min_bet_stakes[$moneda] = 0.1;

                    $partner_id['user_password_min_length'] = 6;
                    $partner_id['id'] = $json->session->mandante;

                    $partner_id['min_bet_stakes'] = $min_bet_stakes;

                    $partner[$json->session->mandante] = $partner_id;

                    $data["partner"] = $partner;

                    $data["usuario"] = $UsuarioToken->getUsuarioId();

                    $response["data"] = $data;

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;

                    $response["data"] = array(
                        "auth_token" => $UsuarioToken->getToken(),
                        "user_id" => $UsuarioToken->getUsuarioId(),
                    );

                } else {

                    throw new Exception("Restringido", "01");

                }

                break;

            case "payment_services":

                // $response = array("code" => 0, "rid" => "150630776768211", "data" => array("withdraw" => ["moneta", "netellernew", "gateway", "ecocard", "astropay", "astropaydirect", "cubits", "wirecardnew", "skrill", "moneybookers"], "deposit" => ["moneta", "netellernew", "gateway", "ecocard", "astropay", "astropaystreamline1", "astropaystreamline2", "astropaystreamline3", "astropaystreamline4", "astropaystreamline5", "astropaystreamline6", "astropaystreamline7", "astropaystreamline8", "astropaystreamline9", "astropaystreamline10", "astropaystreamline11", "astropaystreamline12", "astropaystreamline13", "astropaystreamline14", "cubits", "paysafecard", "wirecard", "skrill", "moneybookers", "yandex", "yandexbank", "yandexcash", "yandexinvois", "yandexprbank", "yandexsberbank", "pugglepay"]));
                $response = array("code" => 0, "data" => array("withdraw" => ["local"], "deposit" => ["safetypay"]));

                break;

            case "balance_history":

                // $response = array("code" => 0, "rid" => "150630776768211", "data" => array("withdraw" => ["moneta", "netellernew", "gateway", "ecocard", "astropay", "astropaydirect", "cubits", "wirecardnew", "skrill", "moneybookers"], "deposit" => ["moneta", "netellernew", "gateway", "ecocard", "astropay", "astropaystreamline1", "astropaystreamline2", "astropaystreamline3", "astropaystreamline4", "astropaystreamline5", "astropaystreamline6", "astropaystreamline7", "astropaystreamline8", "astropaystreamline9", "astropaystreamline10", "astropaystreamline11", "astropaystreamline12", "astropaystreamline13", "astropaystreamline14", "cubits", "paysafecard", "wirecard", "skrill", "moneybookers", "yandex", "yandexbank", "yandexcash", "yandexinvois", "yandexprbank", "yandexsberbank", "pugglepay"]));

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $ClientId = $UsuarioMandante->getUsuarioMandante();

                $Usuario = new Usuario($ClientId);
                $movimientos = $Usuario->getMovimientosResume("", "");

                $movimientos = json_decode($movimientos);

                $movimientosData = array();

                foreach ($movimientos->data as $key => $value) {

                    /*
                           '0': 'New Bets',
                          '1': 'Winning Bets',
                          '2': 'Returned Bet',
                          '3': 'Deposit',
                          '4': 'Card Deposit',
                          '5': 'Bonus',
                          '6': 'Bonus Bet',
                          '7': 'Commission',
                          '8': 'Withdrawal',
                          '9': 'Correction Up',
                          '302': 'Correction Down',
                          '10': 'Deposit by payment system',
                          '12': 'Withdrawal request',
                          '13': 'Authorized Withdrawal',
                          '14': 'Withdrawal denied',
                          '15': 'Withdrawal paid',
                          '16': 'Pool Bet',
                          '17': 'Pool Bet Win',
                          '18': 'Pool Bet Return',
                          '23': 'In the process of revision',
                          '24': 'Removed for recalculation',
                          '29': 'Free Bet Bonus received',
                          '30': 'Wagering Bonus received',
                          '31': 'Transfer from Gaming Wallet',
                          '32': 'Transfer to Gaming Wallet',
                          '37': 'Declined Superbet',
                          '39': 'Bet on hold',
                          '40': 'Bet cashout',
                          '19': 'Fair',
                          '20': 'Fair Win',
                          '21': 'Fair Commission'


                     */

                    $array = array();

                    switch ($value->{"movimientos.tipo"}) {
                        case "Apuestas":
                            $array["operation"] = 0;

                            break;

                        case "Ganadoras":
                            $array["operation"] = 1;

                            break;

                        case "Depositos":
                            $array["operation"] = 3;

                            break;
                        case "Retiros":
                            $array["operation"] = 15;

                            break;

                        case "RetirosPendientes":
                            $array["operation"] = 12;

                            break;
                    }

                    $array["amount"] = ($value->{"movimientos.valor"});
                    $array["balance"] = ($value->{"movimientos.valor"});
                    $array["date_time"] = ($value->{"movimientos.fecha"});
                    $array["operation_name"] = ($value->{"movimientos.tipo"});
                    $array["product_category"] = 0;
                    $array["transaction_id"] = 0;

                    array_push($movimientosData, $array);


                }


                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array(
                    "history" => $movimientosData


                );

                break;

            case "balance_resume":

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $ClientId = $UsuarioMandante->getUsuarioMandante();

                $Usuario = new Usuario($ClientId);
                $movimientos = $Usuario->getMovimientosTotalResume();

                $movimientos = json_decode($movimientos);

                $movimientosData = array();
                $saldo = 0;

                foreach ($movimientos->data as $key => $value) {

                    /*
                           '0': 'New Bets',
                          '1': 'Winning Bets',
                          '2': 'Returned Bet',
                          '3': 'Deposit',
                          '4': 'Card Deposit',
                          '5': 'Bonus',
                          '6': 'Bonus Bet',
                          '7': 'Commission',
                          '8': 'Withdrawal',
                          '9': 'Correction Up',
                          '302': 'Correction Down',
                          '10': 'Deposit by payment system',
                          '12': 'Withdrawal request',
                          '13': 'Authorized Withdrawal',
                          '14': 'Withdrawal denied',
                          '15': 'Withdrawal paid',
                          '16': 'Pool Bet',
                          '17': 'Pool Bet Win',
                          '18': 'Pool Bet Return',
                          '23': 'In the process of revision',
                          '24': 'Removed for recalculation',
                          '29': 'Free Bet Bonus received',
                          '30': 'Wagering Bonus received',
                          '31': 'Transfer from Gaming Wallet',
                          '32': 'Transfer to Gaming Wallet',
                          '37': 'Declined Superbet',
                          '39': 'Bet on hold',
                          '40': 'Bet cashout',
                          '19': 'Fair',
                          '20': 'Fair Win',
                          '21': 'Fair Commission'


                     */

                    $array = array();

                    switch ($value->{"movimientos.tipo"}) {
                        case "Apuestas":
                            $array["operation"] = 0;
                            $saldo = $saldo - ($value->{"movimientos.valor"});

                            break;

                        case "Ganadoras":
                            $array["operation"] = 1;
                            $saldo = $saldo + ($value->{"movimientos.valor"});

                            break;

                        case "Depositos":
                            $array["operation"] = 3;
                            $saldo = $saldo + ($value->{"movimientos.valor"});

                            break;
                        case "Retiros":
                            $array["operation"] = 15;
                            $saldo = $saldo - ($value->{"movimientos.valor"});

                            break;

                        case "RetirosPendientes":
                            $array["operation"] = 12;
                            $saldo = $saldo - ($value->{"movimientos.valor"});

                            break;
                    }

                    $array["amount"] = ($value->{"movimientos.valor"});
                    $array["balance"] = ($value->{"movimientos.valor"});
                    $array["operation_name"] = ($value->{"movimientos.tipo"});
                    $array["product_category"] = 0;
                    $array["transaction_id"] = 0;


                    array_push($movimientosData, $array);


                }

                $array = array();

                $array["amount"] = $saldo;
                $array["balance"] = $saldo;
                $array["operation_name"] = "Balance";
                $array["product_category"] = 0;
                $array["transaction_id"] = 0;


                array_push($movimientosData, $array);


                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array(
                    "resume" => $movimientosData


                );

                break;

                break;
            case "deposit":

                $amount = $json->params->amount;
                $service = $json->params->service;
                $player = $json->params->player;
                $status_url = $player->status_url;
                $cancel_url = $status_url->status;
                $fail_url = $status_url->fail;
                $success_url = $status_url->success;

                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array(
                    "result" => 0,
                    "details" => array(
                        "action" => "https://customer.cc.at.paysafecard.com/psccustomer/GetCustomerPanelServlet?mid",
                        "method" => "get",
                        "fields" => array(
                            array("name" => "mid", "value" => "012020321"),
                            array("name" => "mtid", "value" => "012020321"),
                            array("name" => "amount", "value" => "10"),
                            array("name" => "currenct", "value" => "USD")
                        )
                    )


                );

                break;

            case "withdraw":

                $amount = $json->params->amount;
                $service = $json->params->service;
                //$player = $json->params->player;
                //$status_url = $player->status_url;
                //$cancel_url = $status_url->status;
                //$fail_url = $status_url->fail;
                //$success_url = $status_url->success;

                if ($service == "local") {

                    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                    $ClientId = $UsuarioMandante->getUsuarioMandante();
                    $Usuario = new Usuario($ClientId);

                    $Consecutivo = new Consecutivo("", "RET", "");

                    $consecutivo_recarga = $Consecutivo->numero;

                    $consecutivo_recarga++;

                    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                    $Consecutivo->setNumero($consecutivo_recarga);


                    $ConsecutivoMySqlDAO->update($Consecutivo);

                    $ConsecutivoMySqlDAO->getTransaction()->commit();


                    $CuentaCobro = new CuentaCobro();


                    $CuentaCobro->cuentaId = $consecutivo_recarga;

                    $CuentaCobro->usuarioId = $ClientId;

                    $CuentaCobro->valor = $amount;

                    $CuentaCobro->fechaPago = '';

                    $CuentaCobro->fechaCrea = date('Y-m-d H:i:s');

                    $CuentaCobro->puntoventaId = '';

                    $CuentaCobro->estado = 'A';
                    $clave = GenerarClaveTicket(5);

                    $CuentaCobro->clave = "aes_encrypt('" . $clave . "','" . $claveEncrypt_Retiro . "')";

                    $CuentaCobro->mandante = '0';

                    $CuentaCobro->dirIp = $json->session->usuarioip;

                    $CuentaCobro->impresa = 'S';

                    $CuentaCobro->mediopagoId = '0';

                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

                    $CuentaCobroMySqlDAO->insert($CuentaCobro);

                    $Usuario->debit($amount, $CuentaCobroMySqlDAO->getTransaction());

                    $CuentaCobroMySqlDAO->getTransaction()->commit();

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = array(
                        "details" => array(
                            "method" => "pdf",
                            "status_message" => '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody><tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr><tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . $amount . '</font></td></tr></tbody></table>',
                            "data" => array(
                                "WithdrawId" => $consecutivo_recarga,
                                "UserId" => $ClientId,
                                "Name" => $Usuario->nombre,
                                "date_time" => $CuentaCobro->fechaCrea,
                                "Key" => $clave,
                                "Amount" => $amount
                            )

                        )

                    );
                }


                break;

            case "withdraw_cancel":

                $cuenta_id = $json->params->id;
                //$observacion = $json->params->observacion;

                if ($cuenta_id != "" && $cuenta_id != undefined && $cuenta_id != "undefined") {
                    $CuentaCobro = new CuentaCobro($cuenta_id);
                }

                if ($CuentaCobro != null) {

                    if ($CuentaCobro->getEstado() == "I") {
                        throw new Exception("No se encontro la cuenta de cobro", "12");
                    }

                    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                    $CuentaCobroEliminar = new \Backend\dto\CuentaCobroEliminada();
                    $CuentaCobroEliminar->setCuentaId($CuentaCobro->getCuentaId());
                    $CuentaCobroEliminar->setValor($CuentaCobro->getValor());
                    $CuentaCobroEliminar->setMandante($CuentaCobro->getMandante());
                    $CuentaCobroEliminar->setObserv($observacion);
                    $CuentaCobroEliminar->setUsuarioId($CuentaCobro->getUsuarioId());
                    $CuentaCobroEliminar->setUsucreaId($UsuarioMandante->getUsuarioMandante());
                    $CuentaCobroEliminar->setFechaCuenta($CuentaCobro->getFechaCrea());
                    $CuentaCobroEliminar->setFechaCrea(date('Y-m-d H:i:s'));

                    $CuentaCobroEliminadaMySqlDAO = new \Backend\mysql\CuentaCobroEliminadaMySqlDAO();

                    $CuentaCobroEliminadaMySqlDAO->insert($CuentaCobroEliminar);

                    $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroEliminadaMySqlDAO->getTransaction());

                    $CuentaCobroMySqlDAO = new \Backend\mysql\CuentaCobroMySqlDAO($CuentaCobroEliminadaMySqlDAO->getTransaction());

                    $CuentaCobroMySqlDAO->delete($CuentaCobro->getCuentaId());

                    $CuentaCobroEliminadaMySqlDAO->getTransaction()->commit();

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = array();

                } else {
                    throw new Exception("No se encontro la cuenta de cobro", "12");
                }

                break;

            case "get_withdrawals":

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $ClientId = $UsuarioMandante->getUsuarioMandante();


                $MaxRows = $params->MaxRows;
                $OrderedItem = $params->OrderedItem;
                $SkeepRows = $params->SkeepRows;

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000;
                }

                $rules = [];
                array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => "$ClientId", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $CuentaCobro = new CuentaCobro();

                $cuentas = $CuentaCobro->getCuentasCobroCustom(" cuenta_cobro.cuenta_id,cuenta_cobro.valor ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                $cuentas = json_decode($cuentas);

                $cuentasData = array();

                foreach ($cuentas->data as $key => $value) {


                    $arraybet = array();
                    $arraybet["id"] = ($value->{"cuenta_cobro.cuenta_id"});
                    $arraybet["amount"] = ($value->{"cuenta_cobro.valor"});
                    $arraybet["date"] = ($value->{"cuenta_cobro.fecha_crea"});
                    $arraybet["status"] = 0;

                    array_push($cuentasData, $arraybet);


                }


                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array(
                    "result_status" => "OK",
                    "withdrawal_requests" => array("request" => $cuentasData
                    )


                );


                break;

            case "get_bonus_details":

                // $response = array("code" => 0, "rid" => "15063077674259", "data" => array("bonuses" => [array("id" => 0, "partner_bonus_id" => 2674, "source" => 1, "name" => "25% Reload Bonus for Sport", "description" => "You can bet all sports except tennis; The minimum odds for ordinar should be 1.7 or higher; Express must contain 2 or more events, the ratio of each event must be 1.7 or higher; To wager the bonus you have 15 days; wager = 18.", "start_date" => 1499198400, "end_date" => 1514664000, "client_bonus_expiration_date" => null, "expiration_date" => null, "expiration_days" => 15, "wagering_factor" => 18, "money_requirenments" => array("cny" => array("currency_id" => "CNY", "min_amount" => 150.0, "max_amount" => 1500.0), "eur" => array("currency_id" => "EUR", "min_amount" => 20.0, "max_amount" => 200.0), "gel" => array("currency_id" => "GEL", "min_amount" => 55.0, "max_amount" => 550.0), "kzt" => array("currency_id" => "KZT", "min_amount" => 6800.0, "max_amount" => 68000.0), "mxn" => array("currency_id" => "MXN", "min_amount" => 410.0, "max_amount" => 4100.0), "pln" => array("currency_id" => "PLN", "min_amount" => 84.0, "max_amount" => 840.0), "rub" => array("currency_id" => "RUB", "min_amount" => 1250.0, "max_amount" => 12500.0), "sek" => array("currency_id" => "SEK", "min_amount" => 190.0, "max_amount" => 1900.0), "try" => array("currency_id" => "TRY", "min_amount" => 80.0, "max_amount" => 800.0), "uah" => array("currency_id" => "UAH", "min_amount" => 570.0, "max_amount" => 5700.0), "usd" => array("currency_id" => "USD", "min_amount" => 22.0, "max_amount" => 220.0)), "can_accept" => true, "bonus_type" => 2, "amount" => 0.0, "acceptance_type" => 0, "result_type" => 0, "external_id" => null)]));
                $response = array("code" => 0, "data" => array("bonuses" => []));

                break;

            case "transfer":
                $fromproduct = $json->params->from_product;
                $to_product = $json->params->to_product;
                $amount = $json->params->amount;

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                if ($fromproduct == "Sport" && $to_product == "Poker") {
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $Usuario->debit($amount, $Transaction);

                    $Transaction->commit();


                    $JOINSERVICES = new JOINSERVICES();


                    $response = $JOINSERVICES->getBalance2($UsuarioMandante->getUsumandanteId());

                    $saldoXML = new SimpleXMLElement($response);

                    if ($saldoXML->RESPONSE->RESULT != "KO") {
                        $saldo = $saldoXML->RESPONSE->BALANCE->__toString();

                    }

                    $response = $JOINSERVICES->depositUser($UsuarioMandante->getUsumandanteId(), $amount);

                    $insertXML = new SimpleXMLElement($response);

                    if ($insertXML->RESPONSE->RESULT != "KO") {


                        $Proveedor = new Proveedor("", "JOINPOKER");

                        try {
                            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
                            $UsuarioToken->saldo = $saldo + $amount;
                            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                            $Transaction = $UsuarioTokenMySqlDAO->getTransaction();
                            $UsuarioTokenMySqlDAO->update($UsuarioToken);
                            $Transaction->commit();


                        } catch (Exception $e) {

                            if ($e->getCode() == 21) {

                                $UsuarioToken = new UsuarioToken();
                                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                                $UsuarioToken->setCookie('0');
                                $UsuarioToken->setRequestId('0');
                                $UsuarioToken->setUsucreaId(0);
                                $UsuarioToken->setUsumodifId(0);
                                $UsuarioToken->setUsuarioId($UsuarioTokenSite->getUsuarioId());
                                $UsuarioToken->setToken($UsuarioToken->createToken());
                                $UsuarioToken->saldo = $amount;

                                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                                $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                                $UsuarioTokenMySqlDAO->getTransaction()->commit();


                            } else {
                                throw $e;
                            }
                        }

                    }

                    $response = array(
                        "code" => 0,
                        "data" => array(
                            "result" => 0,
                            "result_text" => null,
                            "data" => array(),
                        ),
                    );

                }

                if ($fromproduct == "Poker" && $to_product == "Sport") {
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $Usuario->creditWin($amount, $Transaction);

                    $Transaction->commit();


                    $JOINSERVICES = new JOINSERVICES();

                    $response = $JOINSERVICES->getBalance2($UsuarioMandante->getUsumandanteId());

                    $saldoXML = new SimpleXMLElement($response);

                    if ($saldoXML->RESPONSE->RESULT != "KO") {
                        $saldo = $saldoXML->RESPONSE->BALANCE->__toString();

                    }

                    $response = $JOINSERVICES->withdrawUser($UsuarioMandante->getUsumandanteId(), $amount);

                    $insertXML = new SimpleXMLElement($response);

                    if ($insertXML->RESPONSE->RESULT != "KO") {


                        $Proveedor = new Proveedor("", "JOINPOKER");

                        try {
                            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
                            $UsuarioToken->saldo = $saldo - $amount;
                            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                            $Transaction = $UsuarioTokenMySqlDAO->getTransaction();
                            $UsuarioTokenMySqlDAO->update($UsuarioToken);
                            $Transaction->commit();


                        } catch (Exception $e) {

                            if ($e->getCode() == 21) {

                                $UsuarioToken = new UsuarioToken();
                                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                                $UsuarioToken->setCookie('0');
                                $UsuarioToken->setRequestId('0');
                                $UsuarioToken->setUsucreaId(0);
                                $UsuarioToken->setUsumodifId(0);
                                $UsuarioToken->setUsuarioId($UsuarioTokenSite->getUsuarioId());
                                $UsuarioToken->setToken($UsuarioToken->createToken());
                                $UsuarioToken->saldo = $amount;

                                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                                $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                                $UsuarioTokenMySqlDAO->getTransaction()->commit();


                            } else {
                                throw $e;
                            }
                        }

                    }

                    $response = array(
                        "code" => 0,
                        "data" => array(
                            "result" => 0,
                            "result_text" => null,
                            "data" => array(),
                        ),
                    );

                }


                break;

            case "bet_history":

                /*     $response = array("code" => 0, "rid" => "150727456897316", "data" => array("bets" => [
                array("id" => 88586818, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 88586819, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885816818, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885862818, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885868318, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885868128, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885868148, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858681238, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858681823, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858612281238, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858681822, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858681128, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885868232118, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885868323426518, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 88586832118, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885868221318, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858682218, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 88586282218, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 88586282128, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858681213218, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858612132818, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 885868154648, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
                , array("id" => 8858681468, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)

                ]));*/


                $from_date = $json->params->from_date;
                $to_date = $json->params->to_date;

                $ItTicketEnc = new ItTicketEnc();

                $ToDateLocal = date("Y-m-d H:00:00", $to_date);
                $FromDateLocal = date("Y-m-d H:00:00", $from_date);

                $MaxRows = $params->MaxRows;
                $OrderedItem = $params->OrderedItem;
                $SkeepRows = $params->SkeepRows;

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 10;
                }

                $rules = [];
                //array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
                //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
                //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));
                array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => "332", "op" => "le"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $tickets = $ItTicketEnc->getTicketsCustom(" CONCAT(it_ticket_enc.fecha_crea, ' ',it_ticket_enc.hora_crea) fecha,it_ticket_enc.it_ticket_id,it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.cant_lineas,it_ticket_enc.bet_status ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                $tickets = json_decode($tickets);

                $total = 0;
                $bets = [];

                foreach ($tickets->data as $key => $value) {

                    $outcome = 0;

                    switch ($value->{"it_ticket_enc.bet_status"}) {
                        case "S":
                            $outcome = 3;
                            break;
                        case "N":
                            $outcome = 1;
                            break;
                    }


                    $arraybet = array();
                    $arraybet["id"] = ($value->{"it_ticket_enc.it_ticket_id"});
                    $arraybet["type"] = 1;
                    $arraybet["odd_type"] = null;
                    $arraybet["amount"] = ($value->{"it_ticket_enc.vlr_apuesta"});
                    $arraybet["k"] = floatval($value->{"it_ticket_enc.vlr_premio"}) / floatval(($value->{"it_ticket_enc.vlr_apuesta"}));
                    $arraybet["currency"] = "USD";
                    $arraybet["outcome"] = $outcome;
                    $arraybet["number"] = null;
                    $arraybet["client_id"] = 1;
                    $arraybet["betshop_id"] = null;
                    $arraybet["is_live"] = false;
                    $arraybet["payout"] = ($value->{"it_ticket_enc.vlr_premio"});
                    $arraybet["possible_win"] = ($value->{"it_ticket_enc.vlr_premio"});
                    $arraybet["accept_type_id"] = 0;
                    $arraybet["client_login"] = "tecnologiatemp2@gmail.com";
                    $arraybet["barcode"] = 885868184;
                    $arraybet["calc_date"] = strtotime($value->{".fecha"});
                    $arraybet["date_time"] = strtotime($value->{".fecha"});
                    $arraybet["events"] = [];

                    array_push($bets, $arraybet);


                }
                $response = array();

                $response["code"] = 0;
                $response["rid"] = $json->rid;
                $response["data"] = array("bets" => $bets);


                break;

            case "casino_auth":

                if ($json->session->logueado) {

                    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
                    $UsuarioToken = new UsuarioToken("", '0', $json->session->usuario);

                    $response = array();

                    $response['code'] = 0;

                    $data = array();
                    $result = array();

                    $result["has_error"] = "False";
                    $result["error_id"] = "0";
                    $result["id"] = $UsuarioMandante->getUsumandanteId();
                    $result["external_id"] = "";
                    $result["username"] = $UsuarioMandante->getUsumandanteId();
                    $result["name"] = $UsuarioMandante->getNombres() . " " . $UsuarioMandante->getApellidos();
                    $result["gender"] = "False";
                    $result["balance"] = $UsuarioMandante->getSaldo();
                    $result["virtual_amount"] = "0.0";
                    $result["coin"] = "0.0";
                    $result["currency"] = $UsuarioMandante->getMoneda();
                    $result["partner_id"] = $json->session->mandante;
                    $result["email"] = "";
                    $result["locked"] = "False";
                    $result["token"] = $UsuarioToken->getToken();

                    $data['result'] = $result;

                    $response['data'] = $data;

                }

                break;

            case "get_table":

                $recurso = $json->params->recurso;
                $deptos = implode(', ', $json->params->deptos);
                $paises = implode(', ', $json->params->paises);
                $puntos = implode(', ', $json->params->puntos);
                $concesionarios = implode(', ', $json->params->concesionarios);
                $ciudades = implode(', ', $json->params->ciudades);
                $productos = implode(', ', $json->params->productos);
                $proveedores = implode(', ', $json->params->proveedores);
                $estado = implode(', ', $json->params->estado);
                $estadoProducto = implode(', ', $json->params->estadoProducto);
                $fecha_ini = $json->params->where->from_date;
                $fecha_fin = $json->params->where->to_date;

                if ($fecha_ini == "" || $fecha_ini == null) {
                    $fecha_ini = time();
                    $fecha_fin = time();
                }

                // $time = strtotime(substr($fecha_ini, 0, 10));
                $time = $fecha_ini;
                $fecha_ini = date('Y-m-d', $time);

                //$time = strtotime(substr($fecha_fin, 0, 10));
                $time = $fecha_fin;
                $fecha_fin = date('Y-m-d', $time);

                switch (strtolower($recurso)) {

                    case "contingencia":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/perfil3_xml/";

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Perfil', 'Descripción', 'Apuesta Minima', 'Contingencia'];
                        $GRID_COLMODEL = [
                            array('name' => 'id', index => 'perfil_id', width => 130, search => false, editable => false, editoptions => array(maxlength => 15), editrules => array(required => true)),
                            array('name' => 'descripcion', index => 'descripcion', width => 350, editable => true, editoptions => array(maxlength => 50), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array('name' => 'apuesta_min', index => 'apuesta_min', hidden => true, width => 95, align => 'right', editable => true, formatter => 'number', editrules => array(required => false), formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array('name' => 'contingencia', index => 'contingencia', align => 'center', hidden => true, width => 80, editable => false, search => false),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_CAPTION = "Lista de perfiles";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/perfil_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

                        break;

                    case "gestion_contacto":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/gestion_contacto_xml/?estado=A";

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Fecha/Hora Creación', 'Apellidos', 'Nombres', 'Empresa', 'Email', 'Teléfono', 'Pais', 'Provincia', 'Dirección', 'Skype', 'Observacion', 'Fecha/Hora Ultima Modif', 'Usuario Ultima Modif'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'contactocom_id', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 130, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'apellidos', index => 'a.apellidos', width => 120, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombres', index => 'a.nombres', width => 120, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'empresa', index => 'a.empresa', width => 100, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'email', index => 'a.email', width => 180, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'telefono', index => 'a.telefono', width => 80, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'pais_nom', index => 'b.pais_nom', width => 90, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'depto_nom', index => 'c.depto_nom', width => 90, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'direccion', index => 'a.direccion', width => 110, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'skype', index => 'a.skype', width => 80, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'observacion', index => 'a.observacion', width => 250, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_modif', index => 'a.fecha_modif', align => 'center', width => 130, editable => false, search => false),
                            array(name => 'usumodif_id', index => 'a.usumodif_id', align => 'left', width => 150, editable => false, search => false),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "a.fecha_crea,a.apellidos,a.nombres";
                        $GRID_SORTORDER = "asc";
                        $GRID_CAPTION = "Trabaja con nosotros";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/perfil_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

                        break;

                    case "usuario_admin_lista":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/usuario_admin_lista_xml/?estado=A&perfil_id=";

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro', 'Login', 'Nombre', 'Ciudad', 'Nombre Contacto', 'Telefono', 'Fecha Creacion', 'Fecha Ultima Entrada', 'Email', 'Concesionario', 'Subconcesionario'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.usuario_id', align => 'center', width => 65, hidden => false, search => false, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['eq'])),
                            array(name => 'login', index => 'a.login', width => 115, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre', index => 'a.nombre', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'ciudad', index => 'g.ciudad_nom', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre_contacto', index => 'f.nombre_contacto', width => 160, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'telefono', index => 'f.telefono', width => 110, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 125, editable => false, search => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_ult', index => 'a.fecha_ult', align => 'center', width => 125, editable => false, search => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'email', index => 'f.email', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'concesionario', index => 'j.nombre', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'subconcesionario', index => 'k.nombre', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 500, 1000];
                        $GRID_SORTNAME = "a.nombre";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";
                        $GRID_CAPTION = "Usuarios";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;

                        break;

                    case "menu":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/menu_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Descripcion', 'Pagina', 'Orden'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'menu_id', width => 40, search => false, editable => false),
                            array(name => 'descripcion', index => 'descripcion', width => 300, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'pagina', index => 'pagina', width => 300, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'orden', index => 'orden', align => 'center', width => 80, editable => $GRID_EDITAR, editoptions => array(maxlength => 5), editrules => array(required => true, integer => true, minValue => 0), search => false),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "orden";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/menu_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Menus";

                        break;

                    case "perfil":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/perfil_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Perfil', 'Descripción', 'Apuesta Minima', 'Tipo', 'Dias Caducidad Clave'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'perfil_id', width => 130, search => false, editable => false, editoptions => array(maxlength => 15), editrules => array(required => true)),
                            array(name => 'descripcion', index => 'descripcion', width => 350, editable => $GRID_EDITAR, editoptions => array(maxlength => 50), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'apuesta_min', index => 'apuesta_min', width => 95, align => 'center', editable => $GRID_EDITAR, formatter => 'number', editrules => array(required => true, number => true, minValue => 1), formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'tipo', index => 'tipo', width => 110, align => 'center', editable => $GRID_EDITAR, sortable => false, search => false, stype => 'select', edittype => "select", editoptions => array(value => ":...;A:Administrativo;C:Comercial"), editrules => array(required => true)),
                            array(name => 'dias_clave', index => 'dias_clave', width => 95, align => 'center', editable => $GRID_EDITAR, formatter => 'number', editrules => array(required => true, integer => true, minValue => 0, maxValue => 1000), formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/perfil_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Perfiles";

                        break;

                    case "perfil_opcion":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/perfil_opcion_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Perfil', 'Menu', 'Submenu', 'Adicionar', 'Editar', 'Eliminar'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'id', hidden => true, width => 200, search => false, editable => false),
                            array(name => 'perfil_id', index => 'perfil_id', width => 200, sortable => false, search => false, stype => 'select', editable => false, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_perfil/?tipo=B"), editrules => array(required => true)),
                            array(name => 'menu_id', index => 'b.menu_id', width => 250, sortable => false, search => true, stype => 'select', editable => false, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_menu/?tipo=B")),
                            array(name => 'submenu_id', index => 'b.submenu_id', width => 250, sortable => false, search => true, stype => 'select', editable => false, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_submenu/?tipo=B"), editrules => array(required => true)),
                            array(name => 'adicionar_opcion', index => 'adicionar_opcion', width => 80, align => 'center', editable => $GRID_EDITAR, sortable => false, search => false, stype => 'select', edittype => "select", editoptions => array(value => ":...;false:No;true:Si"), editrules => array(required => true)),
                            array(name => 'editar_opcion', index => 'editar_opcion', width => 80, align => 'center', editable => $GRID_EDITAR, sortable => false, search => false, stype => 'select', edittype => "select", editoptions => array(value => ":...;false:No;true:Si"), editrules => array(required => true)),
                            array(name => 'eliminar_opcion', index => 'eliminar_opcion', width => 80, align => 'center', editable => $GRID_EDITAR, sortable => false, search => false, stype => 'select', edittype => "select", editoptions => array(value => ":...;false:No;true:Si"), editrules => array(required => true)),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "c.orden,b.descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/perfil_opcion_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Opciones para perfil";
                        $GRID_GROUPINGVIEW = array(
                            groupField => ['perfil_id'],
                            groupColumnShow => [false],
                            groupText => ['<b>{0} - {1} Elemento(s)</b>'],
                            groupCollapse => true,
                            groupOrder => ['asc'],
                        );

                        $GRID_SELECT = ["perfil_id", "submenu_id"];

                        break;

                    case "submenu":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/submenu_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Descripcion', 'Pagina', 'Menu'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'submenu_id', width => 40, search => false, editable => false),
                            array(name => 'descripcion', index => 'a.descripcion', width => 250, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'pagina', index => 'a.pagina', width => 270, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'menu_id', index => 'a.menu_id', width => 170, sortable => true, search => true, stype => 'select', searchoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_menu/?tipo=B"), editable => $GRID_EDITAR, edittype => "select", editrules => array(required => true), editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_menu/?tipo=E")),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "b.orden,a.descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/submenu_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Submenus";
                        $GRID_GROUPINGVIEW = "";

                        break;

                    case "usuario_perfil":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/usuario_perfil_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Usuario', 'Perfil'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.usuario_id', width => 350, search => true, stype => 'select', searchoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuario4/?tipo=B"), editable => false, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuario4/?tipo=B"), editrules => array(required => true)),
                            array(name => 'perfil_id', index => 'a.perfil_id', width => 300, sortable => true, search => true, stype => 'select', searchoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_perfil/?tipo=B"), editable => $GRID_EDITAR, editrules => array(required => true), edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_perfil/?tipo=B")),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "a.usuario_id";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/usuario_perfil_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Perfiles para Usuarios";
                        $GRID_GROUPINGVIEW = "";

                        $GRID_SELECT = ["id"];

                        break;

                    case "clasificador":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/clasificador_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Agrupador', 'Descripcion', 'Tipo', 'Estado'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'clasificador_id', width => 40, search => false, editable => false),
                            array(name => 'agrupa', index => 'a.tipo', width => 40, search => false, editable => false),
                            array(name => 'descripcion', index => 'a.descripcion', width => 250, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'tipo', index => 'a.tipo', align => 'center', width => 180, editable => $GRID_EDITAR, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;RG:Regional;TP:Tipo Punto;TE=>Tipo Establecimiento"), edittype => "select", editrules => array(required => true), editoptions => array(value => "RG:Regional;TP:Tipo Punto;TE:Tipo Establecimiento")),
                            array(name => 'estado', index => 'estado', align => 'center', width => 100, editable => $GRID_EDITAR, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo"), edittype => "select", editrules => array(required => true), editoptions => array(value => "A:Activo;I:Inactivo")),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "a.descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/clasificador_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Clasificadores";
                        $GRID_GROUPINGVIEW = array(
                            groupField => ['agrupa'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Clasificadores )</font></b>'],
                            groupCollapse => false,
                            groupOrder => ['asc'],
                        );

                        $GRID_SELECT = ["id"];

                        break;

                    case "concesionario":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/concesionario_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Concesionario', 'Subconcesionario', 'Usuario Asociado', 'Concesionario', 'Subconcesionario', 'Es Punto de Venta?'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'concesionario_id', width => 40, search => false, editable => false),
                            array(name => 'usupadre_id', hidden => true, index => 'b.nombre', width => 300, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => $GRID_EDITAR, editrules => array(required => true), edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuconcesionario/?tipo=B&clase=C")),
                            array(name => 'usupadre2_id', hidden => true, index => 'e.nombre', width => 300, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => $GRID_EDITAR, editrules => array(required => false), edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_ususubconcesionario/?tipo=B&clase=C")),
                            array(name => 'usuhijo_id', index => 'c.nombre', width => 350, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => $GRID_EDITAR, editrules => array(required => true), edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuconcesionario/?tipo=B&clase=U")),
                            array(name => 'concesionario', index => 'b.nombre', width => 210, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => false),
                            array(name => 'concesionario2', index => 'e.nombre', width => 210, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => false),
                            array(name => 'punto_venta', index => 'punto_venta', align => 'center', width => 60, editable => false, sortable => true, search => false),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "b.nombre,c.nombre";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/concesionario_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Usuarios Asociados x Concesionario";
                        $GRID_GROUPINGVIEW = array(
                            groupField => ['concesionario'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Usuarios )</font></b>'],
                            groupCollapse => false,
                            groupOrder => ['asc'],
                        );

                        $GRID_SELECT = ["usupadre_id", "usupadre2_id", "usuhijo_id"];

                        break;

                    case "puntoventa":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/puntoventa_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Descripcion', 'Departamento', 'Ciudad', 'Direccion', 'Barrio', 'Tel&eacute;fono', 'Nombre del Contacto', 'Usuario Ascociado', 'Estado', 'Concesionario'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'puntoventa_id', align => 'center', width => 30, search => false, editable => false),
                            array(name => 'descripcion', index => 'a.descripcion', width => 250, editable => $GRID_EDITAR, editoptions => array(maxlength => 150), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'depto_id', index => 'depto_id', width => 227, sortable => true, search => false, hidden => true, editable => false, edittype => "select", editrules => array(required => true)),
                            array(name => 'ciudad_id', index => 'c.ciudad_nom', width => 120, sortable => false, search => true, stype => 'text', editable => $GRID_EDITAR, editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_ciudad/?tipo=B&depto_id=0"), edittype => "select", editrules => array(required => true), searchoptions => array(sopt => ['cn'])),
                            array(name => 'direccion', index => 'a.direccion', width => 220, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'barrio', index => 'a.barrio', width => 150, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'telefono', index => 'a.telefono', align => 'center', width => 150, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre_contacto', index => 'nombre_contacto', width => 200, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'usuario_id', index => 'b.nombre', hidden => true, width => 140, sortable => false, search => true, stype => 'text', editable => $GRID_EDITAR, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuario3/?tipo=B&clase=PUNTO"), editrules => array(required => true), searchoptions => array(sopt => ['cn'])),
                            array(name => 'estado', index => 'estado', hidden => true, align => 'center', width => 90, editable => $GRID_EDITAR, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo"), edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo")),
                            array(name => 'concesionario', index => 'f.nombre', width => 250, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/puntoventa_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Puntos de venta";
                        $GRID_GROUPINGVIEW = "";

                        $GRID_SELECT = ["depto_id"];

                        break;

                    case "cupo":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/cupo_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro', 'Descripcion', 'Tipo', 'Concesionario', 'Cupo para Apuestas', 'Cupo para Recargas', 'Moneda'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.usuario_id', hidden => true, align => 'center', width => 70, search => false, editable => false),
                            array(name => 'nombre', index => 'b.nombre', width => 180, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'tipo', index => 'tipo', align => 'center', width => 70, search => false, editable => false),
                            array(name => 'concesionario', index => 'd.nombre', hidden => true, width => 170, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'creditos_base', index => 'a.creditos_base', width => 80, align => 'right', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'cupo_recarga', index => 'a.cupo_recarga', width => 80, align => 'right', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "b.nombre";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/cupo_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Listado de Concesionarios / Puntos de Venta";
                        $GRID_GROUPINGVIEW = array(
                            groupField => ['tipo'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => false,
                            groupOrder => ['asc'],
                        );

                        $GRID_SELECT = [];

                        break;

                    case "bono_it":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/bono_it_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Descripción', 'PlanID', 'Código', 'Tipo', 'Fecha Inicio', 'Fecha Fin', 'Dias Expiración', 'Owner', 'Usuario Ultima Modificación', 'Fecha Modificación'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'bono_id', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'descripcion', index => 'descripcion', width => 200, editable => $GRID_EDITAR, editoptions => array(maxlength => 150), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'bonusplanid', index => 'bonusplanid', align => 'center', width => 100, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true, integer => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'codigo', index => 'codigo', align => 'center', width => 100, editable => $GRID_EDITAR, editoptions => array(maxlength => 50), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'tipo', index => 'tipo', width => 100, editable => $GRID_EDITAR, edittype => "select", editoptions => array(value => "C:Codigo;D:Depósito;PD:Primer Depósito;F:Free Bet"), editrules => array(required => true), search => true, stype => 'select', searchoptions => array(value => ":...;C:Codigo;D:Deposito;PD:Primer Deposito;F:Free Bet")),
                            array(name => 'fecha_ini', index => 'fecha_ini', align => 'center', width => 88, editable => $GRID_EDITAR, formoptions => array(elmsuffix => '  aaaa-mm-dd'), editoptions => array(maxlength => 10), editrules => array(required => true, date => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_fin', index => 'fecha_fin', align => 'center', width => 88, editable => $GRID_EDITAR, formoptions => array(elmsuffix => '  aaaa-mm-dd'), editoptions => array(maxlength => 10), editrules => array(required => true, date => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'dias_expira', index => 'a.dias_expira', width => 90, align => 'center', editable => $GRID_EDITAR, editrules => array(required => true, integer => true, minValue => 1), editoptions => array(defaultValue => '1'), formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'owner', index => 'owner', align => 'center', width => 100, editable => $GRID_EDITAR, editoptions => array(maxlength => 50), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre', index => 'nombre', width => 200, editable => false, search => false),
                            array(name => 'fecha_modif', index => 'fecha_modif', align => 'center', width => 140, editable => false, search => false),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/bono_it_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
                        $GRID_CAPTION = "Bonos";
                        $GRID_GROUPINGVIEW = "";

                        $GRID_SELECT = [];

                        break;

                    case "cuenta_cobro_eliminar":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/cuenta_cobro_eliminar_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro Nota Retiro', 'Fecha Eliminaci&oacute;n', 'Usuario que la Elimin&oacute;', 'Valor Nota', 'Moneda', 'Fecha Nota', 'Nro Usuario', 'Nombre del Usuario', 'Observaciones'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.cuenta_id', align => 'center', width => 72, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 135, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'usucrea_id', index => 'b.nombre', width => 130, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'valor', index => 'a.valor', align => 'right', width => 72, editable => false, editoptions => array(maxlength => 8), editrules => array(required => true, integer => true, minValue => 0), formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),
                            array(name => 'fecha_nota', index => 'a.fecha_nota', align => 'center', width => 135, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'usuario_id', index => 'a.usuario_id', align => 'center', width => 75, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre', index => 'c.nombre', width => 185, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'observ', index => 'a.observ', width => 210, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "a.fecha_crea";
                        $GRID_SORTORDER = "desc";
                        $GRID_EDITURL = "";
                        $GRID_CAPTION = "Listado de Notas de Retiro Eliminadas";
                        $GRID_GROUPINGVIEW = "";

                        $GRID_SELECT = [];

                        break;

                    case "gestion_red":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/gestion_red1_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro', 'Perfil', 'Nombre', 'Estado', 'Bloqueo Ventas', 'Observaciones'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'usuario_id', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'perfil', index => 'perfil', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'nombre', index => 'a.nombre', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'estado', index => 'a.estado_esp', align => 'center', width => 70, editable => false, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'bloqueo_ventas', index => 'a.bloqueo_ventas', align => 'center', width => 60, editable => false, search => true, stype => 'select', searchoptions => array(value => ":...;S:Si;N:No")),
                            array(name => 'observ', index => 'a.observ', width => 210, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "a.nombre";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";
                        $GRID_CAPTION = "Listado de Notas de Retiro Eliminadas";
                        $GRID_GROUPINGVIEW = array(
                            groupField => ['perfil'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Personas )</font></b>'],
                            groupCollapse => false,
                            groupOrder => ['asc'],
                        );

                        $GRID_SELECT = [];

                        break;

                    case "registro_rapido":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/registro_rapido_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = true;
                        $GRID_AGREGAR = true;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Tipo Documento', 'Nro Documento', 'Pais', 'Moneda', 'Primer Apellido', 'Segundo Apellido', 'Primer Nombre', 'Segundo Nombre'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'registro_id', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'tipo_doc', index => 'a.tipo_doc', width => 150, editable => $GRID_EDITAR, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;C:Cedula de Ciudadania;E:Cedula de Extranjeria;P:Pasaporte"), edittype => "select", editoptions => array(value => "C:Cedula de Ciudadania;E:Cedula de Extranjeria;P:Pasaporte")),
                            array(name => 'cedula', index => 'a.cedula', width => 100, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'pais', index => 'pais', width => 160, sortable => true, search => true, stype => 'select', searchoptions => array(dataUrl => "lista_pais2.php?tipo=B"), editable => false),
                            array(name => 'moneda', index => 'pais', align => 'center', width => 80, sortable => true, search => false, editable => false),
                            array(name => 'apellido1', index => 'a.apellido1', width => 120, editable => $GRID_EDITAR, editoptions => array(maxlength => 20), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'apellido2', index => 'a.apellido2', width => 120, editable => $GRID_EDITAR, editoptions => array(maxlength => 20), editrules => array(required => false), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre1', index => 'a.nombre1', width => 120, editable => $GRID_EDITAR, editoptions => array(maxlength => 20), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre2', index => 'a.nombre2', width => 120, editable => $GRID_EDITAR, editoptions => array(maxlength => 20), editrules => array(required => false), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "a.apellido1,a.apellido2,a.nombre1,a.nombre2";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/registro_rapido_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

                        $GRID_CAPTION = "Listado de Usuarios con Registro Rapido";
                        $GRID_GROUPINGVIEW = "";

                        $GRID_SELECT = [];

                        break;

                    case "cheque_reimpresion":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/cheque_reimpresion_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro. Recarga', 'Punto de Venta', 'Nro. Cliente', 'Nombre Cliente', 'Fecha Creacion', 'Valor', 'Moneda'];
                        $GRID_COLMODEL = [

                            array(name => 'id', index => 'a.recarga_id', hidden => false, align => 'center', width => 80, search => true, editable => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'puntoventa_id', index => 'a.puntoventa_id', align => 'center', width => 80, search => false),
                            array(name => 'usuario_id', index => 'a.usuario_id', align => 'center', width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre', index => 'b.nombre', width => 290, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 120, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'valor', index => 'a.valor', width => 100, align => 'right', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),

                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];
                        $GRID_SORTNAME = "a.fecha_crea";
                        $GRID_SORTORDER = "desc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Reimpresión de cheques";
                        $GRID_GROUPINGVIEW = array(
                            groupField => ['puntoventa_id'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Juegos )</font></b>'],
                            groupCollapse => false,
                            groupOrder => ['asc'],
                        );

                        $GRID_SELECT = [];

                        break;

                    case "flujo_caja":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/flujo_caja_xml/?fecha_ini=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&tipo=I" . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Caja Agrupa', 'Fecha', 'Hora', 'No. Ticket', 'Forma Pago 1', 'Valor 1', 'Pago Bono / T.C.', 'Valor 2', 'Valor Entradas Efectivo', 'Valor Entradas Bono / T.C.', 'Valor Entradas Traslados', 'Valor Entradas Recargas', 'Valor Salidas Efectivo', 'Valor Salidas Traslados', 'Valor Salidas Notas de Retiro', 'Saldo', 'Moneda'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'ticket_id', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'punto_venta', index => 'd.nombre', align => 'center', width => 70, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_crea', index => 'x.fecha_crea', align => 'center', width => 86, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'hora_crea', index => 'x.hora_crea', align => 'center', width => 50, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'ticket_id', index => 'x.ticket_id', align => 'center', width => 86, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'forma_pago1', index => "case when x.tipomov_id='S' then '-' else b.descripcion end", width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'valor_form1', index => 'a.valor_forma1', hidden => true, align => 'right', width => 75, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'forma_pago2', index => "case when x.tipomov_id='S' then '' else c.descripcion end", width => 70, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'valor_form2', index => 'a.valor_forma2', hidden => true, align => 'right', width => 75, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_efectivo', index => 'valor_entrada_efectivo', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_bono', index => 'valor_entrada_bono', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_traslado', index => 'valor_entrada_traslado', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_recarga', index => 'valor_entrada_recarga', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_efectivo', index => 'valor_salida_efectivo', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_traslado', index => 'valor_salida_traslado', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_recarga', index => 'valor_salida_recarga', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'saldo', index => 'saldo', align => 'right', width => 75, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),
                        ];
                        if (stristr($_SESSION["win_perfil"], 'PUNTO')) {
                            $GRID_ROWNUM = 250;
                            $GRID_ROWLIST = [250, 500, 1000];
                        } else {
                            $GRID_ROWNUM = 1000;
                            $GRID_ROWLIST = [1000, 5000, 10000];
                        }

                        $GRID_SORTNAME = "d.nombre,x.fecha_crea desc,x.hora_crea";
                        $GRID_SORTORDER = "desc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Flujo de Caja";
                        $GRID_GROUPINGVIEW = array(
                            groupField => ['punto_venta'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Movimientos )</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );

                        $GRID_SELECT = [];

                        break;

                    case "pago_nota_cobro":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/cuenta_cobro_detalle_xml/?cuenta_id=&clave=";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Usuario', 'Valor', 'Moneda', 'Pagada?'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'id', hidden => true, align => 'center', width => 30, search => false, editable => false),
                            array(name => 'nombre', index => 'b.nombre', width => 320, search => false, editable => false),
                            array(name => 'valor', index => 'valor', align => 'center', width => 130, editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),
                            array(name => 'pagada', index => 'pagada', align => 'center', width => 80, search => false, editable => false),
                        ];
                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.cuenta_id";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Detalle de la Nota de Retiro";
                        $GRID_GROUPINGVIEW = "";

                        $GRID_SELECT = [];

                        break;

                    case "consulta_flujo_historico":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/consulta_flujo_historico_xml/?fecha_ini=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&tipo=I" . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";

                        $GRID_COLNAMES = ['Id', 'Caja Agrupa', 'Fecha', 'Moneda', 'Cantidad Tickets', 'Valor Entradas Efectivo', 'Valor Entradas Bono / T.C.', 'Valor Entradas Traslados', 'Valor Entradas Recargas', 'Valor Salidas Efectivo', 'Valor Salidas Traslados', 'Valor Salidas Notas de Retiro', 'Saldo'];

                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'ticket_id', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'punto_venta', index => 'd.nombre', align => 'center', width => 70, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_crea', index => 'x.fecha_crea', align => 'center', width => 86, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 70, editable => false, search => false),
                            array(name => 'cant_tickets', index => 'cant_tickets', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_efectivo', index => 'valor_entrada_efectivo', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_bono', index => 'valor_entrada_bono', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_traslado', index => 'valor_entrada_traslado', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_recarga', index => 'valor_entrada_recarga', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_efectivo', index => 'valor_salida_efectivo', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_traslado', index => 'valor_salida_traslado', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_recarga', index => 'valor_salida_recarga', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'saldo', index => 'saldo', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                        ];

                        if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "OPERCOM") {
                            array_push($GRID_COLNAMES, 'Valor Premios Pendientes');
                            array_push($GRID_COLMODEL, array(name => 'premios_pend', index => 'premios_pend', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false));
                        }

                        $GRID_ROWNUM = 50000;
                        $GRID_ROWLIST = [50000, 100000, 200000];

                        $GRID_SORTNAME = "z.fecha_crea";
                        $GRID_SORTORDER = "desc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Flujo de Caja Historico";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['punto_venta'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );

                        $GRID_SELECT = [];

                        break;

                    case "consulta_flujo_caja":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/consulta_flujo_caja_xml/?tipo=I&fecha_ini=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Caja Agrupa', 'Fecha', 'Moneda', 'Cantidad Tickets', 'Valor Entradas Efectivo', 'Valor Entradas Bono / T.C.', 'Valor Entradas Traslados', 'Valor Entradas Recargas', 'Valor Salidas Efectivo', 'Valor Salidas Traslados', 'Valor Salidas Notas de Retiro', 'Saldo', 'Moneda'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'ticket_id', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'punto_venta', index => 'd.nombre', align => 'center', width => 70, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_crea', index => 'x.fecha_crea', align => 'center', width => 86, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 70, editable => false, search => false),
                            array(name => 'cant_tickets', index => 'cant_tickets', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_efectivo', index => 'valor_entrada_efectivo', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_bono', index => 'valor_entrada_bono', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_traslado', index => 'valor_entrada_traslado', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_entrada_recarga', index => 'valor_entrada_recarga', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_efectivo', index => 'valor_salida_efectivo', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_traslado', index => 'valor_salida_traslado', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_salida_recarga', index => 'valor_salida_recarga', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'saldo', index => 'saldo', align => 'right', width => 75, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'moneda', index => 'moneda', align => 'center', width => 80, editable => false, search => false),
                        ];

                        if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "OPERCOM") {
                            array_push($GRID_COLNAMES, 'Valor Premios Pendientes');
                            array_push($GRID_COLMODEL, array(name => 'premios_pend', index => 'premios_pend', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false));
                        }

                        $GRID_ROWNUM = 50000;
                        $GRID_ROWLIST = [50000, 100000, 200000];

                        $GRID_SORTNAME = "z.fecha_crea";
                        $GRID_SORTORDER = "desc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Flujo de Caja Resumido";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['punto_venta'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "informe_casino":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/informe_casino_xml/?fecha_inicio=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Pais', 'Fecha', 'Moneda', 'Cantidad de Jugadas', 'Valor Apostado', 'Valor Jugada Promedio', 'Proyecci&oacute;n Premios', 'Valor Premios'];
                        $GRID_COLMODEL = [

                            array(name => 'id', index => 'id', width => 40, hidden => true, search => false, editable => false),
                            array(name => 'pais_nom', index => 'pais_nom', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'fecha_crea', index => 'fecha_crea', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
                            array(name => 'cant_tickets', index => 'cant_tickets', width => 80, align => 'center', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'valor_apostado', index => 'valor_apostado', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'valor_ticket_prom', hidden => true, index => 'valor_ticket_prom', width => 100, align => 'right', summaryType => 'avg', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'proyeccion_premios', hidden => true, index => 'proyeccion_premios', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'valor_premios', index => 'valor_premios', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "x.trnFecReg";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Informe de Casino";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['pais_nom'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['asc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "transacciones":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/transacciones_xml/?fecha_inicio=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises . "&productos=" . $productos . "&proveedores=" . $proveedores;
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Proveedor', 'Producto', 'Usuario', 'Pais', 'Fecha', 'Moneda', 'Valor', 'Estado', 'Estado Producto'];
                        $GRID_COLMODEL = [

                            array(name => 'id', index => 'id', width => 40, hidden => true, search => false, editable => false),
                            array(name => 'proveedor_nom', index => 'proveedor_nom', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'producto_nom', index => 'producto_nom', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'usuario', index => 'usuario', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'pais_nom', index => 'pais_nom', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'fecha_crea', index => 'fecha_crea', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
                            array(name => 'valor', index => 'valor', width => 80, align => 'center', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'estado', index => 'estado', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'estado_producto', index => 'pais_nom', width => 120, align => 'center', search => false, editable => false),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.fecha_crea";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Transacciones";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['pais_nom'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['asc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "consulta_listado_recargas":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/consulta_listado_recargas_xml/?fecha_ini=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&tipo=C" . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Pais', 'Punto de Venta', 'Nro. Usuario', 'Nombre Usuario', 'Nro. Recarga', 'Fecha Recarga', 'Nro. Pedido', 'Valor Recargado', 'Moneda', 'Direccion IP'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'recarga_id', hidden => true, width => 40, search => false, editable => false),
                            array(name => 'pais_nom', index => 'pais_nom', width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'punto_venta', index => 'b.nombre', width => 170, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'usuario_id', index => 'a.usuario_id', align => 'center', width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'usuario', index => 'c.nombre', width => 170, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'recarga_id', index => 'a.recarga_id', align => 'center', width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['eq'])),
                            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'pedido', index => 'a.pedido', width => 100, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'valor', index => 'a.valor', align => 'right', width => 90, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
                            array(name => 'dir_ip', index => 'a.dir_ip', width => 100, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 500, 1000];

                        $GRID_SORTNAME = "b.nombre,a.fecha_crea desc,c.nombre";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Listado de Recargas";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['pais_nom'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "informe_gerencial":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/informe_gerencial_xml/?fecha_inicio=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&tipo=C" . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Pais', 'Fecha', 'Moneda', 'Cantidad de Tickets', 'Valor Apostado', 'Valor Ticket Promedio', 'Proyecci&oacute;n Premios', 'Valor Premios'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'id', width => 40, hidden => true, search => false, editable => false),
                            array(name => 'pais_nom', index => 'pais_nom', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'fecha_crea', index => 'fecha_crea', width => 120, align => 'center', search => false, editable => false),
                            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
                            array(name => 'cant_tickets', index => 'cant_tickets', width => 80, align => 'center', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'valor_apostado', index => 'valor_apostado', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'valor_ticket_prom', index => 'valor_ticket_prom', width => 100, align => 'right', summaryType => 'avg', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'proyeccion_premios', index => 'proyeccion_premios', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'valor_premios', index => 'valor_premios', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "x.fecha_crea";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Informe Gerencial";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['pais_nom'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "consulta_premio_pend":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/ticket_premiado_xml/?pagados=N&punto&caduco=N";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['No. Ticket', 'Agrupador', 'Fecha Crea', 'Hora Crea', 'Fecha Premio', 'Hora Premio', 'Valor Apostado', 'Valor Premio', 'Moneda', 'Punto de Venta', 'Concesionario', 'Fecha Caducidad Pago', 'Caduco'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.ticket_id', align => 'center', width => 82, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'pais_nom', index => 'pais_nom', align => 'center', width => 95, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 82, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'hora_crea', index => 'a.hora_crea', align => 'center', width => 45, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_cierre', index => 'a.fecha_cierre', align => 'center', width => 82, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'hora_cierre', index => 'a.hora_cierre', align => 'center', width => 45, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'vlr_apuesta', index => 'a.vlr_apuesta', align => 'right', width => 60, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'vlr_premio', index => 'a.vlr_premio', align => 'right', width => 65, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
                            array(name => 'punto_venta', index => 'b.nombre', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'concesionario', index => 'y.nombre', hidden => true, width => 180, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_maxpago', index => 'a.fecha_maxpago', align => 'center', width => 82, editable => false, formoptions => array(elmsuffix => '  aaaa-mm-dd'), editoptions => array(maxlength => 10), editrules => array(required => true, date => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'caduco', index => 'caduco', width => 50, align => 'center', editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.ticket_id";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Listado de Premios x Pagar";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['pais_nom'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Tickets )</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "consulta_online_resumen":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/consulta_online_resumen_xml/?fecha_ini=$fecha_hoy&fecha_fin=$fecha_hoy";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['...', 'Pais', 'Fecha', 'Moneda', 'Cant Tickets', 'Saldo Recargas', 'Saldo Disp Retiro', 'Valor Recargas', 'Valor Promocional', 'Valor Tickets Abiertos', 'Notas Retiro Pend', 'Valor Pagado', 'Valor Apostado', 'Valor Premios'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'id', width => 35, align => 'center', editable => false, search => false),
                            array(name => 'pais_nom', index => 'pais_nom', align => 'center', width => 120, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha', index => 'x.fecha', align => 'center', width => 90, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 60, editable => false, search => false),
                            array(name => 'cant_tickets', index => 'x.cant_tickets', width => 60, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
                            array(name => 'saldo_recarga', index => 'x.saldo_recarga', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'disp_retiro', index => 'x.disp_retiro', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_recargas', index => 'x.valor_recargas', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_promocional', index => 'x.valor_promocional', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_tickets_abiertos', index => 'x.valor_tickets_abiertos', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'nora_retiro_pend', index => 'x.nota_retiro_pend', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_pagado', index => 'x.valor_pagado', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_apostado', index => 'x.valor_apostado', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                            array(name => 'valor_premio', index => 'x.valor_premio', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "x.fecha";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Reporte Resumido de los Usuarios OnLine";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['pais_nom'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "promocional":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/promocional_xml/?fecha_ini=$fecha_hoy&fecha_fin=$fecha_hoy";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Descripcion', 'Estado', 'Moneda', 'Tipo', 'Valor', 'Tope', 'Total', 'Acumulado', 'Tipo Promocional', 'Dias Expiracion', 'Fecha inicio', 'Fecha fin', 'Fecha Modificación', 'Usuario que Modifica'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.promocional_id', width => 35, align => 'center', editable => false, search => false),
                            array(name => 'descripcion', index => 'a.descripcion', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'moneda', index => 'a.moneda', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'tipo', index => 'a.tipo', align => 'center', width => 120, editable => false, edittype => "select", editoptions => array(value => "Porcentaje:PORCENTAJE;Valor:VALOR"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'valor', index => 'a.valor', align => 'center', width => 140, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'tope', index => 'a.tope', align => 'center', width => 140, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'total', index => 'a.total', align => 'center', width => 140, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'acumulado', index => 'a.acumulado', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'tipopromocional', index => 'a.tipo_promocional', align => 'center', width => 120, editable => false, edittype => "select", editoptions => array(value => "Primer Deposito:PRIMERDEPOSITO"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'dias_expiracion', index => 'a.dias_expiracion', align => 'center', width => 140, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_ini', index => 'a.fecha_ini', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_fin', index => 'a.fecha_fin', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'fecha_modif', index => 'a.fecha_modif', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'usu_modif', index => 'usu_modif', width => 170, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/promocional_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

                        $GRID_CAPTION = "Listado de Promocionales";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                    case "promocional_detalle":
                        $promocion_id = implode(', ', $json->params->promocionId);

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/promocional_detalle_xml/?fecha_ini=$fecha_hoy&fecha_fin=$fecha_hoy&promocion_id=" . $promocion_id;
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Promocion', 'KEY', 'valor', 'estado'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.promocondicion_id', width => 35, align => 'center', editable => false, search => false),
                            array(name => 'Promocion', index => 'b.descripcion', width => 35, align => 'center', editable => false, search => false),
                            array(name => 'key', index => 'a.t_key', align => 'center', width => 120, editable => false, edittype => "select", editoptions => array(value => "PAIS:Pais"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'valor', index => 'a.t_value', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.promocondicion_id";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/promocional_detalle_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

                        $GRID_CAPTION = "Detalle Promocional";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                    case "informe_promocional":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/promolog_xml/?fecha_ini=$fecha_hoy&fecha_fin=$fecha_hoy";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Promocional', 'Usuario', 'Valor', 'Valor Promocional', 'Valor Base', 'Estado'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.promolog_id', width => 35, align => 'center', editable => false, search => false),
                            array(name => 'promocional', index => 'b.descripcion', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'usuario', index => 'a.usuario_id', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'valor', index => 'a.valor', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'valorpromocional', index => 'c.valor_promocional', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'valorbase', index => 'c.valor_base', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => false, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.promolog_id";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Listado de Promocionales";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                    case "producto":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/producto2_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Nombre', 'Proveedor', 'Imagen', 'Estado', 'Verificacion', 'ID Externo', 'Mostrar'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.producto_id', width => 35, align => 'center', editable => false, search => false),
                            array(name => 'nombre', index => 'a.descripcion', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'proveedor', index => 'b.descripcion', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'imagen', index => 'a.image_url', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'verificacion', index => 'a.verifica', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'externo_id', index => 'a.externo_id', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'mostrar', index => 'a.mostrar', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "S:Si;N:No"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;S:Si;N:No")),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.producto_id";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/producto_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

                        $GRID_CAPTION = "Productos";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                    case "producto_detalle":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/producto_detalle_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Producto', 'Key', 'Valor'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.productodetalle_id', width => 35, align => 'center', editable => false, search => false),
                            array(name => 'producto', index => 'b.descripcion', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'key', index => 'a.p_key', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'value', index => 'a.p_value', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.productodetalle_id";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/producto_detalle_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

                        $GRID_CAPTION = "Producto Detalle";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;
                    case "proveedor":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/proveedor2_xml/";
                        $GRID_EDITAR = true;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Descripcion', 'Tipo', 'Estado', 'Verificacion', 'Abreviado'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.proveedor_id', width => 35, align => 'center', editable => false, search => false),
                            array(name => 'descripcion', index => 'a.descripcion', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'tipo', index => 'a.tipo', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "PAYMENT:Payment;CASINO:Casino"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;PAYMENT:Payment;CASINO:Casino")),
                            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'verificacion', index => 'a.verifica', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
                            array(name => 'abreviado', index => 'a.abreviado', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.proveedor_id";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/proveedor_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

                        $GRID_CAPTION = "Proveedores";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                }

                $str_GRIDURL = "";

                if (substr($GRID_URL, -1) === "/") {

                    $str_GRIDURL = $GRID_URL . "?tk=" . str_replace("/", "#@@#", encrypt($json->session->usuario . "#" . $json->session->sid));

                } else {

                    $str_GRIDURL = $GRID_URL . "&tk=" . str_replace("/", "#@@#", encrypt($json->session->usuario . "#" . $json->session->sid));

                }

                $str_GRIDEDITURL = "";

                if (substr($GRID_EDITURL, -1) === "/") {

                    $str_GRIDEDITURL = $GRID_EDITURL . "?tk=" . str_replace("/", "#@@#", encrypt($json->session->usuario . "#" . $json->session->sid));

                } else {

                    $str_GRIDEDITURL = $GRID_EDITURL . "&tk=" . str_replace("/", "#@@#", encrypt($json->session->usuario . "#" . $json->session->sid));

                }

                $response = array("code" => 0, "rid" => "$json->rid", "data" => array(
                    "GRID_URL" => $str_GRIDURL,
                    "GRID_DATATYPE" => $GRID_DATATYPE,
                    "GRID_COLNAMES" => $GRID_COLNAMES,
                    "GRID_COLMODEL" => $GRID_COLMODEL,
                    "GRID_ROWNUM" => $GRID_ROWNUM,
                    "GRID_ROWLIST" => $GRID_ROWLIST,
                    "GRID_SORTNAME" => $GRID_SORTNAME,
                    "GRID_SORTORDER" => $GRID_SORTORDER,
                    "GRID_EDITURL" => $str_GRIDEDITURL,
                    "GRID_CAPTION" => $GRID_CAPTION,
                    "GRID_EDITAR" => $GRID_EDITAR,
                    "GRID_ELIMINAR" => $GRID_ELIMINAR,
                    "GRID_AGREGAR" => $GRID_AGREGAR,
                    "GRID_GROUPING" => $GRID_GROUPING,
                    "GRID_GROUPINGVIEW" => $GRID_GROUPINGVIEW,
                    "GRID_SELECT" => $GRID_SELECT,
                    "data_source" => 5,
                ));

                break;

            case "get_filtre":

                $recurso = $json->params->recurso;

                switch (strtolower($recurso)) {

                    case "puntos_venta":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/usuario2_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Login', 'Nombre', 'Agrupador'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'usuario_id', width => 40, hidden => true, search => false, editable => false),
                            array(name => 'login', index => 'login', hidden => true, width => 90, search => false),
                            array(name => 'nombre', index => 'nombre', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'perfil', index => 'perfil', align => 'center', width => 140, editable => false),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "nombre";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Puntos de Venta / Usuarios Online";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['perfil'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "concesionario":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/usuario4_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro', 'Login', 'Nombre', 'Agrupador'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.usuario_id', width => 50, align => 'center', hidden => false, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'login', index => 'login', hidden => true, width => 90, search => false),
                            array(name => 'nombre', index => 'nombre', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'perfil', index => 'perfil', align => 'center', width => 140, editable => false),

                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.nombre";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Concesionarios";
                        $GRID_GROUPINGVIEW = array(

                            groupField => ['perfil'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['desc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "departamento":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/departamento_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Nombre Departamento'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.depto_id', width => 50, align => 'center', hidden => true, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'nombre', index => 'a.depto_nom', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.depto_nom";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Departamentos";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                    case "ciudad":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/ciudad_xml/?depto_id=";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = true;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Id', 'Departamento', 'Nombre Ciudad'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.ciudad_id', width => 50, align => 'center', hidden => false, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'depto_nom', index => 'b.depto_nom', width => 250, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'ciudad_nom', index => 'a.ciudad_nom', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "b.depto_nom,a.ciudad_nom";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Ciudades";
                        $GRID_GROUPINGVIEW = array(
                            groupField => ['depto_nom'],
                            groupColumnShow => [false],
                            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
                            groupCollapse => true,
                            groupOrder => ['asc'],
                        );
                        $GRID_SELECT = [];

                        break;

                    case "pais":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/pais_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro', 'Descripcion'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.pais_id', width => 50, align => 'center', hidden => true, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'pais_nom', index => 'pais_nom', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.pais_nom";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Paises";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                    case "producto":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/producto_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro', 'Descripcion'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.producto_id', width => 50, align => 'center', hidden => true, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'descripcion', index => 'descripcion', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Paises";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                    case "proveedor":

                        $GRID_URL = "https://admin.doradobet.com/restapi/api/proveedor_xml/";
                        $GRID_EDITAR = false;
                        $GRID_ELIMINAR = false;
                        $GRID_AGREGAR = false;
                        $GRID_GROUPING = false;

                        $GRID_DATATYPE = "xml";
                        $GRID_COLNAMES = ['Nro', 'Descripcion'];
                        $GRID_COLMODEL = [
                            array(name => 'id', index => 'a.proveedor_id', width => 50, align => 'center', hidden => true, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                            array(name => 'descripcion', index => 'descripcion', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
                        ];

                        $GRID_ROWNUM = 100;
                        $GRID_ROWLIST = [100, 200, 300];

                        $GRID_SORTNAME = "a.descripcion";
                        $GRID_SORTORDER = "asc";
                        $GRID_EDITURL = "";

                        $GRID_CAPTION = "Paises";
                        $GRID_GROUPINGVIEW = "";
                        $GRID_SELECT = [];

                        break;

                }

                $response = array("code" => 0, "rid" => "$json->rid", "data" => array(
                    "GRID_URL" => $GRID_URL,
                    "GRID_DATATYPE" => $GRID_DATATYPE,
                    "GRID_COLNAMES" => $GRID_COLNAMES,
                    "GRID_COLMODEL" => $GRID_COLMODEL,
                    "GRID_ROWNUM" => $GRID_ROWNUM,
                    "GRID_ROWLIST" => $GRID_ROWLIST,
                    "GRID_SORTNAME" => $GRID_SORTNAME,
                    "GRID_SORTORDER" => $GRID_SORTORDER,
                    "GRID_EDITURL" => $GRID_EDITURL,
                    "GRID_CAPTION" => $GRID_CAPTION,
                    "GRID_EDITAR" => $GRID_EDITAR,
                    "GRID_ELIMINAR" => $GRID_ELIMINAR,
                    "GRID_AGREGAR" => $GRID_AGREGAR,
                    "GRID_GROUPING" => $GRID_GROUPING,
                    "GRID_GROUPINGVIEW" => $GRID_GROUPINGVIEW,
                    "GRID_SELECT" => $GRID_SELECT,
                    "PERMISO" => true,
                    "data_source" => 5,
                ));

                break;

            case "get_user":

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);
                //$UsuarioMandante = new UsuarioMandante(1);
                $Mandante = new Mandante($UsuarioMandante->getMandante());

                $ciudad = "";
                $sexo = "";
                $direcchon = "";
                $fecha_nacimiento = "";
                $cedula = "";
                $celular = "";

                $saldo = $UsuarioMandante->getSaldo();
                $moneda = $UsuarioMandante->getMoneda();
                $paisId = $UsuarioMandante->getPaisId();

                $saldo = $UsuarioMandante->getSaldo();

                $jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"}] ,"groupOp" : "AND"}';
                //$usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);
                //$usuarioMensajes = json_encode($usuarioMensajes);
                //$mensajes_no_leidos = $usuarioMensajes->count[0]->{".count"};

                if ($Mandante->propio == "S") {

                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());

                    $ciudad = $Registro->getCiudadId();
                    $pais = $Usuario->paisId;
                    $pais = 'CO';
                    $sexo = $Registro->getSexo();
                    $direccion = $Registro->getDireccion();
                    $fecha_nacimiento = $Registro->getFechaExped();
                    $cedula = $Registro->getCedula();
                    $celular = $Registro->getCelular();
                    $nombre1 = $Registro->nombre1;
                    $nombre2 = $Registro->nombre2;
                    $apellido1 = $Registro->apellido1;
                    $apellido2 = $Registro->apellido2;

                    $saldo = $Usuario->getBalance();

                }

                $response = array(
                    "code" => 0,
                    "data" => array(
                        "balance" => $saldo,
                        "username" => $Usuario->login,
                        "country_code" => $pais,
                        "city" => $ciudad,
                        "user_id" => $UsuarioMandante->getUsumandanteId(),
                        "first_name" => $nombre1,
                        "countrybirth_id" => array(
                            "id" => 5,
                            "name" => "Colombia",
                            "departaments" => array(
                                "id" => 5,
                                "name" => "Antioquia",
                                "cities" => array(
                                    array(
                                        "id" => "551",
                                        "name" => "medellin"
                                    )
                                )
                            )
                        ),
                        "departmentbirth_id" => array(
                            "id" => 5,
                            "name" => "Antioquia",
                            "cities" => array(
                                array(
                                    "id" => "551",
                                    "name" => "medellin"
                                )
                            )
                        ),
                        "citybirth_id" => array(

                            "id" => "551",
                            "name" => "medellin"

                        ),
                        "second_name" => $nombre2,
                        "sur_name" => $apellido1,
                        "second_sur_name" => $apellido2,
                        "sex" => $sexo,
                        "address" => $direccion,
                        "birth_date" => '1977-07-19',
                        "documentType" => 1,
                        "doc_number" => $cedula,
                        "email" => $Usuario->login,
                        "phone" => $celular,
                        "mobile_phone" => null,
                        "iban" => null,
                        "is_verified" => false,
                        "maximal_daily_bet" => null,
                        "maximal_single_bet" => null,
                        "personal_id" => null,
                        "subscribed_to_news" => false,
                        "loyalty_point" => 0.0,
                        "loyalty_earned_points" => 0.0,
                        "loyalty_exchanged_points" => 0.0,
                        "loyalty_level_id" => null,
                        "casino_maximal_daily_bet" => null,
                        "casino_maximal_single_bet" => null,
                        "zip_code" => null,
                        "currency" => $moneda,
                        "casino_balance" => $saldo,
                        "bonus_balance" => 0.0,
                        "frozen_balance" => 0.0,
                        "bonus_win_balance" => 0.0,
                        "bonus_money" => 0.0,
                        "province" => null,
                        "active_step" => null,
                        "active_step_state" => null,
                        "has_free_bets" => false,
                        "swift_code" => null,
                        "additional_address" => null,
                        "affiliate_id" => null,
                        "btag" => null,
                        "exclude_date" => null,
                        "reg_date" => "2017-08-13",
                        "doc_issue_date" => null,
                        "subscribe_to_email" => true,
                        "subscribe_to_sms" => true,
                        "subscribe_to_bonus" => true,
                        "unread_count" => $mensajes_no_leidos,
                        "incorrect_fields" => null,
                        "loyalty_last_earned_points" => 0.0,
                        "loyalty_point_usage_period" => 0,
                        "loyalty_min_exchange_point" => 0,
                        "loyalty_max_exchange_point" => 0,
                        "active_time_in_casino" => null,
                        "last_login_date" => 1507440476,
                        "name" => $UsuarioMandante->getNombres() . " " . $UsuarioMandante->getApellidos()
                    ),
                );

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);


                $limites = array();

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

                $limitesArray = $UsuarioConfiguracionMySqlDAO->queryByUsuarioId($UsuarioMandante->getUsuarioMandante());
                $limites["t"] = $UsuarioMandante->getUsuarioMandante();

                foreach ($limitesArray as $item) {

                    $tipo = "";

                    switch ($item->getTipo()) {
                        case "EXCTIME":
                            $response["data"]["active_time_in_casino"] = intval($item->getValor());

                            break;


                    }


                }


                break;

            case "user_limits":

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);


                $limites = array();

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

                $limitesArray = $UsuarioConfiguracionMySqlDAO->queryByUsuarioId($UsuarioMandante->getUsuarioMandante());
                $limites["t"] = $UsuarioMandante->getUsuarioMandante();

                foreach ($limitesArray as $item) {

                    $tipo = "";

                    switch ($item->getTipo()) {
                        case "LIMITEDEPOSITOSIMPLE":
                            $tipo = "max_single_deposit";

                            break;

                        case "LIMITEDEPOSITODIARIO":
                            $tipo = "max_day_deposit";

                            break;

                        case "LIMITEDEPOSITOSEMANA":
                            $tipo = "max_week_deposit";

                            break;

                        case "LIMITEDEPOSITOMENSUAL":
                            $tipo = "max_month_deposit";

                            break;

                        case "LIMITEDEPOSITOANUAL":
                            $tipo = "max_year_deposit";

                            break;
                    }


                    $limites[$tipo] = $item->getValor();


                }


                $response = array(
                    "code" => 0,
                    "data" => array(
                        "result" => 0,
                        "result_text" => null,
                        "details" => $limites,
                    ),
                );


                break;
            case "documentUser":
                $type = $json->params->type;
                $document = $json->params->document;
                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $ClientId = $UsuarioMandante->getUsuarioMandante();

                if ($type == "acceptDocument") {
                    $Descarga = new Descarga($document->id);

                    if ($Descarga->estado == "A") {
                        $DocumentoUsuario = new DocumentoUsuario();

                        $DocumentoUsuario->usuarioId = $ClientId;
                        $DocumentoUsuario->documentoId = $Descarga->descargaId;
                        $DocumentoUsuario->version = $Descarga->version;
                        $DocumentoUsuario->estadoAprobacion = "A";

                        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
                        $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
                        $DocumentoUsuarioMySqlDAO->getTransaction()->commit();
                    }
                }
                if ($type == "rejectDocument") {
                    $Descarga = new Descarga($document->id);

                    if ($Descarga->estado == "A") {
                        $DocumentoUsuario = new DocumentoUsuario();

                        $DocumentoUsuario->usuarioId = $ClientId;
                        $DocumentoUsuario->documentoId = $Descarga->descargaId;
                        $DocumentoUsuario->version = $Descarga->version;
                        $DocumentoUsuario->estadoAprobacion = "R";

                        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
                        $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
                        $DocumentoUsuarioMySqlDAO->getTransaction()->commit();
                    }

                }

                $response = array(
                    "code" => 0,
                    "data" => array(
                        "result" => 0,
                        "result_text" => null,
                        "data" => array(),
                    ),
                );

                break;

            case "set_user_limits":
                $type = $json->params->type;
                $limits = $json->params->limits;
                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $ClientId = $UsuarioMandante->getUsuarioMandante();

                if ($type == "deposit") {
                    foreach ($limits as $item) {
                        $tipo = "";

                        switch ($item->period_type) {
                            case 1:
                                $tipo = "LIMITEDEPOSITOSIMPLE";

                                break;

                            case 2:
                                $tipo = "LIMITEDEPOSITODIARIO";

                                break;

                            case 3:
                                $tipo = "LIMITEDEPOSITOSEMANA";

                                break;

                            case 4:
                                $tipo = "LIMITEDEPOSITOMENSUAL";

                                break;

                            case 5:
                                $tipo = "LIMITEDEPOSITOANUAL";

                                break;
                        }

                        try {
                            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, $tipo);

                            $UsuarioConfiguracion->setValor($item->deposit_limit);

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                            //$UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                            $UsuarioLog = new UsuarioLog();
                            $UsuarioLog->setUsuarioId($ClientId);
                            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                            $UsuarioLog->setUsuariosolicitaId($ClientId);
                            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                            $UsuarioLog->setTipo($tipo);
                            $UsuarioLog->setEstado("P");
                            $UsuarioLog->setValorAntes($UsuarioConfiguracion->getValor());
                            $UsuarioLog->setValorDespues($item->deposit_limit);
                            $UsuarioLog->setUsucreaId(0);
                            $UsuarioLog->setUsumodifId(0);
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());
                            $UsuarioLogMySqlDAO->insert($UsuarioLog);

                            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                        } catch (Exception $e) {
                            if ($e->getCode() == 30) {

                                $UsuarioConfiguracion2 = new UsuarioConfiguracion();
                                $UsuarioConfiguracion2->setUsuarioId($ClientId);
                                $UsuarioConfiguracion2->setTipo($tipo);
                                $UsuarioConfiguracion2->setValor($item->deposit_limit);
                                $UsuarioConfiguracion2->setUsucreaId("0");
                                $UsuarioConfiguracion2->setUsumodifId("0");

                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);

                                $UsuarioLog = new UsuarioLog();
                                $UsuarioLog->setUsuarioId($ClientId);
                                $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                                $UsuarioLog->setUsuariosolicitaId($ClientId);
                                $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                                $UsuarioLog->setTipo($tipo);
                                $UsuarioLog->setEstado("A");
                                $UsuarioLog->setValorAntes("");
                                $UsuarioLog->setValorDespues($item->deposit_limit);
                                $UsuarioLog->setUsucreaId(0);
                                $UsuarioLog->setUsumodifId(0);
                                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());
                                $UsuarioLogMySqlDAO->insert($UsuarioLog);


                                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                            } else {
                                throw new Exception($e->getMessage(), $e->getCode());
                            }
                        }
                    }

                }


                $response = array(
                    "code" => 0,
                    "data" => array(
                        "result" => 0,
                        "result_text" => null,
                        "data" => array(),
                    ),
                );

                break;

            case "set_client_self_exclusion":
                $exc_type = $json->params->exc_type;
                $days = $json->params->days;
                $months = $json->params->months;
                $years = $json->params->years;

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $ClientId = $UsuarioMandante->getUsuarioMandante();

                $tipo = "";

                switch ($exc_type) {
                    case 6:
                        $tipo = "EXCTIMEOUT";

                        break;

                    case 2:
                        $tipo = "EXCTOTAL";

                        break;

                }

                if ($tipo != "") {
                    if ($days != "") {
                        $valor = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . ' + ' . $days . ' days'));

                    }
                    if ($months != "") {
                        $valor = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . ' + ' . $months . ' months'));

                    }
                    if ($years != "") {
                        $valor = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . ' + ' . $years . ' years'));

                    }

                    try {
                        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, $tipo);

                        $UsuarioConfiguracion->setValor($valor);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                    } catch (Exception $e) {
                        if ($e->getCode() == 30) {

                            $UsuarioConfiguracion2 = new UsuarioConfiguracion();
                            $UsuarioConfiguracion2->setUsuarioId($ClientId);
                            $UsuarioConfiguracion2->setTipo($tipo);
                            $UsuarioConfiguracion2->setValor($valor);
                            $UsuarioConfiguracion2->setUsucreaId("0");
                            $UsuarioConfiguracion2->setUsumodifId("0");

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
                            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                        } else {
                            throw new Exception($e->getMessage(), $e->getCode());
                        }
                    }
                }


                $response = array(
                    "code" => 0,
                    "data" => array(
                        "result" => 0,
                        "result_text" => null,
                        "data" => array(),
                    ),
                );


                break;

            case "update_user_active_time":

                $active_time = $json->params->active_time;

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $ClientId = $UsuarioMandante->getUsuarioMandante();

                $tipo = "EXCTIME";


                if ($active_time != "") {
                    $valor = $active_time;


                    try {
                        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, $tipo);

                        $UsuarioConfiguracion->setValor($valor);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                    } catch (Exception $e) {
                        if ($e->getCode() == 30) {

                            $UsuarioConfiguracion2 = new UsuarioConfiguracion();
                            $UsuarioConfiguracion2->setUsuarioId($ClientId);
                            $UsuarioConfiguracion2->setTipo($tipo);
                            $UsuarioConfiguracion2->setValor($valor);
                            $UsuarioConfiguracion2->setUsucreaId("0");
                            $UsuarioConfiguracion2->setUsumodifId("0");

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
                            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                        } else {
                            throw new Exception($e->getMessage(), $e->getCode());
                        }
                    }
                }


                $response = array(
                    "code" => 0,
                    "data" => array(
                        "result" => 0,
                        "result_text" => null,
                        "data" => array(),
                    ),
                );


                break;


            case "change_pais":

                $pais_id = $json->params->pais_id;

                $DepartamentoMySqlDAO = new Backend\mysql\DepartamentoMySqlDAO();

                $Departamentos = $DepartamentoMySqlDAO->queryByPaisId($pais_id);

                $PaisMonedaMySqlDAO = new Backend\mysql\PaisMonedaMySqlDAO();

                $PaisMonedas = $PaisMonedaMySqlDAO->queryByPaisId($pais_id);

                $array_departamentos = [];
                $array_monedas = [];

                foreach ($Departamentos as $departamento) {
                    $array_departamento = array(
                        "key" => $departamento->deptoId,
                        "name" => $departamento->deptoNom,
                    );

                    array_push($array_departamentos, $array_departamento);

                }

                $MonedaMySqlDAO = new \Backend\mysql\MonedaMySqlDAO();

                foreach ($PaisMonedas as $paismoneda) {

                    $Moneda = $MonedaMySqlDAO->load($paismoneda->moneda);
                    $array_moneda = array(
                        "key" => $Moneda->moneda,
                        "name" => $Moneda->descripcion,
                    );

                    array_push($array_monedas, $array_moneda);

                }

                $response = array("code" => 0, "data" => array(
                    "provincias" => $array_departamentos,
                    "monedas" => $array_monedas,
                ));

                break;

            case "change_provincia":

                $depto_id = $json->params->provincia_id;

                $CiudadMySqlDAO = new Backend\mysql\CiudadMySqlDAO();

                $Ciudades = $CiudadMySqlDAO->queryByDeptoId($depto_id);

                $array_ciudades = [];

                foreach ($Ciudades as $ciudad) {
                    $array = array(
                        "key" => $ciudad->ciudadCod,
                        "name" => $ciudad->ciudadNom,
                    );

                    array_push($array_ciudades, $array);

                }

                $response = array("code" => 0, "data" => array(
                    "ciudades" => $array_ciudades,
                ));

                break;

            case "change_moneda":

                $moneda = $json->params->moneda;
                $pais = $json->params->pais_id;

                $UsuarioMySqlDAO = new Backend\mysql\UsuarioMySqlDAO();
                $Concesionarios = $UsuarioMySqlDAO->queryForPerfiles("CONCESIONARIO", $pais, $moneda, $json->session->mandante);

                $array_concesionaros = [];

                foreach ($Concesionarios as $concesionario) {
                    $array = array(
                        "key" => $concesionario->usuarioId,
                        "name" => $concesionario->nombre,
                    );

                    array_push($array_concesionaros, $array);

                }

                $response = array("code" => 0, "data" => array(
                    "concesionarios" => $array_concesionaros,
                ));

                break;

            case "change_concesionarios":

                $concesionario_id = $json->params->concesionario_id;

                $UsuarioMySqlDAO = new Backend\mysql\UsuarioMySqlDAO();
                $SubConcesionarios = $UsuarioMySqlDAO->queryForSubconcesionarios($concesionario_id);

                $array_subconcesionaros = [];

                foreach ($SubConcesionarios as $subconcesionario) {
                    $array = array(
                        "key" => $concesionario->usuarioId,
                        "name" => $concesionario->nombre,
                    );

                    array_push($array_subconcesionaros, $array);

                }

                $response = array("code" => 0, "data" => array(
                    "subconcesionarios" => $array_subconcesionaros,
                ));

                break;

            case "get_infouser":

                $user_id = $json->params->user_id;
                $correo = $json->params->correo;

                if ($user_id != "" && $user_id != undefined && $user_id != "undefined") {
                    $Usuario = new Usuario($user_id);
                } elseif ($correo != "" && $correo != undefined && $correo != "undefined") {

                    $UsuarioMysqlDAO = new \Backend\mysql\UsuarioMySqlDAO();

                    $Usuario = $UsuarioMysqlDAO->queryByLogin($correo);
                    $Usuario = $Usuario[0];
                }

                if ($Usuario != null) {

                    $UsuarioEstadisticas = $Usuario->getEstadisticas();

                    $apuesta_prom = number_format($UsuarioEstadisticas[".apuesta_prom"], 2);
                    $tot_tickets = number_format($UsuarioEstadisticas[".tot_tickets"], 2);
                    $tot_premiados = number_format($UsuarioEstadisticas[".tot_premiados"], 2);
                    $tot_apostado = number_format($UsuarioEstadisticas[".tot_apostado"], 2);
                    $tot_premio = number_format($UsuarioEstadisticas[".tot_premio"], 2);
                    $tot_recarga = number_format($UsuarioEstadisticas[".tot_recarga"], 2);
                    $tot_abiertos = number_format($UsuarioEstadisticas[".tot_abiertos"], 2);
                    $ganancias = number_format($tot_premio - $tot_apostado, 2);
                    $porcentaje_utilidad = number_format(($tot_premio - $tot_apostado) / $tot_apostado * 100, 1);
                    $total_reinvertido = number_format($tot_apostado - $tot_recarga, 2);
                    $asertividad = number_format($tot_premiados / $tot_tickets * 100, 1);

                    $Registro = new Registro("", $Usuario->usuarioId);

                    $response = array(
                        "code" => 0,
                        "data" => array(

                            "nro_cliente" => $Usuario->usuarioId,
                            "nombre" => $Registro->getNombre1(),
                            "apellido" => $Registro->getNombre2(),
                            "cedula" => $Registro->getCedula(),
                            "fecha_creacion" => "",
                            "last_login" => "",
                            "celular" => $Registro->getCelular(),
                            "disponible_jugar" => $Usuario->getBalance(),
                            "disponible_retiro" => $Registro->getCreditos(),
                            "pais" => "1",
                            "departamento" => "1",
                            "ciudad" => "1",
                            "estado_especial" => "",
                            "email" => $Usuario->login,
                            "ip" => $Usuario->dirIp,
                            "estado" => $Usuario->estado,
                            "moneda" => $Usuario->moneda,
                            "observ_especial" => "TEST",

                            "apuestas_promedio" => $apuesta_prom,
                            "tickets_premiados" => $tot_premiados,
                            "total_recarga" => $tot_recarga,
                            "total_ganancia" => $ganancias,
                            "total_reinvertido" => $total_reinvertido,
                            "tickets_abiertos" => $tot_abiertos,
                            "tickets_total" => $tot_tickets,
                            "total_apostado" => $tot_apostado,
                            "total_premios" => $tot_premio,
                            "porcentaje_utilidad" => $porcentaje_utilidad,
                            "asertividad_tickets" => $asertividad,

                            "balance" => "",
                            "username" => $Usuario->login,
                            "country_code" => "",
                            "city" => "",
                            "user_id" => "",
                            "first_name" => "",
                            "sur_name" => "",
                            "sex" => "M",
                            "address" => "CALLE",
                            "birth_date" => "",
                            "doc_number" => "",
                            "phone" => "",
                            "mobile_phone" => null,
                            "iban" => null,
                            "is_verified" => false,
                            "maximal_daily_bet" => null,
                            "maximal_single_bet" => null,
                            "personal_id" => null,
                            "subscribed_to_news" => false,
                            "loyalty_point" => 0.0,
                            "loyalty_earned_points" => 0.0,
                            "loyalty_exchanged_points" => 0.0,
                            "loyalty_level_id" => null,
                            "casino_maximal_daily_bet" => null,
                            "casino_maximal_single_bet" => null,
                            "zip_code" => null,
                            "currency" => "",
                            "casino_balance" => "",
                            "bonus_balance" => 0.0,
                            "frozen_balance" => 0.0,
                            "bonus_win_balance" => 0.0,
                            "bonus_money" => 0.0,
                            "province" => null,
                            "active_step" => null,
                            "active_step_state" => null,
                            "has_free_bets" => false,
                            "swift_code" => null,
                            "additional_address" => null,
                            "affiliate_id" => null,
                            "btag" => null,
                            "exclude_date" => null,
                            "reg_date" => "2017-08-13",
                            "doc_issue_date" => null,
                            "subscribe_to_email" => true,
                            "subscribe_to_sms" => true,
                            "subscribe_to_bonus" => true,
                            "unread_count" => 1,
                            "incorrect_fields" => null,
                            "loyalty_last_earned_points" => 0.0,
                            "loyalty_point_usage_period" => 0,
                            "loyalty_min_exchange_point" => 0,
                            "loyalty_max_exchange_point" => 0,
                            "active_time_in_casino" => null,
                            "last_login_date" => 1507440476,
                            "name" => "",
                        ),
                    );
                } else {
                    throw new Exception("No se encontro el usuario", "11");
                }
                break;

            case "get_infouser_admin":

                $user_id = $json->params->user_id;

                if ($user_id != "" && $user_id != undefined && $user_id != "undefined") {
                    $Usuario = new Usuario($user_id);
                }

                if ($Usuario != null) {

                    $UsuarioAdminDetails = $Usuario->getAdminDetails();

                    $fecha_crea = $UsuarioAdminDetails["a.fecha_crea"];
                    $fecha_ult = $UsuarioAdminDetails["a.fecha_ult"];
                    $fecha_modif = $UsuarioAdminDetails["a.fecha_modif"];
                    $perfil_id = $UsuarioAdminDetails["c.perfil_id"];
                    if (stristr($perfil_id, 'PUNTO') or stristr($perfil_id, 'CONCESIONARIO')) {
                        $mostrar_infopunto = "display:block;";
                    }

                    $dir_ip = $UsuarioAdminDetails["a.dir_ip"];
                    if (stristr($perfil_id, 'ADMIN') or stristr($perfil_id, 'SA')) {
                        $dir_ip = "";
                    }

                    $login = $UsuarioAdminDetails["a.login"];
                    $nombre = $UsuarioAdminDetails["a.nombre"];
                    $usuario_modif = $UsuarioAdminDetails[".usuario_modif"];
                    $estado = $UsuarioAdminDetails["a.estado"];
                    $estado_esp = $UsuarioAdminDetails["a.estado_esp"];
                    $retirado = $UsuarioAdminDetails["a.retirado"];
                    $observ = $UsuarioAdminDetails["a.observ"];
                    $intentos = $UsuarioAdminDetails["a.intentos"];
                    $texto_punto = "";
                    if (stristr($perfil_id, 'PUNTO')) {
                        $texto_punto = " PUNTO DE VENTA";
                    } else {
                        if ($perfil_id == 'CONCESIONARIO2') {
                            $texto_punto = " SUBCONCESIONARIO";
                        } else {
                            if ($perfil_id == 'CONCESIONARIO') {
                                $texto_punto = " CONCESIONARIO";
                            }

                        }
                    }
                    $pais_usuario = $UsuarioAdminDetails["a.pais_id"];
                    $moneda_usuario = $UsuarioAdminDetails["a.moneda"];
                    $idioma_usuario = $UsuarioAdminDetails["a.idioma"];
                    $permite_recarga = $UsuarioAdminDetails[".permite_recarga"];
                    $pinagent = $UsuarioAdminDetails[".pinagent"];
                    $recibo_caja = $UsuarioAdminDetails[".recibo_caja"];
                    $bloqueo_ventas = $UsuarioAdminDetails["a.bloqueo_ventas"];
                    $permite_activareg = $UsuarioAdminDetails["a.permite_activareg"];
                    $descripcion = $UsuarioAdminDetails["e.descripcion"];
                    $nombre_contacto = $UsuarioAdminDetails["e.nombre_contacto"];
                    $depto_id = $UsuarioAdminDetails["f.depto_id"];
                    $ciudad_id = $UsuarioAdminDetails["e.ciudad_id"];
                    $direccion = $UsuarioAdminDetails["e.direccion"];
                    $barrio = $UsuarioAdminDetails["e.barrio"];
                    $telefono = $UsuarioAdminDetails["e.telefono"];
                    $email = $UsuarioAdminDetails["e.email"];
                    $periodicidad_id = $UsuarioAdminDetails["e.periodicidad_id"];
                    $premio_max = $UsuarioAdminDetails["g.premio_max"];
                    $premio_max1 = $UsuarioAdminDetails["g.premio_max1"];
                    $premio_max2 = $UsuarioAdminDetails["g.premio_max2"];
                    $premio_max3 = $UsuarioAdminDetails["g.premio_max3"];
                    $cant_lineas = $UsuarioAdminDetails["g.cant_lineas"];
                    $apuesta_min = $UsuarioAdminDetails["g.apuesta_min"];
                    $valor_directo = $UsuarioAdminDetails["g.valor_directo"];
                    $valor_evento = $UsuarioAdminDetails["g.valor_evento"];
                    $valor_diario = $UsuarioAdminDetails["g.valor_diario"];
                    $optimizar_parrilla = $UsuarioAdminDetails["g.optimizar_parrilla"];
                    if ($optimizar_parrilla == "S") {
                        $mostrar_optimizar_parrilla = "block";
                    } else {
                        $mostrar_optimizar_parrilla = "none";
                    }

                    if ($optimizar_parrilla == "S" and stristr($perfil_id, 'PUNTO')) {
                        $mostrar_optimizar_parrilla2 = "block";
                    } else {
                        $mostrar_optimizar_parrilla2 = "none";
                    }

                    $valor_cupo = $UsuarioAdminDetails["e.valor_cupo"];
                    $valor_cupo2 = $UsuarioAdminDetails["e.valor_cupo2"];
                    $porcen_comision = $UsuarioAdminDetails["e.porcen_comision"];
                    $porcen_comision2 = $UsuarioAdminDetails["e.porcen_comision2"];
                    $usupadre_id = $UsuarioAdminDetails["h.usupadre_id"];
                    $usupadre2_id = $UsuarioAdminDetails["h.usupadre2_id"];
                    $nodos = $UsuarioAdminDetails[".nodos"];
                    $texto_op1 = $UsuarioAdminDetails["g.texto_op1"];
                    $texto_op2 = $UsuarioAdminDetails["g.texto_op2"];
                    $url_op2 = $UsuarioAdminDetails["g.url_op2"];
                    $texto_op3 = $UsuarioAdminDetails["g.texto_op3"];
                    $url_op3 = $UsuarioAdminDetails["g.url_op3"];
                    $clasificador1_id = $UsuarioAdminDetails["e.clasificador1_id"];
                    $clasificador2_id = $UsuarioAdminDetails["e.clasificador2_id"];
                    $clasificador3_id = $UsuarioAdminDetails["e.clasificador3_id"];

                    $response = array(
                        "code" => 0,
                        "data" => array(

                            "usuario_id" => $user_id,
                            "fecha_crea" => $fecha_crea,
                            "fecha_ult" => $fecha_ult,
                            "fecha_modif" => $fecha_modif,
                            "perfil_id" => array(
                                "key" => $perfil_id,
                            ),
                            "mostrar_infopunto" => array(
                                "key" => $mostrar_infopunto,
                            ),
                            "dir_ip" => $dir_ip,
                            "login" => $login,
                            "nombre" => $nombre,
                            "usuario_modif" => $usuario_modif,
                            'clave' => '****',
                            "estado" => array(
                                "key" => $estado,
                            ),
                            "estado_esp" => array(
                                "key" => $estado_esp,
                            ),
                            "retirado" => array(
                                "key" => $retirado,
                            ),
                            "observ" => $observ,
                            "intentos" => $intentos,
                            "texto_punto" => $texto_punto,
                            "pais_id" => array(
                                "key" => $pais_usuario,
                            ),
                            "moneda" => array(
                                "key" => $moneda_usuario,
                            ),
                            "idioma" => array(
                                "key" => $idioma_usuario,
                            ),
                            "permite_recarga" => array(
                                "key" => $permite_recarga,
                            ),
                            "pinagent" => array(
                                "key" => $pinagent,
                            ),
                            "recibo_caja" => array(
                                "key" => $recibo_caja,
                            ),
                            "bloqueo_ventas" => array(
                                "key" => $bloqueo_ventas,
                            ),
                            "permite_activareg" => array(
                                "key" => $permite_activareg,
                            ),
                            "descripcion" => $descripcion,
                            "nombre_contacto" => $nombre_contacto,
                            "depto_id" => array(
                                "key" => $depto_id,
                            ),
                            "ciudad_id" => array(
                                "key" => $ciudad_id,
                            ),
                            "direccion" => $direccion,
                            "barrio" => $barrio,
                            "telefono" => $telefono,
                            "email" => $email,
                            "periodicidad_id" => array(
                                "key" => $periodicidad_id,
                            ),
                            "premio_max" => $premio_max,
                            "premio_max1" => $premio_max1,
                            "premio_max2" => $premio_max2,
                            "premio_max3" => $premio_max3,
                            "cant_lineas" => $cant_lineas,
                            "apuesta_min" => $apuesta_min,
                            "valor_directo" => $valor_directo,
                            "valor_evento" => $valor_evento,
                            "valor_diario" => $valor_diario,
                            "optimizar_parrilla" => $optimizar_parrilla,
                            "mostrar_optimizar_parrilla" => $mostrar_optimizar_parrilla,

                            "valor_cupo" => $valor_cupo,
                            "valor_cupo2" => $valor_cupo2,
                            "porcen_comision" => $porcen_comision,
                            "porcen_comision2" => $porcen_comision2,
                            "usupadre_id" => array(
                                "key" => $usupadre_id,
                            ),
                            "usupadre2_id" => array(
                                "key" => $usupadre2_id,
                            ),
                            "nodos" => $nodos,
                            "texto_op1" => $texto_op1,
                            "texto_op2" => $texto_op2,
                            "url_op2" => $url_op2,
                            "texto_op3" => $texto_op3,
                            "url_op3" => $url_op3,
                            "clasificador1_id" => array(
                                "key" => $clasificador1_id,
                            ),
                            "clasificador2_id" => array(
                                "key" => $clasificador2_id,
                            ),
                            "clasificador3_id" => array(
                                "key" => $clasificador3_id,
                            ),

                        ),
                    );
                } else {
                    throw new Exception("No se encontro el usuario", "11");
                }
                break;

            case "get_cuenta_cobro":

                $cuenta_id = $json->params->cuenta_id;

                if ($cuenta_id != "" && $cuenta_id != undefined && $cuenta_id != "undefined") {
                    $CuentaCobro = new \Backend\dto\CuentaCobro($cuenta_id);
                }

                if ($CuentaCobro != null) {

                    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                    $response = array(
                        "code" => 0,
                        "data" => array(

                            "cuenta_id" => $CuentaCobro->getCuentaId(),
                            "fecha_creacion" => $CuentaCobro->getFechaCrea(),
                            "nombre" => $Usuario->nombre,
                            "valor" => $CuentaCobro->getValor(),
                        ),
                    );
                } else {
                    throw new Exception("No se encontro la cuenta de cobro", "12");
                }
                break;

            case "eliminar_cuenta_cobro":

                $cuenta_id = $json->params->cuenta_id;
                $observacion = $json->params->observacion;

                if ($cuenta_id != "" && $cuenta_id != undefined && $cuenta_id != "undefined") {
                    $CuentaCobro = new \Backend\dto\CuentaCobro($cuenta_id);
                }

                if ($CuentaCobro != null) {

                    if ($CuentaCobro->getEstado() == "I") {
                        throw new Exception("No se encontro la cuenta de cobro", "12");
                    }

                    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                    $CuentaCobroEliminar = new \Backend\dto\CuentaCobroEliminada();
                    $CuentaCobroEliminar->setCuentaId($CuentaCobro->getCuentaId());
                    $CuentaCobroEliminar->setValor($CuentaCobro->getValor());
                    $CuentaCobroEliminar->setMandante($CuentaCobro->getMandante());
                    $CuentaCobroEliminar->setObserv($observacion);
                    $CuentaCobroEliminar->setUsuarioId($CuentaCobro->getUsuarioId());
                    $CuentaCobroEliminar->setUsucreaId($UsuarioMandante->getUsuarioMandante());
                    $CuentaCobroEliminar->setFechaCuenta($CuentaCobro->getFechaCrea());
                    $CuentaCobroEliminar->setFechaCrea(date('Y-m-d H:i:s'));

                    $CuentaCobroEliminadaMySqlDAO = new \Backend\mysql\CuentaCobroEliminadaMySqlDAO();

                    $CuentaCobroEliminadaMySqlDAO->insert($CuentaCobroEliminar);

                    $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroEliminadaMySqlDAO->getTransaction());

                    $CuentaCobroMySqlDAO = new \Backend\mysql\CuentaCobroMySqlDAO($CuentaCobroEliminadaMySqlDAO->getTransaction());

                    $CuentaCobroMySqlDAO->delete($CuentaCobro->getCuentaId());

                    $CuentaCobroEliminadaMySqlDAO->getTransaction()->commit();

                    $response = array(
                        "code" => 0,
                        "data" => array(),
                    );
                } else {
                    throw new Exception("No se encontro la cuenta de cobro", "12");
                }
                break;

            case "update_user":

                $clave = $json->params->user_info->password;
                $direccion = $json->params->user_info->address;
                $ciudad = $json->params->user_info->city;
                $celular = $json->params->user_info->phone;

                // $response = array("code" => 0, "rid" => "15063077673908", "data" => array("subid" => "-7031402156054098668", "data" => array("profile" => array("26678955" => array("id" => 26678955, "unique_id" => 26678955, "username" => "danielftg@hotmail.com", "name" => "Daniel Tamayo", "first_name" => "Daniel", "last_name" => "Tamayo", "gender" => "M", "email" => "danielftg@hotmail.com", "phone" => "573012976239", "reg_info_incomplete" => false, "address" => "calle 100 c sur", "reg_date" => "2017-08-13", "birth_date" => "1994-11-20", "doc_number" => "1026152151", "casino_promo" => null, "currency_name" => "USD", "currency_id" => "USD", "balance" => 5.0, "casino_balance" => 5.0, "exclude_date" => null, "bonus_id" => -1, "games" => 0, "super_bet" => -1, "country_code" => "CO", "doc_issued_by" => null, "doc_issue_date" => null, "doc_issue_code" => null, "province" => null, "iban" => null, "active_step" => null, "active_step_state" => null, "subscribed_to_news" => false, "bonus_balance" => 0.0, "frozen_balance" => 0.0, "bonus_win_balance" => 0.0, "city" => "Manizales", "has_free_bets" => false, "loyalty_point" => 0.0, "loyalty_earned_points" => 0.0, "loyalty_exchanged_points" => 0.0, "loyalty_level_id" => null, "affiliate_id" => null, "is_verified" => false, "incorrect_fields" => null, "loyalty_point_usage_period" => 0, "loyalty_min_exchange_point" => 0, "loyalty_max_exchange_point" => 0, "active_time_in_casino" => null, "last_read_message" => null, "unread_count" => 0, "last_login_date" => 1506281782, "swift_code" => null, "bonus_money" => 0.0, "loyalty_last_earned_points" => 0.0)))));

                $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                $UsuarioClave = $Usuario->checkClave($clave);

                $response = array();

                $response['code'] = 0;

                $data = array();

                $data["auth_token"] = "543456ASDASDA";
                $data["result"] = 0;

                $response['data'] = $data;

                break;

            case "update_user_password":

                $clave = $json->params->password;
                $nueva_clave = $json->params->new_password;

                $UsuarioMandante = new UsuarioMandante(1);

                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                $UsuarioClave = $Usuario->checkClave($clave);

                $UsuarioCambioClave = $Usuario->changeClave($nueva_clave);

                $response = array();

                $response['code'] = 0;

                $data = array();

                $data["auth_token"] = "543456ASDASDA";
                $data["result"] = 0;

                $response['data'] = $data;

                break;

            case "create_new_user":

                $usuario_id = $json->params->usuario_id;
                $login = $json->params->login;
                $nombre = $json->params->nombre;
                $clave = $json->params->clave;
                $perfil_id = $json->params->perfil_id;
                $permite_recarga = $json->params->permite_recarga;
                $pinagent = $json->params->pinagent;
                $recibo_caja = $json->params->recibo_caja;
                $bloqueo_ventas = $json->params->bloqueo_ventas;
                $permite_activareg = $json->params->permite_activareg;
                $estado = $json->params->estado;
                $estado_esp = $json->params->estado_esp;
                $observ = $json->params->observ;
                $intentos = $json->params->intentos;
                $pais_usuario = $json->params->pais_id;
                $moneda_usuario = $json->params->moneda;
                $idioma_usuario = $json->params->idioma;
                $descripcion = $json->params->descripcion;
                $nombre_contacto = $json->params->nombre_contacto;
                $ciudad_id = $json->params->ciudad_id;
                $direccion = $json->params->direccion;
                $barrio = $json->params->barrio;
                $telefono = $json->params->telefono;
                $email = $json->params->email;
                $periodicidad_id = $json->params->periodicidad_id;
                $clasificador1_id = $json->params->clasificador1_id;
                $clasificador2_id = $json->params->clasificador2_id;
                $clasificador3_id = $json->params->clasificador3_id;
                $premio_max = $json->params->premio_max;
                $premio_max1 = $json->params->premio_max1;
                $premio_max2 = $json->params->premio_max2;
                $premio_max3 = $json->params->premio_max3;
                $cant_lineas = $json->params->cant_lineas;
                $apuesta_min = $json->params->apuesta_min;
                $valor_directo = $json->params->valor_directo;
                $valor_evento = $json->params->valor_evento;
                $valor_diario = $json->params->valor_diario;
                $optimizar_parrilla = "N";
                $texto_op1 = "";
                $texto_op2 = "";
                $url_op2 = "";
                $texto_op3 = "";
                $url_op3 = "";
                $usupadre_id = $json->params->usupadre_id;
                $usupadre2_id = $json->params->usupadre2_id;
                $valor_cupo = $json->params->valor_cupo;
                $valor_cupo2 = $json->params->valor_cupo2;
                $porcen_comision = $json->params->porcen_comision;
                $porcen_comision2 = $json->params->porcen_comision2;
                $cant_terminal = $json->params->cant_terminal;
                $clave_terminal = $json->params->clave_terminal;

                //Incializa valores por defecto
                $premio_max = 100;
                $premio_max1 = 100;
                $premio_max2 = 100;
                $premio_max3 = 100;
                $cant_lineas = 14;
                $apuesta_min = 2;
                $valor_directo = 100;
                $valor_evento = 0;
                $valor_diario = 0;
                $valor_cupo = 0;
                $valor_cupo2 = 0;
                $porcen_comision = 0;
                $porcen_comision2 = 0;

                //Valida los parametros ingresados
                $seguir = true;
                if (!ValidarCampo($usuario_id, "N", "N", 20)) {
                    $seguir = false;
                }

                if (!ValidarCampo($login, "S", "T", 15)) {
                    $seguir = false;
                }

                if (!ValidarCampo($nombre, "S", "T", 150)) {
                    $seguir = false;
                }

                if (strlen($usuario_id) > 0) {
                    if (!ValidarCampo($clave, "N", "T", 25)) {
                        $seguir = false;
                    }

                } else {
                    if (!ValidarCampo($clave, "S", "T", 25)) {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($perfil_id, "S", "T", 15)) {
                    $seguir = false;
                }

                if (!ValidarCampo($permite_recarga, "S", "T", 1)) {
                    $seguir = false;
                } else {
                    if ($permite_recarga != "S" and $permite_recarga != "N") {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($pinagent, "S", "T", 1)) {
                    $seguir = false;
                } else {
                    if ($pinagent != "S" and $pinagent != "N") {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($recibo_caja, "S", "T", 1)) {
                    $seguir = false;
                } else {
                    if ($recibo_caja != "S" and $recibo_caja != "N") {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($bloqueo_ventas, "S", "T", 1)) {
                    $seguir = false;
                } else {
                    if ($bloqueo_ventas != "S" and $bloqueo_ventas != "N") {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($permite_activareg, "S", "T", 1)) {
                    $seguir = false;
                } else {
                    if ($permite_activareg != "S" and $permite_activareg != "N") {
                        $seguir = false;
                    }

                }

                if (!ValidarCampo($pais_usuario, "S", "N", 20)) {
                    $seguir = false;
                }

                if (!ValidarCampo($moneda_usuario, "S", "T", 3)) {
                    $seguir = false;
                }

                if (!ValidarCampo($idioma_usuario, "S", "T", 2)) {
                    $seguir = false;
                }

                if (!ValidarCampo($estado, "S", "T", 1)) {
                    $seguir = false;
                } else {
                    if ($estado != "A" and $estado != "I" and $estado != "R") {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($estado_esp, "S", "T", 1)) {
                    $seguir = false;
                } else {
                    if ($estado_esp != "A" and $estado_esp != "I") {
                        $seguir = false;
                    }

                }

                if (!ValidarCampo($observ, "N", "T", 150)) {
                    $seguir = false;
                }

                if (!ValidarCampo($intentos, "S", "N", 1)) {
                    $seguir = false;
                }

                if (!ValidarCampo($premio_max, "S", "N", 10)) {
                    $seguir = false;
                } else {
                    if (floatval($premio_max) <= 0) {
                        $seguir = false;
                    }

                }

                if (!ValidarCampo($premio_max1, "S", "N", 10)) {
                    $seguir = false;
                } else {
                    if (floatval($premio_max1) <= 0) {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($premio_max2, "S", "N", 10)) {
                    $seguir = false;
                } else {
                    if (floatval($premio_max2) <= 0) {
                        $seguir = false;
                    }

                }

                if (!ValidarCampo($premio_max3, "S", "N", 10)) {
                    $seguir = false;
                } else {
                    if (floatval($premio_max3) <= 0) {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($valor_directo, "S", "N", 10)) {
                    $seguir = false;
                } else {
                    if (floatval($valor_directo) <= 0) {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($valor_evento, "S", "N", 10)) {
                    $seguir = false;
                } else {
                    if (floatval($valor_evento) < 0) {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($valor_diario, "S", "N", 10)) {
                    $seguir = false;
                } else {
                    if (floatval($valor_diario) < 0) {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($cant_lineas, "S", "N", 2)) {
                    $seguir = false;
                } else {
                    if (floatval($cant_lineas) <= 0) {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($apuesta_min, "S", "N", 10)) {
                    $seguir = false;
                } else {
                    if (floatval($apuesta_min) <= 0) {
                        $seguir = false;
                    }

                }
                if (!ValidarCampo($optimizar_parrilla, "S", "T", 1)) {
                    $seguir = false;
                } else {
                    if ($optimizar_parrilla != "S" and $optimizar_parrilla != "N") {
                        return false;
                    }

                }

                //Validaciones especificas cuando existe optimizaci�n de parrilla
                if ($optimizar_parrilla == "S") {
                    if (!ValidarCampo($texto_op1, "S", "T", 100)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($texto_op2, "S", "T", 100)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($url_op2, "S", "T", 150)) {
                        $seguir = false;
                    }

                    //Valida si es un punto de venta para validar los textos de los nodos
                    if ($perfil_id == "PUNTOVENTA") {
                        if (!ValidarCampo($texto_op3, "S", "T", 100)) {
                            $seguir = false;
                        }

                        if (!ValidarCampo($url_op3, "S", "T", 150)) {
                            $seguir = false;
                        }

                    }
                }

                //Validaciones especificas de informacion de punto de venta o concesionario
                if (stristr($perfil_id, 'PUNTO') or stristr($perfil_id, 'CONCESIONARIO')) {
                    if (!ValidarCampo($descripcion, "S", "T", 150)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($nombre_contacto, "S", "T", 150)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($ciudad_id, "S", "N", 20)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($direccion, "S", "T", 150)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($barrio, "N", "T", 100)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($telefono, "S", "T", 20)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($email, "N", "E", 150)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($periodicidad_id, "S", "N", 20)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($clasificador1_id, "S", "N", 20)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($clasificador2_id, "S", "N", 20)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($clasificador3_id, "S", "N", 20)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($usupadre_id, "N", "N", 20)) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($usupadre2_id, "N", "N", 20)) {
                        $seguir = false;
                    }

                    if (strlen($usupadre2_id) > 0 and strlen($usupadre_id) <= 0) {
                        $seguir = false;
                    }

                    if (!ValidarCampo($valor_cupo, "S", "N", 10)) {
                        $seguir = false;
                    } else {
                        if (floatval($valor_cupo) < 0) {
                            $seguir = false;
                        }

                    }
                    if (!ValidarCampo($valor_cupo2, "S", "N", 10)) {
                        $seguir = false;
                    } else {
                        if (floatval($valor_cupo2) < 0) {
                            $seguir = false;
                        }

                    }
                    if (!ValidarCampo($porcen_comision, "S", "N", 5)) {
                        $seguir = false;
                    } else {
                        if (floatval($porcen_comision) < 0 or floatval($porcen_comision) > 100) {
                            $seguir = false;
                        }

                    }
                    if (!ValidarCampo($porcen_comision2, "S", "N", 5)) {
                        $seguir = false;
                    } else {
                        if (floatval($porcen_comision2) < 0 or floatval($porcen_comision2) > 100) {
                            $seguir = false;
                        }

                    }
                    if (strlen($barrio) <= 0) {
                        $barrio = "NULL";
                    } else {
                        $barrio = "'" . $barrio . "'";
                    }

                }

                //Validaciones especificas cuando hay creaci�n autom�tica de terminales nodo
                $cant_terminal = 0;
                $clave_terminal = "123";
                if (strlen($usuario_id) <= 0 and stristr($perfil_id, 'PUNTO')) {
                    if (!ValidarCampo($cant_terminal, "S", "N", 2)) {
                        $seguir = false;
                    } else {
                        if (floatval($cant_terminal) > 0) {
                            if (!ValidarCampo($clave_terminal, "S", "T", 25)) {
                                $seguir = false;
                            }

                        }
                    }
                }

                if ($seguir) {

                    if (strlen($usuario_id) > 0 && $usuario_id > 0) {

                        $Usuario = new Usuario($usuario_id);

                        $Usuario->usuarioId = $usuario_id;
                        $Usuario->retirado = "N";
                        $Usuario->fechaRetiro = '';
                        $Usuario->horaRetiro = '';
                        $Usuario->usuretiroId = 0;

                        //Verifica cual es el estado
                        $strEstado = ",retirado='N',fecha_retiro='',hora_retiro='',usuretiro_id=0 ";
                        if ($estado == "R") {
                            if ($Usuario->estado == "N") {

                                $Usuario->fechaRetiro = date('Y-m-d');
                                $Usuario->horaRetiro = date('H:i');
                                $Usuario->usuretiroId = $json->session->usuario;
                                $Usuario->estado = "S";

                            }

                            $Usuario->estado = "I";
                            $Usuario->estadoEsp = "I";
                            $Usuario->observ = "Retirado";

                            $estado = "I";
                            $estado_esp = "I";
                            $observ = "Retirado";

                        }
                        $Usuario->login = $login;
                        $Usuario->nombre = $nombre;
                        $Usuario->estado = $estado;
                        $Usuario->estadoEsp = $estado_esp;
                        $Usuario->bloqueoVentas = $bloqueo_ventas;
                        $Usuario->permiteActivareg = $permite_activareg;
                        $Usuario->observ = $observ;
                        $Usuario->estadoAnt = $estado;
                        $Usuario->usucreaId = $json->session->usuario;
                        $Usuario->paisId = $pais_usuario;
                        $Usuario->moneda = $moneda_usuario;
                        $Usuario->idioma = $idioma_usuario;
                        $Usuario->mandante = $json->session->mandante;

                        $UsuarioMySqlDAO = new Backend\mysql\UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);

                        $Transaccion = $UsuarioMySqlDAO->getTransaction();

                        $UsuarioConfig = new UsuarioConfig($usuario_id);
                        $UsuarioConfig->permiteRecarga = $permite_recarga;
                        $UsuarioConfig->pinagent = $pinagent;
                        $UsuarioConfig->reciboCaja = $recibo_caja;
                        $UsuarioConfig->mandante = $json->session->mandante;

                        $UsuarioConfigMySqlDAO = new \Backend\mysql\UsuarioConfigMySqlDAO($Transaccion);
                        $UsuarioConfigMySqlDAO->update($UsuarioConfig);

                        if (stristr($perfil_id, 'PUNTO') or stristr($perfil_id, 'CONCESIONARIO')) {

                            $PuntoVenta = new PuntoVenta("", $usuario_id);
                            $PuntoVenta->descripcion = $descripcion;
                            $PuntoVenta->nombreContacto = $nombre_contacto;
                            $PuntoVenta->ciudadId = $ciudad_id;
                            $PuntoVenta->direccion = $direccion;
                            $PuntoVenta->barrio = $barrio;
                            $PuntoVenta->telefono = $telefono;
                            $PuntoVenta->email = $email;
                            $PuntoVenta->periodicidadId = $periodicidad_id;
                            $PuntoVenta->clasificador1Id = $clasificador1_id;
                            $PuntoVenta->clasificador2Id = $clasificador2_id;
                            $PuntoVenta->clasificador3Id = $clasificador3_id;
                            $PuntoVenta->valorCupo = $valor_cupo;
                            $PuntoVenta->valorCupo2 = $valor_cupo2;
                            $PuntoVenta->porcenComision = $porcen_comision;
                            $PuntoVenta->porcenComision2 = $porcen_comision2;
                            $PuntoVenta->estado = $estado;
                            $PuntoVenta->usuarioId = $numero;
                            $PuntoVenta->mandante = $json->session->mandante;

                            $PuntoVentaMySqlDAO = new \Backend\mysql\PuntoVentaMySqlDAO($Transaccion);
                            $PuntoVentaMySqlDAO->update($PuntoVenta);

                        }

                        //Verifica si es un punto de venta
                        if (stristr($perfil_id, 'PUNTO')) {

                            //Valida si hay un concesionario seleccionado
                            if (strlen($usupadre_id) > 0) {
                                //Valida subconcesionario
                                if (strlen($usupadre2_id) <= 0) {
                                    $usupadre2_id = 0;
                                }

                                try {

                                    $Concesionario = new \Backend\dto\Concesionario($usuario_id);
                                    $Concesionario->usupadreId = $usupadre_id;
                                    $Concesionario->usupadre2Id = $usupadre2_id;
                                    $Concesionario->usuhijoId = $usuario_id;
                                    $Concesionario->mandante = $json->session->mandante;

                                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                                    $ConcesionarioMySqlDAO->update($Concesionario);

                                } catch (Exception $e) {
                                    $Concesionario = new \Backend\dto\Concesionario();
                                    $Concesionario->usupadreId = $usupadre_id;
                                    $Concesionario->usupadre2Id = $usupadre2_id;
                                    $Concesionario->usuhijoId = $usuario_id;
                                    $Concesionario->mandante = $json->session->mandante;

                                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                                    $ConcesionarioMySqlDAO->insert($Concesionario);
                                }

                            } else {
                                try {

                                    $Concesionario = new \Backend\dto\Concesionario($usuario_id);
                                    $Concesionario->usupadreId = $usupadre_id;
                                    $Concesionario->usupadre2Id = $usupadre2_id;
                                    $Concesionario->usuhijoId = $usuario_id;
                                    $Concesionario->mandante = $json->session->mandante;

                                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                                    $ConcesionarioMySqlDAO->delete($Concesionario);

                                } catch (Exception $e) {

                                }

                            }
                        }

                        //Verifica si es un subconcesionario
                        if ($perfil_id == 'CONCESIONARIO2') {
                            //Valida si hay un concesionario seleccionado
                            if (strlen($usupadre_id) > 0) {
                                //Valida subconcesionario
                                if (strlen($usupadre2_id) <= 0) {
                                    $usupadre2_id = 0;
                                }

                                try {

                                    $Concesionario = new \Backend\dto\Concesionario($usuario_id);
                                    $Concesionario->usupadreId = $usupadre_id;
                                    $Concesionario->usupadre2Id = $usupadre2_id;
                                    $Concesionario->usuhijoId = $usuario_id;
                                    $Concesionario->mandante = $json->session->mandante;

                                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                                    $ConcesionarioMySqlDAO->update($Concesionario);

                                } catch (Exception $e) {
                                    $Concesionario = new \Backend\dto\Concesionario();
                                    $Concesionario->usupadreId = $usupadre_id;
                                    $Concesionario->usupadre2Id = $usupadre2_id;
                                    $Concesionario->usuhijoId = $usuario_id;
                                    $Concesionario->mandante = $json->session->mandante;

                                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                                    $ConcesionarioMySqlDAO->insert($Concesionario);
                                }

                            } else {

                                try {

                                    $Concesionario = new \Backend\dto\Concesionario($usuario_id);
                                    $Concesionario->usupadreId = $usupadre_id;
                                    $Concesionario->usupadre2Id = $usupadre2_id;
                                    $Concesionario->usuhijoId = $usuario_id;
                                    $Concesionario->mandante = $json->session->mandante;

                                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                                    $ConcesionarioMySqlDAO->delete($Concesionario);

                                } catch (Exception $e) {

                                }

                            }
                        }

                        $Transaccion->commit();

                        //Verifica si fue pasada la clave para cambiarla
                        $strClave = "";
                        if (strlen($clave) > 3 and $clave != "****") {

                            $Usuario2 = new Usuario($usuario_id);
                            $UsuarioCambioClave = $Usuario2->changeClave($clave);

                        }

                        $response = array();

                        $response['code'] = 0;

                        $data = array();

                        $data["auth_token"] = "543456ASDASDA";
                        $data["result"] = 0;

                        $response['data'] = $data;

                    } else {
                        $Usuario = new Usuario();
                        $Usuario->login = $login;
                        $Usuario->nombre = $nombre;
                        $Usuario->estado = $estado;
                        $Usuario->estadoEsp = $estado_esp;
                        $Usuario->bloqueoVentas = $bloqueo_ventas;
                        $Usuario->permiteActivareg = $permite_activareg;
                        $Usuario->observ = $observ;
                        $Usuario->estadoAnt = $estado;
                        $Usuario->usucreaId = $json->session->usuario;
                        $Usuario->paisId = $pais_usuario;
                        $Usuario->moneda = $moneda_usuario;
                        $Usuario->idioma = $idioma_usuario;
                        $Usuario->mandante = $json->session->mandante;

                        if ($Usuario->exitsLogin()) {

                            $seguir = false;
                        }

                        if ($seguir) {

                            $Consecutivo = new Consecutivo("", "USU", "");

                            $numero = $Consecutivo->getNumero();
                            $Consecutivo->setNumero($numero + 1);

                            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();
                            $ConsecutivoMySqlDAO->update($Consecutivo);
                            $ConsecutivoMySqlDAO->getTransaction()->commit();

                            $Usuario->usuarioId = $numero;

                            $UsuarioMySqlDAO = new Backend\mysql\UsuarioMySqlDAO();
                            $UsuarioMySqlDAO->insert($Usuario);

                            $Transaccion = $UsuarioMySqlDAO->getTransaction();

                            $UsuarioConfig = new UsuarioConfig();
                            $UsuarioConfig->usuarioId = $numero;
                            $UsuarioConfig->permiteRecarga = $permite_recarga;
                            $UsuarioConfig->pinagent = $pinagent;
                            $UsuarioConfig->reciboCaja = $recibo_caja;
                            $UsuarioConfig->mandante = $json->session->mandante;

                            $UsuarioConfigMySqlDAO = new \Backend\mysql\UsuarioConfigMySqlDAO($Transaccion);
                            $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

                            $UsuarioPremiomax = new \Backend\dto\UsuarioPremiomax();
                            $UsuarioPremiomax->usuarioId = $numero;
                            $UsuarioPremiomax->premioMax = $premio_max;
                            $UsuarioPremiomax->premioMax1 = $premio_max1;
                            $UsuarioPremiomax->premioMax2 = $premio_max2;
                            $UsuarioPremiomax->premioMax3 = $premio_max3;
                            $UsuarioPremiomax->cantLineas = $cant_lineas;
                            $UsuarioPremiomax->apuestaMin = $apuesta_min;
                            $UsuarioPremiomax->valorDirecto = $valor_directo;
                            $UsuarioPremiomax->valorEvento = $valor_evento;
                            $UsuarioPremiomax->valorDiario = $valor_diario;
                            $UsuarioPremiomax->optimizarParrilla = $optimizar_parrilla;
                            $UsuarioPremiomax->textoOp1 = $texto_op1;
                            $UsuarioPremiomax->textoOp2 = $texto_op2;
                            $UsuarioPremiomax->textoOp3 = $texto_op3;
                            $UsuarioPremiomax->urlOp2 = $url_op2;
                            $UsuarioPremiomax->fechaModif = date('Y-m-d H:i:s');
                            $UsuarioPremiomax->mandante = $json->session->mandante;
                            $UsuarioPremiomax->usumodifId = $json->session->usuario;

                            $UsuarioPremiomaxMySqlDAO = new \Backend\mysql\UsuarioPremiomaxMySqlDAO($Transaccion);
                            $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

                            $UsuarioPerfil = new \Backend\dto\UsuarioPerfil();
                            $UsuarioPerfil->usuarioId = $numero;
                            $UsuarioPerfil->perfilId = $perfil_id;
                            $UsuarioPerfil->mandante = $json->session->mandante;

                            $UsuarioPerfilMySqlDAO = new \Backend\mysql\UsuarioPerfilMySqlDAO($Transaccion);
                            $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

                            //Inserta la informaci�n de punto de venta si aplica el perfil seleccionado
                            if (stristr($perfil_id, 'PUNTO') or stristr($perfil_id, 'CONCESIONARIO')) {

                                $PuntoVenta = new PuntoVenta();
                                $PuntoVenta->descripcion = $descripcion;
                                $PuntoVenta->nombreContacto = $nombre_contacto;
                                $PuntoVenta->ciudadId = $ciudad_id;
                                $PuntoVenta->direccion = $direccion;
                                $PuntoVenta->barrio = $barrio;
                                $PuntoVenta->telefono = $telefono;
                                $PuntoVenta->email = $email;
                                $PuntoVenta->periodicidadId = $periodicidad_id;
                                $PuntoVenta->clasificador1Id = $clasificador1_id;
                                $PuntoVenta->clasificador2Id = $clasificador2_id;
                                $PuntoVenta->clasificador3Id = $clasificador3_id;
                                $PuntoVenta->valorCupo = $valor_cupo;
                                $PuntoVenta->valorCupo2 = $valor_cupo2;
                                $PuntoVenta->porcenComision = $porcen_comision;
                                $PuntoVenta->porcenComision2 = $porcen_comision2;
                                $PuntoVenta->estado = $estado;
                                $PuntoVenta->usuarioId = $numero;
                                $PuntoVenta->mandante = $json->session->mandante;

                                $PuntoVentaMySqlDAO = new \Backend\mysql\PuntoVentaMySqlDAO($Transaccion);
                                $PuntoVentaMySqlDAO->insert($PuntoVenta);

                            }

                            //Verifica si es un punto de venta y fue seleccionado alg�n concesionario para proceder a guardarlo
                            if ((stristr($perfil_id, 'PUNTO') or $perfil_id == "CONCESIONARIO2") and strlen($usupadre_id) > 0) {
                                //Valida subconcesionario
                                if (strlen($usupadre2_id) <= 0) {
                                    $usupadre2_id = 0;
                                }

                                $Concesionario = new \Backend\dto\Concesionario();
                                $Concesionario->usupadreId = $usupadre_id;
                                $Concesionario->usupadre2Id = $usupadre2_id;
                                $Concesionario->usuhijoId = $numero;
                                $Concesionario->mandante = $json->session->mandante;

                                $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                                $ConcesionarioMySqlDAO->insert($Concesionario);

                            }

                            $Transaccion->commit();

                            $Usuario2 = new Usuario($numero);

                            $UsuarioCambioClave = $Usuario2->changeClave($clave);

                            $response = array();

                            $response['code'] = 0;

                            $data = array();

                            $data["auth_token"] = "543456ASDASDA";
                            $data["result"] = 0;

                            $response['data'] = $data;

                        } else {
                            $response = array();

                            $response['code'] = -1;

                            $data = array();

                            $data["auth_token"] = "543456ASDASDA";
                            $data["login"] = $login;
                            $data["result"] = -1;

                            $response['data'] = $data;
                        }

                    }
                } else {
                    $response = array();

                    $response['code'] = -1;

                    $data = array();

                    $data["auth_token"] = "543456ASDASDA";
                    $data["message"] = "2";
                    $data["result"] = -1;

                    $response['data'] = $data;
                }
                break;

            case "betshop-livecasino-login":

                $params = $json->params;

                $auth_token = $params->token;

                if ($auth_token == "") {

                    throw new Exception("Token vacio", "01");

                }

                $cumple = true;

                $Proveedor = new Proveedor("", "IES");


                $ProdMandanteTipo = new ProdMandanteTipo('CASINO', '0');
                if ($ProdMandanteTipo->estado == "I") {
                    $cumple = false;

                } elseif ($ProdMandanteTipo->estado == "A") {

                    $UsuarioToken = new UsuarioToken($auth_token, $Proveedor->getProveedorId());

                } else {

                    $UsuarioToken = new UsuarioToken($auth_token, $Proveedor->getProveedorId());

                    if ($UsuarioToken->estado != "NR") {
                        $cumple = false;
                    }

                }


                if ($cumple) {

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId(), "");

                    $saldo = $UsuarioMandante->getSaldo();
                    $moneda = $UsuarioMandante->getMoneda();
                    $paisId = $UsuarioMandante->getPaisId();

                    $UsuarioToken->setRequestId($json->session->sid);

                    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();

                    $response = array();

                    $response['code'] = 0;

                    $data = array();
                    $partner = array();
                    $partner_id = array();

                    $min_bet_stakes = array();

                    $partner_id['partner_id'] = $json->session->mandante;
                    $partner_id['currency'] = $moneda;
                    $partner_id['is_cashout_live'] = 0;
                    $partner_id['is_cashout_prematch'] = 0;
                    $partner_id['cashout_percetage'] = 0;
                    $partner_id['maximum_odd_for_cashout'] = 0;
                    $partner_id['is_counter_offer_available'] = 0;
                    $partner_id['sports_book_profile_ids'] = [1, 2, 5];
                    $partner_id['odds_raised_percent'] = 0;
                    $partner_id['minimum_offer_amount'] = 0;
                    $partner_id['minimum_offer_amount'] = 0;

                    $min_bet_stakes[$moneda] = 0.1;

                    $partner_id['user_password_min_length'] = 6;
                    $partner_id['id'] = $json->session->mandante;

                    $partner_id['min_bet_stakes'] = $min_bet_stakes;

                    $partner[$json->session->mandante] = $partner_id;

                    //$data["partner"] = $partner;

                    //$data["usuario"] = $UsuarioToken->getUsuarioId();

                    $response["data"] = $data;

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;

                    $response["data"] = array(
                        "token" => $UsuarioToken->getToken()
                        //"user_id" => $UsuarioToken->getUsuarioId(),
                    );

                } else {

                    throw new Exception("Restringido", "01");

                }

                break;

            case "betshop-livecasino-credit":

                $params = $json->params;

                $auth_token = $params->token;

                $amount = $params->amount;
                $externalId = $params->externalId . $json->rid;

                $data = array(
                    "amount" => $amount,
                    "externalId" => $externalId,
                    "token" => $auth_token
                );


                if ($auth_token == "") {

                    throw new Exception("Token vacio", "01");

                }

                $IES = new IES($auth_token);

                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;

                $response["data"] = $IES->Credit($amount, $externalId, json_encode($data));


                break;

            case "betshop-livecasino-debit":

                $params = $json->params;

                $auth_token = $params->token;

                $amount = $params->amount;
                $externalId = $params->externalId . $json->rid;

                $data = array(
                    "amount" => $amount,
                    "externalId" => $externalId,
                    "token" => $auth_token
                );


                if ($auth_token == "") {

                    throw new Exception("Token vacio", "01");

                }

                $IES = new IES($auth_token);

                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;

                $response["data"] = $IES->Debit($amount, $externalId, json_encode($data));


                break;

            case "betshop-livecasino-getBalance":

                $params = $json->params;

                $auth_token = $params->token;

                $amount = $params->amount;
                $externalId = $params->externalId . $json->rid;

                $data = array(
                    "amount" => $amount,
                    "externalId" => $externalId,
                    "token" => $auth_token
                );


                if ($auth_token == "") {

                    throw new Exception("Token vacio", "01");

                }

                $IES = new IES($auth_token);

                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;

                $response["data"] = $IES->Auth();


                break;


        }


    } catch (Exception $e) {

        if ($e->getCode() == 50) {
            $ClientId = $json->params->username;

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Usuario = $UsuarioMySqlDAO->queryByLogin($ClientId);
            $Usuario = $Usuario[0];
            if ($Usuario != "") {
                $UsuarioLog = new UsuarioLog();

                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                $UsuarioLog->setUsuariosolicitaId(0);
                $UsuarioLog->setUsuariosolicitaIp('');
                $UsuarioLog->setTipo("LOGIN");
                $UsuarioLog->setEstado("F");
                $UsuarioLog->setValorAntes($json->session->usuarioip);
                $UsuarioLog->setValorDespues($json->session->usuarioip);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

                $UsuarioLogMySqlDAO->getTransaction()->commit();

            }
        }


        $response = array();

        $response['code'] = 12;
        $response['msg'] = "2";
        $response['error_code'] = $e->getCode();
        $response['error_msj'] = $e->getMessage();


    }

    $response["rid"] = $json->rid;

    return (json_encode($response));

}

/**
 * Validar campo de seguridad
 *
 *
 * @param String $string string
 * @param String $espacios espacios
 *
 * @return boolaen $ resultado de la validación
 * @throws Exception si hay algo inusual
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function validarCampoSecurity($string, $espacios)
{

    if ($espacios) {
        if (strpos($string, ' ') !== false) {
            throw new Exception("Inusual Detected", "11");

        }
    }

    return DepurarCaracteres($string);
}


/**
 * Depurar caracteres de una cadena de texto
 *
 *
 * @param String $texto_depurar texto_depurar
 *
 * @return String $texto_depurar texto_depurar
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function DepurarCaracteres($texto_depurar)
{

    $texto_depurar = str_replace("'", "", $texto_depurar);
    $texto_depurar = str_replace('"', "", $texto_depurar);
    $texto_depurar = str_replace(">", "", $texto_depurar);
    $texto_depurar = str_replace("<", "", $texto_depurar);
    $texto_depurar = str_replace("[", "", $texto_depurar);
    $texto_depurar = str_replace("]", "", $texto_depurar);
    $texto_depurar = str_replace("{", "", $texto_depurar);
    $texto_depurar = str_replace("}", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("`", "", $texto_depurar);
    $texto_depurar = str_replace("|", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("%", "", $texto_depurar);
    $texto_depurar = str_replace("&", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("~", "", $texto_depurar);
    $texto_depurar = str_replace("+", "", $texto_depurar);
    $texto_depurar = str_replace("^", "", $texto_depurar);
    $texto_depurar = str_replace("/", "", $texto_depurar);
    //$texto_retornar = addslashes($texto_depurar);

    $c = null;
    return $texto_depurar;
}


/**
 * Encriptar una cadena con el método aes-256-cbc
 *
 *
 * @param String $string string
 *
 * @return String $encrypted cadena encriptada
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */function encrypt($string)
{

    $key = 'zsdfdsartw4saerR'; //Aquí pon lo que quieras y guárdalo en algún sitio dónde solo TU tengas acceso.

    $method = "aes-256-cbc";

    $encrypted = openssl_encrypt($string, $method, $key);

    return $encrypted;
}

/**
 * Desencriptar una cadena con el método aes-256-cbc
 *
 *
 * @param String $string string
 *
 * @return String $decrypted cadena desencriptada
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function decrypt($string)
{

    $key = 'zsdfdsartw4saerR'; //Aquí pon lo que quieras y guárdalo en algún sitio dónde solo TU tengas acceso.
    $method = "aes-256-cbc";

    $decrypted = openssl_decrypt($string, $method, $key);

    return $decrypted;

}

/**
 * Validar campo
 *
 *
 * @param String $valor valor
 * @param String $obligatorio obligatorio
 * @param String $tipo_dato tipo_dato
 * @param int $longitud longitud
 *
 * @return boolean $ resultado de la validación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function ValidarCampo($valor, $obligatorio, $tipo_dato, $longitud)
{
    //Pregunta si el campo es obligatorio
    if ($obligatorio == "S") {
        //Valida que el campo contenga alg�n valor y que su tama�o no sobrepase el permitido
        if (strlen($valor) <= 0 or strlen($valor) > $longitud) {
            return false;
        }

        //Valida el tipo de campo
        switch ($tipo_dato) {
            case "N": //Tipo n�mero
                if (!is_numeric($valor)) {
                    return false;
                }

                break;
            case "E": //Tipo email
                if (!filter_var(filter_var($valor, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

                break;
            case "F": //Tipo fecha
                if (strlen($valor) != "10") {
                    return false;
                } else {
                    if (!validateDate($valor)) {
                        return false;
                    }

                }
                break;
            case "H": //Tipo hora
                if (strlen($valor) != 5) {
                    return false;
                } else {
                    $separado = split("[:]", $valor);
                    if ((floatval($separado[0]) < 0 and floatval($separado[0]) > 23) or (floatval($separado[1]) < 0 and floatval($separado[1]) > 59)) {
                        return false;
                    }

                }
                break;
        }
    } else {
        //Depura valor
        $valor = str_replace("_empty", "", $valor);

        //No es obligatorio pero contiene alg�n valor
        if (strlen($valor) > 0) {
            //Valida que no sobrepase la longitud maxima del campo
            if (strlen($valor) > $longitud) {
                return false;
            }

            //Valida el tipo de campo
            switch ($tipo_dato) {
                case "N": //Tipo n�mero
                    if (!is_numeric($valor)) {
                        return false;
                    }

                    break;
                case "E": //Tipo email
                    if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                        return false;
                    }

                    break;
                case "F": //Tipo fecha
                    if (strlen($valor) != 10) {
                        return false;
                    } else {
                        if (!validateDate($valor)) {
                            return false;
                        }

                    }
                    break;
                case "H": //Tipo fecha
                    if (strlen($valor) != 5) {
                        return false;
                    } else {
                        $separado = split("[:]", $valor);
                        if ((floatval($separado[0]) < 0 and floatval($separado[0]) > 23) or (floatval($separado[1]) < 0 and floatval($separado[1]) > 59)) {
                            return false;
                        }

                    }
                    break;
            }
        }
    }

    //Retorna campo OK
    return true;
}

/**
 * Validar fecha
 *
 *
 * @param String $date date
 *
 * @return boolean $ resultado de la validación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function validateDate($date)
{
    list($year, $month, $day) = explode('-', $date);
    if (is_numeric($year) && is_numeric($month) && is_numeric($day)) {
        return checkdate($month, $day, $year);
    }

    return false;
}

/**
 * Obtener la ip del cliente
 *
 *
 * @return String $ipaddress ip del cliente
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Traducir mercado
 *
 *
 * @param String $mercado mercado
 * @param String $idioma idioma
 *
 * @return String $mercado mercado
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function traduccionMercado($mercado, $idioma)
{
    switch (strtolower($mercado)) {
        case "draw":

            return "Empate";

            break;

        case "hd":

            return "1X";

            break;

        case "ha":

            return "12";

            break;

        case "da":

            return "X2";

            break;

        default:
            if (strpos($mercado, 'Under') !== false) {
                return str_replace("Under ", "Menos ", $mercado);

            }

            if (strpos($mercado, 'Over') !== false) {
                return str_replace("Over ", "Mas ", $mercado);

            }

            return $mercado;
    }
}


/**
 * Generar clave para ticket
 *
 *
 * @param int $length length
 *
 * @return String $randomString clave para ticket
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function GenerarClaveTicket($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


/**
 * Generar clave para ticket
 *
 *
 * @param int $length length
 *
 * @return String $randomString clave para ticket
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function GenerarClaveTicket2($length)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


$msg='{"command":"get","params":{"source":"betting","what":{"sport":["id","alias"],"competition":["id","name"],"region":["id"],"game":[],"market":[],"event":[]},"where":{"game":{"id":8}},"subscribe":true},"rid":"15257004066987"}';
$object = json_decode($msg, true);
$object = json_encode($object);

$object = json_decode($object);

$response = resolverAPI($object);



