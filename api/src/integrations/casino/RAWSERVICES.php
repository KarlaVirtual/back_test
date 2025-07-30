<?php

namespace Backend\integrations\casino;

use DateTime;
use Throwable;
use DateTimeZone;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\genericTransactional\models\DataConvert;

/**
 * Clase de configuración y parametrización de los servicios de RAW, es responsable de obtener:
 *          - URL del juego (lanzamiento).
 *          - Configurar el request de freeSpin con el formato y estructura correctas para el proveedor.
 *          - Ejecutar la petición para crear el freeSpin.
 *          - Validar la respuesta de freeSpin y retornar el resultado.
 */
class RAWSERVICES {

    /**
     * Nombre del proveedor
     * @var string
     */
    private static $supplierName = "RAW";

    /**
     * URL de redirección al lobby del casino
     * @var string
     */
    private string $urlRedirection = "";

    /**
     * Construye la URL del juego para el proveedor RAW, se encarga de crear el token del usuario y de obtener la URL de redirección al lobby del casino.
     * @param string $externoGameId Es el id del juego dado por el proveedor.
     * @param string $language Es el idioma en el que se mostrará el juego.
     * @param bool $isPlayForFun Indica si el juego es en modo demo o real.
     * @param string $usuarioToken Es el token del usuario que se está autenticando.
     * @param bool $isMobile Indica si el juego se está ejecutando en un dispositivo móvil o en un escriotorio.
     * @param string $usuarioMandanteId Es el id del usuario asociado al proveedor.
     * 
     * @return object Es la estructura de respuesta satisfactoria o de error:
     *          - error: booleano que indica si hubo un error o no.
     *          - response: string que contiene la URL del juego o el mensaje de error.
     * @throws Exception Si ocurre algún error durante el proceso de construcción de la URL o al buscar información en la base de datos.
     */
    public function getGame($externoGameId, $language, $isPlayForFun, $usuarioToken, $isMobile, $usuarioMandanteId) :object {
        try {
            $proveedor = new Proveedor("", self::$supplierName);
            $producto = new Producto("", $externoGameId, $proveedor->proveedorId);

            if (empty($usuarioMandanteId)) {
                $usuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usuarioMandanteId = $usuarioTokenSite->getUsuarioId();
            }

            try {
                $usuarioToken = new Usuariotoken("", $proveedor->getProveedorId(), $usuarioMandanteId);
                $usuarioToken->setToken($usuarioToken->createToken());
                $usuarioToken->setProveedorId($proveedor->getProveedorId());
                $usuarioToken->setProductoId($producto->productoId);

                $usuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $usuarioTokenMySqlDAO->update($usuarioToken);
                $usuarioTokenMySqlDAO->getTransaction()->commit();

            } catch (Throwable $th) {
                if ($th->getCode() != 21) throw $th;
                
                // Crea el token en caso de que no exista en la base de datos.
                $usuarioToken = new UsuarioToken();
                $usuarioToken->setProveedorId($proveedor->getProveedorId());
                $usuarioToken->setCookie('0');
                $usuarioToken->setRequestId('0');
                $usuarioToken->setUsucreaId(0);
                $usuarioToken->setUsumodifId(0);
                $usuarioToken->setUsuarioId($usuarioMandanteId);
                $usuarioToken->setToken($usuarioToken->createToken());
                $usuarioToken->setSaldo(0);
                $usuarioToken->setProductoId($producto->productoId);

                $usuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $usuarioTokenMySqlDAO->insert($usuarioToken);
                $usuarioTokenMySqlDAO->getTransaction()->commit();
            }

            $usuarioMandante = new UsuarioMandante($usuarioToken->usuarioId);
            $mandante = new Mandante($usuarioMandante->mandante);

            if (!empty($mandante->baseUrl)) $this->urlRedirection = $mandante->baseUrl . "new-casino";

            $subProveedorMandantePais = new SubproveedorMandantePais('', $producto->subproveedorId, $usuarioMandante->mandante, $usuarioMandante->paisId);
            $credentials = DataConvert::fromJsonToObject($subProveedorMandantePais->getCredentials());

            $currentDate = date("Y-m-d H:i:s");
            $lastModifiedDate = $usuarioToken->fechaModif;
            $pais = new Pais($usuarioMandante->paisId);

            $gameUrl = $credentials->URL . "launcher?" .
                       'siteId=' . $credentials->SITE_ID . 
                       '&gameId=' . $externoGameId .
                       '&gameMode=' . ($isPlayForFun == true ? "FUN" : "REAL") .
                       '&userId=' . $usuarioToken->usuarioId .
                       '&currency=' . $usuarioMandante->moneda . 
                       '&locale=' . $language . 
                       '&channel=' . ($isMobile == true ? "mobile" : "desktop") .
                       '&lobbyURL=' . $this->urlRedirection .
                       '&token=' . $usuarioToken->token .
                       '&rm=' . $pais->iso . 
                       '&rcPeriod=' . $credentials->RC_PERIOD .
                       '&rcElapsedSeconds=' . $currentDate - $lastModifiedDate;

            return DataConvert::fromAssociativeArrayToObject(array("error" => false, "response" => $gameUrl));
            
        } catch (Throwable $th) {
            return DataConvert::fromAssociativeArrayToObject(array("error" => true, "response" => $th->getMessage()));
        }
    }

