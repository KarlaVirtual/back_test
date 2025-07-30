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
use Backend\dto\TransjuegoLog;
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
 * Este script genera una respuesta JSON con información sobre deportes virtuales, competiciones y juegos.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud, incluyendo:
 * @param object $json->params Objeto JSON que contiene los parámetros de la solicitud.
 * @param string $json->params->what Objeto JSON que especifica los campos a filtrar (e.g., sport, competition, game).
 *
 * @return array $response Respuesta en formato JSON que incluye:
 *                         - code: Código de estado de la respuesta (0 para éxito).
 *                         - rid: Identificador único de la respuesta.
 *                         - data: Datos estructurados según los filtros aplicados, incluyendo:
 *                           - sport: Información de deportes virtuales.
 *                           - competition: Información de competiciones.
 *                           - game: Información de juegos.
 */
exit();
/*                        {"code":0,"rid":"15183107607554","data":{"subid":"-8252782767092495715","data":{"sport":{"54":{"id":54,"name":"Carrera Virtual de Caballos","alias":"VirtualHorseRacing","order":176,"game":8},"55":{"id":55,"name":"Carrera de Galgos","alias":"VirtualGreyhoundRacing","order":175,"game":9},"56":{"id":56,"name":"Tenis Virtual","alias":"VirtualTennis","order":174,"game":5},"57":{"id":57,"name":"Fútbol Virtual","alias":"VirtualFootball","order":173,"game":5},"118":{"id":118,"name":"Carrera Virtual de Carros","alias":"VirtualCarRacing","order":177,"game":4},"150":{"id":150,"name":"Virtual Bicycle","alias":"VirtualBicycle","order":178,"game":5},"174":{"id":174,"name":"The Penalty Kicks","alias":"ThePenaltyKicks","order":128,"game":5}}}}}*/

$what = $json->params->what;


if ($what->sport[0] == "id") {

    if ($what->sport[1] == "name") {

        /* Se crea un arreglo de respuesta con código y un identificador específico. */
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;
        $response["data"] = array(
            "subid" => "-8252782767092495715",

            "data" => array(
                "sport" => array(
                    "54" => array(
                        "game" => 8,
                        "alias" => "VirtualHorseRacing",
                        "id" => 54,
                        "name" => "Carrera Virtual de Caballos",
                        "order" => 176,
                    ),
                    "174" => array(
                        "game" => 5,
                        "alias" => "VirtualFootball",
                        "id" => 174,
                        "name" => "The Penalty Kicks",
                        "order" => 128,
                    ),
                ),
            ),
        );
    }

    if ($what->sport[1] == "alias") {

        /* Se crea un arreglo de respuesta con código y un identificador de solicitud. */
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;
        $response["data"] = array(
            "subid" => "-5754574707528464820",

            "data" => array(
                "sport" => array(
                    "174" => array(
                        "id" => 174,
                        "name" => "ThePenaltyKicks",
                        "region" => array(
                            10174 => array(
                                "competition" => array(
                                    "27510" => array(
                                        "game" => array(
                                            "9742278" => array(
                                                "game_number" => 12263,
                                                "id" => 9742278,
                                                "info" => array(
                                                    "field" => 0,
                                                    "virtual" => array(
                                                        array(
                                                            "AnimalName" => "",
                                                            "Number" => 1,
                                                            "PlayerName" => "Spain"
                                                        ),
                                                        array(
                                                            "AnimalName" => "",
                                                            "Number" => 2,
                                                            "PlayerName" => "Armenia"
                                                        )
                                                    )
                                                ),
                                                "is_blocked" => 0,
                                                "is_live" => 1,
                                                "is_neutral_venue" => false,
                                                "is_reversed" => false,
                                                "is_started" => 0,
                                                "is_stat_available" => false,
                                                "live_available" => 0,
                                                "market" =>
                                                    array(
                                                        "142338819" => array(
                                                            "cashout" => 0,
                                                            "col_count" => 2,
                                                            "event" => array(
                                                                "472054085" => array(
                                                                    "id" => 472054085,
                                                                    "name" => "Belgium",
                                                                    "order" => 0,
                                                                    "price" => 1.77,
                                                                    "type" => "{t1}",
                                                                    "type_1" => "Home",
                                                                    "type_id" => 15229
                                                                ),
                                                                "472054086" => array(
                                                                    "id" => 472054086,
                                                                    "name" => "Argentina",
                                                                    "order" => 1,
                                                                    "price" => 1.93,
                                                                    "type" => "{t2}",
                                                                    "type_1" => "Away",
                                                                    "type_id" => 15230
                                                                )
                                                            ),
                                                            "id" => 142338819,
                                                            "market_type" => "MatchResult",
                                                            "name" => "Match Result",
                                                            "name_template" => "Match Result",
                                                            "name_template" => "Match Result",
                                                            "optimal" => false,
                                                            "order" => 100000,
                                                            "point_sequence" => 0,
                                                            "sequence" => 0
                                                        )

                                                    ),
                                                "markets_count" => 1,
                                                "not_in_sportsbook" => 0,
                                                "start_ts" => 1518311820,
                                                "team1_id" => 395250,
                                                "team1_name" => "España",
                                                "team2_id" => 395228,
                                                "team2_name" => "Armenia",
                                                "tv_type" => 15,
                                                "type" => 0,
                                                "video_id" => 10,
                                                "visible_in_prematch" => 1,
                                            )
                                        ),
                                        "id" => 27510,
                                        "name" => "The Penalty Kicks"
                                    ),
                                    "id" => 10174
                                )
                            )
                        ),
                    ),
                ),
            ),
        );
    }

}

/* verifica una condición y crea una respuesta en formato JSON. */
if ($what->competition[0] == "id") {
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "subid" => "-6444586264083712884",

        "data" => array(
            "competition" => array(
                "27510" => array(
                    "id" => 27510,
                    "name" => "The Penalty Kicks",
                    "order" => 1001
                ),
            ),
        ),
    );
}

if ($what->game[0] == "game_number") {

    /* crea un arreglo de respuesta con un código y un identificador. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "subid" => "-713534352033310597",

        "data" => array(
            "game" => array(
                "9742278" => array(
                    "game_number" => 12263,
                    "id" => 9742278,
                    "start_ts" => 1518311820,
                    "team1_name" => "España",
                    "team2_name" => "Armenia"
                ),
                "9742304" => array(
                    "game_number" => 9275,
                    "id" => 9742304,
                    "start_ts" => 1518312120,
                    "team1_name" => "Inglaterra",
                    "team2_name" => "Armenia"
                ),
                "9742328" => array(
                    "game_number" => 2094,
                    "id" => 9742328,
                    "start_ts" => 1518311820,
                    "team1_name" => "España",
                    "team2_name" => "Armenia"
                ),
                "9742350" => array(
                    "game_number" => 12199,
                    "id" => 9742350,
                    "start_ts" => 9742350,
                    "team1_name" => "Bélgica",
                    "team2_name" => "Argentina"
                ),
                "9742377" => array(
                    "game_number" => 8714,
                    "id" => 9742377,
                    "start_ts" => 1518313020,
                    "team1_name" => "Italy",
                    "team2_name" => "Netherlands"
                ),
            ),
        ),
    );
}


