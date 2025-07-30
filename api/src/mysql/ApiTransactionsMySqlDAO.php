<?php namespace Backend\mysql;
use Backend\dao\ApiTransactionsDAO;
use Backend\dto\ApiTransactions;
use Backend\dto\Helpers;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'ApiTransactionsMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'ApiTransactions'
* 
* Ejemplo de uso: 
* $ApiTransactionsMySqlDAO = new ApiTransactionsMySqlDAO();
*	
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ApiTransactionsMySqlDAO implements ApiTransactionsDAO{

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
		$sql = 'SELECT * FROM ApiTransactions WHERE trnID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Obtener todos los registros de la base datos
	 *
	 * @return Array $ resultado de la consulta
     *
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM ApiTransactions';
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
		$sql = 'SELECT * FROM ApiTransactions ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $trnID llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($trnID){
		$sql = 'DELETE FROM ApiTransactions WHERE trnID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($trnID);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto ApiTransactions apiTransaction
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($apiTransaction){
		$sql = 'INSERT INTO ApiTransactions (cliID, mocID, trnMonto, transactionID, trnType, trnDescription, roundID, History, IsRoundFinished, GameID, trnSaldo, trnSaldoEX, trnEstado, trnFecReg, trnFecMod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($apiTransaction->cliID);
		$sqlQuery->setNumber($apiTransaction->mocID);
		$sqlQuery->set($apiTransaction->trnMonto);
		$sqlQuery->set($apiTransaction->transactionID);
		$sqlQuery->set($apiTransaction->trnType);
		$sqlQuery->set($apiTransaction->trnDescription);
		$sqlQuery->set($apiTransaction->roundID);
		$sqlQuery->set($apiTransaction->history);
		$sqlQuery->setNumber($apiTransaction->isRoundFinished);
		$sqlQuery->setNumber($apiTransaction->gameID);
		$sqlQuery->set($apiTransaction->trnSaldo);
		$sqlQuery->set($apiTransaction->trnSaldoEX);
		$sqlQuery->set($apiTransaction->trnEstado);
		$sqlQuery->set($apiTransaction->trnFecReg);
		$sqlQuery->set($apiTransaction->trnFecMod);

		$id = $this->executeInsert($sqlQuery);	
		$apiTransaction->trnID = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto ApiTransactions apiTransaction
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($apiTransaction){
		$sql = 'UPDATE ApiTransactions SET cliID = ?, mocID = ?, trnMonto = ?, transactionID = ?, trnType = ?, trnDescription = ?, roundID = ?, History = ?, IsRoundFinished = ?, GameID = ?, trnSaldo = ?, trnSaldoEX = ?, trnEstado = ?, trnFecReg = ?, trnFecMod = ? WHERE trnID = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($apiTransaction->cliID);
		$sqlQuery->setNumber($apiTransaction->mocID);
		$sqlQuery->set($apiTransaction->trnMonto);
		$sqlQuery->set($apiTransaction->transactionID);
		$sqlQuery->set($apiTransaction->trnType);
		$sqlQuery->set($apiTransaction->trnDescription);
		$sqlQuery->set($apiTransaction->roundID);
		$sqlQuery->set($apiTransaction->history);
		$sqlQuery->setNumber($apiTransaction->isRoundFinished);
		$sqlQuery->setNumber($apiTransaction->gameID);
		$sqlQuery->set($apiTransaction->trnSaldo);
		$sqlQuery->set($apiTransaction->trnSaldoEX);
		$sqlQuery->set($apiTransaction->trnEstado);
		$sqlQuery->set($apiTransaction->trnFecReg);
		$sqlQuery->set($apiTransaction->trnFecMod);

		$sqlQuery->setNumber($apiTransaction->trnID);
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
		$sql = 'DELETE FROM ApiTransactions';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CliID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CliID requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCliID($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE cliID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MocID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MocID requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByMocID($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE mocID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnMonto sea igual al valor pasado como parámetro
 	 *
 	 * @param flot $value TrnMonto requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTrnMonto($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE trnMonto = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TransactionID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransactionID requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTransactionID($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE transactionID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnType sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnType requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTrnType($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE trnType = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnDescription sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnDescription requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTrnDescription($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE trnDescription = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RoundID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RoundID requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByRoundID($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE roundID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna History sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value History requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByHistory($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE History = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna IsRoundFinished sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value isRoundFinished requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByIsRoundFinished($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE IsRoundFinished = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna GameID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value GameID requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByGameID($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE GameID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnSaldo sea igual al valor pasado como parámetro
 	 *
 	 * @param float $value TrnSaldo requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTrnSaldo($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE trnSaldo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnSaldoEX sea igual al valor pasado como parámetro
 	 *
 	 * @param float $value TrnSaldoEX requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTrnSaldoEX($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE trnSaldoEX = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnEstado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnEstado requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTrnEstado($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE trnEstado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnFecReg sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnFecReg requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTrnFecReg($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE trnFecReg = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnFecMod sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnFecMod requerido
 	 *
	 * @return Array $ resultado de la consulta
     *
 	 */
	public function queryByTrnFecMod($value){
		$sql = 'SELECT * FROM ApiTransactions WHERE trnFecMod = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

   	/**
   	* Realizar una consulta en la tabla de transacciones 'ApiTransaction'
   	* de una manera personalizada
    *
   	* @param String $select campos de consulta
   	* @param String $sidx columna para ordenar
   	* @param String $sord orden los datos asc | desc
   	* @param String $start inicio de la consulta
   	* @param String $limit limite de la consulta
   	* @param String $filters condiciones de la consulta 
  	* @param boolean $searchOn utilizar los filtros o no
   	* @param String $grouping columna para agrupar
   	*
	* @return Array $json resultado de la consulta
   	*
 	*/
    public function queryApiTransactionsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {


        $where = " where 1=1 ";

		$Helpers = new Helpers();

        if($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach($rules as $rule)
            {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;
                

                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '".$fieldData."'";
                        break;
                    case "ne":
                        $fieldOperation = " != '".$fieldData."'";
                        break;
                    case "lt":
                        $fieldOperation = " < '".$fieldData."'";
                        break;
                    case "gt":
                        $fieldOperation = " > '".$fieldData."'";
                        break;
                    case "le":
                        $fieldOperation = " <= '".$fieldData."'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '".$fieldData."'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (".$fieldData.")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '".$fieldData."'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '".$fieldData."%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '".$fieldData."%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%".$fieldData."'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%".$fieldData."'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%".$fieldData."%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%".$fieldData."%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
                if (oldCount($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                }
                else
                {
                    $where = "";
                }
            }

        }


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }


        $sql = "SELECT count(*) count FROM ApiTransactions INNER JOIN usuario ON(usuario.usuario_id =ApiTransactions.cliID) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM ApiTransactions INNER JOIN usuario ON(usuario.usuario_id =ApiTransactions.cliID) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CliID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CliID requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
    public function deleteByCliID($value){
		$sql = 'DELETE FROM ApiTransactions WHERE cliID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna MocID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MocID requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByMocID($value){
		$sql = 'DELETE FROM ApiTransactions WHERE mocID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnMonto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnMonto requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTrnMonto($value){
		$sql = 'DELETE FROM ApiTransactions WHERE trnMonto = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TransactionID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransactionID requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTransactionID($value){
		$sql = 'DELETE FROM ApiTransactions WHERE transactionID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnType sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnType requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTrnType($value){
		$sql = 'DELETE FROM ApiTransactions WHERE trnType = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnDescription sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnDescription requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTrnDescription($value){
		$sql = 'DELETE FROM ApiTransactions WHERE trnDescription = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RoundID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RoundID requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByRoundID($value){
		$sql = 'DELETE FROM ApiTransactions WHERE roundID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna History sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value History requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByHistory($value){
		$sql = 'DELETE FROM ApiTransactions WHERE History = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna IsRoundFinished sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IsRoundFinished requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByIsRoundFinished($value){
		$sql = 'DELETE FROM ApiTransactions WHERE IsRoundFinished = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna GameID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value GameID requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByGameID($value){
		$sql = 'DELETE FROM ApiTransactions WHERE GameID = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnSaldo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnSaldo requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTrnSaldo($value){
		$sql = 'DELETE FROM ApiTransactions WHERE trnSaldo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnSaldoEx sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnSaldoEX requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTrnSaldoEX($value){
		$sql = 'DELETE FROM ApiTransactions WHERE trnSaldoEX = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnEstado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnEstado requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTrnEstado($value){
		$sql = 'DELETE FROM ApiTransactions WHERE trnEstado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnFecReg sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnFecReg requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTrnFecReg($value){
		$sql = 'DELETE FROM ApiTransactions WHERE trnFecReg = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnFecMod sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnFecMod requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTrnFecMod($value){
		$sql = 'DELETE FROM ApiTransactions WHERE trnFecMod = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}




	
	/**
 	 * Crear y devolver un objeto del tipo ApiTransaction
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $apiTransaction ApiTransaction
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$apiTransaction = new ApiTransaction();
		
		$apiTransaction->trnID = $row['trnID'];
		$apiTransaction->cliID = $row['cliID'];
		$apiTransaction->mocID = $row['mocID'];
		$apiTransaction->trnMonto = $row['trnMonto'];
		$apiTransaction->transactionID = $row['transactionID'];
		$apiTransaction->trnType = $row['trnType'];
		$apiTransaction->trnDescription = $row['trnDescription'];
		$apiTransaction->roundID = $row['roundID'];
		$apiTransaction->history = $row['History'];
		$apiTransaction->isRoundFinished = $row['IsRoundFinished'];
		$apiTransaction->gameID = $row['GameID'];
		$apiTransaction->trnSaldo = $row['trnSaldo'];
		$apiTransaction->trnSaldoEX = $row['trnSaldoEX'];
		$apiTransaction->trnEstado = $row['trnEstado'];
		$apiTransaction->trnFecReg = $row['trnFecReg'];
		$apiTransaction->trnFecMod = $row['trnFecMod'];

		return $apiTransaction;
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
?>