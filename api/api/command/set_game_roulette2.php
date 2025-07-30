<?php

use Backend\dto\UsuarioMandante;
use Backend\dto\RuletaInterno;

/**
 * command/set_game_roulette2
 *
 * Procesa la asignación de un premio en la ruleta interna para un usuario específico.
 *
 * @param int $Id : Identificador de la ruleta asociada al usuario.
 *
 * @return objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de estado de la operación.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *data* (array): Contiene el resultado del proceso de asignación de premio.
 *    - *winner* (mixed): Información del premio asignado al usuario.
 *
 *
 * @throws Exception No
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');


//Agrega premios en ruleta.


/* agrega un premio a la ruleta y devuelve información del ganador. */
if (true) {


    $usuruletaId = $json->params->Id;
    $accion = (!empty($json->params->action) && $json->params->action == "Giro Extra") ? "Giro Extra" : null;
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $RuletaInterna = new RuletaInterno();

    $winner=$RuletaInterna->agregarPremioRuleta($usuruletaId,$UsuarioMandante, $accion);

    $response = array();

    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "winner" => $winner["data"]["winner"]
    );
}


