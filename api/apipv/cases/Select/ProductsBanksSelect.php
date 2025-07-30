<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Banco;
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
 * ProductsBanksSelect
 *
 * Este script permite obtener una lista de productos y proveedores bancarios filtrados según las condiciones especificadas.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param string $params->Filter Palabra clave para filtrar productos por descripción.
 *
 * @return array Respuesta en formato JSON con las siguientes claves:
 * - HasError: (boolean) Indica si ocurrió un error.
 * - AlertType: (string) Tipo de alerta (por ejemplo, "success").
 * - AlertMessage: (string) Mensaje de alerta.
 * - ModelErrors: (array) Lista de errores del modelo.
 * - Data: (array) Datos finales con productos y proveedores.
 * - pos: (int) Posición inicial de los resultados.
 * - total_count: (int) Total de registros encontrados.
 * - data: (array) Datos finales con productos y proveedores.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */


/*error_reporting(E_ALL);
ini_set('display_errors', 'ON');*/

/* Inicializa un objeto y establece parámetros para filtrar y paginar resultados. */
$ProductoMandante = new ProductoMandante();
$keyword = $params->Filter;

$OrderedItem = 1;
$SkeepRows = 0;
$MaxRows = 100;


/* Se inicializa un array vacío llamado $rules en PHP. */
$rules = [];

if ($keyword != "" & $keyword != null) {

    /* Se agregan reglas de filtrado para distintos campos y condiciones en un arreglo. */
    array_push($rules, array("field" => "producto.descripcion", "data" => $keyword, "op" => "cn"));
    array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "subproveedor.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "subproveedor_mandante.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "proveedor_mandante.estado", "data" => "A", "op" => "eq"));
    /* Agrega reglas para validar condiciones según el estado y el mandante del usuario. */


// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Agrega una regla si "mandanteLista" de sesión no está vacía ni es "-1". */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    /* Condiciones para agregar reglas basadas en la sesión del país del usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "producto_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    } else {
        if ($_SESSION["PaisCondS"] != '') {
            array_push($rules, array("field" => "producto_mandante.pais_id", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
        }
    }
    array_push($rules, array("field" => "proveedor.tipo", "data" => "'PAYOUT', 'PAYMENT'", "op" => "in")); // Condicional que rigue los tipos de bancos
    /* Se crea un filtro JSON y se obtienen productos de una base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $productos = $ProductoMandante->getProductosMandanteCustom2("producto_mandante.prodmandante_id, producto.descripcion,proveedor.descripcion, producto.producto_id, subproveedor.tipo", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $productos = json_decode($productos);

    /* transforma datos de productos en un nuevo arreglo estructurado. */
    $final = [];


    foreach ($productos->data as $key => $value) {

        $array = [];


        $array["id"] = $value->{"producto.producto_id"};
        $array["value"] = $value->{"producto.descripcion"} . "(" . $value->{"proveedor.descripcion"} . ") - " . $value->{"subproveedor.tipo"};

        array_push($final, $array);


    }

}

/* Código inicializa respuesta sin errores y asigna datos finales a la variable. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna valores a las claves del array de respuesta. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $productos->count[0]->{".count"};
$response["data"] = $final;