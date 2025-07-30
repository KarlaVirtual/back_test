<?php

use Backend\dto\ApiTransaction;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concesionario;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
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
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\SitioTracking;
use Backend\dto\Submenu;
use Backend\dto\Template;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiUsuario;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
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
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
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
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionApiUsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransapiusuarioLogMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
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
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

include "includes.php";

//print_r(time());


/* Configura cabeceras HTTP para permitir solicitudes CORS y gestionar contenido JSON. */
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');

/* permite CORS, establece un tiempo de ejecución y ajusta la zona horaria. */
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
$_ENV["ENABLEDSETMAX_EXECUTION_TIME"] = '1';


$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);

/* inicializa un valor de zona horaria y define variables para URI y URL. */
$timezone = 0;

$URI = $_SERVER["REQUEST_URI"];
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();



/* lee datos JSON de entrada y establece una respuesta inicial. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$response = array();
$response["error"] = false;
$response["code"] = 0;

$ENCRYPTION_KEY = "D!@#$%^&*";


/* habilita la depuración y configura variables según condiciones específicas. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
    $_ENV["debugFixed2"] = '1';
}


/* gestiona solicitudes OPTIONS y procesa una cadena URI dividiéndola en un array. */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$arraySuper = explode("/", current(explode("?", $URI)));

$time = time();

try {

    /* verifica un estado "BLOCKED" y lanza una excepción si es encontrado. */
    try {
        $responseEnable = file_get_contents(__DIR__ . '/../../logSit/enabled');
    } catch (Exception $e) {
    }

    if ($responseEnable == 'BLOCKED') {
        throw new Exception("Inusual Detected", "11");
    }

    switch ($arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1]) {
        case 'machineprint/deposit':

            /* establece un encabezado HTML y obtiene un parámetro 'id' de la solicitud. */
            header('Content-Type: text/html; charset=UTF-8');
            $id = $_REQUEST['id'];

            $parameterR = $id;
            $parameterR = (str_replace(" ", "+", $parameterR));

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            /* desencripta el parámetro $parameterR usando la configuración de entorno. */
            $id = $ConfigurationEnvironment->decrypt($parameterR);
            if ($id != '') {


                /* Se crea una instancia de la clase UsuarioRecarga utilizando un identificador. */
                $UsuarioRecarga = new UsuarioRecarga();


                $Id = $id;
                $seguir = true;

                $UsuarioRecarga = new UsuarioRecarga($Id);

                /* Se crean dos objetos de la clase Usuario usando diferentes identificadores. */
                $Usuario = new Usuario($UsuarioRecarga->usuarioId);
                $UsuarioPuntoVenta = new Usuario($UsuarioRecarga->puntoventaId);
                if ($seguir) {


                    if ($UsuarioPuntoVenta->usuarioId == 25415) {

                        try {

                            /* Genera un código HTML con información específica de un usuario para crear un PDF. */
                            $Clasificador = new Clasificador("", "TEMRECRE");

                            $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
                            $html_barcode = $Template->templateHtml;
                            if ($html_barcode != '') {
                                $html_barcode = str_replace("#depositnumber#", $UsuarioRecarga->recargaId, $html_barcode);

                                $html_barcode = str_replace("#userid#", $UsuarioRecarga->usuarioId, $html_barcode);
                                $html_barcode = str_replace("#login#", $Usuario->login, $html_barcode);
                                $html_barcode = str_replace("#name#", $Usuario->nombre, $html_barcode);
                                $html_barcode = str_replace("#idpointsale#", $UsuarioPuntoVenta->usuarioId, $html_barcode);
                                $html_barcode = str_replace("#namepointsale#", $UsuarioPuntoVenta->nombre, $html_barcode);
                                $html_barcode = str_replace("#value#", $UsuarioRecarga->valor, $html_barcode);
                                $html_barcode = str_replace("#creationdate#", $UsuarioRecarga->fechaCrea, $html_barcode);

                                $html_barcode = str_replace("#tax#", '0', $html_barcode);

                                $html_barcode = str_replace("#totalvalue#", $UsuarioRecarga->valor, $html_barcode);

                                $pdf = $html_barcode;
                                $response["Pdf"] = $pdf;

                            }
                        } catch (Exception $e) {
                            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir el flujo del programa. */


                        }
                    } else {


                        /* Crea un nuevo objeto "Mandante" utilizando el mandante del usuario actual. */
                        $Mandante = new Mandante($Usuario->mandante);
                        $pdf = '<head>
    <style>
        @page {
            /* prevents electrons additional margins around sheet-content (the first printedPage of: the html already sized 8.5x22) */
            margin-top: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
            /* prevents electron from printing a blank page between each sheet (the first and second printedPages of the tall html) */
            margin-bottom: -.5cm;
        }
        body {
            font-family: \'Roboto\', sans-serif;
            text-decoration: none;
            font-size: 12px;
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
<div style="width:220px; border:1px solid grey; padding: 15px;margin-left: 0px;margin-top: 0px;">
    <table style="width:100%;height: 355px;">
        <tbody>
        <tr >
            <td align="left" valign="top"><img style="width: 100%; padding-left: 20px;"
                                               src="' . $Mandante->logoPdf . '" alt="logo">
            </td>
            <td align="right" valign="top" style="display: block;text-align:center;"><font
                        style="text-align:center;font-size:20px;font-weight:bold;">RECIBO<br>DE RECARGA</font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">Recibo de Recarga No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">' . $UsuarioRecarga->recargaId . ' </font>
            </td>
        </tr>

        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">Fecha:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">' . $UsuarioRecarga->getFechaCrea() . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">Punto de Venta:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">' . $UsuarioPuntoVenta->nombre . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">No. de Cliente</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">' . $Usuario->usuarioId . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">Nombre Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">' . $Usuario->nombre . '</font>
            </td>
        </tr>';


                        /* Condicionalmente genera una fila en PDF mostrando la cédula del cliente. */
                        if ($UsuarioPuntoVenta->paisId == '2' and $UsuarioPuntoVenta->mandante == "0") {
                            $pdf = $pdf . '<tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">Cedula Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">' . $Registro->cedula . '</font>
            </td>
        </tr>';
                        }


                        $pdf = $pdf . '
        <tr>
            <td align="left" valign="top"><font
                        style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">Email: </font>
            </td>
            <td align="right" valign="top"><font
                        style="padding-left:5px;text-align:left;font-size:12px;font-weight:normal;">' . $Usuario->login . ' </font>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                        style="padding-left:5px;text-align:left;font-size:14px;font-weight:bold;">Valor recarga :</font>
            </td>
            <td align="right" valign="top"><font
                        style="padding-left:5px;text-align:left;font-size:14px;">' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . '</font></td>
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



                        /* Condicional que añade información de RUC si el país del usuario es Perú. */
                        if ($Usuario->paisId == 173) {
                            $pdf .= '
    <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
    </div>';

                        }


                        /* Genera un código HTML para mostrar un código de barras basado en un ID. */
                        $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
            <div class="barcodecell" style="  text-align: center;"><barcode code="' . $UsuarioRecarga->getRecargaId() . '" type="I25" class="barcode" /></div>
        </div>
    </div>
</div>
</body>

';


                        /* Asigna el valor de $pdf al índice "Pdf" en el array $response. */
                        $response["Pdf"] = $pdf;


                    }
                } else {
                    /* inicializa una respuesta vacía cuando no se cumplen ciertas condiciones. */



                    $response["pos"] = 0;
                    $response["total_count"] = 0;
                    $response["data"] = array();

                }


                /* Imprime el contenido del array "Pdf" de la variable $response. */
                print_r($response["Pdf"]);
            }

            /* "exit();" finaliza la ejecución del script en programas de programación. */
            exit();

            break;
        case 'machineprint/withdraw':

            /* Define encabezado HTML, recibe un ID y reemplaza espacios por signos más. */
            header('Content-Type: text/html; charset=UTF-8');
            $id = $_REQUEST['id'];

            $parameterR = $id;
            $parameterR = (str_replace(" ", "+", $parameterR));


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            /* cifra y descifra un ID usando un entorno de configuración. */
            $id = $ConfigurationEnvironment->encrypt("615804");
            $id = $ConfigurationEnvironment->decrypt($parameterR);
            if ($id != '') {

                /* crea instancias de clases relacionadas con cuentas y usuarios. */
                $CuentaCobro = new CuentaCobro($id);
                $consecutivo_recarga = $CuentaCobro->cuentaId;
                $Usuario = new Usuario($CuentaCobro->usuarioId);
                $Registro = new Registro("", $CuentaCobro->usuarioId);

                $CuentaCobroMySqlDAO2 = new CuentaCobroMySqlDAO();

                /* Se asignan variables para almacenar información de usuario y cuenta de cobro. */
                $ClientId = $Usuario->usuarioId;
                $clave = $CuentaCobroMySqlDAO2->getClaveD($CuentaCobro->cuentaId);

                $amount = $CuentaCobro->valor;
                $valorImpuesto = $CuentaCobro->impuesto;
                $valorPenalidad = $CuentaCobro->costo;

                /* Se define un mensaje de estado con diseño para impresión de una cuenta. */
                $valorFinal = $CuentaCobro->valor;


                $status_message = '
<style>
    @page {
        /* prevents electrons additional margins around sheet-content (the first printedPage of: the html already sized 8.5x22) */
        margin-top: 0cm;
        margin-left: 0cm;
        margin-right: 0cm;
        /* prevents electron from printing a blank page between each sheet (the first and second printedPages of the tall html) */
        margin-bottom: -.5cm;
    }</style><table style="width:220px;height: 355px;/* border:1px solid black; */">
    <tbody><tr><td align="center" valign="top"><div style="height:5px;">&nbsp;</div></td></tr>';


                /* Genera una tabla HTML con información sobre una nota de retiro. */
                $status_message .= '
    <tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr>';

                if ($Usuario->paisId == 173) {


                    /* asigna nombres a tipos de documentos según un código específico. */
                    $tipoDoc = $Registro->tipoDoc;

                    switch ($tipoDoc) {
                        case "P":
                            $tipoDoc = 'Pasaporte';
                            break;
                        case "C":
                            $tipoDoc = 'DNI';
                            break;
                        case "E":
                            $tipoDoc = 'Carnet de extranjeria';
                            break;

                    }


                    /* Construye un mensaje HTML con detalles de tipo de documento y número de identificación. */
                    $status_message .= "
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Tipo de Doc: :&nbsp;&nbsp;" . $tipoDoc . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Documento:&nbsp;&nbsp;" . $Registro->cedula . "</font></td></tr>
    ";
                }


                /* Construye un mensaje de estado con detalles financieros en formato HTML. */
                $status_message .= '<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . $amount . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto:&nbsp;&nbsp;' . $valorImpuesto . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Costo:&nbsp;&nbsp;' . $valorPenalidad . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar:&nbsp;&nbsp;' . $valorFinal . '</font></td></tr>
    <tr><td align="center" valign="top"><div style="height:5px;">&nbsp;</div></td></tr>
    </tbody></table>';


                try {

                    /* Se crea un clasificador y se genera una plantilla HTML con un código de barras. */
                    $Clasificador = new Clasificador("", "TEMRECNORE");

                    $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
                    $html_barcode = $Template->templateHtml;
                    if ($html_barcode != '') {

                        /* Reemplaza marcadores en una plantilla de código HTML con datos de una cuenta. */
                        $html_barcode = str_replace("#idnotewithdrawal#", $CuentaCobro->cuentaId, $html_barcode);
                        $html_barcode = str_replace("#withdrawalnotenumber#", $CuentaCobro->cuentaId, $html_barcode);
                        $html_barcode = str_replace("#value#", $CuentaCobro->valor, $html_barcode);
                        $html_barcode = str_replace("#totalvalue#", $CuentaCobro->valor, $html_barcode);
                        $html_barcode = str_replace("#tax#", $CuentaCobro->impuesto, $html_barcode);
                        $html_barcode = str_replace("#keynotewithdrawal#", $clave, $html_barcode);

                        /* reemplaza marcadores en un HTML y define estilos de impresión. */
                        $html_barcode = str_replace("#creationdate#", $CuentaCobro->fechaCrea, $html_barcode);
                        $html_barcode = str_replace("#userid#", $CuentaCobro->usuarioId, $html_barcode);
                        $html_barcode = str_replace("#name#", $Usuario->nombre, $html_barcode);
                        $status_message = '<style>
    @page {
        /* prevents electrons additional margins around sheet-content (the first printedPage of: the html already sized 8.5x22) */
        margin-top: 0cm;
        margin-left: 0cm;
        margin-right: 0cm;
        /* prevents electron from printing a blank page between each sheet (the first and second printedPages of the tall html) */
        margin-bottom: -.5cm;

    }
    body{
        width: 220px;
    }figure{
         margin: 0px;
     }</style>' . $html_barcode;

                    }
                } catch (Exception $e) {
                    /* Captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución del script. */


                }



                /* Imprime el contenido de la variable $status_message en un formato legible. */
                print_r($status_message);
            }

            /* El comando "exit();" finaliza la ejecución del script o programa en curso. */
            exit();

            break;
        case 'betshop/geoip':


            /* obtiene la dirección IP del usuario, considerando proxies si es necesario. */
            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

            /* valida la longitud de una cadena IP y lanza una excepción si es válida. */
            $ip = explode(",", $ip)[0];


            if (strlen($ip) >= 20) {
                if (strlen($ip) >= 20) {

                    throw new Exception("Datos de login incorrectos", "50003");
                }
            }
// echo "Remote IP:$ip-$URI";


//Se hace explode para tomar la primera IP

            /* asigna una IP y limpia caracteres de una variable obtenida por GET. */
            $dir_ip = $ip;

//Se captura la URL para de allí extraer el numero del punto de venta
            $usuario = $_GET['info'];

//Depurarar caracteres
            $dir_ip = DepurarCaracteres($dir_ip);


            /* extrae un token de la URI y define reglas para una operación. */
            $URI = $_SERVER["REQUEST_URI"];

            $token = (explode("info=", $URI)[1]);
            $rules = array();

// array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "2", "op" => "eq"));

            /* Se crean reglas de filtrado y se codifican en formato JSON. */
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);


            /* Configura la localización y obtiene datos de usuarios en formato JSON. */
            setlocale(LC_ALL, 'czech');


            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom("usuario_token_interno.*,usuario.usuario_id", "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $data = json_decode($data);



            /* inicializa un arreglo de respuesta sin errores y con código cero. */
            $response["error"] = 0;
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* Creación de un objeto Usuario y obtención de transacciones del registro correspondiente. */
                $Usuario = new Usuario($data->data[0]->{'usuario.usuario_id'});

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $Transaction = $UsuarioLogMySqlDAO->getTransaction();

                $UsuarioLog = new UsuarioLog();

                /* establece propiedades para un objeto de registro de usuario en un sistema. */
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId(0);
                $UsuarioLog->setUsuariosolicitaIp($ip);

                $UsuarioLog->setTipo("DIRIPBETSHOP");

                /* Registra cambios de estado y valores de usuario en la base de datos. */
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->dirIp);
                $UsuarioLog->setValorDespues($dir_ip);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);


                $UsuarioLogMySqlDAO->insert($UsuarioLog);


                /* Actualiza la IP de un usuario en la base de datos y confirma la transacción. */
                $Usuario->usuarioIp = $dir_ip;

                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                $UsuarioMySqlDAO->update($Usuario);

                $Transaction->commit();
            } else {
                /* Lanza una excepción con mensaje y código al fallar el login. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'user/chat-search':


            /* Asigna un token y userid; obtiene userid de la solicitud si está vacío. */
            $token = $params->token;

            $userid = $params->userid;

            if ($userid == "") {
                $userid = $_REQUEST['userid'];

            }



            /* verifica campos vacíos y lanza excepciones si son inexistentes. */
            if ($token == "") {
// throw new Exception("Field: Key", "50001");

            }
            if ($userid == "") {
                throw new Exception("Field: document, userid, phone", "50001");

            }



            /* Se define una configuración inicial para limitar filas y ordenar elementos en un conjunto. */
            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];


            /* Inicializa variables para gestionar respuesta y errores en el código. */
            $tokenSec = true;


            $response["error"] = 0;
            $response["code"] = 0;


            if ($tokenSec) {


                /* verifica si $userid no está vacío y crea un objeto Usuario. */
                if ($userid != "") {
                    $Usuario = new Usuario($userid);

                } else {

                    if ($document != "") {

                        /* Se crean reglas para validar campos de un registro según condiciones específicas. */
                        $rules = [];

                        array_push($rules, array("field" => "registro.cedula", "data" => "$document", "op" => "eq"));
                        array_push($rules, array("field" => "usuario.mandante", "data" => $data->data[0]->{"usuario.mandante"}, "op" => "eq"));
                        if ($phone != "") {
                            array_push($rules, array("field" => "registro.celular", "data" => "$phone", "op" => "eq"));

                        }



                        /* Crea un filtro JSON para obtener usuarios personalizados desde la base de datos. */
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $Usuario = new Usuario();

                        $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

                        /* decodifica JSON y crea un objeto Usuario con un ID específico. */
                        $usuarios = json_decode($usuarios);

                        $Usuario = new Usuario($usuarios->data[0]->{"usuario.usuario_id"});


                    }
                }

                /* verifica el perfil del usuario y lanza una excepción si no es válido. */
                $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                $Registro = new Registro('', $Usuario->usuarioId);

                if ($UsuarioPerfil->getPerfilId() != 'USUONLINE') {
                    throw new Exception("No existe Usuario", "24");
                }



                /* asigna información del usuario a un array de respuesta. */
                $response["idUser"] = $Usuario->usuarioId;
                $response["idCasino"] = $UsuarioMandante->usumandanteId;
                $response["name"] = $Usuario->nombre;
                $response["identification"] = $Registro->cedula;
                $response["email"] = $Usuario->login;
                $response["phone"] = $Registro->celular;

                /* asigna datos del usuario a un array de respuesta. */
                $response["balance"] = $Usuario->getBalance();
                $response["state"] = $Usuario->estado;

                $response["contingency"] = $Usuario->contingencia;
                $response["contingencySports"] = $Usuario->contingenciaDeportes;
                $response["contingencyCasino"] = $Usuario->contingenciaCasino;

                /* asigna datos de un usuario a un arreglo de respuesta. */
                $response["contingencyLiveCasino"] = $Usuario->contingenciaCasvivo;
                $response["contingencyVirtual"] = $Usuario->contingenciaVirtuales;
                $response["contingencyPoker"] = $Usuario->contingenciaPoker;

                $response["note"] = $Usuario->observ;


            } else {
                /* Se lanza una excepción de error si los datos de inicio de sesión son incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'user/search':


            /* Código que extrae parámetros de una fuente para gestionar información de un usuario. */
            $shop = $params->shop;
            $token = $params->token;
            $document = $params->document;
            $userid = $params->userid;
            $pais = $params->country;
            $phone = $params->phone;



            /* lanza excepciones si ciertos campos están vacíos. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }
            if ($document == "" && $userid == "" && $phone == "") {
                throw new Exception("Field: document, userid, phone", "50001");

            }

            /* Lanza una excepción si 'document' o 'phone' están llenos y el 'shop' es específico. */
            if ($document != '' && $shop == '1211624') {
                throw new Exception("Field: document, userid, phone", "50001");
            }

            if ($phone != '' && $shop == '1211624') {
                throw new Exception("Field: document, userid, phone", "50001");
            }


            /* verifica si el país está vacío y lanza una excepción si es así. */
            if ($pais == "") {
                throw new Exception("Field: Pais", "50001");

            }

            $MaxRows = 1;

            /* Se inicializan variables para el manejo de elementos ordenados y reglas en un script. */
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            /* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_log.* ";


$UsuarioLog = new UsuarioLog();
$data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);*/


            /* construye un filtro de reglas para consultas de usuario y token. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro a JSON y establece la configuración regional en checo. */
            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario.mandante,usuario_token_interno.* ";



            /* Se obtiene y decodifica datos de usuarios con un token interno en formato JSON. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Asigna el valor 0 a la clave "code" en el arreglo "$response". */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* Crea un objeto Usuario si $userid no está vacío. */
                if ($userid != "") {
                    $Usuario = new Usuario($userid);

                } else {

                    if ($document != "") {

                        /* Se definen reglas para validar campos de un registro mediante condiciones específicas. */
                        $rules = [];

                        array_push($rules, array("field" => "registro.cedula", "data" => "$document", "op" => "eq"));
                        array_push($rules, array("field" => "usuario.mandante", "data" => $data->data[0]->{"usuario.mandante"}, "op" => "eq"));
                        if ($phone != "") {
                            array_push($rules, array("field" => "registro.celular", "data" => "$phone", "op" => "eq"));

                        }


                        /* Se construye un filtro para validar datos de usuario y se convierte a JSON. */
                        array_push($rules, array("field" => "usuario.plataforma", "data" => "0", "op" => "eq"));
                        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $Usuario = new Usuario();


                        /* Se obtienen y decodifican usuarios personalizados para crear una instancia de Usuario. */
                        $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
                        $usuarios = json_decode($usuarios);

                        $Usuario = new Usuario($usuarios->data[0]->{"usuario.usuario_id"});


                    }
                }

                /* Validación de usuario y perfil antes de continuar con la ejecución del programa. */
                $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

                if ($Usuario->mandante != 8) {
                    if ($UsuarioPerfil->getPerfilId() != 'USUONLINE') {
                        throw new Exception("No existe Usuario", "24");
                    }
                } else {
                    /* Verifica el perfil del usuario y lanza excepción si no es válido. */


                    if ($UsuarioPerfil->getPerfilId() != 'USUONLINE' && $UsuarioPerfil->getPerfilId() != 'PUNTOVENTA' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO2' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO3') {
                        throw new Exception("No existe Usuario", "24");
                    }
                }


                /* verifica si un usuario pertenece al mismo país que un punto de venta. */
                $UsuarioPuntoVenta = new Usuario($shop);

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }


                /* Verifica si el usuario pertenece al mismo mandante; de lo contrario, lanza una excepción. */
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }

                $response["name"] = $Usuario->nombre;

                /* Asigna el identificador de usuario al arreglo de respuesta en PHP. */
                $response["userid"] = $Usuario->usuarioId;

            } else {
                /* Lanza una excepción con mensaje específico si los datos de login son incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'system/ping':
            /* Código que responde a un "ping" con estado y tiempo actual. */


            $response["error"] = 0;
            $response["code"] = 0;
            $response["time"] = time();
            $response["message"] = 'ok';

            break;

        case 'user/deposit':


            /* inicializa variables con parámetros relacionados a una tienda y usuario. */
            $start_time = microtime(true);

            $shop = $params->shop;
            $token = $params->token;
            $document = $params->document;
            $userid = $params->userid;

            /* asigna variables y verifica si el token está vacío, lanzando una excepción. */
            $pais = $params->country;
            $amount = $params->amount;
            $transactionId = $params->transactionId;
            $shopReference = $params->shopReference;

            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            /* lanza excepciones si ciertos campos están vacíos o no proporcionados. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }
            if ($document == "" && $userid == "") {
                throw new Exception("Field: document, userid", "50001");

            }

            /* lanza excepciones si los campos 'amount' o 'pais' están vacíos. */
            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");

            }
            if ($pais == "") {
                throw new Exception("Field: Pais", "50001");

            }

            /* lanza una excepción si el ID de transacción está vacío. */
            if ($transactionId == "") {
                throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;

            /* Se inicializan variables y un array vacío para almacenar reglas en programación. */
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            /* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_log.* ";


$UsuarioLog = new UsuarioLog();
$data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);*/


            /* Se definen reglas de filtrado para una consulta utilizando un arreglo. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro a JSON y selecciona campos de una base de datos. */
            $json = json_encode($filtro);

//setlocale(LC_ALL, 'czech');


            $select = " usuario.mandante,usuario_token_interno.* ";



            /* Se crea un token interno de usuario y se decodifica en formato JSON. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Asigna el valor 0 a la clave "code" en el arreglo $response. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* Valida si la tienda está en la lista y el monto es menor a 1. */
                if (in_array($shop, array(1784692, 853460))) {


                    if (floatval($amount) < 1) {
                        throw new Exception("Field: Valor", "50001");

                    }
                }

                /* Valida que el monto sea mayor que 1 para la tienda específica. */
                if (in_array($shop, array(853460))) {


                    if (floatval($amount) < 1) {
                        throw new Exception("Field: Valor", "50001");

                    }
                }


                /* Crea un objeto Usuario si el identificador de usuario no está vacío. */
                if ($userid != "") {
                    $Usuario = new Usuario($userid);

                } else {


                    /* valida un documento y obtiene usuarios personalizados según reglas definidas. */
                    if ($document != "") {
                        $rules = [];

                        array_push($rules, array("field" => "registro.cedula", "data" => "$document", "op" => "eq"));
                        array_push($rules, array("field" => "usuario.mandante", "data" => $data->data[0]->{"usuario.mandante"}, "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $Usuario = new Usuario();

                        $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
                        $usuarios = json_decode($usuarios);

                        $Usuario = new Usuario($usuarios->data[0]->{"usuario.usuario_id"});


                    }
                }

                /* Valida el acceso de un usuario según su perfil y mandante en un punto de venta. */
                $UsuarioPuntoVenta = new Usuario($shop);
                $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

                if ($Usuario->mandante != 8 && $Usuario->mandante != 19) {
                    if ($UsuarioPerfil->getPerfilId() != 'USUONLINE') {
                        throw new Exception("No existe Usuario", "24");
                    }
                } else {
                    /* Verifica el perfil del usuario; lanza excepción si no es válido. */


                    if ($UsuarioPerfil->getPerfilId() != 'USUONLINE' && $UsuarioPerfil->getPerfilId() != 'PUNTOVENTA' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO2' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO3') {
                        throw new Exception("No existe Usuario", "24");
                    }
                }



                /* Verifica coincidencia de países entre usuario y punto de venta, lanzando excepciones si no coinciden. */
                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }
                if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
                    throw new Exception("Código de país incorrecto", "10018");

                }

                /* Verifica si el usuario pertenece al socio y si no está eliminado; lanza excepción. */
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }

                if ($Usuario->eliminado == 'S') {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }




                /*
                 * Verifica el estado de la contingencia para depósitos en puntos de venta o redes aliadas
                 *
                 * Este bloque de código intenta obtener el valor de la contingencia para depósitos
                 * en redes aliadas o puntos de venta. Si ocurre una excepción, se establece el valor
                 * de la contingencia como inactivo ("I").
                 *
                 * en caso de tener una contingencia activa se deja el registro del intento fallido del deposito.
                 */



                try {
                    $Clasificador = new Clasificador('', 'CONTINGENCYRETAIL');
                    $UsuarioConfiguracion = new UsuarioConfiguracion($userid, 'A', $Clasificador->getClasificadorId());
                    $Contingencia = $UsuarioConfiguracion->getEstado();

                } catch (Exception $e) {
                    $Contingencia = "I";
                }


                if($Contingencia == "A"){


                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($userid);
                    $AuditoriaGeneral->setUsuarioIp("");
                    $AuditoriaGeneral->setUsuariosolicitaId($shop);
                    $AuditoriaGeneral->setUsuariosolicitaIp("");
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("FALLOENDEPOSITOPV");
                    $AuditoriaGeneral->setValorAntes(0);
                    $AuditoriaGeneral->setValorDespues(0);
                    $AuditoriaGeneral->setUsucreaId($userid);
                    $AuditoriaGeneral->setUsumodifId(0);
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion('Intento fallido de deposito por red aliada');



                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

                    throw new Exception("Esta cuenta tiene una restricción activa. El usuario debe comunicarse con soporte para más información.", "300166");
                }




