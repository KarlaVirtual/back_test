<?php

use Backend\dto\Automation;
use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaAsociada;
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
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioInformacion;
use Backend\dto\UsuarioAutomation;
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
use Backend\mysql\AutomationMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
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
use Firebase\JWT\Key;

/**
 * Deposit
 *
 * Procesa depósitos de clientes en diferentes servicios y valida restricciones.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param float $params->amount Monto del depósito.
 * @param string $params->service Servicio seleccionado para el depósito.
 * @param string $params->operation_number Número de operación.
 * @param string $params->currency Moneda utilizada.
 * @param object $params->payer Información del cliente que realiza el depósito.
 * @param string $params->vs_utm_campaign Campaña de marketing asociada.
 * @param string $params->vs_utm_campaign2 Campaña secundaria de marketing.
 * @param string $params->riskId Identificador de riesgo.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - "code" (int): Código de respuesta.
 * - "rid" (string): Identificador de la solicitud.
 * - "data" (array): Información adicional del depósito.
 *
 * @throws Exception Si ocurre un error en las validaciones o el procesamiento del depósito.
 */

$amount = $params->amount;
$service = $params->service;
$operation_number = $params->operation_number;
$currency = $params->currency;
$player = $params->payer;
$status_url = $player->status_urls;
$cancel_url = $status_url->cancel;
$fail_url = $status_url->fail;
$success_url = $status_url->success;
$date = $status_url->date;
$vs_utm_campaign = $params->vs_utm_campaign;

if ($vs_utm_campaign != '' && $vs_utm_campaign != 'undefined' && explode('_', $vs_utm_campaign)[1] != 'undefined') {
    $_REQUEST['vs_utm_campaign'] = $vs_utm_campaign;
}
$vs_utm_campaign2 = $params->vs_utm_campaign2;

if ($vs_utm_campaign2 != '' && $vs_utm_campaign2 != 'undefined' && explode('_', $vs_utm_campaign2)[1] != 'undefined') {
    $_REQUEST['vs_utm_campaign'] = $vs_utm_campaign2;
}

$riskId = $params->riskId;

$stateRisk = 0;


$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

//$Pais = new Pais($Usuario->paisId);

try {
    $Clasificador = new Clasificador("", "DEPOSITBETSHOP");

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor == "A") {
        throw new Exception("Contingencia global para depositos por punto de venta", "300008");
    }

} catch (Exception $e) {
    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}


try {
    /**
     * Se crea una instancia de la clase Clasificador
     * con un identificador vacío y el tipo 'TOTALCONTINGENCEDEPOSIT'.
     */
    $Clasificador = new Clasificador('', 'TOTALCONTINGENCEDEPOSIT');
    $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

    /**
     * Se lanza una excepción si el valor recuperado
     * del MandanteDetalle es igual a 1.
     */
    if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
} catch (Exception $ex) {
    if ($ex->getCode() == 30004) throw $ex;
}

try {
    /**
     * Se crea una instancia de la clase Clasificador
     * con un identificador vacío y el tipo 'EXCTIMEOUT'.
     */
    $Clasificador = new Clasificador('', 'EXCTIMEOUT');
    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->usuarioMandante, 'A', $Clasificador->getClasificadorId());

    if ($UsuarioConfiguracion->getValor() > date('Y-m-d H:i:s')) throw new Exception('Partial self-excluded user by time', 100085);
} catch (Exception $ex) {
    /**
     * Si la excepción lanzada tiene un código de error igual a 100085,
     * se relanza la misma excepción.
     */
    if ($ex->getCode() == 100085) throw $ex;
}

// try {
//     $tipo = "EXCTIMEOUT";
//     $Tipo = new Clasificador("", $tipo);
//     $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), 'A', $Tipo->getClasificadorId());

//     if (strtotime($UsuarioConfiguracion->getValor()) > (time())) {
//         throw new Exception("El usuario ingresado esta autoexcluido temporalmente ", "20027");
//     }

// } catch (Exception $e) {
//     if ($e->getCode() != '46') {
//         throw $e;
//     }

// }


/**
 * Obtener las fechas de inicio y fin del día actual.
 */
$start_day = date('Y-m-d 00:00:00');
$end_day = date('Y-m-d 23:59:59');
$current_day = date('w') === 1 ? date('w') + 1 : date('w') - 1;
$start_week = date('Y-m-d 00:00:00', strtotime(' - ' . $current_day . ' days'));
$end_week = date('Y-m-d 23:59:59', strtotime($start_week . ' + ' . 6 . ' days'));

