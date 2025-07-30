<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaAsociada;
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
use Backend\dto\PaisMandante;
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
use Backend\mysql\CuentaAsociadaMySqlDAO;
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
 * Este script procesa una solicitud HTTP para importar usuarios en bloque desde un archivo CSV codificado en base64.
 * 
 * @param string $params JSON recibido desde la entrada estándar ('php://input') que contiene:
 * @param string $params->CSV Cadena codificada en base64 que representa el archivo CSV.
 * 
 * @return array $response Respuesta generada al final del script, que incluye:
 *                         - code: Código de estado de la operación (0 para éxito).
 *                         - rid: Identificador de la solicitud.
 *                         - data: Datos procesados del archivo CSV.
 * 
 * @throws Exception Si ocurre un error al procesar el archivo CSV o al manejar datos.
 */

/**
 * Genera una cadena aleatoria de caracteres alfanuméricos.
 *
 * @param int $length_of_string Longitud de la cadena a generar.
 * @return string Cadena aleatoria generada.
 */
function random_strings($length_of_string)
{
    // Cadena de todos los caracteres alfanuméricos
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Mezcla la cadena $str_result y devuelve una subcadena
    // de la longitud especificada
    return substr(str_shuffle($str_result), 0, $length_of_string);
}

/* inicializa un ambiente de configuración y procesa datos JSON de entrada. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

$params = file_get_contents('php://input');
$params = json_decode($params);
$ClientIdCsv = $params->CSV;
if ($_ENV['debug']) {

} else {
    /* está preparado para ejecutar una acción alterna si no se cumplen ciertas condiciones. */

// exit();

}

exit();
/* decodifica una cadena en base64 y separa sus líneas por salto de línea. */
$ClientIdCsv = explode("base64,", $ClientIdCsv);
$ClientIdCsv = $ClientIdCsv[1];
$ClientIdCsv = base64_decode($ClientIdCsv);
//$ClientIdCsv = str_replace(";",",",$ClientIdCsv);

$lines = explode(PHP_EOL, $ClientIdCsv);

/* Divide una cadena CSV en líneas usando expresiones regulares para diferentes saltos de línea. */
$lines = preg_split('/\r\n|\r|\n/', $ClientIdCsv);

