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
use Backend\dto\UsuarioInformacion;
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
 * Obtiene las pasarelas de pago disponibles para el usuario.
 *
 * @param stdClass $json Objeto JSON que contiene los parámetros de entrada.
 * @param string $json->params->site_id ID del sitio.
 * @param string $json->params->country País del usuario.
 * @param bool $json->params->is_popup Indica si es una ventana emergente.
 * @param bool $json->params->is_crypto Indica si es criptomoneda.
 * @param stdClass|null $json->session Sesión del usuario.
 * @param string|null $json->session->usuario Usuario de la sesión.
 * @param string $json->rid ID de la solicitud.
 *
 * @return array $response Respuesta con los datos de los productos mandantes.
 * @return int $response["code"] Código de respuesta.
 * @return string $response["rid"] ID de la solicitud.
 * @return array $response["data"] Datos de los productos mandantes.
 *
 * @throws Exception Si el celular no está verificado.
 */

// Se obtiene el ID del sitio y se convierte a minúsculas
$site_id = $json->params->site_id;
$site_id = strtolower($site_id);
$Country =$json->params->country;

// Verifica si no existe una sesión
if($json->session == null){
    $json->session = new stdClass();
    if($json->session->usuario =='' && $json->session->usuario==null){
        syslog(LOG_WARNING, " get_payments2LANDING" );

        // Se inicializa el usuario como vacío
        $json->session->usuario = '';
        try {
            // Se crea un nuevo objeto Pais con el país obtenido
            $Pais = new Pais($Country);

        } catch (Exception $e){
            // En caso de excepción, se crea un objeto Pais con los valores por defecto
            $Pais = new Pais('', $Country);
        }
        // Se crea un nuevo objeto Usuario
        $Usuario = new stdClass();
        $Usuario->mandante=$site_id;
        $Usuario->paisId=$Pais->paisId;
    }
}else{
    // Si ya existe una sesión, se obtiene el usuario mandante
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

}
//$Pais = new Pais($Usuario->paisId);

// Se verifica si el parámetro is_popup está definido
$is_popup = !empty($json->params->is_popup) ? $json->params->is_popup : false;
$MaxRows = 1000;
$OrderedItem = 1;
$SkeepRows = 0;

// Variables de configuración inicializadas en falso
$configured = false;
$isConfigurable = false;

// Se crea un objeto Clasificador para el tipo 'PHONEVERIFICATION'
$Clasificador = new Clasificador('', 'PHONEVERIFICATION');
$tipoDetalle = $Clasificador->getClasificadorId();

try {
    $MandanteDetalle = new MandanteDetalle('', $site_id, $tipoDetalle, $Usuario->paisId,'',0);
    if ($MandanteDetalle->estado == 'A')
    {
        if ($Usuario->verifCelular == 'N')
        {
            throw new Exception('Celular no verificado.', 100095);
        }
    }
}catch (Exception $e){
    // Si se lanza una excepción con el código 100095, la vuelve a lanzar
    if($e->getCode() == 100095) throw $e;
}

// Inicializamos un array de reglas
$rules = [];
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));
array_push($rules, array("field" => "proveedor_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "subproveedor_mandante.estado", "data" => "A", "op" => "eq"));

// Condiciones adicionales basadas en el mandante del usuario
if($Usuario->mandante == '2'){
    array_push($rules, array("field" => "proveedor.abreviado", "data" => "SAGICOR", "op" => "ne"));
}
if($Usuario->mandante == '13'){
    array_push($rules, array("field" => "proveedor.abreviado", "data" => "PAYMENTEZ", "op" => "ne"));
}

// Condicional que verifica un estado verdadero
if (true) {
    // Verificación basada en el parámetro de prueba del usuario
    if ($Usuario->test=='S') {

    } else {
        // Agregamos una regla adicional si el test no es 'S'
        array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "I", "op" => "eq"));
    }
} else {
    // Se puede agregar lógica para el caso en que la condición sea falsa
}
// Comprobamos si el ID del sitio es igual a 8
if($site_id == 8) {
    $is_crypto = $json->params->is_crypto;

    // Si es criptomoneda, agregamos una regla para incluir el proveedor con ID 135
    if($is_crypto == true){
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "135", "op" => "in"));
    } else {
        // Si no es criptomoneda, excluimos el proveedor con ID 135
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "135", "op" => "ni"));

    }

}

