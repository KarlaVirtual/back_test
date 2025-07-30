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
use Firebase\JWT\Key;

/**
 * command/user_query
 *
 * Obtener usuario por filtros
 *
 * Recupera la información de un usuario en base a los parámetros de filtrado proporcionados.
 *
 * @param string $json : Objeto JSON con los parámetros de búsqueda, que incluye:
 *  - *params* (object): Contiene los filtros de búsqueda.
 *    - *user* (string): Nombre de usuario.
 *    - *email* (string): Correo electrónico del usuario.
 *    - *name* (string): Nombre del usuario.
 *    - *apellido* (string): Apellido del usuario.
 *    - *nacionalidad* (string): Nacionalidad del usuario.
 *    - *CIP* (string): Código CIP del usuario.
 *    - *domicilio* (string): Dirección del usuario.
 *    - *cell_phone* (string): Número de celular del usuario.
 *    - *site_id* (string): Identificador del sitio.
 *  - *rid* (string): Identificador único de la solicitud.
 *
 * @return object  $response es un array con los siguientes atributos:
 *  - *code* (int): Código de estado de la respuesta.
 *  - *msg* (string): Mensaje descriptivo del resultado.
 *  - *data* (array): Contiene la información del usuario encontrado, con los siguientes datos:
 *    - *userId* (int): Identificador único del usuario.
 *  - *rid* (string): Identificador único de la solicitud.
 *
 * @throws Exception Si no se encuentra el usuario (código de error 24).
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


// $site_id = $user_info->site_id;
// $site_id=strtolower($site_id);


/* extrae datos de un objeto JSON en variables PHP. */
$params = $json->params;

$user = $params->user;
$email = $params->email;
$Name = $params->name;
$Lastname = $params->apellido;

/* Se asignan valores de parámetros a variables relacionadas con datos personales y un ítem. */
$Nationality = $params->nacionalidad;
$CIP = $params->CIP;
$Home = $params->domicilio;
$CellPhone = $params->cell_phone;
$site_id = $params->site_id;


$OrderedItem = 1;

/* Código PHP que define reglas para filtrar datos basados en un campo específico. */
$SkeepRows = 0;
$MaxRows = 1;


$rules = [];


array_push($rules, array("field" => "usuario.login", "data" => $Name, "op" => "eq"));

//array_push($rules,array("field"=>"registro.celular","data"=>$CellPhone,"op"=>"eq"));


/* Se crean reglas de filtrado para usuarios, organizadas en un arreglo JSON. */
array_push($rules, array("field" => "usuario.mandante", "data" => $site_id, "op" => "eq"));
array_push($rules, array("field" => "usuario.eliminado", "data" => 'N', "op" => "eq"));
array_push($rules, array("field" => "usuario.estado", "data" => 'A', "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


/* Se obtiene una lista de usuarios en formato JSON, que se decodifica y almacena. */
$Usuario = new Usuario();
$users = $Usuario->getUsuariosCustom("usuario.login,registro.celular,usuario.usuario_id", "usuario.usuario_id", "desc", $SkeepRows, $MaxRows, $json, true);


$users = json_decode($users);


$final = [];


/* Recorre usuarios y almacena sus IDs en un array final. */
foreach ($users->data as $key => $value) {


    $array = [];

    $array["userId"] = $value->{"usuario.usuario_id"};


    array_push($final, $array);

}


/* Verifica la existencia de un usuario y lanza una excepción si no se encuentra. */
if (oldCount($final) == 0) {
    throw new Exception("No existe el usuario", 24);
}


$response = [];

/* establece una respuesta con éxito y datos específicos en un array. */
$response['code'] = 0;
$response['msg'] = 'Success';
$response["data"] = $final[0];
$response['rid'] = $json->rid;


?>