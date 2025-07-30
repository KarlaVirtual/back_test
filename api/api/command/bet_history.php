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
 * Solicita el historial de apuestas deportivas
 * @param string $json->params->where->from_date
 * @param string $json->params->where->to_date
 * @param int $json->params->where->bet_id
 *
 * @return array
 *  - code: int Código de respuesta
 *  - rid: string Identificador de la petición
 *  - data: array
 *  - data.bets: array
 *  - data.total_count: int Cantidad total de registros
 *  - total_count: int Cantidad total de registros
 * */

/*Establece la zona horaria predeterminada a 'America/Bogota'.*/
date_default_timezone_set('America/Bogota');

exit();
/*     $response = array("code" => 0, "rid" => "150727456897316", "data" => array("bets" => [
array("id" => 88586818, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 88586819, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885816818, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885862818, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885868318, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885868128, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885868148, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858681238, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858681823, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858612281238, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858681822, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858681128, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885868232118, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885868323426518, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 88586832118, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885868221318, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858682218, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 88586282218, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 88586282128, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858681213218, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858612132818, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 885868154648, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)
, array("id" => 8858681468, "type" => 1, "odd_type" => null, "amount" => 5.0, "k" => 3.0, "currency" => "USD", "outcome" => 1, "number" => null, "client_id" => 26678955, "betshop_id" => null, "is_live" => true, "payout" => 0.0, "possible_win" => 15.0, "accept_type_id" => 0, "system_min_count" => null, "client_login" => "danielftg@hotmail.com", "barcode" => 885868184, "calc_date" => 1504650440, "date_time" => 1504643924, "events" => [array("bet_id" => 88586818, "selection_id" => 296777937, "coeficient" => 3.0, "outcome" => 1, "outcome_name" => "Lost", "game_info" => "W1", "event_name" => "W1", "game_start_date" => 1504643400, "team1" => "Colombia", "team2" => "Brasil", "competition_name" => "Clasificación Mundial", "game_id" => 7650883, "is_live" => true, "sport_name" => "Fútbol", "sport_index" => "Football", "region_name" => "Mundo", "market_name" => "Resultado del Partido", "match_display_id" => 7650883, "game_name" => "Colombia - Brasil", "basis" => 0.0, "match_info" => "0 => 0, (0=>0) 7`", "home_score" => null, "away_score" => null, "competition_url" => null, "cash_out_selection_id" => null, "cash_out_price" => null, "selection_price" => 0.0)], "client_bonus_id" => null, "bonus_bet_amount" => 0.0, "bonus" => 0.0, "is_super_bet" => false, "is_bonus_money" => false, "source" => 42, "draw_number" => null)

]));*/


/*El código extrae parámetros de un objeto JSON, crea un objeto UsuarioMandante, y convierte una fecha de finalización a un formato específico si está presente.*/
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

/**
 * Se almacenan las fechas y el id de apuesta desde los parámetros del JSON.
 */
$from_date = $json->params->where->from_date;
$to_date = $json->params->where->to_date;
$bet_id = $json->params->where->bet_id;

/**
 * Se crea una instancia de la clase ItTicketEnc.
 */
$ItTicketEnc = new ItTicketEnc();

/**
 * Si la fecha de fin no está vacía, se transforma a formato "Y-m-d H:i:s".
 */
if($to_date != ""){
    $ToDateLocal = date("Y-m-d H:i:s", $to_date);

}

// Verifica si la fecha de inicio no está vacía y la convierte al formato 'Y-m-d H:i:s'.
if($to_date != ""){
    $FromDateLocal = date("Y-m-d H:i:s", $from_date);

}

// Obtiene el número máximo de filas, el elemento ordenado y las filas a omitir de los parámetros.
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

// Asigna los valores de 'count' y 'start' desde el objeto JSON a $MaxRows y $SkeepRows.
$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;

// Si $SkeepRows está vacío, se inicializa a 0.
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

// Si $OrderedItem está vacío, se inicializa a 1.
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

// Si $MaxRows está vacío, se inicializa a 10.
if ($MaxRows == "") {
    $MaxRows = 10;
}

// Inicializa un array vacío para las reglas.
$rules = [];

// Crea una nueva instancia de la clase ConfigurationEnvironment.
$ConfigurationEnvironment = new ConfigurationEnvironment();