if (isset($ClientIdCsv) && $ClientIdCsv != '') {


    $line = array();
    $i = 0;

    $linee = str_getcsv($ClientIdCsv, "\n");

    //CSV: one line is one record and the cells/fields are seperated by ";"
    //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
    foreach ($linee as $line) {
        if ($i > 0) {
            $dsatz[$i] = array();
            $dsatz[$i] = explode(";", $line);

        }


        $i++;
    }

    foreach ($dsatz as $key => $number) {

        $k = 0;
        $PlayerId = $number[$k];
        $k++;
        $celular = $number[$k];
        $k++;
        $cedula = $number[$k];
        $k++;
        $nombre1 = $number[$k];

        $nombre1 = substr($nombre1, 0, 19);

        $k++;
        $nombre2 = $number[$k];

        $nombre2 = substr($nombre2, 0, 19);

        $k++;
        $apellido1 = $number[$k];

        $apellido1 = substr($apellido1, 0, 19);
        $k++;
        $apellido2 = $number[$k];

        $apellido2 = substr($apellido2, 0, 19);
        $k++;
        /*$apellido2=$number[$k];
        $k++;*/
        $fecha_nacim = $number[$k];

        $k++;
        $email = $number[$k];
        $k++;
        $pais = $number[$k];
        $k++;
        $departamento = $number[$k];
        $k++;
        $ciudad = $number[$k];
        $k++;
        $direccion = $number[$k];
        $k++;
        $codigo_postal = $number[$k];
        $k++;
        $estado = $number[$k];
        $k++;
        $registro_ip = $number[$k];
        /* Recorta `$dir_ip` a 20 caracteres y asigna valores a las propiedades del usuario. */
        $registro_ip = mb_substr($registro_ip, 0, 20);

        $k++;
        $registro_fecha = date('Y-m-d H:i:s');
        $k++;
        $telefono_fijo = $number[$k];

        $k++;
        $tipo_de_documento = $number[$k];
        $k++;
        $afiliador = $number[$k];
        $k++;


        if (strpos($number[0], '@') !== false) {

            $celular = $number[5];
            $cedula = $number[4];
            $nombre1 = $number[0];

            $nombre1 = substr($nombre1, 0, 19);

            $nombre2 = $number[1];

            $nombre2 = substr($nombre2, 0, 19);

            $apellido1 = $number[1];

            $apellido1 = substr($apellido1, 0, 19);
            $apellido2 = $number[2];

            $apellido2 = substr($apellido2, 0, 19);
            $fecha_nacim = '';
            $email = $number[3];
            $pais = '173';
            $departamento = '0';
            $ciudad = '0';
            $direccion = '';
            $codigo_postal = '';
            $estado = 'A';
            $registro_ip = '';
            $registro_fecha = '';
            $telefono_fijo = '';
            $tipo_de_documento = 'C';
            $afiliador = '0';
        }
        $registro_fecha = date('Y-m-d H:i:s');

        if ($afiliador == "") {
            $afiliador = 0;
        }

        $genero = 'M';
        //$telefono_fijo = '';

        $nombre1 = $ConfigurationEnvironment->DepurarCaracteres($nombre1);
        $nombre2 = $ConfigurationEnvironment->DepurarCaracteres($nombre2);
        $apellido1 = $ConfigurationEnvironment->DepurarCaracteres($apellido1);
        $apellido2 = $ConfigurationEnvironment->DepurarCaracteres($apellido2);
        $nombre = $ConfigurationEnvironment->DepurarCaracteres($nombre);
        $direccion = $ConfigurationEnvironment->DepurarCaracteres($direccion);


        try {
            if (($cedula != "" && $celular != "") || true) {


                /* Se asignan valores de usuario y configuración a variables para su uso posterior. */
                $user_info = $json->params->user_info;
                $type_register = 0;

//Mandante
                $site_id = $_SESSION['mandante'];

//Pais de residencia
                $countryResident_id = $pais;

//Idioma

                /* Definición de variables para idioma y creación de objetos Mandante y País. */
                $lang_code = 'ES';
                $idioma = strtoupper($lang_code);


                $Mandante = new Mandante($site_id);
                $Pais = new Pais($pais);

                /* Se crean instancias de país y moneda, asignando un estado predeterminado al usuario. */
                $PaisMandante = new \Backend\dto\PaisMandante('', $site_id, $pais);
                $PaisMoneda = new PaisMoneda($pais);

                $moneda_default = $PaisMandante->moneda;

                $estadoUsuarioDefault = 'A';


                /* Se crea un clasificador y se verifica el estado del usuario basado en su valor. */
                $Clasificador = new Clasificador("", "REGISTERACTIVATION");

                try {
                    $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

                    $estadoUsuarioDefault = (intval($MandanteDetalle->getValor()) == 1) ? "A" : "I";


                } catch (Exception $e) {
                    /* Manejador de excepciones que verifica si el código de error es 34. */


                    if ($e->getCode() == 34) {
                    } else {
                    }
                }

                /* asigna valores a variables relacionadas con el estado y datos del usuario. */
                $estadoUsuarioDefault = 'A';


                $address = $direccion;
                $birth_date = $fecha_nacim;


                $department_id = $departamento;

                /* Asignación de variables en PHP para procesar documentos y datos de usuario. */
                $docnumber = $cedula;
                $doctype_id = 1;

                $email = $email;

                $expedition_day = '00';

                /* Asignación de variables para datos personales y fecha de expedición. */
                $expedition_month = '00';
                $expedition_year = '0000';
                $first_name = $nombre1;
                $middle_name = $nombre2;
                $last_name = $apellido1;
                $second_last_name = $apellido2;


                /* asigna variables de género, teléfono y idioma, y establece un límite de depósito. */
                $gender = $genero;

                $landline_number = $telefono_fijo;
                $language = 'ES';


                $limit_deposit_day = 0;

                /* Se inicializan límites de depósito y se genera una contraseña aleatoria de 12 caracteres. */
                $limit_deposit_month = 0;
                $limit_deposit_week = 0;

                $nationality_id = $pais;

                $password = random_strings(12);
//$password = $ConfigurationEnvironment->GenerarClaveTicket(15);

                /* asigna valores a variables relacionadas con teléfono, ciudad y país de nacimiento. */
                $phone = $celular;


                $city_id = $ciudad;

                $countrybirth_id = 0;

                /* Inicializa variables para IDs de departamento, ciudad y código postal. */
                $departmentbirth_id = 0;
                $citybirth_id = 0;
                $cp = $codigo_postal;


                $expdept_id = 0;

                /* Se genera un nombre completo y una clave activa de 15 caracteres. */
                $expcity_id = 0;

                $nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;
                $clave_activa = $ConfigurationEnvironment->GenerarClaveTicket(15);


                $registroCorto = true;



                /* Asigna valores predeterminados si ciertas variables están vacías en condición específica. */
                if (($registroCorto) && $birth_date == "") {
                    $origen = 1;
                    $birth_date = '1970-01-01';
                }

                if (($registroCorto) && $depto_nacimiento == "") {
                    $depto_nacimiento = '0';
                }


                /* Asigna '0' a variables si cumplen condiciones específicas y están vacías. */
                if (($registroCorto) && $ciudad_nacimiento == "") {
                    $ciudad_nacimiento = '0';
                }

                if (($registroCorto) && $ocupacion == "") {
                    $ocupacion = '0';
                }


                /* Asigna '0' a variables vacías si se cumple la condición de $registroCorto. */
                if (($registroCorto) && $rangoingreso_id == "") {
                    $rangoingreso_id = '0';
                }

                if (($registroCorto) && $origen_fondos == "") {
                    $origen_fondos = '0';
                }


                /* Asigna '0' a $paisnacim_id y $countrybirth_id si $registroCorto es verdadero. */
                if (($registroCorto) && ($countrybirth_id == "")) {
                    $paisnacim_id = '0';
                    $countrybirth_id = '0';
                }

                if (($registroCorto) && $idioma == "") {
                    $idioma = 'ES';
                }

                switch ($doctype_id) {
                    case 1:
                        /* Asignación de "C" a la variable $doctype_id en el caso 1 de un switch. */

                        $doctype_id = "C";
                        break;

                    case 2:
                        /* asigna "E" a la variable $doctype_id si se cumple el caso correspondiente en un switch. */

                        $doctype_id = "E";

                        break;

                    case 3:
                        /* Asignación del valor "P" a la variable $doctype_id en el caso 3. */


                        $doctype_id = "P";

                        break;

                    default:
//throw new Exception("Inusual Detected", "11");

                        break;
                }


                /* Se asigna el valor 'C' a la variable tipo_de_documento en PHP. */
                $tipo_de_documento = 'C';
                switch (strtoupper($tipo_de_documento)) {
                    case 'DNI':
                        /* asigna "C" a $doctype_id si el caso es 'DNI'. */

                        $doctype_id = "C";
                        break;
                    case 'CE':
                        /* Asigna "CE" a $doctype_id si se cumple la condición del 'case'. */

                        $doctype_id = "CE";
                        break;
                    case 'C':
                        /* asigna el valor "C" a la variable `$doctype_id` en un caso específico. */

                        $doctype_id = "C";
                        break;

                    case 'P':
                        /* asigna "P" a $doctype_id si se cumple la condición del 'case'. */

                        $doctype_id = "P";

                        break;
                    default:

                        /* Se está generando una excepción con un mensaje de error específico y un código. */
                        throw new Exception("Inusual Detected", "11");

                        break;
                }



                /* Verifica si el email está vacío; si es así, lanza una excepción. */
                if ($email == '') {
                    throw new Exception("Inusual Detected", "11");
                }

                $Usuario = new Usuario();
                $Usuario->login = $email;

                /* Asignación de mandante y verificación de email en el sistema de usuario. */
                $Usuario->mandante = $Mandante->mandante;

                /* Verificamos si existe el email para el partner */
                $checkLogin = $Usuario->exitsLogin();
                if ($checkLogin) {
                    throw new Exception("Inusual Detected", "11");

                }



                /* Se crea un objeto Registro y se establecen atributos de identificación y mandante. */
                $Registro = new Registro();
                $Registro->setCedula($docnumber);
                $Registro->setMandante($Mandante->mandante);

                //if (!$Registro->existeCedula() ) {
                if (true) {

                    /* $Consecutivo = new Consecutivo("", "USU", "");

                     $consecutivo_usuario = $Consecutivo->numero;

                     $consecutivo_usuario++;

                     $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                     $Consecutivo->setNumero($consecutivo_usuario);


                     $ConsecutivoMySqlDAO->update($Consecutivo);

                     $ConsecutivoMySqlDAO->getTransaction()->commit();*/



                    /* Se definen variables vacías para almacenar premios y datos relacionados. */
                    $premio_max = "";
                    $premio_max1 = "";
                    $premio_max2 = "";
                    $premio_max3 = "";
                    $cant_lineas = "";
                    $lista_id = "";

                    /* Variables vacías definidas para almacenar datos de regalos y valores en un registro. */
                    $regalo_registro = "";
                    $valor_directo = "";
                    $valor_evento = "";
                    $valor_diario = "";
                    $destin1 = "";
                    $destin2 = "";

                    /* Se generan variables y un token utilizando una configuración y IP del usuario. */
                    $destin3 = "";

                    $apuesta_min = "";

                    $token_itainment = $ConfigurationEnvironment->GenerarClaveTicket2(12);

                    $dir_ip = $json->session->usuarioip;


                    /* Se crea un objeto DAO y se obtiene una transacción con la base de datos. */
                    $RegistroMySqlDAO = new RegistroMySqlDAO();
                    $Transaction = $RegistroMySqlDAO->getTransaction();


                    $Transaccion = $RegistroMySqlDAO->getTransaction();

//$Usuario->usuarioId = $consecutivo_usuario;

                    $Usuario->login = $email;


                    /* Asignación de valores a propiedades del objeto Usuario, incluyendo fecha y clave vacía. */
                    $Usuario->nombre = $nombre;

                    $Usuario->estado = $estadoUsuarioDefault;

                    $Usuario->fechaUlt = date('Y-m-d H:i:s');

                    $Usuario->claveTv = '';


                    /* Se inicializan propiedades del objeto Usuario con valores predeterminados. */
                    $Usuario->estadoAnt = 'I';

                    $Usuario->intentos = 0;

                    $Usuario->estadoEsp = $estadoUsuarioDefault;

                    $Usuario->observ = '';



                    /* Se asignan valores a propiedades del objeto Usuario, configurando su estado inicial. */
                    $Usuario->dirIp = $registro_ip;

                    $Usuario->eliminado = 'N';

                    $Usuario->mandante = $Mandante->mandante;

                    $Usuario->usucreaId = '0';


                    /* Se asignan valores a propiedades del objeto Usuario, incluyendo identificación y tokens. */
                    $Usuario->usumodifId = '0';

                    $Usuario->claveCasino = '';

                    $Usuario->tokenItainment = $token_itainment;

                    $Usuario->fechaClave = '';


                    /* inicializa propiedades relacionadas con el retiro de un usuario. */
                    $Usuario->retirado = '';

                    $Usuario->fechaRetiro = '';

                    $Usuario->horaRetiro = '';

                    $Usuario->usuretiroId = '0';


                    /* Se establecen propiedades de un objeto Usuario, incluyendo estado y token de casino. */
                    $Usuario->bloqueoVentas = 'N';

                    $Usuario->infoEquipo = '';

                    $Usuario->estadoJugador = 'AC';

                    $Usuario->tokenCasino = '';


                    /* asigna valores a propiedades de un objeto 'Usuario'. */
                    $Usuario->sponsorId = 0;

                    $Usuario->verifCorreo = 'N';

                    $Usuario->paisId = $countryResident_id;

                    $Usuario->moneda = $moneda_default;


                    /* Se configuran propiedades del objeto Usuario relacionadas con idioma y permisos. */
                    $Usuario->idioma = $idioma;

                    $Usuario->permiteActivareg = 'N';

                    $Usuario->test = 'N';

                    $Usuario->tiempoLimitedeposito = 0;


                    /* Inicializa propiedades de un objeto 'Usuario' relacionadas con configuración y estado. */
                    $Usuario->tiempoAutoexclusion = 0;

                    $Usuario->cambiosAprobacion = 'S';

                    $Usuario->timezone = '-5';

                    $Usuario->puntoventaId = 0;

                    /* Se están inicializando propiedades de un objeto Usuario con valores por defecto. */
                    $Usuario->usucreaId = 0;
                    $Usuario->usumodifId = 0;
                    $Usuario->usuretiroId = 0;
                    $Usuario->sponsorId = $afiliador;

                    $Usuario->puntoventaId = 0;


                    /* Se asignan valores a propiedades del objeto Usuario en el sistema. */
                    $Usuario->fechaCrea = $registro_fecha;

                    $Usuario->origen = 0;

                    $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                    $Usuario->documentoValidado = "I";

                    /* Se inicializan propiedades de un objeto Usuario para validación. */
                    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                    $Usuario->usuDocvalido = 0;


                    $Usuario->estadoValida = 'N';
                    $Usuario->usuvalidaId = 0;

                    /* Asignación de valores a propiedades del objeto $Usuario en PHP. */
                    $Usuario->fechaValida = date('Y-m-d H:i:s');
                    $Usuario->contingencia = 'I';
                    $Usuario->contingenciaDeportes = 'I';
                    $Usuario->contingenciaCasino = 'I';
                    $Usuario->contingenciaCasvivo = 'I';
                    $Usuario->contingenciaVirtuales = 'I';

                    /* Código asigna valores a atributos del objeto Usuario relacionados a poker y ubicación. */
                    $Usuario->contingenciaPoker = 'I';
                    $Usuario->restriccionIp = 'I';
                    $Usuario->ubicacionLongitud = '';
                    $Usuario->ubicacionLatitud = '';
                    $Usuario->usuarioIp = '';
                    $Usuario->tokenGoogle = "I";

                    /* Se inicializan propiedades del objeto $Usuario con valores predeterminados. */
                    $Usuario->tokenLocal = "I";
                    $Usuario->saltGoogle = '';


                    $Usuario->skype = '';
                    $Usuario->plataforma = 0;



                    /* Variables del objeto usuario se actualizan con información inicial y estado de importación. */
                    $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                    $Usuario->documentoValidado = "A";
                    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                    $Usuario->usuDocvalido = 0;


                    $Usuario->estadoImport = 1;


                    /* inserta un usuario en la base de datos y maneja transacciones. */
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaccion);
//$UsuarioMySqlDAO = new UsuarioMySqlDAO();

                    $UsuarioMySqlDAO->insert($Usuario);


//$UsuarioMySqlDAO->getTransaction()->commit();

                    $consecutivo_usuario = $Usuario->usuarioId;



                    /* Asigna valores a propiedades de un objeto llamado $Registro para un nuevo usuario. */
                    $Registro->setNombre($nombre);
                    $Registro->setEmail($email);
                    $Registro->setClaveActiva($clave_activa);
                    $Registro->setEstado($estadoUsuarioDefault);
                    $Registro->usuarioId = $consecutivo_usuario;
                    $Registro->setCelular($phone);

                    /* establece valores iniciales para créditos y asigna un ID de ciudad. */
                    $Registro->setCreditosBase(0);
                    $Registro->setCreditos(0);
                    $Registro->setCreditosAnt(0);
                    $Registro->setCreditosBaseAnt(0);
//$Registro->setCiudadId($department_id->cities[0]->id);
                    $Registro->setCiudadId($city_id);

                    /* establece propiedades de un objeto Registro con datos de un usuario. */
                    $Registro->setCasino(0);
                    $Registro->setCasinoBase(0);
                    $Registro->setMandante($Mandante->mandante);
                    $Registro->setNombre1($first_name);
                    $Registro->setNombre2($middle_name);
                    $Registro->setApellido1($last_name);

                    /* Código para establecer atributos de un objeto Registro, incluyendo apellidos, sexo y contacto. */
                    $Registro->setApellido2($second_last_name);
                    $Registro->setSexo($gender);
                    $Registro->setTipoDoc($doctype_id);
                    $Registro->setDireccion($address);
                    $Registro->setTelefono($landline_number);
                    $Registro->setCiudnacimId($citybirth_id);

                    /* establece atributos en un objeto $Registro con valores específicos. */
                    $Registro->setNacionalidadId($nationality_id);
                    $Registro->setDirIp($dir_ip);
                    $Registro->setOcupacionId(0);
                    $Registro->setRangoingresoId(0);
                    $Registro->setOrigenfondosId(0);
                    $Registro->setPaisnacimId($countrybirth_id);

                    /* establece valores iniciales en un objeto de registro. */
                    $Registro->setPuntoVentaId(0);
                    $Registro->setPreregistroId(0);
                    $Registro->setCreditosBono(0);
                    $Registro->setCreditosBonoAnt(0);
                    $Registro->setPreregistroId(0);
                    $Registro->setUsuvalidaId(0);

                    /* actualiza propiedades de un objeto de registro con datos específicos. */
                    $Registro->setFechaValida('');
                    $Registro->setCodigoPostal($cp);


                    $Registro->setCiudexpedId($expcity_id);
                    $Registro->setFechaExped($expedition_year . "-" . $expedition_month . "-" . $expedition_day);

                    /* establece valores en un registro y lo inserta en la base de datos. */
                    $Registro->setPuntoventaId(0);
                    $Registro->setEstadoValida("I");

                    $Registro->setAfiliadorId($afiliador);


                    $RegistroMySqlDAO->insert($Registro);



                    /* Crea una nueva instancia de UsuarioOtrainfo con datos de usuario especificados. */
                    $UsuarioOtrainfo = new UsuarioOtrainfo();

                    $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
                    $UsuarioOtrainfo->fechaNacim = $birth_date;
                    $UsuarioOtrainfo->mandante = $Mandante->mandante;
                    $UsuarioOtrainfo->bancoId = '0';

                    /* Se inicializan propiedades del objeto UsuarioOtrainfo y se crea su DAO correspondiente. */
                    $UsuarioOtrainfo->numCuenta = '0';
                    $UsuarioOtrainfo->anexoDoc = 'N';
                    $UsuarioOtrainfo->direccion = '';
                    $UsuarioOtrainfo->tipoCuenta = '0';


                    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaccion);
//$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();


                    /* Inserta información de usuario y prepara un perfil asociado en MySQL. */
                    $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);
//$UsuarioOtrainfoMySqlDAO->getTransaction()->commit();

                    $UsuarioPerfil = new UsuarioPerfil();

                    $UsuarioPerfil->setUsuarioId($consecutivo_usuario);

                    /* configura un perfil de usuario con atributos específicos y establece conexión DAO. */
                    $UsuarioPerfil->setPerfilId('USUONLINE');
                    $UsuarioPerfil->setMandante($Mandante->mandante);
                    $UsuarioPerfil->setPais('N');
                    $UsuarioPerfil->setGlobal('N');


                    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaccion);
//$UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();

                    /* Inserta un perfil de usuario y inicializa una variable para premio máximo. */
                    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);
