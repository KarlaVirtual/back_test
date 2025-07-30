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
use Exception;
use Backend\utils\SlackVS;
use Backend\utils\BackgroundProcessVS;

use Backend\utils\RedisConnectionTrait;

/**
 * Clase 'CronJobAMonitorServer'
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
class CronJobReportingAPIDashboardRedis
{

    private $SlackVS;
    private $BackgroundProcessVS;

    public function __construct()
    {
        $this->SlackVS = new SlackVS('monitor-server');
        $this->BackgroundProcessVS = new BackgroundProcessVS();

    }

    public function execute()
    {
        $_ENV['DB_HOST'] = $_ENV['DB_HOST_BACKUP'];
        try {


            $Types = array(
                'TotalRegisters',
                'Registers',
                'TotalAmountBetsSport',
                'CantTotalAmountBetsSport',
                'TotalAmountBetsSportCurrency',
                'TotalAmountDeposits',
                'CantTotalAmountDeposits',
                'TotalAmountDepositstCurrency',
                'TotalAmountWithdraws',
                'CantTotalTotalAmountWithdraws',
                'CantTotalAmountWithdraws',
                'TotalAmountWithdrawsCurrency',
                'TotalAmountLogCron',
                'TotalAmountBonus',
                'CantTotalTotalAmountBonus',
                'TotalAmountLogCron',
                'TotalAmountBonus',
                'CantTotalTotalAmountBonus',
                'CantTotalAmountBonus',
                'TotalAmountBonusCurrency',
                'TotalAmountUserBonus',
                'CantTotalTotalAmountUserBonus',
                'CantTotalAmountUserBonus',
                'TotalAmountUserBonusCurrency',
                'CantTotalLogins',
                'CantTotalLoginsError',
                'TotalAmountWinsSport',
                'TotalAmountWinsSportCurrency',
                'CantTotalAmountWinsSport',
                'CantTotalUserBetsSports',
                'CantTotalUserBetsCasino',
                'TotalAmountBetsCasino',
                'TotalAmountWinsSport',
                'CantTotalAmountBetsCasino',
                'TotalAmountWinsSport',
                'TotalAmountBetsCasinoCurrency',
                'GGRCasinoCurrency',
                'CantTotalAmountWinsCasino',
                'TotalAmountWinsCasino',
                'TotalAmountWinsCasinoCurrency',
                'GGRSportCurrency',
                'CantTotalUserBetsSportsTotalTotal',
                'CantTotalUserBetsCasinoTotalTotal',
                'CantTotalUserBetsSportsCountryTotalTotal', 'CantTotalUserBetsCasinoCountryTotalTotal'
            , 'CantTotalLoginUsersUniqueTotalTotal'
            , 'CantTotalLoginUsersUniqueByCountryTotalTotal'
            , 'CantTotalLoginErrorUsersUniqueTotalTotal'
            , 'CantTotalLoginErrorUsersUniqueByCountryTotalTotal',
                'CantTotalUserPingsTotalTotal',
                'CantTotalUserPingsPartnerTotalTotal',
                'CantTotalUserPingsPartnerCountryTotalTotal',
                'CantTotalUserPings5minTotalTotal',
                'CantTotalUserPingsPartner5minTotalTotal',
                'CantTotalUserPingsPartnerCountry5minTotalTotal'

            );
            //$NameReportTotal,$TypeG,$TypeDate2,$State,$IsUnique,$TypeDate,$TypeDate3,$TypeData,$TypeState,$Type2,$TypeBets,$TotalTotal22


            /*
             *
             * $TypeGroup:'',Partner,PartnerCountry
             * $TypeValue: COUNT,SUM
             * $TypeTotalDay: 1,''
             */
            $Types = array(

                "TotalUserBetsSports" => array(''),
                "TotalUserBetsSportsFREEBET" => array(''),
                "TotalUserBetsSportsPartnerCountry" => array(''),
                "TotalUserBetsSportsPartnerCountryFREEBET" => array(''),
                "TotalUserBetsCasinoNORMAL" => array(''),
                "TotalUserBetsCasinoFREESPIN" => array(''),
                "TotalUserBetsCasinoFREECASH" => array(''),
                "TotalUserBetsCasinoPartnerCountryNORMAL" => array(''),
                "TotalUserBetsCasinoPartnerCountryFREESPIN" => array(''),
                "TotalUserBetsCasinoPartnerCountryFREECASH" => array(''),
                "TotalUserPings" => array(''),
                "TotalUserPingsPartner" => array(''),
                "TotalUserPingsPartnerCountry" => array(''),
                "TotalUserPings5min" => array(''),
                "TotalUserPingsPartner5min" => array(''),
                "TotalUserPingsPartnerCountry5min" => array(''),
                "TotalUserPings5minAVG" => array(''),
                "TotalUserPingsPartner5minAVG" => array(''),
                "TotalUserPingsPartnerCountry5minAVG" => array('')
            );


            foreach ($Types as $Type => $Values) {
                if (oldCount($Values) == 0) {
                    $this->sendData($Type, '', '', '');
                } else {
                    foreach ($Values as $value) {
                        $valueexplode = explode("&", $value);
                        $this->sendData($Type, $valueexplode[0], $valueexplode[1], $valueexplode[2]);

                    }
                }


            }

        } catch (Exception $e) {
            print_r($e);
        }
    }

    /*
     *
     * $TypeGroup:'',Partner,PartnerCountry
     * $TypeValue: COUNT,SUM
     * $TypeTotalDay: 1,''
     */
    public function sendData($Type, $TypeGroup, $TypeValue, $TypeTotalDay)
    {
        $TypeDate = 'h';
        $redisParam = ['ex' => 18000000];

        $NameReportTotal = ($TypeTotalDay == '1' ? 'XDIA-' : '') . $TypeValue . '-' . $Type . '-' . $TypeGroup;
        $NameReportTotalDATA = ($TypeTotalDay == '1' ? 'XDIA-' : '') . $TypeValue . '-' . $Type;
        $redisPrefix = "GetReportTotalV2+UID" . $NameReportTotal;
        print_r(PHP_EOL);
        print_r($redisPrefix);
        print_r(PHP_EOL);

        $redis = RedisConnectionTrait::getRedisInstance(true);

        $color = 'green';
        $TypeSplit = '900';

        $dateFrom = date("Y-m-d 00:00:00", strtotime('-1 hour '));
        $dateTo = date("Y-m-d H:00:00");

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
            "TotalUserBetsSports",
            "TotalUserBetsSportsFREEBET",
            "TotalUserBetsSportsPartnerCountry",
            "TotalUserBetsSportsPartnerCountryFREEBET",
            "TotalUserBetsCasinoNORMAL",
            "TotalUserBetsCasinoFREESPIN",
            "TotalUserBetsCasinoFREECASH",
            "TotalUserBetsCasinoPartnerCountryNORMAL",
            "TotalUserBetsCasinoPartnerCountryFREESPIN",
            "TotalUserBetsCasinoPartnerCountryFREECASH",
            "TotalUserPings",
            "TotalUserPingsPartner",
            "TotalUserPingsPartnerCountry",
            "TotalUserPings5min",
            "TotalUserPingsPartner5min",
            "TotalUserPingsPartnerCountry5min",
            "TotalUserPings5minAVG",
            "TotalUserPingsPartner5minAVG",
            "TotalUserPingsPartnerCountry5minAVG"

        );
        if (!in_array($Type, $arrayRedis)) {

            $sqlWhere = '';

            $name = "UPPER('General')";
            $TypeG = 'Counter';
            if ($TypeGroup == 'PartnerCountry') {
                $TypeG = 'ListFunel';

                $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                $value = "count(*)";
                $groupby = "group by mandante.mandante,pais.pais_id";
            }
            if ($TypeGroup == 'Partner') {
                $TypeG = 'ListFunel';

                $name = "UPPER(mandante.descripcion)";
                $value = "count(*)";
                $groupby = "group by mandante.mandante";
            }
            switch ($Type) {
                case "Registers":
                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario.fecha_crea";
                    $byMoneda = '';

                    $sql = "
                    FROM registro
                    inner join usuario on usuario.usuario_id = registro.usuario_id
                    inner join pais on usuario.pais_id = pais.pais_id
                    inner join mandante on usuario.mandante = mandante.mandante
                    left outer join usuario_link on usuario_link.usulink_id = registro.link_id";

                    break;


                case "Deposits":
                    $valueSUM = "usuario_recarga.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_recarga.fecha_crea";
                    $sqlWhere .= " AND usuario_recarga.estado = 'A' ";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM usuario_recarga
         inner join usuario on usuario.usuario_id = usuario_recarga.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;


                case "WithdrawsOTP":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "";
                    $sqlWhere .= " AND cuenta_cobro.estado ='O' ";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;

                case "WithdrawsPaid":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "cuenta_cobro.fecha_pago";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;

                case "WithdrawsAction":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "cuenta_cobro.fecha_accion";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;

                case "WithdrawsCreated":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "cuenta_cobro.fecha_crea";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;


                case "LogCronID1TOTAL":
                    $name = "UPPER(valor_id1)";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                    $sql = "
                    FROM log_cron
";

                    $groupby = ' GROUP BY valor_id1';
                    break;

                case "LogCronID2TOTAL":
                    $name = "UPPER(valor_id2)";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                    $sql = "
                    FROM log_cron
";
                    $groupby = ' GROUP BY valor_id2';

                    break;

                case "LogCronID1OK":
                    $name = "UPPER(valor_id1)";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='OK' ";

                    $sql = "
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;

                case "LogCronID2OK":
                    $name = "UPPER(valor_id2)";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='OK' ";

                    $sql = "
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;
                case "LogCronID1ERROR":
                    $name = "UPPER(valor_id1)";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='ERROR' ";

                    $sql = "
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;

                case "LogCronID2ERROR":
                    $name = "UPPER(valor_id2)";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='ERROR' ";

                    $sql = "
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;

                case "Bonus":

                    $valueSUM = "bono_log.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "bono_log.fecha_crea";
                    $groupby = "group by bono_log.tipo";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= " AND bono_log.estado ='L' ";
                    $sql = "
                    FROM bono_log
         inner join usuario on usuario.usuario_id = bono_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;


                case "UserBonusCreated":

                    $valueSUM = "usuario_bono.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_bono.fecha_modif";
                    $byMoneda = 'usuario.moneda';
                    $sql = "
                    FROM usuario_bono
         inner join usuario on usuario.usuario_id = usuario_bono.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;

                case "UserBonusRedimed":

                    $valueSUM = "usuario_bono.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_bono.fecha_modif";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= " AND usuario_bono.estado ='R' ";
                    $sql = "
                    FROM usuario_bono
         inner join usuario on usuario.usuario_id = usuario_bono.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "Logins":

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_log.fecha_crea";

                    $sqlWhere .= " AND (usuario_log.tipo LIKE 'LOGIN%' AND usuario_log.tipo != 'LOGININCORRECTO') ";
                    $sql = "
                   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;


                case "LoginsError":

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_log.fecha_crea";

                    $sqlWhere .= " AND ( usuario_log.tipo = 'LOGININCORRECTO') ";
                    $sql = "
                   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;

                case "LoginsUnique":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(usuario_log.usuario_id)";
                    $groupbyDateField = "usuario_log.fecha_crea";

                    $sqlWhere .= " AND (usuario_log.tipo LIKE 'LOGIN%' AND usuario_log.tipo != 'LOGININCORRECTO') ";
                    $sql = "
                   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;


                case "LoginsErrorUnique":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(usuario_log.usuario_id)";
                    $groupbyDateField = "usuario_log.fecha_crea";

                    $sqlWhere .= " AND ( usuario_log.tipo = 'LOGININCORRECTO') ";
                    $sql = "
                   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;

                case "BetsSport":

                    $valueSUM = "it_ticket_enc.vlr_apuesta";
                    $valueCOUNT = "*";
                    $groupbyDateField = "it_ticket_enc.fecha_crea_time";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "BetsSportDateClosed":

                    $valueSUM = "it_ticket_enc.vlr_apuesta";
                    $valueCOUNT = "*";
                    $groupbyDateField = "it_ticket_enc.fecha_cierre_time";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;

                case "WinsSport":

                    $valueSUM = "it_ticket_enc.vlr_premio";
                    $valueCOUNT = "*";
                    $groupbyDateField = "it_ticket_enc.fecha_cierre_time";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= "  AND it_ticket_enc.eliminado = 'N' ";
                    $sql = "
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "UserBetsSports":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(it_ticket_enc.usuario_id)";
                    $groupbyDateField = "it_ticket_enc.fecha_crea_time";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= "  AND it_ticket_enc.eliminado = 'N' ";
                    $sql = "
                   FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;
                case "UserBetsSportsDateClosed":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(it_ticket_enc.usuario_id)";
                    $groupbyDateField = "it_ticket_enc.fecha_cierre_time";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= "  AND it_ticket_enc.eliminado = 'N' ";
                    $sql = "
                   FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;
                case "UserBetsCasinoNORMAL":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(transaccion_juego.usuario_id)";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%DEBIT%' AND transaccion_juego.tipo ='NORMAL' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
";


                    break;
                case "UserBetsCasinoFREESPIN":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(transaccion_juego.usuario_id)";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%DEBIT%' AND transaccion_juego.tipo ='FREESPIN' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
";


                    break;
                case "UserBetsCasinoFREECASH":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(transaccion_juego.usuario_id)";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%DEBIT%' AND transaccion_juego.tipo ='FREECASH' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
";


                    break;


                case "BetsCasinoNORMAL":

                    $valueSUM = "reporte_casino_resumen.valor";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='NORMAL' ";

                    $sql = "
                     FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;
                case "BetsCasinoFREESPIN":

                    $valueSUM = "reporte_casino_resumen.valor";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='FREESPIN' ";

                    $sql = "
                     FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;
                case "BetsCasinoFREECASH":

                    $valueSUM = "reporte_casino_resumen.valor";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='FREECASH' ";

                    $sql = "
                     FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;

                case "GGRCasinoNORMAL":

                    $valueSUM = "
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END
            ";
                    $valueCOUNT = "*";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transaccion_juego_tipo ='NORMAL' ";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "GGRCasinoFREESPIN":

                    $valueSUM = "
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END
            ";
                    $valueCOUNT = "*";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transaccion_juego_tipo ='FREESPIN' ";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "GGRCasinoFREECASH":

                    $valueSUM = "
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END
            ";
                    $valueCOUNT = "*";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transaccion_juego_tipo ='FREECASH' ";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "WinsCasinoNORMAL":


                    $valueSUM = "
            reporte_casino_resumen.valor
            ";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= " 
                    AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='NORMAL'

";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "WinsCasinoFREESPIN":


                    $valueSUM = "
            reporte_casino_resumen.valor
            ";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= " 
                    AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='FREESPIN'

";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "WinsCasinoFREECASH":


                    $valueSUM = "
            reporte_casino_resumen.valor
            ";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= " 
                    AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='FREECASH'

";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;

                case "GGRSportCurrency":

                    $valueSUM = "
            it_ticket_enc.vlr_apuesta - it_ticket_enc.vlr_premio
            ";
                    $valueCOUNT = "*";
                    $groupbyDateField = "it_ticket_enc.fecha_cierre_time";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= " and it_ticket_enc.eliminado = 'N' ";

                    $sql = "
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;

                default:
                    continue;
            }
            if ($_ENV['debug']) {
                print_r($sql);
            }

            if ($groupbyDateField != '') {

                $date = "DATE_FORMAT({$groupbyDateField}, '%Y-%m-%d %H:%i:%s')";
            } else {
                $date = "DATE_FORMAT(now(), '%Y-%m-%d %H:%i:%s')";

            }

            if ($TypeValue == 'COUNT') {
                $value = "COUNT(" . $valueCOUNT . ")";
                if (strpos($valueCOUNT, 'SUM') !== false) {
                    $value = $valueCOUNT;
                }
                $byMoneda = '';
            }
            if ($TypeValue == 'SUM') {
                if ($byMoneda != '') {
                    $value = "SUM(" . $valueSUM . ")";

                } else {
                    $value = "SUM(" . $valueSUM . ")";

                }
            }

            if ($TypeTotalDay == '1') {
                if ($groupbyDateField != '') {
                    $sqlWhere .= " AND {$groupbyDateField} LIKE '" . date("Y-m-d", strtotime($dateFrom)) . "%' ";

                }

            } else {
                if ($groupbyDateField != '') {
                    $sqlWhere .= " AND {$groupbyDateField} >= '{$dateFrom}' ";
                    $sqlWhere .= " AND {$groupbyDateField} < '{$dateTo}' ";
                }
                if ($groupby != '') {
                    $groupby .= ",FLOOR(UNIX_TIMESTAMP({$groupbyDateField}) / " . $TypeSplit . ")";

                } else {
                    if ($groupbyDateField != '') {

                        $groupby .= " GROUP BY FLOOR(UNIX_TIMESTAMP({$groupbyDateField}) / " . $TypeSplit . ")";
                    }
                }

            }
            if ($byMoneda != '') {
                $byMoneda = ',UPPER(' . $byMoneda . ') moneda';
            }
            $sql = "SELECT {$name} name, {$value} value, {$date} date{$byMoneda}"
                .
                $sql
                . "    
                    WHERE   1=1
                    {$sqlWhere}
                    {$groupby}
                    order by value desc;
                    ";


            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
            $Transaction = $BonoInternoMySqlDAO->getTransaction();

            $BonoInterno = new BonoInterno();
            $Resultado = $BonoInterno->execQuery($Transaction, $sql);

            if ($_ENV['debug']) {
                print_r('Resultado');
                print_r($Resultado);
            }
        } else {
            $TypeG = 'Counter';

            if (in_array($Type, [
                "TotalUserBetsSportsPartnerCountry",
                "TotalUserBetsSportsPartnerCountryFREEBET",
                "TotalUserBetsCasinoPartnerCountryNORMAL",
                "TotalUserBetsCasinoPartnerCountryFREESPIN",
                "TotalUserBetsCasinoPartnerCountryFREECASH",
                "TotalUserPingsPartner",
                "TotalUserPingsPartnerCountry",
                "TotalUserPingsPartner5min",
                "TotalUserPingsPartnerCountry5min",
                "TotalUserPingsPartner5minAVG",
                "TotalUserPingsPartnerCountry5minAVG"
            ])) {
                $NameReportTotalDATA=str_replace('PartnerCountry','',$NameReportTotalDATA);
                $TypeG = 'ListFunel';
                $TypeGroup = 'PartnerCountry';
                if($Type =='TotalUserPingsPartner' || $Type =='TotalUserPingsPartner5min' || $Type =='TotalUserPingsPartner5minAVG'){
                    $NameReportTotalDATA=str_replace('Partner','',$NameReportTotalDATA);
                    $TypeGroup = 'Partner';
                }
            }

            // Definir el array de mappings
            $cases = [
                "TotalUserBetsSports" => 'CantTotalUserBetsSportsTotalTotal' . '+' . '',
                "TotalUserBetsSportsFREEBET" => 'CantTotalUserBetsSportsTotalTotal' . '+' . 'FREEBET',
                "TotalUserBetsSportsPartnerCountry" => 'CantTotalUserBetsSportsCountryTotalTotal' . '+' . '',
                "TotalUserBetsSportsPartnerCountryFREEBET" => 'CantTotalUserBetsSportsCountryTotalTotal' . '+' . 'FREEBET',
                "TotalUserBetsCasinoNORMAL" => 'CantTotalUserBetsCasinoTotalTotal' . '+' . 'NORMAL',
                "TotalUserBetsCasinoFREESPIN" => 'CantTotalUserBetsCasinoTotalTotal' . '+' . 'FREESPIN',
                "TotalUserBetsCasinoFREECASH" => 'CantTotalUserBetsCasinoTotalTotal' . '+' . 'FREECASH',
                "TotalUserBetsCasinoPartnerCountryNORMAL" => 'CantTotalUserBetsCasinoCountryTotalTotal' . '+' . 'NORMAL',
                "TotalUserBetsCasinoPartnerCountryFREESPIN" => 'CantTotalUserBetsCasinoCountryTotalTotal' . '+' . 'FREESPIN',
                "TotalUserBetsCasinoPartnerCountryFREECASH" => 'CantTotalUserBetsCasinoCountryTotalTotal' . '+' . 'FREECASH',
                "TotalUserPings" => 'CantTotalUserPingsTotalTotal' . '+' . '',
                "TotalUserPingsPartner" => 'CantTotalUserPingsPartnerTotalTotal' . '+' . '',
                "TotalUserPingsPartnerCountry" => 'CantTotalUserPingsPartnerCountryTotalTotal' . '+' . '',
                "TotalUserPings5min" => 'CantTotalUserPings5minTotalTotal' . '+' . '',
                "TotalUserPingsPartner5min" => 'CantTotalUserPingsPartner5minTotalTotal' . '+' . '',
                "TotalUserPingsPartnerCountry5min" => 'CantTotalUserPingsPartnerCountry5minTotalTotal' . '+' . '',
                "TotalUserPings5minAVG" => 'CantTotalUserPings5minTotalTotal' . '+' . '',
                "TotalUserPingsPartner5minAVG" => 'CantTotalUserPingsPartner5minTotalTotal' . '+' . '',
                "TotalUserPingsPartnerCountry5minAVG" => 'CantTotalUserPingsPartnerCountry5minTotalTotal' . '+' . ''

            ];

// Obtener el valor correspondiente usando el tipo $Type
            if (array_key_exists($Type, $cases)) {
                $key = $cases[$Type];
                $Resultado = $redis->get($key);
                $Resultado = json_decode($Resultado);
            } else {
                // Si no existe el tipo, manejar el caso de error
                $Resultado = null;  // O el valor que prefieras
            }
        }

        if (oldCount($Resultado) > 0) {
            if ($TypeGroup == '') {
                $TypeGroup = 'General';
            }
            switch ($TypeG) {
                case "ListFunel":
                    $array = [];

                    foreach ($Resultado as $index => $value) {

                        $item2 = new stdClass();

                        $item2->key = $NameReportTotalDATA;
                        $item2->value = $value->{".value"};
                        $item2->date = date('Y-m-d\TH:i:sP', strtotime($value->{".date"}));
                        $item2->unit = $value->{".moneda"};
                        $item3 = new stdClass();
                        $item3->key = $TypeGroup;
                        $item3->value = $value->{".name"};
                        $item2->attributes = array(
                            $item3
                        );
                        $item2->timestamp = strtotime($value->{".date"});

                        array_push($array, $item2);
                    }


                    if (oldCount($array) > 0) {


                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_USERPWD => '2uyhe2ia489apvmhawgipj1uismlj0fkyg0wf8d9piex1n07jg:',

                            CURLOPT_URL => 'https://push.databox.com/data',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($array),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Accept: application/vnd.databox.v2+json'

                            ),
                        ));
                        print_r($array);

                        $response = curl_exec($curl);

                        curl_close($curl);
                        print_r($NameReportTotal);
                        print_r(PHP_EOL);

                        echo $response;

                    }
                    break;
                case "Counter":

                    $array = [];

                    foreach ($Resultado as $index => $value) {

                        $item2 = new stdClass();

                        $item2->key = $NameReportTotalDATA;
                        $item2->value = $value->{".value"};
                        $item2->date = date('Y-m-d\TH:i:sP', strtotime($value->{".date"}));
                        $item2->unit = $value->{".moneda"};
                        $item3 = new stdClass();
                        $item3->key = $TypeGroup;
                        $item3->value = 'Data';
                        $item2->attributes = array(
                            $item3
                        );
                        $item2->timestamp = strtotime($value->{".date"});

                        array_push($array, $item2);
                    }
                    $arrayFinal=array();

                    foreach ($Resultado as $index => $value) {
                        if($arrayFinal[0] == null){
                            $arrayFinal[0]=new stdClass();

                        }

                        ($arrayFinal[0])->key = $NameReportTotalDATA;
                        if(($arrayFinal[0])->value == null){
                            ($arrayFinal[0])->value=0;
                        }
                        ($arrayFinal[0])->value = ($arrayFinal[0])->value + $value->{".value"};

                        ($arrayFinal[0])->date = date('Y-m-d\TH:i:sP', strtotime($value->{".date"}));
                        ($arrayFinal[0])->unit = $value->{".moneda"};
                        $item3 = new stdClass();
                        $item3->key = $TypeGroup;
                        $item3->value = 'Data';
                        ($arrayFinal[0])->attributes = array(
                            $item3
                        );
                        ($arrayFinal[0])->timestamp = strtotime($value->{".date"});


                    }
                    $array=$arrayFinal;
                    if (oldCount($array) > 0) {


                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_USERPWD => '2uyhe2ia489apvmhawgipj1uismlj0fkyg0wf8d9piex1n07jg:',

                            CURLOPT_URL => 'https://push.databox.com/data',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($array),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Accept: application/vnd.databox.v2+json',

                            ),
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                        print_r($NameReportTotal);
                        print_r(PHP_EOL);

                        echo $response;
                    }
            }

        }


    }
}

