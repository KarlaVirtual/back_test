<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
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
use Backend\dto\UsuarioBono;
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
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
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
use Backend\utils\RedisConnectionTrait;

/**
 * Command/user_code_bonus
 *
 * Validación y redención de un bono por codigo de redención
 *
 * Este recurso valida el código de bono ingresado por el usuario, asegurando que cumple con los requisitos
 * de formato y disponibilidad. Luego, realiza la redención del bono si es válido. La validación incluye
 * verificaciones en Redis y en la base de datos para evitar múltiples redenciones del mismo código.
 * Se generan logs en Slack en caso de detección de intentos repetidos.
 *
 * @param string $bonuscode : Código del bono ingresado por el usuario.
 * @param string $usuario   : Identificador del usuario en sesión.
 *
 * @return object $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error o éxito.
 *  - *data* (array): Contiene el resultado de la validación o redención del bono.
 *
 * Objeto en caso de error:
 *
 * "code" => 30008,
 * "result" => "El código de bono ingresado es incorrecto",
 * "data" => array(),
 *
 * @throws Exception "El código de bono ingresado es incorrecto" (30008) si el código no es válido.
 * @throws Exception "Bono ya redimido" (200) si el usuario ya ha usado este bono.
 * @throws Exception "Bono no existe" (200) si el código ingresado no se encuentra en la base de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* configura una variable y depura un código de bonificación. */
$_ENV["NEEDINSOLATIONLEVEL"] = '1';

$bonuscode = $json->params->bonuscode;

$ConfigurationEnvironment = new ConfigurationEnvironment();

$bonuscode = $ConfigurationEnvironment->DepurarCaracteres($bonuscode);


/* valida y limpia un código de bono, lanzando excepción si es inválido. */
if ($bonuscode == "" || $bonuscode == "0") {
    throw new Exception("El codigo de bono ingresado es incorrecto", "30008");

}
$bonuscode = preg_replace('/[^(\x20-\x7F)]*/', '', $bonuscode);

if ($_ENV['debug']) {
    print_r($bonuscode);
}

/* verifica un código de bono y crea objetos de usuario si es válido. */
if ($bonuscode == "" || $bonuscode == "0") {
    throw new Exception("El codigo de bono ingresado es incorrecto", "30008");

}
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

/* Código crea un registro asociado a un usuario y establece parámetros de redis. */
$Registro = new Registro('', $Usuario->usuarioId);


$redisParam = ['ex' => 300];

$redisPrefix = "BonusCodeUser+";


/* Conecta a Redis y recupera un valor basado en un clave generada. */
$redis = RedisConnectionTrait::getRedisInstance(true);

if ($redis != null) {

    $cachedKey = $redisPrefix . 'UID+' . $UsuarioMandante->getUsuarioMandante();
    $cachedValue = ($redis->get($cachedKey));
}


/* Verifica y ejecuta un script si un valor no está vacío, manejando excepciones. */
if (!empty($cachedValue)) {
    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'USERCODEBONUS2 " . $cachedKey . "' '#dev' > /dev/null & ");
    throw new Exception("El codigo de bono ingresado es incorrecto", "30008");
}

if ($redis != null) {

    $redis->set($redisPrefix . 'UID+' . $UsuarioMandante->getUsuarioMandante(), '1', $redisParam);
}


/* Código para almacenar y recuperar información de un usuario en Redis con un prefijo. */
$redisParam = ['ex' => 300];

$redisPrefix = "BonusCode+" . $bonuscode;

$redis = RedisConnectionTrait::getRedisInstance(true);

if ($redis != null) {

    $cachedKey = $redisPrefix . 'UID+' . $UsuarioMandante->getUsuarioMandante();
    $cachedValue = ($redis->get($cachedKey));
}


/* verifica un valor en caché y ejecuta un script si está vacío. */
if (!empty($cachedValue)) {
    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'LEALTADPROBLEM " . $cachedKey . "' '#dev' > /dev/null & ");
    throw new Exception("El codigo de bono ingresado es incorrecto", "30008");
}

if ($redis != null) {

    $redis->set($redisPrefix . 'UID+' . $UsuarioMandante->getUsuarioMandante(), '1', $redisParam);
}


/* Carga una ciudad desde la base de datos y ajusta el número de filas a ignorar. */
$CiudadMySqlDAO = new CiudadMySqlDAO();
$Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);

$codeUsuarioBono = '';
if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100;
}


/* Se crea un filtro JSON para consultar datos sobre bonos según condiciones específicas. */
$mensajesEnviados = [];
$mensajesRecibidos = [];


$json2 = '{"rules" : [{"field" : "usuario_bono.codigo", "data": "' . $bonuscode . '","op":"eq"},{"field" : "usuario_bono.estado", "data": "L","op":"eq"},{"field" : "bono_interno.estado", "data": "A","op":"eq"},{"field" : "bono_detalle.valor", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';

if ($_ENV['debug']) {
    print_r($json2);
}

