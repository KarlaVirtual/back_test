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
 * Obtener las KPI de un usuario
 *
 * Este script calcula y devuelve las métricas clave de rendimiento (KPI) de un usuario, 
 * incluyendo apuestas deportivas, ganancias, depósitos, retiros, ajustes y bonos.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $params->ToDateLocal Fecha de inicio del rango de consulta.
 * @param string $params->FromDateLocal Fecha de fin del rango de consulta.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento por el cual ordenar los resultados.
 * @param int $params->SkeepRows Número de filas a omitir en la consulta.
 * 
 * 
 * @return array $response Arreglo con la estructura:
 *  - HasError: booleano que indica si hubo errores.
 *  - AlertType: tipo de alerta (success, error, etc.).
 *  - AlertMessage: mensaje de alerta.
 *  - Data: arreglo con las métricas calculadas, incluyendo:
 *    - LastSportBetTimeLocal: Última hora de apuesta deportiva.
 *    - TotalSportBets: Total de apuestas deportivas.
 *    - TotalUnsettledBets: Total de apuestas no resueltas.
 *    - TotalSportStakes: Total apostado en deportes.
 *    - TotalUnsettledStakes: Total apostado no resuelto.
 *    - TotalSportWinnings: Total de ganancias deportivas.
 *    - TotalCasinoWinnings: Total de ganancias en casino.
 *    - TotalCasinoStakes: Total apostado en casino.
 *    - SportProfitness: Rentabilidad deportiva.
 *    - TotalDeposit: Total de depósitos.
 *    - TotalWithdrawal: Total de retiros.
 *    - TotalPendingWithdrawal: Total de retiros pendientes.
 *    - CasinoProfitness: Rentabilidad del casino.
 *    - AdjustmentE: Ajustes de entrada.
 *    - AdjustmentS: Ajustes de salida.
 *    - TotalBonus: Total de bonos.
 *    - TotalBonusFreebet: Total de bonos Freebet.
 *    - TotalBonusDelete: Total de bonos eliminados.
 *    - TotalBonusWin: Total de bonos ganados.
 */

/* obtiene variables de entrada de un arreglo y una fecha. */
$id = $_GET["id"];

$ToDateLocal = $params->ToDateLocal;
$FromDateLocal = $params->FromDateLocal;
if (true) {


    /* Inicia un objeto y asigna parámetros relacionados con tickets de forma estructurada. */
    $ItTicketEnc = new ItTicketEnc();


    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;


    /* asigna valores predeterminados a variables si están vacías. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un valor predeterminado y define reglas de filtrado. */
    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }

    $rules = [];
