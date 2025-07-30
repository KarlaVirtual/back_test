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
use Backend\integrations\crm\Optimove;
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
 * Client/SaveClientMessageCrm
 *
 * Envío y gestión de mensajes programados e inmediatos.
 *
 * Este recurso procesa el envío de mensajes a través de una campaña,
 * permitiendo asignar un mensaje a un usuario y asociarlo con un
 * proveedor y una campaña específica. Los mensajes pueden ser enviados
 * inmediatamente o programados para una fecha de expiración y envío.
 * También se gestionan los detalles asociados al cliente, país y
 * los datos específicos de la campaña.
 *
 * @param string $ClientId : Identificador único del cliente que solicita el envío del mensaje.
 * @param string $Description : Descripción del mensaje a enviar, que será mostrada al destinatario.
 * @param string $Message : Contenido completo del mensaje que se enviará al destinatario.
 * @param string $Name : Nombre del remitente del mensaje (quien está enviando el mensaje).
 * @param string $Title : Título o asunto del mensaje a enviar.
 * @param string $DateExpiration : Fecha y hora en que el mensaje expira, después de la cual no será válido.
 * @param string $DateFrom : Fecha y hora de inicio del mensaje, indicando cuándo será enviado.
 * @param string $DateProgram : Fecha programada para el envío del mensaje si aplica (puede estar vacío si no se utiliza programación).
 * @param string $Mandante : Identificador del mandante (usuario o entidad que gestiona el envío del mensaje).
 * @param string $CountrySelect : País seleccionado para el envío del mensaje, que puede influir en su proceso.
 * @param string $ClientIdCsv : Identificador CSV para representar al cliente.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error durante la operación (true si hubo error, false si no).
 *  - *AlertType* (string): Especifica el tipo de alerta que debe mostrarse al usuario (ej. "success", "danger").
 *  - *AlertMessage* (string): Contiene el mensaje que será mostrado en la interfaz, informando del resultado de la operación.
 *  - *ModelErrors* (array): Contiene errores del modelo si los hay, generalmente vacío.
 *  - *Data* (array): Contiene datos adicionales sobre la operación o el resultado de la consulta realizada.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Hubo un error al procesar la solicitud.";
 * $response["ModelErrors"] = [];
 * $response["Data"] = [];
 *
 * @throws Exception Error en la operación, como un fallo en el procesamiento de los datos o en la inserción en la base de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asigna un ID de proveedor basado en una condición de sesión. */
$proveedorId = '0';
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}

/*error_reporting(E_ALL);
ini_set("display_errors","ON");*/

try {

    /* Decodifica datos entrantes y reemplaza entidades HTML por caracteres correspondientes. */
    $params = file_get_contents('php://input');
    $params = base64_decode($params);
    $params = html_entity_decode($params);


    $unwanted_array = array(
        '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
        '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
        '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');

    /* reemplaza cadenas, decodifica JSON y inicializa variables para contar. */
    $params = strtr($params, $unwanted_array);

    $params = json_decode($params);

    $numerosString = "##";
    $cont = 0;


    /* asigna valores de parámetros y corrige un carácter acentuado en la descripción. */
    $Name = $params->Name;
    $Description = $params->Description;
    $Description = str_replace('ó', 'ó', $Description);

    $Message = $params->Message;
    $ParentId = $params->ParentId;

    /* Asigna parámetros de entrada a variables para procesar información del cliente. */
    $ClientId = $params->ClientId;
    $Title = $params->Title;
    $isSMS = $params->isSms;
    $Id = $params->Id;
    $CountrySelect = $params->CountrySelect;
    $DateProgram = $params->DateProgram;


    /* Itera sobre $DateProgram, formateando SendDate y DateExpiration en formato de fecha. */
    foreach ($DateProgram as $key => $value) {


        $SendDate = date('Y-m-d H:i:s', $value->SendDate);
        $DateExpiration = date('Y-m-d H:i:s', $value->DateExpiration);
    }

    /* Código que asigna valores de sesión a variables según condiciones específicas. */
    $Mandante = $_SESSION["mandante"];
    $usuarioId = $_SESSION["usuario"];


    if ($_SESSION['PaisCond'] == "S") {
        $CountrySelect = $_SESSION["pais_id"];
    }


    /* Crea un objeto UsuarioMensajecampana y configura sus propiedades iniciales. */
    $UsuarioMensajecampana = new UsuarioMensajecampana();
    $UsuarioMensajecampana->usufromId = 0;
    $UsuarioMensajecampana->usutoId = -1;
    $UsuarioMensajecampana->isRead = 0;
    $UsuarioMensajecampana->body = $Message;
    $UsuarioMensajecampana->msubject = "";

    /* Asignación de propiedades a un objeto para gestionar campañas de mensajes. */
    $UsuarioMensajecampana->parentId = 0;
    $UsuarioMensajecampana->proveedorId = $proveedorId;
    $UsuarioMensajecampana->tipo = "MENSAJE";
    $UsuarioMensajecampana->paisId = $CountrySelect;
    $UsuarioMensajecampana->estado = "A";
    $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;

    /* Asignación de propiedades a un objeto UsuarioMensajecampana con datos de campaña. */
    $UsuarioMensajecampana->usucreaId = $usuarioId;
    $UsuarioMensajecampana->usumodifId = $usuarioId;
    $UsuarioMensajecampana->nombre = $Name;
    $UsuarioMensajecampana->descripcion = $Description;
    $UsuarioMensajecampana->mandante = $Mandante;
    $UsuarioMensajecampana->fechaEnvio = $SendDate;

    /* Inserta un mensaje en la base de datos usando un DAO de usuario. */
    $msg = "entro5";

    $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

    $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
    $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


    /* crea un nuevo objeto usuario mensaje y define sus propiedades básicas. */
    $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;

    $UsuarioMensaje = new UsuarioMensaje();
    $UsuarioMensaje->usufromId = 0;
    $UsuarioMensaje->usutoId = -1;
    $UsuarioMensaje->isRead = 0;

    /* Asignación de valores a las propiedades de un objeto `UsuarioMensaje`. */
    $UsuarioMensaje->body = $Description;
    $UsuarioMensaje->msubject = $Title;
    $UsuarioMensaje->parentId = 0;
    $UsuarioMensaje->proveedorId = $proveedorId;
    $UsuarioMensaje->tipo = "MENSAJE";
    $UsuarioMensaje->paisId = $CountrySelect;

    /* Se asignan valores a un objeto y se inserta en base de datos. */
    $UsuarioMensaje->fechaExpiracion = $DateExpiration;
    $UsuarioMensaje->usumencampanaId = $usumencampanaId;
    $msg = "entro5";

    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


    /* Código para confirmar transacciones y agregar plantillas en Optimoove según condiciones. */
    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

    $ParentId = $UsuarioMensaje->usumensajeId;

    if ($usumencampanaId != "") {

        $Optimove = new Optimove();
        //$Token = $Optimove->Login($Mandante,$CountrySelect);

        //$Token = $Token->response;
        $TemplateId = $Optimove->AddChannelTemplates($usumencampanaId, 511, $Name, $Mandante, $CountrySelect);
    }


    /* inicializa una respuesta sin errores y con un mensaje de éxito. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = $msg . " - " . $usumencampanaId;
    $response["ModelErrors"] = [];

    $response["Data"] = [];
} catch (Exception $e) {
    /* captura excepciones y las imprime para debugueo. */

    print_r($e);
}
