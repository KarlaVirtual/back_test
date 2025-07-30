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
 * Accounting/SaveIncome
 *
 * Guardar un ingreso
 *
 * @param object $params Objeto que contiene los parámetros necesarios para guardar un ingreso:
 * @param int Concept: ID del concepto del ingreso.
 * @param string Description: Descripción del ingreso.
 * @param string Document: Referencia del documento.
 * @param string Value: Valor del ingreso.
 * @param int ProvidersThird: ID del proveedor tercero.
 * @param int BetShops: (opcional) ID de las casas de apuestas.
 * @param int CloseBoxId: (opcional) ID del cierre de caja.
 *
 * @return array $response Array con la respuesta de la operación:
 *                         - HasError: booleano que indica si hubo un error.
 *                         - AlertType: tipo de alerta.
 *                         - AlertMessage: mensaje de alerta.
 *                         - ModelErrors: array con los errores del modelo.
 *                         - Pdf: PDF codificado en base64.
 *                         - PdfPOS: PDF POS codificado en base64.
 */

if ($_SESSION['win_perfil2'] == "PUNTOVENTA" || $_SESSION['win_perfil2'] == "CAJERO") {


    /* Se crean objetos de usuario y se asignan parámetros desde una sesión. */
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $Concept = $params->Concept;
    $Description = $params->Description;
    $Reference = $params->Document;

    /* asigna valores a variables y crea una instancia de la clase Ingreso. */
    $Value = $params->Value;

    $ProvidersThird = $params->ProvidersThird;

    $Ingreso = new Ingreso();
    $Ingreso->setTipoId(0);

    /* configura propiedades de un objeto "Ingreso" con diferentes valores. */
    $Ingreso->setDescripcion($Description);
    $Ingreso->setCentrocostoId(0);
    $Ingreso->setDocumento($Reference);
    $Ingreso->setEstado("A");
    $Ingreso->setValor($Value);
    $Ingreso->setImpuesto(0);

    /* Código establece propiedades del objeto $Ingreso relacionado con un usuario y proveedor. */
    $Ingreso->setRetraccion(0);
    $Ingreso->setUsuarioId($Usuario->puntoventaId);
    $Ingreso->setConceptoId($Concept);
    $Ingreso->setProductotercId(0);
    $Ingreso->setProveedortercId($ProvidersThird);
    $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());


    /* establece IDs y crea una instancia de IngresoMySqlDAO. */
    $Ingreso->setProductotercId(0);

    $Ingreso->setUsucreaId(0);
    $Ingreso->setUsumodifId(0);


    $IngresoMySqlDAO = new IngresoMySqlDAO();

    /* Inserta un ingreso en MySQL y confirma la transacción sin errores. */
    $IngresoMySqlDAO->insert($Ingreso);
    $IngresoMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";

    /* Inicializa un array vacío para almacenar errores del modelo en una respuesta. */
    $response["ModelErrors"] = [];

} else {

    /* Se inicializan variables y parámetros relacionados con casas de apuestas y cierre. */
    $BetShops = $params->BetShops;

    $estado = "A";

    $CloseBoxId = $params->CloseBoxId;
    $fechaEspecifica = '';


    /* verifica un ID, crea un objeto y obtiene datos de cierre de caja. */
    if ($CloseBoxId != "") {
        $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
        $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

        $BetShops = $UsuarioCierrecaja->getUsuarioId();

        $estado = 'C';
    }


    /* inicializa objetos de usuario según la variable BetShops. */
    if ($BetShops != "") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($BetShops);

    } else {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    }

    /* Condicionalmente se asigna un usuario dependiendo del valor de $CloseBoxId. */
    $Usucrea = $UsuarioMandante->getUsuarioMandante();

    if ($CloseBoxId != "") {

        $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);

        $Usucrea = $UsuarioMandante2->getUsuarioMandante();

        $estado = 'C';
    }


    /* asigna parámetros a variables para su posterior uso en un proceso. */
    $Concept = $params->Concept;
    $Description = $params->Description;
    $Reference = $params->Document;
    $Value = $params->Value;

    $ProvidersThird = $params->ProvidersThird;


    /* Se crea un objeto "Ingreso" y se configuran sus propiedades con valores específicos. */
    $Ingreso = new Ingreso();
    $Ingreso->setTipoId(0);
    $Ingreso->setDescripcion($Description);
    $Ingreso->setCentrocostoId(0);
    $Ingreso->setDocumento($Reference);
    $Ingreso->setEstado($estado);

    /* Código que establece valores y atributos para un objeto de ingreso en un sistema. */
    $Ingreso->setValor($Value);
    $Ingreso->setImpuesto(0);
    $Ingreso->setRetraccion(0);
    $Ingreso->setUsuarioId($Usuario->puntoventaId);
    $Ingreso->setConceptoId($Concept);
    $Ingreso->setProductotercId(0);

    /* asigna valores a propiedades de un objeto "Ingreso". */
    $Ingreso->setProveedortercId($ProvidersThird);
    $Ingreso->setUsucajeroId($Usuario->usuarioId);

    $Ingreso->setProductotercId(0);

    $Ingreso->setUsucreaId($Usucrea);

    /* Se asigna un ID y se establece una fecha en un objeto de ingreso. */
    $Ingreso->setUsumodifId(0);

    if ($fechaEspecifica != '') {
        $Ingreso->fechaCrea = $fechaEspecifica;
    }

    $IngresoMySqlDAO = new IngresoMySqlDAO();

    /* Inserta un ingreso en MySQL y confirma la transacción sin errores. */
    $IngresoMySqlDAO->insert($Ingreso);
    $IngresoMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";

    /* Inicializa un arreglo vacío para almacenar errores del modelo en la respuesta. */
    $response["ModelErrors"] = [];
}


