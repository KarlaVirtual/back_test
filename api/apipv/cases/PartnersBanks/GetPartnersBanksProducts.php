<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Banco;
use Backend\dto\BancoMandante;
use Backend\dto\BancoDetalle;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BancoMySqlDAO;
use Backend\mysql\BancoMandanteMySqlDAO;
use Backend\mysql\BancoDetalleMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Obtener bancos y productos asociados a un partner.
 *
 * Este script permite obtener los bancos y productos asociados a un partner, 
 * aplicando filtros y reglas específicas según los parámetros enviados.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param int $params->Id ID del banco asociado.
 * @param string $params->Products Productos para filtro.
 * @param int $params->Partner ID del partner asociado.
 * @param int $params->Product ID del producto asociado.
 * @param int $params->BankId ID del banco.
 * 
 * 
 * @param array $_REQUEST Arreglo con los siguientes valores:
 * @param string $_REQUEST["Id"] ID del banco asociado.
 * @param string $_REQUEST["Products"] Productos para filtro.
 * @param string $_REQUEST["Partner"] ID del partner asociado.
 * @param string $_REQUEST["Product"] ID del producto asociado.
 * @param string $_REQUEST["BankId"] ID del banco.
 * @param string $_REQUEST["IsActivate"] Estado del banco (A: Activo, I: Inactivo).
 * @param string $_REQUEST["Name"] Nombre del banco.
 * @param string $_REQUEST["CountrySelect"] ID del país asociado.
 * @param string $_REQUEST["TypeDevice"] Tipo de dispositivo.
 * @param string $_REQUEST["Desktop"] Estado de escritorio (A: Activo, I: Inactivo).
 * @param string $_REQUEST["Mobile"] Estado de móvil (A: Activo, I: Inactivo).
 * @param string $_REQUEST["count"] Número máximo de filas (opcional, predeterminado: 1000).
 * @param string $_REQUEST["start"] Número de filas a omitir (opcional, predeterminado: 0).
 * @param string $_REQUEST["CountrySelect"] País para filtro (opcional).
 * @param string $_REQUEST["sort[Order]"] Orden de los datos (opcional, valores: "asc", "desc").
 * 
 * 
 *
 * @return array $response Respuesta con los siguientes valores:
 *     - bool $response["HasError"] Indica si hubo un error.
 *     - string $response["AlertType"] Tipo de alerta (success o mensaje de error).
 *     - string $response["AlertMessage"] Mensaje de alerta.
 *     - array $response["ModelErrors"] Errores del modelo (vacío si no hay errores).
 *     - int $response["pos"] Posición inicial de los datos.
 *     - int $response["total_count"] Total de registros encontrados.
 *     - array $response["data"] Datos procesados.
 */

//Verificamos data enviada por Frontend

/* obtiene parámetros de solicitud para manejar paginación de datos. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Establece valores predeterminados para `$OrderedItem` y `$MaxRows` si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Asigna valores de parámetros a variables para su uso posterior en el código. */
$Id = $params->Id;
$Products = $params->Products;
$Partner = $params->Partner;
$Product = $params->Product;
$BankId = $params->BankId;


$Id = $_REQUEST["Id"];

/* Valida y asigna valores de entrada según condiciones específicas en PHP. */
$IsActivate = ($_REQUEST["IsActivate"] == "A" || $_REQUEST["IsActivate"] == "I") ? $_REQUEST["IsActivate"] : '';;
$Products = $_REQUEST["Products"];
$Partner = $_REQUEST["Partner"];
$Product = $_REQUEST["Product"];
$BankId = $_REQUEST["BankId"];
$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';

/* obtiene y verifica datos de solicitudes HTTP. */
$Name = $_REQUEST["Name"];
$CountrySelect = $_REQUEST["CountrySelect"];
$TypeDevice = $_REQUEST["TypeDevice"];

$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';

/* modifica variables según sus valores iniciales, asignando nuevas letras. */
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
    $Desktop = 'N';
}

if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    /* asigna 'N' a $Mobile si su valor es 'I'. */

    $Mobile = 'N';
}

/* Código inicializa un array y establece una respuesta sin errores y éxito. */
$final = array();


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* inicializa un arreglo de respuesta con errores y datos procesados. */
$response["ModelErrors"] = [];


$response["pos"] = $SkeepRows;
$response["total_count"] = 0;
$response["data"] = $final;

