<?php namespace Backend\mysql;
use Backend\dao\CategoriaDAO;
use Backend\dto\Categoria;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'CategoriaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Categoria'
* 
* Ejemplo de uso: 
* $CategoriaMySqlDAO = new CategoriaMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CategoriaMySqlDAO implements CategoriaDAO
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
    public function load($id)
    {
        $sql = 'SELECT * FROM categoria WHERE categoria_id = ?';
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
    public function queryAll()
    {
        $sql = 'SELECT * FROM categoria';
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
        $sql = 'SELECT * FROM categoria ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $categoria_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($categoria_id)
    {
        $sql = 'DELETE FROM categoria WHERE categoria_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($categoria_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object Categoria categoria
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($categoria)
    {
        $sql = 'INSERT INTO categoria (descripcion, tipo,slug,estado, usucrea_id, usumodif_id,superior) VALUES (?, ?, ?, ?,?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($categoria->descripcion);
        $sqlQuery->set($categoria->tipo);
        $sqlQuery->set($categoria->slug);
        $sqlQuery->set($categoria->estado);
        $sqlQuery->setNumber($categoria->usucreaId);
        $sqlQuery->setNumber($categoria->usumodifId);
        $sqlQuery->setNumber($categoria->superior);

        $id = $this->executeInsert($sqlQuery);
        $categoria->categoriaId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object Categoria categoria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($categoria)
    {
        $sql = 'UPDATE categoria SET descripcion = ?, tipo = ?,slug=?,estado=?, usucrea_id = ?, usumodif_id = ?, superior = ? WHERE categoria_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($categoria->descripcion);
        $sqlQuery->set($categoria->tipo);
        $sqlQuery->set($categoria->slug);
        $sqlQuery->set($categoria->estado);
        $sqlQuery->setNumber($categoria->usucreaId);
        $sqlQuery->setNumber($categoria->usumodifId);
        $sqlQuery->setNumber($categoria->superior);

        $sqlQuery->setNumber($categoria->categoriaId);
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
        $sql = 'DELETE FROM categoria';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }




    /**
     * Obtener todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM categoria WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Tipo sea igual al valor pasado como parámetro
     *
     * @param String $value Tipo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTipo($value,$mandante="",$paisId="")
    {
        $innerjoinMandante="";
        $imagen = ',categoria.imagen ';
        $orden ="";
        if($mandante != "" && $paisId != ''){
            $innerjoinMandante =" INNER JOIN categoria_mandante ON ( categoria.categoria_id=categoria_mandante.categoria_id AND categoria_mandante.mandante = '".$mandante."'  AND categoria_mandante.pais_id = '".$paisId."' AND categoria_mandante.estado='A') ";
            $imagen = ',CASE when categoria_mandante.imagen IS NULL THEN categoria.imagen ELSE categoria_mandante.imagen end imagen';
            $orden = ' ORDER BY categoria_mandante.orden ';
        }elseif($mandante != ""){
            $innerjoinMandante =" INNER JOIN categoria_mandante ON ( categoria.categoria_id=categoria_mandante.categoria_id AND categoria_mandante.mandante = '".$mandante."' AND categoria_mandante.estado='A') ";
            $imagen = ',CASE when categoria_mandante.imagen IS NULL THEN categoria.imagen ELSE categoria_mandante.imagen end imagen';
            $orden = ' ORDER BY categoria_mandante.orden ';
        }

        $sql = 'SELECT categoria.categoria_id,categoria.descripcion,categoria.tipo,categoria.slug,categoria.estado,categoria.superior '.$imagen.' FROM categoria '.$innerjoinMandante.' WHERE categoria.tipo = ? '. $orden;
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        if($_ENV["debugFixed2"] == '1'){
          print_r($sql);
        }
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
        $sql = 'SELECT * FROM categoria WHERE usucrea_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM categoria WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM categoria WHERE usumodif_id = ?';
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
        $sql = 'SELECT * FROM categoria WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna slug sea igual al valor pasado como parámetro
     *
     * @param String $value slug requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySlug($value)
    {
        $sql = 'SELECT * FROM categoria WHERE slug = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM categoria WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByDescripcion($value)
    {
        $sql = 'DELETE FROM categoria WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Tipo sea igual al valor pasado como parámetro
     *
     * @param String $value Tipo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTipo($value)
    {
        $sql = 'DELETE FROM categoria WHERE tipo = ?';
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
        $sql = 'DELETE FROM categoria WHERE usucrea_id = ?';
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
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM categoria WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM categoria WHERE usumodif_id = ?';
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
        $sql = 'DELETE FROM categoria WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
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
    public function queryCategoriasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM categoria  ' . $where;



        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM categoria ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }





    /**
     * Crear y devolver un objeto del tipo Categoria
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Categoria Categoria
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $categoria = new Categoria();

        $categoria->categoriaId = $row['categoria_id'];
        $categoria->descripcion = $row['descripcion'];
        $categoria->tipo = $row['tipo'];
        $categoria->slug = $row['slug'];
        $categoria->estado = $row['estado'];
        $categoria->usucreaId = $row['usucrea_id'];
        $categoria->usumodifId = $row['usumodif_id'];
        $categoria->superior = $row['superior'];
        $categoria->imagen = $row['imagen'];

        return $categoria;
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
        $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
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
        $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
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
    protected function executeUpdate($sqlQuery)
    {
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
    protected function querySingleResult($sqlQuery)
    {
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
    protected function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
    }
}

?>
