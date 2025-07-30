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
use Backend\dto\UsuarioLog2;
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
use Backend\mysql\UsuarioLog2MySqlDAO;
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
 *
 * command/set_user_limits
 *
 * Procesamiento de límites de usuario por tipo de operación
 *
 * Este recurso maneja las solicitudes para establecer límites de operación de un usuario, basados en el tipo de operación
 * (depósito, deporte, casino, etc.) y las limitaciones de tiempo (diaria, semanal, mensual).
 * El proceso verifica si el usuario ya tiene un límite activo y, si no es así, lo crea. Si ya existe un límite, se lanza una excepción.
 *
 * @param int $type : Vertical asociada a la limitacion
 * @param int $time_limitation :  Tiempo de limitacion
 * @param int $amount : Monto a limitar
 * @param int $note : Nota de la limitacion
 *
 * @return objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Mensaje de error si ocurre.
 *  - *data* (array): Resultado de la operación o detalles de la transacción.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws Exception Si el usuario ya tiene un límite activo o si ocurre un error al procesar la transacción.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Extrae parámetros de un JSON y crea un objeto UsuarioMandante con un ID. */
$type = $json->params->type;
$time_limitation = $json->params->time_limitation;
$amount = $json->params->amount;
$note = $json->params->note;
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ClientId = $UsuarioMandante->getUsuarioMandante();

/**
 * getDateExclusion
 *
 * Obtiene una fecha de exclusión basada en un valor de índice predefinido.
 *
 * @param int $value Índice para seleccionar una fecha de exclusión. Valores aceptados:
 *                   - 0 → Fecha actual + 1 día.
 *                   - 1 → Fecha actual + 1 semana.
 *                   - 2 → Fecha actual + 1 mes.
 *
 * @return string response Fecha de exclusión en formato 'Y-m-d H:i:s'. Si el índice no es válido, retorna una cadena vacía.
 *
 * @access public
 */

function getDateExclusion($value)
{
    $currentDate = date('Y-m-d H:i:s');
    $dates = [
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 1 day')),
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 1 week')),
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 1 month')),
    ];
    return isset($dates[$value]) ? $dates[$value] : '';
}

/*
 * $time_limitation
 * 0 - Diario
 * 1 - Semanal
 * 2 - Mensual
 */
$time_limitationG = $time_limitation;

/* establece una limitación de tiempo y crea un objeto de configuración de usuario. */
$time_limitation = getDateExclusion($time_limitation);
$UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
if ($type == "deposit") {

    /* establece límites de depósito según el tiempo y genera una excepción. */
    try {
        $abreviadoF = 'LIMITEDEPOSITOSIMPLE';
        switch ($time_limitationG) {
            case 0:
                $abreviadoF = 'LIMITEDEPOSITODIARIO';
                break;
            case 1:
                $abreviadoF = 'LIMITEDEPOSITOSEMANA';
                break;
            case 2:
                $abreviadoF = 'LIMITEDEPOSITOMENSUAL';
                break;
        }
        $Clasificador = new Clasificador("", $abreviadoF);
        $tipo = $Clasificador->getClasificadorId();
        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $tipo);
        throw new Exception('Señor usuario usted ya cuenta con una limitación activa', 300033);
    } catch (Exception $e) {

        /* Registra un log de usuario en la base de datos si se cumple una condición. */
        if ($e->getCode() == 30) {
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        } elseif ($e->getCode() == 46) {


            /* Crea y configura un objeto UsuarioConfiguracion con datos del usuario y sesión. */
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($tipo);
            $UsuarioConfiguracion->setNota($note);
            $UsuarioConfiguracion->setValor($amount);
            $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

            /* Se establece configuración de usuario y se inserta en la base de datos. */
            $UsuarioConfiguracion->setUsumodifId("0");
            $UsuarioConfiguracion->setProductoId("0");
            $UsuarioConfiguracion->setEstado("A");
            $UsuarioConfiguracion->setFechaFin($time_limitation);

            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);


            /* Se registra un nuevo objeto UsuarioLog con información de usuario y sesión. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);

            /* Se registran cambios en el estado y valores de usuario en una base de datos. */
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($UsuarioConfiguracion->getValor());
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());

            /* Inserta un registro de usuario y obtiene la configuración de la transacción en MySQL. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        } else {
            /* Captura excepciones y lanza una nueva con el mensaje y código originales. */

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}

