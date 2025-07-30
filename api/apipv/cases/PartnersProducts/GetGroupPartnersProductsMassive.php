<?php

use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;

$params = file_get_contents('php://input');
$params = json_decode($params);


$FilterCountry = "";
$ClientIdCsv = $params->CSV;
$CountrySelect = $params->CountrySelect;
$Partner = $params->Partner;
$type = $params->Type;
$Description = $params->Description;

$ClientIdCsv = explode("base64,", $ClientIdCsv);
$ClientIdCsv = $ClientIdCsv[1];
$ClientIdCsv = base64_decode($ClientIdCsv);
$ClientIdCsv = str_replace(";", ",", $ClientIdCsv);
$ClientIdCsv = trim($ClientIdCsv, "\xEF\xBB\xBF");
$ClientIdCsv = explode("\n", $ClientIdCsv);
if (empty(end($ClientIdCsv))) {
    array_pop($ClientIdCsv);
}


$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];

array_shift($ClientIdCsv);


$MaxRows = (count($ClientIdCsv));
$SkeepRows = 0;

$id = implode(',', $ClientIdCsv);


$ProductoMandante = new ProductoMandante();

$rules = [];

if ($id != "") {
    array_push($rules, array("field" => "producto_mandante.producto_id", "data" => "$id", "op" => "in"));
}

if ($CountrySelect != "") {
    array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
}

if ($FilterCountry != "" && $FilterCountry != null) {
    $FilterCountry = ($FilterCountry == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "$FilterCountry", "op" => "eq"));
}

if ($Partner != "") {
    array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Partner", "op" => "eq"));
}

// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    //array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

$orden = "producto_mandante.prodmandante_id";
$ordenTipo = "asc";

if ($_REQUEST["sort[Order]"] != "") {
    $orden = "producto_mandante.orden";
    $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

}

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.*,proveedor.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

$productos = json_decode($productos);

$productosString = '##';

$final = [];

$children_final = [];
$children_final2 = [];


foreach ($productos->data as $key => $value) {

    $productosString = $productosString . "," . $value->{"producto.producto_id"};

}


//-------------------------------------------------PRODUCTOS EXCLUIDOS --------------------------------------------

$Producto = new Producto();

$rules = [];

if ($id != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$id", "op" => "in"));
}

if ($CountrySelect != "") {
    array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
}
array_push($rules, array("field" => "subproveedor_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.habilitacion","data"=>"A","op"=>"eq"));
array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);


$productos = $Producto->getProductosCustomMandante(" producto.*,proveedor.*,subproveedor.descripcion ", "producto.descripcion", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true,$Partner,$CountrySelect);

$productos = json_decode($productos);

$final = [];

foreach ($productos->data as $key => $value) {

    $array = [];

    $children = [];
    $children["id"] = $value->{"producto.producto_id"};
    $children["value"] = $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";

    array_push($children_final, $children);

}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $productos->count[0]->{".count"});
$response["Data"]["ExcludedProductsList"] = $children_final;
$response["Data"]["IncludedProductsList"] = str_replace("##","",str_replace("##,","",$productosString));

$response["pos"] = $SkeepRows;
$response["total_count"] = $productos->count[0]->{".count"};
$response["data"] = $final;
