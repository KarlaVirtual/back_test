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
 * SaveClientProduct
 *
 * Esta función permite guardar el producto de un cliente, asegurando la correcta
 * asociación entre el cliente, el estado y el proveedor. Además, maneja la creación
 * y actualización de registros en la base de datos relacionados con el cliente y sus tokens.
 *
 * @param int $ClientId : ID del cliente cuyo producto será guardado.
 * @param string $State : Estado del producto asociado al cliente.
 * @param string $Token : Token de autenticación del cliente.
 * @param int $ProviderId : ID del proveedor asociado al cliente.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Mensaje informativo sobre el resultado del proceso.
 *  - *ModelErrors* (array): En caso de error, contiene los errores encontrados durante la validación o ejecución.
 *  - *Result* (array): Contiene el resultado de la operación (vacío en este caso).
 *
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Datos Incorrectos";
 * $response["ModelErrors"] = [];
 * $response["Result"] = [];
 *
 * @throws Exception En caso de datos incorrectos o fallos en la base de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables específicas en programación. */
$ClientId = $params->ClientId;

$State = $params->State;
$Token = $params->Token;
$ProviderId = $params->ProviderId;


try {


    /* Verifica campos vacíos y lanza excepción, luego crea objeto UsuarioMandante. */
    if ($ClientId == "" || $State == "" || $ProviderId == "") {
        throw new Exception("Datos Incorrectos", "22");
    }

    try {
        $UsuarioMandante = new UsuarioMandante('', $ClientId, '0');
    } catch (Exception $e) {
        if ($e->getCode() == 22) {

            /* Crea un nuevo usuario y asigna sus datos a un usuario mandante. */
            $Usuario = new Usuario($ClientId);

            $UsuarioMandante = new UsuarioMandante();

            $UsuarioMandante->mandante = $Usuario->mandante;
            $UsuarioMandante->dirIp = $dir_ip;

            /* Asigna propiedades del objeto $Usuario a $UsuarioMandante. */
            $UsuarioMandante->nombres = $Usuario->nombre;
            $UsuarioMandante->apellidos = $Usuario->nombre;
            $UsuarioMandante->estado = $Usuario->estado;
            $UsuarioMandante->email = $Usuario->login;
            $UsuarioMandante->moneda = $Usuario->moneda;
            $UsuarioMandante->paisId = $Usuario->paisId;

            /* Inicializa propiedades de un objeto y crea una instancia de UsuarioMandanteMySqlDAO. */
            $UsuarioMandante->saldo = 0;
            $UsuarioMandante->usuarioMandante = $Usuario->usuarioId;
            $UsuarioMandante->usucreaId = 0;
            $UsuarioMandante->usumodifId = 0;

            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();

            /* Se inserta un usuario y se obtiene la transacción activa en MySQL. */
            $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

            $UsuarioMandanteMySqlDAO->getTransaction()->getConnection()->commit();

        }
    }


    /* Actualiza el estado de un UsuarioToken en la base de datos y confirma la transacción. */
    try {
        $UsuarioToken = new UsuarioToken('', $ProviderId, $UsuarioMandante->getUsumandanteId());
        $UsuarioToken->estado = $State;

        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->update($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();


    } catch (Exception $e) {
        /* Manejo de excepciones que crea y almacena un token de usuario en la base de datos. */


        $UsuarioToken = new UsuarioToken();
        $UsuarioToken->setProveedorId($ProviderId);
        $UsuarioToken->setCookie('0');
        $UsuarioToken->setRequestId('0');
        $UsuarioToken->setUsucreaId(0);
        $UsuarioToken->setUsumodifId(0);
        $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
        $UsuarioToken->setToken($UsuarioToken->createToken());
        $UsuarioToken->estado = $State;

        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

        $UsuarioTokenMySqlDAO->getTransaction()->commit();
    }


    /* inicializa una respuesta sin errores y define mensajes y resultados. */
    $response["HasError"] = false;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();

} catch (Exception $e) {
    /* Manejo de excepciones que genera una respuesta de error personalizada. */


    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = $e->getMessage();
    $response["ModelErrors"] = [];

}