<?php

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;
/**
 * Clase SpotUpay
 *
 * Esta clase gestiona las operaciones relacionadas con la pasarela de pagos SpotUpay,
 * permitiendo procesar confirmaciones de transacciones y registrar los estados correspondientes
 * en el sistema local. Funciona en conjunto con la clase TransaccionProducto para reflejar
 * los cambios de estado: aprobado, pendiente o rechazado.

 * Esta clase es utilizada principalmente como parte del flujo de confirmación (callback)
 * que SpotUpay ejecuta al finalizar una transacción de pago.
 *
 * @package ninguno
 * @author Karla Ramirez <karla.ramirez@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @date 05.05.25
 *
 * @throws Exception Puede lanzar errores si fallan las instancias de TransaccionProducto o se reciben datos incorrectos.
 */
class SpotUpay
{
    /**
     * Constructor de la clase SpotUpay.
     *
     * Inicializa una instancia de SpotUpay con los datos necesarios para procesar
     * la confirmación de una transacción. Almacena los valores proporcionados como
     * propiedades internas para ser utilizados posteriormente durante la ejecución
     * del método `confirmation()`.
     *
     * @param string $PublicId        Identificador público o referencia proporcionada por SpotUpay.
     * @param string $TransactionId   Identificador de la transacción en el sistema local.
     * @param float  $Amount          Monto de la transacción procesada.
     * @param string $status          Estado actual de la transacción según SpotUpay.
     * @return void
     * @access public
     * @since 05.05.25
     *
     * @throws void Este constructor no lanza excepciones de forma explícita.
     */
    public function __construct($PublicId, $TransactionId, $Amount, $status)
    {
        $this->PublicId = $PublicId;
        $this->TransactionId = $TransactionId;
        $this->Amount = $Amount;
        $this->status = $status;
    }

    /**
     * Procesa la confirmación de una transacción recibida desde la pasarela SpotUpay.
     *
     * Este método evalúa el estado de la transacción (`PENDING`, `OK`, `DECLINE`) y actualiza
     * la información correspondiente en el sistema a través de la clase TransaccionProducto.
     * Cada estado se registra con su respectivo comentario, tipo y auditoría en formato JSON.
     *
     * @param object $data Objeto JSON recibido desde SpotUpay con los datos de la transacción.
     *
     * @return string Resultado de la operación, retornado por los métodos de TransaccionProducto:
     *                `setPendiente()`, `setAprobada()` o `setRechazada()`.
     * @throws Exception Si ocurre un error al procesar la transacción o instanciar TransaccionProducto.
     */
    public function confirmation($data)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->TransactionId;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($data);

        switch ($this->status) {
            case 'PENDING':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por SpotUpay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "OK":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por SpotUpay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->PublicId);
                return $return;

                break;

            case
            "DECLINE":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por SpotUpay ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }

    }

}
