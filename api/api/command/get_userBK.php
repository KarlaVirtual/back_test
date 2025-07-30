<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/*Inicializa variables de usuario y crea instancias de UsuarioMandante y Mandante.*/
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$UsuarioMandante = new UsuarioMandante(1);
$Mandante = new Mandante($UsuarioMandante->getMandante());

$ciudad = ""; // Variable para almacenar la ciudad
$sexo = ""; // Variable para almacenar el sexo
$direcchon = ""; // Variable para almacenar la dirección
$fecha_nacimiento = ""; // Variable para almacenar la fecha de nacimiento
$fecha_expedicion = ""; // Variable para almacenar la fecha de expedición
$fecha_registro = ""; // Variable para almacenar la fecha de registro
$nombres = ""; // Variable para almacenar los nombres
$cedula = ""; // Variable para almacenar la cédula
$celular = ""; // Variable para almacenar el celular

/*Inicializa variables de usuario y obtiene información del objeto UsuarioMandante.*/
$IdUsuario = $UsuarioMandante->getUsumandanteId(); // Obtención del ID del usuario mandante
$UserName = $UsuarioMandante->getEmail(); // Obtención del email del usuario mandante

$saldo = $UsuarioMandante->getSaldo(); // Obtención del saldo del usuario mandante
$moneda = $UsuarioMandante->getMoneda(); // Obtención de la moneda del usuario mandante
$paisId = $UsuarioMandante->getPaisId(); // Obtención del ID del país del usuario mandante

$fecha_registro = ""; // Resetear fecha de registro
$fecha_ultima = ""; // Variable para almacenar la fecha de la última acción
$nombres = $UsuarioMandante->getNombres() . " " . $UsuarioMandante->getApellidos(); // Obtención del nombre completo

$saldo = $UsuarioMandante->getSaldo(); // Obtención nuevamente del saldo del usuario mandante

// Configuración de un JSON para las reglas de búsqueda de mensajes del usuario
$jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';
$usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);
$usuarioMensajes = json_encode($usuarioMensajes); // Codificación de mensajes a formato JSON

// Conteo de los mensajes no leídos del usuario
$mensajes_no_leidos = $usuarioMensajes->count[0]->{".count"};
if ($Mandante->propio == "S") {

    /*Instancia objetos de usuario y obtiene información de ciudad y país.*/
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());
    $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->getUsuarioMandante());
    $ciudad = $Registro->getCiudadId();
    $pais = $Usuario->paisId;
    $Pais = new Pais($pais);

    /*Obtiene y asigna datos del usuario como país, sexo, dirección, y fecha de nacimiento.*/
    $pais = $Pais->iso;
    $sexo = $Registro->getSexo();
    $direccion = $Registro->getDireccion();
    $fecha_nacimiento = $UsuarioOtrainfo->getFechaNacim();
    $fecha_expedicion = $Registro->getFechaExped();
    $cedula = $Registro->getCedula();
    $celular = $Registro->getCelular();
    $nombre1 = $Registro->nombre1;
    $nombre2 = $Registro->nombre2;
    $apellido1 = $Registro->apellido1;
    $apellido2 = $Registro->apellido2;
    $ciudadNacimiento = $Registro->getCiudnacimId();

    /*Obtiene y asigna datos del usuario como saldo, moneda, país, ID, nombre y fechas.*/
    $saldo = $Usuario->getBalance();
    $moneda = $Usuario->moneda;
    $paisId = $UsuarioMandante->paisId;
    $IdUsuario = $Usuario->usuarioId;
    $UserName = $Usuario->login;
    $fecha_registro = $Usuario->fecha_crea;
    $nombres = $Usuario->nombre;
    $fecha_ultima = $Usuario->fecha_ult;
}

/*Filtra y obtiene datos de países y ciudades en formato JSON.*/
$Pais = new Pais();

$SkeepRows = 0;
$MaxRows = 1000000;

$rules = [];


array_push($rules, array("field" => "ciudad.ciudad_id", "data" => "$ciudad", "op" => "eq"));

// Crea un filtro que contiene las reglas y la operación de agrupación
$filtro = array("rules" => $rules, "groupOp" => "AND");