// Comprobamos si el ID del sitio es igual a 15
if($site_id == 15) {
    $is_crypto = $json->params->is_crypto;

    // Definimos el estado de is_crypto como falso si no tiene valor o es falso
    if ($is_crypto == "" || $is_crypto == false){
        $is_crypto = false;
    } else if($is_crypto == true){
        $is_crypto = true;
    }

    // Agregamos reglas basadas en si es criptomoneda
    if($is_crypto == true){
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "135", "op" => "in"));

    }else{
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "135", "op" => "ni"));

    }

}

// Comprobamos si el ID del sitio es igual a 20
if($site_id == 20) {
    $is_crypto = $json->params->is_crypto;

    if($is_crypto == true){
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "135", "op" => "in"));

    }else{
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "135", "op" => "ni"));

    }

}

// Si el ID de usuario está en el array especificado, se excluye el proveedor con ID 8 y 100
if (in_array($Usuario->usuarioId, array('315236', '94415', '223641','232085',215760,246814,262771))) {
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "8,100", "op" => "ni"));

}
// Se define un filtro en forma de array con las reglas y la operación de agrupación.
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

// Se instancia el objeto ProductoMandante.
$ProductoMandante = new ProductoMandante();

if(true){
    // Se obtienen los productos del mandante de acuerdo a ciertos parámetros.
    $ProductoMandantes = $ProductoMandante->getProductosMandantePaisCustom2(" proveedor.abreviado,proveedor.descripcion,proveedor.proveedor_id,proveedor.imagen,producto.producto_id , producto.descripcion,producto_mandante.extra_info,producto_mandante.valor ,CASE producto_mandante.filtro_pais WHEN 'A' THEN producto_mandante.min ELSE producto_mandante.min END min,CASE 'A' WHEN 'A' THEN producto_mandante.max ELSE producto_mandante.max END max,CASE 'A' WHEN 'A' THEN producto_mandante.tiempo_procesamiento ELSE producto_mandante.tiempo_procesamiento END tiempo, producto.image_url imagen,producto.orden,producto_mandante.min,producto_mandante.max ,producto_mandante.image_url,producto_mandante.image_url2,producto.externo_id", "producto_mandante.orden", "DESC,producto.descripcion", $SkeepRows, $MaxRows, $json2, true, $Usuario->paisId);

}else{
    // Se obtienen los productos del mandante usando una función diferente.
    $ProductoMandantes = $ProductoMandante->getProductosMandantePaisCustom(" proveedor.proveedor_id,producto_mandante.image_url,producto_mandante.image_url2,proveedor.descripcion,proveedor.imagen,producto.producto_id , producto.descripcion,producto_mandante.extra_info,producto_mandante.valor, CASE producto_mandante.filtro_pais WHEN 'A' THEN producto_mandante.min ELSE producto_mandante.min END min,CASE producto_mandante.filtro_pais WHEN 'A' THEN producto_mandante.max ELSE producto_mandante.max END max,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.tiempo_procesamiento ELSE producto_mandante.tiempo_procesamiento END tiempo, producto.image_url imagen,producto.orden,producto_mandante.min,producto_mandante.max,prodmandante_pais.min,prodmandante_pais.max ", "producto_mandante.orden", "DESC,producto.descripcion", $SkeepRows, $MaxRows, $json2, true, $Usuario->paisId);

}

// Se decodifica el JSON de productos mandantes a un objeto PHP.
$ProductoMandantes = json_decode($ProductoMandantes);

