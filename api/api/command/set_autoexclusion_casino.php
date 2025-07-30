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
 * command/set_autoexclusion_casino
 *
 * Actualiza o inserta la configuración de un usuario en función de la categoría o subcategoría seleccionada, con una fecha de expiración determinada.
 *
 * @param object $category : Objeto que contiene la información de la categoría o subcategoría a configurar.
 * @param int $to_date : Fecha límite de la configuración en formato timestamp.
 *
 * @return objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de estado de la operación.
 *  - *data* (array): Contiene el resultado del proceso de actualización.
 *  - *result* (int): Código interno de la operación (0 para éxito).
 *  - *result_text* (string|null): Mensaje adicional sobre el resultado.
 *  - *data* (array): Datos adicionales de la operación (vacío en este caso).
 *
 *
 * @throws Exception Si ocurre un error al actualizar o insertar la configuración del usuario.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se extraen datos JSON y se crea un objeto UsuarioMandante con el usuario actual. */
$category = $json->params->category;
$to_date = $json->params->to_date;


$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ClientId = $UsuarioMandante->getUsuarioMandante();


/* Asigna ID y tipo si existen subcategorías en la categoría dada. */
$producto_id = "";
$tipo = "";

if (oldCount($category->Subcategories) > 0) {
    $producto_id = $category->Subcategories->Id;
    $tipo = "EXCCASINOSUBCATEGORY";

} else {
    /* Asignación de ID y tipo cuando no se cumple la condición en un bloque if. */

    $producto_id = $category->Id;
    $tipo = "EXCCASINOCATEGORY";

}


if ($tipo != "" && $producto_id != "") {


    /* Actualiza la configuración de usuario en una base de datos con una fecha específica. */
    $valor = date('Y-m-d 23:59:59', $to_date);

    try {

        $Tipo = new Clasificador("", $tipo);
        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, "A", $Tipo->getClasificadorId(), $producto_id);

        $UsuarioConfiguracion->setValor($valor);

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

    } catch (Exception $e) {
        /* Maneja excepciones, crea un objeto y lo inserta en la base de datos. */

        if ($e->getCode() == 46) {

            $UsuarioConfiguracion2 = new UsuarioConfiguracion();
            $UsuarioConfiguracion2->setUsuarioId($ClientId);
            $UsuarioConfiguracion2->setTipo($Tipo->getClasificadorId());
            $UsuarioConfiguracion2->setValor($valor);
            $UsuarioConfiguracion2->setUsucreaId("0");
            $UsuarioConfiguracion2->setUsumodifId("0");
            $UsuarioConfiguracion2->setProductoId($producto_id);
            $UsuarioConfiguracion2->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion2);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        } else {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}


/* crea un arreglo de respuesta estructurado en PHP. */
$response = array(
    "code" => 0,
    "data" => array(
        "result" => 0,
        "result_text" => null,
        "data" => array(),
    ),
);
