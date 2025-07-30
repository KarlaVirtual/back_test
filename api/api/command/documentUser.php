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
 *Registra la firma o rechazo de los documentos que se presentan para aprobación por parte del usuario
 *@param string $json->params->type Tipo de acción a realizar (aceptar o rechazar documento)
 *@param int $json->params->document ID del documento a procesar
 *@param int $json->session->usuario ID del usuario que realiza la acción
 *
 * @return array
 *  - code (int) Código de respuesta
 *  - data (array)
 *    - result (int) Resultado de la operación
 *    - result_text (string) Texto descriptivo del resultado
 */

// Se obtiene el tipo de acción (aceptar o rechazar documento) del JSON
$type = $json->params->type;
$document = $json->params->document;
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

// Se obtiene el ID del usuario mandante
$ClientId = $UsuarioMandante->getUsuarioMandante();
$Transaction = '';

// Se verifica si el tipo de acción es "acceptDocument"
if ($type == "acceptDocument") {
    $Descarga = new Descarga($document->id);

    // Se verifica si el estado de la descarga es "A"
    if ($Descarga->estado == "A") {
        $DocumentoUsuario = new DocumentoUsuario();

        // Se asignan los valores correspondientes a la instancia de DocumentoUsuario
        $DocumentoUsuario->usuarioId = $ClientId;
        $DocumentoUsuario->documentoId = $Descarga->descargaId;
        $DocumentoUsuario->version = $Descarga->version;
        $DocumentoUsuario->estadoAprobacion = "A";

        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
        $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
        $Transaction = $DocumentoUsuarioMySqlDAO->getTransaction();
    }
}

// Se verifica si el tipo de acción es "rejectDocument"
if ($type == "rejectDocument") {
    // Se crea una instancia de Descarga utilizando el ID del documento
    $Descarga = new Descarga($document->id);

    // Se verifica si el estado de la descarga es "A"
    if ($Descarga->estado == "A") {
        $DocumentoUsuario = new DocumentoUsuario();

        // Se asignan los valores correspondientes a la instancia de DocumentoUsuario
        $DocumentoUsuario->usuarioId = $ClientId;
        $DocumentoUsuario->documentoId = $Descarga->descargaId;
        $DocumentoUsuario->version = $Descarga->version;
        $DocumentoUsuario->estadoAprobacion = "R";

        // Se crea una instancia de DocumentoUsuarioMySqlDAO para manejar la base de datos
        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
        $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
        $Transaction = $DocumentoUsuarioMySqlDAO->getTransaction();
    }

}

//Recuperando información del solicitante
$plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
$plaform = str_replace('"',"",$plaform);
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];

//Dejando LOG
$UsuarioLog = new UsuarioLog();
$UsuarioLog->setUsuarioId($UsuarioMandante->getUsuarioMandante());
$UsuarioLog->setUsuarioIp($ip);
$UsuarioLog->setUsuariosolicitaId($UsuarioMandante->getUsuarioMandante());
$UsuarioLog->setUsuariosolicitaIp($ip);
$UsuarioLog->setUsuarioaprobarId(0);
$UsuarioLog->setUsuarioaprobarIp(0);
$UsuarioLog->setTipo('APRUEBADOCUMENTO');
$UsuarioLog->setValorAntes('');
$UsuarioLog->setValorDespues($DocumentoUsuario->getEstadoAprobacion());
$UsuarioLog->setUsucreaId($UsuarioMandante->getUsuarioMandante());
$UsuarioLog->setUsumodifId($UsuarioMandante->getUsuarioMandante());
$UsuarioLog->setEstado('A');
$UsuarioLog->setDispositivo('');
$UsuarioLog->setSoperativo($plaform);
$UsuarioLog->setSversion($DocumentoUsuario->docusuarioId);

$UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);
$UsuarioLogMySqlDAO->insert($UsuarioLog);

$Transaction->commit();

/**
 * Respuesta a la solicitud.
 *
 * @var array $response Array que contiene el código y los datos de respuesta.
 */
$response = array(
    "code" => 0,
    "data" => array(
        "result" => 0,
        "result_text" => null,
        "data" => array(),
    ),
);
