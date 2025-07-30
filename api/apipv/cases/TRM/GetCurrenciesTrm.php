<?php

/**
 * Recurso para obtener las monedas y paises
 * Normalmente es usado para listar en los componentes 'Select' de los formularios.
 * Ejemplo del formato devuelto: [{"id":1,"value":"Colombia","baseCurrency":"COP"}]
 * 
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025-02-04
 */

use Backend\mysql\TasaCambioMySqlDAO;

try {
  $tasaCambioMySqlDAO = new TasaCambioMySqlDAO();
  $monedas = $tasaCambioMySqlDAO->getAllCurrency();
  $final = [];

  foreach ($monedas as $key => $value) {
    $array = [];
    $array["id"] = $value['pm.id'];
    $array["value"] = $value['p.pais'];
    $array["baseCurrency"] = $value['pm.moneda'];

    array_push($final, $array);
  }

  $response["hasError"] = false;
  $response["AlertType"] = "success";
  $response["AlertMessage"] = "";
  $response["ModelErrors"] = [];
  $response["data"] = $final;
} catch (Exception $e) {
  $response["hasError"] = true;
  $response["AlertType"] = "danger";
  $response["AlertMessage"] = $e->getMessage();
  $response["ModelErrors"] = [];
  $response["data"] = [];
}
