<?php


use Backend\dto\SorteoInterno2;

/**
 * GetBonusBetshopDefinitions
 * 
 * Obtiene las reglas y definiciones de un sorteo específico de casa de apuestas
 *
 * @param int $_REQUEST['Id']  ID del sorteo a consultar
 * 
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success/danger)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Errores del modelo
 *   "Count": int,             // Total de registros (1 o 0)
 *   "Data": array {
 *     "Rules": string         // Reglas del sorteo
 *   }
 * }
 *
 * @throws Exception          // Errores de procesamiento
 */


    $SorteoId = $_REQUEST['Id'];

    try {

        $SorteoInterno2 = new SorteoInterno2($SorteoId);

        $response['HasError'] = false;
        $response['AlertType'] = 'success';
        $response['AlertMessage'] = '';
        $response['ModelErrors'] = [];
        $response['Count'] = !empty($SorteoInterno2->sorteoId) ? 1 : 0;

        $response['Data'] = array(
            "Rules"=>$SorteoInterno2->reglas
        );


    } catch (Exception $ex) {

    }