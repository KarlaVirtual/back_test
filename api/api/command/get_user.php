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

/**
 * Obtiene la información del usuario.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 *        - session: object Información de la sesión del usuario.
 *          - usuario: string Usuario de la sesión.
 * @return array Respuesta de la API.
 *         - code: int Código de respuesta.
 *         - data: array Datos de la respuesta.
 *           - balance: float Saldo del usuario.
 *           - username: string Nombre de usuario.
 *           - country_code: string Código del país.
 *           - city: string Ciudad del usuario.
 *           - country: array Información del país.
 *           - nationality_id: int ID de la nacionalidad.
 *           - city_id: array Información de la ciudad.
 *           - department_id: array Información del departamento.
 *           - cp: string Código postal.
 *           - rfc: string RFC del usuario.
 *           - doc_number2: string Número de documento adicional.
 *           - receive_advertising: bool Indica si el usuario recibe publicidad.
 *           - user_id: int ID del usuario.
 *           - first_name: string Primer nombre del usuario.
 *           - countrybirth_id: array Información del país de nacimiento.
 *           - departmentbirth_id: array Información del departamento de nacimiento.
 *           - citybirth_id: array Información de la ciudad de nacimiento.
 *           - second_name: string Segundo nombre del usuario.
 *           - sur_name: string Primer apellido del usuario.
 *           - second_sur_name: string Segundo apellido del usuario.
 *           - sex: string Sexo del usuario.
 *           - address: string Dirección del usuario.
 *           - birth_date: string Fecha de nacimiento del usuario.
 *           - documentType: int Tipo de documento.
 *           - doc_number: string Número de documento.
 *           - email: string Correo electrónico del usuario.
 *           - phone: string Teléfono del usuario.
 *           - mobile_phone: string Teléfono móvil del usuario.
 *           - iban: string IBAN del usuario.
 *           - is_verified: bool Indica si el usuario está verificado.
 *           - maximal_daily_bet: float Apuesta diaria máxima.
 *           - maximal_single_bet: float Apuesta única máxima.
 *           - personal_id: string ID personal del usuario.
 *           - subscribed_to_news: bool Indica si el usuario está suscrito a noticias.
 *           - loyalty_point: float Puntos de lealtad del usuario.
 *           - loyalty_earned_points: float Puntos de lealtad ganados.
 *           - loyalty_exchanged_points: float Puntos de lealtad intercambiados.
 *           - loyalty_level_id: int ID del nivel de lealtad.
 *           - casino_maximal_daily_bet: float Apuesta diaria máxima en el casino.
 *           - casino_maximal_single_bet: float Apuesta única máxima en el casino.
 *           - zip_code: string Código postal.
 *           - currency: string Moneda del usuario.
 *           - casino_balance: float Saldo del casino.
 *           - bonus_balance: float Saldo de bonos.
 *           - frozen_balance: float Saldo congelado.
 *           - bonus_win_balance: float Saldo de ganancias de bonos.
 *           - bonus_money: float Dinero de bonos.
 *           - province: string Provincia del usuario.
 *           - active_step: string Paso activo.
 *           - active_step_state: string Estado del paso activo.
 *           - has_free_bets: bool Indica si el usuario tiene apuestas gratuitas.
 *           - swift_code: string Código SWIFT.
 *           - additional_address: string Dirección adicional.
 *           - affiliate_id: int ID del afiliado.
 *           - btag: string Etiqueta del afiliado.
 *           - exclude_date: string Fecha de exclusión.
 *           - reg_date: string Fecha de registro.
 *           - doc_issue_date: string Fecha de emisión del documento.
 *           - subscribe_to_email: bool Indica si el usuario está suscrito a correos electrónicos.
 *           - subscribe_to_sms: bool Indica si el usuario está suscrito a SMS.
 *           - subscribe_to_bonus: bool Indica si el usuario está suscrito a bonos.
 *           - unread_count: int Número de mensajes no leídos.
 *           - incorrect_fields: array Campos incorrectos.
 *           - loyalty_last_earned_points: float ��ltimos puntos de lealtad ganados.
 *           - loyalty_point_usage_period: int Período de uso de puntos de lealtad.
 *           - loyalty_min_exchange_point: int Puntos mínimos de intercambio de lealtad.
 *           - loyalty_max_exchange_point: int Puntos máximos de intercambio de lealtad.
 *           - active_time_in_casino: int Tiempo activo en el casino.
 *           - last_login_date: int Fecha del último inicio de sesión.
 *           - name: string Nombre completo del usuario.
 *           - expe_date: string Fecha de expedición.
 *           - cc: string Ciudad.
 * @throws Exception Si ocurre un error al procesar la solicitud.
 */



