<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajecampanaMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Client/SetClientChats
 *
 * Asignación y procesamiento de mensajes de usuario.
 *
 * Esta función asigna los valores de los parámetros proporcionados, maneja la creación y actualización de mensajes para un usuario, y gestiona la comunicación de estos mensajes a través de un sistema de mensajería.
 * Se realizan varias asignaciones de datos, como la fecha de expiración, los IDs de los usuarios y la información relacionada con el mensaje. Después, el mensaje es insertado o actualizado en la base de datos.
 * Además, si es necesario, se gestionan las actualizaciones de saldo para el usuario a través de WebSocket.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para el procesamiento del mensaje.
 * @param object $params ->Message : Contiene los detalles del mensaje, como el ID del usuario, nombre, tipo, mensaje, fecha y otros.
 * @param string $params ->Message->UserId : ID del usuario destinatario del mensaje.
 * @param string $params ->Message->UserName : Nombre del usuario destinatario.
 * @param string $params ->Message->TypeUser : Tipo de usuario.
 * @param string $params ->Message->Message : Cuerpo del mensaje.
 * @param string $params ->Message->Date : Fecha del mensaje.
 * @param string $params ->Id : ID del mensaje, en caso de que sea necesario actualizarlo.
 * @param string $params ->DateExpiration : Fecha de expiración del mensaje.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío en caso de éxito o contiene errores del modelo.
 *  - *Data* (array): Contiene los mensajes procesados.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "error",
 * "AlertMessage" => "Error en el procesamiento",
 * "ModelErrors" => [],
 * "Data" => [],
 *
 * @throws Exception [La excepción puede ser lanzada en caso de error al insertar o actualizar el mensaje en la base de datos, o al procesar la información del usuario.]
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de variables desde un objeto de parámetros en un sistema de mensajería. */
$UserId = $params->Message->UserId;
$UserName = $params->Message->UserName;
$TypeUser = $params->Message->TyperUser;
$Message = $params->Message->Message;
$Date = $params->Message->Date;
$ParentId = $params->Id;

/* Asigna la fecha de expiración y determina el proveedor basado en sesión. */
$DateExpiration = $params->DateExpiration;


$proveedorId = '0';
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}


/* Convierte fechas y verifica país en la sesión. */
if ($DateExpiration != "") {
    $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $DateExpiration)));
    $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace(".000Z", "", $DateExpiration)));

}

if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}


/* divide una cadena en un array usando comas como delimitadores. */
$clients = explode(",", $UserId);


foreach ($clients as $key => $UserId) {


    if ($UserId != "") {
        try {


            /* Asignación de IDs y creación de objetos relacionados con usuarios. */
            $usutoId = 0;
            if ($UserId != 0) {

                $UsuarioMandante = new UsuarioMandante("", $UserId, $proveedorId);

                $msg = "entro4";

                $UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                $usutoId = $UsuarioMandante->usumandanteId;

            }


            /* asigna cero al ID padre vacío y marca un mensaje como no leído. */
            if ($ParentId == "") {
                $ParentId = 0;
            }

            $UsuarioMensaje2->isRead = 0;

            $UsuarioMensaje = new UsuarioMensaje();

            /* Código asigna valores a propiedades de un objeto `UsuarioMensaje`. */
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = 0;
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $Message;
            $UsuarioMensaje->msubject = '';
            $UsuarioMensaje->parentId = $ParentId;

            /* Código establece propiedades de un objeto $UsuarioMensaje y define un mensaje. */
            $UsuarioMensaje->proveedorId = $proveedorId;
            $UsuarioMensaje->tipo = "MENSAJE";
            $UsuarioMensaje->paisId = '';
            $UsuarioMensaje->fechaCrea = $Date;
            $UsuarioMensaje->usumencampanaId = 0;
            $msg = "entro5";


            /* maneja inserciones y actualizaciones de mensajes de usuario en una base de datos. */
            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            if ($UsuarioMensaje2->usumensaje_id != null) {
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
            }
            $UsuarioMensajeMySqlDAO->getTransaction()->commit();


            /* Código para gestionar usuarios y enviar mensajes via WebSocket para actualizaciones. */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
            //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            //$WebsocketUsuario->sendWSMessage();

            $msg = "entro6";

        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, captura y almacenamiento del mensaje de error. */

            $msg = $e->getMessage();

        }

        /* Inicializa un arreglo para mensajes y establece que no hay errores en la respuesta. */
        $response["data"] = array(
            "messages" => array()
        );
        $response->HasError = false;
    }

}

