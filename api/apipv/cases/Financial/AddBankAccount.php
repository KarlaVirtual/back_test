<?php

use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioBancoMySqlDAO;

/**
 *
 * @param int $UserId :       Descripción: Identificador único del usuario al que se le agregará la cuenta bancaria.
 * @param string $Account :   Descripción: Número de cuenta bancaria que se agregará al usuario.
 * @param int $TypeAccount :  Descripción: Tipo de cuenta bancaria. Este parámetro debe ser 1 para cuenta corriente o 0 para cuenta de ahorros.
 * @param int $Bank :         Descripción: Identificador único del banco al que se le asociará la cuenta bancaria.
 * @param int $InterbankCode : Descripción: Código interbancario de la cuenta bancaria.
 *
 * @Descripción:  permite agregar una cuenta bancaria a un usuario en el sistema.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 * Response es un array que contiene los siguientes atributos:
 * HasError: booleano que indica si hubo un error en la operación.
 * AlertType: string que indica el tipo de alerta que se mostrará en la vista.
 * AlertMessage: string que contiene el mensaje que se mostrará en la vista.
 * ModelErrors: array que contiene los errores de validación del modelo.
 *
 */


/* recibe y procesa un JSON con datos de usuario y cuenta. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$UserId = $params->UserId;
$account = $params->Account;
$account_type = ($params->TypeAccount == 1) ? 1 : 0;

/* Asignación de valores y creación de objetos basados en parámetros de sesión. */
$bank = $params->Bank;
$client_type = 0;
$cod_interbank = $params->InterbankCode;
$conf_account = $params->conf_account;


//$UsuarioMandante = new UsuarioMandante("", $UserId, ($_SESSION["mandante"]));
//$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

$UsuarioMandante = new UsuarioMandante("", $_SESSION["usuario"], $_SESSION["mandante"]);

/* Se crea un objeto Usuario a partir de UsuarioMandante y se inicializa restricciones. */
$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

//$UsuarioMandante = new UsuarioMandante("", $UserId, 0);
//$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

$tieneRestricciones = 0;

/* verifica restricciones de un usuario mediante un clasificador y mandante detalle. */
$cumpleRestricciones = false;

try {
    $Clasificador = new Clasificador("", "MAXACCOUNTSBANK");

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    $tieneRestricciones = $MandanteDetalle->getValor();


} catch (Exception $e) {
    /* Manejo de excepciones que asigna valor según el código de error. */


    if ($e->getCode() == 34) {
        $tieneRestricciones = 0;
    } elseif ($e->getCode() == 41) {
        $tieneRestricciones = 0;
    } else {
        throw $e;
    }
}

/* Variable que indica si hay restricciones aplicadas, siendo 1 verdadero. */
$tieneRestricciones = 1;

if ($tieneRestricciones > 0) {


    /* Código crea un filtro con reglas de comparación para usuarios y estados específicos. */
    $rules = [];
    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y consulta datos de usuarios en la base de datos. */
    $json2 = json_encode($filtro);

    $UsuarioBanco = new UsuarioBanco();

    $configuraciones = $UsuarioBanco->getUsuarioBancosCustom(" usuario_banco.* ", "usuario_banco.usubanco_id", "asc", 0, 100, $json2, true);

    $configuraciones = json_decode($configuraciones);


    /* Verifica si el conteo supera restricciones; asigna true si no las cumple. */
    if (intval($configuraciones->count[0]->{'.count'}) >= intval($tieneRestricciones)) {

    } else {
        $cumpleRestricciones = true;
    }
} else {
    /* asigna verdadero a `$cumpleRestricciones` si no se cumplen ciertas condiciones. */

    $cumpleRestricciones = true;
}

if ($cumpleRestricciones) {


    /* Se crea un objeto UsuarioBanco y se configuran sus propiedades con valores específicos. */
    $UsuarioBanco = new UsuarioBanco();
    $UsuarioBanco->setUsuarioId($UsuarioMandante->getUsuarioMandante());
    $UsuarioBanco->setBancoId($bank);
    $UsuarioBanco->setCuenta($account);
    $UsuarioBanco->setTipoCuenta($account_type);
    $UsuarioBanco->setTipoCliente($client_type);

    /* Asigna valores a un objeto 'UsuarioBanco' según condiciones específicas. */
    $UsuarioBanco->setUsucreaId($UsuarioMandante->getUsuarioMandante());
    $UsuarioBanco->setUsumodifId($UsuarioMandante->getUsuarioMandante());
    $UsuarioBanco->setEstado('A');

    if ($cod_interbank != "" && $cod_interbank != null) {

        $UsuarioBanco->setCodigo($cod_interbank);

    } else {
        /* establece un valor de código en 0 si no se cumple cierta condición. */


        $UsuarioBanco->setCodigo(0);

    }


    /* Se inserta un usuario en MySQL y se confirma la transacción. */
    $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

    $UsuarioBancoMySqlDAO->insert($UsuarioBanco);

    $UsuarioBancoMySqlDAO->getTransaction()->commit();

    $respuestafinal = "";


    /* crea una respuesta estructurada para una operación exitosa. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = $respuestafinal;
    $response["ModelErrors"] = [];
    $response["data"] = [];
} else {
    /* maneja una respuesta de error con alertas y datos vacíos. */

    $respuestafinal = "";

    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = $respuestafinal;
    $response["ModelErrors"] = [];
    $response["data"] = [];
}


?>

