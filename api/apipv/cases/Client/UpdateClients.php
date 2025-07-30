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
 * Client/UpdateClients
 *
 * Actualiza la información de los clientes según los filtros proporcionados.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param int $params ->Id: Identificador del cliente.
 * @param string $params ->Login: Nombre de usuario.
 * @param string $params ->IsActivate: Estado de activación ('A', 'I', 'R').
 * @param string $params ->DocumentStatus: Estado del documento ('A', 'I', 'R').
 * @param string $params ->dateFrom: Fecha inicial en formato 'Y-m-d'.
 * @param string $params ->dateTo: Fecha final en formato 'Y-m-d'.
 * @param string $params ->MinLastTimeLoginDateLocal: Fecha mínima del último inicio de sesión.
 * @param string $params ->MaxLastTimeLoginDateLocal: Fecha máxima del último inicio de sesión.
 * @param string $params ->FirstName: Primer nombre.
 * @param string $params ->LastName: Apellido.
 * @param string $params ->MiddleName: Segundo nombre.
 * @param string $params ->OriginRegistry: Origen del registro.
 * @param string $params ->DocumentNumber: Número de documento.
 * @param string $params ->Region: Región del cliente.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta.
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Información de los clientes.
 *  - pos (int): Posición inicial de los datos.
 *  - total_count (int): Total de registros encontrados.
 */


/* Asigna un usuario por defecto si la sesión está vacía, luego crea instancias de usuario. */
$userNow = $_SESSION['usuario2'];
if ($_SESSION['usuario2'] == "") {
    $userNow = 5;
}
$Usuario = new Usuario();
$UsuarioMandante = new UsuarioMandante($userNow);

/* obtiene datos JSON, los decodifica y extrae el Id. */
$Mandante = new Mandante($UsuarioMandante->getMandante());

$params = file_get_contents('php://input');
$params = json_decode($params);


$Id = $params->Id;

/* asigna parámetros a variables para su uso posterior. */
$Login = $params->Login;
$IsActivate = $params->IsActivate;
$DocumentStatus = $params->DocumentStatus;
$dateFrom = $params->dateFrom;
$dateTo = $params->dateTo;
$MinLastTimeLoginDateLocal = $params->MinLastTimeLoginDateLocal;

/* Asignación de variables a partir de parámetros de entrada, probablemente en un contexto de usuario. */
$MaxLastTimeLoginDateLocal = $params->MaxLastTimeLoginDateLocal;
$FirstName = $params->FirstName;
$LastName = $params->LastName;
$MiddleName = $params->MiddleName;
$OriginRegistry = $params->OriginRegistry;
$DocumentNumber = $params->DocumentNumber;

/* valida y asigna parámetros de una solicitud HTTP, asegurando tipos y valores. */
$Region = $params->Region;

$Id = (is_numeric($_REQUEST["Id"])) ? $_REQUEST["Id"] : '';
$Login = $_REQUEST["Login"];
$IsActivate = ($_REQUEST["IsActivate"] != "A" && $_REQUEST["IsActivate"] != "I" && $_REQUEST["IsActivate"] != "R") ? '' : $_REQUEST["IsActivate"];
$IsRegisterActivate = ($_REQUEST["IsRegisterActivate"] != "A" && $_REQUEST["IsRegisterActivate"] != "I" && $_REQUEST["IsRegisterActivate"] != "R") ? '' : $_REQUEST["IsRegisterActivate"];


/* ajusta las fechas para incluir horas mínima y máxima. */
if ($dateFrom == $dateTo && $dateFrom != "") {
    $dateFrom = $dateFrom . " 00:00:00";
    $dateTo = $dateTo . " 23:59:59";
} else {
    if ($dateFrom != "") {
        $dateFrom = $dateFrom . " 00:00:00";
    }

    if ($dateTo != "") {
        $dateTo = $dateTo . " 23:59:59";

    }

}


/* ajusta fechas para establecer rangos de tiempo específicos en registros. */
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


/* Código verifica una fecha y ajusta el formato basado en solicitudes del usuario. */
if ($_REQUEST["dateTo"] != "" && false) {
    $dateFrom = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +1 day' . $timezone . ' hour '));
}


$FromDateLocal = $params->FromCreatedDateLocal;


