<?php namespace Backend\dto;
use Backend\mysql\ConcesionarioMySqlDAO;
use Exception;
/** 
* Clase 'Concesionario'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Concesionario'
* 
* Ejemplo de uso: 
* $Concesionario = new Concesionario();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Concesionario
{

    /**
    * Representación de la columna 'concesionarioId' de la tabla 'Concesionario'
    *
    * @var string
    */
	var $concesionarioId;

    /**
    * Representación de la columna 'usupadreId' de la tabla 'Concesionario'
    *
    * @var string
    */
	var $usupadreId;

    /**
    * Representación de la columna 'usuhijoId' de la tabla 'Concesionario'
    *
    * @var string
    */
	var $usuhijoId;

    /**
    * Representación de la columna 'usupadre2Id' de la tabla 'Concesionario'
    *
    * @var string
    */
	var $usupadre2Id;

    /**
    * Representación de la columna 'mandante' de la tabla 'Concesionario'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'usupadre3Id' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $usupadre3Id;

    /**
    * Representación de la columna 'usupadre4Id' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $usupadre4Id;

    /**
    * Representación de la columna 'porcenhijo' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $porcenhijo;

    /**
    * Representación de la columna 'porcenpadre1' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $porcenpadre1;

    /**
    * Representación de la columna 'porcenpadre2' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $porcenpadre2;

    /**
    * Representación de la columna 'porcenpadre3' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $porcenpadre3;

    /**
    * Representación de la columna 'porcenpadre4' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $porcenpadre4;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'prodinternoId' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $prodinternoId;

    /**
    * Representación de la columna 'estado' de la tabla 'Concesionario'
    *
    * @var string
    */
    var $estado;

    /**
    * Constructor de clase
    *
    *
    * @param String $usuhijoId      
    * @param String $prodinternoId          
    *
    * @return no
    * @throws Exception si el concensionario existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function __construct($usuhijoId="",$prodinternoId="")
	{
		if ($usuhijoId != "" && $prodinternoId != "") 
        {

			$ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
			$Concesionario = $ConcesionarioMySqlDAO->queryByUsuhijoIdAndProdinternoId($usuhijoId,$prodinternoId);

			$Concesionario = $Concesionario[0];

			$this->success = false;

			if ($Concesionario != null && $Concesionario != "") 
            {

				$this->concesionarioId = $Concesionario->concesionarioId;
				$this->usupadreId = $Concesionario->usupadreId;
				$this->usuhijoId = $Concesionario->usuhijoId;
				$this->mandante = $Concesionario->mandante;
                $this->usupadre2Id = $Concesionario->usupadre2Id;
                $this->usupadre3Id = $Concesionario->usupadre3Id;
                $this->usupadre4Id = $Concesionario->usupadre4Id;
                $this->porcenhijo = $Concesionario->porcenhijo;
                $this->porcenpadre1 = $Concesionario->porcenpadre1;
                $this->porcenpadre2 = $Concesionario->porcenpadre2;
                $this->porcenpadre3 = $Concesionario->porcenpadre3;
                $this->porcenpadre4 = $Concesionario->porcenpadre4;
                $this->usucreaId = $Concesionario->usucreaId;
                $this->usumodifId = $Concesionario->usumodifId;
                $this->prodinternoId = $Concesionario->prodinternoId;
                $this->estado = $Concesionario->estado;

				$this->success = true;
			}
			else 
            {
				throw new Exception("No existe " . get_class($this), "48");

			}
		}
        elseif ($usuhijoId != "" ) 
        {

            $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
            $Concesionario = $ConcesionarioMySqlDAO->queryByUsuhijoId($usuhijoId);

            $Concesionario = $Concesionario[0];

            $this->success = false;

            if ($Concesionario != null && $Concesionario != "") 
            {

                $this->concesionarioId = $Concesionario->ConcesionarioId;
                $this->usupadreId = $Concesionario->usupadreId;
                $this->usuhijoId = $Concesionario->usuhijoId;
                $this->mandante = $Concesionario->mandante;
                $this->usupadre2Id = $Concesionario->usupadre2Id;
                $this->usupadre3Id = $Concesionario->usupadre3Id;
                $this->usupadre4Id = $Concesionario->usupadre4Id;
                $this->porcenhijo = $Concesionario->porcenhijo;
                $this->porcenpadre1 = $Concesionario->porcenpadre1;
                $this->porcenpadre2 = $Concesionario->porcenpadre2;
                $this->porcenpadre3 = $Concesionario->porcenpadre3;
                $this->porcenpadre4 = $Concesionario->porcenpadre4;
                $this->usucreaId = $Concesionario->usucreaId;
                $this->usumodifId = $Concesionario->usumodifId;
                $this->prodinternoId = $Concesionario->prodinternoId;
                $this->estado = $Concesionario->estado;

                $this->success = true;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "48");

            }
        }

	}


    /**
    * Realizar una consulta en la tabla de conceptos 'Concesionario'
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
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getConcesionariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();

        $Productos = $ConcesionarioMySqlDAO->queryConcesionariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "48");
        }

    }


    /**
    * Realizar una consulta en la tabla de conceptos 'ConcesionariosProductoInterno'
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
    * @param String $usuhijoId usuhijoId
    *
    * @return Array resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getConcesionariosProductoInternoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$usuhijoId="", $withClasificadorMandante = false)
    {

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();

        $Productos = $ConcesionarioMySqlDAO->queryConcesionariosProductoInternoCustom($select, $sidx, $sord, $start, $limit, $filters,$searchOn,$usuhijoId,$withClasificadorMandante);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "48");
        }

    }







    /**
     * Obtener el campo usupadreId de un objeto
     *
     * @return String usupadreId usupadreId
     * 
     */
    public function getUsupadreId()
    {
        return $this->usupadreId;
    }

    /**
     * Modificar el campo 'usupadreId' de un objeto
     *
     * @param String $usupadreId usupadreId
     *
     * @return no
     *
     */
    public function setUsupadreId($usupadreId)
    {
        $this->usupadreId = $usupadreId;
    }

    /**
     * Obtener el campo usuhijoId de un objeto
     *
     * @return String usuhijoId usuhijoId
     * 
     */
    public function getUsuhijoId()
    {
        return $this->usuhijoId;
    }

    /**
     * Modificar el campo 'usuhijoId' de un objeto
     *
     * @param String $usuhijoId usuhijoId
     *
     * @return no
     *
     */
    public function setUsuhijoId($usuhijoId)
    {
        $this->usuhijoId = $usuhijoId;
    }

    /**
     * Obtener el campo usupadre2Id de un objeto
     *
     * @return String usupadre2Id usupadre2Id
     * 
     */
    public function getUsupadre2Id()
    {
        return $this->usupadre2Id;
    }

    /**
     * Modificar el campo 'usupadre2Id' de un objeto
     *
     * @param String $usupadre2Id usupadre2Id
     *
     * @return no
     *
     */
    public function setUsupadre2Id($usupadre2Id)
    {
        $this->usupadre2Id = $usupadre2Id;
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
     * Obtener el campo usupadre3Id de un objeto
     *
     * @return String usupadre3Id usupadre3Id
     * 
     */
    public function getUsupadre3Id()
    {
        return $this->usupadre3Id;
    }

    /**
     * Modificar el campo 'usupadre3Id' de un objeto
     *
     * @param String $usupadre3Id usupadre3Id
     *
     * @return no
     *
     */
    public function setUsupadre3Id($usupadre3Id)
    {
        $this->usupadre3Id = $usupadre3Id;
    }

    /**
     * Obtener el campo usupadre4Id de un objeto
     *
     * @return String usupadre4Id usupadre4Id
     * 
     */
    public function getUsupadre4Id()
    {
        return $this->usupadre4Id;
    }

    /**
     * Modificar el campo 'usupadre4Id' de un objeto
     *
     * @param String $usupadre4Id usupadre4Id
     *
     * @return no
     *
     */
    public function setUsupadre4Id($usupadre4Id)
    {
        $this->usupadre4Id = $usupadre4Id;
    }

    /**
     * Obtener el campo porcenhijo de un objeto
     *
     * @return String porcenhijo porcenhijo
     * 
     */
    public function getPorcenhijo()
    {
        return $this->porcenhijo;
    }

    /**
     * Modificar el campo 'porcenhijo' de un objeto
     *
     * @param String $porcenhijo porcenhijo
     *
     * @return no
     *
     */
    public function setPorcenhijo($porcenhijo)
    {
        $this->porcenhijo = $porcenhijo;
    }

    /**
     * Obtener el campo porcenpadre1 de un objeto
     *
     * @return String porcenpadre1 porcenpadre1
     * 
     */
    public function getPorcenpadre1()
    {
        return $this->porcenpadre1;
    }

    /**
     * Modificar el campo 'porcenpadre1' de un objeto
     *
     * @param String $porcenpadre1 porcenpadre1
     *
     * @return no
     *
     */
    public function setPorcenpadre1($porcenpadre1)
    {
        $this->porcenpadre1 = $porcenpadre1;
    }

    /**
     * Obtener el campo porcenpadre2 de un objeto
     *
     * @return String porcenpadre2 porcenpadre2
     * 
     */
    public function getPorcenpadre2()
    {
        return $this->porcenpadre2;
    }

    /**
     * Modificar el campo 'porcenpadre2' de un objeto
     *
     * @param String $porcenpadre2 porcenpadre2
     *
     * @return no
     *
     */
    public function setPorcenpadre2($porcenpadre2)
    {
        $this->porcenpadre2 = $porcenpadre2;
    }

    /**
     * Obtener el campo porcenpadre3 de un objeto
     *
     * @return String porcenpadre3 porcenpadre3
     * 
     */
    public function getPorcenpadre3()
    {
        return $this->porcenpadre3;
    }

    /**
     * Modificar el campo 'porcenpadre3' de un objeto
     *
     * @param String $porcenpadre3 porcenpadre3
     *
     * @return no
     *
     */
    public function setPorcenpadre3($porcenpadre3)
    {
        $this->porcenpadre3 = $porcenpadre3;
    }

    /**
     * Obtener el campo porcenpadre4 de un objeto
     *
     * @return String porcenpadre4 porcenpadre4
     * 
     */
    public function getPorcenpadre4()
    {
        return $this->porcenpadre4;
    }

    /**
     * Modificar el campo 'plancuentaId' de un objeto
     *
     * @param String $plancuentaId porcenpadre4
     *
     * @return no
     *
     */
    public function setPorcenpadre4($porcenpadre4)
    {
        $this->porcenpadre4 = $porcenpadre4;
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
     * Obtener el campo prodinternoId de un objeto
     *
     * @return String prodinternoId prodinternoId
     * 
     */
    public function getProdinternoId()
    {
        return $this->prodinternoId;
    }

    /**
     * Modificar el campo 'prodinternoId' de un objeto
     *
     * @param String $prodinternoId prodinternoId
     *
     * @return no
     *
     */
    public function setProdinternoId($prodinternoId)
    {
        $this->prodinternoId = $prodinternoId;
    }

    /**
     * Obtener el campo concesionarioId de un objeto
     *
     * @return String concesionarioId concesionarioId
     * 
     */
    public function getConcesionarioId()
    {
        return $this->concesionarioId;
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



}
?>
