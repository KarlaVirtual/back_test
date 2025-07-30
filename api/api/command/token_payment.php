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
 *
 * command/token_payment
 *
 * Procesamiento de solicitud de pago
 *
 * Este recurso se encarga de procesar una solicitud de pago basada en el proveedor `PAYMENTEZ`,
 * utilizando los parámetros proporcionados en el objeto JSON de entrada.
 *
 * @param string $token : Token para autenticar el pago.
 * @param float $valor : Valor de la transacción.
 * @param string $cvc : Código CVC de la tarjeta.
 * @param string $provider : Identificador del proveedor (en este caso, 'PAYMENTEZ').
 * @param int $productoId : ID del producto relacionado con el pago.
 *
 * @returns object $response es un objeto con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error o éxito.
 *  - *data* (array): Contiene el resultado de la operación.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws Exception No
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Crea un arreglo de respuesta con un código y un resultado específico. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => "-1119"

);


/* Código para inicializar usuarios y obtener parámetros de un objeto JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$token = $json->params->token;
$valor = $json->params->valor;

$cvc = $json->params->cvc;


/* Código para crear una solicitud de pago según el proveedor especificado. */
$provider = $json->params->provider;
$productoId = $json->params->productoId;

$Proveedor = new  Proveedor($provider);
$Producto = new Producto($productoId);
switch ($Proveedor->getAbreviado()) {

    case 'PAYMENTEZ':

        $PAYMENTEZSERVICES = new Backend\integrations\payment\PAYMENTEZSERVICES();

        $data = $PAYMENTEZSERVICES->createRequestPayment2($Usuario, $Producto, $valor, $token, $cvc, $Proveedor->getProveedorId());

        break;

}

/* Crea un objeto de configuración y prepara una respuesta con código y ID. */
$ConfigurationEnvironment = new ConfigurationEnvironment();


$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;

/* Asigna un mensaje a una clave "result" en un array de respuesta. */
$response["data"] = array(
    "result" => $data->message,

);





