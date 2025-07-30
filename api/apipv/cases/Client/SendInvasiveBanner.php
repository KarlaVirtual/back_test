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
 * Client/SendInvasiveBanner
 *
 * Envía un banner invasivo a uno o varios usuarios.
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
 * @param string $params ->Image URL de la imagen del banner.
 * @param string $params ->ButtonText Texto del botón.
 * @param int|null $params ->Id ID del mensaje (opcional).
 *
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Datos adicionales.
 */


/* Asignación de valores desde un objeto `$params` a variables en PHP. */
$ClientId = $params->ClientId;
$Message = $params->Content;
$Title = $params->Title;
$ParentId = $params->ParentId;
$CountrySelect = $params->CountrySelect;
$DateExpiration = $params->DateExpiration;

/* asigna parámetros a variables y define un identificador inicial negativamente. */
$DateSend = $params->DateSend;
$Redirection = $params->Redirection;
$Image = $params->Image;
$ButtonText = $params->ButtonText;

$proveedorId = -1;

/* maneja sesiones y formatea fechas de expiración. */
if ($_SESSION["Global"] == 'N') {
    $proveedorId = $_SESSION["mandante"];
}

$Id = $params->Id;

if ($DateExpiration != "") {
    $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $DateExpiration)));
    $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace(".000Z", "", $DateExpiration)));

}


/* verifica una condición y asigna un valor a la variable `$CountrySelect`. */
if ($_SESSION['PaisCond'] == "S") {
    $CountrySelect = $_SESSION["pais_id"];
}

$clients = explode(",", $ClientId);
foreach ($clients as $key => $ClientId) {


    if ($ClientId != "") {
        try {

            /* Se inicializa un ID de usuario solo si ClientId es diferente de cero. */
            $usutoId = 0;
            if ($ClientId != 0) {
                $UsuarioMandante = new UsuarioMandante("", $ClientId, "0");
                $msg = "entro4";

                //$UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                $usutoId = $UsuarioMandante->usumandanteId;
            }


            /* Asigna 0 a $ParentId si está vacío y concatena texto a $Message. */
            if ($ParentId == "") {
                $ParentId = 0;
            }

            $Message = $Message . '##FIX##' . $ButtonText;

            //$UsuarioMensaje2->isRead = 0;

            if ($Id != '') {

                /* Se instancia un objeto UsuarioMensaje y se asignan diversas propiedades. */
                $UsuarioMensaje = new UsuarioMensaje($Id);
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Image;
                $UsuarioMensaje->parentId = $ParentId;
                $UsuarioMensaje->proveedorId = $proveedorId;

                /* Se asignan valores a un objeto y se crea una instancia de la clase DAO. */
                $UsuarioMensaje->tipo = "MESSAGEINV";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                $msg = "entro5";


                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

                /* Inserta un mensaje y gestiona transacciones en MySQL para usuarios. */
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                /*if ($UsuarioMensaje2->usumensaje_id != null) {
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                }*/
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

            } else {


                /* Crea un nuevo objeto UsuarioMensaje con propiedades inicializadas para el mensaje. */
                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $usutoId;
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Image;

                /* Configura propiedades de un objeto de mensaje para su procesamiento posterior. */
                $UsuarioMensaje->parentId = $ParentId;
                $UsuarioMensaje->proveedorId = $proveedorId;
                $UsuarioMensaje->tipo = "MESSAGEINV";
                $UsuarioMensaje->paisId = $CountrySelect;
                $UsuarioMensaje->fechaExpiracion = $DateExpiration;
                $msg = "entro5";


                /* Código establece un DAO para insertar un mensaje de usuario en la base de datos. */
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                /*if ($UsuarioMensaje2->usumensaje_id != null) {
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                }*/
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
            }

            /* Código que gestiona un usuario y envía actualizaciones de saldo mediante WebSocket. */
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

        /* Se crea una respuesta estructurada con éxito y sin errores para un cliente. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg . " - " . $ClientId;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

    } else {
        /* maneja un error de datos incorrectos y genera una respuesta estructurada. */

        $response["HasError"] = true;
        $response["AlertType"] = "error";
        $response["AlertMessage"] = "Datos incorrectos";
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    }
}