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
 * Maneja un desafío de pago utilizando el servicio de PAGADITO.
 *
 * @param object $json Objeto JSON que contiene los datos de la solicitud, incluyendo:
 *  - session: Objeto que contiene la información de la sesión del usuario.
 *    - usuario: Información del usuario mandante.
 *  - params: Objeto que contiene los parámetros de la solicitud.
 *    - num_tarjeta: Número de tarjeta de crédito.
 *    - expiry_month: Mes de expiración de la tarjeta.
 *    - expiry_year: Año de expiración de la tarjeta.
 *    - cvv: Código de seguridad de la tarjeta.
 *    - amount: Monto de la transacción.
 *    - productId: ID del producto.
 *    - idTransaction: ID de la transacción.
 *    - transactionOriginal: Transacción original.
 *    - requestId: ID de la solicitud.
 *    - referenceId: ID de referencia.
 *
 * @return array $response
 *  - code:int Código de respuesta.
 *  - rid:string ID de respuesta.
 *  - data:array Datos de respuesta.
 *   -result:string Mensaje de respuesta.
 */

/* crea una respuesta JSON con un código y un identificador. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);


/* Crea instancias de UsuarioMandante y Usuario usando datos extraídos de un JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$numTarjeta = $json->params->num_tarjeta;
$expiry_month = $json->params->expiry_month;
$expiry_year = $json->params->expiry_year;
$cvv = $json->params->cvv;

/* extrae parámetros de un objeto JSON para su procesamiento. */
$valor = $json->params->amount;
$productId = $json->params->productId;

$idTransaction = $json->params->idTransaction;
$transactionOriginal = $json->params->transactionOriginal;
$requestId = $json->params->requestId;

/* Se extraen datos de un JSON y se limpia un número de tarjeta. */
$referenceId = $json->params->referenceId;
$numTarjeta = str_replace(' ', '', $numTarjeta);

$datos = $json->params;


$Producto = new Producto($productId);


/* maneja un pago específico utilizando el servicio de PAGADITO. */
$Proveedor = new Proveedor($Producto->proveedorId);


switch ($Proveedor->getAbreviado()) {

    case 'PAGADITO':

        $PAGADITOSERVICES = new Backend\integrations\payment\PAGADITOSERVICES();

        $data = $PAGADITOSERVICES->PaymentChallenge($Usuario, $Producto, $numTarjeta, $UsuarioMandante->getNombres(), $expiry_month, $expiry_year, $cvv, $Proveedor->getProveedorId(), $valor, $requestId, $referenceId, $idTransaction, $transactionOriginal);

        break;

}


//$ConfigurationEnvironment = new ConfigurationEnvironment();

/* Verifica si la respuesta es exitosa y estructura los datos para el usuario. */
if ($data->success == "true") {
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "token" => $data->token,
        "requestId" => $data->requestId,
        "referenceId" => $data->referenceId,
        "deviceDataCollectionUrl" => $data->deviceDataCollectionUrl

    );
} else {
    /* Crea una respuesta JSON con código, ID y mensaje basado en datos recibidos. */


    $response = array();
    $response["code"] = 1;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message);
}







