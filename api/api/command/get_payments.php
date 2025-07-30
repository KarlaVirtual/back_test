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
 * get_payments
 *
 * Obtener los pagos disponibles para el usuario de la sesión.
 *
 * @param object $json->session->usuario Usuario de la sesión.
 * @param int $json->rid ID de la solicitud.
 *
 * @return array $response Respuesta que contiene el código de estado, ID de la solicitud y datos de los productos mandantes.
 *  - code:int Código de estado de la respuesta.
 *  - rid:int ID de la solicitud.
 *  - data:array Datos de los productos mandantes.
 *      - name:string Nombre del producto.
 *      - displayName:string Nombre para mostrar del producto.
 *      - canDeposit:bool Indica si se puede depositar.
 *      - canWithdraw:bool Indica si se puede retirar.
 *      - order:int Orden del producto.
 *      - depositInfoTextKey:string Clave de texto de información de depósito.
 *      - stayInSameTabOnDeposit:bool Indica si se mantiene en la misma pestaña al depositar.
 *      - depositFormFields:array Campos del formulario de depósito.
 *      - predefinedFields:array Campos predefinidos.
 *      - info:array Información del producto.
 *          - depositFee:float Tarifa de depósito.
 *          - withdrawFee:float Tarifa de retiro.
 *          - depositProcessTime:string Tiempo de procesamiento de depósito.
 *          - withdrawProcessTime:string Tiempo de procesamiento de retiro.
 *          - minDeposit:float Depósito mínimo.
 *          - maxDeposit:float Depósito máximo.
 *          - minWithdraw:float Retiro mínimo.
 *          - maxWithdraw:float Retiro máximo.
 *
 * @throws Exception Si ocurre un error al obtener los productos mandantes.
 */

// Se crea una instancia de UsuarioMandante utilizando la información de la sesión.
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
//$Pais = new Pais($Usuario->paisId);

// Definición de variables para la consulta
$MaxRows = 1000; // Número máximo de filas a recuperar
$OrderedItem = 1; // Ítem ordenado
$SkeepRows = 0; // Fila para omitir

// Se inicializa un arreglo de reglas para las consultas
$rules = [];
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));

// Si el usuario no es un usuario específico, se agregan más reglas
if($Usuario->usuarioId != 886){
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "37", "op" => "ne"));
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "45", "op" => "ne"));
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "41", "op" => "ne"));
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "50", "op" => "ne"));
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "61", "op" => "ne"));
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "45,41,50,74,87,94", "op" => "ni"));
}

// Se agrupan las reglas en un filtro
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

// Se crea una instancia de ProductoMandante
$ProductoMandante = new ProductoMandante();

// Se recuperan los productos del mandante y país utilizando el filtro JSON
$ProductoMandantes = $ProductoMandante->getProductosMandantePaisCustom(" producto.producto_id , producto.descripcion ,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.min ELSE producto_mandante.min END min,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.max ELSE producto_mandante.max END max,CASE producto_mandante.filtro_pais WHEN 'A' THEN prodmandante_pais.tiempo_procesamiento ELSE producto_mandante.tiempo_procesamiento END tiempo, producto.image_url imagen,producto.orden ", "producto.orden", "DESC,producto.descripcion", $SkeepRows, $MaxRows, $json2, true,$Usuario->paisId);

// Se decodifica el JSON de productos mandantes
$ProductoMandantes = json_decode($ProductoMandantes);

// Se inicializa un arreglo para almacenar los datos de productos mandantes
$ProductoMandantesData = array();

$moneda = $Usuario->moneda;
foreach ($ProductoMandantes->data as $key => $value) {
    /*Definición e iteración para objetos de respuesta */

    $array= array();
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

    array_push($ProductoMandantesData, $array);
}
/**
 * Se crea un arreglo que almacena la información de un punto de venta.
 */
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

array_push($ProductoMandantesData, $array);

/*Formato de respuesta*/
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $ProductoMandantesData;

