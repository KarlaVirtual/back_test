<?php namespace Backend\mysql;
use Backend\dao\PaisMonedaDAO;
use Backend\dto\PaisMoneda;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'PaisMonedaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'PaisMoneda'
* 
* Ejemplo de uso: 
* $PaisMonedaMySqlDAO = new PaisMonedaMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PaisMonedaMySqlDAO implements PaisMonedaDAO
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
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id)
	{
		$sql = 'SELECT * FROM pais_moneda WHERE paismoneda_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}




    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $paisId pais ID
     * @param String $moneda moneda
     *
     * @return Array resultado de la consulta
     *
     */
    public function loadByPaisAndMoneda($paisId,$moneda)
    {
        $sql = 'SELECT * FROM pais_moneda WHERE pais_id = ? AND moneda = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($paisId);
        $sqlQuery->set($moneda);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $paisId pais ID
     *
     * @return Array resultado de la consulta
     *
     */
    public function loadByPais($paisId)
    {
        $sql = 'SELECT * FROM pais_moneda WHERE pais_id = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($paisId);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll()
	{
		$sql = 'SELECT * FROM pais_moneda';
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
	public function queryAllOrderBy($orderColumn)
	{
		$sql = 'SELECT * FROM pais_moneda ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $paismoneda_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($paismoneda_id)
	{
		$sql = 'DELETE FROM pais_moneda WHERE paismoneda_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($paismoneda_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object paisMoneda paisMoneda
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($paisMoneda)
	{
		$sql = 'INSERT INTO pais_moneda (pais_id, moneda) VALUES (?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($paisMoneda->paisId);
		$sqlQuery->set($paisMoneda->moneda);

		$id = $this->executeInsert($sqlQuery);
		$paisMoneda->paismonedaId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Object paisMoneda paisMoneda
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($paisMoneda)
	{
		$sql = 'UPDATE pais_moneda SET pais_id = ?, moneda = ? WHERE paismoneda_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($paisMoneda->paisId);
		$sqlQuery->set($paisMoneda->moneda);

		$sqlQuery->set($paisMoneda->paismonedaId);
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
	public function clean()
	{
		$sql = 'DELETE FROM pais_moneda';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPaisId($value)
	{
		$sql = 'SELECT * FROM pais_moneda WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna moneda sea igual al valor pasado como parámetro
     *
     * @param String $value moneda requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMoneda($value)
	{
		$sql = 'SELECT * FROM pais_moneda WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPaisId($value)
	{
		$sql = 'DELETE FROM pais_moneda WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna moneda sea igual al valor pasado como parámetro
     *
     * @param String $value moneda requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMoneda($value)
	{
		$sql = 'DELETE FROM pais_moneda WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Crear y devolver un objeto del tipo PaisMoneda
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $PaisMoneda PaisMoneda
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$paisMoneda = new PaisMoneda();

		$paisMoneda->paismonedaId = $row['paismoneda_id'];
		$paisMoneda->paisId = $row['pais_id'];
		$paisMoneda->moneda = $row['moneda'];

		return $paisMoneda;
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
	protected function getList($sqlQuery)
	{
		$tab = QueryExecutor::execute($this->transaction, $sqlQuery);
		$ret = array();
		for ($i = 0; $i < oldCount($tab); $i++) {
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
	protected function getRow($sqlQuery)
	{
		$tab = QueryExecutor::execute($this->transaction, $sqlQuery);
		if (oldCount($tab) == 0) {
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
	protected function execute($sqlQuery)
	{
		return QueryExecutor::execute($this->transaction, $sqlQuery);
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
	protected function executeUpdate($sqlQuery)
	{
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
	protected function querySingleResult($sqlQuery)
	{
		return QueryExecutor::queryForString($this->transaction, $sqlQuery);
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
	protected function executeInsert($sqlQuery)
	{
		return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
	}
}
?>