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

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

/**
 * get_note_withdrawal
 *
 * Obtener una nota de retiro respecto el ID especificado
 *
 * @param object $json->session->usuario Usuario de la sesión.
 * @param int $json->params->idNoteWithdrawal ID de la nota de retiro.
 * @param string $json->params->passwordNoteWithdrawal Contraseña de la nota de retiro.
 * @param int $json->rid ID de la solicitud.
 *
 * @return array $response Respuesta que contiene el código de estado, ID de la solicitud y datos de la nota de retiro.
 *  - code:int Código de estado de la respuesta.
 *  - rid:int ID de la solicitud.
 *  - data:array Datos de la nota de retiro.
 *      - noteWithdrawal:int ID de la nota de retiro.
 *      - idUser:int ID del usuario.
 *      - loginUser:string Nombre del usuario.
 *      - valueWithdrawal:float Valor de la nota de retiro.
 *
 * @throws Exception Si ocurre un error al obtener las cuentas de cobro.
 */

$params = file_get_contents('php://input'); // Obtiene los datos de entrada en formato JSON
$params = json_decode($params); // Decodifica los datos JSON a un objeto PHP

$IdNota = intval($json->params->idNoteWithdrawal); // Convierte el id de nota de retiro a entero
$Clave = $json->params->passwordNoteWithdrawal; // Obtiene la contraseña de la nota de retiro

$MaxRows = 1; // Define el número máximo de filas a obtener
$OrderedItem = 1; // Define el item ordenado (no se usa en el código)
$SkeepRows = 0; // Define cuántas filas omitir


$rules = [];

array_push($rules, array("field" => "cuenta_cobro.cuenta_id", "data" => "$IdNota", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND"); // Define el filtro aplicando las reglas
$jsonfiltro = json_encode($filtro); // Codifica el filtro a formato JSON

$CuentaCobro = new CuentaCobro($IdNota, "", $Clave); // Crea una nueva instancia de CuentaCobro

$cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.nombre,cuenta_cobro.cuenta_id,cuenta_cobro.usuario_id,cuenta_cobro.usucambio_id,cuenta_cobro.observacion,cuenta_cobro.mensaje_usuario,usuario.login,cuenta_cobro.fecha_crea,cuenta_cobro.valor,cuenta_cobro.mediopago_id,usuario.moneda,cuenta_cobro.puntoventa_id,punto_venta.descripcion puntoventa,cuenta_cobro.mediopago_id, banco.descripcion banco_nombre,cuenta_cobro.estado,usuario_banco.cuenta", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, "cuenta_cobro.cuenta_id"); // Obtiene las cuentas de cobro personalizadas

$cuentas = json_decode($cuentas); // Decodifica las cuentas obtenidas a un objeto PHP


/*obtiene y procesa datos de notas de retiro de una base de datos, aplicando filtros y formateando los resultados en un array para su respuesta en formato JSON.*/
$final = array();
foreach ($cuentas->data as $key => $value) {

    $array = [];

    $array["noteWithdrawal"] = $value->{"cuenta_cobro.cuenta_id"};
    $array["idUser"] = $value->{"cuenta_cobro.usuario_id"};
    $array["loginUser"] = $value->{"usuario.nombre"};
    $array["valueWithdrawal"] = $value->{"cuenta_cobro.valor"};

    array_push($final, $array);
}

$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;

$response["data"] = $final[0];

