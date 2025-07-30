<?php

use Backend\dto\SubproveedorMandantePais;

/**
 * Filtra y organiza proveedores por tipo y país para un socio específico.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param string $params->Partner Socio de referencia.
 * @param string $params->Country País asociado al socio.
 * 
 * 
 * @return void Modifica el array $response con los siguientes valores:
 *              - HasError: boolean, indica si ocurrió un error.
 *              - AlertType: string, tipo de alerta ('success').
 *              - AlertMessage: string, mensaje de alerta.
 *              - ModelErrors: array, lista de errores del modelo.
 *              - Data: array, lista de proveedores organizados por tipo.
 * @throws Exception Si ocurre un error durante la consulta de proveedores.
 */

/*Obtención de parámetros*/
$Partner = $params->Partner;
$Country = $params->Country;

if (!empty($Country) && $Partner !== '') {
    /*Generación de filtrado*/
    $rules = [];

    array_push($rules, ['field' => 'proveedor.tipo', 'data' => '"MENSAJERIA","EMAIL","CRM","VERIFICATION","FIRMA","CPF","BINGO"', 'op' => 'in']);
    array_push($rules, ['field' => 'subproveedor_mandante_pais.pais_id', 'data' => $Country, 'op' => 'eq']);
    array_push($rules, ['field' => 'subproveedor_mandante_pais.mandante', 'data' => $Partner, 'op' => 'eq']);

    /*Obtención data personalizada*/
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $SubProveedorMandantePais = new SubproveedorMandantePais();
    $query = $SubProveedorMandantePais->getSubproveedoresMandantePaisCustom('proveedor.proveedor_id, proveedor.descripcion, proveedor.tipo', 'subproveedor.subproveedor_id', 'ASC', 0, 100000, $filter, true);

    $query = json_decode($query, true);

    $providers = [
        'providerSMS' => [],
        'providerEmail' => [],
        'providerCRM' => [],
        'providerVerification' => [],
        'providerSignature' => [],
        'providerCPF' => []
    ];

    foreach ($query['data'] as $key => $value) {
        /*Iteración objetos de palabras*/
        $data = [];
        $data['id'] = $value['proveedor.proveedor_id'];
        $data['value'] = $value['proveedor.descripcion'];

        if ($value['proveedor.tipo'] === 'MENSAJERIA') array_push($providers['providerSMS'], $data);
        elseif ($value['proveedor.tipo'] === 'EMAIL') array_push($providers['providerEmail'], $data);
        elseif ($value['proveedor.tipo'] === 'CRM') array_push($providers['providerCRM'], $data);
        elseif ($value['proveedor.tipo'] === 'FIRMA') array_push($providers['providerSignature'], $data);
        elseif ($value['proveedor.tipo'] === 'CPF') array_push($providers['providerCPF'], $data);
        else array_push($providers['providerVerification'], $data);
    }
}


/* Crea una respuesta estructurada con estados y mensajes para una API o función. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = $providers ?: [];
?>