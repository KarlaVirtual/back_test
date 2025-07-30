<?php

use Backend\dto\PaisMandante;
use Dompdf\Dompdf;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Template;
use Backend\dto\FlujoCaja;
use Backend\dto\PuntoVenta;
use Backend\dto\Clasificador;
use Backend\dto\UsuarioConfig;
use Backend\dto\TransjuegoInfo;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransjuegoLog;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

/**
 * UserManagement/PayWinningTicketVirtual
 *
 * Realiza el pago de un ticket ganador virtual, validando condiciones y generando un recibo.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param float $params->Amount Monto del premio.
 * @param string $params->NoTicket Número del ticket.
 * @param string $params->ClaveTicket Clave asociada al ticket.
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta generada.
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo, si los hay.
 *  - Pdf (string): Documento PDF codificado en base64.
 *  - PdfPOS (string): Documento PDF para impresión en punto de venta.
 */


/* activa la depuración si se cumple una condición específica. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

/* Se crea un objeto Usuario y se asignan valores a variables desde $params. */
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

$Amount = $params->Amount;
//$Amount = -$Amount;
$Id = $params->NoTicket;
$Clave = $params->ClaveTicket;


/* Se depuran caracteres de dos variables y se asigna un tipo 'E'. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

$Id = $ConfigurationEnvironment->DepurarCaracteres("CASI_" . $Id);
$Clave = $ConfigurationEnvironment->DepurarCaracteres($Clave);

$tipo = 'E';


/* valida parámetros y genera un ticket con un prefijo si están completos. */
if ($Id == "" || $Clave == "") {
    throw new Exception("Error en los parametros enviados", 100001);
}

$ticket = $Id;

$prefix = "CASI_";

/* verifica si el ID comienza con un prefijo y luego lo procesa. */
if (strpos($Id, $prefix) === 0) {
    $Id = substr($Id, strlen($prefix));
}

$TransaccionJuego = new TransaccionJuego();
$TransaccionJuego = $TransaccionJuego->checkTicket($Id, $Clave);


/* crea usuarios y verifica la existencia de un ticket de juego. */
$UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);

if ($TransaccionJuego == null) {
    throw new Exception("No existe Ticket", 24);
}

