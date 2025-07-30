<?php 
    namespace Backend\mysql;
    use Backend\dto\CategoriaMandante;
    use Backend\sql\Transaction;
    use Backend\sql\SqlQuery;
    use Backend\sql\QueryExecutor;

    /** 
     * Clase CategoriaMandanteMySqlDAO
     * Provee las solicitudes vinculadas a la tabla categoria_mandante
     * 
     * @author Desconocido
     * @since: Desconocido
     * @category No
     * @package No
     * @version     1.0
     */
    class CategoriaMandanteMySqlDAO {

        /**
         * Atributo Transaction transacción
         *
         * @var Objeto
         */
        private $transaction;

        /**
         * Constructor de la clase
         *
         * CategoriaMandanteMySqlDAO constructor.
         * @param $transaction
         */
        public function __construct($transaction = null) {
            if ($transaction == '') {
                $this->transaction = new Transaction();
            } else {
                $this->transaction = $transaction;
            }
        }

        /**
         * Obtener la transacción actual.
         *
         * @return Objeto La transacción actual.
         */
        public function getTransaction() {
            return $this->transaction;
        }

        /**
         * Establecer una nueva transacción.
         *
         * @param Objeto $transaction La nueva transacción.
         */
        public function setTransaction($transaction) {
            $this->transaction = $transaction;
        }

        /**
         * Obtener todos los registros condicionados por la
         * llave primaria que se pasa como parámetro
         *
         * @param String $id llave primaria
         *
         * @return Array $ resultado de la consulta
         *
         */
        public function load($id) {
            $sql = 'SELECT * FROM categoria_mandante WHERE catmandante_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->getRow($sqlQuery);
        }

        /**
         * Obtener todos los registros de la base datos
         *
         * @return Array $ resultado de la consulta
         *
         */
        public function queryAll() {
            $sql = 'SELECT * FROM categoria_mandante';
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Obtener todos los registros
         * ordenadas por el nombre de la columna
         * que se pasa como parámetro
         *
         * @param String $orderColumn nombre de la columna
         *
         * @return Array $ resultado de la consulta
         *
         */
        public function queryAllOrderBy($orderColumn) {
            $sql = 'SELECT * FROM categoria_mandante ORDER BY ' . $orderColumn;
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Eliminar todos los registros condicionados
         * por la llave primaria
         *
         * @param String $id llave primaria
         *
         * @return boolean $ resultado de la consulta
         *
         */
        public function delete($id) {
            $sql = 'DELETE FROM categoria_mandante WHERE catmandante_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Insertar un registro en la base de datos
         *
         * @param Objeto $CategoriaMandante categoria_mandante
         *
         * @return String $id resultado de la consulta
         *
         */
        public function insert($CategoriaMandante) {
            $sql = 'INSERT INTO categoria_mandante (descripcion, tipo, mandante, slug, estado, imagen, usucrea_id, usumodif_id, orden, pais_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($CategoriaMandante->getDescripcion());
            $sqlQuery->set($CategoriaMandante->getTipo());
            $sqlQuery->setNumber($CategoriaMandante->getMandante());
            $sqlQuery->set($CategoriaMandante->getSlug());
            $sqlQuery->set($CategoriaMandante->getEstado());
            $sqlQuery->set($CategoriaMandante->getImagen());
            $sqlQuery->setNumber($CategoriaMandante->getUsucreaId());
            $sqlQuery->setNumber($CategoriaMandante->getUsumodifId());
            $sqlQuery->setNumber($CategoriaMandante->getOrden());
            $sqlQuery->setNumber($CategoriaMandante->getPaisId());

            $id = $this->executeInsert($sqlQuery);
            return $id;
        }

        /**
         * Editar un registro en la base de datos
         *
         * @param Objeto $CategoriaMandante categoria_mandante
         *
         * @return boolean $ resultado de la consulta
         *
         */
        public function update($CategoriaMandante) {
            $sql = 'UPDATE categoria_mandante SET descripcion = ?, tipo = ?, slug = ?, estado = ?, imagen = ?, usucrea_id = ?, usumodif_id = ?, orden = ?, pais_id = ?,fecha_modif = ? WHERE catmandante_id = ?';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($CategoriaMandante->getDescripcion());
            $sqlQuery->set($CategoriaMandante->getTipo());
            $sqlQuery->set($CategoriaMandante->getSlug());
            $sqlQuery->set($CategoriaMandante->getEstado());
            $sqlQuery->set($CategoriaMandante->getImagen());
            $sqlQuery->setNumber($CategoriaMandante->getUsucreaId());
            $sqlQuery->setNumber($CategoriaMandante->getUsumodifId());
            $sqlQuery->setNumber($CategoriaMandante->getOrden());
            $sqlQuery->setNumber($CategoriaMandante->getPaisId());
            $sqlQuery->set($CategoriaMandante->getFechaModif());

            $sqlQuery->setNumber($CategoriaMandante->getCatmandanteId());
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Eliminar todas los registros de la base de datos
         *
         * @param no
         *
         * @return boolean $ resultado de la consulta
         *
         */
        public function clean() {
            $sql = 'DELETE FROM categoria_mandante';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Obtener todos los registros donde se encuentre que
         * la columna descripcion sea igual al valor pasado como parámetro
         *
         * @param String $value descripcion requerido
         *
         * @return Array $ resultado de la consulta
         *
         */
        public function queryByDescripcion($value) {
            $sql = 'SELECT * FROM CategoriaMandante WHERE descripcion = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($value);
            return $this->getList($sqlQuery);
        }

        /**
         * Obtener todos los registros donde se encuentre que
         * la columnas tipo, mandante y paisId sean igual al valor pasado como parámetro
         *
         * @param String $tipo tipo requerido
         * @param String $mandante tipo requerido
         * @param String $paisId tipo requerido
         *
         * @return Array $ resultado de la consulta
         *
         */
        public function queryByTipo($tipo, $mandante, $paisId) {
            $sql = 'SELECT * FROM categoria_mandante WHERE tipo = ? AND mandante = ? AND pais_id = ? ORDER BY orden';
            if($_ENV["debugFixed2"] == '1'){

                print_r($sql);
            }
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($tipo);
            $sqlQuery->setNumber($mandante);
            $sqlQuery->setNumber($paisId);
            return $this->getList($sqlQuery);
        }

        /**
         * Obtener todos los registros donde se encuentre que
         * la columna slug sea igual al valor pasado como parámetro
         *
         * @param String $value slug requerido
         *
         * @return Array $ resultado de la consulta
         *
         */
        public function queryBySlug($value) {
            $sql = 'SELECT * FROM categoria_mandante WHERE slug = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($value);
            return $this->getList($sqlQuery);
        }


/**
         * Eliminar registros por descripción.
         *
         * @param String $value Descripción del registro a eliminar.
         * @return boolean Resultado de la consulta.
         */
        public function deleteByDescripcion($value) {
            $sql = 'DELETE FROM categoria_mandante WHERE descripcion = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($value);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Eliminar registros por tipo.
         *
         * @param String $value Tipo del registro a eliminar.
         * @return boolean Resultado de la consulta.
         */
        public function deleteByTipo($value) {
            $sql = 'DELETE FROM categoria_mandante WHERE tipo = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($value);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Realiza una consulta personalizada en la tabla `categoria_mandante` con filtros y paginación.
         *
         * @param string $select Campos a seleccionar en la consulta.
         * @param string $sidx Columna por la cual ordenar los resultados.
         * @param string $sord Dirección de la ordenación (ASC o DESC).
         * @param int $start Índice de inicio para la paginación.
         * @param int $limit Número de registros a devolver.
         * @param string $filters Filtros en formato JSON para aplicar a la consulta.
         * @param bool $searchOn Indica si se deben aplicar los filtros.
         * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
         */
        public function querytCategoriasMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $where = " where 1=1 ";

            if ($searchOn) {
                $filters = json_decode($filters);
                $whereArray = array();
                $rules = $filters->rules;
                $groupOperation = $filters->groupOp;
                $cont = 0;

                foreach ($rules as $rule) {
                    $fieldName = $rule->field;
                    $fieldData = $rule->data;
                    switch ($rule->op) {
                        case "eq":
                            $fieldOperation = " = '" . $fieldData . "'";
                            break;
                        case "ne":
                            $fieldOperation = " != '" . $fieldData . "'";
                            break;
                        case "lt":
                            $fieldOperation = " < '" . $fieldData . "'";
                            break;
                        case "gt":
                            $fieldOperation = " > '" . $fieldData . "'";
                            break;
                        case "le":
                            $fieldOperation = " <= '" . $fieldData . "'";
                            break;
                        case "ge":
                            $fieldOperation = " >= '" . $fieldData . "'";
                            break;
                        case "nu":
                            $fieldOperation = " = ''";
                            break;
                        case "nn":
                            $fieldOperation = " != ''";
                            break;
                        case "in":
                            $fieldOperation = " IN (" . $fieldData . ")";
                            break;
                        case "ni":
                            $fieldOperation = " NOT IN '" . $fieldData . "'";
                            break;
                        case "bw":
                            $fieldOperation = " LIKE '" . $fieldData . "%'";
                            break;
                        case "bn":
                            $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                            break;
                        case "ew":
                            $fieldOperation = " LIKE '%" . $fieldData . "'";
                            break;
                        case "en":
                            $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                            break;
                        case "cn":
                            $fieldOperation = " LIKE '%" . $fieldData . "%'";
                            break;
                        case "nc":
                            $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                            break;
                        default:
                            $fieldOperation = "";
                            break;
                    }
                    if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                    if (oldCount($whereArray) > 0) {
                        $where = $where . " " . $groupOperation . " " . $fieldName . " " . strtoupper($fieldOperation);
                    } else {
                        $where = "";
                    }
                }

            }

            $sql = 'SELECT count(*) count FROM categoria_mandante ' . $where;

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);

            $sql = 'SELECT ' . $select . ' FROM categoria_mandante ' . $where . ' order by ' . $sidx . ' ' . $sord . ' LIMIT ' . $start . ' , ' . $limit;

            $sqlQuery = new SqlQuery($sql);

            $result = $this->execute2($sqlQuery);

            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

            return $json;
        }

/**
         * Leer una fila de la base de datos y convertirla en un objeto CategoriaMandante.
         *
         * @param array $row Fila de la base de datos.
         * @return CategoriaMandante Objeto CategoriaMandante.
         */
        protected function readRow($row) {
            $CategoriaMandante = new CategoriaMandante();

            $CategoriaMandante->setCatmandanteId($row['catmandante_id']);
            $CategoriaMandante->setDescripcion($row['descripcion']);
            $CategoriaMandante->setTipo($row['tipo']);
            $CategoriaMandante->setMandante($row['mandante']);
            $CategoriaMandante->setSlug($row['slug']);
            $CategoriaMandante->setEstado($row['estado']);
            $CategoriaMandante->setImangen($row['imagen']);
            $CategoriaMandante->setUsucreaId($row['usucrea_id']);
            $CategoriaMandante->setFechaCrea($row['fecha_crea']);
            $CategoriaMandante->setUsumodifId($row['usumodif_id']);
            $CategoriaMandante->setFechaModif($row['fecha_modif']);
            $CategoriaMandante->setOrden($row['orden']);
            $CategoriaMandante->setPaisId($row['pais_id']);

            return $CategoriaMandante;
        }

        /**
         * Obtener una lista de objetos CategoriaMandante a partir de una consulta SQL.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return array Lista de objetos CategoriaMandante.
         */
        protected function getList($sqlQuery) {
            $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
            $ret = array();
            for ($i = 0; $i < oldCount($tab); $i++) {
                $ret[$i] = $this->readRow($tab[$i]);
            }
            return $ret;
        }

        /**
         * Obtener una fila de la base de datos a partir de una consulta SQL.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return CategoriaMandante|null Objeto CategoriaMandante o null si no se encuentra.
         */
        protected function getRow($sqlQuery) {
            $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
            if (oldCount($tab) == 0) {
                return null;
            }
            return $this->readRow($tab[0]);
        }

        /**
         * Ejecutar una consulta SQL.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return mixed Resultado de la consulta.
         */
        protected function execute($sqlQuery) {
            return QueryExecutor::execute($this->transaction,$sqlQuery);
        }

        /**
         * Ejecutar una consulta SQL y devolver el resultado.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return mixed Resultado de la consulta.
         */
        protected function execute2($sqlQuery) {
            return QueryExecutor::execute2($this->transaction, $sqlQuery);
        }

        /**
         * Ejecutar una consulta SQL de actualización.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return int Número de filas afectadas.
         */
        protected function executeUpdate($sqlQuery) {
            return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
        }

        /**
         * Ejecutar una consulta SQL y devolver un único resultado.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return string Resultado de la consulta.
         */
        protected function querySingleResult($sqlQuery) {
            return QueryExecutor::queryForString($this->transaction,$sqlQuery);
        }

        /**
         * Ejecutar una consulta SQL de inserción.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return int ID del registro insertado.
         */
        protected function executeInsert($sqlQuery) {
            return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
        }
    }
?>
