<?php
namespace Backend\dto;

use Backend\mysql\BancoMandanteMySqlDAO;
use Exception;
/**
 * Clase 'BancoMandante'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'BancoMandante'
 *
 * Ejemplo de uso:
 * $BancoMandante = new BancoMandante();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

class BancoMandante
{
    /**
     * Representación de la columna 'bancomandante_id' de la tabla 'BancoMandante'
     *
     * @var string
     */
    var $bancomandanteId;

    /**
     * Representación de la columna 'banco_id' de la tabla 'BancoMandante'
     *
     * @var string
     */

    var $bancoId;

    /**
     * Representacion de la columna 'pais_id' de la tabla 'BancoMandante'
     *
     * @var string
     *
     */
    var $paisId;

    /**
     * Representación de la columna 'mandante' de la tabla 'BancoMandante'
     *
     * @var string
     */


    var $mandante;
    /**
     * Representación de la columna 'estado' de la tabla 'BancoMandante'
     *
     * @var string
     */

    var $estado;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'BancoMandante'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'BancoMandante'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'BancoMandante'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'BancoMandante'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Constructor de clase
     *
     *
     * @param String $bancoId id del banco
     * @param String $mandante mandante
     * @param String $bancomandanteId bancomandanteId
     *
     * @return no
     * @throws Exception si BancoMandante no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($bancoId ="", $mandante="", $bancomandanteId = "", $paisId="")
    {
        if ($bancoId != "" && $mandante != "" && $paisId != "")
        {
            $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO();
            $BancoMandante = $BancoMandanteMySqlDAO->queryByBancoIdAndMandanteAndPaisId($bancoId, $mandante,$paisId);
            $BancoMandante = $BancoMandante[0];

            if ($BancoMandante != null && $BancoMandante != "")
            {
                $this->bancomandanteId = $BancoMandante->bancomandanteId;
                $this->bancoId = $BancoMandante->bancoId;
                $this->paisId = $BancoMandante->paisId;
                $this->mandante = $BancoMandante->mandante;
                $this->estado = $BancoMandante->estado;
                $this->usucreaId = $BancoMandante->usucreaId;
                $this->usumodifId = $BancoMandante->usumodifId;


            }
            else
            {
                throw new Exception("No existe " . get_class($this), "27");
            }

        }
        elseif ($bancoId != "" && $mandante != "")
        {

            $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO();

            $BancoMandante = $BancoMandanteMySqlDAO->queryByBancoIdAndMandante($bancoId, $mandante);
            $BancoMandante = $BancoMandante[0];

            if ($BancoMandante != null && $BancoMandante != "")
            {
                $this->bancomandanteId = $BancoMandante->bancomandanteId;
                $this->bancoId = $BancoMandante->bancoId;
                $this->paisId = $BancoMandante->paisId;
                $this->mandante = $BancoMandante->mandante;
                $this->estado = $BancoMandante->estado;
                $this->usucreaId = $BancoMandante->usucreaId;
                $this->usumodifId = $BancoMandante->usumodifId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "27");
            }

        }else if($bancomandanteId != "" and $mandante != "" and $paisId !=""){


            $BancoMandanteMysqlDAO = new BancoMandanteMySqlDAO();
            $BancoMandante = $BancoMandanteMysqlDAO->dataByCountryMandante($mandante,$bancomandanteId,$paisId);
            $BancoMandante = $BancoMandante[0];

            if($BancoMandante != ""){
                $this->bancomandanteId = $BancoMandante->bancomandanteId;
                $this->bancoId = $BancoMandante->bancoId;
                $this->paisId = $BancoMandante->paisId;
                $this->mandante = $BancoMandante->mandante;
                $this->estado = $BancoMandante->estado;
                $this->usucreaId = $BancoMandante->usucreaId;
                $this->usumodifId = $BancoMandante->usumodifId;

            }else{
                throw new Exception("No existe".get_class($this),"27");
            }

        }elseif ($bancomandanteId != "") {

            $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO();

            $BancoMandante = $BancoMandanteMySqlDAO->load($bancomandanteId);

            if ($BancoMandante != null && $BancoMandante != "")
            {
                $this->bancomandanteId = $BancoMandante->bancomandanteId;
                $this->bancoId = $BancoMandante->bancoId;
                $this->paisId = $BancoMandante->paisId;
                $this->mandante = $BancoMandante->mandante;
                $this->estado = $BancoMandante->estado;
                $this->usucreaId = $BancoMandante->usucreaId;
                $this->usumodifId = $BancoMandante->usumodifId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "27");
            }
        }
    }

    /**
     * Establece el valor del mandante.
     *
     * @param mixed $mandante El valor del mandante a establecer.
     * @return void
     */
    public function setMandante($mandante){
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el valor del mandante.
     *
     * @return mixed
     */
    public function getMandante(){
        return $this->mandante;
    }

    /**
     * Establece el valor de la fecha de creación.
     *
     * @param mixed $fechaCrea El valor de la fecha de creación a establecer.
     * @return void
     */
    public function setPais($Country){
        $this->paisId = $Country;
    }

    /**
     * Obtiene el valor de la fecha de creación.
     *
     * @return mixed
     */
    public function getPais(){
        return $this->paisId;
    }

    /**
     * Establece el valor de la fecha de creación.
     *
     * @param mixed $fechaCrea El valor de la fecha de creación a establecer.
     * @return void
     */
    public function setBancoId($BancoId){
        $this->bancoId = $BancoId;
    }

    /**
     * Obtiene el valor de la fecha de creación.
     *
     * @return mixed
     */
    public function setUsuCreaId($id){
        $this->usucreaId = $id;
    }

    /**
     * Obtiene el valor de la fecha de creación.
     *
     * @return mixed
     */
    public function setEstado($estado){
        $this->estado = $estado;
    }

    /**
     * Obtiene el valor de la fecha de creación.
     *
     * @return mixed
     */
    public function setUsuModifId($id){
        $this->usumodifId = $id;
    }

    /**
     * Obtiene el valor de la fecha de creación.
     *
     * @return mixed
     */
    public function setBancomandanteId($value){
        $this->bancomandanteId = $value;
    }

    /**
     * Obtiene el valor de la fecha de creación.
     *
     * @return mixed
     */
    public function getBancomandanteId()
    {
        return $this->bancomandanteId;
    }

    /**
     * Realizar una consulta en la tabla de BancoMandante 'BancoMandante'
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
     *
     * @return Array resultado de la consulta
     * @throws Exception si los bancos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getBancosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO();

        $Bancos = $BancoMandanteMySqlDAO->queryBancosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Bancos != null && $Bancos != "")
        {
            return $Bancos ;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

}