//Consultamos cul es el maximo de deposito diario


                /* obtiene el máximo depósito diario configurado para un clasificador específico. */
                try {
                    $Clasificador = new Clasificador("", "LIMITDAILYDEPOSITSPERPOINTSOFSALE");
                    $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                    $MaxDepositDay = $UsuarioConfiguracion->getValor();
                } catch (Exception $e) {

                }
                if ($MaxDepositDay != 0 && $MaxDepositDay != '' && $MaxDepositDay != null) {



                    /* Consulta SQL que suma los depósitos de usuarios activos en un rango de tiempo específico. */
                    $fecha_actual = date('Y-m-d');

// Definir el rango de tiempo desde las 00:00:00 hasta las 11:59:59 del día actual
                    $inicio_dia = $fecha_actual . " 00:00:00";
                    $fin_dia = $fecha_actual . " 23:59:59";

                    $sql = " SELECT SUM(transaccion_api_usuario.valor) AS total_depositos
FROM transaccion_api_usuario
INNER JOIN usuario_recarga  ON (usuario_recarga.recarga_id = transaccion_api_usuario.identificador) INNER JOIN usuario_perfil ON
(transaccion_api_usuario.usuario_id = usuario_perfil.usuario_id)
WHERE usuariogenera_id = $shop
AND tipo = 0
AND usuario_recarga.estado = 'A'
AND usuario_perfil.perfil_id IN('CONCESIONARIO', 'CONCESIONARIO2', 'CONCESIONARIO3', 'PUNTOVENTA')
AND usuario_recarga.fecha_crea BETWEEN '$inicio_dia' AND '$fin_dia'";



                    /* Se inicia una transacción para ejecutar una consulta SQL sobre BonoInterno. */
                    $BonoInterno = new BonoInterno();
                    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

                    $transaccion = $BonoInternoMySqlDAO->getTransaction();
                    $transaccion->getConnection()->beginTransaction();
                    $ValorRecargaDia = $BonoInterno->execQuery($transaccion, $sql);


                    /* Valida si el depósito diario supera un límite para ciertos perfiles de usuario. */
                    $total_depositos = $ValorRecargaDia[0]->{'.total_depositos'};


                    try {

                        $UsuarioPerfil = new UsuarioPerfil($userid);
                        $Perfil = $UsuarioPerfil->perfilId;

                        if ($Perfil == "CONCESIONARIO" || $Perfil == "CONCESIONARIO2" || $Perfil == "CONCESIONARIO3" || $Perfil == "PUNTOVENTA") {


                            if ($total_depositos + $amount > $MaxDepositDay && $total_depositos != "" && $total_depositos != "Null" && $MaxDepositDay != 0) {
                                throw new Exception("El valor de la recarga que intentas procesar supera el maximo permitido por dia", 300032);
                            }

                        }
                    } catch (Exception $e) {
                        /* Captura excepciones y vuelve a lanzar si el código es 300032. */

                        if ($e->getCode() == 300032) {
                            throw $e;
                        }
                    }

                }



                /* maneja excepciones al verificar condiciones de depósito en un clasificador. */
                try {


                    $Clasificador = new Clasificador("", "DEPOSITBETSHOP");

                    $MandanteDetalle = new MandanteDetalle("", $UsuarioPuntoVenta->mandante, $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

                    if ($MandanteDetalle->valor == "A") {
                        throw new Exception("No es posible realizar depositos", "300006");
                    }

                } catch (Exception $e) {
                    /* Maneja excepciones y vuelve a lanzar si el código no es 34 o 41. */

                    if ($e->getCode() != 34 && $e->getCode() != 41) {
                        throw $e;
                    }
                }

                try {

                    /* Se crea un objeto UsuarioPerfil y se obtiene su perfil asociado. */
                    $UsuarioPerfil = new UsuarioPerfil($userid);
                    $Perfil = $UsuarioPerfil->perfilId;

                    if ($Perfil == "CONCESIONARIO" || $Perfil == "CONCESIONARIO2" || $Perfil == "CONCESIONARIO3" || $Perfil == "PUNTOVENTA") {


                        /* asigna un concesionario basado en el perfil de usuario. */
                        if ($Perfil == "PUNTOVENTA") {
                            $Concesionario = new Concesionario($userid);
                            $ConcesionarioPrincipal = $Concesionario->usupadreId;
                        } else if ($Perfil == "CONCESIONARIO2") {
                            $Concesionario = new Concesionario('', '', $userid);
                            $ConcesionarioPrincipal = $Concesionario->usupadreId;
                        } else if ($Perfil == "CONCESIONARIO3") {
                            /* Aquí se asigna el concesionario principal según el perfil del usuario. */

                            $Concesionario = new Concesionario("", "", "", $userid);
                            $ConcesionarioPrincipal = $Concesionario->usupadreId;
                        } else if ($Perfil == "CONCESIONARIO") {
                            /* Condicional que asigna un usuario a la variable si su perfil es "CONCESIONARIO". */

                            $ConcesionarioPrincipal = $userid;
                        }


                        /* Se establece un clasificador y se obtienen IDs permitidos de concesionarios. */
                        try {
                            $Clasificador = new Clasificador("", "CONCESIONARIOSALLOWED");
                            $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                            $IdsAllowed = $UsuarioConfiguracion->getValor();

                            $idsConcesionariosPermitidos = explode(",", $IdsAllowed);

                        } catch (Exception $e) {
                            /* Manejo de excepciones en PHP para evitar interrupciones por errores. */


                        }

//preguntamos si el boton de restriccion esta activo


                        /* Se inicializan clases para configurar un clasificador y obtener restricciones de usuario. */
                        try {
                            $Clasificador = new Clasificador("", "ALLOWSDEPOSITTOALLIEDNETWORKS");
                            $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                            $Restriccion = $UsuarioConfiguracion->getValor();


                        } catch (Exception $e) {
                            /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */


                        }


                        /* verifica restricciones de concesionarios y lanza una excepción si son violadas. */
                        if ($Restriccion == "A" && !in_array($ConcesionarioPrincipal, $idsConcesionariosPermitidos)) {
                            throw new Exception("La red a la que pertenece el usuario no está habilitada para gestionar depósitos o retiros a través de " . $UsuarioPuntoVenta->nombre . ". Por favor, comuníquese con Ecuabet", 300030);
                        }
                    }
                } catch (Exception $e) {
                    /* Captura excepciones y relanza si el código de la excepción es 300030. */

                    if ($e->getCode() == 300030) {
                        throw $e;
                    }
                }
                /**
                 * Actualizamos consecutivo Recarga
                 */

                /*$Consecutivo = new Consecutivo("", "REC", "");

$consecutivo_recarga = $Consecutivo->numero;

$consecutivo_recarga++;

$ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

$Consecutivo->setNumero($consecutivo_recarga);


$ConsecutivoMySqlDAO->update($Consecutivo);

$ConsecutivoMySqlDAO->getTransaction()->commit();*/



                /* Código para obtener una transacción y configurar el ID de un usuario en recarga. */
                $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();


                $UsuarioRecarga = new UsuarioRecarga();
//$UsuarioRecarga->setRecargaId($consecutivo_recarga);
                $UsuarioRecarga->setUsuarioId($Usuario->usuarioId);

                /* Se establecen atributos de un objeto UsuarioRecarga con valores específicos. */
                $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
                $UsuarioRecarga->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
                $UsuarioRecarga->setValor($amount);
                $UsuarioRecarga->setPorcenRegaloRecarga(0);
                $UsuarioRecarga->setDirIp(0);
                $UsuarioRecarga->setPromocionalId(0);

                /* Configura atributos de un objeto UsuarioRecarga con valores iniciales. */
                $UsuarioRecarga->setValorPromocional(0);
                $UsuarioRecarga->setHost(0);
                $UsuarioRecarga->setMandante($Usuario->mandante);
                $UsuarioRecarga->setPedido(0);
                $UsuarioRecarga->setPorcenIva(0);
                $UsuarioRecarga->setMediopagoId(0);

                /* Se crea una recarga de usuario con IVA, estado activo y versión 2. */
                $UsuarioRecarga->setValorIva(0);
                $UsuarioRecarga->setEstado('A');
                $UsuarioRecarga->setVersion(2);

                $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

                $consecutivo_recarga = $UsuarioRecarga->recargaId;



                /* Se crea y configura una transacción con detalles del usuario y monto. */
                $TransaccionApiUsuario = new TransaccionApiUsuario();

                $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransaccionApiUsuario->setValor(($amount));
                $TransaccionApiUsuario->setTipo(0);

                /* Se configura una transacción API con parámetros y estado de respuesta "OK". */
                $TransaccionApiUsuario->setTValue(json_encode($params));
                $TransaccionApiUsuario->setRespuestaCodigo("OK");
                $TransaccionApiUsuario->setRespuesta("OK");
                $TransaccionApiUsuario->setTransaccionId($transactionId);

                $TransaccionApiUsuario->setUsucreaId(0);

                /* Establece un ID y verifica si la transacción fue procesada, lanzando un error si es así. */
                $TransaccionApiUsuario->setUsumodifId(0);


                if ($TransaccionApiUsuario->existsTransaccionIdAndProveedor("OK")) {

//  Si la transaccionId ha sido procesada, reportamos el error
                    throw new Exception("Transaccion ya procesada", "10001");

                }


                /* Código para registrar una transacción y su log en base de datos. */
                $TransaccionApiUsuario->setIdentificador($consecutivo_recarga);
                $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                $TransapiusuarioLog = new TransapiusuarioLog();

                $TransapiusuarioLog->setIdentificador($consecutivo_recarga);

                /* Se establece información de transacción en un objeto de registro de usuario. */
                $TransapiusuarioLog->setTransaccionId($transactionId);
                $TransapiusuarioLog->setTValue(json_encode($params));
                $TransapiusuarioLog->setTipo(0);
                $TransapiusuarioLog->setValor($amount);
                $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);



                /* Se inserta un registro en la base de datos utilizando transacciones. */
                $TransapiusuarioLog->setUsucreaId(0);
                $TransapiusuarioLog->setUsumodifId(0);


                $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);
                $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

                if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {


                    /* Se crea un objeto CupoLog y se configuran sus propiedades estableciendo valores específicos. */
                    $CupoLog = new CupoLog();
                    $CupoLog->setUsuarioId($Usuario->puntoventaId);
                    $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
                    $CupoLog->setTipoId('E');
                    $CupoLog->setValor($amount);
                    $CupoLog->setUsucreaId(0);

                    /* Código establece propiedades de un objeto CupoLog y crea un DAO para MySQL. */
                    $CupoLog->setMandante(0);
                    $CupoLog->setTipocupoId('A');
                    $CupoLog->setRecargaId($consecutivo_recarga);


                    $CupoLogMySqlDAO = new CupoLogMySqlDAO($Transaction);


                    /* Inserta el registro de CupoLog y verifica saldo antes de transferir créditos. */
                    $CupoLogMySqlDAO->insert($CupoLog);

                    $PuntoVenta = new PuntoVenta('', $Usuario->puntoventaId);
                    $cant = $PuntoVenta->setBalanceCreditosBase($CupoLog->getValor(), $Transaction);

                    if ($cant == 0) {
                        throw new Exception("No tiene saldo para transferir", "111");
                    }


                } else {
                    /* asigna un crédito al usuario basado en un monto y transacción. */

                    $Usuario->credit($amount, $Transaction);


                }



                /* Inicializa un objeto `FlujoCaja` con datos de fecha, hora y usuario. */
                $rowsUpdate = 0;

                $FlujoCaja = new FlujoCaja();
                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);

                /* establece propiedades del objeto $FlujoCaja relacionadas con una recarga. */
                $FlujoCaja->setTipomovId('E');
                $FlujoCaja->setValor($UsuarioRecarga->getValor());
                $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
                $FlujoCaja->setTraslado('N');
                $FlujoCaja->setFormapago1Id(1);

                /* configura propiedades de un objeto FlujoCaja según condiciones específicas. */
                $FlujoCaja->setCuentaId('0');
                if ($CupoLog != null) {
                    $FlujoCaja->setRecargaId(0);
                    $FlujoCaja->setcupologId($CupoLog->getCupologId());
                }

                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }


                /* asigna valor cero si las formas de flujo de caja están vacías. */
                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }

                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }


                /* Establece valores predeterminados si ciertos campos están vacíos en FlujoCaja. */
                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }

                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }


                /* Se verifica el IVA y, si está vacío, se establece en cero antes de insertar. */
                if ($FlujoCaja->getValorIva() == "") {
                    $FlujoCaja->setValorIva(0);
                }

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


                $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                if ($rowsUpdate > 0) {

                    /* Se crea una instancia de PuntoVenta con un ID de usuario específico. */
                    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->usuarioId);

                    if (floatval($PuntoVenta->getCreditosBase()) - floatval($amount) < 0) {
                        throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100002");
                    }

                    try {



                        /* Se crean objetos y se cargan ciudades desde la base de datos mediante DAO. */
                        $Registro = new Registro('', $Usuario->usuarioId);

                        $CiudadMySqlDAO = new CiudadMySqlDAO();

                        $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
                        $CiudadPuntoVenta = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);



                        /* Se cuentan depósitos de usuario y se preparan detalles en un array. */
                        $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $Usuario->usuarioId . "'");

                        $detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];
                        $detalleDepositos = $detalleDepositos - 1;

                        $detalles = array(
                            "Depositos" => $detalleDepositos,
                            "DepositoEfectivo" => true,
                            "MetodoPago" => 0,
                            "ValorDeposito" => $UsuarioRecarga->getValor(),
                            "PaisPV" => $UsuarioPuntoVenta->paisId,
                            "DepartamentoPV" => $CiudadPuntoVenta->deptoId,
                            "CiudadPV" => $PuntoVenta->ciudadId,
                            "PuntoVenta" => $UsuarioPuntoVenta->puntoventaId,
                            "PaisUSER" => $Usuario->paisId,
                            "DepartamentoUSER" => $Ciudad->deptoId,
                            "CiudadUSER" => $Registro->ciudadId,
                            "MonedaUSER" => $Usuario->moneda,

                        );


                        /* Se crea un bono interno y se agrega con detalles del usuario y transacción. */
                        $BonoInterno = new BonoInterno();
                        $detalles = json_decode(json_encode($detalles));

                        $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);

                    } catch (Exception $e) {
                        /* captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


                    }



                    /* Calcula la duración en horas, minutos y segundos entre dos tiempos. */
                    $end_time = microtime(true);
                    $duration = $end_time - $start_time;
                    $hours = (int)($duration / 60 / 60);
                    $minutes = (int)($duration / 60) - $hours * 60;

                    $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;


                    /* verifica condiciones y lanza excepciones si se cumplen ciertos criterios. */
                    if ($seconds >= 15) {
                        throw new Exception("Error General", "100000");
                    }



//$Transaction->commit();



                    /* Valida el ID de punto de venta y registra el historial de usuario. */
                    if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {


                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(60);
                        $UsuarioHistorial->setValor($CupoLog->getValor());
                        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


                    } else {
                        /* Se crea un historial de usuario y se guarda en la base de datos. */



                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($amount);
                        $UsuarioHistorial->setExternoId($consecutivo_recarga);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                    }


                    /* actualiza el saldo y verifica la disponibilidad de cupo. */
                    $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$amount, $Transaction);

                    if ($rowsUpdate == null || $rowsUpdate <= 0) {
                        throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100000");
                    }


                    $UsuarioHistorial = new UsuarioHistorial();

                    /* configura un historial de usuario con diferentes propiedades asociadas. */
                    $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('S');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(10);

                    /* Se actualiza historial de usuario con datos de recarga y se guarda en base de datos. */
                    $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                    $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

                    $Transaction->commit();



                    /* Calcula la duración en horas, minutos y segundos a partir del tiempo transcurrido. */
                    $end_time = microtime(true);
                    $duration = $end_time - $start_time;
                    $hours = (int)($duration / 60 / 60);
                    $minutes = (int)($duration / 60) - $hours * 60;
                    $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;

                    if ($seconds >= 15) {

                        /* Ejecuta un script PHP para procesar una transacción y actualiza el consecutivo. */
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'TIME OPERATORAPI DEPOSIT  " . $seconds . " s '.$transactionId. '#alertas-integraciones' > /dev/null & ");

                        sleep(1);

                        $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "0");

                        /**
                         * Actualizamos consecutivo Recarga
                         */

                        $UsuarioRecarga = new UsuarioRecarga($TransaccionApiUsuario->getIdentificador());



                        /* Verifica estado de recarga y marca como eliminada si es necesario. */
                        if ($UsuarioRecarga->getEstado() != "A") {
                            throw new Exception("La recarga no se puede eliminar", "50001");
                        }
                        $UsuarioRecarga->setEstado('I');
                        $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
                        $UsuarioRecarga->setUsueliminaId($UsuarioRecarga->getPuntoventaId());



                        /* Se crea un DAO para manejar transacciones y actualizar datos de usuario. */
                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                        $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();

                        $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);

                        $puntoventa_id = $UsuarioRecarga->getPuntoventaId();

                        /* Código para obtener un valor de recarga de usuario y preparar registro en flujo de caja. */
                        $valor = $UsuarioRecarga->getValor();

                        $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

                        $FlujoCaja = new FlujoCaja();
                        $FlujoCaja->setFechaCrea(date('Y-m-d'));

                        /* Código para establecer propiedades de un objeto 'FlujoCaja' con información de transacciones. */
                        $FlujoCaja->setHoraCrea(date('H:i'));
                        $FlujoCaja->setUsucreaId($puntoventa_id);
                        $FlujoCaja->setTipomovId('S');
                        $FlujoCaja->setValor($valor);
                        $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                        $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

                        /* establece valores por defecto para métodos de pago en un flujo de caja. */
                        $FlujoCaja->setDevolucion('S');

                        if ($FlujoCaja->getFormapago1Id() == "") {
                            $FlujoCaja->setFormapago1Id(0);
                        }

                        if ($FlujoCaja->getFormapago2Id() == "") {
                            $FlujoCaja->setFormapago2Id(0);
                        }


                        /* asigna cero a valores vacíos en dos propiedades de FlujoCaja. */
                        if ($FlujoCaja->getValorForma1() == "") {
                            $FlujoCaja->setValorForma1(0);
                        }

                        if ($FlujoCaja->getValorForma2() == "") {
                            $FlujoCaja->setValorForma2(0);
                        }


                        /* Asigna 0 a CuentaId y PorcenIva si están vacíos en FlujoCaja. */
                        if ($FlujoCaja->getCuentaId() == "") {
                            $FlujoCaja->setCuentaId(0);
                        }

                        if ($FlujoCaja->getPorcenIva() == "") {
                            $FlujoCaja->setPorcenIva(0);
                        }


                        /* Verifica si el valor del IVA está vacío y lo establece en cero antes de insertar. */
                        if ($FlujoCaja->getValorIva() == "") {
                            $FlujoCaja->setValorIva(0);
                        }

                        $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                        $FlujoCajaMySqlDAO->insert($FlujoCaja);
