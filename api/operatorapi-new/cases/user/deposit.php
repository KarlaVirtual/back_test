<?php


use Backend\dto\AuditoriaGeneral;
use Backend\dto\BonoInterno;
use Backend\dto\FlujoCaja;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\Concesionario;
use Backend\dto\UsuarioPerfil;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\TransaccionApiUsuario;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\Usuario;
use Backend\dto\Clasificador;
use Backend\dto\UsuarioMandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioTokenInterno;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\TransaccionApiUsuarioMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\TransapiusuarioLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\sql\SqlQuery;


//sleep(120);
//set_time_limit(30);

//error_reporting(E_ALL);
//ini_set("display_errors", "ON");


/**
 * Este script maneja el proceso de depósito de usuarios en el sistema.
 * Valida los parámetros de entrada, verifica restricciones, realiza operaciones de base de datos
 * y gestiona transacciones relacionadas con depósitos.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->shop Identificador del comercio.
 * @param string $params->token Token de autenticación.
 * @param string $params->document Documento del usuario.
 * @param string $params->userid Identificador del usuario.
 * @param string $params->country Código del país.
 * @param float $params->amount Monto del depósito.
 * @param string $params->transactionId Identificador único de la transacción.
 * @param string $params->shopReference Referencia del comercio.
 * 
 *
 * @return array $response Arreglo que contiene:
 *  - error (int): Código de error (0 si no hay errores).
 *  - code (int): Código de respuesta (0 si no hay errores).
 *  - transactionId (int): Identificador de la transacción registrada.
 *  - depositId (int): Identificador del depósito registrado.
 *
 * @throws Exception Si alguno de los siguientes errores ocurre:
 *  - "Field: Key" (50001): El token está vacío.
 *  - "Field: document, userid" (50001): Ambos campos, documento y usuario, están vacíos.
 *  - "Field: Valor" (50001): El monto del depósito está vacío o es menor al mínimo permitido.
 *  - "Field: Pais" (50001): El país no está especificado.
 *  - "Field: transactionId" (50001): El ID de la transacción está vacío.
 *  - "No es posible realizar depositos" (300006): Restricción para realizar depósitos.
 *  - "No existe Usuario" (24): El usuario no tiene un perfil válido.
 *  - "Usuario no pertenece al pais" (50005): El usuario no pertenece al país del comercio.
 *  - "Código de país incorrecto" (10018): El código de país no coincide.
 *  - "Usuario no pertenece al partner" (50006): El usuario no pertenece al socio comercial.
 *  - "El valor de la recarga que intentas procesar supera el maximo permitido por dia" (300032): Se excede el límite diario de depósitos.
 *  - "La red a la que pertenece el usuario no está habilitada para gestionar depósitos o retiros" (300030): Restricción de red para depósitos.
 *  - "Transaccion ya procesada" (10001): La transacción ya fue procesada previamente.
 *  - "No tiene saldo para transferir" (111): El punto de venta no tiene saldo suficiente.
 *  - "Punto de venta no tiene cupo disponible para realizar la recarga" (100002): No hay cupo disponible en el punto de venta.
 *  - "Error General" (100000): Error general en el proceso.
 *  - "Datos de login incorrectos" (50003): Credenciales de inicio de sesión incorrectas.
 */

/* Código que inicializa variables y registra la URI de la solicitud en un log. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];

/* registra información de una solicitud HTTP en un archivo de log. */
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
$start_time = microtime(true);


/* asigna parámetros a variables para ser utilizadas en un proceso. */
$shop = $params->shop;
$token = $params->token;
$document = $params->document;
$userid = $params->userid;
$pais = $params->country;
$amount = $params->amount;

/* valida un token; lanza excepción si está vacío. */
$transactionId = $params->transactionId;
$shopReference = $params->shopReference;


if ($token == "") {
    throw new Exception("Field: Key", "50001");

}

/* lanza excepciones si faltan "token", "document" o "userid". */
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}
if ($document == "" && $userid == "") {
    throw new Exception("Field: document, userid", "50001");

}

/* verifica campos vacíos y lanza excepciones si están sin valor. */
if ($amount == "") {
    throw new Exception("Field: Valor", "50001");

}
if ($pais == "") {
    throw new Exception("Field: Pais", "50001");

}

