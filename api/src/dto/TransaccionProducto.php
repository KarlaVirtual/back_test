<?php

namespace Backend\dto;

use Exception;
use Backend\dto\Registro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioRecarga;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;


/**
 * Clase 'TransaccionProducto'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'TransaccionProducto'
 *
 * Ejemplo de uso:
 * $TransaccionProducto = new TransaccionProducto();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 */
class TransaccionProducto
{

    /**
     * Representación de la columna 'transproductoId' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $transproductoId;

    /**
     * Representación de la columna 'productoId' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $productoId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'valor' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'impuesto' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $impuesto;

    /**
     * Representación de la columna 'comision' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $comision;

    /**
     * Representación de la columna 'estado' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'tipo' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'externoId' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $externoId;

    /**
     * Representación de la columna 'estadoProducto' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $estadoProducto;

    /**
     * Representación de la columna 'mandante' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'finalId' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $finalId;

    /**
     * Representación de la columna 'finalId' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $usutarjetacredId;

    /**
     * Representación de la columna 'usubancoId' de la tabla 'TransaccionProducto'
     *
     * @var string
     */
    var $usubancoId;

    /**
     * Constructor de clase
     *
     *
     * @param string $transproductoId transproductoId
     * @param string $externoId externoId
     * @param string $productoId productoId
     * @param string $usutarjetacredId usutarjetacredId
     *
     * @return void
     * @throws no
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($transproductoId = "", $externoId = "", $productoId = "", $usutarjetacredId = "")
    {
        if ($transproductoId != "") {

            $this->transproductoId = $transproductoId;

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();

            $TransaccionProducto = $TransaccionProductoMySqlDAO->load($transproductoId);

            if ($TransaccionProducto != null && $TransaccionProducto != "") {
                $this->productoId = $TransaccionProducto->productoId;
                $this->usuarioId = $TransaccionProducto->usuarioId;
                $this->valor = $TransaccionProducto->valor;
                $this->impuesto = $TransaccionProducto->impuesto;
                $this->comision = $TransaccionProducto->comision;
                $this->estado = $TransaccionProducto->estado;
                $this->tipo = $TransaccionProducto->tipo;
                $this->externoId = $TransaccionProducto->externoId;
                $this->estadoProducto = $TransaccionProducto->estadoProducto;
                $this->mandante = $TransaccionProducto->mandante;
                $this->finalId = $TransaccionProducto->finalId;
                $this->usutarjetacredId  = $TransaccionProducto->usutarjetacredId;
                $this->usubancoId  = $TransaccionProducto->usubancoId;
            } else {
                throw new Exception("No existe " . get_class($this), "103");
            }
        } elseif ($externoId != "" && $productoId != "") {

            $this->externoId = $externoId;
            $this->productoId = $productoId;

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();

            $TransaccionProducto = $TransaccionProductoMySqlDAO->queryByExternoIdAndProductoId($externoId, $productoId);
            $TransaccionProducto = $TransaccionProducto[0];

            if ($TransaccionProducto != null && $TransaccionProducto != "") {
                $this->transproductoId = $TransaccionProducto->transproductoId;
                $this->productoId = $TransaccionProducto->productoId;
                $this->usuarioId = $TransaccionProducto->usuarioId;
                $this->valor = $TransaccionProducto->valor;
                $this->impuesto = $TransaccionProducto->impuesto;
                $this->comision = $TransaccionProducto->comision;
                $this->estado = $TransaccionProducto->estado;
                $this->tipo = $TransaccionProducto->tipo;
                $this->externoId = $TransaccionProducto->externoId;
                $this->estadoProducto = $TransaccionProducto->estadoProducto;
                $this->mandante = $TransaccionProducto->mandante;
                $this->finalId = $TransaccionProducto->finalId;
                $this->usutarjetacredId  = $TransaccionProducto->usutarjetacredId;
                $this->usubancoId  = $TransaccionProducto->usubancoId;
            } else {
                throw new Exception("No existe " . get_class($this), "103");
            }
        } elseif ($usutarjetacredId != "") {

            $this->usutarjetacredId = $usutarjetacredId;

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();

            $TransaccionProducto = $TransaccionProductoMySqlDAO->queryByTarjetaId($usutarjetacredId);
            $TransaccionProducto = $TransaccionProducto[0];

            if ($TransaccionProducto != null && $TransaccionProducto != "") {
                $this->transproductoId = $TransaccionProducto->transproductoId;
                $this->productoId = $TransaccionProducto->productoId;
                $this->usuarioId = $TransaccionProducto->usuarioId;
                $this->valor = $TransaccionProducto->valor;
                $this->impuesto = $TransaccionProducto->impuesto;
                $this->comision = $TransaccionProducto->comision;
                $this->estado = $TransaccionProducto->estado;
                $this->tipo = $TransaccionProducto->tipo;
                $this->externoId = $TransaccionProducto->externoId;
                $this->estadoProducto = $TransaccionProducto->estadoProducto;
                $this->mandante = $TransaccionProducto->mandante;
                $this->finalId = $TransaccionProducto->finalId;
                $this->usutarjetacredId  = $TransaccionProducto->usutarjetacredId;
                $this->usubancoId  = $TransaccionProducto->usubancoId;
            } else {
                throw new Exception("No existe " . get_class($this), "103");
            }
        }
    }



    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return void
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo productoId de un objeto
     *
     * @return String productoId productoId
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Modificar el campo 'productoId' de un objeto
     *
     * @param String $productoId productoId
     *
     * @return void
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return void
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return void
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtener el campo impuesto de un objeto
     *
     * @return String impuesto impuesto
     */
    public function getImpuesto()
    {
        return $this->impuesto;
    }

