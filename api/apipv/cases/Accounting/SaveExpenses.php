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
 * Guardar un egreso
 *
 * @param object $params Objeto que contiene los parámetros necesarios para guardar el egreso.
 * @param string $params ->Concept Concepto del egreso.
 * @param string $params ->Description Descripción del egreso.
 * @param string $params ->Document Documento de referencia del egreso.
 * @param float $params ->Value Valor del egreso.
 * @param string $params ->Serie Serie del documento del egreso.
 * @param int $params ->TypeDocument Tipo de documento del egreso.
 * @param float $params ->Tax Impuesto del egreso.
 * @param int $params ->ProvidersThird Proveedor tercero del egreso.
 * @param string $params ->BetShops Tiendas de apuestas relacionadas.
 * @param string $params ->CloseBoxId ID de caja cerrada.
 *
 * @return void Modifica el array $response para indicar el resultado de la operación.
 * - bool "HasError" Indica si hubo un error en la operación.
 * - string "AlertType" Tipo de alerta generada.
 * - string "AlertMessage" Mensaje de alerta generado.
 * - array "ModelErrors" Errores del modelo si los hay.
 * - string "Pdf" Documento PDF generado en base64.
 * - string "PdfPOS" Documento PDF para POS generado en base64.
 *
 * @throws Exception Si ocurre un error durante la transacción.
 */


if ($_SESSION['win_perfil2'] == "PUNTOVENTA" || $_SESSION['win_perfil2'] == "CAJERO") {


    /* Se crean objetos de usuario y se asignan conceptos y descripciones desde parámetros. */
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $Concept = $params->Concept;
    $Description = $params->Description;
    $Reference = $params->Document;

    /* Asigna valores de parámetros a variables, asegurando que TypeDocument y Tax sean numéricos. */
    $Value = $params->Value;

    $Serie = $params->Serie;
    $TypeDocument = (is_numeric($params->TypeDocument)) ? $params->TypeDocument : 0;
    $Tax = (is_numeric($params->Tax)) ? $params->Tax : 0;

    $ProvidersThird = $params->ProvidersThird;


    /* Se crea un objeto "Egreso" con atributos específicos en PHP. */
    $Egreso = new Egreso();
    $Egreso->setTipoId(0);
    $Egreso->setDescripcion($Description);
    $Egreso->setCentrocostoId(0);
    $Egreso->setDocumento($Reference);
    $Egreso->setEstado("A");

    /* Configura propiedades de un objeto "Egreso" con valores específicos y atributos relacionados. */
    $Egreso->setValor($Value);
    $Egreso->setImpuesto($Tax);
    $Egreso->setRetraccion(0);
    $Egreso->setUsuarioId($Usuario->puntoventaId);
    $Egreso->setConceptoId($Concept);
    $Egreso->setProveedortercId($ProvidersThird);

    /* Asignación de propiedades a un objeto "Egreso" usando datos de otro objeto y valores específicos. */
    $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
    $Egreso->setSerie($Serie);
    $Egreso->setTipoDocumento($TypeDocument);

    $Egreso->setProductotercId(0);
    $Egreso->setUsucreaId(0);

    /* Se establece un ID de usuario y se inserta un registro en la base de datos. */
    $Egreso->setUsumodifId(0);


    $EgresoMySqlDAO = new EgresoMySqlDAO();
    $EgresoMySqlDAO->insert($Egreso);
    $EgresoMySqlDAO->getTransaction()->commit();


    /* inicializa una respuesta sin errores y configura un mensaje de éxito. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
} else {

    /* Asignación de parámetros y variables iniciales para operaciones en una aplicación. */
    $BetShops = $params->BetShops;

    $estado = "A";

    $CloseBoxId = $params->CloseBoxId;
    $fechaEspecifica = '';


    /* Verifica si hay un ID de caja cerrado y obtiene información relacionada del usuario. */
    if ($CloseBoxId != "") {
        $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
        $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

        $BetShops = $UsuarioCierrecaja->getUsuarioId();

        $estado = 'C';
    }


    /* Condicional que inicializa objetos UsuarioMandante y Usuario según la variable BetShops. */
    if ($BetShops != "") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($BetShops);

    } else {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    }


    /* verifica el ID y obtiene datos del usuario correspondiente. */
    $Usucrea = $UsuarioMandante->getUsuarioMandante();

    if ($CloseBoxId != "") {

        $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);

        $Usucrea = $UsuarioMandante2->getUsuarioMandante();

        $estado = 'C';
    }


    /* asigna variables basadas en los parámetros de entrada del objeto $params. */
    $Concept = $params->Concept;
    $Description = $params->Description;
    $Reference = $params->Document;
    $Value = $params->Value;

    $Serie = $params->Serie;

    /* Verifica y asigna valores numéricos a documentos y tasas, luego inicializa un objeto Egreso. */
    $TypeDocument = (is_numeric($params->TypeDocument)) ? $params->TypeDocument : 0;
    $Tax = (is_numeric($params->Tax)) ? $params->Tax : 0;

    $ProvidersThird = $params->ProvidersThird;

    $Egreso = new Egreso();

    /* Se configuran propiedades del objeto Egreso con valores específicos como tipo, descripción y estado. */
    $Egreso->setTipoId(0);
    $Egreso->setDescripcion($Description);
    $Egreso->setCentrocostoId(0);
    $Egreso->setDocumento($Reference);
    $Egreso->setEstado($estado);
    $Egreso->setValor($Value);

    /* Configura propiedades de un objeto "Egreso" usando datos del usuario y otros parámetros. */
    $Egreso->setImpuesto($Tax);
    $Egreso->setRetraccion(0);
    $Egreso->setUsuarioId($Usuario->puntoventaId);
    $Egreso->setConceptoId($Concept);
    $Egreso->setProveedortercId($ProvidersThird);
    $Egreso->setUsucajeroId($Usuario->usuarioId);

    /* establece propiedades en un objeto "Egreso" con varios identificadores y tipos. */
    $Egreso->setSerie($Serie);
    $Egreso->setTipoDocumento($TypeDocument);

    $Egreso->setProductotercId(0);
    $Egreso->setUsucreaId($Usucrea);
    $Egreso->setUsumodifId(0);


    /* asigna una fecha específica y luego inserta un registro en la base de datos. */
    if ($fechaEspecifica != '') {
        $Egreso->fechaCrea = $fechaEspecifica;
    }

    $EgresoMySqlDAO = new EgresoMySqlDAO();
    $EgresoMySqlDAO->insert($Egreso);

    /* Confirma la transacción y prepara una respuesta sin errores para el usuario. */
    $EgresoMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
}


