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
use Backend\dto\SubproveedorMandantePais;
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
use Backend\mysql\SubproveedorMandantePaisMySqlDAO;
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
 * PartnerProduct/CreatePartnerProvider
 *
 * Guarda un proveedor asociado a un socio (Partner).
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param int $params->Provider ID del proveedor.
 * @param int $params->Partner ID del socio.
 * @param string $params->IsActivate Estado de activación ('A' para activo, 'I' para inactivo).
 * @param string $params->FilterCountry Filtro de país ('A' para activo, 'I' para inactivo).
 * @param string $params->IsVerified Estado de verificación ('A' para verificado, 'I' para no verificado).
 * @param float $params->Maximum Valor máximo permitido.
 * @param float $params->Minimum Valor mínimo permitido.
 * @param string $params->Detail Detalle adicional del proveedor.
 *
 * @return array $response Respuesta con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success', 'Error', etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si el proveedor no existe.
 * @throws Exception Si el socio (Partner) no existe.
 * @throws Exception Si el proveedor no tiene el tipo de juego permitido.
 * @throws Exception Si el proveedor ya está asociado al socio.
 */


/* Asigna valores de parámetros a variables para procesamiento posterior en un script. */
$Provider = $params->Provider;
$Partner = $params->Partner;
$IsActivate = $params->IsActivate;
$FilterCountry = $params->FilterCountry;
$IsVerified = $params->IsVerified;
$Maximum = $params->Maximum;

/* Se asignan parámetros y se validan condiciones para activar o verificar estados. */
$Minimum = $params->Minimum;
$Detail = $params->Detail;
$Maximum = $params->Maximum;

$IsActivate = ($IsActivate != 'A' && $IsActivate != "I") ? '' : $IsActivate;
$IsVerified = ($IsVerified == 'A' && $IsVerified != "I") ? '' : $IsVerified;

/* valida y modifica variables relacionadas con países, proveedores y socios. */
$FilterCountry = ($FilterCountry == 'A' && $FilterCountry != "I") ? '' : $FilterCountry;

$seguir = true;

if (($Provider == "" || !is_numeric($Provider)) || ($Partner == "" || !is_numeric($Partner))) {
    $seguir = false;
}

if ($seguir) {


    /* Se intenta crear un objeto Proveedor, manejando excepciones si falla. */
    try {
        $Proveedor = new Proveedor($Provider);

    } catch (Exception $e) {
        $seguir = false;
        $messageError = "No existe el proveedor";
    }

    /* Intenta crear un objeto Mandante; captura excepción si el Partner no existe. */
    if ($seguir) {

        try {
            $Mandante = new Mandante($Partner);
        } catch (Exception $e) {
            $seguir = false;
            $messageError = "No existe el Partner";
        }
    }

    /* Intenta inicializar un objeto y maneja excepciones si el tipo no es permitido. */
    if ($seguir) {

        try {
            $ProveedorMandante = new ProdMandanteTipo($Proveedor->getTipo(), $Partner, "");

        } catch (Exception $e) {
            $seguir = false;
            $messageError = "El proveedor no tiene el tipo de juego permitido";

        }
    }


    /* Intenta crear un proveedor y captura errores si ya existe para el socio. */
    if ($seguir) {

        try {
            $ProveedorMandante = new ProveedorMandante($Provider, $Partner, "");
            $seguir = false;
            $messageError = "Ya existe el proveedor para el Partner";
        } catch (Exception $e) {
        }
    }


    if ($seguir) {


        /* Código que instancia un objeto y asigna propiedades según condiciones. */
        $ProveedorMandante = new ProveedorMandante();

        $ProveedorMandante->mandante = $Partner;
        $ProveedorMandante->proveedorId = $Provider;

        if ($IsActivate != "") {
            $ProveedorMandante->estado = $IsActivate;
        }


        /* Asigna valores a propiedades si las variables no están vacías. */
        if ($IsVerified != "") {
            $ProveedorMandante->verifica = $IsVerified;
        }

        if ($FilterCountry != "") {
            $ProveedorMandante->filtroPais = $FilterCountry;
        }


        /* Asigna valores a propiedades de $ProveedorMandante si $Maximum o $Minimum no están vacíos. */
        if ($Maximum != "") {
            $ProveedorMandante->max = $Maximum;
        }

        if ($Minimum != "") {
            $ProveedorMandante->min = $Minimum;
        }


        /* Asignación de valores a propiedades de un objeto según condiciones y sesión de usuario. */
        if ($Detail != "") {
            $ProveedorMandante->detalle = $Detail;
        }
        $ProveedorMandante->usucreaId = $_SESSION["usuario"];
        $ProveedorMandante->usumodifId = $_SESSION["usuario"];


        $ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO();


        /* Inserta un proveedor y confirma la transacción, indicando éxito en la respuesta. */
        $ProveedorMandanteMySqlDAO->insert($ProveedorMandante);
        $ProveedorMandanteMySqlDAO->getTransaction()->commit();


        $response["HasError"] = false;
        $response["AlertType"] = "success";

        /* Inicializa un mensaje de alerta vacío y un array para errores del modelo. */
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    } else {
        /* maneja un error estableciendo parámetros en el arreglo de respuesta. */

        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = $messageError;
        $response["ModelErrors"] = [];

    }
} else {
    /* gestiona errores, configurando parámetros de respuesta para indicar fallo. */

    $response["HasError"] = true;
    $response["AlertType"] = "Error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
