<?php

/**
 * Archivo principal para la gestión de flujo de caja y transacciones.
 *
 * Este archivo contiene la lógica para procesar solicitudes relacionadas con el flujo de caja,
 * incluyendo validaciones de fechas, autenticación mediante tokens JWT, y consultas a la base de datos
 * para obtener información resumida o detallada de transacciones.
 *
 * @category Finanzas
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $timeNow                  Variable que almacena la hora actual.
 * @var mixed $PuntoVenta               Variable que almacena información sobre un punto de venta.
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $header                   Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $TokenHeader              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $final                    Esta variable se utiliza para indicar si un proceso o estado es final.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $FromDateLocal            Variable que almacena la fecha local de inicio de un proceso o intervalo.
 * @var mixed $ToDateLocal              Variable que almacena la fecha local de finalización de un proceso o intervalo.
 * @var mixed $paisId                   Variable que almacena el identificador único de un país.
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $date                     Variable que almacena una fecha genérica.
 * @var mixed $CountrySelect            Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $BetShopId                Variable que almacena el identificador de una casa de apuestas.
 * @var mixed $TypeTotal                Variable que almacena el tipo de total a calcular.
 * @var mixed $UserIdAgent              Variable que almacena el identificador de un agente de usuario.
 * @var mixed $UserIdAgent2             Variable que almacena un segundo identificador de un agente de usuario.
 * @var mixed $MaxRows                  Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $OrderedItem              Variable que representa un elemento ordenado en una lista.
 * @var mixed $SkeepRows                Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $seguir                   Variable que indica si se debe continuar con una operación o proceso.
 * @var mixed $totalm                   Variable que almacena el total de un cálculo o proceso.
 * @var mixed $conHoras                 Variable que indica si se deben considerar horas en un cálculo.
 * @var mixed $rules                    Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $IsDetails                Variable que indica si se incluyen detalles en la respuesta.
 * @var mixed $grouping                 Variable que almacena criterios de agrupación de datos.
 * @var mixed $select                   Variable que almacena una consulta o selección de datos.
 * @var mixed $Pais                     Variable que almacena el nombre de un país.
 * @var mixed $transacciones            Variable que almacena un conjunto de transacciones.
 * @var mixed $json                     Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $FromDateLocal2           Variable que almacena una segunda fecha de inicio en formato local.
 * @var mixed $ToDateLocal2             Variable que almacena una segunda fecha de finalización en formato local.
 * @var mixed $timezone                 Variable que almacena la zona horaria.
 * @var mixed $filtro                   Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $BodegaFlujoCaja          Variable que almacena información sobre el flujo de caja en bodega.
 * @var mixed $_SESSION                 Variable que almacena datos de sesión del usuario.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $_ENV                     Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $e                        Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 */

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\BodegaFlujoCaja;
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


$timeNow = time();
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);
header('Content-Type: application/json');
$headers = getallheaders();

$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'ApiVirtualSoft';
} else {
    $usuario = 'ApiVirtualSoft';
}
$header = json_encode([
    'alg' => 'HS256',
    'typ' => 'JWT'
]);

$payload = json_encode([
    'codigo' => 0,
    'mensaje' => 'OK',
    "usuario" => $usuario
]);

$key = 'VirtualS2022';

$signature = hash('sha256', $header . $payload . $key);

$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

$TokenHeader = $headers["token"];

if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

