<?php namespace Backend\mysql;
use Backend\dao\UsuariojackpotGanadorDAO;
use Backend\dao\UsuarioOtrainfoDAO;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\dto\UsuarioOtrainfo;
use Exception;
/** 
* Clase 'UsuariojackpotGanadorMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioOtrainfo'
* 
* Ejemplo de uso: 
* $UsuariojackpotGanadorMySqlDAO = new UsuariojackpotGanadorMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuariojackpotGanadorMySqlDAO implements UsuariojackpotGanadorDAO
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
		$sql = 'SELECT * FROM usuariojackpot_ganador WHERE usujackpotganador_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros condicionados por 
     * UsujackpotId, JackpotId, Tipo
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function UsujackpotIdAndJackpotIdAndTipo($usujackpotId, $jackpotId, $tipo) {
		$sql = 'SELECT * FROM usuariojackpot_ganador WHERE usujackpot_id = ? AND jackpot_id = ? AND tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($usujackpotId);
		$sqlQuery->set($jackpotId);
		$sqlQuery->set($tipo);
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
		$sql = 'SELECT * FROM usuariojackpot_ganador';
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
		$sql = 'SELECT * FROM usuariojackpot_ganador ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usujackpotGanadorId llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($usujackpotGanadorId){
		$sql = 'DELETE FROM usuariojackpot_ganador WHERE usujackpotganador_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($usujackpotGanadorId);
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
	public function insert($usujackpotGanador){

		$sql = 'INSERT INTO usuariojackpot_ganador (usujackpot_id,jackpot_id,tipo,usuario_id,valor_premio,estado,usucrea_id,usumodif_id) VALUES (?,?,?,?,?,?,?,?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usujackpotGanador->usujackpotId);
        $sqlQuery->set($usujackpotGanador->jackpotId);
        $sqlQuery->set($usujackpotGanador->tipo);
        $sqlQuery->set($usujackpotGanador->usuarioId);
        $sqlQuery->setNumber($usujackpotGanador->valorPremio);
        $sqlQuery->set($usujackpotGanador->estado);
        //$sqlQuery->set($usujackpotGanador->fechaCrea);
        $sqlQuery->set($usujackpotGanador->usucreaId);
        $sqlQuery->set($usujackpotGanador->usumodifId);
        //$sqlQuery->set($usujackpotGanador->fechaModif);


        $id = $this->executeInsert($sqlQuery);
		$usujackpotGanador->usujackpotganadorId = $id;
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
	public function update($usujackpotGanador){
		$sql = 'UPDATE usuariojackpot_ganador SET usujackpot_id = ?, jackpot_id = ?, tipo = ?, usuario_id = ?, valor_premio = ?, estado = ?, fecha_crea = ?, usucrea_id = ?, usumodif_id = ?, fecha_modif = ? WHERE usujackpotganador_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usujackpotGanador->usujackpotId);
        $sqlQuery->set($usujackpotGanador->jackpotId);
        $sqlQuery->set($usujackpotGanador->tipo);
        $sqlQuery->set($usujackpotGanador->usuarioId);
        $sqlQuery->set($usujackpotGanador->valorPremio);
        $sqlQuery->set($usujackpotGanador->estado);
        $sqlQuery->set($usujackpotGanador->fechaCrea);
        $sqlQuery->set($usujackpotGanador->usucreaId);
        $sqlQuery->set($usujackpotGanador->usumodifId);
        $sqlQuery->set($usujackpotGanador->fechaModif);


        $sqlQuery->set($usujackpotGanador->usujackpotganadorId);
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
		$sql = 'DELETE FROM usuariojackpot_ganador';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}


    /**
     * Crear y devolver un objeto del tipo UsuarioOtrainfo
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usujackpotGanador UsuarioOtrainfo
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usujackpotGanador = new UsuarioOtrainfo();
		
		$usujackpotGanador->usujackpotganadorId = $row['usujackpotganador_id'];
		$usujackpotGanador->usujackpotId = $row['usujackpot_id'];
		$usujackpotGanador->jackpotId = $row['jackpot_id'];
		$usujackpotGanador->tipo = $row['tipo'];
		$usujackpotGanador->usuarioId = $row['usuario_id'];
		$usujackpotGanador->valorPremio = $row['valor_premio'];
		$usujackpotGanador->estado = $row['estado'];
		$usujackpotGanador->fechaCrea = $row['fecha_crea'];
		$usujackpotGanador->usucreaId = $row['usucrea_id'];
		$usujackpotGanador->usumodifId = $row['usumodif_id'];
		$usujackpotGanador->fechaModif = $row['fecha_modif'];

		return $usujackpotGanador;
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
		for($i=0;$i<count($tab);$i++){
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
		if(count($tab)==0){
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