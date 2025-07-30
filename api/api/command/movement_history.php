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

try {
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $id = $UsuarioMandante->usuarioMandante;

    $Usuario = new Usuario();


    $params = file_get_contents('php://input'); // Obtiene el JSON enviado
    $params = json_decode($params); // Decodifica el JSON
    $params = $params->params;


// Accede directamente a los valores
    $IsDetails = $params->IsDetails;
    $CurrencyId = $params->CurrencyId;
    $IsTest = $params->IsTest;
    $ProductId = $params->ProductId;
    $ProviderId = $params->ProviderId;
    $Region = $params->Region;

    $MaxRows = $params->count;
    $SkeepRows = $params->start;
    $ToDateLocal = $params->to_date;
    $FromDateLocal = $params->from_date;

    $ToDateLocal = date('Y-m-d H:i:s', $ToDateLocal);
    $FromDateLocal = date('Y-m-d H:i:s', $FromDateLocal);


    if ($_REQUEST["dateFrom"] != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

    }
    if ($_REQUEST["dateTo"] != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));

    }

    $UserId = $_REQUEST['UserId'];

    $CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';

    $ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
    $ProductId = $_REQUEST["ProductId"];
    $IsDetails = $_REQUEST["IsDetails"];
    $Type = $params->Type;

    switch ($Type){
        case "1":
            $Type = 10;
            break;
        case "2":
            $Type = 40;
            break;
        case "3":
            $Type = 20;
            break;
        case "4":
            $Type = 30;
            break;
        case "5":
            $Type = 50;
            break;
        case "6":
            $Type = 15;
            break;
            default :
            $Type = '';
            break;
    }




    $FromId = $_REQUEST["FromId"];


    $Movement = $params->type_of_movement; // tipo de movimiento
    $ExternalId = $_REQUEST["ExternalId"];


    $seguir = true;

    if ($SkeepRows == "") {
        $seguir = false;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $seguir = false;
    }


    $rules = [];

    if ($ProductId != "") {

        if ($_SESSION['Global'] == "S") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
        } else {
            array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$ProductId", "op" => "eq"));
        }
    }

    if ($Region != "") {
        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
    }
    $withUser=false;

    if ($id != "") {
        array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => "$id", "op" => "eq"));
        $withUser=true;

    }

    if ($UserId != "") {
        array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => "$UserId", "op" => "eq"));
        $withUser=true;

    }

    if ($FromId != "") {
        $UsuarioPerfil = new UsuarioPerfil($FromId);
        if($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA'){

            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => "$FromId", "op" => "eq"));

        }else{
            array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => "$FromId", "op" => "eq"));

        }
        $withUser=true;

    }

    if($withUser){

        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));


        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
        }
    }else{

        if ($FromDateLocal != "") {
            //array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

            array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));

        }

        if ($ToDateLocal != "") {
            //array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
        }
    }



    if ($Type != "" && is_numeric($Type)) {
        array_push($rules, array("field" => "usuario_historial.tipo", "data" => $Type, "op" => "eq"));
    }


    if ($Movement =='1') {

        array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));

    }


    if ($Movement =='2') {

        array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));

    }

    if ($Movement =='3') {

        array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "C", "op" => "eq"));

    }

    if($ExternalId != ""){
        array_push($rules, array("field" => "usuario_historial.externo_id", "data" => $ExternalId, "op" => "eq"));

    }

    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

// Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    if ($_SESSION['win_perfil2'] == "PUNTOVENTA" || $_SESSION['win_perfil2'] == "CAJERO" ) {

        array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
    }

    if($_SESSION['win_perfil2'] == "CONCESIONARIO" ){
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }

    if($_SESSION['win_perfil2'] == "CONCESIONARIO2" ){
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }
    if($_SESSION['win_perfil2'] == "CONCESIONARIO3" ){
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

    }
// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $select = "usuario_historial.*,usuario.nombre ";

    $UsuarioHistorial = new UsuarioHistorial();
    $data = $UsuarioHistorial->getUsuarioHistorialsCustom($select, "usuario_historial.usuhistorial_id", "desc", $SkeepRows, $MaxRows, $json, true,!$withUser,$FromDateLocal,$ToDateLocal);
    $data = json_decode($data);

//hasta aca funciona bien

    $final = [];

    $papuestas = 0;
    $ppremios = 0;
    $pcont = 0;

    foreach ($data->data as $key => $value) {


        $array["Id"] = $value->{"usuario_historial.usuhistorial_id"};
        $array["UserId"] = $value->{"usuario_historial.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};
        $array["Movement"] = ($value->{"usuario_historial.movimiento"} === 'E') ? 0 : $value->{"usuario_historial.movimiento"};

        if ($array["Movement"] === 'S') {
            $array["Movement"] = 1;
        }

        if ($array["Movement"] === 'C') {
            $array["Movement"] = 0;
        }


        $array["Type"] = $value->{"usuario_historial.tipo"};

        switch ($value->{"usuario_historial.tipo"}) {
            case "10":
                $array["Type"] = 'Recarga';

                break;
            case "15":
                $array["Type"] = 'Ajuste de saldo';

                break;
            case "20":
                $array["Type"] = 'Apuestas Deportivas';

                break;
            case "30":
                $array["Type"] = 'Apuestas Casino';

                break;
            case "40":
                $array["Type"] = 'Nota de retiro Creada';

                break;
            case "50":
                $array["Type"] = 'Bono Redimido';

                break;
            case "60":
                $array["Type"] = 'Aumento de Cupo';

                break;
        }

        if(strtolower($_SESSION["idioma"]) == "en"){

            switch ($value->{"usuario_historial.tipo"}) {
                case 10:
                    $array["Type"] = 'Deposit';

                    break;
                case 15:
                    $array["Type"] = 'Balance adjustment';

                    break;
                case 20:
                    $array["Type"] = 'Sports bets';

                    break;
                case 30:
                    $array["Type"] = 'Casino Gambling';

                    break;
                case 40:
                    $array["Type"] = 'Withdrawal note Created';

                    break;
                case 50:
                    $array["Type"] = 'Bonus Redeemed';

                    break;

            }
        }



        $array["ExternalId"] = $value->{"usuario_historial.externo_id"};
        $array["CreatedLocalDate"] = $value->{"usuario_historial.fecha_crea"};
        $array["BalanceDeposit"] = number_format($value->{"usuario_historial.creditos_base"}, 2, ',', '.');
        $array["BalanceWithdrawal"] = number_format($value->{"usuario_historial.creditos"}, 2, ',', '.');
        $array["Amount"] = $value->{"usuario_historial.valor"};
        $array["Latitude"] = '';
        $array["Longitude"] = '';

        $customS = $value->{"usuario_historial.customs"};
        if($customS != ''){
            $customS=json_decode($customS);
            if(is_object($customS)){
                $array["Latitude"] = $customS->lat;
                $array["Longitude"] = $customS->lng;

            }

        }


        array_push($final, $array);


    }
    /*if (!$IsDetails) {
        if ($pcont > 0) {
            $array["Game"] = $prod->{"producto.descripcion"};
            $array["ProviderName"] = $prod->{"proveedor.descripcion"};
            $array["Bets"] = $pcont;
            $array["Stakes"] = $papuestas;
            $array["Winnings"] = $ppremios;
            $array["Profit"] = 0;
            $array["BonusCashBack"] = 0;
            $array["CurrencyId"] = $prod->{"usuario_mandante.moneda"};

            array_push($final, $array);
        }
    }*/

//continuar aca


    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result_status" => "OK",
        "Response" => array("request" => $final
        )
    );

    $response["total_count"] = $data->count[0]->{".count"};


}catch (Exception $e){
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "message" => "Error al procesar la solicitud: " . $e->getMessage(),
    );
}
