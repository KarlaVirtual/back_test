<?php namespace Backend\mysql;
use Backend\dao\ProdMandanteTipoDAO;
use Backend\dto\ProdMandanteTipo;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'ProdMandanteTipoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'ProdMandanteTipo'
* 
* Ejemplo de uso: 
* $ProdMandanteTipoMySqlDAO = new ProdMandanteTipoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProdMandanteTipoMySqlDAO implements ProdMandanteTipoDAO
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
	public function load($id)
	{
		$sql = 'SELECT * FROM prodmandante_tipo WHERE prodmandtipo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll()
	{
		$sql = 'SELECT * FROM prodmandante_tipo';
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
	public function queryAllOrderBy($orderColumn)
	{
		$sql = 'SELECT * FROM prodmandante_tipo ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}





    /**
    * Realizar una consulta en la tabla de ProdMandanteTipo 'ProdMandanteTipo'
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
    public function queryProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM prodmandante_tipo  INNER JOIN mandante ON (mandante.mandante = prodmandante_tipo.mandante)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM prodmandante_tipo  INNER JOIN mandante ON (mandante.mandante = prodmandante_tipo.mandante)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }




    /**
     * Obtener todos los registros donde se encuentre que los valores
     * de las columnas productoId y value sean iguales
     * a los valores pasados por parametro
     *
     * @param String $productoId id del producto
     * @param String $value value	
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByTipoAndMandante($productoId, $value)
    {
        $sql = 'SELECT * FROM prodmandante_tipo WHERE tipo=? AND mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($productoId);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna siteId sea igual al valor pasado como parámetro
     *
     * @param String $siteId siteId
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryBySite($siteId="")
    {
        $sql = 'SELECT * FROM prodmandante_tipo WHERE site_id=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($siteId);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que los valores
     * de las columnas siteId y siteKey sean iguales
     * a los valores pasados por parametro
     *
     * @param String $siteId siteId
     * @param String $siteKey siteKey	
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryBySiteAndKey($siteId, $siteKey)
    {
        $sql = 'SELECT * FROM prodmandante_tipo WHERE site_id=? AND site_key = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($siteId);
        $sqlQuery->set($siteKey);
        return $this->getList($sqlQuery);
    }






    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $prodmandtipo_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($prodmandtipo_id)
	{
		$sql = 'DELETE FROM prodmandante_tipo WHERE prodmandtipo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($prodmandtipo_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object productoMandante productoMandante
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($productoMandante)
	{
		$sql = 'INSERT INTO prodmandante_tipo (tipo, mandante, estado, site_id, site_key, usucrea_id, usumodif_id, url_api,tipo_integracion,contingencia) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($productoMandante->tipo);
		$sqlQuery->setNumber($productoMandante->mandante);
		$sqlQuery->set($productoMandante->estado);
		$sqlQuery->set($productoMandante->siteId);
		$sqlQuery->set($productoMandante->siteKey);
		$sqlQuery->setNumber($productoMandante->usucreaId);
		$sqlQuery->setNumber($productoMandante->usumodifId);
        $sqlQuery->set($productoMandante->urlApi);

        if($productoMandante->tipoIntegracion == ""){
            $productoMandante->tipoIntegracion=0;
        }

        if($productoMandante->contingencia == ""){
            $productoMandante->contingencia=0;
        }

        $sqlQuery->set($productoMandante->tipoIntegracion);
        $sqlQuery->set($productoMandante->contingencia);

		$id = $this->executeInsert($sqlQuery);
		$productoMandante->prodmandtipoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object productoMandante productoMandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($productoMandante)
	{
		$sql = 'UPDATE prodmandante_tipo SET tipo = ?, mandante = ?, estado = ?, site_id = ?, site_key = ?, usucrea_id = ?, usumodif_id = ?,  url_api = ?,tipo_integracion = ?,contingencia = ? WHERE prodmandtipo_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($productoMandante->tipo);
		$sqlQuery->setNumber($productoMandante->mandante);
		$sqlQuery->set($productoMandante->estado);
		$sqlQuery->set($productoMandante->siteId);
		$sqlQuery->set($productoMandante->siteKey);
		$sqlQuery->setNumber($productoMandante->usucreaId);
		$sqlQuery->setNumber($productoMandante->usumodifId);
		$sqlQuery->set($productoMandante->urlApi);


        if($productoMandante->tipoIntegracion == ""){
            $productoMandante->tipoIntegracion=0;
        }

        if($productoMandante->contingencia == ""){
            $productoMandante->contingencia=0;
        }

        $sqlQuery->set($productoMandante->tipoIntegracion);
        $sqlQuery->set($productoMandante->contingencia);


        $sqlQuery->setNumber($productoMandante->prodmandtipoId);
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
		$sql = 'DELETE FROM prodmandante_tipo';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Crear y devolver un objeto del tipo ProdMandanteTipo
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ProdMandanteTipo ProdMandanteTipo
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$productoMandante = new ProdMandanteTipo();

		$productoMandante->prodmandtipoId = $row['prodmandtipo_id'];
		$productoMandante->tipo = $row['tipo'];
		$productoMandante->mandante = $row['mandante'];
		$productoMandante->estado = $row['estado'];
		$productoMandante->siteId = $row['site_id'];
		$productoMandante->siteKey = $row['site_key'];
		$productoMandante->fechaCrea = $row['fecha_crea'];
		$productoMandante->fechaModif = $row['fecha_modif'];
		$productoMandante->usucreaId = $row['usucrea_id'];
		$productoMandante->usumodifId = $row['usumodif_id'];
        $productoMandante->urlApi = $row['url_api'];
        $productoMandante->tipoIntegracion = $row['tipo_integracion'];
        $productoMandante->contingencia = $row['contingencia'];

		return $productoMandante;
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
	protected function execute($sqlQuery)
	{
		return QueryExecutor::execute($this->transaction, $sqlQuery);
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