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
use Backend\dto\UsuarioOtrainfo;
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
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Aprobar log de usuario.
 *
 * Este script procesa la aprobación de un log de usuario, actualizando información
 * relacionada con el usuario y su configuración en la base de datos.
 *
 * @param $_REQUEST["id"] int ID del log de usuario a aprobar.
 * @param $_SESSION['usuario2'] int ID del usuario que aprueba el log.
 * @param $_SERVER['HTTP_X_FORWARDED_FOR'] string Dirección IP del usuario aprobador.
 *
 * @return array Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos adicionales (vacío en este caso).
 */


/* obtiene la IP del usuario y crea un objeto UsuarioLog con un ID. */
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


$Id = $_REQUEST["id"];


$UsuarioLog = new UsuarioLog($Id);


/* Inicializa objetos de Usuario y Registro, ajustando créditos si están vacíos. */
$Usuario = new Usuario($UsuarioLog->getUsuarioId());
$UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioLog->getUsuarioId());
$Registro = new Registro("", $UsuarioLog->getUsuarioId());
if ($Registro->getCreditosBase() == "") {
    $Registro->setCreditosBase(0);
}

/* inicializa variables en cero si están vacías. */
if ($Registro->getCreditos() == "") {
    $Registro->setCreditos(0);
}
if ($Usuario->intentos == "") {
    $Usuario->intentos = (0);
}

/* Asigna el valor 0 a propiedades vacías de un objeto Usuario. */
if ($Usuario->mandante == "") {
    $Usuario->mandante = (0);
}
if ($Usuario->usucreaId == "") {
    $Usuario->usucreaId = (0);
}

/* Asigna 0 a usumodifId y usuretiroId si están vacíos. */
if ($Usuario->usumodifId == "") {
    $Usuario->usumodifId = (0);
}
if ($Usuario->usuretiroId == "") {
    $Usuario->usuretiroId = (0);
}

/* Asigna 0 a sponsorId y tokenItainment si están vacíos en el objeto Usuario. */
if ($Usuario->sponsorId == "") {
    $Usuario->sponsorId = (0);
}
if ($Usuario->tokenItainment == "") {
    $Usuario->tokenItainment = (0);
}


/* establece el estado y usuario, luego inicia una transacción en la base de datos. */
$UsuarioLog->setEstado("A");
$UsuarioLog->setUsuarioaprobarId($_SESSION['usuario2']);
$UsuarioLog->setUsuarioaprobarIp($ip);

$UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
$Transaction = $UsuarioLogMySqlDAO->getTransaction();


/* Se obtiene el tipo de usuario y se clasifica si es numérico. */
$tipo = $UsuarioLog->getTipo();

if (is_numeric($tipo)) {
    $Clasificador = new Clasificador($tipo);
    $tipo = $Clasificador->getAbreviado();
}