//array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => "$id", "op" => "eq"));
//array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");


    /* Codifica un filtro, obtiene usuarios, y decodifica los datos JSON resultantes. */
    $json = json_encode($filtro);

    $Usuario = new Usuario();

    $data = $Usuario->getUsuariosKPICustom("data.tipo, SUM(data.valor) valor", "data.fecha", "desc", 0, 100, $json, true, "data.tipo", $id);

    $dataT = json_decode($data);


    /* Variables para rastrear totales de apuestas, fondos y ganancias en deportes y casino. */
    $TotalSportBets = 0;
    $TotalUnsettledBets = 0;
    $TotalSportStakes = 0;
    $TotalUnsettledStakes = 0;
    $TotalSportWinnings = 0;
    $TotalCasinoWinnings = 0;

    /* Variables inicializan montos relacionados con apuestas, depósitos y ganancias en un casino. */
    $TotalCasinoStakes = 0;
    $SportProfitness = 0;
    $TotalDeposit = 0;
    $TotalWithdrawal = 0;
    $TotalPendingWithdrawal = 0;
    $CasinoProfitness = 0;

    /* Variables inicializan ajustes y totales para bonificaciones en el código presentado. */
    $AdjustmentE = 0;
    $AdjustmentS = 0;
    $TotalBonus = 0;

    $TotalBonusFreebet = 0;

    $TotalBonusEliminado = 0;


    /* Inicializa la variable $TotalBonusGanado con un valor de cero para acumular bonificaciones. */
    $TotalBonusGanado = 0;


    foreach ($dataT->data as $data) {

        switch ($data->{"data.tipo"}) {

            case "Apuesta Deportiva":
                /* Asigna el valor de la apuesta deportiva a la variable TotalSportStakes. */


                $TotalSportStakes = $data->{".valor"};

                break;

            case "Premio Apuesta Deportiva":
                /* Asignación del valor de las ganancias deportivas a la variable $TotalSportWinnings. */


                $TotalSportWinnings = $data->{".valor"};

                break;

            case "Deposito":
                /* Código que asigna el valor de un depósito a la variable $TotalDeposit. */


                $TotalDeposit = $data->{".valor"};

                break;

            case "Ajuste Entrada":
                /* Asignación del valor de ajuste a la variable $AdjustmentE en un caso específico. */


                $AdjustmentE = $data->{".valor"};

                break;

            case "Ajuste Salida":
                /* asigna un valor a la variable $AdjustmentS en un caso específico. */


                $AdjustmentS = $data->{".valor"};

                break;

            case "Retiro Pendiente":
                /* Asignación del valor de retiro pendiente a la variable TotalPendingWithdrawal. */


                $TotalPendingWithdrawal = $data->{".valor"};

                break;

            case "Retiro Pagado":
                /* asigna el valor de "Retiro Pagado" a la variable $TotalWithdrawal. */


                $TotalWithdrawal = $data->{".valor"};

                break;

            case "Apuesta Casino":
                /* Asigna el valor de apuesta al total de apuestas en el casino. */


                $TotalCasinoStakes = $data->{".valor"};

                break;

            case "Premio Casino":
                /* Asignación del total de ganancias del casino desde los datos obtenidos. */


                $TotalCasinoWinnings = $data->{".valor"};

                break;


            case "Bono":
                /* Asigna el valor de bono desde los datos a la variable TotalBonus. */


                $TotalBonus = $data->{".valor"};

                break;


            case "Bono Freebet":
                /* asigna el valor del bono a una variable en un caso específico. */


                $TotalBonusFreebet = $data->{".valor"};

                break;


            case "Bono Freebet Eliminado":
                /* Asignación del valor de un bono eliminado a la variable TotalBonusEliminado. */


                $TotalBonusEliminado = $data->{".valor"};

                break;


            case "Bono Free Ganado":
                /* asigna un valor a la variable según el caso "Bono Free Ganado". */


                $TotalBonusGanado = $data->{".valor"};

                break;


        }

    }

    /* Resta el valor de "TotalBonusFreebet" de "TotalSportBets" y crea una respuesta. */
    $TotalSportBets = $TotalSportBets - $TotalBonusFreebet;
    $TotalSportBets = $TotalSportBets - $TotalBonusFreebet;

    /* Inicializa un arreglo vacío para almacenar errores del modelo en una respuesta. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "LastSportBetTimeLocal" => "",
        "TotalSportBets" => ($TotalSportBets),
        "TotalUnsettledBets" => ($TotalUnsettledBets),
        "TotalSportStakes" => round(($TotalSportStakes), 2),
        "TotalUnsettledStakes" => ($TotalUnsettledStakes),
        "TotalSportWinnings" => round(($TotalSportWinnings), 2),
        "TotalCasinoWinnings" => round($TotalCasinoWinnings, 2),
        "TotalCasinoStakes" => round($TotalCasinoStakes, 2),
        "SportProfitness" => $TotalSportWinnings == 0 ? 0 : ($TotalSportStakes / $TotalSportWinnings),
        "TotalDeposit" => round(($TotalDeposit), 2),
        "TotalWithdrawal" => round(($TotalWithdrawal), 2),
        "TotalPendingWithdrawal" => round(($TotalPendingWithdrawal), 2),
        "CasinoProfitness" => $TotalCasinoWinnings == 0 ? 0 : ($TotalCasinoStakes / $TotalCasinoWinnings),
        "AdjustmentE" => round(($AdjustmentE), 2),
        "AdjustmentS" => round(($AdjustmentS), 2),
        "TotalBonus" => round(($TotalBonus), 2),
        "TotalBonusFreebet" => round(($TotalBonusFreebet), 2),
        "TotalBonusDelete" => round(($TotalBonusEliminado), 2),
        "TotalBonusWin" => round(($TotalBonusGanado), 2)


    );

    /* Corrige las ganancias a 1 si son 0 y hay apuestas realizadas. */
    if ($TotalSportWinnings == 0 && $TotalSportBets > 0) {
        $TotalSportWinnings = 1;
    }
    if ($TotalCasinoWinnings == 0 && $TotalCasinoStakes > 0) {
        $TotalCasinoWinnings = 1;
    }

    $response = [array(
        "LastSportBetTimeLocal" => "",
        "TotalSportBets" => ($TotalSportBets),
        "TotalUnsettledBets" => ($TotalUnsettledBets),
        "TotalSportStakes" => round(($TotalSportStakes), 2),
        "TotalUnsettledStakes" => ($TotalUnsettledStakes),
        "TotalSportWinnings" => round(($TotalSportWinnings), 2),
        "TotalCasinoWinnings" => round($TotalCasinoWinnings, 2),
        "TotalCasinoStakes" => round($TotalCasinoStakes, 2),
        "SportProfitness" => $TotalSportWinnings == 0 ? 0 : ($TotalSportStakes / $TotalSportWinnings),
        "TotalDeposit" => round(($TotalDeposit), 2),
        "TotalWithdrawal" => round(($TotalWithdrawal), 2),
        "TotalPendingWithdrawal" => round(($TotalPendingWithdrawal), 2),
        "CasinoProfitness" => $TotalCasinoWinnings == 0 ? 0 : ($TotalCasinoStakes / $TotalCasinoWinnings),
        "AdjustmentE" => round(($AdjustmentE), 2),
        "AdjustmentS" => round(($AdjustmentS), 2),
        "TotalBonus" => round(($TotalBonus + $TotalBonusFreebet - $TotalBonusEliminado), 2),
        "TotalBonusFreebet" => round(($TotalBonusFreebet), 2),
        "TotalBonusDelete" => round(($TotalBonusEliminado), 2),
        "TotalBonusWin" => round(($TotalBonusGanado), 2)

    )];
} else {


    /* Inicializa una respuesta sin errores y lista de mensajes para alertas. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "LastSportBetTimeLocal" => "",
        "TotalSportBets" => (0),
        "TotalUnsettledBets" => (0),
        "TotalSportStakes" => round((0), 2),
        "TotalUnsettledStakes" => (0),
        "TotalSportWinnings" => round((0), 2),
        "TotalCasinoWinnings" => round(0, 2),
        "TotalCasinoStakes" => round(0, 2),
        "SportProfitness" => 0,
        "TotalDeposit" => round((0), 2),
        "TotalWithdrawal" => round((0), 2),
        "TotalPendingWithdrawal" => round((0), 2),
        "CasinoProfitness" => 0,
        "AdjustmentE" => round((0), 2),
        "AdjustmentS" => round((0), 2),
        "TotalBonus" => round((0), 2),
        "TotalBonusFreebet" => round((0), 2),
        "TotalBonusDelete" => round((0), 2),
        "TotalBonusWin" => round((0), 2)


    );
}