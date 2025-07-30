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
 * Client/GetRegions
 *
 * Este script obtiene las regiones, países o deportes según los parámetros proporcionados.
 *
 * @param object $params
 * $param int $params->SportId Identificador del deporte.
 * $param string $params->BeginDate Fecha de inicio en formato 'Y-m-d H:i:s'.
 * $param string $params->EndDate Fecha de fin en formato 'Y-m-d H:i:s'.
 *
 *
 * @return array $response
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos de las regiones, países o deportes.
 */


/* verifica un SportId y obtiene regiones relacionadas si es válido. */
$SportId = $params->SportId;
$sportId = $_REQUEST["sportId"];

if ($SportId != "") {
    $BeginDate = $params->BeginDate;
    $EndDate = $params->EndDate;

    $regions = (new ConfigurationEnvironment())->getRegions($SportId, $BeginDate, $EndDate);

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfuly";
    $response["ModelErrors"] = [];
    $response["Data"] = $regions;

} elseif ($sportId != "") {


    /* Genera una consulta JSON para filtrar regiones por deporte y las obtiene. */
    $json = '{"rules" : [{"field" : "int_region.deporte_id", "data" : "' . $sportId . '","op":"eq"}] ,"groupOp" : "AND"}';

    $IntRegion = new IntRegion();
    $regiones = $IntRegion->getRegionesCustom(" int_deporte.*,int_region.* ", "int_region.region_id", "asc", 0, 10000, $json, true);
    $regiones = json_decode($regiones);

    $final = array();


    /* Itera sobre regiones, extrayendo ID y nombre, y los almacena en un arreglo. */
    foreach ($regiones->data as $region) {

        $array = array();
        $array["Id"] = $region->{"int_region.region_id"};
        $array["Name"] = $region->{"int_region.nombre"};

        array_push($final, $array);

    }

    /* configura una respuesta exitosa con datos y sin errores. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfuly";
    $response["ModelErrors"] = [];

    $response["Data"] = $final;

} else {

    /* Se crea un objeto "Pais" y se define una consulta JSON para filtrar estados. */
    $Pais = new Pais();

    $SkeepRows = 0;
    $MaxRows = 1000000;

    $json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';


    /* asigna un valor a $mandanteEspecifico según condiciones de sesión. */
    $mandanteEspecifico = '';
    if ($_SESSION['Global'] == "N") {
        $mandanteEspecifico = $_SESSION['mandante'];
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            $mandanteEspecifico = $_SESSION["mandanteLista"];
        }

    }


    /* obtiene y decodifica datos de países en formato JSON, creando estructuras adicionales. */
    $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true, $mandanteEspecifico);

    $paises = json_decode($paises);

    $final = [
        array(
            "Id" => "",
            "Name" => "All",
            "currencies" => array(
                "Id" => "",
                "Name" => "All",
            ),
            "departments" => array(
                "Id" => "",
                "Name" => "All",
            )
        )
    ];

    /* Se inicializan arrays vacíos para monedas, ciudades y departamentos. */
    $arrayf = [];
    $monedas = [];

    $ciudades = [];
    $departamentos = [];

    foreach ($paises->data as $key => $value) {


        /* Se crea un array con información de país y departamento desde un objeto. */
        $array = [];

        $array["Id"] = $value->{"pais.pais_id"};
        $array["Name"] = $value->{"pais.pais_nom"};

        $departamento_id = $value->{"departamento.depto_id"};

        /* organiza datos y limpia arrays tras agregar elementos únicos a una lista final. */
        $departamento_texto = $value->{"departamento.depto_nom"};

        $ciudad_id = $value->{"ciudad.ciudad_id"};
        $ciudad_texto = $value->{"ciudad.ciudad_nom"};

        if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

            $arrayf["currencies"] = array_unique($monedas);
            $arrayf["departments"] = $departamentos;
            array_push($final, $arrayf);

            $arrayf = [];
            $monedas = [];
            $departamentos = [];
            $ciudades = [];

        }


        /* Se asignan datos de país y moneda a arrays asociativos en PHP. */
        $arrayf["Id"] = $value->{"pais.pais_id"};
        $arrayf["Name"] = $value->{"pais.pais_nom"};

        $moneda = [];
        $moneda["Id"] = $value->{"pais_moneda.moneda"};
        $moneda["Name"] = $value->{"pais_moneda.moneda"};


        /* agrega monedas y departamentos con ciudades a las respectivas estructuras de datos. */
        array_push($monedas, $moneda);

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
            /* Agrega un nuevo elemento ciudad al array ciudades con ID y nombre. */

            $ciudad = [];
            $ciudad["Id"] = $ciudad_id;
            $ciudad["Name"] = $ciudad_texto;

            array_push($ciudades, $ciudad);
        }


        /* Extrae el ID y nombre del departamento de un objeto en PHP. */
        $departamento_idf = $value->{"departamento.depto_id"};
        $departamento_textof = $value->{"departamento.depto_nom"};

    }


    /* Crea un array de departamentos y lo agrega a otro array. */
    $departamento = [];
    $departamento["Id"] = $departamento_idf;
    $departamento["Name"] = $departamento_textof;
    $departamento["cities"] = $ciudades;

    array_push($departamentos, $departamento);


    /* organiza y almacena monedas y departamentos en un arreglo final. */
    $ciudades = [];

    array_push($monedas, $moneda);
    $arrayf["currencies"] = array_unique($monedas);
    $arrayf["departments"] = $departamentos;

    array_push($final, $arrayf);


    /* inicializa una respuesta sin errores para una operación específica. */
    $regiones = $final;

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    /* Se asignan los datos de regiones a la clave "Data" de la respuesta. */
    $response["Data"] = $regiones;
}