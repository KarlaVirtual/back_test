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
$_['LogFile'] = 'payment_log.txt';

//Setting
$_['DebugStatus'] = true;
$_['Language'] = 'en';
$_['Mode2'] = 'test';

//Access
$_['Merchant2'] = array('username' => 'psc_i_tainment_europe_ltd_test', 'password' => 'Akn28934mkwmkskjdfg');
$_['Mode'] = 'prod';

//Access
//psc_gamblingmaltaapco
//psc_I-TAINMENT_EURO_MAN9310721657
$_['Merchant'] = array('username' => 'psc_I-TAINMENT_EURO_MAN9310721657', 'password' => 'XxgMfXSUgTE2Njt');
