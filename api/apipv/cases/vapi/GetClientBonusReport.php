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
 * vapi/GetClientBonusReport
 *
 * Consulta de Bonos de Usuario
 *
 * Recupera los bonos asociados a un usuario en función de los parámetros de búsqueda proporcionados.
 * Permite filtrar por fechas, estado del bono y otros criterios relevantes.
 *
 * @param string $FromDateLocal : Fecha de inicio del rango de búsqueda.
 * @param string $ToDateLocal : Fecha de fin del rango de búsqueda.
 * @param string $ClientId : Identificador del cliente.
 * @param bool $IsDetails : Indica si se debe incluir información detallada en la respuesta.
 * @param string $PartnerBonusId : Identificador del bono del socio.
 * @param int $MaxRows : Número máximo de registros a recuperar (por defecto 10,000).
 * @param int $OrderedItem : Criterio de ordenamiento (por defecto 1).
 * @param int $SkeepRows : Número de filas a omitir en la consulta (por defecto 0).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si ocurrió un error en la operación.
 *  - *items* (array): Lista de bonos recuperados con detalles específicos de cada bono.
 *  - *total* (int): Número total de bonos encontrados según los criterios de búsqueda.
 *
 *
 * @throws Exception Si ocurre un error en la consulta o procesamiento de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* asigna valores de parámetros a variables en un script PHP. */
$FromDateLocal = $params->FromDateLocal;
$ToDateLocal = $params->ToDateLocal;
$ClientId = $params->clientId;
$IsDetails = $params->IsDetails;
$PartnerBonusId = $params->PartnerBonusId;


$MaxRows = $params->MaxRows;

/* Asigna valores de parámetros y establece un valor predeterminado para SkeepRows. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados si las variables están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}



/* Se construye un array de reglas para filtrar datos específicos. */
$rules = [];

//  array_push($rules, array("field" => "usuario_log.usuario_id", "data" => "$UserId", "op" => "eq"));
//array_push($rules, array("field" => "usuario_log.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
// array_push($rules, array("field" => "usuario_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
//  array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));

if ($PartnerBonusId != "") {
    array_push($rules, array("field" => "usuario_bono.bono_id", "data" => "$PartnerBonusId", "op" => "eq"));

}

/* Crea un filtro JSON con reglas y agrupación para una consulta SQL. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_bono.*,usuario.moneda,bono_interno.* ";



/* Se crea un objeto para obtener bonos de usuario y decodificar datos JSON. */
$UsuarioBono = new UsuarioBono();
$data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);

$final = array();

/* Inicializa la variable $totalAmount en cero, preparándola para sumar valores posteriores. */
$totalAmount = 0;
foreach ($data->data as $value) {

/* crea un array asociativo con datos de usuario y bono. */
    $array = array();

    $array["id"] = $value->{"usuario_bono.usubono_id"};
    $array["timestamp"] = strtotime($value->{"usuario_bono.fecha_crea"});
    $array["userid"] = $value->{"usuario_bono.usuario_id"};
    $array["bonusid"] = $value->{"usuario_bono.bono_id"};

/* Se asignan valores de un objeto a un array y se calcula el rollover restante. */
    $array["rollover"] = $value->{"usuario_bono.rollower_requerido"};
    $array["rolloverWagered"] = $value->{"usuario_bono.apostado"};
    $array["rolloverRemaining"] = $array["RolloverRequired"] - $array["RolloverWagered"];

    $array["bonusname"] = $value->{"bono_interno.descripcion"};
    if ($value->{"bono_interno.tipo"} == "2") {
        $array["bonustype"] = "deposit";
    } else {
/* asigna un tipo de bono según el valor de "bono_interno.tipo". */

        if ($value->{"bono_interno.tipo"} == "3") {
            $array["bonustype"] = "nodeposit";
        } else {
            $array["bonustype"] = "freebet";
        }
    }
    
/* Se asigna un estado a un array según el valor de "usuario_bono.estado". */
if ($value->{"usuario_bono.estado"} == "A") {
        $array["status"] = "activated";

    } else {
        if ($value->{"usuario_bono.estado"} == "R") {
            $array["status"] = "released";

        } else {
            if ($value->{"usuario_bono.estado"} == "E") {
                $array["status"] = "expired";

            } else {
                $array["status"] = "inactive";

            }
        }
    }


/* asigna valores de un objeto a un array en PHP. */
    $array["ClientCurrency"] = $value->{"usuario.moneda"};
    $array["Amount"] = floatval($value->{"usuario_bono.valor"});

    $array["ClientBonusExpirationDateLocal"] = "";
    $array["BonusType"] = $value->{"bono_interno.tipo"};
    $array["RolloverWagered"] = $value->{"usuario_bono.apostado"};

/* Calcula el saldo restante y actualiza un total, agregando elementos a un arreglo. */
    $array["RolloverRemaining"] = $array["RolloverRequired"] - $array["RolloverWagered"];

    $totalAmount = $totalAmount + $array["Amount"];
    array_push($final, $array);
}



/* crea una respuesta estructurada sin errores, incluyendo elementos y total. */
$response["HasError"] = false;


$response = array(
    "items" => $final,
    "total" => intval($data->count[0]->{".count"})

);