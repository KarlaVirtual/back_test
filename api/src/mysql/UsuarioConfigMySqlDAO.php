<?php namespace Backend\mysql;
use Backend\dao\UsuarioConfigDAO;
use Backend\dto\UsuarioConfig;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'UsuarioConfigMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioConfig'
* 
* Ejemplo de uso: 
* $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioConfigMySqlDAO implements UsuarioConfigDAO
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
     * llave primaria y el mandante
     *
     * @param String $usuarioconfigId llave primaria
     * @param String $mandante llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function load($usuarioconfigId, $mandante)
	{
		$sql = 'SELECT * FROM usuario_config WHERE usuarioconfig_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usuarioconfigId);
		$sqlQuery->setNumber($mandante);

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
	public function queryAll()
	{
		$sql = 'SELECT * FROM usuario_config';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAllOrderBy($orderColumn)
	{
		$sql = 'SELECT * FROM usuario_config ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}


    /**
     * Eliminar todos los registros condicionados por la 
     * llave primaria y el mandante
     *
     * @param String $usuarioconfigId llave primaria
     * @param String $mandante llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function delete($usuarioconfigId, $mandante)
	{
		$sql = 'DELETE FROM usuario_config WHERE usuarioconfig_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usuarioconfigId);
		$sqlQuery->setNumber($mandante);

		return $this->executeUpdate($sqlQuery);
	}

    

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioConfig usuarioConfig
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioConfig)
	{
		$sql = 'INSERT INTO usuario_config (usuario_id, permite_recarga, pinagent, recibo_caja,  maxpago_retiro, maxpago_premio, usuarioconfig_id, mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($usuarioConfig->usuarioId);
		$sqlQuery->set($usuarioConfig->permiteRecarga);
		$sqlQuery->set($usuarioConfig->pinagent);
        $sqlQuery->set($usuarioConfig->reciboCaja);

        if($usuarioConfig->maxpagoRetiro ==''){
            $usuarioConfig->maxpagoRetiro=0;
        }

        $sqlQuery->set($usuarioConfig->maxpagoRetiro);

        if($usuarioConfig->maxpagoPremio ==''){
            $usuarioConfig->maxpagoPremio=0;
        }

        $sqlQuery->set($usuarioConfig->maxpagoPremio);


		$sqlQuery->setNumber($usuarioConfig->usuarioconfigId);

		$sqlQuery->setNumber($usuarioConfig->mandante);

		$this->executeInsert($sqlQuery);	
		//$usuarioConfig->id = $id;
		//return $id;

	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioConfig usuarioConfig
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioConfig)
	{
		$sql = 'UPDATE usuario_config SET usuario_id = ?, permite_recarga = ?, pinagent = ?, recibo_caja = ?,maxpago_retiro = ?, maxpago_premio=? WHERE usuarioconfig_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($usuarioConfig->usuarioId);
		$sqlQuery->set($usuarioConfig->permiteRecarga);
		$sqlQuery->set($usuarioConfig->pinagent);
		$sqlQuery->set($usuarioConfig->reciboCaja);

        if($usuarioConfig->maxpagoRetiro ==''){
            $usuarioConfig->maxpagoRetiro=0;
        }

        $sqlQuery->set($usuarioConfig->maxpagoRetiro);

        if($usuarioConfig->maxpagoPremio ==''){
            $usuarioConfig->maxpagoPremio=0;
        }

        $sqlQuery->set($usuarioConfig->maxpagoPremio);


        $sqlQuery->setNumber($usuarioConfig->usuarioconfigId);

		$sqlQuery->setNumber($usuarioConfig->mandante);

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
		$sql = 'DELETE FROM usuario_config';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}









    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuarioId($value)
	{
		$sql = 'SELECT * FROM usuario_config WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna permite_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value permite_recarga requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPermiteRecarga($value)
	{
		$sql = 'SELECT * FROM usuario_config WHERE permite_recarga = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pinagent sea igual al valor pasado como parámetro
     *
     * @param String $value pinagent requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPinagent($value)
	{
		$sql = 'SELECT * FROM usuario_config WHERE pinagent = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna recibo_caja sea igual al valor pasado como parámetro
     *
     * @param String $value recibo_caja requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByReciboCaja($value)
	{
		$sql = 'SELECT * FROM usuario_config WHERE recibo_caja = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}







    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsuarioId($value)
	{
		$sql = 'DELETE FROM usuario_config WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna permite_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value permite_recarga requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPermiteRecarga($value)
	{
		$sql = 'DELETE FROM usuario_config WHERE permite_recarga = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pinagent sea igual al valor pasado como parámetro
     *
     * @param String $value pinagent requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPinagent($value)
	{
		$sql = 'DELETE FROM usuario_config WHERE pinagent = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna recibo_caja sea igual al valor pasado como parámetro
     *
     * @param String $value recibo_caja requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByReciboCaja($value)
	{
		$sql = 'DELETE FROM usuario_config WHERE recibo_caja = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Crear y devolver un objeto del tipo UsuarioConfig
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioConfig UsuarioConfig
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$usuarioConfig = new UsuarioConfig();

		$usuarioConfig->usuarioconfigId = $row['usuarioconfig_id'];
		$usuarioConfig->usuarioId = $row['usuario_id'];
		$usuarioConfig->permiteRecarga = $row['permite_recarga'];
		$usuarioConfig->mandante = $row['mandante'];
		$usuarioConfig->pinagent = $row['pinagent'];
		$usuarioConfig->reciboCaja = $row['recibo_caja'];
        $usuarioConfig->maxpagoRetiro = $row['maxpago_retiro'];
        $usuarioConfig->maxpagoPremio = $row['maxpago_premio'];

		return $usuarioConfig;
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