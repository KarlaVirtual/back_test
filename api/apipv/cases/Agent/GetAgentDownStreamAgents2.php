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
 * Agent/GetAgentDownStreamAgents
 *
 * Obtener la red de los agentes con los puntos de venta
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud:
 * @param string $params ->RoleId ID del perfil de usuario.
 * @param string $params ->Login Nombre de usuario.
 * @param string $params ->UserId ID de usuario.
 * @param string $params ->BetShopId ID de la tienda de apuestas.
 * @param int $params ->MaxRows Número máximo de filas a recuperar.
 * @param string $params ->OrderedItem Elemento por el cual se ordenarán los resultados.
 * @param int $params ->SkeepRows Número de filas a omitir.
 *
 *
 * @return array $response Respuesta de la solicitud:
 *                         - bool $response["HasError"] Indica si hubo un error.
 *                         - string $response["AlertType"] Tipo de alerta.
 *                         - string $response["AlertMessage"] Mensaje de alerta.
 *                         - array $response["ModelErrors"] Errores del modelo.
 *                         - array $response["Data"] Datos de la respuesta:
 *                           - array $response["Data"]["Children"] Hijos descendientes.
 *                           - int $response["Data"]["DownStreamChildrenCount"] Conteo de hijos descendientes.
 *                           - float $response["Data"]["DownStreamChildrenBalanceSum"] Suma de balances de hijos descendientes.
 *                           - int $response["Data"]["DownStreamPlayerCount"] Conteo de jugadores descendientes.
 *                           - float $response["Data"]["DownStreamPlayerBalanceSum"] Suma de balances de jugadores descendientes.
 */


/* Se crean objetos de usuario y se obtienen datos de perfil desde parámetros GET. */
$UsuarioPerfil = new UsuarioPerfil();
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Perfil_id = $_GET["roleId"];
$Login = $_GET["Login"];
$UserId = $_GET["UserId"];


/* Código que obtiene parámetros de una solicitud GET y establece valores predeterminados. */
$BetShopId = $_GET["BetShopId"];

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores por defecto si las variables están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000000;
}


/* Código que configura reglas para obtener usuarios según perfil "CONCESIONARIO". */
$mismenus = "0";

$rules = [];


