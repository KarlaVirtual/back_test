<?php
    namespace Backend\mysql;

    use Backend\dto\FormulariosGenericos;
    use Backend\sql\QueryExecutor;
    use Backend\sql\SqlQuery;
    use Backend\sql\Transaction;

    /** 
     * Clase 'FormulariosGenericosMySqlDAO' que implementa los métodos de la interfaz 'FormulariosGenericosDAO'
     * para el manejo de la tabla 'formularios_genericos' en la base de datos.
     * 
     * @author Desconocido
     * @since: Desconocido
     * @category No
     * @package No
     * @version     1.0
     */
    class FormulariosGenericosMySqlDAO {

        /** Objeto vincula una conexión de la base de datos con el objeto correspondiente
         * @var Transaction $transaction
         */
        private $transaction;

        /**
         * Obtiene la transacción actual.
         *
         * @return Transaction La transacción actual.
         */
        public function getTransaction()
        {
            return $this->transaction;
        }

        /**
         * Establece una nueva transacción.
         *
         * @param Transaction $transaction La nueva transacción.
         */
        public function setTransaction($transaction)
        {
            $this->transaction = $transaction;
        }

        /**
         * Constructor de la clase.
         *
         * @param Transaction|null $transaction La transacción inicial (opcional).
         */
        public function __construct($transaction = null)
        {
            if (empty($transaction)) {
                $this->transaction = new Transaction();
            } else {
                $this->transaction = $transaction;
            }
        }

        /**
         * Carga un formulario genérico por su ID.
         *
         * @param int $id El ID del formulario genérico.
         * @return FormulariosGenericos|null El formulario genérico o null si no se encuentra.
         */
        public function load($id)
        {
            $sql = 'SELECT * FROM formularios_genericos WHERE formgenerico_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un formulario genérico por usuario, mandante, país, año y tipo.
         *
         * @param int $usuarioId El ID del usuario.
         * @param int $mandante El mandante.
         * @param int $pais El ID del país.
         * @param string $anio El año.
         * @param string $tipo El tipo.
         * @return FormulariosGenericos|null El formulario genérico o null si no se encuentra.
         */
        public function loadFormByMandantePaisAnioTipo($usuarioId, $mandante, $pais, $anio, $tipo)
        {
            $sql = 'SELECT * FROM formularios_genericos WHERE usuario_id = ? AND  mandante = ? AND pais_id = ? AND anio = ? AND tipo = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuarioId);
            $sqlQuery->setNumber($mandante);
            $sqlQuery->setNumber($pais);
            $sqlQuery->set($anio);
            $sqlQuery->set($tipo);
            return $this->getRow($sqlQuery);
        }

        /**
         * Consulta todos los formularios genéricos.
         *
         * @return array Lista de formularios genéricos.
         */
        public function queryAll()
        {
            $sql = 'SELECT * FROM formularios_genericos';
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Consulta todos los formularios genéricos ordenados por una columna específica.
         *
         * @param string $orderColumn La columna por la cual ordenar.
         * @return array Lista de formularios genéricos ordenados.
         */
        public function queryAllOrderBy($orderColumn)
        {
            $sql = 'SELECT * FROM formularios_genericos ORDER BY ' . $orderColumn;
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Elimina un formulario genérico por su ID.
         *
         * @param int $formGenricoId El ID del formulario genérico.
         * @return int El número de filas afectadas.
         */
        public function delete($formGenricoId)
        {
            $sql = 'DELETE FROM formularios_genericos WHERE formgenerico_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($formGenricoId);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Inserta un nuevo formulario genérico.
         *
         * @param FormulariosGenericos $formulariosGenericos El formulario genérico a insertar.
         * @return int El ID del formulario genérico insertado.
         */
        public function insert($formulariosGenericos)
        {
            $sql = 'INSERT INTO formularios_genericos (form_data, usuario_id, diligenciado, anio, mandante, pais_id, tipo, usucrea_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($formulariosGenericos->getFormData());
            $sqlQuery->setNumber($formulariosGenericos->getUsuarioId());
            $sqlQuery->set($formulariosGenericos->getDiligenciado());
            $sqlQuery->set($formulariosGenericos->getAnio());
            $sqlQuery->setNumber($formulariosGenericos->getMandante());
            $sqlQuery->setNumber($formulariosGenericos->getPaisId());
            $sqlQuery->set($formulariosGenericos->getTipo());
            $sqlQuery->setNumber($formulariosGenericos->getUsucreaId());

            $id = $this->executeInsert($sqlQuery);

            return $id;
        }

        /**
         * Actualiza un formulario genérico existente.
         *
         * @param FormulariosGenericos $formulariosGenericos El formulario genérico a actualizar.
         * @return int El número de filas afectadas.
         */
        public function update($formulariosGenericos)
        {
            $sql = 'UPDATE formularios_genericos SET form_data = ?, diligenciado = ?, anio = ?, mandante = ?, pais_id = ?, tipo = ?, usumodif_id = ? WHERE formgenerico_id = ?';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($formulariosGenericos->getFormData());
            $sqlQuery->set($formulariosGenericos->getDiligenciado());
            $sqlQuery->set($formulariosGenericos->getAnio());
            $sqlQuery->setNumber($formulariosGenericos->getMandante());
            $sqlQuery->setNumber($formulariosGenericos->getPaisId());
            $sqlQuery->set($formulariosGenericos->getTipo());
            $sqlQuery->setNumber($formulariosGenericos->getUsumodifId());
            $sqlQuery->setNumber($formulariosGenericos->getFormGenericoId());

            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Limpia la tabla de formularios genéricos.
         *
         * @return int El número de filas afectadas.
         */
        public function clean()
        {
            $sql = 'DELETE FROM formularios_genericos';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }
    
        /**
         * Realiza una consulta personalizada en la tabla formularios_genericos con filtros y paginación.
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
        public function queryFormularioGenericoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
    
            $where = " where 1 = 1 ";
    
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
    
            $sql = "SELECT count(*) count FROM formularios_genericos INNER JOIN mandante ON (mandante.mandante=formularios_genericos.mandante)" . $where;
    
            $sqlQuery = new SqlQuery($sql);
    
            $count = $this->execute2($sqlQuery);
            $sql = "SELECT " . $select . " FROM formularios_genericos INNER JOIN mandante ON (mandante.mandante=formularios_genericos.mandante)" . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
    
            $sqlQuery = new SqlQuery($sql);

            $result = $this->execute2($sqlQuery);
    
            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
    
            return $json;
        }

/**
             * Lee una fila de la base de datos y la convierte en un objeto FormulariosGenericos.
             *
             * @param array $row La fila de la base de datos.
             * @return FormulariosGenericos El objeto FormulariosGenericos.
             */
            protected function readRow($row) {
                $FormulariosGenericos = new FormulariosGenericos();

                $FormulariosGenericos->setformGenericoId($row['formgenerico_id']);
                $FormulariosGenericos->setFormData($row['form_data']);
                $FormulariosGenericos->setUsuarioId($row['usuario_id']);
                $FormulariosGenericos->setDiligenciado($row['diligenciado']);
                $FormulariosGenericos->setAnio($row['anio']);
                $FormulariosGenericos->setMandante($row['mandante']);
                $FormulariosGenericos->setPaisId($row['pais_id']);
                $FormulariosGenericos->setTipo($row['tipo']);
                $FormulariosGenericos->setUsucreaId($row['usucrea_id']);
                $FormulariosGenericos->setUsumodifId($row['usumodif_id']);
                $FormulariosGenericos->setFechaCrea($row['fecha_crea']);
                $FormulariosGenericos->setFechaModif($row['fecha_modif']);

                return $FormulariosGenericos;
            }

            /**
             * Obtiene una lista de objetos FormulariosGenericos a partir de una consulta SQL.
             *
             * @param SqlQuery $sqlQuery La consulta SQL.
             * @return array La lista de objetos FormulariosGenericos.
             */
            protected function getList($sqlQuery) {
                $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
                $ret = array();
                for($i=0;$i<oldCount($tab);$i++){
                    $ret[$i] = $this->readRow($tab[$i]);
                }
                return $ret;
            }

            /**
             * Obtiene una fila de la base de datos a partir de una consulta SQL.
             *
             * @param SqlQuery $sqlQuery La consulta SQL.
             * @return FormulariosGenericos|null El objeto FormulariosGenericos o null si no se encuentra.
             */
            protected function getRow($sqlQuery) {
                $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
                if(oldCount($tab)==0){
                    return null;
                }
                return $this->readRow($tab[0]);
            }

            /**
             * Ejecuta una consulta SQL.
             *
             * @param SqlQuery $sqlQuery La consulta SQL.
             * @return mixed El resultado de la consulta.
             */
            protected function execute($sqlQuery) {
                return QueryExecutor::execute($this->transaction,$sqlQuery);
            }

            /**
             * Ejecuta una consulta SQL y devuelve el resultado.
             *
             * @param SqlQuery $sqlQuery La consulta SQL.
             * @return mixed El resultado de la consulta.
             */
            protected function execute2($sqlQuery) {
                return QueryExecutor::execute2($this->transaction, $sqlQuery);
            }

            /**
             * Ejecuta una consulta SQL de actualización.
             *
             * @param SqlQuery $sqlQuery La consulta SQL.
             * @return int El número de filas afectadas.
             */
            protected function executeUpdate($sqlQuery) {
                return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
            }

            /**
             * Realiza una consulta SQL y devuelve un único resultado.
             *
             * @param SqlQuery $sqlQuery La consulta SQL.
             * @return string El resultado de la consulta.
             */
            protected function querySingleResult($sqlQuery) {
                return QueryExecutor::queryForString($this->transaction, $sqlQuery);
            }

            /**
             * Ejecuta una consulta SQL de inserci��n.
             *
             * @param SqlQuery $sqlQuery La consulta SQL.
             * @return int El ID del registro insertado.
             */
            protected function executeInsert($sqlQuery) {
                return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
            }
    }
    
?>