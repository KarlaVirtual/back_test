<?php

use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\Registro;
use Backend\dto\ConfigurationEnvironment;

/**
 * Account/ExitsDocument
 *
 * Procesa el número de documento y verifica su existencia.
 *
 * @param string $docnumber Número de documento proporcionado en los parámetros.
 * @var ConfigurationEnvironment $ConfigurationEnvironment Objeto para manejar la configuración del entorno.
 * @var Registro $Registro Objeto para manejar las operaciones relacionadas con el registro.
 * @var array $data Arreglo que contiene información sobre el usuario relacionado con el documento.
 * @var array $response Arreglo que contiene la respuesta del proceso, ya sea con errores o éxito.
 *
 * @return no
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 *
 */


/* Validación y limpieza de un número de documento, lanzando excepción si está vacío. */
$docnumber = $params->docnumber;
$ConfigurationEnvironment = new ConfigurationEnvironment();
$docnumber = $ConfigurationEnvironment->DepurarCaracteres($docnumber);
$docnumber = preg_replace('/[^(\x20-\x7F)]*/', '', $docnumber);
if ($docnumber == '') {
    throw new Exception("Documento Vacio", 6969);
}


/*Lógica para validar si la opción de apuestas anónimas con documento de identidad está activa o no*/
try {
    $clasificador = new Clasificador(null, 'DOCUAPUANONIMA' );
    $mandanteDetalle = new MandanteDetalle(null, $_SESSION["mandante"], $clasificador->clasificadorId, $_SESSION["pais_id"], 'A' );

    if($mandanteDetalle->valor == 'A'){
        /* verifica la existencia de una cédula y retorna datos del usuario. */
        $Registro = new Registro();
        $Registro->setMandante($_SESSION['mandante']);
        $Registro->setCedula($docnumber);
        $Registro->existeCedula();
        if ($Registro->existeCedula()) {
            $data = [];
            $data["id"] = $Registro->existeCedula()->{'usuarioId'};
            $data["name"] = $Registro->existeCedula()->{'nombre'};
            $data["value"] = $Registro->existeCedula()->{'cedula'};
            //array_push($array["Countries"], $array2);

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];
            $response["data"] = $data;
            return;
        }else {
            /* maneja respuestas de error, asignando valores específicos al arreglo $response. */

            $response["HasError"] = true;
            $response["AlertType"] = "f";
            $response["AlertMessage"] = "f";
            $response["CodeError"] = 100010;
            //$response["data"] = $data;
        }
    }

} catch (Exception $e) {

}


/*Lógica para validar si la opción de apuestas anónimas con celular está activa o no*/
try {
    $clasificador = new Clasificador(null, 'CELUAPUANONIMA' );
    $mandanteDetalle = new MandanteDetalle(null, $_SESSION["mandante"], $clasificador->clasificadorId, $_SESSION["pais_id"], 'A');

    if($mandanteDetalle->valor == 'A') {
        /* verifica la existencia de un número de télefono y retorna datos del usuario. */
        $Registro = new Registro();
        $Registro->setMandante($_SESSION['mandante']);
        $Registro->setCelular($docnumber);
        $Registro->existeCelular();
        if ($Registro->existeCelular()) {
            $response = null;
            $data = [];
            $data["id"] = $Registro->existeCelular()->{'usuarioId'};
            $data["name"] = $Registro->existeCelular()->{'nombre'};
            $data["value"] = $Registro->existeCelular()->{'celular'};
            //array_push($array["Countries"], $array2);

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];
            $response["data"] = $data;
            return;
        } else {
            /* maneja respuestas de error, asignando valores específicos al arreglo $response. */

            $response["HasError"] = true;
            $response["AlertType"] = "f";
            $response["AlertMessage"] = "f";
            $response["CodeError"] = 100010;
            //$response["data"] = $data;
        }
    }
} catch (Exception $e) {

}