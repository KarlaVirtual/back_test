<?php namespace Backend\mysql;
use Backend\dao\CuentaCobroEliminadaDAO;
use Backend\dto\CuentaCobroEliminada;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'CuentaCobroEliminadaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'CuentaCobroEliminada'
* 
* Ejemplo de uso: 
* $CuentaCobroEliminadaMySqlDAO = new CuentaCobroEliminadaMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CuentaCobroEliminadaMySqlDAO implements CuentaCobroEliminadaDAO{


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
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada WHERE cuenta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada';
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
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $cuenta_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($cuenta_id){
		$sql = 'DELETE FROM cuenta_cobro_eliminada WHERE cuenta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($cuenta_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object cuentaCobroEliminada cuentaCobroEliminada
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($cuentaCobroEliminada){
		$sql = 'INSERT INTO cuenta_cobro_eliminada (cuenta_id,fecha_cuenta, usuario_id, usucrea_id, fecha_crea, valor, observ, mandante) VALUES (?, ?, ?, ?, ?, ?, ?,?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($cuentaCobroEliminada->cuentaId);
        $sqlQuery->set($cuentaCobroEliminada->fechaCuenta);
		$sqlQuery->set($cuentaCobroEliminada->usuarioId);
		$sqlQuery->set($cuentaCobroEliminada->usucreaId);
		$sqlQuery->set($cuentaCobroEliminada->fechaCrea);
		$sqlQuery->set($cuentaCobroEliminada->valor);
		$sqlQuery->set($cuentaCobroEliminada->observ);
		$sqlQuery->set($cuentaCobroEliminada->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$cuentaCobroEliminada->cuentaId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object cuentaCobroEliminada cuentaCobroEliminada
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($cuentaCobroEliminada){
		$sql = 'UPDATE cuenta_cobro_eliminada SET fecha_cuenta = ?, usuario_id = ?, usucrea_id = ?, fecha_crea = ?, valor = ?, observ = ?, mandante = ? WHERE cuenta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($cuentaCobroEliminada->fechaCuenta);
		$sqlQuery->set($cuentaCobroEliminada->usuarioId);
		$sqlQuery->set($cuentaCobroEliminada->usucreaId);
		$sqlQuery->set($cuentaCobroEliminada->fechaCrea);
		$sqlQuery->set($cuentaCobroEliminada->valor);
		$sqlQuery->set($cuentaCobroEliminada->observ);
		$sqlQuery->set($cuentaCobroEliminada->mandante);

		$sqlQuery->set($cuentaCobroEliminada->cuentaId);
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
		$sql = 'DELETE FROM cuenta_cobro_eliminada';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_cuenta sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cuenta requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaCuenta($value){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada WHERE fecha_cuenta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada WHERE usuario_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada WHERE usucrea_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor sea igual al valor pasado como parámetro
     *
     * @param String $value valor requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValor($value){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna observ sea igual al valor pasado como parámetro
     *
     * @param String $value observ requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByObserv($value){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada WHERE observ = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM cuenta_cobro_eliminada WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}








    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_cuenta sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cuenta requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaCuenta($value){
		$sql = 'DELETE FROM cuenta_cobro_eliminada WHERE fecha_cuenta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsuarioId($value){
		$sql = 'DELETE FROM cuenta_cobro_eliminada WHERE usuario_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM cuenta_cobro_eliminada WHERE usucrea_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM cuenta_cobro_eliminada WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor sea igual al valor pasado como parámetro
     *
     * @param String $value valor requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValor($value){
		$sql = 'DELETE FROM cuenta_cobro_eliminada WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna observobserv sea igual al valor pasado como parámetro
     *
     * @param String $value observ requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByObserv($value){
		$sql = 'DELETE FROM cuenta_cobro_eliminada WHERE observ = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM cuenta_cobro_eliminada WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	
    /**
     * Crear y devolver un objeto del tipo CuentaCobroEliminada
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $CuentaCobroEliminada CuentaCobroEliminada
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$cuentaCobroEliminada = new CuentaCobroEliminada();
		
		$cuentaCobroEliminada->cuentaId = $row['cuenta_id'];
		$cuentaCobroEliminada->fechaCuenta = $row['fecha_cuenta'];
		$cuentaCobroEliminada->usuarioId = $row['usuario_id'];
		$cuentaCobroEliminada->usucreaId = $row['usucrea_id'];
		$cuentaCobroEliminada->fechaCrea = $row['fecha_crea'];
		$cuentaCobroEliminada->valor = $row['valor'];
		$cuentaCobroEliminada->observ = $row['observ'];
		$cuentaCobroEliminada->mandante = $row['mandante'];

		return $cuentaCobroEliminada;
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
     * Ejecutar una consulta sql
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */	protected function executeUpdate($sqlQuery){
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
?>