/**
 * Inicializar las reglas para las consultas.
 */
$rules = [];

/**
 * Agregar condiciones para las reglas de filtrado.
 */
array_push($rules, ['field' => 'usuario_recarga.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
array_push($rules, ['field' => 'usuario_recarga.fecha_crea', 'data' => date('Y-m-01 00:00:00'), 'op' => 'ge']);
array_push($rules, ['field' => 'usuario_recarga.fecha_crea', 'data' => date('Y-m-t 23:59:59'), 'op' => 'le']);

/**
 * Convertir las reglas a formato JSON para el filtrado.
 */
$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

/**
 * Crear una instancia de UsuarioRecarga y realizar la consulta.
 */
$UsuarioRecarga = new UsuarioRecarga();
$query = $UsuarioRecarga->getUsuarioRecargasCustom2("IF(usuario_recarga.fecha_crea >= '{$start_day}' AND usuario_recarga.fecha_crea <= '{$end_day}', SUM(usuario_recarga.valor), 0) as total_day, IF(usuario_recarga.fecha_crea >= '{$start_week}' AND usuario_recarga.fecha_crea <= '{$end_week}', SUM(usuario_recarga.valor), 0) as total_week, IFNULL(SUM(usuario_recarga.valor), 0) as total_mounth", 'usuario_recarga.fecha_crea', 'asc', 0, 1, $filter, true);
$query = json_decode($query, true);

/**
 * Almacenar los totales de las recargas en variables.
 */
$total_day = $query['data'][0]['.total_day'];
$total_week = $query['data'][0]['.total_week'];
$total_mounth = $query['data'][0]['.total_mounth'];

try {
    // Se crea una instancia de la clase Clasificador con un tipo específico.
    $Clasificador = new Clasificador("", "ACCVERIFFORDEPOSIT");
    $minimoMontoPremios = 0;
    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    // Verifica si el valor de MandanteDetalle es igual a '1'.
    if ($MandanteDetalle->getValor() == '1') {
        if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
            // throw new Exception("La cuenta necesita estar verificada para poder depositar", "21006");
        }

    }

} catch (Exception $e) {
    // Si el código de la excepción no es 34 ni 41, se relanza la excepción.
    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}


// try {
//     $Clasificador = new clasificador("","LIMITEDEPOSITODIARIOGLOBAL");

//     $MandanteDetalle = new MandanteDetalle("",$UsuarioMandante->mandante,$Clasificador->getClasificadorId(),$Usuario->paisId,$Usuario->estado);


// } catch (\Throwable $th) {
//     //throw $th;
// }


// verificamos el minimo para depositar

try {
    $Clasificador = new Clasificador("", "MINDEPOSIT");
    $minimoMontoPremios = 0;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->getValor() != '0') {
        if ($amount < $MandanteDetalle->getValor()) {
            throw new Exception("Valor menor al minimo permitido para depositar", "21008");
        }

    }

} catch (Exception $e) {
    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}

try {
    // Crea una nueva instancia de la clase Clasificador con parámetros específicos
    $Clasificador = new Clasificador("", "MAXDEPOSIT");
    $minimoMontoPremios = 0;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    // Obtiene el valor del MandanteDetalle
    $valor = $MandanteDetalle->valor;

    // Verifica si el valor del MandanteDetalle es diferente de '0'
    if ($MandanteDetalle->getValor() != '0') {
        if ($amount > $MandanteDetalle->getValor()) {
            throw new Exception("Valor mayor al máximo permitido para depositar", "21009");
        }

    }

} catch (Exception $e) {
    // Verifica si el código de la excepción es diferente de 34 y 41
    // Si es diferente, lanza la excepción de nuevo
    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}

// Establece la variable $sg como verdadero
$sg = true;


if ($service == "FIXED-BANCOCR1") {
    // Inicializa una cadena para el texto del depósito.
    $textDeposito = "
";

    // Crea un array para almacenar la información de respuesta.
    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    // Asigna el array de datos a la respuesta.
    $response["data"] = $data;

    // Estructura los datos de la respuesta.
    $response["data"] = array(
        "result" => 0,
        "details" => array(
            "action" => 'https://mercadeoonline.net/pago-codigos/',
            "method" => '',
            "fields" => array(
                array("status" => $status_url),
                // array("name" => "mtid", "value" => "012020321"),
                // array("name" => "amount", "value" => "10"),
                // array("name" => "currenct", "value" => "USD")
            )
        )


    );

    // Establece el objetivo del enlace como abrir en una nueva pestaña.
    $response["data"]['target'] = '_blank';


    $sg = false;
}

