<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */






/**
 * Clase 'CronJobInterbankHora'
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
class CronJobInterbankHora
{


    public function __construct()
    {
    }

    public function execute()
    {

        exec("php -f " . __DIR__ . "/../../api/integrations/payout/interbank/api/index.php" . " > /dev/null &");


    }
}