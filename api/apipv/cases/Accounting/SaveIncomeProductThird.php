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
 * @param object $params Objeto que contiene los parámetros necesarios para guardar un ingreso.
 * @param string $params ->ProductThird Identificador del producto tercero.
 * @param string $params ->Description Descripción del ingreso.
 * @param string $params ->Document Documento de referencia del ingreso.
 * @param float $params ->Value Valor del ingreso.
 * @param string $params ->BetShops Identificador de las tiendas de apuestas.
 * @param string $params ->CloseBoxId Identificador del cierre de caja.
 *
 * @return array Respuesta con el estado de la operación.
 * @return bool $response["HasError"] Indica si hubo un error.
 * @return string $response["AlertType"] Tipo de alerta.
 * @return string $response["AlertMessage"] Mensaje de alerta.
 * @return array $response["ModelErrors"] Errores del modelo.
 * @return string $response["Pdf"] PDF generado en base64.
 * @return string $response["PdfPOS"] PDF POS generado en base64.
 *
 * @throws Exception Captura y muestra cualquier excepción que ocurra durante la operación.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


if ($_SESSION['win_perfil2'] == "PUNTOVENTA" || $_SESSION['win_perfil2'] == "CAJERO") {


    /* instanciar objetos de usuario y producto basado en información de sesión. */
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $ProductThird = $params->ProductThird;

    $ProductoTercero = new ProductoTercero($ProductThird);


    /* Se crea un objeto y se obtienen proveedores y descripciones. */
    $ProductoterceroUsuario = new ProductoterceroUsuario("", "", $Usuario->puntoventaId, $ProductThird);

    $ProvidersThird = $ProductoTercero->getProveedortercId();

    $Concept = 0;
    $Description = $params->Description;

    /* Se está creando un objeto "Ingreso" y configurando su tipo de identificación. */
    $Reference = $params->Document;
    $Value = $params->Value;


    $Ingreso = new Ingreso();
    $Ingreso->setTipoId(0);

    /* configura atributos de un objeto 'Ingreso' con valores específicos. */
    $Ingreso->setDescripcion($Description);
    $Ingreso->setCentrocostoId(0);
    $Ingreso->setDocumento($Reference);
    $Ingreso->setEstado("A");
    $Ingreso->setValor($Value);
    $Ingreso->setImpuesto(0);

    /* Configura un objeto $Ingreso con diversos parámetros relacionados a un usuario y transacción. */
    $Ingreso->setRetraccion(0);
    $Ingreso->setUsuarioId($Usuario->puntoventaId);
    $Ingreso->setConceptoId($Concept);
    $Ingreso->setProductotercId($ProductThird);
    $Ingreso->setProveedortercId($ProvidersThird);
    $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());


    /* Código para insertar un ingreso y actualizar un usuario en base de datos. */
    $Ingreso->setUsucreaId(0);
    $Ingreso->setUsumodifId(0);

    try {


        $IngresoMySqlDAO = new IngresoMySqlDAO();
        $IngresoMySqlDAO->insert($Ingreso);

        $ProductoterceroUsuario->setCupo("cupo - " . "'" . $Value . "'");

        $ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO($IngresoMySqlDAO->getTransaction());
        $ProductoterceroUsuarioMySqlDAO->update($ProductoterceroUsuario);

        $IngresoMySqlDAO->getTransaction()->commit();
    } catch (Exception $e) {
        /* Captura excepciones y las imprime para diagnóstico de errores en PHP. */

        print_r($e);
    }

    /* Código para gestionar una respuesta exitosa sin errores en un sistema. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

} else {

    /* Inicializa variables a partir de parámetros, incluyendo BetShops y CloseBoxId. */
    $BetShops = $params->BetShops;

    $estado = "A";

    $CloseBoxId = $params->CloseBoxId;
    $fechaEspecifica = '';


    /* Condicional que inicializa objeto y obtiene fecha y usuario si $CloseBoxId no está vacío. */
    if ($CloseBoxId != "") {
        $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
        $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

        $BetShops = $UsuarioCierrecaja->getUsuarioId();

        $estado = 'C';
    }


    /* inicializa objetos dependiendo del valor de $BetShops. */
    if ($BetShops != "") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($BetShops);

    } else {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    }

    /* obtiene usuarios de un objeto dependiendo de la condición del ID. */
    $Usucrea = $UsuarioMandante->getUsuarioMandante();

    if ($CloseBoxId != "") {

        $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);

        $Usucrea = $UsuarioMandante2->getUsuarioMandante();

        $estado = 'C';
    }


    /* Se asignan valores y se crean objetos relacionados con productos y proveedores. */
    $ProductThird = $params->ProductThird;

    $ProductoTercero = new ProductoTercero($ProductThird);

    $ProductoterceroUsuario = new ProductoterceroUsuario("", "", $Usuario->puntoventaId, $ProductThird);

    $ProvidersThird = $ProductoTercero->getProveedortercId();


    /* inicializa variables y crea un objeto "Ingreso" con parámetros proporcionados. */
    $Concept = 0;

    $Description = $params->Description;
    $Reference = $params->Document;
    $Value = $params->Value;


    $Ingreso = new Ingreso();

    /* Configura propiedades de un objeto "Ingreso" con diferentes valores y descripciones. */
    $Ingreso->setTipoId(0);
    $Ingreso->setDescripcion($Description);
    $Ingreso->setCentrocostoId(0);
    $Ingreso->setDocumento($Reference);
    $Ingreso->setEstado($estado);
    $Ingreso->setValor($Value);

    /* Se establece información de un ingreso, incluyendo impuestos, usuario, concepto y proveedor. */
    $Ingreso->setImpuesto(0);
    $Ingreso->setRetraccion(0);
    $Ingreso->setUsuarioId($Usuario->puntoventaId);
    $Ingreso->setConceptoId($Concept);
    $Ingreso->setProductotercId($ProductThird);
    $Ingreso->setProveedortercId($ProvidersThird);

    /* Se asignan IDs de usuario y fecha de creación a un objeto Ingreso. */
    $Ingreso->setUsucajeroId($Usuario->usuarioId);

    $Ingreso->setUsucreaId($Usucrea);
    $Ingreso->setUsumodifId(0);

    if ($fechaEspecifica != '') {
        $Ingreso->fechaCrea = $fechaEspecifica;
    }


    /* Inserta un ingreso y establece un cupo en la base de datos MySQL. */
    $IngresoMySqlDAO = new IngresoMySqlDAO();
    $IngresoMySqlDAO->insert($Ingreso);

    $ProductoterceroUsuario->setCupo("cupo - " . "'" . $Value . "'");

    $ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO($IngresoMySqlDAO->getTransaction());

    /* Actualiza datos de usuario y finaliza la transacción sin errores. */
    $ProductoterceroUsuarioMySqlDAO->update($ProductoterceroUsuario);


    $IngresoMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;

    /* Configura una respuesta con estado exitoso y sin errores. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
}


/* inicializa un clasificador y obtiene información de ingresos específicos. */
$TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


