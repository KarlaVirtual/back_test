<?php

use Backend\dto\BonoInterno;

/**
 * GetBonusTyC
 * 
 * Obtiene los términos y condiciones de un bono específico
 * 
 * @param int $Id ID del bono a consultar (enviado por REQUEST)
 * 
 * @return array {
 *   "HasError": boolean,      // Indica si hubo error
 *   "AlertType": string,      // Tipo de alerta (success/danger)
 *   "AlertMessage": string,   // Mensaje descriptivo
 *   "ModelErrors": array,     // Errores del modelo
 *   "Count": int,             // Cantidad de registros encontrados (1 o 0)
 *   "Data": array {
 *     "Rules": string         // Términos y condiciones del bono
 *   }
 * }
 * 
 * @throws Exception           // Errores durante el procesamiento
 */


$BonoId = $_REQUEST['Id'];

try {

    $BonoInterno = new BonoInterno($BonoId);


    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Count'] = !empty($BonoInterno->bonoId) ? 1 : 0;

    $response['Data'] = array(
        "Rules"=>$BonoInterno->reglas
    );

} catch (Exception $ex) {

}



?>