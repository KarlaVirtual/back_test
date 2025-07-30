<?php

/**
 * Este archivo contiene la implementación de la clase `payout`,
 * que extiende la funcionalidad de `paysafecard_base` para manejar
 * operaciones de pago y validación de parámetros relacionados.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase `payout`
 *
 * Proporciona métodos para realizar pagos y validar parámetros
 * utilizando la configuración de paysafecard.
 */
class payout extends paysafecard_base
{

    /**
     * Constructor de la clase `payout`.
     *
     * Carga la configuración inicial para la funcionalidad de pagos
     * y establece el modo si se proporciona.
     *
     * @param boolean $mode Modo de operación (opcional).
     */
    public function __construct($mode = false)
    {
        parent::loadConfig('payout');
        if ($mode) {
            $this->setMode($mode);
        }
    }

    /**
     * Crea un nuevo pago.
     *
     * Valida los parámetros proporcionados y realiza las operaciones
     * necesarias para iniciar un pago. Si los parámetros no son válidos,
     * establece un error y devuelve `false`.
     *
     * @param array $parameter Parámetros necesarios para el pago,
     *                         incluyendo `username` y `password`.
     *
     * @return boolean `true` si el pago se inicia correctamente,
     *              `false` en caso de error.
     */
    public function newPayout($parameter)
    {
        $validate = new payoutValidate($this->registry);
        if ( ! isset($parameter['username']) or ! isset($parameter['password'])) {
            $parameter['username'] = $this->registry->config->get('Merchant')['username'];
            $parameter['password'] = $this->registry->config->get('Merchant')['password'];
        }
        if ( ! $validate->validate($parameter)) {
            $this->setError($this->registry->api->error);
            return false;
        }
        $parameter = $validate->checkPayoutParameter($parameter);
        if ( ! $parameter) {
            $this->setError($this->registry->api->error);
            return false;
        }
    }

}