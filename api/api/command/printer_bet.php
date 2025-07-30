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
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TranssportsbookDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
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
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
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
 * Obtiene el imprimible de una apuesta
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada.
 *  - session: Objeto que contiene la sesión del usuario.
 *      - usuario: Objeto que contiene la información del usuario.
 *  - params: Objeto que contiene los parámetros de la solicitud.
 *      - where: Objeto que contiene los filtros de búsqueda.
 *          - from_date: Fecha de inicio del filtro.
 *          - to_date: Fecha de fin del filtro.
 *          - bet_id: ID de la apuesta.
 *      - count: Número de filas a contar.
 *      - start: Inicio de las filas.
 * @return array Respuesta en formato JSON.
 *  - code: Código de respuesta.
 *  - rid: ID de respuesta.
 *  - data: Datos de la respuesta.
 *      - htmlPOS: HTML generado para el PDF.
 */


/* Configura la zona horaria, crea un usuario y obtiene una fecha desde JSON. */
date_default_timezone_set('America/Bogota');


$UsuarioMandante = new UsuarioMandante($json->session->usuario);


$from_date = $json->params->where->from_date;

/* extrae fechas y bet_id de un JSON y las formatea. */
$to_date = $json->params->where->to_date;
$bet_id = $json->params->where->bet_id;

$ItTicketEnc = new ItTicketEnc();

if ($to_date != "") {
    $ToDateLocal = date("Y-m-d H:i:s", $to_date);

}


/* verifica una fecha y asigna una variable si es válida. */
if ($to_date != "") {
    $FromDateLocal = date("Y-m-d H:i:s", $from_date);

}

$MaxRows = $params->MaxRows;

/* asigna valores y gestiona la paginación de elementos. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Se inicia una configuración y se crea una tabla HTML para PDF. */
$rules = [];

$ConfigurationEnvironment = new ConfigurationEnvironment();

$html_pdf = "<table style='width:500px;height:280px;font-size: 18px;'>";

if ($ConfigurationEnvironment->isDevelopment()) {

    /* Agrega reglas de fecha a un array si las variables no están vacías. */
    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "transaccion_sportsbook.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }
    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "transaccion_sportsbook.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }


    /* añade reglas de filtro basadas en condiciones específicas para transacciones. */
    if ($bet_id != "") {
        array_push($rules, array("field" => "transaccion_sportsbook.ticket_id", "data" => $bet_id, "op" => "eq"));
    }

    array_push($rules, array("field" => "transaccion_sportsbook.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
    array_push($rules, array("field" => "transaccion_sportsbook.eliminado", "data" => "N", "op" => "eq"));


    /* Se crea un filtro y se obtienen transacciones personalizadas con ciertas condiciones. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $TransaccionSportsbook = new TransaccionSportsbook();

    $tickets = $TransaccionSportsbook->getTransaccionesCustom(" usuario.login,usuario.moneda,transaccion_sportsbook.clave,transaccion_sportsbook.bet_status,transaccion_sportsbook.ticket_id,transaccion_sportsbook.usuario_id,transaccion_sportsbook.vlr_apuesta,transaccion_sportsbook.vlr_premio,transaccion_sportsbook.estado,transaccion_sportsbook.fecha_crea,transaccion_sportsbook.dir_ip,transaccion_sportsbook.transsport_id  ", "transaccion_sportsbook.transsport_id", "desc", $SkeepRows, $MaxRows, $json2, true, "transaccion_sportsbook.transsport_id");

    /* Decodifica un JSON de tickets y inicializa variables para total y apuestas. */
    $tickets = json_decode($tickets);

    $total = 0;
    $bets = [];
    $response = array();

    foreach ($tickets->data as $key => $value) {


        /* asigna un valor a $outcome según el estado de la transacción. */
        $outcome = 0;

        switch ($value->{"transaccion_sportsbook.bet_status"}) {
            case "S":
                $outcome = 3;
                break;
            case "N":
                $outcome = 1;
                break;
            case "T":
                $outcome = 5;
                break;
        }


        /* Se crea un arreglo que almacena el ID de una transacción de apuestas. */
        $arraybet = [];


        $arraybet = array();
        $arraybet["id"] = ($value->{"transaccion_sportsbook.ticket_id"});
        $arraybet["type"] = 1;

        /* Se crea un array con datos de una apuesta, incluyendo monto, probabilidades y moneda. */
        $arraybet["odd_type"] = null;
        $arraybet["amount"] = ($value->{"transaccion_sportsbook.vlr_apuesta"});
        $arraybet["k"] = floatval($value->{"transaccion_sportsbook.vlr_premio"}) / floatval(($value->{"transaccion_sportsbook.vlr_apuesta"}));
        $arraybet["currency"] = $value->{"usuario.moneda"};
        $arraybet["outcome"] = $outcome;
        $arraybet["number"] = null;

        /* Se define un array con información sobre una apuesta y sus propiedades. */
        $arraybet["client_id"] = 1;
        $arraybet["betshop_id"] = null;
        $arraybet["is_live"] = false;
        $arraybet["payout"] = ($value->{"transaccion_sportsbook.vlr_premio"});
        $arraybet["possible_win"] = ($value->{"transaccion_sportsbook.vlr_premio"});
        $arraybet["accept_type_id"] = 0;

        /* asigna valores de un objeto a un array asociativo en PHP. */
        $arraybet["client_login"] = $value->{"usuario.login"};
        $arraybet["barcode"] = '';
        $arraybet["calc_date"] = strtotime($value->{"transaccion_sportsbook.fecha_crea"});
        $arraybet["date_time"] = strtotime($value->{"transaccion_sportsbook.fecha_crea"});

        $arraybet["events"] = array(/* '-5': 'On Hold', '-4': 'Declined', '0': 'UNSETTLED', '1': 'Lost', '2': 'Returned', '3': 'Won', '5': 'Cashed out' */

        );

        $html_pdf .= "
        <tr><td><table>
        

    <tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr>
    <tr><td><img src=\"https://images.virtualsoft.tech/site/doradobet/logo-doradobet.png\" style=\"width: 100%;\"></td></tr>
    
    <tr>
    <td>
    <table style=\"width: 100%;border: 1px solid black;padding: 10px;\">
    <tr>
    <td>
        Cliente
    </td>
    <td style=\"text-align: right;\">
        " . $UsuarioMandante->nombres . "
    </td>
    </tr>
    
    <tr>
    <td>
        No
    </td>
    <td style=\"text-align: right;\">
        " . $arraybet["id"] . "
    </td>
    </tr>
    <tr>
    <td>
        IB
    </td>
    <td style=\"text-align: right;\">
        " . $value->{"transaccion_sportsbook.clave"} . "
    </td>
    </tr>
    <tr>
    <td>
        Fecha
    </td>
    <td style=\"text-align: right;\">
        " . date('Y-m-d H:i:s', $arraybet["date_time"]) . "
    </td>
    </tr>
</table></td></tr>
    ";

        $SkeepRows = 0;
        $OrderedItem = 1;
        $MaxRows = 10;
        $rules = [];

        array_push($rules, array("field" => "transsportsbook_detalle.transsport_id", "data" => $value->{"transaccion_sportsbook.transsport_id"}, "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json3 = json_encode($filtro);

        $TranssportsbookDetalle = new TranssportsbookDetalle();
        $ticketdetalles = $TranssportsbookDetalle->getTransaccionesCustom(" transsportsbook_detalle.* ", "transsportsbook_detalle.transsportdet_id", "asc", $SkeepRows, $MaxRows, $json3, true);


        $ticketdetalles = json_decode($ticketdetalles);


        $html_pdf .= " <tr><td><table style=\"width: 100%;border: 1px solid black;padding: 10px;\">";
        foreach ($ticketdetalles->data as $key2 => $value2) {


            $arraybetdetail = array();


            $arraybetdetail["game_start_date"] = $value2->{"transsportsbook_detalle.fecha_evento"};
            $arraybetdetail["team1"] = $value2->{"transsportsbook_detalle.apuesta"};
            $arraybetdetail["team2"] = $value2->{"transsportsbook_detalle.apuesta"};
            $arraybetdetail["market_name"] = $value2->{"transsportsbook_detalle.agrupador"};
            $arraybetdetail["event_name"] = $value2->{"transsportsbook_detalle.apuesta"};
            $arraybetdetail["coeficient"] = $value2->{"transsportsbook_detalle.logro"};
            $arraybetdetail["option"] = $value2->{"transsportsbook_detalle.opcion"};


            array_push($arraybet["events"], $arraybetdetail);

            $html_pdf .= "
        <tr><td><table><tr>
        <td>" . $arraybetdetail["event_name"] . "</td>
        </tr><tr>
        <td>" . $arraybetdetail["game_start_date"] . "</td> </tr><tr>
        <td>" . $arraybetdetail["event_name"] . "</td> </tr><tr>
        <td>
        <table>
        <tr><td><table><tr><td>" . $arraybetdetail["option"] . "</td> <td>Cuotas: " . $arraybetdetail["coeficient"] . "</td></tr></table></td>
        
</tr>
</table>
</td></tr>
</table></td></tr>";


        }
        array_push($bets, $arraybet);
        $html_pdf .= " </table></td></tr>";


    }

    if (false) {


//array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));
        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $tickets = $ItTicketEnc->getTicketsCustom(" usuario.login,usuario.moneda,CONCAT(it_ticket_enc.fecha_crea, ' ',it_ticket_enc.hora_crea) fecha,it_ticket_enc.it_ticket_id,it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.cant_lineas,it_ticket_enc.bet_status ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        $tickets = json_decode($tickets);

        $total = 0;
        $bets = [];

        foreach ($tickets->data as $key => $value) {

            $outcome = 0;

            switch ($value->{"it_ticket_enc.bet_status"}) {
                case "S":
                    $outcome = 3;
                    break;
                case "N":
                    $outcome = 1;
                    break;
            }


            $arraybet = array();
            $arraybet["id"] = ($value->{"it_ticket_enc.it_ticket_id"});
            $arraybet["type"] = 1;
            $arraybet["odd_type"] = null;
            $arraybet["amount"] = ($value->{"it_ticket_enc.vlr_apuesta"});
            $arraybet["k"] = floatval($value->{"it_ticket_enc.vlr_premio"}) / floatval(($value->{"it_ticket_enc.vlr_apuesta"}));
            $arraybet["currency"] = $value->{"usuario.usuario.moneda"};
            $arraybet["outcome"] = $outcome;
            $arraybet["number"] = null;
            $arraybet["client_id"] = 1;
            $arraybet["betshop_id"] = null;
            $arraybet["is_live"] = false;
            $arraybet["payout"] = ($value->{"it_ticket_enc.vlr_premio"});
            $arraybet["possible_win"] = ($value->{"it_ticket_enc.vlr_premio"});
            $arraybet["accept_type_id"] = 0;
            $arraybet["client_login"] = $value->{"usuario.usuario.login"};
            $arraybet["barcode"] = '';
            $arraybet["calc_date"] = strtotime($value->{".fecha"});
            $arraybet["date_time"] = strtotime($value->{".fecha"});

            $arraybet["events"] = array(/* '-5': 'On Hold', '-4': 'Declined', '0': 'UNSETTLED', '1': 'Lost', '2': 'Returned', '3': 'Won', '5': 'Cashed out' */

            );

            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 10;
            $rules = [];

            array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => $value->{"it_ticket_enc.ticket_id"}, "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json3 = json_encode($filtro);

            $ticketdetalles = $ItTicketEnc->getTicketDetallesCustom(" CONCAT(it_ticket_det.fecha_evento, ' ',it_ticket_det.hora_evento) fecha,it_ticket_det.ticket_id,it_ticket_det.apuesta, it_ticket_det.agrupador,it_ticket_det.logro,it_ticket_det.opcion,it_ticket_det.apuesta_id,it_ticket_det.agrupador_id ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json3, true);


            $ticketdetalles = json_decode($ticketdetalles);


            foreach ($ticketdetalles->data as $key2 => $value2) {


                $arraybetdetail = array();


                $arraybetdetail["game_start_date"] = $value2->{".fecha"};
                $arraybetdetail["team1"] = $value2->{"it_ticket_det.apuesta"};
                $arraybetdetail["team2"] = $value2->{"it_ticket_det.apuesta"};
                $arraybetdetail["market_name"] = $value2->{"it_ticket_det.agrupador"};
                $arraybetdetail["event_name"] = $value2->{"it_ticket_det.apuesta"};
                $arraybetdetail["coeficient"] = $value2->{"it_ticket_det.logro"};


                array_push($arraybet["events"], $arraybetdetail);


            }
            array_push($bets, $arraybet);


        }
    }
} else {
    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal ", "op" => "ge"));
    }
    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));
    }


    if ($bet_id != "") {
        array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => $bet_id, "op" => "eq"));
    }

    array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $ItTicketEnc = new ItTicketEnc();

    $clave_encrypt = "b#4rBZ4n2015";


    $tickets = $ItTicketEnc->getTicketsCustom(" usuario.login,usuario.moneda,aes_decrypt(it_ticket_enc.clave,'" . $clave_encrypt . "') clave,it_ticket_enc.bet_status,it_ticket_enc.ticket_id,it_ticket_enc.usuario_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.estado,it_ticket_enc.fecha_crea,it_ticket_enc.hora_crea,it_ticket_enc.dir_ip,it_ticket_enc.it_ticket_id  ", "it_ticket_enc.it_ticket_id", "desc", $SkeepRows, $MaxRows, $json2, true);
    $tickets = json_decode($tickets);

    $total = 0;
    $bets = [];
    $response = array();

    foreach ($tickets->data as $key => $value) {

        $outcome = 0;

        switch ($value->{"it_ticket_enc.bet_status"}) {
            case "S":
                $outcome = 3;
                break;
            case "N":
                $outcome = 1;
                break;
            case "T":
                $outcome = 5;
                break;
        }

        $arraybet = [];


        $arraybet = array();
        $arraybet["id"] = ($value->{"it_ticket_enc.ticket_id"});
        $arraybet["type"] = 1;
        $arraybet["odd_type"] = null;
        $arraybet["amount"] = ($value->{"it_ticket_enc.vlr_apuesta"});
        $arraybet["k"] = floatval($value->{"it_ticket_enc.vlr_premio"}) / floatval(($value->{"it_ticket_enc.vlr_apuesta"}));
        $arraybet["currency"] = $value->{"usuario.moneda"};
        $arraybet["outcome"] = $outcome;
        $arraybet["number"] = null;
        $arraybet["client_id"] = 1;
        $arraybet["betshop_id"] = null;
        $arraybet["is_live"] = false;
        $arraybet["payout"] = ($value->{"it_ticket_enc.vlr_premio"});
        $arraybet["possible_win"] = ($value->{"it_ticket_enc.vlr_premio"});
        $arraybet["accept_type_id"] = 0;
        $arraybet["client_login"] = $value->{"usuario.login"};
        $arraybet["barcode"] = '';
        $arraybet["calc_date"] = strtotime($value->{"it_ticket_enc.fecha_crea"} . ' ' . $value->{"it_ticket_enc.hora_crea"});
        $arraybet["date_time"] = strtotime($value->{"it_ticket_enc.fecha_crea"} . ' ' . $value->{"it_ticket_enc.hora_crea"});

        $arraybet["events"] = array(/* '-5': 'On Hold', '-4': 'Declined', '0': 'UNSETTLED', '1': 'Lost', '2': 'Returned', '3': 'Won', '5': 'Cashed out' */

        );

        //    <tr><td><img src=\"https://images.virtualsoft.tech/site/doradobet/logo-doradobet.png\" style=\"width: 100%;\"></td></tr>
        $html_pdf .= "
        <tr><td><table>
        

    <tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr>
    
    <tr><td>
    <table style=\"width: 100%;border: 1px solid black;padding: 10px;\">
    <tr>
    <td style=\"font-weight: bold;font-size: 18px;\">
        Cliente
    </td>
    <td style=\"text-align: right;font-size: 18px;\">
        " . $UsuarioMandante->nombres . "
    </td>
    </tr>
    <tr>
    <td style=\"font-weight: bold;font-size: 18px;\">
        No
    </td>
    <td style=\"text-align: right;font-size: 18px;\">
        " . $arraybet["id"] . "
    </td>
    </tr>
    <tr>
    <td style=\"font-weight: bold;font-size: 18px;\">
        IB
    </td>
    <td style=\"text-align: right;font-size: 18px;\">
        " . $value->{".clave"} . "
    </td>
    </tr>
    <tr>
    <td style=\"font-weight: bold;font-size: 18px;\">
        Fecha
    </td>
    <td style=\"text-align: right;font-size: 18px;\">
        " . date('Y-m-d H:i:s', $arraybet["date_time"]) . "
    </td>
    </tr>
</table></td></tr>
    ";


        /* Define variables y aplica reglas para filtrar datos de tickets en una base de datos. */
        $SkeepRows = 0;
        $OrderedItem = 1;
        $MaxRows = 10;
        $rules = [];

        array_push($rules, array("field" => "it_ticket_det.ticket_id", "data" => $value->{"it_ticket_enc.ticket_id"}, "op" => "eq"));


        /* filtra y obtiene detalles de tickets, convirtiendo los datos a JSON. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json3 = json_encode($filtro);

        $ItTicketDet = new \Backend\dto\ItTicketDet();
        $ticketdetalles = $ItTicketEnc->getTicketDetallesCustom(" it_ticket_det.* ", "it_ticket_det.it_ticketdet_id", "asc", $SkeepRows, $MaxRows, $json3, true);


        $ticketdetalles = json_decode($ticketdetalles);


        /* Genera una tabla HTML con borde y padding, dentro de una fila de otra tabla. */
        $html_pdf .= " <tr><td><table style=\"width: 100%;border: 1px solid black;padding: 10px;\">";
        foreach ($ticketdetalles->data as $key2 => $value2) {


            /* Se crea un arreglo con detalles de una apuesta en un evento deportivo. */
            $arraybetdetail = array();


            $arraybetdetail["game_start_date"] = $value2->{"it_ticket_det.fecha_evento"};
            $arraybetdetail["team1"] = $value2->{"it_ticket_det.apuesta"};
            $arraybetdetail["team2"] = $value2->{"it_ticket_det.apuesta"};

            /* asigna valores a un arreglo y agrega detalles de apuestas a otro arreglo. */
            $arraybetdetail["market_name"] = $value2->{"it_ticket_det.agrupador"};
            $arraybetdetail["event_name"] = $value2->{"it_ticket_det.apuesta"};
            $arraybetdetail["coeficient"] = $value2->{"it_ticket_det.logro"};


            array_push($arraybet["events"], $arraybetdetail);


            /* Genera HTML para mostrar detalles de eventos y opciones de apuestas en formato PDF. */
            $html_pdf .= "
        <tr><td><table><tr>
        <td>" . $arraybetdetail["event_name"] . "</td>
        </tr><tr>
        <td>" . $arraybetdetail["game_start_date"] . "</td> </tr><tr>
        <td>" . $arraybetdetail["event_name"] . "</td> </tr><tr>
        <td>
        <table>
        <tr><td><table><tr><td>" . $arraybetdetail["option"] . "</td> <td>Cuotas: " . $arraybetdetail["coeficient"] . "</td></tr></table></td>
        
</tr>
</table>
</td></tr>
</table></td></tr>";


        }

        /* agrega apuestas a un array y genera una tabla en HTML. */
        array_push($bets, $arraybet);

        $html_pdf .= " </table></td></tr>";


        $html_pdf .= "<tr><td>
    <table style=\"width: 100%;border: 1px solid black;\">
    <tr>
    <td style=\"font-weight: bold;\">
        APUESTA
    </td>
    <td style=\"text-align: right;\">
        " . $arraybet["currency"] . " " . $arraybet["amount"] . "
    </td>
    </tr>
</table></td></tr>
    ";


        $html_pdf .= "<tr><td>
    <table style=\"width: 100%;border: 1px solid black;\">
    <tr>
    <td style=\"font-weight: bold;\">
        GANANCIAS TOTALES
    </td>
    <td style=\"text-align: right;\">
        " . $arraybet["currency"] . " " . $arraybet["possible_win"] . "
    </td>
    </tr>
</table></td></tr>
    ";


        /* $html_pdf.= "<tr><td>
     <table style=\"width: 100%;border: 1px solid black;\">
     <tr>
     <td style=\"font-weight: bold;\">
         GANANCIAS TOTALES
     </td></tr><tr>
     <td style=\"text-align: right;\">
     </td>
     </tr>
 </table></td></tr>
     ";*/
    }

}

/* finaliza una tabla HTML y estructura una respuesta JSON con datos. */
$html_pdf .= "</table>";

$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array("htmlPOS" => $html_pdf);
