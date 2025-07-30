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
 *
 * command/user_limits
 *
 * Consulta de límites configurados para el usuario
 *
 * Este recurso permite obtener la configuración de límites asociados a un usuario mandante,
 * incluyendo restricciones de depósitos y apuestas en diferentes categorías como deportes,
 * casino y juegos virtuales. Se filtran los datos según el usuario y los tipos de límites predefinidos.
 *
 * @param string $usuario : Nombre de usuario en la sesión activa.
 * @param string $type : Tipo de consulta (opcional).
 * @param int $count : Número máximo de registros a recuperar (por defecto 100).
 * @param int $start : Número de registros a omitir en la consulta (por defecto 0).
 *
 * @return object  $response es un array con los siguientes atributos:
 *  - *code* (int): Código de éxito o error de la operación.
 *  - *data* (array): Contiene los resultados de la consulta de límites.
 *      - *result* (int): Resultado de la consulta (0 en caso de éxito).
 *      - *result_text* (string|null): Mensaje adicional sobre el resultado.
 *      - *details* (array): Lista de límites configurados para el usuario.
 *          - *id* (int): Identificador único de la configuración de límite.
 *          - *time_frame* (string): Periodo de tiempo del límite (24 Horas, 1 Semana, 1 Mes, No definido).
 *          - *cancel* (bool): Indica si el límite puede ser cancelado (true/false).
 *          - *type* (string): Tipo de límite (Deposito, Apuesta Deportiva, Casino, Casino en Vivo, Virtuales, No definido).
 *          - *status* (string): Estado del límite configurado.
 *          - *date_created* (string): Fecha de creación del límite.
 *          - *end_date* (string): Fecha de finalización del límite.
 *          - *amount* (float): Valor asignado al límite.
 *  - *total_count* (int): Cantidad total de configuraciones de límites encontradas.
 *
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crea un objeto UsuarioMandante y se inicializa un array de límites. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);


$limites = array();

$type = $json->params->type;


/* inicializa variables para manejar paginación de datos en JSON. */
$OrderedItem = 1;

$MaxRows = $json->params->count ?? 100;
$SkeepRows = $json->params->start ?? 0;

$rules = [];