/* inicializa un clasificador y obtiene detalles sobre un ingreso específico. */
$TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


$Ingreso = new Ingreso($Ingreso->getIngresoId());
$consecutive = "I" . $Ingreso->getUsuarioId() . "-" . $Ingreso->getConsecutivo();
$descripcion = $Ingreso->getDescripcion();

/* asigna una descripción a "concepto" basada en un ID válido. */
$concepto = "";

if ($Ingreso->getConceptoId() != "" && $Ingreso->getConceptoId() != "0") {
    $Concepto = new Concepto($Ingreso->getConceptoId());
    $concepto = $Concepto->getDescripcion();
}


/* asigna descripciones basadas en condiciones de productos y clasificadores. */
if ($Ingreso->getProductotercId() != "" && $Ingreso->getProductotercId() != "0") {
    $ProductoTercero = new ProductoTercero($Ingreso->getProductotercId());
    $concepto = "";
    $descripcion = "Producto " . $ProductoTercero->getDescripcion();
}


if ($Ingreso->getTipoId() != "" && $Ingreso->getTipoId() != "0") {

    $Clasificador = new Clasificador($Ingreso->getTipoId());

    if ($Clasificador->getAbreviado() == "ACCAMOUNTDAY") {
        $concepto = "";
        $descripcion = "Dinero Inicial";

    } else {
        if ($Clasificador->getTipo() == "TARJCRED") {
            $concepto = "";
            $descripcion = "Tarjeta de Credito " . $Clasificador->getDescripcion();

        }

    }

}


/* Genera una tabla HTML para una factura de soporte de ingreso. */
$pdf = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody>
<tr><td align="center" valign="top"><img src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg"></td></tr>
<tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">SOPORTE INGRESO</font></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Soporte No.:&nbsp;&nbsp;' . $consecutive . '</font></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $Ingreso->getFechaCrea() . '</font></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Concepto:&nbsp;&nbsp;' . $concepto . '</font></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Descripcion:&nbsp;&nbsp;' . $descripcion . '</font></td></tr>
<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor:&nbsp;&nbsp;' . $Ingreso->getValor() . '</font></td></tr>
</tbody></table>';

$pdf = '<div style="width:330px; border:1px solid grey; padding: 15px;">
	<table style="width:100%;height: 355px;">
		<tbody>
			<tr >
				<td align="center" valign="top"><img style="width: 50px; padding-left: 20px;" src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg" alt="logo-doradobet">
				</td>
				<td align="center" valign="top" style="display: block;text-align:center;padding-top:25px;"><font style="text-align:center;font-size:20px;font-weight:bold;">SOPORTE</font>
				</td>
			</tr>
			<tr>
				<td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
					<font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Soporte No.:</font>
				</td>
				<td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
					<font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $consecutive . ' </font>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">
					<font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:</font>
				</td>
				<td align="left" valign="top">
					<font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Ingreso->getFechaCrea() . ' </font>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Concepto: </font>
				</td>
				<td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $concepto . ' </font>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Descripcion: </font>
				</td>
				<td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $descripcion . ' </font>
				</td>
			</tr>
			<tr>
				<td align="center" valign="top">
					<div style="height:1px;">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor:</font></td>
				<td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">' . $Ingreso->getValor() . '</font></td>
			</tr>



		</tbody>
	</table>
	
</div>';


require_once "mpdf6.1/mpdf.php";
// $mpdf = new mPDF('c', array(80, 150));

/* Crea un documento PDF en formato A4 horizontal con márgenes espejados y estilos opcionales. */
$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($pdf);


/* genera un PDF y lee su contenido desde el sistema de archivos. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* codifica datos y PDF en formato base64 para su manejo. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["Pdf"] = base64_encode($data);
$mpdf = new mPDF('c', array(45, 350), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');


/* Configura márgenes espejados y opciones de visualización para un documento PDF. */
$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($pdf);


/* guarda un PDF en un archivo y obtiene su contenido. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* convierte datos en formato base64 para su uso en aplicaciones. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["PdfPOS"] = base64_encode($data);
