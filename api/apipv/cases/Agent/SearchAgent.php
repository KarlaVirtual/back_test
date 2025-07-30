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
 * Agent/SearchAgent
 *
 * Buscar un agente
 *
 * @param array $params Arreglo de parámetros que contiene los datos de la búsqueda
 * @param $params ->MaxRows int Número máximo de filas a devolver
 * @param $params ->OrderedItem int Elemento ordenado
 * @param $params ->SkeepRows int Número de filas a omitir
 *
 * @return array Respuesta con el estado de la operación, tipo de alerta, mensaje de alerta y datos de los usuarios
 *  - HasError bool Indica si ocurrió un error
 *  - AlertType string Tipo de alerta
 *  - AlertMessage string Mensaje de alerta
 *  - url string URL de redirección
 *  - success string Mensaje de éxito
 */


/* obtiene un filtro y crea objetos relacionados con el perfil de usuario. */
$filter = $_REQUEST["filter"];

$UsuarioPerfil = new UsuarioPerfil();
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Perfil_id = $_GET["roleId"];

/* recoge parámetros GET y asigna valores para usuarios y paginación. */
$Type = $_GET["Type"];
$tipoUsuario = "";

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;


/* obtiene valores de filas a omitir y contar desde una solicitud. */
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Establece valores predeterminados para `$OrderedItem` y `$MaxRows` si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000000;
}


/* Se crea un filtro de reglas para obtener usuarios según perfil. */
$mismenus = "0";

$rules = [];


if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

    if ($Type == "1") {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
    }


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
    /* Genera reglas de filtrado basadas en el perfil de usuario en una sesión. */

    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

    if ($Type == "1") {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*   ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
    /* configura reglas de filtrado para obtener usuarios según perfil y concesionario. */

    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

    if ($Type == "1") {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*   ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

} else {


    /* agrega reglas a un array según el valor de $Type. */
    if ($Type == "1") {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

    } elseif (($Type == "1")) {
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2','CONCESIONARIO3'", "op" => "in"));
    } else {
        /* Agrega reglas para el campo "perfil_id" con valores específicos mediante operación "in". */

        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'AFILIADOR','CONCESIONARIO','CONCESIONARIO2','CONCESIONARIO3'", "op" => "in"));

    }


    /* Se agrega un filtro basado en el nombre de usuario si no está vacío. */
    if ($filter != "") {
        array_push($rules, array("field" => "usuario.nombre", "data" => "$filter", "op" => "cn"));

    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y obtiene perfiles de usuario personalizados. */
    $json2 = json_encode($filtro);


    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.estado_valida,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.moneda,usuario.fecha_ult,usuario.fecha_crea,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

}


/* decodifica un JSON de usuarios y define un array vacío y un balance inicial. */
$usuarios = json_decode($usuarios);
$arrayf = [];

$balanceAgent = 0;

foreach ($usuarios->data as $key => $value) {

    /* crea un array con datos de un usuario y un array vacío para hijos. */
    $array = [];
    $array["id"] = $value->{"usuario.usuario_id"};
    $array["value"] = $value->{"usuario.nombre"};
    $array["Children"] = array();

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

        /* Se establecen reglas de filtrado para consultar registros de concesionarios y usuarios. */
        $rules2 = array();

        array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
        $filtro = array("rules" => $rules2, "groupOp" => "AND");

        /* convierte un filtro JSON y obtiene detalles de usuarios organizados en un array. */
        $json2 = json_encode($filtro);

        $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
        $usuariosdetalle = json_decode($usuariosdetalle);


        foreach ($usuariosdetalle->data as $key2 => $value2) {
            $array2 = [];

            $array2["id"] = $value2->{"usuario.usuario_id"};
            $array2["value"] = $value2->{"usuario.nombre"};

            array_push($array["Children"], $array2);


        }

    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        /* Condición que verifica si el perfil de usuario es "CONCESIONARIO2". */


    } else {

        /* Se definen reglas de filtrado para consultar concesionarios y usuarios específicos. */
        $rules2 = array();

        array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
        $filtro = array("rules" => $rules2, "groupOp" => "AND");

        /* Convierte un filtro a JSON y recupera detalles de usuarios. */
        $json2 = json_encode($filtro);

        $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
        $usuariosdetalle = json_decode($usuariosdetalle);

        foreach ($usuariosdetalle->data as $key2 => $value2) {

            /* Crea un array asociativo con ID, nombre y un array vacío para hijos. */
            $array2 = [];

            $array2["id"] = $value2->{"usuario.usuario_id"};
            $array2["value"] = $value2->{"usuario.nombre"};

            $array2["Children"] = array();

            if (true) {

                /* Crea filtros de reglas para consultas sobre concesionarios y perfiles de usuarios. */
                $rules3 = array();

                array_push($rules3, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                $filtro = array("rules" => $rules3, "groupOp" => "AND");

                /* Convierte datos de usuarios a JSON y los organiza en un array estructurado. */
                $json3 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json3, true);
                $usuariosdetalle = json_decode($usuariosdetalle);


                foreach ($usuariosdetalle->data as $key3 => $value3) {
                    $array2 = [];

                    $array2["id"] = $value3->{"usuario.usuario_id"};
                    $array2["value"] = $value3->{"usuario.nombre"};

                    array_push($array2["Children"], $array3);


                }

            }


            /* Agrega el contenido de `$array2` al final del subarreglo "Children" de `$array`. */
            array_push($array["Children"], $array2);


        }


    }


    /* Se agrega un elemento a un array y se suma un valor al saldo de un agente. */
    array_push($arrayf, $array);

    $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
}


/* Asigna el contenido de `$arrayf` a la variable `$response`. */
$response = $arrayf;
