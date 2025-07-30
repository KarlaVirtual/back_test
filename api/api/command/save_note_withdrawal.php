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


/* Se crean instancias de objetos basados en los datos del usuario desde un JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
$UsuarioPerfilUsuario = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());

/**
 * command/save_note_withdrawal
 *
 * Realizar pago de una nota de retiro de un usuario
 *
 * @param string $Id : Id de la nota de retiro
 * @param string $Clave : Clave de la nota de retiro
 *
 * El objeto $response es un array con los siguientes atributos:
 *   - *code* (int): Codigo de error desde el proveedor
 *   - *rid* (string): Contiene el mensaje de error.
 *   - *data* (array): Contiene el pdf.
 *   - *Pdf* (array): Pdf de la recarga encriptado en base 64
 *   - *PdfPOS* (array): Pdf de la recarga encriptado en base 64
 *
 * @throws Exception Error en los parametros enviados
 * @throws Exception La nota de retiro no esta activa
 * @throws Exception La nota de retiro no se puede pagar por este medio
 * @throws Exception Error General
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Verifica si los parámetros 'idNoteWithdrawal' y 'passwordNoteWithdrawal' están vacíos. */
$Id = intval($json->params->idNoteWithdrawal);
$Clave = $json->params->passwordNoteWithdrawal;

if ($Id == "" || $Clave == "") {
    throw new Exception("Error en los parametros enviados", "100001");
}


/* Se crean instancias de CuentaCobro y Usuario utilizando identificadores específicos. */
$CuentaCobro = new CuentaCobro($Id, "", $Clave);
$Usuario = new  Usuario($CuentaCobro->getUsuarioId());

if ($Usuario->paisId == $UsuarioPuntoVenta->paisId && $Usuario->mandante == $UsuarioPuntoVenta->mandante) {


    /* Valida el estado y medio de pago de una cuenta de cobro, arroja excepciones si falla. */
    if ($CuentaCobro->getEstado() != "A") {
        throw new Exception("La nota de retiro no esta activa", "21001");
    }

    if ($CuentaCobro->getMediopagoId() != "0") {
        throw new Exception("La nota de retiro no se puede pagar por este medio", "100001");
    }

    /* Asigna la fecha actual a campos vacíos o nulos en una cuenta de cobro. */
    if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
        $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
    }

    if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
        $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
    }


    /* Se inicializa un objeto PuntoVenta y se crea una instancia de CuentaCobroMySqlDAO. */
    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

    $rowsUpdate = 0;


    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

    /* Código para gestionar transacciones y actualizar el estado de una cuenta de cobro. */
    $Transaction = $CuentaCobroMySqlDAO->getTransaction();
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

    $CuentaCobro->setEstado('I');
    $CuentaCobro->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
    $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));


    /* Actualiza registros y maneja excepciones si no se actualizan correctamente. */
    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='A' ");

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }

    $rowsUpdate = 0;


    /* Crea un objeto FlujoCaja y establece sus propiedades usando datos actuales. */
    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
    $FlujoCaja->setTipomovId('S');
    $FlujoCaja->setValor($CuentaCobro->getValor());

    /* Configura propiedades de un objeto FlujoCaja según los datos de CuentaCobro. */
    $FlujoCaja->setTicketId('');
    $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
    $FlujoCaja->setMandante($CuentaCobro->getMandante());
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setRecargaId(0);

    if ($FlujoCaja->getFormapago1Id() == "") {
        $FlujoCaja->setFormapago1Id(0);
    }


    /* Asigna valores predeterminados si los campos son vacíos en objeto FlujoCaja. */
    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }

    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }


    /* Asigna 0 a valores vacíos en FlujoCaja para evitar errores. */
    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }

    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }


    /* Establece valores predeterminados a cero si los porcentajes de IVA son vacíos. */
    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }

    if ($FlujoCaja->getValorIva() == "") {
        $FlujoCaja->setValorIva(0);
    }

    /* Inserta un objeto FlujoCaja en la base de datos y maneja errores. */
    $FlujoCaja->setDevolucion('');

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }


    /* actualiza el balance de créditos según el perfil del usuario. */
    $rowsUpdate = 0;
    if ($UsuarioPerfilUsuario->perfilId == "CONCESIONARIO" or $UsuarioPerfilUsuario->perfilId == "CONCESIONARIO2" or $UsuarioPerfilUsuario->perfilId == "PUNTOVENTA" or $UsuarioPerfilUsuario->perfilId == "CAJERO") {

        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($CuentaCobro->getValor(), $Transaction);

    }


    /* Registra un historial del usuario si se actualizan filas en la base de datos. */
    if ($rowsUpdate > 0) {

        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(40);
        $UsuarioHistorial->setValor($CuentaCobro->getValor());
        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


        $Transaction->commit();
    } else {
        /* Lanza una excepción con mensaje "Error General" y código "100000" si hay un fallo. */

        throw new Exception("Error General", "100000");
    }


    /* Crea una instancia de la clase Mandante utilizando el objeto mandante del usuario. */
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
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE PAGO RETIRO</font>
        </div>
    <table style="width:100%;height: 355px;">
        <tbody>
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Retiro No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $CuentaCobro->getCuentaId() . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha de Pago:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $CuentaCobro->getFechaPago() . ' </font>
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
        </tr>
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $CuentaCobro->getValor() . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $CuentaCobro->getImpuesto() . '</font></td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $CuentaCobro->getValor() . '</font></td>
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


    /* Se muestra un mensaje si el usuario pertenece a un país específico. */
    if ($Usuario->paisId == 173) {
        $pdf .= '
        <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
        </div>';

    }


    /* Genera un código HTML para mostrar un código de barras en una PDF. */
    $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
      <div class="barcodecell" style="  text-align: center;"><barcode code="' . $CuentaCobro->getCuentaId() . '" type="I25" class="barcode" /></div>
  </div>
    </div>
</div>
</body>
';


    require_once __DIR__ . "/../../src/imports/mpdf6.1/mpdf.php";


    /* Inicializa mPDF con márgenes espejados y modo de visualización ajustado. */
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


    /* Genera un archivo PDF y obtiene su tipo de archivo. */
    $pdfFile = "nw" . $CuentaCobro->getCuentaId() . ".pdf";
    $mpdf->Output(__DIR__ . "/pdf/" . $pdfFile, "F");

    $path = __DIR__ . '/pdf/' . $pdfFile;

    $type = pathinfo($path, PATHINFO_EXTENSION);

    /* lee un archivo y lo codifica en base64 para su respuesta. */
    $data = file_get_contents($path);
    $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

    $response["Pdf"] = base64_encode($data);
    $response["PdfPOS"] = base64_encode($data);

    $response = array();

    /* define una respuesta con un código, un identificador y datos en formato HTML. */
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    $response["data"] =
        array(
            "htmlPOS" => $pdf
        );

    /* Elimina el archivo PDF especificado en la ruta del directorio actual. */
    unlink(__DIR__ . '/pdf/' . $pdfFile);
} else {
    /* Lanza una excepción con un mensaje y código de error específico. */

    throw new Exception("Error General", "100000");
}
