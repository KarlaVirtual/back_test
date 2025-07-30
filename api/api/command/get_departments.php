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
 * Código para obtener y organizar información de países, departamentos, ciudades y códigos postales.
 */

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


// Crear una nueva instancia de la clase Pais
$Pais = new Pais();

$SkeepRows = 0; // Número de filas a saltar
$MaxRows = 1000000; // Número máximo de filas a procesar

$rules = []; // Array para almacenar las reglas de filtrado

// Verificar si el usuario de la sesión no está vacío
if ($json->session->usuario != "") {
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    array_push($rules, array("field" => "pais.pais_id", "data" => "$Usuario->paisId", "op" => "eq"));

}

// Crear un filtro con las reglas y la operación de grupo
$filtro = array("rules" => $rules, "groupOp" => "AND");

// Codificar el filtro a formato JSON
$json2 = json_encode($filtro);

/*$paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);

$paises = json_decode($paises);*/

//Solicitud consulta personalizada
$paises = $Pais->getPaisesCodigosPostales("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, true);

$paises = json_decode($paises);

$final = [];
$arrayf = [];
$monedas = [];

$ciudades = [];
$departamentos = [];


foreach ($paises->data as $key => $value) {

    $arrayf[$value->{"pais.pais_id"}]["Name"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["Code2"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["Name"] = $value->{"departamento.depto_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["Name"] = $value->{"ciudad.ciudad_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["postalCodes"][$value->{"codigo_postal.codigopostal_id"}]["Name"] = $value->{"codigo_postal.codigo_postal"};
    $arrayf[$value->{"pais.pais_id"}]["currencies"][$value->{"pais_moneda.moneda"}] = $value->{"pais_moneda.moneda"};

}
/**
 * Itera sobre un arreglo de países y construye una estructura compleja con información organizada
 * sobre cada país, sus departamentos, ciudades y códigos postales.
 *
 * @param array $arrayf Arreglo de datos de países, donde cada país contiene información sobre su nombre,
 *                      departamentos, monedas y más.
 * @param array $final Arreglo donde se almacenarán los países procesados.
 */
foreach ($arrayf as $key => $value) {
    $pais = array(
        "Id" => $key,
        "Name" => $arrayf[$key]["Name"],
        "departments" => array(),
        "currencies" => array()
    );

    foreach ($arrayf[$key]["currencies"] as $currencyId => $currency) {
        array_push($pais["currencies"], array(
            "Id" => $currencyId,
            "Name" => $currency

        ));

    }
    foreach ($arrayf[$key]["departments"] as $deptoId => $depto) {
        $deptoObj = array(
            "Id" => $deptoId,
            "Name" => $depto["Name"],
            "cities" => array()
        );
        foreach ($arrayf[$key]["departments"][$deptoId]["cities"] as $cityId => $city) {

            $cityObj = array(
                "Id" => $cityId,
                "Name" => $city["Name"],
                "postalCodes" => array()

            );

            foreach ($arrayf[$key]["departments"][$deptoId]["cities"][$cityId]["postalCodes"] as $postalCodeId => $postalCode) {
                array_push($cityObj["postalCodes"], array(
                    "Id" => $postalCodeId,
                    "Name" => $postalCode["Name"]

                ));
            }

            array_push($deptoObj["cities"], $cityObj);


        }
        array_push($pais["departments"], $deptoObj);

    }
    array_push($final, $pais);

}
/**
 * Inicializa la variable $departamentos con el valor de "departments"
 * que se encuentra en el primer elemento del array $final.
 */
$departamentos= $final[0]["departments"];

$response["data"] = $final;


$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $departamentos;
