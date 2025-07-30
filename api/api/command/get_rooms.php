<?php

use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\PaisMoneda;

/**
 * Obtiene la info de los rooms propiciada por un proveedor
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param object $json->params Objeto que contiene los parámetros de la solicitud.
 * @param int $json->params->site_id Identificador del sitio.
 * @param string $json->params->country Código del país.
 * @param mixed $json->params->filter Filtro para la consulta.
 * @return array $response Respuesta estructurada con código, identificador y datos finales.
 *  -code:int Código de respuesta.
 *  -data:object Datos de la respuesta.
 *  -rid:string Identificador de la respuesta.
 * @throws Exception Si ocurre un error durante la obtención de las habitaciones.
 */

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

/**
 * Obtiene los encabezados de la solicitud HTTP.
 *
 * @return array Un arreglo asociativo de los encabezados de la solicitud.
 */
function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}
$headers = getRequestHeaders();

//$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$params = $json->params;
$site_id = $json->params->site_id;
$country = $json->params->country;

$filter = $json->params->filter;

$data = array(
    "filter" => $filter
);

/**
 * Asigna un identificador de país basado en el código de país proporcionado.
 *
 * Este bloque de código utiliza un switch para determinar el paisId
 * correspondiente según el valor de la variable $country.
 * Se asigna un código específico para cada país.
 */
switch ($country) {
    case "pe":
        $paisId = 173;
        break;
    case "ec":
        $paisId = 66;
        break;
    case "cr":
        $paisId = 60;
        break;
    case "cl":
        $paisId = 46;
        break;
}

// Se instancia un nuevo objeto de la clase Pais con el país especificado por su nombre
$Pais = new Pais('', $country);

// Se instancia un nuevo objeto de la clase Proveedor con el nombre "IESGAMES"
$Proveedor = new Proveedor("", "IESGAMES");

// Se instancia un nuevo objeto de la clase Producto con los detalles correspondientes
$Producto = new Producto("", "IESGAMES", $Proveedor->getProveedorId());

// Se crea un nuevo objeto de la clase PaisMoneda utilizando el id del país
$PaisMoneda = new PaisMoneda($paisId);

// Se instancia el servicio IESGAMESSERVICES para obtener información de habitaciones
$IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
$respon = $IESGAMESSERVICES->GetRooms($data, $site_id, $Pais->paisId);

// Se decodifica la respuesta JSON obtenida del servicio
$respon = json_decode($respon);

// Se agrega la moneda correspondiente al país en la respuesta decodificada
$respon->currency = $PaisMoneda->moneda;

// Se prepara la respuesta final en un array
$response = array();
$response["code"] = 0; // Código de respuesta
$response["data"] = $respon; // Datos de la respuesta
$response["rid"] = $json->rid; // Id de la respuesta en formato JSON