if ($TokenHeader === $token) {
    $final = [];
    if ($_REQUEST["DateFrom"] != "") {
        $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["DateFrom"])));
    }
    if ($_REQUEST["DateTo"] != "") {
        $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["DateTo"])));
    }
    $paisId = $_GET["CountrySelect"];
    $Mandante = $_GET["Mandante"];

    $date = date("Y-m-d", strtotime($ToDateLocal . " -7 days"));


    if ($FromDateLocal >= $date) {
        $CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
        $BetShopId = (is_numeric($_REQUEST["BetShopId"])) ? $_REQUEST["BetShopId"] : '';
        $TypeTotal = ($_REQUEST["Type"] == "0") ? 0 : 1;

        $UserIdAgent = (is_numeric($_REQUEST["UserIdAgent"])) ? $_REQUEST["UserIdAgent"] : '';
        $UserIdAgent2 = (is_numeric($_REQUEST["UserIdAgent2"])) ? $_REQUEST["UserIdAgent2"] : '';
        $MaxRows = $_REQUEST["Count"];
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?Start"] : $_REQUEST["Start"];

        $seguir = true;

        if ($SkeepRows == "" || $MaxRows == "") {
            // $seguir = false;

        }

        $totalm = 0;


        if ($seguir) {
            $conHoras = false;


            $rules = [];
            $IsDetails = ($params->IsDetails == true) ? true : false;


            $grouping = "";
            $select = "";
            if ($IsDetails) {
            } else {
                if ($TypeTotal == 0) {
                    $grouping = 0;
                } else {
                    $grouping = 1;
                }

                $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
            }

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100000;
            }
            $MaxRows = 100000;


            if ($_REQUEST["UserId"] != "") {
                $BetShopId = $_REQUEST["UserId"];
            }
            if ($_REQUEST["Mandante"] != "") {
                $Mandante = $_REQUEST["Mandante"];
            }

            if ($_REQUEST["CountrySelect"] != "") {
                $Pais = $_REQUEST["CountrySelect"];
            }

            $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $UserIdAgent, $UserIdAgent2, "", $Pais, $Mandante, $BetShopId);


            $transacciones = json_decode($transacciones);

            foreach ($transacciones->data as $key => $value) {
                $array = [];
                $array["Punto"] = "PUNTO";

                $array["Punto"] = $value->{"y.punto_venta"};

                $array["UsuarioId"] = $value->{"y.usuario_id"};

                $array["Fecha"] = $value->{"y.fecha_crea"};
                $array["Moneda"] = $value->{"y.moneda"};
                $array["PaisId"] = $value->{"y.pais_nom"} . ' - ' . $value->{"y.mandante"};
                $array["Partner"] = $value->{"y.mandante"};
                $array["PaisIso"] = strtolower($value->{"y.pais_iso"});
                $array["Agente"] = $value->{"uu.agente"} . ' - ' . $array["Moneda"];
                $array["CantidadTickets"] = $value->{".cant_tickets"};
                $array["ValorEntradasEfectivo"] = $value->{".valor_entrada_efectivo"};
                $array["ValorEntradasBonoTC"] = $value->{".valor_entrada_bono"};
                $array["ValorEntradasRecargas"] = $value->{".valor_entrada_recarga"};
                $array["ValorEntradasRecargasAgentes"] = $value->{".valor_entrada_recarga_agentes"};
                $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};
                $array["ValorSalidasEfectivo"] = $value->{".valor_salida_efectivo"};
                $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};
                $array["ValorSalidasNotasRetiro"] = $value->{".valor_salida_notaret"};
                $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"] + $array["ValorEntradasRecargasAgentes"];
                $array["Nombre"] = $value->{"y.punto_venta"};
                $array["Impuesto"] = $value->{".impuestos"};


                $array["ApuestasRealizadasAnuladas"] = $value->{".apuestas_void"};
                $array["RecargasAnuladas"] = $value->{".valor_entrada_recarga_anuladas"};


                $array["ApuestasPagadasAnuladas"] = $value->{".premios_void"};

                $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["ApuestasRealizadasAnuladas"];
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["ApuestasRealizadasAnuladas"];

                if ($array["Partner"] == 1 || $array["Partner"] == 2) {
                    $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["ApuestasPagadasAnuladas"];
                    $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
                    $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["ApuestasPagadasAnuladas"];
                }

                if ($TypeTotal == 1) {
                    $array["Punto"] = $value->{"y.punto_venta"} . $array["Agent"];
                }
                if ($array["UserId"] != '') {
                    array_push($final, $array);
                }
            }
        }


        try {
            if ($seguir) {
                $FromDateLocal2 = date("Y-m-d", strtotime(time()));
                $ToDateLocal2 = date("Y-m-d", strtotime(time()));


                if ($_REQUEST["DateFrom"] != "") {
                    $FromDateLocal2 = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["DateFrom"]) . $timezone . ' hour '));
                }
                if ($_REQUEST["DateTo"] != "") {
                    $ToDateLocal2 = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["DateTo"]) . '' . $timezone . ' hour '));
                }
                $rules = [];

                array_push($rules, array("field" => "bodega_flujo_caja.fecha", "data" => "$FromDateLocal2", "op" => "ge"));
                array_push($rules, array("field" => "bodega_flujo_caja.fecha", "data" => "$ToDateLocal2", "op" => "le"));


                if ($_REQUEST["UserId"] != "") {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_REQUEST["UserId"], "op" => "eq"));
                }


                if ($_REQUEST["BetShopId"] != "") {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_REQUEST["BetShopId"], "op" => "eq"));
                }


                if ($UserIdAgent != "") {
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UserIdAgent, "op" => "eq"));
                }


                if ($UserIdAgent2 != "") {
                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UserIdAgent2, "op" => "eq"));
                }


                $grouping = "";
                $select = "";
                if ($TypeTotal == '0') {
                    $select = 'bodega_flujo_caja.usuario_id,
       bodega_flujo_caja.fecha,
       bodega_flujo_caja.pais_id,
       bodega_flujo_caja.mandante,
       bodega_flujo_caja.concesionario_id,
       SUM(bodega_flujo_caja.cant_tickets) cant_tickets,
       SUM(bodega_flujo_caja.valor_entrada_efectivo) valor_entrada_efectivo,
       SUM(bodega_flujo_caja.valor_entrada_bono) valor_entrada_bono,
       SUM(bodega_flujo_caja.valor_entrada_recarga) valor_entrada_recarga,
              SUM(bodega_flujo_caja.recargas_anuladas) valor_entrada_recarga_anuladas,
       SUM(bodega_flujo_caja.valor_entrada_recarga_agentes) valor_entrada_recarga_agentes,
       SUM(bodega_flujo_caja.valor_entrada_traslado) valor_entrada_traslado,
       SUM(bodega_flujo_caja.valor_salida_traslado) valor_salida_traslado,
       SUM(bodega_flujo_caja.valor_salida_notaret) valor_salida_notaret,
       SUM(bodega_flujo_caja.valor_entrada) valor_entrada,
       SUM(bodega_flujo_caja.valor_salida) valor_salida,
       SUM(bodega_flujo_caja.valor_salida_efectivo) valor_salida_efectivo,
       SUM(bodega_flujo_caja.premios_pend) premios_pend,
       SUM(bodega_flujo_caja.impuestos) impuestos,
       SUM(bodega_flujo_caja.apuestas_void) apuestas_void,
       SUM(bodega_flujo_caja.premios_void) premios_void,
       
       usuario.usuario_id,
       usuario.login,
       usuario.moneda,
       punto_venta.descripcion,
       
       pais.*,
       
       agente.nombre,
       agente2.nombre,agente.usuario_id,agente2.usuario_id';

                    $grouping = 'bodega_flujo_caja.usuario_id,bodega_flujo_caja.fecha';
                } else {
                    $select = 'bodega_flujo_caja.usuario_id,
       bodega_flujo_caja.fecha,
       bodega_flujo_caja.pais_id,
       bodega_flujo_caja.mandante,
       bodega_flujo_caja.concesionario_id,
       SUM(bodega_flujo_caja.cant_tickets) cant_tickets,
       SUM(bodega_flujo_caja.valor_entrada_efectivo) valor_entrada_efectivo,
       SUM(bodega_flujo_caja.valor_entrada_bono) valor_entrada_bono,
       SUM(bodega_flujo_caja.valor_entrada_recarga) valor_entrada_recarga,
       SUM(bodega_flujo_caja.recargas_anuladas) valor_entrada_recarga_anuladas,
       SUM(bodega_flujo_caja.valor_entrada_recarga_agentes) valor_entrada_recarga_agentes,
       SUM(bodega_flujo_caja.valor_entrada_traslado) valor_entrada_traslado,
       SUM(bodega_flujo_caja.valor_salida_traslado) valor_salida_traslado,
       SUM(bodega_flujo_caja.valor_salida_notaret) valor_salida_notaret,
       SUM(bodega_flujo_caja.valor_entrada) valor_entrada,
       SUM(bodega_flujo_caja.valor_salida) valor_salida,
       SUM(bodega_flujo_caja.valor_salida_efectivo) valor_salida_efectivo,
       SUM(bodega_flujo_caja.premios_pend) premios_pend,
       SUM(bodega_flujo_caja.impuestos) impuestos,
       SUM(bodega_flujo_caja.apuestas_void) apuestas_void,
       SUM(bodega_flujo_caja.premios_void) premios_void,
       
       usuario.usuario_id,
       usuario.login,
       usuario.moneda,
       punto_venta.descripcion,
       
       pais.*,
       
       agente.nombre,
       agente2.nombre,agente.usuario_id,agente2.usuario_id
       
       ';

                    $grouping = 'bodega_flujo_caja.mandante,concesionario.usupadre_id,bodega_flujo_caja.fecha';
                }


                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000;
                }
                $MaxRows = 1000000;


                $Pais = "";

                if ($CountrySelect != "" && $CountrySelect != "0") {
                    $Pais = $CountrySelect;
                }


                $Mandante = "";


                if ($Pais != '') {
                    array_push($rules, array("field" => "bodega_flujo_caja.pais_id", "data" => $Pais, "op" => "in"));
                }

                if ($Mandante != '') {
                    array_push($rules, array("field" => "bodega_flujo_caja.mandante", "data" => $Mandante, "op" => "in"));
                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 5;
                }

                $json = json_encode($filtro);


                $BodegaFlujoCaja = new BodegaFlujoCaja();
                $transacciones = $BodegaFlujoCaja->getBodegaFlujoCajaCustom($select, "punto_venta.descripcion asc,bodega_flujo_caja.fecha", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);


                $transacciones = json_decode($transacciones);

                $totalm = 0;
                foreach ($transacciones->data as $key => $value) {
                    $array = [];
                    $array["Punto"] = "PUNTO";

                    if ($TypeTotal == '0') {
                        if ($value->{"bodega_flujo_caja.mandante"} == '2') {
                            $array["Punto"] = $value->{"punto_venta.descripcion"};
                        } else {
                            $array["Punto"] = $value->{"punto_venta.descripcion"} . ' - ' . $value->{"usuario.usuario_id"};
                        }
                    } else {
                        $array["Punto"] = 'Punto Venta ' . $value->{"usuario.moneda"} . $value->{"agente.usuario_id"};


                        if (strtolower($_SESSION["idioma"]) == "en") {
                            $array["Punto"] = 'Betshop ' . $value->{"usuario.moneda"} . $value->{"agente.usuario_id"};
                        }
                    }


                    $array["UserId"] = $value->{"usuario.usuario_id"};

                    $array["Fecha"] = $value->{"bodega_flujo_caja.fecha"};
                    $array["Moneda"] = $value->{"usuario.moneda"};
                    $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"bodega_flujo_caja.mandante"};
                    $array["Partner"] = $value->{"bodega_flujo_caja.mandante"};
                    $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                    $array["Agent"] = $value->{"agente.nombre"} . ' - ' . $array["Moneda"];
                    $array["CantidadTickets"] = $value->{".cant_tickets"};
                    $array["ValorEntradasEfectivo"] = $value->{".valor_entrada_efectivo"};
                    $array["ValorEntradasBonoTC"] = $value->{".valor_entrada_bono"};
                    $array["ValorEntradasRecargas"] = $value->{".valor_entrada_recarga"};
                    $array["ValorEntradasRecargasAgentes"] = $value->{".valor_entrada_recarga_agentes"};

                    $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};
                    $array["ValorSalidasEfectivo"] = $value->{".valor_salida_efectivo"};
                    $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};
                    $array["ValorSalidasNotasRetiro"] = $value->{".valor_salida_notaret"};
                    $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"] + $array["ValorEntradasRecargasAgentes"];
                    $array["MMoneda"] = $value->{"usuario.moneda"};
                    $array["Tax"] = $value->{".impuestos"};


                    $array["VoidedPlacedBets"] = $value->{".apuestas_void"};
                    $array["RecargasAnuladas"] = $value->{".valor_entrada_recarga_anuladas"};

                    $array["VoidedPaidBets"] = $value->{".premios_void"};

                    $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];
                    $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

                    if ($array["Partner"] == 1 || $array["Partner"] == 2) {
                        $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
                        $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
                        $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];
                    }

                    if ($array["UserId"] != '') {
                        array_push($final, $array);
                    }
                }

                $response["Pos"] = $SkeepRows;
                $response["TotalCount"] = null;
                $response["Data"] = $final;

                if ($_ENV['debug']) {
                    print_r($response);
                    exit();
                }
            }

            $response["Pos"] = $SkeepRows;
            $response["TotalCount"] = null;
            $response["Data"] = $final;
        } catch (Exception $e) {
        }
    } else {
        $response["Error"] = true;
        $response["Mensaje"] = "fechas fueras del rango de 7 dias permitidos";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
}



