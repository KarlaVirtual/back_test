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
 * Envía un mensaje de texto relacionado con operaciones de apuestas o transacciones basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param string $params ->Code Código asociado a la operación (ID de recarga, cobro o ticket).
 * @param string $params ->Phone Número de teléfono del usuario.
 * @param string $params ->Type Tipo de operación ('MakeDeposit', 'PayNoteWithdrawal', 'PayWinningTicket', etc.).
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('Success' en caso de éxito, 'Error' en caso de fallo).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si el código proporcionado no es válido o no existe.
 *                   Código: '44', Mensaje: 'No existe el código asociado'.
 * @throws Exception Si el número de teléfono proporcionado no es válido.
 *                   Código: '45', Mensaje: 'Número de teléfono no válido'.
 */

/* asigna valores de parámetros a variables para su uso posterior. */
$Code = $params->Code;
$Phone = $params->Phone;
$Type = $params->Type;

if ($Type == 'MakeDeposit') {
    if (($Code != "" && is_numeric($Code)) && ($Phone != "" && is_numeric($Phone))) {

        /* crea objetos para manejar usuarios y depósitos, generando un mensaje informativo. */
        $Usuario = new Usuario($_SESSION["usuario"]);

        $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
        $UsuarioRecarga = new UsuarioRecarga($Code);
        $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
        $mensaje_txt = $Mandante->nombre . ' le informa deposito a su cuenta por ' . $Usuario->moneda . ' ' . $UsuarioRecarga->valor . ' ID del deposito (' . $UsuarioRecarga->recargaId . ')';


        /* Se inicializa un objeto de configuración y se preparan mensajes para enviar. */
        $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

        //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Phone, 0, $UsuarioMandante);
        //$envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($mensaje_txt, '', $Phone, 0, $UsuarioMandante);

        $UsuarioMensajes = array();

        /* Crea un array con datos de usuario y lo añade a otro array de mensajes. */
        $varArray = array();
        $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
        $varArray['tophone'] = $Phone;
        $varArray['link'] = '';

        array_push($UsuarioMensajes, $varArray);

        /* Envía un mensaje de texto y genera una respuesta de éxito sin errores. */
        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Phone, 0, $UsuarioMandante);
        $cambios = true;


        $response["HasError"] = false;
        $response["AlertType"] = "Success";

        /* Inicializa un mensaje de alerta vacío y un array para errores del modelo. */
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    } else {
        /* Manejo de errores en una respuesta, indicando que ocurrió un problema. */

        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    }

} elseif ($Type == 'PayNoteWithdrawal') {

    /* Valida datos y envía un mensaje de texto sobre un pago exitoso. */
    if (($Code != "" && is_numeric($Code)) && ($Phone != "" && is_numeric($Phone))) {
        $Usuario = new Usuario($_SESSION["usuario"]);

        $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
        $CuentaCobro = new CuentaCobro($Code);
        $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
        $mensaje_txt = $Mandante->nombre . ' ha pagado exitosamente su notas de retiro por valor ' . $CuentaCobro->valor . ' a las ' . $CuentaCobro->fechaPago . '. ID ' . $CuentaCobro->cuentaId;

        $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Phone, 0, $UsuarioMandante);
        $cambios = true;

        $response["HasError"] = false;
        $response["AlertType"] = "Success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    } else {
        /* Manejo de errores, configurando respuesta con detalles sobre el estado del sistema. */

        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    }

} elseif ($Type == 'PayWinningTicket') {

    /* Envía un mensaje de pago de apuesta si el código y teléfono son válidos. */
    if (($Code != "" && is_numeric($Code)) && ($Phone != "" && is_numeric($Phone))) {
        $Usuario = new Usuario($_SESSION["usuario"]);


        $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
        $ItTicketEnc = new ItTicketEnc($Code);
        $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
        $mensaje_txt = $Mandante->nombre . ' le informa pago de su apuesta ' . $ItTicketEnc->ticketId . ' por ' . $Usuario->moneda . ' ' . $ItTicketEnc->vlrPremio;

        $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Phone, 0, $UsuarioMandante);
        $cambios = true;


        $response["HasError"] = false;
        $response["AlertType"] = "Success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    } else {
        /* maneja un error, configurando una respuesta con detalles específicos. */

        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    }

} else {
    if (true) {

        /* Valida datos, crea mensajes y envía información sobre pronósticos a un usuario. */
        if (($Code != "" && is_numeric($Code)) && ($Phone != "" && is_numeric($Phone))) {
            $Usuario = new Usuario($_SESSION["usuario"]);

            $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
            $ItTicketEnc = new ItTicketEnc($Code);
            $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
            $mensaje_txt = $Mandante->nombre . ' le informa: Pronostico ID ' . $ItTicketEnc->ticketId . ' - IB ' . $ItTicketEnc->clave . ' por ' . $Usuario->moneda . ' ' . $ItTicketEnc->vlrApuesta . ', WIN ' . $Usuario->moneda . ' ' . $ItTicketEnc->vlrPremio . '(Cuota ' . ($ItTicketEnc->vlrPremio / $ItTicketEnc->vlrApuesta) . ')';

            $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Phone, 0, $UsuarioMandante);
            $cambios = true;


            $response["HasError"] = false;
            $response["AlertType"] = "Success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];
        } else {
            /* maneja un error al establecer parámetros en una respuesta JSON. */

            $response["HasError"] = true;
            $response["AlertType"] = "Error";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        }

    }
}