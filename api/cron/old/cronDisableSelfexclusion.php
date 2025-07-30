<?php

use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');
ini_set("display_errors", "off");

for($i=0;$i<10;$i++) {
    $filename = __DIR__ . '/lastrunCronDisableSelfexclusion';
    $datefilename = date("Y-m-d H:i:s", filemtime($filename));

    if ($datefilename <= date("Y-m-d H:i:s", strtotime('-6 hour'))) {
        unlink($filename);
    }

    if (file_exists($filename)) {
        throw new Exception("There is a process currently running", "1");
        exit();
    }

    file_put_contents($filename, 'RUN');

    $fecha_actual = date("Y-m-d H:i:s");


    $sqlData = "
SELECT * from usuario_configuracion  
             INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)

         WHERE 
     clasificador.abreviado in (
        'EXCTIMEOUT',
'EXCTIME',
'EXCTOTAL',
'EXCPRODUCT',
'EXCCASINOCATEGORY',
'EXCCASINOSUBCATEGORY',
'EXCCASINOGAME'



    ) AND usuario_configuracion.valor < '$fecha_actual' AND usuario_configuracion.estado = 'A'
";

    $BonoInterno = new BonoInterno();
    $data = $BonoInterno->execQuery('', $sqlData);

    $cont = 0;
    foreach ($data as $datanum) {

        $sql = "UPDATE usuario_configuracion SET estado = 'I'
            WHERE usuconfig_id = '" . $datanum->{'usuario_configuracion.usuconfig_id'} . "'
    ";
        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $transaccion = $BonoInternoMySqlDAO->getTransaction();
        $data = $BonoInterno->execQuery($transaccion, $sql);
        $transaccion->commit();
    }

    unlink($filename);
    print_r('PROCCESS OK');

    sleep(3);

}