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
use Backend\dto\UsuariocomisionPagado;
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
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\mysql\UsucomisionusuarioResumenMySqlDAO;
use Backend\mysql\UsuariocomisionPagadoMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * @param array $Ids Descripción: Identificadores de los usuarios para el reporte de comisiones de pago.
 * @param string $dateFrom Descripción: Fecha de inicio para el reporte de comisiones de pago.
 * @param string $dateTo Descripción: Fecha de fin para el reporte de comisiones de pago.
 * @param int $OrderedItem Descripción: Ítem ordenado.
 * @param string $ComissionsPayment Descripción: Tipo de pago de comisiones.
 * @param string $ComissionsType Descripción: Tipo de comisión.
 *
 * @Description Obtener el resumen de comisiones de pago para un conjunto de usuarios en un rango de fechas específico.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en el procesamiento.
 * - *AlertType* (string): Tipo de alerta ('success' o 'error').
 * - *AlertMessage* (string): Mensaje de alerta.
 * - *ModelErrors* (array): Errores del modelo, si los hay.
 * - *Data* (array): Datos del resumen de comisiones de pago.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'error';
 * $response['AlertMessage'] = 'Mensaje de error';
 * $response['ModelErrors'] = [];
 *
 * @throws Exception Rango de Fechas Obligatorio
 * @throws Exception Error: el usuario tiene comisión compuesta
 * @throws Exception Error: el usuario tiene comisión individual
 * @throws Exception Usuario no puede pagar comisiones
 */

/* recibe datos JSON y extrae los valores de 'Ids' y 'dateTo'. */
$params = file_get_contents('php://input');
$params = json_decode($params);


$Ids = $params->Ids;

$ToDateLocal = $params->dateTo;

/* Asignación de variables desde parámetros para establecer datos sobre comisiones y estado. */
$FromDateLocal = $params->dateFrom;
$OrderedItem = $params->OrderedItem;
$Estado = 'A';

$ComissionsPayment = $params->ComissionsPayment;
$ComissionsType = $params->ComissionsType;


/* inicializa variables y verifica si $MaxRows está vacío para continuar. */
$start = 0;
$MaxRows = 1000000;
$SkeepRows = 0;

$seguir = true;


if ($MaxRows == "") {
    $seguir = false;
}


/* establece fechas límites locales, lanzando una excepción si están vacías. */
if ($ToDateLocal != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));
} else {
    throw new Exception('Rango de Fechas Obligatorio', 300025);
}

if ($FromDateLocal != "") {

    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
} else {
    /* lanza una excepción si el rango de fechas no se proporciona. */

    throw new Exception('Rango de Fechas Obligatorio', 300025);
}


/* Se inicializan arreglos para almacenar totales de comisiones y transacciones. */
$array = [];
$array["ComissionTotal"] = 0;
$array2 = [];
$array2["ComissionTotal"] = 0;
$transaccionesTotales = [];
$final = [];

/* Se crea un arreglo vacío llamado $final2 para almacenar valores futuros. */
$final2 = [];

