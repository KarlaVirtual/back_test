<?php
/**
* Constantes de configuración global para la api de pagos
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/

//Url
$_['ApiWsdlSandbox']        = 'https://soatest.paysafecard.com/psc/services/PscService?wsdl';
$_['PaymentPanelSandbox']   = 'https://customer.test.at.paysafecard.com/psccustomer/GetCustomerPanelServlet';
$_['ApiWsdlProductive']     = 'https://soa.paysafecard.com/psc/services/PscService?wsdl';
$_['PaymentPanelProductive'] = 'https://customer.cc.at.paysafecard.com/psccustomer/GetCustomerPanelServlet';

//File
$_['LogFile'] = 'ppayout_log.txt';

//Setting
$_['DebugStatus'] = true;
$_['Language'] = 'de';
$_['Mode'] = 'test';

//Access
$_['Merchant'] = array('username' => '', 'password' => '');