/* valida un ID de transacción y maneja excepciones relacionadas con depósitos. */
if ($transactionId == "") {
    throw new Exception("Field: transactionId", "50001");

}


/*
 * Verifica el estado de la contingencia para depósitos en puntos de venta o redes aliadas
 *
 * Este bloque de código intenta obtener el valor de la contingencia para depósitos
 * en redes aliadas o puntos de venta. Si ocurre una excepción, se establece el valor
 * de la contingencia como inactivo ("I").
 *
 * en caso de tener una contingencia activa se deja el registro del intento fallido del deposito.
 */



try {
    $Clasificador = new Clasificador('', 'CONTINGENCYRETAIL');
    $UsuarioConfiguracion = new UsuarioConfiguracion($userid, 'A', $Clasificador->getClasificadorId());
    $Contingencia = $UsuarioConfiguracion->getEstado();

} catch (Exception $e) {
    $Contingencia = "I";
}


if($Contingencia == "A"){


    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($userid);
    $AuditoriaGeneral->setUsuarioIp("");
    $AuditoriaGeneral->setUsuariosolicitaId($shop);
    $AuditoriaGeneral->setUsuariosolicitaIp("");
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("FALLOENDEPOSITOPV");
    $AuditoriaGeneral->setValorAntes(0);
    $AuditoriaGeneral->setValorDespues(0);
    $AuditoriaGeneral->setUsucreaId($userid);
    $AuditoriaGeneral->setUsumodifId(0);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setObservacion('Intento fallido de deposito por red aliada');


    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

    throw new Exception("Esta cuenta tiene una restricción activa. El usuario debe comunicarse con soporte para más información.", "300166");
}


try {

    $Usuario = new Usuario($shop);

    $Clasificador = new Clasificador("", "DEPOSITBETSHOP");

    $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor == "A") {
        throw new Exception("No es posible realizar depositos", "300006");
    }

} catch (Exception $e) {
    /* Manejo de excepciones: re-lanza la excepción si el código no es 34 o 41. */

    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}


/* Variables inicializan el número de filas, elementos ordenados y reglas en un arreglo. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$rules = [];

/*
 array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
 array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
 array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
 array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

 $filtro = array("rules" => $rules, "groupOp" => "AND");

 $json = json_encode($filtro);

 setlocale(LC_ALL, 'czech');


 $select = " usuario_log.* ";


 $UsuarioLog = new UsuarioLog();
 $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

 $data = json_decode($data);
*/


/* Se crean reglas de filtrado para consultar datos de usuarios y tokens. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* convierte un filtro a JSON y define una consulta SQL select. */
$json = json_encode($filtro);

//setlocale(LC_ALL, 'czech');


$select = " usuario.mandante,usuario_token_interno.* ";


/* obtiene datos de "UsuarioTokenInterno" y los decodifica en JSON. */
$UsuarioTokenInterno = new UsuarioTokenInterno();
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);


$response["error"] = 0;

/* Asigna un valor de error 0 a la clave "code" en el arreglo $response. */
$response["code"] = 0;


