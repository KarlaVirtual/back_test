<?php
namespace Backend\mysql;


use Backend\dto\CriptoredProdmandante;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/**
 * Clase 'CriptoredProdmandanteMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'cripto_red'
 *
 * Ejemplo de uso:
 * $CriptoredProdmandante = new CriptoredProdmandanteMySqlDAO();
 *
 *
 * @package ninguno
 * @author  Juan David Taborda <juan.taborda@virtualsoft.tech>
 * @version ninguna
 * @access  public
 * @see no
 *
 */

class CriptoredProdmandanteMySqlDAO{
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
    public function getTransaction()
    {
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
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }


    /**
     * Constructor de la clase CriptoRedMySqlDAO.
     *
     * @param Transaction $transaction Objeto de transacción opcional.
     */
    public function __construct($transaction="")
    {
        if ($transaction == "")
        {
            $transaction = new Transaction();
            $this->transaction = $transaction;
        }
        else
        {
            $this->transaction = $transaction;
        }
    }

    /**
     * Cargar una cuenta asociada por ID.
     *
     * @param int $id    ID de la cuenta asociada.
     * @return CriptoredProdmandante Objeto CriptoredProdmandante.
     */

    public function load($id)
    {
        $sql = "SELECT * FROM criptored_prodmandante WHERE criptored_prodmandante_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }


    /**
     * Consultar por id de criptored.
     *
     * @param  int $value ID de la criptomoneda.
     * @return array      Lista de objetos CriptoMoneda.
     */

    public function queryByCriptoredId($value){
        $sql = "SELECT * FROM criptored_prodmandante WHERE criptored_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Consultar por id de prodmandante.
     *
     * @param  int $value ID del prodmandante.
     * @return array      Lista de objetos criptoProdmanante.
     */

    public function queryByProdmandanteId($value)
    {
        $sql = "SELECT * FROM criptored_prodmandante WHERE prodmandante_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);

    }



      /**
     * Consultar por estado de la asociación.
     *
     * @param string $state Estado de la asociación (activo o inactivo).
     * @return array Lista de objetos CriptoredProdmandante.
     */
    public function queryByEstado($state){
        $sql = "SELECT * FROM criptored_prodmandante WHERE estado = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($state);
        return $this->getList($sqlQuery);
    }

    /**
     * Consultar por id de criptored y prodmandante.
     *
     * @param  int $criptoredId ID de la criptored.
     * @param  int $prodmandanteId ID del prodmandante.
     * @return CriptoredProdmandante Objeto CriptoredProdmandante.
     */
    public function queryByCriptoredIdAndProdmandanteId($criptoredId, $prodmandanteId){
        $sql = "SELECT * FROM criptored_prodmandante WHERE criptored_id = ? AND prodmandante_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($criptoredId);
        $sqlQuery->set($prodmandanteId);
        return $this->getRow($sqlQuery);
    }

    /**
     * Consultar por id de criptored y estado.
     *
     * @param  int $criptoredId ID de la criptored.
     * @param  string $estado Estado de la asociación (activo o inactivo).
     * @return array Lista de objetos CriptoredProdmandante.
     */

    public function queryByCriptoRedIdAndEstado($criptoredId, $estado){
        $sql = "SELECT * FROM criptored_prodmandante WHERE criptored_id = ? AND estado = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($criptoredId);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
    }



   /**
     * Consultar todos los registros de la tabla `criptored_prodmandante` ordenados por una columna específica.
     *
     * @param string $orderColumn Nombre de la columna por la cual se ordenarán los resultados.
     * @return array Lista de objetos CriptoredProdmandante ordenados por la columna especificada.
     */

    public function queryAllOrderBy($orderColumn){
        $sql = 'SELECT * FROM criptored_prodmandante ORDER BY '.$orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }


    /**
     * Consultar todos los registros de la tabla `criptored_prodmandante`.
     *
     * @return array Lista de objetos CriptoredProdmandante.
     */

    public function queryAll(){
        $sql = 'SELECT * FROM criptored_prodmandante';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

        /**
        * Elimina un registro de la tabla `criptored_prodmandante` por su identificador único.
        *
        * @param int $id Identificador único del registro a eliminar.
        * @return bool Resultado de la operación de eliminación.
        */

       public function delete($id)
       {
           $sql = "DELETE FROM criptored_prodmandante WHERE criptored_prodmandante_id = ?";
           $sqlQuery = new SqlQuery($sql);
           $sqlQuery->set($id);
           return $this->executeUpdate($sqlQuery);
       }



    /**
     * Inserta un nuevo registro en la tabla `criptored_prodmandante`.
     *
     * @param CriptoredProdmandante $criptoredProdmandante Objeto que contiene los datos a insertar.
     * @return int Identificador único del registro insertado.
     */

    public function insert($criptoredProdmandante)
    {

        $sql = "INSERT INTO criptored_prodmandante (criptored_id, prodmandante_id, estado,usucrea_id,usumodif_id) VALUES (?, ?, ?, ?, ?)";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($criptoredProdmandante->criptoredId);
        $sqlQuery->set($criptoredProdmandante->prodmandanteId);
        $sqlQuery->set($criptoredProdmandante->estado);
        $sqlQuery->set($criptoredProdmandante->usucreaId);
        $sqlQuery->set($criptoredProdmandante->usumodifId);


        $id = $this->executeInsert($sqlQuery); // Ejecutar la inserción
        return $id;
    }


    /**
     * Actualiza un registro en la tabla `criptored_prodmandante`.
     *
     * @param CriptoredProdmandante $criptoredProdmandante Objeto que contiene los datos a actualizar.
     * @return bool Resultado de la operación de actualización.
     */


    public function update($criptoredProdmandante)
    {
        $sql = "UPDATE criptored_prodmandante SET criptored_id = ?, prodmandante_id = ?, estado = ?,usucrea_id = ?, usumodif_id = ? WHERE criptored_prodmandante_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($criptoredProdmandante->criptoredId);
        $sqlQuery->set($criptoredProdmandante->prodmandanteId);
        $sqlQuery->set($criptoredProdmandante->estado);
        $sqlQuery->set($criptoredProdmandante->usucreaId);
        $sqlQuery->set($criptoredProdmandante->usumodifId);
        $sqlQuery->set($criptoredProdmandante->criptoredProdmandanteId);


        return $this->executeUpdate($sqlQuery); // Ejecutar la actualización



    }

    /**
     * realizar una consulta a la tabla criptored_prodmandante de manera personalizada
     *
     *
     * @param String  $select    campos de consulta
     * @param String  $sidx      columna para ordenar
     * @param String  $sord      orden los datos asc | desc
     * @param String  $start     inicio de la consulta
     * @param String  $limit     limite de la consulta
     * @param String  $filters   condiciones de la consulta
     * @param boolean $searchOn  utilizar los filtros o no
     * @param String  $grouping  columna para agrupar
     *
     * @return Array resultado de la consulta
     *
     *
     */


    public function queryCriptoredProdmandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn){
        if($limit < 0){
            $limit=0;
        }

        $where = " where 1=1 ";


        if ($searchOn) {
            // Construye el where
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
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $sql = 'SELECT count(*) count FROM criptored_prodmandante INNER JOIN cripto_red ON ( criptored_prodmandante.criptored_id = cripto_red.criptored_id ) INNER JOIN criptomoneda ON (cripto_red.criptomoneda_id = criptomoneda.criptomoneda_id) INNER JOIN red_blockchain ON (cripto_red.redblockchain_id = red_blockchain.redblockchain_id) INNER JOIN producto_mandante ON (criptored_prodmandante.prodmandante_id = producto_mandante.prodmandante_id) INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN proveedor on (producto.proveedor_id = proveedor.proveedor_id) INNER JOIN pais ON (producto_mandante.pais_id = pais.pais_id) INNER JOIN mandante ON (producto_mandante.mandante = mandante.mandante) ' . $where;
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . ' FROM criptored_prodmandante INNER JOIN cripto_red ON ( criptored_prodmandante.criptored_id = cripto_red.criptored_id ) INNER JOIN criptomoneda ON (cripto_red.criptomoneda_id = criptomoneda.criptomoneda_id) INNER JOIN red_blockchain ON (cripto_red.redblockchain_id = red_blockchain.redblockchain_id) INNER JOIN producto_mandante ON
	(criptored_prodmandante.prodmandante_id = producto_mandante.prodmandante_id) INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN proveedor on (producto.proveedor_id = proveedor.proveedor_id) INNER JOIN pais ON (producto_mandante.pais_id = pais.pais_id) INNER JOIN mandante ON (producto_mandante.mandante = mandante.mandante)' . $where . ' ORDER BY ' . $sidx . ' ' . $sord . ' LIMIT ' . $start . ',' . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
        
    }


    /**
     * Lee una fila de resultados de la base de datos y la convierte en un objeto CriptoRed.
     *
     * @param array $row La fila de resultados de la base de datos.
     * @return CriptoredProdmandante El objeto CriptoredProdmandante con los datos de la fila.
     */



    protected function readRow($row){
        $CriptoredProdmandante = new CriptoredProdmandante();
        $CriptoredProdmandante->criptoredProdmandanteId = $row['criptored_prodmandante_id'];
        $CriptoredProdmandante->criptoredId = $row['criptored_id'];
        $CriptoredProdmandante->prodmandanteId = $row["prodmandante_id"];
        $CriptoredProdmandante->estado = $row["estado"];
        $CriptoredProdmandante->fechaCrea = $row["fecha_crea"];
        $CriptoredProdmandante->usucreaId = $row["usucrea_id"];
        $CriptoredProdmandante->fechaModif = $row["fecha_modif"];
        $CriptoredProdmandante->usumodifId = $row["usumodif_id"];
        return $CriptoredProdmandante;

    }



    /**
     * Ejecutar una consulta sql y devolver el resultado como un arreglo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */


    protected function getRow($sqlQuery){
        $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
        if(count($tab)==0){
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Obtiene una lista de resultados a partir de una consulta SQL.
     *
     * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
     * @return array Un arreglo de objetos resultantes de la consulta.
     */

    protected function getList($sqlQuery){
        $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
        $ret = array();
        for($i=0;$i<count($tab);$i++){
            $ret[$i] = $this->readRow($tab[$i]);
        }
        return $ret;
    }


    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */

    protected function execute($sqlQuery){
        return QueryExecutor::execute($this->transaction,$sqlQuery);
    }

    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como update
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeUpdate($sqlQuery){
        return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como select
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function querySingleResult($sqlQuery){
        return QueryExecutor::queryForString($this->transaction,$sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como insert
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeInsert($sqlQuery){
        return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
    }
    

}