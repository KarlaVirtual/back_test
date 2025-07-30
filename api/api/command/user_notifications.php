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
use Backend\dto\UsuarioHistorial;
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
 * command/user_notifications
 *
 * Notificaciones del usuario
 *
 * @param string $json : Objeto JSON con los parámetros de filtrado.
 *
 * @return object  $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor 0 en caso de exito.
 *  - *result* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene la lista de notificaciones del usuario.
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Variables que controlan la paginación y agrupación de datos en una consulta. */
$MaxRows = 15;
$SkeepRows = 0;
$grouping = "";

/**
 * time_elapsed_string
 *
 * Calcula el tiempo transcurrido desde una fecha dada hasta el momento actual y lo devuelve en formato legible.
 *
 * @param string $datetime Fecha y hora de referencia en formato compatible con DateTime.
 * @param bool $full (Opcional) Determina si se debe mostrar el tiempo completo o solo la unidad más significativa.
 *                   Por defecto es false (solo la unidad más significativa).
 *
 * @return string Cadena de texto con el tiempo transcurrido en inglés, como "2 hours ago" o "just now".
 *
 * El formato de salida varía según la diferencia de tiempo:
 * - Segundos: "X seconds ago"
 * - Minutos: "X minutes ago"
 * - Horas: "X hours ago"
 * - Días: "X days ago"
 * - Semanas: "X weeks ago"
 * - Meses: "X months ago"
 * - Años: "X years ago"
 *
 * @throws Exception no
 */

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    /* formatea una fecha y crea un objeto UsuarioMandante con datos de sesión. */
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

if ($json->params->where->from_date != "") {
    $FromDateLocal = date('Y-m-d 00:00:00', $json->params->where->from_date);

}

/* Verifica si "to_date" existe, formatea la fecha y almacena parámetros. */
if ($json->params->where->to_date != "") {
    $ToDateLocal = date('Y-m-d 23:59:59', $json->params->where->to_date);

}
$type = $json->params->where->type;
$product = $json->params->where->product;


/* agrega una regla de filtrado si se proporciona una fecha de inicio. */
$rules = [];

if ($FromDateLocal != "") {
    array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

}

/* Condiciones que añaden reglas basadas en el valor de la variable $type. */
if ($type == 8) {

    array_push($rules, array("field" => "usuario_historial.tipo", "data" => "40,41", "op" => "in"));
}
if ($type == 3) {

    array_push($rules, array("field" => "usuario_historial.tipo", "data" => "10", "op" => "in"));
}

/* Condicionalmente agrega reglas a un array según tipo y fecha especificados. */
if ($type == 1) {

    array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30 ", "op" => "eq"));
}
if ($ToDateLocal != "") {
    array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}
if (is_numeric($type)) {

    switch ($type) {

        case 0:
            /* Se crean reglas de filtrado según el tipo de producto en un historial de usuario. */

            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));

            if ($product == '0') {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20", "op" => "eq"));

            } elseif ($product == '1') {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20,30", "op" => "in"));

            }

            break;

        case 1:
            /* Agrega reglas de filtrado basado en el tipo de producto seleccionado. */

            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));

            if ($product == '0') {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20", "op" => "eq"));

            } elseif ($product == '1') {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20,30", "op" => "in"));

            }

            break;

        case 2:
            /* Código que define reglas de filtrado según el estado de un producto. */

            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "C", "op" => "eq"));

            if ($product == '0') {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20", "op" => "eq"));

            } elseif ($product == '1') {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20,30", "op" => "in"));

            }

            break;

        case 3:
            /* Agrega una regla al arreglo para verificar si tipo es igual a 10. */

            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "10", "op" => "eq"));


            break;

        case 5:
            /* Agrega una regla para comparar 'usuario_historial.tipo' con el valor '50'. */

            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "50", "op" => "eq"));


            break;

        case 8:
            /* Agrega una regla al array para comparar el tipo de usuario con el valor 40. */

            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "40", "op" => "eq"));


            break;

        case 9:
            /* Agrega reglas para filtrar datos de 'usuario_historial' basadas en condiciones específicas. */

            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "15", "op" => "eq"));
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));


            break;

        case 301:
            /* Agrega reglas de filtro para tipo y movimiento en un historial de usuario. */

            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "15", "op" => "eq"));
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));


            break;

        case 12:
            /* Se agregan reglas para filtrar historial de usuario por tipo y movimiento. */

            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "40", "op" => "eq"));
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));


            break;


        case 14:
            /* Agrega reglas de filtrado para historial de usuario en un caso específico. */

            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "40", "op" => "eq"));
            array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "'C','E'", "op" => "in"));


            break;

        case 15:
            /* Añade una regla que verifica si "tipo" es igual a "41". */

            array_push($rules, array("field" => "usuario_historial.tipo", "data" => "41", "op" => "eq"));


            break;
    }

} else {
    /* agrega reglas basadas en el valor de la variable $product. */

    if ($product == '0') {
        array_push($rules, array("field" => "usuario_historial.tipo", "data" => "20", "op" => "eq"));

    } elseif ($product == '1') {
        array_push($rules, array("field" => "usuario_historial.tipo", "data" => "30", "op" => "eq"));

    }
}


