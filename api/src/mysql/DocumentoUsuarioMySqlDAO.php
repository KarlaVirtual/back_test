<?php namespace Backend\mysql;
use Backend\dao\DocumentoUsuarioDAO;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Helpers;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/**
* Clase 'DocumentoUsuarioMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'DocumentoUsuario'
*
* Ejemplo de uso:
* $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class DocumentoUsuarioMySqlDAO implements DocumentoUsuarioDAO{

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
     * @param String $descarga_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM documento_usuario WHERE docusuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}


    /**
     * Obtiene un registro de la tabla documento_usuario basado en el external_id proporcionado.
     *
     * @param mixed $id El valor del external_id para buscar el registro.
     * @return array El registro encontrado en la tabla documento_usuario.
     */
    public function externalId($id){
        $sql = 'SELECT * FROM documento_usuario WHERE external_id = ?';
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
		$sql = 'SELECT * FROM documento_usuario';
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
		$sql = 'SELECT * FROM documento_usuario ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $docusuarioId llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($docusuarioId){
		$sql = 'DELETE FROM documento_usuario WHERE docusuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($docusuarioId);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object documentoUsuario documentoUsuario
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($documentoUsuario){
		$sql = 'INSERT INTO documento_usuario (usuario_id,documento_id,version,estado_aprobacion,external_id) VALUES (?, ?, ?, ?,?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($documentoUsuario->usuarioId);
        $sqlQuery->set($documentoUsuario->documentoId);
        $sqlQuery->set($documentoUsuario->version);
        $sqlQuery->set($documentoUsuario->estadoAprobacion);
        $sqlQuery->set($documentoUsuario->externalId);

		$id = $this->executeInsert($sqlQuery);	
		$documentoUsuario->docusuarioId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object documentoUsuario documentoUsuario
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($documentoUsuario){
		$sql = 'UPDATE documento_usuario SET usuario_id = ?, documento_id=?,version=?,estado_aprobacion=?,external_id= ? WHERE docusuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($documentoUsuario->usuarioId);
        $sqlQuery->set($documentoUsuario->documentoId);
        $sqlQuery->set($documentoUsuario->version);
        $sqlQuery->set($documentoUsuario->estadoAprobacion);
        $sqlQuery->set($documentoUsuario->externalId);

		$sqlQuery->set($documentoUsuario->docusuarioId);
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
		$sql = 'DELETE FROM documento_usuario';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM documento_usuario WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna documento_id sea igual al valor pasado como parámetro
     *
     * @param String $value documento_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByTipo($value){
        $sql = 'SELECT * FROM documento_usuario WHERE documento_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener la información de descarga pertinente al usuario
     *
     * @return String usuarioId usuarioId
     *
     */
    public function queryForDocumentosNoProcesados($usuario_id,$plataforma=0)
    {

        $sql = "select descarga.* FROM descarga LEFT JOIN documento_usuario ON (documento_usuario.documento_id = descarga.descarga_id AND documento_usuario.usuario_id =" . $usuario_id.") WHERE  documento_usuario.docusuario_id IN (
  SELECT MAX(documento_usuario.docusuario_id) 
  FROM documento_usuario GROUP BY documento_id
) AND (documento_usuario.estado_aprobacion!='A' OR documento_usuario.estado_aprobacion IS NULL) AND plataforma='".$plataforma."'";
        $sql = "select descarga.* FROM descarga INNER JOIN usuario ON (usuario.usuario_id =" . $usuario_id." and usuario.mandante = descarga.mandante and descarga.pais_id = usuario.pais_id) LEFT JOIN documento_usuario ON (documento_usuario.version = descarga.version AND documento_usuario.documento_id = descarga.descarga_id AND documento_usuario.usuario_id =" . $usuario_id.") WHERE
        (documento_usuario.estado_aprobacion!='A' OR documento_usuario.estado_aprobacion IS NULL) AND descarga.estado='A' AND descarga.plataforma='".$plataforma."'";


        $sqlQuery = new SqlQuery($sql);

        return $this->execute2($sqlQuery);
    }

    public function queryForDocumentosNoProcesadosPRO($usuario_id,$plataforma=0,$perfilId)
    {
        $sql = " select descarga.*
FROM descarga

INNER JOIN usuario ON (usuario.usuario_id =" . $usuario_id." and usuario.mandante = descarga.mandante )
INNER JOIN usuario_perfil ON (descarga.perfil_id = usuario_perfil.perfil_id and usuario.usuario_id=usuario_perfil.usuario_id)

         LEFT JOIN documento_usuario ON (documento_usuario.version = descarga.version AND
                                         documento_usuario.documento_id = descarga.descarga_id AND
                                         documento_usuario.usuario_id = usuario.usuario_id AND
                                         documento_usuario.estado_aprobacion = 'A')
WHERE
    (documento_usuario.docusuario_id IS NULL)
  AND descarga.plataforma='".$plataforma."'
  AND descarga.estado='A'
  AND descarga.perfil_id='".$perfilId."'
  group by  descarga.descarga_id";

        $sqlQuery = new SqlQuery($sql);

        return $this->execute2($sqlQuery);
    }

    public function queryForDocumentosPendientesPorProcesar($usuario_id,$plataforma=0,$perfilId)
    {
        $sql = "SELECT
	descarga_version.*,
	descarga.*
FROM
	descarga_version INNER JOIN descarga ON
	(descarga_version.documento_id = descarga.descarga_id) INNER JOIN usuario ON (usuario.usuario_id =" . $usuario_id." and usuario.mandante = descarga.mandante ) INNER JOIN usuario_perfil ON
	(descarga.perfil_id = usuario_perfil.perfil_id
		and usuario.usuario_id = usuario_perfil.usuario_id) LEFT JOIN documento_usuario ON
	(documento_usuario.version = descarga_version.version
		AND documento_usuario.documento_id = descarga_version.documento_id
		AND
           documento_usuario.usuario_id = usuario.usuario_id
		AND
           documento_usuario.estado_aprobacion = 'A') WHERE
    (documento_usuario.docusuario_id IS NULL)
  AND descarga.plataforma='".$plataforma."'
  AND descarga.estado='A'
  AND descarga.perfil_id='".$perfilId."'
  group by  descarga_version.documento_id";


        $sqlQuery = new SqlQuery($sql);

        return $this->execute2($sqlQuery);

    }






    /**
    * Realizar una consulta en la tabla de documento_usuario 'DocumentosUsuario'
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
    * @return Array resultado de la consulta
    *
    */
    public function queryDocumentosUsuarioCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $Helpers = new Helpers();
        $where = " where 1=1 ";


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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM documento_usuario INNER JOIN descarga ON (documento_usuario.documento_id = descarga.descarga_id)  INNER JOIN usuario ON (documento_usuario.usuario_id = usuario.usuario_id) INNER JOIN descarga_version ON (documento_usuario.version = descarga_version.version AND descarga.descarga_id = descarga_version.documento_id) '. $where;



        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = 'SELECT ' . $select . 'FROM documento_usuario INNER JOIN descarga ON (documento_usuario.documento_id = descarga.descarga_id) INNER JOIN usuario ON (documento_usuario.usuario_id = usuario.usuario_id) INNER JOIN descarga_version ON (documento_usuario.version = descarga_version.version AND descarga.descarga_id = descarga_version.documento_id) '. $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        if($_ENV["debugFixed2"]){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuario_id y documento_id sean iguales
     * a los valores que se pasen como parámetros
     *
     * @param String $usuarioid usuarioid requerido
     * @param String $value documento_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndDocumentoId($usuarioid,$value){
        $sql = 'SELECT * FROM documento_usuario WHERE usuario_id = ? AND documento_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioid);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByUsuarioId($value){
		$sql = 'DELETE FROM documento_usuario WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Crear y devolver un objeto del tipo CupoLog
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $DocumentoUsuario DocumentoUsuario
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$documentoUsuario = new DocumentoUsuario();
		
		$documentoUsuario->docusuarioId = $row['docusuario_id'];
		$documentoUsuario->usuarioId = $row['usuario_id'];
        $documentoUsuario->documentoId = $row['documento_id'];
        $documentoUsuario->version = $row['version'];
        $documentoUsuario->estadoAprobacion = $row['estado_aprobacion'];
        $documentoUsuario->ruta = $row['ruta'];
        $documentoUsuario->externalId = $row['external_id'];

		return $documentoUsuario;
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
	protected function execute($sqlQuery){
		return QueryExecutor::execute($this->transaction,$sqlQuery);
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