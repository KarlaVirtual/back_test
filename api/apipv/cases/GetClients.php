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
 * Obtener la lista de clientes.
 *
 * Este script permite obtener una lista de clientes con información detallada como su estado, dirección IP, nombre, 
 * idioma, país, observaciones, entre otros datos relevantes.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params->MaxRows Número máximo de filas a devolver. Por defecto, 10.
 * @param int $params->OrderedItem Orden de los elementos. Por defecto, 1.
 * @param int $params->SkeepRows Número de filas a omitir. Por defecto, 0.
 * 
 * 
 * @return array $response Respuesta en formato JSON con la siguiente estructura:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Contiene los objetos de clientes y el conteo total:
 *   - Objects (array): Lista de clientes con sus datos.
 *   - Count (int): Número total de clientes.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */

/*
     $Usuario = new Usuario();

     $params = file_get_contents('php://input');
     $params = json_decode($params);

     $MaxRows = $params->MaxRows;
     $OrderedItem = $params->OrderedItem;
     $SkeepRows = $params->SkeepRows;

     if ($SkeepRows == "") {
     $SkeepRows = 0;
     }

     if ($OrderedItem == "") {
     $OrderedItem = 1;
     }

     if ($MaxRows == "") {
     $MaxRows = 10;
     }

     $json = '{"rules" : [] ,"groupOp" : "AND"}';

     $usuarios = $Usuario->getUsuariosCustom( "usuario.","a.usuario_id", "asc", $SkeepRows, $MaxRows,$json,true);

     $usuarios = json_decode($usuarios);

     $usuariosFinal = [];

     foreach ($usuarios->data as $key => $value) {

     $array = [];

     $array["Id"] = $value->{"a.usuario_id"};
     $array["Ip"] = $value->{"a.dir_ip"};
     $array["Login"] = $value->{"a.login"};
     $array["Estado"] = array($value->{"a.estado"});
     $array["EstadoEspecial"] = $value->{"a.estado_esp"};
     $array["PermiteRecargas"] = $value->{".permite_recarga"};
     $array["ImprimeRecibo"] = $value->{".recibo_caja"};
     $array["Pais"] = $value->{"a.pais_id"};
     $array["Idioma"] = $value->{"a.idioma"};
     $array["Nombre"] = $value->{"a.nombre"};
     $array["FirstName"] = $value->{"registro.nombre1"};
     $array["MiddleName"] = $value->{"registro.nombre2"};
     $array["LastName"] = $value->{"registro.apellido1"};
     $array["Email"] = $value->{"registro.email"};
     $array["Address"] = $value->{"registro.direccion"};
     $array["TipoUsuario"] = $value->{"e.perfil_id"};
     $array["Intentos"] = $value->{"a.intentos"};
     $array["Observaciones"] = $value->{"a.observ"};
     $array["PinAgent"] = $value->{".pinagent"};
     $array["BloqueoVentas"] = $value->{"a.bloqueo_ventas"};
     $array["Moneda"] = $value->{"a.moneda"};
     $array["ActivarRecarga"] = $value->{"a.permite_activareg"};
     $array["City"] = $value->{"g.ciudad_nom"};
     $array["Phone"] = $value->{"f.telefono"};
     $array["FechaCrea"] = $value->{"a.fecha_crea"};
     $array["CreatedLocalDate"] = $value->{"a.fecha_crea"};
     $array["FechaCrea"] = $value->{".fecha_ult"};
     $array["IsLocked"] = false;
     $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
     $array["BirthDate"] = $value->{"usuario_otrainfo.fecha_nacim"};

     $array["Birthday"] = $value->{".fecha_ult"};
     $array["BirthDepartment"] = $value->{"registro.ciudnacim_id"};
     $array["BirthRegionCode"] = $value->{".fecha_ult"};
     $array["BirthRegionId"] = $value->{".fecha_ult"};
     $array["CreatedLocalDate"] = $value->{"a.fecha_crea"};
     $array["CurrencyId"] = $value->{"a.moneda"};
     $array["DocNumber"] = $value->{"registro.cedula"};
     $array["Gender"] = $value->{"registro.sexo"};
     $array["Language"] = $value->{"a.idioma"};
     $array["Phone"] = $value->{"registro.telefono"};
     $array["MobilePhone"] = $value->{"registro.celular"};
     $array["LastLoginLocalDate"] = $value->{".fecha_ult"};
     $array["Province"] = $value->{"registro.ciudnacim_id"};
     $array["CountryName"] = $value->{"registro.nacionalidad_id"};
     $array["ZipCode"] = $value->{"registro.codigo_postal"};

     array_push($usuariosFinal, $array);

     }

     $response["HasError"] = false;
     $response["AlertType"] = "success";
     $response["AlertMessage"] = "";
     $response["ModelErrors"] = [];

     $response["Data"] = array(
     "Objects" => $usuariosFinal,
     "Count" => $usuarios->count[0]->{".count"},

     );
      */