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
 * Procesa las solicitudes de retiro y genera una respuesta JSON.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param int $json->params->from_date Fecha de inicio del rango de búsqueda.
 * @param int $json->params->to_date Fecha de fin del rango de búsqueda.
 * @param int $json->params->count Número máximo de filas a devolver.
 * @param int $json->params->start Número de filas a omitir.
 * @param string $json->params->State Estado de la solicitud de retiro.
 * @param string $json->params->site_id Identificador del sitio.
 * @param string $json->rid Identificador de la solicitud.
 * @param string $json->params->OrderedItem Elemento ordenado.
 * @return array Respuesta estructurada con las solicitudes de retiro.
 *  -code:int Código de respuesta.
 *  -rid:string Identificador de respuesta.
 *  -data:array Arreglo de solicitudes de retiro.
 *      -result_status:string Estado del resultado.
 *      -withdrawal_requests:array Arreglo de solicitudes de retiro.
 *          -request:array Arreglo de detalles de la solicitud.
 *              -id:int Identificador de la cuenta.
 *              -amount:float Monto de la solicitud.
 *              -date:string Fecha de creación de la solicitud.
 *              -date_payment:string Fecha de pago de la solicitud.
 *              -date_payment2:string Fecha de cambio de la solicitud.
 *              -payment_system_name:string Nombre del sistema de pago.
 *              -tax1:float Impuesto 1.
 *              -tax2:float Impuesto 2.
 *              -final_value:float Valor final después de impuestos.
 *              -status:int Estado de la solicitud.
 *              -detail:string Detalle de la solicitud.
 *  -total_count:int Conteo total de solicitudes.
 */

/* Asignación de usuario y manejo de fecha a partir de parámetros JSON. */
$UsuarioMandante = $UsuarioMandanteSite;
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$ClientId = $UsuarioMandante->getUsuarioMandante();

if ($json->params->from_date != "") {
    $FromDateLocal = date('Y-m-d 00:00:00', $json->params->from_date);

}

/* Verifica si to_date está vacío y asigna una fecha límite a ToDateLocal. */
if ($json->params->to_date != "") {
    $ToDateLocal = date('Y-m-d 23:59:59', $json->params->to_date);

}

$OrderedItem = $params->OrderedItem;


/* define variables para paginación basada en parámetros JSON. */
$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;

$State = $json->params->State;
$Site = $json->params->site_id;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}
if ($SkeepRows < 0) {
    $SkeepRows = 0;
}


/* inicializa variables si no contienen valores asignados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* crea una regla de validación para un campo específico en un array. */
$rules = [];
array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => "$ClientId", "op" => "eq"));


/* cambia valores de estado según el valor de la variable $State. */
switch ($State) {
    case "-1":
        $State = 'E';
        break;
    case "0":
        $State = 'A';
        break;
    case "2":
        $State = 'P';
        break;
    case "3":
        $State = 'I';
        break;
    case "4":
        $State = 'R';
        break;
    case "5":
        $State = 'M';
        break;
}


/* asigna un estado basado en el valor de `$State`. */
$Status = '';

switch ($State) {
    case "-1":
        $Status = 'E';
        break;
    case "0":
        $Status = 'A';
        break;
    case "2":
        $Status = 'P';
        break;
    case "3":
        $Status = 'I';
        break;
    case "4":
        $Status = 'R';
        break;
}


/* Añade reglas de fecha a un arreglo si las fechas no están vacías. */
if ($FromDateLocal != "") {
    array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
}
if ($ToDateLocal != "") {
    array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}

/* agrega reglas a un array según el estado de 'cuenta_cobro'. */
if ($State != "" && $State != 'A') {
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "$State", "op" => "eq"));
}
if ($State == "A") {
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'A','P','M'", "op" => "in"));

}

/* Añade reglas según el estado y condición del status en cobro. */
if ($State == "R") {
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'R','D'", "op" => "in"));

}
if ($Status != "" && $Status != 'A') {
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "$Status", "op" => "eq"));
}

/* Agrega reglas basadas en el estado, permitiendo diferentes valores según la condición. */
if ($Status == "A") {
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'A','P','M'", "op" => "in"));

}
if ($Status == "R") {
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'R','D'", "op" => "in"));

}


