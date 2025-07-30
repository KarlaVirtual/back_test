<?php


use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\FlujoCaja;
use Backend\dto\PuntoVenta;
use Backend\dto\TransaccionApiUsuario;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioTokenInterno;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\TransaccionApiUsuarioMySqlDAO;
use Backend\mysql\TransapiusuarioLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;


/**
 * Este script revierte un retiro previamente realizado por un usuario.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->shop Identificador del comercio.
 * @param string $params->token Token de autenticación.
 * @param string $params->withdrawId Nota asociada al retiro.
 * @param string $params->password Contraseña del usuario.
 * @param string $params->country País del usuario.
 * @param string $params->transactionId Identificador de la transacción.
 * 
 *
 * @return array $response Arreglo que contiene:
 *  - error (int): Código de error (0 si no hay errores).
 *  - code (int|string): Código adicional (0 si no hay errores).
 *  - name (string): Nombre del usuario.
 *  - currency (string): Moneda del usuario.
 *  - amount (float): Monto del retiro revertido.
 *  - transactionId (string): Identificador de la transacción.
 *
 * @throws Exception Si alguno de los campos obligatorios está vacío o si ocurren errores durante el proceso.
 */

/* Inicializa variables y registra la URI de la solicitud en un log. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];

/* registra información de solicitudes HTTP en un archivo de registro. */
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$shop = $params->shop;

/* asigna valores de parámetros a variables para gestionar transacciones. */
$token = $params->token;
$nota = $params->withdrawId;
$clave = $params->password;
$pais = $params->country;

$transactionId = $params->transactionId;


/* lanza una excepción si el token está vacío. */
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}


/* verifica si las variables "nota" y "clave" están vacías y lanza excepciones. */
if ($nota == "") {
    throw new Exception("Field: Nota", "50001");

}

if ($clave == "") {
    //throw new Exception("Field: Clave", "50001");

}

/* verifica si el país está vacío y establece variables para límites y orden. */
if ($pais == "") {
    //throw new Exception("Field: Pais", "50001");
}

$MaxRows = 1;
$OrderedItem = 1;

/* Se inicializan variables para omitir filas y almacenar reglas en un array vacío. */
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


/* crea un filtro con condiciones para consultar usuarios y tokens. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* Codifica datos en JSON y establece la configuración regional en checo para consultas SQL. */
$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_token_interno.* ";


/* Se obtiene un token de usuario, se decodifica y establece una respuesta sin errores. */
$UsuarioTokenInterno = new UsuarioTokenInterno();
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);


$response["error"] = 0;

/* Se asigna el valor 0 a la clave "code" en el array $response. */
$response["code"] = 0;


