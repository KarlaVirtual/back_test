<?php

/**
 * CronJobAcumuladoJackpotCasinos
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 10/03/2025
 */

use Backend\dto\BonoInterno;
use Backend\dto\JackpotInterno;
use Backend\websocket\WebsocketUsuario;
use Backend\mysql\BonoDetalleMySqlDAO;

class CronJobAcumuladoJackpotCasinos
{

    private $BonoInterno;
    private $transaccion;

    public function __construct()
    {
        $this->BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $this->transaccion = $BonoDetalleMySqlDAO->getTransaction();
    }

    public function execute()
    {
        try {

            $filename = __DIR__ . '/lastrunCronJobAcumuladoJackpotCasinos';

            $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='ACUMULADOJACKPOTCASINOS'";

            $data = $this->BonoInterno->execQuery('', $sqlProcesoInterno2);
            $data = $data[0];
            $line = $data->{'proceso_interno2.fecha_ultima'};

            if ($line == '') return;

            if (date('Y-m-d H:i:s', strtotime($line)) >= date('Y-m-d H:i:s')) {
                return;
            }
            
            $fecha = date('Y-m-d H:i:s', strtotime($line . '+30 seconds'));
            $filename .= str_replace(' ', '-', str_replace(':', '-', $fecha));

            if (file_exists($filename)) {
                $datefilename = date("Y-m-d H:i:s", filemtime($filename));
                if ($datefilename <= date("Y-m-d H:i:s", strtotime('-10 minute'))) unlink($filename);
                return;
            }

            file_put_contents($filename, 'RUN');

            $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fecha . "' WHERE  tipo='ACUMULADOJACKPOTCASINOS';";

            $data = $this->BonoInterno->execQuery($this->transaccion, $sqlProcesoInterno2);
            $this->transaccion->commit();

            $jackpots = $this->getAcumuadoActualJackpots();

            $WebsocketUsuario = new WebsocketUsuario('', '');
            foreach ($jackpots as $jackpot) {
                
                $dataSend = [
                    "type" => "updateDom",
                    "data" => ["jackpot_{$jackpot['id']}" => $jackpot['currentValue']],
                ];
        
                $WebsocketUsuario->sendWSPieSocketMandantePais($jackpot['mandante'], $jackpot['pais'], $dataSend);
            }

            unlink($filename);
        } catch (\Throwable $th) {
            if ($_ENV['debug']) {
                print_r($th->getMessage());
            }
            throw $th;
        }
    }
    
    private function getAcumuadoActualJackpots(): array
    {
        $JackpotInterno = new JackpotInterno();
        $rules = [];
        $joins = [];

        array_push($rules, array("field" => "jackpot_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
        array_push($rules, array("field" => "jackpot_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "jackpot_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));
        $joins[] = (object) ['type' => 'LEFT', 'table' => 'pais', 'on' => 'jackpot_detalle.valor = pais.pais_id'];

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $Jackpots = $JackpotInterno->getJackpotCustom("jackpot_interno.jackpot_id, jackpot_interno.valor_actual, jackpot_interno.mandante, pais.iso","jackpot_interno.jackpot_id", "asc", '0', '1000', $json2, true, $joins);

        $Jackpots = json_decode((string) $Jackpots);
        $final = array();
        foreach ($Jackpots->data as $value) {

            $array = [];
            $array["id"] = intval($value->{"jackpot_interno.jackpot_id"});
            $array["currentValue"] = intval($value->{"jackpot_interno.valor_actual"});
            $array["mandante"] = intval($value->{"jackpot_interno.mandante"});
            $array["pais"] = $value->{"pais.iso"};

            array_push($final, $array);
        }

        return $final;
    } 
}