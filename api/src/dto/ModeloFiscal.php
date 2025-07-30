<?php

    namespace Backend\dto;
    use Backend\mysql\ModeloFiscalMySqlDAO;

    class ModeloFiscal {
        /** @var mixed Representación del campo 'modelofiscal_id' en la tabla 'ModeloFiscal' */
        private $modeloFiscalId;

        /** @var mixed Representación del campo 'tipo' en la tabla 'ModeloFiscal' */
        private $tipo;

        /** @var mixed Representación del campo 'valor' en la tabla 'ModeloFiscal' */
        private $valor;

        /** @var mixed Representación del campo 'mandante' en la tabla 'ModeloFiscal' */
        private $mandante;

        /** @var mixed Representación del campo 'pais_id' en la tabla 'ModeloFiscal' */
        private $paisId;

        /** @var mixed Representación del campo 'mes' en la tabla 'ModeloFiscal' */
        private $mes;

        /** @var mixed Representación del campo 'anio' en la tabla 'ModeloFiscal' */
        private $anio;

        /** @var mixed Representación del campo 'estado' en la tabla 'ModeloFiscal' */
        private $estado;

        /** @var mixed Representación del campo 'usucrea_id' en la tabla 'ModeloFiscal' */
        private $usucreaId;

        /** @var mixed Representación del campo 'usumodif_id' en la tabla 'ModeloFiscal' */
        private $usumodifId;

        /** @var mixed Representación del campo 'ciudad_id' en la tabla 'ModeloFiscal' */
        private $ciudadId;

        /** @var mixed Representación del campo 'fecha_crea' en la tabla 'ModeloFiscal' */
        private $fechaCrea;

        /** @var mixed Representación del campo 'fecha_modif' en la tabla 'ModeloFiscal' */
        private $fechaModif;


        /**
         * Constructor de la clase ModeloFiscal.
         *
         * @param int|null $modeloFiscalId ID del modelo fiscal (opcional).
         * @param string $tipo Tipo del modelo fiscal.
         * @param string $mandante Mandante del modelo fiscal.
         * @param string $paisId ID del país.
         * @param string $mes Mes del modelo fiscal.
         * @param string $year Año del modelo fiscal.
         * @param string $estado Estado del modelo fiscal (por defecto 'A').
         * 
         * @throws \Exception Si no existe el modelo fiscal con los parámetros proporcionados.
         */
        public function __construct($modeloFiscalId = null, $tipo = '', $mandante = '', $paisId = '', $mes = '', $year = '', $estado = 'A') {
            if($tipo !== '' && $mandante !== '' && $paisId !== '' && $mes !== '' && $year !== '') {
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO();
                $ModeloFiscal = $ModeloFiscalMySqlDAO->loadByTipoMandantePaisEstado($tipo, $mandante, $paisId, $mes, $year, $estado);

                if($ModeloFiscal != '') {
                    $this->modeloFiscalId = $ModeloFiscal->getModeloFiscalId();
                    $this->tipo = $ModeloFiscal->getTipo();
                    $this->valor = $ModeloFiscal->getValor();
                    $this->mandante = $ModeloFiscal->getMandante();
                    $this->paisId = $ModeloFiscal->getPaisId();
                    $this->mes = $ModeloFiscal->getMes();
                    $this->anio = $ModeloFiscal->getAnio();
                    $this->estado = $ModeloFiscal->getEstado();
                    $this->usucreaId = $ModeloFiscal->getUsucreaId();
                    $this->usumodifId = $ModeloFiscal->getUsumodifId();
                    $this->ciudadId = $ModeloFiscal->getCiudadId();
                    $this->fechaCrea = $ModeloFiscal->getFechaCrea();
                    $this->fechaModif = $ModeloFiscal->getFechaModif();
                } else {
                    throw new \Exception('No existe' . get_class($this), 34);
                }
            }
        }

        /**
         * Establece el ID del modelo fiscal.
         *
         * @param mixed $modeloFiscalId
         */
        public function setModeloFiscalId($modeloFiscalId) {
            $this->modeloFiscalId = $modeloFiscalId;
        }

        /**
         * Obtiene el ID del modelo fiscal.
         *
         * @return mixed
         */
        public function getModeloFiscalId() {
            return $this->modeloFiscalId;
        }

        /**
         * Establece el tipo.
         *
         * @param mixed $tipo
         */
        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        /**
         * Obtiene el tipo.
         *
         * @return mixed
         */
        public function getTipo() {
            return $this->tipo;
        }

        /**
         * Establece el valor.
         *
         * @param mixed $valor
         */
        public function setValor($valor) {
            $this->valor = $valor;
        }

        /**
         * Obtiene el valor.
         *
         * @return mixed
         */
        public function getValor() {
            return $this->valor;
        }

        /**
         * Establece el mandante.
         *
         * @param mixed $mandante
         */
        public function setMandante($mandante) {
            $this->mandante = $mandante;
        }

        /**
         * Obtiene el mandante.
         *
         * @return mixed
         */
        public function getMandante() {
            return $this->mandante;
        }

        /**
         * Establece el ID del país.
         *
         * @param mixed $paisId
         */
        public function setPaisId($paisId) {
            $this->paisId = $paisId;
        }

        /**
         * Obtiene el ID del país.
         *
         * @return mixed
         */
        public function getPaisId() {
            return $this->paisId;
        }

        /**
         * Establece el mes.
         *
         * @param mixed $mes
         */
        public function setMes($mes) {
            $this->mes = $mes;
        }

        /**
         * Obtiene el mes.
         *
         * @return mixed
         */
        public function getMes() {
            return $this->mes;
        }

        /**
         * Establece el año.
         *
         * @param mixed $anio
         */
        public function setAnio($anio) {
            $this->anio = $anio;
        }

        /**
         * Obtiene el año.
         *
         * @return mixed
         */
        public function getAnio() {
            return $this->anio;
        }

        /**
         * Establece el estado.
         *
         * @param mixed $estado
         */
        public function setEstado($estado) {
            $this->estado = $estado;
        }

        /**
         * Obtiene el estado.
         *
         * @return mixed
         */
        public function getEstado() {
            return $this->estado;
        }

        /**
         * Establece el ID del usuario creador.
         *
         * @param mixed $usucreaId
         */
        public function setUsucreaId($usucreaId) {
            $this->usucreaId = $usucreaId;
        }

        /**
         * Obtiene el ID del usuario creador.
         *
         * @return mixed
         */
        public function getUsucreaId() {
            return $this->usucreaId;
        }

        /**
         * Establece el ID del usuario modificador.
         *
         * @param mixed $usumodifId
         */
        public function setUsumodifId($usumodifId) {
            $this->usumodifId = $usumodifId;
        }

        /**
         * Obtiene el ID del usuario modificador.
         *
         * @return mixed
         */
        public function getUsumodifId() {
            return $this->usumodifId;
        }

        /**
         * Establece el ID de la ciudad.
         *
         * @param mixed $ciudadId
         */
        public function setCiudadId($ciudadId) {
            $this->ciudadId = $ciudadId;
        }

        /**
         * Obtiene el ID de la ciudad.
         *
         * @return mixed
         */
        public function getCiudadId() {
            return $this->ciudadId;
        }

        /**
         * Establece la fecha de creación.
         *
         * @param mixed $fechaCrea
         */
        public function setFechaCrea($fechaCrea) {
            $this->fechaCrea = $fechaCrea;
        }

        /**
         * Obtiene la fecha de creación.
         *
         * @return mixed
         */
        public function getFechaCrea() {
            return $this->fechaCrea;
        }

        /**
         * Establece la fecha de modificación.
         *
         * @param mixed $fechaModif
         */
        public function setFechaModif($fechaModif) {
            $this->fechaModif = $fechaModif;
        }

        /**
         * Obtiene la fecha de modificación.
         *
         * @return mixed
         */
        public function getFechaModif() {
            return $this->fechaModif;
        }
        

        public function getModeloFiscalCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $MandanteDetalleMySqlDAO = new ModeloFiscalMySqlDAO();
    
            $model = $MandanteDetalleMySqlDAO->queryModeloFiscalCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);
    
            if($model === '') throw new \Exception('No existe ' . get_class($this), 34);
            
            return $model;
        }
        
    }
?>