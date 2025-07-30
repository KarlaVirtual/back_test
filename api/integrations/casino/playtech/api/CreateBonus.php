<?php
/**
 * Este archivo contiene un script para procesar y gestionar la creación de bonos
 * en la integración con el proveedor de casino Playtech.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $body                Contiene el cuerpo de la solicitud HTTP en formato JSON.
 * @var mixed $data                Objeto decodificado del JSON recibido en la solicitud.
 * @var mixed $usuarioId           Identificador único del usuario.
 * @var mixed $bonoId              Identificador único del bono.
 * @var mixed $valor               Valor asociado al bono.
 * @var mixed $roundsFree          Número de rondas gratuitas asignadas.
 * @var mixed $TemplateCode        Código de plantilla para el bono.
 * @var mixed $GoldenChip          Indica si el bono incluye fichas doradas.
 * @var mixed $games               Identificador externo de los juegos asociados.
 * @var mixed $Usuario             Objeto que representa al usuario.
 * @var mixed $estado              Estado inicial del bono.
 * @var mixed $valor_bono          Valor del bono asignado.
 * @var mixed $valor_base          Valor base del bono.
 * @var mixed $errorId             Identificador de error, si aplica.
 * @var mixed $idExterno           Identificador externo del bono.
 * @var mixed $mandante            Mandante asociado al usuario.
 * @var mixed $usucreaId           Identificador del usuario creador.
 * @var mixed $usumodifId          Identificador del usuario que modifica.
 * @var mixed $apostado            Valor apostado asociado al bono.
 * @var mixed $rollowerRequerido   Requisito de rollover para el bono.
 * @var mixed $codigo              Código asociado al bono.
 * @var mixed $UsuarioBono         Objeto que representa el bono del usuario.
 * @var mixed $UsuarioBonoMysqlDAO Objeto para manejar operaciones de base de datos relacionadas con bonos.
 * @var mixed $inse                Resultado de la inserción del bono en la base de datos.
 * @var mixed $PLAYTECHSERVICES    Objeto para interactuar con los servicios de Playtech.
 * @var mixed $response            Respuesta generada por la operación de asignación de bono.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\Usuario;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Playtech;
use Backend\integrations\casino\PLAYTECHSERVICES;

header('Content-type: application/json; charset=utf-8');


ini_set('display_errors', 'OFF');

$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

$usuarioId = $data->userId;
$bonoId = $data->bonoId;
$valor = $data->valor;
$roundsFree = $data->roundsFree;
$TemplateCode = $data->TemplateCode;
$GoldenChip = $data->isGoldenChip;
$games = $data->externoId;

$Usuario = new Usuario($usuarioId);

$estado = 'R';
$valor_bono = '0';
$valor_base = '0';
$errorId = '0';
$idExterno = '0';
$mandante = $Usuario->mandante;
$usucreaId = '0';
$usumodifId = '0';
$apostado = '0';
$rollowerRequerido = '0';
$codigo = '0';

$UsuarioBono = new UsuarioBono();

$UsuarioBono->setUsuarioId($usuarioId);
$UsuarioBono->setBonoId($bonoId);
$UsuarioBono->setValor($valor);
$UsuarioBono->setValorBono($valor_bono);
$UsuarioBono->setValorBase($valor_base);
$UsuarioBono->setEstado($estado);
$UsuarioBono->setErrorId($errorId);
$UsuarioBono->setIdExterno($idExterno);
$UsuarioBono->setMandante($mandante);
$UsuarioBono->setUsucreaId($usucreaId);
$UsuarioBono->setUsumodifId($usumodifId);
$UsuarioBono->setApostado($apostado);
$UsuarioBono->setRollowerRequerido($rollowerRequerido);
$UsuarioBono->setCodigo($codigo);
$UsuarioBono->setVersion(0);
$UsuarioBono->setExternoId(0);

$UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);
$inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


$PLAYTECHSERVICES = new PLAYTECHSERVICES();
$response = $PLAYTECHSERVICES->givefreespins($bonoId, $roundsFree, $valor, '', $usuarioId, $games, $inse, $GoldenChip, $TemplateCode);

echo json_encode($response);

