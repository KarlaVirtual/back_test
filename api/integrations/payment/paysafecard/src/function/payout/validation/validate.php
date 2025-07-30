<?php

/**
 * Este archivo contiene la clase `payoutValidate` que se utiliza para validar parámetros
 * relacionados con pagos y realizar registros de errores en caso de validaciones fallidas.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase `payoutValidate`
 *
 * Proporciona métodos para validar parámetros de pago, registrar errores y verificar
 * parámetros obligatorios para la creación de pagos.
 */
class payoutValidate
{
    /**
     * Arreglo que almacena los errores de validación.
     *
     * @var array
     */
    public $error = array();

    /**
     * Objeto de registro utilizado para acceder a configuraciones y dependencias.
     *
     * @var object
     */
    protected $_registry;

    /**
     * Indicador para habilitar o deshabilitar el modo de depuración.
     *
     * @var boolean
     */
    protected $_debug = false;

    /**
     * Objeto para manejar los mensajes de idioma.
     *
     * @var object
     */
    protected $_language;

    /**
     * Constructor de la clase.
     *
     * @param object $_registry Objeto de registro utilizado para acceder a configuraciones y dependencias.
     */
    public function __construct($_registry)
    {
        $this->_registry = $_registry;
    }

    /**
     * Valida un conjunto de parámetros.
     *
     * @param array $parameter Arreglo de parámetros a validar.
     *
     * @return boolean Devuelve `true` si todos los parámetros son válidos, de lo contrario `false`.
     */
    public function validate($parameter)
    {
        foreach ($parameter as $key => $value) {
            if ( ! $this->validation($key, $value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Realiza la validación de un parámetro específico según su tipo.
     *
     * @param string $type  Tipo del parámetro a validar.
     * @param mixed  $value Valor del parámetro a validar.
     *
     * @return boolean Devuelve `true` si el parámetro es válido, de lo contrario `false`.
     */
    private function validation($type = '', $value)
    {
        if ($type == '' && empty($value)) {
            $this->addLog('error_validation', '>>empty<<');
            return false;
        }
        switch ($type) {
            case 'username':
                if (empty($value)) {
                    $this->addLog('username_empty', $value);
                    return false;
                } elseif (strlen($value) <= '3') {
                    $this->addLog('username_length', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            case 'password':
                if (empty($value)) {
                    $this->addLog('password_empty', $value);
                    return false;
                } elseif (strlen($value) <= '5') {
                    $this->addLog('passwor_length', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            case 'amount':
                if ($value == '') {
                    $this->addLog('empty_amount');
                    return false;
                } elseif (strlen($value) <= '3') {
                    $this->addLog('wrong_amount', $value);
                    return false;
                } elseif ((strpos($value, ',') !== false) or (strpos($value, '.') === false)) {
                    $this->addLog('dot_amount', $value);
                    return false;
                } else {
                    $amountParts = explode('.', $value);
                    if ( ! isset($amountParts[1]) or strlen($amountParts[1]) != 2) {
                        $this->addLog('wrong_amount', $value);
                        return false;
                    } else {
                        return true;
                    }
                }
                break;
            case 'ptid':
                if (strlen($value) > 60) {
                    $this->addLog('ptid_oversize', $value);
                    return false;
                } elseif (strlen($value) < 1) {
                    $this->addLog('ptid_undersize', $value);
                    return false;
                }
                return true;
                break;
            case 'customerIdType':
                if (empty($value)) {
                    $this->addLog('empty_customer_id_type', $value);
                    return false;
                }
                $allowedTypes = array('ACCOUNT', 'PHONE', 'E-MAIL');
                if ( ! in_array($value, $allowedTypes)) {
                    $this->addLog('invalide_customer_id_type', $value);
                    return false;
                }
                return true;
                break;
            case 'customerId':
                if (strlen($value) > 90) {
                    $this->addLog('customer_id_oversize', $value);
                    return false;
                } elseif (strlen($value) < 5) {
                    $this->addLog('customer_id_undersize', $value);
                    return false;
                }
                return true;
                break;
            case 'merchantClientId':
                if (empty($value)) {
                    $this->addLog('invalid_client_id', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            case 'currency':
                if (strlen($value) != '3') {
                    $this->addLog('wrong_currency', $value);
                    return false;
                } elseif (preg_match('/^[A-Z]{3}$/', $value) != 1) {
                    $this->addLog('wrong_currency_case', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            default:
                if ($type == '') {
                    $this->addLog('error_validation_type');
                    return false;
                } else {
                    $this->addLog('error_validation');
                    return false;
                }
        }
    }

    /**
     * Registra un mensaje de error en el sistema de depuración.
     *
     * @param string $key  Clave del mensaje de error.
     * @param mixed  $data Datos adicionales relacionados con el error.
     *
     * @return void
     */
    private function addLog($key, $data = '')
    {
        if ( ! $this->_debug) {
            $this->_debug = new debug($this->_registry);
            $this->_language = $this->_registry->language;
        }
        $msg = $this->_language->get($key);
        $this->error = $msg;
        $this->_debug->_debug($msg, $data);
    }

    /**
     * Verifica que todos los parámetros obligatorios para un pago estén presentes y no estén vacíos.
     *
     * @param array $parameter Arreglo de parámetros a verificar.
     *
     * @return array|false Devuelve un arreglo con los parámetros si son válidos, de lo contrario `false`.
     */
    public function checkPayoutParameter($parameter)
    {
        $return = array();
        $required = array(
            'username',
            'password',
            'amount',
            'currency',
            'ptid',
            'merchantClientId',
            'customerIdType',
            'customerId',
            'validationOnly',
            'utcOffset',
        );
        foreach ($required as $key) {
            if ( ! array_key_exists($key, $parameter)) {
                $this->error = sprintf($this->_registry->language->get('create_payout_missing_parameter'), $key);
                return false;
            } elseif (empty($parameter[$key])) {
                $this->error = sprintf($this->_registry->language->get('create_payout_missing_parameter'), $key);
                return false;
            }
            $return[$key] = $parameter[$key];
        }
        return $return;
    }
}
