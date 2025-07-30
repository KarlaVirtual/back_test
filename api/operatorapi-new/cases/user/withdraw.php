<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioMandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Concesionario;
use Backend\dto\Clasificador;
use Backend\dto\CuentaCobro;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\FlujoCaja;
use Backend\dto\PuntoVenta;
use Backend\dto\TransaccionApiUsuario;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioTokenInterno;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\TransaccionApiUsuarioMySqlDAO;
use Backend\mysql\TransapiusuarioLogMySqlDAO;

//
//error_reporting(E_ALL);
//ini_set("display_errors", "ON");

/**
 * Este script gestiona el proceso de retiro de usuarios en el sistema.
 * Valida parámetros de entrada, verifica condiciones de negocio y realiza transacciones
 * en la base de datos para completar el retiro solicitado.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $params->shop Identificador de la tienda.
 * @param string $params->token Token de autenticación del usuario.
 * @param string $params->withdrawId Identificador de la nota de retiro.
 * @param string $params->password Contraseña del usuario.
 * @param string $params->country Código del país.
 * @param string $params->transactionId Identificador único de la transacción.
 * 
 *
 * @return array $response Respuesta del sistema con las siguientes claves:
 *  - int $error Código de error (0 si no hay errores).
 *  - int $code Código de estado (0 si no hay errores).
 *  - int $transactionId Identificador de la transacción registrada (si aplica).
 *
 * @throws Exception Si alguno de los siguientes errores ocurre:
 *  - "50001" Si algún campo obligatorio está vacío.
 *  - "300006" Si no se pueden realizar retiros actualmente.
 *  - "300029" Si el valor del retiro excede el límite diario permitido.
 *  - "300027" Si los retiros globales están desactivados.
 *  - "300031" Si la red del usuario no está habilitada para retiros.
 *  - "50005" Si el usuario no pertenece al país.
 *  - "10018" Si el código de país es incorrecto.
 *  - "50006" Si el usuario no pertenece al partner.
 *  - "106" Si la nota de retiro no está activa.
 *  - "100031" Si el monto del retiro excede el máximo permitido.
 *  - "100000" Si ocurre un error general.
 *  - "50003" Si los datos de inicio de sesión son incorrectos.
 */

/* Código para establecer parámetros y crear un registro de log en formato específico. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;


$log = "\r\n" . "-------------------------" . "\r\n";

/* concatena información de la solicitud HTTP y la guarda en un archivo log. */
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


/* asigna valores de parámetros a variables para procesar una transacción. */
$shop = $params->shop;
$token = $params->token;
$nota = $params->withdrawId;
$clave = $params->password;
$pais = $params->country;

$transactionId = $params->transactionId;


/* lanza una excepción si el token está vacío, indicando un error. */
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}


/* valida entradas vacías y lanza excepciones si son encontradas. */
if ($nota == "") {
    throw new Exception("Field: Nota", "50001");

}

if ($clave == "") {
    throw new Exception("Field: Clave", "50001");

}

/* Verifica campos vacíos y lanza excepciones si están incompletos. */
if ($pais == "") {
    throw new Exception("Field: Pais", "50001");

}
if ($transactionId == "") {
    throw new Exception("Field: transactionId", "50001");

}


//set_time_limit(30); // 5 segundos de límite de ejecución
//
//sleep(33);


/* Se instancia un usuario y se valida si puede realizar retiros. */
$Usuario = new Usuario($shop);
$PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

try {
    $Clasificador = new Clasificador("", "WITHDRAWALBETSHOP");

    $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor == "A") {
        throw new Exception("No se puede realizar retiros actualmente", "300006");
    }

} catch (Exception $e) {
    /* Maneja excepciones, re-lanzando solo si el código no es 34 o 41. */

    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}


/* Se definen variables para controlar el número de filas y reglas en el código. */
$MaxRows = 1;
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


/* Se define un filtro con reglas para validar condiciones en una consulta. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* convierte un arreglo a JSON y establece la configuración regional a checa. */
$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_token_interno.* ";


/* Creación y obtención de datos de usuario con manejo de tokens internos en JSON. */
$UsuarioTokenInterno = new UsuarioTokenInterno();
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);
$data = json_decode($data);


$response["error"] = 0;

/* Asigna el valor 0 a la clave "code" del array "$response". */
$response["code"] = 0;