/* Se crea un filtro JSON para buscar usuarios en la base de datos. */
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

// $response = array("code" => 0, "rid" => "150630776768211", "data" => array("withdraw" => ["moneta", "netellernew", "gateway", "ecocard", "astropay", "astropaydirect", "cubits", "wirecardnew", "skrill", "moneybookers"], "deposit" => ["moneta", "netellernew", "gateway", "ecocard", "astropay", "astropaystreamline1", "astropaystreamline2", "astropaystreamline3", "astropaystreamline4", "astropaystreamline5", "astropaystreamline6", "astropaystreamline7", "astropaystreamline8", "astropaystreamline9", "astropaystreamline10", "astropaystreamline11", "astropaystreamline12", "astropaystreamline13", "astropaystreamline14", "cubits", "paysafecard", "wirecard", "skrill", "moneybookers", "yandex", "yandexbank", "yandexcash", "yandexinvois", "yandexprbank", "yandexsberbank", "pugglepay"]));

$select = "usuario_historial.*,usuario.nombre ";


/* Se obtiene el historial de usuario, se decodifica y se inicializan variables. */
$UsuarioHistorial = new UsuarioHistorial();
$data = $UsuarioHistorial->getUsuarioHistorialsCustom($select, "usuario_historial.usuhistorial_id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, true, $grouping);
$movimientos = json_decode($data);
$movimientosData = array();
$notificaciones = array();
$notificacionesUnread = 0;
foreach ($movimientos->data as $key => $value) {


    /* Se definen variables vacías y un array en PHP para almacenar datos. */
    $url = '';
    $img = '';
    $icon = '';
    $content = '';

    $array = array();

    /* Asigna el valor "Movimiento" a la clave "operation_name" en el array $array. */
    $array["operation_name"] = "Movimiento";


    /* clasifica operaciones de usuario según tipo y movimiento en un array. */
    switch ($value->{"usuario_historial.tipo"}) {

        case "10":
            if ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 3;
                $array["operation_name"] = "Deposito";
            } else {
                $array["operation"] = 3;
                $array["operation_name"] = "Cancelacion Deposito";
            }


            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $array["operation"] = 0;
                $array["operation_name"] = "Apuesta Deportiva";

            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 1;
                $array["operation_name"] = "Ganancia Deportiva";
            } else {
                $array["operation"] = 2;
                $array["operation_name"] = "Cancelacion Deportiva";
            }

            $icon = '💰';

            if ($value->{"usuario_historial.customs"} != '') {
            }

            if ($value->{"usuario_historial.movimiento"} == 'E') {
                $content = '<span>Depositaste</span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> a tu cuenta </span>';
                $url = '/consulta/consulta_depositos';
            } else {
                $content = '<span>Se ha cancelado un deposito por valor de </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> a tu cuenta </span>';
                $url = '/consulta/consulta_depositos';
                $icon = '‼️';
            }

            break;

        case "15":

            /* asigna valores a un arreglo basado en una condición de movimiento. */
            if ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 9;
                $array["operation_name"] = "Corrección";

            } else {
                $array["operation"] = 302;
                $array["operation_name"] = "Corrección";

            }


            /* genera un mensaje de ajuste de saldo según el tipo de movimiento. */
            if ($value->{"usuario_historial.movimiento"} == 'E') {
                $content = '<span>Se ha realizado un ajuste de saldo por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> a tu cuenta </span>';
                $url = '/apuestas';
                $icon = '⬆️️';
            } else {
                $content = '<span>Se ha realizado un ajuste de saldo por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> a tu cuenta </span>';
                $url = '/apuestas';
                $icon = '⬇️️';
            }
            break;

        case "20":

            /* verifica el tipo de movimiento del usuario y asigna valores a un array. */
            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $array["operation"] = 0;
                $array["operation_name"] = "Apuesta Deportiva";

            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 1;

                /* Se asigna nombre de operación basado en una condición, añadiendo un ícono de fútbol. */
                $array["operation_name"] = "Ganancia Deportiva";
            } else {
                $array["operation"] = 2;
                $array["operation_name"] = "Cancelacion Deportiva";
            }

            $icon = '⚽️';


            /* Condiciona acciones según el historial de usuario en apuestas deportivas. */
            if ($value->{"usuario_historial.customs"} != '') {
            }

            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $content = '<span>Apostaste</span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en </span><span class="text-indigo-700">Deportivas</span>';
                $url = '/apuestas';
            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                /* Condición que verifica si el movimiento es 'E' en el historial del usuario. */

                /* Genera un mensaje sobre ganancias o devoluciones en apuestas deportivas. */
                $content = '<span>Ganaste</span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en </span><span class="text-indigo-700">Deportivas</span>';
                $url = '/apuestas';
            } else {
                $content = '<span>Te devolvimos</span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en </span><span class="text-indigo-700">Deportivas</span>';
                $url = '/apuestas';
            }

            break;

        case "30":


            /* Verifica si hay historial de usuario y inicializa un objeto Producto. */
            if ($value->{"usuario_historial.customs"} != '') {
                $Producto = new Producto($value->{"usuario_historial.customs"});
                $img = $Producto->imageUrl;
                $img = '';
                $icon = '🎰';
            }


            /* Condicionalmente genera contenido HTML según el tipo de movimiento del usuario. */
            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $content = '<span>Jugaste</span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en </span><span class="text-indigo-700">' . $Producto->descripcion . '</span>';
                $url = '/new-casino';
            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                $content = '<span>Ganaste</span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en </span><span class="text-indigo-700">' . $Producto->descripcion . '</span>';

                $img = 'https://images.virtualsoft.tech/m/msjT1650084989.png';

                /* Genera contenido HTML dinámico basado en el historial del usuario y un producto. */
                $icon = '';
                $url = '/new-casino';
            } else {
                $content = '<span>Te devolvimos</span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en </span><span class="text-indigo-700">' . $Producto->descripcion . '</span>';
                $url = '/new-casino';
            }

            break;

        case "40":

            /* Asignación de operaciones según el tipo de movimiento del historial de usuario. */
            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $array["operation"] = 12;
                $array["operation_name"] = "Retiro Creado";
            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                $array["operation"] = 14;
                $array["operation_name"] = "Retiro Cancelado";
            } else {
                /* La sentencia else se utiliza para definir una alternativa en estructuras condicionales. */


                /* Define una operación de retiro y genera contenido respecto a su estado y valor. */
                $array["operation"] = 14;
                $array["operation_name"] = "Retiro Cancelado";
            }

            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $content = '<span>Se ha generado un retiro por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> de tu cuenta </span>';
                $url = '/consulta/consulta_retiros';
                $icon = '💰';
            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                /* Condicional que verifica si el movimiento del historial del usuario es 'E'. */

                /* Genera un mensaje de cancelación de retiro, incluyendo moneda y enlace de consulta. */
                $content = '<span>Se ha cancelado un retiro por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> de tu cuenta </span>';
                $url = '/consulta/consulta_retiros';
                $icon = '💰';
            } else {
                $content = '<span>Se ha cancelado un retiro por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> de tu cuenta </span>';
                $url = '/consulta/consulta_retiros';

                /* Asignación de un emoji de dinero a la variable `$icon`. */
                $icon = '💰';
            }

            break;

        case "41":
            /* Genera un mensaje sobre un retiro realizado por un usuario, incluyendo detalles económicos. */


            $content = '<span>Se ha pagado un retiro por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> </span>';
            $url = '/consulta/consulta_retiros';
            $icon = '💰';

            break;

        case "50":
            /* Codifica respuestas para movimientos de bonos en una cuenta de usuario. */


            $icon = '️🎁';


            if ($value->{"usuario_historial.movimiento"} == 'S') {
                $content = '<span>Hemos eliminado un bono por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en tu cuenta</span>';
                $url = '/apuestas';
            } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                $content = '<span>Ganaste un bono por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en tu cuenta</span>';
                $url = '/apuestas';
            } else {
                $content = '<span>Ganaste un bono por </span> <div class="font-semibold inline-block">' . $UsuarioMandante->moneda . ' ' . $value->{"usuario_historial.valor"} . '</div><span> en tu cuenta</span>';
                $url = '/apuestas';
            }


            break;
    }


    /* asigna valores a un array basado en un historial de usuario. */
    $array["amount"] = ($value->{"usuario_historial.valor"});
    $array["balance"] = ($value->{"usuario_historial.valor"});
    $array["date_time"] = ($value->{"usuario_historial.fecha_crea"});
    $array["product_category"] = 0;
    $array["transaction_id"] = $value->{"usuario_historial.externo_id"};
    $array2 = $array;

    /* Agrega un nuevo elemento a un arreglo con información de usuario y acción. */
    array_push($movimientosData, $array);

    $array = [];
    $array["id"] = $value->{"usuario_historial.usuhistorial_id"};
    $array["content"] = $array2["operation_name"];
    $array["content"] = '<span class="text-indigo-700">James Doe</span> <span> favourited an</span> <span class="text-indigo-700">item</span>';

    /* asigna valores a un array asociativo en PHP. */
    $array["content"] = $content;
    $array["img"] = 'https://tuk-cdn.s3.amazonaws.com/can-uploader/notification_1-svg2.svg';
    $array["img"] = $img;
    $array["icon"] = $icon;
    $array["url"] = $url;

    $array["checked"] = 0;

    /* Se crea un registro de notificación con información sobre el historial del usuario. */
    $array["date"] = time_elapsed_string($value->{"usuario_historial.fecha_crea"});
    $array["title"] = $array2["operation_name"];
    $array["action"] = "user.balanceHistory";

    array_push($notificaciones, $array);


}


