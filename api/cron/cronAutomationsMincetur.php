<?php
error_reporting(E_ERROR);
ini_set('display_errors', 'OFF');
ini_set('max_execution_time', 0);

require(__DIR__ . '/../vendor/autoload.php');

use Backend\dto\BonoInterno;
use \CurlWrapper;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\sql\Transaction;
use Backend\dto\MandanteDetalle;
use Backend\dto\Clasificador;
use Backend\dto\Usuario;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\dto\UsuarioLog;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\UsuarioRestriccion;
use Backend\mysql\UsuarioRestriccionMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Template;
use Backend\dto\Helpers;

/** Inactivación usuarios presentes en la lista de ludópatas Mincetur  */

$executionWithErrors = false;
$ConfigurationEnvironment = new ConfigurationEnvironment();

/** Verificando última ejecución */
//NOTA: Proceso SÓLO se ejecuta 1 vez al día
$BonoInterno = new BonoInterno();
$lastExecutionCheckLudopatiaQuery = "select fecha_ultima from proceso_interno2 where tipo = 'LISTA_LUDOPATIA_MINCETUR'";
$lastExecutionCheckLudopatiaResponse = $BonoInterno->execQuery("", $lastExecutionCheckLudopatiaQuery);

if (count($lastExecutionCheckLudopatiaResponse) < 1) throw new Exception('No existe proceso_interno2', 300062);
else {
    $lastExecutionCheckLudopatia = $lastExecutionCheckLudopatiaResponse[0]->{'proceso_interno2.fecha_ultima'};
}

$lastDateCheckLudopatia = date('Y-m-d', strtotime($lastExecutionCheckLudopatia));
$currentDate = date("Y-m-d");
if ($currentDate <= $lastDateCheckLudopatia) throw new Exception('Ejecucion fuera de rango de fecha', 300063);

