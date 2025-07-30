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
use Backend\dto\TransaccionProducto;
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
use Backend\mysql\TransaccionProductoMySqlDAO;
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
 * command/user_code_coupons
 *
 * Validación y redención de cupón
 *
 * Este recurso permite validar y redimir un código de cupón ingresado por el usuario.
 * Se verifica la existencia del cupón y su estado actual antes de proceder con su redención.
 * Si el cupón es válido y está pendiente de uso, se actualiza su estado y se registra la transacción correspondiente.
 *
 * @param int $count : Número máximo de registros a procesar.
 * @param int $start : Número de registros a omitir antes de iniciar la consulta.
 * @param string $couponscode : Código encriptado del cupón ingresado por el usuario.
 * @param int $site_id : Identificador del sitio donde se realiza la transacción.
 *
 * @return object $response es un array con los siguientes atributos:
 *  - *code* (int): Código de éxito o error según el resultado de la validación.
 *  - *data* (array): Contiene el resultado de la validación o redención del cupón.
 *
 * Objeto en caso de error:
 *
 * "code" => 1,
 * "data" => [
 *      "result" => "Codigo no existe"
 *  ],
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* procesa y decodifica un código de cupón y establece variables. */
$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;
$couponscode = $json->params->couponscode;
$ConfigurationEnvironment = new ConfigurationEnvironment();
$couponscode = $ConfigurationEnvironment->decryptCusNum($couponscode);
$couponscode = explode("_", $couponscode)[0];

/* Asigna el valor de site_id desde un objeto JSON a una variable PHP. */
$site_id = $json->params->site_id;

if (is_numeric($couponscode)) {


    /* gestiona una transacción de producto y actualiza su estado en la base de datos. */
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);


    $TransaccionProducto = new TransaccionProducto($couponscode);

    if ($TransaccionProducto != "" && $TransaccionProducto->getEstadoProducto() == "P") {

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $t_value = '{}';
        $TransaccionProducto->setUsuarioId($UsuarioMandante->usuarioMandante);
        $TransaccionProducto->setEstado("A");
        $TransaccionProducto->setEstadoProducto("E");
        $TransaccionProductoMySqlDAO->update($TransaccionProducto);
        $Transaction->commit();

        $TransaccionProducto->setAprobada($couponscode, "A", "A", "Cupon Redimido", $t_value, $couponscode);

        // COMMIT de la transacción

        $response = array();
        $response["code"] = 0;
        $response["data"] = array();
    } else {
        /* genera una respuesta indicando que el código no existe. */


        $response = array();
        $response["code"] = 1;
        $response["data"] = array(
            "result" => "Codigo no existe",

        );
    }

} else {
    /* genera una respuesta indicando que el código no existe. */


    $response = array();
    $response["code"] = 1;
    $response["data"] = array(
        "result" => "Codigo no existe",

    );
}









