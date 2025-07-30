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
 * Obtiene las monedas disponibles por país.
 *
 * @param no
 *
 * @return array $response Respuesta con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (success, danger, etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Lista de monedas disponibles por país, con los siguientes atributos:
 *   - Id (int): ID del país.
 *   - Name (string): Nombre del país.
 *   - currencies (array): Lista de monedas únicas asociadas al país.
 *   - departments (array): Lista de departamentos con:
 *     - Id (int): ID del departamento.
 *     - Name (string): Nombre del departamento.
 *     - cities (array): Lista de ciudades con:
 *       - Id (int): ID de la ciudad.
 *       - Name (string): Nombre de la ciudad.
 */


/* Se crea una instancia de 'Pais' y se define un filtro JSON para consultas. */
$Pais = new Pais();

$SkeepRows = 0;
$MaxRows = 1000000;

$json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';


/* Asignación de un 'mandanteEspecifico' basado en condiciones de sesión. */
$mandanteEspecifico = '';
if ($_SESSION['Global'] == "N") {
    $mandanteEspecifico = $_SESSION['mandante'];
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        $mandanteEspecifico = $_SESSION["mandanteLista"];
    }

}


/* obtiene y decodifica datos de países y ciudades en formato JSON. */
$paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true, $mandanteEspecifico);

$paises = json_decode($paises);

$final = [];
$arrayf = [];

/* Se inicializan tres arreglos vacíos: monedas, ciudades y departamentos. */
$monedas = [];

$ciudades = [];
$departamentos = [];

foreach ($paises->data as $key => $value) {


    /* Se crea un arreglo con identificadores y nombres de país desde un objeto. */
    $array = [];

    $array["Id"] = $value->{"pais.pais_id"};
    $array["Name"] = $value->{"pais.pais_nom"};

    $departamento_id = $value->{"departamento.depto_id"};

    /* gestiona datos de departamentos y ciudades, consolidando y filtrando información única. */
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


    /* Se asignan valores de país y moneda a un arreglo asociativo. */
    $arrayf["Id"] = $value->{"pais.pais_id"};
    $arrayf["Name"] = $value->{"pais.pais_nom"};

    $moneda = [];
    $moneda["Id"] = $value->{"pais_moneda.moneda"};
    $moneda["Name"] = $value->{"pais_moneda.moneda"};


    /* Agrega un departamento y sus ciudades a un arreglo si cumplen ciertas condiciones. */
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
        /* crea un array de ciudad con ID y nombre, y lo agrega a una lista. */

        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;

        array_push($ciudades, $ciudad);
    }


    /* Se obtienen el ID y nombre del departamento de un objeto $value. */
    $departamento_idf = $value->{"departamento.depto_id"};
    $departamento_textof = $value->{"departamento.depto_nom"};

}


/* Se crea un array de departamentos con ID, nombre y ciudades asociadas. */
$departamento = [];
$departamento["Id"] = $departamento_idf;
$departamento["Name"] = $departamento_textof;
$departamento["cities"] = $ciudades;

array_push($departamentos, $departamento);


/* Se agregan monedas únicas y departamentos a un arreglo final en PHP. */
$ciudades = [];

array_push($monedas, $moneda);
$arrayf["currencies"] = array_unique($monedas);
$arrayf["departments"] = $departamentos;

array_push($final, $arrayf);


/* Se crea un array "regiones" y se agrega un elemento con datos de América. */
$regiones = [];

$array["Id"] = "1";
$array["Name"] = "America";
$array["countries"] = $final;

array_push($regiones, $array);


/* Código establece una respuesta exitosa con datos y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = ($monedas);