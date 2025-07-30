<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
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
use Backend\dto\UsuarioInformacion;
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
 * Este script PHP se encarga de gestionar la obtención de productos de pago para un usuario específico en un sistema de gestión de pagos.
 * 
 * @param object $params Parámetros de entrada que incluyen el ID del sitio y si es un popup.
 * @param int $params->site_id ID del sitio para el cual se obtienen los productos de pago.
 * @param bool $params->is_popup Indica si la solicitud es para un popup.
 * 
 * @return object $response Respuesta que contiene los productos de pago disponibles para el usuario.
 *  -bool HasError Indica si hubo un error en la ejecución.
 *  -string AlertType Tipo de alerta (error o éxito).
 *  -string Message Mensaje de error o éxito.
 *  -array Data Datos de los productos de pago disponibles.
 */


/* Se crean instancias de UsuarioMandante y Usuario utilizando datos de sesión y parámetros. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
//$Pais = new Pais($Usuario->paisId);

$site_id = $params->site_id;

/* convierte el ID de sitio a minúsculas y define parámetros iniciales. */
$site_id = strtolower($site_id);

$is_popup = !empty($params->is_popup) ? $params->is_popup : false;
$MaxRows = 1000;
$OrderedItem = 1;
$SkeepRows = 0;


/* Define reglas para validar el estado de productos en un sistema. */
$configured = false;
$isConfigurable = false;

$rules = [];
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));

/* Agrega condiciones a un arreglo de reglas basado en el estado del proveedor y usuario. */
array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));
if ($Usuario->mandante == '2') {
    array_push($rules, array("field" => "proveedor.abreviado", "data" => "SAGICOR", "op" => "ne"));
}


/* Condicional que modifica reglas según el estado del usuario y el mandante. */
if ($Usuario->test == 'S') {

} else {
    if ($Usuario->mandante == '0') {
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "45,41,50,87,94,135,148,106,174", "op" => "ni"));
        array_push($rules, array("field" => "producto.producto_id", "data" => "6107,6110,6113,6116,6119,6122,6125,6128,6131,6134,6137,6140,6143,6146,6170,6173,6176,6179,6182,6185,6188,6191,6194,6197,6200,7960,7963,7966,7969,7972,8823,8826,8829,15154,15151,15148,15145", "op" => "ni"));
    }
    if ($Usuario->mandante == '8') {
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "87,94,155,174,90,185,100", "op" => "ni"));
        array_push($rules, array("field" => "producto.producto_id", "data" => "6107,6110,6113,6116,6119,6122,6125,6128,6131,6137,6143,6146,6170,6173,6179,6182,6185,6188,6191,6194,6197,6200,7960,7963,7966,7969,7972,8829,12254", "op" => "ni"));
    }

}

/* Condiciona reglas según si es criptomoneda o no para el proveedor. */
if ($site_id == 8) {
    $is_crypto = $params->is_crypto;

    if ($is_crypto == true) {
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "135", "op" => "in"));

    } else {
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "135", "op" => "ni"));

    }

}

/* Verifica si el usuario está en una lista y agrega reglas a un filtro. */
if (in_array($Usuario->usuarioId, array('315236', '94415', '223641', '232085', 215760, 246814, 262771))) {
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "8,100", "op" => "ni"));

}
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


/* Se crea un objeto ProductoMandante y se obtienen productos según país. */
$ProductoMandante = new ProductoMandante();

$ProductoMandantes = $ProductoMandante->getProductosMandantePaisCustom(" proveedor.proveedor_id,proveedor.imagen,producto.producto_id, producto.es_configurable, producto.descripcion ,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.min ELSE producto_mandante.min END min,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.max ELSE producto_mandante.max END max,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.tiempo_procesamiento ELSE producto_mandante.tiempo_procesamiento END tiempo, producto.image_url imagen,producto.orden,producto_mandante.min,producto_mandante.max,prodmandante_pais.min,prodmandante_pais.max ", "producto_mandante.orden", "DESC,producto.descripcion", $SkeepRows, $MaxRows, $json2, true, $Usuario->paisId);

$ProductoMandantes = json_decode($ProductoMandantes);

$rules = [];


/* Se definen reglas de filtrado y se convierten a formato JSON. */
array_push($rules, ['field' => 'categoria_mandante.tipo', 'data' => 'PAYMENT', 'op' => 'eq']);
array_push($rules, ['field' => 'categoria_producto.mandante', 'data' => $Usuario->mandante, 'op' => 'eq']);
array_push($rules, ['field' => 'categoria_producto.pais_id', 'data' => $Usuario->paisId, 'op' => 'eq']);
array_push($rules, ['field' => 'categoria_producto.estado', 'data' => 'A', 'op' => 'eq']);

$filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);


/* Se obtienen categorías de productos y se decodifican en formato JSON. */
$CategoriaProducto = new CategoriaProducto();
$CategoriaProductos = $CategoriaProducto->getCategoriaProductosMandanteCustom('categoria_mandante.descripcion, categoria_producto.producto_id', 'categoria_producto.catprod_id', 'ASC', $SkeepRows, $MaxRows, $filters, true);
$CategoriaProductos = json_decode($CategoriaProductos, true);

$minDeposit = 0;
$maxDeposit = 0;


/* Crea un clasificador y obtiene un valor de mandato con manejo de excepciones. */
try {
    $Clasificador = new Clasificador('', 'MINDEPOSIT');
    $MandateDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->clasificadorId, $UsuarioMandante->paisId, 'A');
    $minDeposit = $MandateDetalle->getValor();
} catch (Exception $ex) {
}


