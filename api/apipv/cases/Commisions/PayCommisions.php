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
 * Commisions/PayCommisions
 *
 * Pagar la comisión de un usuario.
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param int $params->Id ID del resumen de comisión
 * 
 * 
 *
 * @return array $response Respuesta con los siguientes atributos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta generada.
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Pdf (string): PDF generado en formato Base64.
 *
 * @throws Exception Si ocurre un error durante la transacción o generación del PDF.
 */


/* Se crea un objeto `UsucomisionResumen` utilizando un identificador proporcionado. */
$Id = $params->Id;

$UsucomisionResumen = new UsucomisionResumen($Id);
if ($UsucomisionResumen->getEstado() == "P") {


    /* gestiona un resumen de comisiones, actualizando estado y valor pagado. */
    $valorPagado = $UsucomisionResumen->getComision();
    $UsucomisionResumen->setUsumodifId($_SESSION['usuario']);
    $UsucomisionResumen->setEstado('I');
    $UsucomisionResumen->setValorPagado($valorPagado);

    $UsucomisionResumenMySqlDAO = new UsucomisionResumenMySqlDAO();

    /* Actualiza información del usuario y agrega créditos de afiliación en la base de datos. */
    $UsucomisionResumenMySqlDAO->update($UsucomisionResumen);

    $Usuario = new Usuario($UsucomisionResumen->getUsuarioId());
    $Usuario->creditosAfiliacion = 'creditos_afiliacion' + $UsucomisionResumen->getValorPagado();

    $UsuarioMySqlDAO = new UsuarioMySqlDAO($UsucomisionResumenMySqlDAO->getTransaction());

    /* Actualiza un usuario en la base de datos y genera un PDF de soporte. */
    $UsuarioMySqlDAO->update($Usuario);

    $UsucomisionResumenMySqlDAO->getTransaction()->commit();


    $pdf = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody>
<tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">SOPORTE</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Soporte No.:&nbsp;&nbsp;' . $Id . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . date("Y-m-d H:i:s") . '</font></td></tr>
<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor:&nbsp;&nbsp;' . $UsucomisionResumen->getValorPagado() . '</font></td></tr>
</tbody></table>';


    require_once "mdpdf/mpdf.php";

    /* Crea un PDF con mPDF, configurando márgenes y estilos personalizados. */
    $mpdf = new mPDF('c', array(80, 150));

    $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

    $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($pdf);


    /* genera un PDF y lo guarda en la ruta especificada. */
    $mpdf->Output('/tmp' . "mpdf.pdf", "F");

    $path = '/tmp' . 'mpdf.pdf';

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);

    /* codifica datos en formato Base64 para su uso en aplicaciones web. */
    $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

    $encoded_html = base64_encode($pdf);

    $response["Pdf"] = base64_encode($data);

}

/* Código que inicializa una respuesta sin errores y con un mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
