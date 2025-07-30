<?php 
namespace Backend\dto;
use Backend\mysql\FlujocajafactMySqlDAO;
use Exception;
/** 
* Clase 'Flujocajafact'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Flujocajafact'
* 
* Ejemplo de uso: 
* $Flujocajafact = new Flujocajafact();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Flujocajafact
{

    /**
    * Representación de la columna 'flujocajaId' de la tabla 'Competencia'
    *
    * @var string
    */	
	var $flujocajaId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'Competencia'
    *
    * @var string
    */
	var $fechaCrea;

    /**
    * Representación de la columna 'vlrEntradaEfectivo' de la tabla 'Competencia'
    *
    * @var string
    */
	var $vlrEntradaEfectivo;

    /**
    * Representación de la columna 'vlrEntradaBono' de la tabla 'Competencia'
    *
    * @var string
    */
	var $vlrEntradaBono;

    /**
    * Representación de la columna 'vlrEntradaRecarga' de la tabla 'Competencia'
    *
    * @var string
    */
	var $vlrEntradaRecarga;

    /**
    * Representación de la columna 'vlrEntradaTraslado' de la tabla 'Competencia'
    *
    * @var string
    */
	var $vlrEntradaTraslado;

    /**
    * Representación de la columna 'vlrSalidaEfectivo' de la tabla 'Competencia'
    *
    * @var string
    */
	var $vlrSalidaEfectivo;

    /**
    * Representación de la columna 'vlrSalidaNotaret' de la tabla 'Competencia'
    *
    * @var string
    */
	var $vlrSalidaNotaret;

    /**
    * Representación de la columna 'vlrSalidaTraslado' de la tabla 'Competencia'
    *
    * @var string
    */
	var $vlrSalidaTraslado;

    /**
    * Representación de la columna 'cantTickets' de la tabla 'Competencia'
    *
    * @var string
    */
	var $cantTickets;

    /**
    * Representación de la columna 'mandante' de la tabla 'Competencia'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'puntoventaId' de la tabla 'Competencia'
    *
    * @var string
    */
	var $puntoventaId;

    /**
    * Constructor de clase
    *
    *
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct(){}

    
    /**
    * Realizar una consulta en la tabla de Flujocajafact 'Flujocajafact'
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
    * @throws Exception si la data no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getFlujocajafactCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $FlujocajafactMySqlDAO = new FlujocajafactMySqlDAO();

        $data = $FlujocajafactMySqlDAO->queryFlujocajafactCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);
    
        if ($data != null && $data != "") 
        {
            return $data;
        }    
        else 
        {
            throw new Exception("No existe " . get_class($this), "51");
        }

    }

}

?>