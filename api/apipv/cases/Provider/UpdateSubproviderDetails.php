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
use Backend\dto\Subproveedor;
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
use Backend\mysql\SubproveedorMySqlDAO;
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
 * Provider/UpdateSubproviderDetails
 *
 * Crea o actualiza un subproveedor en la base de datos dependiendo del ID proporcionado.
 *
 * Este método valida y procesa los datos del subproveedor. Si el ID está presente, actualiza un subproveedor existente; de lo contrario, crea uno nuevo. También se valida y estructura la información de las credenciales proporcionadas antes de ser guardadas. El tipo de proveedor se asigna dependiendo del valor recibido en el parámetro Type.
 *
 * @param object $params : Objeto que contiene los parámetros para la creación o actualización del subproveedor.
 *
 * El objeto $params contiene los siguientes atributos:
 *  - *Id* (int, opcional): ID del subproveedor. Si está vacío, se crea un nuevo subproveedor; de lo contrario, se actualiza el subproveedor existente.
 *  - *IsActivate* (bool): Indica si el subproveedor está activado o desactivado.
 *  - *IsVerified* (bool): Indica si el subproveedor está verificado.
 *  - *Name* (string): Nombre completo del subproveedor.
 *  - *Abbreviated* (string): Nombre abreviado del subproveedor.
 *  - *Image* (string): URL o ruta de la imagen asociada al subproveedor.
 *  - *Credentials* (string, opcional): Información de credenciales codificada en base64. Si está presente, se valida la estructura de las credenciales antes de procesarlas.
 *  - *ProviderId* (int, opcional): ID del proveedor asociado al subproveedor, solo necesario si se está creando un nuevo subproveedor.
 *  - *Type* (int): Tipo de subproveedor. Los valores posibles son:
 *    - 1: CASINO
 *    - 2: LIVECASINO
 *    - 3: PAYMENT
 *    - 4: PAYOUT
 *    - 5: SPORTS
 *    - 6: VERIFICATION
 *    - 7: VERIFY
 *    - 8: VIRTUAL
 *    - 9: WHATSAPP
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta que se mostrará (por ejemplo, "success" si la operación fue exitosa).
 *  - *AlertMessage* (string): Mensaje que se mostrará junto a la alerta.
 *  - *ModelErrors* (array): Retorna un array vacío en este caso.
 *
 * @throws Exception Si la estructura de las credenciales es incorrecta o si se produce algún error durante la creación o actualización del subproveedor.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de parámetros a variables en un script PHP. */
$Id = $params->Id;

$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;
$Name = $params->Name;
$Abbreviated = $params->Abbreviated;

/* asigna una imagen de parámetros y define una variable de credenciales nula. */
$Image = $params->Image;
$credentials = null;
if (!empty($params->Credentials)) {

    /* valida y decodifica credenciales; genera error si son inválidas. */
    $credentials = json_decode(base64_decode($params->Credentials));
    if ($credentials == null) {
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "La estructura de las credenciales es incorrecta.";
        $response["ModelErrors"] = [];
        return;
    }


    /* Valida que todas las credenciales tengan un nombre asignado, generando un mensaje de error. */
    $validateCredentials = (array)$credentials;
    foreach ($validateCredentials as $key => $value) {
        if (empty($key)) {
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "Debes asignar el nombre a todas las credenciales.";
            $response["ModelErrors"] = [];
            return;
        }
    }


    /* convierte las credenciales a formato JSON para facilitar su manejo. */
    $credentials = json_encode($credentials);
}


/* Código que inicializa un objeto Subproveedor y establece sus propiedades. */
$subProveedor = new Subproveedor($Id);
$subProveedor->setEstado($IsActivate);
$subProveedor->setVerifica($IsVerified);
$subProveedor->setDescripcion($Name);
$subProveedor->setAbreviado($Abbreviated);
$subProveedor->setImage($Image);


/* Actualiza un proveedor en la base de datos y maneja la transacción adecuadamente. */
if ($Id != "") {

    $subProveedor->setUsumodifId($_SESSION["usuario"]);
    $SubproveedorMySqlDAO = new SubproveedorMySqlDAO();
    $transaction = $SubproveedorMySqlDAO->getTransaction();
    $SubproveedorMySqlDAO->update($subProveedor);
    $SubproveedorMySqlDAO->getTransaction()->commit();


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


} else {


    /* asigna valores de parámetros a variables para su posterior uso. */
    $providerId = $params->ProviderId;
    $Type = $params->Type;

    $TipoProveedor = "";

    switch ($Type) {
        case 1:
            /* asigna 'CASINO' a la variable $TipoProveedor en el caso 1. */

            $TipoProveedor = 'CASINO';
            break;
        case 2:
            /* Asignación del tipo de proveedor como "LIVECASINO" en un caso específico. */

            $TipoProveedor = "LIVECASINO";
            break;
        case 3:
            /* Asignación del tipo de proveedor como "PAYMENT" en un caso específico. */

            $TipoProveedor = "PAYMENT";
            break;
        case 4:
            /* Define un tipo de proveedor como "PAYOUT" en una estructura de control. */

            $TipoProveedor = "PAYOUT";
            break;
        case 5:
            /* asigna "SPORTS" a la variable $TipoProveedor si se cumple el caso 5. */

            $TipoProveedor = "SPORTS";
            break;
        case 6:
            /* asigna el valor "VERIFICATION" a la variable $TipoProveedor en el caso 6. */

            $TipoProveedor = "VERIFICATION";
            break;
        case 7:
            /* asigna "VERIFY" a $TipoProveedor en el caso 7 de un switch. */

            $TipoProveedor = "VERIFY";
            break;
        case 8:
            /* Asignación del tipo de proveedor como "VIRTUAL" en el caso 8 de un switch. */

            $TipoProveedor = "VIRTUAL";
            break;
        case 9:
            /* asigna "WHATSAPP" a la variable $TipoProveedor en el caso 9. */

            $TipoProveedor = "WHATSAPP";
            break;
        default:
            # code...
            break;
    }


    /* Configura un objeto de subproveedor con credenciales y datos del proveedor. */
    $subProveedor->setCredentials($credentials);
    $subProveedor->setProveedorId($providerId);
    $subProveedor->setTipo($TipoProveedor);
    $subProveedor->setUsucreaId($_SESSION["usuario"]);
    $subProveedor->setUsumodifId(0);

    $SubProveedorMySqlDAO = new SubproveedorMySqlDAO();

    /* Inserta un subproveedor en base de datos y confirma la transacción exitosa. */
    $SubProveedorMySqlDAO->insert($subProveedor);
    $SubProveedorMySqlDAO->getTransaction()->commit();

    /* Inicializa un mensaje de alerta vacío y un arreglo para errores en el modelo. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}


?>
