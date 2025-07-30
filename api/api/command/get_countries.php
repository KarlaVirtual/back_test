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
 * Obtiene los países basados en el ID del sitio y otros parámetros.
 *
 * @param int $json->params->site_id ID del sitio.
 * @throws Exception Si ocurre un error durante la ejecución.
 * @return array
 *  -code:int Código de respuesta.
 *  -rid:int ID de respuesta.
 *  -data:array Arreglo de países.
 *      -Id:int ID del país.
 *      -Name:string Nombre del país.
 *      -Cities:array Arreglo de ciudades.
 */

ini_set('memory_limit', '-1');

// Se obtiene el ID del sitio en minúsculas desde los parámetros del JSON
$site_id = strtolower($json->params->site_id);

// Se inicializan las clases Mandante y Pais
$Mandante = new Mandante($site_id);
$Pais = new Pais();

// Se definen las variables para el control de filas
$SkeepRows = 0;
$MaxRows = 1000000;

// Se crea un JSON que contiene las reglas de filtrado
$json2 = '{"rules" : [{"field" : "pais_mandante.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

// Se inicializa un array para las reglas
$rules = [];
// Se añaden las reglas al array
array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "pais_mandante.mandante", "data"=> "$site_id","op"=>"eq"));

// Si el ID del sitio es '0', se podrían agregar reglas adicionales
if($site_id == '0'){
   //array_push($rules, array("field" => "pais.pais_id", "data" => "66", "op" => "ne"));
   //array_push($ules, array("field" => "pais.pais_id", "data" => "68", "op" => "ne"));

}

// Se crea un filtro a partir de las reglas
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$ConfigurationEnvironment = new ConfigurationEnvironment();

// Se obtienen los países basándose en si la variable isPanama es '1' o no
if($json->isPanama == '1'){
    $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id,pais.iso", "asc", $SkeepRows, $MaxRows, $json2, true,$Mandante->mandante);
}else{
    $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id,pais.iso", "asc", $SkeepRows, $MaxRows, $json2, true,$Mandante->mandante);
}

// Se decodifica el JSON obtenido en el paso anterior
$paises = json_decode($paises);

// Se inicializan arrays para almacenar los resultados finales
$final = [];
$arrayf = [];
$monedas = [];

// Se inicializan arrays para ciudades y departamentos
$ciudades = [];
$departamentos = [];
foreach ($paises->data as $key => $value) {
    /*Asignación valores de la respuesta*/
    if($site_id == 21){
        if($value->{"pais.pais_id"} == '232'){
            $value->{"pais.pais_nom"}='Venezuela - VES';
        }
        if($value->{"pais.pais_id"} == '243'){
            $value->{"pais.pais_nom"}='Venezuela - USD';
        }
    }
    $arrayf[$value->{"pais.pais_id"}]["Name"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["Code2"] = $value->{"pais.pais_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["Name"] = $value->{"departamento.depto_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["Name"] = $value->{"ciudad.ciudad_nom"};
    $arrayf[$value->{"pais.pais_id"}]["departments"][$value->{"departamento.depto_id"}]["cities"][$value->{"ciudad.ciudad_id"}]["postalCodes"][$value->{"codigo_postal.codigopostal_id"}]["Name"] = $value->{"codigo_postal.codigo_postal"};
    $arrayf[$value->{"pais.pais_id"}]["currencies"][$value->{"pais_moneda.moneda"}] = $value->{"pais_moneda.moneda"};
    $arrayf[$value->{"pais.pais_id"}]["Iso"] = $value->{"pais.iso"};
}



foreach ($arrayf as $key => $value) {
    //Adición de información de monedas
    $pais = array(
        "Id" => $key,
        "Name" => str_replace(',','',str_replace('&','',$arrayf[$key]["Name"])),
        "Iso" => strtolower($arrayf[$key]["Iso"]),
        "departments" => array(),
        "currencies" => array()
    );

    foreach ($arrayf[$key]["currencies"] as $currencyId => $currency) {
        array_push($pais["currencies"], array(
            "Id" => $currencyId,
            "Name" => $currency

        ));

    }

    /*Adición información geográfica para la locación solicitada*/
    foreach ($arrayf[$key]["departments"] as $deptoId => $depto) {
        $deptoObj = array(
            "Id" => $deptoId,
            "Name" => str_replace(',',' ',str_replace('&','',$depto["Name"])),
            "cities" => array()
        );
        foreach ($arrayf[$key]["departments"][$deptoId]["cities"] as $cityId => $city) {

            $cityObj = array(
                "Id" => $cityId,
                "Name" => str_replace(',',' ',str_replace('&','',$city["Name"])),
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


$regiones = $final;

//Formateo de respuesta
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

