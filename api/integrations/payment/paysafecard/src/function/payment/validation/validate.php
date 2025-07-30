<?php

/**
 * Este archivo contiene la clase `paymentValidate` que se utiliza para validar parámetros relacionados
 * con pagos en la integración de Paysafecard.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Paysafecard
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Clase `paymentValidate`
 *
 * Esta clase proporciona métodos para validar parámetros de pago, verificar parámetros requeridos
 * y opcionales, y registrar errores en un sistema de depuración.
 */
class paymentValidate
{
    /**
     * Array que almacena los errores encontrados durante la validación.
     *
     * @var array
     */
    public $error = array();

    /**
     * Objeto de registro que contiene dependencias como el idioma y la depuración.
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
     * Objeto que gestiona el idioma utilizado en los mensajes de error.
     *
     * @var object
     */
    protected $_language;

    /**
     * Constructor de la clase.
     *
     * @param object $_registry Objeto de registro que contiene dependencias como el idioma y la depuración.
     */
    public function __construct($_registry)
    {
        $this->_registry = $_registry;
    }

    /**
     * Valida un conjunto de parámetros.
     *
     * Este método recorre un arreglo de parámetros y valida cada uno de ellos
     * utilizando el método `validation`. Si algún parámetro no es válido,
     * se detiene la validación y devuelve `false`.
     *
     * @param array $parameter Arreglo de parámetros a validar.
     *
     * @return boolean Devuelve `true` si todos los parámetros son válidos, de lo contrario `false`.
     */
    public function validate($parameter)
    {
        foreach ($parameter as $key => $value) {
            if ( ! $this->validation($key, $value)) {
                echo $key . '<br />';
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
            case 'shopId':
                if (empty($value)) {
                    if (strlen($value) > 60) {
                        $this->addLog('shopid_oversize', $value);
                        return false;
                    } elseif (strlen($value) < 1) {
                        $this->addLog('shopid_undersize', $value);
                        return false;
                    } else {
                        $this->addLog('shopid_invalid', $value);
                        return false;
                    }
                } else {
                    return true;
                }
                break;
            case 'shopLabel':
                if (empty($value)) {
                    if (strlen($value) > 60) {
                        $this->addLog('shoplabel_oversize', $value);
                        return false;
                    } elseif (strlen($value) < 1) {
                        $this->addLog('shoplabel_undersize', $value);
                        return false;
                    } else {
                        $this->addLog('shoplabel_invalid', $value);
                        return false;
                    }
                } else {
                    return true;
                }
                break;
            case 'mtid':
                if (empty($value)) {
                    if (strlen($value) > 60) {
                        $this->addLog('mtid_oversize', $value);
                        return false;
                    } elseif (strlen($value) < 1) {
                        $this->addLog('mtid_undersize', $value);
                        return false;
                    } else {
                        $this->addLog('mtid_invalid', $value);
                        return false;
                    }
                } else {
                    return true;
                }
                break;
            case 'subId':
                return true;
                break;
            case 'close':
                if ($value != '1' and $value != '0') {
                    $this->addLog('invalid_close', $value, '');
                    return false;
                } else {
                    return true;
                }
                break;
            case 'nokUrl':
                if (empty($value) or strlen($value) < 10) {
                    $this->addLog('invalid_nok_url', 'validate_nokUrl', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            case 'okUrl':
                if (empty($value) or strlen($value) < 10) {
                    $this->addLog('invalid_ok_url', 'validate_okUrl', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            case 'pnUrl':
                if (empty($value) or strlen($value) < 10) {
                    $this->addLog('invalid_pn_url', 'validate_pnUrl', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            case 'minAge':
                if (preg_match('/^ \b[0-9]{1,2}\b$/', $value) != 1) {
                    $this->addLog('min_age_invalide', 'validate_minAge', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            case 'MinKycLevel':
                if ( ! in_array($value, $this->MinKycLevel)) {
                    $this->addLog('min_kyc_level_invalide', 'validate_MinKycLevel', $value);
                    return false;
                } else {
                    return true;
                }
                break;
            case 'restricedCountry':
                if (strlen($value) != 2) {
                    $this->addLog('restricted_country_invalide', 'validate_restricedCountry', $value);
                    return false;
                } elseif (preg_match('/^[A-Z]{2}$/', $value) != 1) {
                    $this->addLog('restricted_country_case', 'validate_restricedCountry', $value);
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
     * Verifica los parámetros requeridos y opcionales para la creación de un pago.
     *
     * @param array $parameter Arreglo de parámetros a verificar.
     *
     * @return array|boolean Devuelve un arreglo con los parámetros válidos o `false` si falta algún parámetro requerido.
     */
    public function checkPaymentParameter($parameter)
    {
        $return = array();
        $required = array(
            'username',
            'password',
            'amount',
            'currency',
            'mtid',
            'merchantClientId',
            'okUrl',
            'nokUrl',
            'pnUrl'
        );
        foreach ($required as $key) {
            if ( ! key_exists($key, $parameter)) {
                $this->error = sprintf($this->_registry->language->get('create_disposition_missing_parameter'), $key);
                return false;
            } elseif (empty($parameter[$key])) {
                $this->error = sprintf($this->_registry->language->get('create_disposition_missing_parameter'), $key);
                return false;
            }
            $return[$key] = $parameter[$key];
        }

        $optional = array(
            'subId',
            'clientIp',
            'dispositionrestrictions',
            'shopId',
            'shoplabel'
        );
        foreach ($optional as $key) {
            $return[$key] = isset($parameter[$key]) ? $parameter[$key] : '';
        }

        return $return;
    }

    /**
     * Verifica los parámetros requeridos y opcionales para la ejecución de un pago.
     *
     * @param array $parameter Arreglo de parámetros a verificar.
     *
     * @return array|boolean Devuelve un arreglo con los parámetros válidos o `false` si falta algún parámetro requerido.
     */
    public function checkExecuteParameter($parameter)
    {
        $return = array();
        $required = array(
            'username',
            'password',
            'amount',
            'currency',
            'mtid',
            'close'
        );
        foreach ($required as $key) {
            if ( ! key_exists($key, $parameter)) {
                $this->error = sprintf($this->_registry->language->get('create_disposition_missing_parameter'), $key);
                return false;
            } elseif (empty($parameter[$key])) {
                $this->error = sprintf($this->_registry->language->get('create_disposition_missing_parameter'), $key);
                return false;
            }
            $return[$key] = $parameter[$key];
        }

        $optional = array(
            'subId'
        );
        foreach ($optional as $key) {
            $return[$key] = isset($parameter[$key]) ? $parameter[$key] : '';
        }

        return $return;
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
}
