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
 * BetShop/SaveBetshopCompetence
 *
 * Guardar el punto de venta de la competencia.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params ->Address Dirección del punto de venta.
 * @param int $params ->Competition Identificador de la competencia.
 * @param string $params ->Description Descripción del punto de venta.
 * @param float $params ->Latitud Latitud del punto de venta.
 * @param float $params ->Longitud Longitud del punto de venta.
 * @param int $params ->CityId Identificador de la ciudad.
 * @param string $params ->Name Nombre del punto de venta.
 * @param int|null $params ->Id Identificador del punto de venta (opcional).
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError: boolean Indica si ocurrió un error.
 * - AlertType: string Tipo de alerta.
 * - AlertMessage: string Mensaje de alerta.
 * - ModelErrors: array Errores del modelo.
 */


/* asigna valores de parámetros a variables específicas para su uso posterior. */
$Address = $params->Address;
$Competition = $params->Competition;
$Description = $params->Description;
$Latitud = $params->Latitud;
$Longitud = $params->Longitud;
$CityId = $params->CityId;

/* asigna valores de parámetros a variables en un entorno de programación. */
$Name = $params->Name;
$Id = $params->Id;

if ($Id != "" && is_numeric($Id)) {

    /* Se inicializa un objeto y se configuran sus propiedades correspondientes a competencia. */
    $CompetenciaPuntos = new CompetenciaPuntos($Id);
    $CompetenciaPuntos->setDireccion($Address);
    $CompetenciaPuntos->setCompetenciaId($Competition);
    $CompetenciaPuntos->setDescripcion($Description);
    $CompetenciaPuntos->setLatitud($Latitud);
    $CompetenciaPuntos->setLongitud($Longitud);

    /* Se asignan propiedades a un objeto y se inicializa un DAO para gestión de datos. */
    $CompetenciaPuntos->setNombre($Name);
    $CompetenciaPuntos->setEstado('A');
    $CompetenciaPuntos->setUsucreaId(0);
    $CompetenciaPuntos->setUsumodifId(0);
    $CompetenciaPuntos->setCiudadId($CityId);

    $CompetenciaPuntosMySqlDAO = new CompetenciaPuntosMySqlDAO();


    /* Actualiza datos y confirma la transacción, indicando éxito sin errores. */
    $CompetenciaPuntosMySqlDAO->update($CompetenciaPuntos);
    $CompetenciaPuntosMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";

    /* Inicializa un array vacío para almacenar errores del modelo en la respuesta. */
    $response["ModelErrors"] = [];

} else {

    /* Se crea un objeto CompetenciaPuntos y se establecen sus propiedades. */
    $CompetenciaPuntos = new CompetenciaPuntos();
    $CompetenciaPuntos->setDireccion($Address);
    $CompetenciaPuntos->setCompetenciaId($Competition);
    $CompetenciaPuntos->setDescripcion($Description);
    $CompetenciaPuntos->setLatitud($Latitud);
    $CompetenciaPuntos->setLongitud($Longitud);

    /* Código que inicializa un objeto CompetenciaPuntos y establece sus propiedades. */
    $CompetenciaPuntos->setNombre($Name);
    $CompetenciaPuntos->setEstado('A');
    $CompetenciaPuntos->setUsucreaId(0);
    $CompetenciaPuntos->setUsumodifId(0);
    $CompetenciaPuntos->setCiudadId($CityId);

    $CompetenciaPuntosMySqlDAO = new CompetenciaPuntosMySqlDAO();


    /* Inserta datos en la base de datos y confirma la transacción con éxito. */
    $CompetenciaPuntosMySqlDAO->insert($CompetenciaPuntos);
    $CompetenciaPuntosMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";

    /* Inicializa una clave "ModelErrors" en el arreglo $response como un arreglo vacío. */
    $response["ModelErrors"] = [];

}

