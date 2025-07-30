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
use Backend\dto\ItTicketEncInfo1;
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
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\ItTicketEncMySqlDAO;
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


/* Crea instancias de usuarios y perfiles a partir de los datos de sesión JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
$UsuarioPerfilUsuario = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());


/**
 * save_prize_payment
 *
 * Realizar pago de un ticket
 *
 * @param string $Id : Id del ticket
 * @param string $Clave : Clave del ticket
 *
 * El objeto $response es un array con los siguientes atributos:
 *   - *code* (int): Codigo de error desde el proveedor
 *   - *rid* (string): Contiene el mensaje de error.
 *   - *data* (array): Contiene el pdf.
 *   - *Pdf* (array): Pdf de la recarga encriptado en base 64
 *   - *PdfPOS* (array): Pdf de la recarga encriptado en base 64
 *
 * @throws Exception Error en los parametros enviados
 * @throws Exception No existe Ticket
 * @throws Exception Error General
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Valida que los parámetros idTicket y passwordTicket no estén vacíos antes de continuar. */
$Id = $json->params->idTicket;
$Clave = $json->params->passwordTicket;
$tipo = 'E';

if ($Id == "" || $Clave == "") {
    throw new Exception("Error en los parametros enviados", "100001");
}


/* Chequea un ticket y obtiene el usuario, lanzando excepción si no existe. */
$ItTicketEnc = new ItTicketEnc();

$ItTicketEnc = $ItTicketEnc->checkTicket($Id, $Clave);

$Usuario = new  Usuario($ItTicketEnc->usuarioId);

if ($ItTicketEnc == null) {
    throw new Exception("No existe Ticket", "24");
}


