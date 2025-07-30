<?php

use Backend\dto\PerfilSubmenu;
use Backend\dto\ReporteDinamico;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\mysql\UsuarioMensajeMySqlDAO;


if (true) {

    /**
     * Account/GetUserPermissions
     *
     * Verifica si el usuario está autenticado en la sesión.
     * Si no lo está, se prepara una respuesta de error,
     * de lo contrario se inicializan objetos y se definen reglas de filtros.
     *
     * @global array $_SESSION Variable de sesión que contiene información del usuario.
     * @var array $response Array que almacenará la respuesta sobre la autenticación y otros datos.
     * @var PerfilSubmenu $PerfilSubmenu Objeto para manejar la lógica de menú de perfil.
     * @var ReporteDinamico $ReporteDinamico Objeto para manejar la lógica de reportes dinámicos.
     * @var int $SkeepRows Número de filas a omitir en la consulta.
     * @var int $OrderItems Número de ítems a ordenar.
     * @var int $MaxRows Máximo número de filas a recuperar.
     * @var array $rules Lista de reglas para filtros de consulta.
     *
     *  El objeto $response es un array con los siguientes atributos:
     *   - *HasError* (bool): Indica si hubo un error durante el proceso.
     *   - *AlertType* (string): Tipo de alerta que se mostrará.
     *   - *AlertMessage* (string): Mensaje de alerta generado.
     *   - *ModelErrors* (array): Errores del modelo, si los hubiera.
     *   - *Data* (array): Contiene la información del estado de autenticación y los menus del usuario.
     *
     *  Ejemplo de respuesta en caso de error:
     *
     *  $response["HasError"] = true;
     *  $response["AlertType"] = "danger";
     *  $response["AlertMessage"] = "Error no authemticated";
     *  $response["ModelErrors"] = [];
     */

    if (!$_SESSION['logueado']) {
        $response['HasError'] = true;
        $response['AlertType'] = 'danger';
        $response['AlertMessage'] = 'Error no authemticated';
        $response['ModelErrors'] = [];

        $response['Data'] = [
            'AuthenticationStatus' => 0,
            'PermissionList' => [],
        ];
    } else {
        $PerfilSubmenu = new PerfilSubmenu();
        $ReporteDinamico = new ReporteDinamico();

        $SkeepRows = 0;
        $OrderItems = 1;
        $MaxRows = 100000;

        $rules = [];

        array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => $_SESSION['win_perfil'], 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => '0', 'op' => 'eq']);

        if ($_SESSION['win_perfil'] == "CUSTOM") {
            array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        } else {
            if ($_SESSION["win_perfil2"] != "SA") {
                if ($_SESSION["mandante"] == '6' && ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO")) {
                    array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '6', "op" => "eq"));
                } else {
                    if ($_SESSION["mandante"] == '8' && $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                        array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));

                    } elseif ($_SESSION["mandante"] == '8' && $_SESSION["win_perfil2"] == "TESORERIA") {
                        array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));

                    } elseif ($_SESSION["mandante"] == '16' && $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                        array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));

                    } else {
                        array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '-1', "op" => "eq"));

                    }
                }

            } else {
                array_push($rules, array("field" => "perfil_submenu.mandante", "data" => '-1', "op" => "eq"));

            }
        }

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        // Obtiene los permisos de perfil submenús personalizados
        $query_permissions = $PerfilSubmenu->getPerfilSubmenusCustom('menu.*, submenu.*, perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

        // Decodifica los permisos obtenidos desde JSON
        $query_permissions = json_decode($query_permissions);

        // Inicializa un nuevo arreglo de reglas
        $rules = [];

        // Agrega reglas específicas al nuevo arreglo
        array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => 'CUSTOM', 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => $_SESSION['usuario'], 'op' => 'eq']);

        // Convierte las nuevas reglas a formato JSON para filtros
        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        // Obtiene los permisos de perfil submenús personalizados con las nuevas reglas
        $query_permissions_custom = $PerfilSubmenu->getPerfilSubmenusCustom('menu.*, submenu.*, perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

        // Decodifica los permisos personalizados obtenidos desde JSON
        $query_permissions_custom = json_decode($query_permissions_custom);

        // Itera sobre los permisos personalizados para verificar y agregar nuevos valores
        foreach ($query_permissions_custom->data as $key => $value) {
            if (!in_array($value->{'submenu.submenu_id'}, array_column(json_decode(json_encode($query_permissions->data), true), 'submenu.submenu_id'))) {
                array_push($query_permissions->data, $value);
            }
        }
        /**
         * Filtra el array de permisos de consulta para obtener solo aquellos elementos
         * que tienen un submenú marcado como menú principal.
         */
        $menus = array_filter($query_permissions->data, function ($item) {
            if ($item->{'submenu.menu_principal'} == 1) return $item;
        });

        $permissions_menu = [];

        // Itera sobre cada elemento en $menus para construir los permisos del menú
        foreach ($menus as $key => $value) {
            // Inicializa un arreglo para almacenar los datos del menú actual
            $data = [];
            $data['index'] = $value->{'submenu.submenu_id'};
            $data['id'] = $value->{'menu.pagina'};
            $data['value'] = $value->{'menu.descripcion'};
            $data['icon'] = $value->{'menu.icon'};
            $data['order'] = $value->{'menu.orden'};
            $data['menu'] = $value->{'menu.menu_id'};
            // Verifica si el permiso para añadir está activado
            $data['add'] = ($value->{'perfil_submenu.adicionar'} == "true") ? true : false;
            // Verifica si el permiso para editar está activado
            $data['edit'] = ($value->{'perfil_submenu.editar'} == "true") ? true : false;
            // Verifica si el permiso para eliminar está activado
            $data['delete'] = ($value->{'perfil_submenu.eliminar'} == "true") ? true : false;

            // Agrega el conjunto de permisos del menú al arreglo de permisos
            array_push($permissions_menu, $data);
        }

        /**
         * Filtra los submenús a partir de los permisos de consulta.
         *
         */
        $submenus = array_filter($query_permissions->data, function ($item) {
            if (!empty($item->{'submenu.descripcion'}) and $item->{'submenu.parent'} == 0) return $item;
        });

        $permissions_submenu = [];

        // Itera sobre los submenús proporcionados.
        foreach ($submenus as $key => $value) {
            $data = [];
            // Asigna el ID del submenú
            $data['index'] = $value->{'submenu.submenu_id'};
            // Asigna la página del submenú
            $data['id'] = $value->{'submenu.pagina'};
            // Asigna la descripción del submenú
            $data['value'] = $value->{'submenu.descripcion'};
            // Asigna el ID del menú al que pertenece el submenú
            $data['menu'] = $value->{'submenu.menu_id'};
            // Asigna el orden del submenú
            $data['order'] = $value->{'submenu.orden'};
            // Determina si se puede adicionar
            $data['add'] = ($value->{'perfil_submenu.adicionar'} == "true") ? true : false;
            // Determina si se puede editar
            $data['edit'] = ($value->{'perfil_submenu.editar'} == "true") ? true : false;
            // Determina si se puede eliminar
            $data['delete'] = ($value->{'perfil_submenu.eliminar'} == "true") ? true : false;

            // Agrega los datos del submenú al arreglo de permisos
            array_push($permissions_submenu, $data);
        }

        $permissions = array_filter($query_permissions->data, function ($item) {
            if ($item->{'submenu.orden'} == 0 and $item->{'submenu.parent'} != 0) return $item;
        });
        /**
         * Inicializa un array para las sub-permisos.
         *
         * Este arreglo almacenará la información de los submenús y sus permisos asociados.
         */
        $sub_permissions = [];

        // Itera sobre cada permiso para construir el arreglo de sub-permisos
        foreach ($permissions as $key => $value) {
            $data = [];
            $data['index'] = $value->{'submenu.submenu_id'};
            $data['id'] = $value->{'submenu.pagina'};
            $data['value'] = $value->{'submenu.descripcion'};
            $data['parent'] = $value->{'submenu.parent'};
            $data['add'] = ($value->{'perfil_submenu.adicionar'} == "true") ? true : false;
            $data['edit'] = ($value->{'perfil_submenu.editar'} == "true") ? true : false;
            $data['delete'] = ($value->{'perfil_submenu.eliminar'} == "true") ? true : false;

            // Agrega el sub-menú al arreglo de sub-permisos
            array_push($sub_permissions, $data);
        }

        /**
         * Inicializa un array para todas las reglas de permisos.
         *
         * Este arreglo será utilizado para almacenar las reglas que se aplican a los permisos.
         */
        $all_permissions = [];

        $rules = [];

        /**
         * Crea una instancia de UsuarioMandante con el usuario de la sesión actual.
         */
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        // Determina el ID del país basado en las condiciones de la sesión
        if ($_SESSION['PaisCond'] === 'S') {
            $pais_id = $UsuarioMandante->getPaisId();
        } else {
            $pais_id = !empty($_SESSION['PaisCondS']) ? $_SESSION['PaisCondS'] : '0';
        }

        // Agrega reglas para filtrar los permisos en base al país, mandante y versión del submenú
        array_push($rules, ['field' => 'reporte_dinamico.pais_id', 'data' => $pais_id, 'op' => 'eq']);
        array_push($rules, ['field' => 'reporte_dinamico.mandante', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);

        // Convierte las reglas a formato JSON para usarlas en la consulta
        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        /**
         * Obtiene las columnas del submenú utilizando el objeto ReporteDinamico.
         *
         * Las columnas obtenidas son aquellas necesarias para la dinámica del reporte.
         */
        $submenu_columns = $ReporteDinamico->getReporteDinamicoCustom('submenu.*, reporte_dinamico.*', 'reporte_dinamico.reportedinamico_id', 'asc', 0, 10000, $filters, true);

        // Decodifica el resultado JSON en un objeto
        $submenu_columns = json_decode($submenu_columns);

        // Define los menús y submenús a excluir
        $excludeMenus = ['Afiliados', 'Sitebuilderg'];
        $excludeSubMenus = ['Herramientas', 'Maquinas', 'Mensajes', 'Contabilidad', 'Mi Configuracion'];

        foreach ($permissions_menu as $key => $value) {
            $data = [];
            $data['id'] = $value['id'];
            $data['value'] = $value['value'];
            $data['icon'] = $value['icon'];
            $data['order'] = $value['order'];
            $data['add'] = ($value['add'] == "true") ? true : false;
            $data['edit'] = ($value['edit'] == "true") ? true : false;
            $data['delete'] = ($value['delete'] == "true") ? true : false;
            $data['show'] = in_array($value['value'], $excludeMenus) ? false : true;
            $data['data'] = [];

            $sub = array_filter($permissions_submenu, function ($item) use ($value) {
                if ($value['menu'] === $item['menu']) return $item;
            });

            // Se busca el índice de la columna dentro de las columnas del submenú.
            $columns_index = array_search(($value['index']), array_column(json_decode(json_encode($submenu_columns->data), true), 'submenu.submenu_id'));

            if ($columns_index !== false) {
                $data['columns'] = json_decode($submenu_columns->data[$columns_index]->{'reporte_dinamico.columnas'});
            }

            if (oldCount($sub) > 0) {

                /**
                 * Itera sobre los elementos en $sub y construye un arreglo $sub_data
                 * para cada elemento, que contiene información sobre permisos y
                 * columnas asociados. Luego, se agrega cada $sub_data al arreglo
                 * $data['data'].
                 */

                foreach ($sub as $key => $sub_value) {

                    $sub_data = [];
                    $sub_data['id'] = $sub_value['id'];
                    $sub_data['value'] = $sub_value['value'];
                    $sub_data['order'] = $sub_value['order'];
                    $sub_data['add'] = ($sub_value['add'] == "true") ? true : false;
                    $sub_data['edit'] = ($sub_value['edit'] == "true") ? true : false;
                    $sub_data['delete'] = ($sub_value['delete'] == "true") ? true : false;
                    if (preg_match('/([0-9])+/', $sub_value['value']) == true) $sub_data['value'] = preg_replace('/([0-9])+/', '-', $sub_data['value']);
                    if (in_array($data['value'], $excludeSubMenus)) {
                        $sub_data['show'] = true;
                    } else {
                        $sub_data['show'] = strpos($sub_value['id'], '.') ? false : true;
                    }

                    $sub_data['data'] = [];

                    $perms = array_filter($sub_permissions, function ($item) use ($sub_value) {
                        if ($sub_value['index'] === $item['parent']) return $item;
                    });

                    $columns_index = array_search($sub_value['index'], array_column(json_decode(json_encode($submenu_columns->data), true), 'submenu.submenu_id'));

                    if ($columns_index !== false) {
                        $sub_data['columns'] = json_decode($submenu_columns->data[$columns_index]->{'reporte_dinamico.columnas'});
                    }

                    if (oldCount($perms) > 0) {
                        foreach ($perms as $key => $perm_value) {
                            $perm_data = [];
                            $perm_data['id'] = $perm_value['id'];
                            $perm_data['value'] = $perm_value['value'];
                            $perm_data['add'] = ($perm_value['add'] == "true") ? true : false;
                            $perm_data['edit'] = ($perm_value['edit'] == "true") ? true : false;
                            $perm_data['delete'] = ($perm_value['delete'] == "true") ? true : false;
                            $perm_data['show'] = false;

                            $columns_index = array_search($perm_value['index'], array_column(json_decode(json_encode($submenu_columns->data), true), 'submenu.submenu_id'));

                            if ($columns_index !== false) {
                                $perm_data['columns'] = json_decode($submenu_columns->data[$columns_index]->{'reporte_dinamico.columnas'});
                            }

                            array_push($sub_data['data'], $perm_data);
                        }
                    }

                    array_push($data['data'], $sub_data);
                }
            } else {
                /**
                 * Filtra las sub-permisos y genera una estructura de datos de permisos.
                 */
                $perms = array_filter($sub_permissions, function ($item) use ($value) {
                    if ($value['index'] === $item['parent']) return $item;
                });

                if (oldCount($perms) > 0) {
                    foreach ($perms as $key => $perm_value) {
                        $perm_data = [];
                        $perm_data['id'] = $perm_value['id'];
                        $perm_data['value'] = $perm_value['value'];
                        $perm_data['add'] = ($perm_value['add'] == "true") ? true : false;
                        $perm_data['edit'] = ($perm_value['edit'] == "true") ? true : false;
                        $perm_data['delete'] = ($perm_value['delete'] == "true") ? true : false;
                        $perm_data['show'] = false;

                        $columns_index = array_search($perm_value['index'], array_column(json_decode(json_encode($submenu_columns->data), true), 'submenu.submenu_id'));

                        if ($columns_index !== false) {
                            $perm_data['columns'] = json_decode($submenu_columns->data[$columns_index]->{'reporte_dinamico.columnas'});
                        }

                        array_push($data['data'], $perm_data);
                    }

                    $columns_index = array_search($perm_value['index'], array_column(json_decode(json_encode($submenu_columns->data), true), 'submenu.submenu_id'));

                    if ($columns_index !== false) {
                        $data['columns'] = json_decode($submenu_columns->data[$columns_index]->{'reporte_dinamico.columnas'});
                    }
                }
            }
            /**
             * Agrega un elemento al array de permisos.
             */
            array_push($all_permissions, $data);
        }

        // Inicializa un array vacío que contendrá las reglas de filtrado
        $rules = [];

        // Agrega reglas de filtrado para el primer conjunto de datos
        array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => $_SESSION['win_perfil'], 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => '0', 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.mandante', 'data' => '-1', 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.menu_id', 'data' => '0', 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.orden', 'data' => '0', 'op' => 'eq']);

        // Convierte el array de reglas en un formato JSON
        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        // Llama a la función para obtener los datos genéricos del perfil
        $query_generic = $PerfilSubmenu->getPerfilGenericCustom('submenu.*, perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

        // Decodifica la respuesta JSON a un objeto
        $query_generic = json_decode($query_generic);

        // Reinicia el array de reglas para un nuevo conjunto de datos
        $rules = [];

        // Agrega reglas de filtrado para el segundo conjunto de datos
        array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => 'CUSTOM', 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => $_SESSION['usuario'], 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.mandante', 'data' => '-1', 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.menu_id', 'data' => '0', 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.orden', 'data' => '0', 'op' => 'eq']);

        // Convierte el nuevo conjunto de reglas en formato JSON
        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        // Llama nuevamente a la función para obtener datos personalizados
        $query_generic_customs = $PerfilSubmenu->getPerfilGenericCustom('submenu.*, perfil_submenu.*', 'submenu.submenu_id', 'asc', 0, 1000000, $filters, true);

        // Decodifica la respuesta JSON personalizada a un objeto
        $query_generic_customs = json_decode($query_generic_customs);

        // Si hay datos en el conjunto personalizado, combina con el conjunto genérico
        if (oldCount($query_generic_customs->data) > 0) $query_generic->data = array_merge($query_generic->data, $query_generic_customs->data);
        // Inicializa un array vacío para almacenar todos los permisos genéricos
        $all_generic_permissions = [];
        /**
         * Recorre los datos obtenidos de la consulta genérica y organiza la información
         * en un formato específico para permisos de usuario. Para cada elemento, se
         * determina si es un nuevo permiso o se agrupa en un permiso existente
         * basado en su relación de padres.
         */
        foreach ($query_generic->data as $key => $value) {
            $data = [];
            $data['index'] = $value->{'submenu.submenu_id'};
            $data['id'] = $value->{'submenu.pagina'};
            $data['value'] = $value->{'submenu.descripcion'};
            $data['add'] = ($value->{'perfil_submenu.adicionar'} == "true") ? true : false;
            $data['edit'] = ($value->{'perfil_submenu.editar'} == "true") ? true : false;
            $data['delete'] = ($value->{'perfil_submenu.eliminar'} == "true") ? true : false;
            $data['show'] = false;
            $newPerm = true;

            if (array_search($value->{'submenu.parent'}, array_column($all_generic_permissions, 'index')) !== false) {
                $index = array_search($value->{'submenu.parent'}, array_column($all_generic_permissions, 'index'));
                if (!isset($all_generic_permissions[$index]['data'])) $all_generic_permissions[$index]['data'] = [];
                array_push($all_generic_permissions[$index]['data'], $data);
                $newPerm = false;
            }

            if ($newPerm) array_push($all_generic_permissions, $data);
        }

        /**
         * Procesa los permisos generados para eliminar el índice innecesario y
         * facilitar la manipulación de los datos en la interfaz.
         */
        $all_generic_permissions = array_map(function ($item) {
            unset($item['index']);
            if (isset($item['data'])) {
                $item['data'] = array_map(function ($sub_item) {
                    unset($sub_item['index']);
                    return $sub_item;
                }, $item['data']);
            }
            return $item;
        }, $all_generic_permissions);

        /**
         * Función anónima para ordenar un arreglo de permisos.
         *
         */
        usort($all_permissions, function ($a, $b) {
            if ($a['order'] > $b['order']) return 1;

            return 0;
        });

        /**
         * Procesa un array de permisos, ordenando y removiendo la clave 'order' de cada ítem.
         */
        $all_permissions = array_map(function ($item) {
            if (oldCount($item['data']) > 0) {
                usort($item['data'], function ($a, $b) {
                    if ($a['order'] > $b['order']) return 1;

                    return 0;
                });

                $item['data'] = array_map(function ($sub_item) {
                    unset($sub_item['order']);
                    return $sub_item;
                }, $item['data']);
            }
            unset($item['order']);
            return $item;
        }, $all_permissions);

        if (oldCount($all_generic_permissions) > 0) $all_permissions = array_merge($all_permissions, $all_generic_permissions);

        if (in_array($_SESSION['win_perfil'], ['PUNTOVENTA', 'CONCESIONARIO', 'CONCESIONARIO2'])) {
            /**
             * Se crea una nueva instancia de UsuarioMensaje.
             */
            $UsuarioMensaje = new UsuarioMensaje();
            $rules = [];

            /**
             * Se agregan las reglas de filtrado para obtener mensajes no leídos de un usuario específico, en un país y proveedor determinados.
             */
            array_push($rules, ['field' => 'usuario_mensaje.is_read', 'data' => '0', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => $_SESSION['usuario2'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.proveedor_id', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.pais_id', 'data' => $_SESSION['pais_id'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => "'MENSAJE', 'POPUP'", 'op' => 'in']);
            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => date('Y-m-d H:i:s'), 'op' => 'ge']);

            /**
             * Se convierte el array de reglas en formato JSON para ser utilizado en la consulta.
             */
            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            /**
             * Se obtienen los mensajes del usuario de acuerdo a los filtros establecidos.
             */
            $messages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usufrom.nombres', 'usuario_mensaje.fecha_crea', 'desc', 0, 10000, $filter, true);
            $messages = json_decode($messages, true);

            $readyMessages = '';

            /**
             * Se procesan los mensajes para obtener los IDs de los mensajes padre que no estén en la cadena de readyMessages.
             */
            foreach ($messages['data'] as $key => $value) {
                if (!empty($value['usuario_mensaje.parent_id'])) {
                    $readyMessages .= strpos($readyMessages, $value['usuario_mensaje.parent_id']) === false ? $value['usuario_mensaje.parent_id'] . ',' : '';
                }
            }

            $rules = [];

            /**
             * Se agregan las reglas de filtrado para obtener mensajes masivos no leídos.
             */
            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => -1, 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.is_read', 'data' => '0', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.proveedor_id', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.pais_id', 'data' => $_SESSION['pais_id'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => "'MENSAJE', 'POPUP'", 'op' => 'in']);
            array_push($rules, ['field' => 'usuario_mensaje.valor1', 'data' => $_SESSION['win_perfil'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => date('Y-m-d H:i:s'), 'op' => 'ge']);
            if (!empty($readyMessages)) array_push($rules, ['field' => 'usuario_mensaje.usumensaje_id', 'data' => trim($readyMessages, ','), 'op' => 'ni']);

            /**
             * Se convierte el array de reglas en formato JSON para ser utilizado en la consulta.
             */
            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            /**
             * Se obtienen los mensajes masivos del usuario de acuerdo a los filtros establecidos.
             */
            $massiveMessages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usufrom.nombres', 'usuario_mensaje.fecha_crea', 'desc', 0, 10000, $filter, true);
            $massiveMessages = json_decode($massiveMessages, true);

            $readCount = 0;

            /**
             * Se combinan los mensajes y los mensajes masivos en un solo array.
             */
            $allMessages = array_merge($messages['data'], $massiveMessages['data']);

            $allPopups = [];

            /**
             * Inicializa una variable para almacenar los IDs de los mensajes a actualizar.
             */
            $updateMessagesId = '';


            foreach ($allMessages as $key => $value) {
                if ($value['usuario_mensaje.fecha_expiracion'] > date('Y-m-d H:i:s') || empty($value['usuario_mensaje.fecha_expiracion'])) {
                    if ($value['usuario_mensaje.usuto_id'] == -1) {

                        /**
                         * Este código se encarga de gestionar los mensajes de usuario en una base de datos.
                         * Se verifica si existen mensajes relacionados y, de no ser así, se procede a insertar un nuevo mensaje.
                         */

                        // Inicializa un array para las reglas de filtrado
                        $rules = [];
                        // Agrega reglas de filtrado para la consulta de los mensajes
                        array_push($rules, array("field" => "usuario_mensaje.parent_id", "data" => $value['usuario_mensaje.usumensaje_id'], "op" => "eq"));
                        array_push($rules, array("field" => "usuario_mensaje.usuto_id", "data" => $_SESSION['usuario2'], "op" => "eq"));

                        // Crea el filtro en formato json
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json2 = json_encode($filtro);

                        // Crea una instancia de UsuarioMensaje
                        $UsuarioMensaje2 = new UsuarioMensaje();
                        // Obtiene los mensajes relacionados desde la base de datos
                        $usuarios = $UsuarioMensaje2->getUsuarioMensajesCustom("usuario_mensaje.*,usufrom.*,usuto.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                        // Decodifica el json obtenido
                        $usuarios = json_decode($usuarios);
                        // Verifica si no hay mensajes relacionados
                        if (oldCount($usuarios->data) == 0) {
                            // Establece los valores del nuevo mensaje
                            $UsuarioMensaje->setUsufromId($value['usuario_mensaje.usufrom_id']);
                            $UsuarioMensaje->setUsutoId($_SESSION['usuario2']);
                            $UsuarioMensaje->setIsRead(0);
                            $UsuarioMensaje->setMsubject($value['usuario_mensaje.msubject']);
                            $UsuarioMensaje->setBody($value['usuario_mensaje.body']);
                            $UsuarioMensaje->setParentId($value['usuario_mensaje.usumensaje_id']);
                            $UsuarioMensaje->setUsucreaId($_SESSION['usuario2']);
                            $UsuarioMensaje->setUsumodifId(0);
                            $UsuarioMensaje->setTipo($value['usuario_mensaje.tipo']);
                            $UsuarioMensaje->setExternoId(0);
                            $UsuarioMensaje->setProveedorId(0);
                            $UsuarioMensaje->setPaisId($value['usuario_mensaje.pais_id']);
                            $UsuarioMensaje->setFechaExpiracion($value['usuario_mensaje.fecha_expiracion']);
                            $UsuarioMensaje->setValor1('');
                            $UsuarioMensaje->setValor2('');
                            $UsuarioMensaje->setValor3('');

                            // Crea una instancia del DAO para gestionar la base de datos
                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                            // Inserta el nuevo mensaje en la base de datos
                            $id = $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                            // Commitea la transacción
                            $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                            // Actualiza el id del mensaje insertado
                            $value['usuario_mensaje.usumensaje_id'] = $id;
                            // Crea un array con los datos del mensaje
                            $data = [];
                            $data['Subject'] = $value['usuario_mensaje.msubject'];
                            $data['Content'] = $value['usuario_mensaje.body'];

                            // Almacena el id del mensaje insertado en una cadena
                            $updateMessagesId .= $value['usuario_mensaje.usumensaje_id'] . ',';
                            $allPopups = [];

                            // Agrega los datos del mensaje a un popup
                            array_push($allPopups, $data);
                        }


                    }
                    // Verifica si el mensaje no ha sido leído y es del tipo 'MENSAJE'
                    if (($value['usuario_mensaje.is_read'] == '0' || $value['usuario_mensaje.is_read'] == false) && $value['usuario_mensaje.tipo'] === 'MENSAJE') $readCount++;

                    // Verifica si el tipo de mensaje es 'POPUP' y no ha sido leído
                    if ($value['usuario_mensaje.tipo'] === 'POPUP' && ($value['usuario_mensaje.is_read'] == '0' && $value['usuario_mensaje.is_read'] == false)) {
                        $data = [];
                        // Asigna el asunto del mensaje
                        $data['Subject'] = $value['usuario_mensaje.msubject'];
                        // Asigna el contenido del mensaje
                        $data['Content'] = $value['usuario_mensaje.body'];

                        // Agrega el ID del mensaje a la cadena de IDs a actualizar
                        $updateMessagesId .= $value['usuario_mensaje.usumensaje_id'] . ',';

                        // Añade el popup a la lista de popups
                        array_push($allPopups, $data);
                    }
                }

            }
            /**
             * Verifica si hay IDs de mensajes para actualizar.
             * Si $updateMessagesId no está vacío, se procede a actualizar el estado
             * de lectura de los mensajes correspondientes en la base de datos.
             */
            if (!empty($updateMessagesId)) {
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->updateReadForID(trim($updateMessagesId, ','));
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
            }
        }


        if (in_array($_SESSION['win_perfil'], ['PUNTOVENTA', 'CONCESIONARIO', 'CONCESIONARIO2'])) {

            // Se crea una instancia de la clase UsuarioMensaje
            $UsuarioMensaje = new UsuarioMensaje();
            // Se inicializa un arreglo para las reglas de filtro
            $rules = [];

            array_push($rules, ['field' => 'usuario_mensaje.is_read', 'data' => '0', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => $_SESSION['usuario2'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.proveedor_id', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.pais_id', 'data' => $_SESSION['pais_id'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => "'MENSAJE', 'POPUP'", 'op' => 'in']);
            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => date('Y-m-d H:i:s'), 'op' => 'ge']);

            // Se convierte el arreglo de reglas a formato JSON
            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            // Se obtienen los mensajes según las reglas definidas
            $messages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usufrom.nombres', 'usuario_mensaje.fecha_crea', 'desc', 0, 10000, $filter, true);
            $messages = json_decode($messages, true);

            // Se inicializa una variable para almacenar los mensajes listos
            $readyMessages = '';

            // Se itera sobre los mensajes obtenidos
            foreach ($messages['data'] as $key => $value) {
                // Se verifica si el mensaje tiene un ID de padre
                if (!empty($value['usuario_mensaje.parent_id'])) {
                    // Si el ID de padre no está ya en el string de mensajes listos, se añade
                    $readyMessages .= strpos($readyMessages, $value['usuario_mensaje.parent_id']) === false ? $value['usuario_mensaje.parent_id'] . ',' : '';
                }
            }

            // Se reinicializa el arreglo de reglas
            $rules = [];

            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => -1, 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.is_read', 'data' => '0', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.proveedor_id', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.pais_id', 'data' => $_SESSION['pais_id'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => "'MENSAJE', 'POPUP'", 'op' => 'in']);
            array_push($rules, ['field' => 'usuario_mensaje.valor1', 'data' => $_SESSION['win_perfil'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => date('Y-m-d H:i:s'), 'op' => 'ge']);
            if (!empty($readyMessages)) array_push($rules, ['field' => 'usuario_mensaje.usumensaje_id', 'data' => trim($readyMessages, ','), 'op' => 'ni']);

            /**
             * Se genera un filtro en formato JSON para las reglas especificadas.
             * Se obtiene un conjunto de mensajes de usuario personalizados y se decodifica la respuesta en un arreglo.
             * Se cuentan los mensajes leídos y se fusionan con un arreglo de mensajes.
             *
             */

            // Convertir las reglas en un filtro JSON
            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            // Obtener los mensajes masivos de usuario
            $massiveMessages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usufrom.nombres', 'usuario_mensaje.fecha_crea', 'desc', 0, 10000, $filter, true);
            $massiveMessages = json_decode($massiveMessages, true);

            // Inicializar contador de mensajes leídos
            $readCount = 0;
            $readCount = $allMessages['count'][0]['.count'];

            // Fusionar mensajes originales con mensajes masivos
            $allMessages = array_merge($messages['data'], $massiveMessages['data']);

            //$allPopups = [];

            $updateMessagesId = '';

            // Contar nuevamente los mensajes leídos de la lista combinada
            $readCount = $allMessages['count'][0]['.count'];


        }

        /**
         * Inicializa la respuesta con valores predeterminados.
         *
         */
        $response['HasError'] = false;
        $response['AlertType'] = 'success';
        $response['AlertMessage'] = '';
        $response['ModelErrors'] = [];
        $response['PermissionsList'] = $all_permissions;
        if (isset($allPopups)) $response['Popups'] = $allPopups;
        if (isset($readCount)) $response['Notifications'] = $readCount;
    }

} else {

    /**
     * Establece la respuesta de error.
     */
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = 'f';
    $response["CodeError"] = 20000;

}
?>
