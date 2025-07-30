<?php

/**
 * Proporciona métodos para realizar OCR (Reconocimiento Óptico de Caracteres).
 *
 * @category Seguridad
 * @package  Auth
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-14
 */

namespace Backend\integrations\auth;

use Backend\dto\Pais;
use Backend\dto\Proveedor;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioMensaje;

/**
 * OcrBigId
 *
 * La clase OcrBigId en PHP proporciona métodos para realizar OCR (Reconocimiento Óptico de Caracteres) y
 * verificación de documentos de identidad utilizando la plataforma BigID.
 */
class OcrBigId
{
    /**
     * URL para obtener el token de acceso.
     *
     * @var string $URLTOKEN .
     */
    private $URLTOKEN = 'https://accesstoken.bigdatacorp.com.br';

    /**
     * URL base de la API de BigDataCorp.
     *
     * @var string $URL .
     */
    private $URL = 'https://bigid.bigdatacorp.com.br';

    /**
     * Nombre de usuario para la API de BigDataCorp.
     *
     * @var string $login .
     */
    private $login = 'lotosports.bet@gmail.com';

    /**
     * Contraseña para la API de BigDataCorp.
     *
     * @var string $password .
     */
    private $password = '9x8ofp7nw';

    /**
     * Tiempo de expiración del token de acceso en segundos.
     *
     * @var integer $expires.
     */
    private $expires = 87500;

    /**
     * Función constructora.
     *
     * La función constructora establece diferentes valores dependiendo de si el es de desarrollo o de producción.
     *
     * No devuelven ningún valor, el constructor se encargan de inicializar un objeto, En lugar de @return.
     */
    public function __construct()
    {
    }

    /**
     * La función OCR procesa docuemntos.
     *
     * La función `ocr` procesa dos imágenes y un tipo de documento y número de identificación para verificar y extraer
     * información, devolviendo un objeto respuesta con estado de éxito y mensaje.
     *
     * @param string $img1   Se usa para el reconocimiento óptico de caracteres (OCR), Toma varios parámetros y realiza
     *                       la validación OCR en dos imágenes junto con algunas comprobaciones adicionales.
     * @param string $img2   Toma múltiples parámetros y realiza varias operaciones para
     *                       verificar documentos de identidad.
     * @param string $tipoD  Representa el tipo de documento que se está procesando, como tipo de documento, como un
     *                       carné de identidad, un pasaporte, un carné de conducir, etc.
     * @param int    $cedula Representar el número de identificación de una persona, como el número de identificación
     *                       nacional o el número de la seguridad social. Se utiliza se utiliza como parte de los datos
     *                       enviados para el procesamiento de reconocimiento óptico de caracteres (OCR) para verificar
     *                       la identidad del individuo.
     *
     * @return array La función `ocr` devuelve un array llamado `$data` que contiene las siguientes claves
     *  y valores.
     *   1. [bool] success: Indica si la operación fue exitosa
     *   2. [int] Code: Código de resultado de la operación
     *   3. [string] ticketId: ID del ticket asociado, solo presente si la operación es exitosa.
     *   4. [string] resultMessage: Mensaje de resultado de la operación, solo presente si la operación es exitosa.
     */
    public function ocr($img1, $img2, $tipoD, $cedula)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $data["Code"] = 1;
        $data["resultMessage"] = 'Error';

        $Result = $this->Detection($img1);
        $val = $Result['Code'];
        $data["resultMessage"] = 'Error, no fue posible leer la imagen enviada.';

