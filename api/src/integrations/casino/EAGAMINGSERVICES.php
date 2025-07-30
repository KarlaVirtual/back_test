<?php

/**
 * Clase principal para la integración con los servicios de EAGAMING.
 *
 * Este archivo contiene la implementación de la clase `EAGAMINGSERVICES` y su clase auxiliar `EAGAMINGUSER`.
 * Proporciona métodos para interactuar con la API de EAGAMING, incluyendo la gestión de jugadores,
 * obtención de listas de juegos, creación de sesiones y más.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use \CurlWrapper;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase para manejar la integración con los servicios de EAGAMING.
 */
class EAGAMINGSERVICES
{
    /**
     * Credenciales de inicio de sesión para el entorno de desarrollo.
     *
     * @var string
     */

    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase EAGAMINGSERVICES.
     *
     * Inicializa las propiedades del usuario y configura el proveedor y el entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Obtiene y prepara el juego solicitado para el usuario.
     *
     * Este método verifica o genera un token de sesión para el usuario, 
     * valida credenciales del proveedor EAGAMING, y se comunica con la API del subproveedor
     * para obtener o crear la identidad del jugador, devolviendo la configuración del juego lista para usarse.
     *
     * @param  string $gameid         ID del juego solicitado.
     * @param  string $lang           Idioma preferido del usuario.
     * @param  bool   $play_for_fun   Indica si el juego será iniciado en modo demo (jugar por diversión).
     * @param  string $usuarioToken   Token de autenticación del
     *                                usuario.
     * @param  string $migameid       ID alternativo del juego para migraciones o referencias cruzadas (Opcional).
     * @param  string $usumandanteId  ID del usuario mandante; si no se proporciona, se obtendrá desde el token (Opcional).
     * 
     * @return string $response       Respuesta del subproveedor TOMHORN que contiene información o URL del juego.
     * 
     * @throws Exception Si ocurre algún error durante el proceso de autenticación o comunicación con el proveedor.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId = "", $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "EAGAMING");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
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
                    $UsuarioToken->setToken($UsuarioToken->createToken());
                    $UsuarioToken->setSaldo(0);
                    $UsuarioToken->setEstado('A');
                    $UsuarioToken->setProductoId($Producto->productoId);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL = $Credentials->URL;
            $API_LOGIN = $Credentials->API_LOGIN;
            $API_PASSWORD = $Credentials->API_PASSWORD;

            $string = "admin?action=create_session&usr=" . $API_LOGIN . "&passw=" . $API_PASSWORD . "&game_id=" . $gameid . "&remote_id=" . $UsuarioMandante->getUsumandanteId();

            $return = $this->EAGAMINGRequest($string, $URL);

            $UsuarioToken->setToken(json_decode($return)->response->token);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();


            if (json_decode($return)->response->game_url != "") {
                $array = array(
                    "error" => false,
                    "response" => json_decode($return)->response->game_url . "&locale=" . $lang,
                );
            } else {
                $array = array(
                    "error" => true,
                    "response" => "",
                );
            }

            return (json_decode(json_encode($array)));

            return $array;
        } catch (Exception $e) {


        }
    }

    /**
     * Realiza una solicitud HTTP POST a una API externa.
     * 
     * Esta función construye una solicitud en formato JSON y la envía mediante un método POST
     * a la API especificada. Los datos enviados incluyen las credenciales de usuario (nombre de usuario
     * y contraseña) junto con los datos adicionales proporcionados en el parámetro `$data`. 
     * Si ocurre un error en la solicitud cURL, se muestra un mensaje de error.
     * 
     * @param array  $data    Datos adicionales que se combinan con las credenciales y se envían en la solicitud.
     * @param string $url     La URL de la API externa a la que se enviará la solicitud.
     * 
     * @return mixed Retorna la respuesta decodificada en formato JSON de la API externa.
     *               Si la solicitud se realiza correctamente, la respuesta será un objeto o array en formato JSON.
     *               En caso de error, se devolverá un error generado por la función cURL.
     */
    public function EAGAMINGRequest($data, $url)
    {
        $curl = new CurlWrapper($url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/vnd.api+json',
            ),
        ));
        $response = $curl->execute();
        return $response;
    }
}