if ($type == "sport") {

    /* asigna un valor a una variable según un caso y lanza una excepción. */
    try {
        $abreviadoF = 'LIMAPUDEPORTIVASIMPLE';
        switch ($time_limitationG) {
            case 0:
                $abreviadoF = 'LIMAPUDEPORTIVADIARIO';
                break;
            case 1:
                $abreviadoF = 'LIMAPUDEPORTIVASEMANA';
                break;
            case 2:
                $abreviadoF = 'LIMAPUDEPORTIVAMENSUAL';
                break;
        }
        $Clasificador = new Clasificador("", $abreviadoF);
        $tipo = $Clasificador->getClasificadorId();
        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $tipo);
        throw new Exception('Señor usuario usted ya cuenta con una limitación activa', 300033);
    } catch (Exception $e) {

        /* Registra un log de usuario en base de datos si el código es 30. */
        if ($e->getCode() == 30) {

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        } elseif ($e->getCode() == 46) {


            /* Se crea un objeto UsuarioConfiguracion y se establecen sus propiedades. */
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($tipo);
            $UsuarioConfiguracion->setNota($note);
            $UsuarioConfiguracion->setValor($amount);
            $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

            /* Configuración de usuario para insertar en base de datos con parámetros específicos. */
            $UsuarioConfiguracion->setUsumodifId("0");
            $UsuarioConfiguracion->setProductoId("0");
            $UsuarioConfiguracion->setEstado("A");
            $UsuarioConfiguracion->setFechaFin($time_limitation);

            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);


            /* crea un registro de usuario con información de ID y IP. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);

            /* Registro de cambios en el estado de usuario y sus valores previos y posteriores. */
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($UsuarioConfiguracion->getValor());
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());

            /* Inserta un registro de usuario y obtiene la transacción de configuración en MySQL. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        } else {
            /* Manejo de excepciones; lanza un error con mensaje y código específico. */

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}

if ($type == "casino") {

    /* maneja limitaciones de usuario y lanza una excepción en condiciones específicas. */
    try {
        $abreviadoF = 'LIMAPUCASINOSIMPLE';
        switch ($time_limitationG) {
            case 0:
                $abreviadoF = 'LIMAPUCASINODIARIO';
                break;
            case 1:
                $abreviadoF = 'LIMAPUCASINOSEMANA';
                break;
            case 2:
                $abreviadoF = 'LIMAPUCASINOMENSUAL';
                break;
        }
        $Clasificador = new Clasificador("", $abreviadoF);
        $tipo = $Clasificador->getClasificadorId();
        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $tipo);
        throw new Exception('Señor usuario usted ya cuenta con una limitación activa', 300033);
    } catch (Exception $e) {

        /* Registra un log de usuario si se cumple una condición específica en el código. */
        if ($e->getCode() == 30) {

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        } elseif ($e->getCode() == 46) {


            /* Código que inicializa y configura un objeto `UsuarioConfiguracion` con datos específicos. */
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($tipo);
            $UsuarioConfiguracion->setNota($note);
            $UsuarioConfiguracion->setValor($amount);
            $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

            /* Configura usuario y guarda los datos en la base de datos MySQL. */
            $UsuarioConfiguracion->setUsumodifId("0");
            $UsuarioConfiguracion->setProductoId("0");
            $UsuarioConfiguracion->setEstado("A");
            $UsuarioConfiguracion->setFechaFin($time_limitation);

            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);


            /* Se crea un registro de usuario con información de sesión y tipo especificado. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);

            /* Registra cambios en el estado y valor de un usuario con un objeto de log. */
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($UsuarioConfiguracion->getValor());
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());

            /* Código para insertar un registro y gestionar transacciones en una base de datos MySQL. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        } else {
            /* Lanza una excepción con el mensaje y código del error capturado. */

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}

