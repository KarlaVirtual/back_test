<?php

use Backend\dto\PaisMandante;
use Dompdf\Dompdf;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Template;
use Backend\dto\FlujoCaja;
use Backend\dto\PuntoVenta;
use Backend\dto\ItTicketEnc;
use Backend\dto\Clasificador;
use Backend\dto\UsuarioConfig;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\ItTicketEncMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

/**
 * UserManagement/PayWinningTicket
 *
 * Realiza el pago de un ticket ganador, validando condiciones y generando un recibo.
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


/* Se crean instancias de usuario y se asignan valores desde parámetros recibidos. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

$Amount = $params->Amount;
//$Amount = -$Amount;
$Id = $params->NoTicket;

/* depura caracteres de un ticket y lo asigna a una variable. */
$Clave = $params->ClaveTicket;

$ConfigurationEnvironment = new ConfigurationEnvironment();

$Id = $ConfigurationEnvironment->DepurarCaracteres($Id);
$Clave = $ConfigurationEnvironment->DepurarCaracteres($Clave);


/* valida parámetros y crea una instancia de 'ItTicketEnc'. */
$tipo = 'E';

if ($Id == "" || $Clave == "") {
    throw new Exception("Error en los parametros enviados", "100001");
}

$ItTicketEnc = new ItTicketEnc();


/* verifica un ticket y crea un usuario si el ticket existe. */
$ItTicketEnc = $ItTicketEnc->checkTicket($Id, $Clave);

$Usuario = new  Usuario($ItTicketEnc->usuarioId);

if ($ItTicketEnc == null) {
    throw new Exception("No existe Ticket", "24");
}


