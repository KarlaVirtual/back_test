<?php

/**
 * Este archivo contiene la lógica para verificar y asignar bonos tipo cashback
 * en función de diversas condiciones y reglas de negocio.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log                            Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $argv                           Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $arg1                           Esta variable se utiliza para almacenar y manipular el valor de 'arg1' en el contexto actual.
 * @var mixed $arg2                           Esta variable se utiliza para almacenar y manipular el valor de 'arg2' en el contexto actual.
 * @var mixed $arg3                           Esta variable se utiliza para almacenar y manipular el valor de 'arg3' en el contexto actual.
 * @var mixed $arg4                           Esta variable se utiliza para almacenar y manipular el valor de 'arg4' en el contexto actual.
 * @var mixed $arg5                           Esta variable se utiliza para almacenar y manipular el valor de 'arg5' en el contexto actual.
 * @var mixed $arg6                           Esta variable se utiliza para almacenar y manipular el valor de 'arg6' en el contexto actual.
 * @var mixed $arg7                           Esta variable se utiliza para almacenar y manipular el valor de 'arg7' en el contexto actual.
 * @var mixed $arg8                           Esta variable se utiliza para almacenar y manipular el valor de 'arg8' en el contexto actual.
 * @var mixed $arg9                           Esta variable se utiliza para almacenar y manipular el valor de 'arg9' en el contexto actual.
 * @var mixed $TransjuegoLog                  Variable que almacena registros de transacciones del sistema Transjuego.
 * @var mixed $TransaccionJuego               Esta variable se utiliza para almacenar y manipular el valor de 'TransaccionJuego' en el contexto actual.
 * @var mixed $BonoAsignado                   Esta variable se utiliza para almacenar y manipular el valor de 'BonoAsignado' en el contexto actual.
 * @var mixed $UsuarioMandante                Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $mandante                       Variable que almacena el mandante o entidad responsable de una operación.
 * @var mixed $Usuario                        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Registro                       Variable que almacena información sobre un registro.
 * @var mixed $CiudadMySqlDAO                 Esta variable se utiliza para almacenar y manipular el valor de 'CiudadMySqlDAO' en el contexto actual.
 * @var mixed $Ciudad                         Variable que almacena el nombre de una ciudad.
 * @var mixed $detalleDepartamentoUSER        Esta variable se utiliza para almacenar y manipular el valor de 'detalleDepartamentoUSER' en el contexto actual.
 * @var mixed $detalleCiudadUSER              Esta variable se utiliza para almacenar y manipular el valor de 'detalleCiudadUSER' en el contexto actual.
 * @var mixed $detalleMonedaUSER              Esta variable se utiliza para almacenar y manipular el valor de 'detalleMonedaUSER' en el contexto actual.
 * @var mixed $IsPublic                       Esta variable se utiliza para almacenar y manipular el valor de 'IsPublic' en el contexto actual.
 * @var mixed $SkeepRows                      Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $MaxRows                        Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $BonoInterno                    Variable que representa un bono interno en el sistema.
 * @var mixed $rules                          Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                         Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $json2                          Variable que almacena un segundo conjunto de datos en formato JSON.
 * @var mixed $BonosDisponibles               Esta variable se utiliza para almacenar y manipular el valor de 'BonosDisponibles' en el contexto actual.
 * @var mixed $Key2                           Esta variable se utiliza para almacenar y manipular el valor de 'Key2' en el contexto actual.
 * @var mixed $Bono                           Esta variable se utiliza para almacenar y manipular el valor de 'Bono' en el contexto actual.
 * @var mixed $bonoId                         Esta variable se utiliza para almacenar y manipular el valor de 'bonoId' en el contexto actual.
 * @var mixed $BonoDetalle                    Esta variable se utiliza para almacenar y manipular el valor de 'BonoDetalle' en el contexto actual.
 * @var mixed $isPublic                       Esta variable se utiliza para almacenar y manipular el valor de 'isPublic' en el contexto actual.
 * @var mixed $cumpleCondiciones              Esta variable se utiliza para almacenar y manipular el valor de 'cumpleCondiciones' en el contexto actual.
 * @var mixed $TIPOPRODUCTO                   Esta variable se utiliza para almacenar y manipular el valor de 'TIPOPRODUCTO' en el contexto actual.
 * @var mixed $MINBETPRICE                    Esta variable se utiliza para almacenar y manipular el valor de 'MINBETPRICE' en el contexto actual.
 * @var mixed $condicionPaisUSERcount         Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $MAXDAILYSPINS                  Esta variable se utiliza para almacenar y manipular el valor de 'MAXDAILYSPINS' en el contexto actual.
 * @var mixed $puederepetirBono               Esta variable se utiliza para almacenar y manipular el valor de 'puederepetirBono' en el contexto actual.
 * @var mixed $condicionDepartamentoUSERcount Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $condicionDepartamentoUSER      Esta variable se utiliza para almacenar y manipular el valor de 'condicionDepartamentoUSER' en el contexto actual.
 * @var mixed $condicionCiudadUSERcount       Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $condicionCiudadUSER            Esta variable se utiliza para almacenar y manipular el valor de 'condicionCiudadUSER' en el contexto actual.
 * @var mixed $condicionesProducto            Esta variable se utiliza para almacenar y manipular el valor de 'condicionesProducto' en el contexto actual.
 * @var mixed $condicionesSubprovider         Esta variable se utiliza para almacenar y manipular el valor de 'condicionesSubprovider' en el contexto actual.
 * @var mixed $condicionesCategory            Esta variable se utiliza para almacenar y manipular el valor de 'condicionesCategory' en el contexto actual.
 * @var mixed $cumpleCondicionesProd          Esta variable se utiliza para almacenar y manipular el valor de 'cumpleCondicionesProd' en el contexto actual.
 * @var mixed $cumpleCondicionesSubProveedor  Esta variable se utiliza para almacenar y manipular el valor de 'cumpleCondicionesSubProveedor' en el contexto actual.
 * @var mixed $cumpleCondicionCategory        Esta variable se utiliza para almacenar y manipular el valor de 'cumpleCondicionCategory' en el contexto actual.
 * @var mixed $maxJugadores                   Esta variable se utiliza para almacenar y manipular el valor de 'maxJugadores' en el contexto actual.
 * @var mixed $UserBonosInfinity              Esta variable se utiliza para almacenar y manipular el valor de 'UserBonosInfinity' en el contexto actual.
 * @var mixed $CodePromocional                Esta variable se utiliza para almacenar y manipular el valor de 'CodePromocional' en el contexto actual.
 * @var mixed $key1                           Esta variable se utiliza para almacenar y manipular el valor de 'key1' en el contexto actual.
 * @var mixed $value1                         Esta variable se utiliza para almacenar y manipular el valor de 'value1' en el contexto actual.
 * @var mixed $fechaTorneo                    Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $fecha_actual                   Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $BonoDetalleMySqlDAO            Variable que hace referencia a una clase o componente DAO relacionado con la base de datos MySQL para bonos.
 * @var mixed $transaccion                    Variable que almacena datos relacionados con una transacción.
 * @var mixed $sqlRepiteBono                  Esta variable se utiliza para almacenar y manipular el valor de 'sqlRepiteBono' en el contexto actual.
 * @var mixed $cantidadBonos                  Esta variable se utiliza para almacenar y manipular el valor de 'cantidadBonos' en el contexto actual.
 * @var mixed $bonoElegido                    Esta variable se utiliza para almacenar y manipular el valor de 'bonoElegido' en el contexto actual.
 * @var mixed $maximopago                     Esta variable se utiliza para almacenar y manipular el valor de 'maximopago' en el contexto actual.
 * @var mixed $valorbono                      Esta variable se utiliza para almacenar y manipular el valor de 'valorbono' en el contexto actual.
 * @var mixed $condicionPaisUSER              Esta variable se utiliza para almacenar y manipular el valor de 'condicionPaisUSER' en el contexto actual.
 * @var mixed $CumpleCondition                Esta variable se utiliza para almacenar y manipular el valor de 'CumpleCondition' en el contexto actual.
 * @var mixed $tiposaldo                      Esta variable se utiliza para almacenar y manipular el valor de 'tiposaldo' en el contexto actual.
 * @var mixed $tieneBono                      Esta variable se utiliza para almacenar y manipular el valor de 'tieneBono' en el contexto actual.
 * @var mixed $sqlRepiteBonoResult            Esta variable se utiliza para almacenar y manipular el valor de 'sqlRepiteBonoResult' en el contexto actual.
 * @var mixed $sql                            Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $rowsUpdate                     Variable que almacena el número de filas actualizadas en una consulta.
 * @var mixed $data                           Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $TransjuegoInfo                 Esta variable se utiliza para almacenar y manipular el valor de 'TransjuegoInfo' en el contexto actual.
 * @var mixed $UsuarioBono                    Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Type                           Variable que almacena el tipo de un objeto o transacción.
 * @var mixed $UsuariosBonos                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $usubonoId                      Esta variable se utiliza para almacenar y manipular el valor de 'usubonoId' en el contexto actual.
 * @var mixed $UsuarioBonoMySqlDAO            Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $TransjuegoInfoMySqlDAO         Esta variable se utiliza para almacenar y manipular el valor de 'TransjuegoInfoMySqlDAO' en el contexto actual.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\TransjuegoInfo;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\BonoInterno;
use Backend\dto\BonoDetalle;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;

/**
 * Registra los argumentos y datos de entrada en un archivo de log.
 */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($argv);
$log = $log . trim(file_get_contents('php://input'));

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

// Asignación de bono tipo cashback
if ($argv[1] == 'MANUAL') {
    exit();
} else {
    // Asignación de argumentos a variables
    $arg1 = $argv[1]; // paisId
    $arg2 = $argv[2]; // usuarioId
    $arg3 = $argv[3]; // Valor apuesta
    $arg4 = $argv[4]; // Tipo bono
    $arg5 = $argv[5]; // ProductoId
    $arg6 = $argv[6]; // Valor Ganancia
    $arg7 = $argv[7]; // CategoriaId
    $arg8 = $argv[8]; // SubProvedorId
    $arg9 = $argv[9]; // TransjuegoLog_id

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
}
