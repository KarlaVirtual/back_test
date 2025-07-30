<?php

/**
 * Clase para gestionar los servicios de integración con Western Union.
 *
 * Este archivo contiene la implementación de la clase `WESTERNUNIONSERVICES`,
 * que permite realizar operaciones relacionadas con pagos y transacciones
 * utilizando la integración con Western Union.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

/**
 * Clase `WESTERNUNIONSERVICES`
 *
 * Esta clase gestiona la integración con los servicios de Western Union,
 * permitiendo realizar operaciones relacionadas con pagos y transacciones.
 */
class WESTERNUNIONSERVICES
{
    /**
     * Nombre de usuario para la autenticación.
     *
     * @var string
     */
    private $username = "";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string
     */
    private $usernamePROD = "";

    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL para la autenticación.
     *
     * @var string
     */
    private $URLAUTH = "";

    /**
     * URL base para las solicitudes en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = "";

    /**
     * URL para la autenticación en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEVAUTH = "";

    /**
     * URL base para las solicitudes en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = "";

    /**
     * URL para la autenticación en el entorno de producción.
     *
     * @var string
     */
    private $URLPRODAUTH = "";

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token = "";

    /**
     * Método de la API a invocar.
     *
     * @var string
     */
    private $metodo = "";

    /**
     * URL de callback para las notificaciones.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/pagofacil/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/pagofacil/confirm/";

    /**
     * Clave privada para la autenticación.
     *
     * @var string
     */
    private $KeyPRIVATE = "";

    /**
     * Clave privada para el entorno de desarrollo.
     *
     * @var string
     */
    private $KeyPRIVATEDEV = "";

    /**
     * Clave privada para el entorno de producción.
     *
     * @var string
     */
    private $KeyPRIVATEPROD = "";

    /**
     * Contraseña para la autenticación.
     *
     * @var string
     */
    private $password = "";

    /**
     * Contraseña para el entorno de desarrollo.
     *
     * @var string
     */
    private $passwordDEV = "";

    /**
     * Contraseña para el entorno de producción.
     *
     * @var string
     */
    private $passwordPROD = "";

    /**
     * Clave pública para la autenticación.
     *
     * @var string
     */
    private $KeyPUBLIC = "";

    /**
     * Clave pública para el entorno de desarrollo.
     *
     * @var string
     */
    private $KeyPUBLICDEV = "";

    /**
     * Clave pública para el entorno de producción.
     *
     * @var string
     */
    private $KeyPUBLICPROD = "";

    /**
     * Hash HMAC generado para la autenticación.
     *
     * @var string
     */
    private $Auth = "";

    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de configuración dependiendo del entorno
     * (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;
            $this->password = $this->passwordDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->URLAUTH = $this->URLDEVAUTH;
            $this->KeyPRIVATE = $this->KeyPRIVATEDEV;
            $this->KeyPUBLIC = $this->KeyPUBLICDEV;
        } else {
            $this->username = $this->usernamePROD;
            $this->password = $this->passwordPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->URLAUTH = $this->URLPRODAUTH;
            $this->KeyPRIVATE = $this->KeyPRIVATEPROD;
            $this->KeyPUBLIC = $this->KeyPUBLICPROD;
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario    Objeto del usuario que realiza la solicitud.
     * @param Producto $Producto   Objeto del producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Proveedor = new Proveedor("", "WESTERNUNION");
        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        //$valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Registro = new Registro("", $Usuario->usuarioId);

        $Ciudad = new Ciudad($Registro->ciudadId);


        $rules = [];


        array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "E", "op" => "eq"));
        array_push($rules, array("field" => "producto.proveedor_id", "data" => "$Proveedor->proveedorId", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $SkeepRows = 0;
        $OrderedItem = 1;
        $MaxRows = 10000;

        $json = json_encode($filtro);
        $TransaccionProducto = new TransaccionProducto();
        $transacciones = $TransaccionProducto->getTransaccionesCustom("transaccion_producto.*,producto.*,usuario.nombre", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);
        $transacciones = json_decode($transacciones);


        if ($transacciones->count[0]->{".count"} < 1) {
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
            $TransaccionProducto->setProductoId($Producto->productoId);
            $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
            $TransaccionProducto->setValor($valor);
            $TransaccionProducto->setImpuesto($totalTax);
            $TransaccionProducto->setEstado($estado);
            $TransaccionProducto->setTipo($tipo);
            $TransaccionProducto->setEstadoProducto($estado_producto);
            $TransaccionProducto->setMandante($mandante);
            $TransaccionProducto->setFinalId(0);
            $TransaccionProducto->setExternoId(0);
            $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode($data);
            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Deposito');
            $TransprodLog->setTValue(json_encode(""));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);


            $ismobile = '';

            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                $ismobile = '1';
            }
//Detect special conditions devices
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }
            //exec("php -f ". __DIR__ ."/../crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "SOLICITUDDEPOSITOCRM" . " " . $transproductoId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

            $data = array();
            $data["success"] = true;
            $data["dataText"] = "La solicitud de deposito fue creada";
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1689795700.png";


            return json_decode(json_encode($data));
        } else {
            $data = array();
            $data["success"] = true;
            $data["dataText"] = "No se puedo crear la solicitud de deposito, ya tiene una solicitud pendiente";
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1689795700.png";
            return json_decode(json_encode($data));
        }
    }


    /**
     * Genera un hash HMAC para autenticar datos.
     *
     * @param string $data Datos a encriptar.
     *
     * @return void
     */
    public function Encrypta($data)
    {
        $this->Auth = hash_hmac('sha512', $data, $this->KeyPRIVATE);
    }


    /**
     * Realiza una conexión para autenticación.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta del servidor.
     */
    public function connectionAutentica($data)
    {
        $data = json_encode($data);

        $curl = curl_init($this->URLAUTH);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($result);
        return $result;
    }

    /**
     * Realiza una conexión para enviar datos a la API de Western Union.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta del servidor.
     */
    public function connection($data)
    {
        $data = json_encode($data);
        /*print_r($data);
        print_r("    ");*/

        $headers = array(
            'Authorization: Basic ' . base64_encode($this->KeyPRIVATE),
            'Content-type: application/json ',
            'Accept: application/vnd.WESTERNUNION-v2.0.0+json'
        );


        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);


        curl_close($result);
        return $result;
    }


}
