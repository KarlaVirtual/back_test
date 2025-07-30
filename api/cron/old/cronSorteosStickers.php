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
use Backend\dto\PreUsuarioSorteo;
use Backend\dto\SorteoDetalle;
use Backend\dto\CategoriaProducto;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\TransjuegoInfo;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;

use Backend\dao\TransjuegoInfoDAO;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Exception;


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');
ini_set("display_errors", "OFF");

for($i=0;$i<10;$i++) {
    $message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");


    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='SORTEO'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));


    print_r(PHP_EOL);
    print_r($fechaL1);
    print_r(PHP_EOL);

    print_r($fechaL2);
    print_r(PHP_EOL);


    if ($fechaL1 >= date('Y-m-d H:i:00')) {
        exit();
    }
    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL1 . "' WHERE  tipo='SORTEO';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


//Esto es lo nuevo que yo he puesto


    $sqlApuestasDeportivasUsuarioDiaCierre = "

select transjuego_log.transjuegolog_id,transjuego_log.transaccion_id,producto.subproveedor_id,transaccion_juego.usuario_id,transjuego_log.valor,
       producto.producto_id,
       producto.proveedor_id,
       producto.subproveedor_id,
       producto_mandante.prodmandante_id,
       subproveedor.tipo,
       
       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id
       
from transjuego_log
INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
    WHERE transjuego_log.fecha_crea >= '" . $fechaL1 . "' AND transjuego_log.fecha_crea <= '" . $fechaL2 . "' 
