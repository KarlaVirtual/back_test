<?php

use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\SubproveedorMandantePaisMySqlDAO;

/**
 * PartnersProviders/UpdatePartnersProviders
 *
 * Actualización del orden de un SubproveedorMandantePais.
 *
 * Este recurso permite actualizar el valor de orden de un registro en la entidad SubproveedorMandantePais.
 * Se utiliza un identificador para localizar el registro y se asigna un nuevo valor de orden, el cual se guarda en la base de datos.
 *
 * @param int $params ->Id : Identificador del SubproveedorMandantePais a actualizar.
 * @param int $params ->Order : Nuevo valor de orden que se asignará al registro.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si ocurrió un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada.
 *  - *AlertMessage* (string): Mensaje de alerta en caso de error.
 *  - *ModelErrors* (array): Lista de errores de validación.
 *
 *
 * @throws Exception No
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

// Asignar el ID y el orden de los parámetros

/* Crea una instancia de SubproveedorMandantePais y establece su orden. */
$Id = $params->Id;
$Order = $params->Order;

// Crear una nueva instancia de SubproveedorMandantePais con el ID proporcionado
$SubproveedorMandantePais = new SubproveedorMandantePais($Id);

// Establecer el orden en el objeto, asignando 0 si el orden no está definido
$SubproveedorMandantePais->setOrden($Order ?: 0);

// Crear una nueva instancia del DAO para manejar la base de datos

/* Actualiza un subproveedor en MySQL y confirma la transacción correspondiente. */
$SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO();
// Actualizar el subproveedor mandante en la base de datos
$SubproveedorMandantePaisMySqlDAO->update($SubproveedorMandantePais);

// Confirmar la transacción en la base de datos
$SubproveedorMandantePaisMySqlDAO->getTransaction()->commit();

// Preparar la respuesta con estado de error y mensajes

/* Código que inicializa una respuesta sin errores y con mensaje de éxito. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
?>