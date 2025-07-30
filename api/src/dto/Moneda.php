<?php 

namespace Backend\dto;
use Backend\mysql\MonedaMySqlDAO;

/**
* Clase 'Moneda'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Moneda'
* 
* Ejemplo de uso: 
* $Moneda = new Moneda();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Moneda
{

    /**
    * Representación de la columna 'moneda' de la tabla 'Moneda'
    *
    * @var string
    */  		
	var $moneda;

    /**
    * Representación de la columna 'codNumerico' de la tabla 'Moneda'
    *
    * @var string
    */  
	var $codNumerico;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Moneda'
    *
    * @var string
    */  
	var $descripcion;

    var $estado;
    /**
     * Representación de la columna 'symbol' de la tabla 'Moneda'
     *
     * @var string
     */
    var $symbol;

    /**
     * Constructor de clase
     *
     *
     * @param String paismandanteId id del pais
     *
     *
     * @return no
     * @throws Exception si el pais no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($moneda="")
    {
        if ($moneda != "")
        {


            $MonedaMySqlDAO = new MonedaMySqlDAO();

            $Moneda = $MonedaMySqlDAO->load($moneda);


            if ($Moneda != null && $Moneda != "")
            {

                $this->moneda = $Moneda->moneda;
                $this->codNumerico = $Moneda->codNumerico;
                $this->descripcion = $Moneda->descripcion;
                $this->estado = $Moneda->estado;
                $this->symbol = $Moneda->symbol;
            } else {
                throw new Exception("No existe " . get_class($this), "101");

            }

        }


    }


    /**
     * Obtiene una lista de monedas personalizadas según los parámetros proporcionados.
     *
     * @param string $sidx El índice de ordenación.
     * @param string $sord El orden de clasificación (ascendente o descendente).
     * @param int $start El índice de inicio para la paginación.
     * @param int $limit El número máximo de resultados a devolver.
     * @param array $filters Filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * 
     * @return array Lista de monedas personalizadas.
     * @throws Exception Si no se encuentran monedas.
     */
    public function getMonedasCustom($sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $MonedaMySqlDAO = new MonedaMySqlDAO();

        $Monedas = $MonedaMySqlDAO->queryMonedasCustom($sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($Monedas != null && $Monedas != "") {

            return $Monedas;


        }
        else {
            throw new Exception("No existe " . get_class($this), "101");
        }


    }

}
?>