<?php namespace Backend\mysql;
use Backend\dao\BannerDAO;
use Backend\dto\Banner;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'BannerMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Banner'
* 
* Ejemplo de uso: 
* $BannerMySqlDAO = new BannerMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class BannerMySqlDAO implements BannerDAO
{


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
    public function load($id)
    {
        $sql = 'SELECT * FROM banner WHERE banner_id = ?';
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
        $sql = 'SELECT * FROM banner';
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
        $sql = 'SELECT * FROM banner ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $banner_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($banner_id)
    {
        $sql = 'DELETE FROM banner WHERE banner_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($banner_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto Banner banner
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($banner)
    {
        $sql = 'INSERT INTO banner (usuario_id, nombre, width, estado, height,bsize,tipo,idioma,productointerno_id,filename,fecha_expiracion,publico, usucrea_id, usumodif_id,pais_id,mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($banner->usuarioId);
        $sqlQuery->set($banner->nombre);
        $sqlQuery->set($banner->width);
        $sqlQuery->set($banner->estado);
        $sqlQuery->set($banner->height);
        $sqlQuery->set($banner->bsize);
        $sqlQuery->set($banner->tipo);
        $sqlQuery->set($banner->idioma);
        $sqlQuery->set($banner->productointernoId);
        $sqlQuery->set($banner->filename);
        $sqlQuery->set($banner->fechaExpiracion);
        $sqlQuery->set($banner->publico);
        $sqlQuery->setNumber($banner->usucreaId);
        $sqlQuery->setNumber($banner->usumodifId);
        $sqlQuery->setNumber($banner->paisId);
        $sqlQuery->setNumber($banner->mandante);

        $id = $this->executeInsert($sqlQuery);
        $banner->bannerId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto Banner banner
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($banner)
    {
        $sql = 'UPDATE banner SET usuario_id = ?, nombre = ?, width = ?, estado = ?, height = ?, bsize = ?,tipo= ?,idioma= ?,productointerno_id= ?,filename= ?,fecha_expiracion = ?,publico = ?, usucrea_id = ?, usumodif_id = ?, pais_id=?, mandante=? WHERE banner_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($banner->usuarioId);
        $sqlQuery->set($banner->nombre);
        $sqlQuery->set($banner->width);
        $sqlQuery->set($banner->estado);
        $sqlQuery->set($banner->height);
        $sqlQuery->set($banner->bsize);
        $sqlQuery->set($banner->tipo);
        $sqlQuery->set($banner->idioma);
        $sqlQuery->set($banner->productointernoId);
        $sqlQuery->set($banner->filename);
        $sqlQuery->set($banner->fechaExpiracion);
        $sqlQuery->set($banner->publico);

        $sqlQuery->setNumber($banner->usucreaId);
        $sqlQuery->setNumber($banner->usumodifId);
        $sqlQuery->setNumber($banner->paisId);
        $sqlQuery->setNumber($banner->mandante);

        $sqlQuery->setNumber($banner->bannerId);
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
        $sql = 'DELETE FROM banner';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ProveedorId sea igual al valor pasado como parámetro
     *
     * @param String $value ProveedorId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByProveedorId($value)
    {
        $sql = 'SELECT * FROM banner WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Nombre sea igual al valor pasado como parámetro
     *
     * @param String $value Nombre requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByNombre($value)
    {
        $sql = 'SELECT * FROM banner WHERE nombre = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Estado sea igual al valor pasado como parámetro
     *
     * @param String $value Estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM banner WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM banner WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsucreaId sea igual al valor pasado como parámetro
     *
     * @param String $value UsucreaId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM banner WHERE usucrea_id = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM banner WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Estado sea igual al valor pasado como parámetro
     *
     * @param String $value Estado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM banner WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM banner WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna UsucreaId sea igual al valor pasado como parámetro
     *
     * @param String $value UsucreaId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM banner WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna UsumodifId sea igual al valor pasado como parámetro
     *
     * @param String $value UsumodifId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM banner WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }






    /**
     * Crear y devolver un objeto del tipo Banner
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $Banner Banner
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $banner = new Banner();

        $banner->bannerId = $row['banner_id'];
        $banner->usuarioId = $row['usuario_id'];
        $banner->nombre = $row['nombre'];
        $banner->width = $row['width'];
        $banner->estado = $row['estado'];
        $banner->height = $row['height'];
        $banner->bsize = $row['bsize'];
        $banner->tipo = $row['tipo'];
        $banner->idioma = $row['idioma'];
        $banner->productointernoId = $row['productointerno_id'];
        $banner->filename = $row['filename'];
        $banner->fechaExpiracion = $row['fecha_expiracion'];
        $banner->publico = $row['publico'];
        $banner->usucreaId = $row['usucrea_id'];
        $banner->usumodifId = $row['usumodif_id'];
        $banner->paisId = $row['pais_id'];
        $banner->mandante = $row['mandante'];

        return $banner;
    }


    /**
    * Realizar una consulta en la tabla de banners 'Banner'
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
    public function queryBanners($select,$sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM banner  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .' FROM banner ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
    * Realizar una consulta en la tabla de banner 'Banner'
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
    public function queryBannersUsuarioCustom($usuarioId,$select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM banner LEFT OUTER JOIN usuario_banner ON (usuario_banner.banner_id = banner.banner_id AND usuario_banner.usuario_id = '.$usuarioId.' ) ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM banner LEFT OUTER JOIN usuario_banner ON (usuario_banner.banner_id = banner.banner_id AND usuario_banner.usuario_id ='.$usuarioId.' ) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