if ($Usuario->puntoventaId == $UsuarioPuntoVenta->puntoventaId && $Usuario->mandante == $UsuarioPuntoVenta->mandante) {


    /* Lanza excepciones si el estado o premio del ticket son incorrectos. */
    if ($ItTicketEnc->estado != "I") {
        throw new Exception("Error en los parametros enviados", "100001A");
    }

    if ($ItTicketEnc->premiado == "N") {
        throw new Exception("Error en los parametros enviados", "100001B");
    }


    /* Se lanzan excepciones si el premio está pagado o el ticket está caducado. */
    if ($ItTicketEnc->premioPagado == "S") {
        throw new Exception("Error en los parametros enviados", "100001C");
    }

    if ($ItTicketEnc->caducado == "S") {
        throw new Exception("Error en los parametros enviados", "100001D");
    }


    /* Código inicializa un objeto de venta y prepara la actualización de tickets en MySQL. */
    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

    $rowsUpdate = 0;


    $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();

    /* inicializa una transacción y define variables relacionadas con beneficiarios e impuestos. */
    $Transaction = $ItTicketEncMySqlDAO->getTransaction();
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

    $beneficiarioId = 0;
    $tipoBeneficiario = 0;
    $impuesto = 0;

    try {

        /* Se crea un clasificador y se obtiene un valor de impuesto por apuesta. */
        $Clasificador = new Clasificador("", "TAXBETPAYPRIZE");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

        $impuestoPorcSobreApuesta = $MandanteDetalle->getValor();


        /* Calcula el impuesto sobre una apuesta y asigna valores a un objeto. */
        $impuesto = floatval($impuestoPorcSobreApuesta / 100) * floatval($ItTicketEnc->vlrApuesta);

        $ItTicketEncInfo1 = new ItTicketEncInfo1();

        $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;
        $ItTicketEncInfo1->tipo = 'IMPUESTO';

        /* Inserta información de ticket con fechas de creación y modificación actualizadas. */
        $ItTicketEncInfo1->valor = $MandanteDetalle->manddetalleId;
        $ItTicketEncInfo1->fechaCrea = date("Y-m-d H:i:s");
        $ItTicketEncInfo1->fechaModif = date("Y-m-d H:i:s");

        $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);
        $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);


    } catch (Exception $e) {
        /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución. */


    }


    try {

        /* inicializa un clasificador y obtiene un impuesto sobre premios. */
        $Clasificador = new Clasificador("", "TAXPRIZEBETSHOP");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

        $impuestoPorcSobrePremio = $MandanteDetalle->getValor();


        /* Calcula el impuesto basado en la diferencia entre premio y apuesta. */
        $paraImpuesto = floatval($ItTicketEnc->vlrPremio) - floatval($ItTicketEnc->vlrApuesta);
        if ($paraImpuesto < 0) {
            $impuesto += 0;
        } else {
            $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
        }


        /* Se asignan valores a un objeto de ticket y se inicializa un DAO. */
        $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;
        $ItTicketEncInfo1->tipo = 'IMPUESTO';
        $ItTicketEncInfo1->valor = $MandanteDetalle->manddetalleId;
        $ItTicketEncInfo1->fechaCrea = date("Y-m-d H:i:s");
        $ItTicketEncInfo1->fechaModif = date("Y-m-d H:i:s");

        $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);

        /* Inserta un nuevo registro de información de ticket en la base de datos MySQL. */
        $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);

    } catch (Exception $e) {
        /* Bloque para manejar excepciones en PHP, sin acciones definidas en este caso. */


    }


    /* Asignación de valores relacionados con un ticket de pago y su modificación. */
    $ItTicketEnc->premioPagado = 'S';
    $ItTicketEnc->usumodificaId = $UsuarioPuntoVenta->usuarioId;
    $ItTicketEnc->fechaModifica = date('Y-m-d H:i:s');
    $ItTicketEnc->fechaPago = date('Y-m-d');
    $ItTicketEnc->horaPago = date('H:i:s');
    $ItTicketEnc->beneficiarioId = $beneficiarioId;

    /* Actualiza datos en la base de datos y lanza excepción si falla. */
    $ItTicketEnc->tipoBeneficiario = $tipoBeneficiario;
    $ItTicketEnc->impuesto = $impuesto;

    $rowsUpdate = $ItTicketEncMySqlDAO->update($ItTicketEnc, " AND premio_pagado='N' AND premiado='S' AND estado='I' ");

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }


    /* Calcula el valor a pagar y crea un registro de flujo de caja. */
    $rowsUpdate = 0;

    $ValorAPagar = $ItTicketEnc->vlrPremio - $impuesto;
    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));

    /* establece propiedades para un objeto FlujoCaja con datos de transacción. */
    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
    $FlujoCaja->setTipomovId('S');
    $FlujoCaja->setValor($ValorAPagar);
    $FlujoCaja->setTicketId($ItTicketEnc->ticketId);
    $FlujoCaja->setCuentaId('0');
    $FlujoCaja->setMandante($ItTicketEnc->mandante);

    /* configura valores para un objeto FlujoCaja y verifica el método de pago. */
    $FlujoCaja->setValorIva($impuesto);
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setRecargaId('0');

    if ($FlujoCaja->getFormapago1Id() == "") {
        $FlujoCaja->setFormapago1Id(0);
    }


    /* Valida campos vacíos en objeto FlujoCaja y asigna valor 0 si es necesario. */
    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }

    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }


    /* asigna 0 si ciertos valores son cadenas vacías en FlujoCaja. */
    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }

    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }


    /* Establece el porcentaje de IVA y la devolución en un objeto de flujo de caja. */
    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }

    $FlujoCaja->setDevolucion('');

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

    /* inserta un flujo de caja y maneja errores en la operación. */
    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }


    $rowsUpdate = 0;

    /* Verifica perfiles y actualiza balances, registrando movimientos en historial. */
    if ($UsuarioPerfilUsuario->perfilId == "CONCESIONARIO" or $UsuarioPerfilUsuario->perfilId == "CONCESIONARIO2" or $UsuarioPerfilUsuario->perfilId == "PUNTOVENTA" or $UsuarioPerfilUsuario->perfilId == "CAJERO") {

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
        /* Lanza una excepción con mensaje de error y código específico si ocurre un problema. */

        throw new Exception("Error General", "100000");
    }


    /* Crea una nueva instancia de la clase Mandante con el dato del usuario. */
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

        <div style="text-align:center;font-size:12px;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">' . $Mandante->nombre . '</font>
        </div>
        <div style="text-align:center;font-size:12px;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE PAGO PREMIO</font>
        </div>
    <table style="width:100%;height: 355px;">
        <tbody>
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
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $ItTicketEnc->vlrPremio . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $impuesto . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $ValorAPagar . '</font></td>
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


    /* muestra un mensaje específico si el país del usuario es Perú. */
    if ($Usuario->paisId == 173) {
        $pdf .= '
        <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
        </div>';

    }


    /* Genera un código HTML para mostrar un código de barras en un PDF. */
    $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
      <div class="barcodecell" style="  text-align: center;"><barcode code="' . $ItTicketEnc->ticketId . '" type="I25" class="barcode" /></div>
  </div>
    </div>
</div>
</body>
';


    require_once __DIR__ . "/../../src/imports/mpdf6.1/mpdf.php";


    /* Genera un documento PDF con márgenes espejados y visualización a dos páginas. */
    $mpdf = new mPDF('c', array(80, 200), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

    $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

    $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


    /* genera un PDF y crea un directorio si no existe. */
    $mpdf->WriteHTML($pdf);

    if (!is_dir(__DIR__ . "/pdf/")) {

        mkdir(__DIR__ . "/pdf/", 0777);

    }

    /* Genera un archivo PDF llamado con el ticket y lo guarda en una carpeta. */
    $pdfFile = "tic" . $ItTicketEnc->ticketId . ".pdf";


    $mpdf->Output(__DIR__ . "/pdf/" . $pdfFile, "F");

    $path = __DIR__ . '/pdf/' . $pdfFile;


    /* Codifica un archivo en Base64 y lo almacena en un arreglo de respuesta. */
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

    $response["Pdf"] = base64_encode($data);
    $response["PdfPOS"] = base64_encode($data);


    /* Genera una respuesta JSON con un código y datos de un PDF. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] =
        array(
            "htmlPOS" => $pdf
        );


    /* elimina un archivo PDF del directorio especificado en el servidor. */
    unlink(__DIR__ . '/pdf/' . $pdfFile);

} else {
    /* Lanza una excepción con un mensaje y código específico en caso de error. */

    throw new Exception("Error General", "100000");
}
