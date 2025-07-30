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
 * vapi/GetClients
 *
 * Consulta de Usuarios
 *
 * Recupera los usuarios registrados y sus detalles asociados basados en los parámetros de búsqueda proporcionados.
 * Permite filtrar por identificador de usuario, estado, perfil y otros criterios relevantes.
 *
 * @param string $Id : Identificador del usuario a buscar.
 * @param string $Login : Nombre de usuario para realizar la búsqueda.
 * @param string $IsActivate : Estado de activación del usuario (A para activado, I para inactivado, R para registrado).
 * @param int $MaxRows : Número máximo de registros a recuperar (por defecto 5000).
 * @param int $OrderedItem : Criterio de ordenamiento (por defecto 1).
 * @param int $SkeepRows : Número de filas a omitir en la consulta (por defecto 0).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si ocurrió un error en la operación.
 *  - *AlertType* (string): Tipo de alerta que se generó.
 *  - *AlertMessage* (string): Mensaje de alerta que se muestra al usuario.
 *  - *ModelErrors* (array): Lista de errores del modelo (vacío si no hay errores).
 *  - *items* (array): Lista de usuarios encontrados con sus datos detallados.
 *  - *total* (int): Número total de usuarios encontrados según los criterios de búsqueda.
 *
 *
 * @throws Exception Si ocurre un error en la consulta o procesamiento de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* crea objetos de usuario y obtiene un ID de datos JSON. */
$Usuario = new Usuario();
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$params = file_get_contents('php://input');
$params = json_decode($params);


$Id = $params->Id;

/* Se definen variables a partir de los parámetros proporcionados en la solicitud. */
$Login = $params->Login;
$IsActivate = $params->IsActivate;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;


/* establece valores predeterminados para $SkeepRows y $OrderedItem si están vacíos. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un límite máximo de filas y define reglas para validaciones. */
if ($MaxRows == "") {
    $MaxRows = 5000;
}

$rules = [];
array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


/* Añade reglas a un array según condiciones de Id y Login. */
if ($Id != "") {
    array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "in"));
}

if ($Login != "") {
    array_push($rules, array("field" => "usuario.login", "data" => "$Login", "op" => "eq"));

}


/* valida condiciones y agrega reglas a un arreglo en función de variables. */
if ($IsActivate != "" && ($IsActivate == "A" || $IsActivate == "I" || $IsActivate == "R")) {
    array_push($rules, array("field" => "registro.estado_valida", "data" => "$IsActivate", "op" => "eq"));
}

if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

}



/* Se filtran y obtienen usuarios de una base de datos, convirtiendo resultados a JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.estado_valida,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

$usuarios = json_decode($usuarios);


/* Se inicializa un array vacío llamado $usuariosFinal para almacenar usuarios. */
$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {


/* verifica si un usuario está inactivo y lo bloquea. */
    $Islocked = false;

    if ($value->{"usuario.estado"} == "I") {
        $Islocked = true;
    }

    $array = [];


/* asigna valores de un objeto a un array, incluyendo estado y fecha. */
    $array["id"] = $value->{"usuario.usuario_id"};
    $array["dirip"] = $value->{"ausuario.dir_ip"};
    $array["login"] = $value->{"usuario.login"};
    $array["status"] = ($value->{"usuario.estado"} == "A") ? "activated" : "inactivated";
    $array["statusRegister"] = ($value->{"registro.estado_valida"} == "S") ? "activated" : "inactivated";
    $array["timestampCreate"] = strtotime($value->{"usuario.fecha_crea"});

/* Convierte datos de un objeto a un array con información del usuario. */
    $array["timestampUlt"] = strtotime($value->{"usuario.fecha_crea"});

    $array["firstname"] = $value->{"registro.nombre1"};
    $array["middlename"] = $value->{"registro.nombre2"};
    $array["lastname"] = $value->{"registro.apellido1"};
    $array["email"] = $value->{"registro.email"};


/* asigna valores específicos de un objeto a un array asociativo. */
    $array["address"] = $value->{"registro.direccion"};
    $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
    $array["Intentos"] = $value->{"usuario.intentos"};
    $array["Observaciones"] = $value->{"usuario.observ"};
    $array["Moneda"] = $value->{"usuario.moneda"};

    $array["Pais"] = $value->{"usuario.pais_id"};

/* Se asignan valores a un array asociativo para almacenar información de una persona. */
    $array["City"] = $value->{"g.ciudad_nom"};


    $array["IsLocked"] = $Islocked;
    $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
    $array["BirthDate"] = $value->{"c.fecha_nacim"};


/* Asigna valores del objeto $value a claves específicas en el array $array. */
    $array["BirthDepartment"] = $value->{"g.depto_id"};
    $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
    $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
    $array["Balance"] = $value->{"registro.creditos"} + $value->{"registro.creditos_base"};

    $array["CurrencyId"] = $value->{"usuario.moneda"};

/* asigna valores de un objeto a un array asociativo en PHP. */
    $array["DocNumber"] = $value->{"registro.cedula"};
    $array["Gender"] = $value->{"registro.sexo"};
    $array["Language"] = $value->{"usuario.idioma"};
    $array["Phone"] = $value->{"registro.telefono"};
    $array["MobilePhone"] = $value->{"registro.celular"};
    $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};

/* Asigna valores a un array basado en propiedades de un objeto $value. */
    $array["Province"] = $value->{"registro.ciudad_id"};
    $array["RegionId"] = $value->{"usuario.pais_id"};
    $array["CountryName"] = $value->{"usuario.pais_id"};
    $array["ZipCode"] = $value->{"registro.codigo_postal"};
    $array["IsVerified"] = true;

    $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};

/* Se asignan valores a un array y se añaden a otro array final. */
    $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
    $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);

    array_push($usuariosFinal, $array);

}


/* Código que configura una respuesta con éxito y datos de usuarios. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response = array(
    "items" => $usuariosFinal,
    "total" => $usuarios->count[0]->{".count"},

);
