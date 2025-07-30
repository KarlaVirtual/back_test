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
use Backend\cron\ResumenesCron;
use Backend\dto\Automation;
use Backend\dto\BonoInterno;
use Backend\mysql\BonoDetalleMySqlDAO;



require(__DIR__.'/../vendor/autoload.php');

ini_set('memory_limit', '-1');


try {

    $ObjAutomation = array(
        //"amount"=> 3
    );

    $Automation = new Automation();
    $Automation->CheckAutomation(json_decode(json_encode($ObjAutomation)),"forever",0,"");

}catch (Exception $e){
print_r($e);

}

