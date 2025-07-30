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
use Backend\mysql\DescargaMySqlDAO;
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
 * RegisteredDocument/UpdateDocument
 *
 * Crear o actualizar una descarga
 *
 * Este recurso permite crear o actualizar una descarga en función de la presencia de un ID.
 * Si el ID está presente, se realiza una actualización de la descarga,
 * de lo contrario, se crea una nueva entrada en la base de datos.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para crear o actualizar la descarga.
 *   - *Id* (string): ID de la descarga (si está presente, se actualizará la descarga existente).
 *   - *Name* (string): Descripción de la descarga.
 *   - *Route* (string): Ruta del archivo de la descarga.
 *   - *Version* (string): Versión de la descarga.
 *   - *Type* (int): Tipo de descarga, representado por un valor numérico (1, 2, o 3).
 *   - *IsActivate* (bool): Estado de activación de la descarga.
 *   - *EncryptionMethod* (string): Método de encriptación de la descarga.
 *   - *EncryptionValue* (string): Valor de encriptación de la descarga.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada ("danger").
 *  - *AlertMessage* (string): Mensaje de alerta generado por el proceso.
 *  - *ModelErrors* (array): Retorna array vacío si no hay errores en el modelo.
 *
 * Objeto en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "danger",
 *  - *AlertMessage* => "[Mensaje de error]",
 *  - *ModelErrors* => array(),
 *
 * @throws Exception Si ocurre un error durante la creación o actualización de la descarga.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se asigna el valor de $params->Id a la variable $Id. */
$Id = $params->Id;


try {

    if ($Id != "") {


        /* Asigna valores de parámetros a variables en un script PHP. */
        $Name = $params->Name;
        $Route = $params->Route;
        $Version = $params->Version;
        $Type = $params->Type;
        $IsActivate = $params->IsActivate;
        $EncryptionMethod = $params->EncryptionMethod;

        /* Asigna un tipo basado en el valor de $Type usando un switch. */
        $EncryptionValue = $params->EncryptionValue;
        switch ($Type) {

            case 1:
                $tipo = "Terminosycondiciones";
                break;
            case 2:
                $tipo = "Politicadeprivacidad";
                break;
            case 3:
                $tipo = "juegos";
                break;
        }

        /* crea una instancia de "Descarga" y establece sus propiedades. */
        $Descarga = new Descarga($Id);

        $Descarga->setDescripcion($Name);
        $Descarga->setRuta($Route);
        $Descarga->setVersion($Version);
        $Descarga->setTipo($tipo);

        /* Configura propiedades de un objeto "Descarga" y crea un DAO correspondiente. */
        $Descarga->setEstado($IsActivate);
        $Descarga->setPlataforma('0');
        $Descarga->setMandante('0');
        $Descarga->setEncriptacionMetodo($EncryptionMethod);
        $Descarga->setEncriptacionValor($EncryptionValue);

        $DescargaMySqlDAO = new DescargaMySqlDAO();

        /* Actualiza datos en la base de datos y confirma la transacción sin errores. */
        $DescargaMySqlDAO->update($Descarga);
        $DescargaMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";

        /* Se inicializa un array vacío para almacenar errores del modelo en la respuesta. */
        $response["ModelErrors"] = [];

    } else {

        /* asigna valores de parámetros a variables en un script PHP. */
        $Name = $params->Name;
        $Route = $params->Route;
        $Version = $params->Version;
        $Type = $params->Type;
        $IsActivate = $params->IsActivate;
        $EncryptionMethod = $params->EncryptionMethod;

        /* asigna un tipo según el valor de "Type" recibido en parámetros. */
        $EncryptionValue = $params->EncryptionValue;

        $Type = $params->Type;


        switch ($Type) {

            case 1:
                $tipo = "Terminosycondiciones";
                break;
            case 2:
                $tipo = "Politicadeprivacidad";
                break;
            case 3:
                $tipo = "juegos";
                break;
        }


        /* crea un objeto "Descarga" y establece sus propiedades. */
        $Descarga = new Descarga();

        $Descarga->setDescripcion($Name);
        $Descarga->setRuta($Route);
        $Descarga->setVersion($Version);
        $Descarga->setTipo($tipo);

        /* Configura propiedades de un objeto "Descarga" y crea una instancia de DescargaMySqlDAO. */
        $Descarga->setEstado($IsActivate);
        $Descarga->setPlataforma('0');
        $Descarga->setMandante('0');
        $Descarga->setEncriptacionMetodo($EncryptionMethod);
        $Descarga->setEncriptacionValor($EncryptionValue);

        $DescargaMySqlDAO = new DescargaMySqlDAO();


        /* inserta un objeto y confirma la transacción sin errores. */
        $DescargaMySqlDAO->insert($Descarga);

        $DescargaMySqlDAO->getTransaction()->commit();


        $response["HasError"] = false;

        /* Inicializa un array para gestionar alertas y errores de un modelo en PHP. */
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


    }


} catch (Exception $e) {
    /* Manejo de excepciones que registra errores en la respuesta del sistema. */


    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = $e->getMessage();
    $response["ModelErrors"] = [];

}
