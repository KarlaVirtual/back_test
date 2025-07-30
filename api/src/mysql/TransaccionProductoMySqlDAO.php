<?php

namespace Backend\mysql;

use Exception;
use Backend\dto\Helpers;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\sql\QueryExecutor;
use Backend\dto\TransaccionProducto;
use Backend\dao\TransaccionProductoDAO;

/**
 * Clase 'TransaccionProductoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'TransaccionProducto'
 *
 * Ejemplo de uso:
 * $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 */
class TransaccionProductoMySqlDAO implements TransaccionProductoDAO
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
	 */
	public function getTransaction()
	{
		return $this->transaction;
	}

	/**
	 * Modificar el atributo transacción del objeto
	 *
	 * @param mixed $transaction transacción
	 *
	 * @return void
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
	 * @return void
	 * @throws no
	 *
	 * @access public
	 * @see no
	 * @since no
	 * @deprecated no
	 */
	public function __construct($transaction = "")
	{
		if ($transaction == "") {
			$transaction = new Transaction();
			$this->transaction = $transaction;
		} else {
			$this->transaction = $transaction;
		}
	}

	/**
	 * Obtener todos los registros condicionados por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param string $id llave primaria
	 *
	 * @return array resultado de la consulta
	 */
	public function load($id)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE transproducto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Obtener todos los registros condicionados por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @return array resultado de la consulta
	 */
	public function queryAll()
	{
		$sql = 'SELECT * FROM transaccion_producto';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros
	 * ordenadas por el nombre de la columna 
	 * que se pasa como parámetro
	 *
	 * @param string $orderColumn nombre de la columna
	 *
	 * @return array resultado de la consulta
	 */
	public function queryAllOrderBy($orderColumn)
	{
		$sql = 'SELECT * FROM transaccion_producto ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}


	/**
	 * Consulta transacciones de productos por ID de tarjeta de crédito.
	 *
	 * @param int $value El ID de la tarjeta de crédito.
	 * @return array Lista de transacciones de productos asociadas al ID de la tarjeta de crédito.
	 */
	public function queryByTarjetaId($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE usutarjetacred_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);

		return $this->getList($sqlQuery);
	}

	/**
	 * Eliminar todos los registros condicionados
	 * por la llave primaria
	 *
	 * @param string $transproducto_id llave primaria
	 *
	 * @return boolean $ resultado de la consulta
	 */
	public function delete($transproducto_id)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE transproducto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($transproducto_id);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Insertar un registro en la base de datos
	 *
	 * @param mixed $transaccionProducto transaccionProducto
	 *
	 * @return string $id resultado de la consulta
	 */
	public function insert($transaccionProducto)
	{
		$sql = 'INSERT INTO transaccion_producto (producto_id, usuario_id, valor, impuesto, comision, estado, tipo, externo_id, estado_producto, mandante, final_id, usutarjetacred_id, usubanco_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transaccionProducto->productoId);
		$sqlQuery->setNumber($transaccionProducto->usuarioId);
		$sqlQuery->set($transaccionProducto->valor);

		if ($transaccionProducto->impuesto == "") {
			$transaccionProducto->impuesto = '0';
		}
		$sqlQuery->set($transaccionProducto->impuesto);

		if ($transaccionProducto->comision == "") {
			$transaccionProducto->comision = '0';
		}
		$sqlQuery->set($transaccionProducto->comision);

		$sqlQuery->set($transaccionProducto->estado);
		$sqlQuery->set($transaccionProducto->tipo);
		$sqlQuery->set($transaccionProducto->externoId);
		$sqlQuery->set($transaccionProducto->estadoProducto);
		$sqlQuery->setNumber($transaccionProducto->mandante);
		$sqlQuery->setNumber($transaccionProducto->finalId);
		$sqlQuery->setNumber($transaccionProducto->usutarjetacredId);
        if($transaccionProducto->usubancoId =='' || $transaccionProducto->usubancoId == null){
            $transaccionProducto->usubancoId='0';
        }
		$sqlQuery->setNumber($transaccionProducto->usubancoId);

		$id = $this->executeInsert($sqlQuery);
		$transaccionProducto->transproductoId = $id;

		if ($_REQUEST['vs_utm_campaign'] != '') {


			try {
				if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"]   != '') {
					$_ENV["connectionGlobalTMP"] = $_ENV["connectionGlobal"];
					$_ENV["connectionGlobal"] = null;
				}
				$SitioTracking = new \Backend\dto\SitioTracking();

				$SitioTracking->setTabla('transaccion_producto');
				$SitioTracking->setTablaId($id);
				$SitioTracking->setTipo('2');
				$SitioTracking->setTvalue($_REQUEST['vs_utm_campaign']);
				$SitioTracking->valueInd = substr($_REQUEST['vs_utm_campaign'], 0, 49);
				$SitioTracking->setUsucreaId('0');
				$SitioTracking->setUsumodifId('0');


				$SitioTrackingMySqlDAO = new \Backend\mysql\SitioTrackingMySqlDAO();
				$SitioTrackingMySqlDAO->insert($SitioTracking);
				$SitioTrackingMySqlDAO->getTransaction()->commit();

				if ($_ENV["connectionGlobalTMP"] != null && $_ENV["connectionGlobalTMP"]   != '') {
					$_ENV["connectionGlobal"] = $_ENV["connectionGlobalTMP"];
					$_ENV["connectionGlobalTMP"] = null;
				}
			} catch (Exception $e) {
				if ($_ENV["connectionGlobalTMP"] != null && $_ENV["connectionGlobalTMP"]   != '') {
					$_ENV["connectionGlobal"] = $_ENV["connectionGlobalTMP"];
					$_ENV["connectionGlobalTMP"] = null;
				}
			}
		}
		return $id;
	}

	/**
	 * Editar un registro en la base de datos
	 *
	 * @param mixed $transaccionProducto transaccionProducto
     * @param string $where condiciones adicionales para el proceso de actualización
	 *
	 * @return boolean resultado de la consulta
	 */
	public function update($transaccionProducto, $where = "")
	{
		$sql = 'UPDATE transaccion_producto SET producto_id = ?, usuario_id = ?, valor = ?, impuesto = ?, comision = ?, estado = ?, tipo = ?, externo_id = ?, estado_producto = ?, mandante = ?, final_id = ?, usutarjetacred_id = ?, usubanco_id = ? WHERE transproducto_id = ? ' . $where;
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transaccionProducto->productoId);
		$sqlQuery->setNumber($transaccionProducto->usuarioId);
		$sqlQuery->set($transaccionProducto->valor);

		if ($transaccionProducto->impuesto == "") {
			$transaccionProducto->impuesto = '0';
		}
		$sqlQuery->set($transaccionProducto->impuesto);

		if ($transaccionProducto->comision == "") {
			$transaccionProducto->comision = '0';
		}
		$sqlQuery->set($transaccionProducto->comision);

		$sqlQuery->set($transaccionProducto->estado);
		$sqlQuery->set($transaccionProducto->tipo);
		$sqlQuery->set($transaccionProducto->externoId);
		$sqlQuery->set($transaccionProducto->estadoProducto);
		$sqlQuery->setNumber($transaccionProducto->mandante);
		$sqlQuery->setNumber($transaccionProducto->finalId);
		$sqlQuery->setNumber($transaccionProducto->usutarjetacredId);

        if($transaccionProducto->usubancoId =='' || $transaccionProducto->usubancoId == null){
            $transaccionProducto->usubancoId='0';
        }

		$sqlQuery->setNumber($transaccionProducto->usubancoId);

		$sqlQuery->setNumber($transaccionProducto->transproductoId);

		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todas los registros de la base de datos
	 *
	 * @return boolean resultado de la consulta
	 */
	public function clean()
	{
		$sql = 'DELETE FROM transaccion_producto';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna producto_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value producto_id requerido
	 *
	 * @return array resultado de la consulta
	 */
	public function queryByProductoId($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna usuario_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value usuario_id requerido
	 *
	 * @return array resultado de la consulta
	 */
	public function queryByUsuarioId($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna valor sea igual al valor pasado como parámetro
	 *
	 * @param string $value valor requerido
	 *
	 * @return array resultado de la consulta
	 */
	public function queryByValor($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna estado sea igual al valor pasado como parámetro
	 *
	 * @param string $value estado requerido
	 *
	 * @return array $ resultado de la consulta
	 */
	public function queryByEstado($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna tipo sea igual al valor pasado como parámetro
	 *
	 * @param string $value tipo requerido
	 *
	 * @return array $ resultado de la consulta
	 */
	public function queryByTipo($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna externo_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value externo_id requerido
	 *
	 * @return array $ resultado de la consulta
	 */
	public function queryByExternoId($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE externo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}



	/**
	 * Consulta registros en la tabla transaccion_producto por externo_id y producto_id.
	 *
	 * @param int $externoId El ID externo para filtrar los registros.
	 * @param int $productoId El ID del producto para filtrar los registros.
	 * @return array Lista de registros que coinciden con los criterios de búsqueda.
	 */
	public function queryByExternoIdAndProductoId($externoId, $productoId)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE externo_id = ? AND producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($externoId);
		$sqlQuery->set($productoId);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna estado_producto sea igual al valor pasado como parámetro
	 *
	 * @param string $value estado_producto requerido
	 *
	 * @return array $ resultado de la consulta
	 */
	public function queryByEstadoProducto($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE estado_producto = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna mandante sea igual al valor pasado como parámetro
	 *
	 * @param string $value mandante requerido
	 *
	 * @return array $ resultado de la consulta
	 */
	public function queryByMandante($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna final_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value final_id requerido
	 *
	 * @return array $ resultado de la consulta
	 */
	public function queryByFinalId($value)
	{
		$sql = 'SELECT * FROM transaccion_producto WHERE final_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Realizar una consulta en la tabla de TransaccionProducto 'TransaccionProducto'
	 * de una manera personalizada
	 *
	 * @param string $sidx columna para ordenar
	 * @param string $sord orden los datos asc | desc
	 * @param string $start inicio de la consulta
	 * @param string $limit limite de la consulta
	 * @param string $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
	 *
	 * @return array $json resultado de la consulta
	 */
	public function queryTransacciones($sidx, $sord, $start, $limit, $filters, $searchOn)
	{
		$where = " where 1=1 ";

		if ($searchOn) {
			// Construye el where
			$filters = json_decode($filters);
			$whereArray = array();
			$rules = $filters->rules;
			$groupOperation = $filters->groupOp;
			$cont = 0;

			foreach ($rules as $rule) {
				$fieldName = $rule->field;
				$fieldData = $rule->data;


				$Helpers = new Helpers();

				if ($fieldName == 'registro.cedula') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
				}
				if ($fieldName == 'registro.celular') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
				}
				if ($fieldName == 'usuario.cedula') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
				}
				if ($fieldName == 'usuario.login') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'usuario_mandante.email') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'punto_venta.email') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'usuario_sitebuilder.login') {
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
						$fieldOperation = " NOT IN '" . $fieldData . "'";
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
				if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
				if (oldCount($whereArray) > 0) {
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				} else {
					$where = "";
				}
			}
		}



		$sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ count(*) count FROM transaccion_producto INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_producto.producto_id) INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " . $where;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);
		$sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ transaccion_producto.*,producto.*,producto_mandante.*,proveedor.* FROM transaccion_producto INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id) INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

		$result = $this->execute2($sqlQuery);

		$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

		return  $json;
	}

	/**
	 * Realizar una consulta en la tabla de TransaccionProducto 'TransaccionProducto'
	 * de una manera personalizada
	 *
	 * @param string $select campos de consulta
	 * @param string $sidx columna para ordenar
	 * @param string $sord orden los datos asc | desc
	 * @param string $start inicio de la consulta
	 * @param string $limit limite de la consulta
	 * @param string $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
     * @param string $grouping campo por el cual se agruparán los resultados (opcional)
	 *
	 * @return array $json resultado de la consulta
	 */
	public function queryTransaccionesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = '')
	{

		$where = " where 1=1 ";

		$Helpers = new Helpers();

		if ($searchOn) {
			// Construye el where
			$filters = json_decode($filters);
			$whereArray = array();
			$rules = $filters->rules;
			$groupOperation = $filters->groupOp;
			$cont = 0;

			foreach ($rules as $rule) {
				$fieldName = $Helpers->set_custom_field($rule->field);
				$fieldData = $rule->data;


				$Helpers = new Helpers();

				if ($fieldName == 'registro.cedula') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
				}
				if ($fieldName == 'registro.celular') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
				}
				if ($fieldName == 'usuario.cedula') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
				}
				if ($fieldName == 'usuario.login') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'usuario_mandante.email') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'punto_venta.email') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'usuario_sitebuilder.login') {
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
						$fieldOperation = " NOT IN '" . $fieldData . "'";
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
				if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
				if (oldCount($whereArray) > 0) {
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				} else {
					$where = "";
				}
			}
		}


		if ($grouping != "") {
			$where = $where . " GROUP BY " . $grouping;
		}

		$ordersql = "";
		if ($sidx != "" && $sord != '') {
			$ordersql = " order by " . $sidx . " " . $sord;
		}

		$sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ count(*) count FROM transaccion_producto  INNER JOIN producto ON (producto.producto_id=transaccion_producto.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) INNER JOIN usuario ON (usuario.usuario_id=transaccion_producto.usuario_id)  LEFT JOIN usuario_banco ON (usuario_banco.usubanco_id=transaccion_producto.usubanco_id) " . $where;

		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);
		$sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ " . $select . " FROM transaccion_producto  INNER JOIN producto ON (producto.producto_id=transaccion_producto.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) INNER JOIN usuario ON (usuario.usuario_id=transaccion_producto.usuario_id) LEFT JOIN usuario_banco ON (usuario_banco.usubanco_id=transaccion_producto.usubanco_id) " . $where . " " . $ordersql . " LIMIT " . $start . " , " . $limit;
		$sqlQuery = new SqlQuery($sql);

		$result = $Helpers->process_data($this->execute2($sqlQuery));

		$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

		return  $json;
	}




	/**
	 * Realiza una consulta personalizada de transacciones de productos con filtros, ordenamiento y paginación.
	 *
	 * @param string $select Campos a seleccionar en la consulta.
	 * @param string $sidx Campo por el cual se ordenarán los resultados.
	 * @param string $sord Orden de los resultados (ascendente o descendente).
	 * @param int $start Índice de inicio para la paginación.
	 * @param int $limit Número de registros a devolver.
	 * @param string $filters Filtros en formato JSON para aplicar a la consulta.
	 * @param boolean $searchOn Indica si se deben aplicar los filtros.
	 * @param string $grouping Campo por el cual se agruparán los resultados (opcional).
	 * @return string JSON con el conteo total de registros y los datos resultantes de la consulta.
	 */
	public function queryTransaccionesCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = '')
	{
		$where = " where 1=1 ";

		$Helpers = new Helpers();

		if ($searchOn) {
			// Construye el where
			$filters = json_decode($filters);
			$whereArray = array();
			$rules = $filters->rules;
			$groupOperation = $filters->groupOp;
			$cont = 0;

			foreach ($rules as $rule) {
				$fieldName = $Helpers->set_custom_field($rule->field);
				$fieldData = $rule->data;


				$Helpers = new Helpers();

				if ($fieldName == 'registro.cedula') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
				}
				if ($fieldName == 'registro.celular') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
				}
				if ($fieldName == 'usuario.cedula') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
				}
				if ($fieldName == 'usuario.login') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'usuario_mandante.email') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'punto_venta.email') {
					$fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
				}
				if ($fieldName == 'usuario_sitebuilder.login') {
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
						$fieldOperation = " NOT IN '" . $fieldData . "'";
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
				if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
				if (oldCount($whereArray) > 0) {
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				} else {
					$where = "";
				}
			}
		}


		if ($grouping != "") {
			$where = $where . " GROUP BY " . $grouping;
		}

		$ordersql = "";
		if ($sidx != "" && $sord != '') {
			$ordersql = " order by " . $sidx . " " . $sord;
		}

		$sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ count(*) count FROM transaccion_producto  INNER JOIN producto ON (producto.producto_id=transaccion_producto.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) LEFT OUTER JOIN usuario ON (usuario.usuario_id=transaccion_producto.usuario_id)" . $where;

		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);
		$sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ " . $select . " FROM transaccion_producto  INNER JOIN producto ON (producto.producto_id=transaccion_producto.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) LEFT OUTER JOIN usuario ON (usuario.usuario_id=transaccion_producto.usuario_id) " . $where . " " . $ordersql . " LIMIT " . $start . " , " . $limit;
		$sqlQuery = new SqlQuery($sql);

		$result = $Helpers->process_data($this->execute2($sqlQuery));

		$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

		return  $json;
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna producto_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value producto_id requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByProductoId($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna usuario_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value usuario_id requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByUsuarioId($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna valor sea igual al valor pasado como parámetro
	 *
	 * @param string $value valor requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByValor($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna estado sea igual al valor pasado como parámetro
	 *
	 * @param string $value estado requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByEstado($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna tipo sea igual al valor pasado como parámetro
	 *
	 * @param string $value tipo requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByTipo($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna externo_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value externo_id requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByExternoId($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE externo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna estado_producto sea igual al valor pasado como parámetro
	 *
	 * @param string $value estado_producto requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByEstadoProducto($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE estado_producto = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna mandante sea igual al valor pasado como parámetro
	 *
	 * @param string $value mandante requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByMandante($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna final_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value final_id requerido
	 *
	 * @return boolean $ resultado de la ejecución
	 */
	public function deleteByFinalId($value)
	{
		$sql = 'DELETE FROM transaccion_producto WHERE final_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna transproducto_id sea igual al valor pasado como parámetro
	 *
	 * @param string $value transproducto_id requerido
	 *
	 * @return array $ resultado de la consulta
	 */
	public function getComplete($value)
	{

		$sql = "SELECT
  transaccionProducto.*,
  producto.*,
  proveedor.*,
  productoMandante.*,
  usuario.*,
  prodmandantePais.*
FROM transaccion_producto transaccionProducto INNER JOIN producto
    ON (producto.producto_id = transaccionProducto.producto_id)
  INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)
  INNER JOIN producto_mandante productoMandante ON (producto.producto_id = productoMandante.producto_id AND
                                                    transaccionProducto.mandante = productoMandante.mandante)
  INNER JOIN usuario ON (usuario.usuario_id = transaccionProducto.usuario_id)
  LEFT OUTER JOIN prodmandante_pais prodmandantePais
    ON (prodmandantePais.producto_id = producto.producto_id AND prodmandantePais.mandante = transaccionProducto.mandante
        AND prodmandantePais.pais_id = usuario.pais_id)
WHERE proveedor.tipo = 'PAYMENT' AND transaccionProducto.transproducto_id = ? ";

		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);

		$Helpers = new Helpers();
		return $Helpers->process_data($this->execute2($sqlQuery));
	}

	/**
	 * Crear y devolver un objeto del tipo TransaccionProducto
	 * con los valores de una consulta sql
	 *
	 *
	 * @param Arreglo $row arreglo asociativo
	 *
	 * @return Objeto $transaccionProducto TransaccionProducto
	 *
	 * @access protected
	 */
	protected function readRow($row)
	{
		$transaccionProducto = new TransaccionProducto();

		$transaccionProducto->transproductoId = $row['transproducto_id'];
		$transaccionProducto->productoId = $row['producto_id'];
		$transaccionProducto->usuarioId = $row['usuario_id'];
		$transaccionProducto->valor = $row['valor'];
		$transaccionProducto->impuesto = $row['impuesto'];
		$transaccionProducto->comision = $row['comision'];
		$transaccionProducto->estado = $row['estado'];
		$transaccionProducto->tipo = $row['tipo'];
		$transaccionProducto->externoId = $row['externo_id'];
		$transaccionProducto->estadoProducto = $row['estado_producto'];
		$transaccionProducto->mandante = $row['mandante'];
		$transaccionProducto->finalId = $row['final_id'];
		$transaccionProducto->usutarjetacredId = $row['usutarjetacred_id'];
		$transaccionProducto->usubancoId = $row['usubanco_id'];

		return $transaccionProducto;
	}

	/**
	 * Ejecutar una consulta sql y devolver los datos
	 * como un arreglo asociativo
	 *
	 *
	 * @param string $sqlQuery consulta sql
	 *
	 * @return array $ret arreglo indexado
	 *
	 * @access protected
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
	 * @param string $sqlQuery consulta sql
	 *
	 * @return array $ resultado de la ejecución
	 *
	 * @access protected
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
	 * @param string $sqlQuery consulta sql
	 *
	 * @return array $ resultado de la ejecución
	 *
	 * @access protected
	 */
	protected function execute($sqlQuery)
	{
		return QueryExecutor::execute($this->transaction, $sqlQuery);
	}

	/**
	 * Ejecutar una consulta sql
	 *
	 *
	 * @param string $sqlQuery consulta sql
	 *
	 * @return array $ resultado de la ejecución
	 *
	 * @access protected
	 */
	protected function execute2($sqlQuery)
	{
		return QueryExecutor::execute2($this->transaction, $sqlQuery);
	}

	/**
	 * Ejecutar una consulta sql como update
	 *
	 *
	 * @param string $sqlQuery consulta sql
	 *
	 * @return array $ resultado de la ejecución
	 *
	 * @access protected
	 */
	protected function executeUpdate($sqlQuery)
	{
		return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
	}

	/**
	 * Ejecutar una consulta sql como select
	 *
	 *
	 * @param string $sqlQuery consulta sql
	 *
	 * @return array $ resultado de la ejecución
	 *
	 * @access protected
	 */
	protected function querySingleResult($sqlQuery)
	{
		return QueryExecutor::queryForString($this->transaction, $sqlQuery);
	}

	/**
	 * Ejecutar una consulta sql como insert
	 *
	 *
	 * @param string $sqlQuery consulta sql
	 *
	 * @return array resultado de la ejecución
	 *
	 * @access protected
	 */
	protected function executeInsert($sqlQuery)
	{
		return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
	}
}