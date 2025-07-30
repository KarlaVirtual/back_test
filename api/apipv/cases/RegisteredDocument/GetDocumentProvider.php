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
 * RegisteredDocument/GetDocumentProvider
 *
 * GetDocumentProvider
 *
 * Este recurso se encarga de obtener documentos asociados a un usuario a través de un servicio de autenticación externo.
 * Los documentos se recuperan utilizando el servicio AUCOSERVICES, que toma como parámetros el ID de usuario,
 * el mandante y el ID de país. Los resultados se devuelven en un formato estructurado con información sobre los documentos.
 *
 * @param string $userId : ID del usuario para obtener los documentos asociados a dicho usuario.
 * @param string $mandante : ID del mandante asociado al usuario.
 * @param string $paisId : ID del país asociado al usuario.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *hasError* (bool): Indica si hubo un error en la operación.
 *  - *alertType* (string): Tipo de alerta que se mostrará (en este caso "success").
 *  - *Data* (array): Contiene los documentos obtenidos a través del servicio AUCOSERVICES. Los documentos incluyen:
 *    - [Estructura de datos de los documentos, dependiendo de la respuesta del servicio].
 *
 *
 * Objeto de respuesta en caso de error:
 *  "hasError" => true,
 *  "alertType" => "danger",
 *  "Data" => array().
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se instancia AUCOSERVICES y se obtienen documentos según el usuario, mandante y país. */
$AUCOSERVICES = new \Backend\integrations\auth\AUCOSERVICES();
$userId = $_SESSION['usuario'];
$mandante = $_SESSION['mandante'];
$paisId = $_SESSION['pais_id'];

$Documentos = $AUCOSERVICES->GetDocuments($userId, $mandante, $paisId);


/* Código que establece una respuesta sin errores, con éxito y datos de documentos. */
$response["hasError"] = false;
$response["alertType"] = "success";
$response["Data"] = $Documentos;


?>