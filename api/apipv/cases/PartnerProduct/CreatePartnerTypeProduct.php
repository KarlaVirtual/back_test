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
 * PartnerProduct/CreatePartnerTypeProduct
 *
 * Guarda un tipo de producto asociado a un socio (Partner).
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param int $params->Partner ID del socio.
 * @param string $params->IsActivate Estado de activación ('A' para activo, 'I' para inactivo).
 * @param string $params->Type Tipo de producto ('CASINO' o 'PAYMENT').
 * @param string $params->Url URL de la API asociada al producto.
 *
 * @return array $response Respuesta con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success', 'Error', etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si el socio (Partner) no existe.
 * @throws Exception Si el proveedor ya tiene el tipo de producto.
 */


/* Asigna valores desde parámetros y valida el estado de activación del socio. */
$Partner = $params->Partner;
$IsActivate = $params->IsActivate;
$Type = $params->Type;
$Url = $params->Url;

$IsActivate = ($IsActivate != 'A' && $IsActivate != "I") ? 'A' : $IsActivate;


/* valida condiciones específicas para continuar con el proceso. */
$seguir = true;

if (($Type != "CASINO" && $Type != "PAYMENT") || ($Partner == "" || !is_numeric($Partner))) {
    $seguir = false;
}

if ($seguir) {


    /* Intenta crear un objeto Mandante; captura excepciones si el Partner no existe. */
    if ($seguir) {

        try {
            $Mandante = new Mandante($Partner);
        } catch (Exception $e) {
            $seguir = false;
            $messageError = "No existe el Partner";
        }
    }

    /* Se intenta crear un objeto, pero se captura un error si ya existe. */
    if ($seguir) {

        try {
            $ProveedorMandante = new ProdMandanteTipo($Type, $Partner, "");
            $seguir = false;
            $messageError = "El proveedor ya tiene el tipo de juego";

        } catch (Exception $e) {

        }
    }

    if ($seguir) {


        /* Se crea un objeto, se asignan valores y se verifica su estado. */
        $ProdMandanteTipo = new ProdMandanteTipo();

        $ProdMandanteTipo->mandante = $Partner;
        $ProdMandanteTipo->tipo = $Type;

        if ($IsActivate != "") {
            $ProdMandanteTipo->estado = $IsActivate;
        }

        /* Se asignan valores a propiedades de un objeto relacionado con un producto y usuario. */
        $ProdMandanteTipo->siteId = 0;
        $ProdMandanteTipo->siteKey = '';
        $ProdMandanteTipo->urlApi = $Url;

        $ProdMandanteTipo->usucreaId = $_SESSION["usuario"];
        $ProdMandanteTipo->usumodifId = $_SESSION["usuario"];


        /* Inserta un objeto en la base de datos y ajusta su propiedad `siteId`. */
        $ProdMandanteTipoMySqlDAO = new ProdMandanteTipoMySqlDAO();

        $ProdMandanteTipoMySqlDAO->insert($ProdMandanteTipo);
        $ProdMandanteTipoMySqlDAO->getTransaction()->commit();

        $ProdMandanteTipo->siteId = $ProdMandanteTipo->prodmandtipoId + 1000;

        /* Se encripta un valor y se actualiza en la base de datos. */
        $ProdMandanteTipo->siteKey = (new ConfigurationEnvironment())->encrypt_decrypt('encrypt', ($ProdMandanteTipo->prodmandtipoId . '_' . $ProdMandanteTipo->mandante) . "_" . time());


        $ProdMandanteTipoMySqlDAO = new ProdMandanteTipoMySqlDAO();

        $ProdMandanteTipoMySqlDAO->update($ProdMandanteTipo);

        /* Realiza una transacción exitosa y establece una respuesta sin errores. */
        $ProdMandanteTipoMySqlDAO->getTransaction()->commit();


        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";

        /* Inicializa un array vacío para almacenar errores del modelo en una respuesta. */
        $response["ModelErrors"] = [];
    } else {
        /* maneja errores, configurando alertas y mensaje de error en la respuesta. */

        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = $messageError;
        $response["ModelErrors"] = [];

    }
} else {
    /* gestiona errores, estableciendo un mensaje de alerta y un estado de error. */

    $response["HasError"] = true;
    $response["AlertType"] = "Error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}