//$UsuarioPerfilMySqlDAO->getTransaction()->commit();

                    $UsuarioPremiomax = new UsuarioPremiomax();

                    $premio_max1 = 0;

                    /* Inicializa variables para gestionar premios, apuestas y líneas en un juego. */
                    $premio_max2 = 0;
                    $premio_max3 = 0;
                    $apuesta_min = 0;
                    $cant_lineas = 0;
                    $valor_directo = 0;
                    $valor_evento = 0;

                    /* asigna valores a propiedades del objeto UsuarioPremiomax. */
                    $valor_diario = 0;

                    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

                    $UsuarioPremiomax->premioMax = $premio_max1;

                    $UsuarioPremiomax->usumodifId = '0';



                    /* Asigna valores a propiedades del objeto UsuarioPremiomax. */
                    $UsuarioPremiomax->cantLineas = $cant_lineas;

                    $UsuarioPremiomax->premioMax1 = $premio_max1;

                    $UsuarioPremiomax->premioMax2 = $premio_max2;

                    $UsuarioPremiomax->premioMax3 = $premio_max3;


                    /* asigna valores a propiedades del objeto UsuarioPremiomax. */
                    $UsuarioPremiomax->apuestaMin = $apuesta_min;

                    $UsuarioPremiomax->valorDirecto = $valor_directo;
                    $UsuarioPremiomax->premioDirecto = $valor_directo;


                    $UsuarioPremiomax->mandante = $Mandante->mandante;

                    /* Se asignan valores a propiedades de un objeto relacionado con usuarios y eventos. */
                    $UsuarioPremiomax->optimizarParrilla = 'N';


                    $UsuarioPremiomax->valorEvento = $valor_evento;

                    $UsuarioPremiomax->valorDiario = $valor_diario;


                    /* Se crea un objeto DAO y se inserta un usuario premiormax en la base de datos. */
                    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaccion);