if ($service == "FIXED-BANCOMX1") {
    $textDeposito = "Hola, realiza la transferencia y envia el comprobante al Whatsapp +52 55 90630287";

    $data = array();
    $data["success"] = true; // Indica que la operación fue exitosa
    $data["userid"] = $Usuario->usuarioId; // ID del usuario
    $data["amount"] = ""; // Cantidad del depósito
    $data["dataText"] = $textDeposito; // Texto informativo del depósito
    $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1636497972.png"; // URL de la imagen
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}
// Se verifica el tipo de servicio que se está utilizando
if ($service == "FIXED-BANCOCR2") {
    $textDeposito = "Hola! Recargar al numero +50685218622 y enviar el comprobante al Whatsapp +506 84079595";

    // Arreglo de datos para la respuesta
    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}
if ($service == "FIXED-BANCO1") {
    // Prepara el mensaje para el depósito a través de FIXED-BANCO1
    $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 3000 USD 
Número de cuenta bancaria: BAC CORDOBAS 5700000330 ROSALIA RUIZ RESTREPO
Número de Whatsapp: 505 82442785
";

    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}
if ($service == "FIXED-BANCO2") {

    // Prepara el mensaje para el depósito a través de FIXED-BANCO2
    $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 3000 USD 
Número de cuenta bancaria: BAC CORD 5710000222 ROSALIA RUIZ RESTREPO
Número de Whatsapp: 505 82442785
";

    $data = array();
    $data["success"] = true; // Indica que la operación fue exitosa
    $data["userid"] = $Usuario->usuarioId; // El ID del usuario
    $data["amount"] = ""; // La cantidad del depósito, actualmente vacío
    $data["dataText"] = $textDeposito; // El mensaje de texto para el depósito
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg"; // URL de la imagen asociada a DoradoBet
    $response = array();
    $response["code"] = 0; // Código de respuesta
    $response["rid"] = $json->rid; // ID de la transacción

    $response["data"] = $data; // Agrega los datos a la respuesta

    $sg = false; // Indica que no hay una operación adicional que manejar
}
if ($service == "FIXED-BANCO4") {

    /**
     * Mensaje de depósito para el servicio FIXED-BANCO4.
     * Este texto incluye información sobre los depósitos disponibles.
     */
    $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: BDF CORD 6080564153 ROSALIA RUIZ RESTREPO 
Número de Whatsapp: 505 82442785
";

    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}
if ($service == "FIXED-BANCO3") {

    /**
     * Mensaje de depósito para el servicio FIXED-BANCO3.
     * Este texto incluye información sobre los depósitos disponibles.
     */
    $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: LAFISE USD 106204583 DARWIN MEDINA
Número de Whatsapp: 505 82442785
";

    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}
// Verifica el servicio para procesar el depósito y asigna el texto y datos correspondientes
if ($service == "FIXED-BANCO5") {

    $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: BDF CORD 6070312903 ROSALIA RUIZ RESTREPO 
Número de Whatsapp: 505 82442785";

    // Inicializa el array de datos para la respuesta
    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}

// Verifica el servicio para procesar el depósito y asigna el texto y datos correspondientes
if ($service == "FIXED-BANCO6") {

    // Mensaje informativo sobre el depósito para FIXED-BANCO6
    $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: BDF DOLARES 366688539 NELSON DAVID MARTINEZ 
Número de Whatsapp: 505 82442785";

    // Inicializa el array de datos para la respuesta
    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}
if ($service == "FIXED-BANCO7") {

    /**
     * Mensaje informativo sobre el depósito a través del banco preferido.
     * Contiene detalles sobre el depósito mínimo, máximo, número de cuenta bancaria
     * y contacto de WhatsApp.
     */
    $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: BDF CORD 366688562 NELSON DAVID MARTINEZ 
Número de Whatsapp: 505 82442785";

    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}
if ($service == "FIXED-BANCOECU1") {
    // Inicializa una variable de texto para el depósito
    $textDeposito = "";

    // Crea un array para almacenar los datos de respuesta
    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    // Asigna el array de datos a la respuesta
    $response["data"] = $data;

    // Configura detalles adicionales en la respuesta
    $response["data"] = array(
        "result" => 0,
        "details" => array(
            "action" => 'https://inhz1.facilito.com.ec:2192/Ubicanos/Index.aspx',
            "method" => '',
            "fields" => array(
                array("status" => $status_url),
                // array("name" => "mtid", "value" => "012020321"),
                // array("name" => "amount", "value" => "10"),
                // array("name" => "currenct", "value" => "USD")
            )
        )


    );
    $response["data"]['target'] = '_blank';

    $sg = false;
}

