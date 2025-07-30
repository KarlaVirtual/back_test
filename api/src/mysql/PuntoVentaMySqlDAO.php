<?php namespace Backend\mysql;

use Backend\dao\PuntoVentaDAO;
use Backend\dto\Exception;
use Backend\dto\Helpers;
use Backend\dto\BonoInterno;
use Backend\dto\PuntoVenta;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\sql\ConnectionProperty;

use PDO;
/**
 * Clase 'PuntoVentaMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'PuntoVenta'
 *
 * Ejemplo de uso:
 * $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class PuntoVentaMySqlDAO implements PuntoVentaDAO
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
    public function load($id)
    {
        $sql = 'SELECT * FROM punto_venta WHERE puntoventa_id = ?';
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
        $sql = 'SELECT * FROM punto_venta';
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
        $sql = 'SELECT * FROM punto_venta ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $puntoventa_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($puntoventa_id)
    {
        $sql = 'DELETE FROM punto_venta WHERE puntoventa_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($puntoventa_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object puntoVenta puntoVenta
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($puntoVenta)
    {
        $sql = 'INSERT INTO punto_venta (descripcion, ciudad, direccion, telefono, nombre_contacto, estado, usuario_id, ciudad_id, mandante, email, valor_cupo, porcen_comision, periodicidad_id, valor_recarga, valor_cupo2, porcen_comision2, barrio, clasificador1_id, clasificador2_id, clasificador3_id, clasificador4_id, creditos, creditos_base, creditos_ant, creditos_base_ant, cupo_recarga, moneda, idioma, propio,cuentacontable_id,cuentacontablecierre_id,codigo_personalizado,header_recibopagopremio,footer_recibopagopremio,header_recibopagoretiro,footer_recibopagoretiro,tipo_tienda,impuesto_pagopremio,cedula,identificacion_ip,facebook,facebook_verificacion,instagram,instagram_verificacion,whatsApp,whatsApp_verificacion,otraredessociales,otraredessociales_verificacion, otraredessocialesname, premiofisico) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);
        $Helpers = new Helpers();

        $sqlQuery->set($puntoVenta->descripcion);
        $sqlQuery->set($puntoVenta->ciudad);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->encode_data($puntoVenta->direccion));
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->encode_data($puntoVenta->telefono));
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($puntoVenta->nombreContacto));
        $sqlQuery->set($puntoVenta->estado);
        $sqlQuery->set($puntoVenta->usuarioId);
        $sqlQuery->set($puntoVenta->ciudadId);
        $sqlQuery->set($puntoVenta->mandante);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data($puntoVenta->email));
        $sqlQuery->set($puntoVenta->valorCupo);
        $sqlQuery->set($puntoVenta->porcenComision);
        $sqlQuery->set($puntoVenta->periodicidadId);
        $sqlQuery->set($puntoVenta->valorRecarga);
        $sqlQuery->set($puntoVenta->valorCupo2);
        $sqlQuery->set($puntoVenta->porcenComision2);
        $sqlQuery->set($puntoVenta->barrio);
        $sqlQuery->set($puntoVenta->clasificador1Id);
        $sqlQuery->set($puntoVenta->clasificador2Id);
        $sqlQuery->set($puntoVenta->clasificador3Id);
        if($puntoVenta->clasificador4Id == ''){
            $puntoVenta->clasificador4Id='0';
        }
        $sqlQuery->set($puntoVenta->clasificador4Id);
        $sqlQuery->setSIN($puntoVenta->creditos);
        $sqlQuery->setSIN($puntoVenta->creditosBase);
        $sqlQuery->set($puntoVenta->creditosAnt);
        $sqlQuery->set($puntoVenta->creditosBaseAnt);
        $sqlQuery->setSIN($puntoVenta->cupoRecarga);
        $sqlQuery->set($puntoVenta->moneda);
        $sqlQuery->set($puntoVenta->idioma);

        if($puntoVenta->propio == ""){
            $puntoVenta->propio = 'S';
        }
        $sqlQuery->set($puntoVenta->propio);


        if($puntoVenta->cuentacontableId == ""){
            $puntoVenta->cuentacontableId = '0';
        }
        $sqlQuery->set($puntoVenta->cuentacontableId);

        if($puntoVenta->cuentacontablecierreId == ""){
            $puntoVenta->cuentacontablecierreId = '0';
        }
        $sqlQuery->set($puntoVenta->cuentacontablecierreId);

        $sqlQuery->set($puntoVenta->codigoPersonalizado);

        $sqlQuery->set($puntoVenta->headerRecibopagopremio);
        $sqlQuery->set($puntoVenta->footerRecibopagopremio);
        $sqlQuery->set($puntoVenta->headerRecibopagoretiro);
        $sqlQuery->set($puntoVenta->footerRecibopagoretiro);
        if($puntoVenta->tipoTienda==''){
            $puntoVenta->tipoTienda='F';
        }
        $sqlQuery->set($puntoVenta->tipoTienda);
        if($puntoVenta->impuestoPagopremio==''){
            $puntoVenta->impuestoPagopremio='0';
        }
        $sqlQuery->set($puntoVenta->impuestoPagopremio);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT', true)->encode_data($puntoVenta->cedula));

        if($puntoVenta->identificacionIp == ''){
            $puntoVenta->identificacionIp='0';
        }

        if($puntoVenta->identificacionIp == 'N'){
            $puntoVenta->identificacionIp='0';
        }
        if($puntoVenta->identificacionIp == 'S'){
            $puntoVenta->identificacionIp='1';
        }

        $sqlQuery->set($puntoVenta->identificacionIp);

        $sqlQuery->set($puntoVenta->facebook);


        if($puntoVenta->facebookVerificacion==''){
            $puntoVenta->facebookVerificacion='0';
        }
        if($puntoVenta->facebookVerificacion=='S'){
            $puntoVenta->facebookVerificacion='1';
        }
        if($puntoVenta->facebookVerificacion=='N'){
            $puntoVenta->facebookVerificacion='0';
        }
        $sqlQuery->set($puntoVenta->facebookVerificacion);

        $sqlQuery->set($puntoVenta->instagram);

        if($puntoVenta->instagramVerificacion==''){
            $puntoVenta->instagramVerificacion='0';
        }
        if($puntoVenta->instagramVerificacion=='S'){
            $puntoVenta->instagramVerificacion='1';
        }
        if($puntoVenta->instagramVerificacion=='N'){
            $puntoVenta->instagramVerificacion='0';
        }
        $sqlQuery->set($puntoVenta->instagramVerificacion);

        $sqlQuery->set($puntoVenta->whatsApp);

        if($puntoVenta->whatsAppVerificacion==''){
            $puntoVenta->whatsAppVerificacion='0';
        }
        if($puntoVenta->whatsAppVerificacion=='S'){
            $puntoVenta->whatsAppVerificacion='1';
        }
        if($puntoVenta->whatsAppVerificacion=='N'){
            $puntoVenta->whatsAppVerificacion='0';
        }
        $sqlQuery->set($puntoVenta->whatsAppVerificacion);

        $sqlQuery->set($puntoVenta->otraRedesSocial);

        if($puntoVenta->otraRedesSocialVerificacion==''){
            $puntoVenta->otraRedesSocialVerificacion='0';
        }
        if($puntoVenta->otraRedesSocialVerificacion=='S'){
            $puntoVenta->otraRedesSocialVerificacion='1';
        }
        if($puntoVenta->otraRedesSocialVerificacion=='N'){
            $puntoVenta->otraRedesSocialVerificacion='0';
        }
        $sqlQuery->set($puntoVenta->otraRedesSocialVerificacion);

        $sqlQuery->set($puntoVenta->otraRedesSocialName);


        $sqlQuery->set($puntoVenta->PhysicalPrize);

        $id = $this->executeInsert($sqlQuery);
        $puntoVenta->puntoventaId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object puntoVenta puntoVenta
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($puntoVenta,$whereFix = "")
    {

        $strCreditos = '';
        $strCreditosBase = '';
        $strCupoRecarga = '';

        if (strpos($puntoVenta->creditos, "creditos") !== false) {
            $strCreditos = 'creditos = ?,';
        }

        if (strpos($puntoVenta->creditosBase, "creditos_base") !== false) {
            $strCreditosBase = 'creditos_base = ?,';
        }

        if (strpos($puntoVenta->cupoRecarga, "cupo_recarga") !== false) {
            $strCupoRecarga = 'cupo_recarga = ?,';
        }

        $sql = 'UPDATE punto_venta SET descripcion = ?, ciudad = ?, direccion = ?, telefono = ?, nombre_contacto = ?, estado = ?, usuario_id = ?, ciudad_id = ?, mandante = ?, email = ?, valor_cupo = ?, porcen_comision = ?, periodicidad_id = ?, valor_recarga = ?, valor_cupo2 = ?, porcen_comision2 = ?, barrio = ?, clasificador1_id = ?, clasificador2_id = ?, clasificador3_id = ?, clasificador4_id = ?,  ' . $strCreditos . ' ' . $strCreditosBase . ' creditos_ant = ?, creditos_base_ant = ?,' . $strCupoRecarga . ' moneda = ?, idioma = ?, propio = ?, cuentacontable_id = ?,cuentacontablecierre_id = ?, codigo_personalizado = ?, header_recibopagopremio = ?,footer_recibopagopremio = ?,header_recibopagoretiro = ?,footer_recibopagoretiro = ?,tipo_tienda = ?, impuesto_pagopremio = ?, cedula= ?, identificacion_ip = ?,facebook = ?,facebook_verificacion = ?,instagram = ?,instagram_verificacion = ?, whatsApp = ?,whatsApp_verificacion = ?, otraredessociales = ?, otraredessociales_verificacion = ?, otraredessocialesname = ?, premiofisico=?  WHERE puntoventa_id = ? ' . $whereFix;
        $sqlQuery = new SqlQuery($sql);
        $Helpers = new Helpers();

        $sqlQuery->set($puntoVenta->descripcion);
        $sqlQuery->set($puntoVenta->ciudad);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->encode_data($puntoVenta->direccion));
        $sqlQuery->set($puntoVenta->telefono);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($puntoVenta->nombreContacto));
        $sqlQuery->set($puntoVenta->estado);
        $sqlQuery->set($puntoVenta->usuarioId);
        $sqlQuery->set($puntoVenta->ciudadId);
        $sqlQuery->set($puntoVenta->mandante);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data($puntoVenta->email));
        $sqlQuery->set($puntoVenta->valorCupo);
        $sqlQuery->set($puntoVenta->porcenComision);
        $sqlQuery->set($puntoVenta->periodicidadId);
        $sqlQuery->set($puntoVenta->valorRecarga);
        $sqlQuery->set($puntoVenta->valorCupo2);
        $sqlQuery->set($puntoVenta->porcenComision2);
        $sqlQuery->set($puntoVenta->barrio);
        $sqlQuery->set($puntoVenta->clasificador1Id);
        $sqlQuery->set($puntoVenta->clasificador2Id);
        $sqlQuery->set($puntoVenta->clasificador3Id);
        if($puntoVenta->clasificador4Id == ''){
            $puntoVenta->clasificador4Id='0';
        }
        $sqlQuery->set($puntoVenta->clasificador4Id);

        if ($strCreditos != '') {
            $sqlQuery->setSIN($puntoVenta->creditos);
        }

        if ($strCreditosBase != '') {
            $sqlQuery->setSIN($puntoVenta->creditosBase);
        }

        $sqlQuery->set($puntoVenta->creditosAnt);
        $sqlQuery->set($puntoVenta->creditosBaseAnt);


        if ($strCupoRecarga != '') {
            $sqlQuery->setSIN($puntoVenta->cupoRecarga);
        }

        $sqlQuery->set($puntoVenta->moneda);
        $sqlQuery->set($puntoVenta->idioma);

        if($puntoVenta->propio == ""){
            $puntoVenta->propio = 'S';
        }
        $sqlQuery->set($puntoVenta->propio);


        if($puntoVenta->cuentacontableId == ""){
            $puntoVenta->cuentacontableId = '0';
        }
        $sqlQuery->set($puntoVenta->cuentacontableId);

        if($puntoVenta->cuentacontablecierreId == ""){
            $puntoVenta->cuentacontablecierreId = '0';
        }
        $sqlQuery->set($puntoVenta->cuentacontablecierreId);

        $sqlQuery->set($puntoVenta->codigoPersonalizado);

        $sqlQuery->set($puntoVenta->headerRecibopagopremio);
        $sqlQuery->set($puntoVenta->footerRecibopagopremio);
        $sqlQuery->set($puntoVenta->headerRecibopagoretiro);
        $sqlQuery->set($puntoVenta->footerRecibopagoretiro);
        if($puntoVenta->tipoTienda==''){
            $puntoVenta->tipoTienda='F';
        }
        $sqlQuery->set($puntoVenta->tipoTienda);
        if($puntoVenta->impuestoPagopremio==''){
            $puntoVenta->impuestoPagopremio='0';
        }
        $sqlQuery->set($puntoVenta->impuestoPagopremio);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT', true)->encode_data($puntoVenta->cedula));

        if($puntoVenta->identificacionIp == ''){
            $puntoVenta->identificacionIp='0';
        }

        if($puntoVenta->identificacionIp == 'N'){
            $puntoVenta->identificacionIp='0';
        }
        if($puntoVenta->identificacionIp == 'S'){
            $puntoVenta->identificacionIp='1';
        }

        $sqlQuery->set($puntoVenta->identificacionIp);


        $sqlQuery->set($puntoVenta->facebook);

        if($puntoVenta->facebookVerificacion == ''){
            $puntoVenta->facebookVerificacion='0';
        }
        if($puntoVenta->facebookVerificacion=='S'){
            $puntoVenta->facebookVerificacion='1';
        }

        if($puntoVenta->facebookVerificacion =='N'){
            $puntoVenta->facebookVerificacion='0';
        }
        $sqlQuery->set($puntoVenta->facebookVerificacion);

        $sqlQuery->set($puntoVenta->instagram);

        if($puntoVenta->instagramVerificacion == ''){
            $puntoVenta->instagramVerificacion ='0';
        }
        if($puntoVenta->instagramVerificacion =='S'){
            $puntoVenta->instagramVerificacion='1';
        }
        if($puntoVenta->instagramVerificacion =='N'){
            $puntoVenta->instagramVerificacion ='0';
        }
        $sqlQuery->set($puntoVenta->instagramVerificacion);

        $sqlQuery->set($puntoVenta->whatsApp);

        if($puntoVenta->whatsAppVerificacion == ''){
            $puntoVenta->whatsAppVerificacion ='0';
        }
        if($puntoVenta->whatsAppVerificacion =='S'){
            $puntoVenta->whatsAppVerificacion ='1';
        }
        if($puntoVenta->whatsAppVerificacion =='N'){
            $puntoVenta->whatsAppVerificacion ='0';
        }
        $sqlQuery->set($puntoVenta->whatsAppVerificacion);

        $sqlQuery->set($puntoVenta->otraRedesSocial);

        if($puntoVenta->otraRedesSocialVerificacion==''){
            $puntoVenta->otraRedesSocialVerificacion ='0';
        }

        if($puntoVenta->otraRedesSocialVerificacion =='S'){
            $puntoVenta->otraRedesSocialVerificacion ='1';
        }
        if($puntoVenta->otraRedesSocialVerificacion =='N'){
            $puntoVenta->otraRedesSocialVerificacion ='0';
        }
        $sqlQuery->set($puntoVenta->otraRedesSocialVerificacion);

        $sqlQuery->set($puntoVenta->otraRedesSocialName);

        $sqlQuery->set($puntoVenta->PhysicalPrize);


        $sqlQuery->set($puntoVenta->puntoventaId);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Update record in table
     *
     * @param PuntoVentaMySql puntoVenta
     */
    public function updateCreditosConCheck($puntoVenta)
    {
        $sql = 'UPDATE punto_venta SET cupo_recarga= ? ,creditos = ?, creditos_base = ?, creditos_ant = creditos, creditos_base_ant = creditos_base WHERE puntoventa_id = ? AND cupo_recarga - ? >=0  AND creditos - ? >=0  AND creditos_base - ? >=0';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setSIN($puntoVenta->cupoRecarga);
        $sqlQuery->setSIN($puntoVenta->creditos);
        $sqlQuery->setSIN($puntoVenta->creditosBase);

        $sqlQuery->set($puntoVenta->puntoventaId);

        $sqlQuery->setSIN($puntoVenta->cupoRecarga);
        $sqlQuery->setSIN($puntoVenta->creditos);
        $sqlQuery->setSIN($puntoVenta->creditosBase);


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
        $sql = 'DELETE FROM punto_venta';
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
    public function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ciudad sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByCiudad($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE ciudad = ?';
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
    public function queryByDireccion($value)
    {
        $Helpers = new Helpers();
        $field = 'punto_venta.direccion';
        $field2 = $Helpers->set_custom_field($field);
        $sql = "SELECT * FROM punto_venta WHERE $field2 = ?";
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
    public function queryByTelefono($value)
    {
        $Helpers = new Helpers();
        $field = 'punto_venta.telefono';
        $field2 = $Helpers->set_custom_field($field);
        $sql = "SELECT * FROM punto_venta WHERE $field2 = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre_contacto sea igual al valor pasado como parámetro
     *
     * @param String $value nombre_contacto requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByNombreContacto($value)
    {
        $Helpers = new Helpers();
        $field = 'punto_venta.nombre_contacto';
        $field2 = $Helpers->set_custom_field($field);
        $sql = "SELECT * FROM punto_venta WHERE $field2 = ?";
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
        $sql = 'SELECT * FROM punto_venta WHERE estado = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByUsuarioId($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE usuario_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByCiudadId($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE ciudad_id = ?';
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
        $sql = 'SELECT * FROM punto_venta WHERE mandante = ?';
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
    public function queryByEmail($value)
    {
        $Helpers = new Helpers();
        $field = 'punto_venta.email';
        $field2 = $Helpers->set_custom_field($field);
        $sql = "SELECT * FROM punto_venta WHERE $field2 = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_cupo sea igual al valor pasado como parámetro
     *
     * @param String $value valor_cupo requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByValorCupo($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE valor_cupo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_comision sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_comision requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByPorcenComision($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE porcen_comision = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna periodicidad_id sea igual al valor pasado como parámetro
     *
     * @param String $value periodicidad_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByPeriodicidadId($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE periodicidad_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value valor_recarga requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByValorRecarga($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE valor_recarga = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_cupo2 sea igual al valor pasado como parámetro
     *
     * @param String $value valor_cupo2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByValorCupo2($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE valor_cupo2 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_comision2 sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_comision2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByPorcenComision2($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE porcen_comision2 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna barrio sea igual al valor pasado como parámetro
     *
     * @param String $value barrio requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByBarrio($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE barrio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clasificador1_id sea igual al valor pasado como parámetro
     *
     * @param String $value clasificador1_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByClasificador1Id($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE clasificador1_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clasificador2_id sea igual al valor pasado como parámetro
     *
     * @param String $value clasificador2_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByClasificador2Id($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE clasificador2_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clasificador3_id sea igual al valor pasado como parámetro
     *
     * @param String $value clasificador3_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByClasificador3Id($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE clasificador3_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByCreditos($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE creditos = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByCreditosBase($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE creditos_base = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByCreditosAnt($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE creditos_ant = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByCreditosBaseAnt($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE creditos_base_ant = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cupo_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value cupo_recarga requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByCupoRecarga($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE cupo_recarga = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    public function queryByHeaderReciboPagoPremio($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE header_recibopagopremio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    public function queryByFooterReciboPagoPremio($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE footer_recibopagopremio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    public function queryByHeaderReciboPagoRetiro($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE header_recibopagoretiro = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    public function queryByFooterReciboPagoRetiro($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE footer_recibopagoretiro = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    public function queryByTipoTienda($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE tipo_tienda = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    public function queryByImpuestoPagoPremio($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE impuesto_pagopremio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
    public function deleteByDescripcion($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE descripcion = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByCiudad($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE ciudad = ?';
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
    public function deleteByDireccion($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE direccion = ?';
        $sqlQuery = new SqlQuery($sql);
        $Helpers = new Helpers();
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->encode_data($value));
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
    public function deleteByTelefono($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE telefono = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre_contacto sea igual al valor pasado como parámetro
     *
     * @param String $value nombre_contacto requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByNombreContacto($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE nombre_contacto = ?';
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
        $sql = 'DELETE FROM punto_venta WHERE estado = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByUsuarioId($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE usuario_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByCiudadId($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE ciudad_id = ?';
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
        $sql = 'DELETE FROM punto_venta WHERE mandante = ?';
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
    public function deleteByEmail($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE email = ?';
        $sqlQuery = new SqlQuery($sql);
        $Helpers = new Helpers();
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data($value));
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_cupo sea igual al valor pasado como parámetro
     *
     * @param String $value valor_cupo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByValorCupo($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE valor_cupo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_comision sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_comision requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByPorcenComision($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE porcen_comision = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna periodicidad_id sea igual al valor pasado como parámetro
     *
     * @param String $value periodicidad_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByPeriodicidadId($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE periodicidad_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value valor_recarga requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByValorRecarga($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE valor_recarga = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_cupo2 sea igual al valor pasado como parámetro
     *
     * @param String $value valor_cupo2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByValorCupo2($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE valor_cupo2 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_comision2 sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_comision2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByPorcenComision2($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE porcen_comision2 = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna barrio sea igual al valor pasado como parámetro
     *
     * @param String $value barrio requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByBarrio($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE barrio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clasificador1_id sea igual al valor pasado como parámetro
     *
     * @param String $value clasificador1_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByClasificador1Id($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE clasificador1_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clasificador2_id sea igual al valor pasado como parámetro
     *
     * @param String $value clasificador2_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByClasificador2Id($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE clasificador2_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clasificador3_id sea igual al valor pasado como parámetro
     *
     * @param String $value clasificador3_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByClasificador3Id($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE clasificador3_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByCreditos($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE creditos = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByCreditosBase($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE creditos_base = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByCreditosAnt($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE creditos_ant = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByCreditosBaseAnt($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE creditos_base_ant = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna cupo_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value cupo_recarga requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByCupoRecarga($value)
    {
        $sql = 'DELETE FROM punto_venta WHERE cupo_recarga = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    public function deleteByHeaderReciboPagoPremio($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE header_recibopagopremio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    public function deleteByFooterReciboPagoPremio($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE footer_recibopagopremio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    public function deleteByHeaderReciboPagoRetiro($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE header_recibopagoretiro = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    public function deleteByFooterReciboPagoRetiro($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE footer_recibopagoretiro = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    public function deleteByTipoTienda($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE tipo_tienda = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    public function deleteByImpuestoPagoPremio($value)
    {
        $sql = 'SELECT * FROM punto_venta WHERE impuesto_pagopremio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
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
    public function queryUsuariosTree($sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM usuario INNER JOIN punto_venta ON (usuario.puntoventa_id = punto_venta.puntoventa_id)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT  punto_venta.*,usuario.nombre,usuario.usuario_id,usuario_config.*   FROM usuario INNER JOIN punto_venta ON (usuario.puntoventa_id = punto_venta.puntoventa_id) INNER JOIN usuario_config ON (usuario_config.usuario_id = usuario.usuario_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);


        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
    public function queryPuntoVentasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if($fieldName == 'punto_venta.cedula'){
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


        $sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0  AND concesionario.estado=\'A\')  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id) INNER JOIN usuario ON (usuario.usuario_id = punto_venta.usuario_id ) INNER JOIN pais ON (pais.pais_id = usuario.pais_id) INNER JOIN ciudad ON (punto_venta.ciudad_id = ciudad.ciudad_id)  INNER JOIN departamento ON (departamento.depto_id = ciudad.depto_id) LEFT OUTER JOIN clasificador clasificador4 ON (clasificador4.clasificador_id = punto_venta.clasificador4_id)     ' . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT  ' . $select . '   FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0  AND concesionario.estado=\'A\') INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)  INNER JOIN usuario ON (usuario.usuario_id = punto_venta.usuario_id ) INNER JOIN pais ON (pais.pais_id = usuario.pais_id) INNER JOIN ciudad ON (punto_venta.ciudad_id = ciudad.ciudad_id)  INNER JOIN departamento ON (departamento.depto_id = ciudad.depto_id) LEFT OUTER JOIN clasificador clasificador4 ON (clasificador4.clasificador_id = punto_venta.clasificador4_id)     ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);
        try {


        $result = $Helpers->process_data($this->execute2($sqlQuery));
            if($_ENV["debugFixed2"] == '1'){
                $result = array_map(function ($item) {
                    return array_filter($item, function ($key) {
                        return !is_numeric($key); // Filtra claves numéricas
                    }, ARRAY_FILTER_USE_KEY);
                }, $result);
                print_r($result);
            }
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
        }catch (Exception $e){
            if($_ENV["debugFixed2"] == '1'){
                print_r($e);
            }
        }
        return $json;
    }


    /**
     * Realizar una consulta personalizada en la tabla punto\_venta
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

    public function queryPuntoVentasCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
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


        $sql = 'SELECT count(*) count FROM punto_venta
         INNER JOIN usuario ON (usuario.puntoventa_id = punto_venta.usuario_id)
         INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         JOIN pais ON (pais.pais_id = usuario.pais_id)
         JOIN ciudad ON (punto_venta.ciudad_id = ciudad.ciudad_id)
         JOIN departamento ON (departamento.depto_id = ciudad.depto_id)
         LEFT OUTER JOIN clasificador clasificador4 ON (clasificador4.clasificador_id = punto_venta.clasificador4_id)    ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT  ' . $select . ' FROM punto_venta
         INNER JOIN usuario ON (usuario.puntoventa_id = punto_venta.usuario_id)
         INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         JOIN pais ON (pais.pais_id = usuario.pais_id)
         JOIN ciudad ON (punto_venta.ciudad_id = ciudad.ciudad_id)
         JOIN departamento ON (departamento.depto_id = ciudad.depto_id)
         LEFT OUTER JOIN clasificador clasificador4 ON (clasificador4.clasificador_id = punto_venta.clasificador4_id)    ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);


        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta personalizada en la tabla punto\_venta
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta en formato JSON
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return string JSON con el conteo y los datos de la consulta
     */
    public function queryPuntoVentasCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
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


        $sql = 'SELECT count(*) count FROM usuario
         INNER JOIN punto_venta ON (usuario.puntoventa_id = punto_venta.usuario_id)
         INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         JOIN pais ON (pais.pais_id = usuario.pais_id)
         JOIN ciudad ON (punto_venta.ciudad_id = ciudad.ciudad_id)
         JOIN departamento ON (departamento.depto_id = ciudad.depto_id)    ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT  ' . $select . ' FROM usuario
         INNER JOIN punto_venta ON (usuario.puntoventa_id = punto_venta.usuario_id)
         INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         JOIN pais ON (pais.pais_id = usuario.pais_id)
         JOIN ciudad ON (punto_venta.ciudad_id = ciudad.ciudad_id)
         JOIN departamento ON (departamento.depto_id = ciudad.depto_id)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);


        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal FromDateLocal
     * @param String $ToDateLocal ToDateLocal
     * @param String $ConcesionarioId ConcesionarioId
     * @param String $ConcesionarioId2 ConcesionarioId2
     * @param String $ConcesionarioId3 ConcesionarioId3
     * @param String $PaisId PaisId
     * @param String $Mandante Mandante
     * @param String $PuntoVentaId PuntoVentaId
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryFlujoCajaResumido($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId = "", $ConcesionarioId2 = "", $ConcesionarioId3 = "", $PaisId = "", $Mandante = "", $PuntoVentaId = "")
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


        $whereReportConcesionario = "";
        $groupAgent = "y.usupadre_id = uu.usuario_id";

        if ($ConcesionarioId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre_id = '" . $ConcesionarioId . "' ";
        }

        if ($ConcesionarioId2 != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre2_id = '" . $ConcesionarioId2 . "' ";
            $groupAgent = "y.usupadre2_id = uu.usuario_id";

        }

        if ($ConcesionarioId3 != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre3_id = '" . $ConcesionarioId3 . "' ";
        }

        if ($PuntoVentaId != "") {
            if(strpos($PuntoVentaId,',') !== false){
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id IN (" . $PuntoVentaId . ") ";

            }else{
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id = '" . $PuntoVentaId . "' ";

            }
        }

        if ($PaisId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND d.pais_id = '" . $PaisId . "' ";
        }

        if ($Mandante != "") {

            $whereReportConcesionario = $whereReportConcesionario . " AND d.mandante IN (" . $Mandante . ") ";
        }

        $whereReportConcesionario = $whereReportConcesionario . "  ";

        //$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where ;


        $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);

        $where1 = "and (((x.fecha_crea))) >=
                  ('" . $FromDateLocal . "')
              and (((x.fecha_crea))) <=
                  ('" . $ToDateLocal . "')";

        $where2 = "and (((z.fecha_crea))) >=
            ('" . $FromDateLocal . "')
        and (((z.fecha_crea))) <=
            ('" . $ToDateLocal . "')";

        $where1 = "and (((x.fecha_crea))) >=
                  ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')
              and (((x.fecha_crea))) <=
                  ('" . date("Y-m-d", strtotime($ToDateLocal)) . "')";



        $where2 = "and (((z.fecha_crea))) >=
            ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')
        and (((z.fecha_crea))) <=
            ('" . date("Y-m-d", strtotime($ToDateLocal)) . "')";

        $where3 = " and (((z.fecha_pago))) >=
            ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')
        and (((z.fecha_pago))) <=
            ('" . date("Y-m-d", strtotime($ToDateLocal)) . "') " ;

        if(date("Y-m-d", strtotime($ToDateLocal)) == date("Y-m-d", strtotime($FromDateLocal))){
            $where1 = "and (((x.fecha_crea))) =
                  ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')";



            $where2 = "and (((z.fecha_crea))) =
            ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')";

            $where2 = "and day_dimension.timestampint =
            " . strtotime($FromDateLocal. ' '. 'America/Bogota') . "";


            $where3 = " and (((z.fecha_pago))) =
            ('" . date("Y-m-d", strtotime($FromDateLocal)) . "') " ;

        }


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }

        $sql = 'SELECT  ' . $select . '   FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0  AND concesionario.estado=\'A\') INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sql = "select y.punto_venta,y.mandante,y.usuario_id,
       y.fecha_crea,
       y.moneda,
       uu.nombre agente,
       y.pais_nom,
       y.pais_iso,
       y.usupadre_id,
       y.usupadre2_id,
       sum(y.cant_tickets)           cant_tickets,
       sum(y.valor_entrada_efectivo) valor_entrada_efectivo,
       sum(y.valor_entrada_bono)     valor_entrada_bono,
       sum(y.valor_entrada_recarga)  valor_entrada_recarga,
       sum(y.valor_entrada_recarga_anuladas)  valor_entrada_recarga_anuladas,
        sum(y.valor_entrada_recarga_agentes)  valor_entrada_recarga_agentes,
       sum(y.valor_entrada_traslado) valor_entrada_traslado,
       sum(y.valor_salida_traslado)  valor_salida_traslado,
       sum(y.valor_salida_notaret)   valor_salida_notaret,
       sum(y.valor_entrada)          valor_entrada,
       sum(y.valor_salida)           valor_salida,
       sum(y.valor_salida_efectivo)  valor_salida_efectivo,
       sum(y.premios_pend)           premios_pend,
       sum(y.impuestos)             impuestos,
             sum(y.apuestas_void)                  apuestas_void,
              sum(y.premios_void)                   premios_void
from (select *

      from (select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "   punto_venta,pv.usuario_id,
            pv.mandante    mandante,
                   x.fecha_crea,
                   d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
                   
                   0                                                                              cant_tickets,
                   sum(case
                         when x.tipomov_id = 'S' then 0
                         when x.tipomov_id = 'E' and x.ticket_id <> '' and not x.ticket_id is null then x.valor
                         else x.valor_forma1 end - case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end -
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_efectivo,
                   sum(case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end)               valor_entrada_bono,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' and x.cupolog_id = '0'   and x.recarga_id != '0'
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga,
                   sum(case
                         when  (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' and x.cupolog_id = '0'  and x.recarga_id != '0'   and x.recarga_id != '' 
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga_anuladas, 
                          sum(case
                           when x.traslado = 'N' and ( x.ticket_id = '' or x.ticket_id is null) AND x.cupolog_id != '0' and ((x.recarga_id = '0' or x.recarga_id is null) AND ( x.ticket_id is null OR x.ticket_id ='')) and x.tipomov_id = 'E'
                               then x.valor
                           else 0 end)                                                                valor_entrada_recarga_agentes,
                   sum(
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_traslado,
                   sum(case when x.tipomov_id = 'S' and x.traslado = 'S' then x.valor else 0 end) valor_salida_traslado,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' AND cuenta_id != ''
                           then x.valor
                        when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' AND cuenta_id != ''
                           then -x.valor
                         else 0 end)                                                              valor_salida_notaret,
                   sum(case when x.tipomov_id = 'E' then x.valor else 0 end)                      valor_entrada,
                   sum(case when x.tipomov_id = 'S' then x.valor else 0 end)                      valor_salida,
                   sum(case
                         when x.tipomov_id = 'S' and x.traslado <> 'S' and ((x.ticket_id <> '' and not x.ticket_id is null) OR ( x.recarga_id <> '' and not x.recarga_id is null) )
                           then x.valor
                         else 0 end)                                                              valor_salida_efectivo,
                   0                                                                              premios_pend,
                   SUM(valor_iva)                                                                 impuestos,
             0                  apuestas_void,
             0                  premios_void
            from flujo_caja x
                    inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = x.fecha_crea)
                    inner join usuario d on ( x.usucrea_id = d.usuario_id)
                   left outer join punto_venta e on ( d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on ( e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   left outer join concesionario c on ( e.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 AND c.estado='A')
            where 1 = 1
              " . $where1 . "
              " . $whereReportConcesionario . "
            group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,x.fecha_crea,d.moneda" : "d.mandante,c.usupadre_id,c.usupadre2_id, x.fecha_crea,d.moneda") . ") z
      union
      select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "     punto_venta,pv.usuario_id,
            pv.mandante    mandante,
             DATE_FORMAT('" . $ToDateLocal . "', '%Y-%m-%d')      fecha_crea,
             d.moneda,
             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             0                 cant_tickets,
             0                 valor_entrada_efectivo,
             0                 valor_entrada_bono,
             0                 valor_entrada_recarga,
             0                 valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                 valor_entrada_traslado,
             0                 valor_salida_traslado,
             0                 valor_salida_notaret,
             0                 valor_entrada,
             0                 valor_salida,
             0                 valor_salida_efectivo,
             sum(z.vlr_premio) premios_pend,
             0                  impuestos,
             0                  apuestas_void,
             0                  premios_void
      from it_ticket_enc z
            inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = z.fecha_maxpago)
            inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             left outer join concesionario c on ( e.usuario_id = c.usuhijo_id and c.prodinterno_id =0 AND c.estado='A')
      where z.fecha_crea <= '" . $ToDateLocal . "'
        and z.premiado = 'S'
        and z.premio_pagado = 'N'
        and date(z.fecha_maxpago) >= date(now())
        " . $whereReportConcesionario . "

      group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,DATE_FORMAT('" . $ToDateLocal . "', '%Y-%m-%d'),d.moneda " : "d.mandante,c.usupadre_id,c.usupadre2_id, DATE_FORMAT('" . $ToDateLocal . "', '%Y-%m-%d'),d.moneda ") . "
      
            union
      select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "     punto_venta,pv.usuario_id,
            pv.mandante    mandante,
             z.fecha_crea,
             d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             0 cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos,
             SUM(z.vlr_apuesta)                  apuestas_void,
             0                  premios_void
      from it_ticket_enc z
            inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = z.fecha_crea)

             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             left outer join concesionario c on ( e.usuario_id = c.usuhijo_id and c.prodinterno_id =0 AND c.estado='A')

      where 1 = 1 and z.bet_status='A' and z.eliminado='N'
        " . $where2 . "
        " . $whereReportConcesionario . "
      group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,z.fecha_crea,d.moneda" : "d.mandante,c.usupadre_id,c.usupadre2_id, z.fecha_crea,d.moneda") . "
union
      select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "     punto_venta,pv.usuario_id,
            pv.mandante    mandante,
             z.fecha_crea,
             d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             count(z.ticket_id) cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos,
             0                  apuestas_void,
             0                  premios_void
      from it_ticket_enc z
                          inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = z.fecha_crea)

             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             left outer join concesionario c on ( e.usuario_id = c.usuhijo_id and c.prodinterno_id =0)

      where 1 = 1
        " . $where2 . "
        " . $whereReportConcesionario . "

      group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,z.fecha_crea,d.moneda" : "d.mandante,c.usupadre_id,c.usupadre2_id, z.fecha_crea,d.moneda") . "
                    union
      select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "     punto_venta,pv.usuario_id,
            pv.mandante    mandante,
             z.fecha_crea,
             d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             0 cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             
             
             0                  impuestos,
             0                  apuestas_void,
             SUM(z.vlr_premio)                  premios_void
      from it_ticket_enc z
                    inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = z.fecha_pago)
             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             left outer join concesionario c on ( e.usuario_id = c.usuhijo_id and c.prodinterno_id =0 AND c.estado='A')

      where 1 = 1 and z.bet_status='A' and z.eliminado='N' and premio_pagado='S'
        " . $where3 . "
        " . $whereReportConcesionario . "


      group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,z.fecha_pago,d.moneda" : "d.mandante,c.usupadre_id,c.usupadre2_id, z.fecha_pago,d.moneda") . "

      ) y
      
                         left outer join usuario uu on ( " . $groupAgent . ")

group by " . (($grouping == 0) ? "y.mandante,y.punto_venta,y.fecha_crea,y.moneda" : "y.mandante,y.usupadre_id,y.usupadre2_id,y.fecha_crea,y.moneda") . "
order by " . (($grouping == 0) ? "y.punto_venta,y.fecha_crea " : "y.fecha_crea ") . "
";
        /*union
      select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "     punto_venta,
            pv.mandante    mandante,
             z.fecha_crea,
             d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             count(z.ticket_id) cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos
      from it_ticket_enc z
             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             left outer join concesionario c on ( e.usuario_id = c.usuhijo_id and c.prodinterno_id =0)

      where 1 = 1
        " . $where2 . "
        " . $whereReportConcesionario . "

      group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,z.fecha_crea,d.moneda" : "d.mandante,z.fecha_crea,d.moneda") . "*/

        $sqlQuery = new SqlQuery($sql);

        if ($grouping == 0) {

        }
        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $result = $Helpers->process_data($this->execute2($sqlQuery));




        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
 * Realizar una consulta resumida de flujo de caja con horas en la tabla punto\_venta
 *
 * @param string $select campos de consulta
 * @param string $sidx columna para ordenar
 * @param string $sord orden de los datos asc | desc
 * @param int $start inicio de la consulta
 * @param int $limit límite de la consulta
 * @param string $filters condiciones de la consulta en formato JSON
 * @param boolean $searchOn utilizar los filtros o no
 * @param int $grouping indicador de agrupación
 * @param string $FromDateLocal fecha de inicio en formato local
 * @param string $ToDateLocal fecha de fin en formato local
 * @param string $ConcesionarioId ID del concesionario opcional
 * @param string $ConcesionarioId2 ID del segundo concesionario opcional
 * @param string $ConcesionarioId3 ID del tercer concesionario opcional
 * @param string $PaisId ID del país opcional*/
    public function queryFlujoCajaResumidoConHoras($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId = "", $ConcesionarioId2 = "", $ConcesionarioId3 = "", $PaisId = "", $Mandante = "", $PuntoVentaId = "")
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
                $fieldName = $Helpers->set_custom_secret_key($rule->field);
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


        $whereReportConcesionario = "";
        $groupAgent = "y.usupadre_id = uu.usuario_id";

        if ($ConcesionarioId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre_id = '" . $ConcesionarioId . "' ";
        }

        if ($ConcesionarioId2 != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre2_id = '" . $ConcesionarioId2 . "' ";
            $groupAgent = "y.usupadre2_id = uu.usuario_id";

        }

        if ($ConcesionarioId3 != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre3_id = '" . $ConcesionarioId3 . "' ";
        }

        if ($PuntoVentaId != "") {
             if(strpos($PuntoVentaId,',') !== false){
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id IN (" . $PuntoVentaId . ") ";

            }else {
                 $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id = '" . $PuntoVentaId . "' ";
             }
        }

        if ($PaisId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND d.pais_id = '" . $PaisId . "' ";
        }

        if ($Mandante != "") {
        if ($PuntoVentaId != "") {
        }else{
            $whereReportConcesionario = $whereReportConcesionario . " AND d.mandante IN (" . $Mandante . ") ";
        }
        }



        $whereReportConcesionario = $whereReportConcesionario . "  ";

        //$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where ;

        $HourFromDateLocal = "";

        if(strpos($FromDateLocal," ") !== false){
            $HourFromDateLocal = explode(" ",$FromDateLocal)[1];
            $FromDateLocal = explode(" ",$FromDateLocal)[0];
        }

        $HourToDateLocal = "";

        if(strpos($ToDateLocal," ") !== false){
            $HourToDateLocal = explode(" ",$ToDateLocal)[1];
            $ToDateLocal = explode(" ",$ToDateLocal)[0];
        }


        $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);

        $where1 = "and (((x.fecha_crea))) >=
                  ('" . $FromDateLocal . "')
              and (((x.fecha_crea))) <=
                  ('" . $ToDateLocal . "')";

        $where2 = "and (((z.fecha_crea))) >=
            ('" . $FromDateLocal . "')
        and (((z.fecha_crea))) <=
            ('" . $ToDateLocal . "')";

        $where1 = "and (((x.fecha_crea))) >=
                  ('" . date("Y-m-d", strtotime($FromDateLocal)) . "') 
              and (((x.fecha_crea))) <=
                  ('" . date("Y-m-d", strtotime($ToDateLocal)) . "')";



        $where2 = "and (((z.fecha_crea))) >=
            ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')

        and (((z.fecha_crea))) <=
            ('" . date("Y-m-d", strtotime($ToDateLocal)) . "')";

        $where3 = " and (((z.fecha_pago))) >=
            ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')

        and (((z.fecha_pago))) <=
            ('" . date("Y-m-d", strtotime($ToDateLocal)) . "') " ;


        if(date("Y-m-d", strtotime($ToDateLocal)) == date("Y-m-d", strtotime($FromDateLocal))){
            $where1 = "and (((x.fecha_crea))) =
                  ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')";


            $where2 = "and (((z.fecha_crea))) =
            ('" . date("Y-m-d", strtotime($FromDateLocal)) . "')";

            $where2 = "and day_dimension.timestampint =
            " . strtotime($FromDateLocal. ' '. 'America/Bogota') . "";


            $where3 = " and (((z.fecha_pago))) =
            ('" . date("Y-m-d", strtotime($FromDateLocal)) . "') " ;

        }

        $sql = 'SELECT  ' . $select . '   FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0  AND concesionario.estado=\'A\') INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sql = "select y.punto_venta,y.mandante,y.usuario_id,
       y.fecha_crea,
       y.moneda,
       uu.nombre agente,
       y.pais_nom,
       y.pais_iso,
       y.usupadre_id,
       y.usupadre2_id,
       sum(y.cant_tickets)           cant_tickets,
       sum(y.valor_entrada_efectivo) valor_entrada_efectivo,
       sum(y.valor_entrada_bono)     valor_entrada_bono,
       sum(y.valor_entrada_recarga)  valor_entrada_recarga,
       sum(y.valor_entrada_recarga_anuladas)  valor_entrada_recarga_anuladas,
        sum(y.valor_entrada_recarga_agentes)  valor_entrada_recarga_agentes,
       sum(y.valor_entrada_traslado) valor_entrada_traslado,
       sum(y.valor_salida_traslado)  valor_salida_traslado,
       sum(y.valor_salida_notaret)   valor_salida_notaret,
       sum(y.valor_entrada)          valor_entrada,
       sum(y.valor_salida)           valor_salida,
       sum(y.valor_salida_efectivo)  valor_salida_efectivo,
       sum(y.premios_pend)           premios_pend,
       sum(y.impuestos)             impuestos,
             sum(y.apuestas_void)                  apuestas_void,
              sum(y.premios_void)                   premios_void
from (select *

      from (select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "   punto_venta,pv.usuario_id,
            pv.mandante    mandante,
                   x.fecha_crea,
                   d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
                   
                   0                                                                              cant_tickets,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN case
                                                                     when x.tipomov_id = 'S' then 0
                                                                     when x.tipomov_id = 'E' and x.ticket_id <> '' and not x.ticket_id is null
                                                                         then x.valor
                                                                     else x.valor_forma1 end -
                                                                 case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end -
                                                                 case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end
                           ELSE 0 END) valor_entrada_efectivo,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal'))
                               THEN case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end
                           ELSE 0 END) valor_entrada_bono,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN case
                                                                     when x.traslado = 'N' and
                                                                          (x.ticket_id = '' or x.ticket_id is null) and
                                                                          x.tipomov_id = 'E'   and x.recarga_id != '0'
                                                                         then x.valor
                                                                     else 0 end
                           ELSE 0 END) valor_entrada_recarga,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN case
                                                                     when x.traslado = 'N' and
                                                                          (x.ticket_id = '' or x.ticket_id is null) and
                                                                          x.tipomov_id = 'S'  and x.recarga_id != '0'   and x.recarga_id != '' 
                                                                         then x.valor
                                                                     else 0 end
                           ELSE 0 END) valor_entrada_recarga_anuladas, 
             0                 valor_entrada_recarga_agentes,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN
                               case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end
                           ELSE 0 END) valor_entrada_traslado,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN
                               case when x.tipomov_id = 'S' and x.traslado = 'S' then x.valor else 0 end
                           ELSE 0 END) valor_salida_traslado,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN case
                                                                     when x.traslado = 'N' and
                                                                          (x.ticket_id = '' or x.ticket_id is null) and
                                                                          x.tipomov_id = 'S' AND cuenta_id != ''
                                                                         then x.valor
                                                                         when x.traslado = 'N' and
                                                                          (x.ticket_id = '' or x.ticket_id is null) and
                                                                          x.tipomov_id = 'E' AND cuenta_id != ''
                                                                         then -x.valor
                                                                     else 0 end
                           ELSE 0 END) valor_salida_notaret,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN case when x.tipomov_id = 'E' then x.valor else 0 end
                           ELSE 0 END) valor_entrada,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN case when x.tipomov_id = 'S' then x.valor else 0 end
                           ELSE 0 END) valor_salida,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN case
                                                                     when x.tipomov_id = 'S' and x.traslado <> 'S' and
                                                                          ((x.ticket_id <> '' and not x.ticket_id is null) OR
                                                                           (x.recarga_id <> '' and not x.recarga_id is null))
                                                                         then x.valor
                                                                     else 0 end
                           ELSE 0 END) valor_salida_efectivo,
                   0                   premios_pend,
                   SUM(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN valor_iva
                           ELSE 0 END) impuestos,
             0                  apuestas_void,
             0                  premios_void
            from flujo_caja x
                    inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = x.fecha_crea)
                    inner join usuario d on ( x.usucrea_id = d.usuario_id)
                   left outer join punto_venta e on ( d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on ( e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   inner join concesionario c on ( e.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 AND c.estado='A')
            where 1 = 1
              " . $where1 . "
              " . $whereReportConcesionario . "
            group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,x.fecha_crea,d.moneda" : "d.mandante,x.fecha_crea,d.moneda") . ") z
      
            union
      select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "     punto_venta,pv.usuario_id,
            pv.mandante    mandante,
             z.fecha_crea,
             d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             0 cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                  valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos,
              SUM(CASE
                           WHEN ((CONCAT(z.fecha_crea, ' ', z.hora_crea)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(z.fecha_crea, ' ', z.hora_crea)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN z.vlr_apuesta
                           ELSE 0 END)                 apuestas_void,
             0                  premios_void
      from it_ticket_enc z
            inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = z.fecha_crea)

             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             inner join concesionario c on ( e.usuario_id = c.usuhijo_id and c.prodinterno_id =0 AND c.estado='A')

      where 1 = 1 and z.bet_status='A' and z.eliminado='N'
        " . $where2 . "
        " . $whereReportConcesionario . "
      group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,z.fecha_crea,d.moneda" : "d.mandante,z.fecha_crea,d.moneda") . "

                    union
      select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "     punto_venta,pv.usuario_id,
            pv.mandante    mandante,
             z.fecha_pago,
             d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             0 cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                  valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             
             
             0                  impuestos,
             0                  apuestas_void,
              SUM(CASE
                           WHEN ((CONCAT(z.fecha_pago, ' ', z.hora_pago)) >=
                                 ('$FromDateLocal $HourFromDateLocal')
                               and (CONCAT(z.fecha_pago, ' ', z.hora_pago)) <=
                                   ('$ToDateLocal $HourToDateLocal')) THEN z.vlr_premio
                           ELSE 0 END)                 premios_void
      from it_ticket_enc z
                    inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = z.fecha_pago)
             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             inner join concesionario c on ( e.usuario_id = c.usuhijo_id and c.prodinterno_id =0 AND c.estado='A')

      where 1 = 1 and z.bet_status='A' and z.eliminado='N' and premio_pagado='S'
        " . $where3 . "
        " . $whereReportConcesionario . "


      group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,z.fecha_pago,d.moneda" : "d.mandante,z.fecha_pago,d.moneda") . "

      
      ) y
      
                         left outer join usuario uu on ( " . $groupAgent . ")

group by " . (($grouping == 0) ? "y.mandante,y.punto_venta,y.fecha_crea,y.moneda" : "y.mandante,y.usupadre_id,y.usupadre2_id,y.fecha_crea,y.moneda") . "
order by " . (($grouping == 0) ? "y.punto_venta,y.fecha_crea " : "y.fecha_crea ") . "
";

        /*union
      select " . (($grouping == 1) ? "CONCAT('Puntos Venta',d.moneda)" : "pv.descripcion") . "     punto_venta,
            pv.mandante    mandante,
             z.fecha_crea,
             d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             count(z.ticket_id) cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos
      from it_ticket_enc z
             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             left outer join concesionario c on ( e.usuario_id = c.usuhijo_id and c.prodinterno_id =0)

      where 1 = 1
        " . $where2 . "
        " . $whereReportConcesionario . "

      group by " . (($grouping == 0) ? "d.mandante,pv.descripcion,z.fecha_crea,d.moneda" : "d.mandante,z.fecha_crea,d.moneda") . "*/

        $sqlQuery = new SqlQuery($sql);

        if ($grouping == 0) {

        }

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta resumida de flujo de caja con cajero en la tabla punto\_venta
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta en formato JSON
     * @param boolean $searchOn utilizar los filtros o no
     * @param int $grouping indicador de agrupación
     * @param string $FromDateLocal fecha de inicio en formato local
     * @param string $ToDateLocal fecha de fin en formato local
     * @param string $PuntoVentaId ID del punto de venta opcional
     * @param string $CajeroId ID del cajero opcional
     *
     * @return string JSON con el conteo y los datos de la consulta
     */
    public function queryFlujoCajaResumidoConCajero($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId = "", $CajeroId = "")
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

        $whereReport = "";

        if ($PuntoVentaId != "") {
            if(strpos($PuntoVentaId,',') !== false){
                $whereReport = $whereReport . " AND y.punto_ventaid IN (" . $PuntoVentaId . ") ";

            }else {
                 $whereReport = $whereReport . " AND y.punto_ventaid = '" . $PuntoVentaId . "'";
             }
        }

        if ($CajeroId != "") {

            $whereReport = $whereReport . " AND y.usuario_id = '" . $CajeroId . "'";
        }

        $HourFromDateLocal = "";

        if(strpos($FromDateLocal," ") !== false){
            $HourFromDateLocal = explode(" ",$FromDateLocal)[1];
            $FromDateLocal = explode(" ",$FromDateLocal)[0];
        }

        $HourToDateLocal = "";

        if(strpos($ToDateLocal," ") !== false){
            $HourToDateLocal = explode(" ",$ToDateLocal)[1];
            $ToDateLocal = explode(" ",$ToDateLocal)[0];
        }

        $HourFromDateLocalsql1=" and (((x.fecha_crea))) >=
                  ('" . $FromDateLocal . "')
              and (((x.fecha_crea))) <=
                  ('" . $ToDateLocal . "') ";
        $HourFromDateLocalsql2="";
        $HourFromDateLocalsql3=" and (((z.fecha_crea))) >=
            ('" . $FromDateLocal . "')
        and (((z.fecha_crea))) <=
            ('" . $ToDateLocal . "') ";

        if($HourFromDateLocal != "" && $HourToDateLocal != ""){
            $HourFromDateLocalsql1= " and ((x.fecha_crea='".$FromDateLocal."' AND x.hora_crea >= '".$HourFromDateLocal."'  ) OR (x.fecha_crea='".$ToDateLocal."' AND x.hora_crea <= '".$HourToDateLocal."'  ) ) " ;
            $HourFromDateLocalsql2=" and (((z.hora_crea))) <= ('" . $HourToDateLocal . "') ";
            $HourFromDateLocalsql3= " and ((z.fecha_crea='".$FromDateLocal."' AND z.hora_crea >= '".$HourFromDateLocal."'  ) OR (z.fecha_crea='".$ToDateLocal."' AND z.hora_crea <= '".$HourToDateLocal."'  ) ) " ;

        }

        //$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where ;


        $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);



        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        $sql = 'SELECT  ' . $select . '   FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0  AND concesionario.estado=\'A\') INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sql = "select  /*+ MAX_EXECUTION_TIME(60000) */  y.punto_venta,y.mandante,y.usuario_id,
        y.punto_ventaid,
        y.usuario_id,
        y.login,
       y.fecha_crea,
       y.moneda,
       'PV' agente,
       y.pais_nom,
       y.pais_iso,
       '0' usupadre_id,
        '0' usupadre2_id,
       sum(y.cant_tickets)           cant_tickets,
       sum(y.valor_entrada_efectivo) valor_entrada_efectivo,
       sum(y.valor_entrada_bono)     valor_entrada_bono,
       sum(y.valor_entrada_recarga)  valor_entrada_recarga,
       sum(y.valor_entrada_recarga_anuladas)  valor_entrada_recarga_anuladas,
             sum(y.valor_entrada_recarga_agentes)                 valor_entrada_recarga_agentes,
       sum(y.valor_entrada_traslado) valor_entrada_traslado,
       sum(y.valor_salida_traslado)  valor_salida_traslado,
       sum(y.valor_salida_notaret)   valor_salida_notaret,
       sum(y.valor_entrada)          valor_entrada,
       sum(y.valor_salida)           valor_salida,
       sum(y.valor_salida_efectivo)  valor_salida_efectivo,
       sum(y.premios_pend)           premios_pend,
       sum(y.impuestos)             impuestos
from (select *
      from (select 
                d.mandante,
                f.pais_nom,
                f.iso pais_iso,
                pv.descripcion                                                                 punto_venta,
                  pv.usuario_id                                                                 punto_ventaid,
                  d.usuario_id,
                  d.login,
                   x.fecha_crea,
                   d.moneda,
                   0                                                                              cant_tickets,
                   sum(case
                         when x.tipomov_id = 'S' then 0
                         when x.tipomov_id = 'E' and x.ticket_id <> '' and not x.ticket_id is null then x.valor
                         else x.valor_forma1 end - case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end -
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_efectivo,
                   sum(case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end)               valor_entrada_bono,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' and x.cupolog_id = '0'   and x.recarga_id != '0'
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga, 
                   sum(case
                         when  (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' and x.cupolog_id = '0'  and x.recarga_id != '0'   and x.recarga_id != '' 
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga_anuladas, 
                   sum(case
                           when x.traslado = 'N' and ( x.ticket_id = '' or x.ticket_id is null) AND x.cupolog_id != '0' and ((x.recarga_id = '0' or x.recarga_id is null) AND ( x.ticket_id is null OR x.ticket_id ='')) and x.tipomov_id = 'E'
                               then x.valor
                           else 0 end)                                                                valor_entrada_recarga_agentes,
                   
                   sum(
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_traslado,
                   sum(case when x.tipomov_id = 'S' and x.traslado = 'S' then x.valor else 0 end) valor_salida_traslado,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' AND cuenta_id != ''
                           then x.valor
                       when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' AND cuenta_id != ''
                           then -x.valor
                       
                         else 0 end)                                                              valor_salida_notaret,
                   sum(case when x.tipomov_id = 'E' then x.valor else 0 end)                      valor_entrada,
                   sum(case when x.tipomov_id = 'S' then x.valor else 0 end)                      valor_salida,
                   sum(case
                         when x.tipomov_id = 'S' and x.traslado <> 'S' and ((x.ticket_id <> '' and not x.ticket_id is null) OR ( x.recarga_id <> '' and not x.recarga_id is null) )
                           then x.valor
                         else 0 end)                                                              valor_salida_efectivo,
                   0                                                                              premios_pend,
                   SUM(valor_iva)                                                                 impuestos
            from flujo_caja x
                   inner join usuario d on ( x.usucrea_id = d.usuario_id)
                   left outer join punto_venta e on ( d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
            where 1 = 1
               ".$HourFromDateLocalsql1."
            group by " . (($grouping == 1) ? "d.usuario_id,x.fecha_crea,d.moneda) z" : "d.usuario_id,x.fecha_crea,d.moneda) z") . "
      union
      select  
                d.mandante,
                f.pais_nom,
                f.iso pais_iso,
                pv.descripcion    punto_venta,
             pv.usuario_id     punto_ventaid,
            d.usuario_id,
            d.login,
             DATE_FORMAT('" . $ToDateLocal . "', '%Y-%m-%d')      fecha_crea,
             d.moneda,
             0                 cant_tickets,
             0                 valor_entrada_efectivo,
             0                 valor_entrada_bono,
             0                 valor_entrada_recarga,
             0                 valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                 valor_entrada_traslado,
             0                 valor_salida_traslado,
             0                 valor_salida_notaret,
             0                 valor_entrada,
             0                 valor_salida,
             0                 valor_salida_efectivo,
             sum(z.vlr_premio) premios_pend,
             0                  impuestos
      from it_ticket_enc z
             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
      where z.fecha_crea <= '" . $ToDateLocal . "' ".$HourFromDateLocalsql2."
        and z.premiado = 'S'
        and z.premio_pagado = 'N'
        and date(z.fecha_maxpago) >= date(now())
      group by " . (($grouping == 1) ? "d.usuario_id,DATE_FORMAT('" . $ToDateLocal . "', '%Y-%m-%d'),d.moneda" : "d.usuario_id,DATE_FORMAT('" . $ToDateLocal . "', '%Y-%m-%d'),d.moneda") . "
      union
      select  
                d.mandante,
                f.pais_nom,
                f.iso pais_iso,
                pv.descripcion     punto_venta,
                   pv.usuario_id     punto_ventaid,
            d.usuario_id,
            d.login,
             z.fecha_crea,
             d.moneda,
             count(z.ticket_id) cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                  valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos
      from it_ticket_enc z
             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
      where 1 = 1
         ".$HourFromDateLocalsql3."
      group by " . (($grouping == 1) ? "d.usuario_id,z.fecha_crea,d.moneda" : "d.usuario_id,z.fecha_crea,d.moneda") . ") y
            WHERE 1=1  " . $whereReport . "

group by " . (($grouping == 1) ? "y.usuario_id,y.fecha_crea,y.moneda" : "y.fecha_crea,y.moneda") . "
order by " . (($grouping == 1) ? "y.usuario_id,y.fecha_crea" : "y.fecha_crea") . "
";
        $sqlQuery = new SqlQuery($sql);

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }


        $result = $Helpers->process_data($this->execute2($sqlQuery));


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta resumida de flujo de caja en la tabla punto\_venta
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta en formato JSON
     * @param boolean $searchOn utilizar los filtros o no
     * @param int $grouping indicador de agrupación
     * @param string $FromDateLocal fecha de inicio en formato local
     * @param string $ToDateLocal fecha de fin en formato local
     * @param string $ConcesionarioId ID del concesionario opcional
     * @param string $ConcesionarioId2 ID del segundo concesionario opcional
     * @param string $ConcesionarioId3 ID del tercer concesionario opcional
     * @param string $PaisId ID del país opcional
     * @param string $Mandante mandante opcional
     * @param string $PuntoVentaId ID del punto de venta opcional
     *
     * @return string JSON con el conteo y los datos de la consulta
     */
    public function queryFlujoCajaResumido2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId = "", $ConcesionarioId2 = "", $ConcesionarioId3 = "", $PaisId = "", $Mandante = "", $PuntoVentaId = "")
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


        $whereReportConcesionario = "";
        $groupAgent = "y.usupadre_id = uu.usuario_id";

        if ($ConcesionarioId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre_id = '" . $ConcesionarioId . "' ";
        }

        if ($ConcesionarioId2 != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre2_id = '" . $ConcesionarioId2 . "' ";
            $groupAgent = "y.usupadre2_id = uu.usuario_id";

        }

        if ($ConcesionarioId3 != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre3_id = '" . $ConcesionarioId3 . "' ";
        }

        if ($PuntoVentaId != "") {
            if(strpos($PuntoVentaId,',') !== false){
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id IN (" . $PuntoVentaId . ") ";

            }else {
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id = '" . $PuntoVentaId . "' ";
            }
        }

        if ($PaisId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND d.pais_id = '" . $PaisId . "' ";
        }

        if ($Mandante != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND d.mandante IN (" . $Mandante . ") ";
        }

        $whereReportConcesionario = $whereReportConcesionario . "  ";

        //$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where ;


        $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);



        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        $sql = 'SELECT  ' . $select . '   FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0  AND concesionario.estado=\'A\') INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sql = "select /*+ MAX_EXECUTION_TIME(60000) */  y.punto_venta,
       y.fecha_crea,
       y.moneda,
       uu.nombre agente,
       y.pais_nom,
       y.pais_iso,
       y.usupadre_id,
       y.usupadre2_id,
       sum(y.cant_tickets)           cant_tickets,
       sum(y.valor_entrada_efectivo) valor_entrada_efectivo,
       sum(y.valor_entrada_bono)     valor_entrada_bono,
       sum(y.valor_entrada_recarga)  valor_entrada_recarga,
       sum(y.valor_entrada_recarga_anuladas)  valor_entrada_recarga_anuladas,
       sum(y.valor_entrada_recarga_agentes)  valor_entrada_recarga_agentes,
       sum(y.valor_entrada_traslado) valor_entrada_traslado,
       sum(y.valor_salida_traslado)  valor_salida_traslado,
       sum(y.valor_salida_notaret)   valor_salida_notaret,
       sum(y.valor_entrada)          valor_entrada,
       sum(y.valor_salida)           valor_salida,
       sum(y.valor_salida_efectivo)  valor_salida_efectivo,
       sum(y.premios_pend)           premios_pend
from (select *

      from (select pv.descripcion                                                                 punto_venta,
                   x.fecha_crea,
                   d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
                   
                   0                                                                              cant_tickets,
                   sum(case
                         when x.tipomov_id = 'S' then 0
                         when x.tipomov_id = 'E' and x.ticket_id <> '' and not x.ticket_id is null then x.valor
                         else x.valor_forma1 end - case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end -
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_efectivo,
                   sum(case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end)               valor_entrada_bono,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' and x.cupolog_id = '0'  and x.recarga_id != '0'
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga,
                   sum(case
                         when  (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' and x.cupolog_id = '0'  and x.recarga_id != '0'   and x.recarga_id != '' 
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga_anuladas, 
                   sum(case
                           when x.traslado = 'N' and ( x.ticket_id = '' or x.ticket_id is null) AND x.cupolog_id != '0' and ((x.recarga_id = '0' or x.recarga_id is null) AND ( x.ticket_id is null OR x.ticket_id ='')) and x.tipomov_id = 'E'
                               then x.valor
                           else 0 end)                                                                valor_entrada_recarga_agentes,
                   
                   sum(
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_traslado,
                   sum(case when x.tipomov_id = 'S' and x.traslado = 'S' then x.valor else 0 end) valor_salida_traslado,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' AND cuenta_id != ''
                           then x.valor
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' AND cuenta_id != ''
                           then -x.valor
                         else 0 end)                                                              valor_salida_notaret,
                   sum(case when x.tipomov_id = 'E' then x.valor else 0 end)                      valor_entrada,
                   sum(case when x.tipomov_id = 'S' then x.valor else 0 end)                      valor_salida,
                   sum(case
                         when x.tipomov_id = 'S' and x.traslado <> 'S' and ((x.ticket_id <> '' and not x.ticket_id is null) OR ( x.recarga_id <> '' and not x.recarga_id is null) )
                           then x.valor
                         else 0 end)                                                              valor_salida_efectivo,
                   0                                                                              premios_pend
            from flujo_caja x
                   inner join usuario d on ( x.usucrea_id = d.usuario_id)
                   left outer join punto_venta e on ( d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on ( e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   left outer join concesionario c on ( d.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 and c.estado ='A')
            where 1 = 1
              and date_add(timestamp(concat(x.fecha_crea, ' ', x.hora_crea)), interval 0 hour) >=
                  timestamp('" . $FromDateLocal . "')
              and date_add(timestamp(concat(x.fecha_crea, ' ', x.hora_crea)), interval 0 hour) <=
                  timestamp('" . $ToDateLocal . "')
              " . $whereReportConcesionario . "
            group by pv.descripcion,x.fecha_crea,d.moneda) z
      union
      select pv.descripcion    punto_venta,
             '" . $ToDateLocal . "'      fecha_crea,
             d.moneda,
             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             0                 cant_tickets,
             0                 valor_entrada_efectivo,
             0                 valor_entrada_bono,
             0                 valor_entrada_recarga,
             0                 valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                 valor_entrada_traslado,
             0                 valor_salida_traslado,
             0                 valor_salida_notaret,
             0                 valor_entrada,
             0                 valor_salida,
             0                 valor_salida_efectivo,
             sum(z.vlr_premio) premios_pend
      from it_ticket_enc z
             inner join usuario d on ( z.usuario_id = d.usuario_id)
             left outer join punto_venta e on (d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on ( e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             left outer join concesionario c on ( d.usuario_id = c.usuhijo_id and c.prodinterno_id =0 and c.estado ='A')
      where z.fecha_crea <= '" . $ToDateLocal . "'
        and z.premiado = 'S'
        and z.premio_pagado = 'N'
        and date(z.fecha_maxpago) >= date(now())
        " . $whereReportConcesionario . "

      group by pv.descripcion,'" . $ToDateLocal . "',d.moneda
      union
      select pv.descripcion     punto_venta,
             z.fecha_crea,
             d.moneda,             f.pais_nom,f.iso pais_iso,c.usupadre_id,c.usupadre2_id,
             count(z.ticket_id) cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                  valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend
      from it_ticket_enc z
             inner join usuario d on ( z.usuario_id = d.usuario_id)
             inner join punto_venta e on ( d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
             left outer join concesionario c on ( d.usuario_id = c.usuhijo_id and c.prodinterno_id =0 and c.estado ='A')

      where 1 = 1
        and date_add(timestamp(concat(z.fecha_crea, ' ', z.hora_crea)), interval 0 hour) >=
            timestamp('" . $FromDateLocal . "')
        and date_add(timestamp(concat(z.fecha_crea, ' ', z.hora_crea)), interval 0 hour) <=     
            timestamp('" . $ToDateLocal . "')
        " . $whereReportConcesionario . "

      group by pv.descripcion,z.fecha_crea,d.moneda) y
      
                         left outer join usuario uu on ( " . $groupAgent . ")

group by y.punto_venta,y.fecha_crea,y.moneda
order by y.punto_venta,y.fecha_crea
";
        $sqlQuery = new SqlQuery($sql);


        $result = $Helpers->process_data($this->execute2($sqlQuery));


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal FromDateLocal
     * @param String $ToDateLocal ToDateLocal
     * @param String $PuntoVentaId PuntoVentaId
     * @param String $CajeroId CajeroId
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryFlujoCajaResumidoConCajero2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId = "", $CajeroId = "")
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

        $whereReport = "";

        if ($PuntoVentaId != "") {
            if(strpos($PuntoVentaId,',') !== false){
                $whereReport = $whereReport . " AND y.punto_ventaid IN (" . $PuntoVentaId . ") ";

            }else {

                $whereReport = $whereReport . " AND y.punto_ventaid = '" . $PuntoVentaId . "'";
            }
        }

        if ($CajeroId != "") {

            $whereReport = $whereReport . " AND y.usuario_id = '" . $CajeroId . "'";
        }

        //$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where ;


        $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        $sql = 'SELECT  ' . $select . '   FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0  AND concesionario.estado=\'A\') INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sql = "select /*+ MAX_EXECUTION_TIME(60000) */  y.punto_venta,
        y.punto_ventaid,
        y.usuario_id,
        y.login,
       y.fecha_crea,
       y.moneda,
       sum(y.cant_tickets)           cant_tickets,
       sum(y.valor_entrada_efectivo) valor_entrada_efectivo,
       sum(y.valor_entrada_bono)     valor_entrada_bono,
       sum(y.valor_entrada_recarga)  valor_entrada_recarga,
       sum(y.valor_entrada_recarga_anuladas)  valor_entrada_recarga_anuladas,
       sum(y.valor_entrada_recarga_agentes)  valor_entrada_recarga_agentes,
       sum(y.valor_entrada_traslado) valor_entrada_traslado,
       sum(y.valor_salida_traslado)  valor_salida_traslado,
       sum(y.valor_salida_notaret)   valor_salida_notaret,
       sum(y.valor_entrada)          valor_entrada,
       sum(y.valor_salida)           valor_salida,
       sum(y.valor_salida_efectivo)  valor_salida_efectivo,
       sum(y.premios_pend)           premios_pend
from (select *
      from (select pv.descripcion                                                                 punto_venta,
                  pv.usuario_id                                                                 punto_ventaid,
                  d.usuario_id,
                  d.login,
                   x.fecha_crea,
                   d.moneda,
                   0                                                                              cant_tickets,
                   sum(case
                         when x.tipomov_id = 'S' then 0
                         when x.tipomov_id = 'E' and x.ticket_id <> '' and not x.ticket_id is null then x.valor
                         else x.valor_forma1 end - case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end -
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_efectivo,
                   sum(case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end)               valor_entrada_bono,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' and x.cupolog_id = '0'  and x.recarga_id != '0'
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga,
                   sum(case
                         when  (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' and x.cupolog_id = '0'  and x.recarga_id != '0'   and x.recarga_id != '' 
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga_anuladas, 
                   sum(case
                           when x.traslado = 'N' and ( x.ticket_id = '' or x.ticket_id is null) AND x.cupolog_id != '0' and ((x.recarga_id = '0' or x.recarga_id is null) AND ( x.ticket_id is null OR x.ticket_id ='')) and  x.tipomov_id = 'E'
                               then x.valor
                           else 0 end)                                                                valor_entrada_recarga_agentes,
                   sum(
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_traslado,
                   sum(case when x.tipomov_id = 'S' and x.traslado = 'S' then x.valor else 0 end) valor_salida_traslado,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' AND cuenta_id != ''
                           then x.valor
                       when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' AND cuenta_id != ''
                           then -x.valor
                         else 0 end)                                                              valor_salida_notaret,
                   sum(case when x.tipomov_id = 'E' then x.valor else 0 end)                      valor_entrada,
                   sum(case when x.tipomov_id = 'S' then x.valor else 0 end)                      valor_salida,
                   sum(case
                         when x.tipomov_id = 'S' and x.traslado <> 'S' and ((x.ticket_id <> '' and not x.ticket_id is null) OR ( x.recarga_id <> '' and not x.recarga_id is null) )
                           then x.valor
                         else 0 end)                                                              valor_salida_efectivo,
                   0                                                                              premios_pend
            from flujo_caja x
                   inner join usuario d on (x.mandante = d.mandante and x.usucrea_id = d.usuario_id)
                   left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
            where 1 = 1
              and date_add(timestamp(concat(x.fecha_crea, ' ', x.hora_crea)), interval 0 hour) >=
                  timestamp('" . $FromDateLocal . "')
              and date_add(timestamp(concat(x.fecha_crea, ' ', x.hora_crea)), interval 0 hour) <=
                  timestamp('" . $ToDateLocal . "')
            group by d.usuario_id,x.fecha_crea,d.moneda) z
      union
      select pv.descripcion    punto_venta,
             pv.usuario_id     punto_ventaid,
            d.usuario_id,
            d.login,
             '" . $ToDateLocal . "'      fecha_crea,
             d.moneda,
             0                 cant_tickets,
             0                 valor_entrada_efectivo,
             0                 valor_entrada_bono,
             0                 valor_entrada_recarga,
             0                 valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                 valor_entrada_traslado,
             0                 valor_salida_traslado,
             0                 valor_salida_notaret,
             0                 valor_entrada,
             0                 valor_salida,
             0                 valor_salida_efectivo,
             sum(z.vlr_premio) premios_pend
      from it_ticket_enc z
             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
      where z.fecha_crea <= '" . $ToDateLocal . "'
      
        and z.premiado = 'S'
        and z.premio_pagado = 'N'
        and date(z.fecha_maxpago) >= date(now())
      group by d.usuario_id,'" . $ToDateLocal . "',d.moneda
      union
      select pv.descripcion     punto_venta,
                   pv.usuario_id     punto_ventaid,
            d.usuario_id,
            d.login,
             z.fecha_crea,
             d.moneda,
             count(z.ticket_id) cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                  valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend
      from it_ticket_enc z
             inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
             inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
             left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
             left outer join pais f on (d.pais_id = f.pais_id)
      where 1 = 1
        and date_add(timestamp(concat(z.fecha_crea, ' ', z.hora_crea)), interval 0 hour) >=
            timestamp('" . $FromDateLocal . "')
        and date_add(timestamp(concat(z.fecha_crea, ' ', z.hora_crea)), interval 0 hour) <=
            timestamp('" . $ToDateLocal . "')
        
      group by d.usuario_id,z.fecha_crea,d.moneda) y
            WHERE 1=1  " . $whereReport . "

group by y.usuario_id,y.fecha_crea,y.moneda
order by y.usuario_id,y.fecha_crea
";


        $sqlQuery = new SqlQuery($sql);


        $result = $Helpers->process_data($this->execute2($sqlQuery));



        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal FromDateLocal
     * @param String $ToDateLocal ToDateLocal
     * @param String $ConcesionarioId ConcesionarioId
     * @param String $ConcesionarioId2 ConcesionarioId2
     * @param String $ConcesionarioId3 ConcesionarioId3
     * @param String $PaisId PaisId
     * @param String $Mandante Mandante
     * @param String $PuntoVentaId PuntoVentaId
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryFlujoCaja($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId = "", $ConcesionarioId2 = "", $ConcesionarioId3 = "", $PaisId = "", $Mandante = "", $PuntoVentaId = "", $UsuarioId = "")
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


        $whereReportConcesionario = "";
        $groupAgent = "c.usupadre_id = uu.usuario_id";

        if ($ConcesionarioId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre_id = '" . $ConcesionarioId . "' ";
        }

        if ($ConcesionarioId2 != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre2_id = '" . $ConcesionarioId2 . "' ";
            $groupAgent = "c.usupadre2_id = uu.usuario_id";

        }

        if ($ConcesionarioId3 != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND c.usupadre3_id = '" . $ConcesionarioId3 . "' ";
        }

        if ($PuntoVentaId != "") {
            if(strpos($PuntoVentaId,',') !== false){
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id IN (" . $PuntoVentaId . ") ";

            }else {
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id = '" . $PuntoVentaId . "' ";
            }
        }

        if ($UsuarioId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND d.usuario_id = '" . $UsuarioId . "' ";
        }

        if ($PaisId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND d.pais_id = '" . $PaisId . "' ";
        }

        if ($Mandante != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND d.mandante IN (" . $Mandante . ") ";
        }

        $whereReportConcesionario = $whereReportConcesionario . "  ";

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        $SQL = "select /*+ MAX_EXECUTION_TIME(60000) */  count(*) from flujo_caja x ";
        $SQL = $SQL . "inner join usuario d on (x.mandante=d.mandante and x.usucrea_id=d.usuario_id) ";
        $SQL = $SQL . "left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   left outer join concesionario c on ( d.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 and c.estado ='A') 
                   left outer join usuario uu on ( " . $groupAgent . ")

                   ";

        $SQL = $SQL . $where . " ";

        if(strpos($FromDateLocal,' ') !== false){

            $SQL = $SQL . " and ((CONCAT(x.fecha_crea,' ',x.hora_crea) )) >=
            ('" . $FromDateLocal . "')
            and (((CONCAT(x.fecha_crea,' ',x.hora_crea)))) <=
            ('" . $ToDateLocal . "')
        " . $whereReportConcesionario;

        }else{

            $SQL = $SQL . " and (((x.fecha_crea))) >=
            ('" . $FromDateLocal . "')
            and (((x.fecha_crea))) <=
            ('" . $ToDateLocal . "')
        " . $whereReportConcesionario;
        }

        $SQL = $SQL . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"]){
            print_r($SQL);
            exit();
        }

        $sqlQuery = new SqlQuery($SQL);
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc'");
        $this->execute2($sqlQuery2);

        $count = $this->execute2($sqlQuery);


        $strContado = "Contado";
        $strPagoPremio = "Pago Premio";
        $strPagoNotaCobro = 'Pago Nota Cobro';
        $strRecargaCredito = "Recarga Credito";
        $strTraslado = "Traslado";
        $strDevolucionRecarga = "Devolucion Recarga";
        $strDevolucionApuesta = "Devolucion Apuesta";
        $strBono = "Bono";

        if (strtolower($_SESSION["idioma"]) == 'en') {

            $strContado = "Cash";
            $strPagoPremio = "Pay Award";
            $strPagoNotaCobro = 'Pay Withdraw';
            $strRecargaCredito = "Deposit";
            $strTraslado = "Transfer";
            $strDevolucionRecarga = "Refund Deposit";
            $strDevolucionApuesta = "Refund Bet";
            $strBono = "Bonus";

        }


        $SQL = "select /*+ MAX_EXECUTION_TIME(60000) */   x.flujocaja_id, x.cupolog_id,case when not x.recarga_id is null AND x.recarga_id != '0' then x.recarga_id when not x.cuenta_id is null AND x.cuenta_id != '0' then x.cuenta_id when x.ticket_id='0' then '-' else x.ticket_id end ticket_id,";
        $SQL = $SQL . "x.fecha_crea,x.hora_crea, x.devolucion,d.login puntoventa,";
        $SQL = $SQL . "case when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='S' then '" . $strPagoNotaCobro . "' when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='E' then '" . $strRecargaCredito . "' when x.traslado='S' then '" . $strTraslado . "' else case when x.tipomov_id='S' and x.devolucion='S' then CASE WHEN (x.ticket_id='' or x.ticket_id is null) THEN '" . $strDevolucionRecarga . "' else '" . $strDevolucionApuesta . "' END  when x.tipomov_id='S' then '" . $strPagoPremio . "' else '" . $strContado . "' end end forma_pago1,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma1 end valor_forma1,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 when x.tipomov_id='E' and x.ticket_id<>'' and not x.ticket_id is null then x.valor else x.valor_forma1 end - case when x.tipomov_id='S' then 0 else x.valor_forma2 end - case when x.tipomov_id='E' and x.traslado='S' then x.valor else 0 end valor_entrada_efectivo,";
        $SQL = $SQL . "case when x.formapago2_id=0 then '' else '" . $strBono . "' end forma_pago2,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma2 end valor_forma2,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma2 end valor_entrada_bono,";
        $SQL = $SQL . "case when x.traslado='N' and not x.recarga_id is null and x.cupolog_id = '0'  and x.recarga_id != '0'  and x.tipomov_id='E' then x.valor else 0  end valor_entrada_recarga,";
        $SQL = $SQL . "case when not x.recarga_id is null and x.cupolog_id = '0'  and x.tipomov_id='S' then x.valor else 0  end valor_entrada_recarga_anuladas,";
        $SQL = $SQL . "case when x.traslado='N' and not x.cupolog_id is null and ((x.recarga_id = '0' or x.recarga_id is null) AND ( x.ticket_id is null OR x.ticket_id ='')) and x.tipomov_id='E' then x.valor else 0 end valor_entrada_recarga_agentes,";
        $SQL = $SQL . "case when x.tipomov_id='E' and x.traslado='S' then x.valor else 0 end valor_entrada_traslado,";
        $SQL = $SQL . "case when x.tipomov_id='S' and x.traslado='S' then x.valor else 0 end valor_salida_traslado,";
        $SQL = $SQL . "case when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='S' AND cuenta_id != ''  then x.valor  when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='E' AND cuenta_id != ''  then -x.valor else 0 end valor_salida_notaret,";
        $SQL = $SQL . "case when x.tipomov_id='E' then x.valor else 0 end valor_entrada,";
        $SQL = $SQL . "case when x.tipomov_id='S' then x.valor else 0 end valor_salida,case when x.tipomov_id='S' and x.traslado<>'S' and ((x.ticket_id <> '' and not x.ticket_id is null) OR ( x.recarga_id <> '' and not x.recarga_id is null) ) then x.valor else 0 end valor_salida_efectivo,d.moneda ";
        $SQL = $SQL . "from flujo_caja x ";
        $SQL = $SQL . "inner join usuario d on (x.mandante=d.mandante and x.usucrea_id=d.usuario_id) ";
        $SQL = $SQL . "left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   left outer join concesionario c on ( d.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 and c.estado ='A') 
                   left outer join usuario uu on ( " . $groupAgent . ")

                   ";

        $SQL = $SQL . $where . " ";

           if(strpos($FromDateLocal,' ') !== false){

            $SQL = $SQL . " and ((CONCAT(x.fecha_crea,' ',x.hora_crea) )) >=
            ('" . $FromDateLocal . "')
            and (((CONCAT(x.fecha_crea,' ',x.hora_crea)))) <=
            ('" . $ToDateLocal . "')
        " . $whereReportConcesionario;

        }else {
               $SQL = $SQL . " and (((x.fecha_crea))) >=
            ('" . $FromDateLocal . "')
            and (((x.fecha_crea))) <=
            ('" . $ToDateLocal . "')
        " . $whereReportConcesionario;
           }

        $SQL = $SQL . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($SQL);


        if($_REQUEST['test2']==1){
            print_r($SQL);
            exit();
        }

        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc'");
        $this->execute2($sqlQuery2);
        $result = $Helpers->process_data($this->execute2($sqlQuery));

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    public function queryFlujoCajaConCajero($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId = "", $CajeroId = "")
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


        $whereReportConcesionario = "";
        $groupAgent = "c.usupadre_id = uu.usuario_id";

        if ($PuntoVentaId != "") {
            if(strpos($PuntoVentaId,',') !== false){
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id IN (" . $PuntoVentaId . ") ";

            }else {
                $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id = '" . $PuntoVentaId . "' ";
            }
        }

        if ($CajeroId != "") {

            $whereReportConcesionario = $whereReportConcesionario . " AND d.usuario_id = '" . $CajeroId . "'";
        }

        $whereReportConcesionario = $whereReportConcesionario . "  ";


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }

        $SQL = "select /*+ MAX_EXECUTION_TIME(60000) */  count(*) from flujo_caja x ";
        $SQL = $SQL . "inner join usuario d on (x.mandante=d.mandante and x.usucrea_id=d.usuario_id)
        left outer join transjuego_info tji on (tji.tipo = 'USUARIORELACIONADO' AND x.ticket_id = CONCAT('CASI_', tji.transjuego_id))
        left outer join it_ticket_enc_info1 itei1 on (itei1.tipo = 'USUARIORELACIONADO' AND itei1.ticket_id = x.ticket_id)
        left outer join usuario_mandante bu /*Usuario del comprador (U.Online)*/ on (COALESCE(tji.valor, itei1.valor) = bu.usuario_mandante)";

        $SQL = $SQL . "left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   left outer join concesionario c on ( d.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 and c.estado ='A') 
                   left outer join usuario uu on ( " . $groupAgent . ")

                   ";

        $SQL = $SQL . $where . " ";


        $HourFromDateLocal = "";

        if(strpos($FromDateLocal," ") !== false){
            $HourFromDateLocal = explode(" ",$FromDateLocal)[1];
            $FromDateLocal = explode(" ",$FromDateLocal)[0];
        }

        $HourToDateLocal = "";

        if(strpos($ToDateLocal," ") !== false){
            $HourToDateLocal = explode(" ",$ToDateLocal)[1];
            $ToDateLocal = explode(" ",$ToDateLocal)[0];
        }


        if($HourFromDateLocal != "" && $HourToDateLocal != ""){
            $SQL = $SQL . " and (((((x.fecha_crea ))) =
            ('" . $FromDateLocal . "') and x.hora_crea >= '".$HourFromDateLocal."')
            OR ((((x.fecha_crea))) <=
            ('" . $ToDateLocal . "')  and x.hora_crea = '".$HourToDateLocal."' ))
        " . $whereReportConcesionario;

        }else{
            $SQL = $SQL . " and (((x.fecha_crea))) >=
            ('" . $FromDateLocal . "')
            and (((x.fecha_crea))) <=
            ('" . $ToDateLocal . "')
        " . $whereReportConcesionario;

        }

        //$SQL = $SQL . "group by " . (($grouping == 1) ? "d.usuario_id,x.fecha_crea,d.moneda" : "x.fecha_crea,d.moneda");

        $SQL = $SQL . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($SQL);
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc'");
        $this->execute2($sqlQuery2);

        $count = $this->execute2($sqlQuery);


        $strContado = "Contado";
        $strPagoPremio = "Pago Premio";
        $strPagoNotaCobro = 'Pago Nota Cobro';
        $strRecargaCredito = "Recarga Credito";
        $strTraslado = "Traslado";
        $strDevolucionRecarga = "Devolucion Recarga";
        $strDevolucionApuesta = "Devolucion Apuesta";
        $strBono = "Bono";

        if (strtolower($_SESSION["idioma"]) == 'en') {

            $strContado = "Cash";
            $strPagoPremio = "Pay Award";
            $strPagoNotaCobro = 'Pay Withdraw';
            $strRecargaCredito = "Deposit";
            $strTraslado = "Transfer";
            $strDevolucionRecarga = "Refund Deposit";
            $strDevolucionApuesta = "Refund Bet";
            $strBono = "Bonus";

        }


        $SQL = "select /*+ MAX_EXECUTION_TIME(60000) */  x.flujocaja_id, x.cupolog_id, x.devolucion, d.usuario_id pventa_id, case when not x.recarga_id is null AND x.recarga_id != '0'  then x.recarga_id when not x.cuenta_id is null AND x.cuenta_id != '0'  then x.cuenta_id when x.ticket_id='0' then '-' else x.ticket_id end ticket_id,";
        $SQL = $SQL . "x.fecha_crea,x.hora_crea,d.login puntoventa,";
        $SQL = $SQL . "case when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='S' then '" . $strPagoNotaCobro . "' when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='E' then '" . $strRecargaCredito . "' when x.traslado='S' then '" . $strTraslado . "' else case when x.tipomov_id='S' and x.devolucion='S' then CASE WHEN (x.ticket_id='' or x.ticket_id is null) THEN '" . $strDevolucionRecarga . "' else '" . $strDevolucionApuesta . "' END  when x.tipomov_id='S' then '" . $strPagoPremio . "' else '" . $strContado . "' end end forma_pago1,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma1 end valor_forma1,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 when x.tipomov_id='E' and x.ticket_id<>'' and not x.ticket_id is null then x.valor else x.valor_forma1 end - case when x.tipomov_id='S' then 0 else x.valor_forma2 end - case when x.tipomov_id='E' and x.traslado='S' then x.valor else 0 end valor_entrada_efectivo,";
        $SQL = $SQL . "case when x.formapago2_id=0 then '' else '" . $strBono . "' end forma_pago2,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma2 end valor_forma2,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma2 end valor_entrada_bono,";
        $SQL = $SQL . "case when x.traslado='N' and not x.recarga_id is null and x.cupolog_id = '0'  and x.recarga_id != '0' and x.tipomov_id='E' then x.valor else 0 end valor_entrada_recarga,";
        $SQL = $SQL . "case when not x.recarga_id is null and x.cupolog_id = '0'  and x.tipomov_id='S' then x.valor else 0  end valor_entrada_recarga_anuladas,";
        $SQL = $SQL . "case when x.traslado='N' and not x.cupolog_id is null and ((x.recarga_id = '0' or x.recarga_id is null) AND ( x.ticket_id is null OR x.ticket_id ='')) and  x.tipomov_id='E' then x.valor else 0 end valor_entrada_recarga_agentes,";
        $SQL = $SQL . "case when x.tipomov_id='E' and x.traslado='S' then x.valor else 0 end valor_entrada_traslado,";
        $SQL = $SQL . "case when x.tipomov_id='S' and x.traslado='S' then x.valor else 0 end valor_salida_traslado,";
        $SQL = $SQL . "case when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='S' AND cuenta_id != ''  then x.valor when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='E' AND cuenta_id != ''  then -x.valor else 0 end valor_salida_notaret,";
        $SQL = $SQL . "case when x.tipomov_id='E' then x.valor else 0 end valor_entrada,";
        $SQL = $SQL . "case when x.tipomov_id='S' then x.valor else 0 end valor_salida,case when x.tipomov_id='S' and x.traslado<>'S' and ((x.ticket_id <> '' and not x.ticket_id is null) OR ( x.recarga_id <> '' and not x.recarga_id is null) ) then x.valor else 0 end valor_salida_efectivo,d.moneda,
        COALESCE(tji.valor, 0)                                                       clienteid_apuesta_casino,
        COALESCE(itei1.valor, 0)                                                     clienteid_apuesta_deportiva,
        bu.usumandante_id                                                            cliente_id_casino,
        bu.usuario_mandante                                                          cliente_id_plataforma,
        tji.identificador                                                            casino_ronda,
        tji.transjuego_id                                                            casino_ticket,
        ta.t_value                                                                   json_query";
        $SQL = $SQL . " from flujo_caja x ";
        $SQL = $SQL . "inner join usuario d on (x.mandante=d.mandante and x.usucrea_id=d.usuario_id) 
        left outer join transjuego_info tji on (tji.tipo = 'USUARIORELACIONADO' AND x.ticket_id = CONCAT('CASI_', tji.transjuego_id))
        left outer join transaccion_api ta on (tji.transaccion_id = ta.transaccion_id and ta.tipo = 'DEBIT')
        left outer join it_ticket_enc_info1 itei1 on (itei1.tipo = 'USUARIORELACIONADO' AND itei1.ticket_id = x.ticket_id)
        left outer join usuario_mandante bu /*Usuario del comprador (U.Online)*/ on (COALESCE(tji.valor, itei1.valor) = bu.usuario_mandante)";
        $SQL = $SQL . "left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   left outer join concesionario c on ( d.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 and c.estado ='A') 
                   left outer join usuario uu on ( " . $groupAgent . ")

                   ";

        $SQL = $SQL . $where . " ";


        if($HourFromDateLocal != "" && $HourToDateLocal != ""){
            $SQL = $SQL . " and (((((x.fecha_crea ))) =
            ('" . $FromDateLocal . "') and x.hora_crea >= '".$HourFromDateLocal."')
            OR ((((x.fecha_crea))) =
            ('" . $ToDateLocal . "')  and x.hora_crea <= '".$HourToDateLocal."' ))
        " . $whereReportConcesionario;

        }else{

            $SQL = $SQL . " and (((x.fecha_crea))) >=
            ('" . $FromDateLocal . "')
            and (((x.fecha_crea))) <=
            ('" . $ToDateLocal . "')
        " . $whereReportConcesionario;



        }

        // $SQL = $SQL . "group by " . (($grouping == 1) ? "d.usuario_id,x.fecha_crea,d.moneda" : "x.fecha_crea,d.moneda");

        $SQL = $SQL . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($SQL);


        if($_ENV["debugFixed2"] == '1'){
            print_r($SQL);
        }
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc'");
        $this->execute2($sqlQuery2);
        $result = $Helpers->process_data($this->execute2($sqlQuery));

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de flujo\_caja con filtros y condiciones específicas
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta en formato JSON
     * @param boolean $searchOn utilizar los filtros o no
     * @param int $grouping indicador de agrupación
     * @param string $FromDateLocal fecha de inicio en formato local
     * @param string $ToDateLocal fecha de fin en formato local
     * @param string $PuntoVentaId ID del punto de venta opcional
     * @param string $CajeroId ID del cajero opcional
     *
     * @return string JSON con el conteo y los datos de la consulta
     */
    public function queryFlujoCajaConCajero2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId = "", $CajeroId = "",$mandante="")
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $whereReportConcesionario = "";
        $groupAgent = "c.usupadre_id = uu.usuario_id";

        if ($PuntoVentaId != "") {
            $whereReportConcesionario = $whereReportConcesionario . " AND d.puntoventa_id = '" . $PuntoVentaId . "' ";
        }

        if ($CajeroId != "") {

            $whereReportConcesionario = $whereReportConcesionario . " AND d.usuario_id = '" . $CajeroId . "'";
        }

        $whereReportConcesionario = $whereReportConcesionario . "  ";


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        $SQL = "select /*+ MAX_EXECUTION_TIME(60000) */  count(*) from flujo_caja x ";
        $SQL = $SQL . "inner join usuario d on (x.mandante=d.mandante and x.usucrea_id=d.usuario_id) ";
        $SQL = $SQL . "left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   left outer join concesionario c on ( d.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 and c.estado ='A') 
                   left outer join usuario uu on ( " . $groupAgent . ")

                   ";

        $SQL = $SQL . $where . " ";

        $SQL = $SQL . " and (((x.fecha_crea_time))) >=
            ('" . $FromDateLocal . "')
            and (((x.fecha_crea_time))) <=
            ('" . $ToDateLocal . "')
        " . $whereReportConcesionario;
        //$SQL = $SQL . "group by " . (($grouping == 1) ? "d.usuario_id,x.fecha_crea,d.moneda" : "x.fecha_crea,d.moneda");

        $SQL = $SQL . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($SQL);

        $count = $this->execute2($sqlQuery);


        $strContado = "Contado";
        $strPagoPremio = "Pago Premio";
        $strPagoNotaCobro = 'Pago Nota Cobro';
        $strRecargaCredito = "Recarga Credito";
        $strTraslado = "Traslado";
        $strDevolucionRecarga = "Devolucion Recarga";
        $strDevolucionApuesta = "Devolucion Apuesta";
        $strBono = "Bono";

        if (strtolower($_SESSION["idioma"]) == 'en') {

            $strContado = "Cash";
            $strPagoPremio = "Pay Award";
            $strPagoNotaCobro = 'Pay Withdraw';
            $strRecargaCredito = "Deposit";
            $strTraslado = "Transfer";
            $strDevolucionRecarga = "Refund Deposit";
            $strDevolucionApuesta = "Refund Bet";
            $strBono = "Bonus";

        }


        $SQL = "select /*+ MAX_EXECUTION_TIME(60000) */  x.fecha_crea_time,x.flujocaja_id, x.cupolog_id, x.recarga_id,  x.cuenta_id , x.ticket_id,";
        $SQL = $SQL . "x.fecha_crea,x.hora_crea,d.login puntoventa,x.usucrea_id,";
        $SQL = $SQL . "case when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='S' then '" . $strPagoNotaCobro . "' when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='E' then '" . $strRecargaCredito . "' when x.traslado='S' then '" . $strTraslado . "' else case when x.tipomov_id='S' and x.devolucion='S' then CASE WHEN (x.ticket_id='' or x.ticket_id is null) THEN '" . $strDevolucionRecarga . "' else '" . $strDevolucionApuesta . "' END  when x.tipomov_id='S' then '" . $strPagoPremio . "' else '" . $strContado . "' end end forma_pago1,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma1 end valor_forma1,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 when x.tipomov_id='E' and x.ticket_id<>'' and not x.ticket_id is null then x.valor else x.valor_forma1 end - case when x.tipomov_id='S' then 0 else x.valor_forma2 end - case when x.tipomov_id='E' and x.traslado='S' then x.valor else 0 end valor_entrada_efectivo,";
        $SQL = $SQL . "case when x.formapago2_id=0 then '' else '" . $strBono . "' end forma_pago2,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma2 end valor_forma2,";
        $SQL = $SQL . "case when x.tipomov_id='S' then 0 else x.valor_forma2 end valor_entrada_bono,";
        $SQL = $SQL . "case when x.traslado='N' and not x.recarga_id is null and x.cupolog_id = '0' and x.tipomov_id='E' then x.valor else 0 end valor_entrada_recarga,";
        $SQL = $SQL . "case when not x.recarga_id is null and x.cupolog_id = '0'  and x.tipomov_id='S' then x.valor else 0  end valor_entrada_recarga_anuladas,";
        $SQL = $SQL . "case when x.traslado='N' and not x.cupolog_id is null and ((x.recarga_id = '0' or x.recarga_id is null) AND ( x.ticket_id is null OR x.ticket_id ='')) and  x.tipomov_id='E' then x.valor else 0 end valor_entrada_recarga_agentes,";
        $SQL = $SQL . "case when x.tipomov_id='E' and x.traslado='S' then x.valor else 0 end valor_entrada_traslado,";
        $SQL = $SQL . "case when x.tipomov_id='S' and x.traslado='S' then x.valor else 0 end valor_salida_traslado,";
        $SQL = $SQL . "case when x.traslado='N' and (x.ticket_id='' or x.ticket_id is null) and x.tipomov_id='S' AND cuenta_id != ''  then x.valor else 0 end valor_salida_notaret,";
        $SQL = $SQL . "case when x.tipomov_id='E' then x.valor else 0 end valor_entrada,";
        $SQL = $SQL . "case when x.tipomov_id='S' and x.traslado<>'S' and ((x.ticket_id <> '' and not x.ticket_id is null) OR ( x.recarga_id != '' and not x.recarga_id is null) ) then x.valor else 0 end recargas_anuladas,";
        $SQL = $SQL . "case when x.tipomov_id='S' and x.devolucion = '' then x.valor else 0 end valor_salida,case when x.tipomov_id='S' and x.traslado<>'S' and x.devolucion = '' and ((x.ticket_id <> '' and not x.ticket_id is null) OR ( x.recarga_id <> '' and not x.recarga_id is null) ) then x.valor else 0 end valor_salida_efectivo,d.moneda,x.cuenta_id,x.recarga_id ";
        $SQL = $SQL . "from flujo_caja x ";
        $SQL = $SQL . "inner join usuario d on (x.mandante=d.mandante and x.usucrea_id=d.usuario_id) ";
        $SQL = $SQL . "left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
                   left outer join punto_venta pv
                                   on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                   left outer join pais f on (d.pais_id = f.pais_id)
                   left outer join concesionario c on ( d.usuario_id = c.usuhijo_id  and c.prodinterno_id =0 and c.estado ='A') 
                   left outer join usuario uu on ( " . $groupAgent . ")

                   ";

        $SQL = $SQL . $where . " ";


            $SQL = $SQL . " and (((x.fecha_crea_time))) >=
            ('" . $FromDateLocal . "')
            and (((x.fecha_crea_time))) <=
            ('" . $ToDateLocal . "')
        " . $whereReportConcesionario;




        // $SQL = $SQL . "group by " . (($grouping == 1) ? "d.usuario_id,x.fecha_crea,d.moneda" : "x.fecha_crea,d.moneda");

        $SQL = $SQL . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($SQL);


        if($_ENV["debugFixed2"]){
            print_r($SQL);
        }
        $result = $Helpers->process_data($this->execute2($sqlQuery));


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal FromDateLocal
     * @param String $ToDateLocal ToDateLocal
     * @param String $TypeBet tipo de apuesta
     * @param String $PaisId id del pais
     * @param String $Mandante Mandante
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryInformeGerencial($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet = "", $PaisId = "", $Mandante = "", $TypeUser = "",$WalletId="")
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


        //$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where ;


        $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);


        $whereEstadoApuesta = "";
        $groupByFecha = "a.fecha_crea";
        $groupByFecha2 = "x.fecha_crea";
        $orderByFecha = "x.fecha_crea ASC";

        $selectFecha = "a.fecha_crea,a.fecha_crea";

        $whereFechasbono_log='';
        $whereFechasusuario='';
        $whereFechasusuario2='';

        if (date('Y-m-d', strtotime($FromDateLocal)) == date('Y-m-d', strtotime($ToDateLocal))) {
            $whereFechas = " and a.fecha_crea ='" . date('Y-m-d', strtotime($FromDateLocal)) . "' ";

            $whereFechasbono_log=" and pl.fecha_crea like '" . date('Y-m-d', strtotime($FromDateLocal)) . "%' ";
            $whereFechasusuario=" and usuario.fecha_crea like '" . date('Y-m-d', strtotime($FromDateLocal)) . "%' ";
            $whereFechasusuario2=" and usuario.fecha_primerdeposito like '" . date('Y-m-d', strtotime($FromDateLocal)) . "%' ";
        } else {
            $whereFechas = " and CONCAT(a.fecha_crea,' ',a.hora_crea) >='" . $FromDateLocal . "' and CONCAT(a.fecha_crea,' ',a.hora_crea)<='" . $ToDateLocal . "'";
            $whereFechas = " and a.fecha_crea >='" . date('Y-m-d', strtotime($FromDateLocal)) . "' and a.fecha_crea <='" . date('Y-m-d', strtotime($ToDateLocal)) . "'";

            $whereFechasbono_log=" and pl.fecha_crea >='" . date('Y-m-d', strtotime($FromDateLocal)) . "' and pl.fecha_crea <='" . date('Y-m-d', strtotime($ToDateLocal)) . "'";
            $whereFechasusuario=" and usuario.fecha_crea >='" . date('Y-m-d', strtotime($FromDateLocal)) . "' and usuario.fecha_crea <='" . date('Y-m-d', strtotime($ToDateLocal)) . "'";
            $whereFechasusuario2=" and usuario.fecha_primerdeposito >='" . date('Y-m-d', strtotime($FromDateLocal)) . "' and usuario.fecha_primerdeposito <='" . date('Y-m-d', strtotime($ToDateLocal)) . "'";

        }

        if ($TypeBet != "" && is_numeric($TypeBet)) {
            if ($TypeBet == 2) {
                $whereEstadoApuesta = $whereEstadoApuesta . " AND a.estado ='I' ";

                if (date('Y-m-d', strtotime($FromDateLocal)) == date('Y-m-d', strtotime($ToDateLocal))) {
                    $whereFechas = " and a.fecha_cierre ='" . date('Y-m-d', strtotime($FromDateLocal)) . "' ";

                } else {
                    $whereFechas = " and CONCAT(a.fecha_cierre,' ',a.hora_cierre) >='" . $FromDateLocal . "' and CONCAT(a.fecha_cierre,' ',a.hora_cierre) <='" . $ToDateLocal . "'";
                    $whereFechas = " and a.fecha_cierre >='" . date('Y-m-d', strtotime($FromDateLocal)) . "' and a.fecha_cierre <='" . date('Y-m-d', strtotime($ToDateLocal)) . "'";

                }

                $groupByFecha = "a.fecha_cierre";
                $groupByFecha2 = "x.fecha_cierre";

                $orderByFecha = "x.fecha_cierre ASC";


                $selectFecha = "a.fecha_cierre,a.fecha_cierre";

            } else {
                $whereEstadoApuesta = $whereEstadoApuesta . " ";

            }
        }

        $timeDimension='                                    inner join time_dimension4 force index (time_dimension4_datestr_hour2str_index)
                    on (time_dimension4.datestr = a.fecha_crea and time_dimension4.hour2str = a.hora_crea)
';
                    if ($TypeBet == 2) {

                        $timeDimension='                                    inner join time_dimension4 force index (time_dimension4_datestr_hour2str_index)
                    on (time_dimension4.datestr = a.fecha_cierre and time_dimension4.hour2str = a.hora_cierre)
';
                    }

        if ($TypeUser != "" && is_numeric($TypeUser)) {
            if ($TypeUser == 2) {
                $whereEstadoApuesta = $whereEstadoApuesta . " AND (up.perfil_id ='PUNTOVENTA' OR up.perfil_id ='CAJERO') ";

            } else {
                $whereEstadoApuesta = $whereEstadoApuesta . " AND up.perfil_id ='USUONLINE' ";

            }
        }

        if($WalletId != "" && is_numeric($WalletId)){
            $whereEstadoApuesta = $whereEstadoApuesta . " AND a.wallet ='".$WalletId."' ";

        }

        $wherePais = "";
        $wherePaisParaMandante = " WHERE 1=1 ";

        if ($PaisId != "") {
            $wherePais = $wherePais . " AND c.pais_id='" . $PaisId . "' ";
            $wherePaisParaMandante = $wherePaisParaMandante. " AND pais.pais_id='" . $PaisId . "' ";
        }


        if ($Mandante != "") {

            $wherePais = $wherePais . " AND b.mandante IN (" . $Mandante . ") ";
            $wherePaisParaMandante = $wherePaisParaMandante. " AND pais_mandante.mandante IN (" . $Mandante . ") ";

        }

        $wherePais = $wherePais . " AND c.pais_id !='1' ";


        if ($TypeUser == 2) {

            $pl2 = "LEFT OUTER JOIN  (SELECT '' moneda,'A' estado,0 valor,DATE_FORMAT('', '%Y-%d-%m') fecha_crea2) pl2 ON (pl2.fecha_crea2 = DATE_FORMAT(" . $groupByFecha2 . ", '%Y-%d-%m')) ";
            $pl3 = "LEFT OUTER JOIN  (SELECT '' moneda,'A' estado,0 registros,DATE_FORMAT('', '%Y-%d-%m') fecha_crea2) pl3 ON (pl3.fecha_crea2 = DATE_FORMAT(" . $groupByFecha2 . ", '%Y-%d-%m')) ";
            $pl4 = "LEFT OUTER JOIN  (SELECT '' moneda,'A' estado,0 primerdepositos,DATE_FORMAT('', '%Y-%d-%m') fecha_crea2) pl4 ON (pl4.fecha_crea2 = DATE_FORMAT(" . $groupByFecha2 . ", '%Y-%d-%m')) ";
        } else {
            $pl2 = "LEFT OUTER JOIN  (SELECT usuario.moneda,usuario.pais_id,usuario.mandante,pl.estado,sum(pl.valor) valor,DATE_FORMAT(pl.fecha_modif, '%Y-%d-%m') fecha_crea2 FROM usuario_bono pl INNER JOIN usuario ON (usuario.usuario_id=pl.usuario_id) where  pl.estado='R' GROUP BY usuario.moneda,fecha_crea2) pl2 ON (pl2.fecha_crea2 = DATE_FORMAT(" . $groupByFecha2 . ", '%Y-%d-%m')  AND x.pais_id=pl2.pais_id AND x.moneda=pl2.moneda  AND x.mandante = pl2.mandante) ";
            $pl2 = "LEFT OUTER JOIN  (SELECT usuario.moneda,usuario.pais_id,usuario.mandante,pl.estado,sum(pl.valor) valor,DATE_FORMAT(pl.fecha_crea, '%Y-%d-%m') fecha_crea2 FROM bono_log pl INNER JOIN usuario ON (usuario.usuario_id=pl.usuario_id) where  pl.estado='L' AND pl.tipo NOT IN ('FC','TC','TL','SC','SCV','SL','TV', 'DC', 'DL', 'DV', 'NC', 'NL', 'NV', 'JS', 'JL', 'JV', 'JD') GROUP BY usuario.mandante,usuario.pais_id,fecha_crea2) pl2 ON (pl2.fecha_crea2 = DATE_FORMAT(" . $groupByFecha2 . ", '%Y-%d-%m')  AND x.pais_id=pl2.pais_id AND x.moneda=pl2.moneda  AND x.mandante = pl2.mandante) ";
            $pl3 = "LEFT OUTER JOIN  (SELECT usuario.moneda,usuario.pais_id,usuario.mandante,DATE_FORMAT(usuario.fecha_crea, '%Y-%d-%m') fecha_crea2,COUNT(*) registros,pais.pais_nom FROM usuario INNER JOIN usuario_perfil ON (usuario.usuario_id=usuario_perfil.usuario_id AND usuario_perfil.perfil_id='USUONLINE') inner join pais on (pais.pais_id = usuario.pais_id) GROUP BY usuario.mandante,usuario.pais_id,fecha_crea2) pl3 ON (pl3.fecha_crea2 = DATE_FORMAT(" . $groupByFecha2 . ", '%Y-%d-%m')  AND x.pais_id=pl3.pais_id  AND x.mandante = pl3.mandante ) ";
            $pl4 = "LEFT OUTER JOIN  (SELECT usuario.moneda,usuario.pais_id,usuario.mandante,DATE_FORMAT(usuario.fecha_primerdeposito, '%Y-%d-%m') fecha_crea2,COUNT(*) primerdepositos FROM usuario INNER JOIN usuario_perfil ON (usuario.usuario_id=usuario_perfil.usuario_id AND usuario_perfil.perfil_id='USUONLINE') GROUP BY usuario.mandante,usuario.pais_id,fecha_crea2) pl4 ON (pl4.fecha_crea2 = DATE_FORMAT(" . $groupByFecha2 . ", '%Y-%d-%m') AND x.pais_id=pl4.pais_id AND x.moneda=pl4.moneda  AND x.mandante = pl4.mandante )";
            $pl5 = "LEFT OUTER JOIN (SELECT usuario.moneda, usuario.pais_id, usuario.mandante, pl.estado, sum(pl.valor) valor, DATE_FORMAT (pl.fecha_crea, '%Y-%d-%m') fecha_crea2 FROM bono_log pl INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id) where pl.estado = 'L' AND pl.tipo = 'JD' GROUP BY usuario.mandante, usuario.pais_id, fecha_crea2) pl5 ON (pl5.fecha_crea2 = DATE_FORMAT(" . $groupByFecha2 . ", '%Y-%d-%m') AND x.pais_id = pl5.pais_id AND x.moneda = pl5.moneda AND x.mandante = pl5.mandante)";

        }

        $sqlDatesBetween =' ' ;

        $startTime = strtotime( $FromDateLocal );
        $endTime = strtotime( $ToDateLocal );

        $cont=0;
// Loop between timestamps, 24 hours at a time
        for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
            $sqlDatesBetween = $sqlDatesBetween . " select pais_mandante.mandante mandante,  '" . date( 'Y-m-d', $i ) . "' fecha_crea,  '" . date( 'Y-m-d', $i ) . "' fecha_cierre, pais.pais_nom pais_nom,pais.pais_id pais_id, pais.iso pais_iso, pais_moneda.moneda moneda,
                      0                                       cant_tickets,
                      0                                       impuesto_premios,
                      0                                       impuesto_apuestas,
                      0                                       valor_apostado,
                      0                                       valor_ticket_prom,
                      0                                       proyeccion_premios,
                      0 valor_premios,
                   0 AS                   premios_live,
                   0 AS                   apuestas_live,
                   0 AS                   cantidad_live,
                   0 AS                   premios_prematch,
                   0 AS                   apuestas_prematch,
                   0 AS                   cantidad_prematch,
                   0 AS                   premios_mixta,
                   0 AS                   apuestas_mixta,
                   0 AS                   cantidad_mixta,
                   0 AS                   premios_hipicas,
                   0 AS                   apuestas_hipicas,
                   0 AS                   cantidad_hipicas,
                   0 AS                   premios_virtuales,
                   0 AS                   apuestas_virtuales,
                   0 AS                   cantidad_virtuales

         from pais_mandante
                  inner join pais on (pais.pais_id = pais_mandante.pais_id)
                  inner join pais_moneda on (pais.pais_id = pais_moneda.pais_id) ".$wherePaisParaMandante." UNION ";

        }
        if($_REQUEST['test2'] == 1){

            $sql="SHOW VARIABLES LIKE 'max_execution_time'";

            $BonoInterno = new BonoInterno();
            $Usuarios = $BonoInterno->execQuery('', $sql);
            print_r($Usuarios);



        }

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        //$sql = "select x.*,(pl2.valor) bonos,(pl4.valor) bonoscasino,pl3.registros from (select a.fecha_crea,a.fecha_cierre,c.pais_nom,c.iso pais_iso,b.moneda,count(a.ticket_id) cant_tickets,sum(a.vlr_apuesta) valor_apostado,sum(a.vlr_apuesta) / count(a.ticket_id) valor_ticket_prom,sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id=a.ticket_id)*a.vlr_apuesta) proyeccion_premios,sum(case when a.premiado='S' then a.vlr_premio else 0 end) valor_premios from it_ticket_enc a inner join usuario b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) inner join pais c on (b.pais_id=c.pais_id and c.pais_id != 1) where 1=1 and a.mandante=0 and a.eliminado='N'" . $whereFechas .$whereEstadoApuesta." ".$wherePais." group by ".$groupByFecha.",c.pais_nom,b.moneda order by a.fecha_crea,c.pais_nom,b.moneda) x  LEFT OUTER JOIN  (SELECT usuario.moneda,pl.estado,sum(pl.valor) valor,DATE_FORMAT(pl.fecha_modif, '%Y-%d-%m') fecha_crea2 FROM usuario_bono pl INNER JOIN usuario ON (usuario.usuario_id=pl.usuario_id) where  pl.estado='R' GROUP BY usuario.moneda,fecha_crea2) pl2 ON (pl2.fecha_crea2 = DATE_FORMAT(".$groupByFecha2.", '%Y-%d-%m') AND x.moneda=pl2.moneda )   LEFT OUTER JOIN  (SELECT usuario.moneda,pl.estado,sum(pl.valor) valor,DATE_FORMAT(pl.fecha_crea, '%Y-%d-%m') fecha_crea2 FROM bono_log pl INNER JOIN usuario ON (usuario.usuario_id=pl.usuario_id) where  pl.tipo='FS' GROUP BY usuario.moneda,fecha_crea2) pl4 ON (pl4.fecha_crea2 = DATE_FORMAT(".$groupByFecha2.", '%Y-%d-%m') AND x.moneda=pl4.moneda )  LEFT OUTER JOIN  (SELECT usuario.moneda,DATE_FORMAT(usuario.fecha_crea, '%Y-%d-%m') fecha_crea2,COUNT(*) registros FROM usuario INNER JOIN usuario_perfil ON (usuario.usuario_id=usuario_perfil.usuario_id AND usuario_perfil.perfil_id='USUONLINE') GROUP BY usuario.moneda,fecha_crea2) pl3 ON (pl3.fecha_crea2 = DATE_FORMAT(".$groupByFecha2.", '%Y-%d-%m') AND x.moneda=pl3.moneda ) ORDER BY " .$orderByFecha;
        $sql = "select /*+ MAX_EXECUTION_TIME(60000) */ x.*,(pl2.valor) bonos,pl3.registros,pl4.primerdepositos, (pl5.valor) jackpots from ( select xx.mandante,
             xx.fecha_crea,
             xx.fecha_cierre,
             xx.pais_nom,
             xx.pais_id,
             xx.pais_iso,
             xx.moneda,
             SUM(xx.cant_tickets) cant_tickets,
             SUM(xx.valor_apostado) valor_apostado,
             SUM(xx.impuesto_apuestas) impuesto_apuestas,
             SUM(xx.impuesto_premios) impuesto_premios,
             SUM(xx.valor_ticket_prom) valor_ticket_prom,
             SUM(xx.proyeccion_premios) proyeccion_premios,
             SUM(xx.valor_premios) valor_premios,                                                                                                                         SUM(xx.premios_live)       AS premios_live,
             SUM(xx.apuestas_live)      AS apuestas_live,
             SUM(xx.cantidad_live)      AS cantidad_live,
             SUM(xx.premios_prematch)   AS premios_prematch,
             SUM(xx.apuestas_prematch)  AS apuestas_prematch,
             SUM(xx.cantidad_prematch)  AS cantidad_prematch,
             SUM(xx.premios_mixta)      AS premios_mixta,
             SUM(xx.apuestas_mixta)     AS apuestas_mixta,
             SUM(xx.cantidad_mixta)     AS cantidad_mixta,
             SUM(xx.premios_hipicas)    AS premios_hipicas,
             SUM(xx.apuestas_hipicas)   AS apuestas_hipicas,
             SUM(xx.cantidad_hipicas)   AS cantidad_hipicas,
             SUM(xx.premios_virtuales)  AS premios_virtuales,
             SUM(xx.apuestas_virtuales) AS apuestas_virtuales,
             SUM(xx.cantidad_virtuales) AS cantidad_virtuales
                                                                                                                             
      from (".$sqlDatesBetween."

          select a.mandante mandante,a.fecha_crea,a.fecha_cierre,c.pais_nom,c.pais_id,c.iso pais_iso,b.moneda,count(a.ticket_id) cant_tickets,sum(a.impuesto) impuesto_premios,sum(a.impuesto_apuesta) impuesto_apuestas,sum(a.vlr_apuesta) valor_apostado,sum(a.vlr_apuesta) / count(a.ticket_id) valor_ticket_prom,sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id=a.ticket_id)*a.vlr_apuesta) proyeccion_premios,sum(case when a.premiado='S' then a.vlr_premio else 0 end) valor_premios,
                   sum(case when a.bet_mode = 'Live' AND a.premiado = 'S' then a.vlr_premio else 0 end) AS                   premios_live,
                   sum(case when a.bet_mode = 'Live' then a.vlr_apuesta else 0 end) AS                   apuestas_live,
                   sum(case when a.bet_mode = 'Live' then 1 else 0 end) AS                   cantidad_live,
                   sum(case when a.bet_mode = 'PreLive' AND a.premiado = 'S' then a.vlr_premio else 0 end) AS                   premios_prematch,
                   sum(case when a.bet_mode = 'PreLive' then a.vlr_apuesta else 0 end) AS                   apuestas_prematch,
                   sum(case when a.bet_mode = 'PreLive' then 1 else 0 end) AS                   cantidad_prematch,
                   sum(case when a.bet_mode = 'Mixed' AND a.premiado = 'S' then a.vlr_premio else 0 end) AS                   premios_mixta,
                   sum(case when a.bet_mode = 'Mixed' then a.vlr_apuesta else 0 end) AS                   apuestas_mixta,
                   sum(case when a.bet_mode = 'Mixed' then 1 else 0 end) AS                  cantidad_mixta,
                   sum(case when a.bet_mode = '' AND a.premiado = 'S' then a.vlr_premio else 0 end) AS                    premios_hipicas,
                   sum(case when a.bet_mode = '' then a.vlr_apuesta else 0 end) AS                   apuestas_hipicas,
                   sum(case when a.bet_mode = '' then 1 else 0 end) AS                   cantidad_hipicas,
                   sum(case when a.bet_mode = 'VSPORT' AND a.premiado = 'S' then a.vlr_premio else 0 end) AS                    premios_virtuales,
                   sum(case when a.bet_mode = 'VSPORT' then a.vlr_apuesta else 0 end) AS                  apuestas_virtuales,
                   sum(case when a.bet_mode = 'VSPORT' then 1 else 0 end) AS                    cantidad_virtuales from it_ticket_enc a 
".$timeDimension."
          
          
          inner join usuario b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) inner join usuario_perfil up on (up.usuario_id=b.usuario_id) inner join pais c on (b.pais_id=c.pais_id and c.pais_id != 1) where 1=1 and a.eliminado LIKE 'N'  " . $whereFechas . $whereEstadoApuesta . " " . $wherePais . " group by a.mandante," . $groupByFecha . ",c.pais_nom,b.moneda ) xx
      group by xx.mandante, xx.pais_nom ) x " . $pl2 . " " . $pl3 . " " . $pl4 . $pl5 . "  ORDER BY " . $orderByFecha;

        if($_ENV["debugFixed2"]){
            print_r($sql);
            exit();
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal FromDateLocal
     * @param String $ToDateLocal ToDateLocal
     * @param String $FromId FromId
     * @param String $UserId id del usuario
     * @param String $TypeBet tipo de apuesta
     * @param String $ConcesionarioId id del concensionario
     * @param String $PaisId id del pais
     * @param String $Mandante Mandante
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryInformeGerencialByUser($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $FromId = "", $UserId = "", $TypeBet = "", $ConcesionarioId = "", $PaisId = "", $Mandante = "")
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


        //$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where ;


        $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);

        $whereC = "";
        $whereEstadoApuesta = "";

        if ($FromId != "" && is_numeric($FromId)) {
            $whereC = $whereC . " AND registro.afiliador_id ='" . $FromId . "' ";
        }

        if ($UserId != "" && is_numeric($UserId)) {
            $whereC = $whereC . " AND x.usuario_id ='" . $UserId . "' ";
        }


        if ($ConcesionarioId != "" && is_numeric($ConcesionarioId)) {
            $whereC = $whereC . " AND concesionario.usupadre_id ='" . $ConcesionarioId . "' ";
        }


        if ($PaisId != "" && is_numeric($PaisId)) {
            $whereC = $whereC . " AND x.pais_id ='" . $PaisId . "' ";
        }

        if ($Mandante != "" && is_numeric($Mandante)) {
            $whereC = $whereC . " AND x.mandante IN (" . $Mandante . ") ";
        }

        $whereC = $whereC . " AND x.pais_id !='1' ";


        if ($TypeBet != "" && is_numeric($TypeBet)) {
            if ($TypeBet == 2) {
                $whereEstadoApuesta = $whereEstadoApuesta . " AND a.estado ='I' ";

            } else {
                $whereEstadoApuesta = $whereEstadoApuesta . " AND a.estado ='A' ";

            }
        }


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        $sql = "select  /*+ MAX_EXECUTION_TIME(60000) */  x.*,afiliador.usuario_id,afiliador.login,(pl2.valor) bonos,concesionario.* from (select a.usuario_id,b.mandante,a.estado,a.fecha_crea,c.pais_nom,c.pais_id,b.moneda,count(a.ticket_id) cant_tickets,sum(a.vlr_apuesta) valor_apostado,sum(a.vlr_apuesta) / count(a.ticket_id) valor_ticket_prom,sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id=a.ticket_id)*a.vlr_apuesta) proyeccion_premios,sum(case when a.premiado='S' then a.vlr_premio else 0 end) valor_premios from it_ticket_enc a inner join usuario b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) inner join pais c on (b.pais_id=c.pais_id and c.pais_id != 1) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id=b.usuario_id  and concesionario.prodinterno_id =0) where 1=1 and a.mandante=0 and a.eliminado LIKE 'N' and a.fecha_crea>='" . $FromDateLocal . "' and a.fecha_crea<='" . $ToDateLocal . "' " . $whereEstadoApuesta . "  group by a.usuario_id,a.fecha_crea,c.pais_nom,b.moneda order by a.usuario_id,a.fecha_crea,c.pais_nom,b.moneda) x        LEFT OUTER JOIN  registro  ON (x.usuario_id=registro.usuario_id) LEFT OUTER JOIN  usuario afiliador ON (afiliador.usuario_id = registro.afiliador_id)  LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id=afiliador.usuario_id and concesionario.prodinterno_id =0) LEFT OUTER JOIN  (SELECT usuario.usuario_id,usuario.moneda,pl.estado,sum(pl.valor) valor,DATE_FORMAT(pl.fecha_modif, '%Y-%d-%m') fecha_crea2 FROM usuario_bono pl INNER JOIN usuario ON (usuario.usuario_id=pl.usuario_id) where  pl.estado='R' GROUP BY usuario.usuario_id,usuario.moneda,fecha_crea2) pl2 ON (pl2.fecha_crea2 = DATE_FORMAT(x.fecha_crea, '%Y-%d-%m') AND x.usuario_id=pl2.usuario_id  ) WHERE 1=1 " . $whereC . " ";

        $sqlQuery = new SqlQuery($sql);


        $result = $Helpers->process_data($this->execute2($sqlQuery));


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal FromDateLocal
     * @param String $ToDateLocal ToDateLocal
     * @param String $TypeBet tipo de apuesta
     * @param String $PaisId id del pais
     * @param String $Mandante Mandante
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryUsuarioResumen($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet = "", $PaisId = "", $Mandante = "")
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


        //$sql = 'SELECT count(*) count FROM punto_venta INNER JOIN concesionario ON (concesionario.usuhijo_id = punto_venta.usuario_id AND concesionario.prodinterno_id=0)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=punto_venta.usuario_id)   ' . $where ;


        $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);


        $whereEstadoApuesta = "";

        if ($TypeBet != "" && is_numeric($TypeBet)) {
            if ($TypeBet == 2) {
                $whereEstadoApuesta = $whereEstadoApuesta . " AND a.estado ='I' ";

            } else {
                $whereEstadoApuesta = $whereEstadoApuesta . " AND a.estado ='A' ";

            }
        }

        $wherePais = "";

        if ($PaisId != "") {
            $wherePais = $wherePais . " AND c.pais_id='" . $PaisId . "' ";
        }


        if ($Mandante != "") {
            $wherePais = $wherePais . " AND b.mandante IN (" . $Mandante . ") ";
        }

        $wherePais = $wherePais . " AND c.pais_id !='1' ";

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

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
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        $sql = "select  /*+ MAX_EXECUTION_TIME(60000) */  'Total general' agrupador,x.*
from (select z.pais_nom,
             z.fecha,
             z.moneda,
             sum(z.saldo_recarga)          saldo_recarga,
             sum(z.disp_retiro)            disp_retiro,
             sum(z.valor_recargas)         valor_recargas,
             sum(z.valor_tickets_abiertos) valor_tickets_abiertos,
             sum(z.nota_retiro_pend)       nota_retiro_pend,
             sum(z.valor_pagado)           valor_pagado,
             sum(z.valor_apostado)         valor_apostado,
             sum(z.valor_premio)           valor_premio,
             sum(z.cant_tickets)           cant_tickets,
             sum(z.valor_promocional)      valor_promocional,
             sum(z.valor_apostado_casino)      valor_apostado_casino,
             sum(z.valor_premios_casino)      valor_premios_casino,
             sum(z.valor_apostado_casinovivo)      valor_apostado_casinovivo,
             sum(z.cant_tickets_casino)      cant_tickets_casino,
             sum(z.cant_tickets_casinovivo)      cant_tickets_casinovivo
      from (select '" . $ToDateLocal . "'              fecha,
                   c.pais_nom,
                   b.moneda,
                   sum(a.creditos_base)      saldo_recarga,
                   round(sum(a.creditos), 0) disp_retiro,
                   0                         valor_recargas,
                   0                         valor_tickets_abiertos,
                   0                         nota_retiro_pend,
                   0                         valor_pagado,
                   0                         valor_apostado,
                   0                         valor_premio,
                   0                         cant_tickets,
                   0                         valor_promocional,
                   0                           valor_apostado_casino,
                   0                           valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   0           cant_tickets_casinovivo
            from registro a
                   inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where a.mandante = 0
                    " . $wherePais . " 

            group by '" . $ToDateLocal . "',c.pais_nom,b.moneda
            /*union
            select '" . $ToDateLocal . "'       fecha,
                   d.pais_nom,
                   c.moneda,
                   0                  saldo_recarga,
                   0                  disp_retiro,
                   0                  valor_recargas,
                   sum(a.vlr_apuesta) valor_tickets_abiertos,
                   0                  nota_retiro_pend,
                   0                  valor_pagado,
                   0                  valor_apostado,
                   0                  valor_premio,
                   0                  cant_tickets,
                   0                  valor_promocional,
                   0                           valor_apostado_casino,
                   0                           valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   0           cant_tickets_casinovivo
            from it_ticket_enc a
                   inner join usuario_perfil b
                              on (a.mandante = b.mandante and a.usuario_id = b.usuario_id and b.perfil_id = 'USUONLINE')
                   inner join usuario c on (a.mandante = c.mandante and a.usuario_id = c.usuario_id)
                   inner join pais d on (c.pais_id = d.pais_id)
            where 
               a.premiado = 'N'
              and a.mandante = 0
              and a.estado " . $wherePais . " 
            group by '" . $ToDateLocal . "',d.pais_nom,c.moneda*/
            union
            select a.fecha_crea                                                 fecha,
                   d.pais_nom,
                   c.moneda,
                   0                                                            saldo_recarga,
                   0                                                            disp_retiro,
                   0                                                            valor_recargas,
                   0                                                            valor_tickets_abiertos,
                   0                                                            nota_retiro_pend,
                   0                                                            valor_pagado,
                   sum(a.vlr_apuesta)                                           valor_apostado,
                   sum(case when a.premiado = 'S' then a.vlr_premio else 0 end) valor_premio,
                   count(a.ticket_id)                                           cant_tickets,
                   0                                                            valor_promocional,
                   0                           valor_apostado_casino,
                   0                           valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   0           cant_tickets_casinovivo
            from it_ticket_enc a
                   inner join usuario_perfil b
                              on (a.mandante = b.mandante and a.usuario_id = b.usuario_id and b.perfil_id = 'USUONLINE')
                   inner join usuario c on (a.mandante = c.mandante and a.usuario_id = c.usuario_id)
                   inner join pais d on (c.pais_id = d.pais_id)
            where a.mandante = 0
              and a.fecha_crea >= '" . $FromDateLocal . "'
              and a.fecha_crea <= '" . $ToDateLocal . "'
               " . $whereEstadoApuesta . " " . $wherePais . " 
            group by a.fecha_crea,d.pais_nom,c.moneda
            union
            select substr(a.fecha_crea, 1, 10) fecha,
                   c.pais_nom,
                   b.moneda,
                   0                           saldo_recarga,
                   0                           disp_retiro,
                   sum(valor)                  valor_recargas,
                   0                           valor_tickets_abiertos,
                   0                           nota_retiro_pend,
                   0                           valor_pagado,
                   0                           valor_apostado,
                   0                           valor_premio,
                   0                           cant_tickets,
                   sum(valor_promocional)      valor_promocional,
                   0                           valor_apostado_casino,
                   0                           valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   0           cant_tickets_casinovivo
            from usuario_recarga a
                   inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where a.mandante = 0
              and substr(a.fecha_crea, 1, 10) >= '" . $FromDateLocal . "'
              and substr(a.fecha_crea, 1, 10) <= '" . $ToDateLocal . "'
              " . $wherePais . " 
            group by substr(a.fecha_crea, 1, 10),c.pais_nom,b.moneda
            union
            select '" . $ToDateLocal . "' fecha,
                   c.pais_nom,
                   b.moneda,
                   0            saldo_recarga,
                   0            disp_retiro,
                   0            valor_recargas,
                   0            valor_tickets_abiertos,
                   sum(a.valor) nota_retiro_pend,
                   0            valor_pagado,
                   0            valor_apostado,
                   0            valor_premio,
                   0            cant_tickets,
                   0            valor_promocional,
                   0                           valor_apostado_casino,
                   0                           valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   0           cant_tickets_casinovivo
            from cuenta_cobro a
                   inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where a.mandante = 0
              and a.estado = 'A'
               " . $wherePais . " 
            group by '" . $ToDateLocal . "',c.pais_nom,b.moneda
            union
            select substr(a.fecha_pago, 1, 10) fecha,
                   c.pais_nom,
                   b.moneda,
                   0                           saldo_recarga,
                   0                           disp_retiro,
                   0                           valor_recargas,
                   0                           valor_tickets_abiertos,
                   0                           nota_retiro_pend,
                   sum(a.valor)                valor_pagado,
                   0                           valor_apostado,
                   0                           valor_premio,
                   0                           cant_tickets,
                   0                           valor_promocional,
                   0                           valor_apostado_casino,
                   0                           valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   0           cant_tickets_casinovivo
            from cuenta_cobro a
                   inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where a.mandante = 0
              and a.estado = 'I'
              and substr(a.fecha_pago, 1, 10) >= '" . $FromDateLocal . "'
              and substr(a.fecha_pago, 1, 10) <= '" . $ToDateLocal . "'
                " . $wherePais . " 
            group by substr(a.fecha_pago, 1, 10),c.pais_nom,b.moneda
            union
            select substr(a.fecha_crea, 1, 10) fecha,
                   c.pais_nom,
                   b.moneda,
                   0                           saldo_recarga,
                   0                           disp_retiro,
                   0                           valor_recargas,
                   0                           valor_tickets_abiertos,
                   0                           nota_retiro_pend,
                   0                           valor_pagado,
                   0                           valor_apostado,
                   0                           valor_premio,
                   0                           cant_tickets,
                   0                           valor_promocional,
                   SUM(a.valor)                valor_apostado_casino,
                   0                           valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   count(a.transjuego_id)           cant_tickets_casino,
                   0           cant_tickets_casinovivo

            from transjuego_log a
                    inner join transaccion_juego ON (a.transjuego_id = transaccion_juego.transjuego_id)
                     inner join casino.producto_mandante on (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     inner join casino.producto on (producto_mandante.producto_id = producto.producto_id)
                     inner join casino.proveedor on (proveedor.proveedor_id = producto.proveedor_id)
                    inner join usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                   inner join usuario b on (usuario_mandante.mandante = b.mandante and usuario_mandante.usuario_mandante = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where
               substr(a.fecha_crea, 1, 10) >= '" . $FromDateLocal . "'
              and substr(a.fecha_crea, 1, 10) <= '" . $ToDateLocal . "'
              AND a.tipo LIKE '%DEBIT%' AND proveedor.tipo = 'CASINO'
                " . $wherePais . "                 

            group by substr(a.fecha_crea, 1, 10),c.pais_nom,b.moneda
            
             union
            select substr(a.fecha_crea, 1, 10) fecha,
                   c.pais_nom,
                   b.moneda,
                   0                           saldo_recarga,
                   0                           disp_retiro,
                   0                           valor_recargas,
                   0                           valor_tickets_abiertos,
                   0                           nota_retiro_pend,
                   0                           valor_pagado,
                   0                           valor_apostado,
                   0                           valor_premio,
                   0                           cant_tickets,
                   0                           valor_promocional,
                   0                           valor_apostado_casino,
                   SUM(a.valor)                valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   0           cant_tickets_casinovivo
            from transjuego_log a
                    inner join transaccion_juego ON (a.transjuego_id = transaccion_juego.transjuego_id)
                     inner join casino.producto_mandante on (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     inner join casino.producto on (producto_mandante.producto_id = producto.producto_id)
                     inner join casino.proveedor on (proveedor.proveedor_id = producto.proveedor_id)

                    inner join usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                   inner join usuario b on (usuario_mandante.mandante = b.mandante and usuario_mandante.usuario_mandante = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where
               substr(a.fecha_crea, 1, 10) >= '" . $FromDateLocal . "'
              and substr(a.fecha_crea, 1, 10) <= '" . $ToDateLocal . "'
              AND a.tipo LIKE '%CREDIT%' AND proveedor.tipo = 'CASINO'
                " . $wherePais . "                 

            group by substr(a.fecha_crea, 1, 10),c.pais_nom,b.moneda
            
            union
            select substr(a.fecha_crea, 1, 10) fecha,
                   c.pais_nom,
                   b.moneda,
                   0                           saldo_recarga,
                   0                           disp_retiro,
                   0                           valor_recargas,
                   0                           valor_tickets_abiertos,
                   0                           nota_retiro_pend,
                   0                           valor_pagado,
                   0                           valor_apostado,
                   0                           valor_premio,
                   0                           cant_tickets,
                   0                           valor_promocional,
                   0                valor_apostado_casino,
                   0                           valor_premios_casino,
                   SUM(a.valor)                           valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   count(a.transjuego_id)           cant_tickets_casinovivo

            from transjuego_log a
                    inner join transaccion_juego ON (a.transjuego_id = transaccion_juego.transjuego_id)
                     inner join casino.producto_mandante on (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     inner join casino.producto on (producto_mandante.producto_id = producto.producto_id)
                     inner join casino.proveedor on (proveedor.proveedor_id = producto.proveedor_id)
                    inner join usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                   inner join usuario b on (usuario_mandante.mandante = b.mandante and usuario_mandante.usuario_mandante = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where
               substr(a.fecha_crea, 1, 10) >= '" . $FromDateLocal . "'
              and substr(a.fecha_crea, 1, 10) <= '" . $ToDateLocal . "'
              AND a.tipo LIKE '%DEBIT%' AND proveedor.tipo = 'LIVECASINO'
                " . $wherePais . "                 

            group by substr(a.fecha_crea, 1, 10),c.pais_nom,b.moneda
            
             union
            select substr(a.fecha_crea, 1, 10) fecha,
                   c.pais_nom,
                   b.moneda,
                   0                           saldo_recarga,
                   0                           disp_retiro,
                   0                           valor_recargas,
                   0                           valor_tickets_abiertos,
                   0                           nota_retiro_pend,
                   0                           valor_pagado,
                   0                           valor_apostado,
                   0                           valor_premio,
                   0                           cant_tickets,
                   0                           valor_promocional,
                   0                           valor_apostado_casino,
                   0                valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   SUM(a.valor)                           valor_premios_casinovivo,
                   0           cant_tickets_casino,
                   0           cant_tickets_casinovivo
            from transjuego_log a
                    inner join transaccion_juego ON (a.transjuego_id = transaccion_juego.transjuego_id)
                     inner join casino.producto_mandante on (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     inner join casino.producto on (producto_mandante.producto_id = producto.producto_id)
                     inner join casino.proveedor on (proveedor.proveedor_id = producto.proveedor_id)

                    inner join usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                   inner join usuario b on (usuario_mandante.mandante = b.mandante and usuario_mandante.usuario_mandante = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where
               substr(a.fecha_crea, 1, 10) >= '" . $FromDateLocal . "'
              and substr(a.fecha_crea, 1, 10) <= '" . $ToDateLocal . "'
              AND a.tipo LIKE '%CREDIT%' AND proveedor.tipo = 'LIVECASINO'
                " . $wherePais . "                 

            group by substr(a.fecha_crea, 1, 10),c.pais_nom,b.moneda
            
            /*
             union
            select substr(a.fecha_crea, 1, 10) fecha,
                   c.pais_nom,
                   b.moneda,
                   0                           saldo_recarga,
                   0                           disp_retiro,
                   0                           valor_recargas,
                   0                           valor_tickets_abiertos,
                   0                           nota_retiro_pend,
                   0                           valor_pagado,
                   0                           valor_apostado,
                   0                           valor_premio,
                   0                           cant_tickets,
                   0                           valor_promocional,
                   0                           valor_apostado_casino,
                   0                           valor_premios_casino,
                   SUM(a.valor)                valor_apostado_casinovivo,
                   0                           valor_premios_casinovivo
            from transaccion_api a
                    inner join transaccion_juego ON (a.identificador = transaccion_juego.ticket_id)
                     inner join casino.producto_mandante on (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     inner join casino.producto on (producto_mandante.producto_id = producto.producto_id)
                     inner join casino.proveedor on (proveedor.proveedor_id = producto.proveedor_id)

                    inner join usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                   inner join usuario b on (usuario_mandante.mandante = b.mandante and usuario_mandante.usuario_mandante = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where
               substr(a.fecha_crea, 1, 10) >= '" . $FromDateLocal . "'
              and substr(a.fecha_crea, 1, 10) <= '" . $ToDateLocal . "'
              AND a.tipo LIKE '%DEBIT%' AND proveedor.tipo = 'LIVECASINO'
                " . $wherePais . "                 

            group by substr(a.fecha_crea, 1, 10),c.pais_nom,b.moneda
            
            
             union
            select substr(a.fecha_crea, 1, 10) fecha,
                   c.pais_nom,
                   b.moneda,
                   0                           saldo_recarga,
                   0                           disp_retiro,
                   0                           valor_recargas,
                   0                           valor_tickets_abiertos,
                   0                           nota_retiro_pend,
                   0                           valor_pagado,
                   0                           valor_apostado,
                   0                           valor_premio,
                   0                           cant_tickets,
                   0                           valor_promocional,
                   0                           valor_apostado_casino,
                   0                           valor_premios_casino,
                   0                           valor_apostado_casinovivo,
                   SUM(a.valor)                valor_premios_casinovivo
            from transaccion_api a
                    inner join transaccion_juego ON (a.identificador = transaccion_juego.ticket_id)
                     inner join casino.producto_mandante on (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     inner join casino.producto on (producto_mandante.producto_id = producto.producto_id)
                     inner join casino.proveedor on (proveedor.proveedor_id = producto.proveedor_id)

                    inner join usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                   inner join usuario b on (usuario_mandante.mandante = b.mandante and usuario_mandante.usuario_mandante = b.usuario_id)
                   inner join pais c on (b.pais_id = c.pais_id)
            where
               substr(a.fecha_crea, 1, 10) >= '" . $FromDateLocal . "'
              and substr(a.fecha_crea, 1, 10) <= '" . $ToDateLocal . "'
              AND a.tipo LIKE '%CREDIT%' AND proveedor.tipo = 'LIVECASINO'
                " . $wherePais . "                 

            group by substr(a.fecha_crea, 1, 10),c.pais_nom,b.moneda*/
            ) z
      group by z.pais_nom,z.fecha,z.moneda
      order by z.pais_nom,z.fecha) x";


        $sqlQuery = new SqlQuery($sql);


        $result = $Helpers->process_data($this->execute2($sqlQuery));


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Crear y devolver un objeto del tipo PuntoVenta
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $PuntoVenta PuntoVenta
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $puntoVenta = new PuntoVenta();
        $Helpers = new Helpers();

        $puntoVenta->puntoventaId = $row['puntoventa_id'];
        $puntoVenta->descripcion = $row['descripcion'];
        $puntoVenta->ciudad = $row['ciudad'];
        $puntoVenta->direccion = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->decode_data($row['direccion']);
        $puntoVenta->telefono = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->decode_data($row['telefono']);
        $puntoVenta->nombreContacto = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->decode_data($row['nombre_contacto']);
        $puntoVenta->estado = $row['estado'];
        $puntoVenta->usuarioId = $row['usuario_id'];
        $puntoVenta->ciudadId = $row['ciudad_id'];
        $puntoVenta->mandante = $row['mandante'];
        $puntoVenta->email = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->decode_data($row['email']);
        $puntoVenta->valorCupo = $row['valor_cupo'];
        $puntoVenta->porcenComision = $row['porcen_comision'];
        $puntoVenta->periodicidadId = $row['periodicidad_id'];
        $puntoVenta->valorRecarga = $row['valor_recarga'];
        $puntoVenta->valorCupo2 = $row['valor_cupo2'];
        $puntoVenta->porcenComision2 = $row['porcen_comision2'];
        $puntoVenta->barrio = $row['barrio'];
        $puntoVenta->clasificador1Id = $row['clasificador1_id'];
        $puntoVenta->clasificador2Id = $row['clasificador2_id'];
        $puntoVenta->clasificador3Id = $row['clasificador3_id'];
        $puntoVenta->clasificador4Id = $row['clasificador4_id'];
        $puntoVenta->creditos = $row['creditos'];
        $puntoVenta->creditosBase = $row['creditos_base'];
        $puntoVenta->creditosAnt = $row['creditos_ant'];
        $puntoVenta->creditosBaseAnt = $row['creditos_base_ant'];
        $puntoVenta->cupoRecarga = $row['cupo_recarga'];
        $puntoVenta->moneda = $row['moneda'];
        $puntoVenta->idioma = $row['idioma'];

        $puntoVenta->propio = $row['propio'];

        $puntoVenta->cuentacontableId = $row['cuentacontable_id'];
        $puntoVenta->cuentacontablecierreId = $row['cuentacontablecierre_id'];
        $puntoVenta->codigoPersonalizado = $row['codigo_personalizado'];

        $puntoVenta->headerRecibopagopremio = $row['header_recibopagopremio'];
        $puntoVenta->footerRecibopagopremio = $row['footer_recibopagopremio'];
        $puntoVenta->headerRecibopagoretiro = $row['header_recibopagoretiro'];
        $puntoVenta->footerRecibopagoretiro = $row['footer_recibopagoretiro'];
        $puntoVenta->tipoTienda = $row['tipo_tienda'];
        $puntoVenta->impuestoPagopremio = $row['impuesto_pagopremio'];
        $puntoVenta->cedula = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT', true)->decode_data($row['cedula']);
        $puntoVenta->identificacionIp = $row['identificacion_ip'];


        if($puntoVenta->identificacionIp == ''){
            $puntoVenta->identificacionIp='0';
        }
        $puntoVenta->facebook = $row['facebook'];
        $puntoVenta->facebookVerificacion = $row['facebook_verificacion'];
        $puntoVenta->instagram = $row['instagram'];
        $puntoVenta->instagramVerificacion = $row['instagram_verificacion'];
        $puntoVenta->whatsApp = $row['whatsApp'];
        $puntoVenta->whatsAppVerificacion = $row['whatsApp_verificacion'];
        $puntoVenta->otraRedesSocial = $row['otraredessociales'];
        $puntoVenta->otraRedesSocialVerificacion = $row['otraredessociales_verificacion'];
        $puntoVenta->otraRedesSocialName = $row['otraredessocialesname'];
        $puntoVenta->PhysicalPrize = $row['premiofisico'];


        return $puntoVenta;
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
