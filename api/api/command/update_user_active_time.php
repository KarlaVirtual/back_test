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
 * command/update_user_active_time
 *
 * Actualizar usuario tiempo activo
 *
 * @param string $active_time : tiempo activo en plataforma
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor en caso de exito 0
 *  - *data* (array): Contiene el mensaje de aprobación del proceso.
 *
 * @throws Exception Si ya existe se genera un insert para actualizar el tiempo activo nuevo
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Código que obtiene el tiempo activo y usuario desde un objeto JSON. */
$active_time = $json->params->active_time;

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ClientId = $UsuarioMandante->getUsuarioMandante();

$tipo = "EXCTIME";


if ($active_time != "") {

    /* Actualiza la configuración de usuario y confirma la transacción en la base de datos. */
    $valor = $active_time;


    try {
        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $tipo);

        $UsuarioConfiguracion->setValor($valor);

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

    } catch (Exception $e) {
        /* Maneja excepciones, inserta configuración de usuario si código es 30, de lo contrario lanza error. */

        if ($e->getCode() == 30) {

            $UsuarioConfiguracion2 = new UsuarioConfiguracion();
            $UsuarioConfiguracion2->setUsuarioId($ClientId);
            $UsuarioConfiguracion2->setTipo($tipo);
            $UsuarioConfiguracion2->setValor($valor);
            $UsuarioConfiguracion2->setUsucreaId("0");
            $UsuarioConfiguracion2->setUsumodifId("0");
            $UsuarioConfiguracion2->setProductoId(0);
            $UsuarioConfiguracion2->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        } else {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}


/* Estructura de respuesta en formato array, indicando código y datos vacíos. */
$response = array(
    "code" => 0,
    "data" => array(
        "result" => 0,
        "result_text" => null,
        "data" => array(),
    ),
);