//$UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
                    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);
//$UsuarioPremiomaxMySqlDAO->getTransaction()->commit();


                    $UsuarioMandante = new UsuarioMandante();


                    /* Se asignan propiedades del objeto $Usuario a $UsuarioMandante. */
                    $UsuarioMandante->mandante = $Usuario->mandante;

                    $UsuarioMandante->nombres = $Usuario->nombre;
                    $UsuarioMandante->apellidos = $Usuario->nombre;
                    $UsuarioMandante->estado = 'A';
                    $UsuarioMandante->email = $Usuario->login;

                    /* Se asignan valores del usuario a un objeto UsuarioMandante y se inicializan otros. */
                    $UsuarioMandante->moneda = $Usuario->moneda;
                    $UsuarioMandante->paisId = $Usuario->paisId;
                    $UsuarioMandante->saldo = 0;
                    $UsuarioMandante->usuarioMandante = $consecutivo_usuario;
                    $UsuarioMandante->usucreaId = 0;
                    $UsuarioMandante->usumodifId = 0;

                    /* Se inserta un usuario mandante y se confirma la transacción en la base de datos. */
                    $UsuarioMandante->propio = 'S';

                    $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaccion);
                    $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);


                    $Transaccion->commit();


                    /* Cambia la contraseña del usuario utilizando el método changeClave con el nuevo password. */
                    $Usuario->changeClave($password);

                    if ($Usuario->mandante == 21) { // este condicional es para realizar el registro a cammanbet

                        /* Asigna un ID de residente basado en el país del usuario. */
                        $IdFirstUser = $Usuario->usuarioId;

                        if ($Usuario->paisId == 243) {
                            $countryResident_id = 232;
                        } else if ($Usuario->paisId == 232) {
                            $countryResident_id = 243;
                        }


                        /* Inicializa una clase de país y obtiene su moneda por defecto. */
                        $PaisMandante = new PaisMandante('', $site_id, $countryResident_id);

                        $moneda_default = $PaisMandante->moneda;


                        /* $Consecutivo = new Consecutivo("", "USU", "");

                        $consecutivo_usuario = $Consecutivo->numero;

                        $consecutivo_usuario++;

                        $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                        $Consecutivo->setNumero($consecutivo_usuario);


                        $ConsecutivoMySqlDAO->update($Consecutivo);

                        $ConsecutivoMySqlDAO->getTransaction()->commit();*/


                        $premio_max = "";

                        /* Variables para almacenar premios, líneas, identificadores y registro de regalos. */
                        $premio_max1 = "";
                        $premio_max2 = "";
                        $premio_max3 = "";
                        $cant_lineas = "";
                        $lista_id = "";
                        $regalo_registro = "";

                        /* Variables inicializadas como strings vacíos para almacenar valores posteriores en programación. */
                        $valor_directo = "";
                        $valor_evento = "";
                        $valor_diario = "";
                        $destin1 = "";
                        $destin2 = "";
                        $destin3 = "";


                        /* genera un token, obtiene una IP y crea una instancia de acceso a MySQL. */
                        $apuesta_min = "";

                        $token_itainment = $ConfigurationEnvironment->GenerarClaveTicket2(12);

                        $dir_ip = $json->session->usuarioip;

                        $RegistroMySqlDAO = new RegistroMySqlDAO();

                        /* obtiene una transacción y establece el login de un usuario. */
                        $Transaction = $RegistroMySqlDAO->getTransaction();


                        $Transaccion = $RegistroMySqlDAO->getTransaction();

//$Usuario->usuarioId = $consecutivo_usuario;

                        $Usuario->login = $email;


                        /* Se asignan propiedades a un objeto Usuario, incluyendo nombre, estado y fecha. */
                        $Usuario->nombre = $nombre;

                        $Usuario->estado = $estadoUsuarioDefault;

                        $Usuario->fechaUlt = date('Y-m-d H:i:s');

                        $Usuario->claveTv = '';


                        /* Se inicializan variables relacionadas con el estado y observaciones del usuario. */
                        $Usuario->estadoAnt = 'I';

                        $Usuario->intentos = 0;

                        $Usuario->estadoEsp = $estadoUsuarioDefault;

                        $Usuario->observ = '';


                        /* Se asignan propiedades al objeto Usuario, como IP, estado y mandante. */
                        $Usuario->dirIp = $registro_ip;

                        $Usuario->eliminado = 'N';

                        $Usuario->mandante = $Mandante->mandante;

                        $Usuario->usucreaId = '0';


                        /* Asignación de valores a propiedades del objeto Usuario en un sistema de gestión. */
                        $Usuario->usumodifId = '0';

                        $Usuario->claveCasino = '';

                        $Usuario->tokenItainment = $token_itainment;

                        $Usuario->fechaClave = '';


                        /* Se inicializan propiedades de un objeto "Usuario" relacionadas con retiros. */
                        $Usuario->retirado = '';

                        $Usuario->fechaRetiro = '';

                        $Usuario->horaRetiro = '';

                        $Usuario->usuretiroId = '0';


                        /* Se asignan valores a propiedades de un objeto Usuario relacionado con ventas y estado. */
                        $Usuario->bloqueoVentas = 'N';

                        $Usuario->infoEquipo = '';

                        $Usuario->estadoJugador = 'AC';

                        $Usuario->tokenCasino = '';


                        /* Asignación de propiedades a un objeto Usuario, incluyendo sponsor, verificación, país y moneda. */
                        $Usuario->sponsorId = 0;

                        $Usuario->verifCorreo = 'N';

                        $Usuario->paisId = $countryResident_id;

                        $Usuario->moneda = $moneda_default;


                        /* Configuración de propiedades de un objeto Usuario en un sistema. */
                        $Usuario->idioma = $idioma;

                        $Usuario->permiteActivareg = 'N';

                        $Usuario->test = 'N';

                        $Usuario->tiempoLimitedeposito = 0;


                        /* Asignación de propiedades a un objeto Usuario, configurando autoexclusión, aprobación y zona horaria. */
                        $Usuario->tiempoAutoexclusion = 0;

                        $Usuario->cambiosAprobacion = 'S';

                        $Usuario->timezone = '-5';

                        $Usuario->puntoventaId = 0;

                        /* Se inicializan varios IDs de un objeto Usuario con valores predeterminados. */
                        $Usuario->usucreaId = 0;
                        $Usuario->usumodifId = 0;
                        $Usuario->usuretiroId = 0;
                        $Usuario->sponsorId = $afiliador;

                        $Usuario->puntoventaId = 0;


                        /* Inicializa y asigna valores a propiedades de un objeto Usuario. */
                        $Usuario->fechaCrea = $registro_fecha;

                        $Usuario->origen = 0;

                        $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                        $Usuario->documentoValidado = "I";

                        /* Se asignan valores a propiedades del objeto $Usuario para validar su estado. */
                        $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                        $Usuario->usuDocvalido = 0;


                        $Usuario->estadoValida = 'N';
                        $Usuario->usuvalidaId = 0;

                        /* Asigna fecha y estado de contingencia a un objeto de usuario. */
                        $Usuario->fechaValida = date('Y-m-d H:i:s');
                        $Usuario->contingencia = 'I';
                        $Usuario->contingenciaDeportes = 'I';
                        $Usuario->contingenciaCasino = 'I';
                        $Usuario->contingenciaCasvivo = 'I';
                        $Usuario->contingenciaVirtuales = 'I';

                        /* Se definen propiedades del objeto Usuario relacionadas con contingencias y ubicación. */
                        $Usuario->contingenciaPoker = 'I';
                        $Usuario->restriccionIp = 'I';
                        $Usuario->ubicacionLongitud = '';
                        $Usuario->ubicacionLatitud = '';
                        $Usuario->usuarioIp = '';
                        $Usuario->tokenGoogle = "I";

                        /* Asignación de valores a propiedades del objeto $Usuario para manejo de autenticación y plataformas. */
                        $Usuario->tokenLocal = "I";
                        $Usuario->saltGoogle = '';


                        $Usuario->skype = '';
                        $Usuario->plataforma = 0;



                        /* asigna valores a atributos de un objeto Usuario. */
                        $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                        $Usuario->documentoValidado = "A";
                        $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                        $Usuario->usuDocvalido = 0;


                        $Usuario->estadoImport = 1;


                        /* Código para insertar un usuario en base de datos utilizando DAO en MySQL. */
                        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaccion);
