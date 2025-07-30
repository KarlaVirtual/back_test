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
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

/**
 * UserManagement/GetPagoPremio
 *
 * Obtener detalle para pago de premio
 *
 * @param string $json->params->idTicket Número de ticket
 * @param string $json->params->passwordTicket Clave del ticket
 * @param object $json->session->usuario Usuario de la sesión
 *
 * @return array $response
 * - boolean HasError Indica si hubo un error
 * - string AlertType Tipo de alerta
 * - string AlertMessage Mensaje de alerta
 * - string url URL de redirección
 * - string success Indica si la operación fue exitosa
 *
 * @throws Exception Si los parámetros son inválidos o si el ticket no existe.
 */

$NoTicket = $json->params->idTicket;  // Se obtiene el número de ticket de los parámetros JSON
$ClaveTicket = $json->params->passwordTicket; // Se obtiene la clave del ticket de los parámetros JSON

$ItTicketEnc = new ItTicketEnc(); // Se instancia la clase ItTicketEnc

$ticket = $ItTicketEnc->checkTicket($NoTicket, $ClaveTicket); // Se verifica el ticket con el número y clave proporcionados

$MaxRows = 1; // Variable para definir el número máximo de filas
$SkeepRows = 0; // Variable para definir el número de filas a omitir
$OrderedItem = 1; // Variable para definir el ítem ordenado

if ($NoTicket == "" || $ClaveTicket == "") { // Se valida que los parámetros no estén vacíos
    throw new Exception("Error en los parametros enviados", "100001"); // Lanza una excepción si hay error en los parámetros
}

$ItTicketEnc = new ItTicketEnc(); // Se instancia nuevamente la clase ItTicketEnc

$ItTicketEnc = $ItTicketEnc->checkTicket($NoTicket, $ClaveTicket); // Se verifica el ticket nuevamente

if ($ItTicketEnc == null) { // Se verifica si el ticket no existe
    throw new Exception("No existe Ticket", "24"); // Lanza una excepción si el ticket no existe
}
// Inicialización de un array para reglas de filtrado
$rules = [];

// Agrega reglas de filtrado al array $rules
array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => "$NoTicket", "op" => "eq"));
array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

// Crea un filtro con las reglas y una operación de grupo
$filtro = array("rules" => $rules, "groupOp" => "AND");

// Convierte el filtro a formato JSON
$jsonfiltro = json_encode($filtro);

// Instancia de la clase ItTicketEnc
$ItTicketEnc = new ItTicketEnc();
// Obtiene tickets personalizados a través del método getTicketsCustom
$tickets = $ItTicketEnc->getTicketsCustom(" it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.estado,it_ticket_enc.premiado,it_ticket_enc.premio_pagado ", "it_ticket_enc.ticket_id", "asc", 0, 1, $jsonfiltro, true);
// Decodifica el JSON devuelto a formato de objeto
$tickets = json_decode($tickets);

// Inicialización del array de respuesta
$response = array();
// Asigna código de respuesta
$response["code"] = 0;
// Asigna el identificador único de respuesta
$response["rid"] = $json->rid;
if (oldCount($tickets->data) > 0) {

    if (oldCount($tickets->data) > 0) {
        // Inicializa la variable para el impuesto en 0
        $impuesto = 0;

        try {
            // Crea una nueva instancia de la clase Clasificador para obtener el impuesto sobre la apuesta
            $Clasificador = new Clasificador("", "TAXBETPAYPRIZE");
            $minimoMontoPremios = 0;

            // Crea una nueva instancia de MandanteDetalle para obtener el porcentaje de impuesto
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

            // Obtiene el valor del impuesto sobre la apuesta
            $impuestoPorcSobreApuesta = $MandanteDetalle->getValor();

            // Calcula el impuesto sobre la apuesta
            $impuesto = floatval($impuestoPorcSobreApuesta / 100) * floatval($tickets->data[0]->{"it_ticket_enc.vlr_apuesta"});

        } catch (Exception $e) {
            // Manejo de excepciones vacío
        }

        try {
            // Crea una nueva instancia de la clase Clasificador para obtener el impuesto sobre el premio
            $Clasificador = new Clasificador("", "TAXPRIZEBETSHOP");
            $minimoMontoPremios = 0;

            // Crea una nueva instancia de MandanteDetalle para obtener el porcentaje de impuesto
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

            // Obtiene el valor del impuesto sobre el premio
            $impuestoPorcSobrePremio = $MandanteDetalle->getValor();

            // Calcula la cantidad sujeta a impuesto
            $paraImpuesto = floatval($tickets->data[0]->{"it_ticket_enc.vlr_premio"}) - floatval($tickets->data[0]->{"it_ticket_enc.vlr_apuesta"});
            if ($paraImpuesto < 0) {
                $impuesto += 0; // Si paraImpuesto es negativo no se agrega impuesto
            } else {
                // Calcula el impuesto sobre el premio y lo agrega al impuesto total
                $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
            }

        } catch (Exception $e) {
            // Manejo de excepciones vacío
        }

        // Prepara la respuesta con los detalles del ticket
        $response["data"] = array(
            "valueBet" => $tickets->data[0]->{"it_ticket_enc.vlr_apuesta"}, // Valor de la apuesta
            "valuePayment" => floatval($tickets->data[0]->{"it_ticket_enc.vlr_premio"}) - $impuesto, // Valor del pago después de impuestos
            "prizeProjected" => $tickets->data[0]->{"it_ticket_enc.vlr_premio"}, // Premio proyectado
            "state" => $tickets->data[0]->{"it_ticket_enc.estado"}, // Estado del ticket
            "winner" => $tickets->data[0]->{"it_ticket_enc.premiado"}, // Información de si es un ganador
        );
        // Verifica si el ticket está marcado como premiado
        if ($tickets->data[0]->{"it_ticket_enc.premiado"} == "S") {
            $response["data"]["payment"] = "SI"; // Si está premiado, establece el estado de pago a "SI"

        } else {
            $response["data"]["payment"] = "NO"; // Si no está premiado, establece el estado de pago a "NO"

        }

        // Verifica si el premio del ticket ha sido pagado
        if ($tickets->data[0]->{"it_ticket_enc.premio_pagado"} == "S") {
            $response["data"]["paymentPaid"] = "S"; // Si ha sido pagado, establece el estado de pago a "S"

        } else {
            $response["data"]["paymentPaid"] = "N"; // Si no ha sido pagado, establece el estado de pago a "N"

        }
    }
}else {

/**
 * Asigna un código de respuesta a la variable $response
 *
 * @var array $response Un arreglo que contiene la respuesta
 * @var int $response["code"] El código de respuesta asignado
 */
$response["code"] = 1;

}
