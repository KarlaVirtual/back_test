<?php


use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Actualiza la información de registro de un usuario
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada.
 * @param string $json->params->id Identificador de la tarjeta de crédito.
 * @param string $json->params->email Correo electrónico del usuario.
 * @param string $json->params->name Nombre del usuario.
 * @param string $json->params->lastname Apellido del usuario.
 * @param string $json->params->nationalty Nacionalidad del usuario.
 * @param string $json->params->cip Cédula de identidad del usuario.
 * @param string $json->params->address Dirección del usuario.
 * @param string $json->params->Site_id Identificador del sitio.
 *
 *
 * @return array Respuesta estructurada con el resultado de la operación.
 * -code:int Código de respuesta.
 * -msg:string Mensaje de respuesta.
 * -rid:string Identificador de la respuesta.
 *
 * @throws Exception Si el procesamiento de la tarjeta no está permitido.
 * @throws Exception Si el proveedor es nulo.
 * @throws Exception Si ocurre un error general.
 */

/* verifica si 'id' está vacío y lanza una excepción si es así. */
$params = $json->params;

$id = $params->id;
if ($id == "") {
    throw new Exception("Error General", "100000");

}


/* asigna valores de parámetros a variables específicas relacionadas con un usuario. */
$Email = $params->email;
$Name = $params->name;
$Lastname = $params->lastname;
$Nationality = $params->nationalty;
$CIP = $params->cip;
$address = $params->address;

/* asigna un identificador y establece valores en un objeto Registro. */
$Site_id = $params->Site_id;


$registro = new Registro("", $id);
$registro->setEmail($Email);
$registro->setCedula($CIP);

/* establece propiedades de un objeto y crea una instancia de acceso a la base de datos. */
$registro->setDireccion($address);
$registro->setApellido1($Lastname);
$registro->setnombre($Name);
$registro->setNacionalidadId($Nationality);


$registroMysqlDao = new RegistroMySqlDAO();

/* gestiona una transacción en una base de datos y configura el entorno. */
$transaction = $registroMysqlDao->getTransaction();
$registroMysqlDao->update($registro);
$registroMysqlDao->getTransaction()->commit();


$config = new ConfigurationEnvironment();

/* Crea un nuevo usuario con nombre, email y genera una contraseña aleatoria. */
$newPassword = $config->GenerarClaveTicket(12);

$Usuario = new Usuario($id);
$Usuario->nombre = $Name . ' ' . $Lastname;
$Usuario->login = $Email;


$UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

/* Se asignan valores a un objeto y se inicia una transacción en MySQL. */
$UsuarioMandante->email = $Email;
$UsuarioMandante->nombres = $Usuario->nombre;


$UsuariomysqlDao = new UsuarioMySqlDAO();
$transaction = $UsuariomysqlDao->getTransaction();


/* Código que actualiza usuarios en una base de datos MySQL utilizando transacciones. */
$UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($transaction);

$UsuariomysqlDao->update($Usuario);

$UsuarioMandanteMySqlDAO->update($UsuarioMandante);

$UsuariomysqlDao->getTransaction()->commit();


/* Código que cambia contraseña y genera un mensaje de confirmación para el usuario. */
$Usuario->changeClave($newPassword);

try {
    $mensaje_txt = "La actualizacion de sus datos esta correcta tu usuario para ingresar a la plataforma es: " . $Email . " " . " y tu contraseña es: " . $newPassword;

    //$envio = $config->EnviarCorreoVersion2($Usuario->login, 'noreply@latinbet.com', 'Latinbet', 'Actualizacion de datos', 'mail_registro.php', '', $mensaje_txt, $dominio, $compania, $color_email, $Usuario->mandante);

} catch (Exception $e) {
    /* Manejo de excepciones en PHP, captura de errores sin especificar acciones dentro del bloque. */


}


/* crea una respuesta estructurada con código, mensaje y un identificador. */
$response = [];
$response['code'] = 0;
$response['msg'] = 'Success' . $newPassword;
$response['rid'] = $json->rid;


?>