<?php 
namespace Backend\dto;
use Backend\mysql\LenguajePalabraMySqlDAO;
use Exception;
/** 
* Clase 'LenguajePalabra'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'LenguajePalabra'
* 
* Ejemplo de uso: 
* $LenguajePalabra = new LenguajePalabra();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class LenguajePalabra
{

    /**
    * Representación de la columna 'lengpalabraId' de la tabla 'LenguajePalabra'
    *
    * @var string
    */ 
	var $lengpalabraId;

    /**
    * Representación de la columna 'estado' de la tabla 'LenguajePalabra'
    *
    * @var string
    */ 
    var $estado;

    /**
    * Representación de la columna 'tipo' de la tabla 'LenguajePalabra'
    *
    * @var string
    */ 
    var $tipo;

    /**
    * Representación de la columna 'valor' de la tabla 'LenguajePalabra'
    *
    * @var string
    */ 
    var $valor;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'LenguajePalabra'
    *
    * @var string
    */ 
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'LenguajePalabra'
    *
    * @var string
    */ 
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'LenguajePalabra'
    *
    * @var string
    */ 
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'LenguajePalabra'
    *
    * @var string
    */ 
    var $usumodifId;


    /**
     * Constructor de clase
     *
     *
     * @param String lengpalabraId id de LenguajePalabra
     * @param String lenguaje lenguaje
     *
     *
    * @return no
    * @throws Exception si LenguajePalabra no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($lengpalabraId="", $lenguaje = "")
    {

        if($lengpalabraId != "")
        {

            $LenguajePalabraMySqlDAO = new LenguajePalabraMySqlDAO();

            $LenguajePalabra = $LenguajePalabraMySqlDAO->load($lengpalabraId);


            $this->success = false;

            if ($LenguajePalabra != null && $LenguajePalabra != "") 
            {
                $this->lengpalabraId = $LenguajePalabra->lengpalabraId;
                $this->estado = $LenguajePalabra->estado;
                $this->tipo = $LenguajePalabra->tipo;
                $this->valor = $LenguajePalabra->valor;
                $this->fechaCrea = $LenguajePalabra->fechaCrea;
                $this->usucreaId = $LenguajePalabra->usucreaId;
                $this->fechaModif = $LenguajePalabra->fechaModif;
                $this->usumodifId = $LenguajePalabra->usumodifId;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "47");
            }

        }
        elseif ($lenguaje != "")
        {
            
            $LenguajePalabraMySqlDAO = new LenguajePalabraMySqlDAO();

            $LenguajePalabra = $LenguajePalabraMySqlDAO->load($lengpalabraId);


            $this->success = false;

            if ($LenguajePalabra != null && $LenguajePalabra != "") 
            {
                $this->lengpalabraId = $LenguajePalabra->lengpalabraId;
                $this->estado = $LenguajePalabra->estado;
                $this->tipo = $LenguajePalabra->tipo;
                $this->valor = $LenguajePalabra->valor;
                $this->fechaCrea = $LenguajePalabra->fechaCrea;
                $this->usucreaId = $LenguajePalabra->usucreaId;
                $this->fechaModif = $LenguajePalabra->fechaModif;
                $this->usumodifId = $LenguajePalabra->usumodifId;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "47");
            }
        
        }

    }


    /**
    * Realizar una consulta en la tabla de LenguajePalabra 'LenguajePalabra'
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
    public function getLenguajePalabrasCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $LenguajePalabraMySqlDAO = new LenguajePalabraMySqlDAO();

        $bonos = $LenguajePalabraMySqlDAO->queryLenguajePalabrasCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

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