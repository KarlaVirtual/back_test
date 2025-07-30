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
use Backend\dto\LealtadHistorial;
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
 * Calcula y devuelve los niveles de lealtad de un usuario.
 *
 * @param object $json Objeto JSON que contiene los datos de la sesión y parámetros de entrada.
 *  - session: Objeto que contiene la información de la sesión del usuario.
 *    - usuario: ID del usuario.
 *  - params: Objeto que contiene los parámetros de entrada.
 *    - site_id: ID del sitio.
 *
 * @return void Modifica el array $response con el código de respuesta y los datos de lealtad.
 *  - code: int Código de respuesta.
 *  - data: array Datos de respuesta.
 *    - loyalty: array Información de lealtad del usuario.
 *      - level: int Nivel de lealtad del usuario.
 *      - points: int Puntos actuales del usuario.
 *      - pointsNext: int Puntos faltantes para el próximo nivel.
 *      - pointsExpire: int Puntos que expiran hoy.
 *      - percentage: float Porcentaje de progreso hacia el próximo nivel.
 */

/* Se crean instancias de configuración y usuario utilizando datos de una sesión JSON. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$UsuarioMandante = new UsuarioMandante(1);
$site_id = $json->params->site_id;

$Mandante = new Mandante($site_id);


/* Se crea un objeto Usuario y se obtienen su ID y puntos de lealtad. */
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$UsuarioId = $Usuario->usuarioId;
$PuntosUsuario = $Usuario->puntosLealtad;

