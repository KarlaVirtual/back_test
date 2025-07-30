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
 * Client/SendInvasiveBanner
 *
 * Envia banner invasivo a los clientes
 *
 * @param int $ClientId : Id del cliente
 * @param int $ClientIdCsv : Ids de los clientes en CSv
 * @param int $Message : Mensaje a enviar
 * @param int $Title : Titulo del banner
 * @param int $Name : Nombre del usuario quien lo crea
 * @param int $Description : Descripcion del banner
 * @param int $Detalle : Detalle del banner
 * @param int $ParentId : Id del banner
 * @param int $CountrySelect : Pais para el despliegue del banner
 * @param int $DateExpiration : Expiracion del banner
 * @param int $Image : Imagen a mostrar
 * @param int $Redirection : URL a redireccionar
 * @param int $Targe : Detalle del banner
 * @param int $ButtonText : Detalle del banner
 * @param int $DateFrom : Fecha de envio del banner
 * @param int $DateProgram : Fecha de envio del banner programada
 * @param int $Id : Id del Ususario vinculado al banner
 *
 *
 * El objeto `$response` es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error durante el proceso.
 * - *AlertType* (string): Tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Errores específicos del modelo, en caso de existir.
 * - *Data* (array): Datos adicionales relacionados con el proceso.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* decodifica datos de entrada y reemplaza entidades HTML por caracteres correspondientes. */
$params = file_get_contents('php://input');
$params = base64_decode($params);
$params = html_entity_decode($params);

$unwanted_array = array(
    '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
    '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
    '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');

/* Transforma y decodifica parámetros JSON, extrayendo ClientId, ClientIdCsv y Message. */
$params = strtr($params, $unwanted_array);

$params = json_decode($params);
$ClientId = $params->ClientId;
$ClientIdCsv = $params->ClientIdCsv;
$Message = $params->Message;

/* limpia y formatea un mensaje, eliminando caracteres no deseados y espacios. */
$Message = str_replace("'", "\'", $Message);
$Message = trim(preg_replace('/\s+/', ' ', $Message));
$Message = preg_replace("/(\r\n|\n|\r|\t)/i", '', $Message);
$Message = explode('</head>', $Message)[1];
$Message = str_replace('</html>', "", $Message);
$Message = preg_replace('/[\xE2\x80\xAF]/', '', $Message);

/* asigna valores y corrige caracteres en una descripción. */
$Title = $params->Title;
$Name = $params->Name;
$Description = $params->Description;
$Description = str_replace('ó', 'ó', $Description);

$Detalle = json_encode($params->T_Value);

/* asigna valores de parámetros a variables para su uso posterior. */
$ParentId = $params->ParentId;
$CountrySelect = $params->CountrySelect;
$DateExpiration = $params->DateExpiration;
$Image = $params->T_Value->Image;
$Redirection = $params->T_Value->Redirection;
$Targe = $params->T_Value->targe;

/* Asignación de valores a variables a partir de parámetros y sesión en PHP. */
$ButtonText = $params->T_Value->ButtonText;
$DateExpiration = date('Y-m-d H:i:s', $params->DateExpiration);
$DateFrom = date('Y-m-d H:i:s', $params->DateFrom);
$Mandante = $_SESSION["mandante"];
$proveedorId = '0';
$DateProgram = $params->DateProgram;


/* Valida condiciones de sesión para asignar valores a variables en PHP. */
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}

$Id = $params->Id;

if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}

/* valida y procesa identificadores de clientes desde variables o CSV. */
if ($ClientId == "" && empty($ClientIdCsv)) {
    $ClientId = 0;
}

$clients = $ClientId != "" ? explode(",", $ClientId) : [];

/** Validando si hay usuarios por CSV */
if (count($clients) == 0) {
    $clientsCsv = explode("base64,", $ClientIdCsv);
    $clientsCsv = $clientsCsv[1];
    $clientsCsv = base64_decode($clientsCsv);
    $clientsCsv = preg_replace("#(\r\n|\n){1}#", ",", $clientsCsv);
    $clientsCsv = explode(',', $clientsCsv);
    $clientsCsv = array_filter($clientsCsv);
    $clientsCsv = array_values($clientsCsv);

    if (count($clientsCsv) > 0) {
        $clients = $clientsCsv;
    }
}


/* verifica condiciones y crea un array con fechas formateadas. */
if ($_ENV['debug']) {
    print_r($params);
}

if ($DateProgram == '' || oldCount($DateProgram) == 0) {
    $DateProgram = array();

    $DateProgramTemp = array(
        'SendDate' => date('Y-m-d H:i:s', $params->DateFrom),
        'DateExpiration' => $params->DateExpiration
    );


    array_push($DateProgram, $DateProgramTemp);

    $DateProgram = json_decode(json_encode($DateProgram));

}

