<?php
/**
 * Este archivo contiene un script para procesar y enviar mensajes SMS utilizando la integración con Infobip.
 * También registra los mensajes enviados en la base de datos y genera logs para auditoría.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $data                     Contiene los datos JSON recibidos en la solicitud HTTP.
 * @var mixed $URI                      URI de la solicitud actual.
 * @var mixed $log                      Cadena que almacena información de logs para auditoría.
 * @var mixed $Proveedor                Objeto que representa al proveedor de servicios (Infobip).
 * @var mixed $Producto                 Objeto que representa el producto asociado al proveedor.
 * @var mixed $usuarioMandante          Objeto que representa al usuario mandante.
 * @var mixed $Usuario                  Objeto que representa al usuario actual.
 * @var mixed $Pais                     Objeto que representa el país asociado al usuario mandante.
 * @var mixed $UsuarioMandante          Objeto que representa al usuario mandante con datos actualizados.
 * @var mixed $SubproveedorMandantePais Objeto que contiene credenciales y configuración del subproveedor.
 * @var mixed $Credentials              Credenciales decodificadas del subproveedor.
 * @var mixed $message                  Mensaje a enviar a través de SMS.
 * @var mixed $tophone                  Número de teléfono del destinatario.
 * @var mixed $Infobip                  Objeto que maneja la integración con Infobip.
 * @var mixed $UsuarioMensaje           Objeto que representa el mensaje enviado por el usuario.
 * @var mixed $UsuarioMensajeMySqlDAO   Objeto que maneja operaciones de base de datos para mensajes de usuario.
 */

// Carga de dependencias
require(__DIR__ . '../../../../../vendor/autoload.php');

// Declaración de espacios de nombres utilizados
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\Template;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioVerificacion;
use Backend\integrations\auth\JUMIOSERVICES;
use Backend\integrations\mensajeria\Flynode;
use Backend\integrations\mensajeria\Infobip;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 'OFF');

// Obtención de datos de la solicitud
$data = file_get_contents('php://input');
$URI = $_SERVER['REQUEST_URI'];

// Generación de logs
$log = "\r\n" . "------------" . date("Y-m-d H:i:s") . "-------------" . "\r\n";
$log = $log . ($URI);
$log = $log . (http_build_query($_REQUEST));
$log = $log . $data . date("Y-m-d");
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

// Decodificación de datos JSON
$data = json_decode($data);

// Inicialización de objetos y configuración
$Proveedor = new Proveedor("", "INFOBIP");
$Producto = new Producto('', 'SMSINFOBIP', $Proveedor->proveedorId);

$usuarioMandante = new UsuarioMandante(10871);
$Usuario = new Usuario($usuarioMandante->getUsuarioMandante());
$Pais = new Pais($usuarioMandante->paisId);
$UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

$SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
$Credentials = json_decode($SubproveedorMandantePais->getCredentials());

// Preparación de datos para el mensaje
$message = $data->message;
$tophone = $data->tophone;

// Envío del mensaje SMS
$Infobip = new Infobip();

$UsuarioMensaje = new UsuarioMensaje();
$UsuarioMensaje->usufromId = 0;
$UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
$UsuarioMensaje->isRead = 0;
$UsuarioMensaje->body = $message;
$UsuarioMensaje->msubject = 'Mensaje';
$UsuarioMensaje->tipo = "SMS";
$UsuarioMensaje->parentId = 0;
$UsuarioMensaje->proveedorId = 0;
$UsuarioMensaje->tipo = "SMS";

// Registro del mensaje en la base de datos
$UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
$UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
$UsuarioMensajeMySqlDAO->getTransaction()->commit();

// Envío del mensaje a través de Infobip
$Infobip->sendMessage($tophone, $message, $UsuarioMensaje);

// Actualización del mensaje con información adicional
$UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
$UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
$UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
$UsuarioMensajeMySqlDAO->getTransaction()->commit();