if (($Usuario->puntoventaId == $UsuarioPuntoVenta->puntoventaId && $Usuario->mandante == $UsuarioPuntoVenta->mandante) || $UsuarioPuntoVenta->mandante == '1' || $UsuarioPuntoVenta->mandante == '2') {


    /* Valida el estado y premio de la transacción, lanzando excepciones en caso de error. */
    if ($TransaccionJuego->estado != "I") {
        throw new Exception("Error en los parametros enviados", 100001);
    }

    if ($TransaccionJuego->premiado == "N") {
        throw new Exception("Error en los parametros enviados", 100001);
    }


    /* verifica un premio y lanza una excepción si ya fue pagado. */
    if ($TransaccionJuego->premioPagado == "S") {
        throw new Exception("Error en los parametros enviados", 100001);
    }

    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);
    $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);


    /* Verifica condiciones de pago antes de procesar un premio en la transacción. */
    if ($UsuarioConfig->maxpagoPremio != "" && $UsuarioConfig->maxpagoPremio != "0") {
        if (floatval($UsuarioConfig->maxpagoPremio) < floatval($TransaccionJuego->valorPremio)) {
            throw new Exception("No es permitido pagar tickets por este valor", 100001);
        }
    }

    $rowsUpdate = 0;


    /* Código para inicializar objetos DAO y definir variables de beneficiario en PHP. */
    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

    $beneficiarioId = 0;
    $tipoBeneficiario = 0;

    /* Inicializa una variable y crea objetos de ProductoMandante y TransjuegoInfo. */
    $impuesto = 0;

    $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);

    $TransjuegoInfo = new TransjuegoInfo();

    if ($PuntoVenta->impuestoPagopremio != '' && $PuntoVenta->impuestoPagopremio != '0') {


        /* Calcula el impuesto sobre el premio basado en el tipo de usuario. */
        $impuestoPorcSobrePremio = $PuntoVenta->impuestoPagopremio;

        if ($Usuario->mandante == 16) {
            $paraImpuesto = floatval($TransaccionJuego->valorPremio);
        } else {
            $paraImpuesto = floatval($TransaccionJuego->valorPremio) - floatval($TransaccionJuego->valorTicket);
        }

        /* Calcula el impuesto basado en un valor condicionado y asigna un ID de producto. */
        if ($paraImpuesto < 0) {
            $impuesto += 0;
        } else {
            $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
        }

        $TransjuegoInfo->setProductoId($ProductoMandante->prodmandanteId);

        /* Configura información de transacción relacionada con impuestos en un sistema. */
        $TransjuegoInfo->setTransaccionId(0);
        $TransjuegoInfo->setTipo("IMPUESTO");
        $TransjuegoInfo->setDescripcion($UsuarioMandante->usumandanteId);
        $TransjuegoInfo->setValor($impuestoPorcSobrePremio);
        $TransjuegoInfo->setTransapiId(0);
        $TransjuegoInfo->setUsucreaId(0);

        /* establece valores en un objeto antes de insertarlo en la base de datos. */
        $TransjuegoInfo->setUsumodifId(0);
        $TransjuegoInfo->setIdentificador(0);
        $TransjuegoInfo->setTransjuegoId($Id);
        $TransjuegoInfo->insert($Transaction);
    } else {

        try {

            /* crea un clasificador y obtiene un valor de impuesto basado en detalles del mandante. */
            $Clasificador = new Clasificador("", "TAXBETPAYPRIZE");
            $minimoMontoPremios = 0;

            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

            $impuestoPorcSobreApuesta = $MandanteDetalle->getValor();


            /* Cálculo del impuesto y configuración de información de transacción de juego. */
            $impuesto = floatval($impuestoPorcSobreApuesta / 100) * floatval($TransaccionJuego->valorTicket);

            $TransjuegoInfo->setProductoId($ProductoMandante->prodmandanteId);
            $TransjuegoInfo->setTransaccionId(0);
            $TransjuegoInfo->setTipo("IMPUESTO");
            $TransjuegoInfo->setDescripcion($UsuarioMandante->usumandanteId);

            /* establece valores en un objeto de transacción de juego. */
            $TransjuegoInfo->setValor($MandanteDetalle->manddetalleId);
            $TransjuegoInfo->setTransapiId(0);
            $TransjuegoInfo->setUsucreaId(0);
            $TransjuegoInfo->setUsumodifId(0);
            $TransjuegoInfo->setIdentificador(0);
            $TransjuegoInfo->setTransjuegoId($Id);

            /* Inserta una transacción en la base de datos utilizando el objeto TransjuegoInfo. */
            $TransjuegoInfo->insert($Transaction);
        } catch (Exception $e) {
            /* Captura excepciones en código PHP sin realizar ninguna acción específica dentro del bloque. */

        }


        /* Calcula el impuesto basado en condiciones específicas del usuario y transacción. */
        if ($UsuarioMandante->getMandante() == 1 && $TransaccionJuego->estado == 'A') {
            $impuesto = 0;
        }

        if ($Usuario->puntoventaId != "236695") {

            try {

                /* Crea un clasificador y obtiene el valor del impuesto sobre premios. */
                $Clasificador = new Clasificador("", "TAXPRIZEBETSHOP");
                $minimoMontoPremios = 0;

                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                $impuestoPorcSobrePremio = $MandanteDetalle->getValor();


                /* Calcula el impuesto sobre ganancias de un juego restando ticket de premio. */
                $paraImpuesto = floatval($TransaccionJuego->valorPremio) - floatval($TransaccionJuego->valorTicket);

                if ($paraImpuesto < 0) {
                    $impuesto += 0;
                } else {
                    $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
                }


                /* Se configuran propiedades de un objeto TransjuegoInfo relacionadas con impuestos y transacciones. */
                $TransjuegoInfo->setProductoId($ProductoMandante->prodmandanteId);
                $TransjuegoInfo->setTransaccionId(0);
                $TransjuegoInfo->setTipo("IMPUESTO");
                $TransjuegoInfo->setDescripcion($UsuarioMandante->usumandanteId);
                $TransjuegoInfo->setValor($impuestoPorcSobrePremio);
                $TransjuegoInfo->setTransapiId(0);

                /* inicializa valores y luego inserta información de transacciones. */
                $TransjuegoInfo->setUsucreaId(0);
                $TransjuegoInfo->setUsumodifId(0);
                $TransjuegoInfo->setIdentificador(0);
                $TransjuegoInfo->setTransjuegoId($Id);
                $TransjuegoInfo->insert($Transaction);
            } catch (Exception $e) {
                /* Bloque para manejar excepciones en PHP, sin realizar ninguna acción específica. */

            }
        }
        if ($Usuario->paisId == "94" && false) {

            try {


                /* Calcula un impuesto del 3% sobre el valor del premio en una transacción de juego. */
                $impuestoPorcSobrePremio = 3;

                $paraImpuesto = floatval($TransaccionJuego->valorPremio);
                if ($paraImpuesto < 0) {
                    $impuesto += 0;
                } else {
                    /* Calcula el impuesto aplicando un porcentaje sobre un monto determinado. */

                    $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
                }


                /* configura un objeto TransjuegoInfo con datos de impuestos y transacciones. */
                $TransjuegoInfo->setProductoId($ProductoMandante->prodmandanteId);
                $TransjuegoInfo->setTransaccionId(0);
                $TransjuegoInfo->setTipo("IMPUESTO");
                $TransjuegoInfo->setDescripcion($UsuarioMandante->usumandanteId);
                $TransjuegoInfo->setValor($impuesto);
                $TransjuegoInfo->setTransapiId(0);

                /* configura información de transacción y la inserta en una base de datos. */
                $TransjuegoInfo->setUsucreaId(0);
                $TransjuegoInfo->setUsumodifId(0);
                $TransjuegoInfo->setIdentificador(0);
                $TransjuegoInfo->setTransjuegoId($Id);
                $TransjuegoInfo->insert($Transaction);

                $impuesto2 = 0;


                /* Calcula el impuesto sobre el premio, asegurando que no sea negativo. */
                $impuestoPorcSobrePremio = 10;

                $paraImpuesto = floatval($TransaccionJuego->valorPremio) - floatval($impuesto);
                if ($paraImpuesto < 0) {
                    $impuesto2 += 0;
                } else {
                    /* Calcula un impuesto proporcional sobre un premio y lo suma a un total acumulado. */

                    $impuesto2 += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
                }


                /* Configura información de una transacción relacionada con impuestos y productos mandantes. */
                $TransjuegoInfo->setProductoId($ProductoMandante->prodmandanteId);
                $TransjuegoInfo->setTransaccionId(0);
                $TransjuegoInfo->setTipo("IMPUESTO");
                $TransjuegoInfo->setDescripcion($UsuarioMandante->usumandanteId);
                $TransjuegoInfo->setValor($impuesto2);
                $TransjuegoInfo->setTransapiId(0);

                /* Se establecen valores y se inserta información en una transacción, luego se suman impuestos. */
                $TransjuegoInfo->setUsucreaId(0);
                $TransjuegoInfo->setUsumodifId(0);
                $TransjuegoInfo->setIdentificador(0);
                $TransjuegoInfo->setTransjuegoId($Id);
                $TransjuegoInfo->insert($Transaction);

                $impuesto = $impuesto + $impuesto2;
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP que ignora errores sin tomar acciones adicionales. */

            }
        }

        if (in_array($Usuario->puntoventaId, array("67561", "129971", "135893", "156774", "161521", "153389", "161529", "147670", "145514", "135930", "147676", "157928", "157933", "140996", "140998", "135893", "156973", "132134", "152495", "164397", "164410", "145483", "135871", "135876", "166627", "174951", "174936", "135876", "166627", "174928", "176650", "513762")) && false) {


            /* Calcula el impuesto sobre el premio restando el valor del ticket, asegurando no ser negativo. */
            $impuestoPorcSobrePremio = 7;

            $paraImpuesto = floatval($TransaccionJuego->valorPremio) - floatval($TransaccionJuego->valorTicket);
            if ($paraImpuesto < 0) {
                $impuesto += 0;
            } else {
                /* Cálculo del impuesto basado en un porcentaje sobre un premio. */

                $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
            }


            /* establece propiedades de un objeto TransjuegoInfo relacionado con un impuesto. */
            $TransjuegoInfo->setProductoId($ProductoMandante->prodmandanteId);
            $TransjuegoInfo->setTransaccionId(0);
            $TransjuegoInfo->setTipo("IMPUESTO");
            $TransjuegoInfo->setDescripcion($UsuarioMandante->usumandanteId);
            $TransjuegoInfo->setValor($impuestoPorcSobrePremio);
            $TransjuegoInfo->setTransapiId(0);

            /* Código para inicializar y guardar información de una transacción de juego. */
            $TransjuegoInfo->setUsucreaId(0);
            $TransjuegoInfo->setUsumodifId(0);
            $TransjuegoInfo->setIdentificador(0);
            $TransjuegoInfo->setTransjuegoId($Id);
            $TransjuegoInfo->insert($Transaction);
        }
    }


    /* Actualiza el estado del premio pagado en la base de datos, validando condiciones específicas. */
    $TransaccionJuego->transjuegoId = $TransaccionJuego->transjuegoId;
    $TransaccionJuego->premioPagado = 'S';
    $TransaccionJuego->fechaPago = (date('Y-m-d H:i:s', time()));
    $rowsUpdate = $TransaccionJuegoMySqlDAO->updatePremioPago($TransaccionJuego, " AND premio_pagado='N' AND premiado='S' AND estado='I' ");

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", 100000);
    }


    /* Calcula el valor a pagar y registra información en FlujoCaja. */
    $rowsUpdate = 0;

    $ValorAPagar = $TransaccionJuego->valorPremio - $impuesto;
    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));

    /* establece valores para un objeto de FlujoCaja relacionado con una transacción. */
    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
    $FlujoCaja->setTipomovId('S');
    $FlujoCaja->setValor($ValorAPagar);
    $FlujoCaja->setTicketId($ticket);
    $FlujoCaja->setCuentaId('0');
    $FlujoCaja->setMandante($TransaccionJuego->mandante);

    /* Configuración de propiedades en un objeto FlujoCaja con valores predeterminados y condiciones. */
    $FlujoCaja->setValorIva($impuesto);
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setRecargaId('0');

    if ($FlujoCaja->getFormapago1Id() == "") {
        $FlujoCaja->setFormapago1Id(0);
    }

    /* Verifica campos vacíos y asigna valor 0 en caso de ser necesario. */
    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }
    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }

    /* asigna valor cero a propiedades vacías del objeto FlujoCaja. */
    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }
    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }

    /* verifica y establece el porcentaje de IVA en $FlujoCaja. */
    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }

    $FlujoCaja->setDevolucion('');

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

    /* Intenta insertar un flujo de caja y maneja posibles errores. */
    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", 100000);
    }

    $rowsUpdate = 0;

    /* Valida perfil de usuario y actualiza balance; registra historial de transacciones si es exitoso. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3" or $_SESSION["win_perfil"] == "PUNTOVENTA" or $_SESSION["win_perfil"] == "CAJERO") {

        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($ValorAPagar, $Transaction);
    }

    if ($rowsUpdate > 0) {

        $TransjuegoLog = new TransjuegoLog("", $Id, 'CREDIT');

        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(30);
        $UsuarioHistorial->setValor($ValorAPagar);
        $UsuarioHistorial->setExternoId($TransjuegoLog->transjuegologId);

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

        $Transaction->commit();
    } else {
        /* Lanza una excepción con un mensaje de error y un código específico. */

        throw new Exception("Error General", 100000);
    }


    /* Se crean instancias de objetos Mandante y TransjuegoInfo utilizando parámetros específicos. */
    $Mandante = new Mandante($Usuario->mandante);

    $TransjuegoInfo = new TransjuegoInfo("", $Id, "USUARIORELACIONADO");

    try {


        /* Se crea un clasificador y un template, generando un HTML con datos específicos. */
        $Clasificador = new Clasificador("", "TEMPPAGOVIRTUAL");
        $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
        $html_barcode = $Template->templateArray;

        if ($html_barcode != '') {


            /* Genera un código HTML que incluye un recibo y un logo en un formato específico. */
            if ($PuntoVenta->headerRecibopagopremio != '') {
                $headerRecibopagopremio = $PuntoVenta->headerRecibopagopremio;
                $html_barcode = '
                <div style="text-align:center;font-size:12px;">' . nl2br($headerRecibopagopremio) . '</div>' . $html_barcode;
            }

            $html_barcode = str_replace("#LogoMandante#", '<img src="' . $Mandante->logoPdf . '" alt="logo-doradobet" style="width: 90px; padding-left: 2px;" />', $html_barcode);

            /* Reemplaza marcadores en una plantilla HTML con información de transacciones y usuario. */
            $html_barcode = str_replace("#ticketnumber#", $Id, $html_barcode);
            $html_barcode = str_replace("#dateTicket#", $TransaccionJuego->fechaPago, $html_barcode);
            $html_barcode = str_replace("#idpointsale#", $UsuarioPuntoVenta->nombre, $html_barcode);
            $html_barcode = str_replace("#user#", $TransjuegoInfo->valor, $html_barcode);
            $html_barcode = str_replace("#value#", $Usuario->moneda . ' ' . number_format($TransaccionJuego->valorPremio, 2), $html_barcode);
            $html_barcode = str_replace("#tax#", $Usuario->moneda . ' ' . number_format($impuesto, 2), $html_barcode);

            /* Reemplaza placeholders en el HTML para generar un código de barras personalizado. */
            $html_barcode = str_replace("#totalvalue#", $Usuario->moneda . ' ' . number_format($ValorAPagar, 2), $html_barcode);
            $html_barcode = str_replace("#partnert#", $Mandante->descripcion, $html_barcode);
            $html_barcode = str_replace("#barcode#", '<barcode code="' . $Id . '" type="I25" class="barcode" />', $html_barcode);
            if ($Usuario->paisId == 173 && $Usuario->mandante == '0') {
                $html_barcode = str_replace("#partnertRuc#", "Interplay Word SAC RUC:<br>20602190103", $html_barcode);
            }

            /* Condicionalmente, se calcula un valor y se añade un pie de recibo. */
            if ($Usuario->mandante == 0 && $Usuario->paisId == 2) {
                $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
                $totalvalue2 = ($ValorAPagar * $PaisMandante->trmNio);
                $html_barcode = str_replace("#totalvalue2#", $totalvalue2, $html_barcode);
            }

            if ($PuntoVenta->footerRecibopagopremio != '') {
                $footerRecibopagopremio = $PuntoVenta->footerRecibopagopremio;
                $html_barcode .= '
                <div style="text-align:center;font-size:12px;">' . nl2br($footerRecibopagopremio) . '</div>';
            }


            /* Genera un documento PDF utilizando mPDF con un formato específico y contenido HTML. */
            $html_barcode .= $Template->templateHtmlCSSPrint;

            // $dompdf = new Dompdf();
            // $dompdf->loadHtml($html_barcode);
            // $width = 80;
            // $height = 150;
            // $paper_format = array( 0, 0, ($width/25.4) * 72, ($height/25.4) * 72 );
            // $dompdf->setPaper($paper_format);
            // $dompdf->render();
            // $data = $dompdf->output();

            $mpdf = new \Mpdf\Mpdf(['format' => array(80, 200), 'tempDir' => '/tmp']);

            /* Genera un PDF con márgenes espejo y lo guarda en la carpeta temporal. */
            $mpdf->mirrorMargins = 1;
            $mpdf->SetDisplayMode('fullpage', 'two');
            $mpdf->WriteHTML($html_barcode);
            $mpdf->Output('/tmp' . "/mpdfPNT" . $ticket . ".pdf", "F");
            $path = '/tmp' . '/mpdfPNT' . $ticket . '.pdf';
            $type = pathinfo($path, PATHINFO_EXTENSION);

            /* Convierte el contenido de un archivo PDF a formato base64 para su respuesta. */
            $data = file_get_contents($path);
            $base64 = 'data:application/pdf;base64,' . base64_encode($data);

            $response["Pdf"] = base64_encode($data);
            $response["PdfPOS"] = base64_encode($data);
        }
    } catch (Exception $e) {
        /* Captura excepciones y lanza una nueva con un mensaje y código específico. */

        throw new Exception("Error General", 100000);
    }
} else {
    /* Lanza una excepción con mensaje "Error General" y código 100000 en caso de error. */

    throw new Exception("Error General", 100000);
}


/* crea una respuesta sin errores indicando éxito en la operación realizada. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];
