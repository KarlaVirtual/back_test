<?php

/**
 * Esta clase se encarga de gestionar las integraciones con el servicio de pagos WEPAY4U.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\CupoLog;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioPerfil;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Exception;

/**
 * Clase WEPAY4USERVICES
 *
 * Esta clase se encarga de gestionar las integraciones con el servicio de pagos WEPAY4U.
 * Proporciona métodos para realizar transacciones de pago y manejar solicitudes relacionadas.
 */
class WEPAY4USERVICES
{
    /**
     * Credenciales de inicio de sesión para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_loginDEV = "";

    /**
     * Contraseña de la API para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_passwordDEV = "";

    /**
     * Credenciales de inicio de sesión para el entorno de producción.
     *
     * @var string
     */
    private $api_login = "";

    /**
     * Contraseña de la API para el entorno de producción.
     *
     * @var string
     */
    private $api_password = "";

    /**
     * Metodo utilizado para definir el tipo de solicitud HTTP.
     *
     * @var string
     */
    private $method;

    /**
     * Nombre de usuario para autenticación en el servicio de pagos.
     *
     * @var string
     */
    private $username = "";

    /**
     * Nombre de usuario para autenticación en el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "434027f4732ec468840a36b15cbcd83715559738";

    /**
     * Nombre de usuario para autenticación en el entorno de producción.
     *
     * @var string
     */
    private $usernamePROD = "";

    /**
     * URL base para el entorno de desarrollo del servicio de pagos WEPAY4U.
     *
     * @var string
     */
    private $URLDEV = 'https://stg-payout.wepay4u.com/api/v1';

    /**
     * URL base para el entorno de producción del servicio de pagos WEPAY4U.
     *
     * @var string
     */
    private $URLPROD = "https://payout.wepay4u.com/api/v1";

    /**
     * URL base que se utilizará para las solicitudes HTTP.
     *
     * @var string
     */
    private $URL = '';

    /**
     * Tipo de solicitud HTTP que se utilizará en las transacciones.
     *
     * @var string
     */
    private $tipo = '';

