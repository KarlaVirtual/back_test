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
 * Provider/UpdateProviderDetails
 *
 * Crea o actualiza un proveedor en la base de datos dependiendo del ID proporcionado.
 *
 * Este método valida si el ID del proveedor es proporcionado. Si es así, actualiza los datos de un proveedor existente.
 * Si el ID está vacío, se crea un nuevo proveedor con los datos proporcionados. Los atributos del proveedor incluyen estado,
 * verificación, descripción, abreviatura, imagen, y tipo, entre otros.
 *
 * @param object $params : Objeto que contiene los parámetros para la creación o actualización del proveedor.
 *
 * El objeto $params contiene los siguientes atributos:
 *  - *Id* (int, opcional): ID del proveedor. Si está vacío, se crea un nuevo proveedor; de lo contrario, se actualiza el proveedor existente.
 *  - *IsActivate* (bool): Indica si el proveedor está activado o desactivado.
 *  - *IsVerified* (bool): Indica si el proveedor está verificado.
 *  - *Name* (string): Nombre completo del proveedor.
 *  - *Abbreviated* (string): Nombre abreviado del proveedor.
 *  - *Image* (string): URL o ruta de la imagen asociada al proveedor.
 *  - *Type* (int, opcional): Tipo del proveedor, solo utilizado si se está creando un nuevo proveedor. Los valores posibles son:
 *    - 1: CASINO
 *    - 2: LIVECASINO
 *    - 3: PAYMENT
 *    - 5: PAYOUT
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta que se mostrará (por ejemplo, "success" si la operación fue exitosa).
 *  - *AlertMessage* (string): Mensaje que se mostrará junto a la alerta.
 *  - *ModelErrors* (array): Retorna un array vacío en este caso.
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se asigna el valor de $params->Id a la variable $Id. */
$Id = $params->Id;

if ($Id != "") {


    /* asigna valores de parámetros a variables y crea un objeto Proveedor. */
    $IsActivate = $params->IsActivate;
    $IsVerified = $params->IsVerified;
    $Name = $params->Name;
    $Abbreviated = $params->Abbreviated;
    $Image = $params->Image;


    $Proveedor = new Proveedor($Id);


    /* Actualiza atributos de un proveedor utilizando valores de variables y sesión actual. */
    $Proveedor->setEstado($IsActivate);
    $Proveedor->setVerifica($IsVerified);
    $Proveedor->setDescripcion($Name);
    $Proveedor->setUsumodifId($_SESSION['usuario2']);
    $Proveedor->setAbreviado($Abbreviated);
    $Proveedor->setImagen($Image);


    /* Código para actualizar un proveedor en la base de datos y gestionar la transacción. */
    $ProveedorMySqlDAO = new ProveedorMySqlDAO();
    $ProveedorMySqlDAO->update($Proveedor);
    $ProveedorMySqlDAO->getTransaction()->commit();
} else {

    /* asigna valores de parámetros a variables para su posterior uso. */
    $IsActivate = $params->IsActivate;
    $IsVerified = $params->IsVerified;
    $Name = $params->Name;
    $Abbreviated = $params->Abbreviated;
    $Image = $params->Image;
    $Type = $params->Type;


    /* Se inicializa la variable `$TipoProveedor` como una cadena vacía. */
    $TipoProveedor = "";


    switch ($Type) {
        case 1:
            /* Asigna el valor "CASINO" a la variable $TipoProveedor en el caso 1. */

            $TipoProveedor = "CASINO";

            break;

        case 2:
            /* Definición de una variable según un caso específico en un switch. */

            $TipoProveedor = "LIVECASINO";

            break;

        case 3:
            /* Se define el tipo de proveedor como "PAYMENT" en el caso 3. */

            $TipoProveedor = "PAYMENT";

            break;
        case 5:
            /* asigna "PAYOUT" a la variable $TipoProveedor en el caso 5. */

            $TipoProveedor = "PAYOUT";
            break;

        default:
    }


    /* crea un objeto "Proveedor" y establece atributos como estado, verificación y descripción. */
    $Proveedor = new Proveedor();

    $Proveedor->setEstado($IsActivate);
    $Proveedor->setVerifica($IsVerified);
    $Proveedor->setDescripcion($Name);
    $Proveedor->setUsumodifId($_SESSION['usuario2']);

    /* Se configuran propiedades de un objeto 'Proveedor' y se instancia 'ProveedorMySqlDAO'. */
    $Proveedor->setUsucreaId($_SESSION['usuario2']);
    $Proveedor->setAbreviado($Abbreviated);
    $Proveedor->setTipo($TipoProveedor);
    $Proveedor->setImagen($Image);

    $ProveedorMySqlDAO = new ProveedorMySqlDAO();

    /* Inserta un proveedor y gestiona la transacción en MySQL. */
    $ProveedorMySqlDAO->insert($Proveedor);
    $ProveedorMySqlDAO->getTransaction()->commit();

}
