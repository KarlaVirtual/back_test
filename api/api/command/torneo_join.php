<?php

use Backend\dto\TorneoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioTorneo;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;

/**
 *
 * command/torneo_join
 *
 * Procesar torneos y condiciones de usuario
 *
 * Este recurso procesa los torneos disponibles para un usuario, revisa las condiciones definidas
 * en los detalles del torneo y actualiza el estado del torneo del usuario en función de las condiciones cumplidas.
 *
 * @param boolean $isMobile : Indica si la solicitud proviene de un dispositivo móvil.
 * @param int $site_id : ID del sitio.
 * @param int $id : ID del torneo.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de respuesta, donde 0 indica éxito.
 *  - *rid* (string): ID de la solicitud.
 *
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/**
 *
 * Ejecuta la sql enviada como parametro
 *
 * @param string $sql : SQL a ejecutar
 *
 * @return object $response responde un array en caso de exito con la respuesta de la SQL
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

function execQuery($sql)
{

    $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO();
    $return = $TorneoInternoMySqlDAO->querySQL($sql);
    $return = json_decode(json_encode($return), FALSE);

    return $return;

}


/* Extracción de parámetros JSON y asignación a variables para usuario y dispositivo. */
$params = $json->params;
$isMobile = $params->isMobile;
$site_id = $params->site_id;
$id = $params->id;


$usuarioId = $UsuarioMandanteSite->usumandanteId;


/* Se inicia un objeto de torneo interno y se definen variables iniciales. */
$TorneoInterno = new TorneoInterno($id);


$torneoid = 0;
$usutorneo_id = 0;
$valorASumar = 0;

//Obtenemos todos los torneos disponibles

/* Definición SQL requerida para la obtención de los torneos solicitados */
$sqlTorneo = "select a.usutorneo_id,a.torneo_id,a.apostado,a.fecha_crea,torneo_interno.condicional,torneo_interno.tipo from usuario_torneo a INNER JOIN torneo_interno ON (torneo_interno.torneo_id = a.torneo_id ) where  a.estado='A' AND (torneo_interno.tipo = 2 OR torneo_interno.tipo = 3) AND a.torneo_id='" . $TorneoInterno->torneoId . "' AND a.usuario_id='" . $usuarioId . "'";
$torneosDisponibles = execQuery($sqlTorneo);

$tipoProducto = "";

if (oldCount($torneosDisponibles) == 0) {

    if ($torneoid == 0) {

        //Obtenemos todos los detalles del torneo
        $sqlDetalleTorneo = "select * from torneo_detalle a where a.torneo_id='" . $TorneoInterno->torneoId . "' AND (moneda='' OR moneda='" . $UsuarioMandanteSite->moneda . "') ";
        $torneoDetalles = execQuery($sqlDetalleTorneo);


        //Inicializamos variables
        $cumplecondicion = true;
        $cumplecondicionproducto = false;
        $condicionesproducto = 0;
        $torneoid = 0;
        $valorapostado = 0;
        $valorrequerido = 0;
        $valorASumar = 0;

        $sePuedeSimples = 0;
        $sePuedeCombinadas = 0;
        $minselcount = 0;
        $userpais = false;
        $userpaisCont = 0;

        $ganaTorneoId = 0;
        $tipotorneo = "";
        $ganaTorneoId = 0;

        // Se determina el tipo de comparación basado en la condición del torneo

        if ($TorneoInterno->condicional == 'NA' || $TorneoInterno->condicional == '') {
            $tipocomparacion = "OR";

        } else {
            $tipocomparacion = $TorneoInterno->condicional;

        }


        foreach ($torneoDetalles as $torneoDetalle) {

            switch ($torneoDetalle->{"a.tipo"}) {

                // Se obtiene el tipo de producto del detalle del torneo
                case "TIPOPRODUCTO":

                    $tipoProducto = $torneoDetalle->{"a.valor"};
                    break;
                // Calcula la fecha del torneo sumando días a la fecha de creación
                case "EXPDIA":
                    $fechaTorneo = date('Y-m-d H:i:ss', strtotime($TorneoInterno->fechaCrea . ' + ' . $torneoDetalle->{"a.valor"} . ' days'));
                    $fecha_actual = date("Y-m-d H:i:ss", time());

                    if ($fechaTorneo < $fecha_actual) {
                        $cumplecondicion = false;
                    }

                    break;
                // Establece la fecha del torneo a partir de un valor específico
                case "EXPFECHA":
                    $fechaTorneo = date('Y-m-d H:i:ss', strtotime($torneoDetalle->{"a.valor"}));
                    $fecha_actual = strtotime(date("Y-m-d H:i:ss", time()));

                    if ($fechaTorneo < $fecha_actual) {
                        $cumplecondicion = false;
                    }
                    break;


                case "LIVEORPREMATCH":


                    break;

                case "MINSELCOUNT":


                    break;

                case "MINSELPRICE":


                    break;


                case "MINSELPRICETOTAL":

                    break;

                case "MINBETPRICE":


                    break;

                case "WINBONOID":
                    // Se asigna el ID del torneo ganado desde el objeto $torneoDetalle
                    $ganaTorneoId = $torneoDetalle->{"a.valor"};
                    $tipotorneo = "WINBONOID";
                    $valor_torneo = 0;

                    break;
                // Se asigna el tipo de saldo desde el objeto $torneoDetalle
                case "TIPOSALDO":
                    $tiposaldo = $torneoDetalle->{"a.valor"};

                    break;

                case "FROZEWALLET":

                    break;

                case "SUPPRESSWITHDRAWAL":

                    break;

                case "SCHEDULECOUNT":

                    break;

                case "SCHEDULENAME":

                    break;

                case "SCHEDULEPERIOD":

                    break;


                case "SCHEDULEPERIODTYPE":

                    break;

                case "ITAINMENT1":

                    break;

                case "ITAINMENT3":

                    break;
                case "ITAINMENT4":


                    break;
                case "ITAINMENT5":


                    break;
                // Caso para validar el país del usuario.
                case "CONDPAISUSER":

                    if ($json->session->logueado) {

                        $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                        if ($torneoDetalle->{"a.valor"} == $UsuarioMandante->getPaisId()) {
                            $userpais = true;
                        }
                    }
                    $userpaisCont++;
                    break;
                // Caso para ITAINMENT82, maneja la posibilidad de apuestas simples o combinadas.
                case "ITAINMENT82":

                    if ($torneoDetalle->{"a.valor"} == 1) {
                        $sePuedeSimples = 1;

                    }
                    if ($torneoDetalle->{"a.valor"} == 2) {
                        $sePuedeCombinadas = 1;

                    }
                    break;

                default:

                    break;
            }


        }

        if ($userpaisCont > 0) {
            if (!$userpais) {
                $cumplecondicion = false;
            }
        }


        if ($cumplecondicion && ($cumplecondicionproducto || $condicionesproducto == 0)) {

            $torneoid = $TorneoInterno->torneoId;

        }
    }

    /**
     * Verifica si el ID del torneo es diferente de 0 y, de ser así,
     * crea una nueva instancia de UsuarioTorneo con los valores predeterminados,
     * inserta el registro en la base de datos y confirma la transacción.
     */
    if ($torneoid != 0) {


        $UsuarioTorneo = new UsuarioTorneo();
        $UsuarioTorneo->usuarioId = $UsuarioMandanteSite->usumandanteId;
        $UsuarioTorneo->torneoId = $TorneoInterno->torneoId;
        $UsuarioTorneo->valor = 0;
        $UsuarioTorneo->posicion = 0;
        $UsuarioTorneo->valorBase = 0;
        $UsuarioTorneo->usucreaId = 0;
        $UsuarioTorneo->usumodifId = 0;
        $UsuarioTorneo->estado = "A";
        $UsuarioTorneo->errorId = 0;
        $UsuarioTorneo->idExterno = 0;
        $UsuarioTorneo->mandante = 0;
        $UsuarioTorneo->version = 0;
        $UsuarioTorneo->apostado = 0;
        $UsuarioTorneo->codigo = 0;
        $UsuarioTorneo->externoId = 0;
        $UsuarioTorneo->valor = 0;
        $UsuarioTorneo->valorBase = 0;

        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO();
        $idUsuTorneo = $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

        $UsuarioTorneoMySqlDAO->getTransaction()->commit();

    }
}
/**
 * Establece el código de respuesta y el identificador de relación.
 *
 * @var array $response Arreglo que contiene el código y el rid.
 * @var int $response ["code"] Código de respuesta inicializado en 0.
 * @var mixed $response ["rid"] Identificador de relación obtenido de $json.
 */
$response["code"] = 0;
$response["rid"] = $json->rid;