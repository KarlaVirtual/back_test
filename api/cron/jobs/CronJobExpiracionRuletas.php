<?php

/**
 * CronJobExpiracionRuletas
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 25/02/2025
 */

use Backend\dto\BonoInterno;
use Backend\dto\UsuarioRuleta;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;

class CronJobExpiracionRuletas
{

    private $BonoInterno;
    private $transaccion;

    public function __construct()
    {
        $this->BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $this->transaccion = $BonoDetalleMySqlDAO->getTransaction();
    }

    public function execute()
    {
        try {

            $filename = __DIR__ . '/lastrunCronJobExpiracionRuletas';

            $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='EXPIRACIONRULETA'";

            $data = $this->BonoInterno->execQuery('', $sqlProcesoInterno2);
            $data = $data[0];
            $line = $data->{'proceso_interno2.fecha_ultima'};

            if ($line == '') return;

            $fecha = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));

            $filename .= str_replace(' ', '-', str_replace(':', '-', $fecha));

            if (file_exists($filename)) {
                $datefilename = date("Y-m-d H:i:s", filemtime($filename));
                if ($datefilename <= date("Y-m-d H:i:s", strtotime('-10 minute'))) unlink($filename);
                return;
            }

            file_put_contents($filename, 'RUN');

            $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fecha . "' WHERE  tipo='EXPIRACIONRULETA';";

            $data = $this->BonoInterno->execQuery($this->transaccion, $sqlProcesoInterno2);
            $this->transaccion->commit();

            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($this->transaccion);

            $data = $UsuarioRuletaMySqlDAO->getUsuarioRuletasExpiradas();

            foreach ($data as $ruleta){
                $UsuarioRuleta = new UsuarioRuleta($ruleta['ur.usuruleta_id']);
                $UsuarioRuleta->setEstado("E");
                $result = $UsuarioRuletaMySqlDAO->update($UsuarioRuleta, " AND estado  in ('PP','PR','A','P') ");
                if ($result > 0) {
                    $this->transaccion->commit();
                }else {
                    continue;
                }
            }

            unlink($filename);
        } catch (\Throwable $th) {
            if ($_ENV['debug']) {
                print_r($th->getMessage());
            }
            throw $th;
        }
    }
}
