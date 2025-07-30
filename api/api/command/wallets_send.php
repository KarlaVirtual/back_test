<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\ContactoComercial;
use Backend\dto\CuentaCobro;
use Backend\dto\Departamento;
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
use Backend\dto\UsuarioBilletera;
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
use Backend\integrations\wallet\Quisk;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioBilleteraMySqlDAO;
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
 * command/wallets_send
 *
 * Actualización de información del usuario y billeteras
 *
 * Este código valida y actualiza los datos del usuario, como la cédula, celular y fecha de nacimiento.
 * Si los parámetros proporcionados no coinciden con los datos almacenados, se actualiza la base de datos.
 * Además, se maneja la creación de la billetera del usuario y se realiza una validación con el servicio Quisk.
 * En caso de error en la integración con Quisk, se registra un log de la acción y se lanza una excepción con el código correspondiente.
 *
 * @param object $json : Objeto que contiene los parámetros necesarios para la operación, incluyendo cédula, celular, fecha de nacimiento y otros datos del usuario.
 * @param string $json ->params->cedula : Cédula del usuario a comparar con la almacenada en la base de datos.
 * @param string $json ->params->celular : Número de celular del usuario a comparar con el almacenado en la base de datos.
 * @param string $json ->params->fecha_nacim : Fecha de nacimiento del usuario a comparar con la almacenada en la base de datos.
 * @param object $UsuarioMandanteSite : Información del usuario relacionada con el partner.
 *
 * El objeto `$response` es un array con los siguientes atributos:
 *  - *code* (int): Código de respuesta, donde 0 indica éxito.
 *  - *msg* (string): Mensaje con la respuesta del proceso.
 *
 * @throws Exception Si los parámetros no coinciden con los almacenados o si ocurre un error con Quisk o la base de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


//se reciben los parametros para comparar

/* actualiza la cédula en la base de datos si es diferente. */
$cedula = $json->params->cedula;
$celular = $json->params->celular;
$fecha_nacim = $json->params->fecha_nacim;

$registro = new Registro($UsuarioMandanteSite->usuarioMandante);

// se comparan los parametros que llegan con los almacenados en base de datos
//si son diferentes se realiza el update en la tabla registro
if ($cedula != $registro->cedula) {
    $registro->cedula = $cedula;
    $RegistroMySqlDAO = new RegistroMySqlDAO();
    $RegistroMySqlDAO->update($registro);
    $RegistroMySqlDAO->getTransaction()->commit();
}
//si son diferentes se realiza el update en la tabla UsuarioOtrainfo

/* Asigna un valor celular y actualiza el registro en la base de datos si está vacío. */
if ($celular = !$registro->celular) {
    $registro->celular = $celular;
    $RegistroMySqlDAO = new RegistroMySqlDAO();
    $RegistroMySqlDAO->update($registro);
    $RegistroMySqlDAO->getTransaction()->commit();
}


/* Actualiza la fecha de nacimiento de un usuario si es diferente a la actual. */
$UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandanteSite->usuarioMandante);

if ($fecha_nacim != $UsuarioOtrainfo->fechaNacim) {

    $UsuarioOtrainfo->fechaNacim = $fecha_nacim;
    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();
    $UsuarioOtrainfoMySqlDAO->update($UsuarioOtrainfo);
    $UsuarioOtrainfoMySqlDAO->getTransaction()->commit();
}


/* inicializa un arreglo de respuesta y crea un objeto UsuarioBilletera. */
$response = array("code" => 0);

try {
    $UsuarioBilletera = new UsuarioBilletera('', $UsuarioMandanteSite->usuarioMandante, '1');
    $response = array("code" => 0);

} catch (Exception $e) {


//Se instancia el objeto de la clase Quisk y se llama el metodo AddCount

    /* valida una respuesta y crea un nuevo objeto de billetera. */
    $Quisk = new Quisk();
    $responseQuisk = $Quisk->AddAoldCount($UsuarioMandanteSite);

//validamos la respuesta obtenida
    if ($responseQuisk->error == false) {
        $UsuarioBilletera = new UsuarioBilletera();
        $UsuarioBilletera->setBilleteraId(1);
        $UsuarioBilletera->setUsuarioId($UsuarioMandanteSite->usuarioMandante);
        $UsuarioBilletera->setEstado('A');
        $UsuarioBilletera->setUsucreaId($UsuarioMandanteSite->usuarioMandante);
        $UsuarioBilletera->setUsucreaId(0);

        $UsuarioBilleteraMySqlDAO = new UsuarioBilleteraMySqlDAO();
        $UsuarioBilleteraMySqlDAO->insert($UsuarioBilletera);
        $UsuarioBilleteraMySqlDAO->getTransaction()->commit();

        $response = array("code" => 0);

    } else {


        /* Se crea un registro de log con información del usuario y tipo de error. */
        $UsuarioLog = new UsuarioLog();
        $UsuarioLog->setUsuarioId($UsuarioMandanteSite->usuarioMandante);
        $UsuarioLog->setUsuarioIp($json->session->usuarioip);
        $UsuarioLog->setUsuariosolicitaId($UsuarioMandanteSite->usuarioMandante);
        $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
        $UsuarioLog->setTipo("QUISKERROR");

        /* Se configura el estado y valores de un registro de usuario. */
        $UsuarioLog->setEstado('A');
        $UsuarioLog->setValorAntes($responseQuisk->response->code);
        $UsuarioLog->setValorDespues($responseQuisk->response->message);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLog->setImagen('');

        /* Inserta un registro de usuario y maneja excepciones de respuesta específica. */
        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $UsuarioLogMySqlDAO->insert($UsuarioLog);
        $UsuarioLogMySqlDAO->getTransaction()->commit();

        if ($responseQuisk->response->code == 'USER_NOT_FLAGGED_BETTING') {
            throw new Exception("USER_NOT_FLAGGED_BETTING ", '30011');

        }

        /* Genera una excepción con un mensaje que incluye código y mensaje de respuesta Quisk. */
        throw new Exception(" QUISK - " . $responseQuisk->response->code . "(" . $responseQuisk->response->message . ")", '30000');
    }


}










