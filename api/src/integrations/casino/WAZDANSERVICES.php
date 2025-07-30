<?php

/**
 * Clase para integrar servicios de Wazdan en la API.
 *
 * Este archivo contiene la implementación de la clase `WAZDANSERVICES`,
 * que permite la integración con los servicios de Wazdan para la gestión
 * de juegos, generación de firmas, y conexión con la API.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Clase principal para manejar la integración con los servicios de Wazdan.
 */
class WAZDANSERVICES
{
    /**
     * Dirección IP de la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $URL_API_DEV = "85.10.255.116, 85.10.255.117";

    /**
     * URL de redirección actual.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * URL de redirección en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLREDIRECTIONDEV = "https://devfrontend.virtualsoft.tech/doradobetv3/new-casino/";

    /**
     * URL de redirección en el entorno de producción.
     *
     * @var string
     */
    private $URLREDIRECTIONPROD = 'https://doradobet.com/new-casino';

    /**
     * Firma generada para las solicitudes.
     *
     * @var string
     */
    private $Signature = "";

    /**
     * URL base actual.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL base en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://gl-staging.wazdanep.com';

    /**
     * URL base en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://gamelaunch.wazdan.com';

    /**
     * URL de servicios actual.
     *
     * @var string
     */
    private $URLSERVICES = "";

    /**
     * URL de servicios en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLSERVICESDEV = 'https://service-stage.wazdanep.com/';

    /**
     * URL de servicios en el entorno de producción.
     *
     * @var string
     */
    private $URLSERVICESPROD = 'https://service.wazdan.com/';

    /**
     * Licencia actual.
     *
     * @var string
     */
    private $LICENSE = "";

    /**
     * Licencia en el entorno de desarrollo.
     *
     * @var string
     */
    private $LICENSEDEV = "curacao";

    /**
     * Licencia en el entorno de producción.
     *
     * @var string
     */
    private $LICENSEPROD = "curacao";

    /**
     * Código del socio actual.
     *
     * @var string
     */
    private $PARTNERCODE = "";

    /**
     * Código del socio en el entorno de desarrollo.
     *
     * @var string
     */
    private $PARTNERCODEDEV = "zsnn2aed";

    /**
     * Código del socio en el entorno de producción.
     *
     * @var string
     */
    private $PARTNERCODEPROD = "zsnn2aed";

    /**
     * Nombre del socio actual.
     *
     * @var string
     */
    private $PARTNERNAME = "";

    /**
     * Nombre del socio en el entorno de desarrollo.
     *
     * @var string
     */
    private $PARTNERNAMEDEV = "virtualsoft";

    /**
     * Nombre del socio en el entorno de producción.
     *
     * @var string
     */
    private $PARTNERNAMEPROD = "virtualsoft";

    /**
     * Clave secreta actual.
     *
     * @var string
     */
    private $SecretKey = "";

    /**
     * Clave secreta en el entorno de desarrollo.
     *
     * @var string
     */
    private $SecretKeyDEV = "C4jX72m8rLZfPFQ3BnlypfSBuQg2Smkn";

    /**
     * Clave secreta en el entorno de producción.
     *
     * @var string
     */
    private $SecretKeyPROD = "TIahTj9NLH8vHBjTwt7J6GYwFTsFRvTB";

    /**
     * Indica si el entorno actual es de desarrollo.
     *
     * @var boolean
     */
    private $is_dev = false;

    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de configuración dependiendo del entorno
     * (desarrollo o producción) utilizando la clase `ConfigurationEnvironment`.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->LICENSE = $this->LICENSEDEV;
            $this->PARTNERCODE = $this->PARTNERCODEDEV;
            $this->PARTNERNAME = $this->PARTNERNAMEDEV;
            $this->SecretKey = $this->SecretKeyDEV;
            $this->URLREDIRECTION = $this->URLREDIRECTIONDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->LICENSE = $this->LICENSEPROD;
            $this->PARTNERCODE = $this->PARTNERCODEPROD;
            $this->PARTNERNAME = $this->PARTNERNAMEPROD;
            $this->SecretKey = $this->SecretKeyPROD;
            $this->URLREDIRECTION = $this->URLREDIRECTIONPROD;
        }
    }

    /**
     * Obtiene la URL para lanzar un juego.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo demo.
     * @param string  $usuarioToken  Token del usuario.
     * @param integer $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el juego se ejecuta en un dispositivo móvil.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                if ($isMobile) {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . "/" . $this->PARTNERCODE . "/gamelauncher?operator=" . $this->PARTNERNAME . "&game=" . $gameid . "&mode=demo&lang=" . $lang . "&platform=mobile&lobbyUrl" . $this->URLREDIRECTION
                    );
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . "/" . $this->PARTNERCODE . "/gamelauncher?operator=" . $this->PARTNERNAME . "&game=" . $gameid . "&mode=demo&lang=" . $lang . "&platform=desktop}&lobbyUrl" . $this->URLREDIRECTION
                    );
                }


                return json_decode(json_encode($array));
            } else {
                $Proveedor = new Proveedor("", "WAZDAN");

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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
                        $UsuarioToken->setProductoId(0);


                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                $UsuarioToken->setToken($UsuarioToken->createToken());

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->update($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "casino";
                }
                if ($isMobile) {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . "/" . $this->PARTNERCODE . "/gamelauncher?operator=" . $this->PARTNERNAME . "&game=" . $gameid . "&mode=real&token=" . $UsuarioToken->getToken() . "&license=" . $this->LICENSE . "&lang=" . $lang . "&platform=mobile&lobbyUrl" . $this->URLREDIRECTION
                    );
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . "/" . $this->PARTNERCODE . "/gamelauncher?operator=" . $this->PARTNERNAME . "&game=" . $gameid . "&mode=real&token=" . $UsuarioToken->getToken() . "&license=" . $this->LICENSE . "&lang=" . $lang . "&platform=desktop&lobbyUrl" . $this->URLREDIRECTION
                    );
                }
                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Genera la firma (signature) para las solicitudes.
     *
     * La firma se genera utilizando el algoritmo HMAC-SHA256 con la clave secreta.
     *
     * @return void
     */
    public function singnature()
    {
        $array = array(
            "operator" => $this->PARTNERNAME,
            "license" => $this->LICENSE
        );
        $array = json_encode($array);

        $signature = hash_hmac('sha256', $array, $this->SecretKey);

        $this->Signature = $signature;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Verifica varias cabeceras HTTP para determinar la IP del cliente.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    /**
     * Realiza una conexión HTTP POST con los datos proporcionados.
     *
     * @param string $data Datos en formato JSON para enviar en la solicitud.
     *
     * @return string Respuesta de la solicitud HTTP.
     */
    public function connection($data)
    {
        $curl = curl_init($this->URL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Signature: ' . $this->Signature]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }


}

