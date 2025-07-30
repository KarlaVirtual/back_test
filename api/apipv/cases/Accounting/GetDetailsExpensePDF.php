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
 * Accounting/GetDetailsExpensePDF
 *
 * Generación de PDF para Egreso
 *
 * Este recurso genera un documento PDF que actúa como soporte para un egreso específico, el cual incluye detalles del egreso como su número de consecutivo, fecha de creación, concepto, descripción, y valor asociado.
 * El formato del PDF incluye la inserción de un logo y la estructuración adecuada de los campos de información sobre el egreso.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para la generación del soporte.
 *  - *Id* (int) : Identificador del egreso.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *Pdf* (string): El archivo PDF codificado en base64.
 *  - *Pdf2* (string): El contenido HTML del PDF codificado en base64.
 *  - *Data* (array): Contiene información adicional sobre el egreso, como el total de la operación.
 *  - *HasError* (bool): Indica si hubo un error durante la operación.
 *  - *AlertType* (string): Tipo de alerta de la operación (success, error, etc.).
 *  - *AlertMessage* (string): Mensaje asociado con el tipo de alerta.
 *  - *ModelErrors* (array): Array vacío si no hubo errores específicos.
 *  - *pos* (int): Número de filas omitidas en la paginación.
 *  - *total_count* (int): Número total de registros disponibles.
 *  - *data* (array): Contiene el resultado final de la operación.
 *
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* crea un objeto Egreso y genera un consecutivo basado en su usuario. */
$Id = $params->Id;

$Egreso = new Egreso($Id);
$consecutive = "E" . $Egreso->getUsuarioId() . "-" . $Egreso->getConsecutivo();
$descripcion = $Egreso->getDescripcion();
$concepto = "";


/* verifica y obtiene descripciones de conceptos y productos según sus ID. */
if ($Egreso->getConceptoId() != "" && $Egreso->getConceptoId() != "0") {
    $Concepto = new Concepto($Egreso->getConceptoId());
    $concepto = $Concepto->getDescripcion();
}


if ($Egreso->getProductotercId() != "" && $Egreso->getProductotercId() != "0") {
    $ProductoTercero = new ProductoTercero($Egreso->getProductotercId());
    $concepto = "";
    $descripcion = "Producto " . $ProductoTercero->getDescripcion();
}


/* verifica el tipo de egreso y asigna descripciones específicas. */
if ($Egreso->getTipoId() != "" && $Egreso->getTipoId() != "0") {

    $Clasificador = new Clasificador($Egreso->getTipoId());

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


/* Genera un PDF con detalles de soporte de egreso, incluyendo logo y formato. */
$pdf = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody>
<tr><td align="center" valign="top"><img src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg"></td></tr>
<tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">SOPORTE EGRESO</font></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Soporte No.:&nbsp;&nbsp;' . $consecutive . '</font></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $Egreso->getFechaCrea() . '</font></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Concepto:&nbsp;&nbsp;' . $concepto . '</font></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Descripcion:&nbsp;&nbsp;' . $descripcion . '</font></td></tr>
<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="left" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor:&nbsp;&nbsp;' . $Egreso->getValor() . '</font></td></tr>
</tbody></table>';

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

/* Configura mPDF con márgenes espejados y modo de visualización en dos páginas. */
$mpdf = new mPDF('c', array(80, 150), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


/* genera un PDF y lo guarda en la carpeta temporal. */
$mpdf->WriteHTML($pdf);

$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);

/* carga un archivo, lo codifica en base64 y lo prepara para respuesta. */
$data = file_get_contents($path);
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["Pdf"] = base64_encode($data);


/* asigna valores a un arreglo de respuesta en formato JSON. */
$response["Pdf2"] = $pdf;


$response["Data"]["Total"] = $total;


$response["HasError"] = false;

/* establece respuestas para una operación, incluyendo errores y conteos. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};

/* Asigna el valor de $final a la clave "data" del arreglo $response. */
$response["data"] = $final;
