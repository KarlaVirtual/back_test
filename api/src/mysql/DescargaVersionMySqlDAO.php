<?php namespace Backend\mysql;

use Backend\dto\DescargaVersion;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\sql\Transaction;

/** 
 * Clase DescargaVersionMySqlDAO
 * Clase provee las consultas a la base de datos de la tabla descarga_version
 */
class DescargaVersionMySqlDAO{
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
     * Constructor de la clase DescargaVersionMySqlDAO.
     *
     * @param Transaction|string $transaction Objeto de transacción o cadena vacía. Si se pasa una cadena vacía, se crea una nueva instancia de Transaction.
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
     * Carga una fila de la tabla descarga_version basada en el ID proporcionado.
     *
     * @param int $id El ID de la versión de descarga a cargar.
     * @return array La fila de la tabla descarga_version correspondiente al ID proporcionado.
     */
    public function load($id){
        $sql = 'SELECT * FROM descarga_version where descarga_version_id = ?';
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
        $sql = 'SELECT * FROM descarga_version';
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
        $sql = 'SELECT * FROM descarga_version ORDER BY'.$orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Elimina un registro de la tabla descarga_version basado en el ID proporcionado.
     *
     * @param int $id El ID del registro que se desea eliminar.
     * @return void
     */
    public function delete($id){
        $sql = 'DELETE FROM descarga_version WHERE descarga_version_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($sql);
    }


     /**
     * Insertar un registro en la base de datos
     *
     * @param Object descargaVersion 
     *
     * @return String $id resultado de la consulta
     *
     */

     public function insert($descargaVersion){

    
        $sql = 'INSERT INTO descarga_version (usuario_id,documento_id,version,fecha_crea,fecha_modif,url,encriptacion) VALUES (?,?,?,?,?,?,?)';

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($descargaVersion->usuarioId);
        $sqlQuery->set($descargaVersion->documentoId);
        $sqlQuery->set($descargaVersion->version);
        $sqlQuery->set($descargaVersion->fechaCrea);
        $sqlQuery->set($descargaVersion->fechaModif);
        $sqlQuery->set($descargaVersion->url);
        $sqlQuery->set($descargaVersion->encriptacion);
       
        $id = $this->executeInsert($sqlQuery);

        return $id;
    
    }

    /**
     * Actualiza un registro en la tabla descarga_version.
     *
     * @param Object $descargaVersion Objeto que contiene los datos de la versión de descarga a actualizar.
     * @return int Número de filas afectadas por la actualización.
     */
    public function update($descargaVersion)
    {

        $sql = 'UPDATE descarga_version SET usuario_id = ?, documento_id = ?,version = ?, fecha_crea = ?, fecha_modif = ?, url = ?, encriptacion = ? where descarga_version_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($descargaVersion->usuarioId);
        $sqlQuery->set($descargaVersion->documentoId);
        $sqlQuery->set($descargaVersion->version);
        $sqlQuery->set($descargaVersion->fechaCrea);
        $sqlQuery->set($descargaVersion->fechaModif);
        $sqlQuery->set($descargaVersion->url);
        $sqlQuery->set($descargaVersion->encriptacion);

        $sqlQuery->set($descargaVersion->descargaVersionId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Consulta registros en la tabla descarga_version por usuario_id.
     *
     * @param int $usuarioId El ID del usuario.
     * @return array Lista de registros que coinciden con el usuario_id.
     */
    public function queryByusuario($usuarioId)
    {
        $sql = 'SELECT * FROM descarga_version where usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioId);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros en la tabla descarga_version por documento_id.
     *
     * @param int $idDocument El ID del documento.
     * @return array Lista de registros que coinciden con el documento_id.
     */
    public function queryBydocument($idDocument)
    {
        $sql = 'SELECT * FROM descarga_version where document_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($idDocument);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros en la tabla descarga_version por versión.
     *
     * @param string $version La versión de la descarga.
     * @return array Lista de registros que coinciden con la versión.
     */
    public function queryByVersion($version)
    {
        $sql = 'SELECT * FROM descarga_version where version = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($version);
        return $this->getList($sql);
    }

    /**
     * Consulta registros en la tabla descarga_version por documento_id y versión.
     *
     * @param int $idDocument El ID del documento.
     * @param string $version La versión de la descarga.
     * @return array Lista de registros que coinciden con el documento_id y la versión.
     */
    public function queryByDocumentAndVersion($idDocument, $version)
    {
        $sql = 'SELECT * FROM descarga_version where documento_id = ? and version = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($idDocument, $version);
        return $this->getList($sqlQuery);
    }

    /** 
     * realizar una consulta a la tabla descarga_version de manera personalizada
     * 
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
     * 
     */
       public function queryDescargaVersionCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping){

        
        $where = "where 1= 1";

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

                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $sql = 'SELECT COUNT(*) FROM descarga_version'.' '. $where;

        $sqlQuery = new SqlQuery($sql);
        
        $count = $this->execute2($sqlQuery);

        $sql = "SELECT " . $select . " FROM descarga_version INNER JOIN descarga ON (descarga_version.documento_id = descarga.descarga_id)".' '. $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;

    }


    /**
     *  funcion para retornar un array de tipo asociativo  
     * 
     */


     protected function readRow($row){
        $descargaVersion = new descargaVersion();
        $descargaVersion->usuarioId = $row['usuario_id'];
        $descargaVersion->documentoId = $row["documento_id"];
        $descargaVersion->version = $row["version"];
        $descargaVersion->fechaCrea = $row["fecha_crea"];
        $descargaVersion->fechaModif = $row["fecha_modif"];


        return $descargaVersion;

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
		if(count($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);		
	}

    /**
     * Obtiene una lista de resultados a partir de una consulta SQL.
     *
     * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
     * @return array Un arreglo de resultados obtenidos de la consulta.
     */
    protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		$ret = array();
		for($i=0;$i<count($tab);$i++){
			$ret[$i] = $this->readRow($tab[$i]);
		}
		return $ret;
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