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
use Backend\dto\UsuarioHistorial;
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
 * Código para procesar parámetros desde un objeto JSON y construir reglas de filtrado.
 * 
 * Se extraen los valores de una sesión de usuario y se construyen condiciones basadas 
 * en fechas y tipos específicos para filtrar registros de historial de usuario.
 *
 * @param int $json->params->count
 * @param int $json->params->start
 * @param string $json->params->where->type
 * @param int $json->params->where->product
 *
 * @return array $response
 *  -  code : int
 *  -  rid : string
 *  -  data->history : array
 *  -  data->total_count : int
 */
$MaxRows = $json->params->count; // Número máximo de filas a retornar
$SkeepRows = $json->params->start; // Número de filas a omitir
$grouping = ""; // Agrupación por defecto
exit(); // Salida inmediata del script
$UsuarioMandante = new UsuarioMandante($json->session->usuario); // Inicialización de un objeto UsuarioMandante

// Comprobación y formato de la fecha desde
if ($json->params->where->from_date != "") {
    $FromDateLocal = date('Y-m-d 00:00:00', $json->params->where->from_date);

}

// Comprobación y formato de la fecha hasta
if ($json->params->where->to_date != "") {
    $ToDateLocal = date('Y-m-d 23:59:59', $json->params->where->to_date);

}

$type = $json->params->where->type; // Tipo de registro a filtrar
$product = $json->params->where->product; // Producto a filtrar

$rules = []; // Arreglo para almacenar las reglas de filtrado

// Agregar regla si se proporciona la fecha desde
if ($FromDateLocal != "") {
    array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
}

// Agregar regla según tipo
if($type == 8){

    array_push($rules, array("field" => "usuario_historial.tipo", "data" => "40,41", "op" => "in"));
}
if($type == 3 ){

    array_push($rules, array("field" => "usuario_historial.tipo", "data" => "10", "op" => "in"));
}
if($type == 1){

    array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30 ", "op" => "eq"));
}

// Agregar regla si se proporciona la fecha hasta
if ($ToDateLocal != "") {
    array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}

// Verifica si la variable $type es un valor numérico
if(is_numeric($type)){

    switch ($type){

        // Este bloque de código agrega reglas a un array $rules basado en el valor de $product.
        // Primero, siempre agrega una regla para el campo "usuario_historial.movimiento" con el valor "S" y la operación "eq".
        // Luego, dependiendo del valor de $product, agrega una regla adicional para el campo "usuario_historial.tipo":
        // - Si $product es '0', agrega una regla con el valor "20" y la operación "eq".
        // - Si $product es '1', agrega una regla con el valor "30" y la operación "eq".
        // - Para cualquier otro valor de $product, agrega una regla con los valores "20,30" y la operación "in".
        case 0:
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));

            if($product == '0'){
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20", "op" => "eq"));

            }elseif($product == '1'){
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30", "op" => "eq"));

            }else{
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20,30", "op" => "in"));

            }

            break;

        case 1:
            // Agrega una regla para el movimiento 'E' y filtra por tipo de producto
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));

            if($product == '0'){
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20", "op" => "eq"));

            }elseif($product == '1'){
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30", "op" => "eq"));

            }else{
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20,30", "op" => "in"));

            }

            break;

        // Este caso maneja el historial de balance para un tipo de producto específico.
        // Agrega reglas para filtrar la tabla 'usuario_historial' basándose en los campos 'movimiento' y 'tipo'.
        // Si el producto es '0', filtra por 'tipo' 20.
        // Si el producto es '1', filtra por 'tipo' 30.
        // De lo contrario, filtra por 'tipo' 20 o 30.
        case 2:
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "C", "op" => "eq"));

            if($product == '0'){
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20", "op" => "eq"));

            }elseif($product == '1'){
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30", "op" => "eq"));

            }else{
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20,30", "op" => "in"));

            }

            break;

        case 3:
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "10", "op" => "eq"));


            break;

        case 5:
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "50", "op" => "eq"));


            break;

        case 8:
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "40", "op" => "eq"));


            break;

        case 9:
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "15", "op" => "eq"));
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));


            break;

        case 301:
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "15", "op" => "eq"));
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));


            break;

        case 12:
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "40", "op" => "eq"));
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));


            break;


        case 14:
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "40", "op" => "eq"));
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "'C','E'", "op" => "in"));


            break;

        case 15:
            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "41", "op" => "eq"));


            break;
    }

}else{
    if($product == '0'){
        array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20", "op" => "eq"));

    }elseif($product == '1'){
        array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30", "op" => "eq"));

    }
}

