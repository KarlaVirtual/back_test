<?php

    use Backend\dto\ModeloFiscal2;
    use Backend\dto\Submenu;
    use Backend\dto\PerfilSubmenu;

/**
 * Control Fiscal - Obtiene datos del modelo fiscal según parámetros específicos
 * 
 * Este archivo maneja la obtención y procesamiento de datos fiscales basados en parámetros
 * como país, fechas, tipo de reporte y socio. Realiza validaciones y filtrado de datos
 * para generar una respuesta estructurada con información fiscal.
 *
 * Estructura de la respuesta:
 * {
 *   "HasError": boolean,      // Indica si hubo errores en el proceso
 *   "AlertType": string,      // Tipo de alerta (success, error, warning)
 *   "AlertMessage": string,   // Mensaje descriptivo del resultado
 *   "ModelErrors": array,     // Lista de errores del modelo si existen
 *   "Data": {
 *     "Columns": array       // Array con las columnas del modelo fiscal
 *   }
 * }
 *
 * @subpackage Financial
 * @version 1.0
 */

    // Inicialización del submenú para balance histórico
    $Submenu = new Submenu("", "balanceHistory2",'3');

    /**
     * Manejo de permisos del usuario
     * Intenta obtener el perfil del submenú del usuario actual
     * Si falla, crea un perfil personalizado para el usuario
     */
    try {
        $PerfilSubmenu = new PerfilSubmenu($_SESSION["win_perfil"], $Submenu->getSubmenuId());

    } catch (Exception $e) {
        $PerfilSubmenu = new PerfilSubmenu('CUSTOM', $Submenu->getSubmenuId(), $_SESSION["usuario"]);
    }

    // Obtención de parámetros de la solicitud
    $Report = $_REQUEST['Report'];
    $Country = $_REQUEST['Country'];
    $DateFrom = $_REQUEST['DateFrom'];
    $DateTo = $_REQUEST['DateTo'];
    $Partner = $_REQUEST['Partner'];
    $Type = $_REQUEST['Type'];

    /**
     * Procesamiento de fechas
     * Convierte y valida las fechas de inicio y fin
     * Si no se especifica fecha de inicio, usa la fecha actual
     */
    $FromDateLocal = date('Y-m-d', strtotime(str_replace(' - ', ' ', $DateFrom)));

    if($FromDateLocal === date('Y-m-d', strtotime(''))) $FromDateLocal = date('Y-m-d');

    $date_parts_start = explode('-', $FromDateLocal);
    $year = $date_parts_start[0];
    $mounth_start = $date_parts_start[1];

    // Procesamiento de fecha final si existe
    if(!empty($DateTo)) {
        $ToDateLocal = date('Y-m-d', strtotime(str_replace(' - ', ' ', $DateTo)));

        if($ToDateLocal === date('Y-m-d', strtotime(''))) $ToDateLocal = date('Y-m-d');

        if($FromDateLocal > $ToDateLocal) $ToDateLocal = $FromDateLocal;

        $date_parts_end = explode('-', $ToDateLocal);
        $mounth_end = $date_parts_end[1];
    }

    /**
     * Construcción de reglas de filtrado
     * Define los criterios para obtener los datos fiscales
     */
    $rules = [];

    array_push($rules, ['field' => 'modelo_fiscal2.mandante', 'data' => $Partner == '' ? $_SESSION['mandante'] : $Partner, 'op' => 'eq']);
    array_push($rules, ['field' => 'anio', 'data' => $year, 'op' => 'eq']);
    array_push($rules, ['field' => 'reporte', 'data' => $Report, 'op' => 'eq']);
    array_push($rules, ['field' => 'pais_id', 'data' => $Country, 'op' => 'eq']);
    array_push($rules, ['field' => 'tipo', 'data' => $Type, 'op' => 'eq']);

    // Agrega reglas de filtrado por mes según si hay fecha final o no
    if (!empty($ToDateLocal)) {
        array_push($rules, ['field' => 'mes', 'data' => $mounth_start, 'op' => 'ge']);
        array_push($rules, ['field' => 'mes', 'data' => $mounth_end, 'op' => 'le']);
    } else {
        array_push($rules, ['field' => 'mes', 'data' => $mounth_start, 'op' => 'eq']);
    }

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    /**
     * Obtención y procesamiento de datos fiscales
     * Recupera los datos del modelo fiscal y los procesa según el formato requerido
     */
    $ModeloFiscal2 = new ModeloFiscal2();

    $modeloFisca2 = $ModeloFiscal2->getModeloFiscal2Custom('modelo_fiscal2.*', 'modelofiscal_id', 'asc', 0, !empty($DateTo) ? 12 : 1, $filter, true);

    $modeloFisca2 = json_decode($modeloFisca2);

    $res = [];

    // Procesamiento de resultados según la cantidad de registros
    if($modeloFisca2->count[0]->{'.count'} <= 1) {
        $res = json_decode($modeloFisca2->data[0]->{'modelo_fiscal2.columnas'});
    } else {
        $res['Columns'] = [];
        foreach($modeloFisca2->data as $key => $value) {
            $data = json_decode($value->{'modelo_fiscal2.columnas'});

            foreach($data->{'Columns'} as $key => $col) {
                $col_values = array_values((array)$col);
                $col_values = array_diff($col_values, ['']);

                if(oldCount($col_values) > 0) array_push($res['Columns'], $col);
            }
        }
    }

    /**
     * Preparación de la respuesta final
     * Estructura la respuesta con los datos procesados
     */
    $response = [];
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = $res;
?>
