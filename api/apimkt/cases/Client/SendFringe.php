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
 * Client/SendFringe
 *
 * Procesa los parámetros recibidos, decodifica y asigna valores a objetos relacionados con mensajes y campañas.
 *
 * Este recurso decodifica los datos recibidos, reemplaza ciertas entidades HTML y los convierte en objetos de campaña y mensajes.
 * A continuación, asigna estos objetos a las variables correspondientes y los inserta en la base de datos.
 * Además, realiza validaciones y configuraciones basadas en los parámetros recibidos.
 *
 * @param string $params : Datos recibidos a través de `php://input`, que son decodificados y procesados.
 *
 * El objeto `$response` es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error durante el proceso.
 *  - *AlertType* (string): Tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Errores específicos del modelo, en caso de existir.
 *  - *Data* (array): Datos adicionales relacionados con el proceso.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Si ocurre un error durante el proceso de inserción o validación.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/* decodifica datos input y reemplaza entidades HTML específicas por caracteres. */
$params = file_get_contents('php://input');
$params = base64_decode($params);
$params = html_entity_decode($params);

$unwanted_array = array(
    '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
    '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
    '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');

/* convierte una cadena con caracteres no deseados en un objeto JSON. */
$params = strtr($params, $unwanted_array);

$params = json_decode($params);

$ClientId = $params->ClientId;
$Title = $params->Title;

/* reemplaza la cadena 'ó' por 'ó' en nombre y mensaje. */
$Name = $params->Name;
$Description = $params->Description;
$Description = str_replace('ó', 'ó', $Description);

$Message = $params->Message;
$Message = str_replace('ó', 'ó', $Message);

/* Codifica datos y maneja fechas para crear configuraciones basadas en parámetros recibidos. */
$Detalle = json_encode($params->T_Value);
$ParentId = $params->ParentId;
$CountrySelect = $params->CountrySelect;
$Redirection = $params->T_Value->Redirection;
$DateExpiration = date('Y-m-d H:i:s', $params->DateExpiration);
$DateFrom = date('Y-m-d H:i:s', $params->DateFrom);

/* Asigna valores de sesión y parámetros a variables según condiciones específicas. */
$Mandante = $_SESSION["mandante"];
$proveedorId = '0';
$DateProgram = $params->DateProgram;
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}


/* asigna valores a variables y verifica una condición de sesión. */
$Id = $params->Id;
$State = $params->State;

if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}

/* Asigna 0 a $ClientId si está vacío, luego lo convierte en un array. */
if ($ClientId == "") {
    $ClientId = 0;
}
$clients = explode(",", $ClientId);