if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    if ($Login != "") {
        array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

    }


    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

    /* Agregar reglas de validación para datos del concesionario y usuario en un arreglo. */
    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

    if ($Login != "") {
        array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

    }


    /* Verifica si $UserId no está vacío y agrega una regla de filtrado. */
    if ($UserId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* codifica un filtro en JSON y obtiene perfiles de usuario personalizados. */
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {

    /* Se agregan reglas de validación para diferentes campos y condiciones de usuario. */
    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

    if ($Login != "") {
        array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

    }


    /* Se añade una regla de filtro basada en el ID de usuario no vacío. */
    if ($UserId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y obtiene perfiles de usuario personalizados. */
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

} else {

    /* Agrega reglas de validación para usuarios según perfil y estado, considerando condiciones por país. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global

    /* define reglas de acceso según la sesión del usuario. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Se crea un filtro JSON para obtener perfiles de usuarios personalizados desde una base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);


    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom("usuario.mandante, usuario.usuario_id,usuario.moneda,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

}


/* decodifica datos JSON y inicializa un array y un balance. */
$usuarios = json_decode($usuarios);
$arrayf = [];

$balanceAgent = 0;

foreach ($usuarios->data as $key => $value) {

    /* crea un array asociativo con datos del usuario extraídos de un objeto. */
    $array = [];

    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["UserId"] = $value->{"usuario.usuario_id"};
    $array["UserName"] = $value->{"usuario.nombre"};
    $array["Country"] = $value->{"usuario.mandante"} . '-' . $value->{"pais.pais_nom"};

    /* asigna valores a un array a partir de un objeto. */
    $array["Currency"] = $value->{"usuario.moneda"};
    $array["Name"] = $value->{"usuario.nombre"};
    $array["SystemName"] = 22;
    $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
    $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};
    $array["AgentBalance2"] = $value->{"punto_venta.cupo_recarga"};

    /* asigna valores a un array basado en propiedades de un objeto. */
    $array["Partner"] = $value->{"usuario.mandante"};
    $array["PlayerCount"] = 0;
    $array["Children"] = array();
    $array["data"] = array();

    $array["flag"] = strtolower($value->{"pais.iso"});

    /* Asigna iconos a un array basado en el perfil de usuario. */
    switch ($value->{"usuario_perfil.perfil_id"}) {
        case "CONCESIONARIO":
            $array["icon"] = "icon-user-secret";
            break;
        case "CONCESIONARIO2":
            $array["icon"] = "icon-user-secret";
            break;
        case "CONCESIONARIO3":
            $array["icon"] = "icon-user-secret";
            break;
        case "PUNTOVENTA":
            $array["icon"] = "icon-shop";
            break;
    }

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

        /* Se crean reglas de validación para filtrar concesionarios y usuarios en un array. */
        $rules2 = array();

        array_push($rules2, array("field" => "concesionario.usupadre2_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
        array_push($rules2, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));


        /* Agrega reglas de filtrado basadas en login y ID de usuario si no están vacíos. */
        if ($Login != "") {
            array_push($rules2, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

        }

        if ($UserId != "") {
            array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

        }


        /* Se agrega una regla de filtro si $BetShopId no está vacío. */
        if ($BetShopId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

        }

        $filtro = array("rules" => $rules2, "groupOp" => "AND");


        /* convierte un filtro a JSON y recupera perfiles de usuarios personalizados. */
        $json2 = json_encode($filtro);

        $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);
        $usuariosdetalle = json_decode($usuariosdetalle);


        foreach ($usuariosdetalle->data as $key2 => $value2) {

            /* crea un array asociativo con datos del usuario procesados de un objeto. */
            $array2 = [];

            $array2["UserName"] = $value2->{"usuario.nombre"};
            $array2["Currency"] = $value2->{"usuario.moneda"};
            $array2["Country"] = $value2->{"usuario.mandante"} . '-' . $value2->{"pais.pais_nom"};

            $array2["Name"] = $value2->{"usuario.nombre"};

            /* Asignación de valores en un array según el estado del usuario y balances del agente. */
            $array2["SystemName"] = 22;
            $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
            $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
            $array2["AgentBalance2"] = $value2->{"punto_venta.cupo_recarga"};
            $array2["PlayerCount"] = 0;
            $array2["Id"] = $value2->{"usuario.usuario_id"};

            /* Asignación de valores a un array y selección de icono según perfil de usuario. */
            $array2["UserId"] = $value2->{"usuario.usuario_id"};
            $array2["Partner"] = $value->{"usuario.mandante"};

            $array2["flag"] = strtolower($value2->{"pais.iso"});
            switch ($value2->{"usuario_perfil.perfil_id"}) {
                case "CONCESIONARIO":
                    $array2["icon"] = "icon-user-secret";
                    break;
                case "CONCESIONARIO2":
                    $array2["icon"] = "icon-user-secret";
                    break;
                case "CONCESIONARIO3":
                    $array2["icon"] = "icon-user-secret";
                    break;
                case "PUNTOVENTA":
                    $array2["icon"] = "icon-shop";
                    break;
            }


            /* Agrega $array2 al final de las claves "Children" y "data" de $array. */
            array_push($array["Children"], $array2);
            array_push($array["data"], $array2);


        }


    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        /* Condición que verifica si el perfil de usuario es "CONCESIONARIO2" en PHP. */


    } else {

        /* Se definen reglas de validación para concesionarios y usuarios en un array. */
        $rules2 = array();

        array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
        array_push($rules2, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

        /* Se prepara un filtro en JSON para obtener detalles de usuarios personalizados. */
        $filtro = array("rules" => $rules2, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);
        $usuariosdetalle = json_decode($usuariosdetalle);

        foreach ($usuariosdetalle->data as $key2 => $value2) {

            /* Crea un arreglo asociativo con datos de usuario y su localización. */
            $array2 = [];

            $array2["UserName"] = $value2->{"usuario.nombre"};
            $array2["Currency"] = $value2->{"usuario.moneda"};
            $array2["Country"] = $value2->{"usuario.mandante"} . '-' . $value2->{"pais.pais_nom"};
            $array2["Name"] = $value2->{"usuario.nombre"};

            /* asigna valores a un arreglo desde un objeto, incluyendo estado y saldo. */
            $array2["Id"] = $value2->{"usuario.usuario_id"};
            $array2["UserId"] = $value2->{"usuario.usuario_id"};

            $array2["SystemName"] = 22;
            $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
            $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};

            /* asigna valores a un array utilizando datos de otros objetos. */
            $array2["AgentBalance2"] = $value2->{"punto_venta.cupo_recarga"};
            $array2["PlayerCount"] = 0;
            $array2["Children"] = array();
            $array2["data"] = array();
            $array2["Partner"] = $value->{"usuario.mandante"};

            $array2["flag"] = strtolower($value2->{"pais.iso"});

            /* asigna íconos a perfiles de usuario según su identificación. */
            switch ($value2->{"usuario_perfil.perfil_id"}) {
                case "CONCESIONARIO":
                    $array2["icon"] = "icon-user-secret";
                    break;
                case "CONCESIONARIO2":
                    $array2["icon"] = "icon-user-secret";
                    break;
                case "CONCESIONARIO3":
                    $array2["icon"] = "icon-user-secret";
                    break;
                case "PUNTOVENTA":
                    $array2["icon"] = "icon-shop";
                    break;
            }

            if (true) {

                /* Se definen reglas para validar condiciones sobre concesionarios y usuarios. */
                $rules3 = array();

                array_push($rules3, array("field" => "concesionario.usupadre2_id", "data" => $value2->{"usuario.usuario_id"}, "op" => "eq"));
                array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));


                /* Se agregan reglas a un array según condiciones de Login y UserId. */
                if ($Login != "") {
                    array_push($rules2, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

                }

                if ($UserId != "") {
                    array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

                }


                /* valida un ID y agrega reglas de filtrado a un arreglo. */
                if ($BetShopId != "") {
                    array_push($rules, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

                }

                $filtro = array("rules" => $rules3, "groupOp" => "AND");

                /* Convierte un filtro a JSON y obtiene detalles de usuarios para paginación. */
                $json3 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
                $usuariosdetalle = json_decode($usuariosdetalle);


                foreach ($usuariosdetalle->data as $key3 => $value3) {

                    /* crea un array con información del usuario desde un objeto. */
                    $array3 = [];
                    $array3["Id"] = $value3->{"usuario.usuario_id"};
                    $array3["UserId"] = $value3->{"usuario.usuario_id"};
                    $array3["UserName"] = $value3->{"usuario.nombre"};
                    $array3["Currency"] = $value3->{"usuario.moneda"};
                    $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};

                    /* Asigna valores a un array con datos de usuario y estado del sistema. */
                    $array3["Name"] = $value3->{"usuario.nombre"};
                    $array3["SystemName"] = 22;
                    $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                    $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                    $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};
                    $array3["PlayerCount"] = 0;

                    /* Se asignan valores a un array según condiciones de tipo de usuario. */
                    $array3["Partner"] = $value->{"usuario.mandante"};

                    $array3["flag"] = strtolower($value3->{"pais.iso"});
                    switch ($value3->{"usuario_perfil.perfil_id"}) {
                        case "CONCESIONARIO":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "CONCESIONARIO2":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "CONCESIONARIO3":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "PUNTOVENTA":
                            $array3["icon"] = "icon-shop";
                            break;
                    }


                    /* Agrega el contenido de $array3 a las claves "Children" y "data" de $array2. */
                    array_push($array2["Children"], $array3);
                    array_push($array2["data"], $array3);


                }

            }


            /* Agrega $array2 a los arrays "Children" y "data" de $array. */
            array_push($array["Children"], $array2);
            array_push($array["data"], $array2);


        }


        /* Se generan reglas de comparación para filtrado de datos en un sistema. */
        $rules4 = array();

        array_push($rules4, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
        array_push($rules4, array("field" => "concesionario.usupadre2_id", "data" => 0, "op" => "eq"));
        array_push($rules4, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules4, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

        /* Agrega condiciones a un array de reglas basadas en estado y login del usuario. */
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

        if ($Login != "") {
            array_push($rules4, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

        }


        /* Se crea un filtro de reglas para usuarios basado en el ID proporcionado. */
        if ($UserId != "") {
            array_push($rules4, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

        }

        $filtro4 = array("rules" => $rules4, "groupOp" => "AND");

        /* Se codifica un filtro a JSON y se obtienen detalles de usuarios. */
        $json4 = json_encode($filtro4);

        $usuariosdetalle4 = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json4, true);
        $usuariosdetalle4 = json_decode($usuariosdetalle4);


        foreach ($usuariosdetalle4->data as $key4 => $value4) {

            /* crea un array con información del usuario extraída de un objeto. */
            $array4 = [];
            $array4["Id"] = $value4->{"usuario.usuario_id"};
            $array4["UserId"] = $value4->{"usuario.usuario_id"};
            $array4["UserName"] = $value4->{"usuario.nombre"};
            $array4["Currency"] = $value4->{"usuario.moneda"};
            $array4["Country"] = $value4->{"usuario.mandante"} . '-' . $value4->{"pais.pais_nom"};

            /* Código que asigna propiedades a un array basado en datos de un objeto. */
            $array4["Name"] = $value4->{"usuario.nombre"};
            $array4["SystemName"] = 22;
            $array4["IsSuspended"] = ($value4->{"usuario.estado"} == 'A' ? false : true);
            $array4["AgentBalance"] = $value4->{"punto_venta.creditos_base"};
            $array4["AgentBalance2"] = $value4->{"punto_venta.cupo_recarga"};
            $array4["PlayerCount"] = 0;


            /* Asigna valores a un array según el perfil de usuario y país. */
            $array4["Partner"] = $value->{"usuario.mandante"};
            $array4["flag"] = strtolower($value4->{"pais.iso"});
            switch ($value4->{"usuario_perfil.perfil_id"}) {
                case "CONCESIONARIO":
                    $array4["icon"] = "icon-user-secret";
                    break;
                case "CONCESIONARIO2":
                    $array4["icon"] = "icon-user-secret";
                    break;
                case "CONCESIONARIO3":
                    $array4["icon"] = "icon-user-secret";
                    break;
                case "PUNTOVENTA":
                    $array4["icon"] = "icon-shop";
                    break;
            }

            /* Agrega el contenido de $array4 a las claves "Children" y "data" de $array. */
            array_push($array["Children"], $array4);
            array_push($array["data"], $array4);

        }


    }


    /* Agrega un elemento a un array y actualiza el saldo del agente. */
    array_push($arrayf, $array);

    $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
}


/* Se inicializa un arreglo vacío llamado "rules" para almacenar reglas o condiciones. */
$rules = [];


if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

    /* Se agregan reglas de validación para consultar concesionarios y perfiles de usuario. */
    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => 0, "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));


    if ($Login != "") {
        array_push($rules2, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

    }


    /* Añade condiciones a un array si los identificadores de usuario o apuestas no están vacíos. */
    if ($UserId != "") {
        array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

    }

    if ($BetShopId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

    }


    /* Crea un filtro JSON y obtiene perfiles de usuario con parámetros específicos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

    /* Se agregan reglas de filtrado para un sistema de concesionarios y usuarios. */
    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));


    if ($Login != "") {
        array_push($rules2, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

    }


    /* agrega condiciones a reglas basadas en si están definidas las variables. */
    if ($UserId != "") {
        array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

    }

    if ($BetShopId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

    }


    /* Se crea un filtro JSON para obtener usuarios de manera personalizada y ordenada. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {

    /* Agregar reglas de filtro a un array basado en condiciones específicas de usuario y estado. */
    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));


    if ($Login != "") {
        array_push($rules2, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

    }


    /* Agrega reglas a arrays si los identificadores de usuario y apuestas no están vacíos. */
    if ($UserId != "") {
        array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

    }

    if ($BetShopId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

    }


    /* define un filtro en formato JSON para obtener perfiles de usuario personalizados. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

} else {
    /* representa una estructura condicional vacía en un bloque "else". */

}


/* convierte una cadena JSON en un objeto o arreglo PHP. */
$usuarios = json_decode($usuarios);


foreach ($usuarios->data as $key => $value) {

    /* Se crea un array asociativo para almacenar información del usuario. */
    $array = [];

    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["UserId"] = $value->{"usuario.usuario_id"};

    $array["UserName"] = $value->{"usuario.nombre"};

    /* Asigna valores a un array asociado con propiedades de un objeto. */
    $array["Currency"] = $value->{"usuario.moneda"};
    $array["Country"] = $value->{"usuario.mandante"} . '-' . $value->{"pais.pais_nom"};
    $array["Name"] = $value->{"usuario.nombre"};
    $array["SystemName"] = 22;
    $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
    $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};

    /* inicializa un array con balances y propiedades del usuario. */
    $array["AgentBalance2"] = $value->{"punto_venta.cupo_recarga"};
    $array["PlayerCount"] = 0;
    $array["Children"] = array();
    $array["data"] = array();

    $array["Partner"] = $value->{"usuario.mandante"};

    /* Asigna un ícono basado en el perfil del usuario y la ISO del país. */
    $array["flag"] = strtolower($value->{"pais.iso"});
    switch ($value->{"usuario_perfil.perfil_id"}) {
        case "CONCESIONARIO":
            $array["icon"] = "icon-user-secret";
            break;
        case "CONCESIONARIO2":
            $array["icon"] = "icon-user-secret";
            break;
        case "CONCESIONARIO3":
            $array["icon"] = "icon-user-secret";
            break;
        case "PUNTOVENTA":
            $array["icon"] = "icon-shop";
            break;
    }

    /* Agrega un arreglo a otro y actualiza el balance del agente con créditos. */
    array_push($arrayf, $array);

    $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
}


/* establece una respuesta sin errores con mensaje de éxito y datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] ["Children"] = $arrayf;

/* organiza datos sobre cuentas y balances en un arreglo de respuesta. */
$response["data"] = $arrayf;

$response["Data"]["DownStreamChildrenCount"] = oldCount($arrayf);
$response["Data"]["DownStreamChildrenBalanceSum"] = $balanceAgent;
$response["Data"]["DownStreamPlayerCount"] = 10;
$response["Data"]["DownStreamPlayerBalanceSum"] = 10;


/*
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "DownStreamChildrenCount"=>100,
                "DownStreamChildrenBalanceSum"=>1000,
                "DownStreamPlayerCount"=>100,
                "DownStreamPlayerBalanceSum"=>100,
                "Children"=>array(
                    array(
                        "UserName"=>"test",
                        "AgentId"=>1,
                        "SystemName"=>1,
                        "PlayerCount"=>100,
                        "AgentBalance"=>1000,
                        "Children"=>array(
                            array(
                                "UserName"=>"test2",
                                "SystemName"=>1,

                                "PlayerCount"=>100,
                                "AgentBalance"=>1000,

                            )
                        )
                    )
                )
            );
*/