<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Banco;
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
 * Asignar cupo a los usuarios de una red
 *
 * @param object $params Objeto que contiene los parámetros de la transacción, incluyendo:
 * @param int $params ->Id ID del usuario
 * @param int $params ->BankId ID del banco
 * @param string $params ->TransactionId ID de la transacción
 * @param float $params ->Amount Monto de la transacción
 * @param int $params ->Type Tipo de transacción (0 o 1)
 * @param string $params ->codeQR Código QR
 *
 *
 * @return array $response Arreglo que contiene la respuesta de la operación:
 *  - bool $HasError: Indica si hubo un error
 *  - string $AlertType: Tipo de alerta
 *  - string $AlertMessage: Mensaje de alerta
 *  - array $ModelErrors: Errores del modelo
 *  - array $Data: Datos adicionales
 *  - string $Pdf: PDF codificado en base64
 */


/* Se crea un objeto UsuarioMandante y se obtienen parámetros de transacción. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


$Id = $params->Id;
$BankId = $params->BankId;
$transactionId = $params->TransactionId;


/* Valida si una transacción ya fue utilizada y maneja excepciones. */
$continuar = false;

if ($transactionId != "" && $transactionId != null) {

    try {
        $CupoLog = new CupoLog("", $transactionId);

        if (!empty($CupoLog) && $CupoLog->getCupoLogId() != null) {
            throw new Exception("El código de transacción ya ha sido utilizado", 300060);
        }

    } catch (Exception $e) {
        if ($e->getCode() == 01) {
            $continuar = true;
        } else {
            throw $e;
        }
    }

} else {
    /* asigna `true` a la variable `$continuar` en caso de no cumplirse una condición. */

    $continuar = true;
}

