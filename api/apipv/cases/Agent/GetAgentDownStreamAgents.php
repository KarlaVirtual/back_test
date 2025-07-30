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
 * @param array $params Parámetros de entrada para la función
 * @param int $params ->MaxRows Número máximo de filas a devolver
 * @param int $params ->OrderedItem Elemento ordenado
 * @param int $params ->SkeepRows Filas a omitir
 *
 *
 * @return array $response Respuesta estructurada con los datos de los agentes y puntos de venta
 * @throws Exception Si ocurre un error durante la ejecución
 */


/* Se crean objetos de usuario y se obtienen parámetros de la solicitud GET. */
$UsuarioPerfil = new UsuarioPerfil();
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Perfil_id = $_GET["roleId"];
$Login = $_GET["Login"];
$Name = $_GET["Name"];

/* recopila parámetros de usuario desde una solicitud HTTP GET. */
$UserId = $_GET["UserId"];
$CountrySelect = $_GET["CountrySelect"];

$BetShopId = $_GET["BetShopId"];
$UserIdAgent = $_GET["UserIdAgent"];
$UserIdAgent2 = $_GET["UserIdAgent2"];

/* obtiene datos de usuario y configuración de sesión en PHP. */
$UserIdAgent3 = $_GET["UserIdAgent3"];


$consultaAgente = $_SESSION['consultaAgente'];


$MaxRows = $params->MaxRows;

/* asigna valores y maneja una variable opcional para filas a omitir. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asignación de valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000000;
}


/* Se inicializa la variable $mismenus con el valor de cadena "0". */
$mismenus = "0";

