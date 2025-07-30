<?php

/**
 * Este archivo contiene un script para procesar solicitudes HTTP y manejar errores
 * relacionados con la integración de la API del Casino Miravalle Palace.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $color               Variable que almacena un valor de color, generalmente en formato hexadecimal o RGB.
 * @var mixed $Type                Variable que almacena el tipo de un objeto o transacción.
 * @var mixed $Mandante            Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $Country             Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $name                Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $value               Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $groupby             Variable que define un criterio de agrupación en una consulta SQL.
 * @var mixed $sqlWhere            Variable que almacena condiciones para una consulta SQL.
 * @var mixed $sql                 Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $TotalTotal          Variable que almacena el total acumulado de una operación.
 * @var mixed $TypeDate            Variable que indica el tipo de fecha utilizada en un proceso.
 * @var mixed $State               Variable que almacena el estado general de un proceso o entidad.
 * @var mixed $IsUnique            Variable que indica si un valor es único en una colección de datos.
 * @var mixed $BonoInternoMySqlDAO Objeto que maneja operaciones de base de datos para bonos internos en MySQL.
 * @var mixed $Transaction         Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $BonoInterno         Variable que representa un bono interno en el sistema.
 * @var mixed $Resultado           Variable que almacena el resultado de una operación o consulta.
 * @var mixed $array               Variable que almacena una lista o conjunto de datos.
 * @var mixed $index               Variable que representa un índice en una estructura de datos.
 * @var mixed $item2               Variable que almacena un segundo elemento en una lista o estructura de datos.
 * @var mixed $response            Esta variable almacena la respuesta generada por una operación o petición.
 */

use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Backend\utils\SlackVS;


$_ENV['DB_HOST'] = $_ENV['DB_HOST_BACKUP'];

