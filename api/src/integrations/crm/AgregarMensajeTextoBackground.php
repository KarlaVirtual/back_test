<?php

/**
 * Este archivo agrega un mensaje de texto a la base de datos y lo envía.
 * Utiliza Redis para almacenar temporalmente los datos y maneja excepciones.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-19
 *
 * @var mixed $UsuarioId                Esta variable representa la información del usuario, empleada para
 *                                      identificarlo dentro del sistema.
 * @var mixed $argv                     Esta variable se utiliza para almacenar y manipular los argumentos pasados al
 *                                      script.
 * @var mixed $TemplateId               Esta variable se utiliza para almacenar y manipular el identificador de la
 *                                      plantilla.
 * @var mixed $CampaignId               Variable que almacena el identificador de una campaña.
 * @var mixed $redisParam               Esta variable se utiliza para almacenar y manipular parámetros de Redis.
 * @var mixed $redisPrefix              Esta variable se utiliza para almacenar y manipular el prefijo utilizado en
 *                                      Redis.
 * @var mixed $redis                    Esta variable se utiliza para almacenar y manipular instancias de Redis.
 * @var mixed $BonoInternoMySqlDAO      Objeto que maneja operaciones de base de datos para bonos internos en MySQL.
 * @var mixed $Usuario                  Esta variable representa la información del usuario, empleada para
 *                                      identificarlo dentro del sistema.
 * @var mixed $Registro                 Variable que almacena información sobre un registro.
 * @var mixed $UsuarioMensajecampana    Esta variable representa la información del usuario, empleada para
 *                                      identificarlo dentro del sistema.
 * @var mixed $UsuarioMandante          Esta variable representa la información del usuario, empleada para
 *                                      identificarlo dentro del sistema.
 * @var mixed $Contenido                Variable que almacena contenido de un mensaje o respuesta.
 * @var mixed $UsuarioMensaje           Esta variable representa la información del usuario, empleada para
 *                                      identificarlo dentro del sistema.
 * @var mixed $UsuarioMensajeMySqlDAO   Esta variable representa la información del usuario, empleada para
 *                                      identificarlo dentro del sistema.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del
 *                                      entorno.
 * @var mixed $UsuarioMensajes          Esta variable representa la información del usuario, empleada para
 *                                      identificarlo dentro del sistema.
 * @var mixed $varArray                 Variable que almacena un arreglo genérico de valores.
 * @var mixed $envio                    Variable que indica una acción o proceso de envío.
 * @var mixed $e                        Esta variable se utiliza para capturar excepciones o errores en bloques
 *                                      try-catch.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\CategoriaMandante;
use Backend\dto\CategoriaProducto;
use Backend\dto\Ciudad;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Exception;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\ProductoDetalle;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\SubproveedorMandante;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTarjetacredito;
use Backend\integrations\casino\PLAYNGOSERVICES;
use Backend\integrations\casino\PLAYTECHSERVICES;
use Backend\integrations\mensajeria\Intico;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;
use phpseclib3\Math\BigInteger\Engines\PHP;
use \CurlWrapper;

// Obtiene los parámetros de entrada del script
$UsuarioId = $argv[1]; // ID del usuario
$TemplateId = $argv[2]; // ID de la plantilla
$CampaignId = $argv[3]; // ID de la campaña

// Configuración de parámetros para Redis
$redisParam = ['ex' => 18000];

// Genera un prefijo único de Redis utilizando el ID de usuario, un ID de plantilla y el ID de campaña
$redisPrefix = "F3BACK+AgregarMensajeTextoBackground+UID" . $UsuarioId . '+' . $TemplateId . '+' . $CampaignId;

/**
 * Obtiene una instancia de Redis y almacena los datos temporalmente.
 * Si Redis está disponible, almacena los datos y finaliza el script.
 */
$redis = RedisConnectionTrait::getRedisInstance(true);

if ($redis != null) {
    $redis->set($redisPrefix, json_encode($argv), $redisParam);
    exit();
}

try {
    // Inicializa el DAO para manejar operaciones de bonos internos en MySQL
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

    // Crea una instancia de Usuario con el ID de usuario proporcionado
    $Usuario = new Usuario($UsuarioId);

    // Crea una instancia de Registro con el ID de usuario proporcionado
    $Registro = new Registro('', $UsuarioId);

    // Crea una instancia de UsuarioMensajecampana con el ID de plantilla proporcionado
    $UsuarioMensajecampana = new UsuarioMensajecampana($TemplateId);

    // Crea una instancia de UsuarioMandante con los datos del usuario
    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

    // Obtiene el contenido del mensaje de la campaña
    $Contenido = $UsuarioMensajecampana->body;

    // Crea una instancia de UsuarioMensaje y establece sus propiedades
    $UsuarioMensaje = new UsuarioMensaje();
    $UsuarioMensaje->usufromId = 0;
    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
    $UsuarioMensaje->isRead = 0;
    $UsuarioMensaje->body = $Contenido;
    $UsuarioMensaje->msubject = 'Mensaje';
    $UsuarioMensaje->parentId = 0;
    $UsuarioMensaje->proveedorId = 0;
    $UsuarioMensaje->tipo = "SMS";
    $UsuarioMensaje->valor1 = $CampaignId;
    $UsuarioMensaje->usumencampanaId = $TemplateId;

    // Inserta el mensaje en la base de datos y confirma la transacción
    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

    // Crea una instancia de ConfigurationEnvironment
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    // Prepara los datos del mensaje para el envío
    $UsuarioMensajes = array();
    $varArray = array();
    $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
    $varArray['tophone'] = $Registro->celular;
    $varArray['link'] = '';

    // Agrega los datos del mensaje al array de mensajes
    array_push($UsuarioMensajes, $varArray);

    // Envía el mensaje de texto
    $envio = $ConfigurationEnvironment->EnviarMensajeTexto($Contenido, '', $Registro->celular, 0, $UsuarioMandante);
} catch (Exception $e) {
    // Captura y maneja cualquier excepción que ocurra
}