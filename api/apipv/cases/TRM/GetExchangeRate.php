<?php

/**
 * Recurso para obtener las tasas de cambio
 * 
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025-02-04
 */

use Backend\dto\TasaCambio;

try {
  $MaxRows = isset($_REQUEST["Count"]) ? $_REQUEST["Count"] : 100000000;
  $SkeepRows = isset($_REQUEST["start"]) ? $_REQUEST["start"] : 0;
  $partner = $_REQUEST["Partner"];

  $rules = [];
   
  array_push($rules, array("field" => "tasa_cambio.estado", "data" => 'A', "op" => "eq"));
  
  $filtro = array("rules" => $rules, "groupOp" => "AND");
  
  $json = json_encode($filtro);
  
  $tasaCambio = new TasaCambio();
  
  $data = $tasaCambio->getTRMCustom(" tasa_cambio.* ", "tasa_cambio.id", "asc", $SkeepRows, $MaxRows, $json, true);
  
  $data = json_decode($data);

  $dataResponse = [];

  foreach ($data->data as $trm) {
    $dataResponse[] = [
      "Id" => $trm->{'tasa_cambio.id'},
      "SourceCurrency" => $trm->{'tasa_cambio.moneda_origen'},
      "DestinationCurrency" => $trm->{'tasa_cambio.moneda_destino'},
      "ExchangeRate" => $trm->{'tasa_cambio.tasa_cambio'}
    ];  
  }

  $response['HasError'] = false; 
  $response['AlertType'] = 'success';
  $response['AlertMessage'] = '';
  $response['ModelErrors'] = [];
  $response['total_count'] =  $data->count[0]->{'.count'};
  $response['data'] = $dataResponse;

} catch (Exception $e) {

  $response['HasError'] = true; 
  $response['AlertType'] = 'danger';
  $response['AlertMessage'] = $e->getMessage();
  $response['ModelErrors'] = [];
  $response['total_count'] =  0;
  $response['data'] = [];

}
