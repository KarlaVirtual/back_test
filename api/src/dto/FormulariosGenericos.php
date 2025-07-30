<?php
    namespace Backend\dto;
    use Backend\mysql\FormulariosGenericosMySqlDAO;
    use Exception;

    class FormulariosGenericos {
        /** Representación de la columna 'formGenerico_id' de la tabla FormulariosGenericos */
        private $formGenericoId;

        /** Representación de la columna 'form_data' de la tabla FormulariosGenericos */
        private $formData;

        /** Representación de la columna 'usuario_id' de la tabla FormulariosGenericos */
        private $usuarioId;

        /** Representación de la columna 'diligenciado' de la tabla FormulariosGenericos */
        private $diligenciado;

        /** Representación de la columna 'anio' de la tabla FormulariosGenericos */
        private $anio;

        /** Representación de la columna 'mandante' de la tabla FormulariosGenericos */
        private $mandante;

        /** Representación de la columna 'pais_id' de la tabla FormulariosGenericos */
        private $paisId;

        /** Representación de la columna 'tipo' de la tabla FormulariosGenericos */
        private $tipo;

        /** Representación de la columna 'usucrea_id' de la tabla FormulariosGenericos */
        private $usucreaId;

        /** Representación de la columna 'usumodif_id' de la tabla FormulariosGenericos */
        private $usumodifId;

        /** Representación de la columna 'fecha_crea' de la tabla FormulariosGenericos */
        private $fechaCrea;

        /** Representación de la columna 'fecha_modif' de la tabla FormulariosGenericos */
        private $fechaModif;

        /**
         * Constructor de la clase FormulariosGenericos.
         *
         * @param string $formGenericoId ID del formulario genérico.
         * @param string $usuarioId ID del usuario.
         * @param string $mandante Mandante del formulario.
         * @param string $anio Año del formulario.
         * @param string $paisId ID del país.
         * @param string $tipo Tipo del formulario.
         * 
         * @throws Exception Si no existe el formulario genérico.
         */
        public function __construct($formGenericoId = '', $usuarioId = '', $mandante = '', $anio = '', $paisId = '', $tipo = '') {
            if(!empty($formGenericoId)) {
                $FormulariosGenericosMySqlDAO = new FormulariosGenericosMySqlDAO();
                $FormulariosGenericos = $FormulariosGenericosMySqlDAO->load($formGenericoId);

                if($FormulariosGenericos != '') {
                    $this->setformGenericoId($FormulariosGenericos->getFormGenericoId());
                    $this->setFormData($FormulariosGenericos->getFormData());
                    $this->setUsuarioId($FormulariosGenericos->getUsuarioId());
                    $this->setDiligenciado($FormulariosGenericos->getDiligenciado());
                    $this->setAnio($FormulariosGenericos->getAnio());
                    $this->setMandante($FormulariosGenericos->getMandante());
                    $this->setPaisId($FormulariosGenericos->getPaisId());
                    $this->setTipo($FormulariosGenericos->getTipo());
                    $this->setUsucreaId($FormulariosGenericos->getUsucreaId());
                    $this->setUsumodifId($FormulariosGenericos->getUsumodifId());
                    $this->setFechaCrea($FormulariosGenericos->getFechaCrea());
                    $this->setFechaModif($FormulariosGenericos->getFechaModif());
                } else {
                    throw new Exception('No existe' . get_class($this), 01);
                }
            }elseif($usuarioId != '' && $mandante != '' && $anio != '' && $paisId != '' && $tipo) {
                $FormulariosGenericosMySqlDAO = new FormulariosGenericosMySqlDAO();
                $FormulariosGenericos = $FormulariosGenericosMySqlDAO->loadFormByMandantePaisAnioTipo($usuarioId, $mandante, $paisId, $anio, $tipo);

                if($FormulariosGenericos != '') {
                    $this->setformGenericoId($FormulariosGenericos->getFormGenericoId());
                    $this->setFormData($FormulariosGenericos->getFormData());
                    $this->setUsuarioId($FormulariosGenericos->getUsuarioId());
                    $this->setDiligenciado($FormulariosGenericos->getDiligenciado());
                    $this->setAnio($FormulariosGenericos->getAnio());
                    $this->setMandante($FormulariosGenericos->getMandante());
                    $this->setPaisId($FormulariosGenericos->getPaisId());
                    $this->setTipo($FormulariosGenericos->getTipo());
                    $this->setUsucreaId($FormulariosGenericos->getUsucreaId());
                    $this->setUsumodifId($FormulariosGenericos->getUsumodifId());
                    $this->setFechaCrea($FormulariosGenericos->getFechaCrea());
                    $this->setFechaModif($FormulariosGenericos->getFechaModif());
                } else throw new Exception('No existe' . get_class($this), 01);
            }
        }

        /**
         * Obtiene un formulario genérico personalizado basado en los parámetros proporcionados.
         *
         * @param string $select Campos a seleccionar en la consulta.
         * @param string $sidx Índice de ordenación.
         * @param string $sord Orden de clasificación (ASC o DESC).
         * @param int $start Índice de inicio para la paginación.
         * @param int $limit Límite de registros a obtener.
         * @param array $filters Filtros a aplicar en la consulta.
         * @param bool $searchOn Indica si la búsqueda está activada.
         * @return object Respuesta de la consulta del formulario genérico personalizado.
         * @throws \Exception Si no se encuentra el formulario genérico.
         */
        public function getFormularioGenericoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $FormulariosGenericosMySqlDAO = new FormulariosGenericosMySqlDAO();

            $response = $FormulariosGenericosMySqlDAO->queryFormularioGenericoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);
            if($response === '') throw new \Exception('No existe' . get_class($this), 34);

            return $response;
        }

        /**
         * Establece el ID del formulario genérico.
         *
         * @param int $formGenericoId
         */
        public function setformGenericoId($formGenericoId) {
            $this->formGenericoId = $formGenericoId;
        }

        /**
         * Obtiene el ID del formulario genérico.
         *
         * @return int
         */
        public function getFormGenericoId() {
            return $this->formGenericoId;
        }

        /**
         * Establece el ID del usuario.
         *
         * @param int $usuarioId
         */
        public function setUsuarioId($usuarioId) {
            $this->usuarioId = $usuarioId;
        }

        /**
         * Obtiene el ID del usuario.
         *
         * @return int
         */
        public function getUsuarioId() {
            return $this->usuarioId;
        }

        /**
         * Establece los datos del formulario.
         *
         * @param array $formData
         */
        public function setFormData($formData) {
            $this->formData = $formData;
        }

        /**
         * Obtiene los datos del formulario.
         *
         * @return array
         */
        public function getFormData() {
            return $this->formData;
        }

        /**
         * Establece si el formulario ha sido diligenciado.
         *
         * @param bool $diligenciado
         */
        public function setDiligenciado($diligenciado) {
            $this->diligenciado = $diligenciado;
        }

        /**
         * Obtiene si el formulario ha sido diligenciado.
         *
         * @return bool
         */
        public function getDiligenciado() {
            return $this->diligenciado;
        }

        /**
         * Establece el año.
         *
         * @param int $anio
         */
        public function setAnio($anio) {
            $this->anio = $anio;
        }

        /**
         * Obtiene el año.
         *
         * @return int
         */
        public function getAnio() {
            return $this->anio;
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
         * Obtiene el mandante.
         *
         * @return string
         */
        public function getMandante() {
            return $this->mandante;
        }

        /**
         * Establece el ID del país.
         *
         * @param int $paisId
         */
        public function setPaisId($paisId) {
            $this->paisId = $paisId;
        }

        /**
         * Obtiene el ID del país.
         *
         * @return int
         */
        public function getPaisId() {
            return $this->paisId;
        }

        /**
         * Establece el tipo.
         *
         * @param string $tipo
         */
        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        /**
         * Obtiene el tipo.
         *
         * @return string
         */
        public function getTipo() {
            return $this->tipo;
        }

        /**
         * Establece el ID del usuario creador.
         *
         * @param int $usucreaId
         */
        public function setUsucreaId($usucreaId) {
            $this->usucreaId = $usucreaId;
        }

        /**
         * Obtiene el ID del usuario creador.
         *
         * @return int
         */
        public function getUsucreaId() {
            return $this->usucreaId;
        }

        /**
         * Establece el ID del usuario que modificó.
         *
         * @param int $usumodifId
         */
        public function setUsumodifId($usumodifId) {
            $this->usumodifId = $usumodifId;
        }

        /**
         * Obtiene el ID del usuario que modificó.
         *
         * @return int
         */
        public function getUsumodifId() {
            return $this->usumodifId;
        }

        /**
         * Establece la fecha de creación.
         *
         * @param string $fechaCrea
         */
        public function setFechaCrea($fechaCrea) {
            $this->fechaCrea = $fechaCrea;
        }

        /**
         * Obtiene la fecha de creación.
         *
         * @return string
         */
        public function getFechaCrea() {
            return $this->fechaCrea;
        }

        /**
         * Establece la fecha de modificación.
         *
         * @param string $fechaModif
         */
        public function setFechaModif($fechaModif) {
            $this->fechaModif = $fechaModif;
        }

        /**
         * Obtiene la fecha de modificación.
         *
         * @return string
         */
        public function getFechaModif() {
            return $this->fechaModif;
        }
    }
?>