and transjuego_log.tipo LIKE 'DEBIT%' 
";
//AND transjuego_log.transjuegolog_id NOT IN (" . implode(',',$ArrayTransaction) . ")
    $time = time();

    $dataUsuario = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);
    if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {


        $SorteoInterno = new SorteoInterno();

        $rules = [];

        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.pegatinas", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => date('Y-m-d H:i:s'), "op" => "le"));
        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true);

        $data = json_decode($data);

        $pos = 1;

        $sorteosAnalizados = '';

        $ActivacionOtros = true;

        $ActivacionSleepTime = true;

        $ArrayTransaction = array('1');


        foreach ($data->data as $key => $value) {
            print_r(PHP_EOL);
            print_r($value->{"sorteo_interno.sorteo_id"});
            print_r(PHP_EOL);
            try {
                if ($ActivacionOtros) {
                    $debug = false;

                    $BonoInterno = new BonoInterno();


                    if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {

                        $condPaises = array();
                        $condMinBetPrice = array();
                        $condMinBetPrice2 = array();
                        $condIdGame = array();
                        $condIdProvider = array();
                        $condIdSubProvider = array();

                        $puederepetirBono = false;

                        $needSubscribe = false;
                        $SorteoDetalle = new SorteoDetalle();

                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $value->{"sorteo_interno.mandante"}, "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        //Foreach que recorre el sorteo detalle
                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {


                                case "MINBETPRICECASINO":


                                    $condMinBetPrice[$value2->{"sorteo_detalle.moneda"}] = floatval($value2->{"sorteo_detalle.valor"});

                                    break;


                                case "MINBETPRICE2CASINO":


                                    $condMinBetPrice2[$value2->{"sorteo_detalle.moneda"}] = floatval($value2->{"sorteo_detalle.valor"});

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;
                                    }

                                    break;


                                case "USERSUBSCRIBE":

                                    if ($value2->{"sorteo_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;


                                case "CONDPAISUSER":


                                    array_push($condPaises, $value2->{"sorteo_detalle.valor"});

                                    break;

                                case "REPETIRSORTEO":

                                    if ($value2->{"sorteo_detalle.valor"} == '1') {

                                        $puederepetirBono = true;
                                    }

                                    break;


                                default:


                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME') && $value2->{"sorteo_detalle.tipo"} !== 'CONDGAME_ALL') {

                                        array_push($condIdGame, explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1]);

                                    }


                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER') && $value2->{"sorteo_detalle.tipo"} !== 'CONDPROVIDER_ALL') {

                                        array_push($condIdProvider, explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1]);

                                    }


                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDSUBPROVIDER') && $value2->{"sorteo_detalle.tipo"} !== 'CONDSUBPROVIDER_ALL') {

                                        array_push($condIdSubProvider, explode("CONDSUBPROVIDER", $value2->{"sorteo_detalle.tipo"})[1]);

                                    }


                                    break;
                            }

                        }


                        //Foreach que recorre las apuestas

                        foreach ($dataUsuario as $key4 => $datanum) {
                            if ($datanum->{"usuario_mandante.usumandante_id"} == 2177872) {

                                print_r(PHP_EOL);
                                print_r($value->{"sorteo_interno.sorteo_id"});
                                print_r(PHP_EOL);
                                print_r('entro');
                                print_r(PHP_EOL);
                                print_r($datanum->{"usuario_mandante.usumandante_id"});
                                print_r(PHP_EOL);

                            }
                            try {


                                if (in_array($datanum->{"transjuego_log.transjuegolog_id"}, $ArrayTransaction)) {
                                    continue;
                                }
                                if (($value->{"sorteo_interno.mandante"} != $datanum->{"usuario_mandante.mandante"})) {
                                    continue;
                                }


                                $typeP = "CASINO";

                                if ($datanum->{'subproveedor.tipo'} == 'CASINO') {
                                    $typeP = "CASINO";

                                } elseif ($datanum->{'subproveedor.tipo'} == 'LIVECASINO') {
                                    $typeP = "LIVECASINO";
                                }

                                $amount = floatval($datanum->{'transjuego_log.valor'});

                                $pegatinas = $value->{"sorteo_interno.pegatinas"};

                                $final = [];

                                $creditosConvert = 0;

                                $cumpleCondicion = true;

                                $condicionesProducto = 0;
                                $cumpleCondicionProd = false;


                                $condicionesProveedor = 0;
                                $cumpleCondicionProv = false;


                                $condicionesSubProveedor = 0;
                                $cumpleCondicionSubProv = false;


                                $cumpleCondicionCont = 0;
                                $cumpleCondicionPais = false;

                                $minBetPrice = 0;
                                $minBetPrice2 = 0;
                                $NUMBERCASINOSTICKERS = 0;


                                if (oldCount($condPaises) > 0) {

                                    if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                        $cumpleCondicionPais = true;
                                    }
                                    if ($cumpleCondicionPais == false) {
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDPAISUSER')";
                                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                                    }
                                    $cumpleCondicionCont++;
                                }


                                if (oldCount($condIdGame) > 0) {

                                    if (in_array($datanum->{"producto_mandante.prodmandante_id"}, $condIdGame)) {

                                        $cumpleCondicionProd = true;
                                    }
                                    if (in_array('ALL', $condIdGame)) {

                                        $cumpleCondicionProd = true;
                                    }
                                    if ($cumpleCondicionProd == false) {
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDGAME')";
                                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                                    }
                                    $condicionesProducto++;
                                }


                                if (oldCount($condIdProvider) > 0) {

                                    if (in_array($datanum->{"producto.proveedor_id"}, $condIdProvider)) {


                                        $cumpleCondicionProv = true;
                                    }
                                    if (in_array('ALL', $condIdProvider)) {

                                        $cumpleCondicionProv = true;
                                    }
                                    if ($cumpleCondicionProv == false) {
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDPROVIDER')";
                                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                                    }
                                    $condicionesProveedor++;
                                }


                                if (oldCount($condIdSubProvider) > 0) {

                                    if (in_array($datanum->{"producto.subproveedor_id"}, $condIdSubProvider)) {
                                        $cumpleCondicionSubProv = true;
                                    }
                                    if (in_array('ALL', $condIdSubProvider)) {

                                        $cumpleCondicionSubProv = true;
                                    }
                                    if ($cumpleCondicionSubProv == false) {
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','CONDSUBPROVIDER')";
                                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                                    }
                                    $condicionesSubProveedor++;
                                }


                                foreach ($condMinBetPrice as $moneda => $valor) {

                                    if ($moneda == $datanum->{"usuario_mandante.moneda"}) {
                                        $minBetPrice = floatval($valor);
                                    }
                                }

                                foreach ($condMinBetPrice2 as $moneda2 => $valor2) {

                                    if ($moneda2 == $datanum->{"usuario_mandante.moneda"}) {
                                        $minBetPrice2 = floatval($valor2);;
                                    }

                                }


                                if ($condicionesProducto > 0 && !$cumpleCondicionProd) {
                                    $cumpleCondicion = false;
                                }

                                if ($condicionesProveedor > 0 && !$cumpleCondicionProv) {

                                    $cumpleCondicion = false;
                                }
                                if ($condicionesSubProveedor > 0 && !$cumpleCondicionSubProv) {

                                    $cumpleCondicion = false;
                                }

                                if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {


                                    $cumpleCondicion = false;
                                }


                                if ($minBetPrice2 > floatval($datanum->{"transjuego_log.valor"})) {


                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','MINBETPRICE2CASINO')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);

                                }

                                $valorTicket = floatval($datanum->{"transjuego_log.valor"});


                                if ($cumpleCondicion) {

                                    if ($puederepetirBono) {


                                    } else {


                                        $sqlRepiteSorteo = "select * from usuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario_mandante.usumandante_id"} . "'";
                                        $repiteSorteo = execQuery('', $sqlRepiteSorteo);

                                        if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                        } else {
                                            $cumpleCondicion = false;
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','REPETIRSORTEO')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        }

                                    }
                                }


                                if ($needSubscribe) {


                                    $rules = [];
                                    array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'I', 'op' => 'eq']);
                                    array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $value->{"sorteo_interno.sorteo_id"}, 'op' => 'eq']);
                                    array_push($rules, ['field' => 'usuario_sorteo.usuario_id', 'data' => $datanum->{"usuario_mandante.usumandante_id"}, 'op' => 'eq']);

                                    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                                    $UsuarioSorteo2 = new UsuarioSorteo();
                                    $allCoupons = (string)$UsuarioSorteo2->getUsuarioSorteosCustom('COUNT(distinct(usuario_sorteo.usuario_id)) countUsers,COUNT((usuario_sorteo.ususorteo_id)) countStickers', 'usuario_sorteo.ususorteo_id', 'asc', 0, 1000000, $filter, true);

                                    $allCoupons = json_decode($allCoupons, true);


                                    if ($allCoupons['count'][0]['.count'] > 0) {
                                    } else {
                                        $cumpleCondicion = false;
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$datanum->{"usuario_mandante.usuario_mandante"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','CASINO','{$datanum->{"transjuego_log.transjuegolog_id"}}','NEEDSUSCRIBE')";
                                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                                    }
                                }


                                if ($cumpleCondicion) {


                                    $messageNot = '';
                                    if ($pegatinas == 1) {
                                        $estado = "P";
                                        $sqlRepiteSorteo = "select * from preusuario_sorteo a where  a.estado = '" . $estado . "' AND a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario_mandante.usumandante_id"} . "' AND a.tipo='1' AND valor_base > apostado ";
                                        $repiteSorteo = execQuery('', $sqlRepiteSorteo);
                                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                                        $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();


                                        if (oldCount($repiteSorteo) == 0) {
                                            $BonoInterno = new BonoInterno();
                                            $PreUsuarioSorteo = new PreUsuarioSorteo();
                                            $PreUsuarioSorteo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                            $PreUsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                            $PreUsuarioSorteo->valor = 0;
                                            $PreUsuarioSorteo->posicion = 0;
                                            $PreUsuarioSorteo->valorBase = $minBetPrice;
                                            $PreUsuarioSorteo->usucreaId = 0;
                                            $PreUsuarioSorteo->usumodifId = 0;
                                            $PreUsuarioSorteo->mandante = $datanum->{"usuario_mandante.mandante"};
                                            $PreUsuarioSorteo->tipo = 1;
                                            if ($valorTicket < $minBetPrice) {
                                                $PreUsuarioSorteo->estado = "P";

                                            } else {
                                                $PreUsuarioSorteo->estado = "A";
                                            }
                                            $PreUsuarioSorteo->errorId = 0;
                                            $PreUsuarioSorteo->idExterno = 0;
                                            $PreUsuarioSorteo->version = 0;
                                            $PreUsuarioSorteo->apostado = $valorTicket;
                                            $PreUsuarioSorteo->codigo = 0;
                                            $PreUsuarioSorteo->externoId = 0;
                                            $PreUsuarioSorteo->valor = $PreUsuarioSorteo->valor + $creditosConvert;

                                            $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $idUsuSorteo = $PreUsuarioSorteoMySqlDAO->insert($PreUsuarioSorteo);


                                        } else {


                                            $ususorteoId = $repiteSorteo[0]->{"a.preususorteo_id"};
                                            $BonoInterno = new BonoInterno();
                                            $idUsuSorteo = $ususorteoId;

                                            $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();
                                            $sql = "UPDATE preusuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE preususorteo_id =" . $ususorteoId;
                                            $BonoInterno->execQuery($transaccion, $sql);

                                            $sql = "UPDATE preusuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P' AND preususorteo_id =" . $ususorteoId;
                                            $BonoInterno->execQuery($transaccion, $sql);


                                        }
                                        $messageNot = '¡ Bien :thumbsup: ! Estas sumando stickers para el Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                                    } else {
                                        $estado = "P";
                                        $sqlRepiteSorteo = "select * from usuario_sorteo a where   a.estado = '" . $estado . "'  and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario_mandante.usumandante_id"} . "' ";
                                        $repiteSorteo = execQuery('', $sqlRepiteSorteo);
                                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                                        $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();

                                        if (oldCount($repiteSorteo) == 0) {
                                            $UsuarioSorteo = new UsuarioSorteo();
                                            $UsuarioSorteo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                            $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                            $UsuarioSorteo->valor = 0;
                                            $UsuarioSorteo->posicion = 0;
                                            $UsuarioSorteo->valorBase = $minBetPrice;
                                            $UsuarioSorteo->usucreaId = 0;
                                            $UsuarioSorteo->usumodifId = 0;
                                            $UsuarioSorteo->mandante = $datanum->{"usuario_mandante.mandante"};
                                            if ($valorTicket < $minBetPrice) {
                                                $UsuarioSorteo->estado = "P";

                                            } else {
                                                $UsuarioSorteo->estado = "A";
                                            }
                                            $UsuarioSorteo->errorId = 0;
                                            $UsuarioSorteo->idExterno = 0;
                                            $UsuarioSorteo->version = 0;
                                            $UsuarioSorteo->apostado = $valorTicket;
                                            $UsuarioSorteo->codigo = 0;
                                            $UsuarioSorteo->externoId = 0;
                                            $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;

                                            $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);
                                        } else {
                                            $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                            $BonoInterno = new BonoInterno();
                                            $idUsuSorteo = $ususorteoId;


                                            $sql = "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE ususorteo_id =" . $ususorteoId;
                                            $BonoInterno->execQuery($transaccion, $sql);

                                            $sql = "UPDATE usuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P' AND ususorteo_id =" . $ususorteoId;
                                            $BonoInterno->execQuery($transaccion, $sql);

                                        }

                                        $messageNot = '¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                                    }


                                    $TransjuegoInfo = new TransjuegoInfo();
                                    $TransjuegoInfo->productoId = $datanum->{"producto_mandante.prodmandante_id"};
                                    $TransjuegoInfo->transaccionId = $datanum->{"transjuego_log.transaccion_id"};

                                    $TransjuegoInfo->tipo = "SORTEO";
                                    $TransjuegoInfo->descripcion = $idUsuSorteo;
                                    $TransjuegoInfo->valor = $creditosConvert;

                                    $TransjuegoInfo->transapiId = $datanum->{"transjuego_log.transjuegolog_id"};

                                    $TransjuegoInfo->usucreaId = 0;
                                    $TransjuegoInfo->usumodifId = 0;


                                    $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                    $TransjuegoInfoMySqlDAO->getTransaction()->commit();

                                    array_push($ArrayTransaction, $datanum->{"transjuego_log.transjuegolog_id"});
                                    if ($datanum->{"usuario_mandante.usumandante_id"} == 2177872) {


                                        print_r(PHP_EOL);
                                        print_r($value->{"sorteo_interno.sorteo_id"});
                                        print_r(PHP_EOL);
                                        print_r('ENTRO');
                                        print_r(PHP_EOL);
                                        print_r($datanum->{"usuario_mandante.usumandante_id"});
                                        print_r(PHP_EOL);

                                    }

                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                                }
                            } catch (Exception $e) {
                                print_r($e);

                            }
                        }
                    }
                }

            } catch (Exception $e) {
                print_r($e);

            }
        }

    } else {
    }

