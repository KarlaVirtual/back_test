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
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
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
class CronJobProcessLogCron
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


        $filename = __DIR__ . '/lastrun' . 'ProcessLogCron'.$argv1;

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

        $wherePreProcess='';
        $whereOFFSET='';

        if($argv1 != ''){
            $wherePreProcess .= " AND tipo='{$argv1}' ";
        }
        if($argv2 != ''){
            $whereOFFSET .= " OFFSET {$argv2} ";
        }
        $sqlApuestasDeportivasUsuarioDiaCierre = "
        SELECT * FROM log_cron WHERE estado='PREPROCESS'  {$wherePreProcess}
        LIMIT 10000
                               {$whereOFFSET}
        ";


        // Control de procesos simultáneos
        $maxConcurrentProcesses = 50; // Máximo de 10 procesos a la vez

        if($argv3 != '' && intval($argv3) > 0){
            $maxConcurrentProcesses = intval($argv3); // Máximo de 10 procesos a la vez

        }

        $activeProcesses = [];
        $startTimes = [];

        $time = time();

        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);



        $cont = 0;
        foreach ($data as $datanum) {
            $logID = $datanum->{'log_cron.logcron_id'};
            $valor1 = json_decode($datanum->{'log_cron.valor1'}, true);
            if (!is_array($valor1)) {
                continue;
            }
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
                // Código que ejecuta cada proceso hijo
                switch ($datanum->{'log_cron.tipo'}) {
                    case 'AgregarValorJackpot':
                        $arg1 = $valor1[1]; //Tipo de transaccion (CASINO, LIVECASINO, VIRTUALES, SPORTBOOK)
                        $arg2 = $valor1[2]; //ID Transaccion (transjuego_log.transjuegolog_id o it_ticket_enc.ticket_id)


                        //Definiendo vertical por la cual sumará la apuesta al Jackpot
                        $vertical = match ($arg1) {
                            'CASINO' => 'CASINO',
                            'LIVECASINO' => 'LIVECASINO',
                            'VIRTUALES' => 'VIRTUAL',
                            'VIRTUAL' => 'VIRTUAL',
                            'SPORTBOOK' => 'SPORTBOOK',
                            default => null
                        };
                        if($vertical == null){
                            $this->updateLog($logID, 'ERROR');

                            exit(0);
                        }

                        $this->updateLog($logID, 'PRE');
                        $JackpoInterno = new JackpotInterno();
                        $JackpoInterno->intentarAcreditarApuesta($vertical, $arg2);
                        $this->updateLog($logID, 'OK');
                        break;
                    case 'VerificarRollower':


                        $arg1 = $valor1[1];
                        $arg2 = $valor1[2];
                        $arg3 = $valor1[3];
                        $arg4 = $valor1[4];


                        $detalles2 = array(
                            "JuegosCasino" => array(array(
                                "Id" => 2
                            )

                            ),
                            "ValorApuesta" => 0
                        );
                        $this->updateLog($logID, 'PRE');

                        $BonoInterno = new BonoInterno();
                        $respuesta = $BonoInterno->verificarBonoRollower($arg3, $detalles2, $arg1, $arg2, $arg4);
                        $this->updateLog($logID, 'OK');

                        break;
                    case 'VerificarCashBack':


                        // Asignación de argumentos a variables
                        $arg1 = $valor1[1]; // paisId
                        $arg2 = $valor1[2]; // usuarioId
                        $arg3 = $valor1[3]; // Valor apuesta
                        $arg4 = $valor1[4]; // Tipo bono
                        $arg5 = $valor1[5]; // ProductoId
                        $arg6 = $valor1[6]; // Valor Ganancia
                        $arg7 = $valor1[7]; // CategoriaId
                        $arg8 = $valor1[8]; // SubProvedorId
                        $arg9 = $valor1[9]; // TransjuegoLog_id



                        $this->updateLog($logID, 'PRE');

                        if (true) {
                            $TransjuegoLog = new \Backend\dto\TransjuegoLog($arg9);
                            if (strpos($TransjuegoLog->getTipo(), 'DEBIT') === false) {
                                exit();
                            }
                            $TransaccionJuego = new \Backend\dto\TransaccionJuego($TransjuegoLog->getTransjuegoId());

                            $BonoAsignado = false;

                            // Inicialización de objetos relacionados con el usuario y su contexto
                            $UsuarioMandante = new UsuarioMandante($arg2);
                            $mandante = $UsuarioMandante->mandante;
                            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                            $Registro = new Registro('', $Usuario->usuarioId);

                            $CiudadMySqlDAO = new CiudadMySqlDAO();
                            $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
                            $detalleDepartamentoUSER = $Ciudad->deptoId;
                            $detalleCiudadUSER = $Ciudad->ciudadId;
                            $detalleMonedaUSER = $Usuario->moneda;
                            $IsPublic = false; // Determina si el bono es público o privado

                            $SkeepRows = 0;
                            $MaxRows = 100;

                            $BonoInterno = new BonoInterno();
                            $rules = [];

                            // Configuración de reglas para filtrar bonos disponibles
                            array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "bono_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));
                            array_push($rules, array("field" => "bono_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));
                            array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => date("Y-m-d H:i:s"), "op" => "ge"));
                            array_push($rules, array("field" => "bono_interno.tipo", "data" => ($arg4), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtro);
                            $BonosDisponibles = $BonoInterno->getBonosCustom("bono_interno.*", "bono_interno.orden,bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json2, true);


                            $BonosDisponibles = json_decode($BonosDisponibles);

                            // Iteración sobre los bonos disponibles para verificar condiciones
                            foreach ($BonosDisponibles->data as $Key2 => $Bono) {
                                if ( ! $BonoAsignado) {
                                    $bonoId = $Bono->{"bono_interno.bono_id"};
                                    $SkeepRows = 0;
                                    $MaxRows = 1000000;
                                    $rules = [];

                                    // Configuración de reglas para detalles del bono
                                    array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));
                                    array_push($rules, array("field" => "bono_interno.bono_id", "data" => $bonoId, "op" => "eq"));
                                    array_push($rules, array("field" => "bono_detalle.tipo", "data" => "'EXPDIA','REPETIRBONO','TIPOPRODUCTO','MAXJUGADORES','TIPOSALDO','MAXPAGO','VALORBONO','CONDPAISUSER','CONDDEPARTAMENTOUSER','CONDCIUDADUSER','CONDSUBPROVIDER','CONDGAME','CONDCATEGORY','CODEPROMO'", "op" => "in"));


                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);


                                    $BonoDetalle = new BonoDetalle();
                                    $BonoDetalle = $BonoDetalle->getBonoDetallesCustom2("bono_detalle.*,bono_interno.*", "bono_detalle.bonodetalle_id", "desc", $SkeepRows, $MaxRows, $json2, true, 'bono_detalle.bonodetalle_id');
                                    $BonoDetalle = json_decode($BonoDetalle);

                                    if ($Bono->{"bono_interno.publico"} == 'A') {
                                        $isPublic = true; //Es publico
                                    } elseif ($Bono->{"bono_interno.publico"} == 'I') {
                                        $isPublic = false; //Es privaddo
                                    }

                                    $cumpleCondiciones = true;

                                    // Variables para evaluar condiciones del bono
                                    $TIPOPRODUCTO = 0;
                                    $MINBETPRICE = 0;
                                    $condicionPaisUSERcount = 0;
                                    $MAXDAILYSPINS = 0;
                                    $puederepetirBono = false;
                                    $condicionDepartamentoUSERcount = 0;
                                    $condicionDepartamentoUSER = false;
                                    $condicionCiudadUSERcount = 0;
                                    $condicionCiudadUSER = false;
                                    $condicionesProducto = 0;
                                    $condicionesSubprovider = 0;
                                    $condicionesCategory = 0;
                                    $cumpleCondicionesProd = false;
                                    $cumpleCondicionesSubProveedor = false;
                                    $cumpleCondicionCategory = false;
                                    $maxJugadores = 0;
                                    $UserBonosInfinity = false; // Indica si el bono acepta asignaciones infinitas
                                    $CodePromocional = false;

                                    // Evaluación de condiciones del bono
                                    foreach ($BonoDetalle->data as $key1 => $value1) {
                                        switch ($value1->{"bono_detalle.tipo"}) {
                                            case "EXPDIA":
                                                $fechaTorneo = date('Y-m-d H:i:ss', strtotime($Bono->{"bono_interno.fecha_crea"} . ' + ' . $value1->{"bono_detalle.valor"} . ' days'));
                                                $fecha_actual = date("Y-m-d H:i:ss", time());
                                                if ($fechaTorneo < $fecha_actual) {
                                                    $cumpleCondiciones = false;
                                                }
                                                break;


                                            case "REPETIRBONO":

                                                if ($value1->{"bono_detalle.valor"} == '1') {
                                                    $puederepetirBono = true;
                                                }

                                                break;

                                            case "TIPOPRODUCTO":

                                                $TIPOPRODUCTO = $value1->{"bono_detalle.valor"};

                                                break;


                                            case "MAXJUGADORES":

                                                $maxJugadores = $value1->{"bono_detalle.valor"};


                                                if ($maxJugadores > 0) {
                                                    $UserBonosInfinity = false;

                                                    $BonoInterno = new BonoInterno();
                                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                                                    //Tambien puede llegarse a contar la cantidad de 'L'

                                                    $sqlRepiteBono = "select count(*) cantidad from usuario_bono a where a.bono_id=" . $bonoId . " and a.estado in " . "('P', 'PR', 'A', 'R')";
                                                    $cantidadBonos = $BonoInterno->execQuery($transaccion, $sqlRepiteBono);


                                                    if ($maxJugadores <= ($cantidadBonos[0]->{'.cantidad'} + 1)) {
                                                        $sqlRepiteBono = "select count(*) cantidad from usuario_bono a where a.bono_id=" . $bonoId . " and a.usuario_id = '" . $UsuarioMandante->usuarioMandante . "'";
                                                        $cantidadBonos = $BonoInterno->execQuery($transaccion, $sqlRepiteBono);


                                                        if (($cantidadBonos[0]->{'.cantidad'}) == '0') {
                                                            $cumpleCondiciones = false;
                                                        }
                                                    }
                                                } elseif ($maxJugadores == 0) {
                                                    $UserBonosInfinity = true;
                                                }

                                                break;


                                            case "MAXPAGO":
                                                $maximopago = $value1->{"bono_detalle.valor"};

                                                break;


                                            case "VALORBONO":

                                                $valorbono = $value1->{"bono_detalle.valor"};

                                                break;


                                            case "CONDPAISUSER":

                                                $condicionPaisUSERcount++;

                                                if ($value1->{"bono_detalle.valor"} == $arg1) {
                                                    $condicionPaisUSER = true;
                                                }

                                                break;


                                            case "CONDDEPARTAMENTOUSER":

                                                $condicionDepartamentoUSERcount++;
                                                if ($value1->{"bono_detalle.valor"} == $detalleDepartamentoUSER) {
                                                    $condicionDepartamentoUSER = true;
                                                }
                                                break;


                                            case "CONDCIUDADUSER":

                                                $condicionCiudadUSERcount++;

                                                if ($value1->{"bono_detalle.valor"} == $detalleCiudadUSER) {
                                                    $condicionCiudadUSER = true;
                                                }
                                                break;


                                            case "CONDSUBPROVIDER":

                                                if ($arg8 == $value1->{"bono_detalle.valor"}) {
                                                    $cumpleCondicionesSubProveedor = true;
                                                }

                                                $condicionesSubprovider++;
                                                break;


                                            case "CONDGAME":

                                                if ($arg5 == $value1->{"bono_detalle.valor"}) {
                                                    $cumpleCondicionesProd = true;
                                                }

                                                $condicionesProducto++;
                                                break;


                                            case "CONDCATEGORY":

                                                if ($arg7 == $value1->{"bono_detalle.valor"}) {
                                                    $cumpleCondicionCategory = true;
                                                }

                                                $condicionesCategory++;
                                                break;

                                            default:

                                                break;
                                        }
                                    }

                                    // Validación de condiciones acumuladas
                                    if ($condicionPaisUSERcount > 0 && ! $condicionPaisUSER) {
                                        $cumpleCondiciones = false;
                                    }

                                    if ($condicionesProducto > 0 && ! $cumpleCondicionesProd) {
                                        $cumpleCondiciones = false;
                                    }

                                    if ($condicionDepartamentoUSERcount > 0 && ! $condicionDepartamentoUSER) {
                                        $cumpleCondiciones = false;
                                    }

                                    if ($condicionCiudadUSERcount > 0 && ! $condicionCiudadUSER) {
                                        $cumpleCondiciones = false;
                                    }


                                    if ($condicionesSubprovider > 0 && ! $cumpleCondicionesSubProveedor) {
                                        $cumpleCondiciones = false;
                                    }

                                    if ($condicionesCategory > 0 && ! $cumpleCondicionCategory) {
                                        $cumpleCondiciones = false;
                                    }

                                    // Asignación del bono si cumple las condiciones
                                    if ($cumpleCondiciones) {
                                        $tieneBono = false;

                                        $sqlRepiteBono = "select a.usubono_id idbono from usuario_bono a where a.bono_id=" . $bonoId . " and a.usuario_id = '" . $UsuarioMandante->usuarioMandante . "'";
                                        $sqlRepiteBonoResult = $BonoInterno->execQuery($transaccion, $sqlRepiteBono);

                                        print_r($sqlRepiteBonoResult);

                                        if (($sqlRepiteBonoResult[0]->{'a.idbono'}) != '' && ($sqlRepiteBonoResult[0]->{'a.idbono'}) != '0') {
                                            $tieneBono = true;
                                        }


                                        if ($tieneBono) { //Si el bono es limitado -> UPDATE
                                            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                                            $sql = "UPDATE usuario_bono set valor_base=valor_base+'" . floatval($arg3) . "' ,apostado=apostado+'" . floatval($arg3) . "' where estado='P' AND  bono_id='" . $bonoId . "';";

                                            $rowsUpdate = $BonoInterno->execUpdate($transaccion, $sql);

                                            $sql = "SELECT * FROM usuario_bono  where estado='P' AND  bono_id='" . $bonoId . "';";
                                            $data = $BonoInterno->execQuery($transaccion, $sql);

                                            $TransjuegoInfo = new TransjuegoInfo();
                                            $TransjuegoInfo->transjuegoId = $TransaccionJuego->transjuegoId;

                                            $TransjuegoInfo->productoId = $TransaccionJuego->productoId;
                                            $TransjuegoInfo->transaccionId = $TransjuegoLog->transaccionId;
                                            $TransjuegoInfo->tipo = "CASHBACKDEBIT";
                                            $TransjuegoInfo->valor = $TransjuegoLog->valor;
                                            $TransjuegoInfo->transapiId = $TransjuegoLog->transjuegologId;
                                            $TransjuegoInfo->usucreaId = 0;
                                            $TransjuegoInfo->usumodifId = 0;
                                            $TransjuegoInfo->identificador = $TransaccionJuego->ticketId;

                                            if ($rowsUpdate == 0) {
                                                $SkeepRows = 0;
                                                $MaxRows = 1;
                                                $rules = [];
                                                array_push($rules, array("field" => "usuario_bono.estado", "data" => "L", "op" => "eq")); //usuario_bono estado A con limite 1
                                                array_push($rules, array("field" => "usuario_bono.bono_id", "data" => $bonoId, "op" => "eq"));
                                                array_push($rules, array("field" => "bono_interno.tipo", "data" => ($arg4), "op" => "eq"));

                                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                                $json2 = json_encode($filtro);


                                                $UsuarioBono = new UsuarioBono();

                                                $Type = ($arg4);

                                                $UsuariosBonos = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "usuario_bono.usubono_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                                $UsuariosBonos = json_decode($UsuariosBonos);

                                                $usubonoId = $UsuariosBonos->data[0]->{"usuario_bono.usubono_id"};


                                                $UsuarioBono = new UsuarioBono($usubonoId);
                                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                                                $UsuarioBono->usuarioId = $UsuarioMandante->usuarioMandante;
                                                $UsuarioBono->valorBase = floatval($arg3);
                                                $UsuarioBono->fechaModif = date('Y-m-d H:i:s');
                                                $UsuarioBono->estado = "P";
                                                $UsuarioBono->mandante = $UsuarioMandante->mandante;
                                                $UsuarioBono->apostado = floatval($arg3);

                                                $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($transaccion);
                                                $UsuarioBonoMySqlDAO->update($UsuarioBono);

                                                $TransjuegoInfo->descripcion = $UsuarioBono->usubonoId;
                                            } else {
                                                $data = $data[0];

                                                $TransjuegoInfo->descripcion = $data->{'usuario_bono.usubono_id'};
                                            }


                                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($transaccion);

                                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);

                                            $transaccion->commit();

                                            if ($puederepetirBono) {
                                                $BonoAsignado = false;
                                            } else {
                                                $BonoAsignado = true;
                                            }
                                        } elseif ( ! $tieneBono) { //Si el bono es ilimitado -> INSERT


                                            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                                            $UsuarioBono = new UsuarioBono();
                                            $UsuarioBono->bonoId = $bonoId;
                                            $UsuarioBono->usuarioId = $UsuarioMandante->usuarioMandante;
                                            $UsuarioBono->valor = 0;
                                            $UsuarioBono->valorBono = 0;
                                            $UsuarioBono->valor = 0;
                                            $UsuarioBono->posicion = 0;
                                            $UsuarioBono->valorBase = floatval($arg3);
                                            $UsuarioBono->fechaCrea = date('Y-m-d H:i:s');
                                            $UsuarioBono->usucreaId = 0;
                                            $UsuarioBono->fechaModif = date('Y-m-d H:i:s');
                                            $UsuarioBono->usumodifId = 0;

                                            $UsuarioBono->estado = "P";

                                            $UsuarioBono->errorId = 0;
                                            $UsuarioBono->idExterno = 0;
                                            $UsuarioBono->mandante = $UsuarioMandante->mandante;
                                            $UsuarioBono->version = 0;
                                            $UsuarioBono->apostado = floatval($arg3);
                                            $UsuarioBono->rollowerRequerido = 0;
                                            $UsuarioBono->codigo = 0;
                                            $UsuarioBono->externoId = 0;
                                            $UsuarioBono->valorPremio = 0;
                                            $UsuarioBono->premio = 0;
                                            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($transaccion);
                                            $UsuarioBonoMySqlDAO->insert($UsuarioBono);


                                            $TransjuegoInfo = new TransjuegoInfo();
                                            $TransjuegoInfo->transjuegoId = $TransaccionJuego->transjuegoId;

                                            $TransjuegoInfo->productoId = $TransaccionJuego->productoId;
                                            $TransjuegoInfo->transaccionId = $TransjuegoLog->transaccionId;
                                            $TransjuegoInfo->tipo = "CASHBACKDEBIT";
                                            $TransjuegoInfo->descripcion = $UsuarioBono->usubonoId;
                                            $TransjuegoInfo->valor = $TransjuegoLog->valor;
                                            $TransjuegoInfo->transapiId = $TransjuegoLog->transjuegologId;
                                            $TransjuegoInfo->usucreaId = 0;
                                            $TransjuegoInfo->usumodifId = 0;
                                            $TransjuegoInfo->identificador = $TransaccionJuego->ticketId;

                                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($transaccion);

                                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);


                                            print_r($TransjuegoInfo);
                                            $transaccion->commit();


                                            if ($puederepetirBono) {
                                                $BonoAsignado = false;
                                            } else {
                                                $BonoAsignado = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }






                        $this->updateLog($logID, 'OK');

                        break;
                    case 'ActivacionRuletaCasino':




                        $arg1 = $valor1[1]; //usuario_mandante.pais_id
                        $arg2 = $valor1[2]; //usuario_mandante.usumandante_id
                        $arg3 = $valor1[3]; //Amount
                        $arg4 = $valor1[4]; //Tipo = 2
                        $arg5 = $valor1[5]; //$Categoria->categoriaId
                        $arg6 = $valor1[6]; //producto.subproveedor_id
                        $arg7 = $valor1[7]; //producto_mandante.prodmandante_id



                        $this->updateLog($logID, 'PRE');

                        $RuletaInterno = new RuletaInterno();
                        $Response = $RuletaInterno->agregarRuleta($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7);

                        $this->updateLog($logID, 'OK');

                        break;
                    case 'ActivacionRuletaSportBook':


                        $arg1 = $valor1[1]; //usuario.pais_id
                        $arg2 = $valor1[2]; //it_ticket_enc.usuario_id
                        $arg3 = $valor1[3]; //Amount
                        $arg4 = $valor1[4]; //Tipo = 1
                        $arg5 = $valor1[5]; //it_ticket_enc.ticket_id




                        $this->updateLog($logID, 'PRE');

                        $RuletaInterno = new RuletaInterno();
                        $Response = $RuletaInterno->agregarRuleta($arg1, $arg2, $arg3, $arg4, "", "", "", $arg5);
                        $this->updateLog($logID, 'OK');

                        break;
                }

                exit(0);
            } else {
                $activeProcesses[$pid] = $pid;
                $startTimes[$pid] = time();
            }
            $cont++;

        }
        syslog(LOG_WARNING, 'ANTES whileactiveProcesses '.date('Y-m-d H:i:s'));
        while (count($activeProcesses)) {
            foreach ($activeProcesses as $key => $pid) {
                $res = pcntl_waitpid($pid, $status, WNOHANG);

                if ($res > 0) {
                    // Proceso terminado normalmente
                    unset($activeProcesses[$pid]);
                    unset($startTimes[$pid]);
                } else {
                    $maxTime = 300; // 5 minutos en segundos

                    // Verificar si ha pasado más de 5 minutos
                    if ((time() - $startTimes[$pid]) > $maxTime) {
                        posix_kill($pid, SIGKILL);
                        pcntl_waitpid($pid, $status); // Limpiar zombie
                        unset($activeProcesses[$pid]);
                        unset($startTimes[$pid]);
                    }
                }
            }
            usleep(10000); // Pequeña pausa (0.1s) para no consumir CPU en el loop
        }

        syslog(LOG_WARNING, 'DESPUES whileactiveProcesses '.date('Y-m-d H:i:s'));

        unlink($filename);


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

