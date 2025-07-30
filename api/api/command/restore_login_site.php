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
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/restore_login_site
 *
 * Recupera la sesion del usuario
 *
 * @param int $site_id : Partner vinculado al usuario
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


/* valida un token de autenticación y genera excepción si está vacío. */
$params = $json->params;

$auth_token = $params->auth_token;
$site_id = $json->params->site_id;
$auth_token = validarCampoSecurity($auth_token, true);


if ($auth_token == "") {

    throw new Exception("Token vacio", "01");

}


/* crea un objeto de token de usuario y inicializa un mandante si es necesario. */
$cumple = true;

$UsuarioToken = new UsuarioToken($auth_token, '0');

if ($UsuarioMandanteSite == '') {
    $UsuarioMandanteSite = new UsuarioMandante($UsuarioToken->usuarioId);
}

/* verifica si el email no es uno de los específicos. */
if ($UsuarioMandanteSite->email != 'tecnologiatemp3@gmail.com' && $UsuarioMandanteSite->email != 'tecnologiatemp5@gmail.com' && $UsuarioMandanteSite->email != 'tecnologiatemp2@gmail.com') {
    // throw new Exception('We are currently in the process of maintaining the site.', 30004);

    //  throw new Exception("No puede iniciar sesion en el sitio. ", "30010");

}

/* verifica si un token es válido según la fecha de creación. */
$diff = abs(time() - strtotime($UsuarioToken->getFechaCrea()));
$years = floor($diff / (365 * 60 * 60 * 24));
$months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
$days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

if (!in_array($UsuarioMandanteSite->mandante, array(11))) {
    if (floatval($days) >= 1 && (in_array($UsuarioMandanteSite->mandante, array('0', '6', '8', '2', '12', 3, 4, 5, 6, 7)) || true) && !in_array($UsuarioMandanteSite->usuarioMandante, array(17884, 242068, 255499, 255528, 255547, 255584, 242055, 242048))) {
        throw new Exception("No existe Token", "21");
    }
}
if ($cumple) {


/* Inicializa variables y verifica si el proveedor de usuario está establecido. */
    $UsuarioMandante = $UsuarioMandanteSite;

    $saldo = $UsuarioMandante->getSaldo();
    $moneda = $UsuarioMandante->getMoneda();
    $paisId = $UsuarioMandante->getPaisId();

    //$UsuarioToken->setRequestId($json->session->sid);
    if ($UsuarioToken->getUsuarioProveedor() == "") {
        $UsuarioToken->setUsuarioProveedor(0);
    }
    
/* Actualiza la fecha de modificación de un token de usuario en MySQL y confirma transacción. */
$UsuarioToken->setFechaModif(date('Y-m-d H:i:s'));

    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
    $UsuarioTokenMySqlDAO->update($UsuarioToken);
    $UsuarioTokenMySqlDAO->getTransaction()->commit();


    $response = array();


/* Código inicializa variables para manejar respuestas y datos de apuestas en un sistema. */
    $response['code'] = 0;

    $data = array();
    $partner = array();
    $partner_id = array();

    $min_bet_stakes = array();


/* Asigna valores a un array de configuración de un socio relacionado con apuestas. */
    $partner_id['partner_id'] = $json->session->mandante;
    $partner_id['currency'] = $moneda;
    $partner_id['is_cashout_live'] = 0;
    $partner_id['is_cashout_prematch'] = 0;
    $partner_id['cashout_percetage'] = 0;
    $partner_id['maximum_odd_for_cashout'] = 0;

/* Código establece parámetros de una oferta para un socio en una plataforma de apuestas. */
    $partner_id['is_counter_offer_available'] = 0;
    $partner_id['sports_book_profile_ids'] = [1, 2, 5];
    $partner_id['odds_raised_percent'] = 0;
    $partner_id['minimum_offer_amount'] = 0;
    $partner_id['minimum_offer_amount'] = 0;

    $min_bet_stakes[$moneda] = 0.1;


/* Se establece la configuración de un socio, incluyendo contraseña mínima y apuestas. */
    $partner_id['user_password_min_length'] = 6;
    $partner_id['id'] = $json->session->mandante;

    $partner_id['min_bet_stakes'] = $min_bet_stakes;

    $partner[$json->session->mandante] = $partner_id;


/* asigna datos a un array y prepara una respuesta estructurada. */
    $data["partner"] = $partner;

    $data["usuario"] = $UsuarioToken->getUsuarioId();

    $response["data"] = $data;

    $response = array();

/* crea una respuesta JSON con información de autenticación y usuario. */
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = array(
        "auth_token" => $UsuarioToken->getToken(),
        "user_id" => $UsuarioToken->getUsuarioId(),
        "channel_id" => $UsuarioToken->getUsuarioId(),
        "id_platform" => $UsuarioMandante->getUsuarioMandante()
    );

} else {
/* Lanza una excepción con mensaje "Restringido" y código "01" en caso de error. */


    throw new Exception("Restringido", "01");

}