$Ingreso = new Ingreso($Ingreso->getIngresoId());
$consecutive = "I" . $Ingreso->getUsuarioId() . "-" . $Ingreso->getConsecutivo();
$descripcion = $Ingreso->getDescripcion();

/* Se asigna una descripción al concepto si su ID es válido. */
$concepto = "";

if ($Ingreso->getConceptoId() != "" && $Ingreso->getConceptoId() != "0") {
    $Concepto = new Concepto($Ingreso->getConceptoId());
    $concepto = $Concepto->getDescripcion();
}


/* valida y asigna descripciones basado en identificadores de productos y tipos. */
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


/* Genera un documento PDF con detalles de un ingreso y su soporte. */
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

/* Código para generar un PDF en orientación horizontal con mPDF y márgenes espejo. */
$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($pdf);


/* genera un PDF y lo guarda en el directorio temporal. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* genera y codifica un PDF en base64 para su uso. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["Pdf"] = base64_encode($data);
$mpdf = new mPDF('c', array(45, 350), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');


/* Configura márgenes espejo y modo de visualización para PDF usando mPDF. */
$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($pdf);


/* Genera un PDF y obtiene su contenido como cadena desde el archivo creado. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* codifica datos y PDF en formato base64 para transferencia. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["PdfPOS"] = base64_encode($data);
