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
 * RegisteredDocument/SignatureRequest
 *
 * Crear un documento
 *
 * Este recurso permite la creación de un nuevo documento en el sistema. Los parámetros `UsuarioId`, `Id`, `Titulo` y `Ruta`
 * se pasan a través de una solicitud JSON. El servicio AUCOSERVICES se utiliza para crear el documento y retornar los detalles
 * asociados al nuevo documento creado.
 *
 * @param string $UsuarioId : ID del usuario que está creando el documento.
 * @param string $Id : ID del documento que se desea crear.
 * @param string $Titulo : Título del documento que se va a crear.
 * @param string $Ruta : Ruta asociada al documento.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *hasError* (bool): Indica si hubo un error durante el proceso de creación.
 *  - *alertType* (string): Tipo de alerta generada (por ejemplo, "success").
 *  - *Data* (array): Contiene los detalles del documento creado, como:
 *    - *documento_id* (string): ID del documento creado.
 *    - *descripcion* (string): Descripción o título del documento.
 *    - *ruta* (string): Ruta donde se encuentra el documento.
 *
 * Objeto en caso de error:
 *  - *hasError* => true,
 *  - *alertType* => "danger",
 *  - *Data* => array().
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de variables desde parámetros en PHP para uso posterior. */
$UsuarioId = $params->UsuarioId;
//$UsuarioId = "160";
$Id = $params->Id;
//$Id = "645e5ecdca2625fd62e4eaa1";
$Titulo = $params->Titulo;
//$Titulo= "Titulo";
//$Ruta= "AUCO";
$Ruta = $params->Ruta;


/* Se crea un documento utilizando el servicio de autenticación AUCOSERVICES. */
$AUCOSERVICES = new \Backend\integrations\auth\AUCOSERVICES();

$Documentos = $AUCOSERVICES->CreateDocument($UsuarioId, $Id, $Titulo, $Ruta);


$response["hasError"] = false;

/* Se asigna un tipo de alerta y se almacenan datos en la respuesta. */
$response["alertType"] = "success";
$response["Data"] = $Documentos;


?>