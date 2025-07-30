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
use Backend\dto\UsuarioLog2;
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
use Backend\mysql\UsuarioLog2MySqlDAO;
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
 * Client/GetClientSpMyInfo
 *
 * Obtener la información del usuario actual.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param int $params ->MaxRows Número máximo de registros a devolver.
 * @param int $params ->OrderedItem Campo por el cual se ordenarán los resultados.
 * @param int $params ->SkeepRows Número de registros a omitir para la paginación.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Errores del modelo.
 * - Data (array): Información del usuario actual.
 *
 * @throws Exception Si ocurre un error general o de validación.
 */


/* Se crea un objeto Usuario y se obtienen parámetros JSON desde la entrada. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$id = $_SESSION["usuario"];


$dniFrontBack = 0;

/* Se inicializa una variable y un objeto, y se establece una condición para continuar. */
$dniFront = 0;

$ConfigurationEnvironment = new ConfigurationEnvironment();


$seguir = true;

/* Verifica el perfil del usuario y crea instancias de configuración y usuario. */
if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {
    // $seguir = false;
}
$ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


if ($id != "" && $seguir) {

    /* inicializa una comisión de usuario y prepara un arreglo para resultados. */
    $MyComission = array();

    $Usuario = new Usuario($id);
    $result_array = array();

    $campos = "";

    /* Se crean reglas de filtrado para clasificar datos según tipo y estado. */
    $cont = 0;

    $rules = [];

    array_push($rules, array("field" => "clasificador.tipo", "data" => "PCOM", "op" => "eq"));
    array_push($rules, array("field" => "clasificador.estado", "data" => "A", "op" => "eq"));

    /* Se define un filtro JSON con reglas para un concesionario en PHP. */
    array_push($rules, array("field" => "DISP", "data" => "DISP", "op" => "neq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $Concesionario = new Concesionario();

    /* Recupera y transforma productos en un array basado en condiciones específicas. */
    $productos = $Concesionario->getConcesionariosProductoInternoCustom("clasificador.clasificador_id,clasificador.abreviado,clasificador.descripcion, concesionario.porcenpadre1,concesionario.porcenpadre2,concesionario.porcenpadre3,concesionario.porcenpadre4,concesionario.porcenhijo  ", "clasificador.clasificador_id", "asc", 0, 10000, $jsonfiltro, true, $id);
    $productos = json_decode($productos);


    $final = array();

    foreach ($productos->data as $producto) {

        $array = array(
            "Id" => $producto->{"clasificador.clasificador_id"},
            "Name" => $producto->{"clasificador.descripcion"},
            "Value" => ($producto->{"concesionario.porcenhijo"} == "") ? 0 : $producto->{"concesionario.porcenhijo"}

        );
        //array_push($final, $array);

        if ($producto->{"concesionario.porcenhijo"} != '' && $producto->{"concesionario.porcenhijo"} != '0') {

            array_push($MyComission, $array);
        }
    }


    /* asigna valores de parámetros y establece valor por defecto a SkeepRows. */
    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* inicializa variables si están vacías, asignando valores predeterminados. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 10;
    }


    /* Se generan reglas de validación basadas en el perfil del usuario. */
    $rules = [];
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $id, "op" => "eq"));

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }


    /* Se filtran y obtienen datos de usuarios en formato JSON. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $usuarios = $Usuario->getUsuariosSuperCustom("  DISTINCT (usuario.usuario_id), usuario.verifcedula_ant,  usuario.verifcedula_post,usuario.creditos_afiliacion,usuario.usumodif_id,usuario.token_google,usuario.token_local,usuario.ubicacion_longitud,usuario.ubicacion_latitud,usuario.restriccion_ip,usuario.usuario_ip,usuario.permite_activareg,usuario.bloqueo_ventas,usuario.nombre,usuario.idioma,usuario_config.*,usuario.documento_validado,usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,ciudad.*,departamento.*,pais.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,usuario_perfil.*,punto_venta.*,concesionario.* ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $usuarios = json_decode($usuarios);


    /* Se inicializan variables y se verifica una condición sobre la cédula del usuario. */
    $usuariosFinal = [];

    $MaxRows = 2;
    $OrderedItem = 1;
    $SkeepRows = 0;

    if ($Usuario->verifcedulaAnt == "S") {
        $dniFront = 3;
    }


    /* Se verifica el estado del usuario y asigna valores a variables según condiciones. */
    if ($Usuario->verifcedulaPost == "S") {
        $dniFrontBack = 3;
    }

    if (($dniFront == 0 || $dniFrontBack == 0)) {

        if ($Usuario->estadoJugador == 'NN') {

        }
        if (substr($Usuario->estadoJugador, 0, 1) == 'P') {
            $dniFront = 2;
        }

        if (substr($Usuario->estadoJugador, 1, 1) == 'P') {
            $dniFrontBack = 2;
        }
    }

    if ($ConfigurationEnvironment->isDevelopment() || false) {


        /* Se crean reglas de filtrado para consultar usuarios basados en condiciones específicas. */
        $rules = [];

        array_push($rules, array("field" => "usuario_log2.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "usuario_log2.tipo", "data" => "LOGIN", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");


        /* obtiene registros de usuario en ambiente de desarrollo, utilizando filtros en JSON. */
        $json2 = json_encode($filtro);

        $select = " usuario_log2.* ";

        if ($ConfigurationEnvironment->isDevelopment()) {


            $UsuarioLog = new UsuarioLog2();
            $data2 = $UsuarioLog->getUsuarioLog2sCustom($select, "usuario_log2.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, "");
            $data2 = json_decode($data2);

            //$fecha_ultima = '';
            //$ip_ultima = '';

            if ($data2->data[1]->{"usuario_log2.fecha_crea"} != "") {
                //$fecha_ultima = $data2->data[1]->{"usuario_log2.fecha_crea"};
                //$ip_ultima = $data2->data[1]->{"usuario_log2.usuario_ip"};
            }
        }


        if (($dniFront == 0 || $dniFrontBack == 0) && false) {

            /* Inicializa variables para gestionar paginación y filtrado de resultados en una consulta. */
            $MaxRows = 10;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $whereStr = '';


            /* asigna condiciones a la variable $whereStr según valores de $dniFront y $dniFrontBack. */
            if ($dniFront == 0) {
                $whereStr = "'USUDNIANTERIOR'";
            }

            if ($dniFrontBack == 0) {
                if ($whereStr != '') {
                    $whereStr = $whereStr . ',';
                }
                $whereStr = $whereStr . "'USUDNIPOSTERIOR'";
            }


            /* Define reglas de filtrado para usuarios y estados específicos en un arreglo. */
            $rules = [];

            array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usuario_log2.tipo", "data" => "$whereStr", "op" => "in"));
            array_push($rules, array("field" => "usuario_log2.estado", "data" => "P", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");


            /* Filtra y procesa registros de usuario, configurando variables según el tipo encontrado. */
            $jsonfiltro = json_encode($filtro);

            $UsuarioLog = new UsuarioLog2();
            $data = $UsuarioLog->getUsuarioLog2sCustom("usuario_log2.*", "usuario_log2.usuariolog2_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, $grouping);
            $data = json_decode($data);

            foreach ($data->data as $key => $value) {

                switch ($value->{'usuario_log2.tipo'}) {
                    case "USUDNIANTERIOR":
                        if ($dniFront == 0) {
                            $dniFront = 2;
                        }

                        break;
                    case "USUDNIPOSTERIOR":
                        if ($dniFrontBack == 0) {
                            $dniFrontBack = 2;
                        }

                        break;
                }
            }

            if (($dniFront == 0 || $dniFrontBack == 0)) {


                /* Se definen variables para controlar la paginación de datos en una consulta. */
                $MaxRows = 10;
                $OrderedItem = 1;
                $SkeepRows = 0;


                $whereStr = '';


                /* establece condiciones para construir una cadena de criterios de búsqueda. */
                if ($dniFront == 0) {
                    $whereStr = "'USUDNIANTERIOR'";
                }

                if ($dniFrontBack == 0) {
                    if ($whereStr != '') {
                        $whereStr = $whereStr . ',';
                    }
                    $whereStr = $whereStr . "'USUDNIPOSTERIOR'";
                }


                /* Se crean reglas de filtrado para consultas con condiciones específicas sobre usuarios. */
                $rules = [];

                array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_log2.tipo", "data" => $whereStr, "op" => "in"));
                array_push($rules, array("field" => "usuario_log2.estado", "data" => "NA", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* procesa registros de usuario y actualiza variables según tipos específicos. */
                $jsonfiltro = json_encode($filtro);

                $UsuarioLog = new UsuarioLog2();
                $data = $UsuarioLog->getUsuarioLog2sCustom("usuario_log2.*", "usuario_log2.usuariolog2_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, $grouping);
                $data = json_decode($data);

                foreach ($data->data as $key => $value) {

                    switch ($value->{'usuario_log2.tipo'}) {
                        case "USUDNIANTERIOR":
                            if ($dniFront == 0) {
                                $dniFront = 1;
                            }
                            break;
                        case "USUDNIPOSTERIOR":
                            if ($dniFrontBack == 0) {
                                $dniFrontBack = 1;
                            }
                            break;
                    }
                }
            }
        }

    }


    foreach ($usuarios->data as $key => $value) {


        /* Se inicializa un array vacío en la variable $array. */
        $array = [];

        if ($value->{"usuario_perfil.perfil_id"} != "USUONLINE") {

            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["id"] = $value->{"usuario.usuario_id"};
            $array["Ip"] = $value->{"usuario.dir_ip"};
            $array["Login"] = $value->{"usuario.login"};
            $array["Estado"] = array($value->{"usuario.estado"});
            $array["State"] = array($value->{"usuario.estado"});

            /* Asignación de valores a un arreglo desde un objeto con información del usuario. */
            $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
            $array["Idioma"] = $value->{"a.idioma"};
            $array["PreferredLanguage"] = $value->{"usuario.idioma"};
            $array["Name"] = $value->{"usuario.nombre"};
            $array["FirstName"] = $value->{"registro.nombre1"};
            $array["MiddleName"] = $value->{"registro.nombre2"};

            /* Asigna valores a un array a partir de propiedades de un objeto. */
            $array["LastName"] = $value->{"registro.apellido1"};
            $array["Email"] = $value->{"punto_venta.email"};
            $array["Address"] = $value->{"punto_venta.direccion"};
            $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
            $array["Intentos"] = $value->{"usuario.intentos"};
            $array["Observaciones"] = $value->{"usuario.observ"};

            /* Asigna valores a un array basado en propiedades de un objeto y ajusta el tipo. */
            $array["Moneda"] = $value->{"usuario.moneda"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["Type"] = $value->{"usuario_perfil.perfil_id"};

            if ($value->{"usuario_perfil.perfil_id"} == "AFILIADOR") {
                $array["Type"] = 1;
            } elseif (strpos($value->{"usuario_perfil.perfil_id"}, "CONCESIONARIO") !== FALSE) {
                /* Verifica si "perfil_id" contiene la palabra "CONCESIONARIO" en una condición elseif. */

                /* asigna valores a un array basado en propiedades de un objeto. */
                $array["Type"] = 0;
            }

            $array["Pais"] = $value->{"usuario.pais_id"};
            $array["City"] = $value->{"g.ciudad_nom"};

            $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};


            /* asigna datos de nacimiento a un arreglo asociativo. */
            $array["IsLocked"] = false;
            $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
            $array["BirthDate"] = $value->{"c.fecha_nacim"};

            $array["BirthDepartment"] = $value->{"g.depto_id"};
            $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};

            /* asigna valores a un array basándose en condiciones y propiedades de un objeto. */
            $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
            $array["Balance"] = $value->{"registro.creditos"} + $value->{"registro.creditos_base"};

            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["DocNumber"] = $value->{"registro.cedula"};


            if ($value->{"usuario_perfil.perfil_id"} == "PUNTOVENTA") {
                $array["DocNumber"] = $value->{"punto_venta.cedula"};
            }


            /* Asigna valores a un array a partir de un objeto con información del usuario. */
            $array["Gender"] = $value->{"registro.sexo"};
            $array["Language"] = $value->{"usuario.idioma"};
            $array["MobilePhone"] = $value->{"registro.celular"};
            $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};
            $array["Province"] = $value->{"registro.ciudad_id"};
            $array["RegionId"] = $value->{"usuario.pais_id"};

            /* Asigna valores a un array basado en propiedades de un objeto. */
            $array["CountryId"] = $value->{"usuario.pais_id"};
            $array["CountryName"] = $value->{"usuario.pais_id"};
            $array["ZipCode"] = $value->{"registro.codigo_postal"};
            $array["IsVerified"] = true;
            $array["IsActivate"] = ($value->{"usuario.estado"});
            $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});

            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});

            $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};
            $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
            $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);


            $array["ContactName"] = ($value->{"punto_venta.nombre_contacto"});

            /* asigna valores formateados a un arreglo a partir de un objeto. */
            $array["AffiliateCredits"] = number_format($value->{"usuario.creditos_afiliacion"}, 2);

            $array["Description"] = $value->{"punto_venta.descripcion"};
            $array["Phone"] = $value->{"punto_venta.telefono"};
            $array["RegionId"] = $value->{"departamento.depto_id"};
            $array["CityId"] = $value->{"ciudad.ciudad_id"};

            /* asigna valores de un objeto a un array asociativo. */
            $array["Pinagent"] = $value->{"usuario_config.pinagent"};
            $array["Lockedsales"] = $value->{"usuario.bloqueo_ventas"};
            $array["PrintReceiptBox"] = $value->{"usuario_config.recibo_caja"};
            $array["AllowsRecharges"] = $value->{"usuario_config.permite_recarga"};
            $array["ActivateRegistration"] = $value->{"usuario.permite_activareg"};
            $array["District"] = $value->{"punto_venta.barrio"};

            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["LastModifiedUser"] = $value->{"usuario.usumodif_id"};
            $array["LastIPaddress"] = $value->{"usuario.dir_ip"};
            $array["IP"] = $value->{"usuario.usuario_ip"};
            $array["IsRestrictionIP"] = $value->{"usuario.restriccion_ip"};
            $array["IsTokenGoogle"] = $value->{"usuario.token_google"};
            $array["IsTokenLocal"] = $value->{"usuario.token_local"};

            /* asigna valores a un array basándose en propiedades de un objeto. */
            $array["AllowDeposits"] = ($value->{"usuario_config.permite_recarga"} == "S") ? "A" : "I";
            $array["Longitud"] = $value->{"usuario.ubicacion_longitud"};
            $array["Latitud"] = $value->{"usuario.ubicacion_latitud"};
            $array["UserCountry"] = $value->{"usuario_perfil.pais"};
            $array["UserGlobal"] = $value->{"usuario_perfil.global"};


            $array["Facebook"] = $value->{"punto_venta.facebook"};

            /* Asignación de valores a un array desde un objeto, incluyendo redes sociales y estado de DNI. */
            $array["Instagram"] = $value->{"punto_venta.instagram"};
            $array["OtherSocialMediaName"] = $value->{"punto_venta.otraredessocialesname"}; //Otro Campo
            $array["OtherSocialMediaContact"] = $value->{"punto_venta.otraredessociales"};

            $array["StateDNIA"] = $value->{"usuario.verifcedula_ant"};
            $array["StateDNIP"] = $value->{"usuario.verifcedula_post"};


            /* Asigna DNI y verifica si ambos son iguales a 3 para validar. */
            $array["dniFront"] = $dniFront;
            $array["dniFrontBack"] = $dniFrontBack;

            if ($dniFront == 3 && $dniFrontBack == 3) {
                $array["is_verified"] = true;
            }


            /* Verifica condición y asigna URL de imagen a un arreglo basado en usuario. */
            if ($value->{"usuario.verifcedula_ant"} == "S") {

                $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'A' . '.png');
                $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'A' . '.png');

                $array["DNIA"] = $filename;
            }

            /* Verifica si el usuario tiene cédula y asigna una URL de imagen. */
            if ($value->{"usuario.verifcedula_post"} == "S") {

                $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'P' . '.png');
                $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'P' . '.png');

                $array["DNIP"] = $filename;
            }


            /* Se asignan valores a un arreglo basado en propiedades de un objeto. */
            $array["BetShopType"] = $value->{"punto_venta.tipo_tienda"};

            $array["Commission"] = $MyComission;


            if ($value->{"concesionario.usupadre_id"} != "") {
                $array["Concessionaire"] = $value->{"concesionario.usupadre_id"};
            }


            /* Asigna el ID de subconcesionario si no está vacío en un array. */
            if ($value->{"concesionario.usupadre2_id"} != "") {
                $array["Subconcessionaire"] = $value->{"concesionario.usupadre2_id"};
            }

        } else {
            /* muestra un bloque "else" vacío, sin instrucciones dentro. */


        }


        /* Asigna el contenido de `$array` a la variable `$usuariosFinal`. */
        $usuariosFinal = $array;

    }

    /* verifica usuarios y prepara una respuesta exitosa si existen. */
    if ($usuariosFinal) {

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response = [$usuariosFinal];

    }
}