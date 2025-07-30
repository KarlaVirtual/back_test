<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\AuditoriaGeneral;
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
use Backend\dto\SitioTracking;
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
use Backend\mysql\AuditoriaGeneralMySqlDAO;
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
 * Actualizar la seguridad de un usuario.
 *
 * Este script permite realizar diversas acciones relacionadas con la seguridad de un usuario,
 * como generar contraseñas, desbloquear usuarios, actualizar estados, gestionar contingencias,
 * y enviar notificaciones, entre otras.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params->Id Identificador del cliente.
 * @param int $params->startDate Fecha de inicio en formato timestamp.
 * @param int $params->endDate Fecha de fin en formato timestamp.
 * @param string $params->Note Nota asociada a la acción.
 * @param string $params->IsActivate Estado de activación ("A" para activo, "I" para inactivo).
 * @param string $params->Action Acción a realizar (e.g., "GeneratePassword", "UnlockUserByPassword").
 * @param string $params->IP Dirección IP del usuario (opcional, dependiendo de la acción).
 * 
 * 
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ("success", "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo (si aplica).
 *  - Data (mixed): Datos adicionales (e.g., contraseña generada).
 *
 * @throws Exception Puede lanzar excepciones en diversos puntos del script, como:
 *  - Al generar contraseñas.
 *  - Al interactuar con la base de datos.
 *  - Al enviar correos o mensajes de texto.
 *  - Al gestionar configuraciones de usuario.
 */


/* captura parámetros y la dirección IP del cliente en PHP. */
$ClientId = intval($params->Id);
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];
$starDate = $params->startDate;
$endDate = $params->endDate;
$Note = $params->Note;


/* formatea fechas, dejando vacía la fecha de fin si son iguales. */
if ($starDate == $endDate) {
    $endDate = "";
}

$startDateFormatted = date("Y-m-d H:i:s", $starDate / 1000);

if ($endDate != "") {
    $endDateFormatted = date("Y-m-d H:i:s", $endDate / 1000);
}


