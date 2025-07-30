<?php

/**
 * Este archivo contiene un script para procesar y registrar información de juegos
 * provenientes de un proveedor externo, generando productos y categorías en la base de datos.
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
 * @var string $data              Cadena JSON que contiene la información de los juegos proporcionados por el proveedor.
 * @var object $Proveedor         Objeto que representa al proveedor de los juegos, inicializado con un identificador y nombre.
 * @var object $ProductoMySqlDAO  Objeto que maneja las operaciones de base de datos relacionadas con los productos.
 * @var object $Producto          Objeto que representa un producto (juego) a ser registrado en la base de datos.
 * @var object $ProductoMandante  Objeto que representa la relación entre un producto y un mandante.
 * @var object $CategoriaProducto Objeto que representa la relación entre un producto y una categoría.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\dto\CategoriaProducto;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\dto\Proveedor;

$data = '{
    "status": "OK",
    "data": {
        "games": [
            {
                "game": {
                    "gameId": "doradobet-bj_vip",
                    "gameName": {
                        "en": "BLACKJACK VIP",
                        "es": "BLACKJACK VIP"
                    },
                    "gameType": "bj",
                    "description": {
                        "en": "Higher bets for greater profits in this exciting game mode!",
                        "es": "¡Apuestas más elevadas para obtener mayores ganancias en esta emocionante modo de juego!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/bj_vip/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-bj_fast",
                    "gameName": {
                        "en": "BLACKJACK FAST",
                        "es": "BLACKJACK FAST"
                    },
                    "gameType": "bj",
                    "description": {
                        "en": "The fastest way to beat the croupier!",
                        "es": "¡La manera más rápida de batir al croupier!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/bj_fast/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-bj_switch",
                    "gameName": {
                        "en": "BLACKJACK SWITCH",
                        "es": "BLACKJACK SWITCH"
                    },
                    "gameType": "bj",
                    "description": {
                        "en": "Exchange the second cards of your hands to increase your chances of winning!",
                        "es": "¡Intercambia las segundas cartas de tus manos para incrementar tus posibilidades de ganar!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/bj_switch/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-bj_doubleexposure",
                    "gameName": {
                        "en": "BLACKJACK DOUBLE EXPOSURE",
                        "es": "BLACKJACK DOUBLE EXPOSURE"
                    },
                    "gameType": "bj",
                    "description": {
                        "en": "Take advantage of knowing the Croupier\'\'\'\'s cards!",
                        "es": "¡Parte con la ventaja de conocer las cartas del Croupier!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/bj_doubleexposure/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-bj_european",
                    "gameName": {
                        "en": "BLACKJACK EUROPEAN",
                        "es": "BLACKJACK EUROPEAN"
                    },
                    "gameType": "bj",
                    "description": {
                        "en": "The european way to enjoy Blackjack!",
                        "es": "¡La forma europea de disfrutar del Blackjack!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/bj_european/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-bj_atlanticcity",
                    "gameName": {
                        "en": "BLACKJACK ATLANTIC CITY",
                        "es": "BLACKJACK ATLANTIC CITY"
                    },
                    "gameType": "bj",
                    "description": {
                        "en": "Get better winnings with a Blackjack",
                        "es": "¡Consigue más premio con un Blackjack!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/bj_atlanticcity/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-bj_vegasstrip",
                    "gameName": {
                        "en": "BLACKJACK VEGAS STRIP",
                        "es": "BLACKJACK VEGAS STRIP"
                    },
                    "gameType": "bj",
                    "description": {
                        "en": "The easiest way to play Blackjack!",
                        "es": "¡El modo más fácil de jugar al Blackjack!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/bj_vegasstrip/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-loki_parck",
                    "gameName": {
                        "en": "Frenzy Discs - Twin Numbers"
                    },
                    "gameType": "loki",
                    "description": {
                        "en": "Novedoso juego de azar, cuyo objetivo consiste en acertar en qué números caerán las 2 bolas lanzadas en dos discos giratorios, consiguiendo el premio máximo en caso de salir el mismo número en los 2 discos.",
                        "es": "Novedoso juego de azar, cuyo objetivo consiste en acertar en qué números caerán las 2 bolas lanzadas en dos discos giratorios, consiguiendo el premio máximo en caso de salir el mismo número en los 2 discos."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/loki_parck/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-rlt_vip",
                    "gameName": {
                        "en": "Roulette VIP",
                        "es": "Ruleta VIP"
                    },
                    "gameType": "rlt",
                    "description": {
                        "en": "The VIP version is played on a roulette wheel with 37 numbered pockets. In this game mode you can place higher bets than in any other version.",
                        "es": "En la modalidad VIP, se juega con una ruleta de 37 casillas numeradas. En este modo de juego podrás realizar apuestas más elevadas que en ninguna otra modalidad."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/rlt_vip/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/rlt_vip/logo_es.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-rlt_french",
                    "gameName": {
                        "en": "French Roulette",
                        "es": "Ruleta Francesa"
                    },
                    "gameType": "rlt",
                    "description": {
                        "en": "Most played. 37 numbered pockets from 1 to 36 (red and black) and 0 (green).",
                        "es": "La más jugada. 37 casillas numeradas, del 1 al 36 (rojo o negro) y el 0 (verde)."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/rlt_french/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/rlt_french/logo_es.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-rlt_european",
                    "gameName": {
                        "en": "European Roulette",
                        "es": "Ruleta Europea"
                    },
                    "gameType": "rlt",
                    "description": {
                        "en": "The European version is played using a roulette wheel which has 37 numbered pockets from 1 to 36 (red and black) and 0 (green).",
                        "es": "En la modalidad europea, se juega usando una rueda de ruleta que tiene 37 casillas numeradas: del 1 al 36 (rojo o negro) y el 0 (verde)."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/rlt_european/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/rlt_european/logo_es.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-rlt_american",
                    "gameName": {
                        "en": "American Roulette",
                        "es": "Ruleta Americana"
                    },
                    "gameType": "rlt",
                    "description": {
                        "en": "The American version is played using a roulette wheel which has 38 pockets. The numbers are from 1 to 36 (red and black) and 0 and 00 (green).",
                        "es": "En la modalidad americana, se juega usando una rueda de ruleta que tiene 38 casillas. Los números son del 1 al 36 (rojo o negro), 0 y 00 (verde)."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/rlt_american/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/rlt_american/logo_es.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-solomonking",
                    "gameName": {
                        "en": "SOLOMON: THE KING",
                        "es": "SALOMÓN: EL REY",
                        "de": "SALOMON: DER KÖNIG",
                        "fr": "SALOMON: LE ROI",
                        "it": "SALOMONE: IL RE",
                        "pt": "SALOMÃO: O REI",
                        "da": "SALOMO: KONGEN",
                        "nb": "SALOMO: KONGEN",
                        "nl": "SALOMON: DE KONING",
                        "ru": "СОЛОМОН: ЦАРЬ",
                        "sv": "SALOMO:KUNGEN",
                        "zh": "所罗门：贤王",
                        "fi": "SOLOMON: KUNINGAS",
                        "tr": "SOLOMON: KRAL",
                        "pl": "SALOMON: KRÓL",
                        "th": "โซโลมอน: จอมราชา",
                        "id": "SULAIMAN: SANG RAJA",
                        "ko": "대왕 솔로몬",
                        "vi": "SOLOMON: NHÀ VUA",
                        "el": "ΣΟΛΟΜΩΝ: O ΒΑΣΙΛΙΑΣ",
                        "ro": "SOLOMON: REGELE"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Travel to King Solomon\'\'s court with this 6x10 reel video slot, where you can win great riches through a fun mini game that merges symbols, and a free spins feature with justice multipliers.",
                        "es": "Viaja a la corte del rey Salomón en esta vídeo slot de 6x10 donde podrás conseguir grandes riquezas a través de un entretenido minijuego de fusión de símbolos y la fase de tiradas gratis con multiplicadores de la justicia."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_es.png",
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_de.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_pt.png",
                        "da": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/ogo_da.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_nl.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_ru.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_ja.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_sv.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_zh.png",
                        "fi": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_fi.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_tr.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_pl.png",
                        "th": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_th.png",
                        "id": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_id.png",
                        "ko": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_ko.png",
                        "vi": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_vi.png",
                        "el": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_el.png",
                        "ro": "https://static2.redrakegaming.com/cdn/img/promo/solomonking/logo_ro.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.20"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-minadegemas",
                    "gameName": {
                        "en": "Gustav Minebuster"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Burrow down to the depths of this magical and mysterious mine to help Gustav find his precious treasure of gems and diamonds. Light the fuse and blow up the dynamite in this 8x8 reel video slot!",
                        "es": "Desciende a las profundidades de esta mágica y misteriosa mina para ayudar a Gustav a encontrar un valioso tesoro de gemas y diamantes. ¡Enciende la mecha y que explote la dinamita en esta videoslot de 8x8 rodillos!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/minadegemas/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.20"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-isisofdestiny",
                    "gameName": {
                        "en": "MOTHER OF HORUS",
                        "es": "MADRE DE HORUS",
                        "de": "MUTTER DES HORUS",
                        "fr": "MÈRE D’HORUS",
                        "it": "MADRE DI HORUS",
                        "pt": "MÃE DE HÓRUS",
                        "da": "HORUS\'\'\'\'\'\'\'\' MOR",
                        "nb": "MOREN TIL HORUS",
                        "nl": "MOEDER VAN HORUS",
                        "ru": "МАТЬ ГОРА",
                        "ja": "ホルスの母",
                        "sv": "HORUSMODERN",
                        "zh": "荷鲁斯之母",
                        "fi": "HORUKSEN ÄITI",
                        "tr": "HORUS\'\'\'\'\'\'\'\'UN ANNESİ",
                        "pl": "MATKA HORUSA",
                        "th": "มารดาแห่งฮอรัส",
                        "id": "IBU HORUS",
                        "ko": "호루스의 어머니",
                        "vi": "MẸ THẦN CHIM ƯNG HORUS",
                        "el": "Η ΜΗΤΕΡΑ ΤΟΥ ΩΡΟΥ",
                        "ro": "MAMA LUI HORUS"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Worship the goddess Isis, the mother of Horus, in this 5x3 reel video slot that has an upper prize bar on each reel, a roulette with jackpots and a free spin feature.",
                        "es": "Sigue los designios de Isis, la madre de Horus, en esta vídeo slot de 5x3 rodillos que incluye una barra superior de recompensas en cada rodillo, una ruleta con jackpots y fase de tiradas gratis."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_es.png",
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_de.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_pt.png",
                        "da": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_da.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_nl.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_ru.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_ja.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_sv.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_zh.png",
                        "fi": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_fi.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_tr.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_pl.png",
                        "th": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_th.png",
                        "id": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_id.png",
                        "ko": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_ko.png",
                        "vi": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_vi.png",
                        "el": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_el.png",
                        "ro": "https://static2.redrakegaming.com/cdn/img/promo/isisofdestiny/logo_ro.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-twinharlequin",
                    "gameName": {
                        "en": "Twin harlequin",
                        "es": "Twin harlequin"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "A burst of colors and wins in this spectacular 5x3 reel video slot with mixed win and free spins roulette.",
                        "es": "Estallido de color y premios en esta espectacular vídeo slot de 5x3 rodillos con ruleta mixta de premios y tiradas gratis."
                    },
                    "imageUrl": {
                        "en": "http://static2.redrakegaming.com/cdn/img/promo/twinharlequin/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.25"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-suertechina",
                    "gameName": {
                        "en": "Cai Shen 88",
                        "ru": "Цай-Шэнь 88",
                        "ja": "財神88",
                        "zh": "财神 88",
                        "th": "เทพเจ้าแห่งโชคลาภ 88",
                        "ko": "부의 신 88",
                        "vi": "Thần Tài 88",
                        "el": "Κάι Σεν 88"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Make your way through the most famous lucky symbols from Chinese culture and watch how the “God of Wealth” awards you multipliers up to x88.",
                        "es": "Ábrete camino entre los símbolos de la suerte más conocidos de la cultura china y observa cómo el \"Dios de la Riqueza\" te regala multiplicadores de hasta x88."
                    },
                    "imageUrl": {
                        "el": "https://static2.redrakegaming.com/cdn/img/promo/suertechina/logo_el.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/suertechina/logo_en.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/suertechina/logo_ja.png",
                        "ko": "https://static2.redrakegaming.com/cdn/img/promo/suertechina/logo_ko.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/suertechina/logo_ru.png",
                        "th": "https://static2.redrakegaming.com/cdn/img/promo/suertechina/logo_th.png",
                        "vi": "https://static2.redrakegaming.com/cdn/img/promo/suertechina/logo_vi.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/suertechina/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.25"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-matahari",
                    "gameName": {
                        "en": "MATA HARI: the spy",
                        "es": "MATA HARI: la espía",
                        "de": "MATA HARI: Die Spionin",
                        "fr": "MATA HARI: l\'\'espionne",
                        "it": "MATA HARI: la spia",
                        "pt": "MATA HARI: a espia",
                        "da": "MATA HARI: spionen",
                        "nb": "MATA HARI: spionen",
                        "nl": "MATA HARI: de spion",
                        "ru": "МАТА ХАРИ: разведчица",
                        "ja": "マタ・ハリ：女スパイ",
                        "sv": "MATA HARI: spionen",
                        "zh": "美女间谍玛塔·哈丽",
                        "fi": "MATA HARI: vakooja",
                        "tr": "MATA HARI: Casus",
                        "pl": "MATA HARI: szpieg",
                        "th": "มาตา ฮารี: ยอดสายลับ",
                        "id": "MATA HARI: sang mata-mata",
                        "ko": "마타하리: 스파이",
                        "vi": "MATA HARI: điệp viên",
                        "el": "Μάτα Χάρι: η κατάσκοπος",
                        "ro": "MATA HARI: spioana"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Allow yourself to be seduced by the most famous spy in history with this 6x6 reel video slot and get up to 144 free spins.",
                        "es": "Déjate seducir por la espía mas famosa de la historia en esta vídeo slot de 6x6 rodillos y consigue hasta 144 tiradas gratis."
                    },
                    "imageUrl": {
                        "cn": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_cn.png",
                        "da": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_da.png",
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_de.png",
                        "el": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_el.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_es.png",
                        "fi": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_fi.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_fr.png",
                        "id": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_id.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_it.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_ja.png",
                        "ko": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_ko.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_nl.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_pl.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_pt.png",
                        "ro": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_ro.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_ru.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_sv.png",
                        "th": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_th.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_tr.png",
                        "vi": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_vi.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/matahari/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.08"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-millionsevens",
                    "gameName": {
                        "en": "MILLION 7",
                        "ru": "Миллион 7",
                        "ja": "ミリオン 7",
                        "zh": "百万 7",
                        "th": "เป็นล้านตัว 7",
                        "id": "Jutaan 7",
                        "ko": "밀리언 7",
                        "vi": "Triệu 7",
                        "el": "ΕΚΑΤΟΜΜΥΡΙΟ 7",
                        "ro": "MILIOANE 7"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Experience all the excitement in this video slot millionways where banknotes, sevens, cherries and much more will surprise you with every spin.",
                        "es": "Vive toda la emoción en esta video slot millionways donde símbolos de billetes, sietes, cerezas y muchos más, te sorprenderán en cada tirada."
                    },
                    "imageUrl": {
                        "el": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_el.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_en.png",
                        "id": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_id.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_ja.png",
                        "ko": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_ko.png",
                        "ro": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_ro.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_ru.png",
                        "th": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_th.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/millionsevens/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.20"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-alibaba",
                    "gameName": {
                        "en": "THE ADVENTURES OF ALI BABA",
                        "es": "LAS AVENTURAS DE ALÍ BABÁ",
                        "de": "DIE ABENTEUER DES ALI BABA",
                        "fr": "LES AVENTURES D\'\'ALI BABA",
                        "it": "LES AVENTURES D\'\'ALI BABA",
                        "pt": "AS AVENTURAS DE ALI BABÁ",
                        "da": "ALI BABAS EVENTYR",
                        "nb": "ALI BABAS EVENTYR",
                        "nl": "DE AVONTUREN VAN ALI BABA",
                        "ru": "Приключения Али-Бабы",
                        "ja": "アリババの冒険",
                        "sv": "ALI BABAS ÄVENTYR",
                        "zh": "阿里巴巴历险记",
                        "fi": "ALI BABAN SEIKKAILUT",
                        "tr": "ALI BABA\'\'NıN SERÜVENLERI",
                        "pl": "PRZYGODY ALI BABY",
                        "th": "การผจญภัยของอาลีบาบา",
                        "id": "PETUALANGAN ALIBABA",
                        "ko": "알리바바의 모험",
                        "vi": "Những cuộc phiêu lưu của Alibaba",
                        "el": "Οι περιπέτειες του Αλή Μπαμπά",
                        "ro": "AVENTURILE LUI ALI BABA"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Enter into the magical world of Ali Baba and secure the treasure of the 40 thieves. A 6x6 reels video slot with an infinite free spins feature.",
                        "es": "Entra en el mundo mágico de Alí Babá y consigue el tesoro de los 40 ladrones. Una videoslot de 6x6 rodillos con fase de tiradas gratis infinita."
                    },
                    "imageUrl": {
                        "da": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_da.png",
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_de.png",
                        "el": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_el.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_es.png",
                        "fi": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_fi.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_fr.png",
                        "id": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_id.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_it.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_ja.png",
                        "ko": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_ko.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_nl.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_pl.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_pt.png",
                        "ro": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_ro.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_ru.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_sv.png",
                        "th": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_th.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_tr.png",
                        "vi": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_vi.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/alibaba/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-super12stars",
                    "gameName": {
                        "en": "Super 12 Stars"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "*After the huge success of Super 5, Super 7 and Super 10 let’s welcome Super 12 Stars. A 5x3 reel video slot that brings together the best of the previous editions and adds the “Lucky Stars” characteristic, which includes 3 types of Jackpots and instant wins. *",
                        "es": "*Tras los grandes éxitos de Super 5, Super 7 y Super 10 damos la bienvenida a Super 12 Estrellas. Una videoslot de 5X3 rodillos que reúne todo lo mejor de las anteriores y añade además la característica “Estrellas de la Suerte”, que incluye 3 tipos de Jackpots y premios directos. *"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/super12stars/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.30"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-marcopolo",
                    "gameName": {
                        "en": "The Travels of Marco",
                        "es": "Los viajes de Marco",
                        "de": "Die Reisen des Marco",
                        "fr": "Les voyages de Marco",
                        "it": "I Viaggi di Marco",
                        "pt": "As viagens de Marco",
                        "da": "Marcos Rejser",
                        "nb": "Marcos reiser",
                        "nl": "De reizen van Marco",
                        "ru": "Марко Поло",
                        "ja": "マルコポーロの旅",
                        "sv": "Marcos resor",
                        "zh": "马可·波罗",
                        "fi": "Marcon matkat",
                        "tr": "Marco\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'nun Seyahatleri",
                        "pl": "Podróże Marco",
                        "th": "มาร์โก โปโล",
                        "id": "Perjalanan Marco",
                        "ko": "마르코 폴로",
                        "vi": "Những cuộc du hành của Marco",
                        "el": "Μάρκο Πόλο",
                        "ro": "Călătoriile lui Marco"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Travel with Marco Polo through Europe and Asia to build up wealth and earn an authentic fortune in this 5x3 reel and 30 winning lines video slot.",
                        "es": "Viaja junto a Marco Polo por Europa y Asia para acumular riquezas y conseguir una auténtica fortuna en esta videoslot de 5x3 carretes y 30 líneas de premio."
                    },
                    "imageUrl": {
                        "da": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_da.png",
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_es.png",
                        "fi": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_fi.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_fr.png",
                        "id": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_id.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_it.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_nl.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_pl.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_pt.png",
                        "ro": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_ro.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_sv.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/marcopolo/logo_tr.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.30"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-knights",
                    "gameName": {
                        "en": "Knights"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Join an unprecedented heroic act in the Kingdom of Camelot to find the Holy Grail with the most courageous knights of the round table in this 5x3 reel video slot with a magic reel that will help you to gain incredible riches.",
                        "es": "Únete a una gesta sin precedentes en el reino de Camelot para encontrar el Santo Grial junto a los caballeros más valientes de la mesa redonda en esta videoslot de 5x3 rodillos y un rodillo mágico que te ayudará a conseguir increíbles riquezas."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/knights/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.01"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-bonnieandclyde",
                    "gameName": {
                        "en": "Bonnie & Clyde"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Come to the United States in the 30s and join two of history’s most famous fugitives to grab a big loot in this 5x4 reel and 50 prize line video slot.",
                        "es": "Trasládate a los Estados Unidos de los años 30 y únete a dos de los fugitivos más famosos de la historia para conseguir un gran botín en esta videoslot de 5x4 rodillos y 50 líneas de premio."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/bonnieandclyde/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-tigreydragon",
                    "gameName": {
                        "en": "TIGER AND DRAGON",
                        "es": "TIGRE Y DRAGÓN",
                        "de": "TIGER UND DRACHE",
                        "fr": "TIGRE ET DRAGON",
                        "it": "LA TIGRE E IL DRAGONE",
                        "pt": "TIGRE E DRAGÃO",
                        "da": "TIGER OG DRAGE",
                        "nb": "TIGER OG DRAGE",
                        "nl": "TIJGER EN DRAAK",
                        "ru": "ТИГР И ДРАКОН",
                        "sv": "TIGERN OCH DRAKEN",
                        "fi": "TIIKERI JA LOHIKÄÄRME",
                        "tr": "KAPLAN VE EJDERHA",
                        "pl": "TYGRYS I SMOK"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Legend has it that in ancient China a powerful white tiger and a terrifying dragon have been guarding valuable treasures for years hidden in this 6x10 reel video slot.",
                        "es": "Cuenta la leyenda que en la antigua China un poderoso tigre blanco y un temido dragón custodian desde hace años valiosos tesoros que esconden en esta video slot de 6x10 rodillos."
                    },
                    "imageUrl": {
                        "da": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_da.png",
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_fr.png",
                        "id": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_id.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_it.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_ja.png",
                        "ko": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_ko.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_nl.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_pl.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_pt.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_ru.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_sv.png",
                        "th": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_th.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_tr.png",
                        "vi": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_vi.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/tigreydragon/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.20"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-magicwilds",
                    "gameName": {
                        "en": "Magic Wilds",
                        "es": "Wilds Mágicos",
                        "de": "Magische Wilds",
                        "fr": "Wilds Magiques",
                        "it": "Wilds Magici",
                        "pt": "Wilds Mágicos",
                        "da": "Magiske Wilds",
                        "nb": "Magiske Wilds",
                        "nl": "Magische Wilds",
                        "ru": "Волшебные символы wild",
                        "ja": "マジックワイルド",
                        "sv": "Magiska Wild-symboler",
                        "zh": "狂野魔力",
                        "fi": "Taika-wilds",
                        "tr": "Büyülü Wilds",
                        "pl": "Magiczne symbole wild",
                        "th": "เมจิกwilds",
                        "id": "Ajaib liar",
                        "ko": "매직 와일드",
                        "vi": "Wilds ma thuật"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Become enchanted by the great magician who will transform your spins into incredible wins!",
                        "es": "¡Déjate hechizar por el gran mago que transformará tus spins en increíbles premios!"
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_es.png",
                        "fi": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_fi.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_it.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_nb.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_pl.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_ru.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_sv.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/magicwilds/logo_tr.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-maya",
                    "gameName": {
                        "en": "MAYA",
                        "es": "MAYA",
                        "de": "MAYA",
                        "fr": "MAYA",
                        "it": "MAYA",
                        "pt": "MAYA",
                        "da": "MAYA",
                        "nb": "MAYA",
                        "nl": "MAYA",
                        "sv": "MAYA",
                        "fi": "MAYA",
                        "tr": "MAYA",
                        "pl": "MAYA",
                        "th": "MAYA",
                        "id": "MAYA",
                        "ko": "MAYA",
                        "vi": "MAYA"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Experience real adventure in this 5x3 reel video slot in which the powerful Mayan Queen guides you towards incredible treasures in order to give you a unique experience full of wonder",
                        "es": "Vive una auténtica aventura en esta video slot de 5x3 rodillos donde una poderosa Reina Maya te guiará a través de increíbles tesoros para hacerte vivir una experiencia única y repleta de misterios."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/maya/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.50"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vivalasvegas",
                    "gameName": {
                        "en": "Viva Las Vegas"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Music, entertainment and much fun take place in this videoslot which features 5x4 reels that will give you fantastic multipliers for any win that you get in the bonus round.",
                        "es": "Música, entretenimiento y mucha diversión se dan cita en esta videoslot de 5x4 carretes con la que podrás conseguir fantásticos multiplicadores por cada premio de la fase de tiradas gratis."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vivalasvegas/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-speedheroes",
                    "gameName": {
                        "en": "Speed Heroes",
                        "es": "Héroes del volante",
                        "de": "Tempohelden",
                        "fr": "Héros du volant",
                        "it": "Eroi della velocità",
                        "pt": "Reis da velocidade",
                        "da": "Farthelte",
                        "nb": "Fartshelter",
                        "nl": "Topcoureurs",
                        "ru": "Герои колеса",
                        "ja": "スピードヒーローズ",
                        "sv": "Farthjältarna",
                        "zh": "速度英雄",
                        "fi": "Vauhtihirmut",
                        "tr": "Hizli kahramanlar",
                        "pl": "Bohaterowie prędkości",
                        "id": "PAHLAWAN KECEPATAN",
                        "th": "สปีดฮีโร่",
                        "ko": "스피드 히어로즈",
                        "vi": "ANH HÙNG TỐC ĐỘ"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "WINS AT FULL SPEED! Start your engines and feel the rush of a real racing car! *",
                        "es": "¡PREMIOS A TODA VELOCIDAD! ¡Calienta motores y siente la emoción de un verdadero coche de carreras ganando premios de vértigo!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_fr.png",
                        "id": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_id.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_it.png",
                        "ko": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_ko.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_pl.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_pt.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_ru.png",
                        "th": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_th.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_tr.png",
                        "vi": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_vi.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/speedheroes/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.06"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-mysticmirror",
                    "gameName": {
                        "en": "Mystic Mirror",
                        "es": "Mystic Mirror"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Shiny Magical Wins! Unicorns, a beatiful princess and a mystic mirror that is the key to success!",
                        "es": "¡Brillantes y mágicos premios! Unicornios, una hermosa princesa y un espejo que... ¡es la clave del éxito!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/mysticmirror/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.02"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-talentshow",
                    "gameName": {
                        "en": "JUDGES RULE THE SHOW!",
                        "es": "JUDGES RULE THE SHOW!",
                        "de": "JUDGES RULE THE SHOW!",
                        "fr": "JUDGES RULE THE SHOW!",
                        "it": "JUDGES RULE THE SHOW!",
                        "pt": "JUDGES RULE THE SHOW!",
                        "da": "JUDGES RULE THE SHOW!",
                        "nb": "JUDGES RULE THE SHOW!",
                        "nl": "JUDGES RULE THE SHOW!",
                        "sv": "JUDGES RULE THE SHOW!",
                        "fi": "JUDGES RULE THE SHOW!",
                        "tr": "JUDGES RULE THE SHOW!",
                        "ja": "[審査員の心をわしづかみ！",
                        "ru": "Судьи решают всё!",
                        "zh": "法官统御全场！"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Participate in the biggest talent contest in history! Four judges, four buzzers and a multitude of respins in an explosive cocktail of prizes.",
                        "es": "¡Participa en el mayor concurso de talentos de la historia! Cuatro jueces, cuatro pulsadores y multitud de respins en un cóctel explosivo de premios."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/talentshow/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.06"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-jinetevsjack",
                    "gameName": {
                        "en": "Jack O\'Lantern",
                        "es": "Jack O\'Lantern",
                        "de": "Jack O\'Lantern",
                        "fr": "Jack O\'Lantern",
                        "it": "Jack Lanterna",
                        "pt": "Jack O\'Lantern",
                        "da": "Jack O\'Lantern",
                        "nb": "Jack O\'Lantern",
                        "nl": "Jack O\'Lantern",
                        "ru": "Джек Фонарь",
                        "ja": "ジャックランタン",
                        "sv": "Jack O\'Lantern",
                        "zh": "杰克南瓜灯和无头骑士",
                        "fi": "Jack O\'Lantern",
                        "tr": "Jack O\'Lantern"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "These 2 mythical characters come through the fog, darkness and silence to spread chaos between spin and spin and seize the power of the reels while they give you out thousands of prizes.",
                        "es": "Entre la niebla, la oscuridad y el silencio llegan estos dos míticos personajes para sembrar el caos entre spin y spin y hacerse con el poder de los carretes mientras reparten miles de premios."
                    },
                    "imageUrl": {
                        "da": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_da.png",
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_es.png",
                        "fi": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_fi.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_it.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_ja.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_nl.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_pl.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_pt.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_ru.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_sv.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_tr.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/jinetevsjack/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.06"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-cleopatra",
                    "gameName": {
                        "en": "THE ASP OF CLEOPATRA",
                        "es": "EL ÁSPID DE CLEOPATRA",
                        "de": "DIE ASPIS DER CLEOPATRA",
                        "fr": "L\'ASPIC DE CLÉOPÂTRE",
                        "it": "L\'ASPIDE DI CLEOPATRA",
                        "pt": "A ÁSPIDE DE CLEÓPATRA",
                        "da": "CLEOPATRAS ASPISSLANGE",
                        "nb": "KLEOPATRAS ASP",
                        "nl": "DE SLANG VAN CLEOPATRA",
                        "ru": "ЗМЕЯ КЛЕОПАТРЫ",
                        "ja": "クレオパトラのコブラ",
                        "sv": "CLEOPATRA’S ASP",
                        "zh": "埃及艳后的毒蛇",
                        "fi": "CLEOPATRAN ASP",
                        "tr": "KLEOPATRA\'NIN YILANI"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Relive Ancient Egypt, its grand queen Cleopatra and the magnificient golden Asp that will give you up to 1000 free spins!",
                        "es": "Revive el Antiguo Egipto, Cleopatra, su gran reina, y el magnífico áspid de oro con el que podrás ganar hasta 1.000 tiradas gratis."
                    },
                    "imageUrl": {
                        "da": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_da.png",
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_es.png",
                        "fi": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_fi.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_it.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_ja.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_nl.png",
                        "pl": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_pl.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_pt.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_ru.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_sv.png",
                        "tr": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_tr.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/cleopatra/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.25"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-super10stars",
                    "gameName": {
                        "en": "Super 10 Stars",
                        "es": "Super 10 Stars",
                        "de": "Super 10 Stars",
                        "fr": "Super 10 Stars",
                        "it": "Super 10 Stars",
                        "pt": "Super 10 Stars"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "A great Super 10 symbol with three different features adds to the proven fun of Super 5 and Super 7 Stars. Triple your chances and win big!",
                        "es": "El nuevo símbolo Super 10 Estrellas se suma a la diversión probada de Super 5 y Super 7 Estrellas. ¡Triplica tus opciones y gana a lo grande!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/super10stars/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.30"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-chinagoddesses",
                    "gameName": {
                        "en": "Eastern Goddesses",
                        "es": "Diosas de Oriente",
                        "ja": "中華の女神",
                        "fi": "ITÄMAISET JUMALATTARET",
                        "tr": "DOĞU TANRIÇALARI",
                        "ru": "ВОСТОЧНАЯ БОГИНЯ",
                        "zh": "中国女神",
                        "fr": "DÉESSES DE L’ORIENT",
                        "it": "DEE DELL\'ORIENTE",
                        "pt": "DEUSAS DO ORIENTE",
                        "de": "ORIENTALISCHE GÖTTINNEN",
                        "nl": "GODINNEN VAN HET OOSTEN",
                        "nb": "ØSTENS GUDINNER",
                        "sv": "ORIENTALISKA GUDINNOR"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "WATER, FIRE AND WIND. Three of the main elements of nature are joining forces in order to attain eternal fortune.",
                        "es": "AGUA, FUEGO Y VIENTO. Tres de los elementos principales de la naturaleza se unen para conseguir la eterna fortuna."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_it.png",
                        "ja": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_ja.png",
                        "nb": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_nb.png",
                        "nl": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_nl.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_pt.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_ru.png",
                        "sv": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_sv.png",
                        "zh": "https://static2.redrakegaming.com/cdn/img/promo/chinagoddesses/logo_zh.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.06"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-irishslot",
                    "gameName": {
                        "en": "Ryan O\'Bryan",
                        "es": "Ryan O\'Bryan"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Flying fairies, pots full of money and a naughty leprechaun that will help you to increase your luck! Wilds everywhere!",
                        "es": "¡Hadas voladoras, ollas repletas de monedas y un duende muy travieso que te ayudará a conseguir que aumente tu suerte! ¡Wilds por doquier!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/irishslot/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.06"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-wildcano",
                    "gameName": {
                        "en": "Wildcano",
                        "es": "Wildcano",
                        "de": "Wildcano",
                        "fr": "Wildcano",
                        "it": "Wildcano",
                        "pt": "Wildcano"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "The music, the atmosphere, the attention to graphic detail, the Orbital ReelsTM presentation will inmerse you into a great adventure in a desert island with a mysterious volcano.  Creativity, beautiful design and exciting new features that offer the player a unique and memorable experience.  3 Reels and 8 paylines. Free spins feature with WILD REEL. Pyroclasts WILD eruption feature. Second chance Earthquake feature. Magma feature with direct win. Symbol Sacrifice feature.",
                        "es": "La música, la atmosfera, la atención al detalle, la presentación con Orbital ReelsTM te harán vivir una gran aventura en una isla desierta con un volcán misterioso.  Creatividad, un diseño espectacular y nuevas y excitantes posibilidades que ofrecen al jugador una experiencia memorable y única.  3 rodillos y 8 líneas de premio. Fase de tiradas gratis con WILD REEL. Erupción de piroclastos WILD. Terremoto para una segunda oportunidad de ganar. Premios directos con el evento Magma. Sacrificio de símbolos."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/wildcano/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.04"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-extra100x",
                    "gameName": {
                        "en": "EXTRA 100X",
                        "es": "EXTRA 100X",
                        "de": "EXTRA 100X",
                        "fr": "EXTRA 100X",
                        "it": "EXTRA 100X",
                        "pt": "EXTRA 100X"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Classic 3-reel slot + 1 extra reel and a fixed payline."
                    },
                    "imageUrl": {
                        "en": "http://cdn.redrakegaming.com/img/promo/extra100x/extra100x_logo_350_en.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-electricsevens",
                    "gameName": {
                        "en": "Electric Sevens",
                        "es": "Sietes Eléctricos",
                        "de": "Die Elektrischen Siebener",
                        "fr": "Septs Électriques",
                        "it": "Sette Elettrici",
                        "pt": "Setes Elétricos"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Electrify your wins in this 5x3 and 30 paylines slot. Maximize your wins with Huge symbols and transform all seven symbols to the same color with electric sevens symbols and combine the seven symbols in the top extra reel with the reels ones to win big in the free spins feature."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/electricsevens/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-witch",
                    "gameName": {
                        "es": "La bruja Myrtle",
                        "en": "Myrtle the Witch"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Are this reels bewitched? Discover what\'s behind the Crystal Balls, mystery symbols that reveal great wins in this 5x3 reel and 30 paylines slot. Get free spins, multipliers and direct wins in the Magic Cauldron minigame and get into the free spins stage where Myrtle will enhance your luck with her magic spells."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/witch/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/witch/logo_es.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/witch/logo_ru.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.06"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-fantasmaopera",
                    "gameName": {
                        "en": "The Secret Of The Opera",
                        "es": "El Secreto De La Ópera",
                        "de": "DAS GEHEIMNIS DER OPER ",
                        "da": "THE SECRET OF THE OPERA ",
                        "fr": "LE SECRET DE L\'OPÉRA ",
                        "it": "IL SEGRETO DELL\'OPERA",
                        "ja": "THE SECRET OF THE OPERA",
                        "nb": "OPERAENS HEMMELIGHET",
                        "nl": "HET GEHEIM VAN DE OPERA",
                        "pt": "O SEGREDO DA ÓPERA",
                        "ru": "THE SECRET OF THE OPERA ",
                        "sv": "HEMLIGHETEN PÅ OPERAN ",
                        "zh": "歌剧之谜"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "One of the greatest love stories takes place at the Palais Garnier opera house hall. Join Christine and the Phantom in this 50 paylines, 6x4 reels slot filled with exhilarating music and big prizes. Make the most out of the Moving Wild Reels and use the pipe organ in the Great Oberture Bonus to win lots of spins for the Free Spins stage."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/fantasmaopera/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/fantasmaopera/logo_es.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/fantasmaopera/logo_ru.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-butterflies",
                    "gameName": {
                        "en": "3 Butterflies",
                        "es": "3 Mariposas",
                        "de": "3 Schmetterlinge",
                        "da": "3 Butterflies",
                        "fr": "3 Papillons",
                        "it": "3 Farfalle",
                        "nb": "3 Sommerfugler",
                        "nl": "3 Vlinders",
                        "pt": "3 Borboletas",
                        "sv": "3 Fjärilar",
                        "zh": "3只蝴蝶"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "This videoslot consists of 5 reels, 4 rows and 50 fixed pay lines. Its flying butterflies transform into WILDS, BONUSES and MINIGAMES, allowing you to win incredible prizes. Each FLYING WILD expands in order to occupy 4 positions and to multiply the pay line up to x6."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/butterflies/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-redvsblue",
                    "gameName": {
                        "en": "Red Dragon VS Blue Dragon",
                        "es": "Dragón Rojo VS Dragón Azul",
                        "de": "Roter Drache gegen blauer Drache",
                        "da": "Red Dragon VS Blue Dragon",
                        "fr": "Dragon Rouge VS Dragon Bleu",
                        "it": "Dragone Rosso VS Dragone Blu",
                        "ja": "Red Dragon VS Blue Dragon",
                        "nb": "Rød drage mot Blå drage",
                        "nl": "Rode Draak vs Blauwe Draak",
                        "pt": "Dragão Vermelho VS Dragão Azul",
                        "ru": "Red Dragon VS Blue Dragon",
                        "sv": "Röda draken mot blåa draken",
                        "zh": "红龙对蓝龙"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "A Videoslot with 6 Reel and 50 paylines."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/redvsblue/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/redvsblue/logo_es.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/redvsblue/logo_ru.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-diamante235",
                    "gameName": {
                        "en": "Respins & Diamonds",
                        "es": "Respins & Diamonds",
                        "zh": "旋转与钻石"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Enjoy the casino excitement with this classic slot that adds to its feature set, the new WILD RESPIN that awards up to 5 respins to get more prizes in a single go."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/diamante235/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-super7stars",
                    "gameName": {
                        "en": "Super 7 Stars",
                        "es": "Super 7 Estrellas",
                        "de": "Super 7 Sternen",
                        "da": "Super 7 Stars",
                        "fr": "Super 7 Étoiles ",
                        "it": "Super 7 Stelle",
                        "ja": "Super 7 Stars",
                        "nb": "Super 7 Stjerner",
                        "nl": "Super 7 Sterren",
                        "pt": "Super 7 Estrelas",
                        "ru": "Super 7 Stars",
                        "sv": "Super 7 Stars",
                        "zh": "超级7星"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Take the Super 5 stars proved playability and engagement and add a Wild Respin and a Lucky Roulette and you have a winner!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/super7stars/logo_en.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/super7stars/logo_ru.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.30"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-heidioktoberfest",
                    "gameName": {
                        "en": "Heidi at Oktoberfest",
                        "es": "Heidi en la Oktoberfest",
                        "it": "Heidi all\'\'Oktoberfest"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Partying, beers, lots of entertainment and big prizes at our German tavern! Heidi will be your host on this 5x4 reel slot machine, she will bring you beers…and luck! The more symbols Heidi obtains, the more free spins you will obtain."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/heidioktoberfest/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/heidioktoberfest/logo_es.png",
                        "ru": "https://static2.redrakegaming.com/cdn/img/promo/heidioktoberfest/logo_ru.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-megaestelar",
                    "gameName": {
                        "en": "Mega Stellar",
                        "es": "Mega Estelar",
                        "de": "Mega Stellar",
                        "da": "Mega Stjernernes",
                        "fr": "Mega Stellaire",
                        "it": "Mega Stellare",
                        "ja": "Mega Stellar",
                        "nb": "Mega Stellar",
                        "nl": "Mega Stellaire",
                        "pt": "Mega Estelar",
                        "ru": "Mega Stellar",
                        "sv": "Mega Stellar",
                        "zh": "巨恒星"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Beautiful jewels, colours and surround sound will make your heart beat with sensational prizes. Obtain up to 3 additional respins by accumulating Mega Stellar Wilds and multiply your earnings."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/megaestelar/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/megaestelar/logo_es.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.10"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-super5stars",
                    "gameName": {
                        "en": "Super 5 Stars",
                        "es": "Super 5 Estrellas",
                        "de": "Super 5 Sternen",
                        "da": "Super 5 Stars",
                        "fr": "Super 5 Étoiles ",
                        "it": "Super 5 Stelle",
                        "ja": "Super 5 Stars",
                        "nb": "Super 5 Stjerner",
                        "nl": "Super 5 Sterren",
                        "pt": "Super 5 Estrelas",
                        "ru": "Super 5 Stars",
                        "sv": "Super 5 Stars",
                        "zh": "超级5星"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Meet the awesome SUPER 5 and SUPER 5 STARS symbols joining the popular fiery sevens, cherries, bars and bells, and get up to 50 free spins with the tv like minigame!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/super5stars/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.30"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-dragonfortunes",
                    "gameName": {
                        "en": "The Legendary Red Dragon",
                        "es": "El Legendario Dragon Rojo",
                        "de": "Der legendäre ROTE DRACHE",
                        "da": "Den legendariske RØDE DRAGE",
                        "fr": "Le légendaire DRAGON ROUGE",
                        "it": "Il leggendario DRAGO ROSSO",
                        "ja": "伝説の紅龍",
                        "nb": "Legendariske DEN RØDE DRAGE",
                        "nl": "De legendarische RODE DRAAK",
                        "pt": "O lendário DRAGÃO VERMELHO",
                        "ru": "Легендарный Красный Дракон",
                        "sv": "The Legendary Red Dragon",
                        "zh": "传奇红龙"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Videoslot with 6 reels and 50 paylines."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/dragonfortunes/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/dragonfortunes/logo_es.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.25"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-tumbling-diamonds",
                    "gameName": {
                        "en": "Queens and Diamonds",
                        "es": "Reinas y Diamantes",
                        "de": "Königinnen und Diamanten",
                        "da": "Dronninger og Diamanter",
                        "fr": "Reines et de diamants",
                        "it": "Regine e Diamanti",
                        "ja": "Queens and Diamonds",
                        "nb": "Dronninger og Diamanter",
                        "nl": "Döninginnen og Diamanter",
                        "pt": "Rainhas e diamantes",
                        "ru": "алмазные королевы",
                        "sv": "Queens and Diamonds",
                        "zh": "皇后与钻石"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Jewels, diamonds and all the wealth of royalty await you in Diamond Queens."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/tumbling-diamonds/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.20"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-buffalo",
                    "gameName": {
                        "en": "Ragin\' Buffalo",
                        "es": "Ragin\' Buffalo",
                        "ru": "Яростный буйвол",
                        "zh": "愤怒的野牛"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Get the legendary Buffalo in one of the most exciting slots!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/buffalo/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.08"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-wolf",
                    "gameName": {
                        "en": "Siberian Wolf",
                        "es": "Lobo Siberiano",
                        "de": "Sibirischer Wolf",
                        "da": "Sibirisk ulv",
                        "fr": "Loup Sibérien",
                        "it": "Lupo Siberiano",
                        "ja": "シベリアンハスキー",
                        "nb": "Sibirulven",
                        "nl": "Siberische Wolf",
                        "pt": "Lobo siberiano",
                        "ru": "Сибирский волк",
                        "sv": "Siberian Wolf",
                        "zh": "西伯利亚之狼"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Spectacular videos, animation, sound and design, watch the Siberian wolf howl at the moon..."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/wolf/logo_en.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.08"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-egypt",
                    "gameName": {
                        "en": "Mysteries of Egypt",
                        "es": "Misterios de Egipto"
                    },
                    "gameType": "slot",
                    "description": {
                        "en": "Delve into the Mysteries of Egypt and discover the pharaoh\'\'s tomb while getting fantastic prizes. This slot takes you back into the world of ancient Egypt thanks to its amazing graphics, animation and music."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/egypt/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/egypt/logo_es.png"
                    },
                    "html": true,
                    "frb": true,
                    "frbRoundMinValue": "0.05"
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_football",
                    "gameName": {
                        "en": "World Football",
                        "es": "Fútbol mundial",
                        "de": "Fußballweltmeisterschaft",
                        "fr": "Football Mondial",
                        "it": "Calcio mondiale",
                        "pt": "Futebol Mundial"
                    },
                    "gameType": "vb",
                    "description": {
                        "en": "The football world cup has come early to Red Rake! 17 patterns with a prize, 10 extra balls and two bonus phases with the mini game of football shirts and the fun penalty shootout mini game. Score and win!",
                        "es": "¡El mundial de fútbol se ha adelantado en Red Rake! 17 patrones con premio, 10 bolas extra y dos fases de bonus con el minijuego de las camisetas el divertido minijuego de lanzamientos de penaltis. ¡Acierta y gana!"
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_football/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_football/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_football/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_football/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_football/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_football/logo_pt.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_disco",
                    "gameName": {
                        "en": "Disco Nights",
                        "es": "Noches de disco",
                        "de": "Disco-Nächte",
                        "fr": "Nuits disco",
                        "it": "Serate in disco",
                        "pt": "Noites de discoteca"
                    },
                    "gameType": "vb",
                    "description": {
                        "en": "Enjoy our latest release to the beat of Disco music! A colourful Video Bingo with 2 bonus phases and 21 patterns with a prize where winning will be easier than ever!",
                        "es": "¡Disfruta al ritmo de la música Disco de nuestro último estreno! ¡Un colorido Video Bingo con 2 fases de bonus y 21 patrones con premio donde ganar te resultará más fácil que nunca!"
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_disco/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_disco/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_disco/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_disco/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_disco/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_disco/logo_pt.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_muertitos",
                    "gameName": {
                        "en": "Muertitos",
                        "es": "Muertitos"
                    },
                    "gameType": "vb",
                    "description": {
                        "en": "The scary and funny MUERTITOS has already conquered Red Rake! A Video Bingo packed with new features with a fun mini game and 19 patterns with a prize!",
                        "es": "¡El terrorífico y divertido MUERTITOS ya ha conquistado Red Rake! ¡Un video bingo repleto de novedades, con un divertido minijuego y 19 patrones con un premio!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_muertitos/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_megamoney",
                    "gameName": {
                        "en": "Megamoney",
                        "es": "Megamoney",
                        "de": "Megamoney",
                        "fr": "Megamoney",
                        "it": "Megamoney",
                        "pt": "Megamoney"
                    },
                    "gameType": "vb",
                    "description": {
                        "en": "Discover this new Video Bingo with spectacular prizes.",
                        "es": "Descubre este nuevo Video Bingo cargado de espectaculares premios."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_megamoney/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_megamoney/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_megamoney/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_megamoney/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_megamoney/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_megamoney/logo_pt.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_world",
                    "gameName": {
                        "en": "Travel with us",
                        "es": "Viaja con nosotros",
                        "de": "Reise mit uns",
                        "fr": "Voyagez avec nous",
                        "it": "Viaggia con noi",
                        "pt": "Vem viajar com a gente"
                    },
                    "gameType": "vb",
                    "description": {
                        "en": "Wait no more and come travelling with us! Discover other countries in this incredible video bingo packed with surprises.",
                        "es": "¡No esperes más y vente de viaje con nosotros! Descubre otros países en este increible video bingo cargado de sorpresas."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_world/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_world/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_world/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_world/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_world/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_world/logo_pt.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_hollywood",
                    "gameName": {
                        "en": "Hollywood dreams",
                        "es": "Sueños de Hollywood",
                        "de": "Träume von Hollywood",
                        "fr": "Rêves d\'Hollywood",
                        "it": "Sogni di Hollywood",
                        "pt": "Sonhos de Hollywood"
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "Viaja a la meca del cine, déjate llevar por el sueño de Hollywood y consigue el ansiado premio en la Fase de Bonus.",
                        "en": "Travel to the mecca of cinema, be carried away by the Hollywood dream and obtain the desired prize in the Bonus Phase."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_hollywood/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_hollywood/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_hollywood/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_hollywood/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_hollywood/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_hollywood/logo_pt.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_gods",
                    "gameName": {
                        "en": "Greek gods",
                        "es": "Dioses griegos",
                        "de": "Griechische götter",
                        "fr": "Dieux grecs",
                        "it": "Dei greci",
                        "pt": "Deuses gregos",
                        "da": "",
                        "nb": "",
                        "nl": "",
                        "ru": "",
                        "ja": "",
                        "sv": "",
                        "zh": "",
                        "fi": "",
                        "tr": ""
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "¡Vence a los Dioses Griegos y conquista la cima del Olimpo!",
                        "en": "Defeat the Greek Gods and conquer the summit of Olympus!"
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_gods/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_gods/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_gods/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_gods/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_gods/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_gods/logo_pt.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_heidi",
                    "gameName": {
                        "es": "La taberna de Heidi",
                        "en": "Heidi\'s tavern",
                        "de": "Heidis taverne",
                        "fr": "Le bistrot de Heidi",
                        "it": "La taverna di Heidi",
                        "pt": "A taberna de Heidi"
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "La Oktoberfest ha llegado a Red Rake Gaming con increíbles novedades y espectaculares premios.\nGráficos realistas.\n11 bolas extra con bola comodín y bola extra gratis.\nAl aparecer las bolas extra conocerás de antemano cuando conseguirás una bola extra GRATIS.\nFase de Bonus con el minijuego de la Taberna de Heidi.\n4 cartones con 12 patrones de premio diferentes.",
                        "en": "Oktoberfest has reached Red Rake Gaming with incredible new features and spectacular prizes.\nRealistic graphics.\n11 extra balls with a wild ball and extra free ball.\nWhen the extra balls appear you will find out beforehand when you will obtain an extra FREE ball.\nBonus phase with the Heidi´s Tavern mini game.\n4 cards with 12 different prize patterns."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_heidi/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_heidi/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_heidi/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_heidi/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_heidi/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_heidi/logo_pt.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_egypt",
                    "gameName": {
                        "es": "La tumba del faraón",
                        "en": "Pharaoh\'s tomb",
                        "de": "Das grab des pharao",
                        "fr": "Le tombeau du pharaon",
                        "it": "La tomba del faraone",
                        "pt": "O tumulo do farao"
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "Adéntrate en los Misterios de Egipto y descubre la tumba del faraón consiguiendo premios espectaculares.\nGráficos realistas.\n4 cartones con 15 patrones de premio diferentes.\n12 bolas extra con bola comodín y bola extra gratis.\n6 Nuevas MEGABOLAS donde podrás conseguir grandes premios o ventajas durante el juego.\nDos fases de bonus con increíbles premios: el muro de las parejas y la tumba del faraón con los relojes de arena.\nBonus progresivo con ruleta de la suerte.",
                        "en": "Delve deeper into the Mysteries of Egypt and discover the tomb of the Pharaoh by obtaining spectacular prizes.\nRealistic graphics.\n4 cards with 15 different prize patterns.\n12 extra balls with a wild ball and extra free ball.\n6 New MEGABALLS where you can obtain great prizes or advantages during the game.\nTwo bonus phases with incredible prizes: the wall of pairs and the Pharaoh´s tomb with sand clocks.\nProgressive bonus with wheel of fortune."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_egypt/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_egypt/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_egypt/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_egypt/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_egypt/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_egypt/logo_pt.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_rio",
                    "gameName": {
                        "es": "Carnaval en Rio",
                        "en": "Carnival in Rio",
                        "de": "Karneval in Rio",
                        "fr": "Carnaval a Rio",
                        "it": "Carnevale a Rio",
                        "pt": "Carnaval no Rio"
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "No podrás parar de bailar en el carnaval. Déjate llevar en la fiesta de Rio.\nAnimaciones y premios especiales.\nBola extra comodín. Además de las bolas extra, consigue una bola comodín que podrás sustituir por el número que más te interese para aumentar tus premios.\n12 cartones de 3 líneas por 5 columnas.\n8 patrones de premio diferentes.",
                        "en": "You will be unable to stop dancing at the carnival. Let yourself get carried away at the Rio party.\nAnimations and special prizes.\nExtra Wild ball. In addition to the extra balls, obtain a Wild ball that you can replace with the number that is of most interest to you in order to increase your prizes.\n12 cards of 3 lines and 5 columns.\n8 different prize patterns."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_rio/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_rio/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_rio/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_rio/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_rio/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_rio/logo_pt.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_circus",
                    "gameName": {
                        "es": "El parque de Zoltan",
                        "en": "The park of Zoltan",
                        "de": "Der park von Zoltan",
                        "fr": "Parc de Zoltan",
                        "it": "Parco di Zoltan",
                        "pt": "O parque de Zoltan"
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "¡Vive un carrusel de sensaciones en el Parque de Zoltan! Afina tu puntería para elegir el patito que te ayudará a conseguir premio.\nAnimaciones y premios especiales.\nBola extra comodín. Además de las bolas extra, consigue una bola comodín que podrás sustituir por el número que más te interese para aumentar tus premios.\n4 cartones de 5 líneas por 5 columnas.\n15 patrones de premio diferentes.",
                        "en": "Enjoy a merry-go-round of sensations at Zoltan Park! Fine tune your aim in order to pick out the duckling that will help you win a prize.\nAnimations and special prizes.\nExtra Wild ball. In addition to the extra balls, obtain a Wild ball that you can replace with the number that is of most interest to you in order to increase your prizes.\n4 cards of 5 lines and 5 columns.\n15 different prize patterns."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_circus/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_circus/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_circus/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_circus/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_circus/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_circus/logo_pt.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_atlantis",
                    "gameName": {
                        "es": "El oro de Poseidón",
                        "en": "The gold of Poseidon",
                        "de": "Gold des Poseidon",
                        "fr": "L\'or de Poseidon",
                        "it": "L\'oro di Poseidon",
                        "pt": "O ouro de Poseidon"
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "Los tesoros del mundo submarino están por descubrir.\nAnimaciones y premios especiales.\nBola extra comodín. Además de las bolas extra, consigue una bola comodín que podrás sustituir por el número que más te interese para aumentar tus premios.\n4 cartones de 3 líneas por 5 columnas.\n8 patrones de premio diferentes.",
                        "en": "The treasures of the underwater world are waiting to be discovered.\nAnimations and special prizes.\nExtra Wild ball. In addition to the extra balls, obtain a Wild ball that you can replace with the number that is of most interest to you in order to increase your prizes.\n4 cards of 3 lines and 5 columns.\n8 different prize patterns."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_atlantis/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_atlantis/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_atlantis/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_atlantis/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_atlantis/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_atlantis/logo_pt.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_hauntedhouse",
                    "gameName": {
                        "es": "Casa Encantada",
                        "en": "Haunted House",
                        "de": "Geisterhaus",
                        "fr": "Maizon Hantée",
                        "it": "Casa Stregata",
                        "pt": "Casa Mal Assombrada"
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "No dejes que las paredes de Casa Encantada te aterroricen. Entre las telarañas y los fantasmas, hay miles de monedas ocultas que esperan un ganador. ¡Juega hasta conseguir llegar a la fase de bonus!\nAnimaciones y premios especiales.\nBola extra comodín. Además de las bolas extra, consigue una bola comodín que podrás sustituir por el número que más te interese para aumentar tus premios.\nFase de bonus. Entra en la sala de los retratos, donde te esperan grandes premios.\n4 cartones de 3 líneas por 5 columnas.\n16 patrones de premio diferentes",
                        "en": "Do not let yourself get scared by the walls of the Haunted House. Among the spider webs and ghosts, there are thousands of hidden coins that are awaiting a winner. Play until you reach the bonus phase!\nAnimations and special prizes.\nExtra Wild ball. In addition to the extra balls, obtain a Wild ball that you can replace with the number that is of most interest to you in order to increase your prizes.\nBonus phase. Enter the portrait room, where great prizes await.\n4 cards of 3 lines and 5 columns.\n16 different prize patterns."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_hauntedhouse/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_hauntedhouse/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_hauntedhouse/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_hauntedhouse/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_hauntedhouse/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_hauntedhouse/logo_pt.png"
                    },
                    "html": false,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vb_planet",
                    "gameName": {
                        "es": "Planeta 67",
                        "en": "Planet 67",
                        "de": "Planet 67",
                        "fr": "Planète 67",
                        "it": "Pianeta 67",
                        "pt": "Planeta 67"
                    },
                    "gameType": "vb",
                    "description": {
                        "es": "Los habitantes de Planet 67 te están esperando con miles de premios.\nAnimaciones y premios especiales.\n4 cartones de 3 líneas por 5 columnas.\n19 patrones de premio diferentes.\nBola extra comodín. Además de las bolas extra, consigue una bola comodín que podrás sustituir por el número que más te interese para aumentar tus premios.",
                        "en": "The inhabitants of Planet 67 await you with thousands of prizes.\nAnimations and special prizes.\n4 cards of 3 lines and 5 columns.\n19 different prize patterns.\nExtra Wild ball. In addition to the extra balls, obtain a Wild ball that you can replace with the number that is of most interest to you in order to increase your prizes."
                    },
                    "imageUrl": {
                        "de": "https://static2.redrakegaming.com/cdn/img/promo/vb_planet/logo_de.png",
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vb_planet/logo_en.png",
                        "es": "https://static2.redrakegaming.com/cdn/img/promo/vb_planet/logo_es.png",
                        "fr": "https://static2.redrakegaming.com/cdn/img/promo/vb_planet/logo_fr.png",
                        "it": "https://static2.redrakegaming.com/cdn/img/promo/vb_planet/logo_it.png",
                        "pt": "https://static2.redrakegaming.com/cdn/img/promo/vb_planet/logo_pt.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_triplebonus",
                    "gameName": {
                        "en": "TRIPLE BONUS POKER",
                        "es": "TRIPLE BONUS POKER"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "As the name suggests, obtain a Four of a Kind and you will triple the value of your prize! You will also obtain prizes with any hand that beats a pair of Ks.",
                        "es": "¡Como dice su nombre, obtén un Poker y triplicarás el valor de su premio! También lograrás premios con cualquier jugada que supere una pareja de Ks."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_triplebonus/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_seqroyal",
                    "gameName": {
                        "en": "SEQUENTIAL ROYAL",
                        "es": "SEQUENTIAL ROYAL"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "The format where the biggest winning hand is the royal flush. Try to obtain it in any way in order to win one of the biggest prizes in all the formats of Video Poker! In any case, any hand higher than a pair of Js will obtain a prize.",
                        "es": "Modalidad donde la jugada más bonificada es la escalera real. ¡Trata de obtenerla en cualquier mano para llevarte uno de los mayores premios de todas las modalidades del Video Poker! De todos modos, cualquier jugada superior a una pareja de Js obtendría premio."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_seqroyal/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_royalcourt",
                    "gameName": {
                        "en": "ROYAL COURT",
                        "es": "ROYAL COURT"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "With any hand equal to or higher than a pair of Js, you will have a prize. But if you want to get something bigger, obtain a Four of a Kind of Js, Qs or Ks in order to take one of the top prizes.",
                        "es": "Con cualquier jugada igual o superior a una pareja de Js, tendrás premio. Pero si quieres llevarte algo grande, logra un Poker de Js, de Qs o de Ks para alzarte con los premios máximos."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_royalcourt/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_fiveaces",
                    "gameName": {
                        "en": "FIVE ACES",
                        "es": "FIVE ACES"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "A curious form of Video Poker where you will have an extra card, an Ace without a suit. Use it to form hands that do not need a specific suit and win your prize!",
                        "es": "Extraña modalidad del Video Poker donde tendrás una carta extra, un As sin palo. ¡Utilízalo para formar jugadas que no necesiten de un palo en concreto y hazte con tu premio!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_fiveaces/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_facesdeuces",
                    "gameName": {
                        "en": "FACES AND DEUCES",
                        "es": "FACES AND DEUCES"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "An entertaining variety of Video Poker where you will have to seek the Four of a Kind of twos plus a J, Q or K card in order to win the biggest prizes. In this case, any hand equal to or higher than a pair of Ks will have a prize.",
                        "es": "Entretenida variante del Video Poker donde tendrás que buscar el Poker de doses más la carta J, Q o K para llevarte los mayores premios. En este caso, cualquier jugada igual o superior a una pareja de Ks tendrá premio."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_facesdeuces/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_doubleacesfaces",
                    "gameName": {
                        "en": "DOUBLE ACES AND FACES",
                        "es": "DOUBLE ACES AND FACES"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "Double your prizes! Obtain a “Four of a Kind of Aces, Js, Qs and Ks”, and you will double the prize of the “Aces and Faces”! Attention, with a single pair of cards you will already have a prize!",
                        "es": "¡Duplica tus premios! ¡Consigue “Poker de As, Js, Qs y Ks”, y lograrás el doble de premio que en la modalidad “Aces and Faces”! ¡Atención, con una simple pareja de cartas ya tendrás premio!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_doubleacesfaces/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_bonusdeuces",
                    "gameName": {
                        "en": "BONUS DEUCES WILD",
                        "es": "BONUS DEUCES WILD"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "If Four of a Kind doesn´t mean much to you, in this new format you can go for a five of a kind. Use the twos as wildcards and obtain one of the biggest prizes in the game!",
                        "es": "Si el Poker te sabe a poco, en esta nueva modalidad podrás ir a por el Repoker. ¡Utiliza los doses como comodines y hazte con uno de los mayores premios del juego!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_bonusdeuces/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_aceeights",
                    "gameName": {
                        "en": "ACES & EIGHTS",
                        "es": "ACES & EIGHTS"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "As the name suggests, the hands with a “Four of a Kind of Aces, 7s or 8s” will be awarded. However, with a single pair of Js or higher you will obtain a prize.",
                        "es": "Como dice su nombre, se verán bonificadas las manos con un “Poker de As, de 7s o de 8s”. Aunque con una simple pareja de Js o superior también obtendrás premio."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_aceeights/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_acedeucebonus",
                    "gameName": {
                        "en": "ACES & DEUCES BONUS POKER",
                        "es": "ACES & DEUCES BONUS POKER"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "Try to obtain the “Four of a Kind of Aces and/or 2s” in order to win the best prizes. However, in this variety, any hand equal to or higher than a pair of Js will give you winnings.",
                        "es": "Busca conseguir el “Poker de As y/o 2s” para llevarte los mejores premios. Aunque en esta variedad, cualquier mano igual o superior a una pareja de Js te dará ganancias."
                    },
                    "imageUrl": {
                        "en": "http://cdn.redrakegaming.com/img/promo/videopoker/aces_deuces_bonus_poker_BIG.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_tensbetter",
                    "gameName": {
                        "en": "TENS OR BETTER",
                        "es": "TENS OR BETTER"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "The easiest variety of Jacks or Better. You will only need a pair of 10s to get a prize!",
                        "es": "La variedad más sencilla de Jacks or Better. ¡Tan solo necesitarás una pareja de 10s para tener premio!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_tensbetter/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_deucesjokers",
                    "gameName": {
                        "en": "DEUCES & JOKERS",
                        "es": "DEUCES & JOKERS"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "You will have 5 wild cards. Both the Joker and the 4 deuces will act as a wild card in a modality where any play equal to or higher than a Three of a Kind will have a prize.",
                        "es": "Dispondrás de 5 cartas comodín. Tanto el Joker como los 4 doses harán la función de comodín en una modalidad donde cualquier jugada igual o superior a un trío tendrá premio."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_deucesjokers/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_ddbonus",
                    "gameName": {
                        "en": "DOUBLE DOUBLE BONUS",
                        "es": "DOUBLE DOUBLE BONUS"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "If you like the “Double bonus”, you will love this variant. This time, the “Four of a Kind of Aces, 2s, 3s or 4s” has even greater prizes, provided that the free card is an “Ace, a 2, a 3 or a 4”.",
                        "es": "Si te gusta el “Double bonus”, esta variante te encantará. Ésta vez, el “Poker de As, 2s, 3s o 4s” está aún más bonificado, siempre que la carta que quede suelta sea un “As, un 2, un 3 o un 4”."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_ddbonus/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_doublebonus",
                    "gameName": {
                        "en": "DOUBLE BONUS",
                        "es": "DOUBLE BONUS"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "A format very similar to “Aces and Faces”, but in this case the winning combinations will be the “Four of a Kind of Aces, 2s, 3s or 4s”.",
                        "es": "Modalidad muy semejante a “Aces and Faces”, pero en este caso las combinaciones bonificadas serán el “Poker de As, 2s, 3s o 4s”."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_doublebonus/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_acesfaces",
                    "gameName": {
                        "en": "ACES & FACES",
                        "es": "ACES & FACES"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "An entertaining format where the Four of a Kind hands of Js, Qs, Ks and As have prizes. It will be easy for you to obtain a winning hand, as any combination equal to or higher than a single pair has a prize.",
                        "es": "Entretenida modalidad donde las jugadas Poker de Js, Qs, Ks y As están bonificadas. Tendrás muy fácil obtener una mano ganadora, pues cualquier combinación igual o superior a una simple pareja tiene premio."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_acesfaces/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_numberbonus",
                    "gameName": {
                        "en": "NUMBER BONUS",
                        "es": "NUMBER BONUS"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "There will be a bonus number each day. Try to create combinations with it in order to obtain big prizes!",
                        "es": "Cada día habrá un número bonificado. ¡Intenta hacer combinaciones con él para lograr grandes premios!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_numberbonus/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_allamerican",
                    "gameName": {
                        "en": "ALL AMERICAN",
                        "es": "ALL AMERICAN"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "A format very similar to “Jacks or Better” where you have to look for the best combinations of a “Colour Straight”, “Straight” and “Colour” in order to win the best prizes. On the other hand, the “Full House” and “Two Pair” hands will somewhat lose their value.",
                        "es": "Modalidad muy parecida a “Jacks or better” donde deberás buscar las combinaciones de “Escalera de color”, “Escalera” y “Color” para llevarte los mejores premios. Por otro lado, las jugadas de “Full” y “Doble Pareja” perderían algo su valor."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_allamerican/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_jokerpoker",
                    "gameName": {
                        "en": "JOKER POKER",
                        "es": "JOKER POKER"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "In this format, you have the help of a wildcard, the Joker. It will thus be easier to obtain combinations.",
                        "es": "En esta modalidad contamos con la ayuda de una carta comodín, el Joker. De este modo conseguir combinaciones nos resultará más sencillo."
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_jokerpoker/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_bonuspoker",
                    "gameName": {
                        "en": "BONUS POKER",
                        "es": "BONUS POKER"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "New variation of “Jacks or Better” where the prize of the Four of a Kind is increased. Obtain that hand in order to win the biggest prizes!",
                        "es": "Nueva variación de “Jacks o Better” donde se incrementaría el premio del Poker. ¡Consigue esa mano para llevarte los mayores premios!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_bonuspoker/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_deuceswild",
                    "gameName": {
                        "en": "DEUCES WILD",
                        "es": "DEUCES WILD"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "In this case, it is the twos that act as wildcards, increasing the chance to obtain a good hand. Try to obtain hands equal to or higher than a Three of a Kind with the help of the 4 twos and obtain your prize!",
                        "es": "En este caso, son los doses los que actúan como comodines, incrementando las posibilidades de lograr una buena mano. Intenta conseguir jugadas igual o superiores a un trío con la ayuda de los 4 doses y ¡consigue tu premio!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_deuceswild/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            },
            {
                "game": {
                    "gameId": "doradobet-vp_jacksbetter",
                    "gameName": {
                        "en": "JACKS OR BETTER",
                        "es": "JACKS OR BETTER"
                    },
                    "gameType": "vp",
                    "description": {
                        "en": "It uses the same rules as Four of a Kind, but will only award any hand that is the same as or higher than a pair of Js. The perfect format for getting an introduction to the game of Video Poker!",
                        "es": "Usa las mismas reglas que el Poker, pero solo premiará cualquier jugada que sea igual o superior a una pareja de Js. ¡La modalidad perfecta para iniciarse en el juego del Video Poker!"
                    },
                    "imageUrl": {
                        "en": "https://static2.redrakegaming.com/cdn/img/promo/vp_jacksbetter/logo_en.png"
                    },
                    "html": true,
                    "frb": false,
                    "frbRoundMinValue": null
                }
            }
        ]
    }
}';
$data = json_decode($data);

exit();

$Proveedor = new Proveedor("", "REDRAKE");

foreach ($data->data->games as $datum) {
    $gameId = $datum->game->gameId;
    $image = $datum->game->imageUrl->en;
    $name = str_replace("'", "", str_replace("/'", "", $datum->game->gameName->es));

    $url = $datum->game->imageUrl->en;
    $img = '/home/backend/images/productos/RR_' . str_replace(" ", "_", str_replace("'", "", str_replace("/'", "", $name))) . '.png';
    file_put_contents($img, file_get_contents($url));

    $ImageUrl = 'https://images.virtualsoft.tech/productos/RR_' . str_replace(" ", "_", str_replace("'", "", str_replace("/'", "", $name))) . '.png';

    $ProductoMySqlDAO = new ProductoMySqlDAO();

    $Producto = new Producto();

    $Producto->setDescripcion($name);
    $Producto->setProveedorId($Proveedor->getProveedorId());
    $Producto->setEstado('A');
    $Producto->setImageUrl($ImageUrl);
    $Producto->setExternoId($gameId);
    $Producto->setVerifica('I');
    $Producto->setUsucreaId(0);
    $Producto->setUsumodifId(0);

    $Producto->setMobile('S');
    $Producto->setDesktop('S');
    $Producto->setMostrar('S');

    $Producto->setOrden(100000);


    $ProductoMySqlDAO = new ProductoMySqlDAO();

    $ProductoId = $ProductoMySqlDAO->insert($Producto);


    $ProductoMandante = new ProductoMandante();

    $ProductoMandante->mandante = '0';
    $ProductoMandante->productoId = $ProductoId;

    $ProductoMandante->estado = 'A';

    $ProductoMandante->verifica = 'I';

    $ProductoMandante->filtroPais = 'I';

    $ProductoMandante->max = 10000;

    $ProductoMandante->min = 0;

    $ProductoMandante->detalle = '';

    $ProductoMandante->orden = 10000000;

    $ProductoMandante->numFila = 1;
    $ProductoMandante->numColumna = 1;

    $ProductoMandante->ordenDestacado = 10000;

    $ProductoMandante->usucreaId = 0;
    $ProductoMandante->usumodifId = 0;

    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($ProductoMySqlDAO->getTransaction());

    $ProductoMandanteMySqlDAO->insert($ProductoMandante);


    $CategoriaProducto = new CategoriaProducto();


    $CategoriaProducto->setCategoriaId('4');
    $CategoriaProducto->setProductoId($ProductoId);

    $CategoriaProducto->setUsucreaId(0);
    $CategoriaProducto->setUsumodifId(0);

    $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO($ProductoMySqlDAO->getTransaction());
    $CategoriaProductoMySqlDAO->insert($CategoriaProducto);


    $ProductoMySqlDAO->getTransaction()->commit();
}
