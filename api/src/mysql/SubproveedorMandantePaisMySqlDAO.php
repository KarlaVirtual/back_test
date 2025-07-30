<?php 
	namespace Backend\mysql;

	use Backend\dto\SubproveedorMandantePais;
	use Backend\sql\Transaction;
	use Backend\sql\SqlQuery;
	use Backend\sql\QueryExecutor;
use Backend\utils\RedisConnectionTrait;

/**
	 * Clase 'SubproveedorMandantePaisMySqlDAO'
	 * Provee las consultas de la tabla 'subproveedor_mandante_pais' en la base de datos
	 * 
	 * @author: Daniel Tamayo
	 * @since: Desconocido
	 * @category No
	 * @package No
	 * @version 1.0
	 */
	class SubproveedorMandantePaisMySqlDAO {

		/** Objeto vincula una conexión de la base de datos con el objeto correspondiente
		* @var Transaction $transaction
		*/
		private $transaction;

		/**
		 * Constructor de la clase.
		 *
		 * @param Transaction $transaction Objeto de transacción opcional.
		 */
		public function __construct($transaction = '') {
			if ($transaction == '') {
				$transaction = new Transaction();
				$this->transaction = $transaction;
			} else {
				$this->transaction = $transaction;
			}
		}

		/**
		 * Establece la transacción.
		 *
		 * @param Transaction $transaction Objeto de transacción.
		 */
		public function setTransaction($transaction) {
			$this->transaction = $transaction;
		}

		/**
		 * Obtiene la transacción.
		 *
		 * @return Transaction Objeto de transacción.
		 */
		public function getTransaction() {
			return $this->transaction;
		}

		/**
		 * Carga un registro por ID.
		 *
		 * @param int $id ID del registro.
		 * @return SubproveedorMandantePais Objeto del registro.
		 */
		public function load($id) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE provmandante_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($id);
			return $this->getRow($sqlQuery);
		}

		/**
		 * Consulta todos los registros.
		 *
		 * @return array Lista de registros.
		 */
		public function queryAll() {
			$sql = 'SELECT * FROM subproveedor_mandante_pais';
			$sqlQuery = new SqlQuery($sql);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta todos los registros ordenados por una columna.
		 *
		 * @param string $orderColumn Columna para ordenar.
		 * @return array Lista de registros ordenados.
		 */
		public function queryAllOrderBy($orderColumn) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais ORDER BY ' . $orderColumn;
			$sqlQuery = new SqlQuery($sql);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta personalizada de subproveedores mandante país.
		 *
		 * @param string $select Campos a seleccionar en la consulta.
		 * @param string $sidx Índice de ordenación.
		 * @param string $sord Orden de clasificación (ASC o DESC).
		 * @param int $start Inicio del límite de resultados.
		 * @param int $limit Número de resultados a devolver.
		 * @param string $filters Filtros en formato JSON.
		 * @param bool $searchOn Indica si la búsqueda está activada.
		 * @return string JSON con el conteo de resultados y los datos obtenidos.
		 */
		public function querySubproveedoresMandantePaisCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {

			$where = " where 1=1 ";

			if ($searchOn) {
				$filters = json_decode($filters);
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				$cont = 0;

				foreach ($rules as $rule) {
					$fieldName = $rule->field;
					$fieldData = $rule->data;
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
						$where = $where . " " . $groupOperation . " " . $fieldName . " " . strtoupper($fieldOperation);
					} else {
						$where = "";
					}
				}

			}

			$sql = 'SELECT count(*) count FROM subproveedor_mandante_pais INNER JOIN subproveedor ON (subproveedor_mandante_pais.subproveedor_id = subproveedor.subproveedor_id) INNER JOIN subproveedor_mandante ON (subproveedor_mandante_pais.subproveedor_id = subproveedor_mandante.subproveedor_id and subproveedor_mandante_pais.mandante = subproveedor_mandante.mandante) INNER JOIN proveedor ON (proveedor.proveedor_id = subproveedor.proveedor_id) INNER JOIN mandante ON (mandante.mandante = subproveedor_mandante_pais.mandante)  ' . $where;

			$sqlQuery = new SqlQuery($sql);

			$count = $this->execute2($sqlQuery);

			$sql = 'SELECT ' .$select .'  FROM subproveedor_mandante_pais INNER JOIN subproveedor ON (subproveedor_mandante_pais.subproveedor_id = subproveedor.subproveedor_id) INNER JOIN subproveedor_mandante ON (subproveedor_mandante_pais.subproveedor_id = subproveedor_mandante.subproveedor_id and subproveedor_mandante_pais.mandante = subproveedor_mandante.mandante) INNER JOIN proveedor ON (proveedor.proveedor_id = subproveedor.proveedor_id) INNER JOIN mandante ON (mandante.mandante = subproveedor_mandante_pais.mandante)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
			$sqlQuery = new SqlQuery($sql);


			if($_ENV["debugFixed2"] == '1'){
				print_r($sql);
			}

			$result = $this->execute2($sqlQuery);

			$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

			return $json;
		}


