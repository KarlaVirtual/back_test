<?php

use Backend\dto\UsuarioRestriccion;

/**
 * AdminUser/GetRestrictUsers
 *
 * Obtener los usuarios restringidos
 *
 * @param no
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors*  (array): retorna array vacio
 *  - *data* (array): Usuarios restringidos.
 *   - *pos* (int): Posición de inicio de los datos devueltos.
 *   - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 *
 *
 * @throws no No contiene manejo de exepciones
 *
 */

/**
 * Obtiene el tipo de documento correspondiente al valor proporcionado.
 *
 * La función convierte un valor numérico en su tipo de documento asociado ('C', 'P', 'E')
 * o, si se proporciona una letra, devuelve el número correspondiente según la asignación predefinida.
 *
 * @param string|int $value Valor numérico o letra representativa del tipo de documento.
 * @return string|int|null Tipo de documento correspondiente o null si no se encuentra coincidencia.
 * @throws no No contiene manejo de excepciones.
 */
function getDocType($value)
{
    $types = ['1' => 'C', '2' => 'P', '3' => 'E'];
    return is_numeric($value) ? $types[$value] : array_search($value, $types);
}

/**
 * Obtiene el estado correspondiente al valor proporcionado.
 *
 * La función convierte un valor numérico en su estado asociado ('A' para 0, 'E' para 1)
 * o, si se proporciona una letra, devuelve el número correspondiente según la asignación predefinida.
 *
 * @param string|int $value Valor numérico o letra representativa del estado.
 * @return string|int|null Estado correspondiente o null si no se encuentra coincidencia.
 * @throws no No contiene manejo de excepciones.
 */
function getState($value)
{
    $states = ['0' => 'A', '1' => 'E'];
    return is_numeric($value) ? $states[$value] : array_search($value, $states);
}

/*Obtención parámetros de consulta*/
$Email = $_REQUEST['Email'];
$Document = $_REQUEST['Document'];
$DocType = $_REQUEST['DocType'];
$Name = $_REQUEST['Name'];
$Phone = $_REQUEST['Phone'];
$Partner = $_REQUEST['Partner'] ?: $_SESSION['mandante'];
if ($Partner == -1) $Partner = null;
$Country = $_REQUEST['CountrySelect'];
$Type = $_REQUEST['Type'];
$State = $_REQUEST['State'] < 0 ? '' : $_REQUEST['State'];
$Start = $_REQUEST['start'] ?: 0;
$Count = $_REQUEST['count'] ?: 10;

$rules = [];

/*Generación de filtrado*/
if (!empty($Email)) array_push($rules, ['field' => 'usuario_restriccion.email', 'data' => $Email, 'op' => 'eq']);
if (!empty($Document)) array_push($rules, ['field' => 'usuario_restriccion.documento', 'data' => $Document, 'op' => 'eq']);
if (!empty($DocType)) array_push($rules, ['field' => 'usuario_restriccion.tipo_doc', 'data' => getDocType($DocType), 'op' => 'eq']);
if (!empty($Name)) array_push($rules, ['field' => 'usuario_restriccion.nombre', 'data' => $Name, 'op' => 'cn']);
if (!empty($Phone)) array_push($rules, ['field' => 'usuario_restriccion.telefono', 'data' => $Phone, 'op' => 'eq']);
if (!empty($Type)) array_push($rules, ['field' => 'usuario_restriccion.clasificador_id', 'data' => $Type, 'op' => 'eq']);
if ($State != '') array_push($rules, ['field' => 'usuario_restriccion.estado', 'data' => getState($State), 'op' => 'eq']);
if ($Partner != '') array_push($rules, ['field' => 'usuario_restriccion.mandante', 'data' => $Partner, 'op' => 'eq']);
if ($Country != '') array_push($rules, ['field' => 'usuario_restriccion.pais_id', 'data' => $Country, 'op' => 'eq']);

$filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$UsuarioRestriccion = new UsuarioRestriccion();
$query = $UsuarioRestriccion->getUsuraioRestriccionCustom('usuario_restriccion.*, clasificador.clasificador_id,  clasificador.descripcion', 'usuario_restriccion.usurestriccion_id', 'desc', $Start, $Count, $filters, true);
$query = json_decode($query, true);

$users = [];

foreach ($query['data'] as $key => $value) {
    /*Construcción de respuesta*/
    $data = [];
    $data['Id'] = $value['usuario_restriccion.usurestriccion_id'];
    $data['Email'] = $value['usuario_restriccion.email'];
    $data['Document'] = $value['usuario_restriccion.documento'];
    $data['DocType'] = getDocType($value['usuario_restriccion.tipo_doc']);
    $data['Phone'] = $value['usuario_restriccion.telefono'];
    $data['Name'] = $value['usuario_restriccion.nombre'];
    $data['State'] = getState($value['usuario_restriccion.estado']);
    $data['Note'] = $value['usuario_restriccion.nota'];
    $data['Partner'] = $value['usuario_restriccion.mandante'];
    $data['Country'] = $value['usuario_restriccion.pais_id'];
    $data['TypeId'] = $value['clasificador.clasificador_id'];
    $data['Type'] = $value['clasificador.descripcion'];
    $data['UserCreated'] = $value['usuario_restriccion.usucrea_id'];
    $data['UserModified'] = $value['usuario_restriccion.usumodif_id'];
    $data['Date'] = $value['usuario_restriccion.fecha_crea'];
    $data['DateModified'] = $value['usuario_restriccion.fecha_modif'];

    array_push($users, $data);
}

$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $users;
$response['pos'] = $Start;
$response['total_count'] = $query['count'][0]['.count'];
?>