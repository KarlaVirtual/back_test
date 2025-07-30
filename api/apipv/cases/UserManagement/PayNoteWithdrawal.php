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
use Backend\dto\Template;
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
use Dompdf\Dompdf;

/**
 * Realizar pago de una nota de retiro de un usuario.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param float $param->Amount Monto de la nota de retiro.
 * @param string $param->IdNota Identificador de la nota de retiro.
 * @param string $param->Clave Clave de autorización.
 * @param string $param->Description Descripción de la operación.
 *
 * @return array $response Respuesta de la operación:
 *  - HasError (boolean): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta (success, danger, etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - url (string): URL relacionada con la operación.
 *  - success (string): Indica el éxito de la operación.
 *  - Pdf (string): Documento PDF en base64.
 *  - PdfPOS (string): Documento PDF para impresión en base64.
 *
 * @throws Exception Si los parámetros enviados son incorrectos o si ocurre un error en la operación.
 */


/* crea objetos de usuario y asigna valores de parámetros. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

$Amount = $params->Amount;
//$Amount = -$Amount;
$Id = $params->IdNota;

/* Asignación de parámetros y creación de objeto ConfigurationEnvironment en un contexto específico. */
$Clave = $params->Clave;
$Description = $params->Description;
$tipo = 'E';

$ConfigurationEnvironment = new ConfigurationEnvironment();


/* Depura y valida variables; lanza excepción si están vacías. */
$Id = $ConfigurationEnvironment->DepurarCaracteres($Id);
$Clave = $ConfigurationEnvironment->DepurarCaracteres($Clave);

if ($Id == "" || $Clave == "") {
    throw new Exception("Error en los parametros enviados", "100001");
}


/* Se crean instancias de CuentaCobro y Usuario utilizando el ID correspondiente. */
$CuentaCobro = new CuentaCobro($Id, "", $Clave);
$Usuario = new  Usuario($CuentaCobro->getUsuarioId());


try {
    $Clasificador = new Clasificador('', 'CONTINGENCIARETIROSRETAIL');
    $UsuarioConfiguracion = new UsuarioConfiguracion($CuentaCobro->getUsuarioId(), 'A', $Clasificador->getClasificadorId());
    $IsActivateContingencyRetailWithdrawals = $UsuarioConfiguracion->getEstado();

    // Verificación de contingencia de retiros retail
    if($IsActivateContingencyRetailWithdrawals == 'A') {
        throw new Exception("Este usuario no puede usar puntos de venta o red aliadas, comuníquese con soporte.", 300152);
    }
} catch (Exception $e) {
    if($e->getCode() == 300152) throw $e;
}

