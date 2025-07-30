<?php

    namespace Backend\dto;

    use Backend\mysql\UsuarioVerificacionMySqlDAO;
    use Exception;

    /** 
     * Objeto que representa la tabla 'usuario_verificacion'.
     * 
     * @author      Desconocido
     * @category No 
     * @package No
     * @version     1.0
     * @since       Desconocido
     */
    class UsuarioVerificacion {

        /**
         * @var string Representación de la columna 'usuverificacion_id' en la tabla 'usuario_verificacion'
         */
        public $usuverificacionId;

        /**
         * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_verificacion'
         */
        public $usuarioId;

        /**
         * @var string Representación de la columna 'mandante' en la tabla 'usuario_verificacion'
         */
        public $mandante;

        /**
         * @var string Representación de la columna 'pais_id' en la tabla 'usuario_verificacion'
         */
        public $paisId;

        /**
         * @var string Representación de la columna 'tipo' en la tabla 'usuario_verificacion'
         */
        public $tipo;

        /**
         * @var string Representación de la columna 'estado' en la tabla 'usuario_verificacion'
         */
        public $estado;

        /**
         * @var string Representación de la columna 'observacion' en la tabla 'usuario_verificacion'
         */
        public $observacion;

        /**
         * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_verificacion'
         */
        public $usucreaId;

        /**
         * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_verificacion'
         */
        public $usumodifId;

        /**
         * @var string Representación de la columna 'fecha_crea' en la tabla 'usuario_verificacion'
         */
        public $fechaCrea;

        /**
         * @var string Representación de la columna 'fecha_modif' en la tabla 'usuario_verificacion'
         */
        public $fechaModif;

        /**
         * @var string Representación de la columna 'clasificador_id' en la tabla 'usuario_verificacion'
         */
        public $clasificadorId;

        /**
         * Constructor de la clase UsuarioVerificacion.
         *
         * @param string $usuverificacionId ID de la verificación del usuario.
         * @param string $usuarioId ID del usuario.
         * @param string $estado Estado de la verificación.
         * @param string $tipo Tipo de verificación.
         * @param string $clasificadorId ID del clasificador.
         *
         * @throws Exception Si no se encuentra la verificación del usuario.
         */
        public function __construct($usuverificacionId = '', $usuarioId = '', $estado = '', $tipo = '') {
            if(!empty($usuverificacionId)) {
                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $usuarioVerificacion = $UsuarioVerificacionMySqlDAO->load($usuverificacionId);
                if($usuarioVerificacion != '') {
                    $this->setUsuverificacionId($usuarioVerificacion->getUsuverificacionId());
                    $this->setUsuarioId($usuarioVerificacion->getUsuarioId());
                    $this->setMandante($usuarioVerificacion->getMandante());
                    $this->setPaisId($usuarioVerificacion->getPaisId());
                    $this->setTipo($usuarioVerificacion->getTipo());
                    $this->setEstado($usuarioVerificacion->getEstado());
                    $this->setObservacion($usuarioVerificacion->getObservacion());
                    $this->setUsucreaId($usuarioVerificacion->getUsucreaId());
                    $this->setUsumodifId($usuarioVerificacion->getUsumodifId());
                    $this->setFechaCrea($usuarioVerificacion->getFechaCrea());
                    $this->setFechaModif($usuarioVerificacion->getFechaModif());
                    $this->setClasificadorId($usuarioVerificacion->getClasificadorId());
                } else throw new Exception('No existe', 14);
            } elseif(!empty($usuarioId) && !empty($tipo) && !empty($estado)) {
                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $usuarioVerificacion = $UsuarioVerificacionMySqlDAO->loadByUsuarioTipoEstado($usuarioId, $tipo, $estado);
                if($usuarioVerificacion != '') {
                    $this->setUsuverificacionId($usuarioVerificacion->getUsuverificacionId());
                    $this->setUsuarioId($usuarioVerificacion->getUsuarioId());
                    $this->setMandante($usuarioVerificacion->getMandante());
                    $this->setPaisId($usuarioVerificacion->getPaisId());
                    $this->setTipo($usuarioVerificacion->getTipo());
                    $this->setEstado($usuarioVerificacion->getEstado());
                    $this->setObservacion($usuarioVerificacion->getObservacion());
                    $this->setUsucreaId($usuarioVerificacion->getUsucreaId());
                    $this->setUsumodifId($usuarioVerificacion->getUsumodifId());
                    $this->setFechaCrea($usuarioVerificacion->getFechaCrea());
                    $this->setFechaModif($usuarioVerificacion->getFechaModif());
                    $this->setClasificadorId($usuarioVerificacion->getClasificadorId());
                } else throw new Exception('No existe', 14);
            } elseif(!empty($usuarioId) && !empty($tipo)) {
                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $usuarioVerificacion = $UsuarioVerificacionMySqlDAO->loadByTipo($usuarioId, $tipo);
                if(!empty($usuarioVerificacion)) {
                    $this->setUsuverificacionId($usuarioVerificacion->getUsuverificacionId());
                    $this->setUsuarioId($usuarioVerificacion->getUsuarioId());
                    $this->setMandante($usuarioVerificacion->getMandante());
                    $this->setPaisId($usuarioVerificacion->getPaisId());
                    $this->setTipo($usuarioVerificacion->getTipo());
                    $this->setEstado($usuarioVerificacion->getEstado());
                    $this->setObservacion($usuarioVerificacion->getObservacion());
                    $this->setUsucreaId($usuarioVerificacion->getUsucreaId());
                    $this->setUsumodifId($usuarioVerificacion->getUsumodifId());
                    $this->setFechaCrea($usuarioVerificacion->getFechaCrea());
                    $this->setFechaModif($usuarioVerificacion->getFechaModif());
                    $this->setClasificadorId($usuarioVerificacion->getClasificadorId());
                } else throw new Exception('No existe', 14);
            } elseif(!empty($usuarioId) && !empty($estado)) {
                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $usuarioVerificacion = $UsuarioVerificacionMySqlDAO->loadByUsuarioEstado($usuarioId, $estado);
                if($usuarioVerificacion != '') {
                    $this->setUsuverificacionId($usuarioVerificacion->getUsuverificacionId());
                    $this->setUsuarioId($usuarioVerificacion->getUsuarioId());
                    $this->setMandante($usuarioVerificacion->getMandante());
                    $this->setPaisId($usuarioVerificacion->getPaisId());
                    $this->setEstado($usuarioVerificacion->getEstado());
                    $this->setObservacion($usuarioVerificacion->getObservacion());
                    $this->setUsucreaId($usuarioVerificacion->getUsucreaId());
                    $this->setUsumodifId($usuarioVerificacion->getUsumodifId());
                    $this->setFechaCrea($usuarioVerificacion->getFechaCrea());
                    $this->setFechaModif($usuarioVerificacion->getFechaModif());
                    $this->setClasificadorId($usuarioVerificacion->getClasificadorId());
                } else throw new Exception('No existe', 14);
            }
            elseif(!empty($usuarioId) && !empty($clasificadorId)) {
                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $usuarioVerificacion = $UsuarioVerificacionMySqlDAO->loadByUsuarioClasificador($usuarioId, $clasificadorId);
                if($usuarioVerificacion != '') {
                    $this->setUsuverificacionId($usuarioVerificacion->getUsuverificacionId());
                    $this->setUsuarioId($usuarioVerificacion->getUsuarioId());
                    $this->setMandante($usuarioVerificacion->getMandante());
                    $this->setPaisId($usuarioVerificacion->getPaisId());
                    $this->setEstado($usuarioVerificacion->getEstado());
                    $this->setObservacion($usuarioVerificacion->getObservacion());
                    $this->setUsucreaId($usuarioVerificacion->getUsucreaId());
                    $this->setUsumodifId($usuarioVerificacion->getUsumodifId());
                    $this->setFechaCrea($usuarioVerificacion->getFechaCrea());
                    $this->setFechaModif($usuarioVerificacion->getFechaModif());
                    $this->setClasificadorId($usuarioVerificacion->getClasificadorId());
                } else throw new Exception('No existe', 14);
            }
        }

        /**
         * Obtiene una colección de verificación de usuario personalizada basada en los parámetros proporcionados.
         *
         * @param string $select Campos a seleccionar en la consulta.
         * @param string $sidx Índice de ordenación.
         * @param string $sord Orden de clasificación (ascendente o descendente).
         * @param int $start Índice de inicio para la paginación.
         * @param int $limit Límite de registros a devolver.
         * @param array $filters Filtros a aplicar en la consulta.
         * @param bool $searchOn Indica si la búsqueda está activada.
         * @param string $grouping (Opcional) Agrupamiento a aplicar en la consulta.
         * 
         * @return array Datos obtenidos de la consulta.
         * @throws Exception Si no existen datos, lanza una excepción con el mensaje 'No existe' y el código 14.
         */
        public function getUsuarioVerificacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="") {
            $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
            $data = $UsuarioVerificacionMySqlDAO->queryUsuarioVerificacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

            if($data === '') throw new Exception('No existe', 14);

            return $data;
        }

