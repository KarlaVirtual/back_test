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
exit();
/**
 * Obtener la información del usuario.
 *
 * @param int $json->params->user_id ID del usuario.
 * @param string $json->params->correo Correo electrónico del usuario.
 *
 * @return array Respuesta en formato JSON:
 * - code (int) Código de respuesta.
 * - data (array) Datos del usuario:
 *   - nro_cliente (int) Número de cliente.
 *   - nombre (string) Primer nombre del usuario.
 *   - apellido (string) Apellido del usuario.
 *   - cedula (string) Cédula del usuario.
 *   - fecha_creacion (string) Fecha de creación de la cuenta.
 *   - last_login (string) Último inicio de sesión.
 *   - celular (string) Número de celular del usuario.
 *   - disponible_jugar (float) Saldo disponible para jugar.
 *   - disponible_retiro (float) Saldo disponible para retiro.
 *   - pais (string) Código del país.
 *   - departamento (string) Código del departamento.
 *   - ciudad (string) Código de la ciudad.
 *   - estado_especial (string) Estado especial del usuario.
 *   - email (string) Correo electrónico del usuario.
 *   - ip (string) Dirección IP del usuario.
 *   - estado (string) Estado del usuario.
 *   - moneda (string) Moneda utilizada por el usuario.
 *   - observ_especial (string) Observaciones especiales.
 *   - apuestas_promedio (string) Promedio de apuestas.
 *   - tickets_premiados (string) Total de tickets premiados.
 *   - total_recarga (string) Total recargado por el usuario.
 *   - total_ganancia (string) Total de ganancias.
 *   - total_reinvertido (string) Total reinvertido.
 *   - tickets_abiertos (string) Total de tickets abiertos.
 *   - tickets_total (string) Total de tickets.
 *   - total_apostado (string) Total apostado.
 *   - total_premios (string) Total de premios.
 *   - porcentaje_utilidad (string) Porcentaje de utilidad.
 *   - asertividad_tickets (string) Asertividad en tickets.
 *   - balance (string) Balance del usuario.
 *   - username (string) Nombre de usuario.
 *   - country_code (string) Código de país (opcional).
 *   - city (string) Ciudad (opcional).
 *   - user_id (string) ID del usuario (opcional).
 *   - first_name (string) Primer nombre (opcional).
 *   - sur_name (string) Apellido (opcional).
 *   - sex (string) Sexo del usuario.
 *   - address (string) Dirección.
 *   - birth_date (string) Fecha de nacimiento.
 *   - doc_number (string) Número de documento.
 *   - phone (string) Teléfono.
 *   - mobile_phone (string) Teléfono móvil.
 *   - iban (string) IBAN.
 *   - is_verified (boolean) Indica si el usuario está verificado.
 *   - maximal_daily_bet (float) Apuesta diaria máxima.
 *   - maximal_single_bet (float) Apuesta única máxima.
 *   - personal_id (string) ID personal.
 *   - subscribed_to_news (boolean) Indica si el usuario está suscrito a noticias.
 *   - loyalty_point (float) Puntos de lealtad.
 *   - loyalty_earned_points (float) Puntos de lealtad ganados.
 *   - loyalty_exchanged_points (float) Puntos de lealtad canjeados.
 *   - loyalty_level_id (int) ID del nivel de lealtad.
 *   - casino_maximal_daily_bet (float) Apuesta diaria máxima en casino.
 *   - casino_maximal_single_bet (float) Apuesta única máxima en casino.
 *   - zip_code (string) Código postal.
 *   - currency (string) Moneda.
 *   - casino_balance (float) Balance en casino.
 *   - bonus_balance (float) Balance de bonos.
 *   - frozen_balance (float) Balance congelado.
 *   - bonus_win_balance (float) Balance de ganancias de bonos.
 *   - bonus_money (float) Dinero de bonos.
 *   - province (string) Provincia.
 *   - active_step (string) Paso activo.
 *   - active_step_state (string) Estado del paso activo.
 *   - has_free_bets (boolean) Indica si el usuario tiene apuestas gratuitas.
 *   - swift_code (string) Código SWIFT.
 *   - additional_address (string) Dirección adicional.
 *   - affiliate_id (int) ID del afiliado.
 *   - btag (string) Etiqueta de afiliado.
 *   - exclude_date (string) Fecha de exclusión.
 *   - reg_date (string) Fecha de registro.
 *   - doc_issue_date (string) Fecha de emisión del documento.
 *   - subscribe_to_email (boolean) Indica si el usuario está suscrito a correos electrónicos.
 *   - subscribe_to_sms (boolean) Indica si el usuario está suscrito a SMS.
 *   - subscribe_to_bonus (boolean) Indica si el usuario está suscrito a bonos.
 *   - unread_count (int) Conteo de mensajes no leídos.
 *   - incorrect_fields (array) Campos incorrectos.
 *   - loyalty_last_earned_points (float) Últimos puntos de lealtad ganados.
 *   - loyalty_point_usage_period (int) Período de uso de puntos de lealtad.
 *   - loyalty_min_exchange_point (int) Puntos mínimos de canje de lealtad.
 *   - loyalty_max_exchange_point (int) Puntos máximos de canje de lealtad.
 *   - active_time_in_casino (string) Tiempo activo en casino.
 *   - last_login_date (int) Fecha del último inicio de sesión.
 *   - name (string) Nombre.
 *
 * @throws Exception "No se encontró el usuario" con código "11".
 */


