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
 * Procesa una solicitud de sesión y actualiza el token de usuario.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 *  - params (object): Contiene los parámetros de la solicitud.
 *    - ck (string): Valor de la cookie.
 *  - session (object): Contiene la información de la sesión.
 *    - sid (string): ID de la sesión.
 *  - rid (string): ID de la solicitud.
 *
 * @return array $response Respuesta en formato array basada en el objeto JSON.
 *  - code (int): Código de respuesta.
 *  - rid (string): ID de la solicitud.
 *  - data (array): Datos de la respuesta.
 *    - sid (string): ID de la sesión.
 *    - ip (string): Dirección IP del cliente.
 *    - skin (string): Tema de la interfaz.
 *    - data_source (int): Fuente de datos.
 *
 */

/* asigna el valor del parámetro 'ck' de un objeto JSON a la variable $cookie. */
$cookie = $json->params->ck;

//$cookie = validarCampoSecurity($cookie, true);



/* actualiza un token de usuario si la cookie no está vacía. */
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
        "skin" => "test2",
        "data_source" => 0,
    );
} else {
/* genera una respuesta en formato array basada en un objeto JSON. */

    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "sid" => $json->session->sid,
        "ip" => get_client_ip(),
        "skin" => "test2",
        "data_source" => 0,
    );
}
//$json->session->sid
