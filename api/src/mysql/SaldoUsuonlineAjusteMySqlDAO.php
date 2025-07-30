<?php namespace Backend\mysql;
use Backend\dao\SaldoUsuonlineAjusteDAO;
use Backend\dto\Helpers;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/**
* Clase 'SaldoUsuonlineAjusteMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'SaldoUsuonlineAjuste'
*
* Ejemplo de uso:
* $SaldoUsuonlineAjusteMySqlDAO = new SaldoUsuonlineAjusteMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class SaldoUsuonlineAjusteMySqlDAO implements SaldoUsuonlineAjusteDAO{


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
        $sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE ajuste_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

	/**
	 * Obtener todos los registros de la base datos
	 *
	 * @return Array $ resultado de la consulta
     *
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM saldo_usuonline_ajuste';
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
		$sql = 'SELECT * FROM saldo_usuonline_ajuste ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $ajuste_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($ajuste_id){
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE ajuste_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($ajuste_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto saldoUsuonlineAjuste saldoUsuonlineAjuste
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($saldoUsuonlineAjuste){
		$sql = 'INSERT INTO saldo_usuonline_ajuste (tipo_id, usuario_id, valor, fecha_crea, usucrea_id, saldo_ant, observ, dir_ip, mandante, motivo_id, tipo_saldo,tipo,proveedor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($saldoUsuonlineAjuste->tipoId);
		$sqlQuery->set($saldoUsuonlineAjuste->usuarioId);
		$sqlQuery->set($saldoUsuonlineAjuste->valor);
		$sqlQuery->set($saldoUsuonlineAjuste->fechaCrea);
		$sqlQuery->set($saldoUsuonlineAjuste->usucreaId);
        if($saldoUsuonlineAjuste->saldoAnt ==''){
            $saldoUsuonlineAjuste->saldoAnt='0';
        }
		$sqlQuery->set($saldoUsuonlineAjuste->saldoAnt);
		$sqlQuery->set($saldoUsuonlineAjuste->observ);

        if(strlen($saldoUsuonlineAjuste->dirIp) > 20){
            $saldoUsuonlineAjuste->dirIp = substr($saldoUsuonlineAjuste->dirIp, 0, 19);
        }

		$sqlQuery->set($saldoUsuonlineAjuste->dirIp);
		$sqlQuery->set($saldoUsuonlineAjuste->mandante);
        $sqlQuery->set($saldoUsuonlineAjuste->motivoId);

        if($saldoUsuonlineAjuste->tipoSaldo == ""){
            $saldoUsuonlineAjuste->tipoSaldo=0;
        }

        $sqlQuery->set($saldoUsuonlineAjuste->tipoSaldo);

        $sqlQuery->set($saldoUsuonlineAjuste->tipo);

        if($saldoUsuonlineAjuste->proveedorId == ""){
            $saldoUsuonlineAjuste->proveedorId=0;
        }

        $sqlQuery->set($saldoUsuonlineAjuste->proveedorId);

		$id = $this->executeInsert($sqlQuery);
		$saldoUsuonlineAjuste->ajusteId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto saldoUsuonlineAjuste saldoUsuonlineAjuste
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($saldoUsuonlineAjuste){
		$sql = 'UPDATE saldo_usuonline_ajuste SET tipo_id = ?, usuario_id = ?, valor = ?, fecha_crea = ?, usucrea_id = ?, saldo_ant = ?, observ = ?, dir_ip = ?, mandante = ?, motivo_id = ?, tipo_saldo = ?, tipo = ?,proveedor_id = ? WHERE ajuste_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($saldoUsuonlineAjuste->tipoId);
		$sqlQuery->set($saldoUsuonlineAjuste->usuarioId);
		$sqlQuery->set($saldoUsuonlineAjuste->valor);
		$sqlQuery->set($saldoUsuonlineAjuste->fechaCrea);
		$sqlQuery->set($saldoUsuonlineAjuste->usucreaId);
		$sqlQuery->set($saldoUsuonlineAjuste->saldoAnt);
		$sqlQuery->set($saldoUsuonlineAjuste->observ);
		$sqlQuery->set($saldoUsuonlineAjuste->dirIp);
		$sqlQuery->set($saldoUsuonlineAjuste->mandante);
		$sqlQuery->set($saldoUsuonlineAjuste->motivoId);

        if($saldoUsuonlineAjuste->tipoSaldo == ""){
            $saldoUsuonlineAjuste->tipoSaldo=0;
        }


        $sqlQuery->set($saldoUsuonlineAjuste->tipoSaldo);

        $sqlQuery->set($saldoUsuonlineAjuste->tipo);

        if($saldoUsuonlineAjuste->proveedorId == ""){
            $saldoUsuonlineAjuste->proveedorId=0;
        }

        $sqlQuery->set($saldoUsuonlineAjuste->proveedorId);


        $sqlQuery->set($saldoUsuonlineAjuste->ajusteId);
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
		$sql = 'DELETE FROM saldo_usuonline_ajuste';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}









	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna tipo_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTipoId($value){
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE tipo_id = ?';
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}



	/**
	 * Consulta personalizada de ajustes de saldo de usuarios en línea.
	 *
	 * @param string $select Campos a seleccionar en la consulta.
	 * @param string $sidx Campo por el cual se ordenará la consulta.
	 * @param string $sord Orden de la consulta (ASC o DESC).
	 * @param int $start Índice de inicio para la paginación.
	 * @param int $limit Límite de registros a devolver.
	 * @param string $filters Filtros en formato JSON para aplicar en la consulta.
	 * @param bool $searchOn Indica si se deben aplicar los filtros.
	 * 
	 * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
	 */
    public function querySaldoUsuonlineAjustesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM saldo_usuonline_ajuste LEFT OUTER JOIN usuario ON (usuario.usuario_id=saldo_usuonline_ajuste.usuario_id)   LEFT OUTER JOIN usuario usuario_crea ON (usuario_crea.usuario_id=saldo_usuonline_ajuste.usucrea_id) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=saldo_usuonline_ajuste.tipo)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM saldo_usuonline_ajuste LEFT OUTER JOIN usuario ON (usuario.usuario_id=saldo_usuonline_ajuste.usuario_id)    LEFT OUTER JOIN usuario usuario_crea ON (usuario_crea.usuario_id=saldo_usuonline_ajuste.usucrea_id) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=saldo_usuonline_ajuste.tipo)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * las columnas tipo_id y usuario_id sean iguales a los
 	 * valores pasados como parámetros
 	 *
 	 * @param String $value tipo_id
 	 * @param String $usuarioId usuario_id
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
    public function queryByTipoIdAndUsuarioId($value,$usuarioId){
        $sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE tipo_id = ? AND usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($usuarioId);
        return $this->getList($sqlQuery);
    }

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValor($value){
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE valor = ?';
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
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna saldo_ant sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value saldo_ant requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryBySaldoAnt($value){
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE saldo_ant = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna observ sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value observ requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByObserv($value){
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE observ = ?';
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
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE dir_ip = ?';
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
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna motivo_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value motivo_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByMotivoId($value){
		$sql = 'SELECT * FROM saldo_usuonline_ajuste WHERE motivo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}









	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna tipo_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTipoId($value){
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE tipo_id = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByUsuarioId($value){
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE usuario_id = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByValor($value){
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE valor = ?';
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
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna saldo_ant sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value saldo_ant requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteBySaldoAnt($value){
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE saldo_ant = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna observ sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value observ requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByObserv($value){
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE observ = ?';
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
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE dir_ip = ?';
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
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna motivo_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value motivo_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByMotivoId($value){
		$sql = 'DELETE FROM saldo_usuonline_ajuste WHERE motivo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	





	/**
 	 * Crear y devolver un objeto del tipo SaldoUsuonlineAjuste
 	 * con los valores de una consulta sql
 	 *
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $saldoUsuonlineAjuste SaldoUsuonlineAjuste
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$saldoUsuonlineAjuste = new SaldoUsuonlineAjuste();
		
		$saldoUsuonlineAjuste->ajusteId = $row['ajuste_id'];
		$saldoUsuonlineAjuste->tipoId = $row['tipo_id'];
		$saldoUsuonlineAjuste->usuarioId = $row['usuario_id'];
		$saldoUsuonlineAjuste->valor = $row['valor'];
		$saldoUsuonlineAjuste->fechaCrea = $row['fecha_crea'];
		$saldoUsuonlineAjuste->usucreaId = $row['usucrea_id'];
		$saldoUsuonlineAjuste->saldoAnt = $row['saldo_ant'];
		$saldoUsuonlineAjuste->observ = $row['observ'];
		$saldoUsuonlineAjuste->dirIp = $row['dir_ip'];
		$saldoUsuonlineAjuste->mandante = $row['mandante'];
		$saldoUsuonlineAjuste->motivoId = $row['motivo_id'];
        $saldoUsuonlineAjuste->tipoSaldo = $row['tipo_saldo'];

        $saldoUsuonlineAjuste->tipo = $row['tipo'];
        $saldoUsuonlineAjuste->proveedorId = $row['proveedor_id'];


        return $saldoUsuonlineAjuste;
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