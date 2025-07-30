<?php

/**
 * Clase para la integración con los servicios de PLAYSON.
 *
 * Este archivo contiene métodos para la creación de ofertas, obtención de juegos,
 * y manejo de solicitudes hacia los servicios de PLAYSON.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use \CurlWrapper;
use \SimpleXMLElement;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase PLAYSONSERVICES
 *
 * Proporciona métodos para interactuar con los servicios de PLAYSON, incluyendo
 * la creación de ofertas y la obtención de URLs de juegos.
 */
class PLAYSONSERVICES
{

    /**
     * URL de redirección utilizada en las solicitudes de los servicios de PLAYSON.
     *
     * @var string
     */
    private $URLREDIRECTION = '';

    /**
     * Constructor de la clase PLAYSONSERVICES.
     *
     * Inicializa la configuración del entorno y realiza configuraciones
     * específicas si el entorno es de desarrollo.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        }
    }

    /**
     * Crea una oferta en los servicios de PLAYSON.
     *
     * @param string  $offer               Nombre de la oferta.
     * @param array  $gameid               ID del juego.
     * @param integer $spins               Número de giros.
     * @param integer $betline             Apuesta por línea.
     * @param integer $bettotal            Apuesta total.
     * @param string  $startlive           Fecha de inicio de la oferta.
     * @param string  $enddate             Fecha de finalización de la oferta.
     * @param string  $user                Usuario asociado a la oferta.
     * @param string  $aditionalIdentifier Identificador adicional del freeSpin..
     *
     * @return array Respuesta con el estado de la operación.
     *
     * @throws Exception Si los parámetros de apuesta son inválidos.
     */
    public function createOffer($offer, $games, $spins, $betline, $bettotal, $startlive, $enddate, $user, $aditionalIdentifier)
    {
        $gameid = trim($games[0]);
        $Proveedor = new Proveedor('', 'PLAYSON');
        $Producto = new Producto('', $gameid, $Proveedor->proveedorId);

        $Usuario = new Usuario($user);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Mandante = new Mandante($Usuario->mandante);
        $name = $Mandante->nombre;

        $bonoId = $offer . $aditionalIdentifier . '_' . $name . $Usuario->usuarioId;

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $currency = $Usuario->moneda;

        $start = new DateTime($startlive);
        $end = new DateTime($enddate);

        $startDate = $start->format('d/m/Y H:i');
        $endDate = $end->format('d/m/Y H:i');

        $startDateFormat = str_replace(' ', '%20', $startDate);
        $endDateFormat = str_replace(' ', '%20', $endDate);

        if (($betline == '' && $bettotal != '') || ($betline != '' && $bettotal == '') || ($betline != '' && $bettotal != '')) {
            $Betline = intval($betline);
            $Bettotal = intval($bettotal);
        } else {
            throw new Exception("cantidad de apuesta es cero", "10000");
        }

        if ($Betline != '0' && $Betline != '') {
            $string = "/?cm=create_offer&offer={$bonoId}&game={$gameid}&wlcode={$Credentials->wl_code}&currency={$Usuario->moneda}&spins={$spins}&bet-line={$Betline}&start_live={$startDateFormat}&end_date_add_offer={$endDateFormat}";
        }
        if ($Bettotal != '0' && $Bettotal != '') {
            $string = "/admservice.cgi?cm=create_offer&offer={$bonoId}&game={$gameid}&wlcode=default&spins={$spins}&lines={$Betline}&total-bet={$Bettotal}&start_live={$startlive}&end_date={$enddate}";
        }

        $return = $this->PLAYSONRequest($string, $Credentials);
        $return = json_encode($return);
        syslog(LOG_WARNING, "PLAYSON BONO DATA: " . $string . " RESPONSE: " . $return);

        $stringAssign = "/?cm=add_offer&wlcode={$Credentials->wl_code}&wlid={$UsuarioMandante->usumandanteId}&type=real&offer={$bonoId}";
        $returnAssign = $this->Add_offer($stringAssign, $Credentials);
        $returnAssign = json_encode($returnAssign);

        syslog(LOG_WARNING, "PLAYSON BONO DATA ASSING: " . $stringAssign . " RESPONSE: " . $returnAssign);

        $return = json_decode($return, true);
        $returnAssign = json_decode($returnAssign, true);

        if ((string)$return['@attributes']['status'] == 'ok' || (string)$returnAssign['@attributes']['status'] == 'ok') {
            $array = array(
                "code" => 0,
                "error" => false,
                "response" => 'OK',
            );
        } else {
            $array = array(
                "code" => 1,
                "error" => true,
                "response" => 'Error',
            );
        }

        return $array;
    }

