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
 * Implementa una acreditación en casino respecto a la data obtenida
 *
 * @param string $params->token Token de autenticación
 * @param string $params->amount Cantidad a acreditar
 * @param string $params->externalId ID externo
 * @param string $params->rid ID de la solicitud
 *
 * @return array
 *  - code: Código de respuesta
 *  - rid: ID de la solicitud
 *  - data: Datos de la respuesta
 */
// Registra un mensaje en el sistema de registros con el nivel de prioridad 10 y una representación en JSON de los parámetros
syslog(10,'MACHINECCREDIT ' . (json_encode(
        $params
    )));



/*El código seleccionado obtiene los parámetros del objeto JSON, extrae el token de autenticación, la cantidad y el ID externo, y luego los agrupa en un arreglo $data.*/
$params = $json->params;

$auth_token = $params->token;

$amount = $params->amount;
$externalId = $params->externalId . $json->rid;

$data = array(
    "amount" => $amount,
    "externalId" => $externalId,
    "token" => $auth_token
);

// Verifica si el token de autenticación está vacío y lanza una excepción en caso afirmativo.
if ($auth_token == "") {

    throw new Exception("Token vacio", "01");

}

$IES = new IES($auth_token);

/**
 * @var array $response Arreglo que contendrá la respuesta con código, ID y datos.
 */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;

$response["data"] = $IES->Credit($amount, $externalId, json_encode($data));

