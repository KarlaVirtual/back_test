<?php

/**
 * Descarga y muestra una imagen desde Google Cloud Storage.
 *
 * Este script permite descargar una imagen (JPG o PNG) desde Google Cloud Storage y mostrarla en el navegador.
 *
 * @param string $_REQUEST["r"] Parámetro cifrado que contiene el nombre de la imagen.
 *
 * @return void La imagen se envía directamente al navegador.
 */

/* establece el tipo de contenido y crea una instancia de ConfigurationEnvironment. */
header('Content-Type: text/html');

use Backend\dto\ConfigurationEnvironment;

$ConfigurationEnvironment = new ConfigurationEnvironment();

$parameterR = ($_REQUEST["r"]);

/* reemplaza espacios y luego cifra y descifra un parámetro. */
$parameterR = (str_replace(" ", "+", $parameterR));

$payload = $ConfigurationEnvironment->encrypt($parameterR);

$payload = $ConfigurationEnvironment->decrypt($parameterR);
if (false) {


    /* verifica si un string contiene ".jpg", luego manipula imágenes y archivos. */
    if (strpos($payload, ".jpg") !== false) {
        print_r($payload);
        //header('Content-Type:' . 'image/jpeg');

        $fp = fopen("gs://cedulas-1/hello_stream.txt", 'w');
        $img = file_get_contents(
            'gs://cedulas-1/' . $payload);

        $data = base64_encode('gs://cedulas-1/' . $payload);


        /*if (file_exists(__DIR__ . '/../../../../images/c/' . $payload)) {
            header('Content-Type:' . 'image/jpeg');
            $file = '/tmp/' . $payload;
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }*/

    }


    /* Verifica si el payload contiene ".png" y procesa una imagen PNG desde la nube. */
    if (strpos($payload, ".png") !== false) {

        header('Content-Type:' . 'image/png');
        $img = file_get_contents(
            'gs://cedulas-1/c/' . $payload);

        $data = base64_encode('gs://cedulas-1/c/' . $payload);

        /*    if (file_exists(__DIR__ . '/../../../../images/c/' . $payload)) {
                header('Content-Type:' . 'image/png');
                $file = '/tmp/' . $payload;
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }*/


    }
}

if (true) {


    if (strpos($payload, ".jpg") !== false || strpos($payload, ".png") !== false) {
        try {


            /* Código configura autenticación para cliente de Google y define bucket y objeto. */
            $bucketName = 'cedulas-1';
            $objectName = 'c/' . $payload;
// Authenticate your API Client
            $client = new Google_Client();
            $client->setAuthConfig('/etc/private/virtual.json');
            $client->useApplicationDefaultCredentials();

            /* Autenticación y solicitud para obtener un objeto específico de Google Cloud Storage. */
            $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

            $storage = new Google_Service_Storage($client);

            try {
                // Google Cloud Storage API request to retrieve the list of objects in your project.
                $object = $storage->objects->get($bucketName, $objectName);
            } catch (Google_Service_Exception $e) {
                /* Maneja excepciones de Google Service, verificando si el bucket no existe. */

                // The bucket doesn't exist!
                if ($e->getCode() == 404) {
                    exit(sprintf("Invalid bucket or object names (\"%s\", \"%s\")\n", $bucketName, $objectName));
                }
                throw $e;
            }

// build the download URL

            /* Genera una URL y descarga un objeto de Google Cloud Storage, verificando errores. */
            $uri = sprintf('https://storage.googleapis.com/%s/%s?alt=media&generation=%s', $bucketName, $objectName, $object->generation);
            $http = $client->authorize();
            $response = $http->get($uri);

            if ($response->getStatusCode() != 200) {
                exit('download failed!' . $response->getBody());
            }

            /* establece el tipo de contenido a imagen PNG y comienza a imprimir datos. */
            header('Content-Type:' . 'image/png');
            print_r((string)$response->getBody());

        } catch (Exception $e) {
            /* Captura excepciones y las imprime para diagnóstico de errores en el código. */

            print_r($e);
        }
    }
}