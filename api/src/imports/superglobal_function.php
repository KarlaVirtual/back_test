<?php
class CurlWrapper {
    private $ch;
    private $url;
    private $options = [];
    private $response;
    private $proxy;
    private $logId;

    public function __construct($url) {
        $this->ch = curl_init($url);
        $this->url = $url;
    }

    public function setOption($option, $value) {
        curl_setopt($this->ch, $option, $value);
        $this->options[$option] = $value;
    }

    public function setOptionsArray($optionsArray) {
        if(strpos($this->url,'platipusgaming.com') !== false){

// Lista de proxies con sus credenciales
            $proxies = [
                ["proxy" => "ddc.oxylabs.io:8001", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8002", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8003", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8004", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8005", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8006", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8007", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8008", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8009", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8010", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8011", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8012", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8013", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8014", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8015", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8016", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8017", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8018", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8019", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="],
                ["proxy" => "ddc.oxylabs.io:8020", "auth" => "vsproxy_jRkf7:5DdZ8r035PJj="]
            ];

// Selecciona un proxy aleatorio
            $selectedProxy = $proxies[array_rand($proxies)];

            $optionsArray[CURLOPT_PROXY]=$selectedProxy["proxy"];
            $optionsArray[CURLOPT_PROXYUSERPWD]=$selectedProxy["auth"];
        }
        curl_setopt_array($this->ch, $optionsArray);

        $this->options = array_merge($this->options, $optionsArray);
    }

    public function execute() {
        // Registrar la solicitud antes del envío
        $this->logId = $this->storeLogInSupabase([
            'url' => $this->url,
            'options' => json_encode($this->options),
            'info' => '{}',
            'response' => '',
            'execution_time' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if(
            strpos($this->url,'platipusgaming.com') !== false

        ) {
            // Clave en Redis para almacenar la última solicitud a esta URL
            $redis_key = "last_request:" . ($this->url);
            $min_interval = 90.0; // 5000ms en segundos (5 segundos)
            $max_wait_time = 600; // 10 minutos en segundos

            if(strpos($this->url,'platipusgaming.com') !== false) {
                // Obtener URL del proveedor
                $provider_url = 'platipusgaming.com'.'##'.$this->proxy ;

                // Clave en Redis para almacenar la última solicitud a esta URL
                $redis_key = "last_request:" . ($provider_url);
                $min_interval = 90.0; // 9000ms en segundos (90 segundos)
                $max_wait_time = 600; // 10 minutos en segundos
            }

            if(strpos($this->url,'biahosted.com') !== false) {
                // Clave en Redis para almacenar la última solicitud a esta URL
                $redis_key = "last_request:" . ($provider_url);
                $min_interval = 5.0; // 500ms en segundos (90 segundos)
                $max_wait_time = 600; // 10 minutos en segundos
            }

            $redis = \Backend\utils\RedisConnectionTrait::getRedisInstance(true);

            $start_time = microtime(true); // Marca de tiempo al inicio

            // Esperar si es necesario para cumplir la regla de 1 solicitud cada 50ms
            while (true) {
                $last_request_time = $redis->get($redis_key);
                $current_time = microtime(true);

                // Verificar si ya pasó el tiempo máximo de espera (10 minutos)
                if (($current_time - $start_time) > $max_wait_time) {
                    return false;
                }

                if (!$last_request_time || ($current_time - $last_request_time) >= $min_interval) {
                    // Se puede enviar la solicitud
                    $redis->set($redis_key, $current_time);
                    break;
                } else {
                    // Esperar 10ms antes de volver a verificar
                    usleep(10000);
                }
            }
        }

        $MICROTIMERQ = microtime(true);
        $this->response = curl_exec($this->ch);
        $MICROTIMERQ2 = microtime(true);

        $info = curl_getinfo($this->ch);
        $this->logCurlRequest($info, $this->options, $this->response,$MICROTIMERQ2 - $MICROTIMERQ);
        return $this->response;
    }

    private function logCurlRequest($info, $options, $response,$time=0) {
        // Registro de la solicitud cURL
        $logEntry = [
            'url' => $this->url,
            'options' => $options,
            'info' => $info,
            'response' => $response
        ];

        // Escribir el log en un archivo

        $MICROTIMERQ = microtime(true);
        // Aquí puedes hacer lo que quieras con la salida capturada
        $arrayREQGEN = array(
            "TYPE" => "CURL", // Define el tipo según tu contexto
            "MICROTIME" => $MICROTIMERQ,
            "SERVER" => json_encode($_SERVER),
            "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
            "DATE" => date('Y-m-d H:i:s'),
            "DEVICE" => '',
            "BROWSER_VERSION" => '',
            "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
            "URL" => $this->url,
            "GETPARAMS" => json_encode($_GET),
            "INFO" => json_encode($info),
            "POSTBODY" => json_encode($options),
            "RESPONSE" => $response,
            "TIMERESPONSE" => $time,
            "STATUSCODE" => 0
        );
        try {
            fwriteCustom('RQQ_log_CURL'.'_'.$_SERVER['USER'].'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
        } catch (TypeError $e) {

        }catch (Exception $e) {

        }

    }

    public function __destruct() {
        curl_close($this->ch);
    }
}

if (strpos($_SERVER['SCRIPT_FILENAME'], 'backendprod/backend/backend/api') !== false) {


    error_reporting(E_ERROR);
    ini_set('display_errors', 'OFF');
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
        error_reporting(E_ALL);
        ini_set('display_errors', 'ON');
    }

    /**
     * @deprecated Temporary solution for count() TypeError on invalid countables
     */

    function oldCount($input)
    {
        return isset($input) && is_array($input) ? count($input) : 0;
    }

    function fwriteCustom($name, $log)
    {
        $nameFolder = $_SERVER['SCRIPT_FILENAME'];
        $nameFolder = str_replace('/home/', 'home/', $nameFolder);
        $nameFolder = str_replace('.php', '', $nameFolder);
        $nameFolder = str_replace('/', '-', $nameFolder);
        $fp = fopen('/home/logsgeneral/' . $nameFolder . '-' . $name, 'a');
        fwrite($fp, $log);
        fclose($fp);
    }

    if (strpos($_SERVER['SCRIPT_FILENAME'], 'autoload.php') === false && strpos($_SERVER['SCRIPT_FILENAME'], 'backend/api') !== false &&
        strpos($_SERVER['SCRIPT_FILENAME'], 'bin/composer') === false) {

        $dirbase = $_SERVER['DOCUMENT_ROOT'];
        if ($dirbase == '') {
            $dirbase = $_SERVER['PWD'];
        }
        $dirbase = (explode('backend/api', $dirbase)[0]) . 'backend/api';
        require_once $dirbase . '/vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable($dirbase);

        $dotenv->load();

//        $_ENV['SECRET_PASSPHRASE_LOGIN'] = 'L14m3O#r?5c2e!$g';
//        $_ENV['SECRET_PASSPHRASE_LASTNAME'] = 'Em4nTs4lOd1#l3p4';
//        $_ENV['SECRET_PASSPHRASE_GENDER'] = 'Gr3dn#?O$3=%g31p';
//        $_ENV['SECRET_PASSPHRASE_DOCUMENT'] = 'N51t4c1f#?$3d1Al';
//        $_ENV['SECRET_PASSPHRASE_ADDRESS'] = 'S#3rd?An51c$3=1d';
//        $_ENV['SECRET_PASSPHRASE_NAME']='Em4n#8b?5$8%4e5a';
//        $_ENV['SECRET_PASSPHRASE_PHONE']='En5hpl4e?Ra$u4rc';






        if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
            $_ENV['debug'] = true;
        }

        if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
            $_ENV["debugFixed2"] = '1';
        }

    }


    function startOutputCapture($callback)
    {
        ob_start();

        // Registrar una función de apagado que se llamará cuando el script termine
        register_shutdown_function(function () use ($callback) {
            $output = ob_get_clean(); // Captura y limpia el búfer
            $callback($output); // Llama a la función de callback con la salida capturada
        });
    }

    function getBrowserAndVersion($userAgent)
    {
        $browser = 'Unknown';
        $version = '';

        if (preg_match('/MSIE (.*?)\./', $userAgent, $matches)) {
            $browser = 'Internet Explorer';
            $version = $matches[1];
        } elseif (preg_match('/Firefox\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Firefox';
            $version = $matches[1];
        } elseif (preg_match('/OPR\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Opera';
            $version = $matches[1];
        } elseif (preg_match('/Chrome\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Chrome';
            $version = $matches[1];
        } elseif (preg_match('/Safari\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Safari';
            $version = $matches[1];
        }

        return [$browser, $version];
    }


    $MICROTIMERQ = microtime(true);
    $userAgentREQGEN = $_SERVER['HTTP_USER_AGENT'];
    list($browserREQGEN, $versionREQGEN) = getBrowserAndVersion($userAgentREQGEN);


    function processOutput($output)
    {
        global $MICROTIMERQ;
        global $browserREQGEN;
        global $browserREQGEN;
        global $userAgentREQGEN;
        global $versionREQGEN;
        try {

            $MICROTIMERQ2 = microtime(true);
            // Aquí puedes hacer lo que quieras con la salida capturada
            $arrayREQGEN = array(
                "TYPE" => "General", // Define el tipo según tu contexto
                "MICROTIME" => $MICROTIMERQ,
                "SERVER" => json_encode($_SERVER),
                "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                "DATE" => date('Y-m-d H:i:s'),
                "DEVICE" => preg_match('/mobile/i', $userAgentREQGEN) ? 'Mobile' : 'Desktop',
                "BROWSER_VERSION" => $browserREQGEN . ' ' . $versionREQGEN,
                "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                "URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                "GETPARAMS" => json_encode($_GET),
                "POSTBODY" => file_get_contents('php://input'),
                "RESPONSE" => $output,
                "TIMERESPONSE" => $MICROTIMERQ2 - $MICROTIMERQ,
                "STATUSCODE" => intval(http_response_code())
            );

            fwriteCustom('RQQ_log_'.$_SERVER['USER'].'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
        }catch (TypeError $e) {

        } catch (Exception $e) {

        }
        $output =ltrim($output);
        print_r($output);
    }

    startOutputCapture('processOutput');

    $arrayREQGEN = array(
        "TYPE" => "General", // Define el tipo según tu contexto
        "MICROTIME" => $MICROTIMERQ,
        "SERVER" => json_encode($_SERVER),
        "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
        "DATE" => date('Y-m-d H:i:s'),
        "DEVICE" => preg_match('/mobile/i', $userAgentREQGEN) ? 'Mobile' : 'Desktop',
        "BROWSER_VERSION" => $browserREQGEN . ' ' . $versionREQGEN,
        "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
        "URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        "GETPARAMS" => json_encode($_GET),
        "POSTBODY" => file_get_contents('php://input'),
        "RESPONSE" => '',
        "STATUSCODE" => intval(http_response_code())
    );

    fwriteCustom('RQQ_log_'.$_SERVER['USER'].'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");


} elseif (strpos($_SERVER['SCRIPT_FILENAME'], 'backendprodfinal/api') !== false) {


    error_reporting(E_ERROR);
    ini_set('display_errors', 'OFF');
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
        error_reporting(E_ALL);
        ini_set('display_errors', 'ON');
    }

    /**
     * @deprecated Temporary solution for count() TypeError on invalid countables
     */

    function oldCount($input)
    {
        return isset($input) && is_array($input) ? count($input) : 0;
    }

    function fwriteCustom($name, $log)
    {
        $nameFolder = $_SERVER['SCRIPT_FILENAME'];
        $nameFolder = str_replace('/home/', 'home/', $nameFolder);
        $nameFolder = str_replace('.php', '', $nameFolder);
        $nameFolder = str_replace('/', '-', $nameFolder);
        $fp = fopen('/home/logsgeneral/' . $nameFolder . '-' . $name, 'a');
        fwrite($fp, $log);
        fclose($fp);
    }

    if (strpos($_SERVER['SCRIPT_FILENAME'], 'autoload.php') === false && strpos($_SERVER['SCRIPT_FILENAME'], 'backendprodfinal/api') !== false &&
        strpos($_SERVER['SCRIPT_FILENAME'], 'bin/composer') === false) {

        $dirbase = $_SERVER['DOCUMENT_ROOT'];
        if ($dirbase == '') {
            $dirbase = $_SERVER['PWD'];
        }
        $dirbase = (explode('backendprodfinal/api', $dirbase)[0]) . 'backendprodfinal/api';
        require_once $dirbase . '/vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable($dirbase);

        $dotenv->load();

//        $_ENV['SECRET_PASSPHRASE_LOGIN'] = 'L14m3O#r?5c2e!$g';
//        $_ENV['SECRET_PASSPHRASE_LASTNAME'] = 'Em4nTs4lOd1#l3p4';
//        $_ENV['SECRET_PASSPHRASE_GENDER'] = 'Gr3dn#?O$3=%g31p';
//        $_ENV['SECRET_PASSPHRASE_DOCUMENT'] = 'N51t4c1f#?$3d1Al';
//        $_ENV['SECRET_PASSPHRASE_ADDRESS'] = 'S#3rd?An51c$3=1d';
//        $_ENV['SECRET_PASSPHRASE_NAME']='Em4n#8b?5$8%4e5a';
//        $_ENV['SECRET_PASSPHRASE_PHONE']='En5hpl4e?Ra$u4rc';

        if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
            $_ENV['debug'] = true;
        }

        if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
            $_ENV["debugFixed2"] = '1';
        }

    }


    function startOutputCapture($callback)
    {
        ob_start();

        // Registrar una función de apagado que se llamará cuando el script termine
        register_shutdown_function(function () use ($callback) {
            $output = ob_get_clean(); // Captura y limpia el búfer
            $callback($output); // Llama a la función de callback con la salida capturada
        });
    }

    function getBrowserAndVersion($userAgent)
    {
        $browser = 'Unknown';
        $version = '';

        if (preg_match('/MSIE (.*?)\./', $userAgent, $matches)) {
            $browser = 'Internet Explorer';
            $version = $matches[1];
        } elseif (preg_match('/Firefox\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Firefox';
            $version = $matches[1];
        } elseif (preg_match('/OPR\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Opera';
            $version = $matches[1];
        } elseif (preg_match('/Chrome\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Chrome';
            $version = $matches[1];
        } elseif (preg_match('/Safari\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Safari';
            $version = $matches[1];
        }

        return [$browser, $version];
    }


    $MICROTIMERQ = microtime(true);
    $userAgentREQGEN = $_SERVER['HTTP_USER_AGENT'];
    list($browserREQGEN, $versionREQGEN) = getBrowserAndVersion($userAgentREQGEN);


    function processOutput($output)
    {
        global $MICROTIMERQ;
        global $browserREQGEN;
        global $browserREQGEN;
        global $userAgentREQGEN;
        global $versionREQGEN;
        try {

            $MICROTIMERQ2 = microtime(true);
            // Aquí puedes hacer lo que quieras con la salida capturada
            $arrayREQGEN = array(
                "TYPE" => "General", // Define el tipo según tu contexto
                "MICROTIME" => $MICROTIMERQ,
                "SERVER" => json_encode($_SERVER),
                "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                "DATE" => date('Y-m-d H:i:s'),
                "DEVICE" => preg_match('/mobile/i', $userAgentREQGEN) ? 'Mobile' : 'Desktop',
                "BROWSER_VERSION" => $browserREQGEN . ' ' . $versionREQGEN,
                "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                "URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                "GETPARAMS" => json_encode($_GET),
                "POSTBODY" => file_get_contents('php://input'),
                "RESPONSE" => $output,
                "TIMERESPONSE" => $MICROTIMERQ2 - $MICROTIMERQ,
                "STATUSCODE" => intval(http_response_code())
            );

            fwriteCustom('RQQ_log_'.$_SERVER['USER'].'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
        }catch (TypeError $e) {

        } catch (Exception $e) {

        }
        $output =ltrim($output);
        print_r($output);
    }

    startOutputCapture('processOutput');

    $arrayREQGEN = array(
        "TYPE" => "General", // Define el tipo según tu contexto
        "MICROTIME" => $MICROTIMERQ,
        "SERVER" => json_encode($_SERVER),
        "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
        "DATE" => date('Y-m-d H:i:s'),
        "DEVICE" => preg_match('/mobile/i', $userAgentREQGEN) ? 'Mobile' : 'Desktop',
        "BROWSER_VERSION" => $browserREQGEN . ' ' . $versionREQGEN,
        "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
        "URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        "GETPARAMS" => json_encode($_GET),
        "POSTBODY" => file_get_contents('php://input'),
        "RESPONSE" => '',
        "STATUSCODE" => intval(http_response_code())
    );

    fwriteCustom('RQQ_log_'.$_SERVER['USER'] .'_' . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");


} elseif (strpos($_SERVER['SERVER_NAME'], 'localhost') !== false || strpos($_SERVER['SCRIPT_FILENAME'], 'localhost') !== false || strpos($_SERVER['SCRIPT_FILENAME'], '127.0.0.1') !== false) {

    error_reporting(E_ERROR);

    ini_set('display_errors', 'OFF');
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
        error_reporting(E_ALL);
        ini_set('display_errors', 'ON');
    }

    /**
     * @deprecated Temporary solution for count() TypeError on invalid countables
     */

    function oldCount($input)
    {
        return isset($input) && is_array($input) ? count($input) : 0;
    }

    function fwriteCustom($name, $log)
    {
        try {

            $nameFolder = $_SERVER['SCRIPT_FILENAME'];
            $nameFolder = str_replace('/home/', 'home/', $nameFolder);
            $nameFolder = str_replace('.php', '', $nameFolder);
            $fp = fopen(__DIR__ . $name, 'a');
            fwrite($fp, $log);
            fclose($fp);
        } catch (TypeError $e) {

        }catch (Exception $e) {

        }
    }



    function startOutputCapture($callback)
    {
        ob_start();

        // Registrar una función de apagado que se llamará cuando el script termine
        register_shutdown_function(function () use ($callback) {
            $output = ob_get_clean(); // Captura y limpia el búfer
            $callback($output); // Llama a la función de callback con la salida capturada
        });
    }

    function getBrowserAndVersion($userAgent)
    {
        $browser = 'Unknown';
        $version = '';

        if (preg_match('/MSIE (.*?)\./', $userAgent, $matches)) {
            $browser = 'Internet Explorer';
            $version = $matches[1];
        } elseif (preg_match('/Firefox\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Firefox';
            $version = $matches[1];
        } elseif (preg_match('/OPR\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Opera';
            $version = $matches[1];
        } elseif (preg_match('/Chrome\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Chrome';
            $version = $matches[1];
        } elseif (preg_match('/Safari\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Safari';
            $version = $matches[1];
        }

        return [$browser, $version];
    }



    function processOutput($output)
    {
        global $MICROTIMERQ;
        global $browserREQGEN;
        global $browserREQGEN;
        global $userAgentREQGEN;
        global $versionREQGEN;
        try {

            $MICROTIMERQ2 = microtime(true);
            // Aquí puedes hacer lo que quieras con la salida capturada
            $arrayREQGEN = array(
                "TYPE" => "General", // Define el tipo según tu contexto
                "MICROTIME" => $MICROTIMERQ,
                "SERVER" => json_encode($_SERVER),
                "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                "DATE" => date('Y-m-d H:i:s'),
                "DEVICE" => preg_match('/mobile/i', $userAgentREQGEN) ? 'Mobile' : 'Desktop',
                "BROWSER_VERSION" => $browserREQGEN . ' ' . $versionREQGEN,
                "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                "URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                "GETPARAMS" => json_encode($_GET),
                "POSTBODY" => file_get_contents('php://input'),
                "RESPONSE" => $output,
                "TIMERESPONSE" => $MICROTIMERQ2 - $MICROTIMERQ,
                "STATUSCODE" => intval(http_response_code())
            );

            fwriteCustom('RQQ_log_'.$_SERVER['USER'].'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
        }catch (TypeError $e) {

        } catch (Exception $e) {

        }
        $output =ltrim($output);
        print_r($output);
    }

    startOutputCapture('processOutput');

    if (strpos($_SERVER['SCRIPT_FILENAME'], 'autoload.php') === false && strpos($_SERVER['SCRIPT_FILENAME'], 'backend/api') !== false &&
        strpos($_SERVER['SCRIPT_FILENAME'], 'bin/composer') === false) {

        $dirbase = $_SERVER['SCRIPT_FILENAME'];
        if ($dirbase == '') {
            $dirbase = $_SERVER['PWD'];
        }
        $dirbase = (explode('backend/api', $dirbase)[0]) . 'backend/api';
        require(__DIR__ . '/../../vendor/autoload.php');

        $dotenv = Dotenv\Dotenv::createImmutable($dirbase);

        $dotenv->load();

        if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
            $_ENV['debug'] = true;
        }

        if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69XSqL') {
            $_ENV["debugFixed2"] = '1';
        }

    }


} else {

    error_reporting(E_ERROR);
    ini_set('display_errors', 'OFF');

    /**
     * @deprecated Temporary solution for count() TypeError on invalid countables
     */

    function oldCount($input)
    {
        return isset($input) && is_array($input) ? count($input) : 0;
    }


    function fwriteCustom($name, $log)
    {
        try {

            $nameFolder = $_SERVER['SCRIPT_FILENAME'];
            $nameFolder = str_replace('/home/', 'home/', $nameFolder);
            $nameFolder = str_replace('.php', '', $nameFolder);
            $nameFolder = str_replace('/', '-', $nameFolder);
            $nameFolder=$nameFolder.'-';
            if(strpos($name,'RQQ_log_') !== false){
                $nameFolder='';
                $dirPath='/home/logsgeneral/' . $nameFolder . $name;
            }
            $fp = fopen('/home/logsgeneral/' . $nameFolder . $name, 'a');
            fwrite($fp, $log);
            fclose($fp);
        } catch (TypeError $e) {

        } catch (Exception $e) {

        }
    }


    function startOutputCapture($callback)
    {
        ob_start();

        // Registrar una función de apagado que se llamará cuando el script termine
        register_shutdown_function(function () use ($callback) {
            $output = ob_get_clean(); // Captura y limpia el búfer
            $callback($output); // Llama a la función de callback con la salida capturada
        });
    }

    function getBrowserAndVersion($userAgent)
    {
        $browser = 'Unknown';
        $version = '';

        if (preg_match('/MSIE (.*?)\./', $userAgent, $matches)) {
            $browser = 'Internet Explorer';
            $version = $matches[1];
        } elseif (preg_match('/Firefox\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Firefox';
            $version = $matches[1];
        } elseif (preg_match('/OPR\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Opera';
            $version = $matches[1];
        } elseif (preg_match('/Chrome\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Chrome';
            $version = $matches[1];
        } elseif (preg_match('/Safari\/(.*?)\./', $userAgent, $matches)) {
            $browser = 'Safari';
            $version = $matches[1];
        }

        return [$browser, $version];
    }


    $MICROTIMERQ = microtime(true);
    $userAgentREQGEN = $_SERVER['HTTP_USER_AGENT'];
    list($browserREQGEN, $versionREQGEN) = getBrowserAndVersion($userAgentREQGEN);


    function processOutput($output)
    {
        global $MICROTIMERQ;
        global $browserREQGEN;
        global $browserREQGEN;
        global $userAgentREQGEN;
        global $versionREQGEN;
        try {

            $MICROTIMERQ2 = microtime(true);
            // Aquí puedes hacer lo que quieras con la salida capturada
            $arrayREQGEN = array(
                "TYPE" => "General", // Define el tipo según tu contexto
                "MICROTIME" => $MICROTIMERQ,
                "SERVER" => json_encode($_SERVER),
                "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                "DATE" => date('Y-m-d H:i:s'),
                "DEVICE" => preg_match('/mobile/i', $userAgentREQGEN) ? 'Mobile' : 'Desktop',
                "BROWSER_VERSION" => $browserREQGEN . ' ' . $versionREQGEN,
                "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                "URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                "GETPARAMS" => json_encode($_GET),
                "POSTBODY" => file_get_contents('php://input'),
                "RESPONSE" => $output,
                "TIMERESPONSE" => $MICROTIMERQ2 - $MICROTIMERQ,
                "STATUSCODE" => intval(http_response_code())
            );

            fwriteCustom('RQQ_log_'.$_SERVER['USER'].'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
        }catch (TypeError $e) {

        } catch (Exception $e) {

        }
        $output =ltrim($output);
        print_r($output);
    }

    startOutputCapture('processOutput');

    $arrayREQGEN = array(
        "TYPE" => "General", // Define el tipo según tu contexto
        "MICROTIME" => $MICROTIMERQ,
        "SERVER" => json_encode($_SERVER),
        "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
        "DATE" => date('Y-m-d H:i:s'),
        "DEVICE" => preg_match('/mobile/i', $userAgentREQGEN) ? 'Mobile' : 'Desktop',
        "BROWSER_VERSION" => $browserREQGEN . ' ' . $versionREQGEN,
        "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
        "URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        "GETPARAMS" => json_encode($_GET),
        "POSTBODY" => file_get_contents('php://input'),
        "RESPONSE" => '',
        "STATUSCODE" => intval(http_response_code())
    );

    fwriteCustom('RQQ_log_'.$_SERVER['USER'].'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");


}


if ($_ENV['DB_HOST_BACKUP'] == null || $_ENV['DB_HOST_BACKUP'] == '') {
    $_ENV['DB_HOST_BACKUP'] = $_ENV['DB_HOST'];
}

// $_ENV['DB_NAME'] = 'casino_enc';



