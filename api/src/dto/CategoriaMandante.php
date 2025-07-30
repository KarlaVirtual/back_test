<?php 
    namespace Backend\dto;
    use Backend\mysql\CategoriaMandanteMySqlDAO;

    class CategoriaMandante {
        /**
         * Class CategoriaMandante
         * 
         * Representación de la columna 'catmandanteId' en la tabla CategoriaMandante
         * @var int
         */
        private $catmandanteId;

        /**
         * Representación de la columna 'descripcion' en la tabla CategoriaMandante
         * @var string
         */
        private $descripcion;

        /**
         * Representación de la columna 'tipo' en la tabla CategoriaMandante
         * @var string
         */
        private $tipo;

        /**
         * Representación de la columna 'mandante' en la tabla CategoriaMandante
         * @var string
         */
        private $mandante;

        /**
         * Representación de la columna 'slug' en la tabla CategoriaMandante
         * @var string
         */
        private $slug;

        /**
         * Representación de la columna 'estado' en la tabla CategoriaMandante
         * @var string
         */
        private $estado;

        /**
         * Representación de la columna 'imagen' en la tabla CategoriaMandante
         * @var string
         */
        private $imagen;

        /**
         * Representación de la columna 'usucreaId' en la tabla CategoriaMandante
         * @var int
         */
        private $usucreaId;

        /**
         * Representación de la columna 'fechaCrea' en la tabla CategoriaMandante
         * @var string
         */
        private $fechaCrea;

        /**
         * Representación de la columna 'usumodifId' en la tabla CategoriaMandante
         * @var int
         */
        private $usumodifId;

        /**
         * Representación de la columna 'fechaModif' en la tabla CategoriaMandante
         * @var string
         */
        private $fechaModif;

        /**
         * Representación de la columna 'orden' en la tabla CategoriaMandante
         * @var int
         */
        private $orden;

        /**
         * Representación de la columna 'pais_id' en la tabla CategoriaMandante
         * @var int
         */
        private $pais_id;

        /**
         * Constructor de la clase CategoriaMandante.
         *
         * @param string $catmandanteId ID de la categoría del mandante.
         * @param string $tipo Tipo de la categoría del mandante.
         * @param string $slug Slug de la categoría del mandante.
         *
         * @throws \Exception Si no existe la categoría del mandante.
         */
        public function __construct($catmandanteId = '', $tipo = '', $slug = '') {
            if ($catmandanteId != '') {

                $CategoriMandanteaMySqlDAO = new CategoriaMandanteMySqlDAO();

                $CategoriaMandante = $CategoriMandanteaMySqlDAO->load($catmandanteId);

                if ($CategoriaMandante != '') {
                    $this->catmandanteId = $CategoriaMandante->catmandanteId;
                    $this->descripcion = $CategoriaMandante->descripcion;
                    $this->tipo = $CategoriaMandante->tipo;
                    $this->slug = $CategoriaMandante->slug;
                    $this->estado = $CategoriaMandante->estado;
                    $this->usucreaId = $CategoriaMandante->usucreaId;
                    $this->usumodifId = $CategoriaMandante->usumodifId;
                    $this->superior = $CategoriaMandante->superior;
                } else {
                    throw new \Exception('No existe ' . get_class($this), '01');
                }
            } else if ($tipo != '') {
                $CategoriaMandanteMySqlDAOMySqlDAO = new CategoriaMandanteMySqlDAO();

                $CategoriaMandante = $CategoriaMandanteMySqlDAOMySqlDAO->queryByTipo($tipo);

                $CategoriaMandante = $CategoriaMandante[0];

                if ($CategoriaMandante != '') {
                    $this->CategoriaMandanteId = $CategoriaMandante->catmandanteId;
                    $this->descripcion = $CategoriaMandante->descripcion;
                    $this->tipo = $CategoriaMandante->tipo;
                    $this->slug = $CategoriaMandante->slug;
                    $this->estado = $CategoriaMandante->estado;
                    $this->usucreaId = $CategoriaMandante->usucreaId;
                    $this->usumodifId = $CategoriaMandante->usumodifId;
                    $this->superior = $CategoriaMandante->superior;

                } else {
                    throw new \Exception('No existe ' . get_class($this), '01');
                }
            } else if ($slug != '') {
                $CategoriaMandanteMySqlDAO = new CategoriaMandanteMySqlDAO();

                $Categoria = $CategoriaMandanteMySqlDAO->queryBySlug($slug);

                $Categoria=$Categoria[0];

                if ($Categoria != null && $Categoria != '') {
                    $this->catmandanteId = $Categoria->catmandanteId;
                    $this->descripcion = $Categoria->descripcion;
                    $this->tipo = $Categoria->tipo;
                    $this->slug = $Categoria->slug;
                    $this->estado = $Categoria->estado;
                    $this->usucreaId = $Categoria->usucreaId;
                    $this->usumodifId = $Categoria->usumodifId;
                    $this->superior = $Categoria->superior;

                } else  {
                    throw new \Exception('No existe ' . get_class($this), '01');
                }

            }
        }

        /**
         * Obtiene el ID de la categoría del mandante.
         *
         * @return int El ID de la categoría del mandante.
         */
        public function getCatmandanteId() {
            return $this->catmandanteId;
        }

        /**
         * Establece el ID de la categoría del mandante.
         *
         * @param int $catmandanteId El ID de la categoría del mandante.
         * @return void
         */
        public function setCatmandanteId($catmandanteId) {
            $this->catmandanteId = $catmandanteId;
        }

        /**
         * Obtiene la descripción de la categoría del mandante.
         *
         * @return string La descripción de la categoría del mandante.
         */
        public function getDescripcion() {
            return $this->descripcion;
        }

        /**
         * Establece la descripción de la categoría mandante.
         *
         * @param string $descripcion La descripción de la categoría mandante.
         * @return void
         */
        public function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
        }

        /**
         * Obtiene el tipo de la categoría del mandante.
         *
         * @return string El tipo de la categoría del mandante.
         */
        public function getTipo() {
            return $this->tipo;
        }

        /**
         * Establece el tipo de la categoría mandante.
         *
         * @param string $tipo El tipo de la categoría mandante.
         * @return void
         */
        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        /**
         * Obtiene el valor de la propiedad 'mandante'.
         *
         * @return mixed El valor de 'mandante'.
         */
        public function getMandante() {
            return $this->mandante;
        }

        /**
         * Establece el valor del mandante.
         *
         * @param mixed $mandante El valor del mandante a establecer.
         * @return void
         */
        public function setMandante($mandante) {
            $this->mandante = $mandante;
        }

        /**
         * Obtiene el slug de la categoría del mandante.
         *
         * @return string El slug de la categoría del mandante.
         */
        public function getSlug() {
            return $this->slug;
        }

        /**
         * Establece el valor del slug.
         *
         * @param string $slug El valor del slug a establecer.
         */
        public function setSlug($slug) {
            $this->slug = $slug;
        }

        /**
         * Obtiene el estado de la categoría mandante.
         *
         * @return mixed El estado de la categoría mandante.
         */
        public function getEstado() {
            return $this->estado;
        }

        /**
         * Establece el estado de la categoría.
         *
         * @param mixed $estado El estado a establecer.
         */
        public function setEstado($estado) {
            $this->estado = $estado;
        }

        /**
         * Obtiene la imagen de la categoría mandante.
         *
         * @return string La imagen de la categoría mandante.
         */
        public function getImagen() {
            return $this->imagen;
        }

        /**
         * Establece el valor de la imagen.
         *
         * @param string $imagen La imagen a establecer.
         */
        public function setImangen($imagen) {
            $this->imagen = $imagen;
        }

        /**
         * Obtiene el ID del usuario creador.
         *
         * @return int El ID del usuario creador.
         */
        public function getUsucreaId() {
            return $this->usucreaId;
        }

        /**
         * Establece el ID del usuario creador.
         *
         * @param int $usucreaId El ID del usuario creador.
         */
        public function setUsucreaId($usucreaId) {
            $this->usucreaId = $usucreaId;
        }

        /**
         * Obtiene la fecha de creación.
         *
         * @return string La fecha de creación.
         */
        public function getFechaCrea() { 
            return $this->fechaCrea;
        }

        /**
         * Establece la fecha de creación.
         *
         * @param string $fechaCrea La fecha de creación.
         */
        public function setFechaCrea($fechaCrea) {
            $this->fechaCrea = $fechaCrea;
        }


        /**
         * Obtiene el ID del usuario que modificó el registro.
         *
         * @return int El ID del usuario que modificó el registro.
         */
        public function getUsumodifId() {
            return $this->usumodifId;
        }

        /**
         * Establece el ID del usuario que modificó el registro.
         *
         * @param int $usumodifId El ID del usuario que modificó el registro.
         */
        public function setUsumodifId($usumodifId) {
            $this->usumodifId = $usumodifId;
        }

        /**
         * Obtiene la fecha en que se modificó el registro.
         *
         * @return string La fecha en que se modificó el registro.
         */
        public function getFechaModif() {
            return $this->fechaModif;
        }

        /**
         * Establece la fecha de modificación.
         *
         * @param mixed $fechaModif La fecha de modificación a establecer.
         */
        public function setFechaModif($fechaModif) {
            $this->fechaModif = $fechaModif;
        }

        /**
         * Obtiene el orden.
         *
         * @return mixed El orden.
         */
        public function getOrden() {
            return $this->orden;
        }

        /**
         * Establece el orden.
         *
         * @param mixed $orden El orden a establecer.
         */
        public function setOrden($orden) {
            $this->orden = $orden;
        }

        /**
         * Obtiene el ID del país.
         *
         * @return mixed El ID del país.
         */
        public function getPaisId() {
            return $this->pais_id;
        }

        /**
         * Establece el ID del país.
         *
         * @param mixed $pais_id El ID del país a establecer.
         */
        public function setPaisId($pais_id) {
            $this->pais_id = $pais_id;
        }

        /**
         * Obtiene las categorías según el tipo, mandante y país.
         *
         * @param string $tipo El tipo de categoría.
         * @param string $mandante El mandante de la categoría.
         * @param int $paisId El ID del país.
         * @return array Las categorías que coinciden con los parámetros dados.
         */
        public function getCategoriasTipo($tipo, $mandante, $paisId) {
            $CategoriaMandanteMySqlDAO = new CategoriaMandanteMySqlDAO();
            return $CategoriaMandanteMySqlDAO->queryByTipo($tipo, $mandante, $paisId);
        }


        /**
         * Obtiene una lista personalizada de categorías de mandante.
         *
         * @param string $select Campos a seleccionar en la consulta.
         * @param string $sidx Índice de ordenación.
         * @param string $sord Orden de clasificación (ascendente o descendente).
         * @param int $start Posición inicial para la consulta.
         * @param int $limit Número máximo de registros a devolver.
         * @param array $filters Filtros a aplicar en la consulta.
         * @param bool $searchOn Indica si la búsqueda está activada.
         * @return array Lista de categorías de mandante.
         * @throws \Exception Si no existen categorías de mandante.
         */
        public function getCategoriaMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $CategoriaMandanteMySqlDAO = new CategoriaMandanteMySqlDAO();
            $Productos = $CategoriaMandanteMySqlDAO->querytCategoriasMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

            if ($Productos != '') return $Productos;
            throw new \Exception('No existe ' . get_class($this), '01');
        }

    }

?>
