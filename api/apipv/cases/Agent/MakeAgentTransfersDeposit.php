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
 * Agent/MakeAgentTransfersDeposit
 *
 * Asignar cupo a los usuarios de una red al saldo de depósitos.
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud.
 * @param int $params ->Id Identificador del usuario.
 * @param float $params ->Amount Monto a transferir.
 * @param int $params ->Type Tipo de transferencia (0 o 1).
 *
 * @return array Respuesta de la operación.
 *  - HasError (bool) Indica si hubo un error.
 *  - AlertType (string) Tipo de alerta.
 *  - AlertMessage (string) Mensaje de alerta.
 *  - ModelErrors (array) Errores del modelo.
 *
 * @throws Exception Si se detecta un perfil inusual.
 * @throws Exception Si no se puede transferir a un usuario específico.
 */


/* Se inicializa un objeto UsuarioMandante y se definen variables de parámetros. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


$Id = $params->Id;
$Amount = $params->Amount;
$Type = ($params->Type != 0) ? 1 : 0;


/* inicia variables y obtiene datos de un objeto UsuarioPerfil. */
$Note = "";
$tipo = 'E';

$UsuarioPerfil = new UsuarioPerfil($Id);


$userfrom = $UsuarioMandante->getUsuarioMandante();

/* asigna un tipo y convierte cantidades negativas a positivas. */
$userto = $Id;

if ($Amount < 0) {
    $tipo = 'S';
    $Amount = -$Amount;
}


/* Se crea un registro de log con información del usuario y transacción. */
$CupoLog = new CupoLog();
$CupoLog->setUsuarioId($userto);
$CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
$CupoLog->setTipoId($tipo);
$CupoLog->setValor($Amount);
$CupoLog->setUsucreaId($userfrom);

/* Configura un registro de cupo y obtiene la transacción desde la base de datos. */
$CupoLog->setMandante(0);
$CupoLog->setTipocupoId('T');
$CupoLog->setObservacion($Note);

$CupoLogMySqlDAO = new CupoLogMySqlDAO();
$Transaction = $CupoLogMySqlDAO->getTransaction();

/* Código que gestiona una transacción y lanza excepción si el perfil de usuario es específico. */
$PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

$CupoLogMySqlDAO->insert($CupoLog);


if ($_SESSION["win_perfil"] == "PUNTOVENTA" or $_SESSION["win_perfil"] == "USUONLINE" or $_SESSION["win_perfil"] == "CAJERO") {
    throw new Exception("Inusual Detected", "11");
}


/* Se verifica la transferencia de concesionario y se lanza una excepción si no es válida. */
$ConcesionarioU = new Concesionario($userto, '0');

if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
    if ($ConcesionarioU->getUsupadreId() != $UsuarioMandante->getUsuarioMandante()) {
        throw new Exception("No puedo transferir a ese usuario", "111");

    }

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
    /* Verifica el perfil y lanza excepción si no concuerda el usuario padre. */

    if ($ConcesionarioU->getUsupadre2Id() != $UsuarioMandante->getUsuarioMandante()) {
        throw new Exception("No puedo transferir a ese usuario", "111");
    }

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
    /* Valida la transferencia de usuarios basada en condiciones específicas de sesión y permisos. */

    if ($ConcesionarioU->getUsupadre3Id() != $UsuarioMandante->getUsuarioMandante()) {
        throw new Exception("No puedo transferir a ese usuario", "111");
    }

}

if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {


    /* Se crea un objeto PuntoVenta y se establece el balance según el tipo y monto. */
    $PuntoVenta = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

    if ($tipo == "S") {
        if ($Type == 0) {
            $PuntoVenta->setBalanceCupoRecarga($Amount);
        }
        {
            $PuntoVenta->setBalanceCreditosBase($Amount);

        }

    } else {
        /* ajusta balances según el tipo y monto especificado. */

        if ($Type == 0) {
            $PuntoVenta->setBalanceCupoRecarga(-$Amount);
        }
        {
            $PuntoVenta->setBalanceCreditosBase(-$Amount);

        }
    }


    /* Actualiza la información del objeto $PuntoVenta en la base de datos. */
    $PuntoVenta->update($PuntoVenta);

}


/* ajusta el balance de un PuntoVenta según el tipo y monto especificado. */
$PuntoVenta = new PuntoVenta("", $Id);

if ($tipo == "S") {
    if ($Type == 0) {
        $PuntoVenta->setBalanceCupoRecarga(-$Amount);
    }
    {
        $PuntoVenta->setBalanceCreditosBase(-$Amount);

    }

} else {
    /* Condicional que establece balances según el tipo y monto especificado. */

    if ($Type == 0) {
        $PuntoVenta->setBalanceCupoRecarga($Amount);
    }
    {
        $PuntoVenta->setBalanceCreditosBase($Amount);

    }

}


/* Actualiza datos de Punto de Venta y registra historial de usuario relacionado. */
$PuntoVentaMySqlDAO->update($PuntoVenta);

$UsuarioHistorial = new UsuarioHistorial();
$UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
$UsuarioHistorial->setDescripcion('');
$UsuarioHistorial->setMovimiento($CupoLog->getTipoId());

/* Se inicializan propiedades del historial de usuario y se crea un DAO para MySQL. */
$UsuarioHistorial->setUsucreaId(0);
$UsuarioHistorial->setUsumodifId(0);
$UsuarioHistorial->setTipo(60);
$UsuarioHistorial->setValor($CupoLog->getValor());
$UsuarioHistorial->setExternoId($CupoLog->getCupologId());

$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);

/* Inserta un historial de usuario en la base de datos y confirma la transacción. */
$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


$Transaction->commit();


/* asigna un mensaje de éxito y errores vacíos a la respuesta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];