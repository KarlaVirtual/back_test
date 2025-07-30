<?php


use Backend\dto\FlujoCaja;
use Backend\dto\PuntoVenta;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\TransaccionApiUsuario;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTokenInterno;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionApiUsuarioMySqlDAO;
use Backend\mysql\TransapiusuarioLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;

/**
 * Este script revierte un depósito previamente realizado por un usuario.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->shop Identificador del comercio.
 * @param string $params->token Token de autenticación.
 * @param float $params->amount Monto del depósito.
 * @param string $params->transactionId Identificador de la transacción.
 * 
 *
 * @return array $response Arreglo que contiene:
 *  - error (int): Código de error (0 si no hay errores).
 *  - code (int|string): Código adicional (0 si no hay errores).
 *  - transactionId (string): Identificador de la transacción.
 *  - depositId (string): Identificador del depósito revertido.
 */


/* inicializa variables y registra la URI de la solicitud en un log. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];

/* registra información y guarda en un archivo de log diario. */
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$shop = $params->shop;

/* verifica si el token está vacío y lanza una excepción si es así. */
$token = $params->token;
$amount = $params->amount;
$transactionId = $params->transactionId;

if ($token == "") {
    throw new Exception("Field: Key", "50001");

}

/* lanza excepciones si los campos "token" o "amount" están vacíos. */
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}

if ($amount == "") {
    throw new Exception("Field: Valor", "50001");

}

/* Valida que el campo transactionId no esté vacío antes de continuar. */
if ($transactionId == "") {
    throw new Exception("Field: transactionId", "50001");

}


$MaxRows = 1;

/* Se inicializan variables para ordenar y omitir filas en un conjunto de datos. */
$OrderedItem = 1;
$SkeepRows = 0;


$rules = [];

/* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
 array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
 array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
 array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

 $filtro = array("rules" => $rules, "groupOp" => "AND");

 $json = json_encode($filtro);

 setlocale(LC_ALL, 'czech');


 $select = " usuario_log.* ";


 $UsuarioLog = new UsuarioLog();
 $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

 $data = json_decode($data);*/


/* Se agregan reglas de filtrado a un array para consultas de usuario. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* Código PHP que convierte un filtro a JSON y define una consulta SQL. */
$json = json_encode($filtro);

//setlocale(LC_ALL, 'czech');


$select = " usuario.mandante,usuario_token_interno.* ";


/* Se solicita y decodifica un token de usuario interno, estableciendo un estado de error. */
$UsuarioTokenInterno = new UsuarioTokenInterno();
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);


$response["error"] = 0;

/* asigna el valor 0 a la clave "code" de la variable $response. */
$response["code"] = 0;



