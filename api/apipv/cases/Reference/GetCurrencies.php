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
 * Reference/GetCurrencies
 *
 * Obtiene una lista de países, departamentos, ciudades y monedas con base en los filtros aplicados.
 *
 * Este método obtiene información de países, sus respectivos departamentos, ciudades y monedas.
 * Se aplica un filtro para obtener solo los países activos y luego se organiza la información en una estructura
 * jerárquica que incluye regiones, países, departamentos y ciudades. Los datos son retornados en formato JSON.
 * Además, se tiene en cuenta la configuración del mandante específico si está disponible en la sesión del usuario.
 *
 * @param object $params Objeto que contiene los parámetros de paginación y filtrado.
 *  - *count* (int): Número máximo de elementos a retornar.
 *  - *start* (int): Número de la primera fila a retornar (paginación).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta que se mostrará (por ejemplo, "success" si la operación fue exitosa).
 *  - *AlertMessage* (string): Mensaje que se mostrará junto a la alerta.
 *  - *ModelErrors* (array): Array vacío en este caso.
 *  - *Data* (array): Lista de monedas únicas obtenidas, organizadas por país, departamento y ciudad.
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crea un objeto "Pais" y se define un filtro JSON para consultas. */
$Pais = new Pais();

$SkeepRows = 0;
$MaxRows = 1000000;

$json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';


/* Selecciona un mandante específico basado en la sesión global y la lista de mandantes. */
$mandanteEspecifico = '';
if ($_SESSION['Global'] == "N") {
    $mandanteEspecifico = $_SESSION['mandante'];
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        $mandanteEspecifico = $_SESSION["mandanteLista"];
    }

}


/* obtiene y decodifica información sobre países y ciudades de una base de datos. */
$paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true, $mandanteEspecifico);

$paises = json_decode($paises);

$final = [];
$arrayf = [];

/* Se inicializan tres arreglos vacíos: monedas, ciudades y departamentos. */
$monedas = [];

$ciudades = [];
$departamentos = [];

foreach ($paises->data as $key => $value) {


    /* extrae datos de un objeto y los organiza en un array asociativo. */
    $array = [];

    $array["Id"] = $value->{"pais.pais_id"};
    $array["Name"] = $value->{"pais.pais_nom"};

    $departamento_id = $value->{"departamento.depto_id"};

    /* gestiona datos de departamentos y ciudades, agregando información a arreglos. */
    $departamento_texto = $value->{"departamento.depto_nom"};

    $ciudad_id = $value->{"ciudad.ciudad_id"};
    $ciudad_texto = $value->{"ciudad.ciudad_nom"};

    if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

        $arrayf["currencies"] = array_unique($monedas);
        $arrayf["departaments"] = $departamentos;
        array_push($final, $arrayf);
        array_push($monedas, $moneda);

        $arrayf = [];
        //$monedas = [];
        $departamentos = [];
        $ciudades = [];

    }


    /* asigna valores de un objeto a arrays para país y moneda. */
    $arrayf["Id"] = $value->{"pais.pais_id"};
    $arrayf["Name"] = $value->{"pais.pais_nom"};

    $moneda = [];
    $moneda["Id"] = $value->{"pais_moneda.moneda"};
    $moneda["Name"] = $value->{"pais_moneda.moneda"};


    /* agrega un departamento y su ciudad a un arreglo si cumplen ciertas condiciones. */
    if ($departamento_idf != $departamento_id && $departamento_idf != "") {

        $departamento = [];
        $departamento["Id"] = $departamento_idf;
        $departamento["Name"] = $departamento_textof;
        $departamento["cities"] = $ciudades;

        array_push($departamentos, $departamento);

        $ciudades = [];

        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;

        array_push($ciudades, $ciudad);

    } else {
        /* Se crea un nuevo registro de ciudad y se agrega a un array. */

        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;

        array_push($ciudades, $ciudad);
    }


    /* Se obtienen el ID y nombre del departamento desde un objeto `$value`. */
    $departamento_idf = $value->{"departamento.depto_id"};
    $departamento_textof = $value->{"departamento.depto_nom"};

}


/* Se crea un array de departamento y se añade a un array de departamentos. */
$departamento = [];
$departamento["Id"] = $departamento_idf;
$departamento["Name"] = $departamento_textof;
$departamento["cities"] = $ciudades;

array_push($departamentos, $departamento);


/* Se crea un arreglo con monedas y departamentos, luego se agrega a un arreglo final. */
$ciudades = [];

array_push($monedas, $moneda);
$arrayf["currencies"] = (new ConfigurationEnvironment())->unique_multidim_array($monedas, "Id");
$arrayf["departments"] = $departamentos;

array_push($final, $arrayf);


/* Se crea un array de regiones con un país específico y se agrega a una lista. */
$regiones = [];

$array["Id"] = "1";
$array["Name"] = "America";
$array["countries"] = $final;

array_push($regiones, $array);


/* crea una respuesta estructurada para un entorno de configuración. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = ((new ConfigurationEnvironment())->unique_multidim_array($monedas, "Id"));
