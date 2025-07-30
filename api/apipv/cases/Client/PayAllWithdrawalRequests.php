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
 * Client/PayAllWithdrawalRequests
 *
 * Este script procesa solicitudes de retiro para un cliente, cambiando su estado y registrando la información correspondiente.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param array $params ->Ids Lista de IDs de las solicitudes de retiro a procesar.
 * @param string $params ->VoucherNumber Número de comprobante asociado al pago.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos adicionales.
 *
 * @throws Exception Si el usuario no tiene permisos para ejecutar esta acción.
 * @throws Exception Si se intenta cambiar el estado de un retiro ya procesado.
 */


/* Se verifica permiso de usuario y se lanzan excepciones si no está autorizado. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
if (!$ConfigurationEnvironment->checkUserPermission('Client/PayAllWithdrawalRequests', $_SESSION['win_perfil'], $_SESSION['usuario'])) {
    throw new Exception('Permiso denegado', 100035);
}


$Ids = $params->Ids;


/* verifica y asigna un número de voucher, controlando acceso para usuarios. */
$VoucherNumber = $params->VoucherNumber;
if ($VoucherNumber == '') {
    $VoucherNumber = $_REQUEST["VoucherNumber"];

}

if ($_SESSION['usuario2'] == 0) {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro.';
    $response["ModelErrors"] = [];
    $response["Data"] = [];
} else {

    foreach ($Ids as $Id) {


        /* Se crea una nueva instancia de la clase CuentaCobro utilizando un identificador específico. */
        $CuentaCobro = new CuentaCobro($Id);


        if ($CuentaCobro->getEstado() == "P") {


            /* Actualiza el estado y datos de pago en una cuenta de cobro. */
            $CuentaCobro->setEstado('I');
            $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
            $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

            if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                $CuentaCobro->usucambioId = 0;
            }

            /* asigna 0 a usupagoId y usurechazaId si están vacíos o nulos. */
            if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                $CuentaCobro->usupagoId = 0;
            }
            if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                $CuentaCobro->usurechazaId = 0;
            }

            /* Asigna la fecha actual si las fechas son vacías o nulas. */
            if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
            }

            if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
            }


            /* Condiciona la adición del voucher a las observaciones de CuentaCobro si no está vacío. */
            if ($VoucherNumber != '') {
                $CuentaCobro->observacion = $CuentaCobro->observacion . ' ' . $VoucherNumber;

            }

            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

            /* Actualiza un registro y ejecuta un script en entorno de desarrollo si corresponde. */
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();


            $ConfigurationEnvironment = new ConfigurationEnvironment();
            if ($ConfigurationEnvironment->isDevelopment()) {
                exec("php -f " . __DIR__ . "/../../../src/integrations/casino" . "/AsignarPuntosLealtad.php " . "RETIRO" . " " . $CuentaCobro->getCuentaId() . " " . 41 . " > /dev/null &");
            }

            try {


                /* recoge información del servidor y la codifica en base64. */
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                $jsonServer = json_encode($_SERVER);
                $serverCodif = base64_encode($jsonServer);


                $ismobile = '';


                /* verifica si un dispositivo es móvil utilizando expresiones regulares en PHP. */
                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

                    $ismobile = '1';

                }
//Detect special conditions devices
                $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");

                /* Detecta si el dispositivo es iPhone, iPad, Android o webOS. */
                $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
                if ($iPod || $iPhone) {
                    $ismobile = '1';
                } else if ($iPad) {
                    /* La condición verifica si es un iPad y establece una variable indicando que es móvil. */

                    $ismobile = '1';
                } else if ($Android) {
                    /* Verifica si es un dispositivo Android y asigna '1' a $ismobile. */

                    $ismobile = '1';
                }
                //exec("php -f ". __DIR__ ."/../../../src/integrations/crm/AgregarCrm.php " . $CuentaCobro->usuarioId . " " . "RETIROPAGADOCRM" . " " . $CuentaCobro->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, captura errores sin procesar en el bloque. */


            }


            /* Código inicializa una respuesta sin errores, indicando éxito y sin mensajes adicionales. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = '';
            $response["ModelErrors"] = [];
            $response["Data"] = [];
        } else {
            /* maneja un error al intentar cambiar el estado de un retiro procesado. */

            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
            $response["ModelErrors"] = [];
            $response["Data"] = [];

        }
    }
}