// Establece el límite de memoria para el script, sin restricciones
ini_set('memory_limit', '-1');

// Crea una nueva instancia de la clase ConfigurationEnvironment
$ConfigurationEnvironment = new ConfigurationEnvironment();

// Crea una nueva instancia de UsuarioMandante utilizando el usuario de la sesión
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$Mandante = new Mandante($UsuarioMandante->getMandante());

// Inicializa variables para almacenar información personal
$ciudad = ""; // Ciudad del usuario
$sexo = ""; // Sexo del usuario
$direcchon = ""; // Dirección del usuario
$fecha_nacimiento = ""; // Fecha de nacimiento del usuario
$fecha_expedicion = ""; // Fecha de expedición
$fecha_registro = ""; // Fecha de registro
$nombres = ""; // Nombres del usuario
$cedula = ""; // Cédula del usuario
$celular = ""; // Celular del usuario
$codigoPostal = ""; // Código postal
$rfc = ""; // RFC del usuario
$codigoPostalObj = array( // Objeto que representa el código postal
    "Id" => '',
    "Name" => ''
);

// Obtiene información del UsuarioMandante
$IdUsuario = $UsuarioMandante->getUsumandanteId(); // ID del usuario mandante
$UserName = $UsuarioMandante->getEmail(); // Correo electrónico del usuario mandante

$saldo = $UsuarioMandante->getSaldo(); // Saldo del usuario mandante
$moneda = $UsuarioMandante->getMoneda(); // Moneda del usuario mandante
$paisId = $UsuarioMandante->getPaisId(); // ID del país del usuario mandante

// Inicializa fechas y nombres del UsuarioMandante
$fecha_registro = ""; // Fecha de registro
$fecha_ultima = ""; // Fecha de la última actividad
$nombres = $UsuarioMandante->getNombres() . " " . $UsuarioMandante->getApellidos(); // Nombres completos del usuario mandante

$saldo = $UsuarioMandante->getSaldo(); // Obtiene nuevamente el saldo del usuario mandante

// Inicializa variables para ciudad y nacionalidad
$ciudadNacimiento = 0; // Ciudad de nacimiento
$nacionalidad = 0; // Nacionalidad

// Crea un JSON para obtener mensajes del usuario
$jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';
$usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);
$usuarioMensajes = json_encode($usuarioMensajes);
$mensajes_no_leidos = $usuarioMensajes->count[0]->{".count"};

if ($Mandante->propio == "S") {

    /*Instancia objetos Usuario, Registro y UsuarioOtrainfo con el ID del UsuarioMandante.*/
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());

    $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->getUsuarioMandante());

    /*Obtiene y asigna información personal del usuario desde diferentes objetos y métodos.*/
    $ciudad = $Registro->getCiudadId();
    $pais = $Usuario->paisId;
    $Pais = new Pais($pais);
    $nacionalidad=$Registro->nacionalidadId;
    $paisIso2 = $Pais->iso;
    $sexo = $Registro->getSexo();
    $direccion = $Registro->getDireccion();
    $fecha_nacimiento = $UsuarioOtrainfo->getFechaNacim();
    $receive_advertising = $Usuario->getPermiteEnviarPublicidad();

    /*Asigna información personal del usuario desde diferentes objetos y m��todos.*/
    $doc_number2 = $UsuarioOtrainfo->info2;
    $fecha_expedicion = $Registro->getFechaExped();
    $cedula = $Registro->getCedula();
    $celular = $Registro->getCelular();
    $nombre1 = $Registro->nombre1;
    $nombre2 = $Registro->nombre2;
    $apellido1 = $Registro->apellido1;
    $apellido2 = $Registro->apellido2;
    $ciudadNacimiento = $Registro->getCiudnacimId();

    /*Obtiene y asigna información del usuario mandante desde diferentes objetos y métodos.*/
    $saldo = $Usuario->getBalance();
    $moneda = $Usuario->moneda;
    $paisId = $UsuarioMandante->paisId;

    $IdUsuario = $Usuario->usuarioId;
    $UserName = $Usuario->login;
    $fecha_registro = $Usuario->fecha_crea;
    $nombres = $Usuario->nombre;
    $fecha_ultima = $Usuario->fecha_ult;

    $codigoPostal = $Registro->getCodigoPostal();
    $rfc = $Registro->getOrigenFondos();

}

