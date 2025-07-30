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
 * PartnerProduct/CreatePartnerProduct
 *
 * Guardar Partner Producto
 *
 * @param no
 *
 * @return
 *{"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asigna valores de parámetros a variables para el manejo de productos y socios. */
$Product = $params->Product;
$Partner = $params->Partner;
$IsActivate = $params->IsActivate;
$FilterCountry = $params->FilterCountry;
$IsVerified = $params->IsVerified;
$Maximum = $params->Maximum;

/* Asignación de valores de parámetros a variables en un script. */
$Minimum = $params->Minimum;
$ProcessingTime = $params->ProcessingTime;
$Order = $params->Order;
$Order = $params->Order;
$Rows = $params->Rows;
$Columns = $params->Columns;

/* Validación de estados y filtros, asignando 'I' si no son 'A' o 'I'. */
$Info = $params->Info;

$IsActivate = ($IsActivate != 'A' && $IsActivate != "I") ? 'I' : $IsActivate;
$IsVerified = ($IsVerified != 'A' && $IsVerified != "I") ? 'I' : $IsVerified;
$FilterCountry = ($FilterCountry != 'A' && $FilterCountry != "I") ? 'I' : $FilterCountry;

$seguir = true;


/* Verifica si varía "Product" o "Partner" están vacíos o no son numéricos. */
if (($Product == "" || !is_numeric($Product)) || ($Partner == "" || !is_numeric($Partner))) {
    $seguir = false;
}

if ($seguir) {


    /* Intenta crear un objeto Producto; captura errores si el proveedor no existe. */
    try {
        $Producto = new Producto($Product);
    } catch (Exception $e) {
        $seguir = false;
        $messageError = "No existe el proveedor";
    }

    /* Código en PHP para manejar excepciones al inicializar un objeto Mandante. */
    if ($seguir) {

        try {
            $Mandante = new Mandante($Partner);
        } catch (Exception $e) {
            $seguir = false;
            $messageError = "No existe el Partner";
        }
    }


    /* Intenta crear un objeto y maneja errores si ya existe el proveedor. */
    if ($seguir) {

        try {
            $ProductoMandante = new ProductoMandante($Product, $Partner, "");
            $seguir = false;
            $messageError = "Ya existe el proveedor para el Partner";
        } catch (Exception $e) {
        }
    }

    if ($seguir) {


        /* Crea un objeto y asigna propiedades dependiendo de condiciones específicas. */
        $ProductoMandante = new ProductoMandante();

        $ProductoMandante->mandante = $Partner;
        $ProductoMandante->productoId = $Product;

        if ($IsActivate != "") {
            $ProductoMandante->estado = $IsActivate;
        }


        /* Asigna valores a propiedades de objeto según condiciones de verificación y país. */
        if ($IsVerified != "") {
            $ProductoMandante->verifica = $IsVerified;
        }

        if ($FilterCountry != "") {
            $ProductoMandante->filtroPais = $FilterCountry;
        }


        /* Asigna valores a un objeto si las variables no están vacías. */
        if ($Maximum != "") {
            $ProductoMandante->max = $Maximum;
        }

        if ($Minimum != "") {
            $ProductoMandante->min = $Minimum;
        }


        /* Asignan valores a propiedades de $ProductoMandante si las variables no están vacías. */
        if ($Detail != "") {
            $ProductoMandante->detalle = $Detail;
        }

        if ($Order != "") {
            $ProductoMandante->orden = $Order;
        }


        /* Asigna valores a numFila y numColumna según la existencia de Rows y Columns. */
        if ($Rows != "") {
            $ProductoMandante->numFila = $Rows;
        }

        if ($Columns != "") {
            $ProductoMandante->numColumna = $Columns;
        }


        /* Se asignan valores a propiedades de un objeto basado en condiciones y variables de sesión. */
        if ($Info != "") {
            $ProductoMandante->extrainfo = $Info;
        }

        $ProductoMandante->habilitacion = "I";
        $ProductoMandante->paisId = $_SESSION["pais_id"];

        /* Inicializa propiedades del objeto y crea una instancia del DAO para manejar datos. */
        $ProductoMandante->ordenDestacado = 0;

        $ProductoMandante->usucreaId = $_SESSION["usuario"];
        $ProductoMandante->usumodifId = $_SESSION["usuario"];


        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();


        /* Inserta un producto en la base de datos y confirma la transacción con éxito. */
        $ProductoMandanteMySqlDAO->insert($ProductoMandante);
        $ProductoMandanteMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";

        /* Inicializa un arreglo vacío para almacenar errores de modelo en la respuesta. */
        $response["ModelErrors"] = [];
    } else {
        /* Manejo de errores en una respuesta, indicando tipo y mensaje de alerta. */

        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = $messageError;
        $response["ModelErrors"] = [];

    }
} else {
    /* maneja un error, estableciendo un mensaje y tipo de alerta. */

    $response["HasError"] = true;
    $response["AlertType"] = "Error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}

