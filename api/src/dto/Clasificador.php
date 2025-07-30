<?php namespace Backend\dto;
use Backend\mysql\ClasificadorMySqlDAO;
use Exception;
/** 
* Clase 'Clasificador'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'clasificador'
* 
* Ejemplo de uso: 
* $Clasificador = new Clasificador();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Clasificador
{

    /**
    * Representación de la columna 'clasificadorId' de la tabla 'Clasificador'
    *
    * @var string
    */
    var $clasificadorId;

    /**
    * Representación de la columna 'tipo' de la tabla 'Clasificador'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Clasificador'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'Clasificador'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'mandante' de la tabla 'Clasificador'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'abreviado' de la tabla 'Clasificador'
    *
    * @var string
    */
    var $abreviado;

    //
    protected $allArray;

    /**
    * Constructor de clase
    *
    *
    * @param String $clasificadorId
    * @param String $abreviado
    *
    * @return no
    * @throws Exception si el clasificador no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($clasificadorId = "", $abreviado = "")
    {

        if ($clasificadorId != "")
        {

            $ClasificadorMySqlDAO = new ClasificadorMySqlDAO();

            $Clasificador = $ClasificadorMySqlDAO->load($clasificadorId);


            if ($Clasificador != null && $Clasificador != "")
            {
                $this->clasificadorId = $Clasificador->clasificadorId;
                $this->tipo = $Clasificador->tipo;
                $this->descripcion = $Clasificador->descripcion;
                $this->estado = $Clasificador->estado;
                $this->mandante = $Clasificador->mandante;
                $this->abreviado = $Clasificador->abreviado;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "41");
            }

        }
        elseif (is_array($abreviado))
        {
            $ClasificadorMySqlDAO = new ClasificadorMySqlDAO();
            $Clasificadores = $ClasificadorMySqlDAO->loadSelected($abreviado);

            $this->allArray =  $Clasificadores;
        }
        elseif ($abreviado != "")
        {
            $ClasificadorMySqlDAO = new ClasificadorMySqlDAO();

            $Clasificador = $ClasificadorMySqlDAO->queryByAbreviado($abreviado);

            $Clasificador = $Clasificador[0];

            if ($Clasificador != null && $Clasificador != "")
            {
                $this->clasificadorId = $Clasificador->clasificadorId;
                $this->tipo = $Clasificador->tipo;
                $this->descripcion = $Clasificador->descripcion;
                $this->estado = $Clasificador->estado;
                $this->mandante = $Clasificador->mandante;
                $this->abreviado = $Clasificador->abreviado;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "41");
            }
        }

    }




    /**
     * Obtiene el parámetro allArray del objeto Clasificador
     *
     * @return array El array completo.
     */
    public function getAllArray()
    {
        return $this->allArray;
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
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
     *
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar el campo 'descripcion' de un objeto
     *
     * @param String $descripcion descripcion
     *
     * @return no
     *
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
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
     * Obtener el campo abreviado de un objeto
     *
     * @return String abreviado abreviado
     *
     */
    public function getAbreviado()
    {
        return $this->abreviado;
    }

    /**
     * Modificar el campo 'abreviado' de un objeto
     *
     * @param String $abreviado abreviado
     *
     * @return no
     *
     */
    public function setAbreviado($abreviado)
    {
        $this->abreviado = $abreviado;
    }

    /**
     * Obtener el campo clasificadorId de un objeto
     *
     * @return String clasificadorId clasificadorId
     * 
     */
    public function getClasificadorId()
    {
        return $this->clasificadorId;
    }








    /**
    * Realizar una consulta en la tabla de clasificadores 'Clasificador'
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
    * @throws Exception si los clasificadores no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getClasificadoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ClasificadorMySqlDAO = new ClasificadorMySqlDAO();

        $clasificadores = $ClasificadorMySqlDAO->queryClasificadoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "") 
        {
            return $clasificadores;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "41");
        }

    }


}

?>