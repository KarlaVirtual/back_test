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
use Backend\dto\Consecutivo;use Backend\dto\ConfigurationEnvironment;
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
exit();
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
$UsuarioPerfilUsuario = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());


/**
 * Report/GetBetHistoryDetail
 *
 * Obtener historial de apuestas con detalles
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada.
 * @param int $json->params->id Identificador del ticket.
 * @param int $json->params->count Número máximo de filas a obtener.
 * @param int $json->params->start Número de filas a omitir.
 * @param int $json->params->OrderedItem Item ordenado.
 *
 * @return array Respuesta con el código, rid, posición, total de filas y datos.
 * @return int $response["code"] Código de respuesta.
 * @return string $response["rid"] Identificador de la solicitud.
 * @return int $response["pos"] Posición de inicio.
 * @return int $response["total_count"] Total de filas.
 * @return array $response["data"] Datos obtenidos.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */


$ItTicketEnc = new ItTicketEnc();

$Id = $json->params->id;

$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;

$OrderedItem = $json->params->OrderedItem;

$seguir = true;

if ($SkeepRows == "") {
    $SkeepRows = 0; // Si no se especifica el número de filas a omitir, se establece en 0
}

if ($OrderedItem == "") {
    $OrderedItem = 1; // Si no se especifica el item ordenado, se establece en 1
}

if ($MaxRows == "") {
    $MaxRows = 100; // Si no se especifica el número máximo de filas, se establece en 100
}


/**
 * Verifica si la variable $Id está vacía.
 * Si es así, se establece la variable $seguir como false.
 *
 * @var string $Id Identificador que se verifica si está vacío.
 * @var bool $seguir Estado que indica si se debe continuar.
 */
if ($Id == "") {
    $seguir = false;
}

if ($seguir) {
    /**
     * Inicializa un array vacío para las reglas de configuración.
     * Se crea una nueva instancia de la clase ConfigurationEnvironment.
     */
    $rules = [];
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    if(!$ConfigurationEnvironment->isDevelopment()) {

        /**
         * Se construyen reglas para filtrar tickets en función del perfil del usuario.
         *
         * - Se agrega una regla básica con el identificador del ticket.
         * - Se añaden condiciones adicionales basadas en el perfil del usuario.
         * - Se crea un filtro en formato JSON que se utilizará para obtener detalles del ticket.
         * - Se ejecuta una consulta para obtener los detalles del ticket.
         * - Finalmente, se organiza la información de los tickets en un array.
         */

        // Se añade una regla que filtra por el ID del ticket
        array_push($rules, array("field" => "it_ticket_det.ticket_id", "data" => "$Id", "op" => "eq"));

        if ($UsuarioPerfilUsuario->perfilId == "CONCESIONARIO") {
            // Se añade una regla que filtra por el ID del padre del concesionario
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

        }

        if ($UsuarioPerfilUsuario->perfilId == "CONCESIONARIO2") {
            // Se añade una regla que filtra por el ID del segundo padre del concesionario
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

        }

        if ($UsuarioPerfilUsuario->perfilId == "CAJERO") {
            // Se añade una regla que filtra por el ID del punto de venta del usuario
            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

        }

        if ($UsuarioPerfilUsuario->perfilId == "PUNTOVENTA") {
            // Se añade una regla que filtra por el ID del punto de venta del usuario
            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

        }

// Se crea el filtro en formato JSON con las reglas definidas
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

        // Se crea una instancia de ItTicketEnc para obtener detalles de los tickets
        $ItTicketEnc = new ItTicketEnc();
        $tickets = $ItTicketEnc->getTicketDetallesCustom(" it_ticket_det.* ", "it_ticket_det.it_ticketdet_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);
        $tickets = json_decode($tickets);

        $final = [];

        // Se itera a través de los tickets obtenidos para organizar la información
        foreach ($tickets->data as $key => $value) {

            $array = [];

            // Se almacena la información del ticket en un array
            $array["id"] = $value->{"it_ticket_det.it_ticketdet_id"};
            $array["ticketId"] = $value->{"it_ticket_det.ticket_id"};
            $array["description"] = $value->{"it_ticket_det.apuesta"};
            $array["market"] = $value->{"it_ticket_det.agrupador"};
            $array["odds"] = $value->{"it_ticket_det.logro"};
            $array["option"] = $value->{"it_ticket_det.opcion"};

            // Se añade el array del ticket final al array principal
            array_push($final, $array);

        }
    }else{
        // Agrega una nueva regla al arreglo de reglas para filtrar por 'ticket_id' de la transacción en sportsbook
        array_push($rules, array("field" => "transaccion_sportsbook.ticket_id", "data" => "$Id", "op" => "eq"));

        // Crea un filtro para las reglas y establece la operación de agrupamiento como "AND"
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonfiltro = json_encode($filtro);

        // Crea una nueva instancia de la clase TranssportsbookDetalle
        $TranssportsbookDetalle = new \Backend\dto\TranssportsbookDetalle();
        $tickets = $TranssportsbookDetalle->getTransaccionesCustom(" transsportsbook_detalle.* ", "transsportsbook_detalle.transsportdet_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);
        $tickets = json_decode($tickets);

        // Inicializa un arreglo final para almacenar los datos procesados
        $final = [];

        // Itera sobre los tickets obtenidos
        foreach ($tickets->data as $key => $value) {

            // Inicializa un arreglo temporal para almacenar información del ticket
            $array = [];
            $array["id"] = $value->{"transsportsbook_detalle.it_ticketdet_id"}; // ID del detalle del ticket
            $array["ticketId"] = $value->{"transsportsbook_detalle.ticket_id"}; // ID del ticket
            $array["description"] = $value->{"transsportsbook_detalle.apuesta"}; // Descripción de la apuesta
            $array["market"] = $value->{"transsportsbook_detalle.agrupador"}; // Agrupador del mercado
            $array["odds"] = $value->{"transsportsbook_detalle.logro"}; // Odds (probabilidades) de la apuesta
            $array["option"] = $value->{"transsportsbook_detalle.opcion"}; // Opción seleccionada en la apuesta

            // Agrega el arreglo temporal al arreglo final
            array_push($final, $array);

        }
    }

    //Formato de respuesta correcta
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["pos"] = $SkeepRows;
    $response["total_count"] = $tickets->count[0]->{".count"};
    $response["data"] = $final;
} else {
    //Formato respuesta vacía
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;


    $response["error_code"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