if ($seguir) {

    /* Se define un arreglo que contiene tipos de comisiones permitidas. */
    $permitIndividualComission = ['DEPOSITOPV', 'RETIROSPV', 'DEPOSITOPVXTR'];


    foreach ($Ids as $clave => $valor) {


        /* Se crea un resumen de comisión y configuración de usuario, manejando excepciones. */
        $UsucomisionusuarioResumen = new UsucomisionusuarioResumen();
        try {
            $ClasificadorIndividual = new Clasificador('', 'INDIVIDUALGGR');
            $UsuariConfiguracion = new UsuarioConfiguracion($Ids[$clave], 'A', $ClasificadorIndividual->clasificadorId);
        } catch (Exception $e) {

        }

        /* valida tipos de comisiones y lanza excepciones según configuraciones de usuario. */
        $rules = [];
        if (!empty($ComissionsType) && $ComissionsType != 'ALL') {

            if (empty($UsuariConfiguracion) && !in_array($ComissionsType, $permitIndividualComission)) {
                throw new Exception('Error: el usuario tiene comisión compuesta', 300024);
            }


            if (!empty($UsuariConfiguracion) && !in_array($ComissionsType, $permitIndividualComission)) {
                throw new Exception('Error: el usuario tiene comisión individual', 300026);
            }
        }


        /* Valida el pago de comisiones y agrega reglas de filtrado por fecha. */
        $Usuario = new Usuario($Ids[$clave]);

        if ($ComissionsPayment != $Usuario->pagoComisiones && $ComissionsPayment != '' && $ComissionsPayment != null) {
            throw new Exception('Usuario no puede pagar comisiones', 300024);
        }

        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usucomisionusuario_resumen.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
        }

        /* Agrega reglas basadas en condiciones de fecha y ID de usuario a un arreglo. */
        if ($ToDateLocal != "") {

            array_push($rules, array("field" => "usucomisionusuario_resumen.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
        }

        if ($Ids[$clave] != "") {
            array_push($rules, array("field" => "usucomisionusuario_resumen.usuario_id", "data" => $Ids[$clave], "op" => "eq"));
        }


        /* agrega condiciones a un array según el estado y perfil del usuario. */
        if ($Estado != "") {
            array_push($rules, array("field" => "usucomisionusuario_resumen.estado", "data" => $Estado, "op" => "eq"));
        }

        //Condicionar la consulta para tipos de win_perfil
        if (trim($_SESSION["win_perfil"]) == 'CONCESIONARIO') {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        /* Agrega reglas basadas en perfil de sesión y tipo de comisión. */
        if (trim($_SESSION["win_perfil"]) == 'CONCESIONARIO2') {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        if (!empty($ComissionsType)) {
            array_push($rules, array("field" => "clasificador.abreviado", "data" => $ComissionsType, "op" => "eq"));
        } else {
            /* Agrega reglas a un array si la configuración del usuario no está vacía. */


            if (!empty($UsuariConfiguracion)) {
                array_push($rules, array("field" => "clasificador.abreviado", "data" => implode(',', array_map(function ($item) {
                    return "'" . $item . "'";
                }, $permitIndividualComission)), "op" => "ni"));

            }

        }


        /* Configuración de consulta SQL para obtener datos de comisiones de usuarios con filtros. */
        $MaxRows = 1000000;
        $grouping = "";
        $select = "";

        $select = "usucomisionusuario_resumen.comision,usucomisionusuario_resumen.estado, usucomisionusuario_resumen.usuario_id, usucomisionusuario_resumen.usucomusuresumen_id, clasificador.clasificador_id,clasificador.abreviado, usuario.pago_comisiones ,usuario.arrastra_negativo, usuario.nombre,usuario_perfil.perfil_id";

        //$grouping = "usucomisionusuario_resumen.usuario_id";

        $filtro = array("rules" => $rules, "groupOp" => "AND");


        /* asigna valores predeterminados a variables si están vacías. */
        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }


        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }


        /* Establece un límite de filas y obtiene un resumen de transacciones en JSON. */
        if ($MaxRows == "") {
            $MaxRows = 1000;
        }

        $json = json_encode($filtro);

        $transacciones = $UsucomisionusuarioResumen->getUsucomisionusuarioResumenCustom($select, "usucomisionusuario_resumen.usucomusuresumen_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);

        /* Convierte una cadena JSON en un objeto o array de PHP. */
        $transacciones = json_decode($transacciones);

        foreach ($transacciones->data as $key2 => $value2) {

            /* Verifica la existencia de un usuario y crea su configuración si no existe. */
            if (!isset($final[$value2->{"usucomisionusuario_resumen.usuario_id"}])) {
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}] = array();
            }

            try {
                $UsuariConfiguracion = new UsuarioConfiguracion($value2->{"usucomisionusuario_resumen.usuario_id"}, 'A', $ClasificadorIndividual->clasificadorId);
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP; captura errores sin procesarlos. */


            }

            /* Calcula comisiones individuales y organiza datos del usuario en un array. */
            if (isset($UsuariConfiguracion)) {
                ///Calculo Invidual Para comisiones
                $array = array();

                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["PagoComisiones"] = $value2->{"usuario.pago_comisiones"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["UserId"] = $value2->{"usucomisionusuario_resumen.usuario_id"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["ArrastraNegativo"] = $value2->{"usuario.arrastra_negativo"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["INDIVIDUALGGR"] = $UsuariConfiguracion->getEstado();
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["Tipos"][$value2->{"usucomisionusuario_resumen.usucomusuresumen_id"}] = $value2->{"usucomisionusuario_resumen.comision"};
            } else {
                /* Calcula comisiones individuales y organiza datos en un arreglo final. */

                ///Calculo Invidual Para comisiones
                $array = array();
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["PagoComisiones"] = $value2->{"usuario.pago_comisiones"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["UserId"] = $value2->{"usucomisionusuario_resumen.usuario_id"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["ArrastraNegativo"] = $value2->{"usuario.arrastra_negativo"};
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["INDIVIDUALGGR"] = 'I';
                $final[$value2->{"usucomisionusuario_resumen.usuario_id"}]["Tipos"][$value2->{"usucomisionusuario_resumen.usucomusuresumen_id"}] = $value2->{"usucomisionusuario_resumen.comision"};
            }
        }

    }


    foreach ($final as $userId => $item) {

        /* suma comisiones y ajusta el estado según condiciones específicas del item. */
        $finalItem = array();
        $finalItem['UserId'] = $userId;
        if ($item["INDIVIDUALGGR"] == 'I') {
            $finalItem['State'] = 'A';
            $finalItem['ComissionTotal'] = 0;
            $finalItem['PagoComisiones'] = $item['PagoComisiones'];
            foreach ($item["Tipos"] as $tipo => $valor) {

                $finalItem['ComissionTotal'] += $valor;
            }
            if ($item["ArrastraNegativo"] == '0') {
                if ($finalItem['ComissionTotal'] < 0) {
                    $finalItem['ComissionTotal'] = 0;
                }
            }
        } else {
            /* asigna valores a un array y calcula comisiones basadas en condiciones. */

            $finalItem['State'] = 'A';
            $finalItem['ComissionTotal'] = 0;
            $finalItem['PagoComisiones'] = $item['PagoComisiones'];
            foreach ($item["Tipos"] as $tipo => $valor) {
                if ($item["ArrastraNegativo"] == '0') {
                    if ($valor < 0) {
                        $valor = 0;
                    }
                }
                $finalItem['ComissionTotal'] += $valor;

            }


        }

        /* Agrega el elemento $finalItem al final del array $transaccionesTotales. */
        array_push($transaccionesTotales, $finalItem);
    }


    /* recopila datos de transacciones en un arreglo para su posterior uso. */
    $result3 = [];

    foreach ($transaccionesTotales as $key5 => $value5) {

        $data['UserId'] = $value5["UserId"];
        $data['ComissionTotal'] = $value5["ComissionTotal"];
        $data['State'] = $value5["State"];
        $data['PagoComisiones'] = $value5["PagoComisiones"];

        $data["dateTo"] = $ToDateLocal;
        $data["dateFrom"] = $FromDateLocal;
        array_push($result3, $data);
        $data = array();
    }


    foreach ($result3 as $key3 => $value3) {


        /* Actualiza saldo del usuario y obtiene información de la transacción en la base de datos. */
        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $result = $UsuarioMySqlDAO->updateBalanceCreditosAfiliacion($Usuario->usuarioId, -($value3['ComissionTotal']), false);
        $Transaction = $UsuarioMySqlDAO->getTransaction();


        $UsuariocomisionPagado = new UsuariocomisionPagado();


        /* asigna valores a propiedades de un objeto relacionado con comisiones pagadas. */
        $UsuariocomisionPagado->usuarioId = $value3["UserId"];
        $UsuariocomisionPagado->fechaInicio = $FromDateLocal;
        $UsuariocomisionPagado->fechaFin = $ToDateLocal;
        $UsuariocomisionPagado->valorPagado = $value3["ComissionTotal"];
        $UsuariocomisionPagado->estado = 'I';
        $UsuariocomisionPagado->tipo = $ComissionsPayment;

        /* Código que asigna propiedades a un objeto y crea una instancia de clase DAO. */
        $UsuariocomisionPagado->usucreaId = $_SESSION["usuario"];
        $UsuariocomisionPagado->usumodifId = 0;
        $UsuariocomisionPagado->tipoComision = $ComissionsType;
        $UsuariocomisionPagado->mandante = '0';

        $UsuariocomisionPagadoMySqlDAO = new UsuariocomisionPagadoMySqlDAO($Transaction);

        /* Insertar un registro y actualizar estados en un ciclo para comisiones de usuarios. */
        $id = $UsuariocomisionPagadoMySqlDAO->insert($UsuariocomisionPagado);


        foreach ($final[$value3["UserId"]]['Tipos'] as $key6 => $value6) {


            $UsucomisionusuarioResumen = new UsucomisionusuarioResumen($key6);
            $UsucomisionusuarioResumen->estado = 'I';
            $UsucomisionusuarioResumen->usucomisionpagadoid = $id;
            $UsucomisionusuarioResumenMySqlDAO = new UsucomisionusuarioResumenMySqlDAO($Transaction);
            $UsucomisionusuarioResumenMySqlDAO->update($UsucomisionusuarioResumen);

        }


        /* finaliza una transacción en una base de datos, guardando los cambios realizados. */
        $Transaction->commit();


    }


}


/* inicializa una respuesta sin errores y con mensaje de éxito. */
$respuestafinal = "";

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = $respuestafinal;
$response["ModelErrors"] = [];

/* Se inicializa un arreglo vacío dentro de la variable $response["Data"]. */
$response["Data"] = [];