    /**
     * Constructor de la clase WEPAY4USERVICES.
     *
     * Inicializa las credenciales y la URL base dependiendo del entorno
     * (desarrollo o producción) configurado en el sistema.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->username = $this->usernameDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->username = $this->usernamePROD;
        }
    }

    /**
     * Realiza una solicitud de retiro (cash out) a través del servicio de pagos WEPAY4U.
     *
     * Este método se encarga de procesar una transacción de retiro para un usuario,
     * configurando los datos necesarios y enviando la solicitud al servicio externo.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     * @param string      $ProductoId  Opcional Identificador del producto. Si no se proporciona, se utiliza el
     *                                 producto asociado al banco.
     *
     * @return void
     * @throws Exception Si ocurre un error durante el procesamiento o la transacción no se puede realizar.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $ProductoId = "")
    {
        $this->tipo = '/payout_order';

        // Obtiene información del usuario y registro asociado a la cuenta de cobro
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $Mandante = $Usuario->mandante;

        // Configura el subproveedor y obtiene las credenciales necesarias
        $Subproveedor = new Subproveedor('', 'WEPAY4U');
        $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $Mandante, '');
        $Detalle = $Subproveedor->detalle;
        $Detalle = json_decode($Detalle);
        $this->username = $Detalle->username;

        // Obtiene información bancaria del usuario
        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        // Determina el producto a utilizar
        if ($ProductoId == "") {
            $Producto = new Producto($Banco->productoPago);
        } else {
            $Producto = new Producto($ProductoId);
        }
        // Crea una nueva transacción de producto
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $valorFinal = $CuentaCobro->getValorAPagar();
        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valorFinal);
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        // Asocia la transacción a la cuenta de cobro
        $CuentaCobro->setTransproductoId($transproductoId);

        // Configura los datos necesarios para la solicitud de retiro
        $order_id = $transproductoId;
        $credit_note = $CuentaCobro->getCuentaId();
        $account_id = $UsuarioBanco->cuenta;
        $interbank = $UsuarioBanco->getCodigo();
        $bankId = $UsuarioBanco->getBancoId();
        $typeAccount = $UsuarioBanco->getTipoCuenta();
        $account_type = "Cuenta de Ahorros";
        $vat_id = $Registro->cedula;
        $amount = $CuentaCobro->getValor();
        $name = $Usuario->nombre;
        $LastName = $Registro->apellido1;
        $subject = 'Transferencia Cuenta ' . $CuentaCobro->getCuentaId();
        $bank_detail = $Banco->descripcion;
        $channel = 1;
        $user_email = $Usuario->login;
        $phone_number = $Registro->celular;
        $bank = $Producto->getExternoId();
        $bank = $Banco->productoPago;

        // Ajusta el código ISO del país si es necesario
        $pais = new Pais($Usuario->paisId);

        if (strtoupper($pais->iso) == "PE") {
            $pais->iso = "PER";
        }
        if (strtoupper($pais->iso) == "EC") {
            $pais->iso = "ECU";
        }
        // Determina el tipo de documento del usuario
        $tipoDoc = "";

        switch ($Registro->getTipoDoc()) {
            case "E":
                $tipoDoc = "C.EXT";
                break;
            case "P":
                $tipoDoc = "PAS";
                break;
            default:
                $tipoDoc = "DNI";
                break;
        }

        // Ajusta el tipo de cuenta bancaria
        $bankId = $Producto->getExternoId();

        switch ($UsuarioBanco->getTipoCuenta()) {
            case "0":
                $typeAccount = "AHORRO";
                break;
            case "1":
                $typeAccount = "CORRIENTE";
                break;
            case "Ahorros":
                $typeAccount = "AHORRO";
                break;
            case "Corriente":
                $typeAccount = "CORRIENTE";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        // Prepara los datos para la solicitud de transacción
        $data = array(
            "MerchantReference" => $order_id,
            "Amount" => $valorFinal,
            "CountryCode" => $pais->iso,
            "CurrencyCode" => $Usuario->moneda,
            "Creation" => date("Y-m-d H:i:s"),
            "Customer" => array(
                "Info" => array(
                    "FirstName" => $name,
                    "LastName" => $LastName,
                    "DocType" => $tipoDoc,
                    "DocNumber" => $vat_id,
                    "CountryCode" => $pais->iso,
                    "Email" => $user_email,
                    "Mobile" => $phone_number,
                ),
                "Bank" => array(
                    "BankCode" => $bankId,
                    "AccountBankNumber" => str_replace('-', '', $account_id),
                    "AccountBankNumberCCI" => $interbank,
                    "TypeAccountBank" => $typeAccount,
                )
            )
        );

        // Registra la solicitud en el sistema de logs
        try {
            syslog(
                10, 'WEPAYSERVICE ' . (json_encode(
                    $data
                ))
            );
        } catch (Exception $e) {
        }

        // Envía la solicitud de transacción al servicio externo
        $result = $this->createTransaction($data);

        // Verifica el resultado de la transacción y actualiza los registros
        if ($result != "" && $result != null && ($result[0])->PublicID != null) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId(($result[0])->PublicID);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("No se pudo realizar la transaccion", "100000");
        }
    }

    /**
     * Realiza una solicitud de retiro en lote (batch cash out) a través del servicio de pagos WEPAY4U.
     *
     * Este método procesa múltiples transacciones de retiro para un conjunto de cuentas de cobro,
     * configurando los datos necesarios y enviando las solicitudes al servicio externo.
     *
     * @param array $CuentaCobro Arreglo de objetos `CuentaCobro` que contienen la información de las cuentas de cobro.
     *
     * @return array Retorna un arreglo con el estado de las transacciones procesadas.
     * @throws Exception Si ocurre un error durante el procesamiento o la transacción no se puede realizar.
     */
    public function cashOut2($CuentaCobro)
    {
        $this->tipo = '/payout_order_batch';

        $final = [];
        $order_ids = [];

        foreach ($CuentaCobro as $key => $value) {
            /**
             * Procesa cada cuenta de cobro para generar los datos necesarios para la solicitud.
             */
            $Usuario = new Usuario($CuentaCobro[$key]->usuarioId);
            $Registro = new Registro("", $CuentaCobro[$key]->usuarioId);

            $Mandante = $Usuario->mandante;

            $Subproveedor = new Subproveedor('', 'WEPAY4U');
            $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $Mandante, '');
            $Detalle = $Subproveedor->detalle;
            $Detalle = json_decode($Detalle);
            $this->username = $Detalle->username;


            $UsuarioBanco = new UsuarioBanco($CuentaCobro[$key]->mediopagoId);

            $Banco = new Banco($UsuarioBanco->bancoId);

            $TransaccionProducto = new TransaccionProducto($CuentaCobro[$key]->getTransproductoId());

            $Producto = new Producto($TransaccionProducto->getProductoId());

            $valorFinal = $CuentaCobro[$key]->getValor() - $CuentaCobro[$key]->getImpuesto() - $CuentaCobro[$key]->getImpuesto2();

            $transproductoId = $CuentaCobro[$key]->getTransproductoId();

            // Configuración de los datos necesarios para la solicitud
            $order_id = $transproductoId;
            $credit_note = $CuentaCobro[$key]->getCuentaId();
            $account_id = $UsuarioBanco->cuenta;
            $interbank = $UsuarioBanco->getCodigo();
            $bankId = $UsuarioBanco->getBancoId();
            $typeAccount = $UsuarioBanco->getTipoCuenta();
            $account_type = "Cuenta de Ahorros";
            $vat_id = $Registro->cedula;
            $amount = $CuentaCobro[$key]->getValor();
            $name = $Usuario->nombre;
            $LastName = $Registro->apellido1;
            $subject = 'Transferencia Cuenta ' . $CuentaCobro[$key]->getCuentaId();
            $bank_detail = $Banco->descripcion;
            $channel = 1;
            $user_email = $Usuario->login;
            $phone_number = $Registro->celular;
            $bank = $Producto->getExternoId();
            $bank = $Banco->productoPago;


            $pais = new Pais($Usuario->paisId);

            // Ajuste del código ISO del país si es necesario
            if (strtoupper($pais->iso) == "PE") {
                $pais->iso = "PER";
            }
            if (strtoupper($pais->iso) == "EC") {
                $pais->iso = "ECU";
            }

            // Determinación del tipo de documento del usuario
            $tipoDoc = "";

            switch ($Registro->getTipoDoc()) {
                case "E":
                    $tipoDoc = "C.EXT";
                    break;
                case "P":
                    $tipoDoc = "PAS";
                    break;
                default:
                    $tipoDoc = "DNI";
                    break;
            }

            // Ajuste del tipo de cuenta bancaria
            $bankId = $Producto->getExternoId();

            switch ($UsuarioBanco->getTipoCuenta()) {
                case "0":
                    $typeAccount = "AHORRO";
                    break;
                case "1":
                    $typeAccount = "CORRIENTE";
                    break;
                case "Ahorros":
                    $typeAccount = "AHORRO";
                    break;
                case "Corriente":
                    $typeAccount = "CORRIENTE";
                    break;
                default:
                    throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                    break;
            }

            // Preparación de los datos para la solicitud de transacción
            $data = array(
                "MerchantReference" => $order_id,
                "Amount" => $valorFinal,
                "CountryCode" => $pais->iso,
                "CurrencyCode" => $Usuario->moneda,
                "Creation" => date("Y-m-d H:i:s"),
                "Customer" => array(
                    "Info" => array(
                        "FirstName" => $name,
                        "LastName" => $LastName,
                        "DocType" => $tipoDoc,
                        "DocNumber" => $vat_id,
                        "CountryCode" => $pais->iso,
                        "Email" => $user_email,
                        "Mobile" => $phone_number,
                    ),
                    "Bank" => array(
                        "BankCode" => $bankId,
                        "AccountBankNumber" => str_replace('-', '', $account_id),
                        "AccountBankNumberCCI" => $interbank,
                        "TypeAccountBank" => $typeAccount,
                    )
                )
            );

            array_push($order_ids, $transproductoId);
            array_push($final, $data);
        }

