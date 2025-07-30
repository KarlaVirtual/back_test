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
class CronJobReportingAPIDashboardGecko
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
                'CantTotalUserPingsPartnerCountryTotalTotal'

            );
            //$NameReportTotal,$TypeG,$TypeDate2,$State,$IsUnique,$TypeDate,$TypeDate3,$TypeData,$TypeState,$Type2,$TypeBets,$TotalTotal22


            /*
             *
             * $TypeGroup:'',Partner,PartnerCountry
             * $TypeValue: COUNT,SUM
             * $TypeTotalDay: 1,''
             */
            $Types = array(


                "Registers" => array('General&COUNT&'),

                "FirstDeposits" => array('General&COUNT&'),
                "Logins" => array('General&COUNT&'),
                "LoginsError" => array('General&COUNT&'),
                "LoginsUnique" => array('General&COUNT&1', 'General&COUNT&'),
                "LoginsErrorUnique" => array('General&COUNT&1', 'General&COUNT&'),
                "UserBetsSports" => array('General&COUNT&'),
                "UserBetsSportsDateClosed" => array('General&COUNT&'),
                "UserBetsCasinoNORMAL" => array('General&COUNT&'),
                "UserBetsCasinoFREESPIN" => array('General&COUNT&'),
                "UserBetsCasinoFREECASH" => array('General&COUNT&'),
                "UserBonusCreated" => array('General&COUNT&'),
                "UserBonusRedimed" => array('General&COUNT&'),

                "BetsSport" => array('General&COUNT&'),
                "BetsSportDateClosed" => array('General&COUNT&'),
                "WinsSport" => array('General&COUNT&'),

                "BetsCasinoNORMAL" => array('General&COUNT&'),
                "BetsCasinoFREESPIN" => array('General&COUNT&'),
                "BetsCasinoFREECASH" => array('General&COUNT&'),

                "WinsCasinoNORMAL" => array('General&COUNT&'),
                "WinsCasinoFREESPIN" => array('General&COUNT&'),
                "WinsCasinoFREECASH" => array('General&COUNT&')

            );

            foreach ($Types as $Type => $Values) {
                if (oldCount($Values) == 0) {

                    // Crear un nuevo proceso hijo
                    $pid = pcntl_fork();
                    if ($pid == -1) {
                    } elseif ($pid == 0) {
                        $this->sendData($Type, '', '', '');
                        exit();
                    }

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
    public function sendData2($Type, $TypeGroup, $TypeValue, $TypeTotalDay)
    {


        $TypeDate = 'h';
        $redisParam = ['ex' => 18000000];

        $NameReportTotal = ($TypeTotalDay == '1' ? 'XDIA-' : '') . $TypeValue . '-' . $Type . '-' . $TypeGroup;
        $NameReportTotalDATA = ($TypeTotalDay == '1' ? 'XDIA-' : '') . $TypeValue . '-' . $Type;
        $redisPrefix = "Gecko" . "GetReportTotalV2+UID" . $NameReportTotal;
        print_r(PHP_EOL);
        print_r($redisPrefix);
        print_r(PHP_EOL);

        $redis = RedisConnectionTrait::getRedisInstance(true);

        $color = 'green';
        $TypeSplit = '60';

        $dateFrom = date("Y-m-d 00:00:00", strtotime('-1 hour '));
        $dateTo = date("Y-m-d H:00:00");

        $updateKey = true;


        /* $curl = curl_init();

         curl_setopt_array($curl, array(
             CURLOPT_URL => 'https://api.geckoboard.com/datasets/' . str_replace('-', '.', 'list-' . strtolower($NameReportTotalDATA)),
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'PUT',
             CURLOPT_POSTFIELDS => '{
   "fields": {

   "aed": {"type": "money", "name": "AED", "currency_code": "AED", "optional": true},
   "afn": {"type": "money", "name": "AFN", "currency_code": "AFN", "optional": true},
   "all": {"type": "money", "name": "ALL", "currency_code": "ALL", "optional": true},
   "amd": {"type": "money", "name": "AMD", "currency_code": "AMD", "optional": true},
   "ang": {"type": "money", "name": "ANG", "currency_code": "ANG", "optional": true},
   "aoa": {"type": "money", "name": "AOA", "currency_code": "AOA", "optional": true},
   "ars": {"type": "money", "name": "ARS", "currency_code": "ARS", "optional": true},
   "aud": {"type": "money", "name": "AUD", "currency_code": "AUD", "optional": true},
   "awg": {"type": "money", "name": "AWG", "currency_code": "AWG", "optional": true},
   "azn": {"type": "money", "name": "AZN", "currency_code": "AZN", "optional": true},
   "bam": {"type": "money", "name": "BAM", "currency_code": "BAM", "optional": true},
   "bbd": {"type": "money", "name": "BBD", "currency_code": "BBD", "optional": true},
   "bdt": {"type": "money", "name": "BDT", "currency_code": "BDT", "optional": true},
   "bgn": {"type": "money", "name": "BGN", "currency_code": "BGN", "optional": true},
   "bhd": {"type": "money", "name": "BHD", "currency_code": "BHD", "optional": true},
   "bif": {"type": "money", "name": "BIF", "currency_code": "BIF", "optional": true},
   "bmd": {"type": "money", "name": "BMD", "currency_code": "BMD", "optional": true},
   "bnd": {"type": "money", "name": "BND", "currency_code": "BND", "optional": true},
   "bob": {"type": "money", "name": "BOB", "currency_code": "BOB", "optional": true},
   "brl": {"type": "money", "name": "BRL", "currency_code": "BRL", "optional": true},
   "bsd": {"type": "money", "name": "BSD", "currency_code": "BSD", "optional": true},
   "btn": {"type": "money", "name": "BTN", "currency_code": "BTN", "optional": true},
   "bwp": {"type": "money", "name": "BWP", "currency_code": "BWP", "optional": true},
   "bzd": {"type": "money", "name": "BZD", "currency_code": "BZD", "optional": true},
   "cad": {"type": "money", "name": "CAD", "currency_code": "CAD", "optional": true},
   "cdf": {"type": "money", "name": "CDF", "currency_code": "CDF", "optional": true},
   "chf": {"type": "money", "name": "CHF", "currency_code": "CHF", "optional": true},
   "clp": {"type": "money", "name": "CLP", "currency_code": "CLP", "optional": true},
   "cny": {"type": "money", "name": "CNY", "currency_code": "CNY", "optional": true},
   "cop": {"type": "money", "name": "COP", "currency_code": "COP", "optional": true},
   "crc": {"type": "money", "name": "CRC", "currency_code": "CRC", "optional": true},
   "usd": {"type": "money", "name": "USD", "currency_code": "USD", "optional": true},
   "uyu": {"type": "money", "name": "UYU", "currency_code": "UYU", "optional": true},
   "uzs": {"type": "money", "name": "UZS", "currency_code": "UZS", "optional": true},
   "ves": {"type": "money", "name": "VES", "currency_code": "VES", "optional": true},
   "vnd": {"type": "money", "name": "VND", "currency_code": "VND", "optional": true},
   "vuv": {"type": "money", "name": "VUV", "currency_code": "VUV", "optional": true},
   "wst": {"type": "money", "name": "WST", "currency_code": "WST", "optional": true},
   "xaf": {"type": "money", "name": "XAF", "currency_code": "XAF", "optional": true},
   "xcd": {"type": "money", "name": "XCD", "currency_code": "XCD", "optional": true},
   "xof": {"type": "money", "name": "XOF", "currency_code": "XOF", "optional": true},
   "xpf": {"type": "money", "name": "XPF", "currency_code": "XPF", "optional": true},
   "yer": {"type": "money", "name": "YER", "currency_code": "YER", "optional": true},
   "zar": {"type": "money", "name": "ZAR", "currency_code": "ZAR", "optional": true},
   "zmw": {"type": "money", "name": "ZMW", "currency_code": "ZMW", "optional": true},
     "typegroup": {
       "type": "string",
       "name": "TypeGoup",
       "optional": false
     },
     "name": {
       "type": "string",
       "name": "Name",
       "optional": false
     },
     "total": {
       "type": "number",
       "name": "Total",
       "optional": true
     },
     "timestamp": {
       "type": "datetime",
       "name": "Date"
     }
   },
   "unique_by": ["typegroup","name","timestamp"]
 }',
             CURLOPT_HTTPHEADER => array(
                 'Content-Type: application/json',
                 'Authorization: Basic ZjU1NWMwZTY3N2UyNGU4YWY4ZDBhNTU2ODFiMmQxMjg6'
             ),
         ));

         $response = curl_exec($curl);
         echo $response;
                curl_close($curl);

        */


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.geckoboard.com/datasets/' . str_replace('-', '.', 'count-' . strtolower($NameReportTotalDATA)),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => '{
  "fields": {
  
    "typegroup": {
      "type": "string",
      "name": "TypeGoup",
      "optional": false
    },
    "type": {
      "type": "string",
      "name": "Type",
      "optional": false
    },
    "partnercountry": {
      "type": "string",
      "name": "PartnerCountry",
      "optional": false
    },
    "partner": {
      "type": "string",
      "name": "Partner",
      "optional": false
    },
    "country": {
      "type": "string",
      "name": "Country",
      "optional": false
    },
    "total": {
      "type": "number",
      "name": "Total",
      "optional": true
    },
    "timestamp": {
      "type": "datetime",
      "name": "Date"
    }
  },
  "unique_by": ["typegroup","partner","country","type","timestamp"]
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ZjU1NWMwZTY3N2UyNGU4YWY4ZDBhNTU2ODFiMmQxMjg6'
            ),
        ));


        $response = curl_exec($curl);
        echo $response;

        curl_close($curl);

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
        $redisPrefix = "Gecko" . "GetReportTotalV2+UID" . $NameReportTotal;
        print_r(PHP_EOL);
        print_r($redisPrefix);
        print_r(PHP_EOL);

        $redis = RedisConnectionTrait::getRedisInstance(true);

        $color = 'green';
        $TypeSplit = '60';

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
                    $dateTo2 = date("Y-m-d H:i:00", strtotime('-1 minutes '));
// Convertir las fechas a timestamp Unix
                    $timestampFrom = strtotime($dateFrom2);
                    $timestampTo = strtotime($dateTo2);

// Calcular la diferencia en segundos
                    $differenceInSeconds = abs($timestampFrom - $timestampTo);

                    if ($differenceInSeconds <= 60) {
                        return;
                    }
                    $dateFrom = $dateFrom2;
                    $dateTo = $dateTo2;

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
            "TotalUserPingsPartnerCountry"

        );
        if (!in_array($Type, $arrayRedis)) {

            $sqlWhere = '';

            $name = "UPPER('General')";
            $namePartner = "UPPER('General')";
            $nameCountry = "UPPER('General')";

            $TypeG = 'Counter';
            if ($TypeGroup == 'PartnerCountry') {

                $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                $namePartner = "UPPER(mandante.descripcion)";
                $nameCountry = "UPPER(pais.pais_nom)";
                $value = "count(*)";
                $groupby = "group by mandante.mandante,pais.pais_id";
                $namePartner = "UPPER(mandante.descripcion)";
                $nameCountry = "UPPER(pais.pais_nom)";
            }
            if ($TypeGroup == 'Partner') {

                $name = "UPPER(mandante.descripcion)";
                $value = "count(*)";
                $groupby = "group by mandante.mandante";
                $namePartner = "UPPER(mandante.descripcion)";
                $nameCountry = "UPPER(pais.pais_nom)";
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

                case "FirstDeposits":
                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "ur.fecha_crea";
                    $byMoneda = '';
                    $sqlWhere .= " AND ur2.usuario_id IS NULL ";

                    $sql = "
                    FROM usuario u
         JOIN pais pais ON u.pais_id = pais.pais_id
         JOIN mandante  ON u.mandante = mandante.mandante
         INNER JOIN usuario_recarga ur ON ur.usuario_id = u.usuario_id
         LEFT JOIN usuario_recarga ur2 ON u.usuario_id = ur2.usuario_id AND ur2.fecha_crea < ur.fecha_crea
                    
                    ";

                    break;
                case "FirstDepositsAmount":
                    $valueSUM = "ur.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "ur.fecha_crea";
                    $byMoneda = 'u.moneda';

                    $sql = "
                    FROM usuario u
         JOIN pais pais ON u.pais_id = pais.pais_id
         JOIN mandante  ON u.mandante = mandante.mandante
         INNER JOIN usuario_recarga ur ON ur.usuario_id = u.usuario_id
         LEFT JOIN usuario_recarga ur2 ON u.usuario_id = ur2.usuario_id AND ur2.fecha_crea < ur.fecha_crea
                    
                    ";

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
                case "WithdrawsDelete":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "cuenta_cobro.fecha_accion";
                    $byMoneda = 'usuario.moneda';
                    $sqlWhere .= " AND cuenta_cobro.estado ='E' ";

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

                case "LogCronBONOTOTAL":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "usuario_bono.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_bono.fecha_crea";

                    $sql = "
                    FROM usuario_bono
                        INNER JOIN log_cron on (log_cron.valor_id2 = usuario_bono.bono_id and log_cron.estado = 'TOTAL' and
                                 log_cron.tipo = 'agregarBonoBackground')

";
                    $TypeGroup = 'ValorId1';

                    $groupby = ' GROUP BY valor_id1,valor_id2';
                    break;
                case "LogCronBONOENVIADO":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "log_cron.valor1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                    $sql = "
                    FROM log_cron

";
                    $TypeGroup = 'ValorId1';

                    $groupby = ' GROUP BY valor_id1,valor_id2';
                    break;


                case "LogCronID1TOTAL":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                    $sql = "
                    FROM log_cron
";
                    $TypeGroup = 'ValorId1';

                    $groupby = ' GROUP BY valor_id1';
                    break;

                case "LogCronID2TOTAL":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                    $sql = "
                    FROM log_cron
";
                    $TypeGroup = 'ValorId2';
                    $groupby = ' GROUP BY valor_id2';

                    break;

                case "LogCronID1OK":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

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

                    $TypeGroup = 'ValorId1';
                    $groupby = ' GROUP BY valor_id1';

                    break;

                case "LogCronID2OK":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

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

                    $TypeGroup = 'ValorId2';
                    $groupby = ' GROUP BY valor_id2';

                    break;
                case "LogCronID1ERROR":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

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

                    $TypeGroup = 'ValorId1';
                    $groupby = ' GROUP BY valor_id1';

                    break;

                case "LogCronID2ERROR":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

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
                    $TypeGroup = 'ValorId2';

                    $groupby = ' GROUP BY valor_id2';

                    break;

                case "Bonus":

                    $valueSUM = "bono_log.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "bono_log.fecha_crea";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= " AND bono_log.estado ='L' ";
                    $sql = "
                    FROM bono_log
         inner join usuario on usuario.usuario_id = bono_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "BonusTypeState":

                    $TypeG = 'ListFunel';
                    $valueSUM = "bono_log.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "bono_log.fecha_crea";
                    $groupby = "group by bono_log.tipo,bono_log.estado";
                    $byMoneda = 'usuario.moneda';

                    $name = "CONCAT(UPPER('General'),'-',bono_log.tipo,'-',bono_log.estado)";

                    if ($TypeGroup == 'PartnerCountry') {
                        $TypeG = 'ListFunel';

                        $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',bono_log.tipo,'-',bono_log.estado)";
                        $groupby = "group by mandante.mandante,pais.pais_id,bono_log.tipo,bono_log.estado";
                    }
                    if ($TypeGroup == 'Partner') {
                        $TypeG = 'ListFunel';

                        $name = "CONCAT(UPPER(mandante.descripcion),'-',bono_log.tipo,'-',bono_log.estado)";
                        $groupby = "group by mandante.mandante,bono_log.tipo,bono_log.estado";
                    }

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

                    $valueSUM = "1";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
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
                case "BetsCasinoFREESPIN":

                    $valueSUM = "1";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
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
                case "BetsCasinoFREECASH":

                    $valueSUM = "1";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
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

                case "GGRCasinoNORMAL":




                    $valueSUM = "CASE 
            WHEN transjuego_log.tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN transjuego_log.tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
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


                case "GGRCasinoFREESPIN":


                    $valueSUM = "CASE 
            WHEN transjuego_log.tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN transjuego_log.tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
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


                case "GGRCasinoFREECASH":


                    $valueSUM = "CASE 
            WHEN transjuego_log.tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN transjuego_log.tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
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


                case "WinsCasinoNORMAL":

                    $valueSUM = "1";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%CREDIT%' AND transaccion_juego.tipo ='NORMAL' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
";


                    break;


                case "WinsCasinoFREESPIN":


                    $valueSUM = "1";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%CREDIT%' AND transaccion_juego.tipo ='FREESPIN' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
";

                    break;


                case "WinsCasinoFREECASH":

                    $valueSUM = "1";
                    $valueCOUNT = "transjuego_log.transjuegolog_id";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%CREDIT%' AND transaccion_juego.tipo ='FREECASH' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
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
                $TypeG = 'ListFunel';
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
            $sql = "SELECT {$name} name,{$namePartner} partner,{$nameCountry} country,
             
             
             {$value} value, {$date} date{$byMoneda}"
                .
                $sql
                . "    
                    WHERE   1=1
                    {$sqlWhere}
                    {$groupby}
                    order by value desc;
                    ";

            print_r($sql);

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
                "TotalUserPingsPartnerCountry" => 'CantTotalUserPingsPartnerCountryTotalTotal' . '+' . ''
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

                    $cont = 0;
                    foreach ($Resultado as $index => $value) {


                        $item2 = new stdClass();

                        $item2->type = '';

                        $item2->partner = $value->{".partner"};
                        $item2->partnercountry = $value->{".name"};
                        $item2->country = $value->{".country"};

                        if ($value->{".moneda"} != null && $value->{".moneda"} != '') {
                            $item2->{strtolower($value->{".moneda"})} = floatval($value->{".value"});

                        } else {
                            $item2->total = floatval($value->{".value"});
                        }
                        $item2->typegroup = $TypeGroup;
                        //$item2->name = $value->{".name"};

                        $item2->timestamp = date('Y-m-d\TH:i:sP', strtotime($value->{".date"}));

                        array_push($array, $item2);

                        $cont++;

                        if ($cont == 499) {
                            if (oldCount($array) > 0) {
                                // Inicializar la clase CurlWrapper
                                $curl = new CurlWrapper('https://api.geckoboard.com/datasets/list.' . str_replace('-', '.', strtolower($NameReportTotalDATA)) . '/data');

                                // Configurar opciones
                                $curl->setOptionsArray([
                                    CURLOPT_URL => 'https://api.geckoboard.com/datasets/list.' . str_replace('-', '.', strtolower($NameReportTotalDATA)) . '/data',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => json_encode(array('data' => $array)),
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json',
                                        'Authorization: Basic ZjU1NWMwZTY3N2UyNGU4YWY4ZDBhNTU2ODFiMmQxMjg6'
                                    ),
                                ]);

                                // Ejecutar la solicitud
                                $response = $curl->execute();
                                try {
                                    $response = json_decode($response);


                                    if ($response->error != '' && $response->error != null) {
                                        if ($response->error->message != '' && $response->error->message != null) {
                                            if ($response->error->message == 'You have exceeded the API rate limit of 60 requests per minute. Try sending data less frequently') {
                                                sleep(60);
                                                $response = $curl->execute();
                                                $response = json_decode($response);
                                                if ($response->error != '' && $response->error != null) {
                                                    if ($response->error->message != '' && $response->error->message != null) {
                                                        if ($response->error->message == 'You have exceeded the API rate limit of 60 requests per minute. Try sending data less frequently') {
                                                            sleep(60);
                                                            $response = $curl->execute();

                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } catch (Exception $e) {
                                }
                            }
                            $array = array();
                            $cont = 0;
                        }
                    }
                    if (oldCount($array) > 0) {
                        // Inicializar la clase CurlWrapper
                        $curl = new CurlWrapper('https://api.geckoboard.com/datasets/list.' . str_replace('-', '.', strtolower($NameReportTotalDATA)) . '/data');

                        // Configurar opciones
                        $curl->setOptionsArray([
                            CURLOPT_URL => 'https://api.geckoboard.com/datasets/list.' . str_replace('-', '.', strtolower($NameReportTotalDATA)) . '/data',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode(array('data' => $array)),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Basic ZjU1NWMwZTY3N2UyNGU4YWY4ZDBhNTU2ODFiMmQxMjg6'
                            ),
                        ]);

                        // Ejecutar la solicitud
                        $response = $curl->execute();

                        try {
                            $response = json_decode($response);


                            if ($response->error != '' && $response->error != null) {
                                if ($response->error->message != '' && $response->error->message != null) {
                                    if ($response->error->message == 'You have exceeded the API rate limit of 60 requests per minute. Try sending data less frequently') {
                                        sleep(60);
                                        $response = $curl->execute();
                                        $response = json_decode($response);
                                        if ($response->error != '' && $response->error != null) {
                                            if ($response->error->message != '' && $response->error->message != null) {
                                                if ($response->error->message == 'You have exceeded the API rate limit of 60 requests per minute. Try sending data less frequently') {
                                                    sleep(60);
                                                    $response = $curl->execute();

                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } catch (Exception $e) {
                        }

                    }


                    break;
                case "Counter":

                    $array = [];
                    $cont = 0;
                    print_r(oldCount($Resultado));


// Configuración de la conexión a PostgreSQL
                    $host = "aws-0-us-west-1.pooler.supabase.com";
                    $dbname = "postgres";
                    $user = "postgres.uruqgfsfodmdxlcysvuw";
                    $password = "w\$S1x4Ee1S18";
                    $port = "5432";

                    try {
                        // Conectar a la base de datos
                        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
                        $pdo = new PDO($dsn, $user, $password, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        ]);


                        // Nombre de la tabla
                        $tableName = $NameReportTotalDATA;
                        $tableName = str_replace('-','',$tableName);

                        // Verificar si la tabla existe y crearla si no
                        $createTableSQL = "CREATE TABLE IF NOT EXISTS reports.$tableName (
        id SERIAL PRIMARY KEY,
        partner TEXT,
        partnercountry TEXT,
        country TEXT,
        total DOUBLE PRECISION,
        type TEXT,
        typegroup TEXT,
        timestamp TIMESTAMP WITH TIME ZONE
    )";

                        $pdo->exec($createTableSQL);
                        print_r($createTableSQL);
                    } catch (PDOException $e) {
                        echo "Error al insertar datos: " . $e->getMessage();
                    }



                    foreach ($Resultado as $index => $value) {


                        $item2 = new stdClass();


                        $item2->partner = $value->{".partner"};
                        $item2->partnercountry = $value->{".name"};
                        $item2->country = $value->{".country"};

                        $item2->total = floatval($value->{".value"});

                        $item2->type = '';
                        $item2->typegroup = $TypeGroup;

                        $item2->timestamp = date('Y-m-d\TH:i:sP', strtotime($value->{".date"}));

                        array_push($array, $item2);
                        $cont++;





                    }

                    try {

                        // Preparar la consulta SQL de inserción
                        $sql = "INSERT INTO reports.$tableName (partner, partnercountry, country, total, type, typegroup, timestamp) 
            VALUES (:partner, :partnercountry, :country, :total, :type, :typegroup, :timestamp)";

                        $stmt = $pdo->prepare($sql);

                        // Recorrer el array e insertar cada elemento en la base de datos
                        foreach ($array as $item2) {
                            $stmt->execute([
                                ':partner' => $item2->partner,
                                ':partnercountry' => $item2->partnercountry,
                                ':country' => $item2->country,
                                ':total' => $item2->total,
                                ':type' => $item2->type,
                                ':typegroup' => $item2->typegroup,
                                ':timestamp' => $item2->timestamp
                            ]);
                        }

                        echo "Datos insertados correctamente en la tabla $tableName.";
                    } catch (PDOException $e) {
                        echo "Error al insertar datos: " . $e->getMessage();
                    }

            }

        }


    }

    public function sendCurlCOUNT($NameReportTotalDATA, $array)
    {

        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper('https://api.geckoboard.com/datasets/count.' . str_replace('-', '.', strtolower($NameReportTotalDATA)) . '/data');

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_URL => 'https://api.geckoboard.com/datasets/count.' . str_replace('-', '.', strtolower($NameReportTotalDATA)) . '/data',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array('data' => $array)),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ZjU1NWMwZTY3N2UyNGU4YWY4ZDBhNTU2ODFiMmQxMjg6'
            ),
        ]);
        print_r('https://api.geckoboard.com/datasets/count.' . str_replace('-', '.', strtolower($NameReportTotalDATA)) . '/data');
        print_r(json_encode(array('data' => $array)));

        // Ejecutar la solicitud
        $response = $curl->execute();
        print_r($response);


        try {
            $response = json_decode($response);
            if ($response->error != '' && $response->error != null) {
                if ($response->error->message != '' && $response->error->message != null) {
                    if ($response->error->message == 'You have exceeded the API rate limit of 60 requests per minute. Try sending data less frequently') {
                        $intent = true;
                        while ($intent) {
                            print_r('intento');
                            sleep(60);
                            $intent = false;
                            $response = $curl->execute();
                            print_r($response);
                            $response = json_decode($response);
                            try {
                                $response = json_decode($response);
                                if ($response->error != '' && $response->error != null) {
                                    if ($response->error->message != '' && $response->error->message != null) {
                                        if ($response->error->message == 'You have exceeded the API rate limit of 60 requests per minute. Try sending data less frequently') {
                                            $intent = true;

                                        }
                                    }
                                }

                            } catch (Exception $e) {
                            }

                        }
                    }
                }
            }
        } catch (Exception $e) {
        }
    }
}

