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
 * Agent/GetAgentCountries
 *
 * Obtener los países disponibles para registrar un agente.
 *
 * @param array $params Parámetros de entrada para la consulta de países.
 *
 * @return array $response Respuesta con la estructura de datos de los países.
 * -HasError: booleano, indica si hubo un error.
 * -AlertType: string, tipo de alerta.
 * -AlertMessage: string, mensaje de alerta.
 * -ModelErrors: array, errores del modelo.
 * -Data: array, datos de los países.
 *   -Id: int, identificador del país.
 *   -Name: string, nombre del país.
 *   -departments: array, departamentos del país.
 *     -Id: int, identificador del departamento.
 *     -Name: string, nombre del departamento.
 *     -cities: array, ciudades del departamento.
 *       -Id: int, identificador de la ciudad.
 *       -Name: string, nombre de la ciudad.
 *   -currencies: array, monedas del país.
 *     -Id: string, identificador de la moneda.
 *     -Name: string, nombre de la moneda.
 *
 * @throws Exception Si ocurre un error durante la consulta.
 */


/* ajusta el límite de memoria y define dos arreglos vacíos. */
ini_set('memory_limit', '-1');


$arrayf = [];

$array = [];


/* Se agrega un país y su identificador a un arreglo dinámico en PHP. */
$array["Id"] = 173;
$array["Name"] = "Peru";
array_push($arrayf, $array);

$array = [];

$array["Id"] = 2;

/* Se crea un arreglo con el nombre "Nicaragua" y se añade a otro arreglo. */
$array["Name"] = "Nicaragua";
array_push($arrayf, $array);

$Pais = new Pais();

$SkeepRows = 0;

/* Define un límite de filas y establece reglas de filtrado para datos específicos. */
$MaxRows = 10000000000000;

$rules = [];

array_push($rules, array("field" => "pais.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


/* Agrega reglas a un array según el perfil de usuario en sesión. */
if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
    array_push($rules, array("field" => "pais.pais_id", "data" => $_SESSION["pais_id"], "op" => "eq"));

}

if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
    array_push($rules, array("field" => "pais.pais_id", "data" => $_SESSION["pais_id"], "op" => "eq"));

}


/* Condición que agrega una regla si el perfil de sesión es "CONCESIONARIO3". */
if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
    array_push($rules, array("field" => "pais.pais_id", "data" => $_SESSION["pais_id"], "op" => "eq"));

}

$mandanteEspecifico = '';

/* Asigna $mandanteEspecifico según condiciones en la sesión. */
if ($_SESSION['Global'] == "N") {
    $mandanteEspecifico = $_SESSION['mandante'];
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        $mandanteEspecifico = $_SESSION["mandanteLista"];
    }

}


// Si el usuario esta condicionado por País

/* Crea un filtro JSON basado en la sesión del usuario y reglas definidas. */
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "pais.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


