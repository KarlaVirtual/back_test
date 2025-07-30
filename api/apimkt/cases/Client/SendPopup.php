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
 * Client/SendPopup
 *
 * Envia un mensaje tipo Popup al usuario
 *
 * @param int $ClientId : Id del cliente
 * @param string $Message  : Cuerpo del mensaje
 * @param string $Title  : Titulo del mensaje
 * @param string $Name : Nombre del usuario quien lo crea
 * @param string $Description : Descripcion del mensaje
 * @param int $ParentId : Id del popup
 * @param string $Detalle : Detalle del popup
 * @param int $CountrySelect : Pais para el despliegue del popup
 * @param string $Redirection : URL a redireccionar
 * @param string $Product : Poducto vinculado al popup
 * @param string $Frame : Parte de la Url a redireccionar
 * @param bool $IsGame : Si es para Juego o no
 * @param string $DateExpiration : Expiracion del popup
 * @param string $DateFrom : Fecha de envio del popup
 * @param int $Id : Id del Ususario vinculado al popup
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors*  (array): retorna array vacio
 * - *Data* (array): vacio.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "Datos incorrectos";
 * $response["ModelErrors"] = [];
 * $response["Data"] = [];
 *
 * @throws no No contiene manejo de exepciones
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* decodifica y convierte caracteres HTML en texto legible. */
$params = file_get_contents('php://input');
$params = base64_decode($params);
$params = html_entity_decode($params);

$unwanted_array = array(
    '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
    '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
    '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');

/* reemplaza caracteres no deseados y procesa un mensaje JSON. */
$params = strtr($params, $unwanted_array);

$params = json_decode($params);
$ClientId = $params->ClientId;
$Message = $params->Message;
$Message = str_replace("'", "\'", $Message);

/* limpia, procesa y extrae contenido de un mensaje HTML. */
$Message = trim(preg_replace('/\s+/', ' ', $Message));
$Message = preg_replace("/(\r\n|\n|\r|\t)/i", '', $Message);
$Message = explode('</head>', $Message)[1];
$Message = str_replace('</html>', "", $Message);
$Title = $params->Title;
$Name = $params->Name;

/* asigna valores de parámetros a variables para su posterior uso. */
$Description = $params->Description;
$ParentId = $params->ParentId;
$Detalle = json_encode($params->T_Value);
$CountrySelect = $params->CountrySelect;
$Redirection = $params->T_Value->Redirection;
$Product = $params->T_Value->Product;

/* asigna valores de parámetros y formatea fechas para su uso posterior. */
$Frame = $params->T_Value->Frame;
$IsGame = $params->T_Value->IsGame;
$DateExpiration = date('Y-m-d H:i:s', $params->DateExpiration);
$DateFrom = date('Y-m-d H:i:s', $params->DateFrom);
$Mandante = $_SESSION["mandante"];
$Id = $params->Id;


/* asigna valores a variables según condiciones de sesión específicas. */
$proveedorId = '0';
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}

if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}

/* Asigna 0 a ClientId si está vacío; divide ClientId en un array. */
if ($ClientId == "") {
    $ClientId = 0;
}
$clients = explode(",", $ClientId);
if (oldCount($clients) >= 0) {


    /* asigna un valor a $usutoId basado en condiciones del cliente y bonus. */
    if ($ClientId == "" || $ClientId == "0") {
        $usutoId = 0;
    } else {
        $usutoId = -1;
    }
    if ($params->IsBonus == 1) {
        $usutoId = -1;
    }


    /* Se inicializa un objeto para manejar mensajes de campaña de usuario. */
    $UsuarioMensajecampana = new UsuarioMensajecampana();
    $UsuarioMensajecampana->usufromId = 0;
    $UsuarioMensajecampana->usutoId = $usutoId;
    $UsuarioMensajecampana->isRead = 0;
    $UsuarioMensajecampana->body = $Message;
    $UsuarioMensajecampana->msubject = "";

    /* Asignación de propiedades a un objeto relacionado con mensajes de campaña publicitaria. */
    $UsuarioMensajecampana->parentId = 0;
    $UsuarioMensajecampana->proveedorId = $proveedorId;
    $UsuarioMensajecampana->tipo = "BANNERINV";
    $UsuarioMensajecampana->paisId = $CountrySelect;
    $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;
    $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];

    /* Asigna valores a las propiedades de un objeto UsuarioMensajecampana. */
    $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];;
    $UsuarioMensajecampana->nombre = $Name;
    $UsuarioMensajecampana->descripcion = $Description;
    $UsuarioMensajecampana->t_value = $Detalle;
    $UsuarioMensajecampana->mandante = $Mandante;
    $UsuarioMensajecampana->fechaEnvio = $DateFrom;

    /* inserta un mensaje en la base de datos usando un DAO. */
    $msg = "entro5";

    $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

    $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
    $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


    /* Asignación del ID de campaña a la variable $usumencampanaId desde el objeto $UsuarioMensajecampana. */
    $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;

    if ($params->IsBonus != 1) {


        /* Crea un nuevo objeto UsuarioMensaje y asigna valores a sus propiedades. */
        $UsuarioMensaje = new UsuarioMensaje();
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = -1;
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $Message;
        $UsuarioMensaje->msubject = $Title;

        /* Se asignan propiedades a un objeto UsuarioMensaje con datos específicos. */
        $UsuarioMensaje->parentId = 0;
        $UsuarioMensaje->proveedorId = $proveedorId;
        $UsuarioMensaje->tipo = "BANNERINV";
        $UsuarioMensaje->paisId = $CountrySelect;
        $UsuarioMensaje->fechaExpiracion = $DateExpiration;
        $UsuarioMensaje->usumencampanaId = $usumencampanaId;

        /* inserta un mensaje de usuario y gestiona transacciones en MySQL. */
        $msg = "entro5";

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

        $UsuarioMensajeMySqlDAO->getTransaction()->commit();


        /* Actualiza un mensaje de usuario en la base de datos y confirma la transacción. */
        $UsuarioMensajecampana->usumensajeId = $UsuarioMensaje->usumensajeId;

        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
        $UsuarioMensajecampanaMySqlDAO->update($UsuarioMensajecampana);
        $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


        $ParentId = $UsuarioMensaje->usumensajeId;
    }

}