/* Se crea un filtro JSON y se obtienen cuentas de cobro personalizadas. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$CuentaCobro = new CuentaCobro();

$cuentas = $CuentaCobro->getCuentasCobroCustom(" cuenta_cobro.version,cuenta_cobro.fecha_cambio,cuenta_cobro.mediopago_id,cuenta_cobro.cuenta_id,cuenta_cobro.estado,cuenta_cobro.valor,cuenta_cobro.fecha_crea,cuenta_cobro.fecha_pago, cuenta_cobro.mensaje_usuario, cuenta_cobro.impuesto, cuenta_cobro.impuesto2, usuario_banco.tipo_cuenta, cripto_red.criptored_id", "cuenta_cobro.cuenta_id", "desc", $SkeepRows, $MaxRows, $json2, true, "cuenta_cobro.cuenta_id");


/* decodifica un JSON y prepara un array para almacenar datos. */
$cuentas = json_decode($cuentas);

$cuentasData = array();

foreach ($cuentas->data as $key => $value) {


    /* Asigna "Local", "Cuenta Bancaria" o "Criptomoneda" según condiciones del medio de pago. */
    if ($value->{"cuenta_cobro.mediopago_id"} == "0" || ($value->{"cuenta_cobro.mediopago_id"} != "0" && $value->{"cuenta_cobro.version"} == "2")) {
        $payment_system_name = "Local";
    } elseif ($value->{"usuario_banco.tipo_cuenta"} == "Digital") {
      $payment_system_name = "Cuenta Digital";
    } elseif ($value->{"cripto_red.criptored_id"} != null) {
        $payment_system_name = "Criptomoneda";
    }else {
        $payment_system_name = "Cuenta Bancaria";
    }


    /* traduce nombres de sistema de pago si el idioma es inglés. */
    if (strtolower($Usuario->idioma) == 'en') {
        $payment_system_name = str_replace("Local", "Betshop", $payment_system_name);
        $payment_system_name = str_replace("Cuenta Bancaria", "Bank account", $payment_system_name);
    }

    $arraybet = array();

    /* Asigna valores a un array desde un objeto, incluyendo detalles de cobro y pagos. */
    $arraybet["id"] = ($value->{"cuenta_cobro.cuenta_id"});
    $arraybet["amount"] = ($value->{"cuenta_cobro.valor"});
    $arraybet["date"] = ($value->{"cuenta_cobro.fecha_crea"});
    $arraybet["date_payment"] = ($value->{"cuenta_cobro.fecha_pago"});
    $arraybet["date_payment2"] = ($value->{"cuenta_cobro.fecha_cambio"});
    $arraybet["payment_system_name"] = $payment_system_name;

    /* Se calculan impuestos y valor final, ajustando estado según condiciones específicas. */
    $arraybet['tax1'] = ($value->{'cuenta_cobro.impuesto'} ?: 0); //Retornar este campo en PROD
    $arraybet['tax2'] = ($value->{'cuenta_cobro.impuesto2'} ?: 0); //Retornar este campo en PROD
    $arraybet["final_value"] = ($arraybet["amount"]) - $arraybet['tax1'] - $arraybet['tax2'];

    if ($value->{"cuenta_cobro.estado"} == "E" || $value->{"cuenta_cobro.estado"} == "D") {
        $arraybet["status"] = -1;

    } elseif ($value->{"cuenta_cobro.estado"} == "A" || $value->{"cuenta_cobro.estado"} == "M") {
        /* Condicional que verifica el estado de "cuenta_cobro" si es "A" o "M". */

        /* asigna un estado a un arreglo basado en una condición específica. */
        $arraybet["status"] = 0;

    } elseif ($value->{"cuenta_cobro.estado"} == "P") {
        $arraybet["status"] = 1;

    } elseif ($value->{"cuenta_cobro.estado"} == "X" || $value->{"cuenta_cobro.estado"} == "S") {

        /* asigna un estado específico a un arreglo según condiciones. */
        $arraybet["status"] = 2;

    } elseif ($value->{"cuenta_cobro.estado"} == "I") {
        $arraybet["status"] = 3;

    } elseif ($value->{"cuenta_cobro.estado"} == "R") {

        /* asigna un estado y comentario a un array, luego lo agrega a otra lista. */
        $arraybet["status"] = 4;
    }

    if (isset($value->{'transprod_log.comentario'})) $arraybet['detail'] = $value->{'transprod_log.comentario'};

    array_push($cuentasData, $arraybet);


}


/* crea una respuesta JSON con información de solicitudes de retiro. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result_status" => "OK",
    "withdrawal_requests" => array("request" => $cuentasData
    )


);


/* Asigna el conteo de cuentas a la variable total_count en un arreglo. */
$response["total_count"] = $cuentas->count[0]->{".count"};;

