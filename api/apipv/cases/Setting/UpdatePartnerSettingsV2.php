<?php

use Backend\dto\ConfigMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\ConfigMandanteMySqlDAO;

$params = file_get_contents('php://input');

/**
 * Actualiza la configuración de un socio en segundo plano.
 *
 * @param string $params Cadena codificada en base64 que contiene los parámetros de entrada:
 * @param array $params['Data'] Datos de configuración.
 * @param string $params['Partner'] ID del socio.
 *
 *
 * @return array $response Respuesta JSON con los siguientes valores:
 *                         - bool $response['HasError'] Indica si hubo un error (true/false).
 *                         - string $response['AlertType'] Tipo de alerta (success/error).
 *                         - string $response['AlertMessage'] Mensaje de alerta.
 *                         - array $response['ModelErrors'] Lista de errores del modelo (puede ser vacía).
 * @throws Exception Si los datos están vacíos o no se pueden procesar.
 */

/**
 * Limpia las cadenas de un array, escapando caracteres especiales.
 *
 * @param array $array El array que se va a limpiar.
 * @return array El array limpio.
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

exit();
$paramsG = $params;
$params = base64_decode($params);
$params = json_decode($params, true);

$Data = $params['Data'];
$Partner = $params['Partner'];

// Lanza una excepción si los datos están vacíos
if ($Data === '') throw new Exception('No hay datos para atualizar', '01');
if (empty($Data)) throw new Exception('No hay datos para atualizar', '01');

// Crea un nombre único para el archivo a guardar en /tmp
$fileName = 'PREV_' . time() . "_config_{$Partner}_{$_SESSION['usuario']}";
$file = fopen("/tmp/{$fileName}", 'w');
fwrite($file, ($paramsG));
fclose($file);


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

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);


// Verifica que haya datos
        if (!isset($data[0]['data'])) {
            die("No se encontró la configuración.");
        }

        $currentData = $data[0]['data'];

// Paso 2: Merge con nueva data
        $newData = $Data;

        $currentData= json_decode($currentData, true);
        $mergedData = array_merge($currentData, $newData); // Merge plano. Para merge profundo, usar recursivo.

// Paso 3: Hacer update con el nuevo JSON
        $updatePayload = json_encode([
            'data' => json_encode($mergedData),
            'updated_at' => date('c') // ISO 8601
        ]);

        $curl = curl_init("$SUPABASE_URL/rest/v1/shared_config?name=eq.main-{$Partner}");

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => $updatePayload
        ]);
        $updateResponse = curl_exec($curl);

        curl_close($curl);
        $response=array();
} catch (Exception $e) {
}


// Ejecuta un script en segundo plano para actualizar la configuración del socio
exec('php -f ' . __DIR__ . '/UpdatePartnerSettingsV2Background.php ' . $fileName . '> /dev/null 2>/dev/null &');

// Inicializa la respuesta con valores predeterminados
$response["HasError"] = false;
$response["AlertType"] = "";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

?>

