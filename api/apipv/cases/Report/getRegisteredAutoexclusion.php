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
 * Report/getRegisteredAutoexclusion
 * 
 * Obtiene el listado de autoexclusiones registradas por los usuarios
 *
 * @param object $params {
 *   "PlayerId": int,               // ID del jugador
 *   "Id": int,                     // ID de la autoexclusión
 *   "UserName": string,            // Nombre de usuario
 *   "UserCreation": string,        // Usuario que creó el registro
 *   "UserModified": string,        // Usuario que modificó el registro
 *   "IsActivate": boolean,         // Estado de activación
 *   "Category": string,            // Categoría
 *   "Game": string,                // Juego
 *   "CountrySelect": string,       // País seleccionado
 *   "dateTo": string,              // Fecha final (Y-m-d)
 *   "dateFrom": string,            // Fecha inicial (Y-m-d)
 *   "MaxRows": int,                // Cantidad máxima de registros
 *   "OrderedItem": string,         // Campo de ordenamiento
 *   "SkeepRows": int               // Registros a omitir (paginación)
 * }
 *
 * @return array {
 *   "HasError": boolean,           // Indica si hubo error
 *   "AlertType": string,           // Tipo de alerta (success, error)
 *   "AlertMessage": string,        // Mensaje descriptivo
 *   "ModelErrors": array,          // Errores del modelo
 *   "Data": array {
 *     "Objects": array[],          // Lista de autoexclusiones
 *     "Count": int                 // Total de registros
 *   }
 * }
 */
// Obtiene el parámetro jsonp de la URL
$jsonpname = $_GET["jsonp"];

// Define el tipo de clasificación para autoexclusión de casino
$tipo = "EXCCASINOCATEGORY";
$Tipo = new Clasificador("", $tipo);

// Obtiene los parámetros de filtrado de la URL
$PlayerId = $_REQUEST["PlayerId"];
$Id = $_REQUEST["Id"];
$UserName= $_REQUEST["UserName"];
$UserCreation = $_REQUEST["UserCreation"];
$UserModified = $_REQUEST["UserModified"];

// Obtiene los parámetros de paginación del objeto params
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

// Obtiene los parámetros de paginación de la URL
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

// Obtiene los parámetros de filtrado adicionales
$IsActivate =$_REQUEST["IsActivate"];
$Category =$_REQUEST["Category"];
$Game =$_REQUEST["Game"];

// Obtiene el ID del producto según el juego seleccionado
$ProductoMandante = new ProductoMandante("","",$Game);
$ProductoId = $ProductoMandante->productoId;

$CountrySelect =$_REQUEST["CountrySelect"];
$rules = [];

// Procesa la fecha final del rango
$ToDateLocal = $params->dateTo;
if($_REQUEST["dateTo"] != ""){
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +1 day' . $timezone . ' hour '));
}else{
    $_REQUEST["dateTo"] = "";
}

// Procesa la fecha inicial del rango
$FromDateLocal = $params->dateFrom;
if($_REQUEST["dateFrom"] != ""){
    $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}else{
    $_REQUEST["dateFrom"] = "";
}

