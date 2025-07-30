<?php namespace Backend\mysql;
use Backend\dao\CuentaCobroDAO;
use Backend\dto\CuentaCobro;
use Backend\dto\Helpers;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\dto\ConfigurationEnvironment;
use Backend\sql\ConnectionProperty;
use PDO;
/**
* Clase 'CuentaCobroMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'CuentaCobro'
*
* Ejemplo de uso:
* $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class CuentaCobroMySqlDAO implements CuentaCobroDAO
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
    public function __construct($transaction = "")
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
     * @param String $cuenta_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE cuenta_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get Domain object by primry key
     *
     * @param String $id primary key
     * @return CuentaCobroMySql
     */
    public function loadByCuentaIdAndClave($id,$clave)
    {
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
        $this->execute2($sqlQuery2);
        $sql = 'SELECT * FROM cuenta_cobro WHERE cuenta_id = ? and aes_decrypt(clave,\'12hur12b\')=\'' . strtoupper($clave) . '\' ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        $result = $this->getRow($sqlQuery);
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
        $this->execute2($sqlQuery2);
        return $result;
    }

    public function getClaveD($id)
    {
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
        $this->execute2($sqlQuery2);

        $sql = 'SELECT aes_decrypt(clave,\'12hur12b\') FROM cuenta_cobro WHERE cuenta_id = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        $result = $this->getRow($sqlQuery)[0] [0];
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
        $this->execute2($sqlQuery2);
        return $result;
    }

    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $transproducto_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
    public function loadByTransproductoId($id)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE transproducto_id = ?';
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
    public function queryAll()
    {
        $sql = 'SELECT * FROM cuenta_cobro';
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
        $sql = 'SELECT * FROM cuenta_cobro ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $cuenta_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($cuenta_id)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE cuenta_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($cuenta_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object cuentaCobro cuentaCobro
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($cuentaCobro)
    {
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
        $this->execute2($sqlQuery2);

        $sql = 'INSERT INTO cuenta_cobro (usuario_id, fecha_crea, valor, fecha_pago, puntoventa_id, estado, clave, mandante, dir_ip, impresa, mediopago_id, observacion, mensaje_usuario, usucambio_id,usurechaza_id,usupago_id,fecha_cambio,fecha_accion,dirip_accion,dirip_cambio,creditos,creditos_base,impuesto,costo,transproducto_id,version,impuesto2,producto_pago_id,factura,puntaje_jugador) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?,?, ?, ?, ?,?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        //$sqlQuery->set($cuentaCobro->cuentaId);
        $sqlQuery->set($cuentaCobro->usuarioId);
        $sqlQuery->set($cuentaCobro->fechaCrea);
        $sqlQuery->set($cuentaCobro->valor);



        if($cuentaCobro->fechaPago == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($cuentaCobro->fechaPago);
        }

        $sqlQuery->set($cuentaCobro->puntoventaId);
        $sqlQuery->set($cuentaCobro->estado);
        $sqlQuery->setSIN($cuentaCobro->clave);
        $sqlQuery->set($cuentaCobro->mandante);
        $sqlQuery->set($cuentaCobro->dirIp);
        $sqlQuery->set($cuentaCobro->impresa);
        $sqlQuery->set($cuentaCobro->mediopagoId);
        $sqlQuery->set($cuentaCobro->observacion);
        $sqlQuery->set($cuentaCobro->mensajeUsuario);
        $sqlQuery->set($cuentaCobro->usucambioId);
        $sqlQuery->set($cuentaCobro->usurechazaId);
        $sqlQuery->set($cuentaCobro->usupagoId);



        if($cuentaCobro->fechaCambio == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($cuentaCobro->fechaCambio);
        }



        if($cuentaCobro->fechaAccion == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($cuentaCobro->fechaAccion);
        }

        $sqlQuery->set($cuentaCobro->diripAccion);
        $sqlQuery->set($cuentaCobro->diripCambio);
        $sqlQuery->set($cuentaCobro->creditos);
        $sqlQuery->set($cuentaCobro->creditosBase);
        $sqlQuery->set($cuentaCobro->impuesto);
        $sqlQuery->set($cuentaCobro->costo);
        $sqlQuery->set($cuentaCobro->transproductoId);

        if($cuentaCobro->version == ""){
            $cuentaCobro->version=1;
        }



        $sqlQuery->set($cuentaCobro->version);
        $sqlQuery->set($cuentaCobro->impuesto2);

        if($cuentaCobro->productoPagoId==null || $cuentaCobro->productoPagoId==""){
            $cuentaCobro->productoPagoId=0;
            $sqlQuery->set($cuentaCobro->productoPagoId);
        }else{
            $sqlQuery->set($cuentaCobro->productoPagoId);
        }

        if($cuentaCobro->factura == null){
            $cuentaCobro->factura='';
        }

        $sqlQuery->set($cuentaCobro->factura);

        $sqlQuery->set($cuentaCobro->puntajeJugador);

        $id = $this->executeInsert($sqlQuery);
        $cuentaCobro->cuentaId = $id;
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
        $this->execute2($sqlQuery2);
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object cuentaCobro CuentaCobro
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($cuentaCobro,$where="")
    {
        $sql = 'UPDATE cuenta_cobro SET usuario_id = ?, fecha_crea = ?, valor = ?, fecha_pago = ?, puntoventa_id = ?, estado = ?, mandante = ?, dir_ip = ?, impresa = ?, mediopago_id = ?, observacion = ?, mensaje_usuario = ?, usucambio_id = ?, usurechaza_id= ?, usupago_id = ?,fecha_cambio = ?,fecha_accion= ?,dirip_accion= ?,dirip_cambio= ?,creditos= ?,creditos_base= ?,impuesto= ?,costo= ?,transproducto_id= ?, version = ?, impuesto2 = ?, producto_pago_id = ?,puntaje_jugador = ? WHERE cuenta_id = ? ' . $where;
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($cuentaCobro->usuarioId);
        $sqlQuery->set($cuentaCobro->fechaCrea);
        $sqlQuery->set($cuentaCobro->valor);


        if($cuentaCobro->fechaPago == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($cuentaCobro->fechaPago);
        }

        $sqlQuery->set($cuentaCobro->puntoventaId);
        $sqlQuery->set($cuentaCobro->estado);
        $sqlQuery->set($cuentaCobro->mandante);
        $sqlQuery->set($cuentaCobro->dirIp);
        $sqlQuery->set($cuentaCobro->impresa);
        $sqlQuery->set($cuentaCobro->mediopagoId);
        $sqlQuery->set($cuentaCobro->observacion);
        $sqlQuery->set($cuentaCobro->mensajeUsuario);
        $sqlQuery->set($cuentaCobro->usucambioId);
        $sqlQuery->set($cuentaCobro->usurechazaId);
        $sqlQuery->set($cuentaCobro->usupagoId);

        if($cuentaCobro->fechaCambio == ""){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($cuentaCobro->fechaCambio);
        }

        if($cuentaCobro->fechaAccion == ""){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($cuentaCobro->fechaAccion);
        }

        $sqlQuery->set($cuentaCobro->diripAccion);
        $sqlQuery->set($cuentaCobro->diripCambio);
        $sqlQuery->set($cuentaCobro->creditos);
        $sqlQuery->set($cuentaCobro->creditosBase);
        $sqlQuery->set($cuentaCobro->impuesto);
        $sqlQuery->set($cuentaCobro->costo);
        $sqlQuery->set($cuentaCobro->transproductoId);



        if($cuentaCobro->version == ""){
            $cuentaCobro->version=1;
        }

        $sqlQuery->set($cuentaCobro->version);

        if($cuentaCobro->fechaEliminacion == ''){
            //$sqlQuery->setSIN('NULL');

        }else{
            //$sqlQuery->set($cuentaCobro->fechaEliminacion);

        }

        $sqlQuery->set($cuentaCobro->impuesto2);

        if($cuentaCobro->productoPagoId==null || $cuentaCobro->productoPagoId==""){
            $cuentaCobro->productoPagoId=0;
            $sqlQuery->set($cuentaCobro->productoPagoId);
        }else{
            $sqlQuery->set($cuentaCobro->productoPagoId);
        }

        $sqlQuery->set($cuentaCobro->puntajeJugador);

        $sqlQuery->set($cuentaCobro->cuentaId);
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
        $sql = 'DELETE FROM cuenta_cobro';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Ejecuta una consulta SQL proporcionada.
     *
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la ejecución de la consulta.
     */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
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
    public function queryByUsuarioId($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE usuario_id = ?';
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
        $sql = 'SELECT * FROM cuenta_cobro WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cuenta_cobro sea igual al valor pasado como parámetro
     *
     * @param String $value cuenta_cobro requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByValor($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_pago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_pago requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaPago($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE fecha_pago = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByPuntoventaId($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE puntoventa_id = ?';
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
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clave sea igual al valor pasado como parámetro
     *
     * @param String $value clave requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByClave($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE clave = ?';
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
    public function queryByMandante($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE mandante = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByDirIp($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE dir_ip = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna impresa sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cuenta requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByImpresa($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE impresa = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mediopago_id sea igual al valor pasado como parámetro
     *
     * @param String $value mediopago_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByMediopagoId($value)
    {
        $sql = 'SELECT * FROM cuenta_cobro WHERE mediopago_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }






    /**
    * Realizar una consulta en la tabla de CuentasCobro 'CuentaCobro'
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
    public function queryCuentasCobroCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping,$having="",$withCount=true,$daydimensionFechaPorPago=false,$forceTimeDimension=false,$daydimensionFechaPorAccion=false)
    {
        $Helpers = new Helpers();

        $innproducto_bancodetalle=false;
        $innproducto_ciudadid=false;

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


                $cond="ciudad";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_ciudadid=true;
                }
                $cond="banco_detalle";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_bancodetalle=true;
                }
                $cond="pr.";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_bancodetalle=true;
                }


                

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

                if($fieldName == 'usuario_banco_puntoventa.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_banco_puntoventa.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
                }

                if($fieldName == 'usuario_punto.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_punto.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
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
                    case "neF":
                        $fieldOperation = " != " . ($fieldData) . "";
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . ($fieldOperation);
                } else {
                    $where = "";
                }
            }
        }

        if ($having != null && json_decode($having)->rules != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;

                $cond="ciudad";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_ciudadid=true;
                }
                $cond="banco_detalle";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_bancodetalle=true;
                }
                $cond="pr.";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innproducto_bancodetalle=true;
                }

                

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

                if($fieldName == 'usuario_banco_puntoventa.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_banco_puntoventa.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
                }

                if($fieldName == 'usuario_punto.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_punto.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
                }

                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_banco_puntoventa.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_banco_puntoventa.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
                }

                if($fieldName == 'usuario_punto.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_punto.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
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
                    case "neF":
                        $fieldOperation = " != " . ($fieldData) . "";
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }


        $daydimensionFecha = "cuenta_cobro.fecha_crea";

        if($daydimensionFechaPorPago){
            $daydimensionFecha = "cuenta_cobro.fecha_pago";

        } if($daydimensionFechaPorAccion){
            $daydimensionFecha = "cuenta_cobro.fecha_accion";

        }


        $forcetime_dimension = "";

        if($forceTimeDimension){
            $forcetime_dimension = "force index (time_dimension_timestampint_timestr_index)";

        }
        $leftouter_bancodetalle = "";

        if($innproducto_bancodetalle){
            $leftouter_bancodetalle = " LEFT OUTER JOIN banco_detalle ON (banco_detalle.banco_id = banco.banco_id and banco_detalle.pais_id=usuario.pais_id and banco_detalle.mandante=usuario.mandante) LEFT OUTER JOIN producto pr ON (pr.producto_id = banco_detalle.producto_id) ";

        }

        $leftouter_ciudad = "";

        if($innproducto_ciudadid){
            $leftouter_ciudad = "  LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id=registro.ciudad_id) LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id)   ";

        }


        if($_REQUEST['test2']!=1) {

            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                try {

                    $connDB5 = null;
                    $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                        PDO::ATTR_TIMEOUT => 5, // in seconds
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    ));

                    $connDB5->exec("set names utf8");
                    $connDB5->exec("set use_secondary_engine=off");

                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                    }

                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                    }
                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                    }
                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                    }
                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                        $connDB5->exec("SET NAMES utf8mb4");
                    }
                    $_ENV["connectionGlobal"]->setConnection($connDB5);
                    $this->transaction->setConnection($_ENV["connectionGlobal"]);

                } catch (\Exception $e) {

                }
            }
        }
        if ($grouping == "cuenta_cobro.estado,pais.pais_id") {
            $where = $where . " GROUP BY " . $grouping;
            $sql = "SELECT count(*) count FROM cuenta_cobro
              inner join time_dimension  ".$forcetime_dimension."
                    on (time_dimension.timestr = ".$daydimensionFecha.") 

 INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante  = usuario.usuario_id) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id) LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id=cuenta_cobro.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id=cuenta_cobro.puntoventa_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id and prodinterno_id = 0 AND concesionario.estado='A')  LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id)  LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id) LEFT OUTER JOIN data_completa2 ON (data_completa2.usuario_id = usuario_mandante.usumandante_id)
 ".$leftouter_ciudad.$leftouter_bancodetalle . $where;

        }else{


            $sql = "SELECT /*+ MAX_EXECUTION_TIME(60000) */  count(*) count FROM cuenta_cobro
                     
                     INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante  = usuario.usuario_id) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id)  LEFT OUTER JOIN producto ON (transaccion_producto.producto_id=producto.producto_id)  LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id=cuenta_cobro.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id=cuenta_cobro.puntoventa_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id and prodinterno_id = 0  AND concesionario.estado='A')  LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id)   LEFT OUTER JOIN usuario usuario_banco_puntoventa ON (usuario_banco_puntoventa.usuario_id = cuenta_cobro.mediopago_id and  version in (2,3,4))  LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id) LEFT OUTER JOIN data_completa2 ON (data_completa2.usuario_id = usuario_mandante.usumandante_id) LEFT OUTER JOIN cripto_red ON (cripto_red.banco_id = banco.banco_id) ".$leftouter_ciudad.$leftouter_bancodetalle . $where;

            if($grouping != ""){
                $where = $where . " GROUP BY " . $grouping;
            }

        }

        if($having != ""){
            $where = $where . " HAVING " . $having;
        }


        $ordersql ="";
        if($sidx != "" && $sord != ''){
            $ordersql=" order by " . $sidx . " " . $sord;
        }
        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
            $withCount=false;
        }

        if($withCount){

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
        }

        $sql = "SELECT /*+ MAX_EXECUTION_TIME(60000) */  " . $select . " FROM cuenta_cobro
                     
                     INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id) LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante  = usuario.usuario_id) INNER JOIN pais ON (usuario.pais_id=pais.pais_id)  LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id)  LEFT OUTER JOIN producto ON (transaccion_producto.producto_id=producto.producto_id)  LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id=cuenta_cobro.puntoventa_id OR punto_venta.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id=cuenta_cobro.puntoventa_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id and prodinterno_id = 0  AND concesionario.estado='A')  LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id  and version in (1,3,4))   LEFT OUTER JOIN usuario usuario_banco_puntoventa ON (usuario_banco_puntoventa.usuario_id = cuenta_cobro.mediopago_id and  version in (2,3,4))  LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id) LEFT OUTER JOIN data_completa2 ON (data_completa2.usuario_id = usuario_mandante.usumandante_id) LEFT OUTER JOIN cripto_red ON (cripto_red.banco_id = banco.banco_id)

                     ".$leftouter_ciudad.$leftouter_bancodetalle . $where . " " . $ordersql . " LIMIT " . $start . " , " . $limit;




       $sqlQuery = new SqlQuery($sql);



        if($_ENV["debugFixed2"] == '1'){
            $sql="
SELECT  SUM(cuenta_cobro.valor) as total
FROM cuenta_cobro
         INNER JOIN usuario
                    ON (cuenta_cobro.usuario_id = usuario.usuario_id)
         LEFT OUTER JOIN registro ON (registro.usuario_id = usuario.usuario_id)
         LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id = cuenta_cobro.transproducto_id)
         LEFT OUTER JOIN producto ON (transaccion_producto.producto_id = producto.producto_id)
         LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id = cuenta_cobro.puntoventa_id OR
                                         punto_venta.usuario_id = usuario.usuario_id)
         LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = cuenta_cobro.puntoventa_id)
         LEFT OUTER JOIN concesionario
                         ON (usuario_punto.puntoventa_id = concesionario.usuhijo_id and prodinterno_id = 0 AND
                             concesionario.estado = 'A')
         LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id and version in (1,
                                                                                                                 3,
                                                                                                                 4))
         LEFT OUTER JOIN usuario usuario_banco_puntoventa
                         ON (usuario_banco_puntoventa.usuario_id = cuenta_cobro.mediopago_id and version in (2,
                                                                                                             3,
                                                                                                             4))
         LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id)