        // Registro de la solicitud en el sistema de logs
        try {
            syslog(10, 'WEPAYSERVICE ' . (json_encode($final)));
        } catch (Exception $e) {
        }

        // Envío de la solicitud de transacción al servicio externo
        $result = $this->createTransaction($final);

        // Manejo de errores y actualización de registros
        $CantFaileds = oldCount($result->Message->ErrorDetails);
        $Faileds = [];

        foreach ($result->Message->ErrorDetails as $key3 => $value3) {
            if (strpos($result->Message->ErrorDetails[$key3]->Description, 'duplicate key value violates unique constraint') === false) {
                array_push($Faileds, $result->Message->ErrorDetails[$key3]->line - 1);
            }
        }
        $transactions = array();
        foreach ($order_ids as $key2 => $value2) {
            $transactions[$key2]['transproductoId'] = $order_ids[$key2];
            $transactions[$key2]['Status'] = "1";
            $transactions[$key2]['CuentaId'] = $CuentaCobro[$key2]->getCuentaId();

            foreach ($Faileds as $key4 => $value4) {
                if ($key2 == $value4) {
                    $transactions[$key2]['Status'] = "0";
                }
            }
        }

        // Registro de las transacciones en el sistema
        try {
            syslog(10, 'WEPAYSERVICERESPONSE ' . (json_encode($result)));
        } catch (Exception $e) {
        }

