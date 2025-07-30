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
class CronJobAAMonitorServer
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
        $outputString="*Date: (MonitorServer) * " . " - Fecha: " . date("Y-m-d H:i:s")."\n";
        // Ejecutar el comando completo en la shell
        $command = "top -b -n 1 | awk 'NR>7 {print $1, $9}' | sort -k2 -nr | head -n 5 | awk '{print $1}' | xargs -I {} ps -p {} -o pid,ppid,%cpu,etime,cmd";

        // Ejecutar el comando y almacenar cada línea de la salida en un arreglo
        exec($command, $output);
        //$this->SlackVS->sendMessage("*Date: (MonitorServer) * " . " - Fecha: " . date("Y-m-d H:i:s"));



// Filtrar las líneas y procesarlas
        $items = [];

        foreach ($output as $line) {
            // Ignorar las líneas de encabezado o vacías
            if (strpos($line, 'PID') !== false || empty(trim($line))) {
                continue;
            }

            // Separar la línea en columnas usando un espacio como delimitador
            $columns = preg_split('/\s+/', trim($line));

            // Si la línea tiene al menos 5 columnas (PID, PPID, %CPU, CMD, ELAPSED)
            if (count($columns) >= 5) {
                $items[] = [
                    'pid' => $columns[0],
                    'ppid' => $columns[1],
                    'cpu' => $columns[2],
                    'cmd' => implode(' ', array_slice($columns, 4)), // Unir todas las columnas a partir de la columna 4
                    'elapsed' => $columns[3],
                ];
            }
        }

// Mostrar la información organizada
        echo "PID   PPID  %CPU  CMD                                   ELAPSED\n";
        echo "---------------------------------------------------------------\n";
        foreach ($items as $key=>$item) {
            $outputString.= "*(".$key.")=> * ".sprintf("%-6s %-6s %-5s %-35s %-10s\n", $item['pid'], $item['ppid'], $item['cpu'], $item['cmd'], $item['elapsed']);

        }
        $this->SlackVS->sendMessage($outputString);
        echo ($outputString);

    }
}

