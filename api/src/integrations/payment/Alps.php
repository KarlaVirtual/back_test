<?php

namespace Backend\integrations\payment;

use Exception;
use Backend\dto\TransaccionProducto;

/**
 * Clase Alps para gestionar integraciones de pagos.
 *
 * Esta clase maneja la lógica de confirmación de transacciones
 * en estados A = APROBADO Y R = RECHAZADO y utiliza
 * la clase TransaccionProducto para registrar los cambios en el sistema.
 */
class Alps
{

    /**
     * variable que representa el id de la transacción
     *
     * @var string $transaccionId Id de la transacción en el sistema.
     */
    private string $transaccionId;

    /**
     * Estado de la transacción, debe ser A = Aprobado o R = Rechazado
     *
     * @var string Es el estado de la transacción, para ALPS debe ser A O R
     */
    private string $status;

    /**
     * id de la transaccion externa en este caso el numero de canal de la pasarela 
     *
     * @var int Es el Id del canal que representa el tipo de deposito
     */
    private int $externalId;

    /**
     * Procesa la confirmación de la transacción.
     *
     * Según el resultado proporcionado por el proveedor, actualiza el estado
     * de la transacción en el sistema y registra los datos necesarios para auditoría.
     *
     * @param stdClass $data Es de tipo stdClass, contiene la información suministrada por el proveedor
     * 
     * @return mixed Resultado del procesamiento de la transacción.
     */
    public function confirmation($data)
    {
        try {
            $data = json_encode($data);
            $tipo_genera = 'A';

            $transaccionProducto = new TransaccionProducto($this->getTransaccionId());

            switch ($this->getStatus()) {
                case 'A':
                    $comentario = 'Aprobada por Alps';
                    $return = $transaccionProducto->setAprobada($this->getTransaccionId(), $tipo_genera, $this->getStatus(), $comentario, $data, $this->getExternalId());
                    break;

                case 'R':
                    $comentario = 'Rechazada por Alps';
                    $return = $transaccionProducto->setRechazada($this->getTransaccionId(), $tipo_genera, $this->getStatus(), $comentario, $data, $this->getExternalId(), true);
                    break;

                default:
                    $return = "No existe el estado";
            }
            $respuesta = json_decode($return);
            $respuesta->transaccion = $this->getTransaccionId();
            return $respuesta;
        } catch (Exception $err) {
            throw $err;
        }
    }

    /**
     * Obtiene el id de la transacción
     * @return  string 
     */
    public function getTransaccionId(): string
    {
        return $this->transaccionId;
    }

    /**
     * Setea el id de la transacción
     * 
     * @param  string $transaccionId Representa el id de la transacción
     *
     * @return  void
     */
    public function setTransaccionId(string $transaccionId): void
    {
        $this->transaccionId = $transaccionId;
    }

    /**
     * Obtiene el estado de la transacción
     * @return  string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Setea el estado de la transacción
     * @param  string  $status representa el estado de la transaccion
     * @return  void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Retorna el id externo, en este caso el id del canal
     * @return  int 
     */
    public function getExternalId(): int
    {
        return $this->externalId;
    }

    /**
     * Setea el id externo, en este caso el id del canal
     * @param  int  $externalId Representa el id externo
     * 
     * @return  void
     */
    public function setExternalId(int $externalId): void
    {
        $this->externalId = $externalId;
    }
}