    /**
     * Obtiene la URL de un juego en los servicios de PLAYSON.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo "jugar por diversión".
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $productoId    ID del producto.
     * @param boolean $isMobile      Indica si el juego es para dispositivos móviles.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego.
     *
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $productoId, $isMobile = false, $usumandanteId = "")
    {
        try {
            $launchURL = '';

            if ($play_for_fun) {
                if (is_string($gameid)) {
                    $array = array(
                        "error" => false,
                        "response" => $launchURL . $gameid . "/open?token=" . strtolower($lang),
                    );
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $launchURL . $gameid . "/open?token=" . strtolower($lang),
                    );
                }

                return json_decode(json_encode($array));
            } else {
                $Proveedor = new Proveedor("", "PLAYSON");

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);

                    $UsuarioToken->setToken($UsuarioToken->createToken());
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setProductoId($productoId);

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
                        $UsuarioToken->setProductoId($productoId);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                $Mandante = new Mandante($UsuarioMandante->mandante);
                $Producto = new Producto($productoId);
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                if ($Mandante->baseUrl != '') {
                    $this->URLREDIRECTION = $Mandante->baseUrl . "new-casino";
                }
                if ($isMobile) {
                    $url = $Credentials->URL . "?key=" . $UsuarioToken->getToken() . "&gameName=" . $gameid . "&partner=" . $Credentials->partner . "&wl_code=" . $Credentials->wl_code . "&platform=mob&lang=" . strtolower($lang) . "&exit_url=" . $this->URLREDIRECTION;

                    $array = array(
                        "error" => false,
                        "response" => $url
                    );
                } else {
                    $url = $Credentials->URL . "?key=" . $UsuarioToken->getToken() . "&gameName=" . $gameid . "&partner=" . $Credentials->partner . "&wl_code=" . $Credentials->wl_code . "&platform=desk&lang=" . strtolower($lang) . "&exit_url=" . $this->URLREDIRECTION;

                    $array = array(
                        "error" => false,
                        "response" => $url
                    );
                }

                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Realiza una solicitud a los servicios de PLAYSON.
     *
     * @param string $string      URL de la solicitud.
     * @param object $Credentials Credenciales para la solicitud.
     * @param string $method      Método HTTP (por defecto "GET").
     *
     * @return SimpleXMLElement Respuesta de la solicitud en formato XML.
     */
    public function PLAYSONRequest($string, $Credentials, $method = "GET")
    {
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ];

        $curl = new CurlWrapper($Credentials->URLBONUS . $string);

        //Configurar opciones
        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $result = $curl->execute();

        $oSimpleXMLObject = new SimpleXMLElement($result);
        libxml_use_internal_errors(true);

        // Retornar el resultado
        return $oSimpleXMLObject;
    }

    /**
     * Agrega una oferta en los servicios de PLAYSON.
     *
     * @param string $stringAssign URL de la solicitud para agregar la oferta.
     * @param object $Credentials  Credenciales para la solicitud.
     * @param string $method       Método HTTP (por defecto "GET").
     *
     * @return SimpleXMLElement Respuesta de la solicitud en formato XML.
     */
    public function Add_offer($stringAssign, $Credentials, $method = "GET")
    {
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ];

        $curl = new CurlWrapper($Credentials->URLBONUS . $stringAssign);

        //Configurar opciones
        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $result = $curl->execute();

        $oSimpleXMLObject = new SimpleXMLElement($result);
        libxml_use_internal_errors(true);
        return $oSimpleXMLObject;
    }
}

