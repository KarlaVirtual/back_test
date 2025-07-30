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
 * command/set_client_self_exclusion
 *
 * Gestiona la exclusión de un usuario según el tipo de exclusión solicitado, estableciendo el estado correspondiente.
 *
 * @param int $exc_type : Tipo de exclusión a aplicar (6 para "EXCTIMEOUT", 2 para "EXCTOTAL").
 * @param int $to_date : Fecha hasta la cual la exclusión estará activa.
 *
 * @return objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de estado de la operación.
 *  - *tipo* (string): Tipo de exclusión aplicado.
 *  - *data* (array): Contiene el resultado del proceso de exclusión.
 *    - *result* (int): Código interno del resultado (0 para éxito, 1 si no se pudo aplicar la exclusión).
 *    - *result_text* (string|null): Mensaje adicional sobre el resultado.
 *    - *data* (array): Datos adicionales de la operación (vacío en este caso).
 *
 *
 * Objeto en caso de error:
 *
 * "code" => 0,
 * "tipo" => "[Tipo de exclusión intentado]",
 * "data" => [
 *     "result" => 1,
 *     "result_text" => "Error occured",
 *     "data" => []
 * ]
 *
 * @throws Exception Si ocurre un error al procesar la exclusión o actualizar los datos del usuario.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene datos de un objeto JSON y crea un objeto UsuarioMandante. */
$exc_type = $json->params->exc_type;
$to_date = $json->params->to_date;


$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ClientId = $UsuarioMandante->getUsuarioMandante();


/* asigna un tipo basado en el valor de $exc_type. */
$tipo = "";

switch ($exc_type) {
    case 6:
        $tipo = "EXCTIMEOUT";

        break;

    case 2:
        $tipo = "EXCTOTAL";

        break;

}

if ($tipo != "") {


    /* verifica si un usuario tiene saldo positivo antes de continuar. */
    $seguir = true;
    if ($tipo == "EXCTOTAL") {

        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        if ($Usuario->getBalance() > 0) {
            $seguir = false;

        }
    }


    /* asigna la fecha y hora límite al formato 'Y-m-d 23:59:59'. */
    $valor = date('Y-m-d 23:59:59', $to_date);

    if ($seguir) {


        /* intenta actualizar la configuración del usuario y gestiona excepciones. */
        try {

            $Tipo = new Clasificador("", $tipo);
            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $Tipo->getClasificadorId());

            //$UsuarioConfiguracion->setValor($valor);
            $UsuarioConfiguracion->setEstado("I");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

            throw new Exception('', 46);
        } catch (Exception $e) {
            /* Manejo de excepciones para insertar configuración de usuario en caso de un error específico. */

            if ($e->getCode() == 46) {

                $UsuarioConfiguracion2 = new UsuarioConfiguracion();
                $UsuarioConfiguracion2->setUsuarioId($ClientId);
                $UsuarioConfiguracion2->setTipo($Tipo->getClasificadorId());
                $UsuarioConfiguracion2->setValor($valor);
                $UsuarioConfiguracion2->setUsucreaId("0");
                $UsuarioConfiguracion2->setUsumodifId("0");
                $UsuarioConfiguracion2->setEstado("A");
                $UsuarioConfiguracion2->setProductoId("0");

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

            } else {
                throw new Exception($e->getMessage(), $e->getCode());
            }
        }

        /* Crea una respuesta estructurada en formato de array con información inicial. */
        $response = array(
            "code" => 0,
            "tipo" => $tipo,
            "data" => array(
                "result" => 0,
                "result_text" => null,
                "data" => array(),
            ),
        );
    } else {
        /* genera una respuesta de error con información específica. */

        $response = array(
            "code" => 0,
            "tipo" => $tipo,
            "data" => array(
                "result" => 1,
                "result_text" => 'Error occured',
                "data" => array(),
            ),
        );
    }

} else {
    /* maneja una respuesta de error en formato de array asociativo. */

    $response = array(
        "code" => 0,
        "tipo" => $tipo,
        "data" => array(
            "result" => 0,
            "result_text" => 'Error occured',
            "data" => array(),
        ),
    );
}


