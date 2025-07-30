<?php namespace Backend\mysql;
use Backend\dao\DescargaDAO;
use Backend\dto\Descarga;
use Backend\dto\Helpers;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'DescargaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Descarga'
* 
* Ejemplo de uso: 
* $DescargaMySqlDAO = new DescargaMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class DescargaMySqlDAO implements DescargaDAO
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
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $descarga_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM descarga WHERE descarga_id = ?';
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
		$sql = 'SELECT * FROM descarga';
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
		$sql = 'SELECT * FROM descarga ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $descarga_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($descarga_id){
		$sql = 'DELETE FROM descarga WHERE descarga_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($descarga_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object descarga descarga
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($descarga){
		$sql = 'INSERT INTO descarga (descripcion, ruta,tipo,version,estado,plataforma,mandante,encriptacion_metodo,encriptacion_valor,external_id,pais_id,proveedor_id,json,perfil_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($descarga->descripcion);
        $sqlQuery->set($descarga->ruta);
        $sqlQuery->set($descarga->tipo);
        $sqlQuery->set($descarga->version);
        $sqlQuery->set($descarga->estado);
        $sqlQuery->set($descarga->plataforma);
        $sqlQuery->set($descarga->mandante);
        $sqlQuery->set($descarga->encriptacionMetodo);
        $sqlQuery->set($descarga->encriptacionValor);
        $sqlQuery->set($descarga->externalId);
        $sqlQuery->set($descarga->paisId);

        if($descarga->proveedorId == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($descarga->proveedorId);
        }
//        $sqlQuery->set($descarga->proveedorId);
        $sqlQuery->set($descarga->json);
        $sqlQuery->set($descarga->perfilId);

		$id = $this->executeInsert($sqlQuery);	
		$descarga->descargaId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object descarga descarga
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($descarga){

		$sql = 'UPDATE descarga SET  descripcion = ?, ruta = ?,tipo = ?,version = ?,estado = ?,plataforma = ?,mandante = ?, encriptacion_metodo = ?,encriptacion_valor = ?,external_id = ?,pais_id = ?,proveedor_id = ?,json = ?,perfil_id = ? WHERE descarga_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($descarga->descripcion);
		$sqlQuery->set($descarga->ruta);
        $sqlQuery->set($descarga->tipo);
        $sqlQuery->set($descarga->version);
        $sqlQuery->set($descarga->estado);
        $sqlQuery->set($descarga->plataforma);
        $sqlQuery->set($descarga->mandante);
        $sqlQuery->set($descarga->encriptacionMetodo);
        $sqlQuery->set($descarga->encriptacionValor);
        $sqlQuery->set($descarga->externalId);
        $sqlQuery->set($descarga->paisId);
        $sqlQuery->set($descarga->proveedorId);
        $sqlQuery->set($descarga->json);
        $sqlQuery->set($descarga->perfilId);
		$sqlQuery->set($descarga->descarga_id);

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
		$sql = 'DELETE FROM descarga';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'SELECT * FROM descarga WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ruta sea igual al valor pasado como parámetro
     *
     * @param String $value ruta requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRuta($value){
		$sql = 'SELECT * FROM descarga WHERE ruta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
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
    public function queryByTipo($value){
        $sql = 'SELECT * FROM descarga WHERE tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }






    /**
    * Realizar una consulta en la tabla de descargas 'Descarga'
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
    public function queryDescargasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $sql = "SELECT count(*) count FROM descarga INNER JOIN descarga_version ON (descarga.descarga_id = descarga_version.documento_id) INNER JOIN usuario ON (descarga_version.usuario_id = usuario.usuario_id)" . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = "SELECT " . $select . " FROM descarga INNER JOIN descarga_version ON (descarga.descarga_id = descarga_version.documento_id) INNER JOIN usuario ON (descarga_version.usuario_id = usuario.usuario_id)" . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Ejecuta una consulta SQL personalizada y devuelve el resultado en formato JSON.
     *
     * @param string $sql La consulta SQL a ejecutar.
     * @return string El resultado de la consulta en formato JSON.
     */
    public function querycustom2($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);
        $json = json_encode($result);
        return $json;
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
		$sql = 'DELETE FROM descarga WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ruta sea igual al valor pasado como parámetro
     *
     * @param String $value ruta requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRuta($value){
		$sql = 'DELETE FROM descarga WHERE ruta = ?';
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
     * @return Object $Descarga Descarga
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$descarga = new Descarga();
		
		$descarga->descargaId = $row['descarga_id'];
		$descarga->descripcion = $row['descripcion'];
        $descarga->ruta = $row['ruta'];
        $descarga->version = $row['version'];
        $descarga->estado = $row['estado'];
        $descarga->tipo = $row['tipo'];
        $descarga->mandante = $row['mandante'];
        $descarga->plataforma = $row['plataforma'];
        $descarga->encriptacionMetodo = $row['encriptacion_metodo'];
        $descarga->externalId = $row['external_id'];
        $descarga->paisId = $row['pais_id'];
        $descarga->proveedorId = $row['proveedor_id'];
        $descarga->json = $row['json'];
        $descarga->perfilId = $row['perfil_id'];

		return $descarga;
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