<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;

use Backend\dto\DescargaVersion;

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
use Backend\mysql\DescargaMySqlDAO;
use Backend\mysql\DescargaVersionMySqlDAO;
use Backend\mysql\descargaVersionMysqlDao as MysqlDescargaVersionMysqlDao;
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
use Firebase\JWT\Key;

/**
 * RegisteredDocument/AddVersion
 *
 * Este recurso gestiona la creación y actualización de la versión de un documento de descarga. Se registra
 * una nueva versión en la base de datos y se actualiza la información del documento correspondiente con
 * la nueva versión y los detalles de encriptación.
 *
 * @param string $DocumentUrl : URL del documento de la nueva versión.
 * @param int $Id : ID del documento que se está actualizando.
 * @param string $Version : Versión que se está asignando al documento.
 * @param string $EncryptionValue : Valor de encriptación de la nueva versión del documento.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *hasError* (bool): Indica si hubo un error en la operación.
 *  - *alertMessage* (string): Mensaje de alerta que se mostrará (en este caso siempre es "success").
 *  - *alertType* (string): Tipo de alerta, en este caso vacío.
 *
 *
 * Objeto de respuesta en caso de error:
 *  "hasError" => true,
 *  "alertMessage" => "success",
 *  "alertType" => ""
 *
 * @throws Exception Si ocurre un error durante el proceso de inserción o actualización del documento o su versión.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros y usuario en sesión a variables. */
$DocumentUrl = $params->DocumentUrl;
$Id = $params->Id;
$Version = $params->Version;
$EncryptionValue = $params->EncryptionValue;

$idUser = $_SESSION["usuario"];


try {


    /* Crea un objeto `DescargaVersion` y establece sus propiedades correspondientes. */
    $descargaVersion = new DescargaVersion();
    $descargaVersion->setUserId($idUser);
    $descargaVersion->setDocumentoId($Id);
    $descargaVersion->setVersion($Version);
    $descargaVersion->setFechaCrea(date('Y-m-d H:i:s'));
    $descargaVersion->setFechaModif(date('Y-m-d H:i:s'));

    /* Código para configurar y guardar una versión de descarga en una base de datos. */
    $descargaVersion->setUrl($DocumentUrl);
    $descargaVersion->setEncriptacion($EncryptionValue);

    $descargaVersionMysqlDAO = new DescargaVersionMySqlDAO();
    $descargaVersionMysqlDAO->insert($descargaVersion);
    $descargaVersionMysqlDAO->getTransaction()->commit();


    /* crea una descarga, establece versión y actualiza en la base de datos. */
    $descarga = new Descarga($Id);
    $descarga->setVersion($Version);

    $descargaMysqlDAO = new DescargaMySqlDAO();
    $transaction = $descargaMysqlDAO->getTransaction();
    $descargaMysqlDAO->update($descarga);

    /* Se realiza una transacción en MySQL y se prepara una respuesta de éxito. */
    $descargaMysqlDAO->getTransaction()->commit();


    $response["hasError"] = false;
    $response["alertMessage"] = "success";
    $response["alertType"] = "";

} catch (\Exception $e) {
    /* Manejo de excepciones que configura la respuesta de error en un sistema. */

    $response["hasError"] = true;
    $response["alertMessage"] = "success";
    $response["alertType"] = "";
}


?>