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


date_default_timezone_set('America/Lima');
require_once __DIR__ . '../../vendor/autoload.php';
///home/devadmin/api/api/
header('Content-type: application/json; charset=utf-8');
ini_set('memory_limit', '-1');
/*$hour = date('H');
if(intval($hour)>9){
    exit();
}*/

/*
$UsuarioId = 886;
$Abreviado = "LOGINCRM";
$IdMovimiento = 886;
$Server = "eyJSRURJUkVDVF9VTklRVUVfSUQiOiJaQ1cxWkx4dk9acmdZQGR1T0FOTVF3QUFBQUEiLCJSRURJUkVDVF9TVEFUVVMiOiIyMDAiLCJVTklRVUVfSUQiOiJaQ1cxWkx4dk9acmdZQGR1T0FOTVF3QUFBQUEiLCJIVFRQX0hPU1QiOiJhcGlkZXYudmlydHVhbHNvZnQudGVjaCIsIkhUVFBfQUNDRVBUX0VOQ09ESU5HIjoiZ3ppcCIsIkhUVFBfWF9GT1JXQVJERURfRk9SIjoiMTkwLjI0OS4yNDYuMTYsIDE2Mi4xNTguMTc1Ljk0IiwiSFRUUF9DRl9SQVkiOiI3YjAxYTU1M2Y4MjM2N2Q0LURGVyIsIkNPTlRFTlRfTEVOR1RIIjoiMTQ1IiwiSFRUUF9YX0ZPUldBUkRFRF9QUk9UTyI6Imh0dHBzIiwiSFRUUF9DRl9WSVNJVE9SIjoie1wic2NoZW1lXCI6XCJodHRwc1wifSIsIkhUVFBfQVVUSE9SSVRZIjoiYXBpZGV2LnZpcnR1YWxzb2Z0LnRlY2giLCJIVFRQX0FDQ0VQVCI6ImFwcGxpY2F0aW9uXC9qc29uLCB0ZXh0XC9wbGFpbiwgKlwvKiIsIkhUVFBfQUNDRVBUX0xBTkdVQUdFIjoiZW4tVVMsZW47cT0wLjksZXMtVVM7cT0wLjgsZXM7cT0wLjciLCJDT05URU5UX1RZUEUiOiJhcHBsaWNhdGlvblwvanNvbiIsIkhUVFBfT1JJR0lOIjoiaHR0cHM6XC9cL2RldmZyb250ZW5kLnZpcnR1YWxzb2Z0LnRlY2giLCJIVFRQX1JFRkVSRVIiOiJodHRwczpcL1wvZGV2ZnJvbnRlbmQudmlydHVhbHNvZnQudGVjaFwvIiwiSFRUUF9TRUNfQ0hfVUEiOiJcIkdvb2dsZSBDaHJvbWVcIjt2PVwiMTExXCIsIFwiTm90KEE6QnJhbmRcIjt2PVwiOFwiLCBcIkNocm9taXVtXCI7dj1cIjExMVwiIiwiSFRUUF9TRUNfQ0hfVUFfTU9CSUxFIjoiPzAiLCJIVFRQX1NFQ19DSF9VQV9QTEFURk9STSI6IlwiV2luZG93c1wiIiwiSFRUUF9TRUNfRkVUQ0hfREVTVCI6ImVtcHR5IiwiSFRUUF9TRUNfRkVUQ0hfTU9ERSI6ImNvcnMiLCJIVFRQX1NFQ19GRVRDSF9TSVRFIjoic2FtZS1zaXRlIiwiSFRUUF9TV0FSTV9TRVNTSU9OIjoiIiwiSFRUUF9VU0VSX0FHRU5UIjoiTW96aWxsYVwvNS4wIChXaW5kb3dzIE5UIDEwLjA7IFdpbjY0OyB4NjQpIEFwcGxlV2ViS2l0XC81MzcuMzYgKEtIVE1MLCBsaWtlIEdlY2tvKSBDaHJvbWVcLzExMS4wLjAuMCBTYWZhcmlcLzUzNy4zNiIsIkhUVFBfQ0FDSEVfQ09OVFJPTCI6Im5vLWNhY2hlIiwiSFRUUF9QT1NUTUFOX1RPS0VOIjoiZGFlNDk1NDAtNDg4Ni00ODBkLWE3ZjctYjFlZjVjODM4ODUyIiwiSFRUUF9DT09LSUUiOiJTZXNzaW9uTmFtZT1lYzRjODg4ZTc2ZjVhZmM1NDk1ZDY0YzAwNjg1N2QyMTsgX19jZl9ibT1NREczbVIzWUJQaW9va19ITjFRVjlSQmlrekZiYVZnSWdYVWpqdWp4R2xFLTE2ODAxOTI1MzctMC1BVmQyZEJoTTJVWnN5c294YzhuZ0ZRRTl2XC9lRzVYdURJWEhiVkpva1dYZ050VnBTWDZEVldBQU44OVhsV2Z4RFo4YjdFRCtsdU44Z3JvQnpVREtzcXNvPSIsIkhUVFBfQ0ZfQ09OTkVDVElOR19JUCI6IjE5MC4yNDkuMjQ2LjE2IiwiSFRUUF9UUlVFX0NMSUVOVF9JUCI6IjE5MC4yNDkuMjQ2LjE2IiwiSFRUUF9DRl9JUENPVU5UUlkiOiJDTyIsIkhUVFBfQ0ROX0xPT1AiOiJjbG91ZGZsYXJlIiwiSFRUUF9YX0NMSUVOVF9JUCI6IjE2Mi4xNTguMTc1Ljk0IiwiSFRUUF9DT05ORUNUSU9OIjoiY2xvc2UiLCJQQVRIIjoiXC91c3JcL2xvY2FsXC9zYmluOlwvdXNyXC9sb2NhbFwvYmluOlwvdXNyXC9zYmluOlwvdXNyXC9iaW4iLCJTRVJWRVJfU0lHTkFUVVJFIjoiIiwiU0VSVkVSX1NPRlRXQVJFIjoiQXBhY2hlXC8yLjQuNiAoQ2VudE9TKSBPcGVuU1NMXC8xLjAuMmstZmlwcyBQSFBcLzcuMi4zNCIsIlNFUlZFUl9OQU1FIjoiYXBpZGV2LnZpcnR1YWxzb2Z0LnRlY2giLCJTRVJWRVJfQUREUiI6IjE5OC4xOTkuMTIwLjE2NCIsIlNFUlZFUl9QT1JUIjoiODAiLCJSRU1PVEVfQUREUiI6IjE5Mi40Ni4yMTkuMjA0IiwiRE9DVU1FTlRfUk9PVCI6IlwvaG9tZVwvYmFja2VuZFwvcHVibGljX2h0bWxcL2FwaVwvIiwiUkVRVUVTVF9TQ0hFTUUiOiJodHRwIiwiQ09OVEVYVF9QUkVGSVgiOiIiLCJDT05URVhUX0RPQ1VNRU5UX1JPT1QiOiJcL2hvbWVcL2JhY2tlbmRcL3B1YmxpY19odG1sXC9hcGlcLyIsIlNFUlZFUl9BRE1JTiI6IndlYm1hc3RlckBkb3JhZG9iZXQuY29tIiwiU0NSSVBUX0ZJTEVOQU1FIjoiXC9ob21lXC9iYWNrZW5kXC9wdWJsaWNfaHRtbFwvYXBpXC9wYXJ0bmVyX2FwaVwvaW5kZXgucGhwIiwiUkVNT1RFX1BPUlQiOiIzOTMwMCIsIlJFRElSRUNUX1VSTCI6IlwvcGFydG5lcl9hcGlcL0xvYmJ5XC9BcGkiLCJHQVRFV0FZX0lOVEVSRkFDRSI6IkNHSVwvMS4xIiwiU0VSVkVSX1BST1RPQ09MIjoiSFRUUFwvMS4xIiwiUkVRVUVTVF9NRVRIT0QiOiJQT1NUIiwiUVVFUllfU1RSSU5HIjoiIiwiUkVRVUVTVF9VUkkiOiJcL3BhcnRuZXJfYXBpXC9Mb2JieVwvQXBpIiwiU0NSSVBUX05BTUUiOiJcL3BhcnRuZXJfYXBpXC9pbmRleC5waHAiLCJQSFBfU0VMRiI6IlwvcGFydG5lcl9hcGlcL2luZGV4LnBocCIsIlJFUVVFU1RfVElNRV9GTE9BVCI6MTY4MDE5Mjg2OC41ODIsIlJFUVVFU1RfVElNRSI6MTY4MDE5Mjg2OH0=";
$Ismobile = "";
$Clasificador = new Clasificador("",$Abreviado);



$Crm = new \Backend\integrations\crm\Crm();
$Response = $Crm->CrmMovements($UsuarioId,$Clasificador,$IdMovimiento,$Server,$Ismobile);

exit();*/

$toDate =date('Y-m-d H:i:s');

$fromDate =date('Y-m-d H:i:s',strtotime('-1 hour'));


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


    $BonoId = strval("B".$value["bono_interno.bono_id"]);
    $Mandante = $value["bono_interno.mandante"];
    $IsCRM = $value["bono_interno.pertenece_crm"];
    array_push($BonosTotal, $BonoId);
}

if($IsCRM == "S"){
    $Clasificador = new Clasificador("","PROVCRM");


    $MandanteDetalle = new MandanteDetalle('', $Mandante, $Clasificador->clasificadorId, $PaisId, 'A');

    $Proveedor = new \Backend\dto\Proveedor($MandanteDetalle->valor);

    switch ($Proveedor->abreviado){
        case "OPTIMOVE":


            $Optimove = new Optimove();
            $respon = $Optimove->DeletePromotions($Mandante,$PaisId,$BonosTotal);

            break;

        case "FASTTRACK":

            break;

        case "CRMPROPIO":


            break;
    }

    //$transaccion->commit();
//print_r("exito");

}





