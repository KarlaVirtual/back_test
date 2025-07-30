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
 * Client/SaveClientMessage
 *
 * Envío de mensaje programado o inmediato
 *
 * Este recurso permite el envío de un mensaje desde un cliente a un destinatario.
 * Los mensajes pueden ser enviados inmediatamente o programados para ser enviados en
 * una fecha y hora específicas. También permite incluir detalles como el remitente,
 * el asunto, la descripción y la fecha de expiración del mensaje.
 *
 * @param string $ClientId : Identificador único del cliente para asociar con el mensaje.
 * @param string $Description : Descripción breve que será incluida en el mensaje a enviar.
 * @param string $Message : Contenido completo del mensaje a ser enviado al destinatario.
 * @param string $Name : Nombre del remitente del mensaje (quien está enviando el mensaje).
 * @param string $Title : Título o asunto del mensaje.
 * @param string $DateExpiration : Fecha y hora de expiración del mensaje, después de la cual ya no será válido.
 * @param string $DateFrom : Fecha y hora de envío del mensaje.
 * @param string $DateProgram : Fecha programada para el envío del mensaje si aplica (puede estar vacío si no se utiliza programación).
 * @param string $Mandante : Identificador del mandante, generalmente representado por el usuario o sesión actual que realiza la acción.
 * @param string $CountrySelect : País seleccionado para la operación del mensaje, que puede ser usado para filtrar por ubicación geográfica.
 * @param string $ClientIdCsv : Identificador CSV (Comma-Separated Values) que representa al cliente en formato de texto separado por comas.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error durante la operación (true si hubo error, false si no).
 *  - *AlertType* (string): Especifica el tipo de alerta que se debe mostrar en la vista (ej. "danger", "success").
 *  - *AlertMessage* (string): Contiene el mensaje que será mostrado en la interfaz al usuario (por ejemplo, un mensaje de error o éxito).
 *  - *ModelErrors* (array): Contiene detalles adicionales de los errores de modelo, si los hay, generalmente vacío.
 *  - *Data* (array): Contiene el resultado específico de la operación o datos relevantes para el usuario o sistema.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Hubo un error al procesar la solicitud.";
 * $response["ModelErrors"] = [];
 * $response["Data"] = [];
 *
 * @throws Exception Error en la operación, como un fallo en la validación de los parámetros o al procesar los datos del mensaje.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* decodifica y reemplaza entidades HTML en datos de entrada. */
$_ENV["DBNEEDUTF8"] = '1';

$params = file_get_contents('php://input');
$params = base64_decode($params);
$params = html_entity_decode($params);


$unwanted_array = array(
    '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
    '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
    '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');

/* reemplaza caracteres no deseados y decodifica un JSON para extraer valores. */
$params = strtr($params, $unwanted_array);

$params = json_decode($params);
$ClientId = $params->ClientId;

$Description = $params->Description;

/* normaliza texto reemplazando caracteres y eliminando espacios y saltos de línea. */
$Description = str_replace('ó', 'ó', $Description);

$Message = $params->Message;
$Message = str_replace("'", "\'", $Message);
$Message = trim(preg_replace('/\s+/', ' ', $Message));
$Message = preg_replace("/(\r\n|\n|\r|\t)/i", '', $Message);

/* procesa un mensaje eliminando partes no deseadas y recoge parámetros. */
$Message = explode('</head>', $Message)[1];
$Message = str_replace('</html>', "", $Message);
$Message = preg_replace('/[\xE2\x80\xAF]/', '', $Message);

$Name = $params->Name;
$Title = $params->Title;

/* asigna valores de parámetros y formatea fechas para procesarlas. */
$ParentId = $params->ParentId;
$CountrySelect = $params->CountrySelect;
$DateExpiration = date('Y-m-d H:i:s', $params->DateExpiration);
$DateFrom = date('Y-m-d H:i:s', $params->DateFrom);
$DateProgram = $params->DateProgram;
$Mandante = $_SESSION["mandante"];