switch ($tipo) {
    case "USUDIRECCION":
        /* Asigna una nueva dirección al registro basado en la información del usuario. */

        $Registro->setDireccion($UsuarioLog->getValorDespues());

        break;
    case "USUGENERO":
        /* asigna un valor de sexo al registro del usuario. */

        $Registro->setSexo($UsuarioLog->getValorDespues());

        break;

    case "USUTELEFONO":
        /* asigna un nuevo teléfono al registro del usuario logueado. */

        $Registro->setTelefono($UsuarioLog->getValorDespues());


        break;

    case "USUCELULAR":

        /* crea un objeto UsuarioMandante y configura un número de celular. */
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, "0");

        $Registro->setCelular($UsuarioLog->getValorDespues());

        /*

        $UsuarioToken2 = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

        $data = array(
            "7040" . $UsuarioToken2->getRequestId() . "5" => array(
                "notifications" => array(
                    array(
                        "type" => "notification",
                        "title" => "Notificacion",
                        "content" => "Se ha actualizado el celular correctamente.",
                        "action" => "slider.userDataConfirm"
                    )
                ),
            ),

        );

        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
        /* $WebsocketUsuario = new WebsocketUsuario($UsuarioToken2->getRequestId(), $data);
         $WebsocketUsuario->sendWSMessage();
*/

        break;


    case "USUEMAIL":
        /* Asigna un email al usuario usando un valor recuperado de $UsuarioLog. */

        $Usuario->login = $UsuarioLog->getValorDespues();
        $Registro->setEmail($UsuarioLog->getValorDespues());

        break;


    case "LIMITEDEPOSITOSIMPLE":

        /* intenta actualizar la configuración de un usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Se crea una nueva configuración de usuario si el código de error es 46. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* lanza una excepción si se cumple una condición específica. */

                throw $e;
            }
        }

        break;

    case "LIMITEDEPOSITODIARIO":

        /* Código que actualiza estado de configuración de usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* inserta una configuración de usuario si se cumple una condición específica. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* lanza una excepción si no se cumple una condición en el bloque anterior. */

                throw $e;
            }
        }

        break;

    case "LIMITEDEPOSITOSEMANA":

        /* actualiza la configuración de un usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Condicional que inserta una configuración de usuario si el código es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Lanza una excepción si no se cumplen ciertas condiciones en el bloque anterior. */

                throw $e;
            }
        }
        break;

    case "LIMITEDEPOSITOMENSUAL":

        /* Intenta actualizar la configuración de un usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta un nuevo registro de configuración de usuario si el código es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* lanza una excepción si no se cumple una condición previa. */

                throw $e;
            }
        }
        break;

    case "LIMITEDEPOSITOANUAL":

        /* Se actualiza la configuración del usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una configuración de usuario si el código de error es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Maneja una excepción lanzándola nuevamente si no se cumple una condición previa. */

                throw $e;
            }
        }
        break;


    case "LIMAPUDEPORTIVASIMPLE":

        /* Intenta actualizar la configuración de usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una nueva configuración de usuario basado en un código específico. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Captura una excepción y la vuelve a lanzar si no se cumple cierta condición. */

                throw $e;
            }
        }

        break;

    case "LIMAPUDEPORTIVADIARIO":

        /* intenta actualizar una configuración de usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Crea y guarda una nueva configuración de usuario cuando el código es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* lanza una excepción si se cumple una condición determinada en el contexto. */

                throw $e;
            }
        }

        break;

    case "LIMAPUDEPORTIVASEMANA":

        /* Intenta actualizar la configuración del usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una nueva configuración de usuario si el código de error es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Se lanza una excepción si no se cumple una condición previamente establecida. */

                throw $e;
            }
        }
        break;

    case "LIMAPUDEPORTIVAMENSUAL":

        /* Código que actualiza configuración de usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Crea y guarda una nueva configuración de usuario si el código es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Lanza la excepción capturada si no se cumple una condición previa en el código. */

                throw $e;
            }
        }
        break;

    case "LIMAPUDEPORTIVAANUAL":

        /* gestiona la configuración del usuario y lanza una excepción después de actualizar. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Se inserta configuración de usuario si el código de error es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Maneja excepciones lanzando nuevamente el error en caso de fallo. */

                throw $e;
            }
        }
        break;


    case "LIMAPUCASINOSIMPLE":

        /* Intenta actualizar la configuración del usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una nueva configuración de usuario si se cumple la condición del código "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Captura una excepción y la vuelve a lanzar si no se cumple una condición. */

                throw $e;
            }
        }

        break;

    case "LIMAPUCASINODIARIO":

        /* Código que actualiza configuración de usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Condicional que crea una configuración de usuario si el código es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Lanza una excepción si se cumple una condición alternativa en el código. */

                throw $e;
            }
        }

        break;

    case "LIMAPUCASINOSEMANA":

        /* intenta actualizar la configuración del usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una nueva configuración de usuario en la base de datos si se cumple una condición. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Captura una excepción y la relanza si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
        break;

    case "LIMAPUCASINOMENSUAL":

        /* Intenta actualizar la configuración del usuario y lanza una excepción en caso de error. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* inserta una configuración de usuario si se cumple una condición específica. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Lanza una excepción si se cumple una condición específica en el código. */

                throw $e;
            }
        }
        break;

    case "LIMAPUCASINOANUAL":

        /* intenta actualizar la configuración del usuario y genera una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* inserta un objeto UsuarioConfiguracion en la base de datos bajo ciertas condiciones. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* lanza una excepción si no se cumple una condición previa. */

                throw $e;
            }
        }
        break;


    case "LIMAPUCASINOVIVOSIMPLE":

        /* crea y actualiza una configuración de usuario, luego lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");
        } catch (Exception $e) {

            /* inserta una nueva configuración de usuario si se cumple una condición específica. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Maneja excepciones lanzando el error si no se cumplen ciertas condiciones. */

                throw $e;
            }
        }

        break;

    case "LIMAPUCASINOVIVODIARIO":

        /* intenta actualizar la configuración del usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* inserta una configuración de usuario en la base de datos si se cumple una condición. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Se lanza la excepción capturada si no se cumple una condición previa. */

                throw $e;
            }
        }

        break;

    case "LIMAPUCASINOVIVOSEMANA":

        /* Código que actualiza la configuración de un usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* inserta una configuración de usuario si el código de error es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* lanza una excepción si una condición no se cumple. */

                throw $e;
            }
        }
        break;

    case "LIMAPUCASINOVIVOMENSUAL":

        /* actualiza la configuración de un usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una nueva configuración de usuario si el código de error es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Lanza una excepción si no se cumple la condición previa en el bloque de código. */

                throw $e;
            }
        }
        break;

    case "LIMAPUCASINOVIVOANUAL":

        /* intenta actualizar la configuración de un usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una configuración de usuario si el código de error es 46. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Lanza una excepción si no se cumple una condición específica. */

                throw $e;
            }
        }
        break;

    case "LIMAPUVIRTUALESSIMPLE":

        /* intenta actualizar la configuración del usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");
        } catch (Exception $e) {

            /* inserta configuración de usuario si se cumple la condición del código 46. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Captura de excepción: lanza el error si no se cumplen las condiciones previas. */

                throw $e;
            }
        }

        break;

    case "LIMAPUVIRTUALESDIARIO":

        /* crea y actualiza la configuración de un usuario, luego lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una nueva configuración de usuario tras verificar un código específico. */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Lanza la excepción capturada si no se cumple la condición anterior. */

                throw $e;
            }
        }

        break;

    case "LIMAPUVIRTUALESSEMANA":

        /* Intenta actualizar la configuración de usuario, lanzando una excepción posteriormente. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Se crea una configuración de usuario si el código de error es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* lanza una excepción si no se cumple una condición previa. */

                throw $e;
            }
        }
        break;

    case "LIMAPUVIRTUALESMENSUAL":

        /* crea y actualiza la configuración de un usuario, lanzando una excepción afterward. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* inserta configuración de usuario si el código del error es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* Se lanza una excepción si se cumple la condición en el bloque anterior. */

                throw $e;
            }
        }
        break;

    case "LIMAPUVIRTUALESANUAL":

        /* Código que actualiza la configuración de un usuario y lanza una excepción. */
        try {
            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
            //$UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());
            $UsuarioConfiguracion->setEstado('I');

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            throw new Exception("", "46");

        } catch (Exception $e) {

            /* Inserta una configuración de usuario si el código es "46". */
            if ($e->getCode() == "46") {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                $UsuarioConfiguracion->setUsucreaId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setUsumodifId($UsuarioLog->getUsuarioId());
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setValor($UsuarioLog->getValorDespues());

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            } else {
                /* lanza una excepción si no se cumplen ciertas condiciones previas. */

                throw $e;
            }
        }
        break;

    case "TIEMPOLIMITEAUTOEXCLUSION":
        /* Asigna un nuevo tiempo de autoexclusión al usuario desde el registro de logs. */

        $Usuario->tiempoAutoexclusion = $UsuarioLog->getValorDespues();

        break;

    case "CAMBIOSAPROBACION":
        /* Asignar un nuevo valor a cambiosAprobacion del usuario tras una modificación. */

        $Usuario->cambiosAprobacion = $UsuarioLog->getValorDespues();
        break;

    case "ESTADOUSUARIO":
        /* Actualiza el estado del usuario con el valor proporcionado por $UsuarioLog. */

        $Usuario->estado = $UsuarioLog->getValorDespues();
        break;


    case "USUNOMBRE2":
        /* Asigna un segundo nombre y actualiza el nombre completo del usuario. */

        $Registro->nombre2 = $UsuarioLog->getValorDespues();
        $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
        break;

    case "USUAPELLIDO2":
        /* Asigna un segundo apellido y actualiza el nombre completo de un usuario. */

        $Registro->apellido2 = $UsuarioLog->getValorDespues();
        $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
        break;

    case "USUFECHANACIM":
        /* extrae y establece la fecha de nacimiento del usuario logueado. */

        $fechaNacim = substr($UsuarioLog->getValorDespues(), 0, 10);
        $UsuarioOtrainfo->setFechaNacim($fechaNacim);
        break;

}


/* Se están actualizando registros de usuario en una base de datos MySQL mediante DAO. */
$RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
$RegistroMySqlDAO->update($Registro);

$UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
$UsuarioMySqlDAO->update($Usuario);

$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);

/* Actualiza información del usuario y registro, luego confirma la transacción en la base de datos. */
$UsuarioOtrainfoMySqlDAO->update($UsuarioOtrainfo);


$UsuarioLogMySqlDAO->update($UsuarioLog);


$Transaction->commit();


/* Código que inicializa una respuesta sin errores y con datos vacíos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = [];