<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
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
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioSessionMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/restore_login
 *
 * Restaura la sesión del usuario
 *
 * @param boolean $inApp : Si el usuario viene de la App
 * @param string $auth_token : Token de autentificación del usuario
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor
 *  - *rid* (string): Contiene el mensaje de error.
 *  - *test* (string): Devuelve el string test
 *  - *data* (array): Devuelve la sesion del usuario
 *
 * @throws Exception Restringido
 * @throws Exception No existe Token
 * @throws Exception Token vacio
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* verifica si 'in_app' es verdadero y asigna valor 1. */
$params = $json->params;
$inApp = $json->params->in_app;
if ($inApp == true) {
    $inApp = 1;
}

$auth_token = $params->auth_token;


/* valida un token de autenticación y lanza una excepción si está vacío. */
$auth_token = validarCampoSecurity($auth_token, true);

if ($auth_token == "") {

    throw new Exception("Token vacio", "01");

}

/* Se establece una variable booleana para controlar la continuación de un proceso. */
$seguirTokenFUN = true;

if ($auth_token == "FUN") {


    /* Inicializa variables y estructura de respuesta en un script PHP. */
    $seguirTokenFUN = false;

    $response = array();

    $response['code'] = 0;

    $data = array();

    /* inicializa arrays para almacenar socios y apuestas mínimas. */
    $partner = array();
    $partner_id = array();

    $min_bet_stakes = array();

    $partner_id['partner_id'] = 0;

    /* Se configura un array con propiedades relacionadas a un socio en transacciones financieras. */
    $partner_id['currency'] = 'USD';
    $partner_id['is_cashout_live'] = 0;
    $partner_id['is_cashout_prematch'] = 0;
    $partner_id['cashout_percetage'] = 0;
    $partner_id['maximum_odd_for_cashout'] = 0;
    $partner_id['is_counter_offer_available'] = 0;

    /* Inicializa parámetros de apuestas y perfiles para un socio en un sistema deportivo. */
    $partner_id['sports_book_profile_ids'] = [1, 2, 5];
    $partner_id['odds_raised_percent'] = 0;
    $partner_id['minimum_offer_amount'] = 0;
    $partner_id['minimum_offer_amount'] = 0;

    $min_bet_stakes[$moneda] = 0.1;


    /* inicializa configuraciones para un socio, incluyendo longitud mínima de contraseña y apuestas. */
    $partner_id['user_password_min_length'] = 6;
    $partner_id['id'] = 0;

    $partner_id['min_bet_stakes'] = $min_bet_stakes;

    $partner[0] = $partner_id;


    /* asigna datos a un array y luego lo inicializa como vacío. */
    $data["partner"] = $partner;

    $data["usuario"] = 0;

    $response["data"] = $data;

    $response = array();

    /* crea un arreglo de respuesta con datos de autenticación y usuario. */
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["test"] = "test";

    $response["data"] = array(
        "auth_token" => 'FUN',
        "user_id" => 0,
        "id_platform" => 0,
        "in_app" => $inApp
    );


}

