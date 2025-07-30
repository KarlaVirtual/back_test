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
use Backend\dto\BonoLog;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\SorteoDetalle2;
use Backend\dto\SorteoInterno;
use Backend\dto\SorteoInterno2;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioSorteo2;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\Mandante;
use Backend\dto\Registro2;
use Backend\dto\UsuarioSorteo;
use Backend\imports\Mautic\Api\Data;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\UsuarioSorteo2MySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoDetalle2MySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\SorteoInterno2MySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Clase 'CronJobSorteosPremios2'
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
class CronJobSorteosPremios2
{


    public function __construct()
    {
    }

    public function execute()
    {


        $message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");
        $ConfigurationEnvironment = new ConfigurationEnvironment();


        if (!$ConfigurationEnvironment->isDevelopment()) {
            // $message = "*CRON: (Segundos) * " . " - Fecha: " . date("Y-m-d H:i:s");
            // exec("php -f /home/backend/public_html/api/src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

//cron

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();

        $sql = "UPDATE sorteo_interno2 set estado = 'I' where fecha_fin < now()";

        $actualizar = $BonoInterno->execQuery($transaccion, $sql);

        $transaccion->commit();


        try {
            $SorteoInterno2 = new SorteoInterno2();


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno2.estado", "data" => "A", "op" => "eq"));
            // array_push($rules, array("field" => "sorteo_interno2.pegatinas", "data" => "1", "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno2.fecha_inicio", "data" => date('Y-m-d H:i:s'), "op" => "le"));
//array_push($rules, array("field" => "sorteo_interno2.sorteo2_id", "data" => '87', "op" => "eq"));
//array_push($rules, array("field" => "sorteo_interno2.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno2.tipo", "data" => "2", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $data = $SorteoInterno2->getSorteos2Custom("sorteo_interno2.*", "sorteo_interno2.orden", "ASC", 0, 1000, $json, true);

            $data = json_decode($data);

            print_r($data);

            $final = [];

            $pos = 1;
            $sorteosAnalizados = '';

            foreach ($data->data as $key => $value) {

                try {


                    $SorteoDetalle2 = new SorteoDetalle2();

                    $rules = [];

                    //array_push($rules, array("field" => "sorteo_interno2.sorteo2_id", "data" => 132, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno2.sorteo2_id", "data" => $value->{"sorteo_interno2.sorteo2_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno2.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_detalle2.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_detalle2.tipo", "data" => "'RANKAWARDMAT','BONO','RANKAWARD'", "op" => "in"));
                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $sorteodetalles = $SorteoDetalle2->getSorteoDetalles2Custom("sorteo_detalle2.*,sorteo_interno2.*", "sorteo_interno2.sorteo2_id", "asc", 0, 100, $json, true);

                    $sorteodetalles = json_decode($sorteodetalles);
                    print_r($sorteodetalles);

                    if (intval($data->count[0]->{".count"}) == 0) {
                        throw new Exception("No existen participantes", "9000");
                    }

                    $final = [];

                    $RANKAWARD = array();
                    $RANKAWARDMAT = array();
                    $BONO = array();

                    date_default_timezone_set('America/Bogota');

                    foreach ($sorteodetalles->data as $key2 => $value2) {

                        switch ($value2->{"sorteo_detalle2.tipo"}) {

                            case "RANKAWARD":

                                $value2->{"sorteo_detalle2.descripcion"} = str_replace('T', ' ', $value2->{"sorteo_detalle2.descripcion"});

                                if (($value2->{"sorteo_detalle2.fecha_sorteo"} != ''
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle2.fecha_sorteo"})) < date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle2.fecha_sorteo"})) <= date('Y-m-d H:i:s', strtotime('+30 seconds')))
                                    || ($value2->{"sorteo_detalle2.fecha_sorteo"} == '' && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno2.fecha_fin"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno2.fecha_fin"})) <= date('Y-m-d H:i:s'))) {
                                    $premio = array(
                                        "position" => $value2->{"sorteo_detalle2.valor"},
                                        "detalleId" => $value2->{"sorteo_detalle2.sorteodetalle2_id"},
                                        "description" => $value2->{"sorteo_detalle2.descripcion"},
                                        "value" => $value2->{"sorteo_detalle2.valor3"},
                                        "imagen" => $value2->{"sorteo_detalle2.imagen_url"},
                                        "sorteoId" => $value2->{"sorteo_detalle2.sorteo2_id"},
                                        "type" => $value2->{"sorteo_detalle2.tipo"});
                                    array_push($RANKAWARD, $premio);
                                }

                                break;

                            case "RANKAWARDMAT":

                                $value2->{"sorteo_detalle2.fecha_sorteo"} = str_replace('T', ' ', $value2->{"sorteo_detalle2.fecha_sorteo"});


                                if ($value2->{"sorteo_detalle2.fecha_sorteo"} != '' && date("Y-m-d H:i:s", strtotime($value2->{"sorteo_detalle2.fecha_sorteo"})) > date("Y-m-d H:i:s", strtotime('-1 hour')) && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle2.fecha_sorteo"})) <= date('Y-m-d H:i:s', strtotime('+30 seconds')) || $value2->{"sorteo_detalle2.fecha_sorteo"} == '' && date("Y-m-d H:i:s", strtotime($value2->{"sorteo_interno2.fecha_fin"})) > date("Y-m-d H:i:s", strtotime("-1 hour")) && date("Y-m-d H:i:s", strtotime($value2->{"sorteo_interno2.fecha_fin"})) <= date("Y-m-d H:i:s")) {


                                    $premio = array(
                                        "position" => $value2->{"sorteo_detalle2.valor"},
                                        "detalleId" => $value2->{"sorteo_detalle2.sorteodetalle2_id"},
                                        "description" => $value2->{"sorteo_detalle2.descripcion"},
                                        "value" => $value2->{"sorteo_detalle2.valor3"},
                                        "imagen" => $value2->{"sorteo_detalle2.imagen_url"},
                                        "sorteoId" => $value2->{"sorteo_detalle2.sorteo2_id"},
                                        "type" => $value2->{"sorteo_detalle2.tipo"});
                                    array_push($RANKAWARDMAT, $premio);

                                }


                                // if (($value2->{"sorteo_detalle2.fecha_sorteo"} != ''
                                //         && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle2.fecha_sorteo"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                //         && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle2.fecha_sorteo"})) <= date('Y-m-d H:i:s', strtotime('+30 seconds')))
                                //     || ($value2->{"sorteo_detalle2.fecha_sorteo"} == '' && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno2.fecha_fin"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                //         && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno2.fecha_fin"})) <= date('Y-m-d H:i:s')))
                                //          {
                                // $premio = array(
                                //     "position" => $value2->{"sorteo_detalle2.valor"},
                                //     "detalleId" => $value2->{"sorteo_detalle2.sorteodetalle2_id"},
                                //     "description" => $value2->{"sorteo_detalle2.descripcion"},
                                //     "value" => $value2->{"sorteo_detalle2.valor3"},
                                //     "imagen" => $value2->{"sorteo_detalle2.imagen_url"},
                                //     "sorteoId" => $value2->{"sorteo_detalle2.sorteo2_id"},
                                //     "type" => $value2->{"sorteo_detalle2.tipo"});
                                // array_push($RANKAWARDMAT, $premio);

                                // }


                                break;
                            case "BONO":
                                $value2->{"sorteo_detalle2.fecha_sorteo"} = str_replace('T', ' ', $value2->{"sorteo_detalle2.fecha_sorteo"});

                                if (($value2->{"sorteo_detalle2.fecha_sorteo"} != ''
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle2.fecha_sorteo"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle2.fecha_sorteo"})) <= date('Y-m-d H:i:s', strtotime('+30 seconds')))
                                    || ($value2->{"sorteo_detalle2.fecha_sorteo"} == '' && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno2.fecha_fin"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno2.fecha_fin"})) <= date('Y-m-d H:i:s'))) {
                                    $premio = array(
                                        "position" => $value2->{"sorteo_detalle2.valor"},
                                        "detalleId" => $value2->{"sorteo_detalle2.sorteodetalle2_id"},
                                        "description" => $value2->{"sorteo_detalle2.descripcion"},
                                        "value" => $value2->{"sorteo_detalle2.valor3"},
                                        "bonoId" => $value2->{"sorteo_detalle2.valor2"},
                                        "imagen" => $value2->{"sorteo_detalle2.imagen_url"},
                                        "sorteoId" => $value2->{"sorteo_detalle2.sorteo2_id"},
                                        "type" => $value2->{"sorteo_detalle2.tipo"});
                                    array_push($BONO, $premio);

                                }

                                break;

                        }

                    }

                    $TotalPremios = array_merge($RANKAWARDMAT, $RANKAWARD, $BONO);

                    rsort($TotalPremios);

                    print_r($TotalPremios);


                    foreach ($TotalPremios as $key3 => $values) {


                        $rules = [];
                        $SorteoDetalle2 = new SorteoDetalle2($values["detalleId"]);


                        if ($SorteoDetalle2->permiteGanador == '1') {

                            array_push($rules, array("field" => "usuario_sorteo2.estado", "data" => "'A'", "op" => "in"));

                        } else {
                            array_push($rules, array("field" => "usuario_sorteo2.estado", "data" => "'A','R'", "op" => "in"));
                        }


                        if ($SorteoDetalle2->jugadorExcluido == '1') {

                            $rules = [];
                            array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'R', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $value->{"sorteo_interno.sorteo_id"}, 'op' => 'eq']);

                            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                            $UsuarioSorteo2 = new UsuarioSorteo();
                            $allCouponsWIN = (string)$UsuarioSorteo2->getUsuarioSorteosCustom('DISCTINCT(usuario_sorteo.usuario_id) usuarioGanador', 'usuario_sorteo.ususorteo_id', 'asc', 0, 1000000, $filter, true);

                            $allCouponsWIN = json_decode($allCouponsWIN, true);
                            $usuariosGanadores =array();
                            foreach ($allCouponsWIN as $valuewin) {
                                array_push($usuariosGanadores, $valuewin[".usuarioGanador"]);
                            }

                            array_push($rules, array("field" => "usuario_sorteo.usuario_id", "data" => implode(',',$usuariosGanadores), "op" => "ni"));

                        }

                        array_push($rules, array("field" => "usuario_sorteo2.sorteo2_id", "data" => $values["sorteoId"], "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        print_r($rules);

                        $SkeepRows = 0;
                        $OrderedItem = 'rand()';
                        $MaxRows = 1;

                        $json = json_encode($filtro);

                        $UsuarioSorteo2 = new UsuarioSorteo2();

                        $data = $UsuarioSorteo2->getUsuarioSorteosCustom("usuario_sorteo2.ususorteo2_id", $OrderedItem, 'asc',
                            $SkeepRows, $MaxRows, $json, true, false);

                        $data = json_decode($data);

                        print_r($data);


                        if (intval($data->count[0]->{".count"}) == 0) {
                            throw new Exception("No existen participantes", "9000");
                        }

                        $ususorteId = $data->data[0]->{"usuario_sorteo2.ususorteo2_id"}; //Me estoy quedando con un usuario al azar de los que estan participando.


                        $UsuarioSorteo2 = new UsuarioSorteo2($ususorteId);


                        if ($UsuarioSorteo2->premio != "") {


                            $jsonpremio = json_decode($UsuarioSorteo2->premio, true);

                            $Premios = array();
                            array_push($Premios, $jsonpremio);
                            array_push($Premios, $values);


                            $premioId = $UsuarioSorteo2->PremioId . '' . $values["detalleId"];


                        } else {


                            $Premios = $values;
                            $premioId = $values["detalleId"];
                        }


                        $UsuarioSorteo2->setEstado("R");
                        $UsuarioSorteo2->SetPrice(json_encode($Premios));
                        $UsuarioSorteo2->SetPriceId($premioId);


                        $UsuarioSorteoMySqlDAO = new UsuarioSorteo2MySqlDAO();
                        $UsuarioSorteoMySqlDAO->update($UsuarioSorteo2);
                        $UsuarioSorteoMySqlDAO->getTransaction()->commit();


                        $SorteoDetalle2->setEstado('R');
                        $SorteoDetalleMySqlDAO = new SorteoDetalle2MySqlDAO();
                        $SorteoDetalleMySqlDAO->updateEstado($SorteoDetalle2);
                        $SorteoDetalleMySqlDAO->getTransaction()->commit();


                        $UsuarioSorteoMySqlDAO = new UsuarioSorteo2MySqlDAO();
                        $Transaction = $UsuarioSorteoMySqlDAO->getTransaction();


                        $Registro2 = new Registro2($UsuarioSorteo2->Registro2Id);
                        // $UsuarioMandante = new UsuarioMandante($UsuarioSorteo2->usuarioId);
                        // $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    }
//$UsuarioSorteoMySqlDAO->getTransaction()->commit();
                } catch (Exception $e) {

                }
            }
            $message = "*CRON: FIN (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");

            if (!$ConfigurationEnvironment->isDevelopment()) {
                //exec("php -f /home/backend/public_html/api/src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

            }
        } catch (Exception $e) {
            print_r($e);
        }


        exit();

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();

        $sql = "
