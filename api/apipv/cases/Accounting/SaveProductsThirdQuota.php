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
 * Accounting/SaveProductsThirdQuota
 *
 * Asignar cupo a un producto tercero con cupo
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la asignación de cupo:
 * @param int $BetShopId ID de la tienda de apuestas.
 * @param int $ProductsThird ID del producto tercero.
 * @param float $Quota Valor del cupo a asignar.
 * @param int $TypeDocument Tipo de documento.
 * @param float $Tax Valor del impuesto.
 * @param string $fechaEspecifica Fecha específica para la creación del egreso.
 *
 * @return array $response Arreglo que contiene la respuesta de la operación:
 * - bool $HasError Indica si hubo un error.
 * - string $AlertType Tipo de alerta.
 * - string $AlertMessage Mensaje de alerta.
 * - array $ModelErrors Errores del modelo.
 * - string $Pdf Contenido del PDF codificado en base64.
 * - string $PdfPOS Contenido del PDF POS codificado en base64.
 */


/* Asigna valores de parámetros a variables y establece el estado como "A". */
$BetShopId = $params->BetShopId;
$ProductsThird = $params->ProductsThird;
$Value = $params->Quota;


$estado = "A";


/* Se crean instancias de usuario, producto y clasificador en una aplicación. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$Usuario = new Usuario($BetShopId);

$ProductoterceroUsuario = new ProductoterceroUsuario("", "", $Usuario->usuarioId, $ProductsThird);

$Clasificador = new Clasificador("", "ASIGQUOTAPT");


/* obtiene un usuario y define variables para concepto, descripción y referencia. */
$Usucrea = $UsuarioMandante->getUsuarioMandante();


$Concept = '0';
$Description = '';
$Reference = '';


/* Inicializa variables y obtiene valores de parámetros, asegurando tipos numéricos. */
$Serie = '';
$TypeDocument = (is_numeric($params->TypeDocument)) ? $params->TypeDocument : 0;
$Tax = (is_numeric($params->Tax)) ? $params->Tax : 0;

$ProvidersThird = 0;

$estado = '';


/* Se crea un objeto Egreso y se establecen sus propiedades a partir de variables. */
$Egreso = new Egreso();
$Egreso->setTipoId($Clasificador->getClasificadorId());
$Egreso->setDescripcion($Description);
$Egreso->setCentrocostoId(0);
$Egreso->setDocumento($Reference);
$Egreso->setEstado($estado);

/* Configuración de un objeto Egreso con valores de impuestos, usuario y proveedores. */
$Egreso->setValor($Value);
$Egreso->setImpuesto($Tax);
$Egreso->setRetraccion(0);
$Egreso->setUsuarioId($Usuario->puntoventaId);
$Egreso->setConceptoId($Concept);
$Egreso->setProveedortercId($ProvidersThird);

/* Se configura un objeto Egreso con diferentes parámetros y valores relacionados. */
$Egreso->setUsucajeroId($Usuario->usuarioId);
$Egreso->setSerie($Serie);
$Egreso->setTipoDocumento($TypeDocument);

$Egreso->setProductotercId($ProductsThird);
$Egreso->setUsucreaId($Usucrea);

/* Se establece un ID y se asigna una fecha si es específica. */
$Egreso->setUsumodifId(0);


if ($fechaEspecifica != '') {
    $Egreso->fechaCrea = $fechaEspecifica;
}


/* Inserta un egreso y actualiza el cupo de un usuario en MySQL. */
$EgresoMySqlDAO = new EgresoMySqlDAO();
$EgresoMySqlDAO->insert($Egreso);


$ProductoterceroUsuario->setCupo("cupo + " . "'" . $Value . "'");

$ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO($EgresoMySqlDAO->getTransaction());

/* Actualiza un producto, confirma la transacción y establece que no hubo errores. */
$ProductoterceroUsuarioMySqlDAO->update($ProductoterceroUsuario);

$EgresoMySqlDAO->getTransaction()->commit();


$response["HasError"] = false;

/* inicializa un arreglo de respuesta y crea un objeto Egreso. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


$Egreso = new Egreso($Egreso->getEgresoId());

/* Genera un código que construye un consecutivo y obtiene descripciones de un egreso. */
$consecutive = "E" . $Egreso->getUsuarioId() . "-" . $Egreso->getConsecutivo();
$descripcion = $Egreso->getDescripcion();
$concepto = "";

if ($Egreso->getConceptoId() != "" && $Egreso->getConceptoId() != "0") {
    $Concepto = new Concepto($Egreso->getConceptoId());
    $concepto = $Concepto->getDescripcion();
}


/* Condicional que asigna descripción basada en un objeto ProductoTercero. */
if ($Egreso->getProductotercId() != "" && $Egreso->getProductotercId() != "0") {
    $ProductoTercero = new ProductoTercero($Egreso->getProductotercId());
    $concepto = "";
    $descripcion = "Producto " . $ProductoTercero->getDescripcion();
}

$descripcion = "Asignacion Cupo Producto tercero";


$pdf = '<div style="width:330px; border:1px solid grey; padding: 15px;">
	<table style="width:100%;height: 355px;">
		<tbody>
			<tr >
				<td align="center" valign="top"><img style="width: 50px; padding-left: 20px;" src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg" alt="logo-doradobet">
				</td>
				<td align="center" valign="top" style="display: block;text-align:center;padding-top:25px;"><font style="text-align:center;font-size:20px;font-weight:bold;">SOPORTE EGRESO</font>
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
					<font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Egreso->getFechaCrea() . ' </font>
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
				<td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">' . $Egreso->getValor() . '</font></td>
			</tr>



		</tbody>
	</table>
	
</div>';


require_once "mpdf6.1/mpdf.php";
// $mpdf = new mPDF('c', array(80, 150));

/* Crea un archivo PDF en formato A4 horizontal con márgenes reflectantes. */
$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');
$mpdf->WriteHTML($pdf);


/* genera un PDF y luego lee su contenido desde el archivo. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* Se generan archivos PDF codificados en base64 para su manejo y respuesta. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["Pdf"] = base64_encode($data);


$mpdf = new mPDF('c', array(45, 350), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');


/* Configura márgenes espejados y carga contenido HTML en PDF con mPDF. */
$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($pdf);


/* genera un PDF y lo guarda en la carpeta temporal. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* codifica datos y PDF en formato base64 para su almacenamiento o transmisión. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["PdfPOS"] = base64_encode($data);
