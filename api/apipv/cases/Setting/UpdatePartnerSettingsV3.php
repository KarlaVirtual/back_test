<?php

use Backend\dto\ConfigMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\ConfigMandanteMySqlDAO;




$params = base64_decode($params);
$params = json_decode($params, true);

$Data = $params['Data'];
$Partner = $params['Partner'];


// Lanza una excepción si los datos están vacíos
if ($Data === '') throw new Exception('No hay datos para atualizar', '01');
if (empty($Data)) throw new Exception('No hay datos para atualizar', '01');

$ConfigurationEnvironment = new ConfigurationEnvironment();

if(!$ConfigurationEnvironment->isDevelopment()){

// Datos de conexión a Supabase
    $host = 'aws-0-us-west-1.pooler.supabase.com';
    $db   = 'postgres';
    $user = 'postgres.uruqgfsfodmdxlcysvuw';
    $pass = 'w$S1x4Ee1S18';
    $port = '5432';
}

// Datos de conexión a Supabase
$host = 'aws-0-us-east-1.pooler.supabase.com';
$db   = 'postgres';
$user = 'postgres.cmzhnytljivilsuwwnih';
$pass = 'ccCHcr4sevMeFy6u';
$port = '5432';


try {
    // Crear conexión
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Consulta UPDATE
    $sql = "UPDATE shared_config SET data = :nuevo_data WHERE name = :id";

    // Preparar y ejecutar
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nuevo_data' => $Data,
        ':id' => "main-{$Partner}"  // Reemplaza con el ID que deseas actualizar
    ]);

// Inicializa la respuesta con valores predeterminados
    $response["HasError"] = false;
    $response["AlertType"] = "";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


} catch (PDOException $e) {
// Inicializa la respuesta con valores predeterminados
    $response["HasError"] = true;
    $response["AlertType"] = "";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
}
?>