/**
         * Obtiene el ID de la verificación del usuario.
         *
         * @return string
         */
        public function getUsuverificacionId() {
            return $this->usuverificacionId;
        }

        /**
         * Establece el ID de la verificación del usuario.
         *
         * @param string $usuverificacionId
         */
        public function setUsuverificacionId($usuverificacionId) {
            $this->usuverificacionId = $usuverificacionId;
        }

        /**
         * Obtiene el ID del usuario.
         *
         * @return string
         */
        public function getUsuarioId() {
            return $this->usuarioId;
        }

        /**
         * Establece el ID del usuario.
         *
         * @param string $usuarioId
         */
        public function setUsuarioId($usuarioId) {
            $this->usuarioId = $usuarioId;
        }

        /**
         * Obtiene el mandante.
         *
         * @return string
         */
        public function getMandante() {
            return $this->mandante;
        }

        /**
         * Establece el mandante.
         *
         * @param string $mandante
         */
        public function setMandante($mandante) {
            $this->mandante = $mandante;
        }

        /**
         * Obtiene el ID del país.
         *
         * @return string
         */
        public function getPaisId() {
            return $this->paisId;
        }

        /**
         * Establece el ID del país.
         *
         * @param string $paisId
         */
        public function setPaisId($paisId) {
            $this->paisId = $paisId;
        }

        /**
         * Obtiene el tipo de verificación.
         *
         * @return string
         */
        public function getTipo() {
            return $this->tipo;
        }

        /**
         * Establece el tipo de verificación.
         *
         * @param string $tipo
         */
        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        /**
         * Obtiene el estado de la verificación.
         *
         * @return string
         */
        public function getEstado() {
            return $this->estado;
        }

        /**
         * Establece el estado de la verificación.
         *
         * @param string $estado
         */
        public function setEstado($estado) {
            $this->estado = $estado;
        }

        /**
         * Obtiene la observación.
         *
         * @return string
         */
        public function getObservacion() {
            return $this->observacion;
        }

        /**
         * Establece la observación.
         *
         * @param string $observacion
         */
        public function setObservacion($observacion) {
            $this->observacion = $observacion;
        }

        /**
         * Obtiene el ID del usuario que creó el registro.
         *
         * @return string
         */
        public function getUsucreaId() {
            return $this->usucreaId;
        }

        /**
         * Establece el ID del usuario que creó el registro.
         *
         * @param string $usucreaId
         */
        public function setUsucreaId($usucreaId) {
            $this->usucreaId = $usucreaId;
        }

        /**
         * Obtiene el ID del usuario que modificó el registro.
         *
         * @return string
         */
        public function getUsumodifId() {
            return $this->usumodifId;
        }

        /**
         * Establece el ID del usuario que modificó el registro.
         *
         * @param string $usumodifId
         */
        public function setUsumodifId($usumodifId) {
            $this->usumodifId = $usumodifId;
        }

        /**
         * Obtiene la fecha de creación del registro.
         *
         * @return string
         */
        public function getFechaCrea() {
            return $this->fechaCrea;
        }

        /**
         * Establece la fecha de creación del registro.
         *
         * @param string $fechaCrea
         */
        public function setFechaCrea($fechaCrea) {
            $this->fechaCrea = $fechaCrea;
        }

        /**
         * Obtiene la fecha de modificación del registro.
         *
         * @return string
         */
        public function getFechaModif() {
            return $this->fechaModif;
        }

        /**
         * Establece la fecha de modificación del registro.
         *
         * @param string $fechaModif
         */
        public function setFechaModif($fechaModif) {
            $this->fechaModif = $fechaModif;
        }

        /**
         * Obtiene el ID del clasificador.
         *
         * @return string
         */
        public function getClasificadorId() {
            return $this->clasificadorId;
        }

        /**
         * Establece el ID del clasificador.
         *
         * @param string $clasificadorId
         */
        public function setClasificadorId($clasificadorId) {
            $this->clasificadorId = $clasificadorId;
        }


    }
?>