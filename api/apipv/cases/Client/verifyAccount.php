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
use Backend\dto\UsuarioVerificacion;
use Backend\integrations\auth\OcrBigId;
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
use Backend\mysql\UsuarioVerificacionMySqlDAO;
use Backend\websocket\WebsocketUsuario;


//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');

/**
 * Procesa la verificación de una cuenta de usuario, actualizando los datos según los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params ->Id Identificador del usuario o perfil.
 * @param string $params ->Name Nombre del usuario.
 * @param string $params ->Phone Teléfono del usuario.
 * @param string $params ->Address Dirección del usuario.
 * @param string $params ->ImageAnt Imagen anterior en formato base64.
 * @param string $params ->DocNumber Número de documento del usuario.
 * @param string $params ->ImagePos Imagen posterior en formato base64.
 * @param string $params ->Facebook Perfil de Facebook del usuario.
 * @param string $params ->Instagram Perfil de Instagram del usuario.
 * @param string $params ->OtherSocialMediaName Nombre de otra red social.
 * @param string $params ->OtherSocialMediaContact Contacto en otra red social.
 * @param string $params ->BirthDate Fecha de nacimiento del usuario.
 * @param string $params ->MiddleName Segundo nombre del usuario.
 * @param string $params ->SecondLastName Segundo apellido del usuario.
 * @param string $params ->Password Contraseña del usuario.
 * @param string $params ->FormType Tipo de formulario (obtenido de la sesión).
 *
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success' o 'error').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - data (array): Datos adicionales de la respuesta.
 *
 * @throws Exception Si la cuenta ya está en estado de verificación o si ocurre un error en el proceso.
 */


/* recibe y decodifica datos JSON desde una solicitud PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);


$Id = $params->Id;
$Name = $params->Name; //OK

/* asigna valores de parámetros a variables en un contexto de programación. */
$Phone = $params->Phone; //OK
$Address = $params->Address; //OK
$ImageAnt = $params->ImageAnt; //OK
$DocNumber = $params->DocNumber; //OK
$ImagePos = $params->ImagePos; //OK
$Facebook = $params->Facebook;

/* Se asignan valores de parámetros a variables de contacto y datos personales. */
$Instagram = $params->Instagram;
$OtherSocialMediaName = $params->OtherSocialMediaName;
$OtherSocialMediaContact = $params->OtherSocialMediaContact;
$BirthDate = $params->BirthDate;
$MiddleName = $params->MiddleName;
$SecondLastName = $params->SecondLastName;

/* asigna valores de sesión y parámetros a variables para manejo de usuario. */
$Password = $params->Password;
$FormType = $params->FormType; //Tomar de la Session

$usuarioip = $_SESSION['dir_ip'];

$UsuarioMandante = new UsuarioMandante("", $_SESSION['usuario'], $_SESSION['mandante']);

/* Se crean instancias de Usuario y UsuarioPerfil usando datos de un mandante y un ID. */
$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

$UsuarioPerfil = new UsuarioPerfil($Id);


