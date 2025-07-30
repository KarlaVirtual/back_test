<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\utils\RedisConnectionTrait;

/**
 * Setting/GetPartnerSettingsV2
 *
 * Este script obtiene configuraciones específicas de un socio desde Redis o un repositorio Git.
 *
 * @param $params array Datos JSON decodificados que incluyen:
 * @param string $params->Partner Identificador del socio.
 *
 * @return array $response Estructura de respuesta que incluye:
 *                         - "HasError" (boolean): Indica si ocurrió un error.
 *                         - "AlertType" (string): Tipo de alerta ("success" o "error").
 *                         - "AlertMessage" (string): Mensaje asociado a la alerta.
 *                         - "ModelErrors" (array): Lista de errores del modelo (vacío si no hay errores).
 *                         - "Data" (array): Configuración obtenida del socio.
 *
 * @throws none
 */

/* recibe datos JSON, los decodifica y establece la configuración del entorno. */
$params = file_get_contents('php://input');
$params = json_decode($params, true);
$Partner = $params['Partner'];
$ConfigurationEnvironment = new ConfigurationEnvironment();
$environment = $ConfigurationEnvironment->isDevelopment() ? 'enviromentdev' : 'main';
$response=array();
if($Partner ==19){

    try {


// Tu config de Supabase
        $SUPABASE_URL = 'https://uruqgfsfodmdxlcysvuw.supabase.co';
        $SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw';

        $headers = [
            "apikey: $SUPABASE_KEY",
            "Authorization: Bearer $SUPABASE_KEY",
            "Content-Type: application/json"
        ];

// Paso 1: Obtener el JSON actual
        $curl = curl_init("$SUPABASE_URL/rest/v1/shared_config?name=eq.main-{$Partner}&select=data");

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response2 = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response2, true);

// Verifica que haya datos
        if (!isset($data[0]['data'])) {
            $response["HasError"] = true;
            $response["AlertType"] = "error";
            $response["AlertMessage"] = "error";
            $response["ModelErrors"] = [];
        }else{

            $currentData = $data[0]['data'];

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "success";
            $response["ModelErrors"] = [];
            $response['Data'] = json_decode($currentData,true);

        }

    } catch (Exception $e) {
    }

}else {

    $redisParam = ['ex' => 1800];


/* Se conecta a Redis, verifica datos y responde según su validez. */
    $redisPrefix = $environment . "-STRCONFIG_";

    $redis = RedisConnectionTrait::getRedisInstance(true, 'tlsv1.2://aaafe5qyeqazenanecabjdpl4v5rr2rigkpxk72ufgpnbxesnmacsxa-p.redis.us-ashburn-1.oci.oraclecloud.com', 6379, '');

    if ($redis != null && $_REQUEST['testtest'] != 1) {

        $cachedKey = $redisPrefix . $Partner;
        $cachedValue = ($redis->get($cachedKey));
        $Data = json_decode($cachedValue, true);

        if ($Data == '' || $Data == null || $Data == 'null' || empty($Data)) {
            $response["HasError"] = true;
            $response["AlertType"] = "error";
            $response["AlertMessage"] = "error";
            $response["ModelErrors"] = [];
        } else {
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "success";
            $response["ModelErrors"] = [];
            $response['Data'] = $Data;
        }
    } else {


    /* Código PHP que crea un directorio temporal para un repositorio de GitHub. */
        $gitRepo = 'https://daniel1430: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@github.com/VirtualsoftSS/FrontendConfigs.git';
        $currentDate = time();
        $folderName = "/tmp/{$currentDate}_{$Partner}";
        mkdir($folderName, 0777, true);

        $userName = 'deploy-auto';

    /* Clona un repositorio Git en un entorno específico y configura usuario y correo. */
        $userEmail = 'tecnologiageneral@virtualsoft.tech';
        $environment = $ConfigurationEnvironment->isDevelopment() ? 'enviromentdev' : 'main';
        $environment = $environment . '-' . $Partner;

        $commands = [
            "cd {$folderName} && git clone --branch {$environment}   --depth 1 --single-branch {$gitRepo}",
            "cd {$folderName}/FrontendConfigs",
            "git config user.name '{$userName}'",
            "git config user.email '{$userEmail}'",
            'git checkout ' . $environment,
            'git pull  --depth 1 -s ours origin ' . $environment,
        ];


    /* Ejecuta comandos en serie y almacena resultados en un archivo basado en un socio. */
        $cmdExec = implode(' && ', $commands);
        $output = [];
        $vars = 0;

        exec($cmdExec, $output, $vars);

    /* lee un archivo JSON y maneja errores mediante un arreglo de respuesta. */
        $fileName = "config_{$Partner}";
        $file = file_get_contents("{$folderName}/FrontendConfigs/{$fileName}");
        $Data = json_decode($file, true) ?: [];

        exec("cd /tmp && rm -rf {$currentDate}_{$Partner}");

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "success";
        $response["ModelErrors"] = [];
        $response['Data'] = $Data;
    }
}
?>