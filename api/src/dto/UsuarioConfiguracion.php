<?php namespace Backend\dto;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioConfiguracion'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioConfiguracion'
* 
* Ejemplo de uso: 
* $UsuarioConfiguracion = new UsuarioConfiguracion();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioConfiguracion
{

    /**
    * Representación de la columna 'usuconfigId' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $usuconfigId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'productoId' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $productoId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioConfiguracion'
    *
    * @var string
    */
    var $estado;

    /**
     * Representación de la columna 'nota' de la tabla 'UsuarioConfiguracion'
     *
     * @var string
     */
    var $nota;

    /**
     * Representación de la columna 'fecha inicio' de la tabla 'UsuarioConfiguracion'
     *
     * @var string
     */

    var $fechaInicio;

    /**
     * Representación de la columna 'fecha fin' de la tabla 'UsuarioConfiguracion'
     *
     * @var string
     */

    var $fechaFin;






    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId usuarioId
    * @param String $estado estado
    * @param String $tipo tipo
    * @param String $productoId productoId
    * @param String $usuconfigId usuconfigId
    *
    * @return no
    * @throws Exception si UsuarioConfiguracion no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuarioId = "", $estado = "", $tipo = "", $productoId = "", $usuconfigId = "")
    {
        $this->usuarioId = $usuarioId;
        $this->tipo = $tipo;

        if ($usuconfigId != "") 
        {


            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

            $UsuarioConfiguracion = $UsuarioConfiguracionMySqlDAO->load($usuconfigId);

            if ($UsuarioConfiguracion != null && $UsuarioConfiguracion != "") 
            {
                $this->usuconfigId = $UsuarioConfiguracion->usuconfigId;
                $this->usuarioId = $UsuarioConfiguracion->usuarioId;
                $this->tipo = $UsuarioConfiguracion->tipo;
                $this->valor = $UsuarioConfiguracion->valor;
                $this->usucreaId = $UsuarioConfiguracion->usucreaId;
                $this->usumodifId = $UsuarioConfiguracion->usumodifId;
                $this->productoId = $UsuarioConfiguracion->productoId;
                $this->estado = $UsuarioConfiguracion->estado;
                $this->nota = $UsuarioConfiguracion->nota;
                $this->fechaCrea = $UsuarioConfiguracion->fechaCrea;
                $this->fechaModif = $UsuarioConfiguracion->fechaModif;
                $this->fechaInicio = $UsuarioConfiguracion->fechaInicio;
                $this->fechaFin = $UsuarioConfiguracion->fechaFin;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "46");
            }

        } 
        elseif ($usuarioId != "" && $tipo != "" && $productoId != "" && $estado != "")
        {


            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

            $UsuarioConfiguracion = $UsuarioConfiguracionMySqlDAO->queryByUsuarioIdAndTipoAndProductoIdAndEstado($usuarioId, $tipo, $productoId, $estado);
            $UsuarioConfiguracion = $UsuarioConfiguracion[0];

            if ($UsuarioConfiguracion != null && $UsuarioConfiguracion != "") 
            {
                $this->usuconfigId = $UsuarioConfiguracion->usuconfigId;
                $this->usuarioId = $UsuarioConfiguracion->usuarioId;
                $this->tipo = $UsuarioConfiguracion->tipo;
                $this->valor = $UsuarioConfiguracion->valor;
                $this->usucreaId = $UsuarioConfiguracion->usucreaId;
                $this->usumodifId = $UsuarioConfiguracion->usumodifId;
                $this->productoId = $UsuarioConfiguracion->productoId;
                $this->estado = $UsuarioConfiguracion->estado;
                $this->nota = $UsuarioConfiguracion->nota;
                $this->fechaCrea = $UsuarioConfiguracion->fechaCrea;
                $this->fechaModif = $UsuarioConfiguracion->fechaModif;
                $this->fechaInicio = $UsuarioConfiguracion->fechaInicio;
                $this->fechaFin = $UsuarioConfiguracion->fechaFin;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "46");
            }

        } 
        elseif ($usuarioId != "" && $tipo != "" && $estado != "") 
        {


            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

            $UsuarioConfiguracion = $UsuarioConfiguracionMySqlDAO->queryByUsuarioIdAndTipoAndEstado($usuarioId, $tipo, $estado);
            $UsuarioConfiguracion = $UsuarioConfiguracion[0];

            if ($UsuarioConfiguracion != null && $UsuarioConfiguracion != "") 
            {
                $this->usuconfigId = $UsuarioConfiguracion->usuconfigId;
                $this->usuarioId = $UsuarioConfiguracion->usuarioId;
                $this->tipo = $UsuarioConfiguracion->tipo;
                $this->valor = $UsuarioConfiguracion->valor;
                $this->usucreaId = $UsuarioConfiguracion->usucreaId;
                $this->usumodifId = $UsuarioConfiguracion->usumodifId;
                $this->productoId = $UsuarioConfiguracion->productoId;
                $this->estado = $UsuarioConfiguracion->estado;
                $this->nota = $UsuarioConfiguracion->nota;
                $this->fechaCrea = $UsuarioConfiguracion->fechaCrea;
                $this->fechaModif = $UsuarioConfiguracion->fechaModif;
                $this->fechaInicio = $UsuarioConfiguracion->fechaInicio;
                $this->fechaFin = $UsuarioConfiguracion->fechaFin;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "46");
            }
        }
        elseif ($usuarioId != "" && $tipo != "" && $productoId != ""){
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

            $UsuarioConfiguracion = $UsuarioConfiguracionMySqlDAO->queryByUsuarioIdAndTipoAndProducto($usuarioId,$tipo,$productoId);
            $UsuarioConfiguracion = $UsuarioConfiguracion[0];

            if ($UsuarioConfiguracion != null && $UsuarioConfiguracion != "")
            {
                $this->usuconfigId = $UsuarioConfiguracion->usuconfigId;
                $this->usuarioId = $UsuarioConfiguracion->usuarioId;
                $this->tipo = $UsuarioConfiguracion->tipo;
                $this->valor = $UsuarioConfiguracion->valor;
                $this->usucreaId = $UsuarioConfiguracion->usucreaId;
                $this->usumodifId = $UsuarioConfiguracion->usumodifId;
                $this->productoId = $UsuarioConfiguracion->productoId;
                $this->estado = $UsuarioConfiguracion->estado;
                $this->nota = $UsuarioConfiguracion->nota;
                $this->fechaCrea = $UsuarioConfiguracion->fechaCrea;
                $this->fechaModif = $UsuarioConfiguracion->fechaModif;
                $this->fechaInicio = $UsuarioConfiguracion->fechaInicio;
                $this->fechaFin = $UsuarioConfiguracion->fechaFin;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "46");
            }
        }
    }





    /**
     * Obtener el campo usuconfigId de un objeto
     *
     * @return String usuconfigId usuconfigId
     * 
     */
    public function getUsuconfigId()
    {
        return $this->usuconfigId;
    }

    /**
     * Modificar el campo 'usuconfigId' de un objeto
     *
     * @param String $usuconfigId usuconfigId
     *
     * @return no
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
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
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
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
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
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo productoId de un objeto
     *
     * @return String productoId productoId
     * 
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
     * @return no
     *
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
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
     * Modificar el campo 'nota' de un objeto
     *
     * @param string $nota nota
     *
     * @return void
     */
    public function setNota($nota) {
        $this->nota = $nota;
    }

    /**
     * Obtener el campo 'nota' de un objeto
     *
     * @return string nota
     */
    public function getNota() {
        return $this->nota;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param string $fechaCrea fechaCrea
     *
     * @return void
     */
    public function setFechaCrea($fechaCrea) {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo 'fechaCrea' de un objeto
     *
     * @return string fechaCrea
     */
    public function getFechaCrea() {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param string $fechaModif fechaModif
     *
     * @return void
     */
    public function setFechaModif($fechaModif) {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo 'fechaModif' de un objeto
     *
     * @return string fechaModif
     */
    public function getFechaModif() {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaInicio' de un objeto
     *
     * @param string $fechaInicio fechaInicio
     *
     * @return void
     */
    public function setFechaInicio($fechaInicio){
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * Obtener el campo 'fechaInicio' de un objeto
     *
     * @return string fechaInicio
     */
    public function getFechaInicio(){
        return $this->fechaInicio;
    }

    /**
     * Modificar el campo 'fechaFin' de un objeto
     *
     * @param string $value fechaFin
     *
     * @return void
     */
    public function setFechaFin($value){
        $this->fechaFin = $value;
    }

    /**
     * Obtener el campo 'fechaFin' de un objeto
     *
     * @return string fechaFin
     */
    public function getFechaFin(){
        return $this->fechaFin;
    }


    /**
    * Realizar una consulta en la tabla de UsuarioConfiguracion 'UsuarioConfiguracion'
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
    * @throws Exception si los deportes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioConfiguracionesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

        $deportes = $UsuarioConfiguracionMySqlDAO->queryUsuarioConfiguracionesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($deportes != null && $deportes != "") 
        {
            return $deportes;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "46");
        }

    }


    /**
     * Verifica los límites de depósito para un usuario.
     *
     * @param mixed $valor El valor a verificar contra los límites de depósito.
     * @return mixed El resultado de la verificación de los límites de depósito.
     * @throws Exception Si el usuario no existe.
     */
    public function verifyLimitesDeposito($valor)
    {

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

        $return = $UsuarioConfiguracionMySqlDAO->queryVerifiyLimitesDeposito($this,$valor);

        return $return;
        if ($Usuario != null && $Usuario != "")
        {
            return $Usuario;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "46");
        }

    }


    /**
     * Verifica los límites del casino para un usuario mandante.
     *
     * @param mixed $valor El valor a verificar.
     * @param mixed $UsuarioMandante El usuario mandante.
     * @return mixed El resultado de la verificación de límites del casino.
     * @throws Exception Si el usuario no existe.
     */
    public function verifyLimitesCasino($valor,$UsuarioMandante)
    {

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

        $return = $UsuarioConfiguracionMySqlDAO->queryVerifiyLimitesCasino($this,$valor,$UsuarioMandante);

        return $return;
        if ($Usuario != null && $Usuario != "")
        {
            return $Usuario;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "46");
        }

    }


    /**
     * Verifica los límites del casino en vivo para un usuario mandante.
     *
     * @param mixed $valor El valor a verificar.
     * @param mixed $UsuarioMandante El usuario mandante para el cual se verifican los límites.
     * @return mixed El resultado de la verificación de los límites del casino en vivo.
     * @throws Exception Si el usuario no existe.
     */
    public function verifyLimitesCasinoVivo($valor,$UsuarioMandante)
    {

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

        $return = $UsuarioConfiguracionMySqlDAO->queryVerifiyLimitesCasinoVivo($this,$valor,$UsuarioMandante);

        return $return;
        if ($Usuario != null && $Usuario != "")
        {
            return $Usuario;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "46");
        }

    }

    /**
     * Verifica los límites deportivos para un usuario mandante.
     *
     * @param mixed $valor El valor a verificar.
     * @param mixed $UsuarioMandante El usuario mandante para la verificación.
     * @return mixed El resultado de la verificación de límites deportivos.
     * @throws Exception Si el usuario no existe.
     */
    public function verifyLimitesDeportivas($valor,$UsuarioMandante)
    {

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

        $return = $UsuarioConfiguracionMySqlDAO->queryVerifiyLimitesDeportivas($this,$valor,$UsuarioMandante);

        return $return;
        if ($Usuario != null && $Usuario != "")
        {
            return $Usuario;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "46");
        }

    }

    /**
     * Verifica los límites virtuales para un usuario mandante.
     *
     * @param mixed $valor El valor a verificar.
     * @param mixed $UsuarioMandante El usuario mandante para el cual se verifican los límites.
     * @return mixed El resultado de la verificación de límites virtuales.
     * @throws Exception Si el usuario no existe.
     */
    public function verifyLimitesVirtuales($valor,$UsuarioMandante)
    {

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

        $return = $UsuarioConfiguracionMySqlDAO->queryVerifiyLimitesVirtuales($this,$valor,$UsuarioMandante);

        return $return;
        if ($Usuario != null && $Usuario != "")
        {
            return $Usuario;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "46");
        }

    }

    /**
     * Obtiene la configuración de usuario para el tipo 'BONDABUSER'.
     *
     * @param int $usuarioId El ID del usuario.
     * @return mixed La configuración del usuario para el tipo 'BONDABUSER'.
     * @throws Exception Si ocurre un error durante la consulta.
     */
    public  function getUserBondABuser($usuarioId)
    {
        try {
            $clasificador = new Clasificador('','BONDABUSER');
            $tipo = $clasificador->clasificadorId;
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracion = $UsuarioConfiguracionMySqlDAO->queryByUsuarioIdAndTipo($usuarioId, $tipo);

            return $UsuarioConfiguracion[0];

        }catch (Exception $e){

        }
    }

}

?>