if ($Usuario->paisId == $UsuarioPuntoVenta->paisId && $Usuario->mandante == $UsuarioPuntoVenta->mandante) {



    /* valida permisos para operaciones de puntos de venta y lanza excepciones si es necesario. */
    if ($CuentaCobro->version == '2') {
        throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

    }
    $UsuarioPuntoVenta2 = new Usuario($UsuarioPuntoVenta->puntoventaId);

    if ($UsuarioPuntoVenta2->contingenciaRetiro == 'A') {
        throw new Exception('Este punto de venta no tiene autorizado hacer notas de retiro', '110005');
    }


    /* verifica autorizaciones basadas en usuario y horarios específicos. */
    if (in_array($UsuarioPuntoVenta->usuarioId, array(1211624, 693978, 1311554, 853460, 1784692, 1022205))) {
        throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

    }

    if ($UsuarioPuntoVenta->paisId == '173' && $UsuarioPuntoVenta->mandante == '0' && ((date('H:i:s') >= '22:00:00' && date('H:i:s') <= '23:59:59') || (date('H:i:s') >= '00:00:00' && date('H:i:s') <= '06:59:59'))) {
// throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");
    }


    /* Verifica condiciones de usuario y hora, lanzando excepciones si no se cumplen. */
    if ($UsuarioPuntoVenta->mandante == '8' && $UsuarioPuntoVenta->mandante == '8' && ((date('H:i:s') >= '22:00:00' && date('H:i:s') <= '23:59:59') || (date('H:i:s') >= '00:00:00' && date('H:i:s') <= '06:59:59'))) {
        throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");
    }

    if ($Usuario->estado != "A") {
        throw new Exception("Error en los parametros enviados", "100001");
    }


    /* verifica condiciones y lanza excepciones si no se cumplen, indicando errores. */
    if ($Usuario->contingencia == "A") {
        throw new Exception("Error en los parametros enviados", "100001");
    }

    if ($CuentaCobro->getEstado() != "A") {
        throw new Exception("Error en los parametros enviados", "100001");
    }


    /* Validación de parámetros de pago con excepciones para errores en los datos. */
    if ($CuentaCobro->getMediopagoId() != "0" && $CuentaCobro->getMediopagoId() == "2") {
        if ($CuentaCobro->getMediopagoId() != $UsuarioPuntoVenta->usuarioId) {
            throw new Exception("Error en los parametros enviados", "100001");
        }
    } else {
        if ($CuentaCobro->getMediopagoId() != "0") {
            throw new Exception("Error en los parametros enviados", "100001");
        }

    }

    /* establece fechas actuales si están vacías o son inválidas. */
    if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
        $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
    }

    if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
        $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
    }


    /* Valida que el valor de pago no exceda el límite configurado para el punto de venta. */
    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);
    $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

    if ($UsuarioConfig->maxpagoRetiro != "" && $UsuarioConfig->maxpagoRetiro != "0") {
        if (floatval($UsuarioConfig->maxpagoRetiro) < floatval($CuentaCobro->getValor())) {
            throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
        }
    }

    /* Validación de pago en notas de retiro según configuración del usuario. */
    if ($UsuarioPuntoVenta->usuarioId != $UsuarioPuntoVenta->puntoventaId) {

        $UsuarioConfigUsuario = new UsuarioConfig($UsuarioPuntoVenta->usuarioId);

        if ($UsuarioConfigUsuario->maxpagoRetiro != "" && $UsuarioConfigUsuario->maxpagoRetiro != "0") {
            if (floatval($UsuarioConfigUsuario->maxpagoRetiro) < floatval($CuentaCobro->getValor())) {
                throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
            }
        }
    }
//verificamos si tiene limite de retiros diario configuardo

    /* inicializa un clasificador y obtiene límites de retiros de usuario. */
    $Clasificador = new Clasificador("", "DAYLILIMITPV");
    try {
        $UsuarioMandante = new UsuarioMandante("", $_SESSION['usuario'], $_SESSION['mandante']);
        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), 'A', $Clasificador->getClasificadorId(), 0);
        $limitWithdraws = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, captura de errores sin realizar acciones adicionales. */

    }

    /* verifica límites de retiros diarios según configuraciones de usuario y condiciones. */
    if ($UsuarioConfiguracion) {
        $BonoInterno = new BonoInterno();
        $sqlSum = "SELECT SUM(valor) AS suma_del_dia
        FROM cuenta_cobro
        WHERE DATE(fecha_pago) = CURDATE()
        AND puntoventa_id = {$UsuarioMandante->getUsuarioMandante()}";

        $sumDailyWithdraw = $BonoInterno->execQuery('', $sqlSum);
        if (floatval($sumDailyWithdraw[0]->{".suma_del_dia"}) + $CuentaCobro->getValor() > floatval($limitWithdraws)) {
            throw new Exception("Limite de retiros diario para punto de venta superado", 300019);
        }
    }

