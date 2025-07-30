<?php

use Backend\dto\Menu;

/**
 * Obtener menús principales
 *
 * Este script permite obtener los menús principales disponibles para el usuario autenticado.
 *
 * @param object $params Objeto con los siguientes parámetros:
 * @param $params->version int Versión del menú a filtrar.
 *
 * @return array $response Respuesta en formato JSON con los siguientes campos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., success, danger).
 * - AlertMessage (string): Mensaje de alerta.
 * - Data (array): Lista de menús principales con sus propiedades.
 *
 * @throws Exception Si el usuario no está autenticado.
 */

/* Verifica si el usuario está autenticado y genera un mensaje de error. */
if (!$_SESSION['logueado']) {
    $response = [];
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Not authenticated";
    $response["Data"] = [
        "AuthenticationStatus" => 0,
        "PermissionList" => [],
    ];
} else {


    /* Se definen reglas de filtrado en un menú utilizando una condición de igualdad. */
    $rules = [];

    array_push($rules, ['field' => 'version', 'data' => 3, 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $Menu = new Menu();

    /* Se obtienen y decodifican menús personalizados, excluyendo aquellos que contienen "Afiliados". */
    $menus = $Menu->getMenusCustom('*', 'orden', 'asc', 0, 1000, $filters, true);

    $menus = json_decode($menus);

    $father_menus = [];

    $excludes = ['Afiliados'];


    /* Recorre menús, asigna datos y aplica condiciones antes de añadirlos a un arreglo. */
    foreach ($menus->data as $key => $value) {
        $data = [];
        $data['id'] = $value->{'menu.menu_id'};
        $data['value'] = $value->{'menu.descripcion'};
        $data['icon'] = !empty($value->{'menu.icon'}) ? $value->{'menu.icon'} : 'icon-pie-chart';
        $data['show'] = in_array($value->{'menu.descripcion'}, $excludes) ? false : true;
        array_push($father_menus, $data);
    }


    /* Código para estructurar una respuesta exitoso en formato JSON. */
    $response = [];

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response['Data'] = $father_menus;
}
?>
