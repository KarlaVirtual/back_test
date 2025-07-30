<?php
ini_set('memory_limit', '-1');
use CurlWrapper;

exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . 'entroenuploadconfig' . "' '#dev' > /dev/null & ");

/*
 import { serve } from "https://deno.land/std@0.224.0/http/server.ts";
serve(async (req)=>{
  const body = await req.json();
  console.log(body?.type);
  const nuevoValor = JSON.stringify(body?.record?.data); // o cualquier campo que estés escuchando
  const GH_TOKEN = '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';
  const GH_OWNER = 'VirtualsoftSS';
  const GH_REPO = 'FrontendConfigs';
  const GH_BRANCH = body?.record?.name;
  const rawName = body?.record?.name ?? "";
  const parts = rawName.split("-");
  const secondPart = parts[1] ?? "";
  const FILE_PATH = `config_${secondPart}`; // resultado final
  //body?.record?.name != 'main-19' && body?.record?.name != 'main-23'
  if (body?.record?.name != 'main-19' && body?.record?.name != 'main-23') {
    console.log('NOUPDATED-' + FILE_PATH);
    return new Response(JSON.stringify({
      config: FILE_PATH,
      message: 'NOUPDATED',
      status: 'ok'
    }), {
      status: 200,
      headers: {
        'Content-Type': 'application/json'
      }
    });
  }
  const headers = {
    Authorization: `Bearer ${GH_TOKEN}`,
    'Accept': 'application/vnd.github+json'
  };
  // 1. Obtener SHA actual del archivo en la rama
  const getFileResp = await fetch(`https://api.github.com/repos/${GH_OWNER}/${GH_REPO}/contents/${FILE_PATH}?ref=${GH_BRANCH}`, {
    method: 'GET',
    headers
  });
  const fileData = await getFileResp.json();
  const currentSha = fileData.sha;
  // 2. Preparar contenido nuevo en base64
  const nuevoContenido = btoa(unescape(encodeURIComponent(nuevoValor)));
  // 3. Hacer PUT para actualizar archivo
  const updateResp = await fetch(`https://api.github.com/repos/${GH_OWNER}/${GH_REPO}/contents/${FILE_PATH}`, {
    method: 'PUT',
    headers,
    body: JSON.stringify({
      message: `Actualización desde tool: `,
      content: nuevoContenido,
      branch: GH_BRANCH,
      sha: currentSha
    })
  });
  const updateData = await updateResp.json();
  console.log('UPDATED-' + FILE_PATH);
  console.log(updateData);
  return new Response(JSON.stringify({
    config: FILE_PATH,
    status: 'ok'
  }), {
    status: 200,
    headers: {
      'Content-Type': 'application/json'
    }
  });
});



 */

$GH_TOKEN = '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';
$GH_OWNER = 'VirtualsoftSS';
$GH_REPO = 'FrontendConfigs';

$input = file_get_contents('php://input');
$body = json_decode($input, true);

$type = $body['type'] ?? null;
$record = $body['record'] ?? null;
$nuevoValor = json_encode($body['record']['data']); // o cualquier campo que estés escuchando

$GH_BRANCH = $record['name'] ?? '';
$rawName = $record['name'] ?? '';
$parts = explode('-', $rawName);
$secondPart = $parts[1] ?? '';
$FILE_PATH = 'config_' . $secondPart;
exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $type . "' '#dev' > /dev/null & ");
exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $FILE_PATH . "' '#dev' > /dev/null & ");
//$record['name'] != 'main-19' && $record['name'] != 'main-23'
//&& $record['name'] != 'main-27' && $record['name'] != 'main-27'
if (false
) {
    echo json_encode([
        'config' => $FILE_PATH,
        'message' => 'NOUPDATED',
        'status' => 'ok'
    ]);
    exit;
}

$headers = [
    'Authorization: Bearer ' . $GH_TOKEN,
    'Accept: application/vnd.github+json',
    'User-Agent: Awesome-Octocat-App',
];

$file_url = "https://api.github.com/repos/{$GH_OWNER}/{$GH_REPO}/contents/{$FILE_PATH}?ref={$GH_BRANCH}";
$curl = new CurlWrapper($file_url);


// Configurar opciones
$curl->setOptionsArray(array(
    CURLOPT_URL => $file_url,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET'
));
// Ejecutar la solicitud
$response2 = $curl->execute();

exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'PRIMER " . $response2 . "' '#dev' > /dev/null & ");

$file_data = json_decode($response2, true);

$currentSha = $file_data['sha'] ?? '';

$encoded_content = base64_encode($nuevoValor);

$update_url = "https://api.github.com/repos/{$GH_OWNER}/{$GH_REPO}/contents/{$FILE_PATH}";
$update_data = json_encode([
    'message' => 'Actualización desde tool: ',
    'content' => $encoded_content,
    'branch' => $GH_BRANCH,
    'sha' => $currentSha,
]);

$ch = new CurlWrapper($update_url);


// Configurar opciones
$ch->setOptionsArray(array(
    CURLOPT_URL => $update_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS => $update_data,
));

// Ejecutar la solicitud
$response2 = $ch->execute();
exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $response2 . "' '#dev' > /dev/null & ");

$update_response = json_decode($response2, true);

echo json_encode([
    'config' => $FILE_PATH,
    'message' => 'UPDATE',
    'status' => 'ok'
]);

?>