// Se agrega una nueva regla al array de reglas, especificando el campo, los datos y la operación a realizar
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

// Se define un filtro que contiene las reglas y la operación lógica
$filtro = array("rules" => $rules, "groupOp" => "AND");
// Se codifica el filtro a formato JSON
$jsonfiltro = json_encode($filtro);

// Se ha comentado la respuesta que contenía un código, un identificador y datos de transacciones
// $response = array("code" => 0, "rid" => "150630776768211", "data" => array("withdraw" => ["moneta", "netellernew", "gateway", "ecocard", "astropay", "astropaydirect", "cubits", "wirecardnew", "skrill", "moneybookers"], "deposit" => ["moneta", "netellernew", "gateway", "ecocard", "astropay", "astropaystreamline1", "astropaystreamline2", "astropaystreamline3", "astropaystreamline4", "astropaystreamline5", "astropaystreamline6", "astropaystreamline7", "astropaystreamline8", "astropaystreamline9", "astropaystreamline10", "astropaystreamline11", "astropaystreamline12", "astropaystreamline13", "astropaystreamline14", "cubits", "paysafecard", "wirecard", "skrill", "moneybookers", "yandex", "yandexbank", "yandexcash", "yandexinvois", "yandexprbank", "yandexsberbank", "pugglepay"]));

// Se establece la selección de campos para la consulta
$select = "usuario_historial.*,usuario.nombre ";