if (($Usuario->puntoventaId == $UsuarioPuntoVenta->puntoventaId && $Usuario->mandante == $UsuarioPuntoVenta->mandante) || $UsuarioPuntoVenta->mandante == '1' || $UsuarioPuntoVenta->mandante == '2') {


    /* Valida condiciones del ticket, lanzando excepciones si no se cumplen. */
    if ($ItTicketEnc->estado != "I") {
        throw new Exception("Error en los parametros enviados", "100001");
    }

    if ($ItTicketEnc->premiado == "N") {
        throw new Exception("Error en los parametros enviados", "100001");
    }


    /* Lanza excepciones si el premio está pagado o si el ticket está caducado. */
    if ($ItTicketEnc->premioPagado == "S") {
        throw new Exception("Error en los parametros enviados", "100001");
    }

    if ($ItTicketEnc->caducado == "S") {
        throw new Exception("Error en los parametros enviados", "100001");
    }


    /* Validación del pago de tickets según configuración del usuario en el punto de venta. */
    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);
    $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

    if ($UsuarioConfig->maxpagoPremio != "" && $UsuarioConfig->maxpagoPremio != "0") {
        if (floatval($UsuarioConfig->maxpagoPremio) < floatval($ItTicketEnc->valor)) {
            throw new Exception("No es permitido pagar tickets por este valor", "100001");
        }
    }


    /* Se inicializan contadores y se configuran objetos para manejar transacciones en MySQL. */
    $rowsUpdate = 0;


    $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();
    $Transaction = $ItTicketEncMySqlDAO->getTransaction();
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);


    /* Variables inicializadas en PHP para almacenar datos relacionados con un beneficiario y un impuesto. */
    $beneficiarioId = 0;
    $tipoBeneficiario = 0;
    $impuesto = 0;

    if ($PuntoVenta->impuestoPagopremio != '' && $PuntoVenta->impuestoPagopremio != '0') {

        /* Se inicializa un objeto y se calcula el impuesto sobre el premio si corresponde. */
        $ItTicketEncInfo1 = new ItTicketEncInfo1();

        $impuestoPorcSobrePremio = $PuntoVenta->impuestoPagopremio;

        if ($Usuario->mandante == 16) {
            //$impuesto += floatval($impuestoPorcSobrePremio / 100) * floatval($ItTicketEnc->vlrPremio);
            $paraImpuesto = floatval($ItTicketEnc->vlrPremio);
        } else {
            /* Calcula el valor del premio menos la apuesta para el impuesto correspondiente. */

            //$impuesto += floatval($impuestoPorcSobrePremio / 100) * floatval($ItTicketEnc->vlrPremio);
            $paraImpuesto = floatval($ItTicketEnc->vlrPremio) - floatval($ItTicketEnc->vlrApuesta);
        }

        /* Calcula el impuesto basado en una condición y asigna un ID de ticket. */
        if ($paraImpuesto < 0) {
            $impuesto += 0;
        } else {
            $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
        }

        $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;

        /* Se asignan valores a un objeto y se inicializa un DAO para interacción con MySQL. */
        $ItTicketEncInfo1->tipo = 'IMPUESTO';
        $ItTicketEncInfo1->valor = $impuestoPorcSobrePremio;
        $ItTicketEncInfo1->fechaCrea = date("Y-m-d H:i:s");
        $ItTicketEncInfo1->fechaModif = date("Y-m-d H:i:s");

        $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);

        /* Inserta un registro de "ItTicketEncInfo1" en la base de datos MySQL. */
        $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
    } else {

        /* Se crea una nueva instancia de la clase ItTicketEncInfo1. */
        $ItTicketEncInfo1 = new ItTicketEncInfo1();


        try {

            /* Crea un clasificador y obtiene un impuesto sobre apuesta del detalle mandante. */
            $Clasificador = new Clasificador("", "TAXBETPAYPRIZE");
            $minimoMontoPremios = 0;

            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

            $impuestoPorcSobreApuesta = $MandanteDetalle->getValor();


            /* Calcula el impuesto sobre la apuesta y asigna datos a un objeto de ticket. */
            $impuesto = floatval($impuestoPorcSobreApuesta / 100) * floatval($ItTicketEnc->vlrApuesta);

            $ItTicketEncInfo1 = new ItTicketEncInfo1();

            $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;
            $ItTicketEncInfo1->tipo = 'IMPUESTO';

            /* Insertar información en base de datos con datos actuales y detalle específico. */
            $ItTicketEncInfo1->valor = $MandanteDetalle->manddetalleId;
            $ItTicketEncInfo1->fechaCrea = date("Y-m-d H:i:s");
            $ItTicketEncInfo1->fechaModif = date("Y-m-d H:i:s");

            $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);
            $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
        } catch (Exception $e) {
            /* Bloque para manejar excepciones en PHP, sin acciones específicas definir. */

        }


        /* asigna cero a $impuesto si se cumplen ciertas condiciones de usuario y estado. */
        if ($UsuarioMandante->getMandante() == 1 && $ItTicketEnc->betStatus == 'A') {
            $impuesto = 0;
        }

        if ($Usuario->puntoventaId != "236695") {

            try {

                /* inicializa un clasificador y obtiene el impuesto sobre premios. */
                $Clasificador = new Clasificador("", "TAXPRIZEBETSHOP");
                $minimoMontoPremios = 0;

                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                $impuestoPorcSobrePremio = $MandanteDetalle->getValor();


                /* Calcula el impuesto sobre la diferencia entre premio y apuesta, si es positiva. */
                $paraImpuesto = floatval($ItTicketEnc->vlrPremio) - floatval($ItTicketEnc->vlrApuesta);
                if ($paraImpuesto < 0) {
                    $impuesto += 0;
                } else {
                    $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
                }


                /* Se crea un registro de impuesto para un ticket con fechas actuales. */
                $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;
                $ItTicketEncInfo1->tipo = 'IMPUESTO';
                $ItTicketEncInfo1->valor = $impuestoPorcSobrePremio;
                $ItTicketEncInfo1->fechaCrea = date("Y-m-d H:i:s");
                $ItTicketEncInfo1->fechaModif = date("Y-m-d H:i:s");

                $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);

                /* Inserta información de tickets en la base de datos usando MySQL. */
                $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
            } catch (Exception $e) {
                /* Bloque que captura excepciones en PHP, pero no maneja el error. */

            }
        }
        if ($Usuario->paisId == "94" && false) {

            try {


                /* Calcula un impuesto sobre un premio, ignorando valores negativos. */
                $impuestoPorcSobrePremio = 3;

                $paraImpuesto = floatval($ItTicketEnc->vlrPremio);
                if ($paraImpuesto < 0) {
                    $impuesto += 0;
                } else {
                    /* Calcula el impuesto aplicando un porcentaje sobre una cantidad base. */

                    $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
                }

                /* Crea un objeto de información de ticket con detalles sobre impuestos y fecha. */
                $ItTicketEncInfo1 = new ItTicketEncInfo1();

                $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;
                $ItTicketEncInfo1->tipo = 'IMPUESTO';
                $ItTicketEncInfo1->valor = $impuesto;
                $ItTicketEncInfo1->fechaCrea = date("Y-m-d H:i:s");

                /* registra la fecha actual y ejecuta una inserción en la base de datos. */
                $ItTicketEncInfo1->fechaModif = date("Y-m-d H:i:s");

                $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);
                $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);

                $impuesto2 = 0;


                /* Calcula el impuesto sobre el premio, asegurando que no sea negativo. */
                $impuestoPorcSobrePremio = 10;

                $paraImpuesto = floatval($ItTicketEnc->vlrPremio) - floatval($impuesto);
                if ($paraImpuesto < 0) {
                    $impuesto2 += 0;
                } else {
                    /* Suma un impuesto proporcional a un monto basado en la variable $impuestoPorcSobrePremio. */

                    $impuesto2 += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
                }


                /* Se crea un objeto de ticket con datos específicos y fecha actual. */
                $ItTicketEncInfo1 = new ItTicketEncInfo1();

                $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;
                $ItTicketEncInfo1->tipo = 'IMPUESTO2';
                $ItTicketEncInfo1->valor = $impuesto2;
                $ItTicketEncInfo1->fechaCrea = date("Y-m-d H:i:s");

                /* Asigna fecha actual, inserta datos en MySQL y suma impuestos. */
                $ItTicketEncInfo1->fechaModif = date("Y-m-d H:i:s");

                $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);
                $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);

                $impuesto = $impuesto + $impuesto2;
            } catch (Exception $e) {
                /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */

            }
        }

        if (in_array($Usuario->puntoventaId, array("67561", "129971", "135893", "156774", "161521", "153389", "161529", "147670", "145514", "135930", "147676", "157928", "157933", "140996", "140998", "135893", "156973", "132134", "152495", "164397", "164410", "145483", "135871", "135876", "166627", "174951", "174936", "135876", "166627", "174928", "176650", "513762")) && false) {


            /* Calcula un impuesto sobre un premio restando la apuesta, asegurando que no sea negativo. */
            $impuestoPorcSobrePremio = 7;

            //$impuesto += floatval($impuestoPorcSobrePremio / 100) * floatval($ItTicketEnc->vlrPremio);
            $paraImpuesto = floatval($ItTicketEnc->vlrPremio) - floatval($ItTicketEnc->vlrApuesta);
            if ($paraImpuesto < 0) {
                $impuesto += 0;
            } else {
                /* calcula un impuesto basado en un porcentaje sobre un premio. */

                $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
            }


            /* Código asigna valores a un objeto de ticket e inicializa su DAO. */
            $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;
            $ItTicketEncInfo1->tipo = 'IMPUESTO';
            $ItTicketEncInfo1->valor = $impuestoPorcSobrePremio;
            $ItTicketEncInfo1->fechaCrea = date("Y-m-d H:i:s");
            $ItTicketEncInfo1->fechaModif = date("Y-m-d H:i:s");

            $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);

            /* Inserta un objeto de información de ticket en la base de datos MySQL. */
            $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
        }
    }

    /* Actualiza los detalles de un ticket de pago con información de modificación y beneficiario. */
    $ItTicketEnc->premioPagado = 'S';
    $ItTicketEnc->usumodificaId = $UsuarioPuntoVenta->usuarioId;
    $ItTicketEnc->fechaModifica = date('Y-m-d H:i:s');
    $ItTicketEnc->fechaPago = date('Y-m-d');
    $ItTicketEnc->horaPago = date('H:i:s');
    $ItTicketEnc->beneficiarioId = $beneficiarioId;

    /* Actualizar datos en la base de datos y manejar errores si no se actualizan. */
    $ItTicketEnc->tipoBeneficiario = $tipoBeneficiario;
    $ItTicketEnc->impuesto = $impuesto;

    $rowsUpdate = $ItTicketEncMySqlDAO->update($ItTicketEnc, " AND premio_pagado='N' AND premiado='S' AND estado='I' ");

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000E");
    }


    /* Código que calcula un valor a pagar y establece datos en un objeto FlujoCaja. */
    $rowsUpdate = 0;

    $ValorAPagar = $ItTicketEnc->vlrPremio - $impuesto;
    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));

    /* Se configuran propiedades de un objeto FlujoCaja con datos de un usuario y transacción. */
    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
    $FlujoCaja->setTipomovId('S');
    $FlujoCaja->setValor($ValorAPagar);
    $FlujoCaja->setTicketId($ItTicketEnc->ticketId);
    $FlujoCaja->setCuentaId('0');
    $FlujoCaja->setMandante($ItTicketEnc->mandante);

    /* establece valores en un objeto FlujoCaja, incluyendo impuestos y métodos de pago. */
    $FlujoCaja->setValorIva($impuesto);
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setRecargaId('0');

    if ($FlujoCaja->getFormapago1Id() == "") {
        $FlujoCaja->setFormapago1Id(0);
    }


    /* establece valores predeterminados en caso de entradas vacías en un objeto. */
    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }

    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }


    /* verifica y establece valores predeterminados en objetos de FlujoCaja. */
    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }

    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }


    /* establece un porcentaje de IVA y una devolución en FlujoCaja. */
    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }

    $FlujoCaja->setDevolucion('');

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

    /* Inserta un flujo de caja y maneja errores de inserción en la base de datos. */
    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000F");
    }


    $rowsUpdate = 0;

    /* Valida el perfil del usuario y actualiza el balance, registrando un historial. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3" or $_SESSION["win_perfil"] == "PUNTOVENTA" or $_SESSION["win_perfil"] == "CAJERO") {

        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($ValorAPagar, $Transaction);
    }

    if ($rowsUpdate > 0) {

        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(20);
        $UsuarioHistorial->setValor($ValorAPagar);
        $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

        $Transaction->commit();
    } else {
        /* Lanza una excepción con un mensaje y un código específico en caso de error. */


        throw new Exception("Error General", "100000G");
    }


    /* Assigna valor a $UserPv si se obtiene correctamente, sino lo establece en 0. */
    $UserPv = 0;
    try {
        $ItTicketEncInfo1 = new ItTicketEncInfo1("", $ItTicketEnc->ticketId, "USUARIORELACIONADO");
        $UserPv = $ItTicketEncInfo1->valor;
    } catch (Exception $e) {
        $UserPv = 0;
    }


    /* Crea una instancia de la clase "Mandante" utilizando el mandante del usuario. */
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
        <tbody>
        ';

    /* Agrega un encabezado de recibo si el mandante es igual a 6. */
    if ($UsuarioPuntoVenta->mandante == 6) {
        $pdf = $pdf . '
        <tr style="width: 100%; display: inline-block;">
            <td colspan="2" align="center" valign="top" style="display: block;text-align:center;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE<br>PAGO PREMIO</font>
            </td>
        </tr>';
    } else {
        /* Genera una tabla en formato HTML para un recibo de pago con un logo. */

        $pdf = $pdf . '
        <tr style="width: 50%; display: inline-block;">
            <td align="center" valign="top"><img style="width: 140px; padding-left: 20px;"
                                                 src="' . $Mandante->logoPdf . '" alt="logo-doradobet">
            </td>
            <td align="center" valign="top" style="display: block;text-align:center;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE<br>PAGO PREMIO</font>
            </td>
        </tr>';
    }
    $pdf = $pdf . ' 
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Ticket No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $ItTicketEnc->ticketId . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha de Pago:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $ItTicketEnc->fechaPago . ' ' . $ItTicketEnc->horaPago . ' </font>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">ID DE CLIENTE:</font>
            </td>
            <td style="padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UserPv . ' </font>
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
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor Premio:</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($ItTicketEnc->vlrPremio, 2) . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($impuesto, 2) . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($ValorAPagar, 2) . '</font></td>
        </tr>
        
        
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
                </tbody>
    </table>
 <div style="text-align:center;font-size:12px;">' . $Mandante->descripcion . '</font>
        </div>
        ';

    if (strtolower($_SESSION["idioma"]) == "en") {
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
        <tbody>
        <tr style="width: 50%; display: inline-block;">
            <td align="center" valign="top"><img style="width: 140px; padding-left: 20px;"
                                                 src="' . $Mandante->logoPdf . '" alt="logo-doradobet">
            </td>
            <td align="center" valign="top" style="display: block;text-align:center;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">PRIZE PAYMENT<br>RECEIPT</font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Ticket No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $ItTicketEnc->ticketId . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Payment date:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $ItTicketEnc->fechaPago . ' ' . $ItTicketEnc->horaPago . ' </font>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">CUSTOMERID:</font>
            </td>
            <td style="padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UserPv . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Betshop:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioPuntoVenta->nombre . ' </font>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Prize Amount:</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($ItTicketEnc->vlrPremio, 2) . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Tax :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($impuesto, 2) . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Amount to deliver :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($ValorAPagar, 2) . '</font></td>
        </tr>
        
        
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
                </tbody>
    </table>
 <div style="text-align:center;font-size:12px;">' . $Mandante->descripcion . '</font>
        </div>
        ';
    }


    if ($Usuario->paisId == 2 && $Usuario->mandante == '0') {

        /* Calcula el total a pagar multiplicando un valor por la tasa de cambio de un país. */
        $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
        $totalvalue2 = ($ValorAPagar * $PaisMandante->trmNio);

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
        <tbody>
        ';

        /* Genera un encabezado de recibo de pago si el mandante es igual a 6. */
        if ($UsuarioPuntoVenta->mandante == 6) {
            $pdf = $pdf . '
        <tr style="width: 100%; display: inline-block;">
            <td colspan="2" align="center" valign="top" style="display: block;text-align:center;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE<br>PAGO PREMIO</font>
            </td>
        </tr>';
        } else {
            /* genera una tabla HTML con un logo y un título para un recibo. */

            $pdf = $pdf . '
        <tr style="width: 50%; display: inline-block;">
            <td align="center" valign="top"><img style="width: 140px; padding-left: 20px;"
                                                 src="' . $Mandante->logoPdf . '" alt="logo-doradobet">
            </td>
            <td align="center" valign="top" style="display: block;text-align:center;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE<br>PAGO PREMIO</font>
            </td>
        </tr>';
        }
        $pdf = $pdf . ' 
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Ticket No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $ItTicketEnc->ticketId . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha de Pago:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $ItTicketEnc->fechaPago . ' ' . $ItTicketEnc->horaPago . ' </font>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">ID DE CLIENTE:</font>
            </td>
            <td style="padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UserPv . ' </font>
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
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor Premio:</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($ItTicketEnc->vlrPremio, 2) . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($impuesto, 2) . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . number_format($ValorAPagar, 2) . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">NIO ' . number_format($totalvalue2, 2) . '</font></td>
        </tr>
        
        
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
                </tbody>
    </table>
 <div style="text-align:center;font-size:12px;">' . $Mandante->descripcion . '</font>
        </div>
        ';

    }

    /* Genera un pie de página en PDF según condiciones del usuario y punto de venta. */
    if ($Usuario->paisId == 173 && $Usuario->mandante == '0') {
        $pdf .= '
        <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
        </div>';
    }


    if ($PuntoVenta->footerRecibopagopremio != '') {

        $footerRecibopagopremio = $PuntoVenta->footerRecibopagopremio;
        $pdf .= '
        <div style="text-align:center;font-size:12px;">' . nl2br($footerRecibopagopremio) . '</div>';
    }


    /* Genera un PDF que incluye un código de barras centrado utilizando los datos del ticket. */
    $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
      <div class="barcodecell" style="  text-align: center;"><barcode code="' . $ItTicketEnc->ticketId . '" type="I25" class="barcode" /></div>
  </div>
    </div>
