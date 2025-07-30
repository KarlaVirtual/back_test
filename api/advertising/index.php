<?php

    ini_set('display_errors', 'OFF');


    require '../vendor/autoload.php';

    $url = parse_url($_SERVER['REQUEST_URI']);
    $urlParts = explode('/', $url['path']);

    $file = __DIR__ . '/cases/' . $urlParts[oldCount($urlParts) - 2] . '/' . $urlParts[oldCount($urlParts) - 1] . '.php';
    
    try {
        if(file_exists($file)) require_once $file;
    } catch (Exception $ex) {
        die($ex);
        header('Content-Type: application/json');
        echo json_encode(['code' => 200, 'message' => 'Error general']);
    }

    die;
?>