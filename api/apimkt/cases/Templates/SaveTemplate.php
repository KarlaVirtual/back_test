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
use Backend\dto\UsuarioMensajecampana;
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
use Backend\mysql\TemplateMySqlDAO;
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
use Backend\mysql\UsuarioMensajecampanaMySqlDAO;
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
 * Client/SaveTemplate
 *
 * Procesa datos entrantes y gestiona plantillas de mensajes, insertando o actualizando registros en la base de datos.
 *
 * Este recurso recibe datos codificados en base64 y realiza varias transformaciones, como la decodificación de entidades HTML,
 * la sustitución de caracteres no deseados y la asignación de valores a variables. Luego, gestiona plantillas de mensajes,
 * creando o actualizando registros en la base de datos dependiendo de si ya existe una plantilla con el ID especificado.
 * Si no se encuentra la plantilla, se crea una nueva entrada en la base de datos. Si la plantilla existe, se actualiza con los
 * nuevos valores proporcionados.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada para procesar la plantilla.
 * @param string $params->CountryId : ID del país al que pertenece la plantilla.
 * @param string $params->TypeName : Nombre del tipo de plantilla.
 * @param string $params->Section : Sección relacionada con la plantilla.
 * @param string $params->TypeId : ID del tipo de plantilla.
 * @param string $params->LanguageId : ID del idioma en que se encuentra la plantilla.
 * @param object $params->Template : Contenido de la plantilla en formato JSON.
 * @param object $params->Template2 : Plantilla alternativa que puede usarse si se proporciona.
 * @param string $params->Message : Mensaje que se asociará a la plantilla.
 * @param string $params->Id : ID de la plantilla que se va a actualizar o crear.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará (por ejemplo, "success").
 *  - *AlertMessage* (string): Contiene el mensaje de alerta que se mostrará en la vista.
 *  - *ModelErrors* (array): En caso de error, contiene los errores del modelo.
 *  - *Data* (array): Contiene los datos procesados, generalmente vacío en este caso.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Error al procesar la plantilla.";
 * $response["ModelErrors"] = ["Error en la base de datos"];
 * $response["Data"] = [];
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* procesa datos entrantes, decodificándolos y reemplazando entidades HTML específicas. */
$params = file_get_contents('php://input');
$params = base64_decode($params);
$params = html_entity_decode($params);


$unwanted_array = array(
    '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
    '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
    '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ', '&iexcl;' => '¡', '&aacute;' => 'á');

/* reemplaza cadenas, decodifica JSON y asigna valores a variables. */
$params = strtr($params, $unwanted_array);

$params = json_decode($params);
$CountryId = $params->CountryId;
$Name = $params->TypeName;
$Section = $params->Section;

/* asigna y modifica variables basadas en parámetros proporcionados. */
$Type = $params->TypeId;
$LanguageSelect = $params->LanguageId;
$TemplateArray = json_encode($params->Template);
$TemplateArray2 = ($params->Template2);

if ($TemplateArray2 != '') {
    $TemplateArray = $TemplateArray2;
}

/* asigna un mensaje y crea un objeto Template con un ID específico. */
$Message = ($params->Message);
$mandante = $_SESSION["mandante"];
//$TemplateArray = $params->Template;

$Id = $params->Id;


$Template = new Template($Id);


if ($Template->templateId == "") {


    /* elimina caracteres específicos de dos variables antes de usar una plantilla. */
    $TemplateArray = str_replace("\xE2\x80\x8A", "", $TemplateArray);
    $Message = str_replace("\xE2\x80\x8A", "", $Message);
    $TemplateArray = str_replace("\xE2\x80\x8B", "", $TemplateArray);
    $Message = str_replace("\xE2\x80\x8B", "", $Message);


    $Template = new Template();

    /* Asigna valores a las propiedades de un objeto Template en PHP. */
    $Template->nombre = $Name;
    $Template->tipo = $Type;
    $Template->templateArray = $TemplateArray;
    $Template->templateHtml = $Message;
    $Template->mandante = $mandante;
    $Template->paisId = $CountryId;

    /* Se asignan valores a un objeto Template y se crea una instancia de TemplateMySqlDAO. */
    $Template->lenguaje = $LanguageSelect;

    $Template->usucreaId = $_SESSION["usuario2"];
    $Template->usumodifId = $_SESSION["usuario2"];

    $TemplateMySqlDAO = new TemplateMySqlDAO();

    /* Inserta un template y obtiene la transacción en la base de datos MySQL. */
    $TemplateMySqlDAO->insert($Template);

    $TemplateMySqlDAO->getTransaction()->commit();
} else {
    /* elimina caracteres invisibles y actualiza una plantilla en la base de datos. */


    $TemplateArray = str_replace("\xE2\x80\x8A", "", $TemplateArray);
    $Message = str_replace("\xE2\x80\x8A", "", $Message);
    $TemplateArray = str_replace("\xE2\x80\x8B", "", $TemplateArray);
    $Message = str_replace("\xE2\x80\x8B", "", $Message);

    $Template->setTemplateArray($TemplateArray);
    $Template->setTemplateHtml($Message);

    $Template->setUsumodifId($_SESSION["usuario2"]);


    $TemplateMySqlDAO = new TemplateMySqlDAO();
    $TemplateMySqlDAO->updateTemplate($Template);

    $TemplateMySqlDAO->getTransaction()->commit();

}


/* inicializa una respuesta sin errores y lista de datos vacía. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["ModelErrors"] = [];

$response["Data"] = [];


$response["Data"] = [];