if (false) {

    /* Extrae el tipo de usuario y crea instancias de UsuarioMandante y Usuario. */
    $type = $json->params->where->type;
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $ClientId = $UsuarioMandante->getUsuarioMandante();

    $Usuario = new Usuario($ClientId);

    /* obtiene y decodifica movimientos de usuario, almacenándolos en un array vacío. */
    $movimientos = $Usuario->getMovimientosResume("", "", $type);

    $movimientos = json_decode($movimientos);

    $movimientosData = array();

    foreach ($movimientos->data as $key => $value) {

        /*
           '0': 'New Bets',
          '1': 'Winning Bets',
          '2': 'Returned Bet',
          '3': 'Deposit',
          '4': 'Card Deposit',
          '5': 'Bonus',
          '6': 'Bonus Bet',
          '7': 'Commission',
          '8': 'Withdrawal',
          '9': 'Correction Up',
          '302': 'Correction Down',
          '10': 'Deposit by payment system',
          '12': 'Withdrawal request',
          '13': 'Authorized Withdrawal',
          '14': 'Withdrawal denied',
          '15': 'Withdrawal paid',
          '16': 'Pool Bet',
          '17': 'Pool Bet Win',
          '18': 'Pool Bet Return',
          '23': 'In the process of revision',
          '24': 'Removed for recalculation',
          '29': 'Free Bet Bonus received',
          '30': 'Wagering Bonus received',
          '31': 'Transfer from Gaming Wallet',
          '32': 'Transfer to Gaming Wallet',
          '37': 'Declined Superbet',
          '39': 'Bet on hold',
          '40': 'Bet cashout',
          '19': 'Fair',
          '20': 'Fair Win',
          '21': 'Fair Commission'


     */


        /* Se crea un arreglo vacío en PHP denominado "$array". */
        $array = array();

        switch ($value->{"movimientos.tipo"}) {
            case "DEBIT":
                /* Asigna 0 a "operation" si la operación es un débito en un array. */

                $array["operation"] = 0;
                break;

            case "CREDIT":
                /* asigna el valor 1 a "operation" si la opción es "CREDIT". */

                $array["operation"] = 1;
                break;

            case "ROLLBACK":
                /* asigna un valor a "operation" en caso de "ROLLBACK". */

                $array["operation"] = 2;
                break;

            case "BET":
                /* establece la operación en 0 para el caso "BET" en un switch. */

                $array["operation"] = 0;
                break;

            case "STAKEDECREASE":
                /* Establece la operación 2 para el caso "STAKEDECREASE" en un arreglo. */

                $array["operation"] = 2;
                break;

            case "REFUND":
                /* asigna el valor 2 a "operation" si la opción es "REFUND". */

                $array["operation"] = 2;
                break;

            case "WIN":
                /* asigna el valor 1 a "operation" si la condición es "WIN". */

                $array["operation"] = 1;
                break;

            case "NEWCREDIT":
                /* asigna el valor 2 a "operation" para el caso "NEWCREDIT". */

                $array["operation"] = 2;
                break;

            case "CASHOUT":
                /* asigna el valor 40 a "operation" si la opción es "CASHOUT". */

                $array["operation"] = 40;
                break;

            case "NEWDEBIT":
                /* Asigna el valor 2 a "operation" si el caso es "NEWDEBIT". */

                $array["operation"] = 2;
                break;

            case "Apuestas":
                /* asigna el valor 0 a "operation" si el caso es "Apuestas". */

                $array["operation"] = 0;

                break;

            case "Ganadoras":
                /* Asignación de valor a 'operation' en un arreglo basado en el caso "Ganadoras". */

                $array["operation"] = 1;

                break;

            case "Depositos":
                /* asigna un valor de operación específico para "Depositos". */

                $array["operation"] = 3;

                break;
            case "Retiros":
                /* Se asigna el valor 15 al elemento "operation" del array para "Retiros". */

                $array["operation"] = 15;

                break;

            case "RetirosPendientes":
                /* Código asigna el valor 12 a "operation" para la opción "RetirosPendientes". */

                $array["operation"] = 12;

                break;
        }


        /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
        $array["amount"] = ($value->{"movimientos.valor"});
        $array["balance"] = ($value->{"movimientos.valor"});
        $array["date_time"] = ($value->{"movimientos.fecha"});
        $array["operation_name"] = ($value->{"movimientos.tipo"});
        $array["product_category"] = 0;
        $array["transaction_id"] = 0;

        /* Se asigna un ID de transacción y se añade a un arreglo de movimientos. */
        $array["transaction_id2"] = $ClientId;

        array_push($movimientosData, $array);


    }


}