if ($seguirTokenFUN) {


    /* inicializa configuraciones y valida un usuario mandante según un token. */
    $cumple = true;

    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $UsuarioToken = new UsuarioToken($auth_token, '0');

    if ($UsuarioMandanteSite == '') {
        $UsuarioMandanteSite = new UsuarioMandante($UsuarioToken->usuarioId);
    }


    /* Calcula la diferencia de tiempo y valida condiciones para lanzar una excepción. */
    $diff = abs(time() - strtotime($UsuarioToken->getFechaCrea()));
    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

    if (!in_array($UsuarioMandanteSite->mandante, array(11))) {

        if (floatval($days) >= 1 && (in_array($UsuarioMandanteSite->mandante, array('0', '6', '8', '2', '12', 3, 4, 5, 6, 7)) || true) && !in_array($UsuarioMandanteSite->usuarioMandante, array(17884, 242068, 255499, 255528, 255547, 255584, 242055, 242048))) {
            throw new Exception("No existe Token", "21");
        }
    }


    /* Verifica el estado del usuario y crea un nuevo objeto UsuarioMandante. */
    if ($UsuarioToken->estado != "NR") {
        if ($ConfigurationEnvironment->isDevelopment()) {
            //$cumple = false;
        }
    }

    $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId(), "");


    /* Se intenta crear un objeto, manejando excepciones si ocurre un error. */
    try {
        $ProdMandanteTipo = new ProdMandanteTipo('CASINO', $UsuarioMandante->mandante);

    } catch (Exception $e) {

    }


    /* verifica el estado de un producto y ajusta 'cumple' según el entorno. */
    if ($ProdMandanteTipo->estado == "I") {
        if ($ConfigurationEnvironment->isDevelopment()) {
            $cumple = false;
        }

    } elseif ($ProdMandanteTipo->estado == "A") {

        /* if($ConfigurationEnvironment->isDevelopment()){

         $Mandante = new Mandante($json->session->mandante);

         if($Mandante->propio == "N"){

             $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
             $data = array(
                 //"site" => $ProdMandanteTipo->siteId,
                 "sign" => $ProdMandanteTipo->siteKey,
                 "token" => $auth_token
             );

             $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/authenticate", "POST", $data);

             $result = array(
                 "error" => 'false',
                 "player" => array(
                     "userid" => 1,
                     "balance" => 1,
                     "name" => 1,
                     "country" => '173',
                     "currency" => 'PEN',
                     "name" => 'Pepito',
                     "lastname" => 'lastname',
                     "email" => 'lastname'
                 )
             );

             $result = json_decode(json_encode($result));

             if ($result == "") {
                 throw new Exception("La solicitud al mandante fue vacia ", "50002");
             }


             $error = $result->error;
             $code = $result->code;

             if ($error == "" || $error == '1') {
                 throw new Exception("Error en mandante ", "M" . $code);
             }

             $userid = $result->player->userid;
             $balance = $result->player->balance;
             $currency = $result->player->currency;
             $token = $result->player->token;

             $country = $result->player->country;
             $name = $result->player->name;
             $lastname = $result->player->lastname;
             $email = $result->player->email;



             if ($userid == "" || !is_numeric($userid)) {
                 throw new Exception("No coinciden ", "50001");
             }

             if ($balance == "") {
                 throw new Exception("No coinciden ", "50001");
             }

             if ($currency == "") {
                 throw new Exception("No coinciden ", "50001");
             }

             if ($token == "") {
                 throw new Exception("No coinciden ", "50001");
             }

             try{
                 $UsuarioMandante = new UsuarioMandante("",$userid,$Mandante->mandante);

                 $UsuarioMandante->tokenExterno = $auth_token;
                 $UsuarioMandante->saldo = $balance;


                 $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
                 $UsuarioMandanteMySqlDAO->update($UsuarioMandante);

                 $UsuarioMandanteMySqlDAO->getTransaction()->commit();

             }catch (Exception $e){
                 if($e->getCode() == 22){
                     $UsuarioMandante = new UsuarioMandante();

                     $UsuarioMandante->mandante = $Mandante->mandante;
                     //$UsuarioMandante->dirIp = $dir_ip;
                     $UsuarioMandante->nombres = $name;
                     $UsuarioMandante->apellidos = $lastname;
                     $UsuarioMandante->estado = 'A';
                     $UsuarioMandante->email = $email;
                     $UsuarioMandante->moneda = $currency;
                     $UsuarioMandante->paisId = $country;
                     $UsuarioMandante->saldo = 0;
                     $UsuarioMandante->usuarioMandante = $userid;
                     $UsuarioMandante->usucreaId = 0;
                     $UsuarioMandante->usumodifId = 0;
                     $UsuarioMandante->propio = 'N';

                     $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
                     $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);
                     $UsuarioMandanteMySqlDAO->getTransaction()->commit();


                 }
             }


         }else{
             $UsuarioToken = new UsuarioToken($auth_token, '0');

         }


     }else{

     }*/


        /* Se crea un nuevo objeto UsuarioToken con un token de autenticación y un identificador. */
        $UsuarioToken = new UsuarioToken($auth_token, '0');

    }


    if ($cumple) {

        /* Asigna tipo de usuario y obtiene saldo del mandante basado en sesión JSON. */
        $tipoUsuarioSession = "1";
        if ($json->session->typeC != "" && $json->session->typeC != null) {
            $tipoUsuarioSession = $json->session->typeC;
        }

        $saldo = $UsuarioMandante->getSaldo();

        /* gestiona sesiones de usuario y actualiza la base de datos según condiciones. */
        $moneda = $UsuarioMandante->getMoneda();
        $paisId = $UsuarioMandante->getPaisId();

        $UsuarioToken->setRequestId($json->session->sid);

        try {
            $UsuarioSession = new UsuarioSession($tipoUsuarioSession, $json->session->sid, "A");

            if ($UsuarioSession->getUsuarioId() != $UsuarioToken->getUsuarioId()) {
                $UsuarioSession->setUsuarioId($UsuarioToken->usuarioId);

                $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
                $UsuarioSessionMySqlDAO->update($UsuarioSession);
                $UsuarioSessionMySqlDAO->getTransaction()->commit();

            }

        } catch (Exception $e) {

            if ($e->getCode() == "99") {


                /* Se crea una sesión de usuario configurando tipo, ID y estado. */
                $UsuarioSession = new UsuarioSession();
                $UsuarioSession->setTipo($tipoUsuarioSession);
                $UsuarioSession->setRequestId($json->session->sid);
                $UsuarioSession->setUsuarioId($UsuarioToken->usuarioId);
                $UsuarioSession->setEstado('A');
                $UsuarioSession->setPerfil('');

                /* establece IDs de usuario y guarda la sesión en la base de datos. */
                $UsuarioSession->setUsucreaId('0');
                $UsuarioSession->setUsumodifId('0');

                $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
                $UsuarioSessionMySqlDAO->insert($UsuarioSession);
                $UsuarioSessionMySqlDAO->getTransaction()->commit();

                /*
                                $UsuarioSession2 = new UsuarioSession();

                                $rules = [];

                                array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioSession->getUsuarioId(), "op" => "ne"));
                                array_push($rules, array("field" => "usuario_session.request_id", "data" => $UsuarioSession->getRequestId(), "op" => "eq"));


                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $json = json_encode($filtro);


                                $usuarios = $UsuarioSession2->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                $usuarios = json_decode($usuarios);
                                $usuariosFinal = [];

                                foreach ($usuarios->data as $key => $value) {

                                    $UsuarioSession3 = new UsuarioSession("", "", "",$value->{'usuario_session.ususession_id'});

                                    $UsuarioSession3->setEstado('I');

                                    $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
                                    $UsuarioSessionMySqlDAO->update($UsuarioSession3);
                                    $UsuarioSessionMySqlDAO->getTransaction()->commit();


                                }*/

            }
        }

        /*$UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->update($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();*/


        /* Se inicializa un arreglo de respuesta y se definen estructuras para datos y socios. */
        $response = array();

        $response['code'] = 0;

        $data = array();
        $partner = array();

        /* Código que asigna identificadores de socios y monedas desde un objeto JSON. */
        $partner_id = array();

        $min_bet_stakes = array();

        $partner_id['partner_id'] = $json->session->mandante;
        $partner_id['currency'] = $moneda;

        /* inicializa un arreglo con configuraciones de socio para cashout y perfiles. */
        $partner_id['is_cashout_live'] = 0;
        $partner_id['is_cashout_prematch'] = 0;
        $partner_id['cashout_percetage'] = 0;
        $partner_id['maximum_odd_for_cashout'] = 0;
        $partner_id['is_counter_offer_available'] = 0;
        $partner_id['sports_book_profile_ids'] = [1, 2, 5];

        /* Se inicializan variables relacionadas con ofertas, apuestas y seguridad de usuarios. */
        $partner_id['odds_raised_percent'] = 0;
        $partner_id['minimum_offer_amount'] = 0;
        $partner_id['minimum_offer_amount'] = 0;

        $min_bet_stakes[$moneda] = 0.1;

        $partner_id['user_password_min_length'] = 6;

        /* asigna identificadores y apuestas mínimas a un socio en un array. */
        $partner_id['id'] = $json->session->mandante;

        $partner_id['min_bet_stakes'] = $min_bet_stakes;

        $partner[$json->session->mandante] = $partner_id;

        $data["partner"] = $partner;


        /* Asignación de usuario y creación de respuesta con código y datos. */
        $data["usuario"] = $UsuarioToken->getUsuarioId();

        $response["data"] = $data;

        $response = array();
        $response["code"] = 0;

        /* Código que construye una respuesta JSON con datos de usuario y autenticación. */
        $response["rid"] = $json->rid;
        $response["test"] = "test";

        $response["data"] = array(
            "auth_token" => $UsuarioToken->getToken(),
            "user_id" => $UsuarioMandante->getUsumandanteId(),
            "id_platform" => $UsuarioMandante->getUsuarioMandante()
        );


    } else {
        /* lanza una excepción con un mensaje y código específicos en caso de restricción. */


        throw new Exception("Restringido", "01");

    }

}

