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
class CronJobReportingRedisPings
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
        $redis = RedisConnectionTrait::getRedisInstance(true);

        $_ENV['DB_HOST'] = $_ENV['DB_HOST_BACKUP'];


// 🚀 Configuración de Supabase
        $supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
        $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
        $today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
$url = "$supabase_url/rest/v1/rpc/get_unique_users_by_partner_country";
// Inicializar cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ]);

// Ejecutar la petición
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);
// 🔍 Verificar si la respuesta es válida
        if ($response === false) {
            die("Error obteniendo los datos.");
        }

        $Resultado = array();
// 📌 Convertir JSON a un array PHP
        $data = json_decode($response, true);
        foreach ($data as $row) {
            array_push($Resultado,
                array(
                    '.name' => strtoupper($row['partner']) . ' ' . strtoupper($row['country']),
            '.value' => $row['unique_users'],
                    '.date' => date("Y-m-d H:i:00")
                )
            );


        }
        $redisPrefix = 'CantTotalUserPingsPartnerCountryTotalTotal+'; // Valor por defecto

        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }






// 🚀 Configuración de Supabase
        $supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
        $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
        $today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
        $url = "$supabase_url/rest/v1/rpc/get_unique_users_by_partner";
// Inicializar cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ]);

// Ejecutar la petición
        $response = curl_exec($ch);
        curl_close($ch);

// 🔍 Verificar si la respuesta es válida
        if ($response === false) {
            die("Error obteniendo los datos.");
        }

        $Resultado = array();
// 📌 Convertir JSON a un array PHP
        $data = json_decode($response, true);
        foreach ($data as $row) {
            array_push($Resultado,
                array(
                    '.name' => strtoupper($row['partner']),
                    '.value' => $row['unique_users'],
                    '.date' => date("Y-m-d H:i:00")
                )
            );


        }
        $redisPrefix = 'CantTotalUserPingsPartnerTotalTotal+'; // Valor por defecto

        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }







// 🚀 Configuración de Supabase
        $supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
        $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
        $today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
        $url = "$supabase_url/rest/v1/rpc/get_unique_users";
// Inicializar cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ]);

// Ejecutar la petición
        $response = curl_exec($ch);
        curl_close($ch);

// 🔍 Verificar si la respuesta es válida
        if ($response === false) {
            die("Error obteniendo los datos.");
        }

        $Resultado = array();
// 📌 Convertir JSON a un array PHP
        $data = json_decode($response, true);
        foreach ($data as $row) {
            array_push($Resultado,
                array(
                    '.name' => strtoupper($row['partner']),
                    '.value' => $row['unique_users'],
                    '.date' => date("Y-m-d H:i:00")
                )
            );


        }
        $redisPrefix = 'CantTotalUserPingsTotalTotal+'; // Valor por defecto

        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }











        /*
         *
         * POR MINUTO
         *
         *
         */




// 🚀 Configuración de Supabase
        $supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
        $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
        $today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
        $url = "$supabase_url/rest/v1/rpc/get_unique_users_by_partner_country_5min";
// Inicializar cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ]);

// Ejecutar la petición
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);
// 🔍 Verificar si la respuesta es válida
        if ($response === false) {
            die("Error obteniendo los datos.");
        }

        $Resultado = array();
// 📌 Convertir JSON a un array PHP
        $data = json_decode($response, true);
        foreach ($data as $row) {
            array_push($Resultado,
                array(
                    '.name' => strtoupper($row['partner']) . ' ' . strtoupper($row['country']),
                    '.value' => $row['unique_users'],
                    '.date' => date("Y-m-d H:i:00")
                )
            );


        }
        $redisPrefix = 'CantTotalUserPingsPartnerCountry5minTotalTotal+'; // Valor por defecto

        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }






// 🚀 Configuración de Supabase
        $supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
        $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
        $today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
        $url = "$supabase_url/rest/v1/rpc/get_unique_users_by_partner_5min";
// Inicializar cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ]);

// Ejecutar la petición
        $response = curl_exec($ch);
        curl_close($ch);

// 🔍 Verificar si la respuesta es válida
        if ($response === false) {
            die("Error obteniendo los datos.");
        }

        $Resultado = array();
// 📌 Convertir JSON a un array PHP
        $data = json_decode($response, true);
        foreach ($data as $row) {
            array_push($Resultado,
                array(
                    '.name' => strtoupper($row['partner']),
                    '.value' => $row['unique_users'],
                    '.date' => date("Y-m-d H:i:00")
                )
            );


        }
        $redisPrefix = 'CantTotalUserPingsPartner5minTotalTotal+'; // Valor por defecto

        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }







// 🚀 Configuración de Supabase
        $supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
        $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
        $today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
        $url = "$supabase_url/rest/v1/rpc/get_unique_users_5min";
// Inicializar cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ]);

// Ejecutar la petición
        $response = curl_exec($ch);
        curl_close($ch);

// 🔍 Verificar si la respuesta es válida
        if ($response === false) {
            die("Error obteniendo los datos.");
        }

        $Resultado = array();
// 📌 Convertir JSON a un array PHP
        $data = json_decode($response, true);
        foreach ($data as $row) {
            array_push($Resultado,
                array(
                    '.name' => strtoupper($row['partner']),
                    '.value' => $row['unique_users'],
                    '.date' => date("Y-m-d H:i:00")
                )
            );


        }
        $redisPrefix = 'CantTotalUserPings5minTotalTotal+'; // Valor por defecto

        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }
    }
}

