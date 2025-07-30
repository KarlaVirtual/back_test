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
 * Bonus/AddBonusBalance
 *
 * Este script agrega un saldo de bono específico a un usuario directamente.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param float $params ->Value Monto del bono.
 * @param string $params ->Reference Referencia o descripción del bono.
 * @param int $params ->TypeBalance Tipo de saldo (0 para torneo casino, 1 para torneo deportivas).
 * @param int $params ->Type Tipo de bono (0-7).
 * @param int $params ->UserId Identificador del usuario.
 * @param string $params ->codeQR Código QR para autenticación.
 *
 *
 * @return array Respuesta en formato JSON con los siguientes campos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., 'success', 'danger').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Resultado de la operación.
 *
 * @throws Exception Si ocurre un error durante la ejecución, si los parámetros son inválidos o si la autenticación falla.
 */


/* valida un código QR y lanza una excepción si está vacío. */
$Amount = $params->Value;
$Description = $params->Reference;
$TypeBalance = $params->TypeBalance;

$codeQR = $params->codeQR;
if ($codeQR == '') {
    throw new Exception("Inusual Detected", "110012");
} else {
    /* Verifica el código de autenticación de Google y maneja excepciones si es inválido. */

    $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);
    $Usuario2 = new Usuario($UsuarioMandante2->usuarioMandante);
    $Google = new GoogleAuthenticator();
    $returnCodeGoogle = $Google->verifyCode($Usuario2->saltGoogle, $codeQR);
    if (!$returnCodeGoogle) {
        throw new Exception("Inusual Detected", "11");
    }
}


/*
 * 0 es torneo casino
 * 1 es torneo deportivas
 */

/* Verifica el valor de $TypeBalance y ajusta la variable $seguir según condiciones. */
$Type = $params->Type;
$UserId = $params->UserId;

$seguir = true;

if ($TypeBalance != 0 && $TypeBalance != 1) {
    $seguir = false;
}


/* valida el tipo y la descripción, permitiendo continuar solo si son válidos. */
if ($Type != 0 && $Type != 1 && $Type != 2 && $Type != 3 && $Type != 4 && $Type != 5 && $Type != 6 && $Type != 7) {
    $seguir = false;
}

if ($Description == "") {
    $seguir = false;
}


/* Verifica si $UserId no es un número; si es así, asigna false a $seguir. */
if (!is_numeric($UserId)) {
    $seguir = false;
}


if ($seguir) {


    /* asigna etiquetas "TC" o "TD" según el valor de $Type. */
    if ($Type == 0) {
        $Type = "TC";
    }

    if ($Type == 1) {
        $Type = "TD";
    }


    /* asigna nombres a tipos basados en su valor numérico. */
    if ($Type == 2) {
        $Type = "TV";
    }

    if ($Type == 3) {
        $Type = "TL";
    }


    /* transforma los valores de $Type en letras específicas según condiciones. */
    if ($Type == 4) {
        $Type = "S";
    }


    if ($Type == 5) {
        $Type = "SC";
    }


    /* asigna alias "SV" y "SL" a los valores 6 y 7 respectivamente. */
    if ($Type == 6) {
        $Type = "SV";
    }


    if ($Type == 7) {
        $Type = "SL";
    }


    /* Se crean instancias de usuario y registro utilizando datos de sesión y un identificador. */
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    $tipo = 'E';

    $Usuario = new Usuario($UserId);
    $Registro = new Registro('', $UserId);


    /* Se crea un registro de bono con usuario, tipo, monto, fecha y estado. */
    $BonoLog = new BonoLog();
    $BonoLog->setUsuarioId($Usuario->usuarioId);
    $BonoLog->setTipo($Type);
    $BonoLog->setValor($Amount);
    $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
    $BonoLog->setEstado('L');

    /* configura propiedades de un objeto BonoLog con diferentes valores. */
    $BonoLog->setErrorId(0);
    $BonoLog->setIdExterno($Description);
    $BonoLog->setMandante($Usuario->mandante);
    $BonoLog->setFechaCierre('');
    $BonoLog->setTransaccionId('');
    $BonoLog->setTipobonoId(4);

    /* Se configura un tipo de saldo y se obtiene una transacción de la base de datos. */
    $BonoLog->setTiposaldoId($TypeBalance);


    $BonoLogMySqlDAO = new BonoLogMySqlDAO();

    $Transaction = $BonoLogMySqlDAO->getTransaction();


    /* Inserts BonoLog and credits user if TypeBalance equals zero. */
    $bonologId = $BonoLogMySqlDAO->insert($BonoLog);


    if ($TypeBalance == 0) {

        $Usuario->credit($Amount, $Transaction);

    } elseif ($TypeBalance == 1) {
        /* Condicional que acredita una ganancia al usuario si cumple con el tipo de balance especificado. */

        $Usuario->creditWin($Amount, $Transaction);

    }


    /* Crea un historial de usuario con información del usuario y tipo de movimiento. */
    $UsuarioHistorial = new UsuarioHistorial();
    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
    $UsuarioHistorial->setDescripcion('');
    $UsuarioHistorial->setMovimiento($tipo);
    $UsuarioHistorial->setUsucreaId($UsuarioMandante->usuarioMandante);
    $UsuarioHistorial->setUsumodifId(0);

    /* Se establece un historial de usuario y se inserta en la base de datos. */
    $UsuarioHistorial->setTipo(50);
    $UsuarioHistorial->setValor($Amount);
    $UsuarioHistorial->setExternoId($bonologId);

    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


    /* confirma una transacción y prepara una respuesta sin errores. */
    $Transaction->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    /* Inicializa un array vacío llamado "Result" dentro del array "$response". */
    $response["Result"] = array();


} else {
    /* Manejo de errores, estableciendo respuesta negativa y mensaje de alerta vacío. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();

}
