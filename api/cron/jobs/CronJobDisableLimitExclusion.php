<?php

use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;




/**
 * Clase 'CronJobDisableLimitExclusion'
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
class CronJobDisableLimitExclusion
{


    public function __construct()
    {
    }

    public function execute()
    {


        $filename = __DIR__ . '/lastrunCronDisableLimitExclusion';
        $datefilename = date("Y-m-d H:i:s", filemtime($filename));

        if ($datefilename <= date("Y-m-d H:i:s", strtotime('-6 hour'))) {
            unlink($filename);
        }

        if (file_exists($filename)) {
            throw new Exception("There is a process currently running", "1");
            exit();
        }

        file_put_contents($filename, 'RUN');

        $sqlData = "
select usuario_configuracion.usuconfig_id from usuario_configuracion
    INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)
    where 1=1
    AND clasificador.abreviado in ('LIMITEDEPOSITOSIMPLE','LIMAPUDERPOTIVASIMPLE','LIMAPUCASINOSIMPLE','LIMAPUCASINOVIVOSIMPLE','LIMAPUCASINOSIMPLEINT','LIMAPUCASINOVIVOSIMPLEINT')
    AND usuario_configuracion.estado = 'A'
    AND usuario_configuracion.fecha_fin < now();
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


    }

}