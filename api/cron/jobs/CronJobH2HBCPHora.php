<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 17.10.24
 *
 */




/**
 * Clase 'CronJobH2HBCPHora'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobH2HBCPHora
{


    public function __construct()
    {
    }

    public function execute()
    {


        exec("php -f " . __DIR__ . "/../../api/integrations/payout/h2hbcp/api/index.php" . " > /dev/null &");


    }
}