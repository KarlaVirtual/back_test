<?php

/**
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @access public
 * @since 07/01/2025
 *
 */

use Elastic\Elasticsearch\ClientBuilder;
use GuzzleHttp\Client as GuzzleClient;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$params = $json->params;

$partner_id = !empty($params->partner_id) ? $params->partner_id : null;
$search = !empty($params->search) ? $params->search : null;
$country = !empty($params->country) ? $params->country : null;
$action = !empty($params->action) ? $params->action : null;

if (false) {

    $apikey = $_ENV['ELASTICSEARCH_API_KEY'];
    $cloudId = $_ENV['ELASTICSEARCH_CLOUD_ID'];
    try {

        if (!$apikey || !$cloudId) {
            http_response_code(500);
            new Exception('No se ha configurado la conexión con elasticsearch', 500);
        }

        $params = [
            'index' => 'sports_teams',
            'body'  => [
                'query' => [
                    'wildcard' => [
                        'LEAGUE_NAME' => '*' . $search . '*'
                    ]
                ]
            ]
        ];

        $client = ClientBuilder::create()
            ->setHttpClient(new GuzzleClient())
            ->setElasticCloudId($cloudId)
            ->setApiKey($apikey)
            ->build();

        $results = $client->search($params);
        $results = $results['hits']['hits'];
    } catch (\Throwable $th) {

        $return_array["status"] = "false";
        $return_array["message"] = $th->getMessage();
        print_r(json_encode($return_array));
        return $return_array;
    }
} else {
    $response = [
        "status" => "ok",
        "cache" => 17879,
        "total_request" => 0,
        "total_count" => 0,
        "c" => 199,
        "is_cache" => [false],
        "sportbook" => [
            "events" => [
                [
                    "id" => 1,
                    "league" => "La Liga",
                    "live" => true,
                    "time_live" => "65",
                    "date" => "12/12 - 12:30 pm",
                    "icon_league" => "https://images.virtualsoft.tech/m/msj0212T1736886583.png",
                    "sport" => "Futbol",
                    "country" => "España",
                    "icon_country" => "https://images.virtualsoft.tech/m/msj0212T1736886452.png",
                    "icon_sport" => "https://images.virtualsoft.tech/m/msj0212T1736886376.png",
                    "link_event" => "deportes/partido/10625472",
                    "background" => "https://images.virtualsoft.tech/m/msj0212T1736886188.png",
                    "team_name_1" => "Almería",
                    "team_icon_1" => "https://images.virtualsoft.tech/m/msj0212T1736886520.png",
                    "team_name_2" => "Mirandés",
                    "team_icon_2" => "https://images.virtualsoft.tech/m/msj0212T1736886656.png"
                ],
                [
                    "id" => 1,
                    "league" => "La Liga",
                    "live" => false,
                    "time_live" => "65",
                    "date" => "12/12 - 12:30 pm",
                    "icon_league" => "https://images.virtualsoft.tech/m/msj0212T1736886583.png",
                    "sport" => "Futbol",
                    "country" => "España",
                    "icon_country" => "https://images.virtualsoft.tech/m/msj0212T1736886452.png",
                    "icon_sport" => "https://images.virtualsoft.tech/m/msj0212T1736886376.png",
                    "link_event" => "deportes/partido/10625472",
                    "background" => "https://images.virtualsoft.tech/m/msj0212T1736886188.png",
                    "team_name_1" => "Almería",
                    "team_icon_1" => "https://images.virtualsoft.tech/m/msj0212T1736886520.png",
                    "team_name_2" => "Mirandés",
                    "team_icon_2" => "https://images.virtualsoft.tech/m/msj0212T1736886656.png"
                ]
            ],
            "categories" => [
                [
                    "name" => "La Liga",
                    "icon_name" => "https://images.virtualsoft.tech/m/msj0212T1736886583.png",
                    "sport" => "Futbol",
                    "country" => "España",
                    "icon_sport" => "https://images.virtualsoft.tech/m/msj0212T1736886376.png",
                    "icon_country" => "https://images.virtualsoft.tech/m/msj0212T1736886452.png",
                    "link_category" => "deportes/liga/2941",
                    "backgroud" => "https://images.virtualsoft.tech/m/msj0212T1736886283.png"
                ],
                [
                    "name" => "Bundesliga",
                    "icon_name" => "https://images.virtualsoft.tech/m/msj0212T1736886640.png",
                    "sport" => "Futbol",
                    "country" => "Alemania",
                    "icon_sport" => "https://images.virtualsoft.tech/m/msj0212T1736886376.png",
                    "icon_country" => "https://images.virtualsoft.tech/m/msj0212T1736886348.png",
                    "link_category" => "deportes/liga/2950",
                    "backgroud" => "https://images.virtualsoft.tech/m/msj0212T1736886283.png"
                ]
            ]
        ],
        "casino" => [
            "games" => [
                [
                    "id" => 467406,
                    "name" => "Aviatrix",
                    "producto_id" => 26309,
                    "provider" => "AVIATRIX",
                    "show_as_provider" => "AVIATRIX",
                    "server_game_id" => 467406,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 467406,
                    "front_game_id" => "nft-aviatrix",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/Aviatrix-1733509184.gift",
                    "icon_3" => "https://images.virtualsoft.tech/productos/Aviatrix-1733487390.gift",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => 478607,
                    "name" => "Aviator",
                    "producto_id" => 13259,
                    "provider" => "SPRIBE",
                    "show_as_provider" => "SPRIBE",
                    "server_game_id" => 478607,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 478607,
                    "front_game_id" => "aviator",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/AviatorT1731536817.gif",
                    "icon_3" => "https://images.virtualsoft.tech/productos/AviatorT1731536822.gif",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => 458092,
                    "name" => "Navigator",
                    "producto_id" => 26100,
                    "provider" => "SOLIDICON",
                    "show_as_provider" => "SOLIDICON",
                    "server_game_id" => 458092,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 458092,
                    "front_game_id" => "SIC_101",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/NavigatorT1715979119.png",
                    "icon_3" => "https://images.virtualsoft.tech/productos/NavigatorT1715979126.png",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => 453681,
                    "name" => "Gravity Roulette",
                    "producto_id" => 18718,
                    "provider" => "BETERLIVE",
                    "show_as_provider" => "Iconic 21",
                    "server_game_id" => 453681,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 453681,
                    "front_game_id" => "launch_mrol_gravity",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/Gravity-RouletteT1732034196.png",
                    "icon_3" => "https://images.virtualsoft.tech/productos/Gravity-RouletteT1732034201.png",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => 453680,
                    "name" => "Gravity Blackjack",
                    "producto_id" => 17051,
                    "provider" => "BETERLIVE",
                    "show_as_provider" => "Iconic 21",
                    "server_game_id" => 453680,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 453680,
                    "front_game_id" => "launch_main_ssbj_01",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/Gravity-BlackjackT1732034153.png",
                    "icon_3" => "https://images.virtualsoft.tech/productos/Gravity-BlackjackT1732034171.png",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => 453682,
                    "name" => "Gravity Sic Bo",
                    "producto_id" => 22675,
                    "provider" => "BETERLIVE",
                    "show_as_provider" => "Iconic 21",
                    "server_game_id" => 453682,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 453682,
                    "front_game_id" => "launch_gravity_sb",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/Gravity-Sic-BoT1732034231.png",
                    "icon_3" => "https://images.virtualsoft.tech/productos/Gravity-Sic-BoT1732034237.png",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => 442144,
                    "name" => "Gravity Bonanza",
                    "producto_id" => 24214,
                    "provider" => "PRAGMATIC",
                    "show_as_provider" => "Pragmatic",
                    "server_game_id" => 442144,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 442144,
                    "front_game_id" => "vs20gravity",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/Gravity-BonanzaT1696887093.png",
                    "icon_3" => "https://images.virtualsoft.tech/productos/Gravity-BonanzaT1696887101.png",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => 472382,
                    "name" => "Aviamasters",
                    "producto_id" => 26599,
                    "provider" => "BGAMING",
                    "show_as_provider" => "BGAMING",
                    "server_game_id" => 472382,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 472382,
                    "front_game_id" => "BGM_Aviamasters",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/AviamastersT1721053492.png",
                    "icon_3" => "https://images.virtualsoft.tech/productos/AviamastersT1721053506.png",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => 478627,
                    "name" => "Lavish Joker",
                    "producto_id" => 27308,
                    "provider" => "BELATRA",
                    "show_as_provider" => "BELATRA",
                    "server_game_id" => 478627,
                    "status" => "published",
                    "background" => "",
                    "categories" => [],
                    "cats" => [],
                    "extearnal_game_id" => 478627,
                    "front_game_id" => "joker",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://images.virtualsoft.tech/productos/Lavish-JokerT1729024144.png",
                    "icon_3" => "https://images.virtualsoft.tech/productos/Lavish-JokerT1729024152.png",
                    "ratio" => "16:9",
                    "rows" => 1,
                    "columns" => 1,
                    "grid_column" => 1,
                    "grid_row" => 1,
                    "TagImage" => null,
                    "TagText" => null,
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => "311",
                    "name" => "Goldenrace Spin2Win",
                    "producto_id" => "4605",
                    "provider" => "XPRESS",
                    "show_as_provider" => "XPRESS",
                    "server_game_id" => "311",
                    "status" => "published",
                    "background" => "https://images.virtualsoft.tech/site/doradobet/fondo-casino2.png?v=1",
                    "categories" => [null],
                    "cats" => [
                        "id" => null,
                        "title" => null
                    ],
                    "extearnal_game_id" => "311",
                    "front_game_id" => "10159",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://goldenrace.com/images/home/bg_webinar.jpg",
                    "icon_3" => "https://images.virtualsoft.tech/productos/Spin2WinT1684262276.png",
                    "ratio" => "16:9",
                    "rows" => "1",
                    "columns" => "1",
                    "grid_column" => "1",
                    "grid_row" => "1",
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => "1488",
                    "name" => "Spin Island",
                    "producto_id" => "5192",
                    "provider" => "VIBRAGAMING",
                    "show_as_provider" => "VIBRAGAMING",
                    "server_game_id" => "1488",
                    "status" => "published",
                    "background" => "https://images.virtualsoft.tech/site/doradobet/fondo-casino2.png?v=1",
                    "categories" => [null],
                    "cats" => [
                        "id" => null,
                        "title" => null
                    ],
                    "extearnal_game_id" => "1488",
                    "front_game_id" => "SPINISLAND2P4",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "https://vibragaming.com/wp-content/uploads/2020/07/SpinIsland_CARD-400x400.jpg",
                    "icon_3" => "",
                    "ratio" => "16:9",
                    "rows" => "1",
                    "columns" => "1",
                    "grid_column" => "1",
                    "grid_row" => "1",
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ],
                [
                    "id" => "5482",
                    "name" => "Let it Spin",
                    "producto_id" => "6006",
                    "provider" => "BOOMING",
                    "show_as_provider" => "BOOMING",
                    "server_game_id" => "5482",
                    "status" => "published",
                    "background" => "https://images.virtualsoft.tech/site/doradobet/fondo-casino2.png?v=1",
                    "categories" => [null],
                    "cats" => [
                        "id" => null,
                        "title" => null
                    ],
                    "extearnal_game_id" => "5482",
                    "front_game_id" => "62bde4e756dc080014ae001e",
                    "game_options" => "",
                    "game_skin_id" => "",
                    "icon_2" => "",
                    "icon_3" => "",
                    "ratio" => "16:9",
                    "rows" => "1",
                    "columns" => "1",
                    "grid_column" => "1",
                    "grid_row" => "1",
                    "types" => [
                        "realMode" => 1,
                        "funMode" => 0
                    ],
                    "isBorderNeon" => 1,
                    "classBorderNeon" => "neon1"
                ]
            ]
        ]
    ];
}

$response["total_count"] = count($response['sportbook']['events']) + count($response['sportbook']['categories']) + count($response['casino']['games']);

return $response;
