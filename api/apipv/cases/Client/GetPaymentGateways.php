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
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
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
 * Obtener las pasarelas de pago disponibles para un cliente.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $params ->ClientId ID del cliente.
 * @param string|null $params ->CodeBank Código del banco.
 * @param string|null $params ->Id ID de la cuenta de cobro.
 * @param string|null $params ->ProviderId ID del proveedor.
 *
 *
 * @return array $response Respuesta con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (success, danger, etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos de las pasarelas de pago disponibles.
 *
 * @throws Exception Si ocurre un error al procesar los datos.
 */


/* recibe y decodifica un JSON del cuerpo de una solicitud HTTP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ClientId = $params->ClientId;
$BancoId = $params->CodeBank;
$Id = $params->Id;

/* Asignación de parámetros para configuración de consulta: ID de proveedor, máximo y saltar filas. */
$ProviderId = $params->ProviderId;
$MaxRows = 100;
$SkeepRows = 0;

if ($BancoId == null) {

    /* crea objetos basados en un identificador, comprobando que no sea nulo. */
    $final = array();
    if ($Id != null && $Id != '') {
        $Cuentacobro = new CuentaCobro($Id);
        $Producto = new Producto($Cuentacobro->productoPagoId);
        $Proveedor = new Proveedor($Producto->proveedorId);

    } else {
        /* Crea un objeto `Proveedor` y un objeto `Producto` asociado a él. */

        $Proveedor = new Proveedor('', 'GLOBOKASRETIROS');
        $Producto = new Producto('', 'GlobokasRetiros', $Proveedor->proveedorId);

    }


    /* crea un array con información sobre un producto específico. */
    $array = array();

    // $array["ProviderId"] = $value->{"proveedor.proveedor_id"};
    //$array["ProviderName"] = $value->{"proveedor.descripcion"};
    $array["ProductId"] = $Producto->productoId;
    $array["ProductName"] = $Producto->descripcion;


    /* Agrega un arreglo a otro y configura respuesta sin errores. */
    array_push($final, $array);


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";

    /* Se inicializa un arreglo de errores y se asigna datos finales a la respuesta. */
    $response["ModelErrors"] = [];

    $response["Data"] = $final;
} else {

    /* Inicializa un arreglo de reglas y crea un objeto Usuario si ClientId no está vacío. */
    $rules = [];


    if ($ClientId != '') {
        $Usuario = new Usuario($ClientId);
    }

    /* Condicional que agrega reglas según el valor de $BancoId y $ClientId. */
    if ($BancoId != "" && $BancoId != null) {

        array_push($rules, array("field" => "banco_detalle.banco_id", "data" => "$BancoId", "op" => "eq"));
    } else {
        if ($ClientId == '72909') {
            array_push($rules, array("field" => "banco_detalle.banco_id", "data" => "595", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "banco_detalle.banco_id", "data" => "", "op" => "eq"));

        }


    }
//array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => "$ClientId", "op" => "eq"));

    /* Agrega reglas a un array según condiciones específicas de estado y proveedor. */
    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

    if ($ProviderId != "" && $ProviderId != null) {

        array_push($rules, array("field" => "subproveedor.subproveedor_id", "data" => $ProviderId, "op" => "eq"));
    }

// Si el usuario esta condicionado por el mandante y no es de Global

    /* Agrega reglas basadas en la sesión para filtrar datos de "mandante". */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "banco_detalle.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "banco_detalle.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    /* Condiciona la inclusión de reglas basadas en la existencia de un usuario. */
    if ($Usuario != null) {
        array_push($rules, array("field" => "banco_detalle.mandante", "data" => $Usuario->mandante, "op" => "eq"));

    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte datos a JSON, los consulta y los decodifica nuevamente. */
    $json = json_encode($filtro);

    $BancoDetalle = new \Backend\dto\BancoDetalle();

    $bancos = $BancoDetalle->queryBancodetallesCustom("banco.banco_id,banco.descripcion,producto.producto_id,producto.descripcion,subproveedor.subproveedor_id,subproveedor.descripcion", "banco_detalle.bancodetalle_id", "desc", $SkeepRows, $MaxRows, $json, true, "producto.producto_id");

    $bancos = json_decode($bancos);


    /* crea un arreglo final con información de productos de bancos. */
    $final = array();
    foreach ($bancos->data as $key => $value) {
        $array = array();

        // $array["ProviderId"] = $value->{"proveedor.proveedor_id"};
        //$array["ProviderName"] = $value->{"proveedor.descripcion"};
        $array["ProductId"] = $value->{"producto.producto_id"};
        $array["ProductName"] = $value->{"producto.descripcion"} . " (" . $value->{"subproveedor.descripcion"} . ")";


        array_push($final, $array);
    }


    /* inicializa una respuesta exitoso sin errores ni alertas. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = $final;
}