//Verificando límite máximo valor en retiros diarios para usuarios online

    /* Verifica si un usuario supera el límite de retiro diario y lanza excepción. */
    $currentDate = date('Y-m-d');
    $booleanMaxAmountUserWithdrawal = $CuentaCobro->usuarioSuperaMaximoMontoRetiroDiario($Usuario->usuarioId, $CuentaCobro->valor, $currentDate);
    if ($booleanMaxAmountUserWithdrawal) throw new Exception('Valor límite en retiros alcanzado', 300034);

    $rowsUpdate = 0;


    /* Se establece un entorno de configuración y se obtiene la IP del cliente. */
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
    $Transaction = $CuentaCobroMySqlDAO->getTransaction();
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

    $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);

    /* Configura propiedades de un objeto CuentaCobro con datos específicos de transacción. */
    $CuentaCobro->setDiripCambio($dirIp);

    $CuentaCobro->setEstado('I');
    $CuentaCobro->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
    $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
    $CuentaCobro->setObservacion($Description);


    /* Actualiza el estado de CuentaCobro y maneja errores en el proceso. */
    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='A' ");

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }

    $rowsUpdate = 0;

    /* Crea un flujo de caja registrando fecha, hora, usuario y tipo de movimiento. */
    $valor = $CuentaCobro->getValorAPagar();
    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
    $FlujoCaja->setTipomovId('S');

    /* Se establecen valores de un objeto FlujoCaja relacionado con CuentaCobro. */
    $FlujoCaja->setValor($valor);
    $FlujoCaja->setTicketId('');
    $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
    $FlujoCaja->setMandante($CuentaCobro->getMandante());
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setRecargaId(0);


    /* Establece el ID de forma de pago en 0 si está vacío. */
    if ($FlujoCaja->getFormapago1Id() == "") {
        $FlujoCaja->setFormapago1Id(0);
    }

    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }


    /* Asigna valores cero a propiedades vacías de un objeto FlujoCaja. */
    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }

    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }


    /* Asigna valores predeterminados a propiedades vacías de objeto FlujoCaja. */
    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }

    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }


    /* asigna valor IVA y crea una instancia de FlujoCajaMySqlDAO. */
    if ($FlujoCaja->getValorIva() == "") {
        $FlujoCaja->setValorIva(0);
    }
    $FlujoCaja->setDevolucion('');

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

    /* inserta datos y verifica errores en la operación. */
    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }

    $rowsUpdate = 0;

    /* Verifica el perfil de sesión y actualiza el balance si coincide. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3" or $_SESSION["win_perfil"] == "PUNTOVENTA" or $_SESSION["win_perfil"] == "CAJERO") {

        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($CuentaCobro->getValor(), $Transaction);

    }

    if ($rowsUpdate > 0) {


        /* Crea un historial de usuario con datos específicos de un punto de venta. */
        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);

        /* Se registra un historial de usuario con datos de una cuenta de cobro. */
        $UsuarioHistorial->setTipo(40);
        $UsuarioHistorial->setValor($CuentaCobro->getValor());
        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');



        /* Finaliza y guarda los cambios realizados en una transacción de base de datos. */
        $Transaction->commit();

        try {


            /* obtiene información del servidor y la codifica en base64. */
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);

            $ismobile = '';


            /* detecta si el usuario utiliza un dispositivo móvil mediante expresiones regulares. */
            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

                $ismobile = '1';

            }
//Detect special conditions devices
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");

            /* detecta dispositivos móviles a partir del user agent del navegador. */
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

//do something with this information
            if ($iPod || $iPhone) {
                $ismobile = '1';
            } else if ($iPad) {
                /* verifica si el dispositivo es un iPad y establece una variable móvil. */

                $ismobile = '1';
            } else if ($Android) {
                /* verifica si la variable $Android es verdadera y asigna '1' a $ismobile. */

                $ismobile = '1';
            }

//exec("php -f " . __DIR__ . "/../../../src/integrations/crm/AgregarCrm.php " . $CuentaCobro->usuarioId . " " . "RETIROPAGADOCRM" . " " . $CuentaCobro->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");
        }catch (Exception $e){
            /* Manejo de excepciones en PHP, captura errores sin realizar ninguna acción. */
        }

    } else {
        /* Lanza una excepción con mensaje "Error General" y código "100000" en caso de error. */

        throw new Exception("Error General", "100000");
    }

    /* Ejecuta un script PHP si el entorno es de desarrollo, gestionando lealtad. */
    if ($ConfigurationEnvironment->isDevelopment()) {
        exec("php -f " . __DIR__ . "/../../../src/integrations/casino" . "/AsignarPuntosLealtad.php " . "RETIRO" . " " . $CuentaCobro->getCuentaId() . " " . 41 . " > /dev/null &");
    }

    $Mandante = new Mandante($Usuario->mandante);
    $pdf = '<head>
    <style>
        body {
            font-family: \'Roboto\', sans-serif;
            text-decoration: none;
            font-size: 14px;
        }

        tr td:first-child {
            text-align: left;
        }

        tr td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>
