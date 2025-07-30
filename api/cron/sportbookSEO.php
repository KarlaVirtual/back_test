<?php

use Backend\dto\ConfigurationEnvironment;
require(__DIR__.'/../vendor/autoload.php');

$URL_ITAINMENT = 'https://dataexport-uof-altenar.biahosted.com';


/**
 * Obtener las regiones de un deporte
 *
 * @param String $sport sport
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getRegions($sport, $fecha_inicial, $fecha_final)
{

    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();

    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                $item_data = array(
                    "Id" => $item2->CategoryId,
                    "Name" => $item2->Name
                );
                array_push($array, $item_data);
            }


        }

    }


    return $array;
}

/**
 * Obtener las competencias de un deporte
 *
 * @param String $sport sport
 * @param String $region region
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
/**
 * Obtener las regiones de un deporte
 *
 * @param String $sport sport
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getCompetitions($sport, $region, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();

    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {
                        $item_data = array(
                            "Id" => $item3->ChampionshipId,
                            "Name" => $item3->Name
                        );
                        array_push($array, $item_data);
                    }
                }

            }


        }

    }


    return $array;

}


/**
 * Obtener información sobre un deporte
 *
 * @param String $sport sport
 * @param String $region region
 * @param String $competition competition
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getMatches($sport, $region, $competition, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {

                        if ($item3->ChampionshipId == $competition) {
                            foreach ($item3->Events as $item4) {
                                print_r($item4);
                                $item_data = array(
                                    "Id" => $item4->EventId,
                                    "Name" => $item4->EventName,
                                    "Date" => $item4->EventDate
                                );
                                array_push($array, $item_data);
                            }
                        }

                    }
                }

            }


        }

    }


    return $array;

}

$fecha1 = date("Y-m-d 00:00", strtotime('0 days'));
$fecha2 = date("Y-m-d 23:59", strtotime('30 days'));

$fecha1 = str_replace(" ","T",$fecha1);
$fecha2 = str_replace(" ","T",$fecha2);

$BeginDate = $fecha1;
$EndDate =$fecha2;

$sportId = '1';
//$regions = getRegions('1', $fecha1, $fecha2);
print_r($regions);

$return = array();

$seo = array();

$arrayRegionsAllow=array(100192,100198,100247,100295,100260,100607,100193,100230);
$regions = array();

array_push($regions,
    array(
        "Id"=>"100192",
        "Name"=>"Italia",
        "SportId"=>"1"

    )
);
array_push($regions,
    array(
        "Id"=>"100198",
        "Name"=>"Francia",
        "SportId"=>"1"

    )
);
array_push($regions,
    array(
        "Id"=>"100247",
        "Name"=>"Brasil",
        "SportId"=>"1"

    )
);
array_push($regions,
    array(
        "Id"=>"100295",
        "Name"=>"Estados Unidos",
        "SportId"=>"1"

    )
);
array_push($regions,
    array(
        "Id"=>"100260",
        "Name"=>"Peru",
        "SportId"=>"1"

    )
);
array_push($regions,
    array(
        "Id"=>"100607",
        "Name"=>"Europa",
        "SportId"=>"1"

    )
);
array_push($regions,
    array(
        "Id"=>"100193",
        "Name"=>"Inglaterra",
        "SportId"=>"1"

    )
);
array_push($regions,
    array(
        "Id"=>"100230",
        "Name"=>"Alemania",
        "SportId"=>"1"

    )
);
array_push($regions,
    array(
        "Id"=>"100303",
        "Name"=>"USA",
        "SportId"=>"12"

    )
);
array_push($regions,
    array(
        "Id"=>"100196",
        "Name"=>"USA",
        "SportId"=>"20"

    )
);

array_push($regions,
    array(
        "Id"=>"100209",
        "Name"=>"USA",
        "SportId"=>"13"

    )
);

foreach ($regions as $region) {
    $IdRegion = $region["Id"];
    $NameRegion = $region["Name"];
    $sportId = $region["SportId"];

    $regions = getRegions('1', $fecha1, $fecha2);


    $competitions = getCompetitions($sportId, $IdRegion, $BeginDate, $EndDate);

    foreach ($competitions as $competition) {
        $IdCompetition = $competition["Id"];
        $IdCompetition = str_replace(" ", "", $IdCompetition);
        $NameCompetition = $competition["Name"];

        $seoprov=array();
        $comp = array();

        $SCHEMASPORTS = array();

        $namesMatches=array();

        $NameCompetitionForUrl = str_replace(" ", "-", $NameCompetition);
        $NameCompetitionForUrl = str_replace("vs.", "", $NameCompetitionForUrl);
        $NameCompetitionForUrl = str_replace("vs", "", $NameCompetitionForUrl);
        $NameCompetitionForUrl = str_replace("/", "", $NameCompetitionForUrl);
        $NameCompetitionForUrl = str_replace(",", "", $NameCompetitionForUrl);
        $NameCompetitionForUrl = str_replace(".", "-", $NameCompetitionForUrl);
        $NameCompetitionForUrl = str_replace("\u0161", "-", $NameCompetitionForUrl);
        $NameCompetitionForUrl = str_replace("\/", "-", $NameCompetitionForUrl);

        if($IdCompetition=="1000000120"){
            $NameCompetitionForUrl = "champions-league";
        }
        if($IdCompetition=="1000000238"){
            $NameCompetitionForUrl = "liga-peruana";
        }

        if($IdCompetition=="1000000149"){
            $NameCompetitionForUrl = "la-liga-santander-espana";
        }

        if($IdCompetition=="1000000149"){
            $NameCompetitionForUrl = "la-liga-santander-espana";
        }

        if($IdCompetition=="1000000097"){
            $NameCompetitionForUrl = "premier-league";
        }

        if($IdCompetition=="1000000097"){
            $NameCompetitionForUrl = "la-liga-santander-espana";
        }

        $NameCompetitionForUrl = strtolower($NameCompetitionForUrl);


        $urlCompetition = "https://doradobet.com/apuestas/liga/" . $NameCompetitionForUrl . "-" . $IdCompetition;



        $matches = getMatches($sportId, $IdRegion, $IdCompetition, $BeginDate, $EndDate);

        foreach ($matches as $match) {
            $IdMatch = $match["Id"];
            $IdMatch = str_replace(" ", "", $IdMatch);
            $NameMatch = $match["Name"];
            $DateMatch = $match["Date"];

            array_push($namesMatches,$NameMatch);


            $NameMatchForUrl = str_replace(" ", "-", $NameMatch);
            $NameMatchForUrl = str_replace("vs.", "", $NameMatchForUrl);
            $NameMatchForUrl = str_replace("vs", "", $NameMatchForUrl);
            $NameMatchForUrl = str_replace("/", "", $NameMatchForUrl);
            $NameMatchForUrl = str_replace(",", "", $NameMatchForUrl);
            $NameMatchForUrl = str_replace(".", "-", $NameMatchForUrl);
            $NameMatchForUrl = str_replace("\u0161", "-", $NameMatchForUrl);
            $NameMatchForUrl = str_replace("\/", "", $NameMatchForUrl);

            $NameMatchForUrl = strtolower($NameMatchForUrl);

            array_push($SCHEMASPORTS, array(
                "@type" => "SportsEvent",
                "name" => $NameMatch,
                "@context" => "http://schema.org",
                "startDate" => $DateMatch,
                "url" => $urlCompetition . "/partido/" . $NameMatchForUrl . "-" . $IdMatch,
                "location" => array(
                    "@type" => "Place",
                    "name" => $NameCompetition,
                    "address" => array(
                        "@type" => "PostalAddress",
                        "addressCountry" => "Europa"
                    )
                )
            ));

        }

        $comp["SCHEMASPORTS"] = array();
        $comp["SCHEMASPORTS"]["@graph"] = $SCHEMASPORTS;

        $comp["SCHEMASPORTS"] = json_encode($comp["SCHEMASPORTS"]);


        $seoprov[$urlCompetition] = $comp;

        $seoprov[$urlCompetition]["HEAD_TITLE"] = "Apuestas en ".$NameCompetition." en la Mejor casa de apuestas";
        $seoprov[$urlCompetition]["HEAD_DESCRIPTION"] = "Mejores cuotas para apostar \ud83e\udd47 Realizar apuestas  en la ".$NameCompetition."  en el ".date('y').", antes y durante el partido a los mejores equipos del futbol";
        $seoprov[$urlCompetition]["HEAD_KEYWORDS"] = implode(",",$namesMatches).",apuestas, ".strtolower($NameCompetition).", futbol, apuestas ".strtolower($NameCompetition)."";
        $seoprov[$urlCompetition]["H1"] = "";
        $seoprov[$urlCompetition]["H2"] = "";
        $seoprov[$urlCompetition]["H3"] = "";
        $seoprov[$urlCompetition]["P"] = "<h1>Apuestas ".$NameCompetition." con Doradobet</h1><p>Las apuestas de la ".$NameCompetition." se encuentran en Doradobet. los mejores eventos se pueden apostar del evento. Apostar al ".implode(",",$namesMatches)." entre otros est&aacute;n disponible para ganar con las mejores cuotas para apostar.</p>";
        $seoprov[$urlCompetition]["CANONICAL"] = $urlCompetition;
        $seoprov[$urlCompetition]["SCHEMA"] = array(
            "@context" => "http://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => array(
                array(
                    "@type" => "ListItem",
                    "position" => 1,
                    "item" => array(
                        "@id" => "https://doradobet.com/",
                        "name" => "Apuestas Deportivas"
                    )
                ),
                array(
                    "@type" => "ListItem",
                    "position" => 2,
                    "item" => array(
                        "@id" => $urlCompetition,
                        "name" => "Apuestas " . $NameCompetition
                    )
                )
            )
        );

        $seoprov[$urlCompetition]["SCHEMA"] = json_encode($seoprov[$urlCompetition]["SCHEMA"]);

        $seoprov[$urlCompetition]["QUESTIONS"] = array();

        array_push($seo,$seoprov);
    }

}

$returnSEO = array(
    "seo" => $seo
);

print_r($returnSEO);

$final = array(
    'token' => 'D0radobet1234!',
    'partner' => 'match_0',
    'lang' => 'es',
    'country' => 'pe',
    'content' => $returnSEO,
);

$ConfigurationEnvironment = new ConfigurationEnvironment();
$payload = $ConfigurationEnvironment->encrypt(json_encode(($final)));

$ch = curl_init("http://app1.local/settings/upload/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app1"));

// Set HTTP Header for POST request
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);
//$rs = curl_exec($ch);
$result = (curl_exec($ch));


// Close cURL session handle
curl_close($ch);

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "success";
$response["ModelErrors"] = [];

