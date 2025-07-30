<?php

use Backend\dto\ApiTransaction;
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


/* Configura encabezados CORS y limita el uso de memoria en una respuesta JSON. */
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');

/* maneja configuraciones de CORS y ajusta la zona horaria. */
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
$_ENV["ENABLEDSETMAX_EXECUTION_TIME"] = '1';

$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);
$timezone = 0;


/* obtiene la URI actual y prepara para recibir datos en formato JSON. */
$URI = $_SERVER["REQUEST_URI"];
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();


$params = file_get_contents('php://input');

/* decodifica un JSON y inicializa un arreglo de respuesta. */
$params = json_decode($params);
$response = array();
$response["error"] = false;
$response["code"] = 0;

$ENCRYPTION_KEY = "D!@#$%^&*";


/* maneja solicitudes HTTP y procesa una URI separando parámetros. */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$arraySuper = explode("/", current(explode("?", $URI)));

$time = time();

/* crea un log con fecha, hora y URI en formato específico. */
$log = "\r\n" . "------------------------- " . $time . "\r\n";
$log = $log . date('Y-m-d H:i:s');
$log = $log . $URI;/**/
$log = $log . file_get_contents('php://input');
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

try {

    /* verifica si una acción está bloqueada y arroja una excepción si es así. */
    try {
        $responseEnable = file_get_contents(__DIR__ . '/../../logSit/enabled');
    } catch (Exception $e) {
    }

    if ($responseEnable == 'BLOCKED') {
        throw new Exception("Inusual Detected", "11");
    }

    switch ($arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1]) {
        case 'machineprint/deposit':

            /* Configura el tipo de contenido y procesa un ID recibido en la solicitud. */
            header('Content-Type: text/html; charset=UTF-8');
            $id = $_REQUEST['id'];

            $parameterR = $id;
            $parameterR = (str_replace(" ", "+", $parameterR));

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            /* descifra un parámetro utilizando la configuración del entorno. */
            $id = $ConfigurationEnvironment->decrypt($parameterR);
            if ($id != '') {


                /* Se crea un objeto UsuarioRecarga con un identificador específico. */
                $UsuarioRecarga = new UsuarioRecarga();


                $Id = $id;
                $seguir = true;

                $UsuarioRecarga = new UsuarioRecarga($Id);

                /* Se crean instancias de la clase Usuario utilizando identificadores de usuario y punto de venta. */
                $Usuario = new Usuario($UsuarioRecarga->usuarioId);
                $UsuarioPuntoVenta = new Usuario($UsuarioRecarga->puntoventaId);
                if ($seguir) {


                    if ($UsuarioPuntoVenta->usuarioId == 25415) {

                        try {

                            /* Genera un PDF con datos de usuario y recarga utilizando un template HTML. */
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
                            /* Manejo de excepciones en PHP, permite capturar errores sin interrumpir la ejecución. */


                        }
                    } else {


                        /* Se instancia un objeto "Mandante" utilizando el atributo "mandante" del objeto "Usuario". */
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


                        /* Genera una fila de tabla HTML con información del cliente si se cumplen condiciones específicas. */
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


                        /* Condicional que agrega información de RUC para usuarios de un país específico. */
                        if ($Usuario->paisId == 173) {
                            $pdf .= '
    <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
    </div>';

                        }


                        /* Genera un código HTML para mostrar un código de barras basado en una recarga de usuario. */
                        $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
            <div class="barcodecell" style="  text-align: center;"><barcode code="' . $UsuarioRecarga->getRecargaId() . '" type="I25" class="barcode" /></div>
        </div>
    </div>
</div>
</body>

';


                        /* Asigna el objeto PDF a la clave "Pdf" del array $response. */
                        $response["Pdf"] = $pdf;


                    }
                } else {
                    /* inicializa una respuesta vacía con posición y conteo en cero. */


                    $response["pos"] = 0;
                    $response["total_count"] = 0;
                    $response["data"] = array();

                }


                /* Imprime en formato legible el contenido del array asociativo "$response" bajo la clave "Pdf". */
                print_r($response["Pdf"]);
            }

            /* La función `exit()` termina la ejecución del programa o script actual. */
            exit();

            break;
        case 'machineprint/withdraw':

            /* Configura el tipo de contenido y recibe un parámetro `id` en UTF-8. */
            header('Content-Type: text/html; charset=UTF-8');
            $id = $_REQUEST['id'];

            $parameterR = $id;
            $parameterR = (str_replace(" ", "+", $parameterR));


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            /* cifra y descifra un identificador usando un entorno de configuración. */
            $id = $ConfigurationEnvironment->encrypt("615804");
            $id = $ConfigurationEnvironment->decrypt($parameterR);
            if ($id != '') {

                /* Se crea una instancia de CuentaCobro y se obtienen datos del usuario y registro. */
                $CuentaCobro = new CuentaCobro($id);
                $consecutivo_recarga = $CuentaCobro->cuentaId;
                $Usuario = new Usuario($CuentaCobro->usuarioId);
                $Registro = new Registro("", $CuentaCobro->usuarioId);

                $CuentaCobroMySqlDAO2 = new CuentaCobroMySqlDAO();

                /* Asigna valores de usuario y cuenta a variables relacionadas con cobros e impuestos. */
                $ClientId = $Usuario->usuarioId;
                $clave = $CuentaCobroMySqlDAO2->getClaveD($CuentaCobro->cuentaId);

                $amount = $CuentaCobro->valor;
                $valorImpuesto = $CuentaCobro->impuesto;
                $valorPenalidad = $CuentaCobro->costo;

                /* establece el valor final y define un estilo para la impresión. */
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


                /* Genera un mensaje HTML con información sobre una nota de retiro de cliente. */
                $status_message .= '
    <tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr>';

                if ($Usuario->paisId == 173) {


                    /* Asigna nombres a tipos de documentos según un identificador en una variable. */
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


                    /* Genera una tabla HTML con información sobre tipo de documento y número de documento. */
                    $status_message .= "
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Tipo de Doc: :&nbsp;&nbsp;" . $tipoDoc . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Documento:&nbsp;&nbsp;" . $Registro->cedula . "</font></td></tr>
    ";
                }


                /* Genera una tabla HTML que muestra detalles de un retiro, incluyendo impuestos y costos. */
                $status_message .= '<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . $amount . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto:&nbsp;&nbsp;' . $valorImpuesto . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Costo:&nbsp;&nbsp;' . $valorPenalidad . '</font></td></tr>
    <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar:&nbsp;&nbsp;' . $valorFinal . '</font></td></tr>
    <tr><td align="center" valign="top"><div style="height:5px;">&nbsp;</div></td></tr>
    </tbody></table>';


                try {

                    /* Se crea un clasificador y un template HTML usando datos del usuario. */
                    $Clasificador = new Clasificador("", "TEMRECNORE");

                    $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
                    $html_barcode = $Template->templateHtml;
                    if ($html_barcode != '') {

                        /* Reemplaza marcadores en un HTML con valores de una cuenta de cobro. */
                        $html_barcode = str_replace("#idnotewithdrawal#", $CuentaCobro->cuentaId, $html_barcode);
                        $html_barcode = str_replace("#withdrawalnotenumber#", $CuentaCobro->cuentaId, $html_barcode);
                        $html_barcode = str_replace("#value#", $CuentaCobro->valor, $html_barcode);
                        $html_barcode = str_replace("#totalvalue#", $CuentaCobro->valor, $html_barcode);
                        $html_barcode = str_replace("#tax#", $CuentaCobro->impuesto, $html_barcode);
                        $html_barcode = str_replace("#keynotewithdrawal#", $clave, $html_barcode);

                        /* reemplaza marcadores en un HTML para generar un código de barras personalizado. */
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
                    /* Maneja excepciones en PHP, permitiendo continuar la ejecución sin interrumpir el flujo. */


                }


                /* imprime en formato legible el contenido de la variable $status_message. */
                print_r($status_message);
            }

            /* El comando "exit();" finaliza la ejecución de un script o programa en programación. */
            exit();

            break;
        case 'betshop/geoip':


            /* obtiene la dirección IP del usuario considerando proxies. */
            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

            /* valida la longitud de una dirección IP y lanza una excepción si es larga. */
            $ip = explode(",", $ip)[0];


            if (strlen($ip) >= 20) {
                if (strlen($ip) >= 20) {

                    throw new Exception("Datos de login incorrectos", "50003");
                }
            }
// echo "Remote IP:$ip-$URI";


//Se hace explode para tomar la primera IP

            /* captura y depura una dirección IP y información del usuario. */
            $dir_ip = $ip;

//Se captura la URL para de allí extraer el numero del punto de venta
            $usuario = $_GET['info'];

//Depurarar caracteres
            $dir_ip = DepurarCaracteres($dir_ip);


            /* Se obtiene un token de URL y se crea una regla para una consulta. */
            $URI = $_SERVER["REQUEST_URI"];

            $token = (explode("info=", $URI)[1]);
            $rules = array();

// array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "2", "op" => "eq"));

            /* Se generan reglas para filtrar datos y se convierten a formato JSON. */
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);


            /* Establece locale en checo y obtiene datos de usuarios en formato JSON. */
            setlocale(LC_ALL, 'czech');


            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom("usuario_token_interno.*,usuario.usuario_id", "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $data = json_decode($data);


            /* inicializa un arreglo de respuesta con errores y códigos como cero. */
            $response["error"] = 0;
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* Se inicializa un usuario y se prepara la transacción para registrar logs. */
                $Usuario = new Usuario($data->data[0]->{'usuario.usuario_id'});

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $Transaction = $UsuarioLogMySqlDAO->getTransaction();

                $UsuarioLog = new UsuarioLog();

                /* establece propiedades en un objeto de registro de usuario. */
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId(0);
                $UsuarioLog->setUsuariosolicitaIp($ip);

                $UsuarioLog->setTipo("DIRIPBETSHOP");

                /* registra un cambio de estado y valores en un log de usuario. */
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
                /* Genera una excepción si los datos de inicio de sesión son incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'user/chat-search':


            /* asigna un token y un userid, buscando el usuario en solicitudes si está vacío. */
            $token = $params->token;

            $userid = $params->userid;

            if ($userid == "") {
                $userid = $_REQUEST['userid'];

            }


            /* valida tokens y usuarios, lanzando excepciones si están vacíos. */
            if ($token == "") {
// throw new Exception("Field: Key", "50001");

            }
            if ($userid == "") {
                throw new Exception("Field: document, userid, phone", "50001");

            }


            /* Se inicializan variables para controlar filas y crear reglas de procesamiento de datos. */
            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];


            /* Inicializa variables de respuesta y estado de token en un sistema. */
            $tokenSec = true;


            $response["error"] = 0;
            $response["code"] = 0;


            if ($tokenSec) {


                /* Crea un objeto Usuario si $userid no está vacío. */
                if ($userid != "") {
                    $Usuario = new Usuario($userid);

                } else {

                    if ($document != "") {

                        /* Se crean reglas de validación para comparación de datos en un arreglo. */
                        $rules = [];

                        array_push($rules, array("field" => "registro.cedula", "data" => "$document", "op" => "eq"));
                        array_push($rules, array("field" => "usuario.mandante", "data" => $data->data[0]->{"usuario.mandante"}, "op" => "eq"));
                        if ($phone != "") {
                            array_push($rules, array("field" => "registro.celular", "data" => "$phone", "op" => "eq"));

                        }


                        /* Se genera un filtro JSON para obtener usuarios personalizados desde una base de datos. */
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $Usuario = new Usuario();

                        $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

                        /* decodifica un JSON y crea un objeto Usuario con un ID específico. */
                        $usuarios = json_decode($usuarios);

                        $Usuario = new Usuario($usuarios->data[0]->{"usuario.usuario_id"});


                    }
                }

                /* Se verifica el perfil del usuario y se lanza una excepción si es inválido. */
                $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                $Registro = new Registro('', $Usuario->usuarioId);

                if ($UsuarioPerfil->getPerfilId() != 'USUONLINE') {
                    throw new Exception("No existe Usuario", "24");
                }


                /* asigna datos del usuario a un array de respuesta. */
                $response["idUser"] = $Usuario->usuarioId;
                $response["idCasino"] = $UsuarioMandante->usumandanteId;
                $response["name"] = $Usuario->nombre;
                $response["identification"] = $Registro->cedula;
                $response["email"] = $Usuario->login;
                $response["phone"] = $Registro->celular;

                /* obtiene y organiza información del usuario en un arreglo de respuesta. */
                $response["balance"] = $Usuario->getBalance();
                $response["state"] = $Usuario->estado;

                $response["contingency"] = $Usuario->contingencia;
                $response["contingencySports"] = $Usuario->contingenciaDeportes;
                $response["contingencyCasino"] = $Usuario->contingenciaCasino;

                /* Asigna valores de contingencias y observaciones del usuario a un arreglo de respuesta. */
                $response["contingencyLiveCasino"] = $Usuario->contingenciaCasvivo;
                $response["contingencyVirtual"] = $Usuario->contingenciaVirtuales;
                $response["contingencyPoker"] = $Usuario->contingenciaPoker;

                $response["note"] = $Usuario->observ;


            } else {
                /* Lanza una excepción si los datos de inicio de sesión son incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'user/search':


            /* Asignación de parámetros a variables en un script PHP para manejo de información. */
            $shop = $params->shop;
            $token = $params->token;
            $document = $params->document;
            $userid = $params->userid;
            $pais = $params->country;
            $phone = $params->phone;


            /* valida campos vacíos y lanza excepciones correspondientes si están incompletos. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }
            if ($document == "" && $userid == "" && $phone == "") {
                throw new Exception("Field: document, userid, phone", "50001");

            }

            /* lanza excepciones si 'document' o 'phone' tienen valores en un 'shop' específico. */
            if ($document != '' && $shop == '1211624') {
                throw new Exception("Field: document, userid, phone", "50001");
            }

            if ($phone != '' && $shop == '1211624') {
                throw new Exception("Field: document, userid, phone", "50001");
            }


            /* Verifica si el país está vacío y genera una excepción si es cierto. */
            if ($pais == "") {
                throw new Exception("Field: Pais", "50001");

            }

            $MaxRows = 1;

            /* Se inicializan variables para ordenar ítems y saltar filas, sin reglas definidas. */
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


            /* Se generan reglas de filtrado para una consulta, combinadas con operación AND. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro a formato JSON y establece la localización en checo. */
            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario.mandante,usuario_token_interno.* ";


            /* Se crea un objeto y se obtiene datos de usuario en formato JSON. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Asigna el valor 0 a la clave "code" en el array $response. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* Se verifica si $userid no está vacío y se crea un objeto Usuario. */
                if ($userid != "") {
                    $Usuario = new Usuario($userid);

                } else {

                    if ($document != "") {

                        /* Se crean reglas para validar campos de un registro basado en condiciones. */
                        $rules = [];

                        array_push($rules, array("field" => "registro.cedula", "data" => "$document", "op" => "eq"));
                        array_push($rules, array("field" => "usuario.mandante", "data" => $data->data[0]->{"usuario.mandante"}, "op" => "eq"));
                        if ($phone != "") {
                            array_push($rules, array("field" => "registro.celular", "data" => "$phone", "op" => "eq"));

                        }


                        /* Se crean reglas de filtrado para usuarios y se convierten a formato JSON. */
                        array_push($rules, array("field" => "usuario.plataforma", "data" => "0", "op" => "eq"));
                        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $Usuario = new Usuario();


                        /* obtiene y decodifica usuarios desde una base de datos en formato JSON. */
                        $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
                        $usuarios = json_decode($usuarios);

                        $Usuario = new Usuario($usuarios->data[0]->{"usuario.usuario_id"});


                    }
                }

                /* Se valida el perfil del usuario y se crea una instancia de Usuario. */
                $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

                if ($UsuarioPerfil->getPerfilId() != 'USUONLINE') {
                    throw new Exception("No existe Usuario", "24");
                }
                $UsuarioPuntoVenta = new Usuario($shop);


                /* Valida que el usuario pertenezca al mismo país y partner que el punto de venta. */
                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }

                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }


                /* Asigna el nombre y el ID del usuario a un arreglo de respuesta. */
                $response["name"] = $Usuario->nombre;
                $response["userid"] = $Usuario->usuarioId;

            } else {
                /* Lanza una excepción si los datos de inicio de sesión son incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'system/ping':
            /* responde con un mensaje de "ok" para el comando 'system/ping'. */


            $response["error"] = 0;
            $response["code"] = 0;
            $response["time"] = time();
            $response["message"] = 'ok';

            break;

        case 'user/deposit':


            /* obtiene parámetros del usuario y registra el tiempo de inicio. */
            $start_time = microtime(true);

            $shop = $params->shop;
            $token = $params->token;
            $document = $params->document;
            $userid = $params->userid;

            /* valida un token y asigna parámetros de una transacción. */
            $pais = $params->country;
            $amount = $params->amount;
            $transactionId = $params->transactionId;
            $shopReference = $params->shopReference;

            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            /* lanza excepciones si ciertos campos están vacíos. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }
            if ($document == "" && $userid == "") {
                throw new Exception("Field: document, userid", "50001");

            }

            /* lanza excepciones si los campos "Valor" o "Pais" están vacíos. */
            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");

            }
            if ($pais == "") {
                throw new Exception("Field: Pais", "50001");

            }

            /* verifica si transactionId está vacío y lanza una excepción si es cierto. */
            if ($transactionId == "") {
                throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;

            /* Se configuran variables para ordenar elementos y omitir filas en un proceso determinado. */
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];

            /*
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
            array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
            array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_log.* ";


            $UsuarioLog = new UsuarioLog();
            $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $data = json_decode($data);
            */


            /* Se crean reglas de filtrado para una consulta de base de datos. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro a formato JSON y define una consulta SQL. */
            $json = json_encode($filtro);

//setlocale(LC_ALL, 'czech');


            $select = " usuario.mandante,usuario_token_interno.* ";


            /* Se obtiene datos de usuario, se decodifica en JSON y se establece respuesta sin errores. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Asigna un valor de 0 a la clave "code" en el array $response. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* Verifica si el establecimiento está en la lista y valida que el monto sea mayor a 1. */
                if (in_array($shop, array(1784692, 853460))) {


                    if (floatval($amount) < 1) {
                        throw new Exception("Field: Valor", "50001");

                    }
                }

                /* Verifica si el valor de '$amount' es menor a 1 y lanza excepción. */
                if (in_array($shop, array(853460))) {


                    if (floatval($amount) < 1) {
                        throw new Exception("Field: Valor", "50001");

                    }
                }


                /* Crea un objeto "Usuario" si $userid no está vacío. */
                if ($userid != "") {
                    $Usuario = new Usuario($userid);

                } else {


                    /* filtra usuarios basándose en condiciones específicas y retorna resultados en formato JSON. */
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


                /* Se verifica que el usuario y el punto de venta pertenezcan al mismo país. */
                $UsuarioPuntoVenta = new Usuario($shop);

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }

                /* valida si el usuario pertenece al país y al mandante correcto. */
                if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
                    throw new Exception("Código de país incorrecto", "10018");

                }
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }


                /* Lanza una excepción si el usuario está marcado como eliminado. */
                if ($Usuario->eliminado == 'S') {
                    throw new Exception("Usuario no pertenece al partner", "50006");

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


                /* Código para recuperar una transacción y establecer el ID de usuario en un objeto. */
                $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();


                $UsuarioRecarga = new UsuarioRecarga();
//$UsuarioRecarga->setRecargaId($consecutivo_recarga);
                $UsuarioRecarga->setUsuarioId($Usuario->usuarioId);

                /* establece propiedades de un objeto UsuarioRecarga con datos específicos. */
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

                /* Se establece valor, estado y versión para insertar un registro de recarga. */
                $UsuarioRecarga->setValorIva(0);
                $UsuarioRecarga->setEstado('A');
                $UsuarioRecarga->setVersion(2);

                $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

                $consecutivo_recarga = $UsuarioRecarga->recargaId;


                /* Creación y configuración de una transacción API con datos de usuario y valor. */
                $TransaccionApiUsuario = new TransaccionApiUsuario();

                $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransaccionApiUsuario->setValor(($amount));
                $TransaccionApiUsuario->setTipo(0);

                /* establece valores en un objeto de transacción API del usuario. */
                $TransaccionApiUsuario->setTValue(json_encode($params));
                $TransaccionApiUsuario->setRespuestaCodigo("OK");
                $TransaccionApiUsuario->setRespuesta("OK");
                $TransaccionApiUsuario->setTransaccionId($transactionId);

                $TransaccionApiUsuario->setUsucreaId(0);

                /* verifica si una transacción ya fue procesada y lanza una excepción. */
                $TransaccionApiUsuario->setUsumodifId(0);


                if ($TransaccionApiUsuario->existsTransaccionIdAndProveedor("OK")) {

//  Si la transaccionId ha sido procesada, reportamos el error
                    throw new Exception("Transaccion ya procesada", "10001");

                }


                /* inserta un registro y establece un identificador en un log. */
                $TransaccionApiUsuario->setIdentificador($consecutivo_recarga);
                $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                $TransapiusuarioLog = new TransapiusuarioLog();

                $TransapiusuarioLog->setIdentificador($consecutivo_recarga);

                /* Registro de transacciones y parámetros asociados en el sistema de log. */
                $TransapiusuarioLog->setTransaccionId($transactionId);
                $TransapiusuarioLog->setTValue(json_encode($params));
                $TransapiusuarioLog->setTipo(0);
                $TransapiusuarioLog->setValor($amount);
                $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                /* Se crean registros de usuario con IDs de creación y modificación establecidos en cero. */
                $TransapiusuarioLog->setUsucreaId(0);
                $TransapiusuarioLog->setUsumodifId(0);


                $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);
                $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


                /* Se registra un crédito al usuario y se crea un historial de transacción. */
                $Usuario->credit($amount, $Transaction);

                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('E');

                /* Configura historial de usuario con ID y valores específicos para una transacción. */
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(10);
                $UsuarioHistorial->setValor($amount);
                $UsuarioHistorial->setExternoId($consecutivo_recarga);

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);

                /* Inserta un historial de usuario en la base de datos y prepara un objeto FlujoCaja. */
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                $rowsUpdate = 0;

                $FlujoCaja = new FlujoCaja();

                /* Establece atributos de un objeto FlujoCaja relacionadas a fecha, hora y usuario. */
                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
                $FlujoCaja->setTipomovId('E');
                $FlujoCaja->setValor($UsuarioRecarga->getValor());
                $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());

                /* Configuración de un objeto FlujoCaja con datos del usuario y pagos. */
                $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
                $FlujoCaja->setTraslado('N');
                $FlujoCaja->setFormapago1Id(1);
                $FlujoCaja->setCuentaId('0');

                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }


                /* Asigna valor cero a forma 1 y forma 2 si están vacíos. */
                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }

                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }


                /* verifica y establece valores predeterminados para cuentas e IVA vacíos. */
                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }

                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }


                /* verifica y establece el valor del IVA antes de insertar en la base de datos. */
                if ($FlujoCaja->getValorIva() == "") {
                    $FlujoCaja->setValorIva(0);
                }

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


                $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                if ($rowsUpdate > 0) {

                    try {


                        /* Se crean objetos para gestionar ventas y registros, y se carga información de ciudad. */
                        $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->usuarioId);

                        $Registro = new Registro('', $Usuario->usuarioId);

                        $CiudadMySqlDAO = new CiudadMySqlDAO();

                        $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);

                        /* Carga la ciudad del punto de venta y cuenta los depósitos del usuario. */
                        $CiudadPuntoVenta = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);


                        $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $Usuario->usuarioId . "'");

                        $detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];


                        /* crea un array con detalles sobre depósitos y usuario para procesar pagos. */
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


                        /* Se crea un bono interno y se agrega con detalles específicos del usuario. */
                        $BonoInterno = new BonoInterno();
                        $detalles = json_decode(json_encode($detalles));

                        $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);
                    } catch (Exception $e) {
                        /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir el flujo del programa. */


                    }


                    /* Se crea un historial de usuario con datos específicos y acciones predeterminadas. */
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('S');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);

                    /* inserta un historial de usuario en la base de datos con datos específicos. */
                    $UsuarioHistorial->setTipo(10);
                    $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                    $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


                    /* Calcula la duración en horas, minutos y segundos entre dos momentos. */
                    $end_time = microtime(true);
                    $duration = $end_time - $start_time;
                    $hours = (int)($duration / 60 / 60);
                    $minutes = (int)($duration / 60) - $hours * 60;

                    $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;


                    /* lanza excepciones si se superan 15 segundos o no hay crédito suficiente. */
                    if ($seconds >= 15) {
                        throw new Exception("Error General", "100000");
                    }


                    if (floatval($PuntoVenta->getCreditosBase()) - floatval($amount) < 0) {
                        throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100002");
                    }


                    /* Actualiza el balance de créditos y verifica disponibilidad antes de confirmar la transacción. */
                    $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$amount, $Transaction);

                    if ($rowsUpdate == null || $rowsUpdate <= 0) {
                        throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100000");
                    }

                    $Transaction->commit();


                    /* Calcula el tiempo transcurrido en horas, minutos y segundos. */
                    $end_time = microtime(true);
                    $duration = $end_time - $start_time;
                    $hours = (int)($duration / 60 / 60);
                    $minutes = (int)($duration / 60) - $hours * 60;
                    $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;

                    if ($seconds >= 15) {

                        /* Ejecuta un script PHP en segundo plano para procesar una transacción de usuario. */
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'TIME OPERATORAPI DEPOSIT  " . $seconds . " s '.$transactionId. '#alertas-integraciones' > /dev/null & ");

                        sleep(1);

                        $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "0");

                        /**
                         * Actualizamos consecutivo Recarga
                         */

                        $UsuarioRecarga = new UsuarioRecarga($TransaccionApiUsuario->getIdentificador());


                        /* Verifica estado de recarga antes de marcarla como eliminada y registra información. */
                        if ($UsuarioRecarga->getEstado() != "A") {
                            throw new Exception("La recarga no se puede eliminar", "50001");
                        }
                        $UsuarioRecarga->setEstado('I');
                        $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
                        $UsuarioRecarga->setUsueliminaId($UsuarioRecarga->getPuntoventaId());


                        /* Se crea un DAO para la recarga de usuario y se actualiza la transacción correspondiente. */
                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                        $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();

                        $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);

                        $puntoventa_id = $UsuarioRecarga->getPuntoventaId();

                        /* Se obtiene valor de recarga y se crea un flujo de caja asociado al usuario. */
                        $valor = $UsuarioRecarga->getValor();

                        $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

                        $FlujoCaja = new FlujoCaja();
                        $FlujoCaja->setFechaCrea(date('Y-m-d'));

                        /* Se configura un objeto FlujoCaja con datos de transacción financiera. */
                        $FlujoCaja->setHoraCrea(date('H:i'));
                        $FlujoCaja->setUsucreaId($puntoventa_id);
                        $FlujoCaja->setTipomovId('S');
                        $FlujoCaja->setValor($valor);
                        $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                        $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

                        /* Se configura un flujo de caja y se inicializan métodos de pago vacíos. */
                        $FlujoCaja->setDevolucion('S');

                        if ($FlujoCaja->getFormapago1Id() == "") {
                            $FlujoCaja->setFormapago1Id(0);
                        }

                        if ($FlujoCaja->getFormapago2Id() == "") {
                            $FlujoCaja->setFormapago2Id(0);
                        }


                        /* Verifica si los valores están vacíos y los establece en cero si es necesario. */
                        if ($FlujoCaja->getValorForma1() == "") {
                            $FlujoCaja->setValorForma1(0);
                        }

                        if ($FlujoCaja->getValorForma2() == "") {
                            $FlujoCaja->setValorForma2(0);
                        }


                        /* asigna valores predeterminados a atributos vacíos de un objeto. */
                        if ($FlujoCaja->getCuentaId() == "") {
                            $FlujoCaja->setCuentaId(0);
                        }

                        if ($FlujoCaja->getPorcenIva() == "") {
                            $FlujoCaja->setPorcenIva(0);
                        }


                        /* verifica y establece el valor del IVA antes de insertar en la base de datos. */
                        if ($FlujoCaja->getValorIva() == "") {
                            $FlujoCaja->setValorIva(0);
                        }

                        $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                        $FlujoCajaMySqlDAO->insert($FlujoCaja);
//print_r(time());


                        /* Se actualiza el balance de créditos de un punto de venta y se valida. */
                        $PuntoVenta = new PuntoVenta("", $puntoventa_id);


                        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);

                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                            throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100000");
                        }

//print_r(time());


                        /* Crea y configura un objeto de ajuste de saldo para un usuario específico. */
                        $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                        $SaldoUsuonlineAjuste->setTipoId('S');
                        $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                        $SaldoUsuonlineAjuste->setValor($valor);
                        $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));

                        /* Ajusta el saldo de usuario y registra observaciones en una recarga. */
                        $SaldoUsuonlineAjuste->setUsucreaId($UsuarioPuntoVenta->usuarioId);
                        $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                        $SaldoUsuonlineAjuste->setObserv("Reversion recarga API " . $UsuarioRecarga->getRecargaId());
                        if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                            $SaldoUsuonlineAjuste->setMotivoId(0);
                        }

                        /* obtiene la dirección IP del usuario y actualiza el estado de la sesión. */
                        $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                        $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                        $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());

                        $UsuarioRecarga->setEstado('I');

                        /* Actualiza la fecha de eliminación y el usuario de recarga en la base de datos. */
                        $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
                        $UsuarioRecarga->setUsueliminaId(0);

                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);

                        $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);


                        /* gestiona la inserción y el débito de usuarios en una base de datos. */
                        $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                        $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);

//print_r(time());


                        $Usuario->debit($UsuarioRecarga->getValor(), $Transaction);


                        /* Se crea un historial de usuario con datos iniciales y movimientos específicos. */
                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('S');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);

                        /* Código que establece valores en objeto y lo inserta en base de datos MySQL. */
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($valor);
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

//print_r(time());


                        /* Se crea un nuevo historial de usuario con datos específicos y movimientos registrados. */
                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($puntoventa_id);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);

                        /* configura un historial de usuario y crea una transacción para API. */
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

//$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
//$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

//print_r(time());

                        $TransaccionApiUsuario = new TransaccionApiUsuario();


                        /* Configura una transacción con usuario, valor, tipo y parámetros en JSON. */
                        $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                        $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                        $TransaccionApiUsuario->setValor(($amount));
                        $TransaccionApiUsuario->setTipo(0);
                        $TransaccionApiUsuario->setTValue(json_encode($params));
                        $TransaccionApiUsuario->setRespuestaCodigo("OK");

                        /* Asignación de valores a propiedades de una transacción de usuario en una API. */
                        $TransaccionApiUsuario->setRespuesta("OK");
                        $TransaccionApiUsuario->setTransaccionId($transactionId);

                        $TransaccionApiUsuario->setUsucreaId(0);
                        $TransaccionApiUsuario->setUsumodifId(0);


                        $TransaccionApiUsuario->setIdentificador($UsuarioRecarga->getRecargaId());

                        /* Crea un objeto DAO y registra una transacción en MySQL. */
                        $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                        $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

//print_r(time());

                        $TransapiusuarioLog = new TransapiusuarioLog();


                        /* registra datos de una transacción y usuario en un sistema log. */
                        $TransapiusuarioLog->setIdentificador($UsuarioRecarga->getRecargaId());
                        $TransapiusuarioLog->setTransaccionId($transactionId);
                        $TransapiusuarioLog->setTValue(json_encode($params));
                        $TransapiusuarioLog->setTipo(3);
                        $TransapiusuarioLog->setValor($amount);
                        $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

                        /* Establece IDs de usuario y crea una instancia de TransapiusuarioLog en MySQL. */
                        $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                        $TransapiusuarioLog->setUsucreaId(0);
                        $TransapiusuarioLog->setUsumodifId(0);


                        $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

                        /* Inserta un registro en la base de datos y maneja posibles excepciones. */
                        $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

//print_r(time());

                        $Transaction->commit();

                        throw new Exception("Error General", "100000");
                    }


                    /* Registra la fecha y monto del primer depósito de un usuario. */
                    if ($Usuario->fechaPrimerdeposito == "") {
                        $Usuario = new Usuario($Usuario->usuarioId);

                        $Usuario->fechaPrimerdeposito = date('Y-m-d H:i:s');
                        $Usuario->montoPrimerdeposito = $UsuarioRecarga->getValor();
                        $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO2->update($Usuario);
                        $UsuarioMySqlDAO2->getTransaction()->commit();
                    }


                    /* ejecuta un script PHP en segundo plano, suplantando errores. */
                    try {


                        try {
                            exec("php -f " . __DIR__ . "/../src/integrations/payment" . "/scriptsDeposito.php " . $consecutivo_recarga . " " . '' . " > /dev/null &");

                        } catch (Exception $e) {

                        }


                    } catch (Exception $e) {
                        /* Registra advertencias de errores de API en el sistema de logs del servidor. */

                        syslog(LOG_WARNING, "ERRORPROVEEDORAPI :" . $e->getCode() . ' - ' . $e->getMessage());
                    }

                } else {
                    /* Lanza una excepción de error general con un código específico. */

                    throw new Exception("Error General", "100000");
                }


                /* Asignación de valores a un arreglo de respuesta para una transacción y un depósito. */
                $response["transactionId"] = $Transapiusuariolog_id;
                $response["depositId"] = $consecutivo_recarga;


            } else {
                /* lanza una excepción si los datos de inicio de sesión son incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'user/withdraw':


            /* almacena el tiempo actual y extrae parámetros de una solicitud. */
            $start_time = microtime(true);

            $shop = $params->shop;
            $token = $params->token;
            $nota = $params->withdrawId;
            $clave = $params->password;

            /* asigna valores de parámetros y valida un token. */
            $pais = $params->country;

            $transactionId = $params->transactionId;

            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            /* valida campos vacíos y lanza excepciones si faltan datos. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            if ($nota == "") {
                throw new Exception("Field: Nota", "50001");

            }


            /* verifica si los campos "clave" y "pais" están vacíos, lanzando excepciones. */
            if ($clave == "") {
                throw new Exception("Field: Clave", "50001");

            }
            if ($pais == "") {
                throw new Exception("Field: Pais", "50001");

            }

            /* verifica si el ID de transacción está vacío y lanza una excepción. */
            if ($transactionId == "") {
                throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;

            /* Se declaran variables para ordenar elementos y saltar filas, además de un array de reglas. */
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


            /* Se configuran reglas de filtrado para una consulta utilizando un array en PHP. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro a formato JSON y establece la localización a checa. */
            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_token_interno.* ";


            /* Se crea un objeto, se obtiene datos JSON y se inicializa la respuesta. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Código verifica credenciales y devuelve un identificador de transacción en formato JSON. */
            $response["code"] = 0;
            if ($nota == '2926653' && $clave == '40768') {
                $response["transactionId"] = '1076577';
                print_r(json_encode($response));
                exit();
            }


            if (oldCount($data->data) > 0) {


                /* crea instancias de "CuentaCobro" y "Usuario" para procesar información relacionada. */
                $CuentaCobro = new CuentaCobro($nota, "", $clave);
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $valor = $CuentaCobro->getValor();

                $UsuarioPuntoVenta = new Usuario($shop);

                /* Validación de país del usuario antes de crear un punto de venta. */
                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }


                /* Verifica la validez del país y la relación del usuario con el partner. */
                if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
                    throw new Exception("Código de país incorrecto", "10018");

                }
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }


                /* verifica el estado de una cuenta y las autorizaciones del usuario para pagos. */
                if ($CuentaCobro->getEstado() != 'A') {
                    throw new Exception("La nota de retiro no se puede pagar porque no esta activa", "50007");
                }

                if (($UsuarioPuntoVenta->usuarioId == 693978 || $UsuarioPuntoVenta->usuarioId == 853460 || $UsuarioPuntoVenta->usuarioId == 1211624 || $UsuarioPuntoVenta->usuarioId == 2894342) && $CuentaCobro->getMediopagoId() != $UsuarioPuntoVenta->usuarioId) {
                    throw new Exception("No existe nota de retiro", "12");
                }


                /* Se instancian objetos para gestionar transacciones y configuraciones de usuario en MySQL. */
                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();
                $Amount = $CuentaCobro->getValor();


                $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);


                /* Valida que el monto de retiro no exceda el máximo permitido para el usuario. */
                if ($UsuarioConfig->maxpagoRetiro != "" && $UsuarioConfig->maxpagoRetiro != "0") {
                    if (floatval($UsuarioConfig->maxpagoRetiro) < floatval($CuentaCobro->getValor())) {
                        throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
                    }
                }

                $rowsUpdate = 0;


                /* Se configura un entorno y se obtienen transacciones y dirección IP del cliente. */
                $ConfigurationEnvironment = new ConfigurationEnvironment();

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();
                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);

                /* Actualiza el estado de CuentaCobro y registra información relevante para el pago. */
                $CuentaCobro->setDiripCambio($dirIp);

                $CuentaCobro->setEstado('I');
                $CuentaCobro->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
                $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='A' ");


                /* Verifica condiciones y lanza excepción si no se actualizan filas en la base de datos. */
                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }

                $rowsUpdate = 0;

                $FlujoCaja = new FlujoCaja();

                /* Establece propiedades de un objeto FlujoCaja con datos actuales y de usuario. */
                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
                $FlujoCaja->setTipomovId('S');
                $FlujoCaja->setValor($CuentaCobro->getValor());
                $FlujoCaja->setTicketId('');

                /* asigna valores a propiedades de un objeto "FlujoCaja" basado en otro objeto. */
                $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
                $FlujoCaja->setMandante($CuentaCobro->getMandante());
                $FlujoCaja->setTraslado('N');
                $FlujoCaja->setRecargaId(0);

                if ($FlujoCaja->getFormapago1Id() == "") {
                    $FlujoCaja->setFormapago1Id(0);
                }


                /* asigna 0 si las propiedades de $FlujoCaja están vacías. */
                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }

                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }


                /* Asigna valores predeterminados si las propiedades están vacías en FlujoCaja. */
                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }

                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }


                /* Inicializa valores de IVA a cero si no están definidos en FlujoCaja. */
                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }

                if ($FlujoCaja->getValorIva() == "") {
                    $FlujoCaja->setValorIva(0);
                }

                /* gestiona la inserción de datos y maneja excepciones en caso de error. */
                $FlujoCaja->setDevolucion('');

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                /* actualiza el balance de créditos y crea un historial de usuario. */
                $rowsUpdate = 0;

                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($CuentaCobro->getValor(), $Transaction);


                $UsuarioHistorial = new UsuarioHistorial();

                /* Configura un historial de usuario con datos específicos, incluyendo tipo y movimiento. */
                $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(40);

                /* Se guardan valores y identificadores en el historial de usuario en la base de datos. */
                $UsuarioHistorial->setValor($CuentaCobro->getValor());
                $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

                if ($rowsUpdate > 0) {


                    /* Se instancia un objeto y se configuran sus propiedades relacionadas con la transacción. */
                    $TransaccionApiUsuario = new TransaccionApiUsuario();

                    $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                    $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                    $TransaccionApiUsuario->setValor($valor);
                    $TransaccionApiUsuario->setTipo(1);

                    /* Configura una transacción API, estableciendo parámetros, respuestas y un ID de usuario. */
                    $TransaccionApiUsuario->setTValue(json_encode($params));
                    $TransaccionApiUsuario->setRespuestaCodigo("OK");
                    $TransaccionApiUsuario->setRespuesta("OK");
                    $TransaccionApiUsuario->setTransaccionId($transactionId);

                    $TransaccionApiUsuario->setUsucreaId(0);

                    /* Se configura y guarda una transacción de usuario en la base de datos. */
                    $TransaccionApiUsuario->setUsumodifId(0);

                    $TransaccionApiUsuario->setIdentificador($CuentaCobro->getCuentaId());
                    $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                    $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                    $TransapiusuarioLog = new TransapiusuarioLog();


                    /* Configura y registra un log de transacción usando datos de cuenta y usuario. */
                    $TransapiusuarioLog->setIdentificador($CuentaCobro->getCuentaId());
                    $TransapiusuarioLog->setTransaccionId($transactionId);
                    $TransapiusuarioLog->setTValue(json_encode($params));
                    $TransapiusuarioLog->setTipo(1);
                    $TransapiusuarioLog->setValor($Amount);
                    $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

                    /* Se configura un registro de usuario y se inicializa el DAO correspondiente. */
                    $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                    $TransapiusuarioLog->setUsucreaId(0);
                    $TransapiusuarioLog->setUsumodifId(0);


                    $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

                    /* inserta registros en bases de datos y mide el tiempo de ejecución. */
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

                    /* Calcula la duración en horas, minutos y segundos; lanza excepción si segundos ≥ 25. */
                    $duration = $end_time - $start_time;
                    $hours = (int)($duration / 60 / 60);
                    $minutes = (int)($duration / 60) - $hours * 60;

                    $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;

                    if ($seconds >= 25) {
                        throw new Exception("Error General", "100000");
                    }


                    /* confirma y guarda los cambios realizados en una transacción de base de datos. */
                    $Transaction->commit();
                } else {
                    /* Lanzo una excepción con un mensaje de error y un código específico. */

                    throw new Exception("Error General", "100000");
                }


                /* asigna un valor a la clave "transactionId" en un array llamado $response. */
                $response["transactionId"] = $Transapiusuariolog_id;


            } else {
                /* Lanza una excepción por datos de inicio de sesión incorrectos con un código específico. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;


        case 'user/conciliation':


            /* Asignación de variables desde un objeto `$params` para uso posterior en el código. */
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


            /* verifica campos vacíos y lanza excepciones para errores específicos. */
            if ($type == "") {
                throw new Exception("Field: type", "50001");

            }

            if ($data == "") {
//throw new Exception("Field: data", "50001");

            }

            /* Valida si la fecha está vacía y lanza una excepción si lo está. */
            if ($date == "") {
                throw new Exception("Field: date", "50001");

            } else {
                $date = date("Y-m-d", strtotime($date));
            }

            if ($shop == '2894342') {


                /* Variables para controlar la cantidad de filas y elementos en un sistema de pedidos. */
                $MaxRows = 1;
                $OrderedItem = 1;
                $SkeepRows = 0;

                $shop = $params->shop;
                $token = $params->token;

                /* verifica si la variable $shop está vacía y lanza una excepción. */
                $type = $params->type;
                $data = $params->data;
                $date = $params->date;


                if ($shop == "") {
                    throw new Exception("Field: $shop", "50001");

                }

                /* valida campos vacíos y lanza excepciones si los encuentra. */
                if ($token == "") {
                    throw new Exception("Field: token", "50001");

                }

                if ($type == "") {
                    throw new Exception("Field: type", "50001");

                }


                /* Lanza excepciones si los campos "data" o "date" están vacíos. */
                if ($data == "") {
                    throw new Exception("Field: data", "50001");

                }
                if ($date == "") {
                    throw new Exception("Field: date", "50001");

                }


                /* Se inicializan variables para gestionar la cantidad y el orden de filas en un conjunto de datos. */
                $MaxRows = 10000;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se construye un filtro con reglas para consultar datos de usuarios. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* Inicializa un arreglo de respuesta con valores de error y código en cero. */
                $response["error"] = 0;
                $response["code"] = 0;

                $final = [];

                if ($type == 1) {


                    /* Se configura una consulta para obtener registros desde una fecha específica. */
                    $MaxRows = 1000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Crea un filtro en formato JSON para reglas de consulta en base de datos. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Se obtienen registros de logs en formato JSON, se decodifican para su uso. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);


                    /* Convierte datos de transacciones en un array estructurado y ajusta el estado. */
                    foreach ($datos->data as $key => $value) {
                        $array = [];

                        $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                        $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                        $array["paidAmount"] = intval($value->{"transapiusuario_log.valor"});
                        $array["status"] = $value->{"transapiusuario_log.tipo"};
                        if ($array["status"] == 0) {
                            $array["status"] = "activo";
                        } else if ($array["status"] == 1) {
                            $array["status"] = "activo";
                        } else if ($array["status"] == 2) {
                            $array["status"] = "inactivo";
                        } else if ($array["status"] == 3) {
                            $array["status"] = "inactivo";
                        }
                        array_push($final, $array);
                    }

                }
                if ($type == 2) {


                    /* Código define límites de filas y reglas para filtrar datos por fecha. */
                    $MaxRows = 1000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se crean reglas de filtro y se convierten a JSON para usar en consultas. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Se recuperan registros de transapiusuario log y se decodifican en formato JSON. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {

                        /* convierte fechas a formato ISO 8601 y las almacena en un array. */
                        $array = [];
                        $fechaActual = date('Y-m-d H:i:s');
                        $fechaActual = str_replace(" ", "T", $fechaActual);

                        $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                        $array["transactionDate"] = $fecha;

                        /* Asigna valores de transacciones a un arreglo, transformando el estado a "activo". */
                        $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                        $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                        $array["paidAmount"] = intval($value->{"transapiusuario_log.valor"});
                        $array["status"] = $value->{"transapiusuario_log.tipo"};
                        if ($array["status"] == 0) {
                            $array["status"] = "activo";
                        } else if ($array["status"] == 1) {
                            /* asigna "activo" al estado de un arreglo si su valor es 1. */

                            $array["status"] = "activo";
                        } else if ($array["status"] == 2) {
                            /* cambia el estado de un array a "inactivo" si es igual a 2. */

                            $array["status"] = "inactivo";
                        } else if ($array["status"] == 3) {
                            /* convierte el estado 3 en "inactivo" en un array. */

                            $array["status"] = "inactivo";
                        }

                        /* Agrega el contenido de `$array` al final del arreglo `$final`. */
                        array_push($final, $array);
                    }
                }


                /* Crea un array con la fecha actual y elementos finales. */
                $array2 = [];
//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array2["accountingDate"] = $fechaActual;
                $array2["items"] = $final;

                /* Asigna un valor de error y establece datos en un arreglo dentro de una respuesta. */
                $response["error"] = 0;
                $response["data"] = array($array2);


            } elseif ($shop == '4133881') {


                /* inicializa variables para gestionar la paginación en una tienda. */
                $MaxRows = 1;
                $OrderedItem = 1;
                $SkeepRows = 0;

                $shop = $params->shop;
                $token = $params->token;

                /* verifica si la variable $shop está vacía y lanza una excepción. */
                $type = $params->type;
                $data = $params->data;
                $date = $params->date;


                if ($shop == "") {
                    throw new Exception("Field: $shop", "50001");

                }

                /* valida campos vacíos y lanza excepciones con códigos de error específicos. */
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


                /* Se definen variables para controlar la paginación y almacenar reglas en un array. */
                $MaxRows = 10000;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se crean reglas para filtrar usuarios y tokens en un array. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* inicializa un arreglo de respuesta sin errores y define un arreglo vacío. */
                $response["error"] = 0;
                $response["code"] = 0;

                $final = [];

                if ($type == 1) {


                    /* Se establece una consulta para filtrar registros de fecha mínima en SQL. */
                    $MaxRows = 1000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se crean reglas para filtrar datos y se convierten a formato JSON. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Se obtiene y decodifica un registro de logs de usuario en formato JSON. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {

                        /* crea un arreglo con información de una transacción. */
                        $array = [];

                        $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                        $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};


                        /* formatea un monto y asigna un estado basado en un tipo. */
                        $array["paidAmount"] = number_format($value->{"transapiusuario_log.valor"}, 2, '.', '');
                        $array["status"] = $value->{"transapiusuario_log.tipo"};
                        if ($array["status"] == 0) {
                            $array["status"] = "Active";
                        } else if ($array["status"] == 1) {
                            $array["status"] = "Active";
                        } else if ($array["status"] == 2) {
                            /* convierte el estado 2 en "Inactivo" dentro de un array. */

                            $array["status"] = "Inactive";
                        } else if ($array["status"] == 3) {
                            /* asigna "Inactive" al estado si el valor es 3. */

                            $array["status"] = "Inactive";
                        }

                        /* Inserta el contenido de `$array` al final de `$final`. */
                        array_push($final, $array);
                    }

                }
                if ($type == 2) {


                    /* Código configura variables y reglas para filtrar datos en una consulta. */
                    $MaxRows = 1000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se crean reglas de filtrado y se codifican en formato JSON. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Se obtienen logs de usuario mediante una consulta y se decodifican en JSON. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {

                        /* formatea fechas en un arreglo según el estándar ISO 8601. */
                        $array = [];
                        $fechaActual = date('Y-m-d H:i:s');
                        $fechaActual = str_replace(" ", "T", $fechaActual);

                        $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                        $array["transactionDate"] = $fecha;

                        /* asigna valores a un array basado en una transacción y su estado. */
                        $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                        $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                        $array["paidAmount"] = number_format($value->{"transapiusuario_log.valor"}, 2, '.', '');
                        $array["status"] = $value->{"transapiusuario_log.tipo"};
                        if ($array["status"] == 0) {
                            $array["status"] = "Active";
                        } else if ($array["status"] == 1) {
                            /* Convierte el estado en un texto legible si su valor es 1. */

                            $array["status"] = "Active";
                        } else if ($array["status"] == 2) {
                            /* Cambia el valor de "status" a "Inactive" si es igual a 2. */

                            $array["status"] = "Inactive";
                        } else if ($array["status"] == 3) {
                            /* cambia el estado del arreglo a "Inactive" si es igual a 3. */

                            $array["status"] = "Inactive";
                        }

                        /* Agrega el contenido de `$array` al final del arreglo `$final`. */
                        array_push($final, $array);
                    }
                }


                /* Crea un array con fecha y elementos contables formateados en un formato específico. */
                $array2 = [];
//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array2["accountingDate"] = $fechaActual;
                $array2["items"] = $final;

                /* asigna un estado de error y envía datos en una respuesta estructurada. */
                $response["error"] = 0;
                $response["data"] = array($array2);


            } elseif ($shop == '4133881') {


                /* define variables para gestionar límites y orden en filas de datos. */
                $MaxRows = 100;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se configuran reglas de filtro para una consulta utilizando condiciones específicas. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* convierte un filtro a JSON y establece la configuración regional en checo. */
                $json = json_encode($filtro);

                setlocale(LC_ALL, 'czech');


                $select = " usuario_token_interno.* ";


                /* crea un objeto, obtiene datos y los decodifica en formato JSON. */
                $UsuarioTokenInterno = new UsuarioTokenInterno();
                $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $data = json_decode($data);


                $response["error"] = 0;

                /* Se inicializa un código de respuesta y un arreglo final vacío. */
                $response["code"] = 0;


                $final = [];
                if ($type == 1) {


                    /* Crea filtros para consultas basadas en condiciones de fecha y punto de venta. */
                    $rules = [];
                    array_push($rules, array("field" => "usuario_recarga.puntoventa_id", "data" => "$shop", "op" => "eq"));
                    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));
                    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");


                    /* Convierte un filtro a JSON y obtiene datos de recargas de usuarios. */
                    $json = json_encode($filtro);

                    $select = " usuario_recarga.* ";

                    $UsuarioRecarga = new UsuarioRecarga();

                    $datos = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true);


                    /* Convierte una cadena JSON en un objeto PHP a través de la función json_decode. */
                    $datos = json_decode($datos);


                    /* itera sobre datos, construyendo un arreglo con información de recargas. */
                    foreach ($datos->data as $key => $value) {
                        $array = [];

                        $array["authorizationCode"] = $value->{"usuario_recarga.recarga_id"};

                        $fecha = str_replace(" ", "T", $value->{"usuario_recarga.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = '';
                        $array["accountId"] = $value->{"usuario_recarga.usuario_id"};
                        $array["paidAmount"] = intval($value->{"usuario_recarga.valor"});
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


                    /* valida datos de cuentas de cobro para un comercio específico. */
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


                    /* Crea reglas de filtrado para consultas basadas en ciertas condiciones. */
                    $rules = [];
                    array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "$shop", "op" => "eq"));
                    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$date" . " 00:00:00", "op" => "ge"));
                    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$date" . " 23:59:59", "op" => "le"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* convierte un filtro a JSON y define una consulta de base de datos. */
                    $json = json_encode($filtro);

                    $select = "cuenta_cobro.cuenta_id,cuenta_cobro.fecha_crea,cuenta_cobro.usuario_id,cuenta_cobro.valor,cuenta_cobro.estado";


                    $CuentaCobro = new CuentaCobro();

                    /* Transforma datos de cuentas de cobro a un formato específico y comprensible. */
                    $datos = $CuentaCobro->getCuentasCobroCustom($select, "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true);
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {
                        $array = [];
                        $array["authorizationCode"] = $value->{"cuenta_cobro.cuenta_id"};
                        $fecha = str_replace(" ", "T", $value->{"cuenta_cobro.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = '';
                        $array["accountId"] = $value->{"cuenta_cobro.usuario_id"};
                        $array["paidAmount"] = intval($value->{"cuenta_cobro.valor"});
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


                /* Crea un array con la fecha y hora actual en formato ISO 8601. */
                $array = [];

//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array["accountingDate"] = $fechaActual;

                /* Se asigna un arreglo y se estructura una respuesta sin errores. */
                $array["items"] = $final;

                $response["error"] = 0;
                $response["data"] = array($array);


            } elseif ($shop == '5446026') {


                /* Se definen variables para gestionar filas y autenticación en una tienda. */
                $MaxRows = 1;
                $OrderedItem = 1;
                $SkeepRows = 0;

                $shop = $params->shop;
                $token = $params->token;

                /* verifica si la variable $shop está vacía y lanza una excepción. */
                $type = $params->type;
                $data = $params->data;
                $date = $params->date;


                if ($shop == "") {
                    throw new Exception("Field: $shop", "50001");

                }

                /* verifica si los campos 'token' y 'type' están vacíos y genera excepciones. */
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


                /* Definición de variables para limitar y ordenar filas en un conjunto de datos. */
                $MaxRows = 10000;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* Se crean reglas de filtrado para una consulta, usando operadores y datos específicos. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* Inicializa un arreglo de respuesta con error y código cero, además de un arreglo final vacío. */
                $response["error"] = 0;
                $response["code"] = 0;

                $final = [];

                if ($type == 1) {


                    /* Código establece límites y reglas para filtrar registros en una consulta, usando PHP. */
                    $MaxRows = 1000000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se crean reglas de filtrado en un arreglo y se codifican en formato JSON. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => "0", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Se obtienen y decodifican registros de transapiusuario log en formato JSON. */
                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);


                    /* procesa datos, mapeando transacciones a un nuevo formato para su almacenamiento. */
                    foreach ($datos->data as $key => $value) {
                        $array = [];

                        $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                        $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                        $array["paidAmount"] = intval($value->{"transapiusuario_log.valor"});
                        $array["status"] = $value->{"transapiusuario_log.tipo"};
                        if ($array["status"] == 0) {
                            $array["status"] = "activo";
                        } else if ($array["status"] == 1) {
                            $array["status"] = "activo";
                        } else if ($array["status"] == 2) {
                            $array["status"] = "inactivo";
                        } else if ($array["status"] == 3) {
                            $array["status"] = "inactivo";
                        }
                        array_push($final, $array);
                    }

                }
                if ($type == 2) {


                    /* Configura límites y reglas para filtrar datos relacionados con fechas en una consulta. */
                    $MaxRows = 1000000;
                    $OrderedItem = 1;
                    $SkeepRows = 0;

                    $rules = [];
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

                    /* Se crean reglas de filtrado para consultas de base de datos en un array. */
                    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
                    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
                    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => "1", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* Convierte un filtro a JSON, obtiene registros y los decodifica. */
                    $json = json_encode($filtro);

                    $transApiUsuarioLog = new TransapiusuarioLog();
                    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {

                        /* Convierte fechas a formato ISO 8601 y las almacena en un arreglo. */
                        $array = [];
                        $fechaActual = date('Y-m-d H:i:s');
                        $fechaActual = str_replace(" ", "T", $fechaActual);

                        $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
                        $array["transactionDate"] = $fecha;

                        /* asigna valores de una transacción a un array y establece el estado. */
                        $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
                        $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
                        $array["paidAmount"] = intval($value->{"transapiusuario_log.valor"});
                        $array["status"] = $value->{"transapiusuario_log.tipo"};
                        if ($array["status"] == 0) {
                            $array["status"] = "activo";
                        } else if ($array["status"] == 1) {
                            /* cambia el estado a "activo" si es igual a 1. */

                            $array["status"] = "activo";
                        } else if ($array["status"] == 2) {
                            /* cambia el estado a "inactivo" si el valor es 2. */

                            $array["status"] = "inactivo";
                        } else if ($array["status"] == 3) {
                            /* Cambia el estado a "inactivo" si el valor actual es 3. */

                            $array["status"] = "inactivo";
                        }

                        /* Agrega el contenido de `$array` al final de `$final`. */
                        array_push($final, $array);
                    }
                }


                /* Crea un array con la fecha actual y elementos contables. */
                $array2 = [];
//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array2["accountingDate"] = $fechaActual;
                $array2["items"] = $final;

                /* Inicializa un arreglo de respuesta sin errores y agrega datos específicos. */
                $response["error"] = 0;
                $response["data"] = array($array2);
            } else {


                /* Se establecen variables para la gestión de filas y reglas en un proceso. */
                $MaxRows = 100;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $rules = [];


                /* define reglas de filtro para consultas en una base de datos. */
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
                array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* convierte un arreglo a JSON y establece la localización en checo. */
                $json = json_encode($filtro);

                setlocale(LC_ALL, 'czech');


                $select = " usuario_token_interno.* ";


                /* Se obtiene un token de usuario y se analiza en formato JSON. */
                $UsuarioTokenInterno = new UsuarioTokenInterno();
                $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $data = json_decode($data);


                $response["error"] = 0;

                /* Se inicializa un código de respuesta y un array final vacío en PHP. */
                $response["code"] = 0;


                $final = [];
                if ($type == 1) {


                    /* Se crean reglas de filtrado para consultar recargas de un usuario por fecha y tienda. */
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


                    /* Convierte una cadena JSON en un objeto o array de PHP. */
                    $datos = json_decode($datos);


                    /* Transforma datos de recargas en un array estructurado según condiciones específicas. */
                    foreach ($datos->data as $key => $value) {
                        $array = [];

                        $array["authorizationCode"] = $value->{"usuario_recarga.recarga_id"};

                        $fecha = str_replace(" ", "T", $value->{"usuario_recarga.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = '';
                        $array["accountId"] = $value->{"usuario_recarga.usuario_id"};
                        $array["paidAmount"] = intval($value->{"usuario_recarga.valor"});
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


                    /* Valida datos de cuenta de cobro según condiciones específicas de la tienda. */
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


                    /* Define reglas de filtrado para consultar datos basados en condiciones específicas. */
                    $rules = [];
                    array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "$shop", "op" => "eq"));
                    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$date" . " 00:00:00", "op" => "ge"));
                    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$date" . " 23:59:59", "op" => "le"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* Se codifica un filtro en JSON y se prepara una consulta select para cuentas de cobro. */
                    $json = json_encode($filtro);

                    $select = "cuenta_cobro.cuenta_id,cuenta_cobro.fecha_crea,cuenta_cobro.usuario_id,cuenta_cobro.valor,cuenta_cobro.estado";


                    $CuentaCobro = new CuentaCobro();

                    /* obtiene y transforma datos de cuentas de cobro en un formato específico. */
                    $datos = $CuentaCobro->getCuentasCobroCustom($select, "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true);
                    $datos = json_decode($datos);

                    foreach ($datos->data as $key => $value) {
                        $array = [];
                        $array["authorizationCode"] = $value->{"cuenta_cobro.cuenta_id"};
                        $fecha = str_replace(" ", "T", $value->{"cuenta_cobro.fecha_crea"});
                        $array["transactionDate"] = $fecha;
                        $array["transactionId"] = '';
                        $array["accountId"] = $value->{"cuenta_cobro.usuario_id"};
                        $array["paidAmount"] = intval($value->{"cuenta_cobro.valor"});
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


                /* crea un arreglo con la fecha y hora actual en formato ISO 8601. */
                $array = [];

//date_default_timezone_set('America/Bogota');
                $fechaActual = date('Y-m-d H:i:s');
                $fechaActual = str_replace(" ", "T", $fechaActual);
                $array["accountingDate"] = $fechaActual;

                /* asigna datos a un array y prepara una respuesta sin errores. */
                $array["items"] = $final;

                $response["error"] = 0;
                $response["data"] = $array;


            }


            break;

        case 'user/searchwithdraw':


            /* asigna parámetros a variables para procesar una transacción en un shop. */
            $shop = $params->shop;
            $token = $params->token;
            $nota = $params->withdrawId;
            $clave = $params->password;
            $pais = $params->country;

            $transactionId = $params->transactionId;


            /* lanza una excepción si el token está vacío. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }


            /* valida campos vacíos lanzando excepciones en caso de error. */
            if ($nota == "") {
                throw new Exception("Field: Nota", "50001");

            }

            if ($clave == "") {
                throw new Exception("Field: Clave", "50001");

            }

            /* Lanza una excepción si el campo "pais" está vacío, limitando las filas a 1. */
            if ($pais == "") {
                throw new Exception("Field: Pais", "50001");

            }

            $MaxRows = 1;

            /* Se inicializan variables para ordenar elementos y omitir filas en un conjunto de reglas. */
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


            /* Se crean reglas para filtrar usuarios en un array utilizando condiciones específicas. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro a JSON y establece la localización en checo. */
            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_token_interno.* ";


            /* Se crea un objeto y se obtiene datos en formato JSON, manejando errores. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Asigna el valor 0 a la clave "code" en el array $response. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* Valida que dos usuarios pertenezcan al mismo país; lanza excepción si no. */
                $CuentaCobro = new CuentaCobro($nota, "", $clave);
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $UsuarioPuntoVenta = new Usuario($shop);

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }

                /* Verifica la validez del país y la asociación del usuario con el mandante. */
                if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
                    throw new Exception("Código de país incorrecto", "10018");

                }
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }


                /* Verifica el estado de cuenta y el usuario antes de permitir una operación. */
                if ($CuentaCobro->getEstado() != "A") {
                    throw new Exception("No existe la nota de retiro", "12");
                }

                if ($UsuarioPuntoVenta->usuarioId != "853460") {
                    if ($CuentaCobro->getMediopagoId() == "853460") {
                        throw new Exception("No existe la nota de retiro", "12");
                    }
                }

                /* Verifica condiciones para lanzar excepción o asignar nombre de usuario a respuesta. */
                if ($UsuarioPuntoVenta->usuarioId != "2894342") {
                    if ($CuentaCobro->getMediopagoId() == "2894342") {
                        throw new Exception("No existe la nota de retiro", "12");
                    }
                }


                $response["name"] = $Usuario->nombre;

                /* Asigna la moneda y el monto a la respuesta del usuario desde objetos. */
                $response["currency"] = $Usuario->moneda;
                $response["amount"] = $CuentaCobro->getValor();


            } else {
                /* lanza una excepción por credenciales de inicio de sesión incorrectas. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;


        case 'rollback/withdraw':
//exit();

            /* asigna parámetros de entrada a variables para procesar una transacción. */
            $shop = $params->shop;
            $token = $params->token;
            $nota = $params->withdrawId;
            $clave = $params->password;
            $pais = $params->country;

            $transactionId = $params->transactionId;


            /* lanza una excepción si el token está vacío, indicando un error específico. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }


            /* verifica si 'nota' y 'clave' están vacíos, lanzando excepciones en caso afirmativo. */
            if ($nota == "") {
                throw new Exception("Field: Nota", "50001");

            }

            if ($clave == "") {
//throw new Exception("Field: Clave", "50001");

            }

            /* verifica si el país está vacío y define un límite de filas. */
            if ($pais == "") {
//throw new Exception("Field: Pais", "50001");

            }

            $MaxRows = 1;

            /* Se inicializan variables para ordenar ítems y omitir filas, junto con un array de reglas. */
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


            /* Se crean reglas de filtro para consultas basadas en condiciones específicas. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro a JSON y establece la localización en checo. */
            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_token_interno.* ";


            /* Se obtiene y decodifica un token de usuario, estableciendo respuesta sin errores. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $data = json_decode($data);


            $response["error"] = 0;

            /* Asignación del valor 0 a la clave "code" en la variable $response. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {


                /* Código que crea usuarios y gestiona transacciones y cuentas de cobro en un sistema. */
                $UsuarioPuntoVenta = new Usuario($shop);

                $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "1");

                $CuentaCobro = new CuentaCobro($TransaccionApiUsuario->getIdentificador());
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

//$UsuarioPuntoVenta = new Usuario($shop);

                /* verifica si un usuario pertenece al país del punto de venta. */
                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50005");

                }

                /* Verifica si el país y mandante del usuario son válidos. Lanza excepciones si no. */
                if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
//throw new Exception("Código de país incorrecto", "10018");

                }
                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50006");

                }

                /* Valida si el usuario tiene acceso a notas de retiro según su ID. */
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

                /* Se inicializan objetos de Usuario y PuntoVenta usando datos de CuentaCobro. */
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $valor = $CuentaCobro->getValor();

//$UsuarioPuntoVenta = new Usuario($shop);
                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);


                /* Verifica la coincidencia de país y mandante entre usuario y punto de venta. */
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


                /* valida si el pago excede el límite configurado antes de proceder. */
                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();
                $Amount = $CuentaCobro->getValor();

                $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

                if ($UsuarioConfig->maxpagoRetiro != "" && $UsuarioConfig->maxpagoRetiro != "0") {
                    if (floatval($UsuarioConfig->maxpagoRetiro) < floatval($CuentaCobro->getValor())) {
                        throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
                    }
                }


                /* Inicializa variables y objetos relacionados con la configuración y transacciones en MySQL. */
                $rowsUpdate = 0;

                $ConfigurationEnvironment = new ConfigurationEnvironment();

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();

                /* Código que establece estado y fecha de acción en un objeto CuentaCobro. */
                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);
//$CuentaCobro->setDiripCambio($dirIp);
                $CuentaCobro->setEstado('E');
                $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                /* Establece fecha de eliminación y actualiza el estado de CuentaCobro en la base de datos. */
                $CuentaCobro->setFechaEliminacion(date('Y-m-d H:i:s'));

                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='I' ");

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                /* Se inicializa un objeto FlujoCaja con fecha, hora y usuario creador. */
                $rowsUpdate = 0;

                $FlujoCaja = new FlujoCaja();
                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);

                /* Se configuran propiedades del objeto FlujoCaja basadas en  CuentaCobro. */
                $FlujoCaja->setTipomovId('E');
                $FlujoCaja->setValor($CuentaCobro->getValor());
                $FlujoCaja->setTicketId('');
                $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
                $FlujoCaja->setMandante($CuentaCobro->getMandante());
                $FlujoCaja->setTraslado('N');

                /* establece valores predeterminados para recarga y métodos de pago. */
                $FlujoCaja->setRecargaId(0);

                if ($FlujoCaja->getFormapago1Id() == "") {
                    $FlujoCaja->setFormapago1Id(0);
                }

                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }


                /* asigna valor cero si los atributos están vacíos. */
                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }

                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }


                /* Asigna valores predeterminados a propiedades vacías en el objeto FlujoCaja. */
                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }

                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }


                /* Establece valor IVA a cero si está vacío y crea un objeto DAO para el flujo de caja. */
                if ($FlujoCaja->getValorIva() == "") {
                    $FlujoCaja->setValorIva(0);
                }
                $FlujoCaja->setDevolucion('');

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

                /* Inserta datos en la base y lanza error si no se afecta ninguna fila. */
                $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                $rowsUpdate = 0;


                /* Actualiza el balance de créditos restando el valor de la cuenta de cobro. */
                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$CuentaCobro->getValor(), $Transaction);


                if ($rowsUpdate > 0) {


                    /* crea un objeto de transacción y establece sus propiedades relacionadas con usuarios. */
                    $TransaccionApiUsuario = new TransaccionApiUsuario();

                    $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                    $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                    $TransaccionApiUsuario->setValor($valor);
                    $TransaccionApiUsuario->setTipo(1);

                    /* configura propiedades de una transacción API, incluyendo valores y estado. */
                    $TransaccionApiUsuario->setTValue(json_encode($params));
                    $TransaccionApiUsuario->setRespuestaCodigo("OK");
                    $TransaccionApiUsuario->setRespuesta("OK");
                    $TransaccionApiUsuario->setTransaccionId($transactionId);

                    $TransaccionApiUsuario->setUsucreaId(0);

                    /* registra una transacción y crea un log correspondiente en la base de datos. */
                    $TransaccionApiUsuario->setUsumodifId(0);

                    $TransaccionApiUsuario->setIdentificador($CuentaCobro->getCuentaId());
                    $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                    $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                    $TransapiusuarioLog = new TransapiusuarioLog();


                    /* Configura un registro de transacción con detalles específicos del usuario y la cuenta. */
                    $TransapiusuarioLog->setIdentificador($CuentaCobro->getCuentaId());
                    $TransapiusuarioLog->setTransaccionId($transactionId);
                    $TransapiusuarioLog->setTValue(json_encode($params));
                    $TransapiusuarioLog->setTipo(2);
                    $TransapiusuarioLog->setValor($Amount);
                    $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

                    /* Se configuran IDs para el registro de usuario en la base de datos. */
                    $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                    $TransapiusuarioLog->setUsucreaId(0);
                    $TransapiusuarioLog->setUsumodifId(0);


                    $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

                    /* Código inserta un registro en base de datos y actualiza historial de usuario. */
                    $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


                    $Usuario->creditWin($CuentaCobro->getValor(), $Transaction);

                    $UsuarioHistorial = new UsuarioHistorial();

                    /* Se establece un historial de usuario con información específica en variables. */
                    $UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(40);

                    /* Se establece un historial de usuario en la base de datos con datos de cuenta. */
                    $UsuarioHistorial->setValor($CuentaCobro->getValor());
                    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                    $UsuarioHistorial = new UsuarioHistorial();

                    /* Configuración de datos para un historial de usuario en un sistema. */
                    $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('S');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(40);

                    /* Se guarda información del usuario y se confirma la transacción en la base de datos. */
                    $UsuarioHistorial->setValor($CuentaCobro->getValor());
                    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

//$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
//$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


                    $Transaction->commit();
                } else {
                    /* lanza una excepción de error general con un código específico. */

                    throw new Exception("Error General", "100000");
                }


                /* asigna valores de usuario y cuenta a un array de respuesta. */
                $response["name"] = $Usuario->nombre;
                $response["currency"] = $Usuario->moneda;
                $response["amount"] = $CuentaCobro->getValor();


            } else {
                /* Lanza una excepción si los datos de inicio de sesión son incorrectos. */

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

            /* lanza excepciones si los campos 'token' o 'amount' están vacíos. */
            if ($token == "") {
                throw new Exception("Field: Key", "50001");

            }

            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");

            }

            /* Verifica si transactionId está vacío y lanza una excepción si lo está. */
            if ($transactionId == "") {
                throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;

            /* Se inicializan variables para contar elementos y saltar filas en un conjunto de reglas. */
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


            /* Se construye un filtro de reglas para una consulta basada en condiciones. */
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
            array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* convierte un filtro a formato JSON y define una consulta SQL. */
            $json = json_encode($filtro);

//setlocale(LC_ALL, 'czech');


            $select = " usuario.mandante,usuario_token_interno.* ";


            /* Se crea un objeto y se obtiene datos de usuario en formato JSON, sin errores. */
            $UsuarioTokenInterno = new UsuarioTokenInterno();
            $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $data = json_decode($data);


            $response["error"] = 0;

            /* asigna el valor cero a la clave "code" en la respuesta. */
            $response["code"] = 0;


            if (oldCount($data->data) > 0) {
//print_r(time());


                /* Se crea un usuario y una transacción, luego se actualiza el consecutivo de recarga. */
                $UsuarioPuntoVenta = new Usuario($shop);

                $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "0");

                /**
                 * Actualizamos consecutivo Recarga
                 */

                $UsuarioRecarga = new UsuarioRecarga($TransaccionApiUsuario->getIdentificador());


                /* verifica y establece el estado de una recarga como eliminada. */
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

                /* Se obtiene el valor y usuario, luego se crea un flujo de caja con fecha actual. */
                $valor = $UsuarioRecarga->getValor();

                $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

                $FlujoCaja = new FlujoCaja();
                $FlujoCaja->setFechaCrea(date('Y-m-d'));

                /* establece propiedades para un objeto FlujoCaja, incluyendo hora, usuario y valores. */
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($puntoventa_id);
                $FlujoCaja->setTipomovId('S');
                $FlujoCaja->setValor($valor);
                $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

                /* establece una devolución y asigna identificadores de forma de pago si están vacíos. */
                $FlujoCaja->setDevolucion('S');

                if ($FlujoCaja->getFormapago1Id() == "") {
                    $FlujoCaja->setFormapago1Id(0);
                }

                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }


                /* asigna un valor de cero si los valores son vacíos. */
                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }

                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }


                /* Verifica valores vacíos y establece valores predeterminados en un objeto FlujoCaja. */
                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }

                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }


                /* verifica el IVA y lo establece en cero si está vacío antes de insertar. */
                if ($FlujoCaja->getValorIva() == "") {
                    $FlujoCaja->setValorIva(0);
                }

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                $FlujoCajaMySqlDAO->insert($FlujoCaja);
//print_r(time());


                /* actualiza el saldo de créditos y verifica disponibilidad en un punto de venta. */
                $PuntoVenta = new PuntoVenta("", $puntoventa_id);


                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100000");
                }

//print_r(time());


                /* Se crea un objeto para ajustar el saldo de un usuario en línea. */
                $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                $SaldoUsuonlineAjuste->setTipoId('S');
                $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                $SaldoUsuonlineAjuste->setValor($valor);
                $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));

                /* Se ajusta el saldo y se establece motivo para la reversión de recarga. */
                $SaldoUsuonlineAjuste->setUsucreaId($UsuarioPuntoVenta->usuarioId);
                $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                $SaldoUsuonlineAjuste->setObserv("Reversion recarga API " . $UsuarioRecarga->getRecargaId());
                if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                    $SaldoUsuonlineAjuste->setMotivoId(0);
                }

                /* obtiene la IP del usuario y ajusta su estado a 'Inactivo'. */
                $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());

                $UsuarioRecarga->setEstado('I');

                /* Actualiza la fecha y usuario de eliminación de un objeto UsuarioRecarga en base de datos. */
                $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
                $UsuarioRecarga->setUsueliminaId(0);

                $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);

                $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);


                /* Código que gestiona ajustes de saldo de usuario en una base de datos MySQL. */
                $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);

//print_r(time());


                $Usuario->debit($UsuarioRecarga->getValor(), $Transaction);


                /* Se crea un historial de usuario con detalles de movimiento y usuarios asociados. */
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('S');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);

                /* Registro historial de usuario en la base de datos tras una recarga. */
                $UsuarioHistorial->setTipo(10);
                $UsuarioHistorial->setValor($valor);
                $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

//print_r(time());


                /* Se crea un nuevo registro de historial de usuario con datos iniciales. */
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($puntoventa_id);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);

                /* Se configuran valores para un historial de usuario y se prepara una transacción API. */
                $UsuarioHistorial->setTipo(10);
                $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

//$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
//$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

//print_r(time());

                $TransaccionApiUsuario = new TransaccionApiUsuario();


                /* configura una transacción API con datos de usuario y respuesta. */
                $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransaccionApiUsuario->setValor(($amount));
                $TransaccionApiUsuario->setTipo(0);
                $TransaccionApiUsuario->setTValue(json_encode($params));
                $TransaccionApiUsuario->setRespuestaCodigo("OK");

                /* Configuración de respuestas y transacciones en un objeto de API de usuario. */
                $TransaccionApiUsuario->setRespuesta("OK");
                $TransaccionApiUsuario->setTransaccionId($transactionId);

                $TransaccionApiUsuario->setUsucreaId(0);
                $TransaccionApiUsuario->setUsumodifId(0);


                $TransaccionApiUsuario->setIdentificador($UsuarioRecarga->getRecargaId());

                /* Se crea un objeto DAO para insertar una transacción en la base de datos. */
                $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

//print_r(time());

                $TransapiusuarioLog = new TransapiusuarioLog();


                /* registra información de transacción de usuario en un sistema. */
                $TransapiusuarioLog->setIdentificador($UsuarioRecarga->getRecargaId());
                $TransapiusuarioLog->setTransaccionId($transactionId);
                $TransapiusuarioLog->setTValue(json_encode($params));
                $TransapiusuarioLog->setTipo(3);
                $TransapiusuarioLog->setValor($amount);
                $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

                /* Se configura un registro de log de usuario con datos específicos en MySQL. */
                $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                $TransapiusuarioLog->setUsucreaId(0);
                $TransapiusuarioLog->setUsumodifId(0);


                $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

                /* Inserta un registro de usuario, confirma la transacción y devuelve el ID generado. */
                $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

//print_r(time());

                $Transaction->commit();


                $response["transactionId"] = $Transapiusuariolog_id;

                /* Asigna el ID de recarga del usuario a la respuesta de la transacción. */
                $response["depositId"] = $UsuarioRecarga->getRecargaId();


            } else {
                /* lanza una excepción por datos de inicio de sesión incorrectos. */

                throw new Exception("Datos de login incorrectos", "50003");

            }


            break;

        case 'coupons/gt':
//print_r(time());

//exit();


            /* extrae y valida parámetros, lanzando excepción si 'key' está vacío. */
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

            /* lanza excepciones si los campos 'value' o 'mandante' están vacíos. */
            if ($value == "") {
                throw new Exception("Field: value empty", "2");

            }
            if ($mandante == "") {
                throw new Exception("Field: parnet empty", "2");

            }

            /* Verifica si el país está vacío o incorrecto, lanzando excepciones en caso afirmativo. */
            if ($country == "") {
                throw new Exception("Field: country empty", "2");

            }

            if ($country != 60) {
                throw new Exception("Código de país incorrecto", "10018");
            }


            /* Se crea una instancia de TransaccionProducto y se inicializan variables para manejo de datos. */
            $TransaccionProducto = new TransaccionProducto();

            $SkeepRows = 0;
            $MaxRows = 1000000;
            $array = array();
            $SecretKey = "MihPXlw2eCX%WGa3";

            /* Código para crear un proveedor, configurar ambiente y un producto con clave secreta. */
            $Proveedor = new Proveedor("", "GT");
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Producto = new Producto("", "CUPONESGT", $Proveedor->proveedorId);

            if ($ConfigurationEnvironment->isProduction()) {
                $SecretKey = 'LVduxWEkNKbR3BUGnh2cgJ9FBqMRnFHG';
            }
            if ($key == $SecretKey) {


                /* inicia una transacción de producto en MySQL con datos específicos. */
                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
                $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

                $TransaccionProducto = new TransaccionProducto();
                $TransaccionProducto->setProductoId($Producto->productoId);
                $TransaccionProducto->setUsuarioId(0);

                /* Se configuran propiedades de un objeto `TransaccionProducto` con valores específicos. */
                $TransaccionProducto->setValor($value);
                $TransaccionProducto->setEstado('A');
                $TransaccionProducto->setTipo('T');
                $TransaccionProducto->setExternoId(0);
                $TransaccionProducto->setEstadoProducto('P');
                $TransaccionProducto->setMandante($mandante);

                /* Inserta un producto en la transacción y genera un código de cupón cifrado. */
                $TransaccionProducto->setFinalId(0);
                $TransaccionProducto->setFinalId(0);
                $TransaccionProducto->setUsutarjetacredId(0);

                $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

                $codigoCupon = $ConfigurationEnvironment->encryptCusNum(intval($transproductoId));


                /* Añade un cupón a un array y registra un nuevo estado en TransprodLog. */
                array_push($array, $codigoCupon);

                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('P');
                $TransprodLog->setTipoGenera('A');

                /* configura un registro de log de transacciones en la base de datos. */
                $TransprodLog->setComentario('Cupon generado por ' . $transproductoId);
                $TransprodLog->setTValue("");
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);

                /* Se inserta un registro y se confirma la transacción, retornando el ID generado. */
                $TransprodlogId = $TransprodLogMySqlDAO->insert($TransprodLog);

                $Transaction->commit();


                $response["transactionId"] = $TransprodlogId;

                /* Asigna el contenido de $array a la clave "coupon" en el arreglo $response. */
                $response["coupon"] = $array;


            } else {
                /* Lanza una excepción si la clave proporcionada es inválida. */

                throw new Exception("Key invalida", "1");

            }


            break;

        default:
# code...
            break;
    }
} catch (Exception $e) {

    /* intenta revertir una transacción si existe; evita errores en operaciones. */
    try {

        if ($Transaction != null) {
            $Transaction->rollback();
        }
    } catch (Exception $e) {
        /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


    }

    /* Registro de errores y depuración condicional basado en la solicitud del usuario. */
    if ($_REQUEST['isDebug'] == '1') {
        print_r($e);
    }
    syslog(LOG_WARNING, "ERRORAPIOPERATOR :" . $e->getCode() . ' - ' . $e->getMessage() . json_encode($params) . json_encode($_SERVER) . json_encode($_REQUEST));

    $code = $e->getCode();


    /* Inicializa variables y captura mensajes de error en un arreglo de respuesta. */
    $codeProveedor = "";
    $messageProveedor = "";
    $message = $e->getMessage();

    $response = array();

    switch ($code) {

        case 50003:
            /* Código de error para credenciales incorrectas asigna un mensaje específico al proveedor. */

            $codeProveedor = "102";  //credenciales incorrectas
            $messageProveedor = $message;
            break;

        case 50001:
            /* Asignación de un código y mensaje para el caso 50001 en un switch. */

            $codeProveedor = "100";  //campos vacios
            $messageProveedor = $message;
            break;

        case 50005:
            /* Asignación de código y mensaje para un usuario que no pertenece al país. */

            $codeProveedor = "101"; //Usuario no pertence al pais
            $messageProveedor = $message;
            break;
        case 50006:
            /* Código asigna un mensaje específico si el usuario no es parte del partner. */

            $codeProveedor = "101";  //usuario no pertenece al partner
            $messageProveedor = $message;
            break;

        case 50007:
            /* Asignar código y mensaje del proveedor si el caso es 50007. */

            $codeProveedor = "106";     //nota de retirno no esta activa
            $messageProveedor = $message;
            break;

        case 50008:
            /* gestiona un caso específico donde no se puede eliminar una nota de retiro. */

            $codeProveedor = "9"; //nota de retiro no puede ser eliminada
            $messageProveedor = $message;
            break;

        case 50009:
            /* maneja el caso 50009 asignando un mensaje para nota de retiro eliminada. */

            $codeProveedor = "9"; //nota de retiro ya eliminada
            $messageProveedor = $message;
            break;

        case 10018:
            /* Caso 10018 asigna un código de proveedor y mensaje por código de país incorrecto. */

            $codeProveedor = "100"; //Codigo de pais incorrecto
            $messageProveedor = $message;
            break;

        case 10001:
            /* asigna un mensaje a un proveedor para transacciones ya procesadas. */

            $codeProveedor = "6"; //Transacción ya procesada
            $messageProveedor = $message;
            break;

        case 100000:
            /* asigna un código y mensaje de error para un caso específico. */

            $codeProveedor = "9"; //error general
            $messageProveedor = $message;
            break;
        case 100031:
            /* maneja un caso donde no se permite pagar una nota de retiro. */

            $codeProveedor = "9"; //No se puede pagar nota de retiro
            $messageProveedor = $message;
            break;
        case 12:
            /* Se define un caso en un switch que asigna valores a variables específicas. */

            $codeProveedor = '10';
            $messageProveedor = 'No Existe la nota de retiro';

            break;
        case 24:
            /* verifica si un proveedor existe; si no, muestra un mensaje de error. */

            $codeProveedor = "101"; //no existe el usuario
            $messageProveedor = 'No existe el usuario';

            break;

        case 10:
            /* maneja un caso de error por clave incorrecta con un mensaje específico. */

            $codeProveedor = "100"; // key incorrecta
            $messageProveedor = 'Key incorrecta';

            break;

        case 20001:
            /* maneja un error por fondos insuficientes para un usuario específico. */

            $codeProveedor = "20001"; // key incorrecta
            $messageProveedor = 'El Usuario no tiene fondos suficientes para hacer este movimiento';

            break;

        case 87:
            /* Código indica que la transacción no se encuentra, asignando un mensaje correspondiente. */

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

            /* Se establece un código de proveedor y se construye un mensaje de error general. */
            $codeProveedor = '9';
            $messageProveedor = 'Error General (' . ($e->getCode()) . ')';

            break;
    }



    /* asigna un estado de error y mensajes a una respuesta estructurada. */
    $response["error"] = 1;
    $response["code"] = $codeProveedor;
    $response["message"] = $messageProveedor;
}



/* registra y muestra respuestas JSON si no están vacías. */
if (json_encode($response) != "[]") {


    $log = "\r\n" . "------------RESPONSE------------- " . $time . "\r\n";
    $log = $log . date('Y-m-d H:i:s');
    $log = $log . $URI;
    $log = $log . json_encode($response);

    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


    print_r(json_encode($response));

}


/**
 * Convierte una cantidad de una moneda a otra.
 *
 * @param string $from_Currency Código de la moneda de origen (por ejemplo, USD).
 * @param string $to_Currency Código de la moneda de destino (por ejemplo, EUR).
 * @param float $amount Cantidad a convertir.
 * @return float Cantidad convertida a la moneda de destino.
 */
function currencyConverter($from_Currency, $to_Currency, $amount)
{
    // Si las monedas son iguales, no se realiza conversión.
    if ($from_Currency == $to_Currency) {
        return $amount;
    }

    global $currencies_valor;
    $convertido = -1;
    $bool = false;

    // Busca si ya existe un valor de conversión almacenado en $currencies_valor.
    foreach ($currencies_valor as $key => $valor) {
        if ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = $amount * $valor;
            $bool = true;
        } elseif ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = ($amount) / $valor;
            $bool = true;
        }
    }

    // Si no se encuentra un valor de conversión, realiza una solicitud a la API.
    if (!$bool) {
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);
        $encode_amount = 1;

        // Llama a la API externa para obtener la tasa de conversión.
        $rawdata = file_get_contents("http://api.currencies.zone/v1/quotes/$from_Currency/$to_Currency/json?quantity=$encode_amount&key=44|YSqBgkAbvbGfenoxx62OaSnoD~rF8rw~");
        if ($_SESSION["usuario2"] == 5) {
        }
        // Decodifica la respuesta de la API y almacena la tasa de conversión.
        $rawdata = json_decode($rawdata);
        $currencies_valor += [$from_Currency . "" . $to_Currency => $rawdata->result->amount];

        // Calcula la cantidad convertida.
        $convertido = $amount * $rawdata->result->amount;
    }

    return $convertido;
}
/**
 * Obtiene una lista de eventos deportivos filtrados por deporte, región, competencia y rango de fechas.
 *
 * @param int $sport ID del deporte a filtrar.
 * @param int $region ID de la región a filtrar.
 * @param int $competition ID de la competencia a filtrar.
 * @param string $fecha_inicial Fecha inicial del rango en formato 'YYYY-MM-DD'.
 * @param string $fecha_final Fecha final del rango en formato 'YYYY-MM-DD'.
 * @return array Lista de eventos que coinciden con los filtros, cada uno con su ID y nombre.
 */
function getMatches($sport, $region, $competition, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    // Obtiene los datos en formato JSON desde la URL proporcionada.
    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata); // Convierte los datos a XML (no utilizado en este caso).
    $datos = json_decode($rawdata); // Decodifica los datos JSON.
    $array = array(); // Inicializa el array para almacenar los eventos.

    // Itera sobre los datos decodificados para filtrar los eventos según los parámetros.
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {
                        if ($item3->ChampionshipId == $competition) {
                            foreach ($item3->Events as $item4) {
                                // Agrega los datos del evento al array de resultados.
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

    // Retorna el array con los eventos filtrados.
    return $array;
}

/**
 * Genera una clave aleatoria alfanumérica de una longitud específica.
 *
 * @param int $length Longitud de la clave a generar.
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
 * @param int $length Longitud de la clave a generar.
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
 * Obtiene la dirección IP del cliente.
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
 * Elimina duplicados de un array multidimensional basado en una clave específica.
 *
 * @param array $array Array multidimensional a procesar.
 * @param string $key Clave por la cual se eliminarán los duplicados.
 * @return array Array sin duplicados.
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
 * Quita tildes y caracteres especiales de una cadena.
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
 * @return string Cadena cifrada.
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
 * @param string $data Cadena cifrada.
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
 * Obtiene la dirección IP del cliente desde las variables de entorno.
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
 * Convierte una dirección IPv6 a IPv4 si es posible.
 *
 * @param string $ipv6 Dirección IPv6 a convertir.
 * @return string Dirección IPv4 convertida o una cadena vacía si no es posible.
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
        $ipv4Addr[0] = chr(ord($ipv4Addr[0]) | 240); // Espacio de Clase E
    }
    $ipv4 = inet_ntop($ipv4Addr);
    return $ipv4;
}


