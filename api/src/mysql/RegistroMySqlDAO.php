<?php namespace Backend\mysql;
use Backend\dao\RegistroDAO;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\dto\Registro;
use Exception;
use Backend\dto\Helpers;

/**
* Clase 'RegistroMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Registro'
* 
* Ejemplo de uso: 
* $RegistroMySqlDAO = new RegistroMySqlDAO();
*	
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class RegistroMySqlDAO implements RegistroDAO
{


    /**
     * Clave de encriptación
     *
     * @var string
     */
    private $claveEncrypt;


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
     * @param Objeto $transaction transacción
     *
     * @return void
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
    * @return void
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transaction="")
    {
		$this->claveEncrypt = base64_decode($_ENV['APP_PASSKEY']);
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
	 * Obtener todos los registros condicionados por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function load($id)
	{
		$sql = 'SELECT * FROM registro WHERE registro_id = ?';
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
	public function queryAll()
	{
		$sql = 'SELECT * FROM registro';
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
		$sql = 'SELECT * FROM registro ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $registro_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($registro_id)
	{
		$sql = 'DELETE FROM registro WHERE registro_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($registro_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto $registro registro
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($registro)
	{
//		$sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
//		$this->execute2($sqlQuery2);
		$sql = "SET @@SESSION.block_encryption_mode = 'aes-128-ecb';INSERT INTO registro (nombre, email, puntoventa_id, estado, usuario_id, clave_activa, creditos, creditos_base, celular, ciudad, creditos_ant, creditos_base_ant, ciudad_id, mandante, casino, casino_base, fecha_casino, preregistro_id, creditos_bono, creditos_bono_ant, ocupacion, rangoingreso_id, origen_fondos, paisnacim_id, cedula, nombre1, nombre2, apellido1, apellido2, sexo, direccion, telefono, ciudnacim_id, nacionalidad_id, estado_valida, usuvalida_id, fecha_valida, dir_ip, tipo_doc, ciudexped_id, fecha_exped, codigo_postal, ocupacion_id, origenfondos_id,afiliador_id,saldo_bonos_liberados,saldo_casino_bonos,banner_id,link_id,codpromocional_id) VALUES (?, ?, ?, ?, ?, aes_encrypt(?,' . $this->claveEncrypt . '), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?,?, ?,?,?)";
		$sqlQuery = new SqlQuery($sql);

		$Helpers = new Helpers();
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data($registro->email));
		$sqlQuery->set($registro->puntoventaId);
		$sqlQuery->set($registro->estado);
		$sqlQuery->set($registro->usuarioId);
		$sqlQuery->set($registro->claveActiva);
		$sqlQuery->set($registro->creditos);
		$sqlQuery->set($registro->creditosBase);
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->encode_data($registro->celular));
		$sqlQuery->set($registro->ciudad);
		$sqlQuery->set($registro->creditosAnt);
		$sqlQuery->set($registro->creditosBaseAnt);

		if($registro->ciudadId == ''){
			$registro->ciudadId = '0';
		}

		$sqlQuery->set($registro->ciudadId);
		$sqlQuery->set($registro->mandante);
		$sqlQuery->set($registro->casino);
		$sqlQuery->set($registro->casinoBase);
		$sqlQuery->set($registro->fechaCasino);
		$sqlQuery->set($registro->preregistroId);
		$sqlQuery->set($registro->creditosBono);
		$sqlQuery->set($registro->creditosBonoAnt);
		$sqlQuery->set($registro->ocupacion);
		$sqlQuery->set($registro->rangoingresoId);
		$sqlQuery->set($registro->origenFondos);
		$sqlQuery->set($registro->paisnacimId);
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT', true)->encode_data($registro->cedula));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre1));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre2));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->encode_data($registro->apellido1));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->encode_data($registro->apellido2));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_GENDER', true)->encode_data($registro->sexo));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->encode_data($registro->direccion));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->encode_data($registro->telefono));
		$sqlQuery->set($registro->ciudnacimId);
		$sqlQuery->set($registro->nacionalidadId);
		$sqlQuery->set($registro->estadoValida);
		$sqlQuery->set($registro->usuvalidaId);
		$sqlQuery->set($registro->fechaValida);
		$sqlQuery->set($registro->dirIp);
		$sqlQuery->set($registro->tipoDoc);
		$sqlQuery->set($registro->ciudexpedId);
		$sqlQuery->set($registro->fechaExped);
		if(strlen($registro->codigoPostal)>15){
			$registro->codigoPostal = substr($registro->codigoPostal, 0, 14);
		}
		$sqlQuery->set($registro->codigoPostal);
		$sqlQuery->set($registro->ocupacionId);
        $sqlQuery->set($registro->origenfondosId);
        $sqlQuery->set($registro->afiliadorId);

        if($registro->saldoBonosLiberados == ""){
            $registro->saldoBonosLiberados=0;
        }

        if($registro->saldoCasinoBonos == ""){
            $registro->saldoCasinoBonos=0;
        }


        $sqlQuery->set($registro->saldoBonosLiberados);
        $sqlQuery->set($registro->saldoCasinoBonos);



        if($registro->bannerId == ""){
            $registro->bannerId=0;
        }

        if($registro->linkId == ""){
            $registro->linkId=0;
        }


        if($registro->codpromocionalId == ""){
            $registro->codpromocionalId=0;
        }

		if(!is_numeric($registro->bannerId)){
			$registro->bannerId = '0';
		}
        $sqlQuery->set($registro->bannerId);

		if(!is_numeric($registro->linkId)){
			$registro->linkId = '0';
		}

		$sqlQuery->set($registro->linkId);

        $sqlQuery->set($registro->codpromocionalId);


        $id = $this->executeInsert($sqlQuery);
		$registro->registroId = $id;
		$sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
		$this->execute2($sqlQuery2);
		return $id;
	}


    /**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto $registro registro
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($registro)
	{
		$sql = 'UPDATE registro SET nombre = ?, email = ?, puntoventa_id = ?, estado = ?, usuario_id = ?, celular = ?, ciudad = ?, ciudad_id = ?, mandante = ?, casino = ?, casino_base = ?, fecha_casino = ?, preregistro_id = ?, ocupacion = ?, rangoingreso_id = ?, origen_fondos = ?, paisnacim_id = ?, cedula = ?, nombre1 = ?, nombre2 = ?, apellido1 = ?, apellido2 = ?, sexo = ?, direccion = ?, telefono = ?, ciudnacim_id = ?, nacionalidad_id = ?, estado_valida = ?, usuvalida_id = ?, fecha_valida = ?, dir_ip = ?, tipo_doc = ?, ciudexped_id = ?, fecha_exped = ?, codigo_postal = ?, ocupacion_id = ?, origenfondos_id = ?, afiliador_id= ?, saldo_bonos_liberados= ?,saldo_casino_bonos= ? WHERE registro_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$Helpers = new Helpers();
		if(strlen($registro->nombre)>100){
			$registro->nombre = substr($registro->nombre, 0, 99);
		}
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre));

		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data($registro->email));
		$sqlQuery->set($registro->puntoventaId);
		$sqlQuery->set($registro->estado);
		$sqlQuery->set($registro->usuarioId);
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->encode_data($registro->celular));
		$sqlQuery->set($registro->ciudad);
		if($registro->ciudadId ==''){
			$registro->ciudadId='0';
		}
		$sqlQuery->set($registro->ciudadId);
		$sqlQuery->set($registro->mandante);
		$sqlQuery->set($registro->casino);
		$sqlQuery->set($registro->casinoBase);
		$sqlQuery->set($registro->fechaCasino);
		$sqlQuery->set($registro->preregistroId);
		$sqlQuery->set($registro->ocupacion);
		$sqlQuery->set($registro->rangoingresoId);
		$sqlQuery->set($registro->origenFondos);
		$sqlQuery->set($registro->paisnacimId);
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT', true)->encode_data($registro->cedula));
		if(strlen($registro->nombre1)>20){
			$registro->nombre1 = substr($registro->nombre1, 0, 19);
		}
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre1));
		if(strlen($registro->nombre2)>20){
			$registro->nombre2 = substr($registro->nombre2, 0, 19);
		}
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre2));

		if(strlen($registro->apellido1)>20){
			$registro->apellido1 = substr($registro->apellido1, 0, 19);
		}
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->encode_data($registro->apellido1));
		if(strlen($registro->apellido2)>20){
			$registro->apellido2 = substr($registro->apellido2, 0, 19);
		}
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->encode_data($registro->apellido2));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_GENDER', true)->encode_data($registro->sexo));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->encode_data($registro->direccion));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->encode_data($registro->telefono));
		$sqlQuery->set($registro->ciudnacimId);
		if($registro->nacionalidadId ==''){
			$registro->nacionalidadId='0';
		}
		$sqlQuery->set($registro->nacionalidadId);
		$sqlQuery->set($registro->estadoValida);
		$sqlQuery->set($registro->usuvalidaId);
		$sqlQuery->set($registro->fechaValida);
		$sqlQuery->set($registro->dirIp);
		$sqlQuery->set($registro->tipoDoc);
		$sqlQuery->set($registro->ciudexpedId);
		$sqlQuery->set($registro->fechaExped);
		if(strlen($registro->codigoPostal)>15){
			$registro->codigoPostal = substr($registro->codigoPostal, 0, 14);
		}
		$sqlQuery->set($registro->codigoPostal);
		$sqlQuery->set($registro->ocupacionId);
        $sqlQuery->set($registro->origenfondosId);

		if($registro->afiliadorId == ""){
			$registro->afiliadorId=0;
		}
        $sqlQuery->set($registro->afiliadorId);

        if($registro->saldoBonosLiberados == ""){
            $registro->saldoBonosLiberados=0;
        }

        if($registro->saldoCasinoBonos == ""){
            $registro->saldoCasinoBonos=0;
        }


        $sqlQuery->setSIN($registro->saldoBonosLiberados);
        $sqlQuery->setSIN($registro->saldoCasinoBonos);

		$sqlQuery->set($registro->registroId);
		return $this->executeUpdate($sqlQuery);
	}


    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto $registro registro
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function updateBK($registro)
    {
        $sql = 'UPDATE registro SET nombre = ?, email = ?, puntoventa_id = ?, estado = ?, usuario_id = ?, creditos = ?, creditos_base = ?, celular = ?, ciudad = ?, creditos_ant = ?, creditos_base_ant = ?, ciudad_id = ?, mandante = ?, casino = ?, casino_base = ?, fecha_casino = ?, preregistro_id = ?, creditos_bono = ?, creditos_bono_ant = ?, ocupacion = ?, rangoingreso_id = ?, origen_fondos = ?, paisnacim_id = ?, cedula = ?, nombre1 = ?, nombre2 = ?, apellido1 = ?, apellido2 = ?, sexo = ?, direccion = ?, telefono = ?, ciudnacim_id = ?, nacionalidad_id = ?, estado_valida = ?, usuvalida_id = ?, fecha_valida = ?, dir_ip = ?, tipo_doc = ?, ciudexped_id = ?, fecha_exped = ?, codigo_postal = ?, ocupacion_id = ?, origenfondos_id = ?, afiliador_id= ?, saldo_bonos_liberados= ?,saldo_casino_bonos= ? WHERE registro_id = ?';
        $sqlQuery = new SqlQuery($sql);

		$Helpers = new Helpers();

		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data($registro->email));
        $sqlQuery->set($registro->puntoventaId);
        $sqlQuery->set($registro->estado);
        $sqlQuery->set($registro->usuarioId);
        $sqlQuery->setSIN($registro->creditos);
        $sqlQuery->setSIN($registro->creditosBase);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->encode_data($registro->celular));
        $sqlQuery->set($registro->ciudad);
        $sqlQuery->set($registro->creditosAnt);
        $sqlQuery->set($registro->creditosBaseAnt);
        $sqlQuery->set($registro->ciudadId);
        $sqlQuery->set($registro->mandante);
        $sqlQuery->set($registro->casino);
        $sqlQuery->set($registro->casinoBase);
        $sqlQuery->set($registro->fechaCasino);
        $sqlQuery->set($registro->preregistroId);
        $sqlQuery->setSIN($registro->creditosBono);
        $sqlQuery->set($registro->creditosBonoAnt);
        $sqlQuery->set($registro->ocupacion);
        $sqlQuery->set($registro->rangoingresoId);
        $sqlQuery->set($registro->origenFondos);
        $sqlQuery->set($registro->paisnacimId);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT', true)->encode_data($registro->cedula));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre1));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($registro->nombre2));
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->encode_data($registro->apellido1));
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->encode_data($registro->apellido2));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_GENDER', true)->encode_data($registro->sexo));
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->encode_data($registro->direccion));
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->encode_data($registro->telefono));
        $sqlQuery->set($registro->ciudnacimId);
        $sqlQuery->set($registro->nacionalidadId);
        $sqlQuery->set($registro->estadoValida);
        $sqlQuery->set($registro->usuvalidaId);
        $sqlQuery->set($registro->fechaValida);
        $sqlQuery->set($registro->dirIp);
        $sqlQuery->set($registro->tipoDoc);
        $sqlQuery->set($registro->ciudexpedId);
        $sqlQuery->set($registro->fechaExped);
        $sqlQuery->set($registro->codigoPostal);
        $sqlQuery->set($registro->ocupacionId);
        $sqlQuery->set($registro->origenfondosId);
        $sqlQuery->set($registro->afiliadorId);

        if($registro->saldoBonosLiberados == ""){
            $registro->saldoBonosLiberados=0;
        }

        if($registro->saldoCasinoBonos == ""){
            $registro->saldoCasinoBonos=0;
        }


        $sqlQuery->setSIN($registro->saldoBonosLiberados);
        $sqlQuery->setSIN($registro->saldoCasinoBonos);

        $sqlQuery->set($registro->registroId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Editar un registro en la base de datos SOLO PARA CAMPOS DE SALDOS
     *
     * @param Objeto $registro registro
	 * @param Objeto $creditos creditos
	 * @param Objeto $creditos_base creditos_base
	 * @param Objeto $creditos_bono creditos_bono
	 * @param Objeto $saldo_bonos_liberados saldo_bonos_liberados
	 * @param Objeto $saldo_casino_bonos saldo_casino_bonos
	 * @param Objeto $withValidation withValidation
	 *
     * @return boolean $ resultado de la consulta
     *
     */
    public function updateBalance($registro,$creditos="",$creditos_base="",$creditos_bono="",$saldo_bonos_liberados="",$saldo_casino_bonos="",$withValidation=false)
    {
		//$withValidation=false;

        $fields="1=1";
        $where ="";

        if($creditos != ""){
            $fields .= ", creditos = creditos+'".$creditos."' ";
            $fields .= ", creditos_ant = creditos ";

			if($withValidation && floatval($creditos) <0){
                $where .= " AND ROUND(creditos+'".$creditos."',2) >= -0.1 ";
            }
        }

        if($creditos_base != ""){
            $fields .= ", creditos_base = creditos_base+'".$creditos_base."' ";
            $fields .= ", creditos_base_ant = creditos_base ";

			if($withValidation  && floatval($creditos_base) <0){
                $where .= " AND ROUND(creditos_base+'".$creditos_base."',2) >= -0.1 ";
            }
        }

        if($creditos_bono != ""){
            $fields .= ", creditos_bono = creditos_bono+'".$creditos_bono."' ";
            $fields .= ", creditos_bono_ant = creditos_bono ";

            if($withValidation){
                $where .= " AND creditos_bono+'".$creditos_bono."' >= -0.1 ";
            }

        }

        if($saldo_bonos_liberados != ""){
            $fields .= ", saldo_bonos_liberados = saldo_bonos_liberados+'".$saldo_bonos_liberados."' ";
        }

        if($saldo_casino_bonos != ""){
            $fields .= ", saldo_casino_bonos = saldo_casino_bonos+'".$saldo_casino_bonos."' ";
        }

        if($fields != "1=1"){
            $fields = str_replace("1=1,","",$fields);
            $sql = 'UPDATE registro SET ' . $fields.' WHERE registro_id = ? '.$where;

            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($registro->registroId);
            return $this->executeUpdate($sqlQuery);
        }
        return false;
    }

	/**
 	 * Eliminar todas los registros de la base de datos
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function clean()
	{
		$sql = 'DELETE FROM registro';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna nombre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByNombre($value)
	{
		$Helpers = new Helpers();
		$field = 'registro.nombre';
		$field2 = $Helpers->set_custom_field($field);
		$sql = "SELECT * FROM registro WHERE $field2 = ?";
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByEmail($value)
	{
		$Helpers = new Helpers();
		$field = 'registro.email';
		$field2 = $Helpers->set_custom_field($field);
		$sql = "SELECT * FROM registro WHERE $field2 = ?";

		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna puntoventa_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value puntoventa_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByPuntoventaId($value)
	{
		$sql = 'SELECT * FROM registro WHERE puntoventa_id = ?';
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
		$sql = 'SELECT * FROM registro WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna usuario_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value usuario_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByUsuarioId($value)
	{
		$sql = 'SELECT * FROM registro WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna clave_activa sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value clave_activa requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByClaveActiva($value)
	{
		$sql = 'SELECT * FROM registro WHERE clave_activa = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna creditos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCreditos($value)
	{
		$sql = 'SELECT * FROM registro WHERE creditos = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna creditos_base sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_base requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCreditosBase($value)
	{
		$sql = 'SELECT * FROM registro WHERE creditos_base = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna celular sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value celular requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCelular2($value)
	{
		$sql = 'SELECT * FROM registro WHERE celular = ?';
		$Helpers = new Helpers();
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->encode_data($value));
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ciudad sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ciudad requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCiudad($value)
	{
		$sql = 'SELECT * FROM registro WHERE ciudad = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna creditos_ant sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_ant requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCreditosAnt($value)
	{
		$sql = 'SELECT * FROM registro WHERE creditos_ant = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna creditos_base_ant sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_base_ant requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCreditosBaseAnt($value)
	{
		$sql = 'SELECT * FROM registro WHERE creditos_base_ant = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ciudad_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ciudad_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCiudadId($value)
	{
		$sql = 'SELECT * FROM registro WHERE ciudad_id = ?';
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByMandante($value)
	{
		$sql = 'SELECT * FROM registro WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna casino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value casino requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCasino($value)
	{
		$sql = 'SELECT * FROM registro WHERE casino = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna casino_base sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value casino_base requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCasinoBase($value)
	{
		$sql = 'SELECT * FROM registro WHERE casino_base = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_casino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_casino requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaCasino($value)
	{
		$sql = 'SELECT * FROM registro WHERE fecha_casino = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna preregistro_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value preregistro_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByPreregistroId($value)
	{
		$sql = 'SELECT * FROM registro WHERE preregistro_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna creditos_bono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_bono requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCreditosBono($value)
	{
		$sql = 'SELECT * FROM registro WHERE creditos_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna creditos_bono_ant sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_bono_ant requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCreditosBonoAnt($value)
	{
		$sql = 'SELECT * FROM registro WHERE creditos_bono_ant = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ocupacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ocupacion requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByOcupacion($value)
	{
		$sql = 'SELECT * FROM registro WHERE ocupacion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna rangoingreso_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value rangoingreso_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByRangoingresoId($value)
	{
		$sql = 'SELECT * FROM registro WHERE rangoingreso_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna origen_fondos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value origen_fondos requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByOrigenFondos($value)
	{
		$sql = 'SELECT * FROM registro WHERE origen_fondos = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna paisnacim_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value paisnacim_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByPaisnacimId($value)
	{
		$sql = 'SELECT * FROM registro WHERE paisnacim_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna cedula sea igual al valor pasado como parámetro
 	 *
     * @param String $value cedula requerido
     * @param String $mandante mandante
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCedula($value,$mandante=0)
	{
		$Helpers = new Helpers();
		$field = 'registro.cedula';
		$field2 = 'registro.cedula';
		$value = $Helpers->encode_data_with_key($value, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);

		$sql = "SELECT * FROM registro inner join usuario on (usuario.usuario_id = registro.usuario_id) WHERE $field2 = ? AND  usuario.mandante = ? AND usuario.eliminado='N' ";
		$sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
		$sqlQuery->set($mandante);
		return $this->getList($sqlQuery);
	}
	public function queryByCelular($value,$mandante=0)
	{
		$Helpers = new Helpers();
		$field = 'registro.celular';
		$field2 = 'registro.celular';
		$value = $Helpers->encode_data_with_key($value, $_ENV['SECRET_PASSPHRASE_PHONE']);

		$sql = "SELECT *, $field2 FROM registro inner join usuario on (usuario.usuario_id = registro.usuario_id) WHERE $field2 = ? AND  usuario.mandante = ? AND usuario.eliminado='N' ";
		$sqlQuery = new SqlQuery($sql);
		$Helpers = new Helpers();
        $sqlQuery->set($value);
        $sqlQuery->set($mandante);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna nombre1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre1 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByNombre1($value)
	{
		$Helpers = new Helpers();
		$field = 'registro.nombre1';
		$field2 = $Helpers->set_custom_field($field);
		$sql = "SELECT * FROM registro WHERE $field2 = ?";
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna nombre2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre2 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByNombre2($value)
	{
		$sql = 'SELECT * FROM registro WHERE nombre2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$Helpers = new Helpers();
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($value));
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna apellido1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apellido1 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByApellido1($value)
	{
		$Helpers = new Helpers();
		$field = 'registro.apellido1';
		$field2 = $Helpers->set_custom_field($field);
		$sql = "SELECT * FROM registro WHERE $field2 = ?";
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna apellido2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apellido2 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByApellido2($value)
	{
		$Helpers = new Helpers();
		$field = 'registro.apellido2';
		$field2 = $Helpers->set_custom_field($field);
		$sql = "SELECT * FROM registro WHERE $field2 = ?";
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna sexo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value sexo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryBySexo($value)
	{
		$Helpers = new Helpers();
		$field = 'registro.sexo';
		$field2 = $Helpers->set_custom_field($field);
		$sql = "SELECT * FROM registro WHERE $field2 = ?";
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByDireccion($value)
	{
		$Helpers = new Helpers();
		$field = 'registro.direccion';
		$field2 = $Helpers->set_custom_field($field);
		$sql = "SELECT * FROM registro WHERE $field2 = ?";
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTelefono($value)
	{
		$Helpers = new Helpers();
		$field = 'registro.telefono';
		$field2 = $Helpers->set_custom_field($field);
		$sql = "SELECT * FROM registro WHERE $field2 = ?";
		$sqlQuery = new SqlQuery($sql);
		$Helpers = new Helpers();
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ciudnacim_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ciudnacim_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCiudnacimId($value)
	{
		$sql = 'SELECT * FROM registro WHERE ciudnacim_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna nacionalidad_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nacionalidad_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByNacionalidadId($value)
	{
		$sql = 'SELECT * FROM registro WHERE nacionalidad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna estado_valida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value estado_valida requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByEstadoValida($value)
	{
		$sql = 'SELECT * FROM registro WHERE estado_valida = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna usuvalida_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value usuvalida_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByUsuvalidaId($value)
	{
		$sql = 'SELECT * FROM registro WHERE usuvalida_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_valida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_valida requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaValida($value)
	{
		$sql = 'SELECT * FROM registro WHERE fecha_valida = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna dir_ip sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value dir_ip requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByDirIp($value)
	{
		$sql = 'SELECT * FROM registro WHERE dir_ip = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna tipo_doc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo_doc requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTipoDoc($value)
	{
		$sql = 'SELECT * FROM registro WHERE tipo_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ciudexped_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ciudexped_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCiudexpedId($value)
	{
		$sql = 'SELECT * FROM registro WHERE ciudexped_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_exped sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_exped requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaExped($value)
	{
		$sql = 'SELECT * FROM registro WHERE fecha_exped = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna codigo_postal sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value codigo_postal requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByCodigoPostal($value)
	{
		$sql = 'SELECT * FROM registro WHERE codigo_postal = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ocupacion_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ocupacion_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByOcupacionId($value)
	{
		$sql = 'SELECT * FROM registro WHERE ocupacion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna origenfondos_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value origenfondos_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByOrigenfondosId($value)
	{
		$sql = 'SELECT * FROM registro WHERE origenfondos_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}







	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna nombre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByNombre($value)
	{
		$sql = 'DELETE FROM registro WHERE nombre = ?';
		$sqlQuery = new SqlQuery($sql);
		$Helpers = new Helpers();
		$sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($value));
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value email requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByEmail($value)
	{
		$sql = 'DELETE FROM registro WHERE email = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna puntoventa_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value puntoventa_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByPuntoventaId($value)
	{
		$sql = 'DELETE FROM registro WHERE puntoventa_id = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByEstado($value)
	{
		$sql = 'DELETE FROM registro WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna usuario_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value usuario_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByUsuarioId($value)
	{
		$sql = 'DELETE FROM registro WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna clave_activa sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value clave_activa requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByClaveActiva($value)
	{
		$sql = 'DELETE FROM registro WHERE clave_activa = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna creditos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCreditos($value)
	{
		$sql = 'DELETE FROM registro WHERE creditos = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna creditos_base sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_base requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCreditosBase($value)
	{
		$sql = 'DELETE FROM registro WHERE creditos_base = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna celular sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value celular requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCelular($value)
	{
		$sql = 'DELETE FROM registro WHERE celular = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ciudad sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ciudad requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCiudad($value)
	{
		$sql = 'DELETE FROM registro WHERE ciudad = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna creditos_ant sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_ant requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCreditosAnt($value)
	{
		$sql = 'DELETE FROM registro WHERE creditos_ant = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna creditos_base_ant sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_base_ant requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCreditosBaseAnt($value)
	{
		$sql = 'DELETE FROM registro WHERE creditos_base_ant = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ciudad_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ciudad_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCiudadId($value)
	{
		$sql = 'DELETE FROM registro WHERE ciudad_id = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByMandante($value)
	{
		$sql = 'DELETE FROM registro WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna casino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value casino requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCasino($value)
	{
		$sql = 'DELETE FROM registro WHERE casino = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna casino_base sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value casino_base requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCasinoBase($value)
	{
		$sql = 'DELETE FROM registro WHERE casino_base = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna fecha_casino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_casino requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaCasino($value)
	{
		$sql = 'DELETE FROM registro WHERE fecha_casino = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna preregistro_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value preregistro_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByPreregistroId($value)
	{
		$sql = 'DELETE FROM registro WHERE preregistro_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna creditos_bono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_bono requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCreditosBono($value)
	{
		$sql = 'DELETE FROM registro WHERE creditos_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna creditos_bono_ant sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value creditos_bono_ant requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCreditosBonoAnt($value)
	{
		$sql = 'DELETE FROM registro WHERE creditos_bono_ant = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ocupacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ocupacion requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByOcupacion($value)
	{
		$sql = 'DELETE FROM registro WHERE ocupacion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna rangoingreso_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value rangoingreso_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByRangoingresoId($value)
	{
		$sql = 'DELETE FROM registro WHERE rangoingreso_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna origen_fondos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value origen_fondos requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByOrigenFondos($value)
	{
		$sql = 'DELETE FROM registro WHERE origen_fondos = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna paisnacim_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value paisnacim_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByPaisnacimId($value)
	{
		$sql = 'DELETE FROM registro WHERE paisnacim_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna cedula sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value cedula requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCedula($value)
	{
		$sql = 'DELETE FROM registro WHERE cedula = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna nombre1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre1 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByNombre1($value)
	{
		$sql = 'DELETE FROM registro WHERE nombre1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna nombre2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre2 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByNombre2($value)
	{
		$sql = 'DELETE FROM registro WHERE nombre2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna apellido1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apellido1 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByApellido1($value)
	{
		$sql = 'DELETE FROM registro WHERE apellido1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna apellido2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apellido2 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByApellido2($value)
	{
		$sql = 'DELETE FROM registro WHERE apellido2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna sexo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value sexo requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteBySexo($value)
	{
		$sql = 'DELETE FROM registro WHERE sexo = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByDireccion($value)
	{
		$sql = 'DELETE FROM registro WHERE direccion = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTelefono($value)
	{
		$sql = 'DELETE FROM registro WHERE telefono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ciudnacim_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ciudnacim_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCiudnacimId($value)
	{
		$sql = 'DELETE FROM registro WHERE ciudnacim_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna nacionalidad_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nacionalidad_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByNacionalidadId($value)
	{
		$sql = 'DELETE FROM registro WHERE nacionalidad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna estado_valida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value estado_valida requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByEstadoValida($value)
	{
		$sql = 'DELETE FROM registro WHERE estado_valida = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna usuvalida_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value usuvalida_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByUsuvalidaId($value)
	{
		$sql = 'DELETE FROM registro WHERE usuvalida_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna fecha_valida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_valida requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaValida($value)
	{
		$sql = 'DELETE FROM registro WHERE fecha_valida = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna dir_ip sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value dir_ip requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByDirIp($value)
	{
		$sql = 'DELETE FROM registro WHERE dir_ip = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna tipo_doc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo_doc requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTipoDoc($value)
	{
		$sql = 'DELETE FROM registro WHERE tipo_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ciudexped_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ciudexped_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCiudexpedId($value)
	{
		$sql = 'DELETE FROM registro WHERE ciudexped_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna fecha_exped sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_exped requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaExped($value)
	{
		$sql = 'DELETE FROM registro WHERE fecha_exped = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna codigo_postal sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value codigo_postal requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByCodigoPostal($value)
	{
		$sql = 'DELETE FROM registro WHERE codigo_postal = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ocupacion_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ocupacion_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByOcupacionId($value)
	{
		$sql = 'DELETE FROM registro WHERE ocupacion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna origenfondos_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value origenfondos_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByOrigenfondosId($value)
	{
		$sql = 'DELETE FROM registro WHERE origenfondos_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}








	
	/**
 	 * Crear y devolver un objeto del tipo Registro
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $registro Registro
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row)
	{
		$registro = new Registro();
		$Helpers = new Helpers();

		$registro->registroId = $row['registro_id'];
		$registro->nombre = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->decode_data($row['nombre']);
		$registro->email = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->decode_data($row['email']);
		$registro->puntoventaId = $row['puntoventa_id'];
		$registro->estado = $row['estado'];
		$registro->usuarioId = $row['usuario_id'];
		$registro->claveActiva = $row['clave_activa'];
		$registro->creditos = $row['creditos'];
		$registro->creditosBase = $row['creditos_base'];
		$registro->celular = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->decode_data($row['celular']);
		$registro->ciudad = $row['ciudad'];
		$registro->creditosAnt = $row['creditos_ant'];
		$registro->creditosBaseAnt = $row['creditos_base_ant'];
		$registro->ciudadId = $row['ciudad_id'];
		$registro->mandante = $row['mandante'];
		$registro->casino = $row['casino'];
		$registro->casinoBase = $row['casino_base'];
		$registro->fechaCasino = $row['fecha_casino'];
		$registro->preregistroId = $row['preregistro_id'];
		$registro->creditosBono = $row['creditos_bono'];
		$registro->creditosBonoAnt = $row['creditos_bono_ant'];
		$registro->ocupacion = $row['ocupacion'];
		$registro->rangoingresoId = $row['rangoingreso_id'];
		$registro->origenFondos = $row['origen_fondos'];
		$registro->paisnacimId = $row['paisnacim_id'];
		$registro->cedula = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT', true)->decode_data($row['cedula']);
		$registro->nombre1 = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->decode_data($row['nombre1']);
		$registro->nombre2 = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->decode_data($row['nombre2']);
		$registro->apellido1 = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->decode_data($row['apellido1']);
		$registro->apellido2 = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->decode_data($row['apellido2']);
		$registro->sexo = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_GENDER', true)->decode_data($row['sexo']);
		$registro->direccion = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->decode_data($row['direccion']);
		$registro->telefono = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->decode_data($row['telefono']);
		$registro->ciudnacimId = $row['ciudnacim_id'];
		$registro->nacionalidadId = $row['nacionalidad_id'];
		$registro->estadoValida = $row['estado_valida'];
		$registro->usuvalidaId = $row['usuvalida_id'];
		$registro->fechaValida = $row['fecha_valida'];
		$registro->dirIp = $row['dir_ip'];
		$registro->tipoDoc = $row['tipo_doc'];
		$registro->ciudexpedId = $row['ciudexped_id'];
		$registro->fechaExped = $row['fecha_exped'];
		$registro->codigoPostal = $row['codigo_postal'];
		$registro->ocupacionId = $row['ocupacion_id'];
        $registro->origenfondosId = $row['origenfondos_id'];
        $registro->afiliadorId = $row['afiliador_id'];

        $registro->bannerId = $row['banner_id'];
        $registro->linkId = $row['link_id'];
        $registro->codpromocionalId = $row['codpromocional_id'];




        $registro->saldoCasinoBonos = $row['saldo_casino_bonos'];
        $registro->saldoBonosLiberados = $row['saldo_bonos_liberados'];

        return $registro;
	}

	protected function execute2($sqlQuery)
	{
		return QueryExecutor::execute2($this->transaction, $sqlQuery);
	}

	/**
	 * Realizar una consulta personalizada a la tabla 'registro'.
	 *
	 * Construye un filtro (WHERE) a partir de los parámetros recibidos.
	 *
	 * @param string $select Columnas que se van a seleccionar
	 * @param string $sidx Nombre de la columna para ordenar
	 * @param string $sord Dirección del orden (ASC o DESC)
	 * @param int $start Índice de inicio de los registros
	 * @param int $limit Cantidad de registros a devolver
	 * @param string $filters Filtros en formato JSON
	 * @param bool $searchOn Indica si se está aplicando algún filtro
	 * @param string $grouping Columna para agrupar (opcional)
	 *
	 * @return string Respuesta en formato JSON con la cuenta y los datos obtenidos
	 *
	 */
	public function queryRegistroCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
	{

		$where = " where 1=1 ";

		$Helpers = new Helpers();

		$innconcesionario=true;

		if ($searchOn) {
			// Construye el where
			$filters = json_decode($filters);
			$whereArray = array();
			$rules = $filters->rules;
			$groupOperation = $filters->groupOp;


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
				if ($fieldOperation != "") {
					$whereArray[] = $fieldName . $fieldOperation;
				}

				if (oldCount($whereArray) > 0) {
					$where =  $where . " " . $groupOperation . " ((" . $fieldName . ")) " . strtoupper($fieldOperation);


				} else {
					$where = "";
				}
			}

		}


		$sql = "SELECT count(*) count FROM registro  
INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)  
 " . $where;


		if ($grouping != "") {
			$where = $where . " GROUP BY " . $grouping;
		}

		$sqlQuery = new SqlQuery($sql);
		$count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM  registro  
INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

		$result = $Helpers->process_data($this->execute2($sqlQuery));

		$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

		return $json;
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
	protected function executeInsert($sqlQuery)
	{
		return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
	}
}
?>
