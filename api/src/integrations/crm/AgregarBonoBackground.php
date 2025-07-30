<?php

/**
 * Procesar diferentes tipos de bonos para un usuario.
 *
 * Este script procesa diferentes tipos de bonos para un usuario en función de su ID y el ID del bono.
 * Utiliza conexiones a bases de datos MySQL y Redis para gestionar la información de los bonos y los usuarios.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-19
 */

/**
 * Procesar diferentes tipos de bonos para un usuario.
 *
 * @var mixed $UsuarioId              Esta variable representa la información del usuario, empleada para identificarlo
 *                                    dentro del sistema.
 * @var mixed $argv                   Esta variable se utiliza para almacenar y manipular los argumentos pasados al
 *                                    script.
 * @var mixed $BonoId                 Esta variable se utiliza para almacenar y manipular el identificador del bono.
 * @var mixed $CampaignID             Esta variable se utiliza para almacenar y manipular el identificador de la
 *                                    campaña.
 * @var mixed $redisParam             Esta variable se utiliza para almacenar y manipular parámetros de Redis.
 * @var mixed $redisPrefix            Esta variable se utiliza para almacenar y manipular el prefijo utilizado en
 *                                    Redis.
 * @var mixed $redis                  Esta variable se utiliza para almacenar y manipular instancias de Redis.
 * @var mixed $BonoInternoMySqlDAO    Objeto que maneja operaciones de base de datos para bonos internos en MySQL.
 * @var mixed $BonoInterno            Variable que representa un bono interno en el sistema.
 * @var mixed $Usuario                Esta variable representa la información del usuario, empleada para identificarlo
 *                                    dentro del sistema.
 * @var mixed $UsuarioBono            Esta variable representa la información del usuario, empleada para identificarlo
 *                                    dentro del sistema.
 * @var mixed $UsuarioBonoMysqlDAO    Esta variable representa la información del usuario, empleada para identificarlo
 *                                    dentro del sistema.
 * @var mixed $Registro               Variable que almacena información sobre un registro.
 * @var mixed $CiudadMySqlDAO         Variable que representa la conexión a la base de datos para la gestión de
 *                                    ciudades.
 * @var mixed $CONDSUBPROVIDER        Variable que almacena condiciones específicas de un subproveedor.
 * @var mixed $CONDGAME               Variable que almacena condiciones específicas de un juego.
 * @var mixed $Transaction            Esta variable contiene información de una transacción, utilizada para el
 *                                    seguimiento y procesamiento de operaciones.
 * @var mixed $sqlDetalleBono         Variable que contiene la consulta SQL para obtener detalles de un bono.
 * @var mixed $bonoDetalles           Variable que almacena un conjunto de detalles de un bono.
 * @var mixed $bonoDetalle            Variable que almacena un detalle específico de un bono.
 * @var mixed $idSub                  Variable que almacena el identificador de un subproveedor.
 * @var mixed $idGame                 Variable que almacena el identificador de un juego.
 * @var mixed $Prefix                 Variable que almacena un prefijo utilizado en diversas operaciones.
 * @var mixed $MaxplayersCount        Esta variable indica la cantidad total de elementos o registros, útil para
 *                                    iteraciones o validaciones.
 * @var mixed $Subproveedor           Variable que almacena información del subproveedor.
 * @var mixed $Proveedor              Esta variable representa la información del proveedor, utilizada para operaciones
 *                                    comerciales o logísticas.
 * @var mixed $responseBonoGlobal     Variable que almacena la respuesta global de una operación relacionada con bonos.
 * @var mixed $usubonoId              Variable que almacena el identificador de un bono asignado a un usuario.
 * @var mixed $BonoDetalleVALORBONO   Variable que almacena el valor del bono.
 * @var mixed $valor_bono             Variable que almacena el monto específico del bono.
 * @var mixed $e                      Esta variable se utiliza para capturar excepciones o errores en bloques
 *                                    try-catch.
 * @var mixed $BonoDetalleWFACTORBONO Variable que almacena el factor de apuesta (wagering) del bono.
 * @var mixed $rollowerBono           Variable que indica los requisitos de liberación del bono.
 * @var mixed $BonoDetalleROUNDSFREE  Variable que almacena la cantidad de rondas gratis asociadas a un bono.
 * @var mixed $rollowerValor          Variable que almacena el monto de apuesta necesario para liberar el bono.
 * @var mixed $rollowerRequerido      Variable que indica si se requiere un rollover para el bono.
 * @var mixed $codigoBono             Variable que almacena el código único de un bono.
 * @var mixed $BonoDetalleMySqlDAO    Variable que hace referencia a una clase o componente DAO relacionado con la base
 *                                    de datos MySQL para bonos.
 * @var mixed $transaccion            Variable que almacena datos relacionados con una transacción.
 * @var mixed $usuarioSql             Esta variable representa la información del usuario, empleada para identificarlo
 *                                    dentro del sistema.
 * @var mixed $dataUsuario            Esta variable representa la información del usuario, empleada para identificarlo
 *                                    dentro del sistema.
 * @var mixed $detalles               Variable que almacena detalles adicionales o información más específica sobre un
 *                                    proceso o elemento.
 * @var mixed $respuesta              Esta variable se utiliza para almacenar y manipular la respuesta de una
 *                                    operación.
 * @var mixed $valorBase              Variable que almacena el valor base de cálculo para un bono.
 * @var mixed $valorBono              Variable que almacena el valor total de un bono.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use /**
 * Represents a custom exception class.
 *
 * Exceptions of this type are thrown when a specific error condition occurs
 * in the application. It extends the base Exception class, allowing for
 * custom handling of specific error cases.
 */
    Exception;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioBono;