if (count($data->data) > 0) {


    /* Crea objetos relacionados a un usuario y una transacción en un sistema de cobros. */
    $UsuarioPuntoVenta = new Usuario($shop);

    $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "1");

    $CuentaCobro = new CuentaCobro($TransaccionApiUsuario->getIdentificador());
    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

    //$UsuarioPuntoVenta = new Usuario($shop);

    /* Se crea un objeto PuntoVenta y se valida el país del usuario. */
    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

    if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
        throw new Exception("Usuario no pertenece al pais", "50005");

    }

    /* Verifica condiciones para lanzar excepciones según país y mandante del usuario. */
    if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
        throw new Exception("Código de país incorrecto", "10018");

    }
    if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
        throw new Exception("Usuario no pertenece al partner", "50006");

    }


    //$CuentaCobro = new CuentaCobro($nota, "", $clave);

    /* inicializa objetos de Usuario y PuntoVenta usando IDs específicos. */
    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

    $valor = $CuentaCobro->getValor();

    //$UsuarioPuntoVenta = new Usuario($shop);
    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);


    /* Valida si el usuario pertenece al país y al partner correcto, lanzando excepciones si no. */
    if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
        throw new Exception("Usuario no pertenece al pais", "50005");
    }

    if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
        throw new Exception("Usuario no pertenece al partner", "50006");
    }


    /* lanza excepciones si la nota de retiro está eliminada o no es eliminable. */
    if ($CuentaCobro->getEstado() == 'E') {
        throw new Exception("La nota de retiro ya se encuentra eliminanada", "50009");
    }

    if ($CuentaCobro->getEstado() != 'I') {
        throw new Exception("La nota de retiro no se puede eliminar", "50008");
    }


    /* Validación de pago de notas de retiro según configuración del usuario en sistema. */
    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
    $Transaction = $CuentaCobroMySqlDAO->getTransaction();
    $Amount = $CuentaCobro->getValor();

    $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

    if ($UsuarioConfig->maxpagoRetiro != "" && $UsuarioConfig->maxpagoRetiro != "0") {
        if (floatval($UsuarioConfig->maxpagoRetiro) < floatval($CuentaCobro->getValor())) {
            throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
        }
    }


    /* Se inicializan variables y se crean instancias para manejar configuraciones y transacciones. */
    $rowsUpdate = 0;

    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
    $Transaction = $CuentaCobroMySqlDAO->getTransaction();

    /* Código que configura el estado y la fecha de eliminación en un objeto de cuenta. */
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

    $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);
    //$CuentaCobro->setDiripCambio($dirIp);
    $CuentaCobro->setEstado('E');
    $CuentaCobro->setFechaEliminacion(date('Y-m-d H:i:s'));


    /* Actualiza un registro, lanzando una excepción si no se modifica ninguno. */
    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='I' ");

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }

    $rowsUpdate = 0;


    /* Se crea un objeto FlujoCaja y se configuran sus propiedades iniciales. */
    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
    $FlujoCaja->setTipomovId('E');
    $FlujoCaja->setValor($CuentaCobro->getValor());

    /* Configura un objeto FlujoCaja con datos de CuentaCobro y verifica método de pago. */
    $FlujoCaja->setTicketId('');
    $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
    $FlujoCaja->setMandante($CuentaCobro->getMandante());
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setRecargaId(0);

    if ($FlujoCaja->getFormapago1Id() == "") {
        $FlujoCaja->setFormapago1Id(0);
    }


    /* Se asignan valores predeterminados a campos vacíos en el objeto $FlujoCaja. */
    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }

    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }


    /* Verifica valores vacíos y asigna 0 a propiedades en un objeto FlujoCaja. */
    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }

    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }


    /* Establece valores predeterminados para porcentaje y valor de IVA si están vacíos. */
    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }

    if ($FlujoCaja->getValorIva() == "") {
        $FlujoCaja->setValorIva(0);
    }

    /* Se inserta un flujo de caja y maneja posibles errores en la operación. */
    $FlujoCaja->setDevolucion('');

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }


    /* Actualiza el balance de créditos usando valores de CuentaCobro y Transaction. */
    $rowsUpdate = 0;

    $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($CuentaCobro->getValor(), $Transaction);

    if ($rowsUpdate > 0) {


        /* Crea una transacción API utilizando datos del usuario y un valor específico. */
        $TransaccionApiUsuario = new TransaccionApiUsuario();

        $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
        $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
        $TransaccionApiUsuario->setValor($valor);
        $TransaccionApiUsuario->setTipo(3);

        /* establece valores para una transacción a través de una API de usuario. */
        $TransaccionApiUsuario->setTValue(json_encode($params));
        $TransaccionApiUsuario->setRespuestaCodigo("OK");
        $TransaccionApiUsuario->setRespuesta("OK");
        $TransaccionApiUsuario->setTransaccionId($transactionId);

        $TransaccionApiUsuario->setUsucreaId(0);

        /* Se establece un registro de transacción en la base de datos para el usuario. */
        $TransaccionApiUsuario->setUsumodifId(0);

        $TransaccionApiUsuario->setIdentificador($CuentaCobro->getCuentaId());
        $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
        $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

        $TransapiusuarioLog = new TransapiusuarioLog();


        /* registra transacciones logueando datos relevantes de usuario y cuenta. */
        $TransapiusuarioLog->setIdentificador($CuentaCobro->getCuentaId());
        $TransapiusuarioLog->setTransaccionId($transactionId);
        $TransapiusuarioLog->setTValue(json_encode($params));
        $TransapiusuarioLog->setTipo(3);
        $TransapiusuarioLog->setValor($Amount);
        $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

        /* Se configura un log de usuario con IDs para creación y modificación. */
        $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


        $TransapiusuarioLog->setUsucreaId(0);
        $TransapiusuarioLog->setUsumodifId(0);


        $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

        /* inserta un registro y actualiza el historial de un usuario. */
        $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


        $Usuario->creditWin($CuentaCobro->getValor(), $Transaction);

        $UsuarioHistorial = new UsuarioHistorial();

        /* Se establece el historial de usuario con datos referentes a un movimiento específico. */
        $UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(40);

        /* inserta un historial de usuario utilizando datos de una cuenta de cobro. */
        $UsuarioHistorial->setValor($CuentaCobro->getValor());
        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

        $UsuarioHistorial = new UsuarioHistorial();

        /* Se establece un historial de usuario con información específica y un tipo definido. */
        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('S');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(40);

        /* asigna valores a un historial de usuario y confirma una transacción. */
        $UsuarioHistorial->setValor($CuentaCobro->getValor());
        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

        //$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        //$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


        $Transaction->commit();
    } else {
        /* Lanza una excepción con un mensaje de error y un código específico. */

        throw new Exception("Error General", "100000");
    }


    /* Asigna datos del usuario y la transacción a un array de respuesta. */
    $response["name"] = $Usuario->nombre;
    $response["currency"] = $Usuario->moneda;
    $response["amount"] = $CuentaCobro->getValor();
    $response["transactionId"] = $TransapiusuarioLog->transapiusuariologId;


} else {
    /* Lanza una excepción por credenciales de inicio de sesión incorrectas con un código de error. */

    throw new Exception("Datos de login incorrectos", "50003");

}