if (count($data->data) > 0) {


    /* Se crean instancias de CuentaCobro y Usuario, y se obtiene el valor de CuentaCobro. */
    $CuentaCobro = new CuentaCobro($nota, "", $clave);
    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

    $valor = $CuentaCobro->getValor();


    try {


        /* Se intenta crear un clasificador y obtener el valor máximo de retiro. */
        try {
            $Clasificador = new Clasificador("", "DAILYWITHDRAWALPOINTLIMIT");
            $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
            $ValueMaxWithdrawal = $UsuarioConfiguracion->getValor();

        } catch (Exception $e) {
            /* Manejo de excepciones en PHP; captura errores sin realizar ninguna acción específica. */


        }
        if ($ValueMaxWithdrawal != '' && $ValueMaxWithdrawal != null && $ValueMaxWithdrawal != 0) {


            /* Se crea un objeto UsuarioPerfil y se obtiene su perfilId. */
            $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

            $Perfil = $UsuarioPerfil->perfilId;

            if ($Perfil == "CONCESIONARIO" || $Perfil == "CONCESIONARIO2" || $Perfil == "CONCESIONARIO3" || $Perfil == "PUNTOVENTA") {


                /* Suma los retiros de usuarios específicos en un día determinado de una base de datos. */
                $fecha_actual = date('Y-m-d');

                // Definir el rango de tiempo desde las 00:00:00 hasta las 11:59:59 del día actual
                $inicio_dia = $fecha_actual . " 00:00:00";
                $fin_dia = $fecha_actual . " 23:59:59";


                //hacemos la suma del total de retiros que hay en el dia de hoy
                $sql = "SELECT SUM(transaccion_api_usuario.valor) AS total_retiros
        FROM transaccion_api_usuario
        INNER JOIN cuenta_cobro ON cuenta_cobro.cuenta_id = transaccion_api_usuario.identificador INNER JOIN usuario_perfil ON
	   (transaccion_api_usuario.usuario_id = usuario_perfil.usuario_id)
        WHERE usuariogenera_id = $shop
        AND cuenta_cobro.estado = 'I'
        AND tipo = 1
        AND usuario_perfil.perfil_id IN('CONCESIONARIO', 'CONCESIONARIO2', 'CONCESIONARIO3', 'PUNTOVENTA')
        AND cuenta_cobro.fecha_pago BETWEEN '$inicio_dia' AND '$fin_dia'";


                /* Se inicia una transacción para ejecutar una consulta en la base de datos. */
                $BonoInterno = new BonoInterno();
                $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

                $transaccion = $BonoInternoMySqlDAO->getTransaction();
                $transaccion->getConnection()->beginTransaction();
                $ValorRetiradoDia = $BonoInterno->execQuery($transaccion, $sql);


                /* Verifica si el retiro diario excede el límite permitido y lanza una excepción. */
                $total_retiros = $ValorRetiradoDia[0]->{'.total_retiros'};

                if ($total_retiros + $valor >= $ValueMaxWithdrawal and $ValueMaxWithdrawal != "0" and $ValueMaxWithdrawal != "" and $ValueMaxWithdrawal != "null") {
                    throw new Exception("el valor del la nota de retiro sobre pasa la cantidad de retiro por dia", 300029);
                }

            }
        }

    } catch (Exception $e) {
        /* Maneja excepciones, re-lanza si el código es 300029. */

        if ($e->getCode() == 300029) {
            throw $e;
        }
    }

    //consultar boton global de retiros


    /* Código que inicializa un clasificador y obtiene configuraciones de usuario relacionadas con retiros. */
    try {
        $Clasificador = new Clasificador("", "ISACTIVATEPAYWITHDRAWALALLIES");
        $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
        $globalWithdrawals = $UsuarioConfiguracion->getValor();

    } catch (Exception $e) {
        /* Manejo de excepciones en PHP para prevenir errores en la ejecución del código. */


    }

    /* gestiona condiciones para activar retiros de segundo nivel en una tienda. */
    if ($globalWithdrawals == "I") {
        throw new Exception ("no es posible realizar retiros actualmente", 300027);
    }

    //consultar boton de segundo nivel de retiro

    try {
        $Clasificador = new Clasificador("", "ACTIVATESECONDLEVELPOINTOFSALEWITHDRAWALS");
        $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
        $IsActivateWithdral = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {
        /* Bloque para manejar excepciones en PHP, sin acciones definidas actualmente. */


    }
    if ($IsActivateWithdral == "A") {
        try {


            /* Se crea un objeto de perfil de usuario y se obtiene su ID de perfil. */
            $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

            $Perfil = $UsuarioPerfil->perfilId;

            if ($Perfil == "CONCESIONARIO" || $Perfil == "CONCESIONARIO2" || $Perfil == "CONCESIONARIO3" || $Perfil == "PUNTOVENTA") {

                //validamos que el global de retiros este activado si no inactivamos los retiros para los concesionarios


                /* asigna un concesionario según el perfil del usuario. */
                if ($Perfil == "PUNTOVENTA") {
                    $Concesionario = new Concesionario($Usuario->usuarioId);
                    $ConcesionarioPrincipal = $Concesionario->usupadreId;
                } else if ($Perfil == "CONCESIONARIO2") {
                    $Concesionario = new Concesionario("", "", $Usuario->usuarioId);
                    $ConcesionarioPrincipal = $Concesionario->usupadre2Id;
                } else if ($Perfil == "CONCESIONARIO3") {
                    /* Crea un objeto "Concesionario" si el perfil del usuario es "CONCESIONARIO3". */

                    $Concesionario = new Concesionario("", "", "", $Usuario->usuarioId);
                } else if ($Perfil == "CONCESIONARIO") {
                    /* Asignación de usuario principal si el perfil es "CONCESIONARIO". */

                    $ConcesionarioPrincipal = $Usuario->usuarioId;
                }


                //preguntamos cuales concesionarios y redes estan habilitadas para retirar

                /* crea un clasificador y obtiene concesionarios permitidos para un usuario. */
                try {
                    $Clasificador = new Clasificador("", "CONCESIONARIOSALLOWED");
                    $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                    $ConcesionariosPermitidos = $UsuarioConfiguracion->getValor();

                    $ConcesionariosPermitidos = explode(",", $ConcesionariosPermitidos);


                } catch (Exception $e) {
                    /* Bloque de captura en PHP para manejar excepciones durante la ejecución del código. */


                }

                //preguntamos si el usuario que esta solicitando el pago esta habilitado para retirar


                /* Valida el acceso a retiros dependiendo del concesionario del usuario. */
                if ($IsActivateWithdral == "A") {
                    if (!in_array($ConcesionarioPrincipal, $ConcesionariosPermitidos)) {
                        throw new Exception("La red a la que pertenece el usuario no está habilitada para gestionar depósitos o retiros a través de " . $Usuario->nombre . ". Por favor, comuníquese con Ecuabet.", 300031);
                    }
                }

            }


        } catch (Exception $e) {
            /* Manejo de excepciones en PHP que re-lanza errores específicos según su código. */

            if ($e->getCode() == 300027) {
                throw $e;
            } elseif ($e->getCode() == 300031) {
                throw $e;
            }
        }

    }


    /* Se verifica que el usuario y el punto de venta pertenezcan al mismo país. */
    $UsuarioPuntoVenta = new Usuario($shop);
    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

    if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
        throw new Exception("Usuario no pertenece al pais", "50005");

    }


    /* Verifica condiciones de país y mandante para lanzar excepciones en validaciones. */
    if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
        throw new Exception("Código de país incorrecto", "10018");

    }
    if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
        throw new Exception("Usuario no pertenece al partner", "50006");

    }


    /* Verifica estado de CuentaCobro; lanza excepción si no está activa. Inicializa DAO. */
    if ($CuentaCobro->getEstado() != 'A') {
        throw new Exception("La nota de retiro no se puede pagar porque no esta activa", 106);
    }

    $start_time = microtime(true);


    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

    /* verifica si el monto de una transacción excede un máximo permitido. */
    $Transaction = $CuentaCobroMySqlDAO->getTransaction();
    $Amount = $CuentaCobro->getValor();


    $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

    if ($UsuarioConfig->maxpagoRetiro != "" && $UsuarioConfig->maxpagoRetiro != "0") {
        if (floatval($UsuarioConfig->maxpagoRetiro) < floatval($CuentaCobro->getValor())) {
            throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
        }
    }


    /* Se inicializa una variable y se configura un entorno de transacción en MySQL. */
    $rowsUpdate = 0;

    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
    $Transaction = $CuentaCobroMySqlDAO->getTransaction();

    /* Código que establece la dirección IP y estado a 'I' en CuentaCobro. */
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

    $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);
    $CuentaCobro->setDiripCambio($dirIp);

    $CuentaCobro->setEstado('I');

    /* Actualiza una cuenta de cobro y maneja errores si falla la actualización. */
    $CuentaCobro->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
    $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='A' ");

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }


    /* Se crea una nueva instancia de FlujoCaja y se establecen sus propiedades. */
    $rowsUpdate = 0;

    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);

    /* Establece propiedades de un objeto FlujoCaja utilizando datos de CuentaCobro. */
    $FlujoCaja->setTipomovId('S');
    $FlujoCaja->setValor($CuentaCobro->getValor());
    $FlujoCaja->setTicketId('');
    $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
    $FlujoCaja->setMandante($CuentaCobro->getMandante());
    $FlujoCaja->setTraslado('N');

    /* Establece identificadores de recarga y forma de pago en un objeto FlujoCaja. */
    $FlujoCaja->setRecargaId(0);

    if ($FlujoCaja->getFormapago1Id() == "") {
        $FlujoCaja->setFormapago1Id(0);
    }

    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }


    /* Asigna valor 0 a Forma1 y Forma2 si están vacíos. */
    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }

    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }


    /* asigna valores predeterminados si ciertos atributos están vacíos. */
    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }

    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }


    /* Verifica el valor del IVA y lo inicializa en cero si está vacío. */
    if ($FlujoCaja->getValorIva() == "") {
        $FlujoCaja->setValorIva(0);
    }
    $FlujoCaja->setDevolucion('');

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

    /* Código verifica inserción de datos y lanza excepción si falla. */
    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }


    $rowsUpdate = 0;


    /* Actualiza el balance de créditos usando el valor de una cuenta de cobro. */
    $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($CuentaCobro->getValor(), $Transaction);


    if ($rowsUpdate > 0) {


        /* Configura una transacción asignando ID de usuario, valor y tipo. */
        $TransaccionApiUsuario = new TransaccionApiUsuario();

        $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
        $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
        $TransaccionApiUsuario->setValor($valor);
        $TransaccionApiUsuario->setTipo(1);

        /* configura una transacción API con valores y respuestas específicas. */
        $TransaccionApiUsuario->setTValue(json_encode($params));
        $TransaccionApiUsuario->setRespuestaCodigo("OK");
        $TransaccionApiUsuario->setRespuesta("OK");
        $TransaccionApiUsuario->setTransaccionId($transactionId);

        $TransaccionApiUsuario->setUsucreaId(0);

        /* inserta una transacción de usuario con identificador de cuenta de cobro. */
        $TransaccionApiUsuario->setUsumodifId(0);

        $TransaccionApiUsuario->setIdentificador($CuentaCobro->getCuentaId());
        $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
        $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

        $TransapiusuarioLog = new TransapiusuarioLog();


        /* registra una transacción asociada a un usuario en un log. */
        $TransapiusuarioLog->setIdentificador($CuentaCobro->getCuentaId());
        $TransapiusuarioLog->setTransaccionId($transactionId);
        $TransapiusuarioLog->setTValue(json_encode($params));
        $TransapiusuarioLog->setTipo(1);
        $TransapiusuarioLog->setValor($Amount);
        $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

        /* configura un registro de usuario en una base de datos transacional. */
        $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


        $TransapiusuarioLog->setUsucreaId(0);
        $TransapiusuarioLog->setUsumodifId(0);


        $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

        /* Inserta un registro en la base de datos y calcula el tiempo de ejecución. */
        $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


        /*$UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(40);
        $UsuarioHistorial->setValor($CuentaCobro->getValor());
        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');*/


        $end_time = microtime(true);

        /* Calcula la duración en horas, minutos y segundos, y lanza excepción si segundos > 60. */
        $duration = $end_time - $start_time;
        $hours = (int)($duration / 60 / 60);
        $minutes = (int)($duration / 60) - $hours * 60;

        $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;

        if ($seconds > 60) {
            throw new Exception("Error General", "100000");
        }


        /* Confirma y guarda todos los cambios realizados en la transacción actual. */
        $Transaction->commit();
    } else {
        /* Lanza una excepción de error general con un mensaje y código específico. */

        throw new Exception("Error General", "100000");
    }


    /* Asigna el ID de transacción a la respuesta de la API. */
    $response["transactionId"] = $Transapiusuariolog_id;


} else {
    /* Lanza una excepción por datos de inicio de sesión incorrectos con un código específico. */

    throw new Exception("Datos de login incorrectos", "50003");

}
