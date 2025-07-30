<?php namespace Backend\dto;
use Backend\mysql\IntEquipoMySqlDAO;
use Exception;
/** 
* Clase 'IntEquipo'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'IntEquipo'
* 
* Ejemplo de uso: 
* $IntEquipo = new IntEquipo();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntEquipo
{

    /**
    * Representación de la columna 'equipoId' de la tabla 'IntEquipo'
    *
    * @var string
    */  
    var $equipoId;

    /**
    * Representación de la columna 'competenciaId' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $competenciaId;

    /**
    * Representación de la columna 'nombre' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $nombre;

    /**
    * Representación de la columna 'nombreTraduccion' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $nombreTraduccion;

    /**
    * Representación de la columna 'nombreInternacional' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $nombreInternacional;

    /**
    * Representación de la columna 'abreviado' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $abreviado;

    /**
    * Representación de la columna 'estado' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $fechaModif;


    /**
    * Constructor de clase
    *
    *
    * @param String $equipoId id del equipo
    *
    * @return no
    * @throws Exception si IntEquipo no existe 
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($equipoId="")
    {
            if ($equipoId != "") 
            {

                $IntEquipoMySqlDAO = new IntEquipoMySqlDAO();

                $IntEquipo = $IntEquipoMySqlDAO->load($equipoId);

                $this->success = false;

                if ($IntEquipo != null && $IntEquipo != "") 
                {
                    $this->equipoId = $IntEquipo->equipoId;
                    $this->nombre = $IntEquipo->nombre;
                    $this->nombreTraduccion = $IntEquipo->nombreTraduccion;
                    $this->nombreInternacional = $IntEquipo->nombreInternacional;
                    $this->competenciaId = $IntEquipo->competenciaId;
                    $this->abreviado = $IntEquipo->abreviado;
                    $this->estado = $IntEquipo->estado;
                    $this->usucreaId = $IntEquipo->usucreaId;
                    $this->usumodifId = $IntEquipo->usumodifId;
                    $this->fechaCrea = $IntEquipo->fechaCrea;
                    $this->fechaModif = $IntEquipo->fechaModif;
                    $this->success = true;
                }
                else 
                {
                    throw new Exception("No existe " . get_class($this), "40");
                }
                
            }

    }

    /**
    * Realizar una consulta en la tabla de detalles de equipos 'IntEquipo'
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
    * @throws Exception si los equipos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getEquiposCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $IntEquipoMySqlDAO = new IntEquipoMySqlDAO();

        $equipos = $IntEquipoMySqlDAO->queryEquiposCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($equipos != null && $equipos != "") 
        {
            return $equipos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "40");
        }

    }

}

?>