// Si no se ha seleccionado pais, se returna data vacia
if ($CountrySelect != '' && $CountrySelect != '0') {

    // Instanciamos la clase banco detalle para obtener los productos a asociar a un banco que ya están asociados a un partner y pais

    /* Inicializa un objeto y aplica un filtro basado en una variable de escritorio. */
    $BancoDetalle = new BancoDetalle();

    //Inicializamos los filtros
    $rules = [];


    if ($Desktop != "") {
        array_push($rules, array("field" => "banco.desktop", "data" => "$Desktop", "op" => "eq"));
    }


    /* Añade reglas basadas en condiciones de variables Mobile e Id. */
    if ($Mobile != "") {
        array_push($rules, array("field" => "banco.mobile", "data" => "$Mobile", "op" => "eq"));
    }

    if ($Id != "") {
        array_push($rules, array("field" => "banco_mandante.bancomandante_id", "data" => "$Id", "op" => "eq"));
    }

    /* Agrega condiciones a un array de reglas según selecciones de país y estado. */
    if ($CountrySelect != "") {
        array_push($rules, array("field" => "banco_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }


    if ($IsActivate == "I") {
        array_push($rules, array("field" => "banco_detalle.estado", "data" => "I", "op" => "eq"));
    } else if ($IsActivate == "A") {
        /* Añade una regla si $IsActivate es "A", verificando el estado de banco_detalle. */

        array_push($rules, array("field" => "banco_detalle.estado", "data" => "A", "op" => "eq"));
    }


    /* Valida si el socio pertenece a una lista nativa y agrega regla si es cierto. */
    if ($Partner != "") {


        if (!in_array($Partner, explode(',', $_SESSION["mandanteLista"]))) {
            throw new Exception("Inusual Detected", "11");
        }

        array_push($rules, array("field" => "banco_mandante.mandante", "data" => "$Partner", "op" => "eq"));
    } else {
        /* Lanza una excepción si se detecta una anomalía en el código. */

        throw new Exception("Inusual Detected", "11");
    }


    /* Condicionalmente agrega reglas a un arreglo según el valor de $BankId y $_SESSION["Global"]. */
    if ($BankId != "") {
        if ($_SESSION["Global"] == "S") {
            array_push($rules, array("field" => "banco.banco_id", "data" => "$BankId", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "banco_mandante.banco_id", "data" => "$BankId", "op" => "eq"));

        }

    }


    /* agrega una regla basada en el estado de una variable de sesión. */
    if ($Product != "") {
        if ($_SESSION["Global"] == "S") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "banco_detalle.producto_id", "data" => "$Product", "op" => "eq"));

        }

    }


    /* Agrega reglas de filtrado a un arreglo según los valores de ProviderId y Name. */
    if ($ProviderId != "") {

        array_push($rules, array("field" => "banco.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }

    if ($Name != "") {

        array_push($rules, array("field" => "banco.descripcion", "data" => "$Name", "op" => "cn"));
    }


    /* verifica un tipo de dispositivo y define una variable de orden. */
    if ($TypeDevice != "") {

        // array_push($rules, array("field" => "banco.descripcion", "data" => "$TypeDevice", "op" => "cn"));
    }


    $orden = "banco_mandante.bancomandante_id";

    /* Establece el criterio de ordenamiento basado en la solicitud del usuario. */
    $ordenTipo = "asc";

    if ($_REQUEST["sort[Order]"] != "") {
        $orden = "banco_mandante.orden";
        $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

    }

    /* filtra y obtiene datos de bancos, productos, partners y países. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    //Obtenemos los bancos que ya han sido asignados a un partner y pais y los productos que ya han sido asignados a este banco
    $bancos = $BancoDetalle->getBancosMandanteProductosCustom(" banco_mandante.*,mandante.*,banco.*,proveedor.*,producto.*,banco_detalle.*", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true, $Partner, $CountrySelect);

    $bancos = json_decode($bancos);


    /* Se inicializa un array vacío en la variable $final para almacenar elementos. */
    $final = [];


    /* Itera sobre bancos, creando un array con detalles específicos de cada uno. */
    foreach ($bancos->data as $key => $value) {

        $array = [];

        $array["Id"] = $value->{"banco_detalle.bancodetalle_id"};
        $array["BankId"] = $value->{"banco.descripcion"} . " (" . $value->{"banco.banco_id"} . ")";
        $array["Product"] = array(
            "Id" => $value->{"producto.producto_id"},
            "Name" => $value->{"producto.descripcion"}
        );
        $array["Product"] = $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";
        $array["ProviderId"] = $value->{"proveedor.descripcion"};
        $array["Partner"] = $value->{"mandante.descripcion"};
        $array["TypeProduct"] = $value->{"proveedor.tipo"};
        $array["IsActivate"] = $value->{"banco_detalle.estado"};

        array_push($final, $array);

    }

    //Devolvemos los bancos y la cantidad de estos que ya han sido asignados a un partner y pais y los productos que ya han sido asignados a este banco

    /* asigna el conteo de bancos y datos finales a un array de respuesta. */
    $response["total_count"] = $bancos->count[0]->{".count"};
    $response["data"] = $final;

}


