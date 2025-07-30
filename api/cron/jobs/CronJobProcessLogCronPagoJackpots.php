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
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\JackpotDetalle;
use Backend\dto\JackpotInterno;
use Backend\dto\Proveedor;
use Backend\dto\Registro;
use Backend\dto\RuletaInterno;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoInfo;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioJackpot;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\JackpotInternoMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioJackpotMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Backend\utils\SlackVS;
use Backend\utils\BackgroundProcessVS;
use Backend\websocket\WebsocketUsuario;
use Backend\sql\ConnectionProperty;


/**
 * Clase 'CronJobRollover'
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
class CronJobProcessLogCronPagoJackpots
{

    private $SlackVS;
    private $BackgroundProcessVS;

    public function __construct()
    {
        $this->SlackVS = new SlackVS('log-cron');
        $this->BackgroundProcessVS = new BackgroundProcessVS();

    }

    public function execute()
    {
        global $argv;

        /* asigna valores a variables según la conexión y parámetros existentes. */
        $argv1 = $argv[3];
        $argv2 = $argv[4];
        $argv3 = $argv[5];

        //$redis = RedisConnectionTrait::getRedisInstance(true);
        $comandos = array();

        $datetie = date('s');


        $filename = __DIR__ . '/lastrun' . 'CronJobProcessLogCronPagoJackpots' . $argv1;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $BonoInterno = new BonoInterno();

        if (file_exists($filename)) {
            print_r('FILEEXITS');

            $datefilename = date("Y-m-d H:i:s", filemtime($filename));

            if ($datefilename <= date("Y-m-d H:i:s", strtotime('-10 minute'))) {
                unlink($filename);
            }

            return;
        }
        file_put_contents($filename, 'RUN');


        $debug = false;

        $BonoInterno = new BonoInterno();

        $wherePreProcess = '';
        $whereOFFSET = '';

        if ($argv1 != '') {
            $wherePreProcess .= " AND tipo='{$argv1}' ";
        }
        if ($argv2 != '') {
            $whereOFFSET .= " OFFSET {$argv2} ";
        }
        $sqlApuestasDeportivasUsuarioDiaCierre = "
       
       
            SELECT * FROM jackpot_interno
            where estado='A';

        ";


        // Control de procesos simultáneos
        $maxConcurrentProcesses = 50; // Máximo de 10 procesos a la vez

        if ($argv3 != '' && intval($argv3) > 0) {
            $maxConcurrentProcesses = intval($argv3); // Máximo de 10 procesos a la vez

        }

        $activeProcesses = [];
        $startTimes = [];

        $time = time();

        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);


        $cont = 0;
        foreach ($data as $datanum) {
            // Esperar si hay 10 procesos corriendo

            while (count($activeProcesses) >= $maxConcurrentProcesses) {
                foreach ($activeProcesses as $key => $pid) {
                    $res = pcntl_waitpid($pid, $status, WNOHANG);
                    if ($res > 0) {
                        // Proceso terminado normalmente
                        unset($activeProcesses[$pid]);
                        unset($startTimes[$pid]);
                    }
                }
                usleep(10000); // Pequeña pausa (0.1s) para no consumir CPU en el loop
            }
            // Crear un nuevo proceso hijo
            $pid = pcntl_fork();
            if ($pid == -1) {

            } elseif ($pid == 0) {
                $_ENV["enabledConnectionGlobal"] = 1;


                try {


                    $jackpot_id = $datanum->{'jackpot_interno.jackpot_id'};
                    $cantidad_apuesta = $datanum->{'jackpot_interno.cantidad_apuesta'};
                    $JackpotInterno = new JackpotInterno($jackpot_id);

                    //Obteniendo apuesta ganadora
                    $JackpotDetalle = new JackpotDetalle();

                    $winnerBet = null;
                    if ($JackpotInterno->reinicio == 0 && date('Y-m-d', strtotime($JackpotInterno->fechaFin)) == date('Y-m-d')) {
                        $winnerBet = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'FALLCRITERIA_LASTDAYWINNERBET')[0];
                    }
                    if (empty((array)$winnerBet)) {
                        $winnerBet = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'FALLCRITERIA_WINNERBET')[0];
                    }

                    $winnerBet = $winnerBet->valor;
                    if (!is_numeric(intval($winnerBet)) || $winnerBet <= 0) throw new Exception('Apuesta de caída configurada para el jackpot es inválida', 300043);

                    $sqlApuestasDeportivasUsuarioDiaCierre = "
       


select sum(cantidad)                           cantidad,
       sum(acumulado)                          acumulado,
       CASE
           WHEN maximoDeportivasFecha > maximoCasinoFecha OR maximoCasinoFecha IS NULL THEN CONCAT('S_', MAX(maximoDeportivas))
           ELSE CONCAT('C_', MAX(maximoCasino)) END ganador
from (select MAX(transjuego_info.transjuegoinfo_id)   maximoCasino,
             MAX(transjuego_info.fecha_crea)          maximoCasinoFecha,
             ''                                       maximoDeportivas,
             ''                                       maximoDeportivasFecha,
             count(transjuego_info.transjuegoinfo_id) cantidad,
             sum(transjuego_info.valor)               acumulado
      from transjuego_info
               inner join usuario_jackpot on usujackpot_id = transjuego_info.descripcion
      where tipo = 'JACKPOT'
        and jackpot_id = {$jackpot_id}
      UNION

      select ''                                       maximoCasino,
             ''                                       maximoCasinoFecha,
             MAX(it_ticket_enc_info1.it_ticket2_id)   maximoDeportivas,
             MAX(it_ticket_enc_info1.fecha_crea)      maximoDeportivasFecha,
             count(it_ticket_enc_info1.it_ticket2_id) cantidad,
             sum(it_ticket_enc_info1.valor2)               acumulado
      from it_ticket_enc_info1
               inner join usuario_jackpot
                          on usujackpot_id = it_ticket_enc_info1.valor
      where tipo = 'JACKPOT'
        and jackpot_id =  {$jackpot_id}
      ) jackpot;

        ";

                    $data2 = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

                    $JackpotResumen = $data2[0];


                    $cantidadAcumulada = $JackpotResumen->{'.cantidad'};
                    $ganador = $JackpotResumen->{'.ganador'};
                    $acumulado = $JackpotResumen->{'.acumulado'};

                    /** Actualizamos Jackpot */
                    $UsuarioJackpotMySqlDAO2 = new UsuarioJackpotMySqlDAO();
                    $Transaction = $UsuarioJackpotMySqlDAO2->getTransaction();


                    $sqlAccreditBet = "UPDATE jackpot_interno
                                SET
                                jackpot_interno.cantidad_apuesta = {$cantidadAcumulada},
                                jackpot_interno.valor_actual =  {$acumulado}
                                WHERE 
                                 
                                    jackpot_interno.jackpot_id = {$JackpotInterno->jackpotId}
                                AND jackpot_interno.estado = 'A'

                                ";
                    $sqlResult2 = $this->execUpdate($Transaction, $sqlAccreditBet);


                    $Transaction->commit();


                    if ($cantidadAcumulada >= $winnerBet) {
                        print_r(PHP_EOL);
                        print_r(PHP_EOL);
                        print_r($JackpotResumen);
                        print_r(PHP_EOL);
                        print_r(PHP_EOL);
                        flush();
                        ob_flush();
                        print_r(PHP_EOL);
                        print_r(PHP_EOL);
                        print_r('ENTRO AQUI');
                        print_r(PHP_EOL);
                        print_r(PHP_EOL);
                        flush();
                        ob_flush();
                        print_r(PHP_EOL);
                        print_r(PHP_EOL);
                        print_r('winnerBet');
                        print_r($winnerBet);
                        print_r(PHP_EOL);
                        print_r(PHP_EOL);
                        flush();
                        ob_flush();
                        /** Comenzando proceso de acreditación */
                        $UsuarioJackpotMySqlDAO2 = new UsuarioJackpotMySqlDAO();
                        $Transaction = $UsuarioJackpotMySqlDAO2->getTransaction();


                        $sqlAccreditBet = "UPDATE jackpot_interno
                                SET
                                jackpot_interno.estado              = 'G',
                                jackpot_interno.cantidad_apuesta = {$cantidadAcumulada},
                                jackpot_interno.valor_actual =  {$acumulado},
                                jackpot_interno.notas = '{$ganador}'
                                WHERE 
                                 
                                    jackpot_interno.jackpot_id = {$JackpotInterno->jackpotId}
                                AND jackpot_interno.estado = 'A'

                                ";
                        $sqlResult2 = $this->execUpdate($Transaction, $sqlAccreditBet);


                        $Transaction->commit();

                        $jackpotWinner = true;
                        /** Entregando premio en caso de haber caído el jackpot */
                        if ($jackpotWinner == true) {

                            /** Comenzando proceso de acreditación */
                            $UsuarioJackpotMySqlDAO2 = new UsuarioJackpotMySqlDAO();
                            $Transaction = $UsuarioJackpotMySqlDAO2->getTransaction();


                            if (strpos($ganador, 'C_') !== false) {
                                $TransjuegoInfo = new TransjuegoInfo(str_replace('C_', '', $ganador));
                                $TransjuegoLog = new TransjuegoLog($TransjuegoInfo->transapiId);
                                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                                $UsuarioJackpot = new UsuarioJackpot($TransjuegoInfo->descripcion);
                            } else {
                                $ItTicketEncInfo1 = new \Backend\dto\ItTicketEncInfo1(str_replace('S_', '', $ganador));
                                $ItTicketEnc = new ItTicketEnc($ItTicketEncInfo1->ticketId);
                                $Usuario = new Usuario($ItTicketEnc->usuarioId);
                                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                                $UsuarioJackpot = new UsuarioJackpot($ItTicketEncInfo1->valor);
                            }

                            try {
                                $JackpotInterno->pagarJackpot($JackpotInterno->jackpotId, $UsuarioJackpot->usujackpotId, $Transaction);
                            } catch (Exception $e) {
                                syslog(LOG_ERR, " ERRORPAGOJACKPOT : " . $e->getCode() . " - " . $e->getMessage() . "Línea: " . $e->getLine() . "Archivo : " . $e->getFile());
                                $Transaction->rollback();
                                continue;
                            }

                            $Transaction->commit();


                            try {
                                /** Se verifica si es necesario reiniciar el jackpot La función utilizada lanza excepciones*/
                                if ($JackpotInterno->reinicio) $JackpotInterno->clonarJakcpotNextSerie($JackpotInterno->jackpotId);
                            } catch (Exception $e) {
                            }

                            try {
                                sleep(30);

                                $rules = [];
                                array_push($rules, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                                array_push($rules, array("field" => "usuario_mensaje2.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
                                array_push($rules, array("field" => "usuario_mensaje2.body", "data" => $JackpotInterno->jackpotId, "op" => "eq"));
                                array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "JACKPOTWINNER", "op" => "eq"));
                                array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => 0, "op" => "eq"));
                                $filtroM = array("rules" => $rules, "groupOp" => "AND");
                                $json2 = json_encode($filtroM);

                                $UsuarioMensaje = new UsuarioMensaje();
                                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->usumandanteId);
                                $usuarios = json_decode($usuarios)->data;

                                $messagesToDesactivate = [];
                                foreach ($usuarios as $jackpotWinnerMessage) {
                                    try {
                                        $JackpotInterno = new JackpotInterno($jackpotWinnerMessage->{'usuario_mensaje.body'});
                                    } catch (Exception $e) {
                                        break;
                                    }

                                    $dropedJackpotData = [[
                                        'uid' => $jackpotWinnerMessage->{'usuario_mensaje.usumensaje_id'},
                                        'id' => $JackpotInterno->jackpotId,
                                        'videoMobile' => $JackpotInterno->videoMobile,
                                        'video' => $JackpotInterno->videoDesktop,
                                        'gif' => $JackpotInterno->gif,
                                        'imagen' => $JackpotInterno->imagen,
                                        'imagen2' => $JackpotInterno->imagen2,
                                        'monto' => $JackpotInterno->valorActual
                                    ]];
                                }


                                $dataSend = array();
                                $dataSend["loyalty_price"] = $dropedJackpotData;

                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);
                            } catch (Exception $e) {
                            }
                        }

                    }
                } catch (Exception $e) {
                }

                exit(0);
            } else {
                $activeProcesses[$pid] = $pid;
                $startTimes[$pid] = time();
            }
            $cont++;
        }

        unlink($filename);


    }

    public function execUpdate($transaccion, $sql)
    {

        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($transaccion);
        $return = $JackpotInternoMySqlDAO->queryUpdate($sql);

        return $return;

    }

    function createLog($tipo, $usuario_id, $valor_id1, $valor_id2, $valor_id3, $valor1, $valor2, $estado)
    {
        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $transaction = $BonoInternoMySqlDAO->getTransaction();

        if (strpos($valor_id1, '==') !== false) {
            $valor_id1 = base64_decode($valor_id2);
        }

        if (strpos($valor_id2, '==') !== false) {
            $valor_id2 = base64_decode($valor_id2);
        }

        $sql = "
INSERT INTO casino.log_cron (tipo, usuario_id, valor_id1, valor_id2, valor_id3, valor1, valor2, fecha_crea, fecha_modif,
                             estado)
VALUES ('$tipo', '" . ($usuario_id != '' ? $usuario_id : '0') . "', '$valor_id1', '$valor_id2', '$valor_id3', '$valor1', '$valor2', DEFAULT, DEFAULT, '$estado');

";
        $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
        $transaction->commit();
        return $resultsql;
    }

    function updateLog($logcron_id, $estado, $valor1 = '', $valor2 = '')
    {
        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $transaction = $BonoInternoMySqlDAO->getTransaction();

        $sql = "
            UPDATE log_cron SET estado='$estado'
    ";
        if ($valor1 != '') {
            $sql .= ",valor1='" . str_replace("'", '"', $valor1) . "' ";
        }
        if ($valor2 != '') {
            $sql .= ",valor2='" . str_replace("'", '"', $valor2) . "' ";

        }
        $sql .= " WHERE logcron_id=$logcron_id; ";
        $resultsql = $BonoInterno->execUpdate($transaction, $sql);
        $transaction->commit();
        return $resultsql;
    }

}