//$UsuarioMySqlDAO = new UsuarioMySqlDAO();

                        $UsuarioMySqlDAO->insert($Usuario);


//$UsuarioMySqlDAO->getTransaction()->commit();

                        $consecutivo_usuario = $Usuario->usuarioId;



                        /* establece atributos de un objeto 'Registro' con datos del usuario. */
                        $Registro->setNombre($nombre);
                        $Registro->setEmail($email);
                        $Registro->setClaveActiva($clave_activa);
                        $Registro->setEstado($estadoUsuarioDefault);
                        $Registro->usuarioId = $consecutivo_usuario;
                        $Registro->setCelular($phone);

                        /* Código que inicializa valores de créditos y asigna una ciudad en un registro. */
                        $Registro->setCreditosBase(0);
                        $Registro->setCreditos(0);
                        $Registro->setCreditosAnt(0);
                        $Registro->setCreditosBaseAnt(0);
//$Registro->setCiudadId($department_id->cities[0]->id);
                        $Registro->setCiudadId($city_id);

                        /* establece valores para propiedades de un objeto "Registro". */
                        $Registro->setCasino(0);
                        $Registro->setCasinoBase(0);
                        $Registro->setMandante($Mandante->mandante);
                        $Registro->setNombre1($first_name);
                        $Registro->setNombre2($middle_name);
                        $Registro->setApellido1($last_name);

                        /* establece propiedades para un objeto de registro personal. */
                        $Registro->setApellido2($second_last_name);
                        $Registro->setSexo($gender);
                        $Registro->setTipoDoc($doctype_id);
                        $Registro->setDireccion($address);
                        $Registro->setTelefono($landline_number);
                        $Registro->setCiudnacimId($citybirth_id);

                        /* establece varios atributos en un objeto Registro, relacionados con un usuario. */
                        $Registro->setNacionalidadId($nationality_id);
                        $Registro->setDirIp($dir_ip);
                        $Registro->setOcupacionId(0);
                        $Registro->setRangoingresoId(0);
                        $Registro->setOrigenfondosId(0);
                        $Registro->setPaisnacimId($countrybirth_id);

                        /* establece valores iniciales para varios atributos de un objeto Registro. */
                        $Registro->setPuntoVentaId(0);
                        $Registro->setPreregistroId(0);
                        $Registro->setCreditosBono(0);
                        $Registro->setCreditosBonoAnt(0);
                        $Registro->setPreregistroId(0);
                        $Registro->setUsuvalidaId(0);

                        /* establece propiedades en un objeto Registro utilizando datos específicos. */
                        $Registro->setFechaValida('');
                        $Registro->setCodigoPostal($cp);


                        $Registro->setCiudexpedId($expcity_id);
                        $Registro->setFechaExped($expedition_year . "-" . $expedition_month . "-" . $expedition_day);

                        /* Se establece un nuevo registro en la base de datos con valores específicos. */
                        $Registro->setPuntoventaId(0);
                        $Registro->setEstadoValida("I");

                        $Registro->setAfiliadorId($afiliador);


                        $RegistroMySqlDAO->insert($Registro);



                        /* Se crea un objeto de UsuarioOtrainfo con propiedades asignadas. */
                        $UsuarioOtrainfo = new UsuarioOtrainfo();

                        $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
                        $UsuarioOtrainfo->fechaNacim = $birth_date;
                        $UsuarioOtrainfo->mandante = $Mandante->mandante;
                        $UsuarioOtrainfo->bancoId = '0';

                        /* Se asignan valores a propiedades de un objeto antes de crear DAO para la base de datos. */
                        $UsuarioOtrainfo->numCuenta = '0';
                        $UsuarioOtrainfo->anexoDoc = 'N';
                        $UsuarioOtrainfo->direccion = '';
                        $UsuarioOtrainfo->tipoCuenta = '0';


                        $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaccion);
//$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();


                        /* inserta información de usuario y configura un perfil asociado a él. */
                        $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);
//$UsuarioOtrainfoMySqlDAO->getTransaction()->commit();

                        $UsuarioPerfil = new UsuarioPerfil();

                        $UsuarioPerfil->setUsuarioId($consecutivo_usuario);

                        /* configura un perfil de usuario y crea un objeto de acceso a MySQL. */
                        $UsuarioPerfil->setPerfilId('USUONLINE');
                        $UsuarioPerfil->setMandante($Mandante->mandante);
                        $UsuarioPerfil->setPais('N');
                        $UsuarioPerfil->setGlobal('N');


                        $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaccion);
//$UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();

                        /* Inserta un perfil de usuario y prepara la creación de un objeto premio máximo. */
                        $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);
