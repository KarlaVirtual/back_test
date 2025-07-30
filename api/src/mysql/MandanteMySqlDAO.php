<?php namespace Backend\mysql;
use Backend\dao\MandanteDAO;
use Backend\dto\Helpers;
use Backend\dto\Mandante;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'MandanteMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Mandante'
* 
* Ejemplo de uso: 
* $MandanteMySqlDAO = new MandanteMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class MandanteMySqlDAO implements MandanteDAO{

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
    public function setStringTransaction($transaction)
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


    /**
     * Constructor de la clase MandanteMySqlDAO
     *
     * Inicializa la transacción del objeto. Si no se proporciona una transacción,
     * se crea una nueva instancia de la clase Transaction.
     *
     * @param Transaction $transaction (opcional) Objeto de transacción
     *
     * @return void
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
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
    public function load($id){
        $sql = 'SELECT * FROM mandante WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryAll(){
        $sql = 'SELECT * FROM mandante';
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
        $sql = 'SELECT * FROM mandante ORDER BY '.$orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $mandante llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($mandante){
        $sql = 'DELETE FROM mandante WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($mandante);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object mandante mandante
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($mandante){
        $sql = 'INSERT INTO mandante (descripcion, contacto, email, telefono, nit, legal1, legal2, legal3, legal4, propio,nombre,base_url, url_api, url_websocket, email_noreply, logo, logo_oscuro, color_principal, email_fondo, skin_itainment, logo_pdf) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setString($mandante->descripcion);
        $sqlQuery->setString($mandante->contacto);
        $sqlQuery->setString($mandante->email);
        $sqlQuery->setString($mandante->telefono);
        $sqlQuery->setString($mandante->nit);
        $sqlQuery->setString($mandante->legal1);
        $sqlQuery->setString($mandante->legal2);
        $sqlQuery->setString($mandante->legal3);
        $sqlQuery->setString($mandante->legal4);
        $sqlQuery->setString($mandante->propio);

        $sqlQuery->setString($mandante->nombre);
        $sqlQuery->setString($mandante->baseUrl);
        $sqlQuery->setString($mandante->urlApi);
        $sqlQuery->setString($mandante->urlWebsocket);

        $sqlQuery->setString($mandante->emailNoreply);
        $sqlQuery->setString($mandante->logo);
        $sqlQuery->setString($mandante->logoOscuro);
        $sqlQuery->setString($mandante->colorPrincipal);
        $sqlQuery->setString($mandante->emailFondo);

        $sqlQuery->setString($mandante->skinItainment);
        $sqlQuery->setString($mandante->logoPdf);

        $id = $this->executeInsert($sqlQuery);
        $mandante->mandante = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object mandante mandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($mandante){
        $sql = 'UPDATE mandante set descripcion = ?, contacto = ?, email = ?, telefono = ?, nit = ?, legal1 = ?, legal2 = ?, legal3 = ?, legal4 = ?, propio = ?, nombre = ?, base_url = ?, url_api = ?, url_websocket = ?, email_noreply = ?, logo = ?, logo_oscuro = ?, color_principal = ?, email_fondo = ?, skin_itainment = ?, logo_pdf = ? WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setString($mandante->descripcion);
        $sqlQuery->setString($mandante->contacto);
        $sqlQuery->setString($mandante->email);
        $sqlQuery->setString($mandante->telefono);
        $sqlQuery->setString($mandante->nit);
        $sqlQuery->setString($mandante->legal1);
        $sqlQuery->setString($mandante->legal2);
        $sqlQuery->setString($mandante->legal3);
        $sqlQuery->setString($mandante->legal4);
        $sqlQuery->setString($mandante->propio);

        $sqlQuery->setString($mandante->nombre);
        $sqlQuery->setString($mandante->baseUrl);
        $sqlQuery->setString($mandante->urlApi);
        $sqlQuery->setString($mandante->urlWebsocket);

        $sqlQuery->setString($mandante->emailNoreply);
        $sqlQuery->setString($mandante->logo);
        $sqlQuery->setString($mandante->logoOscuro);
        $sqlQuery->setString($mandante->colorPrincipal);
        $sqlQuery->setString($mandante->emailFondo);

        $sqlQuery->setString($mandante->skinItainment);

        $sqlQuery->setString($mandante->logoPdf);


        $sqlQuery->setString($mandante->mandante);
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
        $sql = 'DELETE FROM mandante';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByDescripcion($value){
        $sql = 'SELECT * FROM mandante WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna contacto sea igual al valor pasado como parámetro
     *
     * @param String $value contacto requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByContacto($value){
        $sql = 'SELECT * FROM mandante WHERE contacto = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
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
        $sql = 'SELECT * FROM mandante WHERE email = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
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
        $sql = 'SELECT * FROM mandante WHERE telefono = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nit sea igual al valor pasado como parámetro
     *
     * @param String $value nit requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByNit($value){
        $sql = 'SELECT * FROM mandante WHERE nit = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna legal1 sea igual al valor pasado como parámetro
     *
     * @param String $value legal1 requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByLegal1($value){
        $sql = 'SELECT * FROM mandante WHERE legal1 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna legal2 igual al valor pasado como parámetro
     *
     * @param String $value legal2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByLegal2($value){
        $sql = 'SELECT * FROM mandante WHERE legal2 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna legal3 sea igual al valor pasado como parámetro
     *
     * @param String $value legal3 requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByLegal3($value){
        $sql = 'SELECT * FROM mandante WHERE legal3 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna legal4 sea igual al valor pasado como parámetro
     *
     * @param String $value legal4 requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByLegal4($value){
        $sql = 'SELECT * FROM mandante WHERE legal4 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna propio sea igual al valor pasado como parámetro
     *
     * @param String $value propio requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByPropio($value){
        $sql = 'SELECT * FROM mandante WHERE propio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->getList($sqlQuery);
    }





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByDescripcion($value){
        $sql = 'DELETE FROM mandante WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna contacto sea igual al valor pasado como parámetro
     *
     * @param String $value contacto requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByContacto($value){
        $sql = 'DELETE FROM mandante WHERE contacto = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
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
        $sql = 'DELETE FROM mandante WHERE email = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
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
        $sql = 'DELETE FROM mandante WHERE telefono = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nit sea igual al valor pasado como parámetro
     *
     * @param String $value nit requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByNit($value){
        $sql = 'DELETE FROM mandante WHERE nit = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna legal1 sea igual al valor pasado como parámetro
     *
     * @param String $value legal1 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByLegal1($value){
        $sql = 'DELETE FROM mandante WHERE legal1 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna legal2 sea igual al valor pasado como parámetro
     *
     * @param String $value legal2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByLegal2($value){
        $sql = 'DELETE FROM mandante WHERE legal2 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna legal3 sea igual al valor pasado como parámetro
     *
     * @param String $value legal3 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByLegal3($value){
        $sql = 'DELETE FROM mandante WHERE legal3 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna legal4 sea igual al valor pasado como parámetro
     *
     * @param String $value legal4 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByLegal4($value){
        $sql = 'DELETE FROM mandante WHERE legal4 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna propio sea igual al valor pasado como parámetro
     *
     * @param String $value propio requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByPropio($value){
        $sql = 'DELETE FROM mandante WHERE propio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        return $this->executeUpdate($sqlQuery);
    }




    /**
    * Realizar una consulta en la tabla de Mandante 'Mandante'
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
	public function queryMandantes($sidx,$sord,$start,$limit,$filters,$searchOn)
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


		$sql = 'SELECT count(*) count FROM mandante ' . $where ;


			$sqlQuery = new SqlQuery($sql);

			$count = $this->execute2($sqlQuery);


			$sql = 'SELECT  mandante.* from mandante ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

			$sqlQuery = new SqlQuery($sql);

			$result = $this->execute2($sqlQuery);

			$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

			return  $json;
	}

    /**
    * Realizar una consulta en la tabla de PuntoVenta 'PuntoVenta'
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
    public function queryPuntosVentaTree($sidx,$sord,$start,$limit,$filters,$searchOn)
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


		$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN mandante on (mandante.mandante = punto_venta.mandante) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = punto_venta.usuario_id) INNER JOIN usuario ON (usuario.usuario_id = punto_venta.usuario_id)   INNER JOIN ciudad ON (ciudad.ciudad_id = punto_venta.ciudad_id) INNER JOIN departamento ON (departamento.depto_id = ciudad.depto_id) INNER JOIN pais ON (pais.pais_id=departamento.pais_id) inner join clasificador regional ON (regional.clasificador_id = punto_venta.clasificador1_id) inner join clasificador tipo_punto ON (tipo_punto.clasificador_id = punto_venta.clasificador2_id) inner join clasificador tipo_establecimiento ON (tipo_establecimiento.clasificador_id = punto_venta.clasificador3_id) INNER JOIN usuario_premiomax  on (usuario.mandante=usuario_premiomax.mandante and usuario.usuario_id=usuario_premiomax.usuario_id) INNER JOIN usuario_config  on (usuario.mandante=usuario_config.mandante and usuario.usuario_id=usuario_config.usuario_id) ' . $where ;


			$sqlQuery = new SqlQuery($sql);

			$count = $this->execute2($sqlQuery);


			$sql = 'SELECT  punto_venta.*,mandante.*,ciudad.*,departamento.*,pais.*,usuario.moneda,usuario.login,usuario.permite_activareg,tipo_punto.*,regional.*,tipo_establecimiento.*,usuario_premiomax.*,usuario_config.*  FROM punto_venta INNER JOIN mandante on (mandante.mandante = punto_venta.mandante) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = punto_venta.usuario_id) INNER JOIN usuario ON (usuario.usuario_id = punto_venta.usuario_id)   INNER JOIN ciudad ON (ciudad.ciudad_id = punto_venta.ciudad_id) INNER JOIN departamento ON (departamento.depto_id = ciudad.depto_id) INNER JOIN pais ON (pais.pais_id=departamento.pais_id) inner join clasificador regional ON (regional.clasificador_id = punto_venta.clasificador1_id) inner join clasificador tipo_punto ON (tipo_punto.clasificador_id = punto_venta.clasificador2_id) inner join clasificador tipo_establecimiento ON (tipo_establecimiento.clasificador_id = punto_venta.clasificador3_id) INNER JOIN usuario_premiomax  on (usuario.mandante=usuario_premiomax.mandante and usuario.usuario_id=usuario_premiomax.usuario_id) INNER JOIN usuario_config  on (usuario.mandante=usuario_config.mandante and usuario.usuario_id=usuario_config.usuario_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

			$sqlQuery = new SqlQuery($sql);

			$result = $Helpers->process_data($this->execute2($sqlQuery));

			$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

			return  $json;
	}





    /**
     * Crear y devolver un objeto del tipo Mandante
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Mandante Mandante
     *
     * @access protected
     *
     */
    protected function readRow($row){
        $mandante = new Mandante();

        $mandante->mandante = $row['mandante'];
        $mandante->descripcion = $row['descripcion'];
        $mandante->contacto = $row['contacto'];
        $mandante->email = $row['email'];
        $mandante->telefono = $row['telefono'];
        $mandante->nit = $row['nit'];
        $mandante->legal1 = $row['legal1'];
        $mandante->legal2 = $row['legal2'];
        $mandante->legal3 = $row['legal3'];
        $mandante->legal4 = $row['legal4'];
        $mandante->propio = $row['propio'];
        $mandante->nombre = $row['nombre'];
        $mandante->baseUrl = $row['base_url'];
        $mandante->urlApi = $row['url_api'];
        $mandante->urlWebsocket = $row['url_websocket'];

        $mandante->emailNoreply = $row['email_noreply'];
        $mandante->logo = $row['logo'];
        $mandante->logoOscuro = $row['logo_oscuro'];
        $mandante->colorPrincipal = $row['color_principal'];
        $mandante->emailFondo = $row['email_fondo'];

        $mandante->skinItainment = $row['skin_itainment'];
        $mandante->walletcodeItainment = $row['walletcode_itainment'];
        $mandante->logoPdf = $row['logo_pdf'];
        $mandante->pathItainment = $row['path_itainment'];
        $mandante->favicon = $row['favicon'];

        return $mandante;
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
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
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
    protected function querySingleResult($sqlQuery){
        return QueryExecutor::queryForString($this->transaction,$sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como insert
     * 
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