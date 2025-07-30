<?php
    namespace Backend\mysql;

    use Backend\dto\UsuarioRestriccion;
    use Backend\sql\SqlQuery;
    use Backend\sql\Transaction;
    use Backend\sql\QueryExecutor;

    /**
     * Clase usuarioRestriccionMySqlDAO
     * Clase encargada de proveer las consultas de la tabla usuario_restriccion
     * 
     * @author Desconocido
     * @package No
     * @category No
     * @version    1.0
     * @since Desconocido
     * 
     */
    class UsuarioRestriccionMySqlDAO {
        /**
         * Objeto contiene la relación entre la conexión a la base de datos y UsuarioRestriccionMySqlDAO
         * @var Transaction
         */
        private $transaction;

        /**
         * Constructor de la clase UsuarioRestriccionMySqlDAO.
         *
         * @param Transaction|null $transaction Objeto de transacción opcional.
         */
        public function __construct($transaction = null) {
            if($transaction == null) $this->transaction = new Transaction();
            else $this->transaction = $transaction;
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
         * Establece una nueva transacción.
         *
         * @param Transaction $transaction La nueva transacción.
         */
        public function setTransaction($transaction) {
            $this->transaction = $transaction;
        }

        /**
         * Carga un registro de usuario_restriccion por ID.
         *
         * @param int $id El ID del registro a cargar.
         * @return UsuarioRestriccion|null El objeto UsuarioRestriccion o null si no se encuentra.
         */
        public function load($id) {
            $sql = 'SELECT * FROM usuario_restriccion WHERE usurestriccion_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro de usuario_restriccion por documento y tipo de documento.
         *
         * @param string $documento El documento del usuario.
         * @param string $tipoDoc El tipo de documento del usuario.
         * @return UsuarioRestriccion|null El objeto UsuarioRestriccion o null si no se encuentra.
         */
        public function loadByDocument($documento, $tipoDoc) {
            $sql = 'SELECT * FROM usuario_restriccion WHERE documento = ? AND tipo_doc = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($documento);
            $sqlQuery->set($tipoDoc);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro de usuario_restriccion por email.
         *
         * @param string $value El email del usuario.
         * @return UsuarioRestriccion|null El objeto UsuarioRestriccion o null si no se encuentra.
         */
        public function loadByEmail($value) {
            $sql = 'SELECT * FROM usuario_restriccion WHERE email = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($value);
            return $this->getRow($sqlQuery);
        }

        /**
         * Consulta todos los registros de usuario_restriccion.
         *
         * @return array Lista de objetos UsuarioRestriccion.
         */
        public function queryAll(){
            $sql = 'SELECT * FROM usuario_restriccion';
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Consulta todos los registros de usuario_restriccion ordenados por una columna específica.
         *
         * @param string $orderColumn La columna por la cual ordenar los registros.
         * @return array Lista de objetos UsuarioRestriccion ordenados.
         */
        public function queryAllOrderBy($orderColumn) {
            $sql = 'SELECT * FROM usuario_restriccion ORDER BY '. $orderColumn;
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Elimina un registro de usuario_restriccion por ID.
         *
         * @param int $value El ID del registro a eliminar.
         * @return int El número de filas afectadas por la eliminación.
         */
        public function delete($value) {
            $sql = 'DELETE FROM usuario_restriccion WHERE usurestriccion_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($value);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Inserta un nuevo registro en la tabla usuario_restriccion.
         *
         * @param UsuarioRestriccion $usuarioRestriccion El objeto UsuarioRestriccion que contiene los datos a insertar.
         * @return int El ID del registro insertado.
         */
        public function insert($usuarioRestriccion) {
            $sql = 'INSERT INTO usuario_restriccion (email, usuario_id, documento, tipo_doc, nombre, telefono, mandante, pais_id, estado, clasificador_id, usucrea_id, metodo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($usuarioRestriccion->getEmail());
            $usuarioRestriccion->getUsuarioId() != null ?$sqlQuery->set($usuarioRestriccion->getUsuarioId()) : $sqlQuery->setSIN('null');
            $sqlQuery->set($usuarioRestriccion->getDocumento());
            $sqlQuery->set($usuarioRestriccion->getTipoDoc());
            $sqlQuery->set($usuarioRestriccion->getNombre());
            $sqlQuery->set($usuarioRestriccion->getTelefono());
            $sqlQuery->setNumber($usuarioRestriccion->getMandante());
            $sqlQuery->setNumber($usuarioRestriccion->getPaisId());
            $sqlQuery->set($usuarioRestriccion->getEstado());
            $sqlQuery->set($usuarioRestriccion->getClasificadorId());
            $sqlQuery->setNumber($usuarioRestriccion->getUsucreaId());
            $usuarioRestriccion->getMetodo() != null ? $sqlQuery->set($usuarioRestriccion->getMetodo()) : $sqlQuery->setSIN('default');

            $id = $this->executeInsert($sqlQuery);

            return $id;
        }

        /**
         * Actualiza un registro en la tabla usuario_restriccion.
         *
         * @param UsuarioRestriccion $usuarioRestriccion El objeto UsuarioRestriccion que contiene los datos a actualizar.
         * @return int El número de filas afectadas por la actualización.
         */
        public function update($usuarioRestriccion) {
            $sql = 'UPDATE usuario_restriccion SET email = ?, usuario_id = ?, documento = ?, tipo_doc = ?, nombre = ?, telefono = ?, nota = ?, mandante = ?, pais_id = ?, estado = ?, clasificador_id = ?, usumodif_id = ?, metodo = ?  WHERE usurestriccion_id = ?';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($usuarioRestriccion->getEmail());

            if($usuarioRestriccion->getUsuarioId() == null)$sqlQuery->setSIN('null');
            else{
                $sqlQuery->set($usuarioRestriccion->getUsuarioId());
            }
            $sqlQuery->set($usuarioRestriccion->getDocumento());
            $sqlQuery->set($usuarioRestriccion->getTipoDoc());
            $sqlQuery->set($usuarioRestriccion->getNombre());
            $sqlQuery->set($usuarioRestriccion->getTelefono());
            $sqlQuery->set($usuarioRestriccion->getNota());
            $sqlQuery->setNumber($usuarioRestriccion->getMandante());
            $sqlQuery->setNumber($usuarioRestriccion->getPaisId());
            $sqlQuery->set($usuarioRestriccion->getEstado());
            $sqlQuery->set($usuarioRestriccion->getClasificadorId());
            $sqlQuery->set($usuarioRestriccion->getUsumodifId());
            $usuarioRestriccion->getMetodo() != null ? $sqlQuery->set($usuarioRestriccion->getMetodo()) : $sqlQuery->setSIN('default');
            $sqlQuery->setNumber($usuarioRestriccion->getUsurestriccionId());

            return $this->executeUpdate($sqlQuery);
        }

        public function clean() {
            $sql = 'DELETE FROM usuario_restriccion';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }
    
        /**
         * Consulta personalizada de restricciones de usuario.
         *
         * @param string $select Campos a seleccionar en la consulta.
         * @param string $sidx Índice de ordenación.
         * @param string $sord Orden de clasificación (ASC o DESC).
         * @param int $start Inicio de los registros a recuperar.
         * @param int $limit Límite de registros a recuperar.
         * @param string $filters Filtros en formato JSON.
         * @param bool $searchOn Indica si la búsqueda está activada.
         * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
         */
        public function queryUsuarioRestriccionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
    
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
    
            $sql = "SELECT count(*) count FROM usuario_restriccion LEFT OUTER JOIN mandante ON (mandante.mandante=usuario_restriccion.mandante) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = usuario_restriccion.clasificador_id) " . $where;
    
            $sqlQuery = new SqlQuery($sql);
    
            $count = $this->execute2($sqlQuery);
            $sql = "SELECT " . $select . " FROM usuario_restriccion LEFT OUTER JOIN mandante ON (mandante.mandante=usuario_restriccion.mandante) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = usuario_restriccion.clasificador_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
    
            $sqlQuery = new SqlQuery($sql);

            $result = $this->execute2($sqlQuery);
    
            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
    
            return $json;
        }

        /**
         * Ejecuta una consulta SQL personalizada y devuelve el resultado en formato JSON.
         *
         * @param string $sql La consulta SQL a ejecutar.
         * @return string El resultado de la consulta en formato JSON.
         */
        public function queryCustom($sql) {
            $sqlQuery = new SqlQuery($sql);
            $query = json_encode($this->execute2($sqlQuery));
            return '{ "result": ' . $query . '}';
        }

        /**
         * Lee una fila de la base de datos y la convierte en un objeto UsuarioRestriccion.
         *
         * @param array $row La fila de la base de datos representada como un array asociativo.
         * 
         * @return UsuarioRestriccion El objeto UsuarioRestriccion con los datos de la fila.
         */
        protected function readRow($row) {
            $UsuarioRestriccion = new UsuarioRestriccion();

            $UsuarioRestriccion->setUsurestriccionId($row['usurestriccion_id']);
            $UsuarioRestriccion->setUsuarioId($row['usuario_id']);
            $UsuarioRestriccion->setEmail($row['email']);
            $UsuarioRestriccion->setDocumento($row['documento']);
            $UsuarioRestriccion->setTipoDoc($row['tipo_doc']);
            $UsuarioRestriccion->setNombre($row['nombre']);
            $UsuarioRestriccion->setTelefono($row['telefono']);
            $UsuarioRestriccion->setNota($row['nota']);
            $UsuarioRestriccion->setMandante($row['mandante']);
            $UsuarioRestriccion->setPaisId($row['pais_id']);
            $UsuarioRestriccion->setEstado($row['estado']);
            $UsuarioRestriccion->setClasificadorId($row['clasificador_id']);
            $UsuarioRestriccion->setUsucreaId($row['usucrea_id']);
            $UsuarioRestriccion->setUsumodifId($row['usumodif_id']);
            $UsuarioRestriccion->setFechaCrea($row['fecha_crea']);
            $UsuarioRestriccion->setFechaModif($row['fecha_modif']);
            $UsuarioRestriccion->setMetodo($row['metodo']);
    
            return $UsuarioRestriccion;
        }
    
/**
             * Obtiene una lista de objetos UsuarioRestriccion a partir de una consulta SQL.
             *
             * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
             * @return array Lista de objetos UsuarioRestriccion.
             */
            protected function getList($sqlQuery) {
                $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
                $ret = array();
                for($i=0; $i < oldCount($tab); $i++) $ret[$i] = $this->readRow($tab[$i]);
                return $ret;
            }

            /**
             * Obtiene un objeto UsuarioRestriccion a partir de una consulta SQL.
             *
             * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
             * @return UsuarioRestriccion|null El objeto UsuarioRestriccion o null si no se encuentra.
             */
            protected function getRow($sqlQuery) {
                $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
                if(oldCount($tab) == 0) return null;
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
             * Ejecuta una consulta SQL y devuelve el resultado.
             *
             * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
             * @return mixed El resultado de la consulta.
             */
            protected function execute2($sqlQuery) {
                return QueryExecutor::execute2($this->transaction, $sqlQuery);
            }

            /**
             * Ejecuta una consulta SQL de actualización.
             *
             * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
             * @return int El número de filas afectadas por la actualización.
             */
            protected function executeUpdate($sqlQuery) {
                return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
            }

            /**
             * Ejecuta una consulta SQL y devuelve un único resultado.
             *
             * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
             * @return string El resultado de la consulta.
             */
            protected function querySingleResult($sqlQuery) {
                return QueryExecutor::queryForString($this->transaction, $sqlQuery);
            }

            /**
             * Ejecuta una consulta SQL de inserción.
             *
             * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
             * @return int El ID del registro insertado.
             */
            protected function executeInsert($sqlQuery) {
                return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
            }

    }
?>