/* Agrega una regla de validación para el usuario en el array de reglas. */
array_push($rules, array("field" => "usuario_configuracion.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
//array_push($rules, array("field" => "usuario_configuracion.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "clasificador.abreviado", "data" => "

        'LIMITEDEPOSITOSIMPLE',
'LIMITEDEPOSITODIARIO',
'LIMITEDEPOSITOSEMANA',
'LIMITEDEPOSITOMENSUAL',
'LIMAPUDEPORTIVASIMPLE',
'LIMAPUDEPORTIVADIARIO',
'LIMAPUDEPORTIVASEMANA',
'LIMAPUDEPORTIVAMENSUAL',
'LIMAPUDEPORTIVAANUAL',
'LIMAPUCASINOSIMPLE',
'LIMAPUCASINODIARIO',
'LIMAPUCASINOSEMANA',
'LIMAPUCASINOMENSUAL',
'LIMAPUCASINOANUAL',
'LIMAPUCASINOVIVOSIMPLE',
'LIMAPUCASINOVIVODIARIO',
'LIMAPUCASINOVIVOSEMANA',
'LIMAPUCASINOVIVOMENSUAL',
'LIMAPUCASINOVIVOANUAL',
'LIMAPUVIRTUALESSIMPLE',
'LIMAPUVIRTUALESDIARIO',
'LIMAPUVIRTUALESSEMANA',
'LIMAPUVIRTUALESMENSUAL',
'LIMAPUVIRTUALESANUAL'

", "op" => "in"));


/* Se crea un JSON con reglas para consultar configuraciones de usuario en la base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$UsuarioConfiguracion = new UsuarioConfiguracion();

$configuraciones = $UsuarioConfiguracion->getUsuarioConfiguracionesCustom(" usuario_configuracion.*,clasificador.* ", "usuario_configuracion.usuconfig_id", "asc", $SkeepRows, $MaxRows, $json2, true);


/* Se decodifica un JSON y se inicializa un array vacío para configuraciones. */
$configuraciones = json_decode($configuraciones);

$configuracionesData = array();
foreach ($configuraciones->data as $item) {

    /* Se extraen fechas y diferencias entre dos fechas de configuración de usuario. */
    $limites["id"] = $item->{'usuario_configuracion.usuconfig_id'};

    $date1 = new DateTime($item->{'usuario_configuracion.fecha_crea'});
    $date2 = new DateTime($item->{'usuario_configuracion.fecha_fin'});
    $now = new DateTime("now");
    $time_frame = $date1->diff($date2);

    /* asigna etiquetas de tiempo según condiciones de duración específicas. */
    if ($time_frame->days == 1 && $time_frame->h == 0) {
        $limites["time_frame"] = '24 Horas';
    } elseif ($time_frame->days == 7 && $time_frame->m == 0) {
        $limites["time_frame"] = '1 Semana';
    } elseif ($time_frame->m == 1 && $time_frame->d == 0 && $time_frame->y == 0) {
        $limites["time_frame"] = '1 Mes';
    } else {
        /* Establece "No definido" como valor para el tiempo si no se cumple cierta condición. */

        $limites["time_frame"] = 'No definido';
    }


    /* Calcula si se puede cancelar, comparando fechas y horas transcurridas. */
    $diferencia = $date1->diff($now);
    if ($diferencia->days >= 1 || ($diferencia->days == 0 && $diferencia->h >= 24)) {
        $limites["cancel"] = true;
    } else {
        $limites["cancel"] = false;
    }

    $limites["type"] = match ($item->{'clasificador.abreviado'}) {

        'LIMITEDEPOSITOSIMPLE' => 'Deposito',
        'LIMITEDEPOSITODIARIO' => 'Deposito',
        'LIMITEDEPOSITOSEMANA' => 'Deposito',
        'LIMITEDEPOSITOMENSUAL' => 'Deposito',
        'LIMAPUDEPORTIVASIMPLE' => 'Apuesta Deportiva',
        'LIMAPUDEPORTIVADIARIO' => 'Apuesta Deportiva',
        'LIMAPUDEPORTIVASEMANA' => 'Apuesta Deportiva',
        'LIMAPUDEPORTIVAMENSUAL' => 'Apuesta Deportiva',
        'LIMAPUDEPORTIVAANUAL' => 'Apuesta Deportiva',
        'LIMAPUCASINOSIMPLE' => 'Casino',
        'LIMAPUCASINODIARIO' => 'Casino',
        'LIMAPUCASINOSEMANA' => 'Casino',
        'LIMAPUCASINOMENSUAL' => 'Casino',
        'LIMAPUCASINOANUAL' => 'Casino en vivo',
        'LIMAPUCASINOVIVOSIMPLE' => 'Casino en vivo',
        'LIMAPUCASINOVIVODIARIO' => 'Casino en vivo',
        'LIMAPUCASINOVIVOSEMANA' => 'Casino en vivo',
        'LIMAPUCASINOVIVOMENSUAL' => 'Casino en vivo',
        'LIMAPUCASINOVIVOANUAL' => 'Casino en vivo',
        'LIMAPUVIRTUALESSIMPLE' => 'Virtuales',
        'LIMAPUVIRTUALESDIARIO' => 'Virtuales',
        'LIMAPUVIRTUALESSEMANA' => 'Virtuales',
        'LIMAPUVIRTUALESMENSUAL' => 'Virtuales',
        default => 'No definido'
    };


    /* Asigna propiedades de 'usuario_configuracion' a un array 'limites' y lo agrega a 'configuracionesData'. */
    $limites["status"] = $item->{'usuario_configuracion.estado'};
    $limites["date_created"] = $item->{'usuario_configuracion.fecha_crea'};
    $limites["end_date"] = $item->{'usuario_configuracion.fecha_fin'};
    $limites["amount"] = $item->{'usuario_configuracion.valor'};

    array_push($configuracionesData, $limites);
}


/* Genera un arreglo en PHP con respuesta y detalles de configuraciones. */
$response = array(
    "code" => 0,
    "data" => array(
        "result" => 0,
        "result_text" => null,
        "details" => $configuracionesData,
    ),
    "total_count" => $configuraciones->count[0]->{'.count'},
);


