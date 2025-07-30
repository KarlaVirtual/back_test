<?php
    namespace Backend\mysql;

    use Backend\dto\ModeloFiscal2;
    use Backend\sql\QueryExecutor;
    use Backend\sql\SqlQuery;
    use Backend\sql\Transaction;

    /**
     * Clase 'ModeloFiscal2MySqlDAO'
     *
     * Esta clase provee las consultas del modelo o tabla 'modelo_fiscal2'
     *
     * Ejemplo de uso:
     * ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO();
     *
     *
     * @package ninguno
     * @author Daniel Tamayo <it@virtualsoft.tech>
     * @version ninguna
     * @access public
     * @see no
     *
     */




    class ModeloFiscal2MySqlDAO {

        /**
         * Atributo Transaction transacción
         *
         * @var object
         */
        private $transaction;


        /**
         * Obtener la transacción de un objeto
         *
         * @return Objeto Transaction transacción
         *
         */
        public function getTransaction() {
            return $this->transaction;
        }
        /**
         * Modificar el atributo transacción del objeto
         *
         * @param Objeto $Transaction transacción
         *
         * @return no
         *
         */

        public function setTransaction($transaction) {
            $this->transaction = $transaction;
        }


        /**
         * Constructor de clase
         *
         * @param Objeto $transaction transaccion
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */

        public function __construct($transaction = null) {
            if (empty($transaction)) {
                $this->transaction = new Transaction();
            } else  {
                $this->transaction = $transaction;
            }
        }

        /**
         * Obtener el registro condicionado por la
         * llave primaria que se pasa como parámetro
         *
         * @param String $id llave primaria
         *
         * @return Array resultado de la consulta
         *
         */
        public function load($id) {
            $sql = 'SELECT * FROM modelo_fiscal2 WHERE modelofiscal_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->getRow($sqlQuery);
        }

        /**
         * Obtener el registro condicionado por los parámetros
         * reporte, mandante, pais, mes, anio y tipo
         *
         * @param String $reporte reporte
         * @param int $mandante mandante
         * @param int $pais pais
         * @param int $mes mes
         * @param String $anio anio
         * @param String $tipo tipo
         *
         * @return Array resultado de la consulta
         *
         */

        public function loadByReporteMandantePaisConMesAnioTipo($reporte, $mandante, $pais, $mes, $anio, $tipo) {
            $sql = 'SELECT * FROM modelo_fiscal2 WHERE reporte = ? AND mandante = ? AND pais_id = ? AND mes = ? AND anio = ? AND tipo = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($reporte);
            $sqlQuery->setNumber($mandante);
            $sqlQuery->setNumber($pais);
            $sqlQuery->setNumber($mes);
            $sqlQuery->set($anio);
            $sqlQuery->set($tipo);
            return $this->getRow($sqlQuery);
        }


        /**
         * Obtener todos los registros de la base datos
         *
         * @return Array resultado de la consulta
         *
         */
        public function queryAll(){
            $sql = 'SELECT * FROM modelo_fiscal2';
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
         * @return Array resultado de la consulta
         *
         */

        public function queryAllOrderBy($orderColumn) {
            $sql = 'SELECT * FROM modelo_fiscal2 ORDER BY '.$orderColumn;
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Eliminar todos los registros condicionados
         * por la llave primaria
         *
         * @param String $modelofiscal_id llave primaria
         *
         * @return boolean $ resultado de la consulta
         *
         */
        public function delete($modelofiscalId) {
            $sql = 'DELETE FROM modelo_fiscal2 WHERE modelofiscal_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($modelofiscalId);
            return $this->executeUpdate($sqlQuery);
        }


        /**
         * Insertar un registro en la base de datos
         *
         * @param Object modeloFiscal2 modeloFiscal2
         *
         * @return String $id resultado de la consulta
         *
         */

        public function insert($modeloFiscal2) {
            $sql = 'INSERT INTO modelo_fiscal2 (reporte, mandante, pais_id, mes, anio, columnas, tipo, usucrea_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $sqlQuery = new SqlQuery($sql);
            
            $sqlQuery->set($modeloFiscal2->getReporte());
            $sqlQuery->setNumber($modeloFiscal2->getMandante());
            $sqlQuery->setNumber($modeloFiscal2->getPaisId());
            $sqlQuery->set($modeloFiscal2->getMes());
            $sqlQuery->set($modeloFiscal2->getAnio());
            $sqlQuery->set($modeloFiscal2->getColumnas());
            $sqlQuery->set($modeloFiscal2->getTipo());
            $sqlQuery->setNumber($modeloFiscal2->getUsucreaId());
    
            $id = $this->executeInsert($sqlQuery);

            return $id;
        }

        /**
         * Editar un registro en la base de datos
         *
         * @param Object modeloFiscal2 modeloFiscal2
         *
         * @return boolean $ resultado de la consulta
         *
         */

        public function update($modeloFiscal2) {
            $sql = 'UPDATE modelo_fiscal2 SET reporte = ?, mandante = ?, pais_id = ?, anio = ?, columnas = ?, usucrea_id = ?, usumodif_id = ?  WHERE modelofiscal_id = ?';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($modeloFiscal2->getReporte());
            $sqlQuery->setNumber($modeloFiscal2->getMandante()); 
            $sqlQuery->setNumber($modeloFiscal2->getPaisId());
            $sqlQuery->set($modeloFiscal2->getAnio());
            $sqlQuery->set($modeloFiscal2->getColumnas());
            $sqlQuery->setNumber($modeloFiscal2->getUsucreaId());
            $sqlQuery->setNumber($modeloFiscal2->getUsumodifId());
            $sqlQuery->setNumber($modeloFiscal2->getModeloFiscalId());

            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Eliminar todos los registros de la tabla 'modelo_fiscal2'
         *
         * @return boolean resultado de la ejecución
         */
        public function clean() {
            $sql = 'DELETE FROM modelo_fiscal2';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Realizar una consulta en la tabla de ModeloFiscal2 'modelo_fiscal2'
         * de una manera personalizada
         *
         * @param String $select campos de consulta
         * @param String $sidx columna para ordenar
         * @param String $sord orden de los datos asc | desc
         * @param int $start inicio de la consulta
         * @param int $limit límite de la consulta
         * @param String $filters condiciones de la consulta
         * @param boolean $searchOn utilizar los filtros o no
         *
         * @return String JSON con el conteo y los datos de la consulta
         */

        public function queryModeloFiscal2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
    
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
    
            $sql = "SELECT count(*) count FROM modelo_fiscal2 INNER JOIN mandante ON (mandante.mandante=modelo_fiscal2.mandante)" . $where;
    
            $sqlQuery = new SqlQuery($sql);
    
            $count = $this->execute2($sqlQuery);
            $sql = "SELECT " . $select . " FROM modelo_fiscal2 INNER JOIN mandante ON (mandante.mandante=modelo_fiscal2.mandante)" . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
    
            $sqlQuery = new SqlQuery($sql);

            $result = $this->execute2($sqlQuery);
    
            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
    
            return $json;
        }

        /**
         * Crear y devolver un objeto del tipo ModeloFiscal2
         * con los valores de una consulta SQL
         *
         * @param array $row arreglo asociativo con los datos de la consulta
         *
         * @return ModeloFiscal2 objeto ModeloFiscal2 con los datos de la consulta
         *
         * @access protected
         */


        protected function readRow($row) {
            $ModeloFiscal2 = new ModeloFiscal2();

            $ModeloFiscal2->setModeloFiscalId($row['modelofiscal_id']);
            $ModeloFiscal2->setReporte($row['reporte']);
            $ModeloFiscal2->setMandante($row['mandante']);
            $ModeloFiscal2->setPaisId($row['pais_id']);
            $ModeloFiscal2->setMes($row['mes']);
            $ModeloFiscal2->setAnio($row['anio']);
            $ModeloFiscal2->setColumnas($row['columnas']);
            $ModeloFiscal2->setTipo($row['tipo']);
            $ModeloFiscal2->setUsucreaId($row['usucrea_id']);
            $ModeloFiscal2->setUsumodifId($row['usumodif_id']);
    
            return $ModeloFiscal2;
        }

        /**
         * Ejecutar una consulta SQL y devolver los datos
         * como un arreglo asociativo
         *
         * @param SqlQuery $sqlQuery consulta SQL
         *
         * @return array arreglo indexado con los resultados de la consulta
         *
         * @access protected
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
         * Ejecutar una consulta SQL y devolver el resultado como un arreglo
         *
         * @param SqlQuery $sqlQuery consulta SQL
         *
         * @return array resultado de la ejecución
         *
         * @access protected
         */
        protected function getRow($sqlQuery) {
            $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
            if (oldCount($tab) == 0) {
                return null;
            }
            return $this->readRow($tab[0]);
        }

        /**
         * Ejecutar una consulta SQL
         *
         * @param SqlQuery $sqlQuery consulta SQL
         *
         * @return array resultado de la ejecución
         *
         * @access protected
         */
        protected function execute($sqlQuery) {
            return QueryExecutor::execute($this->transaction, $sqlQuery);
        }

        /**
         * Ejecutar una consulta SQL
         *
         * @param SqlQuery $sqlQuery consulta SQL
         *
         * @return array resultado de la ejecución
         *
         * @access protected
         */
        protected function execute2($sqlQuery) {
            return QueryExecutor::execute2($this->transaction, $sqlQuery);
        }

        /**
         * Ejecutar una consulta SQL como update
         *
         * @param SqlQuery $sqlQuery consulta SQL
         *
         * @return array resultado de la ejecución
         *
         * @access protected
         */
        protected function executeUpdate($sqlQuery) {
            return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
        }

        /**
         * Ejecutar una consulta SQL y devolver el resultado como una cadena
         *
         * @param SqlQuery $sqlQuery consulta SQL
         *
         * @return string resultado de la ejecución
         *
         * @access protected
         */
    
        protected function querySingleResult($sqlQuery) {
            return QueryExecutor::queryForString($this->transaction, $sqlQuery);
        }


        /**
         * Ejecutar una consulta SQL como insert
         *
         * @param SqlQuery $sqlQuery consulta SQL
         *
         * @return int ID del registro insertado
         *
         * @access protected
         */
    
        protected function executeInsert($sqlQuery) {
            return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
        }
    }
    
?>