if ($continuar == true) {


    /* asigna valores y lanza una excepción si el tipo es cero. */
    $Amount = $params->Amount;
    $Type = ($params->Type != 0) ? 1 : 0;

    $codeQR = $params->codeQR;


    if ($Type == 0) {
        throw new Exception("Inusual Detected", "11");
    }


    /* valida valores de $Amount e $Id, lanzando excepciones si son inválidos. */
    if (floatval($Amount) <= 0) {
        //throw new Exception("Inusual Detected", "11");
    }

    if (!is_numeric($Id) || $Id <= 0) {
        throw new Exception("Inusual Detected", "11");
    }


    /* Verifica el perfil de usuario y lanza excepción si es no autorizado. */
    $Note = "";
    $tipo = 'E';


    $UsuarioPerfil = new UsuarioPerfil($Id);

    if ($_SESSION["win_perfil"] == "PUNTOVENTA" or $_SESSION["win_perfil"] == "USUONLINE" or $_SESSION["win_perfil"] == "CAJERO") {
        throw new Exception("Inusual Detected", "11");
    }

    if ($UsuarioPerfil->getPerfilId() == "USUONLINE") {

        /* El comando "exit();" termina la ejecución del programa o script actual. */
        exit();
        if ($Amount > 0) {


            /* Se incrementa un consecutivo de recarga mediante un objeto en PHP. */
            $Consecutivo = new Consecutivo("", "REC", "");

            $consecutivo_recarga = $Consecutivo->numero;

            /**
             * Actualizamos consecutivo Recarga
             */

            $consecutivo_recarga++;

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();


            /* Actualiza un objeto "Consecutivo" en la base de datos usando MySQL. */
            $Consecutivo->setNumero($consecutivo_recarga);


            $ConsecutivoMySqlDAO->update($Consecutivo);

            $ConsecutivoMySqlDAO->getTransaction()->commit();


            /* Crea un objeto UsuarioRecarga y establece sus propiedades relevantes. */
            $UsuarioRecarga = new UsuarioRecarga();

            $UsuarioRecarga = new UsuarioRecarga();
            $UsuarioRecarga->setRecargaId($consecutivo_recarga);
            $UsuarioRecarga->setUsuarioId($Id);
            $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));

            /* establece propiedades para un objeto de recarga de usuario. */
            $UsuarioRecarga->setPuntoventaId($UsuarioMandante->getUsuarioMandante());
            $UsuarioRecarga->setValor($Amount);
            $UsuarioRecarga->setPorcenRegaloRecarga(0);
            $UsuarioRecarga->setDirIp(0);
            $UsuarioRecarga->setPromocionalId(0);
            $UsuarioRecarga->setValorPromocional(0);

            /* Establece varios atributos de un objeto UsuarioRecarga a cero. */
            $UsuarioRecarga->setHost(0);
            $UsuarioRecarga->setMandante(0);
            $UsuarioRecarga->setPedido(0);
            $UsuarioRecarga->setPorcenIva(0);
            $UsuarioRecarga->setMediopagoId(0);
            $UsuarioRecarga->setValorIva(0);

            /* Código para actualizar el estado de usuario y guardar la recarga en la base de datos. */
            $UsuarioRecarga->setEstado('A');

            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
            $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

            $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);


            /* gestiona créditos para usuarios según su perfil en el sistema. */
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


            /* Confirma y guarda los cambios realizados en una transacción de base de datos. */
            $Transaction->commit();


        }


    } elseif ($UsuarioPerfil->getPerfilId() == "MAQUINAANONIMA") {

        /* Asignación de variables y ajuste de monto negativo a positivo en el código. */
        $userfrom = $UsuarioMandante->getUsuarioMandante();
        $userto = $Id;

        if ($Amount < 0) {
            $tipo = 'S';
            $Amount = -$Amount;
        }/*
    $Consecutivo = new Consecutivo("", "REC", "");

    $consecutivo_recarga = $Consecutivo->numero;

    $consecutivo_recarga++;

    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

    $Consecutivo->setNumero($consecutivo_recarga);


    $ConsecutivoMySqlDAO->update($Consecutivo);

    $ConsecutivoMySqlDAO->getTransaction()->commit();*/


        /* Crea una nueva instancia de UsuarioRecarga con datos del usuario y fecha. */
        $UsuarioRecarga = new UsuarioRecarga();
        //$UsuarioRecarga->setRecargaId($consecutivo_recarga);
        $UsuarioRecarga->setUsuarioId($UsuarioPerfil->getUsuarioId());
        $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
        $UsuarioRecarga->setPuntoventaId(0);
        $UsuarioRecarga->setValor($Amount);

        /* Código que configura propiedades de un objeto UsuarioRecarga a cero. */
        $UsuarioRecarga->setPorcenRegaloRecarga(0);
        $UsuarioRecarga->setDirIp(0);
        $UsuarioRecarga->setPromocionalId(0);
        $UsuarioRecarga->setValorPromocional(0);
        $UsuarioRecarga->setHost(0);
        $UsuarioRecarga->setMandante(0);

        /* configura un objeto "UsuarioRecarga" con valores iniciales y crea un DAO. */
        $UsuarioRecarga->setPedido(0);
        $UsuarioRecarga->setPorcenIva(0);
        $UsuarioRecarga->setMediopagoId(0);
        $UsuarioRecarga->setValorIva(0);
        $UsuarioRecarga->setEstado('A');

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

        /* Se obtienen transacciones y se insertan datos de recarga en la base de datos. */
        $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);


        $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);
        $consecutivo_recarga = $UsuarioRecarga->recargaId;


        /* Inicializa variables para el saldo de recargas y el saldo de juego en cero. */
        $SaldoRecargas = 0;
        $SaldoJuego = 0;

        if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {


            /* Valida créditos o cupo antes de realizar una transferencia en el punto de venta. */
            $PuntoVentaSuper = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

            if (($PuntoVentaSuper->getCreditosBase() < $Amount && $Type == 1 && $tipo != "S") || ($PuntoVentaSuper->getCupoRecarga() < $Amount && $Type == 0 && $tipo != "S")) {
                throw new Exception("No tiene saldo para transferir", "111");
            } else {

            }


            /* gestiona recargas y créditos en un sistema de punto de venta. */
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
                /* Ajusta balances de recarga o créditos basados en tipo de transacción. */

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


            /* maneja transacciones y crea objetos de usuario para WebSocket. */
            $Transaction->commit();

            $UsuarioMandante = new UsuarioMandante("", $Id, "0");

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            /*$data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
            $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            $WebsocketUsuario->sendWSMessage();*/


            /* genera un formato HTML para visualizar detalles de una recarga en PDF. */
            $pdf = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody>
<tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">RECARGA</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Recarga No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Maquina:&nbsp;&nbsp;' . $Id . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . date("Y-m-d H:i:s") . '</font></td></tr>
<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor:&nbsp;&nbsp;' . $Amount . '</font></td></tr>
</tbody></table>';

        } else {

            /* Código que ajusta balances en un punto de venta según tipo y monto especificado. */
            $PuntoVenta = new PuntoVenta("", $Id);

            if ($tipo == "S") {
                if ($Type == 0) {
                    $PuntoVenta->setBalanceCupoRecarga(-$Amount);
                } else {
                    $PuntoVenta->setBalanceCreditosBase(-$Amount);

                }

            } else {
                /* asigna un valor según el tipo y actualiza el balance correspondiente. */

                if ($Type == 0) {
                    $PuntoVenta->setBalanceCupoRecarga($Amount);
                } else {
                    $PuntoVenta->setBalanceCreditosBase($Amount);

                }

            }


            /* Actualiza un punto de venta y obtiene información del usuario para WebSocket. */
            $PuntoVentaMySqlDAO->update($PuntoVenta);

            $Transaction->commit();

            $UsuarioMandante = new UsuarioMandante("", $Id, "0");

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            /*$data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
            $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            $WebsocketUsuario->sendWSMessage();*/


            /* Genera un PDF con detalles de una recarga incluyendo consecutivo, máquina, fecha y valor. */
            $pdf = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody>
<tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">RECARGA</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Recarga No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Maquina:&nbsp;&nbsp;' . $Id . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . date("Y-m-d H:i:s") . '</font></td></tr>
<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor:&nbsp;&nbsp;' . $Amount . '</font></td></tr>
</tbody></table>';

        }


        /* Se crea un PDF usando Mpdf con márgenes espejados y estilo personalizado. */
        $mpdf = new \Mpdf\Mpdf(['format' => array(80, 150), 'tempDir' => '/tmp']);

        $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

        $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

        $mpdf->WriteHTML($pdf);


        /* genera un archivo PDF y lo convierte a base64. */
        $mpdf->Output("mpdf.pdf", "F");

        $path = 'mpdf.pdf';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);


        /* Código que codifica un PDF en base64 y lo asigna a una respuesta. */
        $encoded_html = base64_encode($pdf);

        $response["Pdf"] = base64_encode($data);

    } else {


        /* verifica un monto negativo y ajusta su valor y tipo. */
        if ($Amount < 0) {
            $tipo = 'S';
            $Amount = -$Amount;
        }

        $userfrom = $UsuarioMandante->getUsuarioMandante();

        /* Se inicializa un objeto Usuario y se establece un tipo de cupo basado en una condición. */
        $UsuarioFrom = new Usuario($userfrom);

        $userto = $Id;

        if ($Type == 0) {
            $tipoCupo = 'R';
        } else {
            /* Asigna el valor 'A' a la variable $tipoCupo en caso de que no se cumpla condición. */

            $tipoCupo = 'A';

        }


        /* Se obtiene la descripción de un banco según su ID y se actualiza una nota. */
        $Banco = new Banco($BankId);
        $descripcionBanco = $Banco->descripcion;

        if ($BankId != '' && $BankId != '0') {
            $Note = $Note . '_' . $BankId;
        }


        /* Registro un nuevo objeto CupoLog con detalles del usuario y transacción. */
        $CupoLog = new CupoLog();
        $CupoLog->setUsuarioId($userto);
        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
        $CupoLog->setTipoId($tipo);
        $CupoLog->setValor($Amount);
        $CupoLog->setUsucreaId($userfrom);

        /* Configura y guarda información de un registro de CupoLog en una base de datos. */
        $CupoLog->setMandante(0);
        $CupoLog->setTipocupoId($tipoCupo);
        $CupoLog->setObservacion($Note);
        $CupoLog->setNumeroTransaccion($transactionId);
        $CupoLog->setNombreBanco2($descripcionBanco);


        $CupoLogMySqlDAO = new CupoLogMySqlDAO();

        /* Creación de transacciones y registro en la base de datos con saldo inicial de recargas. */
        $Transaction = $CupoLogMySqlDAO->getTransaction();
        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

        $CupoLogMySqlDAO->insert($CupoLog);

        $SaldoRecargas = 0;

        /* verifica condiciones antes de permitir la transferencia de saldo entre usuarios. */
        $SaldoJuego = 0;

        $ConcesionarioU = new Concesionario($Id, '0');

        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            if ($ConcesionarioU->getUsupadreId() != $UsuarioMandante->getUsuarioMandante()) {
                throw new Exception("No puedo transferir a ese usuario", "111");

            }

        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
            /* Verifica condiciones antes de transferir; lanza excepción si no se cumplen. */

            if ($ConcesionarioU->getUsupadre2Id() != $UsuarioMandante->getUsuarioMandante()) {
                throw new Exception("No puedo transferir a ese usuario", "111");
            }

        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
            /* Verifica permisos para transferir usuarios en concesionarios específicos en la sesión. */

            if ($ConcesionarioU->getUsupadre3Id() != $UsuarioMandante->getUsuarioMandante()) {
                throw new Exception("No puedo transferir a ese usuario", "111");
            }

        }


        if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {


            /* Verifica el saldo de un punto de venta antes de permitir transferencias. */
            $PuntoVentaSuper = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

            if (($PuntoVentaSuper->getCreditosBase() < $Amount && $Type == 1 && $tipo != "S") || ($PuntoVentaSuper->getCupoRecarga() < $Amount && $Type == 0 && $tipo != "S")) {
                throw new Exception("No tiene saldo para transferir", "111");
            } else {

            }


            /* maneja transacciones de recarga y créditos en un sistema de punto de venta. */
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
                /* ajusta balances según el tipo de transacción y monto. */

                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCupoRecarga(-$Amount, $Transaction);

                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCreditosBase(-$Amount, $Transaction);

                }

            }


            /* Lanza una excepción si el saldo para transferir es cero en ambas condiciones. */
            if ($cant == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }


            if ($cant2 == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }
            //$PuntoVentaMySqlDAO->update($PuntoVenta);
            // $PuntoVentaMySqlDAO->update($PuntoVentaSuper);
        } else {

            /* gestiona ajustes de saldo en un sistema de punto de venta. */
            $PuntoVenta = new PuntoVenta("", $Id);

            if ($tipo == "S") {
                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga(-$Amount, $Transaction);
                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);

                }

            } else {
                /* Condicional que establece saldo según tipo de transacción y monto. */

                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);
                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);

                }

            }


            /* Genera una excepción si el saldo es cero al intentar transferir. */
            if ($cant == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }

            //$PuntoVentaMySqlDAO->update($PuntoVenta);

        }


        /* Crea un historial de usuario con datos del objeto CupoLog. */
        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);

        /* inserta un registro de historial de usuario en la base de datos. */
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor($CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


        /* Verifica el perfil de usuario y registra un histórico en base de datos. */
        if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {

            $tipoConce = 'E';

            if ($CupoLog->getTipoId() == "E") {
                $tipoConce = 'S';
            }
            $UsuarioHistorial2 = new UsuarioHistorial();
            $UsuarioHistorial2->setUsuarioId($CupoLog->getUsucreaId());
            $UsuarioHistorial2->setDescripcion('');
            $UsuarioHistorial2->setMovimiento($tipoConce);
            $UsuarioHistorial2->setUsucreaId(0);
            $UsuarioHistorial2->setUsumodifId(0);
            $UsuarioHistorial2->setTipo(60);
            $UsuarioHistorial2->setValor($CupoLog->getValor());
            $UsuarioHistorial2->setExternoId($CupoLog->getCupologId());

            $UsuarioHistorialMySqlDAO2 = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO2->insert($UsuarioHistorial2, '1');
        }


        /* confirma una transacción y prepara datos para generar un PDF de saldo. */
        $Transaction->commit();


        $Usuario = new Usuario($PuntoVenta->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);

        $PDFtitulo = "TRANSFERENCIA DE SALDO";

        /* Variables definidas para generar un PDF con información sobre transferencias y usuarios. */
        $PDFsoporteNo = "Transferencia No.";
        $PDFusuario = "Usuario:";
        $PDFvalor = "Valor:";
        $PDFfecha = "Fecha:";
        $PDFTipodesaldo = "Tipo de saldo:";

        $PDFTipofinal = "";


        /* Asigna un nombre a PDFTipofinal según el tipo de cupo. */
        if ($tipoCupo == "A") {
            $PDFTipofinal = "Saldo juego";

        }

        if ($tipoCupo == "R") {
            $PDFTipofinal = "Saldo recargas";
        }

        if ($_SESSION["idioma"] == "en") {


            /* Asignación de variables para generar un documento PDF sobre transferencias de saldo. */
            $PDFtitulo = "BALANCE TRANSFER";
            $PDFsoporteNo = "Transfer No.";
            $PDFusuario = "User:";
            $PDFvalor = "Amount:";
            $PDFfecha = "Date:";
            $PDFTipodesaldo = "Balance type:";


            /* Asigna un tipo de balance PDF según el valor de $tipoCupo. */
            if ($tipoCupo == "A") {
                $PDFTipofinal = "Game balance";

            }

            if ($tipoCupo == "R") {
                $PDFTipofinal = "Deposit balance";
            }


        }
        try {

            if (true) {


                /* Se crea un clasificador y plantilla, y se añade un logo para PDF. */
                $Clasificador = new Clasificador("", "PLPDFTRANS");

                $Plantilla = new Plantilla("", $Clasificador->clasificadorId, "3", $Mandante->mandante, strtolower($Usuario->idioma));

                $pdf = $Plantilla->plantilla;
                $logoParam = '<img style=" padding-left: 20px;" src="' . $Mandante->logoPdf . '" alt="logo">';

                /* Se reemplazan marcadores en un PDF con datos de CupoLog. */
                $logoParam = '';

                $pdf = str_replace("[id]", $CupoLog->getCupologId(), $pdf);
                $pdf = str_replace("[date]", $CupoLog->getFechaCrea(), $pdf);
                $pdf = str_replace("[user_id]", $CupoLog->getUsuarioId(), $pdf);
                $pdf = str_replace("[type_balance]", $PDFTipofinal, $pdf);

                /* reemplaza marcadores en un PDF y configura Mpdf para generación de documentos. */
                $pdf = str_replace("[amount]", $CupoLog->getValor() . ' ' . $Usuario->moneda, $pdf);
                $pdf = str_replace("[userfrom_id]", $userfrom, $pdf);
                $pdf = str_replace("[name_userfrom_id]", $UsuarioFrom->nombre, $pdf);
                $pdf = str_replace("[logo]", $logoParam, $pdf);


                $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L', 'tempDir' => '/tmp']);


                /* Configuración de márgenes, visualización y salida de un PDF con mPDF. */
                $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

                $mpdf->SetDisplayMode('fullpage', 'two');
                $mpdf->WriteHTML($pdf);

                $mpdf->Output('/tmp' . "/mpdfMAT" . $CupoLog->getCupologId() . ".pdf", "F");


                /* genera un PDF codificado en base64 desde un archivo específico. */
                $path = '/tmp' . '/mpdfMAT' . $CupoLog->getCupologId() . '.pdf';

                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

                $encoded_html = base64_encode($pdf);


                /* convierte datos binarios a una cadena base64 y la asigna a "Pdf". */
                $response["Pdf"] = base64_encode($data);
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, evitando que interrumpan la ejecución del script. */

        }


    }
}


/* crea una respuesta indicando éxito en una operación sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];