/* crea un arreglo de respuesta en formato JSON con un código y un ID. */
$response = array();


$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;

/* Crea una respuesta con notificaciones y un identificador de sesión para procesamiento. */
$response["data"] = array(
    "notifications" => $notificaciones,
    "notificationsUnread" => $notificacionesUnread,
    "subid" => "7040" . $json->session->sid . "5",
);

if (false) {


    /* Crea un objeto UsuarioMandante y obtiene mensajes de usuario según reglas JSON definidas. */
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "NOTIFICACION","op":"eq"}] ,"groupOp" : "AND"}';
    $usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);

    $usuarioMensajes = json_decode($usuarioMensajes);

    /* procesa mensajes de usuario para crear notificaciones organizadas en un array. */
    $notificacion_nuevas = intval($usuarioMensajes->count[0]->{".count"});


    $notificaciones = array();
    foreach ($usuarioMensajes->data as $key => $value) {

        $array = [];
        $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
        $array["content"] = $value->{"usuario_mensaje.body"};
        $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
        $array["date"] = strtotime($value->{"usuario_mensaje.fecha_crea"});
        $array["title"] = $value->{"usuario_mensaje.msubject"};
        $array["action"] = "user.balanceHistory";

        array_push($notificaciones, $array);

    }


    /* inicializa un array y asigna valores a sus claves. */
    $response = array();


    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;

    /* estructura una respuesta JSON con notificaciones y un identificador único. */
    $response["data"] = array(
        "notifications" => $notificaciones,
        "subid" => "7040" . $json->session->sid . "5",
    );
}