<div style="width:330px; border:1px solid grey; padding: 15px;">
    <table style="width:100%;height: 355px;">
        <tbody>';


    /* Condicional que añade texto a un PDF si cumplen ciertas condiciones específicas. */
    if ($Mandante->mandante == 6 && $UsuarioPuntoVenta->puntoventaId != 157933) {

        $pdf = $pdf . ' <tr>
            <td colspan="2" style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">PERMISO SEGOB 8.S.7.1/DGG/SN/94, OFICIO DE AUTORIZACION No. DGJS/0223/2020 DE FECHA 12 DE MARZO DE 2020</font>
            </td>
        </tr> ';
    }
    $pdf = $pdf . '   <tr style="width: 50%; display: inline-block;">
            <td align="center" valign="top"><img style="width: 140px; padding-left: 20px;"
                                                 src="' . $Mandante->logoPdf . '" alt="logo">
            </td>
            <td align="center" valign="top" style="display: block;text-align:center;"><font
                        style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE<br>PAGO RETIRO</font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Retiro No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $CuentaCobro->getCuentaId() . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha de Pago:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $CuentaCobro->getFechaPago() . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Punto de Venta:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioPuntoVenta->nombre . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->usuarioId . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->nombre . '</font>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($CuentaCobro->getValor(), '2', ',', '.') . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($CuentaCobro->getImpuesto(), '2', ',', '.') . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($CuentaCobro->getValor(), '2', ',', '.') . '</font></td>
        </tr>';


    /* Genera un recibo PDF específico si el mandante es igual a 6. */
    if ($Mandante->mandante == 6) {

        $pdf = $pdf . ' <tr>
            <td colspan="2" style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">RECIBO DE NETABET SA DE CV LA CANTIDAD INDICADA, POR CONCEPTO DE PAGO DE NOTA DE RETIRO.</font>
            </td>
        </tr><tr>
            <td colspan="2" style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">FIRMA: </font>
            </td>
        </tr> ';
    }



    /* Añade una fila vacía a un documento PDF en formato HTML. */
    $pdf = $pdf . '
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
                </tbody>
    </table>';


    if ($PuntoVenta->footerRecibopagoretiro != '') {

        $footerRecibopagoretiro = $PuntoVenta->footerRecibopagoretiro;
        $pdf .= '
        <div style="text-align:center;font-size:12px;">' . nl2br($footerRecibopagoretiro) . '</div>';

    }
    if ($Usuario->paisId == 2 && $Usuario->mandante == '0') {
        $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
        $totalvalue2 = ($CuentaCobro->getValor() * $PaisMandante->trmNio);

        $pdf = '<head>
    <style>
        body {
            font-family: \'Roboto\', sans-serif;
            text-decoration: none;
            font-size: 14px;
        }

        tr td:first-child {
            text-align: left;
        }

        tr td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>
<div style="width:330px; border:1px solid grey; padding: 15px;">
    <table style="width:100%;height: 355px;">
        <tbody>';

        if ($Mandante->mandante == 6 && $UsuarioPuntoVenta->puntoventaId != 157933) {

            $pdf = $pdf . ' <tr>
            <td colspan="2" style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">PERMISO SEGOB 8.S.7.1/DGG/SN/94, OFICIO DE AUTORIZACION No. DGJS/0223/2020 DE FECHA 12 DE MARZO DE 2020</font>
            </td>
        </tr> ';
        }
        $pdf = $pdf . '   <tr style="width: 50%; display: inline-block;">
            <td align="center" valign="top"><img style="width: 140px; padding-left: 20px;"
                                                 src="' . $Mandante->logoPdf . '" alt="logo">
            </td>
            <td align="center" valign="top" style="display: block;text-align:center;"><font
                        style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE<br>PAGO RETIRO</font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Retiro No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $CuentaCobro->getCuentaId() . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha de Pago:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $CuentaCobro->getFechaPago() . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Punto de Venta:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioPuntoVenta->nombre . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->usuarioId . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->nombre . '</font>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($CuentaCobro->getValor(), '2', ',', '.') . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($CuentaCobro->getImpuesto(), '2', ',', '.') . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($CuentaCobro->getValor(), '2', ',', '.') . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">NIO ' . number_format($totalvalue2, '2', ',', '.') . '</font></td>
        </tr>';

        if ($Mandante->mandante == 6) {

            $pdf = $pdf . ' <tr>
            <td colspan="2" style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">RECIBO DE NETABET SA DE CV LA CANTIDAD INDICADA, POR CONCEPTO DE PAGO DE NOTA DE RETIRO.</font>
            </td>
        </tr><tr>
            <td colspan="2" style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">FIRMA: </font>
            </td>
        </tr> ';
        }


        $pdf = $pdf . '
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
                </tbody>
    </table>';

    }
    $pdf = $pdf . '
 <div style="text-align:center;font-size:12px;">' . $Mandante->descripcion . '</font>
        </div>
        ';


    if ($Usuario->paisId == 173 && $Usuario->mandante == '0') {
        $pdf .= '
        <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
        </div>';

    }

    $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
      <div class="barcodecell" style="  text-align: center;"><barcode code="' . $CuentaCobro->getCuentaId() . '" type="I25" class="barcode" /></div>
  </div>
    </div>
