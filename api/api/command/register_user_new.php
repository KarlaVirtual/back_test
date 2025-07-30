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
use Backend\dto\Registro2;
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
use Backend\mysql\Registro2MySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Registra un nuevo usuario en el sistema.
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada.
 * @param string $json->params->first_name Nombre del usuario.
 * @param string $json->params->last_name Apellido del usuario.
 * @param string $json->params->docnumber Número de documento del usuario.
 * @param string $json->params->countryResident_id Identificador del país de residencia.
 * @param string $json->params->type Tipo de usuario.
 * @param string $json->params->email Correo electrónico del usuario.
 * @param string $json->params->phone Teléfono del usuario.
 *
 * @return array Respuesta estructurada con el resultado de la operación.
 * -code:int Código de respuesta.
 * -rid:string Identificador de la respuesta.
 * -data:int Identificador del nuevo registro.
 *
 * @throws Exception Si la cédula ya existe.
 * @throws Exception Si el nombre está vacío.
 * @throws Exception Si el número de documento está vacío.
 * @throws Exception Si el apellido está vacío.
 * @throws Exception Si el correo electrónico está vacío.
 * @throws Exception Si el teléfono está vacío.
 * @throws Exception Si el tipo de usuario está vacío.
 */

/* extrae parámetros de un objeto JSON a variables individuales. */
$params = $json->params;
$first_name = $params->first_name;
$last_name = $params->last_name;
$docnumber = $params->docnumber;
$countryResident_id = $params->countryResident_id;
$type = $params->type;

/* asigna valores de parámetros a variables y establece un documento en un objeto. */
$email = $params->email;
$phone = $params->phone;

$registro2 = new Registro2();

$registro2->setDocument($docnumber);

/* verifica cédula y nombre, lanzando excepciones si hay errores. */
$CheckId = $registro2->verificarCedula();

if ($CheckId) {
    throw new Exception("la cedula ya existe", 100098);
}

if ($first_name == "") {
    throw new Exception("Error Processing Request", 100001);
}


/* valida si $docnumber y $last_name están vacíos, lanzando excepciones. */
if ($docnumber == "") {
    throw new Exception("Error Processing Request", 100001);
}

if ($last_name == "") {
    throw new Exception("Error Processing Request", 100001);
}


/* verifica si email o teléfono están vacíos y genera una excepción. */
if ($email == "") {
    throw new Exception("Error Processing Request", 100001);
}

if ($phone == "") {
    throw new Exception("Error Processing Request", 100001);
}


/* lanza una excepción si `$type` está vacío, creando un nuevo registro. */
if ($type == "") {
    throw new Exception("Error Processing Request", 100001);
}


$Registro = new Registro2();

/* establece valores en un objeto `Registro` para diferentes atributos. */
$Registro->setDocument($docnumber);
$Registro->setName($first_name);
$Registro->setApellido($last_name);
$Registro->setPhone($phone);
$Registro->setEmail($email);
$Registro->setTipo($type);


/* Se inserta un registro en MySQL y se confirma la transacción. Respuesta preparada. */
$RegistroMySqlDAO = new Registro2MySqlDAO();
$Id = $RegistroMySqlDAO->insert($Registro);
$RegistroMySqlDAO->getTransaction()->commit();

$response = array();
$response["code"] = 0;

/* Asigna valores de un objeto JSON a un array de respuesta en PHP. */
$response["rid"] = $json->rid;
$response["data"] = $Id;


?>