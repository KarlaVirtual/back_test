<?php

/**
 * Clase MERKURSERVICES
 *
 * Esta clase proporciona servicios relacionados con la integración de juegos de casino
 * para el proveedor MERKUR. Incluye métodos para obtener juegos, agregar giros gratis,
 * enviar solicitudes de giros gratis y generar claves únicas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use \CurlWrapper;
use DateTimeZone;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase MERKURSERVICES
 *
 * Proporciona métodos para la integración con el proveedor de juegos de casino MERKUR.
 * Incluye funcionalidades como obtención de juegos, asignación de giros gratis,
 * envío de solicitudes a APIs externas y generación de claves únicas.
 */
class MERKURSERVICES
{
    /**
     * Constructor de la clase MERKURSERVICES.
     */
    public function __construct()
    {
    }

    /**
     * Obtiene la URL de redirección para un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     *
     * @return object Respuesta con la URL de redirección o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "MERKUR");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId, "", "");

                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->update($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);
                    $UsuarioToken->setProductoId(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

            $Mandante = new Mandante($UsuarioMandante->getMandante());

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $array = array(
                "error" => false,
                "response" => $credentials->LAUNCH_URL . "?casino=" . $credentials->CASINO_ID . "&gameKey=" . $gameid . "&lang=" . $lang . "&gameMode=money&playerId=" . $UsuarioMandante->usumandanteId . "&startToken=" . $UsuarioToken->token
            );
            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Agrega giros gratis a los usuarios para juegos específicos.
     *
     * @param string  $bonoElegido Identificador del bono.
     * @param integer $roundsFree  Cantidad de giros gratis.
     * @param float   $roundsValue Valor de cada giro.
     * @param string  $StartDate   Fecha de inicio de la validez.
     * @param string  $EndDate     Fecha de fin de la validez.
     * @param array   $users       Lista de IDs de usuarios.
     * @param array   $games       Lista de IDs de juegos.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoElegido, $roundsFree, $roundsValue, $StartDate, $EndDate, $users, $games)
    {
        $Usuario = new Usuario($users[0]);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Proveedor = new Proveedor("", "MERKUR");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $players = [];

        foreach ($users as $playerId) {
            $Usuario = new Usuario($playerId);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            $players[] = (string)$UsuarioMandante->usumandanteId;
        }

        $gameKeys = [];

        foreach ($games as $game) {
            $gameKeys[] = [
                'gameKey' => $game,
                'freespinStakeAmount' => $roundsValue,
            ];
        }
 
        $dataFreeSpin = json_encode([
            'casinoCampaignIdentifier' => (string)$bonoElegido,
            'playerIdentifiers' => $players,
            'games' => $gameKeys,
            'validityStartDate' => date('Y-m-d\TH:i:s\Z', strtotime($StartDate)),
            'validityEndDate' => date('Y-m-d\TH:i:s\Z', strtotime($EndDate)),
            'freespinQuantity' => intval($roundsFree),
            'freespinStakeCurrency' => $UsuarioMandante->moneda,
        ]);

        
        $operatorID = $credentials->USERNAME;
        $operatorPW = $credentials->PASSWORD;
        
        $response = $this->SendFreespins($credentials->BONUS_URL . $credentials->CASINO_ID . '/campaigns', $dataFreeSpin, $operatorID, $operatorPW);
        
        syslog(LOG_WARNING, "MERKUR BONO DATA: " . $dataFreeSpin . " RESPONSE: " . $response);

        $response = json_decode($response);
        $responseCode = http_response_code();

        if ($responseCode != '200') {
            $return = array(
                "code" => 1,
                "response_code" => 0,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response,
                "response_message" => 'OK'
            );
        }
        return $return;
    }

    /**
     * Envía una solicitud para agregar giros gratis a través de una API externa.
     *
     * @param string $url          URL de la API.
     * @param string $dataFreeSpin Datos de la solicitud en formato JSON.
     * @param string $operatorID   ID del operador.
     * @param string $operatorPW   Contraseña del operador.
     *
     * @return string Respuesta de la API.
     */
    public function SendFreespins($url, $dataFreeSpin, $operatorID, $operatorPW)
    {
        $curl = new CurlWrapper($url);
        $authToken = base64_encode($operatorID . ":" . $operatorPW);

        $curl->setOptionsArray([
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $dataFreeSpin,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . $authToken,
                'Content-Type: application/json',
            ],
        ]);

        $response = $curl->execute();
        return $response;
    }

    /**
     * Genera una clave única basada en el identificador del jugador.
     *
     * @param string $player Identificador del jugador.
     *
     * @return string Clave generada.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }
}
