<?php

use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\SubproveedorMandantePais;

/**
 * Este recurso permite obtener los proveedores que esten activos para asociar a una criptored
 *
 * @param object $params Objeto que contiene los parámetros de entrada.
 * @param string $Partner Identificador del mandante asociado.
 * @param string $Country Identificador del país asociado.
 */
$Partner = $params->Partner;
$Country  = $params->Country;
$keyword = $params->Filter;

/*Descripcion: Este recurso permite obtener las criptomonedas activas*/
if ($keyword != "" & $keyword != null) {
    // Agrega reglas de filtro por descripción y estado activo
    $rules = [];

    array_push($rules, array("field" => "proveedor.descripcion", "data" => $keyword, "op" => "cn"));

    array_push($rules, array("field" => "subproveedor_mandante.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "subproveedor_mandante.mandante", "data" => $Partner, "op" => "eq"));
    array_push($rules, array("field" => "subproveedor_mandante_pais.pais_id", "data" => $Country, "op" => "eq"));
    array_push($rules, array("field" => "proveedor.tipo", "data" => "'PAYAOUT','PAYMENT'", "op" => "in"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $SubProveedormandantePais = new SubproveedorMandantePais();
    $datos = $SubProveedormandantePais->getSubproveedoresMandantePaisCustom("proveedor.proveedor_id,proveedor.descripcion", "subproveedor_mandante_pais.provmandante_id", "desc", 0, 100, $jsonfiltro, true);


    $datos = json_decode($datos);


    $final = [];
    $proveedoresId = [];

    foreach ($datos->data as $key => $value) {
        if (in_array($value->{"proveedor.proveedor_id"}, $proveedoresId)) {
            continue; // Si el proveedor ya fue agregado, saltar al siguiente
        } else {
            $proveedoresId[] = $value->{"proveedor.proveedor_id"}; // Agregar el proveedor a la lista de IDs
        }
        $array = [];
        $array["id"] = $value->{"proveedor.proveedor_id"};
        $array["value"] = $value->{"proveedor.descripcion"};

        array_push($final, $array); // Agrega el array al resultado final
    }
}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Consulta exitosa";
$response["Data"] = $final;

