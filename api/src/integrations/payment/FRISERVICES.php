<?php

/**
 * Clase para la integración con los servicios de pago de FRI.
 *
 * Este archivo contiene la implementación de la clase `FRISERVICES`, que permite
 * realizar operaciones relacionadas con pagos, como creación de solicitudes de pago,
 * búsqueda de usuarios, envío de transacciones, cancelaciones, reembolsos, entre otros.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Exception;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase `FRISERVICES`
 *
 * Esta clase proporciona métodos para interactuar con los servicios de pago de FRI,
 * incluyendo autenticación, búsqueda de usuarios, envío de transacciones, cancelaciones,
 * reembolsos y generación de enlaces de pago.
 */
class FRISERVICES
{


    /**
     * Nombre de usuario utilizado para la autenticación.
     *
     * @var string
     */
    private $username = "";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "doradobet-697";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string
     */
    private $usernamePROD = "doradobet-guatemala";

    /**
     * Contraseña utilizada para la autenticación.
     *
     * @var string
     */
    private $password = "";

    /**
     * Contraseña para el entorno de desarrollo.
     *
     * @var string
     */
    private $passwordDEV = "94udDmJTdz";

    /**
     * Contraseña para el entorno de producción.
     *
     * @var string
     */
    private $passwordPROD = "FLMfdgilVy";

    /**
     * Identificador de sesión actual.
     *
     * @var string
     */
    private $sessionId = "";

    /**
     * Identificador de sesión para el entorno de desarrollo.
     *
     * @var string
     */
    private $sessionIdDEV = "";

    /**
     * Identificador de sesión para el entorno de producción.
     *
     * @var string
     */
    private $sessionIdPROD = "";

    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://comercios.dev.soyfri.com';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://api.negocios.soyfri.com';

