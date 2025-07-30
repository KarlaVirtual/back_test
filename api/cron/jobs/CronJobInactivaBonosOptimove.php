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
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\LealtadInterna;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\crm\Optimove;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;



/**
 * Clase 'CronJobInactivaBonosOptimove'
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
class CronJobInactivaBonosOptimove
{


    public function __construct()
    {
    }

    public function execute()
    {

        $toDate = date('Y-m-d H:i:s');

        $fromDate = date('Y-m-d H:i:s', strtotime('-1 hour'));


        $sql = "SELECT  bono_interno.bono_id,bono_interno.mandante,bono_interno.pertenece_crm,bono_detalle.*
FROM bono_interno
INNER JOIN bono_detalle ON (bono_detalle.bono_id = bono_interno.bono_id)
        WHERE bono_interno.fecha_fin BETWEEN '$fromDate' AND '$toDate'
AND bono_interno.estado = 'I'
AND bono_interno.pertenece_crm = 'S'
AND bono_detalle.tipo = 'CONDPAISUSER'";

        $BonoInterno = new BonoInterno();


        $BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();

        $transaccion = $BonoInternoMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();
        $Bonos = $BonoInterno->execQuery($transaccion, $sql);

        $Bonos = json_decode(json_encode($Bonos), TRUE);

        $BonosTotal = array();
        $PaisId = "";
        foreach ($Bonos as $key => $value) {
            $array = array();

            if ($value["bono_detalle.tipo"] == "CONDPAISUSER") {

                $PaisId = $value["bono_detalle.valor"];


            }


            $BonoId = strval("B" . $value["bono_interno.bono_id"]);
            $Mandante = $value["bono_interno.mandante"];
            $IsCRM = $value["bono_interno.pertenece_crm"];
            array_push($BonosTotal, $BonoId);
        }

        if ($IsCRM == "S") {
            $Clasificador = new Clasificador("", "PROVCRM");


            $MandanteDetalle = new MandanteDetalle('', $Mandante, $Clasificador->clasificadorId, $PaisId, 'A');

            $Proveedor = new \Backend\dto\Proveedor($MandanteDetalle->valor);

            switch ($Proveedor->abreviado) {
                case "OPTIMOVE":


                    $Optimove = new Optimove();
                    $respon = $Optimove->DeletePromotions($Mandante, $PaisId, $BonosTotal);

                    break;

                case "FASTTRACK":

                    break;

                case "CRMPROPIO":


                    break;
            }

            //$transaccion->commit();
//print_r("exito");

        }


    }
}