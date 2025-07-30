<?php
/**
 * PartnersSubProviders/GetTypeSubProviders.php
 *
 * Obtener listado de los tipos de Subproveedores
 *
 * Permite obtener un listado de tipos en los que se clasifican los subproveedores
 *
 * @param no
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna un array vacío.
 *  - *data* (array): Contiene la lista de los tipos de subproveedores que se manejan dentro de la plataforma con su id y nombre
 *
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
$response['HasError'] = false;
$response['AlertType'] = 'Success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = [
    ['id' => '1', 'value' => 'Casino'],
    ['id' => '2', 'value' => 'Live Casino'],
    ['id' => '3', 'value' => 'Depositos'],
    ['id' => '4', 'value' => 'Apuesta Virtual'],
    ['id' => '5', 'value' => 'Pagos'],
    ['id' => '6', 'value' => 'VERIFICACION'],
    ['id' => '7', 'value' => 'CRM'],
    ['id' => '8', 'value' => 'FIRMA'],
    ['id' => '9', 'value' => 'Mensajeria'],
    ['id' => '10', 'value' => 'Deportiva']
];
?>