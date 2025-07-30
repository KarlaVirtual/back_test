<?php
    namespace Backend\dto;

    use Exception;
    use Backend\mysql\UsuarioSitebuilderMySqlDAO;


    /**
     * Clase 'UsuarioSitebuilder'
     *
     * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
     * para la tabla 'UsuarioSitebuilder'
     *
     * Ejemplo de uso:
     * $UsuarioSession = new UsuarioSession();
     *
     *
     * @package ninguno
     * @author Daniel Tamayo <it@virtualsoft.tech>
     * @version ninguna
     * @access public
     * @see no
     *
     */
    class UsuarioSitebuilder {
        /**
         * @var string Representación de la columna 'usuario_sitebuilder_id' en la tabla 'usuario_sitebuilder'
         */
        private $usuarioSitebuilderId;

        /**
         * @var string Representación de la columna 'login' en la tabla 'usuario_sitebuilder'
         */
        private $login;

        /**
         * @var string Representación de la columna 'clave' en la tabla 'usuario_sitebuilder'
         */
        private $clave;

        /**
         * @var string Representación de la columna 'estado' en la tabla 'usuario_sitebuilder'
         */
        private $estado;

        /**
         * @var string Representación de la columna 'intentos' en la tabla 'usuario_sitebuilder'
         */
        private $intentos;

        /**
         * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_sitebuilder'
         */
        private $usuarioId;

        /**
         * @var string Representación de la columna 'fecha_crea' en la tabla 'usuario_sitebuilder'
         */
        private $fechaCrea;

        /**
         * @var string Representación de la columna 'fecha_modif' en la tabla 'usuario_sitebuilder'
         */
        private $fechaModif;

        /**
         * Constructor de la clase UsuarioSitebuilder.
         *
         * @param string $usuarioSitebuilderId ID del UsuarioSitebuilder (opcional).
         * @param string $usuarioId ID del Usuario (opcional).
         * @throws Exception Si no existe el UsuarioSitebuilder o el Usuario.
         */
        public function __construct($usuarioSitebuilderId = '', $usuarioId = '') {
            if(!empty($usuarioSitebuilderId)) {
                $UsuarioSitebuilderMySqlDAO = new UsuarioSitebuilderMySqlDAO();
                $usuarioSitebuilder = $UsuarioSitebuilderMySqlDAO->load($usuarioSitebuilderId);
                if($usuarioSitebuilder) {
                    $this->setUsuarioSitebuilderId($usuarioSitebuilder->getUsuarioSitebuilderId());
                    $this->setLogin($usuarioSitebuilder->getLogin());
                    $this->setEstado($usuarioSitebuilder->getEstado());
                    $this->setIntentos($usuarioSitebuilder->getIntentos());
                    $this->setUsuarioId($usuarioSitebuilder->getUsuarioId());
                    $this->setFechaCrea($usuarioSitebuilder->getFechaCrea());
                    $this->setFechaModif($usuarioSitebuilder->getFechaModif());
                } else throw new Exception('No existe ' . get_class($this), '24');
            }

            if(!empty($usuarioId)) {
                $UsuarioSitebuilderMySqlDAO = new UsuarioSitebuilderMySqlDAO();
                $usuarioSitebuilder = $UsuarioSitebuilderMySqlDAO->loadByUsuarioId($usuarioId);
                if($usuarioSitebuilder) {
                    $this->setUsuarioSitebuilderId($usuarioSitebuilder->getUsuarioSitebuilderId());
                    $this->setLogin($usuarioSitebuilder->getLogin());
                    $this->setEstado($usuarioSitebuilder->getEstado());
                    $this->setIntentos($usuarioSitebuilder->getIntentos());
                    $this->setUsuarioId($usuarioSitebuilder->getUsuarioId());
                    $this->setFechaCrea($usuarioSitebuilder->getFechaCrea());
                    $this->setFechaModif($usuarioSitebuilder->getFechaModif());
                } else throw new Exception('No existe ' . get_class($this), '24');
            }
        }

        /**
         * Cambia la clave del usuario.
         *
         * @param string $clave La nueva clave que se va a establecer.
         * @return void
         */
        public function changeClave($clave) {
            $UsuarioSitebuilderMySqlDAO = new UsuarioSitebuilderMySqlDAO();
            $UsuarioSitebuilderMySqlDAO->updateClave($this, $clave);
            $UsuarioSitebuilderMySqlDAO->getTransaction()->commit();
        }

        /**
         * Inicia sesión de un usuario en el sistema.
         *
         * @param string $login El nombre de usuario o correo electrónico.
         * @param string $clave La contraseña del usuario.
         * @return mixed Devuelve el ID del usuario si el inicio de sesión es exitoso, o false si los parámetros están vacíos.
         * @throws Exception Si el usuario no existe, si la contraseña es incorrecta, o si el usuario ha sido bloqueado por exceder el número de intentos permitidos.
         */
        public function login($login, $clave) {
            if(empty($login) || empty($clave)) return false;
            $UsuarioSitebuilderMySqlDAO = new UsuarioSitebuilderMySqlDAO();
            $byLogin = $UsuarioSitebuilderMySqlDAO->queryByLogin($login)[0];

            if(empty($byLogin)) throw new Exception('Usuario no existe', 30002);

            $forLogin = $UsuarioSitebuilderMySqlDAO->queryForLogin($login, $clave)[0];

            if(empty($forLogin)) {
                
                $byLogin->setIntentos($byLogin->getIntentos() + 1);
                if($byLogin->getIntentos() >= 3) $byLogin->setEstado('I');
                $UsuarioSitebuilderMySqlDAO->update($byLogin);
                $UsuarioSitebuilderMySqlDAO->getTransaction()->commit();

                if($byLogin->getIntentos() - 1 > 3) throw new Exception('El usuario ha sido bloqueado por el sistema debido a que excedió el número de intentos permitidos con clave errónea.', 30001);

                throw new Exception('Clave incorrecta', 30002);
            }

            if($byLogin->getIntentos() > 0) {
                $byLogin->setIntentos(0);
                $UsuarioSitebuilderMySqlDAO->update($byLogin);
                $UsuarioSitebuilderMySqlDAO->getTransaction()->commit();
            }
            
            return $byLogin->getUsuarioId();
        }

        /**
         * Obtiene una colección de datos personalizados de UsuarioSitebuilder.
         *
         * @param string $select Campos a seleccionar.
         * @param string $sidx Índice de ordenación.
         * @param string $sord Orden de clasificación (ASC o DESC).
         * @param int $start Posición inicial para la consulta.
         * @param int $limit Límite de registros a obtener.
         * @param array $filters Filtros a aplicar en la consulta.
         * @param bool $searchOn Indica si la búsqueda está activada.
         * @return mixed Datos obtenidos de la consulta.
         * @throws Exception Si no existen datos.
         */
        public function getUsuarioSitebuilderCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $UsuarioSitebuilderMySqlDAO = new UsuarioSitebuilderMySqlDAO();
            $data = $UsuarioSitebuilderMySqlDAO->queryUsuarioSitebuilderCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

            if($data === '') throw new Exception('No existe', 14);

            return $data;
        }

/**
         * Obtiene el ID del UsuarioSitebuilder.
         *
         * @return string
         */
        public function getUsuarioSitebuilderId() {
            return $this->usuarioSitebuilderId;
        }

        /**
         * Establece el ID del UsuarioSitebuilder.
         *
         * @param string $usuarioSitebuilderId
         * @return void
         */
        public function setUsuarioSitebuilderId($usuarioSitebuilderId) {
            $this->usuarioSitebuilderId = $usuarioSitebuilderId;
        }

        /**
         * Obtiene el login del UsuarioSitebuilder.
         *
         * @return string
         */
        public function getLogin() {
            return $this->login;
        }

        /**
         * Establece el login del UsuarioSitebuilder.
         *
         * @param string $login
         * @return void
         */
        public function setLogin($login) {
            $this->login = $login;
        }

        /**
         * Establece la clave del UsuarioSitebuilder.
         *
         * @param string $clave
         * @return void
         */
        public function setClave($clave) {
            $this->clave = $clave;
        }

        /**
         * Establece el estado del UsuarioSitebuilder.
         *
         * @param string $estado
         * @return void
         */
        public function setEstado($estado) {
            $this->estado = $estado;
        }

        /**
         * Obtiene el estado del UsuarioSitebuilder.
         *
         * @return string
         */
        public function getEstado() {
            return $this->estado;
        }

        /**
         * Obtiene el número de intentos del UsuarioSitebuilder.
         *
         * @return int
         */
        public function getIntentos() {
            return $this->intentos;
        }

        /**
         * Establece el número de intentos del UsuarioSitebuilder.
         *
         * @param int $intentos
         * @return void
         */
        public function setIntentos($intentos) {
            $this->intentos = $intentos;
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
         * @return void
         */
        public function setUsuarioId($usuarioId) {
            $this->usuarioId = $usuarioId;
        }

        /**
         * Obtiene la fecha de creación del UsuarioSitebuilder.
         *
         * @return string
         */
        public function getFechaCrea() {
            return $this->fechaCrea;
        }

        /**
         * Establece la fecha de creación del UsuarioSitebuilder.
         *
         * @param string $fechaCrea
         * @return void
         */
        public function setFechaCrea($fechaCrea) {
            $this->fechaCrea = $fechaCrea;
        }

        /**
         * Obtiene la fecha de modificación del UsuarioSitebuilder.
         *
         * @return string
         */
        public function getFechaModif() {
            return $this->fechaModif;
        }

        /**
         * Establece la fecha de modificación del UsuarioSitebuilder.
         *
         * @param string $fechaModif
         * @return void
         */
        public function setFechaModif($fechaModif) {
            $this->fechaModif = $fechaModif;
        }
        
    }
?>