/* Se crea un objeto Egreso y se obtiene información relacionada con él. */
$Egreso = new Egreso($Egreso->getEgresoId());
$consecutive = "E" . $Egreso->getUsuarioId() . "-" . $Egreso->getConsecutivo();
$descripcion = $Egreso->getDescripcion();
$concepto = "";

if ($Egreso->getConceptoId() != "" && $Egreso->getConceptoId() != "0") {
    $Concepto = new Concepto($Egreso->getConceptoId());
    $concepto = $Concepto->getDescripcion();
}


/* asigna descripciones basadas en condiciones del objeto Egreso y sus propiedades. */
if ($Egreso->getProductotercId() != "" && $Egreso->getProductotercId() != "0") {
    $ProductoTercero = new ProductoTercero($Egreso->getProductotercId());
    $concepto = "";
    $descripcion = "Producto " . $ProductoTercero->getDescripcion();
}


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

/* Crea un PDF en formato A4 horizontal con márgenes espejados y contenido HTML. */
$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');
$mpdf->WriteHTML($pdf);


/* genera un PDF y obtiene su contenido y tipo de archivo. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* genera y codifica documentos PDF en formato base64 para transferencias. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["Pdf"] = base64_encode($data);


$mpdf = new mPDF('c', array(45, 350), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');


/* Configura márgenes, modo de visualización y carga estilos en un PDF usando mPDF. */
$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($pdf);


/* Genera un PDF y lo guarda en '/tmp/mpdf.pdf' para su posterior uso. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* codifica datos en base64 para uso en aplicaciones y respuestas JSON. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["PdfPOS"] = base64_encode($data);

