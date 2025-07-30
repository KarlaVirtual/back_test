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
use \Backend\dto\UsucomisionResumen;
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
use Backend\dto\UsucomisionusuarioResumen;
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
use Backend\mysql\UsucomisionusuarioResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\mysql\UsuariocomisionPagadoMySqlDAO;
use Backend\dto\UsuariocomisionPagado;


//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');


/**
 *
 * @param int $UserId : Descripción: Identificador del usuario.
 * @param int $ByAllowDate : Descripción: Indicador para filtrar por fecha de autorización.
 * @param int $count : Descripción: Número máximo de filas a devolver.
 * @param string $State : Descripción: Estado de la comisión.
 * @param string $MyCommission : Descripción: Tipo de comisión.
 * @param int $start : Descripción: Número de filas a omitir en la consulta.
 * @param string $UserType : Descripción: Tipo de usuario.
 * @param int $ConcessionaireId : Descripción: Identificador del concesionario.
 * @param string $dateTo : Descripción: Fecha de fin para el reporte de comisiones.
 * @param string $dateFrom : Descripción: Fecha de inicio para el reporte de comisiones.
 * @param string $ComissionsType : Descripción: Tipo de comisiones.
 * @param int $Type : Descripción: Indicador para agrupar total o detallado.
 *
 * @Description Este recurso permite obtener los pagos de comisiones de los usuarios en el sistema, filtrando por varios criterios como fechas, tipo de usuario, concesionario, estado, tipo de comisión, etc.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Lista de errores de validación del modelo.
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos de los pagos de comisiones.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'danger';
 * $response['AlertMessage'] = 'Invalid';
 * $response['ModelErrors'] = [];
 *
 */
// Obtiene el identificador del usuario a partir de la solicitud

/* obtiene parámetros de solicitud HTTP para su uso posterior. */
$Id = $_REQUEST["UserId"];
$ByAllowDate = $_REQUEST["ByAllowDate"];
$MaxRows = $_REQUEST["count"];
$State = $_REQUEST["State"];
$MyComission = $_REQUEST["MyCommission"];

$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

/* asigna variables basadas en la solicitud del usuario y su tipo. */
$OrderedItem = "";

// filtro por tipo de usuario
$UserType = $_REQUEST["UserType"];
//filtro por usuario_id que le pagaron
$ConcessionaireId = $_REQUEST["ConcessionaireId"];
//filtro por usuairo que realizo el pago

/* obtiene parámetros de solicitud para filtrar comisiones y tipo de resultado. */
$UserId = $_REQUEST["UserId"];
//filtro por comision pagada
$ComissionsType = $_REQUEST["ComissionsType"];
//filtro grouping total o detallado
$IsTotal = $_REQUEST["Type"] ?? 0;
$seguir = true;


/* verifica si las variables están vacías y establece un flag. */
if ($MaxRows == "") {
    $seguir = false;
}

if ($SkeepRows == "") {
    $seguir = false;
}


/* establece fechas localizadas a partir de entradas de fecha en formato específico. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}

if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


/* Se inicializa un array vacío llamado $final3 en PHP para almacenamiento de datos. */
$final3 = [];


