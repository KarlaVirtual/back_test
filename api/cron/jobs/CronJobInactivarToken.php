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
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\utils\BackgroundProcessVS;
use Backend\utils\SlackVS;
use Backend\websocket\WebsocketUsuario;


/**
 * Clase 'CronJobInactivarToken'
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
class CronJobInactivarToken
{

    private $SlackVS;
    private $BackgroundProcessVS;

    public function __construct()
    {
        $this->SlackVS = new SlackVS('virtualsoft-cron');
        $this->BackgroundProcessVS = new BackgroundProcessVS();

    }

    public function execute()
    {

        $filename = __DIR__ . '/lastrunCronJobInactivarToken';

        $BonoInterno = new BonoInterno();

        $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='DEPURACIONUTOKENGENERAL'";


        $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
        $data = $data[0];
        $line = $data->{'proceso_interno2.fecha_ultima'};

        if ($line == '') {
            unlink($filename);
            exit();
        }


        $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+20 seconds'));
        $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+20 seconds'));


        if ($fechaL2 >= date('Y-m-d H:i:00', strtotime('-30 seconds'))) {
            unlink($filename);
            exit();
        }

        if (file_exists($filename)) {

            $datefilename = date("Y-m-d H:i:s", filemtime($filename));

            if ($datefilename <= date("Y-m-d H:i:s", strtotime('-2 minute'))) {
                unlink($filename);
            }

            throw new Exception("There is a process currently running", "1");
            exit();
        }
        file_put_contents($filename, 'RUN');

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='DEPURACIONUTOKENGENERAL';";


        $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
        $transaccion->commit();


        $fechaSoloDia = $fechaL1;
        $fechaSoloDia2 = $fechaL2;

        $Clasificador = new Clasificador('', 'SESSIONINACTIVITYMIN');

        $MandanteDetalle = new MandanteDetalle();
        $rules = [];
        array_push($rules, array("field" => "mandante_detalle.tipo", "data" => "$Clasificador->clasificadorId", "op" => "eq"));
        array_push($rules, array("field" => "mandante_detalle.estado", "data" => "A", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $mandanteDetalles = $MandanteDetalle->getMandanteDetallesCustom("mandante_detalle.mandante,mandante_detalle.pais_id,mandante_detalle.valor as valor, clasificador.abreviado as abreviado ", "mandante_detalle.manddetalle_id", "asc", '0', '1000', $json2, true);

        $mandanteDetalles = json_decode($mandanteDetalles);

        $loyalty = array();
        foreach ($mandanteDetalles->data as $key => $value) {
            $minutes = $value->{'mandante_detalle.valor'};
            if ($minutes > 0 && $minutes != '' && $minutes != null) {


                $sql = "
select usuario_token.usutoken_id,usuario_token.usuario_id
from usuario_token inner join usuario_mandante on usuario_token.usuario_id = usuario_mandante.usumandante_id
where usuario_token.estado = 'A' and usuario_token.proveedor_id='0' and usuario_mandante.mandante='" . $value->{'mandante_detalle.mandante'} . "'  and usuario_mandante.pais_id='" . $value->{'mandante_detalle.pais_id'} . "' 
  and usuario_token.fecha_modif >= DATE_SUB(NOW(), INTERVAL 360 MINUTE) AND usuario_token.usuario_id != '0'
 and usuario_token.fecha_modif <= '" . date('Y-m-d H:i:s', strtotime("- {$minutes} minute")) . "'  ORDER BY usuario_token.usutoken_id DESC LIMIT 500
";

                $data2 = $BonoInterno->execQuery($transaccion, $sql);

                $data = array();
                $dataUsuarios = array();
                foreach ($data2 as $datum) {
                    array_push($data, $datum->{'usuario_token.usutoken_id'});
                    array_push($dataUsuarios, $datum->{'usuario_token.usuario_id'});

                }

                $cont2 = 0;
                $cont = 0;
                $contG = 0;

                $BonoInterno = new BonoInterno();
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                $dataC = array();


                $this->SlackVS->sendMessage("*CRON INICIO: (Inactivamos Tokens Usuario22) * " . oldCount($data) . " - Fecha: " . date("Y-m-d H:i:s"));

                foreach ($data as $key => $datum) {
                    if ($datum != '') {

                        if ($contG >= 0) {

                            array_push($dataC, $datum);

                            if ($cont == 10) {

                                $sql2 = "

UPDATE
usuario_token SET 
                   
estado='I'
                   
WHERE usuario_token.usutoken_id IN (" . implode(',', $dataC) . ")
";
                                $data = $BonoInterno->execQuery($transaccion, $sql2);


                                $transaccion->commit();

                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                                $cont = 0;
                                if (($contG % 1000) == 0) {
                                    usleep(500);
                                }
                                $dataC = array();

                            }
                            $cont++;

                        }

                        $contG++;
                    }

                }

                if ($cont > 0 && oldCount($dataC) > 0) {

                    $sql2 = "

UPDATE
usuario_token SET 
                   
estado='I'
                   
WHERE usuario_token.usutoken_id IN (" . implode(',', $dataC) . ")
";
                    //print_r($sql2);
                    $data = $BonoInterno->execQuery($transaccion, $sql2);


                    $transaccion->commit();

                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                    $cont = 0;
                    if (($contG % 100000) == 0) {
                        sleep(2);
                    }
                    $dataC = array();


                }

                $dataSend = array();
                $dataSend["logout"] = '1';
                $UsuarioMandante = new UsuarioMandante($dataUsuarios[$cont2]);

                $WebsocketUsuario = new WebsocketUsuario('', '');
                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend,true);
                $this->SlackVS->sendMessage("*LOGOUT * ".$dataUsuarios[$cont2] . $fechaSoloDia . " - Fecha: " . date("Y-m-d H:i:s"));

                $cont2=$cont2+1;
                $transaccion->commit();
            }
        }



        $Clasificador = new Clasificador('', 'SESSIONDURATIONMIN');

        $MandanteDetalle = new MandanteDetalle();
        $rules = [];
        array_push($rules, array("field" => "mandante_detalle.tipo", "data" => "$Clasificador->clasificadorId", "op" => "eq"));
        array_push($rules, array("field" => "mandante_detalle.estado", "data" => "A", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $mandanteDetalles = $MandanteDetalle->getMandanteDetallesCustom("mandante_detalle.mandante,mandante_detalle.pais_id,mandante_detalle.valor as valor, clasificador.abreviado as abreviado ", "mandante_detalle.manddetalle_id", "asc", '0', '1000', $json2, true);

        $mandanteDetalles = json_decode($mandanteDetalles);

        $loyalty = array();
        foreach ($mandanteDetalles->data as $key => $value) {
            $minutes = $value->{'mandante_detalle.valor'};
            if ($minutes > 0 && $minutes != '' && $minutes != null) {


                $sql = "
select usuario_token.usutoken_id
from usuario_token inner join usuario_mandante on usuario_token.usuario_id = usuario_mandante.usumandante_id
where usuario_token.estado = 'A' and usuario_token.proveedor_id='0' and usuario_mandante.mandante='" . $value->{'mandante_detalle.mandante'} . "'  and usuario_mandante.pais_id='" . $value->{'mandante_detalle.pais_id'} . "' 
    and usuario_token.fecha_modif >= DATE_SUB(NOW(), INTERVAL 360 MINUTE) AND usuario_token.usuario_id != '0'
and usuario_token.fecha_crea <= '" . date('Y-m-d H:i:s', strtotime("- {$minutes} minute")) . "'   ORDER BY usuario_token.usutoken_id DESC LIMIT 500
";

                $data2 = $BonoInterno->execQuery($transaccion, $sql);

                $data = array();
                $dataUsuarios = array();

                foreach ($data2 as $datum) {
                    array_push($data, $datum->{'usuario_token.usutoken_id'});
                    array_push($dataUsuarios, $datum->{'usuario_token.usuario_id'});
                }

                $cont = 0;
                $contG = 0;
                $cont2 = 0;

                $BonoInterno = new BonoInterno();
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                $dataC = array();


                $this->SlackVS->sendMessage("*CRON INICIO: (Inactivamos Tokens Usuario22) * " . oldCount($data) . " - Fecha: " . date("Y-m-d H:i:s"));

                foreach ($data as $key=>$datum) {
                    if ($datum != '') {

                        if ($contG >= 0) {

                            array_push($dataC, $datum);

                            if ($cont == 10) {

                                $sql2 = "

UPDATE
usuario_token SET 
                   
estado='I'
                   
WHERE usuario_token.usutoken_id IN (" . implode(',', $dataC) . ")
";
                                $data = $BonoInterno->execQuery($transaccion, $sql2);


                                $transaccion->commit();

                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                                $cont = 0;
                                if (($contG % 1000) == 0) {
                                    usleep(500);
                                }
                                $dataC = array();

                            }
                            $cont++;

                        }

                        $contG++;
                    }

                }

                if ($cont > 0 && oldCount($dataC) > 0) {

                    $sql2 = "

UPDATE
usuario_token SET 
                   
estado='I'
                   
WHERE usuario_token.usutoken_id IN (" . implode(',', $dataC) . ")
";
                    //print_r($sql2);
                    $data = $BonoInterno->execQuery($transaccion, $sql2);


                    $transaccion->commit();

                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                    $cont = 0;
                    if (($contG % 100000) == 0) {
                        sleep(2);
                    }
                    $dataC = array();


                }

                $dataSend = array();
                $dataSend["logout"] = 1;
                $UsuarioMandante = new UsuarioMandante($dataUsuarios[$cont2]);

                $WebsocketUsuario = new WebsocketUsuario('', '');
                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend,true);
                $this->SlackVS->sendMessage("*LOGOUT * ".$dataUsuarios[$cont2] . $fechaSoloDia . " - Fecha: " . date("Y-m-d H:i:s"));

                $cont2=$cont2+1;
                $transaccion->commit();
            }
        }



        $this->SlackVS->sendMessage("*CRON FIN: (Inactivamos Tokens Usuario22) * " . $fechaSoloDia . " - Fecha: " . date("Y-m-d H:i:s"));

        print_r('PROCCESS_OK');

        unlink($filename);

    }
}