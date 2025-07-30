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

/** Genera un proceso de débito para el proceso de livecasino requerido mediante los parámetros entregados
 *
 * @param int $params->token Token de autenticación
 * @param int $params->amount  Valor a debitar
 * @param int $params->externalId  Identificador externo
 *
 * @return array Respuesta de la operación
 *  -code: int Código de respuesta
 *  -rid: string Identificador de la solicitud
 *  -data: array Respuesta de la operación
 */

// Obtiene los parámetros del objeto JSON
$params = $json->params;

// Almacena el token de autenticación
$auth_token = $params->token;

// Almacena el monto de la transacción
$amount = $params->amount;

// Crea un identificador externo combinando el externalId del parámetro y el rid del JSON
$externalId = $params->externalId . $json->rid;

// Prepara los datos para la transacción en un arreglo
$data = array(
    "amount" => $amount,
    "externalId" => $externalId,
    "token" => $auth_token
);

// Verifica si el token de autenticación está vacío
if ($auth_token == "") {
    // Lanza una excepción si el token está vacío
    throw new Exception("Token vacio", "01");
}

// Crea una nueva instancia de IES con el token de autenticación
$IES = new IES($auth_token);

// Inicializa un arreglo para la respuesta
$response = array();
$response["code"] = 0; // Código de respuesta
$response["rid"] = $json->rid; // Almacena el rid del JSON

// Realiza la operación de débito a través de la instancia de IES y almacena la respuesta
$response["data"] = $IES->Debit($amount, $externalId, json_encode($data));

