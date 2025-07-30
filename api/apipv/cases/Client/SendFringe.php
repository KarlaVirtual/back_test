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
 * Envía un mensaje tipo "franja" a uno o varios usuarios.
 *
 * @param object $params Objeto que contiene:
 * @param string $params ->ClientId IDs de los clientes separados por comas.
 * @param string $params ->Content Contenido del mensaje.
 * @param string $params ->Title Título del mensaje.
 * @param int|null $params ->ParentId ID del mensaje padre (opcional).
 * @param int $params ->CountrySelect ID del país.
 * @param string $params ->DateExpiration Fecha de expiración en formato ISO 8601.
 * @param string $params ->DateSend Fecha de envío en formato ISO 8601.
 * @param string $params ->Redirection URL de redirección.
 * @param int|null $params ->Id ID del mensaje (opcional).
 * @param string $params ->State Estado del mensaje ("I" para inactivo, "A" para activo).
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Datos adicionales.
 *
 * @throws Exception Si ocurre un error durante la inserción o actualización del mensaje.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables específicas en un script. */
$ClientId = $params->ClientId;
$Message = $params->Content;
$Title = $params->Title;
$ParentId = $params->ParentId;
$CountrySelect = $params->CountrySelect;
$DateExpiration = $params->DateExpiration;

/* obtiene datos de parámetros y ajusta el ID del proveedor según la sesión. */
$DateSend = $params->DateSend;
$Redirection = $params->Redirection;

$proveedorId = -1;
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}


/* procesa y formatea una fecha de expiración desde un formato específico. */
$Id = $params->Id;
$State = $params->State;


if ($DateExpiration != "") {
    $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $DateExpiration)));
    $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace(".000Z", "", $DateExpiration)));

}


/* verifica la sesión y extrae identificadores de clientes en un array. */
if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}

$clients = explode(",", $ClientId);
foreach ($clients as $key => $ClientId) {


    if ($ClientId != "") {
        try {

            /* Define un ID de usuario basado en el ClientId proporcionado. */
            $usutoId = 0;
            if ($ClientId != 0) {
                $UsuarioMandante = new UsuarioMandante("", $ClientId, "0");
                $msg = "entro4";

                //$UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                $usutoId = $UsuarioMandante->usumandanteId;
            }


            /* Asigna 0 a $ParentId si está vacío o es una cadena vacía. */
            if ($ParentId == "") {
                $ParentId = 0;
            }

            if ($Id != '') {

                /* Crea un objeto UsuarioMensaje con propiedades específicas para gestionar mensajes de usuarios. */
                $UsuarioMensaje = new UsuarioMensaje($Id);
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Redirection;
                $UsuarioMensaje->parentId = $ParentId;
                $UsuarioMensaje->proveedorId = $proveedorId;

                /* Código que establece propiedades de un objeto UsuarioMensaje según condiciones específicas. */
                $UsuarioMensaje->tipo = "STRIPETOP";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->fechaExpiracion = $DateExpiration;

                if ($State == 'I') {
                    //$UsuarioMensaje->isRead = 1;
                } elseif ($State == 'A') {
                    /* Condicional que verifica el estado 'A' sin realizar acciones en este caso. */

                    //$UsuarioMensaje->isRead = 0;
                }


                /* Actualiza un mensaje de usuario en la base de datos mediante DAO en PHP. */
                $msg = "entro5";

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                /*if ($UsuarioMensaje2->usumensaje_id != null) {
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                }*/
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();


            } else {

                /* Crea una instancia de UsuarioMensaje con información sobre el usuario y mensaje. */
                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Redirection;

                /* Código asigna valores a propiedades de un objeto UsuarioMensaje. */
                $UsuarioMensaje->parentId = $ParentId;
                $UsuarioMensaje->proveedorId = $proveedorId;
                $UsuarioMensaje->tipo = "STRIPETOP";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                $msg = "entro5";


                /* Inserta un mensaje de usuario en la base de datos utilizando DAO. */
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                /*if ($UsuarioMensaje2->usumensaje_id != null) {
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                }*/
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();


            }


            /* Se crea un objeto de usuario y se prepara un mensaje para WebSocket. */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
            //$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            //$WebsocketUsuario->sendWSMessage();

            $msg = "entro6";


        } catch (Exception $e) {
            /* Captura excepciones y almacena el mensaje en la variable $msg. */

            $msg = $e->getMessage();

        }

        /* configura una respuesta JSON sin errores, con mensaje y datos vacíos. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg . " - " . $ClientId;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

    } else {
        /* Manejo de errores: establece respuesta con mensaje de error y datos vacíos. */

        $response["HasError"] = true;
        $response["AlertType"] = "error";
        $response["AlertMessage"] = "Datos incorrectos";
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    }
}