<?php namespace Backend\mysql;
use Backend\dao\ItTicketEncInfo1DAO;
use Backend\dto\ItTicketEncInfo1;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'ItTicketEncInfo1MySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'ItTicketEncInfo1'
* 
* Ejemplo de uso: 
* $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ItTicketEncInfo1MySqlDAO implements ItTicketEncInfo1DAO{


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
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE it_ticket2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
 	 * Obtener todos los registros donde se encuentre que
 	 * las columnas ticket_id y tipo sean iguales a los
 	 * valores pasados como parámetros
 	 *
 	 * @param String $value ticket_id requerido
 	 * @param String $tipo tipo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
      public function queryByTicketIdAndTipo($value, $tipo)
      {
          $sql = 'SELECT * FROM it_ticket_enc_info1 WHERE ticket_id = ? AND tipo = ?';
          $sqlQuery = new SqlQuery($sql);
          $sqlQuery->setNumber($value);
          $sqlQuery->set($tipo);
          return $this->getRow($sqlQuery);
      }

    /**
     * Obtener el registro condicionado por la 
     * llave primaria y la clave encriptada
     *
     * @param String $ticketId llave primaria
     * @param String $clave clave
     *
     * @return Array resultado de la consulta
     *
     */
    public function checkTicket($ticketId,$clave){
        $clave_encrypt = base64_decode($_ENV['APP_PASSKEY']);
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
        $this->execute2($sqlQuery2);
        $sql = "SELECT * FROM it_ticket_enc_info1 WHERE it_ticket2_id = ? and aes_decrypt(clave,'".$clave_encrypt."')='".$clave."'";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($ticketId);
        $return = $this->getRow($sqlQuery);
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
        $this->execute2($sqlQuery2);
        return $return;
    }
	
    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM it_ticket_enc_info1';
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
		$sql = 'SELECT * FROM it_ticket_enc_info1 ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $it_ticket2_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($it_ticket2_id){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE it_ticket2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($it_ticket2_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object itTicketEncInfo1 itTicketEncInfo1
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($itTicketEncInfo1){
		$sql = 'INSERT INTO it_ticket_enc_info1 (tipo, ticket_id, valor,fecha_crea, fecha_modif, valor2) VALUES (?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($itTicketEncInfo1->tipo);
		$sqlQuery->set($itTicketEncInfo1->ticketId);
		$sqlQuery->set($itTicketEncInfo1->valor);
		$sqlQuery->set($itTicketEncInfo1->fechaCrea);
		$sqlQuery->set($itTicketEncInfo1->fechaModif);
		$sqlQuery->set($itTicketEncInfo1->valor2);

		$id = $this->executeInsert($sqlQuery);	
		$itTicketEncInfo1->itTicket2Id = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object itTicketEncInfo1 itTicketEncInfo1
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($itTicketEncInfo1){
		$sql = 'UPDATE it_ticket_enc_info1 SET tipo = ?, ticket_id = ?, valor = ?, fecha_crea = ?, fecha_modif = ? WHERE it_ticket2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($itTicketEncInfo1->tipo);
		$sqlQuery->set($itTicketEncInfo1->ticketId);
		$sqlQuery->set($itTicketEncInfo1->valor);
		$sqlQuery->set($itTicketEncInfo1->fechaCrea);
		$sqlQuery->set($itTicketEncInfo1->fechaModif);

		$sqlQuery->set($itTicketEncInfo1->itTicket2Id);
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
		$sql = 'DELETE FROM it_ticket_enc_info1';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTransaccionId($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTicketId($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE ticket_id = ?';
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
	public function queryByVlrApuesta($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_premio sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_premio requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrPremio($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE vlr_premio = ?';
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
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna game_reference sea igual al valor pasado como parámetro
     *
     * @param String $value game_reference requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByGameReference($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE game_reference = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna bet_status sea igual al valor pasado como parámetro
     *
     * @param String $value bet_status requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByBetStatus($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE bet_status = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cant_lineas sea igual al valor pasado como parámetro
     *
     * @param String $value cant_lineas requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCantLineas($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE cant_lineas = ?';
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
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_crea sea igual al valor pasado como parámetro
     *
     * @param String $value hora_crea requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByHoraCrea($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE hora_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premiado sea igual al valor pasado como parámetro
     *
     * @param String $value premiado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremiado($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE premiado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_pagado sea igual al valor pasado como parámetro
     *
     * @param String $value premio_pagado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioPagado($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE premio_pagado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_pago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_pago requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaPago($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE fecha_pago = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_pago sea igual al valor pasado como parámetro
     *
     * @param String $value hora_pago requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByHoraPago($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE hora_pago = ?';
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
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna eliminado sea igual al valor pasado como parámetro
     *
     * @param String $value eliminado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEliminado($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE eliminado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_maxpago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_maxpago requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaMaxpago($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE fecha_maxpago = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cierre requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaCierre($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE fecha_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value hora_cierre requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByHoraCierre($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE hora_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clave sea igual al valor pasado como parámetro
     *
     * @param String $value clave requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByClave($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE clave = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodifica_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodifica_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodificaId($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE usumodifica_id = ?';
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
	public function queryByFechaModifica($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna beneficiario_id sea igual al valor pasado como parámetro
     *
     * @param String $value beneficiario_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByBeneficiarioId($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE beneficiario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo_beneficiario sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_beneficiario requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipoBeneficiario($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE tipo_beneficiario = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByDirIp($value){
		$sql = 'SELECT * FROM it_ticket_enc_info1 WHERE dir_ip = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}





    /**
    * Realizar una consulta en la tabla de itTicketEncInfo1 'itTicketEncInfo1'
    * de una manera personalizada
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    * @param String $grouping columna para ordenar
    *
    * @return Array resultado de la consulta
    *
    */
	public function queryTicketsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping, $joins = [], $groupingCount = false)
    {


      $where = " where 1=1 ";


  		if($searchOn) {
  			// Construye el where
  			$filters = json_decode($filters);
  			$whereArray = array();
  			$rules = $filters->rules;
  			$groupOperation = $filters->groupOp;
  			$cont = 0;

  			foreach($rules as $rule)
  			{
  				$fieldName = $rule->field;
  				$fieldData = $rule->data;
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
  						$fieldOperation = " NOT IN (".$fieldData.") ";
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
  					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
  				}
  				else
  				{
  					$where = "";
  				}
  			}

		  }

        /** Construyendo cadena de joins solicitados fuera de la petición JOINS DINÁMICOS*/
        $strJoins = " ";
        if (!empty($joins)) {
            foreach ($joins as $join) {
                /**
                 *Ejemplo estructura $join
                 *{
                 *     "type": "INNER" | "LEFT" | "RIGHT",
                 *     "table": "usuario_puntoslealtad"
                 *     "on": "usuario.usuario_id = usuario_puntoslealtad.usuario_id"
                 *}
                 */
                $allowedJoins = ["INNER", "LEFT", "RIGHT"];
                if (in_array($join->type, $allowedJoins)) {
                    //Estructurando cadena de joins
                    $strJoins .= " " . strtoupper($join->type) . " JOIN " . $join->table . " ON (" . $join->on . ") ";
                }
            }
        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        /** Modificando el count para los casos donde se usa un group by en la consulta con el fin de garantizar un correcto funcionamiento
         *IMPORTANTE Estos 2 parámetros deben ir al inicio y final respectivamente de la consulta del count
         */
        $groupByCountPrefix = "";
        $groupByCountSuffix = "";
        if (!empty($grouping) && $groupingCount) {
            $groupByCountPrefix = " SELECT COUNT(*) AS count FROM (";
            $groupByCountSuffix = " ) x";
        }

        $sql = $groupByCountPrefix . "SELECT count(*) count FROM it_ticket_enc_info1   INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = SUBSTRING_INDEX(it_ticket_enc_info1.ticket_id, ',', 1))  " . $strJoins . $where. $groupByCountSuffix;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM it_ticket_enc_info1 INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = SUBSTRING_INDEX(it_ticket_enc_info1.ticket_id, ',', 1)) " . $strJoins . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


		$sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);


		$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTransaccionId($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTicketId($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE ticket_id = ?';
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
	public function deleteByVlrApuesta($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_premio sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_premio requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrPremio($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE vlr_premio = ?';
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
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna game_reference sea igual al valor pasado como parámetro
     *
     * @param String $value game_reference requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByGameReference($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE game_reference = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna bet_status sea igual al valor pasado como parámetro
     *
     * @param String $value bet_status requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByBetStatus($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE bet_status = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna cant_lineas sea igual al valor pasado como parámetro
     *
     * @param String $value cant_lineas requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCantLineas($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE cant_lineas = ?';
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
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_crea sea igual al valor pasado como parámetro
     *
     * @param String $value hora_crea requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByHoraCrea($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE hora_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premiado sea igual al valor pasado como parámetro
     *
     * @param String $value premiado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremiado($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE premiado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_pagado sea igual al valor pasado como parámetro
     *
     * @param String $value premio_pagado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioPagado($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE premio_pagado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_pago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_pago requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaPago($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE fecha_pago = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_pago sea igual al valor pasado como parámetro
     *
     * @param String $value hora_pago requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByHoraPago($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE hora_pago = ?';
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
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna eliminado sea igual al valor pasado como parámetro
     *
     * @param String $value eliminado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEliminado($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE eliminado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_maxpago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_maxpago requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaMaxpago($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE fecha_maxpago = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cierre requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaCierre($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE fecha_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value hora_cierre requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByHoraCierre($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE hora_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clave sea igual al valor pasado como parámetro
     *
     * @param String $value clave requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByClave($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE clave = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodifica_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodifica_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsumodificaId($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE usumodifica_id = ?';
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
	public function deleteByFechaModifica($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna beneficiario_id sea igual al valor pasado como parámetro
     *
     * @param String $value beneficiario_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByBeneficiarioId($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE beneficiario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo_beneficiario sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_beneficiario requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipoBeneficiario($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE tipo_beneficiario = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDirIp($value){
		$sql = 'DELETE FROM it_ticket_enc_info1 WHERE dir_ip = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	


	
    /**
     * Crear y devolver un objeto del tipo ItTicketEncInfo1
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ItTicketEncInfo1 ItTicketEncInfo1
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$itTicketEncInfo1 = new ItTicketEncInfo1();
		
		$itTicketEncInfo1->itTicket2Id = $row['it_ticket2_id'];
		$itTicketEncInfo1->tipo = $row['tipo'];
		$itTicketEncInfo1->ticketId = $row['ticket_id'];
		$itTicketEncInfo1->valor = $row['valor'];
		$itTicketEncInfo1->fechaCrea = $row['fecha_crea'];
		$itTicketEncInfo1->fechaModif = $row['fecha_modif'];

		return $itTicketEncInfo1;
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
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo 
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
     * Ejecutar una consulta sql y devolver el resultado como un arreglo
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $tab arreglo
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