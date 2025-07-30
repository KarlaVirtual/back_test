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
 * Client/MakeDepositAgent
 *
 * Este script asigna un cupo a los usuarios de una red desde un punto de venta o cajero.
 *
 * @param object $params Objeto JSON decodificado con las siguientes propiedades:
 * @param int $params ->Id ID del usuario al que se asignará el cupo.
 * @param float $params ->Amount Monto del cupo a asignar.
 * @param int $params ->Type Tipo de operación (1 para asignar, 0 no permitido).
 *
 *
 * @return array $response Respuesta en formato JSON con las siguientes propiedades:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta o error.
 * - ModelErrors (array): Lista de errores de validación.
 * - Pdf (string): Archivo PDF codificado en base64 con el comprobante de la operación.
 *
 * @throws Exception Si ocurre un error durante la asignación del cupo.
 */

/**
 * @OA\Post(path="apipv/Client/MakeDepositAgent", tags={"Agent"}, description = "",
 *
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Pdf",
 *                   description="",
 *                   type="string",
 *                   example= {}
 *               ),
 *             )
 *         ),
 *         )
 * ),
 * @OA\Response (response="404", description="Not found")
 * )
 */


/*error_reporting(E_ALL);
ini_set("display_errors", "ON");*/


/* Se crea un objeto UsuarioMandante utilizando el usuario de la sesión actual. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
/*$UsuarioMandante = new UsuarioMandante("","20603","0");
$_SESSION["win_perfil"] = "PUNTOVENTA";*/


$Id = $params->Id;


/* verifica el tipo y lanza una excepción si es inusual. */
$Amount = $params->Amount;
$Type = ($params->Type != 0) ? 1 : 0;


if ($Type == 0) {
    throw new Exception("Inusual Detected", "11");
}


/* Valida el monto y el ID, lanzando excepciones si son inusuales. */
if (floatval($Amount) <= 0) {
    //throw new Exception("Inusual Detected", "11");
}

if (!is_numeric($Id) || $Id <= 0) {
    throw new Exception("Inusual Detected", "11");
}


/* Código verifica el perfil del usuario y lanza excepción si se detecta un acceso inusual. */
$Note = "";
$tipo = 'E';


$UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());

if ($_SESSION["win_perfil"] == "USUONLINE") {
    throw new Exception("Inusual Detected", "11");
}