/* crea un clasificador y obtiene un valor de mandato, manejando excepciones. */
try {
    $Clasificador = new Clasificador('', 'MAXDEPOSIT');
    $MandateDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, 'A');
    $maxDeposit = $MandateDetalle->getValor();
} catch (Exception $ex) {
}



/* Se asigna moneda y se crean columnas para productos, considerando un posible popup. */
$moneda = $Usuario->moneda;

$columns = array_column($CategoriaProductos['data'], 'categoria_producto.producto_id');

$ProductoMandantesData = $is_popup ? [
    'todos' => [
        'length' => 0,
        'data' => []
    ]
] : [];

foreach ($ProductoMandantes->data as $key => $value) {

    /*$array= array();
    $array["name"] = ($value->{"producto.producto_id"});
    $array["displayName"] = ($value->{"producto.descripcion"});
    $array["canDeposit"] = true;
    $array["canWithdraw"] = false;
    $array["order"] = ($value->{"producto.orden"});
    $array["depositInfoTextKey"] = '';
    $array["stayInSameTabOnDeposit"] = true;
    $array["depositFormFields"] = array();
    $array["predefinedFields"] = array();
    $array["predefinedFields"]['1tap']= true;
    $array["info"] = array();
    $array["info"][$moneda] = array();
    $array["info"][$moneda]["depositFee"] = 0;
    $array["info"][$moneda]["withdrawFee"] = 0;
    $array["info"][$moneda]["depositProcessTime"] = $value->{".tiempo"};
    $array["info"][$moneda]["withdrawProcessTime"] = $value->{".tiempo"};
    $array["info"][$moneda]["minDeposit"] = $value->{".min"};
    $array["info"][$moneda]["maxDeposit"] = $value->{".max"};
    $array["info"][$moneda]["minWithdraw"] = $value->{".min"};
    $array["info"][$moneda]["maxWithdraw"] = $value->{".max"};
*/

    /* Cálculo de valores mínimo y máximo basado en condiciones de producto. */
    $min = ($value->{"prodmandante_pais.min"} !== '' && ($value->{"prodmandante_pais.min"} > $value->{"producto_mandante.min"}) && ($value->{"prodmandante_pais.min"} > $value->{"producto.min"})) ? $value->{"prodmandante_pais.min"} : (($value->{"producto_mandante.min"} != '' && $value->{"producto_mandante.min"} > $value->{"producto.min"}) ? $value->{"producto_mandante.min"} : (($value->{"producto.min"} != '') ? $value->{"producto.min"} : 0));
    $max = $value->{".max"} ?: 0;
// $max = number_format($value->{"prodmandante_pais.max"} !== '' && $value->{"prodmandante_pais.max"} !== '0' ? $value->{"prodmandante_pais.max"} : (($value->{"producto_mandante.max"} != '' && $value->{"producto_mandante.max"} < $value->{"producto.max"}) ? $value->{"producto_mandante.max"} : (($value->{"producto.max"} != '') ? $value->{"producto.max"} : 0)), 0);


    $isConfigurable = $value->{"producto.es_configurable"};


    /* establece el valor de la variable $isConfigurable y crea un arreglo vacío. */
    if ($isConfigurable) {
        $isConfigurable = true;
    } elseif (!$isConfigurable) {
        $isConfigurable = false;
    }


    $lista_temp = [];

    /* Asigna propiedades de un objeto a un arreglo temporal en PHP. */
    $lista_temp['id'] = $value->{"producto.producto_id"};
    $lista_temp['nombre'] = $value->{"producto.descripcion"};
    $lista_temp['imagen'] = $value->{"producto.imagen"};
    $lista_temp['pasarela'] = $value->{"producto.imagen"};
    $lista_temp['tiempo'] = $value->{"producto.tiempo"};
    $lista_temp['pasarela'] = $value->{"producto.imagen"};

    /* Configura parámetros para la pasarela de pago según el proveedor específico. */
    $lista_temp['isConfigurable'] = $isConfigurable; //Determinar si la pasarela de pago es configurable


    $Proveedor = new Proveedor($value->{"proveedor.proveedor_id"});


    if ($Proveedor->abreviado == "PAGADITO") {

        $lista_temp['popupTarjeta'] = true;
        $lista_temp['requireAuth'] = true;
        $lista_temp['is_crypto'] = false;
    }                   /*   elseif($Proveedor->abreviado == "N1CO"){
$lista_temp['popupTarjeta'] = true;
$lista_temp['saveCard'] = true;
$lista_temp['is_crypto'] = false;
}*/

    elseif ($Proveedor->abreviado == "VISANET") {
        /* Configura opciones para el proveedor VISANET en una lista temporal. */

        $lista_temp['popupTarjeta'] = true;
        $lista_temp['requireAuth'] = false;
        $lista_temp['is_crypto'] = false;
        $lista_temp['saveCard'] = true;
    } elseif ($Proveedor->abreviado == "TOTALPAGO") {
        /* Activa un popup si el proveedor es "TOTALPAGO". */

        $lista_temp['popupTransfer'] = true;
    } else {
        /* asigna falso a 'popupTarjeta' si no se cumple una condición previa. */

        $lista_temp['popupTarjeta'] = false;
    }


    if ($Proveedor->abreviado == "FRI") {
        try {



            /* Instancia y obtiene información de clasificador y usuario mediante objetos en PHP. */
            $Clasificador = new Clasificador('', 'FRIUSERNAME');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioInformacion = new UsuarioInformacion('', $ClasificadorId, $Usuario->usuarioId, "", $Usuario->mandante);


            $Clasificador = new Clasificador('', 'FRIPHONE');

            /* Se obtienen datos de un clasificador y se inicializan variables de usuario. */
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioInformacion2 = new UsuarioInformacion('', $ClasificadorId, $Usuario->usuarioId, "", $Usuario->mandante);


            $FriUsername = $UsuarioInformacion->valor;
            $FriPhoneNumber = $UsuarioInformacion2->valor;



            /* Verifica si se ingresó un nombre de usuario o número de teléfono. */
            if (($FriUsername !== "" && $FriUsername !== null) || ($FriPhoneNumber !== "" && $FriPhoneNumber !== null)) {
                $configured = true;
            } else {

                $configured = false;
            }


        } catch (Exception $ex) {
            /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */

        }


        /* Se asigna el estado de configuración de la pasarela de pago a un array. */
        $lista_temp['configured'] = $configured; //Determina si la pasarela de pago ha sido configurada por el usuario.

    }



    /* Condición que verifica el usuario y establece un valor máximo si no cumple criterios. */
    if ($Usuario->mandante == '8' && $lista_temp['id'] == '11274') {

        if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {
            $max = 100;

        }

    }


    /* establece valores formateados y comisión específica para un ID determinado. */
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));

    if ($lista_temp['id'] == '10312') {
        $lista_temp['comision'] = '3.5%';
    }


    /* Asigna valores a 'valor' según la moneda del usuario y condiciones específicas. */
    $lista_temp['valor'] = 50;

    if ($Usuario->moneda == 'MXN') {
        $lista_temp['valor'] = 100;
    }

    if ($Usuario->moneda == 'CRC' && $lista_temp['id'] == '11852') {
        $lista_temp['valor'] = 5000;
    }


    /* asigna valores a variables según condiciones específicas relacionadas con moneda e ID. */
    if ($Usuario->moneda == 'CLP') {
        $lista_temp['valor'] = 9000;
    }

    $lista_temp['pasarela'] = $value->{"proveedor.imagen"};


    if ($lista_temp['id'] == "8811") {
        $lista_temp['pasarela'] = "https://images.virtualsoft.tech/productos/payment/icon/color/2logoBbva.png";

    }



    /* Asigna una URL de imagen o vacía según condiciones específicas del usuario y proveedor. */
    if ($lista_temp['id'] == "8820") {
        $lista_temp['pasarela'] = "https://images.virtualsoft.tech/productos/payment/icon/color/5logoInterbank.png";

    }
    if ($Usuario->mandante == '8' && $value->{"proveedor.proveedor_id"} == '74') {
        $lista_temp['pasarela'] = "";
    }


    /* Asigna diferentes imágenes según el valor del ID en la lista temporal. */
    if ($lista_temp['id'] == "9313") {
        $lista_temp['pasarela'] = "https://images.virtualsoft.tech/m/msjT1607693348.png";

    }
    if ($lista_temp['id'] == "12254") {
        $lista_temp['pasarela'] = "https://images.virtualsoft.tech/productos/payment/icon/pagoefectivo2.png";

    }

    /* Asigna imagen y valor según condiciones de 'id' y propiedades del usuario. */
    if ($lista_temp['id'] == "24" && $Usuario->paisId == 60) {
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1634313003.png";
        $lista_temp['valor'] = 10000;

    }
    if ($lista_temp['id'] == "9400" && $Usuario->mandante == 13) {
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1636121313.png";
        $lista_temp['valor'] = 100;

    }

    /* Asigna imagen y valor a lista_temp si cumple condiciones específicas. */
    if ($lista_temp['id'] == "9403" && $Usuario->mandante == 13) {
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1636120861.png";
        $lista_temp['valor'] = 100;

    }


    /* organiza productos en categorías basadas en condiciones específicas. */
    if ($is_popup) {
        $search = array_search($value->{'producto.producto_id'}, $columns);

        if ($search) {
            $category = $CategoriaProductos['data'][$search]['categoria_mandante.descripcion'];
            if (!isset($ProductoMandantesData[$category])) $ProductoMandantesData[$category] = [
                'length' => 0,
                'data' => []
            ];

            $ProductoMandantesData[$category]['length']++;
            array_push($ProductoMandantesData[$category]['data'], $lista_temp);
        }

        $ProductoMandantesData['todos']['length']++;
        array_push($ProductoMandantesData['todos']['data'], $lista_temp);

    } else array_push($ProductoMandantesData, $lista_temp);
}
if ($Usuario->mandante == '13') {


    /* Código PHP que inicializa valores mínimos, máximos y datos de una lista. */
    $min = 70;
    $max = 50000;

    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCOMX1";
    $lista_temp['moneda'] = "MXN";

    /* Se define un array con detalles de una recarga, incluyendo valores y parámetros. */
    $lista_temp['nombre'] = "Recarga Via SPEI o BBVA Bancomer";
    $lista_temp['valor'] = 100;
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1636497800.png";
    $lista_temp['tiempo'] = "Tiempo normal";

    /* Se asignan valores a un array relacionado con pagos y configuraciones. */
    $lista_temp['pasarela'] = "https://images.virtualsoft.tech/productos/PaycipsT1605914194.png";
    $lista_temp['pasarelatxt'] = "SPEI o BBVA Bancomer";
    $lista_temp['isInfoText'] = "Enviar";
    $lista_temp['isInfo'] = false;
    $lista_temp['pasarela'] = "https://images.virtualsoft.tech/productos/PaycipsT1605914194.png";
    $lista_temp['isConfigurable'] = $isConfigurable; //Determinar si la pasarela de pago es configurable

    /* verifica configuración de pasarela y actualiza lista de productos en ventana emergente. */
    $lista_temp['configured'] = $configured; //Determina si la pasarela de pago ha sido configurada por el usuario.


    if ($is_popup) {
        array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        $ProductoMandantesData['todos']['length']++;
    } else array_push($ProductoMandantesData, $lista_temp);
}