if ($ClientId > 0) {

    /* Asignación de estado de activación y acción desde parámetros, inicializando cambios en falso. */
    $IsActivate = ($params->IsActivate == "I") ? "I" : "A";
    $Action = $params->Action;
    $cambios = false;

    if ($Action === 'GeneratePassword') {
        try {


            /* Se crean instancias de Usuario y GoogleAuthenticator utilizando un ClientId específico. */
            $Usuario = new Usuario($ClientId);
            $Google = new GoogleAuthenticator();

            if ($Usuario->tokenGoogle == 'I') {


                /* Se crea un objeto UsuarioLog y se establecen sus propiedades con datos del usuario. */
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($ClientId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $UsuarioLog->setUsuariosolicitaIp($ip);


                /* Registra cambios en el token de Google del usuario en el sistema. */
                $UsuarioLog->setTipo("TOKENGOOGLEUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->tokenGoogle);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


                /* Se inserta un registro de usuario y se genera un token y secreto de Google. */
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();

                $Usuario->tokenGoogle = "A";


                if ($Usuario->saltGoogle == "") {
                    $Usuario->saltGoogle = $Google->createSecret();
                }


            }


            /* Código asigna valores a un objeto de usuario y marca cambios para guardar. */
            $Usuario->estado = "A";
            $Usuario->estadoEsp = "A";
            $Usuario->intentos = 0;


//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


            /* Genera una contraseña aleatoria y la asigna a un usuario. */
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            $newPassword = substr(base64_encode(bin2hex(openssl_random_pseudo_bytes(16))), 0, 12);

            $Usuario->changeClave($newPassword);

            $UsuarioLog = new UsuarioLog();

            /* Código que registra un cambio de clave de email y almacenamiento de datos en un log. */
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioaprobarIp('');
            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);
            $UsuarioLog->setTipo('CAMBIOCLAVEEMAIL');
            $UsuarioLog->setEstado('P');

            /* Se configuran valores y usuario para un registro de log en la base de datos. */
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* inserta un log de usuario y define el asunto según el idioma. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

            $cambios = true;

            switch ($Usuario->idioma) {
                case 'EN':
                    $subject = 'Key reset';
                    break;
                case 'PT':
                    $subject = 'Redefinição de chave';
                    break;
                default:
                    $subject = 'Restablecimiento de clave';
                    break;
            }


            $html = '<!doctype html> <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"> <head> <title></title> <!--[if !mso]><!--> <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!--<![endif]--> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <style type="text/css"> #outlook a{padding:0;}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;}table,td{border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;}img{border:0;height:auto;line-height:100%;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;}p{display:block;margin:0;} </style> <!--[if mso]> <noscript><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript> <![endif]--> <!--[if lte mso 11]> <style type="text/css"> .ogf{width:100% !important;} </style> <![endif]--> <!--[if !mso]><!--> <link href="https://fonts.googleapis.com/css?family=Poppins:700,500,600,400" rel="stylesheet" type="text/css"> <link href="https://fonts.googleapis.com/css?family=Inter:700" rel="stylesheet" type="text/css"> <style type="text/css"> </style> <!--<![endif]--> <style type="text/css"> @media only screen and (min-width:599px){.xc568{width:568px!important;max-width:568px;}.pc100{width:100%!important;max-width:100%;}.pc19-8732{width:19.8732%!important;max-width:19.8732%;}.pc2-537{width:2.537%!important;max-width:2.537%;}.pc77-5899{width:77.5899%!important;max-width:77.5899%;}.xc536{width:536px!important;max-width:536px;}} </style> <style media="screen and (min-width:599px)">.moz-text-html .xc568{width:568px!important;max-width:568px;}.moz-text-html .pc100{width:100%!important;max-width:100%;}.moz-text-html .pc19-8732{width:19.8732%!important;max-width:19.8732%;}.moz-text-html .pc2-537{width:2.537%!important;max-width:2.537%;}.moz-text-html .pc77-5899{width:77.5899%!important;max-width:77.5899%;}.moz-text-html .xc536{width:536px!important;max-width:536px;} </style> <style type="text/css"> @media only screen and (max-width:598px){table.fwm{width:100%!important;}td.fwm{width:auto!important;}} </style> <style type="text/css"> u+.emailify .gs{background:#000;mix-blend-mode:screen;display:inline-block;padding:0;margin:0;}u+.emailify .gd{background:#000;mix-blend-mode:difference;display:inline-block;padding:0;margin:0;}p{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;}u+.emailify a{color:inherit!important;text-decoration:none!important;}#MessageViewBody a{color:inherit!important;text-decoration:none!important;}td.b .klaviyo-image-block{display:inline;vertical-align:middle;} @media only screen and (max-width:599px){.emailify{height:100%!important;margin:0!important;padding:0!important;width:100%!important;}u+.emailify .glist{margin-left:1em!important;}td.ico.v>div.il>a.l.m,td.ico.v .mn-label{padding-right:0!important;padding-bottom:16px!important;}td.x{padding-left:0!important;padding-right:0!important;}.fwm img{max-width:100%!important;height:auto!important;}.aw img{width:auto!important;margin-left:auto!important;margin-right:auto!important;}.awl img{width:auto!important;margin-right:auto!important;}.awr img{width:auto!important;margin-left:auto!important;}.ah img{height:auto!important;}td.b.nw>table,td.b.nw a{width:auto!important;}td.stk{border:0!important;}td.u{height:auto!important;}br.sb{display:none!important;}.thd-1 .i-thumbnail{display:inline-block!important;height:auto!important;overflow:hidden!important;}.hd-1{display:block!important;height:auto!important;overflow:visible!important;}.hm-1{display:none!important;max-width:0!important;max-height:0!important;overflow:hidden!important;mso-hide:all!important;}.ht-1{display:table!important;height:auto!important;overflow:visible!important;}.hr-1{display:table-row!important;height:auto!important;overflow:visible!important;}.hc-1{display:table-cell!important;height:auto!important;overflow:visible!important;}div.r.pr-16>table>tbody>tr>td,div.r.pr-16>div>table>tbody>tr>td{padding-right:16px!important}div.r.pl-16>table>tbody>tr>td,div.r.pl-16>div>table>tbody>tr>td{padding-left:16px!important}} @media (prefers-color-scheme:light) and (max-width:599px){.ds-1.hd-1{display:none!important;height:0!important;overflow:hidden!important;}} @media (prefers-color-scheme:dark) and (max-width:599px){.ds-1.hd-1{display:block!important;height:auto!important;overflow:visible!important;}} </style> <meta name="color-scheme" content="light dark"> <meta name="supported-color-schemes" content="light dark"> <!--[if gte mso 9]> <style>a:link,span.MsoHyperlink{mso-style-priority:99;color:inherit;text-decoration:none;}a:visited,span.MsoHyperlinkFollowed{mso-style-priority:99;color:inherit;text-decoration:none;}li{text-indent:-1em;}table,td,p,div,span,ul,ol,li,a{mso-hyphenate:none;}sup,sub{font-size:100% !important;} </style> <![endif]--> </head> <body lang="en" link="#DD0000" vlink="#DD0000" class="emailify" style="mso-line-height-rule:exactly;mso-hyphenate:none;word-spacing:normal;background-color:#1e1e1e;"><div class="bg" style="background-color:#1e1e1e;" lang="en"> <!--[if mso | IE]> <table align="center" border="0" cellpadding="0" cellspacing="0" class="r-outlook -outlook pr-16-outlook pl-16-outlook -outlook" role="presentation" style="width:600px;" width="600"><tr><td style="line-height:0;font-size:0;mso-line-height-rule:exactly;"> <![endif]--><div class="r  pr-16 pl-16" style="background:#4c4c4c;background-color:#4c4c4c;margin:0px auto;max-width:600px;"> <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#4c4c4c;background-color:#4c4c4c;width:100%;"><tbody><tr><td style="border:none;direction:ltr;font-size:0;padding:16px 16px 16px 16px;text-align:left;"> <!--[if mso | IE]> <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="c-outlook -outlook -outlook" style="vertical-align:middle;width:568px;"> <![endif]--><div class="xc568 ogf c" style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border:none;vertical-align:middle;" width="100%"><tbody><tr><td align="center" class="c" style="font-size:0;padding:0;word-break:break-word;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0;"><tbody><tr><td style="width:211px;"> <img alt src="https://images.virtualsoft.tech/m/msj0212T1703269212.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" title width="211" height="auto"> </td></tr></tbody></table> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <![endif]--> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <table align="center" border="0" cellpadding="0" cellspacing="0" class="r-outlook -outlook pr-16-outlook pl-16-outlook -outlook" role="presentation" style="width:600px;" width="600"><tr><td style="line-height:0;font-size:0;mso-line-height-rule:exactly;"> <![endif]--><div class="r  pr-16 pl-16" style="background:#4c4c4c;background-color:#4c4c4c;margin:0px auto;max-width:600px;"> <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#4c4c4c;background-color:#4c4c4c;width:100%;"><tbody><tr><td style="border:none;direction:ltr;font-size:0;padding:16px 80px 0px 47px;text-align:left;"> <!--[if mso | IE]> <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="width:473px;"> <![endif]--><div class="pc100 ogf" style="font-size:0;line-height:0;text-align:left;display:inline-block;width:100%;direction:ltr;"> <!--[if mso | IE]> <table border="0" cellpadding="0" cellspacing="0" role="presentation"><tr><td style="vertical-align:middle;width:94px;"> <![endif]--><div class="pc19-8732 ogf m c" style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:19.8732%;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border:none;vertical-align:middle;" width="100%"><tbody><tr><td align="left" class="i" style="font-size:0;padding:0;word-break:break-word;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0;"><tbody><tr><td style="width:94px;"> <img alt src="https://images.virtualsoft.tech/m/msj0212T1703269168.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" title width="94" height="auto"> </td></tr></tbody></table> </td></tr></tbody></table></div> <!--[if mso | IE]> </td><td style="width:12px;"> <![endif]--><div class="pc2-537 ogf g" style="font-size:0;text-align:left;direction:ltr;display:inline-block;width:2.5370%;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody><tr><td style="padding:0;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style width="100%"><tbody></tbody></table> </td></tr></tbody></table></div> <!--[if mso | IE]> </td><td style="vertical-align:middle;width:367px;"> <![endif]--><div class="pc77-5899 ogf c" style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:77.5899%;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border:none;vertical-align:middle;" width="100%"><tbody><tr><td align="center" class="x" style="font-size:0;word-break:break-word;"><div style="text-align:center;"><p style="Margin:0;text-align:center;mso-line-height-alt:30px;mso-ansi-font-size:20px;"><span style="font-size:20px;font-family:\'Poppins\',\'Arial\',sans-serif;font-weight:700;color:#ffffff;line-height:150%;mso-line-height-alt:30px;mso-ansi-font-size:20px;">&iexcl;Restablecimiento de clave!</span></p></div> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <![endif]--></div> <!--[if mso | IE]> </td></tr></table> <![endif]--> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <table align="center" border="0" cellpadding="0" cellspacing="0" class="r-outlook -outlook pr-16-outlook pl-16-outlook -outlook" role="presentation" style="width:600px;" width="600"><tr><td style="line-height:0;font-size:0;mso-line-height-rule:exactly;"> <![endif]--><div class="r  pr-16 pl-16" style="background:#4c4c4c;background-color:#4c4c4c;margin:0px auto;max-width:600px;"> <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#4c4c4c;background-color:#4c4c4c;width:100%;"><tbody><tr><td style="border:none;direction:ltr;font-size:0;padding:32px 32px 32px 32px;text-align:left;"> <!--[if mso | IE]> <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="c-outlook -outlook -outlook" style="vertical-align:middle;width:536px;"> <![endif]--><div class="xc536 ogf c" style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border:none;vertical-align:middle;" width="100%"><tbody><tr><td align="center" class="x" style="font-size:0;word-break:break-word;"><div style="text-align:center;"><p style="Margin:0;text-align:center;mso-line-height-alt:22px;mso-ansi-font-size:18px;"><span style="font-size:18px;font-family:\'Poppins\',\'Arial\',sans-serif;font-weight:500;color:#ffffff;line-height:117%;mso-line-height-alt:22px;mso-ansi-font-size:18px;">Recientemente se realizo un cambio de contrase&ntilde;a, la clave es la siguiente:</span></p></div> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <![endif]--> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <table align="center" border="0" cellpadding="0" cellspacing="0" class="r-outlook -outlook pr-16-outlook pl-16-outlook -outlook" role="presentation" style="width:600px;" width="600"><tr><td style="line-height:0;font-size:0;mso-line-height-rule:exactly;"> <![endif]--><div class="r  pr-16 pl-16" style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;"> <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fffffe;background-color:#fffffe;width:100%;"><tbody><tr><td style="border:none;direction:ltr;font-size:0;padding:32px 32px 0px 32px;text-align:left;"> <!--[if mso | IE]> <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="c-outlook -outlook -outlook" style="vertical-align:middle;width:536px;"> <![endif]--><div class="xc536 ogf c" style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border:none;vertical-align:middle;" width="100%"><tbody><tr><td align="center" class="x" style="font-size:0;word-break:break-word;"><div style="text-align:center;"><p style="Margin:0;text-align:center;mso-line-height-alt:38px;mso-ansi-font-size:26px;"><span style="font-size:25px;font-family:\'Poppins\',\'Arial\',sans-serif;font-weight:600;color:#256575;line-height:152%;mso-line-height-alt:38px;mso-ansi-font-size:26px;">#password#</span></p></div> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <![endif]--> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <table align="center" border="0" cellpadding="0" cellspacing="0" class="r-outlook -outlook pr-16-outlook pl-16-outlook -outlook" role="presentation" style="width:600px;" width="600"><tr><td style="line-height:0;font-size:0;mso-line-height-rule:exactly;"> <![endif]--><div class="r  pr-16 pl-16" style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;"> <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fffffe;background-color:#fffffe;width:100%;"><tbody><tr><td style="border:none;direction:ltr;font-size:0;padding:20px 16px 20px 16px;text-align:left;"> <!--[if mso | IE]> <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="c-outlook -outlook -outlook" style="vertical-align:middle;width:568px;"> <![endif]--><div class="xc568 ogf c" style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border:none;vertical-align:middle;" width="100%"><tbody><tr><td align="center" class="x  m" style="font-size:0;padding-bottom:8px;word-break:break-word;"><div style="text-align:center;"><p style="Margin:0;text-align:center;mso-line-height-alt:24px;mso-ansi-font-size:20px;"><span style="font-size:20px;font-family:\'Inter\',\'Arial\',sans-serif;font-weight:700;color:#000000;line-height:120%;mso-line-height-alt:24px;mso-ansi-font-size:20px;">Codigo QR Google:</span></p></div> </td></tr><tr><td align="center" class="c  m" style="font-size:0;padding:0;padding-bottom:8px;word-break:break-word;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0;"><tbody><tr><td style="width:200px;"> <img alt src="#codeqr#" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" title width="200" height="auto"> </td></tr></tbody></table> </td></tr><tr><td class="s" style="font-size:0;padding:0;padding-bottom:0;word-break:break-word;" aria-hidden="true"><div style="height:4px;line-height:4px;">&#8202;</div> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <![endif]--> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <table align="center" border="0" cellpadding="0" cellspacing="0" class="r-outlook -outlook pr-16-outlook pl-16-outlook -outlook" role="presentation" style="width:600px;" width="600"><tr><td style="line-height:0;font-size:0;mso-line-height-rule:exactly;"> <![endif]--><div class="r  pr-16 pl-16" style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;"> <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fffffe;background-color:#fffffe;width:100%;"><tbody><tr><td style="border:none;direction:ltr;font-size:0;padding:32px 32px 32px 32px;text-align:left;"> <!--[if mso | IE]> <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="c-outlook -outlook -outlook" style="vertical-align:middle;width:536px;"> <![endif]--><div class="xc536 ogf c" style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;"> <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border:none;vertical-align:middle;" width="100%"><tbody><tr><td align="center" class="x" style="font-size:0;word-break:break-word;"><div style="text-align:center;"><p style="Margin:0;text-align:center;mso-line-height-alt:18px;mso-ansi-font-size:16px;"><span style="font-size:15px;font-family:\'Poppins\',\'Arial\',sans-serif;font-weight:400;color:#000000;line-height:113%;mso-line-height-alt:18px;mso-ansi-font-size:16px;">Si no puede cambiar su contrase&ntilde;a, por favor p&oacute;ngase en contacto con nosotros.</span></p><p style="Margin:0;mso-line-height-alt:18px;mso-ansi-font-size:16px;"><span style="font-size:15px;font-family:\'Poppins\',\'Arial\',sans-serif;font-weight:400;color:#000000;line-height:113%;mso-line-height-alt:18px;mso-ansi-font-size:16px;">&nbsp;</span></p><p style="Margin:0;mso-line-height-alt:18px;mso-ansi-font-size:16px;"><span style="font-size:15px;font-family:\'Poppins\',\'Arial\',sans-serif;font-weight:400;color:#000000;line-height:113%;mso-line-height-alt:18px;mso-ansi-font-size:16px;">Gracias por su compresi&oacute;n.</span></p></div> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <![endif]--> </td></tr></tbody></table></div> <!--[if mso | IE]> </td></tr></table> <![endif]--></div> </body> </html>';

            $html = str_replace('#name#', $Usuario->nombre, $html);

            /* Reemplaza marcadores en HTML con datos de usuario y código QR. */
            $html = str_replace('#Name#', $Usuario->nombre, $html);
            $html = str_replace('#password#', $newPassword, $html);
            $html = str_replace('#Password#', $newPassword, $html);
            $html = str_replace('#codeqr#', $Google->getQRCodeGoogleUrl('Virtualsoft', $Usuario->saltGoogle), $html);

            $email = '';


            /* Crea un perfil de usuario y obtiene el email si es tipo "PUNTOVENTA". */
            $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

            if ($UsuarioPerfil->perfilId == 'PUNTOVENTA') {
                $PuntoVenta = new PuntoVenta('', $Usuario->usuarioId);
                $email = $PuntoVenta->email;
            } else {
                /* Se asigna el valor del login del usuario a la variable $email. */

                $email = $Usuario->login;
            }


            /* envía un correo usando una función de configuración con parámetros específicos. */
            $email = trim($email);


            $ConfigurationEnvironment->EnviarCorreoVersion3($email, '', '', $subject, '', $subject, $html, '', '', '', '-1');

        } catch (Exception $ex) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir el flujo del programa. */

        }
    }

    if ($Action === 'GeneratePassword2') {

        /* Genera un nuevo usuario, configura entorno y cambia clave por una segura. */
        $Usuario = new Usuario($ClientId);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $passwordGen = substr(base64_encode(bin2hex(openssl_random_pseudo_bytes(16))), 0, 12);

        $Usuario->changeClave($passwordGen);

        $UsuarioLog = new UsuarioLog();

        /* Registro de actividad del usuario relacionado con cambio de clave en el sistema. */
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioaprobarIp('');
        $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
        $UsuarioLog->setUsuariosolicitaIp($ip);
        $UsuarioLog->setTipo('CAMBIOCLAVE');
        $UsuarioLog->setEstado('A');

        /* Se inicializan valores y se establece el usuario en un objeto de registro. */
        $UsuarioLog->setValorAntes('');
        $UsuarioLog->setValorDespues('');
        $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
        $UsuarioLog->setUsumodifId($_SESSION['usuario2']);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

        /* inserta un registro y confirma la transacción en MySQL. */
        $UsuarioLogMySqlDAO->insert($UsuarioLog);
        $UsuarioLogMySqlDAO->getTransaction()->commit();

        $cambios = true;
    }

    if ($Action == "UnlockUserByPassword") {

        /* Se crea un usuario y se obtienen transacciones desde la base de datos MySQL. */
        $IsActivate = "A";
        $Usuario = new Usuario($ClientId);
//$Registro = new Registro("", $ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();


        /* Se crea un registro de usuario con ID y dirección IP en la sesión. */
        $UsuarioLog = new UsuarioLog();
        $UsuarioLog->setUsuarioId($ClientId);
        $UsuarioLog->setUsuarioIp('');

        $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
        $UsuarioLog->setUsuariosolicitaIp($ip);


        /* Registro de cambios de estado de usuario en el sistema junto a sus metadatos. */
        $UsuarioLog->setTipo("ESTADOUSUARIO");
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($Usuario->estado);
        $UsuarioLog->setValorDespues($IsActivate);
        $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
        $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


        /* Inserta un registro de usuario y actualiza su estado y intentos. */
        $UsuarioLogMySqlDAO->insert($UsuarioLog);

        $Usuario->estado = "A";
        $Usuario->estadoEsp = "A";
        $Usuario->intentos = 0;


        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);


        /* Actualiza un usuario y confirma la transacción en la base de datos. */
        $UsuarioMySqlDAO->update($Usuario);


        $UsuarioLogMySqlDAO->getTransaction()->commit();


//$UsuarioMySqlDAO->getTransaction()->commit();
        $cambios = true;


    }

    if ($Action == "State") {

        /* Se crean instancias de usuario y registro, y se obtienen transacciones desde la base de datos. */
        $Usuario = new Usuario($ClientId);
        $Registro = new Registro("", $ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->estado) {

            /* crea un registro de usuario con identificaciones y direcciones IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registra el estado de un usuario antes y después de una modificación. */
            $UsuarioLog->setTipo("ESTADOUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->estado);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y activa su estado si es "A". */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            if ($IsActivate == "A") {
                $Usuario->estado = "A";
            } else {
                /* establece el estado del usuario como "Inactivo" bajo ciertas condiciones. */

                $Usuario->estado = "I";
            }


            /* Actualiza un usuario en MySQL utilizando una transacción. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);


            $UsuarioLogMySqlDAO->getTransaction()->commit();


//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Se define una variable llamada "cambios" y se le asigna el valor verdadero. */
            $cambios = true;


        }


    }

    if ($Action == "RegistroUsuario") {

        /* Se crea un usuario y un registro, obteniendo una transacción desde la base de datos. */
        $Usuario = new Usuario($ClientId);
        $Registro = new Registro("", $ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Registro->estadoValida) {

            /* Se crea un registro de usuario con ID y dirección IP en sesión. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios de estado de usuario en el sistema. */
            $UsuarioLog->setTipo("ESTADOREGISTRO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Registro->estadoValida);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* inserta un registro y determina el estado de validación. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Registro->estadoValida = "A";
            } else {
                $Registro->estadoValida = "I";
            }


            /* Actualiza un registro en MySQL usando una transacción gestionada por RegistroMySqlDAO. */
            $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);

            $RegistroMySqlDAO->update($Registro);


            $RegistroMySqlDAO->getTransaction()->commit();


//$UsuarioMySqlDAO->getTransaction()->commit();

            /* La variable $cambios se establece como verdadera, indicando que hay cambios pendientes. */
            $cambios = true;


        }


    }


    if ($Action == "Contingency") {

        /* Se crean objetos para gestionar usuarios y transacciones en una base de datos MySQL. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->contingencia) {

            /* Se crea un objeto UsuarioLog y se asignan valores de sesión e IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registrar cambios en el estado de un usuario en el sistema de contingencia. */
            $UsuarioLog->setTipo("CONTINGENCIAUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingencia);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro y asigna estado de contingencia basado en activación. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->contingencia = "A";
            } else {
                $Usuario->contingencia = "I";

            }


            /* Actualiza un usuario en la base de datos usando una transacción MySQL. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $UsuarioLogMySqlDAO->getTransaction()->commit();
            try {

                /* actualiza la configuración de usuario solo si la fecha de inicio es válida. */
                $Clasificador = new Clasificador("", "CONTINGENCIA");
                $ClasificadorId = $Clasificador->getClasificadorId();

                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* crea y almacena una configuración de usuario para una contingencia. */
                    $Clasificador = new Clasificador("", "CONTINGENCIA");
                    $ClasificadorId = $Clasificador->getClasificadorId();
                    if ($startDateFormatted != "") {
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencia");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                    }
                }
            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Variable booleana que indica si hay cambios en el contexto del código. */
            $cambios = true;

        }
    }

    if ($Action == "ContingencyRetired") {

        /* Se crea un usuario y se obtiene la transacción desde MySQL. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->retirado) {

            /* Se crea un registro de usuario con ID y dirección IP especificados. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registra cambios en el estado del usuario y su información asociada en el log. */
            $UsuarioLog->setTipo("CONTINGENCIAUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->retirado);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y define su estado de retiro. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->retirado = "S";
            } else {
                $Usuario->retirado = "N";
            }


            /* Código para actualizar un usuario en base de datos mediante un DAO y transacción. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);


            $UsuarioLogMySqlDAO->getTransaction()->commit();

            try {


                /* Crea un objeto Clasificador y obtiene su identificador. */
                $Clasificador = new Clasificador("", "CONTINGENCIARETIROS");
                $ClasificadorId = $Clasificador->getClasificadorId();


                /* Actualiza la configuración del usuario si la fecha de inicio es válida. */
                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencias retiro");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);


                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                }
            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* Crea y guarda una configuración de usuario si se proporciona una fecha de inicio. */
                    $Clasificador = new Clasificador("", "CONTINGENCIARETIROS");
                    $ClasificadorId = $Clasificador->getClasificadorId();
                    if ($startDateFormatted != "") {
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencias retiro");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                    }
                }

            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Variable booleana que indica si hay cambios en el estado actual. */
            $cambios = true;

        }
    }


    if ($Action == "ContingencySports") {

        /* Se crea un usuario y se obtiene una transacción desde la base de datos. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();


        if ($IsActivate != $Usuario->contingenciaDeportes) {

            /* Código para registrar el log de un usuario en sesión con su ID e IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios en estado de usuario relacionado con contingencia de deportes. */
            $UsuarioLog->setTipo("CONTINGENCIADEPORTEUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaDeportes);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro y activa o inactiva 'contingenciaDeportes' según el estado. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->contingenciaDeportes = "A";
            } else {
                $Usuario->contingenciaDeportes = "I";
            }


            /* Código para actualizar un usuario en la base de datos usando DAO. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $UsuarioLogMySqlDAO->getTransaction()->commit();

            try {


                /* Configura un clasificador de contingencias deportivas y actualiza el usuario en la base de datos. */
                $Clasificador = new Clasificador("", "CONTINGENCIADEPORTIVAS");
                $ClasificadorId = $Clasificador->getClasificadorId();

                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia deportivas");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* inserta una configuración de usuario en la base de datos si se cumple una condición. */
                    $Clasificador = new Clasificador("", "CONTINGENCIADEPORTIVAS");
                    $ClasificadorId = $Clasificador->getClasificadorId();

                    if ($startDateFormatted != "") {

                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencia deportivas");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                    }
                }
            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Se establece una variable llamada $cambios y se le asigna un valor verdadero. */
            $cambios = true; //vamos aca

        }
    }

    //Activa o desactiva la contingencia de retiros retail del usuario para generar notas de retiro por puntos de venta o red aliadas
    if ($Action == "ContingencyRetailWithdrawals") {
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        $Clasificador = new Clasificador("", "CONTINGENCIARETIROSRETAIL");
        $ClasificadorId = $Clasificador->getClasificadorId();

        $UsuarioLog = new UsuarioLog();
        $UsuarioLog->setUsuarioId($ClientId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
        $UsuarioLog->setUsuariosolicitaIp($ip);
        $UsuarioLog->setTipo("CONTINGENCIARETIROSRETAIL");
        $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
        $UsuarioLog->setUsumodifId($_SESSION['usuario2']);

        if ($startDateFormatted != "") {
            try {

                $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($ClasificadorId);
                $UsuarioConfiguracion->setValor($IsActivate);
                $UsuarioConfiguracion->setNota("Contingencia retiros retail");
                $UsuarioConfiguracion->setUsucreaId($ClientId);
                $UsuarioConfiguracion->setUsumodifId(0);
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setEstado($IsActivate);
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($IsActivate == "A" ? "I" : "A");
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == "46") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia retiros retail");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(productoId: 0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes($IsActivate == "A" ? "I" : "A");
                    $UsuarioLog->setValorDespues($IsActivate);
                    $UsuarioLogMySqlDAO->insert($UsuarioLog);
                    $UsuarioLogMySqlDAO->getTransaction()->commit();
                }
            }
            $cambios = true;
        }
    }

    /**
     * Gestiona la contingencia de puntos de venta para un usuario.
     *
     * Este bloque de código permite habilitar o deshabilitar la contingencia de puntos de venta
     * para un usuario específico. Se actualiza o inserta la configuración del usuario en la base de datos
     * y se registra una auditoría de la acción realizada.
     */

    if($Action == "PointOfSaleContingency"){
        try {
            // Guardamos en la configuración del usuario una contingencia de pasarelas.
            $Clasificador = new Clasificador("","CONTINGENCYRETAIL");
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IsActivate);
            $UsuarioConfiguracion->setNota("Contingencia deposito retail");
            $UsuarioConfiguracion->setUsucreaId($ClientId);
            $UsuarioConfiguracion->setUsumodifId(0);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado($IsActivate);
            $UsuarioConfiguracion->setFechaInicio("");
            $UsuarioConfiguracion->setFechaFin("");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
            $AuditoriaGeneral->setUsuarioIp("");
            $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsuariosolicitaIp("");
            $AuditoriaGeneral->setUsuarioaprobarIp(0);
            $AuditoriaGeneral->setTipo("HABILITACIONCONTINGENCIAPORPUNTOVENTARETAIL");
            $AuditoriaGeneral->setValorAntes(0);
            $AuditoriaGeneral->setValorDespues(0);
            $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsumodifId(0);
            $AuditoriaGeneral->setEstado("A");
            $AuditoriaGeneral->setDispositivo(0);
            $AuditoriaGeneral->setObservacion('Habilitacion contingencia retail por punto de venta');

            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
            $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
            $AuditoriaGeneralMySqlDAO->getTransaction()->commit();


        }catch (Exception $e){
            /*En caso de error, intentamos insertar la configuración de contingencia.*/
            $Clasificador = new Clasificador("","CONTINGENCYRETAIL");
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IsActivate);
            $UsuarioConfiguracion->setNota("Contingencia deposito retail");
            $UsuarioConfiguracion->setUsucreaId($ClientId);
            $UsuarioConfiguracion->setUsumodifId(0);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado($IsActivate);
            $UsuarioConfiguracion->setFechaInicio("");
            $UsuarioConfiguracion->setFechaFin("");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsuarioIp("");
            $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsuariosolicitaIp("");
            $AuditoriaGeneral->setUsuarioaprobarIp(0);
            $AuditoriaGeneral->setTipo("HABILITACIONCONTINGENCIAPORPUNTOVENTARETAIL");
            $AuditoriaGeneral->setValorAntes(0);
            $AuditoriaGeneral->setValorDespues(0);
            $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsumodifId(0);
            $AuditoriaGeneral->setEstado("A");
            $AuditoriaGeneral->setDispositivo(0);
            $AuditoriaGeneral->setObservacion('Habilitacion contingencia retail por punto de venta');

            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
            $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
            $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

        }
    }


    //--------------------------------------------------------------------------------CONTINGENCIA DEPOSITO USUONLINE-----------------------


    /**
     * Gestiona la contingencia de pasarelas de pago para un usuario.
     *
     * Este bloque de código permite habilitar o deshabilitar la contingencia de pasarelas de pago
     * para un usuario específico. Se actualiza o inserta la configuración del usuario en la base de datos
     * y se registra una auditoría de la acción realizada.
     */

    if($Action == "PaymentGatewayContingency"){
        try {
            $Clasificador = new Clasificador("","PAYMENTGATEWAYCONTINGENCY");
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId,"A", $ClasificadorId,0);
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IsActivate);
            $UsuarioConfiguracion->setNota("Contingencia depositos usuonline");
            $UsuarioConfiguracion->setUsucreaId($ClientId);
            $UsuarioConfiguracion->setUsumodifId(0);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado($IsActivate);
            $UsuarioConfiguracion->setFechaInicio("");
            $UsuarioConfiguracion->setFechaFin("");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsuarioIp("");
            $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsuariosolicitaIp("");
            $AuditoriaGeneral->setUsuarioaprobarIp(0);
            $AuditoriaGeneral->setTipo("HABILITACIONCONTINGENCIAPORPASARELA");
            $AuditoriaGeneral->setValorAntes(0);
            $AuditoriaGeneral->setValorDespues(0);
            $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsumodifId(0);
            $AuditoriaGeneral->setEstado("A");
            $AuditoriaGeneral->setDispositivo(0);
            $AuditoriaGeneral->setObservacion('Habilitacion contingencia por pasarela');

            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
            $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
            $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

        }catch (Exception $e){
            $Clasificador = new Clasificador("","PAYMENTGATEWAYCONTINGENCY");
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IsActivate);
            $UsuarioConfiguracion->setNota("Contingencia depositos usuonline");
            $UsuarioConfiguracion->setUsucreaId($ClientId);
            $UsuarioConfiguracion->setUsumodifId(0);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado($IsActivate);
            $UsuarioConfiguracion->setFechaInicio("");
            $UsuarioConfiguracion->setFechaFin("");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();



            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsuarioIp("");
            $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsuariosolicitaIp("");
            $AuditoriaGeneral->setUsuarioaprobarIp(0);
            $AuditoriaGeneral->setTipo("HABILITACIONCONTINGENCIAPORPASARELA");
            $AuditoriaGeneral->setValorAntes(0);
            $AuditoriaGeneral->setValorDespues(0);
            $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsumodifId(0);
            $AuditoriaGeneral->setEstado("A");
            $AuditoriaGeneral->setDispositivo(0);
            $AuditoriaGeneral->setObservacion('Habilitacion contingencia pasarela');

            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
            $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
            $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

        }
    }



    if ($Action == "ContingencyCasino") {

        /* Se crea un usuario y se obtiene una transacción desde el DAO correspondiente. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->contingenciaCasino) {

            /* Se crea un registro de usuario asociado a una solicitud con información de IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registra cambios en el estado de un usuario en el sistema de contingencia. */
            $UsuarioLog->setTipo("CONTINGENCIACASINOUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaCasino);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y establece el estado de contingencia en función de la activación. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->contingenciaCasino = "A";
            } else {
                $Usuario->contingenciaCasino = "I";

            }


            /* Se actualiza un usuario en la base de datos utilizando una transacción. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);


            $UsuarioLogMySqlDAO->getTransaction()->commit();


            try {


                /* actualiza la configuración de usuario para un clasificador específico. */
                $Clasificador = new Clasificador("", "CONTINGENCIACASINO");
                $ClasificadorId = $Clasificador->getClasificadorId();

                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia Casino");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }

            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* configura un usuario y guarda sus preferencias en una base de datos. */
                    $Clasificador = new Clasificador("", "CONTINGENCIACASINO");
                    $ClasificadorId = $Clasificador->getClasificadorId();

                    if ($startDateFormatted != "") {

                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencia Casino");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado("A");
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                    }
                }
            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Se define una variable llamada 'cambios' y se asigna el valor booleano verdadero. */
            $cambios = true;
        }

        /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();
        }
    }

    if ($Action == "ContingencyCasinoVivo") {

        /* Se crea un objeto Usuario y se obtiene una transacción de base de datos. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->contingenciaCasvivo) {


            /* Se crea un registro de usuario con identificación y dirección IP proporcionadas. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registra cambios de estado y usuario en el log de contingencias. */
            $UsuarioLog->setTipo("CONTINGENCIACASINOVIVOUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaCasvivo);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* inserta un registro y actualiza el estado del usuario si está activo. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->contingenciaCasvivo = "A";

            } else {
                /* Establece el valor "I" a la propiedad contingenciaCasvivo del objeto Usuario. */

                $Usuario->contingenciaCasvivo = "I";

            }


            /* Actualiza un usuario en la base de datos usando una transacción específica. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);


            $UsuarioLogMySqlDAO->getTransaction()->commit();


            try {

                /* Actualiza la configuración del usuario en base a la fecha y estado proporcionados. */
                if ($startDateFormatted != "") {
                    $Clasificador = new Clasificador("", "CONTINGENCIACASENVIVO");
                    $ClasificadorId = $Clasificador->getClasificadorId();
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia Casino en vivo");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* Se crea y guarda configuración de usuario para un clasificador de contingencia. */
                    $Clasificador = new Clasificador("", "CONTINGENCIACASENVIVO");
                    $ClasificadorId = $Clasificador->getClasificadorId();
                    if ($startDateFormatted != "") {
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencia Casino en vivo");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                    }
                }
            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Variable booleana que indica si hay cambios en el sistema. */
            $cambios = true;

        }

        /* Actualiza usuario en base de datos y confirma transacciones si hay cambios pendientes. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();
        }
    }

    if ($Action == "ContingencyVirtuales") {

        /* Se crea un usuario y se obtiene su transacción desde la base de datos. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->contingenciaVirtuales) {

            /* Se crea un objeto UsuarioLog y se establecen sus propiedades con datos de usuario. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios en el usuario, actualizando estado y valores de contingencia virtual. */
            $UsuarioLog->setTipo("CONTINGENCIAVIRTUALESUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaVirtuales);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro y actualiza el estado de usuario según condición. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->contingenciaVirtuales = "A";

            } else {
                /* Asignación de "I" a la propiedad contingenciaVirtuales del objeto Usuario en caso contrario. */

                $Usuario->contingenciaVirtuales = "I";

            }


            /* Actualiza un usuario en la base de datos usando transacciones MySQL. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $UsuarioLogMySqlDAO->getTransaction()->commit();


            try {

                /* actualiza configuraciones de usuario si se proporciona una fecha de inicio. */
                $Clasificador = new Clasificador("", "CONTINGENCIAVIRTUALES");
                $ClasificadorId = $Clasificador->getClasificadorId();


                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia virtuales");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* Crea una configuración de usuario para contingencias virtuales y la guarda en la base de datos. */
                    $Clasificador = new Clasificador("", "CONTINGENCIAVIRTUALES");
                    $ClasificadorId = $Clasificador->getClasificadorId();

                    if ($startDateFormatted != "") {
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencia virtuales");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


                    }
                }

            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Se declara una variable booleana llamada "cambios" y se le asigna el valor verdadero. */
            $cambios = true;


        }


        /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();
        }
    }

    if ($Action == "ContingencyPoker") {

        /* crea un usuario y obtiene su transacción desde una base de datos. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->contingenciaPoker) {

            /* Se crea un registro de log de usuario con ID e IP del solicitante. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de usuario con estado y valores antes y después de un cambio. */
            $UsuarioLog->setTipo("CONTINGENCIAPOKERUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaPoker);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Código inserta un registro de usuario y activa una contingencia si se cumple la condición. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {

                $Usuario->contingenciaPoker = "A";
            } else {
                /* asigna "I" a la propiedad contingenciaPoker del objeto Usuario en caso contrario. */


                $Usuario->contingenciaPoker = "I";

            }


            /* confirma una transacción y actualiza un usuario en la base de datos. */
            $UsuarioLogMySqlDAO->getTransaction()->commit();
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            try {

                /* Actualiza la configuración del usuario en la base de datos según parámetros dados. */
                $Clasificador = new Clasificador("", "CONTINGENCIAPOKER");
                $ClasificadorId = $Clasificador->getClasificadorId();

                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia poker");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }

            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* Crea y guarda una configuración de usuario relacionada a "Contingencia poker" si hay fecha. */
                    $Clasificador = new Clasificador("", "CONTINGENCIAPOKER");
                    $ClasificadorId = $Clasificador->getClasificadorId();

                    if ($startDateFormatted != "") {
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencia poker");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                    }

                }
            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Variable que indica si hay cambios; se inicializa como verdadero. */
            $cambios = true;

        }


        /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();
        }

    }

    if ($Action == "ContingencyWithdrawals") {

        /* Se crean usuarios y se obtiene una transacción desde MySQL. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->contingenciaRetiro) {

            /* Se crea un registro de log para un usuario con información específica. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);
            $UsuarioLog->setTipo("CONTINGENCIARETIROUSUARIO");

            /* registra cambios en el estado de un usuario en la base de datos. */
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaRetiro);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            /* asigna "A" o "I" a contingenciaRetiro según el valor de IsActivate. */
            if ($IsActivate == "A") {

                $Usuario->contingenciaRetiro = "A";
            } else {

                $Usuario->contingenciaRetiro = "I";
            }

            try {

                /* Actualiza la configuración de usuario para retiros en función de las fechas y estado. */
                $Clasificador = new Clasificador("", "CONTINGENCIARETIROS");
                $ClasificadorId = $Clasificador->getClasificadorId();

                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia retiro");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }

            } catch (Exception $e) {
                if ($e->getCode() == "46") {


                    /* Crea y guarda la configuración de usuario para el clasificador de contingencia de retiros. */
                    $Clasificador = new Clasificador("", "CONTINGENCIARETIROS");
                    $ClasificadorId = $Clasificador->getClasificadorId();

                    if ($startDateFormatted != "") {
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencia retiro");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                    }
                }
            }

            /* La variable $cambios se inicializa en verdadero, indicando que hay modificaciones. */
            $cambios = true;
        }

        /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();
        }
    }

    if ($Action == "ContingencyWithdrawal") {

        /* Se crea un objeto usuario y se obtiene una transacción de la base de datos. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->contingenciaRetiro) {

            /* Registro de usuario configura ID e IP del cliente y solicitante en sesión. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Código para registrar cambios de estado de un usuario en contingencia de retiro. */
            $UsuarioLog->setTipo("CONTINGENCIARETIROUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaRetiro);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y activa contingencia si se cumple la condición. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {

                $Usuario->contingenciaRetiro = "A";
            } else {
                /* Asigna "I" a contingenciaRetiro del objeto Usuario en caso de no cumplirse condiciones. */


                $Usuario->contingenciaRetiro = "I";

            }


            try {

                /* Se actualiza la configuración de usuario para contingencias de retiros en la base de datos. */
                $Clasificador = new Clasificador("", "CONTINGENCIARETIROS");
                $ClasificadorId = $Clasificador->getClasificadorId();

                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencias");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* configura y guarda ajustes de usuario relacionados con contingencias en la base de datos. */
                    $Clasificador = new Clasificador("", "CONTINGENCIARETIROS");
                    $ClasificadorId = $Clasificador->getClasificadorId();

                    if ($startDateFormatted != "") {
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencias");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                    }
                }
            }

            /* La variable `$cambios` se establece en verdadero, indicando que hay cambios pendientes. */
            $cambios = true;
//$UsuarioMySqlDAO->getTransaction()->commit();

        }

        /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();
        }
    }

    if ($Action == "ContingencyDeposit") {

        /* Se inicia un usuario y se obtiene una transacción desde la base de datos MySQL. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->contingenciaDeposito) {

            /* Crea un registro de log de usuario con ID y dirección IP correspondientes. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registra cambios en el estado y valores de un usuario en contingencia de depósito. */
            $UsuarioLog->setTipo("CONTINGENCIADEPOSITOUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaRetiro);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y activa el depósito si la condición se cumple. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {

                $Usuario->contingenciaDeposito = "A";
            } else {
                /* asigna "I" a contingenciaDeposito si la condición anterior no se cumple. */


                $Usuario->contingenciaDeposito = "I";

            }


            /* actualiza un usuario en la base de datos mediante un DAO MySQL. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $UsuarioLogMySqlDAO->getTransaction()->commit();


            try {

                /* actualiza la configuración del usuario según la contingencia de depósitos. */
                $Clasificador = new Clasificador("", "CONTINGENCIADEPOSITOS");
                $ClasificadorId = $Clasificador->getClasificadorId();

                if ($startDateFormatted != "") {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $ClasificadorId);
                    $UsuarioConfiguracion->setUsuarioId($ClientId);
                    $UsuarioConfiguracion->setTipo($ClasificadorId);
                    $UsuarioConfiguracion->setValor($IsActivate);
                    $UsuarioConfiguracion->setNota("Contingencia deposito");
                    $UsuarioConfiguracion->setUsucreaId($ClientId);
                    $UsuarioConfiguracion->setUsumodifId(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivate);
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                    $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                if ($e->getCode() == "46") {

                    /* Crea y guarda la configuración de usuario para contingencia de depósitos. */
                    $Clasificador = new Clasificador("", "CONTINGENCIADEPOSITOS");
                    $ClasificadorId = $Clasificador->getClasificadorId();

                    if ($startDateFormatted != "") {
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($ClientId);
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor($IsActivate);
                        $UsuarioConfiguracion->setNota("Contingencia deposito");
                        $UsuarioConfiguracion->setUsucreaId($ClientId);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setProductoId(0);
                        $UsuarioConfiguracion->setEstado($IsActivate);
                        $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                        $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                    }
                }
            }
