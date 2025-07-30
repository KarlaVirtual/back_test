<?php
/**
 * Index de la api 'bonusapi'
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */


use Backend\dto\Usuario;

/**
 * Configura la visualización de errores en función de los parámetros de la solicitud.
 * Si se proporciona el valor específico en `DXbDpfykzqwS`, habilita el informe de errores.
 *
 * También define una función `getallheaders` si no existe, para obtener todas las cabeceras de la solicitud HTTP.
 */

ini_set('display_errors', 'OFF');


if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X'){
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL'){
    $_ENV["debugFixed2"]= '1';
}

if (!function_exists('getallheaders')) {
    /**
     * Obtiene todas las cabeceras de la solicitud HTTP actual.
     *
     * @return array Un array asociativo de cabeceras.
     */
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

$URI = $_SERVER["REQUEST_URI"];
$params = file_get_contents('php://input');

if (substr($params, 0, 1) === '{') {
    // JSON is valid
}else{
    $params = base64_decode($params);

    $params = html_entity_decode ($params) ;

}

$params = json_decode($params);
$response = array();

try {
    // Inicia la variable $log con la fecha actual y una línea separadora
    $log = "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    // Agrega la URI al log
    $log = $log . $URI;
    // Agrega el contenido del cuerpo de la solicitud al log, eliminando espacios en blanco
    $log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

}catch (Exception $e){
}

$headers = getallheaders();

// Define las URL de los servicios
$URL_ITAINMENT2 = 'https://dataexport-altenar.biahosted.com';
//$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com'; // Línea comentada de la URL del servicio
$URL_ITAINMENT = 'https://dataexport-uof-altenar.biahosted.com'; // URL del servicio a utilizar

// Verifica si el método de la solicitud es 'OPTIONS', y si es así, termina la ejecución del script
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$arraySuper = explode("/", current(explode("?", $URI)));

try {
    // Se intenta modificar el segundo último elemento del arreglo $arraySuper,
    // convirtiendo su primer carácter a mayúscula.
    $arraySuper[oldCount($arraySuper) - 2] = ucfirst($arraySuper[oldCount($arraySuper) - 2]);
if($arraySuper[oldCount($arraySuper) - 2] == "" || $arraySuper[oldCount($arraySuper) - 2] == "BonusApi"){
    $filename = 'cases/' . $arraySuper[oldCount($arraySuper) - 1] . ".php";

}else{
    $filename = 'cases/' . $arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1] . ".php";
    $filename = 'cases/' . $arraySuper[oldCount($arraySuper) - 1] . ".php";

}
    $filename = str_replace("CreateTournamentMANUAL","CreateTournament",$filename);
    $filename = str_replace("CreateBonusMANUAL","CreateBonus",$filename);
    $filename = str_replace("CreateBonusMANUAL2","CreateBonus2",$filename);
    $filename = str_replace("CreateBonusFreeSpinMANUAL","CreateBonusFreeSpin",$filename);

    // Se inicializa la variable de validación de usuario logueado.
    $validacionLogueado = true;
    // Se verifica si el usuario está logueado o si se accede a ciertas funciones públicas.
    if ($_SESSION["logueado"] || $arraySuper[oldCount($arraySuper) - 1] == "CheckAuthentication" || $arraySuper[oldCount($arraySuper) - 1] == "uploadImage" || $arraySuper[oldCount($arraySuper) - 1] == "Login" || $arraySuper[oldCount($arraySuper) - 1] == "ImportBulkUsers" || $arraySuper[oldCount($arraySuper) - 1] == "GenerateHashFiles" || $arraySuper[oldCount($arraySuper) - 1] == "GeneraHashFilesBackend" || $arraySuper[oldCount($arraySuper) - 1] == "CloseDaySpecific" || $arraySuper[oldCount($arraySuper) - 1] == "LoginGoogle") {
        $validacionLogueado = true;
    }

    // Se verifica si el archivo existe y si el usuario está validado para incluirlo.
    if (file_exists(__DIR__ ."/".$filename) && $validacionLogueado) {
        require $filename;
    } else {
        // Se prepara una respuesta de error en caso de que no se cumplan las condiciones.
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'f';
        $response["CodeError"] = $code;

    }

} catch (Exception $e) {
    // En caso de que ocurra una excepción, se imprime en caso de que el modo debug esté activado.
    if ($_ENV['debug']) {
        print_r($e);
    }
}





if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}

