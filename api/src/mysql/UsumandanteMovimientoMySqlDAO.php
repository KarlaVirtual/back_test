<?php namespace Backend\mysql;
use Backend\dao\UsumandanteMovimientoDAO;
use Backend\dto\UsumandanteMovimiento;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'UsumandanteMovimientoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsumandanteMovimiento'
* 
* Ejemplo de uso: 
* $UsumandanteMovimientoMySqlDAO = new UsumandanteMovimientoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsumandanteMovimientoMySqlDAO implements UsumandanteMovimientoDAO
{

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
    * Constructor de clase
    *
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
     * Obtener todos los registros condicionados por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE usumandmov_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
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
	public function queryAll(){
		$sql = 'SELECT * FROM usumandante_movimiento';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
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
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM usumandante_movimiento ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usumandmov_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($usumandmov_id){
		$sql = 'DELETE FROM usumandante_movimiento WHERE usumandmov_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usumandmov_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usumandanteMovimiento usumandanteMovimiento
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usumandanteMovimiento){
		$sql = 'INSERT INTO usumandante_movimiento (usumandante_id, producto_tipo, movimiento, request, response, fecha_crea, usucrea_id, fecha_modif, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($usumandanteMovimiento->usumandanteId);
		$sqlQuery->set($usumandanteMovimiento->productoTipo);
		$sqlQuery->set($usumandanteMovimiento->movimiento);
		$sqlQuery->set($usumandanteMovimiento->request);
		$sqlQuery->set($usumandanteMovimiento->response);
		$sqlQuery->set($usumandanteMovimiento->fechaCrea);
		$sqlQuery->setNumber($usumandanteMovimiento->usucreaId);
		$sqlQuery->set($usumandanteMovimiento->fechaModif);
		$sqlQuery->setNumber($usumandanteMovimiento->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$usumandanteMovimiento->usumandmovId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usumandanteMovimiento usumandanteMovimiento
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usumandanteMovimiento){
		$sql = 'UPDATE usumandante_movimiento SET usumandante_id = ?, producto_tipo = ?, movimiento = ?, request = ?, response = ?, fecha_crea = ?, usucrea_id = ?, fecha_modif = ?, usumodif_id = ? WHERE usumandmov_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($usumandanteMovimiento->usumandanteId);
		$sqlQuery->set($usumandanteMovimiento->productoTipo);
		$sqlQuery->set($usumandanteMovimiento->movimiento);
		$sqlQuery->set($usumandanteMovimiento->request);
		$sqlQuery->set($usumandanteMovimiento->response);
		$sqlQuery->set($usumandanteMovimiento->fechaCrea);
		$sqlQuery->setNumber($usumandanteMovimiento->usucreaId);
		$sqlQuery->set($usumandanteMovimiento->fechaModif);
		$sqlQuery->setNumber($usumandanteMovimiento->usumodifId);

		$sqlQuery->setNumber($usumandanteMovimiento->usumandmovId);
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
	public function clean(){
		$sql = 'DELETE FROM usumandante_movimiento';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}












    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumandante_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumandante_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsumandanteId($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE usumandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna producto_tipo sea igual al valor pasado como parámetro
     *
     * @param String $value producto_tipo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByProductoTipo($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE producto_tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna movimiento sea igual al valor pasado como parámetro
     *
     * @param String $value movimiento requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByMovimiento($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE movimiento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna request sea igual al valor pasado como parámetro
     *
     * @param String $value request requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByRequest($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE request = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna response sea igual al valor pasado como parámetro
     *
     * @param String $value response requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByResponse($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE response = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM usumandante_movimiento WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}














    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumandante_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumandante_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsumandanteId($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE usumandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna producto_tipo sea igual al valor pasado como parámetro
     *
     * @param String $value producto_tipo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByProductoTipo($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE producto_tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna movimiento sea igual al valor pasado como parámetro
     *
     * @param String $value movimiento requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMovimiento($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE movimiento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna request sea igual al valor pasado como parámetro
     *
     * @param String $value request requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByRequest($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE request = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna response sea igual al valor pasado como parámetro
     *
     * @param String $value response requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByResponse($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE response = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM usumandante_movimiento WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}















	
    /**
     * Crear y devolver un objeto del tipo UsumandanteMovimiento
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usumandanteMovimiento UsumandanteMovimiento
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usumandanteMovimiento = new UsumandanteMovimiento();
		
		$usumandanteMovimiento->usumandmovId = $row['usumandmov_id'];
		$usumandanteMovimiento->usumandanteId = $row['usumandante_id'];
		$usumandanteMovimiento->productoTipo = $row['producto_tipo'];
		$usumandanteMovimiento->movimiento = $row['movimiento'];
		$usumandanteMovimiento->request = $row['request'];
		$usumandanteMovimiento->response = $row['response'];
		$usumandanteMovimiento->fechaCrea = $row['fecha_crea'];
		$usumandanteMovimiento->usucreaId = $row['usucrea_id'];
		$usumandanteMovimiento->fechaModif = $row['fecha_modif'];
		$usumandanteMovimiento->usumodifId = $row['usumodif_id'];

		return $usumandanteMovimiento;
	}
	
    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo 
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo indexado
     *
     * @access protected
     *
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
		if(oldCount($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);		
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
		return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
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
?>