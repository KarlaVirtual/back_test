<?php namespace Backend\mysql;
 use Backend\dao\ContactoComercialDAO;
 use Backend\dto\ContactoComercial;
 use Backend\sql\QueryExecutor;
 use Backend\sql\SqlQuery;
 use Backend\sql\Transaction;

/**
* Clase 'ContactoComercialMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'ContactoComercial'
*
* Ejemplo de uso:
* $ContactoComercialMySqlDAO = new ContactoComercialMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class ContactoComercialMySqlDAO implements ContactoComercialDAO{

    /** Objeto vincula una conexión de la base de datos con el objeto correspondiente
     * @var Transaction $transaction
     */
    private $transaction;


    /**
     * Obtener la transacción actual.
     *
     * @return Transaction La transacción actual.
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Establecer una nueva transacción.
     *
     * @param Transaction $transaction La nueva transacción.
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * TransaccionProductoMySqlDAO constructor.
     * @param $transaction
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
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM contacto_comercial WHERE contactocom_id = ?';
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
		$sql = 'SELECT * FROM contacto_comercial';
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
		$sql = 'SELECT * FROM contacto_comercial ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $contactocom_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($contactocom_id){
		$sql = 'DELETE FROM contacto_comercial WHERE contactocom_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($contactocom_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object contactoComercial contactoComercial
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($contactoComercial){
		$sql = 'INSERT INTO contacto_comercial (nombres, apellidos, empresa, email, skype, pais_id, depto_id, direccion, telefono, observacion, estado, fecha_crea, fecha_modif, usumodif_id, mandante, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($contactoComercial->nombres);
		$sqlQuery->set($contactoComercial->apellidos);
		$sqlQuery->set($contactoComercial->empresa);
		$sqlQuery->set($contactoComercial->email);
		$sqlQuery->set($contactoComercial->skype);
		$sqlQuery->set($contactoComercial->paisId);
		$sqlQuery->set($contactoComercial->deptoId);
		$sqlQuery->set($contactoComercial->direccion);
		$sqlQuery->set($contactoComercial->telefono);
		$sqlQuery->set($contactoComercial->observacion);
		$sqlQuery->set($contactoComercial->estado);
		$sqlQuery->set($contactoComercial->fechaCrea);
		$sqlQuery->set($contactoComercial->fechaModif);
		$sqlQuery->set($contactoComercial->usumodifId);
		$sqlQuery->set($contactoComercial->mandante);
		$sqlQuery->set($contactoComercial->tipo);

		$id = $this->executeInsert($sqlQuery);	
		$contactoComercial->contactocomId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object contactoComercial contactoComercial
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($contactoComercial){
		$sql = 'UPDATE contacto_comercial SET nombres = ?, apellidos = ?, empresa = ?, email = ?, skype = ?, pais_id = ?, depto_id = ?, direccion = ?, telefono = ?, observacion = ?, estado = ?, fecha_crea = ?, fecha_modif = ?, usumodif_id = ?, mandante = ?, tipo = ? WHERE contactocom_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($contactoComercial->nombres);
		$sqlQuery->set($contactoComercial->apellidos);
		$sqlQuery->set($contactoComercial->empresa);
		$sqlQuery->set($contactoComercial->email);
		$sqlQuery->set($contactoComercial->skype);
		$sqlQuery->set($contactoComercial->paisId);
		$sqlQuery->set($contactoComercial->deptoId);
		$sqlQuery->set($contactoComercial->direccion);
		$sqlQuery->set($contactoComercial->telefono);
		$sqlQuery->set($contactoComercial->observacion);
		$sqlQuery->set($contactoComercial->estado);
		$sqlQuery->set($contactoComercial->fechaCrea);
		$sqlQuery->set($contactoComercial->fechaModif);
		$sqlQuery->set($contactoComercial->usumodifId);
		$sqlQuery->set($contactoComercial->mandante);
		$sqlQuery->set($contactoComercial->tipo);

		$sqlQuery->set($contactoComercial->contactocomId);
		return $this->executeUpdate($sqlQuery);
	}


    /**
     * Realiza una consulta personalizada sobre los contactos comerciales.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros para la paginación.
     * @param string $filters Filtros en formato JSON para aplicar a la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * 
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryContactoComercialesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM contacto_comercial ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM contacto_comercial INNER JOIN pais ON (pais.pais_id = contacto_comercial.pais_id) INNER JOIN departamento ON (departamento.depto_id = contacto_comercial.depto_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
		$sql = 'DELETE FROM contacto_comercial';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombres sea igual al valor pasado como parámetro
     *
     * @param String $value nombres requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByNombres($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE nombres = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna apellidos sea igual al valor pasado como parámetro
     *
     * @param String $value apellidos requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByApellidos($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE apellidos = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna empresa sea igual al valor pasado como parámetro
     *
     * @param String $value empresa requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEmpresa($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE empresa = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna email sea igual al valor pasado como parámetro
     *
     * @param String $value email requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEmail($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE email = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna skype sea igual al valor pasado como parámetro
     *
     * @param String $value skype requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryBySkype($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE skype = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPaisId($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna depto_id sea igual al valor pasado como parámetro
     *
     * @param String $value depto_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDeptoId($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna direccion sea igual al valor pasado como parámetro
     *
     * @param String $value direccion requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDireccion($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE direccion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna telefono sea igual al valor pasado como parámetro
     *
     * @param String $value telefono requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTelefono($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE telefono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna observacion sea igual al valor pasado como parámetro
     *
     * @param String $value observacion requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByObservacion($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE observacion = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE estado = ?';
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
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE fecha_crea = ?';
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
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE fecha_modif = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM contacto_comercial WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}








    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombres sea igual al valor pasado como parámetro
     *
     * @param String $value nombres requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByNombres($value){
		$sql = 'DELETE FROM contacto_comercial WHERE nombres = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna apellidos sea igual al valor pasado como parámetro
     *
     * @param String $value apellidos requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByApellidos($value){
		$sql = 'DELETE FROM contacto_comercial WHERE apellidos = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna empresa sea igual al valor pasado como parámetro
     *
     * @param String $value empresa requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEmpresa($value){
		$sql = 'DELETE FROM contacto_comercial WHERE empresa = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna email sea igual al valor pasado como parámetro
     *
     * @param String $value email requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEmail($value){
		$sql = 'DELETE FROM contacto_comercial WHERE email = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna skype sea igual al valor pasado como parámetro
     *
     * @param String $value skype requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteBySkype($value){
		$sql = 'DELETE FROM contacto_comercial WHERE skype = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPaisId($value){
		$sql = 'DELETE FROM contacto_comercial WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna depto_id sea igual al valor pasado como parámetro
     *
     * @param String $value depto_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDeptoId($value){
		$sql = 'DELETE FROM contacto_comercial WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna direccion sea igual al valor pasado como parámetro
     *
     * @param String $value direccion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDireccion($value){
		$sql = 'DELETE FROM contacto_comercial WHERE direccion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna telefono sea igual al valor pasado como parámetro
     *
     * @param String $value telefono requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTelefono($value){
		$sql = 'DELETE FROM contacto_comercial WHERE telefono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna observacion sea igual al valor pasado como parámetro
     *
     * @param String $value observacion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByObservacion($value){
		$sql = 'DELETE FROM contacto_comercial WHERE observacion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function deleteByEstado($value){
		$sql = 'DELETE FROM contacto_comercial WHERE estado = ?';
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
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM contacto_comercial WHERE fecha_crea = ?';
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
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM contacto_comercial WHERE fecha_modif = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM contacto_comercial WHERE usumodif_id = ?';
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
	public function deleteByMandante($value){
		$sql = 'DELETE FROM contacto_comercial WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipo($value){
		$sql = 'DELETE FROM contacto_comercial WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	






    /**
     * Crear y devolver un objeto del tipo Competencia
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $consecutivo Consecutivo
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$contactoComercial = new ContactoComercial();
		
		$contactoComercial->contactocomId = $row['contactocom_id'];
		$contactoComercial->nombres = $row['nombres'];
		$contactoComercial->apellidos = $row['apellidos'];
		$contactoComercial->empresa = $row['empresa'];
		$contactoComercial->email = $row['email'];
		$contactoComercial->skype = $row['skype'];
		$contactoComercial->paisId = $row['pais_id'];
		$contactoComercial->deptoId = $row['depto_id'];
		$contactoComercial->direccion = $row['direccion'];
		$contactoComercial->telefono = $row['telefono'];
		$contactoComercial->observacion = $row['observacion'];
		$contactoComercial->estado = $row['estado'];
		$contactoComercial->fechaCrea = $row['fecha_crea'];
		$contactoComercial->fechaModif = $row['fecha_modif'];
		$contactoComercial->usumodifId = $row['usumodif_id'];
		$contactoComercial->mandante = $row['mandante'];
		$contactoComercial->tipo = $row['tipo'];

		return $contactoComercial;
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
     * Execute2 sql query
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