//print_r(time());


                        /* verifica y actualiza el saldo de un punto de venta. */
                        $PuntoVenta = new PuntoVenta("", $puntoventa_id);


                        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);

                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                            throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100000");
                        }

//print_r(time());


                        /* Crea un objeto de saldo ajustado para un usuario con datos específicos. */
                        $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                        $SaldoUsuonlineAjuste->setTipoId('S');
                        $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                        $SaldoUsuonlineAjuste->setValor($valor);
                        $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));

                        /* ajusta saldo de usuario y establece motivo en caso de estar vacío. */
                        $SaldoUsuonlineAjuste->setUsucreaId($UsuarioPuntoVenta->usuarioId);
                        $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                        $SaldoUsuonlineAjuste->setObserv("Reversion recarga API " . $UsuarioRecarga->getRecargaId());
                        if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                            $SaldoUsuonlineAjuste->setMotivoId(0);
                        }

                        /* obtiene la dirección IP del usuario y ajusta parámetros de estado. */
                        $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                        $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                        $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());

                        $UsuarioRecarga->setEstado('I');

                        /* Actualiza la fecha de eliminación y el usuario de eliminación en la base de datos. */
                        $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
                        $UsuarioRecarga->setUsueliminaId(0);

                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);

                        $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);


                        /* interactúa con una base de datos para ajustar saldo de usuario. */
                        $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                        $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);

//print_r(time());


                        $Usuario->debit($UsuarioRecarga->getValor(), $Transaction);



                        /* Se crea un historial de usuario con datos específicos y valores predeterminados. */
                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('S');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);

                        /* Se establece un historial de usuario y se inserta en la base de datos. */
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($valor);
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

//print_r(time());


                        /* Código que crea y configura un objeto UsuarioHistorial con datos específicos. */
                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($puntoventa_id);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);

                        /* configura un historial de usuario y prepara una transacción API. */
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

//$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
//$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

//print_r(time());

                        $TransaccionApiUsuario = new TransaccionApiUsuario();


                        /* Configura una transacción de API con detalles del usuario y parámetros específicos. */
                        $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                        $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                        $TransaccionApiUsuario->setValor(($amount));
                        $TransaccionApiUsuario->setTipo(0);
                        $TransaccionApiUsuario->setTValue(json_encode($params));
                        $TransaccionApiUsuario->setRespuestaCodigo("OK");

                        /* Código que establece parámetros para una transacción de usuario en una API. */
                        $TransaccionApiUsuario->setRespuesta("OK");
                        $TransaccionApiUsuario->setTransaccionId($transactionId);

                        $TransaccionApiUsuario->setUsucreaId(0);
                        $TransaccionApiUsuario->setUsumodifId(0);


                        $TransaccionApiUsuario->setIdentificador($UsuarioRecarga->getRecargaId());

                        /* Se crea un DAO para gestionar transacciones y un objeto para registros. */
                        $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                        $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

