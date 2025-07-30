<?php

/**
 * Este archivo contiene la clase `paymentExecute` que se encarga de ejecutar pagos
 * utilizando la integración con Paysafecard. Proporciona métodos para validar
 * parámetros, obtener números de serie y ejecutar débitos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase `paymentExecute`
 *
 * Esta clase extiende de `paysafecard_base` y proporciona funcionalidades
 * relacionadas con la ejecución de pagos mediante la API de Paysafecard.
 */
class paymentExecute extends paysafecard_base
{

    /**
     * Estado de la disposición después de obtener los números de serie.
     *
     * @var string
     */
    public $dispositionState;


    /**
     * Constructor de la clase `paymentExecute`.
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
     * Ejecuta el proceso de pago.
     *
     * @param array $parameter Parámetros necesarios para la ejecución del pago.
     *
     * @return boolean|mixed Retorna `false` en caso de error o el resultado de `getSerialNumbers`.
     */
    public function execute($parameter)
    {
        $validate = new paymentValidate($this->registry);
        if ( ! isset($parameter['username']) or ! isset($parameter['password'])) {
            $parameter['username'] = $this->registry->config->get('Merchant')['username'];
            $parameter['password'] = $this->registry->config->get('Merchant')['password'];
        }
        if ( ! $validate->validate($parameter)) {
            $this->_customerInfo = $this->registry->language->get('msg_execute_debit');
            $this->setError($validate->error);
            return false;
        }
        $parameter = $validate->checkExecuteParameter($parameter);
        if ( ! $parameter) {
            $this->_customerInfo = $this->registry->language->get('msg_execute_debit');
            $this->setError($validate->error);
            return false;
        }
        return $this->getSerialNumbers($parameter);
    }

    /**
     * Obtiene los números de serie necesarios para el pago.
     *
     * @param array $parameter Parámetros necesarios para la solicitud.
     *
     * @return boolean|mixed Retorna `false` en caso de error o el resultado de `executeDebit`.
     */
    private function getSerialNumbers($parameter)
    {
        $params = array(
            'username' => $parameter['username'],
            'password' => $parameter['password'],
            'mtid' => $parameter['mtid'],
            'subId' => $parameter['subId'],
            'currency' => $parameter['currency']
        );
        if ( ! $this->registry->api->newClient()) {
            $this->_customerInfo = $this->registry->language->get('msg_execute_debit');
            $this->setError($this->registry->api->error);
            return false;
        }
        if ( ! $this->registry->api->action('getSerialNumbers', $parameter)) {
            $this->_customerInfo = $this->registry->language->get('msg_execute_debit');
            $this->setError($this->registry->api->error);
            return false;
        }
        if ($this->registry->api->result->getSerialNumbersReturn->resultCode === 0 and $this->registry->api->result->getSerialNumbersReturn->errorCode === 0) {
            $this->dispositionState = $this->registry->api->result->getSerialNumbersReturn->dispositionState;
            if ($this->dispositionState == 'S' or $this->dispositionState == 'E') {
                return $this->executeDebit($parameter);
            } elseif ($this->dispositionState == 'O') {
                $this->_customerInfo = $this->registry->language->get('payment_done');
                return true;
            } else {
                if ($this->dispositionState === 'R') {
                    $this->_customerInfo = $this->registry->language->get('payment_invalide');
                } elseif ($this->dispositionState === 'L') {
                    $this->_customerInfo = $this->registry->language->get('payment_cancelled');
                } elseif ($this->dispositionState === 'X') {
                    $this->_customerInfo = $this->registry->language->get('payment_expired');
                } else {
                    $this->_customerInfo = $this->registry->language->get('msg_execute_debit');
                }
                return false;
            }
        } else {
            $this->_customerInfo = $this->registry->language->get('msg_execute_debit');
            $this->setError(
                sprintf(
                    $this->registry->language->get('get_serial_number_error'),
                    $this->registry->api->result->getSerialNumbersReturn->resultCode,
                    $this->registry->api->result->getSerialNumbersReturn->errorCode
                )
            );
            return false;
        }
    }

    /**
     * Ejecuta el débito del pago.
     *
     * @param array $parameter Parámetros necesarios para la ejecución del débito.
     *
     * @return boolean Retorna `true` si el débito se ejecuta correctamente, `false` en caso contrario.
     */
    private function executeDebit($parameter)
    {
        if ( ! $this->registry->api->action('executeDebit', $parameter)) {
            $this->_customerInfo = $this->registry->language->get('msg_execute_debit');
            $this->setError($this->registry->api->error);
            return false;
        }
        if ($this->registry->api->result->executeDebitReturn->resultCode === 0 and $this->registry->api->result->executeDebitReturn->errorCode === 0) {
            $this->_customerInfo = $this->registry->language->get('payment_done');
            return true;
        } else {
            $this->_customerInfo = $this->registry->language->get('msg_execute_debit');
            $this->setError(
                sprintf(
                    $this->registry->language->get('execute_debit_error'),
                    $this->registry->api->result->executeDebitReturn->resultCode,
                    $this->registry->api->result->executeDebitReturn->errorCode
                )
            );
            return false;
        }
    }
}