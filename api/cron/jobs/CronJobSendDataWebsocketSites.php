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
class CronJobSendDataWebsocketSites
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
                //$WebsocketUsuario->sendWSPieSocketMandantePais($mandante, $paisIso, $dataSend);

            }
        }
        print_r('PROCCESS 1');

        $filename = __DIR__ . '/lastrunCronJobSendDataWebsocketSites';
        $datefilename = date("Y-m-d H:i:s", filemtime($filename));

        if ($datefilename <= date("Y-m-d H:i:s", strtotime('-1 minutes'))) {
            unlink($filename);
        }
        $continue = true;

        if (file_exists($filename)) {
            $continue = false;
            print_r('PROCCESS ERROR');

        }
        print_r('PROCCESS OK');

    }
}

