<?php

use Backend\dto\Menu;
use Backend\dto\Submenu;
use Backend\mysql\MenuMySqlDAO;
use Backend\mysql\SubmenuMySqlDAO;

/**
 * Crea un menú y un submenú en la base de datos.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param string $params->Description Descripción del menú.
 * @param string $params->FatherMenu ID del menú padre (opcional).
 * @param int $params->Order Orden del menú.
 * @param string $params->Url URL del menú.
 * @param string $params->Icon Icono del menú (opcional).
 * 
 * @return array $response Respuesta JSON con los siguientes valores:
 *                         - HasError: Indica si hubo un error (true/false).
 *                         - AlertType: Tipo de alerta (success/error).
 *                         - AlertMessage: Mensaje de alerta.
 *                         - ModelErrors: Lista de errores del modelo (puede ser vacía).
 */

 
$menu_principal = 0;
if (!$_SESSION['logueado']) {
    $response = [];
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Not authenticated";
} else {
    $Description = $params->Description;
    // Se obtiene el menú padre o se inicializa en 0 si no existe
    $FatherMenu = $params->FatherMenu ?: 0;
    // Se obtiene el orden del menú
    $Order = $params->Order;
    // Se obtiene la URL del menú
    $Url = $params->Url;
    // Se obtiene el icono del menú
    $Icon = $params->Icon;

    // Se intenta crear un nuevo objeto Menu
    try {
        $Menu = new Menu($FatherMenu);
    } catch (Exception $ex) {
        if ($ex->getCode() == 109) {
            if (empty($Description) || empty($Url)) throw new Exception('Error en los parametros', 3002);
            $Menu = new Menu();
            $Menu->descripcion = $Description; // Se asigna la descripción
            $Menu->pagina = $Url; // Se asigna la URL
            $Menu->orden = $Order ?: 0; // Se asigna el orden o se establece en 0
            $Menu->version = 3; // Se asigna la versión
            $Menu->icon = $Icon ?: 'icon-players'; // Se asigna el icono o se usa el predeterminado

            // Se instancia el DAO para la base de datos
            $MenuMySqlDAO = new MenuMySqlDAO();
            $Menu->menuId = $MenuMySqlDAO->insert($Menu);
            $MenuMySqlDAO->getTransaction()->commit();
            $menu_principal = 1;
        } else throw $ex;
    }

    // Se verifica que la versión del menú sea 3
    if($Menu->version != 3) throw new Exception('No existe el menu', 3001);

    // Se crea un nuevo objeto Submenu
    $Submenu = new Submenu();
    $Submenu->setDescripcion($Description ?: ''); // Se asigna la descripción
    $Submenu->setPagina($Url ?: ''); // Se asigna la URL
    $Submenu->setMenuId($Menu->menuId); // Se asigna el ID del menú asociado
    $Submenu->setOrden($Order ?: '0'); // Se asigna el orden o '0' por defecto
    $Submenu->setVersion(3); // Se establece la versión
    $Submenu->setParent('0'); // Se establece el padre como 0
    $Submenu->setMenuPrincipal($menu_principal); // Se establece si es menú principal

    // Se instancia el DAO para la base de datos de submenús
    $SubmenuMySqlDAO = new SubmenuMySqlDAO();
    $SubmenuMySqlDAO->insert($Submenu);

    // Se confirma la transacción
    $SubmenuMySqlDAO->getTransaction()->commit();

    // Se prepara la respuesta
    $response = [];
    $response["HasError"] = false; // Se indica que no hay errores
    $response["AlertType"] = 'success'; // Se establece el tipo de alerta
    $response["AlertMessage"] = ''; // Se establece el mensaje de alerta
    $response["ModelErrors"] = []; // Se inicializa el array de errores del modelo
}
?>
