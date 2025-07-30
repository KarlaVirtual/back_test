<?php 
namespace Backend\mysql;
use Backend\dao\UsuarioOtrainfoDAO;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\dto\UsuarioOtrainfo;
use Exception;
/** 
* Clase 'UsuarioOtrainfoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioOtrainfo'
* 
* Ejemplo de uso: 
* $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioOtrainfoMySqlDAO implements UsuarioOtrainfoDAO
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
		$sql = 'SELECT * FROM usuario_otrainfo WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
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
		$sql = 'SELECT * FROM usuario_otrainfo';
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
		$sql = 'SELECT * FROM usuario_otrainfo ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usuario_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($usuario_id){
		$sql = 'DELETE FROM usuario_otrainfo WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($usuario_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioOtrainfo usuarioOtrainfo
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioOtrainfo){
		$sql = 'INSERT INTO usuario_otrainfo (usuario_id,fecha_nacim, direccion, banco_id, tipo_cuenta, num_cuenta, mandante, anexo_doc, info1, info2, info3, deporte_favorito, casino_favorito,referente_avalado, usuid_referente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioOtrainfo->usuarioId);
        $sqlQuery->set($usuarioOtrainfo->fechaNacim);
		$sqlQuery->set($usuarioOtrainfo->direccion);
		$sqlQuery->set($usuarioOtrainfo->bancoId);
		$sqlQuery->set($usuarioOtrainfo->tipoCuenta);
		$sqlQuery->set($usuarioOtrainfo->numCuenta);
		$sqlQuery->set($usuarioOtrainfo->mandante);
		$sqlQuery->set($usuarioOtrainfo->anexoDoc);

        $sqlQuery->set($usuarioOtrainfo->info1);
        $sqlQuery->set($usuarioOtrainfo->info2);
        $sqlQuery->set($usuarioOtrainfo->info3);

        if($usuarioOtrainfo->deporteFavorito ==""){
            $usuarioOtrainfo->deporteFavorito='0';
        }
        $sqlQuery->set($usuarioOtrainfo->deporteFavorito);

        if($usuarioOtrainfo->casinoFavorito ==""){
            $usuarioOtrainfo->casinoFavorito='0';
        }
        $sqlQuery->set($usuarioOtrainfo->casinoFavorito);


        if(empty($usuarioOtrainfo->referenteAvalado)) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->setNumber($usuarioOtrainfo->referenteAvalado);
        }

        if(empty($usuarioOtrainfo->usuidReferente)) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->setNumber($usuarioOtrainfo->usuidReferente);
        }

        $id = $this->executeInsert($sqlQuery);
		$usuarioOtrainfo->usuarioId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioOtrainfo usuarioOtrainfo
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioOtrainfo){
		$sql = 'UPDATE usuario_otrainfo SET fecha_nacim = ?, direccion = ?, banco_id = ?, tipo_cuenta = ?, num_cuenta = ?, mandante = ?, anexo_doc = ?,info1 = ?, info2 = ?, info3 = ?, deporte_favorito = ?, casino_favorito = ?, referente_avalado = ?, usuid_referente = ? WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioOtrainfo->fechaNacim);
		$sqlQuery->set($usuarioOtrainfo->direccion);
		$sqlQuery->set($usuarioOtrainfo->bancoId);
		$sqlQuery->set($usuarioOtrainfo->tipoCuenta);
		$sqlQuery->set($usuarioOtrainfo->numCuenta);
		$sqlQuery->set($usuarioOtrainfo->mandante);
		$sqlQuery->set($usuarioOtrainfo->anexoDoc);

        $sqlQuery->set($usuarioOtrainfo->info1);
        $sqlQuery->set($usuarioOtrainfo->info2);
        $sqlQuery->set($usuarioOtrainfo->info3);

        if($usuarioOtrainfo->deporteFavorito == ''){
            $usuarioOtrainfo->deporteFavorito='0';
        }
        $sqlQuery->setNumber($usuarioOtrainfo->deporteFavorito);

        if($usuarioOtrainfo->casinoFavorito == ''){
            $usuarioOtrainfo->casinoFavorito='0';
        }
        $sqlQuery->setNumber($usuarioOtrainfo->casinoFavorito);

        if(empty($usuarioOtrainfo->referenteAvalado)) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->setNumber($usuarioOtrainfo->referenteAvalado);
        }

        if(empty($usuarioOtrainfo->usuidReferente)) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->setNumber($usuarioOtrainfo->usuidReferente);
        }


        $sqlQuery->set($usuarioOtrainfo->usuarioId);
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
		$sql = 'DELETE FROM usuario_otrainfo';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}












    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_nacim sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_nacim requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaNacim($value){
		$sql = 'SELECT * FROM usuario_otrainfo WHERE fecha_nacim = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna direccion sea igual al valor pasado como parámetro
     *
     * @param String $value direccion requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByDireccion($value){
		$sql = 'SELECT * FROM usuario_otrainfo WHERE direccion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna banco_id sea igual al valor pasado como parámetro
     *
     * @param String $value banco_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByBancoId($value){
		$sql = 'SELECT * FROM usuario_otrainfo WHERE banco_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo_cuenta sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_cuenta requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTipoCuenta($value){
		$sql = 'SELECT * FROM usuario_otrainfo WHERE tipo_cuenta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna num_cuenta sea igual al valor pasado como parámetro
     *
     * @param String $value num_cuenta requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByNumCuenta($value){
		$sql = 'SELECT * FROM usuario_otrainfo WHERE num_cuenta = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM usuario_otrainfo WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna anexo_doc sea igual al valor pasado como parámetro
     *
     * @param String $value anexo_doc requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByAnexoDoc($value){
		$sql = 'SELECT * FROM usuario_otrainfo WHERE anexo_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}









    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_nacim sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_nacim requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaNacim($value){
		$sql = 'DELETE FROM usuario_otrainfo WHERE fecha_nacim = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna direccion sea igual al valor pasado como parámetro
     *
     * @param String $value direccion requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByDireccion($value){
		$sql = 'DELETE FROM usuario_otrainfo WHERE direccion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna banco_id sea igual al valor pasado como parámetro
     *
     * @param String $value banco_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByBancoId($value){
		$sql = 'DELETE FROM usuario_otrainfo WHERE banco_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo_cuenta sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_cuenta requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTipoCuenta($value){
		$sql = 'DELETE FROM usuario_otrainfo WHERE tipo_cuenta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna num_cuenta sea igual al valor pasado como parámetro
     *
     * @param String $value num_cuenta requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByNumCuenta($value){
		$sql = 'DELETE FROM usuario_otrainfo WHERE num_cuenta = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM usuario_otrainfo WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna anexo_doc sea igual al valor pasado como parámetro
     *
     * @param String $value anexo_doc requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByAnexoDoc($value){
		$sql = 'DELETE FROM usuario_otrainfo WHERE anexo_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}









    /**
     * Crear y devolver un objeto del tipo UsuarioOtrainfo
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioOtrainfo UsuarioOtrainfo
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usuarioOtrainfo = new UsuarioOtrainfo();
		
		$usuarioOtrainfo->usuarioId = $row['usuario_id'];
		$usuarioOtrainfo->fechaNacim = $row['fecha_nacim'];
		$usuarioOtrainfo->direccion = $row['direccion'];
		$usuarioOtrainfo->bancoId = $row['banco_id'];
		$usuarioOtrainfo->tipoCuenta = $row['tipo_cuenta'];
		$usuarioOtrainfo->numCuenta = $row['num_cuenta'];
		$usuarioOtrainfo->mandante = $row['mandante'];
		$usuarioOtrainfo->anexoDoc = $row['anexo_doc'];

        $usuarioOtrainfo->info1 = $row['info1'];
        $usuarioOtrainfo->info2 = $row['info2'];
        $usuarioOtrainfo->info3 = $row['info3'];

        $usuarioOtrainfo->deporteFavorito = $row['deporte_favorito'];
        $usuarioOtrainfo->casinoFavorito = $row['casino_favorito'];
        $usuarioOtrainfo->referenteAvalado = $row['referente_avalado'];
        $usuarioOtrainfo->usuidReferente = $row['usuid_referente'];

		return $usuarioOtrainfo;
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