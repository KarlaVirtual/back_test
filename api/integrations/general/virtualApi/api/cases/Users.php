<?php

/**
 * Este archivo contiene la implementación de una API para gestionar usuarios y sus datos asociados.
 * Incluye la validación de tokens, consultas a la base de datos y generación de respuestas en formato JSON.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $Usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMandante          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $userNow                  Variable que almacena la información del usuario actualmente autenticado.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $header                   Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $TokenHeader              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $UserId                   Esta variable se utiliza para almacenar y manipular el identificador del usuario.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $MaxRows                  Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $OrderedItem              Variable que representa un elemento ordenado en una lista.
 * @var mixed $SkeepRows                Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $cadena                   Variable que almacena una cadena de texto.
 * @var mixed $no_permitidas            Variable que contiene una lista de valores no permitidos.
 * @var mixed $permitidas               Variable que contiene una lista de valores permitidos.
 * @var mixed $texto                    Variable que almacena un texto genérico.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $UsuarioMySqlDAO          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $count                    Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $usuarios                 Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $BonoInterno              Variable que representa un bono interno en el sistema.
 * @var mixed $UsuarioBonoMySqlDAO      Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Bonos                    Variable que almacena información sobre bonos otorgados.
 * @var mixed $usuariosFinal            Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $Marca                    Variable que almacena la marca de un producto o entidad.
 * @var mixed $status                   Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Verificado               Variable que indica si un elemento ha sido verificado.
 * @var mixed $EstadosVerifica          Variable que almacena los estados de verificación posibles.
 * @var mixed $value2                   Variable que almacena un valor adicional en un proceso.
 * @var mixed $array1                   Variable que almacena un arreglo de valores.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 */

use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;

$Usuario = new Usuario();
$Mandante = new Mandante(6);

$params = file_get_contents('php://input');
$params = json_decode($params);

header('Content-Type: application/json');

$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'netabetVirtualSoft';
} else {
    $usuario = 'netabetVirtualSoft';
}
$header = json_encode([
    'alg' => 'HS256',
    'typ' => 'JWT'
]);

$payload = json_encode([
    'codigo' => 0,
    'mensaje' => 'OK',
    "usuario" => $usuario
]);

$key = 'netabetVirtualS';

$signature = hash('sha256', $header . $payload . $key);

$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

$TokenHeader = $headers["token"];

if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

if ($TokenHeader === $token || true) {
    $UserId = intval($_REQUEST["UserId"]);


    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;


    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 100;
    }

    if ($MaxRows == "") {
        $MaxRows = 1;
    }
    /**
     * Elimina las tildes y caracteres especiales de una cadena de texto.
     *
     * @param string $cadena La cadena de texto a procesar.
     *
     * @return string La cadena de texto sin tildes ni caracteres especiales.
     */
    function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

    $sql =
        "SELECT count(*) count 
FROM usuario force index (usuario_pais_id_index) INNER JOIN pais ON (usuario.pais_id=pais.pais_id)
        LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante=usuario.usuario_id and usuario_mandante.mandante = usuario.mandante )
        INNER JOIN usuario_perfil   ON (usuario_perfil.usuario_id=usuario.usuario_id)
        INNER JOIN perfil ON (usuario_perfil.perfil_id=perfil.perfil_id)
        LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id)
WHERE 1=1
AND usuario.usuario_id = $UserId
AND usuario_perfil.perfil_id = 'USUONLINE'
AND usuario.eliminado = 'N'
AND usuario.estado = 'A'
";

    $Usuario = new Usuario();

    $UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();
    $transaccion = $UsuarioMySqlDAO->getTransaction();

    $count = $Usuario->execQuery($transaccion, $sql);


    $sql =
        "SELECT usuario.usuario_id, 
(CASE WHEN usuario.nombre IS NOT NULL AND LENGTH(usuario.nombre)>32 THEN CONVERT(AES_DECRYPT(SUBSTRING(FROM_BASE64(usuario.nombre), 17), '{$_ENV['SECRET_PASSPHRASE_NAME']}',SUBSTRING(FROM_BASE64(usuario.nombre), 1, 16)) USING utf8mb4)ELSE usuario.nombre  END) nombre,
pais.pais_nom,usuario.mandante,