//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Una variable booleana que indica si hay cambios o no. */
            $cambios = true;
        }


        /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();
        }
    }

    if ($Action == "UpdateIP") {

        /* Código para crear un usuario y obtener su transacción desde MySQL. */
        $Usuario = new Usuario($ClientId);
        $IP = $params->IP;

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IP != $Usuario->usuarioIp) {

            /* Se crea un objeto UsuarioLog y se configuran sus propiedades de usuario. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Se registran cambios en el usuario, incluyendo IP, estado y creador/modificador. */
            $UsuarioLog->setTipo("USUARIOIP");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->usuarioIp);
            $UsuarioLog->setValorDespues($IP);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de log de usuario en MySQL y actualiza su IP. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $Usuario->usuarioIp = $IP;


//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;

        }


        /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();

        }


    }

    if ($Action == "ContingencyRestriccionIP") {

        /* Se crea un usuario y se obtiene una transacción desde la base de datos MySQL. */
        $Usuario = new Usuario($ClientId);
        $IP = $params->IP;

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->restriccionIp) {

            /* Se crea un objeto UsuarioLog y se le asignan valores de usuario e IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios de un usuario, incluyendo estado y valores antes y después. */
            $UsuarioLog->setTipo("RESTRICCIONIPUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->contingenciaPoker);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y establece la restricción de IP según estado. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->restriccionIp = "A";
            } else {
                $Usuario->restriccionIp = "I";

            }


            /* inserta una nueva configuración de usuario si la fecha de inicio es válida. */
            if ($startDateFormatted != "") {
                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($ClasificadorId);
                $UsuarioConfiguracion->setValor("A");
                $UsuarioConfiguracion->setNota("Contingencias");
                $UsuarioConfiguracion->setUsucreaId($ClientId);
                $UsuarioConfiguracion->setUsumodifId(0);
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setEstado("A");
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Se asigna el valor verdadero a la variable "cambios". */
            $cambios = true;
        }


        /* Código que actualiza un usuario en la base de datos y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();

        }


    }

    if ($Action == "CancelAccount") {

        /* Se crea un objeto Usuario y se obtiene una transacción del DAO correspondiente. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();


        if ($IsActivate == "A" && $Usuario->eliminado != 'S') {

            /* Se crea un registro de usuario con identificadores y direcciones IP correspondientes. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios en la cuenta de usuario al cancelar, con estado y valores actualizados. */
            $UsuarioLog->setTipo("CANCELACCOUNT");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes('N');
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* inserta un registro de usuario y marca como eliminado y retirado. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

//$Usuario->login = '--'.$Usuario->login.'--DEL';
            $Usuario->eliminado = "S";
            $Usuario->retirado = "S";
            $Usuario->fechaRetiro = date('Y-m-d');

            /* Código asigna hora y estado a usuario y modifica el correo del registro. */
            $Usuario->horaRetiro = date('H:i');
            $Usuario->estado = "I";

            $Registro = new Registro("", $ClientId);
            $Registro->email = '--' . $Registro->email . '--DEL';


//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;
        }


        /* Actualiza usuario y registro en la base de datos si hay cambios, luego confirma transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);
            $RegistroMySqlDAO->update($Registro);

            $Transaction->commit();

        }


    }

// accion para cancelar cuenta administrativa
    if ($Action == "IsCancelAdminAccount") {

        /* Se crea un usuario y se obtiene una transacción desde la base de datos MySQL. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();


        if ($IsActivate == "A" && $Usuario->eliminado != 'S') {

            /* Se crea un registro de log para un usuario con su ID e IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registrar una cancelación de cuenta con detalles del usuario y cambios en estado. */
            $UsuarioLog->setTipo("CANCELACCOUNTADMIN");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes('N');
            $UsuarioLog->setValorDespues(json_encode(['nuevo_estado' => 'N', 'observacion' => $Note]));
            $UsuarioLog->setUsucreaId($_SESSION['usuario']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario']);


            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            /* modifica el estado del usuario, marcándolo como eliminado y retirado. */
            $Usuario->login = '--' . $Usuario->login . '--DEL';
            $Usuario->eliminado = "S";
            $Usuario->retirado = "S";
            $Usuario->fechaRetiro = date('Y-m-d');
            $Usuario->horaRetiro = date('H:i');
            $Usuario->estado = "I";


            /* La variable $cambios se establece en verdadero, indicando que ha habido modificaciones. */
            $cambios = true;
        }


        /* Actualiza un usuario en la base de datos si hay cambios y confirma transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();

        }

    }


    if ($Action == "ContingencyTokenGoogle") {

        /* Creación de un usuario y obtención de transacciones desde una base de datos MySQL. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();


        if ($IsActivate != $Usuario->tokenGoogle) {

            /* Código para registrar datos de un usuario, incluyendo ID y dirección IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registra cambios en el token de Google de un usuario en el sistema. */
            $UsuarioLog->setTipo("TOKENGOOGLEUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->tokenGoogle);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* inserta un usuario y genera un token de Google si está activado. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->tokenGoogle = "A";

                $Google = new GoogleAuthenticator();


                if ($Usuario->saltGoogle == "") {
                    $Usuario->saltGoogle = $Google->createSecret();
                }


            } else {
                /* Asigna el valor "I" al token de Google del usuario si se cumple una condición. */

                $Usuario->tokenGoogle = "I";

            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Se asigna el valor verdadero a la variable $cambios. */
            $cambios = true;


        }


        /* Actualiza un usuario en la base de datos si hay cambios y confirma transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();

        }


    }


    if ($Action == "ContingencyTokenLocal") {

        /* crea un usuario y obtiene su transacción desde una base de datos. */
        $Usuario = new Usuario($ClientId);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();


        if ($IsActivate != $Usuario->tokenLocal) {

            /* crea un registro de usuario con ID e IP del solicitante. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de usuario log con cambios en el token y estado de activación. */
            $UsuarioLog->setTipo("TOKENLOCALUSUARIO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->tokenLocal);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* inserta un registro y asigna un token según el estado de activación. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            if ($IsActivate == "A") {
                $Usuario->tokenLocal = "A";
            } else {
                $Usuario->tokenLocal = "I";

            }

//$UsuarioMySqlDAO->getTransaction()->commit();

            /* Se establece una variable llamada "cambios" y se le asigna el valor verdadero. */
            $cambios = true;


        }


        /* Actualiza usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();

        }


    }

    if ($Action == "AllowRecarga") {


        /* Código define un usuario y su configuración, y activa o desactiva según estado. */
        $Usuario = new Usuario($ClientId);
        $UsuarioConfig = new UsuarioConfig($ClientId);

        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

        /* obtiene una transacción del objeto `$UsuarioLogMySqlDAO`. */
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();


        if ($IsActivate != $UsuarioConfig->permiteRecarga) {

            /* Crea un registro de usuario con ID y dirección IP solicitada. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Se registra un cambio en la configuración de recarga del usuario. */
            $UsuarioLog->setTipo("USUARIOPERMITERECARGA");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioConfig->permiteRecarga);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* inserta un registro de usuario y actualiza la configuración de recarga. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioConfig->permiteRecarga = $IsActivate;

