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
use Backend\integrations\crm\Optimove;
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
 * Client/SendSms
 *
 * Esta función gestiona la creación y envío de mensajes SMS y actualiza la base de datos con la información relacionada.
 * Se asegura de que se asignen los valores correctos a los objetos y realiza inserciones o actualizaciones de datos según sea necesario.
 * Además, gestiona excepciones y proporciona respuestas detalladas sobre el éxito o error de las operaciones realizadas.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para crear y enviar mensajes. Debe incluir:
 *  - Name (string): Nombre del mensaje.
 *  - Description (string): Descripción del mensaje.
 *  - Message (string): Contenido del mensaje.
 *  - ParentId (int): ID del mensaje principal, si aplica.
 *  - ClientId (string): IDs de los clientes a los que se enviará el mensaje.
 *  - Title (string): Título del mensaje.
 *  - isSms (bool): Determina si el mensaje debe enviarse por SMS.
 *  - Id (int): ID del mensaje a modificar, si aplica.
 *  - DateExpiration (int): Fecha de expiración del mensaje.
 *  - DateFrom (int): Fecha de inicio de la campaña de mensaje.
 *  - CountrySelect (int): ID del país seleccionado para el mensaje.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta (success, error).
 *  - *AlertMessage* (string): Mensaje detallado que se mostrará en la vista.
 *  - *ModelErrors* (array): Errores específicos del modelo, si los hay.
 *  - *Data* (array): Datos relacionados con la operación.
 *
 * Ejemplo de respuesta en caso de éxito:
 *
 * $response["HasError"] = false;
 * $response["AlertType"] = "success";
 * $response["AlertMessage"] = "Mensaje enviado correctamente.";
 * $response["ModelErrors"] = [];
 * $response["Data"] = [];
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "Error al enviar el mensaje.";
 * $response["ModelErrors"] = [];
 * $response["Data"] = [];
 *
 * @throws Exception Si ocurre un error durante la creación, inserción o actualización del mensaje, o en el envío del SMS.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se asigna un ID de proveedor basado en la sesión global y un objeto de usuario. */
$proveedorId = '0';
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}


$UsuarioMensajeSuperior = new UsuarioMensaje();

/* Establece variables a partir de parámetros y define una cadena "##". */
$numerosString = "##";
$cont = 0;

$Name = $params->Name;
$Description = $params->Description;
$Message = $params->Message;

/* Asigna parámetros de entrada a variables para su uso posterior en el código. */
$ParentId = $params->ParentId;
$ClientId = $params->ClientId;
$Title = $params->Title;
$isSMS = $params->isSms;
$Id = $params->Id;
$CountrySelect = $params->CountrySelect;

/* formatea fechas y extrae datos de sesión del usuario. */
$DateExpiration = date('Y-m-d H:i:s', $params->DateExpiration);
$DateFrom = date('Y-m-d H:i:s', $params->DateFrom);
$Mandante = $_SESSION["mandante"];
$usuarioId = $_SESSION["usuario"];
if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}

