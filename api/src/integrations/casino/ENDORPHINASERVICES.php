<?php

/**
 * Clase ENDORPHINASERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con la integración de Endorphina.
 * Proporciona métodos para obtener juegos, agregar giros gratis y realizar solicitudes HTTP.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase que proporciona servicios relacionados con la integración de Endorphina.
 * Incluye métodos para gestionar juegos, giros gratis y solicitudes HTTP.
 */
class ENDORPHINASERVICES
{
    /**
     * URL de redirección para los servicios.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Obtiene un juego basado en los parámetros proporcionados.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es modo de prueba.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $migameid      ID del juego en el sistema.
     * @param string  $usumandanteId Opcional ID del usuario mandante.
     *
     * @return object Respuesta con la URL del juego o un error.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $migameid, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }
            $Proveedor = new Proveedor("", "ENPH");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
                $UsuarioToken->setProductoId($Producto->productoId);
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
                    $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
                    $UsuarioToken->setSaldo(0);
                    $UsuarioToken->setProductoId($Producto->productoId);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());
            $Mandante = new Mandante($UsuarioMandante->getMandante());
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());
            $Pais = $UsuarioMandante->paisId;

            $URL = $credentials->URL;
            $HASH = $credentials->HASH;
            $NODE_ID = $credentials->NODE_ID;

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
            }

            $profile = '';
            $ProfileHash = '';
            if ($Pais == 173){
                $profile='&profile=peru.xml';
                $ProfileHash = 'peru.xml';
            }else{
                $profile='&profile=nofullscreen_money.xml';
                $ProfileHash = 'nofullscreen_money.xml';
            }

            $array = array(
                "error" => false,
                "response" => $URL . "nodeId=" . $NODE_ID .$profile. "&exit=" . $this->URLREDIRECTION . "&token=" . $UsuarioToken->getToken() . "&sign=" . hash('sha1', $this->URLREDIRECTION . $NODE_ID . $ProfileHash . $UsuarioToken->getToken() . $HASH),
            );

            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Agrega giros gratis a un usuario.
     *
     * @param integer $bonoId              ID del bono.
     * @param string  $Name                Nombre del bono.
     * @param integer $roundsFree          Cantidad de giros gratis.
     * @param float   $roundvalue          Valor de cada giro.
     * @param string  $StartDate           Fecha de inicio.
     * @param string  $EndDate             Fecha de finalización.
     * @param string  $user                Usuario al que se asignan los giros.
     * @param array   $games               Juegos asociados al bono.
     * @param integer $aditionalIdentifier Identificador adicional del bono.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $Name, $roundsFree, $roundvalue, $StartDate, $EndDate, $user, $games, $aditionalIdentifier)
    {
        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Proveedor = new Proveedor("", "ENPH");
        $Producto = new Producto("", $games[0], $Proveedor->getProveedorId());

        $currency = $Usuario->moneda;
        $player = "Usuario" . $UsuarioMandante->usumandanteId;

        $EndDateFormat = date("Y-m-d\TH:i:s\Z", strtotime($EndDate));

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL_BONUS = $credentials->URL_BONUS;
        $HASH = $credentials->HASH;
        $NODE_ID = $credentials->NODE_ID;

        $game = '';
        foreach ($games as $valor) {
            $game .= $valor;
        }
        
        $aditionalIdentifier = $bonoId . $aditionalIdentifier . $Usuario->usuarioId;
        $name = "BONOID_" . $aditionalIdentifier;

        $gameUrlDecode = urldecode($games[0]);
        $roundvalue = $roundvalue * 100;

        $array = 
            "currency=" . $currency . 
            "&expires=" . $EndDateFormat . 
            "&game=" . $games[0] . 
            "&id=" . $aditionalIdentifier . 
            "&name=" . $name . 
            "&nodeId=" . intval($NODE_ID) . 
            "&player=" . $player . 
            "&spins.amount=" . $roundsFree . 
            "&spins.totalBet=" . $roundvalue . 
            "&sign=" . hash('sha1', $currency . $EndDateFormat . $gameUrlDecode . $aditionalIdentifier . $name . intval($NODE_ID) . $player . $roundsFree . $roundvalue . $HASH);

        $response = $this->Request($array, $URL_BONUS);

        syslog(LOG_WARNING, " ENDORPHINASERVICE DATA: " . $array . " RESPONSE: " . $response);
        $response = json_decode($response);

        if ($response != "" && $response->state != "INIT") {
            $return = array(
                "code" => 1,
                "response_code" => $response->code,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $response->state,
                "response_message" => 'OK',
                "bonusId" => $bonoId
            );
        }
        return $return;
    }

    /**
     * Realiza una solicitud HTTP POST.
     *
     * @param string $array  Datos a enviar en la solicitud.
     * @param string $urlApi URL de la API.
     *
     * @return string Respuesta de la API.
     */
    public function Request($array, $urlApi)
    {
        //InicializarlaclaseCurlWrapper
        $curl = new CurlWrapper($urlApi);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $urlApi,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        // Ejecutar la solicitud
        $response = $curl->execute();

        return $response;
    }

    /**
     * Genera una clave única basada en el jugador.
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
