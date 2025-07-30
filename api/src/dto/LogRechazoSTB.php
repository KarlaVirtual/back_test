<?php

namespace Backend\dto;

use Backend\mysql\LogRechazoSTBMySqlDAO;

class LogRechazoSTB
{
    public $logId;
    public $usuarioId;
    public $tipo;
    public $tipoId;
    public $transaccion;
    public $transaccionId;
    public $descripcion;
    public $fechaCrea;
    public $fechaModif;

    public function __construct($logId = null) {
        $LogRechazoSTB = null;

        if (!empty($logId)) {
            $LogRechazoSTBMySqlDAO = new LogRechazoSTBMySqlDAO();
            $LogRechazoSTB = $LogRechazoSTBMySqlDAO->load($logId);

            if (empty((array)$LogRechazoSTB)) throw new Exception("No existe " . get_class($this), 300073);
        }

        if (!empty((array) $LogRechazoSTB)) {
            foreach ($LogRechazoSTB as $propidad => $valor) {
                $this->$propidad = $valor;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getLogId()
    {
        return $this->logId;
    }

    /**
     * @param mixed $logId
     */
    public function setLogId($logId): void
    {
        $this->logId = $logId;
    }

    /**
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * @param mixed $usuarioId
     */
    public function setUsuarioId($usuarioId): void
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * @param mixed $tipoId
     */
    public function setTipoId($tipoId): void
    {
        $this->tipoId = $tipoId;
    }

    /**
     * @return mixed
     */
    public function getTransaccion()
    {
        return $this->transaccion;
    }

    /**
     * @param mixed $transaccion
     */
    public function setTransaccion($transaccion): void
    {
        $this->transaccion = $transaccion;
    }

    /**
     * @return mixed
     */
    public function getTransaccionId()
    {
        return $this->transaccionId;
    }

    /**
     * @param mixed $transaccionId
     */
    public function setTransaccionId($transaccionId): void
    {
        $this->transaccionId = $transaccionId;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * @param mixed $fechaCrea
     */
    public function setFechaCrea($fechaCrea): void
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * @return mixed
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * @param mixed $fechaModif
     */
    public function setFechaModif($fechaModif): void
    {
        $this->fechaModif = $fechaModif;
    }

    /** Función retorna un array con los logs de errores solicitados en la validación de condiciones o false en caso de fallar */
    public function logRechazosJackpot (array $errores,string $tipoTransaccion, JackpotInterno $JackpotInterno, UsuarioMandante $UsuarioMandante, ?TransjuegoLog $TransjuegoLog, ?ItTicketEnc $ItTicketEnc) : array | false
    {
        //Parámetro almacena el total de objetos del tipo LogRechazoSTB generados bajo la solicitud
        $totalGeneratedLogs = [];

        //parámetro agrupo cada descripción asignable con el patrón de error que puede ser entregado por la validación de condiciones de los jackpot
        $standardLogTypes = [
            [
                'pattern' => '/^BONDABUSER?/', //Para el error específico BONDABUSER
                'description' => 'BONDABUSER-REJECTEDBET', //Se deja la descripción BONDABUSER-REJECTEDBET
            ]
        ];
        $standardLogTypes = json_decode(json_encode($standardLogTypes));

        //Obteniendo el tipo de transaccion y su ID
        $typeTransaction = null;
        $typeTransactionId = null;
        $casinoGroup = JackpotInterno::getCasinoGroup();
        if (in_array($tipoTransaccion, $casinoGroup)) {
            $typeTransaction = $tipoTransaccion;
            $typeTransactionId = $TransjuegoLog->getTransjuegologId();
        }
        elseif ('SPORTBOOK' == $tipoTransaccion) {
            $typeTransaction = $tipoTransaccion;
            $typeTransactionId = $ItTicketEnc->ticketId;
        }
        if (empty($typeTransactionId) || empty($typeTransaction)) return false;

        foreach ($errores as $error) {
            $currentLogData = null;
            foreach ($standardLogTypes as $log) {
                if (preg_match($log->pattern, $error)) {
                    $currentLogData = $log;
                    break;
                }
            }
            if($currentLogData == null){
                $currentLogData= new \stdClass();
                $currentLogData->description=$error;

            }

            if (!is_null($currentLogData)) {
                $LogRechazoSTB = new LogRechazoSTB();
                $LogRechazoSTB->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                $LogRechazoSTB->setTipo("JACKPOT");
                $LogRechazoSTB->setTipoId($JackpotInterno->jackpotId);
                $LogRechazoSTB->setTransaccion($typeTransaction);
                $LogRechazoSTB->setTransaccionId($typeTransactionId);
                $LogRechazoSTB->setDescripcion($currentLogData->description);
                $LogRechazoSTBMySqlDAO = new LogRechazoSTBMySqlDAO();
                $LogRechazoSTBMySqlDAO->insert($LogRechazoSTB);
                $LogRechazoSTBMySqlDAO->getTransaction()->commit();

                $totalGeneratedLogs[] = $LogRechazoSTB;
            }
        }

        return $totalGeneratedLogs;
    }
}