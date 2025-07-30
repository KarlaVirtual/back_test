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
use Backend\dto\Plantilla;
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
 * Agent/MakeAgentTransfers
 *
 * Reservar asignación de saldo
 *
 * @param object $params Objeto que contiene los parámetros de la operación:
 * @param int $Id : Identificador del usuario.
 * @param float $Amount : Monto a transferir.
 * @param int $Type : Tipo de operación (0 o 1).
 *
 * @return array $response Arreglo con la respuesta de la operación:
 *                         - bool $HasError: Indica si hubo un error.
 *                         - string $AlertType: Tipo de alerta.
 *                         - string $AlertMessage: Mensaje de alerta.
 *                         - array $ModelErrors: Errores del modelo.
 *                         - string $Pdf: PDF codificado en base64.
 */


/* Se crea un objeto UsuarioMandante con información de sesión y se obtienen parámetros. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


$Id = $params->Id;

$Amount = $params->Amount;

/* valida un tipo y monto, lanzando excepciones en condiciones específicas. */
$Type = ($params->Type != 0) ? 1 : 0;

if ($Type == 0) {
    throw new Exception("Inusual Detected", "11");
}

if (floatval($Amount) <= 0) {
    //throw new Exception("Inusual Detected", "11");
}


/* Verifica si $Id es numérico y mayor a cero; lanza excepción si no. */
if (!is_numeric($Id) || $Id <= 0) {
    throw new Exception("Inusual Detected", "11");
}


$Note = "";

/* Verifica si el usuario en sesión no es uno de los IDs excluidos. */
$tipo = 'S';


