<?php
    namespace Backend\dto;

    use Exception;
    use Backend\mysql\SubproveedorMandantePaisMySqlDAO;

    class SubproveedorMandantePais {

        /**
         * @var string Representación de la columna 'provmandante_id' en la tabla 'subproveedor_mandante_pais'
         */
        private $provmandanteId;

        /**
         * @var string Representación de la columna 'mandante' en la tabla 'subproveedor_mandante_pais'
         */
        private $mandante;

        /**
         * @var string Representación de la columna 'subproveedor_id' en la tabla 'subproveedor_mandante_pais'
         */
        private $subproveedorId;

        /**
         * @var string Representación de la columna 'estado' en la tabla 'subproveedor_mandante_pais'
         */
        private $estado;

        /**
         * @var string Representación de la columna 'detalle' en la tabla 'subproveedor_mandante_pais'
         */
        private $detalle;

        /**
         * @var string Representación de la columna 'usucrea_id' en la tabla 'subproveedor_mandante_pais'
         */
        private $usucreaId;

        /**
         * @var string Representación de la columna 'fecha_crea' en la tabla 'subproveedor_mandante_pais'
         */
        private $fechaCrea;

        /**
         * @var string Representación de la columna 'usumodif_id' en la tabla 'subproveedor_mandante_pais'
         */
        private $usumodifId;

        /**
         * @var string Representación de la columna 'fecha_modif' en la tabla 'subproveedor_mandante_pais'
         */
        private $fechaModif;

        /**
         * @var string Representación de la columna 'verifica' en la tabla 'subproveedor_mandante_pais'
         */
        private $verifica;

        /**
         * @var string Representación de la columna 'filtro_pais' en la tabla 'subproveedor_mandante_pais'
         */
        private $filtroPais;

        /**
         * @var string Representación de la columna 'max' en la tabla 'subproveedor_mandante_pais'
         */
        private $max;

        /**
         * @var string Representación de la columna 'min' en la tabla 'subproveedor_mandante_pais'
         */
        private $min;

        /**
         * @var string Representación de la columna 'orden' en la tabla 'subproveedor_mandante_pais'
         */
        private $orden;

        /**
         * @var string Representación de la columna 'pais_id' en la tabla 'subproveedor_mandante_pais'
         */
        private $paisId;

        /**
         * @var string Representación de la columna 'credentials' en la tabla 'subproveedor_mandante_pais'
         */
        private $credentials;

        /**
         * @var string Representación de la columna 'image' en la tabla 'subproveedor_mandante_pais'
         */
        private $image;

        /**
         * @var string Representación de la columna 'bonus_system' en la tabla 'subproveedor_mandante_pais'
         */
        private $bonusSystem;

        /**
         * @var string Representación de la columna 'usuarios_prueba' en la tabla 'subproveedor_mandante_pais'
         */
        private $usuariosPrueba;


        public function __construct($provmandanteId = '', $subproveedorId = '', $mandante = '', $paisId = '') {
            if ($provmandanteId != '') {
                $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO();

                $SubproveedorMandantePais = $SubproveedorMandantePaisMySqlDAO->load($provmandanteId);

                if ($SubproveedorMandantePais != '') {
                    $this->provmandanteId = $SubproveedorMandantePais->getProvmandanteId();
                    $this->mandante = $SubproveedorMandantePais->getMandante();
                    $this->subproveedorId = $SubproveedorMandantePais->getSubproveedorId();
                    $this->estado = $SubproveedorMandantePais->getEstado();
                    $this->detalle = $SubproveedorMandantePais->getDetalle();
                    $this->usucreaId = $SubproveedorMandantePais->getUsucreaId();
                    $this->fechaCrea = $SubproveedorMandantePais->getFechaCrea();
                    $this->usumodifId = $SubproveedorMandantePais->getUsumodifId();
                    $this->verifica = $SubproveedorMandantePais->getVerifica();
                    $this->filtroPais = $SubproveedorMandantePais->getFiltroPais();
                    $this->max = $SubproveedorMandantePais->getMax();
                    $this->min = $SubproveedorMandantePais->getMin();
                    $this->orden = $SubproveedorMandantePais->getOrden();
                    $this->paisId = $SubproveedorMandantePais->getPaisId();
                    $this->image = $SubproveedorMandantePais->getImage();
                    $this->credentials = $SubproveedorMandantePais->getCredentials();
                    $this->bonusSystem = $SubproveedorMandantePais->getBonusSystem();
                    $this->usuariosPrueba = $SubproveedorMandantePais->getUsuariosPrueba();

                } else {
                    throw new Exception('No existe ' . get_class($this), '107');
                }
            } elseif ($subproveedorId != '' && $mandante != '' && $paisId != '') {

                $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO();

                $SubproveedorMandantePais = $SubproveedorMandantePaisMySqlDAO->queryBySubproveedorIdAndMandantePais($subproveedorId, $mandante, $paisId);
                $SubproveedorMandantePais = $SubproveedorMandantePais[0];

                if ($SubproveedorMandantePais != '') {
                    $this->provmandanteId = $SubproveedorMandantePais->getProvmandanteId();
                    $this->mandante = $SubproveedorMandantePais->getMandante();
                    $this->subproveedorId = $SubproveedorMandantePais->getSubproveedorId();
                    $this->estado = $SubproveedorMandantePais->getEstado();
                    $this->detalle = $SubproveedorMandantePais->getDetalle();
                    $this->usucreaId = $SubproveedorMandantePais->getUsucreaId();
                    $this->fechaCrea = $SubproveedorMandantePais->getFechaCrea();
                    $this->usumodifId = $SubproveedorMandantePais->getUsumodifId();
                    $this->fechaModif = $SubproveedorMandantePais->getFechaModif();
                    $this->verifica = $SubproveedorMandantePais->getVerifica();
                    $this->filtroPais = $SubproveedorMandantePais->getFiltroPais();
                    $this->max = $SubproveedorMandantePais->getMax();
                    $this->min = $SubproveedorMandantePais->getMin();
                    $this->orden = $SubproveedorMandantePais->getOrden();
                    $this->paisId = $SubproveedorMandantePais->getPaisId();
                    $this->image = $SubproveedorMandantePais->getImage();
                    $this->credentials = $SubproveedorMandantePais->getCredentials();
                    $this->bonusSystem = $SubproveedorMandantePais->getBonusSystem();
                    $this->usuariosPrueba = $SubproveedorMandantePais->getUsuariosPrueba();
                } else {
                    throw new Exception('No existe ' . get_class($this), '107');
                }
            } 

        }

       /**
         * Establece el ID del proveedor mandante.
         *
         * @param string $provmandanteId
         */
        public function setProvmandanteId($provmandanteId) {
            $this->provmandanteId = $provmandanteId;
        }

        /**
         * Obtiene el ID del proveedor mandante.
         *
         * @return string
         */
        public function getProvmandanteId() {
            return $this->provmandanteId;
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
         * Establece el ID del subproveedor.
         *
         * @param string $subproveedorId
         */
        public function setSubproveedorId($subproveedorId) {
            $this->subproveedorId = $subproveedorId;
        }

        /**
         * Obtiene el ID del subproveedor.
         *
         * @return string
         */
        public function getSubproveedorId() {
            return $this->subproveedorId;
        }


        /**
         * Propósito: funcion set bonusSystem permite enviar el Contenido de BonusSystem que permite establecer si tiene o no BonusSystem
         * Descripción de variables:
         */

        public function setBonusSystem($value){
            $this->bonusSystem = $value;
        }

        /**
         * Propósito: funcion get bonusSystem permite obtener el Contenido de BonusSystem que nos indica si tiene o no BonusSystem
         *
         */


        /**
         * Obtiene el sistema de bonificación.
         *
         * @return string
         */
        public function getBonusSystem(){
            return $this->bonusSystem;
        }

        /**
         * Establece el estado.
         *
         * @param string $estado
         */
        public function setEstado($estado) {
            $this->estado = $estado;
        }

        /**
         * Obtiene el estado.
         *
         * @return string
         */
        public function getEstado() {
            return $this->estado;
        }

        /**
         * Establece el detalle.
         *
         * @param string $detalle
         */
        public function setDetalle($detalle) {
            $this->detalle = $detalle;
        }

        /**
         * Obtiene el detalle.
         *
         * @return string
         */
        public function getDetalle() {
            return $this->detalle;
        }

        /**
         * Establece el ID del usuario creador.
         *
         * @param string $usucreaId
         */
        public function setUsucreaId($usucreaId) {
            $this->usucreaId = $usucreaId;
        }

        /**
         * Obtiene el ID del usuario creador.
         *
         * @return string
         */
        public function getUsucreaId() {
            return $this->usucreaId;
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
         * Establece el ID del usuario modificador.
         *
         * @param string $usumodifId
         */
        public function setUsumodifId($usumodifId) {
            $this->usumodifId = $usumodifId;
        }

        /**
         * Obtiene el ID del usuario modificador.
         *
         * @return string
         */
        public function getUsumodifId() {
            return $this->usumodifId;
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

        /**
         * Establece la verificación.
         *
         * @param string $verifica
         */
        public function setVerifica($verifica) {
            $this->verifica = $verifica;
        }

        /**
         * Obtiene la verificación.
         *
         * @return string
         */
        public function getVerifica() {
            return $this->verifica;
        }

        /**
         * Establece el filtro de país.
         *
         * @param string $filtroPais
         */
        public function setFiltroPais($filtroPais) {
            $this->filtroPais = $filtroPais;
        }

/**
         * Obtiene el filtro de país.
         *
         * @return string
         */
        public function getFiltroPais() {
            return $this->filtroPais;
        }

        /**
         * Establece el valor máximo.
         *
         * @param string $max
         */
        public function setMax($max) {
            $this->max = $max;
        }

        /**
         * Obtiene el valor máximo.
         *
         * @return string
         */
        public function getMax() {
            return $this->max;
        }

        /**
         * Establece el valor mínimo.
         *
         * @param string $min
         */
        public function setMin($min) {
            $this->min = $min;
        }

        /**
         * Obtiene el valor mínimo.
         *
         * @return string
         */
        public function getMin() {
            return $this->min;
        }

        /**
         * Establece el orden.
         *
         * @param string $orden
         */
        public function setOrden($orden) {
            $this->orden = $orden;
        }

        /**
         * Obtiene el orden.
         *
         * @return string
         */
        public function getOrden() {
            return $this->orden;
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
         * Obtiene el ID del país.
         *
         * @return string
         */
        public function getPaisId() {
            return $this->paisId;
        }

        /**
         * Obtiene la imagen.
         *
         * @return string
         */
        public function getImage(){
            return $this->image;
        }

        /**
         * Establece la imagen.
         *
         * @param string $value
         */
        public function setImage($value){
            $this->image = $value;
        }

        /**
         * Obtiene las credenciales.
         *
         * @return string
         */
        public function getCredentials(){
            return $this->credentials;
        }

        /**
         * Establece las credenciales.
         *
         * @param string $value
         */
        public function setCredentials($value){
            $this->credentials = $value;
        }

        /**
         * Establece los usuarios de prueba.
         *
         * @param string $value
         */
        public function setUsuariosPrueba($value){
            $this->usuariosPrueba = $value;
        }

        /**
         * Obtiene los usuarios de prueba.
         *
         * @return string
         */
        public function getUsuariosPrueba() {
            return $this->usuariosPrueba;
        }

        public function getSubproveedoresMandantePaisCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {

            $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO();

            $Productos = $SubproveedorMandantePaisMySqlDAO->querySubproveedoresMandantePaisCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

            if ($Productos == '') throw new Exception('No existe ' . get_class($this), '01');

            return $Productos;

        }

        /**
         * Obtiene el listado de subproveedores por pais en la sección de partner ajustes-> credenciales por subproveedor
         */
        public function getSubproveedoresMandantePaisCustomCredentials($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {

            $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO();

            $Productos = $SubproveedorMandantePaisMySqlDAO->querySubproveedoresMandantePaisCustomCredentials($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

            if ($Productos == '') throw new Exception('No existe ' . get_class($this), '01');

            return $Productos;

        }

        /**
         * Actualiza las credenciales de un sub proveedor 
         */
        public function updateCredencialesSubproveedoresMandantePais(): void {
            $subproveedor = new SubproveedorMandantePaisMySqlDAO();
            $subproveedor->updateCredenials($this);
            $subproveedor->getTransaction()->commit();
        }
    }
?>
