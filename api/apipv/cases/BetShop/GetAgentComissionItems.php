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
 * Obtiene los ítems de las comisiones del agente basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params ->FromId Identificador del usuario que solicita los ítems de comisión.
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Datos de las comisiones, incluyendo:
 *                             - Id (int): Identificador del producto.
 *                             - SumMaxComission (int): Suma máxima de comisiones.
 *                             - MaxComissionLevelBetShop (int): Comisión máxima para el nivel BetShop.
 *                             - MaxComissionLevel1 (int): Comisión máxima para el nivel 1.
 *                             - MaxComissionLevel2 (int): Comisión máxima para el nivel 2.
 *                             - MaxComissionLevel3 (int): Comisión máxima para el nivel 3.
 *                             - MaxComissionLevel4 (int): Comisión máxima para el nivel 4.
 *                             - ProductName (string): Nombre del producto.
 *                             - ComissionLevel1 (float): Comisión asignada al nivel 1.
 *                             - ComissionLevel2 (float): Comisión asignada al nivel 2.
 *                             - ComissionLevel3 (float): Comisión asignada al nivel 3.
 *                             - ComissionLevel4 (float): Comisión asignada al nivel 4.
 *                             - ComissionLevelBetShop (float): Comisión asignada al nivel BetShop.
 */


/* PHP establece una respuesta indicando éxito en una operación y recoge un ID. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];

$FromId = $_REQUEST["FromId"];


/* Código en PHP para inicializar un objeto Usuario y un array vacío. */
$Usuario = new Usuario($FromId);
$result_array = array();

$campos = "";
$cont = 0;

$rules = [];

/* agrega reglas basadas en el mandante del usuario. */
if ($Usuario->mandante == '8') {
    //array_push($rules, array("field" => "clasificador.abreviado", "data" => "'SPORTNGRAFF','DEPOSITOAFF','BETSPORTPV','CASINONGRAFF'", "op" => "ni"));

}
if ($Usuario->mandante == '13') {
    //array_push($rules, array("field" => "clasificador.abreviado", "data" => "'SPORTNGRAFF','DEPOSITOAFF','BETSPORTPV','CASINONGRAFF'", "op" => "ni"));

}
array_push($rules, array("field" => "clasificador.tipo", "data" => "PCOM", "op" => "eq"));