//$UsuarioPerfilMySqlDAO->getTransaction()->commit();

                        $UsuarioPremiomax = new UsuarioPremiomax();

                        $premio_max1 = 0;

                        /* Inicializa variables para gestionar premios, apuestas y líneas en un sistema de juego. */
                        $premio_max2 = 0;
                        $premio_max3 = 0;
                        $apuesta_min = 0;
                        $cant_lineas = 0;
                        $valor_directo = 0;
                        $valor_evento = 0;

                        /* Inicializa variables para un usuario y asigna valores a sus propiedades. */
                        $valor_diario = 0;

                        $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

                        $UsuarioPremiomax->premioMax = $premio_max1;

                        $UsuarioPremiomax->usumodifId = '0';



                        /* Se asignan valores a propiedades de un objeto en programación orientada a objetos. */
                        $UsuarioPremiomax->cantLineas = $cant_lineas;

                        $UsuarioPremiomax->premioMax1 = $premio_max1;

                        $UsuarioPremiomax->premioMax2 = $premio_max2;

                        $UsuarioPremiomax->premioMax3 = $premio_max3;


                        /* Asignación de valores a propiedades de un objeto $UsuarioPremiomax. */
                        $UsuarioPremiomax->apuestaMin = $apuesta_min;

                        $UsuarioPremiomax->valorDirecto = $valor_directo;
                        $UsuarioPremiomax->premioDirecto = $valor_directo;


                        $UsuarioPremiomax->mandante = $Mandante->mandante;

                        /* asigna valores a propiedades de un objeto llamado UsuarioPremiomax. */
                        $UsuarioPremiomax->optimizarParrilla = 'N';


                        $UsuarioPremiomax->valorEvento = $valor_evento;

                        $UsuarioPremiomax->valorDiario = $valor_diario;


                        /* Se crea un DAO para insertar un usuario y preparar otro objeto. */
                        $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaccion);
//$UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
                        $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);
//$UsuarioPremiomaxMySqlDAO->getTransaction()->commit();


                        $UsuarioMandante = new UsuarioMandante();


                        /* Asigna propiedades de un objeto usuario a otro, estableciendo un nuevo usuario mandante. */
                        $UsuarioMandante->mandante = $Usuario->mandante;

                        $UsuarioMandante->nombres = $Usuario->nombre;
                        $UsuarioMandante->apellidos = $Usuario->nombre;
                        $UsuarioMandante->estado = 'A';
                        $UsuarioMandante->email = $Usuario->login;

                        /* Se asignan propiedades del objeto Usuario a UsuarioMandante con valores específicos. */
                        $UsuarioMandante->moneda = $Usuario->moneda;
                        $UsuarioMandante->paisId = $Usuario->paisId;
                        $UsuarioMandante->saldo = 0;
                        $UsuarioMandante->usuarioMandante = $consecutivo_usuario;
                        $UsuarioMandante->usucreaId = 0;
                        $UsuarioMandante->usumodifId = 0;

                        /* Inserta un nuevo usuario mandante en la base de datos y confirma la transacción. */
                        $UsuarioMandante->propio = 'S';

                        $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaccion);
                        $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);


                        $Transaccion->commit();



                        /* Actualiza la contraseña del usuario y asigna cuentas asociadas con identificadores. */
                        $Usuario->changeClave($password);


                        $CuentaAsociada = new CuentaAsociada();
                        $CuentaAsociada->setUsuarioId($IdFirstUser);
                        $CuentaAsociada->setUsuarioId2($Usuario->usuarioId);

                        /* Inserta una nueva cuenta asociada y gestiona la transacción en MySQL. */
                        $CuentaAsociada->SetUsucreaId($IdFirstUser);
                        $CuentaAsociada->setUsumodifId(0);

                        $CuentaAsociadaMySqlDAO = new CuentaAsociadaMySqlDAO();
                        $CuentaAsociadaMySqlDAO->insert($CuentaAsociada);
                        $CuentaAsociadaMySqlDAO->getTransaction()->commit();
                    }


                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = array(
                        "result" => "OK"

                    );


                } else {
                    $response["data"] = array(
                        "result" => "-1123"

                    );
                }

            }

        } catch (Exception $e) {
            //print_r($e);
        }


    }

}

