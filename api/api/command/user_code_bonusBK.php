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
use Backend\dto\UsuarioBono;
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
use Backend\mysql\UsuarioBonoMySqlDAO;
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
 * command/user_code_bonusBK
 *
 * Validación y redención de código de bono
 *
 * Este recurso verifica la existencia y el estado del código de bono ingresado por el usuario.
 * Si el bono es válido y está disponible, se procede con su redención según las condiciones establecidas.
 * La validación considera los detalles del bono, incluyendo restricciones y requisitos de liberación.
 * También se registran intentos repetidos de redención para su monitoreo.
 *
 * @param string $bonuscode : Código del bono ingresado por el usuario.
 * @param string $usuario : Identificador del usuario en sesión.
 *
 * @return object $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error o éxito según el resultado de la validación.
 *  - *rid* (string): Identificador único de la transacción o usuario.
 *  - *data* (array): Contiene el resultado de la validación o redención del bono.
 *
 * Objeto en caso de error:
 *
 * "code" => 200,
 * "rid" => [RID del usuario],
 * "data" => [
 *      "reason" => "Bono ya redimido." | "Bono no existe."
 *  ],
 *
 * @throws Exception "El código de bono ingresado es incorrecto" (30008) si el código no es válido.
 * @throws Exception "Bono ya redimido" (200) si el usuario ya ha usado este bono.
 * @throws Exception "Bono no existe" (200) si el código ingresado no se encuentra en la base de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene un bono y crea objetos de usuario basados en la sesión. */
$bonuscode = $json->params->bonuscode;

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100;
}


/* Inicializa arreglos y crea una consulta JSON para buscar un código de bono. */
$mensajesEnviados = [];
$mensajesRecibidos = [];


$json2 = '{"rules" : [{"field" : "usuario_bono.codigo", "data": "' . $bonuscode . '","op":"eq"}] ,"groupOp" : "AND"}';

$UsuarioBono = new UsuarioBono();

/* Se obtienen y decodifican datos de bonos para el usuario, inicializando variable de existencia. */
$UsuarioBonos = $UsuarioBono->getUsuarioBonosCustom(" usuario_bono.*,bono_interno.* ", "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json2, true);

$UsuarioBonos = json_decode($UsuarioBonos);


$existeBono = false;

/* Variable booleana que indica si un bono ha sido redimido o no. */
$bonoRedimido = false;


foreach ($UsuarioBonos->data as $key => $value) {

    if ($value->{'usuario_bono.estado'} == "L") {

        /* Se crea un objeto `UsuarioBono` y se asigna un ID de usuario al mismo. */
        $UsuarioBono = new $UsuarioBono($value->{'usuario_bono.usubono_id'});
        $UsuarioBono->usuarioId = $UsuarioMandante->getUsuarioMandante();


        if ($value->{'bono_interno.tipo'} == 3) {


            /* Código que inicializa variables y define reglas para un bono con array. */
            $rollower = 0;
            $valorbono = 0;

            $rules = [];

            array_push($rules, array("field" => "bono_detalle.bono_id", "data" => $value->{'bono_interno.bono_id'}, "op" => "eq"));


            /* inicializa un filtro y gestiona valores predeterminados para variables. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* establece un máximo de filas y convierte un filtro a JSON. */
            if ($MaxRows == "") {
                $MaxRows = 100;
            }

            $json2 = json_encode($filtro);

            $BonoDetalle = new BonoDetalle();


            /* procesa detalles de bonos, filtrando y asignando valores según condiciones específicas. */
            $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json2, TRUE);

            $bonodetalles = json_decode($bonodetalles);

            $final = [];


            foreach ($bonodetalles->data as $key => $value) {
                if ($value->{"bono_detalle.tipo"} == "WFACTORBONO") {
                    $rollower = $value->{"bono_detalle.valor"};

                }
                if ($value->{"bono_detalle.tipo"} == "MAXPAGO") {
                    if ($value->{"bono_detalle.moneda"} == $Usuario->moneda) {
                        $valorbono = $value->{"bono_detalle.valor"};
                    }

                }

            }


            /* Asigna el estado 'A' a UsuarioBono si $rollower es igual a 0. */
            if ($rollower == 0) {

                $UsuarioBono->estado = 'A';


            } else {
                /* Se asignan valores a propiedades de un objeto UsuarioBono basado en condiciones específicas. */

                $UsuarioBono->rollowerRequerido = $rollower * $valorbono;
                $UsuarioBono->valor = $valorbono;
                $UsuarioBono->valorPromocional = $valorbono;
                $UsuarioBono->valorBase = $valorbono;
                $UsuarioBono->estado = 'P';

            }
        } else {
            /* Asigna el estado 'P' al objeto $UsuarioBono en caso de que no se cumpla una condición. */

            $UsuarioBono->estado = 'P';

        }


        /* Actualiza un registro y confirma la transacción en la base de datos MySQL. */
        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
        $UsuarioBonoMySqlDAO->update($PromocionalLog);
        $UsuarioBonoMySqlDAO->getTransaction()->commit();
        //$UsuarioBono->verifyRollower();

        $existeBono = true;
    } else {
        /* establece una variable como verdadera si no se cumple una condición anterior. */

        $bonoRedimido = true;
    }


}


/* verifica si existe un bono y prepara una respuesta en formato JSON. */
if ($existeBono) {
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => true
    );
} else {
    /* Manejo de respuestas para bonos redimidos y no existentes en formato JSON. */

    if ($bonoRedimido) {
        $response = array();
        $response["code"] = 200;
        $response["rid"] = $json->rid;
        $response["data"] = array(
            "reason" => "Bono ya redimido."
        );
    } else {
        $response = array();
        $response["code"] = 200;
        $response["rid"] = $json->rid;
        $response["data"] = array(
            "reason" => "Bono no existe."
        );
    }

}
