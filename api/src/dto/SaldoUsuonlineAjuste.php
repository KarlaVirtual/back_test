<?php namespace Backend\dto;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
/**
* Clase 'Registro'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Registro'
*
* Ejemplo de uso:
* $Registro = new Registro();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/

class SaldoUsuonlineAjuste
{

    /**
    * Representación de la columna 'ajusteId' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $ajusteId;

    /**
    * Representación de la columna 'tipoId' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $tipoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $usuarioId;

    /**
    * Representación de la columna 'valor' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $valor;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $usucreaId;

    /**
    * Representación de la columna 'saldoAnt' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $saldoAnt;

    /**
    * Representación de la columna 'observ' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $observ;

    /**
    * Representación de la columna 'dirIp' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $dirIp;

    /**
    * Representación de la columna 'mandante' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'motivoId' de la tabla 'SaldoUsuonlineAjuste'
    *
    * @var string
    */
	var $motivoId;

        var $tipoSaldo;


    /**
     * Representación de la columna 'tipo' de la tabla 'SaldoUsuonlineAjuste'
     *
     * @var string
     */
    var $tipo;


    /**
     * Representación de la columna 'proveedorId;' de la tabla 'SaldoUsuonlineAjuste'
     *
     * @var string
     */
    var $proveedorId;


    /**
    * Constructor de clase
    *
    *
    * @param String $ajusteId id del ajuste
    * @param String $tipoId id del tipo
    * @param String $usuarioId id del usuario
    *
    * @return no
    * @throws Exception si SaludoUsuonlineAjuste no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($ajusteId="", $tipoId="", $usuarioId="")
    {


        if($ajusteId != "")
        {

            $SaldoUsuonlineAjusteMySqlDAO = new SaldoUsuonlineAjusteMySqlDAO();
            $SaldoUsu = $SaldoUsuonlineAjusteMySqlDAO->load($ajusteId);

            $this->success = false;

            if ($SaldoUsu != null && $SaldoUsu != "")
            {

                $this->ajusteId = $SaldoUsu->ajusteId;
                $this->tipoId = $SaldoUsu->tipoId;
                $this->usuarioId = $SaldoUsu->usuarioId;
                $this->valor = $SaldoUsu->valor;
                $this->fechaCrea = $SaldoUsu->fechaCrea;
                $this->usucreaId = $SaldoUsu->usucreaId;
                $this->saldoAnt = $SaldoUsu->saldoAnt;
                $this->observ = $SaldoUsu->observ;
                $this->dirIp = $SaldoUsu->dirIp;
                $this->mandante = $SaldoUsu->mandante;
                $this->motivoId = $SaldoUsu->motivoId;
                $this->tipo = $SaldoUsu->tipo;
                $this->proveedorId = $SaldoUsu->proveedorId;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "43");
            }

        }
        elseif ($tipoId != "" && $usuarioId != "")
        {

            $SaldoUsuonlineAjusteMySqlDAO = new SaldoUsuonlineAjusteMySqlDAO();
            $SaldoUsu = $SaldoUsuonlineAjusteMySqlDAO->queryByTipoIdAndUsuarioId($ajusteId);

            $SaldoUsu=$SaldoUsu[0];
            $this->success = false;

            if ($SaldoUsu != null && $SaldoUsu != "")
            {

                $this->ajusteId = $SaldoUsu->ajusteId;
                $this->tipoId = $SaldoUsu->tipoId;
                $this->usuarioId = $SaldoUsu->usuarioId;
                $this->valor = $SaldoUsu->valor;
                $this->fechaCrea = $SaldoUsu->fechaCrea;
                $this->usucreaId = $SaldoUsu->usucreaId;
                $this->saldoAnt = $SaldoUsu->saldoAnt;
                $this->observ = $SaldoUsu->observ;
                $this->dirIp = $SaldoUsu->dirIp;
                $this->mandante = $SaldoUsu->mandante;
                $this->motivoId = $SaldoUsu->motivoId;
                $this->tipo = $SaldoUsu->tipo;
                $this->proveedorId = $SaldoUsu->proveedorId;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "43");

            }

        }
    }

/**
     * Obtener el campo tipoAfectado de un objeto
     *
     * @return mixed tipoAfectado
     */
    public function getTipoAfectado() {
        return $this->tipoAfectado;
    }

    /**
     * Modificar el campo 'tipoAfectado' de un objeto
     *
     * @param mixed $tipoAfectado tipoAfectado
     */
    public function setTipoAfectado($tipoAfectado) {
        $this->tipoAfectado = $tipoAfectado;
    }

    /**
     * Obtener el campo tipoId de un objeto
     *
     * @return String tipoId tipoId
     *
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Modificar el campo 'tipoId' de un objeto
     *
     * @param String $tipoId tipoId
     *
     * @return no
     *
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;
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
     * Obtener el campo saldoAnt de un objeto
     *
     * @return String saldoAnt saldoAnt
     *
     */
    public function getSaldoAnt()
    {
        return $this->saldoAnt;
    }

    /**
     * Modificar el campo 'saldoAnt' de un objeto
     *
     * @param String $saldoAnt saldoAnt
     *
     * @return no
     *
     */
    public function setSaldoAnt($saldoAnt)
    {
        $this->saldoAnt = $saldoAnt;
    }

    /**
     * Obtener el campo observ de un objeto
     *
     * @return String observ observ
     *
     */
    public function getObserv()
    {
        return $this->observ;
    }

    /**
     * Modificar el campo 'observ' de un objeto
     *
     * @param String $observ observ
     *
     * @return no
     *
     */
    public function setObserv($observ)
    {
        $this->observ = $observ;
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
     * Obtener el campo motivoId de un objeto
     *
     * @return String motivoId motivoId
     *
     */
    public function getMotivoId()
    {
        return $this->motivoId;
    }

    /**
     * Modificar el campo 'motivoId' de un objeto
     *
     * @param String $motivoId motivoId
     *
     * @return no
     *
     */
    public function setMotivoId($motivoId)
    {
        $this->motivoId = $motivoId;
    }

    /**
     * obtener el campo tipo de un objeto SaldoUsuonlineAjuste
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto SaldoUsuonlineAjuste
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * obtener el campo proveedorId de un objeto SaldoUsuonlineAjuste
     * @return string
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Modificar el campo 'proveedorId' de un objeto SaldoUsuonlineAjuste
     * @param string $proveedorId
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }



    /**
     * Modificar el campo 'ajusteId' de un objeto
     * Obtener el campo ajusteId de un objeto
     *
     * @return String ajusteId ajusteId
     *
     */
    public function getAjusteId()
    {
        return $this->ajusteId;
    }

        /**
         * Obtener el campo tipoSaldo de un objeto SaldoUsuonlineAjuste
         * @return mixed
         */
        public function getTipoSaldo()
        {
            return $this->tipoSaldo;
        }

        /**
         * Modificar el campo 'tipoSaldo' de un objeto SaldoUsuonlineAjuste
         * @param mixed $tipoSaldo
         */
        public function setTipoSaldo($tipoSaldo)
        {
            $this->tipoSaldo = $tipoSaldo;
        }



        /**
         * Get Domain object by primry key
         *
         * @param
         * @return int
         */
        public function getSaldoUsuonlineAjustesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
        {

            $SaldoUsuonlineAjusteMySqlDAO = new SaldoUsuonlineAjusteMySqlDAO();

            $Ajustes = $SaldoUsuonlineAjusteMySqlDAO->querySaldoUsuonlineAjustesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

            if ($Ajustes != null && $Ajustes != "") {

                return $Ajustes;

            } else {
                throw new Exception("No existe " . get_class($this), "43");
            }

        }



}
?>