if ($Usuario->paisId == 60 && $Usuario->mandante == '0') {


    /* Se definen límites y se inicializa un arreglo con datos de un banco. */

    /* Define un rango de valores y una lista con información de una moneda específica. */
    $min = 10;
    $max = 3000;

    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCOCR2";
    $lista_temp['moneda'] = "CRC";

    /* asigna información sobre un pago a una lista temporal. */

    /* Código que configura datos para un servicio de recarga por SINPE Móvil. */
    $lista_temp['nombre'] = "Recargá por SINPE Móvil al número +506 8407-9595  y enviá el comprobante de transferencia al número de Whatsapp +506 8407 9595";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1626997073.png";
    $lista_temp['imagentxt'] = "";
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));

    /* establece valores en un array relacionado con un sistema de pagos. */

    /* configura valores en un array, relacionado con un procesamiento financiero. */
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";
    $lista_temp['pasarelatxt'] = "Sinpe Movil";
    $lista_temp['isInfoText'] = "Enviar";
    $lista_temp['isInfo'] = true;
    $lista_temp['pasarela'] = "";

    if ($is_popup) {
        $ProductoMandantesData['todos']['length']++;
        array_push($ProductoMandantesData['todos']['data'], $lista_temp);
    } else array_push($ProductoMandantesData, $lista_temp);


    /* Se crea un arreglo asociativo con datos de un método de pago. */
    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCOCR1";
    $lista_temp['moneda'] = "CRC";
    $lista_temp['nombre'] = "Visa - Mastercard";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "https://images.virtualsoft.tech/productos/payment/icon/visa-mastercard.jpeg";

    /* asigna valores a un arreglo temporal con datos formateados. */
    $lista_temp['imagentxt'] = "";
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";
    $lista_temp['pasarelatxt'] = "Cupon";

    /* Condicionalmente agrega información de producto a la lista si se muestra un popup. */
    $lista_temp['isInfoText'] = "Comprar";
    $lista_temp['isInfo'] = true;

    if ($is_popup) {
        $ProductoMandantesData['todos']['length']++;
        array_push($ProductoMandantesData['todos']['data'], $lista_temp);
    } else array_push($ProductoMandantesData, $lista_temp);


    /* Crea un array en PHP que almacena información sobre cupones de recarga. */
    $lista_temp = [];
    $lista_temp['id'] = "coupons";
    $lista_temp['moneda'] = "CRC";
    $lista_temp['nombre'] = "Introducí tu número de cupón de recarga";
    $lista_temp['valor'] = '';
    $lista_temp['imagen'] = "";

    /* define un arreglo con información sobre cupones de recarga y límites monetarios. */
    $lista_temp['imagentxt'] = "Cupón de Recarga";
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";
    $lista_temp['pasarelatxt'] = "Doradobet Cupones";

    /* Agrega información a un arreglo si la condición de popup se cumple. */
    $lista_temp['isInfoText'] = "Obtener";
    $lista_temp['isInfo'] = false;

    if ($is_popup) {
        $ProductoMandantesData['todos']['length']++;
        array_push($ProductoMandantesData['todos']['data'], $lista_temp);
    } else array_push($ProductoMandantesData, $lista_temp);


}
if ($Usuario->paisId == 2 && $Usuario->mandante == '0') {


    /* crea un arreglo para almacenar datos de un banco fijo en USD. */
    $min = 10;
    $max = 3000;

    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCO1";
    $lista_temp['moneda'] = "USD";

    /* Asignación de valores y formato a un array temporal para un banco específico. */
    $lista_temp['nombre'] = "Banco BDF";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "";
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));

    /* Código inicializa una variable y define un valor mínimo, preparando una lista para productos. */
    $lista_temp['pasarela'] = "";


