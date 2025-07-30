<?php

/**
 * Configuración para la integración de pagos con Paysafecard.
 *
 * Este archivo contiene las configuraciones necesarias para interactuar con
 * los servicios de Paysafecard, incluyendo URLs, archivos de registro,
 * configuraciones de depuración, idioma, modo de operación y credenciales de acceso.
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

//Archivo
$_['LogFile'] = 'ppayout_log.txt';

//Configuracion
$_['DebugStatus'] = true;
$_['Language'] = 'de';
$_['Mode'] = 'test';

//Acceso
$_['Merchant'] = array('username' => '', 'password' => '');
