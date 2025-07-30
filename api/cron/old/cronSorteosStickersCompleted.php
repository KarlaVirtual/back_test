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
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;



require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');


for($i=0;$i<10;$i++) {
    $message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");
    $ConfigurationEnvironment = new ConfigurationEnvironment();


    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    $SorteoInterno = new SorteoInterno();

    $rules = [];


    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "le"));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "ASC", 0, 1000, $json, true, '');

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';
    print_r($data);

    foreach ($data->data as $key => $value) {
        print_r($value);
        try {
            $pegatinas = $value->{"sorteo_interno.pegatinas"};

            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;
            $puederepetirBono = false;

            $minBetPrice = 0;
            $minBetPrice2 = 0;
            $NUMBERCASINOSTICKERS = '';
            $NUMBERDEPOSITSTICKERS = '';
            $NUMBERSPORTSBOOKSTICKERS = '';


            $Moneda = 'USD';

            foreach ($sorteodetalles->data as $key2 => $value2) {

                switch ($value2->{"sorteo_detalle.tipo"}) {


                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "REPETIRSORTEO":

                        if ($value2->{"sorteo_detalle.valor"} == '1') {

                            $puederepetirBono = true;
                        }

                        break;
                    case "NUMBERCASINOSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERDEPOSITSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERSPORTSBOOKSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;

                }

            }

            if (true) {


                $messageNot = '';
                if ($pegatinas == 1) {


                    if ($NUMBERCASINOSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='1' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERCASINOSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='1' AND a.estado = 'A' LIMIT " . $NUMBERCASINOSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }
                    if ($NUMBERSPORTSBOOKSTICKERS != '') {


                        // Para Sportbook


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='2' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERSPORTSBOOKSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='2' AND a.estado = 'A' LIMIT " . $NUMBERSPORTSBOOKSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                    // Para Depositos

                    if ($NUMBERDEPOSITSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='3' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERDEPOSITSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='3' AND a.estado = 'A' LIMIT " . $NUMBERDEPOSITSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                } else {

                }


            }

        } catch (Exception $e) {
            print_r($e);
        }
    }


    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    $SorteoInterno = new SorteoInterno();

    $rules = [];


    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "le"));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "ASC", 0, 1000, $json, true, '');

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';
    print_r($data);

    foreach ($data->data as $key => $value) {
        print_r($value);
        try {
            $pegatinas = $value->{"sorteo_interno.pegatinas"};

            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "0", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;
            $puederepetirBono = false;

            $minBetPrice = 0;
            $minBetPrice2 = 0;
            $NUMBERCASINOSTICKERS = '';
            $NUMBERDEPOSITSTICKERS = '';
            $NUMBERSPORTSBOOKSTICKERS = '';


            $Moneda = 'PEN';

            foreach ($sorteodetalles->data as $key2 => $value2) {

                switch ($value2->{"sorteo_detalle.tipo"}) {


                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "REPETIRSORTEO":

                        if ($value2->{"sorteo_detalle.valor"} == '1') {

                            $puederepetirBono = true;
                        }

                        break;
                    case "NUMBERCASINOSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERDEPOSITSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERSPORTSBOOKSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;

                }

            }

            if (true) {


                $messageNot = '';
                if ($pegatinas == 1) {


                    if ($NUMBERCASINOSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='1' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERCASINOSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='1' AND a.estado = 'A' LIMIT " . $NUMBERCASINOSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }
                    if ($NUMBERSPORTSBOOKSTICKERS != '') {


                        // Para Sportbook


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='2' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERSPORTSBOOKSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='2' AND a.estado = 'A' LIMIT " . $NUMBERSPORTSBOOKSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                    // Para Depositos

                    if ($NUMBERDEPOSITSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='3' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERDEPOSITSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='3' AND a.estado = 'A' LIMIT " . $NUMBERDEPOSITSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                } else {

                }


            }

        } catch (Exception $e) {
            print_r($e);
        }
    }


    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    $SorteoInterno = new SorteoInterno();

    $rules = [];


    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "le"));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "ASC", 0, 1000, $json, true, '');

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';
    print_r($data);

    foreach ($data->data as $key => $value) {
        print_r($value);
        try {
            $pegatinas = $value->{"sorteo_interno.pegatinas"};

            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "0", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;
            $puederepetirBono = false;

            $minBetPrice = 0;
            $minBetPrice2 = 0;
            $NUMBERCASINOSTICKERS = '';
            $NUMBERDEPOSITSTICKERS = '';
            $NUMBERSPORTSBOOKSTICKERS = '';


            $Moneda = 'CRC';

            foreach ($sorteodetalles->data as $key2 => $value2) {

                switch ($value2->{"sorteo_detalle.tipo"}) {


                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "REPETIRSORTEO":

                        if ($value2->{"sorteo_detalle.valor"} == '1') {

                            $puederepetirBono = true;
                        }

                        break;
                    case "NUMBERCASINOSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERDEPOSITSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERSPORTSBOOKSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;

                }

            }

            if (true) {


                $messageNot = '';
                if ($pegatinas == 1) {


                    if ($NUMBERCASINOSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='1' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERCASINOSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='1' AND a.estado = 'A' LIMIT " . $NUMBERCASINOSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }
                    if ($NUMBERSPORTSBOOKSTICKERS != '') {


                        // Para Sportbook


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='2' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERSPORTSBOOKSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='2' AND a.estado = 'A' LIMIT " . $NUMBERSPORTSBOOKSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                    // Para Depositos

                    if ($NUMBERDEPOSITSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='3' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERDEPOSITSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='3' AND a.estado = 'A' LIMIT " . $NUMBERDEPOSITSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                } else {

                }


            }

        } catch (Exception $e) {
            print_r($e);
        }
    }

    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    $SorteoInterno = new SorteoInterno();

    $rules = [];


    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "le"));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "ASC", 0, 1000, $json, true, '');

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';
    print_r($data);

    foreach ($data->data as $key => $value) {
        print_r($value);
        try {
            $pegatinas = $value->{"sorteo_interno.pegatinas"};

            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "0", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;
            $puederepetirBono = false;

            $minBetPrice = 0;
            $minBetPrice2 = 0;
            $NUMBERCASINOSTICKERS = '';
            $NUMBERDEPOSITSTICKERS = '';
            $NUMBERSPORTSBOOKSTICKERS = '';


            $Moneda = 'MXN';

            foreach ($sorteodetalles->data as $key2 => $value2) {

                switch ($value2->{"sorteo_detalle.tipo"}) {


                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "REPETIRSORTEO":

                        if ($value2->{"sorteo_detalle.valor"} == '1') {

                            $puederepetirBono = true;
                        }

                        break;
                    case "NUMBERCASINOSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERDEPOSITSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERSPORTSBOOKSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;

                }

            }

            if (true) {


                $messageNot = '';
                if ($pegatinas == 1) {


                    if ($NUMBERCASINOSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='1' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERCASINOSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='1' AND a.estado = 'A' LIMIT " . $NUMBERCASINOSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }
                    if ($NUMBERSPORTSBOOKSTICKERS != '') {


                        // Para Sportbook


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='2' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERSPORTSBOOKSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='2' AND a.estado = 'A' LIMIT " . $NUMBERSPORTSBOOKSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                    // Para Depositos

                    if ($NUMBERDEPOSITSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='3' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERDEPOSITSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='3' AND a.estado = 'A' LIMIT " . $NUMBERDEPOSITSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                } else {

                }


            }

        } catch (Exception $e) {
            print_r($e);
        }
    }

    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    $SorteoInterno = new SorteoInterno();

    $rules = [];


    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "le"));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "ASC", 0, 1000, $json, true, '');

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';
    print_r($data);

    foreach ($data->data as $key => $value) {
        print_r($value);
        try {
            $pegatinas = $value->{"sorteo_interno.pegatinas"};

            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "0", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;
            $puederepetirBono = false;

            $minBetPrice = 0;
            $minBetPrice2 = 0;
            $NUMBERCASINOSTICKERS = '';
            $NUMBERDEPOSITSTICKERS = '';
            $NUMBERSPORTSBOOKSTICKERS = '';


            $Moneda = 'GTQ';

            foreach ($sorteodetalles->data as $key2 => $value2) {

                switch ($value2->{"sorteo_detalle.tipo"}) {


                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "REPETIRSORTEO":

                        if ($value2->{"sorteo_detalle.valor"} == '1') {

                            $puederepetirBono = true;
                        }

                        break;
                    case "NUMBERCASINOSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERDEPOSITSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERSPORTSBOOKSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;

                }

            }

            if (true) {


                $messageNot = '';
                if ($pegatinas == 1) {


                    if ($NUMBERCASINOSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='1' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERCASINOSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='1' AND a.estado = 'A' LIMIT " . $NUMBERCASINOSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }
                    if ($NUMBERSPORTSBOOKSTICKERS != '') {


                        // Para Sportbook


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='2' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERSPORTSBOOKSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='2' AND a.estado = 'A' LIMIT " . $NUMBERSPORTSBOOKSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                    // Para Depositos

                    if ($NUMBERDEPOSITSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='3' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERDEPOSITSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='3' AND a.estado = 'A' LIMIT " . $NUMBERDEPOSITSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                } else {

                }


            }

        } catch (Exception $e) {
            print_r($e);
        }
    }


    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    $SorteoInterno = new SorteoInterno();

    $rules = [];


    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "le"));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "ASC", 0, 1000, $json, true, '');

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';
    print_r($data);

    foreach ($data->data as $key => $value) {
        print_r($value);
        try {
            $pegatinas = $value->{"sorteo_interno.pegatinas"};

            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "0", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;
            $puederepetirBono = false;

            $minBetPrice = 0;
            $minBetPrice2 = 0;
            $NUMBERCASINOSTICKERS = '';
            $NUMBERDEPOSITSTICKERS = '';
            $NUMBERSPORTSBOOKSTICKERS = '';


            $Moneda = 'BRL';

            foreach ($sorteodetalles->data as $key2 => $value2) {

                switch ($value2->{"sorteo_detalle.tipo"}) {


                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "REPETIRSORTEO":

                        if ($value2->{"sorteo_detalle.valor"} == '1') {

                            $puederepetirBono = true;
                        }

                        break;
                    case "NUMBERCASINOSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERDEPOSITSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERSPORTSBOOKSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;

                }

            }

            if (true) {


                $messageNot = '';
                if ($pegatinas == 1) {


                    if ($NUMBERCASINOSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='1' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERCASINOSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='1' AND a.estado = 'A' LIMIT " . $NUMBERCASINOSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }
                    if ($NUMBERSPORTSBOOKSTICKERS != '') {


                        // Para Sportbook


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='2' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERSPORTSBOOKSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='2' AND a.estado = 'A' LIMIT " . $NUMBERSPORTSBOOKSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                    // Para Depositos

                    if ($NUMBERDEPOSITSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='3' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERDEPOSITSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='3' AND a.estado = 'A' LIMIT " . $NUMBERDEPOSITSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                } else {

                }


            }

        } catch (Exception $e) {
            print_r($e);
        }
    }


    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    $SorteoInterno = new SorteoInterno();

    $rules = [];


    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "le"));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "ASC", 0, 1000, $json, true, '');

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';
    print_r($data);

    foreach ($data->data as $key => $value) {
        print_r($value);
        try {
            $pegatinas = $value->{"sorteo_interno.pegatinas"};

            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "0", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;
            $puederepetirBono = false;

            $minBetPrice = 0;
            $minBetPrice2 = 0;
            $NUMBERCASINOSTICKERS = '';
            $NUMBERDEPOSITSTICKERS = '';
            $NUMBERSPORTSBOOKSTICKERS = '';


            $Moneda = 'CLP';

            foreach ($sorteodetalles->data as $key2 => $value2) {

                switch ($value2->{"sorteo_detalle.tipo"}) {


                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "REPETIRSORTEO":

                        if ($value2->{"sorteo_detalle.valor"} == '1') {

                            $puederepetirBono = true;
                        }

                        break;
                    case "NUMBERCASINOSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERDEPOSITSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERSPORTSBOOKSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;

                }

            }

            if (true) {


                $messageNot = '';
                if ($pegatinas == 1) {


                    if ($NUMBERCASINOSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='1' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERCASINOSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='1' AND a.estado = 'A' LIMIT " . $NUMBERCASINOSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }
                    if ($NUMBERSPORTSBOOKSTICKERS != '') {


                        // Para Sportbook


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='2' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERSPORTSBOOKSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='2' AND a.estado = 'A' LIMIT " . $NUMBERSPORTSBOOKSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                    // Para Depositos

                    if ($NUMBERDEPOSITSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='3' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERDEPOSITSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='3' AND a.estado = 'A' LIMIT " . $NUMBERDEPOSITSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                } else {

                }


            }
        } catch (Exception $e) {
            print_r($e);
        }
    }





    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    $SorteoInterno = new SorteoInterno();

    $rules = [];


    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$FromDateLocal", "op" => "le"));
    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ToDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "ASC", 0, 1000, $json, true, '');

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';
    print_r($data);

    foreach ($data->data as $key => $value) {
        print_r($value);
        try {
            $pegatinas = $value->{"sorteo_interno.pegatinas"};

            $SorteoDetalle = new SorteoDetalle();

            //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
            //$bonos = json_decode($bonos);


            $rules = [];

            array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
            array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "0", "op" => "eq"));
            //array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

            $sorteodetalles = json_decode($sorteodetalles);

            $final = [];

            $creditosConvert = 0;

            $cumpleCondicion = false;
            $needSubscribe = false;

            $cumpleCondicionPais = false;
            $cumpleCondicionCont = 0;

            $condicionesProducto = 0;
            $puederepetirBono = false;

            $minBetPrice = 0;
            $minBetPrice2 = 0;
            $NUMBERCASINOSTICKERS = '';
            $NUMBERDEPOSITSTICKERS = '';
            $NUMBERSPORTSBOOKSTICKERS = '';


            $Moneda = 'HNL';

            foreach ($sorteodetalles->data as $key2 => $value2) {

                switch ($value2->{"sorteo_detalle.tipo"}) {


                    case "VISIBILIDAD":

                        if ($value2->{"sorteo_detalle.valor"} == 1) {
                            $needSubscribe = true;

                        } else {
                        }

                        break;


                    case "REPETIRSORTEO":

                        if ($value2->{"sorteo_detalle.valor"} == '1') {

                            $puederepetirBono = true;
                        }

                        break;
                    case "NUMBERCASINOSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERDEPOSITSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;
                    case "NUMBERSPORTSBOOKSTICKERS":

                        if ($value2->{"sorteo_detalle.moneda"} == $Moneda) {

                            $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                        }
                        break;

                }

            }

            if (true) {


                $messageNot = '';
                if ($pegatinas == 1) {


                    if ($NUMBERCASINOSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='1' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERCASINOSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='1' AND a.estado = 'A' LIMIT " . $NUMBERCASINOSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }
                    if ($NUMBERSPORTSBOOKSTICKERS != '') {


                        // Para Sportbook


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='2' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERSPORTSBOOKSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='2' AND a.estado = 'A' LIMIT " . $NUMBERSPORTSBOOKSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                    // Para Depositos

                    if ($NUMBERDEPOSITSTICKERS != '') {


                        $SorteoInterno = new SorteoInterno();
                        $sqlRepiteSorteo = "select count(*) count,usuario_id from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.tipo='3' AND a.estado = 'A' GROUP BY usuario_id

HAVING count >=" . $NUMBERDEPOSITSTICKERS . "
";
                        print_r($sqlRepiteSorteo);

                        $repiteSorteo = $SorteoInterno->execQuery('', $sqlRepiteSorteo);
                        $BonoInterno = new BonoInterno();


                        foreach ($repiteSorteo as $item) {
                            $sqlRepiteSorteo2 = "select * from preusuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id='" . $item->{"a.usuario_id"} . "' AND a.tipo='3' AND a.estado = 'A' LIMIT " . $NUMBERDEPOSITSTICKERS;
                            $repiteSorteo2 = $SorteoInterno->execQuery('', $sqlRepiteSorteo2);

                            $count = 0;

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                            $transaction = $TransjuegoInfoMySqlDAO->getTransaction();

                            foreach ($repiteSorteo2 as $item2) {

                                if ($count == 0) {


                                    $sql = "INSERT INTO usuario_sorteo (sorteo_id, usuario_id, valor, posicion, valor_base, usucrea_id,
                            usumodif_id, estado, error_id, id_externo, mandante, version, apostado, externo_id,
                            valor_premio, premio)

VALUES ( '" . $item2->{"a.sorteo_id"} . "',
       '" . $item2->{"a.usuario_id"} . "',
       1,
       0,
       '" . $item2->{"a.valor_base"} . "',
       0,
       0,
       'A',
       0,
       0,
       '" . $item2->{"a.mandante"} . "',
       1,
       0,
       0,
       0,
       '')";
                                    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
                                }
                                if ($resultsql != '') {


                                    $sql = "UPDATE preusuario_sorteo 
SET preusuario_sorteo.estado='R',preusuario_sorteo.ususorteo_id='" . $resultsql . "' 
WHERE preusuario_sorteo.preususorteo_id = '" . $item2->{"a.preususorteo_id"} . "' ";
                                    $BonoInterno->execUpdate($transaction, $sql);

                                    $messageNot = '¡ Bien :thumbsup: ! Has obtenido un cupon para al Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';


                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                    if (!$ConfigurationEnvironment->isDevelopment()) {

                                        if (false) {

                                            $UsuarioSession = new UsuarioSession();
                                            $rules = [];

                                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                                            $json = json_encode($filtro);


                                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                            $usuarios = json_decode($usuarios);

                                            $usuariosFinal = [];

                                            foreach ($usuarios->data as $key => $value) {

                                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                $WebsocketUsuario->sendWSMessage();

                                            }
                                        }

                                        if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                            $dataSend = $data;
                                            $WebsocketUsuario = new WebsocketUsuario('', '');
                                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                        }
                                    }
                                }

                                $count++;

                            }

                            $transaction->commit();

                        }
                    }


                } else {

                }


            }
        } catch (Exception $e) {
            print_r($e);
        }
    }


    sleep(3);

}

