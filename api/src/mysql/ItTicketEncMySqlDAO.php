<?php namespace Backend\mysql;

use Backend\dao\ItTicketEncDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Helpers;
use Backend\dto\ItTicketEnc;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Clase 'ItTicketEncMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'ItTicketEnc'
 *
 * Ejemplo de uso:
 * $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class ItTicketEncMySqlDAO implements ItTicketEncDAO
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
        $sql = 'SELECT * FROM it_ticket_enc WHERE it_ticket_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
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
    public function loadByTicketId($id)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE ticket_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener el registro condicionado por la
     * llave primaria y la clave encriptada
     *
     * @param String $ticketId llave primaria
     * @param String $clave clave
     *
     * @return Array resultado de la consulta
     *
     */
    public function checkTicket($ticketId, $clave)
    {
        $clave_encrypt = base64_decode($_ENV['APP_PASSKEY']);
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
        $this->execute2($sqlQuery2);

        $sql = "SELECT * FROM it_ticket_enc WHERE ticket_id = ? and aes_decrypt(clave,'" . $clave_encrypt . "')='" . $clave . "'";

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($ticketId);
        $return = $this->getRow($sqlQuery);
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
        $this->execute2($sqlQuery2);
        return $return;
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM it_ticket_enc';
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
        $sql = 'SELECT * FROM it_ticket_enc ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $it_ticket_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($it_ticket_id)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE it_ticket_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($it_ticket_id);
        return $this->executeUpdate($sqlQuery);
    }


    public function existsTicketId($ticketId,$usuario_id){
        $sql = 'SELECT * FROM it_ticket_enc WHERE ticket_id = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($ticketId);
        return $this->getList($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object itTicketEnc itTicketEnc
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($itTicketEnc)
    {
        $sql = 'INSERT INTO it_ticket_enc (transaccion_id, ticket_id, vlr_apuesta, impuesto_apuesta, vlr_premio, usuario_id, game_reference, 
                           bet_status, cant_lineas, fecha_crea, hora_crea, premiado, premio_pagado, fecha_pago, hora_pago, 
                           mandante, estado, eliminado, fecha_maxpago, fecha_cierre, hora_cierre, clave, usumodifica_id, 
                           fecha_modifica, beneficiario_id, tipo_beneficiario, dir_ip, bet_mode, freebet, saldo_creditos, 
                           saldo_creditos_base, saldo_bonos, saldo_free, impuesto, vlr_premio_previo, 
                           transaccion_wallet, wallet, fecha_crea_time,fecha_pago_time,fecha_cierre_time,
                           fecha_modifica_time,fecha_maxpago_time) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        if(strlen($itTicketEnc->fechaPago) > 10){

            $itTicketEnc->horaPago=explode(' ',$itTicketEnc->fechaPago)[1]; //Solo se inserta en el WIN
            $itTicketEnc->fechaPago=explode(' ',$itTicketEnc->fechaPago)[0]; //Solo se inserta en el WIN
        }

        if(strlen($itTicketEnc->fechaCrea) > 10){

            $itTicketEnc->horaCrea=explode(' ',$itTicketEnc->fechaCrea)[1];
            $itTicketEnc->fechaCrea=explode(' ',$itTicketEnc->fechaCrea)[0];

        }

        $itTicketEnc->horaCrea= date('H:i:s');
        $itTicketEnc->fechaCrea= date('Y-m-d');

        if(strlen($itTicketEnc->fechaCierre) >10){
            $itTicketEnc->horaCierre=explode(' ',$itTicketEnc->fechaCierre)[1];
            $itTicketEnc->fechaCierre=explode(' ',$itTicketEnc->fechaCierre)[0];

        }


        if(strlen($itTicketEnc->fechaMaxpago) >10){
            $itTicketEnc->fechaMaxpago=explode(' ',$itTicketEnc->fechaMaxpago)[0];

        }

        if($itTicketEnc->beneficiarioId ==''){
            $itTicketEnc->beneficiarioId='0';

        }

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($itTicketEnc->transaccionId);
        $sqlQuery->set($itTicketEnc->ticketId);
        $sqlQuery->set($itTicketEnc->vlrApuesta);

        if($itTicketEnc->impuestoApuesta == ""){
            $itTicketEnc->impuestoApuesta='0';
        }

        $sqlQuery->set($itTicketEnc->impuestoApuesta);
        $sqlQuery->set($itTicketEnc->vlrPremio);
        $sqlQuery->set($itTicketEnc->usuarioId);
        $sqlQuery->set($itTicketEnc->gameReference);
        $sqlQuery->set($itTicketEnc->betStatus);
        $sqlQuery->set($itTicketEnc->cantLineas);
        $sqlQuery->set($itTicketEnc->fechaCrea);
        $sqlQuery->set($itTicketEnc->horaCrea);
        $sqlQuery->set($itTicketEnc->premiado);
        $sqlQuery->set($itTicketEnc->premioPagado);

        $sqlQuery->set($itTicketEnc->fechaPago);
        $sqlQuery->set($itTicketEnc->horaPago);
        $sqlQuery->set($itTicketEnc->mandante);
        $sqlQuery->set($itTicketEnc->estado);
        $sqlQuery->set($itTicketEnc->eliminado);
        $sqlQuery->set($itTicketEnc->fechaMaxpago);
        $sqlQuery->set($itTicketEnc->fechaCierre);
        $sqlQuery->set($itTicketEnc->horaCierre);


        $clave_encrypt = base64_decode($_ENV['APP_PASSKEY']);

        $sqlQuery->setSIN("aes_encrypt('".$itTicketEnc->clave . "','" . $clave_encrypt . "')");
        $sqlQuery->set($itTicketEnc->usumodificaId);
        $sqlQuery->set($itTicketEnc->fechaModifica);
        $sqlQuery->set($itTicketEnc->beneficiarioId);
        $sqlQuery->set($itTicketEnc->tipoBeneficiario);
        $sqlQuery->set($itTicketEnc->dirIp);
        $sqlQuery->set($itTicketEnc->betmode);


        if($itTicketEnc->freebet == '' || $itTicketEnc->freebet== null){
            $sqlQuery->set('0'); //freebet
        }else{
            $sqlQuery->set($itTicketEnc->freebet); //freebet
        }

        $sqlQuery->set($itTicketEnc->getSaldoCreditos()); //saldo_creditos
        $sqlQuery->set($itTicketEnc->getSaldoCreditosBase()); //saldo_creditos_base
        $sqlQuery->set(0); //saldo_bonos
        $sqlQuery->set(0); //saldo_free

        if($itTicketEnc->impuesto == ""){
            $itTicketEnc->impuesto='0';
        }

        $sqlQuery->set($itTicketEnc->impuesto);//impuesto

        $sqlQuery->set($itTicketEnc->valorPremioPrevio); //valor_premio_previo
        $sqlQuery->set($itTicketEnc->transaccionWallet); //transaccion_wallet
        $sqlQuery->set($itTicketEnc->wallet); //wallet


        $fechaCreaTime='';

        if($itTicketEnc->fechaCrea!= '' && $itTicketEnc->horaCrea!= '' ){
            $fechaCreaTime = $itTicketEnc->fechaCrea . ' '.  $itTicketEnc->horaCrea;
        }



        $fechaPagoTime='';

        if($itTicketEnc->fechaPago!= '' && $itTicketEnc->horaPago!= '' ){
            $fechaPagoTime = $itTicketEnc->fechaPago . ' '.  $itTicketEnc->horaPago;
        }


        $fechaCierreTime='';

        if($itTicketEnc->fechaCierre!= '' && $itTicketEnc->horaCierre!= '' ){
            $fechaCierreTime = $itTicketEnc->fechaCierre . ' '.  $itTicketEnc->horaCierre;
        }



        $fechaModificaTime='';

        if($itTicketEnc->fechaModifica!= ''  ){
            $fechaModificaTime = $itTicketEnc->fechaModifica;
        }


        $fechaMaxpagoTime='';

        if($itTicketEnc->fechaMaxpago!= ''  ){
            $fechaMaxpagoTime = $itTicketEnc->fechaMaxpago;
        }


        if($fechaCreaTime ==''){
            $sqlQuery->setSIN('null');

        }else{
            $sqlQuery->set($fechaCreaTime);

        }
        if($fechaPagoTime ==''){
            $sqlQuery->setSIN('null');

        }else{
            $sqlQuery->set($fechaPagoTime);

        }

        if($fechaCierreTime ==''){
            $sqlQuery->setSIN('null');

        }else{
            $sqlQuery->set($fechaCierreTime);

        }
        if($fechaModificaTime ==''){
            $sqlQuery->setSIN('null');

        }else{
            $sqlQuery->set($fechaModificaTime);

        }
        if($fechaMaxpagoTime ==''){
            $sqlQuery->setSIN('null');

        }else{
            $sqlQuery->set($fechaMaxpagoTime);

        }

        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
        $this->execute2($sqlQuery2);
        $id = $this->executeInsert($sqlQuery);
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
        $this->execute2($sqlQuery2);
        $itTicketEnc->itTicketId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object itTicketEnc itTicketEnc
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($itTicketEnc,$where="")
    {
        $sql = 'UPDATE it_ticket_enc SET transaccion_id = ?, ticket_id = ?, vlr_apuesta = ?, impuesto_apuesta = ?, vlr_premio = ?, usuario_id = ?, game_reference = ?, bet_status = ?, cant_lineas = ?, fecha_crea = ?, hora_crea = ?, premiado = ?, premio_pagado = ?, fecha_pago = ?, hora_pago = ?, mandante = ?, estado = ?, eliminado = ?, fecha_maxpago = ?, fecha_cierre = ?, hora_cierre = ?, usumodifica_id = ?, fecha_modifica = ?, beneficiario_id = ?, tipo_beneficiario = ?, dir_ip = ?, impuesto = ?,fecha_crea_time = ?,fecha_pago_time = ?,fecha_cierre_time = ?,fecha_modifica_time = ?,fecha_maxpago_time = ?
                         , saldo_creditos = ?, saldo_creditos_base = ? WHERE it_ticket_id = ? '.$where;
        $sqlQuery = new SqlQuery($sql);



        if(strlen($itTicketEnc->fechaPago) >10){

            $itTicketEnc->horaPago=explode(' ',$itTicketEnc->fechaPago)[1]; //Solo se inserta en el WIN
            $itTicketEnc->fechaPago=explode(' ',$itTicketEnc->fechaPago)[0]; //Solo se inserta en el WIN
        }
        $fechaMaxpagoTime=$itTicketEnc->fechaMaxpago;

        if(strlen($itTicketEnc->fechaMaxpago) >10){
            $itTicketEnc->fechaMaxpago=explode(' ',$itTicketEnc->fechaMaxpago)[0];

        }

        if(strlen($itTicketEnc->fechaCierre) >10){
            $itTicketEnc->horaCierre=explode(' ',$itTicketEnc->fechaCierre)[1];
            $itTicketEnc->fechaCierre=explode(' ',$itTicketEnc->fechaCierre)[0];

        }

        if(strlen($itTicketEnc->fechaCierre) >10){
            $itTicketEnc->horaCierre=explode(' ',$itTicketEnc->fechaCierre)[1];
            $itTicketEnc->fechaCierre=explode(' ',$itTicketEnc->fechaCierre)[0];

        }
        if(strlen($itTicketEnc->fechaPago) >10){
            $itTicketEnc->horaPago=explode(' ',$itTicketEnc->horaPago)[1];
            $itTicketEnc->fechaPago=explode(' ',$itTicketEnc->fechaPago)[0];

        }
        if(strlen($itTicketEnc->fechaCrea) >10){
            $itTicketEnc->horaCrea=explode(' ',$itTicketEnc->horaCrea)[1];
            $itTicketEnc->fechaCrea=explode(' ',$itTicketEnc->fechaCrea)[0];

        }

        $sqlQuery->set($itTicketEnc->transaccionId);
        $sqlQuery->set($itTicketEnc->ticketId);
        $sqlQuery->set($itTicketEnc->vlrApuesta);

        if($itTicketEnc->impuestoApuesta == ""){
            $itTicketEnc->impuestoApuesta='0';
        }

        $sqlQuery->set($itTicketEnc->impuestoApuesta);
        $sqlQuery->set($itTicketEnc->vlrPremio);
        $sqlQuery->set($itTicketEnc->usuarioId);
        $sqlQuery->set($itTicketEnc->gameReference);
        $sqlQuery->set($itTicketEnc->betStatus);
        $sqlQuery->set($itTicketEnc->cantLineas);
        $sqlQuery->set($itTicketEnc->fechaCrea);
        $sqlQuery->set($itTicketEnc->horaCrea);
        $sqlQuery->set($itTicketEnc->premiado);
        $sqlQuery->set($itTicketEnc->premioPagado);
        $sqlQuery->set($itTicketEnc->fechaPago);
        $sqlQuery->set($itTicketEnc->horaPago);
        $sqlQuery->set($itTicketEnc->mandante);
        $sqlQuery->set($itTicketEnc->estado);
        $sqlQuery->set($itTicketEnc->eliminado);
        $sqlQuery->set($itTicketEnc->fechaMaxpago);



        $sqlQuery->set($itTicketEnc->fechaCierre);
        $sqlQuery->set($itTicketEnc->horaCierre);
        $sqlQuery->set($itTicketEnc->usumodificaId);
        $sqlQuery->set($itTicketEnc->fechaModifica);
        $sqlQuery->set($itTicketEnc->beneficiarioId);
        $sqlQuery->set($itTicketEnc->tipoBeneficiario);
        $sqlQuery->set($itTicketEnc->dirIp);
        if($itTicketEnc->impuesto == ""){
            $itTicketEnc->impuesto='0';
        }

        $sqlQuery->set($itTicketEnc->impuesto);


        if($itTicketEnc->fechaCrea == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($itTicketEnc->fechaCrea . ' '.$itTicketEnc->horaCrea);
        }


        if($itTicketEnc->fechaPago == '' || $itTicketEnc->fechaPago==" " ){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($itTicketEnc->fechaPago. ' '.$itTicketEnc->horaPago);
        }



        if($itTicketEnc->fechaCierre == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($itTicketEnc->fechaCierre . ' '.$itTicketEnc->horaCierre);
        }

        if($itTicketEnc->fechaModifica == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($itTicketEnc->fechaModifica);
        }

        if($itTicketEnc->fechaMaxpago == ''  || $itTicketEnc->fechaMaxpago==" "){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($itTicketEnc->fechaMaxpago);        }


        $sqlQuery->set($itTicketEnc->getSaldoCreditos()); //saldo_creditos
        $sqlQuery->set($itTicketEnc->getSaldoCreditosBase()); //saldo_creditos_base

        $sqlQuery->set($itTicketEnc->itTicketId);


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
        $sql = 'DELETE FROM it_ticket_enc';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna transaccion_id sea igual al valor pasado como parámetro
     *
     * @param String $value transaccion_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByTransaccionId($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE transaccion_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByTicketId($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE ticket_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_apuesta sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_apuesta requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByVlrApuesta($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE vlr_apuesta = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_premio sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_premio requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByVlrPremio($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE vlr_premio = ?';
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
        $sql = 'SELECT * FROM it_ticket_enc WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna game_reference sea igual al valor pasado como parámetro
     *
     * @param String $value game_reference requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByGameReference($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE game_reference = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna bet_status sea igual al valor pasado como parámetro
     *
     * @param String $value bet_status requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByBetStatus($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE bet_status = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cant_lineas sea igual al valor pasado como parámetro
     *
     * @param String $value cant_lineas requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByCantLineas($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE cant_lineas = ?';
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
        $sql = 'SELECT * FROM it_ticket_enc WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_crea sea igual al valor pasado como parámetro
     *
     * @param String $value hora_crea requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByHoraCrea($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE hora_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premiado sea igual al valor pasado como parámetro
     *
     * @param String $value premiado requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByPremiado($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE premiado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_pagado sea igual al valor pasado como parámetro
     *
     * @param String $value premio_pagado requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByPremioPagado($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE premio_pagado = ?';
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
        $sql = 'SELECT * FROM it_ticket_enc WHERE fecha_pago = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_pago sea igual al valor pasado como parámetro
     *
     * @param String $value hora_pago requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByHoraPago($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE hora_pago = ?';
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
        $sql = 'SELECT * FROM it_ticket_enc WHERE mandante = ?';
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
        $sql = 'SELECT * FROM it_ticket_enc WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna eliminado sea igual al valor pasado como parámetro
     *
     * @param String $value eliminado requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByEliminado($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE eliminado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_maxpago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_maxpago requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaMaxpago($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE fecha_maxpago = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cierre requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaCierre($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE fecha_cierre = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value hora_cierre requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByHoraCierre($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE hora_cierre = ?';
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
        $sql = 'SELECT * FROM it_ticket_enc WHERE clave = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodifica_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodifica_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByUsumodificaId($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE usumodifica_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modifica sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modifica requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaModifica($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE fecha_modifica = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna beneficiario_id sea igual al valor pasado como parámetro
     *
     * @param String $value beneficiario_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByBeneficiarioId($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE beneficiario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo_beneficiario sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_beneficiario requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByTipoBeneficiario($value)
    {
        $sql = 'SELECT * FROM it_ticket_enc WHERE tipo_beneficiario = ?';
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
        $sql = 'SELECT * FROM it_ticket_enc WHERE dir_ip = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Realizar una consulta en la tabla de IntTicketEnc 'IntTicketEnc'
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
     * @param String $having condición para agrupar
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryTicketsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "",$withCount=true,$daydimensionFechaP=0,$forceTimeDimension=false,$typeDimension=0,$isInnerConce=false)
    {


        $where = " where 1=1 ";
        $Helpers = new Helpers();
        $innConcesionario=false;
        $innBeneficiarioId=false;

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;


            foreach ($rules as $rule) {
                $fieldName = (string)$Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;

                $cond="concesionario";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innConcesionario=true;
                }
                $cond="afiliador";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innBeneficiarioId=true;
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
                    case "bt":
                        $fieldOperation = " BETWEEN " . $fieldData . " ";
                        break;
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "nisnull":
                        $fieldOperation = " NOT IS NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if ($having != null) {

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
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "nisnull":
                        $fieldOperation = " NOT IS NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (count($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        $daydimensionFecha = "it_ticket_enc.fecha_crea";
        $hourdimensionFecha = "it_ticket_enc.hora_crea";

        if($daydimensionFechaP == 1){
            $daydimensionFecha = "it_ticket_enc.fecha_pago";
            $hourdimensionFecha = "it_ticket_enc.hora_pago";

        }

        if($daydimensionFechaP == 2){
            $daydimensionFecha = "it_ticket_enc.fecha_cierre";
            $hourdimensionFecha = "it_ticket_enc.hora_cierre";

        }

        if($daydimensionFechaP == 3){
            $daydimensionFecha = "it_ticket_enc.fecha_maxpago";
        }

        $typeDimensionSql="inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = ".$daydimensionFecha.")";
        $typeDimensionSql='';
        if($typeDimension==4){
            if($hourdimensionFecha != ''){
                $typeDimensionSql="inner join time_dimension4  
                    on (time_dimension4.datestr = ".$daydimensionFecha." and hour2str =".$hourdimensionFecha.")";

            }else{
                $typeDimensionSql="inner join time_dimension4 
                    on (time_dimension4.datestr = ".$daydimensionFecha." )";

            }
            //$typeDimensionSql='';

        }if($typeDimension==5){
        if($hourdimensionFecha != ''){
            $typeDimensionSql="inner join time_dimension5  force index (time_dimension5_dbdate_dbdate1_index)
                    on (time_dimension5.datestr = ".$daydimensionFecha." and hour2str =".$hourdimensionFecha.")";

        }else{
            $typeDimensionSql="inner join time_dimension5  force index (time_dimension5_dbdate_dbdate1_index)
                    on (time_dimension5.datestr = ".$daydimensionFecha." )";

        }

     }

        $sqlinnerBeneficiarioId = '' ;
        $sqlinnerConcesionario = '' ;


        if($innBeneficiarioId){
            $sqlinnerBeneficiarioId = "          LEFT OUTER JOIN usuario as afiliador ON ((it_ticket_enc.beneficiario_id = afiliador.usuario_id AND it_ticket_enc.tipo_beneficiario=1) OR (it_ticket_enc.beneficiario_id = afiliador.usuario_id AND it_ticket_enc.tipo_beneficiario=2)) ";
        }
        if($isInnerConce){
            if($innConcesionario){
                $sqlinnerConcesionario = "  INNER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') ";
            }
            $sql = "SELECT /*+ MAX_EXECUTION_TIME(60000) */  count(*) count FROM it_ticket_enc  

             ".$typeDimensionSql."

        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id) INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) 
         ".$sqlinnerBeneficiarioId."
         LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id) 
         LEFT OUTER JOIN it_ticket_enc_info1 itei1 ON (usuario_perfil.perfil_id != 'USUONLINE' AND itei1.tipo = 'USUARIORELACIONADO' AND it_ticket_enc.ticket_id = itei1.ticket_id) /*Vinculo a cliente auténtico en apuestas hechas mediante PVenta */
                   ".$sqlinnerConcesionario . $where;

        }else{
            if($innConcesionario){
                $sqlinnerConcesionario = "  LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') ";
            }
            $sql = "SELECT /*+ MAX_EXECUTION_TIME(60000) */  count(*) count FROM it_ticket_enc  

             ".$typeDimensionSql."

        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id) 
         ".$sqlinnerBeneficiarioId."
         LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)
            LEFT OUTER JOIN it_ticket_enc_info1 itei1 ON (usuario_perfil.perfil_id != 'USUONLINE' AND itei1.tipo = 'USUARIORELACIONADO' AND it_ticket_enc.ticket_id = itei1.ticket_id) /*Vinculo a cliente auténtico en apuestas hechas mediante PVenta */".$sqlinnerConcesionario . $where. " order by " . $sidx . " " . $sord ;

        }

        $timeNow=time();

        $sqlQuery = new SqlQuery($sql);

        if($withCount){
            if($_ENV["debugFixed2"] == '1'){
                print_r($sql);
                $count = $this->execute2($sqlQuery);
                print_r(' TIME ');
                print_r($timeNow - time());
                $timeNow=time();

            }else {

                $count = $this->execute2($sqlQuery);
            }
        }




        $forcetime_dimension = "";

        if($forceTimeDimension){
            $forcetime_dimension = "force index (day_dimension_timestrc_timestampint_index)";

        }


        $typeDimensionSql="inner join day_dimension ".$forcetime_dimension."
                    on (day_dimension.timestrc = ".$daydimensionFecha.")";
        $typeDimensionSql='';
        if($typeDimension==4){
            if($forceTimeDimension){
                $forcetime_dimension = "force index (time_dimension4_dbdate_dbdate1_index)";

            }
            if($hourdimensionFecha != ''){
                $typeDimensionSql="inner join time_dimension4  ".$forcetime_dimension."
                    on (time_dimension4.datestr = ".$daydimensionFecha."  and hour2str =".$hourdimensionFecha.")";

            }else{
                $typeDimensionSql="inner join time_dimension4  ".$forcetime_dimension."
                    on (time_dimension4.datestr = ".$daydimensionFecha."  )";

            }
            //$typeDimensionSql='';

        }if($typeDimension==5){
        if($forceTimeDimension){
            $forcetime_dimension = "force index (time_dimension5_dbdate_dbdate1_index)";

        }
        if($hourdimensionFecha != ''){
            $typeDimensionSql="inner join time_dimension5  ".$forcetime_dimension."
                    on (time_dimension5.datestr = ".$daydimensionFecha."  and hour2str =".$hourdimensionFecha.")";

        }else{
            $typeDimensionSql="inner join time_dimension5  ".$forcetime_dimension."
                    on (time_dimension5.datestr = ".$daydimensionFecha."  )";

        }

    }
        if($isInnerConce) {

            if($innConcesionario){
                $sqlinnerConcesionario = "  INNER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') ";
            }

            // " order by " . $sidx . " " . $sord .
            $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */ " . $select . " FROM it_ticket_enc  
                     " . $typeDimensionSql . "

        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) ".$sqlinnerBeneficiarioId." LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id)   LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)   
        LEFT OUTER JOIN it_ticket_enc_info1 itei1 ON (usuario_perfil.perfil_id != 'USUONLINE' AND itei1.tipo = 'USUARIORELACIONADO' AND it_ticket_enc.ticket_id = itei1.ticket_id) /*Vinculo a cliente auténtico en apuestas hechas mediante PVenta */ ".$sqlinnerConcesionario . $where . " order by " . $sidx . " " . $sord . " " . " LIMIT " . $start . " , " . $limit;

        }else{
            if($innConcesionario){
                $sqlinnerConcesionario = "  LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') ";
            }


            // " order by " . $sidx . " " . $sord .
            $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */  " . $select . " FROM it_ticket_enc  
                     " . $typeDimensionSql . "

        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) ".$sqlinnerBeneficiarioId." LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id)   LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)
         LEFT OUTER JOIN it_ticket_enc_info1 itei1 ON (usuario_perfil.perfil_id != 'USUONLINE' AND itei1.tipo = 'USUARIORELACIONADO' AND it_ticket_enc.ticket_id = itei1.ticket_id) /*Vinculo a cliente auténtico en apuestas hechas mediante PVenta */".$sqlinnerConcesionario . $where . " order by " . $sidx . " " . $sord . " " . " LIMIT " . $start . " , " . $limit;

        }


        $sqlQuery = new SqlQuery($sql);

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
            exit();
            $result = $Helpers->process_data($this->execute2($sqlQuery));
            print_r(' TIME ');
            print_r($timeNow - time());
            $timeNow=time();

        }else{
            if (strpos($select, "it_ticket_enc.clave") !== false){
                $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
                $this->execute2($sqlQuery2);
            }
            $result = $Helpers->process_data($this->execute2($sqlQuery));

        }




        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
        $this->execute2($sqlQuery2);
        return $json;
    }



    /**
     * Consulta personalizada de tickets con múltiples opciones de filtrado, agrupación y ordenación.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenarán los resultados.
     * @param string $sord Orden de los resultados (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de resultados para la paginación.
     * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campos por los cuales se agruparán los resultados.
     * @param string $having Filtros en formato JSON para construir la cláusula HAVING.
     * @param bool $withCount Indica si se debe incluir el conteo de resultados.
     * @param int $daydimensionFechaP Dimensión de fecha a utilizar (0: fecha_crea, 1: fecha_pago, 2: fecha_cierre, 3: fecha_maxpago).
     * @param bool $forceTimeDimension Indica si se debe forzar el uso de la dimensión de tiempo.
     * @param int $typeDimension Tipo de dimensión de tiempo a utilizar (4 o 5).
     * @param bool $isInnerConce Indica si se debe usar INNER JOIN con la tabla concesionario.
     * @param array $ArrayTransaction Array de IDs de transacciones a excluir de los resultados.
     * @return string JSON con el conteo de resultados y los datos obtenidos.
     */
    public function queryTicketsCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "",$withCount=true,$daydimensionFechaP=0,$forceTimeDimension=false,$typeDimension=0,$isInnerConce=false, $ArrayTransaction=array())
    {


        $where = " where 1=1 ";

        $innConcesionario=false;

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

                $cond="concesionario";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innConcesionario=true;
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
                    case "bt":
                        $fieldOperation = " BETWEEN " . $fieldData . " ";
                        break;
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "nisnull":
                        $fieldOperation = " NOT IS NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if ($having != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
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
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "nisnull":
                        $fieldOperation = " NOT IS NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (count($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        $daydimensionFecha = "it_ticket_enc.fecha_crea";
        $hourdimensionFecha = "it_ticket_enc.hora_crea";

        if($daydimensionFechaP == 1){
            $daydimensionFecha = "it_ticket_enc.fecha_pago";
            $hourdimensionFecha = "it_ticket_enc.hora_pago";

        }

        if($daydimensionFechaP == 2){
            $daydimensionFecha = "it_ticket_enc.fecha_cierre";
            $hourdimensionFecha = "it_ticket_enc.hora_cierre";

        }

        if($daydimensionFechaP == 3){
            $daydimensionFecha = "it_ticket_enc.fecha_maxpago";
        }

        $typeDimensionSql="inner join day_dimension force index (day_dimension_timestrc_timestampint_index)
                    on (day_dimension.timestrc = ".$daydimensionFecha.")";
        $typeDimensionSql='';
        if($typeDimension==4){
            if($hourdimensionFecha != ''){
                $typeDimensionSql="inner join time_dimension4  
                    on (time_dimension4.datestr = ".$daydimensionFecha." and hour2str =".$hourdimensionFecha.")";

            }else{
                $typeDimensionSql="inner join time_dimension4 
                    on (time_dimension4.datestr = ".$daydimensionFecha." )";

            }
            //$typeDimensionSql='';

        }if($typeDimension==5){
        if($hourdimensionFecha != ''){
            $typeDimensionSql="inner join time_dimension5  force index (time_dimension5_dbdate_dbdate1_index)
                    on (time_dimension5.datestr = ".$daydimensionFecha." and hour2str =".$hourdimensionFecha.")";

        }else{
            $typeDimensionSql="inner join time_dimension5  force index (time_dimension5_dbdate_dbdate1_index)
                    on (time_dimension5.datestr = ".$daydimensionFecha." )";

        }

    }

        $sqlinnerConcesionario = '' ;


        if($isInnerConce){
            if($innConcesionario){
                $sqlinnerConcesionario = "  INNER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') ";
            }
            $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */   count(*) count FROM it_ticket_enc  

             ".$typeDimensionSql."

        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)  ".$sqlinnerConcesionario . $where;

        }else{
            if($innConcesionario){
                $sqlinnerConcesionario = "  LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') ";
            }

            $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */   count(*) count FROM it_ticket_enc  

             ".$typeDimensionSql."

        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)  ".$sqlinnerConcesionario . $where."AND it_ticket_enc.ticket_id  NOT IN (" . implode(',',$ArrayTransaction) . ")". " order by " . $sidx . " " . $sord ;

        }

        $timeNow=time();

        $sqlQuery = new SqlQuery($sql);

        if($withCount){
            if($_ENV["debugFixed2"] == '1'){
                print_r($sql);
                $count = $this->execute2($sqlQuery);
                print_r(' TIME ');
                print_r($timeNow - time());
                $timeNow=time();

            }else {

                $count = $this->execute2($sqlQuery);
            }
        }




        $forcetime_dimension = "";

        if($forceTimeDimension){
            $forcetime_dimension = "force index (day_dimension_timestrc_timestampint_index)";

        }


        $typeDimensionSql="inner join day_dimension ".$forcetime_dimension."
                    on (day_dimension.timestrc = ".$daydimensionFecha.")";
        $typeDimensionSql='';
        if($typeDimension==4){
            if($forceTimeDimension){
                $forcetime_dimension = "force index (time_dimension4_dbdate_dbdate1_index)";

            }
            if($hourdimensionFecha != ''){
                $typeDimensionSql="inner join time_dimension4  ".$forcetime_dimension."
                    on (time_dimension4.datestr = ".$daydimensionFecha."  and hour2str =".$hourdimensionFecha.")";

            }else{
                $typeDimensionSql="inner join time_dimension4  ".$forcetime_dimension."
                    on (time_dimension4.datestr = ".$daydimensionFecha."  )";

            }
            //$typeDimensionSql='';

        }if($typeDimension==5){
        if($forceTimeDimension){
            $forcetime_dimension = "force index (time_dimension5_dbdate_dbdate1_index)";

        }
        if($hourdimensionFecha != ''){
            $typeDimensionSql="inner join time_dimension5  ".$forcetime_dimension."
                    on (time_dimension5.datestr = ".$daydimensionFecha."  and hour2str =".$hourdimensionFecha.")";

        }else{
            $typeDimensionSql="inner join time_dimension5  ".$forcetime_dimension."
                    on (time_dimension5.datestr = ".$daydimensionFecha."  )";

        }

    }
        if($isInnerConce) {

            if($innConcesionario){
                $sqlinnerConcesionario = "  INNER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') ";
            }


            // " order by " . $sidx . " " . $sord .
            $sql = "SELECT " . $select . " FROM it_ticket_enc  
                     " . $typeDimensionSql . "

        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id)   LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)   ".$sqlinnerConcesionario . $where . " order by " . $sidx . " " . $sord . " " . " LIMIT " . $start . " , " . $limit;

        }else{
            if($innConcesionario){
                $sqlinnerConcesionario = "  LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') ";
            }


            // " order by " . $sidx . " " . $sord .
            $sql = "SELECT " . $select . " FROM it_ticket_enc  
                     " . $typeDimensionSql . "

        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id)   LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id) ".$sqlinnerConcesionario . $where ."AND it_ticket_enc.ticket_id  NOT IN (" . implode(',',$ArrayTransaction) . ")". " order by " . $sidx . " " . $sord . " " . " LIMIT " . $start . " , " . $limit;

        }

        $sqlQuery = new SqlQuery($sql);

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
            exit();
            $result = $this->execute2($sqlQuery);
            print_r(' TIME ');
            print_r($timeNow - time());
            $timeNow=time();

        }else{
            $result = $this->execute2($sqlQuery);

        }




        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Consulta personalizada de tickets con automatización.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Inicio del límite de resultados.
     * @param int $limit Cantidad de resultados a devolver.
     * @param string $filters Filtros en formato JSON para la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Campos para agrupar los resultados.
     * @param string $having Condiciones HAVING en formato JSON para la consulta.
     * @return string JSON con el conteo y los datos de los resultados.
     */
    public function queryTicketsCustomAutomation($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "")
    {

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $where = " WHERE 1=1 ";


        $relationship = "";
        $relationship .= " INNER JOIN usuario ON (it_ticket_enc.usuario_id=usuario.usuario_id)";

        $relationshipArray = [];
        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $count = 0;

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
                    case "bt":
                        $fieldOperation = " BETWEEN " . $fieldData . " ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }

                $result = $ConfigurationEnvironment->getRelationship(explode(".", $rule->field)[0], $relationship, $relationshipArray);

                $relationship = $result['relationship'];
                $relationshipArray = $result['relationshipArray'];
            }
        }

        if ($having != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
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
                if (count($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        // $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */   count(*) count FROM it_ticket_enc  INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)  LEFT OUTER JOIN concesionario ON (usuario.usuario_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A') " . $where;
        // $sqlQuery = new SqlQuery($sql);
        // $count = $this->execute2($sqlQuery);

        // " order by " . $sidx . " " . $sord .

        $relationship = $ConfigurationEnvironment->getRelationshipSelect($select, $relationship, $relationshipArray);

        $sql = "SELECT " . $select . " FROM it_ticket_enc ". $relationship . $where . " " . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de IntTicketEnc 'IntTicketEnc'
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
     * @param String $having condición para agrupar
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryTicketsCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "", $withNull = false)
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if ($withNull) {
            $where = $where . " OR (it_ticket_enc.usuario_id is not null OR it_ticket_enc.usuario_id IS NULL) ";
            $where = $where . " OR ((((it_ticket_enc.eliminado ))) IS NULL AND (((it_ticket_enc.fecha_crea ))) IS NULL) ";
        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        if ($having != "") {
            $where = $where . " HAVING " . $having;
            $count = array();
        } else {
            $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */   count(*) count FROM it_ticket_enc   RIGHT JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)   INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)   INNER JOIN usuario_mandante ON (usuario.usuario_id = usuario_mandante.usuario_mandante)  LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id and prodinterno_id=0 AND concesionario.estado='A') " . $where;


            $sqlQuery = new SqlQuery($sql);
            $count = $this->execute2($sqlQuery);

        }


        $sql = "SELECT " . $select . " FROM it_ticket_enc   RIGHT JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)   INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)   INNER JOIN usuario_mandante ON (usuario.usuario_id = usuario_mandante.usuario_mandante)  LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id and prodinterno_id=0 AND concesionario.estado='A')  " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);


        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de IntTicketEnc 'IntTicketEnc'
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
     * @param String $having condición para agrupar
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryGGRCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "")
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        $sql = "SELECT " . $select . "
FROM (
SELECT it_ticket_enc.usuario_id,SUM(vlr_apuesta) apuestas, 0 premios, date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha 
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       " . $where . "

       UNION
SELECT it_ticket_enc.usuario_id,0 apuestas, SUM(vlr_premio) premios, date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha 
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
" . $where . "
) c GROUP BY usuario_id " . " HAVING " . $having;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{  "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de IntTicketEnc 'IntTicketEnc'
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
    public function queryTicketDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping)
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . ")) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        $sql = "SELECT count(*) count FROM it_ticket_det INNER JOIN it_ticket_enc ON (it_ticket_det.ticket_id = it_ticket_enc.ticket_id)  INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id) LEFT OUTER JOIN concesionario ON ( usuario.puntoventa_id = concesionario.usuhijo_id and prodinterno_id=0  AND concesionario.estado='A') " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM it_ticket_det INNER JOIN it_ticket_enc ON (it_ticket_det.ticket_id = it_ticket_enc.ticket_id)   INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id) LEFT OUTER JOIN concesionario ON ( usuario.puntoventa_id = concesionario.usuhijo_id and prodinterno_id=0 AND concesionario.estado='A')   " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if ($_ENV["debugFixed2"] == '1') {
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de IntTicketEnc 'IntTicketEnc'
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
     * @param String $having condición para agrupar
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryTicketTransactionsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $having = "")
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        $orderBy='';

        if($sidx != ''){
            $orderBy= " order by " . $sidx . " " . $sord;

        }


        $sql = "SELECT count(*) count FROM it_transaccion INNER JOIN it_ticket_enc ON (it_transaccion.ticket_id = it_ticket_enc.ticket_id)  INNER JOIN usuario ON (it_transaccion.usuario_id = usuario.usuario_id) " . $where;


        $sqlQuery = new SqlQuery($sql);
        if ($_ENV["debugFixes2"] == '1') {
            print_r($sql);
        }else {
            $count = $this->execute2($sqlQuery);
        }
        $sql = "SELECT " . $select . " FROM it_transaccion INNER JOIN it_ticket_enc ON (it_transaccion.ticket_id = it_ticket_enc.ticket_id)  INNER JOIN usuario ON (it_transaccion.usuario_id = usuario.usuario_id) " . $where . " "  . $orderBy . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);
        if ($_ENV["debugFixed2"] == '1') {
            print_r($sql);
        }else {
            $result = $this->execute2($sqlQuery);
        }
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna transaccion_id sea igual al valor pasado como parámetro
     *
     * @param String $value transaccion_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTransaccionId($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE transaccion_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTicketId($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE ticket_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_apuesta sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_apuesta requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByVlrApuesta($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE vlr_apuesta = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_premio sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_premio requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByVlrPremio($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE vlr_premio = ?';
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
        $sql = 'DELETE FROM it_ticket_enc WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna game_reference sea igual al valor pasado como parámetro
     *
     * @param String $value game_reference requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByGameReference($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE game_reference = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna bet_status sea igual al valor pasado como parámetro
     *
     * @param String $value bet_status requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByBetStatus($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE bet_status = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna cant_lineas sea igual al valor pasado como parámetro
     *
     * @param String $value cant_lineas requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByCantLineas($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE cant_lineas = ?';
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
        $sql = 'DELETE FROM it_ticket_enc WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_crea sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByHoraCrea($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE hora_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premiado sea igual al valor pasado como parámetro
     *
     * @param String $value premiado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByPremiado($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE premiado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_pagado sea igual al valor pasado como parámetro
     *
     * @param String $value premio_pagado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByPremioPagado($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE premio_pagado = ?';
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
        $sql = 'DELETE FROM it_ticket_enc WHERE fecha_pago = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_pago sea igual al valor pasado como parámetro
     *
     * @param String $value hora_pago requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByHoraPago($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE hora_pago = ?';
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
        $sql = 'DELETE FROM it_ticket_enc WHERE mandante = ?';
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
        $sql = 'DELETE FROM it_ticket_enc WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna eliminado sea igual al valor pasado como parámetro
     *
     * @param String $value eliminado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByEliminado($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE eliminado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_maxpago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_maxpago requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFechaMaxpago($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE fecha_maxpago = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cierre requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFechaCierre($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE fecha_cierre = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value hora_cierre requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByHoraCierre($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE hora_cierre = ?';
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
        $sql = 'DELETE FROM it_ticket_enc WHERE clave = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodifica_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodifica_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByUsumodificaId($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE usumodifica_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modifica sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modifica requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFechaModifica($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE fecha_modifica = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna beneficiario_id sea igual al valor pasado como parámetro
     *
     * @param String $value beneficiario_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByBeneficiarioId($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE beneficiario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo_beneficiario sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_beneficiario requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTipoBeneficiario($value)
    {
        $sql = 'DELETE FROM it_ticket_enc WHERE tipo_beneficiario = ?';
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
        $sql = 'DELETE FROM it_ticket_enc WHERE dir_ip = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo ItTicketEnc
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ItTicketEnc ItTicketEnc
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $itTicketEnc = new ItTicketEnc();

        $itTicketEnc->itTicketId = $row['it_ticket_id'];
        $itTicketEnc->transaccionId = $row['transaccion_id'];
        $itTicketEnc->ticketId = $row['ticket_id'];
        $itTicketEnc->vlrApuesta = $row['vlr_apuesta'];
        $itTicketEnc->impuestoApuesta = $row['impuesto_apuesta'];
        $itTicketEnc->vlrPremio = $row['vlr_premio'];
        $itTicketEnc->usuarioId = $row['usuario_id'];
        $itTicketEnc->gameReference = $row['game_reference'];
        $itTicketEnc->betStatus = $row['bet_status'];
        $itTicketEnc->cantLineas = $row['cant_lineas'];
        $itTicketEnc->fechaCrea = $row['fecha_crea'];
        $itTicketEnc->horaCrea = $row['hora_crea'];
        $itTicketEnc->fechaCreaTime = $row['fecha_crea_time'];
        $itTicketEnc->premiado = $row['premiado'];
        $itTicketEnc->premioPagado = $row['premio_pagado'];
        $itTicketEnc->fechaPago = $row['fecha_pago'];
        $itTicketEnc->horaPago = $row['hora_pago'];
        $itTicketEnc->mandante = $row['mandante'];
        $itTicketEnc->estado = $row['estado'];
        $itTicketEnc->eliminado = $row['eliminado'];
        $itTicketEnc->fechaMaxpago = $row['fecha_maxpago'];
        $itTicketEnc->fechaCierre = $row['fecha_cierre'];
        $itTicketEnc->horaCierre = $row['hora_cierre'];
        $itTicketEnc->clave = $row['clave'];
        $itTicketEnc->usumodificaId = $row['usumodifica_id'];
        $itTicketEnc->fechaModifica = $row['fecha_modifica'];
        $itTicketEnc->beneficiarioId = $row['beneficiario_id'];
        $itTicketEnc->tipoBeneficiario = $row['tipo_beneficiario'];
        $itTicketEnc->impuesto = $row['impuesto'];
        $itTicketEnc->dirIp = $row['dir_ip'];
        $itTicketEnc->saldoCreditos = $row['saldo_creditos'];
        $itTicketEnc->saldoCreditosBase = $row['saldo_creditos_base'];
        $itTicketEnc->saldoBonos = $row['saldo_bonos'];
        $itTicketEnc->saldoFree = $row['saldo_free'];
        $itTicketEnc->freebet = $row['freebet'];

        return $itTicketEnc;
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
        for ($i = 0; $i < count($tab); $i++) {
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
        if (count($tab) == 0) {
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


    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
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
