<?php
    namespace Backend\dto;

    use Backend\sql\Transaction;
    use Exception;
    use Backend\mysql\UsuarioRestriccionMySqlDAO;

    use Backend\dto\AuditoriaGeneral;
    use Backend\mysql\AuditoriaGeneralMySqlDAO;

    /**
     * DTO encargado dw representar la tabla usuario_restriccion en la base de datos.
     *
     * @author: Daniel Tamayo
     * @date: desconocido
     * @package No
     * @category No
     * @version 1.0
     */
    class UsuarioRestriccion {
/**
         * @var string Representación de la columna 'usurestriccion_id' en la tabla 'usuario_restriccion'
         */
        private $usurestriccionId;

        /**
         * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_restriccion'
         */
        private $usuarioId;

        /**
         * @var string Representación de la columna 'email' en la tabla 'usuario_restriccion'
         */
        private $email;

        /**
         * @var string Representación de la columna 'documento' en la tabla 'usuario_restriccion'
         */
        private $documento;

        /**
         * @var string Representación de la columna 'tipo_doc' en la tabla 'usuario_restriccion'
         */
        private $tipoDoc;

        /**
         * @var string Representación de la columna 'nombre' en la tabla 'usuario_restriccion'
         */
        private $nombre;

        /**
         * @var string Representación de la columna 'telefono' en la tabla 'usuario_restriccion'
         */
        private $telefono;

        /**
         * @var string Representación de la columna 'nota' en la tabla 'usuario_restriccion'
         */
        private $nota;

        /**
         * @var string Representación de la columna 'mandante' en la tabla 'usuario_restriccion'
         */
        private $mandante;

        /**
         * @var string Representación de la columna 'pais_id' en la tabla 'usuario_restriccion'
         */
        private $paisId;

        /**
         * @var string Representación de la columna 'estado' en la tabla 'usuario_restriccion'
         */
        private $estado;

        /**
         * @var string Representación de la columna 'clasificador_id' en la tabla 'usuario_restriccion'
         */
        private $clasificadorId;

        /**
         * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_restriccion'
         */
        private $usucreaId;

        /**
         * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_restriccion'
         */
        private $usumodifId;

        /**
         * @var string Representación de la columna 'fecha_crea' en la tabla 'usuario_restriccion'
         */
        private $fechaCrea;

        /**
         * @var string Representación de la columna 'fecha_modif' en la tabla 'usuario_restriccion'
         */
        private $fechaModif;

        /**
         * @var string Representación de la columna 'metodo' en la tabla 'usuario_restriccion'
         */
        private $metodo;

        /**
         * Constructor de la clase UsuarioRestriccion.
         *
         * @param string $usurestriccionId El ID de la restricción de usuario. Si se proporciona, se cargan los datos correspondientes.
         * @throws Exception Si no existe la restricción de usuario con el ID proporcionado.
         */
        public function __construct($usurestriccionId = '') {
            if($usurestriccionId != '') {
                $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO();
                $usuarioRestriccion = $UsuarioRestriccionMySqlDAO->load($usurestriccionId);
                if($usuarioRestriccion != '') {
                    $this->setUsurestriccionId($usuarioRestriccion->getUsurestriccionId());
                    $this->setUsuarioId($usuarioRestriccion->getUsuarioId());
                    $this->setEmail($usuarioRestriccion->getEmail());
                    $this->setDocumento($usuarioRestriccion->getDocumento());
                    $this->setNombre($usuarioRestriccion->getNombre());
                    $this->setTipoDoc($usuarioRestriccion->getTipoDoc());
                    $this->setTelefono($usuarioRestriccion->getTelefono());
                    $this->setNota($usuarioRestriccion->getNota());
                    $this->setMandante($usuarioRestriccion->getMandante());
                    $this->setPaisId($usuarioRestriccion->getPaisId());
                    $this->setEstado($usuarioRestriccion->getEstado());
                    $this->setClasificadorId($usuarioRestriccion->getClasificadorId());
                    $this->setUsucreaId($usuarioRestriccion->getUsucreaId());
                    $this->setUsumodifId($usuarioRestriccion->getUsumodifId());
                    $this->setFechaCrea($usuarioRestriccion->getFechaCrea());
                    $this->setFechaModif($usuarioRestriccion->getFechaModif());
                    $this->setMetodo($usuarioRestriccion->getMetodo());
                } else throw new Exception('No existe '. get_class($this), 34);
            }
        }

        /**
         * Obtiene la colección de restricciones de un usuario personalizadas según los parámetros proporcionados.
         *
         * @param string $select Campos a seleccionar en la consulta.
         * @param string $sidx Índice de ordenación.
         * @param string $sord Orden de clasificación (ascendente o descendente).
         * @param int $start Posición inicial para la consulta.
         * @param int $limit Límite de registros a obtener.
         * @param array $filters Filtros a aplicar en la consulta.
         * @param bool $searchOn Indica si la búsqueda está activada.
         * @return array Datos obtenidos de la consulta.
         * @throws Exception Si no se encuentran datos, lanza una excepción con el mensaje 'No existe' y el nombre de la clase.
         */
        public function getUsuraioRestriccionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO();
            $data = $UsuarioRestriccionMySqlDAO->queryUsuarioRestriccionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

            if(empty($data)) throw new Exception('No existe ' . get_class($this), 34);

            return $data;
        }

        /**
         * Obtiene datos personalizados basados en el valor proporcionado.
         *
         * @param mixed $value El valor utilizado para la consulta personalizada.
         * @return mixed Los datos resultantes de la consulta personalizada.
         */
        public function getQueryCustom($value) {
            $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO();
            $data = $UsuarioRestriccionMySqlDAO->queryCustom($value);
            return $data;
        }