//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


        }


        /* Actualiza la configuración del usuario en MySQL y confirma la transacción si hay cambios. */
        if ($cambios) {
            $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($Transaction);

            $UsuarioConfigMySqlDAO->update($UsuarioConfig);

            $Transaction->commit();

        }


    }

    if ($Action == "StateCountry") {


        /* Se crea un perfil de usuario y se determina su estado de activación. */
        $UsuarioPerfil = new UsuarioPerfil($ClientId);

        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $UsuarioPerfil->pais) {

            /* Se registra información de un usuario en un sistema, incluyendo ID y dirección IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Código para registrar cambios en el estado de un usuario y su información de país. */
            $UsuarioLog->setTipo("USUARIODEPAIS");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioPerfil->pais);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Código para insertar un registro y activar un perfil de usuario en la base de datos. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioPerfil->pais = $IsActivate;

//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


        }


        /* Actualiza un perfil de usuario en la base de datos si hay cambios. */
        if ($cambios) {
            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);

            $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);

            $Transaction->commit();

        }


    }

    if ($Action == "AllowSendSms") {


        /* Se crea un perfil de usuario y se determina su estado de activación. */
        $UsuarioPerfil = new UsuarioPerfil($ClientId);

        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $UsuarioPerfil->consentimientoSms) {

            /* Código que registra información del usuario y su IP en un objeto UsuarioLog. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios en el consentimiento SMS del usuario en el sistema. */
            $UsuarioLog->setTipo("CONSENTSMS");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioPerfil->consentimientoSms);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Insertar un registro de usuario y actualizar su consentimiento para recibir SMS. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioPerfil->consentimientoSms = $IsActivate;

