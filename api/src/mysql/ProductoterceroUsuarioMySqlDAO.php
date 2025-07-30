<?php namespace Backend\mysql;
use Backend\dao\ProductoterceroUsuarioDAO;
use Backend\dto\Helpers;
use Backend\dto\ProductoterceroUsuario;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'IntEventoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'IntEvento'
* 
* Ejemplo de uso: 
* $IntEventoMySqlDAO = new IntEventoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProductoterceroUsuarioMySqlDAO implements ProductoterceroUsuarioDAO
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
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM productotercero_usuario WHERE prodtercusuario_id = ?';
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
		$sql = 'SELECT * FROM productotercero_usuario';
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
		$sql = 'SELECT * FROM productotercero_usuario ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $prodtercusuario_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($prodtercusuario_id){
		$sql = 'DELETE FROM productotercero_usuario WHERE prodtercusuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($prodtercusuario_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object productotercero_usuario productotercero_usuario
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($productotercero_usuario){
		$sql = 'INSERT INTO productotercero_usuario (producto_id, usuario_id,estado,usucrea_id,usumodif_id,cuentacontable_id,cuentacontableegreso_id,cupo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($productotercero_usuario->productoId);
        $sqlQuery->set($productotercero_usuario->usuarioId);
        $sqlQuery->set($productotercero_usuario->estado);
        $sqlQuery->set($productotercero_usuario->usucreaId);
        $sqlQuery->set($productotercero_usuario->usumodifId);
        $sqlQuery->set($productotercero_usuario->cuentacontableId);
        $sqlQuery->set($productotercero_usuario->cuentacontableegresoId);
        $sqlQuery->set($productotercero_usuario->cupo);

		$id = $this->executeInsert($sqlQuery);	
		$productotercero_usuario->prodtercusuarioId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object productotercero_usuario productotercero_usuario
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($productotercero_usuario){
		$sql = 'UPDATE productotercero_usuario SET producto_id = ?, usuario_id = ?, estado = ?,usucrea_id = ?,usumodif_id = ?, cuentacontable_id = ?, cuentacontableegreso_id = ?, cupo = ? WHERE prodtercusuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($productotercero_usuario->productoId);
        $sqlQuery->set($productotercero_usuario->usuarioId);
        $sqlQuery->set($productotercero_usuario->estado);
        $sqlQuery->set($productotercero_usuario->usucreaId);
        $sqlQuery->set($productotercero_usuario->usumodifId);
        $sqlQuery->set($productotercero_usuario->cuentacontableId);
        $sqlQuery->set($productotercero_usuario->cuentacontableegresoId);
        $sqlQuery->setSIN($productotercero_usuario->cupo);

		$sqlQuery->set($productotercero_usuario->prodtercusuarioId);
		return $this->executeUpdate($sqlQuery);
	}



    /**
    * Realizar una consulta en la tabla de ProductoterceroUsuario 'ProductoterceroUsuario'
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
    * @return Array resultado de la consulta
    *
    */
    public function queryProductoterceroUsuarioesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM productotercero_usuario INNER JOIN producto_tercero ON (producto_tercero.productoterc_id =productotercero_usuario.producto_id) INNER JOIN usuario ON (usuario.usuario_id =productotercero_usuario.usuario_id) ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM productotercero_usuario INNER JOIN producto_tercero ON (producto_tercero.productoterc_id =productotercero_usuario.producto_id) INNER JOIN usuario ON (usuario.usuario_id =productotercero_usuario.usuario_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


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
		$sql = 'DELETE FROM productotercero_usuario';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $value productoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM productotercero_usuario WHERE productoId = ?';
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
		$sql = 'SELECT * FROM productotercero_usuario WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value usuarioId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM productotercero_usuario WHERE usuarioId = ?';
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
		$sql = 'SELECT * FROM productotercero_usuario WHERE mandante = ?';
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
        $sql = 'SELECT * FROM productotercero_usuario WHERE codigo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    public function queryByProductoIdAndUsuarioId($value,$usuarioId){
        $sql = 'SELECT * FROM productotercero_usuario WHERE producto_id = ?  AND usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($usuarioId);
        return $this->getList($sqlQuery);
    }






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $value productoId requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTipo($value){
		$sql = 'DELETE FROM productotercero_usuario WHERE productoId = ?';
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
		$sql = 'DELETE FROM productotercero_usuario WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value usuarioId requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM productotercero_usuario WHERE usuarioId = ?';
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
		$sql = 'DELETE FROM productotercero_usuario WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	



    /**
     * Crear y devolver un objeto del tipo ProductoterceroUsuario
     * con los valores de una consulta sql
     * 
     *	
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ProductoterceroUsuario ProductoterceroUsuario
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$productotercero_usuario = new ProductoterceroUsuario();
		
		$productotercero_usuario->prodtercusuarioId = $row['prodtercusuario_id'];
		$productotercero_usuario->productoId = $row['producto_id'];
		$productotercero_usuario->usuarioId = $row['usuario_id'];
        $productotercero_usuario->usucreaId = $row['usucrea_id'];
        $productotercero_usuario->usumodifId = $row['usumodif_id'];
        $productotercero_usuario->cuentacontableId = $row['cuentacontable_id'];
        $productotercero_usuario->cuentacontableegresoId = $row['cuentacontableegreso_id'];
        $productotercero_usuario->estado = $row['estado'];
        $productotercero_usuario->cupo = $row['cupo'];

		return $productotercero_usuario;
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