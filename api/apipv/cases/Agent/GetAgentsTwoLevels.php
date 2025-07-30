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
 * Agent/GetAgentsTwoLevels
 *
 * Obtener la red completa del partner
 *
 * @param object $params
 * - MaxRows:int - Número máximo de filas a devolver
 * - OrderedItem:string - Elemento por el cual ordenar
 * - SkeepRows:int - Número de filas a omitir
 *
 * @return array
 * - HasError:bool - Indica si ocurrió un error
 * - AlertType:string - Tipo de alerta
 * - AlertMessage:string - Mensaje de alerta
 * - ModelErrors:array - Lista de errores de validación
 * - Data:array - Lista de usuarios
 *      - Children:array - Lista de hijos
 *          - Id:int - Identificador del usuario
 *          - UserId:int - Identificador del usuario
 *          - StateValidate:string - Estado de validación
 *          - Action:string - Acción
 *          - State:string - Estado
 *          - UserName:string - Nombre de usuario
 *          - Name:string - Nombre
 *          - Email:string - Correo electrónico
 *          - Phone:string - Teléfono
 *          - Address:string - Dirección
 *          - CurrencyId:int - Identificador de moneda
 *          - RegionName:string - Nombre de la región
 *          - DepartmentName:string - Nombre del departamento
 *          - CityName:string - Nombre de la ciudad
 *          - SystemName:int - Nombre del sistema
 *          - IsSuspended:bool - Indica si está suspendido
 *          - AgentBalance:float - Balance del agente
 *          - PlayerCount:int - Número de jugadores
 *          - LastLoginDateLabel:string - Fecha del último inicio de sesión
 *          - CreatedDate:string - Fecha de creación
 *          - Document:string - Documento
 *          - IPIdentification:string - Identificación IP
 *          - Children:array - Lista de hijos
 *          - Ip:string - Dirección IP
 * - DownStreamChildrenCount:int - Conteo de hijos en la red
 * - DownStreamChildrenBalanceSum:float - Suma de balances de hijos en la red
 * - DownStreamPlayerCount:int - Conteo de jugadores en la red
 * - DownStreamPlayerBalanceSum:float - Suma de balances de jugadores en la red
 * - pos:int - Posición
 * - total_count:int - Conteo total
 * - data:array - Datos
 */


/* Se inicializan objetos de usuario con parámetros de sesión y se validan tipos. */
$UsuarioPerfil = new UsuarioPerfil();
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


$Perfil_id = $_GET["roleId"];
$Type = ($_GET["Type"] != 1 && $_GET["Type"] != 0) ? '' : $_GET["Type"];

/* valida parámetros obtenidos de la URL y los asigna a variables. */
$IsRegisterActivate = ($_GET["IsRegisterActivate"] != "A" && $_GET["IsRegisterActivate"] != "I" && $_GET["IsRegisterActivate"] != "N" && $_GET["IsRegisterActivate"] != "R") ? '' : $_GET["IsRegisterActivate"];
$IsActivate = ($_GET["IsActivate"] != "A" && $_GET["IsActivate"] != "I") ? '' : $_GET["IsActivate"];
$UserId = $_GET["UserId"];
$Id = $_GET["Id"];
$Ip = $_GET["Ip"];

$Name = $_GET["Name"];

/* captura parámetros GET para login, documento, y identificación IP. */
$Login = $_GET["Login"];
$Document = $_GET["Document"];
$IPIdentification = $_GET["IPIdentification"];
$tipoUsuario = "";

$MaxRows = $params->MaxRows;

/* Asignación de parámetros y manejo de variables para paginación en una solicitud. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$seguir = true;


/* Se obtienen parámetros y se inicializan variables para manejar una consulta. */
$isList = $_REQUEST["isList"];
$CountrySelect = $_REQUEST["CountrySelect"];

$consultaAgente = $_SESSION['consultaAgente'];

if ($isList != "") {
    $SkeepRows = 0;
    $MaxRows = 10000;
}

/* verifica condiciones para detener un proceso basado en valores y sesiones. */
if (($MaxRows == "" || $SkeepRows == "") && $isList == "") {
    $seguir = false;
}

if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {
    $seguir = false;

}

/*
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100000000;
            }*/
