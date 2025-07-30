<?php

use Backend\dto\ConfigMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\ConfigMandanteMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Backend\utils\SlackVS;

require_once __DIR__ . '../../../../vendor/autoload.php';
ini_set('memory_limit', '-1');


$argParams = $argv[1];

$params = file_get_contents('/tmp/' . $argParams);

/**
 * Limpia las cadenas en un array reemplazando ciertos caracteres especiales.
 *
 * @param array $array El array que contiene las cadenas a limpiar.
 * @return array El array con las cadenas limpias.
 */
function cleanStrings($array)
{
    if (is_array($array) === false || oldCount($array) === 0) return $array;

    foreach ($array as $key => $value) {
        if (is_array($value) === true || oldCount($value) > 0) $array[$key] = cleanStrings($value);
        if (is_string($value)) $array[$key] = str_replace(["\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c"], ["\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b"], $value);
    }

    return $array;
}

/*El código decodifica, convierte y valida datos JSON para actualizar configuraciones de un socio.*/
$params = base64_decode($params);
$params = json_decode($params, true);

$Data = $params['Data'];
$Partner = $params['Partner'];

if ($Data === '') throw new Exception('No hay datos para atualizar', '01');
if (empty($Data)) throw new Exception('No hay datos para atualizar', '01');

$ConfigurationEnvironment = new ConfigurationEnvironment();

$array_combine = [];


/*El código combina y limpia datos de configuración de un socio para su actualización.*/
if (isset($Data['languagesDataBackoffice']) && oldCount($Data['languagesDataBackoffice']) > 0) {
    $array_combine['languagesDataBackoffice'] = $Data['languagesDataBackoffice'];
}

if (isset($Data['bannersDesktop']) && oldCount($Data['bannersDesktop']) > 0) {
    $array_combine['bannersDesktop'] = $Data['bannersDesktop'];
}

if (isset($Data['termsandconditionBackoffice']) && oldCount($Data['termsandconditionBackoffice']) > 0) $array_combine['termsandconditionBackoffice'] = $Data['termsandconditionBackoffice'];

$array_combine = cleanStrings($array_combine);


/*El código configura el entorno, crea un directorio y verifica la rama de Git.*/
$ConfigurationEnvironment = new ConfigurationEnvironment();
$environment = $ConfigurationEnvironment->isDevelopment() ? 'enviromentdev' : 'main';
$SlackVS = new SlackVS('monitoring-configs-' . $environment);

$gitRepo = 'https://daniel1430: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@github.com/VirtualsoftSS/FrontendConfigs.git';
$currentDate = time();
$folderName = "{$currentDate}_{$Partner}";
mkdir("/tmp/{$folderName}", 0777, true);
// Verificar si la rama {$environment} existe en remoto y cambiar a ella; si no, crearla desde 'main'

$environment = "{$environment}-{$Partner}";

/*clona un repositorio Git en una carpeta temporal según el entorno especificado.*/
if($environment == 'main') {
    // Clonar solo la rama 'main' para minimizar la cantidad de datos descargados
    exec("cd /tmp/{$folderName} && git clone --branch main --depth 1  {$gitRepo}");
    print_r("cd /tmp/{$folderName} && git clone --branch main --depth 1  {$gitRepo}");
} else {
    // Clonar solo la rama 'main' para minimizar la cantidad de datos descargados
    exec("cd /tmp/{$folderName} && git clone --branch {$environment} --depth 1 {$gitRepo}");
    print_r("cd /tmp/{$folderName} && git clone --branch {$environment} --depth 1 {$gitRepo}");

}


/*El código crea y cambia a una rama Git, luego guarda datos en un archivo JSON.*/
$cmdBranch = <<<BASH
cd /tmp/{$folderName}/FrontendConfigs
if git ls-remote --heads origin {$environment} | grep -q {$environment}; then
    git checkout {$environment}
else
    git checkout -b {$environment}
    git push -u origin {$environment}
fi
BASH;

exec($cmdBranch);
if (!empty($cmdBranch)) exec("cd /tmp/{$folderName}/FrontendConfigs/ && {$cmdBranch}");

$fileName = "config_{$Partner}";
$file = fopen("/tmp/{$folderName}/FrontendConfigs/{$fileName}", 'w');
$basestr = json_encode($Data);


fwrite($file, $basestr);
fclose($file);

/*El código configura Git, realiza commit y push de cambios en segundo plano.*/
$userID = explode('_', $argParams)[oldCount(explode('_', $argParams)) - 1];

$commitMessage = "Config partner {$Partner} actualizada por {$userID}";
$userName = 'deploy-auto';
$userEmail = 'tecnologiageneral@virtualsoft.tech';

$SlackVS->sendMessage('*INIT:* ' . $commitMessage);
$commands = [
    "cd /tmp/{$folderName}/FrontendConfigs",
    "git config user.name '{$userName}'",
    "git config user.email '{$userEmail}'",
    "git add {$fileName}",
    "git commit -m \"{$commitMessage}\" || echo 'No hay cambios para hacer commit'",

    // Asegurar que la rama está actualizada antes de hacer el push
    "git pull -s ours origin {$environment}",
    "git push origin {$environment}",
];
$cmdExec = implode(' && ', $commands);
// Para ejecutarlo en segundo plano en sistemas Unix (Linux/macOS):
$cmdExec .= " &"; // El operador '&' ejecuta el comando en segundo plano


$output = [];
$vars = 0;


/*El código configura Redis, ejecuta comandos y envía mensajes de estado a Slack.*/
if ($_ENV['debug']) {

    print_r($cmdBranch);
    print_r($cmdExec);
    print_r($_SERVER);

    exit();
}

$redisParam = ['ex' => 180000];

$redisPrefix = $environment . "-STRCONFIG_" . $Partner;

$redis = RedisConnectionTrait::getRedisInstance(true, 'tlsv1.2://aaafe5qyeqazenanecabjdpl4v5rr2rigkpxk72ufgpnbxesnmacsxa-p.redis.us-ashburn-1.oci.oraclecloud.com', 6379, '');

if ($redis != null) {
    $contentFile = json_encode($Data);

    $redis->set($redisPrefix, $contentFile, $redisParam);
}

exec($cmdExec, $output, $vars);
exec("cd /tmp && sudo rm -rf ".$argParams);
$SlackVS->sendMessage('*END:* ' . $commitMessage);
/*El código maneja respuestas de error y éxito en una estructura de respuesta JSON.*/
if (!in_array($vars, [0, 1])) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "error";
    $response["ModelErrors"] = [];
} else {
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "success";
    $response["ModelErrors"] = [];
}