/* Se generan reglas de filtrado para consultas, agrupadas bajo una operación "AND". */
array_push($rules, array("field" => "clasificador.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "DISP", "data" => "DISP", "op" => "neq"));

array_push($rules, array("field" => "clasificador_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "clasificador_mandante.mandante", "data" => $Usuario->mandante, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro en JSON y obtiene productos de concesionarios. */
$jsonfiltro = json_encode($filtro);


$Concesionario = new Concesionario();
$productos = $Concesionario->getConcesionariosProductoInternoCustom("clasificador.clasificador_id,clasificador.abreviado,clasificador.descripcion, concesionario.porcenpadre1,concesionario.porcenpadre2,concesionario.porcenpadre3,concesionario.porcenpadre4,concesionario.porcenhijo  ", "clasificador.clasificador_id", "asc", 0, 10000, $jsonfiltro, true, $FromId, true);
$productos = json_decode($productos);


/* Se inicializa un arreglo vacío llamado $final en PHP. */
$final = array();

foreach ($productos->data as $producto) {


    /* Variables que definen comisiones máximas para diferentes niveles en una apuesta. */
    $SumMaxComission = 100;
    $MaxComissionLevelBetShop = 100;
    $MaxComissionLevel1 = 100;
    $MaxComissionLevel2 = 100;
    $MaxComissionLevel3 = 100;
    $MaxComissionLevel4 = 100;

    if ($Usuario->mandante == '8' && ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3")) {
        switch ($producto->{"clasificador.abreviado"}) {

            case "SPORTNGRAFF":
                /* Configura comisiones máximas para el caso "SPORTNGRAFF" en diferentes niveles. */


                $SumMaxComission = 50;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;
                break;


            case "DEPOSITOAFF":
                /* define comisiones máximas para diferentes niveles de depósito. */


                $SumMaxComission = 0;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;


            case "SPORTPVIP":
                /* Define límites de comisiones para el tipo "SPORTPVIP" en diversas categorías. */


                $SumMaxComission = 50;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;


            case "SPORTPV":
                /* Establece límites de comisiones para distintos niveles en el caso SPORTPV. */


                $SumMaxComission = 50;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;

            case "DEPOSITOPV":
                /* Configura los niveles de comisiones para "DEPOSITOPV" en una aplicación. */


                $SumMaxComission = 50;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;

            case "BETSPORTPV":
                /* Establece comisiones máximas para diferentes niveles en una apuesta deportiva. */


                $SumMaxComission = 0;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;

            case "RETIROSPV":
                /* Configura las comisiones máximas para diferentes niveles de retiro en SPV. */


                $SumMaxComission = 50;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;

            case "SPORTAFF":
                /* Define límites de comisiones para diferentes niveles en el caso "SPORTAFF". */


                $SumMaxComission = 50;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;

            case "CASINONGRAFF":
                /* establece comisiones máximas para diferentes niveles en "CASINONGRAFF". */


                $SumMaxComission = 0;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;
            case "BETSPORTAFF":
                /* Configuración de comisiones máximas para diferentes niveles en BetSportAff. */


                $SumMaxComission = 50;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;
            case "WINSPORTAFF":
                /* define comisiones máximas para un caso específico en un sistema. */


                $SumMaxComission = 0;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;
                break;
            case "CASINOAFF":
                /* Código define comisiones máximas para diferentes niveles en un caso de "CASINOAFF". */


                $SumMaxComission = 0;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;
            case "WINSPORTPV":
                /* Código configura comisiones máximas para diferentes niveles en una apuesta de apuestas. */


                $SumMaxComission = 0;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;
            case "CASINOPV":
                /* Configura límites de comisiones para diferentes niveles en un caso de "CASINOPV". */


                $SumMaxComission = 0;

                $MaxComissionLevelBetShop = 30;
                $MaxComissionLevel1 = 50;
                $MaxComissionLevel2 = 40;
                $MaxComissionLevel3 = 0;
                $MaxComissionLevel4 = 0;

                break;
        }
    }


    if (strtolower($_SESSION["idioma"]) == "en") {
        switch ($producto->{"clasificador.descripcion"}) {

            case "Sportbook GGR Afiliados":
                /* Asigna la descripción "Sportsbook GGR Affiliates" al producto según el caso. */

                $producto->{"clasificador.descripcion"} = "Sportsbook GGR Affiliates";
                break;

            case "Deposito Afiliados":
                /* Asignación de la descripción "Deposit Affiliates" al producto para el caso específico. */

                $producto->{"clasificador.descripcion"} = "Deposit Affiliates";
                break;

            case "Sportbook GGR Punto venta":
                /* asigna una descripción a un producto basado en una categoría específica. */

                $producto->{"clasificador.descripcion"} = "Sportsbook GGR Betshop";
                break;

            case "Deposito Punto venta":
                /* Asigna "Deposit Betshop" a la descripción del producto en un caso específico. */

                $producto->{"clasificador.descripcion"} = "Deposit Betshop";
                break;

            case "Apuesta Sport Punto venta":
                /* Asigna una descripción de producto para apuestas deportivas en un sistema. */

                $producto->{"clasificador.descripcion"} = "Sportsbook Turnover Betshop";
                break;

            case "Notas de retiro Punto venta":
                /* asigna una descripción a un producto basado en un caso específico. */

                $producto->{"clasificador.descripcion"} = "Withdrawals Betshop";
                break;

        }

    }

    /* Construye un array PHP con información de un producto y sus comisiones. */
    $array = array(
        "Id" => $producto->{"clasificador.clasificador_id"},
        "SumMaxComission" => $SumMaxComission,
        "MaxComissionLevelBetShop" => $SumMaxComission,
        "MaxComissionLevel1" => $MaxComissionLevel1,
        "MaxComissionLevel2" => $MaxComissionLevel2,
        "MaxComissionLevel3" => $MaxComissionLevel3,
        "MaxComissionLevel4" => $MaxComissionLevel4,
        "ProductName" => $producto->{"clasificador.descripcion"},
        "ComissionLevel1" => ($producto->{"concesionario.porcenpadre1"} == "") ? 0 : $producto->{"concesionario.porcenpadre1"},
        "ComissionLevel2" => ($producto->{"concesionario.porcenpadre2"} == "") ? 0 : $producto->{"concesionario.porcenpadre2"},
        "ComissionLevel3" => ($producto->{"concesionario.porcenpadre3"} == "") ? 0 : $producto->{"concesionario.porcenpadre3"},
        "ComissionLevel4" => ($producto->{"concesionario.porcenpadre4"} == "") ? 0 : $producto->{"concesionario.porcenpadre4"},
        "ComissionLevelBetShop" => ($producto->{"concesionario.porcenhijo"} == "") ? 0 : $producto->{"concesionario.porcenhijo"}

    );

    /* Agrega el contenido de $array al final del arreglo $final en PHP. */
    array_push($final, $array);

}


/* Se asigna el valor de `$final` a dos claves en el arreglo `$response`. */
$response["Data"] = $final;
$response["data"] = $final;