/* Código que define redirecciones según el producto seleccionado en una sesión de juego. */
$Redirection2 = $Frame . '##URL##' . $Redirection;
if ($IsGame == true) {
    if ($Product != '') {
        if ($_SESSION['Global'] == "S") {

            $Frame = 'GAME' . $Product;
        } else {
            $Product = new ProductoMandante('', '', $Product);
            $Frame = 'GAME' . $Product->productoId;
        }
        $Redirection2 = $Frame . '##URL##' . $Redirection;

    } else {

    }
}


foreach ($clients as $key => $ClientId) {


    if ($ClientId != "" && $ClientId != "0") {
        try {

            /* Asigna un ID de usuario basado en un cliente existente. */
            $usutoId = 0;
            if ($ClientId != 0) {
                $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);
                $msg = "entro4";

                //$UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                $usutoId = $UsuarioMandante->usumandanteId;
            }


            /* Asigna 0 a $ParentId si está vacío o no definido. */
            if ($ParentId == "") {
                $ParentId = 0;
            }


            //$UsuarioMensaje2->isRead = 0;

            if ($Id != '') {

                /* Creación de un objeto UsuarioMensaje con propiedades específicas para un mensaje. */
                $UsuarioMensaje = new UsuarioMensaje($Id);
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Redirection2;
                $UsuarioMensaje->parentId = $ParentId;
                $UsuarioMensaje->proveedorId = $proveedorId;

                /* Asignación de propiedades a un objeto para configurar un mensaje de usuario. */
                $UsuarioMensaje->tipo = "BANNERINV";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                $msg = "entro5";


                /* Actualiza un mensaje de usuario en la base de datos utilizando MySQL. */
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                /* if ($UsuarioMensaje2->usumensaje_id != null) {
                     $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                 }*/
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

            } else {

                /* Se crea un nuevo objeto UsuarioMensaje con propiedades asignadas para un mensaje. */
                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Redirection2;

                /* Código asigna valores a propiedades de un objeto UsuarioMensaje para un mensaje específico. */
                $UsuarioMensaje->parentId = $ParentId;
                $UsuarioMensaje->proveedorId = $proveedorId;
                $UsuarioMensaje->tipo = "BANNERINV";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                /* Inserta un mensaje de usuario en la base de datos y gestiona la transacción. */
                $msg = "entro5";

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                /* if ($UsuarioMensaje2->usumensaje_id != null) {
                     $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                 }*/
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

            }


            /* Código para gestionar la actualización de saldo mediante WebSocket y usuarios. */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
            //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            //$WebsocketUsuario->sendWSMessage();

            $msg = "entro6";

        } catch (Exception $e) {
            /* Maneja excepciones en PHP y captura el mensaje de error si ocurre una. */

            $msg = $e->getMessage();

        }

        /* construye una respuesta JSON exitosa con mensaje y datos vacíos. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg . " - " . $ClientId;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

    } else {
        /* maneja un error al validar datos, generando una respuesta de error. */

        $response["HasError"] = true;
        $response["AlertType"] = "error";
        $response["AlertMessage"] = "Datos incorrectos";
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    }
}