// Convierte el filtro a formato JSON
$json2 = json_encode($filtro);

// Obtiene los países según los parámetros definidos
$paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);

// Decodifica el JSON a un objeto
$paises = json_decode($paises);

// Inicializa un arreglo final
$final = [];
$arrayf = [];
$monedas = [];

$ciudades = [];
$departamentos = [];

foreach ($paises->data as $key => $value) {

    // Inicializa un arreglo vacío
    $array = [];

    // Asigna el ID y Nombre del país al arreglo
    $array["Id"] = $value->{"pais.pais_id"};
    $array["Name"] = $value->{"pais.pais_nom"};

    // Obtiene el ID y texto del departamento
    $departamento_id = $value->{"departamento.depto_id"};
    $departamento_texto = $value->{"departamento.depto_nom"};

    // Obtiene el ID y texto de la ciudad
    $ciudad_id = $value->{"ciudad.ciudad_id"};
    $ciudad_texto = $value->{"ciudad.ciudad_nom"};

    // Verifica si el ID del país ha cambiado y si el ID del arreglo anterior no está vacío
    if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

        // Asigna monedas únicas al arreglo final
        $arrayf["currencies"] = array_unique($monedas);
        $arrayf["departments"] = $departamentos;
        array_push($final, $arrayf);

        $arrayf = [];
        $monedas = [];
        $departamentos = [];
        $ciudades = [];
    }

    /*Asigna valores de país y moneda a un array asociativo $arrayf y $monedas.*/
    $arrayf["Id"] = $value->{"pais.pais_id"};
    $arrayf["Name"] = $value->{"pais.pais_nom"};

    $moneda = [];
    $moneda["Id"] = $value->{"pais_moneda.paismoneda_id"};
    $moneda["Name"] = $value->{"pais_moneda.moneda"};

    array_push($monedas, $moneda);

    /*Verifica si el ID del departamento ha cambiado y actualiza los arrays correspondientes.*/
    if ($departamento_idf != $departamento_id && $departamento_idf != "") {

        $departamento = [];
        $departamento["Id"] = $departamento_idf;
        $departamento["Name"] = $departamento_textof;
        $departamento["cities"] = $ciudades;

        array_push($departamentos, $departamento);

        $ciudades = [];

        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;


    } else {
        /*Agrega un código postal global a la ciudad si no hay coincidencias.*/
        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;

        $ciudad["postalCodes"] = array();


        $postalarray = array();
        $postalarray["Id"] = "1";
        $postalarray["Name"] = "Global";

        array_push($ciudad["postalCodes"], $postalarray);

        /*
    $codigopostales = file_get_contents('https://www.datos.gov.co/resource/krpp-ufw8.json?$select=codigo_postal,barrios_contenidos_en_el_codigo_postal&$where=nombre_municipio="' . strtoupper( $ciudad_texto).'"');



    foreach ($codigopostales as $codigopostale) {
        $postalarray = array();
        $postalarray["Id"] = $codigopostales.codigo_postal;
        $postalarray["Name"] = $codigopostales.barrios_contenidos_en_el_codigo_postal;

        array_push($ciudad["postalCodes"],$postalarray);
    }
*/

        array_push($ciudades, $ciudad);
    }

    $departamento_idf = $value->{"departamento.depto_id"};
    $departamento_textof = $value->{"departamento.depto_nom"};

}

/*Asigna departamentos y monedas a un array final para la región.*/
$departamento = [];
$departamento["Id"] = $departamento_idf; // Asigna el ID del departamento
$departamento["Name"] = $departamento_textof; // Asigna el nombre del departamento
$departamento["cities"] = $ciudades; // Asigna las ciudades asociadas al departamento

// Agrega el departamento al array de departamentos
array_push($departamentos, $departamento);

$ciudades = [];

// Agrega la moneda al array de monedas
array_push($monedas, $moneda);
$arrayf["currencies"] = array_unique($monedas);
$arrayf["departments"] = $departamentos;

// Agrega el array con departamentos y monedas al array final
array_push($final, $arrayf);