/* verifica si $ClientId está vacío y lo inicializa a 0 antes de dividirlo. */
if ($ClientId == "") {
    $ClientId = 0;
}
$clients = explode(",", $ClientId);
if (oldCount($clients) >= 0) {


    /* Se asigna un ID de usuario según el valor de $ClientId. */
    switch ($ClientId) {
        case 0:
            $usutoId = 0;
            break;
        default:
            $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);
            $usutoId = $UsuarioMandante->usumandanteId;
            break;
    }

    /* Se crea un nuevo objeto de mensaje para un usuario en campaña. */
    $UsuarioMensajecampana = new UsuarioMensajecampana();
    $UsuarioMensajecampana->usufromId = 0;
    $UsuarioMensajecampana->usutoId = -1;
    $UsuarioMensajecampana->isRead = 0;
    $UsuarioMensajecampana->body = $Message;
    $UsuarioMensajecampana->msubject = "";

    /* Se asignan propiedades a un objeto relacionado con campañas de mensajes SMS. */
    $UsuarioMensajecampana->parentId = 0;
    $UsuarioMensajecampana->proveedorId = $proveedorId;
    $UsuarioMensajecampana->tipo = "SMS";
    $UsuarioMensajecampana->paisId = $CountrySelect;
    $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;
    $UsuarioMensajecampana->usucreaId = $usuarioId;

    /* Asigna valores a propiedades de un objeto relacionado con mensajes de campaña. */
    $UsuarioMensajecampana->usumodifId = $usuarioId;
    $UsuarioMensajecampana->nombre = $Name;
    $UsuarioMensajecampana->descripcion = $Description;
    $UsuarioMensajecampana->mandante = $Mandante;
    $UsuarioMensajecampana->fechaEnvio = $DateFrom;
    $msg = "entro5";


    /* Se inserta un mensaje de campaña y se confirma la transacción en MySQL. */
    $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

    $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
    $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

    $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;


    /* Código para crear un nuevo objeto de mensaje de usuario con atributos iniciales. */
    $UsuarioMensaje = new UsuarioMensaje();
    $UsuarioMensaje->usufromId = 0;
    $UsuarioMensaje->usutoId = -1;
    $UsuarioMensaje->isRead = 0;
    $UsuarioMensaje->body = $Description;
    $UsuarioMensaje->msubject = $Title;

    /* Asignación de valores a propiedades de un objeto `UsuarioMensaje`. */
    $UsuarioMensaje->parentId = 0;
    $UsuarioMensaje->proveedorId = $proveedorId;
    $UsuarioMensaje->tipo = "SMS";
    $UsuarioMensaje->paisId = $CountrySelect;
    $UsuarioMensaje->fechaExpiracion = $DateExpiration;
    $UsuarioMensaje->usumencampanaId = $usumencampanaId;

    /* inserta un mensaje de usuario en una base de datos usando MySQL. */
    $msg = "entro5";

    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


    /* Código PHP que gestiona plantillas de canales y genera respuestas basadas en condiciones. */
    $ParentId = $UsuarioMensaje->usumensajeId;
    if ($ParentId != "") {

        $Optimove = new Optimove();
        //$Token = $Optimove->Login($Mandante,$CountrySelect);

        //$Token = $Token->response;
        $TemplateId = $Optimove->AddChannelTemplates($usumencampanaId, 509, $Name, $Mandante, $CountrySelect);
        // $Token = $Optimove->Login($Mandante,$CountrySelect);


        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg . " - " . $ClientId;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    } else {
        foreach ($clients as $key => $ClientId) {


            if ($ClientId != "") {
                try {

                    /* asigna un ID de usuario si el ClientId es diferente de cero. */
                    $usutoId = 0;
                    if ($ClientId != 0) {
                        $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);
                        $msg = "entro4";

                        //$UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                        $usutoId = $UsuarioMandante->usumandanteId;
                    }


                    /* asigna un valor a `$ParentId` y configura un mensaje SMS. */
                    if ($ParentId == "") {
                        $ParentId = 0;
                    }

                    if ($isSMS) {
                        $UsuarioMensaje->tipo = "SMS";
                        $Registro = new Registro('', $UsuarioMandante->usuarioMandante);
                        $Pais = new Pais($Usuario->paisId);

                        $numerosString = $numerosString . "," . $Pais->prefijoCelular . $Registro->celular;
                    }

                    /* asigna y establece un ID de mensaje externo basado en una condición. */
                    if ($cont == 0) {
                        $UsuarioMensajeSuperior = $UsuarioMensaje;
                        $cont = $cont + 1;
                    } else {
                        $UsuarioMensaje->setExternoId($UsuarioMensajeSuperior->usumensajeId);
                    }


                    /* Se asigna el texto "entro5" a la variable $msg en PHP. */
                    $msg = "entro5";


                    //$UsuarioMensaje2->isRead = 0;

                    if ($Id != '') {

                        /* Creación de un objeto UsuarioMensaje con propiedades específicas asignadas. */
                        $UsuarioMensaje = new UsuarioMensaje($Id);
                        $UsuarioMensaje->usutoId = $usutoId;
                        $UsuarioMensaje->body = $Description;
                        $UsuarioMensaje->msubject = "";
                        $UsuarioMensaje->parentId = $ParentId;
                        $UsuarioMensaje->proveedorId = $proveedorId;

                        /* Se asignan valores a propiedades de $UsuarioMensaje y se define un mensaje. */
                        $UsuarioMensaje->paisId = $CountrySelect;
                        $UsuarioMensaje->fechaExpiracion = $DateExpiration;

                        $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                        $msg = "entro5";


                        /* Actualiza un mensaje del usuario en la base de datos utilizando MySQL. */
                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                        /* if ($UsuarioMensaje2->usumensaje_id != null) {
                             $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                         }*/
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    } else {

                        /* Creación y configuración de un objeto 'UsuarioMensaje' con datos específicos. */
                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $usutoId;
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $Description;
                        $UsuarioMensaje->msubject = "";

                        /* Asignación de propiedades a un objeto UsuarioMensaje en PHP para un mensaje SMS. */
                        $UsuarioMensaje->parentId = $ParentId;
                        $UsuarioMensaje->proveedorId = $proveedorId;
                        $UsuarioMensaje->tipo = "SMS";
                        $UsuarioMensaje->paisId = $CountrySelect;
                        $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                        $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                        /* Código para insertar un mensaje de usuario en una base de datos MySQL. */
                        $msg = "entro5";

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        /* if ($UsuarioMensaje2->usumensaje_id != null) {
                             $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                         }*/
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    }


                    /* Código para gestionar un usuario y enviar un mensaje por WebSocket para actualizar saldo. */
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                    //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    //$WebsocketUsuario->sendWSMessage();

                    $msg = "entro6";

                } catch (Exception $e) {
                    /* captura excepciones y almacena el mensaje en la variable $msg. */

                    $msg = $e->getMessage();

                }

                if ($isSMS) {

                    /* intenta enviar un mensaje y actualizar una base de datos, pero está comentado. */
                    try {
                        // $Okroute = new Okroute();

                        //$numerosString = str_replace("##,", "", $numerosString);


                        #'%0a -> new line'

                        //$Message = trim(preg_replace('/\s\s+/', '%0a', $Message));


                        /*   $Okroute->sendMessageWithNumbers($numerosString, $Message, $UsuarioMensajeSuperior);
                             $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                             $UsuarioMensajeMySqlDAO->update($UsuarioMensajeSuperior);
                             $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                         */
                    } catch (Exception $e) {
                        /* Captura excepciones y almacena el mensaje en la variable $msg. */

                        $msg = $e->getMessage();

                    }
                }

                /* crea una respuesta exitosa con mensajes y datos relacionados. */
                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = $msg . " - " . $ClientId;
                $response["ModelErrors"] = [];

                $response["Data"] = [];

            } else {
                /* Manejo de errores en código, retorna alerta y datos vacíos si hay fallos. */

                $response["HasError"] = true;
                $response["AlertType"] = "error";
                $response["AlertMessage"] = "Datos incorrectos";
                $response["ModelErrors"] = [];

                $response["Data"] = [];
            }
        }

    }


}

