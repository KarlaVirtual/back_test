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


/**
 * @param int $UserId : Descripción: Identificador del usuario para el reporte de comisiones.
 * @param string $dateFrom : Descripción: Fecha de inicio para el reporte de comisiones.
 * @param string $dateTo : Descripción: Fecha de fin para el reporte de comisiones.
 * @param string $ComissionsPayment : Descripción: Tipo de pago de comisiones.
 * @param string $ComissionsType : Descripción: Tipo de comisión.
 * @param string $UserType : Descripción: Tipo de usuario (AF, CO, SC, PV).
 * @param int $ConcessionaireId : Descripción: Identificador del concesionario.
 * @param int $count : Descripción: Número máximo de filas a devolver.
 * @param int $start : Descripción: Número de filas a omitir en la consulta.
 *
 * @Description Obtener el resumen de comisiones de usuarios a nivel individual
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en el procesamiento.
 * - *AlertType* (string): Tipo de alerta ('success' o 'error').
 * - *AlertMessage* (string): Mensaje de alerta.
 * - *ModelErrors* (array): Errores del modelo, si los hay.
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos del resumen de comisiones.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'error';
 * $response['AlertMessage'] = 'Mensaje de error';
 * $response['ModelErrors'] = [];
 *
 * @throws Exception Rango de Fechas Obligatorio
 *
 */


/* lee y decodifica datos JSON, obteniendo fechas y un ID de usuario. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$Id = $_REQUEST["UserId"];
$ToDateLocal = $params->ToCreatedDateLocal;
$FromDateLocal = $params->FromCreatedDateLocal;

/* asigna valores a variables a partir de solicitudes HTTP. */
$OrderedItem = "";
$Estado = 'A';

$ComissionsPayment = $_REQUEST["ComissionsPayment"];
//tipo de comision a consultar
$ComissionsType = $_REQUEST["ComissionsType"];
// filtro por tipo de usuario

/* establece variables basadas en solicitudes de usuario y parámetros filtrados. */
$UserType = ($_REQUEST["UserType"] == 'ALL') ? '' : $_REQUEST["UserType"];
//filtro por usuario concesionario o subconcensionario
$ConcessionaireId = $_REQUEST["ConcessionaireId"];

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* verifica si $MaxRows está vacío y cambia la variable $seguir. */
$seguir = true;


if ($MaxRows == "") {
    $seguir = false;
}


/* verifica si hay filas a omitir y ajusta una fecha con zona horaria. */
if ($SkeepRows == "") {
    $seguir = false;
}

if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
} else {
    /* Lanza una excepción si no se proporciona un rango de fechas necesario. */

    throw new Exception('Rango de Fechas Obligatorio', 300025);
}


/* Valida una fecha de inicio y la formatea, lanzando excepción si está vacía. */
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
} else {
    throw new Exception('Rango de Fechas Obligatorio', 300025);
}

$permitIndividualComission = ['DEPOSITOPV', 'RETIROSPV', 'DEPOSITOPVXTR'];


/* Se inicializan arreglos para almacenar totales de comisiones y transacciones. */
$array = [];
$array["ComissionTotal"] = 0;
$array2 = [];
$array2["ComissionTotal"] = 0;
$transaccionesTotales = [];
$final = [];

/* Se inicializa un arreglo vacío y un clasificador individual en el código. */
$final2 = [];
$ClasificadorIndividual = new Clasificador('', 'INDIVIDUALGGR');


