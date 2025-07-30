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
 * Report/GetBalanceUsers
 *
 * Obtener la lista de usuarios con filtros personalizados.
 *
 * Este recurso permite recuperar una lista de usuarios registrados en el sistema con la opción de aplicar filtros personalizados
 * como fechas, país, mandante, balance negativo y otros parámetros.
 *
 * @param string $FromDateLocal : Fecha de inicio del filtro en formato local.
 * @param string $ToDateLocal : Fecha de fin del filtro en formato local.
 * @param int $ClientId : Identificador del cliente.
 * @param bool $IsDetails : Indica si se requieren detalles adicionales en la consulta.
 * @param int $PartnerBonusId : Identificador del bono asociado al usuario.
 * @param int $Type : Tipo de consulta (1 para agrupaciones por moneda, 0 para detalles de usuario).
 * @param int $MaxRows : Número máximo de filas a recuperar.
 * @param int $OrderedItem : Criterio de ordenamiento.
 * @param int $SkeepRows : Número de filas a omitir en la consulta (paginación).
 * @param int $UserId : Identificador del usuario.
 * @param int $PlayerId : Identificador del jugador.
 * @param string $CountrySelect : Código del país seleccionado para filtrar usuarios.
 * @param int $NegativeBalances : Indica si se incluyen usuarios con balance negativo (0 para excluirlos).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna un array vacío en caso de éxito.
 *  - *pos* (int): Posición actual en la paginación.
 *  - *total_count* (int): Número total de usuarios encontrados.
 *  - *data* (array): Contiene la información detallada de los usuarios consultados.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * @OA\Post(path="apipv/Report/GetBalanceUsers", tags={"Report"}, description = "",
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="count",
 *                   description="Número total de registros",
 *                   type="integer",
 *                   example= 469
 *               ),
 *               @OA\Property(
 *                   property="start",
 *                   description="Indice de posición de registros",
 *                   type="string",
 *                   example= "2"
 *               ),
 *               @OA\Property(
 *                   property="Type",
 *                   description="",
 *                   type="string",
 *                   example= "2"
 *               ),
 *               @OA\Property(
 *                   property="UserId",
 *                   description="",
 *                   type="string",
 *                   example= "2"
 *               ),
 *               @OA\Property(
 *                   property="PlayerId",
 *                   description="",
 *                   type="string",
 *                   example= "2"
 *               ),
 *               @OA\Property(
 *                   property="CountrySelect",
 *                   description="",
 *                   type="string",
 *                   example= "2"
 *               ),
 *             )
 *         ),
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
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
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
 *               ),
 *             )
 *         ),
 *         )
 * ),
 * @OA\Response (response="404", description="Not found")
 * ),
 */


/* Variables locales se asignan a partir de parámetros y solicitud del usuario. */
$FromDateLocal = $params->FromDateLocal;
$ToDateLocal = $params->ToDateLocal;
$ClientId = $params->ClientId;
$IsDetails = $params->IsDetails;
$PartnerBonusId = $params->PartnerBonusId;

$Type = $_REQUEST["Type"];


/* obtiene parámetros de solicitud para procesar datos de usuarios y elementos ordenados. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$UserId = $_REQUEST["UserId"];
$PlayerId = $_REQUEST["PlayerId"];
$CountrySelect = $_REQUEST["CountrySelect"];

/* verifica si `$SkeepRows` está vacío y decide continuar o detenerse. */
$NegativeBalances = $_REQUEST["NegativeBalances"];

$seguir = true;

if ($SkeepRows == "") {
    $seguir = false;
}


/* Verifica si $OrderedItem y $MaxRows están vacíos, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $seguir = false;
}

if ($seguir) {

    /* Ordena registros por créditos, según el método y dirección especificados en la solicitud. */
    $order = "registro.creditos";
    $orderType = "desc";

    if ($_REQUEST["sort[AmountWithdraw]"] != "") {
        $order = "registro.creditos";
        $orderType = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

    }


    /* Crea reglas para validar usuario, agregando condiciones si $UserId no está vacío. */
    $rules = [];


    if ($UserId != "") {

        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$UserId ", "op" => "eq"));

    }


    /* Agrega reglas de filtrado para usuario según PlayerId y CountrySelect. */
    if ($PlayerId != "") {

        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId ", "op" => "eq"));

    }
    if ($CountrySelect != "") {

        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect ", "op" => "eq"));

    }


    /* Valida saldos negativos y define reglas de ordenación para créditos en ascendente. */
    if ($NegativeBalances === '0') {

        array_push($rules, array("field" => "registro.creditos + registro.creditos_base", "data" => 0, "op" => "lt"));
        $order = 'registro.creditos + registro.creditos_base';
        $orderType = 'asc';
    }

    // Si el usuario esta condicionado por País

    /* Se añaden reglas basadas en condiciones de sesión del usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Añade reglas de validación basadas en la sesión de mandante si existen. */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    /* Se definen reglas de filtrado para usuarios con condiciones específicas. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE ", "op" => "eq"));

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");


    /* convierte un filtro a JSON y configura la localización en checo. */
    $json = json_encode($filtro);

    setlocale(LC_ALL, 'czech');

    $select = " usuario.usuario_id,usuario.moneda,usuario.nombre,usuario.fecha_crea,registro.creditos_base,registro.creditos ";
    $grouping = "usuario.usuario_id";

    /* selecciona y agrupa datos de créditos según el tipo de usuario. */
    if ($Type == 1) {
        $select = " usuario.moneda,SUM(registro.creditos_base) creditos_base,SUM(registro.creditos) creditos ";
        $grouping = "usuario.moneda";
        $MaxRows = 100;
    }

    $Usuario = new Usuario();


    /* obtiene y decodifica datos de usuarios personalizados en formato JSON. */
    $data = $Usuario->getUsuariosCustom($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping);

    $data = json_decode($data);

    $final = array();
    $totalAmount = 0;

    /* Recorre datos, crea un array con información de usuarios y registros financieros. */
    foreach ($data->data as $value) {
        $array = array();
        $array["UserId"] = $value->{"usuario.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};
        $array["Currency"] = $value->{"usuario.moneda"};
        $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};
        $array["AmountDeposit"] = $value->{"registro.creditos_base"};
        $array["AmountWithdraw"] = $value->{"registro.creditos"};
        if ($Type == 1) {
            $array["AmountDeposit"] = $value->{".creditos_base"};
            $array["AmountWithdraw"] = $value->{".creditos"};

        }
        array_push($final, $array);
    }


    /* define una respuesta exitosa para una operación, incluyendo posibles errores. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfully";
    $response["ModelErrors"] = [];


    $response["pos"] = $SkeepRows;

    /* asigna un valor a "total_count" según el tipo seleccionado. */
    if ($Type == 1) {
        $response["total_count"] = oldCount($final);

    } else {
        $response["total_count"] = $data->count[0]->{".count"};

    }

    /* Asignación de datos finales a la clave "data" en el array de respuesta. */
    $response["data"] = $final;
} else {
    /* gestiona respuestas tras una operación exitosa, informando del estado. */

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfully";
    $response["ModelErrors"] = [];


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}