// Se crea una instancia de la clase UsuarioHistorial
$UsuarioHistorial = new UsuarioHistorial();
// Se obtienen los historiales de usuario personalizados con la selección, la orden y el filtro especificados
$data = $UsuarioHistorial->getUsuarioHistorialsCustom($select, "usuario_historial.usuhistorial_id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, true, $grouping);
// Se decodifica la respuesta JSON en un objeto PHP
$movimientos = json_decode($data);
// Se inicializa un array para almacenar los movimientos
$movimientosData = array();
foreach ($movimientos->data as $key => $value) {

    /*
       '0': 'New Bets',
      '1': 'Winning Bets',
      '2': 'Returned Bet',
      '3': 'Deposit',
      '4': 'Card Deposit',
      '5': 'Bonus',
      '6': 'Bonus Bet',
      '7': 'Commission',
      '8': 'Withdrawal',
      '9': 'Correction Up',
      '302': 'Correction Down',
      '10': 'Deposit by payment system',
      '12': 'Withdrawal request',
      '13': 'Authorized Withdrawal',
      '14': 'Withdrawal denied',
      '15': 'Withdrawal paid',
      '16': 'Pool Bet',
      '17': 'Pool Bet Win',
      '18': 'Pool Bet Return',
      '23': 'In the process of revision',
      '24': 'Removed for recalculation',
      '29': 'Free Bet Bonus received',
      '30': 'Wagering Bonus received',
      '31': 'Transfer from Gaming Wallet',
      '32': 'Transfer to Gaming Wallet',
      '37': 'Declined Superbet',
      '39': 'Bet on hold',
      '40': 'Bet cashout',
      '19': 'Fair',
      '20': 'Fair Win',
      '21': 'Fair Commission'


 */

    $array = array();
    $array["operation_name"] = "Movimiento";
    /**
     * Este bloque de código evalúa el valor de movimiento del usuario
     * y asigna operaciones específicas a un arreglo basado en ese valor.
     *
     * Se manejan diferentes casos (10, 15 y 20) que corresponden
     * a distintas acciones y resultados en el historial del usuario.
     *
     */
    switch ($value-> {"usuario_historial.tipo"}) {

        case "10":
            if ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 3;
                $array["operation_name"]="Deposito";
            } else {
                $array["operation"] = 3;
                $array["operation_name"]="Cancelacion Deposito";
            }

            break;

        case "15":
            if ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 9;
                $array["operation_name"]="Corrección";

            } else {
                $array["operation"] = 302;
                $array["operation_name"]="Corrección";

            }

            break;

        case "20":
            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $array["operation"] = 0;
                $array["operation_name"]="Apuesta Deportiva";

            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 1;
                $array["operation_name"]="Ganancia Deportiva";
            } else {
                $array["operation"] = 2;
                $array["operation_name"]="Cancelacion Deportiva";
            }

            break;

        case "30":
            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $array["operation"] = 0;
                $array["operation_name"]="Apuesta Casino";
            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 1;
                $array["operation_name"]="Ganancia Casino";
            } else {
                $array["operation"] = 2;
                $array["operation_name"]="Cancelación Deportiva";
            }


            break;

        case "40":
            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $array["operation"] = 12;
                $array["operation_name"]="Retiro Creado";
            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 14;
                $array["operation_name"]="Retiro Cancelado";
            } else {
                $array["operation"] = 14;
                $array["operation_name"]="Retiro Cancelado";
            }


            break;

        case "50":
            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $array["operation"] = 5;
                $array["operation_name"]="Bono";
            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
            }


            break;
    }

    /**
     * Asigna valores a un array asociativo con información de una transacción.
     *
     * @var array $array Array que contendrá los datos de la transacción.
     * @var float $array ["amount"] Monto de la transacción extraído de usuario_historial.valor.
     * @var float $array ["balance"] Saldo actual extraído de usuario_historial.valor.
     * @var string $array ["date_time"] Fecha y hora de creación de la transacción extraída de usuario_historial.fecha_crea.
     * @var int $array ["product_category"] Categoría del producto, por defecto 0.
     * @var string $array ["transaction_id"] ID externo de la transacción extraído de usuario_historial.externo_id.
     */
    $array["amount"] = ($value->{"usuario_historial.valor"});
    $array["balance"] = ($value->{"usuario_historial.valor"});
    $array["date_time"] = ($value->{"usuario_historial.fecha_crea"});
    $array["product_category"] = 0;
    $array["transaction_id"]  = $value->{"usuario_historial.externo_id"};

    array_push($movimientosData, $array);


}


    if (false) {
        /**
         * Obtiene el tipo de movimiento del objeto JSON.
         * Crea una instancia de UsuarioMandante utilizando el usuario de la sesión.
         * Obtiene el ID del usuario mandante.
         * Crea una instancia de Usuario utilizando el ClientId obtenido.
         * Obtiene un resumen de los movimientos para el usuario.
         * Decodifica los movimientos de formato JSON a un objeto PHP.
         * Inicializa un array para almacenar los datos de movimientos.
         */
        $type = $json->params->where->type;
        $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $ClientId = $UsuarioMandante->getUsuarioMandante();

    $Usuario = new Usuario($ClientId);
    $movimientos = $Usuario->getMovimientosResume("", "", $type);

    $movimientos = json_decode($movimientos);

    $movimientosData = array();

    foreach ($movimientos->data as $key => $value) {

        /*
           '0': 'New Bets',
          '1': 'Winning Bets',
          '2': 'Returned Bet',
          '3': 'Deposit',
          '4': 'Card Deposit',
          '5': 'Bonus',
          '6': 'Bonus Bet',
          '7': 'Commission',
          '8': 'Withdrawal',
          '9': 'Correction Up',
          '302': 'Correction Down',
          '10': 'Deposit by payment system',
          '12': 'Withdrawal request',
          '13': 'Authorized Withdrawal',
          '14': 'Withdrawal denied',
          '15': 'Withdrawal paid',
          '16': 'Pool Bet',
          '17': 'Pool Bet Win',
          '18': 'Pool Bet Return',
          '23': 'In the process of revision',
          '24': 'Removed for recalculation',
          '29': 'Free Bet Bonus received',
          '30': 'Wagering Bonus received',
          '31': 'Transfer from Gaming Wallet',
          '32': 'Transfer to Gaming Wallet',
          '37': 'Declined Superbet',
          '39': 'Bet on hold',
          '40': 'Bet cashout',
          '19': 'Fair',
          '20': 'Fair Win',
          '21': 'Fair Commission'


     */

        $array = array();

                        /**
                 * Asigna un valor específico a la clave "operation" del array $array
                 * basado en el tipo de operación.
                 *
                 * Las operaciones disponibles son:
                 * - DEBIT: 0
                 * - CREDIT: 1
                 * - ROLLBACK: 2
                 * - BET: 0
                 * - STAKEDECREASE: 2
                 * - REFUND: 2
                 * - WIN: 1
                 * - NEWCREDIT: 2
                 * - CASHOUT: 40
                 * - NEWDEBIT: 2
                 * - Apuestas: 0
                 * - Ganadoras: 1
                 */
            switch ($value->{"movimientos.tipo"}) {

                case "DEBIT":
                    $array["operation"] = 0;
                break;

            case "CREDIT":
                $array["operation"] = 1;
                break;

            case "ROLLBACK":
                $array["operation"] = 2;
                break;

            case "BET":
                $array["operation"] = 0;
                break;

            case "STAKEDECREASE":
                $array["operation"] = 2;
                break;

            case "REFUND":
                $array["operation"] = 2;
                break;

            case "WIN":
                $array["operation"] = 1;
                break;

            case "NEWCREDIT":
                $array["operation"] = 2;
                break;

            case "CASHOUT":
                $array["operation"] = 40;
                break;

            case "NEWDEBIT":
                $array["operation"] = 2;
                break;

            case "Apuestas":
                $array["operation"] = 0;

                break;

            case "Ganadoras":
                $array["operation"] = 1;

                break;

            case "Depositos":
                $array["operation"] = 3;

                break;
            case "Retiros":
                $array["operation"] = 15;

                break;

            case "RetirosPendientes":
                $array["operation"] = 12;

                break;
        }
        /**
        * Se construye un array con información sobre una transacción.
        *
        * @var array $array Almacena los datos de la transacción.
        * @var array $movimientosData Array donde se agregan las transacciones.
        * @var object $value Objeto que contiene la información de los movimientos.
        * @var mixed $ClientId Identificador del cliente asociado a la transacción.
        */
        $array["amount"] = ($value->{"movimientos.valor"});
        $array["balance"] = ($value->{"movimientos.valor"});
        $array["date_time"] = ($value->{"movimientos.fecha"});
        $array["operation_name"] = ($value->{"movimientos.tipo"});
        $array["product_category"] = 0;
        $array["transaction_id"] = 0;
        $array["transaction_id2"] = $ClientId;

        array_push($movimientosData, $array);


    }


}

/**
 * Respuesta estructurada para la solicitud.
 *
 * @var array $response Estructura de respuesta que contiene:
 *     - int $response["code"] Código de estado de la respuesta.
 *     - mixed $response["rid"] Identificador de la solicitud.
 *     - array $response["data"] Datos adicionales:
 *         - array $response["data"]["history"] Historial de movimientos.
 *         - int $response["data"]["total_count"] Conteo total de movimientos.
 *     - int $response["total_count"] Conteo total de movimientos.
 */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "history" => $movimientosData,
    "total_count" => $movimientos->count[0]->{".count"}


);

$response["total_count"] = $movimientos->count[0]->{".count"};;


