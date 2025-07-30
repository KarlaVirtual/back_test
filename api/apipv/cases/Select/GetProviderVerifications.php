<?php

use Backend\dto\Proveedor;
use Backend\mysql\ProveedorMySqlDAO;

/**
 * Select/GetProviderVerifications
 * 
 * Obtiene la lista de proveedores de verificación disponibles en el sistema
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertType": string,     // Tipo de alerta (success, warning, error)
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "ModelErrors": array,    // Lista de errores del modelo
 *   "data": array[{         // Lista de proveedores
 *     "id": int,           // ID del proveedor
 *     "value": string      // Nombre del proveedor
 *   }]
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */

// Inicializa el array de reglas para el filtro
$rules = [];

// Agrega regla para filtrar solo proveedores de tipo VERIFICATION
array_push($rules, ['field' => 'proveedor.tipo', 'data' => 'VERIFICATION', 'op' => 'eq']);

// Crea el filtro JSON con las reglas definidas
$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

// Inicializa el objeto Proveedor y obtiene la lista filtrada de proveedores
$Proveedor = new Proveedor();
$proveedores = $Proveedor->getProveedoresCustom('proveedor.*', 'proveedor.proveedor_id', 'asc', 0, 1000, $filter, true);

// Decodifica la respuesta JSON a objeto
$proveedores = json_decode($proveedores);

// Inicializa array para almacenar resultados formateados
$final = [];

// Procesa cada proveedor y formatea los datos para la respuesta
foreach ($proveedores->data as $key => $value) {
    $array = [];

    $array['id'] = $value->{'proveedor.proveedor_id'};
    $array['value'] = $value->{'proveedor.descripcion'};

    array_push($final, $array);
}

// Prepara la respuesta final con los proveedores formateados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = $final;