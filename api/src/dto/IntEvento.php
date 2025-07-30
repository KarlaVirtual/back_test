<?php namespace Backend\dto;
use Backend\mysql\IntEventoMySqlDAO;
use Exception;
/** 
* Clase 'IntEvento'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'IntEvento'
* 
* Ejemplo de uso: 
* $IntEvento = new IntEvento();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntEvento
{

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */  		
	var $eventoId;

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
    * Representación de la columna 'estado' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $estado;

    /**
    * Representación de la columna 'apuestaEstado' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $apuestaEstado;

    /**
    * Representación de la columna 'fecha' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $fecha;

    /**
    * Representación de la columna 'competenciaId' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $competenciaId;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'IntEquipo'
    *
    * @var string
    */  
	var $proveedorId;

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
    * @param String $eventoId id del evento
    *
    * @return no
    * @throws Exception si IntEvento no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($eventoId="")
    {
            if ($eventoId != "") 
            {

                $IntEventoMySqlDAO = new IntEventoMySqlDAO();

                $IntEvento = $IntEventoMySqlDAO->load($eventoId);

                $this->success = false;

                if ($IntEvento != null && $IntEvento != "") 
                {
                    $this->eventoId = $IntEvento->eventoId;
                    $this->nombre = $IntEvento->nombre;
                    $this->nombreTraduccion = $IntEvento->nombreTraduccion;
                    $this->nombreInternacional = $IntEvento->nombreInternacional;
                    $this->estado = $IntEvento->estado;
                    $this->apuestaEstado = $IntEvento->apuestaEstado;
                    $this->fecha = $IntEvento->fecha;
                    $this->competenciaId = $IntEvento->competenciaId;
                    $this->proveedorId = $IntEvento->proveedorId;
                    $this->usucreaId = $IntEvento->usucreaId;
                    $this->usumodifId = $IntEvento->usumodifId;
                    $this->fechaCrea = $IntEvento->fechaCrea;
                    $this->fechaModif = $IntEvento->fechaModif;
                    $this->success = true;
                }
                else 
                {
                    throw new Exception("No existe " . get_class($this), "22");
                }
                
            }

    }

    /**
    * Realizar una consulta en la tabla de detalles de equipos 'IntEvento'
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
    * @throws Exception si los eventos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getEventosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $IntEventoMySqlDAO = new IntEventoMySqlDAO();

        $eventos = $IntEventoMySqlDAO->queryEventosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($eventos != null && $eventos != "") 
        {
            return $eventos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }



    }
?>