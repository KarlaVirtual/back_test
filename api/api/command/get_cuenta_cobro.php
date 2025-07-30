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
 * Recurso que obtiene la cuenta de cobro de un usuario.
 *
 * @param int $json->params->cuenta_id ID de la cuenta de cobro.
 *
 * @return array Respuesta en formato JSON:
 * - code (int) Código de respuesta.
 * - data (array) Datos de la cuenta de cobro:
 *   - cuenta_id (int) ID de la cuenta de cobro.
 *   - fecha_creacion (string) Fecha de creación de la cuenta de cobro.
 *   - nombre (string) Nombre del usuario asociado a la cuenta de cobro.
 *   - valor (float) Valor de la cuenta de cobro.
 *
 * @throw Excepción 12 - No se encontró la cuenta cobro
 */

// Se obtiene el ID de la cuenta del objeto JSON recibido
$cuenta_id = $json->params->cuenta_id;

// Se verifica que el ID de la cuenta no esté vacío, no sea indefinido y no sea la cadena "undefined"
if ($cuenta_id != "" && $cuenta_id != undefined && $cuenta_id != "undefined") {
    $CuentaCobro = new \Backend\dto\CuentaCobro($cuenta_id);
}

// Se verifica que la instancia de CuentaCobro no sea nula
if ($CuentaCobro != null) {

    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

    // Se prepara la respuesta con el código y los datos de la cuenta de cobro y del usuario
    $response = array(
        "code" => 0,
        "data" => array(
            "cuenta_id" => $CuentaCobro->getCuentaId(),
            "fecha_creacion" => $CuentaCobro->getFechaCrea(),
            "nombre" => $Usuario->nombre,
            "valor" => $CuentaCobro->getValor(),
        ),
    );
} else {
    // Se lanza una excepción si no se encuentra la cuenta de cobro
    throw new Exception("No se encontro la cuenta de cobro", "12");
}