use Backend\dto\Subproveedor;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\utils\RedisConnectionTrait;

// Obtiene los parámetros de entrada del script
$UsuarioId = $argv[1]; // ID del usuario
$BonoId = $argv[2];    // ID del bono
$CampaignID = $argv[3]; // ID de la campaña

// Configuración de parámetros para Redis
$redisParam = ['ex' => 18000];

// Crea una instancia del usuario con el ID proporcionado
$Usuario = new Usuario($UsuarioId);

// Determina el prefijo en función de las propiedades del usuario
$Prefix = '';
if ($Usuario->mandante == '0' && $Usuario->paisId == 173) {
    $Prefix = 'ADMIN2';
} elseif ($Usuario->mandante != '8') {
    $Prefix = 'ADMIN3';
}

// Genera un prefijo único para Redis basado en los parámetros
$redisPrefix = $Prefix . "F3BACK+AgregarBonoBackground+UID" . $UsuarioId . '+' . $BonoId . '+' . $CampaignID;

// Obtiene una instancia de Redis
$redis = RedisConnectionTrait::getRedisInstance(true);

// Si Redis está disponible, almacena los datos y finaliza el script
if ($redis != null) {
    $redis->set($redisPrefix, json_encode($argv), $redisParam);
    exit();
}

try {
    // Crea una instancia del DAO para manejar bonos internos
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

    // Ajusta el ID del bono si es necesario
    if ($BonoId == '26354') {
        $BonoId = 26366;
    }

    // Crea una instancia del bono interno con el ID proporcionado
    $BonoInterno = new BonoInterno($BonoId);

    // Procesa el bono para el usuario con ID específico
    if ($BonoId == 32785) {
        // Crea y configura un nuevo bono para el usuario
        $UsuarioBono = new UsuarioBono();
        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
        $UsuarioBono->setBonoId($BonoInterno->bonoId);
        $UsuarioBono->setValor(0);
        $UsuarioBono->setValorBono(0);
        $UsuarioBono->setValorBase(0);
        $UsuarioBono->setEstado('P');
        $UsuarioBono->setErrorId('0');
        $UsuarioBono->setIdExterno('0');
        $UsuarioBono->setMandante($BonoInterno->mandante);
        $UsuarioBono->setUsucreaId('0');
        $UsuarioBono->setUsumodifId('0');
        $UsuarioBono->setApostado('0');
        $UsuarioBono->setVersion('3');
        $UsuarioBono->setRollowerRequerido('0');
        $UsuarioBono->setCodigo('');
        $UsuarioBono->setExternoId('0');

        // Inserta el bono en la base de datos y confirma la transacción
        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();
        $UsuarioBonoMysqlDAO->insert($UsuarioBono);
        $UsuarioBonoMysqlDAO->getTransaction()->commit();
    } elseif ($BonoId == 32793) {
        // Similar al caso anterior, procesa el bono para otro ID específico
        $UsuarioBono = new UsuarioBono();
        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
        $UsuarioBono->setBonoId($BonoInterno->bonoId);
        $UsuarioBono->setValor(0);
        $UsuarioBono->setValorBono(0);
        $UsuarioBono->setValorBase(0);
        $UsuarioBono->setEstado('P');
        $UsuarioBono->setErrorId('0');
        $UsuarioBono->setIdExterno('0');
        $UsuarioBono->setMandante($BonoInterno->mandante);
        $UsuarioBono->setUsucreaId('0');
        $UsuarioBono->setUsumodifId('0');
        $UsuarioBono->setApostado('0');
        $UsuarioBono->setVersion('3');
        $UsuarioBono->setRollowerRequerido('0');
        $UsuarioBono->setCodigo('');
        $UsuarioBono->setExternoId('0');

        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();
        $UsuarioBonoMysqlDAO->insert($UsuarioBono);
        $UsuarioBonoMysqlDAO->getTransaction()->commit();
    } elseif ($BonoId == 32821) {
        // Procesa el bono para otro ID específico
        $UsuarioBono = new UsuarioBono();

        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
        $UsuarioBono->setBonoId($BonoInterno->bonoId);
        $UsuarioBono->setValor(0);
        $UsuarioBono->setValorBono(0);
        $UsuarioBono->setValorBase(0);
        $UsuarioBono->setEstado('P');
        $UsuarioBono->setErrorId('0');
        $UsuarioBono->setIdExterno('0');
        $UsuarioBono->setMandante($BonoInterno->mandante);
        $UsuarioBono->setUsucreaId('0');
        $UsuarioBono->setUsumodifId('0');
        $UsuarioBono->setApostado('0');
        $UsuarioBono->setVersion('3');
        $UsuarioBono->setRollowerRequerido('0');
        $UsuarioBono->setCodigo('');
        $UsuarioBono->setExternoId('0');

        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();
        $UsuarioBonoMysqlDAO->insert($UsuarioBono);
        $UsuarioBonoMysqlDAO->getTransaction()->commit();
    } elseif ($BonoId == 32822) {
        // Procesa el bono para otro ID específico
        $UsuarioBono = new UsuarioBono();
        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
        $UsuarioBono->setBonoId($BonoInterno->bonoId);
        $UsuarioBono->setValor(0);
        $UsuarioBono->setValorBono(0);
        $UsuarioBono->setValorBase(0);
        $UsuarioBono->setEstado('P');
        $UsuarioBono->setErrorId('0');
        $UsuarioBono->setIdExterno('0');
        $UsuarioBono->setMandante($BonoInterno->mandante);
        $UsuarioBono->setUsucreaId('0');
        $UsuarioBono->setUsumodifId('0');
        $UsuarioBono->setApostado('0');
        $UsuarioBono->setVersion('3');
        $UsuarioBono->setRollowerRequerido('0');
        $UsuarioBono->setCodigo('');
        $UsuarioBono->setExternoId('0');

        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();
        $UsuarioBonoMysqlDAO->insert($UsuarioBono);
        $UsuarioBonoMysqlDAO->getTransaction()->commit();
    } else {
        // Procesa otros tipos de bonos
        // (El código continúa con lógica adicional para manejar diferentes tipos de bonos)
        $Registro = new Registro("", $Usuario->usuarioId);
        $CiudadMySqlDAO = new CiudadMySqlDAO();
        $CONDSUBPROVIDER = array();
        $CONDGAME = array();

        $insertBonus = true;
        try {
            $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'REPETIRBONO');
            $repeatBonus = $BonoDetalleROUNDSFREE->valor;
        } catch (Exception $e) {
            $repeatBonus = 0;
        }

        if ( ! $repeatBonus) {
            try {
                $VerifUsuarioBono = new UsuarioBono('', $Usuario->usuarioId, $BonoId);
                $insertBonus = false;
            } catch (Exception $e) {
                $insertBonus = true;
            }
        }

        /**
         * Procesa bonos Freespin.
         */
        if ($BonoInterno->tipo == "8") {
            $Transaction = $BonoInternoMySqlDAO->getTransaction();
            $sqlDetalleBono = "select * from bono_detalle a  where a.bono_id='" . $BonoId . "' AND (moneda='' OR moneda='" . $Usuario->moneda . "') ";

            $bonoDetalles = $BonoInterno->execQuery($Transaction, $sqlDetalleBono);

            foreach ($bonoDetalles as $bonoDetalle) {
                if (stristr($bonoDetalle->{'a.tipo'}, 'CONDSUBPROVIDER')) {
                    syslog(LOG_WARNING, "CAMPA OPTIMOVE 2: " . $CampaignID . ' ' . $bonoDetalle->{'a.tipo'});

                    $idSub = explode("CONDSUBPROVIDER", $bonoDetalle->{'a.tipo'})[1];
                    syslog(LOG_WARNING, "CAMPA OPTIMOVE 3: " . $CampaignID . ' ' . $idSub);

                    array_push($CONDSUBPROVIDER, $idSub);
                    syslog(LOG_WARNING, "CAMPA OPTIMOVE 3A: " . $CampaignID . ' ' . $idSub);
                }

                if (stristr($bonoDetalle->{'a.tipo'}, 'CONDGAME')) {
                    $idGame = explode("CONDGAME", $bonoDetalle->{'a.tipo'})[1];
                    if ($idGame == '') {
                        if ($bonoDetalle->{'a.valor'} != '') {
                            $idGame = $bonoDetalle->{'a.valor'};
                        }
                    }
                    array_push($CONDGAME, $idGame);
                }

                if (stristr($bonoDetalle->{'a.tipo'}, 'PREFIX')) {
                    $Prefix = explode("PREFIX", $bonoDetalle->{'a.tipo'})[1];
                    if ($Prefix == '') {
                        if ($bonoDetalle->{'a.valor'} != '') {
                            $Prefix = $bonoDetalle->{'a.valor'};
                        }
                    }
                }

                if (stristr($bonoDetalle->{'a.tipo'}, 'MAXJUGADORES')) {
                    $MaxplayersCount = explode("MAXJUGADORES", $bonoDetalle->{'a.tipo'})[1];
                    if ($MaxplayersCount == '') {
                        if ($bonoDetalle->{'a.valor'} != '') {
                            $MaxplayersCount = $bonoDetalle->{'a.valor'};
                        }
                    }
                }
            }

            syslog(LOG_WARNING, "CAMPA OPTIMOVE 4: " . $CampaignID . ' ' . $idSub);
            print_r('CAMPA OPTIMOVE 4');

            $Subproveedor = new Subproveedor($idSub);
            $Proveedor = new Proveedor($Subproveedor->proveedorId);
            print_r($Subproveedor);
            syslog(LOG_WARNING, "CAMPA OPTIMOVE 5: " . ($Subproveedor->abreviado));

            if (in_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)) {
                $responseBonoGlobal = $BonoInterno->bonoGlobal(
                    $Proveedor,
                    $BonoId,
                    $CONDGAME,
                    $Usuario->mandante,
                    $Usuario->usuarioId,
                    $Transaction,
                    0,
                    false,
                    0,
                    $BonoInterno->nombre,
                    $Prefix,
                    $MaxplayersCount
                );
            }

            if ($responseBonoGlobal["status"] != "ERROR") {
                $Transaction->commit();
            }
        }
        /**
         * Procesa bonos Freebet.
         */
        if ($BonoInterno->tipo == "6") {
            if ($insertBonus) {
                $Transaction = $BonoInternoMySqlDAO->getTransaction();

                $UsuarioBono = new UsuarioBono();
                $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                $UsuarioBono->setBonoId($BonoInterno->bonoId);
                $UsuarioBono->setValor(0);
                $UsuarioBono->setValorBono(0);
                $UsuarioBono->setValorBase(0);
                $UsuarioBono->setEstado('A');
                $UsuarioBono->setErrorId('0');
                $UsuarioBono->setIdExterno('0');
                $UsuarioBono->setMandante($BonoInterno->mandante);
                $UsuarioBono->setUsucreaId('0');
                $UsuarioBono->setUsumodifId('0');
                $UsuarioBono->setApostado('0');
                $UsuarioBono->setVersion('3');
                $UsuarioBono->setRollowerRequerido('0');
                $UsuarioBono->setCodigo('');
                $UsuarioBono->setExternoId('0');
                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                $Transaction->commit();
            }
        }
        /**
         * Procesa bonos de depósito.
         */
        if ($BonoInterno->tipo == "2") {
            if ($insertBonus) {
                $Transaction = $BonoInternoMySqlDAO->getTransaction();

                $UsuarioBono = new UsuarioBono();
                $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                $UsuarioBono->setBonoId($BonoInterno->bonoId);
                $UsuarioBono->setValor('0');
                $UsuarioBono->setValorBono('0');
                $UsuarioBono->setValorBase('0');
                $UsuarioBono->setEstado('P');
                $UsuarioBono->setErrorId('0');
                $UsuarioBono->setIdExterno('0');
                $UsuarioBono->setMandante($BonoInterno->mandante);
                $UsuarioBono->setUsucreaId('0');
                $UsuarioBono->setUsumodifId('0');
                $UsuarioBono->setApostado('0');
                $UsuarioBono->setVersion('3');
                $UsuarioBono->setRollowerRequerido('0');
                $UsuarioBono->setCodigo('');
                $UsuarioBono->setExternoId('0');
                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                $Transaction->commit();
            }
        }
        /**
         * Procesa bonos sin depósito con rollower requerido.
         */
        if ($BonoInterno->tipo == "3") {
            if ($insertBonus) {
                $Transaction = $BonoInternoMySqlDAO->getTransaction();

                try {
                    $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'VALORBONO');
                    $valor_bono = $BonoDetalleVALORBONO->valor;
                } catch (Exception $e) {
                }

                if ($valor_bono == '') {
                    $valor_bono = '0';
                }

                try {
                    $BonoDetalleWFACTORBONO = new BonoDetalle('', $BonoId, 'WFACTORBONO');
                    $rollowerBono = $BonoDetalleWFACTORBONO->valor;
                } catch (Exception $e) {
                }

                try {
                    $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'VALORROLLOWER');
                    $rollowerValor = $BonoDetalleROUNDSFREE->valor;
                } catch (Exception $e) {
                }
                $rollowerRequerido = 0;

                if ($rollowerBono) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);
                }
                if ($rollowerValor) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);
                }

                $codigoBono = 'CRM' . sprintf('%010d', rand(0, 9999));
                $UsuarioBono = new UsuarioBono();
                $UsuarioBono->setUsuarioId(0);
                $UsuarioBono->setBonoId($BonoInterno->bonoId);
                $UsuarioBono->setValor($valor_bono);
                $UsuarioBono->setValorBono($valor_bono);
                $UsuarioBono->setValorBase($valor_bono);
                $UsuarioBono->setEstado('L');
                $UsuarioBono->setErrorId('0');
                $UsuarioBono->setIdExterno('0');
                $UsuarioBono->setMandante($BonoInterno->mandante);
                $UsuarioBono->setUsucreaId('0');
                $UsuarioBono->setUsumodifId('0');
                $UsuarioBono->setApostado('0');
                $UsuarioBono->setVersion('3');
                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                $UsuarioBono->setCodigo($codigoBono);
                $UsuarioBono->setExternoId('0');
                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                $Transaction->commit();

                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                $BonoInterno = new BonoInterno();

                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,usuario.pais_id,usuario.moneda,usuario.mandante FROM registro
  INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN pais ON (pais.pais_id = usuario.pais_id)
  LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  LEFT OUTER JOIN departamento ON (ciudad.depto_id = departamento.depto_id) WHERE registro.usuario_id='" . $Usuario->usuarioId . "'";

                $dataUsuario = $BonoInterno->execQuery($transaccion, $usuarioSql);

                if ($dataUsuario[0]->{'usuario.mandante'} != "") {
                    $detalles = array(
                        "PaisUSER" => $dataUsuario[0]->{'usuario.pais_id'},
                        "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
                        "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},
                        "MonedaUSER" => $dataUsuario[0]->{'usuario.moneda'},
                        "ValorDeposito" => 0

                    );
                    $detalles = json_decode(json_encode($detalles));

                    $respuesta = $BonoInterno->agregarBonoFree(
                        $BonoId,
                        $Usuario->usuarioId,
                        $Usuario->mandante,
                        $detalles,
                        true,
                        $codigoBono,
                        $transaccion
                    );
                    $transaccion->commit();
                }
            }
        }
        /**
         * Procesa bonos FreeCasino.
         */
        if ($BonoInterno->tipo == "5") {
            if ($insertBonus) {
                $Transaction = $BonoInternoMySqlDAO->getTransaction();

                try {
                    $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'VALORBONO');
                    $valorBase = $BonoDetalleVALORBONO->valor;
                } catch (Exception $e) {
                }

                try {
                    $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'MINAMOUNT');
                    $valorBono = $BonoDetalleVALORBONO->valor;
                } catch (Exception $e) {
                }
                $UsuarioBono = new UsuarioBono;
                $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                $UsuarioBono->setBonoId($BonoInterno->bonoId);
                $UsuarioBono->setValor(0);
                $UsuarioBono->setValorBono($valorBono);
                $UsuarioBono->setValorBase($valorBono);
                $UsuarioBono->setEstado('A');
                $UsuarioBono->setErrorId('0');
                $UsuarioBono->setIdExterno('0');
                $UsuarioBono->setMandante($BonoInterno->mandante);
                $UsuarioBono->setUsucreaId('0');
                $UsuarioBono->setUsumodifId('0');
                $UsuarioBono->setApostado('0');
                $UsuarioBono->setVersion('3');
                $UsuarioBono->setRollowerRequerido('0');
                $UsuarioBono->setCodigo('');
                $UsuarioBono->setExternoId('0');
                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                $Transaction->commit();
            }
        }
    }
} catch (Exception $e) {
}