//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


        }


        /* Actualiza el perfil de usuario en la base de datos si hay cambios. */
        if ($cambios) {
            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);

            $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);

            $Transaction->commit();

        }

    }

    if ($Action == "AllowSendEmail") {


        /* Se crea un perfil de usuario y se gestiona una transacción. */
        $UsuarioPerfil = new UsuarioPerfil($ClientId);

        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $UsuarioPerfil->consentimientoEmail) {

            /* Se crea un registro de usuario con datos de sesión y dirección IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios en el consentimiento de email de un usuario. */
            $UsuarioLog->setTipo("CONSENTEMAIL");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioPerfil->consentimientoEmail);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y actualiza el consentimiento de email. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioPerfil->consentimientoEmail = $IsActivate;

//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


        }


        /* Actualiza el perfil de usuario en la base de datos y confirma la transacción. */
        if ($cambios) {
            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);

            $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);

            $Transaction->commit();

        }

    }

    if ($Action == "AllowSendPhone") {


        /* Inicializa un perfil de usuario y verifica si está activado para gestionar transacciones. */
        $UsuarioPerfil = new UsuarioPerfil($ClientId);

        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $UsuarioPerfil->consentimientoTelefono) {

            /* Código que registra información de usuario, incluyendo ID y dirección IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios en consentimiento telefónico del usuario en el sistema. */
            $UsuarioLog->setTipo("CONSENTTELEPHONE");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioPerfil->consentimientoTelefono);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y actualiza el consentimiento del teléfono. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioPerfil->consentimientoTelefono = $IsActivate;