SELECT *
        FROM usuario_mensajecampana
        WHERE fecha_expiracion > now() and estado='I' and fecha_envio < now()
";

        $dataParaEstadoA = $BonoInterno->execQuery($transaccion, $sql);
        foreach ($dataParaEstadoA as $datanum) {
            $sql = "UPDATE usuario_mensajecampana  SET estado = 'A' WHERE usumencampana_id = '" . $datanum->{'usuario_mensajecampana.usumencampana_id'} . "' ";
            $BonoInterno->execQuery($transaccion, $sql);

        }

        $sql = "
SELECT *
        FROM usuario_mensajecampana
        WHERE fecha_expiracion < now() and estado='A'
";


        $dataParaEstadoI = $BonoInterno->execQuery($transaccion, $sql);
        foreach ($dataParaEstadoI as $datanum) {
            $sql = "UPDATE usuario_mensajecampana  SET estado = 'I' WHERE usumencampana_id = '" . $datanum->{'usuario_mensajecampana.usumencampana_id'} . "' ";
            $BonoInterno->execQuery($transaccion, $sql);

        }
        $transaccion->commit();


        $sql = "
        SELECT *
        FROM casino.preusuario_sorteo 
        WHERE apostado >= valor_base and estado='P'
        LIMIT 500
";

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();


        $dataParaEstadoA = $BonoInterno->execQuery($transaccion, $sql);
        foreach ($dataParaEstadoA as $datanum) {
            $sql = "UPDATE preusuario_sorteo SET estado='A'  WHERE  preususorteo_id =" . $datanum->{'preusuario_sorteo.preususorteo_id'};
            $BonoInterno->execQuery($transaccion, $sql);


        }
        $transaccion->commit();


        $fecha1 = date("Y-m-d H:00:00", strtotime('-1 hour'));
        $fecha2 = date("Y-m-d H:i:s", strtotime('-5 minute'));

        $sqlApuestasDeportivasUsuarioDiaCierre = "