if ($_SESSION["usuario"] == 449 or $_SESSION["usuario"] == 186879 or $_SESSION["usuario"] == 556512 or $_SESSION["usuario"] == 73737 || true) {
    if (true) {

        /* Código que inicializa un arreglo y verifica el perfil de usuario en sesión. */
        $arrayfinal = array();
        $balanceAgent = 0;

        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            $UserIdAgent = $_SESSION["usuario"];
        }


        /* Asignación de variables según el perfil del usuario en sesión. */
        if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
            $UserIdAgent2 = $_SESSION["usuario"];
        }


        if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
            $UserIdAgent3 = $_SESSION["usuario"];
        }


        /* asigna valores a $UserIdAgent basándose en condiciones de usuarios. */
        if ($UserIdAgent == '' && $UserIdAgent2 != '') {
            $Concesionario = new Concesionario($UserIdAgent2, '0');
            $UserIdAgent = $Concesionario->usupadreId;
        }

        if ($UserIdAgent == '' && $UserIdAgent2 == '' && $UserIdAgent3 != '') {
            $Concesionario = new Concesionario($UserIdAgent3, '0');
            $UserIdAgent = $Concesionario->usupadreId;
            $UserIdAgent2 = $Concesionario->usupadre2Id;
        }


        /* Asigna $BetShopId a $UserId si $BetShopId no está vacío. */
        if ($BetShopId != '') {
            $UserId = $BetShopId;
        }


        /* Asignación de IDs de usuario según condiciones en un objeto Concesionario. */
        if ($UserId != "") {
            $Concesionario = new Concesionario($UserId, '0');
            if ($UserIdAgent == '') {
                $UserIdAgent = $Concesionario->usupadreId;

            }
            if ($UserIdAgent2 == '') {
                $UserIdAgent2 = $Concesionario->usupadre2Id;

            }
            if ($UserIdAgent3 == '') {
                $UserIdAgent3 = $Concesionario->usupadre3Id;

            }
            if ($UserIdAgent4 == '') {
                $UserIdAgent4 = $Concesionario->usupadre4Id;

            }

        }


        /* asigna una cadena vacía si los identificadores son '0'. */
        if ($UserIdAgent == '0') {
            $UserIdAgent = '';
        }

        if ($UserIdAgent2 == '0') {
            $UserIdAgent2 = '';
        }


        /* asigna una cadena vacía si los identificadores de usuario son '0'. */
        if ($UserIdAgent3 == '0') {
            $UserIdAgent3 = '';
        }

        if ($UserIdAgent4 == '0') {
            $UserIdAgent4 = '';
        }


        /* Define reglas de validación para usuarios y concesionarios en un sistema. */
        $rules3 = array();
        array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2','CONCESIONARIO3','CONCESIONARIO4','PUNTOVENTA','CAJERO'", "op" => "in"));

        array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

        array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


        /* Agrega reglas de validación según el login y el país seleccionados. */
        if ($Login != "") {
            array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

        }
        if ($CountrySelect != "") {
            array_push($rules3, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

        }


        /* agrega reglas a un array basado en condiciones de entrada. */
        if ($Name != '') {
            array_push($rules3, array("field" => "usuario.nombre", "data" => $Name, "op" => "cn"));

        }

        if ($UserId != "") {
            array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $UserId, "op" => "eq"));

        }

        /*if ($BetShopId != "") {
            array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $BetShopId, "op" => "eq"));

        }*/


        /* Agrega reglas a un arreglo si los identificadores de usuario no están vacíos. */
        if ($UserIdAgent != "") {
            array_push($rules3, array("field" => "concesionario.usupadre_id", "data" => $UserIdAgent, "op" => "eq"));

        }

        if ($UserIdAgent2 != "") {
            array_push($rules3, array("field" => "concesionario.usupadre2_id", "data" => $UserIdAgent2, "op" => "eq"));

        }


        /* Agregar condiciones a un arreglo basado en el ID de usuario y país. */
        if ($UserIdAgent3 != "") {
            array_push($rules3, array("field" => "concesionario.usupadre3_id", "data" => $UserIdAgent3, "op" => "eq"));

        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules3, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global

        /* establece condiciones para agregar reglas basadas en la sesión del usuario. */
        if ($_SESSION['Global'] == "N") {
            array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }

        /* Filtra reglas basadas en la región del perfil de usuario en sesión. */
        if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {

            // array_push($rules3, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));

        }


        $filtro = array("rules" => $rules3, "groupOp" => "AND");

        /* Convierte datos a JSON, consulta perfiles de usuario y decodifica el resultado. */
        $json3 = json_encode($filtro);


        $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" concesionario.*,usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
        $usuariosdetalle = json_decode($usuariosdetalle);

        foreach ($usuariosdetalle->data as $key3 => $value3) {


            /* Crea un array asociativo con información del usuario y su país. */
            $array3 = [];
            $array3["Id"] = $value3->{"usuario.usuario_id"};
            $array3["UserId"] = $value3->{"usuario.usuario_id"};
            $array3["UserName"] = $value3->{"usuario.nombre"};
            $array3["Currency"] = $value3->{"usuario.moneda"};
            $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};

            /* Asigna propiedades a un array a partir de un objeto en PHP. */
            $array3["Name"] = $value3->{"usuario.nombre"};
            $array3["SystemName"] = 22;
            $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
            $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
            $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};
            $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};

            /* Configura un array con detalles de usuarios y asigna íconos según su perfil. */
            $array3["PlayerCount"] = 0;
            $array3["Partner"] = $value3->{"usuario.mandante"};

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
                case "CAJERO":
                    $array4["icon"] = "icon-shop";
                    break;
            }


            /* Asigna $array3 a una estructura multidimensional basada en IDs de concesionarios. */
            $arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})] = $array3;

        }


        /* Verifica condiciones de login y asigna IDs de usuario a variables específicas. */
        if (($Login != "" || $Name != '') && $UserIdAgent == '' && $UserIdAgent2 == '' && $UserIdAgent3 == '') {
            $Concesionario = new Concesionario($value3->{"usuario.usuario_id"}, '0');
            $UserIdAgent = $Concesionario->usupadreId;
            $UserIdAgent2 = $Concesionario->usupadre2Id;
            $UserIdAgent3 = $Concesionario->usupadre3Id;
            $UserIdAgent4 = $Concesionario->usupadre4Id;

        }


        if ($UserIdAgent != "") {


            /* Define reglas para validar perfiles y estados de concesionarios en una aplicación. */
            $rules3 = array();
            array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO'", "op" => "in"));

            array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

            array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


            /* Código que verifica variables y prepara reglas de validación condicionalmente. */
            if ($Login != "") {
                //array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

            }

            if ($UserId != "") {
                // array_push($rules3, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

            }


            /* Añade condiciones a un arreglo de reglas basadas en valores definidos. */
            if ($BetShopId != "") {
                //array_push($rules3, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

            }


            if ($CountrySelect != "") {
                array_push($rules3, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

            }

            /* Condicionales que añaden reglas basadas en identificadores de usuario a un arreglo. */
            if ($UserIdAgent != "") {
                array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $UserIdAgent, "op" => "in"));

            }
            if ($UserIdAgent2 != "") {
                $Concesionario = new Concesionario($UserIdAgent2, '0');
                array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $Concesionario->usupadreId, "op" => "in"));

            }


            // Si el usuario esta condicionado por País

            /* agrega reglas de filtrado basadas en condiciones de sesión del usuario. */
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules3, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                /* Agrega una regla si "mandanteLista" no está vacío o es diferente de "-1". */


                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            /* Condicional verifica la región de perfil y crea un filtro con reglas. */
            if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {

                // array_push($rules3, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));

            }

            $filtro = array("rules" => $rules3, "groupOp" => "AND");

            /* Codifica un filtro a JSON y obtiene detalles de usuarios específicos de la base de datos. */
            $json3 = json_encode($filtro);

            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" concesionario.*,usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
            $usuariosdetalle = json_decode($usuariosdetalle);

            foreach ($usuariosdetalle->data as $key3 => $value3) {


                /* Se crea un array con información de usuario y su país. */
                $array3 = [];
                $array3["Id"] = $value3->{"usuario.usuario_id"};
                $array3["UserId"] = $value3->{"usuario.usuario_id"};
                $array3["UserName"] = $value3->{"usuario.nombre"};
                $array3["Currency"] = $value3->{"usuario.moneda"};
                $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};

                /* Asigna información de usuario a un array utilizando propiedades de un objeto. */
                $array3["Name"] = $value3->{"usuario.nombre"};
                $array3["SystemName"] = 22;
                $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};
                $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};

                /* Asignación de iconos a diferentes perfiles de usuario en función de su tipo. */
                $array3["PlayerCount"] = 0;
                $array3["Partner"] = $value3->{"usuario.mandante"};

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
                    case "CAJERO":
                        $array4["icon"] = "icon-shop";
                        break;
                }


                /* Asigna `$array3` a una estructura multidimensional basada en identificadores de concesionario. */
                $arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})] = $array3;

            }


        }
        if ($UserIdAgent2 != "") {


            /* Código que define reglas de filtrado para una consulta de base datos. */
            $rules3 = array();
            array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO2'", "op" => "in"));

            array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

            array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


            /* Añade reglas a un arreglo dependiendo de las variables $CountrySelect y $Login. */
            if ($CountrySelect != "") {
                array_push($rules3, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

            }
            if ($Login != "") {
                //array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

            }


            /* Código que verifica condiciones para agregar reglas a un arreglo, actualmente comentadas. */
            if ($UserId != "") {
                //array_push($rules3, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

            }

            if ($BetShopId != "") {
                //array_push($rules3, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

            }


            /* añade reglas de filtrado basadas en condiciones de usuario y país. */
            if ($UserIdAgent2 != "") {
                array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $UserIdAgent2, "op" => "in"));

            }

            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules3, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* Condiciona la inserción de reglas en función del estado de sesión y datos. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            /* Valida la región perfil en la sesión y crea un filtro con reglas. */
            if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {

                // array_push($rules3, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));

            }

            $filtro = array("rules" => $rules3, "groupOp" => "AND");

            /* Se codifica un filtro en JSON y se obtienen detalles de usuarios. */
            $json3 = json_encode($filtro);

            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" concesionario.*,usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
            $usuariosdetalle = json_decode($usuariosdetalle);

            foreach ($usuariosdetalle->data as $key3 => $value3) {


                /* Crea un arreglo asociativo con datos de usuario, incluyendo ID, nombre y ubicación. */
                $array3 = [];
                $array3["Id"] = $value3->{"usuario.usuario_id"};
                $array3["UserId"] = $value3->{"usuario.usuario_id"};
                $array3["UserName"] = $value3->{"usuario.nombre"};
                $array3["Currency"] = $value3->{"usuario.moneda"};
                $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};

                /* Asigna valores a un array asociativo basado en propiedades de un objeto. */
                $array3["Name"] = $value3->{"usuario.nombre"};
                $array3["SystemName"] = 22;
                $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};
                $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};

                /* asigna valores e íconos a un arreglo basado en condiciones del perfil. */
                $array3["PlayerCount"] = 0;
                $array3["Partner"] = $value3->{"usuario.mandante"};

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
                    case "CAJERO":
                        $array4["icon"] = "icon-shop";
                        break;
                }


                /* Asigna el valor de $array3 a una estructura multidimensional basada en IDs. */
                $arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})] = $array3;

            }


        }
        if ($UserIdAgent3 != "") {


            /* Se crean reglas de validación para filtrar concesionarios según criterios específicos. */
            $rules3 = array();
            array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO3'", "op" => "in"));

            array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

            array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


            /* Condiciona la adición de reglas basadas en la entrada del país o login. */
            if ($CountrySelect != "") {
                array_push($rules3, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

            }
            if ($Login != "") {
                // array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

            }


            /* Condicionales que añaden reglas a un array basado en variables no vacías. */
            if ($UserId != "") {
                // array_push($rules3, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

            }

            if ($BetShopId != "") {
                //array_push($rules3, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

            }


            /* añade condiciones a un array basado en variables específicas. */
            if ($UserIdAgent3 != "") {
                array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $UserIdAgent3, "op" => "in"));

            }

            if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {

                // array_push($rules3, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));

            }


            // Si el usuario esta condicionado por País

            /* Condiciona reglas basadas en la sesión del usuario respecto a país y mandante. */
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules3, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                /* Verifica una condición y agrega una regla a un array si se cumple. */


                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            /* Se crea un filtro JSON para obtener detalles de usuarios en una consulta. */
            $filtro = array("rules" => $rules3, "groupOp" => "AND");
            $json3 = json_encode($filtro);

            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" concesionario.*,usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
            $usuariosdetalle = json_decode($usuariosdetalle);

            foreach ($usuariosdetalle->data as $key3 => $value3) {


                /* Crea un arreglo asociativo con información de usuario a partir de un objeto. */
                $array3 = [];
                $array3["Id"] = $value3->{"usuario.usuario_id"};
                $array3["UserId"] = $value3->{"usuario.usuario_id"};
                $array3["UserName"] = $value3->{"usuario.nombre"};
                $array3["Currency"] = $value3->{"usuario.moneda"};
                $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};

                /* Crea un arreglo asociativo con información de un usuario y su estado. */
                $array3["Name"] = $value3->{"usuario.nombre"};
                $array3["SystemName"] = 22;
                $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};
                $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};

                /* asigna valores y define íconos según el perfil de usuario. */
                $array3["PlayerCount"] = 0;
                $array3["Partner"] = $value3->{"usuario.mandante"};

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
                    case "CAJERO":
                        $array4["icon"] = "icon-shop";
                        break;
                }


                /* Asigna $array3 a una estructura multidimensional basada en varios IDs de concesionario. */
                $arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})] = $array3;

            }


        }


        /* Suma créditos base de punto de venta al saldo del agente. */
        $balanceAgent = $balanceAgent + $value3->{"punto_venta.creditos_base"};

    }


    /* Inicializa un arreglo vacío llamado $arrayfinal2 en PHP. */
    $arrayfinal2 = array();

    // Concesionarios


    foreach ($arrayfinal[0][0][0][0] as $item) {

        /* inicializa arreglos y agrega elementos de otro arreglo a ellos. */
        $item["Children"] = array();
        $item["data"] = array();


        foreach ($arrayfinal[$item["Id"]][0][0][0] as $itemH) {
            array_push($item["Children"], $itemH);
            array_push($item["data"], $itemH);

        }


        foreach ($item["Children"] as $keyHH2 => $itemHH2) {

            /* asigna un valor de un array a una variable clave anterior. */
            $keyHH2G = $keyHH2;
            $keyHH2 = $itemHH2["Id"];
            if ($itemHH2["Type"] == "PUNTOVENTA") {


                /* Consulta de usuarios cajeros vinculados a un Punto de Venta y Mandante específico. */
                $PuntoVentaId = $itemHH2["Id"];
                $Mandante = $itemHH2["Partner"];
                $sql =
                    "SELECT usuario.usuario_id,usuario.nombre,usuario.moneda,usuario.mandante,pais.pais_nom,usuario_perfil.perfil_id
 FROM usuario
LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id)
LEFT OUTER JOIN usuario_perfil ON (usuario.usuario_id=usuario_perfil.usuario_id)
WHERE usuario.puntoventa_id = $PuntoVentaId
AND usuario.mandante =  $Mandante
 AND usuario_perfil.perfil_id =  'CAJERO'
ORDER  BY usuario.usuario_id; ";

                $BonoInterno = new BonoInterno();


                /* Se crea un objeto Usuario y se obtiene una conexión a la base de datos. */
                $Usuario = new \Backend\dto\Usuario();

                $UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();

                $transaccion = $UsuarioMySqlDAO->getTransaction();
                $transaccion->getConnection()->beginTransaction();

                /* Se crea un arreglo de cajeros con información de usuarios extraídos de una consulta. */
                $Usuarios = $BonoInterno->execQuery($transaccion, $sql);

                $cajeros = array();
                foreach ($Usuarios as $key => $cajeval) {

                    $cajeros["UserId"] = $cajeval->{"usuario.usuario_id"};
                    $cajeros["UserName"] = $cajeval->{"usuario.nombre"};
                    $cajeros["Currency"] = $cajeval->{"usuario.moneda"};
                    $cajeros["Country"] = $cajeval->{"usuario.mandante"} . '-' . $cajeval->{"pais.pais_nom"};;
                    $cajeros["Name"] = $cajeval->{"usuario.nombre"};;
                    $cajeros["SystemName"] = 0;
                    $cajeros["IsSuspended"] = ($cajeval->{"usuario.estado"} == 'A' ? false : true);

                    $cajeros["Type"] = $cajeval->{"usuario_perfil.perfil_id"};
                    $cajeros["PlayerCount"] = 0;
                    $cajeros["Partner"] = $cajeval->{"usuario.mandante"};
                    $cajeros["AgentBalance"] = 0;
                    $cajeros["AgentBalance2"] = 0;
                    $cajeros["flag"] = strtolower($cajeval->{"pais.iso"});
                    $cajeros["icon"] = "icon-shop";
                }


                /* Se inicializan arrays y se agregan datos si no están vacíos. */
                $itemHH2["Children"] = array();
                $itemHH2["data"] = array();
                if (!empty($cajeros)) {
                    array_push($itemHH2["Children"], $cajeros);
                    array_push($itemHH2["data"], $cajeros);
                }
            } else {
                /* inicializa arrays vacíos para "Children" y "data" en $itemHH2. */

                $itemHH2["Children"] = array();
                $itemHH2["data"] = array();
            }


            /* Itera sobre un arreglo, agregando elementos a estructuras si cumplen una condición. */
            foreach ($arrayfinal[$item["Id"]][$keyHH2][0][0] as $itemH) {
                if ($itemH["Id"] != $keyHH2) {
                    array_push($itemHH2["Children"], $itemH);
                    array_push($itemHH2["data"], $itemH);

                }

            }

            foreach ($itemHH2["Children"] as $keyHH3 => $itemHH3) {

                /* asigna un nuevo valor a la variable $keyHH3, guardando el antiguo. */
                $keyHH3G = $keyHH3;
                $keyHH3 = $itemHH3["Id"];
                if ($itemHH3["Type"] == "PUNTOVENTA") {


                    /* Consulta SQL para obtener usuarios con perfil 'CAJERO' según condiciones específicas. */
                    $PuntoVentaId = $itemHH3["Id"];
                    $Mandante = $itemHH3["Partner"];
                    $sql =
                        "SELECT usuario.usuario_id,usuario.nombre,usuario.moneda,usuario.mandante,pais.pais_nom,usuario_perfil.perfil_id
 FROM usuario
LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id)
LEFT OUTER JOIN usuario_perfil ON (usuario.usuario_id=usuario_perfil.usuario_id)
WHERE usuario.puntoventa_id = $PuntoVentaId
AND usuario.mandante =  $Mandante
 AND usuario_perfil.perfil_id =  'CAJERO'
ORDER  BY usuario.usuario_id; ";

                    $BonoInterno = new BonoInterno();


                    /* Código que crea un usuario y establece una conexión a la base de datos. */
                    $Usuario = new \Backend\dto\Usuario();

                    $UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();

                    $transaccion = $UsuarioMySqlDAO->getTransaction();
                    $transaccion->getConnection()->beginTransaction();

                    /* procesa usuarios y crea un array con datos relevantes sobre cajeros. */
                    $Usuarios = $BonoInterno->execQuery($transaccion, $sql);

                    $cajeros = array();
                    foreach ($Usuarios as $key => $cajeval) {

                        $cajeros["UserId"] = $cajeval->{"usuario.usuario_id"};
                        $cajeros["UserName"] = $cajeval->{"usuario.nombre"};
                        $cajeros["Currency"] = $cajeval->{"usuario.moneda"};
                        $cajeros["Country"] = $cajeval->{"usuario.mandante"} . '-' . $cajeval->{"pais.pais_nom"};
                        $cajeros["Name"] = $cajeval->{"usuario.nombre"};
                        $cajeros["SystemName"] = 0;
                        $cajeros["IsSuspended"] = ($cajeval->{"usuario.estado"} == 'A' ? false : true);

                        $cajeros["Type"] = $cajeval->{"usuario_perfil.perfil_id"};
                        $cajeros["PlayerCount"] = 0;
                        $cajeros["Partner"] = $cajeval->{"usuario.mandante"};
                        $cajeros["AgentBalance"] = 0;
                        $cajeros["AgentBalance2"] = 0;
                        $cajeros["flag"] = strtolower($cajeval->{"pais.iso"});
                        $cajeros["icon"] = "icon-shop";
                    }


                    /* inicializa arrays y añade elementos de 'cajeros' a ellos, si no están vacíos. */
                    $itemHH3["Children"] = array();
                    $itemHH3["data"] = array();
                    if (!empty($cajeros)) {
                        array_push($itemHH3["Children"], $cajeros);
                        array_push($itemHH3["data"], $cajeros);
                    }
                } else {
                    /* Inicializa arrays vacíos "Children" y "data" si la condición no se cumple. */

                    $itemHH3["Children"] = array();
                    $itemHH3["data"] = array();
                }


                /* Recorre un array y añade elementos a "Children" y "data" de "itemHH3". */
                foreach ($arrayfinal[$item["Id"]][$keyHH2][$keyHH3][0] as $itemH) {
                    array_push($itemHH3["Children"], $itemH);
                    array_push($itemHH3["data"], $itemH);

                }


                /* Recorre un arreglo y organiza datos en estructuras jerárquicas. */
                foreach ($itemHH3["Children"] as $keyHH => $itemHH) {
                    $keyHHG = $keyHH;
                    $keyHH = $itemHH["Id"];
                    $itemHH["Children"] = array();
                    $itemHH["data"] = array();

                    foreach ($arrayfinal[$item["Id"]][$keyHH2][$keyHH3][$keyHH] as $itemH) {
                        array_push($itemHH["Children"], $itemH);
                        array_push($itemHH["data"], $itemH);

                    }

                    if (oldCount($itemHH["Children"]) > 0) {
                        $itemHH3["Children"][$keyHHG]["Children"] = $itemHH["Children"];
                        $itemHH3["Children"][$keyHHG]["data"] = $itemHH["Children"];

                    }

                }

                /* Verifica si hay hijos en $itemHH3 y los asigna a $itemHH2. */
                if (oldCount($itemHH3["Children"]) > 0) {

                    $itemHH2["Children"][$keyHH3G]["Children"] = $itemHH3["Children"];
                    $itemHH2["Children"][$keyHH3G]["data"] = $itemHH3["Children"];
                }
            }

            /* Verifica si hay hijos y asigna datos a la estructura del ítem. */
            if (oldCount($itemHH2["Children"]) > 0) {

                $item["Children"][$keyHH2G]["Children"] = $itemHH2["Children"];
                $item["Children"][$keyHH2G]["data"] = $itemHH2["Children"];
                $item["data"][$keyHH2G]["Children"] = $itemHH2["Children"];
                $item["data"][$keyHH2G]["data"] = $itemHH2["Children"];
            }
        }


        /* Agrega un elemento `$item` al final del arreglo `$arrayfinal2`. */
        array_push($arrayfinal2, $item);

    }

    /* Se asigna un array según el perfil de sesión del usuario. */
    $arrayf = $arrayfinal2;

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $arrayf = $arrayf[0]["Children"];

    }


    /* verifica el perfil de sesión y modifica un array accordingly. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        $arrayf = $arrayf[0]["Children"][0]["Children"];
    }

    if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        $arrayf = $arrayf[0]["Children"][0]["Children"][0]["Children"];
    }


    if (false) {
        foreach ($arrayfinal[0][0][0][0] as $item) {

            /* agrega elementos a la clave "Children" de un arreglo. */
            $item["Children"] = array();


            foreach ($arrayfinal[$item["Id"]][0][0][0] as $itemH) {
                array_push($item["Children"], $itemH);

            }

            if ($item["Id"] != "0") {
                //SubConcesionarios
                foreach ($arrayfinal[$item["Id"]] as $key2 => $item2) {


                    /* Se inicializa un array vacío llamado "Children" dentro del arreglo $item2. */
                    $item2["Children"] = array();


                    if ($key2 != "0") {
                        //SubConcesionarios2

                        foreach ($arrayfinal[$item["Id"]][$key2] as $key3 => $item3) {

                            /* Se inicializa un array vacío llamado "Children" dentro del elemento "item3". */
                            $item3["Children"] = array();

                            if ($key3 != "0") {
                                //SubConcesionario3
                                foreach ($arrayfinal[$item["Id"]][$key2][$key3] as $key4 => $item4) {

                                    /* Se inicializa un arreglo vacío llamado "Children" dentro del arreglo asociativo "item4". */
                                    $item4["Children"] = array();


                                    /* Recorre estructuras anidadas, buscando coincidencias y modificando hijos en elementos específicos. */
                                    foreach ($item["Children"] as $keyHH2 => $itemHH2) {
                                        foreach ($itemHH2["Children"] as $keyHH3 => $itemHH3) {
                                            foreach ($itemHH3["Children"] as $keyHH => $itemHH) {
                                                if ($itemHH["Id"] == $key4) {
                                                    $itemHH["Children"] = array();

                                                    foreach ($arrayfinal[$item["Id"]][$key2][$key3][$key4] as $itemH) {
                                                        array_push($itemHH["Children"], $itemH);

                                                    }
                                                    unset($item4['0']);
                                                    array_push($itemHH["Children"], $item4);


                                                }
                                                $item3["Children"] = $itemHH;
                                            }
                                        }
                                    }

                                }
                            }


                            /* organiza y modifica estructuras de datos anidando elementos en arrays hijos. */
                            foreach ($item["Children"] as $keyHH2 => $itemHH2) {
                                foreach ($itemHH2["Children"] as $keyHH => $itemHH) {
                                    if ($itemHH["Id"] == $key3) {
                                        $itemHH["Children"] = array();

                                        foreach ($arrayfinal[$item["Id"]][$key2][$key3][0] as $itemH) {
                                            array_push($itemHH["Children"], $itemH);

                                        }

                                        unset($item3['0']);
                                        array_push($itemHH["Children"], $item3);

                                        unset($itemHH['0']);

                                    }
                                    $item3["Children"] = $itemHH;
                                }
                            }
                        }
                    }


                    /* itera sobre elementos, ajustando sus hijos y modificando estructuras de datos. */
                    foreach ($item["Children"] as $keyHH => $itemHH) {
                        if ($itemHH["Id"] == $key2) {
                            $itemHH["Children"] = array();

                            foreach ($arrayfinal[$item["Id"]][$key2][0][0] as $itemH) {
                                array_push($itemHH["Children"], $itemH);

                            }

                            unset($item2['0']);
                            array_push($itemHH["Children"], $item2);

                            unset($itemHH['0']);

                        }
                        $item["Children"][$keyHH] = $itemHH;
                    }
                }
            }

            /* Agrega un elemento al final del arreglo `$arrayfinal2`. */
            array_push($arrayfinal2, $item);

        }

    }


} else {


    /* Se inicializa un arreglo vacío llamado $rules para almacenar reglas. */
    $rules = [];


    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

        /* Se están añadiendo reglas de validación a un array en PHP. */
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


        /* Añade una regla al filtro si $UserIdAgent2 no está vacío. */
        if ($UserIdAgent2 != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $UserIdAgent2, "op" => "eq"));

        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* Convierte un filtro a JSON y agrega reglas si hay un login válido. */
        $json2 = json_encode($filtro);

        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

        }


        /* Se verifica un país seleccionado para filtrar usuarios en una consulta. */
        if ($CountrySelect != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

        }

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

        /* Se agregan reglas de filtrado para consultar datos de concesionarios y usuarios. */
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


        /* Agrega reglas de búsqueda según la entrada de usuario y país seleccionados. */
        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

        }

        if ($CountrySelect != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

        }

        /* Condicionalmente agrega reglas de filtro basadas en el ID de usuario proporcionado. */
        if ($UserId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* Codifica un filtro JSON y obtiene usuarios con parámetros personalizados. */
        $json2 = json_encode($filtro);

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {

        /* Se crean reglas para validar campos en un sistema de concesionarios. */
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


        /* Agrega reglas de validación según el login y país seleccionados. */
        if ($Login != "") {
            array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

        }

        if ($CountrySelect != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

        }

        /* verifica el ID de usuario y agrega reglas de filtrado. */
        if ($UserId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* Codifica un filtro JSON y obtiene perfiles de usuario personalizados en orden ascendente. */
        $json2 = json_encode($filtro);

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

    } else {

        /* Agrega reglas de filtrado a un array para validar condiciones de usuarios. */
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

        if ($UserIdAgent != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $UserIdAgent, "op" => "eq"));

        }

        // Si el usuario esta condicionado por País

        /* Agrega reglas basadas en condiciones de sesión para filtros de usuarios. */
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            /* agrega una regla si "mandanteLista" no está vacío o es "-1". */


            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* Se crea un filtro JSON y se obtiene información de usuarios según criterios específicos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);


        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom("usuario.mandante, usuario.usuario_id,usuario.moneda,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

    }


    /* Se decodifica un JSON de usuarios y se inicializa un array y un balance. */
    $usuarios = json_decode($usuarios);
    $arrayf = [];

    $balanceAgent = 0;


    foreach ($usuarios->data as $key => $value) {

        /* Inicializa variables y aumenta contadores según el perfil de usuario en sesión. */
        $puntosdeVenta = 0;
        $agentesConce2 = 0;
        $array = [];

        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            $agentesConce2++;
        }


        /* Asignación de propiedades del objeto $value a un array asociativo en PHP. */
        $array["Id"] = $value->{"usuario.usuario_id"};
        $array["UserId"] = $value->{"usuario.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};
        $array["Country"] = $value->{"usuario.mandante"} . '-' . $value->{"pais.pais_nom"};
        $array["Currency"] = $value->{"usuario.moneda"};
        $array["Name"] = $value->{"usuario.nombre"};

        /* Se asignan valores a un array basado en propiedades de un objeto. */
        $array["SystemName"] = 22;
        $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
        $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};
        $array["AgentBalance2"] = $value->{"punto_venta.cupo_recarga"};
        $array["Partner"] = $value->{"usuario.mandante"};
        $array["PlayerCount"] = 0;

        /* Se asignan iconos a usuarios según su perfil en un array. */
        $array["Children"] = array();
        $array["data"] = array();

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
            case "CAJERO":
                $array4["icon"] = "icon-shop";
                break;
        }

        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {


            /* define reglas de validación para consultas basadas en condiciones específicas. */
            $rules2 = array();

            array_push($rules2, array("field" => "concesionario.usupadre2_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
            array_push($rules2, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
            array_push($rules2, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));


            /* Agrega reglas de filtrado para usuarios según condiciones específicas. */
            array_push($rules2, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

            if ($Login != "") {
                array_push($rules2, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));
            }

            if ($CountrySelect != "") {
                array_push($rules2, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

            }

            /* Agrega condiciones a un arreglo según la validez de $UserId y $BetShopId. */
            if ($UserId != "") {
                array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

            }

            if ($BetShopId != "") {
                array_push($rules2, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

            }


            /* Se crea un filtro, se convierte a JSON y se obtienen detalles de usuarios. */
            $filtro = array("rules" => $rules2, "groupOp" => "AND");

            $json2 = json_encode($filtro);

            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);
            $usuariosdetalle = json_decode($usuariosdetalle);


            foreach ($usuariosdetalle->data as $key2 => $value2) {
                $puntosdeVenta++;


                /* Se crea un arreglo asociativo con datos de usuario y país. */
                $array2 = [];

                $array2["UserName"] = $value2->{"usuario.nombre"};
                $array2["Currency"] = $value2->{"usuario.moneda"};
                $array2["Country"] = $value2->{"usuario.mandante"} . '-' . $value2->{"pais.pais_nom"};

                $array2["Name"] = $value2->{"usuario.nombre"};

                /* Se asignan valores a un array asociativo basado en propiedades de un objeto. */
                $array2["SystemName"] = 22;
                $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
                $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                $array2["AgentBalance2"] = $value2->{"punto_venta.cupo_recarga"};
                $array2["PlayerCount"] = 0;
                $array2["Id"] = $value2->{"usuario.usuario_id"};

                /* asigna valores a un array basado en el perfil de usuario. */
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
                    case "CAJERO":
                        $array4["icon"] = "icon-shop";
                        break;
                }


                /* Agrega elementos de $array2 a los arrays "Children" y "data" de $array. */
                array_push($array["Children"], $array2);
                array_push($array["data"], $array2);


            }


        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
            /* Condicional que verifica si el perfil de sesión es "CONCESIONARIO2". */


        } else {

            /* Se crea una regla de validación si $UserIdAgent2 no está vacío. */
            $rules2 = array();

            $debeUserIdAgent2 = false;


            if ($UserIdAgent2 != "") {
                $debeUserIdAgent2 = true;
                array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserIdAgent2, "op" => "eq"));

            }


            /* Se agregan reglas de filtrado a un array para consultas de base de datos. */
            array_push($rules2, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

            array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
            array_push($rules2, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
            array_push($rules2, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

            /* Se construye un filtro en JSON y se obtiene información detallada de usuarios. */
            $filtro = array("rules" => $rules2, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);
            $usuariosdetalle = json_decode($usuariosdetalle);

            foreach ($usuariosdetalle->data as $key2 => $value2) {
                $agentesConce2++;

                /* crea un array asociativo con datos de usuario y su país. */
                $array2 = [];

                $array2["UserName"] = $value2->{"usuario.nombre"};
                $array2["Currency"] = $value2->{"usuario.moneda"};
                $array2["Country"] = $value2->{"usuario.mandante"} . '-' . $value2->{"pais.pais_nom"};
                $array2["Name"] = $value2->{"usuario.nombre"};

                /* Asignación de datos de usuario y estado en un arreglo asociativo PHP. */
                $array2["Id"] = $value2->{"usuario.usuario_id"};
                $array2["UserId"] = $value2->{"usuario.usuario_id"};

                $array2["SystemName"] = 22;
                $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
                $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};

                /* crea un arreglo `$array2` con información de agentes y parámetros asociados. */
                $array2["AgentBalance2"] = $value2->{"punto_venta.cupo_recarga"};
                $array2["PlayerCount"] = 0;
                $array2["Children"] = array();
                $array2["data"] = array();
                $array2["Partner"] = $value->{"usuario.mandante"};

                $array2["flag"] = strtolower($value2->{"pais.iso"});

                /* Asignación de íconos según el perfil de usuario en función de su tipo. */
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
                    case "CAJERO":
                        $array4["icon"] = "icon-shop";
                        break;
                }

                if (true) {

                    /* Define reglas para validar datos de concesionarios y usuarios en un sistema. */
                    $rules3 = array();

                    array_push($rules3, array("field" => "concesionario.usupadre2_id", "data" => $value2->{"usuario.usuario_id"}, "op" => "eq"));
                    array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                    array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                    array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));


                    /* Se añaden reglas para filtrar usuarios activos y opcionalmente por login. */
                    array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

                    if ($Login != "") {
                        array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

                    }

                    /* Agrega reglas de filtrado basadas en país y usuario si están definidos. */
                    if ($CountrySelect != "") {
                        array_push($rules3, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

                    }

                    if ($UserId != "") {
                        array_push($rules3, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

                    }


                    /* Condiciona la adición de reglas al filtro según el BetShopId proporcionado. */
                    if ($BetShopId != "") {
                        array_push($rules3, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

                    }

                    $filtro = array("rules" => $rules3, "groupOp" => "AND");

                    /* convierte un filtro a JSON y obtiene detalles de usuarios personalizados. */
                    $json3 = json_encode($filtro);

                    $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
                    $usuariosdetalle = json_decode($usuariosdetalle);


                    foreach ($usuariosdetalle->data as $key3 => $value3) {
                        $puntosdeVenta++;

                        /* Crea un array asociativo con datos de usuario específicos. */
                        $array3 = [];
                        $array3["Id"] = $value3->{"usuario.usuario_id"};
                        $array3["UserId"] = $value3->{"usuario.usuario_id"};
                        $array3["UserName"] = $value3->{"usuario.nombre"};
                        $array3["Currency"] = $value3->{"usuario.moneda"};
                        $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};

                        /* asigna valores a un array a partir de propiedades de un objeto. */
                        $array3["Name"] = $value3->{"usuario.nombre"};
                        $array3["SystemName"] = 22;
                        $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                        $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                        $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};
                        $array3["PlayerCount"] = 0;

                        /* Se asignan valores y se define un icono según el perfil de usuario. */
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
                            case "CAJERO":
                                $array4["icon"] = "icon-shop";
                                break;
                        }


                        /* Agrega el contenido de `$array3` a dos claves de `$array2`. */
                        array_push($array2["Children"], $array3);
                        array_push($array2["data"], $array3);


                    }

                    /* verifica condiciones y agrega datos a un arreglo si se cumplen. */
                    if ($Login != "" || $UserId != "" || $BetShopId != "" || $UserIdAgent2 != "") {
                        if (oldCount($usuariosdetalle->data) > 0) {
                            array_push($array["Children"], $array2);
                            array_push($array["data"], $array2);

                        }
                    } else {
                        /* Inserta $array2 en las claves "Children" y "data" del array principal. */

                        array_push($array["Children"], $array2);
                        array_push($array["data"], $array2);

                    }

                }


            }
            if ($UserIdAgent2 == "") {


                /* Se definen reglas de validación para concesionarios y perfiles de usuario. */
                $rules4 = array();

                array_push($rules4, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                array_push($rules4, array("field" => "concesionario.usupadre2_id", "data" => 0, "op" => "eq"));
                array_push($rules4, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules4, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

                /* Agrega reglas de filtrado a un array para validar datos de usuarios y concesionarios. */
                array_push($rules4, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

                array_push($rules4, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

                if ($Login != "") {
                    array_push($rules4, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

                }

                /* Agrega reglas de filtrado basadas en el país o ID de usuario proporcionados. */
                if ($CountrySelect != "") {
                    array_push($rules4, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

                }

                if ($UserId != "") {
                    array_push($rules4, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

                }


                /* verifica un ID de apuestas y agrega una regla a un filtro. */
                if ($BetShopId != "") {
                    array_push($rules4, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

                }


                $filtro4 = array("rules" => $rules4, "groupOp" => "AND");

                /* convierte datos a formato JSON y obtiene detalles de usuarios. */
                $json4 = json_encode($filtro4);

                $usuariosdetalle4 = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json4, true);
                $usuariosdetalle4 = json_decode($usuariosdetalle4);


                foreach ($usuariosdetalle4->data as $key4 => $value4) {
                    $puntosdeVenta++;

                    /* crea un arreglo con información de usuarios extraída de un objeto. */
                    $array4 = [];
                    $array4["Id"] = $value4->{"usuario.usuario_id"};
                    $array4["UserId"] = $value4->{"usuario.usuario_id"};
                    $array4["UserName"] = $value4->{"usuario.nombre"};
                    $array4["Currency"] = $value4->{"usuario.moneda"};
                    $array4["Country"] = $value4->{"usuario.mandante"} . '-' . $value4->{"pais.pais_nom"};

                    /* Se asignan valores a un arreglo asociativo desde un objeto. */
                    $array4["Name"] = $value4->{"usuario.nombre"};
                    $array4["SystemName"] = 22;
                    $array4["IsSuspended"] = ($value4->{"usuario.estado"} == 'A' ? false : true);
                    $array4["AgentBalance"] = $value4->{"punto_venta.creditos_base"};
                    $array4["AgentBalance2"] = $value4->{"punto_venta.cupo_recarga"};
                    $array4["PlayerCount"] = 0;


                    /* Asigna valores a un array según condiciones de perfil de usuario. */
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
                        case "CAJERO":
                            $array4["icon"] = "icon-shop";
                            break;
                    }

                    /* Agrega `$array4` a los arrays "Children" y "data" en `$array`. */
                    array_push($array["Children"], $array4);
                    array_push($array["data"], $array4);

                }
            }

        }


        /* Verifica condiciones para agregar un elemento a un array y suma al balance de agente. */
        if (((($Login != "" || $UserId != "" || $BetShopId != "") && $puntosdeVenta > 0) || (($Login == "" && $UserId == "" && $BetShopId == ""))) && (($agentesConce2 > 0 && $UserIdAgent2 != "") || $UserIdAgent2 == "")) {
            array_push($arrayf, $array);
        }

        $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
    }


    /* Se inicializa un arreglo vacío llamado "rules" para almacenar reglas. */
    $rules = [];


    if ($_SESSION["win_perfil"] == "CONCESIONARIO" && $UserIdAgent2 == "") {

        /* Se definen reglas de filtrado para parámetros de concesionarios y usuarios. */
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => 0, "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

        array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


        /* Agrega condiciones de búsqueda a un array según el login y país seleccionados. */
        if ($Login != "") {
            array_push($rules2, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

        }

        if ($CountrySelect != "") {
            array_push($rules2, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));

        }

        /* Se añaden reglas de filtrado basado en UserId y BetShopId. */
        if ($UserId != "") {
            array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

        }

        if ($BetShopId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

        }


        /* crea un filtro JSON para recuperar perfiles de usuario según ciertas reglas. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);

    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        /*array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
        array_push($rules, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));


        if ($Login != "") {
            array_push($rules2, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

        }

        if ($UserId != "") {
            array_push($rules2, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

        }

        if ($BetShopId != "") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.mandante,usuario.moneda,usuario.usuario_id,punto_venta.cupo_recarga,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json2, true);*/

    } else {
        /* muestra una estructura condicional sin acciones especificadas en el bloque else. */

    }


    /* Convierte una cadena JSON en un objeto o arreglo en PHP. */
    $usuarios = json_decode($usuarios);


    foreach ($usuarios->data as $key => $value) {

        /* Se crea un array con información del usuario extraída de un objeto. */
        $array = [];

        $array["Id"] = $value->{"usuario.usuario_id"};
        $array["UserId"] = $value->{"usuario.usuario_id"};

        $array["UserName"] = $value->{"usuario.nombre"};

        /* asigna valores a un arreglo basado en propiedades de un objeto. */
        $array["Currency"] = $value->{"usuario.moneda"};
        $array["Country"] = $value->{"usuario.mandante"} . '-' . $value->{"pais.pais_nom"};
        $array["Name"] = $value->{"usuario.nombre"};
        $array["SystemName"] = 22;
        $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
        $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};

        /* asigna valores a un array basado en propiedades de un objeto. */
        $array["AgentBalance2"] = $value->{"punto_venta.cupo_recarga"};
        $array["PlayerCount"] = 0;
        $array["Children"] = array();
        $array["data"] = array();

        $array["Partner"] = $value->{"usuario.mandante"};

        /* Asigna un ícono según el perfil del usuario y el país en minúsculas. */
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

        /* agrega un array y suma un valor a balanceAgent. */
        array_push($arrayf, $array);

        $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
    }

}


/* Filtra un array por "UserId" coincidente con "consultaAgente" y reinicia el array. */
if ($consultaAgente != "0" && $consultaAgente != null) {

    foreach ($arrayf as $key => $value) {

        if ($value["UserId"] == $consultaAgente) {
            $arrayf = [];
            array_push($arrayf, $value);
        }

        foreach ($value["Children"] as $key2 => $value2) {

            if ($value2["UserId"] == $consultaAgente) {
                $arrayf = [];
                array_push($arrayf, $value2);
            }
        }

    }

}


/* Código para estructurar una respuesta con éxito, sin errores ni mensajes de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] ["Children"] = $arrayf;

/* Se estructura una respuesta JSON con datos sobre recuentos y sumas de balances. */
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