/* asigna un valor del parámetro "ClientIdCsv" a la variable $ClientIdCsv. */
$ClientIdCsv = $params->ClientIdCsv;
if ($ClientIdCsv != '') {


    /* decodifica un string en base64 y reemplaza puntos y comas. */
    $ClientIdCsv = explode("base64,", $ClientIdCsv);
    $ClientIdCsv = $ClientIdCsv[1];

    $ClientIdCsv = base64_decode($ClientIdCsv);

    $ClientIdCsv = str_replace(";", ",", $ClientIdCsv);


    /* convierte un CSV en un array mediante separación de líneas y campos. */
    $lines = explode(PHP_EOL, $ClientIdCsv);
    $lines = preg_split('/\r\n|\r|\n/', $ClientIdCsv);
    $array = array();
    foreach ($lines as $line) {
        $array[] = str_getcsv($line);

    }

    /* intenta crear un array final a partir de columnas de otro array. */
    $countArray = oldCount($array[0]);

    for ($i = 0; $i <= $countArray; $i++) {
        $arrayfinal = array();
        $arrayfinal = array_column($array, $i);
    }

    /* Extrae valores de un array y obtiene posiciones de sus elementos. */
    $arrayfinal = array_column($array, '0');

    $posiciones = array_keys($arrayfinal);

    $ultima = strval(end($posiciones));
    $arrayfinal = json_decode(json_encode($arrayfinal));
    // unset($arrayfinal[$ultima]);


    /* Convierte un array en una cadena de texto con elementos separados por comas. */
    $ids = implode(",", $arrayfinal);

}


/* asigna valores a $proveedorId y $CountrySelect según la sesión del usuario. */
$proveedorId = '0';
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}

if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}


/* valida y procesa el identificador de cliente, gestionando valores vacíos o nulos. */
if ($ClientId == "" || $ClientId == "0" || $ClientId == "0," || $ClientId == ",") {
    $ClientId = '0';
} else {
    $clients = explode(",", $ClientId);
}

if ($ids != "") {

    $clients = $ids;
    $clients = explode(",", $clients);
}


