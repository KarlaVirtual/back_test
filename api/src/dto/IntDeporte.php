<?php namespace Backend\dto;
use Backend\mysql\IntDeporteMySqlDAO;
use Exception;
/** 
* Clase 'IntCompetencia'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'IntCompetencia'
* 
* Ejemplo de uso: 
* $IntCompetencia = new IntCompetencia();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntDeporte
{

    /**
    * Representación de la columna 'deporteId' de la tabla 'IntDeporte'
    *
    * @var string
    */  		
	var $deporteId;

    /**
    * Representación de la columna 'nombre' de la tabla 'IntDeporte'
    *
    * @var string
    */  
	var $nombre;

    /**
    * Representación de la columna 'nombreTraduccion' de la tabla 'IntDeporte'
    *
    * @var string
    */  
	var $nombreTraduccion;

    /**
    * Representación de la columna 'nombreInternacional' de la tabla 'IntDeporte'
    *
    * @var string
    */  
	var $nombreInternacional;

    /**
    * Representación de la columna 'abreviado' de la tabla 'IntDeporte'
    *
    * @var string
    */  
	var $abreviado;

    /**
    * Representación de la columna 'estado' de la tabla 'IntDeporte'
    *
    * @var string
    */  
	var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'IntDeporte'
    *
    * @var string
    */  
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'IntDeporte'
    *
    * @var string
    */  
	var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'IntDeporte'
    *
    * @var string
    */  
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'IntDeporte'
    *
    * @var string
    */
    public function __construct($deporteId = "")
    {

        if ($deporteId != "")
        {

            $IntDeporteMySqlDAO = new IntDeporteMySqlDAO();

            $IntDeporte = $IntDeporteMySqlDAO->load($deporteId);


            if ($IntDeporte != null && $IntDeporte != "")
            {
                $this->deporteId = $IntDeporte->deporteId;
                $this->nombre = $IntDeporte->nombre;
                $this->nombreTraduccion = $IntDeporte->nombreTraduccion;
                $this->nombreInternacional = $IntDeporte->nombreInternacional;
                $this->abreviado = $IntDeporte->abreviado;
                $this->estado = $IntDeporte->estado;
                $this->usucreaId = $IntDeporte->usucreaId;
                $this->usumodifId = $IntDeporte->usumodifId;
                $this->fechaCrea = $IntDeporte->fechaCrea;
                $this->fechaModif =$IntDeporte->fechaModif;
                $this->proveedorDeporteId = $IntDeporte->proveedorDeporteId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "116");
            }

        }

    }


    /**
    * Realizar una consulta en la tabla de detalles de deportes 'IntDeporte'
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
    * @throws Exception si los deportes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getDeportesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $IntDeporteMySqlDAO = new IntDeporteMySqlDAO();

        $deportes = $IntDeporteMySqlDAO->queryDeportesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($deportes != null && $deportes != "") 
        {
            return $deportes;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

		
	}
?>