    /**
     * Extrae y configura los datos necesarios para la creación del freeSpin
     * 
     * @param string     $bonoId              id único del bono que se está otorgando
     * @param int|string $roundsFree          cantidad de rondas otorgadas en el freeSpin
     * @param int|string $roundValue          valor por cada ronda gratis
     * @param string     $startDate           fecha de inicio de la campaña del freeSpin
     * @param string     $endDate             Fecha de fin de la campaña del freeSpin
     * @param string     $userId              id del usuario al que se le asigna el freeSpin
     * @param array      $games               contiene el id de los juegos que hacen parte de la campaña de freeSpin
     * @param string     $aditionalIdentifier Identificador adicional del freeSpin.
     * 
     * @return void
     * @throws Exception Si ocurre algun error consultando los datos.
     */
    public function createBonusFreeSpin($bonoId, $roundsFree, $roundsValue, $startDate, $endDate, $userId, $games, $aditionalIdentifier){
        try {
            $proveedor = new Proveedor('', self::$supplierName);
            $producto = new Producto('',$games[0], $proveedor->getProveedorId());

            $date = new DateTime($startDate, new DateTimeZone('America/Bogota'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $startDateUTC = $date->format('Y-m-d H:i:s');

            $date = new DateTime($endDate, new DateTimeZone('America/Bogota'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $endDateUTC = $date->format('Y-m-d H:i:s');

            $usuario = new Usuario($userId);    
            $usuarioMandante = new UsuarioMandante('', $usuario->usuarioId, $usuario->mandante);

            $subProveedorMandantePais = new SubproveedorMandantePais('', $producto->subproveedorId, $usuarioMandante->mandante, $usuarioMandante->paisId);
            $credentials = DataConvert::fromJsonToObject($subProveedorMandantePais->getCredentials());

            $urlBonus = $credentials->URL . "free-spins";
            $userName = $credentials->USERNAME;
            $password = $credentials->PASSWORD;
            $siteId = $credentials->SITE_ID;

            $configurationEnvironment = new ConfigurationEnvironment();
            $isPlayForFun = $configurationEnvironment->isDevelopment() == true ? "FUN" : "REAL";

            $bodyToCreateFreeSpin = DataConvert::toJson(array(
                "promoCode" => $bonoId . "_" . $aditionalIdentifier . $usuario->usuarioId,
                "gameMode" => $isPlayForFun,
                "totalPlays" => $roundsFree,
                "amount" => $roundsValue,
                "currency" => $usuarioMandante->moneda,
                "startDate" => $startDateUTC,
                "endDate" => $endDateUTC,
                "siteId" => $siteId,
                "gameCode" => $games[0],
                "extraInfo" => "",
                "termsCons" => "",
                "userId" => $usuarioMandante->usumandanteId,
            ));

            $response = self::executeBonusFreeSpin($urlBonus, $userName, $password, $bodyToCreateFreeSpin);

            syslog(LOG_WARNING, "RAW BONO DATA: " . $bodyToCreateFreeSpin . " BONO RESPONSE: " . $response);

            $response = self::validateBonusFreeSpin($response);
            
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Verifica y crea una respuesta dependiendo si la ejecución del freeSpin fué exitosa o no
     * @param string Es la respuesta de la creación del freeSpin
     * @return string|json con la estructura de respuesta
     * @throws Exception Si ocurre algun error durante la conversión o acceso a datos.
     */
    private function validateBonusFreeSpin($response) :string {
        try {
            $response = DataConvert::fromJsonToObject($response);

            if ($response->status != "ok"){
                $response = DataConvert::toJson(array(
                    "code" => 1,
                    "response_code" => $response->status,
                    "response_message" => $response->description,
                ));

                return $response;
            }

            $response = DataConvert::toJson(array(
                "code" => 0,
                "response_code" => $response->status,
                "response_message" => $response->description,
                "response_data" => $response->data->promoId
            ));

            return $response;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Crea el freeSpin consumiendo el api del proveedor
     * @param string $urlBonus Es la url a donde se hace el llamado del api
     * @param string $userName Es el nombre de usuario de acceso al api
     * @param string $password Es la contraseña de acceso al api 
     * @param string|Json Son los parámetros que deben ser enviados por el cuerpo al api
     * 
     * @return string|Json Es la estructura de respuesta de error o exitosa del proveedor
     * @throws Exception Si ocurre algún error con CurlWrapper
     */
    private function executeBonusFreeSpin($urlBonus, $userName, $password, $bodyToCreateFreeSpin) :string {
        try {
            $curl = new CurlWrapper($urlBonus);
            $curl->setOptionsArray([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS => $bodyToCreateFreeSpin,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ],
                CURLOPT_USERPWD => "$userName:$password",
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC
            ]);

            $response = $curl->execute();

            return $response;
            
        } catch (Throwable $th) {
            throw $th;
        }
    }
}