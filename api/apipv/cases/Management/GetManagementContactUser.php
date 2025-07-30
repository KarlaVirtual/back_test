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
use Backend\dto\Contacto;
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
use Backend\mysql\ContactoMySqlDAO;
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
 * Management/GetManagementContact
 *
 * Obtener las solicitudes de contacto.
 *
 * @param array $params Arreglo que contiene los parámetros necesarios para la operación:
 * @param string $params->State Estado del contacto.
 * @param int $params->CountrySelect Identificador del país seleccionado.
 * @param string $params->Names Nombre del contacto.
 * @param string $params->Phone Teléfono del contacto.
 * @param string $params->Skype Skype del contacto.
 * @param string $params->Address Dirección del contacto.
 * @param string $params->Company Compañía del contacto.
 * @param string $params->Email Correo electrónico del contacto.
 * @param string $params->Lastname Apellido del contacto.
 * @param string $params->dateFrom Fecha inicial del filtro.
 * @param string $params->dateTo Fecha final del filtro.
 * 
 * 
 * @return array $response Respuesta estructurada con las siguientes claves:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., success, danger).
 * - AlertMessage (string): Mensaje descriptivo de la operación.
 * - ModelErrors (array): Lista de errores de validación.
 * - Data (array): Datos de los contactos obtenidos.
 * - pos (int): Posición inicial de los resultados.
 * - total_count (int): Total de contactos encontrados.
 */


/* Recibe datos del formulario web mediante el arreglo $_REQUEST en PHP. */
$State = $_REQUEST["State"];
$CountrySelect = $_REQUEST["CountrySelect"];
$Names = $_REQUEST["Names"];
$Phone = $_REQUEST["Phone"];
$Skype = $_REQUEST["Skype"];
$Address = $_REQUEST["Address"];

/* Recibe datos de una solicitud HTTP y asigna valores a variables. */
$Company = $_REQUEST["Company"];
$Email = $_REQUEST["Email"];
$Lastname = $_REQUEST["Lastname"];
$dateFrom = $_REQUEST["dateFrom"];
$dateTo = $_REQUEST["dateTo"];


$OrderedItem = 1;

/* Código define condiciones para filtrar contactos, ignorando filas iniciales y limitando resultados. */
$SkeepRows = 0;
$MaxRows = 10000;


$rules = [];

array_push($rules, array("field" => "contacto.tipo", "data" => "", "op" => "eq"));


/* verifica una fecha y la formatea antes de agregarla a reglas. */
if ($_REQUEST["dateTo"] != "") {
    $dateTo = "$dateTo 23:59:59";
    //$dateTo = date("Y-m-d 23:59:59",$dateTo);
    array_push($rules, array("field" => "contacto.fecha_crea", "data" => $dateTo, "op" => "le"));

}


/* crea condiciones para filtrar registros según fechas y nombres. */
if ($_REQUEST["dateFrom"] != "") {
    $dateFrom = "$dateFrom 00:00:00";
    // $dateFrom = date("Y-m-d 00:00:00", $dateFrom);
    array_push($rules, array("field" => "contacto.fecha_crea", "data" => $dateFrom, "op" => "ge"));
}
if ($Names != "") {
    array_push($rules, array("field" => "contacto.nombre", "data" => "$Names", "op" => "cn"));
}

/* Condiciona reglas para validar email y mandante según sesión de usuario. */
if ($Email != "") {
    array_push($rules, array("field" => "contacto.email", "data" => "$Email", "op" => "eq"));
}

// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "contacto.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {
    /* verifica y agrega reglas de contacto basado en la sesión actual. */


    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "contacto.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

// Si el usuario esta condicionado por País

/* Se establece una regla basada en el país del usuario en sesión. */
if ($_SESSION['PaisCond'] == "S") {
    if ($_SESSION['pais_id'] == 173) {
        array_push($rules, array("field" => "contacto.pais_id", "data" => '0,173', "op" => "in"));

    } else {
        array_push($rules, array("field" => "contacto.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));

    }
}


/* Condiciona la lógica para asignar reglas basadas en la selección de país. */
if ($CountrySelect != "" && $CountrySelect != "0") {
    if ($CountrySelect == 173) {
        array_push($rules, array("field" => "contacto.pais_id", "data" => '0,173', "op" => "in"));

    } else {
        array_push($rules, array("field" => "contacto.pais_id", "data" => $CountrySelect, "op" => "eq"));

    }
}


/* Se configura un filtro y se obtienen contactos personalizados desde la base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Contacto = new Contacto();

$contactos = $Contacto->getContactosCustom("  contacto.* ", "contacto.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);


/* Convierte datos JSON de contactos a un array PHP estructurado para su uso. */
$contactos = json_decode($contactos);
$final = [];

foreach ($contactos->data as $key => $value) {

    $array = [];


    $array["Id"] = $value->{"contacto.contacto_id"};
    $array["DateTimeCreation"] = $value->{"contacto.fecha_crea"};
    $array["Name"] = $value->{"contacto.nombre"};
    $array["Email"] = $value->{"contacto.email"};
    $array["Phone"] = $value->{"contacto.telefono"};
    $array["Message"] = $value->{"contacto.mensaje"};

    array_push($final, $array);


}


/* establece una respuesta exitosa sin errores, incluyendo datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna valores a un array de respuesta para una solicitud. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $contactos->count[0]->{".count"};
$response["data"] = $final;
