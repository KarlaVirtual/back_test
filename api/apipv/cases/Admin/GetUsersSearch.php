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
 * Admin/GetUsersSearch
 *
 * Obtener los usuarios registrados por unos filtros
 *
 * @param object $params Objeto con los parámetros de búsqueda:
 * @param int $params ->Id ID del usuario
 * @param string $params ->Login Login del usuario
 * @param string $params ->IsActivate Estado de activación del usuario
 * @param string $params ->DocumentStatus Estado del documento del usuario
 * @param string $params ->dateFrom Fecha de inicio
 * @param string $params ->dateTo Fecha de fin
 * @param string $params ->MinLastTimeLoginDateLocal Fecha mínima del último login
 * @param string $params ->MaxLastTimeLoginDateLocal Fecha máxima del último login
 * @param string $params ->FirstName Primer nombre del usuario
 * @param string $params ->LastName Apellido del usuario
 * @param string $params ->MiddleName Segundo nombre del usuario
 * @param string $params ->OriginRegistry Registro de origen del usuario
 * @param string $params ->DocumentNumber Número de documento del usuario
 * @param string $params ->Region Región del usuario
 * @param int $params ->MaxRows Número máximo de filas
 * @param int $params ->OrderedItem Elemento ordenado
 * @param int $params ->SkeepRows Número de filas a omitir
 * @param string $params ->FromCreatedDateLocal Fecha de creación desde
 *
 * @return array Respuesta del servicio:
 * - bool $response["HasError"] Indica si hubo un error
 * - string $response["AlertType"] Tipo de alerta generada
 * - string $response["AlertMessage"] Mensaje de alerta generado
 * - array $response["ModelErrors"] Errores del modelo si los hay
 * - array $response["Data"] Datos de los usuarios
 * - int $response["pos"] Posición de inicio
 * - int $response["total_count"] Conteo total de usuarios
 * - array $response["data"] Datos de los usuarios filtrados
 */


/* Se crean instancias de usuario y mandante, y se procesan parámetros JSON de entrada. */
$Usuario = new Usuario();
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$Mandante = new Mandante($UsuarioMandante->getMandante());

$params = file_get_contents('php://input');
$params = json_decode($params);


/* asigna valores de parámetros a variables para su uso posterior. */
$Id = $params->Id;
$Login = $params->Login;
$IsActivate = $params->IsActivate;
$DocumentStatus = $params->DocumentStatus;
$dateFrom = $params->dateFrom;
$dateTo = $params->dateTo;

/* Se asignan parámetros de entrada a variables para su posterior uso. */
$MinLastTimeLoginDateLocal = $params->MinLastTimeLoginDateLocal;
$MaxLastTimeLoginDateLocal = $params->MaxLastTimeLoginDateLocal;
$FirstName = $params->FirstName;
$LastName = $params->LastName;
$MiddleName = $params->MiddleName;
$OriginRegistry = $params->OriginRegistry;

/* asigna parámetros y valida datos de entrada en una solicitud. */
$DocumentNumber = $params->DocumentNumber;
$Region = $params->Region;

$Id = (is_numeric($_REQUEST["Id"])) ? $_REQUEST["Id"] : '';
$Login = $_REQUEST["Login"];
$IsActivate = ($_REQUEST["IsActivate"] != "A" && $_REQUEST["IsActivate"] != "I" && $_REQUEST["IsActivate"] != "R") ? '' : $_REQUEST["IsActivate"];

/* Evalúa un parámetro de registro y ajusta las fechas a un rango diario. */
$IsRegisterActivate = ($_REQUEST["IsRegisterActivate"] != "A" && $_REQUEST["IsRegisterActivate"] != "I" && $_REQUEST["IsRegisterActivate"] != "R") ? '' : $_REQUEST["IsRegisterActivate"];

