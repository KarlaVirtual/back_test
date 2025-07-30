<?php

/**
 * Archivo de configuración para la integración de pagos con Paysafecard.
 *
 * Este archivo contiene las configuraciones necesarias para interactuar con
 * los servicios de Paysafecard, incluyendo URLs, credenciales de acceso,
 * configuraciones de depuración y otros parámetros relevantes.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_ Esta variable se utiliza para almacenar y manipular el valor de '_' en el contexto actual.
 */

//Url
$_['ApiWsdlSandbox'] = 'https://soatest.paysafecard.com/psc/services/PscService?wsdl';
$_['PaymentPanelSandbox'] = 'https://customer.test.at.paysafecard.com/psccustomer/GetCustomerPanelServlet';
$_['ApiWsdlProductive'] = 'https://soa.paysafecard.com/psc/services/PscService?wsdl';
$_['PaymentPanelProductive'] = 'https://customer.cc.at.paysafecard.com/psccustomer/GetCustomerPanelServlet';

//File
$_['LogFile'] = 'payment_log.txt';

//Setting
$_['DebugStatus'] = true;
$_['Language'] = 'en';
$_['Mode2'] = 'test';

//Access
$_['Merchant2'] = array('username' => 'psc_i_tainment_europe_ltd_test', 'password' => 'Akn28934mkwmkskjdfg');
$_['Mode'] = 'prod';

$_['Merchant'] = array('username' => 'psc_I-TAINMENT_EURO_MAN9310721657', 'password' => 'XxgMfXSUgTE2Njt');