//array_push($ProductoMandantesData, $lista_temp);

    $min = 10;

    /* Se define un arreglo con información de un banco específico en dólares. */
    $max = 5000;

    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCO2";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Banco BDF";

    /* inicializa un array con valores y formatos específicos para una operación. */
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "";
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";


//array_push($ProductoMandantesData, $lista_temp);


    /* Se crea un array asociativo con información de un banco específico. */
    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCO3";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Banco LAFISE";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "";

    /* asigna valores a un array y lo agrega a otro si se cumple una condición. */
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";


    if ($is_popup) {
        $ProductoMandantesData['todos']['length']++;
        array_push($ProductoMandantesData['todos']['data'], $lista_temp);
    } else array_push($ProductoMandantesData, $lista_temp);


    /* Crea un arreglo asociativo con información de un banco y su moneda. */
    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCO4";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Banco BDF";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "";

    /* Se asignan valores a un array temporal basado en condiciones de depósitos mínimos y máximos. */
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";


//array_push($ProductoMandantesData, $lista_temp);

    $min = 10;

    /* Se define un arreglo con información de un banco en dólares. */
    $max = 3000;

    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCO5";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Banco BDF";

    /* Se asignan valores y formateo a un array asociativo llamado $lista_temp. */
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "";
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";


//array_push($ProductoMandantesData, $lista_temp);


    /* Se crea un arreglo asociativo con información de un banco específico. */
    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCO5";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Banco BDF";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "";

    /* asigna valores formateados a un array temporal y lo reinicia. */
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";

