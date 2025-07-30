<?php namespace Backend\mysql;
use Backend\dao\CategoriaProductoDAO;
use Backend\dto\CategoriaProducto;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'CategoriaProductoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'CategoriaProducto'
* 
* Ejemplo de uso: 
* $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CategoriaProductoMySqlDAO implements CategoriaProductoDAO{

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
		$sql = 'SELECT * FROM categoria_producto WHERE catprod_id = ?';
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
		$sql = 'SELECT * FROM categoria_producto';
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
		$sql = 'SELECT * FROM categoria_producto ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $catprod_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($catprod_id){
		$sql = 'DELETE FROM categoria_producto WHERE catprod_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($catprod_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object categoriaProducto categoriaProducto
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($categoriaProducto){

		$sql = 'INSERT INTO categoria_producto (categoria_id, producto_id, usucrea_id, usumodif_id, estado, orden, mandante, pais_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($categoriaProducto->categoriaId);
		$sqlQuery->setNumber($categoriaProducto->productoId);
		$sqlQuery->setNumber($categoriaProducto->usucreaId);
		$sqlQuery->setNumber($categoriaProducto->usumodifId);

        $sqlQuery->setString($categoriaProducto->estado);
        $sqlQuery->setNumber($categoriaProducto->orden);
        $sqlQuery->setString($categoriaProducto->mandante);
        $sqlQuery->setNumber($categoriaProducto->paisId);

        $id = $this->executeInsert($sqlQuery);
		$categoriaProducto->catprodId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object categoriaProducto categoriaProducto
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($categoriaProducto){
		$sql = 'UPDATE categoria_producto SET categoria_id = ?, producto_id = ?, usucrea_id = ?, usumodif_id = ?,estado = ?, orden = ?, mandante = ? WHERE catprod_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($categoriaProducto->categoriaId);
		$sqlQuery->setNumber($categoriaProducto->productoId);
		$sqlQuery->setNumber($categoriaProducto->usucreaId);
		$sqlQuery->setNumber($categoriaProducto->usumodifId);

        $sqlQuery->setString($categoriaProducto->estado);
        $sqlQuery->setNumber($categoriaProducto->orden);
        $sqlQuery->setString($categoriaProducto->mandante);

		$sqlQuery->setNumber($categoriaProducto->catprodId);
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
		$sql = 'DELETE FROM categoria_producto';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}




    /**
     * Obtener todos los registros donde se encuentre que
     * la columna categoria_id sea igual al valor pasado como parámetro
     *
     * @param String $value categoria_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByCategoriaId($value){
		$sql = 'SELECT * FROM categoria_producto WHERE categoria_id = ?';
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
	public function queryByProductoId($value){
		$sql = 'SELECT * FROM categoria_producto WHERE producto_id = ?';
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
    public function queryByProductoIdAndCategoriaIdMandante($value,$categoriaId,$mandante){
        $sql = 'SELECT * FROM categoria_producto WHERE producto_id = ? AND categoria_id = ? AND mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        $sqlQuery->setNumber($categoriaId);
        $sqlQuery->setNumber($mandante);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros en la tabla categoria_producto basándose en los parámetros proporcionados.
     *
     * @param int $value El ID del producto.
     * @param int $categoriaId El ID de la categoría.
     * @param int $mandante El ID del mandante.
     * @param int $paisId El ID del país.
     * @return array Lista de registros que coinciden con los criterios de búsqueda.
     */
    public function queryByProductoIdAndCategoriaIdMandanteAndPaisId($value,$categoriaId,$mandante,$paisId){
        $sql = 'SELECT * FROM categoria_producto WHERE producto_id = ? AND categoria_id = ? AND mandante = ? AND pais_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        $sqlQuery->setNumber($categoriaId);
        $sqlQuery->setNumber($mandante);
        $sqlQuery->setNumber($paisId);
        return $this->getList($sqlQuery);
    }
    public function queryByProductoIdAndCategoriaIdMandanteAndPaisIdAndEstado($value,$categoriaId,$mandante,$paisId,$estado){
        $sql = 'SELECT * FROM categoria_producto WHERE producto_id = ? AND categoria_id = ? AND mandante = ? AND pais_id = ? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        $sqlQuery->setNumber($categoriaId);
        $sqlQuery->setNumber($mandante);
        $sqlQuery->setNumber($paisId);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas Tipo y producto_id sean iguales a los
     * valores pasados como parámetros
     *
     * @param String $value producto_id requerido
     * @param String $tipo Tipo requerido
	 *     
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByProductoIdAndTipoCategoria($value,$tipo){
        $sql = 'SELECT * FROM categoria_producto  INNER JOIN categoria ON (categoria.categoria_id = categoria_producto.categoria_id AND tipo= "'.$tipo.'" ) WHERE producto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
		$sql = 'SELECT * FROM categoria_producto WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'SELECT * FROM categoria_producto WHERE fecha_crea = ?';
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
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM categoria_producto WHERE usumodif_id = ?';
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
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM categoria_producto WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de areas 'Area'
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
    public function queryCategoriaProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM categoria_producto  INNER JOIN categoria_mandante ON (categoria_mandante.catmandante_id = categoria_producto.categoria_id ) INNER JOIN producto ON (producto.producto_id = categoria_producto.producto_id)  ' . $where;



        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'   FROM categoria_producto  INNER JOIN categoria_mandante ON (categoria_mandante.catmandante_id = categoria_producto.categoria_id ) INNER JOIN producto ON (producto.producto_id = categoria_producto.producto_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Consulta personalizada de categorías de productos para un mandante específico.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual ordenar.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Inicio del límite de la consulta.
     * @param int $limit Cantidad de registros a obtener.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $mandanteSelect Identificador del mandante para filtrar los resultados.
     * @return string JSON con el conteo de registros y los datos obtenidos.
     */
    public function queryCategoriaProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandanteSelect="") {
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sqlMandante ='';

        if($mandanteSelect != ''){
            $sqlMandante= " INNER JOIN subproveedor_mandante on (subproveedor_mandante.mandante=".$mandanteSelect." AND subproveedor_mandante.subproveedor_id = subproveedor.subproveedor_id) ";
        }

        $sql = 'SELECT count(*) count FROM categoria_producto  INNER JOIN categoria_mandante ON (categoria_mandante.catmandante_id = categoria_producto.categoria_id
                                              AND categoria_mandante.pais_id = categoria_producto.pais_id

    ) INNER JOIN producto ON (producto.producto_id = categoria_producto.producto_id)  INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) '.$sqlMandante.'  INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.pais_id=categoria_producto.pais_id AND producto_mandante.mandante=categoria_producto.mandante)  ' . $where;
        $sqlQuery = new SqlQuery($sql);

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $count = $this->execute2($sqlQuery);

        $sql = 'SELECT ' .$select .'   FROM categoria_producto  INNER JOIN categoria_mandante ON (categoria_mandante.catmandante_id = categoria_producto.categoria_id
                                                       AND categoria_mandante.pais_id = categoria_producto.pais_id

         ) INNER JOIN producto ON (producto.producto_id = categoria_producto.producto_id)  INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) '.$sqlMandante.'    INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.pais_id=categoria_producto.pais_id AND producto_mandante.mandante=categoria_producto.mandante)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }




    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna categoria_id sea igual al valor pasado como parámetro
     *
     * @param String $value categoria_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByCategoriaId($value){
		$sql = 'DELETE FROM categoria_producto WHERE categoria_id = ?';
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
	public function deleteByProductoId($value){
		$sql = 'DELETE FROM categoria_producto WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'DELETE FROM categoria_producto WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'DELETE FROM categoria_producto WHERE fecha_crea = ?';
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
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM categoria_producto WHERE usumodif_id = ?';
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
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM categoria_producto WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Crear y devolver un objeto del tipo CategoriaProducto
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $CategoriaProducto CategoriaProducto
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$categoriaProducto = new CategoriaProducto();
		
		$categoriaProducto->catprodId = $row['catprod_id'];
		$categoriaProducto->categoriaId = $row['categoria_id'];
		$categoriaProducto->productoId = $row['producto_id'];
		$categoriaProducto->usucreaId = $row['usucrea_id'];
		$categoriaProducto->usumodifId = $row['usumodif_id'];

        $categoriaProducto->estado = $row['estado'];
        $categoriaProducto->orden = $row['orden'];
        $categoriaProducto->mandante = $row['mandante'];
        $categoriaProducto->paisId = $row['pais_id'];

		return $categoriaProducto;
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

    /**
     * Ejecuta una consulta SQL personalizada.
     *
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la ejecución de la consulta.
     */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }
}
?>
