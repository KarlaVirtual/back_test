<?php
namespace Backend\dto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\sql\Transaction;
use Exception;
/**
* Clase 'CuentaCobro'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'CuentaCobro'
*
* Ejemplo de uso:
* $CuentaCobro = new CuentaCobro();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class CuentaCobro
{

    /**
    * Representación de la columna 'cuentaId' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $cuentaId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'valor' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'fechaPago' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $fechaPago;

    /**
    * Representación de la columna 'puntoventaId' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $puntoventaId;

    /**
    * Representación de la columna 'estado' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'clave' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $clave;

    /**
    * Representación de la columna 'mandante' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'dirIp' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $dirIp;

    /**
    * Representación de la columna 'impresa' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $impresa;

    /**
    * Representación de la columna 'mediopagoId' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $mediopagoId;

    /**
    * Representación de la columna 'observacion' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $observacion;

    /**
    * Representación de la columna 'mensajeUsuario' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $mensajeUsuario;

    /**
    * Representación de la columna 'usucambioId' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $usucambioId;

    /**
    * Representación de la columna 'usurechazaId' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $usurechazaId;

    /**
    * Representación de la columna 'usupagoId' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $usupagoId;

    /**
    * Representación de la columna 'fechaCambio' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $fechaCambio;

    /**
    * Representación de la columna 'fechaAccion' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $fechaAccion;

    /**
    * Representación de la columna 'diripAccion' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $diripAccion;

    /**
    * Representación de la columna 'diripCambio' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $diripCambio;

    /**
    * Representación de la columna 'creditos' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $creditos;

    /**
    * Representación de la columna 'creditosBase' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $creditosBase;

    /**
    * Representación de la columna 'impuesto' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $impuesto;

    /**
    * Representación de la columna 'costo' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $costo;

    /**
    * Representación de la columna 'transproductoId' de la tabla 'CuentaCobro'
    *
    * @var string
    */
    var $transproductoId;

    /**
     * Representación de la columna 'version' de la tabla 'version'
     *
     * @var string
     */
    var $version;

    /**
     * @var string Representación de la columna fecha_eliminacion de la tabla 'cuenta_cobro'
     */
    var $fechaEliminacion;

    /**
     * @var float Representación de la columna impuesto2 de la tabla 'cuenta_cobro'
     */
    var $impuesto2;

    /**
     * @var int Representación de la columna producto_pago_id de la tabla 'cuenta_cobro'
     */
    var $productoPagoId;

    /**
     * @var string Representación de la columna factura de la tabla 'cuenta_cobro'
     */
    var $factura;

    /**
     * Representación de la columna 'puntaje_jugador' de la tabla 'version'
     *
     * @var string
     */

    var $puntajeJugador;

    /**
     * Representación de la columna 'transaction' de la tabla 'version'
     *
     * @var string
     */
    public $transaction;

    /**
    * Constructor de clase
    *
    *
    * @param String $cuentaId id de la cuenta
    * @param String $transproductoId transproductoId
    *
    * @return no
    * @throws Exception si la CuentaCobro no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($cuentaId="",$transproductoId="",$clave = "", $transaction = "")
    {
        $this->transaction = $transaction;

        if ($cuentaId != "" && $clave != "") {


            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            $CuentaCobro = $CuentaCobroMySqlDAO->loadByCuentaIdAndClave($cuentaId,$clave);

            if ($CuentaCobro != null && $CuentaCobro != "")
            {

                $this->cuentaId = $CuentaCobro->cuentaId;
                $this->usuarioId=$CuentaCobro->usuarioId;
                $this->fechaCrea=$CuentaCobro->fechaCrea;
                $this->valor=$CuentaCobro->valor;
                $this->fechaPago=$CuentaCobro->fechaPago;
                $this->puntoventaId=$CuentaCobro->puntoventaId;
                $this->estado=$CuentaCobro->estado;
                $this->clave=$CuentaCobro->clave;
                $this->mandante=$CuentaCobro->mandante;
                $this->dirIp=$CuentaCobro->dirIp;
                $this->impresa=$CuentaCobro->impresa;
                $this->mediopagoId=$CuentaCobro->mediopagoId;
                $this->observacion=$CuentaCobro->observacion;
                $this->mensajeUsuario=$CuentaCobro->mensajeUsuario;
                $this->usucambioId=$CuentaCobro->usucambioId;
                $this->fechaCambio=$CuentaCobro->fechaCambio;
                $this->fechaAccion=$CuentaCobro->fechaAccion;
                $this->diripAccion=$CuentaCobro->diripAccion;
                $this->diripCambio=$CuentaCobro->diripCambio;
                $this->creditos=$CuentaCobro->creditos;
                $this->creditosBase=$CuentaCobro->creditosBase;
                $this->impuesto=$CuentaCobro->impuesto;
                $this->costo=$CuentaCobro->costo;
                $this->transproductoId=$CuentaCobro->transproductoId;

                $this->usupagoId=$CuentaCobro->usupagoId;
                $this->usurechazaId=$CuentaCobro->usurechazaId;

                $this->version=$CuentaCobro->version;
                $this->impuesto2=$CuentaCobro->impuesto2;
                $this->productoPagoId=$CuentaCobro->productoPagoId;
                $this->factura = $CuentaCobro->factura;
                $this->puntajeJugador = $CuentaCobro->puntajeJugador;
                if($this->creditos == "")
                {
                    $this->creditos=0;
                }

                if($this->creditosBase == "")
                {
                    $this->creditosBase=0;
                }

                if($this->impuesto == "")
                {
                    $this->impuesto=0;
                }
                if($this->costo == "")
                {
                    $this->costo=0;
                }

                if($this->impuesto2 == "")
                {
                    $this->impuesto2=0;
                }
                if($this->productoPagoId == "")
                {
                    $this->productoPagoId=0;
                }

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "12");

            }


        }
        else if ($transproductoId != "")
        {

            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            $CuentaCobro = $CuentaCobroMySqlDAO->loadByTransproductoId($transproductoId);

            if ($CuentaCobro != null && $CuentaCobro != "")
            {

                $this->cuentaId = $CuentaCobro->cuentaId;
                $this->usuarioId=$CuentaCobro->usuarioId;
                $this->fechaCrea=$CuentaCobro->fechaCrea;
                $this->valor=$CuentaCobro->valor;
                $this->fechaPago=$CuentaCobro->fechaPago;
                $this->puntoventaId=$CuentaCobro->puntoventaId;
                $this->estado=$CuentaCobro->estado;
                $this->clave=$CuentaCobro->clave;
                $this->mandante=$CuentaCobro->mandante;
                $this->dirIp=$CuentaCobro->dirIp;
                $this->impresa=$CuentaCobro->impresa;
                $this->mediopagoId=$CuentaCobro->mediopagoId;
                $this->observacion=$CuentaCobro->observacion;
                $this->mensajeUsuario=$CuentaCobro->mensajeUsuario;
                $this->usucambioId=$CuentaCobro->usucambioId;
                $this->fechaCambio=$CuentaCobro->fechaCambio;
                $this->fechaAccion=$CuentaCobro->fechaAccion;
                $this->diripAccion=$CuentaCobro->diripAccion;
                $this->diripCambio=$CuentaCobro->diripCambio;
                $this->creditos=$CuentaCobro->creditos;
                $this->creditosBase=$CuentaCobro->creditosBase;
                $this->impuesto=$CuentaCobro->impuesto;
                $this->costo=$CuentaCobro->costo;
                $this->transproductoId=$CuentaCobro->transproductoId;

                $this->usupagoId=$CuentaCobro->usupagoId;
                $this->usurechazaId=$CuentaCobro->usurechazaId;

                $this->version=$CuentaCobro->version;
                $this->impuesto2=$CuentaCobro->impuesto2;
                $this->productoPagoId=$CuentaCobro->productoPagoId;
                $this->factura = $CuentaCobro->factura;
                $this->puntajeJugador = $CuentaCobro->puntajeJugador;
                if($this->creditos == ""){
                    $this->creditos=0;
                }

                if($this->creditosBase == ""){
                    $this->creditosBase=0;
                }

                if($this->impuesto == ""){
                    $this->impuesto=0;
                }
                if($this->costo == ""){
                    $this->costo=0;
                }
                if($this->impuesto2 == ""){
                    $this->impuesto2=0;
                }

                if($this->productoPagoId == "")
                {
                    $this->productoPagoId=0;
                }
            }else {
                throw new Exception("No existe " . get_class($this), "12");

            }


        }else if ($cuentaId != "") {

            $this->cuentaId = $cuentaId;
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            $CuentaCobro = $CuentaCobroMySqlDAO->load($cuentaId);

            if ($CuentaCobro != null && $CuentaCobro != "") {

                $this->cuentaId = $CuentaCobro->cuentaId;
                $this->usuarioId=$CuentaCobro->usuarioId;
                $this->fechaCrea=$CuentaCobro->fechaCrea;
                $this->valor=$CuentaCobro->valor;
                $this->fechaPago=$CuentaCobro->fechaPago;
                $this->puntoventaId=$CuentaCobro->puntoventaId;
                $this->estado=$CuentaCobro->estado;
                $this->clave=$CuentaCobro->clave;
                $this->mandante=$CuentaCobro->mandante;
                $this->dirIp=$CuentaCobro->dirIp;
                $this->impresa=$CuentaCobro->impresa;
                $this->mediopagoId=$CuentaCobro->mediopagoId;
                $this->observacion=$CuentaCobro->observacion;
                $this->mensajeUsuario=$CuentaCobro->mensajeUsuario;
                $this->usucambioId=$CuentaCobro->usucambioId;
                $this->fechaCambio=$CuentaCobro->fechaCambio;
                $this->fechaAccion=$CuentaCobro->fechaAccion;
                $this->diripAccion=$CuentaCobro->diripAccion;
                $this->diripCambio=$CuentaCobro->diripCambio;
                $this->creditos=$CuentaCobro->creditos;
                $this->creditosBase=$CuentaCobro->creditosBase;
                $this->impuesto=$CuentaCobro->impuesto;
                $this->costo=$CuentaCobro->costo;
                $this->transproductoId=$CuentaCobro->transproductoId;

                $this->usupagoId=$CuentaCobro->usupagoId;
                $this->usurechazaId=$CuentaCobro->usurechazaId;

                $this->version=$CuentaCobro->version;
                $this->impuesto2=$CuentaCobro->impuesto2;
                $this->productoPagoId=$CuentaCobro->productoPagoId;
                $this->factura = $CuentaCobro->factura;
                $this->puntajeJugador = $CuentaCobro->puntajeJugador;
                if($this->creditos == "")
                {
                    $this->creditos=0;
                }

                if($this->creditosBase == "")
                {
                    $this->creditosBase=0;
                }

                if($this->impuesto == "")
                {
                    $this->impuesto=0;
                }
                if($this->costo == "")
                {
                    $this->costo=0;
                }
                if($this->impuesto2 == ""){
                    $this->impuesto2=0;
                }
                if($this->productoPagoId == "")
                {
                    $this->productoPagoId=0;
                }
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "12");

            }


        }


    }








    /**
     * Obtener el campo cuentaId de un objeto
     *
     * @return String cuentaId cuentaId
     *
     */
    public function getCuentaId()
    {
        return $this->cuentaId;
    }

    /**
     * Modificar el campo 'cuentaId' de un objeto
     *
     * @param String $cuentaId cuentaId
     *
     * @return no
     *
     */
    public function setCuentaId($cuentaId)
    {
        $this->cuentaId = $cuentaId;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     *
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
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     *
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     *
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
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtener el campo fechaPago de un objeto
     *
     * @return String fechaPago fechaPago
     *
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Modificar el campo 'fechaPago' de un objeto
     *
     * @param String $fechaPago fechaPago
     *
     * @return no
     *
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;
    }

    /**
     * Obtener el campo puntoventaId de un objeto
     *
     * @return String puntoventaId puntoventaId
     *
     */
    public function getPuntoventaId()
    {
        return $this->puntoventaId;
    }

    /**
     * Modificar el campo 'puntoventaId' de un objeto
     *
     * @param String $puntoventaId puntoventaId
     *
     * @return no
     *
     */
    public function setPuntoventaId($puntoventaId)
    {
        $this->puntoventaId = $puntoventaId;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     *
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
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo clave de un objeto
     *
     * @return String clave clave
     *
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Modificar el campo 'clave' de un objeto
     *
     * @param String $clave clave
     *
     * @return no
     *
     */
    public function setClave($clave)
    {
        $this->clave = $clave;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     *
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
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo dirIp de un objeto
     *
     * @return String dirIp dirIp
     *
     */
    public function getDirIp()
    {
        return $this->dirIp;
    }

    /**
     * Modificar el campo 'dirIp' de un objeto
     *
     * @param String $dirIp dirIp
     *
     * @return no
     *
     */
    public function setDirIp($dirIp)
    {
        $this->dirIp = $dirIp;
    }

    /**
     * Obtener el campo impresa de un objeto
     *
     * @return String impresa impresa
     *
     */
    public function getImpresa()
    {
        return $this->impresa;
    }

    /**
     * Modificar el campo 'impresa' de un objeto
     *
     * @param String $impresa impresa
     *
     * @return no
     *
     */
    public function setImpresa($impresa)
    {
        $this->impresa = $impresa;
    }

    /**
     * Obtener el campo mediopagoId de un objeto
     *
     * @return String mediopagoId mediopagoId
     *
     */
    public function getMediopagoId()
    {
        return $this->mediopagoId;
    }

    /**
     * Modificar el campo 'mediopagoId' de un objeto
     *
     * @param String $mediopagoId mediopagoId
     *
     * @return no
     *
     */
    public function setMediopagoId($mediopagoId)
    {
        $this->mediopagoId = $mediopagoId;
    }

    /**
     * Obtener el campo observacion de un objeto
     *
     * @return String observacion observacion
     *
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Modificar el campo 'observacion' de un objeto
     *
     * @param String $observacion observacion
     *
     * @return no
     *
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    /**
     * Obtener el campo mensajeUsuario de un objeto
     *
     * @return String mensajeUsuario mensajeUsuario
     *
     */
    public function getMensajeUsuario()
    {
        return $this->mensajeUsuario;
    }

    /**
     * Modificar el campo 'mensajeUsuario' de un objeto
     *
     * @param String $mensajeUsuario mensajeUsuario
     *
     * @return no
     *
     */
    public function setMensajeUsuario($mensajeUsuario)
    {
        $this->mensajeUsuario = $mensajeUsuario;
    }

    /**
     * Obtener el campo usucambioId de un objeto
     *
     * @return String usucambioId usucambioId
     *
     */
    public function getUsucambioId()
    {
        return $this->usucambioId;
    }

    /**
     * Modificar el campo 'usucambioId' de un objeto
     *
     * @param String $usucambioId usucambioId
     *
     * @return no
     *
     */
    public function setUsucambioId($usucambioId)
    {
        $this->usucambioId = $usucambioId;
    }

    /**
     * Obtener el campo usurechazaId de un objeto
     *
     * @return String usurechazaId usurechazaId
     *
     */
    public function getUsurechazaId()
    {
        return $this->usurechazaId;
    }

    /**
     * Modificar el campo 'usurechazaId' de un objeto
     *
     * @param String $usurechazaId usurechazaId
     *
     * @return no
     *
     */
    public function setUsurechazaId($usurechazaId)
    {
        $this->usurechazaId = $usurechazaId;
    }

    /**
     * Obtener el campo usupagoId de un objeto
     *
     * @return String usupagoId usupagoId
     *
     */
    public function getUsupagoId()
    {
        return $this->usupagoId;
    }

    /**
     * Modificar el campo 'usupagoId' de un objeto
     *
     * @param String $usupagoId usupagoId
     *
     * @return no
     *
     */
    public function setUsupagoId($usupagoId)
    {
        $this->usupagoId = $usupagoId;
    }

    /**
     * Obtener el campo fechaCambio de un objeto
     *
     * @return String fechaCambio fechaCambio
     *
     */
    public function getFechaCambio()
    {
        return $this->fechaCambio;
    }

    /**
     * Modificar el campo 'fechaCambio' de un objeto
     *
     * @param String $fechaCambio fechaCambio
     *
     * @return no
     *
     */
    public function setFechaCambio($fechaCambio)
    {
        $this->fechaCambio = $fechaCambio;
    }

    /**
     * Obtener el campo fechaAccion de un objeto
     *
     * @return String fechaAccion fechaAccion
     *
     */
    public function getFechaAccion()
    {
        return $this->fechaAccion;
    }

    /**
     * Modificar el campo 'fechaAccion' de un objeto
     *
     * @param String $fechaAccion fechaAccion
     *
     * @return no
     *
     */
    public function setFechaAccion($fechaAccion)
    {
        $this->fechaAccion = $fechaAccion;
    }

    /**
     * Obtener el campo diripAccion de un objeto
     *
     * @return String diripAccion diripAccion
     *
     */
    public function getDiripAccion()
    {
        return $this->diripAccion;
    }

    /**
     * Modificar el campo 'diripAccion' de un objeto
     *
     * @param String $diripAccion diripAccion
     *
     * @return no
     *
     */
    public function setDiripAccion($diripAccion)
    {
        $this->diripAccion = $diripAccion;
    }

    /**
     * Obtener el campo diripCambio de un objeto
     *
     * @return String diripCambio diripCambio
     *
     */
    public function getDiripCambio()
    {
        return $this->diripCambio;
    }

    /**
     * Modificar el campo 'diripCambio' de un objeto
     *
     * @param String $diripCambio diripCambio
     *
     * @return no
     *
     */
    public function setDiripCambio($diripCambio)
    {
        $this->diripCambio = $diripCambio;
    }

    /**
     * Obtener el campo creditos de un objeto
     *
     * @return String creditos creditos
     *
     */
    public function getCreditos()
    {
        return $this->creditos;
    }

    /**
     * Modificar el campo 'creditos' de un objeto
     *
     * @param String $creditos creditos
     *
     * @return no
     *
     */
    public function setCreditos($creditos)
    {
        $this->creditos = $creditos;
    }

    /**
     * Obtener el campo creditosBase de un objeto
     *
     * @return String creditosBase creditosBase
     *
     */
    public function getCreditosBase()
    {
        return $this->creditosBase;
    }

    /**
     * Modificar el campo 'creditosBase' de un objeto
     *
     * @param String $creditosBase creditosBase
     *
     * @return no
     *
     */
    public function setCreditosBase($creditosBase)
    {
        $this->creditosBase = $creditosBase;
    }

    /**
     * Obtener el campo impuesto de un objeto
     *
     * @return String impuesto impuesto
     *
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
     * @return no
     *
     */
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }

    /**
     * Obtener el campo costo de un objeto
     *
     * @return String costo costo
     *
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Modificar el campo 'costo' de un objeto
     *
     * @param String $costo costo
     *
     * @return no
     *
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;
    }

    /**
     * @return int
     */
    public function getImpuesto2()
    {
        return $this->impuesto2;
    }

    /**
     * @param int $impuesto2
     */
    public function setImpuesto2($impuesto2)
    {
        $this->impuesto2 = $impuesto2;
    }

    /**
     * @return int
     */
    public function getProductoPagoId()
    {
        return $this->productoPagoId;
    }

    /**
     * @param int $productoPagoId
     */
    public function setProductoPagoId($productoPagoId)
    {
        $this->productoPagoId = $productoPagoId;
    }

    /**
     * Obtener el campo transproductoId de un objeto
     *
     * @return String transproductoId transproductoId
     *
     */
    public function getTransproductoId()
    {
        return $this->transproductoId;
    }

    /**
     * Modificar el campo 'transproductoId' de un objeto
     *
     * @param String $transproductoId transproductoId
     *
     * @return no
     *
     */
    public function setTransproductoId($transproductoId)
    {
        $this->transproductoId = $transproductoId;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setFactura($factura){
        $this->factura = $factura;
    }

    public function getFactura(){
        return $this->factura;
    }


    public function setPuntajeJugador($value){
        $this->puntajeJugador = $value;
    }

    public function getPuntajeJugador(){
        return $this->puntajeJugador;
    }



    /**
    * Realizar una consulta en la tabla de cuenta_cobro 'CuentaCobro'
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
    * @throws Exception si las cuentas no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getCuentasCobroCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="",$having="",$withCount=true,$daydimensionFechaPorPago=false,$forceTimeDimension=false,$daydimensionFechaPorAccion=false)
    {

        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

        $cuentas = $CuentaCobroMySqlDAO->queryCuentasCobroCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having,$withCount,$daydimensionFechaPorPago,$forceTimeDimension,$daydimensionFechaPorAccion);

        if ($cuentas != null && $cuentas != "")
        {
            return $cuentas;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Realizar una consulta en la tabla de cuenta_cobro 'CuentaCobro'
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
     * @return object resultado de la consulta
     * @throws Exception si las cuentas no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getCuentasCobroPuntoVentaCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="",$having="",$withCount=true,$daydimensionFechaPorPago=false,$forceTimeDimension=false,$daydimensionFechaPorAccion=false)
    {

        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

        $cuentas = $CuentaCobroMySqlDAO->queryCuentasCobroPuntoVentaCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having,$withCount,$daydimensionFechaPorPago,$forceTimeDimension,$daydimensionFechaPorAccion);

        if ($cuentas != null && $cuentas != "")
        {
            return $cuentas;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


    /**
     * Obtiene las cuentas de cobro personalizadas con automatización.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los registros a obtener.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento de los resultados.
     * @param string $having (Opcional) Condiciones de agrupamiento.
     * @return object|null Resultados de la consulta de cuentas de cobro.
     * @throws Exception Si no existen resultados.
     */
    public function getCuentasCobroCustomAutomation($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="",$having="")
    {


        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($this->transaction);

        $cuentas = $CuentaCobroMySqlDAO->queryCuentasCobroCustomAutomation($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having);

        if ($cuentas != null && $cuentas != "")
        {
            return $cuentas;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     *Función recopila el total de retiros solicitados por un usuario para una fecha especificada y retorna TRUE o FALSE en los casos
     *que supere el límite establecido mediante el clasificador MAXAMOUNTUSERWITHDRAWALS
     *
     * @param $usuarioId - Id del usuario a consultar
     * @param  $valor - Valor solicitado mediante nuevo retiro
     * @param $fechaCrea - Fecha de creación en formato 'YYYY-mm-dd' para definir el intervalo de tiempo a consultar
     *
     * @return bool TRUE: Supera el límite,  FALSE: No supera el límite
     */
    public function usuarioSuperaMaximoMontoRetiroDiario($usuarioId, $valor, $fechaCrea) {
        /** Inicializando parámetros */
        $queriedDate = date("Y-m-d", strtotime($fechaCrea));
        $queriedDateBegins = $queriedDate . ' 00:00:00';
        $queriedDateEnds = $queriedDate . ' 23:59:59';
        $withdrawalsLimit = 0;


        /** Consultando información del usuario */
        $Usuario = new Usuario($usuarioId);


        /** Consultando límite para retiros diarios definido para el partner país */
        $Clasificador = new Clasificador(null, 'MAXAMOUNTUSERWITHDRAWALS');

        try {
            $MandanteDetalle = new MandanteDetalle(null, $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $withdrawalsLimit = $MandanteDetalle->getValor();

            if ($withdrawalsLimit == 0) return false; //Si el límite es cero no se evalúa
            elseif ($withdrawalsLimit < 0) return true; //Si el límite es negativo, siempre será superado
            elseif ($withdrawalsLimit < $valor) return true; //Si es menor que el valor solicitado
        }
        catch (Exception $e) {
            //Si límite no está definido retorna false
            if ($e->getCode() != 34) Throw $e;
            return false;
        }


        /** Consultando acumulado de retiros */
        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();
        $sql = "SELECT SUM(valor) AS valorRetiros FROM cuenta_cobro WHERE usuario_id = {$Usuario->usuarioId} AND estado IN ('A', 'P', 'S', 'I') AND fecha_crea BETWEEN '" . $queriedDateBegins . "' AND '". $queriedDateEnds ."' GROUP BY usuario_id";
        $accumulated = $BonoInterno->execQuery($Transaction, $sql);
        $accumulated = $accumulated[0];


        /** Verificando cumplimiento del límite */
        if (($accumulated->{'.valorRetiros'} + $valor) > $withdrawalsLimit) return true; //Solicitud supera el máximo
        else return false; //Solicitud NO supera el máximo
    }

    /**
     * @return mixed
     */
    public function getFechaEliminacion()
    {
        return $this->fechaEliminacion;
    }

    /**
     * @param mixed $fechaEliminacion
     */
    public function setFechaEliminacion($fechaEliminacion)
    {
        $this->fechaEliminacion = $fechaEliminacion;
    }



    /**
     * Calcula el valor a pagar restando los impuestos del valor base.
     *
     * @return float El valor a pagar después de restar los impuestos.
     */
    public function getValorAPagar()
    {
        $valorFinal = $this->getValor() - $this->getImpuesto() - $this->getImpuesto2();

        return $valorFinal;
    }


    /**
     * Ejecuta una consulta SQL utilizando una transacción específica.
     *
     * @param mixed $transaccion La transacción a utilizar para la consulta.
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la consulta SQL.
     */
    public function execQuery($transaccion, $sql)
    {
        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($transaccion);
        $return = $CuentaCobroMySqlDAO->querySQL($sql);

        return $return;
    }

}

?>