//array_push($ProductoMandantesData, $lista_temp);

    $lista_temp = [];

    /* define un arreglo con información sobre un banco específico. */
    $lista_temp['id'] = "FIXED-BANCO6";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Banco BAC";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "";
    $lista_temp['tiempo'] = "Tiempo normal";

    /* Código que formatea valores y actualiza una lista condicionalmente si es un popup. */
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";


    if ($is_popup) {
        $ProductoMandantesData['todos']['length']++;
        array_push($ProductoMandantesData['todos']['data'], $lista_temp);
    } else array_push($ProductoMandantesData, $lista_temp);


    /* Se crea un arreglo asociativo en PHP con detalles de un banco. */
    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCO7";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Banco BAC";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "";

    /* Asigna valores formateados a un arreglo basado en condiciones y lo añade si es popup. */
    $lista_temp['tiempo'] = "Tiempo normal";
    $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
    $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
    $lista_temp['pasarela'] = "";

    if ($is_popup) {
        $ProductoMandantesData['todos']['length']++;
        array_push($ProductoMandantesData['todos']['data'], $lista_temp);
    } else array_push($ProductoMandantesData, $lista_temp);
}

$hour = date('H');
$day = date('N');

if (($day >= 1 && $day <= 5 && intval($hour) >= 8 && intval($hour) <= 17) || $Usuario->usuarioId == 438120) {

    $min = 1000;
    $max = 40000;

    if ($Usuario->mandante == '2' && $Usuario->origen == '0') {


        /* Código PHP que crea un array asociativo con información de una transferencia bancaria. */
        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOJMD1";
        $lista_temp['moneda'] = "JMD";
        $lista_temp['nombre'] = "Transfer via Bank Transfer - Bank of Nova Scotia";
        $lista_temp['valor'] = 5000;
        $lista_temp['imagen'] = "";

        /* asigna valores a un array y gestiona la inclusión en una lista. */
        $lista_temp['tiempo'] = "Regular time";
        $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
        $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
        $lista_temp['pasarela'] = "";


        if ($is_popup) {
            $ProductoMandantesData['todos']['length']++;
            array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        } else array_push($ProductoMandantesData, $lista_temp);


        /* Crea un arreglo con información de un método de transferencia bancaria. */
        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOJMD2";
        $lista_temp['moneda'] = "JMD";
        $lista_temp['nombre'] = "Transfer via Bank Transfer - Sagicor Bank Jamaica";
        $lista_temp['valor'] = 5000;
        $lista_temp['imagen'] = "";

        /* Asigna valores a un array y lo agrega a una lista si se cumple una condición. */
        $lista_temp['tiempo'] = "Regular time";
        $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
        $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
        $lista_temp['pasarela'] = "";


        if ($is_popup) {
            $ProductoMandantesData['todos']['length']++;
            array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        } else array_push($ProductoMandantesData, $lista_temp);

    }
    if ($Usuario->mandante == '2' && $Usuario->origen == '1') {


        /* Código de PHP que crea un array con información de transferencia bancaria. */
        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOJMD3";
        $lista_temp['moneda'] = "JMD";
        $lista_temp['nombre'] = "Transfer via Bank Transfer - Bank of Nova Scotia";
        $lista_temp['valor'] = 5000;
        $lista_temp['imagen'] = "";

        /* Asigna valores a un arreglo basado en condiciones y agrega datos si es un popup. */
        $lista_temp['tiempo'] = "Regular time";
        $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
        $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
        $lista_temp['pasarela'] = "";


        if ($is_popup) {
            $ProductoMandantesData['todos']['length']++;
            array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        } else array_push($ProductoMandantesData, $lista_temp);


        /* Creación de un arreglo en PHP con detalles de una transferencia bancaria. */
        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOJMD4";
        $lista_temp['moneda'] = "JMD";
        $lista_temp['nombre'] = "Transfer via Bank Transfer - Sagicor Bank Jamaica";
        $lista_temp['valor'] = 5000;
        $lista_temp['imagen'] = "";

        /* asigna valores a un arreglo y lo agrega si se cumple una condición. */
        $lista_temp['tiempo'] = "Regular time";
        $lista_temp['min'] = number_format($min >= $minDeposit ? $min : ($minDeposit ?: $min));
        $lista_temp['max'] = number_format($max <= $maxDeposit ? $max : ($maxDeposit ?: $max));
        $lista_temp['pasarela'] = "";


        if ($is_popup) {
            $ProductoMandantesData['todos']['length']++;
            array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        } else array_push($ProductoMandantesData, $lista_temp);

    }
}


/**
 * Inserta un arreglo en una posición específica dentro de otro arreglo.
 *
 * @param array $arr El arreglo original donde se insertará el nuevo arreglo.
 * @param array $insertedArray El arreglo que se desea insertar.
 * @param int $position La posición en la que se insertará el nuevo arreglo.
 * @return array El nuevo arreglo con los valores insertados en la posición especificada.
 */
function insertValueAtPosition($arr, $insertedArray, $position)
{
    $i = 0;
    $new_array = [];
    foreach ($arr as $key => $value) {
        if ($i == $position) {
            foreach ($insertedArray as $ikey => $ivalue) {
                $new_array[$ikey] = $ivalue;
            }
        }
        $new_array[$key] = $value;
        $i++;
    }
    return $new_array;
}

/**
 * Inserta un objeto en una posición específica dentro de un arreglo.
 *
 * @param array $array El arreglo original donde se insertará el nuevo objeto.
 * @param mixed $object El objeto que se desea insertar.
 * @param int $position La posición en la que se insertará el nuevo objeto.
 * @param string|null $name (Opcional) El nombre de la clave para el nuevo objeto. Si no se proporciona, se usará un índice numérico.
 * @return array El arreglo modificado con el objeto insertado en la posición especificada.
 */