/**
         * Obtiene el ID de la restricción de usuario.
         *
         * @return string El ID de la restricción de usuario.
         */
        public function getUsurestriccionId() {
            return $this->usurestriccionId;
        }

        /**
         * Establece el ID de la restricción de usuario.
         *
         * @param string $usurestriccionId El ID de la restricción de usuario.
         */
        public function setUsurestriccionId($usurestriccionId) {
            $this->usurestriccionId = $usurestriccionId;
        }

        /**
         * Obtiene el ID del usuario.
         *
         * @return string El ID del usuario.
         */
        public function getUsuarioId() {
            return $this->usuarioId;
        }

        /**
         * Establece el ID del usuario.
         *
         * @param string $usuarioId El ID del usuario.
         */
        public function setUsuarioId($usuarioId): void {
            $this->usuarioId = $usuarioId;
        }

        /**
         * Obtiene el email del usuario.
         *
         * @return string El email del usuario.
         */
        public function getEmail() {
            return $this->email;
        }

        /**
         * Establece el email del usuario.
         *
         * @param string $email El email del usuario.
         */
        public function setEmail($email) {
            $this->email = $email;
        }

        /**
         * Obtiene el documento del usuario.
         *
         * @return string El documento del usuario.
         */
        public function getDocumento() {
            return $this->documento;
        }

        /**
         * Establece el documento del usuario.
         *
         * @param string $documento El documento del usuario.
         */
        public function setDocumento($documento) {
            $this->documento = $documento;
        }

        /**
         * Obtiene el tipo de documento del usuario.
         *
         * @return string El tipo de documento del usuario.
         */
        public function getTipoDoc() {
            return $this->tipoDoc;
        }

        /**
         * Establece el tipo de documento del usuario.
         *
         * @param string $tipoDoc El tipo de documento del usuario.
         */
        public function setTipoDoc($tipoDoc) {
            $this->tipoDoc = $tipoDoc;
        }

        /**
         * Obtiene el nombre del usuario.
         *
         * @return string El nombre del usuario.
         */
        public function getNombre() {
            return $this->nombre;
        }

        /**
         * Establece el nombre del usuario.
         *
         * @param string $nombre El nombre del usuario.
         */
        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }

        /**
         * Obtiene el teléfono del usuario.
         *
         * @return string El teléfono del usuario.
         */
        public function getTelefono() {
            return $this->telefono;
        }

        /**
         * Establece el teléfono del usuario.
         *
         * @param string $telefono El teléfono del usuario.
         */
        public function setTelefono($telefono) {
            $this->telefono = $telefono;
        }

        /**
         * Obtiene la nota del usuario.
         *
         * @return string La nota del usuario.
         */
        public function getNota() {
            return $this->nota;
        }

        /**
         * Establece la nota del usuario.
         *
         * @param string $nota La nota del usuario.
         */
        public function setNota($nota) {
            $this->nota = $nota;
        }

        /**
         * Obtiene el mandante del usuario.
         *
         * @return string El mandante del usuario.
         */
        public function getMandante() {
            return $this->mandante;
        }

        /**
         * Establece el mandante del usuario.
         *
         * @param string $mandante El mandante del usuario.
         */
        public function setMandante($mandante) {
            $this->mandante = $mandante;
        }

        /**
         * Obtiene el ID del país del usuario.
         *
         * @return string El ID del país del usuario.
         */
        public function getPaisId() {
            return $this->paisId;
        }

        /**
         * Establece el ID del país del usuario.
         *
         * @param string $paisId El ID del país del usuario.
         */
        public function setPaisId($paisId) {
            $this->paisId = $paisId;
        }

        /**
         * Obtiene el estado del usuario.
         *
         * @return string El estado del usuario.
         */
        public function getEstado() {
            return $this->estado;
        }

        /**
         * Establece el estado del usuario.
         *
         * @param string $estado El estado del usuario.
         */
        public function setEstado($estado) {
            $this->estado = $estado;
        }

        /**
         * Obtiene el ID del clasificador del usuario.
         *
         * @return string El ID del clasificador del usuario.
         */
        public function getClasificadorId() {
            return $this->clasificadorId;
        }

        /**
         * Establece el ID del clasificador del usuario.
         *
         * @param string $clasificadorId El ID del clasificador del usuario.
         */
        public function setClasificadorId($clasificadorId) {
            $this->clasificadorId = $clasificadorId;
        }

        /**
         * Obtiene el ID del usuario que creó el registro.
         *
         * @return string El ID del usuario que creó el registro.
         */
        public function getUsucreaId() {
            return $this->usucreaId;
        }

        /**
         * Establece el ID del usuario que creó el registro.
         *
         * @param string $usucreaId El ID del usuario que creó el registro.
         */
        public function setUsucreaId($usucreaId) {
            $this->usucreaId = $usucreaId;
        }

        /**
         * Obtiene el ID del usuario que modificó el registro.
         *
         * @return string El ID del usuario que modificó el registro.
         */
        public function getUsumodifId() {
            return $this->usumodifId;
        }

        /**
         * Establece el ID del usuario que modificó el registro.
         *
         * @param string $usumodifId El ID del usuario que modificó el registro.
         */
        public function setUsumodifId($usumodifId) {
            $this->usumodifId = $usumodifId;
        }

        /**
         * Obtiene la fecha de creación del registro.
         *
         * @return string La fecha de creación del registro.
         */
        public function getFechaCrea() {
            return $this->fechaCrea;
        }

        /**
         * Establece la fecha de creación del registro.
         *
         * @param string $fechaCrea La fecha de creación del registro.
         */
        public function setFechaCrea($fechaCrea) {
            $this->fechaCrea = $fechaCrea;
        }

        /**
         * Obtiene la fecha de modificación del registro.
         *
         * @return string La fecha de modificación del registro.
         */
        public function getFechaModif() {
            return $this->fechaModif;
        }

        /**
         * Establece la fecha de modificación del registro.
         *
         * @param string $fechaModif La fecha de modificación del registro.
         */
        public function setFechaModif($fechaModif) {
            $this->fechaModif = $fechaModif;
        }

        /**
         * Obtiene el método del usuario.
         *
         * @return string El método del usuario.
         */
        public function getMetodo() {
            return $this->metodo;
        }

        /**
         * Establece el método del usuario.
         *
         * @param string $metodo El método del usuario.
         */
        public function setMetodo($metodo): void {
            $this->metodo = $metodo;
        }

        /** Registra un intento de registro para una plataforma específica donde se encontró coincidencia con
         *una restricción almacenada en plataforma
         * @param object $UserInfo Es un objeto con la estructura {
         *     'doctype'
         *     'document'
         *     'email'
         *     'phone'
         *     'name'
         *     'classifier'
         * }
         *
         * @param mixed $partner Es el partner bajo el cual se presenta el intento de registro
         * @param mixed $country Es el país bajo el cual se presenta el intento de registro
         *
         *
         * @return bool Retorno booleano, true si fue posible almacenar el intento de registro en usuario_restriccion, false en caso contrario
         */
        public function saveRegistrationAttemptMincetur (object $UserInfo, $partner, $country) : bool {
            //Verificando que intento de registro sea en un sition específico
            if ($partner === '' || $partner === null) return false;
            if (empty($country)) return false;

            //Almacenando registro
            try {
                $Clasificador = new Clasificador(null, 'MINCETUR_BLACK_LIST');
                $Transaction = new Transaction();
                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);

                $AuditoriaGeneral = new AuditoriaGeneral();
                $AuditoriaGeneral->valorAntes = $partner; //Mandante
                $AuditoriaGeneral->valorDespues = $country; //PaisId
                $AuditoriaGeneral->observacion = $UserInfo->document; //Número documento del ciudadano
                $AuditoriaGeneral->estado = 'A';
                $AuditoriaGeneral->tipo = 'PERSONA_PROHIBIDA'; //El tipo correspondiente a intento de login en la plataforma
                $AuditoriaGeneral->sversion = $Clasificador->getClasificadorId(); //Clasificador de listaNegra Mincetur
                $AuditoriaGeneral->data = json_encode($UserInfo); //Objeto con la data del ciudadano enviada a través del registro

                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                $Transaction->commit();
            } catch (Exception $e) {
                return false;
            }

            return true;
        }

    }
?>