$MandanteDetalle = new MandanteDetalle();
if (true) {

    /* Define reglas de filtrado para consulta, usando condiciones sobre campos específicos. */
    $rules = [];
    array_push($rules, array("field" => "mandante_detalle.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
    array_push($rules, array("field" => "mandante_detalle.estado", "data" => "A", "op" => "eq"));

//array_push($rules, array("field" => "casificador.tipo", "data" => "LDN", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y obtiene detalles de mandante. */
    $json2 = json_encode($filtro);

    $mandanteDetalles = $MandanteDetalle->getMandanteDetallesCustom("mandante_detalle.valor as valor, clasificador.abreviado as abreviado ", "mandante_detalle.manddetalle_id", "asc", '0', '1000', $json2, true);

    $mandanteDetalles = json_decode($mandanteDetalles);
    $loyalty = array();
    foreach ($mandanteDetalles->data as $key => $value) {


        switch ($value->{'clasificador.abreviado'}) {
            case "POINTSLEVELONE":
                /* Asigna el valor de 'mandante_detalle.valor' a la variable $ValorNivel1. */


                $ValorNivel1 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELTWO":
                /* Asignación de valor de nivel dos a partir de un objeto en función del caso. */

                $ValorNivel2 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELTHREE":
                /* Se asigna un valor específico a la variable según el caso "POINTSLEVELTHREE". */

                $ValorNivel3 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELFOUR":
                /* Asignación del valor de 'mandante_detalle.valor' a $ValorNivel4 para el caso específico. */

                $ValorNivel4 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELFIVE":
                /* asigna un valor específico a una variable basada en un caso. */

                $ValorNivel5 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELSIX":
                /* Asignación del valor de "mandante_detalle.valor" a $ValorNivel6, según el caso definido. */

                $ValorNivel6 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELSEVEN":
                /* Asignación del valor para el nivel siete desde un objeto JSON. */

                $ValorNivel7 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELEIGHT":
                /* Extrae el valor de "mandante_detalle.valor" para el caso "POINTSLEVELEIGHT". */

                $ValorNivel8 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELNIVE":
                /* Asigna valor de 'mandante_detalle.valor' a $ValorNivel9 para "POINTSLEVELNIVE". */

                $ValorNivel9 = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELTEN":
                /* Asigna el valor de "mandante_detalle.valor" a "ValorNivel10" en un caso específico. */

                $ValorNivel10 = $value->{'mandante_detalle.valor'};
                break;
        }

    }

    if (($Usuario->paisId == 173 && $Usuario->mandante == '0') || $Usuario->mandante == '8') {

//traer valores de la base de datos
        /*   $ValorNivel1 = 50;
           $ValorNivel2 = 200;
           $ValorNivel3 = 400;
           $ValorNivel4 = 800;
           $ValorNivel5 = 1500;
           $ValorNivel6 = 3000;
           $ValorNivel7 = 6000;
           $ValorNivel8 = 12000;
           $ValorNivel9 = 24000;
           $ValorNivel10 = 48000;*/


//Niveles del usuario

        /* Se asigna un nivel de lealtad según los puntos del usuario. */
        if ($PuntosUsuario < $ValorNivel1) {
            $nivelLealtad = 0;

        }
        if ($PuntosUsuario >= $ValorNivel1 && $PuntosUsuario < $ValorNivel2) {
            $nivelLealtad = 1;
        }

        /* Asignar el nivel de lealtad basado en los puntos del usuario. */
        if ($PuntosUsuario >= $ValorNivel2 && $PuntosUsuario < $ValorNivel3) {
            $nivelLealtad = 2;
        }
        if ($PuntosUsuario >= $ValorNivel3 && $PuntosUsuario < $ValorNivel4) {
            $nivelLealtad = 3;
        }

        /* asigna un nivel de lealtad basado en puntos del usuario. */
        if ($PuntosUsuario >= $ValorNivel4 && $PuntosUsuario < $ValorNivel5) {
            $nivelLealtad = 4;
        }
        if ($PuntosUsuario >= $ValorNivel5 && $PuntosUsuario < $ValorNivel6) {
            $nivelLealtad = 5;
        }

        /* determina el nivel de lealtad según puntos del usuario. */
        if ($PuntosUsuario >= $ValorNivel6 && $PuntosUsuario < $ValorNivel7) {
            $nivelLealtad = 6;
        }
        if ($PuntosUsuario >= $ValorNivel7 && $PuntosUsuario < $ValorNivel8) {
            $nivelLealtad = 7;
        }

        /* determina el nivel de lealtad según puntos del usuario. */
        if ($PuntosUsuario >= $ValorNivel8 && $PuntosUsuario < $ValorNivel9) {
            $nivelLealtad = 8;
        }
        if ($PuntosUsuario >= $ValorNivel9 && $PuntosUsuario < $ValorNivel10) {
            $nivelLealtad = 9;
        }

        /* Asigna el nivel de lealtad 10 si puntos del usuario superan el valor correspondiente. */
        if ($PuntosUsuario >= $ValorNivel10) {
            $nivelLealtad = 10;
        }

    }
    /*//Niveles del usuario
    if($PuntosUsuario < $ValorNivel1){
        $Usuario->nivelLealtad = 0;
    }
    if($PuntosUsuario >= $ValorNivel1 && $PuntosUsuario < $ValorNivel2){
        $Usuario->nivelLealtad = 1;
    }
    if($PuntosUsuario >= $ValorNivel2 && $PuntosUsuario < $ValorNivel3){
        $Usuario->nivelLealtad = 2;
    }
    if($PuntosUsuario >= $ValorNivel3 && $PuntosUsuario < $ValorNivel4){
        $Usuario->nivelLealtad = 3;
    }
    if($PuntosUsuario >= $ValorNivel4 && $PuntosUsuario < $ValorNivel5){
        $Usuario->nivelLealtad = 4;
    }
    if($PuntosUsuario >= $ValorNivel5 && $PuntosUsuario < $ValorNivel6){
        $Usuario->nivelLealtad = 5;
    }
    if($PuntosUsuario >= $ValorNivel6 && $PuntosUsuario < $ValorNivel7){
        $Usuario->nivelLealtad = 6;
    }
    if($PuntosUsuario >= $ValorNivel7 && $PuntosUsuario < $ValorNivel8){
        $Usuario->nivelLealtad = 7;
    }
    if($PuntosUsuario >= $ValorNivel8 && $PuntosUsuario < $ValorNivel9){
        $Usuario->nivelLealtad = 8;
    }
    if($PuntosUsuario >= $ValorNivel9 && $PuntosUsuario < $ValorNivel10){
        $Usuario->nivelLealtad = 9;
    }
    if($PuntosUsuario >= $ValorNivel10){
        $Usuario->nivelLealtad = 10;
    }

    $Usuario->usuarioId = $UsuarioId;
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO->update($Usuario);
    $UsuarioMySqlDAO->getTransaction()->commit();*/

//Puntos faltante para proximo nivel

    /* Calcula puntos faltantes y porcentaje para el nivel de lealtad cero. */
    if ($nivelLealtad == 0) {
        $PuntosFaltantes = $ValorNivel1 - $PuntosUsuario;
        $puntosTotal = 0;
        $porcentaje = ($ValorNivel1 - 0) == 0 ? 0 : ($PuntosUsuario - 0) / ($ValorNivel1 - 0);

    }

    /* Calcula puntos faltantes y porcentaje de avance para un usuario en nivel de lealtad 1. */
    if ($nivelLealtad == 1) {
        $PuntosFaltantes = $ValorNivel2 - $PuntosUsuario;

        $porcentaje = ($ValorNivel2 - $ValorNivel1) == 0 ? 0 : ($PuntosUsuario - $ValorNivel1) / ($ValorNivel2 - $ValorNivel1);

    }

    /* Calcula puntos faltantes y porcentaje según el nivel de lealtad del usuario. */
    if ($nivelLealtad == 2) {
        $PuntosFaltantes = $ValorNivel3 - $PuntosUsuario;
        $porcentaje = ($ValorNivel3 - $ValorNivel2) == 0 ? 0 : ($PuntosUsuario - $ValorNivel2) / ($ValorNivel3 - $ValorNivel2);
    }
    if ($nivelLealtad == 3) {
        $PuntosFaltantes = $ValorNivel4 - $PuntosUsuario;

        $porcentaje = ($ValorNivel4 - $ValorNivel3) == 0 ? 0 : ($PuntosUsuario - $ValorNivel3) / ($ValorNivel4 - $ValorNivel3);
    }

    /* Calcula puntos faltantes y porcentaje de avance en niveles de lealtad. */
    if ($nivelLealtad == 4) {
        $PuntosFaltantes = $ValorNivel5 - $PuntosUsuario;

        $porcentaje = ($ValorNivel5 - $ValorNivel4) == 0 ? 0 : ($PuntosUsuario - $ValorNivel4) / ($ValorNivel5 - $ValorNivel4);
    }
    if ($nivelLealtad == 5) {
        $PuntosFaltantes = $ValorNivel6 - $PuntosUsuario;

        $porcentaje = ($ValorNivel6 - $ValorNivel5) == 0 ? 0 : ($PuntosUsuario - $ValorNivel5) / ($ValorNivel6 - $ValorNivel5);
    }

    /* Calcula puntos faltantes y porcentaje de progreso en niveles de lealtad. */
    if ($nivelLealtad == 6) {
        $PuntosFaltantes = $ValorNivel7 - $PuntosUsuario;
        $porcentaje = ($ValorNivel7 - $ValorNivel6) == 0 ? 0 : ($PuntosUsuario - $ValorNivel6) / ($ValorNivel7 - $ValorNivel6);
    }
    if ($nivelLealtad == 7) {
        $PuntosFaltantes = $ValorNivel8 - $PuntosUsuario;
        $porcentaje = ($ValorNivel8 - $ValorNivel7) == 0 ? 0 : ($PuntosUsuario - $ValorNivel7) / ($ValorNivel8 - $ValorNivel7);
    }

    /* Calcula puntos faltantes y porcentaje de progreso para niveles de lealtad específicos. */
    if ($nivelLealtad == 8) {
        $PuntosFaltantes = $ValorNivel9 - $PuntosUsuario;
        $porcentaje = ($ValorNivel9 - $ValorNivel8) == 0 ? 0 : ($PuntosUsuario - $ValorNivel8) / ($ValorNivel9 - $ValorNivel8);
    }
    if ($nivelLealtad == 9) {
        $PuntosFaltantes = $ValorNivel10 - $PuntosUsuario;
        $porcentaje = ($ValorNivel10 - $ValorNivel9) == 0 ? 0 : ($PuntosUsuario - $ValorNivel9) / ($ValorNivel10 - $ValorNivel9);
    }

    /* Calcula porcentaje de puntos de usuario si el nivel de lealtad es 10. */
    if ($nivelLealtad == 10) {
        $PuntosFaltantes = 0;
        $porcentaje = ($PuntosFaltantes - $ValorNivel10) == 0 ? 0 : ($PuntosUsuario - $ValorNivel10) / ($PuntosFaltantes - $ValorNivel10);
    }


    $SkeepRows = 0;

    /* Código inicializa variables y objetos para gestionar detalles de un mandante y clasificador. */
    $OrderedItem = 1;
    $MaxRows = 10;
    $rules = [];

    $Clasificador = new Clasificador("", "LOYALTYEXPIRATIONDATE");

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');

    /* Se definen reglas de filtrado para buscar registros de lealtad del usuario. */
    $diasExpiracion = $MandanteDetalle->valor;

    array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "lealtad_historial.fecha_exp", "data" => date("Y-m-d", strtotime("-" . $diasExpiracion . " days")), "op" => "bw"));
    array_push($rules, array("field" => "lealtad_historial.movimiento", "data" => 'E', "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* obtiene y decodifica puntos expiran hoy de un historial de lealtad. */
    $json2 = json_encode($filtro);
    $LealtadHistorial = new LealtadHistorial();
    $lealtadhistorico = $LealtadHistorial->getLealtadHistorialCustom("SUM(lealtad_historial.valor) as expirePoints ", 'lealtad_historial.usuario_id', "desc", $SkeepRows, $MaxRows, $json2, true, 'lealtad_historial.usuario_id');
    $lealtadhistorico = json_decode($lealtadhistorico);

    if ($lealtadhistorico != "") {

        $puntosExpiranHoy = intval($lealtadhistorico->data[0]->{".expirePoints"});

    } else {
        /* Establece que los puntos que expiran hoy son cero si no se cumplen condiciones previas. */

        $puntosExpiranHoy = 0;
    }


    /* Se crea un array asociativo con información sobre niveles y puntos del usuario. */
    $array = array();

    $array['level'] = intval($nivelLealtad);
    $array['points'] = intval($PuntosUsuario);
    $array['pointsNext'] = intval($PuntosFaltantes);
    $array['pointsExpire'] = intval($puntosExpiranHoy);

    /* guarda un porcentaje en un array y lo prepara para respuesta. */
    $array['percentage'] = floatval($porcentaje);
    array_push($loyalty, $array);
    $response = array();
    $response["code"] = 0;
    $response["data"] = array(
        "loyalty" => $loyalty
    );


} else {
    /* inicializa un array de lealtad con valores predeterminados y establece una respuesta. */

    $loyalty = array();
    $array = array();

    $array['level'] = intval(0);
    $array['points'] = intval(0);
    $array['pointsNext'] = intval(0);
    $array['pointsExpire'] = intval(0);
    $array['percentage'] = floatval(0);
    array_push($loyalty, $array);
    $response = array();
    $response["code"] = 0;
    $response["data"] = array(
        "loyalty" => $loyalty
    );

}