if ($DateProgram != '' && oldCount($DateProgram) > 0) {
    foreach ($DateProgram as $key => $value) {

        /* Verifica el ClientId y asigna un valor a usutoId basado en su validez. */
        if ($ClientId == "" || $ClientId == "0") {
            $usutoId = 0;
        } else {
            $usutoId = -1;
        }

        $SendDate = date('Y-m-d H:i:s', $value->SendDate);

        /* configura una fecha de expiración y crea un objeto de mensaje de campaña. */
        $DateExpiration = date('Y-m-d H:i:s', $value->DateExpiration);

        $UsuarioMensajecampana = new UsuarioMensajecampana();
        $UsuarioMensajecampana->usufromId = 0;
        $UsuarioMensajecampana->usutoId = $usutoId;
        $UsuarioMensajecampana->isRead = 0;

        /* Se asignan propiedades a un objeto de mensaje para una campaña de usuario. */
        $UsuarioMensajecampana->body = $Message;
        $UsuarioMensajecampana->msubject = "";
        $UsuarioMensajecampana->parentId = 0;
        $UsuarioMensajecampana->proveedorId = $proveedorId;
        $UsuarioMensajecampana->tipo = "STRIPETOP";
        $UsuarioMensajecampana->paisId = $CountrySelect;

        /* Asigna valores a las propiedades de un objeto relacionado con una campaña de usuario. */
        $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;
        $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];
        $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];
        $UsuarioMensajecampana->nombre = $Name;
        $UsuarioMensajecampana->descripcion = $Description;
        $UsuarioMensajecampana->t_value = $Detalle;

        /* Asignación de valores y creación de objeto para manejar campañas de usuario en MySQL. */
        $UsuarioMensajecampana->mandante = $Mandante;
        $UsuarioMensajecampana->fechaEnvio = $SendDate;

        $msg = "entro5";

        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();


        /* inserta un mensaje de campaña y confirma la transacción en MySQL. */
        $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
        $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

        $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;

        if (oldCount($clients) == 0) {


            /* Crea un objeto "UsuarioMensaje" y asigna propiedades a un mensaje. */
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = -1;
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $Description;
            $UsuarioMensaje->msubject = $Title;

            /* Se asignan valores a propiedades de un objeto UsuarioMensaje en PHP. */
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $proveedorId;
            $UsuarioMensaje->tipo = "STRIPETOP";
            $UsuarioMensaje->paisId = $CountrySelect;
            $UsuarioMensaje->fechaExpiracion = $DateExpiration;
            $UsuarioMensaje->usumencampanaId = $usumencampanaId;

            /* Código que inserta un mensaje de usuario en la base de datos y gestiona transacciones. */
            $msg = "entro5";

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

            $UsuarioMensajeMySqlDAO->getTransaction()->commit();


            /* Asignación de la variable $ParentId con el identificador de mensaje del usuario. */
            $ParentId = $UsuarioMensaje->usumensajeId;
        }
        foreach ($clients as $key => $ClientId) {


            if ($ClientId != "" && $ClientId != "0") {
                try {

                    /* Inicializa $usutoId; crea UsuarioMandante si $ClientId es distinto de cero. */
                    $usutoId = 0;
                    if ($ClientId != 0) {
                        $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);
                        $msg = "entro4";

                        //$UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                        $usutoId = $UsuarioMandante->usumandanteId;
                    }


                    /* Asigna 0 a $ParentId si su valor original es una cadena vacía. */
                    if ($ParentId == "") {
                        $ParentId = 0;
                    }

                    if ($Id != '') {

                        /* crea un nuevo objeto UsuarioMensaje con varios atributos asignados. */
                        $UsuarioMensaje = new UsuarioMensaje($Id);
                        $UsuarioMensaje->usutoId = $usutoId;
                        $UsuarioMensaje->body = $Description;
                        $UsuarioMensaje->msubject = $Redirection;
                        $UsuarioMensaje->parentId = $ParentId;
                        $UsuarioMensaje->proveedorId = $proveedorId;

                        /* Asigna valores a un objeto y verifica un estado específico. */
                        $UsuarioMensaje->tipo = "STRIPETOP";
                        $UsuarioMensaje->paisId = $CountrySelect;
                        $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                        $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                        if ($State == 'I') {
                            //$UsuarioMensaje->isRead = 1;
                        } elseif ($State == 'A') {
                            /* Condición para verificar si el estado es 'A', sin ejecutar código comentado. */

                            //$UsuarioMensaje->isRead = 0;
                        }


                        /* Código actualiza un mensaje de usuario en la base de datos con transacciones. */
                        $msg = "entro5";

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                        /*if ($UsuarioMensaje2->usumensaje_id != null) {
                            $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                        }*/
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    } else {

                        /* Creación de un objeto 'UsuarioMensaje' con propiedades inicializadas para un mensaje. */
                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $usutoId;
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $Description;
                        $UsuarioMensaje->msubject = $Redirection;

                        /* Asignación de propiedades a un objeto de mensaje de usuario en PHP. */
                        $UsuarioMensaje->parentId = $ParentId;
                        $UsuarioMensaje->proveedorId = $proveedorId;
                        $UsuarioMensaje->tipo = "STRIPETOP";
                        $UsuarioMensaje->paisId = $CountrySelect;
                        $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                        $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                        /* Código para insertar un mensaje de usuario en una base de datos MySQL. */
                        $msg = "entro5";

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        /*if ($UsuarioMensaje2->usumensaje_id != null) {
                            $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                        }*/
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    }

                    // $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                    //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    //$WebsocketUsuario->sendWSMessage();


                    /* Se define una variable `$msg` que almacena el texto "entro6". */
                    $msg = "entro6";


                } catch (Exception $e) {
                    /* Manejo de excepciones en PHP, capturando errores y almacenando el mensaje. */

                    $msg = $e->getMessage();

                }

                /* Código para estructurar una respuesta exitosa en formato de array. */
                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = $msg . " - " . $ClientId;
                $response["ModelErrors"] = [];

                $response["Data"] = [];

            } else {
                /* gestiona un error y prepara una respuesta informativa. */

                $response["HasError"] = true;
                $response["AlertType"] = "error";
                $response["AlertMessage"] = "Datos incorrectos";
                $response["ModelErrors"] = [];

                $response["Data"] = [];
            }
        }
    }
} else {
    if (oldCount($clients) >= 0) {


        /* asigna un valor a $usutoId según el estado de $ClientId. */
        if ($ClientId == "" || $ClientId == "0") {
            $usutoId = 0;
        } else {
            $usutoId = -1;
        }

        $UsuarioMensajecampana = new UsuarioMensajecampana();

        /* Se asignan valores a propiedades de un objeto relacionado con mensajes de campaña. */
        $UsuarioMensajecampana->usufromId = 0;
        $UsuarioMensajecampana->usutoId = $usutoId;
        $UsuarioMensajecampana->isRead = 0;
        $UsuarioMensajecampana->body = $Message;
        $UsuarioMensajecampana->msubject = "";
        $UsuarioMensajecampana->parentId = 0;

        /* Asigna valores a propiedades de un objeto relacionado con la campaña de mensajes. */
        $UsuarioMensajecampana->proveedorId = $proveedorId;
        $UsuarioMensajecampana->tipo = "STRIPETOP";
        $UsuarioMensajecampana->paisId = $CountrySelect;
        $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;
        $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];
        $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];

        /* Asignación de propiedades a un objeto y mensaje de depuración. */
        $UsuarioMensajecampana->nombre = $Name;
        $UsuarioMensajecampana->descripcion = $Description;
        $UsuarioMensajecampana->t_value = $Detalle;
        $UsuarioMensajecampana->mandante = $Mandante;
        $UsuarioMensajecampana->fechaEnvio = $DateFrom;

        $msg = "entro5";


        /* Se inserta un mensaje de campaña y se confirma la transacción en MySQL. */
        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

        $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
        $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

        $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;


        /* Se crea un objeto UsuarioMensaje con propiedades específicas para su uso. */
        $UsuarioMensaje = new UsuarioMensaje();
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = -1;
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $Description;
        $UsuarioMensaje->msubject = $Title;

        /* Se asignan propiedades a un objeto de mensaje de usuario en PHP. */
        $UsuarioMensaje->parentId = 0;
        $UsuarioMensaje->proveedorId = $proveedorId;
        $UsuarioMensaje->tipo = "STRIPETOP";
        $UsuarioMensaje->paisId = $CountrySelect;
        $UsuarioMensaje->fechaExpiracion = $DateExpiration;
        $UsuarioMensaje->usumencampanaId = $usumencampanaId;

        /* Inserta un mensaje de usuario en la base de datos y gestiona la transacción. */
        $msg = "entro5";

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

        $UsuarioMensajeMySqlDAO->getTransaction()->commit();


        /* Asigna el ID del mensaje de usuario a la variable $ParentId. */
        $ParentId = $UsuarioMensaje->usumensajeId;
    }
    foreach ($clients as $key => $ClientId) {


        if ($ClientId != "" && $ClientId != "0") {
            try {

                /* Inicializa `$usutoId` y asigna su valor basado en `$ClientId`. */
                $usutoId = 0;
                if ($ClientId != 0) {
                    $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);
                    $msg = "entro4";

                    //$UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                    $usutoId = $UsuarioMandante->usumandanteId;
                }


                /* Asigna 0 a $ParentId si está vacío. */
                if ($ParentId == "") {
                    $ParentId = 0;
                }

                if ($Id != '') {

                    /* Crea un objeto UsuarioMensaje con información específica del usuario y mensaje. */
                    $UsuarioMensaje = new UsuarioMensaje($Id);
                    $UsuarioMensaje->usutoId = $usutoId;
                    $UsuarioMensaje->body = $Description;
                    $UsuarioMensaje->msubject = $Redirection;
                    $UsuarioMensaje->parentId = $ParentId;
                    $UsuarioMensaje->proveedorId = $proveedorId;

                    /* Establece propiedades de un objeto según variables específicas y condiciones. */
                    $UsuarioMensaje->tipo = "STRIPETOP";
                    $UsuarioMensaje->paisId = $CountrySelect;
                    $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                    $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                    if ($State == 'I') {
                        //$UsuarioMensaje->isRead = 1;
                    } elseif ($State == 'A') {
                        /* Condición que verifica si el estado es 'A' y comenta una línea de código. */

                        //$UsuarioMensaje->isRead = 0;
                    }


                    /* Actualiza datos de usuario y gestiona transacciones en la base de datos. */
                    $msg = "entro5";

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    /*if ($UsuarioMensaje2->usumensaje_id != null) {
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                    }*/
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                } else {

                    /* Crea un nuevo objeto UsuarioMensaje con propiedades definidas para enviar un mensaje. */
                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $usutoId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $Description;
                    $UsuarioMensaje->msubject = $Redirection;

                    /* Se asignan valores a propiedades de un objeto UsuarioMensaje en PHP. */
                    $UsuarioMensaje->parentId = $ParentId;
                    $UsuarioMensaje->proveedorId = $proveedorId;
                    $UsuarioMensaje->tipo = "STRIPETOP";
                    $UsuarioMensaje->paisId = $CountrySelect;
                    $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                    $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                    /* Código para insertar un mensaje en la base de datos usando MySQL. */
                    $msg = "entro5";

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    /*if ($UsuarioMensaje2->usumensaje_id != null) {
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                    }*/
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                }


                /* Se crea un objeto Usuario y se prepara un mensaje para el WebSocket. */
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

            /* crea una respuesta JSON sin errores, con mensaje y datos vacíos. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = $msg . " - " . $ClientId;
            $response["ModelErrors"] = [];

            $response["Data"] = [];

        } else {
            /* Maneja un error asignando mensajes y datos a una respuesta JSON. */

            $response["HasError"] = true;
            $response["AlertType"] = "error";
            $response["AlertMessage"] = "Datos incorrectos";
            $response["ModelErrors"] = [];

            $response["Data"] = [];
        }
    }

}

