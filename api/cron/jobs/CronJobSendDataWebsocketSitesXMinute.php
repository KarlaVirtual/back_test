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
use Backend\dto\JackpotDetalle;
use Backend\dto\JackpotInterno;
use Backend\dto\Moneda;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\Registro;
use Backend\dto\SitioTracking;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioToken;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\sql\ConnectionProperty;
use Backend\websocket\WebsocketUsuario;
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
class CronJobSendDataWebsocketSitesXMinute
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
        print_r('executeCronJobSendDataWebsocketSites');
        print_r(PHP_EOL);

        ##SECCION DE JACKPOT
        $JackpotInterno = new JackpotInterno();
        $rules = [];
        //array_push($rules, array("field" => "jackpot_interno.mandante", "data" => "$Mandante", "op" => "eq"));
        array_push($rules, array("field" => "jackpot_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
        // array_push($rules, array("field" => "jackpot_detalle.valor", "data" => "$Pais->paisId", "op" => "eq"));
        array_push($rules, array("field" => "jackpot_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "jackpot_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $Jackpots = $JackpotInterno->getJackpotCustom("jackpot_interno.*, jackpot_detalle.valor pais", "jackpot_interno.jackpot_id", "asc", '0', '1000', $json2, true);

        $Jackpots = json_decode($Jackpots);
        $final = array();
        foreach ($Jackpots->data as $value) {
            $Pais = new \Backend\dto\Pais($value->{'jackpot_detalle.pais'});

            $array = [];
            $array["id"] = $value->{"jackpot_interno.jackpot_id"};
            $array["name"] = $value->{"jackpot_interno.nombre"};
            $array["description"] = $value->{"jackpot_interno.descripcion"};
            $array["currentValue"] = intval($value->{"jackpot_interno.valor_actual"});
            $array["image"] = $value->{"jackpot_interno.imagen"};
            $array["image2"] = $value->{"jackpot_interno.imagen2"};
            $array["gif"] = $value->{"jackpot_interno.gif"};
            $array["startDate"] = $value->{"jackpot_interno.fecha_inicio"};
            $array["endDate"] = $value->{"jackpot_interno.fecha_fin"};
            $array["information"] = $value->{"jackpot_interno.informacion"};

            /** Verificacion para definir contador en enteros o en decimales*/
            $JackpotDetalle = new JackpotDetalle();
            $jackpotCurrency = $JackpotDetalle->cargarDetallesJackpot($value->{"jackpot_interno.jackpot_id"}, 'COUNTERSTYLE');
            $array["counterStyle"] = intval($jackpotCurrency[0]->valor);
            $array["counterStyle"] = 1;

            /** Verificacion para agregar simbolo de moneda */
            $JackpotDetalle = new JackpotDetalle();
            $jackpotCurrency = $JackpotDetalle->cargarDetallesJackpot($value->{"jackpot_interno.jackpot_id"}, 'SHOWCURRENCYSIGN');
            $array["currency"] = null;
            if ($jackpotCurrency[0]->valor == "1") { //Si el usuario eligió que se mostrara el simbolo de la moneda
                $Moneda = new Moneda($jackpotCurrency[0]->moneda);
                $array["currency"] = $Moneda->{"symbol"} . " "; // Devolvemos simbolo de moneda
            };
            if ($final[$value->{"jackpot_interno.mandante"} . '_' . $Pais->iso] == null) {
                $final[$value->{"jackpot_interno.mandante"} . '_' . $Pais->iso] = array();
            }
            array_push($final[$value->{"jackpot_interno.mandante"} . '_' . $Pais->iso], $array);
        }


        foreach ($final as $key => $item) {
            $mandante = explode('_', $key)[0];
            $paisIso = explode('_', $key)[1];
            $dataSend = array(
                "type" => 'getJackpots',
                "data" => $item
            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocketMandantePais($mandante, $paisIso, $dataSend);

        }


        // FRANJAS SUPERIORES
        if (true) {
            $boxes = [];

            $rulesM = array();
            //array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.usuto_id", "data" => "0", "op" => "in"));
            //array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "STRIPETOP", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));


            $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
            $json2 = json_encode($filtroM);

            $SkeepRows = 0;
            $MaxRows = 20;

            $UsuarioMensajecampana = new UsuarioMensajecampana();
            $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" usuario_mensajecampana.* ", "usuario_mensajecampana.usumencampana_id", "asc", $SkeepRows, $MaxRows, $json2, true);


            $usuarios = json_decode($usuarios);

            foreach ($usuarios->data as $key => $value) {
                $Pais = new \Backend\dto\Pais($value->{'usuario_mensajecampana.pais_id'});

                $array = [];

                $array["title"] = $value->{"usuario_mensajecampana.body"};
                $array["url"] = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                /* $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                 $array["open"] = false;
                 $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
                 $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
                 $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};*/

                /*$UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                $UsuarioMensaje->setIsRead(1);

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/

                if ($boxes[$value->{"usuario_mensajecampana.mandante"} . '_' . $Pais->iso] == null) {
                    $boxes[$value->{"usuario_mensajecampana.mandante"} . '_' . $Pais->iso] = array();
                }

                array_push($boxes[$value->{"usuario_mensajecampana.mandante"} . '_' . $Pais->iso], $array);

            }

            foreach ($boxes as $key => $item) {
                $mandante = explode('_', $key)[0];
                $paisIso = explode('_', $key)[1];
                $dataSend = array("data" => array(
                    "boxes" => $item
                ));
                $WebsocketUsuario = new WebsocketUsuario('', '');
                $WebsocketUsuario->sendWSPieSocketMandantePais($mandante, $paisIso, $dataSend);

            }
        }

        $filename = __DIR__ . '/lastrunCronJobSendDataWebsocketSites';
        $datefilename = date("Y-m-d H:i:s", filemtime($filename));

        if ($datefilename <= date("Y-m-d H:i:s", strtotime('-1 minutes'))) {
            unlink($filename);
        }
        $continue = true;

        if (file_exists($filename)) {
            $continue = false;
        }
        if ($continue) {
            try {


                file_put_contents($filename, 'RUN');


                $BonoInterno = new BonoInterno();

                $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='DATAWEBSOCKETSITES'
";


                $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
                $data = $data[0];
                $line = $data->{'proceso_interno2.fecha_ultima'};

                $continue = true;
                if ($line == '') {

                    print_r('PROCCESS OK');
                    unlink($filename);
                    $continue = false;
                }

                $fechaActual = date('Y-m-d H:i:00', strtotime('-1 minute'));

                $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
                $fechaL2 = date('Y-m-d H:i:59', strtotime($fechaActual));


                if ($fechaL1 >= $fechaActual) {

                    print_r('PROCCESS OK');
                    unlink($filename);
                    $continue = false;
                }
                if ($continue) {

                    $BonoInterno = new BonoInterno();
                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='DATAWEBSOCKETSITES';
";


                    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
                    $transaccion->commit();


                    #LOGUEADO EN EL ULTIMO MINUTO


                    #REFRESCAR EN EL ULTIMO MINUTO

                    $sql = "
SELECT usuario.usuario_id, usuario_mandante.usumandante_id,usuario_mandante.mandante,
       max(usuario_token.fecha_modif) fecha_modif
FROM casino.usuario_token
         inner join usuario_mandante on usuario_mandante.usumandante_id = usuario_token.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante

WHERE usuario_token.fecha_modif >= '{$fechaL1}'
  AND usuario_token.fecha_modif <= '{$fechaL2}' 
  group by usuario.usuario_id
  ";

                    $BonoInterno = new BonoInterno();
                    $data = $BonoInterno->execQuery('', $sql);


                    foreach ($data as $valueUltimoMinuto) {
                        $bannerInv = [];
                        $depositPopup = array();
                        $loyalty_price = array();
                        $bonusesDatanew = array();

                        $Usuario = new \Backend\dto\Usuario($valueUltimoMinuto->{"usuario.usuario_id"});
                        $UsuarioMandante = new \Backend\dto\UsuarioMandante($valueUltimoMinuto->{"usuario_mandante.usumandante_id"});
                        $Mandante = new \Backend\dto\Mandante($valueUltimoMinuto->{"usuario_mandante.mandante"});

                        #BANNER DE USUARIOS SIN SALDO
                        if (false) {
                            if ($Usuario->usuarioId == 886 && $Usuario->mandante == 0) {

                                $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->getUsumandanteId() . '","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensaje.fecha_crea", "data": "' . date('Y-m-d') . '","op":"cn"}] ,"groupOp" : "AND"}';
                                $UsuarioMensaje = new UsuarioMensaje();
                                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true, $UsuarioMandante->getUsumandanteId());

                                $usuarios = json_decode($usuarios);

                                if (intval($usuarios->count[0]->{".count"}) > 0 && floatval($Usuario->getBalance()) < 1) {

                                    $array = [];
                                    array_push($array, 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png');
                                    array_push($array, '¡ CLICK PARA DEPOSITAR !');
                                    array_push($array, ':star: ¿TE QUEDASTE SIN SALDO? :moneybag: DEPOSITA Y SIGUE GANANDO ! :credit_card: ¡ Ha llegado VISA ! :smiley: :credit_card: :point_right: ¡HAZ CLICK AQUI! :point_left:');
                                    array_push($array, 'https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv');
                                    array_push($array, '_self');
                                    array_push($array, '');
                                    array_push($array, "isMessage");
                                    array_push($array, "¡ CLICK PARA DEPOSITAR !");

                                    $bannerInv = $array;

                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                    $UsuarioMensaje->isRead = 1;
                                    $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                                    $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = 0;
                                    $UsuarioMensaje->setExternoId(0);


                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                                }


                            }
                        }
                        if (true) {
                            $UsuarioPerfil = new \Backend\dto\UsuarioPerfil($Usuario->usuarioId);

                            #MENSAJES PARA USUARIOS DE CUMPLEAÑOS
                            if ($UsuarioPerfil->perfilId == 'USUONLINE' && $Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S') {
                                // if ( $UsuarioMandante->getUsuarioMandante() =='73818') {
                                $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);
                                //   if ( $UsuarioMandante->getUsuarioMandante() =='73818') {
                                if (strpos(substr($UsuarioOtraInfo->fechaNacim, -5), date('m-d')) !== false) {


                                    $arrayCampanasCumple = array(
                                        '8_66' => 24749
                                    , '0_173' => 48095
                                    , '0_66' => 135650
                                    , '0_60' => 135650
                                    , '0_68' => 135651
                                    , '0_46' => 135651
                                    , '0_94' => 135651
                                    , '23_102' => 157073
                                    , '21_232' => 157111
                                    , '27_94' => 157110
                                    , '27_68' => 157109
                                    );
                                    $arrayBonoCumple = array(
                                        '8_66' => 67638
                                    , '0_173' => 62982
                                    , '0_66' => 46860
                                    , '0_60' => 59255
                                    , '0_68' => 60352
                                    , '0_46' => 63136
                                    , '0_94' => 68613
                                    , '23_102' => 66999
                                    , '21_232' => 74599
                                    , '27_94' => 74522
                                    , '27_68' => 74519
                                    );

                                    foreach ($arrayCampanasCumple as $keyCampana => $valueCampana) {
                                        $partner = explode('_', $keyCampana)[0];
                                        $pais = explode('_', $keyCampana)[1];
                                        if (!($Usuario->mandante == $partner && $Usuario->paisId == $pais)) {
                                            continue;
                                        }
                                        if ($arrayBonoCumple[$keyCampana] == null) {
                                            continue;
                                        }

                                        $rulesM = array();
                                        array_push($rulesM, array("field" => "usuario_mensaje.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "in"));
                                        array_push($rulesM, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                                        array_push($rulesM, array("field" => "usuario_mensaje.fecha_crea", "data" => date('Y-m-d'), "op" => "cn"));
                                        array_push($rulesM, array("field" => "usuario_mensaje.usumencampana_id", "data" => $valueCampana, "op" => "eq"));


                                        $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                                        $json2 = json_encode($filtroM);

                                        $UsuarioMensaje = new UsuarioMensaje();
                                        $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

                                        $usuarios = json_decode($usuarios);

                                        $mensajeEnviado = true;

                                        if (intval($usuarios->count[0]->{".count"}) == 0) {
                                            $mensajeEnviado = false;
                                        }


                                        try {
                                            if (!$mensajeEnviado) {

                                                $UsuarioMensaje = new UsuarioMensaje();
                                                $UsuarioMensaje->usufromId = 0;
                                                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                                $UsuarioMensaje->isRead = 0;
                                                $UsuarioMensaje->body = '';
                                                $UsuarioMensaje->msubject = 'Campaña Cumpleaños';
                                                $UsuarioMensaje->tipo = "MESSAGEINV";
                                                $UsuarioMensaje->parentId = 151931894;
                                                $UsuarioMensaje->proveedorId = $Usuario->mandante;
                                                $UsuarioMensaje->setExternoId(0);
                                                $UsuarioMensaje->usumencampanaId = $valueCampana;


                                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                                                $bonoId = $arrayBonoCumple[$keyCampana];

                                                $UsuarioBono = new UsuarioBono();

                                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

                                                $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                                                $UsuarioBono->setUsuarioId(0);
                                                $UsuarioBono->setBonoId($bonoId);
                                                $UsuarioBono->setValor(0);
                                                $UsuarioBono->setValorBono(0);
                                                $UsuarioBono->setValorBase(0);
                                                $UsuarioBono->setEstado("L");
                                                $UsuarioBono->setErrorId(0);
                                                $UsuarioBono->setIdExterno(0);
                                                $UsuarioBono->setMandante($Usuario->mandante);
                                                $UsuarioBono->setUsucreaId(0);
                                                $UsuarioBono->setUsumodifId(0);
                                                $UsuarioBono->setApostado(0);
                                                $UsuarioBono->setRollowerRequerido(0);
                                                $UsuarioBono->setCodigo("");
                                                $UsuarioBono->setVersion(0);
                                                $UsuarioBono->setExternoId(0);


                                                //print_r($UsuarioBono);

                                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                                $transaccion5->commit();

                                                $Registro = new Registro('', $Usuario->usuarioId);

                                                $CiudadMySqlDAO = new CiudadMySqlDAO();
                                                $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
                                                $detalles = array(
                                                    "Depositos" => 0,
                                                    "DepositoEfectivo" => false,
                                                    "MetodoPago" => 0,
                                                    "ValorDeposito" => 0,
                                                    "PaisPV" => 0,
                                                    "DepartamentoPV" => 0,
                                                    "CiudadPV" => 0,
                                                    "PuntoVenta" => 0,
                                                    "PaisUSER" => $Usuario->paisId,
                                                    "DepartamentoUSER" => $Ciudad->deptoId,
                                                    "CiudadUSER" => $Registro->ciudadId,
                                                    "MonedaUSER" => $Usuario->moneda,

                                                );

                                                $BonoInterno = new BonoInterno();
                                                $BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();

                                                $Transaction = $BonoInternoMySqlDAO->getTransaction();

                                                $detalles = json_decode(json_encode($detalles));

                                                $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction);
                                                $Transaction->commit();

                                            }

                                        } catch (Exception $e) {

                                        }
                                    }
                                }
                            }
                        }

                        #Mensajes invasivos

                        if (true) {


                            //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA
                            $currentDateTime = date('Y-m-d H:i:s');


                            $rulesM = array();
                            array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.usuto_id", "data" => "0", "op" => "in"));
                            array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rulesM, array("field" => "usuario_mensaje2.usumensaje_id", "data" => "NULL", "op" => "isnull"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));


                            $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                            $json2 = json_encode($filtroM);

                            $UsuarioMensajecampana = new UsuarioMensajecampana();
                            $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

                            $usuarios = json_decode($usuarios);

                            foreach ($usuarios->data as $key => $value) {

                                $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Image;
                                $URL = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                                $target = json_decode($value->{"usuario_mensajecampana.t_value"})->Target;

                                $body = $value->{"usuario_mensajecampana.body"};
                                $botonTexto = json_decode($value->{"usuario_mensajecampana.t_value"})->ButtonText;

                                $array = [];
                                array_push($array, $imagen);
                                array_push($array, $botonTexto);
                                array_push($array, $body);
                                array_push($array, $URL);
                                array_push($array, $target);
                                array_push($array, '');
                                array_push($array, "isMessage");
                                array_push($array, $value->{"usuario_mensajecampana.parent_id"});

                                $bannerInv = $array;


                                if (false) {
                                    $bannerInv = [];
                                    $bannerInv['image'] = $array[0];
                                    $bannerInv['buttonText'] = $array[1];
                                    $bannerInv['body'] = $array[2];
                                    $bannerInv['url'] = $array[3];
                                    $bannerInv['target'] = $array[4];
                                    $bannerInv['target2'] = $array[5];
                                    $bannerInv['type'] = 'bannerInvasive';
                                    $bannerInv['parentId'] = $value->{"usuario_mensajecampana.parent_id"};
                                }

                                /*                    $UsuarioMensaje = new UsuarioMensaje();
                                                    $UsuarioMensaje->usufromId = 0;
                                                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                                    $UsuarioMensaje->isRead = 1;
                                                    $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                                                    $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                                    $UsuarioMensaje->parentId = 0;
                                                    $UsuarioMensaje->proveedorId = 0;
                                                    $UsuarioMensaje->setExternoId(0);




                                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/


                                if ($value->{"usuario_mensajecampana.usuto_id"} == '0') {
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                    $UsuarioMensaje->isRead = 1;
                                    $UsuarioMensaje->body = $value->{"usuario_mensajecampana.body"};
                                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);
                                    $UsuarioMensaje->msubject = $value->{"usuario_mensajecampana.msubject"};
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->parentId = $value->{"usuario_mensajecampana.usumensaje_id"};
                                    $UsuarioMensaje->usumencampanaId = $value->{"usuario_mensajecampana.usumencampana_id"};
                                    $UsuarioMensaje->proveedorId = 0;
                                    $UsuarioMensaje->setExternoId(0);
                                    $UsuarioMensaje->setUsucreaId(0);
                                    $UsuarioMensaje->setUsumodifId(0);


                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                                }

                                $seguirBanner = false;

                            }

                            if (oldCount($bannerInv) == 0) {

                                //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA para un USUARIO EN ESPECIFICO
                                $currentDateTime = date('Y-m-d H:i:s');

                                $rulesM = array();
                                array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                                array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
                                array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                                array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                                array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
                                array_push($rulesM, array("field" => "usuario_mensaje2.is_read", "data" => "0", "op" => "eq"));
                                array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));


                                $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                                $json2 = json_encode($filtroM);


                                $UsuarioMensajecampana = new UsuarioMensajecampana();
                                $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", $SkeepRows, $MaxRows, $json2, true, $UsuarioMandante->getUsumandanteId());

                                $usuarios = json_decode($usuarios);

                                foreach ($usuarios->data as $key => $value) {

                                    $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Image;
                                    if ($imagen == "") {
                                        $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Imagen;

                                    }
                                    $URL = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                                    $target = json_decode($value->{"usuario_mensajecampana.t_value"})->Target;


                                    $body = $value->{"usuario_mensajecampana.body"};
                                    $nombre = $value->{"usuario_mensajecampana.nombre"};

                                    /**
                                     * Propósito: devolver las notificaciones
                                     * $Url: trae la url que se envia de pop ups
                                     */


                                    if ($nombre == "JACKPOT POPUP") {
                                        $URL = $value->{"usuario_mensajecampana.t_value"};
                                    }

                                    $botonTexto = json_decode($value->{"usuario_mensajecampana.t_value"})->ButtonText;

                                    $array = [];
                                    array_push($array, $imagen);
                                    array_push($array, $botonTexto);
                                    array_push($array, $body);
                                    array_push($array, $URL);
                                    array_push($array, $target);
                                    array_push($array, '');
                                    array_push($array, "isMessage");
                                    array_push($array, $value->{"usuario_mensajecampana.parent_id"});

                                    $bannerInv = $array;

                                    if (false) {
                                        $bannerInv = [];
                                        $bannerInv['image'] = $array[0];
                                        $bannerInv['buttonText'] = $array[1];
                                        $bannerInv['body'] = $array[2];
                                        $bannerInv['url'] = $array[3];
                                        $bannerInv['target'] = $array[4];
                                        $bannerInv['target2'] = $array[5];
                                        $bannerInv['type'] = 'bannerInvasive';
                                        $bannerInv['parentId'] = $value->{"usuario_mensajecampana.parent_id"};
                                    }
                                    $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje2.usumensaje_id"});
                                    $UsuarioMensaje->isRead = 1;
                                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                                    $seguirBanner = false;

                                }
                            }

                            if (oldCount($bannerInv) == 0) {
                                //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA para un USUARIO EN ESPECIFICO
                                $currentDateTime = date('Y-m-d H:i:s');

                                $rulesM = array();
                                array_push($rulesM, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                                array_push($rulesM, array("field" => "usuario_mensaje.usuto_id", "data" => $UsuarioMandante->getUsumandanteId() . "", "op" => "in"));
                                array_push($rulesM, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                                array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                                array_push($rulesM, array("field" => "usuario_mensaje.fecha_expiracion", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                                array_push($rulesM, array("field" => "usuario_mensaje.is_read", "data" => "0", "op" => "eq"));
                                array_push($rulesM, array("field" => "usuario_mensaje.fecha_crea", "data" => "2025-03-20 00:00:00", "op" => "ge"));


                                $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                                $json2 = json_encode($filtroM);

                                $UsuarioMensaje = new UsuarioMensaje();
                                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

                                $usuarios = json_decode($usuarios);

                                foreach ($usuarios->data as $key => $value) {


                                    $URL = $value->{"usuario_mensaje.msubject"};
                                    $target = '';

                                    $body = $value->{"usuario_mensaje.body"};

                                    $array = [];
                                    array_push($array, $imagen);
                                    array_push($array, $botonTexto);
                                    array_push($array, $body);
                                    array_push($array, $URL);
                                    array_push($array, $target);
                                    array_push($array, '');
                                    array_push($array, "isMessage");
                                    array_push($array, $value->{"usuario_mensaje.parent_id"});

                                    $bannerInv = $array;

                                    if (false) {
                                        $bannerInv = [];
                                        $bannerInv['image'] = $array[0];
                                        $bannerInv['buttonText'] = $array[1];
                                        $bannerInv['body'] = $array[2];
                                        $bannerInv['url'] = $array[3];
                                        $bannerInv['target'] = $array[4];
                                        $bannerInv['target2'] = $array[5];
                                        $bannerInv['type'] = 'bannerInvasive';
                                        $bannerInv['parentId'] = $value->{"usuario_mensaje.parent_id"};
                                    }
                                    $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                                    $UsuarioMensaje->isRead = 1;
                                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                                    $seguirBanner = false;

                                }
                            }
                        }

                        #Verificaciones de cuenta
                        if (true) {
                            try {


                                if (($Usuario->mandante == 8 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-08 00:00:00'))) && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if (($Usuario->mandante == 0 && $Usuario->paisId == 2) && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if (($Usuario->mandante == 23) && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if (($Usuario->mandante == 0 && $Usuario->paisId == 46) && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if (($Usuario->mandante == 0 && $Usuario->paisId == 66) && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if (($Usuario->mandante == 0 && $Usuario->paisId == 94) && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if (($Usuario->mandante == 14 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-27 08:00:00'))) && $Usuario->verifCorreo == "N" && false) {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if (($Usuario->mandante == 17 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-27 08:00:00'))) && $Usuario->verifCorreo == "N") {
                                    //$ConfigurationEnvironment = new ConfigurationEnvironment();
                                    //$ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if ($Usuario->usuarioId == 85 && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                                if ($Usuario->usuarioId == 1508705 && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }

                                if ($Usuario->usuarioId == 1243479 && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }

                                if ($Usuario->mandante == 8 && $Usuario->verifCorreo == "N") {
                                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                                }
                            } catch (Exception $e) {
                            }
                        }

                        #BANNER INVASIVO CON MASCOTA

                        if (true) {

                            // BANNERINV Banner invasivo - Mascota para usuario en especifico


                            $rulesM = array();
                            array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.usuto_id", "data" => "0", "op" => "in"));
                            array_push($rulesM, array("field" => "usuario_mensaje2.usumensaje_id", "data" => "NULL", "op" => "isnull"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "BANNERINV", "op" => "eq"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
                            array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));


                            $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                            $json2 = json_encode($filtroM);

                            $UsuarioMensajecampana = new UsuarioMensajecampana();
                            $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

                            $usuarios = json_decode($usuarios);

                            foreach ($usuarios->data as $key => $value) {

                                $array = [];

                                $array["title"] = $value->{"usuario_mensaje.body"};
                                $array["url"] = $value->{"usuario_mensaje.msubject"};
                                /* $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                                 $array["open"] = false;
                                 $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
                                 $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
                                 $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};*/

                                /*$UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                                $UsuarioMensaje->setIsRead(1);

                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/

                                $urlFrame = explode('##URL##', $value->{"usuario_mensaje.msubject"})[0];
                                $url = explode('##URL##', $value->{"usuario_mensaje.msubject"})[1];

                                $seguirProducto = true;
                                $proveedorReq = 0;

                                if (strpos($urlFrame, 'GAME') !== false) {

                                    $gameid = explode('GAME', $urlFrame)[1];

                                    if (is_numeric($gameid)) {
                                        try {
                                            $Producto = new Producto($gameid);
                                            $ProductoMandante = new ProductoMandante($gameid, $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);
                                            $Proveedor = new Proveedor($Producto->getProveedorId());
                                            $urlFrame = 'https://casino.virtualsoft.tech/game/play/?gameid=' . $ProductoMandante->prodmandanteId . '&mode=real&provider=' . $Proveedor->getAbreviado() . '&lan=es&mode=real&partnerid=' . $UsuarioMandante->getMandante();
                                            $url = $Mandante->baseUrl . '/' . 'casino' . '/' . $ProductoMandante->prodmandanteId;
                                            $proveedorReq = $Proveedor->getProveedorId();
                                        } catch (Exception $e) {
                                            $seguirProducto = false;

                                        }
                                    }
                                } else {
                                    $proveedorReq = $value->{"usuario_mensaje.proveedor_id"};
                                }

                                if ($seguirProducto) {
                                    if ($proveedorReq != 0 && $proveedorReq != '') {

                                        $token = '';


                                        try {

                                            //$UsuarioToken = new UsuarioToken("", $proveedorReq, $UsuarioMandante->getUsumandanteId());
                                            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                                            $token = $UsuarioToken->getToken();
                                        } catch (Exception $e) {

                                            if ($e->getCode() == "21" && false) {

                                                $UsuarioToken = new UsuarioToken();

                                                $UsuarioToken->setRequestId('');
                                                $UsuarioToken->setProveedorId($proveedorReq);
                                                $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                                                $UsuarioToken->setToken($UsuarioToken->createToken());
                                                //$UsuarioToken->setCookie(encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
                                                $UsuarioToken->setUsumodifId(0);
                                                $UsuarioToken->setUsucreaId(0);
                                                $UsuarioToken->setSaldo(0);

                                                $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                                                $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                                                $UsuarioTokenMySqlDAO->getTransaction()->commit();
                                                $token = $UsuarioToken->getToken();


                                            }
                                        }
                                        $urlFrame = $urlFrame . '&token=' . $token;
                                        // $url = $url . '&token='.$token;

                                    }

                                    //'https://demogamesfree.pragmaticplay.net/gs2c/openGame.do?lang=en&cur=USD&gameSymbol=vs40beowulf&lobbyURL=https://doradobet.com/new-casino'
                                    $array = [];

                                    if ($Usuario->mandante == 18) {
                                        array_push($array, 'https://images.virtualsoft.tech/m/msjT1683652624.png');

                                    } else {
                                        array_push($array, 'https://images.virtualsoft.tech/site/doradobet/pet/pet-doradobet.png');

                                    }
                                    array_push($array, 'Hola');
                                    array_push($array, $value->{"usuario_mensaje.body"});
                                    array_push($array, $url);
                                    array_push($array, '_self');
                                    array_push($array, $urlFrame);
                                    array_push($array, "isInvasive");

                                    // $array["title"] = $value->{"usuario_mensaje.body"};
                                    // $array["url"] = $value->{"usuario_mensaje.msubject"};

                                    $bannerInv = $array;

                                    if ($value->{"usuario_mensaje.usuto_id"} == '0') {
                                        $UsuarioMensaje = new UsuarioMensaje();
                                        $UsuarioMensaje->usufromId = 0;
                                        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                        $UsuarioMensaje->isRead = 1;
                                        $UsuarioMensaje->body = str_replace("'", '"', $value->{"usuario_mensaje.body"});
                                        $UsuarioMensaje->msubject = $value->{"usuario_mensaje.msubject"};
                                        $UsuarioMensaje->tipo = "BANNERINV";
                                        $UsuarioMensaje->parentId = $value->{"usuario_mensaje.usumensaje_id"};
                                        $UsuarioMensaje->proveedorId = 0;
                                        $UsuarioMensaje->setExternoId(0);
                                        $UsuarioMensaje->setUsucreaId(0);
                                        $UsuarioMensaje->setUsumodifId(0);


                                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                                    } else {
                                        $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                                        $UsuarioMensaje->isRead = 1;
                                        $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                                    }
                                }

                            }


                        }


                        #POPUPS DE DEPOSITAR PARA LOS USUARIOS QUE TIENEN REGALOS
                        if (true) {
                            $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro_type_gift","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                            $SitioTracking = new SitioTracking();
                            $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                            $sitiosTracking = json_decode($sitiosTracking);

                            $type_gift = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                            if ($type_gift != '' && $type_gift != 'no_quiero_regalos') {
                                $Registro = new Registro('', $Usuario->usuarioId);

                                $arrayimgPromotion = array(
                                    '0_173' => "https://images.virtualsoft.tech/m/msj0212T1712692456.png", // Doradobet, paisId == '173' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '0_173_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557765.png", // Doradobet, saldo bajo
                                    '0_94' => "https://images.virtualsoft.tech/m/msj0212T1714577075.png", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_66' => "https://images.virtualsoft.tech/m/msj212T1708556935.png", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557733.png", // Ecuabet, saldo bajo
                                    '23_N' => "https://images.virtualsoft.tech/m/msj0212T1714507606.png" // Paniplay, verifcedulaAnt == 'N'
                                );

                                $arrayimgDecoration = array(
                                    '0_173' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '173'
                                    '0_173_saldo' => "https://images.virtualsoft.tech/m/msj212T1708556742.png", // Doradobet, saldo bajo
                                    '0_94' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '94'
                                    '8_66' => "https://images.virtualsoft.tech/m/msj212T1708556953.png", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_66_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557116.png", // Ecuabet, saldo bajo
                                    '23_102' => "https://images.virtualsoft.tech/m/msj0212T1708557280.png" // Paniplay, verifcedulaAnt == 'N'
                                );

                                $arraytextButton = array(
                                    '0_173' => "¡Deposita Ya!", // Doradobet, paisId == '173' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '0_173_saldo' => "¡Deposita Ya!", // Doradobet, saldo bajo
                                    '0_94' => "¡Deposita Ya!", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_66' => "¡Deposita Ya!", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_66_saldo' => "¡Deposita Ya!", // Ecuabet, saldo bajo
                                    '23_102' => "¡Ingresa Ya!" // Paniplay, verifcedulaAnt == 'N'
                                );

                                $arrayurlButton = array(
                                    '0_173' => "/gestion/deposito", // Doradobet, saldo bajo
                                    '0_94' => "/gestion/deposito", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_66' => "/gestion/deposito", // Ecuabet, saldo bajo
                                    '23_102' => "/gestion/verificar_cuenta" // Paniplay, verifcedulaAnt == 'N'
                                );
                                $arrayimgPromotionSaldo = array(
                                    '0_173' => "https://images.virtualsoft.tech/m/msj0212T1708557765.png", // Doradobet, saldo bajo
                                    '0_94' => "https://images.virtualsoft.tech/m/msj0212T1714577075.png", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_66' => "https://images.virtualsoft.tech/m/msj0212T1708557733.png", // Ecuabet, saldo bajo
                                    '23_N' => "https://images.virtualsoft.tech/m/msj0212T1714507606.png" // Paniplay, verifcedulaAnt == 'N'
                                );

                                $arrayimgDecorationSaldo = array(
                                    '0_173' => "https://images.virtualsoft.tech/m/msj212T1708556742.png", // Doradobet, saldo bajo
                                    '0_94' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '94'
                                    '8_66' => "https://images.virtualsoft.tech/m/msj0212T1708557116.png", // Ecuabet, saldo bajo
                                    '23_102' => "https://images.virtualsoft.tech/m/msj0212T1708557280.png" // Paniplay, verifcedulaAnt == 'N'
                                );

                                $arraytextButtonSaldo = array(
                                    '0_173' => "¡Deposita Ya!", // Doradobet, saldo bajo
                                    '0_94' => "¡Deposita Ya!", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_66' => "¡Deposita Ya!", // Ecuabet, saldo bajo
                                    '23_102' => "¡Ingresa Ya!" // Paniplay, verifcedulaAnt == 'N'
                                );

                                $arrayurlButtonSaldo = array(
                                    '0_173' => "/gestion/deposito", // Doradobet, saldo bajo
                                    '0_94' => "/gestion/deposito", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                                    '8_66' => "/gestion/deposito", // Ecuabet, saldo bajo
                                    '23_102' => "/gestion/verificar_cuenta" // Paniplay, verifcedulaAnt == 'N'
                                );
                                if ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S' && $Usuario->fechaPrimerdeposito == '') {
                                    array_push($depositPopup,
                                        array(
                                            "imgPromotion" => $arrayimgPromotion[$Usuario->mandante . '_' . $Usuario->paisId]
                                        , "imgDecoration" => $arrayimgDecoration[$Usuario->mandante . '_' . $Usuario->paisId]
                                        , "textButton" => $arraytextButton[$Usuario->mandante . '_' . $Usuario->paisId]
                                        , "urlButton" => $arrayurlButton[$Usuario->mandante . '_' . $Usuario->paisId]
                                        ));
                                } elseif ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S' && $Usuario->fechaPrimerdeposito != '' &&
                                    round((floatval(($Registro->getCreditosBase() * 100)) + floatval(($Registro->getCreditos() * 100))) / 100, 2) < 0.5) {
                                    array_push($depositPopup,
                                        array(
                                            "imgPromotion" => $arrayimgPromotionSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                                        , "imgDecoration" => $arrayimgDecorationSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                                        , "textButton" => $arraytextButtonSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                                        , "urlButton" => $arrayurlButtonSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                                        ));
                                }


                            }
                        }


                        /** Verificación caídas de Jackpots */
                        if (true) {
                            $rules = [];
                            array_push($rules, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                            array_push($rules, array("field" => "usuario_mensaje2.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
                            array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "JACKPOTWINNER", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => 0, "op" => "cn"));
                            $filtroM = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtroM);

                            $UsuarioMensaje = new UsuarioMensaje();
                            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());
                            $usuarios = json_decode($usuarios)->data;

                            $messagesToDesactivate = [];
                            foreach ($usuarios as $jackpotWinnerMessage) {
                                try {
                                    $JackpotInterno = new JackpotInterno($jackpotWinnerMessage->{'usuario_mensaje.body'});
                                } catch (Exception $e) {
                                    break;
                                }

                                $loyalty_price = [[
                                    'uid' => $jackpotWinnerMessage->{'usuario_mensaje.usumensaje_id'},
                                    'id' => $JackpotInterno->jackpotId,
                                    'videoMobile' => $JackpotInterno->videoMobile,
                                    'video' => $JackpotInterno->videoDesktop,
                                    'gif' => $JackpotInterno->gif,
                                    'imagen' => $JackpotInterno->imagen,
                                    'imagen2' => $JackpotInterno->imagen2,
                                    'monto' => round($JackpotInterno->valorActual, 2)
                                ]];
                                $messagesToDesactivate[] = $jackpotWinnerMessage->{'usuario_mensaje.usumensaje_id'};
                            }

                            if (count($messagesToDesactivate) > 0) {
                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $UsuarioMensajeMySqlDAO->updateReadForID(implode(',', $messagesToDesactivate));
                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                            }
                        }

                        if ($UsuarioMandante->getMandante() == 19) {

                            // ----- NOTIFICACIONES PUSH -----
                            $currentDateTime = date('Y-m-d H:i:s');
                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => '-1', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.usufrom_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.usuto_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => $UsuarioMandante->getPaisId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $massiveForCountry = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $massiveForCountry = json_decode($massiveForCountry, true);

                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => '-1', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.usufrom_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.usuto_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                            $massiveForNotCountry = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $massiveForNotCountry = json_decode($massiveForNotCountry, true);

                            $allMassiveMessages = array_merge($massiveForCountry['data'], $massiveForNotCountry['data']);

                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => $UsuarioMandante->getUsumandanteId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.is_read', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => $UsuarioMandante->getPaisId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                            $userMessages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $userMessages = json_decode($userMessages, true);

                            $userMessages['data'] = array_merge($allMassiveMessages, $userMessages['data']);

                            $assignedMessages = [];

                            if (oldCount($allMassiveMessages) > 0) {
                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $ids = array_map(function ($value) {
                                    return $value['usuario_mensaje.usumensaje_id'];
                                }, $allMassiveMessages);
                                $query = $UsuarioMensajeMySqlDAO->queryByParent(implode(',', $ids), $UsuarioMandante->getUsumandanteId(), $currentDateTime);

                                $assignedMessages = array_unique(array_map(function ($value) {
                                    return $value['parent_id'];
                                }, $query));
                            }


                            $pushMessages = [];
                            $updatedMessages = '';

                            foreach ($userMessages['data'] as $key => $value) {
                                $data = [];

                                if ($value['usuario_mensaje.usuto_id'] != $UsuarioMandante->getUsumandanteId() && in_array($value['usuario_mensaje.usumensaje_id'], $assignedMessages) == false) {
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->setUsufromId(0);
                                    $UsuarioMensaje->setUsutoId($UsuarioMandante->getUsumandanteId());
                                    $UsuarioMensaje->setIsRead(1);
                                    $UsuarioMensaje->setMsubject($value['usuario_mensaje.msubject']);
                                    $UsuarioMensaje->setBody($value['usuario_mensaje.body']);
                                    $UsuarioMensaje->setParentId($value['usuario_mensaje.usumensaje_id']);
                                    $UsuarioMensaje->setUsucreaId($value['usuario_mensaje.usucrea_id']);
                                    $UsuarioMensaje->setUsumodifId(0);
                                    $UsuarioMensaje->setTipo($value['usuario_mensaje.tipo']);
                                    $UsuarioMensaje->setExternoId(0);
                                    $UsuarioMensaje->setProveedorId(0);
                                    $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                                    $UsuarioMensaje->setFechaExpiracion($value['usuario_mensaje.fecha_expiracion']);
                                    $UsuarioMensaje->setUsumencampanaId($value['usuario_mensajecampana.usumencampana_id']);

                                    $UsuarioMensaje->setValor1(0);
                                    $UsuarioMensaje->setValor2(0);
                                    $UsuarioMensaje->setValor3(0);

                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $messageID = $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                                    $value['usuario_mensaje.usuto_id'] = $UsuarioMandante->getUsumandanteId();
                                    $value['usuario_mensaje.usumensaje_id'] = $messageID;
                                }

                                if ($value['usuario_mensaje.is_read'] == 0 && !in_array($value['usuario_mensaje.usuto_id'], [-1, 0])) {
                                    $tval = json_decode($value['usuario_mensajecampana.t_value'], true);
                                    $link = "<a href=\"#\" style=\"text-decoration: none; font-size: 1.2em; color: black;\">{$value['usuario_mensaje.body']}</a>";


                                    $data['id'] = $value['usuario_mensaje.usumensaje_id'];
                                    $data['body'] = '<div>' . $link . '</div>';
                                    $updatedMessages .= $value['usuario_mensaje.usumensaje_id'] . ',';

                                    array_push($pushMessages, $data);
                                }
                            }

                            if (!empty($updatedMessages)) {
                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $UsuarioMensajeMySqlDAO->updateReadForID(trim($updatedMessages, ','));
                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                            }


                            $dataSend = array(
                                "messages" => $pushMessages
                            );
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);


                        }

                        ##Franja de Bonos
                        if ($Usuario->mandante == 17 || $Usuario->mandante == 19) {


                            $MaxRows = 100;
                            $OrderedItem = 1;
                            $SkeepRows = 0;
                            $rules = [];
                            array_push($rules, array("field" => "bono_interno.tipo", "data" => '5,6,2,3', "op" => "in"));
                            array_push($rules, array("field" => "bono_interno.estado", "data" => 'A', "op" => "eq"));
                            array_push($rules, array("field" => "bono_interno.mandante", "data" => $Usuario->mandante, "op" => "eq"));
                            array_push($rules, array("field" => "usuario_bono.estado", "data" => 'Q', "op" => "eq"));
                            array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtro);


                            $UsuarioBono = new UsuarioBono();


                            $UsuarioBonos = $UsuarioBono->getUsuarioBonosCustom(" usuario_bono.*,bono_interno.* ", "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json2, true);


                            $bonos = json_decode($UsuarioBonos);


                            if ($bonos->count[0]->{".count"} != "0") {
                                $bonusesDatanew = array();
                                foreach ($bonos->data as $key => $value) {
                                    $array = [];
                                    $array["bonoId"] = $value->{"usuario_bono.bono_id"};
                                    $array["descripcion"] = $value->{"bono_interno.descripcion"};
                                    $array["usubonoId"] = $value->{"usuario_bono.usubono_id"};
                                    $array["valor"] = $value->{"usuario_bono.valor_bono"};
                                    $array["description"] = $value->{"bono_interno.descripcion"};
                                    $array["detailId"] = $value->{"usuario_bono.usubono_id"};
                                    $array["value"] = $value->{"usuario_bono.valor_bono"};
                                    $array["image"] = $value->{"bono_interno.imagen"};

                                    array_push($bonusesDatanew, $array);
                                }
                            }

                        }


                        ##ENVIO DE MENSAJE PARA USUARIO UNICO

                        if (oldCount($depositPopup) > 0) {
                            $dataSend = array(
                                "depositPopup" => $depositPopup,

                            );
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            //$WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

                        }

                        if (oldCount($bannerInv) > 0) {
                            $dataSend = array(
                                "bannerInv" => $bannerInv
                            );
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

                        }
                        if (oldCount($loyalty_price) > 0) {
                            $dataSend = array(
                                "loyalty_price" => $loyalty_price
                            );
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

                        }
                        if (oldCount($bonusesDatanew) > 0) {
                            $dataSend = array(
                                "bonuses" => $bonusesDatanew
                            );
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

                        }


                    }

                }
            } catch (Exception $e) {
                print_r($e);
            }
            unlink($filename);
        }
        print_r('PROCCESS OK');

    }
}