function array_put_to_position(&$array, $object, $position, $name = null)
{
    $count = 0;
    $return = array();
    foreach ($array as $k => $v) {
        // Inserta el nuevo objeto
        if ($count == $position) {
            if (!$name) $name = $count;
            $return[$name] = $object;
            $inserted = true;
        }
        // Inserta el objeto existente
        $return[$k] = $v;
        $count++;
    }
    if (!$name) $name = $count;
    if (!$inserted) $return[$name];
    $array = $return;
    return $array;
}


if ($Usuario->paisId == 66 && $Usuario->mandante == '8' && $is_crypto == false) {
    $ProductoMandantesDataOld = array();

    $entroKEy = false;
    foreach ($ProductoMandantesData as $key => $productoMandantesDatum) {

        if ($key == 5) {

            /* Se define un arreglo con información sobre una entidad financiera en dólares. */
            $entroKEy = true;
            $lista_temp = [];
            $lista_temp['id'] = "FIXED-BANCOECU1";
            $lista_temp['moneda'] = "USD";
            $lista_temp['nombre'] = "Facilito";
            $lista_temp['valor'] = 50;

            /* asigna valores a un array temporal llamado $lista_temp. */
            $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1637954827.png";
            $lista_temp['tiempo'] = "2 horas";
            $lista_temp['min'] = number_format(10, 0);
            $lista_temp['max'] = number_format(3000, 0);
            $lista_temp['pasarela'] = "";


//array_push($ProductoMandantesDataOld, $lista_temp);

            $lista_temp = [];

            /* Código que define un arreglo con información sobre un servicio de transferencia de dinero. */
            $lista_temp['id'] = "FIXED-BANCOECU2";
            $lista_temp['moneda'] = "USD";
            $lista_temp['nombre'] = "Red Activa Western Union";
            $lista_temp['valor'] = 50;
            $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1637954767.png";
            $lista_temp['tiempo'] = "2 horas";

            /* Se crea un array con valores mínimos y máximos, luego se agrega a otro array. */
            $lista_temp['min'] = number_format(1, 0);
            $lista_temp['max'] = number_format(5000, 0);
            $lista_temp['pasarela'] = "";

            array_push($ProductoMandantesDataOld, $lista_temp);


            $lista_temp = [];

            /* Código que define un array con información sobre un producto financiero. */
            $lista_temp['id'] = "FIXED-BANCOECU4";
            $lista_temp['moneda'] = "USD";
            $lista_temp['nombre'] = "Bakan";
            $lista_temp['valor'] = 50;
            $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1655400914.png";
            $lista_temp['tiempo'] = "2 horas";

            /* define un array temporal con valores mínimos y máximos formateados. */
            $lista_temp['min'] = number_format(10, 0);
            $lista_temp['max'] = number_format(5000, 0);
            $lista_temp['pasarela'] = "";

//array_push($ProductoMandantesDataOld, $lista_temp);

            $lista_temp = [];

            /* Asignación de datos a un arreglo asociativo para almacenar información de un producto. */
            $lista_temp['id'] = "FIXED-BANCOECU5";
            $lista_temp['moneda'] = "USD";
            $lista_temp['nombre'] = "FullCarga";
            $lista_temp['valor'] = 50;
            $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1669771359.png";
            $lista_temp['tiempo'] = "2 horas";

            /* Se formatean valores y se añaden a un array, luego se reinicia la variable. */
            $lista_temp['min'] = number_format(10, 0);
            $lista_temp['max'] = number_format(5000, 0);
            $lista_temp['pasarela'] = "";

            array_push($ProductoMandantesDataOld, $lista_temp);

            $lista_temp = [];

            /* Crea un arreglo con información sobre un negocio y sus propiedades. */
            $lista_temp['id'] = "FIXED-BANCOECU6";
            $lista_temp['moneda'] = "USD";
            $lista_temp['nombre'] = "Farmacias Medicity";
            $lista_temp['valor'] = 50;
            $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1696267989.png";
            $lista_temp['tiempo'] = "2 horas";

            /* Se crea un array con valores formateados y se añade a otro array. */
            $lista_temp['min'] = number_format(10, 0);
            $lista_temp['max'] = number_format(5000, 0);
            $lista_temp['pasarela'] = "";

            array_push($ProductoMandantesDataOld, $lista_temp);
            $lista_temp = [];

            /* Código que crea un arreglo con datos de una farmacia, incluyendo identificador, moneda y valor. */
            $lista_temp['id'] = "FIXED-BANCOECU7";
            $lista_temp['moneda'] = "USD";
            $lista_temp['nombre'] = "Farmacias Economicas";
            $lista_temp['valor'] = 50;
            $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1696011878.png";
            $lista_temp['tiempo'] = "2 horas";

            /* Se crea un arreglo con valores formateados y se agrega a otro arreglo. */
            $lista_temp['min'] = number_format(10, 0);
            $lista_temp['max'] = number_format(5000, 0);
            $lista_temp['pasarela'] = "";

            array_push($ProductoMandantesDataOld, $lista_temp);


        }

        /* Agrega un elemento al final del array `$ProductoMandantesDataOld` en PHP. */
        array_push($ProductoMandantesDataOld, $productoMandantesDatum);

    }

    if (!$entroKEy) {


        /* Se crea un arreglo asociativo con información de un banco y su imagen. */
        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOECU1";
        $lista_temp['moneda'] = "USD";
        $lista_temp['nombre'] = "Facilito";
        $lista_temp['valor'] = 50;
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1637954827.png";

        /* Se crean e inicializan variables en un array temporal llamado $lista_temp. */
        $lista_temp['tiempo'] = "2 horas";
        $lista_temp['min'] = number_format(10, 0);
        $lista_temp['max'] = number_format(3000, 0);
        $lista_temp['pasarela'] = "";


// array_push($ProductoMandantesDataOld, $lista_temp);

        $lista_temp = [];

        /* Define un arreglo con datos sobre un servicio de transferencia en dólares. */
        $lista_temp['id'] = "FIXED-BANCOECU2";
        $lista_temp['moneda'] = "USD";
        $lista_temp['nombre'] = "Red Activa Western Union";
        $lista_temp['valor'] = 50;
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1637954767.png";
        $lista_temp['tiempo'] = "2 horas";

        /* Se formatea un rango numérico y se almacena en un arreglo. */
        $lista_temp['min'] = number_format(1, 0);
        $lista_temp['max'] = number_format(5000, 0);
        $lista_temp['pasarela'] = "";

        array_push($ProductoMandantesDataOld, $lista_temp);


        $lista_temp = [];

        /* Se crea un array con información sobre un banco, incluyendo ID, moneda y valores. */
        $lista_temp['id'] = "FIXED-BANCOECU4";
        $lista_temp['moneda'] = "USD";
        $lista_temp['nombre'] = "Bakan";
        $lista_temp['valor'] = 50;
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1655400914.png";
        $lista_temp['tiempo'] = "2 horas";

        /* Se formatean valores mínimos y máximos y se inicializa un array. */
        $lista_temp['min'] = number_format(10, 0);
        $lista_temp['max'] = number_format(5000, 0);
        $lista_temp['pasarela'] = "";

//array_push($ProductoMandantesDataOld, $lista_temp);

        $lista_temp = [];

        /* Código que define un arreglo con información de un producto financiero. */
        $lista_temp['id'] = "FIXED-BANCOECU5";
        $lista_temp['moneda'] = "USD";
        $lista_temp['nombre'] = "FullCarga";
        $lista_temp['valor'] = 50;
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1669771359.png";
        $lista_temp['tiempo'] = "2 horas";

        /* Se define un rango y se añade a un array, luego se reinicia. */
        $lista_temp['min'] = number_format(10, 0);
        $lista_temp['max'] = number_format(5000, 0);
        $lista_temp['pasarela'] = "";

        array_push($ProductoMandantesDataOld, $lista_temp);

        $lista_temp = [];

        /* Se define un array con información sobre un banco y sus características. */
        $lista_temp['id'] = "FIXED-BANCOECU6";
        $lista_temp['moneda'] = "USD";
        $lista_temp['nombre'] = "Farmacias Medicity";
        $lista_temp['valor'] = 50;
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1696267989.png";
        $lista_temp['tiempo'] = "2 horas";

        /* Se define un arreglo con valores mínimos y máximos, luego se agrega a otro. */
        $lista_temp['min'] = number_format(10, 0);
        $lista_temp['max'] = number_format(5000, 0);
        $lista_temp['pasarela'] = "";

        array_push($ProductoMandantesDataOld, $lista_temp);
        $lista_temp = [];

        /* Se crea un array con información de una farmacia, incluyendo identificación y moneda. */
        $lista_temp['id'] = "FIXED-BANCOECU7";
        $lista_temp['moneda'] = "USD";
        $lista_temp['nombre'] = "Farmacias Economicas";
        $lista_temp['valor'] = 50;
        $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1696268082.png";
        $lista_temp['tiempo'] = "2 horas";

        /* Se formatean valores y se añaden a un arreglo de productos. */
        $lista_temp['min'] = number_format(10, 0);
        $lista_temp['max'] = number_format(5000, 0);
        $lista_temp['pasarela'] = "";

        array_push($ProductoMandantesDataOld, $lista_temp);

    }



    /* Crea un arreglo asociativo con detalles de un servicio llamado Bemovil. */
    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCOECU3";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Bemovil";
    $lista_temp['valor'] = 50;
    $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1645161004.png";

    /* Se asignan valores a un arreglo temporal para productos. */
    $lista_temp['tiempo'] = "2 horas";
    $lista_temp['min'] = number_format(10, 0);
    $lista_temp['max'] = number_format(5000, 0);
    $lista_temp['pasarela'] = "";

//array_push($ProductoMandantesDataOld, $lista_temp);

    $ProductoMandantesData = $ProductoMandantesDataOld;


}


/*
$array= array();
$array["name"] = 'local';
$array["displayName"] = 'Punto de venta';
$array["canDeposit"] = false;
$array["canWithdraw"] = true;
$array["order"] = 0;
$array["depositInfoTextKey"] = '';
$array["stayInSameTabOnDeposit"] = true;
$array["depositFormFields"] = array();
$array["predefinedFields"] = array();
$array["predefinedFields"]['1tap']= true;
$array["info"] = array();
$array["info"][$moneda] = array();
$array["info"][$moneda]["depositFee"] = 0;
$array["info"][$moneda]["withdrawFee"] = 0;
$array["info"][$moneda]["depositProcessTime"] = '';
$array["info"][$moneda]["withdrawProcessTime"] = '';
$array["info"][$moneda]["minDeposit"] = '';
$array["info"][$moneda]["maxDeposit"] = '';
$array["info"][$moneda]["minWithdraw"] = '';
$array["info"][$moneda]["maxWithdraw"] = '';

array_push($ProductoMandantesData, $array);*/

if ($UsuarioTokenSite != null && $Usuario->paisId == 66 && $Usuario->mandante == '8' && false) {

    $cookie = $UsuarioTokenSite->getCookie();

    if (($cookie != '' && $cookie != null) || true) {
        try {

            /* La línea de código decodifica un JSON almacenado en la variable $cookie. */
            $cookie = json_decode($cookie);
            if (($cookie != null && in_array($cookie->RISK, array('RISK10', 'RISK0'))) || true) {

                /* Se crea un arreglo con información de un producto financiero. */
                $ProductoMandantesDataFinal = array();

                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-MASTERCARD";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Master Card";

                /* Se crea un array con datos numéricos y de imagen para una interfaz. */
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1681490718.png";
                $lista_temp['tiempo'] = "5 minutos";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(500, 0);
                $lista_temp['pasarela'] = "";

                /* establece límites mínimos y máximos si se cumple una condición de cookie. */
                $lista_temp['isConfigurable'] = "";

                if ($cookie->RISK == 'RISK10') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(100, 0);
                }

                /* Configura valores mínimos y máximos si el riesgo es 'RISK0' y reinicia lista. */
                if ($cookie->RISK == 'RISK0') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(2000, 0);
                }
//array_push($ProductoMandantesDataFinal, $lista_temp);

                $lista_temp = [];

                /* Se crea un array asociativo con información sobre un producto llamado VISA. */
                $lista_temp['id'] = "PREPROD-VISA";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "VISA";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1681490804.png";
                $lista_temp['tiempo'] = "5 minutos";

                /* Se establecen valores mínimos y máximos basados en una condición de riesgo. */
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(500, 0);

                if ($cookie->RISK == 'RISK10') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(100, 0);
                }

                /* asigna valores a un array basado en una condición de cookie. */
                if ($cookie->RISK == 'RISK0') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(2000, 0);
                }

                $lista_temp['pasarela'] = "";

                /* Se inicializa un array temporal con un id específico en PHP. */
                $lista_temp['isConfigurable'] = "";