    /**
     * Tipo de operación o endpoint utilizado en las solicitudes.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase `FRISERVICES`.
     *
     * Este constructor inicializa las credenciales y la URL base dependiendo del entorno
     * configurado (desarrollo o producción). Utiliza la clase `ConfigurationEnvironment`
     * para determinar el entorno actual.
     *
     * - En el entorno de desarrollo, se asignan las credenciales y URL de desarrollo.
     * - En el entorno de producción, se asignan las credenciales y URL de producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;
            $this->password = $this->passwordDEV;
            $this->sessionId = $this->sessionIdDEV;
            $this->URL = $this->URLDEV;
        } else {
            $this->username = $this->usernamePROD;
            $this->password = $this->passwordPROD;
            $this->sessionId = $this->sessionIdPROD;
            $this->URL = $this->URLPROD;
        }
    }

    /**
     * Crea una solicitud de pago en el sistema FRI.
     *
     * Este método realiza las siguientes acciones:
     * - Autentica al usuario en el sistema FRI.
     * - Busca al usuario en FRI utilizando el nombre de usuario o número de teléfono.
     * - Calcula los impuestos aplicables al valor de la transacción.
     * - Registra la transacción en la base de datos.
     * - Envía la solicitud de pago o genera un enlace de pago dependiendo del dispositivo del usuario.
     *
     * @param Usuario  $Usuario         Objeto que representa al usuario que realiza la transacción.
     * @param Producto $Producto        Objeto que representa el producto asociado a la transacción.
     * @param float    $valor           Monto de la transacción.
     * @param string   $urlSuccess      URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed       URL a la que se redirige en caso de fallo.
     * @param string   $urlCancel       Opcional URL a la que se redirige en caso de cancelación.
     * @param string   $Fri_username    Opcional Nombre de usuario en FRI.
     * @param string   $Fri_phoneNumber Opcional Número de teléfono en FRI.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo la URL del pago si es exitosa.
     * @throws Exception Si ocurre un error durante la autenticación, búsqueda de usuario, creación de transacción o generación de enlace.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel = "", $Fri_username = "", $Fri_phoneNumber = "")
    {
        $data = array();
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $producto_id = $Producto->productoId;
        $mandante = $Usuario->mandante;
        $Pais = $Usuario->paisId;

        if ($mandante == 27 && $Pais == 94) {
            $this->username = "ganaplay-web";
            $this->password = "wQ39ksho22";
        }

        $dataLogin = array(

            "username" => $this->username,
            "password" => $this->password

        );


        $ResultLogin = $this->loginfri($dataLogin);

        $ResultLogin = json_decode($ResultLogin);


        $this->sessionId = $ResultLogin->responseContent->sessionId;

        $ContinueUsername = false;
        $ContinuePhone = false;


        if ($Fri_username !== "") {
            $dataSearch['info'] = array();
            $dataSearch['requestContent'] = array(

                "username" => $Fri_username

            );
        }


        $ResultSearch = $this->searchfri($dataSearch);


        $ResultSearch = json_decode($ResultSearch);


        if ($ResultSearch->info->type == "error") {
            if ($Fri_phoneNumber !== "") {
                $dataSearch['info'] = array();
                $dataSearch['requestContent'] = array(

                    "phoneNumber" => $Fri_phoneNumber

                );
            }

            $ResultSearch = $this->searchfri($dataSearch);


            $ResultSearch = json_decode($ResultSearch);


            if ($ResultSearch->info->type == "error") {
                throw new Exception("Usuario no encontrado en FRI", "100111");
            } elseif ($ResultSearch->info->type == "success") {
                $ContinueUsername = false;
                $ContinuePhone = true;
            }
        } elseif ($ResultSearch->info->type == "success") {
            $ContinueUsername = true;
            $ContinuePhone = false;
        }


        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        if ($Fri_username !== "" && $ContinueUsername) {
            $dataSend['info'] = array();
            $dataSend['requestContent'] = array(
                "friUsername" => $Fri_username,
                "amount" => number_format($valorTax, 2, '.', ''),
                "reference" => $transproductoId
            );
        } elseif ($Fri_phoneNumber !== "" && $ContinuePhone) {
            $dataSend['info'] = array();
            $dataSend['requestContent'] = array(
                "friPhoneNumber" => $Fri_phoneNumber,
                "amount" => number_format($valorTax, 2, '.', ''),
                "reference" => $transproductoId
            );
        }
        $Mandante = new Mandante($mandante);
        $result = $Mandante->baseUrl . 'gestion/deposito/pendiente';

        $ResultSend = '';
        $ResultLink = '';


        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $ismobile = '';
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            $ismobile = '1';
        }

        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        if ($iPod || $iPhone) {
            $ismobile = '1';
        } elseif ($iPad) {
            $ismobile = '1';
        } elseif ($Android) {
            $ismobile = '1';
        }

        if ($ismobile != '1') {
            $ResultSend = $this->sendfri($dataSend);
            $ResultSend = json_decode($ResultSend);

            if ($ResultSend->info->type == "error") {
                throw new Exception("Error al crear transaccion", "100113");
            }
        }

        $dataLink['info'] = array();
        $dataLink['requestContent'] = array(
            "amount" => number_format($valorTax, 2, '.', ''),
            "reference" => $transproductoId,
            "note" => "Deposito: " . $usuario_id,
        );

        if ($ismobile == '1') {
            $ResultLink = $this->linkfri($dataLink);
            $ResultLink = json_decode($ResultLink);

            $result = $ResultLink->responseContent->paymentLink;

            if ($ResultLink->info->type == "error") {
                throw new Exception("Error al crear link", "100115");
            }
        }

        if (($ResultSend->info->type != " " && isset($ResultSend->info->type) && $ResultSend->info->type == "success") || ($ResultLink->info->type != " " && isset($ResultLink->info->type) && $ResultLink->info->type == "success")) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito FRI');
            $TransprodLog->setTValue(json_encode($ResultSend));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);
            $Transaction->commit();


            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);


            $data = array();
            $data["success"] = true;
            $data["url"] = $result;
        }


        return json_decode(json_encode($data));
    }

    /**
     * Realiza el inicio de sesión en el sistema FRI.
     *
     * Este método envía una solicitud POST al endpoint de autenticación de FRI
     * con las credenciales proporcionadas en el parámetro `$datalogin`.
     *
     * @param array $datalogin Datos de inicio de sesión, incluyendo `username` y `password`.
     *
     * @return string Respuesta de la API de FRI en formato JSON.
     */
    public function loginfri($datalogin)
    {
        $this->tipo = '/business/auth/v1/login';

        $datalogin = json_encode($datalogin);

        $header = array(
            'Content-Type: application/json',
            'Cookie: visid_incap_2389751=2pp8Y7rrQqS8JbjbQ7Fdp99pQWQAAAAAQUIPAAAAAADwe+H2W2Zlz/jqdU8ALOCE'
        );


        $URL = $this->URL . $this->tipo;

        $curl = new CurlWrapper($URL);


        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $datalogin,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Cierra la sesión en el sistema FRI.
     *
     * Este método envía una solicitud POST al endpoint de cierre de sesión de FRI
     * utilizando el identificador de sesión actual para autenticar la solicitud.
     *
     * @param array $datalogout Datos necesarios para el cierre de sesión.
     *
     * @return string Respuesta de la API de FRI en formato JSON.
     */
    public function logoutfri($datalogout)
    {
        $this->tipo = '/business/auth/v1/logout';

        $datalogout = json_encode($datalogout);

        $header = array(
            "Content-Type: application/json",
            "Accept : application/json",
            "Authorization: Bearer " . $this->sessionId
        );

        $curl = curl_init($this->URL . $this->tipo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $datalogout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * Realiza una búsqueda de personas en el sistema FRI.
     *
     * Este método envía una solicitud POST al endpoint de búsqueda de personas en FRI.
     * Si se proporciona un identificador de sesión, se utiliza para autenticar la solicitud;
     * de lo contrario, se utiliza el identificador de sesión actual de la clase.
     *
     * @param array  $dataSearch Datos de búsqueda, incluyendo los criterios de búsqueda.
     * @param string $sessionId  Opcional. Identificador de sesión para autenticar la solicitud.
     *
     * @return string Respuesta de la API de FRI en formato JSON.
     */
    public function searchfri($dataSearch, $sessionId = "")
    {
        $this->tipo = '/business/processors/v1/persons/search';

        $dataSearch = json_encode($dataSearch, JSON_FORCE_OBJECT);


        if ($sessionId != "") {
            $header = array(
                "Authorization: Bearer " . $sessionId
            );
        } else {
            $header = array(
                "Authorization: Bearer " . $this->sessionId
            );
        }


        $curl = curl_init($this->URL . $this->tipo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataSearch);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);


        return $result;
    }

    /**
     * Envía una solicitud de transacción al sistema FRI.
     *
     * Este método realiza una solicitud POST al endpoint de envío de transacciones
     * utilizando los datos proporcionados en `$dataSend`.
     *
     * @param array $dataSend Datos de la solicitud, incluyendo información de la transacción.
     *
     * @return string Respuesta de la API de FRI en formato JSON.
     */
    public function sendfri($dataSend)
    {
        $this->tipo = '/business/transactions/v1/requests/send';

        $dataSend = json_encode($dataSend, JSON_FORCE_OBJECT);

        $header = array(
            "Authorization: Bearer " . $this->sessionId
        );

        $curl = curl_init($this->URL . $this->tipo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataSend);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * Cancela una solicitud de transacción en el sistema FRI.
     *
     * Este método envía una solicitud POST al endpoint de cancelación de transacciones
     * utilizando los datos proporcionados en `$dataCancel`.
     *
     * @param array $dataCancel Datos necesarios para cancelar la transacción.
     *
     * @return string Respuesta de la API de FRI en formato JSON.
     */
    public function cancelfri($dataCancel)
    {
        $this->tipo = '/business/transactions/v1/requests/cancel';

        $dataCancel = json_encode($dataCancel);

        $header = array(
            "Content-Type: application/json",
            "Accept : application/json",
            "Authorization: Bearer " . $this->sessionId
        );

        $curl = curl_init($this->URL . $this->tipo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataCancel);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * Recupera información de una transacción en el sistema FRI.
     *
     * Este método envía una solicitud POST al endpoint de recuperación de transacciones
     * utilizando los datos proporcionados en `$dataRetrieve`.
     *
     * @param array $dataRetrieve Datos necesarios para recuperar la transacción.
     *
     * @return string Respuesta de la API de FRI en formato JSON.
     */
    public function retrievefri($dataRetrieve)
    {
        $this->tipo = '/business/processors/v1/transactions/retrieve';

        $dataRetrieve = json_encode($dataRetrieve);

        $header = array(
            "Content-Type: application/json",
            "Accept : application/json",
            "Authorization: Bearer " . $this->sessionId
        );

        $curl = curl_init($this->URL . $this->tipo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataRetrieve);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * Genera un enlace de pago en el sistema FRI.
     *
     * Este método envía una solicitud POST al endpoint correspondiente para generar
     * un enlace de pago utilizando los datos proporcionados en `$dataLink`.
     *
     * @param array $dataLink Datos necesarios para generar el enlace de pago, incluyendo
     *                        el monto, referencia y cualquier otra información requerida.
     *
     * @return string Respuesta de la API de FRI en formato JSON, que incluye el enlace
     *                de pago si la operación es exitosa.
     */
    public function linkfri($dataLink)
    {
        $this->tipo = '/business/processors/v1/payments/link';

        $dataLink = json_encode($dataLink, JSON_FORCE_OBJECT);

        $time = time();
        $header = array(
            "Authorization: Bearer " . $this->sessionId
        );
        syslog(LOG_WARNING, "FRIDATA " . $time . " " . $this->URL . $this->tipo . $dataLink);

        $curl = curl_init($this->URL . $this->tipo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataLink);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);
        syslog(LOG_WARNING, "FRIRESPONSE " . $time . " " . $this->URL . $this->tipo . $result);

        return $result;
    }

    /**
     * Realiza un reembolso en el sistema FRI.
     *
     * Este método envía una solicitud POST al endpoint de reembolso de transacciones
     * utilizando los datos proporcionados en `$dataRefund`.
     *
     * @param array $dataRefund Datos necesarios para realizar el reembolso, incluyendo
     *                          información de la transacción y el monto a reembolsar.
     *
     * @return string Respuesta de la API de FRI en formato JSON.
     */
    public function refundfri($dataRefund)
    {
        $this->tipo = '/business/transactions/v1/refund';

        $dataRefund = json_encode($dataRefund);

        $header = array(
            "Content-Type: application/json",
            "Accept : application/json",
            "Authorization: Bearer " . $this->sessionId
        );

        $curl = curl_init($this->URL . $this->tipo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataRefund);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }


}


