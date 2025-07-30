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
 * command/update_user_password
 *
 * Actualizar clave del usuario
 *
 * @param string $clave : clave actual
 * @param string $nueva_clave : Nueva clave a actualizar
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor
 *  - *data* (array): Contiene el mensaje de aprobación del proceso y el token de autentificación.
 *
 * @throws Exception el tamaño de la contraseña es demasiado corto
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* verifica la sesión del usuario y asigna contraseñas desde JSON. */
$clave = $json->params->password;
$nueva_clave = $json->params->new_password;
if ($json->session->usuario == "") {
    exit();
}

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

/* verifica si $UsuarioMandante está vacío o es nulo, y si es así, termina la ejecución. */
if ($UsuarioMandante == "" || $UsuarioMandante == null) {
    exit();
}


if ($UsuarioMandante->mandante == 21) {

    /* Código para gestionar usuario: verificar contraseña, cambiarla y obtener ID de usuario. */
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $MandanteUser = $UsuarioMandante->mandante;
    $UsuarioClave = $Usuario->checkClave($clave);

    $UsuarioCambioClave = $Usuario->changeClave($nueva_clave);

    $UsuarioId = $Usuario->usuarioId;

    /* crea un objeto de CuentaAsociada y obtiene un Usuario asociado. */
    try {

        $CuentaAsociada = new CuentaAsociada('', $UsuarioId);

        $SegundaCuenta = $CuentaAsociada->usuarioId2;

        $Usuario2 = new Usuario($SegundaCuenta);
    } catch (Exception $e) {
        /* Manejo de excepciones para crear un usuario basado en un código de error específico. */

        if ($e->getCode() == '110008') {

            $CuentaAsociada = new CuentaAsociada('', '', $UsuarioId);

            $SegundaCuenta = $CuentaAsociada->usuarioId;

            $Usuario = new Usuario($SegundaCuenta);
        }
    }

    /* Código que crea objetos y obtiene longitud de una nueva contraseña en PHP. */
    try {
        $clasificador = new Clasificador("", "MINLENPASSWORD");

        $MandanteDetalle = new MandanteDetalle('', $MandanteUser, $clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->estado);

        $valor = $MandanteDetalle->valor;

        $characters = strlen($nueva_clave);


    } catch (\Exception $e) {
        /* Captura excepciones en PHP y evita que el script falle. */


    }


    /* Valida contraseña corta, cambia clave de usuario y obtiene IP de sesión. */
    if ($characters < $MandanteDetalle->valor) {
        throw new Exception("el tamaño de la contraseña es demasiado corto", 100077);
    }

    $UsuarioCambioClave = $Usuario->changeClave($nueva_clave);

    $ip = $json->session->usuarioip;


    /* Registro de información del usuario y su IP en el sistema. */
    $UsuarioLog = new UsuarioLog();
    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
    $UsuarioLog->setUsuarioIp($ip);

    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
    $UsuarioLog->setUsuariosolicitaIp($ip);


    /* Registra un cambio de clave de usuario con detalles específicos como IP y estado. */
    $UsuarioLog->setTipo("CAMBIOCLAVE");
    $UsuarioLog->setEstado("A");
    $UsuarioLog->setValorAntes($ip);
    $UsuarioLog->setValorDespues($ip);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    /* Inserta un registro de usuario y clasifica con un nuevo objeto. */
    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
    $UsuarioLogMySqlDAO->insert($UsuarioLog);
    $UsuarioLogMySqlDAO->getTransaction()->commit();


    $clasificador = new Clasificador("", "DAYSEXPIREPASSWORD");

    try {


        /* Se crea un objeto y se establece la fecha de modificación para un usuario. */
        $MandantDetalle = new MandanteDetalle('', $MandanteUser, $clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->estado);

        $valor = $MandantDetalle->valor;

        $fecha_Modificacion = date('Y-m-d H:i:s');

        $Usuario->setFechaClave($fecha_Modificacion);


        // $Usuario->fechaClave = $date1;

        /* Código que actualiza un usuario en una base de datos utilizando transacciones. */
        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $transaction = $UsuarioMySqlDAO->getTransaction();

        $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaction);
        $UsuarioMySqlDAO->update($Usuario);

        $transaction->commit();


        /* Se inicializa un array vacío llamado $response para almacenar datos. */
        $response = array();

    } catch (\Throwable $th) {
        /* Captura excepciones en PHP, pero no las lanza nuevamente. */

        //throw $th;
    }

    // $Usuario->setFechaClave(date('Y-m-d H:i:s'));
    // // $Usuario->fechaClave = $date1;
    // $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    // $transaction = $UsuarioMySqlDAO->getTransaction();

    // $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaction);
    // $UsuarioMySqlDAO->update($Usuario);

    // $transaction->commit();

} else {


    /* crea un usuario y verifica su clave, inicializa detalles de mandante. */
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $MandanteUser = $Usuario->mandante;

    $UsuarioClave = $Usuario->checkClave($clave);

    try {
        $clasificador = new Clasificador("", "MINLENPASSWORD");

        $MandanteDetalle = new MandanteDetalle('', $MandanteUser, $clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->estado);

        $valor = $MandanteDetalle->valor;

        $characters = strlen($nueva_clave);


    } catch (\Exception $e) {
        /* captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


    }


    /* Valida la longitud de la contraseña y cambia la clave del usuario. */
    if ($valor != null && $characters < $valor) {
        throw new Exception("el tamaño de la contraseña es demasiado corto", 100077);
    }

    $UsuarioCambioClave = $Usuario->changeClave($nueva_clave);


    $ip = $json->session->usuarioip;


    /* Código para registrar información de usuario y su solicitud en un log. */
    $UsuarioLog = new UsuarioLog();
    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
    $UsuarioLog->setUsuarioIp($ip);

    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
    $UsuarioLog->setUsuariosolicitaIp($ip);


    /* Registro de cambio de clave de usuario con información de IP y estado. */
    $UsuarioLog->setTipo("CAMBIOCLAVE");
    $UsuarioLog->setEstado("A");
    $UsuarioLog->setValorAntes($ip);
    $UsuarioLog->setValorDespues($ip);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    /* Inserta un log de usuario y crea un clasificador para días de expiración de contraseñas. */
    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
    $UsuarioLogMySqlDAO->insert($UsuarioLog);
    $UsuarioLogMySqlDAO->getTransaction()->commit();


    $clasificador = new Clasificador("", "DAYSEXPIREPASSWORD");

    try {


        /* Se crea un objeto MandanteDetalle y se actualiza la fecha de la clave del usuario. */
        $MandantDetalle = new MandanteDetalle('', $MandanteUser, $clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->estado);

        $valor = $MandantDetalle->valor;

        $fecha_Modificacion = date('Y-m-d H:i:s');

        $Usuario->setFechaClave($fecha_Modificacion);


// $Usuario->fechaClave = $date1;

        /* actualiza un usuario en la base de datos y confirma la transacción. */
        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $transaction = $UsuarioMySqlDAO->getTransaction();

        $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaction);
        $UsuarioMySqlDAO->update($Usuario);

        $transaction->commit();


        /* Se inicializa un array vacío llamado $response en PHP. */
        $response = array();

    } catch (\Exception $th) {
        /* captura excepciones sin realizar ninguna acción específica. */

    }

}

/* crea un array de respuesta con un token de autenticación. */
$response = array();

$response['code'] = 0;

$data = array();

$data["auth_token"] = "543456ASDASDA";

/* Se inicializa una variable y se asigna a una respuesta de datos. */
$data["result"] = 0;

$response['data'] = $data;

