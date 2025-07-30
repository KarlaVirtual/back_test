<?php

/**
 * Genera hashes SHA-256 para archivos en el backend.
 *
 * Este script genera un archivo de log con los hashes SHA-256 de los archivos en el directorio especificado, 
 * dependiendo del entorno (desarrollo o producción).
 *
 * @return void El archivo de log se genera en el directorio correspondiente.
 */

/* verifica el entorno y ejecuta un comando para generar sumas SHA-256. */
$ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

if ($ConfigurationEnvironment->isDevelopment()) {

    exec("cd /home/devadmin/ && find ./cert -type f -print0 | xargs -0 -n1 -i sha256sum {} > ./log-sha/backendOriginal/" . time() . ".sha256");
} else {
    /* Ejecuta un comando para calcular hash SHA-256 de archivos en un directorio. */

    exec("cd /home/home2/backend/ && find ./api -type f -print0 | xargs -0 -n1 -i sha256sum {} > ./log-sha/backendOriginal/" . time() . ".sha256");
}
