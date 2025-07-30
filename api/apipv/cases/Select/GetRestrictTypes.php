<?php

    use Backend\dto\Clasificador;

/**
 * Select/GetRestrictTypes
 * 
 * Obtiene la lista de tipos de restricciones disponibles en el sistema
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertReferenceId": string,  // Tipo de alerta (success, warning, error)
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "ModelErrors": array,    // Lista de errores del modelo
 *   "Data": array[{         // Lista de tipos de restricciones
 *     "id": int,           // ID del tipo de restricción
 *     "value": string      // Descripción del tipo de restricción
 *   }]
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */

    // Define los tipos de clasificadores a consultar
    $classifiers = "'TYPEVIRTUALSOFT','MINCETUR_BLACK_LIST'";

    // Configura las reglas de filtrado para la consulta
    $rules = [];
    
    array_push($rules, ['field' => 'clasificador.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'clasificador.tipo','data' => 'TP', 'op' => 'eq']);
    array_push($rules, ['field' => 'clasificador.abreviado', 'data' =>  $classifiers, 'op' =>'in']);

    // Crea el filtro JSON y ejecuta la consulta de clasificadores
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $Clasificador = new Clasificador();
    $query = $Clasificador->getClasificadoresCustom('clasificador.clasificador_id, clasificador.descripcion', 'clasificador.clasificador_id', 'asc', 0, count(explode(',', $classifiers)), $filter, true);
    $query = json_decode($query, true);

    // Procesa los resultados y formatea la data
    $RestrictTypes = [];
    
    foreach($query['data'] as $key => $value) {
        $data = [];
        $data['id'] = $value['clasificador.clasificador_id'];
        $data['value'] = $value['clasificador.descripcion'];

        array_push($RestrictTypes, $data);
    }

    // Prepara la respuesta con los tipos de restricciones
    $response = [];
    $response['HasError'] = false;
    $response['AlertReferenceId'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = $RestrictTypes;
?>