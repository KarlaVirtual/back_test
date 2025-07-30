<?php
use Backend\sql\Transaction;
use Backend\dto\BonoInterno;
use Backend\dto\ReferidoInvitacion;
use Backend\mysql\ReferidoInvitacionMySqlDAO;





/**
 * Clase 'CronJobInvitacionesReferidos'
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
class CronJobInvitacionesReferidos
{


    public function __construct()
    {
    }

    public function execute()
    {


        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();

//Conociendo información de la última ejecución del CRON
        $sql = "SELECT * FROM proceso_interno2 WHERE tipo='REFERIDO_INVITACION'";
        $ProcesoInterno = $BonoInterno->execQuery($Transaction, $sql);
        $ultimaEjecucion = $ProcesoInterno[0]->{'proceso_interno2.fecha_ultima'};

        if (empty($ultimaEjecucion)) {
            exit();
        }

        $fechaL1 = date('Y-m-d H:i:00', strtotime($ultimaEjecucion . '+1 minute'));
        $fechaL2 = date('Y-m-d H:i:59', strtotime($ultimaEjecucion . '+2 minute'));
        if ($fechaL2 >= date('Y-m-d H:i:00')) {
            unlink($filename);
            exit();
        }

        $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='REFERIDO_INVITACION'";
        $data = $BonoInterno->execQuery($Transaction, $sqlProcesoInterno2);
        $Transaction->commit();

        print_r(__DIR__ . PHP_EOL);
        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "Inicia cronInvitacionesReferidos: " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();

        $sql = "select referido_invitacion.refinvitacion_id from usuario as referido inner join usuario_otrainfo on (referido.usuario_id = usuario_otrainfo.usuario_id) inner join referido_invitacion on (referido.login = referido_invitacion.referido_email) inner join usuario as referente on (usuario_otrainfo.usuid_referente = referente.usuario_id) where referido.fecha_crea between '" . $fechaL1 . "' and '" . $fechaL2 . "' and (referido_invitacion.referido_exitoso is null or referido_invitacion.referido_exitoso = '')";
        $successedConvites = $BonoInterno->execQuery($Transaction, $sql);

        $ReferidoInvitacionMySqlDAO = new ReferidoInvitacionMySqlDAO($Transaction);
        foreach ($successedConvites as $successedConvite) {
            $ReferidoInvitacion = new ReferidoInvitacion($successedConvite->{'referido_invitacion.refinvitacion_id'});
            $ReferidoInvitacion->setReferidoExitoso(1);

            $ReferidoInvitacionMySqlDAO->update($ReferidoInvitacion);
        }

        $Transaction->commit();


    }
}