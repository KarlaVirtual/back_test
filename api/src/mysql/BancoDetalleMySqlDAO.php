<?php namespace Backend\mysql;
use Backend\dao\banco_detalleDAO;
use Backend\dao\BancoDetalleDAO;
use Backend\dto\banco_detalle;
use Backend\dto\BancoDetalle;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'banco_detalleMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Bano'
* 
* Ejemplo de uso: 
* $banco_detalleMySqlDAO = new banco_detalleMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class BancoDetalleMySqlDAO implements BancoDetalleDAO {

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
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
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
		$sql = 'SELECT * FROM banco_detalle WHERE bancodetalle_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
//        $sqlQuery->set($estado);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM banco_detalle';
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
		$sql = 'SELECT * FROM banco_detalle ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $banco_detalle_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($bancoDetalle){
		$sql = 'DELETE FROM banco_detalle WHERE bancodetalle_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($bancoDetalle);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto banco_detalle banco_detalle
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($bancoDetalle){
		$sql = 'INSERT INTO banco_detalle (banco_id,producto_id,pais_id, mandante,estado,usucrea_id,usumodif_id) VALUES (?,?,?,?,?,?,?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($bancoDetalle->bancoId);
        $sqlQuery->setNumber($bancoDetalle->productoId);
        $sqlQuery->setNumber($bancoDetalle->paisId);
        $sqlQuery->setNumber($bancoDetalle->mandante);
        $sqlQuery->set($bancoDetalle->estado);
        $sqlQuery->setNumber($bancoDetalle->usucreaId);
        $sqlQuery->setNumber($bancoDetalle->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$bancoDetalle->bancodetalle_id = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto banco_detalle banco_detalle
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($bancoDetalle){
		$sql = 'UPDATE banco_detalle SET banco_id = ?,producto_id = ?,pais_id = ?, mandante = ?, estado = ?, usucrea_id = ?, usumodif_id = ?  WHERE bancodetalle_id = ?';

		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($bancoDetalle->bancoId);
        $sqlQuery->setNumber($bancoDetalle->productoId);
        $sqlQuery->setNumber($bancoDetalle->paisId);
        $sqlQuery->setNumber($bancoDetalle->mandante);
        $sqlQuery->set($bancoDetalle->estado);
        $sqlQuery->setNumber($bancoDetalle->usucreaId);
        $sqlQuery->setNumber($bancoDetalle->usumodifId);

		$sqlQuery->setNumber($bancoDetalle->bancodetalleId);
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
		$sql = 'DELETE FROM banco_detalle';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de banco_detalles 'banco_detalle'
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
    public function queryBancodetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $sql = "SELECT count(*) count FROM banco_detalle INNER JOIN pais ON (banco_detalle.pais_id = pais.pais_id)   INNER JOIN producto ON (banco_detalle.producto_id = producto.producto_id) INNER JOIN banco ON (banco_detalle.banco_id = banco.banco_id)  INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)  LEFT OUTER JOIN usuario_banco ON (banco_detalle.banco_id = usuario_banco.banco_id) " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM banco_detalle INNER JOIN pais ON (banco_detalle.pais_id = pais.pais_id) INNER JOIN producto ON (banco_detalle.producto_id = producto.producto_id) INNER JOIN banco ON (banco_detalle.banco_id = banco.banco_id)  INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) LEFT OUTER JOIN usuario_banco ON (banco_detalle.banco_id = usuario_banco.banco_id) " . $where." " .  " " . " order by " . $sidx . " " . $sord  . " LIMIT ". $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
     * Realizar una consulta en la tabla de banco_detalles 'banco_detalle'
     * de una manera personalizada con consultas en la tabla 'banco_mandante' o 'banco'
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $mandante para generar consulta por mandante
     * @param String $paisId para generar consulta por pais
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryBancosCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId='')
    {
        $where = " where 1=1 ";

        $withbancoMandante=false;


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

                $cond="banco_mandante";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $withbancoMandante=true;
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $innerbanco_mandante='';

        if($withbancoMandante){
            $strPaisId='';
            if($paisId != ''){
                $strPaisId=' AND banco_mandante.pais_id = "'.$paisId.'" ';
            }
            $innerbanco_mandante = '  INNER JOIN banco_mandante ON (banco_mandante.banco_id = banco.banco_id AND banco_mandante.mandante= "'.$mandante.'" '.$strPaisId.')  ';
        }
        else {
            $strPaisId='';
            if($paisId != ''){
                $strPaisId=' AND banco_detalle.pais_id = "'.$paisId.'" ';
            }
            $innerbanco_mandante = '  INNER JOIN banco ON (banco_detalle.banco_id = banco.banco_id AND banco_detalle.mandante= "'.$mandante.'" '.$strPaisId.')  ';
        }


        $sql = 'SELECT count(*) count FROM banco_detalle '.$innerbanco_mandante.'   ' . $where;


        $where=$where . " GROUP BY banco_detalle.banco_id ";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $innerbanco_mandante='';

        if($withbancoMandante){
            $strPaisId='';
            if($paisId != ''){
                $strPaisId=' AND banco_mandante.pais_id = "'.$paisId.'" ';
            }
            $innerbanco_mandante = '  INNER JOIN banco_mandante ON (banco_mandante.banco_id = banco.banco_id AND banco_mandante.mandante= "'.$mandante.'" '.$strPaisId.')  ';
        }
        else {
            $strPaisId='';
            if($paisId != ''){
                $strPaisId=' AND banco_detalle.pais_id = "'.$paisId.'" ';
            }
            $innerbanco_mandante = '  INNER JOIN banco ON (banco_detalle.banco_id = banco.banco_id AND banco_detalle.mandante= "'.$mandante.'" '.$strPaisId.')  ';
        }

        $sql = 'SELECT ' .$select .'  FROM banco_detalle  '.$innerbanco_mandante.'  ' . $where . "  order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        if($_ENV["debugFixed2"] == '1'){
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de banco_detalles 'banco_detalle'
     * de una manera personalizada con consultas en la tabla 'banco_mandante' o 'banco'
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $mandante para generar consulta por mandante
     * @param String $paisId para generar consulta por pais
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryBancosMandanteProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId='')
    {
        $where = " where 1=1   AND proveedor.tipo = 'payout' AND banco_mandante.estado = 'A' " ;

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

        $sql = 'SELECT count(*) count 
FROM banco_detalle 
INNER JOIN banco_mandante ON banco_mandante.banco_id = banco_detalle.banco_id
    AND banco_detalle.pais_id = banco_mandante.pais_id
    AND banco_detalle.mandante = banco_mandante.mandante
    INNER JOIN banco ON banco.banco_id = banco_detalle.banco_id
    INNER JOIN producto ON (banco_detalle.producto_id = producto.producto_id)
    INNER JOIN mandante ON (banco_mandante.mandante = mandante.mandante )
    INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id )  ' . $where;

//        $where=$where . " GROUP BY banco_detalle.banco_id ";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = 'SELECT ' .$select .'  FROM banco_detalle INNER JOIN banco_mandante ON banco_mandante.banco_id = banco_detalle.banco_id AND banco_detalle.pais_id = banco_mandante.pais_id AND banco_detalle.mandante = banco_mandante.mandante INNER JOIN banco ON banco.banco_id = banco_detalle.banco_id INNER JOIN producto ON (banco_detalle.producto_id = producto.producto_id) INNER JOIN mandante ON (banco_mandante.mandante = mandante.mandante ) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id )   ' . $where . "  order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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



    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM banco_detalle WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna verifica sea igual al valor pasado como parámetro
     *
     * @param String $value verifica requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByVerifica($value)
    {
        $sql = 'SELECT * FROM banco_detalle WHERE verifica = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM banco_detalle WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaModif($value)
    {
        $sql = 'SELECT * FROM banco_detalle WHERE fecha_modif = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM banco_detalle WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM banco_detalle WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value Descripcion requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByBancoId($value){
		$sql = 'SELECT * FROM banco_detalle WHERE banco_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value Descripcion requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByBancoId($value){
		$sql = 'DELETE FROM banco_detalle WHERE banco_id = ?';
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
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM banco_detalle WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM banco_detalle WHERE estado = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM banco_detalle WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFechaModif($value)
    {
        $sql = 'DELETE FROM banco_detalle WHERE fecha_modif = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM banco_detalle WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM banco_detalle WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }





    /**
     * Crear y devolver un objeto del tipo banco_detalle
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $bancoDetalle banco_detalle
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$bancoDetalle = new BancoDetalle();

		$bancoDetalle->bancodetalleId = $row['bancodetalle_id'];
        $bancoDetalle->bancoId = $row['banco_id'];
        $bancoDetalle->productoId = $row['producto_id'];
        $bancoDetalle->paisId = $row['pais_id'];
        $bancoDetalle->mandante = $row['mandante'];
        $bancoDetalle->estado = $row['estado'];
        $bancoDetalle->fechaCrea = $row['fecha_crea'];
        $bancoDetalle->fechaModif = $row['fecha_modif'];
        $bancoDetalle->usucreaId = $row['usucrea_id'];
        $bancoDetalle->usumodifId = $row['usumodif_id'];

		return $bancoDetalle;
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