if ($service == "FIXED-BANCOECU2") {
    // Mensaje para el depósito en Western Union
    $textDeposito = "Dirigete al punto de Red activa Western Union, indica el id de tu cliente y solicita tu recarga.";

    // Inicializa el array de datos de respuesta
    $data = array();
    $data["success"] = true;
    $data["userid"] = $Usuario->usuarioId;
    $data["amount"] = "";
    $data["dataText"] = $textDeposito;
    $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1637954767.png";
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] = $data;

    $sg = false;
}

if ($service == "FIXED-BANCOECU3") {
    // Mensaje para el depósito en Bemovil
    $textDeposito = "Dirigete al punto de Bemovil, indica el id de tu cliente y solicita tu recarga.";

    // Inicializa el array de datos de respuesta
    $data = array();
    $data["success"] = true; // Estado de éxito
    $data["userid"] = $Usuario->usuarioId; // ID del usuario
    $data["amount"] = ""; // Monto de la transacción
    $data["dataText"] = $textDeposito; // Texto del mensaje
    $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1645551119.png"; // URL de la imagen
    $response = array();
    $response["code"] = 0; // Código de respuesta
    $response["rid"] = $json->rid; // ID de la solicitud

    $response["data"] = $data; // Datos de respuesta

    $sg = false; // Estado de la variable
}

if ($service == "FIXED-BANCOJMD1") {
    // Definición de la información del depósito para el beneficiario
    $textDeposito = "
    Beneficiary Name: Prime Sports Jamaica Limited
Beneficiary Address: 9A Retirement Crescent, Kingston 5
Beneficiary Bank: Bank of Nova Scotia
Beneficiary Account: 000802175
Branch Transit #: 50575
Account Type: Chequing Account
Bank Address: Knutsford Boulevard, Kingston 5 (New Kingston)

";

    // Inicialización del array de datos
    $data = array();
    $data["success"] = true; // Indica que la operación fue exitosa
    $data["userid"] = $Usuario->usuarioId; // ID del usuario
    $data["amount"] = ""; // Monto (actualmente vacío)
    $data["dataText"] = $textDeposito; // Texto con la información del depósito
    $data["dataImg"] = "https://images.virtualsoft.tech/site/justbet/logo.png"; // URL de la imagen del logo
    $response = array(); // Inicialización del array de respuesta
    $response["code"] = 0; // Código de respuesta, 0 indica éxito
    $response["rid"] = $json->rid; // ID de la transacción

    $response["data"] = $data; // Asignación de datos a la respuesta

    $sg = false; // Variable para estado de servicio (false por defecto)
}

if ($service == "FIXED-BANCOJMD2") {
    $textDeposito = "
    Beneficiary Name: Prime Sports Jamaica Limited
Beneficiary Address: 9A Retirement Crescent, Kingston 5
Beneficiary Bank: Sagicor Bank Jamaica
Beneficiary Account #5503070814
Account Type: Savings Account
Bank Address: 17 Dominica Drive, Kingston 5.


";

    $data = array();
    $data["success"] = true; // Indica si la operación fue exitosa
    $data["userid"] = $Usuario->usuarioId; // ID del usuario
    $data["amount"] = ""; // Monto del depósito
    $data["dataText"] = $textDeposito; // Texto del depósito
    $data["dataImg"] = "https://images.virtualsoft.tech/site/justbet/logo.png"; // URL de la imagen
    $response = array();
    $response["code"] = 0; // Código de respuesta
    $response["rid"] = $json->rid; // ID de la transacción

    $response["data"] = $data; // Asignación de los datos a la respuesta

    $sg = false; // Variable de control, su uso no está definido en el fragmento
}
if ($service == "FIXED-BANCOJMD3") {
    // Se define el texto del depósito con detalles del beneficiario.
    $textDeposito = "
    Beneficiary Name: Prime Sports Jamaica Limited
Beneficiary Address: 9A Retirement Crescent, Kingston 5
Beneficiary Bank: Bank of Nova Scotia
Beneficiary Account: 000802175
Branch Transit #: 50575
Account Type: Chequing Account
Bank Address: Knutsford Boulevard, Kingston 5 (New Kingston)

";

    // Se inicializa un array para almacenar los datos de respuesta.
    $data = array();
    $data["success"] = true; // Indica que la operación fue exitosa.
    $data["userid"] = $Usuario->usuarioId; // ID del usuario.
    $data["amount"] = ""; // Cantidad (en este caso, no se especifica).
    $data["dataText"] = $textDeposito; // Texto del depósito.
    $data["dataImg"] = "https://images.virtualsoft.tech/site/justbet/logo.png"; // URL de la imagen.

    // Se prepara la respuesta principal.
    $response = array();
    $response["code"] = 0; // Código de respuesta, 0 indica éxito.
    $response["rid"] = $json->rid; // ID de la transacción.

    $response["data"] = $data; // Se añaden los datos a la respuesta.

    $sg = false; // Variable de estado, se establece en falso.
}

