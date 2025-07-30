<?php namespace Backend\mysql;
use Backend\dao\FlujoCajaDAO;
use Backend\dto\FlujoCaja;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'FlujoCajaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'FlujoCaja'
* 
* Ejemplo de uso: 
* $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class FlujoCajaMySqlDAO implements FlujoCajaDAO{

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
     * @param String $flujocaja_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM flujo_caja WHERE flujocaja_id = ?';
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
		$sql = 'SELECT * FROM flujo_caja';
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
		$sql = 'SELECT * FROM flujo_caja ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $flujocaja_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($flujocaja_id){
		$sql = 'DELETE FROM flujo_caja WHERE flujocaja_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($flujocaja_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object flujoCaja flujoCaja
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($flujoCaja){
		$sql = 'INSERT INTO flujo_caja (fecha_crea, hora_crea, usucrea_id, tipomov_id, valor, ticket_id, traslado, doctraslado_id, recarga_id, formapago1_id, formapago2_id, valor_forma1, valor_forma2, devolucion, cuenta_id, mandante, porcen_iva, valor_iva,cupolog_id,fecha_crea_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)';
		$sqlQuery = new SqlQuery($sql);

		// Guardar el valor del huso horario actual
		$oldTimeZone = date_default_timezone_get();

		// Establecer el nuevo huso horario
		date_default_timezone_set('America/Bogota');



		$flujoCaja->setFechaCrea(date('Y-m-d'));
		$flujoCaja->setHoraCrea(date('H:i'));

		// Restablecer el huso horario al valor anterior
		date_default_timezone_set($oldTimeZone);


		$sqlQuery->set($flujoCaja->fechaCrea);
		$sqlQuery->set($flujoCaja->horaCrea);
		$sqlQuery->set($flujoCaja->usucreaId);
		$sqlQuery->set($flujoCaja->tipomovId);
		$sqlQuery->set($flujoCaja->valor);
		$sqlQuery->set($flujoCaja->ticketId);
		$sqlQuery->set($flujoCaja->traslado);
		$sqlQuery->set($flujoCaja->doctrasladoId);
		if($flujoCaja->recargaId=='' || $flujoCaja->recargaId=='0'){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($flujoCaja->recargaId);

		}
		$sqlQuery->set($flujoCaja->formapago1Id);
		$sqlQuery->set($flujoCaja->formapago2Id);
		$sqlQuery->set($flujoCaja->valorForma1);
		$sqlQuery->set($flujoCaja->valorForma2);
		$sqlQuery->set($flujoCaja->devolucion);
		$sqlQuery->set($flujoCaja->cuentaId);
		$sqlQuery->set($flujoCaja->mandante);
		$sqlQuery->set($flujoCaja->porcenIva);
		$sqlQuery->set($flujoCaja->valorIva);
		if($flujoCaja->cupologId==""){
            $flujoCaja->cupologId ='0';
        }
		$sqlQuery->set($flujoCaja->cupologId);

        $sqlQuery->set($flujoCaja->fechaCrea . ' '.$flujoCaja->horaCrea);

		$id = $this->executeInsert($sqlQuery);	
		$flujoCaja->flujocajaId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object flujoCaja flujoCaja
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($flujoCaja){
		$sql = 'UPDATE flujo_caja SET fecha_crea = ?, hora_crea = ?, usucrea_id = ?, tipomov_id = ?, valor = ?, ticket_id = ?, traslado = ?, doctraslado_id = ?, recarga_id = ?, formapago1_id = ?, formapago2_id = ?, valor_forma1 = ?, valor_forma2 = ?, devolucion = ?, cuenta_id = ?, mandante = ?, porcen_iva = ?, valor_iva = ? , cupolog_id = ?, fecha_crea_time = ? WHERE flujocaja_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($flujoCaja->fechaCrea);
		$sqlQuery->set($flujoCaja->horaCrea);
		$sqlQuery->set($flujoCaja->usucreaId);
		$sqlQuery->set($flujoCaja->tipomovId);
		$sqlQuery->set($flujoCaja->valor);
		$sqlQuery->set($flujoCaja->ticketId);
		$sqlQuery->set($flujoCaja->traslado);
		$sqlQuery->set($flujoCaja->doctrasladoId);
		$sqlQuery->set($flujoCaja->recargaId);
		$sqlQuery->set($flujoCaja->formapago1Id);
		$sqlQuery->set($flujoCaja->formapago2Id);
		$sqlQuery->set($flujoCaja->valorForma1);
		$sqlQuery->set($flujoCaja->valorForma2);
		$sqlQuery->set($flujoCaja->devolucion);
		$sqlQuery->set($flujoCaja->cuentaId);
		$sqlQuery->set($flujoCaja->mandante);
		$sqlQuery->set($flujoCaja->porcenIva);
		$sqlQuery->set($flujoCaja->valorIva);
        $sqlQuery->set($flujoCaja->cupologId);

        $sqlQuery->set($flujoCaja->fechaCrea . ' '.$flujoCaja->horaCrea);

		$sqlQuery->set($flujoCaja->flujocajaId);
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
		$sql = 'DELETE FROM flujo_caja';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'SELECT * FROM flujo_caja WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM flujo_caja WHERE hora_crea = ?';
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
		$sql = 'SELECT * FROM flujo_caja WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipomov_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipomov_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipomovId($value){
		$sql = 'SELECT * FROM flujo_caja WHERE tipomov_id = ?';
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
		$sql = 'SELECT * FROM flujo_caja WHERE valor = ?';
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
		$sql = 'SELECT * FROM flujo_caja WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna traslado sea igual al valor pasado como parámetro
     *
     * @param String $value traslado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTraslado($value){
		$sql = 'SELECT * FROM flujo_caja WHERE traslado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna doctraslado_id sea igual al valor pasado como parámetro
     *
     * @param String $value doctraslado_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDoctrasladoId($value){
		$sql = 'SELECT * FROM flujo_caja WHERE doctraslado_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna recarga_id sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRecargaId($value){
		$sql = 'SELECT * FROM flujo_caja WHERE recarga_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna formapago1_id sea igual al valor pasado como parámetro
     *
     * @param String $value formapago1_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFormapago1Id($value){
		$sql = 'SELECT * FROM flujo_caja WHERE formapago1_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna formapago2_id sea igual al valor pasado como parámetro
     *
     * @param String $value formapago2_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFormapago2Id($value){
		$sql = 'SELECT * FROM flujo_caja WHERE formapago2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_forma1 sea igual al valor pasado como parámetro
     *
     * @param String $value valor_forma1 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorForma1($value){
		$sql = 'SELECT * FROM flujo_caja WHERE valor_forma1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_forma2 sea igual al valor pasado como parámetro
     *
     * @param String $value valor_forma2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorForma2($value){
		$sql = 'SELECT * FROM flujo_caja WHERE valor_forma2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna devolucion sea igual al valor pasado como parámetro
     *
     * @param String $value devolucion requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDevolucion($value){
		$sql = 'SELECT * FROM flujo_caja WHERE devolucion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cuenta_id sea igual al valor pasado como parámetro
     *
     * @param String $value cuenta_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCuentaId($value){
		$sql = 'SELECT * FROM flujo_caja WHERE cuenta_id = ?';
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
		$sql = 'SELECT * FROM flujo_caja WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_iva sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_iva requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPorcenIva($value){
		$sql = 'SELECT * FROM flujo_caja WHERE porcen_iva = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_iva sea igual al valor pasado como parámetro
     *
     * @param String $value valor_iva requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorIva($value){
		$sql = 'SELECT * FROM flujo_caja WHERE valor_iva = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_iva sea igual al valor pasado como parámetro
     *
     * @param String $value valor_iva requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByCupologId($value){
        $sql = 'SELECT * FROM flujo_caja WHERE cupolog_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
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
		$sql = 'DELETE FROM flujo_caja WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM flujo_caja WHERE hora_crea = ?';
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
		$sql = 'DELETE FROM flujo_caja WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipomov_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipomov_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipomovId($value){
		$sql = 'DELETE FROM flujo_caja WHERE tipomov_id = ?';
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
		$sql = 'DELETE FROM flujo_caja WHERE valor = ?';
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
		$sql = 'DELETE FROM flujo_caja WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna traslado sea igual al valor pasado como parámetro
     *
     * @param String $value traslado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTraslado($value){
		$sql = 'DELETE FROM flujo_caja WHERE traslado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna doctraslado_id sea igual al valor pasado como parámetro
     *
     * @param String $value doctraslado_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDoctrasladoId($value){
		$sql = 'DELETE FROM flujo_caja WHERE doctraslado_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna recarga_id sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRecargaId($value){
		$sql = 'DELETE FROM flujo_caja WHERE recarga_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna formapago1_id sea igual al valor pasado como parámetro
     *
     * @param String $value formapago1_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFormapago1Id($value){
		$sql = 'DELETE FROM flujo_caja WHERE formapago1_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna formapago2_id sea igual al valor pasado como parámetro
     *
     * @param String $value formapago2_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFormapago2Id($value){
		$sql = 'DELETE FROM flujo_caja WHERE formapago2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_forma1 sea igual al valor pasado como parámetro
     *
     * @param String $value valor_forma1 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorForma1($value){
		$sql = 'DELETE FROM flujo_caja WHERE valor_forma1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_forma2 sea igual al valor pasado como parámetro
     *
     * @param String $value valor_forma2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorForma2($value){
		$sql = 'DELETE FROM flujo_caja WHERE valor_forma2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna devolucion sea igual al valor pasado como parámetro
     *
     * @param String $value devolucion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDevolucion($value){
		$sql = 'DELETE FROM flujo_caja WHERE devolucion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna cuenta_id sea igual al valor pasado como parámetro
     *
     * @param String $value cuenta_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCuentaId($value){
		$sql = 'DELETE FROM flujo_caja WHERE cuenta_id = ?';
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
		$sql = 'DELETE FROM flujo_caja WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_iva sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_iva requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPorcenIva($value){
		$sql = 'DELETE FROM flujo_caja WHERE porcen_iva = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_iva sea igual al valor pasado como parámetro
     *
     * @param String $value valor_iva requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorIva($value){
		$sql = 'DELETE FROM flujo_caja WHERE valor_iva = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


    /**
     * Elimina registros de la tabla flujo_caja basados en el valor de cupolog_id.
     *
     * @param mixed $value El valor de cupolog_id para identificar los registros a eliminar.
     * @return int El número de filas afectadas por la operación de eliminación.
     */
    public function deleteByCupologId($value){
        $sql = 'DELETE FROM flujo_caja WHERE cupolog_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

	





	
    /**
     * Crear y devolver un objeto del tipo CupoLog
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $FlujoCaja FlujoCaja
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$flujoCaja = new FlujoCaja();
		
		$flujoCaja->flujocajaId = $row['flujocaja_id'];
		$flujoCaja->fechaCrea = $row['fecha_crea'];
		$flujoCaja->horaCrea = $row['hora_crea'];
		$flujoCaja->usucreaId = $row['usucrea_id'];
		$flujoCaja->tipomovId = $row['tipomov_id'];
		$flujoCaja->valor = $row['valor'];
		$flujoCaja->ticketId = $row['ticket_id'];
		$flujoCaja->traslado = $row['traslado'];
		$flujoCaja->doctrasladoId = $row['doctraslado_id'];
		$flujoCaja->recargaId = $row['recarga_id'];
		$flujoCaja->formapago1Id = $row['formapago1_id'];
		$flujoCaja->formapago2Id = $row['formapago2_id'];
		$flujoCaja->valorForma1 = $row['valor_forma1'];
		$flujoCaja->valorForma2 = $row['valor_forma2'];
		$flujoCaja->devolucion = $row['devolucion'];
		$flujoCaja->cuentaId = $row['cuenta_id'];
		$flujoCaja->mandante = $row['mandante'];
		$flujoCaja->porcenIva = $row['porcen_iva'];
		$flujoCaja->valorIva = $row['valor_iva'];
		$flujoCaja->cupologId = $row['cupolog_id'];

		return $flujoCaja;
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
