<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
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
use Backend\dto\Subproveedor;
use Backend\dto\proveedorMandante;
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
use Backend\mysql\proveedorMandanteMySqlDAO;
use Backend\mysql\SubproveedorMySqlDAO;
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
 * Providers/GetProviders
 *
 * Obtiene una lista paginada de subproveedores con filtros personalizados.
 *
 * Este método recibe parámetros desde `$_REQUEST` y `$params` para filtrar y paginar los subproveedores,
 * y devuelve una lista de ellos con su información básica (ID, proveedor ID, nombre, tipo, estado, verificación,
 * abreviado, imagen, credenciales). Se pueden aplicar filtros como ID, nombre, estado, abreviado, tipo y verificación.
 * Además, permite la paginación.
 *
 * @param object $params Objeto que contiene los parámetros de orden, filtrado y paginación.
 *  - *OrderedItem* (int): Elemento por el cual se ordena la lista de subproveedores.
 *  - *start* (int): Número de la primera fila (para paginación).
 *  - *count* (int): Número de elementos a retornar.
 *  - *Id* (string): ID del subproveedor para filtrar.
 *  - *Name* (string): Nombre del subproveedor para filtrar.
 *  - *IsActivate* (string): Estado de activación del subproveedor ("A" para activado, "I" para inactivado).
 *  - *Type* (int): Tipo del subproveedor (1 = CASINO, 2 = LIVECASINO, 3 = PAYMENT, etc.).
 *  - *IsVerified* (string): Estado de verificación del subproveedor ("A" para verificado, "I" para no verificado).
 *  - *Abbreviated* (string): Abreviatura del subproveedor para filtrar.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta que se mostrará (por ejemplo, "success" si la operación fue exitosa).
 *  - *AlertMessage* (string): Mensaje que se mostrará junto a la alerta.
 *  - *ModelErrors* (array): Retorna un array vacío en este caso.
 *  - *pos* (int): Número de la fila de inicio (paginación).
 *  - *total_count* (int): Número total de subproveedores disponibles que cumplen los filtros.
 *  - *data* (array): Contiene los subproveedores obtenidos, cada uno con los atributos "Id", "ProviderId", "Name", "Type", "IsActivate", "IsVerified", "Abbreviated", "Image", y "Credentials".
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* captura variables de solicitud HTTP usando PHP. */
$Id = $_REQUEST["Id"];
$Name = $_REQUEST["Name"];
$IsActivate = $_REQUEST["IsActivate"];
$Type = $_REQUEST["Type"];
$IsVerified = $_REQUEST["IsVerified"];
$Abbreviated = $_REQUEST["Abbreviated"];


/* Se declara una variable vacía en PHP para almacenar el tipo de subproveedor. */
$tipoSubproveedor = '';

switch ($Type) {
    case 1:
        /* Asigna "CASINO" a la variable $tipoSubproveedor en el caso 1. */

        $tipoSubproveedor = "CASINO";
        break;
    case 2:
        /* Estructura de control que asigna "LIVECASINO" a $tipoSubproveedor en caso 2. */

        $tipoSubproveedor = "LIVECASINO";
        break;
    case 3:
        /* Asigna "PAYMENT" a la variable $tipoSubproveedor en un caso específico. */

        $tipoSubproveedor = "PAYMENT";
        break;
    case 4:
        /* Asigna "PAYOUT" a la variable $tipoSubproveedor si el caso es 4. */

        $tipoSubproveedor = "PAYOUT";
        break;
    case 5:
        /* Condicional asigna "SPORTS" a $tipoSubproveedor si se cumple el caso 5. */

        $tipoSubproveedor = "SPORTS";
        break;
    case 6:
        /* asigna el valor "POKER" a la variable $tipoSubproveedor si el caso es 6. */

        $tipoSubproveedor = "POKER";
        break;
    case 7:
        /* asigna "LOTERIA" a la variable $tipoSubproveedor si el caso es 7. */

        $tipoSubproveedor = "LOTERIA";
        break;
    case 8:
        /* asigna el valor "VERIFICATION" a $tipoSubproveedor para el caso 8. */

        $tipoSubproveedor = "VERIFICATION";
        break;
    case 9:
        /* Establece el tipo de subproveedor como "VERIFY" cuando el caso es 9. */

        $tipoSubproveedor = "VERIFY";
        break;
    case 10:
        /* Asignación del tipo de subproveedor como "WHATSAPP" en el caso 10. */

        $tipoSubproveedor = "WHATSAPP";
        break;
    default:
        # code...
        break;
}


