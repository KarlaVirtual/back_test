<?php

/**
 * Este archivo contiene la implementación de la clase `payment` para gestionar pagos
 * utilizando la integración con Paysafecard.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase `payment`
 *
 * Esta clase extiende `paysafecard_base` y proporciona métodos para
 * gestionar pagos, validar parámetros y generar disposiciones de pago.
 */
class payment extends paysafecard_base
{

    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    public $mid;


    /**
     * Constructor de la clase `payment`.
     *
     * Carga la configuración inicial y establece el modo si se proporciona.
     *
     * @param boolean $mode Modo de operación (opcional).
     */
    public function __construct($mode = false)
    {
        parent::loadConfig('payment');
        if ($mode) {
            $this->setMode($mode);
        }
    }

    /**
     * Crea un nuevo pago.
     *
     * Valida los parámetros proporcionados, verifica credenciales y genera
     * una disposición de pago si todo es correcto.
     *
     * @param array $parameter Parámetros del pago (username, password, etc.).
     *
     * @return boolean|mixed URL del panel del cliente en caso de éxito, o `false` en caso de error.
     */
    public function newPayment($parameter)
    {
        $validate = new paymentValidate($this->registry);
        if ( ! isset($parameter['username']) or ! isset($parameter['password'])) {
            $parameter['username'] = $this->registry->config->get('Merchant')['username'];
            $parameter['password'] = $this->registry->config->get('Merchant')['password'];
        }

        if ( ! $validate->validate($parameter)) {
            $this->_customerInfo = $this->registry->language->get('msg_create_disposition');
            $this->setError($this->registry->api->error);
            return false;
        }
        $parameter = $validate->checkPaymentParameter($parameter);
        if ( ! $parameter) {
            $this->_customerInfo = $this->registry->language->get('msg_create_disposition');
            $this->setError($this->registry->api->error);
            return false;
        }

        return $this->createDisposition($parameter);
        return true;
    }

    /**
     * Crea una disposición de pago.
     *
     * Realiza una acción en la API para generar una disposición de pago
     * y maneja los posibles errores.
     *
     * @param array $parameter Parámetros necesarios para la disposición.
     *
     * @return boolean|mixed URL del panel del cliente en caso de éxito, o `false` en caso de error.
     */
    private function createDisposition($parameter)
    {
        if ( ! $this->registry->api->newClient()) {
            $this->_customerInfo = $this->registry->language->get('msg_create_disposition');
            $this->_error = $this->registry->api->error;

            return false;
        }

        if ( ! $this->registry->api->action('createDisposition', $parameter)) {
            $this->_customerInfo = $this->registry->language->get('msg_create_disposition');
            $this->_error = $this->registry->api->error;

            return false;
        }

        if ($this->registry->api->result->createDispositionReturn->resultCode === 0 or $this->registry->api->result->createDispositionReturn->errorCode === 0) {
            $this->mid = $this->registry->api->result->createDispositionReturn->mid;

            return $this->getCustomerPanel($parameter);
        } else {
            $this->mid = null;
            $this->_customerInfo = $this->registry->language->get('msg_create_disposition');
            $this->setError(
                sprintf(
                    $this->registry->language->get('create_disposition_error'),
                    $this->registry->api->result->createDispositionReturn->resultCode,
                    $this->registry->api->result->createDispositionReturn->errorCode
                )
            );

            return false;
        }
    }

    /**
     * Obtiene la URL del panel del cliente.
     *
     * Genera la URL del panel de pago del cliente dependiendo del modo
     * (prueba o productivo) y los parámetros proporcionados.
     *
     * @param array $parameter Parámetros necesarios para construir la URL.
     *
     * @return string URL del panel del cliente.
     */
    private function getCustomerPanel($parameter)
    {
        $url = $this->registry->config->get('Mode') == 'test' ? $this->registry->config->get('PaymentPanelSandbox') : $this->registry->config->get('PaymentPanelProductive');
        $url .= '?currency=' . $parameter['currency'];
        $url .= '&mtid=' . $parameter['mtid'];
        $url .= '&amount=' . $parameter['amount'];
        $url .= '&mid=' . $this->mid;
        return $url;
    }
}
