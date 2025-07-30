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
 * Accounting/GetInitiaMmoney
 *
 * Obtener el dinero inicial
 *
 * @param no
 *
 * @return no
 * {"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * Accounting/GetInitialMoney
 *
 * Obtener el dinero inicial
 *
 * @param object $params
 * @param int $CloseBoxId ID de la caja de cierre
 *
 * @return array
 *     "HasError": boolean Estado de la solicitud,
 *     "AlertType": string Tipo de alerta,
 *     "AlertMessage": string Mensaje de alerta,
 *     "ModelErrors": array Errores de modelo,
 *     "Data": float Dinero inicial,
 *     "pos": int Posición de inicio,
 *     "total_count": int Conteo total,
 *     "data": float Dinero inicial
 *
 * @throws Exception Si ocurre un error al obtener el dinero inicial
 */


/* Se inicializa un objeto de usuario y variables para la gestión de caja. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$dineroInicial = 0;

$CloseBoxId = $params->CloseBoxId;
$fechaEspecifica = '';

/* asigna datos de cierre de caja a variables basadas en un ID. */
$BetShopId = 0;
if ($CloseBoxId != "") {
    $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
    $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

    $BetShopId = $UsuarioCierrecaja->getUsuarioId();

    $dineroInicial = $UsuarioCierrecaja->getDineroInicial();
}

if ($BetShopId == 0) {


    if ($_SESSION["win_perfil2"] == "CAJERO") {

        /* Crea reglas de filtrado para obtener ingresos según usuario, fecha y tipo. */
        $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");

        $rules = [];
        array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
        array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


        /* Se crea un filtro en JSON y se obtiene datos de ingresos personalizados. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Ingreso = new Ingreso();

        $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", 0, 1, $json, true);


        /* decodifica JSON y extrae el valor de ingreso en un bucle. */
        $data = json_decode($data);

        foreach ($data->data as $key => $value) {
            $dineroInicial = $value->{"ingreso.valor"};
        }


    } else {


        /* Se crea un nuevo objeto Usuario utilizando datos de UsuarioMandante existente. */
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

        if ($Usuario->fechaCierrecaja == "") {


            /* Código que define reglas para filtrar ingresos según usuario, fecha y tipo. */
            $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");

            $rules = [];
            array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
            array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


            /* Se crea un filtro y se obtiene datos de ingresos personalizados en formato JSON. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Ingreso = new Ingreso();

            $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", 0, 1, $json, true);


            /* Decodifica un JSON y extrae valores de ingresos en un bucle. */
            $data = json_decode($data);

            foreach ($data->data as $key => $value) {
                $dineroInicial = $value->{"ingreso.valor"};
            }

        } else {


            /* Se definen reglas de filtrado para realizar consultas en una base de datos. */
            $rules = [];
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usuario_cierrecaja.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime(date("Y-m-d 00:00:00") . ' - 1 days')), "op" => "ge"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte datos a JSON y obtiene información de cierre de caja de usuarios. */
            $json = json_encode($filtro);

            $UsuarioCierrecaja = new UsuarioCierrecaja();

            $data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.usucierrecaja_id", "desc", 0, 1, $json, true);

            $data = json_decode($data);

            foreach ($data->data as $key => $value) {


                /* crea un array con datos de un usuario específico. */
                $array = [];


                $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
                $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};
                $array["UserName"] = $value->{"usuario.login"};

                /* asigna valores a un array a partir de propiedades de un objeto. */
                $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
                $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
                $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
                $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
                $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
                $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};

                /* Calcula el total financiero sumando ingresos y restando gastos de un usuario. */
                $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
                $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
                $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
                $array["Total"] = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
                    - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

                $dineroInicial = $array["Total"];

            }
        }


    }
}

/* Convierte $dineroInicial a float y configura respuesta sin errores y tipo éxito. */
$dineroInicial = floatval($dineroInicial);


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* inicializa un arreglo de respuesta con datos y errores relacionados. */
$response["ModelErrors"] = [];

$response["Data"] = $dineroInicial;
$response["pos"] = $SkeepRows;
$response["total_count"] = 0;
$response["data"] = $dineroInicial;
