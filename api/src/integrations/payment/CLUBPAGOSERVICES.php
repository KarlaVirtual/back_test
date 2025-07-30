<?php

/**
 * Este archivo contiene la clase `CLUBPAGOSERVICES`, que proporciona métodos para interactuar con el servicio de pagos ClubPago.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\Integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Este archivo contiene la clase `CLUBPAGOSERVICES`, que proporciona métodos para interactuar con el servicio de pagos ClubPago.
 * Incluye funcionalidades para generar referencias de pago, códigos de barras y formatos de pago, además de manejar la autenticación y la conexión con el servicio.
 */
class CLUBPAGOSERVICES
{
    /**
     * Nombre de usuario utilizado para la autenticación.
     * Este valor se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $username = "";

    /**
     * Contraseña utilizada para la autenticación.
     * Este valor se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $password = "";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "ElTribet651";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string
     */
    private $usernamePROD = "ElTribet651";

    /**
     * URL base para las solicitudes al servicio de pagos.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL para la autenticación con el servicio de pagos.
     *
     * @var string
     */
    private $URLAUTH = "";

    /**
     * URL base para las solicitudes en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://qa.clubpago.site/referencegenerator/svc/generator/';

    /**
     * URL para la autenticación en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEVAUTH = 'https://qa.clubpago.site/auth/api/auth';

    /**
     * URL base para las solicitudes en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://referencias.clubpago.site/svc/generator/';

    /**
     * URL para la autenticación en el entorno de producción.
     *
     * @var string
     */
    private $URLPRODAUTH = 'https://auth.clubpago.site/api/auth';

    /**
     * Token de autenticación generado tras una solicitud exitosa.
     *
     * @var string
     */
    private $token = "";

    /**
     * Metodo utilizado en las solicitudes al servicio de pagos.
     *
     * @var string
     */
    private $metodo = "";

    /**
     * URL de callback para notificaciones del servicio de pagos.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/clubpago/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/clubpago/confirm/";

    /**
     * Clave privada utilizada para la autenticación.
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
     * Contraseña utilizada para la autenticación.
     *
     * @var string
     */
    private $Password = "";

    /**
     * Contraseña para el entorno de desarrollo.
     *
     * @var string
     */
    private $PasswordDEV = "j3bWXsn243yj";

    /**
     * Contraseña para el entorno de producción.
     *
     * @var string
     */
    private $PasswordPROD = "j3bWXsn243yj";

    /**
     * Clave pública utilizada para la autenticación.
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
     * Valor de autenticación generado a partir de la clave privada.
     *
     * @var string
     */
    private $Auth = "";

    /**
     * Constructor de la clase `CLUBPAGOSERVICES`.
     * Inicializa las variables de configuración dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;
            $this->password = $this->PasswordDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->URLAUTH = $this->URLDEVAUTH;
            $this->KeyPRIVATE = $this->KeyPRIVATEDEV;
            $this->KeyPUBLIC = $this->KeyPUBLICDEV;
        } else {
            $this->username = $this->usernamePROD;
            $this->password = $this->PasswordPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->URLAUTH = $this->URLPRODAUTH;
            $this->KeyPRIVATE = $this->KeyPRIVATEPROD;
            $this->KeyPUBLIC = $this->KeyPUBLICPROD;
        }
    }


    /**
     * Crea una referencia de pago para un usuario y un producto.
     *
     * Este metodo genera una referencia de pago interactuando con el servicio de pagos.
     * También registra la transacción en la base de datos y maneja los impuestos aplicables.
     *
     * @param Usuario  $Usuario    Objeto que contiene la información del usuario.
     * @param Producto $Producto   Objeto que contiene la información del producto.
     * @param float    $valor      Monto de la transacción.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto con los datos de la referencia generada o un error.
     */
    public function createRequestPaymentReference(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        // Impuesto a los depósitos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        $array = array(
            "User" => $this->username,
            "Pswd" => $this->password
        );

        $Result = $this->connectionAutentica($array);
        if ($Result->Message == "Exito") {
            $this->token = $Result->Token;
        }
        $this->metodo = "refence";
        $datos = array(
            "Descripcion" => $descripcion,
            "Amount" => $valorTax,
            "Account" => $Usuario->usuarioId,
            "CustomerEmail" => $email,
            "CustomerName" => $nombre,
            "ExpirationDate" => date('Y-m-d', strtotime('+1 day')),
            "RequestClabe" => 0
        );
        $Result2 = $this->connection($datos);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

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
        $TransaccionProducto->setExternoId($Result2->Reference);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($datos);

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


        if (json_decode($Result2->Message) == 'Success') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($Result);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $response = json_decode($Result);

