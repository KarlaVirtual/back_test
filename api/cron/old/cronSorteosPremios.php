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
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;



require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time',0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');

for($i=0;$i<10;$i++) {

    $message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    try {
        $SorteoInterno = new SorteoInterno();

        $rules = [];

        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.pegatinas", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => date('Y-m-d H:i:s'), "op" => "le"));
//array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => '87', "op" => "eq"));
//array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);


        $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true);


        $data = json_decode($data);

        $final = [];

        $pos = 1;
        $sorteosAnalizados = '';

        foreach ($data->data as $key => $value) {
            try {


                $SorteoDetalle = new SorteoDetalle();

                $rules = [];

                //array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => 132, "op" => "eq"));
                array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "sorteo_detalle.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "'RANKAWARDMAT','BONO','RANKAWARD'", "op" => "in"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);

                $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", 0, 1000, $json, TRUE);

                $sorteodetalles = json_decode($sorteodetalles);


                if (intval($data->count[0]->{".count"}) == 0) {
                    throw new Exception("No existen participantes", "9000");
                }
                $final = [];

                $RANKAWARD = array();
                $RANKAWARDMAT = array();
                $BONO = array();
                date_default_timezone_set('America/Bogota');

                foreach ($sorteodetalles->data as $key2 => $value2) {

                    switch ($value2->{"sorteo_detalle.tipo"}) {

                        case "RANKAWARD":
                            $value2->{"sorteo_detalle.descripcion"} = str_replace('T', ' ', $value2->{"sorteo_detalle.descripcion"});

                            if ($value2->{"sorteo_detalle.estado"} == 'A' && (($value2->{"sorteo_detalle.fecha_sorteo"} != ''
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.fecha_sorteo"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.fecha_sorteo"})) <= date('Y-m-d H:i:s', strtotime('+30 seconds')))
                                    || ($value2->{"sorteo_detalle.fecha_sorteo"} == '' && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) <= date('Y-m-d H:i:s')))) {
                                $premio = array(
                                    "position" => $value2->{"sorteo_detalle.valor"},
                                    "detalleId" => $value2->{"sorteo_detalle.sorteodetalle_id"},
                                    "description" => $value2->{"sorteo_detalle.descripcion"},
                                    "value" => $value2->{"sorteo_detalle.valor3"},
                                    "imagen" => $value2->{"sorteo_detalle.imagen_url"},
                                    "sorteoId" => $value2->{"sorteo_detalle.sorteo_id"},
                                    "type" => $value2->{"sorteo_detalle.tipo"});
                                array_push($RANKAWARD, $premio);


                            }


                            break;

                        case "RANKAWARDMAT":
                            $value2->{"sorteo_detalle.fecha_sorteo"} = str_replace('T', ' ', $value2->{"sorteo_detalle.fecha_sorteo"});

                            if ($value2->{"sorteo_detalle.estado"} == 'A' && (($value2->{"sorteo_detalle.fecha_sorteo"} != ''
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.fecha_sorteo"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.fecha_sorteo"})) <= date('Y-m-d H:i:s', strtotime('+30 seconds')))
                                    || ($value2->{"sorteo_detalle.fecha_sorteo"} == '' && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) <= date('Y-m-d H:i:s')))) {

                                $premio = array(
                                    "position" => $value2->{"sorteo_detalle.valor"},
                                    "detalleId" => $value2->{"sorteo_detalle.sorteodetalle_id"},
                                    "description" => $value2->{"sorteo_detalle.descripcion"},
                                    "value" => $value2->{"sorteo_detalle.valor3"},
                                    "imagen" => $value2->{"sorteo_detalle.imagen_url"},
                                    "sorteoId" => $value2->{"sorteo_detalle.sorteo_id"},
                                    "type" => $value2->{"sorteo_detalle.tipo"});
                                array_push($RANKAWARDMAT, $premio);

                            }


                            break;
                        case "BONO":
                            $value2->{"sorteo_detalle.fecha_sorteo"} = str_replace('T', ' ', $value2->{"sorteo_detalle.fecha_sorteo"});

                            if ($value2->{"sorteo_detalle.estado"} == 'A' && (($value2->{"sorteo_detalle.fecha_sorteo"} != ''
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.fecha_sorteo"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.fecha_sorteo"})) <= date('Y-m-d H:i:s', strtotime('+30 seconds')))
                                    || ($value2->{"sorteo_detalle.fecha_sorteo"} == '' && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                        && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) <= date('Y-m-d H:i:s')))) {
                                $premio = array(
                                    "position" => $value2->{"sorteo_detalle.valor"},
                                    "detalleId" => $value2->{"sorteo_detalle.sorteodetalle_id"},
                                    "description" => $value2->{"sorteo_detalle.descripcion"},
                                    "value" => $value2->{"sorteo_detalle.valor3"},
                                    "bonoId" => $value2->{"sorteo_detalle.valor2"},
                                    "imagen" => $value2->{"sorteo_detalle.imagen_url"},
                                    "sorteoId" => $value2->{"sorteo_detalle.sorteo_id"},
                                    "type" => $value2->{"sorteo_detalle.tipo"});
                                array_push($BONO, $premio);

                            }

                            break;

                    }

                }
                $TotalPremios = array_merge($RANKAWARDMAT, $RANKAWARD, $BONO);


                rsort($TotalPremios);

                foreach ($TotalPremios as $key3 => $values) {
                    /** Solicitando sorteo_interno bajo redención */
                    $SorteoInterno = new SorteoInterno($value->{"sorteo_interno.sorteo_id"});
                    $jsonTempLottery = $SorteoInterno->jsonTemp;
                    $jsonTempLottery = is_object($jsonTempLottery) ? $jsonTempLottery : json_decode($jsonTempLottery);

                    /** Consultando objeto correspondiente al premio en el JsonTemp de sorteo_interno
                     * Se consulta este objeto para almacenar información vinculada al desarrollo del sorteo en sorteo_interno.jsonTemp
                     */
                    $detailId = $values["detalleId"];
                    $prizes  = $jsonTempLottery->RanksPrize[0]->Amount;
                    $totalEngagedCoupons = 0; //Total cupones que participaron en el sorteo
                    $totalEngagedUsers = 0; //Total usuarios que participaron en el sorteo

                    $rules = [];
                    $SorteoDetalle = new SorteoDetalle($values["detalleId"]);
                    if ($SorteoDetalle->permiteGanador == '1') {

                        array_push($rules, array("field" => "usuario_sorteo.estado", "data" => "'A','R'", "op" => "in"));

                    } else {
                        array_push($rules, array("field" => "usuario_sorteo.estado", "data" => "A", "op" => "eq"));
                    }

                    array_push($rules, array("field" => "usuario_sorteo.sorteo_id", "data" => $values["sorteoId"], "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $SkeepRows = 0;
                    $OrderedItem = 'rand()';
                    $MaxRows = 1;

                    $json = json_encode($filtro);

                    $UsuarioSorteo = new UsuarioSorteo();
                    $data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.ususorteo_id", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, false);
                    $data = json_decode($data);

                    if (intval($data->count[0]->{".count"}) == 0) {
                        throw new Exception("No existen participantes", "9000");
                    } else {
                        //Obteniendo total de cupones que participaron en el sorteo para la reportería que requiera este dato
                        $totalEngagedCoupons = intval($data->count[0]->{".count"});
                    }

                    //Obteniendo total de usuarios que participaron en el sorteo para la reportería que requiera este dato
                    if ($totalEngagedCoupons > 0) {
                        $engagedUsersResponse = $UsuarioSorteo->getUsuarioSorteosCustom("COUNT(DISTINCT usuario_sorteo.usuario_id) AS totalUsers", 'usuario_sorteo.usuario_id', 'DESC', 0, 1, $json, true);
                        $engagedUsersResponse = json_decode($engagedUsersResponse)->data;
                        $engagedUsersResponse = $engagedUsersResponse[0]->{'.totalUsers'};

                        if (!empty($engagedUsersResponse)) $totalEngagedUsers = intval($engagedUsersResponse);
                    }

                    //Almacenando total de usuarios y cupones participantes

                    if (!empty((array)$jsonTempLottery)) {
                        $updatedPrizes = array_map(function ($prize) use ($detailId, $totalEngagedUsers, $totalEngagedCoupons) {
                            $prize = (object)$prize;
                            if ($prize->detailId != $detailId) return $prize;

                            //Si el premio bajo iteración corresponde con sorteodetalle_id bajo proceso de redención se almacenan los valores
                            $prize->engagedCoupons = $totalEngagedCoupons;
                            $prize->engagedUsers = $totalEngagedUsers;
                            return $prize;
                        }, $prizes);
                        $jsonTempLottery->RanksPrize[0]->Amount = $updatedPrizes;
                        $jsonTempLottery = json_encode($jsonTempLottery);
                        $SorteoInterno->jsonTemp = $jsonTempLottery;
                    }


                    $ususorteId = $data->data[0]->{"usuario_sorteo.ususorteo_id"};

                    $UsuarioSorteo = new UsuarioSorteo($ususorteId);

                    if ($UsuarioSorteo->premio != "") {
                        $jsonpremio = json_decode($UsuarioSorteo->premio, true);

                        $Premios = array();
                        array_push($Premios, $jsonpremio);
                        array_push($Premios, $values);
                        $premioId = $UsuarioSorteo->premioId . ',' . $values["detalleId"];
                    } else {

                        $Premios = $values;
                        $premioId = $values["detalleId"];
                    }

                    $UsuarioSorteo->setEstado("R");
                    $UsuarioSorteo->setPremio(json_encode($Premios));
                    $UsuarioSorteo->setPremioId($premioId);

                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
                    $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);
                    $UsuarioSorteoMySqlDAO->getTransaction()->commit();

                    $SorteoDetalle->setEstado('R');
                    $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();
                    $SorteoDetalleMySqlDAO->updateEstado($SorteoDetalle);
                    $SorteoDetalleMySqlDAO->getTransaction()->commit();

                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
                    $Transaction = $UsuarioSorteoMySqlDAO->getTransaction();
                    $UsuarioMandante = new UsuarioMandante($UsuarioSorteo->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    if ($values["type"] == "RANKAWARD") {

                        $SorteoInternoInt = new SorteoInterno($values["sorteoId"]);

                        switch ($values["description"]) {
                            case "Saldo Creditos":
                                $TypeBalance = 0;
                                break;
                            case "Saldo Premios":
                                $TypeBalance = 1;
                                break;
                            case "Saldo Bonos":
                                $TypeBalance = 2;
                                break;
                        }

                        $BonoLog = new BonoLog();
                        $BonoLog->setUsuarioId($UsuarioMandante->usuarioMandante);
                        $BonoLog->setTipo('S');
                        $BonoLog->setValor($values["value"]);
                        $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                        $BonoLog->setEstado('L');
                        $BonoLog->setErrorId(0);
                        $BonoLog->setIdExterno($UsuarioSorteo->ususorteoId);
                        $BonoLog->setMandante($UsuarioMandante->mandante);
                        $BonoLog->setFechaCierre('');
                        $BonoLog->setTransaccionId('');
                        $BonoLog->setTipobonoId(4);
                        $BonoLog->setTiposaldoId($TypeBalance);

                        if ($TypeBalance == '0') {

                            $Usuario->credit($values["value"], $Transaction);

                        } elseif ($TypeBalance == '1') {
                            $Usuario->creditWin($values["value"], $Transaction);

                        }
                        $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);


                        $bonologId = $BonoLogMySqlDAO->insert($BonoLog);

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId($UsuarioMandante->usuarioMandante);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(50);
                        $UsuarioHistorial->setValor($values["value"]);
                        $UsuarioHistorial->setExternoId($bonologId);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                        $UsuarioSorteo->setValor($values["value"]);

                        $SorteoInternoInt->cupoActual = floatval($SorteoInternoInt->cupoActual) + floatval($values["value"]);
                        //$Transaction->commit();
                    } elseif ($values["type"] == "BONO") {
                        $BonoInterno = new BonoInterno($values["bonoId"]);

                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        //$transaccion = $BonoDetalleMySqlDAO->getTransaction();
                        //$transaccion->getConnection()->beginTransaction();
                        $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante,usuario.moneda FROM registro
    INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN pais ON (pais.pais_id = usuario.pais_id)
    LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  LEFT OUTER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
   WHERE registro.usuario_id='" . $UsuarioMandante->getUsuarioMandante() . "'";

                        $Usuario = $BonoInterno->execQuery($Transaction, $usuarioSql);


                        $dataUsuario = $Usuario;

                        $detalles = array(
                            "PaisUSER" => $dataUsuario[0]->{'pais.pais_id'},
                            "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
                            "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},
                            "MonedaUSER" => $dataUsuario[0]->{'usuario.moneda'}

                        );

                        $detalles = json_decode(json_encode($detalles));

                        $respuesta = $BonoInterno->agregarBonoFree($BonoInterno->bonoId, $UsuarioMandante->getUsuarioMandante(), $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, "", $Transaction);

                        if ($BonoInterno->bonoId == 17336) {
                            $respuesta = $BonoInterno->agregarBonoFree('17330', $UsuarioMandante->getUsuarioMandante(), $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, "", $Transaction);

                        }

                        //$Transaccion->commit();
                    }

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = '¡ Bien :thumbsup: ! Has ganado un premio del Sorteo ' . $value->{"sorteo_interno.nombre"} . '_' . $values["position"] . ' :clap:';
                    $UsuarioMensaje->msubject = 'Felicidades eres uno de los ganadores';
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "POPUPWIN";
                    $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                    $UsuarioMensaje->fechaExpiracion = '';
                    $UsuarioMensaje->valor1 = str_replace('RANKAWARDMAT', 'Fisico', json_encode($Premios));

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                    $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);

                    $SorteoInterno->update($Transaction);

                    $Transaction->commit();
                }
                /*      $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO($Transaction);
                      $sql = "
          SELECT *
                  FROM sorteo_interno
                  WHERE fecha_fin < now() and estado='A'
          ";


                      $BonoInterno = new BonoInterno();
                      $dataParaEstadoI = $BonoInterno->execQuery($Transaction, $sql);
                      foreach ($dataParaEstadoI as $datanum) {
                          $sql = "UPDATE sorteo_interno  SET estado = 'I' WHERE sorteo_id = '" . $datanum->{'sorteo_interno.sorteo_id'} . "' ";
                          $BonoInterno->execQuery($Transaction, $sql);

                      }*/

//$UsuarioSorteoMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {

            }
        }
        $message = "*CRON: FIN (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    } catch (Exception $e) {
        print_r($e);
    }


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


    exit();

    $sql = "
        SELECT *
        FROM casino.preusuario_sorteo 
        WHERE preususorteo_id >0 and   apostado >= valor_base and estado='P'
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
        exec("php -f " . __DIR__ . "../src/imports/Slack/message.php 'DUP CASINO *Cant:* " . $datanum->{'.count'} . " " . $datanum->{'transjuego_log.transaccion_id'} . " *" . $UsuarioMandante->moneda . " " . $datanum->{'.valorTotal'} . "*' '#transacciones-duplicadas' > /dev/null & ");
    }


    sleep(3);

}

