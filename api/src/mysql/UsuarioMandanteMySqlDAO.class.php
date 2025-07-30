<?php namespace Backend\mysql;
use Backend\dao\UsuarioMandanteDAO;
use Backend\dto\UsuarioMandante;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'UsuarioMandanteMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioMandante'
* 
* Ejemplo de uso: 
* $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioMandanteMySqlDAO implements UsuarioMandanteDAO
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
		$sql = 'SELECT * FROM usuario_mandante WHERE usumandante_id = ?';
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
		$sql = 'SELECT * FROM usuario_mandante';
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
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM usuario_mandante ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usumandante_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($usumandante_id){
		$sql = 'DELETE FROM usuario_mandante WHERE usumandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usumandante_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioMandante usuarioMandante
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioMandante){
		$sql = 'INSERT INTO usuario_mandante (mandante, usuario_mandante, nombres, apellidos, email, saldo, moneda, dir_ip, pais_id, estado, fecha_crea, usucrea_id, fecha_modif, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($usuarioMandante->mandante);
		$sqlQuery->setNumber($usuarioMandante->usuarioMandante);
		$sqlQuery->set($usuarioMandante->nombres);
		$sqlQuery->set($usuarioMandante->apellidos);
		$sqlQuery->set($usuarioMandante->email);
		$sqlQuery->set($usuarioMandante->saldo);
		$sqlQuery->set($usuarioMandante->moneda);
		$sqlQuery->set($usuarioMandante->dirIp);
		$sqlQuery->setNumber($usuarioMandante->paisId);
		$sqlQuery->set($usuarioMandante->estado);
		$sqlQuery->set($usuarioMandante->fechaCrea);
		$sqlQuery->setNumber($usuarioMandante->usucreaId);
		$sqlQuery->set($usuarioMandante->fechaModif);
		$sqlQuery->setNumber($usuarioMandante->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$usuarioMandante->usumandanteId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioMandante usuarioMandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioMandante){
		$sql = 'UPDATE usuario_mandante SET mandante = ?, usuario_mandante = ?, nombres = ?, apellidos = ?, email = ?, saldo = ?, moneda = ?, dir_ip = ?, pais_id = ?, estado = ?, fecha_crea = ?, usucrea_id = ?, fecha_modif = ?, usumodif_id = ? WHERE usumandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($usuarioMandante->mandante);
		$sqlQuery->setNumber($usuarioMandante->usuarioMandante);
		$sqlQuery->set($usuarioMandante->nombres);
		$sqlQuery->set($usuarioMandante->apellidos);
		$sqlQuery->set($usuarioMandante->email);
		$sqlQuery->set($usuarioMandante->saldo);
		$sqlQuery->set($usuarioMandante->moneda);
		$sqlQuery->set($usuarioMandante->dirIp);
		$sqlQuery->setNumber($usuarioMandante->paisId);
		$sqlQuery->set($usuarioMandante->estado);
		$sqlQuery->set($usuarioMandante->fechaCrea);
		$sqlQuery->setNumber($usuarioMandante->usucreaId);
		$sqlQuery->set($usuarioMandante->fechaModif);
		$sqlQuery->setNumber($usuarioMandante->usumodifId);

		$sqlQuery->setNumber($usuarioMandante->usumandanteId);
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
		$sql = 'DELETE FROM usuario_mandante';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}











    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_mandante sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuarioMandante($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE usuario_mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombres sea igual al valor pasado como parámetro
     *
     * @param String $value nombres requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByNombres($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE nombres = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna apellidos sea igual al valor pasado como parámetro
     *
     * @param String $value apellidos requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByApellidos($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE apellidos = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna email sea igual al valor pasado como parámetro
     *
     * @param String $value email requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByEmail($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE email = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo sea igual al valor pasado como parámetro
     *
     * @param String $value saldo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldo($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE saldo = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByMoneda($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna dir_ip sea igual al valor pasado como parámetro
     *
     * @param String $value dir_ip requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByDirIp($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE dir_ip = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPaisId($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM usuario_mandante WHERE estado = ?';
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
		$sql = 'SELECT * FROM usuario_mandante WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM usuario_mandante WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM usuario_mandante WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM usuario_mandante WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}










    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM usuario_mandante WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuario_mandante sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_mandante requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsuarioMandante($value){
		$sql = 'DELETE FROM usuario_mandante WHERE usuario_mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombres sea igual al valor pasado como parámetro
     *
     * @param String $value nombres requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByNombres($value){
		$sql = 'DELETE FROM usuario_mandante WHERE nombres = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna apellidos sea igual al valor pasado como parámetro
     *
     * @param String $value apellidos requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByApellidos($value){
		$sql = 'DELETE FROM usuario_mandante WHERE apellidos = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna email sea igual al valor pasado como parámetro
     *
     * @param String $value email requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByEmail($value){
		$sql = 'DELETE FROM usuario_mandante WHERE email = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo sea igual al valor pasado como parámetro
     *
     * @param String $value saldo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldo($value){
		$sql = 'DELETE FROM usuario_mandante WHERE saldo = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMoneda($value){
		$sql = 'DELETE FROM usuario_mandante WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna dir_ip sea igual al valor pasado como parámetro
     *
     * @param String $value dir_ip requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByDirIp($value){
		$sql = 'DELETE FROM usuario_mandante WHERE dir_ip = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPaisId($value){
		$sql = 'DELETE FROM usuario_mandante WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM usuario_mandante WHERE estado = ?';
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
		$sql = 'DELETE FROM usuario_mandante WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM usuario_mandante WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM usuario_mandante WHERE fecha_modif = ?';
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
		$sql = 'DELETE FROM usuario_mandante WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}


	







    /**
     * Crear y devolver un objeto del tipo UsuarioMandante
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioMandante UsuarioMandante
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usuarioMandante = new UsuarioMandante();
		
		$usuarioMandante->usumandanteId = $row['usumandante_id'];
		$usuarioMandante->mandante = $row['mandante'];
		$usuarioMandante->usuarioMandante = $row['usuario_mandante'];
		$usuarioMandante->nombres = $row['nombres'];
		$usuarioMandante->apellidos = $row['apellidos'];
		$usuarioMandante->email = $row['email'];
		$usuarioMandante->saldo = $row['saldo'];
		$usuarioMandante->moneda = $row['moneda'];
		$usuarioMandante->dirIp = $row['dir_ip'];
		$usuarioMandante->paisId = $row['pais_id'];
		$usuarioMandante->estado = $row['estado'];
		$usuarioMandante->fechaCrea = $row['fecha_crea'];
		$usuarioMandante->usucreaId = $row['usucrea_id'];
		$usuarioMandante->fechaModif = $row['fecha_modif'];
		$usuarioMandante->usumodifId = $row['usumodif_id'];

		return $usuarioMandante;
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