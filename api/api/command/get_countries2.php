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
 * Obtiene una lista de países y los filtra según ciertas condiciones.
 *
 * Se utilizan las clases y métodos de la clase Pais para obtener información
 * sobre los países. Los resultados son almacenados en un arreglo para su
 * posterior uso.
 *
 * @param string $site_id ID del sitio
 * @return array Arreglo con la información de los países
 *  -code:int Código de respuesta
 *  -rid:string ID de la solicitud
 *  -data:array Arreglo con la información de los países
 *      -Id:string ID del país
 *      -Name:string Nombre del país
 *      -departments:array Arreglo con la información de los departamentos
 */

// Convierte el site_id a minúsculas
$site_id = strtolower($json->params->site_id);

/*Definición requisitos de consulta*/
$Pais = new Pais();
$SkeepRows = 0;
$MaxRows = 1000000;

$json2 = '{"rules" : [] ,"groupOp" : "AND"}';
$rules = [];

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

/*$paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json2, false);

$paises = json_decode($paises);*/

/*Obtención dinámica de los países respecto a los filtros requeridos*/
$paises2 = $Pais->getPaisesCustom2("pais.pais_nom", "asc", $SkeepRows, $MaxRows, $json2, false);

$paises2 = json_decode($paises2);

$final = [];
$final2 = [];
$arrayf = [];
$monedas = [];

$ciudades = [];
$departamentos = [];

foreach ($paises2->data as $key => $value) { // Itera sobre los datos de países

    if($site_id == 21){ // Verifica si el site_id es 21
        if($value->{"pais.pais_id"} == '232'){ // Verifica si el país es 232
        }
        if($value->{"pais.pais_id"} == '243'){  // Verifica si el país es 243
            continue;  // Omite el país si es 243
        }
    }
    $pais = array( // Crea un arreglo para el país
        "Id" => $value->{"pais.pais_id"}, // Asigna el ID del país
        "Name" => $value->{"pais.pais_nom"}, // Asigna el nombre del país
        "departments" => array(), // Inicializa el arreglo de departamentos
        "currencies" => array() // Inicializa el arreglo de monedas
    );

    array_push($final2, $pais); // Agrega el país al arreglo final
}

/*foreach ($paises->data as $key => $value) {

    $arrayf[$value->{"pais.pais_id"}]["Name"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["Code2"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["Name"] = $value->{"departamento.depto_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["Name"] = $value->{"ciudad.ciudad_nom"};
    $arrayf[$value->{"pais.pais_id"}]["currencies"][$value->{"pais_moneda.moneda"}] = $value->{"pais_moneda.moneda"};

}

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
            array_push($deptoObj["cities"], array(
                "Id" => $cityId,
                "Name" => $city["Name"],
                "postalCodes" => array()

            ));
        }
        array_push($pais["departments"], $deptoObj);

    }
    array_push($final, $pais);

}*/


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

/*Formateo de respuesta*/
$response["data2"] = $final;
$response["data"] = $final2;