try {
    /** Consultando partners que requieren revisión de la lista negra de Mincetur */
    $Clasificador = new Clasificador('', 'MINCETUR_BLACK_LIST');
    $ClasificadorTemplate = new Clasificador('', 'TMPMINCETURBLACKLIST');
    $partner = 0;
    $country = 173;
    $partnersRequireCheckLudopatiaTuples = "'{$partner}_{$country}'";
    $restrictionsCreationMode = 'AUTOMATION';


    if ($ConfigurationEnvironment->isProduction()) {
        /** Solicitando listado a Mincetur */
        $apiMincetur = "https://wsel.mincetur.gob.pe/wsludopatia/api/RegistroLudopatia/a9d1358fc10de9aecb744955694d204f/consultarRegistroLudopatia";
        $curl = new CurlWrapper($apiMincetur);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $apiMincetur,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
            ),
        ));
        $minceturResponse = $curl->execute();
        $minceturResponse = json_decode($minceturResponse);
    }
    else {
        //Listado de documentos para pruebas en desarrollo
        $minceturResponse = '{
  "fecSincronizacion": "'. date('Y/m/d H:i:s') .'",
  "IdTipo": 0,
  "IdError": null,
  "DesError": null,
  "Valor": "2669615",
  "mensaje": "La información del Registro es confidencial, y su trasgresión está sujeta a responsabilidades administrativas (Ley N° 29907)",
  "lstEnLudopatia": [
    {
      "codRegistro": "10152",
      "nombres": "David",
      "apePater": "Torres",
      "apeMater": "Rendon",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "4213914",
      "nombresContacto": "Tomás Angelo",
      "apePaterContacto": "Pantigoso",
      "apeMaterContacto": "Napanga",
      "numeroTelfContacto": ".",
      "numeroTelf1Contacto": "987 776 303",
      "fecPublicacion": "20161108"
    },
    {
      "codRegistro": "11972",
      "nombres": "MARIO",
      "apePater": "JAILLITA",
      "apeMater": "FERNANDEZ",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "00430776",
      "nombresContacto": "Sandra Sonasa",
      "apePaterContacto": "Jaillita",
      "apeMaterContacto": "Vicente",
      "numeroTelfContacto": "931375289",
      "numeroTelf1Contacto": null,
      "fecPublicacion": "20230529"
    },
    {
      "codRegistro": "1001",
      "nombres": "JOSE WILLIAM",
      "apePater": "SANDOVAL",
      "apeMater": "PEREZ",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "40707410",
      "nombresContacto": "Flor De María",
      "apePaterContacto": "Perez",
      "apeMaterContacto": "Sandoval De Sandoval",
      "numeroTelfContacto": "(074) 634 - 192",
      "numeroTelf1Contacto": "978764896/979062816",
      "fecPublicacion": "20161031"
    },
    {
      "codRegistro": "12868",
      "nombres": "PIERO LEONIDAS",
      "apePater": "GAMBINI",
      "apeMater": "ORIHUELA",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "47142889",
      "nombresContacto": "Carmen Haydee",
      "apePaterContacto": "Orihuela",
      "apeMaterContacto": "Valenzuela",
      "numeroTelfContacto": "945861635",
      "numeroTelf1Contacto": null,
      "fecPublicacion": "20241003"
    },
    {
      "codRegistro": "11512",
      "nombres": "JORGE LUIS",
      "apePater": "VARGAS",
      "apeMater": "VELAZCO",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "24365688",
      "nombresContacto": "Franklin",
      "apePaterContacto": "Vargas",
      "apeMaterContacto": "Velazco",
      "numeroTelfContacto": "927935167",
      "numeroTelf1Contacto": null,
      "fecPublicacion": "20220929"
    },
    {
      "codRegistro": "11007",
      "nombres": "CARMEN TEÓFILA",
      "apePater": "ARANA",
      "apeMater": "ESCOBEDO",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "07374636",
      "nombresContacto": "Gary Alexis",
      "apePaterContacto": "Grentz",
      "apeMaterContacto": "Arana",
      "numeroTelfContacto": "972711621",
      "numeroTelf1Contacto": "01-3610531",
      "fecPublicacion": "20200224"
    },
    {
      "codRegistro": "10344",
      "nombres": "ROLANDO",
      "apePater": "TULLUME",
      "apeMater": "ESPINOZ",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "16679595",
      "nombresContacto": "Edgar Hugo",
      "apePaterContacto": "Macedo",
      "apeMaterContacto": "Carrillo",
      "numeroTelfContacto": "074498688",
      "numeroTelf1Contacto": "979994473",
      "fecPublicacion": "20180615"
    },
    {
      "codRegistro": "11759",
      "nombres": "JOSE ANTONIO AGUSTIN",
      "apePater": "MARTINEZ",
      "apeMater": "CADENILLAS",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "10542324",
      "nombresContacto": "Ricardo Humberto",
      "apePaterContacto": "Gastelo",
      "apeMaterContacto": "Escurra",
      "numeroTelfContacto": "242 - 7790",
      "numeroTelf1Contacto": "981 - 651 - 288",
      "fecPublicacion": "20230206"
    },
    {
      "codRegistro": "10247",
      "nombres": "JOSÉ MARTÍN",
      "apePater": "VELA",
      "apeMater": "ROJAS",
      "idTipoDocu": 1,
      "desTipoDocu": "DNI",
      "numDocu": "09431916",
      "nombresContacto": "Melissa Angelica",
      "apePaterContacto": "Villarán",
      "apeMaterContacto": "Queirolo",
      "numeroTelfContacto": ".",
      "numeroTelf1Contacto": "988 080 488",
      "fecPublicacion": "20170824"
    },
     {
            "codRegistro": "12900",
            "nombres": "ALEX RAFAEL",
            "apePater": "KOHATSU",
            "apeMater": "INAFUKU",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "07975669",
            "nombresContacto": "Flor De Maria",
            "apePaterContacto": "Kohatsu",
            "apeMaterContacto": "Inafuku",
            "numeroTelfContacto": "999910276",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241004"
        },
        {
            "codRegistro": "11222",
            "nombres": "ANTIO MIGUEL",
            "apePater": "ARMAS",
            "apeMater": "SUAREZ",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "41971371",
            "nombresContacto": "Katerin Verenisse",
            "apePaterContacto": "Gonzales",
            "apeMaterContacto": "Cotrina",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20211123"
        },
        {
            "codRegistro": "11229",
            "nombres": "CARLOS ENRIQUE",
            "apePater": "ALVAREZ",
            "apeMater": "BREGANTE",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "72942655",
            "nombresContacto": "Jose Martin",
            "apePaterContacto": "Alvarez",
            "apeMaterContacto": "Juarez",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20211129"
        },
        {
            "codRegistro": "11311",
            "nombres": "JULIO",
            "apePater": "ATENCIO",
            "apeMater": "CALLUCHE",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "10268160",
            "nombresContacto": "Johana Teresa Angie",
            "apePaterContacto": "Sosa",
            "apeMaterContacto": "Sanchez",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220308"
        },
        {
            "codRegistro": "11379",
            "nombres": "JOSE RAUL",
            "apePater": "ORE",
            "apeMater": "ÑAÑEZ",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "40034560",
            "nombresContacto": "Anita Irma",
            "apePaterContacto": "Huamani",
            "apeMaterContacto": "Taquiri",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220602"
        },
        {
            "codRegistro": "11382",
            "nombres": "CARLOS ENRIQUE",
            "apePater": "TORI",
            "apeMater": "REATEGUI",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "70439678",
            "nombresContacto": "Claudia Nicole",
            "apePaterContacto": "Bravo",
            "apeMaterContacto": "Romero",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220602"
        },
        {
            "codRegistro": "11461",
            "nombres": "ROGER",
            "apePater": "SALVADOR",
            "apeMater": "BEJARANO",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "09646153",
            "nombresContacto": "Lucia Lorena",
            "apePaterContacto": "Guillen",
            "apeMaterContacto": "Nolasco",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220901"
        },
        {
            "codRegistro": "11250",
            "nombres": "HERMES",
            "apePater": "ANGULO",
            "apeMater": "LA MADRID",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "46507560",
            "nombresContacto": "Kristel Noemi",
            "apePaterContacto": "Sifuentes",
            "apeMaterContacto": "Sifuentes",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220416"
        },
        {
            "codRegistro": "11683",
            "nombres": "LEONARDO FABIAN",
            "apePater": "OJEDA",
            "apeMater": "ROJAS",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "70691406",
            "nombresContacto": "Fernando Javier",
            "apePaterContacto": "Ojeda",
            "apeMaterContacto": "Del Carpio",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20221222"
        },
        {
            "codRegistro": "11630",
            "nombres": "LUIS LEONIDAS",
            "apePater": "ORTIZ",
            "apeMater": "TAPIA",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "43403496",
            "nombresContacto": "Jessica Karina",
            "apePaterContacto": "Quispe",
            "apeMaterContacto": "Muñoz",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20221201"
        },
        {
            "codRegistro": "11610",
            "nombres": "ALLISON GRETT",
            "apePater": "ARAPA",
            "apeMater": "ESPINOZA",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "47697980",
            "nombresContacto": "Dina",
            "apePaterContacto": "Fasabi",
            "apeMaterContacto": "Tuanama",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20221124"
        },
        {
            "codRegistro": "11475",
            "nombres": "ROJERIO AUSALON",
            "apePater": "PITA",
            "apeMater": "LOZANO",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "16693831",
            "nombresContacto": "Ana Thalia",
            "apePaterContacto": "Peralta",
            "apeMaterContacto": "Suich",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220912"
        },
        {
            "codRegistro": "11690",
            "nombres": "EDGAR",
            "apePater": "ZAMBRANO",
            "apeMater": "GONZALES",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "26707580",
            "nombresContacto": "Lidia",
            "apePaterContacto": "Zambrano",
            "apeMaterContacto": "Gonzales",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20221223"
        },
        {
            "codRegistro": "11416",
            "nombres": "CARLOS ALBERTO",
            "apePater": "ARRASCO",
            "apeMater": "SANDOVAL",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "16708953",
            "nombresContacto": "Marlene Esther",
            "apePaterContacto": "Arrasco",
            "apeMaterContacto": "Sandoval",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220705"
        },
        {
            "codRegistro": "11210",
            "nombres": "JUAN",
            "apePater": "COJAL",
            "apeMater": "BRIONES",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "44646950",
            "nombresContacto": "Segundo Romulo",
            "apePaterContacto": "Cojal",
            "apeMaterContacto": "Zambrano",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20211111"
        },
        {
            "codRegistro": "11707",
            "nombres": "ROSARIO MARIA",
            "apePater": "SARMIENTO",
            "apeMater": "CAPCHA",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "21136701",
            "nombresContacto": "Leemarvin Anderson",
            "apePaterContacto": "Bovis",
            "apeMaterContacto": "Sarmiento",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20230117"
        },
        {
            "codRegistro": "11200",
            "nombres": "TIBURCIO",
            "apePater": "ROJAS",
            "apeMater": "CHANINE",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "40451550",
            "nombresContacto": "Beatriz",
            "apePaterContacto": "Mendoza",
            "apeMaterContacto": "Pacho",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20211103"
        },
        {
            "codRegistro": "11693",
            "nombres": "MAURICIO",
            "apePater": "ZAVALA",
            "apeMater": "TORRES",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "73007682",
            "nombresContacto": "Luis Pablo",
            "apePaterContacto": "Martinez",
            "apeMaterContacto": "Veloz",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20221226"
        },
        {
            "codRegistro": "11224",
            "nombres": "JESUS ALESSANDRO",
            "apePater": "ALVARADO",
            "apeMater": "GONZALES",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "45560541",
            "nombresContacto": "Beatriz Angela",
            "apePaterContacto": "Malvaceda",
            "apeMaterContacto": "Pajuelo",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20211123"
        },
        {
            "codRegistro": "11299",
            "nombres": "GRISELDA OLGA",
            "apePater": "POLO",
            "apeMater": "POLO",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "08274855",
            "nombresContacto": "Maria Fernanda",
            "apePaterContacto": "Correa",
            "apeMaterContacto": "Polo",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220416"
        },
        {
            "codRegistro": "12433",
            "nombres": "YONGCONG",
            "apePater": "WEN",
            "apeMater": ".",
            "idTipoDocu": 2,
            "desTipoDocu": "CE",
            "numDocu": "001057885",
            "nombresContacto": "Yongcong",
            "apePaterContacto": "Wen",
            "apeMaterContacto": ".",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240208"
        },
        {
            "codRegistro": "11298",
            "nombres": "CESAR ALBERTO",
            "apePater": "PARIONA",
            "apeMater": "MILLAN",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "15439349",
            "nombresContacto": "Gabriela Liz",
            "apePaterContacto": "Pariona",
            "apeMaterContacto": "Coronado",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220221"
        },
        {
            "codRegistro": "11513",
            "nombres": "DAVID ISRRAEL",
            "apePater": "LEON",
            "apeMater": "FLORES",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "71778328",
            "nombresContacto": "Nataly Jimena",
            "apePaterContacto": "Farfan",
            "apeMaterContacto": "Guerra",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220929"
        },
        {
            "codRegistro": "11414",
            "nombres": "RYAN MARLON",
            "apePater": "PAZ",
            "apeMater": "RUIZ",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "40762575",
            "nombresContacto": "Evelyn Viviana",
            "apePaterContacto": "Almestar",
            "apeMaterContacto": "Lopez",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220705"
        },
        {
            "codRegistro": "11575",
            "nombres": "ALEX TOMAS",
            "apePater": "PEÑA",
            "apeMater": "ESPINOZA",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "72422417",
            "nombresContacto": "Jennifer Katherine De Los Milagros",
            "apePaterContacto": "Burneo",
            "apeMaterContacto": "Castro",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20221109"
        },
        {
            "codRegistro": "11228",
            "nombres": "ALVARO JUNIOR",
            "apePater": "RUIZ",
            "apeMater": "BURGA",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "42335379",
            "nombresContacto": "Angelita",
            "apePaterContacto": "Burga",
            "apeMaterContacto": "Carranza",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20211123"
        },
        {
            "codRegistro": "11660",
            "nombres": "JULIO CESAR",
            "apePater": "DIAZ",
            "apeMater": "RUIZ",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "26705826",
            "nombresContacto": "Cecilia Milagritos",
            "apePaterContacto": "Jauregui",
            "apeMaterContacto": "Lingan",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20221214"
        },
        {
            "codRegistro": "10957",
            "nombres": "JUAN ALEJANDRO",
            "apePater": "ESTELA",
            "apeMater": "ORRILLO",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "42455780",
            "nombresContacto": "Juan Alejandro",
            "apePaterContacto": "Estela",
            "apeMaterContacto": "Orrillo",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20200127"
        },
        {
            "codRegistro": "10956",
            "nombres": "ELADIA",
            "apePater": "ORRILLO",
            "apeMater": "SANCHEZ",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "48858132",
            "nombresContacto": "Juan Alejandro",
            "apePaterContacto": "Estela",
            "apeMaterContacto": "Orrillo",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20200127"
        },
        {
            "codRegistro": "11428",
            "nombres": "JOSE DAVID",
            "apePater": "ROIG",
            "apeMater": "CARBONEL",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "44749669",
            "nombresContacto": "Estefania Noelia",
            "apePaterContacto": "Roig",
            "apeMaterContacto": "Carbonel",
            "numeroTelfContacto": null,
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20220713"
        },
        {
            "codRegistro": "12860",
            "nombres": "SANDRA JISELA",
            "apePater": "CORCUERA",
            "apeMater": "MEL",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "40967169",
            "nombresContacto": "Teresa Silvana",
            "apePaterContacto": "Corcuerra",
            "apeMaterContacto": "Mel",
            "numeroTelfContacto": "977534696",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240913"
        },
        {
            "codRegistro": "12861",
            "nombres": "ROBERTO JORGE",
            "apePater": "NAVARRETE",
            "apeMater": "CABALLERO",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "09336817",
            "nombresContacto": "Guido Arturo",
            "apePaterContacto": "Caballero",
            "apeMaterContacto": "Herrera",
            "numeroTelfContacto": "967243994",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240913"
        },
        {
            "codRegistro": "12862",
            "nombres": "LEONIDAS PERICLES",
            "apePater": "GOMERO",
            "apeMater": "RIOS",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "41044582",
            "nombresContacto": "Geraldine Zenorina",
            "apePaterContacto": "Gomero",
            "apeMaterContacto": "Vasquez",
            "numeroTelfContacto": "980576833",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240913"
        },
        {
            "codRegistro": "12865",
            "nombres": "HUI HUA",
            "apePater": "TAN",
            "apeMater": "ZHU",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "42725980",
            "nombresContacto": "Jianan",
            "apePaterContacto": "Lli",
            "apeMaterContacto": "-",
            "numeroTelfContacto": "926300173",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241003"
        },
        {
            "codRegistro": "12867",
            "nombres": "ROGEL",
            "apePater": "SHUÑA",
            "apeMater": "GRANDES",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "40860882",
            "nombresContacto": "Diva Araceli",
            "apePaterContacto": "Carrasco",
            "apeMaterContacto": "Yamunaque",
            "numeroTelfContacto": "933313281",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240925"
        },
        {
            "codRegistro": "12872",
            "nombres": "LEOPOLDO DANTE",
            "apePater": "SILVA",
            "apeMater": "PAISIG",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "46231579",
            "nombresContacto": "Enrique Joel",
            "apePaterContacto": "Segales",
            "apeMaterContacto": "Lopez",
            "numeroTelfContacto": "968119133",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240925"
        },
        {
            "codRegistro": "12869",
            "nombres": "LUIS ANDRES",
            "apePater": "GARCIA",
            "apeMater": "BENITES",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "45934084",
            "nombresContacto": "Garcia",
            "apePaterContacto": "Julia Estefany",
            "apeMaterContacto": "Valera",
            "numeroTelfContacto": "970308989",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241003"
        },
        {
            "codRegistro": "12870",
            "nombres": "GIANCARLO",
            "apePater": "LLONTOP",
            "apeMater": "MAZZETTI",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "41051730",
            "nombresContacto": "Enice Ivette",
            "apePaterContacto": "Anton",
            "apeMaterContacto": "Rodriguez",
            "numeroTelfContacto": "986999173",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241001"
        },
        {
            "codRegistro": "12871",
            "nombres": "CARLOS HECTOR",
            "apePater": "BAILETTI",
            "apeMater": "CHIONG",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "41309088",
            "nombresContacto": "Enice Ivette",
            "apePaterContacto": "Anton",
            "apeMaterContacto": "Rodriguez",
            "numeroTelfContacto": "986999173",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240925"
        },
        {
            "codRegistro": "12873",
            "nombres": "EDUARDO ABEL",
            "apePater": "VEGAS",
            "apeMater": "MORETO",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "70482757",
            "nombresContacto": "Bettzabe Maira",
            "apePaterContacto": "Quiroz",
            "apeMaterContacto": "Izarra",
            "numeroTelfContacto": "986525788",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241001"
        },
        {
            "codRegistro": "12875",
            "nombres": "DAVID MARVIN",
            "apePater": "SEGOVIA",
            "apeMater": "CALDERON",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "76121791",
            "nombresContacto": "Maria Claudia Del Pilar",
            "apePaterContacto": "Munive",
            "apeMaterContacto": "Mendez",
            "numeroTelfContacto": "980478012",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240925"
        },
        {
            "codRegistro": "12876",
            "nombres": "NARDA LUCRECIA",
            "apePater": "PADILLA",
            "apeMater": "CUBAS DE MOGROVEJO",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "03846451",
            "nombresContacto": "Marco Antonio",
            "apePaterContacto": "Mogrovejo",
            "apeMaterContacto": "Valera",
            "numeroTelfContacto": "998731479",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240925"
        },
        {
            "codRegistro": "12874",
            "nombres": "GARY ALONSO",
            "apePater": "TALLEDO",
            "apeMater": "SALAS",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "76274847",
            "nombresContacto": "Maria Claudia Del Pilar",
            "apePaterContacto": "Munive",
            "apeMaterContacto": "Mendez",
            "numeroTelfContacto": "986357159",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240925"
        },
        {
            "codRegistro": "12878",
            "nombres": "OBED",
            "apePater": "CENTURION",
            "apeMater": "SUXE",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "45997707",
            "nombresContacto": "Carter",
            "apePaterContacto": "Centurion",
            "apeMaterContacto": "Suxe",
            "numeroTelfContacto": "921479616",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20240925"
        },
        {
            "codRegistro": "12879",
            "nombres": "JOSE LUIS",
            "apePater": "VARGAS",
            "apeMater": "TELLO",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "09672024",
            "nombresContacto": "Liliana Ivette",
            "apePaterContacto": "Vargas",
            "apeMaterContacto": "Tello De Chienda",
            "numeroTelfContacto": "947570175",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241001"
        },
        {
            "codRegistro": "12880",
            "nombres": "JUAN DIEGO",
            "apePater": "SOLORZANO",
            "apeMater": "BERNUY",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "74132755",
            "nombresContacto": "Cesarina Milagros",
            "apePaterContacto": "Bernuy",
            "apeMaterContacto": "Gutierrez",
            "numeroTelfContacto": "994607130",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241001"
        },
        {
            "codRegistro": "12881",
            "nombres": "ANDRES WILSON",
            "apePater": "VIDAL",
            "apeMater": "ROSALES",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "77670701",
            "nombresContacto": "Andy Wilson",
            "apePaterContacto": "Vidal",
            "apeMaterContacto": "Lopez",
            "numeroTelfContacto": "980411846",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241001"
        },
        {
            "codRegistro": "12883",
            "nombres": "EPIFANIO",
            "apePater": "CONCHA",
            "apeMater": "PERALTA",
            "idTipoDocu": 1,
            "desTipoDocu": "DNI",
            "numDocu": "46057930",
            "nombresContacto": "Oscar",
            "apePaterContacto": "Cordova",
            "apeMaterContacto": "Perez",
            "numeroTelfContacto": "919666393",
            "numeroTelf1Contacto": null,
            "fecPublicacion": "20241003"
        },
        {
         "codRegistro":"6456",
         "nombres":"Lucho",
         "apePater":"Zuluaga",
         "apeMater":"Betancur",
         "idTipoDocu":1,
         "desTipoDocu":"DNI",
         "numDocu":"78945612",
         "nombresContacto":"Eduardo",
         "apePaterContacto":"Elrick",
         "apeMaterContacto":"Restrepo",
         "numeroTelfContacto":"",
         "numeroTelf1Contacto":"987 776 303",
         "fecPublicacion":"20241105"
      },
      {
         "codRegistro":"666",
         "nombres":"Luis",
         "apePater":"Zuluaga",
         "apeMater":"Betancur",
         "idTipoDocu":1,
         "desTipoDocu":"DNI",
         "numDocu":"22321456",
         "nombresContacto":"Eduardo",
         "apePaterContacto":"Elrick",
         "apeMaterContacto":"Restrepo",
         "numeroTelfContacto":"",
         "numeroTelf1Contacto":"987 776 303",
         "fecPublicacion":"20241106"
      }
  ]
}';
        $minceturResponse = json_decode($minceturResponse);
    }


    //Almacenando respuesta Mincetur
    $Transaction = new Transaction();

    $minceturResponsePhoto = clone $minceturResponse;
    $minceturResponsePhoto->lstEnLudopatia = count($minceturResponse->lstEnLudopatia); //No se almacenan los ciudadanos enviados, solo la cantidad

    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->usuarioId = 0;
    $AuditoriaGeneral->usuarioIp = 0;
    $AuditoriaGeneral->estado = 'A';
    $AuditoriaGeneral->tipo = "LUDOPATIA_MINCETUR_AUTOMATION";
    $AuditoriaGeneral->data = json_encode($minceturResponsePhoto);

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

    $Transaction->commit();

    //Organizando usuarios por consultar
    $lstEnLudopatia = $minceturResponse->lstEnLudopatia;
    $citizensStack = [];
    $citizenslist = "";
    $citizensInStack = 0;
    $citizensPerStack = 60;
    foreach ($lstEnLudopatia as $citizen) {
        $citizen->numDocu = trim($citizen->numDocu);
        $citizen2=$citizen->numDocu;
        //Verificando que valor enviado sea seguro
        if (preg_match('#\W#', $citizen2)) continue;
        $Helpers = new Helpers();
        $citizen2 = $Helpers->encode_data_with_key($citizen2, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);

        //Almacenando DNI
        $citizenslist .= (empty($citizenslist) ? "" : ",") . "'{$citizen2}'";

        $citizensInStack++;

        if ($citizensInStack == $citizensPerStack) {
            $citizensStack[] = $citizenslist;
            $citizensInStack = 0;
            $citizenslist = "";
        }
    }
    if ($citizensInStack != 0) {
        //Almacenando ciudadanos que no fueron almacenados durante el foreach
        $citizensStack[] = $citizenslist;
    }

    /** Consultando usuarios por inactivar */
    $totalStacks = count($citizensStack);
    for ($i = 0; $i < $totalStacks; $i++) {
        $Transaction = new Transaction();
        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);
        $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO($Transaction);
        $citizensDni = null;
        $citizensDni = $citizensStack[$i]; //Listado documentos separados por comas
        $usersToNotify = [];

        try {
            $activeUsersInPlatformQuery = "SELECT mandante.descripcion, usuario.usuario_id, registro.cedula, registro.tipo_doc
            FROM usuario
            INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
            INNER JOIN mandante ON (usuario.mandante = mandante.mandante)
            LEFT JOIN usuario_restriccion ON (usuario_restriccion.usuario_id = usuario.usuario_id)
            WHERE registro.cedula in ({$citizensDni})
            AND usuario_restriccion.usurestriccion_id IS NULL
            AND CONCAT(usuario.mandante, '_', usuario.pais_id) in ({$partnersRequireCheckLudopatiaTuples}) AND usuario.estado = 'A'";

            $activeUsersInPlatformList = $BonoInterno->execQuery("", $activeUsersInPlatformQuery);

            foreach ($activeUsersInPlatformList as $registeredUser) {
                try {
                    //Si el caso SI coincide se procede con la inactivación del usuarios, el log y la restricción
                    $Usuario = new Usuario($registeredUser->{'usuario.usuario_id'});
                    $Usuario->estado = 'I';
                    $Usuario->contingencia = 'A';

                    $UsuarioLog = new UsuarioLog();
                    $UsuarioLog->usuarioId = $Usuario->getUsuarioId();
                    $UsuarioLog->tipo = 'LUDOPATIA_MINCETUR';
                    $UsuarioLog->estado = 'A';
                    $UsuarioLog->sversion = $restrictionsCreationMode;

                    $UsuarioRestriccion = new UsuarioRestriccion();
                    $UsuarioRestriccion->setUsuarioId($Usuario->usuarioId);
                    $UsuarioRestriccion->setEmail($Usuario->login);
                    $UsuarioRestriccion->setDocumento($registeredUser->{'registro.cedula'});
                    $UsuarioRestriccion->setTipoDoc($registeredUser->{'registro.tipo_doc'});
                    $UsuarioRestriccion->setNombre($Usuario->nombre);
                    $UsuarioRestriccion->setTelefono($Usuario->celular ?: '');
                    $UsuarioRestriccion->setMandante($Usuario->mandante);
                    $UsuarioRestriccion->setPaisId($Usuario->paisId);
                    $UsuarioRestriccion->setEstado('A');
                    $UsuarioRestriccion->setClasificadorId($Clasificador->clasificadorId);
                    $UsuarioRestriccion->setUsucreaId(0);
                    $UsuarioRestriccion->setUsumodifId(0);
                    $UsuarioRestriccion->setMetodo($restrictionsCreationMode);
                } catch (Exception $e) {
                    $executionWithErrors = true;
                    continue;
                }
                $UsuarioMySqlDAO->update($Usuario);
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioRestriccionMySqlDAO->insert($UsuarioRestriccion);

                /** Se almacena para posterior notificación al usuario */
                $Usuario->MandanteNombre = $registeredUser->{'mandante.descripcion'};
                $usersToNotify[$Usuario->idioma . '_' . $Usuario->paisId . '_' . $Usuario->mandante . '_' . $Usuario->usuarioId] = $Usuario;
            }
        } catch (Exception $e) {
            $executionWithErrors = true;
            $Transaction->rollback();
            continue;
        }

        //Almacenando cambios para el stack de usuarios actual
        $Transaction->commit();


        /** Notificando a los usuarios bloqueados */
        //Enviando notificación
        ksort($usersToNotify);
        $lastQueriedLanguageCountryPartner = null;
        $lastTemplateHTML = null;
        foreach($usersToNotify as $Usuario) {
            $userHTML = null;
            $currentLanguageCountryPartner = $Usuario->idioma . '_' . $Usuario->paisId . '_' . $Usuario->mandante;

            //Solicitando template a enviar
            if (empty($lastQueriedLanguageCountryPartner) || $currentLanguageCountryPartner != $lastQueriedLanguageCountryPartner) {
                $Template = new Template("", $Usuario->mandante, $ClasificadorTemplate->getClasificadorId(), $Usuario->paisId, $Usuario->idioma);
                $lastTemplateHTML = $Template->getTemplateHtml();
                $lastQueriedLanguageCountryPartner = $currentLanguageCountryPartner;
            }
            $userHTML = $lastTemplateHTML;

            //Personalizando template con la información del usuario
            $userHTML = str_replace("#fullname#", $Usuario->nombre, $userHTML);
            $userHTML = str_replace("#mandante#", $Usuario->MandanteNombre, $userHTML);

            //Definiendo asunto
            $subject = match (strtolower($Usuario->idioma)) {
                'es' => 'Información importante sobre tu saldo en #mandante#',
                'en' => 'Important info about your balance in #mandante#',
                'pt' => 'Información importante sobre tu saldo en #mandante#',
                default => 'Información importante sobre tu saldo en #mandante#'
            };
            $subject = str_replace('#mandante#', $Usuario->MandanteNombre, $subject);

            $finalSentStatus = $ConfigurationEnvironment->EnviarCorreoVersion2($Usuario->login, "", "", $subject, "", "", $userHTML, "", "", "", $Usuario->mandante, true);
        }
    }

    /** Actualizando tabla de usuario_restricción */
    //Filtrando sólo documentos pendientes de registro
    $lstEnLudopatiaDnis = array_column($lstEnLudopatia, 'numDocu');
    foreach ($citizensStack as $citizensIds) {
        //Consultando por los DNI que ya han sido registrados en la base de datos
        $alreadyRestrictedCitizensSql = "SELECT documento FROM usuario_restriccion WHERE mandante = {$partner} AND pais_id = {$country} AND documento IN ({$citizensIds})";
        $alreadyRestrictedCitizensResponse = $BonoInterno->execQuery("", $alreadyRestrictedCitizensSql);
        $alreadyRestrictedCitizensDnis = array_column($alreadyRestrictedCitizensResponse, 'usuario_restriccion.documento');

        //Verificando coincidencias con el fin de removerlas y evitar registros duplicados
        $dniCoincidences = array_intersect($lstEnLudopatiaDnis, $alreadyRestrictedCitizensDnis);
        $dniCoincidenceKeys = array_keys($dniCoincidences);
        foreach($dniCoincidenceKeys as $key) {
            $lstEnLudopatia[$key] = null;
        }
    }
    $lstEnLudopatia = array_filter($lstEnLudopatia);
    $lstEnLudopatia = array_values($lstEnLudopatia);


    //Registrando documentos sobrantes
    $newRestrictedUsers = "";
    $ConfigurationEnvironment = new ConfigurationEnvironment();
    foreach ($lstEnLudopatia as $citizen) {
        /** Registrando usuario en la base de datos de restricciones */
        //Verificando validez de la data por almacenar
        $citizen->desTipoDocu = $ConfigurationEnvironment->DepurarCaracteres($citizen->desTipoDocu);
        $citizen->nombres = $ConfigurationEnvironment->DepurarCaracteres($citizen->nombres);
        $citizen->apePater = $ConfigurationEnvironment->DepurarCaracteres($citizen->apePater);
        $citizen->apeMater = $ConfigurationEnvironment->DepurarCaracteres($citizen->apeMater);

        //Definiendo tipo de documento
        $docType = match(strtoupper($citizen->desTipoDocu)) {
            'DNI' => 'C',
            'PAS' => 'P',
            'CE' => 'E',
            default => 'C'
        };

        $citizenFullName = $citizen->nombres . " " . $citizen->apePater . " " . $citizen->apeMater;

        $newRestrictedUsers .= (empty($newRestrictedUsers) ? "" : ",") . "('', " . "'{$citizen->numDocu}' ," . "'{$docType}' ," . "'{$citizenFullName}' ," . "'' ,'' ,{$partner} ,{$country} ," . "'A' ," . "{$Clasificador->clasificadorId}, 0, 0, '{$restrictionsCreationMode}')";
    }

    $Transaction = new Transaction();
    $newRestrictedUsersSql = "INSERT INTO usuario_restriccion (email, documento, tipo_doc, nombre, telefono, nota, mandante, pais_id, estado, clasificador_id, usucrea_id, usumodif_id, metodo) VALUES " . $newRestrictedUsers;
    $BonoInterno->execUpdate($Transaction, $newRestrictedUsersSql);
    $Transaction->commit();
} catch (Exception $e) {
    $executionWithErrors = true;
} finally {
    $Transaction = new Transaction();
    $updateProcesoInterno = "UPDATE proceso_interno2 set fecha_ultima = NOW() WHERE tipo = 'LISTA_LUDOPATIA_MINCETUR'";
    $BonoInterno->execUpdate($Transaction, $updateProcesoInterno);
    $Transaction->commit();
}


?>
