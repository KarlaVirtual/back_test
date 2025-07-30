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
 * Financial/DepositRequests
 *
 * Aprobar manualmente un deposito
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
 * Función para aprobar o rechazar manualmente una solicitud de depósito en el sistema.
 *
 * @param int $Id Identificador único de la solicitud de depósito.
 * @param int $State Estado de la solicitud de depósito (0 para aprobar, 1 para rechazar).
 * @param string $Reference Referencia de la transacción.
 * @param string $codeQR Código QR para la verificación de seguridad.
 *
 * @Description Este recurso permite aprobar o rechazar manualmente una solicitud de depósito en el sistema,
 * verificando la autenticidad del usuario y actualizando los registros correspondientes.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Lista de errores de validación del modelo.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'error';
 * $response['AlertMessage'] = 'An error occurred during the operation';
 * $response['ModelErrors'] = [];
 *
 * @throws Exception Permiso denegado
 * @throws Exception Inusual Detected
 * @throws Exception No tiene saldo para transferir
 *
 */
// Se crea una instancia de UsuarioMandante utilizando la información del usuario almacenada en la sesión.

/* Verifica permisos de usuario y registra advertencias si no tiene acceso a depósitos. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);

$ConfigurationEnvironment = new ConfigurationEnvironment();
if (!$ConfigurationEnvironment->checkUserPermission('Financial/DepositRequests', $_SESSION['win_perfil'], $_SESSION['usuario'])) {
    syslog(LOG_WARNING, "DEPOSITOAPROB :" . file_get_contents('php://input') . json_encode($_SERVER));
    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'DEPOSITOAPROB " . $Usuario->login . " " . $_SESSION["win_perfil"] . " " . $_SESSION["usuario"] . json_encode($_SERVER) . "' '#alertas-integraciones' > /dev/null & ");

    throw new Exception('Permiso denegado', 100035);
}


/* asigna valores de parámetros a variables y crea un objeto UsuarioMandante. */
$Id = $params->Id;
$State = $params->State;
$Reference = $params->Reference;
$codeQR = $params->codeQR;

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


/* Se crean objetos de Transacción, Producto y Proveedor usando identificadores relacionados. */
$TransaccionProducto = new TransaccionProducto($Id);
$Producto = new Producto ($TransaccionProducto->getProductoId());
$Proveedor = new Proveedor($Producto->getProveedorId());


if ($Proveedor->getTipo() == "PAYMENT") {


    switch ($State) {
        case "0":
            // Aprobar Deposito

            /* Verifica autenticación de usuario y código QR, lanzando excepciones en caso de anomalías. */
            $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);
            $Usuario2 = new Usuario($UsuarioMandante2->usuarioMandante);

            if ($Usuario2->mandante != 21) {


                if ($codeQR == '') {
                    throw new Exception("Inusual Detected", "110012");
                } else {
                    $Google = new GoogleAuthenticator();
                    $returnCodeGoogle = $Google->verifyCode($Usuario2->saltGoogle, $codeQR);
                    if (!$returnCodeGoogle) {
                        throw new Exception("Inusual Detected", "11");
                    }
                }
            }

            if ($TransaccionProducto->transproductoId == 102372427){
                error_reporting(E_ALL);
                ini_set("display_errors","ON");

            }

            /* Código que aprueba un producto según condiciones específicas de estado y id. */
            $comentario = "Aprobado manualmente por " . $UsuarioMandante->getUsuarioMandante();

            //|| $TransaccionProducto->getEstadoProducto() == "R" ||
            if (($TransaccionProducto->getEstadoProducto() == "R" && $TransaccionProducto->getProductoId() == "5503") || $TransaccionProducto->getEstadoProducto() == "E" || ($TransaccionProducto->getEstadoProducto() == "A" && ($TransaccionProducto->getFinalId() == "" || $TransaccionProducto->getFinalId() == "0"))) {


                $respuesta = $TransaccionProducto->setAprobada($TransaccionProducto->transproductoId, "M", "A", $comentario, "{}", $Reference);
            }

            break;

        case "1":
            // Rechazar Deposito

            /* Valida que el deposito no se encuentre pagado. */
            if ($TransaccionProducto->getEstadoProducto() == "A" && $TransaccionProducto->getEstado() == "I") {
                throw new Exception("Error al rechazar la solicitud", "300169");
            }
            /* Valida un código QR para un usuario, lanzando excepciones si no es correcto. */
            $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);
            $Usuario2 = new Usuario($UsuarioMandante2->usuarioMandante);

            if ($Usuario2->mandante != 21) {
                if ($codeQR == '') {
                    throw new Exception("Inusual Detected", "110012");
                } else {
                    $Google = new GoogleAuthenticator();
                    $returnCodeGoogle = $Google->verifyCode($Usuario2->saltGoogle, $codeQR);
                    if (!$returnCodeGoogle) {
                        throw new Exception("Inusual Detected", "11");
                    }
                }
            }


            /* Código para rechazar manualmente un producto basado en su estado. */
            $comentario = "Rechazado manualmente por " . $UsuarioMandante->getUsuarioMandante();

            if ($TransaccionProducto->getEstadoProducto() == "E" || $TransaccionProducto->getEstadoProducto() == "A") {
                $respuesta = $TransaccionProducto->setRechazadaManualmente($TransaccionProducto->transproductoId, "M", "R", $comentario, "{}", "");
            }


            break;
    }

    /* Inicializa una respuesta sin errores, con tipo y mensaje de alerta vacíos. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
