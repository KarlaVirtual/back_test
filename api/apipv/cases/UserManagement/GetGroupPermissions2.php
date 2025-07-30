<?php

use Backend\dto\PerfilSubmenu;
use Backend\dto\Submenu;
use Backend\dto\UsuarioPerfil;

/**
 * GetGroupPermissions2
 *
 * Este script obtiene los permisos de un grupo de usuarios, organizándolos en una estructura jerárquica
 * según menús, submenús y permisos específicos.
 *
 * @param object $json JSON recibido desde la entrada que contiene los parámetros de la solicitud.
 * @param string $json->roleId ID del rol del usuario.
 * @param string $json->UserId ID del usuario.
 * 
 * 
 * @return array $response Respuesta con el estado de la operación.
 *                         - HasError: bool Indica si ocurrió un error.
 *                         - AlertType: string Tipo de alerta (success, error, etc.).
 *                         - AlertMessage: string Mensaje de alerta.
 *                         - Data: array Datos de permisos organizados.
 *                             - ExcludedPermissions: array Lista de permisos excluidos.
 *                             - IncludedPermissionList: string Lista de permisos incluidos.
 * @throws Exception Si ocurre un error al obtener los permisos o procesar los datos.
 */

/*El código maneja la autenticación y genera una respuesta JSON con el estado de permisos.*/
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

    /*El código obtiene y filtra menús y submenús según permisos de usuario.*/
    $roleId = $_REQUEST['roleId'];
    $UserId = $_REQUEST['UserId'];
    $Submenu = new Submenu();

    $rules = [];
    array_push($rules, ['field' => 'menu.version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $query_data = $Submenu->getAllMenusCustom('menu.*, submenu.*', 'submenu.submenu_id', 'asc', 0, 100000, $filters, true);

    $query_data = json_decode($query_data);

    $menus = array_filter($query_data->data, function ($item) {
        if ($item->{'submenu.menu_principal'} == 1) return $item;
    });

    /*Obtiene y ordena menús con permisos de usuario según su orden de visualización.*/
    $permissions_menus = [];

    foreach ($menus as $key => $value) {
        $data = [];
        $data['id'] = $value->{'submenu.submenu_id'};
        $data['value'] = $value->{'menu.descripcion'} . " - " . $value->{"menu.pagina"};
        $data['menu'] = $value->{'menu.menu_id'};
        $data['orden'] = $value->{'menu.orden'};

        array_push($permissions_menus, $data);
    }

    usort($permissions_menus, function ($a, $b) {
        if ($a['orden'] > $b['orden']) return 1;

        return 0;
    });

    /*Filtra y ordena submenús y permisos de usuario según su jerarquía y orden.*/
    $submenus = array_filter($query_data->data, function ($item) {
        if (!empty($item->{'submenu.descripcion'}) and $item->{'submenu.parent'} == 0) return $item;
    });

    $permissions_submenus = [];

    foreach ($submenus as $key => $value) {
        $data = [];
        $data['id'] = $value->{'submenu.submenu_id'};
        $data['value'] = $value->{'submenu.descripcion'} . " - " . $value->{"submenu.pagina"};
        $data['menu'] = $value->{'menu.menu_id'};
        $data['orden'] = $value->{'submenu.orden'};

        array_push($permissions_submenus, $data);
    }

    usort($permissions_submenus, function ($a, $b) {
        if ($a['orden'] > $b['orden']) return 1;

        return 0;
    });

    $permissions = array_filter($query_data->data, function ($item) {
        if ($item->{'submenu.parent'} != 0 and $item->{'submenu.orden'} == 0) return $item;
    });

    /*El código recopila permisos de submenús y los organiza en una estructura de datos.*/
    $sub_permissions = [];

    foreach ($permissions as $key => $value) {
        $data = [];
        $data['id'] = $value->{'submenu.submenu_id'};
        $data['value'] = $value->{'submenu.descripcion'} . " - " . $value->{"submenu.pagina"};
        $data['parent'] = $value->{'submenu.parent'};

        array_push($sub_permissions, $data);
    }

    $all_permissions = [];

    foreach ($permissions_menus as $key => $value) {
        $data = [];
        $data['id'] = $value['id'];
        $data['value'] = $value['value'];
        $data['data'] = [];

        $sub = array_filter($permissions_submenus, function ($item) use ($value) {
            if ($value['menu'] == $item['menu']) return $item;
        });

        /*Filtra y organiza submenús y permisos de usuario en una estructura de datos jerárquica.*/
        if (oldCount($sub) > 0) {
            foreach ($sub as $key => $sub_value) {
                $sub_data = [];
                $sub_data['id'] = $sub_value['id'];
                $sub_data['value'] = $sub_value['value'];
                $sub_data['data'] = [];

                $perms = array_filter($sub_permissions, function ($perm_item) use ($sub_value) {
                    if ($sub_value['id'] == $perm_item['parent']) return $perm_item;
                });

                if (oldCount($perms) > 0) {
                    foreach ($perms as $key => $perm_value) {
                        $perm_data = [];
                        $perm_data['id'] = $perm_value['id'];
                        $perm_data['value'] = $perm_value['value'];

                        array_push($sub_data['data'], $perm_data);
                    }
                };

                array_push($data['data'], $sub_data);
            }
        } else {
            /*El código filtra y organiza permisos de submenús en una estructura de datos jerárquica.*/
            $perms = array_filter($sub_permissions, function ($perm_item) use ($value) {
                if ($value['id'] == $perm_item['parent']) return $perm_item;
            });

            if (oldCount($perms) > 0) {
                foreach ($perms as $keys => $perm_value) {
                    $perm_data = [];
                    $perm_data['id'] = $perm_value['id'];
                    $perm_data['value'] = $perm_value['value'];

                    array_push($data['data'], $perm_data);
                }
            }
        }

        array_push($all_permissions, $data);
    }


    /*Filtra y ordena submenús genéricos según versión y menú\_id, luego organiza permisos.*/
    $rules = [];

    array_push($rules, ['field' => 'version', 'data' => 3, 'op' => 'eq']);
    array_push($rules, ['field' => 'menu_id', 'data' => '0', 'op' => 'eq']);
//    array_push($rules, ['field' => 'orden', 'data' => '0', 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $generic = $Submenu->getAllSubmenusCustom('*', 'submenu_id', 'asc', 0, 100000, $filters, true);

    $generic = json_decode($generic);

    usort($generic->data, function ($a, $b) {
        return empty($a->{'submenu.parent'}) ? 0 : 1;
    });

    $perms_generic = [];

    /*El código organiza submenús genéricos en una estructura de datos jerárquica.*/
    foreach ($generic->data as $key => $value) {
        $data = [];
        $data['id'] = $value->{'submenu.submenu_id'};
        $data['value'] = $value->{'submenu.descripcion'} . " - " . $value->{"submenu.pagina"};
        $data['data'] = [];
        $newPerm = true;

        if (array_search($value->{'submenu.parent'}, array_column($perms_generic, 'id')) !== false) {
            $index = array_search($value->{'submenu.parent'}, array_column($perms_generic, 'id'));
            array_push($perms_generic[$index]['data'], $data);
            $newPerm = false;
        }

        if ($newPerm) array_push($perms_generic, $data);

    }

    if (!empty($roleId)) {
        $PerfilSubmenu = new PerfilSubmenu();
        $profile_id = $roleId;

        /*El código obtiene permisos personalizados de un perfil de usuario específico.*/
        if ($profile_id === 'CUSTOM' and !empty($UserId)) {
            $UsuarioPerfil = new UsuarioPerfil($UserId);

            $rules = [];

            array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => $profile_id, 'op' => 'eq']);
            array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
            //array_push($rules, ['field' => 'perfil_submenu.mandante', 'data' => '-1', 'op' => 'eq']);
            array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => $UsuarioPerfil->usuarioId, 'op' => 'eq']);

            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $profile_custom_permissions = $PerfilSubmenu->getPerfilGenericCustom('perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

            $profile_custom_permissions = json_decode($profile_custom_permissions)->data;

            $profile_id = $UsuarioPerfil->getPerfilId();
        }

        /*El código obtiene permisos de perfil y los organiza en una lista de permisos incluidos.*/
        $rules = [];

        array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => $profile_id, 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.mandante', 'data' => '-1', 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => '0', 'op' => 'eq']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
        $profile_permissions = $PerfilSubmenu->getPerfilGenericCustom('perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 10000000, $filters, true);

        $profile_permissions = json_decode($profile_permissions)->data;

        if (oldCount($profile_custom_permissions) > 0) $profile_permissions = array_merge($profile_permissions, $profile_custom_permissions);

        $include_permissions = [];

        foreach ($profile_permissions as $key => $value) {
            if (in_array($value->{'perfil_submenu.submenu_id'}, $include_permissions) === false) array_push($include_permissions, $value->{'perfil_submenu.submenu_id'});
        }
    }

    /*El código maneja permisos de usuario y genera una respuesta JSON con los resultados.*/
    if (oldCount($perms_generic) > 0) $all_permissions = array_merge($all_permissions, $perms_generic);

    $response = [];

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response['Data'] = [
        'ExcludedPermissions' => $all_permissions,
        'IncludedPermissionList' => implode(',', $include_permissions),
    ];
}
?>
