<?php

use Backend\dto\Submenu;

/**
 * Obtiene todos los menús y submenús personalizados según los parámetros de solicitud.
 *
 * @param string|null $Description Descripción del menú.
 * @param string|null $PaternMenu ID del menú padre.
 * @param int|null $Order Orden del menú.
 * @param string|null $Url URL del menú.
 * @return array $response Respuesta JSON con los siguientes valores:
 *                         - bool $response['HasError'] Indica si hubo un error (true/false).
 *                         - string $response['AlertType'] Tipo de alerta (success/danger).
 *                         - string $response['AlertMessage'] Mensaje de alerta.
 *                         - array $response['Data'] Lista de menús y submenús.
 */

/*Verifica si el usuario está autenticado y prepara la respuesta en consecuencia.*/
if(!$_SESSION['logueado']) {
    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'danger';
    $response['AlertMessage'] = 'Not authenticated';
    $response['Data'] = [
        'AuthenticationStatus' => 0,
        'PermissionList' => [],
    ];
} else {

    /*Asigna valores de solicitud a variables y crea una instancia de la clase Submenu.*/
    $Description = $_REQUEST['Description'];
    $PaternMenu = $_REQUEST['FatherMenu'];
    $Order = $_REQUEST['Order'];
    $Url = $_REQUEST['Url'];

    $Submenu = new Submenu();

    /*Genera filtros y obtiene menús personalizados según los parámetros de solicitud.*/
    $rules = [];

    array_push($rules, ['field' => 'menu.version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.parent', 'data' => '0', 'op' => 'eq']);

    if(!empty($Description)) array_push($rules, ['field' => 'submenu.descripcion', 'data' => $Description, 'op' => 'cn']);

    if(!empty($PaternMenu)) array_push($rules, ['field' => 'submenu.menu_id', 'data' => $PaternMenu, 'op' => 'eq']);

    if(!empty($Order)) array_push($rules, ['field' => 'submenu.orden', 'data' => $Order, 'op' => 'eq']);

    if(!empty($Url)) array_push($rules, ['field' => 'submenu.pagina', 'data' => $Url, 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $menus = $Submenu->getAllMenusCustom('menu.*, submenu.*', 'menu.orden', 'asc', 0, 10000, $filters, true);

    $menus = json_decode($menus);

    $all_menus = [];

    $exclude = ['Afiliados'];

    foreach($menus->data as $key => $value) {

        /*Inicializa datos del menú y submenú si existen descripciones y páginas no vacías.*/
        $data = [];
        $data['id'] = $value->{'menu.menu_id'};
        $data['page'] = $value->{'menu.pagina'};
        $data['description'] = $value->{'menu.descripcion'};
        $data['order'] = $value->{'menu.orden'};
        $data['notMenu'] = in_array($value->{'menu.descripcion'}, $exclude) ? true : false;
        $new_item = true;

        $data_sub = [];

        if(!empty($value->{'submenu.descripcion'}) && !empty($value->{'submenu.pagina'})) {
            $data_sub = [
                'id' => $value->{'submenu.submenu_id'},
                'page' => $value->{'submenu.pagina'},
                'description' => $value->{'submenu.descripcion'},
                'order' => $value->{'submenu.orden'},
                'menu' => $value->{'menu.menu_id'}
            ];
        }

        /*Verifica y agrega submenús a la lista de menús si no existen duplicados.*/
        if(array_search($data_sub['menu'], array_column($all_menus, 'id')) !== false) {
            unset($data_sub['menu']);
            $index = array_search($value->{'menu.menu_id'}, array_column($all_menus, 'id'));

            array_push($all_menus[$index]['data'], $data_sub);

            $new_item = false;

        } else {
            $data['data'] = [];
            if(oldCount($data_sub) > 0) array_push($data['data'], $data_sub);
        }

        if(array_search($data['id'], array_column($all_menus, 'id')) === false && $new_item) array_push($all_menus, $data);
    }

    $all_menus = array_map(function($item) {
        if(oldCount($item['data']) > 0) usort($item['data'], function($after, $before) {
            return $before['order'] < $after['order'] ? 1 : 0;
        });

        return $item;
    }, $all_menus);

    /*Genera filtros y obtiene submenús personalizados según los parámetros de solicitud.*/
    $rules = [];

    array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.menu_id', 'data' =>  '0', 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.orden', 'data' => '0', 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.parent', 'data' => '0', 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $generic = $Submenu->getAllSubmenusCustom('*', 'submenu.submenu_id', 'asc', 0, 10, $filters, true);

    $generic = json_decode($generic);

    /*Itera sobre datos genéricos y agrega submenús a la lista de todos los menús.*/
    foreach($generic->data as $key => $value) {
        $data = [];
        $data['id'] = $value->{'submenu.submenu_id'};
        $data['page'] = $value->{'submenu.pagina'};
        $data['description'] = $value->{'submenu.descripcion'};
        $data['order'] = $value->{'submenu.order'};
        $data['notMenu'] = true;

        array_push($all_menus, $data);
    }

    /*Formato de respuesta*/
    $response = [];

    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['Data'] = $all_menus;
}
?>