</div>
</body>
';

    //  require_once __DIR__ . "/../../mpdf6.1/mpdf.php";

    $mpdf = new \Mpdf\Mpdf(['format' => array(80, 200), 'tempDir' => '/tmp']);
    //$mpdf = new mPDF('c', array(80, 200), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

    $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

    $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($pdf);

    $mpdf->Output('/tmp' . "/mpdfPNW" . $Id . ".pdf", "F");

    $path = '/tmp' . '/mpdfPNW' . $Id . '.pdf';

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

    $response["Pdf"] = base64_encode($data);
    $response["PdfPOS"] = base64_encode($data);

    if ($UsuarioPuntoVenta->usuarioId == 6290 || $UsuarioPuntoVenta->mandante == 2 || $UsuarioPuntoVenta->mandante == 1 || $UsuarioPuntoVenta->mandante == 18) {

        try {

            /* Se crea un clasificador y un template para generar código HTML de barras. */
            $Clasificador = new Clasificador("", "TEMREPANORE");

            $Template = new Template('', $UsuarioPuntoVenta->mandante, $Clasificador->clasificadorId, $UsuarioPuntoVenta->paisId, strtolower($UsuarioPuntoVenta->idioma));
            $html_barcode = $Template->templateHtml;
            $html_barcode .= $Template->templateHtmlCSSPrint;
            if ($html_barcode != '') {


                /* Reemplaza marcadores en HTML con datos del usuario y detalles de la transacción. */
                $html_barcode = str_replace("#idpointsale#", $UsuarioPuntoVenta->usuarioId, $html_barcode);

                $html_barcode = str_replace("#namepointsale#", $UsuarioPuntoVenta->nombre, $html_barcode);

                $html_barcode = str_replace("#value#", $Usuario->moneda . ' ' . number_format($CuentaCobro->getValor(), '2', ',', '.'), $html_barcode);

                $html_barcode = str_replace("#userid#", $Usuario->usuarioId, $html_barcode);

                /* reemplaza marcadores en un HTML con datos de usuario y cuenta. */
                $html_barcode = str_replace("#name#", $Usuario->nombre, $html_barcode);

                $html_barcode = str_replace("#creationdate#", $CuentaCobro->getFechaPago(), $html_barcode);
                $html_barcode = str_replace("#withdrawalnotenumber#", $CuentaCobro->getCuentaId(), $html_barcode);
                $html_barcode = str_replace("#tax#", $Usuario->moneda . ' ' . number_format($CuentaCobro->getImpuesto(), '2', ',', '.'), $html_barcode);

                /* reemplaza valores en un HTML con datos de usuario y cuenta. */
                $html_barcode = str_replace("#totalvalue#", $Usuario->moneda . ' ' . number_format(($CuentaCobro->getValor() - $CuentaCobro->getImpuesto()), '2', ',', '.'), $html_barcode);
                if ($Usuario->mandante == 0 && $Usuario->paisId == 2) {
                    $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
                    $totalvalue2 = (($CuentaCobro->getValor() - $CuentaCobro->getImpuesto()) * $PaisMandante->trmNio);
                    $html_barcode = str_replace("#totalvalue2#", number_format(($totalvalue2), '2', ',', '.'), $html_barcode);
                }

                /* Se genera un PDF a partir de un código HTML con Dompdf. */
                $pdf = $html_barcode;
                // instantiate and use the dompdf class
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html_barcode);

                // (Optional) Setup the paper size and orientation
                $width = 80; //mm!

                /* convierte milímetros a puntos y configura un formato de papel para PDF. */
                $height = 150; //mm!

                //convert mm to points
                $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);
                $dompdf->setPaper($paper_format);

                // Render the HTML as PDF
                $dompdf->render();

                // Output the generated PDF to Browser



                /* Genera un PDF y lo codifica en base64 para enviarlo como respuesta. */
                $data = $dompdf->output();

                $base64 = 'data:application/pdf;base64,' . base64_encode($data);

                $response["Pdf"] = base64_encode($data);
                $response["Pdf2"] = $pdf;

                /* codifica datos en formato base64 y lo almacena en un arreglo. */
                $response["PdfPOS"] = base64_encode($data);

            }
        } catch (Exception $e) {
            /* Bloque de captura de excepciones en lenguaje PHP para manejar errores. */


        }
    }

} else {
    throw new Exception("Error General", "100000");
}


