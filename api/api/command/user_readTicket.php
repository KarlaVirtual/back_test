<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\FlujoCaja;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/user_readTicket
 *
 * Gestión de estados de usuario y pago de tickets
 *
 * Este recurso permite gestionar los estados de usuario en la aplicación y procesar el pago de tickets según el estado recibido.
 *
 * @param object $json : Objeto JSON recibido con los parámetros de la solicitud.
 * @param object $json ->session : Datos de la sesión del usuario.
 * @param string $json ->session->usuario : Identificador del usuario en sesión.
 * @param string $json ->session->usuarioip : Dirección IP del usuario en sesión.
 * @param object $json ->params : Parámetros específicos de la solicitud.
 * @param string $json ->params->state : Estado de la operación a realizar (cancel, create, readSuccess, payTicket).
 * @param string|null $json ->params->result : Resultado de la operación en caso de estar disponible.
 * @param string|null $json ->params->ticket : Identificador del ticket en caso de que el estado sea "payTicket".
 *
 * @return object  $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *result* (int): Indica si la operación fue exitosa (1) o fallida (0).
 *  - *data* (array): Contiene el resultado de la consulta o acción ejecutada.
 *
 * @throws Exception Si hay un error en la conexión con la base de datos.
 * @throws Exception Si el estado recibido no es válido o falta información requerida.
 * @throws Exception Si ocurre un error inesperado durante el procesamiento de la solicitud.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crea un objeto UsuarioMandante y se inicializan variables para la respuesta. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ClientId = $UsuarioMandante->getUsuarioMandante();
$state = $json->params->state;


$response = array();

/* Código PHP que estructura una respuesta JSON con resultados y un identificador. */
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["result"] = 0;

$response["data"] = array(
    "result" => 0


);

if ($state == "cancel") {


    /* Se definen variables y un arreglo vacío para almacenamiento de reglas en programación. */
    $MaxRows = 1;
    $OrderedItem = 1;
    $SkeepRows = 0;


    $rules = [];


    /* Se crean reglas de filtrado para un registro de usuario en formato JSON. */
    array_push($rules, array("field" => "usuario_log.usuario_id", "data" => "$ClientId", "op" => "eq"));
    array_push($rules, array("field" => "usuario_log.tipo", "data" => "ESTADOUSUARIO", "op" => "eq"));
    array_push($rules, array("field" => "usuario_log.valor_antes", "data" => "READTICKET", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json2 = json_encode($filtro);


    /* establece la configuración regional en checo y crea una instancia de UsuarioLog. */
    setlocale(LC_ALL, 'czech');


    $select = " usuario_log.* ";


    $UsuarioLog = new UsuarioLog();

    /* obtiene registros de usuario y actualiza su estado. */
    $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, $grouping);
    $data = json_decode($data);

    $UsuarioLog = new UsuarioLog($data->data[0]->{"usuario_log.usuariolog_id"});

    $UsuarioLog->setEstado("NA");

    /* Se actualiza un registro de usuario en la base de datos mediante un DAO. */
    $UsuarioLog->setUsuarioaprobarId("697");
    $UsuarioLog->setUsuarioaprobarIp("11");

    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
    $UsuarioLogMySqlDAO->update($UsuarioLog);

    $UsuarioLogMySqlDAO->getTransaction()->commit();

    /* Asigna el ID de usuario a la respuesta en formato estructurado. */
    $response["data"]["tt"] = $data->data[0]->{"usuario_log.usuariolog_id"};

}

if ($state == "create") {


    /* registra información del usuario y su IP en un objeto UsuarioLog. */
    $UsuarioLog = new UsuarioLog();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp($json->session->usuarioip);
    $UsuarioLog->setUsuariosolicitaId($ClientId);
    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
    $UsuarioLog->setTipo("ESTADOUSUARIO");

    /* Se configura un registro de usuario con estado y valores específicos en MySQL. */
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes("READTICKET");
    $UsuarioLog->setValorDespues("");
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);
    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

    /* inserta un registro de usuario y confirma la transacción en MySQL. */
    $UsuarioLogMySqlDAO->insert($UsuarioLog);

    $UsuarioLogMySqlDAO->getTransaction()->commit();

    $profile_id = array();
    $profile_id['id'] = 'tt';


    /* Se crea un array en PHP que define una acción para leer un ticket. */
    $data = array(
        "data" => array(
            "action" => "readTicket"
        )

    );


    /* Se crean instancias de Proveedor, UsuarioToken y WebsocketUsuario con datos específicos. */
    $Proveedor = new Proveedor("", "IES");


    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());

    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);


    /* Envía un mensaje a través de un websocket usando el objeto WebsocketUsuario. */
    $WebsocketUsuario->sendWSMessage();


}
if ($state == "readSuccess") {


    /* Extrae resultados de un objeto JSON y define variables iniciales para procesamiento. */
    $result = $json->params->result;

    $MaxRows = 1;
    $OrderedItem = 1;
    $SkeepRows = 0;


    $rules = [];


    /* Se crea un filtro en JSON con condiciones para consultas de usuario. */
    array_push($rules, array("field" => "usuario_log.usuario_id", "data" => "$ClientId", "op" => "eq"));
    array_push($rules, array("field" => "usuario_log.tipo", "data" => "ESTADOUSUARIO", "op" => "eq"));
    array_push($rules, array("field" => "usuario_log.valor_antes", "data" => "READTICKET", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json2 = json_encode($filtro);


    /* Se configura la localización a checa y se inicia una instancia de UsuarioLog. */
    setlocale(LC_ALL, 'czech');


    $select = " usuario_log.* ";


    $UsuarioLog = new UsuarioLog();

    /* Obtiene y decodifica logs de usuario, luego establece un nuevo valor. */
    $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, $grouping);
    $data = json_decode($data);

    $UsuarioLog = new UsuarioLog($data->data[0]->{"usuario_log.usuariolog_id"});

    $UsuarioLog->setValorDespues($result);

    /* Actualiza el estado y la información de un usuario en la base de datos. */
    $UsuarioLog->setEstado("A");
    $UsuarioLog->setUsuarioaprobarId("697");
    $UsuarioLog->setUsuarioaprobarIp("11");

    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
    $UsuarioLogMySqlDAO->update($UsuarioLog);


    /* confirma una transacción y asigna un valor a la respuesta. */
    $UsuarioLogMySqlDAO->getTransaction()->commit();
    $response["data"]["tt"] = $data->data[0]->{"usuario_log.usuariolog_id"};

}


if ($state == "payTicket") {


    /* Asigna el valor del ticket, o el resultado si el ticket está vacío. */
    $ticket = $json->params->ticket;

    if ($ticket == "") {
        $ticket = $json->params->result;

    }

    /* Variables inicializan parámetros para limitar filas y ordenar elementos en un contexto específico. */
    $MaxRows = 1;
    $OrderedItem = 1;
    $SkeepRows = 0;

    try {
        /*
$ticket = "ITN" . $ticket;

     $TransaccionSportsbook = new \Backend\dto\TransaccionSportsbook('', $ticket, '');

    if ($TransaccionSportsbook->getPremiado() == "S") {

        if ($TransaccionSportsbook->getPremioPagado() == "N") {

            $responseTicket = $TransaccionSportsbook->pagar($UsuarioMandante);

            if ($responseTicket) {

                $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();

                $response["data"]["result"] = 1;
            }


        } else {

        }

    } else {

    }*/


        try {

            /* Se crea un objeto ItTicketEnc y se asigna el ticket a la respuesta. */
            $ItTicketEnc = new ItTicketEnc($ticket);
            $response["data"]["tt"] = $ticket;

            if ($ItTicketEnc->premiado == "S" && $ItTicketEnc->premioPagado == "N") {


                /* Actualiza el estado de un ticket y registra la fecha y hora del pago. */
                $ItTicketEnc->premioPagado = 'S';
                $ItTicketEnc->usumodificaId = $UsuarioMandante->usuarioMandante;
                $ItTicketEnc->fechaPago = date('Y-m-d');
                $ItTicketEnc->horaPago = date('H:i:s');

                $ItTicketEnc->fechaModifica = $ItTicketEnc->fechaPago . ' ' . $ItTicketEnc->horaPago;

                /* Se inicializan propiedades en un objeto y se crea un DAO para MySQL. */
                $ItTicketEnc->beneficiarioId = 0;
                $ItTicketEnc->tipoBeneficiario = 0;
                $ItTicketEnc->beneficiarioId = 0;
                $ItTicketEnc->impuesto = '0';


                $ItTicketEncMySqlDAO = new \Backend\mysql\ItTicketEncMySqlDAO();

                /* Actualiza un ticket y calcula el valor a pagar después de impuestos. */
                $ItTicketEncMySqlDAO->update($ItTicketEnc);
                $Transaction = $ItTicketEncMySqlDAO->getTransaction();

                $impuesto = 0;

                $ValorAPagar = $ItTicketEnc->vlrPremio - $impuesto;


                /* Crea un objeto "FlujoCaja" y establece sus propiedades relacionadas con un movimiento financiero. */
                $FlujoCaja = new FlujoCaja();
                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($UsuarioMandante->usuarioMandante);
                $FlujoCaja->setTipomovId('S');
                $FlujoCaja->setValor($ValorAPagar);

                /* Código que configura propiedades de un objeto FlujoCaja con información de un ticket. */
                $FlujoCaja->setTicketId($ItTicketEnc->ticketId);
                $FlujoCaja->setCuentaId('0');
                $FlujoCaja->setMandante($ItTicketEnc->mandante);
                $FlujoCaja->setValorIva($impuesto);
                $FlujoCaja->setTraslado('N');
                $FlujoCaja->setRecargaId('0');


                /* Establece valores predeterminados para formapago1Id y formapago2Id si están vacíos. */
                if ($FlujoCaja->getFormapago1Id() == "") {
                    $FlujoCaja->setFormapago1Id(0);
                }

                if ($FlujoCaja->getFormapago2Id() == "") {
                    $FlujoCaja->setFormapago2Id(0);
                }


                /* Inicializa valores de Forma1 y Forma2 en 0 si están vacíos. */
                if ($FlujoCaja->getValorForma1() == "") {
                    $FlujoCaja->setValorForma1(0);
                }

                if ($FlujoCaja->getValorForma2() == "") {
                    $FlujoCaja->setValorForma2(0);
                }


                /* establece valores predeterminados para CuentaId y PorcenIva si están vacíos. */
                if ($FlujoCaja->getCuentaId() == "") {
                    $FlujoCaja->setCuentaId(0);
                }

                if ($FlujoCaja->getPorcenIva() == "") {
                    $FlujoCaja->setPorcenIva(0);
                }


                /* Inserta datos de flujo de caja y maneja errores de actualización en la base de datos. */
                $FlujoCaja->setDevolucion('');

                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                /* Se crea un usuario, se actualiza su saldo con un premio y se inicia una recarga. */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $oldBalance = $Usuario->getBalance();
                $Usuario->credit($ItTicketEnc->vlrPremio, $Transaction);


                /*$Consecutivo = new Consecutivo("", "REC", "");

                $consecutivo_recarga = $Consecutivo->numero;
                $consecutivo_recarga++;

                $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                $Consecutivo->setNumero($consecutivo_recarga);


                $ConsecutivoMySqlDAO->update($Consecutivo);

                $ConsecutivoMySqlDAO->getTransaction()->commit();*/

                $UsuarioRecarga = new UsuarioRecarga();
                //$UsuarioRecarga->setRecargaId($consecutivo_recarga);

                /* Código que configura un objeto de recarga de usuario con datos específicos. */
                $UsuarioRecarga->setUsuarioId($UsuarioMandante->usuarioMandante);
                $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
                $UsuarioRecarga->setPuntoventaId($UsuarioMandante->usuarioMandante);
                $UsuarioRecarga->setValor($ItTicketEnc->vlrPremio);
                $UsuarioRecarga->setPorcenRegaloRecarga(0);
                $UsuarioRecarga->setDirIp(0);

                /* establece varios atributos a cero en el objeto UsuarioRecarga. */
                $UsuarioRecarga->setPromocionalId(0);
                $UsuarioRecarga->setValorPromocional(0);
                $UsuarioRecarga->setHost(0);
                $UsuarioRecarga->setMandante(0);
                $UsuarioRecarga->setPedido(0);
                $UsuarioRecarga->setPorcenIva(0);

                /* Se establece un registro de recarga de usuario con valores iniciales y se inserta. */
                $UsuarioRecarga->setMediopagoId(0);
                $UsuarioRecarga->setValorIva(0);
                $UsuarioRecarga->setEstado('A');

                $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);
                $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

                /* asigna un ID de recarga y establece un historial de usuario. */
                $consecutivo_recarga = $UsuarioRecarga->recargaId;


                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
                $UsuarioHistorial->setDescripcion('');

                /* establece propiedades de un objeto UsuarioHistorial basado en una recarga. */
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(40);
                $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());


                /* Inserta historial de usuario en MySQL y confirma la transacción. */
                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                // Commit de la transacción
                $Transaction->commit();


                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */

                /* crea un token y envía un mensaje WebSocket para actualizar saldo. */
                $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

                $UsuarioSession = new UsuarioSession();

                /* define reglas de filtrado para una consulta de usuario. */
                $rules = [];

                array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* Convierte un filtro a JSON, luego obtiene y decodifica usuarios personalizados. */
                $json2 = json_encode($filtro);


                $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

                $usuarios = json_decode($usuarios);


                /* envía mensajes a usuarios mediante WebSocket utilizando tokens de sesión. */
                $usuariosFinal = [];

                foreach ($usuarios->data as $key => $value) {

                    $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", $data);
                    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
                    $WebsocketUsuario->sendWSMessage();

                }


                /* Código que retorna un mensaje de éxito tras un pago de ticket. */
                $response["data"] = array(
                    "result" => 1,
                    "resultmsg" => "El ticket fue pagado satisfactoriamente"

                );
            } else {


                /* Verifica si un ticket premiado ya fue pagado y responde adecuadamente. */
                if ($ItTicketEnc->premiado == "S" && $ItTicketEnc->premioPagado == "S") {
                    $response["data"] = array(
                        "result" => 0,
                        "resultmsg" => "El ticket fue pagado previamente"

                    );
                }


                /* verifica condiciones de un ticket y devuelve un mensaje de perdedor. */
                if ($ItTicketEnc->premiado == "N" && $ItTicketEnc->estado == "I") {
                    $response["data"] = array(
                        "result" => 0,
                        "resultmsg" => "El ticket es perdedor"

                    );
                }


                /* Verifica si el estado del ticket es "A" y prepara una respuesta. */
                if ($ItTicketEnc->estado == "A") {
                    $response["data"] = array(
                        "result" => 0,
                        "resultmsg" => "El ticket esta pendiente"

                    );
                }
            }

        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, captura de errores sin acciones definidas. */


        }


    } catch (Exception $e) {
        /* Bloque que captura excepciones en PHP, evitando que el programa falle abruptamente. */


    }

}