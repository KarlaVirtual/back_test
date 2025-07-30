<?php

/**
 * Editar menú
 *
 * Este script permite editar las propiedades de un submenú en el sistema.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param int $params->Id Identificador del submenú.
 * @param int $params->FatherMenu Identificador del menú padre.
 * @param string $params->Url URL del submenú.
 * @param int $params->Order Orden del submenú.
 * @param string $params->Description Descripción del submenú.
 * @param int $params->level Nivel del submenú.
 * @param string $params->pagina Página asociada al submenú.
 *
 * @return array $response Respuesta en formato JSON con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta o error.
 * - ModelErrors (array): Lista de errores de validación.
 */

use Backend\dto\Submenu;
use Backend\mysql\SubmenuMySqlDAO;
use Backend\mysql\SubmenuMySqlExtDAO;


/* asigna valores de un objeto $params a variables individuales. */
$Id = $params->Id;
$FatherMenu = $params->FatherMenu;
$Url = $params->Url;
$Order = $params->Order;
$Description = $params->Description;
$level = $params->level;

/* inicializa un objeto Submenu y establece su descripción si no está vacía. */
$pagina = $params->pagina;


$submenu = new Submenu($Id);

if ($Description != "") {
    $submenu->setDescripcion($Description);
}


/* Condicionales que asignan valores a propiedades del objeto $submenu si no están vacíos. */
if ($url != "") {
    $submenu->setPagina($url);
}


if ($Order != "") {
    $submenu->setOrden($Order);
}


/* Asigna un ID al submenú si $FatherMenu no está vacío y crea un DAO. */
if ($FatherMenu != "") {
    $submenu->setMenuId($FatherMenu);
}


$SubmenuMysqlDao = new SubmenuMySqlDAO();

/* Se maneja una transacción para actualizar un submenu y confirmar los cambios. */
$Transaction = $SubmenuMysqlDao->getTransaction();
$SubmenuMysqlDao->update($submenu);
$SubmenuMysqlDao->getTransaction()->commit();


$response["HasError"] = false;

/* Código PHP que inicializa una respuesta con éxito, sin mensajes ni errores de modelo. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