if ($service == "FIXED-BANCOJMD4") {
    $textDeposito = "
    Beneficiary Name: Prime Sports Jamaica Limited
Beneficiary Address: 9A Retirement Crescent, Kingston 5
Beneficiary Bank: Sagicor Bank Jamaica
Beneficiary Account #5503070814
Account Type: Savings Account
Bank Address: 17 Dominica Drive, Kingston 5.


";

    // Arreglo que almacena información sobre el éxito y datos del usuario
    $data = array();
    $data["success"] = true; // Indica que la operación fue exitosa
    $data["userid"] = $Usuario->usuarioId; // ID del usuario
    $data["amount"] = ""; // Monto (vacío en este caso)
    $data["dataText"] = $textDeposito; // Texto del depósito
    $data["dataImg"] = "https://images.virtualsoft.tech/site/justbet/logo.png"; // URL de la imagen

    // Respuesta que se devolverá
    $response = array();
    $response["code"] = 0; // Código de respuesta
    $response["rid"] = $json->rid; // ID de la transacción

    $response["data"] = $data; // Datos de la respuesta

    $sg = false; // Bandera que indica un estado específico
}

if ($sg) {

    if ($riskId != '') {

        // Inicializa la variable $stateRisk en 0.
        $stateRisk = 0;

        try {

            // Crea una nueva instancia de UsuarioAutomation utilizando $riskId.
            $UsuarioAutomation = new UsuarioAutomation($riskId);

            if ($UsuarioAutomation->estado == 'A') {
                $stateRisk = 1;
            }

            if ($UsuarioAutomation->estado == 'R') {
                $stateRisk = 2;
            }

            // Si $stateRisk sigue siendo 0, se evalúa la fecha de creación para posible actualización del estado.
            if ($stateRisk == 0) {
                $date1 = strtotime($UsuarioAutomation->fechaCrea);
                $date2 = time();
                $mins = ($date2 - $date1) / 60;

                // Verifica que la fecha de creación no esté vacía.
                if ($UsuarioAutomation->fechaCrea != '') {
                    if ($mins >= 1) {

                        $UsuarioAutomation->estado = 'R';

                        $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO();

                        $UsuarioAutomationMySqlDAO->update($UsuarioAutomation);

                        $UsuarioAutomationMySqlDAO->getTransaction()->commit();
                        $stateRisk = 2;

                    }
                }
            }
        } catch (Exception $e) {
            // Manejo de excepciones (sin acción específica definida).
        }


        if ($stateRisk != 1) {
            $seguir = false;
            $data[] = array('success' => false, 'stateRisk' => $stateRisk);


            $response["data"] = array(
                "result" => 0,
                "stateRisk" => $stateRisk,


            );
            print json_encode($response);

            exit();

        } else {
        }

    }


    // Definición del número máximo de filas a recuperar
    $MaxRows = 1000;
    $OrderedItem = 1;
    $SkeepRows = 0;


    // Inicialización de las reglas de filtrado
    $rules = [];
    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
    array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));
    array_push($rules, array("field" => "producto.producto_id", "data" => "$service", "op" => "eq"));


    // Verifica si el usuario tiene una prueba activa
    if ($Usuario->test == 'S') {

    } else {
        if ($Usuario->mandante == '0') {

            // Agrega regla para filtrar proveedores específicos
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "45,41,50,87", "op" => "ni")); // Filtra proveedores que no estén en la lista dada
        }

    }

    // Configuración del filtro usando las reglas definidas
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $ProductoMandante = new ProductoMandante();

    $ProductoMandantes = $ProductoMandante->getProductosMandantePaisCustom(" producto.*,proveedor.*,prodmandante_pais.* ", "producto.orden", "DESC,producto.descripcion", $SkeepRows, $MaxRows, $json2, true, $Usuario->paisId);

    $ProductoMandantes = json_decode($ProductoMandantes);

    $ProductoMandantesData = array();

    $moneda = $Usuario->moneda;


    // Prepara la respuesta
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $URLREDIRECCION = "";

    if (count($ProductoMandantes->data) > 0) {

        $producto = $ProductoMandantes->data[0];

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment() || $Usuario->mandante == '2') {

            if ($Usuario->usuarioId == '438120') {
                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId('27915');
                $result = $UsuarioConfiguracion->verifyLimitesDeposito($amount);


                if ($result != '0') {
                    throw new Exception("Limite de deposito", $result);
                }
            }
            $UsuarioConfiguracion = new UsuarioConfiguracion();

            $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->getUsuarioMandante());

        }

        if ($producto->{'prodmandante_pais.min'} > $amount && $producto->{'prodmandante_pais.min'} != '') {
            throw new Exception("Valor menor al minimo permitido para depositar", '21008');
        }

        if ($producto->{'prodmandante_pais.max'} < $amount && $producto->{'prodmandante_pais.max'} != 0 && $producto->{'prodmandante_pais.max'} != '') {
            throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
        }

        if ($producto->{'producto_mandante.min'} > $amount) {
            throw new Exception("Valor menor al minimo permitido para depositar", '21008');
        }

        if ($producto->{'producto_mandante.max'} < $amount && $producto->{'producto_mandante.max'} != 0) {
            throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
        }

        if ($producto->{'producto.min'} > $amount) {
            throw new Exception("Valor menor al minimo permitido para depositar", '21008');
        }

        if ($producto->{'producto.max'} < $amount && $producto->{'producto.max'} != 0) {
            throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
        }

        /**
         * Crea instancias de Proveedor y Producto utilizando datos del objeto $ProductoMandantes.
         * Valida condiciones específicas basadas en el usuario y el producto antes de realizar una transacción.
         * Lanzará excepciones si se violan las reglas de negocio establecidas.
         */

        // Se inicializa el objeto Proveedor con el ID del proveedor del primer producto mandante
        $Proveedor = new Proveedor($ProductoMandantes->data[0]->{'proveedor.proveedor_id'});
        $Producto = new Producto($ProductoMandantes->data[0]->{'producto.producto_id'});


        if ($Usuario->mandante == '8' && $Producto->productoId == '11274') {

            if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {


                if (100 < $amount) {
                    throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
                }
            }

        }

        // Verifica si el proveedor es 'SFP', si el mandante del usuario es '8' y si una condición es falsa
        if ($Proveedor->getAbreviado() == 'SFP' && $Usuario->mandante == '8' && false) {

            // Define las fechas para el rango de búsqueda
            $FromDateLocal = date("Y-m-d 00:00:00");
            $ToDateLocal = date("Y-m-d 23:59:59");
            $rules = [];

            // Se agregan reglas de filtros para la consulta de transacciones
            array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "E", "op" => "eq"));
            array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));

            array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

            // Construye el filtro para la consulta
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 100;

            // Codifica el filtro en formato JSON
            $json = json_encode($filtro);

            // Se inicializa el objeto TransaccionProducto
            $TransaccionProducto = new TransaccionProducto();
            $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

            // Se decodifica el resultado JSON
            $transacciones = json_decode($transacciones);


            // Si se encontraron transacciones, lanza una excepción indicando que ya hay solicitudes pendientes
            if (count($transacciones->data) > 0) {
                throw new Exception("Ya tiene solicitudes pendientes del día del proveedor por pagar. Revisa tu correo electornico para encontrar y pagar estas solicitudes.", '21020');
            }
        }

        // Verifica si el proveedor es 'PAYPHONE' y el mandante del usuario es '8'
        if ($Proveedor->getAbreviado() == 'PAYPHONE' && $Usuario->mandante == '8') {

            // Establece las fechas de inicio y fin para la transacción
            $FromDateLocal = date("Y-m-d 00:00:00");
            $ToDateLocal = date("Y-m-d 23:59:59");
            $rules = [];

            // Agrega reglas de filtrado basadas en el estado del producto y la fecha de creación
            array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));

            array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 100;

            $json = json_encode($filtro);

            $TransaccionProducto = new TransaccionProducto();
            $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $transacciones = json_decode($transacciones);

            if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {

                if (count($transacciones->data) > 1) {
                    throw new Exception("Los usuarios no verificados solo pueden realizar dos (2) transacción por día.", '21021');
                }
            } else {

                if (count($transacciones->data) > 2) {
                    throw new Exception("Solo puedes realizar máximo tres (3) transacciones por día.", '21022');
                }
            }

        }

        if ($Proveedor->getAbreviado() == 'DIRECTA24' && $Usuario->mandante == '8') {

            $FromDateLocal = date("Y-m-d 00:00:00");
            $ToDateLocal = date("Y-m-d 23:59:59");
            $rules = [];

            array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));

            array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 100;

            $json = json_encode($filtro);

            $TransaccionProducto = new TransaccionProducto();
            $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $transacciones = json_decode($transacciones);

            if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {

                if (count($transacciones->data) > 1) {
                    throw new Exception("Los usuarios no verificados solo pueden realizar dos (2) transacción por día.", '21021');
                }
            } else {

                if (count($transacciones->data) > 2) {
                    throw new Exception("Solo puedes realizar máximo tres (3) transacciones por día.", '21022');
                }
            }

        }
        if ($Usuario->usuarioId == '1314023') {

            //cambiar el condicional por 2
            if ($Usuario->mandante == '0') {


                // Se establece la fecha inicial del día actual a las 00:00:00
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");

                $rules = [];

                // Se agrega una regla para filtrar por el estado del producto
                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal", "op" => "le"));
                // array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$fechaFinSemana", "op" => "ge"));
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                // Se crea un filtro con las reglas y la operación de agrupamiento
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);

                // Se instancia la clase TransaccionProducto
                $TransaccionProducto = new TransaccionProducto();
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);

                $diaSemana = date("w");


                // Se calcula el tiempo de inicio de la semana restando el número de días desde el domingo
                $tiempoDeInicioDeSemana = strtotime("-" . $diaSemana . " days");

                $fechaInicioSemana = date("Y-m-d", $tiempoDeInicioDeSemana);

                $tiempoDeFinDeSemana = strtotime("+" . $diaSemana . " days", $tiempoDeInicioDeSemana);

                $fechaFinSemana = date("Y-m-d", $tiempoDeFinDeSemana);

                /**
                 * Se inicializa un array para almacenar las reglas de filtro.
                 */
                $rules = [];

                /**
                 * Se agrega una regla para filtrar por el estado del producto (Activo).
                 */
                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$fechaInicioSemana", "op" => "le"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$fechaFinSemana", "op" => "ge"));
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 100;

                /**
                 * Se convierte el filtro a formato JSON para su uso en la consulta.
                 */
                $json = json_encode($filtro);

                /**
                 * Se crea una instancia de la clase TransaccionProducto
                 * para realizar consultas sobre las transacciones.
                 */
                $TransaccionProducto = new TransaccionProducto();
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);


                if (count($transacciones->data) > 2) {
                    throw new Exception("Solo puedes realizar máximo tres (3) transacciones por día.", '21022');
                }

            }

            // Verifica si el proveedor es 'PAYCIPS' y el mandante es '13'
            if ($Proveedor->getAbreviado() == 'PAYCIPS' && $Usuario->mandante == '13') {

                // Obtiene la fecha y hora inicial y final del día actual
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                // Agrega reglas de filtrado para la consulta de transacciones
                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                // Crea el filtro para la consulta
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 100;

                // Convierte el filtro a formato JSON
                $json = json_encode($filtro);

                // Crea una nueva instancia de TransaccionProducto
                $TransaccionProducto = new TransaccionProducto();
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);

                if (count($transacciones->data) >= 1) {
                    throw new Exception("Los usuarios no verificados solo pueden realizar dos (2) transacción por día.", '21021');
                }

            }


            $FromDateLocal = date("Y-m-d 00:00:00");
            $ToDateLocal = date("Y-m-d 23:59:59");
            $rules = [];

            // Se agregan las reglas de filtrado para la consulta de transacciones.
            array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));

            array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

            // Se agrupa las reglas en un filtro.
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 100;


            // Se codifica el filtro a formato JSON.
            $json = json_encode($filtro);

            $TransaccionProducto = new TransaccionProducto();
            $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $transacciones = json_decode($transacciones);

            try {
                // Se instancia el objeto clasificador.
                $clasificador = new clasificador("", "LIMITEDEPOSITOSEMANAGLOBAL");

                $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->estado);

                $value = $MandanteDetalle->valor;


            } catch (Exception $e) {
                if (floatval($transacciones->data[0]->{'valor'}) >= $value) {
                    throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a $value por día.", '21021');
                }
            }

        }


        switch ($Proveedor->getAbreviado()) {

            case 'MONNET':

                $MONNETSERVICES = new Backend\integrations\payment\MONNETSERVICES();
                $paymentMethodId = "";
                $data = $MONNETSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                $URLREDIRECCION = $data;
                break;


            case 'DIRECTA24':

                $DIRECTA24SERVICES = new Backend\integrations\payment\DIRECTA24SERVICES();

                $data = $DIRECTA24SERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                $URLREDIRECCION = $data;
                break;

            case 'INSWITCH':

                $INSWITCHSERVICES = new Backend\integrations\payment\INSWITCHSERVICES();
                // $data = $INSWITCHSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                switch ($Producto->getExternoId()) {

                    case "VIRTUALACCOUNT":
                        $data = $INSWITCHSERVICES->PaymentMethodsVirtualAccounts($Usuario, $Producto, $amount, $success_url, $fail_url);
                        break;
                    case "INSWITCHTARJETAS":
                        $data = $INSWITCHSERVICES->PaymentMethodsCard($Usuario, $Producto, $amount, $success_url, $fail_url);
                        break;
                    case "INSWITCHPAGOSFISICOS":
                        $data = $INSWITCHSERVICES->PaymentMethodsCash($Usuario, $Producto, $amount, $success_url, $fail_url);
                        /* $data = array();
                         $data["success"] = true;
                         $data["userid"] = $Usuario->usuarioId;
                         $data["amount"] = $amount;
                         $data["dataText"] = 'Paga tu CIP: '.$data2->code;
                         $data["dataImg"] = 'https://cdn.bmpcloud.com/elements/cms/business/bm/images/partners/pagoefectivo.PNG';
                         $data["sid"] = 1;


                         $response = array();
                         $response["code"] = 0;
                         $response["rid"] = $json->rid;

                         $response["data"] = $data;


                         print json_encode($response);

                         exit();*/

                        break;


                    case "BIN_PAY":
                        $data = $INSWITCHSERVICES->PaymentMethodsCrypto($Usuario, $Producto, $amount, $success_url, $fail_url);
                        break;


                    default:
                        $data = $INSWITCHSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                        break;

                }

                $URLREDIRECCION = $data;
                break;


        }


        if ($URLREDIRECCION->success && $URLREDIRECCION != '') {

            if ($URLREDIRECCION->dataImg != "") {

                $response["data"] = $URLREDIRECCION;

            } else if ($URLREDIRECCION->intentId != "" && $URLREDIRECCION->widgetId != "") {

                $response["data"] = array(
                    "result" => 0,
                    "details" => array(
                        "intentId" => $URLREDIRECCION->intentId,
                        "widgetId" => $URLREDIRECCION->widgetId,
                        "method" => ($URLREDIRECCION->method == "" ? "post" : $URLREDIRECCION->method),
                        "fields" => array(
                            array("status" => $status_url),
                            // array("name" => "mtid", "value" => "012020321"),
                            // array("name" => "amount", "value" => "10"),
                            // array("name" => "currenct", "value" => "USD")
                        )
                    )


                );

            } else if ($URLREDIRECCION->transactionId != "" and $URLREDIRECCION != "null") {
                $response["data"] = array(
                    "result" => 0,
                    "details" => array(
                        "transactionId" => $URLREDIRECCION->transactionId,
                        "fields" => array(
                            "status" => true
                        )
                    )
                );
            } else {
                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "";
                $response["ModelErrors"] = [];

                $response["Data"] = array(
                    "action" => $URLREDIRECCION->url,
                    "method" => ($URLREDIRECCION->method == "" ? "post" : $URLREDIRECCION->method),
                    "fields" => array(
                        array("status" => $status_url),
                        // array("name" => "mtid", "value" => "012020321"),
                        // array("name" => "amount", "value" => "10"),
                        // array("name" => "currenct", "value" => "USD")
                    )


                );

                if ($URLREDIRECCION->target != '' && $URLREDIRECCION->target != null) {
                    $response["data"]['target'] = $URLREDIRECCION->target;
                }
            }
        } else {
            throw new Exception("Producto no disponible", '21010');
        }


    } else {
        /**
         * Lanza una excepción cuando un producto no está disponible.
         *
         * @throws Exception Esta excepción se lanza con un mensaje específico
         *                   y un código de error personalizado.
         */
        throw new Exception("Producto no disponible2", '21010');

    }

}
