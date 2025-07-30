<?php 
namespace Backend\dto;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Exception;
/** 
* Clase 'LenguajeMandante'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'LenguajeMandante'
* 
* Ejemplo de uso: 
* $LenguajeMandante = new LenguajeMandante();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class LenguajeMandante
{

    /**
    * Representación de la columna 'lengmandanteId' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
	var $lengmandanteId;

    /**
    * Representación de la columna 'lenguaje' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
	var $lenguaje;

    /**
    * Representación de la columna 'mandante' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
    var $mandante;

    /**
    * Representación de la columna 'estado' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
    var $estado;

    /**
    * Representación de la columna 'valor' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
    var $valor;

    /**
    * Representación de la columna 'traducido' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
    var $traducido;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'LenguajeMandante'
    *
    * @var string
    */ 
    var $usumodifId;


    /**
     * Constructor de clase
     *
     *
     * @param String lengmandanteId id del lenguaje mandante
     * @param String lenguaje lenguaje
     * @param String valor valor
     *
     *
    * @return no
    * @throws Exception si LenguajeMandante no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($lengmandanteId="", $lenguaje = "",$valor="")
    {

        if($lengmandanteId != "")
        {

            $LenguajeMandanteMySqlDAO = new LenguajeMandanteMySqlDAO();

            $LenguajeMandante = $LenguajeMandanteMySqlDAO->load($lengmandanteId);


            $this->success = false;

            if ($LenguajeMandante != null && $LenguajeMandante != "")
            {
                $this->lenguaje = $LenguajeMandante->lenguaje;
                $this->mandante = $LenguajeMandante->mandante;
                $this->lengmandanteId = $LenguajeMandante->lengmandanteId;
                $this->estado = $LenguajeMandante->estado;
                $this->valor = $LenguajeMandante->valor;
                $this->traducido = $LenguajeMandante->traducido;
                $this->fechaCrea = $LenguajeMandante->fechaCrea;
                $this->usucreaId = $LenguajeMandante->usucreaId;
                $this->fechaModif = $LenguajeMandante->fechaModif;
                $this->usumodifId = $LenguajeMandante->usumodifId;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "47");
            }

        }elseif ($lenguaje != "" && $valor != "")
        {
        
            $LenguajeMandanteMySqlDAO = new LenguajeMandanteMySqlDAO();

            $LenguajeMandante = $LenguajeMandanteMySqlDAO->loadByLenguajeAndValor($lenguaje,$valor);


            $this->success = false;

            if ($LenguajeMandante != null && $LenguajeMandante != "") 
            {
            
                    $this->lenguaje = $LenguajeMandante->lenguaje;
                    $this->mandante = $LenguajeMandante->mandante;
                    $this->lengmandanteId = $LenguajeMandante->lengmandanteId;
                    $this->estado = $LenguajeMandante->estado;
                    $this->valor = $LenguajeMandante->valor;
                    $this->traducido = $LenguajeMandante->traducido;
                    $this->fechaCrea = $LenguajeMandante->fechaCrea;
                    $this->usucreaId = $LenguajeMandante->usucreaId;
                    $this->fechaModif = $LenguajeMandante->fechaModif;
                    $this->usumodifId = $LenguajeMandante->usumodifId;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "47");
            }
        }

    }


    /**
    * Realizar una consulta en la tabla de lenguajes mandantes 'LenguajeMandantes'
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
    * @throws Exception si los bonos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getLenguajeMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $LenguajeMandanteMySqlDAO = new LenguajeMandanteMySqlDAO();

        $bonos = $LenguajeMandanteMySqlDAO->queryLenguajeMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($bonos != null && $bonos != "") 
        {
            return $bonos;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "47");
        }


    }

    /**
    * Realizar una consulta en la tabla de LenguajeMandantesFromPalabra 'LenguajeMandantesFromPalabra'
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
    * @param String $leng leng
    *
    * @return Array resultado de la consulta
    * @throws Exception si los bonos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getLenguajeMandantesFromPalabraCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$leng)
    {

        $LenguajeMandanteMySqlDAO = new LenguajeMandanteMySqlDAO();

        $bonos = $LenguajeMandanteMySqlDAO->queryLenguajeMandantesFromPalabraCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$leng);

        if ($bonos != null && $bonos != "") 
        {
            return $bonos;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "47");
        }


    }





    /**
     * Obtener el campo lenguaje de un objeto
     *
     * @return String lenguaje lenguaje
     * 
     */
    public function getLenguaje()
    {
        return $this->lenguaje;
    }

    /**
     * Modificar el campo 'lenguaje' de un objeto
     *
     * @param String $lenguaje lenguaje
     *
     * @return no
     *
     */
    public function setLenguaje($lenguaje)
    {
        $this->lenguaje = $lenguaje;
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
     * Obtener el campo traducido de un objeto
     *
     * @return String traducido traducido
     * 
     */
    public function getTraducido()
    {
        return $this->traducido;
    }

    /**
     * Modificar el campo 'traducido' de un objeto
     *
     * @param String $traducido traducido
     *
     * @return no
     *
     */
    public function setTraducido($traducido)
    {
        $this->traducido = $traducido;
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
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     * 
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
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





}
?>