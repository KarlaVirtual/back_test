<?php

/**
 * Este archivo contiene la clase `paymentCancel` que extiende la funcionalidad
 * de `paysafecard_base` para manejar la cancelación de pagos en la integración
 * de Paysafecard.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Clase `paymentCancel`
 *
 * Esta clase se encarga de gestionar la lógica relacionada con la cancelación
 * de pagos en la integración de Paysafecard. Extiende la funcionalidad base
 * proporcionada por `paysafecard_base`.
 */
class paymentCancel extends paysafecard_base
{
    /**
     * Constructor de la clase `paymentCancel`.
     *
     * Carga la configuración de pagos y establece el idioma si se proporciona.
     *
     * @param boolean|string $lang Idioma a establecer (opcional).
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