if ($seguir) {


    /* Se crea un arreglo de reglas basado en la variable $consultaAgente. */
    $mismenus = "0";

    $rules = [];


    if ($consultaAgente != "0" && $consultaAgente != null) {

        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$consultaAgente", "op" => "eq"));

    }


    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

        /* Agrega reglas de filtro según condiciones específicas sobre concesionarios y perfiles de usuario. */
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

        if ($Type == "1") {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

        } else {
            /* Agrega reglas de validación para perfiles de usuario en un arreglo. */

            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO2','CONCESIONARIO3'", "op" => "in"));
        }


        /* Se agregan reglas de validación basadas en condiciones de activación de usuario. */
        if ($IsRegisterActivate != "") {
            array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
        }

        if ($IsActivate != "") {
            array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
        }

        /* Agrega reglas de filtrado según si $UserId o $Id no están vacíos. */
        if ($UserId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$UserId", "op" => "eq"));
        }
        if ($Id != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "eq"));
        }


        /* Agrega reglas de validación si 'Name' y 'Login' no están vacíos. */
        if ($Name != "") {
            array_push($rules, array("field" => "usuario.nombre", "data" => $Name, "op" => "cn"));
        }
        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));
        }

        /* Agrega reglas de filtrado condicional para usuarios y documentos en un array. */
        if ($CountrySelect != "" && is_numeric($CountrySelect)) {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
        }

        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));
        if ($Document != "") {
            array_push($rules, array("field" => "punto_venta.cedula", "data" => $Document, "op" => "eq"));
        }

        /* Agrega reglas basadas en las validaciones de identificación IP proporcionadas. */
        if ($IPIdentification != "") {
            array_push($rules, array("field" => "punto_venta.indentificacion_ip", "data" => $IPIdentification, "op" => "eq"));
        }
        if ($Ip != "") {
            array_push($rules, array("field" => "usuario.dir_ip", "data" => "$Ip", "op" => "eq"));
        }


        /* Filtra usuarios y perfiles, generando una consulta personalizada en formato JSON. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

        /* Se añaden reglas de validación basadas en condiciones específicas para usuarios. */
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

        if ($Type == "1") {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

        } else {
            /* Agrega una regla a un array si no se cumple una condición específica. */

            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO3", "op" => "eq"));
        }


        /* Agrega condiciones a un array de reglas basado en registros activos. */
        if ($IsRegisterActivate != "") {
            array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
        }

        if ($IsActivate != "") {
            array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
        }

        /* Agrega reglas de igualdad para usuario_id según UserId e Id si no están vacíos. */
        if ($UserId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$UserId", "op" => "eq"));
        }
        if ($Id != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "eq"));
        }


        /* Agrega reglas a un array si los valores de nombre y login no están vacíos. */
        if ($Name != "") {
            array_push($rules, array("field" => "usuario.nombre", "data" => $Name, "op" => "cn"));
        }
        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));
        }

        /* Valida inputs y agrega reglas a un array si se cumplen condiciones específicas. */
        if ($CountrySelect != "" && is_numeric($CountrySelect)) {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
        }
        if ($Document != "") {
            array_push($rules, array("field" => "punto_venta.cedula", "data" => $Document, "op" => "eq"));
        }

        /* Añade condiciones a un arreglo de reglas basadas en identificaciones IP no vacías. */
        if ($IPIdentification != "") {
            array_push($rules, array("field" => "punto_venta.indentificacion_ip", "data" => $IPIdentification, "op" => "eq"));
        }
        if ($Ip != "") {
            array_push($rules, array("field" => "usuario.dir_ip", "data" => "$Ip", "op" => "eq"));
        }

        /* Construye un filtro JSON y recupera datos de usuarios con condiciones específicas. */
        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*   ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {

        /* Agrega reglas de validación basadas en condiciones específicas de usuario y concesionario. */
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

        if ($Type == "1") {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

        } else {
            /* Añade una regla al arreglo si la condición en el else se cumple. */

            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO4", "op" => "eq"));
        }


        /* Agrega reglas basadas en el estado de activación del usuario. */
        if ($IsRegisterActivate != "") {
            array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
        }

        if ($IsActivate != "") {
            array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
        }

        /* Condicionalmente agrega reglas basadas en los valores de $UserId y $Id. */
        if ($UserId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$UserId", "op" => "eq"));
        }
        if ($Id != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "eq"));
        }


        /* Agrega reglas de validación si los campos nombre y login no están vacíos. */
        if ($Name != "") {
            array_push($rules, array("field" => "usuario.nombre", "data" => $Name, "op" => "cn"));
        }
        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));
        }

        /* Agrega reglas de validación basadas en país y documento si están definidos. */
        if ($CountrySelect != "" && is_numeric($CountrySelect)) {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
        }
        if ($Document != "") {
            array_push($rules, array("field" => "punto_venta.cedula", "data" => $Document, "op" => "eq"));
        }

        /* Agrega reglas de filtrado basadas en identificaciones IP no vacías. */
        if ($IPIdentification != "") {
            array_push($rules, array("field" => "punto_venta.indentificacion_ip", "data" => $IPIdentification, "op" => "eq"));
        }
        if ($Ip != "") {
            array_push($rules, array("field" => "usuario.dir_ip", "data" => "$Ip", "op" => "eq"));
        }

        /* Se define un filtro JSON para obtener perfiles de usuarios en una consulta. */
        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*   ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

    } elseif ($_SESSION["win_perfil"] == "ADMINAFILIADOS") {

        /* Agrega reglas de validación en un array, condicionadas por el estado de registro. */
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));


        if ($IsRegisterActivate != "") {
            array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
        }


        /* Agrega reglas a un array según condiciones de activación y ID de usuario. */
        if ($IsActivate != "") {
            array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
        }
        if ($UserId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$UserId", "op" => "eq"));
        }

        /* agrega reglas a un array según condiciones de variables. */
        if ($Id != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "eq"));
        }


        if ($Name != "") {
            array_push($rules, array("field" => "usuario.nombre", "data" => $Name, "op" => "cn"));
        }

        /* agrega reglas de filtrado basadas en login y país si son válidos. */
        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));
        }
        if ($CountrySelect != "" && is_numeric($CountrySelect)) {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
        }

        /* Agrega reglas a un arreglo si los documentos o identificaciones no están vacíos. */
        if ($Document != "") {
            array_push($rules, array("field" => "punto_venta.cedula", "data" => $Document, "op" => "eq"));
        }
        if ($IPIdentification != "") {
            array_push($rules, array("field" => "punto_venta.indentificacion_ip", "data" => $IPIdentification, "op" => "eq"));
        }

        /* Se agregan reglas de filtro para buscar usuarios activos por IP. */
        if ($Ip != "") {
            array_push($rules, array("field" => "usuario.dir_ip", "data" => "$Ip", "op" => "eq"));
        }
        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* Se genera un JSON y se obtiene perfiles de usuario personalizados con los parámetros especificados. */
        $json2 = json_encode($filtro);

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

    } else {


        /* Agrega reglas según el valor de la variable $Type en un array. */
        if ($Type == "1") {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

        } elseif (($Type == "1")) {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2','CONCESIONARIO3','CONCESIONARIO4'", "op" => "in"));
        } else {
            /* Agrega reglas de validación para perfiles de usuario específicos en un array. */

            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'AFILIADOR','CONCESIONARIO','CONCESIONARIO2','CONCESIONARIO3','CONCESIONARIO4'", "op" => "in"));

        }


        /* Se añaden reglas de validación basadas en condiciones de activación de usuario. */
        if ($IsRegisterActivate != "") {
            array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
        }

        if ($IsActivate != "") {
            array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
        }

        /* Agrega reglas de filtrado basadas en condiciones de usuario e ID no vacíos. */
        if ($UserId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$UserId", "op" => "eq"));
        }
        if ($Id != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "eq"));
        }


        /* Agrega reglas a un array si 'Name' o 'Login' no están vacíos. */
        if ($Name != "") {
            array_push($rules, array("field" => "usuario.nombre", "data" => $Name, "op" => "cn"));
        }
        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));
        }

        /* valida y agrega reglas basadas en country y document. */
        if ($CountrySelect != "" && is_numeric($CountrySelect)) {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
        }
        if ($Document != "") {
            array_push($rules, array("field" => "punto_venta.cedula", "data" => $Document, "op" => "eq"));
        }

        /* Se agregan reglas de comparación para identificación de IP y dirección del usuario. */
        if ($IPIdentification != "") {
            array_push($rules, array("field" => "punto_venta.indentificacion_ip", "data" => $IPIdentification, "op" => "eq"));
        }

        if ($Ip != "") {
            array_push($rules, array("field" => "usuario.dir_ip", "data" => "$Ip", "op" => "eq"));
        }
        // Si el usuario esta condicionado por País

        /* Verifica condiciones y agrega reglas a un arreglo basado en la sesión del usuario. */
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            /* Añade una regla a un array si "mandanteLista" no está vacío ni es "-1". */


            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }

        /* Verifica el perfil de usuario y agrega reglas según la región si es necesario. */
        if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {
            if ($_SESSION["win_perfil"] != "CONCESIONARIO" && $_SESSION["win_perfil"] != "CONCESIONARIO2" && $_SESSION["win_perfil"] != "CONCESIONARIO3" && $_SESSION["win_perfil"] != "PUNTOVENTA") {

                array_push($rules, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));
            }
        }


        /* Agrega condiciones a un array según la sesión y el estado de usuario. */
        if ($_SESSION["usuario"] == 4089418) {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => 693966, "op" => "eq"));
        }


        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


        /* Crea un filtro JSON y obtiene usuarios según criterios específicos de la base de datos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);


        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.estado_valida,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.moneda,usuario.fecha_ult,usuario.fecha_crea,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

    }


    /* decodifica datos JSON de usuarios y inicializa un arreglo vacío y un saldo. */
    $usuarios = json_decode($usuarios);
    $arrayf = [];

    $balanceAgent = 0;

    foreach ($usuarios->data as $key => $value) {

        if ($isList != 1) {


            /* crea un array con información del usuario a partir de un objeto. */
            $array = [];
            $array["id"] = $value->{"usuario.usuario_id"};
            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["UserId"] = $value->{"usuario.usuario_id"};

            $array["StateValidate"] = $value->{"usuario.estado_valida"};


            /* Verifica el perfil de usuario y asigna estado válido o vacío. */
            if ($_SESSION["win_perfil2"] != "CONCESIONARIO" && $_SESSION["win_perfil2"] != "CONCESIONARIO2" && $_SESSION["win_perfil2"] != "CONCESIONARIO3") {
                $array["Action"] = $value->{"usuario.estado_valida"};

            } else {
                $array["Action"] = '';
            }


            /* Extrae y procesa información del objeto 'usuario' y 'punto_venta' a un array. */
            $array["State"] = $value->{"usuario.estado"};

            $array["UserName"] = str_replace("VAFILV", '', $value->{"usuario.login"});
            $array["Name"] = $value->{"usuario.nombre"};
            $array["Email"] = str_replace("VAFILV", "", $value->{"punto_venta.email"});
            $array["Phone"] = $value->{"punto_venta.telefono"};

            /* Se asignan valores a un array desde un objeto definido por propiedades específicas. */
            $array["Address"] = $value->{"punto_venta.direccion"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["RegionName"] = $value->{"pais.pais_nom"};
            $array["DepartmentName"] = $value->{"departamento.depto_nom"};
            $array["CityName"] = $value->{"ciudad.ciudad_nom"};
            $array["SystemName"] = 22;

            /* asigna valores a un array basándose en propiedades de un objeto. */
            $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
            $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};
            $array["PlayerCount"] = 0;
            $array["LastLoginDateLabel"] = $value->{"usuario.fecha_ult"};
            $array["CreatedDate"] = $value->{"usuario.fecha_crea"};
            $array["Document"] = $value->{"punto_venta.cedula"};

            /* asigna datos de un objeto a un array con clave-valor. */
            $array["IPIdentification"] = $value->{"punto_venta.identificacion_ip"};
            $array["Children"] = array();
            $array["Ip"] = $value->{"usuario.dir_ip"};
            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

                /* Se generan reglas de filtrado para consultar concesionarios y perfiles de usuario. */
                $rules2 = array();

                array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                $filtro = array("rules" => $rules2, "groupOp" => "AND");

                /* Convierte datos de usuarios en un array estructurado y procesa detalles específicos. */
                $json2 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                $usuariosdetalle = json_decode($usuariosdetalle);


                foreach ($usuariosdetalle->data as $key2 => $value2) {
                    $array2 = [];

                    $array2["Id"] = $value2->{"usuario.usuario_id"};
                    $array2["UserId"] = $value2->{"usuario.usuario_id"};

                    $array2["UserName"] = $value2->{"usuario.login"};
                    $array2["Name"] = $value2->{"usuario.nombre"};

                    $array2["SystemName"] = 22;
                    $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
                    $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                    $array2["PlayerCount"] = 0;
                    array_push($array["Children"], $array2);


                }

            } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                /* Condición que verifica si el perfil de usuario es "CONCESIONARIO2" en PHP. */


            } else {

                /* Se construyen reglas de filtro para consultas basadas en condiciones específicas. */
                $rules2 = array();

                array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                $filtro = array("rules" => $rules2, "groupOp" => "AND");

                /* Codifica un filtro JSON y recupera detalles de usuarios desde una base de datos. */
                $json2 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                $usuariosdetalle = json_decode($usuariosdetalle);

                foreach ($usuariosdetalle->data as $key2 => $value2) {

                    /* asigna datos de usuario a un array asociativo en PHP. */
                    $array2 = [];

                    $array2["Id"] = $value2->{"usuario.usuario_id"};
                    $array2["UserName"] = $value2->{"usuario.login"};
                    $array2["Name"] = $value2->{"usuario.nombre"};
                    $array2["UserId"] = $value2->{"usuario.usuario_id"};


                    /* Asignación de valores a un arreglo basado en condiciones y datos de usuarios. */
                    $array2["SystemName"] = 22;
                    $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
                    $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                    $array2["PlayerCount"] = 0;
                    $array["LastLoginDateLabel"] = $value2->{"usuario.fecha_ult"};
                    $array["Document"] = $value->{"punto_venta.cedula"};

                    /* Asignación de "N" o "S" a IPIdentification según el valor de identificacion_ip. */
                    switch ($value->{"punto_venta.identificacion_ip"}) {
                        case '0':
                            $IPIdentification = "N";
                            break;
                        case '1':
                            $IPIdentification = "S";
                            break;
                    }

                    /* Código asigna valores a un arreglo y crea un arreglo vacío para "Children". */
                    $array["IPIdentification"] = $IPIdentification;
                    $array["Ip"] = $value->{"usuario.dir_ip"};
                    $array2["Children"] = array();

                    if (true) {

                        /* Se crean reglas de filtrado para una consulta utilizando condiciones específicas. */
                        $rules3 = array();

                        array_push($rules3, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                        array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                        $filtro = array("rules" => $rules3, "groupOp" => "AND");

                        /* Convierte datos en JSON, consulta usuarios y decodifica el resultado JSON. */
                        $json3 = json_encode($filtro);

                        $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json3, true);
                        $usuariosdetalle = json_decode($usuariosdetalle);


                        /* Se crea un arreglo de detalles de usuarios a partir de datos proporcionados. */
                        foreach ($usuariosdetalle->data as $key3 => $value3) {
                            $array3 = [];

                            $array3["Id"] = $value3->{"usuario.usuario_id"};
                            $array3["UserId"] = $value3->{"usuario.usuario_id"};

                            $array3["UserName"] = $value3->{"usuario.login"};
                            $array3["Name"] = $value3->{"usuario.nombre"};
                            $array3["SystemName"] = 22;
                            $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                            $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                            $array3["PlayerCount"] = 0;
                            $array3["Document"] = $value->{"punto_venta.cedula"};
                            $array3["Children"] = array();

                            $array3["IPIdentification"] = intval($value->{"punto_venta.identificacion_ip"});
                            array_push($array2["Children"], $array3);


                        }

                    }


                    /* Añade el contenido de $array2 al final del array "Children" de $array. */
                    array_push($array["Children"], $array2);


                }


            }
        } else {
            /* crea un arreglo con ID y nombre de un usuario específico. */

            $array = [];
            $array["id"] = $value->{"usuario.usuario_id"};
            $array["Name"] = $value->{"usuario.nombre"};

        }


        /* agrega un arreglo y actualiza el saldo del agente. */
        array_push($arrayf, $array);

        $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
    }


    /* establece una respuesta exitosa sin errores y sin mensajes específicos. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] ["Children"] = $arrayf;


    /* Se asignan valores a la respuesta relacionada con contadores y balances de jugadores. */
    $response["Data"]["DownStreamChildrenCount"] = oldCount($arrayf);
    $response["Data"]["DownStreamChildrenBalanceSum"] = $balanceAgent;
    $response["Data"]["DownStreamPlayerCount"] = 10;
    $response["Data"]["DownStreamPlayerBalanceSum"] = 10;

    $response["pos"] = $SkeepRows;

    /* Asigna el conteo de usuarios y un arreglo a la respuesta. */
    $response["total_count"] = $usuarios->count[0]->{".count"};
    $response["data"] = $arrayf;


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
}
