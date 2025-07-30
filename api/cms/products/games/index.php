<?php
/**
 * Index de la api 'cms'
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 20.09.17
 *
 */
ini_set('display_errors', 'OFF');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X'){
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}



header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST,    OPTIONS,PUT');
header('Access-Control-Max-Age: 1728000');


require(__DIR__ . '../../../../vendor/autoload.php');


use Backend\cms\CMSProveedor;
use Backend\cms\CMSCategoria;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\UsuarioToken;
$_ENV["enabledConnectionGlobal"]=1;

$contingenciaTotalCasino=false;
try{
$responseEnable= file_get_contents(__DIR__.'/../../../../logSit/enabled');
}catch (Exception $e){}

if($responseEnable=='BLOCKED'){
    exit();
}


try {
    $action = DepurarCaracteres($_GET["action"]);

    if ($action == "getGames") {


        $offset = intval(DepurarCaracteres($_GET["offset"]));
        $limit = intval(DepurarCaracteres($_GET["limit"]));
        $provider = DepurarCaracteres($_GET["provider"]);
        $category = DepurarCaracteres($_GET["category"]);

        $partner_id = DepurarCaracteres($_GET["partner_id"]);
        $search = DepurarCaracteres($_GET["search"]);
        $isMobile = (DepurarCaracteres($_GET["isMobile"])== 'true') ? true : false;

// CONTINGENCES //
        $country = DepurarCaracteres($_GET["country"]);

        if($country != '' ){
            $Pais = new \Backend\dto\Pais('',$country);
            $country=$Pais->paisId;
        }



        switch ($partner_id){

            case '0':
                $bgCasino='https://images.virtualsoft.tech/site/doradobet/fondo-casino2.png?v=1';
                break;

            case '3':
                $bgCasino='https://images.virtualsoft.tech/site/miravalle/bgCasino.jpg';
                break;

            case '4':
                $bgCasino='https://images.virtualsoft.tech/site/casinogranpalacio/bgCasino.jpg';
                break;

            case '5':
                $bgCasino='https://images.virtualsoft.tech/site/casinointercontinental/bgCasino.jpg';
                break;

            case '6':
                $bgCasino='https://images.virtualsoft.tech/site/netabet/bgCasino.jpg';
                break;

            case '7':
                $bgCasino='https://images.virtualsoft.tech/site/astoria/bgCasino.jpg';
                break;

            default:
                $bgCasino='https://images.virtualsoft.tech/productos/casino/casino-background2.jpg';
                break;
        }

        if ($category == 3) {
            if($partner_id ==11){
                if($category == 3){
                    $category =399;
                    $limit=20;
                }
            }
            $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

                if($ConfigurationEnvironment->isDevelopment()){
                    $ProdMandanteTipo = new ProdMandanteTipo("LIVECASINO", $partner_id);

                    if ($ProdMandanteTipo->estado == "I") {

                        throw new Exception("LiveCasino Inactivo", "20025");
                    }
                    if ($ProdMandanteTipo->contingencia == "A") {

                        throw new Exception("Livecasino en contingencia", "20026");
                    }

                }

            $id = DepurarCaracteres($_GET["id"]);

            if ($id != "") {
                $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;
            }


            $Proveedor = new CMSProveedor("LIVECASINO", "", $partner_id);
        } else {


            $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $partner_id);

            if ($ProdMandanteTipo->estado == "I") {

                throw new Exception("Casino Inactivo", "20023");
            }
            if ($ProdMandanteTipo->contingencia == "A") {

                throw new Exception("Casino en contingencia", "20024");
            }

            $id = DepurarCaracteres($_GET["id"]);

            if ($id != "") {
                $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;

            }


            $Proveedor = new CMSProveedor("CASINO", "", $partner_id);
        }

        $Productos = $Proveedor->getProductos($category, '', $offset, $limit, $search, $isMobile,$provider);


        $Productos = json_decode($Productos);


        $data = $Productos->data;

        $return_array = array();
        $return_array["status"] = "ok";
        $return_array["total_count"] = $Productos->total;

        $games = array();

        $cont = 0;
        $contint = 0;
        $cont = 0;

        if ($offset == 0) {
            $contint = 0;
        } elseif ($offset < 24) {
            $contint = 24;
        } elseif ($offset < 36) {
            $contint = 36;
        } elseif ($offset < 48) {
            $contint = 48;
        } elseif ($offset < 60) {
            $contint = 60;
        } elseif ($offset < 72) {
            $contint = 72;
        } elseif ($offset < 84) {
            $contint = 84;
        } elseif ($offset < 96) {
            $contint = 96;
        } elseif ($offset < 108) {
            $contint = 108;
        } elseif ($offset < 120) {
            $contint = 120;
        } elseif ($offset < 132) {
            $contint = 132;
        } elseif ($offset < 144) {
            $contint = 144;
        } elseif ($offset < 156) {
            $contint = 156;
        }

        foreach ($data as $producto) {
            $seguir = true;


            if ($isMobile && ($producto->descripcion == "Bet On Numbers" || $producto->descripcion == "Live Keno")) {
                $seguir = false;
            }

            /*

            if(!$isMobile && ($producto->producto_id =="558" || $producto->producto_id =="560" || $producto->producto_id =="548" || $producto->producto_id =="566" || $producto->producto_id =="570" || $producto->producto_id =="574" || $producto->producto_id =="550" || $producto->producto_id =="578" || $producto->producto_id =="582"|| $producto->producto_id =="586"|| $producto->producto_id =="590"|| $producto->producto_id =="594"|| $producto->producto_id =="598"|| $producto->producto_id =="602"|| $producto->producto_id =="552")){
                $seguir=false;
            }

            if($isMobile && ($producto->producto_id =="546" || $producto->producto_id =="562" || $producto->producto_id =="564" || $producto->producto_id =="568" || $producto->producto_id =="572" || $producto->producto_id =="576" || $producto->producto_id =="554" || $producto->producto_id =="580" || $producto->producto_id =="584"|| $producto->producto_id =="588"|| $producto->producto_id =="592"|| $producto->producto_id =="596"|| $producto->producto_id =="600"|| $producto->producto_id =="604"|| $producto->producto_id =="556")){
                $seguir=false;
            }
            */
            $id = DepurarCaracteres($_GET["id"]);
            $search = DepurarCaracteres($_GET["search"]);
            $search = DepurarCaracteres($_GET["search"]);
            $search = str_replace('?','',$search);
            $search = preg_replace('/[^(\x20-\x7F)]*/','', $search);

            if($search != ''){
                $limit=10;
            }
            if ($cont >= ($limit + $offset) && $category != 3 && $id == "" && $search == "") {

                $seguir = false;
            }

            if ($seguir) {


                $game = array();
                $game["id"] = $producto->id;
                $game["name"] = $producto->descripcion;
                $game["producto_id"] = $producto->producto_id;
                $game["provider"] = $producto->proveedor->descripcion;
                $game["show_as_provider"] = $producto->proveedor->descripcion;
                $game["server_game_id"] = $producto->id;
                $game["status"] = "published";

                if ($producto->background == "") {
                    $producto->background = $bgCasino;
                }

                $game["background"] = $producto->background;
                $game["categories"] = array($producto->categoria->id);
                $game["cats"] = array("id" => $producto->categoria->id, "title" => $producto->categoria->descripcion);
                $game["extearnal_game_id"] = $producto->id;
                $game["front_game_id"] = $producto->externo_id;
                $game["game_options"] = "";
                $game["game_skin_id"] = "";
                $game["icon_2"] = str_replace("http:","https:",$producto->image);
                $game["icon_3"] =  str_replace("http:","https:",$producto->image2);
                $game["ratio"] = "16:9";


                if(in_array($game["front_game_id"],array(
                    'gpas_aogggriffin_pop','gpas_aogrotu_pop','aogmt','gpas_aogiw_pop','gpas_aoggosun_pop','aogmm','wop','gpas_aogwfot_pop','gpas_aogww_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='mrj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'anwild','gpas_awild2pp_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_fballiwpp_pop','gpas_focashco_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'fdtjg'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='fdtjp-2';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_azbolipp_pop','gpas_ppayspp_pop','gpas_pigeonfspp_pop','gpas_sstrikepp_pop','gpas_soicepp_pop','gpas_tttotemspp_pop','gpas_mblockspp_pop','gpas_wlinxpp_pop','gpas_fmhitbarpp_pop','gpas_hgextremepp_pop','gpas_kgomoonpp_pop','gpas_dostormspp_pop','gpas_bokings2pp_pop','gpas_eemeraldspp_pop','gpas_betwildspp_pop','gpas_bbellspp_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'tmccoy','asct','gpas_bgeorge_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='sljp-3';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_bbmwayslo_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='bjp-4';
                }
                if(in_array($game["front_game_id"],array(
                    'cbells'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jbells4-4';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_eape2_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='bjp-4';
                }
                if(in_array($game["front_game_id"],array(
                    'evj'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='evjj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'fcgz'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='drgj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_fbars_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jhreelsj-2';
                }
                if(in_array($game["front_game_id"],array(
                    'fmjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='fmjp8';
                }
                if(in_array($game["front_game_id"],array(
                    'grbjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='grbjpj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'jbells'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jbells4-4';
                }
                if(in_array($game["front_game_id"],array(
                    'jpgt'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jpgt6-1';
                }
                if(in_array($game["front_game_id"],array(
                    'zcjbjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='drgj-1';
                }
                if($isMobile){
                    $game["rows"] = 1;
                    $game["columns"] = 1;
                    $game["grid_column"] = 1;
                    $game["grid_row"] = 1;

                }else{
                    $game["rows"] = $producto->fila;
                    $game["columns"] = $producto->columna;
                    $game["grid_column"] = $producto->fila;
                    $game["grid_row"] = $producto->columna;

                }

                $game["types"] = array(
                    "realMode" => 1,
                    "funMode" => 0

                );

                if ($game["id"] == "4158") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                    $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
                }


                if ($game["id"] == "4194") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                    $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
                }




                if ($game["id"] == "4566") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                    $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
                }


                if ($game["id"] == "4428") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if ($game["id"] == "4803") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if ($game["id"] == "4194") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if ($game["id"] == "4566") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if ($game["id"] == "4566") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if(in_array($game["id"],array(5457,8717, 5459, 8720,593,6107,9251,11858,11861,5217,51637))){
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }






                if ($game["id"] == "4428") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                    $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
                }

                array_push($games, $game);

                $cont = $cont + ($producto->fila * $producto->columna);

                $contintold = $contint;
                $esreplazo = 0;

                $contint = $contint + ($producto->fila * $producto->columna);

                if ($contintold < 12 && $contint > 12) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 12;
                }
                if ($contintold < 24 && $contint > 24) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 24;
                }
                if ($contintold < 36 && $contint > 36) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 36;
                }
                if ($contintold < 48 && $contint > 48) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 48;
                }
                if ($contintold < 60 && $contint > 60) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 60;
                }
                if ($contintold < 72 && $contint > 72) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 72;
                }
                if ($contintold < 84 && $contint > 84) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 84;
                }
                if ($contintold < 96 && $contint > 96) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 96;
                }

                if ($contintold < 108 && $contint > 108) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 108;
                }

                if ($contintold < 120 && $contint > 120) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 120;
                }


                if ($contintold < 132 && $contint > 132) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 132;
                }


                if ($contintold < 144 && $contint > 144) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 144;
                }


                if (true && !in_array($partner_id,array(3,4,5,0,6,7,8,1,2))  ) {
                    if (($contint == 12 || $contint == 84) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 24 || $contint == 96) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-2';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 36 || $contint == 108) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-3';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 48 || $contint == 120) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-4';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 60 || $contint == 132) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-5';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 72 || $contint == 144) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-6';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }


                    if (($contint == 12 || $contint == 84) && $id == "" && $category == "" && $isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 24 || $contint == 96) && $id == "" && $category == "" && $isMobile) {

                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-2';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }

                    if (($contint == 36 || $contint == 108) && $id == "" && $category == "" && $isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-3';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 48 || $contint == 120) && $id == "" && $category == "" && $isMobile) {

                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-4';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }

                    if (($contint == 60 || $contint == 132) && $id == "" && $category == "" && $isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-5';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 72 || $contint == 144) && $id == "" && $category == "" && $isMobile) {

                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-6';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }


                    if ($esreplazo == 1) {
                        $contint = $contintold;
                    }
                }
            }
        }

        $return_array["games"] = $games;
        if($contingenciaTotalCasino){
            $return_array["games"] = array();
        }

        print_r(json_encode($return_array));
    }

    if ($action == "getGames2") {
        $typelobby = DepurarCaracteres($_GET["typelobby"]);


        $offset = intval(DepurarCaracteres($_GET["offset"]));
        $limit = intval(DepurarCaracteres($_GET["limit"]));
        $provider = DepurarCaracteres($_GET["provider"]);
        $category = DepurarCaracteres($_GET["category"]);

        $partner_id = DepurarCaracteres($_GET["partner_id"]);
        $isMobile = (DepurarCaracteres($_GET["isMobile"])== 'true') ? true : false;
        $search = DepurarCaracteres($_GET["search"]);
        $search = str_replace('?','',$search);
        $search = preg_replace('/[^(\x20-\x7F)]*/','', $search);

        if($search != ''){
            $limit=10;
        }
        switch ($partner_id){

            case '0':
                $bgCasino='https://images.virtualsoft.tech/site/doradobet/fondo-casino2.png?v=1';
                break;

            case '3':
                $bgCasino='https://images.virtualsoft.tech/site/miravalle/bgCasino.jpg';
                break;

            case '4':
                $bgCasino='https://images.virtualsoft.tech/site/casinogranpalacio/bgCasino.jpg';
                break;

            case '5':
                $bgCasino='https://images.virtualsoft.tech/site/casinointercontinental/bgCasino.jpg';
                break;

            case '6':
                $bgCasino='https://images.virtualsoft.tech/site/netabet/bgCasino.jpg';
                break;

            case '7':
                $bgCasino='https://images.virtualsoft.tech/site/astoria/bgCasino.jpg';
                break;

            default:
                $bgCasino='https://images.virtualsoft.tech/productos/casino/casino-background2.jpg';
                break;
        }

        if ($category == 3 || $typelobby=="2") {

            $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

            if($ConfigurationEnvironment->isDevelopment()){
                $ProdMandanteTipo = new ProdMandanteTipo("LIVECASINO", $partner_id);

                /*if ($ProdMandanteTipo->estado == "I") {

                    throw new Exception("LiveCasino Inactivo", "20025");
                }
                if ($ProdMandanteTipo->contingencia == "A") {

                    throw new Exception("Livecasino en contingencia", "20026");
                }*/

            }

            $id = DepurarCaracteres($_GET["id"]);

            if ($id != "") {
                $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;
            }

            $country = DepurarCaracteres($_GET["country"]);

            if($country != '' && $partner_id =='0'){
                $Pais = new \Backend\dto\Pais('',$country);
                $country=$Pais->paisId;
            }elseif($country != '' && $partner_id =='18'){
                $Pais = new \Backend\dto\Pais('',$country);
                $country=$Pais->paisId;
            }
            else{
                $country='';
            }


            if($country == '' && $partner_id =='0'){
                $country='173';
            }


            $Proveedor = new CMSProveedor("LIVECASINO", "", $partner_id,$country);
        }elseif ( $typelobby=="1") {

            $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

            if($ConfigurationEnvironment->isDevelopment()){
                $ProdMandanteTipo = new ProdMandanteTipo("VIRTUAL", $partner_id);

                /*if ($ProdMandanteTipo->estado == "I") {

                    throw new Exception("LiveCasino Inactivo", "20025");
                }
                if ($ProdMandanteTipo->contingencia == "A") {

                    throw new Exception("Livecasino en contingencia", "20026");
                }*/

            }

            $id = DepurarCaracteres($_GET["id"]);

            if ($id != "") {
                $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;
            }
            $country = DepurarCaracteres($_GET["country"]);


            if($country != '' && $partner_id =='0'){
                $Pais = new \Backend\dto\Pais('',$country);
                $country=$Pais->paisId;
            }elseif($country != '' && $partner_id =='18'){
                $Pais = new \Backend\dto\Pais('',$country);
                $country=$Pais->paisId;
            }
            else{
                $country='';
            }


            if($country == '' && $partner_id =='0'){
                $country='173';
            }




            $Proveedor = new CMSProveedor("VIRTUAL", "", $partner_id,$country);
        } elseif ($typelobby == "-1") {
            //typelobby = -1 corresponde a una búsqueda de productos/juegos entre CASINO, LIVECASINO y VIRTUAL



            $id = DepurarCaracteres($_GET["id"]);
            if ($id != "") {
                $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;
            }

            $country = DepurarCaracteres($_GET["country"]);
            if($country != '' && $partner_id =='0'){
                $Pais = new \Backend\dto\Pais('',$country);
                $country=$Pais->paisId;
            }else{
                $country='';
            }
            if($country == '' && $partner_id =='0'){
                $country='173';
            }


            $Proveedor = new CMSProveedor("", "", $partner_id,$country);
        }  else {


            $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $partner_id);

            if ($ProdMandanteTipo->estado == "I") {

                throw new Exception("Casino Inactivo", "20023");
            }
            if ($ProdMandanteTipo->contingencia == "A") {

                throw new Exception("Casino en contingencia", "20024");
            }

            $id = DepurarCaracteres($_GET["id"]);

            if ($id != "") {
                $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;

            }
            $country = DepurarCaracteres($_GET["country"]);

            if($country != '' && $partner_id =='0'){
                $Pais = new \Backend\dto\Pais('',$country);
                $country=$Pais->paisId;
            }elseif($country != '' && $partner_id =='18'){
                $Pais = new \Backend\dto\Pais('',$country);
                $country=$Pais->paisId;
            }else{
                $country='';
            }


            if($country == '' && $partner_id =='0'){
                $country='173';
            }
            if($country == '' && $partner_id =='19'){
                $country='173';
            }


            $Proveedor = new CMSProveedor("CASINO", "", $partner_id,$country);
        }

        if($provider != "" || $search != "" || $typelobby=="2"|| $typelobby=="1"){
            $Productos = $Proveedor->getProductos($category, '', $offset, $limit, $search, $isMobile,$provider);

        }else{
            $Productos = $Proveedor->getProductos2($category, '', $offset, $limit, $search, $isMobile,$provider);

        }


        $Productos = json_decode($Productos);


        $data = $Productos->data;

        $return_array = array();
        $return_array["status"] = "ok";
        $return_array["total_count"] = $Productos->total;

        $games = array();

        $cont = 0;
        $contint = 0;
        $cont = 0;

        if ($offset == 0) {
            $contint = 0;
        } elseif ($offset < 24) {
            $contint = 24;
        } elseif ($offset < 36) {
            $contint = 36;
        } elseif ($offset < 48) {
            $contint = 48;
        } elseif ($offset < 60) {
            $contint = 60;
        } elseif ($offset < 72) {
            $contint = 72;
        } elseif ($offset < 84) {
            $contint = 84;
        } elseif ($offset < 96) {
            $contint = 96;
        } elseif ($offset < 108) {
            $contint = 108;
        } elseif ($offset < 120) {
            $contint = 120;
        } elseif ($offset < 132) {
            $contint = 132;
        } elseif ($offset < 144) {
            $contint = 144;
        } elseif ($offset < 156) {
            $contint = 156;
        }

        foreach ($data as $producto) {
            $seguir = true;


            if ($isMobile && ($producto->descripcion == "Bet On Numbers" || $producto->descripcion == "Live Keno")) {
                $seguir = false;
            }

            /*

            if(!$isMobile && ($producto->producto_id =="558" || $producto->producto_id =="560" || $producto->producto_id =="548" || $producto->producto_id =="566" || $producto->producto_id =="570" || $producto->producto_id =="574" || $producto->producto_id =="550" || $producto->producto_id =="578" || $producto->producto_id =="582"|| $producto->producto_id =="586"|| $producto->producto_id =="590"|| $producto->producto_id =="594"|| $producto->producto_id =="598"|| $producto->producto_id =="602"|| $producto->producto_id =="552")){
                $seguir=false;
            }

            if($isMobile && ($producto->producto_id =="546" || $producto->producto_id =="562" || $producto->producto_id =="564" || $producto->producto_id =="568" || $producto->producto_id =="572" || $producto->producto_id =="576" || $producto->producto_id =="554" || $producto->producto_id =="580" || $producto->producto_id =="584"|| $producto->producto_id =="588"|| $producto->producto_id =="592"|| $producto->producto_id =="596"|| $producto->producto_id =="600"|| $producto->producto_id =="604"|| $producto->producto_id =="556")){
                $seguir=false;
            }
            */
            $id = DepurarCaracteres($_GET["id"]);
            $search = DepurarCaracteres($_GET["search"]);

            if ($cont >= ($limit + $offset) && $category != 3 && $id == "" && $search == "") {

                $seguir = false;
            }

            if ($seguir) {

                $game = array();
                $game["id"] = $producto->id;
                $game["name"] = $producto->descripcion;
                $game["producto_id"] = $producto->producto_id;
                $game["provider"] = $producto->proveedor->abreviado;
                $game["show_as_provider"] = $producto->proveedor->descripcion;
                $game["server_game_id"] = $producto->id;
                $game["status"] = "published";

                if ($producto->background == "") {
                    $producto->background = $bgCasino;
                }

                $game["background"] = $producto->background;
                $game["categories"] = array($producto->categoria->id);
                $game["cats"] = array("id" => $producto->categoria->id, "title" => $producto->categoria->descripcion);
                $game["extearnal_game_id"] = $producto->id;
                $game["front_game_id"] = $producto->externo_id;
                $game["game_options"] = "";
                $game["game_skin_id"] = "";
                $game["icon_2"] = str_replace("http:","https:",$producto->image);
                $game["icon_3"] =  str_replace("http:","https:",$producto->image2);
                $game["ratio"] = "16:9";


                if(in_array($game["front_game_id"],array(
                    'gpas_aogggriffin_pop','gpas_aogrotu_pop','aogmt','gpas_aogiw_pop','gpas_aoggosun_pop','aogmm','wop','gpas_aogwfot_pop','gpas_aogww_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='mrj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'anwild','gpas_awild2pp_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_fballiwpp_pop','gpas_focashco_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'fdtjg'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='fdtjp-2';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_azbolipp_pop','gpas_ppayspp_pop','gpas_pigeonfspp_pop','gpas_sstrikepp_pop','gpas_soicepp_pop','gpas_tttotemspp_pop','gpas_mblockspp_pop','gpas_wlinxpp_pop','gpas_fmhitbarpp_pop','gpas_hgextremepp_pop','gpas_kgomoonpp_pop','gpas_dostormspp_pop','gpas_bokings2pp_pop','gpas_eemeraldspp_pop','gpas_betwildspp_pop','gpas_bbellspp_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'tmccoy','asct','gpas_bgeorge_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='sljp-3';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_bbmwayslo_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='bjp-4';
                }
                if(in_array($game["front_game_id"],array(
                    'cbells'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jbells4-4';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_eape2_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='bjp-4';
                }
                if(in_array($game["front_game_id"],array(
                    'evj'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='evjj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'fcgz'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='drgj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_fbars_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jhreelsj-2';
                }
                if(in_array($game["front_game_id"],array(
                    'fmjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='fmjp8';
                }
                if(in_array($game["front_game_id"],array(
                    'grbjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='grbjpj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'jbells'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jbells4-4';
                }
                if(in_array($game["front_game_id"],array(
                    'jpgt'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jpgt6-1';
                }
                if(in_array($game["front_game_id"],array(
                    'zcjbjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='drgj-1';
                }

                if($isMobile){
                    $game["rows"] = 1;
                    $game["columns"] = 1;
                    $game["grid_column"] = 1;
                    $game["grid_row"] = 1;

                }else{
                    $game["rows"] = $producto->fila;
                    $game["columns"] = $producto->columna;
                    $game["grid_column"] = $producto->fila;
                    $game["grid_row"] = $producto->columna;

                }

                $game["types"] = array(
                    "realMode" => 1,
                    "funMode" => 0

                );

                if ($game["id"] == "4158") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                    $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
                }


                if ($game["id"] == "4194") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                    $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
                }




                if ($game["id"] == "4566") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                    $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
                }


                if ($game["id"] == "4428") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if ($game["id"] == "4803") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if ($game["id"] == "4194") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if ($game["id"] == "4566") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if ($game["id"] == "4566") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }

                if(in_array($game["id"],array(5457,8717, 5459, 8720,593,6107,9251,11858,11861,5217,51637))){
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                }




                $game["name"] = html_entity_decode(preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $game["name"]));
                $game["icon_2"] = html_entity_decode(preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $game["icon_2"]));


                /*if($producto->id ==5798){
                    $game["name"] = "Vivogaming";
                    $game["icon_2"]="https://images.virtualsoft.tech/m/msjT1617929493.png";
                    $game["rows"] = 2;
                    $game["columns"] = 2;
                    $game["grid_column"] = 2;
                    $game["grid_row"] = 2;
                }
                if($producto->id ==5707){
                    $game["name"] = "BetgamesTV";
                    $game["icon_2"]="https://images.virtualsoft.tech/m/msjT1617929820.png";
                    $game["rows"] = 2;
                    $game["columns"] = 2;
                    $game["grid_column"] = 2;
                    $game["grid_row"] = 2;
                }
                if($producto->id ==481){
                    $game["name"] = "Ezugi";
                    //$game["icon_2"]="https://images.virtualsoft.tech/m/msjT1617929493.png";
                    $game["rows"] = 2;
                    $game["columns"] = 2;
                    $game["grid_column"] = 2;
                    $game["grid_row"] = 2;

                }*/


                if ($game["id"] == "4428") {
                    $game["isBorderNeon"] = true;
                    $game["classBorderNeon"] = 'neon1';

                    $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
                }
                array_push($games, $game);

                $cont = $cont + ($producto->fila * $producto->columna);

                $contintold = $contint;
                $esreplazo = 0;

                $contint = $contint + ($producto->fila * $producto->columna);

                if ($contintold < 12 && $contint > 12) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 12;
                }
                if ($contintold < 24 && $contint > 24) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 24;
                }
                if ($contintold < 36 && $contint > 36) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 36;
                }
                if ($contintold < 48 && $contint > 48) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 48;
                }
                if ($contintold < 60 && $contint > 60) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 60;
                }
                if ($contintold < 72 && $contint > 72) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 72;
                }
                if ($contintold < 84 && $contint > 84) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 84;
                }
                if ($contintold < 96 && $contint > 96) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 96;
                }

                if ($contintold < 108 && $contint > 108) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 108;
                }

                if ($contintold < 120 && $contint > 120) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 120;
                }


                if ($contintold < 132 && $contint > 132) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 132;
                }


                if ($contintold < 144 && $contint > 144) {
                    $contintold = $contint;
                    $esreplazo = 1;
                    $contint = 144;
                }

                if (true &&  $provider == ""  && $category == "" && $search == "" && in_array($partner_id,array())) {
                    if (($contint == 3) && $id == "" ) {
                        $game["id"] = "promo" . $producto->id;

                        $game["object_fit"] = 'contain';
                        $game["icon_2"] = 'https://images.virtualsoft.tech/m/msjT1684584805.png';
                        $game["icon_3"] =  'https://images.virtualsoft.tech/m/msjT1684584805.png';

                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        $game["rows"] = 2;
                        $game["columns"] = 3;
                        $game["grid_column"] = 2;
                        $game["grid_row"] = 3;

                        if($isMobile){
                            $game["rows"] = 1;
                            $game["columns"] = 2;
                            $game["grid_column"] = 1;
                            $game["grid_row"] = 2;

                        }

                        array_push($games, $game);

                    }
                    if (( $contint == 84) && $id == "" ) {
                        $game["id"] = "promo" . $producto->id;

                        $game["object_fit"] = 'contain';
                        $game["icon_2"] = 'https://images.virtualsoft.tech/m/msjT1684584994.png';
                        $game["icon_3"] =  'https://images.virtualsoft.tech/m/msjT1684584994.png';

                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        $game["rows"] = 2;
                        $game["columns"] = 3;
                        $game["grid_column"] = 2;
                        $game["grid_row"] = 3;

                        if($isMobile){
                            $game["rows"] = 1;
                            $game["columns"] = 2;
                            $game["grid_column"] = 1;
                            $game["grid_row"] = 2;

                        }

                        array_push($games, $game);

                    }
                }

                if (true &&  $provider == "" &&  $category == "" && $search == "" && in_array($partner_id,array())) {
                    if (($contint == 15 ) && $id == "" ) {
                        $game["id"] = "promo" . $producto->id;

                        $game["object_fit"] = 'contain';
                        $game["icon_2"] = 'https://images.virtualsoft.tech/m/msjT1683764939.png';
                        $game["icon_3"] =  'https://images.virtualsoft.tech/m/msjT1683764939.png';

                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        $game["rows"] = 1;
                        $game["columns"] = 'full';
                        $game["grid_column"] = 'full';
                        $game["grid_row"] = 1;

                        if($isMobile){
                            $game["rows"] = 1;
                            $game["columns"] = 'full';
                            $game["grid_column"] = 'full';
                            $game["grid_row"] = 1;
                        }


                        array_push($games, $game);

                    }
                    if (($contint == 25 ) && $id == "" ) {
                        $game["id"] = "promo" . $producto->id;

                        $game["object_fit"] = 'contain';
                        $game["icon_2"] = 'https://images.virtualsoft.tech/m/msjT1683766721.png';
                        $game["icon_3"] =  'https://images.virtualsoft.tech/m/msjT1683766721.png';

                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        $game["rows"] = 1;
                        $game["columns"] = 'full';
                        $game["grid_column"] = 'full';
                        $game["grid_row"] = 1;

                        if($isMobile){
                            $game["rows"] = 1;
                            $game["columns"] = 'full';
                            $game["grid_column"] = 'full';
                            $game["grid_row"] = 1;
                        }


                        array_push($games, $game);

                    }
                    if (($contint == 40 ) && $id == "" ) {
                        $game["id"] = "promo" . $producto->id;

                        $game["object_fit"] = 'contain';
                        $game["icon_2"] = 'https://images.virtualsoft.tech/m/msjT1683766721.png';
                        $game["icon_3"] =  'https://images.virtualsoft.tech/m/msjT1683766721.png';

                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        $game["rows"] = 1;
                        $game["columns"] = 'full';
                        $game["grid_column"] = 'full';
                        $game["grid_row"] = 1;

                        if($isMobile){
                            $game["rows"] = 1;
                            $game["columns"] = 'full';
                            $game["grid_column"] = 'full';
                            $game["grid_row"] = 1;
                        }


                        array_push($games, $game);

                    }
                }
                if (false && !in_array($partner_id,array(3,4,5,0,6,7,8,1,2,12,13))) {
                    if (($contint == 12 || $contint == 84) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 24 || $contint == 96) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-2';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 36 || $contint == 108) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-3';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 48 || $contint == 120) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-4';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 60 || $contint == 132) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-5';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 72 || $contint == 144) && $id == "" && !$isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-6';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }


                    if (($contint == 12 || $contint == 84) && $id == "" && $category == "" && $isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-1';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 24 || $contint == 96) && $id == "" && $category == "" && $isMobile) {

                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-2';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }

                    if (($contint == 36 || $contint == 108) && $id == "" && $category == "" && $isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-3';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 48 || $contint == 120) && $id == "" && $category == "" && $isMobile) {

                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-4';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }

                    if (($contint == 60 || $contint == 132) && $id == "" && $category == "" && $isMobile) {
                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-5';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }
                    if (($contint == 72 || $contint == 144) && $id == "" && $category == "" && $isMobile) {

                        $game["id"] = "promo" . $producto->id;


                        $game["name"] = $producto->nombre;

                        $game["isPromo"] = true;
                        $game["codePromo"] = 'casino-promo-6';
                        $game["widthPercentage"] = 100;
                        array_push($games, $game);

                    }


                    if ($esreplazo == 1) {
                        $contint = $contintold;
                    }
                }
            }
        }

        $return_array["games"] = $games;
        if($contingenciaTotalCasino) {

            $return_array["games"] = array();
            $return_array["total_count"] = 0;

        }
        print_r(json_encode($return_array));
    }

    if ($action == "getOptions") {
        $typelobby = DepurarCaracteres($_GET["typelobby"]);
        $partner_id = DepurarCaracteres($_GET["partner_id"]);

        $country = DepurarCaracteres($_GET["country"]);


        $offset = DepurarCaracteres($_GET["offset"]);
        $limit = DepurarCaracteres($_GET["limit"]);

        $tipoProducto="CASINO";

        if($typelobby == "2"){
            $tipoProducto="LIVECASINO";
        }
        if($typelobby == "1"){
            $tipoProducto="VIRTUAL";
        }

        if($country != ''){
            $Pais = new \Backend\dto\Pais('',strtoupper($country));
            $country=$Pais->paisId;
        }else{
           // $country='';
        }


        if($country == '' && $partner_id =='0'){
            $country='173';
        }

        if($country == '' && in_array($partner_id,array(3,4,5,6,7,10,13))){
            $country='146';
        }
        if(in_array($partner_id,array(11))){
            $country='1';
        }
        if($_ENV['debug']){
            print_r($country);
        }


        $CMSCategoria = new CMSCategoria("", $tipoProducto,$partner_id,$country);

        $Categorias = $CMSCategoria->getCategoriasMandante();


        $Categorias = json_decode($Categorias);

        $data = $Categorias->data;

        $return_array = array();
        $return_array["status"] = "ok";
        $return_array["total_count"] = $Categorias->total;

        $categories = array();

        foreach ($data as $categoria) {

            if ($categoria->estado == "A") {
                $game = array();
                $game["id"] = $categoria->id;
                $game["slug"] = $categoria->slug;
                $game["name"] = $categoria->descripcion;
                $game["title"] = $categoria->descripcion;
                $game["icon"] = $categoria->imagen;


                array_push($categories, $game);
            }

        }

        $return_array["categories"] = $categories;


        $Proveedor = new CMSProveedor($tipoProducto, "",$partner_id,$country);

        //$Proveedores = $Proveedor->getProveedores('A');
        $Proveedores = $Proveedor->getSubProveedoresPais('A');
        //$Proveedores = $Subproveedor->getProveedores('A');

        $Proveedores = json_decode($Proveedores);

        $data = $Proveedores->data;

        $providers = array();


        foreach ($data as $proveedor) {

            if($proveedor->abreviado !=  'XPRESS' && $proveedor->abreviado !=  'UNIVERSALS'             && $proveedor->abreviado !=  'IESGAMES'    && $proveedor->abreviado !=  'EVENBET'   && $proveedor->abreviado !=  'EVENBET'                   && $proveedor->abreviado !=  'GANAPATI'   && $proveedor->abreviado !=  'BRAGG'   && $proveedor->abreviado !=  'BRAGG'   && $proveedor->abreviado !=  'PRAGMATICBINGO'                  ){
                $game = array();
                $game["name"] = $proveedor->abreviado;
                $game["title"] = $proveedor->descripcion;
                $game["title"] = ucfirst(strtolower($game["title"]));
                $game["image"] = $proveedor->imagen;

                array_push($providers, $game);

            }

        }
        $return_array["providers"] = $providers;

        print_r(json_encode($return_array));

    }

    if ($action == "getJeckpots2") {

        $offset = DepurarCaracteres($_GET["offset"]);
        $limit = DepurarCaracteres($_GET["limit"]);
        $provider = DepurarCaracteres($_GET["provider"]);
        $category = DepurarCaracteres($_GET["category"]);


        $Proveedor = new CMSProveedor("CASINO", "");

        $Productos = $Proveedor->getProductos($category, $provider, $offset, $limit);

        $Productos = json_decode($Productos);

        $data = $Productos->data;

        $return_array = array();
        $return_array["status"] = "ok";
        $return_array["total_count"] = $Productos->total;

        $games = array();


        foreach ($data as $producto) {

            $game = array();
            $game["id"] = $producto->id;
            $game["name"] = $producto->descripcion;
            $game["provider"] = $producto->proveedor->abreviado;
            $game["show_as_provider"] = $producto->proveedor->abreviado;
            $game["server_game_id"] = $producto->id;
            $game["status"] = "published";

            $game["background"] = $producto->background;
            $game["categories"] = array($producto->categoria->id);
            $game["cats"] = array("id" => $producto->categoria->id, "title" => $producto->categoria->descripcion);
            $game["extearnal_game_id"] = $producto->id;
            $game["front_game_id"] = $producto->id;
            $game["game_options"] = "";
            $game["game_skin_id"] = "";
            $game["icon_2"] = str_replace("http:","https:",$producto->image);
            $game["icon_3"] =  str_replace("http:","https:",$producto->image2);
            $game["ratio"] = "16:9";
            $game["types"] = array(
                "realMode" => 1,
                "funMode" => 1

            );


            if(in_array($game["front_game_id"],array(
                'gpas_aogggriffin_pop','gpas_aogrotu_pop','aogmt','gpas_aogiw_pop','gpas_aoggosun_pop','aogmm','wop','gpas_aogwfot_pop','gpas_aogww_pop'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='mrj-1';
            }
            if(in_array($game["front_game_id"],array(
                'anwild','gpas_awild2pp_pop'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='ptjp-1';
            }
            if(in_array($game["front_game_id"],array(
                'gpas_fballiwpp_pop','gpas_focashco_pop'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='ptjp-1';
            }
            if(in_array($game["front_game_id"],array(
                'fdtjg'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='fdtjp-2';
            }
            if(in_array($game["front_game_id"],array(
                'gpas_azbolipp_pop','gpas_ppayspp_pop','gpas_pigeonfspp_pop','gpas_sstrikepp_pop','gpas_soicepp_pop','gpas_tttotemspp_pop','gpas_mblockspp_pop','gpas_wlinxpp_pop','gpas_fmhitbarpp_pop','gpas_hgextremepp_pop','gpas_kgomoonpp_pop','gpas_dostormspp_pop','gpas_bokings2pp_pop','gpas_eemeraldspp_pop','gpas_betwildspp_pop','gpas_bbellspp_pop'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='ptjp-1';
            }
            if(in_array($game["front_game_id"],array(
                'tmccoy','asct','gpas_bgeorge_pop'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='sljp-3';
            }
            if(in_array($game["front_game_id"],array(
                'gpas_bbmwayslo_pop'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='bjp-4';
            }
            if(in_array($game["front_game_id"],array(
                'cbells'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='jbells4-4';
            }
            if(in_array($game["front_game_id"],array(
                'gpas_eape2_pop'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='bjp-4';
            }
            if(in_array($game["front_game_id"],array(
                'evj'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='evjj-1';
            }
            if(in_array($game["front_game_id"],array(
                'fcgz'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='drgj-1';
            }
            if(in_array($game["front_game_id"],array(
                'gpas_fbars_pop'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='jhreelsj-2';
            }
            if(in_array($game["front_game_id"],array(
                'fmjp'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='fmjp8';
            }
            if(in_array($game["front_game_id"],array(
                'grbjp'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='grbjpj-1';
            }
            if(in_array($game["front_game_id"],array(
                'jbells'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='jbells4-4';
            }
            if(in_array($game["front_game_id"],array(
                'jpgt'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='jpgt6-1';
            }
            if(in_array($game["front_game_id"],array(
                'zcjbjp'
            ))){
                $game["jackpot"]=1;
                $game["front_game_id"]='drgj-1';
            }

            array_push($games, $game);
        }

        $return_array["games"] = $games;

        print_r(json_encode($return_array));
    }
    if ($action == "getJeckpots") {


        $offset = DepurarCaracteres($_GET["offset"]);
        $limit = DepurarCaracteres($_GET["limit"]);
        $provider = DepurarCaracteres($_GET["provider"]);
        $category = DepurarCaracteres($_GET["category"]);

        $partner_id = DepurarCaracteres($_GET["partner_id"]);
        $search = DepurarCaracteres($_GET["search"]);
        $isMobile = DepurarCaracteres($_GET["isMobile"]);

        if ($category == 3) {

            $id = DepurarCaracteres($_GET["id"]);

            if ($id != "") {
                $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;

            }


            $Proveedor = new CMSProveedor("LIVECASINO", "", $partner_id);
        } else {

            $id = DepurarCaracteres($_GET["id"]);

            if ($id != "") {
                $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;

            }


            $Proveedor = new CMSProveedor("CASINO", "", $partner_id);
        }

        $Productos = $Proveedor->getProductos($category, $provider, $offset, $limit, $search, $isMobile);

        $Productos = json_decode($Productos);

        $data = $Productos->data;

        $return_array = array();
        $return_array["status"] = "ok";
        $return_array["total_count"] = $Productos->total;

        $games = array();


        $cont = 0;
        $cont = $offset;

        foreach ($data as $producto) {
            $seguir = true;

            $cont = $cont + 1;


            if ($isMobile && ($producto->descripcion == "Bet On Numbers" || $producto->descripcion == "Live Keno")) {
                $seguir = false;
            }

            /*

            if(!$isMobile && ($producto->producto_id =="558" || $producto->producto_id =="560" || $producto->producto_id =="548" || $producto->producto_id =="566" || $producto->producto_id =="570" || $producto->producto_id =="574" || $producto->producto_id =="550" || $producto->producto_id =="578" || $producto->producto_id =="582"|| $producto->producto_id =="586"|| $producto->producto_id =="590"|| $producto->producto_id =="594"|| $producto->producto_id =="598"|| $producto->producto_id =="602"|| $producto->producto_id =="552")){
                $seguir=false;
            }

            if($isMobile && ($producto->producto_id =="546" || $producto->producto_id =="562" || $producto->producto_id =="564" || $producto->producto_id =="568" || $producto->producto_id =="572" || $producto->producto_id =="576" || $producto->producto_id =="554" || $producto->producto_id =="580" || $producto->producto_id =="584"|| $producto->producto_id =="588"|| $producto->producto_id =="592"|| $producto->producto_id =="596"|| $producto->producto_id =="600"|| $producto->producto_id =="604"|| $producto->producto_id =="556")){
                $seguir=false;
            }
            */

            if ($seguir) {
                $game = array();
                $game["id"] = $producto->id;
                $game["name"] = $producto->descripcion;
                $game["producto_id"] = $producto->producto_id;
                $game["provider"] = $producto->proveedor->abreviado;
                $game["show_as_provider"] = $producto->proveedor->abreviado;
                $game["server_game_id"] = $producto->id;
                $game["status"] = "published";

                if ($producto->background == "") {
                    $producto->background = $bgCasino;
                }
                $game["background"] = $producto->background;
                $game["categories"] = array($producto->categoria->id);
                $game["cats"] = array("id" => $producto->categoria->id, "title" => $producto->categoria->descripcion);
                $game["extearnal_game_id"] = $producto->id;
                $game["front_game_id"] = $producto->externo_id;
                $game["game_options"] = "";
                $game["game_skin_id"] = "";
                $game["icon_2"] = str_replace("http:","https:",$producto->image);
                $game["icon_3"] =  str_replace("http:","https:",$producto->image2);
                $game["ratio"] = "16:9";
                $game["types"] = array(
                    "realMode" => 1,
                    "funMode" => 0

                );


                if(in_array($game["front_game_id"],array(
                    'gpas_aogggriffin_pop','gpas_aogrotu_pop','aogmt','gpas_aogiw_pop','gpas_aoggosun_pop','aogmm','wop','gpas_aogwfot_pop','gpas_aogww_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='mrj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'anwild','gpas_awild2pp_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_fballiwpp_pop','gpas_focashco_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'fdtjg'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='fdtjp-2';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_azbolipp_pop','gpas_ppayspp_pop','gpas_pigeonfspp_pop','gpas_sstrikepp_pop','gpas_soicepp_pop','gpas_tttotemspp_pop','gpas_mblockspp_pop','gpas_wlinxpp_pop','gpas_fmhitbarpp_pop','gpas_hgextremepp_pop','gpas_kgomoonpp_pop','gpas_dostormspp_pop','gpas_bokings2pp_pop','gpas_eemeraldspp_pop','gpas_betwildspp_pop','gpas_bbellspp_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='ptjp-1';
                }
                if(in_array($game["front_game_id"],array(
                    'tmccoy','asct','gpas_bgeorge_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='sljp-3';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_bbmwayslo_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='bjp-4';
                }
                if(in_array($game["front_game_id"],array(
                    'cbells'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jbells4-4';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_eape2_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='bjp-4';
                }
                if(in_array($game["front_game_id"],array(
                    'evj'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='evjj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'fcgz'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='drgj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'gpas_fbars_pop'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jhreelsj-2';
                }
                if(in_array($game["front_game_id"],array(
                    'fmjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='fmjp8';
                }
                if(in_array($game["front_game_id"],array(
                    'grbjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='grbjpj-1';
                }
                if(in_array($game["front_game_id"],array(
                    'jbells'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jbells4-4';
                }
                if(in_array($game["front_game_id"],array(
                    'jpgt'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='jpgt6-1';
                }
                if(in_array($game["front_game_id"],array(
                    'zcjbjp'
                ))){
                    $game["jackpot"]=1;
                    $game["front_game_id"]='drgj-1';
                }
                array_push($games, $game);

            }

            if (($cont == 12 || $cont == 36) && $id == "" && !$isMobile) {
                $game["id"] = "promo" . $producto->id;


                $game["name"] = $producto->nombre;

                $game["isPromo"] = true;
                $game["codePromo"] = 'casino-promo-2';
                $game["widthPercentage"] = 100;
                array_push($games, $game);

            }
            if (($cont == 24 || $cont == 48) && $id == "" && !$isMobile) {
                $game["id"] = "promo" . $producto->id;


                $game["name"] = $producto->nombre;

                $game["isPromo"] = true;
                $game["codePromo"] = 'casino-promo-1';
                $game["widthPercentage"] = 100;
                array_push($games, $game);

            }

            if (($cont == 12 || $cont == 36) && $id == "" && $category == "" && $isMobile) {
                $game["id"] = "promo" . $producto->id;


                $game["name"] = $producto->nombre;

                $game["isPromo"] = true;
                $game["codePromo"] = 'casino-promo-1';
                $game["widthPercentage"] = 100;
                array_push($games, $game);

            }
            if (($cont == 24 || $cont == 48) && $id == "" && $category == "" && $isMobile) {

                $game["id"] = "promo" . $producto->id;


                $game["name"] = $producto->nombre;

                $game["isPromo"] = true;
                $game["codePromo"] = 'casino-promo-2';
                $game["widthPercentage"] = 100;
                array_push($games, $game);

            }

        }

        $return_array["games"] = $games;

        print_r(json_encode($return_array));
    }

} catch (Exception $e) {
    if($_ENV['debug']){
        print_r($e);
    }

    $return_array = array();
    $return_array["status"] = "ok";
    $return_array["total_count"] = 0;

    print_r(json_encode($return_array));

}
/**
 * Depurar caracteres
 *
 * @param String $texto_depurar texto a depurar
 *
 * @return String $texto_depurar texto depurado
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function DepurarCaracteres($texto_depurar)
{

    $texto_depurar = str_replace("'", "", $texto_depurar);
    $texto_depurar = str_replace('"', "", $texto_depurar);
    $texto_depurar = str_replace(">", "", $texto_depurar);
    $texto_depurar = str_replace("<", "", $texto_depurar);
    $texto_depurar = str_replace("[", "", $texto_depurar);
    $texto_depurar = str_replace("]", "", $texto_depurar);
    $texto_depurar = str_replace("{", "", $texto_depurar);
    $texto_depurar = str_replace("}", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("`", "", $texto_depurar);
    $texto_depurar = str_replace("|", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("%", "", $texto_depurar);
    $texto_depurar = str_replace("&", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("~", "", $texto_depurar);
    $texto_depurar = str_replace("+", "", $texto_depurar);
    $texto_depurar = str_replace("^", "", $texto_depurar);
    $texto_depurar = str_replace("/", "", $texto_depurar);
    //$texto_retornar = addslashes($texto_depurar);

    $unwanted_array = array(     '©' => 'c', '®' => 'r',
        '̊'=>'','̧'=>'','̨'=>'','̄'=>'','̱'=>'',
        'Á'=>'a','á'=>'a','À'=>'a','à'=>'a','Ă'=>'a','ă'=>'a','ắ'=>'a','Ắ'=>'A','Ằ'=>'A',
        'ằ'=>'a','ẵ'=>'a','Ẵ'=>'A','ẳ'=>'a','Ẳ'=>'A','Â'=>'a','â'=>'a','ấ'=>'a','Ấ'=>'A',
        'ầ'=>'a','Ầ'=>'a','ẩ'=>'a','Ẩ'=>'A','Ǎ'=>'a','ǎ'=>'a','Å'=>'a','å'=>'a','Ǻ'=>'a',
        'ǻ'=>'a','Ä'=>'a','ä'=>'a','ã'=>'a','Ã'=>'A','Ą'=>'a','ą'=>'a','Ā'=>'a','ā'=>'a',
        'ả'=>'a','Ả'=>'a','Ạ'=>'A','ạ'=>'a','ặ'=>'a','Ặ'=>'A','ậ'=>'a','Ậ'=>'A','Æ'=>'ae',
        'æ'=>'ae','Ǽ'=>'ae','ǽ'=>'ae','ẫ'=>'a','Ẫ'=>'A',
        'Ć'=>'c','ć'=>'c','Ĉ'=>'c','ĉ'=>'c','Č'=>'c','č'=>'c','Ċ'=>'c','ċ'=>'c','Ç'=>'c','ç'=>'c',
        'Ď'=>'d','ď'=>'d','Ḑ'=>'D','ḑ'=>'d','Đ'=>'d','đ'=>'d','Ḍ'=>'D','ḍ'=>'d','Ḏ'=>'D','ḏ'=>'d','ð'=>'d','Ð'=>'D',
        'É'=>'e','é'=>'e','È'=>'e','è'=>'e','Ĕ'=>'e','ĕ'=>'e','ê'=>'e','ế'=>'e','Ế'=>'E','ề'=>'e',
        'Ề'=>'E','Ě'=>'e','ě'=>'e','Ë'=>'e','ë'=>'e','Ė'=>'e','ė'=>'e','Ę'=>'e','ę'=>'e','Ē'=>'e',
        'ē'=>'e','ệ'=>'e','Ệ'=>'E','Ə'=>'e','ə'=>'e','ẽ'=>'e','Ẽ'=>'E','ễ'=>'e',
        'Ễ'=>'E','ể'=>'e','Ể'=>'E','ẻ'=>'e','Ẻ'=>'E','ẹ'=>'e','Ẹ'=>'E',
        'ƒ'=>'f',
        'Ğ'=>'g','ğ'=>'g','Ĝ'=>'g','ĝ'=>'g','Ǧ'=>'G','ǧ'=>'g','Ġ'=>'g','ġ'=>'g','Ģ'=>'g','ģ'=>'g',
        'H̲'=>'H','h̲'=>'h','Ĥ'=>'h','ĥ'=>'h','Ȟ'=>'H','ȟ'=>'h','Ḩ'=>'H','ḩ'=>'h','Ħ'=>'h','ħ'=>'h','Ḥ'=>'H','ḥ'=>'h',
        'Ỉ'=>'I','Í'=>'i','í'=>'i','Ì'=>'i','ì'=>'i','Ĭ'=>'i','ĭ'=>'i','Î'=>'i','î'=>'i','Ǐ'=>'i','ǐ'=>'i',
        'Ï'=>'i','ï'=>'i','Ḯ'=>'I','ḯ'=>'i','Ĩ'=>'i','ĩ'=>'i','İ'=>'i','Į'=>'i','į'=>'i','Ī'=>'i','ī'=>'i',
        'ỉ'=>'I','Ị'=>'I','ị'=>'i','Ĳ'=>'ij','ĳ'=>'ij','ı'=>'i',
        'Ĵ'=>'j','ĵ'=>'j',
        'Ķ'=>'k','ķ'=>'k','Ḵ'=>'K','ḵ'=>'k',
        'Ĺ'=>'l','ĺ'=>'l','Ľ'=>'l','ľ'=>'l','Ļ'=>'l','ļ'=>'l','Ł'=>'l','ł'=>'l','Ŀ'=>'l','ŀ'=>'l',
        'Ń'=>'n','ń'=>'n','Ň'=>'n','ň'=>'n','Ñ'=>'N','ñ'=>'n','Ņ'=>'n','ņ'=>'n','Ṇ'=>'N','ṇ'=>'n','Ŋ'=>'n','ŋ'=>'n',
        'Ó'=>'o','ó'=>'o','Ò'=>'o','ò'=>'o','Ŏ'=>'o','ŏ'=>'o','Ô'=>'o','ô'=>'o','ố'=>'o','Ố'=>'O','ồ'=>'o',
        'Ồ'=>'O','ổ'=>'o','Ổ'=>'O','Ǒ'=>'o','ǒ'=>'o','Ö'=>'o','ö'=>'o','Ő'=>'o','ő'=>'o','Õ'=>'o','õ'=>'o',
        'Ø'=>'o','ø'=>'o','Ǿ'=>'o','ǿ'=>'o','Ǫ'=>'O','ǫ'=>'o','Ǭ'=>'O','ǭ'=>'o','Ō'=>'o','ō'=>'o','ỏ'=>'o',
        'Ỏ'=>'O','Ơ'=>'o','ơ'=>'o','ớ'=>'o','Ớ'=>'O','ờ'=>'o','Ờ'=>'O','ở'=>'o','Ở'=>'O','ợ'=>'o','Ợ'=>'O',
        'ọ'=>'o','Ọ'=>'O','ọ'=>'o','Ọ'=>'O','ộ'=>'o','Ộ'=>'O','ỗ'=>'o','Ỗ'=>'O','ỡ'=>'o','Ỡ'=>'O',
        'Œ'=>'oe','œ'=>'oe',
        'ĸ'=>'k',
        'Ŕ'=>'r','ŕ'=>'r','Ř'=>'r','ř'=>'r','ṙ'=>'r','Ŗ'=>'r','ŗ'=>'r','Ṛ'=>'R','ṛ'=>'r','Ṟ'=>'R','ṟ'=>'r',
        'S̲'=>'S','s̲'=>'s','Ś'=>'s','ś'=>'s','Ŝ'=>'s','ŝ'=>'s','Š'=>'s','š'=>'s','Ş'=>'s','ş'=>'s',
        'Ṣ'=>'S','ṣ'=>'s','Ș'=>'S','ș'=>'s',
        'ſ'=>'z','ß'=>'ss','Ť'=>'t','ť'=>'t','Ţ'=>'t','ţ'=>'t','Ṭ'=>'T','ṭ'=>'t','Ț'=>'T',
        'ț'=>'t','Ṯ'=>'T','ṯ'=>'t','™'=>'tm','Ŧ'=>'t','ŧ'=>'t',
        'Ú'=>'u','ú'=>'u','Ù'=>'u','ù'=>'u','Ŭ'=>'u','ŭ'=>'u','Û'=>'u','û'=>'u','Ǔ'=>'u','ǔ'=>'u','Ů'=>'u','ů'=>'u',
        'Ü'=>'u','ü'=>'u','Ǘ'=>'u','ǘ'=>'u','Ǜ'=>'u','ǜ'=>'u','Ǚ'=>'u','ǚ'=>'u','Ǖ'=>'u','ǖ'=>'u','Ű'=>'u','ű'=>'u',
        'Ũ'=>'u','ũ'=>'u','Ų'=>'u','ų'=>'u','Ū'=>'u','ū'=>'u','Ư'=>'u','ư'=>'u','ứ'=>'u','Ứ'=>'U','ừ'=>'u','Ừ'=>'U',
        'ử'=>'u','Ử'=>'U','ự'=>'u','Ự'=>'U','ụ'=>'u','Ụ'=>'U','ủ'=>'u','Ủ'=>'U','ữ'=>'u','Ữ'=>'U',
        'Ŵ'=>'w','ŵ'=>'w',
        'Ý'=>'y','ý'=>'y','ỳ'=>'y','Ỳ'=>'Y','Ŷ'=>'y','ŷ'=>'y','ÿ'=>'y','Ÿ'=>'y','ỹ'=>'y','Ỹ'=>'Y','ỷ'=>'y','Ỷ'=>'Y',
        'Z̲'=>'Z','z̲'=>'z','Ź'=>'z','ź'=>'z','Ž'=>'z','ž'=>'z','Ż'=>'z','ż'=>'z','Ẕ'=>'Z','ẕ'=>'z',
        'þ'=>'p','ŉ'=>'n','А'=>'a','а'=>'a','Б'=>'b','б'=>'b','В'=>'v','в'=>'v','Г'=>'g','г'=>'g','Ґ'=>'g','ґ'=>'g',
        'Д'=>'d','д'=>'d','Е'=>'e','е'=>'e','Ё'=>'jo','ё'=>'jo','Є'=>'e','є'=>'e','Ж'=>'zh','ж'=>'zh','З'=>'z','з'=>'z',
        'И'=>'i','и'=>'i','І'=>'i','і'=>'i','Ї'=>'i','ї'=>'i','Й'=>'j','й'=>'j','К'=>'k','к'=>'k','Л'=>'l','л'=>'l',
        'М'=>'m','м'=>'m','Н'=>'n','н'=>'n','О'=>'o','о'=>'o','П'=>'p','п'=>'p','Р'=>'r','р'=>'r','С'=>'s','с'=>'s',
        'Т'=>'t','т'=>'t','У'=>'u','у'=>'u','Ф'=>'f','ф'=>'f','Х'=>'h','х'=>'h','Ц'=>'c','ц'=>'c','Ч'=>'ch','ч'=>'ch',
        'Ш'=>'sh','ш'=>'sh','Щ'=>'sch','щ'=>'sch','Ъ'=>'-',
        'ъ'=>'-','Ы'=>'y','ы'=>'y','Ь'=>'-','ь'=>'-',
        'Э'=>'je','э'=>'je','Ю'=>'ju','ю'=>'ju','Я'=>'ja','я'=>'ja','א'=>'a','ב'=>'b','ג'=>'g','ד'=>'d','ה'=>'h','ו'=>'v',
        'ז'=>'z','ח'=>'h','ט'=>'t','י'=>'i','ך'=>'k','כ'=>'k','ל'=>'l','ם'=>'m','מ'=>'m','ן'=>'n','נ'=>'n','ס'=>'s','ע'=>'e',
        'ף'=>'p','פ'=>'p','ץ'=>'C','צ'=>'c','ק'=>'q','ר'=>'r','ש'=>'w','ת'=>'t'
    );
    $texto_depurar = strtr( $texto_depurar , $unwanted_array );




    $c = null;
    return $texto_depurar;
}
