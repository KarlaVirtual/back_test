<?php

/**
 * Este archivo contiene la clase `paymentCancel` que extiende la funcionalidad
 * de `paysafecard_base` para manejar la cancelación de pagos en la integración
 * con Paysafecard.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones\Paysafecard
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Clase `paymentCancel`
 *
 * Esta clase se encarga de inicializar la configuración necesaria para la
 * cancelación de pagos y establecer el idioma si es proporcionado.
 */
class paymentCancel extends paysafecard_base
{
    /**
     * Constructor de la clase `paymentCancel`.
     *
     * Carga la configuración de pagos y establece el idioma si se proporciona.
     *
     * @param boolean|string $lang Idioma a establecer, o `false` si no se especifica.
     */
    public function __construct($lang = false)
    {
        parent::loadConfig('payment');
        if ($lang) {
            $this->setLanguage($lang);
        }
        $this->_customerInfo = $this->registry->language->get('payment_cancelled');
    }
}