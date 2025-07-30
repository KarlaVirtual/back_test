<?php namespace Backend\mysql;
use Backend\dao\EgresoDAO;
use Backend\dto\Egreso;
use Backend\dto\Helpers;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/**
* Clase 'EgresoMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'Egreso'
*
* Ejemplo de uso:
* $EgresoMySqlDAO = new EgresoMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class EgresoMySqlDAO implements EgresoDAO{


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
     * @param String $descarga_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM egreso WHERE egreso_id = ?';
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
		$sql = 'SELECT * FROM egreso';
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
		$sql = 'SELECT * FROM egreso ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $egreso_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($egreso_id){
		$sql = 'DELETE FROM egreso WHERE egreso_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($egreso_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object egreso egreso
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($egreso){
        $sqlFechaA ='';
        $sqlFecha ='';

        if($egreso->fechaCrea != ''){
            $sqlFechaA =',fecha_crea';
            $sqlFecha = ', ?';
        }
		$sql = 'INSERT INTO egreso (tipo_id, descripcion, estado,usucrea_id,usumodif_id,centrocosto_id,documento,valor,retraccion,impuesto,concepto_id,usuario_id,productoterc_id,proveedorterc_id,usucajero_id,tipo_documento,serie'.$sqlFechaA.',consecutivo) SELECT ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?'.$sqlFecha.',  CASE WHEN max(consecutivo)+1 IS NULL THEN 1 ELSE max(consecutivo)+1 END FROM egreso WHERE usuario_id='.$egreso->usuarioId;
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($egreso->tipoId);
		$sqlQuery->set($egreso->descripcion);
		$sqlQuery->set($egreso->estado);
        $sqlQuery->set($egreso->usucreaId);
        $sqlQuery->set($egreso->usumodifId);

        $sqlQuery->set($egreso->centrocostoId);
        $sqlQuery->set($egreso->documento);
        $sqlQuery->set($egreso->valor);
        $sqlQuery->set($egreso->retraccion);
        $sqlQuery->set($egreso->impuesto);
        $sqlQuery->set($egreso->conceptoId);
        $sqlQuery->set($egreso->usuarioId);
        $sqlQuery->set($egreso->productotercId);
        $sqlQuery->set($egreso->proveedortercId);
        $sqlQuery->set($egreso->usucajeroId);
        if($egreso->tipoDocumento == ''){
            $egreso->tipoDocumento=0;
        }
        $sqlQuery->set($egreso->tipoDocumento);
        $sqlQuery->set($egreso->serie);
        if($egreso->fechaCrea != ''){
            $sqlQuery->set($egreso->fechaCrea);
        }

		$id = $this->executeInsert($sqlQuery);	
		$egreso->egresoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object egreso egreso
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($egreso){
		$sql = 'UPDATE egreso SET tipo_id = ?, descripcion = ?, estado = ?,usucrea_id = ?,usumodif_id = ?,centrocosto_id = ?,documento = ?,valor = ?,retraccion = ?,impuesto=0,concepto_id = ?,usuario_id = ?,productoterc_id = ?,proveedorterc_id = ?,usucajero_id = ?,tipo_documento = ?,serie = ? WHERE egreso_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($egreso->tipoId);
		$sqlQuery->set($egreso->descripcion);
		$sqlQuery->set($egreso->estado);
        $sqlQuery->set($egreso->usucreaId);
        $sqlQuery->set($egreso->usumodifId);


        $sqlQuery->set($egreso->centrocostoId);
        $sqlQuery->set($egreso->documento);
        $sqlQuery->set($egreso->valor);
        $sqlQuery->set($egreso->retraccion);
        $sqlQuery->set($egreso->impuesto);
        $sqlQuery->set($egreso->conceptoId);

        $sqlQuery->set($egreso->usuarioId);
        $sqlQuery->set($egreso->productotercId);
        $sqlQuery->set($egreso->proveedortercId);
        $sqlQuery->set($egreso->usucajeroId);
        $sqlQuery->set($egreso->tipoDocumento);
        $sqlQuery->set($egreso->serie);

        $sqlQuery->set($egreso->egresoId);
		return $this->executeUpdate($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de egresos 'Egreso'
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
    * @return Array resultado de la consulta
    *
    */
    public function queryEgresoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
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
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        $sql = 'SELECT count(*) count FROM egreso LEFT OUTER JOIN proveedor_tercero ON (proveedor_tercero.proveedorterc_id = egreso.proveedorterc_id) LEFT OUTER JOIN producto_tercero ON (producto_tercero.productoterc_id = egreso.productoterc_id)  LEFT OUTER JOIN cuenta_contable as cuenta_producto ON (producto_tercero.cuentacontable_id = cuenta_producto.cuentacontable_id)  LEFT OUTER JOIN cuenta_contable as cuenta_producto_egreso ON (producto_tercero.cuentacontableegreso_id = cuenta_producto_egreso.cuentacontable_id) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = egreso.tipo_id)  LEFT OUTER JOIN concepto ON (egreso.concepto_id = concepto.concepto_id)  LEFT OUTER JOIN cuenta_contable as cuenta_concepto ON (concepto.cuentacontable_id = cuenta_concepto.cuentacontable_id)  LEFT OUTER JOIN usuario as usuario_punto ON (usuario_punto.usuario_id = egreso.usuario_id)   LEFT OUTER JOIN concesionario ON (usuario_punto.usuario_id = concesionario.usuhijo_id and prodinterno_id=0  AND concesionario.estado=\'A\')   LEFT OUTER JOIN usuario as usuario_cajero ON (usuario_cajero.usuario_id = egreso.usucajero_id)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM egreso LEFT OUTER JOIN proveedor_tercero ON (proveedor_tercero.proveedorterc_id = egreso.proveedorterc_id)   LEFT OUTER JOIN producto_tercero ON (producto_tercero.productoterc_id = egreso.productoterc_id)  LEFT OUTER JOIN cuenta_contable as cuenta_producto ON (producto_tercero.cuentacontable_id = cuenta_producto.cuentacontable_id)  LEFT OUTER JOIN cuenta_contable as cuenta_producto_egreso ON (producto_tercero.cuentacontableegreso_id = cuenta_producto_egreso.cuentacontable_id) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = egreso.tipo_id)  LEFT OUTER JOIN clasificador documento ON (documento.clasificador_id = egreso.tipo_documento) LEFT OUTER JOIN concepto ON (egreso.concepto_id = concepto.concepto_id)  LEFT OUTER JOIN cuenta_contable as cuenta_concepto ON (concepto.cuentacontable_id = cuenta_concepto.cuentacontable_id)  LEFT OUTER JOIN usuario as usuario_punto ON (usuario_punto.usuario_id = egreso.usuario_id)    LEFT OUTER JOIN concesionario ON (usuario_punto.usuario_id = concesionario.usuhijo_id and prodinterno_id=0  AND concesionario.estado=\'A\')  LEFT OUTER JOIN usuario as usuario_cajero ON (usuario_cajero.usuario_id = egreso.usucajero_id)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
		$sql = 'DELETE FROM egreso';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipoId sea igual al valor pasado como parámetro
     *
     * @param String $value tipoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM egreso WHERE tipoId = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDescripcion($value){
		$sql = 'SELECT * FROM egreso WHERE descripcion = ?';
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
		$sql = 'SELECT * FROM egreso WHERE estado = ?';
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
		$sql = 'SELECT * FROM egreso WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna codigo sea igual al valor pasado como parámetro
     *
     * @param String $value codigo requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByAbreviado($value){
        $sql = 'SELECT * FROM egreso WHERE codigo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipoId sea igual al valor pasado como parámetro
     *
     * @param String $value tipoId requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTipo($value){
		$sql = 'DELETE FROM egreso WHERE tipoId = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM egreso WHERE descripcion = ?';
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
		$sql = 'DELETE FROM egreso WHERE estado = ?';
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
		$sql = 'DELETE FROM egreso WHERE mandante = ?';
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
     * @return Object $Egreso Egreso
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$egreso = new Egreso();
		
		$egreso->egresoId = $row['egreso_id'];
		$egreso->tipoId = $row['tipo_id'];
		$egreso->descripcion = $row['descripcion'];
		$egreso->estado = $row['estado'];
        $egreso->usucreaId = $row['usucrea_id'];
        $egreso->usumodifId = $row['usumodif_id'];
        $egreso->usuarioId = $row['usuario_id'];

        $egreso->centrocostoId = $row['centrocosto_id'];
        $egreso->documento = $row['documento'];
        $egreso->valor = $row['valor'];
        $egreso->retraccion = $row['retraccion'];
        $egreso->impuesto = $row['impuesto'];
        $egreso->conceptoId = $row['concepto_id'];
        $egreso->productotercId = $row['productoterc_id'];
        $egreso->proveedortercId = $row['proveedorterc_id'];
        $egreso->usucajeroId = $row['usucajero_id'];
        $egreso->consecutivo = $row['consecutivo'];
        $egreso->fechaCrea = $row['fecha_crea'];
        $egreso->tipoDocumento = $row['tipo_documento'];
        $egreso->serie = $row['serie'];

        return $egreso;
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