if ($URI == "/admin/dao/backapi/en/Financial/GetDepositsWithdrawalsWithPaging") {
    // Devuelve un JSON indicando que no hay errores y datos de ejemplo
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":{"Documents" : {"Objects":[{"Id" : 1,"ClientId":1,"CreatedLocal":"07/07/2012 07:59:59","TypeName":1,"CurrencyId":1,"ModifiedLocal":"07/07/2012 07:59:59","PaymentSystemName":1,"CashDeskId":1,"State":1,"Note":1,"ExternalId":1,"Amount" : 1000}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}}');
}

if ($URI == "/admin/dao/backapi/es/Financial/GetDepositsWithdrawalsWithPaging") {
    // Devuelve un JSON indicando que no hay errores y una lista vacía de objetos
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","Objects":[],"Data":{"Documents" : {"Objects":[{"Id" : 1,"ClientId":1,"CreatedLocal":"07/07/2012 07:59:59","TypeName":1,"CurrencyId":1,"ModifiedLocal":"07/07/2012 07:59:59","PaymentSystemName":1,"CashDeskId":1,"State":1,"Note":1,"ExternalId":1,"Amount" : 1000}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}}');
}

if ($URI == "/admin/dao/backapi/en/Report/GetPaymentSystems") {

    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":{"Documents" : {"Objects":[{"Id" : 1,"ClientId":1,"CreatedLocal":"07/07/2012 07:59:59","TypeName":1,"CurrencyId":1,"ModifiedLocal":"07/07/2012 07:59:59","PaymentSystemName":1,"CashDeskId":1,"State":1,"Note":1,"ExternalId":1,"Amount" : 1000}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}}');
}

if ($URI == "/admin/dao/backapi/es/Report/GetPaymentSystems") {

    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","Objects":[],"Data":{"Documents" : {"Objects":[{"Id" : 1,"ClientId":1,"CreatedLocal":"07/07/2012 07:59:59","TypeName":1,"CurrencyId":1,"ModifiedLocal":"07/07/2012 07:59:59","PaymentSystemName":1,"CashDeskId":1,"State":1,"Note":1,"ExternalId":1,"Amount" : 1000}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}}');
}

if ($URI == "/admin/dao/backapi/en/Financial/GetDocumentStates") {
    // Devuelve un JSON indicando que no hay errores y un estado de documento de ejemplo
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data": [{"NumId":"1","Name":"test"}]}');
}

if ($URI == "/admin/dao/backapi/es/Financial/GetDocumentStates") {

    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data": [{"NumId":"1","Name":"test"}]}');
}
if ($URI == "/admin/dao/backapi/en/Setting/GetReportColumns?reportName=DepositReportSettings") {
    // Devuelve un JSON con las columnas del reporte de depósitos
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data": ["Id","ClientId","CreatedLocal","TypeName","CurrencyId","StakeCurrency","Amount","ModifiedLocal","PaymentSystemName","PaymentSystemName","State","Note","ExternalId"]}');
}

if ($URI == "/admin/dao/backapi/es/Setting/GetReportColumns?reportName=DepositReportSettings") {

    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data": ["Id","ClientId","CreatedLocal","TypeName","CurrencyId","StakeCurrency","Amount","ModifiedLocal","PaymentSystemName","PaymentSystemName","State","Note","ExternalId"]}');
}
if ($URI == "/admin/dao/backapi/en/Client/GetClients") {
    // Devuelve un JSON con información de un cliente de ejemplo
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","Objects":[],"Data":{"Objects":[{"Id" : 1,"Login":"Login","FirstName":"Pedro","LastName":"PErez","PersonalId":1,"Email":"test@test.com","AffilateId":1,"BTag":1,"IsSubscribeToEmail":false,"IsSubscribeToSMS ":true,"ExternalId":1,"AccountHolder" : 1000,"Address": "Calle","Address": "Calle","Address": "Calle","BirthCity": "Caldas","BirthDate": "07/07/2017","BirthDepartment": "Caldas","BirthRegionCode2": "2","BirthRegionId": "1","CashDeskId": "1","CreatedLocalDate": "07/07/2016 09:09:00","CurrencyId": "1","DocIssueCode": "1","DocIssueDate": "1","DocIssuedBy": "1","Gender": "M","IBAN": "1","IsLoggedIn ": true,"IsResident ": true,"IsSubscribedToNewsletter ": false,"IsTest ": true,"IsVerified ": true,"Language": "ES","LastLoginLocalDate": "07/07/1994 09:09:00","MiddleName": "1","MobilePhone": "1","Phone": "1","ProfileId": "1","PromoCode": "1","Province": "1","CountryName": "1","RegistrationSource": "1","SportsbookProfileId": "1","SwiftCode": "1","Title": "1","ZipCode": "1","IsLocked": true}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}');
}

