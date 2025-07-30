<?php

/**
 * Descarga un archivo de Google Cloud Storage.
 *
 * Este script permite descargar un archivo ZIP desde Google Cloud Storage y enviarlo al navegador.
 *
 * @param string $_REQUEST["r"] Parámetro cifrado que contiene el nombre del archivo.
 *
 * @return void El archivo se envía directamente al navegador.
 */

/* establece el tipo de contenido y crea una instancia de `ConfigurationEnvironment`. */
header('Content-Type: text/html');

use Backend\dto\ConfigurationEnvironment;

$ConfigurationEnvironment = new ConfigurationEnvironment();

$parameterR = ($_REQUEST["r"]);

/* reemplaza espacios, luego encripta y desencripta el parámetro R. */
$parameterR = (str_replace(" ", "+", $parameterR));

$payload = $ConfigurationEnvironment->encrypt($parameterR);

$payload = $ConfigurationEnvironment->decrypt($parameterR);

if (true) {
    if (strpos($payload, ".zip") !== false) {

        /* descarga un archivo de Google Cloud Storage y lo envía al navegador. */
        try {

            $bucketName = 'cedulas-1';
            $objectName = 'machine/' . $payload;


            if (!file_exists('filesTMP/')) {
                mkdir('filesTMP/', 0755, true);
            }

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp  gs://cedulas-1/machine/' . $payload . " filesTMP/" . $payload);

            header("Content-type: application/zip");
            //header("Content-Disposition: attachment; filename=$payload");
            header("Content-length: " . filesize("filesTMP/" . $payload));
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile("filesTMP/" . "$payload");

        } catch (Exception $e) {
            /* Captura excepciones en PHP y las imprime para diagnóstico y depuración. */

            print_r($e);
        }
    }
}