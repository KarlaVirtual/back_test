<?php

    use Backend\dto\FormulariosGenericos;
    use Backend\dto\UsuarioMandante;

    /**
     * Report/GetGenericForms
     * 
     * Obtiene los formularios genéricos según los filtros especificados
     *
     * @param array $params {
     *   "FormId": int,           // ID del formulario
     *   "UserId": int,           // ID del usuario
     *   "IsFilledOut": boolean,  // Indica si está diligenciado (0=Si, 1=No)
     *   "Year": int,             // Año del formulario
     *   "Country": int,          // ID del país
     *   "Type": string,          // Tipo de formulario
     *   "MaxRow": int,           // Máximo de registros a retornar
     *   "SkeepRows": int         // Registros a omitir (paginación)
     * }
     *
     * @return array {
     *   "HasError": boolean,         // Indica si hubo error
     *   "AlertType": string,         // Tipo de alerta (success, error)
     *   "AlertMessage": string,      // Mensaje de alerta
     *   "ModelErrors": array,        // Errores del modelo
     *   "data": array {
     *     "Id": int,                 // ID del formulario
     *     "DateTimeCreation": string,// Fecha de creación
     *     "Form": object,            // Datos del formulario
     *     "Message": string,         // Mensaje visual en JSON
     *     "UserId": int,             // ID del usuario
     *     "Name": string,            // Nombre del usuario
     *     "Email": string,           // Email del usuario
     *     "Phone": string,           // Teléfono del usuario
     *     "Diligence": string,       // Estado de diligenciamiento (SI/NO)
     *     "FilledOut": string,       // Estado de llenado (SI/NO)
     *     "Year": int,               // Año del formulario
     *     "Partner": string          // Mandante/Partner
     *   }[],
     *   "pos": int,                  // Posición actual
     *   "total_count": int           // Total de registros
     * }
     */

    // Inicializa el objeto UsuarioMandante con la sesión actual
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    // Obtiene los parámetros de la solicitud HTTP
    $FormId = $_REQUEST['FormId'];
    $UserId = $_REQUEST['UserId'];
    $IsFilledOut = $_REQUEST['IsFilledOut'];
    $Year = $_REQUEST['Year'];
    $Country = $_REQUEST['Country'] ?: $_REQUEST['Country2'];
    $Type = $_REQUEST['Type'];
    $MaxRows = $_REQUEST['MaxRow'] ?: 100;
    $SkeepRows = $_REQUEST['SkeepRows'] ?: 0;
    $Partner = $_SESSION['mandante'] == -1 ? '' : $_SESSION['mandante'];

    // Convierte el valor de IsFilledOut a formato S/N
    if($IsFilledOut != '') $IsFilledOut = $IsFilledOut == 0 ? 'S' : 'N';

    // Construye las reglas de filtrado basadas en los parámetros recibidos
    $rules = [];

    if(!empty($FormId)) array_push($rules, ['field' => 'formularios_genericos.formgenerico_id', 'data' => $FormId, 'op' => 'eq']);
    if(!empty($UserId)) array_push($rules, ['field' => 'formularios_genericos.usuario_id', 'data' => $UserId, 'op' => 'eq']);
    if(!empty($IsFilledOut)) array_push($rules, ['field' => 'formularios_genericos.diligenciado', 'data' => $IsFilledOut, 'op' => 'eq']);
    if(!empty($Year)) array_push($rules, ['field' => 'formularios_genericos.anio', 'data' => $Year, 'op' => 'eq']);
    if(!empty($Country)) array_push($rules, ['field' => 'formularios_genericos.pais_id', 'data' => $Country, 'op' => 'eq']);
    if(!empty($Type)) array_push($rules, ['field' => 'formularios_genericos.tipo', 'data' => $Type, 'op' => 'eq']);
    if($Partner !== '') array_push($rules, ['field' => 'formularios_genericos.mandante', 'data' => $Partner, 'op' => 'eq']);

    // Prepara y ejecuta la consulta para obtener los formularios
    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $FormulariosGenricos = new FormulariosGenericos();
    $formularios = $FormulariosGenricos->getFormularioGenericoCustom('formularios_genericos.*', 'formularios_genericos.formgenerico_id', 'asc', $SkeepRows, $MaxRows, $filters, true);

    $formularios = json_decode($formularios);

    // Inicializa el array para almacenar los formularios procesados
    $GenericForms = [];

    // Procesa cada formulario obtenido y extrae la información relevante
    foreach($formularios->data as $key => $value) {
        $data = [];
        $data['Id'] = $value->{'formularios_genericos.formgenerico_id'};
        $data['DateTimeCreation'] = $value->{'formularios_genericos.fecha_crea'};
        $data['Form'] = is_string($value->{'formularios_genericos.form_data'}) ? json_decode($value->{'formularios_genericos.form_data'})->form : '';

        // Extrae y formatea los datos específicos del formulario
        $data['Message'] = is_string($value->{'formularios_genericos.form_data'}) ? json_encode((json_decode($value->{'formularios_genericos.form_data'})->visual)) : '{}';
        $data['UserId'] = $value->{'formularios_genericos.usuario_id'};
        $data['Name'] = json_decode($value->{'formularios_genericos.form_data'})->form->{'first_name'};
        $data['Email'] = json_decode($value->{'formularios_genericos.form_data'})->form->{'email'};
        $data['Phone'] = json_decode($value->{'formularios_genericos.form_data'})->form->{'phone'};
        $data['Diligence'] = $value->{'formularios_genericos.diligenciado'} == 'S' ? 'SI' : 'NO';

        // Completa la información adicional del formulario
        $data['FilledOut'] = $value->{'formularios_genericos.'} === 'S' ? 'SI' : 'NO';
        $data['Year'] = $value->{'formularios_genericos.anio'};
        $data['Partner'] = $value->{'formularios_genericos.mandante'};
        $data['Country'] = $value->{'formularios_genericos.pais_id'};
        $data['Type'] = $value->{'formularios_genericos.tipo'};

        array_push($GenericForms, $data);
    }

    // Prepara la respuesta final con los formularios procesados
    $response = [];
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];

    $response['data'] = $GenericForms ?: [];
?>