if ($URI == "/admin/dao/backapi/es/Client/GetClients") {

/**
 * Código que obtiene una lista de usuarios.
 *
 * Se crea una instancia de la clase Usuario con un ID específico.
 * Luego se obtienen los parámetros de entrada en formato JSON y se decodifican.
 *
 * Se establece el número máximo de filas a recuperar desde los parámetros o se asigna un valor por defecto.
 * Finalmente, se llama al método getUsuarios para obtener los usuarios según los criterios especificados.
 */

$Usuario = new Usuario(1);

    $params = file_get_contents('php://input');
    $params = json_decode($params);

    $MaxRows = $params->MaxRows;

    if ($MaxRows == "") {
        $MaxRows = 10;
    }

    $usuarios = $Usuario->getUsuarios("A", "", "a.usuario_id", "asc", "0", $MaxRows);

    $usuariosFinal = [];

    foreach ($usuarios as $key => $value) {
        /**
         * Arreglo que almacena información de un usuario, incluyendo identificación,
         * nombre, detalles de contacto y dirección. Los valores se asignan a cada
         * clave correspondiente en el arreglo.
         *
         */
        $array = [];

        $array["Id"] = $value["a.usuario_id"];
        $array["Login"] = $value["a.login"];
        $array["FirstName"] = $value["a.nombre"];
        $array["LastName"] = $value["a.nombre"];
        $array["PersonalId"] = 1;
        $array["Email"] = $value["a.nombre"];
        $array["AffilateId"] = $value["a.nombre"];
        $array["LastName"] = $value["a.nombre"];

        $array["Id"] = 1;
        $array["Login"] = "Login";
        $array["FirstName"] = "Pedro";
        $array["LastName"] = "PErez";
        $array["PersonalId"] = 1;
        $array["Email"] = "test@test.com";
        $array["AffilateId"] = 1;
        $array["BTag"] = 1;
        $array["IsSubscribeToEmail"] = false;
        $array["IsSubscribeToSMS"] = true;
        $array["ExternalId"] = 1;
        $array["AccountHolder"] = 1000;
        $array["Address"] = "Calle";
        $array["Address"] = "Calle";
        $array["Address"] = "Calle";
        $array["BirthCity"] = "Caldas";
        $array["BirthDate"] = "07/07/2017";
        $array["BirthDepartment"] = "Caldas";
        $array["BirthRegionCode2"] = "2";
        $array["BirthRegionId"] = "1";
        $array["CashDeskId"] = "1";
        $array["CreatedLocalDate"] = "07/07/2016 09:09:00";
        $array["CurrencyId"] = "1";
        $array["DocIssueCode"] = "1";
        $array["DocIssueDate"] = "1";
        $array["DocIssuedBy"] = "1";
        $array["Gender"] = "M";
        $array["IBAN"] = "1";
        $array["IsLoggedIn "] = true;
        $array["IsResident "] = true;
        $array["IsSubscribedToNewsletter"] = false;
        $array["IsTest"] = true;
        $array["IsVerified"] = true;
        $array["Language"] = "ES";
        $array["LastLoginLocalDate"] = "07/07/1994 09:09:00";
        $array["MiddleName"] = "1";
        $array["MobilePhone"] = "1";
        $array["Phone"] = "1";
        $array["ProfileId"] = "1";
        $array["PromoCode"] = "1";
        $array["Province"] = "1";
        $array["CountryName"] = "1";
        $array["RegistrationSource"] = "1";
        $array["SportsbookProfileId"] = "1";
        $array["SwiftCode"] = "1";
        $array["Title"] = "1";
        $array["ZipCode"] = "1";
        $array["IsLocked"] = true;

        array_push($usuariosFinal, $array);
    }
    // Imprimir un mensaje de error en formato JSON si hay una falla en la autenticación
    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","Objects":[],"Data":{"Objects": ' . json_encode($usuariosFinal) . ',"Count": ' . oldCount($usuariosFinal) . '}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}');
}

