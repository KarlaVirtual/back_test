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
use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;



require(__DIR__.'/../vendor/autoload.php');

function read_and_delete_first_line($filename) {
    $file = file($filename);
    $output = $file[0];
    unset($file[0]);
    file_put_contents($filename, $file);
    return $output;
}
$hour = date('H');
print_r($hour);

try{



    $line = fgets(fopen(__DIR__.'/processlist', 'r'));

    if($line != ''){
        print_r('Ejecucion: '.$line);
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $line . "' '#virtualsoft-cron-error-urg' > /dev/null & ");
        read_and_delete_first_line(__DIR__.'/processlist');
        exec($line);

    }
}catch (Exception $e){

}