// Establece valores por defecto para las fechas si no se proporcionaron
if ($FromDateLocal == "") {
    $FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
}else{
    array_push($rules, array("field" => "usuario_configuracion.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
}
if ($ToDateLocal == "") {
    $ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
}else{
    array_push($rules, array("field" => "usuario_configuracion.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
}

// Establece valores por defecto para la paginación
if ($SkeepRows == "") {
    $SkeepRows = 0;
}
if ($OrderedItem == "") {
    $OrderedItem = 1;
}
if ($MaxRows == "") {
    $MaxRows = 100;
}

// Agrega regla para filtrar por tipos de exclusión
array_push($rules, array("field" => "clasificador.abreviado", "data" => "'EXCTIMEOUT','EXCTIME','EXCTOTAL','EXCPRODUCT','EXCCASINOCATEGORY','EXCCASINOSUBCATEGORY','EXCCASINOGAME'", "op" => "in"));

// Agrega reglas de filtrado según los parámetros proporcionados
if ($Id != "") {
    array_push($rules, array("field" => "usuario_configuracion.usuconfig_id", "data" => $Id, "op" => "eq"));
}

if ($PlayerId != "") {
    array_push($rules, array("field" => "usuario_configuracion.usuario_id", "data" => $PlayerId, "op" => "eq"));
}

if ($UserName != "") {
    array_push($rules, array("field" => "usuario.login", "data" => $UserName, "op" => "eq")); 
}

// Agrega reglas de filtrado para estado y categorías
if($IsActivate != "" && $IsActivate != "null"){
    array_push($rules, array("field" => "usuario_configuracion.estado", "data" => $IsActivate, "op" => "eq"));
}

if ($Category != "") {
    array_push($rules, array("field" => "usuario_configuracion.producto_id", "data" => $Category, "op" => "eq"));
}

// Agrega reglas de filtrado para usuarios y juegos
if ($UserCreation != "") {
    array_push($rules, array("field" => "usuario_configuracion.usucrea_id", "data" => $UserCreation, "op" => "eq"));
}
if ($Game != "") {
    array_push($rules, array("field" => "usuario_configuracion.producto_id", "data" => $ProductoId, "op" => "eq"));
}
if ($UserModified != "") {
    array_push($rules, array("field" => "usuario_configuracion.usumodif_id", "data" => $UserModified, "op" => "eq"));
}

// Agrega reglas de filtrado por país y mandante
if ($CountrySelect != '') {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
}
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}else {
    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }
}

// Agrega regla para filtrar por tipo de clasificador
array_push($rules, array("field" => "clasificador.tipo", "data" => "UC", "op" => "eq"));

// Construye el JSON de filtrado y obtiene los datos
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$UsuarioConfiguracion = new UsuarioConfiguracion();
$configuraciones = $UsuarioConfiguracion->getUsuarioConfiguracionesCustom(" usuario.login,usuario_configuracion.*,clasificador.*, categoria.*, producto.descripcion", "usuario_configuracion.usuconfig_id", "desc", $SkeepRows, $MaxRows, $json, true);
$data = json_decode($configuraciones);

// Inicializa variables para el procesamiento
$final = [];
$papuestas = 0;
$ppremios = 0;
$pcont = 0;


// Procesa cada registro de autoexclusión y construye un array con los datos formateados
foreach ($data->data as $key => $value) {
    $array = array();
    $array["Id"] = ($value->{"usuario_configuracion.usuconfig_id"});
    $array["PlayerId"] = ($value->{"usuario_configuracion.usuario_id"});
    $array["UserName"] = $value->{"usuario.login"};
    $array["State"] = ($value->{"usuario_configuracion.estado"});
    $array["FinalDate"] = $value->{"usuario_configuracion.valor"};
    $array["StartDate"] = $value->{"usuario_configuracion.fecha_crea"};
    $array["ModifiedDate"] = $value->{"usuario_configuracion.fecha_modif"};

    // Agrega información básica sobre la autoexclusión
    $array["Reason"] = $value->{"usuario_configuracion.nota"};
    $array["AutoAmount"] = 1;
    $array["UserCreation"] = $value->{"usuario_configuracion.usucrea_id"};
    $array["UserModified"] = $value->{"usuario_configuracion.usumodif_id"};
    $array["Type"] = $value->{"clasificador.descripcion"};

    // Procesa diferentes tipos de autoexclusión según el clasificador
    switch ($value->{"clasificador.abreviado"}){
        case "EXCCASINOCATEGORY":
            $array["IdCategory"] = $value->{"usuario_configuracion.producto_id"};
            $array["Category"] = $value->{"categoria.descripcion"};
            break;
        case "EXCCASINOSUBCATEGORY":
            $array["SubCategory"] = $value->{"usuario_configuracion.producto_id"};
            break;
        case "EXCCASINOGAME":
            $array["IdGame"] = $value->{"usuario_configuracion.producto_id"};
            $array["Game"] = $value->{"producto.descripcion"};
            break;
        case "EXCTOTAL":
            $array["Product"] = $value->{"clasificador.descripcion"};
            break;

        // Maneja la autoexclusión por producto específico
        case "EXCPRODUCT":
            $array["Product"] = $value->{"usuario_configuracion.producto_id"};

            switch ($array["Product"]){
                case "0":
                    $array["Product"] = "Apuestas Deportivas";
                    if(strtolower($_SESSION["idioma"])=="en"){
                        $array["Product"] = 'Sportbook Bets';
                    }
                    break;

                case "1":
                    $array["Product"] = "Virtuales";
                    if(strtolower($_SESSION["idioma"])=="en"){
                        $array["Product"] = 'Virtuals';
                    }
                    break;

                case "2":
                    $array["Product"] = "Casino en vivo";
                    if(strtolower($_SESSION["idioma"])=="en"){
                        $array["Product"] = 'Live Casino';
                    }
                    break;

                case "3":
                    $array["Product"] = "Casino";
                    if(strtolower($_SESSION["idioma"])=="en"){
                        $array["Product"] = 'Casino';
                    }
                    break;

                case "4":
                    $array["Product"] = "Prematch";
                    break;

                case "5":
                    $array["Product"] = "Live";
                    break;
            }
            break;
    }

    // Asigna producto por defecto si no está definido
    if (!isset($array["Product"])){
        $array["Product"] = $value->{"clasificador.descripcion"};
    }

    // Traduce los diferentes tipos de configuración según el idioma
    switch ($array["Type"]){
        case "USUDIRECCION":
            $array["Type"] = 'Direccion';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Address';
            }
            break;

        case "USUGENERO":
            $array["Type"] = 'Genero';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Gender';
            }
            break;

        // Traduce los tipos relacionados con información personal
        case "USUTELEFONO":
            $array["Type"] = 'Telefono';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Phone';
            }
            break;

        case "USUNOMBRE1":
            $array["Type"] = 'Primer Nombre';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'First Name';
            }
            break;

        // Continúa con más traducciones de información personal
        case "USUNOMBRE2":
            $array["Type"] = 'Segundo Nombre';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Second name';
            }
            break;

        case "USUAPELLIDO1":
            $array["Type"] = 'Primer Apellido';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Surname';
            }
            break;



        case "USUAPELLIDO2":
            $array["Type"] = 'Segundo Apellido';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Second surname';
            }
            break;

        case "USUCELULAR":
            $array["Type"] = 'Celular';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Mobile';
            }
            break;


        case "USUEMAIL":
            $array["Type"] = 'Email';
            break;


        case "LIMITEDEPOSITOSIMPLE":
            $array["Type"] = 'Limite Deposito Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Simple Deposit Limit';
            }
            break;

        case "LIMITEDEPOSITODIARIO":
            $array["Type"] = 'Limite Deposito Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Daily Deposit Limit';
            }
            break;

        case "LIMITEDEPOSITOSEMANA":
            $array["Type"] = 'Limite Deposito Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Weekly Deposit Limit';
            }
            break;

        case "LIMITEDEPOSITOMENSUAL":
            $array["Type"] = 'Limite Deposito Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Monthly Deposit Limit';
            }
            break;

        case "LIMITEDEPOSITOANUAL":
            $array["Type"] = 'Limite Deposito Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Annual Deposit Limit';
            }
            break;


        case "LIMAPUDEPORTIVASIMPLE":
            $array["Type"] = 'Limite Deportivas Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Simple Sports Limit';
            }
            break;

        case "LIMAPUDEPORTIVADIARIO":
            $array["Type"] = 'Limite Deportivas Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Daily Sports Limit';
            }
            break;

        case "LIMAPUDEPORTIVASEMANA":
            $array["Type"] = 'Limite Deportivas Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Weekly Sports Limit';
            }
            break;

        case "LIMAPUDEPORTIVAMENSUAL":
            $array["Type"] = 'Limite Deportivas Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Monthly Sports Limit';
            }
            break;

        case "LIMAPUDEPORTIVAANUAL":
            $array["Type"] = 'Limite Deportivas Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Annual Sports Limit';
            }
            break;


        case "LIMAPUCASINOSIMPLE":
            $array["Type"] = 'Limite Casino Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Simple Casino';
            }
            break;

        case "LIMAPUCASINODIARIO":
            $array["Type"] = 'Limite Casino Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Daily Casino';
            }
            break;

        case "LIMAPUCASINOSEMANA":
            $array["Type"] = 'Limite Casino Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Weekly Casino';
            }
            break;

        case "LIMAPUCASINOMENSUAL":
            $array["Type"] = 'Limite Casino Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Monthly Casino';
            }
            break;

        case "LIMAPUCASINOANUAL":
            $array["Type"] = 'Limite Casino Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Annual Casino';
            }
            break;


        case "LIMAPUCASINOVIVOSIMPLE":
            $array["Type"] = 'Limite Casino Vivo Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Simple Live Casino';
            }
            break;

        case "LIMAPUCASINOVIVODIARIO":
            $array["Type"] = 'Limite Casino Vivo Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Daily Live Casino';
            }

            break;

        case "LIMAPUCASINOVIVOSEMANA":
            $array["Type"] = 'Limite Casino Vivo Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Weekly Live Casino';
            }
            break;

        case "LIMAPUCASINOVIVOMENSUAL":
            $array["Type"] = 'Limite Casino Vivo Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Monthly Live Casino';
            }
            break;

        case "LIMAPUCASINOVIVOANUAL":
            $array["Type"] = 'Limite Casino Vivo Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Annual Live Casino';
            }
            break;

        case "LIMAPUVIRTUALESSIMPLE":
            $array["Type"] = 'Limite Virtuales Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Simple Virtuales';
            }
            break;

        case "LIMAPUVIRTUALESDIARIO":
            $array["Type"] = 'Limite Virtuales Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Daily Virtuales';
            }

            break;

        case "LIMAPUVIRTUALESSEMANA":
            $array["Type"] = 'Limite Virtuales Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Weekly Virtuales';
            }
            break;

        case "LIMAPUVIRTUALESMENSUAL":
            $array["Type"] = 'Limite Virtuales Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Monthly Virtuales';
            }
            break;

        case "LIMAPUVIRTUALESANUAL":
            $array["Type"] = 'Limite Virtuales Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Limit Annual Virtuales';
            }
            break;


        case "TIEMPOLIMITEAUTOEXCLUSION":
            $array["Type"] = 'Autoexclusion por tiempo';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Self-exclusion by time';
            }
            break;

        case "CAMBIOSAPROBACION":
            $array["Type"] = 'Cambios Aprobacion';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Changes Approval';
            }
            break;

        case "ESTADOUSUARIO":
            $array["Type"] = 'Estado Usuario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'State User';
            }
            break;


        case "USUDNIANTERIOR":
            $array["Type"] = 'DNI Anterior';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Document front side';
            }
            break;

        case "USUDNIPOSTERIOR":
            $array["Type"] = 'DNI Posterior';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Document back side';
            }
            break;

        case "USUVERDOM":
            $array["Type"] = 'Verificacion de Direccion';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Address Verification';
            }
            break;


        case "USUCIUDAD":
            $array["Type"] = 'Ciudad Residencia';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'City of Residence';
            }


            break;


        case "USUCODIGOPOSTAL":
            $array["Type"] = 'Codigo Postal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Postal Code';
            }
            break;

        case "USUFECHANACIM":
            $array["Type"] = 'Fecha de Nacimiento';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Date of birth';
            }
            break;

        case "USUCEDULA":
            $array["Type"] = 'Cedula (ID)';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Identification (DNI or ID)';
            }
            break;

        case "USUNACIONALIDAD":
            $array["Type"] = 'Nacionalidad';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Nationality';
            }

            break;

        case "USUTIPODOC":
            $array["Type"] = 'Tipo de Documento';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Type"] = 'Document type';
            }
            break;

        case "UINFO1":
            $array["Type"] = 'Info 1';
            break;

        case "UINFO2":
            $array["Type"] = 'Info 2';
            break;

        case "UINFO3":
            $array["Type"] = 'Info 3';
            break;
    }

    array_push($final, $array);
}

// Prepara la respuesta final con los datos procesados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};
$response["data"] = $final;

// Devuelve la respuesta en formato JSONP si se especifica un nombre de callback
if ($jsonpname != "") {
    header('content-type: application/json; charset=utf-8');
    echo $jsonpname . "(" . json_encode($response) . ")";
    exit;
}