if ($seguir) {

    try {


        /* Se crea un objeto y se establece una regla de fecha condicionalmente. */
        $UsucomisionusuarioResumen = new UsucomisionusuarioResumen();

        $rules = [];

        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usucomisionusuario_resumen.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
        }

        /* añade reglas de filtrado según condiciones específicas para una consulta. */
        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "usucomisionusuario_resumen.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
        }

        if ($Id != "") {
            array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => $Id, "op" => "eq"));
        }


        /* Agrega reglas para filtrar estado y pago de comisiones en un array. */
        array_push($rules, array("field" => "usucomisionusuario_resumen.estado", "data" => 'A', "op" => "eq"));


        if ($ComissionsPayment != "") {
            array_push($rules, array("field" => "usuario.pago_comisiones", "data" => "$ComissionsPayment", "op" => "eq"));
        }


        /* Agrega reglas basadas en tipos de comisiones y usuarios si no están vacías. */
        if (!empty($ComissionsType)) {
            array_push($rules, array("field" => "clasificador.abreviado", "data" => $ComissionsType, "op" => "eq"));
        }

        if (!empty($UserType)) {
            $userTypeMap = [
                "AF" => "AFILIADOR",
                "CO" => "CONCESIONARIO",
                "SC" => "SUBCONCESIONARIO2",
                "PV" => "PUNTOVENTA"
            ];
            $UserType = $userTypeMap[$UserType];
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => $UserType, "op" => "eq"));
        }

        //filtro por usuario_id que le pagaron

        /* Condiciona reglas de consulta basadas en el perfil del usuario concesionario. */
        $ConcessionaireId = $_REQUEST["ConcessionaireId"];

        //Condicionar la consulta para tipos de win_perfil
        if (trim($_SESSION["win_perfil"]) == 'CONCESIONARIO') {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        /* Agrega reglas de filtro según el perfil y el ID del concesionario. */
        if (trim($_SESSION["win_perfil"]) == 'CONCESIONARIO2') {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        if ($ConcessionaireId != null && $ConcessionaireId != "") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $ConcessionaireId, "op" => "eq"));
        }


        // Si el usuario esta condicionado por el mandante y no es de Global

        /* Condiciona la adición de reglas según el valor de la sesión "Global". */
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        /* Se define una consulta SQL seleccionando columnas de varias tablas relacionadas. */
        $MaxRows = 1000000;

        $grouping = "";
        $select = "";

        $select = "usucomisionusuario_resumen.comision,usucomisionusuario_resumen.estado, usucomisionusuario_resumen.usuario_id, usucomisionusuario_resumen.usucomusuresumen_id, clasificador.clasificador_id,clasificador.abreviado, usuario.pago_comisiones ,usuario.arrastra_negativo, usuario.nombre,usuario_perfil.perfil_id";


        /* Configura un filtro y maneja valores predeterminados para paginación y ordenamiento. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }


        /* Establece un valor por defecto y obtiene transacciones usando parámetros específicos. */
        if ($MaxRows == "") {
            $MaxRows = 1000;
        }

        $json = json_encode($filtro);

        $transacciones = $UsucomisionusuarioResumen->getUsucomisionusuarioResumenCustom($select, "usucomisionusuario_resumen.usucomusuresumen_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


        /* Se decodifica un JSON a un objeto y se asigna a otra variable. */
        $transacciones = json_decode($transacciones);


        $transacciones2 = $transacciones;


        foreach ($transacciones->data as $key2 => $value2) {

            /* Verifica y inicializa un arreglo de usuario antes de crear su configuración. */
            if (!isset($final[$value2->{"usucomisionusuario_resumen.usuario_id"}])) {
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}] = array();
            }

            try {
                $UsuariConfiguracion = new UsuarioConfiguracion($value2->{"usucomisionusuario_resumen.usuario_id"}, 'A', $ClasificadorIndividual->clasificadorId);
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP para evitar errores inesperados durante la ejecución. */


            }


            /* Se asignan datos de configuración y comisiones a un arreglo según usuario. */
            if (isset($UsuariConfiguracion)) {

                ///Calculo Invidual Para comisiones
                $array = array();

                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["PagoComisiones"] = $value2->{"usuario.pago_comisiones"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["UserId"] = $value2->{"usucomisionusuario_resumen.usuario_id"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["ArrastraNegativo"] = $value2->{"usuario.arrastra_negativo"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["INDIVIDUALGGR"] = $UsuariConfiguracion->getEstado();
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["Tipos"][$value2->{"clasificador.abreviado"}] = $value2->{"usucomisionusuario_resumen.comision"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["UserName"] = $value2->{"usuario.nombre"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["UserType"] = $value2->{"usuario_perfil.perfil_id"};

            } else {
                /* Calcula comisiones individuales y almacena información de usuarios en un array. */


                ///Calculo Invidual Para comisiones
                $array = array();
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["PagoComisiones"] = $value2->{"usuario.pago_comisiones"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["UserId"] = $value2->{"usucomisionusuario_resumen.usuario_id"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["ArrastraNegativo"] = $value2->{"usuario.arrastra_negativo"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["INDIVIDUALGGR"] = 'I';
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["Tipos"][$value2->{"clasificador.abreviado"}] = $value2->{"usucomisionusuario_resumen.comision"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["Tipos"][$value2->{"clasificador.abreviado"}] = $value2->{"usucomisionusuario_resumen.comision"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["UserName"] = $value2->{"usuario.nombre"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["UserType"] = $value2->{"usuario_perfil.perfil_id"};

            }
        }

    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, captura de errores sin procesar. */


    }
}

/* Se inicializa un arreglo vacío para almacenar transacciones totales. */
$transaccionesTotales = array();
foreach ($final as $userId => $item) {

    /* crea un array final con detalles de usuario y comisiones. */
    $finalItem = array();
    $finalItem['UserId'] = $userId;
    if ($item["INDIVIDUALGGR"] == 'I') {
        $finalItem['State'] = 'A';
        $finalItem['ComissionTotal'] = 0;
        $finalItem['PagoComisiones'] = $item['PagoComisiones'];
        $finalItem['UserName'] = $item['UserName'];
        $finalItem['UserType'] = $item['UserType'];

        foreach ($item["Tipos"] as $tipo => $valor) {

            $finalItem['ComissionTotal'] += $valor;
        }
        if ($item["ArrastraNegativo"] == '0') {
            if ($finalItem['ComissionTotal'] < 0) {
                $finalItem['ComissionTotal'] = 0;
            }
        }
    } else {

        /* Se calcula el total de comisiones y se ajustan valores negativos. */
        $finalItem['State'] = 'A';
        $finalItem['ComissionTotal'] = 0;
        $finalItem['PagoComisiones'] = $item['PagoComisiones'];
        $finalItem['UserName'] = $item['UserName'];
        $finalItem['UserType'] = $item['UserType'];
        foreach ($item["Tipos"] as $tipo => $valor) {
            if (empty($ComissionsType) || $ComissionsType == 'ALL') {
                if (in_array($tipo, $permitIndividualComission)) {
                    $valor = 0;
                }
            }
            if ($item["ArrastraNegativo"] == '0') {
                if ($valor < 0) {
                    $valor = 0;
                }
            }
            $finalItem['ComissionTotal'] += $valor;

        }


    }

    /* Añade el elemento $finalItem al final del array $transaccionesTotales en PHP. */
    array_push($transaccionesTotales, $finalItem);
}


/* recopila información de transacciones y la almacena en un arreglo. */
$final3 = [];

foreach ($transaccionesTotales as $key5 => $value5) {

    $data['UserId'] = $value5["UserId"];
    $data['ComissionTotal'] = $value5["ComissionTotal"];
    $data['State'] = $value5["State"];
    $data['PagoComisiones'] = $value5["PagoComisiones"];
    $data['UserName'] = $value5["UserName"];
    $data['UserType'] = $value5["UserType"];
    $data["dateTo"] = $ToDateLocal;
    $data["dateFrom"] = $FromDateLocal;
    array_push($final3, $data);
    $data = array();
}


/* establece una respuesta exitosa y contiene información sobre datos procesados. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["pos"] = $SkeepRows;
$response["total_count"] = oldCount($final3);

/* Asigna el valor de $final3 a la clave "data" del arreglo $response. */
$response["data"] = $final3;