SELECT max(castransprov_id) castransprov_id
        FROM casino.casino_transprovisional t
        WHERE fecha_crea < '" . $fecha2 . "'
        ORDER BY castransprov_id DESC
        LIMIT 501
";

        $BonoInterno = new BonoInterno();
        /*
        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

        $castransprov_id = '';
        foreach ($data as $datanum) {
            $castransprov_id = $datanum->{'.castransprov_id'};


        }
        */
        /*$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $BonoInterno->execQuery($transaccion, "DELETE FROM casino_transprovisional where  fecha_crea < '".$fecha2."' ");


        $transaccion->commit();*/


        $sql = "UPDATE sorteo_interno2 set estado = 'I' WHERE sorteo_interno2.fecha_fin > now()";

        $sql = "UPDATE sorteo_detalle2 set estado = 'I' WHERE sorteo_detalle.fecha_sorteo > now()";


        exit();
        $sqlApuestasDeportivasUsuarioDiaCierre = "
select count(*) count,transaccion_id,valor,SUM(valor) valorTotal,transjuego_id
from transjuego_log
where fecha_crea >='" . $fecha1 . "'
group by transaccion_id

having count >1 AND valorTotal >0
";

        $BonoInterno = new BonoInterno();

        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);


        foreach ($data as $datanum) {
            $TransaccionJuego = new TransaccionJuego($datanum->{'transjuego_log.transjuego_id'});
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
            exec("php -f /home/backend/public_html/api/src/imports/Slack/message.php 'DUP CASINO *Cant:* " . $datanum->{'.count'} . " " . $datanum->{'transjuego_log.transaccion_id'} . " *" . $UsuarioMandante->moneda . " " . $datanum->{'.valorTotal'} . "*' '#transacciones-duplicadas' > /dev/null & ");
        }


    }
}