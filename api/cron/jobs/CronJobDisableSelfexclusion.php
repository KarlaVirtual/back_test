<?php

use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;



/**
 * Clase 'CronJobDisableSelfexclusion'
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
class CronJobDisableSelfexclusion
{


    public function __construct()
    {
    }

    public function execute()
    {


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
SELECT * from usuario_configuracion  WHERE tipo IN (35, 39) AND valor < '$fecha_actual' AND estado = 'A'
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


    }
}