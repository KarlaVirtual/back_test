<?php namespace Backend\mysql;
use Backend\dao\BonoInternoDAO;
use Backend\dto\BonoInterno;
use Backend\dto\Helpers;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'BonoInternoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'BonoInterno'
* 
* Ejemplo de uso: 
* $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class BonoInternoMySqlDAO implements BonoInternoDAO{


    /**
    * Atributo Transaction transacción
    *
    * @var Objeto
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
        if ($transaction == "") {

            $transaction = new Transaction();
            $this->transaction = $transaction;

        } else {
            $this->transaction = $transaction;
        }
    }










    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM bono_interno WHERE bono_id = ?';
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
		$sql = 'SELECT * FROM bono_interno';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Ejecutar una consulta sql
     * 
     *
     * @param String $sqls consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    public function querySQL($sql)
    {
        $Helpers = new Helpers();
        $sqlQuery = new SqlQuery($sql);
        return $Helpers->process_data($this->execute2($sqlQuery));

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
		$sql = 'SELECT * FROM bono_interno ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $bono_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($bono_id){
		$sql = 'DELETE FROM bono_interno WHERE bono_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($bono_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto BonoInterno bonoInterno
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($BonoInterno){

		$sql = 'INSERT INTO bono_interno (fecha_inicio, fecha_fin, descripcion,nombre, tipo, estado, mandante, usucrea_id, usumodif_id,condicional, orden,cupo_actual,cupo_maximo,cantidad_bonos,maximo_bonos,imagen,reglas,publico,codigo, permite_bono,pertenece_crm,categoria_campaña,detalle_campaña, tipo_accion,json_temp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?)';


		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($BonoInterno->fechaInicio);
		$sqlQuery->set($BonoInterno->fechaFin);
        $sqlQuery->set($BonoInterno->descripcion);
        $sqlQuery->set($BonoInterno->nombre);
		$sqlQuery->set($BonoInterno->tipo);
		$sqlQuery->set($BonoInterno->estado);
		$sqlQuery->setNumber($BonoInterno->mandante);
		$sqlQuery->setNumber($BonoInterno->usucreaId);
        $sqlQuery->setNumber($BonoInterno->usumodifId);
        $sqlQuery->set($BonoInterno->condicional);
        $sqlQuery->set($BonoInterno->orden);
        $sqlQuery->set($BonoInterno->cupoActual);
        $sqlQuery->set($BonoInterno->cupoMaximo);
        $sqlQuery->set($BonoInterno->cantidadBonos);
        $sqlQuery->set($BonoInterno->maximoBonos);

        $sqlQuery->set($BonoInterno->imagen);
        $sqlQuery->set($BonoInterno->reglas);

        $sqlQuery->set($BonoInterno->publico);
        $sqlQuery->set($BonoInterno->codigo);


        if($BonoInterno->permiteBono!="" ||$BonoInterno->permiteBono!= null){

            $sqlQuery->setNumber($BonoInterno->permiteBono);

        }else{
            $sqlQuery->set(0);

        }

        if ($BonoInterno->perteneceCrm == "") {
            $BonoInterno->perteneceCrm = 'N';

        }
        $sqlQuery->set($BonoInterno->perteneceCrm);


        if($BonoInterno->categoriaCampaña == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($BonoInterno->categoriaCampaña);
        }

        if($BonoInterno->detallesCampaña == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($BonoInterno->detallesCampaña);
        }


        if($BonoInterno->tipoAccion == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($BonoInterno->tipoAccion);
        }

        $sqlQuery->set($BonoInterno->jsonTemp);

        $id = $this->executeInsert($sqlQuery);
		$BonoInterno->bonoId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto BonoInterno bonoInterno
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($BonoInterno){
		$sql = 'UPDATE bono_interno SET fecha_inicio = ?, fecha_fin = ?, descripcion = ?,nombre=?, tipo = ?, estado = ?,  mandante = ?, usucrea_id = ?, usumodif_id = ?, condicional = ?,orden = ?, cupo_actual = ?, cupo_maximo = ?, cantidad_bonos = ?, maximo_bonos = ?, imagen = ?, reglas = ?, publico = ?, codigo = ?, permite_bono=?, pertenece_crm= ?,  categoria_campaña = ?,detalle_campaña = ?, tipo_accion= ?,json_temp = ? WHERE bono_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($BonoInterno->fechaInicio);
		$sqlQuery->set($BonoInterno->fechaFin);
        $sqlQuery->set($BonoInterno->descripcion);
        $sqlQuery->set($BonoInterno->nombre);
		$sqlQuery->set($BonoInterno->tipo);
		$sqlQuery->set($BonoInterno->estado);
		$sqlQuery->set($BonoInterno->mandante);
		$sqlQuery->setNumber($BonoInterno->usucreaId);
        $sqlQuery->setNumber($BonoInterno->usumodifId);
        $sqlQuery->set($BonoInterno->condicional);
        $sqlQuery->set($BonoInterno->orden);
        $sqlQuery->set($BonoInterno->cupoActual);
        $sqlQuery->set($BonoInterno->cupoMaximo);
        $sqlQuery->set($BonoInterno->cantidadBonos);
        $sqlQuery->set($BonoInterno->maximoBonos);

        $sqlQuery->set($BonoInterno->imagen);
        $sqlQuery->set($BonoInterno->reglas);

        $sqlQuery->set($BonoInterno->publico);
        $sqlQuery->set($BonoInterno->codigo);


        if($BonoInterno->permiteBono == "" || $BonoInterno->permiteBono == null){

            $BonoInterno->permiteBono = 0;

        }
        $sqlQuery->setNumber($BonoInterno->permiteBono);

        if ($BonoInterno->perteneceCrm == "") {
            $BonoInterno->perteneceCrm = 'N';

        }
        $sqlQuery->set($BonoInterno->perteneceCrm);


        if($BonoInterno->categoriaCampaña == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($BonoInterno->categoriaCampaña);
        }


        if($BonoInterno->detallesCampaña == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($BonoInterno->detallesCampaña);
        }

        if($BonoInterno->tipoAccion == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($BonoInterno->tipoAccion);
        }

        $sqlQuery->set($BonoInterno->jsonTemp);

		$sqlQuery->setNumber($BonoInterno->bonoId);

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
		$sql = 'DELETE FROM bono_interno';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value UsuarioId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM bono_interno WHERE fecha_inicio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ProductoId sea igual al valor pasado como parámetro
     *
     * @param String $value ProductoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByProductoId($value){
		$sql = 'SELECT * FROM bono_interno WHERE fecha_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ValorTicket sea igual al valor pasado como parámetro
     *
     * @param String $value ValorTicket requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorTicket($value){
		$sql = 'SELECT * FROM bono_interno WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ValorPremio sea igual al valor pasado como parámetro
     *
     * @param String $value ValorPremio requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorPremio($value){
		$sql = 'SELECT * FROM bono_interno WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Estado sea igual al valor pasado como parámetro
     *
     * @param String $value Estado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM bono_interno WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna TransaccionId sea igual al valor pasado como parámetro
     *
     * @param String $value TransaccionId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTransaccionId($value){
		$sql = 'SELECT * FROM bono_interno WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaPago sea igual al valor pasado como parámetro
     *
     * @param String $value FechaPago requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaPago($value){
		$sql = 'SELECT * FROM bono_interno WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Mandante sea igual al valor pasado como parámetro
     *
     * @param String $value Mandante requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM bono_interno WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsucreaId sea igual al valor pasado como parámetro
     *
     * @param String $value UsucreaId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM bono_interno WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsumodifId sea igual al valor pasado como parámetro
     *
     * @param String $value UsumodifId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM bono_interno WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de bonos 'Bonos'
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
	public function queryBonosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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



		  $sql = "SELECT count(*) count FROM bono_interno INNER JOIN mandante ON (bono_interno.mandante = mandante.mandante) " . $where;


		  $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM bono_interno INNER JOIN mandante ON (bono_interno.mandante = mandante.mandante)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
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
    public function queryUpdate($sql,$insert=""){

        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery,$insert);
	
	}






    /**
     * Crear y devolver un objeto del tipo BonoInterno
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $BonoInterno BonoInterno
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$BonoInterno = new BonoInterno();

		$BonoInterno->bonoId = $row['bono_id'];
		$BonoInterno->fechaInicio = $row['fecha_inicio'];
		$BonoInterno->fechaFin = $row['fecha_fin'];
        $BonoInterno->descripcion = $row['descripcion'];
        $BonoInterno->nombre = $row['nombre'];
		$BonoInterno->tipo = $row['tipo'];
		$BonoInterno->estado = $row['estado'];
		$BonoInterno->mandante = $row['mandante'];
		$BonoInterno->usucreaId = $row['usucrea_id'];
		$BonoInterno->fechaCrea = $row['fecha_crea'];
		$BonoInterno->usumodifId = $row['usumodif_id'];
        $BonoInterno->fechaModif = $row['fecha_modif'];
        $BonoInterno->orden = $row['orden'];
        $BonoInterno->condicional = $row['condicional'];
        $BonoInterno->cupoActual = $row['cupo_actual'];
        $BonoInterno->cupoMaximo = $row['cupo_maximo'];
        $BonoInterno->cantidadBonos = $row['cantidad_bonos'];
        $BonoInterno->maximoBonos = $row['maximo_bonos'];
        $BonoInterno->imagen = $row['imagen'];
        $BonoInterno->reglas = $row['reglas'];
        $BonoInterno->codigo = $row['codigo'];

        $BonoInterno->publico = $row['publico'];
        $BonoInterno->permiteBono = $row['permite_bono'];
        $BonoInterno->perteneceCrm = $row['pertenece_crm'];
        $BonoInterno->categoriaCampaña = $row['categoria_campaña'];
        $BonoInterno->detallesCampaña = $row['detalle_campaña'];
        $BonoInterno->tipoAccion = $row['tipo_accion'];
        $BonoInterno->jsonTemp = $row['json_temp'];

		return $BonoInterno;
	}

    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo 
     * 
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo asociativo
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
	protected function executeUpdate($sqlQuery,$insert=""){
		return QueryExecutor::executeUpdate($this->transaction,$sqlQuery,$insert);
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
    function querySingleResult($sqlQuery){
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