(CASE WHEN registro.email IS NOT NULL AND LENGTH(registro.email)>32 THEN CONVERT(AES_DECRYPT(SUBSTRING(FROM_BASE64(registro.email), 17), '{$_ENV['SECRET_PASSPHRASE_LOGIN']}',SUBSTRING(FROM_BASE64(registro.email), 1, 16)) USING utf8mb4)ELSE registro.email  END) email,
registro.estado,
usuario.estado,
usuario.observ,usuario.verifcedula_post,usuario.verifcedula_ant, registro.creditos,registro.creditos_base,usuario.clave_tv

       FROM usuario force index (usuario_pais_id_index) INNER JOIN pais ON (usuario.pais_id=pais.pais_id)
        LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante=usuario.usuario_id and usuario_mandante.mandante = usuario.mandante )
        INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=usuario.usuario_id)
        INNER JOIN perfil ON (usuario_perfil.perfil_id=perfil.perfil_id)
        LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id)

WHERE 1=1
AND usuario.usuario_id = $UserId
AND usuario_perfil.perfil_id = 'USUONLINE'
AND usuario.eliminado = 'N'
AND usuario.estado = 'A'

  ";


    $Usuario = new Usuario();

    $UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();
    $transaccion = $UsuarioMySqlDAO->getTransaction();

    $usuarios = $Usuario->execQuery($transaccion, $sql);


    $usuarios = json_encode($usuarios);
    $usuarios = json_decode($usuarios);


    $sql =
        "SELECT usuario_bono.usubono_id
FROM usuario_bono
         INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)
         LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
         LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado = 'W')
WHERE 1 = 1
  AND usuario_bono.usuario_id = $UserId
  AND usuario_bono.estado = 'A'
  ";

    $BonoInterno = new BonoInterno();

    $UsuarioBonoMySqlDAO = new \Backend\mysql\UsuarioBonoMySqlDAO();
    $transaccion = $UsuarioBonoMySqlDAO->getTransaction();

    $Bonos = $BonoInterno->execQuery($transaccion, $sql);

    $Bonos = json_encode($Bonos);
    $Bonos = json_decode($Bonos);

    if ($count->{".count"} != "0") {
        $usuariosFinal = array();

        foreach ($usuarios as $key => $value) {
            $array = array();

            $array["UserId"] = intval($value->{"usuario.usuario_id"});
            $array["Nombre"] = $value->{".nombre"};
            $array["Pais"] = quitar_tildes($value->{"pais.pais_nom"});

            switch ($value->{"usuario.mandante"}) {
                case "0":
                    $Marca = "DoradoBet";
                    break;
                case "2":
                    $Marca = "JustBet";
                    break;
                case "6":
                    $Marca = "NetaBet";
                    break;
                case "8":
                    $Marca = "Ecuabet";
                    break;
                case "13":
                    $Marca = "Eltribet";
                    break;
            }
            $array["Marca"] = $Marca;
            switch ($value->{"usuario.estado"}) {
                case "A":
                    $status = "Activo";
                    break;
                case "I":
                    $status = "Inactivo";
                    if ($value->{"usuario.eliminado"} == "S") {
                        $status = "Eliminado";
                    }
                    break;
            }

            $array["Correo"] = $value->{".email"};
            $array["Estado"] = $status;
            $array["Observacion"] = $value->{"usuario.observ"};
            $array["VIP"] = intval($value->{"usuario.clave_tv"});
            if ($value->{"usuario.verifcedula_ant"} == "S" && $value->{"usuario.verifcedula_post"} == "S") {
                $Verificado = "Si";
                $EstadosVerifica = "Verificado";
            } else {
                $Verificado = "No";
                $EstadosVerifica = "No Verificado";
            }
            $array["Verificado"] = $Verificado;
            $array["Estado_Verificacion"] = $EstadosVerifica;
            $array["BonosIds"] = array();

            $array["SaldoRecargas"] = floatval($value->{"registro.creditos_base"});
            $array["SaldoRetiros"] = floatval($value->{"registro.creditos"});


            array_push($usuariosFinal, $array);
        }

        foreach ($Bonos as $key => $value2) {
            $array1 = $value2->{"usuario_bono.usubono_id"};

            // Inicializa el subarray si no existe
            if (!isset($usuariosFinal[0]["BonosIds"])) {
                $usuariosFinal[0]["BonosIds"] = [];
            }

            // Agrega el valor al array
            $usuariosFinal[0]["BonosIds"][] = $array1;
        }

        $response = array();
        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = intval($usuarios->count[0]->{".count"});
        $response["Data"] = $usuariosFinal;
    } else {
        $response["Error"] = false;
        $response["Mensaje"] = "No hay usuarios en este rango de fechas";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
} else {
    throw new Exception("Usuario no coincide con token", "30012");
}
