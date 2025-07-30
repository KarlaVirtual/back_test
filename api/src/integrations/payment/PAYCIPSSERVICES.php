<?php

/**
 * Clase PAYCIPSSERVICES
 *
 * Esta clase proporciona servicios de integración con la API de PAYCIPS para realizar operaciones de pago,
 * autenticación de tarjetas, generación de referencias de pago, obtención de balances, y más.
 *
 * @category Integración
 *
 * @package Backend\Integrations\payment
 * @author  Desconocido
 * @version 1.0.0
 * @since   2025-04-25
 */

namespace Backend\Integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;

use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase PAYCIPSSERVICES
 *
 * Proporciona métodos para la integración con la API de PAYCIPS, incluyendo
 * autenticación, generación de referencias de pago, obtención de balances,
 * y manejo de transacciones.
 */
class PAYCIPSSERVICES
{
    /**
     * Login de la API.
     *
     * @var string
     */
    private $api_login = "";

    /**
     * Contraseña de la API.
     *
     * @var string
     */
    private $api_password = "";

    /**
     * URL de confirmación.
     *
     * @var string
     */
    private $URLConfirm = "";

    /**
     * URL de confirmación en entorno de desarrollo.
     *
     * @var string
     */
    private $URLConfirmDEV = "https://PayCIPS.com/Test/PayCIPSCheckoutForm";

    /**
     * URL de confirmación en entorno de producción.
     *
     * @var string
     */
    private $URLConfirmPROD = "https://PayCIPS.com/prod/PayCIPSCheckoutForm";

    /**
     * Modo de operación actual.
     *
     * @var string
     */
    private $OpMode = "";

    /**
     * Modo de operación en entorno de desarrollo.
     *
     * @var string
     */
    private $OpModeDEV = "TEST";

    /**
     * Modo de operación en entorno de producción.
     *
     * @var string
     */
    private $OpModePROD = "PROD";

    /**
     * URL de retorno.
     *
     * @var string
     */
    private $ReturnUrl = "";

    /**
     * URL de retorno en entorno de desarrollo.
     *
     * @var string
     */
    private $ReturnUrlDEV = "https://apidevintegrations.virtualsoft.tech/integrations/payment/paycips/";

    /**
     * URL de retorno en entorno de producción.
     *
     * @var string
     */
    private $ReturnUrlPROD = "https://integrations.virtualsoft.tech/payment/paycips/";

    /**
     * Metodo de la API.
     *
     * @var string
     */
    private $method;

    /**
     * URL base de la API.
     *
     * @var string
     */
    private $url;

    /**
     * URL base de la API en entorno de producción.
     *
     * @var string
     */
    private $urlProd = "https://paycips.com/v14/PROD/api/";

    /**
     * URL base de la API en entorno de desarrollo.
     *
     * @var string
     */
    private $urlDev = "https://paycips.com/v14/TEST/api/";

    /**
     * Vector de inicialización para encriptación.
     *
     * @var string
     */
    private $iv;

    /**
     * Vector de inicialización en entorno de desarrollo.
     *
     * @var string
     */
    private $ivDev = '477522fb7825b4b691f6a990edfacbc4';

    /**
     * Vector de inicialización en entorno de producción.
     *
     * @var string
     */
    private $ivProd = '477522fb7825b4b691f6a990edfacbc4';

    /**
     * Clave de encriptación.
     *
     * @var string
     */
    private $key;

    /**
     * Clave de encriptación en entorno de desarrollo.
     *
     * @var string
     */
    private $keyDev = '56b9ec57bdb4424aab5ca463c9647d06';

    /**
     * Clave de encriptación en entorno de producción.
     *
     * @var string
     */
    private $keyProd = '56b9ec57bdb4424aab5ca463c9647d06';

    /**
     * Clave del comerciante.
     *
     * @var string
     */
    private $merchantKey = "000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778";

    /**
     * URL del servicio de depósito en entorno de desarrollo.
     *
     * @var string
     */
    private $serviceUrlDepositDEV = "";

    /**
     * URL del servicio de depósito.
     *
     * @var string
     */
    private $serviceUrlDeposit = "";

    /**
     * Clave pública.
     *
     * @var string
     */
    private $public_key = "";

    /**
     * Clave secreta.
     *
     * @var string
     */
    private $secret_key = "";

