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
 * Inactiva una entidad UsuarioBanco solicitada
 * @param int $json->params[0]->userbank_id ID del usuarioBanco
 * @return array
 *  - code int Código de respuesta
 *  - rid string ID de la petición
 *  - data int ID usuarioBanco
 */

// Obtiene el id del banco de usuario desde los parámetros JSON.
$userbank_id = $json->params[0]->userbank_id;

// Verifica si el userbank_id no está vacío y es numérico.
if ($userbank_id != "" && is_numeric($userbank_id)) {

    // Crea una instancia de la clase UsuarioBanco.
    $UsuarioBanco = new UsuarioBanco($userbank_id);

    // Inicializa un arreglo de reglas.
    $rules = [];

    // Agrega reglas para filtrar las cuentas de cobro.
    array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "$userbank_id", "op" => "eq"));
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    // Crea una instancia de la clase CuentaCobro.
    $CuentaCobro = new CuentaCobro();
    $cuentas = $CuentaCobro->getCuentasCobroCustom("cuenta_cobro.cuenta_id,cuenta_cobro.usuario_id,cuenta_cobro.usucambio_id,cuenta_cobro.observacion,cuenta_cobro.mensaje_usuario,usuario.login,cuenta_cobro.fecha_crea,cuenta_cobro.valor,cuenta_cobro.mediopago_id,usuario.moneda,cuenta_cobro.puntoventa_id,punto_venta.descripcion puntoventa,cuenta_cobro.mediopago_id, banco.descripcion banco_nombre,cuenta_cobro.estado,usuario_banco.cuenta", "cuenta_cobro.cuenta_id", "asc", 0, 1, $json2, true, "cuenta_cobro.cuenta_id");

    // Decodifica las cuentas obtenidas en formato JSON.
    $cuentas = json_decode($cuentas);

    // Verifica si hay cuentas de cobro pendientes.
    if (intval($cuentas->count[0]->{'.count'}) > 0) {
        // Prepara la respuesta indicando que no se puede eliminar la cuenta.
        $response = array();
        $response["code"] = 12;
        $response["error_code"] = 300181;
        $response["rid"] = $json->rid;
        $response["data"] = array(
            "result" => "You can not delete an account with a pending withdrawal."
        );
        $response["result"] = "You can not delete an account with a pending withdrawal";


    } else {
        // Cambia el estado del UsuarioBanco a 'Inactivo'.
        $UsuarioBanco->setEstado('I');

        // Crea una instancia del DAO para UsuarioBanco.
        $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

        // Actualiza el estado del UsuarioBanco en la base de datos.
        $UsuarioBancoMySqlDAO->update($UsuarioBanco);
        $UsuarioBancoMySqlDAO->getTransaction()->commit();

        // Prepara la respuesta indicando que la operación fue exitosa.
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;
        $response["data"] = $userbank_id;

    }

}