if ($dateFrom == $dateTo && $dateFrom != "") {
    $dateFrom = $dateFrom . " 00:00:00";
    $dateTo = $dateTo . " 23:59:59";
} else {
    /* ajusta las fechas añadiendo horas para límites de tiempo específicos. */

    if ($dateFrom != "") {
        $dateFrom = $dateFrom . " 00:00:00";
    }

    if ($dateTo != "") {
        $dateTo = $dateTo . " 23:59:59";

    }

}


/* ajusta fechas de inicio y fin para la comparación de registros de login. */
if ($MinLastTimeLoginDateLocal == $MaxLastTimeLoginDateLocal && $MinLastTimeLoginDateLocal != "") {
    $MinLastTimeLoginDateLocal = $MinLastTimeLoginDateLocal . " 00:00:00";
    $MaxLastTimeLoginDateLocal = $MaxLastTimeLoginDateLocal . " 23:59:59";
} else {
    if ($MinLastTimeLoginDateLocal != "") {
        $MinLastTimeLoginDateLocal = $MinLastTimeLoginDateLocal . " 00:00:00";

    }
    if ($MaxLastTimeLoginDateLocal != "") {

        $MaxLastTimeLoginDateLocal = $MaxLastTimeLoginDateLocal . " 00:00:00";
    }
}


