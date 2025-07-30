<?php namespace Backend\mysql;
use Backend\dao\PerfilSubmenuDAO;
use Backend\dto\PerfilSubmenu;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'PerfilSubmenuMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'PerfilSubmenu'
* 
* Ejemplo de uso: 
* $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PerfilSubmenuMySqlDAO implements PerfilSubmenuDAO
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
     * Obtener el registro condicionado por las 
     * llaves primarias que se pasan como parámetros
     *
     * @param String $perfilId id del perfil
     * @param String $submenuId id del submenu
     *
     * @return Array resultado de la consulta
     *
     */
    public function load($perfilId, $submenuId)
    {
        $sql = 'SELECT * FROM perfil_submenu WHERE perfil_id = ?  AND submenu_id = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($perfilId);
        $sqlQuery->setNumber($submenuId);
        return $this->getRow($sqlQuery);
    }


    /**
     * Get Domain object by primry key
     *
     * @param String $id primary key
     * @return PerfilSubmenuMySql
     */
    public function loadByUsuarioId($perfilId, $submenuId,$usuarioId)
    {
        $sql = 'SELECT * FROM perfil_submenu WHERE perfil_id = ?  AND submenu_id = ?  AND usuario_id = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($perfilId);
        $sqlQuery->setNumber($submenuId);
        $sqlQuery->setNumber($usuarioId);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener el registro condicionado por las 
     * llaves primarias que se pasan como parámetros
     *
     * @param String $perfilId id del perfil
     * @param String $submenuId id del submenu
     * @param String $usuarioId id del usuario
     *
     * @return Array resultado de la consulta
     *
     */
    public function loadWithUsuarioId($perfilId, $submenuId, $usuarioId)
    {
        $sql = 'SELECT * FROM perfil_submenu WHERE perfil_id = ?  AND submenu_id = ? AND usuario_id = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($perfilId);
        $sqlQuery->setNumber($submenuId);
        $sqlQuery->setNumber($usuarioId);
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
        $sql = 'SELECT * FROM perfil_submenu';
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
        $sql = 'SELECT * FROM perfil_submenu ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }
    
    /**
     * Eliminar todos los registros condicionados
     * por la llaves primarias pasadas como parámetro
     *
     * @param String $perfilId id del perfil
     * @param String $submenuId id del submenu
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($perfilId, $submenuId,$userId="")
    {
        $sqlUserId="";

        if($userId != ""){
            $sqlUserId=" AND usuario_id='".$userId."' ";
        }

        $sql = 'DELETE FROM perfil_submenu WHERE perfil_id = ?  AND submenu_id = ? '.$sqlUserId;
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($perfilId);
        $sqlQuery->setNumber($submenuId);
        return $this->executeUpdate($sqlQuery);
    }
    
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object perfilSubmenu perfilSubmenu
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($perfilSubmenu)
    {
        $sql = 'INSERT INTO perfil_submenu (adicionar, editar, eliminar, perfil_id, submenu_id,pais,usuario_id,mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($perfilSubmenu->adicionar);
        $sqlQuery->set($perfilSubmenu->editar);
        $sqlQuery->set($perfilSubmenu->eliminar);
        $sqlQuery->set($perfilSubmenu->perfilId);
        $sqlQuery->setNumber($perfilSubmenu->submenuId);
        $sqlQuery->set($perfilSubmenu->pais);
        $sqlQuery->set($perfilSubmenu->usuarioId);
        $sqlQuery->set($perfilSubmenu->mandante);
        $this->executeInsert($sqlQuery);
        //$perfilSubmenu->id = $id;
        //return $id;
    }
    
    /**
     * Editar un registro en la base de datos
     *
     * @param Object perfilSubmenu perfilSubmenu
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($perfilSubmenu)
    {
        $sql = 'UPDATE perfil_submenu SET adicionar = ?, editar = ?, eliminar = ?, pais = ?, usuario_id = ?, mandante = ? WHERE perfil_id = ?  AND submenu_id = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($perfilSubmenu->adicionar);
        $sqlQuery->set($perfilSubmenu->editar);
        $sqlQuery->set($perfilSubmenu->eliminar);
        $sqlQuery->set($perfilSubmenu->pais);
        $sqlQuery->set($perfilSubmenu->usuarioId);

        $sqlQuery->set($perfilSubmenu->mandante);

        $sqlQuery->set($perfilSubmenu->perfilId);
        $sqlQuery->setNumber($perfilSubmenu->submenuId);
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
        $sql = 'DELETE FROM perfil_submenu';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna adicionar sea igual al valor pasado como parámetro
     *
     * @param String $value adicionar requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByAdicionar($value)
    {
        $sql = 'SELECT * FROM perfil_submenu WHERE adicionar = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna editar sea igual al valor pasado como parámetro
     *
     * @param String $value editar requerido
     *
     * @return Array resultado de la consulta
     *
     */ 
    public function queryByEditar($value)
    {
        $sql = 'SELECT * FROM perfil_submenu WHERE editar = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna eliminar sea igual al valor pasado como parámetro
     *
     * @param String $value eliminar requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByEliminar($value)
    {
        $sql = 'SELECT * FROM perfil_submenu WHERE eliminar = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }





    /**
    * Realizar una consulta en la tabla de PerfilSubmenu 'PerfilSubmenu'
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
    public function queryPerfilSubmenusCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }
        }
        $sql = "SELECT count(*) count FROM perfil_submenu INNER JOIN submenu ON (perfil_submenu.submenu_id=submenu.submenu_id) LEFT OUTER JOIN menu ON (menu.menu_id = submenu.menu_id) " . $where;
        $sqlQuery = new SqlQuery($sql);
        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM perfil_submenu INNER JOIN submenu ON (perfil_submenu.submenu_id=submenu.submenu_id) LEFT OUTER JOIN menu ON (menu.menu_id = submenu.menu_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);


        if ($_REQUEST["debugFixed2"] == '1') {

            print_r($sql);
        }

        $result = $this->execute2($sqlQuery);
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
        return $json;
    }


    /**
     * Realizar una consulta personalizada en la tabla perfil_submenu
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return string JSON con el conteo y los datos de la consulta
     */
    public function queryPerfilSubmenusRecursoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
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
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }
        }

        $sql = "SELECT count(*) count FROM perfil_submenu INNER JOIN submenu ON (perfil_submenu.submenu_id=submenu.submenu_id) LEFT OUTER JOIN menu ON (menu.menu_id = submenu.menu_id) LEFT OUTER JOIN submenu_recurso ON (submenu_recurso.pagina = submenu.pagina)" . $where;
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = "SELECT " . $select . " FROM perfil_submenu INNER JOIN submenu ON (perfil_submenu.submenu_id=submenu.submenu_id) LEFT OUTER JOIN menu ON (menu.menu_id = submenu.menu_id) LEFT OUTER JOIN submenu_recurso ON (submenu_recurso.pagina = submenu.pagina)" . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
        return $json;
    }


    /**
     * Realizar una consulta personalizada en la tabla perfil\_submenu
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return string JSON con el conteo y los datos de la consulta
     */

    public function queryPerfilGenericCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
        $sql = "SELECT count(*) count FROM perfil_submenu INNER JOIN submenu ON (perfil_submenu.submenu_id=submenu.submenu_id) " . $where;
        $sqlQuery = new SqlQuery($sql);
        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM perfil_submenu INNER JOIN submenu ON (perfil_submenu.submenu_id=submenu.submenu_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
        return $json;
    }






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna adicionar sea igual al valor pasado como parámetro
     *
     * @param String $value adicionar requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByAdicionar($value)
    {
        $sql = 'DELETE FROM perfil_submenu WHERE adicionar = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }
   
    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna editar sea igual al valor pasado como parámetro
     *
     * @param String $value editar requerido
     *
     * @return boolean resultado de la ejecución
     *
     */ 
    public function deleteByEditar($value)
    {
        $sql = 'DELETE FROM perfil_submenu WHERE editar = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }
    
    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna eliminar sea igual al valor pasado como parámetro
     *
     * @param String $value eliminar requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByEliminar($value)
    {
        $sql = 'DELETE FROM perfil_submenu WHERE eliminar = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }
    





    /**
     * Crear y devolver un objeto del tipo PerfilSubmenu
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $PerfilSubmenu PerfilSubmenu
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $perfilSubmenu = new PerfilSubmenu();
        $perfilSubmenu->perfilId = $row['perfil_id'];
        $perfilSubmenu->submenuId = $row['submenu_id'];
        $perfilSubmenu->adicionar = $row['adicionar'];
        $perfilSubmenu->editar = $row['editar'];
        $perfilSubmenu->eliminar = $row['eliminar'];
        $perfilSubmenu->pais = $row['pais'];
        $perfilSubmenu->usuarioId = $row['usuario_id'];

        $perfilSubmenu->mandante = $row['mandante'];

        return $perfilSubmenu;
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
