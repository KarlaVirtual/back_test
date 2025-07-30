<?php

use Backend\dto\TransjuegoLog;
use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\Clasificador;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;



/**
 * Clase 'CronJobLimitInt'
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
class CronJobLimitInt
{


    public function __construct()
    {
    }

    public function execute()
    {


        $filename = __DIR__ . '/lastrunCronLimitInt';
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
select usuario_configuracion.usuconfig_id,usuario_configuracion.usuario_id,clasificador.abreviado,usuario_configuracion.fecha_crea,usuario_configuracion.fecha_fin from usuario_configuracion
    INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)
    where 1=1
    AND clasificador.abreviado in ('LIMAPUCASINOSIMPLE','LIMAPUCASINOVIVOSIMPLE','LIMAPUVIRTUALESSIMPLE')
    AND usuario_configuracion.estado = 'A'
";

        $BonoInterno = new BonoInterno();
        $data = $BonoInterno->execQuery('', $sqlData);

        foreach ($data as $value) {
            if ($value->{'clasificador.abreviado'} == 'LIMAPUCASINOSIMPLE') {
                $Clasificador = new Clasificador('', 'LIMAPUCASINOSIMPLE');
                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($value->{'usuario_configuracion.usuario_id'}, 'A', $Clasificador->getClasificadorId());
                } catch (Exception $e) {

                }
                if (!empty($UsuarioConfiguracion->valor)) {
                    $Usuario = new Usuario($value->{'usuario_configuracion.usuario_id'});
                    $UsuarioMandante = new UsuarioMandante('', $Usuario->getUsuarioId(), $Usuario->mandante);

                    $rules = [];
                    array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->usumandanteId, "op" => "eq"));
                    array_push($rules, array("field" => "transjuego_log.tipo", "data" => "DEBIT", "op" => "eq"));
                    array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $value->{"usuario_configuracion.fecha_crea"}, "op" => "ge"));
                    array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $value->{"usuario_configuracion.fecha_fin"}, "op" => "le"));
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => "CASINO", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $select = "SUM(transjuego_log.valor) as bets_amount";
                    $TransjuegoLog = new TransjuegoLog();
                    $data = $TransjuegoLog->getTransjuegoLogsCustom3($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, '');
                    $data = json_decode($data);
                    if ($data->data[0]->{'.bets_amount'} > $UsuarioConfiguracion->valor) {
                        try {
                            $Clasificador2 = new Clasificador('', 'LIMAPUCASINOSIMPLEINT');
                            $UsuarioConfiguracion2 = new UsuarioConfiguracion($value->{'usuario_configuracion.usuario_id'}, 'A', $Clasificador2->getClasificadorId());
                        } catch (Exception $e) {
                            if ($e->getCode() == 46) {
                                $UsuarioConfiguracion2 = new UsuarioConfiguracion();
                                $UsuarioConfiguracion2->usuarioId = $value->{'usuario_configuracion.usuario_id'};
                                $UsuarioConfiguracion2->tipo = $Clasificador2->getClasificadorId();
                                $UsuarioConfiguracion2->valor = $UsuarioConfiguracion->usuconfigId;
                                $UsuarioConfiguracion2->estado = 'A';
                                $UsuarioConfiguracion2->fechaFin = $UsuarioConfiguracion->fechaFin;
                                $UsuarioConfiguracion2->nota = 'Creado por cron de limite de casino';

                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
                                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                            }
                        }
                    }

                }

            } elseif ($value->{'clasificador.abreviado'} == 'LIMAPUCASINOVIVOSIMPLE') {
                $Clasificador = new Clasificador('', 'LIMAPUCASINOVIVOSIMPLE');
                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($value->{'usuario_configuracion.usuario_id'}, 'A', $Clasificador->getClasificadorId());
                } catch (Exception $e) {

                }
                if (!empty($UsuarioConfiguracion->valor)) {
                    $Usuario = new Usuario($value->{'usuario_configuracion.usuario_id'});
                    $UsuarioMandante = new UsuarioMandante('', $Usuario->getUsuarioId(), $Usuario->mandante);

                    $rules = [];
                    array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->usumandanteId, "op" => "eq"));
                    array_push($rules, array("field" => "transjuego_log.tipo", "data" => "DEBIT", "op" => "eq"));
                    array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $value->{"usuario_configuracion.fecha_crea"}, "op" => "ge"));
                    array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $value->{"usuario_configuracion.fecha_fin"}, "op" => "le"));
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => "LIVECASINO", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $select = "SUM(transjuego_log.valor) as bets_amount";
                    $TransjuegoLog = new TransjuegoLog();
                    $data = $TransjuegoLog->getTransjuegoLogsCustom3($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, '');
                    $data = json_decode($data);
                    if ($data->data[0]->{'.bets_amount'} > $UsuarioConfiguracion->valor) {
                        try {
                            $Clasificador2 = new Clasificador('', 'LIMAPUCASINOVIVOSIMPLEINT');
                            $UsuarioConfiguracion2 = new UsuarioConfiguracion($value->{'usuario_configuracion.usuario_id'}, 'A', $Clasificador2->getClasificadorId());
                        } catch (Exception $e) {
                            if ($e->getCode() == 46) {
                                $UsuarioConfiguracion2 = new UsuarioConfiguracion();
                                $UsuarioConfiguracion2->usuarioId = $value->{'usuario_configuracion.usuario_id'};
                                $UsuarioConfiguracion2->tipo = $Clasificador2->getClasificadorId();
                                $UsuarioConfiguracion2->valor = $UsuarioConfiguracion->usuconfigId;
                                $UsuarioConfiguracion2->estado = 'A';
                                $UsuarioConfiguracion2->fechaFin = $UsuarioConfiguracion->fechaFin;
                                $UsuarioConfiguracion2->nota = 'Creado por cron de limite de Casino en vivo';

                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
                                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                            }
                        }
                    }
                }
            } elseif ($value->{'clasificador.abreviado'} == 'LIMAPUVIRTUALESSIMPLE') {
                $Clasificador = new Clasificador('', 'LIMAPUVIRTUALESSIMPLE');
                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($value->{'usuario_configuracion.usuario_id'}, 'A', $Clasificador->getClasificadorId());
                } catch (Exception $e) {

                }
                if (!empty($UsuarioConfiguracion->valor)) {
                    $Usuario = new Usuario($value->{'usuario_configuracion.usuario_id'});
                    $UsuarioMandante = new UsuarioMandante('', $Usuario->getUsuarioId(), $Usuario->mandante);

                    $rules = [];
                    array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->usumandanteId, "op" => "eq"));
                    array_push($rules, array("field" => "transjuego_log.tipo", "data" => "DEBIT", "op" => "eq"));
                    array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $value->{"usuario_configuracion.fecha_crea"}, "op" => "ge"));
                    array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $value->{"usuario_configuracion.fecha_fin"}, "op" => "le"));
                    array_push($rules, array("field" => "subproveedor.tipo", "data" => "VIRTUAL", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $select = "SUM(transjuego_log.valor) as bets_amount";
                    $TransjuegoLog = new TransjuegoLog();
                    $data = $TransjuegoLog->getTransjuegoLogsCustom3($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, '');
                    $data = json_decode($data);
                    if ($data->data[0]->{'.bets_amount'} > $UsuarioConfiguracion->valor) {
                        try {
                            $Clasificador2 = new Clasificador('', 'LIMAPUVIRTUALESSIMPLEINT');
                            $UsuarioConfiguracion2 = new UsuarioConfiguracion($value->{'usuario_configuracion.usuario_id'}, 'A', $Clasificador2->getClasificadorId());
                        } catch (Exception $e) {
                            if ($e->getCode() == 46) {
                                $UsuarioConfiguracion2 = new UsuarioConfiguracion();
                                $UsuarioConfiguracion2->usuarioId = $value->{'usuario_configuracion.usuario_id'};
                                $UsuarioConfiguracion2->tipo = $Clasificador2->getClasificadorId();
                                $UsuarioConfiguracion2->valor = $UsuarioConfiguracion->usuconfigId;
                                $UsuarioConfiguracion2->estado = 'A';
                                $UsuarioConfiguracion2->fechaFin = $UsuarioConfiguracion->fechaFin;
                                $UsuarioConfiguracion2->nota = 'Creado por cron de limite de apuestas virtuales';

                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
                                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                            }
                        }
                    }
                }
            }

        }


        unlink($filename);


    }

}