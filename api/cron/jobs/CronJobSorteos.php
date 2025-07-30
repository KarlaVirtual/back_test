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
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;




/**
 * Clase 'CronJobSorteos'
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
class CronJobSorteos
{


    public function __construct()
    {
    }

    public function execute()
    {


        $message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $SorteoInterno = new SorteoInterno();

        $rules = [];

        if ($sorteosAnalizados != '') {
            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
        }

        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => date('Y-m-d H:i:s'), "op" => "le"));
//array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => '87', "op" => "eq"));
//array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);


        $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true, '');


        $data = json_decode($data);

        $final = [];

        $pos = 1;
        $sorteosAnalizados = '';

        foreach ($data->data as $key => $value) {


            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;


            $MaxplayersToWin = 0;
            $MinplayersForLoyalty = 0;

            $RANKAWARD = array();
            $RANKAWARDMAT = array();

            $maxPos = 0;
            $maxPosMat = 0;

            foreach ($sorteodetalles->data as $key2 => $value2) {
                print_r($value2->{"sorteo_detalle.tipo"});

                switch ($value2->{"sorteo_detalle.tipo"}) {
                    case "RANK":
                        if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                            if ($TransaccionApi->valor >= $value2->{"sorteo_detalle.valor"}) {
                                if ($TransaccionApi->valor <= $value2->{"sorteo_detalle.valor2"}) {
                                    $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                }

                            }
                        }

                        break;

                    case "USERSUBSCRIBE":

                        if ($value2->{"sorteo_detalle.valor"} == 0) {

                        } else {
                            $needSubscribe = true;
                        }

                        break;

                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "MINPLAYERSCOUNT":
                        $MinplayersForLoyalty = intval($value2->{"sorteo_detalle.valor"});

                        break;

                    case "MAXPLAYERSCOUNT":
                        $MaxplayersToWin = intval($value2->{"sorteo_detalle.valor"});


                        break;

                    case "CONDPAISUSER":


                        break;

                    case "RANKAWARD":
                        $value2->{"sorteo_detalle.descripcion"} = str_replace('T', ' ', $value2->{"sorteo_detalle.descripcion"});

                        if (($value2->{"sorteo_detalle.descripcion"} != ''
                                && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.descripcion"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.descripcion"})) <= date('Y-m-d H:i:s'))
                            || ($value2->{"sorteo_detalle.descripcion"} == '' && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) <= date('Y-m-d H:i:s'))) {
                            array_push($RANKAWARD, $value2->{"sorteo_detalle.valor2"} . '_' . $value2->{"sorteo_detalle.valor3"} . '_' . $value2->{"sorteo_detalle.valor"});
                            $maxPos = intval($value2->{"sorteo_detalle.valor"});

                        }


                        break;

                    case "RANKAWARDMAT":
                        $value2->{"sorteo_detalle.descripcion"} = str_replace('T', ' ', $value2->{"sorteo_detalle.descripcion"});

                        if (($value2->{"sorteo_detalle.descripcion"} != ''
                                && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.descripcion"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                && date('Y-m-d H:i:s', strtotime($value2->{"sorteo_detalle.descripcion"})) <= date('Y-m-d H:i:s'))
                            || ($value2->{"sorteo_detalle.descripcion"} == '' && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) > date('Y-m-d H:i:s', strtotime('-1 hour'))
                                && date('Y-m-d H:i:s', strtotime($value->{"sorteo_interno.fecha_fin"})) <= date('Y-m-d H:i:s'))) {

                            array_push($RANKAWARDMAT, $value2->{"sorteo_detalle.valor2"} . '_' . $value2->{"sorteo_detalle.valor3"} . '_' . $value2->{"sorteo_detalle.valor"});
                            $maxPosMat = intval($value2->{"sorteo_detalle.valor"});
                        }


                        break;

                    default:

                        break;
                }

            }

            if (true) {

                $cantPremiosDados = 0;

                $rules = [];


                array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                array_push($rules, array("field" => "usuario_sorteo.estado", "data" => "R", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");


                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 1000000000;

                $json = json_encode($filtro);

                $UsuarioSorteo = new UsuarioSorteo();
                $data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.*,usuario_mandante.nombres", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, false);

                $data = json_decode($data);


                $cantPremiosDados = intval($data->count[0]->{".count"});


                $arrayMovimientoParticipantes = array();
                $arrayUsuariosUnicosParticipantes = array();
                $rules = [];


                array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");


                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 1000000000;

                $json = json_encode($filtro);

                $UsuarioSorteo = new UsuarioSorteo();
                $data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.*,usuario_mandante.nombres", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, false);

                $data = json_decode($data);


                if (intval($data->count[0]->{".count"}) == 0) {
                    continue;
                }


                $final = [];

                foreach ($data->data as $keyP => $valueP) {

                    array_push($arrayMovimientoParticipantes, $valueP->{"usuario_sorteo.ususorteo_id"});
                    if (!in_array($valueP->{"usuario_sorteo.usuario_id"}, $arrayUsuariosUnicosParticipantes)) {
                        array_push($arrayUsuariosUnicosParticipantes, $valueP->{"usuario_sorteo.usuario_id"});

                    }

                }

                print_r("arrayUsuariosUnicosParticipantes");
                print_r($arrayUsuariosUnicosParticipantes);
                print_r("RANKAWARD");
                print_r($RANKAWARD);
                print_r($RANKAWARDMAT);
                if ($MinplayersForLoyalty > oldCount($arrayUsuariosUnicosParticipantes)) {
                    continue;
                }


                $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
                $Transaction = $UsuarioSorteoMySqlDAO->getTransaction();

                $SorteoInternoInt = new SorteoInterno($value->{"sorteo_interno.sorteo_id"});

                foreach ($RANKAWARD as $PremioDinero) {

                    //$arrayMovimientoParticipantes = array();
                    //$arrayUsuariosUnicosParticipantes = array();
                    $rules = [];


                    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "usuario_sorteo.estado", "data" => "A", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");


                    $SkeepRows = 0;
                    $OrderedItem = 1;
                    $MaxRows = 1000000000;

                    $json = json_encode($filtro);

                    /* $UsuarioSorteo = new UsuarioSorteo();
                     $data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.*,usuario_mandante.nombres", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, false);

                     $data = json_decode($data);
                     print_r(" ENTROasdsadasd ");
                     print_r($rules);
                     print_r($data);

                     if (intval($data->count[0]->{".count"}) == 0) {
                         continue;
                     }*/


                    $final = [];

                    foreach ($data->data as $key => $valueP) {

                        array_push($arrayMovimientoParticipantes, $valueP->{"usuario_sorteo.ususorteo_id"});
                        if (!in_array($valueP->{"usuario_sorteo.usuario_id"}, $arrayUsuariosUnicosParticipantes)) {
                            array_push($arrayUsuariosUnicosParticipantes, $valueP->{"usuario_sorteo.usuario_id"});

                        }

                    }

                    if ($MinplayersForLoyalty > oldCount($arrayUsuariosUnicosParticipantes)) {
                        continue;
                    }

                    $ItemEscogido = array_rand($arrayMovimientoParticipantes, 1);

                    $UsuarioSorteo = new UsuarioSorteo($arrayMovimientoParticipantes[$ItemEscogido]);
                    $UsuarioSorteo->setEstado('R');

                    unset($arrayMovimientoParticipantes[$ItemEscogido]);

                    if ($PremioDinero != '' && $PremioDinero != null) {
                        print_r($PremioDinero);
                        $TypeBalance = explode('_', $PremioDinero)[0];
                        $valorBono = explode('_', $PremioDinero)[1];
                        $posicion = explode('_', $PremioDinero)[2];
                        $UsuarioSorteo->setPosicion($posicion);


                        $UsuarioMandante = new UsuarioMandante($UsuarioSorteo->usuarioId);
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $BonoLog = new BonoLog();
                        $BonoLog->setUsuarioId($UsuarioMandante->usuarioMandante);
                        $BonoLog->setTipo('S');
                        $BonoLog->setValor($valorBono);
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

                            $Usuario->credit($valorBono, $Transaction);

                        } elseif ($TypeBalance == '1') {
                            $Usuario->creditWin($valorBono, $Transaction);

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
                        $UsuarioHistorial->setValor($valorBono);
                        $UsuarioHistorial->setExternoId($bonologId);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                        $UsuarioSorteo->setValor($valorBono);

                        $SorteoInternoInt->cupoActual = floatval($SorteoInternoInt->cupoActual) + floatval($valorBono);

                        $title = '';
                        $messageBody = '';
                        $lotteryName = $value->{"sorteo_interno.nombre"};

                        switch (strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Has ganado un premio del Sorteo {$lotteryName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You have earned a prize in the Raffle {$lotteryName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Atenção! :thumbsup: Você foi sorteado no {$lotteryName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $messageBody;
                        $UsuarioMensaje->msubject = $title;
                        $UsuarioMensaje->parentId = 0;
                        $UsuarioMensaje->proveedorId = 0;
                        $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                        $UsuarioMensaje->paisId = 0;
                        $UsuarioMensaje->fechaExpiracion = '';

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($UsuarioSorteoMySqlDAO->getTransaction());
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                        $mensajesRecibidos = [];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;
                        $data["bono"] = array(
                            'type' => 'bono'
                        );

                        //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                        $ConfigurationEnvironment = new ConfigurationEnvironment();

                        if (!$ConfigurationEnvironment->isDevelopment()) {

                            if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                            }
                        }

                    }


                    $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);


                }

                foreach ($RANKAWARDMAT as $PremioMaterial) {


                    //$arrayMovimientoParticipantes = array();
                    //$arrayUsuariosUnicosParticipantes = array();
                    $rules = [];


                    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");


                    $SkeepRows = 0;
                    $OrderedItem = 1;
                    $MaxRows = 1000000000;

                    $json = json_encode($filtro);

                    /* $UsuarioSorteo = new UsuarioSorteo();
                     $data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.*,usuario_mandante.nombres", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, false);

                     $data = json_decode($data);*/


                    $final = [];

                    foreach ($data->data as $key => $valueP) {

                        array_push($arrayMovimientoParticipantes, $valueP->{"usuario_sorteo.ususorteo_id"});
                        if (!in_array($valueP->{"usuario_sorteo.usuario_id"}, $arrayUsuariosUnicosParticipantes)) {
                            array_push($arrayUsuariosUnicosParticipantes, $valueP->{"usuario_sorteo.usuario_id"});

                        }

                    }

                    if ($MinplayersForLoyalty > oldCount($arrayUsuariosUnicosParticipantes)) {
                        continue;
                    }

                    $ItemEscogido = array_rand($arrayMovimientoParticipantes, 1);


                    //$UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
                    // $Transaction = $UsuarioSorteoMySqlDAO->getTransaction();


                    $UsuarioSorteo = new UsuarioSorteo($arrayMovimientoParticipantes[$ItemEscogido]);
                    $UsuarioSorteo->setEstado('R');
                    $UsuarioSorteo->setPosicion($cantPremiosDados + 1);


                    unset($arrayMovimientoParticipantes[$ItemEscogido]);

                    if ($PremioMaterial != '') {

                        $TypeBalance = explode('_', $PremioMaterial)[0];
                        $valorBono = explode('_', $PremioMaterial)[1];
                        $posicion = explode('_', $PremioMaterial)[2];
                        $UsuarioSorteo->setPosicion($posicion);
                    }


                    $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);

                    $title = '';
                    $messageBody = '';
                    $lotteryName = $value->{"sorteo_interno.nombre"};

                    switch (strtolower($Usuario->idioma)) {
                        case 'es':
                            $title = 'Notificacion';
                            $messageBody = "¡ Bien :thumbsup: ! Has ganado un premio del Sorteo {$lotteryName} :clap:";
                            break;
                        case 'en':
                            $title = 'Notification';
                            $messageBody = "¡ Great :thumbsup: ! You have earned a prize in the Raffle {$lotteryName} :clap:";
                            break;
                        case 'pt':
                            $title = 'Notificação';
                            $messageBody = "Atenção! :thumbsup: Você foi sorteado no {$lotteryName} :clap:";
                            break;
                    }

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $messageBody;
                    $UsuarioMensaje->msubject = $title;
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                    $UsuarioMensaje->paisId = 0;
                    $UsuarioMensaje->fechaExpiracion = '';

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($UsuarioSorteoMySqlDAO->getTransaction());
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                    $mensajesRecibidos = [];
                    $array = [];

                    $array["body"] = $messageBody;

                    array_push($mensajesRecibidos, $array);
                    $data = array();
                    $data["messages"] = $mensajesRecibidos;
                    $data["bono"] = array(
                        'type' => 'bono'
                    );

                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    if (!$ConfigurationEnvironment->isDevelopment()) {

                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                            $dataSend = $data;
                            //$WebsocketUsuario = new WebsocketUsuario('', '');
                            //$WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                        }
                    }
                }

                /*if ($cantPremiosDados + 1 >= $maxPos && $cantPremiosDados + 1 >= $maxPosMat) {
                    $SorteoInternoInt->estado = 'I';
                }*/
                $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO($Transaction);
                $SorteoInternoMySqlDAO->update($SorteoInternoInt);

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

                }

                $UsuarioSorteoMySqlDAO->getTransaction()->commit();


                break;
            }


        }


        $message = "*CRON: FIN (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }
}