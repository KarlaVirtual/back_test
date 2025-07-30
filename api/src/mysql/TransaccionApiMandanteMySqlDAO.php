<?php namespace Backend\mysql;
use Backend\dao\TransaccionApiMandanteDAO;
use Backend\dto\Helpers;
use Backend\dto\TransaccionApiMandante;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
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
class TransaccionApiMandanteMySqlDAO implements TransaccionApiMandanteDAO
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
	public function load($id)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE transapimandante_id = ?';
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
	public function queryAll()
	{
		$sql = 'SELECT * FROM transaccion_api_mandante';
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
	public function queryAllOrderBy($orderColumn)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}





   	/**
   	* Realizar una consulta en la tabla de TransaccionApiMandante 'TransaccionApiMandante'
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
    public function queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
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


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }


        $sql = "SELECT count(*) count FROM transaccion_api_mandante INNER JOIN transaccion_api ON (transaccion_api.transapi_id = transaccion_api_mandante.transapi_id )  INNER JOIN usuario_mandante ON (transaccion_api.usuario_id = usuario_mandante.usumandante_id ) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transaccion_api_mandante INNER JOIN transaccion_api ON (transaccion_api.transapi_id = transaccion_api_mandante.transapi_id )  INNER JOIN usuario_mandante ON (transaccion_api.usuario_id = usuario_mandante.usumandante_id )  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }





	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $transapimandante_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($transapimandante_id)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE transapimandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($transapimandante_id);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto transaccionApi transaccionApi
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($transaccionApi)
	{
		$sql = 'INSERT INTO transaccion_api_mandante (proveedor_id,producto_id,usuario_id, tipo, transaccion_id, t_value,valor,identificador,respuesta_codigo,respuesta, usucrea_id, usumodif_id, transapi_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transaccionApi->proveedorId);
		$sqlQuery->setNumber($transaccionApi->productoId);
		$sqlQuery->setNumber($transaccionApi->usuarioId);
		$sqlQuery->set($transaccionApi->tipo);
		$sqlQuery->setString($transaccionApi->transaccionId);
        $sqlQuery->set($transaccionApi->tValue);
        $sqlQuery->set($transaccionApi->valor);
		$sqlQuery->set($transaccionApi->identificador);
		$sqlQuery->set($transaccionApi->respuestaCodigo);
		$sqlQuery->set($transaccionApi->respuesta);
		$sqlQuery->setNumber($transaccionApi->usucreaId);
        $sqlQuery->setNumber($transaccionApi->usumodifId);
        $sqlQuery->set($transaccionApi->transapiId);

		$id = $this->executeInsert($sqlQuery);
		$transaccionApi->transapimandanteId = $id;
		return $id;
	}

	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto transaccionApi transaccionApi
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($transaccionApi)
	{
		$sql = 'UPDATE transaccion_api_mandante SET proveedor_id = ?,producto_id=?,usuario_id=?, tipo = ?, transaccion_id = ?, t_value = ?,valor=?, identificador=?, respuesta_codigo = ?, respuesta = ?, usucrea_id = ?, usumodif_id = ?, transapi_id = ? WHERE transapimandante_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transaccionApi->proveedorId);
		$sqlQuery->setNumber($transaccionApi->productoId);
		$sqlQuery->setNumber($transaccionApi->usuarioId);
		$sqlQuery->set($transaccionApi->tipo);
		$sqlQuery->setString($transaccionApi->transaccionId);
        $sqlQuery->set($transaccionApi->tValue);
        $sqlQuery->set($transaccionApi->valor);
		$sqlQuery->set($transaccionApi->identificador);
		$sqlQuery->set($transaccionApi->respuestaCodigo);
		$sqlQuery->set($transaccionApi->respuesta);
		$sqlQuery->setNumber($transaccionApi->usucreaId);
		$sqlQuery->setNumber($transaccionApi->usumodifId);
        $sqlQuery->set($transaccionApi->transapiId);

		$sqlQuery->setNumber($transaccionApi->transapimandanteId);
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
	public function clean()
	{
		$sql = 'DELETE FROM transaccion_api_mandante';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}









	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna proveedor_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value proveedor_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByProveedorId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE proveedor_id = ?';
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
	public function queryByProductoId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function queryByUsuarioId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTipo($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function queryByTransaccionId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * las columnas transaccion_id, proveedor_id y respuestaCodigo, sean
 	 * iguales a los valores pasados como parámetro
 	 *
 	 * @param String $value transaccion_id requerido
 	 * @param String $proveedorId proveedor_id requerido
 	 * @param String $respuestaCodigo respuesta_codigo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTransaccionIdAndProveedor($value, $proveedorId, $respuestaCodigo)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE transaccion_id = ? AND proveedor_id = ? AND respuesta_codigo = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		$sqlQuery->setNumber($proveedorId);
		$sqlQuery->setString($respuestaCodigo);

		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna t_value sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value t_value requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTValue($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE t_value = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna identificador sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value identificador requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByIdentificador($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE identificador = ?';
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
	public function queryByFechaCrea($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE fecha_crea = ?';
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
	public function queryByUsucreaId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE usucrea_id = ?';
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
	public function queryByFechaModif($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE fecha_modif = ?';
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
	public function queryByUsumodifId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_mandante WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna transapi_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transapi_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
    public function queryByTransapiId($value)
    {
        $sql = 'SELECT * FROM transaccion_api_mandante WHERE transapi_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }











	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna proveedor_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value proveedor_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
    public function deleteByProveedorId($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE proveedor_id = ?';
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
	public function deleteByProductoId($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function deleteByUsuarioId($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTipo($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function deleteByTransaccionId($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna t_value sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value t_value requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTValue($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE t_value = ?';
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
	public function deleteByFechaCrea($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE fecha_crea = ?';
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
	public function deleteByUsucreaId($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE usucrea_id = ?';
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
	public function deleteByFechaModif($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE fecha_modif = ?';
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
	public function deleteByUsumodifId($value)
	{
		$sql = 'DELETE FROM transaccion_api_mandante WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}









	/**
 	 * Crear y devolver un objeto del tipo TransaccionApiMandante
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $transaccionApi TransaccionApiMandante
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row)
	{
		$transaccionApi = new TransaccionApiMandante();

		$transaccionApi->transapimandanteId = $row['transapimandante_id'];
		$transaccionApi->proveedorId = $row['proveedor_id'];
		$transaccionApi->producto_id = $row['producto_id'];
		$transaccionApi->usuarioId = $row['usuario_id'];
		$transaccionApi->tipo = $row['tipo'];
		$transaccionApi->transaccionId = $row['transaccion_id'];
        $transaccionApi->tValue = $row['t_value'];
        $transaccionApi->valor = $row['valor'];
		$transaccionApi->identificador = $row['identificador'];
		$transaccionApi->respuesta = $row['respuesta'];
		$transaccionApi->respuestaCodigo = $row['respuesta_codigo'];
		$transaccionApi->fechaCrea = $row['fecha_crea'];
		$transaccionApi->usucreaId = $row['usucrea_id'];
		$transaccionApi->fechaModif = $row['fecha_modif'];
        $transaccionApi->usumodifId = $row['usumodif_id'];
        $transaccionApi->transapiId = $row['transapi_id'];

		return $transaccionApi;
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
 	 * @param String $sqlQuery consulta sql
 	 *
 	 * @return Array $ resultado de la ejecución
 	 *
 	 * @access protected
 	 *
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
	protected function execute($sqlQuery)
	{
		return QueryExecutor::execute($this->transaction, $sqlQuery);
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
	protected function executeUpdate($sqlQuery)
	{
		return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
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
	protected function querySingleResult($sqlQuery)
	{
		return QueryExecutor::queryForString($this->transaction, $sqlQuery);
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
	protected function executeInsert($sqlQuery)
	{
		return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
	}
}
?>