if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {

    /* Inicializa un punto de venta y verifica la clave del usuario en MySQL. */
    $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);

    $UsuarioClave = $Usuario->checkClave($Password);

    $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
    $Transaction = $UsuarioLog2MySqlDAO->getTransaction();


    /* Se crea un objeto de acceso a datos de usuario y se inicializa una variable. */
    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

    $UpdateUSER = false;

    if ($ImageAnt != "") {

        /* decodifica una imagen en base64 y ajusta el estado del jugador. */
        $tipo = 'USUDNIANTERIOR'; //OK
        $pos = strpos($ImageAnt, 'base64,');
        $file_contents1 = base64_decode(substr($ImageAnt, $pos + 7));
        $file_contents1 = addslashes($file_contents1);

        $Usuario->estadoJugador = 'P' . substr($Usuario->estadoJugador, 1, 1);

        /* Actualiza datos del usuario logueado con su ID y dirección IP. */
        $UpdateUSER = true;

        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);

        /* configura un registro de usuario con varios parámetros y estado pendiente. */
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);
        $UsuarioLog->setUsuarioaprobarId(0);
        $UsuarioLog->setTipo($tipo);
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes('');
        $UsuarioLog->setValorDespues('');

        /* Se configura un usuario log y se inserta en la base de datos. */
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLog->setImagen($file_contents1);

        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

        $UsuarioLogMySqlDAO2->insert($UsuarioLog);


    }


    if ($ImagePos != "") {

        /* Decodifica una imagen en base64 y modifica el estado del jugador. */
        $tipo = 'USUDNIPOSTERIOR'; //OK
        $pos = strpos($ImagePos, 'base64,');
        $file_contents1 = base64_decode(substr($ImagePos, $pos + 7));
        $file_contents1 = addslashes($file_contents1);


        $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1) . 'P';

        /* Se registra la información del usuario en un objeto UsuarioLog2. */
        $UpdateUSER = true;


        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        /* Se configura un registro de usuario con detalles específicos en el sistema. */
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);
        $UsuarioLog->setUsuarioaprobarId(0);
        $UsuarioLog->setTipo($tipo);
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes('');

        /* Se configuran propiedades de un objeto `UsuarioLog` y se inicializa un DAO de MySQL. */
        $UsuarioLog->setValorDespues('');
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLog->setImagen($file_contents1);

        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);


        /* Se inserta un registro de usuario en la base de datos usando DAO en MySQL. */
        $UsuarioLogMySqlDAO2->insert($UsuarioLog);


    }


    /* Registra cambios en la dirección del usuario en el sistema de logs. */
    if ($Address != $PuntoVenta->getDireccion() && $Address != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUDIRECCION"); //OK
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($PuntoVenta->getDireccion());
        $UsuarioLog->setValorDespues($Address);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);


        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }


    /* Registra un cambio de teléfono si el nuevo número es diferente y no está vacío. */
    if ($Phone != $PuntoVenta->getTelefono() && $Phone != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUCELULAR");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($PuntoVenta->getTelefono());
        $UsuarioLog->setValorDespues($Phone);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }


    /* Registra un cambio en el número de documento y usuario en la base de datos. */
    if ($DocNumber != $PuntoVenta->getCedula() && $DocNumber != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUCEDULA");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($PuntoVenta->getCedula());
        $UsuarioLog->setValorDespues($DocNumber);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }


    /* Registra un cambio en Facebook de un usuario si es diferente y no vacío. */
    if ($Facebook != $PuntoVenta->getFacebook() && $Facebook != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUFACEBOOK"); //OK
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($PuntoVenta->getFacebook());
        $UsuarioLog->setValorDespues($Facebook);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }


    /* Registra cambios de Instagram en el log de usuarios si son diferentes y no vacíos. */
    if ($Instagram != $PuntoVenta->getInstagram() && $Instagram != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUINSTAGRAM"); //OK
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($PuntoVenta->getFacebook());
        $UsuarioLog->setValorDespues($Instagram);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }


    /* Registra un cambio de contacto en redes sociales si es diferente y no vacío. */
    if ($OtherSocialMediaContact != $PuntoVenta->getOtraRedesSocial() && $OtherSocialMediaContact != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUOTRAREDSOCIAL");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($PuntoVenta->getOtraRedesSocial());
        $UsuarioLog->setValorDespues($OtherSocialMediaContact);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }

    if ($Name != "" && $Name != $PuntoVenta->getDescripcion()) {


        /* Se crea un registro de usuario con ID y IP del solicitante. */
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);


        /* configura variables para un registro de log de usuario relacionado a ventas. */
        $UsuarioLog->setTipo("PVNOMBRE");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($PuntoVenta->getDescripcion());
        $UsuarioLog->setValorDespues($Name);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);


        /* Inserta un registro de usuario en la base de datos mediante el DAO. */
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);


    }


    /* actualiza un usuario si la condición se cumple y obtiene una transacción. */
    if ($UpdateUSER) {
        $UsuarioMySqlDAO->update($Usuario);
    }

    $UsuarioLog2MySqlDAO->getTransaction()->commit();

}
if ($UsuarioPerfil->perfilId == "USUONLINE") {


    /* crea instancias de clases para manejar registro y usuario en MySQL. */
    $Registro = new Registro("", $Id);
    $UsuarioOtraInfo = new UsuarioOtrainfo($Registro->usuarioId);
//$UsuarioClave = $Usuario->checkClave($Password);
    $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
    $Transaction = $UsuarioLog2MySqlDAO->getTransaction();

    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);


    /* Intenta crear una instancia de UsuarioVerificacion y maneja posibles excepciones. */
    $UpdateUSER = false;

    try {
        $UsuarioVerificacion = new UsuarioVerificacion('', $UsuarioPerfil->usuarioId, 'P', 'USUVERIFICACION');
    } catch (Exception $ex) {
    }


    /* verifica una cuenta y lanza una excepción si ya está en verificación. */
    if (isset($UsuarioVerificacion)) throw new Exception('Su cuenta ya se encuentra en estado de verificacion', 100086);
    $Clasificador = new Clasificador('', 'VERIFICAMANUAL');

    /* Se crea un objeto UsuarioVerificacion y se configuran sus propiedades. */
    $UsuarioVerificacion = new UsuarioVerificacion();
    $UsuarioVerificacion->setUsuarioId($UsuarioPerfil->usuarioId);
    $UsuarioVerificacion->setMandante($UsuarioPerfil->mandante);
    $UsuarioVerificacion->setPaisId($UsuarioMandante->paisId);
    $UsuarioVerificacion->setTipo('USUVERIFICACION');
    $UsuarioVerificacion->setEstado('P');

    /* Código inserta un nuevo registro de verificación de usuario en la base de datos. */
    $UsuarioVerificacion->setObservacion('');
    $UsuarioVerificacion->setUsucreaId($UsuarioMandante->usuarioMandante);
    $UsuarioVerificacion->setClasificadorId($Clasificador->clasificadorId ?: '0');

    $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO($Transaction);
    $ID = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);

    if ($ImageAnt != "") {

        /* decodifica una imagen en base64 y modifica el estado del usuario. */
        $tipo = 'USUDNIANTERIOR'; //OK
        $pos = strpos($ImageAnt, 'base64,');
        $file_contents1 = base64_decode(substr($ImageAnt, $pos + 7));
        $file_contents1 = addslashes($file_contents1);

        $Usuario->estadoJugador = 'P' . substr($Usuario->estadoJugador, 1, 1);

        /* Código para registrar información de un usuario en un sistema de logging. */
        $UpdateUSER = true;

        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Registro->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);

        /* establece propiedades de un objeto UsuarioLog relacionado con una solicitud de usuario. */
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);
        $UsuarioLog->setUsuarioaprobarId(0);
        $UsuarioLog->setTipo($tipo);
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes('');
        $UsuarioLog->setValorDespues('');

        /* Inicializa propiedades de usuario en un objeto y crea un DAO para transacciones. */
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLog->setSversion($ID);
        $UsuarioLog->setImagen($file_contents1);

        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);


        /* Inserta un registro de usuario en la base de datos mediante un objeto DAO. */
        $UsuarioLogMySqlDAO2->insert($UsuarioLog);


    }


    if ($ImagePos != "") {

        /* decodifica una imagen en base64 y modifica el estado del jugador. */
        $tipo = 'USUDNIPOSTERIOR'; //OK
        $pos = strpos($ImagePos, 'base64,');
        $file_contents1 = base64_decode(substr($ImagePos, $pos + 7));
        $file_contents1 = addslashes($file_contents1);


        $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1) . 'P';

        /* Se crea un registro de log con ID de usuario e IP. */
        $UpdateUSER = true;


        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Registro->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        /* Se configura un registro de log de usuario con detalles específicos. */
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);
        $UsuarioLog->setUsuarioaprobarId(0);
        $UsuarioLog->setTipo($tipo);
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes('');

        /* Se inicializan valores en un objeto UsuarioLog para registro en la base de datos. */
        $UsuarioLog->setValorDespues('');
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLog->setSversion($ID);
        $UsuarioLog->setImagen($file_contents1);

        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);


        /* inserta un registro de usuario en la base de datos MySQL. */
        $UsuarioLogMySqlDAO2->insert($UsuarioLog);


    }


    /* Registra cambios en la dirección del usuario si la nueva dirección es diferente. */
    if ($Address != $Registro->getDireccion() && $Address != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Registro->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUDIRECCION"); //OK
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($Registro->getDireccion());
        $UsuarioLog->setValorDespues($Address);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setSversion($ID);
        $UsuarioLog->setUsumodifId(0);


        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }


    /* Registra un cambio de número de teléfono en el sistema, si es diferente. */
    if ($Phone != $Registro->getTelefono() && $Phone != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Registro->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUCELULAR");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($Registro->getTelefono());
        $UsuarioLog->setValorDespues($Phone);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setSversion($ID);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }


    /* Registra un cambio de cédula si el número es distinto y no está vacío. */
    if ($DocNumber != $Registro->getCedula() && $DocNumber != '') {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Registro->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);

        $UsuarioLog->setTipo("USUCEDULA");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($Registro->getCedula());
        $UsuarioLog->setValorDespues($DocNumber);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setSversion($ID);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    }


    if ($BirthDate != "" && $BirthDate != $UsuarioOtraInfo->getFechaNacim()) {


        /* crea un registro de log para un usuario con sus datos. */
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Registro->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);


        /* Registra cambios en la fecha de nacimiento de un usuario en el sistema. */
        $UsuarioLog->setTipo("USUFECHANACIM");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
        $UsuarioLog->setValorDespues($BirthDate);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setSversion($ID);

        /* Se está configurando un ID y luego se inserta un registro en la base de datos. */
        $UsuarioLog->setUsumodifId(0);


        $UsuarioLog2MySqlDAO->insert($UsuarioLog);


    }

    if ($MiddleName != "" && $MiddleName != $Registro->getNombre2()) {


        /* Se crea un registro de usuario con ID e IP del solicitante. */
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Registro->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);


        /* registra cambios en un objeto de usuario log para seguimiento. */
        $UsuarioLog->setTipo("USUNOMBRE2");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($Registro->getNombre2());
        $UsuarioLog->setValorDespues($MiddleName);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setSversion($ID);

        /* Se establece un ID de modificación y se inserta en la base de datos. */
        $UsuarioLog->setUsumodifId(0);


        $UsuarioLog2MySqlDAO->insert($UsuarioLog);


    }

    if ($SecondLastName != "" && $SecondLastName != $Registro->getApellido2()) {


        /* Se crea un objeto de registro de usuario y se configuran sus propiedades. */
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Registro->usuarioId);
        $UsuarioLog->setUsuarioIp($usuarioip);

        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($usuarioip);


        /* registra cambios en un usuario, incluyendo estado y apellidos. */
        $UsuarioLog->setTipo("USUAPELLIDO2");
        $UsuarioLog->setEstado("P");
        $UsuarioLog->setValorAntes($Registro->getApellido2());
        $UsuarioLog->setValorDespues($SecondLastName);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setSversion($ID);

        /* Inserta un registro de usuario modificado en la base de datos MySQL. */
        $UsuarioLog->setUsumodifId(0);


        $UsuarioLog2MySqlDAO->insert($UsuarioLog);


    }

    /* Actualiza un usuario si se cumple la condición y gestiona la transacción correspondiente. */
    if ($UpdateUSER) {
        $UsuarioMySqlDAO->update($Usuario);
    }

    $UsuarioLog2MySqlDAO->getTransaction()->commit();

}


$response = array();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = [];

