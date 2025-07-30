<?php
/** 
* Clase 'payout'
* 
* Esta clase provee funciones para la realización de un pago
* 
* Ejemplo de uso: 
* $payout = new payout();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class payout extends paysafecard_base
{


    /**
     * Método constructor
     *
     * @param boolean $mode mode
     * 
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($mode=false)
    {
        parent::loadConfig('payout');
        if($mode)
        {
            $this->setMode($mode);
        }
    }

    /**
     * Método para realizar un nuevo pago
     *
     *
     * @param array $parameter parameter
     * 
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function newPayout($parameter)
    {
        $validate = new payoutValidate($this->registry);
        if(!isset($parameter['username']) OR !isset($parameter['password']))
        {
            $parameter['username'] = $this->registry->config->get('Merchant')['username'];
            $parameter['password'] = $this->registry->config->get('Merchant')['password'];
        }
        if(!$validate->validate($parameter))
        {
            $this->setError($this->registry->api->error);
            return false;
        }
        $parameter = $validate->checkPayoutParameter($parameter);
        if(!$parameter)
        {
            $this->setError($this->registry->api->error);
            return false;
        }

        //return
    }

}