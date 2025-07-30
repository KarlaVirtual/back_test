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
 * Inicializa un arreglo de respuesta y lo establece con valores predeterminados.
 * Luego, crea instancias de las clases UsuarioMandante y Usuario usando los datos del json.
 * Dependiendo del valor del mandante del Usuario, se instancian diferentes objetos de Proveedor.
 */


/**
 * Recurso que obtiene las tarjetas de crédito de un usuario.
 *
 * @param int $json->session->usuario ID del usuario que opera la sesión.
 * @param int $params->MaxRows Número máximo de filas a obtener.
 * @param int $params->OrderedItem Elemento ordenado.
 * @param int $params->SkeepRows Número de filas a omitir.
 *
 * @return array Respuesta en formato JSON:
 * - code (int) Código de respuesta.
 * - rid (int) ID de la transacción.
 * - data (array) Datos de las tarjetas de crédito:
 *   - id (int) ID de la tarjeta de crédito.
 *   - imagen (string) URL de la imagen de la tarjeta.
 *   - cuenta (string) Número de cuenta de la tarjeta.
 *   - description (string) Descripción de la tarjeta.
 *   - state (string) Estado de la tarjeta.
 *   - requiresVerification (string) Indica si se requiere verificación (S/N).
 *   - activateCvc (string) Indica si se activa la verificación CVC (S/N).
 * - total_count (int) Total de tarjetas de crédito obtenidas.
 */

$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


//$provider = $json->params->provider;

if($Usuario->mandante == "0"){
    $Proveedor = new  Proveedor("","PAYMENTEZ");
    $Activate = true;
}
if($Usuario->mandante == "8"){
    $Proveedor = new  Proveedor("","PAYMENTEZ");
    $Activate = true;
}
if($Usuario->mandante == "13"){
    $Proveedor = new  Proveedor("","PAYMENTEZ");
    $Activate = true;
}
if($Usuario->mandante == "2"){
    $Proveedor = new  Proveedor("","SAGICOR");
    $Activate = false;
}
if($Proveedor != null) {

    // Se inicializan las variables MaxRows, OrderedItem y SkeepRows a partir de los parámetros proporcionados.
    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    // Se asignan valores a MaxRows y SkeepRows desde un objeto JSON que contiene parámetros.
    $MaxRows = $json->params->count;
    $SkeepRows = $json->params->start;


    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 10;
    }

    // Se inicializa un arreglo para almacenar las reglas de filtrado.
    $rules = [];

    // Se añaden reglas de filtrado relacionadas con el usuario y el proveedor.
    array_push($rules, array("field" => "usuario_tarjetacredito.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "usuario_tarjetacredito.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));

    array_push($rules, array("field" => "usuario_tarjetacredito.estado", "data" => "'A','P'", "op" => "in"));

    // Se agrupan las reglas utilizando una operación lógica AND.
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    $select = "usuario_tarjetacredito.*";

    $UsuarioTarjetacredito = new UsuarioTarjetacredito();

// Se obtienen los datos de tarjetas de crédito del usuario mediante el método getUsuarioTarjetasCustom.
    $data = $UsuarioTarjetacredito->getUsuarioTarjetasCustom($select, "usuario_tarjetacredito.usutarjetacredito_id", 'desc', $SkeepRows, $MaxRows, $jsonfiltro, true);
    $tarjetas = json_decode($data);
    $tarjetasData = array();


    foreach ($tarjetas->data as $key => $value) {
        //Almacenamiento de cada una de las tarjetas obtenidas
        $array = array();


        $array["id"] = $value->{"usuario_tarjetacredito.usutarjetacredito_id"};
        $array["imagen"] = "https://images.virtualsoft.tech/m/msjT1637706419.png"; //$value->{"usuario_tarjetacredito.imagen"};
        $array["cuenta"] = $value->{"usuario_tarjetacredito.cuenta"};
        $array["description"] = $value->{"usuario_tarjetacredito.descripcion"};
        $array["state"] = $value->{"usuario_tarjetacredito.estado"};

        if ($array["state"] === 'P') {

            $array["requiresVerification"] = "S";
        }

        if ($array["state"] === 'A') {

            $array["requiresVerification"] = "N";
        }
        if ($Activate == true) {
            $array["activateCvc"] = "S";
        } else {
            $array["activateCvc"] = "N";
        }
        array_push($tarjetasData, $array);

    }
/**
 * Genera la respuesta en formato de array que incluye códigos, datos de tarjetas y total de tarjetas.
 *
 * Dependiendo de la condición, llena el array $response con los datos correspondientes ya sea con
 * las tarjetas obtenidas o con un array vacío.
 */

    $response = array();
    $response["code"] = 0;
    $response["data"] = array(
        "cards" => $tarjetasData,
        "total_count" => $tarjetas->count[0]->{".count"}
    );

    $response["total_count"] = $tarjetas->count[0]->{".count"};

    $response["rid"] = $json->rid;
}else{
    //Formato de respuesta vacía
    $response = array();
    $response["code"] = 0;
    $response["data"] = array(
        "cards" => array(),
        "total_count" => 0
    );

    $response["total_count"] = $tarjetas->count[0]->{".count"};

    $response["rid"] = $json->rid;
}


