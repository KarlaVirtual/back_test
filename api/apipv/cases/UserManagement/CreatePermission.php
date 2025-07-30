<?php

use Backend\dto\Submenu;
use Backend\mysql\SubmenuMySqlDAO;

/**
 * Crea un nuevo permiso en el sistema.
 *
 * @param string $Url URL del permiso.
 * @param string $Description Descripción del permiso.
 * @param string|null $FatherMenu ID del menú padre.
 * @param string|null $SubMenu ID del submenú.
 * @return array $response Respuesta JSON con los siguientes valores:
 *                         - bool $response['HasError'] Indica si hubo un error (true/false).
 *                         - string $response['AlertType'] Tipo de alerta (success/error).
 *                         - string $response['AlertMessage'] Mensaje de alerta.
 *                         - array $response['ModelErrors'] Lista de errores del modelo (puede ser vacía).
 * @throws Exception Si no existe el menú seleccionado o ocurre un error en la base de datos.
 */

if (!$_SESSION['logueado']) {
    /*Respuesta anpe peticiones sin login previo*/
    $response = [];
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Not authenticated";
} else {

    $Url = $params->Url;
    $Description = $params->Description;
    $FatherMenu = $params->FatherMenu;
    $SubMenu = $params->SubMenu;

    try {
       /*Crea filtros y obtiene menús personalizados según los parámetros proporcionados.*/
        $Submenu = new Submenu();

        $rules = [];

        if (!empty($SubMenu)) {
            array_push($rules, ['field' => 'submenu.submenu_id', 'data' => $SubMenu, 'op' => 'eq']);
        } else {
            array_push($rules, ['field' => 'submenu.descripcion', 'data' => '', 'op' => 'eq']);
            array_push($rules, ['field' => 'submenu.pagina', 'data' => '', 'op' => 'eq']);
        }

        if (!empty($FatherMenu)) array_push($rules, ['field' => 'menu.menu_id', 'data' => $FatherMenu, 'op' => 'eq']);

        array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $data = $Submenu->getAllMenusCustom('submenu.*', 'submenu.submenu_id', 'asc', 0, 1, $filters, true);

        $data = json_decode($data);


        /*Verifica si el menú padre existe, si no, lanza una excepción y limpia `FatherMenu`.*/
        if (!oldCount($data->data) > 0) {
            $rules = [];

            array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
            array_push($rules, ['field' => 'submenu.submenu_id', 'data' => $FatherMenu, 'op' => 'eq']);

            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $data = $Submenu->getAllSubmenusCustom('submenu.*', 'submenu.submenu_id', 'asc', 0, 1, $filters, true);

            $data = json_decode($data);

            if (!oldCount($data->data) > 0) throw new Exception('No existe menu', 01);

            $FatherMenu = '';
        }

        /*Establece propiedades de `Submenu`, inserta en la base de datos y confirma la transacción.*/
        $Submenu->setDescripcion($Description);
        $Submenu->setPagina($Url);
        $Submenu->setMenuId($FatherMenu ?: 0);
        $Submenu->setOrden(0);
        $Submenu->setVersion(3);
        $Submenu->setParent(empty($SubMenu) ? $data->data[0]->{'submenu.submenu_id'} : $SubMenu);

        $SubmenuMySqlDAO = new SubmenuMySqlDAO();

        $SubmenuMySqlDAO->insert($Submenu);

        $SubmenuMySqlDAO->getTransaction()->commit();

        /*Formato de respuesta*/
        $response = [];
        $response["HasError"] = false;
        $response["AlertType"] = 'success';
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];

    } catch (Exception $ex) {
        /*Formato de respuesta erroneo*/
        header('Content-Type: text/HTML');
        die($ex);
        $response = [];
        $response["HasError"] = true;
        $response["AlertType"] = 'error';
        $response["AlertMessage"] = 'No existe el menu seleccionado';
        $response["ModelErrors"] = [];
    }
}
?>