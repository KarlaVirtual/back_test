<?php

use Backend\dto\Mandante;
use Backend\dto\ModeloFiscal;

/**
 * Obtiene la configuración fiscal de un socio para un año y país específicos.
 *
 * @param string $Partner ID del socio.
 * @param string|null $Country ID del país.
 * @param string|null $Year Año fiscal.
 * @param int $start Índice inicial para la paginación (por defecto 0).
 * @param int $count Cantidad de registros a obtener (por defecto 10000).
 * @return array $response Respuesta JSON con los siguientes valores:
 *                         - bool $response['HasError'] Indica si hubo un error (true/false).
 *                         - string $response['AlertType'] Tipo de alerta (success).
 *                         - string $response['AlertMessage'] Mensaje de alerta.
 *                         - array $response['data'] Lista de configuraciones fiscales por mes.
 */

/*Obtiene parámetros de solicitud y establece valores predeterminados para `start` y `count`.*/
$Partner = $_REQUEST['Partner'];
$Country = $_REQUEST['Country'];
$Year = $_REQUEST['Year'];
$start = $_REQUEST['start'] ?: 0;
$count = $_REQUEST['count'] ?: 10000;

if ($Partner !== '') {
    /*Obtiene y filtra datos fiscales según parámetros de solicitud y los decodifica.*/
    $Mandante = new Mandante(strval($Partner));

    $rules = [];

    if (!empty($Country)) array_push($rules, ['field' => 'modelo_fiscal.pais_id', 'data' => $Country, 'op' => 'eq']);
    if (!empty($Year)) array_push($rules, ['field' => 'modelo_fiscal.anio', 'data' => $Year, 'op' => 'eq']);
    array_push($rules, ['field' => 'modelo_fiscal.mandante', 'data' => $Mandante->mandante, 'op' => 'eq']);
    array_push($rules, ['field' => 'modelo_fiscal.estado', 'data' => 'A', 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $ModeloFiscal = new ModeloFiscal();

    $model = $ModeloFiscal->getModeloFiscalCustom('clasificador.*, modelo_fiscal.*', 'modelo_fiscal.modelofiscal_id', 'asc', $start, $count, $filters, true);

    $model = json_decode($model);

    $all_mounths = [
        ['Mounth' => '01'],
        ['Mounth' => '02'],
        ['Mounth' => '03'],
        ['Mounth' => '04'],
        ['Mounth' => '05'],
        ['Mounth' => '06'],
        ['Mounth' => '07'],
        ['Mounth' => '08'],
        ['Mounth' => '09'],
        ['Mounth' => '10'],
        ['Mounth' => '11'],
        ['Mounth' => '12'],
    ];

    $all_models = [];

    header('Content-Type: text/HTML');

    foreach ($all_mounths as $key => $value) {

        /*Inicializa un modelo fiscal con valores predeterminados para cada mes del año.*/
        $fiscal_model = [];
        $fiscal_model['Mounth'] = $value['Mounth'];
        $fiscal_model['Year'] = $Year;
        $fiscal_model['PercentDepositValue'] = 0;
        $fiscal_model['PercentRetirementValue'] = 0;
        $fiscal_model['PercentValueSportsBets'] = 0;
        $fiscal_model['PercentValueNonSportBets'] = 0;
        $fiscal_model['PercentValueSportsAwards'] = 0;
        $fiscal_model['PercentValueNonSportsAwards'] = 0;
        $fiscal_model['PercentValueSportsBonds'] = 0;
        $fiscal_model['PercentValueNonSportsBounds'] = 0;
        $fiscal_model['PercentValueTickets'] = 0;

        $data = array_filter($model->data, function ($item) use ($value) {
            if ($item->{'modelo_fiscal.mes'} == $value['Mounth'] and $item->{'modelo_fiscal.anio'}) return $item;
        });

        if (oldCount($data) > 0) {
            foreach ($data as $key => $model_value) {
                /*Asigna valores fiscales según el clasificador abreviado del modelo fiscal.*/
                switch ($model_value->{'clasificador.abreviado'}) {
                    case 'PORCENVADEPO':
                        $fiscal_model['PercentDepositValue'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    case 'PORCENVARETR':
                        $fiscal_model['PercentRetirementValue'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    case 'PORCENVAAPUESDEPOR':
                        $fiscal_model['PercentValueSportsBets'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    case 'PORCENVAAPUESNODEPOR':
                        $fiscal_model['PercentValueNonSportBets'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    case 'PORCENVAPREMDEPOR':
                        $fiscal_model['PercentValueSportsAwards'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    case 'PORCENVAPREMNODEPOR':
                        $fiscal_model['PercentValueNonSportsAwards'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    case 'PORCENVABONDEPOR':
                        $fiscal_model['PercentValueSportsBonds'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    case 'PORCENVABONNODEPOR':
                        $fiscal_model['PercentValueNonSportsBounds'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    case 'PORCENVATICKET':
                        $fiscal_model['PercentValueTickets'] = intval($model_value->{'modelo_fiscal.valor'});
                        break;
                    default:
                        break;
                }

            }
        }

        array_push($all_models, $fiscal_model);
    }
}

/*Generación formato de respuesta*/
$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];

$response['data'] = $all_models ?: [];
?>