    /**
     * Clave pública en entorno de desarrollo.
     *
     * @var string
     */
    private $public_keyDEV = "";

    /**
     * Clave secreta en entorno de desarrollo.
     *
     * @var string
     */
    private $secret_keyDEV = "";

    /**
     * Constructor de la clase.
     * Configura las URLs, claves y modos de operación dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URLConfirm = $this->URLConfirmDEV;
            $this->OpMode = $this->OpModeDEV;
            $this->ReturnUrl = $this->ReturnUrlDEV;

            $this->iv = $this->ivDev;
            $this->key = $this->keyDev;
            $this->url = $this->urlDev;
        } else {
            $this->URLConfirm = $this->URLConfirmPROD;
            $this->OpMode = $this->OpModePROD;
            $this->ReturnUrl = $this->ReturnUrlPROD;

            $this->iv = $this->ivProd;
            $this->key = $this->keyProd;
            $this->url = $this->urlProd;
        }
    }


    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario  Objeto del usuario.
     * @param Producto $Producto Objeto del producto.
     * @param float    $valor    Valor del pago.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $Registro = new Registro("", $Usuario->usuarioId);

        if ($Usuario->mandante == 3) { //CASINOMIRAVALLE
            $this->merchantKey = '000000003315_7AC35757B9EE6941C4E96E2BBC8173085847FEB29802EFE2AFFB7B820D89C36B';
        } elseif ($Usuario->mandante == 25) { //RED CASINO
            $this->merchantKey = '000000005680_40E0086989505ECD066A742531849B3ECF1CA1E09E871D301334F73863540456';
        } elseif ($Usuario->mandante == 6) { //NETABET
            $this->merchantKey = '000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778';
        } elseif ($Usuario->mandante == 22) { //GANAMEX
            $this->merchantKey = '000000004557_A5EFC765333C98377078296B5EBA4AA514974BB6EE0BF535269D786E6F140CF1';
        }

        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;

        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $banco = 0;
        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";


        switch ($pais) {
            case "173":
                $CountryCode = "PER";
                break;
            case "66":
                $CountryCode = "ECU";
                break;
            case "2":
                $CountryCode = "NIC";
                break;
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

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


        $t_value = json_encode(array());

        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transproductoId);
        $TransprodLog->setEstado('E');
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setComentario('Envio Solicitud de deposito');
        $TransprodLog->setTValue($t_value);
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
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $valorTax = floatval($valorTax * 100);
        $Mandante = $Usuario->mandante;
        $Subproveedor = new Subproveedor('', 'PAYCIPS');
        $Pais = new Pais($Usuario->paisId);
        $Amountcurrency = $Usuario->moneda;
        $TxRef1 = strtoupper($Pais->iso);
        $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $Mandante, '');
        $Detalle = $Subproveedor->detalle;
        $Detalle = json_decode($Detalle);
        $ContractNo = $Detalle->ContractNo;

        if ($Amountcurrency == "MXN") {
            $Amountcurrency = "MXP";
        }
        $Amountcurrency = "MXP";
        $TxRef1 = "MX";

        $Registro = new Registro("", $TransaccionProducto->getUsuarioId());

        $URLConfirm = $this->URLConfirm . "?amount={$valorTax}{$Amountcurrency}&TxOrderId={$transproductoId}&TxRef1={$TxRef1}&ContractNo={$ContractNo}&OpMode={$this->OpMode}";

        $data = array();
        $data["success"] = true;
        $data["url"] = $URLConfirm;
//."&ReturnUrl=".$this->ReturnUrl

        return json_decode(json_encode($data));
    }

    /**
     * Crea una solicitud de pago utilizando REST.
     *
     * @param Usuario  $Usuario  Objeto del usuario.
     * @param Producto $Producto Objeto del producto.
     * @param float    $valor    Valor del pago.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON.
     */
    public function createRequestPaymentREST(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        // $banco = $idBank;
        $usuario_id = $Usuario->usuarioId;
        // $nombre = $Usuario->nombre;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito Paycips";

        if ($mandante == 22) {
            $this->merchantKey = '000000004557_A5EFC765333C98377078296B5EBA4AA514974BB6EE0BF535269D786E6F140CF1';
        } elseif ($Usuario->mandante == 25) {
            $this->merchantKey = '000000005680_40E0086989505ECD066A742531849B3ECF1CA1E09E871D301334F73863540456';
        } elseif ($Usuario->mandante == 6) {
            $this->merchantKey = '000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778';
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

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


        $t_value = json_encode(array());

        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transproductoId);
        $TransprodLog->setEstado('E');
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setComentario('Envio Solicitud de deposito');
        $TransprodLog->setTValue($t_value);
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
        switch ($Producto->externoId) {
            case 'cash':
                $pathUrl = "cash";
                break;
            case 'debit':
                $pathUrl = 'debit';
                break;
            case 'credit':
                $pathUrl = 'credit';
                break;
        }

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $baseUrl = "https://admincert.virtualsoft.tech/api/api/integrations/payment/paycips/" . $pathUrl . "/";
        } else {
            $baseUrl = "https://admincert.virtualsoft.tech/api/api/integrations/payment/paycips/" . $pathUrl . "/";
        }
        $baseUrl = $this->ReturnUrl . $pathUrl . "/";

