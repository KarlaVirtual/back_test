<?php

use Backend\dto\AuditoriaGeneral;
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
use Backend\dto\UsuarioTarjetacredito;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
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

if($_REQUEST["debug"]=="1"){
    error_reporting(E_ALL);
    /**
     *Verificación de la información vinculada a una tarjeta bancaria
     *@param int $json->params->id  Id de la tarjeta
     *@param int $json->params->amount  Monto de la transacción
     *@param int $json->params->cvv  Código de seguridad de la tarjeta
     *@param int $json->params->site_id Id del sitio
     *@param int $json->params->payer Datos del pagador
     *@param string $player->status_urls Urls de estado
     *@param bool $status_url->cancel Indica si la transacción fue cancelada
     *@param bool $status_url->fail Indica si la transacción falló
     *@param bool $status_url->success Indica si la transacción fue exitosa
     *
     * @return array
     *  - code: int Código de respuesta
     *  - rid: int Identificador de solicitud
     *  - data: array
     *     - result: string Resultado de la autenticación
     */
    ini_set("display_errors","ON");
}

/*El código inicializa un arreglo de respuesta con un código de éxito, un identificador de solicitud y un arreglo de datos vacío con una clave result.*/
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);


/*El código seleccionado obtiene varios parámetros del objeto JSON (productId, id, amount, cvv, site_id, payer) y asigna las URLs de estado (cancel, fail, success) del objeto payer a variables separadas.*/
$productId =$json->params->productId;

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$id = $json->params->id;
$valor = $json->params->amount;
$cvv = $json->params->cvv;
$site_id = $json->params->site_id;
$player = $json->params->payer;
$status_url = $player->status_urls;
$cancel_url = $status_url->cancel;
$fail_url = $status_url->fail;
$success_url = $status_url->success;

$datos = $json->params;


/*El código seleccionado crea instancias de varias clases (UsuarioTarjetacredito, Proveedor, Producto,
ConfigurationEnvironment) utilizando parámetros obtenidos de un objeto JSON. Estas instancias se utilizan
para manejar la autenticación de pagos con tarjeta de crédito.*/
$UsuarioTarjera = new UsuarioTarjetacredito($id);

$Proveedor = new Proveedor($UsuarioTarjera->proveedorId);

$Producto = new Producto("","Tarjetas",$Proveedor->proveedorId);

$ConfigurationEnvironment = new ConfigurationEnvironment();



try {
    // Se inicializa un nuevo objeto de la clase Clasificador
    $Clasificador = new Clasificador("", "ACCVERIFFORDEPOSIT");
    $minimoMontoPremios = 0;

    // Se inicializa un nuevo objeto de la clase MandanteDetalle con los parámetros requeridos
    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    // Se verifica el valor del MandanteDetalle
    if ($MandanteDetalle->getValor() == '1') {
        // Se comprueba si las cédulas están verificadas, en caso contrario se lanza una excepción
        if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
            throw new Exception("La cuenta necesita estar verificada para poder depositar", "21006");
        }
    }
} catch (Exception $e) {
    // Se maneja la excepción solo si el código de error no es 34 ni 41
    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}


/**
 * Verifica el estado de la contingencia para depósitos en pasarelas de pago.
 *
 * Este bloque de código intenta obtener el valor de la contingencia para depósitos
 * en pasarelas de pago del usuario. Si ocurre una excepción, se establece el valor
 * de la contingencia como inactivo ("I").
 *
 * en caso de haber una contingencia se deja el registro del intento fallido del jugador
 *
 */

try {
    $Clasificador = new Clasificador('', 'PAYMENTGATEWAYCONTINGENCY');
    $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $Clasificador->getClasificadorId());
    $Contingencia = $UsuarioConfiguracion->getValor();

} catch (Exception $e) {
    $Contingencia = "I";
}

if ($Contingencia == "A") {


    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsuarioIp("");
    $AuditoriaGeneral->setUsuariosolicitaId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsuariosolicitaIp("");
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("FALLOENDEPOSITOPASARELA");
    $AuditoriaGeneral->setValorAntes(0);
    $AuditoriaGeneral->setValorDespues(0);
    $AuditoriaGeneral->setUsucreaId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsumodifId(0);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setObservacion('intento fallido por pasarela para depositar ');

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

    // Si la contingencia está activa, lanzamos una excepción indicando que no se pueden realizar depósitos.
    throw new Exception("Actualmente no puedes realizar depósitos por este medio. Por favor, comunícate con soporte.", "300167");
}

/*El código verifica si el usuario tiene una contingencia de depósito y lanza una excepción si es así. Luego, obtiene la latitud y longitud
de los parámetros JSON y las concatena en una variable de ubicación.*/
if ($Usuario->contingenciaDeposito == 'A') {

    throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
}



try {
    //Verifica si el partner presenta una contingencia total de depoósito
    $Clasificador = new Clasificador('', 'TOTALCONTINGENCEDEPOSIT');
    $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

    if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
} catch (Exception $ex) {
    if ($ex->getCode() == 30004) throw $ex;
}

try {
    //Verifica exclusión específica al usuario
    $tipo = "EXCTIMEOUT";
    $Tipo = new Clasificador("", $tipo);
    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), 'A', $Tipo->getClasificadorId());

    if (strtotime($UsuarioConfiguracion->getValor()) > (time())) {
        throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
    }
} catch (Exception $e) {
    if ($e->getCode() != '46') {
        throw $e;
    }
}
// validacion de vertical de deposito


/*El código verifica si es posible realizar un depósito en el sitio en función de la configuración del usuario y el mandante. Si no es posible,
lanza una excepción con un mensaje específico.*/
try {
    $Clasificador = new Clasificador("", "DEPOSITUSUONLINE");

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor == "A") {
        throw new Exception("Imposible depositar en este momento en el sitio", "300007");
    }
} catch (Exception $e) {
    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}




/*El código realiza una autenticación de pago para un proveedor específico (PAGADITO).*/
switch ($Proveedor->getAbreviado()) {

    // Caso específico para el proveedor 'PAGADITO'
    case 'PAGADITO':

        $PAGADITOSERVICES= new Backend\integrations\payment\PAGADITOSERVICES();

        $data = $PAGADITOSERVICES->PaymentAutentica($Usuario,$UsuarioTarjera);

        break;


}

// Se verifica si la autentificación del pago fue exitosa
if($data->success == true){
    $response = array();
    $response["code"] = 0; // Código de éxito
    $response["rid"] = $json->rid; // ID de la solicitud
    $response["data"] = array(
        "token" => $data->token, // Token recibido
        "requestId" => $data->requestId, // ID de la solicitud del pago
        "referenceId" => $data->referenceId, // ID de referencia del pago
        "deviceDataCollectionUrl" => $data->deviceDataCollectionUrl // URL de recolección de datos del dispositivo
    );


}else{
    // Se prepara la respuesta en caso de fallo
    $response = array();
    $response["code"] = 1; // Código de error
    $response["rid"] = $json->rid; // ID de la solicitud
    $response["data"] = array(
        "result" => $data->Message);
}






