<?php

namespace Backend\integrations\casino;

use DateTime;
use Throwable;
use DateTimeZone;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\integrations\casino\genericTransactional\models\DataConvert;

/**
 * Clase de configuración y parametrización de los servicios de RFRANCO, es responsable de obtener:
 *          - URL del juego (lanzamiento).
 *          - Configurar el request de freeSpin con el formato y estructura correctas para el proveedor.
 *          - Ejecutar la petición para crear el freeSpin.
 *          - Validar la respuesta de freeSpin y retornar el resultado.
 * 
 * @category API
 * @package  src\integrations\casino\
 * @author   Esteban Arévalo
 * @version  1.0
 * @since    13/06/2025
 */
class RFRANCOSERVICES {

    /**
     * Nombre del proveedor.
     * 
     * @var string
     */
    private static $supplierName = "RFRANCO";

    /**
     * Construye la URL del juego para el proveedor RFRANCO, se encarga de crear el token del usuario y de obtener la URL de redirección al lobby del casino.
     * 
     * @param string $externoGameId     Es el id del juego dado por el proveedor.
     * @param string $language          Es el idioma en el que se mostrará el juego.
     * @param bool   $isPlayForFun      Indica si el juego es en modo demo o real.
     * @param string $usuarioToken      Es el token del usuario que se está autenticando.
     * @param string $usuarioMandanteId Es el id del usuario asociado al proveedor.
     * 
     * @return object Es la estructura de respuesta satisfactoria o de error:
     *          - error: booleano que indica si hubo un error o no.
     *          - response: string que contiene la URL del juego o el mensaje de error.
     * @throws Exception Si ocurre algún error durante el proceso de construcción de la URL o al buscar información en la base de datos.
     */
    public function getGame($externoGameId, $language, $isPlayForFun, $usuarioToken, $usuarioMandanteId) :object {
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

            $gameUrl = $credentials->URL . "launcher/?" .
                       'isForFun=' . ($isPlayForFun ? "true" : "false") . 
                       '&gameUid=' . $externoGameId . 
                       '&casinoUid=' . $credentials->CASINOUID .
                       '&lang=' . $language . 
                       '&playerUid=' . $usuarioToken->usuarioId .
                       '&token=' . $usuarioToken->token .
                       '&currencyCode=' . $usuarioMandante->moneda . 
                       '&integration=' . $credentials->INTEGRATION;

            return DataConvert::fromAssociativeArrayToObject(array("error" => false, "response" => $gameUrl));
            
        } catch (Throwable $th) {
            return DataConvert::fromAssociativeArrayToObject(array("error" => true, "response" => $th->getMessage()));
        }
    }

    /**
     * Extrae y configura los datos necesarios para la creación del freeSpin.
     * 
     * @param string     $bonoId              Id único del bono que se está otorgando.
     * @param int|string $roundsFree          Cantidad de rondas otorgadas en el freeSpin.
     * @param int|string $roundValue          Valor por cada ronda gratis.
     * @param object     $bonoInterno         Objeto que contiene la información del bono.
     * @param array      $usersId             Ids des usuarios a los que se les asigna el freeSpin.
     * @param array      $games               Contiene el id de los juegos que hacen parte de la campaña de freeSpin.
     * @param string     $aditionalIdentifier Identificador adicional del freeSpin..
     * 
     * @return void
     * @throws Exception Si ocurre algun error consultando los datos.
     */
    public function createBonusFreeSpin($bonoId, $roundsFree, $roundsValue, object &$bonoInterno, $usersId, $games, $aditionalIdentifier){
        try {
            $proveedor = new Proveedor('', self::$supplierName);
            $producto = new Producto('', $games[0], $proveedor->getProveedorId());

            $date = new DateTime($bonoInterno->fechaInicio, new DateTimeZone('America/Bogota'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $startDateUTC = $date->getTimestamp() * 1000;

            $date = new DateTime($bonoInterno->fechaFin, new DateTimeZone('America/Bogota'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $endDateUTC = $date->getTimestamp() * 1000;

            $usuarioMandanteIds = [];
            foreach ($usersId as $userId) {
                $usuario = new Usuario($userId);    
                $usuarioMandante = new UsuarioMandante('', $usuario->usuarioId, $usuario->mandante);
                $usuarioMandanteIds[] = $usuarioMandante->usumandanteId;
            }

            $subProveedorMandantePais = new SubproveedorMandantePais('', $producto->subproveedorId, $usuarioMandante->mandante, $usuarioMandante->paisId);
            $credentials = DataConvert::fromJsonToObject($subProveedorMandantePais->getCredentials());

            $urlBonus = $credentials->URL . "api/freespin/promos?requestUid=" . $bonoId;
            $userName = $credentials->USERNAME;
            $password = $credentials->PASSWORD;

            $bet = $roundsValue * 1000;
            $budget = $bet * $roundsFree * count($usuarioMandanteIds); 

            $bodyToCreateFreeSpin = DataConvert::toJson([
                "promoType" => "CAMPAIGN",
                "bonusId" => null,
                "bonus" => [
                    "name" => "Campaña :" . $bonoId . "_" . $aditionalIdentifier . $usuario->usuarioId,
                    "description" => $bonoInterno->descripcion,
                    "casinoUid" => $credentials->CASINOUID,
                    "currencyCode" => $usuarioMandante->moneda,
                    "realWin" => true,
                    "bonusType" => "FREEBET",
                    "expirationTime" => $endDateUTC,
                    "autoAccepted" => true,
                    "bonusGameConfigurationRequests" => [
                        [
                            "type" => "CONFIGURED_ROUNDS",
                            "rounds" => $roundsFree,
                            "bet" => $bet,
                            "advanced" => false
                        ]
                    ]
                ],
                "name" => "Campaña :" . $bonoId . "_" . $aditionalIdentifier . $usuario->usuarioId,
                "description" => $bonoInterno->descripcion,
                "deviceType" => "ALL_DEVICES",
                "initDate" => $startDateUTC,
                "endDate" => $endDateUTC,
                "validationRequired" => false,
                "budget" => $budget,
                "campaignType" => "PRIVATE",
                "playerUids" => $usuarioMandanteIds,
                "gameUids" => $games,
                "reason" => "",
                "currencyCode" => $usuarioMandante->moneda,
            ]);

            $response = self::executeBonusFreeSpin($urlBonus, $userName, $password, $bodyToCreateFreeSpin);

            syslog(LOG_WARNING, "RFRANCO BONO DATA: " . $bodyToCreateFreeSpin . " RESPONSE: " . $response);

            $response = self::validateBonusFreeSpin($response);

            return $response;
            
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Verifica y crea una respuesta dependiendo si la ejecución del freeSpin fué exitosa o no.
     * 
     * @param string $response Es la respuesta de la creación del freeSpin.
     * 
     * @return array Con la estructura de respuesta.
     * @throws Exception Si ocurre algun error durante la conversión o acceso a datos.
     */
    private function validateBonusFreeSpin($response) :array {
        try {
            $response = DataConvert::fromJsonToObject($response);

            if (!isset($response->errorCode)){
                $response = array(
                    "code" => 0,
                    "response_code" => "OK",
                    "response_message" => "Success",
                );

                return $response;
            }

            $response = array(
                "code" => 1,
                "response_code" => $response->errorCode,
                "response_message" => $response->errorDescription
            );

            return $response;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Crea el freeSpin consumiendo el api del proveedor.
     * 
     * @param string $urlBonus                  Es la url a donde se hace el llamado del api.
     * @param string $userName                  Es el nombre de usuario de acceso al api.
     * @param string $password                  Es la contraseña de acceso al api. 
     * @param string|Json $bodyToCreateFreeSpin Son los parámetros que deben ser enviados por el cuerpo al api.
     * 
     * @return string|Json Es la estructura de respuesta de error o exitosa del proveedor.
     * @throws Exception Si ocurre algún error con CurlWrapper.
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