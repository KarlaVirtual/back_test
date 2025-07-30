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
 * Client/ReprintDeposit
 *
 * Reimprime el comprobante de un depósito.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param int $params ->Id Identificador del depósito.
 *
 *
 *
 * @return array $response Respuesta del proceso:
 *                         - HasError (boolean): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ("success").
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - Pdf (string): PDF en formato base64.
 *                         - PdfPOS (string): PDF para impresión en formato base64.
 *
 * @throws Exception Si ocurre un error durante la generación del PDF.
 */


/* Se crea un objeto UsuarioRecarga utilizando un identificador pasado como parámetro. */
$UsuarioRecarga = new UsuarioRecarga();


$Id = $params->Id;
$seguir = true;

$UsuarioRecarga = new UsuarioRecarga($Id);

/* Se crean objetos de usuario a partir de identificadores de recarga y punto de venta. */
$Usuario = new Usuario($UsuarioRecarga->usuarioId);
$UsuarioPuntoVenta = new Usuario($UsuarioRecarga->puntoventaId);
if ($seguir) {


    if ($UsuarioPuntoVenta->mandante == 2 || $UsuarioPuntoVenta->mandante == 6 || $UsuarioPuntoVenta->mandante == 18) {

        try {

            /* Se crea un clasificador y una plantilla HTML utilizando información del usuario. */
            $Clasificador = new Clasificador("", "TEMRECRE");

            $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower('es'));
            $html_barcode = $Template->templateHtml;
            if ($html_barcode != '') {

                /* Reemplaza marcadores en una plantilla HTML con datos específicos del usuario y recarga. */
                $html_barcode = str_replace("#depositnumber#", $UsuarioRecarga->recargaId, $html_barcode);

                $html_barcode = str_replace("#userid#", $UsuarioRecarga->usuarioId, $html_barcode);
                $html_barcode = str_replace("#login#", $Usuario->login, $html_barcode);
                $html_barcode = str_replace("#name#", $Usuario->nombre, $html_barcode);
                $html_barcode = str_replace("#idpointsale#", $UsuarioPuntoVenta->usuarioId, $html_barcode);

                /* Reemplaza marcadores en un código HTML por valores de variables específicas. */
                $html_barcode = str_replace("#namepointsale#", $UsuarioPuntoVenta->nombre, $html_barcode);
                $html_barcode = str_replace("#value#", $UsuarioRecarga->valor, $html_barcode);
                $html_barcode = str_replace("#creationdate#", $UsuarioRecarga->fechaCrea, $html_barcode);

                $html_barcode = str_replace("#tax#", '0', $html_barcode);

                $html_barcode = str_replace("#totalvalue#", $UsuarioRecarga->valor, $html_barcode);

                /* Condicional que calcula un valor y reemplaza en un HTML para un PDF. */
                if ($Usuario->mandante == 0 && $Usuario->paisId == 2) {
                    $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
                    $totalvalue2 = ($UsuarioRecarga->valor * $PaisMandante->trmNio);
                    $html_barcode = str_replace("#totalvalue2#", $totalvalue2, $html_barcode);
                }
                $pdf = $html_barcode;


                /* Se genera un código HTML para imprimir un código de barras usando Dompdf. */
                $html_barcode .= $Template->templateHtmlCSSPrint;
// instantiate and use the dompdf class
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html_barcode);

// (Optional) Setup the paper size and orientation
                $width = 80; //mm!

                /* Convierte medidas de milímetros a puntos y configura el papel en Dompdf. */
                $height = 150; //mm!

                //convert mm to points
                $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);
                $dompdf->setPaper($paper_format);

// Render the HTML as PDF
                $dompdf->render();

// Output the generated PDF to Browser


                /* Convierte un archivo PDF a base64 para su almacenamiento o transmisión. */
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = $dompdf->output();

                $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

                $response["Pdf"] = base64_encode($data);

                /* Codifica los datos en formato Base64 y los asigna a "PdfPOS". */
                $response["PdfPOS"] = base64_encode($data);


            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


        }
    } else {


        /* Se crea una instancia de la clase Mandante utilizando el atributo mandante del usuario. */
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
        <tr style="width: 50%; display: inline-block;">
            <td align="center" valign="top"><img style="width: 140px; padding-left: 20px;"
                                                 src="' . $Mandante->logoPdf . '" alt="logo">
            </td>
            <td align="center" valign="top" style="display: block;text-align:center;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO<br>DE RECARGA</font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Recibo de Recarga No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioRecarga->recargaId . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioRecarga->getFechaCrea() . ' </font>
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
        </tr>';


        /* Condicional que genera una fila en PDF con información del cliente si se cumplen criterios. */
        if ($UsuarioPuntoVenta->paisId == '2' and $UsuarioPuntoVenta->mandante == "0") {
            $pdf = $pdf . '<tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Cedula Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Registro->cedula . '</font>
            </td>
        </tr>';
        }


        $pdf = $pdf . '
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Email: </font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->login . ' </font>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor recarga :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . '</font></td>
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
        <div style="text-align:center;font-size:12px;">Disfruta del juego en vivo</font>
        </div>
        ';


        /* Condicional que añade información de empresa si el usuario pertenece a un país específico. */
        if ($Usuario->paisId == 173 && $Usuario->mandante == '0') {
            $pdf .= '
        <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
        </div>';

        }


        /* Genera un código HTML para mostrar un código de barras de recarga de usuario. */
        $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
      <div class="barcodecell" style="  text-align: center;"><barcode code="' . $UsuarioRecarga->getRecargaId() . '" type="I25" class="barcode" /></div>
  </div>
    </div>
</div>
</body>

';

//        require_once __DIR__ . "/../../mpdf6.1/mpdf.php";

        /* Inicializa una instancia de Mpdf con formato personalizado y márgenes espejados. */
        $mpdf = new \Mpdf\Mpdf(['format' => array(80, 150), 'tempDir' => '/tmp']);

        //  $mpdf = new mPDF('c', array(80, 150), 10, 10, 10, 10);
//$mpdf = new mPDF('c', 'A4-L');

        $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)


        /* Configura mPDF para mostrar en dos páginas y carga contenido HTML. */
        $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


        $mpdf->WriteHTML($pdf);


        /* Genera un archivo PDF y obtiene su contenido desde una ruta temporal. */
        $mpdf->Output('/tmp' . "/mpdfRD" . $UsuarioRecarga->getRecargaId() . ".pdf", "F");

        $path = '/tmp' . '/mpdfRD' . $UsuarioRecarga->getRecargaId() . '.pdf';

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        /* Codifica datos en base64 para PDF y los almacena en un arreglo de respuesta. */
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

        $response["Pdf"] = base64_encode($data);
        $response["PdfPOS"] = base64_encode($data);

        $ConfigurationEnvironment = new ConfigurationEnvironment();


        /* envía un mensaje WebSocket para actualizar el saldo en desarrollo. */
        if ($ConfigurationEnvironment->isDevelopment()) {

            try {
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();

            } catch (Exception $e) {

            }
        }

    }
} else {
    /* Establece valores iniciales en la respuesta si no se cumple una condición. */


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}