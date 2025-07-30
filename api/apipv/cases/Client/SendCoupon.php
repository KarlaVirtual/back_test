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
 * Coupons/InsertCoupons
 *
 * Genera cupones para un producto específico.
 *
 * @param object $params Objeto que contiene:
 * @param int $params ->Quantity Cantidad de cupones a generar.
 * @param float $params ->Value Valor de cada cupón.
 * @param int $params ->ProductId ID del producto asociado.
 * @param int $params ->CountryId ID del país.
 * @param int $params ->CurrencyId ID de la moneda.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Lista de códigos de cupones generados.
 */


/* Asigna valores de parámetros a variables en PHP para procesar información de productos. */
$cantidad = $params->Quantity;
$valor = $params->Value;
$productoId = $params->ProductId;
$PaisId = $params->CountryId;
$MonedaId = $params->CurrencyId;
$mandante = $_SESSION['mandante'];

/* Código que inicializa objetos y configura parámetros para manejar transacciones de productos. */
$TransaccionProducto = new TransaccionProducto();

$ConfigurationEnvironment = new ConfigurationEnvironment();

$SkeepRows = 0;
$MaxRows = 1000000;

/* Se inicializa un arreglo vacío en PHP llamado `$array`. */
$array = array();
for ($i = 1; $i <= $cantidad; $i++) {


    /* Se crea una transacción de producto con un ID de producto y usuario. */
    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
    $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

    $TransaccionProducto = new TransaccionProducto();
    $TransaccionProducto->setProductoId($productoId);
    $TransaccionProducto->setUsuarioId(0);

    /* Código que configura un objeto de transacción de producto con varios atributos. */
    $TransaccionProducto->setValor($valor);
    $TransaccionProducto->setEstado('A');
    $TransaccionProducto->setTipo('T');
    $TransaccionProducto->setExternoId(0);
    $TransaccionProducto->setEstadoProducto('P');
    $TransaccionProducto->setMandante($mandante);

    /* establece propiedades y luego inserta un objeto en la base de datos. */
    $TransaccionProducto->setFinalId(0);
    $TransaccionProducto->setFinalId(0);
    $TransaccionProducto->setUsutarjetacredId(0);


    $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

    /* genera un cupón encriptado y lo almacena en un array. */
    $codigoCupon = $ConfigurationEnvironment->encryptCusNum(intval($TransaccionProducto->transproductoId));

    array_push($array, $codigoCupon);

    $TransprodLog = new TransprodLog();
    $TransprodLog->setTransproductoId($transproductoId);

    /* Configura el estado y detalles de un log de transacción en un sistema. */
    $TransprodLog->setEstado('P');
    $TransprodLog->setTipoGenera('A');
    $TransprodLog->setComentario('Cupon generado por ' . $_SESSION["usuario2"]);
    $TransprodLog->setTValue("");
    $TransprodLog->setUsucreaId($_SESSION["usuario2"]);
    $TransprodLog->setUsumodifId(0);


    /* inserta un registro en la base de datos y confirma la transacción. */
    $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
    $TransprodLogMySqlDAO->insert($TransprodLog);

    $Transaction->commit();
}


/* Código que configura una respuesta exitosa sin errores y con datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $array;
/*
$json = '{"rules" : [{"field" : "a.", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';

$transproductos = $TransaccionProducto->getTransaccionesCustom("transaccion_producto.*, producto.*", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, false);

$transproductos = json_decode($transproductos);

$final = [];

foreach ($transproductos->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"transaccion_producto.producto_id"};
    $array["Producto"] = $value->{"producto.descripcion"};
    $array["Usuario"] = $value->{"transaccion_producto.usuario_id"};
    $array["ProviderId"] = $value->{"transaccion_producto.proveedor_id"};
    $array["ProviderName"] = $value->{"transaccion_producto.descripcion"};


    $array["Notes"] = $value->{"transaccion_producto.descripcion"};
    array_push($final, $array);

}


$response = $final;*/