try{

    $rules = [];

    // Se agregan diferentes condiciones a las reglas.
    array_push($rules, ['field' => 'categoria_mandante.tipo', 'data' => 'PAYMENT', 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_producto.mandante', 'data' => $Usuario->mandante, 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_producto.pais_id', 'data' => $Usuario->paisId, 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_producto.estado', 'data' => 'A', 'op' => 'eq']);

    // Se convierten las reglas a formato JSON.
    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $CategoriaProducto = new CategoriaProducto();
    // Se obtienen las categorías de productos del mandante con ciertas condiciones.
    $CategoriaProductos = $CategoriaProducto->getCategoriaProductosMandanteCustom('categoria_mandante.descripcion, categoria_producto.producto_id', 'categoria_producto.catprod_id', 'ASC', $SkeepRows, $MaxRows, $filters, true);
    $CategoriaProductos = json_decode($CategoriaProductos, true);

}catch (Exception $e){

}
$columns = array_column($CategoriaProductos['data'], 'categoria_producto.producto_id');

$ProductoMandantesData = $is_popup ? [
    'todos' => [
        'length' => 0,
        'data' => []
    ]
] : [];



$moneda = $Usuario->moneda;
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


    $isConfigurable = $value->{"producto.es_configurable"};

    if ($isConfigurable) {
        $isConfigurable = true;
    } elseif (!$isConfigurable) {
        $isConfigurable = false;
    }
    if ($value->{"proveedor.abreviado"} == "FRI") {
        $isConfigurable = true;
    }


    $lista_temp = [];
    $lista_temp['id'] = $value->{"producto.producto_id"};
    $lista_temp['nombre'] = $value->{"producto.descripcion"};
    $lista_temp['Commission'] = $value->{"producto_mandante.valor"};
    $lista_temp['imagen'] = $value->{"producto.imagen"};
    $lista_temp['tiempo'] = $value->{".tiempo"};

    if($lista_temp['id']==16861 && $Usuario->mandante =='0' && $Usuario->paisId =='2'){
        $lista_temp['nombre'] = 'VISA / MASTERCARD';
    }

    $lista_temp['info'] = $value->{"producto_mandante.extra_info"};
    $lista_temp['min'] = number_format(($value->{"prodmandante_pais.min"} !== '' && ($value->{"prodmandante_pais.min"} > $value->{"producto_mandante.min"}) && ($value->{"prodmandante_pais.min"} > $value->{"producto.min"})) ? $value->{"prodmandante_pais.min"} : (($value->{"producto_mandante.min"} != '' && $value->{"producto_mandante.min"} > $value->{"producto.min"}) ? $value->{"producto_mandante.min"} : (($value->{"producto.min"} != '') ? $value->{"producto.min"} : 0)), 0);
    $lista_temp['max'] = number_format($value->{"prodmandante_pais.max"} !== '' && $value->{"prodmandante_pais.max"} !== '0' ? $value->{"prodmandante_pais.max"} : (($value->{"producto_mandante.max"} != '' && $value->{"producto_mandante.max"} < $value->{"producto.max"}) ? $value->{"producto_mandante.max"} : (($value->{"producto.max"} != '') ? $value->{"producto.max"} : 0)), 0);
    $lista_temp['max'] = number_format($value->{".max"} , 0);
    $lista_temp['id_pasarela'] = $value->{"proveedor.proveedor_id"};
    $lista_temp['isConfigurable'] = $isConfigurable; //Determinar si la pasarela de pago es configurable

    $lista_temp['nombre_pasarela'] = $value->{"proveedor.descripcion"};

    if($value->{"producto_mandante.image_url"} != '') {
        $lista_temp['imagen'] = $value->{"producto_mandante.image_url"};
    }

    if($value->{"producto_mandante.image_url2"} != '') {
        $lista_temp['pasarela'] = $value->{"producto_mandante.image_url2"};
    }

    /*Definición de requerimientos del proveedor*/
    if($value->{"proveedor.abreviado"} == "PAGADITO"){
        $lista_temp['popupTarjeta'] = true;
        $lista_temp['requireAuth'] = true;
        $lista_temp['is_crypto'] = false;

    }
    /*   elseif($Proveedor->abreviado == "N1CO"){
           $lista_temp['popupTarjeta'] = true;
           $lista_temp['saveCard'] = true;
           $lista_temp['is_crypto'] = false;
       }*/
    elseif ($value->{"proveedor.abreviado"} == "VISANET") {
        $lista_temp['popupTarjeta'] = true;
        $lista_temp['requireAuth'] = false;
        $lista_temp['is_crypto'] = false;
        $lista_temp['saveCard'] = true;
    } elseif ($value->{"proveedor.abreviado"} == "TOTALPAGO") {
        $lista_temp['popupTransfer'] = true;
    }  elseif ($value->{"proveedor.abreviado"} == "PAYGATE") {
        $lista_temp['popupTarjeta'] = true;
        $lista_temp['requireAuth'] = false;
        $lista_temp['is_crypto'] = false;
        $lista_temp['saveCard'] = true;
    }elseif ($value->{"proveedor.abreviado"} == "GREENPAY") {
        if ($Usuario->mandante == '23'){
            $lista_temp['popupTarjeta'] = true;
            $lista_temp['requireAuth'] = true;
            $lista_temp['is_crypto'] = false;
            $lista_temp['validation_3DS'] = true;
        }
    } else {
        // No se mostrará el popup de tarjeta si no se cumplen las condiciones anteriores
        $lista_temp['popupTarjeta'] = false;
    }
    // Verificar condiciones adicionales para el mandante '8' y un ID específico
    IF($Usuario->mandante =='8' && $lista_temp['id'] =='11274'){

        if($Usuario->verifcedulaAnt !='S' || $Usuario->verifcedulaPost !='S'){
            $lista_temp['max'] = number_format( 100, 0);

        }

    }


    if ($value->{"proveedor.abreviado"} == "FRI") {
        try {
            // Intenta crear un objeto de Clasificador para obtener el ID de clasificador de 'FRIUSERNAME'
            try {
                $Clasificador = new Clasificador('', 'FRIUSERNAME');
                $ClasificadorId = $Clasificador->getClasificadorId();
                $UsuarioInformacion = new UsuarioInformacion('', $ClasificadorId, $Usuario->usuarioId, "", $Usuario->mandante);
            } catch (Exception $ex) {
            }

            // Intenta crear un objeto de Clasificador para obtener el ID de clasificador de 'FRIPHONE'
            try {
                $Clasificador = new Clasificador('', 'FRIPHONE');
                $ClasificadorId = $Clasificador->getClasificadorId();
                $UsuarioInformacion2 = new UsuarioInformacion('', $ClasificadorId, $Usuario->usuarioId, "", $Usuario->mandante);
            } catch (Exception $ex) {
            }

            // Obtiene los valores de usuario de la información del clasificador FRI
            $FriUsername = $UsuarioInformacion->valor;
            $FriPhoneNumber = $UsuarioInformacion2->valor;

            // Verifica si al menos uno de los valores obtenidos es distinto de vacío o nulo
            if (($FriUsername !== "" && $FriUsername !== null) || ($FriPhoneNumber !== "" && $FriPhoneNumber !== null)) {
                $configured = true;
            } else {

                $configured = false;
            }


        } catch (Exception $ex) {
        }

        // Almacena en la lista temporal si la pasarela de pago ha sido configurada por el usuario
        $lista_temp['configured'] = $configured; // Determina si la pasarela de pago ha sido configurada por el usuario.
    }



    if($lista_temp['id'] == '10312'){
        $lista_temp['comision'] = '3.5%';
    }

    $lista_temp['valor'] = 50;

    // Modifica el valor en la lista temporal si la moneda del usuario es 'MXN'
    if($Usuario->moneda=='MXN'){
        $lista_temp['valor'] = 100;
    }

    if($Usuario->moneda=='BRL'){
        $lista_temp['valor'] = 30;
    }

    if($site_id=='14'){
        $lista_temp['valor'] = 70;
    }
    if($Usuario->mandante == 0 && $Usuario->paisId=='68'){
        $lista_temp['valor'] = 10;
    }


    if($Usuario->mandante == 0 && $Usuario->moneda=='CRC'){
        $lista_temp['valor'] = 5000;
    }


    if($Usuario->moneda=='CRC' && $lista_temp['id']=='11852'){
        $lista_temp['valor'] = 5000;
    }

    if($Usuario->mandante=='16'){
        $lista_temp['valor'] = 0;
    }

    if($Usuario->moneda=='CLP'){
        $lista_temp['valor'] = 9000;

        if($lista_temp['id'] == 13443){
            $lista_temp['valor'] = 2500;

        }
    }


    if($Usuario->moneda=='HNL'){
        $lista_temp['valor'] = 1000;
    }

    if ( $Usuario->mandante == 18 && $Usuario->paisId == 173) {
        $lista_temp['valor'] = 50;

    }






    if ($lista_temp['id'] == "3995" && $Usuario->paisId == 173 && $Usuario->mandante == '0') {
        $lista_temp['nombre'] = "Banca móvil, agentes y bodegas vía PagoEfectivo";

    }

    $lista_temp['externo_id'] = $value->{'producto.externo_id'};

    if ($is_popup) {
        $search = array_search($value->{'producto.producto_id'}, $columns);

        // Verifica si se encontró el producto en las columnas
        if($search) {
            $category = $CategoriaProductos['data'][$search]['categoria_mandante.descripcion'];
            if(!isset($ProductoMandantesData[$category])) $ProductoMandantesData[$category] = [
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


// Verifica si el mandante del usuario es '16' y si no está utilizando criptomonedas
if ($Usuario->mandante == '16' && $is_crypto == false ) {

    // Inicializa un arreglo temporal para almacenar información sobre un producto
    $lista_temp = [];
    $lista_temp['id'] = "FIXED-BANCOLATIN1";
    $lista_temp['moneda'] = "USD";
    $lista_temp['nombre'] = "Whatsapp";
    $lista_temp['valor'] = 100;
    $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1661290169.png";
    $lista_temp['tiempo'] = "2 horas";
    $lista_temp['min'] = number_format(70, 0);
    $lista_temp['max'] = number_format(50000, 0);
    $lista_temp['pasarela'] = "";
    $lista_temp['pasarelatxt'] = "Whatsapp";
    $lista_temp['isInfoText'] = "Enviar";
    $lista_temp['isInfo'] = false;
    $lista_temp['pasarela'] = "";
   // array_push($ProductoMandantesData, $lista_temp);



}




$hour = date('H');
$day = date('N');

/*Definición pasarelas ofertadas en franjas horarias específicas*/
if(($day>=1 && $day<=5 && intval($hour) >=8 && intval($hour) <=17) || $Usuario->usuarioId == 438120 ){

    if ( $Usuario->mandante == '2' && $Usuario->origen == '0') {
        //Definición y almacenamiento bancos ofertados en partner específico
        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOJMD1";
        $lista_temp['moneda'] = "JMD";
        $lista_temp['nombre'] = "Transfer via Bank Transfer - Bank of Nova Scotia";
        $lista_temp['valor'] = 5000;
        $lista_temp['imagen'] = "";
        $lista_temp['tiempo'] = "Regular time";
        $lista_temp['min'] = number_format(1000, 0);
        $lista_temp['max'] = number_format(40000, 0);
        $lista_temp['pasarela'] = "";


        if($is_popup) {
            $ProductoMandantesData['todos']['length']++;
            array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        }  else array_push($ProductoMandantesData, $lista_temp);

        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOJMD2";
        $lista_temp['moneda'] = "JMD";
        $lista_temp['nombre'] = "Transfer via Bank Transfer - Sagicor Bank Jamaica";
        $lista_temp['valor'] = 5000;
        $lista_temp['imagen'] = "";
        $lista_temp['tiempo'] = "Regular time";
        $lista_temp['min'] = number_format(1000, 0);
        $lista_temp['max'] = number_format(40000, 0);
        $lista_temp['pasarela'] = "";


        if($is_popup) {
            $ProductoMandantesData['todos']['length']++;
            array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        }  else array_push($ProductoMandantesData, $lista_temp);

    }
    if ( $Usuario->mandante == '2' && $Usuario->origen == '1') {
        //Definición y almacenamiento bancos ofertados en partner específico

        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOJMD3";
        $lista_temp['moneda'] = "JMD";
        $lista_temp['nombre'] = "Transfer via Bank Transfer - Bank of Nova Scotia";
        $lista_temp['valor'] = 5000;
        $lista_temp['imagen'] = "";
        $lista_temp['tiempo'] = "Regular time";
        $lista_temp['min'] = number_format(1000, 0);
        $lista_temp['max'] = number_format(40000, 0);
        $lista_temp['pasarela'] = "";


        if($is_popup) {
            $ProductoMandantesData['todos']['length']++;
            array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        }  else array_push($ProductoMandantesData, $lista_temp);

        $lista_temp = [];
        $lista_temp['id'] = "FIXED-BANCOJMD4";
        $lista_temp['moneda'] = "JMD";
        $lista_temp['nombre'] = "Transfer via Bank Transfer - Sagicor Bank Jamaica";
        $lista_temp['valor'] = 5000;
        $lista_temp['imagen'] = "";
        $lista_temp['tiempo'] = "Regular time";
        $lista_temp['min'] = number_format(1000, 0);
        $lista_temp['max'] = number_format(40000, 0);
        $lista_temp['pasarela'] = "";



        if($is_popup) {
            $ProductoMandantesData['todos']['length']++;
            array_push($ProductoMandantesData['todos']['data'], $lista_temp);
        }  else array_push($ProductoMandantesData, $lista_temp);

    }
}


/**
 * Inserta un array en una posición específica dentro de otro array.
 *
 * @param array $arr El array original.
 * @param array $insertedArray El array a insertar.
 * @param int $position La posición en la que se debe insertar el array.
 * @return array El nuevo array con el array insertado en la posición especificada.
 */
function insertValueAtPosition($arr, $insertedArray, $position) {
    $i = 0;
    $new_array=[];
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
 * Inserta un objeto en una posición específica dentro de un array.
 *
 * @param array $array El array original.
 * @param mixed $object El objeto a insertar.
 * @param int $position La posición en la que se debe insertar el objeto.
 * @param string|null $name El nombre opcional para la clave del objeto insertado.
 * @return array El array con el objeto insertado en la posición especificada.
 */
function array_put_to_position(&$array, $object, $position, $name = null)
{
    $count = 0;
    $return = array();
    foreach ($array as $k => $v)
    {
        // insertar nuevo objeto
        if ($count == $position)
        {
            if (!$name) $name = $count;
            $return[$name] = $object;
            $inserted = true;
        }
        // insertar objeto antiguo
        $return[$k] = $v;
        $count++;
    }
    if (!$name) $name = $count;
    if (!$inserted) $return[$name];
    $array = $return;
    return $array;
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

if ($UsuarioTokenSite != null && $Usuario->paisId == 66 && $Usuario->mandante == '8'  && false    ) {

    $cookie =$UsuarioTokenSite->getCookie();

    if(($cookie != '' && $cookie != null ) || true){
        try {
            $cookie = json_decode($cookie);
            if (($cookie != null && in_array($cookie->RISK, array('RISK10', 'RISK0'))) || true ) {
                /*Definición y envío de productos adicionales por obtención de Cookie en token del sitio*/
                $ProductoMandantesDataFinal = array();

                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-MASTERCARD";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Master Card";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1681490718.png";
                $lista_temp['tiempo'] = "5 minutos";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(500, 0);
                $lista_temp['pasarela'] = "";
                $lista_temp['isConfigurable'] = "";

                if ($cookie->RISK == 'RISK10') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(100, 0);
                }
                if ($cookie->RISK == 'RISK0') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(2000, 0);
                }
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(100, 0);

                if((intval($hour) >=20 || (intval($hour) >0 && intval($hour) <7))   ) {

                    array_push($ProductoMandantesDataFinal, $lista_temp);
                }
                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-VISA";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "VISA";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1681490804.png";
                $lista_temp['tiempo'] = "5 minutos";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(500, 0);

                if ($cookie->RISK == 'RISK10') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(100, 0);
                }
                if ($cookie->RISK == 'RISK0') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(2000, 0);
                }

                $lista_temp['pasarela'] = "";
                $lista_temp['isConfigurable'] = "";
                if(( intval($hour) >=20 || (intval($hour) >0 && intval($hour) <7))  ) {

                    array_push($ProductoMandantesDataFinal, $lista_temp);

                }

                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-EFECTIVO";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Transferencia Bancaria";
                $lista_temp['valor'] = 20;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msj8212T1729878292.png";
                $lista_temp['tiempo'] = "5 minutos";


                    $lista_temp['min'] = number_format(20, 0);
                    $lista_temp['max'] = number_format(1000, 0);

                $lista_temp['pasarela'] = "";
                $lista_temp['isConfigurable'] = "";
                array_push($ProductoMandantesDataFinal, $lista_temp);

                foreach ($ProductoMandantesData as $productoMandantesDatum) {
                    //!in_array($productoMandantesDatum['id_pasarela'], array(214, 74, 141)) &&
                    if ( !in_array($productoMandantesDatum['id'], array(13437,8793))) {
                        if(( intval($hour) >=20 || (intval($hour) >0 && intval($hour) <7))) {
                            if (in_array($productoMandantesDatum['id'], array(16437, 25046))) {
                                continue;
                            }
                        }
                            array_push($ProductoMandantesDataFinal, $productoMandantesDatum);

                    }

                }


                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-3";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Pago online";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/productos/Pago-onlineT1625779317.png";
                $lista_temp['tiempo'] = "5 minutos";
                $lista_temp['min'] = number_format(20, 0);
                $lista_temp['max'] = number_format(3000, 0);
                $lista_temp['pasarela'] = "";
                $lista_temp['isConfigurable'] = "";
                //array_push($ProductoMandantesData, $lista_temp);


                $lista_temp = [];
                $lista_temp['id'] = "FIXED-BANCOECU2";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Red Activa Western Union";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1637954767.png";
                $lista_temp['tiempo'] = "2 horas";
                $lista_temp['min'] = number_format(1, 0);
                $lista_temp['max'] = number_format(5000, 0);
                $lista_temp['pasarela'] = "";

                //array_push($ProductoMandantesDataFinal, $lista_temp);


                $lista_temp = [];
                $lista_temp['id'] = "FIXED-BANCOECU5";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "FullCarga";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1669771359.png";
                $lista_temp['tiempo'] = "2 horas";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(5000, 0);
                $lista_temp['pasarela'] = "";

                //array_push($ProductoMandantesDataFinal, $lista_temp);
                $ProductoMandantesData = $ProductoMandantesDataFinal;

                $lista_temp = [];
                $lista_temp['id'] = "FIXED-BANCOECU6";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Farmacias Medicity";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1669771359.png";
                $lista_temp['tiempo'] = "2 horas";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(5000, 0);
                $lista_temp['pasarela'] = "";

                //array_push($ProductoMandantesDataFinal, $lista_temp);
                $ProductoMandantesData = $ProductoMandantesDataFinal;

                $lista_temp = [];
                $lista_temp['id'] = "FIXED-BANCOECU7";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Farmacias Economicas";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1696011878.png";
                $lista_temp['tiempo'] = "2 horas";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(5000, 0);
                $lista_temp['pasarela'] = "";

                //array_push($ProductoMandantesDataFinal, $lista_temp);
                $ProductoMandantesData = $ProductoMandantesDataFinal;

            }

        }catch (Exception $e){
        }
    }


}

if ($UsuarioTokenSite != null && $Usuario->paisId == 66 && $Usuario->mandante == '0'    ) {

    $cookie =$UsuarioTokenSite->getCookie();

    if(($cookie != '' && $cookie != null ) || true){
        try {
            $cookie = json_decode($cookie);
            if (($cookie != null && in_array($cookie->RISK, array('RISK10', 'RISK0'))) || true ) {
                /*Definición y envío de productos adicionales por obtención de Cookie en token del sitio*/
                $ProductoMandantesDataFinal = array();

                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-MASTERCARD";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Payphone: Mastercard";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1681490718.png";
                $lista_temp['tiempo'] = "Inmediato";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(500, 0);
                $lista_temp['pasarela'] = "https://images.virtualsoft.tech/m/msj0212T1747330903.png";
                $lista_temp['isConfigurable'] = "";

                if ($cookie->RISK == 'RISK10') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(100, 0);
                }
                if ($cookie->RISK == 'RISK0') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(2000, 0);
                }
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(100, 0);

                array_push($ProductoMandantesDataFinal, $lista_temp);

                $lista_temp = [];
                $lista_temp['id'] = "PREPROD-VISA";
                $lista_temp['moneda'] = "USD";
                $lista_temp['nombre'] = "Payphone: VISA";
                $lista_temp['valor'] = 50;
                $lista_temp['imagen'] = "https://images.virtualsoft.tech/m/msjT1681490804.png";
                $lista_temp['tiempo'] = "Inmediato";
                $lista_temp['min'] = number_format(10, 0);
                $lista_temp['max'] = number_format(500, 0);
                $lista_temp['pasarela'] = "https://images.virtualsoft.tech/m/msj0212T1747330903.png";

                if ($cookie->RISK == 'RISK10') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(100, 0);
                }
                if ($cookie->RISK == 'RISK0') {
                    $lista_temp['min'] = number_format(10, 0);
                    $lista_temp['max'] = number_format(2000, 0);
                }

                $lista_temp['isConfigurable'] = "";
                array_push($ProductoMandantesDataFinal, $lista_temp);


            }

            foreach ($ProductoMandantesData as $productoMandantesDatum) {
                //!in_array($productoMandantesDatum['id_pasarela'], array(214, 74, 141)) &&
                if ( in_array($productoMandantesDatum['id'], array(28356,25046))) {
                    continue;


                }
                array_push($ProductoMandantesDataFinal, $productoMandantesDatum);

            }
            $ProductoMandantesData = $ProductoMandantesDataFinal;

        }catch (Exception $e){
        }
    }


}

/*Formateo de respuesta*/
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $ProductoMandantesData;