//Esto es lo nuevo que yo he puesto


    $sqlApuestasDeportivasUsuarioDiaCierre = "

select usuario_recarga.recarga_id,
       usuario_recarga.valor,

       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id,producto.producto_id,proveedor.proveedor_id,subproveedor.subproveedor_id
from usuario_recarga
         INNER JOIN usuario on usuario.usuario_id = usuario_recarga.usuario_id
         INNER JOIN usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id and
                                        usuario_mandante.mandante = usuario.mandante
        LEFT OUTER JOIN transaccion_producto on transaccion_producto.final_id=usuario_recarga.recarga_id
        LEFT OUTER JOIN producto on producto.producto_id=transaccion_producto.producto_id
        LEFT OUTER JOIN proveedor on producto.proveedor_id=proveedor.proveedor_id
        LEFT OUTER JOIN subproveedor on producto.subproveedor_id=subproveedor.subproveedor_id

where  usuario_recarga.fecha_crea >= '" . $fechaL1 . "' AND usuario_recarga.fecha_crea <= '" . $fechaL2 . "' 

";
    $time = time();

    $dataUsuario = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);
    if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {


        $SorteoInterno = new SorteoInterno();

        $rules = [];

        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.pegatinas", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.habilita_deposito", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => date('Y-m-d H:i:s'), "op" => "le"));
        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true);

        $data = json_decode($data);

        $pos = 1;

        $sorteosAnalizados = '';

        $ActivacionOtros = true;

        $ActivacionSleepTime = true;

        $ArrayTransaction = array('1');


        foreach ($data->data as $key => $value) {
            print_r(PHP_EOL);
            print_r($value->{"sorteo_interno.sorteo_id"});
            print_r(PHP_EOL);
            try {
                if ($ActivacionOtros) {
                    $debug = false;

                    $BonoInterno = new BonoInterno();


                    if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {

                        $condPaises = array();
                        $condMinBetPrice = array();
                        $condMinBetPrice2 = array();
                        $condIdGame = array();
                        $condIdProvider = array();
                        $condIdSubProvider = array();

                        $puederepetirBono = false;

                        $needSubscribe = false;
                        $SorteoDetalle = new SorteoDetalle();

                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $value->{"sorteo_interno.mandante"}, "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        //Foreach que recorre el sorteo detalle
                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {


                                case "MINBETPRICEDEPOSIT":


                                    $condMinBetPrice[$value2->{"sorteo_detalle.moneda"}] = floatval($value2->{"sorteo_detalle.valor"});

                                    break;


                                case "MINBETPRICE2DEPOSIT":


                                    $condMinBetPrice2[$value2->{"sorteo_detalle.moneda"}] = floatval($value2->{"sorteo_detalle.valor"});

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;
                                    }

                                    break;


                                case "USERSUBSCRIBE":

                                    if ($value2->{"sorteo_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;


                                case "CONDPAISUSER":


                                    array_push($condPaises, $value2->{"sorteo_detalle.valor"});

                                    break;

                                case "REPETIRSORTEO":

                                    if ($value2->{"sorteo_detalle.valor"} == '1') {

                                        $puederepetirBono = true;
                                    }

                                    break;


                                default:


                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONPAYMENT') && $value2->{"sorteo_detalle.tipo"} !== 'CONPAYMENT_ALL') {

                                        array_push($condIdGame, explode("CONPAYMENT", $value2->{"sorteo_detalle.tipo"})[1]);

                                    }


                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER') && $value2->{"sorteo_detalle.tipo"} !== 'CONDPROVIDER_ALL') {

                                        if (explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1] != '') {
                                            array_push($condIdProvider, explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1]);
                                        }
                                    }


                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDSUBPROVIDER') && $value2->{"sorteo_detalle.tipo"} !== 'CONDSUBPROVIDER_ALL') {

                                        if (explode("CONDSUBPROVIDER", $value2->{"sorteo_detalle.tipo"})[1] != '') {
                                            array_push($condIdSubProvider, explode("CONDSUBPROVIDER", $value2->{"sorteo_detalle.tipo"})[1]);

                                        }

                                    }


                                    break;
                            }

                        }


                        //Foreach que recorre las apuestas

                        foreach ($dataUsuario as $key4 => $datanum) {
                            if ($datanum->{"usuario_mandante.usumandante_id"} == 2177872) {

                                print_r(PHP_EOL);
                                print_r($value->{"sorteo_interno.sorteo_id"});
                                print_r(PHP_EOL);
                                print_r('entro');
                                print_r(PHP_EOL);
                                print_r($datanum->{"usuario_mandante.usumandante_id"});
                                print_r(PHP_EOL);

                            }
                            try {


                                if (in_array($datanum->{"usuario_recarga.recarga_id"}, $ArrayTransaction)) {
                                    continue;
                                }
                                if (($value->{"sorteo_interno.mandante"} != $datanum->{"usuario_mandante.mandante"})) {
                                    continue;
                                }


                                $typeP = "DEPOSITO";

                                $amount = floatval($datanum->{'usuario_recarga.valor'});

                                $pegatinas = $value->{"sorteo_interno.pegatinas"};

                                $final = [];

                                $creditosConvert = 0;

                                $cumpleCondicion = true;

                                $condicionesProducto = 0;
                                $cumpleCondicionProd = false;


                                $condicionesProveedor = 0;
                                $cumpleCondicionProv = false;


                                $condicionesSubProveedor = 0;
                                $cumpleCondicionSubProv = false;


                                $cumpleCondicionCont = 0;
                                $cumpleCondicionPais = false;

                                $minBetPrice = 0;
                                $minBetPrice2 = 0;
                                $NUMBERCASINOSTICKERS = 0;


                                if (oldCount($condPaises) > 0) {

                                    if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;
                                }


                                if (oldCount($condIdGame) > 0) {

                                    if (in_array($datanum->{"producto.producto_id"}, $condIdGame)) {

                                        $cumpleCondicionProd = true;
                                    }
                                    if (in_array('ALL', $condIdGame)) {

                                        $cumpleCondicionProd = true;
                                    }
                                    $condicionesProducto++;
                                }


                                if (oldCount($condIdProvider) > 0) {

                                    if (in_array($datanum->{"producto.proveedor_id"}, $condIdProvider)) {


                                        $cumpleCondicionProv = true;
                                    }
                                    if (in_array('ALL', $condIdProvider)) {

                                        $cumpleCondicionProv = true;
                                    }
                                    $condicionesProveedor++;
                                }


                                if (oldCount($condIdSubProvider) > 0) {

                                    if (in_array($datanum->{"producto.subproveedor_id"}, $condIdSubProvider)) {
                                        $cumpleCondicionSubProv = true;
                                    }
                                    if (in_array('ALL', $condIdSubProvider)) {

                                        $cumpleCondicionSubProv = true;
                                    }
                                    $condicionesSubProveedor++;
                                }


                                foreach ($condMinBetPrice as $moneda => $valor) {

                                    if ($moneda == $datanum->{"usuario_mandante.moneda"}) {
                                        $minBetPrice = floatval($valor);
                                    }
                                }

                                foreach ($condMinBetPrice2 as $moneda2 => $valor2) {

                                    if ($moneda2 == $datanum->{"usuario_mandante.moneda"}) {
                                        $minBetPrice2 = floatval($valor2);;
                                    }

                                }


                                if ($condicionesProducto > 0 && !$cumpleCondicionProd) {
                                    $cumpleCondicion = false;
                                }

                                if ($condicionesProveedor > 0 && !$cumpleCondicionProv) {

                                    $cumpleCondicion = false;
                                }
                                if ($condicionesSubProveedor > 0 && !$cumpleCondicionSubProv) {

                                    $cumpleCondicion = false;
                                }

                                if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {


                                    $cumpleCondicion = false;
                                }


                                if ($minBetPrice2 > floatval($datanum->{"usuario_recarga.valor"})) {


                                    $cumpleCondicion = false;

                                }

                                $valorTicket = floatval($datanum->{"usuario_recarga.valor"});


                                if ($cumpleCondicion) {

                                    if ($puederepetirBono) {


                                    } else {


                                        $sqlRepiteSorteo = "select * from usuario_sorteo a where  a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario_mandante.usumandante_id"} . "'";
                                        $repiteSorteo = execQuery('', $sqlRepiteSorteo);

                                        if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                        } else {
                                            $cumpleCondicion = false;
                                        }

                                    }
                                }


                                if ($needSubscribe) {


                                    $rules = [];
                                    array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'I', 'op' => 'eq']);
                                    array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $value->{"sorteo_interno.sorteo_id"}, 'op' => 'eq']);
                                    array_push($rules, ['field' => 'usuario_sorteo.usuario_id', 'data' => $datanum->{"usuario_mandante.usumandante_id"}, 'op' => 'eq']);

                                    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                                    $UsuarioSorteo2 = new UsuarioSorteo();
                                    $allCoupons = (string)$UsuarioSorteo2->getUsuarioSorteosCustom('COUNT(distinct(usuario_sorteo.usuario_id)) countUsers,COUNT((usuario_sorteo.ususorteo_id)) countStickers', 'usuario_sorteo.ususorteo_id', 'asc', 0, 1000000, $filter, true);

                                    $allCoupons = json_decode($allCoupons, true);


                                    if ($allCoupons['count'][0]['.count'] > 0) {
                                    } else {
                                        $cumpleCondicion = false;
                                    }
                                }


                                if ($cumpleCondicion) {


                                    $messageNot = '';
                                    if ($pegatinas == 1) {
                                        $estado = "P";
                                        $sqlRepiteSorteo = "select * from preusuario_sorteo a where  a.estado = '" . $estado . "' AND a.ususorteo_id IS NULL and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario_mandante.usumandante_id"} . "' AND a.tipo='3' AND valor_base > apostado ";
                                        $repiteSorteo = execQuery('', $sqlRepiteSorteo);
                                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                                        $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();


                                        if (oldCount($repiteSorteo) == 0) {
                                            $BonoInterno = new BonoInterno();
                                            $PreUsuarioSorteo = new PreUsuarioSorteo();
                                            $PreUsuarioSorteo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                            $PreUsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                            $PreUsuarioSorteo->valor = 0;
                                            $PreUsuarioSorteo->posicion = 0;
                                            $PreUsuarioSorteo->valorBase = $minBetPrice;
                                            $PreUsuarioSorteo->usucreaId = 0;
                                            $PreUsuarioSorteo->usumodifId = 0;
                                            $PreUsuarioSorteo->mandante = $datanum->{"usuario_mandante.mandante"};
                                            $PreUsuarioSorteo->tipo = 3;
                                            if ($valorTicket < $minBetPrice) {
                                                $PreUsuarioSorteo->estado = "P";

                                            } else {
                                                $PreUsuarioSorteo->estado = "A";
                                            }
                                            $PreUsuarioSorteo->errorId = 0;
                                            $PreUsuarioSorteo->idExterno = 0;
                                            $PreUsuarioSorteo->version = 0;
                                            $PreUsuarioSorteo->apostado = $valorTicket;
                                            $PreUsuarioSorteo->codigo = 0;
                                            $PreUsuarioSorteo->externoId = 0;
                                            $PreUsuarioSorteo->valor = $PreUsuarioSorteo->valor + $creditosConvert;

                                            $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $idUsuSorteo = $PreUsuarioSorteoMySqlDAO->insert($PreUsuarioSorteo);


                                        } else {


                                            $ususorteoId = $repiteSorteo[0]->{"a.preususorteo_id"};
                                            $BonoInterno = new BonoInterno();
                                            $idUsuSorteo = $ususorteoId;

                                            $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();
                                            $sql = "UPDATE preusuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE preususorteo_id =" . $ususorteoId;
                                            $BonoInterno->execQuery($transaccion, $sql);

                                            $sql = "UPDATE preusuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P' AND preususorteo_id =" . $ususorteoId;
                                            $BonoInterno->execQuery($transaccion, $sql);


                                        }
                                        $messageNot = '¡ Bien :thumbsup: ! Estas sumando stickers para el Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                                    } else {
                                        $estado = "P";
                                        $sqlRepiteSorteo = "select * from usuario_sorteo a where   a.estado = '" . $estado . "'  and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario_mandante.usumandante_id"} . "' ";
                                        $repiteSorteo = execQuery('', $sqlRepiteSorteo);
                                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
                                        $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();

                                        if (oldCount($repiteSorteo) == 0) {
                                            $UsuarioSorteo = new UsuarioSorteo();
                                            $UsuarioSorteo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                            $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                            $UsuarioSorteo->valor = 0;
                                            $UsuarioSorteo->posicion = 0;
                                            $UsuarioSorteo->valorBase = $minBetPrice;
                                            $UsuarioSorteo->usucreaId = 0;
                                            $UsuarioSorteo->usumodifId = 0;
                                            $UsuarioSorteo->mandante = $datanum->{"usuario_mandante.mandante"};
                                            if ($valorTicket < $minBetPrice) {
                                                $UsuarioSorteo->estado = "P";

                                            } else {
                                                $UsuarioSorteo->estado = "A";
                                            }
                                            $UsuarioSorteo->errorId = 0;
                                            $UsuarioSorteo->idExterno = 0;
                                            $UsuarioSorteo->version = 0;
                                            $UsuarioSorteo->apostado = $valorTicket;
                                            $UsuarioSorteo->codigo = 0;
                                            $UsuarioSorteo->externoId = 0;
                                            $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;

                                            $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                            $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);
                                        } else {
                                            $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                            $BonoInterno = new BonoInterno();
                                            $idUsuSorteo = $ususorteoId;

                                            $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();

                                            $sql = "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE ususorteo_id =" . $ususorteoId;
                                            $BonoInterno->execQuery($transaccion, $sql);

                                            $sql = "UPDATE usuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P' AND ususorteo_id =" . $ususorteoId;
                                            $BonoInterno->execQuery($transaccion, $sql);

                                        }

                                        $messageNot = '¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                                    }

                                    array_push($ArrayTransaction, $datanum->{"usuario_recarga.recarga_id"});
                                    if ($datanum->{"usuario_mandante.usumandante_id"} == 2177872) {


                                        print_r(PHP_EOL);
                                        print_r($value->{"sorteo_interno.sorteo_id"});
                                        print_r(PHP_EOL);
                                        print_r('ENTRO');
                                        print_r(PHP_EOL);
                                        print_r($datanum->{"usuario_mandante.usumandante_id"});
                                        print_r(PHP_EOL);

                                    }

                                    $mensajesRecibidos = [];
                                    $array = [];

                                    $array["body"] = $messageNot;

                                    array_push($mensajesRecibidos, $array);
                                    $data = array();
                                    $data["messages"] = $mensajesRecibidos;
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $transaccion->commit();

                                }
                            } catch (Exception $e) {
                                print_r($e);

                            }
                        }
                    }
                }

            } catch (Exception $e) {
                print_r($e);

            }
        }

    } else {
    }

    sleep(3);

}
    /**
     * Ejecutar un query
     *
     *
     * @param Objeto transaccion transaccion
     * @param String sql sql
     *
     * @return Array $result resultado de la verificación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function execQuery($transaccion, $sql)
    {

        $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO($transaccion);
        $return = $SorteoInternoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }


