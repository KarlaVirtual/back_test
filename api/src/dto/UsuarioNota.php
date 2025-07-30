<?php
    
    namespace Backend\dto;

    use Backend\mysql\UsuarioNotaMySqlDAO;
    use Exception;

    class UsuarioNota {

/**
         * @var string Representación de la columna 'usunota_id' en la tabla 'usuario_nota'
         */
        private $usunotaId;

        /**
         * @var string Representación de la columna 'tipo' en la tabla 'usuario_nota'
         */
        private $tipo;

        /**
         * @var string Representación de la columna 'descripcion' en la tabla 'usuario_nota'
         */
        private $descripcion;

        /**
         * @var string Representación de la columna 'usufrom_id' en la tabla 'usuario_nota'
         */
        private $usufromId;

        /**
         * @var string Representación de la columna 'usu_to_id' en la tabla 'usuario_nota'
         */
        private $usuToId;

        /**
         * @var string Representación de la columna 'mandate' en la tabla 'usuario_nota'
         */
        private $mandate;

        /**
         * @var string Representación de la columna 'pais_id' en la tabla 'usuario_nota'
         */
        private $paisId;

        /**
         * @var string Representación de la columna 'ref_id' en la tabla 'usuario_nota'
         */
        private $refId;

        /**
         * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_nota'
         */
        private $usucreaId;

        /**
         * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_nota'
         */
        private $usumodifId;

        /**
         * @var string Representación de la columna 'fecha_crea' en la tabla 'usuario_nota'
         */
        private $fechaCrea;

        /**
         * @var string Representación de la columna 'fecha_modif' en la tabla 'usuario_nota'
         */
        private $fechaModif;

        /**
         * Constructor de la clase UsuarioNota.
         *
         * @param string $usunotaId El ID de la nota de usuario. Si se proporciona, se cargará la información correspondiente desde la base de datos.
         * 
         * @throws Exception Si no existe la nota de usuario con el ID proporcionado.
         */
        public function __construct($usunotaId = '') {

            if($usunotaId !== '') {
                $UsuarioNotaMySqlDAO = new UsuarioNotaMySqlDAO();
                $usuarioNota = $UsuarioNotaMySqlDAO->load($$usunotaId);
                if($usuarioNota != '') {
                    $this->setUsunotaId($usuarioNota->getUsunotaIdId());
                    $this->setTipo($usuarioNota->getTipo());
                    $this->setDescription($usuarioNota->getDescripcion());
                    $this->setUsufromId($usuarioNota->getUsufromId());
                    $this->setUsutoId($usuarioNota->getUsutoId());
                    $this->setMandante($usuarioNota->getMandante());
                    $this->setPaisId($usuarioNota->getPaisId());
                    $this->setRefId($usuarioNota->getRefId());
                    $this->setUsucreaId($usuarioNota->getUsucreaId());
                    $this->setUsumodifId($usuarioNota->getUsumodifId());
                    $this->setFechaCrea($usuarioNota->getFechaCrea());
                    $this->setFechaModif($usuarioNota->getFechaModif());
                } else throw new Exception('No exite' . get_class($this), 34);
            }
            
        }

        public function getUsuarioNotaCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $UsuarioNotaMySqlDAO = new UsuarioNotaMySqlDAO();
            $data = $UsuarioNotaMySqlDAO->queryUsuarioNotaCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);
            if($data == '') throw new Exception('No existe ' . get_class($this), 34);

            return $data;
        }

/**
             * Obtiene el ID de la nota de usuario.
             *
             * @return string El ID de la nota de usuario.
             */
            public function getUsunotaIdId() {
                return $this->usunotaId;
            }

            /**
             * Establece el ID de la nota de usuario.
             *
             * @param string $usunotaId El ID de la nota de usuario.
             */
            public function setUsunotaId($usunotaId) {
                $this->usunotaId = $usunotaId;
            }

            /**
             * Obtiene el tipo de la nota de usuario.
             *
             * @return string El tipo de la nota de usuario.
             */
            public function getTipo() {
                return $this->tipo;
            }

            /**
             * Establece el tipo de la nota de usuario.
             *
             * @param string $tipo El tipo de la nota de usuario.
             */
            public function setTipo($tipo) {
                $this->tipo = $tipo;
            }

            /**
             * Obtiene la descripción de la nota de usuario.
             *
             * @return string La descripción de la nota de usuario.
             */
            public function getDescripcion() {
                return $this->descripcion;
            }

            /**
             * Establece la descripción de la nota de usuario.
             *
             * @param string $descripcion La descripción de la nota de usuario.
             */
            public function setDescripcion($descripcion) {
                $this->descripcion = $descripcion;
            }

            /**
             * Obtiene el ID del usuario que creó la nota.
             *
             * @return string El ID del usuario que creó la nota.
             */
            public function getUsufromId() {
                return $this->usufromId;
            }

            /**
             * Establece el ID del usuario que creó la nota.
             *
             * @param string $usufromId El ID del usuario que creó la nota.
             */
            public function setUsufromId($usufromId) {
                $this->usufromId = $usufromId;
            }

            /**
             * Obtiene el ID del usuario destinatario de la nota.
             *
             * @return string El ID del usuario destinatario de la nota.
             */
            public function getUsutoId() {
                return $this->usuToId;
            }

            /**
             * Establece el ID del usuario destinatario de la nota.
             *
             * @param string $usutoId El ID del usuario destinatario de la nota.
             */
            public function setUsutoId($usutoId) {
                $this->usuToId = $usutoId;
            }

            /**
             * Obtiene el mandato de la nota de usuario.
             *
             * @return string El mandato de la nota de usuario.
             */
            public function getMandante() {
                return $this->mandate;
            }

            /**
             * Establece el mandato de la nota de usuario.
             *
             * @param string $mandate El mandato de la nota de usuario.
             */
            public function setMandante($mandate) {
                $this->mandate = $mandate;
            }

            /**
             * Obtiene el ID del país relacionado con la nota de usuario.
             *
             * @return string El ID del país relacionado con la nota de usuario.
             */
            public function getPaisId() {
                return $this->paisId;
            }

            /**
             * Establece el ID del país relacionado con la nota de usuario.
             *
             * @param string $paisId El ID del país relacionado con la nota de usuario.
             */
            public function setPaisId($paisId) {
                $this->paisId = $paisId;
            }

            /**
             * Obtiene el ID de referencia de la nota de usuario.
             *
             * @return string El ID de referencia de la nota de usuario.
             */
            public function getRefId() {
                return $this->refId;
            }

            /**
             * Establece el ID de referencia de la nota de usuario.
             *
             * @param string $refId El ID de referencia de la nota de usuario.
             */
            public function setRefId($refId) {
                $this->refId = $refId;
            }

            /**
             * Obtiene el ID del usuario que creó la nota.
             *
             * @return string El ID del usuario que creó la nota.
             */
            public function getUsucreaId() {
                return $this->usucreaId;
            }

            /**
             * Establece el ID del usuario que creó la nota.
             *
             * @param string $usucreaId El ID del usuario que creó la nota.
             */
            public function setUsucreaId($usucreaId) {
                $this->usucreaId = $usucreaId;
            }

            /**
             * Obtiene el ID del usuario que modificó la nota.
             *
             * @return string El ID del usuario que modificó la nota.
             */
            public function getUsumodifId() {
                return $this->usumodifId;
            }

            /**
             * Establece el ID del usuario que modificó la nota.
             *
             * @param string $usumodifId El ID del usuario que modificó la nota.
             */
            public function setUsumodifId($usumodifId) {
                $this->usumodifId = $usumodifId;
            }

            /**
             * Obtiene la fecha de creación de la nota de usuario.
             *
             * @return string La fecha de creación de la nota de usuario.
             */
            public function getFechaCrea() {
                return $this->fechaCrea;
            }

            /**
             * Establece la fecha de creación de la nota de usuario.
             *
             * @param string $fechaCrea La fecha de creación de la nota de usuario.
             */
            public function setFechaCrea($fechaCrea) {
                $this->fechaCrea = $fechaCrea;
            }

            /**
             * Obtiene la fecha de modificación de la nota de usuario.
             *
             * @return string La fecha de modificación de la nota de usuario.
             */
            public function getFechaModif() {
                return $this->fechaModif;
            }

            /**
             * Establece la fecha de modificación de la nota de usuario.
             *
             * @param string $fechaModif La fecha de modificación de la nota de usuario.
             */
            public function setFechaModif($fechaModif) {
                $this->fechaModif = $fechaModif;
            }
    }
?>