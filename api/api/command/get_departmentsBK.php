<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Recurso que obtiene la información de países, departamentos, ciudades y códigos postales.
 *
 * @param int $json->session->usuario ID del usuario que opera la sesión.
 *
 * @return array Respuesta en formato JSON:
 * - code (int) Código de respuesta.
 * - rid (int) ID de la transacción.
 * - data (array) Datos organizados de países:
 *   - Id (int) ID del país.
 *   - Name (string) Nombre del país.
 *   - departments (array) Lista de departamentos:
 *     - Id (int) ID del departamento.
 *     - Name (string) Nombre del departamento.
 *     - cities (array) Lista de ciudades:
 *       - Id (int) ID de la ciudad.
 *       - Name (string) Nombre de la ciudad.
 *       - postalCodes (array) Lista de códigos postales:
 *         - Id (int) ID del código postal.
 *         - Name (string) Código postal.
 *   - currencies (array) Lista de monedas:
 *     - Id (int) ID de la moneda.
 *     - Name (string) Nombre de la moneda.
 */

/*El código obtiene información de países, departamentos, ciudades y códigos postales, filtrando por el ID del usuario de la sesión, y organiza estos datos en una estructura JSON para su respuesta.*/
$Pais = new Pais();

$SkeepRows = 0;
$MaxRows = 1000000;

$rules = [];


if ($json->session->usuario != "") {
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    array_push($rules, array("field" => "pais.pais_id", "data" => "$Usuario->paisId", "op" => "eq"));

}

/*Generación de consulta dinámica para la obtención de paises*/
$filtro = array("rules" => $rules, "groupOp" => "AND");

$json2 = json_encode($filtro);

$paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);

$paises = json_decode($paises);

$final = [];
$arrayf = [];
$monedas = [];

$ciudades = [];
$departamentos = [];

foreach ($paises->data as $key => $value) {
    /*Almacenamiento de paises en objetos para envío de respuesta*/

    $array = [];

    $array["Id"] = $value->{"pais.pais_id"};
    $array["Name"] = $value->{"pais.pais_nom"};

    $departamento_id = $value->{"departamento.depto_id"};
    $departamento_texto = $value->{"departamento.depto_nom"};

    $ciudad_id = $value->{"ciudad.ciudad_id"};
    $ciudad_texto = $value->{"ciudad.ciudad_nom"};

    if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {
        /*Correcciones en el objeto en caso de no coincidir con el ID del array original*/
        $arrayf["currencies"] = array_unique($monedas);
        $arrayf["departments"] = $departamentos;
        array_push($final, $arrayf);

        $arrayf = [];
        $monedas = [];
        $departamentos = [];
        $ciudades = [];

    }

    /*Asignación de país y moneda en objetos de respuesta*/
    $arrayf["Id"] = $value->{"pais.pais_id"};
    $arrayf["Name"] = $value->{"pais.pais_nom"};

    $moneda = [];
    $moneda["Id"] = $value->{"pais_moneda.paismoneda_id"};
    $moneda["Name"] = $value->{"pais_moneda.moneda"};

    array_push($monedas, $moneda);

    if ($departamento_idf != $departamento_id && $departamento_idf != "") {
        /*Readecuaciones ciudades vinculadas a un departamento*/
        $departamento = [];
        $departamento["Id"] = $departamento_idf;
        $departamento["Name"] = $departamento_textof;
        $departamento["cities"] = $ciudades;

        array_push($departamentos, $departamento);

        $ciudades = [];

        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;


    } else {
        /*Caso de coincidencia entre departamento_idf y departamento_id */
        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;

        $ciudad["postalCodes"] = array();


        $postalarray = array();
        $postalarray["Id"] = "1";
        $postalarray["Name"] = "Global";

        array_push($ciudad["postalCodes"], $postalarray);

        /*
    $codigopostales = file_get_contents('https://www.datos.gov.co/resource/krpp-ufw8.json?$select=codigo_postal,barrios_contenidos_en_el_codigo_postal&$where=nombre_municipio="' . strtoupper( $ciudad_texto).'"');



    foreach ($codigopostales as $codigopostale) {
        $postalarray = array();
        $postalarray["Id"] = $codigopostales.codigo_postal;
        $postalarray["Name"] = $codigopostales.barrios_contenidos_en_el_codigo_postal;

        array_push($ciudad["postalCodes"],$postalarray);
    }
*/

        array_push($ciudades, $ciudad);
    }

    $departamento_idf = $value->{"departamento.depto_id"};
    $departamento_textof = $value->{"departamento.depto_nom"};

}

//Construcción final objeto de departamento
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

$regiones = $final;


$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    array(
        "Id" => "1",
        "Name" => "Colombia",
        "departments" =>
            array(
                "id" => 5,
                "name" => "Antioquia",
                "cities" => array(
                    array(
                        "id" => "551",
                        "name" => "edellin"
                    )
                )
            )
    )

);

$response["data"] = $final;

//Construcción respuesta final
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $departamentos;
