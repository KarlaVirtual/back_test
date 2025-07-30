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
use Mpdf\Tag\Em;

/**
 * command/auto_exclusion
 *
 * Gestiona la exclusión automática de productos para un usuario, estableciendo una fecha de expiración basada en un valor predefinido.
 *
 * @param object $product : Objeto que contiene la información del producto a excluir.
 * @param int $to_date : Índice que define la fecha de exclusión predefinida.
 * @param string $note : Nota adicional sobre la exclusión.
 *
 * @return objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de estado de la operación.
 *  - *data* (array): Contiene el resultado del proceso de exclusión.
 *    - *ress* (int): Código interno del resultado.
 *    - *prod* (string): ID del producto afectado.
 *    - *usu* (object): Objeto con la configuración del usuario.
 *    - *result* (int): Código interno de la operación (0 para éxito).
 *    - *result_text* (string|null): Mensaje adicional sobre el resultado.
 *    - *data* (array): Datos adicionales de la operación (vacío en este caso).
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
 * getDateExclusion
 *
 * Genera una fecha futura basada en el valor proporcionado, estableciendo exclusiones de tiempo predefinidas.
 *
 * @param int $value Índice que determina el período de exclusión (1 = 1 día, 2 = 1 semana, 3 = 1 mes, etc.).
 *
 * @return string $response Fecha futura en formato 'Y-m-d H:i:s' según el valor ingresado. Devuelve una cadena vacía si el índice es inválido.
 *
 * @throws Exception No aplica
 * @access public
 * @see no
 * @since no
 */

function getDateExclusion($value)
{
    $currentDate = date('Y-m-d H:i:s');
    $dates = [
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 1 day')),
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 1 week')),
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 1 month')),
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 3 month')),
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 6 month')),
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 1 year')),
        date('Y-m-d H:i:s', strtotime($currentDate . '+ 100 year')),
    ];
    return isset($dates[$value - 1]) ? $dates[$value - 1] : '';
}

/**
 * autoExclusion
 *
 * Gestiona la exclusión automática de un usuario sobre un producto, verificando y actualizando su configuración.
 *
 * @param string $tipo Tipo de exclusión a aplicar.
 * @param int $ClientId Identificador del cliente.
 * @param int $producto_id Identificador del producto asociado.
 * @param string $note Nota adicional sobre la exclusión.
 * @param mixed $valor Valor asociado a la configuración de exclusión.
 * @param object $UsuarioMandante Objeto que representa al usuario que gestiona la exclusión.
 *
 * @return array $response Arreglo con la instancia de `UsuarioConfiguracion` y el código de resultado (`2`, `3` o `4`).
 *
 *
 * @throws Exception Si ocurre un error general o una excepción específica en la base de datos.
 *
 * @access public
 * @see no
 * @since no
 */

function autoExclusion($tipo, $ClientId, $producto_id, $note, $valor, $UsuarioMandante)
{
    try {
        $Tipo = new Clasificador("", $tipo);

        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $Tipo->getClasificadorId(), $producto_id);

        if (!empty($UsuarioConfiguracion->usuconfigId)) throw new Exception('Error general', 300001);

        $UsuarioConfiguracion->setNota($note);
        $UsuarioConfiguracion->setValor($valor);

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        $ress = 2;
    } catch (Exception $e) {
        /*Manejo de excepciones*/

        if ($e->getCode() == 46) {
            $UsuarioConfiguracion2 = new UsuarioConfiguracion();
            $UsuarioConfiguracion2->setUsuarioId($ClientId);
            $UsuarioConfiguracion2->setTipo($Tipo->getClasificadorId());
            $UsuarioConfiguracion2->setNota($note);
            $UsuarioConfiguracion2->setValor($valor);
            $UsuarioConfiguracion2->setUsucreaId($UsuarioMandante->usuarioMandante);
            $UsuarioConfiguracion2->setUsumodifId("0");
            $UsuarioConfiguracion2->setProductoId($producto_id);
            $UsuarioConfiguracion2->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


            $ress = 3;
        } else {
            $ress = 4;

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
    return [$UsuarioConfiguracion, $ress];
}

$product = $json->params->product;
$to_date = $json->params->to_date;
$note = $json->params->Note;

/*Obtención información del usuario solicitante*/
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$ClientId = $UsuarioMandante->getUsuarioMandante();
$producto_id = "";

if (isset($product->children) && count($product->children) > 0) {
    $producto_id = $product->children->Id;
} else {
    /*obtención alternativa del productoId*/

    $producto_id = $product->Id;
}


/* Definición clasificación requerido*/
$tipo = "EXCPRODUCT";


$ress = 1;

if ($tipo != "" && is_numeric($producto_id)) {

    if ($producto_id == 0) {
        $producto_id = '0';
    }
    $ress = 11;


    $valor = getDateExclusion($to_date);
    if ($producto_id == 6) {
        // Iterar desde 0 hasta 3
        for ($i = 0; $i < 4; $i++) {
            $producto_id = $i;
            list($UsuarioConfiguracion, $ress) = autoExclusion($tipo, $ClientId, $producto_id, $note, $valor, $UsuarioMandante);
        }
    } else {
        list($UsuarioConfiguracion, $ress) = autoExclusion($tipo, $ClientId, $producto_id, $note, $valor, $UsuarioMandante);
    }

}


/* Generación objeto de respuesta*/
$response = array(
    "code" => 0,
    "data" => array(
        "ress" => $ress,
        "prod" => $producto_id,
        "usu" => $UsuarioConfiguracion,
        "result" => 0,
        "result_text" => null,
        "data" => array(),
    ),
);


