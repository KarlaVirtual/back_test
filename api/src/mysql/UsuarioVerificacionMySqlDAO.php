<?php

    namespace Backend\mysql;

    use Backend\sql\SqlQuery;
    use Backend\sql\Transaction;
    use Backend\sql\QueryExecutor;
    use Backend\dto\UsuarioVerificacion;

    /** 
     * Clase UsuarioVerificacionMySqlDAO
     * 
     * Esta clase provee las consultas de la tabla usuario_verificacion
     * 
     * @author Desconocido
     * @package No
     * @category No
     * @version    1.0
     * @since   Desconocido

     */
    class UsuarioVerificacionMySqlDAO {
        /**
         * Objeto Transaction vinculado a la conexión establecida entre la base de datos y el objeto MySqlDAO
         * @var Transaction
         */
        private $transaction;


        /**
         * Constructor de la clase UsuarioVerificacionMySqlDAO.
         *
         * @param Transaction|null $transaction Una instancia de la clase Transaction. Si no se proporciona, se creará una nueva instancia.
         */
        public function __construct($transaction = null) {
            if($transaction == null) {
                $this->transaction = new Transaction();
            } else {
                $this->transaction = $transaction;
            }
        }

/**
         * Establece la transacción para la conexión a la base de datos.
         *
         * @param Transaction $transaction La transacción a establecer.
         */
        public function setTransaction($transaction) {
            $this->transaction = $transaction;
        }

        /**
         * Obtiene la transacción actual de la conexión a la base de datos.
         *
         * @return Transaction La transacción actual.
         */
        public function getTransaction() {
            return $this->transaction;
        }

        /**
         * Carga un registro de usuario\_verificacion por su ID.
         *
         * @param int $id El ID del registro a cargar.
         * @return UsuarioVerificacion|null El objeto UsuarioVerificacion o null si no se encuentra.
         */
        public function load($id) {
            $sql = 'SELECT * FROM usuario_verificacion WHERE usuverificacion_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro de usuario\_verificacion por usuario, país y mandante.
         *
         * @param int $usuarioId El ID del usuario.
         * @param int $paisId El ID del país.
         * @param int $mandante El mandante.
         * @return UsuarioVerificacion|null El objeto UsuarioVerificacion o null si no se encuentra.
         */
        public function loadFormByUsuarioMandante($usuarioId, $paisId, $mandante) {
            $sql = 'SELECT * FROM usuario_verificacion WHERE usuario_id = ? AND pais_id = ? AND mandante = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuarioId);
            $sqlQuery->setNumber($paisId);
            $sqlQuery->setNumber($mandante);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro de usuario\_verificacion por usuario y estado.
         *
         * @param int $usuarioId El ID del usuario.
         * @param string $estado El estado del usuario.
         * @return UsuarioVerificacion|null El objeto UsuarioVerificacion o null si no se encuentra.
         */
        public function loadByUsuarioEstado($usuarioId, $estado) {
            $sql = 'SELECT * FROM usuario_verificacion WHERE usuario_id = ? AND estado = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuarioId);
            $sqlQuery->set($estado);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro de usuario\_verificacion por usuario y clasificador.
         *
         * @param int $usuarioId El ID del usuario.
         * @param int $clasificadorId El ID del clasificador.
         * @return UsuarioVerificacion|null El objeto UsuarioVerificacion o null si no se encuentra.
         */
        public function loadByUsuarioClasificador($usuarioId, $clasificadorId) {
            $sql = 'SELECT * FROM usuario_verificacion WHERE usuario_id = ? AND clasificador_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuarioId);
            $sqlQuery->setNumber($clasificadorId);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro de usuario\_verificacion por usuario, tipo y estado.
         *
         * @param int $usuarioId El ID del usuario.
         * @param string $tipo El tipo de verificación.
         * @param string $estado El estado de la verificación.
         * @return UsuarioVerificacion|null El objeto UsuarioVerificacion o null si no se encuentra.
         */
        public function loadByUsuarioTipoEstado($usuarioId, $tipo, $estado) {
            $sql = 'SELECT * FROM usuario_verificacion WHERE usuario_id = ? AND tipo = ? AND estado = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuarioId);
            $sqlQuery->set($tipo);
            $sqlQuery->set($estado);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga el registro más reciente de usuario\_verificacion por usuario y tipo.
         *
         * @param int $usuarioId El ID del usuario.
         * @param string $tipo El tipo de verificación.
         * @return UsuarioVerificacion|null El objeto UsuarioVerificacion o null si no se encuentra.
         */
        public function loadByTipo($usuarioId, $tipo) {
            $sql = 'SELECT * FROM usuario_verificacion WHERE usuario_id = ? AND tipo = ? ORDER BY fecha_crea DESC LIMIT 1';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuarioId);
            $sqlQuery->set($tipo);
            return $this->getRow($sqlQuery);
        }

        /**
         * Consulta todos los registros de la tabla usuario\_verificacion.
         *
         * @return array Un array de objetos UsuarioVerificacion.
         */
        public function queryAll(){
            $sql = 'SELECT * FROM usuario_verificacion';
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Consulta todos los registros de la tabla usuario\_verificacion ordenados por una columna específica.
         *
         * @param string $orderColumn La columna por la cual ordenar los resultados.
         * @return array Un array de objetos UsuarioVerificacion.
         */
        public function queryAllOrderBy($orderColumn) {
            $sql = 'SELECT * FROM usuario_verificacion ORDER BY '. $orderColumn;
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Elimina un registro de usuario\_verificacion por su ID.
         *
         * @param int $usuverificacionId El ID del registro a eliminar.
         * @return int El número de filas afectadas por la eliminación.
         */
        public function delete($usuverificacionId) {
            $sql = 'DELETE FROM usuario_verificacion WHERE usuverificacion_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuverificacionId);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Inserta un nuevo registro en la tabla usuario_verificacion.
         *
         * @param UsuarioVerificacion $usuarioVerificacion El objeto de tipo UsuarioVerificacion que contiene los datos a insertar.
         * @return int El ID del registro insertado.
         */
        public function insert($usuarioVerificacion) {
            $sql = 'INSERT INTO usuario_verificacion (usuario_id, mandante, pais_id, tipo, estado, observacion, usucrea_id,clasificador_id) VALUE (?, ?, ?, ?, ?, ?, ?, ?)';
            $sqlQuery = new SqlQuery($sql);
        
            $sqlQuery->setNumber($usuarioVerificacion->getUsuarioId());
            $sqlQuery->setNumber($usuarioVerificacion->getMandante());
            $sqlQuery->setNumber($usuarioVerificacion->getPaisId());
            $sqlQuery->set($usuarioVerificacion->getTipo());
            $sqlQuery->set($usuarioVerificacion->getEstado());
            $sqlQuery->set($usuarioVerificacion->getObservacion());
            $sqlQuery->setNumber($usuarioVerificacion->getUsucreaId());
            if($usuarioVerificacion->getClasificadorId() == ""){

                $usuarioVerificacion->setClasificadorId(228);
            }
            $sqlQuery->setNumber($usuarioVerificacion->getClasificadorId());


            $id = $this->executeInsert($sqlQuery);
            $usuarioVerificacion->setUsuverificacionId($id) ;

            return $id;
        }


        /**
         * Actualiza un registro en la tabla usuario_verificacion.
         *
         * @param UsuarioVerificacion $usuarioVerificacion El objeto UsuarioVerificacion que contiene los datos a actualizar.
         * @return int El número de filas afectadas por la actualización.
         */
        public function update($usuarioVerificacion) {
            $sql = 'UPDATE usuario_verificacion SET usuario_id = ?, mandante = ?, pais_id = ?, tipo = ?, estado = ?, observacion = ?, usucrea_id = ? , usumodif_id = ?, clasificador_id = ? WHERE usuverificacion_id = ?';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->setNumber($usuarioVerificacion->getUsuarioId());
            $sqlQuery->setNumber($usuarioVerificacion->getMandante());
            $sqlQuery->setNumber($usuarioVerificacion->getPaisId());
            $sqlQuery->set($usuarioVerificacion->getTipo());
            $sqlQuery->set($usuarioVerificacion->getEstado());
            $sqlQuery->set($usuarioVerificacion->getObservacion());
            $sqlQuery->setNumber($usuarioVerificacion->getUsucreaId());
            $sqlQuery->setNumber($usuarioVerificacion->getUsumodifId());
            if($usuarioVerificacion->getClasificadorId() == '') $usuarioVerificacion->setClasificadorId(228);
            $sqlQuery->setNumber($usuarioVerificacion->getClasificadorId());
            $sqlQuery->setNumber($usuarioVerificacion->getUsuVerificacionId());

            return $this->executeUpdate($sqlQuery);
        }

        public function clean() {
            $sql = 'DELETE FROM usuario_verificacion';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }
    
        /**
         * Realiza una consulta personalizada en la tabla usuario_verificacion con filtros y ordenamiento.
         *
         * @param string $select Campos a seleccionar en la consulta.
         * @param string $sidx Campo por el cual se ordenarán los resultados.
         * @param string $sord Orden de los resultados (ASC o DESC).
         * @param int $start Índice de inicio para la paginación.
         * @param int $limit Número de registros a devolver.
         * @param string $filters Filtros en formato JSON para aplicar a la consulta.
         * @param bool $searchOn Indica si se deben aplicar los filtros.
         * @param string $grouping (Opcional) Campo por el cual agrupar los resultados.
         * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
         */
        public function queryUsuarioVerificacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="") {
    
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
    
            $sql = "SELECT count(*) count FROM usuario_verificacion INNER JOIN mandante ON (mandante.mandante=usuario_verificacion.mandante) INNER JOIN pais ON (pais.pais_id = usuario_verificacion.pais_id) INNER JOIN verificacion_log ON (verificacion_log.usuverificacion_id = usuario_verificacion.usuverificacion_id) LEFT JOIN clasificador ON (clasificador.clasificador_id = usuario_verificacion.clasificador_id) " . $where;

            if ($grouping != "") {
                $where = $where . " GROUP BY " . $grouping;
            }

            $sqlQuery = new SqlQuery($sql);
    
            $count = $this->execute2($sqlQuery);
            $sql = "SELECT " . $select . " FROM usuario_verificacion INNER JOIN mandante ON (mandante.mandante=usuario_verificacion.mandante) INNER JOIN pais ON (pais.pais_id = usuario_verificacion.pais_id) INNER JOIN verificacion_log ON (verificacion_log.usuverificacion_id = usuario_verificacion.usuverificacion_id) LEFT JOIN clasificador ON (clasificador.clasificador_id = usuario_verificacion.clasificador_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

            $sqlQuery = new SqlQuery($sql);

            $result = $this->execute2($sqlQuery);
    
            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
    
            return $json;
        }

        protected function readRow($row) {
            $UsuarioVerificacion = new UsuarioVerificacion();
        
            $UsuarioVerificacion->setUsuverificacionId($row['usuverificacion_id']);
            $UsuarioVerificacion->setUsuarioId($row['usuario_id']);
            $UsuarioVerificacion->setMandante($row['mandante']);
            $UsuarioVerificacion->setPaisId($row['pais_id']);
            $UsuarioVerificacion->setTipo($row['tipo']);
            $UsuarioVerificacion->setEstado($row['estado']);
            $UsuarioVerificacion->setObservacion($row['observacion']);
            $UsuarioVerificacion->setUsucreaId($row['usucrea_id']);
            $UsuarioVerificacion->setUsumodifId($row['usumodif_id']);
            $UsuarioVerificacion->setFechaCrea($row['fecha_crea']);
            $UsuarioVerificacion->setFechaModif($row['fecha_modif']);
            $UsuarioVerificacion->setClasificadorId($row['clasificador_id']);

            return $UsuarioVerificacion;
        }
    
/**
             * Obtiene una lista de registros de la base de datos.
             *
             * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
             * @return array Un array de objetos UsuarioVerificacion.
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
             * Obtiene un único registro de la base de datos.
             *
             * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
             * @return UsuarioVerificacion|null El objeto UsuarioVerificacion o null si no se encuentra.
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
             * Ejecuta una consulta SQL y devuelve el resultado.
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
             * Ejecuta una consulta SQL y devuelve un único resultado.
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

            /**
             * Ejecuta una consulta SQL personalizada.
             *
             * @param string $sql La consulta SQL a ejecutar.
             * @return mixed El resultado de la consulta.
             */
            public function querySQL($sql) {
                $sqlQuery = new SqlQuery($sql);
                return $this->execute2($sqlQuery);
            }

    }
?>