//print_r(time());

                        $TransapiusuarioLog = new TransapiusuarioLog();


                        /* registra una transacción de recarga con datos del usuario y parámetros. */
                        $TransapiusuarioLog->setIdentificador($UsuarioRecarga->getRecargaId());
                        $TransapiusuarioLog->setTransaccionId($transactionId);
                        $TransapiusuarioLog->setTValue(json_encode($params));
                        $TransapiusuarioLog->setTipo(3);
                        $TransapiusuarioLog->setValor($amount);
                        $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

                        /* Se asignan IDs de usuario a un objeto y se inicializa el DAO correspondiente. */
                        $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                        $TransapiusuarioLog->setUsucreaId(0);
                        $TransapiusuarioLog->setUsumodifId(0);


                        $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

                        /* Insertar un registro, realizar commit y manejar excepciones en base de datos. */
                        $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

//print_r(time());

                        $Transaction->commit();

                        throw new Exception("Error General", "100000");
                    }



                    /* Actualiza la fecha y monto del primer depósito de un usuario en la base de datos. */
                    if ($Usuario->fechaPrimerdeposito == "") {
                        $Usuario = new Usuario($Usuario->usuarioId);

                        $Usuario->fechaPrimerdeposito = date('Y-m-d H:i:s');
                        $Usuario->montoPrimerdeposito = $UsuarioRecarga->getValor();
                        $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO2->update($Usuario);
                        $UsuarioMySqlDAO2->getTransaction()->commit();
                    }



                    /* Bloque de código en JavaScript para manejar excepciones; actualmente vacío. */
                    try {





                    } catch (Exception $e) {
                        /* Registra un aviso en el sistema ante una excepción con código y mensaje. */

                        syslog(LOG_WARNING, "ERRORPROVEEDORAPI :" . $e->getCode() . ' - ' . $e->getMessage());
                    }

                } else {
                    /* Lanza una excepción con un mensaje de error y un código específico. */

                    throw new Exception("Error General", "100000");
                }



                /* asigna identificadores a una respuesta estructurada en un array. */
                $response["transactionId"] = $Transapiusuariolog_id;
                $response["depositId"] = $consecutivo_recarga;


            } else {
                /* Lanza una excepción con un mensaje y código en caso de error de login. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'user/withdraw':


            /* inicializa variables con parámetros de configuración para una tienda. */
            $start_time = microtime(true);

            $shop = $params->shop;
            $token = $params->token;
            $nota = $params->withdrawId;
            $clave = $params->password;

            /* Asigna país y transacción; lanza excepción si el token está vacío. */
            $pais = $params->country;

            $transactionId = $params->transactionId;

            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            /* Verifica campos vacíos y lanza excepciones si están vacíos. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            if ($nota == "") {
                throw new Exception("Field: Nota", "50001");

            }


            /* verifica campos vacíos y lanza excepciones si están vacíos. */
            if ($clave == "") {
                throw new Exception("Field: Clave", "50001");

            }
            if ($pais == "") {
                throw new Exception("Field: Pais", "50001");

            }

            /* Verifica si transactionId está vacío y lanza una excepción si es así. */
            if ($transactionId == "") {
                throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;

            /* Se inicializan variables para ordenar y omitir filas en un conjunto de reglas. */
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            /* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_log.* ";


$UsuarioLog = new UsuarioLog();
$data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);*/


            /* Se construye un conjunto de reglas de filtro para una consulta. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un array a JSON y configura la localización en checo. */
            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_token_interno.* ";



            /* Se obtiene usuarios en formato JSON y se inicializa respuesta sin errores. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $data = json_decode($data);


            $response["error"] = 0;

            /* verifica credenciales y retorna un ID de transacción en formato JSON. */
            $response["code"] = 0;
            if ($nota == '2926653' && $clave == '40768') {
                $response["transactionId"] = '1076577';
                print_r(json_encode($response));
                exit();
            }


            if (oldCount($data->data) > 0) {



                /* Se crean instancias de CuentaCobro y Usuario, y se obtiene un valor específico. */
                $CuentaCobro = new CuentaCobro($nota, "", $clave);
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $valor = $CuentaCobro->getValor();

                $UsuarioPuntoVenta = new Usuario($shop);

                /* crea un punto de venta y verifica si se permiten retiros. */
                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

                try {
                    $Clasificador = new Clasificador("", "WITHDRAWALBETSHOP");

                    $MandanteDetalle = new MandanteDetalle("", $UsuarioPuntoVenta->mandante, $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

                    if ($MandanteDetalle->valor == "A") {
                        throw new Exception("No se puede realizar retiros actualmente", "300006");
                    }

                } catch (Exception $e) {
                    /* Maneja excepciones, re-lanza solo si el código no es 34 o 41. */

                    if ($e->getCode() != 34 && $e->getCode() != 41) {
                        throw $e;
                    }
                }


                try {



                    /* Crea un clasificador y obtiene el valor máximo de retiro del usuario configurado. */
                    try {
                        $Clasificador = new Clasificador("", "DAILYWITHDRAWALPOINTLIMIT");
                        $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                        $ValueMaxWithdrawal = $UsuarioConfiguracion->getValor();

                    } catch (Exception $e) {
                        /* Manejo de excepciones en PHP; captura errores sin realizar ninguna acción en este caso. */


                    }
                    if ($ValueMaxWithdrawal != '' && $ValueMaxWithdrawal != null && $ValueMaxWithdrawal != 0) {



                        /* Se crea un objeto UsuarioPerfil y se obtiene su perfil asociado. */
                        $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

                        $Perfil = $UsuarioPerfil->perfilId;

                        if ($Perfil == "CONCESIONARIO" || $Perfil == "CONCESIONARIO2" || $Perfil == "CONCESIONARIO3" || $Perfil == "PUNTOVENTA") {


                            /* Consulta la suma de retiros del día actual para usuarios específicos. */
                            $fecha_actual = date('Y-m-d');

// Definir el rango de tiempo desde las 00:00:00 hasta las 11:59:59 del día actual
                            $inicio_dia = $fecha_actual . " 00:00:00";
                            $fin_dia = $fecha_actual . " 23:59:59";


//hacemos la suma del total de retiros que hay en el dia de hoy
                            $sql = "SELECT SUM(transaccion_api_usuario.valor) AS total_retiros
FROM transaccion_api_usuario
INNER JOIN cuenta_cobro ON cuenta_cobro.cuenta_id = transaccion_api_usuario.identificador INNER JOIN usuario_perfil ON
(transaccion_api_usuario.usuario_id = usuario_perfil.usuario_id)
WHERE usuariogenera_id = $shop
AND cuenta_cobro.estado = 'I'
AND tipo = 1
AND usuario_perfil.perfil_id IN('CONCESIONARIO', 'CONCESIONARIO2', 'CONCESIONARIO3', 'PUNTOVENTA')
AND cuenta_cobro.fecha_pago BETWEEN '$inicio_dia' AND '$fin_dia'";



                            /* Inicia una transacción y ejecuta una consulta para BonoInterno en MySQL. */
                            $BonoInterno = new BonoInterno();
                            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

                            $transaccion = $BonoInternoMySqlDAO->getTransaction();
                            $transaccion->getConnection()->beginTransaction();
                            $ValorRetiradoDia = $BonoInterno->execQuery($transaccion, $sql);


                            /* verifica si el retiro diario supera el límite permitido, lanzando una excepción. */
                            $total_retiros = $ValorRetiradoDia[0]->{'.total_retiros'};

                            if ($total_retiros + $valor >= $ValueMaxWithdrawal and $ValueMaxWithdrawal != "0" and $ValueMaxWithdrawal != "" and $ValueMaxWithdrawal != "null") {
                                throw new Exception("el valor del la nota de retiro sobre pasa la cantidad de retiro por dia", 300029);
                            }

                        }
                    }

                } catch (Exception $e) {
                    /* Captura excepciones específicas y relanza si el código es 300029. */

                    if ($e->getCode() == 300029) {
                        throw $e;
                    }
                }

//consultar boton global de retiros


                /* crea objetos para clasificar usuarios y obtener valores de configuraciones. */
                try {
                    $Clasificador = new Clasificador("", "ISACTIVATEPAYWITHDRAWALALLIES");
                    $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                    $globalWithdrawals = $UsuarioConfiguracion->getValor();

                } catch (Exception $e) {
                    /* Maneja excepciones en PHP sin realizar ninguna acción específica en el bloque catch. */


                }

                /* valida retiros globales y configura un segundo nivel de autorización. */
                if ($globalWithdrawals == "I") {
                    throw new Exception ("no es posible realizar retiros actualmente", 300027);
                }

//consultar boton de segundo nivel de retiro

                try {
                    $Clasificador = new Clasificador("", "ACTIVATESECONDLEVELPOINTOFSALEWITHDRAWALS");
                    $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                    $IsActivateWithdral = $UsuarioConfiguracion->getValor();
                } catch (Exception $e) {
                    /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución del script. */


                }
                if ($IsActivateWithdral == "A") {
                    try {


                        /* Se crea un objeto de perfil de usuario utilizando el ID del usuario. */
                        $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

                        $Perfil = $UsuarioPerfil->perfilId;

                        if ($Perfil == "CONCESIONARIO" || $Perfil == "CONCESIONARIO2" || $Perfil == "CONCESIONARIO3" || $Perfil == "PUNTOVENTA") {

//validamos que el global de retiros este activado si no inactivamos los retiros para los concesionarios



                            /* asigna concesionarios según el perfil del usuario. */
                            if ($Perfil == "PUNTOVENTA") {
                                $Concesionario = new Concesionario($Usuario->usuarioId);
                                $ConcesionarioPrincipal = $Concesionario->usupadreId;
                            } else if ($Perfil == "CONCESIONARIO2") {
                                $Concesionario = new Concesionario("", "", $Usuario->usuarioId);
                                $ConcesionarioPrincipal = $Concesionario->usupadre2Id;
                            } else if ($Perfil == "CONCESIONARIO3") {
                                /* Crea un objeto "Concesionario" si el perfil es "CONCESIONARIO3". */

                                $Concesionario = new Concesionario("", "", "", $Usuario->usuarioId);
                            } else if ($Perfil == "CONCESIONARIO") {
                                /* Condicional que asigna el ID del usuario a la variable si es concesionario. */

                                $ConcesionarioPrincipal = $Usuario->usuarioId;
                            }


//preguntamos cuales concesionarios y redes estan habilitadas para retirar

                            /* inicializa un clasificador y obtiene concesionarios permitidos de la configuración. */
                            try {
                                $Clasificador = new Clasificador("", "CONCESIONARIOSALLOWED");
                                $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                                $ConcesionariosPermitidos = $UsuarioConfiguracion->getValor();

                                $ConcesionariosPermitidos = explode(",", $ConcesionariosPermitidos);


                            } catch (Exception $e) {
                                /* Bloque para manejar excepciones en PHP sin realizar ninguna acción específica. */


                            }

//preguntamos si el usuario que esta solicitando el pago esta habilitado para retirar


                            /* Verifica si un concesionario está permitido para gestionar retiros; lanza excepción si no. */
                            if ($IsActivateWithdral == "A") {
                                if (!in_array($ConcesionarioPrincipal, $ConcesionariosPermitidos)) {
                                    throw new Exception("La red a la que pertenece el usuario no está habilitada para gestionar depósitos o retiros a través de " . $Usuario->nombre . ". Por favor, comuníquese con Ecuabet.", 300031);
                                }
                            }

                        }


                    } catch (Exception $e) {
                        /* Captura excepciones y lanza de nuevo si el código es 300027 o 300031. */

                        if ($e->getCode() == 300027) {
                            throw $e;
                        } elseif ($e->getCode() == 300031) {
                            throw $e;
                        }
                    }

                }



                /* Verifica si el usuario y el punto de venta pertenecen al mismo país. */
                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }

                if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
                    throw new Exception("Código de país incorrecto", "10018");

                }

                /* Verifica coincidencia de mandante y estado activo de la cuenta antes de proceder. */
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }

                if ($CuentaCobro->getEstado() != 'A') {
                    throw new Exception("La nota de retiro no se puede pagar porque no esta activa", "50007");
                }


                /* Verifica condiciones y lanza excepción si no se cumple; obtiene transacción de la base de datos. */
                if (($UsuarioPuntoVenta->usuarioId == 693978 || $UsuarioPuntoVenta->usuarioId == 853460 || $UsuarioPuntoVenta->usuarioId == 1211624 || $UsuarioPuntoVenta->usuarioId == 2894342) && $CuentaCobro->getMediopagoId() != $UsuarioPuntoVenta->usuarioId) {
                    throw new Exception("No existe nota de retiro", "12");
                }

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();

                /* Verifica si el valor de un pago supera el límite permitido para notas de retiro. */
                $Amount = $CuentaCobro->getValor();


                $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

                if ($UsuarioConfig->maxpagoRetiro != "" && $UsuarioConfig->maxpagoRetiro != "0") {
                    if (floatval($UsuarioConfig->maxpagoRetiro) < floatval($CuentaCobro->getValor())) {
                        throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
                    }
                }


                /* Se inicializan variables y se obtiene una transacción de la base de datos. */
                $rowsUpdate = 0;

                $ConfigurationEnvironment = new ConfigurationEnvironment();

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();

                /* Se establece una dirección IP y estado 'I' para una cuenta de cobro. */
                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);
                $CuentaCobro->setDiripCambio($dirIp);

                $CuentaCobro->setEstado('I');

                /* Actualiza una cuenta de cobro y gestiona errores si no se actualiza correctamente. */
                $CuentaCobro->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
                $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='A' ");

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                /* Se inicializa un objeto FlujoCaja con fecha, hora y ID de usuario creador. */
                $rowsUpdate = 0;

                $FlujoCaja = new FlujoCaja();
                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);

                /* configura atributos de un objeto FlujoCaja usando datos de CuentaCobro. */
                $FlujoCaja->setTipomovId('S');
                $FlujoCaja->setValor($CuentaCobro->getValor());
                $FlujoCaja->setTicketId('');
                $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
                $FlujoCaja->setMandante($CuentaCobro->getMandante());
                $FlujoCaja->setTraslado('N');

                /* inicializa identidades de recarga y forma de pago si están vacías. */
                $FlujoCaja->setRecargaId(0);

                if ($FlujoCaja->getFormapago1Id() == "") {
                    $FlujoCaja->setFormapago1Id(0);
                }

                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }


                /* Se asigna valor 0 si Forma1 o Forma2 están vacíos en FlujoCaja. */
                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }

                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }


                /* Asigna valores por defecto a CuentaId y PorcenIva si están vacíos. */
                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }

                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }


                /* Se verifica el valor del IVA y se inicializa la devolución en el flujo de caja. */
                if ($FlujoCaja->getValorIva() == "") {
                    $FlujoCaja->setValorIva(0);
                }
                $FlujoCaja->setDevolucion('');

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

                /* inserta datos y lanza una excepción si falla la inserción. */
                $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                $rowsUpdate = 0;


                /* Actualiza el balance de créditos y registra historial del usuario en el sistema. */
                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($CuentaCobro->getValor(), $Transaction);


                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
                $UsuarioHistorial->setDescripcion('');

                /* Se establecen propiedades del objeto UsuarioHistorial relacionadas con un movimiento financiero. */
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(40);
                $UsuarioHistorial->setValor($CuentaCobro->getValor());
                $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());


                /* Crea un DAO para manejar el historial de usuarios y lo inserta en la base de datos. */
                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

                if ($rowsUpdate > 0) {



                    /* inicializa una transacción con datos específicos de usuario y valor. */
                    $TransaccionApiUsuario = new TransaccionApiUsuario();

                    $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                    $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                    $TransaccionApiUsuario->setValor($valor);
                    $TransaccionApiUsuario->setTipo(1);

                    /* establece valores en un objeto de transacción API de usuario. */
                    $TransaccionApiUsuario->setTValue(json_encode($params));
                    $TransaccionApiUsuario->setRespuestaCodigo("OK");
                    $TransaccionApiUsuario->setRespuesta("OK");
                    $TransaccionApiUsuario->setTransaccionId($transactionId);

                    $TransaccionApiUsuario->setUsucreaId(0);

                    /* inserta una transacción de usuario en la base de datos. */
                    $TransaccionApiUsuario->setUsumodifId(0);

                    $TransaccionApiUsuario->setIdentificador($CuentaCobro->getCuentaId());
                    $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                    $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                    $TransapiusuarioLog = new TransapiusuarioLog();


                    /* Se registra una transacción de usuario con detalles específicos en el log. */
                    $TransapiusuarioLog->setIdentificador($CuentaCobro->getCuentaId());
                    $TransapiusuarioLog->setTransaccionId($transactionId);
                    $TransapiusuarioLog->setTValue(json_encode($params));
                    $TransapiusuarioLog->setTipo(1);
                    $TransapiusuarioLog->setValor($Amount);
                    $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

                    /* Se establece el usuario y se inicializan IDs de creación y modificación en el log. */
                    $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                    $TransapiusuarioLog->setUsucreaId(0);
                    $TransapiusuarioLog->setUsumodifId(0);


                    $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

                    /* Inserta registros de usuario y calcula el tiempo de ejecución del código. */
                    $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


                    /*$UsuarioHistorial = new UsuarioHistorial();
$UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
$UsuarioHistorial->setDescripcion('');
$UsuarioHistorial->setMovimiento('E');
$UsuarioHistorial->setUsucreaId(0);
$UsuarioHistorial->setUsumodifId(0);
$UsuarioHistorial->setTipo(40);
$UsuarioHistorial->setValor($CuentaCobro->getValor());
$UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');*/

                    $end_time = microtime(true);

                    /* Calcula duración y lanza excepción si los segundos son 25 o más. */
                    $duration = $end_time - $start_time;
                    $hours = (int)($duration / 60 / 60);
                    $minutes = (int)($duration / 60) - $hours * 60;

                    $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;

                    if ($seconds >= 25) {
                        throw new Exception("Error General", "100000");
                    }



                    /* guarda los cambios realizados en una transacción de base de datos. */
                    $Transaction->commit();
                } else {
                    /* Lanza una excepción con mensaje y código al ocurrir un error general. */

                    throw new Exception("Error General", "100000");
                }



                /* Asigna el ID de transacción a un array de respuesta en PHP. */
                $response["transactionId"] = $Transapiusuariolog_id;


            } else {
                /* Lanza una excepción con un mensaje específico por login incorrecto en el sistema. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;


        case 'user/conciliation':


            /* Asignación de variables a partir de parámetros recibidos en una función o método. */
            $shop = $params->shop;
            $token = $params->token;
            $type = $params->type;
            $data = $params->data;
            $date = $params->date;
            $dataG = $params->data;



            /* lanza excepciones si los campos "shop" o "token" están vacíos. */
            if ($shop == "") {
                throw new Exception("Field: shop", "50001");

            }
            if ($token == "") {
                throw new Exception("Field: token", "50001");

            }


            /* lanza excepciones si los campos 'type' o 'data' están vacíos. */
            if ($type == "") {
                throw new Exception("Field: type", "50001");

            }

            if ($data == "") {
//throw new Exception("Field: data", "50001");

            }

            /* Valida si la fecha está vacía y la formatea; lanza excepción si está vacía. */
            if ($date == "") {
                throw new Exception("Field: date", "50001");

            } else {
                $date = date("Y-m-d", strtotime($date));
            }

            if ($shop == '2894342') {


                /* inicializa variables para gestionar filas y parámetros de una tienda. */
                $MaxRows = 1;
                $OrderedItem = 1;
                $SkeepRows = 0;

                $shop = $params->shop;
                $token = $params->token;

                /* valida que el campo 'shop' no esté vacío y lanza una excepción. */
                $type = $params->type;
                $data = $params->data;
                $date = $params->date;


                if ($shop == "") {
                    throw new Exception("Field: $shop", "50001");

                }

                /* valida campos vacíos y lanza excepciones si no se cumplen. */
                if ($token == "") {
                    throw new Exception("Field: token", "50001");

                }

                if ($type == "") {
                    throw new Exception("Field: type", "50001");

                }


                /* lanza excepciones si los campos "data" o "date" están vacíos. */
                if ($data == "") {
                    throw new Exception("Field: data", "50001");

                }
                if ($date == "") {
                    throw new Exception("Field: date", "50001");

                }



                /* inicializa variables para controlar la paginación y almacenar reglas. */
                $MaxRows = 10000;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se definen reglas para filtrar usuarios en base a condiciones específicas. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");



                /* Inicializa un arreglo de respuesta con código y error en cero. */
                $response["error"] = 0;
                $response["code"] = 0;

                $final = [];

                if ($type == 1) {


                    /* Código para configurar parámetros y reglas de búsqueda en una base de datos. */
                    $MaxRows = 1000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Crea un filtro en formato JSON para una consulta de base de datos. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Se obtienen logs de usuario, filtrando y decodificando datos en formato JSON. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {


// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra e edita un arreglo basado en condiciones de autorización y tipo. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "inactivo";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "inactivo";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {


                            /* Crea un arreglo con datos de transacción usando información de un objeto. */
                            $array = [];

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};

                            /* Asigna valores a un arreglo basado en datos de un objeto y ajusta el estado. */
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = floatval($value->{"transapiusuario_log.valor"});
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "activo";
                            } else if ($array["status"] == 1) {
                                /* Transforma el valor de "status" en "activo" si es igual a 1. */

                                $array["status"] = "activo";
                            } else if ($array["status"] == 2) {
                                /* Cambia el estado del array a "inactivo" si el valor es 2. */

                                $array["status"] = "inactivo";
                            } else if ($array["status"] == 3) {
                                /* Esta condición convierte el estado 3 en "inactivo" dentro del arreglo. */

                                $array["status"] = "inactivo";
                            }

                            /* Añade el contenido de `$array` al final del arreglo `$final`. */
                            array_push($final, $array);
                        }
                    }

                }
                if ($type == 2) {


                    /* establece límites y reglas para filtrar registros en una consulta. */
                    $MaxRows = 1000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Construye un filtro en formato JSON para consultas, combinando reglas lógicas. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* obtiene registros de logs de usuario y los decodifica en JSON. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {

                        /* Filtra el array buscando elementos con un código de autorización específico. */
                        $array = [];


// Filtrar el array para encontrar el que tiene authorizationCode = 2010
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return
                                /* Edita el estado de un array según condiciones específicas del código de autorización. */
                                isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "inactivo";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "inactivo";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {

                            /* Convierte fechas a formato ISO 8601 y crea un arreglo con datos de transacción. */
                            $fechaActual = date('Y-m-d H:i:s');
                            $fechaActual = str_replace(" ", "T", $fechaActual);

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};
                            $array["transactionDate"] = $fecha;

                            /* asigna valores a un array basado en un objeto de transacción. */
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = floatval($value->{"transapiusuario_log.valor"});
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "activo";
                            } else if ($array["status"] == 1) {
                                /* cambia el estado de un elemento en un array a "activo" si es 1. */

                                $array["status"] = "activo";
                            } else if ($array["status"] == 2) {
                                /* Cambia el estado del arreglo a "inactivo" si es igual a 2. */

                                $array["status"] = "inactivo";
                            } else if ($array["status"] == 3) {
                                /* cambia el estatus del arreglo a "inactivo" si es igual a 3. */

                                $array["status"] = "inactivo";
                            }

                            /* Añade el contenido de `$array` al final del arreglo `$final`. */
                            array_push($final, $array);
                        }
                    }
                }


                /* crea un array con la fecha actual y elementos finales. */
                $array2 = [];
