<?php

    use Backend\dto\Clasificador;

/**
 * Select/GetNoteTypes
 * 
 * Obtiene los tipos de notas disponibles en el sistema
 *
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertReferenceId": string,// Tipo de alerta (success, warning, error)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Lista de errores del modelo
 *   "Data": array[{           // Lista de tipos de notas
 *     "Id": int,             // ID del tipo de nota
 *     "Description": string  // Descripción del tipo de nota
 *   }]
 * }
 *
 * @throws Exception          // Errores de procesamiento
 */
    // Inicializa el objeto Clasificador para acceder a los tipos de notas
    $Clasificador = new Clasificador();

    // Configura las reglas de filtrado para obtener solo registros de tipo TNT
    $rules = [];
    array_push($rules, ['field' => 'clasificador.tipo', 'data' => 'TNT', 'op' => 'eq']);

    // Prepara el filtro JSON para la consulta
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
    
    // Obtiene los tipos de notas según el filtro y los convierte a array
    $types = $Clasificador->getClasificadoresCustom('clasificador.*', 'clasificador.clasificador_id', 'asc', 0, 1000, $filter, true);
    $types = json_decode($types, true);

    $allTypes = [];

    // Procesa cada tipo de nota y formatea los datos para la respuesta
    foreach($types['data'] as $key => $value) {
        $data = [];
        $data['Id'] = $value['clasificador.clasificador_id'];
        $data['Description'] = $value['clasificador.descripcion'];

        array_push($allTypes, $data);
    }

    // Prepara la respuesta final con los tipos de notas formateados
    $response = [];
    $response['HasError'] = false;
    $response['AlertReferenceId'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = $allTypes;
?>