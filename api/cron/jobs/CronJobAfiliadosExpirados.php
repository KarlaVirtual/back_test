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
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\sql\ConnectionProperty;
use Backend\sql\Transaction;



/**
 * Clase 'CronJobAfiliadosExpirados'
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
class CronJobAfiliadosExpirados
{


    public function __construct()
    {
    }

    public function execute()
    {
        /* asigna valores a variables según la conexión y parámetros existentes. */
        $_ENV["enabledConnectionGlobal"] = 1;

        $filename = __DIR__ . '/lastrunCronJobAfiiadosExpirados';

        $datefilename = date("Y-m-d H:i:s", filemtime($filename));

        if ($datefilename <= date("Y-m-d H:i:s", strtotime('-0.5 days'))) {
            unlink($filename);
        }

        if (file_exists($filename)) {
            throw new Exception("There is a process currently running", "1");
            exit();
        }
        file_put_contents($filename, 'RUN');


        $message = "*CRON: (cronBonosExpirados) * " . " - Fecha: " . date("Y-m-d H:i:s");
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }



        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $sqlInsert = "
select usuario.usuario_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
where registro.afiliador_id != 0
  and usuario.mandante = 8
  and DATEDIFF(NOW(), usuario.fecha_crea) > 365
limit 10000;
";


        $datosBonosAExpirar = $BonoInterno->execQuery($transaccion, $sqlInsert);


        foreach ($datosBonosAExpirar as $datanum) {
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $BonoInterno->execQuery($transaccion, "
UPDATE registro
set afiliadorantiguo_id=afiliador_id,
    bannerantiguo_id=banner_id,
    linkantiguo_id=link_id,
    afiliador_id=0,
    banner_id=0,
    link_id=0,
    fecha_casino=now()
WHERE usuario_id='" . $datanum->{'usuario.usuario_id'} . "'; ");
            $transaccion->commit();

        }


        print_r('PROCCESS OK');
        unlink($filename);


    }
}