        if ($val == 0) {
            $dataT = array();
            $dataT['login'] = $this->login;
            $dataT['password'] = $this->password;
            $dataT['expires'] = $this->expires;
            $respuesta = $this->token($dataT, $this->URLTOKEN, '/Generate');
            $tokenT = $respuesta->token;

            list(, $img1) = explode(';', $img1);
            list(, $img1) = explode(',', $img1);
            list(, $img2) = explode(';', $img2);
            list(, $img2) = explode(',', $img2);

            $dataB = array();
            $dataB['SearchFace'] = true;
            $dataB['ForensicValidations'] = true;

            $dataB['CheckInfo'] = [
                "CPF" => $cedula,
            ];
            $dataB['Parameters'] = [
                "DOC_TYPE=" . $tipoD,
                "DOC_IMG_A=" . $img1,
                "DOC_IMG_B=" . $img2
            ];

            $Result = $this->ocrV($dataB, $this->URL, $tokenT, '/VerifyID');
            $resultF = $Result->DocInfo->CONTAINSFACE;
            $resultC = $Result->OcrValidations;
            $ResultCode = $Result->ResultCode;
            $CheckInfo = $Result->CheckInfo;

            $data["resultMessage"] = $Result->ResultMessage;
            if ($CheckInfo->CPFMATCH == '0' && $ResultCode == 70) {
                $data["resultMessage"] = 'Error, la cedula registrada no coincide con el documento enviado.';
            }

            if ($resultF != 'False' && $ResultCode == 70 && $CheckInfo != null && $CheckInfo->CPFMATCH == '1') {
                $response = $Result;
                $data = array();
                $data["success"] = true;
                $data["Code"] = 0;
                $data["ticketId"] = $response->TicketId;
                $data["resultMessage"] = $response->ResultMessage;
            } else {
            }
        }
        return $data;
    }

    /**
     * La función `ocrV` envía una petición POST.
     *
     * La función `ocrV` envía una petición POST con datos JSON a una URL especificada usando cURL en PHP y
     * devuelve la respuesta después de decodificarla de JSON.
     *
     * @param array  $data   El parámetro `data` de la función `ocrV` son los datos que se quieren enviar para
     *                       OCR (Reconocimiento Óptico de Caracteres). Estos datos deben estar en un formato que el
     *                       servicio OCR que el servicio OCR espera, normalmente en formato JSON. Puede contener la
     *                       imagen o el documento que necesita ser procesar.
     * @param string $url    El parámetro `url` de la función `ocrV` es la URL base a la que se enviará la solicitud de
     *                       OCR(Reconocimiento Óptico de Caracteres). Se utiliza para construir la URL completa
     *                       añadiendo al parámetro antes de realizar la solicitud POST.
     * @param string $tokenT El parámetro `tokenT` en la función `ocrV` es probablemente el token de autenticación
     *                       necesario para autorizar la petición API. Este token se obtiene normalmente a través de un
     *                       proceso de autenticación y se utiliza para autenticar al usuario que realiza la llamada a
     *                       la API. Se incluye en la solicitud con la clave 'Authorization'.
     * @param string $path   El parámetro `path` de la función `ocrV` representa la ruta del endpoint donde el
     *                       OCR (Reconocimiento Óptico de Caracteres). Se concatena con la URL base
     *                       para formar la URL completa de la solicitud de API.
     *
     * @return array La función `ocrV` devuelve la respuesta de la API de OCR (Reconocimiento Óptico de Caracteres)
     * tras realizar una solicitud a la API. La respuesta
     * respuesta es decodificada desde el formato JSON y devuelta por la función.
     */
    public function ocrV($data, $url, $tokenT, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_TIMEOUT => 300,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $tokenT,
                'Content-Type: application/json'
            ),
        ));

        $time = time();
        syslog(LOG_WARNING, " ocrVREQ :" . $time . ' ' . json_encode($data));

        $response = curl_exec($curl);
        syslog(LOG_WARNING, " ocrV R:" . $time . ' ' . $response);

        curl_close($curl);
        return json_decode($response);
    }


    /**
     * La función `Detección` procesa una imagen para su verificación.
     *
     * La función `Detección` procesa una imagen para su verificación y devuelve un estado de éxito junto
     * con un código basado en el resultado del análisis.
     *
     * @param string $img1 La función `Detection` es una función PHP que procesa una imagen con fines de verificación.
     *                     propósitos de verificación. Enviar los datos de la imagen a una URL específica para su
     *                     análisis y luego comprueba el resultado para determinar si la imagen está manipulada o no.
     *
     * @return array La función `Detection` devuelve un array `` que contiene las claves
     * 1. [bool] success: Indica si la operación fue exitosa
     * 2. [int] Code: Código de resultado de la operación
     * 3. [int] error: Indica código error.
     * Los valores iniciales establecidos para estas claves son `false`, `1`, y `1`
     * respectivamente.
     */
    public function Detection($img1)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $data["Code"] = 1;

        $dataT = array();
        $dataT['login'] = $this->login;
        $dataT['password'] = $this->password;
        $dataT['expires'] = $this->expires;
        $respuesta = $this->token($dataT, $this->URLTOKEN, '/Generate');
        $tokenT = $respuesta->token;

        list(, $img1) = explode(';', $img1);
        list(, $img1) = explode(',', $img1);
        $dataB = array();
        $dataB['ModelVariant'] = 'balanced';
        $dataB['Parameters'] = [
            "DOC_IMG=" . $img1
        ];
        $Result = $this->valDoc($dataB, $this->URL, $tokenT, '/VerifyID/ImageAnalysisV2');
        $resultCode = $Result->ImageAnalysis->MANIPULATEDIMAGE;

        if ($resultCode == 'DIGITALY OK') {
            $data = array();
            $data["success"] = true;
            $data["Code"] = 0;
        }
        return $data;
    }

    /**
     * La función `valDoc` envía una petición POST.
     *
     * La función `valDoc` envía una petición POST con datos JSON a una URL especificada usando cURL en PHP
     * y devuelve la respuesta.
     *
     * @param array  $data   La función `valDoc` que ha proporcionado es una función PHP que envía una petición POST
     *                       usando cURL a una URL especificada con los datos, token y ruta proporcionados. Luego
     *                       registra la solicitud y respuesta usando syslog y devuelve la respuesta JSON decodificada.
     * @param string $url    El parámetro `url` en la función `valDoc` es la URL base a la que se enviará la petición.
     *                       será enviada. Se concatena con el parámetro `path` para formar la URL completa de la
     *                       petición POST solicitud.
     * @param string $tokenT El parámetro `tokenT` de la función `valDoc` se utiliza para pasar el token de
     *                       autenticación
     *                       (normalmente un token de portador).
     *                       (normalmente un token de portador) necesario para realizar la solicitud API a la URL
     *                       especificada. Este token se incluye en las cabeceras de la solicitud con la clave
     *                       'Authorization' para autenticar al usuario y autorizar el acceso.
     * @param string $path   El parámetro `path` de la función `valDoc` representa la ruta del punto final donde
     *                       se realizará la solicitud HTTP POST.
     *                       se enviará la solicitud HTTP POST. Se concatena con la URL base para formar la URL
     *                       completa de la petición.
     *
     * @return array La función `valDoc` devuelve la respuesta de la petición cURL después de ejecutarla.
     *  ejecutarla. La respuesta es decodificada desde el formato JSON usando `json_decode` antes de ser devuelta.
     */
    public function valDoc($data, $url, $tokenT, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $tokenT,
                'Content-Type: application/json'
            ),
        ));
        $time = time();
        syslog(LOG_WARNING, " valDocREQ :" . $time . ' ' . json_encode($data));

        $response = curl_exec($curl);

        syslog(LOG_WARNING, " valDoc R:" . $time . ' ' . $response);

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * La función `token` envía una petición POST con datos JSON a una URL y ruta especificadas, devolviendo
     * la respuesta como un objeto JSON decodificado.
     *
     * @param array  $data El parámetro `data` de la función `token` son los datos que quieres enviar en
     *                     la petición POST. Debe ser una matriz asociativa que contenga los pares clave-valor que
     *                     desea enviar a la URL especificada. Estos datos serán codificados como JSON antes de enviar
     *                     la solicitud. solicitud.
     * @param string $url  El parámetro `url` de la función `token` es la URL base a la que se enviará la solicitud.
     *                     será enviada. Se utiliza para construir la URL completa añadiendo el parámetro `path` antes
     *                     de realizar la solicitud POST.hacer la petición POST.
     * @param string $path El parámetro `path` de la función `token` representa la ruta del punto final al que se
     *                     enviará la solicitud POST.
     *                     se enviará la solicitud POST. Es una cadena que especifica la ruta específica o recurso en
     *                     el servidor al que debe dirigirse la solicitud. Por ejemplo, si el punto final de la API.
     *
     * @return array La función `token` devuelve la respuesta de la petición cURL después de enviar una
     * solicitud POST a la URL especificada con los datos proporcionados en formato JSON. La respuesta es entonces
     * decodificada del formato JSON y devuelta por la función.
     */
    public function token($data, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
}
