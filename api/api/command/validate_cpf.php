<?php
//require('../../vendor/autoload.php');
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\integrations\general\bigboost\Bigboost;
use Backend\integrations\general\hub\Hub;
use Backend\integrations\payment\PAYBROKERSSERVICES;

//header('Content-Type: application/json');

//$data = (file_get_contents('php://input'));
//$data = json_decode($data);
// = $data->cedula;

/**
 * command/validate_cpf
 *
 * Verificación de datos de la cédula del usuario
 *
 * Este recurso permite verificar los datos relacionados con la cédula de un usuario, de acuerdo con el proveedor especificado
 * (BIGBOOSTCPF, HUBCPF, PAYBROKERSCPF). Dependiendo del proveedor, se envían solicitudes a diferentes servicios de verificación
 * y se procesan los datos de la respuesta para retornar la información del usuario, como su nombre y fecha de nacimiento.
 *
 * @param object $json : Objeto JSON recibido con los parámetros de la solicitud.
 * @param string $json ->params->docnumber : Número de la cédula del usuario.
 * @param string $json ->params->site_id : Identificador del sitio o aplicación.
 * @param string $json ->params->country : Código del país donde se realiza la verificación.
 * @param string $json ->params->DateOfBirth : Fecha de nacimiento del usuario en formato "dd-mm-yyyy".
 *
 * @return object  $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *data* (array): Contiene los datos de la respuesta o error.
 *     - *birth_date* (string): Fecha de nacimiento del usuario, en formato "yyyy-mm-dd".
 *     - *first_name* (string): Primer nombre del usuario.
 *     - *last_name* (string): Apellido completo del usuario.
 *
 *
 * @throws Exception Si ocurre un error durante la creación de objetos o servicios.
 * @throws Exception Si no se puede procesar la cédula debido a un error del proveedor.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores del JSON a variables y crea un objeto 'Pais'. */
$rid = $json->rid;
$cedula = $json->params->docnumber;
$site_id = $json->params->site_id;
$country = $json->params->country;
$fechaNacimiento = $json->params->DateOfBirth;


$Pais = new \Backend\dto\Pais("", $country);

/* Se crean instancias de clases para manejar datos de clasificación y proveedores. */
$Clasificador = new Clasificador("", "PROVCPF");
$MandanteDetalle = new MandanteDetalle('', $site_id, $Clasificador->clasificadorId, $Pais->paisId, 'A');

$Proveedor = new \Backend\dto\Proveedor($MandanteDetalle->valor);


switch ($Proveedor->abreviado) {
    case "BIGBOOSTCPF":
        /* llama a una API para obtener información de un usuario mediante su cédula. */


        $api = new Bigboost();

        $response = $api->cedula($cedula, $rid, $site_id);

        break;

    case "HUBCPF":
        /* Convierte una fecha de nacimiento y llama a una API con datos específicos. */


        $fechaNacimiento = date("d-m-Y", strtotime($fechaNacimiento));
        $fechaNacimiento = str_replace("-", "/", $fechaNacimiento);
        $api = new Hub();


        $response = $api->cedula($cedula, $rid, $fechaNacimiento);
        break;


    case "PAYBROKERSCPF":

        /* obtiene y separa el nombre de un usuario a partir de una respuesta API. */
        $api = new PAYBROKERSSERVICES();
        $response = $api->GetChekingCPF($cedula, $site_id);

        $nombre = explode(' ', $response->name);
        $first_name = $nombre[0];
        $last_name = implode(' ', array_slice($nombre, 1));


        /* Extrae y organiza la fecha de nacimiento de un formato específico a ISO. */
        $day = substr($response->birthday_date, 0, 2);
        $month = substr($response->birthday_date, 2, 2);
        $year = substr($response->birthday_date, 4, 4);
        $birth_date = $year . "-" . $month . "-" . $day;

        $response = array();

        /* crea una respuesta estructurada con información personal del usuario. */
        $response["code"] = 0;
        $response["rid"] = $rid;
        $response["data"] = array();
        array_push($response["data"], array(
            "birth_date" => $birth_date,
            "first_name" => $first_name,
            "last_name" => $last_name,
        ));

        //$response = json_encode($response);
        break;

}


/*$response = array();
$response["code"] = 0;
$response["data"] = $response;
$response["rid"] = $json->rid;*/

