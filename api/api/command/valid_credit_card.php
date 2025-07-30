<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
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
use Backend\dto\UsuarioMarketing;
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
use Backend\mysql\BonoInternoMySqlDAO;
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
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/valid_credit_card
 *
 * Verificación de tarjeta de pago para el usuario
 *
 * Este recurso permite verificar la tarjeta de un usuario mediante el servicio correspondiente, según el proveedor configurado.
 * Dependiendo de los resultados, se retornan diferentes códigos de respuesta con el mensaje correspondiente.
 *
 * @param object $json : Objeto JSON recibido con los parámetros de la solicitud.
 * @param string $json ->session->usuario : Identificador del usuario en sesión.
 * @param int $json ->params->id : Identificador del pago a verificar.
 * @param float $json ->params->debitedValue : Monto a verificar.
 *
 * @return object  $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *data* (array): Contiene el resultado de la consulta o acción ejecutada.
 *     - *result* (string): Mensaje correspondiente a la verificación de la tarjeta.
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* crea una respuesta estructurada en formato JSON con un resultado vacío. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);


/* Crea un usuario y proveedor basado en datos de sesión y parámetros JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$Id = $json->params->id;
$amount = $json->params->debitedValue;


if ($Usuario->mandante == "2") {
    $Proveedor = new  Proveedor("", "SAGICOR");
}


/* verifica una tarjeta para el proveedor SAGICOR utilizando un servicio específico. */
switch ($Proveedor->getAbreviado()) {


    case 'SAGICOR':

        $SAGICORSERVICES = new Backend\integrations\payment\SAGICORSERVICES();

        $data = $SAGICORSERVICES->VerificarTarjeta($Usuario, $Id, $amount);

        break;
}


//$ConfigurationEnvironment = new ConfigurationEnvironment();

/* Verifica el éxito de la operación y estructura una respuesta en formato JSON. */
if ($data->success == "true") {
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message

    );
} elseif ($data->code == 1) {
    /* Genera una respuesta en formato array si el código es igual a 1. */


    $response = array();
    $response["code"] = 1;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message);
} elseif ($data->code == 2) {
    /* verifica un código y prepara una respuesta en formato de array. */

    $response = array();
    $response["code"] = 2;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message);
}