//array_push($ProductoMandantesDataFinal, $lista_temp);


                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-EFECTIVO";

                /* define una lista con información sobre un método de pago. */
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Transferencia Bancaria / Pago Efectivo";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1673389158.png";
                $lista_temp['tiempo'] = "5 minutos";


                $lista_temp['min'] = number_format(20, 0);

                /* formatea un número y filtra productos, excluyendo ciertos IDs. */
                $lista_temp['max'] = number_format(1000, 0);

                $lista_temp['pasarela'] = "";
                $lista_temp['isConfigurable'] = "";
                array_push($ProductoMandantesDataFinal, $lista_temp);

                foreach ($ProductoMandantesData as $productoMandantesDatum) {
//!in_array($productoMandantesDatum['id_pasarela'], array(214, 74, 141)) &&
                    if (!in_array($productoMandantesDatum['id'], array(13437, 8793))) {
                        array_push($ProductoMandantesDataFinal, $productoMandantesDatum);

                    }

                }



                /* Crea un arreglo asociativo en PHP con información sobre un pago online. */
                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-3";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Pago online";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/productos/Pago-onlineT1625779317.png";

                /* Se inicializa un arreglo temporal para almacenar datos sobre un producto. */
                $lista_temp['tiempo'] = "5 minutos";
                $lista_temp['min'] = number_format(20, 0);
                $lista_temp['max'] = number_format(3000, 0);
                $lista_temp['pasarela'] = "";
                $lista_temp['isConfigurable'] = "";
//array_push($ProductoMandantesData, $lista_temp);


                $lista_temp = [];

                /* Código asigna valores a un array asociativo que representa información sobre un servicio. */
                $lista_temp['id'] = "FIXED-BANCOECU2";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Red Activa Western Union";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1637954767.png";
                $lista_temp['tiempo'] = "2 horas";

                /* Se inicializa un arreglo con valores formateados y se reinicia posteriormente. */
                $lista_temp['min'] = number_format(1, 0);
                $lista_temp['max'] = number_format(5000, 0);
                $lista_temp['pasarela'] = "";

//array_push($ProductoMandantesDataFinal, $lista_temp);


                $lista_temp = [];

                /* Código define un arreglo de información sobre un servicio de carga con detalles específicos. */
                $lista_temp['id'] = "FIXED-BANCOECU5";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "FullCarga";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1669771359.png";
                $lista_temp['tiempo'] = "2 horas";

                /* Se formatean valores y se añaden a un arreglo temporal de productos. */
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(5000, 0);
                $lista_temp['pasarela'] = "";

//array_push($ProductoMandantesDataFinal, $lista_temp);
                $ProductoMandantesData = $ProductoMandantesDataFinal;


                /* Se crea un arreglo asociativo para almacenar información de una farmacia. */
                $lista_temp = [];
                $lista_temp['id'] = "FIXED-BANCOECU6";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Farmacias Medicity";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1669771359.png";

                /* Asignación de valores a un array temporal y continuación con otro array. */
                $lista_temp['tiempo'] = "2 horas";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(5000, 0);
                $lista_temp['pasarela'] = "";

//array_push($ProductoMandantesDataFinal, $lista_temp);
                $ProductoMandantesData = $ProductoMandantesDataFinal;


                /* Se crea un arreglo asociativo con información de una farmacia y su imagen. */
                $lista_temp = [];
                $lista_temp['id'] = "FIXED-BANCOECU7";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Farmacias Economicas";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1696011878.png";

                /* Se define un array con datos temporales y se asigna a otra variable. */
                $lista_temp['tiempo'] = "2 horas";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(5000, 0);
                $lista_temp['pasarela'] = "";

//array_push($ProductoMandantesDataFinal, $lista_temp);
                $ProductoMandantesData = $ProductoMandantesDataFinal;

            }

        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción específica dentro del bloque. */

        }
    }


}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = $ProductoMandantesData;