if ($type == "livecasino") {

    /* establece una limitación de tiempo y crea configuraciones de usuario. */
    try {
        $abreviadoF = 'LIMAPUCASINOVIVOSIMPLE';
        switch ($time_limitationG) {
            case 0:
                $abreviadoF = 'LIMAPUCASINOVIVODIARIO';
                break;
            case 1:
                $abreviadoF = 'LIMAPUCASINOVIVOSEMANA';
                break;
            case 2:
                $abreviadoF = 'LIMAPUCASINOVIVOMENSUAL';
                break;
        }
        $Clasificador = new Clasificador("", $abreviadoF);
        $tipo = $Clasificador->getClasificadorId();
        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $tipo);
        throw new Exception('Señor usuario usted ya cuenta con una limitación activa', 300033);
    } catch (Exception $e) {

        /* Registra un log de usuario cuando se cumple la condición del código 30. */
        if ($e->getCode() == 30) {

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        } elseif ($e->getCode() == 46) {


            /* crea una nueva configuración de usuario con atributos específicos. */
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($tipo);
            $UsuarioConfiguracion->setNota($note);
            $UsuarioConfiguracion->setValor($amount);
            $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

            /* Se configura un usuario y se insertan datos en la base de datos. */
            $UsuarioConfiguracion->setUsumodifId("0");
            $UsuarioConfiguracion->setProductoId("0");
            $UsuarioConfiguracion->setEstado("A");
            $UsuarioConfiguracion->setFechaFin($time_limitation);

            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);


            /* Inicializa un objeto de registro de usuario con datos específicos en PHP. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);

            /* registra cambios en la configuración de un usuario en la base de datos. */
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($UsuarioConfiguracion->getValor());
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());

            /* Inserta un registro de usuario y obtiene una transacción de configuración en MySQL. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        } else {
            /* lanza una excepción con el mensaje y el código del error. */

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}

if ($type == "virtual") {

    /* gestiona limitaciones de tiempo y lanza excepciones según configuraciones. */
    try {
        $abreviadoF = 'LIMAPUVIRTUALESSIMPLE';
        switch ($time_limitationG) {
            case 0:
                $abreviadoF = 'LIMAPUVIRTUALESDIARIO';
                break;
            case 1:
                $abreviadoF = 'LIMAPUVIRTUALESSEMANA';
                break;
            case 2:
                $abreviadoF = 'LIMAPUVIRTUALESMENSUAL';
                break;
        }


        $Clasificador = new Clasificador("", $abreviadoF);
        $tipo = $Clasificador->getClasificadorId();
        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $tipo);
        throw new Exception('Señor usuario usted ya cuenta con una limitación activa', 300033);
    } catch (Exception $e) {

        /* Registro un evento de usuario en la base de datos si se cumple una condición. */
        if ($e->getCode() == 30) {

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());
            $UsuarioLogMySqlDAO->insert($UsuarioLog);


            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        } elseif ($e->getCode() == 46) {


            /* Crea y configura un objeto UsuarioConfiguracion con datos del usuario y sesión. */
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($tipo);
            $UsuarioConfiguracion->setNota($note);
            $UsuarioConfiguracion->setValor($amount);
            $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

            /* Configura un usuario y lo inserta en la base de datos. */
            $UsuarioConfiguracion->setUsumodifId("0");
            $UsuarioConfiguracion->setProductoId("0");
            $UsuarioConfiguracion->setEstado("A");
            $UsuarioConfiguracion->setFechaFin($time_limitation);

            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);


            /* Se crea un objeto UsuarioLog2 y se configuran sus propiedades. */
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($json->session->usuarioip);
            $UsuarioLog->setUsuariosolicitaId($ClientId);
            $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
            $UsuarioLog->setTipo($tipo);

            /* Registra el estado y cambios de un usuario en la base de datos. */
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($UsuarioConfiguracion->getValor());
            $UsuarioLog->setValorDespues($amount);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($UsuarioConfiguracionMySqlDAO->getTransaction());

            /* Se insertan datos de usuario y se inicia una transacción en MySQL. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        } else {
            /* Manejo de excepciones en código, lanzando un error con mensaje y código. */

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}


/* Código PHP que crea un array de respuesta estructurado con datos por defecto. */
$response = array(
    "code" => 0,
    "data" => array(
        "result" => 0,
        "result_text" => null,
        "data" => array(),
    ),
);