/**
		 * Elimina un registro por ID.
		 *
		 * @param int $provmandante_id ID del registro a eliminar.
		 * @return int Número de filas afectadas.
		 */
		public function delete($provmandante_id) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE provmandante_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($provmandante_id);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Inserta un nuevo registro.
		 *
		 * @param SubproveedorMandantePais $SubproveedorMandantePais Objeto con los datos del registro a insertar.
		 * @return int ID del registro insertado.
		 */
		public function insert($SubproveedorMandantePais) {
			$sql = 'INSERT INTO subproveedor_mandante_pais (mandante, subproveedor_id, estado, detalle, usucrea_id, usumodif_id, verifica, filtro_pais, max, min, orden, pais_id,imagen,credentials,bonus_system,usuarios_prueba) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)';
			$sqlQuery = new SqlQuery($sql);

			$sqlQuery->setNumber($SubproveedorMandantePais->getMandante());
			$sqlQuery->setNumber($SubproveedorMandantePais->getSubproveedorId());
			$sqlQuery->set($SubproveedorMandantePais->getEstado());
			$sqlQuery->set($SubproveedorMandantePais->getDetalle());
			$sqlQuery->setNumber($SubproveedorMandantePais->getUsucreaId());
			$sqlQuery->setNumber($SubproveedorMandantePais->getUsumodifId());
			$sqlQuery->set($SubproveedorMandantePais->getVerifica());
			$sqlQuery->set($SubproveedorMandantePais->getFiltroPais());
			$sqlQuery->set($SubproveedorMandantePais->getMax());
			$sqlQuery->set($SubproveedorMandantePais->getMin());
			$sqlQuery->set($SubproveedorMandantePais->getOrden());
			$sqlQuery->setNumber($SubproveedorMandantePais->getPaisId());
			$sqlQuery->set($SubproveedorMandantePais->getImage());
			if ($SubproveedorMandantePais->getCredentials() == '') {
				$sqlQuery->setSIN('null');
			}
			else {
			$sqlQuery->set($SubproveedorMandantePais->getCredentials());
			}
			if ($SubproveedorMandantePais->getBonusSystem() == '') {
				$sqlQuery->setSIN('null');
			}
			else {
			$sqlQuery->set($SubproveedorMandantePais->getBonusSystem());
			}

			if ($SubproveedorMandantePais->getusuariosPrueba() == "") {
				$sqlQuery->setSIN('null');
			} else {
				$sqlQuery->set($SubproveedorMandantePais->getusuariosPrueba());
			}

			return $this->executeInsert($sqlQuery);
		}

		/**
		 * Actualiza un registro existente.
		 *
		 * @param SubproveedorMandantePais $SubproveedorMandantePais Objeto con los datos del registro a actualizar.
		 * @return int Número de filas afectadas.
		 */
		public function update($SubproveedorMandantePais) {
			$sql = 'UPDATE subproveedor_mandante_pais SET mandante = ?, subproveedor_id = ?, estado = ?, detalle = ?, usucrea_id = ?, fecha_crea = ?, usumodif_id = ?, verifica = ?, filtro_pais = ?, max = ?, min = ?, orden = ?, pais_id = ?,imagen = ?,credentials = ?,bonus_system = ?,usuarios_prueba = ? WHERE provmandante_id = ?';
			$sqlQuery = new SqlQuery($sql);

			$sqlQuery->setNumber($SubproveedorMandantePais->getMandante());
			$sqlQuery->setNumber($SubproveedorMandantePais->getSubproveedorId());
			$sqlQuery->set($SubproveedorMandantePais->getEstado());
			$sqlQuery->set($SubproveedorMandantePais->getDetalle());
			$sqlQuery->setNumber($SubproveedorMandantePais->getUsucreaId());
			$sqlQuery->set($SubproveedorMandantePais->getFechaCrea());
			$sqlQuery->setNumber($SubproveedorMandantePais->getUsumodifId());
			$sqlQuery->set($SubproveedorMandantePais->getVerifica());
			$sqlQuery->set($SubproveedorMandantePais->getFiltroPais());
			$sqlQuery->set($SubproveedorMandantePais->getMax());
			$sqlQuery->set($SubproveedorMandantePais->getMin());
			$sqlQuery->setNumber($SubproveedorMandantePais->getOrden());
			$sqlQuery->setNumber($SubproveedorMandantePais->getPaisId());
			$sqlQuery->set($SubproveedorMandantePais->getImage());

			if($SubproveedorMandantePais->getCredentials() == ""){
				$sqlQuery->setSIN('null');
			}else{
				$sqlQuery->set($SubproveedorMandantePais->getCredentials());
			}

			if ($SubproveedorMandantePais->getBonusSystem() == "") {
				$sqlQuery->setSIN('null');
			} else {
			$sqlQuery->set($SubproveedorMandantePais->getBonusSystem());
			}

			if ($SubproveedorMandantePais->getusuariosPrueba() == "") {
				$sqlQuery->setSIN('null');
			} else {
			$sqlQuery->set($SubproveedorMandantePais->getusuariosPrueba());
			}

			$sqlQuery->setNumber($SubproveedorMandantePais->getProvmandanteId());
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina todos los registros de la tabla.
		 *
		 * @return int Número de filas afectadas.
		 */
		public function clean() {
			$sql = 'DELETE FROM subproveedor_mandante_pais';
			$sqlQuery = new SqlQuery($sql);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Consulta registros por subproveedor.
		 *
		 * @param int $value ID del subproveedor.
		 * @return array Lista de registros.
		 */
		public function queryBySubproveedor($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE subproveedor_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por mandante.
		 *
		 * @param int $value ID del mandante.
		 * @return array Lista de registros.
		 */
		public function queryByMandante($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE mandante = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por país.
		 *
		 * @param int $value ID del país.
		 * @return array Lista de registros.
		 */
		public function queryByPais($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE pais_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por subproveedor, mandante y país.
		 *
		 * @param int $subproveedorId ID del subproveedor.
		 * @param int $manddante ID del mandante.
		 * @param int $pais ID del país.
		 * @return array Lista de registros.
		 */
		public function queryBySubproveedorIdAndMandantePais($subproveedorId, $manddante, $pais) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE subproveedor_id = ? AND mandante = ? AND pais_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($subproveedorId);
			$sqlQuery->setNumber($manddante);
			$sqlQuery->setNumber($pais);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por estado.
		 *
		 * @param string $value Estado del registro.
		 * @return array Lista de registros.
		 */
		public function queryByEstado($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE estado = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por verificación.
		 *
		 * @param string $value Verificación del registro.
		 * @return array Lista de registros.
		 */
		public function queryByVerifica($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE verifica = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por filtro de país.
		 *
		 * @param string $value Filtro de país del registro.
		 * @return array Lista de registros.
		 */
		public function queryByFiltroPais($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE filtro_pais = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por fecha de creación.
		 *
		 * @param string $value Fecha de creación del registro.
		 * @return array Lista de registros.
		 */
		public function queryByFechaCrea($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE fecha_crea = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por fecha de modificación.
		 *
		 * @param string $value Fecha de modificación del registro.
		 * @return array Lista de registros.
		 */
		public function queryByFechaModif($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE fecha_modif = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por ID de usuario creador.
		 *
		 * @param int $value ID del usuario creador.
		 * @return array Lista de registros.
		 */
		public function queryByUsucreaId($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE usucrea_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por ID de usuario modificador.
		 *
		 * @param int $value ID del usuario modificador.
		 * @return array Lista de registros.
		 */
		public function queryByUsumodifId($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE usumodif_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por valor máximo.
		 *
		 * @param string $value Valor máximo del registro.
		 * @return array Lista de registros.
		 */
		public function queryByMax($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE max = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por valor mínimo.
		 *
		 * @param string $value Valor mínimo del registro.
		 * @return array Lista de registros.
		 */
		public function queryByMin($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE min = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Consulta registros por detalle.
		 *
		 * @param string $value Detalle del registro.
		 * @return array Lista de registros.
		 */
		public function queryByDetalle($value) {
			$sql = 'SELECT * FROM subproveedor_mandante_pais WHERE detalle = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->getList($sqlQuery);
		}

		/**
		 * Elimina registros por subproveedor.
		 *
		 * @param int $value ID del subproveedor.
		 * @return int Número de filas afectadas.
		 */
		public function deleteBySubproveedor($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE subproveedor_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por mandante.
		 *
		 * @param int $value ID del mandante.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByMandante($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE mandante = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por estado.
		 *
		 * @param string $value Estado del registro.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByEstado($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE estado = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por verificación.
		 *
		 * @param string $value Verificación del registro.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByVerifica($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE verifica = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por filtro de país.
		 *
		 * @param string $value Filtro de país del registro.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByFiltroPais($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE filtro_pais = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por fecha de creación.
		 *
		 * @param string $value Fecha de creación del registro.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByFechaCrea($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE fecha_crea = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por fecha de modificación.
		 *
		 * @param string $value Fecha de modificación del registro.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByFechaModif($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE fecha_modif = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por ID de usuario creador.
		 *
		 * @param int $value ID del usuario creador.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByUsucreaId($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE usucrea_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por ID de usuario modificador.
		 *
		 * @param int $value ID del usuario modificador.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByUsumodifId($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE usumodif_id = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->setNumber($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por valor máximo.
		 *
		 * @param string $value Valor máximo del registro.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByMax($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE max = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por valor mínimo.
		 *
		 * @param string $value Valor mínimo del registro.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByMin($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE min = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Elimina registros por detalle.
		 *
		 * @param string $value Detalle del registro.
		 * @return int Número de filas afectadas.
		 */
		public function deleteByDetalle($value) {
			$sql = 'DELETE FROM subproveedor_mandante_pais WHERE detalle = ?';
			$sqlQuery = new SqlQuery($sql);
			$sqlQuery->set($value);
			return $this->executeUpdate($sqlQuery);
		}

		/**
		 * Lee una fila de resultados y la convierte en un objeto SubproveedorMandantePais.
		 *
		 * @param array $row Fila de resultados de la consulta.
		 * @return SubproveedorMandantePais Objeto con los datos de la fila.
		 */
		protected function readRow($row) {
			$SubproveedorMandantePais = new SubproveedorMandantePais();

			$SubproveedorMandantePais->setProvmandanteId($row['provmandante_id']);
			$SubproveedorMandantePais->setMandante($row['mandante']);
			$SubproveedorMandantePais->setSubproveedorId($row['subproveedor_id']);
			$SubproveedorMandantePais->setEstado($row['estado']);
			$SubproveedorMandantePais->setDetalle($row['detalle']);
			$SubproveedorMandantePais->setUsucreaId($row['usucrea_id']);
			$SubproveedorMandantePais->setFechaCrea($row['fecha_crea']);
			$SubproveedorMandantePais->setUsumodifId($row['usumodif_id']);
			$SubproveedorMandantePais->setFechaModif($row['fecha_modif']);
			$SubproveedorMandantePais->setVerifica($row['verifica']);
			$SubproveedorMandantePais->setFiltroPais($row['filtro_pais']);
			$SubproveedorMandantePais->setMax($row['max']);
			$SubproveedorMandantePais->setMin($row['min']);
			$SubproveedorMandantePais->setOrden($row['orden']);
			$SubproveedorMandantePais->setPaisId($row['pais_id']);
			$SubproveedorMandantePais->setImage($row['imagen']);
			$SubproveedorMandantePais->setCredentials($row['credentials']);
			$SubproveedorMandantePais->setBonusSystem($row['bonus_system']); /*proposito de la funcion retornar el campo de la Base de datos*/
			$SubproveedorMandantePais->setUsuariosPrueba($row['usuarios_prueba']);

			return $SubproveedorMandantePais;
		}

/**
		 * Obtiene una lista de resultados de la consulta SQL.
		 *
		 * @param SqlQuery $sqlQuery Consulta SQL a ejecutar.
		 * @return array Lista de resultados.
		 */
		protected function getList($sqlQuery) {
			$tab = QueryExecutor::execute($this->transaction, $sqlQuery);
			$ret = array();
			for ($i = 0; $i < oldCount($tab); $i++) {
				$ret[$i] = $this->readRow($tab[$i]);
			}
			return $ret;
		}

		/**
		 * Obtiene una fila de resultados de la consulta SQL.
		 *
		 * @param SqlQuery $sqlQuery Consulta SQL a ejecutar.
		 * @return mixed Fila de resultados o null si no hay resultados.
		 */
		protected function getRow($sqlQuery) {
			$tab = QueryExecutor::execute($this->transaction, $sqlQuery);

			if (oldCount($tab) == 0) {
				return null;
			}
			return $this->readRow($tab[0]);
		}

		/**
		 * Ejecuta una consulta SQL.
		 *
		 * @param SqlQuery $sqlQuery Consulta SQL a ejecutar.
		 * @return mixed Resultado de la ejecución.
		 */
		protected function execute($sqlQuery) {
			return QueryExecutor::execute($this->transaction, $sqlQuery);
		}

		/**
		 * Ejecuta una consulta SQL (método alternativo).
		 *
		 * @param SqlQuery $sqlQuery Consulta SQL a ejecutar.
		 * @return mixed Resultado de la ejecución.
		 */
		protected function execute2($sqlQuery) {
			return QueryExecutor::execute2($this->transaction, $sqlQuery);
		}

		/**
		 * Ejecuta una actualización SQL.
		 *
		 * @param SqlQuery $sqlQuery Consulta SQL de actualización a ejecutar.
		 * @return int Número de filas afectadas.
		 */
		protected function executeUpdate($sqlQuery) {
			return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
		}

		/**
		 * Ejecuta una consulta SQL y obtiene un único resultado.
		 *
		 * @param SqlQuery $sqlQuery Consulta SQL a ejecutar.
		 * @return string Resultado de la consulta.
		 */
		protected function querySingleResult($sqlQuery) {
			return QueryExecutor::queryForString($this->transaction, $sqlQuery);
		}

		/**
		 * Ejecuta una inserción SQL.
		 *
		 * @param SqlQuery $sqlQuery Consulta SQL de inserción a ejecutar.
		 * @return int ID del registro insertado.
		 */
		protected function executeInsert($sqlQuery) {
			return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
		}

		/**
		 * Actualiza las credenciales de un subproveedor mandante país.
		 *
		 * @param SubproveedorMandantePais $subProveedorMandantePais Objeto con las credenciales a actualizar.
		 * @return int Número de filas afectadas.
		 * @throws \Throwable Si ocurre un error durante la actualización.
		 */
		public function updateCredenials($subProveedorMandantePais) {
			try {
				$sql = 'UPDATE subproveedor_mandante_pais SET credentials = ? WHERE provmandante_id = ?';
				$sqlQuery = new SqlQuery($sql);

				$sqlQuery->set($subProveedorMandantePais->getCredentials());
				$sqlQuery->setNumber($subProveedorMandantePais->getProvmandanteId());
				return $this->executeUpdate($sqlQuery);
			} catch (\Throwable $th) {
				throw $th;
			}
		}

		/**
		 * Consulta subproveedores mandante país con credenciales personalizadas.
		 *
		 * @param string $select Campos a seleccionar en la consulta.
		 * @param string $sidx Índice de ordenación.
		 * @param string $sord Orden de clasificación (ASC o DESC).
		 * @param int $start Inicio del límite de la consulta.
		 * @param int $limit Límite de registros a devolver.
		 * @param string $filters Filtros en formato JSON.
		 * @param bool $searchOn Indica si la búsqueda está activada.
		 * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
		 */
		public function querySubproveedoresMandantePaisCustomCredentials($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {

			$where = " where 1=1 ";

			if ($searchOn) {
				$filters = json_decode($filters);
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				$cont = 0;

				foreach ($rules as $rule) {
					$fieldName = $rule->field;
					$fieldData = $rule->data;
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
						$where = $where . " " . $groupOperation . " " . $fieldName . " " . strtoupper($fieldOperation);
					} else {
						$where = "";
					}
				}

			}

			$sql = 'SELECT count(*) count FROM subproveedor_mandante_pais INNER JOIN subproveedor ON (subproveedor_mandante_pais.subproveedor_id = subproveedor.subproveedor_id) INNER JOIN subproveedor_mandante ON (subproveedor_mandante_pais.subproveedor_id = subproveedor_mandante.subproveedor_id and subproveedor_mandante_pais.mandante = subproveedor_mandante.mandante) INNER JOIN proveedor ON (proveedor.proveedor_id = subproveedor.proveedor_id) INNER JOIN mandante ON (mandante.mandante = subproveedor_mandante_pais.mandante) INNER JOIN pais ON (subproveedor_mandante_pais.pais_id = pais.pais_id) ' . $where;

			$sqlQuery = new SqlQuery($sql);

			$count = $this->execute2($sqlQuery);

			$sql = 'SELECT ' .$select .'  FROM subproveedor_mandante_pais INNER JOIN subproveedor ON (subproveedor_mandante_pais.subproveedor_id = subproveedor.subproveedor_id) INNER JOIN subproveedor_mandante ON (subproveedor_mandante_pais.subproveedor_id = subproveedor_mandante.subproveedor_id and subproveedor_mandante_pais.mandante = subproveedor_mandante.mandante) INNER JOIN proveedor ON (proveedor.proveedor_id = subproveedor.proveedor_id) INNER JOIN mandante ON (mandante.mandante = subproveedor_mandante_pais.mandante) INNER JOIN pais ON (subproveedor_mandante_pais.pais_id = pais.pais_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
			$sqlQuery = new SqlQuery($sql);


			$result = $this->execute2($sqlQuery);

			$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

			return $json;
		}
	}
?>