//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


        }


        /* Actualiza el perfil de usuario en la base de datos si hay cambios. */
        if ($cambios) {
            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);

            $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);

            $Transaction->commit();

        }

    }

    if ($Action == "AbusadorBonos") {
        try {
            /** Contingencia abusador de bonos este bloquea bonos pendientes del usuario y bloquea la accion de redencion del usuario*/
// Se verifica si el usuario tiene activa la contingencia abusador de bonos

            /* Se crea un objeto de configuración de usuario y se obtiene información de la base de datos. */
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($ClientId);
            $UsarioConfiguracionMysqlDAO = new UsuarioConfiguracionMySqlDAO();

            if ($UsuarioConfiguracion->usuconfigId != '' && $UsuarioConfiguracion->usuconfigId != null) {
                if ($UsuarioConfiguracion->estado != $IsActivate) {
//Si el usuario tiene activa la contingencia y se envio desde front una peticion de inactivacion se hace update del estado de la contingencia inactivandola


                    /* Actualiza el estado y valor del usuario en la base de datos y registra un log. */
                    $UsuarioConfiguracion->estado = $IsActivate;
                    $UsuarioConfiguracion->valor = $IsActivate;
                    $UsarioConfiguracionMysqlDAO->update($UsuarioConfiguracion);

// Logs sobre el estado de la asiganción
                    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

                    /* Código que registra un log de usuario con datos específicos y tipo de evento. */
                    $UsuarioLog = new UsuarioLog();
                    $UsuarioLog->setUsuarioId($ClientId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                    $UsuarioLog->setUsuariosolicitaIp($ip);
                    $UsuarioLog->setTipo("CONTINGENCIAABUSADORBONOSUSUARIO");

                    /* Registro del estado y cambios de un usuario en la base de datos. */
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes("A");
                    $UsuarioLog->setValorDespues($IsActivate);
                    $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                    $UsuarioLog->setUsumodifId($_SESSION['usuario2']);
                    $UsuarioLogMySqlDAO->insert($UsuarioLog);


                    /* confirma una transacción en MySQL y establece una variable de cambios. */
                    $UsarioConfiguracionMysqlDAO->getTransaction()->commit();

                    $cambios = true;
                }
            } else {
                if ($IsActivate == 'A') {

                    /** Activacion de la contingencia abusador de bonos a un usuario en especifico  */

                    /* Se crea un clasificador y se establece una configuración de usuario para insertar datos. */
                    $clasificador = new Clasificador('', 'BONDABUSER');
                    $tipo = $clasificador->clasificadorId;

//Generamos datos para el insert de la contingencia
                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                    $UsuarioConfiguracion->usuarioId = $ClientId;

                    /* Asigna valores a propiedades de un objeto de configuración de usuario. */
                    $UsuarioConfiguracion->valor = $IsActivate;
                    $UsuarioConfiguracion->usucreaId = $_SESSION['usuario2'];
                    $UsuarioConfiguracion->tipo = $tipo;
                    $UsuarioConfiguracion->usumodifId = 0;
                    $UsuarioConfiguracion->productoId = 0;
                    $UsuarioConfiguracion->estado = $IsActivate;

                    /* Configura nota y fechas para un usuario, registrando en logs cambios realizados. */
                    $UsuarioConfiguracion->nota = 'Contingencia abusador bonos';
                    $UsuarioConfiguracion->fechaCrea = date('Y-m-d H:i:s');
                    $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');
                    $UsuarioConfiguracion->setFechaInicio($startDateFormatted);

// Logs sobre el estado de la asiganción
                    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

                    /* Registra un nuevo log de usuario con información específica de sesión e IP. */
                    $UsuarioLog = new UsuarioLog();
                    $UsuarioLog->setUsuarioId($ClientId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                    $UsuarioLog->setUsuariosolicitaIp($ip);
                    $UsuarioLog->setTipo("CONTINGENCIAABUSADORBONOSUSUARIO");

                    /* Se registra un cambio de estado en el usuario en la base de datos. */
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes("I");
                    $UsuarioLog->setValorDespues($IsActivate);
                    $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                    $UsuarioLog->setUsumodifId($_SESSION['usuario2']);
                    $UsuarioLogMySqlDAO->insert($UsuarioLog);

                    /** Inactivación de bonos pendientes por contingencia abusador de bonos */


                    /* Se crea un filtro para usuarios y bonos específicos en un array. */
                    $UsuarioBono = new UsuarioBono();
                    $rules = [];
// Ajustamos filtros
                    array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$ClientId", "op" => "eq"));
                    array_push($rules, array("field" => "bono_interno.tipo", "data" => '8', "op" => "ne"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");


                    /* inactiva bonos de usuario obtenidos desde una base de datos. */
                    $json = json_encode($filtro);

                    $bonos = $UsuarioBono->getUsuarioBonosNoAltenarCustom(" usuario_bono.* ", "usuario_bono.bono_id", "asc", 0, 100, $json, true);

//Inactivamos bonos encontrados
                    foreach ($bonos as $bono) {
                        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
                        $bono->estado = 'I';
                        $UsuarioBonoMySqlDAO->update($bono);
                    }
// Se genera el insert en la tabla usuario_configuracion para la activación de la contingencia

                    /* Se inserta un objeto de configuración de usuario en MySQL y se confirma la transacción. */
                    $UsarioConfiguracionMysqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsarioConfiguracionMysqlDAO->insert($UsuarioConfiguracion);
                    $UsarioConfiguracionMysqlDAO->getTransaction()->commit();

                    $cambios = true;
                }
            }
        } catch (Exception $e) {
            /* Es un bloque de captura que maneja excepciones en programación, sin realizar acciones. */

        }
    }

    if ($Action == "AllowSendPush") {


        /* Se crea un perfil de usuario y se determina su estado de activación. */
        $UsuarioPerfil = new UsuarioPerfil($ClientId);

        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $UsuarioPerfil->consentimientoPush) {


            /* Crea un registro de log para un usuario con su ID y dirección IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios en el consentimiento de notificaciones push para un usuario específico. */
            $UsuarioLog->setTipo("CONSENTPUSH");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioPerfil->consentimientoPush);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario y actualiza el consentimiento de notificaciones push. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioPerfil->consentimientoPush = $IsActivate;

//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


        }


        /* Actualiza el perfil de usuario en la base de datos si hay cambios. */
        if ($cambios) {


            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);

            $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);

            $Transaction->commit();

        }

    }

    if ($Action == "StateMandante") {


        /* Se crea un perfil de usuario y se define su estado de activación. */
        $UsuarioPerfil = new UsuarioPerfil($ClientId);

        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $UsuarioPerfil->global) {

            /* Crea un registro de usuario asociando ID y IP en una sesión. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registra cambios de estado de un usuario global en el sistema. */
            $UsuarioLog->setTipo("USUARIOGLOBAL");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioPerfil->global);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de usuario en MySQL y actualiza el perfil global. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioPerfil->global = $IsActivate;

