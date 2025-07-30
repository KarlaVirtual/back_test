<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoInterno;
use Backend\dto\ItTicketEnc;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioBono;
use Backend\dto\Usuario;

/**
 * bonusapi/cases/CheckRolloverBonus
 *
 * Verificación de Bono y Validación de Transacciones
 *
 * Este recurso verifica la validez de un bono asignado a un usuario y realiza validaciones sobre transacciones y tickets
 * relacionadas con la actividad de apuestas. Dependiendo del estado del bono y del tipo de transacción, se ejecutan distintas
 * verificaciones y procesos para determinar su validez y registrar los datos correspondientes.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *BonusId* (int): Identificador del bono asociado al usuario.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada ("success" o "danger").
 *  - *AlertMessage* (string): Mensaje de alerta generado por el proceso.
 *  - *ModelErrors* (array): Retorna un array vacío si no hay errores en el modelo.
 *
 * En caso de éxito, el objeto $response retornará los datos procesados relacionados con el bono y las transacciones evaluadas.
 *
 * Ejemplo de respuesta en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "danger",
 *  - *AlertMessage* => "Error ([Código de error])",
 *  - *ModelErrors* => array(),
 *
 * @throws Exception Si ocurre un error en la validación del bono o en la recuperación de las transacciones del usuario.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* desactiva errores y establece un bonus relacionado con un usuario. */
ini_set("display_errors", "OFF");
$BonusId = $params->BonusId;

$UsuarioBono = new UsuarioBono($BonusId);
$BonoInterno = new BonoInterno($UsuarioBono->bonoId);


$rules = [];

/* Se crean reglas de filtrado para una consulta, combinadas con operación lógica AND. */
array_push($rules, array("field" => "bono_detalle.bono_id", "data" => $UsuarioBono->bonoId, "op" => "eq"));

array_push($rules, array("field" => "bono_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");


/* Se crea un objeto y se obtienen detalles de bono en formato JSON. */
$BonoDetalle = new \Backend\dto\BonoDetalle();

$json = json_encode($filtro);

$bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "desc", 0, 1000, $json, TRUE);

$bonodetalles = json_decode($bonodetalles);


/* Crea un arreglo vacío y asigna un elemento de $bonodetalles a $bonoDetalle. */
$final = [];

$bonoDetalle = $bonodetalles->data[0];

if ($bonoDetalle->{"bono_detalle.valor"} == '1') {

    if ($UsuarioBono->estado == "A") {


        /* Se crea un usuario y se establece una regla de validación para una transacción. */
        $Usuario = new Usuario($UsuarioBono->usuarioId);

        $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

        $rules = [];
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $UsuarioBono->fechaCrea, "op" => "ge"));
        array_push($rules, array("field" => "transjuego_log.tipo", "data" => "DEBIT", "op" => "eq"));

        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => "$UsuarioMandante->usumandanteId", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");


        /* convierte un arreglo a JSON y crea una instancia de TransaccionApi. */
        $json = json_encode($filtro);
        //setlocale(LC_ALL, 'czech');


        $TransjuegoLog = new \Backend\dto\TransjuegoLog();
        $casino = $TransjuegoLog->getTransjuegoLogsCustom2(" transjuego_log.* ", "transjuego_log.transjuegolog_id", "desc", 0, 10000000, $json, true, "");
        $casino = json_decode($casino);


        foreach ($casino->data as $key => $value) {

            /* Se crea un array con un ID de ticket y detalles de apuestas. */
            $array = [];

            $array["Id"] = '';
            $detalles2 = array(
                "JuegosCasino" => array(array(
                    "Id" => 2
                )

                ),
                "ValorApuesta" => 2000
            );

            /* Se intenta verificar un bono para un usuario en un sistema de casinos. */
            try {
                $BonoInterno = new BonoInterno();
                $respuesta = $BonoInterno->verificarBonoRollower($UsuarioMandante->usuarioMandante, $detalles2, 'CASINO', '',$value->{"transjuego_log.transjuegolog_id"});


            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, captura y comentario de impresión del error. */

                //  print_r($e);
            }
        }


        /* define una respuesta sin errores, con mensaje de éxito y sin errores de modelo. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    } else {
        /* establece una respuesta sin errores, indicando éxito y sin mensajes. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    }
} else {

    if ($UsuarioBono->estado == "A") {

        /* Código que establece un estado e incluye una actualización comentada de base de datos. */
        $UsuarioBono->estado = "I";
        /*
                    $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
                    $UsuarioBonoMySqlDAO->update($UsuarioBono);
                    $UsuarioBonoMySqlDAO->getTransaction()->commit();

                    */


        $MaxRows = 1000;

        /* Se configura una lista de reglas para filtrar información de tickets. */
        $OrderedItem = 1;
        $SkeepRows = 0;

        $rules = [];
        //array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));
        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioBono->usuarioId, "op" => "eq"));

        /* Crea un filtro de reglas en formato JSON para consultas de base de datos. */
        array_push($rules, array("field" => "it_ticket_enc_info1.valor", "data" => $UsuarioBono->usubonoId, "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc_info1.tipo", "data" => "ROLLOWER", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);


        /* Se crea un objeto y se obtienen tickets personalizados en formato JSON. */
        $ItTicketEncInfo1 = new ItTicketEncInfo1();

        $tickets = $ItTicketEncInfo1->getTicketsCustom(" it_ticket_enc_info1.*,it_ticket_enc.vlr_apuesta,it_ticket_enc.fecha_cierre,it_ticket_enc.fecha_crea ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json2, true);

        $tickets = json_decode($tickets);

        $final = [];

        /* recorre tickets, extrae datos y concatena identificadores en una cadena. */
        $strApuestas = '0';

        foreach ($tickets->data as $key => $value) {

            $array = [];

            $array["Id"] = ($value->{"it_ticket_enc_info1.it_ticket2_id"});
            $array["TicketId"] = ($value->{"it_ticket_enc_info1.ticket_id"});
            $array["Valor"] = ($value->{"it_ticket_enc_info1.valor"});
            $array["Amount"] = ($value->{"it_ticket_enc.vlr_apuesta"});
            $array["DateCreate"] = ($value->{"it_ticket_enc.fecha_crea"});
            $array["DateClose"] = ($value->{"it_ticket_enc.fecha_cierre"});

            array_push($final, $array["TicketId"]);
            $strApuestas = $strApuestas . "," . $array["TicketId"];

        }


        /* Define reglas para filtrar tickets en función de varios criterios. */
        $rules = [];

        array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => "$strApuestas", "op" => "ni"));
        array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));

        /* Se añaden reglas a un array para filtrar datos de tickets. */
        array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "'S','N'", "op" => "in"));

        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioBono->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => $UsuarioBono->fechaCrea, "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* Codifica un filtro en JSON, obtiene y decodifica tickets personalizados, y muestra resultados. */
        $json = json_encode($filtro);

        $ItTicketEnc = new ItTicketEnc();
        $tickets = $ItTicketEnc->getTicketsCustom(" it_ticket_enc.ticket_id ", "it_ticket_enc.it_ticket_id", "desc", $SkeepRows, $MaxRows, $json, true);
        $tickets = json_decode($tickets);

        if ($_REQUEST['test2'] == '1') {
            print_r($rules);
            print_r($tickets);
        }

        /* verifica bonos para tickets, imprimiendo resultados si se activa el test. */
        $final = [];

        foreach ($tickets->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"it_ticket_enc.ticket_id"};


            $BonoInterno = new BonoInterno();
            $respuesta = $BonoInterno->verificarBonoRollower($UsuarioBono->usuarioId, '', "SPORT", $array["Id"]);


            if ($_REQUEST['test2'] == '1') {
                print_r('ROLLOWER ');
                print_r($respuesta);
            }
        }


        /* Verifica si no hay tickets antes de procesar un bono interno para un usuario. */
        if (oldCount($tickets->data) == 0) {

            $BonoInterno = new BonoInterno();
            $BonoInterno->verificarBonoRollower($UsuarioBono->usuarioId, '', "SPORT", '');

        }


        /* inicializa una respuesta sin errores y con mensaje de éxito vacío. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    } else {
        /* maneja un caso sin errores, configurando una respuesta exitosa. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    }
}