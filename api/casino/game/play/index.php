<?php

/* Desactiva errores, permite acceso CORS y define encabezados permitidos en PHP. */
ini_set('display_errors', 'OFF');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');

/* Configura cabeceras CORS y carga autoload de dependencias en PHP. */
header('Access-Control-Allow-Methods: GET, POST,    OPTIONS,PUT');

require(__DIR__ . '../../../../vendor/autoload.php');

use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\cms\CMSCategoria;
use Backend\cms\CMSProveedor;
use Backend\dto\ProductoMandante;
use Backend\dto\ConfigurationEnvironment;

$_ENV["enabledConnectionGlobal"] = 1;

/* verifica un archivo y detiene la ejecución si contiene "BLOCKED". */
try{
    $responseEnable= file_get_contents(__DIR__.'/../../../../logSit/enabled');
}catch (Exception $e){}

if($responseEnable=='BLOCKED'){
    exit();
}

/**
 * Created by IntelliJ IDEA.
 * User: macbook
 * Date: 20/09/17
 * Time: 6:37 PM
 */

/* activa el modo de depuración si se cumple una condición con un parámetro. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X'){
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$gameGid = $_GET["gameGid"];

/* obtiene parámetros de la URL para usarlos en un juego. */
$gameid = $_GET["gameid"];
$mode = $_GET["mode"];
$provider = $_GET["provider"];
$lan = $_GET["lan"];
$partnerid = $_GET["partnerid"];
$user_token = $_GET["token"];

/* obtiene valores de una solicitud GET y establece un token de usuario. */
$isMobile = $_GET["isMobile"];
$miniGame = $_GET["miniGame"];
$minimode = $_GET["minimode"];

if($user_token == "null"){
    $user_token="";
}

if($_REQUEST["TEST"] == 'TEST'){
    ?>
    <script src="https://dga.pragmaticplaylive.net/dgaAPI.js"></script>

<?php
    exit();
}
$bgCasino ='';


/* verifica y actualiza el token del usuario y su idioma. */
if($user_token == 'FUN'){
    $user_token='';
}
if($lan=="" || $lan == "undefined"){
    $lan = $_GET["lang"];
}

/* establece un idioma por defecto y define un fondo según el partner. */
if($lan=="" || $lan == "undefined"){
    $lan="es";
}

switch ($partnerid){
    case '0':
        $bgCasino='https://images.doradobet.com/productos/casino/casino-background.jpg';
        break;

    case '3':
        $bgCasino='https://images.virtualsoft.tech/site/miravalle/bgCasino.jpg';
        break;

    default:
        $bgCasino='';
        break;
}

/* Código que recibe un parámetro y ajusta el valor si corresponde a un proveedor específico. */
$in_app = $_GET["in_app"];

if($provider == 'Ezugi'){
    $provider = 'EZZG';
}

/* ajusta el formato del nombre del proveedor a mayúsculas específicas. */
if($provider == 'Betgamestv'){
    $provider = 'BETGAMESTV';
}

if($provider == 'Playngo'){
    $provider = 'PLAYNGO';
}

/* ajusta el proveedor y el ID del juego en entorno de desarrollo. */
$provider = strtoupper($provider);
$ConfigurationEnvironment = new ConfigurationEnvironment();

if($ConfigurationEnvironment->isDevelopment()){
    if($gameid==27){
        $gameid=312;
        $provider='XPRESS';
    }
}

if($gameid==51434){
    $gameid='53860';
}

$miniGame = false;
$Game = new \Backend\integrations\casino\Game($gameid, $mode, $provider, $lan, strtolower($partnerid), $user_token, $isMobile,$miniGame, $minimode);