if ($_REQUEST["Sign"] == "8eWGT3838M8ihOfiX4pA5vd8acrWJMYu") {
    $redisParam = ['ex' => 18000000];

    $redisPrefix = "GetReportTotal+UID" . implode('&', $_REQUEST);

    $redis = RedisConnectionTrait::getRedisInstance(true);
    $SlackVS = new SlackVS('monitor-server');
    $SlackVS->sendMessage($redisPrefix);

    $color = 'green';
    $Type = $_REQUEST["Type"];
    $TypeSplit = '900';

    $dateFrom = date("Y-m-d 00:00:00", strtotime('-1 hour '));
    $dateTo = date("Y-m-d H:00:00");

    $TypeDate = $_REQUEST["TypeDate"];
    $updateKey = true;
    if ($TypeDate == 'h') {
        if ($redis != null) {
            $cachedValue = ($redis->get($redisPrefix));
            if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
                $dateFrom = explode('###', $cachedValue)[0];
                $dateTo = explode('###', $cachedValue)[1];

                $dateFrom2 = date("Y-m-d H:i:00", strtotime($dateTo));
                if (date("i") < 15) {
                    $dateTo2 = date("Y-m-d H:00:00");
                } elseif (date("i") < 30) {
                    $dateTo2 = date("Y-m-d H:15:00");
                } elseif (date("i") < 45) {
                    $dateTo2 = date("Y-m-d H:30:00");
                } else {
                    $dateTo2 = date("Y-m-d H:45:00");
                }

                if ($dateTo2 < date("Y-m-d H:i:00", strtotime('-5 minutes '))) {
                    $dateFrom = $dateFrom2;
                    $dateTo = $dateTo2;
                } else {
                    $updateKey = false;
                }
            } else {
                $dateFrom = date("Y-m-d 00:00:00");
                $dateTo = date("Y-m-d H:15:00");
            }
        } else {
            $dateFrom = date("Y-m-d H:00:00", strtotime('-1 hour '));
            $dateTo = date("Y-m-d H:00:00");
        }
    } else {
        $cachedValue = ($redis->get($redisPrefix));
        if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
            $dateFrom = explode('###', $cachedValue)[0];
            $dateTo = explode('###', $cachedValue)[1];

            $dateFrom2 = date("Y-m-d H:i:00", strtotime($dateTo));
            if (date("i") < 15) {
                $dateTo2 = date("Y-m-d H:00:00");
            } elseif (date("i") < 30) {
                $dateTo2 = date("Y-m-d H:15:00");
            } elseif (date("i") < 45) {
                $dateTo2 = date("Y-m-d H:30:00");
            } else {
                $dateTo2 = date("Y-m-d H:45:00");
            }

            if ($dateTo2 < date("Y-m-d H:i:00", strtotime('-5 minutes '))) {
                $updateKey = false;
                $dateFrom = $dateFrom2;
                $dateTo = $dateTo2;
            }
        } else {
            $dateFrom = date("Y-m-d H:00:00");
            $dateTo = date("Y-m-d H:15:00");
        }
    }

    if (date("Y-m-d H:i:s") < '2025-02-03 12:45:00' && ($Type == 'CantTotalAmountBetsCasino')) {
        if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
            $dateFrom = explode('###', $cachedValue)[0];
            $dateTo = explode('###', $cachedValue)[1];

            if (strpos($dateTo, '02-02') !== false) {
                $dateFrom = '2025-02-02 00:00:00';
                $dateTo = '2025-02-03 00:00:00';
            } elseif (strpos($dateTo, '02-03') !== false) {
                $dateFrom = '2025-02-03 00:00:00';
                $dateTo = date("Y-m-d H:15:00");
            } else {
                $dateFrom = '2025-02-01 00:00:00';
                $dateTo = '2025-02-02 00:00:00';
            }

            $TypeSplit = '86400';
        }
    }
    if (date("Y-m-d H:i:s") < '2025-02-03 12:45:00' && ($Type == 'CantTotalAmountWinsCasino')) {
        if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
            $dateFrom = explode('###', $cachedValue)[0];
            $dateTo = explode('###', $cachedValue)[1];

            if (strpos($dateTo, '02-02') !== false) {
                $dateFrom = '2025-02-02 00:00:00';
                $dateTo = '2025-02-03 00:00:00';
            } elseif (strpos($dateTo, '02-03') !== false) {
                $dateFrom = '2025-02-03 00:00:00';
                $dateTo = date("Y-m-d H:15:00");
            } else {
                $dateFrom = '2025-02-01 00:00:00';
                $dateTo = '2025-02-02 00:00:00';
            }

            $TypeSplit = '86400';
        }
    }

    if (date("Y-m-d H:i:s") < '2025-02-03 11:45:00' && ($Type == 'TotalRegisters' || $Type == 'Registers')) {
        if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
            $dateFrom = explode('###', $cachedValue)[0];
            $dateTo = explode('###', $cachedValue)[1];

            if (strpos($dateTo, '02-02') !== false) {
                $dateFrom = '2025-02-02 00:00:00';
                $dateTo = '2025-02-03 00:00:00';
            } elseif (strpos($dateTo, '02-03') !== false) {
                $dateFrom = '2025-02-03 00:00:00';
                $dateTo = date("Y-m-d H:15:00");
            } else {
                $dateFrom = '2025-02-01 00:00:00';
                $dateTo = '2025-02-02 00:00:00';
            }

            $TypeSplit = '86400';
        }
    }
    if (date("Y-m-d H:i:s") < '2025-02-03 11:45:00' && ($Type == 'GGRSportCurrency')) {
        if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
            $dateFrom = explode('###', $cachedValue)[0];
            $dateTo = explode('###', $cachedValue)[1];

            if (strpos($dateTo, '02-02') !== false) {
                $dateFrom = '2025-02-02 00:00:00';
                $dateTo = '2025-02-03 00:00:00';
            } elseif (strpos($dateTo, '02-03') !== false) {
                $dateFrom = '2025-02-03 00:00:00';
                $dateTo = date("Y-m-d H:15:00");
            } else {
                $dateFrom = '2025-02-01 00:00:00';
                $dateTo = '2025-02-02 00:00:00';
            }

            $TypeSplit = '86400';
        }
    }
    if (date("Y-m-d H:i:s") < '2025-02-03 13:45:00' && ($Type == 'GGRCasinoCurrency')) {
        if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
            $dateFrom = explode('###', $cachedValue)[0];
            $dateTo = explode('###', $cachedValue)[1];

            if (strpos($dateTo, '02-02') !== false) {
                $dateFrom = '2025-02-02 00:00:00';
                $dateTo = '2025-02-03 00:00:00';
            } elseif (strpos($dateTo, '02-03') !== false) {
                $dateFrom = '2025-02-03 00:00:00';
                $dateTo = date("Y-m-d H:15:00");
            } else {
                $dateFrom = '2025-02-01 00:00:00';
                $dateTo = '2025-02-02 00:00:00';
            }

            $TypeSplit = '86400';
        }
    }
    if ($redis != null) {
        if ($_REQUEST['wset'] != '1' && $updateKey) {
            $redis->set($redisPrefix, $dateFrom . '###' . $dateTo, $redisParam);
        }
    } else {
    }

    $arrayRedis = array(
        'CantTotalUserBetsSportsTotalTotal',
        'CantTotalUserBetsCasinoTotalTotal',
        'CantTotalUserBetsSportsCountryTotalTotal',
        'CantTotalUserBetsCasinoCountryTotalTotal'
    ,
        'CantTotalLoginUsersUniqueTotalTotal'
    ,
        'CantTotalLoginUsersUniqueByCountryTotalTotal'
    ,
        'CantTotalLoginErrorUsersUniqueTotalTotal'
    ,
        'CantTotalLoginErrorUsersUniqueByCountryTotalTotal',
        'CantTotalUserPingsTotalTotal',
        'CantTotalUserPingsPartnerTotalTotal',
        'CantTotalUserPingsPartnerCountryTotalTotal'

    );
    if ( ! in_array($Type, $arrayRedis)) {
        switch ($Type) {
            case "TotalRegisters":
                $color = 'blue';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "UPPER('Registros')";
                $value = "count(*)";
                $date = "DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by FLOOR(UNIX_TIMESTAMP(usuario.fecha_crea) / " . $TypeSplit . ")";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "count(*)";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP(usuario.fecha_crea) / " . $TypeSplit . ")";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "UPPER(usuario_link.nombre)";
                    $value = "count(*)";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP(usuario.fecha_crea) / " . $TypeSplit . ")";
                }
                $sqlWhere .= " AND usuario.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND usuario.fecha_crea < '{$dateTo}' ";


                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM registro
                    inner join usuario on usuario.usuario_id = registro.usuario_id
                    inner join pais on usuario.pais_id = pais.pais_id
                        inner join mandante on usuario.mandante = mandante.mandante
                        left outer join usuario_link on usuario_link.usulink_id = registro.link_id
                                       WHERE   1=1
                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;

            case "Registers":
                $color = 'blue';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "UPPER(mandante.descripcion)";
                $value = "count(*)";
                $date = "DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,FLOOR(UNIX_TIMESTAMP(usuario.fecha_crea) / " . $TypeSplit . ")";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario.fecha_crea) / " . $TypeSplit . ")";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "UPPER(usuario_link.nombre)";
                    $value = "count(*)";
                    $groupby = "group by usuario_link.link_id,FLOOR(UNIX_TIMESTAMP(usuario.fecha_crea) / " . $TypeSplit . ")";
                }
                $sqlWhere .= " AND usuario.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND usuario.fecha_crea < '{$dateTo}' ";

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM registro
                    inner join usuario on usuario.usuario_id = registro.usuario_id
                    inner join pais on usuario.pais_id = pais.pais_id
                        inner join mandante on usuario.mandante = mandante.mandante
     left outer join usuario_link on usuario_link.usulink_id = registro.link_id
                                       WHERE   1=1
                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;

            case "TotalAmountBetsSport":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "UPPER(mandante.descripcion)";
                $value = "SUM(it_ticket_enc.vlr_apuesta)";
                $date = "DATE_FORMAT(it_ticket_enc.fecha_crea_time, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,usuario.pais_id,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "SUM(it_ticket_enc.vlr_apuesta)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(it_ticket_enc.vlr_apuesta)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                }
                $sqlWhere .= " AND it_ticket_enc.fecha_crea_time >= '{$dateFrom}' ";
                $sqlWhere .= " AND it_ticket_enc.fecha_crea_time < '{$dateTo}' ";

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and it_ticket_enc.eliminado = 'N'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalAmountBetsSport":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "UPPER(mandante.descripcion)";
                $value = "count(*)";
                $date = "DATE_FORMAT(it_ticket_enc.fecha_crea_time, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                }
                $sqlWhere .= " AND it_ticket_enc.fecha_crea_time >= '{$dateFrom}' ";
                $sqlWhere .= " AND it_ticket_enc.fecha_crea_time < '{$dateTo}' ";

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and it_ticket_enc.eliminado = 'N'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";


                break;
            case "TotalAmountBetsSportCurrency":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion))";
                $value = "SUM(it_ticket_enc.vlr_apuesta)";
                $date = "DATE_FORMAT(it_ticket_enc.fecha_crea_time, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,usuario.pais_id,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(pais.pais_nom))";
                    $value = "SUM(it_ticket_enc.vlr_apuesta)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(it_ticket_enc.vlr_apuesta)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / " . $TypeSplit . ")";
                }
                $sqlWhere .= " AND it_ticket_enc.fecha_crea_time >= '{$dateFrom}' ";
                $sqlWhere .= " AND it_ticket_enc.fecha_crea_time < '{$dateTo}' ";

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and it_ticket_enc.eliminado = 'N'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;


            case "TotalAmountDeposits":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "UPPER(mandante.descripcion)";
                $value = "SUM(usuario_recarga.valor)";
                $date = "DATE_FORMAT(usuario_recarga.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "SUM(usuario_recarga.valor)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(usuario_recarga.valor)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                }
                $sqlWhere .= " AND usuario_recarga.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND usuario_recarga.fecha_crea < '{$dateTo}' ";

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_recarga
         inner join usuario on usuario.usuario_id = usuario_recarga.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and usuario_recarga.estado = 'A'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalAmountDeposits":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "UPPER(mandante.descripcion)";
                $value = "count(*)";
                $date = "DATE_FORMAT(usuario_recarga.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                }
                $sqlWhere .= " AND usuario_recarga.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND usuario_recarga.fecha_crea < '{$dateTo}' ";

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_recarga
         inner join usuario on usuario.usuario_id = usuario_recarga.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and usuario_recarga.estado = 'A'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "TotalAmountDepositstCurrency":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion))";
                $value = "SUM(usuario_recarga.valor)";
                $date = "DATE_FORMAT(usuario_recarga.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(pais.pais_nom))";
                    $value = "SUM(usuario_recarga.valor)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(usuario_recarga.valor)";
                    $groupby = "group by usuario.pais_id,FLOOR(UNIX_TIMESTAMP(usuario_recarga.fecha_crea) / " . $TypeSplit . ")";
                }
                $sqlWhere .= " AND usuario_recarga.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND usuario_recarga.fecha_crea < '{$dateTo}' ";

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_recarga
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and usuario_recarga.estado = 'A'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;


            case "TotalAmountWithdraws":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $TypeDate = $_REQUEST["TypeDate"];

                $name = "CONCAT(UPPER(mandante.descripcion),'-',UPPER(cuenta_cobro.estado))";
                $value = "SUM(cuenta_cobro.valor)";
                $date = "now()";
                $groupby = "group by mandante.mandante,usuario.pais_id,cuenta_cobro.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(cuenta_cobro.estado))";
                    $value = "SUM(cuenta_cobro.valor)";
                    $groupby = "group by usuario.pais_id,cuenta_cobro.estado";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(cuenta_cobro.estado))";
                    $value = "SUM(cuenta_cobro.valor)";
                    $groupby = "group by usuario.pais_id,cuenta_cobro.estado";
                }
                switch ($TypeDate) {
                    case "DateOTP":
                        $sqlWhere .= " AND cuenta_cobro.estado ='O' ";

                        break;
                    case "DatePaid":
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_pago, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_pago >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_pago < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_pago) / ' . $TypeSplit . ')';
                        break;
                    case "DateAction":
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_accion, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_accion >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_accion < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_accion) / ' . $TypeSplit . ')';

                        break;
                    default:
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalTotalAmountWithdraws":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate"];

                $name = "UPPER(cuenta_cobro.estado)";
                $value = "count(*)";
                $date = "now()";
                $groupby = "group by cuenta_cobro.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(cuenta_cobro.estado))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,cuenta_cobro.estado";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(cuenta_cobro.estado))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,cuenta_cobro.estado";
                }

                switch ($TypeDate) {
                    case "DateOTP":
                        $sqlWhere .= " AND cuenta_cobro.estado ='O' ";

                        break;
                    case "DatePaid":
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_pago, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_pago >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_pago < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_pago) / ' . $TypeSplit . ')';

                        break;
                    case "DateAction":
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_accion, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_accion >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_accion < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_accion) / ' . $TypeSplit . ')';

                        break;
                    default:
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal && $TypeDate != 'DateOTP') {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalAmountWithdraws":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate"];

                $name = "CONCAT(UPPER(mandante.descripcion),'-',UPPER(cuenta_cobro.estado))";
                $value = "count(*)";
                $date = "now()";
                $groupby = "group by mandante.mandante,cuenta_cobro.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(cuenta_cobro.estado))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,cuenta_cobro.estado";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(cuenta_cobro.estado))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,cuenta_cobro.estado";
                }
                $State = $_REQUEST["State"];
                if ($State == 'A') {
                    $sqlWhere .= " AND cuenta_cobro.estado ='A' ";
                }
                switch ($TypeDate) {
                    case "DateOTP":
                        $sqlWhere .= " AND cuenta_cobro.estado ='O' ";

                        break;
                    case "DatePaid":
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_pago, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_pago >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_pago < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_pago) / ' . $TypeSplit . ')';

                        break;
                    case "DateAction":
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_accion, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_accion >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_accion < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_accion) / ' . $TypeSplit . ')';

                        break;
                    default:
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "TotalAmountWithdrawsCurrency":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate"];

                $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),'-',UPPER(cuenta_cobro.estado))";
                $value = "SUM(cuenta_cobro.valor)";
                $date = "now()";
                $groupby = "group by mandante.mandante,usuario.pais_id,cuenta_cobro.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(pais.pais_nom),'-',UPPER(cuenta_cobro.estado))";
                    $value = "SUM(cuenta_cobro.valor)";
                    $groupby = "group by usuario.pais_id,cuenta_cobro.estado";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(cuenta_cobro.estado))";
                    $value = "SUM(cuenta_cobro.valor)";
                    $groupby = "group by usuario.pais_id,cuenta_cobro.estado";
                }

                switch ($TypeDate) {
                    case "DateOTP":
                        $sqlWhere .= " AND cuenta_cobro.estado ='O' ";

                        break;
                    case "DatePaid":
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_pago, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_pago >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_pago < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_pago) / ' . $TypeSplit . ')';

                        break;
                    case "DateAction":
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_accion, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_accion >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_accion < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_accion) / ' . $TypeSplit . ')';

                        break;
                    default:
                        $date = "DATE_FORMAT(cuenta_cobro.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND cuenta_cobro.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND cuenta_cobro.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(cuenta_cobro.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;


            case "TotalAmountLogCron":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $TypeDate = $_REQUEST["TypeDate"];

                $TypeData = $_REQUEST["TypeData"];
                $TypeState = $_REQUEST["TypeState"];

                $typename = 'valor_id1';

                $name = "UPPER({$typename})";
                $value = "COUNT(*)";
                $sqlWhere = '';

                switch ($TypeData) {
                    case "id1":
                        $typename = 'valor_id1';

                        break;
                    case "id2":
                        $typename = 'valor_id2';

                        break;
                    case "id1Cant":
                        $typename = 'valor_id1';
                        $value = "SUM(valor1)";
                        $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                        break;
                    case "id2Cant":
                        $typename = 'valor_id2';
                        $value = "SUM(valor2)";
                        $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                        break;
                    default:

                        break;
                }

                if ($TypeState != '') {
                    $sqlWhere .= " AND log_cron.estado='{$TypeState}' ";
                }

                $groupby = "group by {$typename}";


                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER({$typename}))";
                    $groupby = "group by {$typename}";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER({$typename}))";
                    $groupby = "group by {$typename}";
                }
                switch ($TypeDate) {
                    default:
                        $date = "DATE_FORMAT(log_cron.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND log_cron.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND log_cron.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(log_cron.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                if ($TypeData == 'id1Cant' || $TypeData == 'id2Cant') {
                    $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM log_cron
                                       WHERE   1=1        

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                } else {
                    $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1        

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                }

                break;

            case "TotalAmountBonus":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $TypeDate = $_REQUEST["TypeDate"];

                $name = "CONCAT(UPPER(mandante.descripcion),'-',UPPER(bono_log.tipo))";
                $value = "SUM(bono_log.valor)";
                $groupby = "group by mandante.mandante,usuario.pais_id,bono_log.tipo";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(bono_log.tipo))";
                    $value = "SUM(bono_log.valor)";
                    $groupby = "group by usuario.pais_id,bono_log.estado";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(bono_log.tipo))";
                    $value = "SUM(bono_log.valor)";
                    $groupby = "group by usuario.pais_id,bono_log.tipo";
                }
                switch ($TypeDate) {
                    default:
                        $date = "DATE_FORMAT(bono_log.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND bono_log.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND bono_log.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(bono_log.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM bono_log
         inner join usuario on usuario.usuario_id = bono_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1          AND bono_log.estado ='L'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalTotalAmountBonus":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate"];

                $name = "UPPER(bono_log.tipo)";
                $value = "count(*)";
                $groupby = "group by bono_log.tipo";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(bono_log.tipo))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,bono_log.tipo";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(bono_log.tipo))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,bono_log.tipo";
                }

                switch ($TypeDate) {
                    default:
                        $date = "DATE_FORMAT(bono_log.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND bono_log.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND bono_log.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(bono_log.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM bono_log
         inner join usuario on usuario.usuario_id = bono_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         AND bono_log.estado ='L'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalAmountBonus":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate"];

                $name = "CONCAT(UPPER(mandante.descripcion),'-',UPPER(bono_log.tipo))";
                $value = "count(*)";
                $groupby = "group by mandante.mandante,bono_log.tipo";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(bono_log.tipo))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,bono_log.tipo";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(bono_log.tipo))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,bono_log.tipo";
                }
                $State = $_REQUEST["State"];
                if ($State == 'A') {
                    $sqlWhere .= " AND bono_log.estado ='A' ";
                }
                switch ($TypeDate) {
                    default:
                        $date = "DATE_FORMAT(bono_log.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND bono_log.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND bono_log.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(bono_log.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM bono_log
         inner join usuario on usuario.usuario_id = bono_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1          AND bono_log.estado ='L'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "TotalAmountBonusCurrency":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate2 = $_REQUEST["TypeDate2"];

                $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),'-',UPPER(bono_log.estado))";
                $value = "SUM(bono_log.valor)";
                $groupby = "group by mandante.mandante,usuario.pais_id,bono_log.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(pais.pais_nom),'-',UPPER(bono_log.estado))";
                    $value = "SUM(bono_log.valor)";
                    $groupby = "group by usuario.pais_id,bono_log.estado";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(bono_log.estado))";
                    $value = "SUM(bono_log.valor)";
                    $groupby = "group by usuario.pais_id,bono_log.estado";
                }

                switch ($TypeDate) {
                    default:
                        $date = "DATE_FORMAT(bono_log.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND bono_log.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND bono_log.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(bono_log.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM bono_log
         inner join usuario on usuario.usuario_id = bono_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1          AND bono_log.estado ='L'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;


            case "TotalAmountUserBonus":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $TypeDate = $_REQUEST["TypeDate2"];

                $name = "CONCAT(UPPER(mandante.descripcion),'-',UPPER(usuario_bono.estado))";
                $value = "SUM(usuario_bono.valor)";
                $groupby = "group by mandante.mandante,usuario.pais_id,usuario_bono.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(usuario_bono.estado))";
                    $value = "SUM(usuario_bono.valor)";
                    $groupby = "group by usuario.pais_id,usuario_bono.estado";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(usuario_bono.estado))";
                    $value = "SUM(usuario_bono.valor)";
                    $groupby = "group by usuario.pais_id,usuario_bono.estado";
                }
                switch ($TypeDate) {
                    case 'R':
                        $date = "DATE_FORMAT(usuario_bono.fecha_modif, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_bono.fecha_modif >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_bono.fecha_modif < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_bono.fecha_modif) / ' . $TypeSplit . ')';

                        break;
                    default:
                        $date = "DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_bono.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_bono.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_bono.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_bono
         inner join usuario on usuario.usuario_id = usuario_bono.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1          

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalTotalAmountUserBonus":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate2"];

                $name = "UPPER(usuario_bono.estado)";
                $value = "count(*)";
                $groupby = "group by usuario_bono.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(usuario_bono.estado))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,usuario_bono.estado";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(usuario_bono.estado))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,usuario_bono.estado";
                }

                switch ($TypeDate) {
                    case 'R':
                        $date = "DATE_FORMAT(usuario_bono.fecha_modif, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_bono.fecha_modif >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_bono.fecha_modif < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_bono.fecha_modif) / ' . $TypeSplit . ')';

                        break;

                    default:
                        $date = "DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_bono.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_bono.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_bono.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_bono
         inner join usuario on usuario.usuario_id = usuario_bono.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalAmountUserBonus":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate2"];

                $name = "CONCAT(UPPER(mandante.descripcion),'-',UPPER(usuario_bono.estado))";
                $value = "count(*)";
                $groupby = "group by mandante.mandante,usuario_bono.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom),'-',UPPER(usuario_bono.estado))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,usuario_bono.estado";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(usuario_bono.estado))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id,usuario_bono.estado";
                }
                $State = $_REQUEST["State"];
                if ($State == 'A') {
                    $sqlWhere .= " AND usuario_bono.estado ='A' ";
                }
                switch ($TypeDate) {
                    case 'R':
                        $date = "DATE_FORMAT(usuario_bono.fecha_modif, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_bono.fecha_modif >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_bono.fecha_modif < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_bono.fecha_modif) / ' . $TypeSplit . ')';

                        break;
                    default:
                        $date = "DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_bono.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_bono.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_bono.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_bono
         inner join usuario on usuario.usuario_id = usuario_bono.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1          

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "TotalAmountUserBonusCurrency":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate2"];

                $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),'-',UPPER(usuario_bono.estado))";
                $value = "SUM(usuario_bono.valor)";
                $groupby = "group by mandante.mandante,usuario.pais_id,usuario_bono.estado";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(pais.pais_nom),'-',UPPER(usuario_bono.estado))";
                    $value = "SUM(usuario_bono.valor)";
                    $groupby = "group by usuario.pais_id,usuario_bono.estado";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',UPPER(usuario_bono.estado))";
                    $value = "SUM(usuario_bono.valor)";
                    $groupby = "group by usuario.pais_id,usuario_bono.estado";
                }

                switch ($TypeDate) {
                    case 'R':
                        $date = "DATE_FORMAT(usuario_bono.fecha_modif, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_bono.fecha_modif >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_bono.fecha_modif < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_bono.fecha_modif) / ' . $TypeSplit . ')';

                        break;

                    default:
                        $date = "DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_bono.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_bono.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_bono.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_bono
         inner join usuario on usuario.usuario_id = usuario_bono.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1          

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;

            case "CantTotalLogins":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate"];
                $IsUnique = $_REQUEST["IsUnique"];

                $name = "CONCAT(UPPER(mandante.descripcion))";
                $value = "count(*)";
                $groupby = "group by mandante.mandante";
                $sqlWhere = '';

                if ($IsUnique == '1') {
                    $value = "count(DISTINCT usuario.usuario_id)";
                }

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom))";
                    $value = "count(*)";

                    if ($IsUnique == '1') {
                        $value = "count(DISTINCT usuario.usuario_id)";
                    }

                    $groupby = "group by usuario.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "count(*)";

                    if ($IsUnique == '1') {
                        $value = "count(DISTINCT usuario.usuario_id)";
                    }

                    $groupby = "group by usuario.pais_id";
                }
                if ($_REQUEST['Type2'] == 'ByPartnerCountry') {
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "count(*)";

                    if ($IsUnique == '1') {
                        $value = "count(DISTINCT usuario.usuario_id)";
                    }

                    $groupby = "group by usuario.pais_id";
                }

                switch ($TypeDate) {
                    default:
                        $date = "DATE_FORMAT(usuario_log.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_log.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_log.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_log.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1           AND (usuario_log.tipo LIKE 'LOGIN%' AND usuario_log.tipo != 'LOGININCORRECTO')

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;

            case "CantTotalLoginsError":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeDate = $_REQUEST["TypeDate"];
                $IsUnique = $_REQUEST["IsUnique"];

                $name = "CONCAT(UPPER(mandante.descripcion))";
                $value = "count(*)";
                $groupby = "group by mandante.mandante";
                $sqlWhere = '';

                if ($IsUnique == '1') {
                    $value = "count(DISTINCT usuario.usuario_id)";
                }

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(UPPER(pais.pais_nom))";
                    $value = "count(*)";

                    if ($IsUnique == '1') {
                        $value = "count(DISTINCT usuario.usuario_id)";
                    }

                    $groupby = "group by usuario.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "count(*)";

                    if ($IsUnique == '1') {
                        $value = "count(DISTINCT usuario.usuario_id)";
                    }

                    $groupby = "group by usuario.pais_id";
                }

                if ($_REQUEST['Type2'] == 'ByPartnerCountry') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "count(*)";

                    if ($IsUnique == '1') {
                        $value = "count(DISTINCT usuario.usuario_id)";
                    }

                    $groupby = "group by usuario.pais_id";
                }

                switch ($TypeDate) {
                    default:
                        $date = "DATE_FORMAT(usuario_log.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                        $sqlWhere .= " AND usuario_log.fecha_crea >= '{$dateFrom}' ";
                        $sqlWhere .= " AND usuario_log.fecha_crea < '{$dateTo}' ";
                        $groupby .= ',FLOOR(UNIX_TIMESTAMP(usuario_log.fecha_crea) / ' . $TypeSplit . ')';

                        break;
                }

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1           AND ( usuario_log.tipo = 'LOGININCORRECTO')

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;

            case "TotalAmountWinsSport":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "UPPER(mandante.descripcion)";
                $value = "SUM(it_ticket_enc.vlr_premio)";
                $groupby = "group by mandante.mandante,usuario.pais_id";
                $sqlWhere = '';
                $date = "DATE_FORMAT(it_ticket_enc.fecha_cierre_time, '%Y-%m-%d %H:%i:%s')";

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "SUM(it_ticket_enc.vlr_premio)";
                    $groupby = "group by usuario.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(it_ticket_enc.vlr_premio)";
                    $groupby = "group by usuario.pais_id";
                }
                $sqlWhere .= " AND it_ticket_enc.fecha_cierre_time >= '{$dateFrom}' ";
                $sqlWhere .= " AND it_ticket_enc.fecha_cierre_time < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_cierre_time) / ' . $TypeSplit . ')';

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and it_ticket_enc.eliminado = 'N'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "TotalAmountWinsSportCurrency":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion))";
                $value = "SUM(it_ticket_enc.vlr_premio)";
                $groupby = "group by mandante.mandante,usuario.pais_id";
                $date = "DATE_FORMAT(it_ticket_enc.fecha_cierre_time, '%Y-%m-%d %H:%i:%s')";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(pais.pais_nom))";
                    $value = "SUM(it_ticket_enc.vlr_premio)";
                    $groupby = "group by usuario.pais_id";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(it_ticket_enc.vlr_premio)";
                    $groupby = "group by usuario.pais_id";
                }
                $sqlWhere .= " AND it_ticket_enc.fecha_cierre_time >= '{$dateFrom}' ";
                $sqlWhere .= " AND it_ticket_enc.fecha_cierre_time < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_cierre_time) / ' . $TypeSplit . ')';

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and it_ticket_enc.eliminado = 'N'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;
            case "CantTotalAmountWinsSport":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "UPPER(mandante.descripcion)";
                $value = "count(*)";
                $date = "DATE_FORMAT(it_ticket_enc.fecha_cierre_time, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "count(*)";
                    $groupby = "group by usuario.pais_id";
                }
                $sqlWhere .= " AND it_ticket_enc.fecha_cierre_time >= '{$dateFrom}' ";
                $sqlWhere .= " AND it_ticket_enc.fecha_cierre_time < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_cierre_time) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and it_ticket_enc.eliminado = 'N'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;

            case "CantTotalUserBetsSports":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }


                $name = "UPPER(mandante.descripcion)";
                $value = "COUNT(DISTINCT(it_ticket_enc.usuario_id))";
                $date = "DATE_FORMAT(it_ticket_enc.fecha_crea_time, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND it_ticket_enc.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "COUNT(DISTINCT(it_ticket_enc.usuario_id))";
                    $groupby = "group by pais.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND pais.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "COUNT(DISTINCT(it_ticket_enc.fecha_crea_time))";
                    $groupby = "group by pais.pais_id";
                }
                $sqlWhere .= " AND it_ticket_enc.fecha_crea_time >= '{$dateFrom}' ";
                $sqlWhere .= " AND it_ticket_enc.fecha_crea_time < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_crea_time) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1        

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";


                break;

            case "CantTotalUserBetsCasino":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }


                $name = "UPPER(mandante.descripcion)";
                $value = "COUNT(DISTINCT(transaccion_juego.usuario_id))";
                $date = "DATE_FORMAT(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND transaccion_juego.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "COUNT(DISTINCT(transaccion_juego.usuario_id))";
                    $groupby = "group by pais.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND pais.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "COUNT(DISTINCT(transaccion_juego.usuario_id))";
                    $groupby = "group by pais.pais_id";
                }
                $sqlWhere .= " AND transjuego_log.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND transjuego_log.fecha_crea < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(transjuego_log.fecha_crea) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
                                       WHERE   1=1        
                                           AND transjuego_log.tipo LIKE '%DEBIT%' AND transaccion_juego.tipo ='{$TypeBets}'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";


                break;

            case "TotalAmountBetsCasino":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }

                $name = "UPPER(mandante.descripcion)";
                $value = "SUM(reporte_casino_resumen.valor)";
                $date = "DATE_FORMAT(reporte_casino_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,reporte_casino_resumen.pais_id";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "SUM(reporte_casino_resumen.valor)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(reporte_casino_resumen.valor)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(reporte_casino_resumen.fecha_crea) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
                                       WHERE   1=1        
                                           AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='{$TypeBets}'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "CantTotalAmountBetsCasino":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }


                $name = "UPPER(mandante.descripcion)";
                $value = "SUM(reporte_casino_resumen.cantidad)";
                $date = "DATE_FORMAT(reporte_casino_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "SUM(reporte_casino_resumen.cantidad)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(reporte_casino_resumen.cantidad)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(reporte_casino_resumen.fecha_crea) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
                                       WHERE   1=1        
                                           AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='{$TypeBets}'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";


                break;
            case "TotalAmountBetsCasinoCurrency":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }


                $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(mandante.descripcion))";
                $value = "SUM(reporte_casino_resumen.valor)";
                $date = "DATE_FORMAT(reporte_casino_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,reporte_casino_resumen.pais_id";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.mandante IN ($Mandante) ";
                    $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(pais.pais_nom))";
                    $value = "SUM(reporte_casino_resumen.valor)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.pais_id IN ($Country) ";
                    $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(reporte_casino_resumen.valor)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(reporte_casino_resumen.fecha_crea) / ' . $TypeSplit . ')';

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM reporte_casino_resumen
                  inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante

                                       WHERE   1=1        
                                           AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='{$TypeBets}'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;

            case "GGRCasinoCurrency":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }

                $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(mandante.descripcion))";
                $value = "SUM(
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END
            
            )";
                $date = "DATE_FORMAT(reporte_casino_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,reporte_casino_resumen.pais_id";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.mandante IN ($Mandante) ";
                    $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(pais.pais_nom))";
                    $value = "SUM(
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            ENDcf
            
            )";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.pais_id IN ($Country) ";
                    $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END
            
            )";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(reporte_casino_resumen.fecha_crea) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
                                       WHERE   1=1        
                                            AND reporte_casino_resumen.transaccion_juego_tipo ='{$TypeBets}'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;


            case "CantTotalAmountWinsCasino":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }


                $name = "UPPER(mandante.descripcion)";
                $value = "SUM(reporte_casino_resumen.cantidad)";
                $date = "DATE_FORMAT(reporte_casino_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "SUM(reporte_casino_resumen.cantidad)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(reporte_casino_resumen.cantidad)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(reporte_casino_resumen.fecha_crea) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
                                       WHERE   1=1        
                                           AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='{$TypeBets}'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";


                break;

            case "TotalAmountWinsCasino":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }


                $name = "UPPER(mandante.descripcion)";
                $value = "SUM(reporte_casino_resumen.valor)";
                $date = "DATE_FORMAT(reporte_casino_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,reporte_casino_resumen.pais_id";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.mandante IN ($Mandante) ";
                    $name = "UPPER(pais.pais_nom)";
                    $value = "SUM(reporte_casino_resumen.valor)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.pais_id IN ($Country) ";
                    $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(reporte_casino_resumen.valor)";
                    $groupby = "group by usuario.pais_id";
                }
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(reporte_casino_resumen.fecha_crea) / ' . $TypeSplit . ')';

                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }

                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
                                       WHERE   1=1        
                                           AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='{$TypeBets}'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";

                break;
            case "TotalAmountWinsCasinoCurrency":
                $color = 'red';

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];
                $TypeBets = $_REQUEST["TypeBets"];

                if ($TypeBets == '') {
                    $TypeBets = 'NORMAL';
                }


                $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(mandante.descripcion))";
                $value = "SUM(reporte_casino_resumen.valor)";
                $date = "DATE_FORMAT(reporte_casino_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,reporte_casino_resumen.pais_id";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.mandante IN ($Mandante) ";
                    $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(pais.pais_nom))";
                    $value = "SUM(reporte_casino_resumen.valor)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND reporte_casino_resumen.pais_id IN ($Country) ";
                    $name = "CONCAT(reporte_casino_resumen.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(reporte_casino_resumen.valor)";
                    $groupby = "group by reporte_casino_resumen.pais_id";
                }
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea >= '{$dateFrom}' ";
                $sqlWhere .= " AND reporte_casino_resumen.fecha_crea < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(reporte_casino_resumen.fecha_crea) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
                                       WHERE   1=1        
                                           AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='{$TypeBets}'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;

            case "GGRSportCurrency":

                $Mandante = $_REQUEST["Partner"];
                $Country = $_REQUEST["Country"];

                $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion))";
                $value = "SUM(it_ticket_enc.vlr_apuesta - it_ticket_enc.vlr_premio)";
                $date = "DATE_FORMAT(it_ticket_enc.fecha_cierre_time, '%Y-%m-%d %H:%i:%s')";
                $groupby = "group by mandante.mandante,usuario.pais_id";
                $sqlWhere = '';

                $Mandante = $Mandante != '0' && $Mandante != '' ? intval($Mandante) : $Mandante;
                if ($Mandante != '') {
                    $sqlWhere .= " AND usuario.mandante IN ($Mandante) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(pais.pais_nom))";
                    $value = "SUM(it_ticket_enc.vlr_apuesta - it_ticket_enc.vlr_premio)";
                    $groupby = "group by usuario.pais_id";
                }

                $Country = $Country != '0' && $Country != '' ? intval($Country) : $Country;
                if ($Country != '') {
                    $sqlWhere .= " AND usuario.pais_id IN ($Country) ";
                    $name = "CONCAT(usuario.moneda,' ',UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                    $value = "SUM(it_ticket_enc.vlr_apuesta - it_ticket_enc.vlr_premio)";
                    $groupby = "group by usuario.pais_id";
                }
                $sqlWhere .= " AND it_ticket_enc.fecha_cierre_time >= '{$dateFrom}' ";
                $sqlWhere .= " AND it_ticket_enc.fecha_cierre_time < '{$dateTo}' ";
                $groupby .= ',FLOOR(UNIX_TIMESTAMP(it_ticket_enc.fecha_cierre_time) / ' . $TypeSplit . ')';


                $TotalTotal = $_REQUEST["TotalTotal"] == '1' ? true : false;
                if ($TotalTotal) {
                    $name = "UPPER('TOTALTOTAL')";
                    $groupby = "group by FLOOR(UNIX_TIMESTAMP" . explode('FLOOR(UNIX_TIMESTAMP', $groupby)[1];
                }
                $sql = " SELECT {$name} name, {$value} value, {$date} date
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
                                       WHERE   1=1         and it_ticket_enc.eliminado = 'N'

                                           {$sqlWhere}
                                           {$groupby}
                                       order by value desc;
