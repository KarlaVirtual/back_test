<?php namespace Backend\mysql;
use Backend\dao\ProdmandantePaisDAO;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/**
* Clase 'ProdmandantePaisMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'ProdmandantePais'
* 
* Ejemplo de uso: 
* $ProdmandantePaisMySqlDAO = new ProdmandantePaisMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProdmandantePaisMySqlDAO implements ProdmandantePaisDAO{

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


    public function load($id){
		$sql = 'SELECT * FROM prodmandante_pais WHERE pmandantepais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM prodmandante_pais';
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
		$sql = 'SELECT * FROM prodmandante_pais ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna lenguaje sea igual al valor pasado como parámetro
     *
     * @param String $value lenguaje requerido
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($pmandantepais_id){
		$sql = 'DELETE FROM prodmandante_pais WHERE pmandantepais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($pmandantepais_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object prodmandantePai prodmandantePai
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($prodmandantePai){
		$sql = 'INSERT INTO prodmandante_pais (producto_id, mandante, pais_id, estado, verifica, fecha_crea, fecha_modif, usucrea_id, usumodif_id, max, min, tiempo_procesamiento,extra_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($prodmandantePai->productoId);
		$sqlQuery->setNumber($prodmandantePai->mandante);
		$sqlQuery->setNumber($prodmandantePai->paisId);
		$sqlQuery->set($prodmandantePai->estado);
		$sqlQuery->set($prodmandantePai->verifica);
		$sqlQuery->set($prodmandantePai->fechaCrea);
		$sqlQuery->set($prodmandantePai->fechaModif);
		$sqlQuery->setNumber($prodmandantePai->usucreaId);
		$sqlQuery->setNumber($prodmandantePai->usumodifId);
		$sqlQuery->set($prodmandantePai->max);
		$sqlQuery->set($prodmandantePai->min);
		$sqlQuery->set($prodmandantePai->tiempoProcesamiento);
		$sqlQuery->set($prodmandantePai->extraInfo);

		$id = $this->executeInsert($sqlQuery);	
		$prodmandantePai->pmandantepaisId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Object prodmandantePai prodmandantePai
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($prodmandantePai){
		$sql = 'UPDATE prodmandante_pais SET producto_id = ?, mandante = ?, pais_id = ?, estado = ?, verifica = ?, fecha_crea = ?, fecha_modif = ?, usucrea_id = ?, usumodif_id = ?, max = ?, min = ?, tiempo_procesamiento = ?, extra_info = ? WHERE pmandantepais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($prodmandantePai->productoId);
		$sqlQuery->setNumber($prodmandantePai->mandante);
		$sqlQuery->setNumber($prodmandantePai->paisId);
		$sqlQuery->set($prodmandantePai->estado);
		$sqlQuery->set($prodmandantePai->verifica);
		$sqlQuery->set($prodmandantePai->fechaCrea);
		$sqlQuery->set($prodmandantePai->fechaModif);
		$sqlQuery->setNumber($prodmandantePai->usucreaId);
		$sqlQuery->setNumber($prodmandantePai->usumodifId);
		$sqlQuery->set($prodmandantePai->max);
		$sqlQuery->set($prodmandantePai->min);
		$sqlQuery->set($prodmandantePai->tiempoProcesamiento);
		$sqlQuery->set($prodmandantePai->extraInfo);

		$sqlQuery->setNumber($prodmandantePai->pmandantepaisId);
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
		$sql = 'DELETE FROM prodmandante_pais';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

        /**
     * Obtener todos los registros donde se encuentre que
     * la columna producto_id sea igual al valor pasado como parámetro
     *
     * @param String $value producto_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByProductoId($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'SELECT * FROM prodmandante_pais WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
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
	public function queryByPaisId($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE pais_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna verifica sea igual al valor pasado como parámetro
     *
     * @param String $value verifica requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVerifica($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE verifica = ?';
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
		$sql = 'SELECT * FROM prodmandante_pais WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM prodmandante_pais WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna max sea igual al valor pasado como parámetro
     *
     * @param String $value max requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMax($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE max = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna min sea igual al valor pasado como parámetro
     *
     * @param String $value min requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMin($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE min = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tiempo_procesamiento sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo_procesamiento requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTiempoProcesamiento($value){
		$sql = 'SELECT * FROM prodmandante_pais WHERE tiempo_procesamiento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}


    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }


    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }
    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna producto_id sea igual al valor pasado como parámetro
     *
     * @param String $value producto_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByProductoId($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'DELETE FROM prodmandante_pais WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
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
	public function deleteByPaisId($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE pais_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna verifica sea igual al valor pasado como parámetro
     *
     * @param String $value verifica requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVerifica($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE verifica = ?';
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
		$sql = 'DELETE FROM prodmandante_pais WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE fecha_modif = ?';
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
		$sql = 'DELETE FROM prodmandante_pais WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna max sea igual al valor pasado como parámetro
     *
     * @param String $value max requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMax($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE max = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna min sea igual al valor pasado como parámetro
     *
     * @param String $value min requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMin($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE min = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tiempo_procesamiento sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo_procesamiento requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTiempoProcesamiento($value){
		$sql = 'DELETE FROM prodmandante_pais WHERE tiempo_procesamiento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}





	
    /**
     * Crear y devolver un objeto del tipo ProdmandantePai
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ProdmandantePai ProdmandantePai
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$prodmandantePai = new ProdmandantePai();
		
		$prodmandantePai->pmandantepaisId = $row['pmandantepais_id'];
		$prodmandantePai->productoId = $row['producto_id'];
		$prodmandantePai->mandante = $row['mandante'];
		$prodmandantePai->paisId = $row['pais_id'];
		$prodmandantePai->estado = $row['estado'];
		$prodmandantePai->verifica = $row['verifica'];
		$prodmandantePai->fechaCrea = $row['fecha_crea'];
		$prodmandantePai->fechaModif = $row['fecha_modif'];
		$prodmandantePai->usucreaId = $row['usucrea_id'];
		$prodmandantePai->usumodifId = $row['usumodif_id'];
		$prodmandantePai->max = $row['max'];
		$prodmandantePai->min = $row['min'];
		$prodmandantePai->tiempoProcesamiento = $row['tiempo_procesamiento'];
		$prodmandantePai->extraInfo = $row['extra_info'];

		return $prodmandantePai;
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