try {

    $URL = $Game->getURL($gameGid);
    $proveedor = $URL->proveedor;

    if($provider == "UNDEFINED" || $provider == "undefined" || $provider == ""){
        $provider = $URL->proveedor;
    }

    if($URL->proveedor !='' && $URL->proveedor !=null){
        $provider = $URL->proveedor;
    }

    if ($provider == '' || $provider == null) {
      try {
        $ProductoMandante = new ProductoMandante("", "", $gameid);
        $Producto = new Producto($ProductoMandante->productoId);
        $Proveedor = new Proveedor($Producto->proveedorId);
        $provider = $Proveedor->abreviado;
        if($provider =="PLAYTECH"){
            $Subproveedor = new \Backend\dto\Subproveedor($Producto->subproveedorId);
            if($Subproveedor->abreviado == "PLAYTECHLIVE"){
                $provider = 'PLAYTECHLIVE';
            }elseif ($Subproveedor->abreviado == "POKERPLAYTECH") {
              $provider = 'POKERPLAYTECH';
            }
        }
      } catch (Exception $e) {
        throw new Exception("Juego no disponible ", "10000");
      }
    }

    if($_ENV['debug']) {
        print_r($URL);
    }

    //if ($proveedor != null && $proveedor != "" && $proveedor != "PLAYNGO" && $proveedor != "PLAYSON"  && $proveedor != "FAZI" &&   $proveedor != "TOMHORN" &&   $proveedor != "GDR" &&   $proveedor != "CTGAMING") {
if(true){
        if ($proveedor === "INB") {

            print_r('
        <html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>


</head>
<body>
        

        <div id="slot" style="width: 100%; height: 100%; position: relative;" class="text-left">
            <div id="game-content"></div>
        </div>  
        <script>
        var JSON_DATA = {
            "api": "http://flash-api.inbet.cc:8080/",
            "applications": {
              "a_ec": {
                "app": [
                  "a_ec-201702281539"
                ],
                "html5": {
                  "app": [
                    "a_ec-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_ec",
                "name": [
                  {
                    "en": "Explosive Cocktail"
                  }
                ],
                "position": 1,
                "lines": 5,      
                "preview": "thumb/a_ec.png",
                "source": "a_ec",
                "type": "slot"
              },
              "a_h": {
                "app": [
                  "a_h-201702281539"
                ],
                "html5": {
                  "app": [
                    "a_h-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_h",
                "name": [
                  {
                    "en": "Houdini"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/a_h.png",
                "source": "a_h",
                "type": "slot"
              },
              "a_hp": {
                "app": [
                  "a_hp-201702281544"
                ],
                "html5": {
                  "app": [
                    "a_hp-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_hp",
                "name": [
                  {
                    "en": "Heart Of Princess"
                  }
                ],
                "position": 1,
                "lines": 9,      
                "preview": "thumb/a_hp.png",
                "source": "a_hp",
                "type": "slot"
              },
              "a_jc": {
                "app": [
                  "a_jc-201702281544"
                ],
                "html5": {
                  "app": [
                    "a_jc-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_jc",
                "name": [
                  {
                    "en": "James Cook"
                  }
                ],
                "position": 1,
                "lines": 9,      
                "preview": "thumb/a_jc.png",
                "source": "a_jc",
                "type": "slot"
              },
              "a_l": {
                "app": [
                  "a_l-201702281544"
                ],
                "html5": {
                  "app": [
                    "a_l-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_l",
                "name": [
                  {
                    "en": "Limoncello"
                  }
                ],
                "position": 1,
                "lines": 9,      
                "preview": "thumb/a_l.png",
                "source": "a_l",
                "type": "slot"
              },
              "a_ml": {
                "app": [
                  "a_ml-201702281704"
                ],
                "html5": {
                  "app": [
                    "a_ml-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_ml",
                "name": [
                  {
                    "en": "Magic Luck"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/a_ml.png",
                "source": "a_ml",
                "type": "slot"
              },
              "a_op": {
                "app": [
                  "a_op-201702281539"
                ],
                "html5": {
                  "app": [
                    "a_op-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_op",
                "name": [
                  {
                    "en": "Ocean Pearl"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/a_op.png",
                "source": "a_op",
                "type": "slot"
              },
              "a_phf": {
                "app": [
                  "a_phf-201702281539"
                ],
                "html5": {
                  "app": [
                    "a_phf-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_phf",
                "name": [
                  {
                    "en": "Phoenixs Fruits"
                  }
                ],
                "position": 1,
                "lines": 5,
                "preview": "thumb/a_phf.png",
                "source": "a_phf",
                "type": "slot"
              },
              "a_soa": {
                "app": [
                  "a_soa-201702281544"
                ],
                "html5": {
                  "app": [
                    "a_soa-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "a_soa",
                "name": [
                  {
                    "en": "Scroll Of Anubis"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/a_soa.png",
                "source": "a_soa",
                "type": "slot"
              },
              "bet_bingo37": {
                "app": [
                  "bingo37-201601261900"
                ],
                "html5": {
                  "app": [
                    "bet_bingo37-201711151726"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "bet_bingo37",
                "name": [
                  {
                    "en": "Bingo 37"
                  }
                ],
                "position": 2,
                "preview": "thumb/bingo37.png",
                "source": 1009,
                "type": "betting"
              },
              "bet_bingo37b": {
                "app": [
                  "bingo37b-201601261900"
                ],
                "html5": {
                  "app": [
                    "bet_bingo37b-201711161905"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "bet_bingo37b",
                "name": [
                  {
                    "en": "Bingo 37 Ticket"
                  }
                ],
                "position": 2,
                "preview": "thumb/bingo37b.png",
                "source": 1009,
                "type": "betting"
              },
              "bet_dogs3d": {
                "html5": {
                  "app": [
                    "bet_dogs3d-201710041717"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "bet_dogs3d",
                "name": [
                  {
                    "en": "Dogs 3D"
                  }
                ],
                "position": 2,
                "preview": "thumb/bet_dogs3d_icon.png",
                "source": 77801,
                "type": "betting"
              },
              "bet_dogs6": {
                "html5": {
                  "app": [
                    "bet_dogs6-201710101857"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "bet_dogs6",
                "name": [
                  {
                    "en": "Bet on Dogs"
                  }
                ],
                "position": 2,
                "preview": "thumb/bet_dogs6.png",
                "source": 778,
                "type": "betting"
              },
              "bet_fortuna": {
                "app": [
                  "bet_fortuna-201608251915"
                ],
                "html5": {
                  "app": [
                    "bet_fortuna-201711211649"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "bet_fortuna",
                "name": [
                  {
                    "en": "Fortuna"
                  }
                ],
                "position": 2,
                "preview": "thumb/fortuna.png",
                "source": 1018,
                "type": "betting"
              },
              "bet_horses6": {
                "html5": {
                  "app": [
                    "bet_horses6-201710101857"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "bet_horses6",
                "name": [
                  {
                    "en": "Bet on Horses"
                  }
                ],
                "position": 2,
                "preview": "thumb/bet_horses6.png",
                "source": 779,
                "type": "betting"
              },
              "bet_keno": {
                "app": [
                  "keno_bet_hx"
                ],
                "html5": {
                  "app": [
                    "bet_keno-201707201722"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "bet_keno",
                "name": [
                  {
                    "en": "Keno Live"
                  }
                ],
                "position": 2,
                "preview": "thumb/kenolive-2.png",
                "source": 999,
                "type": "keno"
              },
              "bet_roul": {
                "html5": {
                  "app": [
                    "bet_roul-201708151438"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "bet_roul",
                "name": [
                  {
                    "en": "Live Roulette"
                  }
                ],
                "position": 2,
                "preview": "thumb/bet_roul.png",
                "source": 1204,
                "type": "betting"
              },
              "bet_tron3d": {
                "html5": {
                  "app": [
                    "bet_tron3d-201709251550"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "bet_tron3d",
                "name": [
                  {
                    "en": "Tron 3D"
                  }
                ],
                "position": 2,
                "preview": "thumb/tron3d.png",
                "source": 77802,
                "type": "betting"
              },
              "bets_poker3x": {
                "html5": {
                  "app": [
                    "bets_poker3x-201711211437"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "bets_poker3x",
                "name": [
                  {
                    "en": "Bet on Poker"
                  }
                ],
                "position": 2,
                "preview": "thumb/betonpoker.png",
                "source": 888,
                "type": "betting"
              },
              "blackjack": {
                "html5": {
                  "app": [
                    "blackjack-201707241832"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "blackjack",
                "name": [
                  {
                    "en": "Black Jack"
                  }
                ],
                "position": 2,
                "preview": "thumb/blackjack.png",
                "source": 886,
                "type": "betting"
              },
              "d_ch": {
                "app": [
                  "chukcha_slot-201507220118"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Chukchi Man"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/d_ch.png",
                "source": "d_ch",
                "type": "slot"
              },
              "e_keno": {
                "app": [
                  "turbokeno-201603011712"
                ],
                "html5": {
                  "app": [
                    "turbokeno-201707201722"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "e_keno",
                "name": [
                  {
                    "en": "Turbo Keno"
                  }
                ],
                "position": 2,
                "preview": "thumb/e_keno.png",
                "source": "turbokeno",
                "type": "egame"
              },
              "e_keno_term": {
                "html5": {
                  "app": [
                    "e_keno_term-201711231800"
                  ], 
                  "mainjs": "game.js"
                }, 
                "lang": [
                  "en"
                ], 
                "load_only": "html5", 
                "loader": "e_keno_term", 
                "name": [
                  {
                    "en": "Keno T+"
                  }
                ], 
                "position": 2, 
                "preview": "thumb/e_keno_term.png", 
                "source": "turbokeno_term", 
                "type": "egame"
              },     
              "e_mr": {
                "app": [
                  "mini_roulette-201604041555"
                ],
                "html5": {
                  "app": [
                    "mini_roulette-201707201722"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "e_mr",
                "name": [
                  {
                    "en": "Mini Roulette"
                  }
                ],
                "position": 2,
                "preview": "thumb/e_mr.png",
                "source": "miniroulette",
                "type": "egame"
              },
              "e_sicbo": {
                "app": [
                  "sicbo-201507171703"
                ],
                "html5": {
                  "app": [
                    "sicbo-201508031703"
                  ],
                  "mainjs": "Sicbo.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "e_sicbo",
                "name": [
                  {
                    "en": "Sicbo"
                  }
                ],
                "position": 2,
                "preview": "thumb/e_sicbo.png",
                "source": "sicbo",
                "type": "egame"
              },
              "e_sicboaus": {
                "app": [
                  "sicbo_aus-201506231515"
                ],
                "html5": {
                  "app": [
                    "sicbo_aus-201508031703"
                  ],
                  "mainjs": "Sicbo_aus.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "e_sicboaus",
                "name": [
                  {
                    "en": "Sicbo Australia"
                  }
                ],
                "position": 2,
                "preview": "thumb/e_sicboaus.png",
                "source": "sicbo_aus",
                "type": "egame"
              },
              "fortuna18": {
                "html5": {
                  "app": [
                    "fortuna18-201707271512"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "fortuna18",
                "name": [
                  {
                    "en": "Fortune 18"
                  }
                ],
                "position": 2,
                "preview": "thumb/fortuna18.png",
                "source": 1013,
                "type": "betting"
              },
              "fortuna_black": {
                "app": [
                  "fortuna_black-201608311700"
                ],
                "html5": {
                  "app": [
                    "fortuna_black-201609152010"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "fortuna_black",
                "name": [
                  {
                    "en": "Fortune black"
                  }
                ],
                "position": 2,
                "preview": "thumb/fortuna_black.png",
                "source": 1019,
                "type": "betting"
              },
              "g_ah": {
                "app": [
                  "alwayshot_slot-201506031715"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Always Hot"
                  }
                ],
                "position": 1,
                "lines": 5,
                "preview": "thumb/g_ah.png",
                "source": "g_ah",
                "type": "slot"
              },
              "g_atl": {
                "app": [
                  "attila_slot-201506031703"
                ],
                "html5": {
                  "app": [
                    "g_atl-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_atl",
                "name": [
                  {
                    "en": "Attila"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_atl.png",
                "source": "u_a",
                "type": "slot"
              },
              "g_bgb": {
                "app": [
                  "g_bgb-201703011124"
                ],
                "html5": {
                  "app": [
                    "g_bgb-201707181158"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_bgb",
                "name": [
                  {
                    "en": "Bananas Go Bahamas"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_bgb.png",
                "source": "g_bgb",
                "type": "slot"
              },
              "g_bor": {
                "html5": {
                  "app": [
                    "g_bor-201707181158"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "g_bor",
                "name": [
                  {
                    "en": "Book Of Ra"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_bor.png",
                "source": "g_bor",
                "type": "slot"
              },
              "g_bor_d": {
                "app": [
                  "g_bor_d-201703011053"
                ],
                "html5": {
                  "app": [
                    "g_bor_d-201710251926"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_bor_d",
                "name": [
                  {
                    "en": "Book Of Ra DeLuxe"
                  }
                ],
                "position": 2,
                "lines": 10,      
                "preview": "thumb/g_bor_d.png",
                "source": "u_bor_d",
                "type": "slot"
              },
              "g_bp": {
                "app": [
                  "blackpearl_slot-201506031704"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Black Pearl"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_bp.png",
                "source": "m_bp",
                "type": "slot"
              },
              "g_bs": {
                "app": [
                  "banana_slot-201506031703"
                ],
                "html5": {
                  "app": [
                    "g_bs-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_bs",
                "name": [
                  {
                    "en": "Banana Splash"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_bs.png",
                "source": "u_bs",
                "type": "slot"
              },
              "g_ch": {
                "app": [
                  "caribeanholidays_slot-201506031704"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Caribbean Holidays"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_ch.png",
                "source": "g_ch",
                "type": "slot"
              },
              "g_col": {
                "app": [
                  "g_col-201702281548"
                ],
                "html5": {
                  "app": [
                    "g_col-201707181158"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_col",
                "name": [
                  {
                    "en": "Columbus"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_col.png",
                "source": "g_col",
                "type": "slot"
              },
              "g_coldl": {
                "app": [
                  "g_coldl-201702281548"
                ],
                "html5": {
                  "app": [
                    "g_coldl-201707181158"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_coldl",
                "name": [
                  {
                    "en": "Columbus DeLuxe"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_coldl.png",
                "source": "g_col",
                "type": "slot"
              },
              "g_dap": {
                "app": [
                  "g_dap-201702281548"
                ],
                "html5": {
                  "app": [
                    "g_dap-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_dap",
                "name": [
                  {
                    "en": "Dolphins Pearl"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_dap.png",
                "source": "u_dap",
                "type": "slot"
              },
              "g_dapdl": {
                "app": [
                  "dolphinspearldx_slot-201506031705"
                ],    
                "html5": {
                  "app": [
                    "g_dapdl-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_dapdl",
                "name": [
                  {
                    "en": "Dolphins Pearl DeLuxe"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_dapdl.png",
                "source": "u_dap_d",
                "type": "slot"
              },
              "g_dom": {
                "app": [
                  "dynastyofming_slot-201506031705"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Dynasty Of Ming"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_dom.png",
                "source": "g_dom",
                "type": "slot"
              },
              "g_ec": {
                "app": [
                  "emperorschina_slot-201506031706"
                ],
                "html5": {
                  "app": [
                    "g_ec-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_ec",
                "name": [
                  {
                    "en": "Emperors China"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_ec.png",
                "source": "u_ec",
                "type": "slot"
              },
              "g_gg": {
                "app": [
                  "gryphonsgold_slot-201506031706"
                ],        
                "html5": {
                  "app": [
                    "g_gg-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_gg",
                "name": [
                  {
                    "en": "Gryphons Gold"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_gg.png",
                "source": "u_gg",
                "type": "slot"
              },
              "g_hog": {
                "app": [
                  "heartofgold_slot-201506031711"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Heart Of Gold"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_hog.png",
                "source": "g_hog",
                "type": "slot"
              },
              "g_ht": {
                "app": [
                  "hattrick_slot-201506031713"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Hat Trick"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_ht.png",
                "source": "g_ht",
                "type": "slot"
              },
              "g_ill": {
                "app": [
                  "illusionist_slot-201506031712"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Illusionist"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_ill.png",
                "source": "g_i",
                "type": "slot"
              },
              "g_jj": {
                "app": [
                  "justjewels_slot-201506031706"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Just Jewels"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_jj.png",
                "source": "g_jj",
                "type": "slot"
              },
              "g_jj_d": {
                "app": [
                  "justjewelsdl_slot-201506031712"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Just Jewels DeLuxe"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_jj_d.png",
                "source": "g_jj_d",
                "type": "slot"
              },
              "g_koc": {
                "app": [
                  "king_of_card-201506031707"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "King Of Cards"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_koc.png",
                "source": "g_koc",
                "type": "slot"
              },
              "g_lch": {
                "app": [
                  "lemoncherry_slot-201506031712"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Lemon Cherry"
                  }
                ],
                "position": 1,
                "lines": 3,
                "preview": "thumb/g_lch.png",
                "source": "g_lch",
                "type": "slot"
              },
              "g_llc": {
                "app": [
                  "g_llc-201702281548"
                ],
                "html5": {
                  "app": [
                    "g_llc-201707181158"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_llc",
                "name": [
                  {
                    "en": "Lucky Ladys Charm"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_llc.png",
                "source": "g_llc",
                "type": "slot"
              },
              "g_llcdl": {
                "app": [
                  "luckyladiescharmdl_slot-201506031707"
                ],
                "html5": {
                  "app": [
                    "g_llcdl-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_llcdl",
                "name": [
                  {
                    "en": "Lucky Ladys Charm DeLuxe"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_llcdl.png",
                "source": "u_llc_d",
                "type": "slot"
              },
              "g_mc": {
                "app": [
                  "megacherry_slot-201506031715"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Mega Cherry"
                  }
                ],
                "position": 1,
                "lines": 5,
                "preview": "thumb/g_mc.png",
                "source": "g_mc",
                "type": "slot"
              },
              "g_mg": {
                "app": [
                  "moneygame_slot-201506031708"
                ],
                "html5": {
                  "app": [
                    "g_mg-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_mg",
                "name": [
                  {
                    "en": "The Money Game"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_mg.png",
                "source": "u_tmg",
                "type": "slot"
              },
              "g_mp": {
                "app": [
                  "g_mp-201702281548"
                ],
                "html5": {
                  "app": [
                    "g_mp-201707181158"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_mp",
                "name": [
                  {
                    "en": "Marco Polo"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_mp.png",
                "source": "g_mp",
                "type": "slot"
              },
              "g_ob": {
                "app": [
                  "oliversbar_slot-201506031708"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Olivers Bar"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_ob.png",
                "source": "g_ob",
                "type": "slot"
              },
              "g_pf": {
                "app": [
                  "polarfox_slot-201506031709"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Polar Fox"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_pf.png",
                "source": "g_pf",
                "type": "slot"
              },
              "g_pg2": {
                "app": [
                  "pharaonsgold2_slot-201506031708"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Pharaohs Gold II"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_pg2.png",
                "source": "g_pg2",
                "type": "slot"
              },
              "g_pg3": {
                "app": [
                  "pharaonsgold3_slot-201506031709"
                ],
                "html5": {
                  "app": [
                    "g_pg3-201706141707"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_pg3",
                "name": [
                  {
                    "en": "Pharaons Gold III"
                  }
                ],
                "position": 2,
                "lines": 10,
                "preview": "thumb/g_pg3.png",
                "source": "u_pg3",
                "type": "slot"
              },
              "g_qoh": {
                "app": [
                  "queenofhearts_slot-201506031712"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Queen Of Hearts"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_qoh.png",
                "source": "g_qoh",
                "type": "slot"
              },
              "g_qoh_d": {
                "app": [
                  "queenofheartsdl_slot-201506031713"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Queen Of Hearts DeLuxe"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_qoh_d.png",
                "source": "g_qoh_d",
                "type": "slot"
              },
              "g_rt": {
                "app": [
                  "royalt_slot-201506031709"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Royal Treasures"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_rt.png",
                "source": "g_rt",
                "type": "slot"
              },
              "g_sf": {
                "app": [
                  "secretforest_slot-201506031710"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Secret Forest"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_sf.png",
                "source": "g_sf",
                "type": "slot"
              },
              "g_sh": {
                "app": [
                  "g_sh-201702281548"
                ],
                "html5": {
                  "app": [
                    "g_sh-201707181158"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "g_sh",
                "name": [
                  {
                    "en": "Sizzling Hot"
                  }
                ],
                "position": 1,
                "lines": 5,
                "preview": "thumb/g_sh.png",
                "source": "g_sh",
                "type": "slot"
              },
              "g_t": {
                "app": [
                  "threee_slot-201506031715"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Threee!"
                  }
                ],
                "position": 1,
                "lines": 5,
                "preview": "thumb/g_t.png",
                "source": "g_t",
                "type": "slot"
              },
              "g_teg": {
                "app": [
                  "eurogame_slot-201506031706"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "The Euro Game"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_teg.png",
                "source": "g_teg",
                "type": "slot"
              },
              "g_uh": {
                "app": [
                  "ultrahot_slot-201506031716"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Ultra Hot"
                  }
                ],
                "position": 1,
                "lines": 5,
                "preview": "thumb/g_uh.png",
                "source": "g_uh",
                "type": "slot"
              },
              "g_um": {
                "app": [
                  "unicornmagic_slot-201506031710"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Unicorn Magic"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_um.png",
                "source": "g_um",
                "type": "slot"
              },
              "g_wf": {
                "app": [
                  "wonderfulflute_slot-201506031710"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Wonderful Flute"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/g_wf.png",
                "source": "g_wf",
                "type": "slot"
              },
              "g_xh": {
                "app": [
                  "xtrahot_slot-201506031716"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Xtra Hot"
                  }
                ],
                "position": 1,
                "lines": 5,
                "preview": "thumb/g_xh.png",
                "source": " g_xh",
                "type": "slot"
              },
              "i_cm": {
                "app": [
                  "crazymonkey-201506031714"
                ],
                "html5": {
                  "app": [
                    "monkey-201605302030"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Crazy Monkey"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/i_cm.png",
                "source": "i_cm",
                "type": "slot"
              },
              "i_fc": {
                "app": [
                  "fruitcoctail_slot-201506031714"
                ],
                "html5": {
                  "app": [
                    "fruit-201605302030"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Fruit Cocktail"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/i_fc.png",
                "source": "i_fc",
                "type": "slot"
              },
              "ib_oz": {
                "html5": {
                  "app": [
                    "ib_oz-201711232118"
                  ], 
                  "mainjs": "game.js"
                }, 
                "lang": [
                  "en"
                ], 
                "load_only": "html5", 
                "loader": "ib_oz", 
                "name": [
                  {
                    "en": "Land of Ozz"
                  }
                ], 
                "position": 2, 
                "preview": "thumb/ib_oz.png", 
                "source": "ib_oz", 
                "type": "slot"
              },     
              "ib_fc_d": {
                "html5": {
                  "app": [
                    "i_fc_d-201707201614"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "ib_fc_d",
                "name": [
                  {
                    "en": "Fruit Cocktail Deluxe"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/i_fc_d.png",
                "source": "i_fc_d",
                "type": "slot"
              },    
              "i_i2": {
                "app": [
                  "island2_slot-201506031714"
                ],
                "html5": {
                  "app": [
                    "island2-201605302030"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Island 2"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/i_i2.png",
                "source": "i_i2",
                "type": "slot"
              },
              "i_k": {
                "app": [
                  "keks_slot-201509091955"
                ],
                "html5": {
                  "app": [
                    "keks-201605302030"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Keks"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/i_k.png",
                "source": "i_k",
                "type": "slot"
              },
              "i_lh": {
                "app": [
                  "luckyhaunter_slot-201506031715"
                ],
                "html5": {
                  "app": [
                    "haunter-201605302030"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Lucky Haunter"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/i_lh.png",
                "source": "i_lh",
                "type": "slot"
              },
              "ib_pc": {
                "html5": {
                  "app": [
                    "ib_pc-201707201614"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "ib_pc",
                "name": [
                  {
                    "en": "Pirate Cave"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/ib_pc.png",
                "source": "ib_pc",
                "type": "slot"
              },
              "i_r": {
                "app": [
                  "resident_slot-201506031715"
                ],
                "html5": {
                  "app": [
                    "resident-201605302030"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Resident"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/i_r.png",
                "source": "i_r",
                "type": "slot"
              },
              "i_rc": {
                "app": [
                  "rockclimber_slot-201506060025"
                ],
                "html5": {
                  "app": [
                    "climber-201605302030"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Rock Climber"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/i_rc.png",
                "source": "i_rc",
                "type": "slot"
              },
              "ib_ad": {
                "app": [
                  "ib_ad-201703151825"
                ],
                "html5": {
                  "app": [
                    "ib_ad-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "ib_ad",
                "name": [
                  {
                    "en": "Atlantis"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/ib_ad.png",
                "source": "ib_ad",
                "type": "slot"
              },
              "ib_ak": {
                "app": [
                  "ib_ak-201703151825"
                ],
                "html5": {
                  "app": [
                    "ib_ak-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "ib_ak",
                "name": [
                  {
                    "en": "Age of Knights"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/ib_ak.png",
                "source": "ib_ak",
                "type": "slot"
              },
              "ib_dg": {
                "app": [
                  "ib_dg-201703151825"
                ],
                "html5": {
                  "app": [
                    "ib_dg-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "ib_dg",
                "name": [
                  {
                    "en": "Dwarfs Gold"
                  }
                ],
                "position": 2,
                "lines": 5,
                "preview": "thumb/ib_dg.png",
                "source": "ib_dg",
                "type": "slot"
              },
              "ib_fh": {
                "app": [
                  "ib_fh-201703151825"
                ],
                "html5": {
                  "app": [
                    "ib_fh-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "ib_fh",
                "name": [
                  {
                    "en": "Fruit Heat"
                  }
                ],
                "position": 2,
                "lines": 5,
                "preview": "thumb/ib_fh.png",
                "source": "ib_fh",
                "type": "slot"
              },
              "ib_hc": {
                "app": [
                  "havanaclub_slot-201507132152"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Havana Club"
                  }
                ],
                "position": 1,
                "lines": 5,
                "preview": "thumb/ib_hc.png",
                "source": "ib_hc",
                "type": "slot"
              },
              "ib_ma": {
                "app": [
                  "ib_ma-201703151825"
                ],
                "html5": {
                  "app": [
                    "ib_ma-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "ib_ma",
                "name": [
                  {
                    "en": "Martians Attack"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/ib_ma.png",
                "source": "ib_ma",
                "type": "slot"
              },
              "ib_p": {
                "app": [
                  "ib_p-201703151825"
                ],
                "html5": {
                  "app": [
                    "ib_p-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "ib_p",
                "name": [
                  {
                    "en": "Pirates Bay"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/ib_p.png",
                "source": "ib_p",
                "type": "slot"
              },
              "ib_sa": {
                "app": [
                  "ib_sa-201703151825"
                ],
                "html5": {
                  "app": [
                    "ib_sa-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "ib_sa",
                "name": [
                  {
                    "en": "Secret Agent"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/ib_sa.png",
                "source": "ib_sa",
                "type": "slot"
              },
              "ib_z": {
                "app": [
                  "ib_z-201703151825"
                ],
                "html5": {
                  "app": [
                    "ib_z-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "ib_z",
                "name": [
                  {
                    "en": "Zombie Moon"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/ib_z.png",
                "source": "ib_z",
                "type": "slot"
              },
              "loader": {
                "app": [
                  "flash_slot_loader-201506031718"
                ],
                "source": "loader",
                "type": "utils"
              },
              "mp_81": {
                "app": [
                  "multi81_slot-201506031714"
                ],
                "lang": [
                  "en"
                ],
                "loader": "loader",
                "name": [
                  {
                    "en": "Multiplay 81"
                  }
                ],
                "position": 1,
                "lines": 81,
                "preview": "thumb/mp_81.png",
                "source": "m_81",
                "type": "slot"
              },
              "o_l16": {
                "app": [
                  "o_l16-201702281548"
                ],
                "html5": {
                  "app": [
                    "o_l16-201707051645"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "o_l16",
                "name": [
                  {
                    "en": "Gagarin-61"
                  }
                ],
                "position": 1,
                "lines": 9,
                "preview": "thumb/o_l16.png",
                "source": "o_l16",
                "type": "slot"
              },
              "o_ts": {
                "app": [
                  "top_secret-201611301657"
                ],
                "html5": {
                  "app": [
                    "top_secret-201611301657"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "loader": "o_ts",
                "name": [
                  {
                    "en": "Top Secret"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/o_ts.png",
                "source": "o_ts",
                "type": "slot"
              },
              "opfl_haxe_loader": {
                "app": [
                  "fortuna__custom_loader__temp"
                ],
                "source": "opfl_haxe_loader",
                "type": "utils"
              },
              "tg_wd": {
                "html5": {
                  "app": [
                    "tg_wd-201707201614"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "tg_wd",
                "name": [
                  {
                    "en": "Walking Death"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/tg_wd.png",
                "source": "tg_wd",
                "type": "slot"
              },
              "ib_al": {
                "html5": {
                  "app": [
                    "ib_al-201707201614"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "ib_al",
                "name": [
                  {
                    "en": "Aladdins Lamp"
                  }
                ],
                "position": 2,
                "lines": 9,
                "preview": "thumb/ib_al.png",
                "source": "ib_al",
                "type": "slot"
              },
              "tg_bv": {
                "html5": {
                  "app": [
                    "tg_bv-201707201614"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "tg_bv",
                "name": [
                  {
                    "en": "Bustin Vegas"
                  }
                ],
                "position": 2,
                "lines": 20,
                "preview": "thumb/tg_bv.png",
                "source": "tg_bv",
                "type": "slot"
              },
              "tg_vn": {
                "html5": {
                  "app": [
                    "tg_vn-201707201614"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "tg_vn",
                "name": [
                  {
                    "en": "Vegas Night"
                  }
                ],
                "position": 2,
                "lines": 20,
                "preview": "thumb/tg_vn.png",
                "source": "tg_vn",
                "type": "slot"
              },
              "tg_ht": {
                "html5": {
                  "app": [
                    "tg_ht-201707201614"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "tg_ht",
                "name": [
                  {
                    "en": "Hotter Than"
                  }
                ],
                "position": 2,
                "lines": 20,
                "preview": "thumb/tg_ht.png",
                "source": "tg_ht",
                "type": "slot"
              },    
              "tg_xm": {
                "html5": {
                  "app": [
                    "tg_xm-201707201614"
                  ],
                  "mainjs": "game.js"
                },
                "lang": [
                  "en"
                ],
                "load_only": "html5",
                "loader": "tg_xm",
                "name": [
                  {
                    "en": "Xmas Luck"
                  }
                ],
                "position": 2,
                "lines": 20,
                "preview": "thumb/tg_xm.png",
                "source": "tg_xm20",
                "type": "slot"
              }
            },
            "container": "http://flashslots.s3.amazonaws.com/",
            "container_name": "flashslots",
            "version": "14"
          }</script>
        <script src="https://api.doradobet.com/media/loader/build/app.js"></script>
        <script type="text/javascript">

            window.init_loader({
                game: "' . $URL->game . '",
                billing: "' . $URL->billing . '",
                token: "' . $URL->token . '",
                kf: 1,
                currency: "' . $URL->currency . '",
                language: "' . $URL->language . '",
                button: "classic"
            });
        </script>
</body>
</html>

        ');

        }


        if ($provider == "EZZG") {
            if ($isMobile == "true"  && $in_app != '1') {
                //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';
                echo '<script>/*var a = document.createElement(\'a\');
a.href="' . $URL . '";
a.target = \'_parent\';

a.click();*/
window.top.postMessage("redirectionurl_' . $URL . '", "'.$_SERVER['HTTP_REFERER'].'");

</script>
';
            }

        }

        if ($provider == "GDR") {

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                if ($isMobile == "true") {
                    echo '<div id="golden-race-mobile-app"></div>
<script src="https://test-virtual.golden-race.net/mobile-v2/golden-race-mobile-loader.js" id="golden-race-mobile-loader"></script>
<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        var grLoader = grMobileLoader({
            onlineHash:      "' . $URL->loginHash . '"// Credentials for external API login.
        });
     });
</script>';

                } else {


                    /*    echo '
                 <div id="golden-race-online-app"></div>
                  <div id="golden-race-app"></div>


          <script src="https://virtual.golden-race.net/web-v2/golden-race-online-loader.js" id="golden-race-online-loader"></script>
        <!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
         --><script>
        document.addEventListener(\'DOMContentLoaded\', function () {
            var loader = grOnlineLoader({
                onlineHash: "' . $URL->loginHash . '"
            });
        });


        </script>
        ';*/

if($URL->play_for_fun == true){
    echo '
               <div id="golden-race-desktop-app"></div>


       <script src="https://test-virtual.golden-race.net/desktop-v3/default/golden-race-desktop-loader.js" id="golden-race-desktop-loader"></script>
     <!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
      --><script>
     document.addEventListener(\'DOMContentLoaded\', function () {
         var loader = grDesktopLoader({
             hwId: "cf4386e2-9460-423d-aa34-98dda0b90149"
         });
     });


     </script>
     ';
}else{
    echo '
               <div id="golden-race-desktop-app"></div>


       <script src="https://test-virtual.golden-race.net/desktop-v3/default/golden-race-desktop-loader.js" id="golden-race-desktop-loader"></script>
     <!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
      --><script>
     document.addEventListener(\'DOMContentLoaded\', function () {
         var loader = grDesktopLoader({
             onlineHash: "' . $URL->loginHash . '"
         });
     });


     </script>
     ';
}


                }
            } else {
                if ($isMobile == "true") {

                    if($URL->play_for_fun == true){

                        echo '<div id="golden-race-mobile-app"></div>
<script src="https://virtual.golden-race.net/mobile-v2/golden-race-mobile-loader.js" id="golden-race-mobile-loader"></script>
<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        var grLoader = grMobileLoader({
             hwId: "cf4386e2-9460-423d-aa34-98dda0b90149"
        });
     });
</script>';
                    }else {


                        echo '<div id="golden-race-mobile-app"></div>
<script src="https://virtual.golden-race.net/mobile-v2/golden-race-mobile-loader.js" id="golden-race-mobile-loader"></script>
<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        var grLoader = grMobileLoader({
            onlineHash:      "' . $URL->loginHash . '"// Credentials for external API login.
        });
     });
</script>';
                    }

                } else {


                    /*    echo '
                 <div id="golden-race-online-app"></div>
                  <div id="golden-race-app"></div>


          <script src="https://virtual.golden-race.net/web-v2/golden-race-online-loader.js" id="golden-race-online-loader"></script>
        <!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
         --><script>
        document.addEventListener(\'DOMContentLoaded\', function () {
            var loader = grOnlineLoader({
                onlineHash: "' . $URL->loginHash . '"
            });
        });


        </script>
        ';*/

                    if($URL->play_for_fun == true) {
                        echo '
          <div id="golden-race-desktop-app"></div>
 
 
  <script src="https://virtual.golden-race.net/desktop-v3/default/golden-race-desktop-loader.js" id="golden-race-desktop-loader"></script>
<!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
 --><script>
document.addEventListener(\'DOMContentLoaded\', function () {
    var loader = grDesktopLoader({
        hwId: "cf4386e2-9460-423d-aa34-98dda0b90149"
    });
});


</script>
';
                    }else {


                        echo '
          <div id="golden-race-desktop-app"></div>
 
 
  <script src="https://virtual.golden-race.net/desktop-v3/default/golden-race-desktop-loader.js" id="golden-race-desktop-loader"></script>
<!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
 --><script>
document.addEventListener(\'DOMContentLoaded\', function () {
    var loader = grDesktopLoader({
        onlineHash: "' . $URL->loginHash . '",
        language:"es-ES"
    });
});


</script>
';
                    }
                }
            }

        } elseif ($provider == "ITN") {
          
            $skinid = $URL->skinItn;
            $srcItn = $URL->skinJsITN;
            $WalletCode =$URL->walletCode;

            $lang='es-ES';
            switch ($lan){
                case 'pt':
                    $lang='pt-BR';
                    break;
                case 'en':
                    $lang='en-US';
                    break;
            }

            if($srcItn == ''){
                throw new Exception("Juego no disponible ", "10000");
            }

            ?>
            <link rel="stylesheet" href="https://doradobet.com/assets/css/custom/custom2.css">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    text-decoration: initial;
                }

                menuvirtual .menuWrap ul li.active {
                    background: #d1b004;
                }

                menuvirtual .menuWrap ul li.active span {
                    color: white;
                }

                menuvirtual .menuWrap ul li {
                    cursor: pointer;
                    background: rgb(255, 255, 255);
                    display: inline-flex;
                }

                menuvirtual .menuWrap ul li span {
                    color: #79680c;
                }

                menuvirtual {
                    width: 100%;
                }

                svg {
                    fill: #d2b100;
                }

                menuvirtual .menuWrap ul li.active svg {
                    fill: white;
                    margin-right: 5px;
                }
            </style>

            <div id="virtual-wrapper" class="">
                <div id="BIA_client_container2"></div>

                <script type="text/javascript" src="<?= $srcItn ?>"></script>

                <script>
                    function showVirtual(Page) {
                        var options = {
                            token: '<?= $URL->token ?>',
                            skinid: '<?= $skinid ?>',
                            walletcode: '<?= $WalletCode ?>',
                            full: true,
                            page: Page,
                            lang: '<?=$lang?>',
                            isHashMode: false,
                            isHashClassesMode: false,
                            fixed: false<?= $isMobile == "true" ? ",\n    mobile: true" : "" ?>
                        };

                        try{
                            window.altenarWSDK.init({
                                integration: "<?= $skinid ?>",
                                culture: "es-ES",
                                token : '<?= $URL->token ?>'
                            });
                            (window).altenarWSDK.addSportsBook({
                                props: {
                                    page: 'virtual',
                                    virtualSportId: parseInt( "<?= $URL->virtualSportId ?>")
                                },
                                container:
                                    document.getElementById("BIA_client_container2")?.appendChild(document.createElement("div")),
                            });
                        }catch (e) {
                        }
                    }

                    function addClass(elements, className) {
                        for (var i = 0; i < elements.length; i++) {
                            var element = elements[i];
                            if (element.classList) {
                                element.classList.add(className);
                            } else {
                                element.className += ' ' + className;
                            }
                        }
                    }

                    function removeClass(elements, className) {
                        for (var i = 0; i < elements.length; i++) {
                            var element = elements[i];
                            if (element.classList) {
                                element.classList.remove(className);
                            } else {
                                element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
                            }
                        }
                    }

                    showVirtual('<?= ($URL->page != "" && $URL->page != "ITN") ? $URL->page : 'vfwc'  ?>');
                </script>
            </div>

            <style>
                @media (max-width: 600px) {
                    li.main-item.active span {
                        display: inline-block !important;
                    }

                    li.main-item span {
                        display: none !important;
                    }
                }
            </style>
            <?php

        }  elseif ($provider == "PLAYNGO") {

            if ($isMobile == "true"  && $in_app != '1') {
                //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                echo '<script>/*var a = document.createElement(\'a\');
                a.href="' . $URL->URL . '";
                a.target = \'_parent\';
                
                a.click();*/
                window.top.postMessage("redirectionurl_' . $URL->URL . '", "'.$_SERVER['HTTP_REFERER'].'");
                
                </script>
                ';
            }elseif ($isMobile == "true"  && $in_app == '1') {
                print('<iframe frameborder="0" src="' . explode("&lobby",$URL->URL)[0] . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');

            }else{


                if($miniGame == "true"){
                    // print_r($URL);

                    print('<iframe id="mini-playngo" height="320" width="100%" src="' . $URL->url . '" ></iframe>');
                    ?>
                    <script>

                        window.addEventListener("message", (event) => {
                            console.log(event );
                            if (event.origin !== "<?= $URL->url ?>" )
                                return;
                            var game = {
                                data: {
                                    messageType: 'startGame',
                                    data: {
                                        gameId: 154 //agregar cada id ?
                                    }
                                }
                            }

                        }, false);

                        function PostMessageCommunicator(targetWindow, targetOrigin,callback)
                        {
                            this.target = targetWindow;
                            this.targetOrigin = targetOrigin;
                            this.onMessage = (function (e) {

                                if (!targetOrigin.includes(e.origin)) {
                                    // reject message due to it arriving from an unsafe origin
                                    // return;
                                }
                                callback(e.data);
                            });
                            window.addEventListener("message", this.onMessage);
                        }
                        PostMessageCommunicator.prototype.postMessage = function (data)
                        {
                            this.target.postMessage(data, this.targetOrigin);
                        };
                        PostMessageCommunicator.prototype.removeEventListener = function(data)
                        {
                            window.removeEventListener("message", this.onMessage);
                        };
                        var lobbyframe = document.getElementById("mini-playngo");
                        lobbyframe.onload = function() {
                            var ticket = "<?= $URL->token ?>";
                            var ctx="MiniGames";
                            var brand = "doradobet";

                            var operatorLobbyCommunicator = new PostMessageCommunicator(
                                lobbyframe.contentWindow,
                                "<?= $URL->url ?>",
                                function(e) {
                                    console.log("Event received on operator page: ", e);
                                    switch (e.messageType) {
                                        case "startGame":
                                            //  alert("3");
                                            Object.assign(e.data, {

                                                ticket: ticket,
                                                ctx: ctx,
                                                brand: brand


                                            });
                                            operatorLobbyCommunicator.postMessage(e);
                                            break;
                                    }
                                });
                            operatorLobbyCommunicator.postMessage({
                                messageType: "addEventListener",
                                eventType: "roundStarted",
                                eventType:"freespinStarted"
                            });
                            operatorLobbyCommunicator.postMessage({
                                messageType: "addEventListener",
                                eventType: "roundEnded",
                                eventType:"freespinEnded"
                            });
                        }
                    </script>
                    <?php


                }else{
                    print('<iframe  allowfullscreen="true" frameborder="0" src="' . $URL->URL . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');
                }
            }

        }elseif ($provider == "MOBADOO") {

            if ($isMobile == "true"  && $in_app != '1' && false) {
                //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                echo '<script>/*var a = document.createElement(\'a\');
                a.href="' . $URL->URL . '";
                a.target = \'_parent\';
                
                a.click();*/
                window.top.postMessage("redirectionurl_' . $URL->URL . '", "'.$_SERVER['HTTP_REFERER'].'");

                </script>';

            }elseif ($isMobile == "true"  && $in_app == '1'  && false) {
                print('<iframe frameborder="0" src="' . explode("&lobby",$URL->URL)[0] . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');

            }else{
                //print('<div ng-if="isDiv" id="pngCasinoGame" style="width: 100%; height: auto;"></div>');
                print('<script src="' . $URL->script . '"></script>');
                //print('<iframe frameborder="0" src="' . $URL->URL . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');
                print('
                       <script type="text/javascript">
                            window.onload = function () {
                                const iframe = new MobaFrame({
                                   client_uid: "' . $URL->client_uid . '",
                                   element: ".iframe-container",
                                   ' . $URL->sandbox . '
                                   profile: "' . $URL->profile . '",
                                   landing: "'.$URL->landing.'",
                                   lang: "'.$URL->lang.'",
                                   hash: "' . $URL->hash . '"
                                });
                            }
                        </script> 
                    ');
                print('<div class="iframe-container" style="width: 100%;height: 100%;"></div>');
            }


          }elseif ($provider == "TVBET") {

            if ($isMobile == "true"  && $in_app != '1' && false) {
                //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                echo '<script>/*var a = document.createElement(\'a\');
                        a.href="' . $URL->URL . '";
                        a.target = \'_parent\';
                        
                        a.click();*/
                        window.top.postMessage("redirectionurl_' . $URL->url . '", "'.$_SERVER['HTTP_REFERER'].'");
                        
                        </script>
                        ';
            }
            else{

                if ($URL->containerId == 'jacktop-game'){
                print('<div id="jacktop-game" style="width: 100%; height: auto;"></div>');
                print('<script src="' . $URL->url . '" type="text/javascript">function reloadgame(gameId, user) { window.location.reload(false);  }</script>');
                print('<script>
                (function () {
                    new JackTopFrame({
                      lng  : "'.$URL->lng.'",
                      clientId   :  "'. $URL->clientId .'",
                      tokenAuth  :  "'.$URL->tokenAuth .'",
                      server    : "'.$URL->server .'",
                      floatTop :  "'.$URL->floatTop .'",
                      containerId : "'.$URL->containerId.'",
                      game_id : '.$URL->game_id.'
                    }).mount();
                  })();
                </script> ');
                }else{
                    print('<div id="tvbet-iframe" style="width: 100%; height: auto;"></div>');
                    print('<script src="' . $URL->url . '" type="text/javascript">function reloadgame(gameId, user) { window.location.reload(false);  }</script>');
                    print('<script>
                    (function () {
                        new TvbetFrame({
                          lng  : "'.$URL->lng.'",
                        clientId   :  "'. $URL->clientId .'",
                          tokenAuth  :  "'.$URL->tokenAuth .'",
                          server    : "'.$URL->server .'",
                          containerId : "'.$URL->containerId.'",
                          game_id : '.$URL->game_id.'
                        });
                      })();
                    </script> ');
                }
            }
        }
        elseif ($provider == "PGSOFT") {
          if ($isMobile == "true"  && $in_app != '1') {
              echo '<script>/*var a = document.createElement(\'a\');
                        a.href="' . $URL->URL . '";
                        a.target = \'_parent\';
                        
                        a.click();*/
                        window.top.postMessage("redirectionurl_' . $URL->url . '", "'.$_SERVER['HTTP_REFERER'].'");
                        
                        </script>
                        ';
          }
            print($URL);


      }
        elseif ($provider == "SOFTSWISS") {
            $URL = str_replace('\/', '/', $URL);
            if ($isMobile == "true"  && $in_app != '1') {
                echo '<script>var a = document.createElement(\'a\');
                a.href="' . $URL . '";
                a.target = \'_top\';
                a.click();
                </script>
                ';
            }else {
//            print('<div id="game_wrapper"></div>');
//            print('<script src="https://casino.int.a8r.games/public/sg.js"></script>');
                print('<script src="https://casino.cur.a8r.games/public/sg.js"></script>');
//            print('
//                <script type="text/javascript">
//                    gameLaunchOptions = {target_element: "game_wrapper"};
//                    gameLaunchOptions["launch_options"] = '.$URL.';
//                    success = function (gameDispatcher) { ... }
//                    error = function (error) { ... }
//                    window.sg.launch(gameLaunchOptions, success, error);
//                </script>
//            ');
                print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');
            }
        }

        elseif ($provider == "HABANERO") {

            if ($isMobile == "true"  && $in_app != '1') {
                //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                echo '<script>/*var a = document.createElement(\'a\');
                            a.href="' . $URL->URL . '";
                            a.target = \'_parent\';
                            
                            a.click();*/
                            window.top.postMessage("redirectionurl_' . $URL . '", "'.$_SERVER['HTTP_REFERER'].'");
                            
                       </script>
                       ';
            }
            else{
                print('<div ng-if="isDiv" id="pngCasinoGame" style="width: 100%; height: auto;"></div>');

                print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');

                print('<script></script>');
            }

        } elseif ($provider == "EZZG") {

            print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');


        } elseif ($provider == "BETGAMESTV") {

          echo '<div id="' . $URL->partnerParam . '"></div>';
          echo '<script type="text/javascript">
          var clientUrl = "' . $URL->jsURL . '";
          var script = document.createElement("script");

          script.onload = function () {
              window.BetGames.setup({
                  containerId: "' . $URL->partnerParam . '",
                  clientUrl: "' . $URL->jsURL . '",
                  apiUrl: "' . $URL->serverParam . '",
                  partnerCode: "' . $URL->partnerParam . '",
                  token: "' . $URL->token . '"
              });
          }

          script.type = "text/javascript";
          script.src = "' . $URL->jsURL . '/public/betgames.js?" + Date.now();

          document.head.appendChild(script);
      </script>';

        }elseif ($provider == "XPRESS") {
            if ($isMobile == "true" && $in_app != '1') {
                echo '<script>/*var a = document.createElement(\'a\');
                a.href="' . $URL->url . '";
                a.target = \'_parent\';

                a.click();*/
                window.top.postMessage("redirectionurl_' . $URL->url . '", "'.$_SERVER['HTTP_REFERER'].'");

                </script>
                ';
            }else {
                print('

                  <script>
                  var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
                  var eventer = window[eventMethod];
                  var messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";
                  eventer(messageEvent, function (e) {

                      switch (e.data.action) {
                          case "game.loaded":
                              // Game successfully loaded.
                              break;
                          case "game.balance.changed":
                              // Game Balance changed.
                              break;
                          case "game.cycle.started":
                              // Ticket placing...
                              break;
                          case "game.cycle.end":
                              // Ticket placed
                              break;
                          case "game.goto.home":
                              //Game has to be redirected to the home lobby page.(exit)
                              break;
                          case "game.goto.history":
                              // History modal opens
                              break;
                          case "game.resize.height":
                              // iframe height should be: e.data.value;
                              document.getElementById("goldenRace").style.height = e.data.value;
                              break;
                          case "game.get.clientrect":
                              // iframe selector.
                              e.source.postMessage({action: "game.clientrect", value: document.getElementById("goldenRace").getBoundingClientRect()}, \'*\');
                              break;
                          case "game.get.clientheight":
                              // iframe selector.
                              e.source.postMessage({action: "game.clientheight", value: document.getElementById("goldenRace").offsetHeight}, \'*\');
                              break;
                          case "game.get.innerheight":
                              // general window selector.
                              e.source.postMessage({action: "game.innerheight", value: window.innerHeight}, \'*\');
                              break;
                      }
                  });

                  function sendMessageToIframe(outerIframe, dni, userID) {
                      var objMessage = {
                          "title": dni,
                          "UsuarioID": userID
                      };

                      outerIframe.contentWindow.postMessage({
                          args: [objMessage],
                          method: "setContextObject",
                          origin: "client-integration"
                      }, \'*\');

                      outerIframe.contentWindow.postMessage({
                        args:[true],
                        method:"closeTicketIframe",
                        origin:"client-integration"
                      },\'*\');
                  }

                ');

                if ($URL->isPv) {
                    print('

                  const dni = "' . $URL->dni . '";
                  const userID = "' . $URL->userID . '";

                  const intervalId = setInterval(() => {
                    const outerIframe = document.querySelector("#goldenRace");
                      if (outerIframe) {

                        sendMessageToIframe(outerIframe, dni, userID);
                        clearInterval(intervalId);

                      }
                    }, 18000);

                  window.addEventListener("message", (event) => {
                      if (event.data.action == "game.ticket.made") {

                          console.log(event.data);
                          const outerIframe = document.querySelector("#goldenRace");
                          sendMessageToIframe(outerIframe, dni, userID);

                      }
                  },
                      false,
                  );
                  ');
                }

                print('</script><div id="container"><iframe id="goldenRace" frameborder="0" src="' . $URL->url . '" class="embed-responsive-item" style="width: 100%;height: 100%;" scrolling="yes"></iframe></div>');
            }
        } elseif ($provider == "TOMHORN") {

            print('
                     
                       <div id="gameClientPlaceholder">
            <h2>Game Client starting procedure failed!</h2>
        </div>
        <script src="'.$URL->{'param:base'}.'ClientUtils.js" type="text/javascript"></script>
        <script src="swfobject.js" type="text/javascript"></script>
        <script type="text/javascript">
            var params = '.json_encode(($URL)).';

            renderClient(params, \'gameClientPlaceholder\');
        </script>
                     
                     ');

        }elseif ($provider == "CTGAMING") {
            if ($_ENV['debug']){
                print_r('entroooo');
            }
            if ($isMobile == "true"  && $in_app != '1') {
                //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';
                echo '<script>/*var a = document.createElement(\'a\');
                        a.href="' . $URL->url . '";
                        a.target = \'_parent\';
                        
                        a.click();*/
                        window.top.postMessage("redirectionurl_' . $URL->url . '", "'.$_SERVER['HTTP_REFERER'].'");
                        
                        </script>
                        ';
            }else{

                /*print('<script
                        type            = "text/javascript"
                        class           = "ctscript ct-jp-display"
                        src             = "https://cdn3.ctrgs.com/jp-display-stable/jsc/jackpot-multidisplay.min.js"
                        display-ids     = "https://rgs1.ctrgs.com/jpdisplay-cache-pub/local_jpdisp_158.json"
                        display-style   = "ct-default-display-desktop-2020"
                        display-lang    = "es"
                        display-tooltip = "bottom"
                    >

                </script>');*/
                print('<iframe frameborder="0" src="' . $URL->url . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');
            }

        }

        elseif ($provider == "HABANERO") {

            if ($isMobile == "true" && $in_app != '1') {
                //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                echo '<script>/*var a = document.createElement(\'a\');
                        a.href="' . $URL->URL . '";
                        a.target = \'_parent\';
                        
                        a.click();*/
                        window.top.postMessage("redirectionurl_' . $URL . '", "' . $_SERVER['HTTP_REFERER'] . '");
                        
                        </script>
                        ';
            } else {
                print('<div ng-if="isDiv" id="pngCasinoGame" style="width: 100%; height: auto;"></div>');

                print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');

                print('<script></script>');
            }

        }elseif ($provider == "PLAYTECH") {
            if ($isMobile == "true" && $in_app != '1') {
                //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                echo '<script>/*var a = document.createElement(\'a\');
                            a.href="' . $URL . '";
                            a.target = \'_parent\';
                            
                            a.click();*/
                            window.top.postMessage("redirectionurl_' . $URL . '", "'.$_SERVER['HTTP_REFERER'].'");
                            
                        </script>
                        ';

            }else{

                echo '<script>/*var a = document.createElement(\'a\');
                        a.href="' . $URL . '";
                        a.target = \'_parent\';
                        
                        a.click();*/
                        window.top.postMessage("redirectionurlblank_' . $URL . '", "'.$_SERVER['HTTP_REFERER'].'");
                        
                    </script>
                    ';
                ?>

                <?php

                print('<iframe id="gameframe"  allowfullscreen="true" frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');

            }

        }elseif ($provider == "PANILOTTERY") {
          
            print('<iframe id="gameframe"  allowfullscreen="true" frameborder="0" src="' . $URL->URL . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');
          

        } elseif ($provider == "RFRANCO") {
          header("Permissions-Policy: sync-xhr=(self)");

          if ($isMobile == "true" && $in_app != '1') {
                echo '<script>/*var a = document.createElement(\'a\');
                            a.href="' . $URL . '";
                            a.target = \'_parent\';
                            
                            a.click();*/
                            window.top.postMessage("redirectionurl_' . $URL . '", "'.$_SERVER['HTTP_REFERER'].'");
                            
                        </script>
                        ';
            }else {
                print('<iframe  allowfullscreen="true" frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 100%;" allow="sync-xhr"></iframe>');
            }

        } else {
            if ($isMobile == "true" && $in_app != '1') {
                echo '<script>/*var a = document.createElement(\'a\');
                            a.href="' . $URL . '";
                            a.target = \'_parent\';
                            
                            a.click();*/
                            window.top.postMessage("redirectionurl_' . $URL . '", "'.$_SERVER['HTTP_REFERER'].'");
                            
                        </script>
                        ';
            }else {
                print('<iframe  allowfullscreen="true" frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');
            }
        }


    }
    /*
    if($user_token=="0167wtapy61pnedcdcmvf34uwpq4x7"){
        echo "
      <script>
        var hasFlash = false;
        try {
          hasFlash = Boolean(new ActiveXObject('ShockwaveFlash.ShockwaveFlash'));
        } catch (exception) {
          hasFlash = ('undefined' != typeof navigator.mimeTypes['application/x-shockwave-flash']);
        }

        if (hasFlash) {
          alert('SI tiene');
        } else {
          alert('NO tiene');

        }
      </script>";

    }

    */


} catch (Exception $e) {
    if($_ENV['debug']){
        print_r($e);
    }
    $string='Juego No disponible';
    if($lan == 'pt'){
        $string='Jogo não disponível';
}
    print('<div style="width: 100%;height: 97%;background: black;background: url(&quot;'.$bgCasino.'&quot;) 50% 0px no-repeat;font: 15px/20px Quicksand,Arial,Helvetica,sans-serif;"><div style="
    color: white;
    text-align: center;
    padding-top: 30%;
    /* display: inline-block; */
    font-size: 35px;
    text-transform: uppercase;
"> 
'.$string.'
</div>
</div>');

}