$FinalRegion = $final;

// Crea una nueva instancia de la clase Pais
$Pais = new Pais();

$SkeepRows = 0;
$MaxRows = 1000000;

$rules = [];

// Agrega una regla de filtrado para la ciudad de nacimiento
array_push($rules, array("field" => "ciudad.ciudad_id", "data" => "$ciudadNacimiento", "op" => "eq"));

// Crea un array que contiene las reglas de filtrado y la operación de grupo
$filtro = array("rules" => $rules, "groupOp" => "AND");

// Convierte el filtro a formato JSON
$json2 = json_encode($filtro);

// Obtiene los países de la base de datos utilizando el método getPaises de la clase Pais
$paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);

// Decodifica el JSON de los países
$paises = json_decode($paises);

// Inicializa arrays vacíos para la siguiente etapa
$final = [];
$arrayf = [];
$monedas = [];

// Inicializa arrays vacíos para ciudades y departamentos
$ciudades = [];
$departamentos = [];

foreach ($paises->data as $key => $value) {

    // Inicializa un array vacío para almacenar información.
    $array = [];

    // Asigna el ID y el nombre del país al array.
    $array["Id"] = $value->{"pais.pais_id"};
    $array["Name"] = $value->{"pais.pais_nom"};

    $departamento_id = $value->{"departamento.depto_id"};
    $departamento_texto = $value->{"departamento.depto_nom"};

    $ciudad_id = $value->{"ciudad.ciudad_id"};
    $ciudad_texto = $value->{"ciudad.ciudad_nom"};

    if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

        $arrayf["currencies"] = array_unique($monedas);
        $arrayf["departments"] = $departamentos;
        array_push($final, $arrayf);

        $arrayf = [];
        $monedas = [];
        $departamentos = [];
        $ciudades = [];
    }

    // Asigna el ID y el nombre del país al array de países.
    $arrayf["Id"] = $value->{"pais.pais_id"};
    $arrayf["Name"] = $value->{"pais.pais_nom"};

    // Inicializa un array para almacenar la moneda.
    $moneda = [];
    $moneda["Id"] = $value->{"pais_moneda.paismoneda_id"};
    $moneda["Name"] = $value->{"pais_moneda.moneda"};

    array_push($monedas, $moneda);
    // Verifica si el ID del departamento actual no es el mismo que el ID del departamento de referencia y si el ID del departamento de referencia no está vacío
    if ($departamento_idf != $departamento_id && $departamento_idf != "") {

        $departamento = []; // Inicializa un array para almacenar la información del departamento
        $departamento["Id"] = $departamento_idf; // Asigna el ID del departamento
        $departamento["Name"] = $departamento_textof; // Asigna el nombre del departamento
        $departamento["cities"] = $ciudades; // Asigna las ciudades relacionadas con el departamento

        array_push($departamentos, $departamento); // Agrega el departamento al array de departamentos

        $ciudades = [];

        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;


    } else {
        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;

        $ciudad["postalCodes"] = array(); // Inicializa un array para los códigos postales de la ciudad

        $postalarray = array(); // Inicializa un array para un código postal
        $postalarray["Id"] = "1"; // Asigna un ID al código postal
        $postalarray["Name"] = "Global"; // Asigna un nombre al código postal

        array_push($ciudad["postalCodes"], $postalarray); // Agrega el código postal al array de códigos postales

        /*
    $codigopostales = file_get_contents('https://www.datos.gov.co/resource/krpp-ufw8.json?$select=codigo_postal,barrios_contenidos_en_el_codigo_postal&$where=nombre_municipio="' . strtoupper( $ciudad_texto).'"');



    foreach ($codigopostales as $codigopostale) {
        $postalarray = array();
        $postalarray["Id"] = $codigopostales.codigo_postal;
        $postalarray["Name"] = $codigopostales.barrios_contenidos_en_el_codigo_postal;

        array_push($ciudad["postalCodes"],$postalarray);
    }
*/

        array_push($ciudades, $ciudad);
    }

    $departamento_idf = $value->{"departamento.depto_id"};
    $departamento_textof = $value->{"departamento.depto_nom"};

}