</div>
</body>
';


    // require_once __DIR__ . "/../../mpdf6.1/mpdf.php";


    /* Se configura una instancia de mPDF con márgenes espejados y modo de visualización. */
    $mpdf = new \Mpdf\Mpdf(['format' => array(80, 200), 'tempDir' => '/tmp']);
    //$mpdf = new mPDF('c', array(80, 200), 0, 0, 0, 0);
    //$mpdf = new mPDF('c', 'A4-L');

    $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

    $mpdf->SetDisplayMode('fullpage', 'two');

    // LOAD a stylesheet
    //$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
    //$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


    /* genera un PDF y lo guarda en un archivo temporal. */
    $mpdf->WriteHTML($pdf);

    $mpdf->Output('/tmp' . "/mpdfPNT" . $ItTicketEnc->ticketId . ".pdf", "F");

    $path = '/tmp' . '/mpdfPNT' . $ItTicketEnc->ticketId . '.pdf';

    $type = pathinfo($path, PATHINFO_EXTENSION);

    /* convierte un archivo a Base64 y lo almacena en un arreglo. */
    $data = file_get_contents($path);
    $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

    $response["Pdf"] = base64_encode($data);
    $response["PdfPOS"] = base64_encode($data);

    if ($UsuarioPuntoVenta->usuarioId == 25415 || $UsuarioPuntoVenta->mandante == 1 || $UsuarioPuntoVenta->mandante == 6 || $UsuarioPuntoVenta->mandante == 18) {

        try {

            /* crea un clasificador y un template para generar HTML de código de barras. */
            $Clasificador = new Clasificador("", "TEMREPAPREMIO");

            $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
            $html_barcode = $Template->templateHtml;
            $html_barcode .= $Template->templateHtmlCSSPrint;
            if ($html_barcode != '') {


                /* Condicional para agregar encabezado de recibo en formato HTML si existe. */
                if ($PuntoVenta->headerRecibopagopremio != '') {

                    $headerRecibopagopremio = $PuntoVenta->headerRecibopagopremio;
                    $html_barcode = '
        <div style="text-align:center;font-size:12px;">' . nl2br($headerRecibopagopremio) . '</div>' . $html_barcode;
                }


                /* Reemplaza variables en la plantilla de código HTML para generar un código de barra. */
                $html_barcode = str_replace("#ticketnumber#", $ItTicketEnc->ticketId, $html_barcode);

                $html_barcode = str_replace("#userid#", $UsuarioPuntoVenta->usuarioId, $html_barcode);
                $html_barcode = str_replace("#login#", $UsuarioPuntoVenta->login, $html_barcode);
                $html_barcode = str_replace("#name#", $UsuarioPuntoVenta->nombre, $html_barcode);
                $html_barcode = str_replace("#idpointsale#", $UsuarioPuntoVenta->usuarioId, $html_barcode);

                /* reemplaza marcadores en un HTML con datos de un usuario y ticket. */
                $html_barcode = str_replace("#namepointsale#", $UsuarioPuntoVenta->nombre, $html_barcode);
                $html_barcode = str_replace("#value#", $Usuario->moneda . ' ' . number_format($ItTicketEnc->vlrPremio, 2), $html_barcode);
                $html_barcode = str_replace("#creationdate#", $ItTicketEnc->fechaPago, $html_barcode);

                $html_barcode = str_replace("#tax#", $Usuario->moneda . ' ' . number_format($ItTicketEnc->impuesto, 2), $html_barcode);

                $html_barcode = str_replace("#totalvalue#", $Usuario->moneda . ' ' . number_format($ValorAPagar, 2), $html_barcode);


                /* Calcula y reemplaza valores en un código HTML basado en condiciones específicas del usuario. */
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


                // instantiate and use the dompdf class

                /* Se configura Dompdf para generar un documento PDF con tamaño específico. */
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html_barcode);

                // (Optional) Setup the paper size and orientation
                $width = 80; //mm!
                $height = 150; //mm!

                //convert mm to points

                /* Configura y genera un PDF a partir de HTML utilizando Dompdf en PHP. */
                $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);
                $dompdf->setPaper($paper_format);

                // Render the HTML as PDF
                $dompdf->render();

                // Output the generated PDF to Browser


                $data = $dompdf->output();


                /* Código para codificar datos PDF en Base64 y almacenarlos en un arreglo de respuesta. */
                $base64 = 'data:application/pdf;base64,' . base64_encode($data);

                $response["Pdf"] = base64_encode($data);
                $response["Pdf2"] = $pdf;
                $response["PdfPOS"] = base64_encode($data);
            }
        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP sin realizar ninguna acción específica. */

        }
    }
} else {
    /* lanza una excepción con un mensaje y código de error específico. */

    throw new Exception("Error General", "100000H");
}


/* establece una respuesta exitosa sin errores para una operación realizada. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];
