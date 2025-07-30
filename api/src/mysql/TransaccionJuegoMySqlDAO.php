<?php namespace Backend\mysql;
use Backend\dto\ConfigurationEnvironment;
use Backend\dao\TransaccionJuegoDAO;
use Backend\dto\Helpers;
use Backend\dto\TransaccionJuego;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
use Backend\sql\ConnectionProperty;

use PDO;
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
class TransaccionJuegoMySqlDAO implements TransaccionJuegoDAO
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
		$sql = 'SELECT * FROM transaccion_juego WHERE transjuego_id = ?';
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
		$sql = 'SELECT * FROM transaccion_juego';
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
		$sql = 'SELECT * FROM transaccion_juego ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
  /**
   * Eliminar todos los registros condicionados
   * por la llave primaria
   *
   * @param String $transjuego_id llave primaria
   *
   * @return boolean $ resultado de la consulta
   *
   */
	public function delete($transjuego_id){
		$sql = 'DELETE FROM transaccion_juego WHERE transjuego_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($transjuego_id);
		return $this->executeUpdate($sqlQuery);
	}

  /**
   * Insertar un registro en la base de datos
   *
   * @param Objeto transaccionJuego transaccionJuego
   *
   * @return String $id resultado de la consulta
   *
   */
	public function insert($transaccionJuego){
		$sql = 'INSERT INTO transaccion_juego (usuario_id, producto_id, valor_ticket, impuesto, valor_premio, tipo, estado, premiado, ticket_id, transaccion_id, fecha_pago, mandante, clave, usucrea_id, usumodif_id, valor_gratis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';


		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($transaccionJuego->usuarioId);
		$sqlQuery->setNumber($transaccionJuego->productoId);
		$sqlQuery->setNumber($transaccionJuego->valorTicket);

		if($transaccionJuego->impuesto == ""){
			$transaccionJuego->impuesto='0';
		}

		$sqlQuery->setNumber($transaccionJuego->impuesto);
        $sqlQuery->setNumber($transaccionJuego->valorPremio);

        if($transaccionJuego->tipo == ""){
            $transaccionJuego->tipo = "NORMAL";
        }

        $sqlQuery->set($transaccionJuego->tipo);
		$sqlQuery->set($transaccionJuego->estado);
		$sqlQuery->set($transaccionJuego->premiado);
		$sqlQuery->setString($transaccionJuego->ticketId);
		$sqlQuery->setString($transaccionJuego->transaccionId);
		$sqlQuery->set($transaccionJuego->fechaPago);
		$sqlQuery->setNumber($transaccionJuego->mandante);
		$sqlQuery->setString($transaccionJuego->clave);
		$sqlQuery->setNumber($transaccionJuego->usucreaId);
		$sqlQuery->setNumber($transaccionJuego->usumodifId);

		if($transaccionJuego->valorGratis == ''){
            $transaccionJuego->valorGratis=0;
        }
        $sqlQuery->set($transaccionJuego->valorGratis);

        $id = $this->executeInsert($sqlQuery);
		$transaccionJuego->transjuegoId = $id;
		return $id;
	}

  /**
   * Editar un registro en la base de datos
   *
   * @param Objeto transaccionJuego transaccionJuego
   *
   * @return boolean $ resultado de la consulta
   *
   */
	public function update($transaccionJuego){
		$sql = 'UPDATE transaccion_juego SET usuario_id = ?, producto_id = ?, valor_ticket = ?, impuesto = ?, valor_premio = ?, tipo=?, estado = ?, premiado = ?, ticket_id = ?, transaccion_id = ?, fecha_pago = ?, mandante = ?, clave = ?, usucrea_id = ?, usumodif_id = ? , valor_gratis = ? WHERE transjuego_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($transaccionJuego->usuarioId);
		$sqlQuery->setNumber($transaccionJuego->productoId);
		$sqlQuery->setSIN($transaccionJuego->valorTicket);

		if($transaccionJuego->impuesto == ""){
			$transaccionJuego->impuesto='0';
		}

		$sqlQuery->setSIN($transaccionJuego->impuesto);
        $sqlQuery->setSIN($transaccionJuego->valorPremio);
        $sqlQuery->set($transaccionJuego->tipo);
		$sqlQuery->set($transaccionJuego->estado);
		$sqlQuery->set($transaccionJuego->premiado);
		$sqlQuery->setString($transaccionJuego->ticketId);
		$sqlQuery->setString($transaccionJuego->transaccionId);
		$sqlQuery->set($transaccionJuego->fechaPago);
		$sqlQuery->setNumber($transaccionJuego->mandante);
		$sqlQuery->setString($transaccionJuego->clave);
		$sqlQuery->setNumber($transaccionJuego->usucreaId);
		$sqlQuery->setNumber($transaccionJuego->usumodifId);


        if($transaccionJuego->valorGratis == ''){
            $transaccionJuego->valorGratis=0;
        }

        $sqlQuery->set($transaccionJuego->valorGratis);

		$sqlQuery->setNumber($transaccionJuego->transjuegoId);
		return $this->executeUpdate($sqlQuery);
	}

  /**
   * Editar un registro en la base de datos
   *
   * @param Objeto transaccionJuego transaccionJuego
   *
   * @return boolean $ resultado de la consulta
   *
   */
	public function updatePremioPago($transaccionJuego, $where=""){

		$sql = 'UPDATE transaccion_juego SET fecha_pago = ?, premio_pagado = ? WHERE transjuego_id = ? '.$where;
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($transaccionJuego->fechaPago);
        $sqlQuery->set($transaccionJuego->premioPagado);

		$sqlQuery->setNumber($transaccionJuego->transjuegoId);
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
		$sql = 'DELETE FROM transaccion_juego';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

  /**
   * @param string $start Punto inicial de la selección
   * @param string $limit El número máximo de filas a devolver.
   * @param string $period_time Periodo de tiempo para filtrar las transacciones
   * @param string $user_name Nombre del usuario
   * @param string $document_type Tipo de documento del usuario
   * @param string $document_number Número de documento del usuario
   * @param string $user_id ID del usuario
   * @param string $partner Identificador del partner
   * @param string $country Identificador del país
   * @return array
   */
    public function getReportStructureGames($start, $limit, $period_time, $user_name, $document_type, $document_number, $user_id, $partner, $country){

        $period_time = (!empty($period_time)) ? "AND tj.fecha_crea BETWEEN '$period_time 00:00:00' AND '$period_time 23:59:59'" : '';

        $user_name = (!empty($user_name)) ? "AND u.nombre like('%$user_name%')" : '';

        $document_type = (!empty($document_type)) ? "AND r.tipo_documento = '$document_type'" : '';

        $document_number = (!empty($document_number)) ? "AND r.numero_documento = '$document_number'" : '';

        $user_condition = (!empty($user_id)) ? "AND u.usuario_id = $user_id" : '';

        $partner = (!empty($partner) || $partner == '0') ? "AND u.mandante = $partner" : '';

        $country = (!empty($country)) ? "AND u.pais_id = $country" : '';

        $countSql = "SELECT COUNT(*) AS total
            FROM transjuego_log tjl
            INNER JOIN transaccion_juego tj ON tjl.transjuego_id = tj.transjuego_id
            INNER JOIN producto_mandante pm ON tj.producto_id = pm.prodmandante_id
            INNER JOIN producto p ON pm.producto_id = p.producto_id
            INNER JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
            INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
            INNER JOIN (SELECT usuario_id,
                CASE
                    WHEN tipo_doc IN ('0', NULL, '', 'C') THEN 'DNI'
                    WHEN tipo_doc = 'E' THEN 'Carnet de extranjería'
                    WHEN tipo_doc = 'P' THEN 'Pasaporte'
                    ELSE 'Otro' END AS tipo_documento,
                cedula AS numero_documento
            FROM registro
            WHERE usuario_id = $user_id) r ON u.usuario_id = r.usuario_id
            INNER JOIN categoria_mandante cm ON cm.catmandante_id = p.categoria_id
            INNER JOIN mandante m ON u.mandante = m.mandante
            INNER JOIN pais ps ON u.pais_id = ps.pais_id
            WHERE 1 = 1
                $period_time
                $partner
                $country
                $user_condition
                $user_name
                $document_number
                $document_type
                AND cm.mandante = -1";

        $countQuery = new SqlQuery($countSql);
        $totalCount = QueryExecutor::execute($this->transaction, $countQuery);
        $totalCount = !empty($totalCount) ? $totalCount[0]['total'] : 0;

        $sql = "SELECT date(tj.fecha_crea) AS fecha,
            u.usuario_id        AS usuario_id,
            u.nombre            AS usuario_nombre,
            p.descripcion       AS juego,
            cm.descripcion      AS tipo_juego,
            tj.ticket_id        AS apuesta_id,
            tjl.fecha_modif     AS fecha_transaccion,
            tjl.tipo            AS tipo_transaccion,
            tjl.valor           AS monto_transaccion,
            CASE
                WHEN EXISTS (SELECT 1
                    FROM transjuego_log AS sub
                    WHERE sub.transjuego_id = tjl.transjuego_id
                    AND sub.tipo = 'Rollback') THEN 'Devuelta'
                ELSE 'Cerrada'
                END             AS estado
            FROM transjuego_log tjl
            INNER JOIN transaccion_juego tj ON tjl.transjuego_id = tj.transjuego_id
            INNER JOIN producto_mandante pm ON tj.producto_id = pm.prodmandante_id
            INNER JOIN producto p ON pm.producto_id = p.producto_id
            INNER JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
            INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
            INNER JOIN (SELECT usuario_id,
                CASE
                    WHEN tipo_doc IN ('0', NULL, '', 'C') THEN 'DNI'
                    WHEN tipo_doc = 'E' THEN 'Carnet de extranjería'
                    WHEN tipo_doc = 'P' THEN 'Pasaporte'
                    ELSE 'Otro' END AS tipo_documento,
                cedula AS numero_documento
            FROM registro
            WHERE usuario_id = $user_id) r ON u.usuario_id = r.usuario_id
            INNER JOIN categoria_mandante cm ON cm.catmandante_id = p.categoria_id
            INNER JOIN mandante m ON u.mandante = m.mandante
            INNER JOIN pais ps ON u.pais_id = ps.pais_id
            WHERE 1 = 1
                $period_time
                $partner
                $country
                $user_condition
                $user_name
                $document_number
                $document_type
                and cm.mandante = -1
            ORDER BY tj.ticket_id, tjl.fecha_modif DESC
            LIMIT $start, $limit;";


        $sqlQuery = new SqlQuery($sql);
        $result = QueryExecutor::execute($this->transaction,$sqlQuery);
        return [
            'total_count' => $totalCount,
            'data' => $result
        ];
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
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM transaccion_juego WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna producto_id sea igual al valor pasado como parámetro
     *
     * @param String $value producto_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByProductoId($value){
		$sql = 'SELECT * FROM transaccion_juego WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_ticket sea igual al valor pasado como parámetro
     *
     * @param String $value valor_ticket requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByValorTicket($value){
		$sql = 'SELECT * FROM transaccion_juego WHERE valor_ticket = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_premio sea igual al valor pasado como parámetro
     *
     * @param String $value valor_premio requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByValorPremio($value){
		$sql = 'SELECT * FROM transaccion_juego WHERE valor_premio = ?';
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
		$sql = 'SELECT * FROM transaccion_juego WHERE estado = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPremiado($value){
		$sql = 'SELECT * FROM transaccion_juego WHERE premiado = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTicketId($value){
		$sql = 'SELECT * FROM transaccion_juego WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna transaccion_id sea igual al valor pasado como parámetro
     *
     * @param String $value transaccion_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTransaccionId($value){
		$sql = 'SELECT /*+ MAX_EXECUTION_TIME(5000) */ * FROM transaccion_juego WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_pago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_pago requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaPago($value){
		$sql = 'SELECT * FROM transaccion_juego WHERE fecha_pago = ?';
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
		$sql = 'SELECT * FROM transaccion_juego WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clave sea igual al valor pasado como parámetro
     *
     * @param String $value clave requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByClave($value){
		$sql = 'SELECT * FROM transaccion_juego WHERE clave = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
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
    public function checkTicket($ticketId, $clave)
    {
        $sql = "SELECT * FROM transaccion_juego WHERE transjuego_id = ? AND clave = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($ticketId);
        $sqlQuery->set($clave);
        return $this->getRow($sqlQuery);
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
		$sql = 'SELECT * FROM transaccion_juego WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'SELECT * FROM transaccion_juego WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM transaccion_juego WHERE usumodif_id = ?';
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
		$sql = 'SELECT * FROM transaccion_juego WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}













    /**
    * Realizar una consulta en la tabla de TransaccionJuego 'TransaccionJuego'
    * de una manera personalizada
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array $json resultado de la consulta
    *
    */
    public function queryTransacciones($sidx,$sord,$start,$limit,$filters,$searchOn)
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
  						$fieldOperation = " NOT IN (" . $fieldData . ")";
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
		  

   
		  $sql = "SELECT count(*) count FROM transaccion_juego INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id) INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " . $where;
		  

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT transaccion_juego.*,producto.*,producto_mandante.*,proveedor.* FROM transaccion_juego INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id) INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }


    /**
    * Realizar una consulta en la tabla de TransaccionJuego 'TransaccionJuego'
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
    * @param String $having condición para agrupar
    *
    * @return Array $json resultado de la consulta
    *
    */
	public function queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having="",$withCount=true,$forceTimeDimension=false)
    {
		$Helpers = new Helpers();

		if($sidx =='transaccion_juego.fecha_crea'){
			//$sidx ='transaccion_juego.transjuego_id';
		}

        $innproducto_mandante=false;
        $innproducto=false;
        $innsubproveedor=false;
        $innproveedor=false;
        $innusuario_mandante=false;
        $inncategoria=false;

      $where = " where 1=1 ";


  		if($searchOn) {
  			// Construye el where
  			$filters = json_decode($filters);
  			$whereArray = array();
  			$rules = $filters->rules;
  			$groupOperation = $filters->groupOp;
  			$cont = 0;


  			foreach($rules as $rule) {
				$fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;

                $cond="producto_mandante";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_mandante=true;
                }

                $cond="producto";

                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false|| strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_mandante=true;
                    $innproducto=true;
                }

                $cond="subproveedor";

                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false|| strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_mandante=true;
                    $innproducto=true;
                    $innsubproveedor=true;
                }

                $cond="proveedor";

                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false|| strpos($grouping, $cond) !== false || strpos($select, $cond) !== false)
                {
                    $innproducto_mandante=true;
                    $innproducto=true;
                    $innproveedor=true;
                }

                $cond="usuario_mandante";

                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false|| strpos($grouping, $cond) !== false || strpos($select, $cond) !== false)
                {
                    $innusuario_mandante=true;
                }

                $cond="categoria_mandante";

                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false|| strpos($grouping, $cond) !== false || strpos($select, $cond) !== false)
                {
                    $inncategoria=true;
                }
                

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
  						$fieldOperation = " NOT IN (" . $fieldData . ")";
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

        if ($having != null && json_decode($having)->rules != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
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
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        if($having != ""){
            $where = $where . " HAVING " . $having;
        }


		if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
			$connOriginal = $_ENV["connectionGlobal"]->getConnection();




			$connDB5 = null;


			if($_ENV['ENV_TYPE'] =='prod') {

				$connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
					, array(
						PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
						PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
					)
				);
			}else{

				$connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

				);
			}

			$connDB5->exec("set names utf8");
			$connDB5->exec("set use_secondary_engine=off");

			try{

				if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
					$connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
				}

				if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
					$connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
				}
				if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
					// $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
				}
				if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
					// $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
				}
				if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
					$connDB5->exec("SET NAMES utf8mb4");
				}
			}catch (\Exception $e){

			}
			$_ENV["connectionGlobal"]->setConnection($connDB5);
			$this->transaction->setConnection($_ENV["connectionGlobal"]);

		}

        if($having == ""){
            $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */ count(transaccion_juego.transjuego_id) count FROM transaccion_juego  ";


			if($inncategoria){
				$innproducto_mandante=true;
			}
            if($innproducto_mandante){
                $sql .= " "."INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id) ";
            }
            if($innproducto){
				if(!$innproducto_mandante) {
					$sql .= " "."INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id) ";
				}
                $sql .= " "."INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id) ";
            }
            if($innsubproveedor){
                $sql .= " "."INNER JOIN subproveedor ON (subproveedor.subproveedor_id = producto.subproveedor_id) ";
            }
            if($innproveedor){
                $sql .= " "."INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) ";
            }
            if($innusuario_mandante){
                $sql .= " "."INNER JOIN usuario_mandante ON(usuario_mandante.usumandante_id =transaccion_juego.usuario_id)";
            }

			if($inncategoria){
				            if(!$innusuario_mandante) {
								$sql .= " "."INNER JOIN usuario_mandante ON(usuario_mandante.usumandante_id =transaccion_juego.usuario_id)";
							}

			}


            $sql .= " ".$where;

            if ($_ENV["debugFixed2"] == '1') {
                print_r($sql);
            }else{
                if($withCount){
                    $sqlQuery = new SqlQuery($sql);
                    $count = $this->execute2($sqlQuery);

                }
            }



        }else{
            $count=array();
        }
        $oderBy="";


        if($sidx != ""){
            $oderBy=" order by " . $sidx . " " . $sord ." ";
        }



        $forcetime_dimension = "";

        if($forceTimeDimension){
            $forcetime_dimension = "force index (time_dimension_timestampint_dbtimestamp_index)";

        }


		$sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */ ".$select." FROM transaccion_juego 
		INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id) INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id) INNER JOIN subproveedor ON (subproveedor.subproveedor_id = producto.subproveedor_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) INNER JOIN usuario_mandante ON(usuario_mandante.usumandante_id =transaccion_juego.usuario_id)
		 ";

        if($inncategoria){
			$sql .= " "."		  LEFT OUTER JOIN categoria_mandante ON (categoria_mandante.catmandante_id = producto.categoria_id) ";
        }


        $sql=$sql . $where ." " .  $oderBy . " LIMIT " . $start . " , " . $limit;
		$sqlQuery = new SqlQuery($sql);
        if ($_ENV["debugFixed2"] == '1') {
            print_r($sql);
        }else{


			$result = $Helpers->process_data($this->execute2($sqlQuery));


        }
            if ($_ENV["debugFixed2"] == '1') {
				exit();
			}

		if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
			$connDB5 = null;
			$_ENV["connectionGlobal"]->setConnection($connOriginal);
			$this->transaction->setConnection($_ENV["connectionGlobal"]);
		}


		$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }


    /**
     * Realiza una consulta personalizada de transacciones de juego.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros para la paginación.
     * @param string $filters Filtros en formato JSON para la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Campo por el cual se agruparán los resultados.
     * @return string JSON con el conteo de registros y los datos de la consulta.
     */
    public function queryTransaccionesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {
		$Helpers = new Helpers();
		if($sidx =='transaccion_juego.fecha_crea'){
			$sidx ='transaccion_juego.transjuego_id';
		}

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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
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


        $sql = "SELECT count(*) count FROM transaccion_juego  INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id) INNER JOIN producto ON ( producto.producto_id = producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)  " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transaccion_juego INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id) INNER JOIN producto ON ( producto.producto_id = producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        //print_r($sql);
        $sqlQuery = new SqlQuery($sql);

		$result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
     * Consulta transacciones personalizadas con automatización.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros para la paginación.
     * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Campos para agrupar los resultados.
     * @param string $having Cláusula HAVING en formato JSON para filtrar resultados agrupados.
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryTransaccionesCustomAutomation($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having="")
    {

		if($sidx =='transaccion_juego.fecha_crea'){
			$sidx ='transaccion_juego.transjuego_id';
		}

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $where = " WHERE 1=1 ";


        if($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $count = 0;

            $relationship = "";
            $relationship .= " INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id =transaccion_juego.usuario_id)";
            $relationship .= " INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id)";
            $relationship .= " INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id)";
            $relationship .= " INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)";
            $relationshipArray = [];

            foreach($rules as $rule)
            {
                $fieldName = $rule->field;
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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

                $ruleRelation = explode(".", $rule->field)[0];
                if ($ruleRelation != "usuario_mandante") {
                    $result = $ConfigurationEnvironment->getRelationship($ruleRelation, $relationship, $relationshipArray);
                }

                $relationship = $result['relationship'];
                $relationshipArray = $result['relationshipArray'];

            }

        }

        if ($having != "" && empty(json_decode($having)->rules)) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $count = 0;
            $having = ' 1=1 ';

            foreach ($ruleshaving as $rule) {
                $fieldName = $rule->field;
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
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        // if($having != ""){
        //     $where = $where . " HAVING " . $having;
        // }

        if(!empty($having)){
            $where = $where . " HAVING " . $having;
        }


        if(empty($having)){
            // if($having == ""){
            $sql = "SELECT count(*) count FROM transaccion_juego INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id) INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id) INNER JOIN subproveedor ON (subproveedor.subproveedor_id = producto.subproveedor_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) INNER JOIN usuario_mandante ON(usuario_mandante.usumandante_id =transaccion_juego.usuario_id) " . $where;

            $sqlQuery = new SqlQuery($sql);
            $count = $this->execute2($sqlQuery);

        }else{
            $count=array();
        }

        if (!strpos($select, "usuario_mandante")) {
            $relationship = $ConfigurationEnvironment->getRelationshipSelect($select, $relationship, $relationshipArray);
        }

        $sql = "SELECT ".$select." FROM transaccion_juego ". $relationship . $where . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        // print_r($sql);
        // // print_r($relationshipArray);
        // exit();
        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }


    /**
     * Realiza una consulta personalizada de GGR (Gross Gaming Revenue) con múltiples opciones de filtrado, agrupamiento y ordenamiento.
     *
     * @param string $select La cláusula SELECT de la consulta SQL.
     * @param string $sidx El índice de ordenamiento.
     * @param string $sord El orden de clasificación (ASC o DESC).
     * @param int $start El índice de inicio para la paginación.
     * @param int $limit El número máximo de registros a devolver.
     * @param string $filters Los filtros en formato JSON para construir la cláusula WHERE.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping La cláusula GROUP BY para la consulta SQL.
     * @param string $having La cláusula HAVING para la consulta SQL (opcional).
     * @return string Un JSON que contiene el conteo y los datos resultantes de la consulta.
     */
    public function queryGGRCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having="")
    {

		if($sidx =='transaccion_juego.fecha_crea'){
			//$sidx ='transaccion_juego.transjuego_id';
		}

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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }


        $sql=$select."
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre2,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       ".$where."

       UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre2,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
".$where."
) c " .$having;

        $sql = "SELECT ".$select." FROM transaccion_juego INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id) INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) INNER JOIN usuario_mandante ON(usuario_mandante.usumandante_id =transaccion_juego.usuario_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : "", "data" : '.json_encode($result).'}';

        return  $json;
    }












    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas ticketId y usuario_id, sean
     * iguales a los valores pasados como parámetro
     *
     * @param String $ticketId ticketId requerido
     * @param String $usuario_id usuario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function existsTicketId($ticketId,$usuario_id){
        $sql = 'SELECT * FROM transaccion_juego WHERE ticket_id = ? AND usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($ticketId);
        $sqlQuery->setNumber($usuario_id);
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
	public function deleteByUsuarioId($value){
		$sql = 'DELETE FROM transaccion_juego WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna producto_id sea igual al valor pasado como parámetro
     *
     * @param String $value producto_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByProductoId($value){
		$sql = 'DELETE FROM transaccion_juego WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_ticket sea igual al valor pasado como parámetro
     *
     * @param String $value valor_ticket requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByValorTicket($value){
		$sql = 'DELETE FROM transaccion_juego WHERE valor_ticket = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_premio sea igual al valor pasado como parámetro
     *
     * @param String $value valor_premio requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByValorPremio($value){
		$sql = 'DELETE FROM transaccion_juego WHERE valor_premio = ?';
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
		$sql = 'DELETE FROM transaccion_juego WHERE estado = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPremiado($value){
		$sql = 'DELETE FROM transaccion_juego WHERE premiado = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTicketId($value){
		$sql = 'DELETE FROM transaccion_juego WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna transaccion_id sea igual al valor pasado como parámetro
     *
     * @param String $value transaccion_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTransaccionId($value){
		$sql = 'DELETE FROM transaccion_juego WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_pago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_pago requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaPago($value){
		$sql = 'DELETE FROM transaccion_juego WHERE fecha_pago = ?';
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
		$sql = 'DELETE FROM transaccion_juego WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clave sea igual al valor pasado como parámetro
     *
     * @param String $value clave requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByClave($value){
		$sql = 'DELETE FROM transaccion_juego WHERE clave = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'DELETE FROM transaccion_juego WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'DELETE FROM transaccion_juego WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM transaccion_juego WHERE usumodif_id = ?';
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
		$sql = 'DELETE FROM transaccion_juego WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






	
    /**
     * Crear y devolver un objeto del tipo TransaccionJuego
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $transaccionJuego TransaccionJuego
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$transaccionJuego = new TransaccionJuego();
		
		$transaccionJuego->transjuegoId = $row['transjuego_id'];
		$transaccionJuego->usuarioId = $row['usuario_id'];
		$transaccionJuego->productoId = $row['producto_id'];
		$transaccionJuego->valorTicket = $row['valor_ticket'];
		$transaccionJuego->impuesto = $row['impuesto'];
        $transaccionJuego->valorPremio = $row['valor_premio'];
        $transaccionJuego->tipo = $row['tipo'];
		$transaccionJuego->estado = $row['estado'];
		$transaccionJuego->premiado = $row['premiado'];
		$transaccionJuego->ticketId = $row['ticket_id'];
		$transaccionJuego->transaccionId = $row['transaccion_id'];
		$transaccionJuego->fechaPago = $row['fecha_pago'];
		$transaccionJuego->mandante = $row['mandante'];
		$transaccionJuego->clave = $row['clave'];
		$transaccionJuego->usucreaId = $row['usucrea_id'];
		$transaccionJuego->fechaCrea = $row['fecha_crea'];
		$transaccionJuego->usumodifId = $row['usumodif_id'];
		$transaccionJuego->fechaModif = $row['fecha_modif'];
        $transaccionJuego->valorGratis = $row['valor_gratis'];
        $transaccionJuego->premioPagado = $row['premio_pagado'];

		return $transaccionJuego;
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