if ($seguir) {

    try {


        /* define reglas para filtrar comisiones de usuarios por fechas. */
        $UsuariocomisionPagado = new UsuariocomisionPagado();

        $rules = [];


        if ($ByAllowDate == 1) {
            if ($FromDateLocal != "") {
                array_push($rules, array("field" => "usuariocomision_pagado.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
            }
            if ($ToDateLocal != "") {
                array_push($rules, array("field" => "usuariocomision_pagado.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
            }
        } elseif ($ByAllowDate == 0) {
            /* Condición que añade reglas de fecha a un array si están definidas. */

            if ($FromDateLocal != "") {
                array_push($rules, array("field" => "usuariocomision_pagado.fecha_inicio", "data" => $FromDateLocal, "op" => "ge"));
            }
            if ($ToDateLocal != "") {
                array_push($rules, array("field" => "usuariocomision_pagado.fecha_inicio", "data" => $ToDateLocal, "op" => "le"));
            }
        }


        /* Asigna un tipo de usuario a una regla basada en un mapa predefinido. */
        if (!empty($UserType) && $UserType != "ALL") {
            $userTypeMap = [
                "AF" => "AFILIADOR",
                "CO" => "CONCESIONARIO",
                "SC" => "SUBCONCESIONARIO2",
                "PV" => "PUNTOVENTA"
            ];
            $UserType = $userTypeMap[$UserType];
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => $UserType, "op" => "eq"));
        }

        /* Agrega reglas a un array si los IDs no están vacíos. */
        if (!empty($ConcessionaireId)) {
            array_push($rules, array("field" => "usuariocomision_pagado.usuario_id", "data" => $ConcessionaireId, "op" => "eq"));
        }
        if (!empty($UserId)) {
            array_push($rules, array("field" => "usuariocomision_pagado.usucrea_id", "data" => $UserId, "op" => "eq"));
        }

        /* Agrega una regla si $ComissionsType no está vacío y no es "ALL". */
        if (!empty($ComissionsType) && $ComissionsType != "ALL") {
            array_push($rules, array("field" => "usuariocomision_pagado.tipo_comision", "data" => $ComissionsType, "op" => "eq"));
        }


        $MaxRows = 1000000;


        /* Configura select y grouping para totalizar valores según condiciones específicas. */
        $grouping = "";
        $select = "";

        if ($IsTotal == 1) {
            $MaxRows = 10000;
            $select = "SUM(usuariocomision_pagado.valor_pagado) AS total_valor_pagado,
                    usuariocomision_pagado.fecha_inicio,usuariocomision_pagado.fecha_fin";
            $grouping = "usuariocomision_pagado.fecha_inicio,usuariocomision_pagado.fecha_fin";

        } else {
            /* Selecciona datos de comisiones y perfil de usuario en condiciones específicas. */

            $select = "usuariocomision_pagado.*,usuario_perfil.perfil_id,usuario.nombre";
        }


        /* Se define un filtro y se inicializa la variable SkeepRows si está vacía. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");


        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }


        /* inicializa variables si están vacías, asignando valores predeterminados. */
        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 1000;
        }


        /* Consulta concesionarios con un filtro JSON y agrupamiento de comisiones pagadas. */
        $json = json_encode($filtro);

        //Consulta para los concesionarios y subconcesionarios
        if (trim($_SESSION["win_perfil"]) == 'CONCESIONARIO' || trim($_SESSION["win_perfil"]) == 'CONCESIONARIO2') {
            $grouping = "usuariocomision_pagado.usucomisionpagado_id";
            $transacciones = $UsuariocomisionPagado->getUsuariocomisionPagadoCustom2($select, "usuariocomision_pagado.usucomisionpagado_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping, $_SESSION["usuario"]);
        } else {
            /* obtiene transacciones usando un método de un objeto de usuario con paginación. */

            $transacciones = $UsuariocomisionPagado->getUsuariocomisionPagadoCustom($select, "usuariocomision_pagado.usucomisionpagado_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);
        }


        /* Convierte una cadena JSON en un objeto o array de PHP. */
        $transacciones = json_decode($transacciones);


        foreach ($transacciones->data as $key => $value) {

            /* Condicional que crea un arreglo con datos de totales y fechas. */
            if ($IsTotal == 1) {
                $arraybet = array();
                $arraybet["Total"] = ($value->{".total_valor_pagado"});
                $arraybet["DateFrom"] = ($value->{"usuariocomision_pagado.fecha_inicio"});
                $arraybet["DateTo"] = $value->{"usuariocomision_pagado.fecha_fin"};
            } else {
                /* Se crea un array con datos de usuario y comisión, asignando valores condicionalmente. */

                $arraybet = array();
                $arraybet["ComissionId"] = ($value->{"usuariocomision_pagado.usucomisionpagado_id"});
                $arraybet["UserId"] = ($value->{"usuariocomision_pagado.usuario_id"});
                $arraybet["DateFrom"] = ($value->{"usuariocomision_pagado.fecha_inicio"});
                $arraybet["DateTo"] = ($value->{"usuariocomision_pagado.fecha_fin"});
                $arraybet["Value"] = ($value->{"usuariocomision_pagado.valor_pagado"});
                $arraybet["State"] = ($value->{"usuariocomision_pagado.estado"});
                $arraybet["Type"] = ($value->{"usuariocomision_pagado.tipo"});
                $arraybet["CreatedLocal"] = ($value->{"usuariocomision_pagado.fecha_crea"});
                $arraybet["UserType"] = ($value->{"usuario_perfil.perfil_id"});
                $arraybet["UserName"] = ($value->{"usuario.nombre"});
                if (!empty($value->{"usuariocomision_pagado.tipo_comision"})) {
                    $arraybet["ComissionsType"] = ($value->{"usuariocomision_pagado.tipo_comision"});
                } else {
                    $arraybet["ComissionsType"] = "Pago Consolidado";
                }

            }


            /* Añade el contenido de `$arraybet` al final del array `$final3`. */
            array_push($final3, $arraybet);

        }

    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, captura de errores sin acción definida. */


    }
}


/* Código inicializa un array y establece una respuesta sin errores y con éxito. */
$data = array();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

/* asigna valores a la respuesta con posiciones, conteo total y datos finales. */
$response["pos"] = $SkeepRows;
$response["total_count"] = oldCount($transacciones->data);
$response["data"] = $final3;