        $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();
        $Transaction = $TransprodLogMysqlDAO->getTransaction();

        foreach ($transactions as $key5 => $value5) {
            if ($transactions[$key5]["Status"] == "1") {
                // Registro de transacciones exitosas
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transactions[$key5]["transproductoId"]);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('M');
                $TransprodLog->setComentario('Envio Solicitud de pago Webpay');
                $TransprodLog->setTValue(json_encode($result));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMysqlDAO->insert($TransprodLog);
            } else {
                // Manejo de transacciones fallidas
                if ($transactions[$key5]['transproductoId'] != null) {
                    $TransprodLog = new TransprodLog();
                    $TransprodLog->setTransproductoId($transactions[$key5]['transproductoId']);
                    $TransprodLog->setEstado('E');
                    $TransprodLog->setTipoGenera('M');
                    $TransprodLog->setComentario('Envio Solicitud de pago Webpay');
                    $TransprodLog->setTValue(json_encode($result));
                    $TransprodLog->setUsucreaId(0);
                    $TransprodLog->setUsumodifId(0);

                    $TransprodLogMysqlDAO = new TransprodLogMySqlDAO($Transaction);
                    $TransprodLogMysqlDAO->insert($TransprodLog);

                    $TransprodLog = new TransprodLog();
                    $TransprodLog->setTransproductoId($transactions[$key5]["transproductoId"]);
                    $TransprodLog->setEstado('R');
                    $TransprodLog->setTipoGenera('M');
                    $TransprodLog->setComentario('Rechazo Solicitud de pago por Webpay');
                    $TransprodLog->setTValue(json_encode($result));
                    $TransprodLog->setUsucreaId(0);
                    $TransprodLog->setUsumodifId(0);

                    $TransprodLogMysqlDAO = new TransprodLogMySqlDAO($Transaction);

                    $TransaccionProducto = new TransaccionProducto($transactions[$key5]["transproductoId"]);
                    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                    $TransaccionProducto->setEstado('R');
                    $TransaccionProducto->setExternoId(0);
                    $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                    $TransprodLogMysqlDAO->insert($TransprodLog);

                    // Actualización del estado de la cuenta de cobro
                    if (($CuentaCobro[$key5]->getEstado() != "I" || ($CuentaCobro[$key5]->getEstado() == "I" && $CuentaCobro[$key5]->getPuntoventaId() == "0")) && $CuentaCobro[$key5]->getEstado() != "R" && $CuentaCobro[$key5]->getEstado() != "E") {
                        if ($CuentaCobro[$key5]->getEstado() == "I") {
                            $CuentaCobro[$key5]->setEstado('D');
                        } else {
                            $CuentaCobro[$key5]->setEstado('R');
                        }
                        $CuentaCobro[$key5]->setUsurechazaId(0);
                        $CuentaCobro[$key5]->setMensajeUsuario('Rechazo por proveedor');
                        $CuentaCobro[$key5]->setObservacion('Rechazo por proveedor');
                        $CuentaCobro[$key5]->setFechaAccion(date("Y-m-d H:i:s"));

                        if ($CuentaCobro[$key5]->getUsupagoId() == "") {
                            $CuentaCobro[$key5]->setUsupagoId(0);
                        }

                        if ($CuentaCobro[$key5]->getFechaAccion() == "") {
                            $CuentaCobro[$key5]->setFechaAccion(date("Y-m-d H:i:s"));
                        }

                        if ($CuentaCobro[$key5]->getFechaCambio() == "") {
                            $CuentaCobro[$key5]->setFechaCambio(date("Y-m-d H:i:s"));
                        }

                        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                        $rowsUpdate = $CuentaCobroMySqlDAO->update(
                            $CuentaCobro[$key5],
                            " AND (estado!='R' AND estado!='E')"
                        );
                        if ($rowsUpdate <= 0) {
                            throw new Exception('No se puede realizar la cancelacion', '21001');
                        }

                        $Usuario = new Usuario($CuentaCobro[$key5]->getUsuarioId());
                        $UsuarioPerfil = new UsuarioPerfil($CuentaCobro[$key5]->getUsuarioId());

                        if ($UsuarioPerfil->getPerfilId() == "USUONLINE") {
                            $Usuario->creditWin(
                                $CuentaCobro[$key5]->getValor(),
                                $CuentaCobroMySqlDAO->getTransaction()
                            );

                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(40);
                            $UsuarioHistorial->setValor($CuentaCobro[$key5]->getValor());
                            $UsuarioHistorial->setExternoId($CuentaCobro[$key5]->getCuentaId());

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                        } else {
                            if ($CuentaCobro[$key5]->version == '3') {
                                $Usuario->creditosAfiliacion = "creditos_afiliacion + " . $CuentaCobro[$key5]->getValor();
                                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                $UsuarioMySqlDAO->update($Usuario);
                            }
                        }

                        // Registro de actividad en CRM
                        try {
                            $useragent = $_SERVER['HTTP_USER_AGENT'];
                            $jsonServer = json_encode($_SERVER);
                            $serverCodif = base64_encode($jsonServer);

                            $ismobile = '';

                            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent, 0, 4))) {
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

                            exec("php -f " . __DIR__ . "/../../../src/integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "RETIROELIMINADOCRM" . " " . $CuentaCobro[$key5]->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");
                        } catch (Exception $e) {
                        }

                        $response["HasError"] = false;
                        $response["AlertType"] = "success";
                        $response["AlertMessage"] = '';
                        $response["ModelErrors"] = [];
                        $response["Data"] = [];
                    }
                }
            }
        }
        $Transaction->commit();
        return $transactions;
    }

    /**
     * Crea una transacción enviando los datos al servicio externo.
     *
     * Este método utiliza la función `request` para realizar una solicitud HTTP
     * al servicio externo con los datos proporcionados. La respuesta se registra
     * en el sistema de logs y se decodifica antes de ser retornada.
     *
     * @param array $data Datos necesarios para la transacción.
     *
     * @return mixed La respuesta decodificada del servicio externo.
     */
    public function createTransaction($data)
    {
        $path = $this->tipo;

        $respuesta = $this->request($path, $data, array("Content-Type: application/json"));
        try {
            syslog(10, 'WEPAYSERVICERESPONSE ' . $respuesta);
        } catch (Exception $e) {
        }
        $respuesta = json_decode($respuesta);
        return $respuesta;
    }

    /**
     * Realiza una solicitud HTTP al servicio externo.
     *
     * Este método utiliza cURL para enviar una solicitud HTTP al servicio externo
     * con los datos proporcionados. Configura los encabezados, el método HTTP y
     * otros parámetros necesarios para la solicitud.
     *
     * @param string $path      Ruta relativa del endpoint al que se enviará la solicitud.
     * @param array  $array_tmp Datos que se enviarán en el cuerpo de la solicitud.
     * @param array  $header    Encabezados HTTP opcionales para la solicitud.
     * @param string $method    Método HTTP que se utilizará (por defecto "POST").
     *
     * @return string Respuesta del servicio externo en formato JSON.
     */
    public function request($path, $array_tmp, $header = array(), $method = "POST")
    {
        $data = array();

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $ch = curl_init($this->URL . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . "");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = (curl_exec($ch));

        return ($result);
    }
}