//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


        }


        /* Actualiza el perfil de usuario y confirma la transacción si hay cambios. */
        if ($cambios) {
            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);

            $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);

            $Transaction->commit();


        }


    }

    if ($Action == "DniAnterior") {
        $Usuario = new Usuario($ClientId);


        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->verifcedulaAnt) {

            /* Código para crear un registro de log de usuario incluyendo ID y dirección IP. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registro de cambios en usuario, almacenando información sobre su estado y creador. */
            $UsuarioLog->setTipo("USUDNIANTERIOR");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->verifcedulaAnt);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Inserta un registro de log de usuario y activa verificación de cédula. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $Usuario->verifcedulaAnt = $IsActivate;

//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


            if ($IsActivate == 'S') {
                try {


                    if (true) {
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                            $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro_type_gift","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                            $SitioTracking = new SitioTracking();
                            $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                            $sitiosTracking = json_decode($sitiosTracking);

                            $type_gift = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                            if ($type_gift != '') {
                                $asignacionDinamica = false;
                                $bonoIdd = null;

                                try {
//Verificando existencia de bonos dinámicos

                                    /* Se inicializan objetos y patrones relacionados con bonos según diferentes categorías. */
                                    $tipoBonoSeleccionado = null;
                                    $Clasificador = new Clasificador('', 'BONUSFORLANDING');
                                    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                                    $patronesBono = [
                                        3 => '#deportiva#', //deportiva
                                        5 => '#casino#', //FreeCasino
                                        6 => '#deportiva#', //FreeBet
                                        8 => '#casino#' //FreeCasino
                                    ];


                                    /* busca identificar un bono basado en patrones definidos y una entrada del usuario. */
                                    foreach ($patronesBono as $tipoBonoId => $patronBono) {
//Identificando bono seleccionado por el usuario
                                        if (preg_match($patronBono, $type_gift)) {
                                            $tipoBonoSeleccionado = $tipoBonoId;
                                            break;
                                        }
                                    }

//Verificando que haya un bono del tipo seleccionado por el usuario
                                    $ofertaBonos = explode(',', $MandanteDetalle->valor);
                                    if (empty($ofertaBonos)) throw new Exception('', 34);


                                    /* Selecciona bonos ofrecidos basándose en patrones definidos y la opción del usuario. */
                                    foreach ($ofertaBonos as $bonoOfertado) {
                                        $BonoInterno = new BonoInterno($bonoOfertado);
                                        foreach ($patronesBono as $tipoBonoId => $patronBono) {
//Identificando bono seleccionado por el usuario
                                            if (preg_match($patronBono, $type_gift)) {
                                                $tipoBonoSeleccionado = $tipoBonoId;
                                                if ($BonoInterno->tipo == $tipoBonoSeleccionado) {

                                                    $bonoIdd = $bonoOfertado;
                                                }
                                            }
                                        }


                                    }
                                    if (empty($bonoIdd)) throw new Exception('', 34);


                                    /* Crea un array asociativo para almacenar detalles de depósitos y usuario. */
                                    $detalles = array(
                                        "Depositos" => 0,
                                        "DepositoEfectivo" => false,
                                        "MetodoPago" => 0,
                                        "ValorDeposito" => 0,
                                        "PaisPV" => 0,
                                        "DepartamentoPV" => 0,
                                        "CiudadPV" => 0,
                                        "PuntoVenta" => 0,
                                        "PaisUSER" => $Usuario->paisId,
                                        "DepartamentoUSER" => 0,
                                        "CiudadUSER" => $Registro->ciudadId,
                                        "MonedaUSER" => $Usuario->moneda,
                                        "CodePromo" => ''
                                    );


                                    /* Se agregan detalles a un bono y se procesa una transacción si se cumple una condición. */
                                    $detalles = json_decode(json_encode($detalles));

                                    $BonoInterno = new BonoInterno();

                                    $responseBonus = $BonoInterno->agregarBonoFree($bonoIdd, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, "", $Transaction);

                                    if ($responseBonus->WinBonus) {
//$Transaction->commit();
                                    }

                                    /* Variable que permite habilitar o deshabilitar la asignación dinámica en el código. */
                                    $asignacionDinamica = true;

                                } catch (Exception $e) {
                                }
                            }

                            if (!$asignacionDinamica && $type_gift != '') {
                                if ($type_gift != '' && $type_gift == '20_tiros_gratis_de_casino') {

                                    /* Asigna diferentes valores a $bonoIdd según condiciones del usuario y su país. */
                                    $bonoIdd = 43166;
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '66') {
                                        $bonoIdd = 42428;
                                    }
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '46') {
                                        $bonoIdd = 26249;
                                    }

                                    /* Asignación de $bonoIdd según condiciones de $Usuario: mandante y paisId. */
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '60') {
                                        $bonoIdd = 42025;
                                    }
                                    if ($Usuario->mandante == 8 && $Usuario->paisId == '66') {
                                        $bonoIdd = 42733;
                                    }

                                    /* Asignación de valores a $bonoIdd según condiciones de $Usuario. */
                                    if ($Usuario->mandante == 14) {
                                        $bonoIdd = 32730;
                                    }
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '94') {
                                        $bonoIdd = 34131;
                                    }

                                    /* Asignación de variables y creación de un array con datos de usuario y depósito. */
                                    if ($Usuario->mandante == 23) {
                                        $bonoIdd = 43204;
                                    }
                                    $detalles = array(
                                        "Depositos" => 0,
                                        "DepositoEfectivo" => false,
                                        "MetodoPago" => 0,
                                        "ValorDeposito" => 0,
                                        "PaisPV" => 0,
                                        "DepartamentoPV" => 0,
                                        "CiudadPV" => 0,
                                        "PuntoVenta" => 0,
                                        "PaisUSER" => $Usuario->paisId,
                                        "DepartamentoUSER" => 0,
                                        "CiudadUSER" => $Registro->ciudadId,
                                        "MonedaUSER" => $Usuario->moneda,
                                        "CodePromo" => ''
                                    );


                                    /* agrega un bono interno utilizando datos del usuario y detalles proporcionados. */
                                    $detalles = json_decode(json_encode($detalles));

                                    $BonoInterno = new BonoInterno();
                                    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
// $Transaction = $BonoInternoMySqlDAO->getTransaction();

                                    $responseBonus = $BonoInterno->agregarBonoFree($bonoIdd, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, "", $Transaction);


                                    /* confirma una transacción si se cumple la condición de ganar un bonus. */
                                    if ($responseBonus->WinBonus) {
// $Transaction->commit();
                                    }


                                }
                                if ($type_gift != '' && $type_gift == 'apuesta_deportiva_gratis_de_20_soles') {

                                    /* Asigna un valor a $bonoIdd según condiciones del usuario y su país. */
                                    $bonoIdd = 23763;
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '66') {
                                        $bonoIdd = 40757;
                                    }
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '46') {
                                        $bonoIdd = 39717;
                                    }

                                    /* Asigna un valor a $bonoIdd según condiciones de mandante y país del usuario. */
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '60') {
                                        $bonoIdd = 42051;
                                    }
                                    if ($Usuario->mandante == 8 && $Usuario->paisId == '66') {
                                        $bonoIdd = 40017;
                                    }

                                    /* Asigna un bono según el mandante y país del usuario. */
                                    if ($Usuario->mandante == 14) {
                                        $bonoIdd = 32289;
                                    }
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '94') {
                                        $bonoIdd = 34120;
                                    }

                                    /* asigna un bono y crea un array con detalles de depósito. */
                                    if ($Usuario->mandante == 23) {
                                        $bonoIdd = 41634;
                                    }


                                    $detalles = array(
                                        "Depositos" => 0,
                                        "DepositoEfectivo" => false,
                                        "MetodoPago" => 0,
                                        "ValorDeposito" => 0,
                                        "PaisPV" => 0,
                                        "DepartamentoPV" => 0,
                                        "CiudadPV" => 0,
                                        "PuntoVenta" => 0,
                                        "PaisUSER" => $Usuario->paisId,
                                        "DepartamentoUSER" => 0,
                                        "CiudadUSER" => $Registro->ciudadId,
                                        "MonedaUSER" => $Usuario->moneda,
                                        "CodePromo" => ''
                                    );


                                    /* agrega un bono libre utilizando datos de usuario y detalles específicos. */
                                    $detalles = json_decode(json_encode($detalles));

                                    $BonoInterno = new BonoInterno();
                                    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
// $Transaction = $BonoInternoMySqlDAO->getTransaction($Transaction);

                                    $responseBonus = $BonoInterno->agregarBonoFree($bonoIdd, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, "", $Transaction);


                                    /* verifica un bono de respuesta y opcionalmente confirma una transacción. */
                                    if ($responseBonus->WinBonus) {
// $Transaction->commit();
                                    }


                                }
                                if ($type_gift != '' && $type_gift == '25_tiros_gratis_de_casino') {

                                    /* asigna un bono según el mandante y país del usuario. */
                                    $bonoIdd = 43166;
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '66') {
                                        $bonoIdd = 42428;
                                    }
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '46') {
                                        $bonoIdd = 26249;
                                    }

                                    /* Condiciona la asignación de $bonoIdd según el mandante y país del usuario. */
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '60') {
                                        $bonoIdd = 42025;
                                    }
                                    if ($Usuario->mandante == 8 && $Usuario->paisId == '66') {
                                        $bonoIdd = 42733;
                                    }

                                    /* asigna valores a `$bonoIdd` según condiciones del usuario y país. */
                                    if ($Usuario->mandante == 14) {
                                        $bonoIdd = 32730;
                                    }
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '94') {
                                        $bonoIdd = 34131;
                                    }

                                    /* Se define un bono y se inicializa un array con detalles de una transacción. */
                                    if ($Usuario->mandante == 23) {
                                        $bonoIdd = 43204;
                                    }
                                    $detalles = array(
                                        "Depositos" => 0,
                                        "DepositoEfectivo" => false,
                                        "MetodoPago" => 0,
                                        "ValorDeposito" => 0,
                                        "PaisPV" => 0,
                                        "DepartamentoPV" => 0,
                                        "CiudadPV" => 0,
                                        "PuntoVenta" => 0,
                                        "PaisUSER" => $Usuario->paisId,
                                        "DepartamentoUSER" => 0,
                                        "CiudadUSER" => $Registro->ciudadId,
                                        "MonedaUSER" => $Usuario->moneda,
                                        "CodePromo" => ''
                                    );


                                    /* Se agrega un bono gratuito con detalles y usuario especificados. */
                                    $detalles = json_decode(json_encode($detalles));

                                    $BonoInterno = new BonoInterno();
                                    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
