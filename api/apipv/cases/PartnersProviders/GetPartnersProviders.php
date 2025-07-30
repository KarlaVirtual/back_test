<?php
    use Backend\dto\SubproveedorMandantePais;

    /**
     * Obtiene la lista de proveedores asociados a un mandante y país específico
     * 
     * Este endpoint permite filtrar y obtener proveedores basados en los siguientes parámetros:
     * - ProviderId: ID del proveedor
     * - Partner: Nombre del mandante
     * - PartnerReference: Referencia del mandante
     * - CountrySelect: ID del país
     * 
     * @return array Estructura de respuesta JSON con el siguiente formato:
     * {
     *   "HasError": boolean,      // Indica si hubo error en la operación
     *   "AlertType": string,      // Tipo de alerta (success/error)
     *   "AlertMessage": string,   // Mensaje de alerta
     *   "ModelErrors": array,     // Array de errores del modelo
     *   "data": array[           // Array de proveedores encontrados
     *     {
     *       "Id": int,           // ID del proveedor-mandante-país
     *       "Description": string, // Descripción del proveedor
     *       "Order": int         // Orden de presentación
     *     }
     *   ]
     * }
     */

    $start = $_REQUEST['start'] ?: 0;
    $count = $_REQUEST['count'] ?: 100;
    $ProviderId = $_REQUEST['ProviderId'];
    $Partner = $_REQUEST['Partner'];
    $PartnerReference = $_REQUEST['PartnerReference'];
    $CountrySelect = $_REQUEST['CountrySelect2'];
    
    $rules = [];

    if(!empty($ProviderId)) array_push($rules, ['field' => 'subproveedor_mandante_pais.subproveedor_id', 'data' => $ProviderId, 'op' => 'eq']); 
    if($CountrySelect != '') array_push($rules, ['field' => 'subproveedor_mandante_pais.pais_id', 'data' => $CountrySelect, 'op' => 'eq']);
    array_push($rules, ['field' => 'subproveedor_mandante_pais.mandante', 'data' => $PartnerReference != '' ? $PartnerReference : $Partner, 'op' => 'eq']);
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $SubproveedorMandantePais = new SubproveedorMandantePais();

    $query = $SubproveedorMandantePais->getSubproveedoresMandantePaisCustom('subproveedor_mandante_pais.*, subproveedor.*', 'subproveedor_mandante_pais.orden', 'asc', $start, $count, $filter, true);

    $query = json_decode($query);

    $providers = [];

    foreach($query->data as $key => $value) {
        $data = [];
        $data['Id'] = $value->{'subproveedor_mandante_pais.provmandante_id'};
        $data['Description'] = $value->{'subproveedor.descripcion'};
        $data['Order'] = $value->{'subproveedor_mandante_pais.orden'};

        array_push($providers, $data);
    }

    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['data'] = $providers;
?>