/* Verifica una fecha en la solicitud y ajusta un formato de fecha específico. */
if ($_REQUEST["dateTo"] != "" && false) {
    $dateFrom = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +1 day' . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;


/* Condicional que verifica "dateFrom" y establece "dateTo" según la zona horaria. */
if ($_REQUEST["dateFrom"] != "" && false) {
    $dateTo = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


$MaxRows = $params->MaxRows;

/* asigna y ajusta parámetros de orden y filas de una solicitud. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Inicializa $OrderedItem y $MaxRows si están vacíos, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100;
}

if ($Mandante->propio == "S") {


    /* genera reglas de filtrado basadas en variables de entrada. */
    $rules = [];

    if ($Id != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "in"));
    }

    if ($FirstName != "") {
        array_push($rules, array("field" => "registro.nombre1", "data" => "$FirstName", "op" => "cn "));

    }


    /* Agrega reglas de validación para nombres y apellidos si no están vacíos. */
    if ($MiddleName != "") {
        array_push($rules, array("field" => "registro.nombre2", "data" => "$MiddleName", "op" => "cn"));

    }


    if ($LastName != "") {
        array_push($rules, array("field" => "registro.apellido1", "data" => "$LastName", "op" => "cn"));

    }


    /* añade reglas basadas en las variables de login y fecha. */
    if ($Login != "") {
        array_push($rules, array("field" => "usuario.login", "data" => "$Login", "op" => "cn"));

    }


    if ($dateFrom != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFrom", "op" => "ge"));

    }

    /* Condiciona la adición de reglas basadas en fechas específicas en un arreglo. */
    if ($dateTo != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateTo", "op" => "le"));

    }

    if ($MinLastTimeLoginDateLocal != "") {
        array_push($rules, array("field" => "usuario.fecha_ult", "data" => "$MinLastTimeLoginDateLocal", "op" => "ge"));

    }

    /* Se añaden reglas a un array según condiciones de fecha y documento no vacío. */
    if ($MaxLastTimeLoginDateLocal != "") {
        array_push($rules, array("field" => "usuario.fecha_ult", "data" => "$MaxLastTimeLoginDateLocal", "op" => "le"));

    }


    if ($DocumentNumber != "") {
        array_push($rules, array("field" => "registro.cedula", "data" => "$DocumentNumber", "op" => "eq"));

    }


    /* valida y añade reglas basadas en los estados de activación. */
    if ($IsActivate != "" && ($IsActivate == "A" || $IsActivate == "I" || $IsActivate == "R")) {
        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
    }

    if ($IsRegisterActivate != "" && ($IsRegisterActivate == "A" || $IsRegisterActivate == "I" || $IsRegisterActivate == "R")) {
        array_push($rules, array("field" => "registro.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
    }


    /* Añade reglas basadas en el estado del documento y el registro de origen. */
    if ($DocumentStatus != "" && ($DocumentStatus == "A" || $DocumentStatus == "I" || $DocumentStatus == "R")) {
        array_push($rules, array("field" => "usuario.documento_validado", "data" => "$DocumentStatus", "op" => "eq"));
    }

    if ($OriginRegistry != "" && $OriginRegistry != "null") {
        array_push($rules, array("field" => "usuario.origen", "data" => "$OriginRegistry", "op" => "cn"));

    }


    /* Añade reglas de filtrado según región y perfil de usuario en un array. */
    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "cn"));

    }

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    }

    // Si el usuario esta condicionado por País

    /* Añade reglas a un array según condiciones de sesión de usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* verifica condiciones y agrega reglas basadas en valores de sesión. */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Agrega reglas de filtrado a un arreglo según condiciones definidas por el usuario. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "ne"));

    if ($_REQUEST["filter"]["value"] != "") {
        array_push($rules, array("field" => "usuario.login", "data" => $_REQUEST["filter"]["value"], "op" => "cn"));

    }


    /* Filtro de usuarios personalizados en JSON y consulta a la base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario.verifcedula_ant,usuario.verifcedula_post,usuario.documento_validado,usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.estado_valida,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base ", "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);

    $usuarios = json_decode($usuarios);


    /* Crea un array vacío llamado usuariosFinal para almacenar datos de usuarios. */
    $usuariosFinal = [];

    foreach ($usuarios->data as $key => $value) {


        /* verifica si un usuario está inactivo y establece una variable. */
        $Islocked = false;

        if ($value->{"usuario.estado"} == "I") {
            $Islocked = true;
        }

        $array = [];


        /* asigna valores a un array desde un objeto "usuario". */
        $array["id"] = $value->{"usuario.usuario_id"};
        $array["Id"] = $value->{"usuario.usuario_id"};
        $array["Ip"] = $value->{"ausuario.dir_ip"};
        $array["Login"] = $value->{"usuario.login"};
        $array["Estado"] = array($value->{"usuario.estado"});
        $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};

        /* Asignación de valores a un array desde un objeto en PHP. */
        $array["Idioma"] = $value->{"a.idioma"};
        $array["Nombre"] = $value->{"a.nombre"};
        $array["FirstName"] = $value->{"registro.nombre1"};
        $array["MiddleName"] = $value->{"registro.nombre2"};
        $array["LastName"] = $value->{"registro.apellido1"};
        $array["Email"] = $value->{"registro.email"};

        /* Se asignan valores a un arreglo utilizando propiedades de un objeto PHP. */
        $array["Address"] = $value->{"registro.direccion"};
        $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
        $array["Intentos"] = $value->{"usuario.intentos"};
        $array["Observaciones"] = $value->{"usuario.observ"};
        $array["Moneda"] = $value->{"usuario.moneda"};

        $array["Pais"] = $value->{"usuario.pais_id"};

        /* Asigna valores a un array desde un objeto, incluyendo ciudad, fecha y estado. */
        $array["City"] = $value->{"g.ciudad_nom"};

        $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

        $array["IsLocked"] = $Islocked;
        $array["BirthCity"] = $value->{"registro.ciudnacim_id"};

        /* Asigna valores de un objeto a un array, incluyendo datos personales y balances. */
        $array["BirthDate"] = $value->{"c.fecha_nacim"};

        $array["BirthDepartment"] = $value->{"g.depto_id"};
        $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
        $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
        $array["Balance"] = $value->{"registro.creditos"} + $value->{"registro.creditos_base"};


        /* asigna valores de un objeto a un array asociativo. */
        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["DocNumber"] = $value->{"registro.cedula"};
        $array["Gender"] = $value->{"registro.sexo"};
        $array["Language"] = $value->{"usuario.idioma"};
        $array["Phone"] = $value->{"registro.telefono"};
        $array["MobilePhone"] = $value->{"registro.celular"};

        /* Asigna valores a un array desde un objeto con datos de usuarios y registro. */
        $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};
        $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};
        $array["Province"] = $value->{"registro.ciudad_id"};
        $array["RegionId"] = $value->{"usuario.pais_id"};
        $array["CountryName"] = $value->{"usuario.pais_id"};
        $array["ZipCode"] = $value->{"registro.codigo_postal"};

        /* asigna estados de verificación y activación a un array. */
        $array["IsVerified"] = true;
        $array["IsActivate"] = ($value->{"usuario.estado"});
        $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});
        $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});

        $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};

        /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
        $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
        $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);


        $array["VerifdnAnt"] = ($value->{"usuario.verifcedula_ant"});
        $array["VerifdniPost"] = ($value->{"usuario.verifcedula_post"});

        /* Verifica condición y asigna 'S' o 'N' al DNI, luego lo agrega al array. */
        $array["DNI"] = ($value->{"usuario.verifcedula_post"} == 'S' && $value->{"usuario.verifcedula_post"} == 'S') ? 'S' : 'N';

        array_push($usuariosFinal, $array);

    }
} else {


    /* Se crea un objeto y se añaden reglas si el ID no está vacío. */
    $UsuarioMandante = new UsuarioMandante();
    $rules = [];

    if ($Id != "") {
        array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$Id", "op" => "in"));
    }
    // Si el usuario esta condicionado por País

    /* agrega reglas basadas en condiciones del usuario y sesión actual. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }

    /* Se crean reglas de filtrado y se obtienen usuarios mandantes personalizados en JSON. */
    array_push($rules, array("field" => "usuario_mandante.propio", "data" => "N", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $usuarios = $UsuarioMandante->getUsuariosMandantesCustom(" usuario_mandante.* ", "usuario_mandante.usumandante_id", "asc", $SkeepRows, $MaxRows, $json, true);


    /* Se decodifica un JSON de usuarios y se inicializa un array vacío. */
    $usuarios = json_decode($usuarios);

    $usuariosFinal = [];

    foreach ($usuarios->data as $key => $value) {


        /* Código que inicializa un estado y crea un array con datos de usuario. */
        $Islocked = false;

        $array = [];

        $array["id"] = $value->{"usuario_mandante.usuario_mandante"};
        $array["Id"] = $value->{"usuario_mandante.usuario_mandante"};

        /* Asignación de valores de propiedades a un array en PHP. */
        $array["Ip"] = $value->{"ausuario.dir_ip"};
        //$array["Login"] = $value->{"usuario.login"};
        // $array["Estado"] = array($value->{"usuario.estado"});
        // $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
        //  $array["Idioma"] = $value->{"a.idioma"};
        $array["Name"] = $value->{"usuario_mandante.nombres"};
        // $array["FirstName"] = $value->{"usuario_mandante.nombre"};
        // $array["MiddleName"] = $value->{"registro.nombre2"};

        /* asigna datos de un objeto a un array y lo agrega a otro array. */
        $array["LastName"] = $value->{"usuario_mandante.apellidos"};
        $array["Email"] = $value->{"usuario_mandante.email"};
        $array["Currency"] = $value->{"usuario_mandante.moneda"};
        $array["CreatedLocalDate"] = $value->{"usuario_mandante.fecha_crea"};

        array_push($usuariosFinal, $array);

    }

}


/* prepara una respuesta estructurada con información sobre usuarios y errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "Objects" => $usuariosFinal,
    "Count" => oldCount($usuariosFinal),

);


/* filtra y estructura datos de usuarios para una respuesta JSON. */
$response["pos"] = $SkeepRows;
$response["total_count"] = oldCount($usuariosFinal);
$response["data"] = $usuariosFinal;

if ($_REQUEST["filter"]["value"] != "") {
    $usuariosFinal2 = array();
    foreach ($usuariosFinal as $item) {
        $array = [];

        $array["id"] = $item["id"];
        $array["value"] = $item["Login"];
        array_push($usuariosFinal2, $array);


    }
    $response["data"] = $usuariosFinal2;

}
