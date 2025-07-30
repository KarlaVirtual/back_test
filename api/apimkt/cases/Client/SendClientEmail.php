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
 * Client/SendClientEmail
 *
 * Envío y registro de mensajes de campaña para múltiples clientes.
 *
 * Este recurso gestiona el envío y registro de mensajes personalizados para un conjunto de clientes,
 * asignando valores según los parámetros proporcionados. Se insertan los mensajes tanto a nivel de campaña
 * como de usuario, gestionando fechas de expiración, proveedores y asignando valores predeterminados según la sesión.
 * Además, se gestionan excepciones para asegurar que cualquier error sea capturado y se mantenga una respuesta coherente.
 *
 * @param string $ClientId : Identificadores de los clientes para los cuales se enviarán los mensajes.
 *        Estos valores son extraídos y divididos en un arreglo para ser procesados individualmente.
 * @param string $Description : Descripción de la campaña o mensaje que será enviado.
 * @param string $Message : Contenido principal del mensaje a ser enviado.
 * @param string $Name : Nombre del mensaje o campaña.
 * @param string $Title : Título del mensaje a ser mostrado a los destinatarios.
 * @param string $ParentId : Identificador de la campaña o mensaje principal (si es un mensaje hijo, se refiere al ID del mensaje principal).
 * @param string $CountrySelect : País seleccionado para el envío del mensaje.
 * @param string $DateExpiration : Fecha de expiración del mensaje, que se formatea y ajusta según el formato adecuado.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error durante el proceso (true en caso de error, false si la operación fue exitosa).
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará al usuario ("success" para éxito, "error" para fallos).
 *  - *AlertMessage* (string): Contiene el mensaje que se debe mostrar, el cual puede incluir información adicional como el ClientId o errores.
 *  - *ModelErrors* (array): Errores asociados al modelo de datos si existiesen, generalmente vacío si la operación fue exitosa.
 *  - *Data* (array): Contiene datos adicionales o detalles sobre la operación (por ejemplo, detalles del mensaje o usuario).
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "Datos incorrectos";
 * $response["ModelErrors"] = [];
 * $response["Data"] = [];
 *
 * Ejemplo de respuesta exitosa:
 *
 * $response["HasError"] = false;
 * $response["AlertType"] = "success";
 * $response["AlertMessage"] = "Mensaje enviado con éxito.";
 * $response["ModelErrors"] = [];
 * $response["Data"] = [];
 *
 * @throws Exception Error durante la ejecución, como la falla en la base de datos, parámetros incorrectos o problemas con la fecha de expiración.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

