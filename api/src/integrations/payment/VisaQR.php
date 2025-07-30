<?php

/**
 * Clase VisaQR para manejar integraciones de pagos con Visa QR.
 *
 * Esta clase permite realizar la confirmación de transacciones realizadas
 * a través del sistema de pagos Visa QR, manejando tanto transacciones
 * aprobadas como rechazadas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Exception;
use Backend\dto\Usuario;
use Backend\dto\TransaccionProducto;

/**
 * Clase que representa la integración con el sistema de pagos Visa QR.
 *
 * Proporciona métodos para manejar transacciones, incluyendo la confirmación
 * de pagos aprobados o rechazados.
 */
class VisaQR
{
    /**
     * Token de sesión del usuario.
     *
     * @var string
     */
    private $sessionToken;

    /**
     * Monto de la transacción.
     *
     * @var float
     */
    private $amount;

    /**
     * Token de la transacción.
     *
     * @var string
     */
    private $transactionToken;

    /**
     * ID del producto de la transacción.
     *
     * @var string
     */
    private $transproductoId;

    /**
     * ID del documento asociado a la transacción.
     *
     * @var string
     */
    private $documento_id;

    /**
     * Constructor de la clase VisaQR.
     *
     * @param string $sessionToken     Token de sesión del usuario.
     * @param string $transactionToken Token de la transacción.
     * @param float  $amount           Monto de la transacción.
     * @param string $transproductoId  ID del producto de la transacción (opcional).
     * @param string $documentId       ID del documento asociado (opcional, por defecto "0").
     */
    public function __construct($sessionToken, $transactionToken, $amount, $transproductoId = "", $documentId = "0")
    {
        $this->sessionToken = $sessionToken;
        $this->transactionToken = $transactionToken;
        $this->amount = $amount;
        $this->transproductoId = $transproductoId;
        $this->documento_id = $documentId;
    }

    /**
     * Confirma el estado de una transacción basada en la respuesta del proveedor.
     *
     * Este método procesa la respuesta del proveedor de pagos y actualiza
     * el estado de la transacción en el sistema, ya sea como aprobada o rechazada.
     *
     * @param string $response Respuesta del proveedor en formato JSON.
     * @param float  $valorT   Valor esperado de la transacción.
     *
     * @return object|null Devuelve un objeto con información del usuario si la transacción es aprobada.
     *
     * @throws Exception Si el valor de la transacción no coincide con el valor esperado.
     */
    public function confirmation($response, $valorT)
    {

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->sessionToken;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($response);

        $code = '';

        if (json_decode($response)->actionCode != '') {
            $code = json_decode($response)->actionCode;
        } else {
            $code = '';
        }

        switch ($code) {

            case "000":
                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por VisaQR ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                if ($TransaccionProducto->valor != $valorT) {

                    throw new Exception("Valor diferente al real", "01");
                }
                $resp = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                $Usuario = new Usuario($TransaccionProducto->getUsuarioId());

                $array = array(
                    "name" => $Usuario->nombre
                );

                return json_decode(json_encode($array));

                break;

            default:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por VisaQR ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;
        }
    }
}