if (false) {
    print_r($params);
    $fichero_subido = '/home/home2/backend/api/apipv/cases/Admin/import.csv';
    exit();
    if (isset($_POST["submit"])) {

        if (isset($_FILES["file"])) {

            //if there was an error uploading the file
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

            } else {
                //Print file details
                echo "Upload: " . $_FILES["file"]["name"] . "<br />";
                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                //if file already exists
                if (file_exists("upload/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " already exists. ";
                } else {

                    //Store file in directory "upload" with the name of "uploaded_file.txt"
                    $storagename = "uploaded_file.txt";
                    move_uploaded_file($_FILES["file"]["tmp_name"], $fichero_subido);
                    echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
                }

                if (isset($storagename) && $file = fopen($fichero_subido, r)) {

                    echo "File opened.<br />";

                    $firstline = fgets($file, 4096);
                    //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
                    $num = strlen($firstline) - strlen(str_replace(";", "", $firstline));

                    //save the different fields of the firstline in an array called fields
                    $fields = array();
                    $fields = explode(";", $firstline, ($num + 1));

                    $line = array();
                    $i = 0;

                    //CSV: one line is one record and the cells/fields are seperated by ";"
                    //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
                    while ($line[$i] = fgets($file, 4096)) {

                        $dsatz[$i] = array();
                        $dsatz[$i] = explode(";", $line[$i], ($num + 1));


                        $i++;
                    }

                    echo "<table>";
                    echo "<tr>";


                    for ($k = 0; $k != ($num + 1); $k++) {


                        // echo "<td>" . $fields[$k] . "</td>";
                    }
                    echo "</tr>";

                    foreach ($dsatz as $key => $number) {
                        print_r($number);

                        $k = 0;
                        $PlayerId = $number[$k];
                        $k++;
                        $celular = $number[$k];
                        $k++;
                        $cedula = $number[$k];
                        $k++;
                        $nombre1 = $number[$k];

                        $nombre1 = substr($nombre1, 0, 19);

                        $k++;
                        $nombre2 = $number[$k];

                        $nombre2 = substr($nombre2, 0, 19);

                        $k++;
                        $apellido1 = $number[$k];

                        $apellido1 = substr($apellido1, 0, 19);
                        $k++;
                        $apellido2 = $number[$k];

                        $apellido2 = substr($apellido2, 0, 19);
                        $k++;
                        /*$apellido2=$number[$k];
                        $k++;*/
                        $fecha_nacim = $number[$k];
                        $k++;
                        $email = $number[$k];
                        $k++;
                        $pais = $number[$k];
                        $k++;
                        $departamento = $number[$k];
                        $k++;
                        $ciudad = $number[$k];
                        $k++;
                        $direccion = $number[$k];
                        $k++;
                        $codigo_postal = $number[$k];
                        $k++;
                        $estado = $number[$k];
                        $k++;
                        $registro_ip = $number[$k];
                        $k++;
                        $registro_fecha = $number[$k];
                        $k++;
                        $telefono_fijo = $number[$k];
                        $k++;
                        $tipo_de_documento = $number[$k];
                        $k++;
                        $afiliador = $number[$k];
                        $k++;

                        if ($afiliador == "") {
                            $afiliador = 0;
                        }

                        $genero = 'M';
                        //$telefono_fijo = '';


                        try {
                            if (($cedula != "" && $celular != "") || true) {

                                $user_info = $json->params->user_info;
                                $type_register = 0;

                                //Mandante
                                $site_id = '15';


                                //Pais de residencia
                                $countryResident_id = $pais;

                                //Idioma
                                $lang_code = 'ES';
                                $idioma = strtoupper($lang_code);


                                $Mandante = new Mandante($site_id);
                                $Pais = new Pais($pais);
                                $PaisMoneda = new PaisMoneda($pais);

                                $moneda_default = $PaisMoneda->moneda;

                                $estadoUsuarioDefault = 'A';

                                $Clasificador = new Clasificador("", "REGISTERACTIVATION");

                                try {
                                    $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

                                    $estadoUsuarioDefault = (intval($MandanteDetalle->getValor()) == 1) ? "A" : "I";


                                } catch (Exception $e) {

                                    if ($e->getCode() == 34) {
                                    } else {
                                    }
                                }
                                $estadoUsuarioDefault = 'A';


                                $address = $direccion;
                                $birth_date = $fecha_nacim;


                                $department_id = $departamento;
                                $docnumber = $cedula;
                                $doctype_id = 1;

                                $email = $email;

                                $expedition_day = '00';
                                $expedition_month = '00';
                                $expedition_year = '0000';
                                $first_name = $nombre1;
                                $middle_name = $nombre2;
                                $last_name = $apellido1;
                                $second_last_name = $apellido2;

                                $gender = $genero;

                                $landline_number = $telefono_fijo;
                                $language = 'EN';


                                $limit_deposit_day = 0;
                                $limit_deposit_month = 0;
                                $limit_deposit_week = 0;

                                $nationality_id = $pais;

                                $password = 'raphiw-mirfu4-jopweB';
                                $ConfigurationEnvironment = new ConfigurationEnvironment();
                                $password = $ConfigurationEnvironment->GenerarClaveTicket(15);
                                $phone = $celular;


                                $city_id = $ciudad;

                                $countrybirth_id = 0;
                                $departmentbirth_id = 0;
                                $citybirth_id = 0;
                                $cp = $codigo_postal;


                                $expdept_id = 0;
                                $expcity_id = 0;

                                $nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;
                                $clave_activa = $ConfigurationEnvironment->GenerarClaveTicket(15);

                                if ($type_register == 1) {
                                    if ($countryResident_id->Id == 173) {
                                        $gender = 'M';
                                        $registroCorto = true;
                                        $nationality_id->Id = 173;
                                        $ciudad_id = 9295;
                                        $city_id->Id = 9295;
                                        $expcity_id->Id = 0;
                                    }
                                    if ($countryResident_id->Id == 2) {
                                        $gender = 'M';
                                        $registroCorto = true;
                                        $nationality_id->Id = 2;
                                        $ciudad_id = 9196;
                                        $city_id->Id = 9196;
                                        $expcity_id->Id = 0;

                                    }

                                }

                                if ($pais_residencia == 173 || $pais_residencia == 2) {
                                    $registroCorto = true;
                                }

                                if (($registroCorto) && $birth_date == "") {
                                    $origen = 1;
                                    $birth_date = '1970-01-01';
                                }

                                if (($registroCorto) && $depto_nacimiento == "") {
                                    $depto_nacimiento = '0';
                                }

                                if (($registroCorto) && $ciudad_nacimiento == "") {
                                    $ciudad_nacimiento = '0';
                                }

                                if (($registroCorto) && $ocupacion == "") {
                                    $ocupacion = '0';
                                }

                                if (($registroCorto) && $rangoingreso_id == "") {
                                    $rangoingreso_id = '0';
                                }

                                if (($registroCorto) && $origen_fondos == "") {
                                    $origen_fondos = '0';
                                }

                                if (($registroCorto) && ($countrybirth_id->Id == "")) {
                                    $paisnacim_id = '0';
                                    $countrybirth_id->Id = '0';
                                }

                                if (($registroCorto) && $idioma == "") {
                                    $idioma = 'ES';
                                }

                                switch ($doctype_id) {
                                    case 1:
                                        $doctype_id = "C";
                                        break;

                                    case 2:
                                        $doctype_id = "E";

                                        break;

                                    case 3:

                                        $doctype_id = "P";

                                        break;

                                    default:
                                        //throw new Exception("Inusual Detected", "11");

                                        break;
                                }

                                $tipo_de_documento = 'C';
                                switch (strtoupper($tipo_de_documento)) {
                                    case 'DNI':
                                        $doctype_id = "C";
                                        break;
                                    case 'CE':
                                        $doctype_id = "CE";
                                        break;
                                    case 'C':
                                        $doctype_id = "C";
                                        break;

                                    case 'P':
                                        $doctype_id = "P";

                                        break;
                                    default:
                                        if ($tipo_de_documento == 'C') {
                                            print_r("ENTRO");
                                        }
                                        print_r($tipo_de_documento);
                                        print_r("ENTRO2");
                                        exit();
                                        throw new Exception("Inusual Detected", "11");

                                        break;
                                }


                                if ($email == '') {
                                    throw new Exception("Inusual Detected", "11");
                                }

                                $Usuario = new Usuario();
                                $Usuario->login = $email;
                                $Usuario->mandante = $Mandante->mandante;

                                /* Verificamos si existe el email para el partner */
                                $checkLogin = $Usuario->exitsLogin();
                                if ($checkLogin) {
                                    throw new Exception("Inusual Detected", "11");

                                }


                                $Registro = new Registro();
                                $Registro->setCedula($docnumber);
                                $Registro->setMandante($Mandante->mandante);

                                //if (!$Registro->existeCedula() ) {
                                if (true) {

                                    /* $Consecutivo = new Consecutivo("", "USU", "");

                                     $consecutivo_usuario = $Consecutivo->numero;

                                     $consecutivo_usuario++;

                                     $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                                     $Consecutivo->setNumero($consecutivo_usuario);


                                     $ConsecutivoMySqlDAO->update($Consecutivo);

                                     $ConsecutivoMySqlDAO->getTransaction()->commit();*/


                                    $premio_max = "";
                                    $premio_max1 = "";
                                    $premio_max2 = "";
                                    $premio_max3 = "";
                                    $cant_lineas = "";
                                    $lista_id = "";
                                    $regalo_registro = "";
                                    $valor_directo = "";
                                    $valor_evento = "";
                                    $valor_diario = "";
                                    $destin1 = "";
                                    $destin2 = "";
                                    $destin3 = "";

                                    $apuesta_min = "";

                                    $token_itainment = $ConfigurationEnvironment->GenerarClaveTicket2(12);

                                    $dir_ip = $json->session->usuarioip;

                                    $RegistroMySqlDAO = new RegistroMySqlDAO();
                                    $Transaction = $RegistroMySqlDAO->getTransaction();


                                    $Transaccion = $RegistroMySqlDAO->getTransaction();

                                    //$Usuario->usuarioId = $consecutivo_usuario;

                                    $Usuario->login = $email;

                                    $Usuario->nombre = $nombre;

                                    $Usuario->estado = $estadoUsuarioDefault;

                                    $Usuario->fechaUlt = date('Y-m-d H:i:s');

                                    $Usuario->claveTv = '';

                                    $Usuario->estadoAnt = 'I';

                                    $Usuario->intentos = 0;

                                    $Usuario->estadoEsp = $estadoUsuarioDefault;

                                    $Usuario->observ = '';

                                    $Usuario->dirIp = $registro_ip;

                                    $Usuario->eliminado = 'N';

                                    $Usuario->mandante = $Mandante->mandante;

                                    $Usuario->usucreaId = '0';

                                    $Usuario->usumodifId = '0';

                                    $Usuario->claveCasino = '';

                                    $Usuario->tokenItainment = $token_itainment;

                                    $Usuario->fechaClave = '';

                                    $Usuario->retirado = '';

                                    $Usuario->fechaRetiro = '';

                                    $Usuario->horaRetiro = '';

                                    $Usuario->usuretiroId = '0';

                                    $Usuario->bloqueoVentas = 'N';

                                    $Usuario->infoEquipo = '';

                                    $Usuario->estadoJugador = 'AC';

                                    $Usuario->tokenCasino = '';

                                    $Usuario->sponsorId = 0;

                                    $Usuario->verifCorreo = 'N';

                                    $Usuario->paisId = $countryResident_id;

                                    $Usuario->moneda = $moneda_default;

                                    $Usuario->idioma = $idioma;

                                    $Usuario->permiteActivareg = 'N';

                                    $Usuario->test = 'N';

                                    $Usuario->tiempoLimitedeposito = 0;

                                    $Usuario->tiempoAutoexclusion = 0;

                                    $Usuario->cambiosAprobacion = 'S';

                                    $Usuario->timezone = '-5';

                                    $Usuario->puntoventaId = 0;
                                    $Usuario->usucreaId = 0;
                                    $Usuario->usumodifId = 0;
                                    $Usuario->usuretiroId = 0;
                                    $Usuario->sponsorId = $afiliador;

                                    $Usuario->puntoventaId = 0;

                                    $Usuario->fechaCrea = $registro_fecha;

                                    $Usuario->origen = 0;

                                    $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                                    $Usuario->documentoValidado = "I";
                                    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                                    $Usuario->usuDocvalido = 0;


                                    $Usuario->estadoValida = 'N';
                                    $Usuario->usuvalidaId = 0;
                                    $Usuario->fechaValida = date('Y-m-d H:i:s');
                                    $Usuario->contingencia = 'I';
                                    $Usuario->contingenciaDeportes = 'I';
                                    $Usuario->contingenciaCasino = 'I';
                                    $Usuario->contingenciaCasvivo = 'I';
                                    $Usuario->contingenciaVirtuales = 'I';
                                    $Usuario->contingenciaPoker = 'I';
                                    $Usuario->restriccionIp = 'I';
                                    $Usuario->ubicacionLongitud = '';
                                    $Usuario->ubicacionLatitud = '';
                                    $Usuario->usuarioIp = '';
                                    $Usuario->tokenGoogle = "I";
                                    $Usuario->tokenLocal = "I";
                                    $Usuario->saltGoogle = '';


                                    $Usuario->skype = '';
                                    $Usuario->plataforma = 0;


                                    $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                                    $Usuario->documentoValidado = "A";
                                    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                                    $Usuario->usuDocvalido = 0;


                                    $Usuario->estadoImport = 1;

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaccion);
                                    //$UsuarioMySqlDAO = new UsuarioMySqlDAO();

                                    $UsuarioMySqlDAO->insert($Usuario);


                                    //$UsuarioMySqlDAO->getTransaction()->commit();

                                    $consecutivo_usuario = $Usuario->usuarioId;


                                    $Registro->setNombre($nombre);
                                    $Registro->setEmail($email);
                                    $Registro->setClaveActiva($clave_activa);
                                    $Registro->setEstado($estadoUsuarioDefault);
                                    $Registro->usuarioId = $consecutivo_usuario;
                                    $Registro->setCelular($phone);
                                    $Registro->setCreditosBase(0);
                                    $Registro->setCreditos(0);
                                    $Registro->setCreditosAnt(0);
                                    $Registro->setCreditosBaseAnt(0);
                                    //$Registro->setCiudadId($department_id->cities[0]->id);
                                    $Registro->setCiudadId($city_id);
                                    $Registro->setCasino(0);
                                    $Registro->setCasinoBase(0);
                                    $Registro->setMandante($Mandante->mandante);
                                    $Registro->setNombre1($first_name);
                                    $Registro->setNombre2($middle_name);
                                    $Registro->setApellido1($last_name);
                                    $Registro->setApellido2($second_last_name);
                                    $Registro->setSexo($gender);
                                    $Registro->setTipoDoc($doctype_id);
                                    $Registro->setDireccion($address);
                                    $Registro->setTelefono($landline_number);
                                    $Registro->setCiudnacimId($citybirth_id);
                                    $Registro->setNacionalidadId($nationality_id);
                                    $Registro->setDirIp($dir_ip);
                                    $Registro->setOcupacionId(0);
                                    $Registro->setRangoingresoId(0);
                                    $Registro->setOrigenfondosId(0);
                                    $Registro->setPaisnacimId($countrybirth_id);
                                    $Registro->setPuntoVentaId(0);
                                    $Registro->setPreregistroId(0);
                                    $Registro->setCreditosBono(0);
                                    $Registro->setCreditosBonoAnt(0);
                                    $Registro->setPreregistroId(0);
                                    $Registro->setUsuvalidaId(0);
                                    $Registro->setFechaValida('');
                                    $Registro->setCodigoPostal($cp);


                                    $Registro->setCiudexpedId($expcity_id);
                                    $Registro->setFechaExped($expedition_year . "-" . $expedition_month . "-" . $expedition_day);
                                    $Registro->setPuntoventaId(0);
                                    $Registro->setEstadoValida("I");

                                    $Registro->setAfiliadorId($afiliador);


                                    $RegistroMySqlDAO->insert($Registro);


                                    $UsuarioOtrainfo = new UsuarioOtrainfo();

                                    $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
                                    $UsuarioOtrainfo->fechaNacim = $birth_date;
                                    $UsuarioOtrainfo->mandante = $Mandante->mandante;
                                    $UsuarioOtrainfo->bancoId = '0';
                                    $UsuarioOtrainfo->numCuenta = '0';
                                    $UsuarioOtrainfo->anexoDoc = 'N';
                                    $UsuarioOtrainfo->direccion = '';
                                    $UsuarioOtrainfo->tipoCuenta = '0';


                                    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaccion);
                                    //$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();

                                    $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);
                                    //$UsuarioOtrainfoMySqlDAO->getTransaction()->commit();

                                    $UsuarioPerfil = new UsuarioPerfil();

                                    $UsuarioPerfil->setUsuarioId($consecutivo_usuario);
                                    $UsuarioPerfil->setPerfilId('USUONLINE');
                                    $UsuarioPerfil->setMandante($Mandante->mandante);
                                    $UsuarioPerfil->setPais('N');
                                    $UsuarioPerfil->setGlobal('N');


                                    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaccion);
                                    //$UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
                                    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);
                                    //$UsuarioPerfilMySqlDAO->getTransaction()->commit();

                                    $UsuarioPremiomax = new UsuarioPremiomax();

                                    $premio_max1 = 0;
                                    $premio_max2 = 0;
                                    $premio_max3 = 0;
                                    $apuesta_min = 0;
                                    $cant_lineas = 0;
                                    $valor_directo = 0;
                                    $valor_evento = 0;
                                    $valor_diario = 0;

                                    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

                                    $UsuarioPremiomax->premioMax = $premio_max1;

                                    $UsuarioPremiomax->usumodifId = '0';


                                    $UsuarioPremiomax->cantLineas = $cant_lineas;

                                    $UsuarioPremiomax->premioMax1 = $premio_max1;

                                    $UsuarioPremiomax->premioMax2 = $premio_max2;

                                    $UsuarioPremiomax->premioMax3 = $premio_max3;

                                    $UsuarioPremiomax->apuestaMin = $apuesta_min;

                                    $UsuarioPremiomax->valorDirecto = $valor_directo;
                                    $UsuarioPremiomax->premioDirecto = $valor_directo;


                                    $UsuarioPremiomax->mandante = $Mandante->mandante;
                                    $UsuarioPremiomax->optimizarParrilla = 'N';


                                    $UsuarioPremiomax->valorEvento = $valor_evento;

                                    $UsuarioPremiomax->valorDiario = $valor_diario;

                                    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaccion);
                                    //$UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
                                    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);
                                    //$UsuarioPremiomaxMySqlDAO->getTransaction()->commit();

                                    $Transaccion->commit();

                                    $Usuario->changeClave($password);


                                    $response = array();
                                    $response["code"] = 0;
                                    $response["rid"] = $json->rid;
                                    $response["data"] = array(
                                        "result" => "OK"

                                    );

                                    print_r($Usuario);

                                } else {
                                    $response["data"] = array(
                                        "result" => "-1123"

                                    );
                                }

                            }

                        } catch (Exception $e) {
                            print_r($e);
                        }

                        //new table row for every record
                        echo "<tr>";
                        foreach ($number as $k => $content) {
                            //new table cell for every field of the record
                            //echo "<td>" . $content . "</td>";
                        }
                    }

                    echo "</table>";
                }

            }
        } else {
            echo "No file selected <br />";
        }
    }
    ?>

    <table width="600">
        <form action="" method="post" enctype="multipart/form-data">

            <tr>
                <td width="20%">Select file</td>
                <td width="80%"><input type="file" name="file" id="file"/></td>
            </tr>

            <tr>
                <td>Submit</td>
                <td><input type="submit" name="submit"/></td>
            </tr>

        </form>
    </table>
    <?php

}


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