/* obtiene parámetros de solicitud y establece valores predeterminados para paginación. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}


/* Se construyen reglas de filtrado basadas en las variables $Id y $Name. */
$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "subproveedor.subproveedor_id", "data" => $Id, "op" => "eq"));
}

if ($Name != "") {
    array_push($rules, array("field" => "subproveedor.descripcion", "data" => $Name, "op" => "eq"));
}


/* Agrega reglas a un array basado en condiciones de activación y abreviatura. */
if ($IsActivate == "A") {
    array_push($rules, array("field" => "subproveedor.estado", "data" => "A", "op" => "eq"));
} else if ($IsActivate == "I") {
    array_push($rules, array("field" => "subproveedor.estado", "data" => "I", "op" => "eq"));
}

if ($Abbreviated != "") {
    array_push($rules, array("field" => "subproveedor.abreviado", "data" => $Abbreviated, "op" => "eq"));
}


/* Agrega reglas a un arreglo si las condiciones son verdaderas. */
if ($Type != "" && $Type != 0) {
    array_push($rules, array("field" => "subproveedor.tipo", "data" => $tipoSubproveedor, "op" => "eq"));
}


if ($IsVerified != "") {
    array_push($rules, array("field" => "subproveedor.verifica", "data" => $IsVerified, "op" => "eq"));
}


/* Codifica un filtro en JSON y obtiene subproveedores personalizados según criterios específicos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Subproveedor = new Subproveedor();

$subproveedores = $Subproveedor->getSubproveedoresCustom("subproveedor.* ", "subproveedor.subproveedor_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Decodifica datos JSON y los almacena en un array vacío llamado $final. */
$subproveedores = json_decode($subproveedores);

$final = [];

foreach ($subproveedores->data as $key => $value) {


    /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
    $array = [];

    $array["Id"] = $value->{"subproveedor.subproveedor_id"};
    $array["ProviderId"] = $value->{"subproveedor.proveedor_id"};
    $array["Name"] = $value->{"subproveedor.descripcion"};
    $array["Type"] = $value->{"subproveedor.tipo"};

    /* Extrae y almacena propiedades de un objeto en un array asociativo. */
    $array["IsActivate"] = $value->{"subproveedor.estado"};
    $array["IsVerified"] = $value->{"subproveedor.verifica"};
    $array["Abbreviated"] = $value->{"subproveedor.abreviado"};
    $array["Image"] = $value->{"subproveedor.imagen"};
    $credentials = !empty($value->{"subproveedor.credentials"}) ? json_decode($value->{"subproveedor.credentials"}) : null;
    $array["Credentials"] = $credentials;

    switch ($value->{"subproveedor.tipo"}) {
        case "CASINO":
            /* Asigna "CASINO" al tipo en un arreglo basado en un caso específico. */

            $array["Type"] = "CASINO";

            break;

        case "LIVECASINO":
            /* asigna el tipo "LIVECASINO" a un array basado en un case específico. */

            $array["Type"] = "LIVECASINO";

            break;

        case "PAYMENT":
            /* Asigna el tipo "PAYMENT" a un array según la condición del caso. */

            $array["Type"] = "PAYMENT";

            break;

        case "PAYOUT":
            /* asigna "PAYOUT" a un elemento del array basado en un caso específico. */

            $array["Type"] = "PAYOUT";

            break;

        case "SPORTS":
            /* asigna "SPORTS" a la clave "Type" en un array. */

            $array["Type"] = "SPORTS";

            break;

    }


    /* Agrega el contenido de `$array` al final de `$final` en PHP. */
    array_push($final, $array);

}


/* inicializa una respuesta sin errores, con tipo y mensaje de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;

/* asigna el conteo y datos de subproveedores a la respuesta. */
$response["total_count"] = $subproveedores->count[0]->{".count"};
$response["data"] = $final;