/**
 * Convertir divisas
 *
 * @param array $from_Currency from_Currency
 * @param String $to_Currency to_Currency
 * @param String $amount amounts
 *
 * @return String $convertido convertido
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function currencyConverter($from_Currency, $to_Currency, $amount)
{
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $encode_amount = 1;

    $rawdata = file_get_contents("http://api.currencies.zone/v1/quotes/$from_Currency/$to_Currency/json?quantity=$amount&key=44|YSqBgkAbvbGfenoxx62OaSnoD~rF8rw~");
    $rawdata = json_decode($rawdata);

    return $rawdata->result->amount;
}


/**
 * Obtener los deportes en el intervalo de dos fechas
 *
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getSports($fecha_inicial, $fecha_final)
{

    exit();
    global $URL_ITAINMENT;
    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);

    $array = array();
    foreach ($datos as $item) {
        $item_data = array(
            "Id" => $item->SportId,
            "Name" => $item->Name
        );
        array_push($array, $item_data);
    }


    return $array;


}

/**
 * Obtener los market types de un deporte
 *
 * @param String $sport sport
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getMarketTypes($sport, $fecha_inicial, $fecha_final)
{

    exit();
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();

    $existeMarcadorCorrecto = false;
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            $rawdata2 = file_get_contents($URL_ITAINMENT . "/Export/GetMarkets?importerId=8&eventId=" . $item->Categories[0]->Championships[0]->Events[0]->EventId);
            $datos2 = json_decode($rawdata2);

            foreach ($datos2 as $item2) {
                $item_data = array(
                    "Id" => $item->SportId . "M" . $item2->MarketTypeid,
                    "Name" => $item2->Name
                );
                array_push($array, $item_data);

                if ($item2->MarketTypeid == 3 && $item->SportId == 1) {
                    $existeMarcadorCorrecto = true;
                }
            }


        }

    }

    if (!$existeMarcadorCorrecto && $sport == 1) {
        $item_data = array(
            "Id" => "1M3",
            "Name" => "Marcador Correcto(F)"
        );
        array_push($array, $item_data);
    }


    return $array;


}


/**
 * Obtener las regiones de un deporte
 *
 *
 *  Esta función se encarga de obtener las regiones relacionadas con un deporte específico
 *  en un rango de fechas determinado. Consulta una API externa para obtener la información
 *  y devuelve un arreglo con los datos de las categorías correspondientes al deporte.
 *
 * @param String $sport sport
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getRegions($sport, $fecha_inicial, $fecha_final)
{

    exit();

    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();

    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                $item_data = array(
                    "Id" => $item2->CategoryId,
                    "Name" => $item2->Name
                );
                array_push($array, $item_data);
            }


        }

    }


    return $array;
}

/**
 * Obtener las competencias de un deporte
 *
 * @param String $sport sport
 * @param String $region region
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
/**
 * Obtener las regiones de un deporte
 *
 * @param String $sport sport
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getCompetitions($sport, $region, $fecha_inicial, $fecha_final)
{

    exit();
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();

    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {
                        $item_data = array(
                            "Id" => $item3->ChampionshipId,
                            "Name" => $item3->Name
                        );
                        array_push($array, $item_data);
                    }
                }

            }


        }

    }


    return $array;

}


/**
 * Obtener información sobre un deporte
 *
 * @param String $sport sport
 * @param String $region region
 * @param String $competition competition
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getMatches($sport, $region, $competition, $fecha_inicial, $fecha_final)
{

    exit();
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {

                        if ($item3->ChampionshipId == $competition) {
                            foreach ($item3->Events as $item4) {
                                $item_data = array(
                                    "Id" => $item4->EventId,
                                    "Name" => $item4->EventName
                                );
                                array_push($array, $item_data);
                            }
                        }

                    }
                }

            }


        }

    }


    return $array;

}


/**
 * Generar una clave alfanumérica del ticket
 *
 *  Este método genera una cadena alfanumérica de longitud especificada utilizando
 *  caracteres numéricos y letras mayúsculas.
 *
 * @param int $length length
 *
 * @return String $randomString randomString
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function GenerarClaveTicket($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


/**
 * Crear arreglo unico a partir de uno multidimensiona
 *
 *  Este método toma un arreglo multidimensional y crea un nuevo arreglo que contiene
 *  solo elementos únicos, basándose en la clave especificada.
 *
 * @param array $array array
 * @param String $key key
 *
 * @return String $temp_array temp_array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

/**
 * Crear arreglo unico a partir de uno multidimensiona
 *
 *  Este método toma un arreglo multidimensional y crea un nuevo arreglo que contiene
 *  solo elementos únicos, basándose en la clave especificada, utilizando una
 *  función diferente para agregar los elementos al arreglo.
 *
 * @param array $array array
 * @param String $key key
 *
 * @return String $temp_array temp_array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function unique_multidim_array2($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            array_push($temp_array, $val);
        }
        $i++;
    }
    return $temp_array;
}