foreach ($DateProgram as $value) {


    /* convierte fechas a formato 'Y-m-d H:i:s' desde objetos. */
    $DateFrom = date('Y-m-d H:i:s', $value->SendDate);
    $DateExpiration = date('Y-m-d H:i:s', $value->DateExpiration);

    if (oldCount($clients) >= 0) {


        /* asigna valores a `$usutoId` según condiciones del cliente. */
        if (($ClientId == "" || $ClientId == "0") && empty($ClientIdCsv)) {
            $usutoId = 0;
        } else {
            $usutoId = -1;
        }
        if ($clients[0] == '0') {
            $usutoId = '0';
        }


        /* Se instancia un objeto para gestionar mensajes de campaña de usuarios. */
        $UsuarioMensajecampana = new UsuarioMensajecampana();
        $UsuarioMensajecampana->usufromId = 0;
        $UsuarioMensajecampana->usutoId = $usutoId;
        $UsuarioMensajecampana->isRead = 0;
        $UsuarioMensajecampana->body = ($Message);
        $UsuarioMensajecampana->msubject = "";

        /* Código que asigna propiedades a un objeto de mensaje de campaña para un usuario. */
        $UsuarioMensajecampana->parentId = 0;
        $UsuarioMensajecampana->proveedorId = $proveedorId;
        $UsuarioMensajecampana->tipo = "MESSAGEINV";
        $UsuarioMensajecampana->paisId = $CountrySelect;
        $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;
        $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];

        /* Asigna valores a las propiedades de un objeto relacionado con mensajes de campaña. */
        $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];;
        $UsuarioMensajecampana->nombre = $Name;
        $UsuarioMensajecampana->descripcion = $Description;
        $UsuarioMensajecampana->t_value = $Detalle;
        $UsuarioMensajecampana->mandante = $Mandante;
        $UsuarioMensajecampana->fechaEnvio = $DateFrom;

        /* Insertar un mensaje en la base de datos usando DAO en PHP. */
        $msg = "entro5";

        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

        $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
        $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


        /* asigna IDs a un objeto de mensaje basado en condiciones específicas. */
        $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;

        $UsuarioMensaje = new UsuarioMensaje();
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = -1;
        if ($clients[0] == '0') {
            $UsuarioMensaje->usutoId = '0';
        }

        /* Se configuran propiedades de un objeto UsuarioMensaje para un mensaje no leído. */
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $Message;
        $UsuarioMensaje->msubject = $Title;
        $UsuarioMensaje->parentId = 0;
        $UsuarioMensaje->proveedorId = $proveedorId;
        $UsuarioMensaje->tipo = "MESSAGEINV";

        /* Asigna valores a propiedades del objeto UsuarioMensaje y crea una instancia de DAO. */
        $UsuarioMensaje->paisId = $CountrySelect;
        $UsuarioMensaje->fechaExpiracion = $DateExpiration;
        $UsuarioMensaje->usumencampanaId = $usumencampanaId;
        $msg = "entro5";

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

        /* inserta un mensaje y confirma la transacción en MySQL. */
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

        $UsuarioMensajecampana->usumensajeId = $UsuarioMensaje->usumensajeId;

        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

        /* Actualiza un registro y confirma la transacción en una base de datos MySQL. */
        $UsuarioMensajecampanaMySqlDAO->update($UsuarioMensajecampana);
        $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


        $ParentId = $UsuarioMensaje->usumensajeId;
    }

    /* concatena un mensaje con un texto y un botón de marcado. */
    $Message = $Message . '##FIX##' . $ButtonText;
    foreach ($clients as $key => $ClientId) {


        if ($ClientId != "" && $ClientId != "0") {
            try {

                /* Inicializa `$usutoId`, crea un objeto si `$ClientId` no es cero. */
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


                //$UsuarioMensaje2->isRead = 0;

                if ($Id != '') {

                    /* Crea un objeto UsuarioMensaje con propiedades específicas asignadas a partir de variables. */
                    $UsuarioMensaje = new UsuarioMensaje($Id);
                    $UsuarioMensaje->usutoId = $usutoId;
                    $UsuarioMensaje->body = $Message;
                    $UsuarioMensaje->msubject = $Image;
                    $UsuarioMensaje->parentId = $ParentId;
                    $UsuarioMensaje->proveedorId = $proveedorId;

                    /* Código que configura propiedades de un objeto y prepara un DAO para interacción con MySQL. */
                    $UsuarioMensaje->tipo = "MESSAGEINV";
                    $UsuarioMensaje->paisId = $CountrySelect;
                    $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                    $UsuarioMensaje->usumencampanaId = $usumencampanaId;
                    $msg = "entro5";


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

                    /* Código para insertar un mensaje de usuario en la base de datos con transacción. */
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    /*if ($UsuarioMensaje2->usumensaje_id != null) {
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                    }*/
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                } else {


                    /* Crea un nuevo objeto UsuarioMensaje y asigna sus propiedades. */
                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $usutoId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $Message;
                    $UsuarioMensaje->msubject = $Image;

                    /* Se asignan valores a propiedades del objeto $UsuarioMensaje para guardar un mensaje. */
                    $UsuarioMensaje->parentId = $ParentId;
                    $UsuarioMensaje->proveedorId = $proveedorId;
                    $UsuarioMensaje->tipo = "MESSAGEINV";
                    $UsuarioMensaje->paisId = $CountrySelect;
                    $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                    $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                    /* Código para insertar un mensaje en base de datos usando una clase DAO. */
                    $msg = "entro5";


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    /*if ($UsuarioMensaje2->usumensaje_id != null) {
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                    }*/
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                }

                /* Código que gestiona usuarios y envía mensajes a través de WebSocket. */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                //$WebsocketUsuario->sendWSMessage();

                $msg = "entro6";

            } catch (Exception $e) {
                /* Captura excepciones y almacena el mensaje de error en la variable $msg. */

                $msg = $e->getMessage();

            }

            /* configura una respuesta exitosa en formato de array. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = $msg . " - " . $ClientId;
            $response["ModelErrors"] = [];

            $response["Data"] = [];

        } else {
            /* maneja una respuesta exitosa sin errores en un sistema. */


            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = $msg . " - " . $ClientId;
            $response["ModelErrors"] = [];

            $response["Data"] = [];
        }
    }
}