";
                break;

            default:
                exit();
        }
        if ($_ENV['debug']) {
            print_r($sql);
        }


        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $Transaction = $BonoInternoMySqlDAO->getTransaction();

        $BonoInterno = new BonoInterno();
        $Resultado = $BonoInterno->execQuery($Transaction, $sql);

        if ($_ENV['debug']) {
            print_r('Resultado');
            print_r($Resultado);
        }
    } else {
        // Obtener el valor de la clave
        $Resultado = $redis->get($Type . '+' . $_REQUEST['Type2']);
        $Resultado = json_decode($Resultado);
    }
    switch ($_REQUEST['TypeG']) {
        case "ListFunel":
            $array = [];

            foreach ($Resultado as $index => $value) {
                $item2 = new stdClass();
                $item2->name = $value->{".name"};
                $item2->value = $value->{".value"};
                $item2->date = $value->{".date"};

                $item2->timestamp = strtotime($value->{".date"});

                array_push($array, $item2);
            }

            $response["valueNameHeader"] = "Registros";
            $response["valueHeader"] = "Registros";
            $response["color"] = $color;
            $response["data"] = $array;

            break;
        case "Counter":

            $array = [];

            foreach ($Resultado as $index => $value) {
                $item2 = new stdClass();
                $item2->value = intval($value->{".value"});
                $item2->date = $value->{".date"};
                $item2->timestamp = strtotime($value->{".date"});
                $array = $item2;
            }

            $response["postfix"] = "Registros";
            $response["color"] = $color;
            $response["data"] = $array;
            break;
    }
} else {
    $response["data"] = [];
}