if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA" || $UsuarioPerfil->getPerfilId() == "CAJERO") {


    /* verifica si una cantidad es negativa y la convierte a positiva. */
    if ($Amount < 0) {
        $tipo = 'S';
        $Amount = -$Amount;
    }

    $userfrom = $UsuarioMandante->getUsuarioMandante();

    /* Se crea un objeto Usuario y se asigna un tipo de cupo basado en una condición. */
    $UsuarioFrom = new Usuario($userfrom);

    $userto = $Id;

    if ($Type == 0) {
        $tipoCupo = 'R';
    } else {
        /* Asignación de valor 'A' a la variable $tipoCupo en un bloque else. */

        $tipoCupo = 'A';

    }


    /* Crea un registro de log de cupo con detalles del usuario y transacción. */
    $CupoLog = new CupoLog();
    $CupoLog->setUsuarioId($userto);
    $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
    $CupoLog->setTipoId($tipo);
    $CupoLog->setValor($Amount);
    $CupoLog->setUsucreaId($userfrom);

    /* Se configura un objeto CupoLog con datos de usuario y tipo de cupo. */
    $CupoLog->setMandante($UsuarioFrom->mandante);
    $CupoLog->setTipocupoId($tipoCupo);
    $CupoLog->setObservacion($Note);

    $CupoLogMySqlDAO = new CupoLogMySqlDAO();
    $Transaction = $CupoLogMySqlDAO->getTransaction();

    /* Se inicializan objetos DAO y se inserta un registro de log para cupones. */
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

    $CupoId = $CupoLogMySqlDAO->insert($CupoLog);

    $SaldoRecargas = 0;
    $SaldoJuego = 0;


    /* Se crea una nueva instancia de la clase Concesionario con un identificador específico. */
    $ConcesionarioU = new Concesionario($Id, '0');

    /*if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        if($ConcesionarioU->getUsupadreId() != $UsuarioMandante->getUsuarioMandante()){
            throw new Exception("No puedo transferir a ese usuario", "111");

        }

    }elseif($_SESSION["win_perfil"] == "CONCESIONARIO2"){
        if($ConcesionarioU->getUsupadre2Id() != $UsuarioMandante->getUsuarioMandante()){
            throw new Exception("No puedo transferir a ese usuario", "111");
        }

    }*/


    if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {


        /* Verifica el saldo antes de permitir una transferencia de créditos en el punto de venta. */
        $UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
        $PuntoVentaSuper = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);


        if (($PuntoVentaSuper->getCreditosBase() < $Amount)) {

            throw new Exception("No tiene saldo para transferir", "111");
        }


        /* Se establece y verifica el saldo de créditos en un sistema de punto de venta. */
        $PuntoVenta = new PuntoVenta("", $Id);


        $cant = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);
        $cant2 = $PuntoVentaSuper->setBalanceCreditosBase(-$Amount, $Transaction);


        if ($cant == 0) {

            throw new Exception("No tiene saldo para transferir", "111");
        }


        /* Lanza una excepción si la cantidad a transferir es cero. */
        if ($cant2 == 0) {

            throw new Exception("No tiene saldo para transferir", "111");
        }
        //$PuntoVentaMySqlDAO->update($PuntoVenta);
        // $PuntoVentaMySqlDAO->update($PuntoVentaSuper);
    } else {
        /* maneja excepciones de saldo insuficiente en transferencias monetarias. */

        throw new Exception("No tiene saldo para transferir", "111");
        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

        if ($Type == 1) {
            $cant = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);

        }

        if ($cant == 0) {
            throw new Exception("No tiene saldo para transferir", "111");
        }

    }
    //$PuntoVentaMySqlDAO->update($PuntoVenta);


    /* Crea un historial de usuario con datos de un registro de cupo. */
    $UsuarioHistorial = new UsuarioHistorial();
    $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
    $UsuarioHistorial->setDescripcion('');
    $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
    $UsuarioHistorial->setUsucreaId(0);
    $UsuarioHistorial->setUsumodifId(0);

    /* inserta un registro de historial de usuario con datos específicos. */
    $UsuarioHistorial->setTipo(60);
    $UsuarioHistorial->setValor($CupoLog->getValor());
    $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


    /* asigna un tipo basado en la condición de tipo ID y crea un objeto FlujoCaja. */
    $tipoConce = 'E';

    if ($CupoLog->getTipoId() == "E") {
        $tipoConce = 'S';
    }

    //falla el proceso
    //flujo de caja realizar proceso
    $FlujoCaja = new FlujoCaja();

    /* configura un objeto FlujoCaja con datos de fecha, hora, usuario y monto. */
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($CupoLog->getUsucreaId());
    $FlujoCaja->setTipomovId('E');
    $FlujoCaja->setValor($Amount);
    $FlujoCaja->setTicketId("");

    /* Código que configura un objeto FlujoCaja con parámetros de usuario y método de pago. */
    $FlujoCaja->setRecargaId(0);
    $FlujoCaja->setMandante($UsuarioPuntoVenta->mandante);
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setFormapago1Id(1);
    $FlujoCaja->setCuentaId('0');

    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }


    /* inicializa valores de flujo de caja en cero si están vacíos. */
    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }

    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }


    /* Validaciones para asignar valores predeterminados si son vacíos en un objeto. */
    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId('');
    }

    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }


    /* Verifica y asigna valor al IVA, luego inicializa objeto para acceso a datos. */
    if ($FlujoCaja->getValorIva() == "") {
        $FlujoCaja->setValorIva(0);
    }
    $FlujoCaja->setcupologId($CupoId);

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


    /* Inserta un flujo de caja y crea un historial de usuario asociado. */
    $FlujoCajaMySqlDAO->insert($FlujoCaja);

    $UsuarioHistorial2 = new UsuarioHistorial();
    $UsuarioHistorial2->setUsuarioId($PuntoVentaSuper->usuarioId);
    $UsuarioHistorial2->setDescripcion('');
    $UsuarioHistorial2->setMovimiento($tipoConce);

    /* Configura propiedades de un historial de usuario y crea un DAO para MySQL. */
    $UsuarioHistorial2->setUsucreaId(0);
    $UsuarioHistorial2->setUsumodifId(0);
    $UsuarioHistorial2->setTipo(60);
    $UsuarioHistorial2->setValor($CupoLog->getValor());
    $UsuarioHistorial2->setExternoId($CupoLog->getCupologId());

    $UsuarioHistorialMySqlDAO2 = new UsuarioHistorialMySqlDAO($Transaction);

    /* Inserta un historial de usuario y confirma la transacción en una base de datos. */
    $UsuarioHistorialMySqlDAO2->insert($UsuarioHistorial2, '1');


    $Transaction->commit();


    $Usuario = new Usuario($PuntoVenta->usuarioId);

    /* Se crea un objeto 'Mandante' y se definen variables para un documento PDF. */
    $Mandante = new Mandante($Usuario->mandante);

    $PDFtitulo = "TRANSFERENCIA DE SALDO";
    $PDFsoporteNo = "Transferencia No.";
    $PDFusuario = "Usuario:";
    $PDFvalor = "Valor:";

    /* Código define variables para etiquetas PDF y asigna tipo de saldo según condición. */
    $PDFfecha = "Fecha:";
    $PDFTipodesaldo = "Tipo de saldo:";

    $PDFTipofinal = "";

    if ($tipoCupo == "A") {
        $PDFTipofinal = "Saldo juego";

    }


    /* asigna "Saldo recargas" a $PDFTipofinal si $tipoCupo es "R". */
    if ($tipoCupo == "R") {
        $PDFTipofinal = "Saldo recargas";
    }

    if ($_SESSION["idioma"] == "en") {


        /* Variables en PHP para generar un PDF sobre transferencias de saldo. */
        $PDFtitulo = "BALANCE TRANSFER";
        $PDFsoporteNo = "Transfer No.";
        $PDFusuario = "User:";
        $PDFvalor = "Amount:";
        $PDFfecha = "Date:";
        $PDFTipodesaldo = "Balance type:";


        /* asigna un título de PDF basado en el tipo de cupo ingresado. */
        if ($tipoCupo == "A") {
            $PDFTipofinal = "Game balance";

        }

        if ($tipoCupo == "R") {
            $PDFTipofinal = "Deposit balance";
        }


    }
    try {

        if (true) {


            /* Se crea un clasificador y plantilla para generar un PDF con un logo. */
            $Clasificador = new Clasificador("", "PLPDFTRANS");

            $Plantilla = new Plantilla("", $Clasificador->clasificadorId, "3", $Mandante->mandante, strtolower($Usuario->idioma));

            $pdf = $Plantilla->plantilla;
            $logoParam = '<img style=" padding-left: 20px;" src="' . $Mandante->logoPdf . '" alt="logo">';


            /* Reemplaza marcadores en un PDF con datos específicos de un objeto CupoLog. */
            $pdf = str_replace("[id]", $CupoLog->getCupologId(), $pdf);
            $pdf = str_replace("[date]", $CupoLog->getFechaCrea(), $pdf);
            $pdf = str_replace("[user_id]", $CupoLog->getUsuarioId(), $pdf);
            $pdf = str_replace("[type_balance]", $PDFTipofinal, $pdf);
            $pdf = str_replace("[amount]", $CupoLog->getValor() . ' ' . $Usuario->moneda, $pdf);
            $pdf = str_replace("[userfrom_id]", $userfrom, $pdf);

            /* reemplaza marcadores en un PDF y configura la librería mPDF. */
            $pdf = str_replace("[name_userfrom_id]", $UsuarioFrom->nombre, $pdf);
            $pdf = str_replace("[logo]", $logoParam, $pdf);


            // require_once "mpdf6.1/mpdf.php";
// $mpdf = new mPDF('c', array(80, 150));
            $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L', 'tempDir' => '/tmp']);
            //$mpdf = new mPDF('c', 'A4-L');


            /* Configura márgenes, muestra el PDF y lo guarda en una ruta específica. */
            $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

            $mpdf->SetDisplayMode('fullpage', 'two');
            $mpdf->WriteHTML($pdf);

            $mpdf->Output('/tmp' . "/mpdfMDA" . $CupoLog->getCupologId() . ".pdf", "F");


            /* genera un PDF codificado en base64 desde un archivo en el sistema. */
            $path = '/tmp' . '/mpdfMDA' . $CupoLog->getCupologId() . '.pdf';

            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

            $encoded_html = base64_encode($pdf);


            /* codifica datos en base64 y los almacena en un arreglo de respuesta. */
            $response["Pdf"] = base64_encode($data);
            $response["PdfPOS"] = base64_encode($data);
        }
    } catch (Exception $e) {
        /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


    }
}


/* define una respuesta JSON indicando éxito en una operación sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];

