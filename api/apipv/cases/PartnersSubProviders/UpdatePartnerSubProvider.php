<?php

use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\SubproveedorMandantePaisMySqlDAO;

/**
 * PartnersSubProviders/UpdatePartnerSubProvider
 *
 * Actualización de Subproveedor Mandante País
 *
 * Este recurso permite actualizar la información de un subproveedor mandante país, incluyendo su estado, detalles,
 * límites y configuración adicional como sistema de bonos y visibilidad para usuarios de prueba.
 *
 * @param int $Id : Identificador único del subproveedor mandante país.
 * @param string $Provider : Nombre del proveedor asociado.
 * @param bool $IsVerified : Indica si el subproveedor está verificado.
 * @param bool $filterCountry : Indica si se debe aplicar filtro por país.
 * @param string $IsActivate : Estado del subproveedor ("A" para activo, "I" para inactivo).
 * @param int $Maximum : Límite máximo permitido.
 * @param int $Minimum : Límite mínimo permitido.
 * @param string $Note : Nota o explicación de la actualización.
 * @param string $Detail : Detalle adicional sobre el subproveedor.
 * @param int $Order : Posición del subproveedor en el orden de prioridad.
 * @param string $Image : URL de la imagen representativa del subproveedor.
 * @param bool $BonusSystem : Indica si el proveedor tiene sistema de bonos (true/false).
 * @param bool $UsuariosPrueba : Indica si el proveedor permite acceso a usuarios de prueba (true/false).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generado.
 *  - *AlertMessage* (string): Mensaje de alerta generado.
 *  - *ModelErrors* (array): Lista de errores si la operación falla.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception no
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables para su posterior uso. */
$Id = $params->Id;
$Provider = $params->Provider;
$IsVerified = $params->isVerify;
$filterCountry = $params->filterCountry;
$IsActivate = $params->State;
$Maximum = $params->Maximum;

/* asigna variables a parámetros desde un objeto llamado $params. */
$Minimum = $params->Minimum;
$Note = $params->Note;
$Detail = $params->Detail;
$Order = $params->Order;
$Image = $params->Image;
$BonusSystem = $params->BonusSystem; // Descripcion de la variable:  la variable sirve para capturar si un proveedor tiene bonusSystem esto sera un booleano

/* verifica si un proveedor tiene bonusSystem y asigna "S" o "N". */
$UsuariosPrueba = $params->TestUsers; // Descripcion de la variable: la variable sirve para capturar si un proveedor puede mostrar sus juegos a usuarios de prueba esto sera un booleano


/* este condicional permite validar y asignar a la variable un valor en caso que sea true significara que el proveedor tiene bonusSystem y se le asignara S* en caso que no tenga bonusSystem se le asigna la letra N*/

if ($BonusSystem == true) {
    $BonusSystem = "S";
} else {
    /* asigna "N" a $BonusSystem si no se cumple una condición anterior. */

    $BonusSystem = "N";
}

/* Asigna "S" o "N" a $UsuariosPrueba según su valor inicial y crea un objeto. */
if ($UsuariosPrueba == true) {
    $UsuariosPrueba = "S";
} else {
    $UsuariosPrueba = "N";
}


$SubproveedorMandantePais = new SubproveedorMandantePais($Id);


/* establece propiedades de un objeto relacionado con subproveedores. */
$SubproveedorMandantePais->setEstado($IsActivate);
$SubproveedorMandantePais->setDetalle($Detail);
$SubproveedorMandantePais->setMin($Minimum);
$SubproveedorMandantePais->setMax($Maximum);
$SubproveedorMandantePais->setVerifica($IsVerified);
$SubproveedorMandantePais->setFiltroPais($filterCountry);

/* gestiona la configuración de un subproveedor, incluyendo imagen y sistema de bonus. */
$SubproveedorMandantePais->setImage($Image);
$SubproveedorMandantePais->setBonusSystem($BonusSystem); // proposito de la funcion: la funcion permite activar o no el bonusSystem
$SubproveedorMandantePais->setUsuariosPrueba($UsuariosPrueba); // proposito de la funcion: la funcion permite activar o no a usuarios de prueba para ver los juegos del subproveedor

$SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO();
$transaction = $SubproveedorMandantePaisMySqlDAO->getTransaction();

/* Actualiza datos en la base de datos y confirma la transacción sin errores. */
$SubproveedorMandantePaisMySqlDAO->update($SubproveedorMandantePais);
$SubproveedorMandantePaisMySqlDAO->getTransaction()->commit();

$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';

/* Inicializa el array 'ModelErrors' en la respuesta con un array vacío. */
$response['ModelErrors'] = [];