if (count($data->data) > 0) {


    /* Valida si un comercio específico tiene un monto válido, arrojando excepciones si no. */
    if (in_array($shop, array(1784692, 853460))) {


        if (floatval($amount) < 1) {
            throw new Exception("Field: Valor", "50001");

        }
    }

    /* Verifica si un valor es menor que 1 y lanza excepción si verdadera. */
    if (in_array($shop, array(853460))) {


        if (floatval($amount) < 1) {
            throw new Exception("Field: Valor", "50001");

        }
    }


    /* Verifica si $userid no está vacío y crea un objeto Usuario. */
    if ($userid != "") {
        $Usuario = new Usuario($userid);
    } else {


        /* Crea filtros para consultar usuarios basados en condiciones específicas y devuelve resultados. */
        if ($document != "") {
            $rules = [];

            array_push($rules, array("field" => "registro.cedula", "data" => "$document", "op" => "eq"));
            array_push($rules, array("field" => "usuario.mandante", "data" => $data->data[0]->{"usuario.mandante"}, "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Usuario = new Usuario();

            $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $usuarios = json_decode($usuarios);

            $Usuario = new Usuario($usuarios->data[0]->{"usuario.usuario_id"});


        }
    }

    /* Se verifica el perfil del usuario según su mandante y lanza excepción si no es válido. */
    $UsuarioPuntoVenta = new Usuario($shop);
    $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

    if ($Usuario->mandante != 8 && $Usuario->mandante != 19) {
        if ($UsuarioPerfil->getPerfilId() != 'USUONLINE') {
            throw new Exception("No existe Usuario", "24");
        }
    } else {
        /* Verifica el perfil de usuario y lanza excepción si no es válido. */


        if ($UsuarioPerfil->getPerfilId() != 'USUONLINE' && $UsuarioPerfil->getPerfilId() != 'PUNTOVENTA' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO2' && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO3') {
            throw new Exception("No existe Usuario", "24");
        }
    }


    /* Verifica que el usuario y punto de venta pertenezcan al mismo país. */
    if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
        throw new Exception("Usuario no pertenece al pais", "50005");

    }
    if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
        throw new Exception("Código de país incorrecto", "10018");

    }

    /* Verifica si usuarios pertenecen al mismo mandante y si están activos. */
    if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
        throw new Exception("Usuario no pertenece al partner", "50006");

    }

    if ($Usuario->eliminado == 'S') {
        throw new Exception("Usuario no pertenece al partner", "50006");

    }

    //Consultamos cul es el maximo de deposito diario


    /* Se intenta obtener el máximo depósito diario para un usuario, manejando excepciones. */
    try {
        $Clasificador = new Clasificador("", "LIMITDAILYDEPOSITSPERPOINTSOFSALE");
        $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
        $MaxDepositDay = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {

    }
    if ($MaxDepositDay != 0 && $MaxDepositDay != '' && $MaxDepositDay != null) {


        /* Calcula el total de depósitos de usuarios activos en un rango horario específico. */
        $fecha_actual = date('Y-m-d');

        // Definir el rango de tiempo desde las 00:00:00 hasta las 11:59:59 del día actual
        $inicio_dia = $fecha_actual . " 00:00:00";
        $fin_dia = $fecha_actual . " 23:59:59";

        $sql = " SELECT SUM(transaccion_api_usuario.valor) AS total_depositos
        FROM transaccion_api_usuario 
        INNER JOIN usuario_recarga  ON (usuario_recarga.recarga_id = transaccion_api_usuario.identificador) INNER JOIN usuario_perfil ON
	(transaccion_api_usuario.usuario_id = usuario_perfil.usuario_id)
        WHERE usuariogenera_id = $shop 
        AND tipo = 0
        AND usuario_recarga.estado = 'A'
        AND usuario_perfil.perfil_id IN('CONCESIONARIO', 'CONCESIONARIO2', 'CONCESIONARIO3', 'PUNTOVENTA')
        AND usuario_recarga.fecha_crea BETWEEN '$inicio_dia' AND '$fin_dia'";


        /* Código para iniciar una transacción y ejecutar una query de recarga. */
        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

        $transaccion = $BonoInternoMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();
        $ValorRecargaDia = $BonoInterno->execQuery($transaccion, $sql);


        /* Verifica si el depósito diario excede el máximo permitido según el perfil del usuario. */
        $total_depositos = $ValorRecargaDia[0]->{'.total_depositos'};


        try {

            $UsuarioPerfil = new UsuarioPerfil($userid);
            $Perfil = $UsuarioPerfil->perfilId;

            if ($Perfil == "CONCESIONARIO" || $Perfil == "CONCESIONARIO2" || $Perfil == "CONCESIONARIO3" || $Perfil == "PUNTOVENTA") {


                if ($total_depositos + $amount > $MaxDepositDay && $total_depositos != "" && $total_depositos != "Null" && $MaxDepositDay != 0) {
                    throw new Exception("El valor de la recarga que intentas procesar supera el maximo permitido por dia", 300032);
                }

            }
        } catch (Exception $e) {
            /* Captura excepciones y relanza si el código es 300032. */

            if ($e->getCode() == 300032) {
                throw $e;
            }
        }

    }


    /* verifica condiciones para depósitos y lanza una excepción si no se cumplen. */
    try {


        $Clasificador = new Clasificador("", "DEPOSITBETSHOP");

        $MandanteDetalle = new MandanteDetalle("", $UsuarioPuntoVenta->mandante, $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

        if ($MandanteDetalle->valor == "A") {
            throw new Exception("No es posible realizar depositos", "300006");
        }

    } catch (Exception $e) {
        /* maneja excepciones, re lanzando solo las que no tienen códigos 34 o 41. */

        if ($e->getCode() != 34 && $e->getCode() != 41) {
            throw $e;
        }
    }


    try {

        /* Se crea un objeto de UsuarioPerfil y se obtiene su identificador de perfil. */
        $UsuarioPerfil = new UsuarioPerfil($userid);
        $Perfil = $UsuarioPerfil->perfilId;

        if ($Perfil == "CONCESIONARIO" || $Perfil == "CONCESIONARIO2" || $Perfil == "CONCESIONARIO3" || $Perfil == "PUNTOVENTA") {


            /* Verifica el perfil y asigna el Concesionario principal según el usuario. */
            if ($Perfil == "PUNTOVENTA") {
                $Concesionario = new Concesionario($userid);
                $ConcesionarioPrincipal = $Concesionario->usupadreId;
            } else if ($Perfil == "CONCESIONARIO2") {
                $Concesionario = new Concesionario('', '', $userid);
                $ConcesionarioPrincipal = $Concesionario->usupadreId;
            } else if ($Perfil == "CONCESIONARIO3") {
                /* Verifica el perfil y crea un objeto Concesionario para obtener usupadreId. */

                $Concesionario = new Concesionario("", "", "", $userid);
                $ConcesionarioPrincipal = $Concesionario->usupadreId;
            } else if ($Perfil == "CONCESIONARIO") {
                /* Asigna el ID de usuario a $ConcesionarioPrincipal si el perfil es concesionario. */

                $ConcesionarioPrincipal = $userid;
            }


            /* inicializa un clasificador y obtiene concesionarios permitidos a partir de configuraciones. */
            try {
                $Clasificador = new Clasificador("", "CONCESIONARIOSALLOWED");
                $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                $IdsAllowed = $UsuarioConfiguracion->getValor();

                $idsConcesionariosPermitidos = explode(",", $IdsAllowed);

            } catch (Exception $e) {
                /* Maneja excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */


            }

            //preguntamos si el boton de restriccion esta activo


            /* inicializa objetos y obtiene una restricción de usuario basado en configuración. */
            try {
                $Clasificador = new Clasificador("", "ALLOWSDEPOSITTOALLIEDNETWORKS");
                $UsuarioConfiguracion = new UsuarioConfiguracion($shop, "A", $Clasificador->getClasificadorId());
                $Restriccion = $UsuarioConfiguracion->getValor();


            } catch (Exception $e) {
                /* Bloque que captura excepciones en PHP, sin realizar ninguna acción dentro. */


            }


            /* Lanza una excepción si el concesionario no está permitido al gestionar depósitos o retiros. */
            if ($Restriccion == "A" && !in_array($ConcesionarioPrincipal, $idsConcesionariosPermitidos)) {
                throw new Exception("La red a la que pertenece el usuario no está habilitada para gestionar depósitos o retiros a través de " . $UsuarioPuntoVenta->nombre . ". Por favor, comuníquese con Ecuabet", 300030);
            }
        }
    } catch (Exception $e) {
        /* captura excepciones y re-lanza si el código de error es 300030. */

        if ($e->getCode() == 300030) {
            throw $e;
        }
    }


    /**
     * Actualizamos consecutivo Recarga
     */

    /*$Consecutivo = new Consecutivo("", "REC", "");

    $consecutivo_recarga = $Consecutivo->numero;

    $consecutivo_recarga++;

    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

    $Consecutivo->setNumero($consecutivo_recarga);


    $ConsecutivoMySqlDAO->update($Consecutivo);

    $ConsecutivoMySqlDAO->getTransaction()->commit();*/


    /* Se crea un objeto de usuario y se asigna su ID. */
    $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
    $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();


    $UsuarioRecarga = new UsuarioRecarga();
    //$UsuarioRecarga->setRecargaId($consecutivo_recarga);
    $UsuarioRecarga->setUsuarioId($Usuario->usuarioId);

    /* Código que establece atributos de un objeto UsuarioRecarga con fecha y otros parámetros. */
    $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
    $UsuarioRecarga->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
    $UsuarioRecarga->setValor($amount);
    $UsuarioRecarga->setPorcenRegaloRecarga(0);
    $UsuarioRecarga->setDirIp(0);
    $UsuarioRecarga->setPromocionalId(0);

    /* Se configuran atributos de un objeto UsuarioRecarga con valores específicos. */
    $UsuarioRecarga->setValorPromocional(0);
    $UsuarioRecarga->setHost(0);
    $UsuarioRecarga->setMandante($Usuario->mandante);
    $UsuarioRecarga->setPedido(0);
    $UsuarioRecarga->setPorcenIva(0);
    $UsuarioRecarga->setMediopagoId(0);

    /* Se configura un objeto, se inserta en la base de datos y se obtiene un ID. */
    $UsuarioRecarga->setValorIva(0);
    $UsuarioRecarga->setEstado('A');
    $UsuarioRecarga->setVersion(2);

    $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

    $consecutivo_recarga = $UsuarioRecarga->recargaId;


    /* Código para crear y configurar una transacción de usuario en el sistema. */
    $TransaccionApiUsuario = new TransaccionApiUsuario();

    $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
    $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
    $TransaccionApiUsuario->setValor(($amount));
    $TransaccionApiUsuario->setTipo(0);

    /* Se configura una transacción API con valores y respuestas específicas. */
    $TransaccionApiUsuario->setTValue(json_encode($params));
    $TransaccionApiUsuario->setRespuestaCodigo("OK");
    $TransaccionApiUsuario->setRespuesta("OK");
    $TransaccionApiUsuario->setTransaccionId($transactionId);

    $TransaccionApiUsuario->setUsucreaId(0);

    /* Verifica si la transacción ha sido procesada y lanza excepción si es así. */
    $TransaccionApiUsuario->setUsumodifId(0);


    if ($TransaccionApiUsuario->existsTransaccionIdAndProveedor("OK")) {

        //  Si la transaccionId ha sido procesada, reportamos el error
        throw new Exception("Transaccion ya procesada", "10001");

    }


    /* Código que registra una transacción y su log asociado en la base de datos. */
    $TransaccionApiUsuario->setIdentificador($consecutivo_recarga);
    $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
    $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

    $TransapiusuarioLog = new TransapiusuarioLog();

    $TransapiusuarioLog->setIdentificador($consecutivo_recarga);

    /* registra una transacción con detalles del usuario y parámetros específicos. */
    $TransapiusuarioLog->setTransaccionId($transactionId);
    $TransapiusuarioLog->setTValue(json_encode($params));
    $TransapiusuarioLog->setTipo(0);
    $TransapiusuarioLog->setValor($amount);
    $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
    $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


    /* Se configura un log de usuario y se inserta en la base de datos. */
    $TransapiusuarioLog->setUsucreaId(0);
    $TransapiusuarioLog->setUsumodifId(0);


    $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);
    $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

    if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {


        /* Registra un log de cupo con información del usuario y monto. */
        $CupoLog = new CupoLog();
        $CupoLog->setUsuarioId($Usuario->puntoventaId);
        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
        $CupoLog->setTipoId('E');
        $CupoLog->setValor($amount);
        $CupoLog->setUsucreaId(0);

        /* Se configuran propiedades del objeto CupoLog y se inicializa su Data Access Object. */
        $CupoLog->setMandante(0);
        $CupoLog->setTipocupoId('A');
        $CupoLog->setRecargaId($consecutivo_recarga);


        $CupoLogMySqlDAO = new CupoLogMySqlDAO($Transaction);


        /* Inserta un registro en MySQL y verifica saldo antes de realizar una transferencia. */
        $CupoLogMySqlDAO->insert($CupoLog);

        $PuntoVenta = new PuntoVenta('', $Usuario->puntoventaId);
        $cant = $PuntoVenta->setBalanceCreditosBase($CupoLog->getValor(), $Transaction);

        if ($cant == 0) {
            throw new Exception("No tiene saldo para transferir", "111");
        }


        /* Crea un nuevo historial de usuario con información del log correspondiente. */
        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);

        /* Se inserta un historial de usuario en la base de datos con datos específicos. */
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor($CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


    } else {
        /* Registro de un movimiento de crédito en el historial del usuario. */

        $Usuario->credit($amount, $Transaction);


        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(10);
        $UsuarioHistorial->setValor($amount);
        $UsuarioHistorial->setExternoId($consecutivo_recarga);

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


    }


    /* Inicializa un objeto FlujoCaja con fecha, hora y usuario creador. */
    $rowsUpdate = 0;

    $FlujoCaja = new FlujoCaja();
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);

    /* Configura un objeto FlujoCaja con información de una recarga de usuario. */
    $FlujoCaja->setTipomovId('E');
    $FlujoCaja->setValor($UsuarioRecarga->getValor());
    $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
    $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setFormapago1Id(1);

    /* configura propiedades de un objeto según condiciones específicas. */
    $FlujoCaja->setCuentaId('0');
    if ($CupoLog != null) {
        $FlujoCaja->setcupologId(0);
        $FlujoCaja->setcupologId($CupoLog->getCupologId());
    }

    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }


    /* Asigna el valor 0 si las formas de flujo son vacías. */
    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }

    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }


    /* asigna valores por defecto a propiedades vacías de un objeto. */
    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId(0);
    }

    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }


    /* Verifica si el IVA es vacío y lo establece en cero antes de insertar en la base de datos. */
    if ($FlujoCaja->getValorIva() == "") {
        $FlujoCaja->setValorIva(0);
    }

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate > 0) {

        /* Se crea una instancia de "PuntoVenta" usando un identificador de usuario. */
        $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->usuarioId);

        try {


            /* Se crean registros y se cargan ciudades desde una base de datos MySQL. */
            $Registro = new Registro('', $Usuario->usuarioId);

            $CiudadMySqlDAO = new CiudadMySqlDAO();

            $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
            $CiudadPuntoVenta = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);


            /* consulta depósitos de un usuario y crea un arreglo con detalles. */
            $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $Usuario->usuarioId . "'");

            $detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];
            $detalleDepositos = $detalleDepositos - 1;

            $detalles = array(
                "Depositos" => $detalleDepositos,
                "DepositoEfectivo" => true,
                "MetodoPago" => 0,
                "ValorDeposito" => $UsuarioRecarga->getValor(),
                "PaisPV" => $UsuarioPuntoVenta->paisId,
                "DepartamentoPV" => $CiudadPuntoVenta->deptoId,
                "CiudadPV" => $PuntoVenta->ciudadId,
                "PuntoVenta" => $UsuarioPuntoVenta->puntoventaId,
                "PaisUSER" => $Usuario->paisId,
                "DepartamentoUSER" => $Ciudad->deptoId,
                "CiudadUSER" => $Registro->ciudadId,
                "MonedaUSER" => $Usuario->moneda,

            );


            /* Se crea un objeto y se agrega un bono con detalles y transacción. */
            $BonoInterno = new BonoInterno();
            $detalles = json_decode(json_encode($detalles));

            $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);

        } catch (Exception $e) {
            /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del programa. */


        }


        /* Calcula la duración en horas, minutos y segundos a partir del tiempo transcurrido. */
        $end_time = microtime(true);
        $duration = $end_time - $start_time;
        $hours = (int)($duration / 60 / 60);
        $minutes = (int)($duration / 60) - $hours * 60;

        $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;


        /* Lanza excepciones si el tiempo es suficiente o si no hay crédito disponible. */
        if ($seconds >= 15) {
            throw new Exception("Error General", "100000");
        }


        if (floatval($PuntoVenta->getCreditosBase()) - floatval($amount) < 0) {
            throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100002");
        }

        /* guarda los cambios realizados en una transacción en la base de datos. */
        $Transaction->commit();

        try {

            /* actualiza el balance de créditos y verifica disponibilidad, lanzando excepciones si no hay suficiente. */
            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$amount, $Transaction);

            if ($rowsUpdate == null || $rowsUpdate <= 0) {
                throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100000");
            }


            $UsuarioHistorial = new UsuarioHistorial();

            /* Se configura un historial de usuario con propiedades específicas. */
            $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('S');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(10);

            /* inserta un historial de usuario en la base de datos y confirma la transacción. */
            $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
            $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

            $Transaction->commit();

        } catch (Exception $e) {
            /* Maneja excepciones ejecutando un script de PHP para enviar un mensaje de error a Slack. */

            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'ERROR REDES " . $e->getMessage() . "' '#alertas-integraciones' > /dev/null & ");

        }


        /* Calcula la duración en horas, minutos y segundos entre dos tiempos dados. */
        $end_time = microtime(true);
        $duration = $end_time - $start_time;
        $hours = (int)($duration / 60 / 60);
        $minutes = (int)($duration / 60) - $hours * 60;
        $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;

        if ($seconds >= 15) {

            /* Ejecuta un script PHP para importar mensajes y actualiza información de transacciones. */
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'TIME OPERATORAPI DEPOSIT  " . $seconds . " s '.$transactionId. '#alertas-integraciones' > /dev/null & ");

            sleep(1);

            $TransaccionApiUsuario = new TransaccionApiUsuario("", $transactionId, $UsuarioPuntoVenta->usuarioId, "0");

            /**
             * Actualizamos consecutivo Recarga
             */

            $UsuarioRecarga = new UsuarioRecarga($TransaccionApiUsuario->getIdentificador());


            /* verifica un estado y establece uno nuevo para una recarga de usuario. */
            if ($UsuarioRecarga->getEstado() != "A") {
                throw new Exception("La recarga no se puede eliminar", "50001");
            }
            $UsuarioRecarga->setEstado('I');
            $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
            $UsuarioRecarga->setUsueliminaId($UsuarioRecarga->getPuntoventaId());


            /* Inicializa DAO, obtiene transacción y actualiza datos de usuario en la base de datos. */
            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
            $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();

            $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);

            $puntoventa_id = $UsuarioRecarga->getPuntoventaId();

            /* Código para obtener valor de usuario y crear un registro de flujo de caja. */
            $valor = $UsuarioRecarga->getValor();

            $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

            $FlujoCaja = new FlujoCaja();
            $FlujoCaja->setFechaCrea(date('Y-m-d'));

            /* Se configura un objeto FlujoCaja con información de movimiento y usuario. */
            $FlujoCaja->setHoraCrea(date('H:i'));
            $FlujoCaja->setUsucreaId($puntoventa_id);
            $FlujoCaja->setTipomovId('S');
            $FlujoCaja->setValor($valor);
            $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
            $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

            /* establece el estado de devolución y verifica métodos de pago. */
            $FlujoCaja->setDevolucion('S');

            if ($FlujoCaja->getFormapago1Id() == "") {
                $FlujoCaja->setFormapago1Id(0);
            }

            if ($FlujoCaja->getFormapago2Id() == "") {
                $FlujoCaja->setFormapago2Id(0);
            }


            /* asigna valor cero si las formas de flujo de caja están vacías. */
            if ($FlujoCaja->getValorForma1() == "") {
                $FlujoCaja->setValorForma1(0);
            }

            if ($FlujoCaja->getValorForma2() == "") {
                $FlujoCaja->setValorForma2(0);
            }


            /* Asigna valores predeterminados a propiedades del objeto si están vacías. */
            if ($FlujoCaja->getCuentaId() == "") {
                $FlujoCaja->setCuentaId(0);
            }

            if ($FlujoCaja->getPorcenIva() == "") {
                $FlujoCaja->setPorcenIva(0);
            }


            /* valida y establece el valor del IVA antes de insertarlo en la base de datos. */
            if ($FlujoCaja->getValorIva() == "") {
                $FlujoCaja->setValorIva(0);
            }

            $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
            $FlujoCajaMySqlDAO->insert($FlujoCaja);
            //print_r(time());


            /* Se actualiza el balance de créditos en Punto de Venta y verifica disponibilidad. */
            $PuntoVenta = new PuntoVenta("", $puntoventa_id);


            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);

            if ($rowsUpdate == null || $rowsUpdate <= 0) {
                throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100000");
            }

            //print_r(time());


            /* Se crea un objeto con información de saldo y usuario en tiempo actual. */
            $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

            $SaldoUsuonlineAjuste->setTipoId('S');
            $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
            $SaldoUsuonlineAjuste->setValor($valor);
            $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));

            /* ajusta el saldo de un usuario y registra la reversion de una recarga. */
            $SaldoUsuonlineAjuste->setUsucreaId($UsuarioPuntoVenta->usuarioId);
            $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
            $SaldoUsuonlineAjuste->setObserv("Reversion recarga API " . $UsuarioRecarga->getRecargaId());
            if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                $SaldoUsuonlineAjuste->setMotivoId(0);
            }

            /* extrae la IP del usuario y actualiza su estado a 'Inactivo'. */
            $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

            $SaldoUsuonlineAjuste->setDirIp($dir_ip);
            $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());

            $UsuarioRecarga->setEstado('I');

            /* Se actualiza la fecha de eliminación y usuario en la base de datos. */
            $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
            $UsuarioRecarga->setUsueliminaId(0);

            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);

            $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);


            /* maneja transacciones de saldo de usuario en una base de datos MySQL. */
            $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

            $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);

            //print_r(time());


            $Usuario->debit($UsuarioRecarga->getValor(), $Transaction);


            /* Crea un nuevo registro de historial de usuario con información específica. */
            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('S');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);

            /* Se establece un historial de usuario y se inserta en la base de datos. */
            $UsuarioHistorial->setTipo(10);
            $UsuarioHistorial->setValor($valor);
            $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

            //print_r(time());


            /* Se crea un historial de usuario con datos específicos de un punto de venta. */
            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($puntoventa_id);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);

            /* Se establece el tipo, valor y externo ID en el historial de usuario. */
            $UsuarioHistorial->setTipo(10);
            $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
            $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

            //$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            //$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

            //print_r(time());

            $TransaccionApiUsuario = new TransaccionApiUsuario();


            /* establece propiedades de un objeto TransaccionApiUsuario con datos de usuarios y transacción. */
            $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
            $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
            $TransaccionApiUsuario->setValor(($amount));
            $TransaccionApiUsuario->setTipo(0);
            $TransaccionApiUsuario->setTValue(json_encode($params));
            $TransaccionApiUsuario->setRespuestaCodigo("OK");

            /* establece parámetros para una transacción en una API de usuario. */
            $TransaccionApiUsuario->setRespuesta("OK");
            $TransaccionApiUsuario->setTransaccionId($transactionId);

            $TransaccionApiUsuario->setUsucreaId(0);
            $TransaccionApiUsuario->setUsumodifId(0);


            $TransaccionApiUsuario->setIdentificador($UsuarioRecarga->getRecargaId());

            /* Código para insertar una transacción en base de datos y crear un registro de log. */
            $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
            $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

            //print_r(time());

            $TransapiusuarioLog = new TransapiusuarioLog();


            /* Registro de transacción en un sistema utilizando datos de usuario y recarga. */
            $TransapiusuarioLog->setIdentificador($UsuarioRecarga->getRecargaId());
            $TransapiusuarioLog->setTransaccionId($transactionId);
            $TransapiusuarioLog->setTValue(json_encode($params));
            $TransapiusuarioLog->setTipo(3);
            $TransapiusuarioLog->setValor($amount);
            $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);

            /* Se configuran propiedades de un objeto de log para usuario en una transacción. */
            $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


            $TransapiusuarioLog->setUsucreaId(0);
            $TransapiusuarioLog->setUsumodifId(0);


            $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);

             /* inserta un registro y luego lanza una excepción de error general. */
            $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

            //print_r(time());

            $Transaction->commit();

            throw new Exception("Error General", "100000");
        }


        /* Actualiza la fecha y monto del primer depósito de un usuario si está vacío. */
        if ($Usuario->fechaPrimerdeposito == "") {
            $Usuario = new Usuario($Usuario->usuarioId);

            $Usuario->fechaPrimerdeposito = date('Y-m-d H:i:s');
            $Usuario->montoPrimerdeposito = $UsuarioRecarga->getValor();
            $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
            $UsuarioMySqlDAO2->update($Usuario);
            $UsuarioMySqlDAO2->getTransaction()->commit();
        }


        /* Manejo de excepciones que registra advertencias en el sistema al ocurrir errores. */
        try {


        } catch (Exception $e) {
            syslog(LOG_WARNING, "ERRORPROVEEDORAPI :" . $e->getCode() . ' - ' . $e->getMessage());
        }

    } else {
        /* Lanza una excepción con mensaje "Error General" y código "100000" en caso de fallo. */

        throw new Exception("Error General", "100000");
    }


    /* Asigna valores de ID a un arreglo de respuesta para transacciones y depósitos. */
    $response["transactionId"] = $Transapiusuariolog_id;
    $response["depositId"] = $consecutivo_recarga;


} else {
    /* Lanza una excepción por datos de inicio de sesión incorrectos con un código específico. */

    throw new Exception("Datos de login incorrectos", "50003");

}