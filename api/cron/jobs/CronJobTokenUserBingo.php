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
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;



/**
 * Clase 'CronJobTokenUserBingo'
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
class CronJobTokenUserBingo
{


    public function __construct()
    {
    }

    public function execute()
    {


        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();

//$respuesta = $IESGAMESSERVICES->Authenticate($Usuario);
        $Proveedor = new \Backend\dto\Proveedor("", "IESGAMES");

        $Producto = new \Backend\dto\Producto("", "IESGAMES", $Proveedor->getProveedorId());

        $sql = "
SELECT *
        FROM prodmandante_pais 
        WHERE prodmandante_pais.producto_id = $Producto->productoId
        AND prodmandante_pais.estado = 'A'
          AND prodmandante_pais.pais_id in(2,173,46,66,60)
";

        $ProdmandantePais = new \Backend\dto\ProdmandantePais();

        $ProdmandantePaisMySqlDAO = new \Backend\mysql\ProdmandantePaisMySqlDAO();
        $transaccion = $ProdmandantePaisMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();
        $data = $ProdmandantePais->execQuery($transaccion, $sql);

        foreach ($data as $datanum) {

            $paisId = $datanum->{'prodmandante_pais.pais_id'};
            $mandante = $datanum->{'prodmandante_pais.mandante'};
            $respuesta = $IESGAMESSERVICES->Authenticate($paisId, $mandante);
            $token = $respuesta->response;

            $sql = "UPDATE prodmandante_pais  SET extra_info  = '$token' WHERE pmandantepais_id = '" . $datanum->{'prodmandante_pais.pmandantepais_id'} . "' ";

            $ProdmandantePais->execQuery($transaccion, $sql);

        }


        $message = "*CRON: (TokenUserBingo) * " . " - Fecha: " . date("Y-m-d H:i:s");
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $transaccion->commit();

        $message = "*CRON: FIN (TokenUserBingo) * " . " - Fecha: " . date("Y-m-d H:i:s");


    }
}