LEFT OUTER JOIN data_completa2 ON (data_completa2.usuario_id = usuario_mandante.usumandante_id)

where 1 = 1
  AND ((cuenta_cobro.estado)) = 'I'
  AND ((cuenta_cobro.usuario_id)) = '42947'
  AND ((cuenta_cobro.fecha_pago)) >= '2024-01-01 00:00:00'
  AND ((cuenta_cobro.fecha_pago)) <= '2024-02-15 07:02:28'
order by cuenta_cobro.cuenta_id asc
LIMIT 0, 1";
            print_r($sql);
            print_r($_ENV);
            $result = $Helpers->process_data($this->execute2($sqlQuery));
        }else{
            $result = $Helpers->process_data($this->execute2($sqlQuery));
        }


        if($_REQUEST['test2']!=1) {

            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                $connDB5 = null;
                $_ENV["connectionGlobal"]->setConnection($connOriginal);
                $this->transaction->setConnection($_ENV["connectionGlobal"]);
            }
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
        if($_ENV["debug"] ){
            print_r($sql);
        }

        return $json;
    }


    /**
     * Realizar una consulta en la tabla de CuentasCobro 'CuentaCobro'
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
    public function queryCuentasCobroPuntoVentaCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping,$having="",$withCount=true,$daydimensionFechaPorPago=false,$forceTimeDimension=false,$daydimensionFechaPorAccion=false)
    {


        $where = " where 1=1 ";
        $Helpers = new Helpers();


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

                if($fieldName == 'usuario_banco_puntoventa.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_banco_puntoventa.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
                }

                if($fieldName == 'usuario_punto.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_punto.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
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
                    case "neF":
                        $fieldOperation = " != " . ($fieldData) . "";
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . ($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if ($having != null && json_decode($having)->rules != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
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

                if($fieldName == 'usuario_banco_puntoventa.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_banco_puntoventa.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
                }

                if($fieldName == 'usuario_punto.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                if($fieldName == 'usuario_punto.nombre'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
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
                    case "neF":
                        $fieldOperation = " != " . ($fieldData) . "";
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "nisnull":
                        $fieldOperation = " IS NOT NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }


        $daydimensionFecha = "cuenta_cobro.fecha_crea";

        if($daydimensionFechaPorPago){
            $daydimensionFecha = "cuenta_cobro.fecha_pago";

        } if($daydimensionFechaPorAccion){
        $daydimensionFecha = "cuenta_cobro.fecha_accion";

    }


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            try{

                $connDB5 = null;
                $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                    PDO::ATTR_TIMEOUT => 5, // in seconds
                    PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                ));

                $connDB5->exec("set names utf8");
                $connDB5->exec("set use_secondary_engine=off");

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
                $_ENV["connectionGlobal"]->setConnection($connDB5);
                $this->transaction->setConnection($_ENV["connectionGlobal"]);

            }catch (\Exception $e){

            }
        }

        $forcetime_dimension = "";

        if($forceTimeDimension){
            $forcetime_dimension = "force index (time_dimension_timestampint_timestr_index)";

        }


        if ($grouping == "cuenta_cobro.estado,pais.pais_id") {
            $where = $where . " GROUP BY " . $grouping;
            $sql = "SELECT /*+ MAX_EXECUTION_TIME(60000) */  count(*) count FROM cuenta_cobro
              inner join time_dimension  ".$forcetime_dimension."
                    on (time_dimension.timestr = ".$daydimensionFecha.") 

 INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante  = usuario.usuario_id) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=cuenta_cobro.usuario_id AND usuario_perfil.perfil_id NOT IN ('USUONLINE')) LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id=cuenta_cobro.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id=cuenta_cobro.puntoventa_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id and prodinterno_id = 0 AND concesionario.estado='A')  LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id)  LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id) LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id=registro.ciudad_id) LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) " . $where;

        }else{


            $sql = "SELECT /*+ MAX_EXECUTION_TIME(60000) */ count(*) count FROM cuenta_cobro
                     
                     INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante  = usuario.usuario_id) INNER JOIN pais ON (usuario.pais_id=pais.pais_id)       LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=cuenta_cobro.usuario_id AND usuario_perfil.perfil_id NOT IN ('USUONLINE')) LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id=cuenta_cobro.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id=cuenta_cobro.puntoventa_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id and prodinterno_id = 0  AND concesionario.estado='A')  LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id)  LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id) LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id=registro.ciudad_id) LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) " . $where;

            if($grouping != ""){
                $where = $where . " GROUP BY " . $grouping;
            }

        }

        if($having != ""){
            $where = $where . " HAVING " . $having;
        }


        $ordersql ="";
        if($sidx != "" && $sord != ''){
            $ordersql=" order by " . $sidx . " " . $sord;
        }
        if($_ENV["debugFixed2"] == '1'){
            $withCount=false;
        }

        if($withCount){

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
        }

        $sql = "SELECT /*+ MAX_EXECUTION_TIME(60000) */  " . $select . " FROM cuenta_cobro
                     
                     INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id) LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante  = usuario.usuario_id) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=cuenta_cobro.usuario_id AND usuario_perfil.perfil_id NOT IN ('USUONLINE')) LEFT OUTER JOIN producto ON (transaccion_producto.producto_id=producto.producto_id)  LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id=cuenta_cobro.puntoventa_id OR punto_venta.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id=cuenta_cobro.puntoventa_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id and prodinterno_id = 0  AND concesionario.estado='A')  LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id)   LEFT OUTER JOIN usuario usuario_banco_puntoventa ON (usuario_banco_puntoventa.usuario_id = cuenta_cobro.mediopago_id)  LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id) LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id=registro.ciudad_id) LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id)   
                     
                     " . $where . " " . $ordersql . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);




        $result = $Helpers->process_data($this->execute2($sqlQuery));

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';




        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        return $json;
    }


    /**
     * Realiza una consulta personalizada en la base de datos para obtener cuentas de cobro con opciones de filtrado, búsqueda, agrupamiento y ordenamiento.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenamiento.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param string $filters Filtros en formato JSON para la búsqueda.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Campos para agrupar los resultados.
     * @param string $having Condiciones HAVING en formato JSON para la agrupación.
     * @return string JSON con el conteo de registros y los datos obtenidos.
     */
    public function queryCuentasCobroCustomAutomation($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "")
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $where = " WHERE 1=1 ";

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $count = 0;

            $relationship = "";
            $relationship .= " INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id)";
            $relationship .= " LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id)";
            $relationshipArray = [];

            foreach ($rules as $rule) {
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . ")) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }

                $result = $ConfigurationEnvironment->getRelationship(explode(".", $rule->field)[0], $relationship, $relationshipArray);

                $relationship = $result['relationship'];
                $relationshipArray = $result['relationshipArray'];
            }
        }

        if ($having != null && json_decode($having)->rules != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $count = 0;

            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }


        // if ($grouping == "cuenta_cobro.estado,pais.pais_id") {
        //     $where = $where . " GROUP BY " . $grouping;
        //     $sql = "SELECT count(*) count FROM cuenta_cobro INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante  = usuario.usuario_id) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id) LEFT OUTER JOIN punto_venta ON (punto_venta.puntoventa_id=cuenta_cobro.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id=cuenta_cobro.puntoventa_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.usuario_id=concesionario.usuhijo_id and prodinterno_id = 0)  LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id)  LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id) LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id=cuenta_cobro.ciudad_id) LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) " . $where;
        // } else {


        //     $sql = "SELECT count(*) count FROM cuenta_cobro INNER JOIN usuario ON (cuenta_cobro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante  = usuario.usuario_id) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id) LEFT OUTER JOIN punto_venta ON (punto_venta.puntoventa_id=cuenta_cobro.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id=cuenta_cobro.puntoventa_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.usuario_id=concesionario.usuhijo_id and prodinterno_id = 0)  LEFT OUTER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id)  LEFT OUTER JOIN banco ON (usuario_banco.banco_id = banco.banco_id) LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id=cuenta_cobro.ciudad_id) LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) " . $where;

        //     if ($grouping != "") {
        //         $where = $where . " GROUP BY " . $grouping;
        //     }
        // }

        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        $relationship = $ConfigurationEnvironment->getRelationshipSelect($select, $relationship, $relationshipArray);

        $sql = "SELECT " . $select . " FROM cuenta_cobro " . $relationship . $where . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
        return $json;
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
    public function deleteByUsuarioId($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE usuario_id = ?';
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
        $sql = 'DELETE FROM cuenta_cobro WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor sea igual al valor pasado como parámetro
     *
     * @param String $value valor requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByValor($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_pago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_pago requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFechaPago($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE fecha_pago = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByPuntoventaId($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE puntoventa_id = ?';
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
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clave sea igual al valor pasado como parámetro
     *
     * @param String $value clave requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByClave($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE clave = ?';
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
        $sql = 'DELETE FROM cuenta_cobro WHERE mandante = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByDirIp($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE dir_ip = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna impresa sea igual al valor pasado como parámetro
     *
     * @param String $value impresa requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByImpresa($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE impresa = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mediopago_id sea igual al valor pasado como parámetro
     *
     * @param String $value mediopago_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByMediopagoId($value)
    {
        $sql = 'DELETE FROM cuenta_cobro WHERE mediopago_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }








    /**
     * Crear y devolver un objeto del tipo CuentaCobro
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $CuentaCobro CuentaCobro
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $cuentaCobro = new CuentaCobro();

        $cuentaCobro->cuentaId = $row['cuenta_id'];
        $cuentaCobro->usuarioId = $row['usuario_id'];
        $cuentaCobro->fechaCrea = $row['fecha_crea'];
        $cuentaCobro->valor = $row['valor'];
        $cuentaCobro->fechaPago = $row['fecha_pago'];
        $cuentaCobro->puntoventaId = $row['puntoventa_id'];
        $cuentaCobro->estado = $row['estado'];
        $cuentaCobro->clave = $row['clave'];
        $cuentaCobro->mandante = $row['mandante'];
        $cuentaCobro->dirIp = $row['dir_ip'];
        $cuentaCobro->impresa = $row['impresa'];
        $cuentaCobro->mediopagoId = $row['mediopago_id'];
        $cuentaCobro->observacion = $row['observacion'];
        $cuentaCobro->mensajeUsuario = $row['mensaje_usuario'];
        $cuentaCobro->usucambioId = $row['usucambio_id'];
        $cuentaCobro->usurechazaId = $row['usurechaza_id'];
        $cuentaCobro->usupagoId = $row['usupago_id'];
        $cuentaCobro->fechaCambio = $row['fecha_cambio'];
        $cuentaCobro->fechaAccion = $row['fecha_accion'];
        $cuentaCobro->diripAccion = $row['dirip_accion'];
        $cuentaCobro->diripCambio = $row['dirip_cambio'];
        $cuentaCobro->creditos = $row['creditos'];
        $cuentaCobro->creditosBase = $row['creditos_base'];
        $cuentaCobro->impuesto = $row['impuesto'];
        $cuentaCobro->costo = $row['costo'];
        $cuentaCobro->transproductoId = $row['transproducto_id'];
        $cuentaCobro->version = $row['version'];
        $cuentaCobro->impuesto2 = $row['impuesto2'];
        $cuentaCobro->productoPagoId = $row['producto_pago_id'];
        $cuentaCobro->puntajeJugador = $row['puntaje_jugador'];
        return $cuentaCobro;
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

?>