try {

    /* Asigna valores de parámetros a variables en un contexto de programación. */
    $ClientId = $params->ClientId;
    $Description = $params->Description;
    $Message = $params->Message;
    $Name = $params->Name;
    $Title = $params->Title;
    $ParentId = $params->ParentId;

    /* extrae datos de parámetros y establece un identificador de proveedor según sesión. */
    $CountrySelect = $params->CountrySelect;
    $DateExpiration = $params->DateExpiration;


    $proveedorId = '0';
    if ($_SESSION["Global"] == 'N') {
        $proveedorId = $_SESSION["mandante"];
    }


    /* formatea una fecha y selecciona un país basado en la sesión. */
    if ($DateExpiration != "") {
        $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $DateExpiration)));
        $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace(".000Z", "", $DateExpiration)));

    }

    if ($_SESSION['PaisCond'] == "S") {
        $CountrySelect = $_SESSION["pais_id"];
    }


    /* divide una cadena de texto en elementos de un arreglo usando comas. */
    $clients = explode(",", $ClientId);
    if (oldCount($clients) > 0) {


        /* Inicializa un objeto de usuario para mensajes de campaña con valores predeterminados. */
        $UsuarioMensajecampana = new UsuarioMensajecampana();
        $UsuarioMensajecampana->usufromId = 0;
        $UsuarioMensajecampana->usutoId = -1;
        $UsuarioMensajecampana->isRead = 0;
        $UsuarioMensajecampana->body = "";
        $UsuarioMensajecampana->msubject = "";

        /* Asignación de valores a las propiedades de un objeto de mensaje de campaña. */
        $UsuarioMensajecampana->parentId = 0;
        $UsuarioMensajecampana->proveedorId = $proveedorId;
        $UsuarioMensajecampana->tipo = "MENSAJE";
        $UsuarioMensajecampana->paisId = $CountrySelect;
        $UsuarioMensajecampana->fechaExpiracion = $DateExpiration;
        $UsuarioMensajecampana->nombre = $Name;

        /* Se asignan valores a un objeto y se inicializa un DAO para base de datos. */
        $UsuarioMensajecampana->descripcion = $Description;
        $UsuarioMensajecampana->t_value = "";

        $msg = "entro5";

        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();


        /* Inserta un mensaje en la campaña y confirma la transacción en MySQL. */
        $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
        $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

        $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;

        $UsuarioMensaje = new UsuarioMensaje();

        /* Se asignan valores a las propiedades de un objeto UsuarioMensaje en PHP. */
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = -1;
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $Message;
        $UsuarioMensaje->msubject = $Title;
        $UsuarioMensaje->parentId = 0;

        /* Asigna valores a propiedades de un objeto y define un mensaje de entrada. */
        $UsuarioMensaje->proveedorId = $proveedorId;
        $UsuarioMensaje->tipo = "MENSAJE";
        $UsuarioMensaje->paisId = $CountrySelect;
        $UsuarioMensaje->fechaExpiracion = $DateExpiration;
        $UsuarioMensaje->usumencampanaId = $usumencampanaId;
        $msg = "entro5";


        /* Se inserta un mensaje de usuario en la base de datos y se confirma la transacción. */
        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

        $ParentId = $UsuarioMensaje->usumensajeId;
    }

    foreach ($clients as $key => $ClientId) {


        if ($ClientId != "") {
            try {


                /* Código que inicializa un ID de usuario si el ClientId es distinto de cero. */
                $usutoId = 0;
                if ($ClientId != 0) {

                    $UsuarioMandante = new UsuarioMandante("", $ClientId, $proveedorId);

                    $msg = "entro4";

                    $UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                    $usutoId = $UsuarioMandante->usumandanteId;

                }


                /* inicializa `$ParentId` y establece el estado de un mensaje como no leído. */
                if ($ParentId == "") {
                    $ParentId = 0;
                }

                $UsuarioMensaje2->isRead = 0;

                $UsuarioMensaje = new UsuarioMensaje();

                /* Se asignan valores a las propiedades de un objeto UsuarioMensaje para un mensaje. */
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Title;
                $UsuarioMensaje->parentId = $ParentId;

                /* Asignación de propiedades a un objeto de mensaje de usuario en PHP. */
                $UsuarioMensaje->proveedorId = $proveedorId;
                $UsuarioMensaje->tipo = "MENSAJE";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                $UsuarioMensaje->usumencampanaId = $usumencampanaId;
                $msg = "entro5";


                /* Código para insertar y actualizar mensajes de usuario en una base de datos MySQL. */
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                if ($UsuarioMensaje2->usumensaje_id != null) {
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                }
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                /* Código que gestiona un usuario y potencialmente envía mensajes por WebSocket. */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                //$WebsocketUsuario->sendWSMessage();

                $msg = "entro6";

            } catch (Exception $e) {
                /* Captura excepciones y guarda el mensaje en la variable $msg. */

                $msg = $e->getMessage();

            }

            /* crea una respuesta exitosa sin errores y con mensaje personalizado. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = $msg . " - " . $ClientId;
            $response["ModelErrors"] = [];

            $response["Data"] = [];

        } else {
            /* Manejo de errores: se establece respuesta de error y mensajes específicos. */

            $response["HasError"] = true;
            $response["AlertType"] = "error";
            $response["AlertMessage"] = "Datos incorrectos";
            $response["ModelErrors"] = [];

            $response["Data"] = [];
        }
    }
} catch (Exception $e) {
    /* Captura excepciones y muestra información sobre el error en formato legible. */

    print_r($e);
}

