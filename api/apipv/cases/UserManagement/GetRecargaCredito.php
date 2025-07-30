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
use Backend\dto\Helpers;
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
 * UserManagement/GetRecargaCredito
 *
 * Este script permite obtener detalles de un usuario para realizar un depósito.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento por el cual se ordenará la consulta.
 * @param int $params->SkeepRows Número de filas a omitir en la consulta.
 * @param string $params->Id ID del usuario.
 * @param string $params->Cedula Número de cédula del usuario.
 * @param string $params->Email Correo electrónico del usuario.
 * @param string $params->Phone Número de teléfono del usuario.
 *
 * @return array Respuesta en formato JSON con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje asociado a la alerta.
 *  - data (array): Contiene los datos del usuario.
 */

/* Se crean instancias de UsuarioMandante y Usuario utilizando datos de sesión. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());



/* Se obtienen y decodifican parámetros JSON de una solicitud HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$Id = $_REQUEST["Id"];

$Cedula = $_REQUEST["Cedula"];

/* obtiene y limpia datos de entrada del usuario para su procesamiento. */
$Email = $_REQUEST["Email"];
$Phone = $_REQUEST["Phone"];

$ConfigurationEnvironment = new ConfigurationEnvironment();

$Cedula = $ConfigurationEnvironment->DepurarCaracteres($Cedula);

/* depura caracteres de email, teléfono e ID usando una función específica. */
$Email = $ConfigurationEnvironment->DepurarCaracteres($Email);
$Phone = $ConfigurationEnvironment->DepurarCaracteres($Phone);
$Id = $ConfigurationEnvironment->DepurarCaracteres($Id);

if ($Cedula != "" || $Email != "" || $Id != "" || $Phone != "") {

    /* asigna valores de parámetros a variables y gestiona solicitudes HTTP. */
    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    $MaxRows = $_REQUEST["count"];
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


    /* asigna valores por defecto a variables si están vacías. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Establece un límite de filas y define reglas para un filtro. */
    if ($MaxRows == "") {
        $MaxRows = 1000;
    }

    $rules = [];
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$UsuarioPuntoVenta->paisId", "op" => "eq"));

    /* Se construye un array de reglas para filtrar usuarios según condiciones específicas. */
    array_push($rules, array("field" => "usuario.mandante", "data" => "$UsuarioPuntoVenta->mandante", "op" => "eq"));
    array_push($rules, array("field" => "usuario.plataforma", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


    if ($Id != "" && $Id != "undefined") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "eq"));

    }


    /* agrega reglas de validación basadas en cedula y email no vacíos. */
    if ($Cedula != "" && $Cedula != "undefined") {

        array_push($rules, array("field" => "registro.cedula", "data" => "$Cedula", "op" => "eq"));

    }

    if ($Email != "" && $Email != "undefined") {
        array_push($rules, array("field" => "usuario.login", "data" => "$Email", "op" => "eq"));

    }


    /* Se valida y agrega una regla de filtrado para el número de teléfono. */
    if ($Phone != "" && $Phone != "undefined") {
        array_push($rules, array("field" => "registro.celular", "data" => "$Phone", "op" => "eq"));

    }
    // array_push($rules, array("field" => "registro.estado_valida", "data" => "I", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Convierte datos a JSON, obtiene usuarios y establece un error en falso. */
    $json = json_encode($filtro);

    $Usuario = new Usuario();
    $tickets = $Usuario->getUsuariosCustom(" pais.pais_nom,usuario.usuario_id,usuario.nombre,usuario.moneda, registro.nombre", "usuario.usuario_id", "asc", 0, 1, $json, true);
    $tickets = json_decode($tickets);


    $response["HasError"] = false;

    /* construye una respuesta con datos de tickets si existen. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    if (oldCount($tickets->data) > 0) {
        $response["data"] =
            array(
                "Id" => $tickets->data[0]->{"usuario.usuario_id"},
                "Name" => $tickets->data[0]->{"registro.nombre"},
                "Pais" => $tickets->data[0]->{"pais.pais_nom"},
                "Moneda" => $tickets->data[0]->{"usuario.moneda"});

    } else {
        /* asigna un valor booleano a "HasError" en caso de error. */

        $response["HasError"] = true;
    }


} else {
    /* Lanza una excepción con un mensaje de error y un código específico. */

    throw new Exception("Error ", "1");
}
