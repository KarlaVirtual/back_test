<?php

/**
 * CronJobDispatchWebSocket
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 25/02/2025
 */

use Backend\dto\BonoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\WebsocketNotificacion;
use Backend\websocket\WebsocketUsuario;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\WebsocketNotificacionMySqlDAO;

class CronJobDispatchWebSocket
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

            $filename = __DIR__ . '/lastrunCronJobDispatchWebSocket';

            $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='WEBSOCKETNOTIFICACION'";

            $data = $this->BonoInterno->execQuery('', $sqlProcesoInterno2);
            $data = $data[0];
            $line = $data->{'proceso_interno2.fecha_ultima'};

            if ($line == '') return;

            $fecha = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
            if ($fecha >= date('Y-m-d H:i:00')) {
                //exit();
            }
            $filename .= str_replace(' ', '-', str_replace(':', '-', $fecha));

            if (file_exists($filename)) {
                $datefilename = date("Y-m-d H:i:s", filemtime($filename));
                if ($datefilename <= date("Y-m-d H:i:s", strtotime('-2 minute'))) unlink($filename);
                return;
            }


            file_put_contents($filename, 'RUN');

            $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fecha . "' WHERE  tipo='WEBSOCKETNOTIFICACION';";

            $data = $this->BonoInterno->execQuery($this->transaccion, $sqlProcesoInterno2);
            $this->transaccion->commit();

            $websocketNotificacion = new WebsocketNotificacion();

            $rules = [];
            array_push($rules, ['field' => 'websocket_notificacion.estado', 'data' => "'P','E'", 'op' => 'in']);
            array_push($rules, ['field' => 'websocket_notificacion.fecha_exp', 'data' => date('Y-m-d H:i:s'), 'op' => 'ge']);

            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $websocketsNotificaciones = $websocketNotificacion->queryWebsocketNotificacionCustom("websocket_notificacion.*", "websocket_notificacion.websocketnotificacion_id", "desc", 0, 20, $filters, true);

            $websocketsNotificaciones = json_decode((string) $websocketsNotificaciones);

            foreach ($websocketsNotificaciones->data as $websocketNotificacion) {

                $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
                $idUsuMandante = $UsuarioMandanteMySqlDAO->queryByUsuarioMandante($websocketNotificacion->{'websocket_notificacion.usuario_id'});
                $UsuarioMandante = new UsuarioMandante($idUsuMandante[0]->usumandanteId);
                $socket = new WebsocketNotificacion($websocketNotificacion->{'websocket_notificacion.websocketnotificacion_id'});
                $this->sendWebsocket($UsuarioMandante, $socket);
            }

            unlink($filename);
        } catch (\Throwable $th) {
            if ($_ENV['debug']) {
                print_r($th->getMessage());
            }
            throw $th;
        }
    }

    /**
     * Envía la información por websocket
     * @param UsuarioMandante $usuarioMandante
     * @param array $data
     * @return void
     */
    private function sendWebsocket(UsuarioMandante $usuarioMandante, WebsocketNotificacion $socket)
    {
        $websocketNotification = new WebsocketNotificacion($socket->getWebsocketnotificacionId());
        $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO($this->transaccion);
        $websocketNotification->setEstado('E');
        $result = $websocketNotificacionMySqlDAO->update($websocketNotification, " AND estado in ('P','E')");

        if ($result > 0) {
            $this->transaccion->commit();
            $dataSend = [
                "data" => $websocketNotification->getMensaje(),
                "id_notification" => $websocketNotification->getWebsocketnotificacionId()
            ];
    
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($usuarioMandante, $dataSend, true);
        }else {
            $this->transaccion->rollback();
            return;            
        }
    }
}
