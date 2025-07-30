<?php
    namespace Backend\mysql;

    use Backend\dto\ModeloFiscal;
    use Backend\sql\QueryExecutor;
    use Backend\sql\SqlQuery;
    use Backend\sql\Transaction;



    /**
     * Clase 'ModeloFiscalMySqlDAO'
     *
     * Esta clase provee las consultas del modelo o tabla 'modelo_fiscal'
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

    class ModeloFiscalMySqlDAO {
        private $transaction;

        /**
         * Obtener una transacción
         *
         * @return Transaction la transacción actual
         */

        public function getTransaction()
        {
            return $this->transaction;
        }



        /**
         * Establecer una transacción
         *
         * @param Transaction $transaction la transacción a establecer
         */
        public function setTransaction($transaction)
        {
            $this->transaction = $transaction;
        }

        /**
         * Constructor de la clase
         *
         * @param Transaction|null $transaction la transacción a utilizar (opcional)
         */

        public function __construct($transaction = null) {
            if (empty($transaction)) {
                $this->transaction = new Transaction();
            } else  {
                $this->transaction = $transaction;
            }
        }


        /**
         * Cargar un registro de la tabla modelo_fiscal por su ID
         *
         * @param int $id ID del registro a cargar
         *
         * @return ModeloFiscal el objeto ModeloFiscal con los datos del registro
         */

        public function load($id) {
            $sql = 'SELECT * FROM modelo_fiscal WHERE modelofiscal_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->getRow($sqlQuery);
        }

        /**
         * Cargar un registro de la tabla modelo_fiscal por tipo, mandante, país, mes, año y estado
         *
         * @param int $tipo tipo del modelo fiscal
         * @param int $mandante mandante del modelo fiscal
         * @param int $pais país del modelo fiscal
         * @param string $mes mes del modelo fiscal
         * @param string $year año del modelo fiscal
         * @param string $estado estado del modelo fiscal
         *
         * @return ModeloFiscal el objeto ModeloFiscal con los datos del registro
         */

        public function loadByTipoMandantePaisEstado($tipo, $mandante, $pais, $mes, $year, $estado) {
            $sql = 'SELECT * FROM modelo_fiscal WHERE tipo = ? AND mandante = ? AND pais_id = ? AND mes = ? AND anio = ? AND estado = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($tipo);
            $sqlQuery->setNumber($mandante);
            $sqlQuery->setNumber($pais);
            $sqlQuery->set($mes);
            $sqlQuery->set($year);
            $sqlQuery->set($estado);
            return $this->getRow($sqlQuery);
        }


        /**
         * Consultar todos los registros de la tabla modelo_fiscal
         *
         * @return array arreglo indexado con los resultados de la consulta
         */
        public function queryAll(){
            $sql = 'SELECT * FROM modelo_fiscal';
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Consultar todos los registros de la tabla modelo_fiscal ordenados por una columna
         *
         * @param string $orderColumn columna por la cual ordenar los resultados
         *
         * @return array arreglo indexado con los resultados de la consulta
         */
        public function queryAllOrderBy($orderColumn){
            $sql = 'SELECT * FROM modelo_fiscal ORDER BY '.$orderColumn;
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Eliminar un registro de la tabla modelo_fiscal por su ID
         *
         * @param int $modelofiscalId ID del registro a eliminar
         *
         * @return int número de filas afectadas
         */
        public function delete($modelofiscalId){
            $sql = 'DELETE FROM modelo_fiscal WHERE modelofiscal_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($modelofiscalId);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Insertar un nuevo registro en la tabla modelo_fiscal
         *
         * @param ModeloFiscal $modeloFiscal objeto ModeloFiscal con los datos a insertar
         *
         * @return int ID del registro insertado
         */
        public function insert($modeloFiscal){
            $sql = 'INSERT INTO modelo_fiscal (tipo, valor, mandante, pais_id, mes, anio, estado, usucrea_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->setNumber($modeloFiscal->getTipo());
            $sqlQuery->set($modeloFiscal->getValor());
            $sqlQuery->setNumber($modeloFiscal->getMandante());
            $sqlQuery->setNumber($modeloFiscal->getPaisId());
            $sqlQuery->set($modeloFiscal->getMes());
            $sqlQuery->set($modeloFiscal->getAnio());
            $sqlQuery->set($modeloFiscal->getEstado());
            $sqlQuery->setNumber($modeloFiscal->getUsucreaId());
    
            $id = $this->executeInsert($sqlQuery);

            return $id;
        }

        /**
         * Actualizar un registro en la tabla modelo_fiscal
         *
         * @param ModeloFiscal $modeloFiscal objeto ModeloFiscal con los datos a actualizar
         *
         * @return int número de filas afectadas
         */

        public function update($modeloFiscal){
            $sql = 'UPDATE modelo_fiscal SET tipo = ?, valor = ?, mandante = ?, pais_id = ?, mes = ?, estado = ?, usucrea_id = ?, usumodif_id = ? WHERE modelofiscal_id = ?';
            $sqlQuery = new SqlQuery($sql);
      
            $sqlQuery->setNumber($modeloFiscal->getTipo());
            $sqlQuery->set($modeloFiscal->getValor());
            $sqlQuery->setNumber($modeloFiscal->getMandante()); 
            $sqlQuery->setNumber($modeloFiscal->getPaisId());
            $sqlQuery->setNumber($modeloFiscal->getMes());
            $sqlQuery->set($modeloFiscal->getEstado());
            $sqlQuery->setNumber($modeloFiscal->getUsucreaId());
            $sqlQuery->setNumber($modeloFiscal->getUsumodifId());

            $sqlQuery->setNumber($modeloFiscal->getModeloFiscalId());
            return $this->executeUpdate($sqlQuery);
        }


        /**
         * Eliminar todos los registros de la tabla modelo_fiscal
         *
         * @return int número de filas afectadas
         */
        public function clean(){
            $sql = 'DELETE FROM modelo_fiscal';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Consultar registros de la tabla modelo_fiscal por tipo, mandante, país y estado
         *
         * @param int $tipo tipo del modelo fiscal
         * @param int $mandante mandante del modelo fiscal
         * @param int $pais país del modelo fiscal
         * @param string $estado estado del modelo fiscal
         *
         * @return array arreglo indexado con los resultados de la consulta
         */
        public function queryByTipoMandantePaisEstado($tipo, $mandante, $pais, $estado) {
            $sql = 'SELECT * FROM modelo_fiscal WHERE mandante = ? AND tipo = ? AND pais_id = ?';
            $sqlQuery = new SqlQuery($sql);
            
            return $this->getList($sqlQuery);
        }

        /**
         * Consultar registros de la tabla modelo_fiscal por tipo, mandante, país y estado
         *
         * @param int $tipo tipo del modelo fiscal
         * @param int $mandante mandante del modelo fiscal
         * @param int $pais_id ID del país del modelo fiscal
         * @param string $estado estado del modelo fiscal
         *
         * @return array arreglo indexado con los resultados de la consulta
         */
 
        public function queryByTipoMandantePais($tipo, $mandante, $pais_id, $estado) {
            $sql = 'SELECT * FROM modelo_fiscal WHERE mandante = ? AND tipo = ? AND pais_id=? AND estado= ?';
            $sqlQuery = new SqlQuery($sql);
            
            return $this->getList($sqlQuery);
        }


        /**
         * Consultar registros de la tabla modelo_fiscal por fecha de creación
         *
         * @param string $fechaCrea fecha de creación del modelo fiscal
         *
         * @return array arreglo indexado con los resultados de la consulta
         */

        public function queryByFechaCrea($fechaCrea){
            $sql = 'SELECT * FROM modelo_fiscal WHERE fecha_crea = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($fechaCrea);
            return $this->getList($sqlQuery);
        }

        /**
         * Consultar registros de la tabla modelo_fiscal por fecha de modificación
         *
         * @param string $fechaModif fecha de modificación del modelo fiscal
         *
         * @return array arreglo indexado con los resultados de la consulta
         */

        public function queryByFechaModif($fechaModif){
            $sql = 'SELECT * FROM modelo_fiscal WHERE fecha_modif = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($fechaModif);
            return $this->getList($sqlQuery);
        }

        /**
         * Consultar registros de la tabla modelo_fiscal por ID del usuario creador
         *
         * @param int $usuCreaId ID del usuario creador
         *
         * @return array arreglo indexado con los resultados de la consulta
         */

        public function queryByUsucreaId($usuCreaId){
            $sql = 'SELECT * FROM modelo_fiscal WHERE usucrea_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuCreaId);
            return $this->getList($sqlQuery);
        }

        /**
         * Consultar registros de la tabla modelo_fiscal por ID del usuario modificador
         *
         * @param int $usuModifId ID del usuario modificador
         *
         * @return array arreglo indexado con los resultados de la consulta
         */
    
        public function queryByUsumodifId($usuModifId){
            $sql = 'SELECT * FROM modelo_fiscal WHERE usumodif_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuModifId);
            return $this->getList($sqlQuery);
        }

        /**
         * Realizar una consulta en la tabla modelo_fiscal de una manera personalizada
         *
         * @param string $select campos de consulta
         * @param string $sidx columna para ordenar
         * @param string $sord orden de los datos asc | desc
         * @param int $start inicio de la consulta
         * @param int $limit límite de la consulta
         * @param string $filters condiciones de la consulta
         * @param boolean $searchOn utilizar los filtros o no
         *
         * @return string JSON con el conteo y los datos de la consulta
         */
    
        public function queryModeloFiscalCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
    
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
    
            $sql = "SELECT count(*) count FROM modelo_fiscal INNER JOIN mandante ON (mandante.mandante=modelo_fiscal.mandante) INNER JOIN clasificador ON (modelo_fiscal.tipo=clasificador.clasificador_id) " . $where;
    
            $sqlQuery = new SqlQuery($sql);
    
            $count = $this->execute2($sqlQuery);
            $sql = "SELECT " . $select . " FROM modelo_fiscal INNER JOIN mandante ON (mandante.mandante=modelo_fiscal.mandante) INNER JOIN clasificador ON (modelo_fiscal.tipo=clasificador.clasificador_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
    
            $sqlQuery = new SqlQuery($sql);

            $result = $this->execute2($sqlQuery);
    
            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
    
            return $json;
        }


        /**
         * Eliminar un registro de la tabla modelo_fiscal por su clave primaria
         *
         * @param mixed $value valor de la clave primaria
         *
         * @return int número de filas afectadas
         */
        public function deleteByPKey($value){
            $sql = 'DELETE FROM modelo_fiscal WHERE tipo = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($value);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Eliminar registros de la tabla modelo_fiscal por fecha de creación
         *
         * @param string $value fecha de creación
         *
         * @return int número de filas afectadas
         */
        public function deleteByFechaCrea($value){
            $sql = 'DELETE FROM modelo_fiscal WHERE fecha_crea = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($value);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Eliminar registros de la tabla modelo_fiscal por fecha de modificación
         *
         * @param string $value fecha de modificación
         *
         * @return int número de filas afectadas
         */
    
        public function deleteByFechaModif($value){
            $sql = 'DELETE FROM modelo_fiscal WHERE fecha_modif = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($value);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Eliminar registros de la tabla modelo_fiscal por ID del usuario creador
         *
         * @param int $value ID del usuario creador
         *
         * @return int número de filas afectadas
         */
        public function deleteByUsucreaId($value){
            $sql = 'DELETE FROM modelo_fiscal WHERE usucrea_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($value);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Eliminar registros de la tabla modelo_fiscal por ID del usuario modificador
         *
         * @param int $value ID del usuario modificador
         *
         * @return int número de filas afectadas
         */
    
        public function deleteByUsumodifId($value){
            $sql = 'DELETE FROM modelo_fiscal WHERE usumodif_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($value);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Crear y devolver un objeto del tipo ModeloFiscal
         * con los valores de una consulta SQL
         *
         * @param array $row arreglo asociativo con los datos de la consulta
         *
         * @return ModeloFiscal objeto ModeloFiscal con los datos de la consulta
         *
         * @access protected
         */
    
        protected function readRow($row){
            $ModeloFiscal = new ModeloFiscal();

            $ModeloFiscal->setModeloFiscalId($row['modelofiscal_id']);
            $ModeloFiscal->setTipo($row['tipo']);
            $ModeloFiscal->setValor($row['valor']);
            $ModeloFiscal->setMandante($row['mandante']);
            $ModeloFiscal->setPaisId($row['pais_id']);
            $ModeloFiscal->setMes($row['mes']);
            $ModeloFiscal->setEstado($row['estado']);
            $ModeloFiscal->setUsucreaId($row['usucrea_id']);
            $ModeloFiscal->setUsumodifIf($row['usumodif_id']);
            $ModeloFiscal->setCiudadId($row['ciudad_id']);
            $ModeloFiscal->setFechaCrea($row['fecha_crea']);
            $ModeloFiscal->setFechaModif($row['fecha_modif']);
    
            return $ModeloFiscal;
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

    
        protected function getList($sqlQuery){
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
        
        protected function getRow($sqlQuery){
            $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
            if(oldCount($tab)==0){
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
        
        protected function execute($sqlQuery){
            return QueryExecutor::execute($this->transaction,$sqlQuery);
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

        protected function execute2($sqlQuery)
        {
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
    
        protected function executeUpdate($sqlQuery){
            return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
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
    
        protected function querySingleResult($sqlQuery){
            return QueryExecutor::queryForString($this->transaction,$sqlQuery);
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
    
        protected function executeInsert($sqlQuery){
            return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
        }
    }
    
?>