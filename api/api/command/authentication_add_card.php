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

/** Autenticación mediante proveedor de una tarjeta agregada por el usuario
 * @param $json->params->num_tarjeta  Número de tarjeta de crédito
 * @param $json->params->expiry_month  Mes de expiración de la tarjeta de crédito
 * @param $json->params->expiry_year  Año de expiración de la tarjeta de crédito
 * @param $json->params->cvv  Código de seguridad de la tarjeta de crédito
 * @param $json->params->amount  Valor de la transacción
 * @param $json->params->productId  ID del producto
 * @param $json->params->saveCard  Indica si se debe guardar la tarjeta
 * 
 * @return array $response Respuesta de la API
 *  - `code`: Código de estado inicializado en 0.
 *  - `rid`: Identificador de la solicitud, obtenido del objeto JSON `$json`.
 *  - `data`: Array que contiene el resultado de la operación, inicialmente vacío.
 */


/**
 * Este bloque de código inicializa una respuesta en formato array para una API.
 * 
 * - `code`: Código de estado inicializado en 0.
 * - `rid`: Identificador de la solicitud, obtenido del objeto JSON `$json`.
 * - `data`: Array que contiene el resultado de la operación, inicialmente vacío.
 */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);

// Crear una instancia de UsuarioMandante con el usuario de la sesión
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

// Crear una instancia de Usuario con el usuario mandante
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

// Número de tarjeta de crédito
$numTarjeta = $json->params->num_tarjeta;

// Mes de expiración de la tarjeta de crédito
$expiry_month = $json->params->expiry_month;

// Año de expiración de la tarjeta de crédito
$expiry_year = $json->params->expiry_year;

// Código de seguridad de la tarjeta de crédito
$cvv = $json->params->cvv;

// Valor de la transacción
$valor = $json->params->amount;

// ID del producto
$productId = $json->params->productId;

// Indica si se debe guardar la tarjeta
$saveCard = $json->params->saveCard;

// Eliminar espacios del número de tarjeta
$numTarjeta = str_replace(' ', '', $numTarjeta);

$datos = $json->params;


// Crear una instancia de Producto con el ID del producto
$Producto = new Producto($productId);

// Crear una instancia de Proveedor con el ID del proveedor del producto
$Proveedor = new Proveedor($Producto->proveedorId);



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



switch ($Proveedor->getAbreviado()) {
    /**
     * Caso 'PAGADITO':
     * 
     * Este caso maneja la integración con el servicio de pago PAGADITO.
     * 
     * - Se crea una instancia de la clase `PAGADITOSERVICES` del espacio de nombres `Backend\integrations\payment`.
     * - Se llama al método `AddAutentica` de la instancia `PAGADITOSERVICES` con los parámetros `$Usuario`, `$numTarjeta`, `$expiry_month`, `$expiry_year` y `$cvv`.
     * - El resultado de la llamada al método `AddAutentica` se almacena en la variable `$data`.
     */
    case 'PAGADITO':

        $PAGADITOSERVICES= new Backend\integrations\payment\PAGADITOSERVICES();

        $data = $PAGADITOSERVICES->AddAutentica($Usuario,$numTarjeta,$expiry_month,$expiry_year,$cvv);

        break;

    // Este caso maneja la integración con el servicio de pago GREENPAY.
    // Dependiendo de los datos proporcionados, realiza una de las siguientes acciones:
    // - Enrollment: Si 'orderReference' está presente y 'validateFinal' está vacío.
    // - validate: Si 'validateFinal' está presente.
    // - AddAutentica: Si ninguna de las condiciones anteriores se cumple.
    case 'GREENPAY':

        $GREENPAYSERVICES= new Backend\integrations\payment\GREENPAYSERVICES();
        if (!empty($datos->orderReference) && empty($datos->validateFinal)){
            $data = $GREENPAYSERVICES->Enrollment($Usuario, $Producto, $UsuarioMandante->getNombres(), $datos);
        }elseif(!empty($datos->validateFinal)){
            $data = $GREENPAYSERVICES->validate($Usuario, $Producto, $UsuarioMandante->getNombres(), $datos);
        }else{
            $data = $GREENPAYSERVICES->AddAutentica($Usuario, $Producto, $UsuarioMandante->getNombres(), $datos);
        }
        break;

}

/**
 * Si la respuesta de la solicitud es exitosa, se crea un array de respuesta con un código de éxito,
 * el ID de la solicitud y los datos relevantes de la tarjeta, incluyendo el token, el ID de la solicitud,
 * el ID de referencia, la URL de recolección de datos del dispositivo y el resultado.
 *
 * @param object $data Objeto que contiene la respuesta de la solicitud.
 * @param object $json Objeto JSON que contiene el ID de la solicitud.
 * @return void
 */
if($data->success == "true"){
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "token"=>$data->token,
        "requestId" => $data->requestId,
        "referenceId" => $data->referenceId,
        "deviceDataCollectionUrl" => $data->deviceDataCollectionUrl,
        "result" => $data->result
    );
}else{
    //Respuesta de error
    $response = array();
    $response["code"] = 1;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message);
}







