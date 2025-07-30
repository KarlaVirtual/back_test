<?php

use Backend\dto\ModeloFiscal2;
use Backend\mysql\ModeloFiscal2MySqlDAO;

/**
 * Procesa y actualiza la configuración fiscal en la base de datos.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param string $params->Report ID del reporte.
 * @param string $params->Country ID del país.
 * @param string $params->DateFrom Fecha de inicio del reporte.
 * @param object $params->Data Datos del reporte (incluye columnas y valores).
 * @param string $params->Type Tipo de reporte.
 * @param string $params->Partner ID del socio asociado al reporte.
 * 
 * 
 * @return array $response Respuesta JSON con los siguientes valores:
 *                         - HasError: Indica si hubo un error (true/false).
 *                         - AlertType: Tipo de alerta (success/error).
 *                         - AlertMessage: Mensaje de alerta.
 *                         - ModelErrors: Lista de errores del modelo (puede ser vacía).
 *                         - Data: Datos adicionales (puede ser vacío).
 * @throws Exception Si ocurre un error al actualizar o insertar datos fiscales.
 */
$Report = $params->Report;
$Country = $params->Country;
$DateFrom = $params->DateFrom;
$Data = $params->Data;
$Type = $params->Type;
$Partner = $params->Partner;

/*Elimina columnas vacías del array $Data->{'Columns'} si no contienen valores.*/
if (!empty($Data)) {
    foreach ($Data->{'Columns'} as $key => $value) {

        $col_values = array_values((array)$value);
        $col_values = array_diff($col_values, ['']);

        if (oldCount($col_values) === 0) unset($Data->{'Columns'}[$key]);
    }
}

/* Elimina columnas vacías del array $Data->{'Columns'} si no contienen valores.*/
$FromDateLocal = date('Y-m-d', strtotime(str_replace(' - ', ' ', $DateFrom)));

$rules = [];

if ($FromDateLocal === date('Y-m-d', strtotime(''))) $FromDateLocal = date('Y-m-d');

$date_parts = explode('-', $FromDateLocal);
$year = $date_parts[0];
$mounth = $date_parts[1];

$ModeloFiscal2MySqlDAO = new ModeloFiscal2MySqlDAO();

/*Intenta actualizar `ModeloFiscal2` si los datos han cambiado, de lo contrario, lanza una excepción.*/
try {
    $ModeloFiscal2 = new ModeloFiscal2('', $Report, $Partner, $Country, $mounth, $year, $Type);

    if ($ModeloFiscal2->getColumnas() !== json_encode($Data)) {
        $ModeloFiscal2->setColumnas(json_encode($Data));
        $ModeloFiscal2->setUsumodifId($_SESSION['usuario']);

        $ModeloFiscal2MySqlDAO->update($ModeloFiscal2);
        $ModeloFiscal2MySqlDAO->getTransaction()->commit();
    }
} catch (Exception $ex) {
    /*Crea un nuevo `ModeloFiscal2` si no existe y lo inserta en la base de datos.*/
    if ($ex->getCode() === 34) {
        $ModeloFiscal2 = new ModeloFiscal2();

        // Establece el reporte en el modelo fiscal
        $ModeloFiscal2->setReporte($Report);
        $ModeloFiscal2->setMandante($Partner);
        $ModeloFiscal2->setPaisId($Country);
        $ModeloFiscal2->setMes($mounth);
        $ModeloFiscal2->setAnio($year);
        $ModeloFiscal2->setColumnas(json_encode($Data));
        $ModeloFiscal2->setTipo($Type);
        $ModeloFiscal2->setUsucreaId($_SESSION['usuario']);

        // Inserta el objeto ModeloFiscal2 en la base de datos
        $ModeloFiscal2MySqlDAO->insert($ModeloFiscal2);
        $ModeloFiscal2MySqlDAO->getTransaction()->commit();
    }
}

// Inicializa un array de respuesta
$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = '';
?>
