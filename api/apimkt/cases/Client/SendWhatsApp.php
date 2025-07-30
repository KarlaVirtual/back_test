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
use Backend\integrations\mensajeria\Message;
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
 * Client/SendWhatsApp
 *
 * Este recurso maneja el envío de mensajes de campaña a los usuarios, creando primero los objetos correspondientes para los mensajes en la base de datos, y luego enviando estos mensajes a través de WhatsApp.
 * La función recibe varios parámetros para configurar el mensaje, como el título, el cuerpo del mensaje, y la información del cliente. Luego, se realiza una serie de validaciones y asignaciones de valores a las variables locales.
 * Después, se crea un objeto de mensaje en la base de datos para cada cliente, asignando la información proporcionada, y se realiza la inserción o actualización de los mensajes en la base de datos.
 * En caso de que el mensaje deba enviarse a través de WhatsApp, la función también gestiona el envío del mensaje a través de un servicio de WhatsApp.
 * Si ocurre algún error durante el proceso, la función captura la excepción y genera una respuesta de error para el usuario.
 *
 * @param object $params : Objeto que contiene los parámetros que se asignarán a las variables locales.
 * @param string $params->ClientId : ID del cliente que se usará para determinar el destinatario del mensaje.
 * @param string $params->Message : Mensaje que se enviará en la campaña.
 * @param string $params->Title : Título del mensaje.
 * @param string $params->Name : Nombre asociado al mensaje.
 * @param string $params->Description : Descripción detallada del mensaje.
 * @param string $params->ParentId : ID del mensaje principal, utilizado para los mensajes en una campaña.
 * @param string $params->CountrySelect : País seleccionado para el envío del mensaje.
 * @param string $params->DateExpiration : Fecha de expiración del mensaje.
 * @param string $params->DateFrom : Fecha de inicio para el envío del mensaje.
 * @param string $params->Id : ID del mensaje en caso de actualización.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío en caso de éxito o contiene errores del modelo.
 *  - *Data* (array): Retorna datos adicionales, si los hubiera.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "error",
 * "AlertMessage" => "Datos incorrectos",
 * "ModelErrors" => [],
 * "Data" => [],
 *
 * @throws Exception [El motivo de la excepción lanzada puede ser un error durante la inserción de datos en la base de datos, la creación del mensaje o el envío del mensaje a través de WhatsApp.]
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* Asigna valores de parámetros a variables en un script de programación. */
$ClientId = $params->ClientId;
$Message = $params->Message;
$Title = $params->Title;
$Name = $params->Name;
$Description = $params->Description;
$ParentId = $params->ParentId;

/* asigna valores de parámetros a variables y define un proveedor por defecto. */
$CountrySelect = $params->CountrySelect;
$DateExpiration = date('Y-m-d H:i:s', $params->DateExpiration);
$DateFrom = date('Y-m-d H:i:s', $params->DateFrom);
$Mandante = $_SESSION["mandante"];
$Id = $params->Id;

$proveedorId = '0';

/* asigna valores de sesión según condiciones específicas sobre globalidad y país. */
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}


if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}

