<?php

use Backend\dto\Pais;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;

/**
 * Este script maneja la lógica para mostrar y gestionar los minijuegos disponibles.
 * 
 * @param array $_REQUEST['DXbDpfykzqwS'] Parámetro para habilitar el modo de depuración.
 * @param string $_GET['token'] Token que contiene información del usuario y proveedor.
 * 
 * @return array $data Contiene los datos de los minijuegos disponibles o un array vacío en caso de error.
 */

ini_set('display_errors', 'OFF');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');


if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

require(__DIR__ . DIRECTORY_SEPARATOR . '../../../vendor/autoload.php');

try {
    $token = $_GET['token'];
    $element = explode("_", $token);
    $token = $element[0];
    $provider = $element[1];

    if (empty($token) || empty($provider)) throw new Exception('Params error', 22);

    $category = 'MINIGAMES';
    $UsuarioToken = new UsuarioToken($token, '0');
    $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
    $Pais = new Pais($UsuarioMandante->getPaisId());
    $country = $UsuarioMandante->getPaisId();
    $Proveedor = new Proveedor('', $provider);

    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $URL = $ConfigurationEnvironment->isDevelopment() ? 'https://apidev.virtualsoft.tech/casino/game/play/' : 'https://casino.virtualsoft.tech/game/play/';

    $rules = [];

    array_push($rules, ['field' => 'producto.proveedor_id', 'data' => $Proveedor->getProveedorId(), 'op' => 'eq']);
    array_push($rules, ['field' => 'producto.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_producto.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_producto.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_mandante.pais_id', 'data' => $country, 'eq' => 'op']);
    array_push($rules, ['field' => 'categoria_mandante.tipo', 'data' => $category, 'op' => 'eq']);
    array_push($rules, ['field' => 'producto_mandante.pais_id', 'data' => $country, 'op' => 'eq']);
    array_push($rules, ['field' => 'producto_mandante.estado', 'data' => 'A', 'op' => 'eq']);

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $CategoriaProducto = new CategoriaProducto();
    $query = $CategoriaProducto->getCategoriaProductosMandanteCustom('producto.*, producto_mandante.prodmandante_id', 'categoria_producto.orden', 'asc', 0, 100000, $filter, true);

    $query = json_decode($query, true);
    $data = $query['data'];
} catch (Exception $ex) {
    $data = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Minigames</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 100%;
            height: 100vh;
            overflow: hidden;
            font-family: sans-serif;
        }

        .container {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            display: flex;
            justify-content: center;
        }

        .card-container {
            width: 90%;
            max-width: 500px;
            padding: 10px;
            display: flex;
            align-content: flex-start;
            flex-wrap: wrap;
            gap: 10px;
            position: relative;
        }

        .card-game {
            max-width: 150px;
            width: calc(50% - 5px);
            min-height: 80px;
            height: 15%;
            border-radius: 8px;
            overflow: hidden;
            background: url("https://images.virtualsoft.tech/m/msjT1617902779.png");
            background-position: center;
            background-size: contain;
            position: relative;
        }

        .card-game img {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            object-fit: cover;
        }

        .info-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            text-align: center;
            transition: all 0.4s ease;
            opacity: 0;
        }

        .info-container:hover {
            opacity: 1;
        }

        .info-container h2 {
            color: #ffffff;
            font-size: 0.6em;
            margin-top: 8px;
        }

        .info-container button {
            width: 80%;
            text-transform: uppercase;
            padding: 8px;
            background: linear-gradient(180deg, #00ff14, #006208);
            border: none;
            outline: none;
            border-radius: 5px;
            color: #ffffff;
            font-weight: bold;
            font-size: 0.5em;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 50%;
            transform: translateX(-50%);
        }

        .info-container button:hover {
            cursor: pointer;
        }

        .game-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background: url("https://images.virtualsoft.tech/site/doradobet/fondo-casino2.png?v=1") 50% 0px repeat;
            background-size: cover;
            display: none;
        }

        .enable {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .game-content {
            width: 90%;
            height: 95%;
            background: #0c1019;
            margin-bottom: 15px;
        }

        .game-content {
            width: 100%;
            height: 100%;
            background: #242637;
        }

        .game-header {
            width: 100%;
            height: 48px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .close-icon {
            width: 25px;
            height: 25px;
            position: relative;
            margin-right: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .close-icon:hover {
            cursor: pointer;
        }

        .close-icon::before {
            content: "";
            width: 7px;
            height: 23px;
            position: absolute;
            background: #ffffff;
            transform: rotate(45deg);
            border-radius: 2px;
            pointer-events: none;
        }

        .close-icon::after {
            content: "";
            width: 7px;
            height: 23px;
            position: absolute;
            background: #ffffff;
            transform: rotate(-45deg);
            border-radius: 2px;
            pointer-events: none;
        }

        .game-body {
            width: 100%;
            height: calc(100% - 48px);
            padding: 0 10px 10px 10px;
        }

        .not-found {
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
        }

        .not-found h1 {
            font-size: 3em;
            color: #888888;
            font-weight: 100;
        }

        .not-found p {
            padding: 2px;
            font-size: 2em;
            position: relative;
            line-height: -10px;
            color: #888888;
        }

        .not-found p::after {
            content: "";
            width: 2px;
            height: 100%;
            background: #cacaca;
            position: absolute;
            right: -20px;
        }

        @media screen and (max-width: 500px) {
            .game-header {
                height: 28px;
            }

            .close-icon {
                width: 15px;
                height: 15px;
                margin-right: 10px;
            }

            .close-icon::before {
                width: 3px;
                height: 12px;
            }

            .close-icon::after {
                width: 3px;
                height: 12px;
            }

            .game-body {
                width: 100%;
                height: 100%;
                padding: 0;
            }

            .not-found {
                height: 200px;
                flex-direction: column;
            }

            .not-found p {
                line-height: 0;
            }
        }

    </style>
</head>
<body>
<?php if (oldCount($query['data']) === 0) { ?>
    <div class="not-found">
        <p>Minigames not found</p>
        <h1>404</h1>
    </div>
<?php } else { ?>
    <section class="container">
        <div class="card-container">
            <?php foreach ($data as $key => $value) { ?>
                <div class="card-game">
                    <img src="<?= $value['producto.image_url'] ?>" alt="">
                    <div class="info-container">
                        <h2><?= $value['producto.descripcion'] ?></h2>
                        <button
                                id="btn-game"
                                data-gameid="<?= $value['producto_mandante.prodmandante_id'] ?>"
                                data-mode="real"
                                data-provider="<?= $provider ?>"
                                data-lan="<?= strtolower($Pais->idioma) ?>"
                                data-partnerid="<?= $UsuarioMandante->mandante ?>"
                                data-token="<?= $UsuarioToken->token ?>"
                                data-balance="0"
                                data-currency="<?= $Pais->moneda ?>"
                                data-userid="<?= $UsuarioMandante->usuarioMandante ?>"
                                data-ismobile="false"
                                data-url="<?= $URL ?>"
                        >juegue ahora
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <section class="game-container" id="game">
        <div class="game-content">
            <div class="game-header">
                <i class="close-icon" id="btn-close"></i>
            </div>
            <div class="game-body"></div>
        </div>
    </section>
<?php } ?>
<script>
    const game = document.getElementById('game');
    const btnGame = document.querySelectorAll('#btn-game');
    const btnClose = document.getElementById('btn-close');

    const enabledGame = (frame) => {
        const gameBody = game.querySelector('.game-body');

        game.classList.add('enable');
        gameBody.insertAdjacentHTML('afterbegin', frame);
    }

    const disabledGame = () => {
        const gameBody = game.querySelector('.game-body');

        game.classList.remove('enable');
        gameBody.removeChild(game.querySelector('iframe'));
    }

    document.addEventListener('DOMContentLoaded', () => {
        btnGame.forEach(item => {
            item.addEventListener('click', async ({target}) => {
                const {
                    gameid,
                    mode,
                    provider,
                    lan,
                    partnerid,
                    token,
                    balence,
                    currency,
                    userid,
                    ismobile,
                    url
                } = target.dataset;
                const URL = `${url}?gameid=${gameid}&mode=${mode}&provider=${provider}&lan=${lan}&partnerid=${partnerid}&token=${token}&balance=${balence}&currency=${currency}&userid=${userid}&isMobile=${ismobile}&miniGame=false&minimode=1`;

                const http = await fetch(URL);
                const response = await http.text();

                enabledGame(response);
            });
        });

        btnClose.addEventListener('click', () => {
            disabledGame();
        });
    });
</script>
</body>
</html>