        $token = $ConfigurationEnvironment->encrypt($transproductoId);
        $URLConfirm = $baseUrl . "?id=" . $token;

        $data = array();
        $data["success"] = true;
        $data["url"] = $URLConfirm;

        return json_decode(json_encode($data));
    }

    /**
     * Guarda un registro de log para una transacción.
     *
     * @param integer $transproductoId ID de la transacción.
     * @param mixed   $tValue          Valor del log.
     *
     * @return void
     */
    public function saveLog($transproductoId, $tValue)
    {
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transproductoId);
        $TransprodLog->setEstado('E');
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setComentario('Envio Solicitud de deposito');
        $TransprodLog->setTValue(json_encode($tValue));
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
        $TransprodLogMySqlDAO->insert($TransprodLog);

        $Transaction->commit();
    }

    /**
     * Realiza una conexión con la API de PAYCIPS.
     *
     * @param string $method  Metodo de la API.
     * @param array  $data    Datos a enviar.
     * @param string $transId ID de la transacción.
     *
     * @return object Respuesta de la API.
     */
    public function connection($method, $data, $transId)
    {
        $tradeNumber = json_decode($this->getTradeNumber($transId));
        $url = $this->url . $method . $tradeNumber->Id;

        $headers = [
            'Content-Type: application/json; charset=utf-8'
        ];
        syslog(LOG_WARNING, "PAYCIPSREQUEST :" . json_encode($tradeNumber) . " " . $url . " " . json_encode($data));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($ch);

        if ($method == 'GetPayRef/' && $_ENV['debug']) {
            print_r($url);
            print_r(json_encode($data));
            exit();
        }
        syslog(LOG_WARNING, "PAYCIPSRESPONSE :" . $result);
        return json_decode($result);
    }

    /**
     * Valida la respuesta de la API.
     *
     * @param object $result Respuesta de la API.
     *
     * @return object Respuesta validada.
     * @throws Exception Si ocurre un error en la validación.
     */
    public function validateResponse($result)
    {
        if (isset($result->Status)) {
            $status = $result->Status;
        } else {
            $status = $result->status;
        }

        try {
            if ($status) {
                return $result;
            } else {
                throw new Exception($result->RespText);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            exit();
        }
    }

    /**
     * Obtiene un token de acceso.
     *
     * @param string $transId  ID de la transacción.
     * @param string $mandante Mandante (opcional).
     *
     * @return string Token de acceso.
     */
    public function accessToken($transId, $mandante = "")
    {
        $method = 'AccessToken/';

        $data = [
            "MerchantKey" => $this->merchantKey,
            "OrderId" => $transId,
        ];

        $response = $this->connection($method, $data, $transId);

        return $response->Token;
    }

    /**
     * Obtiene la lista de bancos disponibles.
     *
     * @param string $transId  ID de la transacción.
     * @param string $mandante Mandante.
     *
     * @return array Lista de bancos.
     */
    public function getBankList($transId, $mandante)
    {
        if ($mandante == 22) {
            $this->merchantKey = '000000004557_A5EFC765333C98377078296B5EBA4AA514974BB6EE0BF535269D786E6F140CF1';
        } elseif ($mandante == 25) {
            $this->merchantKey = '000000005680_40E0086989505ECD066A742531849B3ECF1CA1E09E871D301334F73863540456';
        } elseif ($mandante == 6) {
            $this->merchantKey = '000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778';
        }

        $method = 'GetBankList/';
        $token = $this->accessToken($transId);

        $data = [
            "Token" => $token
        ];

        $response = $this->connection($method, $data, $transId);
        return $response->ReportData;
    }

    /**
     * Genera una solicitud de pago SPEI.
     *
     * @param string  $account     Cuenta bancaria.
     * @param integer $idBank      ID del banco.
     * @param string  $description Descripción del pago.
     * @param string  $name        Nombre del titular.
     * @param float   $amount      Monto del pago.
     * @param string  $transId     ID de la transacción.
     * @param string  $mandante    Mandante.
     *
     * @return object Respuesta en formato JSON.
     */
    public function getSPEI($account, $idBank, $description, $name, $amount, $transId, $mandante)
    {
        if ($mandante == 22) {
            $this->merchantKey = '000000004557_A5EFC765333C98377078296B5EBA4AA514974BB6EE0BF535269D786E6F140CF1';
        } elseif ($mandante == 25) {
            $this->merchantKey = '000000005680_40E0086989505ECD066A742531849B3ECF1CA1E09E871D301334F73863540456';
        } elseif ($mandante == 6) {
            $this->merchantKey = '000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778';
        }

        if ($transId == '') {
            return $this->objResponse();
        }

        $method = 'GetSPEI/';
        $token = $this->accessToken($transId);

        $data = [
            "Account" => $account,
            "InstFinId" => $idBank,
            "Description" => $description,
            "Name" => $name,
            "Amount" => $amount * 100,
            "Token" => $token
        ];

        $response = $this->connection($method, $data, $transId);
        $this->saveLog($transId, $response);
        return json_decode($this->objResponse($response));
    }


    /**
     * Realiza un pago SPEI.
     *
     * @param Usuario  $Usuario  Objeto del usuario.
     * @param Producto $Producto Objeto del producto.
     * @param float    $valor    Valor del pago.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON.
     */
    public function SpeiPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($Usuario->mandante == 22) {
                $comercio = '4557';
                $this->merchantKey = '000000004557_A5EFC765333C98377078296B5EBA4AA514974BB6EE0BF535269D786E6F140CF1';
            } elseif ($Usuario->mandante == 25) {
                $comercio = '5680';
                $this->merchantKey = '000000005680_40E0086989505ECD066A742531849B3ECF1CA1E09E871D301334F73863540456';
            } elseif ($Usuario->mandante == 6) {
                $comercio = '642';
                $this->merchantKey = '000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778';
            }

            $this->url = "https://test.paycips.com/v20/api/";
            $urlSpei = "https://paycips.com/v20/STPMexSPEIInstructions.aspx?";
            $method = 'Payment/';
            $logo = "https://images.virtualsoft.tech/site/netabet/logo2.png";
        } else {
            if ($Usuario->mandante == 22) {
                $comercio = '4557';
                $this->merchantKey = '000000004557_A5EFC765333C98377078296B5EBA4AA514974BB6EE0BF535269D786E6F140CF1';
            } elseif ($Usuario->mandante == 25) {
                $comercio = '5680';
                $this->merchantKey = '000000005680_40E0086989505ECD066A742531849B3ECF1CA1E09E871D301334F73863540456';
            } elseif ($Usuario->mandante == 6) {
                $comercio = '642';
                $this->merchantKey = '000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778';
            }

            $this->url = "https://paycips.com/v20/api/";
            $urlSpei = "https://paycips.com/v20/STPMexSPEIInstructions.aspx?";
            $method = 'Payment/';
            $logo = "https://images.virtualsoft.tech/site/netabet/logo2.png";
        }

        $data_ = array();
        $data_["success"] = false;
        $data_["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);

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
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $token = $this->accessToken($transproductoId);

        $data = [
            "userId" => $Usuario->usuarioId,
            "userName" => $Usuario->nombre,
            "merchantId" => $comercio,
            "country" => $pais->iso,
            "transactionType" => "sale",
            "paymentOption" => [
                "alternativePaymentMethod" => [
                    "paymentMethod" => "apmgw_STPmex"
                ]
            ],
            "userTokenId" => $Usuario->usuarioId,
            "billingAddress" => [
                "email" => $Usuario->login,
                "country" => $pais->iso,
                "firstName" => $Usuario->nombre,
                "lastName" => $Registro->apellido1
            ],
            "sessionToken" => $token,
            "clientUniqueId" => $Usuario->usuarioId,
            "clientRequestId" => $transproductoId,
            "merchantSiteId" => $comercio,
            "amount" => $valorTax,
            "currency" => $Usuario->moneda
        ];

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $response = $this->connection($method, $data, $transproductoId);

        $descriptor = $Usuario->nombre;
        $this->ReturnUrl = $this->ReturnUrl; // Crear el array con los datos
        $data = array(
            "logo" => $logo,
            "firstName" => $Usuario->nombre,
            "lastName" => $Registro->apellido1,
            "clabe" => $response->externalAccountID,
            "order" => $token,
            "descriptor" => $descriptor,
            "returnURL" => $this->ReturnUrl
        );

        $queryString = http_build_query($data);
        $baseUrl = $urlSpei . $queryString;
        if ($response->externalAccountID != "") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($response));
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

            $data_ = array();
            $data_["success"] = true;
            $data_["url"] = $baseUrl;
        }

        return json_decode(json_encode($data_));
    }


    /**
     * Autentica una tarjeta.
     *
     * @param string $pan               Número de tarjeta.
     * @param string $cvv               Código de seguridad.
     * @param string $expDate           Fecha de expiración.
     * @param string $firstName         Nombre del titular.
     * @param string $lastName          Apellido del titular.
     * @param string $email             Correo electrónico.
     * @param float  $amount            Monto del pago.
     * @param string $currency          Moneda.
     * @param string $orderDescription  Descripción del pedido.
     * @param string $customerIPAddress Dirección IP del cliente.
     * @param string $lang              Idioma.
     * @param string $address           Dirección de facturación.
     * @param string $country           País.
     * @param string $transId           ID de la transacción.
     * @param string $mandante          Mandante.
     *
     * @return object Respuesta en formato JSON.
     */
    public function getCardAuth($pan, $cvv, $expDate, $firstName, $lastName, $email, $amount, $currency, $orderDescription, $customerIPAddress, $lang, $address, $country, $transId, $mandante)
    {
        if ($mandante == 22) {
            $this->url = "https://paycips.com/v20/api/";
            $method = 'GetCardAuth/';
            $this->merchantKey = '000000004557_A5EFC765333C98377078296B5EBA4AA514974BB6EE0BF535269D786E6F140CF1';
        } elseif ($mandante == 25) {
            $this->url = "https://paycips.com/v20/api/";
            $method = 'GetCardAuth/';
            $this->merchantKey = '000000005680_40E0086989505ECD066A742531849B3ECF1CA1E09E871D301334F73863540456';
        } elseif ($mandante == 6) {
            $this->url = "https://paycips.com/v20/api/";
            $method = 'GetCardAuth/';
            $this->merchantKey = '000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778';
        } else {
            $method = 'GetCardAuth/';
        }

        if ($transId == '') {
            return $this->objResponse();
        }

        switch ($currency) {
            case 'MXN':
                $currency = "MXP";
                break;
        }


        $token = $this->accessToken($orderDescription, $mandante);

        $data = [
            "pan" => $this->encrypt($pan, $transId),
            "cvv" => $this->encrypt($cvv, $transId),
            "expdate" => $this->encrypt($expDate, $transId),
            "firstname" => $this->encrypt($firstName, $transId),
            "lastname" => $this->encrypt($lastName, $transId),
            "email" => $email,
            "Amount" => $amount * 100,
            "Currency" => $currency,
            "Token" => $token,
            "OrderDescription" => $orderDescription,
            "CustomerIPAddress" => explode(',', $customerIPAddress)[0],
            "Lang" => $lang,
            "BillingAddress" => [
                "street" => $this->quitar_tildes($address),
                "street2" => "",
                "state" => "",
                "country" => $this->quitar_tildes($country),
                "zipCode" => ""
            ]
        ];

        $response = $this->connection($method, $data, $transId);
        $this->saveLog($transId, $response);
        return json_decode($this->objResponse($response));
    }

    /**
     * Obtiene un valor encriptado utilizando AES.
     *
     * @param string $inputString Cadena de entrada.
     * @param string $transId     ID de la transacción.
     *
     * @return object Respuesta de la API.
     */
    public function getAES($inputString, $transId)
    {
        $method = 'GetAES/';

        $data = [
            "inputString" => $inputString
        ];

        return $response = $this->connection($method, $data, $transId);
    }

    /**
     * Genera una referencia de pago.
     *
     * @param string $description Descripción del pago.
     * @param float  $amount      Monto del pago.
     * @param string $transId     ID de la transacción.
     * @param string $mandante    Mandante.
     *
     * @return object Respuesta en formato JSON.
     */
    public function getPayRef($description, $amount, $transId, $mandante)
    {
        if ($mandante == 22) {
            $this->url = "https://paycips.com/v20/api/";
            $this->merchantKey = '000000004557_A5EFC765333C98377078296B5EBA4AA514974BB6EE0BF535269D786E6F140CF1';
        } elseif ($mandante == 25) {
            $this->url = "https://paycips.com/v20/api/";
            $this->merchantKey = '000000005680_40E0086989505ECD066A742531849B3ECF1CA1E09E871D301334F73863540456';
        } elseif ($mandante == 6) {
            $this->url = "https://paycips.com/v20/api/";
            $this->merchantKey = '000000000642_A5171ADBC09753352B87B9ED2ABCD40A64B8C64BA1BF3033CB1675FEB00BE778';
        }

        if ($transId == '') {
            return $this->objResponse();
        }

        $method = "GetPayRef/";
        $token = $this->accessToken($transId);


        $data = [
            "Description" => $description,
            "Amount" => $amount,
            "Token" => $token
        ];

        $response = $this->connection($method, $data, $transId);
        $this->saveLog($transId, $response);

        return json_decode($this->objResponse($response));
    }

    /**
     * Obtiene el balance de la cuenta.
     *
     * @param string $transId ID de la transacción.
     *
     * @return object Respuesta en formato JSON.
     */
    public function getBalance($transId)
    {
        $method = "GetBalance/";
        $token = $this->accessToken($transId);
        $data = [
            "Token" => $token
        ];

        $response = $this->connection($method, $data, $transId);
        return json_decode($this->objResponse($response));
    }

    /**
     * Encripta un texto utilizando el algoritmo AES-128-CBC.
     *
     * @param string $text Texto a encriptar.
     *
     * @return string Texto encriptado en formato hexadecimal y en mayúsculas.
     */
    public function encrypt($text = "")
    {
        $method = 'AES-128-CBC';

        $iv = $this->pares($this->iv);
        $key = $this->pares($this->key);

        $crypt = openssl_encrypt($text, $method, $key, false, $iv);
        $crypt = bin2hex($crypt);
        $crypt = strtoupper($crypt);

        return $crypt;
    }

    /**
     * Convierte una cadena hexadecimal en una cadena de bytes.
     *
     * @param string $str Cadena hexadecimal de entrada.
     *
     * @return string Cadena de bytes resultante.
     */
    function pares($str)
    {
        $bytess = "";
        for ($pos = 0; $pos < strlen($str); $pos += 2) {
            $byte = $str[$pos] . $str[$pos + 1];
            $bytess .= chr(hexdec($byte));
        }
        return $bytess;
    }

    /**
     * Genera una respuesta en formato JSON.
     *
     * @param object $data Datos de la respuesta.
     *
     * @return string Respuesta en formato JSON.
     */
    public function objResponse($data = '')
    {
        if (isset($data->Status)) {
            $status = $data->Status;
        } else {
            $status = $data->status;
        }

        if ($status) {
            $return = [
                "success" => true,
                "data" => $data,
                "message" => "Transacción generada correctamente"
            ];
        } else {
            $return = [
                "success" => false,
                "message" => "Error al procesar la transacción"
            ];
        }

        return json_encode($return);
    }

    /**
     * Elimina tildes de una cadena.
     *
     * @param string $cadena Cadena de texto.
     *
     * @return string Cadena sin tildes.
     */
    function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

    /**
     * Obtiene el número de transacción.
     *
     * @param string $transId ID de la transacción.
     *
     * @return string Número de transacción.
     */
    public function getTradeNumber($transId)
    {
        $TransaccionProducto = new TransaccionProducto($transId);
        $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Proveedor = new Proveedor("", "PAYCIPS");
        $Subproveedor = new Subproveedor("", $Proveedor->abreviado);
        $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $UsuarioMandante->mandante);
        return ($SubproveedorMandante->detalle);
    }


}
