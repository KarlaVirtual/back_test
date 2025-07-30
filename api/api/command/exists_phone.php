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
 * Verifica la existencia de un número de teléfono en el sistema.
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada.
 * @param string $json ->params->ck Cookie del usuario.
 * @param string $json ->params->site_id Identificador del sitio.
 * @param string $json ->params->phone Número de teléfono a verificar.
 *
 * @return array Respuesta con el código de resultado y los datos.
 *  -code:int Código de resultado.
 *  -rid:string Identificador de la solicitud.
 *  -data:array Datos de la respuesta.
 *
 * @throws Exception Si el número de teléfono está vacío.
 * @throws Exception Si el número de teléfono ya existe en el sistema.
 * @throws Exception Si el identificador del sitio está vacío.
 */

// Se obtiene la cookie y el ID del sitio del objeto JSON de parámetros
$cookie = $json->params->ck;
$site_id = $json->params->site_id;
$site_id = strtolower($site_id);

// Se obtiene el número de teléfono del objeto JSON de parámetros
$phone = $json->params->phone;
$ConfigurationEnvironment = new ConfigurationEnvironment();

$phone = $ConfigurationEnvironment->DepurarCaracteres($phone);

// Se eliminan caracteres no ASCII del número de teléfono
$phone =   preg_replace('/[^(\x20-\x7F)]*/','', $phone );

$site_id = $ConfigurationEnvironment->DepurarCaracteres($site_id);

// Se lanza una excepción si el ID del sitio está vacío
if ($site_id == "") {
    throw new Exception("Inusual Detected", "100001");
}

// Se lanza una excepción si el teléfono está vacío
if ($phone == "") {
    throw new Exception("Inusual Detected", "100001");
}

// Se crea una instancia de Mandante con el ID del sitio
$Mandante = new Mandante($site_id);

// Se crea una nueva instancia de Registro
$Registro = new Registro();
$Registro->setCelular($phone);
$Registro->setMandante($Mandante->mandante);

$existsId = false;

// Se verifica si UsuarioMandanteSite no es nulo
if($UsuarioMandanteSite != null){
    $UsuarioMandante =  $UsuarioMandanteSite;
    $Usuario2 = new Usuario($UsuarioMandante->getUsuarioMandante());
    $Registro2 = new Registro('',$Usuario2->usuarioId);

    // Se verifica si el celular registrado no coincide con el teléfono proporcionado
    if ( $Registro2->celular !== str_replace('-', '', $phone)) {

        if ($Registro->existeCelular()) {
            throw new Exception("La cédula ya existe", "19000");
        }
    }
}else {
    // Se verifica la existencia del celular si no hay un UsuarioMandanteSite
    if ($Registro->existeCelular()) {
        throw new Exception("La cédula ya existe", "19000");
    }
}

// Se prepara la respuesta para el envío
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array();
