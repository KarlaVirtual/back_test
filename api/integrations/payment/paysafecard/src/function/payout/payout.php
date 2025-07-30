<?php

/**
 * Este archivo contiene la implementación de la clase `payout`, que extiende
 * la funcionalidad de `paysafecard_base` para manejar operaciones de pago.
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
 * Esta clase se encarga de gestionar las operaciones de pago, incluyendo
 * la validación de parámetros y la configuración del modo de operación.
 */
class payout extends paysafecard_base
{

    /**
     * Constructor de la clase `payout`.
     *
     * Carga la configuración inicial para las operaciones de pago y permite
     * establecer un modo de operación si se proporciona.
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
     * Crea una nueva operación de pago.
     *
     * Valida los parámetros proporcionados, establece credenciales predeterminadas
     * si no se especifican, y verifica los parámetros necesarios para la operación.
     *
     * @param array $parameter Parámetros para la operación de pago.
     *
     * @return boolean `true` si la operación es válida, `false` en caso de error.
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