/* Envía un mensaje a Slack si se cumplen ciertas condiciones del usuario y cuenta. */
if ($Usuario->paisId == 173 && $Usuario->mandante == '0' && floatval($CuentaCobro->getValor()) >= 3000) {
    try {

        $message = '*Nota de Retiro Pagada:* - *Usuario:*' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $CuentaCobro->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-peru' > /dev/null & ");
    } catch (Exception $e) {

    }
}


/* Envía un mensaje de Slack si se cumplen ciertas condiciones para usuarios específicos. */
if ($Usuario->paisId == 173 && $Usuario->mandante == '0' && floatval($CuentaCobro->getValor()) >= 500 && $PuntoVenta->propio != 'S') {
    try {

        $message = '*Nota de Retiro Pagada Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $CuentaCobro->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-peru' > /dev/null & ");
    } catch (Exception $e) {

    }
}


/* Envía un mensaje a Slack si se cumplen ciertas condiciones del usuario y la cuenta. */
if ($Usuario->paisId == 66 && $Usuario->mandante == '8' && floatval($CuentaCobro->getValor()) >= 125 && $PuntoVenta->propio != 'S') {
    try {

        $message = '*Nota de Retiro Pagada Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $CuentaCobro->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-ecuabet' > /dev/null & ");
    } catch (Exception $e) {

    }
}



/* Envía un mensaje a Slack si se cumplen ciertas condiciones del usuario y cuenta. */
if ($Usuario->paisId == 60 && $Usuario->mandante == '0' && floatval($CuentaCobro->getValor()) >= 80300 && $PuntoVenta->propio != 'S') {
    try {

        $message = '*Nota de Retiro Pagada Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $CuentaCobro->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-costarica' > /dev/null & ");
    } catch (Exception $e) {

    }
}



/* Envía un mensaje a Slack si se cumplen ciertas condiciones de usuario y cuenta. */
if ($Usuario->paisId == 146 && floatval($CuentaCobro->getValor()) >= 2578 && $PuntoVenta->propio != 'S') {
    try {

        $message = '*Nota de Retiro Pagada Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $CuentaCobro->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-netabet' > /dev/null & ");
    } catch (Exception $e) {

    }
}



/* Envía una notificación a Slack si se cumplen ciertas condiciones del usuario y cuenta. */
if ($Usuario->paisId == 46 && $Usuario->mandante == '0' && floatval($CuentaCobro->getValor()) >= 110000 && $PuntoVenta->propio != 'S') {
    try {

        $message = '*Nota de Retiro Pagada Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $CuentaCobro->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-chile' > /dev/null & ");
    } catch (Exception $e) {

    }
}


/* Envía un mensaje de pago exitoso si se cumplen ciertas condiciones del usuario. */
try {
    if ($Usuario->test == 'S' && $Usuario->mandante == '0' && $Usuario->paisId == '94' && false) {
        $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
        $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
        $mensaje_txt = $Mandante->nombre . ' ha pagado exitosamente su notas de retiro por valor ' . $CuentaCobro->valor . ' a las ' . $CuentaCobro->fechaPago . '. ID ' . $CuentaCobro->cuentaId;
        $Registro = new Registro('', $Usuario->usuarioId);

        $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
//Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), $UsuarioMandante->mandante, $UsuarioMandante);
    }

} catch (Exception $e) {
    /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir el flujo del programa. */


}



/* define una respuesta exitosa para una operación en un sistema. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];