if (count($data->data) > 0) {
    //print_r(time());


    /* Se crean objetos para manejar usuarios y transacciones en un punto de venta. */
    $UsuarioPuntoVenta = new Usuario($shop);

    $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "0");

    /**
     * Actualizamos consecutivo Recarga
     */

    $UsuarioRecarga = new UsuarioRecarga($TransaccionApiUsuario->getIdentificador());


    /* verifica el estado del usuario antes de permitir eliminar una recarga. */
    if ($UsuarioRecarga->getEstado() != "A") {
        throw new Exception("La recarga no se puede eliminar", "50001");
    }
    $UsuarioRecarga->setEstado('I');


    $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

    /* Código para obtener y actualizar información de transacciones de usuario en base de datos. */
    $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();

    $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);

    $puntoventa_id = $UsuarioRecarga->getPuntoventaId();
    $valor = $UsuarioRecarga->getValor();


    /* Se crea un objeto Usuario y un objeto FlujoCaja con datos iniciales. */
    $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($puntoventa_id);

    /* Se configuran atributos de un objeto FlujoCaja y se valida un campo. */
    $FlujoCaja->setTipomovId('S');
    $FlujoCaja->setValor($valor);
    $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
    $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

    if ($FlujoCaja->getFormapago1Id() == "") {
        $FlujoCaja->setFormapago1Id(0);
    }


    /* Se verifica y asigna 0 a ciertos atributos si están vacíos en FlujoCaja. */
    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }

    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }


    /* establece valores predeterminados de 0 si están vacíos. */
    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }

    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }


    /* Asigna cero a atributos de $FlujoCaja si están vacíos. */
    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }

    if ($FlujoCaja->getValorIva() == "") {
        $FlujoCaja->setValorIva(0);
    }


    /* Se inserta un flujo de caja y se establece el balance de créditos de un punto de venta. */
    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
    $FlujoCajaMySqlDAO->insert($FlujoCaja);
    //print_r(time());

    $PuntoVenta = new PuntoVenta("", $puntoventa_id);

    $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);


    /* Se actualiza un registro de venta en MySQL y se crea un ajuste de saldo. */
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

    $PuntoVentaMySqlDAO->update($PuntoVenta);

    //print_r(time());

    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

    /* ajusta el saldo de un usuario en línea mediante diferentes parámetros. */
    $SaldoUsuonlineAjuste->setTipoId('S');
    $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
    $SaldoUsuonlineAjuste->setValor($valor);
    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
    $SaldoUsuonlineAjuste->setUsucreaId($UsuarioPuntoVenta->usuarioId);
    if ($Usuario->getBalance() == "") {
        $SaldoUsuonlineAjuste->setSaldoAnt(0);
    } else {
        /* establece el saldo anterior del usuario si no se cumple una condición. */

        $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
    }

    /* ajusta el saldo de un usuario y registra su IP. */
    $SaldoUsuonlineAjuste->setObserv("Reversion recarga API " . $UsuarioRecarga->getRecargaId());
    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
        $SaldoUsuonlineAjuste->setMotivoId(0);
    }
    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

    $SaldoUsuonlineAjuste->setDirIp($dir_ip);

    /* Se establece un mandante y se inserta un ajuste en la base de datos MySQL. */
    $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());


    $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

    $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);

    //print_r(time());


    /* debita un monto y registra un historial para el usuario correspondiente. */
    $Usuario->debit($UsuarioRecarga->getValor(), $Transaction);


    $UsuarioHistorial = new UsuarioHistorial();
    $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
    $UsuarioHistorial->setDescripcion('');

    /* Se configura el historial de usuario con diversos parámetros y un ID de recarga. */
    $UsuarioHistorial->setMovimiento('S');
    $UsuarioHistorial->setUsucreaId(0);
    $UsuarioHistorial->setUsumodifId(0);
    $UsuarioHistorial->setTipo(10);
    $UsuarioHistorial->setValor($valor);
    $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());


    /* Se crea un DAO para insertar un historial de usuario en MySQL. */
    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

    //print_r(time());

    $UsuarioHistorial = new UsuarioHistorial();

    /* establece propiedades de un objeto UsuarioHistorial para registrar un movimiento. */
    $UsuarioHistorial->setUsuarioId($puntoventa_id);
    $UsuarioHistorial->setDescripcion('');
    $UsuarioHistorial->setMovimiento('E');
    $UsuarioHistorial->setUsucreaId(0);
    $UsuarioHistorial->setUsumodifId(0);
    $UsuarioHistorial->setTipo(10);

    /* inicializa un historial de usuario y prepara una transacción API. */
    $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
    $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

    //$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
    //$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

    //print_r(time());

    $TransaccionApiUsuario = new TransaccionApiUsuario();


    /* Se configuran los parámetros de una transacción API para un usuario específico. */
    $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
    $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
    $TransaccionApiUsuario->setValor(($amount));
    $TransaccionApiUsuario->setTipo(2);
    $TransaccionApiUsuario->setTValue(json_encode($params));
    $TransaccionApiUsuario->setRespuestaCodigo("OK");

    /* configura una transacción de usuario con estado y datos específicos. */
    $TransaccionApiUsuario->setRespuesta("OK");
    $TransaccionApiUsuario->setTransaccionId($transactionId);

    $TransaccionApiUsuario->setUsucreaId(0);
    $TransaccionApiUsuario->setUsumodifId(0);


    $TransaccionApiUsuario->setIdentificador($UsuarioRecarga->getRecargaId());

    /* Crea una instancia de DAO para insertar transacciones en una base de datos. */
    $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
    $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

    //print_r(time());

    $TransapiusuarioLog = new TransapiusuarioLog();


    /* configura un registro de transacción con datos específicos del usuario y operación. */
    $TransapiusuarioLog->setIdentificador($UsuarioRecarga->getRecargaId());
    $TransapiusuarioLog->setTransaccionId($transactionId);
    $TransapiusuarioLog->setTValue(json_encode($params));
    $TransapiusuarioLog->setTipo(2);
    $TransapiusuarioLog->setValor($amount);
    $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

    /* Configuración de un registro de usuario en la base de datos con IDs específicos. */
    $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


    $TransapiusuarioLog->setUsucreaId(0);
    $TransapiusuarioLog->setUsumodifId(0);


    $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

    /* Inserta un registro en la base de datos y confirma la transacción. */
    $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

    //print_r(time());

    $Transaction->commit();


    $response["transactionId"] = $Transapiusuariolog_id;

    /* Asignación del ID de recarga del usuario a la respuesta en formato de array. */
    $response["depositId"] = $UsuarioRecarga->getRecargaId();


} else {
    /* lanza una excepción si los datos de inicio de sesión son incorrectos. */

    throw new Exception("Datos de login incorrectos", "50003");

}