/* comprueba una fecha y asigna una variable según condiciones específicas. */
if ($_REQUEST["dateFrom"] != "" && false) {
    $dateTo = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


$MaxRows = $params->MaxRows;

/* asigna y verifica parámetros para paginación de items ordenados. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1;
}

if ($Mandante->propio == "S") {

    /* Se establecen reglas para filtrar datos de usuarios online y específicos. */
    $MaxRows = 15000;

    $rules = [];
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

    if ($Id != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "in"));
    }


    /* Añade reglas a un array basadas en nombres no vacíos. */
    if ($FirstName != "") {
        array_push($rules, array("field" => "registro.nombre1", "data" => "$FirstName", "op" => "cn "));

    }

    if ($MiddleName != "") {
        array_push($rules, array("field" => "registro.nombre2", "data" => "$MiddleName", "op" => "cn"));

    }


    /* Se agregan reglas a un array si los campos no están vacíos. */
    if ($LastName != "") {
        array_push($rules, array("field" => "registro.apellido1", "data" => "$LastName", "op" => "cn"));

    }


    if ($Login != "") {
        array_push($rules, array("field" => "usuario.login", "data" => "$Login", "op" => "cn"));

    }


    /* Añade reglas para filtrar fechas de creación de usuario en un array. */
    if ($dateFrom != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFrom", "op" => "ge"));

    }
    if ($dateTo != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateTo", "op" => "le"));

    }


    /* Agrega reglas de búsqueda según fechas de último inicio de sesión del usuario. */
    if ($MinLastTimeLoginDateLocal != "") {
        array_push($rules, array("field" => "usuario.fecha_ult", "data" => "$MinLastTimeLoginDateLocal", "op" => "ge"));

    }
    if ($MaxLastTimeLoginDateLocal != "") {
        array_push($rules, array("field" => "usuario.fecha_ult", "data" => "$MaxLastTimeLoginDateLocal", "op" => "le"));

    }


    /* Se agregan reglas de validación según la información del documento y estado del usuario. */
    if ($DocumentNumber != "") {
        array_push($rules, array("field" => "registro.cedula", "data" => "$DocumentNumber", "op" => "eq"));

    }

    if ($IsActivate != "" && ($IsActivate == "A" || $IsActivate == "I" || $IsActivate == "R")) {
        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
    }


    /* verifica condiciones y agrega reglas basadas en estados de registro y documento. */
    if ($IsRegisterActivate != "" && ($IsRegisterActivate == "A" || $IsRegisterActivate == "I" || $IsRegisterActivate == "R")) {
        array_push($rules, array("field" => "registro.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
    }

    if ($DocumentStatus != "" && ($DocumentStatus == "A" || $DocumentStatus == "I" || $DocumentStatus == "R")) {
        array_push($rules, array("field" => "usuario.documento_validado", "data" => "$DocumentStatus", "op" => "eq"));
    }


    /* Agrega reglas a un arreglo si se cumplen ciertas condiciones de variables. */
    if ($OriginRegistry != "" && $OriginRegistry != "null") {
        array_push($rules, array("field" => "usuario.origen", "data" => "$OriginRegistry", "op" => "cn"));

    }

    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "cn"));

    }


    /* verifica condiciones de sesión y añade reglas a un array. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global

    /* añade reglas de validación según el estado de la sesión. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Construye un filtro JSON y recupera usuarios personalizados desde una base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario.verifcedula_ant,usuario.verifcedula_post,usuario.documento_validado,usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.estado_valida,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base ", "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);

    $usuarios = json_decode($usuarios);


    /* Se declara un array vacío llamado $usuariosFinal para almacenar usuarios. */
    $usuariosFinal = [];

    foreach ($usuarios->data as $key => $value) {


        /* verifica si el usuario está inactivo y establece una variable en consecuencia. */
        $Islocked = false;

        if ($value->{"usuario.estado"} == "I") {
            $Islocked = true;
        }

        $array = [];


        /* Asigna valores de un objeto a un array asociativo en PHP. */
        $array["id"] = $value->{"usuario.usuario_id"};
        $array["Id"] = $value->{"usuario.usuario_id"};
        $array["Ip"] = $value->{"ausuario.dir_ip"};
        $array["Login"] = $value->{"usuario.login"};
        $array["Estado"] = array($value->{"usuario.estado"});
        $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};

        /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
        $array["Idioma"] = $value->{"a.idioma"};
        $array["Nombre"] = $value->{"a.nombre"};
        $array["FirstName"] = $value->{"registro.nombre1"};
        $array["MiddleName"] = $value->{"registro.nombre2"};
        $array["LastName"] = $value->{"registro.apellido1"};
        $array["Email"] = $value->{"registro.email"};

        /* Asigna valores a un array desde las propiedades de un objeto. */
        $array["Address"] = $value->{"registro.direccion"};
        $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
        $array["Intentos"] = $value->{"usuario.intentos"};
        $array["Observaciones"] = $value->{"usuario.observ"};
        $array["Moneda"] = $value->{"usuario.moneda"};

        $array["Pais"] = $value->{"usuario.pais_id"};

        /* asigna valores a un arreglo asociativo desde un objeto. */
        $array["City"] = $value->{"g.ciudad_nom"};

        $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

        $array["IsLocked"] = $Islocked;
        $array["BirthCity"] = $value->{"registro.ciudnacim_id"};

        /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
        $array["BirthDate"] = $value->{"c.fecha_nacim"};

        $array["BirthDepartment"] = $value->{"g.depto_id"};
        $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
        $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
        $array["Balance"] = $value->{"registro.creditos"} + $value->{"registro.creditos_base"};


        /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["DocNumber"] = $value->{"registro.cedula"};
        $array["Gender"] = $value->{"registro.sexo"};
        $array["Language"] = $value->{"usuario.idioma"};
        $array["Phone"] = $value->{"registro.telefono"};
        $array["MobilePhone"] = $value->{"registro.celular"};

        /* Asigna valores específicos a un array desde un objeto en PHP. */
        $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};
        $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};
        $array["Province"] = $value->{"registro.ciudad_id"};
        $array["RegionId"] = $value->{"usuario.pais_id"};
        $array["CountryName"] = $value->{"usuario.pais_id"};
        $array["ZipCode"] = $value->{"registro.codigo_postal"};

        /* asigna valores a un arreglo según propiedades específicas de un objeto `$value`. */
        $array["IsVerified"] = true;
        $array["IsActivate"] = ($value->{"usuario.estado"});
        $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});
        $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});

        $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};

        /* asigna datos de usuario a un arreglo asociado en PHP. */
        $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
        $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);


        $array["VerifdnAnt"] = ($value->{"usuario.verifcedula_ant"});
        $array["VerifdniPost"] = ($value->{"usuario.verifcedula_post"});

        /* Condiciona el DNI y lo agrega a la lista de usuarios. */
        $array["DNI"] = ($value->{"usuario.verifcedula_post"} == 'S' && $value->{"usuario.verifcedula_post"} == 'S') ? 'S' : 'N';

        array_push($usuariosFinal, $array);

    }


    /* Crea un arreglo de usuarios con detalles específicos extraídos de $usuariosFinal. */
    $dddd = array();

    foreach ($usuariosFinal as $item) {

        $ddd = array('email' => $item["Login"]
        , 'name' => $item["FirstName"]
        , 'lastname' => $item["LastName"]
        , 'mobile' => $item["MobilePhone"]
        , 'id' => $item["id"]
        , 'profile_image_url' => 'https://images.virtualsoft.tech/site/doradobet/logo-d-white.png'
        , 'dateBirth' => $item["BirthDate"]
        , 'dateCreated' => $item["CreatedLocalDate"]
        , 'country' => $item["Pais"]
        );

        array_push($dddd, $ddd);
    }


    /* Se asigna el valor de la variable `$dddd` a `$usuariosFinal`. */
    $usuariosFinal = $dddd;
} else {


    /* Se crea un objeto y se añade una regla condicional con un ID. */
    $UsuarioMandante = new UsuarioMandante();
    $rules = [];

    if ($Id != "") {
        array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$Id", "op" => "in"));
    }
    // Si el usuario esta condicionado por País

    /* agrega reglas basadas en condiciones del usuario y sesión. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Agrega una regla de validación si "mandanteLista" tiene un valor válido. */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    /* Se añaden reglas de filtrado y se obtienen usuarios mandantes personalizados en JSON. */
    array_push($rules, array("field" => "usuario_mandante.propio", "data" => "N", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $usuarios = $UsuarioMandante->getUsuariosMandantesCustom(" usuario_mandante.* ", "usuario_mandante.usumandante_id", "asc", $SkeepRows, $MaxRows, $json, true);


    /* decodifica datos JSON y inicializa un array vacío para usuarios. */
    $usuarios = json_decode($usuarios);

    $usuariosFinal = [];

    foreach ($usuarios->data as $key => $value) {


        /* inicializa un array con el ID del usuario mandante. */
        $Islocked = false;

        $array = [];

        $array["id"] = $value->{"usuario_mandante.usuario_mandante"};
        $array["Id"] = $value->{"usuario_mandante.usuario_mandante"};

        /* Extrae y asigna datos específicos de un objeto a un array asociativo. */
        $array["Ip"] = $value->{"ausuario.dir_ip"};
        //$array["Login"] = $value->{"usuario.login"};
        // $array["Estado"] = array($value->{"usuario.estado"});
        // $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
        //  $array["Idioma"] = $value->{"a.idioma"};
        $array["Name"] = $value->{"usuario_mandante.nombres"};
        // $array["FirstName"] = $value->{"usuario_mandante.nombre"};
        // $array["MiddleName"] = $value->{"registro.nombre2"};

        /* Se están almacenando datos de usuarios en un arreglo para su posterior uso. */
        $array["LastName"] = $value->{"usuario_mandante.apellidos"};
        $array["Email"] = $value->{"usuario_mandante.email"};
        $array["Currency"] = $value->{"usuario_mandante.moneda"};
        $array["CreatedLocalDate"] = $value->{"usuario_mandante.fecha_crea"};

        array_push($usuariosFinal, $array);

    }

}


/* crea una respuesta estructurada con información de éxito y datos de usuarios. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "Objects" => $usuariosFinal,
    "Count" => $usuarios->count[0]->{".count"},

);


/* asigna datos de usuarios y totales a un arreglo de respuesta. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $usuarios->count[0]->{".count"};
$response["data"] = $usuariosFinal;
