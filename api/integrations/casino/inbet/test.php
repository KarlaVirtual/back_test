<?php

/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Mandante            Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $Country             Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $AffiliateId         Variable que almacena el identificador de un afiliado.
 * @var mixed $BonoInternoMySqlDAO Objeto que maneja operaciones de base de datos para bonos internos en MySQL.
 * @var mixed $Transaction         Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $sql                 Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $BonoInterno         Variable que representa un bono interno en el sistema.
 * @var mixed $Resultado           Variable que almacena el resultado de una operación o consulta.
 * @var mixed $array               Variable que almacena una lista o conjunto de datos.
 * @var mixed $index               Variable que representa un índice en una estructura de datos.
 * @var mixed $value               Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $item                Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $response            Esta variable almacena la respuesta generada por una operación o petición.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\INBETSERVICES;

$INBET = new INBETSERVICES();

$str = '{
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
          "en": "Phoenix\'s Fruits"
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
          "bingo37-201709112031"
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
      "position": 1,
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
          "bingo37b-201707271642"
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
      "position": 1,
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
      "position": 1,
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
      "position": 1,
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
          "bet_fortuna-201707201722"
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
      "position": 1,
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
      "position": 1,
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
      "position": 1,
      "preview": "thumb/tron3d.png",
      "source": 77802,
      "type": "betting"
    },
    "bets_poker3x": {
      "html5": {
        "app": [
          "bets_poker3x-201709281813"
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
      "position": 1,
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
          "g_bor_d-201707281516"
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
          "en": "Dolphin\'s Pearl"
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
          "en": "Dolphin\'s Pearl DeLuxe"
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
          "en": "Emperor\'s China"
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
          "en": "Gryphon\'s Gold"
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
          "en": "Lucky Lady\'s Charm"
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
          "en": "Lucky Lady\'s Charm DeLuxe"
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
          "en": "Oliver\'s Bar"
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
          "en": "Pharaoh\'s Gold II"
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
          "en": "Pharaon\'s Gold III"
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
      "source": "g_xh",
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
          "en": "Dwarf\'s Gold"
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
          "en": "Aladdin\'s Lamp"
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
          "en": "Bustin\' Vegas"
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
}';
exit();
$INBET->setGameList(json_decode($str));