/* obtiene datos de bonos de usuario, los decodifica y accede a un valor específico. */
$UsuarioBono = new UsuarioBono();
$UsuarioBonos = $UsuarioBono->getUsuarioBonosCustom(" usuario_bono.*,bono_interno.* ", "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json2, true, '', 'ALLOWCODEINDIVIDUAL');


$UsuarioBonos = json_decode($UsuarioBonos);

if (is_object($UsuarioBonos)) {
    $codeUsuarioBono = $UsuarioBonos->data[0]->{'usuario_bono.bono_id'};

}
if ($codeUsuarioBono == "") {

    /* inicializa variables si están vacías, estableciendo valores predeterminados. */
    $codeUsuarioBono2 = "";
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un valor predeterminado para $MaxRows y inicializa arreglos vacíos. */
    if ($MaxRows == "") {
        $MaxRows = 1;
    }

    $mensajesEnviados = [];
    $mensajesRecibidos = [];


    /* lanza excepciones si el código de bono está vacío o es 'fortuna1'. */
    if ($bonuscode == '') {
        throw new Exception("El codigo de bono ingresado es incorrecto", "30008");
    }
    if ($bonuscode == 'fortuna1') {

        throw new Exception("El codigo de bono ingresado es incorrecto", "30008");
    }

    /* Genera un JSON para filtrar bonos internos según condiciones específicas. */
    $json2 = '{"rules" : [{"field" : "bono_interno.codigo", "data": "' . $bonuscode . '","op":"eq"},{"field" : "bono_interno.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

    if ($_ENV['debug']) {
        print_r($UsuarioBonos);
        print_r($json2);
    }

    /* Se obtiene información de bonos de usuario y se procesa en formato JSON. */
    $UsuarioBono = new UsuarioBono();
    $UsuarioBonos = $UsuarioBono->getUsuarioBonosCustom(" usuario_bono.*,bono_interno.* ", "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json2, true, '');

    $UsuarioBonos = json_decode($UsuarioBonos);

    if (is_array($UsuarioBonos->data) && oldCount($UsuarioBonos->data) > 0) {
        $codeUsuarioBono = $UsuarioBonos->data[0]->{'bono_interno.bono_id'};
        $bonuscode = "";
    }

}

/* crea un array asociativo con detalles de depósitos y usuario. */
$detalles = array(
    "Depositos" => 0,
    "DepositoEfectivo" => false,
    "MetodoPago" => 0,
    "ValorDeposito" => 0,
    "PaisPV" => 0,
    "DepartamentoPV" => 0,
    "CiudadPV" => 0,
    "PuntoVenta" => 0,
    "PaisUSER" => $Usuario->paisId,
    "DepartamentoUSER" => $Ciudad->deptoId,
    "CiudadUSER" => $Registro->ciudadId,
    "MonedaUSER" => $Usuario->moneda,
    "CodePromo" => $bonuscode

);


/* verifica si está en modo debug y luego inicializa objetos. */
if ($_ENV['debug']) {
    print_r($detalles);
}

$BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

$BonoInterno = new BonoInterno();

/* Decodifica detalles en JSON y agrega un bono si el código de usuario está vacío. */
$detalles = json_decode(json_encode($detalles));

$Transaction = $BonoInternoMySqlDAO->getTransaction();

if ($codeUsuarioBono == "") {
    $responseBonus = $BonoInterno->agregarBono("", $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);

    if ($_ENV['debug']) {
        print_r($responseBonus);
    }
} else {
    /* agrega un bono free si no está en modo debug. */


    if ($_ENV['debug']) {
        print_r(PHP_EOL);

        print_r(' TEST ');
        print_r($UsuarioBonos);
        print_r($codeUsuarioBono);
    }
    $responseBonus = $BonoInterno->agregarBonoFree($codeUsuarioBono, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', $bonuscode, $Transaction);


}


/* verifica si existe un bono y hace un commit en caso afirmativo. */
$existeBono = false;
if ($_ENV['debug']) {
    print_r($responseBonus);
}
if ($responseBonus->WinBonus) {
    $existeBono = true;
    $Transaction->commit();
}


if ($existeBono) {

    /* Crea un array de respuesta con código, identificador y resultado exitoso. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => true
    );

    if ($responseBonus->SumoSaldo) {


        /* Se crea un token de usuario y se envía un mensaje Websocket para actualizar saldo. */
        $UsuarioToken2 = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());


        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
        $data = $UsuarioMandante->getWSProfileSite($UsuarioToken2->getRequestId());

        $dataBonus = array();
        /* Crea un array de notificaciones con un bono, título y contenido dinámico. */
        $dataBonus["7040" . $UsuarioToken2->getRequestId() . "5"] =
            array(
                "notifications" => array(
                    array(
                        "type" => "bono",
                        "title" => "BONUS",
                        "content" => $responseBonus->SumoSaldoValor
                    )
                ),
            );


        /* copia elementos de $data a $dataBonus, luego asigna $dataBonus a $data. */
        foreach ($data as $key => $datum) {
            $dataBonus[$key] = $datum;

        }
        //$keyFirst = array_key_first($data);
        //$dataBonus[$keyFirst] = $data[$keyFirst];

        $data = $dataBonus;

        /*$WebsocketUsuario = new WebsocketUsuario($UsuarioToken2->getRequestId(), $data);
        $WebsocketUsuario->sendWSMessage();*/


    }
} else {
    /* maneja respuestas para bonos redimidos o inexistentes en una aplicación. */

    if ($bonoRedimido) {
        $response = array();
        $response["code"] = 200;
        $response["rid"] = $json->rid;
        $response["data"] = array(
            "reason" => "Bono ya redimido."
        );
    } else {
        throw new Exception("El codigo de bono ingresado es incorrecto", "30008");
        $response = array();
        $response["code"] = 200;
        $response["rid"] = $json->rid;
        $response["data"] = array(
            "reason" => "Bono no existe."
        );
    }

}
//$response["response"] = $responseBonus;