//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array2["accountingDate"] = $fechaActual;
                $array2["items"] = $final;

                /* asigna un error cero y envía datos en un arreglo. */
                $response["error"] = 0;
                $response["data"] = array($array2);


            } elseif ($shop == '4133881') {


                /* inicializa variables para gestionar filas y datos de una tienda. */
                $MaxRows = 1;
                $OrderedItem = 1;
                $SkeepRows = 0;

                $shop = $params->shop;
                $token = $params->token;

                /* valida si la variable $shop está vacía y lanza una excepción. */
                $type = $params->type;
                $data = $params->data;
                $date = $params->date;


                if ($shop == "") {
                    throw new Exception("Field: $shop", "50001");

                }

                /* Lanza excepciones si los campos "token" o "type" están vacíos. */
                if ($token == "") {
                    throw new Exception("Field: token", "50001");

                }

                if ($type == "") {
                    throw new Exception("Field: type", "50001");

                }


                /* lanza excepciones si los campos 'data' o 'date' están vacíos. */
                if ($data == "") {
                    throw new Exception("Field: data", "50001");

                }
                if ($date == "") {
                    throw new Exception("Field: date", "50001");

                }



                /* Se definen variables para controlar el número de filas y las reglas en un sistema. */
                $MaxRows = 10000;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se crean reglas de filtrado para consultar usuarios y tokens relacionados. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");



                /* Se inicializa un array de respuesta con error y código establecidos en cero. */
                $response["error"] = 0;
                $response["code"] = 0;

                $final = [];

                if ($type == 1) {


                    /* Se establece un límite de filas y se define una regla de filtrado de fecha. */
                    $MaxRows = 1000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se construye un filtro con reglas para una consulta de base de datos. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"0","3"', "op" => "in"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* convierte un filtro a JSON y obtiene registros de logs. */
                    $json = json_encode($filtro);

                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {




// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra y actualiza el estado de elementos en un array basado en condiciones específicas. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "Inactive";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "Inactive";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {

                            /* crea un array con información de transacciones de usuario. */
                            $array = [];

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};

                            /* asigna valores de un objeto a un array, formateando un monto. */
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};

                            $array["paidAmount"] = number_format($value->{"transapiusuario_log.valor"}, 2, '.', '');
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "Active";
                            } else if ($array["status"] == 1) {
                                /* cambia el estado a "Active" si el valor es 1. */

                                $array["status"] = "Active";
                            } else if ($array["status"] == 2) {
                                /* Cambia el valor de "status" a "Inactive" si es igual a 2. */

                                $array["status"] = "Inactive";
                            } else if ($array["status"] == 3) {
                                /* cambia el estado a "Inactive" si el valor es 3. */

                                $array["status"] = "Inactive";
                            }

                            /* Agrega el contenido de `$array` al final de `$final`. */
                            array_push($final, $array);
                        }
                    }

                }
                if ($type == 2) {


                    /* Es un código PHP que inicializa variables y establece reglas para filtrar datos. */
                    $MaxRows = 1000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Construye un filtro de reglas para consultas basadas en condiciones específicas. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"1","2"', "op" => "in"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* convierte un filtro a JSON y obtiene registros de una base de datos. */
                    $json = json_encode($filtro);

                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {




// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra y actualiza el estado de elementos en un array basado en condiciones específicas. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "Inactive";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "Inactive";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {

                            /* Convierte fechas a formato ISO 8601 y almacena un código de autorización en un array. */
                            $array = [];
                            $fechaActual = date('Y-m-d H:i:s');
                            $fechaActual = str_replace(" ", "T", $fechaActual);

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};

                            /* asigna valores a un arreglo basado en un objeto y formatea montos. */
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = number_format($value->{"transapiusuario_log.valor"}, 2, '.', '');
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "Active";
                            } else if ($array["status"] == 1) {
                                /* Actualiza el estado del arreglo a "Active" si es igual a 1. */

                                $array["status"] = "Active";
                            } else if ($array["status"] == 2) {
                                /* cambia el estado a "Inactive" si el valor es 2. */

                                $array["status"] = "Inactive";
                            } else if ($array["status"] == 3) {
                                /* Asigna "Inactive" al estado si el valor es igual a 3. */

                                $array["status"] = "Inactive";
                            }

                            /* Agrega un elemento `$array` al final del arreglo `$final`. */
                            array_push($final, $array);
                        }
                    }
                }


                /* Crea un arreglo con la fecha actual y elementos contables. */
                $array2 = [];
//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array2["accountingDate"] = $fechaActual;
                $array2["items"] = $final;

                /* asigna un error cero y encapsula un array en la respuesta. */
                $response["error"] = 0;
                $response["data"] = array($array2);


            } elseif ($shop == '4133881') {


                /* Se definen variables para la paginación y se inicializa un arreglo de reglas vacío. */
                $MaxRows = 100;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Agrupa condiciones para filtrar datos de usuarios mediante un array de reglas. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* convierte un filtro a JSON y establece la localidad checa para consultas. */
                $json = json_encode($filtro);

                setlocale(LC_ALL, 'czech');


                $select = " usuario_token_interno.* ";



                /* Se crea un objeto y se obtiene datos en formato JSON, sin errores. */
                $UsuarioTokenInterno = new UsuarioTokenInterno();
                $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $data = json_decode($data);


                $response["error"] = 0;

                /* Inicializa el código de respuesta y un arreglo vacío para almacenar datos finales. */
                $response["code"] = 0;


                $final = [];
                if ($type == 1) {


                    /* Se crean reglas para filtrar datos según condiciones específicas en un arreglo. */
                    $rules = [];
                    array_push($rules, array("field" => "usuario_recarga.puntoventa_id", "data" => "$shop", "op" => "eq"));
                    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));
                    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");


                    /* convierte un filtro a JSON y obtiene datos de recargas de usuarios. */
                    $json = json_encode($filtro);

                    $select = " usuario_recarga.* ";

                    $UsuarioRecarga = new UsuarioRecarga();

                    $datos = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true);


                    /* convierte datos JSON en un objeto o array de PHP. */
                    $datos = json_decode($datos);


                    /* procesa datos y crea un arreglo con información de transacciones. */
                    foreach ($datos->data as $key => $value) {
                        $array = [];

                        $array["authorizationCode"] = $value->{"usuario_recarga.recarga_id"};

                        $fecha = str_replace(" ", "T", $value->{"usuario_recarga.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = '';
                        $array["accountId"] = $value->{"usuario_recarga.usuario_id"};
                        $array["paidAmount"] = floatval($value->{"usuario_recarga.valor"});
                        $array["status"] = $value->{"usuario_recarga.estado"};

                        if ($array["status"] == 'A') {
                            $array["status"] = 'Active';
                        }
                        if ($array["status"] == 'I') {
                            $array["status"] = 'Inactive';
                        }
                        array_push($final, $array);
                    }
                }
                if ($type == 2) {


                    /* Verifica condiciones de cuentas de cobro en un comercio específico. */
                    if ($shop == '20612') {

                        foreach ($dataG->items as $datum) {
                            if ($datum->withdrawId != '') {
                                $CuentaCobro = new CuentaCobro($datum->withdrawId);

                                if ($CuentaCobro->estado != 'I') {
                                    throw new Exception("Error General", "100000");
                                }
                                if ($CuentaCobro->valor != $datum->paidAmount) {
                                    throw new Exception("Error General", "100000");
                                }
                                if ($CuentaCobro->puntoventaId != $shop) {
                                    throw new Exception("Error General", "100000");
                                }
                            }
                        }

                    }


                    /* Crea reglas de filtrado para una consulta de cuentas de cobro. */
                    $rules = [];
                    array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "$shop", "op" => "eq"));
                    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$date" . " 00:00:00", "op" => "ge"));
                    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$date" . " 23:59:59", "op" => "le"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* Se convierte un filtro a JSON y se inicializa una clase CuentaCobro. */
                    $json = json_encode($filtro);

                    $select = "cuenta_cobro.cuenta_id,cuenta_cobro.fecha_crea,cuenta_cobro.usuario_id,cuenta_cobro.valor,cuenta_cobro.estado";


                    $CuentaCobro = new CuentaCobro();

                    /* procesa datos de cuentas de cobro y transforma su formato. */
                    $datos = $CuentaCobro->getCuentasCobroCustom($select, "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true);
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {
                        $array = [];
                        $array["authorizationCode"] = $value->{"cuenta_cobro.cuenta_id"};
                        $fecha = str_replace(" ", "T", $value->{"cuenta_cobro.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = '';
                        $array["accountId"] = $value->{"cuenta_cobro.usuario_id"};
                        $array["paidAmount"] = floatval($value->{"cuenta_cobro.valor"});
                        $array["status"] = $value->{"cuenta_cobro.estado"};

                        if ($array["status"] == 'I') {
                            $array["status"] = 'Active';
                        }
                        if ($array["status"] == 'E') {
                            $array["status"] = 'Inactive';
                        }

                        array_push($final, $array);
                    }
                }


                /* almacena la fecha y hora actual en formato ISO en un arreglo. */
                $array = [];

//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array["accountingDate"] = $fechaActual;

                /* Se crea un arreglo con datos y un indicador de error en la respuesta. */
                $array["items"] = $final;

                $response["error"] = 0;
                $response["data"] = array($array);


            } elseif ($shop == '5446026') {



                /* inicializa variables para gestionar un sistema relacionado con una tienda. */
                $MaxRows = 1;
                $OrderedItem = 1;
                $SkeepRows = 0;

                $shop = $params->shop;
                $token = $params->token;

                /* obtiene parámetros y valida que el campo 'shop' no esté vacío. */
                $type = $params->type;
                $data = $params->data;
                $date = $params->date;


                if ($shop == "") {
                    throw new Exception("Field: $shop", "50001");

                }

                /* lanza excepciones si los campos "token" o "type" están vacíos. */
                if ($token == "") {
                    throw new Exception("Field: token", "50001");

                }

                if ($type == "") {
                    throw new Exception("Field: type", "50001");

                }


                /* lanza una excepción si los campos 'data' o 'date' están vacíos. */
                if ($data == "") {
                    throw new Exception("Field: data", "50001");

                }
                if ($date == "") {
                    throw new Exception("Field: date", "50001");

                }



                /* Variables iniciales para manejar límites y reglas en procesamiento de datos. */
                $MaxRows = 10000;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se define un conjunto de reglas para filtrar datos en un array. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");



                /* Inicializa un arreglo de respuesta sin errores y un arreglo final vacío. */
                $response["error"] = 0;
                $response["code"] = 0;

                $final = [];

                if ($type == 1) {


                    /* Código establece límites para procesar registros y define reglas de filtrado. */
                    $MaxRows = 1000000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se construye un filtro en formato JSON con reglas de búsqueda para consultar datos. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"0","3"', "op" => "in"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Crea un objeto, obtiene logs de usuario y los decodifica en formato JSON. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {




// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra y edita un array basado en un código de autorización específico. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "inactivo";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "inactivo";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {

                            /* crea un array con datos de transacciones de un objeto. */
                            $array = [];

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};

                            /* Asigna valores de un objeto a un arreglo, transformando el estado numérico en texto. */
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = floatval($value->{"transapiusuario_log.valor"});
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "activo";
                            } else if ($array["status"] == 1) {
                                /* convierte un estado numérico en una representación textual "activo". */

                                $array["status"] = "activo";
                            } else if ($array["status"] == 2) {
                                /* Cambia el estado de un array a "inactivo" si su valor es 2. */

                                $array["status"] = "inactivo";
                            } else if ($array["status"] == 3) {
                                /* Cambia el estado del array a "inactivo" si era 3. */

                                $array["status"] = "inactivo";
                            }

                            /* Agrega el contenido de `$array` al final del array `$final`. */
                            array_push($final, $array);
                        }
                    }

                }
                if ($type == 2) {


                    /* Configura límites y reglas para filtrar registros de una base de datos. */
                    $MaxRows = 1000000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se agregan reglas de filtrado para consultar registros en una base de datos. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"1","2"', "op" => "in"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* convierte datos a JSON y obtiene registros de la base de datos. */
                    $json = json_encode($filtro);

                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {




// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra y modifica el estado de un array basado en un código de autorización. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "inactivo";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "inactivo";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {

                            /* Se crea un array con la fecha actual y un código de autorización. */
                            $array = [];
                            $fechaActual = date('Y-m-d H:i:s');
                            $fechaActual = str_replace(" ", "T", $fechaActual);

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};

                            /* Crea un array con datos de transacción y modifica el estado según condición. */
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = floatval($value->{"transapiusuario_log.valor"});
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "activo";
                            } else if ($array["status"] == 1) {
                                /* Convierte el valor 1 de "status" en la cadena "activo" en un array. */

                                $array["status"] = "activo";
                            } else if ($array["status"] == 2) {
                                /* Cambia el estado del arreglo a "inactivo" si el valor es 2. */

                                $array["status"] = "inactivo";
                            } else if ($array["status"] == 3) {
                                /* convierte el estado numérico 3 en la cadena "inactivo". */

                                $array["status"] = "inactivo";
                            }

                            /* Añade un elemento al final del array $final en PHP. */
                            array_push($final, $array);
                        }
                    }
                }


                /* Crea un arreglo con la fecha actual y elementos finales en formato ISO 8601. */
                $array2 = [];
//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array2["accountingDate"] = $fechaActual;
                $array2["items"] = $final;

                /* inicializa un arreglo de respuesta con error y datos. */
                $response["error"] = 0;
                $response["data"] = array($array2);
            } elseif ($shop == '6283508') {


                /* inicializa variables para controlar la paginación de un proceso de tienda. */
                $MaxRows = 1;
                $OrderedItem = 1;
                $SkeepRows = 0;

                $shop = $params->shop;
                $token = $params->token;

                /* extrae datos de parámetros y lanza una excepción si el campo de tienda está vacío. */
                $type = $params->type;
                $data = $params->data;
                $date = $params->date;


                if ($shop == "") {
                    throw new Exception("Field: $shop", "50001");

                }

                /* Verifica si los campos 'token' y 'type' están vacíos y lanza excepciones. */
                if ($token == "") {
                    throw new Exception("Field: token", "50001");

                }

                if ($type == "") {
                    throw new Exception("Field: type", "50001");

                }


                /* lanza excepciones si los campos "data" o "date" están vacíos. */
                if ($data == "") {
                    throw new Exception("Field: data", "50001");

                }
                if ($date == "") {
                    throw new Exception("Field: date", "50001");

                }



                /* Se definen variables para manejar la paginación de datos en un conjunto. */
                $MaxRows = 10000;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se construye un filtro con reglas para consultar usuarios y tokens. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");



                /* Se inicializan variables de respuesta y un array vacío llamado $final. */
                $response["error"] = 0;
                $response["code"] = 0;

                $final = [];

                if ($type == 1) {


                    /* Código PHP que define variables y establece reglas para filtrar datos por fecha. */
                    $MaxRows = 1000000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Crea un filtro en formato JSON con reglas específicas para una consulta. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"0","3"', "op" => "in"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Se crea un log y se obtienen datos en formato JSON desde una base de datos. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {


// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra y edita un array según un código de autorización y tipo específico. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "Inactive";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "Inactive";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {

                            /* crea un array con datos de transacciones formateadas. */
                            $array = [];

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};

                            /* Se asignan valores a un array basado en un objeto, con condición para estatus. */
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = floatval($value->{"transapiusuario_log.valor"});
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "Active";
                            } else if ($array["status"] == 1) {
                                /* cambia el estado de "status" a "Active" si su valor es 1. */

                                $array["status"] = "Active";
                            } else if ($array["status"] == 2) {
                                /* modifica el estado del arreglo a "Inactive" si es 2. */

                                $array["status"] = "Inactive";
                            } else if ($array["status"] == 3) {
                                /* Cambia el estado de un elemento a "Inactive" si su valor es 3. */

                                $array["status"] = "Inactive";
                            }


                            /* Agrega un elemento array al final de otro array en PHP. */
                            array_push($final, $array);
                        }
                    }

                }
                if ($type == 2) {


                    /* Define parámetros para limitar y ordenar filas en una consulta de base de datos. */
                    $MaxRows = 1000000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se configuran reglas de filtrado para una consulta SQL usando un array en PHP. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"1","2"', "op" => "in"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* convierte datos a JSON y obtiene registros de una tabla usando parámetros. */
                    $json = json_encode($filtro);

                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {

// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra y actualiza elementos de un array según un código de autorización y tipo. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "Inactive";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "Inactive";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {


                            /* crea un array con fecha y código de autorización formateados. */
                            $array = [];
                            $fechaActual = date('Y-m-d H:i:s');
                            $fechaActual = str_replace(" ", "T", $fechaActual);

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};

                            /* Asigna valores a un arreglo basado en propiedades de un objeto y el estado es verificado. */
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = floatval($value->{"transapiusuario_log.valor"});
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "Active";
                            } else if ($array["status"] == 1) {
                                /* Cambia el valor de "status" a "Active" si es igual a 1. */

                                $array["status"] = "Active";
                            } else if ($array["status"] == 2) {
                                /* cambia el valor de "status" a "Inactive" si es igual a 2. */

                                $array["status"] = "Inactive";
                            } else if ($array["status"] == 3) {
                                /* Cambia el estado a "Inactive" si el valor actual es 3. */

                                $array["status"] = "Inactive";
                            }

                            /* Añade el contenido de `$array` al final de `$final`. */
                            array_push($final, $array);
                        }

                    }
                }


                /* crea un array con fecha actual y elementos de una variable. */
                $array2 = [];
//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array2["accountingDate"] = $fechaActual;
                $array2["items"] = $final;

                /* asigna un error cero y un array a una respuesta. */
                $response["error"] = 0;
                $response["data"] = $array2;

            } else {


                /* inicializa variables para manejar datos de una tienda online. */
                $MaxRows = 1;
                $OrderedItem = 1;
                $SkeepRows = 0;

                $shop = $params->shop;
                $token = $params->token;

                /* asigna valores de parámetros y verifica si el campo "shop" está vacío. */
                $type = $params->type;
                $data = $params->data;
                $date = $params->date;


                if ($shop == "") {
                    throw new Exception("Field: $shop", "50001");

                }

                /* valida variables y lanza excepciones si están vacías. */
                if ($token == "") {
                    throw new Exception("Field: token", "50001");

                }

                if ($type == "") {
                    throw new Exception("Field: type", "50001");

                }


                /* verifica si 'data' y 'date' están vacíos, lanzando excepciones si es así. */
                if ($data == "") {
                    throw new Exception("Field: data", "50001");

                }
                if ($date == "") {
                    throw new Exception("Field: date", "50001");

                }



                /* Se definen variables para gestionar filas y un arreglo para reglas. */
                $MaxRows = 10000;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se agregan reglas de filtrado para usuarios y tokens en un array. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");



                /* inicializa un array de respuesta con error y código, además de un array final. */
                $response["error"] = 0;
                $response["code"] = 0;

                $final = [];

                if ($type == 1) {


                    /* Código establece reglas para filtrar datos en una consulta basándose en fecha. */
                    $MaxRows = 1000000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se generan reglas para filtrar datos y se convierten a formato JSON. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"0","3"', "op" => "in"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* obtiene registros de logs de usuario y los decodifica de JSON. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {


// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra y actualiza el estado de un array según condiciones específicas. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "Inactive";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "Inactive";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {


                            /* crea un arreglo con datos de transacciones de un usuario. */
                            $array = [];

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};

                            /* asigna valores a un arreglo según condiciones específicas de un objeto. */
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = floatval($value->{"transapiusuario_log.valor"});
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "Active";
                            } else if ($array["status"] == 1) {
                                /* cambia el valor de "status" a "Active" si es igual a 1. */

                                $array["status"] = "Active";
                            } else if ($array["status"] == 2) {
                                /* Cambia el estado a "Inactive" si el valor actual es 2. */

                                $array["status"] = "Inactive";
                            } else if ($array["status"] == 3) {
                                /* Cambia el estado del arreglo a "Inactive" si el valor es 3. */

                                $array["status"] = "Inactive";
                            }


                            /* Añade el contenido de $array al final de $final en PHP. */
                            array_push($final, $array);
                        }
                    }

                }
                if ($type == 2) {


                    /* Código configura límite de filas y define reglas para filtrar datos por fecha. */
                    $MaxRows = 1000000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Crea un filtro utilizando reglas para consultas de base datos con condiciones específicas. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"1","2"', "op" => "in"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* Codifica datos en JSON, obtiene registros y los decodifica para su uso. */
                    $json = json_encode($filtro);

                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {


// Filtrar el array para encontrar el que tiene authorizationCode = 2010

                        /* Filtra y actualiza el estado de elementos en un array basado en condiciones específicas. */
                        $resultado = array_filter($final, function ($item) use ($value) {
                            return isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"};
                        });

// Verificar si se encontró el array
                        if (!empty($resultado)) {
// Se encontró el array, ahora editarlo
                            foreach ($final as &$item) {
                                if (isset($item['authorizationCode']) && $item['authorizationCode'] == $value->{"transapiusuario_log.identificador"}) {
                                    if ($value->{"transapiusuario_log.tipo"} == 2) {
                                        $item["status"] = "Inactive";
                                    }
                                    if ($value->{"transapiusuario_log.tipo"} == 3) {
                                        $item["status"] = "Inactive";
                                    }
                                }
                            }
                            unset($item); // Desreferenciar el puntero
                        } else {


                            /* crea un arreglo con fechas formateadas y un código de autorización. */
                            $array = [];
                            $fechaActual = date('Y-m-d H:i:s');
                            $fechaActual = str_replace(" ", "T", $fechaActual);

                            $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                            $array["authorizationCode"] = $value->{"transapiusuario_log.identificador"};

                            /* asigna valores a un arreglo basado en datos de transacciones. */
                            $array["transactionDate"] = $fecha;
                            $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                            $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                            $array["paidAmount"] = floatval($value->{"transapiusuario_log.valor"});
                            $array["status"] = $value->{"transapiusuario_log.tipo"};
                            if ($array["status"] == 0) {
                                $array["status"] = "Active";
                            } else if ($array["status"] == 1) {
                                /* Reemplaza el valor de "status" si es igual a 1, asignando "Active". */

                                $array["status"] = "Active";
                            } else if ($array["status"] == 2) {
                                /* cambia el estado a "Inactive" si el status es igual a 2. */

                                $array["status"] = "Inactive";
                            } else if ($array["status"] == 3) {
                                /* asigna "Inactive" al estado si su valor es 3. */

                                $array["status"] = "Inactive";
                            }

                            /* Agrega el contenido de `$array` al final de `$final`. */
                            array_push($final, $array);
                        }
                    }
                }


                /* crea un array con la fecha actual y items finales. */
                $array2 = [];
//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array2["accountingDate"] = $fechaActual;
                $array2["items"] = $final;

                /* Asigna un valor de error y almacena un arreglo en la respuesta. */
                $response["error"] = 0;
                $response["data"] = array($array2);

            }


            break;

        case 'user/searchwithdraw':


            /* asigna valores de parámetros a variables específicas. */
            $shop = $params->shop;
            $token = $params->token;
            $nota = $params->withdrawId;
            $clave = $params->password;
            $pais = $params->country;

            $transactionId = $params->transactionId;


            /* lanza una excepción si el token está vacío, indicando un error. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }


            /* verifica campos vacíos y lanza excepciones si no cumplen requisitos. */
            if ($nota == "") {
                throw new Exception("Field: Nota", "50001");

            }

            if ($clave == "") {
                throw new Exception("Field: Clave", "50001");

            }

            /* Verifica si la variable $pais está vacía y lanza una excepción si lo está. */
            if ($pais == "") {
                throw new Exception("Field: Pais", "50001");

            }

            $MaxRows = 1;

            /* Se inicializan variables para ordenar elementos y omitir filas, luego se define un arreglo. */
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            /* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_log.* ";


$UsuarioLog = new UsuarioLog();
$data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);*/


            /* Se crean reglas de filtrado para una consulta de base de datos en PHP. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* codifica datos como JSON y establece la configuración regional en checo. */
            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_token_interno.* ";



            /* Se crea un objeto y se obtiene datos de usuarios en formato JSON. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Establece el código de respuesta en 0, indicando éxito o estado inicial. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* verifica si dos usuarios pertenecen al mismo país antes de continuar. */
                $CuentaCobro = new CuentaCobro($nota, "", $clave);
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $UsuarioPuntoVenta = new Usuario($shop);

                try {
                    $Clasificador = new Clasificador('', 'CONTINGENCIARETIROSRETAIL');
                    $UsuarioConfiguracion = new UsuarioConfiguracion($CuentaCobro->getUsuarioId(), 'A', $Clasificador->getClasificadorId());
                    $IsActivateContingencyRetailWithdrawals = $UsuarioConfiguracion->getEstado();
                
                    // Verificación de contingencia de retiros retail
                    if($IsActivateContingencyRetailWithdrawals == 'A') {
                        throw new Exception("Este usuario no puede usar puntos de venta o red aliadas, comuníquese con soporte.", 300152);
                    }
                } catch (Exception $e) {
                    if($e->getCode() == 300152) throw $e;
                }

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }

                /* Se validan condiciones de país y asociación de usuarios, lanzando excepciones si no coinciden. */
                if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
                    throw new Exception("Código de país incorrecto", "10018");

                }
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }


                /* Verifica el estado de CuentaCobro y el usuario antes de procesar la nota de retiro. */
                if ($CuentaCobro->getEstado() != "A") {
                    throw new Exception("No existe la nota de retiro", "12");
                }

                if ($UsuarioPuntoVenta->usuarioId != "853460") {
                    if ($CuentaCobro->getMediopagoId() == "853460") {
                        throw new Exception("No existe la nota de retiro", "12");
                    }
                }

                /* Verifica usuario y medio de pago, lanza excepción si no coinciden. */
                if ($UsuarioPuntoVenta->usuarioId != "2894342") {
                    if ($CuentaCobro->getMediopagoId() == "2894342") {
                        throw new Exception("No existe la nota de retiro", "12");
                    }
                }


                $response["name"] = $Usuario->nombre;

                /* Asigna valores de moneda, monto y usuario a una respuesta estructurada. */
                $response["currency"] = $Usuario->moneda;
                $response["amount"] = $CuentaCobro->getValor();
                $response["userId"] = $CuentaCobro->getUsuarioId();


            } else {
                /* Se lanza una excepción si los datos de inicio de sesión son incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;


        case 'rollback/withdraw':
//exit();

            /* extrae parámetros de una solicitud, incluyendo tienda, token, y transacción. */
            $shop = $params->shop;
            $token = $params->token;
            $nota = $params->withdrawId;
            $clave = $params->password;
            $pais = $params->country;

            $transactionId = $params->transactionId;


            /* Código verifica si el token está vacío y lanza una excepción si es necesario. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }


            /* valida si los campos "nota" y "clave" están vacíos, lanzando excepciones. */
            if ($nota == "") {
                throw new Exception("Field: Nota", "50001");

            }

            if ($clave == "") {
//throw new Exception("Field: Clave", "50001");

            }

            /* Validación de un campo vacío y definición de una variable para registros máximos. */
            if ($pais == "") {
//throw new Exception("Field: Pais", "50001");

            }

            $MaxRows = 1;

            /* Se inicializan variables para ordenar elementos y omitir filas en procesamiento de datos. */
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            /* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_log.* ";


$UsuarioLog = new UsuarioLog();
$data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);*/


            /* Se crean reglas de filtrado para realizar consultas en una base de datos. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro en JSON y establece la localización en checo. */
            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_token_interno.* ";



            /* Se crea un objeto, se obtienen datos y se decodifica JSON sin errores. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Asigna el valor 0 a la clave "code" del array $response. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {



                /* Se crean instancias de usuario, transacción y cuenta de cobro en un sistema de ventas. */
                $UsuarioPuntoVenta = new Usuario($shop);

                $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "1");

                $CuentaCobro = new CuentaCobro($TransaccionApiUsuario->getIdentificador());
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

//$UsuarioPuntoVenta = new Usuario($shop);

                /* Se valida que el usuario pertenezca al mismo país del punto de venta. */
                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }

                /* Verifica condiciones de país y mandante, lanzando excepciones si no se cumplen. */
                if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
//throw new Exception("Código de país incorrecto", "10018");

                }
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }

                /* Verifica condiciones de usuario y lanza excepción si no se cumplen. */
                if ($UsuarioPuntoVenta->usuarioId != "853460") {
                    if ($CuentaCobro->getMediopagoId() == "853460") {
                        throw new Exception("No existe la nota de retiro", "12");
                    }
                }
                if ($UsuarioPuntoVenta->usuarioId != "2894342") {
                    if ($CuentaCobro->getMediopagoId() == "2894342") {
                        throw new Exception("No existe la nota de retiro", "12");
                    }
                }


//$CuentaCobro = new CuentaCobro($nota, "", $clave);

                /* instancia objetos de Usuario y PuntoVenta usando datos del objeto CuentaCobro. */
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $valor = $CuentaCobro->getValor();

//$UsuarioPuntoVenta = new Usuario($shop);
                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);


                /* Verifica si el usuario está en el país y partner correctos, lanzando excepciones si no. */
                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }

                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }


                /* verifica el estado de una cuenta antes de permitir su eliminación. */
                if ($CuentaCobro->getEstado() == 'E') {
                    throw new Exception("La nota de retiro ya se encuentra eliminada", "50009");
                }

                if ($CuentaCobro->getEstado() != 'I') {
                    throw new Exception("La nota de retiro no se puede eliminar", "50008");
                }



                /* Valida si el monto de pago excede el límite permitido para notas de retiro. */
                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();
                $Amount = $CuentaCobro->getValor();

                $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

                if ($UsuarioConfig->maxpagoRetiro != "" && $UsuarioConfig->maxpagoRetiro != "0") {
                    if (floatval($UsuarioConfig->maxpagoRetiro) < floatval($CuentaCobro->getValor())) {
                        throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
                    }
                }


                /* inicializa variables y objetos para manejar transacciones en una base de datos. */
                $rowsUpdate = 0;

                $ConfigurationEnvironment = new ConfigurationEnvironment();

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();

                /* Código que establece parámetros de un objeto CuentaCobro usando datos de configuración. */
                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);
//$CuentaCobro->setDiripCambio($dirIp);
                $CuentaCobro->setEstado('E');
                $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                /* Actualiza la fecha de eliminación y lanza excepción si no se actualizan filas. */
                $CuentaCobro->setFechaEliminacion(date('Y-m-d H:i:s'));

                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='I' ");

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                /* Se crea un objeto FlujoCaja y se configura su fecha, hora y usuario creador. */
                $rowsUpdate = 0;

                $FlujoCaja = new FlujoCaja();
                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);

                /* Configura un objeto FlujoCaja con información de una cuenta de cobro. */
                $FlujoCaja->setTipomovId('E');
                $FlujoCaja->setValor($CuentaCobro->getValor());
                $FlujoCaja->setTicketId('');
                $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
                $FlujoCaja->setMandante($CuentaCobro->getMandante());
                $FlujoCaja->setTraslado('N');

                /* inicializa IDs de recarga y forma de pago en cero si están vacíos. */
                $FlujoCaja->setRecargaId(0);

                if ($FlujoCaja->getFormapago1Id() == "") {
                    $FlujoCaja->setFormapago1Id(0);
                }

                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }


                /* Establece valor cero si "ValorForma" está vacío en objeto "FlujoCaja". */
                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }

                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }


                /* Asigna valores por defecto a atributos vacíos en el objeto $FlujoCaja. */
                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }

                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }


                /* Se verifica el valor del IVA y se inicializa si está vacío. */
                if ($FlujoCaja->getValorIva() == "") {
                    $FlujoCaja->setValorIva(0);
                }
                $FlujoCaja->setDevolucion('');

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

                /* inserta datos y maneja errores si la inserción falla. */
                $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                $rowsUpdate = 0;


                /* Actualiza el balance de créditos base restando el valor de la cuenta de cobro. */
                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$CuentaCobro->getValor(), $Transaction);


                if ($rowsUpdate > 0) {



                    /* Se crean y configuran los atributos de una transacción de usuario en la API. */
                    $TransaccionApiUsuario = new TransaccionApiUsuario();

                    $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                    $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                    $TransaccionApiUsuario->setValor($valor);
                    $TransaccionApiUsuario->setTipo(1);

                    /* Se configura una transacción con parámetros, respuesta y usuario creador. */
                    $TransaccionApiUsuario->setTValue(json_encode($params));
                    $TransaccionApiUsuario->setRespuestaCodigo("OK");
                    $TransaccionApiUsuario->setRespuesta("OK");
                    $TransaccionApiUsuario->setTransaccionId($transactionId);

                    $TransaccionApiUsuario->setUsucreaId(0);

                    /* Se insertan datos de transacción de usuario en la base de datos. */
                    $TransaccionApiUsuario->setUsumodifId(0);

                    $TransaccionApiUsuario->setIdentificador($CuentaCobro->getCuentaId());
                    $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                    $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                    $TransapiusuarioLog = new TransapiusuarioLog();


                    /* registra transacciones de usuario con detalles específicos en un objeto de log. */
                    $TransapiusuarioLog->setIdentificador($CuentaCobro->getCuentaId());
                    $TransapiusuarioLog->setTransaccionId($transactionId);
                    $TransapiusuarioLog->setTValue(json_encode($params));
                    $TransapiusuarioLog->setTipo(2);
                    $TransapiusuarioLog->setValor($Amount);
                    $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

                    /* Se configuran identificadores de usuario y se crea un objeto de acceso a datos. */
                    $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                    $TransapiusuarioLog->setUsucreaId(0);
                    $TransapiusuarioLog->setUsumodifId(0);


                    $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

                    /* inserta un registro de usuario y actualiza su historial de créditos. */
                    $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


                    $Usuario->creditWin($CuentaCobro->getValor(), $Transaction);

                    $UsuarioHistorial = new UsuarioHistorial();

                    /* configura un historial de usuario con diversos parámetros específicos. */
                    $UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(40);

                    /* registra un historial de usuario con información de cuenta de cobro. */
                    $UsuarioHistorial->setValor($CuentaCobro->getValor());
                    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                    $UsuarioHistorial = new UsuarioHistorial();

                    /* Código que establece propiedades en un objeto UsuarioHistorial con datos específicos. */
                    $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('S');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(40);

                    /* actualiza un historial de usuario y confirma una transacción en la base de datos. */
                    $UsuarioHistorial->setValor($CuentaCobro->getValor());
                    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

//$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
//$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


                    $Transaction->commit();
                } else {
                    /* Lanza una excepción de error general con un código específico. */

                    throw new Exception("Error General", "100000");
                }



                /* asigna valores de un usuario y cuenta a un arreglo de respuesta. */
                $response["name"] = $Usuario->nombre;
                $response["currency"] = $Usuario->moneda;
                $response["amount"] = $CuentaCobro->getValor();


            } else {
                /* Lanza una excepción con mensaje y código al fallar el login. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;


        case 'rollback/deposit':
//print_r(time());

//exit();



            /* verifica si el token está vacío y lanza una excepción si es así. */
            $shop = $params->shop;
            $token = $params->token;
            $amount = $params->amount;
            $transactionId = $params->transactionId;

            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            /* verifica si los campos token y amount están vacíos, lanzando excepciones. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");

            }

            /* verifica si transactionId está vacío y lanza una excepción si es así. */
            if ($transactionId == "") {
                throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;

            /* Se definen variables para orden y filas a omitir, además de un arreglo vacío. */
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            /* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_log.* ";


$UsuarioLog = new UsuarioLog();
$data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);*/


            /* Se crean reglas de filtrado para consultar usuarios y tokens en un array. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro en formato JSON y selecciona campos de la base de datos. */
            $json = json_encode($filtro);

//setlocale(LC_ALL, 'czech');


            $select = " usuario.mandante,usuario_token_interno.* ";



            /* Se obtiene y decodifica datos de tokens internos de usuario en formato JSON. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $data = json_decode($data);


            $response["error"] = 0;

            /* asigna el valor 0 a la clave "code" en el arreglo $response. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {
//print_r(time());


                /* Se instancia un usuario y una transacción para actualizar información de recargas. */
                $UsuarioPuntoVenta = new Usuario($shop);

                $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "0");

                /**
                 * Actualizamos consecutivo Recarga
                 */

                $UsuarioRecarga = new UsuarioRecarga($TransaccionApiUsuario->getIdentificador());



                /* verifica el estado de una recarga antes de marcarla como eliminada. */
                if ($UsuarioRecarga->getEstado() != "A") {
                    throw new Exception("La recarga no se puede eliminar", "50001");
                }
                $UsuarioRecarga->setEstado('I');
                $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
                $UsuarioRecarga->setUsueliminaId($UsuarioRecarga->getPuntoventaId());



                /* gestiona transacciones de recarga de usuarios en MySQL. */
                $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();

                $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);

                $puntoventa_id = $UsuarioRecarga->getPuntoventaId();

                /* Se obtiene valor del usuario, se crea un nuevo usuario y se inicializa FlujoCaja. */
                $valor = $UsuarioRecarga->getValor();

                $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

                $FlujoCaja = new FlujoCaja();
                $FlujoCaja->setFechaCrea(date('Y-m-d'));

                /* Establece propiedades de un objeto FlujoCaja con datos de entrada específicos. */
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($puntoventa_id);
                $FlujoCaja->setTipomovId('S');
                $FlujoCaja->setValor($valor);
                $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

                /* Establece una devolución y asigna identificadores de forma de pago si están vacíos. */
                $FlujoCaja->setDevolucion('S');

                if ($FlujoCaja->getFormapago1Id() == "") {
                    $FlujoCaja->setFormapago1Id(0);
                }

                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }


                /* asigna 0 a valores vacíos en FlujoCaja. */
                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }

                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }


                /* Asigna valores predeterminados a atributos vacíos de la clase FlujoCaja. */
                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }

                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }


                /* Asignar valor cero al IVA si está vacío y guardar en base de datos. */
                if ($FlujoCaja->getValorIva() == "") {
                    $FlujoCaja->setValorIva(0);
                }

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                $FlujoCajaMySqlDAO->insert($FlujoCaja);
//print_r(time());


                /* verifica si un punto de venta tiene cupo antes de recargar. */
                $PuntoVenta = new PuntoVenta("", $puntoventa_id);


                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100000");
                }

//print_r(time());


                /* Crea un ajuste de saldo de usuario con datos específicos y fecha actual. */
                $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                $SaldoUsuonlineAjuste->setTipoId('S');
                $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                $SaldoUsuonlineAjuste->setValor($valor);
                $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));

                /* Se configura un ajuste de saldo con información del usuario y su recarga. */
                $SaldoUsuonlineAjuste->setUsucreaId($UsuarioPuntoVenta->usuarioId);
                $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                $SaldoUsuonlineAjuste->setObserv("Reversion recarga API " . $UsuarioRecarga->getRecargaId());
                if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                    $SaldoUsuonlineAjuste->setMotivoId(0);
                }

                /* Extrae la IP del cliente y ajusta el estado del usuario a 'I'. */
                $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());

                $UsuarioRecarga->setEstado('I');

                /* actualiza un registro de usuario estableciendo la fecha de eliminación. */
                $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
                $UsuarioRecarga->setUsueliminaId(0);

                $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);

                $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);


                /* Se crea un objeto para insertar datos en la base de datos usando MySQL. */
                $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);

//print_r(time());

                if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {


                    /* Código para registrar un log de cupo con valores y usuario específicos. */
                    $CupoLog = new CupoLog();
                    $CupoLog->setUsuarioId($Usuario->puntoventaId);
                    $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
                    $CupoLog->setTipoId('E');
                    $CupoLog->setValor(-$UsuarioRecarga->getValor());
                    $CupoLog->setUsucreaId(0);

                    /* Configura un registro de recarga de usuario utilizando CupoLog y DAO MySQL. */
                    $CupoLog->setMandante(0);
                    $CupoLog->setTipocupoId('A');
                    $CupoLog->setRecargaId($UsuarioRecarga->recargaId);


                    $CupoLogMySqlDAO = new CupoLogMySqlDAO($Transaction);


                    /* Inserta un registro y verifica saldo antes de transferir créditos en punto de venta. */
                    $CupoLogMySqlDAO->insert($CupoLog);

                    $PuntoVenta = new PuntoVenta('', $Usuario->puntoventaId);
                    $cant = $PuntoVenta->setBalanceCreditosBase($CupoLog->getValor(), $Transaction);

                    if ($cant == 0) {
                        throw new Exception("No tiene saldo para transferir", "111");
                    }


                    /* Crea un nuevo registro de historial de usuario con datos específicos del cupo. */
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);

                    /* Se crea un historial de usuario y se inserta en la base de datos. */
                    $UsuarioHistorial->setTipo(60);
                    $UsuarioHistorial->setValor($CupoLog->getValor());
                    $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


                } else {
                    /* registra un débito y un historial de usuario en la base de datos. */


                    $Usuario->debit($UsuarioRecarga->getValor(), $Transaction);


                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('S');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(10);
                    $UsuarioHistorial->setValor($valor);
                    $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                }


//print_r(time());


                /* Código que inicializa un historial de usuario con datos específicos en una base de datos. */
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($puntoventa_id);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);

                /* configura un historial de usuario basado en una recarga especificada. */
                $UsuarioHistorial->setTipo(10);
                $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

//$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
//$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

//print_r(time());

                $TransaccionApiUsuario = new TransaccionApiUsuario();


                /* Código que configura una transacción asignando valores y parámetros específicos. */
                $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransaccionApiUsuario->setValor(($amount));
                $TransaccionApiUsuario->setTipo(0);
                $TransaccionApiUsuario->setTValue(json_encode($params));
                $TransaccionApiUsuario->setRespuestaCodigo("OK");

                /* Establece valores para una transacción API, incluyendo respuesta y usuarios relacionados. */
                $TransaccionApiUsuario->setRespuesta("OK");
                $TransaccionApiUsuario->setTransaccionId($transactionId);

                $TransaccionApiUsuario->setUsucreaId(0);
                $TransaccionApiUsuario->setUsumodifId(0);


                $TransaccionApiUsuario->setIdentificador($UsuarioRecarga->getRecargaId());

                /* Se crea un DAO y se inserta una transacción, además se inicializa un logger. */
                $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

//print_r(time());

                $TransapiusuarioLog = new TransapiusuarioLog();


                /* Se registran detalles de la transacción en el log del usuario. */
                $TransapiusuarioLog->setIdentificador($UsuarioRecarga->getRecargaId());
                $TransapiusuarioLog->setTransaccionId($transactionId);
                $TransapiusuarioLog->setTValue(json_encode($params));
                $TransapiusuarioLog->setTipo(3);
                $TransapiusuarioLog->setValor($amount);
                $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

                /* asigna IDs a un registro y crea un objeto DAO para operaciones con base de datos. */
                $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                $TransapiusuarioLog->setUsucreaId(0);
                $TransapiusuarioLog->setUsumodifId(0);


                $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

                /* Inserta un registro en la base de datos y confirma la transacción. */
                $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

//print_r(time());

                $Transaction->commit();


                $response["transactionId"] = $Transapiusuariolog_id;

                /* Asigna el ID de recarga del usuario a la respuesta del sistema. */
                $response["depositId"] = $UsuarioRecarga->getRecargaId();


            } else {
                /* lanza una excepción si los datos de login son incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'coupons/gt':
//print_r(time());

//exit();


            /* asigna valores de parámetros y valida que la clave no esté vacía. */
            $transactionId = $params->transactionId;
            $key = $params->key;
            $value = $params->value;
            $mandante = $params->partner;
            $country = $params->country;

            if ($key == "") {
                throw new Exception("Field: Key empty", "2");

            }


            /* if ($transactionId == "") {
throw new Exception("Field: transactionId empty", "2");

}*/

            /* lanza excepciones si los campos `$value` o `$mandante` están vacíos. */
            if ($value == "") {
                throw new Exception("Field: value empty", "2");

            }
            if ($mandante == "") {
                throw new Exception("Field: parnet empty", "2");

            }

            /* valida si el país está vacío o es incorrecto, lanzando excepciones. */
            if ($country == "") {
                throw new Exception("Field: country empty", "2");

            }

            if ($country != 60) {
                throw new Exception("Código de país incorrecto", "10018");
            }


            /* Inicializa una transacción de producto y configura límites de filas y clave secreta. */
            $TransaccionProducto = new TransaccionProducto();

            $SkeepRows = 0;
            $MaxRows = 1000000;
            $array = array();
            $SecretKey = "MihPXlw2eCX%WGa3";

            /* inicializa proveedores, productos y configura claves en producción. */
            $Proveedor = new Proveedor("", "GT");
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Producto = new Producto("", "CUPONESGT", $Proveedor->proveedorId);

            if ($ConfigurationEnvironment->isProduction()) {
                $SecretKey = 'LVduxWEkNKbR3BUGnh2cgJ9FBqMRnFHG';
            }
            if ($key == $SecretKey) {



                /* Se crea una transacción de producto utilizando MySQL como base de datos. */
                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
                $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

                $TransaccionProducto = new TransaccionProducto();
                $TransaccionProducto->setProductoId($Producto->productoId);
                $TransaccionProducto->setUsuarioId(0);

                /* Se establecen propiedades de un objeto TransaccionProducto con valores específicos. */
                $TransaccionProducto->setValor($value);
                $TransaccionProducto->setEstado('A');
                $TransaccionProducto->setTipo('T');
                $TransaccionProducto->setExternoId(0);
                $TransaccionProducto->setEstadoProducto('P');
                $TransaccionProducto->setMandante($mandante);

                /* Se configuran propiedades de un objeto y se inserta en la base de datos. */
                $TransaccionProducto->setFinalId(0);
                $TransaccionProducto->setFinalId(0);
                $TransaccionProducto->setUsutarjetacredId(0);

                $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

                $codigoCupon = $ConfigurationEnvironment->encryptCusNum(intval($transproductoId));


                /* agrega un cupón a un arreglo y registra un log de transacción. */
                array_push($array, $codigoCupon);

                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('P');
                $TransprodLog->setTipoGenera('A');

                /* Se configura un registro de log de transacción con diversos parámetros de inicialización. */
                $TransprodLog->setComentario('Cupon generado por ' . $transproductoId);
                $TransprodLog->setTValue("");
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);

                /* inserta un registro y confirma la transacción, devolviendo el identificador generado. */
                $TransprodlogId = $TransprodLogMySqlDAO->insert($TransprodLog);

                $Transaction->commit();


                $response["transactionId"] = $TransprodlogId;

                /* Asigna el valor de $array a la clave "coupon" en el arreglo $response. */
                $response["coupon"] = $array;


            } else {
                /* Lanza una excepción indicando que la clave proporcionada es inválida. */

                throw new Exception("Key invalida", "1");

            }


            break;

        default:
# code...
            break;
    }
} catch (Exception $e) {

    /* Intenta revertir una transacción si no es nula. */
    try {

        if ($Transaction != null) {
            $Transaction->rollback();
        }
    } catch (Exception $e) {
        /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */


    }

    /* verifica una condición y registra errores en el sistema. */
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
        print_r($e);
    }
    syslog(LOG_WARNING, "ERRORAPIOPERATOR :" . $e->getCode() . ' - ' . $e->getMessage() . json_encode($params) . json_encode($_SERVER) . json_encode($_REQUEST));

    $code = $e->getCode();


    /* inicializa variables y captura un mensaje de error en un arreglo. */
    $codeProveedor = "";
    $messageProveedor = "";
    $message = $e->getMessage();

    $response = array();

    switch ($code) {

        case 50003:
            /* Asignación de código y mensaje para error de credenciales incorrectas en proveedor. */

            $codeProveedor = "102";  //credenciales incorrectas
            $messageProveedor = $message;
            break;

        case 50001:
            /* asigna un valor a $codeProveedor y un mensaje en caso de un error específico. */

            $codeProveedor = "100";  //campos vacios
            $messageProveedor = $message;
            break;

        case 50005:
            /* Código maneja un caso específico, asignando un mensaje para un usuario no nacional. */

            $codeProveedor = "101"; //Usuario no pertence al pais
            $messageProveedor = $message;
            break;
        case 50006:
            /* Código maneja el caso 50006, asignando un mensaje para proveedor no asociado. */

            $codeProveedor = "101";  //usuario no pertenece al partner
            $messageProveedor = $message;
            break;

        case 50007:
            /* Asigna un código de proveedor y mensaje para un caso específico (50007). */

            $codeProveedor = "106";     //nota de retirno no esta activa
            $messageProveedor = $message;
            break;

        case 50008:
            /* Código verifica caso 50008, denota que la nota de retiro no es eliminable. */

            $codeProveedor = "9"; //nota de retiro no puede ser eliminada
            $messageProveedor = $message;
            break;

        case 50009:
            /* asigna un mensaje a un proveedor tras eliminar una nota de retiro. */

            $codeProveedor = "9"; //nota de retiro ya eliminada
            $messageProveedor = $message;
            break;

        case 10018:
            /* Código que asigna un mensaje de error por código de país incorrecto. */

            $codeProveedor = "100"; //Codigo de pais incorrecto
            $messageProveedor = $message;
            break;

        case 10001:
            /* Código que maneja un caso específico de transacción ya procesada asignando un mensaje. */

            $codeProveedor = "6"; //Transacción ya procesada
            $messageProveedor = $message;
            break;

        case 100000:
            /* asigna un error general a un proveedor en un caso específico. */

            $codeProveedor = "9"; //error general
            $messageProveedor = $message;
            break;
        case 100031:
            /* Se establece un código de proveedor específico para notas de retiro no pagables. */

            $codeProveedor = "9"; //No se puede pagar nota de retiro
            $messageProveedor = $message;
            break;
        case 12:
            /* asigna un mensaje de error para un proveedor específico. */

            $codeProveedor = '10';
            $messageProveedor = 'No Existe la nota de retiro';

            break;
        case 24:
            /* Código que maneja el caso donde un proveedor no existe, mostrando un mensaje correspondiente. */

            $codeProveedor = "101"; //no existe el usuario
            $messageProveedor = 'No existe el usuario';

            break;

        case 10:
            /* Manejo de error: asigna un mensaje y código para clave incorrecta en caso 10. */

            $codeProveedor = "100"; // key incorrecta
            $messageProveedor = 'Key incorrecta';

            break;

        case 20001:
            /* Código que maneja un error por falta de fondos en una transacción. */

            $codeProveedor = "20001"; // key incorrecta
            $messageProveedor = 'El Usuario no tiene fondos suficientes para hacer este movimiento';

            break;

        case 87:
            /* Código establece un mensaje de error para transacción no encontrada con código 5. */

            $codeProveedor = "5"; // Transaccion no encontrada
            $messageProveedor = 'Transaccion no encontrada';

            break;

        /*
        case 50001:
        $codeProveedor = "2";
        $messageProveedor = "Data Incorrect. (" . $e->getMessage() . ")";

        break;
        case 61:
        $codeProveedor = "3";
        $messageProveedor = "Incorrect login details.";

        break;

        case 86:
        $codeProveedor = "3";
        $messageProveedor = "Incorrect login details.";

        break;


        case 12:
        $codeProveedor = "20";
        $messageProveedor = "No Existe la nota de retiro.";

        break;*/


        default:

            /* Asignación de variables que almacena un código de proveedor y un mensaje de error. */
            $codeProveedor = '9';
            $messageProveedor = 'Error General (' . ($e->getCode()) . ')';

            break;
    }



    /* asigna valores de error, código y mensaje a una respuesta. */
    $response["error"] = 1;
    $response["code"] = $codeProveedor;
    $response["message"] = $messageProveedor;
}


if (json_encode($response) != "[]") {


    print_r(json_encode($response));

}


/**
 * Convierte una cantidad de una moneda a otra utilizando tasas de cambio.
 *
 * @param string $from_Currency Moneda de origen.
 * @param string $to_Currency Moneda de destino.
 * @param float $amount Cantidad a convertir.
 * @return float Cantidad convertida.
 */
function currencyConverter($from_Currency, $to_Currency, $amount)
{
    if ($from_Currency == $to_Currency) {
        return $amount;
    }
    global $currencies_valor;
    $convertido = -1;
    $bool = false;

    foreach ($currencies_valor as $key => $valor) {
        if ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = $amount * $valor;
            $bool = true;
        } elseif ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = ($amount) / $valor;
            $bool = true;
        }
    }
    if (!$bool) {
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);
        $encode_amount = 1;

        $rawdata = file_get_contents("http://api.currencies.zone/v1/quotes/$from_Currency/$to_Currency/json?quantity=$encode_amount&key=44|YSqBgkAbvbGfenoxx62OaSnoD~rF8rw~");
        if ($_SESSION["usuario2"] == 5) {

        }
        $rawdata = json_decode($rawdata);
        $currencies_valor += [$from_Currency . "" . $to_Currency => $rawdata->result->amount];

        $convertido = $amount * $rawdata->result->amount;
    }

    return $convertido;
}

/**
 * Obtiene una lista de eventos deportivos según los parámetros proporcionados.
 *
 * @param string $sport ID del deporte.
 * @param string $region ID de la región.
 * @param string $competition ID de la competición.
 * @param string $fecha_inicial Fecha inicial en formato YYYY-MM-DD.
 * @param string $fecha_final Fecha final en formato YYYY-MM-DD.
 * @return array Lista de eventos deportivos.
 */
function getMatches($sport, $region, $competition, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {
                        if ($item3->ChampionshipId == $competition) {
                            foreach ($item3->Events as $item4) {
                                $item_data = array(
                                    "Id" => $item4->EventId,
                                    "Name" => $item4->Name
                                );
                                array_push($array, $item_data);
                            }
                        }
                    }
                }
            }
        }
    }

    return $array;
}

/**
 * Genera una clave aleatoria alfanumérica de una longitud específica.
 *
 * @param int $length Longitud de la clave.
 * @return string Clave generada.
 */
function GenerarClaveTicket($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Genera una clave aleatoria numérica de una longitud específica.
 *
 * @param int $length Longitud de la clave.
 * @return string Clave generada.
 */
function GenerarClaveTicket2($length)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Cifra o descifra una cadena utilizando el método AES-256-CBC.
 *
 * @param string $action Acción a realizar: 'encrypt' para cifrar o 'decrypt' para descifrar.
 * @param string $string Cadena a cifrar o descifrar.
 * @return string|false Cadena cifrada o descifrada, o false en caso de error.
 */
function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'D0RAD0';
    $secret_iv = 'D0RAD0';
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

/**
 * Obtiene la dirección IP del cliente que realiza la solicitud.
 *
 * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
 */
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Elimina duplicados de un arreglo multidimensional basado en una clave específica.
 *
 * @param array $array Arreglo multidimensional.
 * @param string $key Clave para identificar duplicados.
 * @return array Arreglo sin duplicados.
 */
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

/**
 * Elimina tildes y caracteres especiales de una cadena.
 *
 * @param string $cadena Cadena de texto a procesar.
 * @return string Cadena sin tildes ni caracteres especiales.
 */
function quitar_tildes($cadena)
{
    $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = str_replace($no_permitidas, $permitidas, $cadena);
    return $texto;
}



/**
 * Cifra una cadena utilizando el método AES-128-CTR.
 *
 * @param string $data Cadena a cifrar.
 * @param string $encryption_key (Opcional) Clave de cifrado.
 * @return string Cadena cifrada con el IV incluido.
 */
function encrypt($data, $encryption_key = "")
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, $iv);
    return $encrypted_string;
}

/**
 * Descifra una cadena cifrada utilizando el método AES-128-CTR.
 *
 * @param string $data Cadena cifrada con el IV incluido.
 * @param string $encryption_key (Opcional) Clave de cifrado.
 * @return string|false Cadena descifrada o false en caso de error.
 */
function decrypt($data, $encryption_key = "")
{
    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return FALSE;
    }
}

/**
 * Elimina caracteres especiales de una cadena de texto.
 *
 * @param string $texto_depurar Cadena de texto a depurar.
 * @return string Cadena depurada sin caracteres especiales.
 */
function DepurarCaracteres($texto_depurar)
{
    $texto_depurar = str_replace("'", "", $texto_depurar);
    $texto_depurar = str_replace('"', "", $texto_depurar);
    $texto_depurar = str_replace(">", "", $texto_depurar);
    $texto_depurar = str_replace("<", "", $texto_depurar);
    $texto_depurar = str_replace("[", "", $texto_depurar);
    $texto_depurar = str_replace("]", "", $texto_depurar);
    $texto_depurar = str_replace("{", "", $texto_depurar);
    $texto_depurar = str_replace("}", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("`", "", $texto_depurar);
    $texto_depurar = str_replace("|", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("%", "", $texto_depurar);
    $texto_depurar = str_replace("&", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("~", "", $texto_depurar);
    $texto_depurar = str_replace("+", "", $texto_depurar);
    $texto_depurar = str_replace("^", "", $texto_depurar);
    $texto_depurar = str_replace("/", "", $texto_depurar);
    //$texto_retornar = addslashes($texto_depurar);

    $c = null;
    return $texto_depurar;
}

/**
 * Obtiene la dirección IP del cliente que realiza la solicitud.
 *
 * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
 */
function get_client_ip_env()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

/**
 * Convierte una dirección IPv6 a IPv4.
 *
 * @param string $ipv6 Dirección IPv6 a convertir.
 * @return string Dirección IPv4 convertida o false si no es válida.
 */
function convertIP6($ipv6)
{
    $ipv6Addr = @inet_pton($ipv6);
    if ($ipv6Addr === false || strlen($ipv6Addr) !== 16) {
    }
    if (strpos($ipv6Addr, chr(0x20) . chr(0x02)) === 0) { // Direcciones 6to4 que comienzan con 2002:
        $ipv4Addr = substr($ipv6Addr, 2, 4);
    } else {
        $ipv4Addr = '';
        for ($i = 0; $i < 8; $i += 2) { // Obtiene los primeros 8 bytes porque la mayoría de los ISP proporcionan direcciones con máscara /64
            $ipv4Addr .= chr(ord($ipv6Addr[$i]) ^ ord($ipv6Addr[$i + 1]));
        }
        $ipv4Addr[0] = chr(ord($ipv4Addr[0]) | 240); // Espacio de clase E
    }
    $ipv4 = inet_ntop($ipv4Addr);
    return $ipv4;
}


