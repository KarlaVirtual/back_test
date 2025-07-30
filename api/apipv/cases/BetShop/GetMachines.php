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
 * Obtiene una lista de máquinas basándose en los filtros y parámetros proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params ->count Número máximo de registros a devolver.
 * @param int $params ->start Número de registros a omitir para la paginación.
 * @param int|null $params ->Id Identificador del punto de venta.
 * @param int|null $params ->OrderedItem Orden de los elementos. Por defecto, 1.
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - pos (int): Posición inicial de los datos devueltos.
 *                         - total_count (int): Número total de registros encontrados.
 *                         - data (array): Información de las máquinas, incluyendo:
 *                             - id (int): Identificador del usuario.
 *                             - Id (int): Identificador del usuario.
 *                             - Name (string): Nombre o descripción de la máquina.
 *                             - Phone (string): Teléfono asociado.
 *                             - Email (string): Correo electrónico asociado.
 *                             - CityName (string): Nombre de la ciudad asociada.
 *                             - DepartmentName (string): Nombre del departamento asociado.
 *                             - RegionName (string): Nombre de la región asociada.
 *                             - CurrencyId (string): Moneda asociada.
 *                             - Address (string): Dirección asociada.
 *                             - CreatedDate (string): Fecha de creación.
 *                             - LastLoginDateLabel (string): Fecha del último inicio de sesión.
 *                             - Type (string): Tipo de máquina.
 *                             - MinBet (float): Apuesta mínima permitida.
 *                             - PreMatchPercentage (float): Porcentaje de comisión para apuestas pre-match.
 *                             - LivePercentage (float): Porcentaje de comisión para apuestas en vivo.
 *                             - RecargasPercentage (float): Porcentaje de comisión para recargas.
 *                             - Amount (float): Cupo de recarga.
 *                             - AmountBetting (float): Créditos base para apuestas.
 */

/**
 * @OA\Post(path="apipv/BetShop/GetMachines", tags={"BetShop"}, description = "GetMachines",
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *              @OA\Property(
 *                   property="count",
 *                   description="count",
 *                   type="integer",
 *                   example= 2
 *               ),
 *               @OA\Property(
 *                   property="start",
 *                   description="start",
 *                   type="integer",
 *                   example= 3
 *               )
 *             )
 *         )
 *     ),
 *
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *              @OA\Property(
 *                   property="data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *                 ),
 *
 *               @OA\Property(
 *                   property="pos",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="total_account",
 *                   description="Total de registros",
 *                   type="integer",
 *                   example= 20
 *               )
 *             )
 *           )
 *         )
 * )
 */


/* recupera parámetros de solicitud para paginación y gestión de datos. */
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$Id = $_REQUEST["Id"];


$seguir = true;


/* inicializa un objeto y verifica condiciones sobre variables específicas. */
$Mandante = new Mandante();

if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Verifica el perfil de usuario para decidir si continuar con la acción. */
if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {
    $seguir = false;

}

if ($seguir) {


    /* Genera reglas de filtrado basadas en condiciones de usuario y parámetros dados. */
    $rules = [];

    if ($Id != "") {
        array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$Id", "op" => "eq"));
    }

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Condiciona reglas basadas en el perfil de usuario "CONCESIONARIO2" en PHP. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Verifica el perfil del usuario y agrega reglas para concesionarios específicos. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }

    /* Agrega reglas de filtrado para perfiles de usuario y condiciones por país. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "MAQUINAANONIMA", "op" => "eq"));


    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global

    /* verifica condiciones de sesión y agrega reglas para un array. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Se crea un filtro JSON y se consulta puntos de venta personalizados. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $PuntoVenta = new PuntoVenta();


    $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true);


    /* convierte una cadena JSON en un objeto PHP y inicializa un arreglo vacío. */
    $mandantes = json_decode($mandantes);


    $final = [];

    foreach ($mandantes->data as $key => $value) {


        /* Se crea un objeto Usuario y se valida el estado según perfiles de sesión. */
        $Usuario = new Usuario($value->{"punto_venta.usuario_id"});

        $array = [];
        $array["StateValidate"] = $value->{"usuario.estado_valida"};

        if ($_SESSION["win_perfil2"] != "CONCESIONARIO" && $_SESSION["win_perfil2"] != "CONCESIONARIO2" && $_SESSION["win_perfil2"] != "CONCESIONARIO3") {
            $array["Action"] = $value->{"usuario.estado_valida"};

        } else {
            /* Asignando una cadena vacía a "Action" si no se cumple una condición específica. */

            $array["Action"] = '';
        }

        /* Asigna valores de un objeto a un array asociativo para su manipulación. */
        $array["id"] = $value->{"punto_venta.usuario_id"};
        $array["Id"] = $value->{"punto_venta.usuario_id"};
        $array["Name"] = $value->{"punto_venta.descripcion"};
        $array["Phone"] = $value->{"punto_venta.telefono"};
        $array["Email"] = $value->{"usuario.email"};
        $array["CityName"] = $value->{"ciudad.ciudad_nom"};

        /* Asigna valores a un array usando propiedades de un objeto en PHP. */
        $array["DepartmentName"] = $value->{"departamento.depto_nom"};
        $array["RegionName"] = $value->{"pais.pais_nom"};
        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["Address"] = $value->{"punto_venta.direccion"};
        $array["CreatedDate"] = $value->{"usuario.fecha_crea"};;
        $array["LastLoginDateLabel"] = $value->{"usuario.fecha_ult"};;


        /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
        $array["Type"] = $value->{"tipo_punto.descripcion"};
        $array["MinBet"] = $value->{"usuario_premiomax.apuesta_min"};
        $array["PreMatchPercentage"] = $value->{"punto_venta.porcen_comision"};
        $array["LivePercentage"] = $value->{"punto_venta.porcen_comision"};
        $array["RecargasPercentage"] = $value->{"punto_venta.porcen_comision2"};

        $array["Amount"] = $value->{"punto_venta.cupo_recarga"};

        /* Se almacenan montos en un array y se agrega a una lista final. */
        $array["AmountBetting"] = $value->{"punto_venta.creditos_base"};


        $array["Amount"] = $value->{"punto_venta.cupo_recarga"};
        $array["AmountBetting"] = $Usuario->getBalance();

        array_push($final, $array);

    }


    /* inicializa una respuesta sin errores y gestiona la posición de filas saltadas. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    //$response["Data"] = array();
    //$response["Data"]["Objects"] = $final;

    $response["pos"] = $SkeepRows;

    /* Asigna el conteo de mandantes y datos finales a la respuesta. */
    $response["total_count"] = $mandantes->count[0]->{".count"};
    $response["data"] = $final;

    //Objects

} else {
    /* inicializa una respuesta vacía con posición y conteo total en cero. */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
