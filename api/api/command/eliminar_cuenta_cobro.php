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
 *Realiza la eliminación de un registro CuentaCobro
 *@param int $json->params->cuenta_id
 *@param string $json->params->observacion
 *@param int $json->session->usuario
 *
 * @return array
 *  - code (int) Código de respuesta
 *  - data (array) Arreglo con los datos de la respuesta
 */

// Se obtiene el ID de la cuenta y la observación del objeto JSON recibido.
$cuenta_id = $json->params->cuenta_id;
$observacion = $json->params->observacion;

// Verifica si el ID de la cuenta no está vacío o indefinido.
if ($cuenta_id != "" && $cuenta_id != undefined && $cuenta_id != "undefined") {
    $CuentaCobro = new \Backend\dto\CuentaCobro($cuenta_id);
}

// Verifica si la instancia de CuentaCobro ha sido creada.
if ($CuentaCobro != null) {
    // Verifica el estado de la cuenta de cobro.
    if ($CuentaCobro->getEstado() == "I") {
        throw new Exception("No se encontro la cuenta de cobro", "12");
    }

    // Se crea una nueva instancia de Usuario usando el ID del usuario de la cuenta de cobro.
    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

    // Se crea una nueva instancia de UsuarioMandante usando el usuario de la sesión.
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    // Se crea una nueva instancia de CuentaCobroEliminada.
    $CuentaCobroEliminar = new \Backend\dto\CuentaCobroEliminada();
    $CuentaCobroEliminar->setCuentaId($CuentaCobro->getCuentaId());
    $CuentaCobroEliminar->setValor($CuentaCobro->getValor());
    $CuentaCobroEliminar->setMandante($CuentaCobro->getMandante());
    $CuentaCobroEliminar->setObserv($observacion);
    $CuentaCobroEliminar->setUsuarioId($CuentaCobro->getUsuarioId());
    $CuentaCobroEliminar->setUsucreaId($UsuarioMandante->getUsuarioMandante());
    $CuentaCobroEliminar->setFechaCuenta($CuentaCobro->getFechaCrea());
    $CuentaCobroEliminar->setFechaCrea(date('Y-m-d H:i:s'));

    // Se crea una nueva instancia de CuentaCobroEliminadaMySqlDAO para manejar la persistencia.
    $CuentaCobroEliminadaMySqlDAO = new \Backend\mysql\CuentaCobroEliminadaMySqlDAO();

    // Se inserta la cuenta de cobro eliminada en la base de datos.
    $CuentaCobroEliminadaMySqlDAO->insert($CuentaCobroEliminar);

    $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroEliminadaMySqlDAO->getTransaction());

    $CuentaCobroMySqlDAO = new \Backend\mysql\CuentaCobroMySqlDAO($CuentaCobroEliminadaMySqlDAO->getTransaction());

    // Se elimina la cuenta de cobro de la base de datos.
    $CuentaCobroMySqlDAO->delete($CuentaCobro->getCuentaId());

    $CuentaCobroEliminadaMySqlDAO->getTransaction()->commit();

    // Se prepara la respuesta en formato de arreglo.
    $response = array(
        "code" => 0,
        "data" => array(),
    );
} else {
    // Lanza una excepción si no se encontró la cuenta de cobro.
    throw new Exception("No se encontro la cuenta de cobro", "12");
}