// Inicializa un arreglo vacío para departamentos
$departamento = [];
$departamento["Id"] = $departamento_idf;
$departamento["Name"] = $departamento_textof;
$departamento["cities"] = $ciudades;

// Agrega el departamento al arreglo de departamentos
array_push($departamentos, $departamento);

$ciudades = [];

// Agrega la moneda al arreglo de monedas
array_push($monedas, $moneda);
$arrayf["currencies"] = array_unique($monedas);
$arrayf["departments"] = $departamentos;

// Agrega el arreglo final al arreglo general
array_push($final, $arrayf);

$FinalRegionNacimiento = $final;

// Crea la respuesta con el código y los datos del usuario
$response = array(
    "code" => 0,
    "data" => array(
        "balance" => $saldo,
        "username" => $UserName,
        "country_code" => $pais,
        "city" => $ciudad,
        "country" => $FinalRegion,
        "city_id" => $FinalRegion[0]['departments'][0]['cities'][0],
        "department_id" => $FinalRegion[0]['departments'][0],
        "user_id" => $IdUsuario,
        "first_name" => $nombre1,
        "countrybirth_id" => $FinalRegion[0],
        "departmentbirth_id" => $FinalRegion[0]['departments'][0],
        "citybirth_id" => $FinalRegion[0]['departments'][0]['cities'][0],
        "second_name" => $nombre2,
        "sur_name" => $apellido1,
        "second_sur_name" => $apellido2,
        "sex" => $sexo,
        "address" => $direccion,
        "birth_date" => $fecha_nacimiento,
        "documentType" => 1,
        "doc_number" => $cedula,
        "email" => $UserName,
        "phone" => $celular,
        "mobile_phone" => null,
        "iban" => null,
        "is_verified" => false,
        "maximal_daily_bet" => null,
        "maximal_single_bet" => null,
        "personal_id" => null,
        "subscribed_to_news" => false,
        "loyalty_point" => 0.0,
        "loyalty_earned_points" => 0.0,
        "loyalty_exchanged_points" => 0.0,
        "loyalty_level_id" => null,
        "casino_maximal_daily_bet" => null,
        "casino_maximal_single_bet" => null,
        "zip_code" => null,
        "currency" => $moneda,
        "casino_balance" => $saldo,
        "bonus_balance" => 0.0,
        "frozen_balance" => 0.0,
        "bonus_win_balance" => 0.0,
        "bonus_money" => 0.0,
        "province" => null,
        "active_step" => null,
        "active_step_state" => null,
        "has_free_bets" => false,
        "swift_code" => null,
        "additional_address" => null,
        "affiliate_id" => null,
        "btag" => null,
        "exclude_date" => null,
        "reg_date" => $fecha_registro,
        "doc_issue_date" => null,
        "subscribe_to_email" => true,
        "subscribe_to_sms" => true,
        "subscribe_to_bonus" => true,
        "unread_count" => $mensajes_no_leidos,
        "incorrect_fields" => null,
        "loyalty_last_earned_points" => 0.0,
        "loyalty_point_usage_period" => 0,
        "loyalty_min_exchange_point" => 0,
        "loyalty_max_exchange_point" => 0,
        "active_time_in_casino" => null,
        "last_login_date" => strtotime($fecha_ultima),
        "name" => $nombres,
        "expe_date"=>$fecha_expedicion
    ),
);

/*Instancia UsuarioMandante, inicializa límites y consulta configuración de usuario por ID.*/
$UsuarioMandante = new UsuarioMandante($json->session->usuario);


$limites = array();

$UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

$limitesArray = $UsuarioConfiguracionMySqlDAO->queryByUsuarioId($UsuarioMandante->getUsuarioMandante());
$limites["t"] = $UsuarioMandante->getUsuarioMandante();

/*Itera sobre límites de usuario y asigna tiempo activo en el casino a la respuesta.*/
foreach ($limitesArray as $item) {

    $tipo = "";

    switch ($item->getTipo()) {
        case "EXCTIME":
            $response["data"]["active_time_in_casino"] = intval($item->getValor());

            break;


    }


}


