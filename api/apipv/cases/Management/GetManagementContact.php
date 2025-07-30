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
 * Management/GetManagementContact
 *
 * Este script obtiene una lista de solicitudes de contacto basadas en filtros proporcionados.
 *
 * @param array $params Parámetros de entrada:
 * @param string $params->State Estado del contacto.
 * @param int $params->CountrySelect ID del país seleccionado.
 * @param string $params->Names Nombres del contacto.
 * @param string $params->Phone Teléfono del contacto.
 * @param string $params->Skype Skype del contacto.
 * @param string $params->Address Dirección del contacto.
 * @param string $params->Company Empresa del contacto.
 * @param string $params->Email Correo electrónico del contacto.
 * @param string $params->Lastname Apellido del contacto.
 * @param string $params->dateFrom Fecha inicial del rango de búsqueda.
 * @param string $params->dateTo Fecha final del rango de búsqueda.
 * 
 * 
 *
 * @return array Respuesta en formato JSON:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos de las solicitudes de contacto.
 * - pos (int): Posición inicial de los datos.
 * - total_count (int): Total de contactos encontrados.
 *
 * @throws Exception Si ocurre un error durante la obtención de datos.
 */


/* recoge datos enviados por formulario mediante solicitudes HTTP. */
$State = $_REQUEST["State"];
$CountrySelect = $_REQUEST["CountrySelect"];
$Names = $_REQUEST["Names"];
$Phone = $_REQUEST["Phone"];
$Skype = $_REQUEST["Skype"];
$Address = $_REQUEST["Address"];

/* Captura datos de entrada sobre empresa, email y apellido usando $_REQUEST en PHP. */
$Company = $_REQUEST["Company"];
$Email = $_REQUEST["Email"];
$Lastname = $_REQUEST["Lastname"];

$OrderedItem = 1;
$SkeepRows = 0;

/* Establece una fecha y limita el número máximo de filas a 10,000 registros. */
$MaxRows = 10000;
$FromDateLocal = $params->dateFrom;

if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"])));

}


/* Convierte una fecha de petición a formato local y establece la hora final del día. */
$ToDateLocal = $params->dateTo;

if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"])));

}


/* Crea un array de reglas basado en nombres y apellidos no vacíos. */
$rules = [];

if ($Names != "") {
    array_push($rules, array("field" => "contacto_comercial.nombres", "data" => "$Names", "op" => "cn"));
}
if ($Lastname != "") {
    array_push($rules, array("field" => "contacto_comercial.apellidos", "data" => "$Lastname", "op" => "cn"));
}

/* Agrega reglas de validación para teléfono y Skype si no están vacíos. */
if ($Phone != "") {
    array_push($rules, array("field" => "contacto_comercial.telefono", "data" => "$Phone", "op" => "eq"));
}
if ($Skype != "") {
    array_push($rules, array("field" => "contacto_comercial.skype", "data" => "$Skype", "op" => "eq"));
}

/* Agrega reglas según la dirección y compañía si no están vacías. */
if ($Address != "") {
    array_push($rules, array("field" => "contacto_comercial.direccion", "data" => "$Address", "op" => "eq"));
}
if ($Company != "") {
    array_push($rules, array("field" => "contacto_comercial.empresa", "data" => "$Company", "op" => "eq"));
}

/* Agrega reglas a un array si el email o país están definidos. */
if ($Email != "") {
    array_push($rules, array("field" => "contacto_comercial.email", "data" => "$Email", "op" => "eq"));
}
if ($CountrySelect != "") {
    array_push($rules, array("field" => "contacto_comercial.pais_id", "data" => "$CountrySelect", "op" => "eq"));
}

/* Añade una regla de filtro según la fecha proporcionada, si no está vacía. */
if ($FromDateLocal != "") {
    array_push($rules, array("field" => "contacto_comercial.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

    //array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

}


/* Agrega condiciones de fecha a un arreglo de reglas en PHP. */
if ($ToDateLocal != "") {
    array_push($rules, array("field" => "contacto_comercial.fecha_crea", "data" => "$ToDateLocal ", "op" => "le"));

    //array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

}


/* agrega reglas a un array según condiciones de sesión en PHP. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "contacto_comercial.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "contacto_comercial.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}


/* Se filtran contactos comerciales y se obtienen datos con formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$ContactoComercial = new ContactoComercial();

$contactos = $ContactoComercial->getContactoComercialesCustom("  contacto_comercial.*,pais.*,departamento.* ", "contacto_comercial.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);


/* decodifica una cadena JSON y crea un arreglo vacío. */
$contactos = json_decode($contactos);
$final = [];

foreach ($contactos->data as $key => $value) {


    /* Crea un array asociativo con información de contacto comercial. */
    $array = [];


    $array["Id"] = $value->{"contacto_comercial.contactocom_id"};
    $array["DateTimeCreation"] = $value->{"contacto_comercial.fecha_crea"};
    $array["Lastname"] = $value->{"contacto_comercial.apellidos"};

    /* asigna valores de contacto a un arreglo asociativo en PHP. */
    $array["Name"] = $value->{"contacto_comercial.nombres"};
    $array["Company"] = $value->{"contacto_comercial.empresa"};
    $array["Email"] = $value->{"contacto_comercial.email"};
    $array["Phone"] = $value->{"contacto_comercial.telefono"};
    $array["Country"] = $value->{"pais.pais_nom"};
    $array["Depto"] = $value->{"departamento.depto_nom"};

    /* asigna datos de contacto comercial a un array asociativo. */
    $array["Address"] = $value->{"contacto_comercial.direccion"};
    $array["Skype"] = $value->{"contacto_comercial.skype"};
    $array["Observation"] = $value->{"contacto_comercial.observacion"};
    $array["DateTimeLastModif"] = $value->{"contacto_comercial.fecha_modif"};
    $array["UserLastModification"] = $value->{"contacto_comercial.usumodif_id"};
    $array["State"] = $value->{"contacto_comercial.estado"};


    /* Agrega el contenido de `$array` al final del array `$final`. */
    array_push($final, $array);


}


/* Código que estructura una respuesta sin errores, con datos y mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna valores a un arreglo de respuesta en formato PHP. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $contactos->count[0]->{".count"};
$response["data"] = $final;