if($ConfigurationEnvironment->isDevelopment()) {
    // Se verifica si la fecha de inicio no está vacía
    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "transaccion_sportsbook.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    }
    // Se verifica si la fecha de fin no está vacía
    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "transaccion_sportsbook.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }

    // Se verifica si el ID de la apuesta no está vacío
    if ($bet_id != "") {
        array_push($rules, array("field" => "transaccion_sportsbook.ticket_id", "data" => $bet_id, "op" => "eq"));
    }

    // Se agrega al filtro el ID del usuario mandante
    array_push($rules, array("field" => "transaccion_sportsbook.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
    // Se agrega al filtro que la transacción no esté eliminada
    array_push($rules, array("field" => "transaccion_sportsbook.eliminado", "data" => "N", "op" => "eq"));

    // Se crea el filtro que contiene las reglas y la operación lógica
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    // Se codifica el filtro en formato JSON
    $json2 = json_encode($filtro);

    // Se crea una nueva instancia de la clase TransaccionSportsbook
    $TransaccionSportsbook = new TransaccionSportsbook();

    // Se obtienen las transacciones personalizadas con los parámetros especificados
    $tickets = $TransaccionSportsbook->getTransaccionesCustom(" usuario.login,usuario.moneda,transaccion_sportsbook.bet_status,transaccion_sportsbook.ticket_id,transaccion_sportsbook.usuario_id,transaccion_sportsbook.vlr_apuesta,transaccion_sportsbook.vlr_premio,transaccion_sportsbook.estado,transaccion_sportsbook.fecha_crea,transaccion_sportsbook.dir_ip,transaccion_sportsbook.transsport_id  ", "transaccion_sportsbook.transsport_id", "desc", $SkeepRows, $MaxRows, $json2, true, "transaccion_sportsbook.transsport_id");
    // Se decodifica el resultado JSON en un objeto PHP
    $tickets = json_decode($tickets);

    $total = 0; // Inicializa el contador total
    $bets = []; // Inicializa el arreglo de apuestas
    $response = array(); // Inicializa el arreglo de respuesta    foreach ($tickets->data as $key => $value) {

    foreach ($tickets->data as $key => $value) {

        /*El código asigna un valor a la variable $outcome basado en el estado de la apuesta (bet_status) utilizando una estructura switch.*/
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

        /*El código inicializa un array $arraybet con información de una transacción de apuestas deportivas.*/
        $arraybet = [];


        $arraybet = array();
        $arraybet["id"] = ($value->{"transaccion_sportsbook.ticket_id"});
        $arraybet["type"] = 1;
        $arraybet["odd_type"] = null;
        $arraybet["amount"] = ($value->{"transaccion_sportsbook.vlr_apuesta"});
        $arraybet["k"] = floatval($value->{"transaccion_sportsbook.vlr_premio"}) / floatval(($value->{"transaccion_sportsbook.vlr_apuesta"}));
        $arraybet["currency"] = $value->{"usuario.moneda"};
        /*El código asigna valores a un array $arraybet con información de una transacción de apuestas deportivas.*/
        $arraybet["outcome"] = $outcome;
        $arraybet["number"] = null;
        $arraybet["client_id"] = 1;
        $arraybet["betshop_id"] = null;
        $arraybet["is_live"] = false;
        $arraybet["payout"] = ($value->{"transaccion_sportsbook.vlr_premio"});
        $arraybet["possible_win"] = ($value->{"transaccion_sportsbook.vlr_premio"});
        $arraybet["accept_type_id"] = 0;
        $arraybet["client_login"] = $value->{"usuario.login"};
        $arraybet["barcode"] = '';
        /*El código convierte la fecha de creación de una transacción a formato de timestamp y asigna un array vacío a la clave events de la apuesta.*/
        $arraybet["calc_date"] = strtotime($value->{"transaccion_sportsbook.fecha_crea"});
        $arraybet["date_time"] = strtotime($value->{"transaccion_sportsbook.fecha_crea"});

        $arraybet["events"] = array(/* '-5': 'On Hold', '-4': 'Declined', '0': 'UNSETTLED', '1': 'Lost', '2': 'Returned', '3': 'Won', '5': 'Cashed out' */

        );

        /*El código inicializa variables, crea un filtro JSON con reglas específicas y lo codifica en formato JSON.*/
        $SkeepRows = 0;
        $OrderedItem = 1;
        $MaxRows = 10;
        $rules = [];

        array_push($rules, array("field" => "transsportsbook_detalle.transsport_id", "data" => $value->{"transaccion_sportsbook.transsport_id"}, "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json3 = json_encode($filtro);

        /*El código crea una instancia de TranssportsbookDetalle, obtiene transacciones personalizadas y decodifica el resultado JSON.*/
        $TranssportsbookDetalle = new TranssportsbookDetalle();
        $ticketdetalles = $TranssportsbookDetalle->getTransaccionesCustom(" transsportsbook_detalle.* ", "transsportsbook_detalle.transsportdet_id", "asc", $SkeepRows, $MaxRows, $json3, true);


        $ticketdetalles = json_decode($ticketdetalles);


        /*El código recorre los detalles de las transacciones deportivas y agrega información específica de cada evento a un array.*/
        foreach ($ticketdetalles->data as $key2 => $value2) {


            $arraybetdetail = array();

        // Asigna valores desde el objeto $value2 al arreglo de detalles de apuestas
        $arraybetdetail["game_start_date"] = $value2->{"transsportsbook_detalle.fecha_evento"};
        $arraybetdetail["team1"] = $value2->{"transsportsbook_detalle.apuesta"};
        $arraybetdetail["team2"] = $value2->{"transsportsbook_detalle.apuesta"};
        $arraybetdetail["market_name"] = $value2->{"transsportsbook_detalle.agrupador"};
        $arraybetdetail["event_name"] = $value2->{"transsportsbook_detalle.apuesta"};
        $arraybetdetail["coeficient"] = $value2->{"transsportsbook_detalle.logro"};

            // Agrega el detalle de la apuesta al arreglo principal
            array_push($arraybet["events"], $arraybetdetail);


        }
        // Agrega el arreglo de apuestas al conjunto final de apuestas
        array_push($bets, $arraybet);


    }

    if (false) {


//array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        /*El código agrega reglas de filtro a un array, las codifica en formato JSON y obtiene tickets personalizados de la base de datos utilizando esas reglas.*/
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));
        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));

        // Se construye el filtro usando las reglas definidas anteriormente y agrupándolas con un operador AND
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        // Se codifica el filtro en formato JSON para su uso posterior
        $json2 = json_encode($filtro);

        // Se obtienen los tickets personalizados de $ItTicketEnc, consultando los campos deseados y aplicando el filtro
        $tickets = $ItTicketEnc->getTicketsCustom(" usuario.login,usuario.moneda,CONCAT(it_ticket_enc.fecha_crea, ' ',it_ticket_enc.hora_crea) fecha,it_ticket_enc.it_ticket_id,it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.cant_lineas,it_ticket_enc.bet_status ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        /*Inicializa las variables $tickets, $total y $bets con un objeto decodificado, un contador en cero y un arreglo vacío, respectivamente.*/
        $tickets = json_decode($tickets);

        $total = 0;
        $bets = [];

        foreach ($tickets->data as $key => $value) {

// Inicializa la variable de resultado en 0
$outcome = 0;

            switch ($value->{"it_ticket_enc.bet_status"}) {
                case "S":
                    $outcome = 3; // Apuesta ganada
                    break;
                case "N":
                    $outcome = 1; // Apuesta no ganada
                    break;
            }

            // Crea un array para almacenar la información de la apuesta
            $arraybet = array();
            $arraybet["id"] = ($value->{"it_ticket_enc.it_ticket_id"}); // ID de la apuesta
            $arraybet["type"] = 1; // Tipo de apuesta
            $arraybet["odd_type"] = null; // Tipo de cuota
            $arraybet["amount"] = ($value->{"it_ticket_enc.vlr_apuesta"}); // Monto de la apuesta
            $arraybet["k"] = floatval($value->{"it_ticket_enc.vlr_premio"}) / floatval(($value->{"it_ticket_enc.vlr_apuesta"})); // Cuota calculada
            $arraybet["currency"] = $value->{"usuario.usuario.moneda"}; // Moneda de la apuesta
            $arraybet["outcome"] = $outcome; // Resultado de la apuesta
            $arraybet["number"] = null; // Numero de la apuesta
            $arraybet["client_id"] = 1; // ID del cliente
            $arraybet["betshop_id"] = null; // ID de la casa de apuestas
            $arraybet["is_live"] = false; // Estado de la apuesta en vivo
            $arraybet["payout"] = ($value->{"it_ticket_enc.vlr_premio"}); // Pago de la apuesta
            $arraybet["possible_win"] = ($value->{"it_ticket_enc.vlr_premio"}); // Ganancia posible
            $arraybet["accept_type_id"] = 0; // ID del tipo de aceptación
            $arraybet["client_login"] = $value->{"usuario.usuario.login"}; // Login del cliente
            $arraybet["barcode"] = ''; // Código de barras
            $arraybet["calc_date"] = strtotime($value->{".fecha"}); // Fecha de cálculo
            $arraybet["date_time"] = strtotime($value->{".fecha"}); // Fecha y hora de la apuesta

            $arraybet["events"] = array(/* '-5': 'On Hold', '-4': 'Declined', '0': 'UNSETTLED', '1': 'Lost', '2': 'Returned', '3': 'Won', '5': 'Cashed out' */
            );

            // Inicializa variables para el manejo de filas
            $SkeepRows = 0; // Filas a omitir
            $OrderedItem = 1; // Item ordenado
            $MaxRows = 10; // Máximo de filas
            $rules = []; // Reglas vacías

            // Se añade una regla al array $rules para filtrar los tickets por el ID del ticket
            array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => $value->{"it_ticket_enc.ticket_id"}, "op" => "eq"));

            // Se construye un filtro en formato array con las reglas y la operación de grupo
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            // Se codifica el filtro a formato JSON
            $json3 = json_encode($filtro);

// Se obtienen los detalles del ticket utilizando el método getTicketDetallesCustom
$ticketdetalles = $ItTicketEnc->getTicketDetallesCustom(" CONCAT(it_ticket_det.fecha_evento, ' ',it_ticket_det.hora_evento) fecha,it_ticket_det.ticket_id,it_ticket_det.apuesta, it_ticket_det.agrupador,it_ticket_det.logro,it_ticket_det.opcion,it_ticket_det.apuesta_id,it_ticket_det.agrupador_id ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json3, true);

// Se decodifica el JSON obtenido en un objeto PHP
$ticketdetalles = json_decode($ticketdetalles);

// Se recorre cada detalle del ticket
foreach ($ticketdetalles->data as $key2 => $value2) {

    // Se inicializa un array para almacenar los detalles de las apuestas
    $arraybetdetail = array();

    // Se asignan los detalles del evento de la apuesta al array
                $arraybetdetail["game_start_date"] = $value2->{".fecha"};
                $arraybetdetail["team1"] = $value2->{"it_ticket_det.apuesta"};
                $arraybetdetail["team2"] = $value2->{"it_ticket_det.apuesta"};
                $arraybetdetail["market_name"] = $value2->{"it_ticket_det.agrupador"};
                $arraybetdetail["event_name"] = $value2->{"it_ticket_det.apuesta"};
                $arraybetdetail["coeficient"] = $value2->{"it_ticket_det.logro"};

                // Se añade el detalle de la apuesta al array de apuestas
                array_push($arraybet["events"], $arraybetdetail);


            }

            // Se añade el array de apuestas al array principal de bets
            array_push($bets, $arraybet);


        }
    }
}else{
    // Verifica si la fecha de inicio no está vacía
    if ($FromDateLocal != "") {
        // Agrega una regla para filtrar los tickets cuya fecha de creación es mayor o igual a FromDateLocal
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal ", "op" => "ge"));
    }
    // Verifica si la fecha de fin no está vacía
    if ($ToDateLocal != "") {
        // Agrega una regla para filtrar los tickets cuya fecha de creación es menor o igual a ToDateLocal
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));
    }

    // Verifica si el ID de la apuesta no está vacío
    if ($bet_id != "") {
        // Agrega una regla para filtrar los tickets con el ID de apuesta específico
        array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => $bet_id, "op" => "eq"));
    }

    // Agrega una regla para filtrar los tickets por el ID del usuario mandante
    array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    // Agrega una regla para filtrar los tickets que no han sido eliminados
    array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));

    // Crea el filtro para las reglas utilizando operación AND
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    // Convierte el filtro a formato JSON
    $json2 = json_encode($filtro);

    // Crea una instancia de la clase ItTicketEnc
    $ItTicketEnc = new ItTicketEnc();

    // Obtiene los tickets personalizados según los criterios definidos
    $tickets = $ItTicketEnc->getTicketsCustom(" usuario.login,usuario.moneda,it_ticket_enc.bet_status,it_ticket_enc.ticket_id,it_ticket_enc.usuario_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.estado,it_ticket_enc.fecha_crea,it_ticket_enc.hora_crea,it_ticket_enc.dir_ip,it_ticket_enc.it_ticket_id  ", "it_ticket_enc.it_ticket_id", "desc", $SkeepRows, $MaxRows, $json2, true);
    // Decodifica el resultado JSON de los tickets
    $tickets = json_decode($tickets);

    $total = 0;
    $bets = [];
    $response = array();

    foreach ($tickets->data as $key => $value) {

        /*El código asigna un valor a la variable $outcome basado en el estado de la apuesta (bet_status) utilizando una estructura switch.*/
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
        $arraybet["calc_date"] = strtotime($value->{"it_ticket_enc.fecha_crea"}. ' '.$value->{"it_ticket_enc.hora_crea"});
        $arraybet["date_time"] = strtotime($value->{"it_ticket_enc.fecha_crea"} . ' '.$value->{"it_ticket_enc.hora_crea"});

        $arraybet["events"] = array(/* '-5': 'On Hold', '-4': 'Declined', '0': 'UNSETTLED', '1': 'Lost', '2': 'Returned', '3': 'Won', '5': 'Cashed out' */

        );

        $SkeepRows = 0;
        $OrderedItem = 1;
        $MaxRows = 10;
        $rules = [];

        // Agrega una regla al array de reglas para filtrar por ticket_id.
        array_push($rules, array("field" => "it_ticket_det.ticket_id", "data" => $value->{"it_ticket_enc.ticket_id"}, "op" => "eq"));

        // Crea un filtro que incluye las reglas y un operador de agrupación.
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        // Convierte el filtro a formato JSON.
        $json3 = json_encode($filtro);

        // Crea una instancia de ItTicketDet de la capa de Backend.
        $ItTicketDet = new \Backend\dto\ItTicketDet();
        // Obtiene los detalles del ticket utilizando el método getTicketDetallesCustom.
        $ticketdetalles = $ItTicketEnc->getTicketDetallesCustom(" it_ticket_det.* ", "it_ticket_det.it_ticketdet_id", "asc", $SkeepRows, $MaxRows, $json3, true);

        // Decodifica el JSON obtenido en un objeto.
        $ticketdetalles = json_decode($ticketdetalles);

        // Itera sobre los detalles del ticket decodificados.
        foreach ($ticketdetalles->data as $key2 => $value2) {

            // Inicializa un array para almacenar los detalles de apuestas.
            $arraybetdetail = array();

            // Asigna valores del ticket a las propiedades del array de detalles.
            $arraybetdetail["game_start_date"] = $value2->{"it_ticket_det.fecha_evento"};
            $arraybetdetail["team1"] = $value2->{"it_ticket_det.apuesta"};
            $arraybetdetail["team2"] = $value2->{"it_ticket_det.apuesta"};
            $arraybetdetail["market_name"] = $value2->{"it_ticket_det.agrupador"};
            $arraybetdetail["event_name"] = $value2->{"it_ticket_det.apuesta"};
            $arraybetdetail["coeficient"] = $value2->{"it_ticket_det.logro"};
            $arraybetdetail["option"] = $value2->{"it_ticket_det.opcion"};

            // Agrega el detalle de la apuesta al array de eventos.
            array_push($arraybet["events"], $arraybetdetail);


        }
        // Agrega el array de apuestas al array principal de apuestas.
        array_push($bets, $arraybet);


    }

}
$response["code"] = 0; // Código de respuesta, 0 indica éxito
$response["rid"] = $json->rid; // Identificador de la solicitud (request ID) extraído del JSON
$response["data"] = array("bets" => $bets, "total_count" => $tickets->count[0]->{".count"}); // Datos de la respuesta, que incluyen las apuestas y el conteo total de boletos
$response["total_count"] = $tickets->count[0]->{".count"}; // Conteo total de boletos