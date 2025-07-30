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
 * Este script genera una respuesta JSON con información de configuración de un socio en una plataforma de apuestas.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud, incluyendo la sesión del usuario.
 * @param object $json->session Objeto JSON que incluye información de la sesión del usuario.
 * @param string $json->session->sid ID de la sesión del usuario.
 * 
 *
 * @return array $response Respuesta en formato JSON que incluye:
 *                         - code: Código de estado de la respuesta (0 para éxito).
 *                         - rid: Identificador único de la respuesta.
 *                         - data: Datos de configuración del socio, incluyendo:
 *                           - subid: Identificador único generado a partir del ID de sesión.
 *                           - partner: Información del socio, como ID, moneda, opciones de cashout, etc.
 */

/* Crea un array con información sobre un socio en una plataforma de apuestas. */
$response = array("code" => 0, "rid" => "15062809258173", "data" => array("subid" => "7040" . $json->session->sid . "2", "data" => array("partner" => array("4" => array("partner_id" => 4, "currency" => "USD", "is_cashout_live" => 1, "is_cashout_prematch" => 1, "cashout_percetage" => 10.0, "maximum_odd_for_cashout" => 51.0, "is_counter_offer_available" => 1, "sports_book_profile_ids=>" => [1, 2, 5], "odds_raised_percent" => 5.0, "minimum_offer_amount" => 200.0, "min_bet_stakes" => array("USD" => 0.1, "EUR" => 0.1, "RUB" => 0.1, "UAH" => 0.1, "CNY" => 0.1, "KZT" => 0.1, "PLN" => 0.1, "SEK" => 0.1, "GBP" => 0.1, "MXN" => 0.1, "GEL" => 0.1, "TRY" => 0.1), "user_password_min_length" => 6, "id" => 4)))));