$user_id = $json->params->user_id;
$correo = $json->params->correo;

if ($user_id != "" && $user_id != undefined && $user_id != "undefined") {
    $Usuario = new Usuario($user_id);
} elseif ($correo != "" && $correo != undefined && $correo != "undefined") {

    $UsuarioMysqlDAO = new \Backend\mysql\UsuarioMySqlDAO();

    $Usuario = $UsuarioMysqlDAO->queryByLogin($correo);
    $Usuario = $Usuario[0];
}

if ($Usuario != null) {
    /*Se obtienen las estadísticas del usuario y se formatean los valores necesarios.*/
    $UsuarioEstadisticas = $Usuario->getEstadisticas();

    $apuesta_prom = number_format($UsuarioEstadisticas[".apuesta_prom"], 2);
    $tot_tickets = number_format($UsuarioEstadisticas[".tot_tickets"], 2);
    $tot_premiados = number_format($UsuarioEstadisticas[".tot_premiados"], 2);
    $tot_apostado = number_format($UsuarioEstadisticas[".tot_apostado"], 2);
    $tot_premio = number_format($UsuarioEstadisticas[".tot_premio"], 2);
    $tot_recarga = number_format($UsuarioEstadisticas[".tot_recarga"], 2);
    $tot_abiertos = number_format($UsuarioEstadisticas[".tot_abiertos"], 2);
    $ganancias = number_format($tot_premio - $tot_apostado, 2);
    $porcentaje_utilidad = number_format(($tot_premio - $tot_apostado) / $tot_apostado * 100, 1);
    $total_reinvertido = number_format($tot_apostado - $tot_recarga, 2);
    $asertividad = number_format($tot_premiados / $tot_tickets * 100, 1);

    $Registro = new Registro("", $Usuario->usuarioId);

    /*Almacenamiento de respuesta*/
    $response = array(
        "code" => 0,
        "data" => array(

            "nro_cliente" => $Usuario->usuarioId,
            "nombre" => $Registro->getNombre1(),
            "apellido" => $Registro->getNombre2(),
            "cedula" => $Registro->getCedula(),
            "fecha_creacion" => "",
            "last_login" => "",
            "celular" => $Registro->getCelular(),
            "disponible_jugar" => $Usuario->getBalance(),
            "disponible_retiro" => $Registro->getCreditos(),
            "pais" => "1",
            "departamento" => "1",
            "ciudad" => "1",
            "estado_especial" => "",
            "email" => $Usuario->login,
            "ip" => $Usuario->dirIp,
            "estado" => $Usuario->estado,
            "moneda" => $Usuario->moneda,
            "observ_especial" => "TEST",

            "apuestas_promedio" => $apuesta_prom,
            "tickets_premiados" => $tot_premiados,
            "total_recarga" => $tot_recarga,
            "total_ganancia" => $ganancias,
            "total_reinvertido" => $total_reinvertido,
            "tickets_abiertos" => $tot_abiertos,
            "tickets_total" => $tot_tickets,
            "total_apostado" => $tot_apostado,
            "total_premios" => $tot_premio,
            "porcentaje_utilidad" => $porcentaje_utilidad,
            "asertividad_tickets" => $asertividad,

            "balance" => "",
            "username" => $Usuario->login,
            "country_code" => "",
            "city" => "",
            "user_id" => "",
            "first_name" => "",
            "sur_name" => "",
            "sex" => "M",
            "address" => "CALLE",
            "birth_date" => "",
            "doc_number" => "",
            "phone" => "",
            "mobile_phone" => null,
            "iban" => null,
            "is_verified" => false,
            "maximal_daily_bet" => null,
            "maximal_single_bet" => null,
            "personal_id" => null,
            "subscribed_to_news" => false,
            "loyalty_point" => 0.0,
            "loyalty_earned_points" => 0.0,
            "loyalty_exchanged_points" => 0.0,
            "loyalty_level_id" => null,
            "casino_maximal_daily_bet" => null,
            "casino_maximal_single_bet" => null,
            "zip_code" => null,
            "currency" => "",
            "casino_balance" => "",
            "bonus_balance" => 0.0,
            "frozen_balance" => 0.0,
            "bonus_win_balance" => 0.0,
            "bonus_money" => 0.0,
            "province" => null,
            "active_step" => null,
            "active_step_state" => null,
            "has_free_bets" => false,
            "swift_code" => null,
            "additional_address" => null,
            "affiliate_id" => null,
            "btag" => null,
            "exclude_date" => null,
            "reg_date" => "2017-08-13",
            "doc_issue_date" => null,
            "subscribe_to_email" => true,
            "subscribe_to_sms" => true,
            "subscribe_to_bonus" => true,
            "unread_count" => 1,
            "incorrect_fields" => null,
            "loyalty_last_earned_points" => 0.0,
            "loyalty_point_usage_period" => 0,
            "loyalty_min_exchange_point" => 0,
            "loyalty_max_exchange_point" => 0,
            "active_time_in_casino" => null,
            "last_login_date" => 1507440476,
            "name" => "",
        ),
    );
} else {
    /*Excepción lanzada cuando no se encuentra un usuario.*/
    throw new Exception("No se encontro el usuario", "11");
}