if (

    $_SESSION["usuario"] != 7194605 &&
    $_SESSION["usuario"] != 7270186 &&
    $_SESSION["usuario"] != 7270205 &&
    $_SESSION["usuario"] != 7270224 &&
    $_SESSION["usuario"] != 7270230 &&
    $_SESSION["usuario"] != 7270239 &&
    $_SESSION["usuario"] != 7270245 &&
    $_SESSION["usuario"] != 7270257 &&
    $_SESSION["usuario"] != 7270266 &&
    $_SESSION["usuario"] != 7270279


) {


    /* lanza una excepción si el perfil de sesión es específico. */
    if ($_SESSION["win_perfil"] == "PUNTOVENTA" or $_SESSION["win_perfil"] == "USUONLINE" or $_SESSION["win_perfil"] == "CAJERO") {
        throw new Exception("Inusual Detected", "11");
    }
}
try {

    /* Crea una nueva instancia de la clase UsuarioPerfil usando el identificador proporcionado. */
    $UsuarioPerfil = new UsuarioPerfil($Id);

    if ($UsuarioPerfil->getPerfilId() == "USUONLINE") {

        /* La función `exit()` detiene la ejecución del script en PHP. */
        exit();
        if ($Amount > 0) {


            /* actualiza un consecutivo de recarga en una base de datos. */
            $Consecutivo = new Consecutivo("", "REC", "");

            $consecutivo_recarga = $Consecutivo->numero;

            /**
             * Actualizamos consecutivo Recarga
             */

            $consecutivo_recarga++;

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();


            /* Actualiza el número de un objeto y gestiona la transacción en MySQL. */
            $Consecutivo->setNumero($consecutivo_recarga);


            $ConsecutivoMySqlDAO->update($Consecutivo);

            $ConsecutivoMySqlDAO->getTransaction()->commit();


            /* Se crea un objeto 'UsuarioRecarga' y se establecen sus propiedades. */
            $UsuarioRecarga = new UsuarioRecarga();

            $UsuarioRecarga = new UsuarioRecarga();
            $UsuarioRecarga->setRecargaId($consecutivo_recarga);
            $UsuarioRecarga->setUsuarioId($Id);
            $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));

            /* establece propiedades de un objeto de recarga de usuario. */
            $UsuarioRecarga->setPuntoventaId($UsuarioMandante->getUsuarioMandante());
            $UsuarioRecarga->setValor($Amount);
            $UsuarioRecarga->setPorcenRegaloRecarga(0);
            $UsuarioRecarga->setDirIp(0);
            $UsuarioRecarga->setPromocionalId(0);
            $UsuarioRecarga->setValorPromocional(0);

            /* Se inicializan propiedades de un objeto UsuarioRecarga a valores predeterminados. */
            $UsuarioRecarga->setHost(0);
            $UsuarioRecarga->setMandante(0);
            $UsuarioRecarga->setPedido(0);
            $UsuarioRecarga->setPorcenIva(0);
            $UsuarioRecarga->setMediopagoId(0);
            $UsuarioRecarga->setValorIva(0);

            /* Configura el estado del usuario recarga y lo inserta en la base de datos. */
            $UsuarioRecarga->setEstado('A');

            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
            $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

            $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);


            /* gestiona créditos en función del perfil de usuario y tipo de transacción. */
            $Usuario = new Usuario($Id);
            $Usuario->credit($Amount, $Transaction);


            if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3" or $_SESSION["win_perfil"] == "PUNTOVENTA") {

                $PuntoVenta = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

                if ($tipo == "S") {
                    $PuntoVenta->setBalanceCreditosBase($Amount);


                } else {
                    $PuntoVenta->setBalanceCreditosBase(-$Amount);
                }

                $PuntoVenta->update($PuntoVenta);

            }


            /* Confirma y guarda los cambios realizados en la transacción actual de la base de datos. */
            $Transaction->commit();


        }


    } elseif ($UsuarioPerfil->getPerfilId() == "MAQUINAANONIMA") {

        /* asigna valores a usuarios y ajusta la cantidad si es negativa. */
        $userfrom = $UsuarioMandante->getUsuarioMandante();
        $userto = $Id;

        if ($Amount < 0) {
            $tipo = 'S';
            $Amount = -$Amount;
        }
        /*$Consecutivo = new Consecutivo("", "REC", "");

        $consecutivo_recarga = $Consecutivo->numero;

        $consecutivo_recarga++;

        $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

        $Consecutivo->setNumero($consecutivo_recarga);


        $ConsecutivoMySqlDAO->update($Consecutivo);

        $ConsecutivoMySqlDAO->getTransaction()->commit();*/


        /* Se crean y configuran propiedades de un objeto UsuarioRecarga. */
        $UsuarioRecarga = new UsuarioRecarga();
        //$UsuarioRecarga->setRecargaId($consecutivo_recarga);
        $UsuarioRecarga->setUsuarioId($UsuarioPerfil->getUsuarioId());
        $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
        $UsuarioRecarga->setPuntoventaId(0);
        $UsuarioRecarga->setValor($Amount);

        /* establece diferentes propiedades del objeto UsuarioRecarga con valores cero. */
        $UsuarioRecarga->setPorcenRegaloRecarga(0);
        $UsuarioRecarga->setDirIp(0);
        $UsuarioRecarga->setPromocionalId(0);
        $UsuarioRecarga->setValorPromocional(0);
        $UsuarioRecarga->setHost(0);
        $UsuarioRecarga->setMandante(0);

        /* Código para inicializar propiedades de un objeto de usuario de recarga. */
        $UsuarioRecarga->setPedido(0);
        $UsuarioRecarga->setPorcenIva(0);
        $UsuarioRecarga->setMediopagoId(0);
        $UsuarioRecarga->setValorIva(0);
        $UsuarioRecarga->setEstado('A');

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

        /* Se obtiene una transacción y se inserta un registro de recarga en la base de datos. */
        $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);


        $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

        $consecutivo_recarga = $UsuarioRecarga->recargaId;


        /* Inicializa variables para almacenar saldo de recargas y saldo del juego en cero. */
        $SaldoRecargas = 0;
        $SaldoJuego = 0;

        if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {


            /* Valida saldo suficiente para transferencias según condiciones específicas antes de proceder. */
            $PuntoVentaSuper = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

            if (($PuntoVentaSuper->getCreditosBase() < $Amount && $Type == 1 && $tipo != "S") || ($PuntoVentaSuper->getCupoRecarga() < $Amount && $Type == 0 && $tipo != "S")) {
                throw new Exception("No tiene saldo para transferir", "111");
            } else {

            }


            /* Establece ajustes de saldo en función del tipo de transacción y monto. */
            $PuntoVenta = new PuntoVenta("", $Id);

            if ($tipo == "S") {
                if ($Type == 0) {
                    $PuntoVenta->setBalanceCupoRecarga(-$Amount, $Transaction);
                    $PuntoVentaSuper->setBalanceCupoRecarga($Amount, $Transaction);
                } else {
                    $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
                    $PuntoVentaSuper->setBalanceCreditosBase($Amount, $Transaction);

                }

            } else {
                /* Ajusta balances de recarga o créditos según tipo de transacción y monto. */

                if ($Type == 0) {
                    $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);
                    $PuntoVentaSuper->setBalanceCupoRecarga(-$Amount, $Transaction);

                } else {
                    $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);
                    $PuntoVentaSuper->setBalanceCreditosBase(-$Amount, $Transaction);

                }

            }

            //$PuntoVentaMySqlDAO->update($PuntoVenta);
            //$PuntoVentaMySqlDAO->update($PuntoVentaSuper);


            /* realiza un commit de una transacción y crea objetos de usuario. */
            $Transaction->commit();

            $UsuarioMandante = new UsuarioMandante("", $Id, "0");

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            /*$data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
            $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            $WebsocketUsuario->sendWSMessage();*/


            /* Genera un formato de tabla HTML para una recarga con información específica. */
            $pdf = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody>
<tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">RECARGA</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Recarga No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Maquina:&nbsp;&nbsp;' . $Id . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . date("Y-m-d H:i:s") . '</font></td></tr>
<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor:&nbsp;&nbsp;' . $Amount . '</font></td></tr>
</tbody></table>';

        } else {

            /* Código para ajustar balance de un punto de venta según tipo y monto. */
            $PuntoVenta = new PuntoVenta("", $Id);

            if ($tipo == "S") {
                if ($Type == 0) {
                    $PuntoVenta->setBalanceCupoRecarga(-$Amount);
                } else {
                    $PuntoVenta->setBalanceCreditosBase(-$Amount);

                }

            } else {
                /* establece balances dependiendo del tipo de operación y monto especificado. */

                if ($Type == 0) {
                    $PuntoVenta->setBalanceCupoRecarga($Amount);
                } else {
                    $PuntoVenta->setBalanceCreditosBase($Amount);

                }

            }


            /* actualiza un punto de venta y gestiona usuario mediante WebSocket. */
            $PuntoVentaMySqlDAO->update($PuntoVenta);

            $Transaction->commit();

            $UsuarioMandante = new UsuarioMandante("", $Id, "0");

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            /* $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
             $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
             $WebsocketUsuario->sendWSMessage();*/


            /* Genera un PDF con información sobre una recarga, incluyendo detalles y valores. */
            $pdf = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody>
<tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">RECARGA</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Recarga No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Maquina:&nbsp;&nbsp;' . $Id . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . date("Y-m-d H:i:s") . '</font></td></tr>
<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor:&nbsp;&nbsp;' . $Amount . '</font></td></tr>
</tbody></table>';

        }


        /* Se configura mPDF para generar un PDF en formato A4 horizontal con márgenes espejados. */
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L', 'tempDir' => '/tmp']);

        $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

        $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

        $mpdf->WriteHTML($pdf);


        /* Genera un archivo PDF, lo convierte a base64 y lo prepara para uso. */
        $mpdf->Output("mpdf.pdf", "F");

        $path = 'mpdf.pdf';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);


        /* Se codifica un PDF en base64 y se almacena en un arreglo de respuesta. */
        $encoded_html = base64_encode($pdf);

        $response["Pdf"] = base64_encode($data);

    } else {


        /* inicializa un usuario y define un tipo de cupo según una condición. */
        $userfrom = $UsuarioMandante->getUsuarioMandante();
        $UsuarioFrom = new Usuario($userfrom);

        $userto = $Id;

        if ($Type == 0) {
            $tipoCupo = 'R';
        } else {
            /* Asigna el valor 'A' a la variable $tipoCupo si no se cumple una condición previa. */

            $tipoCupo = 'A';

        }


        /* Registra un nuevo log de cupo con información del usuario y fecha. */
        $CupoLog = new CupoLog();
        $CupoLog->setUsuarioId($userto);
        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
        $CupoLog->setTipoId($tipo);
        $CupoLog->setValor($Amount);
        $CupoLog->setUsucreaId($userfrom);

        /* Configura un objeto de registro y obtiene una transacción de base de datos. */
        $CupoLog->setMandante(0);
        $CupoLog->setTipocupoId($tipoCupo);
        $CupoLog->setObservacion($Note);

        $CupoLogMySqlDAO = new CupoLogMySqlDAO();
        $Transaction = $CupoLogMySqlDAO->getTransaction();

        /* Se inicializan objetos y se insertan datos en la base de datos MySQL. */
        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

        $CupoLogMySqlDAO->insert($CupoLog);

        $SaldoRecargas = 0;
        $SaldoJuego = 0;


        /* Valida si se puede transferir un concesionario según el usuario y perfil. */
        $ConcesionarioU = new Concesionario($userto, '0');

        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            if ($ConcesionarioU->getUsupadreId() != $UsuarioMandante->getUsuarioMandante()) {
                throw new Exception("No puedo transferir a ese usuario", "111");

            }

        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
            /* Valida que el usuario sea transferido solo si cumple condiciones específicas de sesión. */

            if ($ConcesionarioU->getUsupadre2Id() != $UsuarioMandante->getUsuarioMandante()) {
                throw new Exception("No puedo transferir a ese usuario", "111");
            }

        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
            /* verifica permisos antes de permitir la transferencia de usuarios en concesionarios. */

            if ($ConcesionarioU->getUsupadre3Id() != $UsuarioMandante->getUsuarioMandante()) {
                throw new Exception("No puedo transferir a ese usuario", "111");
            }

        }

        if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {


            /* Verifica el saldo del punto de venta antes de permitir transferencias. */
            $PuntoVentaSuper = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

            if (($PuntoVentaSuper->getCreditosBase() < $Amount && $Type == 1 && $tipo != "S") || ($PuntoVentaSuper->getCupoRecarga() < $Amount && $Type == 0 && $tipo != "S")) {
                throw new Exception("No tiene saldo para transferir", "111");
            } else {

            }


            /* gestiona transacciones de recarga y ajuste de créditos en puntos de venta. */
            $PuntoVenta = new PuntoVenta("", $Id);

            if ($tipo == "S") {
                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga(-$Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCupoRecarga($Amount, $Transaction);
                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCreditosBase($Amount, $Transaction);

                }

            } else {
                /* ajusta balances de recarga o créditos según el tipo de transacción. */

                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCupoRecarga(-$Amount, $Transaction);

                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCreditosBase(-$Amount, $Transaction);

                }

            }


            /* verifica saldo y lanza excepciones si no hay fondos disponibles. */
            if ($cant == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }


            if ($cant2 == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }
            //$PuntoVentaMySqlDAO->update($PuntoVenta);
            // $PuntoVentaMySqlDAO->update($PuntoVentaSuper);
        } else {

            /* gestiona balances de recarga y créditos en un punto de venta. */
            $PuntoVenta = new PuntoVenta("", $Id);

            if ($tipo == "S") {
                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga(-$Amount, $Transaction);
                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);

                }

            } else {
                /* ajusta balances según el tipo de transacción y monto especificado. */

                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);
                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);

                }

            }


            /* Lanza una excepción si la cantidad de saldo es cero al intentar transferir. */
            if ($cant == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }

            //$PuntoVentaMySqlDAO->update($PuntoVenta);

        }


        /* Se crea un historial de usuario con datos del objeto CupoLog. */
        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);

        /* Se crea un historial de usuario y se inserta en la base de datos. */
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor($CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

        if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {


            /* Se asigna 'S' a $tipoConce si el tipo es 'E', y se crea un objeto. */
            $tipoConce = 'E';

            if ($CupoLog->getTipoId() == "E") {
                $tipoConce = 'S';
            }
            $UsuarioHistorial2 = new UsuarioHistorial();

            /* establece atributos en un objeto UsuarioHistorial2 para registrar un movimiento. */
            $UsuarioHistorial2->setUsuarioId($CupoLog->getUsucreaId());
            $UsuarioHistorial2->setDescripcion('');
            $UsuarioHistorial2->setMovimiento($tipoConce);
            $UsuarioHistorial2->setUsucreaId(0);
            $UsuarioHistorial2->setUsumodifId(0);
            $UsuarioHistorial2->setTipo(60);

            /* inserta un historial de usuario usando datos de un registro de cupo. */
            $UsuarioHistorial2->setValor($CupoLog->getValor());
            $UsuarioHistorial2->setExternoId($CupoLog->getCupologId());

            $UsuarioHistorialMySqlDAO2 = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO2->insert($UsuarioHistorial2, '1');

        }


        /* compromete una transacción y genera un título para un PDF. */
        $Transaction->commit();


        $Usuario = new Usuario($PuntoVenta->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);

        $PDFtitulo = "RETRACCION DE SALDO";

        /* Variables en PHP para generar un PDF con información financiera. */
        $PDFsoporteNo = "Transferencia No.";
        $PDFusuario = "Usuario:";
        $PDFvalor = "Valor:";
        $PDFfecha = "Fecha:";
        $PDFTipodesaldo = "Tipo de saldo:";

        $PDFTipofinal = "";


        /* asigna descripciones a '$PDFTipofinal' según el valor de '$tipoCupo'. */
        if ($tipoCupo == "A") {
            $PDFTipofinal = "Saldo juego";

        }

        if ($tipoCupo == "R") {
            $PDFTipofinal = "Saldo recargas";
        }

        if ($_SESSION["idioma"] == "en") {


            /* Variables en PHP para crear un documento PDF sobre un retiro de saldo. */
            $PDFtitulo = "WITHDRAWAL OF BALANCE";
            $PDFsoporteNo = "Transfer No.";
            $PDFusuario = "User:";
            $PDFvalor = "Amount:";
            $PDFfecha = "Date:";
            $PDFTipodesaldo = "Balance type:";


            /* asigna un tipo de saldo basado en la variable $tipoCupo. */
            if ($tipoCupo == "A") {
                $PDFTipofinal = "Game balance";

            }

            if ($tipoCupo == "R") {
                $PDFTipofinal = "Deposit balance";
            }


        }
        try {

            if (true) {


                /* crea un clasificador y una plantilla PDF con un logo. */
                $Clasificador = new Clasificador("", "PLPDFRET");

                $Plantilla = new Plantilla("", $Clasificador->clasificadorId, "3", $Mandante->mandante, strtolower($Usuario->idioma));

                $pdf = $Plantilla->plantilla;
                $logoParam = '<img style=" padding-left: 20px;" src="' . $Mandante->logoPdf . '" alt="logo">';


                /* Reemplaza marcadores en un PDF con datos de un registro específico. */
                $pdf = str_replace("[id]", $CupoLog->getCupologId(), $pdf);
                $pdf = str_replace("[date]", $CupoLog->getFechaCrea(), $pdf);
                $pdf = str_replace("[user_id]", $CupoLog->getUsuarioId(), $pdf);
                $pdf = str_replace("[type_balance]", $PDFTipofinal, $pdf);
                $pdf = str_replace("[amount]", $CupoLog->getValor() . ' ' . $Usuario->moneda, $pdf);
                $pdf = str_replace("[userfrom_id]", $userfrom, $pdf);

                /* modifica un PDF reemplazando variables y establece su formato. */
                $pdf = str_replace("[name_userfrom_id]", $UsuarioFrom->nombre, $pdf);
                $pdf = str_replace("[logo]", $logoParam, $pdf);


// $mpdf = new mPDF('c', array(80, 150));
                $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L', 'tempDir' => '/tmp']);


                /* Configura márgenes, visualización y genera un PDF en el directorio temporal. */
                $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

                $mpdf->SetDisplayMode('fullpage', 'two');
                $mpdf->WriteHTML($pdf);

                $mpdf->Output('/tmp' . "/mpdfMA" . $CupoLog->getCupologId() . ".pdf", "F");


                /* Genera un archivo PDF, lo convierte a base64 y lo codifica como HTML. */
                $path = '/tmp' . '/mpdfMA' . $CupoLog->getCupologId() . '.pdf';

                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

                $encoded_html = base64_encode($pdf);


                /* Codifica datos en formato Base64 y los asigna a la clave "Pdf" de respuesta. */
                $response["Pdf"] = base64_encode($data);
            }
        } catch (Exception $e) {
            /* captura excepciones y las imprime para depuración en PHP. */

            print_r($e);
        }


    }
} catch (Exception $e) {
    /* Captura y muestra información sobre excepciones en PHP. */

    print_r($e);
}


/* Código asigna respuesta exitosa a una operación sin errores ni mensajes de modelo. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];