//$Transaction = $BonoInternoMySqlDAO->getTransaction();

                                    $responseBonus = $BonoInterno->agregarBonoFree($bonoIdd, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, "", $Transaction);


                                    /* Condicional que verifica si hay un bono ganado y comenta la transacción. */
                                    if ($responseBonus->WinBonus) {
// $Transaction->commit();


                                    }

                                }
                                if ($type_gift != '' && $type_gift == 'apuesta_deportiva_gratis_de_25_soles') {

                                    /* Asignación condicional de $bonoIdd según condiciones de mandante y paisId del usuario. */
                                    $bonoIdd = 23763;
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '66') {
                                        $bonoIdd = 40757;
                                    }
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '46') {
                                        $bonoIdd = 39717;
                                    }

                                    /* asigna un bono basado en condiciones del usuario y país. */
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '60') {
                                        $bonoIdd = 42051;
                                    }
                                    if ($Usuario->mandante == 8 && $Usuario->paisId == '66') {
                                        $bonoIdd = 40017;
                                    }

                                    /* Asigna un identificador de bono según el mandante y el país del usuario. */
                                    if ($Usuario->mandante == 14) {
                                        $bonoIdd = 32289;
                                    }
                                    if ($Usuario->mandante == 0 && $Usuario->paisId == '94') {
                                        $bonoIdd = 34120;
                                    }

                                    /* Asigna un bono y crea un arreglo con detalles de un usuario y depósitos. */
                                    if ($Usuario->mandante == 23) {
                                        $bonoIdd = 41634;
                                    }

                                    $detalles = array(
                                        "Depositos" => 0,
                                        "DepositoEfectivo" => false,
                                        "MetodoPago" => 0,
                                        "ValorDeposito" => 0,
                                        "PaisPV" => 0,
                                        "DepartamentoPV" => 0,
                                        "CiudadPV" => 0,
                                        "PuntoVenta" => 0,
                                        "PaisUSER" => $Usuario->paisId,
                                        "DepartamentoUSER" => 0,
                                        "CiudadUSER" => $Registro->ciudadId,
                                        "MonedaUSER" => $Usuario->moneda,
                                        "CodePromo" => ''
                                    );


                                    /* agrega un bono interno utilizando datos de usuario y detalles en formato JSON. */
                                    $detalles = json_decode(json_encode($detalles));

                                    $BonoInterno = new BonoInterno();
                                    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
//$Transaction = $BonoInternoMySqlDAO->getTransaction();

                                    $responseBonus = $BonoInterno->agregarBonoFree($bonoIdd, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, "", $Transaction);


                                    /* verifica una condición y comenta una línea para confirmar una transacción. */
                                    if ($responseBonus->WinBonus) {
//  $Transaction->commit();


                                    }


                                }

                            }

                    }
                } catch (Exception $e) {
                }
            }


        }


        /* actualiza un usuario en la base de datos si hay cambios. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();


        }


    }


    if ($Action == "DniPosterior") {


        /* Se crea un objeto Usuario y se define su estado de activación. */
        $Usuario = new Usuario($ClientId);


        $IsActivate = ($IsActivate == "A") ? "S" : "N";

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

        /* Se obtiene la transacción del usuario usando el objeto DAO de MySQL. */
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        if ($IsActivate != $Usuario->verifcedulaPost) {

            /* Creación y configuración de un objeto UsuarioLog con datos del usuario actual. */
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);


            /* Registra cambios en el estado de un usuario en el sistema. */
            $UsuarioLog->setTipo("USUDNIPOSTERIOR");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->verifcedulaPost);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


            /* Se inserta un registro de usuario y se actualiza la verificación de cédula. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $Usuario->verifcedulaPost = $IsActivate;


//$UsuarioMySqlDAO->getTransaction()->commit();
            $cambios = true;


        }


        /* Actualiza un usuario en la base de datos si hay cambios y confirma la transacción. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $UsuarioMySqlDAO->update($Usuario);

            $Transaction->commit();


        }


    }


    if ($Action == "Advertising") {

        /* crea instancias de usuario y registro, y asigna un estado de activación. */
        $Usuario = new Usuario($ClientId);
        $Registro = new Registro("", $ClientId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Pais = new Pais($Usuario->paisId);

        $IsActivate = ($IsActivate == "A") ? "S" : "N";


        /* Se crea una instancia de UsuarioLogMySqlDAO y se obtiene una transacción asociada. */
        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();


        /* Registra un cambio en la configuración de publicidad del usuario si es necesario. */
        if ($IsActivate != $Usuario->getPermiteEnviarPublicidad()) {
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);

            $UsuarioLog->setTipo("ALLOWSENDADVERTISING");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioPerfil->consentimientoPush);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);

            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $Usuario->permite_enviarpublicidad = $IsActivate;

            $cambios = true;
        }


        /* actualiza un usuario y registra el cambio en la base de datos. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMySqlDAO->update($Usuario);
            $UsuarioMySqlDAO->getTransaction()->commit();

            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

        }

    }

    if ($Action == 'PhoneVerification') {

        /* actualiza el estado de verificación del celular y registra el cambio. */
        $IsActivate = $IsActivate == 'I' ? 'N' : 'S';
        $Usuario = new Usuario($ClientId);
        if ($Usuario->verifCelular != $IsActivate) {
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);

            $UsuarioLog->setTipo("PHONEVERIFICATION");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->verifCelular);
            $UsuarioLog->setValorDespues($IsActivate);
            $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
            $UsuarioLog->setUsumodifId($_SESSION['usuario2']);

            $Usuario->verifCelular = $IsActivate;
            $cambios = true;
        }

        /* Actualiza un usuario y registra el cambio en logs si hay modificaciones. */
        if ($cambios) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMySqlDAO->update($Usuario);
            $transaction->commit();

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $transaction = $UsuarioLogMySqlDAO->getTransaction();
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $transaction->commit();
        }
    }

    if ($Action == "SendCodeMobile") {


        /* inicializa objetos de usuario, registro, país y configuración ambiental. */
        $Usuario = new Usuario($ClientId);
        $Registro = new Registro("", $ClientId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Pais = new Pais($Usuario->paisId);

        $ConfigurationEnvironment = new ConfigurationEnvironment();


        /* Genera una nueva clave de ticket y la asigna a un usuario específico. */
        $nuevaClave = $ConfigurationEnvironment->GenerarClaveTicket(8);
        $Usuario->changeClave($nuevaClave);

        $Mandante = new Mandante($Usuario->mandante);
        switch (strtolower($Usuario->idioma)) {


            case "pt":
                /* genera un mensaje de registro con una clave temporal para el usuario. */

//Arma el mensaje para el usuario que se registra
                $mensaje_txt = "A chave de entrada temporaria gerada e xxxxxx:";


                break;

            case "en":
                /* Genera un mensaje de registro con una clave temporal en inglés. */

//Arma el mensaje para el usuario que se registra
                $mensaje_txt = "The temporary entry key generated is xxxxxx";
                break;

            default:

//Arma el mensaje para el usuario que se registra

                /* Variable que almacena un mensaje con una clave de acceso temporal generada. */
                $mensaje_txt = "La clave de ingreso temporal generada es xxxxxx";
                break;
        }


        /* Condicional que modifica un mensaje y lo envía si se cumplen ciertas condiciones. */
        if ($Usuario->paisId == "173" && $Usuario->mandante == 0) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }

        /* Condicional que modifica un mensaje y envía un correo según condiciones específicas. */
        if ($Usuario->paisId == "94" && $Usuario->mandante == 0) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }

        /* Condicional que envía un mensaje de texto si se cumplen ciertos criterios del usuario. */
        if ($Usuario->paisId == "66" && $Usuario->mandante == 8) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }


        /* Condición que personaliza un mensaje y lo envía si se cumplen ciertos criterios. */
        if ($Usuario->paisId == "66" && $Usuario->mandante == 0) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }


        /* Condición para enviar un mensaje personalizado según país y estado del usuario. */
        if ($Usuario->paisId == "2" && $Usuario->mandante == 0) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }


        /* Condicional que modifica y envía un mensaje si se cumple ciertas condiciones del usuario. */
        if ($Usuario->paisId == "46" && $Usuario->mandante == 0) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }


        /* Condicional que modifica y envía un mensaje si se cumplen ciertas condiciones del usuario. */
        if ($Usuario->paisId == "60" && $Usuario->mandante == 0) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }

        /* envía un mensaje personalizado si el mandante es 14. */
        if ($Usuario->mandante == 14) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }

        /* Condicional que envía un mensaje de texto al usuario si su mandante es 13. */
        if ($Usuario->mandante == 13) {
            $mensaje_txt = $Mandante->nombre . ' | ' . $mensaje_txt;

            $mensaje_txt = str_replace("xxxxxx", $nuevaClave, $mensaje_txt);

//Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
            $cambios = true;

        }


    }


    /*$response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];*/

    if (isset($passwordGen)) $response['Data'] = $passwordGen;

    if ($cambios) {

        /* Código para manejar la respuesta de un proceso, indicando éxito y sin errores. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $Usuario = new Usuario($ClientId);


        /* Se crea una nueva instancia de la clase ConfigurationEnvironment en la variable. */
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {

// se agrega validacion que permite evaluar el perfil del usuario evitando que cuando sea un usuario diferente a perfil usuarioonline no nos devuelva error


            /* verifica el perfil de un usuario y envía un mensaje por WebSocket. */
            $UsuarioPerfil = new UsuarioPerfil($ClientId);

            if ($UsuarioPerfil->perfilId == "USUONLINE") {
                $UsuarioMandante = new UsuarioMandante("", $ClientId, $Usuario->mandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                try {


                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                    /* $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();*/
                } catch (Exception $e) {

                }
            }
        }


    }


} else {

    /* Código para manejar respuestas de error en una aplicación, indicando datos incorrectos. */
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "Datos incorrectos";
    $response["ModelErrors"] = [];


}