if ($DateProgram == '') {


    /* Asigna un ID a `$usutoId` basado en condiciones del cliente y la cantidad de clientes. */
    if (($ClientId == "" || $ClientId == "0") && oldCount($clients) == 0) {
        $usutoId = '0';
    } else {
        $usutoId = -1;
    }

    $UsuarioMensajecampana = new UsuarioMensajecampana();

    /* Se asignan valores a las propiedades de un objeto de mensaje de campaña. */
    $UsuarioMensajecampana->usufromId = 0;
    $UsuarioMensajecampana->usutoId = $usutoId;
    $UsuarioMensajecampana->isRead = 0;
    $UsuarioMensajecampana->body = $Message;
    $UsuarioMensajecampana->msubject = "";
    $UsuarioMensajecampana->parentId = 0;

    /* asigna valores a propiedades de un objeto relacionado a usuarios y mensajes. */
    $UsuarioMensajecampana->proveedorId = $proveedorId;
    $UsuarioMensajecampana->tipo = "MENSAJE";
    $UsuarioMensajecampana->paisId = $CountrySelect;
    $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;
    $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];
    $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];;

    /* Se asignan valores a propiedades de un objeto relacionado a una campaña de usuario. */
    $UsuarioMensajecampana->nombre = $Name;
    $UsuarioMensajecampana->descripcion = $Description;
    $UsuarioMensajecampana->t_value = "";
    $UsuarioMensajecampana->mandante = $Mandante;
    $UsuarioMensajecampana->fechaEnvio = $DateFrom;

    $Title = $Description;


    /* Se inserta un mensaje en la base de datos mediante un DAO. */
    $msg = "entro5";

    $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

    $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
    $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


    /* Se define un ID de campaña y se inicializa un objeto de mensaje para usuarios. */
    $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;

    /*$usutoIdF="-1";

    if(oldCount($clients)==0 &&( $ClientId == "" || $ClientId == '0' || $ClientId == ',' || $ClientId == "0,")){
        $usutoIdF="0";

    }*/

    $UsuarioMensaje = new UsuarioMensaje();

    /* Se asignan valores a atributos de un objeto de mensaje de usuario. */
    $UsuarioMensaje->usufromId = 0;
    $UsuarioMensaje->usutoId = $usutoId;
    $UsuarioMensaje->isRead = 0;
    $UsuarioMensaje->body = $Message;
    $UsuarioMensaje->msubject = $Title;
    $UsuarioMensaje->parentId = 0;

    /* Se asignan valores a las propiedades de un objeto 'UsuarioMensaje'. */
    $UsuarioMensaje->proveedorId = $proveedorId;
    $UsuarioMensaje->tipo = "MENSAJE";
    $UsuarioMensaje->paisId = $CountrySelect;
    $UsuarioMensaje->fechaExpiracion = $DateExpiration;
    $UsuarioMensaje->usumencampanaId = $usumencampanaId;
    $msg = "entro5";


    /* Código que inserta un mensaje de usuario en una base de datos y confirma la transacción. */
    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

    $UsuarioMensajecampana->usumensajeId = $UsuarioMensaje->usumensajeId;


    /* Actualiza datos en MySQL y confirma la transacción, obteniendo un identificador. */
    $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
    $UsuarioMensajecampanaMySqlDAO->update($UsuarioMensajecampana);
    $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


    $ParentId = $UsuarioMensaje->usumensajeId;


    if ((oldCount($clients) > 0 || (oldCount($clients) == 0 && $ClientId != '0'))) {


        foreach ($clients as $key => $ClientId) {


            if ($ClientId != "" && $ClientId != "0") {
                try {


                    /* Código que procesa un ID de cliente y crea objetos relacionados. */
                    $usutoId = '';
                    if ($ClientId != 0) {
                        $ClientId = strtolower($ClientId);
                        $ClientId = preg_replace("/(\r\n|\n|\r|\t)/i", '', $ClientId);

                        $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);

                        $msg = "entro4";

                        $UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                        $usutoId = $UsuarioMandante->usumandanteId;

                    }


                    /* Asigna 0 a $ParentId si está vacío y guarda $Message en $mensajeTemp. */
                    if ($ParentId == "") {
                        $ParentId = 0;
                    }


                    $mensajeTemp = $Message;

                    /* Reemplaza marcadores en un mensaje con valores de un arreglo en orden descendente. */
                    for ($i = $countArray - 1; $i > 0; $i--) {
                        $field = $array[$key][$i];


                        $mensajeTemp = str_replace('#col' . $i, $field, $mensajeTemp);
                    }


                    /* inicializa un objeto de mensaje con estado no leído para un usuario. */
                    $UsuarioMensaje2->isRead = 0;

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $usutoId;
                    $UsuarioMensaje->isRead = 0;

                    /* Se asignan propiedades a un objeto de mensaje de usuario con diversos atributos. */
                    $UsuarioMensaje->body = $mensajeTemp;
                    $UsuarioMensaje->msubject = $Title;
                    $UsuarioMensaje->parentId = $ParentId;
                    $UsuarioMensaje->proveedorId = $proveedorId;
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->paisId = $CountrySelect;

                    /* Código que asigna datos a un objeto y lo inserta en la base de datos. */
                    $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                    $UsuarioMensaje->usumencampanaId = $usumencampanaId;
                    $msg = "entro5";

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                    /* realiza una transacción y prepara envíos de mensajes WebSocket. */
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                    /*if ($UsuarioMensaje2->usumensaje_id != null) {
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                    }
                   */


                    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                    //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    //$WebsocketUsuario->sendWSMessage();

                    $msg = "entro6";

                } catch (Exception $e) {
                    /* Captura excepciones en PHP y almacena el mensaje de error en $msg. */

                    $msg = $e->getMessage();

                }

                /* Código para generar una respuesta exitosa en formato de arreglo. */
                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = $msg . " - " . $ClientId;
                $response["ModelErrors"] = [];

                $response["Data"] = [];

            } else {
                /* Manejo de errores para respuestas con datos incorrectos en una API. */

                $response["HasError"] = true;
                $response["AlertType"] = "error";
                $response["AlertMessage"] = "Datos incorrectos";
                $response["ModelErrors"] = [];

                $response["Data"] = [];
            }
        }
    } else {
        /* define una respuesta exitosa sin errores para un cliente. */

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg . " - " . $ClientId;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

    }
} else {


    foreach ($DateProgram as $keys => $value) {

        /* Se asigna un ID según condiciones del cliente y bonificaciones. */
        if (($ClientId == "" || $ClientId == "0") && oldCount($clients) == 0) {
            $usutoId = '0';
        } else {
            $usutoId = -1;
        }
        if ($params->IsBonus == 1) {
            $usutoId = -1;
        }

        /* formatea fechas y crea un objeto de usuario para mensajes de campaña. */
        $SendDate = date('Y-m-d H:i:s', $value->SendDate);
        $DateExpiration = date('Y-m-d H:i:s', $value->DateExpiration);


        $UsuarioMensajecampana = new UsuarioMensajecampana();
        $UsuarioMensajecampana->usufromId = 0;

        /* Asignación de valores a propiedades de un objeto UsuarioMensajecampana. */
        $UsuarioMensajecampana->usutoId = $usutoId;
        $UsuarioMensajecampana->isRead = 0;
        $UsuarioMensajecampana->body = $Message;
        $UsuarioMensajecampana->msubject = "";
        $UsuarioMensajecampana->parentId = 0;
        $UsuarioMensajecampana->proveedorId = $proveedorId;

        /* asigna valores a propiedades de un objeto de usuario en una campaña. */
        $UsuarioMensajecampana->tipo = "MENSAJE";
        $UsuarioMensajecampana->paisId = $CountrySelect;
        $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;
        $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];
        $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];;
        $UsuarioMensajecampana->nombre = $Name;

        /* Se asignan valores a atributos de un objeto relacionado con campañas de mensajería. */
        $UsuarioMensajecampana->descripcion = $Description;
        $UsuarioMensajecampana->t_value = "";
        $UsuarioMensajecampana->mandante = $Mandante;
        $UsuarioMensajecampana->fechaEnvio = $SendDate;
        //$UsuarioMensajecampana->estado = 'A';

        $Title = $Description;


        /* inserta un mensaje en la base de datos usando un DAO. */
        $msg = "entro5";

        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

        $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
        $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


        /* Asigna el valor de 'usumencampanaId' a la variable '$usumencampanaId'. */
        $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;
        if ($params->IsBonus != 1) {


            /* Crea un nuevo objeto UsuarioMensaje con datos de usuario y mensaje. */
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $usutoId;
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $Message;
            $UsuarioMensaje->msubject = $Title;

            /* Asignación de propiedades a un objeto UsuarioMensaje en un contexto específico. */
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $proveedorId;
            $UsuarioMensaje->tipo = "MENSAJE";
            $UsuarioMensaje->paisId = $CountrySelect;
            $UsuarioMensaje->fechaExpiracion = $DateExpiration;
            $UsuarioMensaje->usumencampanaId = $usumencampanaId;

            /* Código que inserta un mensaje de usuario y gestiona la transacción en MySQL. */
            $msg = "entro5";

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

            $UsuarioMensajeMySqlDAO->getTransaction()->commit();


            /* Actualiza información de usuario en la base de datos y gestiona transacciones. */
            $UsuarioMensajecampana->usumensajeId = $UsuarioMensaje->usumensajeId;

            $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
            $UsuarioMensajecampanaMySqlDAO->update($UsuarioMensajecampana);
            $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


            //$ParentId = $UsuarioMensaje->usumensajeId;
        }


        if ((oldCount($clients) > 0 || (oldCount($clients) == 0 && $ClientId != '0'))) {


            foreach ($clients as $key => $ClientId) {


                if ($ClientId != "" && $ClientId != "0") {
                    try {


                        /* verifica un ClientId, procesándolo y creando un objeto UsuarioMandante. */
                        $usutoId = '';
                        if ($ClientId != 0) {
                            $ClientId = strtolower($ClientId);
                            $ClientId = preg_replace("/(\r\n|\n|\r|\t)/i", '', $ClientId);

                            $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);

                            $msg = "entro4";

                            //$UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                            $usutoId = $UsuarioMandante->usumandanteId;

                        }


                        /* asigna 0 a `$ParentId` si está vacío y guarda `$Message` en `$mensajeTemp`. */
                        if ($ParentId == "") {
                            $ParentId = 0;
                        }


                        $mensajeTemp = $Message;

                        /* Reemplaza placeholders en un mensaje utilizando valores de un array en PHP. */
                        for ($i = $countArray - 1; $i > 0; $i--) {
                            $field = $array[$key][$i];


                            $mensajeTemp = str_replace('#col' . $i, $field, $mensajeTemp);
                        }


                        //$UsuarioMensaje2->isRead = 0;


                        /* Crea un nuevo mensaje de usuario con destinatario, contenido y estado de lectura. */
                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $usutoId;
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $mensajeTemp;
                        $UsuarioMensaje->msubject = $Title;

                        /* Crea un objeto de mensaje de usuario con atributos específicos. */
                        $UsuarioMensaje->parentId = $ParentId;
                        $UsuarioMensaje->proveedorId = $proveedorId;
                        $UsuarioMensaje->tipo = "MENSAJE";
                        $UsuarioMensaje->paisId = $CountrySelect;
                        $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                        $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                        /* gestiona mensajes de usuario insertando y comentando lógicas relacionadas con WebSocket. */
                        $msg = "entro5";

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                        /*if ($UsuarioMensaje2->usumensaje_id != null) {
                            $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                        }
                       */


                        /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                        //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                        //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                        //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                        //$WebsocketUsuario->sendWSMessage();

                        $msg = "entro6";

                    } catch (Exception $e) {
                        /* Manejo de excepciones en PHP, captura y asignación del mensaje de error a $msg. */

                        $msg = $e->getMessage();

                    }

                    /* Código que construye una respuesta exitosa con información y sin errores. */
                    $response["HasError"] = false;
                    $response["AlertType"] = "success";
                    $response["AlertMessage"] = $msg . " - " . $ClientId;
                    $response["ModelErrors"] = [];

                    $response["Data"] = [];

                } else {
                    /* Manejo de errores: si hay problemas, se genera una respuesta de error. */

                    $response["HasError"] = true;
                    $response["AlertType"] = "error";
                    $response["AlertMessage"] = "Datos incorrectos";
                    $response["ModelErrors"] = [];

                    $response["Data"] = [];
                }
            }
        } else {
            /* define una respuesta exitosa sin errores en formato JSON. */

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = $msg . " - " . $ClientId;
            $response["ModelErrors"] = [];

            $response["Data"] = [];

        }
    }
}

