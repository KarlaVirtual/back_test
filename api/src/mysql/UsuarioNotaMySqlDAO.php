<?php
    
    namespace Backend\mysql;

    use Backend\dto\UsuarioNota;
    use Backend\sql\QueryExecutor;
    use Backend\sql\SqlQuery;
    use Backend\sql\Transaction;

    /**
     * Clase UsuarioNotaMySqlDAO
     * 
     * Clase encargada de proveer las consultas vinculadas a la tabla usuario_nota de la base de datos
     * 
     * @author: Desconocido
     * @package No
     * @category No
     * @version    1.0
     * @since Descocido
     */
    class UsuarioNotaMySqlDAO {

        /**
         * Objeto contiene la relación entre la conexión a la base de datos y UsuarioRestriccionMySqlDAO
         * @var Transaction
         */
        private $transaction;


        /**
         * Constructor de la clase UsuarioNotaMySqlDAO.
         *
         * @param Transaction $transaction Objeto de transacción opcional.
         */
        public function __construct($transaction = '') {
            if($transaction != '') {
                $this->transaction = $transaction;
            } else {
                $this->transaction = new Transaction();
            }
        }

        /**
         * Establece la transacción.
         *
         * @param Transaction $transaction Objeto de transacción.
         */
        public function setTransaction($transaction) {
            $this->transaction = $transaction;
        }

        /**
         * Obtiene la transacción actual.
         *
         * @return Transaction La transacción actual.
         */
        public function getTransaction() {
            return $this->transaction;
        }

        /**
         * Carga un registro de usuario_nota por ID.
         *
         * @param int $id El ID del registro a cargar.
         * @return UsuarioNota El objeto UsuarioNota cargado.
         */
        public function load($id) {
            $sql = 'SELECT * FROM usuario_nota WHERE usunota_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro de usuario_nota por usuario y tipo.
         *
         * @param int $usuarioId El ID del usuario.
         * @param string $tipo El tipo de nota.
         * @return UsuarioNota El objeto UsuarioNota cargado.
         */
        public function loadFormByUsuarioTipo($usuarioId, $tipo) {
            $sql = 'SELECT * FROM usuario_nota WHERE usuto_id = ? AND tipo = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuarioId);
            $sqlQuery->set($tipo);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro de usuario_nota por referencia y tipo.
         *
         * @param int $refId El ID de referencia.
         * @param string $tipo El tipo de nota.
         * @return UsuarioNota El objeto UsuarioNota cargado.
         */
        public function loadFormByRefTipo($refId, $tipo) {
            $sql = 'SELECT * FROM usuario_nota WHERE ref_id = ? AND tipo = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($refId);
            $sqlQuery->set($tipo);
            return $this->getRow($sqlQuery);
        }

        /**
         * Consulta todos los registros de usuario_nota.
         *
         * @return array Lista de objetos UsuarioNota.
         */
        public function queryAll(){
            $sql = 'SELECT * FROM usuario_nota';
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Consulta todos los registros de usuario_nota ordenados por una columna.
         *
         * @param string $orderColumn La columna por la cual ordenar los resultados.
         * @return array Lista de objetos UsuarioNota.
         */
        public function queryAllOrderBy($orderColumn) {
            $sql = 'SELECT * FROM usuario_nota ORDER BY '. $orderColumn;
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Elimina un registro de usuario_nota por ID.
         *
         * @param int $usunotaId El ID del registro a eliminar.
         * @return int El número de filas afectadas por la eliminación.
         */
        public function delete($usunotaId) {
            $sql = 'DELETE FROM usuario_nota WHERE formgenerico_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usunotaId);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Inserta un nuevo registro en la tabla usuario_nota.
         *
         * @param UsuarioNota $usuarioNota El objeto UsuarioNota que contiene los datos a insertar.
         * @return int El ID del registro insertado.
         */
        public function insert($usuarioNota) {
            $sql = 'INSERT INTO usuario_nota (tipo, descripcion, usufrom_id, usuto_id, mandante, pais_id, ref_id, usucrea_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $sqlQuery = new SqlQuery($sql);
            
            $sqlQuery->set($usuarioNota->getTipo());
            $sqlQuery->set($usuarioNota->getDescripcion());
            $sqlQuery->setNumber($usuarioNota->getUsufromId());
            $sqlQuery->setNumber($usuarioNota->getUsutoId());
            $sqlQuery->setNumber($usuarioNota->getMandante());
            $sqlQuery->setNumber($usuarioNota->getPaisId());
            $sqlQuery->setNumber($usuarioNota->getRefId());
            $sqlQuery->setNumber($usuarioNota->getUsucreaId());
    
            $id = $this->executeInsert($sqlQuery);

            return $id;
        }

        /**
         * Actualiza un registro en la tabla usuario_nota.
         *
         * @param UsuarioNota $usuarioNota El objeto UsuarioNota que contiene los datos a actualizar.
         * @return int El número de filas afectadas por la actualización.
         */
        public function update($usuarioNota) {
            $sql = 'UPDATE usuario_nota SET tipo = ?, descripcion = ?, usufrom_id = ?, usuto_id = ?, mandante = ?, pais_id, ref_id = ?, usumodif_if  WHERE usunota_id = ?';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($usuarioNota->getTipo());
            $sqlQuery->set($usuarioNota->getDescripcion());
            $sqlQuery->setNumber($usuarioNota->getUsufromId());
            $sqlQuery->setNumber($usuarioNota->getUsuToId());
            $sqlQuery->setNumber($usuarioNota->getMandante());
            $sqlQuery->setNumber($usuarioNota->getPaisId());
            $sqlQuery->setNumber($usuarioNota->getRefId());
            $sqlQuery->setNumber($usuarioNota->getUsumodifId());
            $sqlQuery->setNumber($usuarioNota->getUsunotaId());

            return $this->executeUpdate($sqlQuery);
        }

        public function clean() {
            $sql = 'DELETE FROM usuario_nota';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }
    
        /**
         * Realiza una consulta personalizada en la tabla usuario_nota con filtros y ordenación.
         *
         * @param string $select Campos a seleccionar en la consulta.
         * @param string $sidx Campo por el cual se ordenarán los resultados.
         * @param string $sord Orden de los resultados (ASC o DESC).
         * @param int $start Índice de inicio para la paginación.
         * @param int $limit Número de registros a devolver.
         * @param string $filters Filtros en formato JSON para aplicar en la consulta.
         * @param bool $searchOn Indica si se deben aplicar los filtros.
         * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
         */
        public function queryUsuarioNotaCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
    
            $where = "where 1 = 1";
    
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
                    if ($fieldOperation != "") {
                        $whereArray[] = $fieldName . $fieldOperation;
                    }
    
                    if (oldCount($whereArray) > 0) {
                        $where = $where . " " . $groupOperation . " " . $fieldName . " " . strtoupper($fieldOperation);
                    } else {
                        $where = "";
                    }
                }
            }
    
            $sql = "SELECT count(*) count FROM usuario_nota INNER JOIN mandante ON (mandante.mandante=usuario_nota.mandante) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = usuario_nota.tipo) LEFT OUTER JOIN usuario_mandante usuto ON (usuto.usumandante_id = usuario_nota.usuto_id) LEFT OUTER JOIN usuario_mandante usufrom ON (usufrom.usumandante_id = usuario_nota.usufrom_id)" . $where;
    
            $sqlQuery = new SqlQuery($sql);
    
            $count = $this->execute2($sqlQuery);
            $sql = "SELECT " . $select . " FROM usuario_nota INNER JOIN mandante ON (mandante.mandante=usuario_nota.mandante) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = usuario_nota.tipo) LEFT OUTER JOIN usuario_mandante usuto ON (usuto.usumandante_id = usuario_nota.usuto_id) LEFT OUTER JOIN usuario_mandante usufrom ON (usufrom.usumandante_id = usuario_nota.usufrom_id)" . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
    
            $sqlQuery = new SqlQuery($sql);

            $result = $this->execute2($sqlQuery);
    
            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
    
            return $json;
        }

        /**
         * Lee una fila de la base de datos y la convierte en un objeto UsuarioNota.
         *
         * @param array $row La fila de la base de datos representada como un array asociativo.
         * @return UsuarioNota El objeto UsuarioNota creado a partir de la fila de la base de datos.
         */
        protected function readRow($row) {
            $UsuarioNota = new UsuarioNota();

            $UsuarioNota->setUsunotaId($row['usunota_id']);
            $UsuarioNota->setTipo($row['tipo']);
            $UsuarioNota->setDescripcion($row['descripcion']);
            $UsuarioNota->setUsufromId($row['usufrom_id']);
            $UsuarioNota->setUsunotaId($row['usuto_id']);
            $UsuarioNota->setMandante($row['mandante']);
            $UsuarioNota->setRefId($row['ref_id']);
            $UsuarioNota->setUsucreaId($row['usucrea_id']);
            $UsuarioNota->setUsumodifId($row['usumodif_id']);
            $UsuarioNota->setFechaCrea($row['fehca_crea']);
            $UsuarioNota->setFechaModif($row['fecha_modif']);
    
            return $UsuarioNota;
        }

/**
         * Obtiene una lista de objetos UsuarioNota a partir de una consulta SQL.
         *
         * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
         * @return array Lista de objetos UsuarioNota.
         */
        protected function getList($sqlQuery) {
            $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
            $ret = array();
            for ($i = 0; $i < oldCount($tab); $i++) {
                $ret[$i] = $this->readRow($tab[$i]);
            }
            return $ret;
        }

        /**
         * Obtiene un objeto UsuarioNota a partir de una consulta SQL.
         *
         * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
         * @return UsuarioNota|null El objeto UsuarioNota o null si no se encuentra.
         */
        protected function getRow($sqlQuery) {
            $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
            if (oldCount($tab) == 0) {
                return null;
            }
            return $this->readRow($tab[0]);
        }

        /**
         * Ejecuta una consulta SQL.
         *
         * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
         * @return mixed El resultado de la consulta.
         */
        protected function execute($sqlQuery) {
            return QueryExecutor::execute($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una consulta SQL con un método alternativo.
         *
         * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
         * @return mixed El resultado de la consulta.
         */
        protected function execute2($sqlQuery) {
            return QueryExecutor::execute2($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una actualización SQL.
         *
         * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
         * @return int El número de filas afectadas por la actualización.
         */
        protected function executeUpdate($sqlQuery) {
            return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una consulta SQL y obtiene un único resultado.
         *
         * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
         * @return string El resultado de la consulta.
         */
        protected function querySingleResult($sqlQuery) {
            return QueryExecutor::queryForString($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una inserción SQL.
         *
         * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
         * @return int El ID del registro insertado.
         */
        protected function executeInsert($sqlQuery) {
            return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
        }
    }
?>