    /**
     * Modificar el campo 'impuesto' de un objeto
     *
     * @param String $impuesto impuesto
     *
     * @return void
     */
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }

    /**
     * Obtener el campo comision de un objeto
     *
     * @return String comision comision
     */
    public function getComision()
    {
        return $this->comision;
    }

    /**
     * Modificar el campo 'comision' de un objeto
     *
     * @param String $comision comision
     *
     * @return void
     */
    public function setComision($comision)
    {
        $this->comision = $comision;
    }

    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return void
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtener el campo externoId de un objeto
     *
     * @return String transjuegoId externoId
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Modificar el campo 'externoId' de un objeto
     *
     * @param String $externoId externoId
     *
     * @return void
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }

    /**
     * Obtener el campo estadoProducto de un objeto
     *
     * @return String estadoProducto estadoProducto
     */
    public function getEstadoProducto()
    {
        return $this->estadoProducto;
    }

    /**
     * Modificar el campo 'estadoProducto' de un objeto
     *
     * @param String $estadoProducto estadoProducto
     *
     * @return void
     */
    public function setEstadoProducto($estadoProducto)
    {
        $this->estadoProducto = $estadoProducto;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return void
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo finalId de un objeto
     *
     * @return String finalId finalId
     */
    public function getFinalId()
    {
        return $this->finalId;
    }

    /**
     * Modificar el campo 'finalId' de un objeto
     *
     * @param String $finalId finalId
     *
     * @return void
     */
    public function setFinalId($finalId)
    {
        $this->finalId = $finalId;
    }

    /**
     * Obtener el campo 'usutarjetacredId' de un objeto
     *
     * @return string
     */
    public function getUsutarjetacredId()
    {
        return $this->usutarjetacredId;
    }

    /**
     * Asigna el campo 'usutarjetacredId' a un objeto
     * @param string $usutarjetacredId Id vinculada al registro de la tarjeta de crédito de un usuario.
     *
     * @return void
     */
    public function setUsutarjetacredId($usutarjetacredId)
    {
        $this->usutarjetacredId = $usutarjetacredId;
    }

    /**
     * Get representación de la columna 'usubanco_id' de la tabla 'TransaccionProducto'
     * @return  string
     */ 
    public function getUsubancoId()
    {
        return $this->usubancoId;
    }

    /**
     * Set representación de la columna 'usubanco_id' de la tabla 'TransaccionProducto'
     * @param  string  $usubancoId  Representación de la columna 'usubanco_id' de la tabla 'TransaccionProducto'
     * @return  self
     */ 
    public function setUsubancoId(string $usubancoId)
    {
        $this->usubancoId = $usubancoId;
        return $this;
    }

    /**
     * Ejecuta una consulta SQL personalizada utilizando el DAO de TransaccionProducto.
     *
     * @param mixed $transaccion Objeto de transacción o conexión a la base de datos.
     * @param string $sql Consulta SQL a ejecutar.
     * @return mixed Resultado de la consulta en formato objeto.
     */
    public function execQuery($transaccion, $sql)
    {

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($transaccion);
        $return = $TransaccionProductoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;
    }

    /**
     * Actualizar el estado de la transacción el base de datos
     *
     *
     * @param String $transaccion_id id de la transacción
     * @param String $tipo_genera tipo_genera
     * @param String $estado estado
     * @param String $comentario comentario
     * @param String $t_value t_value
     *
     * @return boolean $ resultado de la consulta
     * @throws Exception si la transacción no está activa
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value)
    {

        $respuesta = array(
            "result" => "success",
            "transaccion" => ""
        );

        try {
            if (false) {
                if ($this->estado == "A") {

                    /**
                     * Insertamos El log de la transaccion
                     */

                    $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                    $Transaction = $TransprodLogMysqlDAO->getTransaction();

                    /* Validamos que hubo conexion a la base de datos */

                    if ($Transaction->isIsconnected()) {

                        try {

                            $TransprodLog = new TransprodLog();
                            $TransprodLog->setTransproductoId($transaccion_id);
                            $TransprodLog->setEstado($estado);
                            $TransprodLog->setTipoGenera($tipo_genera);
                            $TransprodLog->setComentario($comentario);
                            $TransprodLog->setTValue($t_value);
                            $TransprodLog->setUsucreaId(0);
                            $TransprodLog->setUsumodifId(0);

                            $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

                            /**
                             * Actualizamos el estado de la transaccion de el producto
                             */

                            $this->setEstadoProducto($estado);

                            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                            $TransaccionProductoMySqlDAO->update($this);

                            /**
                             * COMMIT de la transacción
                             */

                            $Transaction->commit();
                        } catch (Exception $e) {

                            $Transaction->rollback();
                            throw $e;
                        }

                        //$checkValidar = $this->checkValidar($transaccion_id);

                    } else {
                        throw new Exception("No transaccion activa", "11101");
                    }
                    return json_encode($respuesta);
                }
            }
        } catch (Exception $e) {

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Actualizar el estado de la transacción el base de datos
     *
     *
     * @param string $transaccion_id id de la transacción
     * @param string $tipo_genera tipo_genera
     * @param string $estado estado
     * @param string $comentario comentario
     * @param string $t_value t_value
     * @param string $externoId externoId
     * @param float $valor_commission valor de la comisión
     *
     * @return boolean $ resultado de la consulta
     * @throws Exception si la transacción no está activa
     * @throws Exception si el deposito ya fue procesado
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $externoId, $valor_commission = 0)
    {
        $respuesta = array(
            "result" => "success",
            "transaccion" => ""
        );
        try {

            if ($this->estado == "A" || ($this->estado == "I" && $tipo_genera == "M" && $this->estadoProducto == "R") || ($this->estado == "I" && $tipo_genera == "M" && $this->estadoProducto == "A")) {

                /**
                 * Insertamos El log de la transaccion
                 */

                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                $Transaction = $TransprodLogMysqlDAO->getTransaction();

                /* Validamos que hubo conexion a la base de datos */

                if ($Transaction->isIsconnected()) {

                    try {

                        $TransprodLog = new TransprodLog();
                        $TransprodLog->setTransproductoId($transaccion_id);
                        $TransprodLog->setEstado($estado);
                        $TransprodLog->setTipoGenera($tipo_genera);
                        $TransprodLog->setComentario($comentario);
                        $TransprodLog->setTValue($t_value);
                        $TransprodLog->setUsucreaId(0);
                        $TransprodLog->setUsumodifId(0);

                        $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

                        /**
                         * Actualizamos el estado de la transaccion de el producto
                         */

                        $this->setEstadoProducto($estado);

                        if ($externoId != '' && $externoId != '0') {
                            $this->setExternoId($externoId);
                        }

                        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                        $string = " AND (estado_producto='E' OR estado_producto='P')  AND estado='A' ";

                        if ($this->productoId != '24863') {
                            if (($this->estado == "I" || ($this->estadoProducto == "A" && $this->finalId == "0")  || ($this->estadoProducto == "A" && $this->finalId == "")) && $tipo_genera == "M") {
                                $string = "";
                            }
                        }


                        $rowsUpdate = $TransaccionProductoMySqlDAO->update($this, $string);


                        if (($rowsUpdate == null || $rowsUpdate <= 0) && $string != '') {
                            throw new Exception("Error General", "100000");
                        }

                        /**
                         * COMMIT de la transacción
                         */

                        $Transaction->commit();
                    } catch (Exception $e) {

                        $Transaction->rollback();
                        throw $e;
                    }

                    $checkValidar = $this->checkValidar($transaccion_id);

                    /* Si la transaccion no necesita verificacion, entonces le agregamos el saldo al usuario*/
                    if (!$checkValidar) {

                        /*$Consecutivo = new Consecutivo("", "REC", "");
                        $consecutivo_recarga = $Consecutivo->numero;*/

                        if ($transaccion_id == "14120") {
                        }
                        if (true) {
                            if ($transaccion_id == "14120") {
                            }
                            /**
                             * Actualizamos consecutivo Recarga
                             */

                            /* $consecutivo_recarga++;

                            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                            $Consecutivo->setNumero($consecutivo_recarga);


                            $ConsecutivoMySqlDAO->update($Consecutivo);

                            $ConsecutivoMySqlDAO->getTransaction()->commit();*/


                            /**
                             * Proceso de recarga al usuario
                             */

                            $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                            $Transaction = $TransprodLogMysqlDAO->getTransaction();

                            /* Validamos que hubo conexion a la base de datos */

                            if ($Transaction->isIsconnected()) {

                                try {


                                    if ($transaccion_id == "14120") {
                                        print_r($TransprodLog);
                                    }

                                    if ($this->transproductoId == '171393') {
                                        print_r($this);
                                    }

                                    if ($this->transproductoId == '171393') {
                                        print_r($rowsUpdate);
                                    }

                                    if ($this->transproductoId == '171393') {
                                        print_r("PASO2");
                                    }
                                    if ($this->transproductoId == '26427715') {
                                        print_r("PASO2");
                                    }

                                    if ($transaccion_id == "14120") {
                                        print_r($this);
                                    }
                                    $Usuario = new Usuario($this->usuarioId);

                                    if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {

                                        $valoriva = 0;
                                        $valorARecargar = $this->valor;

                                        if ($this->productoId == '10312') {
                                            $valoriva = floatval(floatval($this->valor) * (0.035));
                                            $valorARecargar = floatval(floatval($this->valor) - $valoriva);
                                        }

                                        $UsuarioRecarga = new UsuarioRecarga();
                                        //$UsuarioRecarga->setRecargaId($consecutivo_recarga);
                                        $UsuarioRecarga->setUsuarioId($this->usuarioId);
                                        $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
                                        $UsuarioRecarga->setPuntoventaId(0);
                                        $UsuarioRecarga->setValor($valorARecargar);
                                        $UsuarioRecarga->setImpuesto($this->impuesto);
                                        $UsuarioRecarga->setPorcenRegaloRecarga(0);
                                        $UsuarioRecarga->setDirIp(0);
                                        $UsuarioRecarga->setPromocionalId(0);
                                        $UsuarioRecarga->setValorPromocional(0);
                                        $UsuarioRecarga->setHost(0);
                                        $UsuarioRecarga->setMandante($Usuario->mandante);
                                        $UsuarioRecarga->setPedido(0);
                                        $UsuarioRecarga->setPorcenIva(0);
                                        $UsuarioRecarga->setMediopagoId(0);
                                        $UsuarioRecarga->setValorIva($valoriva);
                                        $UsuarioRecarga->setEstado('A');

                                        $Usuario2 = new Usuario($this->usuarioId);

                                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);


                                        $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);
                                        $consecutivo_recarga = $UsuarioRecarga->recargaId;

                                        $CupoLog = new CupoLog();
                                        $CupoLog->setUsuarioId($Usuario->puntoventaId);
                                        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
                                        $CupoLog->setTipoId('E');
                                        $CupoLog->setValor($valorARecargar);
                                        $CupoLog->setUsucreaId(0);
                                        $CupoLog->setMandante(0);
                                        $CupoLog->setTipocupoId('A');
                                        $CupoLog->setRecargaId($consecutivo_recarga);


                                        $CupoLogMySqlDAO = new CupoLogMySqlDAO($Transaction);

                                        $CupoLogMySqlDAO->insert($CupoLog);

                                        $PuntoVenta = new PuntoVenta('', $Usuario->puntoventaId);
                                        $cant = $PuntoVenta->setBalanceCreditosBase($CupoLog->getValor(), $Transaction);

                                        if ($cant == 0) {
                                            throw new Exception("No tiene saldo para transferir", "111");
                                        }


                                        /**
                                         * Actualizamos el estado de la transaccion de el producto y le agregamos consecutivo_recarga
                                         */

                                        $TransprodLog = new TransprodLog();
                                        $TransprodLog->setTransproductoId($transaccion_id);
                                        $TransprodLog->setEstado("I");
                                        $TransprodLog->setTipoGenera("A");
                                        $TransprodLog->setComentario("Aprobado automaticamente y se genera la recarga " . $consecutivo_recarga);
                                        $TransprodLog->setTValue('');
                                        $TransprodLog->setUsucreaId(0);
                                        $TransprodLog->setUsumodifId(0);


                                        $rowsUpdate = $TransprodLogMysqlDAO->insert($TransprodLog);

                                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                                            throw new Exception("Error General", "100001");
                                        }

                                        $this->setEstadoProducto($estado);
                                        $this->setEstado('I');
                                        $this->setFinalId($consecutivo_recarga);

                                        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                                        $rowsUpdate = $TransaccionProductoMySqlDAO->update($this);

                                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                                            throw new Exception("Error General", "100002");
                                        }

                                        if ($transaccion_id == "14120") {
                                            print_r($UsuarioRecarga);
                                        }
                                        /**
                                         * COMMIT de la transacción
                                         */

                                        $UsuarioHistorial = new UsuarioHistorial();
                                        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
                                        $UsuarioHistorial->setDescripcion('');
                                        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
                                        $UsuarioHistorial->setUsucreaId(0);
                                        $UsuarioHistorial->setUsumodifId(0);
                                        $UsuarioHistorial->setTipo(60);
                                        $UsuarioHistorial->setValor($CupoLog->getValor());
                                        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

                                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

                                        $Transaction->commit();
                                    } else {

                                        if ($transaccion_id == "14120") {
                                            print_r($Usuario);
                                            print_r("VAMOS BIEN");
                                        }
                                        $valoriva = 0;
                                        $valorARecargar = $this->valor;

                                        if ($valor_commission > 0) {
                                            $valoriva = $valor_commission;
                                        }

                                        if ($this->productoId == '10312') {
                                            $valoriva = floatval(floatval($this->valor) * (0.035));
                                            $valorARecargar = floatval(floatval($this->valor) - $valoriva);
                                        }

                                        if ($this->comision > 0) {
                                            $valoriva = $this->comision;
                                        }

                                        if ($Usuario->mandante == "2" && false) {
                                            $Usuario->creditWin($valorARecargar, $Transaction);
                                        } else {
                                            $Usuario->credit($valorARecargar, $Transaction);
                                        }


                                        if ($transaccion_id == "14120") {
                                            print_r($Usuario);
                                        }


                                        $UsuarioRecarga = new UsuarioRecarga();
                                        //$UsuarioRecarga->setRecargaId($consecutivo_recarga);
                                        $UsuarioRecarga->setUsuarioId($this->usuarioId);
                                        $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
                                        $UsuarioRecarga->setPuntoventaId(0);
                                        $UsuarioRecarga->setValor($valorARecargar);
                                        $UsuarioRecarga->setImpuesto($this->impuesto);
                                        $UsuarioRecarga->setPorcenRegaloRecarga(0);
                                        $UsuarioRecarga->setDirIp(0);
                                        $UsuarioRecarga->setPromocionalId(0);
                                        $UsuarioRecarga->setValorPromocional(0);
                                        $UsuarioRecarga->setHost(0);
                                        $UsuarioRecarga->setMandante($Usuario->mandante);
                                        $UsuarioRecarga->setPedido(0);
                                        $UsuarioRecarga->setPorcenIva(0);
                                        $UsuarioRecarga->setMediopagoId(0);
                                        $UsuarioRecarga->setValorIva($valoriva);
                                        $UsuarioRecarga->setEstado('A');

                                        $Registro = new Registro('', $this->usuarioId);
                                        $Usuario2 = new Usuario($this->usuarioId);

                                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);
                                        $CiudadMySqlDAO = new CiudadMySqlDAO();

                                        $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);


                                        $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $this->usuarioId . "'");

                                        $detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];


                                        $detalles = array(
                                            "Depositos" => $detalleDepositos,
                                            "DepositoEfectivo" => false,
                                            "MetodoPago" => $this->productoId,
                                            "ValorDeposito" => $valorARecargar,
                                            "PaisPV" => 0,
                                            "DepartamentoPV" => 0,
                                            "CiudadPV" => 0,
                                            "PuntoVenta" => 0,
                                            "PaisUSER" => $Usuario2->paisId,
                                            "DepartamentoUSER" => $Ciudad->deptoId,
                                            "CiudadUSER" => $Registro->ciudadId,
                                            "MonedaUSER" => $Usuario2->moneda,

                                        );


                                        $BonoInterno = new BonoInterno();
                                        $detalles = json_decode(json_encode($detalles));

                                        if ($Usuario2->mandante != '14' || ($Usuario2->mandante == '14')) {
                                            try {
                                                $respuestaBono = $BonoInterno->agregarBono('2', $this->usuarioId, $Usuario2->mandante, $detalles, $Transaction);
                                            } catch (Exception $e) {
                                                syslog(LOG_WARNING, "ERRORPROVEEDORAPI 3:" .  $e->getCode() . ' - ' . $e->getMessage());
                                            }
                                        }
                                        if ($this->usuarioId == 886) {
                                            // print_r($detalles);
                                            // print_r($respuestaBono);
                                        }

                                        $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);
                                        $consecutivo_recarga = $UsuarioRecarga->recargaId;


                                        /**
                                         * Actualizamos el estado de la transaccion de el producto y le agregamos consecutivo_recarga
                                         */

                                        $TransprodLog = new TransprodLog();
                                        $TransprodLog->setTransproductoId($transaccion_id);
                                        $TransprodLog->setEstado("I");
                                        $TransprodLog->setTipoGenera("A");
                                        $TransprodLog->setComentario("Aprobado automaticamente y se genera la recarga " . $consecutivo_recarga);
                                        $TransprodLog->setTValue('');
                                        $TransprodLog->setUsucreaId(0);
                                        $TransprodLog->setUsumodifId(0);


                                        $rowsUpdate = $TransprodLogMysqlDAO->insert($TransprodLog);

                                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                                            throw new Exception("Error General", "100001");
                                        }

                                        $this->setEstadoProducto($estado);
                                        $this->setEstado('I');
                                        $this->setFinalId($consecutivo_recarga);

                                        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                                        $rowsUpdate = $TransaccionProductoMySqlDAO->update($this);

                                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                                            throw new Exception("Error General", "100002");
                                        }

                                        if ($transaccion_id == "14120") {
                                            print_r($UsuarioRecarga);
                                        }
                                        /**
                                         * COMMIT de la transacción
                                         */

                                        $UsuarioHistorial = new UsuarioHistorial();
                                        $UsuarioHistorial->setUsuarioId($this->usuarioId);
                                        $UsuarioHistorial->setDescripcion('');
                                        $UsuarioHistorial->setMovimiento('E');
                                        $UsuarioHistorial->setUsucreaId(0);
                                        $UsuarioHistorial->setUsumodifId(0);
                                        $UsuarioHistorial->setTipo(10);
                                        $UsuarioHistorial->setValor($valorARecargar);
                                        $UsuarioHistorial->setExternoId($consecutivo_recarga);

                                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                                        if ($Usuario2->fechaPrimerdeposito == "") {
                                            $Usuario2->fechaPrimerdeposito = date('Y-m-d H:i:s');
                                            $Usuario2->montoPrimerdeposito = $valorARecargar;
                                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                            $UsuarioMySqlDAO->update($Usuario2);
                                        }

                                        if ($Usuario2->fechaCrea  >= '2023-03-01 08:00:00' && $Usuario2->mandante == 14 && $Usuario2->contingenciaRetiro == 'A') {
                                            $Usuario2->contingenciaRetiro = 'I';
                                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                            $UsuarioMySqlDAO->update($Usuario2);
                                        }
                                        if ($Usuario2->fechaCrea  >= '2023-04-01 00:00:00' && $Usuario2->mandante == 0 && $Usuario2->paisId == 2 && $Usuario2->contingenciaRetiro == 'A') {
                                            $Usuario2->contingenciaRetiro = 'I';
                                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                            $UsuarioMySqlDAO->update($Usuario2);
                                        }
                                        if ($Usuario2->fechaCrea  >= '2023-05-29 00:00:00' && $Usuario2->mandante == 0 && $Usuario2->paisId == 46 && $Usuario2->contingenciaRetiro == 'A') {
                                            $Usuario2->contingenciaRetiro = 'I';
                                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                            $UsuarioMySqlDAO->update($Usuario2);
                                        }
                                        if ($Usuario2->fechaCrea  >= '2024-01-17 00:00:00' && $Usuario2->mandante == 0 && $Usuario2->paisId == 46 && $Usuario2->contingenciaRetiro == 'A') {
                                            $Usuario2->contingenciaRetiro = 'I';
                                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                            $UsuarioMySqlDAO->update($Usuario2);
                                        }
                                        if ($Usuario2->fechaCrea  >= '2024-01-17 00:00:00' && $Usuario2->mandante == 0 && $Usuario2->paisId == 66 && $Usuario2->contingenciaRetiro == 'A') {
                                            $Usuario2->contingenciaRetiro = 'I';
                                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                            $UsuarioMySqlDAO->update($Usuario2);
                                        }


                                        $Transaction->commit();
                                    }

                                    //PERU
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29154', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }


                                    //DECU
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29156', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }

                                    //ECU
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('33834', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //CL
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29160', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //NIC
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29164', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //BR
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29166', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //GTM
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29170', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //TRI
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29172', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //CR
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29174', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }

                                    //PERU
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29188', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }


                                    //DECU
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29192', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }

                                    //ECU
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29194', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //CL
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29196', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //NIC
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29198', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //BR
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29208', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //GTM
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29204', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //TRI
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29210', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }
                                    //CR
                                    $arrayUsuarios = array();
                                    if (in_array($Usuario->usuarioId, $arrayUsuarios)) {
                                        $respuestaBono2 = $BonoInterno->agregarBonoFree('29206', $this->usuarioId, $Usuario2->mandante, $detalles, true, "", $Transaction);
                                    }


                                    try {

                                        $useragent = $_SERVER['HTTP_USER_AGENT'];
                                        $jsonServer = json_encode($_SERVER);
                                        $serverCodif = base64_encode($jsonServer);


                                        $ismobile = '';

                                        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

                                            $ismobile = '1';
                                        }
                                        //Detect special conditions devices
                                        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                                        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                                        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                                        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                                        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");



                                        //do something with this information
                                        if ($iPod || $iPhone) {
                                            $ismobile = '1';
                                        } else if ($iPad) {
                                            $ismobile = '1';
                                        } else if ($Android) {
                                            $ismobile = '1';
                                        }


                                        //exec("php -f ". __DIR__ ."/../src/integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "DEPOSITOCRM" . " " . $UsuarioRecarga->recargaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");
                                    } catch (Exception $e) {
                                    }

                                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                    //exec("php -f " . __DIR__ . "/../integrations/payment/VerificarSorteo.php " . $UsuarioMandante->usumandanteId  . " " . $consecutivo_recarga . " " . $this->transproductoId . " > /dev/null &");




                                    exec("php -f " . __DIR__ . "/../integrations/payment/ActivacionRuletaMetodosPagos.php " . $UsuarioMandante->paisId  . " " . $UsuarioMandante->usumandanteId . " " . $valorARecargar . " " . 5 . " " . $this->productoId . " " . $consecutivo_recarga . " > /dev/null &");





                                    if ($this->valor >= 1500 && $Usuario->paisId == '173' && false) {
                                        $UsuarioAlerta = new UsuarioAlerta();
                                        $msg = "*Usuario:* " . $this->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $this->valor . " - *Producto:* " . $this->productoId;
                                        $UsuarioAlerta->CheckAlert($this, 45, $this->usuarioId, $msg);
                                    }
                                    if ($this->valor >= 600 && $Usuario->paisId == '66' && false) {
                                        $UsuarioAlerta = new UsuarioAlerta();
                                        $msg = "*Usuario:* " . $this->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $this->valor . " - *Producto:* " . $this->productoId;
                                        $UsuarioAlerta->CheckAlert($this, 45, $this->usuarioId, $msg);
                                    }
                                    if ($this->valor >= 10000 && $Usuario->paisId == '146' && false) {
                                        $UsuarioAlerta = new UsuarioAlerta();
                                        $msg = "*Usuario:* " . $this->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $this->valor . " - *Producto:* " . $this->productoId;
                                        $UsuarioAlerta->CheckAlert($this, 45, $this->usuarioId, $msg);
                                    }

                                    if ($this->valor >= 3500 && $Usuario->paisId == '173') {

                                        try {

                                            $message = "Deposito *Usuario:* " . $this->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $this->valor . " - *Producto:* " . $this->productoId;

                                            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-peru' > /dev/null & ");
                                        } catch (Exception $e) {
                                        }
                                    }

                                    if ($this->valor >= 700 && $Usuario->paisId == '66') {

                                        try {

                                            $message = "Deposito *Usuario:* " . $this->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $this->valor . " - *Producto:* " . $this->productoId;

                                            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-ecuabet' > /dev/null & ");
                                        } catch (Exception $e) {
                                        }
                                    }

                                    if ($this->valor >= 625 && $Usuario->paisId == '60') {

                                        try {

                                            $message = "Deposito *Usuario:* " . $this->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $this->valor . " - *Producto:* " . $this->productoId;

                                            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-costarica' > /dev/null & ");
                                        } catch (Exception $e) {
                                        }
                                    }

                                    try {

                                        $ConfigurationEnvironment = new ConfigurationEnvironment();
                                        if (!$ConfigurationEnvironment->isDevelopment()) {
                                        }

                                        if ($ConfigurationEnvironment->isDevelopment() || $Usuario->mandante == 8 || ($Usuario->mandante == 0 && $Usuario->paisId == 173)) {
                                            exec("php -f " . __DIR__ . "/../integrations/casino" . "/AsignarPuntosLealtad.php " . "DEPOSITO" . " " . $UsuarioRecarga->getRecargaId() . " " . 10 . " > /dev/null &");
                                        }
                                    } catch (Exception $e) {
                                        syslog(LOG_WARNING, "ERRORPROVEEDORAPI :" .  $e->getCode() . ' - ' . $e->getMessage());
                                    }
                                } catch (Exception $e) {
                                    syslog(LOG_WARNING, "ERRORPROVEEDORAPI 2:" .  $e->getCode() . ' - ' . $e->getMessage());

                                    $Transaction->rollback();
                                    throw $e;
                                }
                            }
                        }
                    }

                    try {
                        $Producto = new Producto($this->productoId);
                        $Clasificador = new Clasificador('', 'TEMPDEPOSIT');
                        $Template = new Template('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId,  strtolower($Usuario->idioma));
                        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

                        $title = '';

                        switch ($Usuario->idioma) {
                            case 'EN':
                                $title = 'Transaction confirmation';
                                break;
                            case 'PT':
                                $title = 'Confirmação da transação';
                                break;
                            default:
                                $title = 'Confirmación de transacción';
                                break;
                        }

                        $html = $Template->templateHtml;
                        $html = str_replace('#name#', $UsuarioMandante->nombres, $html);
                        $html = str_replace('#Value#', $valorARecargar, $html);
                        $html = str_replace('#MethodPayment#', $Producto->descripcion, $html);
                        $html = str_replace('#Transaction#', $transaccion_id, $html);

                        $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
                        $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, '', '', $title, '', $title, $html, '', '', '', $Usuario->mandante);
                    } catch (Exception $ex) {
                    }


                    if ($Usuario->test == 'S' && $Usuario->mandante == '0' && $Usuario->paisId == '94') {
                        $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
                        $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                        $mensaje_txt = $Mandante->nombre . ' le informa depósito a su cuenta por ' . $Usuario->moneda . ' ' . $UsuarioRecarga->valor . ' ID del depósito (' . $UsuarioRecarga->recargaId . ')';

                        $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
                        //Envia el mensaje de correo
                        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
                        $cambios = true;
                    }
                } else {
                    throw new Exception("No transaccion activa", "11101");
                }
            } else {
                throw new Exception("Deposito procesad0", "11102");
            }
            return json_encode($respuesta);
        } catch (Exception $e) {

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Actualizar el estado de la transacción el base de datos
     *
     *
     * @param string $transaccion_id id de la transacción
     * @param string $tipo_genera tipo_genera
     * @param string $estado estado
     * @param string $comentario comentario
     * @param string $t_value t_value
     * @param string $externoId id externo
     * @param boolean $enabled si se encuentra habilitado
     *
     * @return boolean $ resultado de la consulta
     * @throws Exception si la transacción no está activa
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $externoId = '', $enabled = false)
    {
        $respuesta = array(
            "result" => "success",
            "transaccion" => ""
        );

        try {
            if ($enabled) {

                if ($this->estado == "A" || ($this->estado == "I" && $tipo_genera == "M" && $this->estadoProducto == "A")) {

                    /**
                     * Insertamos El log de la transaccion
                     */


                    $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                    $Transaction = $TransprodLogMysqlDAO->getTransaction();

                    /* Validamos que hubo conexion a la base de datos */

                    if ($Transaction->isIsconnected()) {

                        try {

                            $seguir = true;

                            if ($this->estadoProducto == "A") {

                                $Usuario = new Usuario($this->usuarioId);
                                $Registro = new Registro("", $this->usuarioId);

                                if ($Registro->getCreditosBase() < $this->valor) {
                                    //  $seguir = false;
                                }
                            }

                            if ($seguir) {

                                $TransprodLog = new TransprodLog();
                                $TransprodLog->setTransproductoId($transaccion_id);
                                $TransprodLog->setEstado($estado);
                                $TransprodLog->setTipoGenera($tipo_genera);
                                $TransprodLog->setComentario($comentario);
                                $TransprodLog->setTValue($t_value);
                                $TransprodLog->setUsucreaId(0);
                                $TransprodLog->setUsumodifId(0);


                                $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);


                                if ($externoId != '' && $externoId != '0') {
                                    $this->setExternoId($externoId);
                                }

                                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                                $TransaccionProductoMySqlDAO->update($this);

                                if ($this->estadoProducto == "A") {

                                    /**
                                     * Actualizamos el estado de la transaccion de el producto
                                     */
                                    $this->setEstadoProducto($estado);

                                    if ($this->finalId != "") {
                                        $UsuarioRecarga = new UsuarioRecarga($this->finalId);
                                        $UsuarioRecarga->setEstado("I");

                                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);
                                        $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);
                                    }
                                    $Usuario->credit(-$this->valor, $Transaction);

                                    $UsuarioHistorial = new UsuarioHistorial();
                                    $UsuarioHistorial->setUsuarioId($this->usuarioId);
                                    $UsuarioHistorial->setMovimiento("S");
                                    $UsuarioHistorial->setTipo(10);
                                    $UsuarioHistorial->setValor($this->valor);
                                    $UsuarioHistorial->setDescripcion("Rechazo Deposito");
                                    $UsuarioHistorial->setExternoId($this->transproductoId);
                                    $UsuarioHistorial->setUsucreaId(0);
                                    $UsuarioHistorial->setUsumodifId(0);

                                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                                    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                                    $TransaccionProductoMySqlDAO->update($this);


                                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                                    $SaldoUsuonlineAjuste->setTipoId('S');
                                    $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                                    $SaldoUsuonlineAjuste->setValor($UsuarioRecarga->getValor());
                                    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                                    $SaldoUsuonlineAjuste->setUsucreaId(0);
                                    $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                                    $SaldoUsuonlineAjuste->setObserv("Reversion recarga " . $UsuarioRecarga->getRecargaId());
                                    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                                        $SaldoUsuonlineAjuste->setMotivoId(0);
                                    }
                                    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                                    $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                                    $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());
                                    $SaldoUsuonlineAjuste->setTipo(10);


                                    $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                                    $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);
                                }

                                if ($this->estadoProducto == "E") {

                                    /**
                                     * Actualizamos el estado de la transaccion de el producto
                                     */
                                    $this->setEstadoProducto($estado);

                                    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                                    $TransaccionProductoMySqlDAO->update($this);
                                }


                                /**
                                 * COMMIT de la transacción
                                 */

                                $Transaction->commit();
                            }
                        } catch (Exception $e) {

                            if ($_ENV['debug']) {
                                print_r($e);
                            }
                            $Transaction->rollback();
                            throw $e;
                        }

                        $checkValidar = $this->checkValidar($transaccion_id);

                        if ($checkValidar) {
                        }
                    } else {
                        throw new Exception("No transaccion activa", "11101");
                    }
                }

                return json_encode($respuesta);
            }
        } catch (Exception $e) {

            if ($_ENV['debug']) {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Rechaza manualmente una transacción de producto.
     *
     * Esta función permite rechazar una transacción de producto de forma manual, registrando el log correspondiente,
     * actualizando el estado de la transacción y realizando los ajustes necesarios en el saldo del usuario y su historial.
     *
     * @param string $transaccion_id ID de la transacción a rechazar.
     * @param string $tipo_genera Tipo de generación del rechazo.
     * @param string $estado Nuevo estado a asignar.
     * @param string $comentario Comentario asociado al rechazo.
     * @param string $t_value Valor adicional para el log.
     * @param string $externoId (Opcional) ID externo relacionado con la transacción.
     * @return string JSON con el resultado de la operación.
     * @throws Exception Si ocurre un error durante el proceso o la transacción no está activa.
     */
    public function setRechazadaManualmente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $externoId = '')
    {
        $respuesta = array(
            "result" => "success",
            "transaccion" => ""
        );

        try {
            if (true) {

                if ($this->estado == "A" || ($this->estado == "I" && $tipo_genera == "M" && $this->estadoProducto == "A")) {

                    /**
                     * Insertamos El log de la transaccion
                     */

                    $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                    $Transaction = $TransprodLogMysqlDAO->getTransaction();

                    /* Validamos que hubo conexion a la base de datos */

                    if ($Transaction->isIsconnected()) {

                        try {

                            $seguir = true;

                            if ($this->estadoProducto == "A") {

                                $Usuario = new Usuario($this->usuarioId);
                                $Registro = new Registro("", $this->usuarioId);

                                if ($Registro->getCreditosBase() < $this->valor) {
                                    //  $seguir = false;
                                }
                            }

                            if ($seguir) {

                                $TransprodLog = new TransprodLog();
                                $TransprodLog->setTransproductoId($transaccion_id);
                                $TransprodLog->setEstado($estado);
                                $TransprodLog->setTipoGenera($tipo_genera);
                                $TransprodLog->setComentario($comentario);
                                $TransprodLog->setTValue($t_value);
                                $TransprodLog->setUsucreaId(0);
                                $TransprodLog->setUsumodifId(0);


                                $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);


                                if ($externoId != '' && $externoId != '0') {
                                    $this->setExternoId($externoId);
                                }

                                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                                $TransaccionProductoMySqlDAO->update($this);

                                if ($this->estadoProducto == "A") {

                                    /**
                                     * Actualizamos el estado de la transaccion de el producto
                                     */
                                    $this->setEstadoProducto($estado);

                                    if ($this->finalId != "") {
                                        $UsuarioRecarga = new UsuarioRecarga($this->finalId);
                                        $UsuarioRecarga->setEstado("I");

                                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);
                                        $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);
                                    }
                                    $Usuario->credit(-$this->valor, $Transaction);

                                    $UsuarioHistorial = new UsuarioHistorial();
                                    $UsuarioHistorial->setUsuarioId($this->usuarioId);
                                    $UsuarioHistorial->setMovimiento("S");
                                    $UsuarioHistorial->setTipo(10);
                                    $UsuarioHistorial->setValor($this->valor);
                                    $UsuarioHistorial->setDescripcion("Rechazo Deposito");
                                    $UsuarioHistorial->setExternoId($this->transproductoId);
                                    $UsuarioHistorial->setUsucreaId(0);
                                    $UsuarioHistorial->setUsumodifId(0);

                                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                                    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                                    $TransaccionProductoMySqlDAO->update($this);


                                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                                    $SaldoUsuonlineAjuste->setTipoId('S');
                                    $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                                    $SaldoUsuonlineAjuste->setValor($UsuarioRecarga->getValor());
                                    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                                    $SaldoUsuonlineAjuste->setUsucreaId(0);
                                    $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                                    $SaldoUsuonlineAjuste->setObserv("Reversion recarga " . $UsuarioRecarga->getRecargaId());
                                    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                                        $SaldoUsuonlineAjuste->setMotivoId(0);
                                    }
                                    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                                    $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                                    $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());
                                    $SaldoUsuonlineAjuste->setTipo(10);


                                    $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                                    $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);
                                }

                                if ($this->estadoProducto == "E") {

                                    /**
                                     * Actualizamos el estado de la transaccion de el producto
                                     */
                                    $this->setEstadoProducto($estado);

                                    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                                    $TransaccionProductoMySqlDAO->update($this);
                                }


                                /**
                                 * COMMIT de la transacción
                                 */

                                $Transaction->commit();
                            }
                        } catch (Exception $e) {

                            if ($_ENV['debug']) {
                                print_r($e);
                            }
                            $Transaction->rollback();
                            throw $e;
                        }

                        $checkValidar = $this->checkValidar($transaccion_id);

                        if ($checkValidar) {
                        }
                    } else {
                        throw new Exception("No transaccion activa", "11101");
                    }
                }

                return json_encode($respuesta);
            }
        } catch (Exception $e) {

            if ($_ENV['debug']) {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Actualizar el estado de la transacción el base de datos
     *
     *
     * @param String $transaccion_id id de la transacción
     *
     * @return boolean $ resultado de la consulta
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    private function checkValidar($transaccion_id)
    {
        $return = true;

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();

        $Complete = $TransaccionProductoMySqlDAO->getComplete($transaccion_id);

        $productoVerifica = $Complete[0]['producto.verifica'];
        $productoMandanteVerifica = $Complete[0]['productoMandante.verifica'];
        $prodmandantePaisVerifica = $Complete[0]['prodmandantePais.verifica'];


        if ($productoVerifica == "I" && ($prodmandantePaisVerifica == "I" || $prodmandantePaisVerifica == "") && $productoMandanteVerifica == "I") {

            $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

            $Transaction = $TransprodLogMysqlDAO->getTransaction();

            if ($Transaction->isIsconnected()) {

                try {

                    $TransprodLog = new TransprodLog();

                    $TransprodLog->setTransproductoId($transaccion_id);
                    $TransprodLog->setEstado("I");
                    $TransprodLog->setTipoGenera("A");
                    $TransprodLog->setComentario("Auto aprobado por el proveedor.");
                    $TransprodLog->setTValue("");
                    $TransprodLog->setUsucreaId(0);
                    $TransprodLog->setUsumodifId(0);


                    $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

                    /**
                     * Actualizamos el estado de la transaccion de el producto
                     */
                    $this->setEstado("I");

                    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

                    $TransaccionProductoMySqlDAO->update($this);

                    /**
                     * COMMIT de la transacción
                     */

                    $Transaction->commit();

                    $return = false;
                } catch (Exception $e) {

                    $Transaction->rollback();
                    throw $e;
                }
            }
        } else {
        }

        return $return;
    }

    /**
     * Generar codigo de error mediante el código y el mensaje
     *
     *
     * @param String $code code
     * @param String $message message
     *
     * @return boolean $ resultado de la consulta
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function convertError($code, $message)
    {
        $respuesta = array(
            "result" => "error",
            "errorId" => $code,
            "message" => $message
        );

        return json_encode($respuesta);
    }

    /**
     * Insertar un registro en la base de datos
     *
     *
     * @param string $transaction id de la transacción
     *
     * @return boolean $ resultado de la consulta
     * @throws no
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function insert($transaction)
    {

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($transaction);

        return $TransaccionProductoMySqlDAO->insert($this);
    }


    /**
     * Realizar una consulta en la tabla de transacciones 'TransaccionProducto'
     * de una manera personalizada
     *
     * @param string $sidx columna para ordenar
     * @param string $sord orden los datos asc | desc
     * @param string $start inicio de la consulta
     * @param string $limit limite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array resultado de la consulta
     * @throws Exception si la transacción no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getTransacciones($sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();

        $Transaccion = $TransaccionProductoMySqlDAO->queryTransacciones($sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Transaccion != null && $Transaccion != "") {
            return $Transaccion;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Realizar una consulta en la tabla de transacciones 'TransaccionProducto'
     * de una manera personalizada
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
     * @throws Exception si las transacciones no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getTransaccionesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = '')
    {

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();

        $transacciones = $TransaccionProductoMySqlDAO->queryTransaccionesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($transacciones != null && $transacciones != "") {
            return $transacciones;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    public function getTransaccionesCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = '')
    {

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();

        $transacciones = $TransaccionProductoMySqlDAO->queryTransaccionesCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($transacciones != null && $transacciones != "") {
            return $transacciones;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }
}