            $data2 = array();
            $data2["success"] = true;
            $data2["Reference"] = $response->Reference;
        }

        return json_decode(json_encode($data2));
    }

    /**
     * Genera una solicitud de referencia de pago en formato de código de barras.
     *
     * Este meodo interactúa con el servicio de pagos para generar un código de barras
     * que puede ser utilizado para realizar un depósito. También registra la transacción
     * en la base de datos y maneja los impuestos aplicables.
     *
     * @param Usuario  $Usuario    Objeto que contiene la información del usuario.
     * @param Producto $Producto   Objeto que contiene la información del producto.
     * @param float    $valor      Monto de la transacción.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto con los datos del código de barras generado o un error.
     */
    public function createRequestPaymentBarcode(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

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

        $array = array(
            "User" => $this->username,
            "Pswd" => $this->password
        );

        $Result = $this->connectionAutentica($array);
        if ($Result->Message == "Exito") {
            $this->token = $Result->Token;
        }
        $this->metodo = "barcode";
        $datos = array(
            "Descripcion" => $descripcion,
            "Amount" => $valorTax,
            "Account" => $Usuario->usuarioId,
            "CustomerEmail" => $email,
            "CustomerName" => $nombre,
            "ExpirationDate" => date('Y-m-d', strtotime('+1 day')),
            "RequestClabe" => 0,
        );
        $Result2 = $this->connection($datos);
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

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
        $TransaccionProducto->setExternoId($Result2->Reference);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($datos);

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


        if (json_decode($Result2->Message) == 'Success') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($Result);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $response = json_decode($Result);

            $data2 = array();
            $data2["success"] = true;
            $data2["BarCode"] = $response->BarCode;
        }

        return json_decode(json_encode($data2));
    }

    /**
     * Genera una solicitud de referencia de pago en formato de PayFormat.
     *
     * Este metodo interactúa con el servicio de pagos para generar un formato de pago
     * que puede ser utilizado para realizar un depósito. También registra la transacción
     * en la base de datos y maneja los impuestos aplicables.
     *
     * @param Usuario  $Usuario    Objeto que contiene la información del usuario.
     * @param Producto $Producto   Objeto que contiene la información del producto.
     * @param float    $valor      Monto de la transacción.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto con los datos del formato de pago generado o un error.
     */
    public function createRequestPaymentPayformat(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

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

        $array = array(
            "User" => $this->username,
            "Pswd" => $this->password
        );

        $Result = $this->connectionAutentica($array);

        $Result = json_decode($Result);

        if ($Result->Message == "Exito") {
            $this->token = $Result->Token;
        }

        $this->metodo = "payformat";

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

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
        //$TransaccionProducto->setExternoId("");
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $datos = array(
            "Description" => $descripcion,
            "Amount" => floatval($valorTax) * 100,
            "Account" => intval($transproductoId),
            "CustomerEmail" => $email,
            "CustomerName" => $nombre,
            "ExpirationDate" => date('Y-m-d', strtotime('+1 day')),
            "RequestClabe" => 1,
        );

        $Result2 = $this->connection($datos);

        $Result2 = json_decode($Result2);


        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($datos);

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


        if ($Result2->Message == 'Exitosa') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
            $TransaccionProducto = new TransaccionProducto($transproductoId);
            $TransaccionProducto->setExternoId($Result2->Reference);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();
            $data2 = array();
            $data2["success"] = true;
            $data2["url"] = $Result2->PayFormat;
        }

        return json_decode(json_encode($data2));
    }

    /**
     * Genera un valor de autenticación utilizando el algoritmo HMAC con SHA-512.
     *
     * Este metodo toma los datos proporcionados y los cifra utilizando la clave privada
     * configurada en la clase, generando un valor de autenticación que se almacena en
     * la propiedad `$Auth`.
     *
     * @param string $data Los datos que se desean cifrar.
     *
     * @return void
     */
    public function Encrypta($data)
    {
        $this->Auth = hash_hmac('sha512', $data, $this->KeyPRIVATE);
    }

    /**
     * Realiza una solicitud de autenticación al servicio de pagos.
     *
     * Este metodo utiliza cURL para enviar una solicitud POST al endpoint de autenticación
     * configurado en la propiedad `$URLAUTH`. Los datos proporcionados se codifican en formato JSON
     * y se incluyen en el cuerpo de la solicitud. El metodo devuelve la respuesta del servicio.
     *
     * @param array $data Datos necesarios para la autenticación, como usuario y contraseña.
     *
     * @return string Respuesta del servicio de autenticación en formato JSON.
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
        curl_close($curl);
        return $result;
    }

    /**
     * Realiza una solicitud al servicio de pagos utilizando cURL.
     *
     * Este metodo envía una solicitud POST al endpoint configurado en `$URL` y `$metodo`.
     * Los datos proporcionados se codifican en formato JSON y se incluyen en el cuerpo de la solicitud.
     * Además, se incluyen encabezados HTTP para la autenticación y el formato de contenido.
     *
     * @param array $data Datos que se enviarán en la solicitud.
     *
     * @return string Respuesta del servicio en formato JSON.
     */
    public function connection($data)
    {
        $data = json_encode($data);


        $headers = array(
            'Authorization: Bearer ' . $this->token,
            'Content-type: application/json',
            'Accept: application/json'
        );

        $curl = curl_init($this->URL . $this->metodo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

}