/* Asigna 0 a $ClientId si está vacío y transforma la cadena en un array. */
if ($ClientId == "") {
    $ClientId = 0;
}
$clients = explode(",", $ClientId);
if (oldCount($clients) >= 0) {


    /* asigna `usutoId` según el valor de `$ClientId`. */
    switch ($ClientId) {
        case 0:
            $usutoId = 0;
            break;
        default:
            $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);
            $usutoId = $UsuarioMandante->usumandanteId;
            break;
    }

    /* Crea un objeto de mensaje para un usuario en una campaña de comunicación. */
    $UsuarioMensajecampana = new UsuarioMensajecampana();
    $UsuarioMensajecampana->usufromId = 0;
    $UsuarioMensajecampana->usutoId = -1;
    $UsuarioMensajecampana->isRead = 0;
    $UsuarioMensajecampana->body = $Message;
    $UsuarioMensajecampana->msubject = "";

    /* Se está configurando un objeto de mensaje para una campaña de WhatsApp. */
    $UsuarioMensajecampana->parentId = 0;
    $UsuarioMensajecampana->proveedorId = $proveedorId;
    $UsuarioMensajecampana->tipo = "WHATSAPP";
    $UsuarioMensajecampana->paisId = $CountrySelect;
    $UsuarioMensajecampana->usucreaId = $usutoId;
    $UsuarioMensajecampana->usumodifId = $usutoId;

    /* Se asignan valores a un objeto y se crea un DAO para interactuar con MySQL. */
    $UsuarioMensajecampana->nombre = $Name;
    $UsuarioMensajecampana->descripcion = $Description;
    $UsuarioMensajecampana->mandante = $Mandante;
    $UsuarioMensajecampana->fechaEnvio = $DateFrom;
    $msg = "entro5";

    $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();


    /* Inserta un mensaje de usuario y confirma la transacción en MySQL. */
    $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
    $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

    $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;

    $UsuarioMensaje = new UsuarioMensaje();

    /* Inicializa propiedades de un objeto UsuarioMensaje, configurando IDs y contenido del mensaje. */
    $UsuarioMensaje->usufromId = 0;
    $UsuarioMensaje->usutoId = -1;
    $UsuarioMensaje->isRead = 0;
    $UsuarioMensaje->body = $Description;
    $UsuarioMensaje->msubject = $Title;
    $UsuarioMensaje->parentId = 0;

    /* Se asignan valores a un objeto y se inicializa un DAO para manejar mensajes. */
    $UsuarioMensaje->proveedorId = $proveedorId;
    $UsuarioMensaje->tipo = "WHATSAPP";
    $UsuarioMensaje->paisId = $CountrySelect;
    $UsuarioMensaje->usumencampanaId = $usumencampanaId;
    $msg = "entro5";

    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

    /* Código inserta un mensaje en la base de datos y confirma la transacción. */
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

    $ParentId = $UsuarioMensaje->usumensajeId;
}
foreach ($clients as $key => $ClientId) {


    if ($ClientId != "") {
        try {

            /* asigna un ID de usuario basado en un cliente si no es cero. */
            $usutoId = 0;
            if ($ClientId != 0) {
                $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);
                $msg = "entro4";

                //$UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                $usutoId = $UsuarioMandante->usumandanteId;
            }


            /* asigna 0 a $ParentId si está vacío. */
            if ($ParentId == "") {
                $ParentId = 0;
            }


            //$UsuarioMensaje2->isRead = 0;

            if ($Id != '') {

                /* Se crea un objeto UsuarioMensaje con propiedades asignadas para almacenar un mensaje. */
                $UsuarioMensaje = new UsuarioMensaje($Id);
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->body = $Description;
                $UsuarioMensaje->msubject = "";
                $UsuarioMensaje->parentId = $ParentId;
                $UsuarioMensaje->proveedorId = $proveedorId;

                /* Se asignan valores a un objeto y se inicializa una conexión a la base de datos. */
                $UsuarioMensaje->tipo = "WHATSAPP";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                $msg = "entro5";

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

                /* Actualiza el mensaje del usuario en la base de datos utilizando DAO. */
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                /* if ($UsuarioMensaje2->usumensaje_id != null) {
                     $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                 }*/
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

            } else {

                /* Se crea un nuevo mensaje de usuario con propiedades inicializadas. */
                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $Description;
                $UsuarioMensaje->msubject = "";

                /* Asignación de propiedades a un objeto de mensaje de usuario en WhatsApp. */
                $UsuarioMensaje->parentId = $ParentId;
                $UsuarioMensaje->proveedorId = $proveedorId;
                $UsuarioMensaje->tipo = "WHATSAPP";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->usumencampanaId = $usumencampanaId;
                $msg = "entro5";


                /* Código para insertar un mensaje de usuario en una base de datos MySQL. */
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                /* if ($UsuarioMensaje2->usumensaje_id != null) {
                     $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                 }*/
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

            }


            /* Código para manejar un usuario y enviar mensajes por WebSocket. */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
            //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            //$WebsocketUsuario->sendWSMessage();

            $Messages = new Message();


            /* Envía un mensaje por WhatsApp y actualiza la información del usuario en la base de datos. */
            $responseMessage = $Messages->SendWhatsAppMessage($UsuarioMandante, $Message);

            $UsuarioMensaje->setValor1($responseMessage);

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);

            /* realiza un commit de una transacción en MySQL y asigna un mensaje. */
            $UsuarioMensajeMySqlDAO->getTransaction()->commit();


            $msg = "entro6";

        } catch (Exception $e) {
            /* Captura excepciones en PHP y almacena el mensaje en la variable $msg. */

            $msg = $e->getMessage();

        }

        /* Se estructura una respuesta sin errores, con mensaje y sin datos del modelo. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg . " - " . $ClientId;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

    } else {
        /* maneja un error y genera un mensaje de alerta. */

        $response["HasError"] = true;
        $response["AlertType"] = "error";
        $response["AlertMessage"] = "Datos incorrectos";
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    }
}
