<?php namespace Backend\mysql;
use Backend\dao\BonoDAO;
use Backend\dto\Bono;
use Backend\dto\Helpers;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'BonoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Banner'
* 
* Ejemplo de uso: 
* $BonoMySqlDAO = new BonoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class BonoMySqlDAO implements BonoDAO{

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
		$sql = 'SELECT * FROM bono WHERE bono_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM bono';
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
		$sql = 'SELECT * FROM bono ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $bono_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($bono_id){
		$sql = 'DELETE FROM bono WHERE bono_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($bono_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto Bono bono
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($bono){
		$sql = 'INSERT INTO bono (codigo, bonusplanid, fecha_ini, fecha_fin, tipo, descripcion, usucrea_id, fecha_crea, mandante, dias_expira, usumodif_id, fecha_modif, owner) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($bono->codigo);
		$sqlQuery->set($bono->bonusplanid);
		$sqlQuery->set($bono->fechaIni);
		$sqlQuery->set($bono->fechaFin);
		$sqlQuery->set($bono->tipo);
		$sqlQuery->set($bono->descripcion);
		$sqlQuery->set($bono->usucreaId);
		$sqlQuery->set($bono->fechaCrea);
		$sqlQuery->set($bono->mandante);
		$sqlQuery->set($bono->diasExpira);
		$sqlQuery->set($bono->usumodifId);
		$sqlQuery->set($bono->fechaModif);
		$sqlQuery->set($bono->owner);

		$id = $this->executeInsert($sqlQuery);	
		$bono->bonoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto Bono bono
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($bono){
		$sql = 'UPDATE bono SET codigo = ?, bonusplanid = ?, fecha_ini = ?, fecha_fin = ?, tipo = ?, descripcion = ?, usucrea_id = ?, fecha_crea = ?, mandante = ?, dias_expira = ?, usumodif_id = ?, fecha_modif = ?, owner = ? WHERE bono_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($bono->codigo);
		$sqlQuery->set($bono->bonusplanid);
		$sqlQuery->set($bono->fechaIni);
		$sqlQuery->set($bono->fechaFin);
		$sqlQuery->set($bono->tipo);
		$sqlQuery->set($bono->descripcion);
		$sqlQuery->set($bono->usucreaId);
		$sqlQuery->set($bono->fechaCrea);
		$sqlQuery->set($bono->mandante);
		$sqlQuery->set($bono->diasExpira);
		$sqlQuery->set($bono->usumodifId);
		$sqlQuery->set($bono->fechaModif);
		$sqlQuery->set($bono->owner);

		$sqlQuery->set($bono->bonoId);
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
		$sql = 'DELETE FROM bono';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Codigo sea igual al valor pasado como parámetro
     *
     * @param String $value Codigo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByCodigo($value){
		$sql = 'SELECT * FROM bono WHERE codigo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Bonusplanid sea igual al valor pasado como parámetro
     *
     * @param String $value Bonusplanid requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByBonusplanid($value){
		$sql = 'SELECT * FROM bono WHERE bonusplanid = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaIni sea igual al valor pasado como parámetro
     *
     * @param String $value FechaIni requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaIni($value){
		$sql = 'SELECT * FROM bono WHERE fecha_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaFin sea igual al valor pasado como parámetro
     *
     * @param String $value FechaFin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaFin($value){
		$sql = 'SELECT * FROM bono WHERE fecha_fin = ?';
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
	public function queryByTipo($value){
		$sql = 'SELECT * FROM bono WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function queryByDescripcion($value){
		$sql = 'SELECT * FROM bono WHERE descripcion = ?';
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
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM bono WHERE usucrea_id = ?';
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
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM bono WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Mandante sea igual al valor pasado como parámetro
     *
     * @param String $value Mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM bono WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna DiasExpira sea igual al valor pasado como parámetro
     *
     * @param String $value DiasExpira requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByDiasExpira($value){
		$sql = 'SELECT * FROM bono WHERE dias_expira = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM bono WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaModif sea igual al valor pasado como parámetro
     *
     * @param String $value FechaModif requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM bono WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Owner sea igual al valor pasado como parámetro
     *
     * @param String $value Owner requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByOwner($value){
		$sql = 'SELECT * FROM bono WHERE owner = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de bonos 'Bono'
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
    public function queryBonosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {


        $where = " where 1=1 ";

        $Helpers = new Helpers();

        if($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach($rules as $rule)
            {
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

        $sql = "SELECT count(*) count FROM bono  INNER JOIN usuario ON (usuario.usuario_id = bono.usuario_id) " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM bono  INNER JOIN usuario ON (usuario.usuario_id = bono.usuario_id)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Codigo sea igual al valor pasado como parámetro
     *
     * @param String $value Codigo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByCodigo($value){
		$sql = 'DELETE FROM bono WHERE codigo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Bonusplanid sea igual al valor pasado como parámetro
     *
     * @param String $value Bonusplanid requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByBonusplanid($value){
		$sql = 'DELETE FROM bono WHERE bonusplanid = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaIni sea igual al valor pasado como parámetro
     *
     * @param String $value FechaIni requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaIni($value){
		$sql = 'DELETE FROM bono WHERE fecha_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaFin sea igual al valor pasado como parámetro
     *
     * @param String $value FechaFin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaFin($value){
		$sql = 'DELETE FROM bono WHERE fecha_fin = ?';
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
	public function deleteByTipo($value){
		$sql = 'DELETE FROM bono WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
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
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM bono WHERE descripcion = ?';
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
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM bono WHERE usucrea_id = ?';
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
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM bono WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Mandante sea igual al valor pasado como parámetro
     *
     * @param String $value Mandante requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM bono WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna DiasExpira sea igual al valor pasado como parámetro
     *
     * @param String $value DiasExpira requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByDiasExpira($value){
		$sql = 'DELETE FROM bono WHERE dias_expira = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM bono WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaModif sea igual al valor pasado como parámetro
     *
     * @param String $value FechaModif requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM bono WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Owner sea igual al valor pasado como parámetro
     *
     * @param String $value Owner requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByOwner($value){
		$sql = 'DELETE FROM bono WHERE owner = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Crear y devolver un objeto del tipo Bono
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $Bono Bono
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$bono = new Bono();
		
		$bono->bonoId = $row['bono_id'];
		$bono->codigo = $row['codigo'];
		$bono->bonusplanid = $row['bonusplanid'];
		$bono->fechaIni = $row['fecha_ini'];
		$bono->fechaFin = $row['fecha_fin'];
		$bono->tipo = $row['tipo'];
		$bono->descripcion = $row['descripcion'];
		$bono->usucreaId = $row['usucrea_id'];
		$bono->fechaCrea = $row['fecha_crea'];
		$bono->mandante = $row['mandante'];
		$bono->diasExpira = $row['dias_expira'];
		$bono->usumodifId = $row['usumodif_id'];
		$bono->fechaModif = $row['fecha_modif'];
		$bono->owner = $row['owner'];

		return $bono;
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