<?php
/** 
* Clase 'paymentCancel'
* 
* Esta clase provee funciones para cancelación de un pago
* 
* Ejemplo de uso: 
* $paymentCancel = new paymentCancel();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class paymentCancel extends paysafecard_base
{             

    /**
     * Método constructor
     *
     * @param String $lang lang
     * 
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($lang=false)
    {
        parent::loadConfig('payment');
        if($lang)
        {
            $this->setLanguage($lang);
        }
        $this->_customerInfo = $this->registry->language->get('payment_cancelled');          
    }

}