$Pais = new Pais();

$SkeepRows = 0;
$MaxRows = 1000000;
// Se verifica si el lugar de nacimiento es un valor vacío o igual a '0'
if($ciudadNacimiento == '0' || $ciudadNacimiento == ''){
    // Si es así, se crea un JSON con las reglas para buscar por el ID del país del usuario
    $json2 = '{"rules" : [{"field" : "pais.pais_id", "data": "' . $Usuario->paisId . '","op":"eq"}] ,"groupOp" : "AND"}';

}else{
    // Si se proporciona un ID de ciudad, se crea un JSON para buscar por el ID de la ciudad
    $json2 = '{"rules" : [{"field" : "ciudad.ciudad_id", "data": "' . $ciudadNacimiento . '","op":"eq"}] ,"groupOp" : "AND"}';

}

// Se obtienen los países junto con sus códigos postales usando el JSON previamente creado
$paises = $Pais->getPaisesCodigosPostales("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);
// Se decodifica el JSON resultante en un objeto PHP
$paises = json_decode($paises);

// Inicialización de arreglos para almacenar la información de nacimiento
$finalNacimiento = [];
$arrayf = [];

// Se recorren los datos de los países obtenidos
foreach ($paises->data as $key => $value) {
    // Se estructura la información en un arreglo anidado
    $arrayf[$value->{"pais.pais_id"}]["Name"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["Code2"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["Name"] = $value->{"departamento.depto_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["Name"] = $value->{"ciudad.ciudad_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["postalCodes"][$value->{"codigo_postal.codigopostal_id"}]["Name"] = $value->{"codigo_postal.codigo_postal"};
    $arrayf[$value->{"pais.pais_id"}]["currencies"][$value->{"pais_moneda.moneda"}] = $value->{"pais_moneda.moneda"};

}

foreach ($arrayf as $key => $value) {
    $pais = array(
        "Id" => $key,
        "Name" => $arrayf[$key]["Name"],
        "departments" => array(),
        "currencies" => array()
    );

    foreach ($arrayf[$key]["departments"] as $deptoId => $depto) {
        $deptoObj = array(
            "Id" => $deptoId,
            "Name" => $depto["Name"],
            "cities" => array()
        );

        /*Itera sobre ciudades y códigos postales, creando objetos y agregándolos a departamentos.*/
        foreach ($arrayf[$key]["departments"][$deptoId]["cities"] as $cityId => $city) {

            $cityObj = array(
                "Id" => $cityId,
                "Name" => $city["Name"],
                "postalCodes" => array()

            );

            foreach ($arrayf[$key]["departments"][$deptoId]["cities"][$cityId]["postalCodes"] as $postalCodeId => $postalCode) {
                array_push($cityObj["postalCodes"], array(
                    "Id" => $postalCodeId,
                    "Name" => $postalCode["Name"]

                ));
            }


            array_push($deptoObj["cities"], $cityObj);
        }
        array_push($pais["departments"], $deptoObj);
    }
    array_push($finalNacimiento, $pais);
}

$Pais = new Pais(); // Crear una nueva instancia de la clase Pais.

$SkeepRows = 0; // Inicializar la variable para filas a omitir.
$MaxRows = 1000000; // Establecer el máximo de filas.
// Verifica si la ciudad es '0' o una cadena vacía

if($ciudad == '0' || $ciudad == ''){
    // Si es cierto, se genera un JSON para filtrar por país
    $json2 = '{"rules" : [{"field" : "pais.pais_id", "data": "' . $Usuario->paisId . '","op":"eq"}] ,"groupOp" : "AND"}';
}else{
    // Si no, se genera un JSON para filtrar por ciudad
    $json2 = '{"rules" : [{"field" : "ciudad.ciudad_id", "data": "' . $ciudad . '","op":"eq"}] ,"groupOp" : "AND"}';
}

// Se verifica si el entorno de configuración es de desarrollo
if ($ConfigurationEnvironment->isDevelopment()) {
    // Se obtiene la lista de países y códigos postales en el entorno de desarrollo
    $paises = $Pais->getPaisesCodigosPostales("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);
} else {
    // Se obtiene la lista de países en otros entornos
    $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);
}

// Decodifica el JSON recibido
$paises = json_decode($paises);

// Inicializa un array para la residencia final
$finalResidencia = [];
$arrayf = [];

// Itera sobre la data obtenida
foreach ($paises->data as $key => $value) {
    // Asigna los nombres y códigos de los países y departamentos
    $arrayf[$value->{"pais.pais_id"}]["Name"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["Code2"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["Name"] = $value->{"departamento.depto_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["Name"] = $value->{"ciudad.ciudad_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["postalCodes"][$value->{"codigo_postal.codigopostal_id"}]["Name"] = $value->{"codigo_postal.codigo_postal"};
    $arrayf[$value->{"pais.pais_id"}]["currencies"][$value->{"pais_moneda.moneda"}] = $value->{"pais_moneda.moneda"};
}

foreach ($arrayf as $key => $value) {

    //Define un array asociativo para almacenar información de un país, departamentos y monedas.
    $pais = array(
        "Id" => $key,
        "Name" => $arrayf[$key]["Name"],
        "departments" => array(),
        "currencies" => array()
    );
    foreach ($arrayf[$key]["departments"] as $deptoId => $depto) {
        //Define un array asociativo para almacenar información de un departamento y sus ciudades.
        $deptoObj = array(
            "Id" => $deptoId,
            "Name" => $depto["Name"],
            "cities" => array()
        );
        foreach ($arrayf[$key]["departments"][$deptoId]["cities"] as $cityId => $city) {

            $cityObj = array(
                "Id" => $cityId,
                "Name" => $city["Name"],
                "postalCodes" => array()

            );

            /*Itera sobre códigos postales, los agrega a un array y verifica coincidencias con codigoPostal.*/
            foreach ($arrayf[$key]["departments"][$deptoId]["cities"][$cityId]["postalCodes"] as $postalCodeId => $postalCode) {
                array_push($cityObj["postalCodes"], array(
                    "Id" => $postalCodeId,
                    "Name" => $postalCode["Name"]

                ));


                if ($postalCodeId == $codigoPostal) {
                    $codigoPostalObj["Id"] = $postalCodeId;
                    $codigoPostalObj["Name"] = $postalCode["Name"];
                }
            }

            /*Establece valores predeterminados para codigoPostalObj si no está en el entorno de desarrollo.*/
            if(!$ConfigurationEnvironment->isDevelopment()){

                $codigoPostalObj["Id"] = '';
                $codigoPostalObj["Name"] =  'Ninguno';


                /* array_push($cityObj["postalCodes"], array(
                     "Id" => 0,
                     "Name" => 'Ninguno'

                 ));*/
            }


            array_push($deptoObj["cities"], $cityObj);
        }
        array_push($pais["departments"], $deptoObj);
    }
    array_push($finalResidencia, $pais);
}



/**
 * Inicializa una nueva instancia de la clase Pais.
 * Se establecen los parámetros para la consulta de datos geográficos y se prepara el JSON para la búsqueda.
 */
$Pais = new Pais();

$SkeepRows = 0; // Número de filas a omitir en la consulta
$MaxRows = 1000000; // Número máximo de filas a obtener en la consulta

// Construcción de una cadena JSON para las reglas de búsqueda
$json2 = '{"rules" : [{"field" : "departamento.depto_id", "data": "' . $finalResidencia[0]['departments'][0]['Id'] . '","op":"eq"}] ,"groupOp" : "AND"}';

// Verifica si el entorno de configuración es de desarrollo
if ($ConfigurationEnvironment->isDevelopment()) {
    // Obtiene los países utilizando códigos postales en desarrollo
    $paises = $Pais->getPaisesCodigosPostales("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);
} else {
    // Obtiene los países en un entorno de producción
    $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);
}

// Decodifica el JSON de la respuesta a un objeto PHP
$paises = json_decode($paises);

$finalResidenciaCiudades = []; // Inicializa un array para las ciudades de residencia
$arrayf = []; // Inicializa un array para almacenar los resultados procesados

// Recorre cada país en la respuesta obtenida
foreach ($paises->data as $key => $value) {
    // Llena el array estructurado con información de países, departamentos, y ciudades
    $arrayf[$value->{"pais.pais_id"}]["Name"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["Code2"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["Name"] = $value->{"departamento.depto_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["Name"] = $value->{"ciudad.ciudad_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["postalCodes"][$value->{"codigo_postal.codigopostal_id"}]["Name"] = $value->{"codigo_postal.codigo_postal"};
    $arrayf[$value->{"pais.pais_id"}]["currencies"][$value->{"pais_moneda.moneda"}] = $value->{"pais_moneda.moneda"};
}

foreach ($arrayf as $key => $value) {
    $pais = array(
        "Id" => $key,
        "Name" => $arrayf[$key]["Name"],
        "departments" => array(),
        "currencies" => array()
    );
    foreach ($arrayf[$key]["departments"] as $deptoId => $depto) {
        /*Define un array asociativo para almacenar información de un departamento y sus ciudades.*/
        $deptoObj = array(
            "Id" => $deptoId,
            "Name" => $depto["Name"],
            "cities" => array()
        );
        foreach ($arrayf[$key]["departments"][$deptoId]["cities"] as $cityId => $city) {

            $cityObj = array(
                "Id" => $cityId,
                "Name" => $city["Name"],
                "postalCodes" => array()

            );

            /*Itera sobre códigos postales, los agrega a un array y verifica coincidencias con codigoPostal.*/
            foreach ($arrayf[$key]["departments"][$deptoId]["cities"][$cityId]["postalCodes"] as $postalCodeId => $postalCode) {
                array_push($cityObj["postalCodes"], array(
                    "Id" => $postalCodeId,
                    "Name" => $postalCode["Name"]

                ));
            }


            if(!$ConfigurationEnvironment->isDevelopment()){

                $codigoPostalObj["Id"] = '';
                $codigoPostalObj["Name"] =  'Ninguno';

                /*array_push($cityObj["postalCodes"], array(
                    "Id" => 0,
                    "Name" => 'Ninguno'

                ));*/
            }

            array_push($deptoObj["cities"], $cityObj);
        }
        array_push($pais["departments"], $deptoObj);
    }
    array_push($finalResidenciaCiudades, $pais);
}


$response = array(
    "code" => 0,
    "data" => array(
        "balance" => $saldo,
        "username" => $UserName,
        "country_code" => $paisIso2,
        "city" => $ciudad,
        "country" => $finalResidencia,
        "nationality_id" => $nacionalidad,
        "city_id" => $finalResidencia[0]['departments'][0]['cities'][0],
        "department_id" => $finalResidenciaCiudades[0]['departments'][0],
        "cp" => $codigoPostal,
        "rfc" => $rfc,
        "doc_number2"=>$doc_number2,
        "receive_advertising" =>$receive_advertising,
        "user_id" => $IdUsuario,
        "first_name" => $nombre1,
        "countrybirth_id" => $finalNacimiento[0],
        "departmentbirth_id" => $finalNacimiento[0]['departments'][0],
        "citybirth_id" => $finalNacimiento[0]['departments'][0]['cities'][0],
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
        "expe_date" => $fecha_expedicion,
        "cc" => $ciudad
    ),
);

$UsuarioMandante = new UsuarioMandante($json->session->usuario); // Instancia de UsuarioMandante con la información del usuario

$limites = array(); // Inicialización del array de límites


$UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO(); // Instancia del Data Access Object para la configuración de usuario

$limitesArray = $UsuarioConfiguracionMySqlDAO->queryByUsuarioId($UsuarioMandante->getUsuarioMandante()); // Consulta de límites por ID de usuario
$limites["t"] = $UsuarioMandante->getUsuarioMandante(); // Asignación del ID de usuario a los límites

foreach ($limitesArray as $item) { // Iteración sobre los límites obtenidos

    $tipo = ""; // Inicialización de la variable tipo

    switch ($item->getTipo()) { // Evaluación del tipo de límite
        case "EXCTIME":
            $response["data"]["active_time_in_casino"] = intval($item->getValor()); // Asignación del tiempo activo en el casino
            break;


    }


}