/* Código que obtiene y decodifica información de países, departamentos y ciudades en formato JSON. */
$paises = $Pais->getPaises("pais_moneda.pais_id,departamento.depto_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true, $mandanteEspecifico);

$paises = json_decode($paises);

$final = [

];

/* inicializa arrays vacíos para almacenar monedas, ciudades y departamentos. */
$arrayf = [];
$monedas = [];

$ciudades = [];
$departamentos = [];

/* foreach ($paises->data as $key => $value) {

     $array = [];

     $array["Id"] = $value->{"pais.pais_id"};
     $array["Name"] = $value->{"pais.pais_nom"};

     $departamento_id = $value->{"departamento.depto_id"};
     $departamento_texto = $value->{"departamento.depto_nom"};

     $ciudad_id = $value->{"ciudad.ciudad_id"};
     $ciudad_texto = $value->{"ciudad.ciudad_nom"};

     if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {


         $departamento = [];
         $departamento["Id"] = $departamento_idf;
         $departamento["Name"] = $departamento_textof;
         $departamento["cities"] = $ciudades;

         array_push($departamentos, $departamento);


         $arrayf["currencies"] = array_unique($monedas);
         $arrayf["departments"] = $departamentos;

         array_push($final, $arrayf);


         $arrayf = [];
         $monedas = [];
         $departamentos = [];
         $ciudades = [];

     }

     if ($departamento_idf != $departamento_id && $departamento_idf != "") {

         if ($array["Id"] == $arrayf["Id"]) {

             $departamento = [];
             $departamento["Id"] = $departamento_idf;
             $departamento["Name"] = $departamento_textof;
             $departamento["cities"] = $ciudades;

             array_push($departamentos, $departamento);
         }

         $ciudades = [];

         $ciudad = [];
         $ciudad["Id"] = $ciudad_id;
         $ciudad["Name"] = $ciudad_texto;

         array_push($ciudades, $ciudad);

     } else {
         $ciudad = [];
         $ciudad["Id"] = $ciudad_id;
         $ciudad["Name"] = $ciudad_texto;

         array_push($ciudades, $ciudad);
     }


     $arrayf["Id"] = $value->{"pais.pais_id"};
     $arrayf["Name"] = $value->{"pais.pais_nom"};

     $moneda = [];
     $moneda["Id"] = $value->{"pais_moneda.moneda"};
     $moneda["Name"] = $value->{"pais_moneda.moneda"};

     array_push($monedas, $moneda);


     $departamento_idf = $value->{"departamento.depto_id"};
     $departamento_textof = $value->{"departamento.depto_nom"};

 }

 /* foreach ($paises->data as $key => $value) {


      $searchedValue = $value->{"pais.pais_id"};
      $Country = reset(array_filter(
          $final,
          function ($e) use (&$searchedValue) {
              return $e["Id"] == $searchedValue;
          }
      ));

      if ($Country == null || $Country["Id"] == null || $Country["Id"] == "") {
          $arrayff = [];
          $arrayff["Id"] = $value->{"pais.pais_id"};
          $arrayff["Name"] = $value->{"pais.pais_nom"};
          $arrayff["currencies"] = array();
          $arrayff["departments"] = array();
          array_push($final, $arrayf);

          $Country = reset(array_filter(
              $final,
              function ($e) use (&$searchedValue) {
                  return $e["Id"] == $searchedValue;
              }
          ));

      }



      $searchedValue = $value->{"departamento.depto_id"};
      $Departament = reset(array_filter(
          $Country["departments"],
          function ($e) use (&$searchedValue) {
              return $e["Id"] == $searchedValue;
          }
      ));

      if ($Departament == null || $Departament["Id"] == null || $Departament["Id"] == "") {
          $arrayff = [];
          $arrayff["Id"] = $value->{"departamento.depto_id"};
          $arrayff["Name"] = $value->{"departamento.depto_nom"};
          $arrayff["cities"] = array();
          array_push($Country["departments"], $arrayf);

          $Departament = reset(array_filter(
              $Country["departments"],
              function ($e) use (&$searchedValue) {
                  return $e["Id"] == $searchedValue;
              }
          ));

      }
      $arrayff = [];
      $arrayff["Id"] = $value->{"ciudad.ciudad_id"};
      $arrayff["Name"] = $value->{"ciudad.ciudad_nom"};
      array_push($Departament["cities"], $arrayf);
  }*/
/*
            $departamento = [];
            $departamento["Id"] = $departamento_idf;
            $departamento["Name"] = $departamento_textof;
            $departamento["cities"] = $ciudades;

            array_push($departamentos, $departamento);

            $ciudades = [];

            array_push($monedas, $moneda);
            $arrayf["currencies"] = array_unique($monedas);
            $arrayf["departments"] = $departamentos;

            array_push($final, $arrayf);

            */


/* Organiza datos de países, departamentos y ciudades en un arreglo estructurado. */
foreach ($paises->data as $key => $value) {

    $arrayf[$value->{"pais.pais_id"}]["Name"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["Name"] = $value->{"departamento.depto_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["Name"] = $value->{"ciudad.ciudad_nom"};
    $arrayf[$value->{"pais.pais_id"}]["currencies"][$value->{"pais_mandante.moneda"}] = $value->{"pais_mandante.moneda"};

}

foreach ($arrayf as $key => $value) {

    /* Código en PHP que crea un array asociativo para representar un país y sus propiedades. */
    $pais = array(
        "Id" => $key,
        "Name" => $arrayf[$key]["Name"],
        "departments" => array(),
        "currencies" => array()
    );


    /* Itera sobre las monedas, agregándolas a un array asociado a "pais". */
    foreach ($arrayf[$key]["currencies"] as $currencyId => $currency) {
        array_push($pais["currencies"], array(
            "Id" => $currencyId,
            "Name" => $currency

        ));

    }


    /* organiza departamentos y ciudades en un arreglo estructurado. */
    foreach ($arrayf[$key]["departments"] as $deptoId => $depto) {
        $deptoObj = array(
            "Id" => $deptoId,
            "Name" => $depto["Name"],
            "cities" => array()
        );
        foreach ($arrayf[$key]["departments"][$deptoId]["cities"] as $cityId => $city) {
            array_push($deptoObj["cities"], array(
                "Id" => $cityId,
                "Name" => $city["Name"]

            ));
        }
        array_push($pais["departments"], $deptoObj);

    }

    /* Añade el valor de $pais al final del array $final en PHP. */
    array_push($final, $pais);

}


/* define una respuesta estructurada sin errores, con datos finales adjuntos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
