<?php namespace Backend\dto;
use Backend\mysql\LealtadDetalleMySqlDAO;
use Exception;
/** 
* Clase 'LealtadDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'LealtadDetalle'
* 
* Ejemplo de uso: 
* $LealtadDetalle = new LealtadDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class LealtadDetalle
{

    /**
    * Representación de la columna 'lealtadDetalleId' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $lealtadDetalleId;

    /**
    * Representación de la columna 'lealtadId' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $lealtadId;

    /**
    * Representación de la columna 'tipo' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $tipo;

    /**
    * Representación de la columna 'moneda' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $moneda;

    /**
    * Representación de la columna 'valor' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $valor;
    
    /**
    * Representación de la columna 'valor2' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $valor2;

    /**
    * Representación de la columna 'valor3' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $valor3;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $usucreaId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $fechaCrea;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $usumodifId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'LealtadDetalle'
    *
    * @var string
    */
    public $fechaModif;

    public $descripcion;

    /**
    * Constructor de clase
    *
    *
    * @param String $lealtadDetalleId id de lealtadDetalle
    * @param String $lealtadId id del lealtad
    * @param String $tipo tipo
    * @param String $moneda moneda 
    *
    * @return no
    * @throws Exception si LealtadDetalle no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($lealtadDetalleId="",$lealtadId="",$tipo="",$moneda="")
    {

        if ($lealtadDetalleId != "") 
        {


            $LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();

            $LealtadDetalle = $LealtadDetalleMySqlDAO->load($lealtadDetalleId);


            $this->success = false;

            if ($LealtadDetalle != null && $LealtadDetalle != "") 
            {
                $this->lealtadDetalleId = $LealtadDetalle->lealtadDetalleId;
                $this->lealtadId = $LealtadDetalle->lealtadId;
                $this->valor = $LealtadDetalle->valor;
                $this->valor2 = $LealtadDetalle->valor2;
                $this->valor3 = $LealtadDetalle->valor3;
                $this->tipo = $LealtadDetalle->tipo;
                $this->moneda = $LealtadDetalle->moneda;
                $this->descripcion = $LealtadDetalle->descripcion;

                $this->usucreaId = $LealtadDetalle->usucreaId;
                $this->fechaCrea = $LealtadDetalle->fechaCrea;
                $this->usumodifId = $LealtadDetalle->usumodifId;
                $this->fechaModif = $LealtadDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }
        }
        elseif ($lealtadId != "" AND $tipo!="")
        {
        
            $LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();

            $LealtadDetalle = $LealtadDetalleMySqlDAO->querybyLealtadIdAndTipo($lealtadId,$tipo);

            $LealtadDetalle = $LealtadDetalle[0];

            $this->success = false;

            if ($LealtadDetalle != null && $LealtadDetalle != "") 
            {
                $this->lealtadDetalleId = $LealtadDetalle->lealtadDetalleId;
                $this->lealtadId = $LealtadDetalle->lealtadId;
                $this->valor = $LealtadDetalle->valor;
                $this->valor2 = $LealtadDetalle->valor2;
                $this->valor3 = $LealtadDetalle->valor3;

                $this->tipo = $LealtadDetalle->tipo;
                $this->moneda = $LealtadDetalle->moneda;
                $this->descripcion = $LealtadDetalle->descripcion;

                $this->usucreaId = $LealtadDetalle->usucreaId;
                $this->fechaCrea = $LealtadDetalle->fechaCrea;
                $this->usumodifId = $LealtadDetalle->usumodifId;
                $this->fechaModif = $LealtadDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }

        }
        elseif ($lealtadId != "" AND $tipo!="" AND $moneda!="")
        {
        
            $LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();

            $LealtadDetalle = $LealtadDetalleMySqlDAO->querybyLealtadIdAndTipoAndMoneda($lealtadId,$tipo,$moneda);

            $LealtadDetalle = $LealtadDetalle[0];

            $this->success = false;

            if ($LealtadDetalle != null && $LealtadDetalle != "") 
            {
            
                $this->lealtadDetalleId = $LealtadDetalle->lealtadDetalleId;
                $this->lealtadId = $LealtadDetalle->lealtadId;
                $this->valor = $LealtadDetalle->valor;
                $this->valor2 = $LealtadDetalle->valor2;
                $this->valor3 = $LealtadDetalle->valor3;

                $this->tipo = $LealtadDetalle->tipo;
                $this->moneda = $LealtadDetalle->moneda;

                $this->descripcion = $LealtadDetalle->descripcion;
                $this->usucreaId = $LealtadDetalle->usucreaId;
                $this->fechaCrea = $LealtadDetalle->fechaCrea;
                $this->usumodifId = $LealtadDetalle->usumodifId;
                $this->fechaModif = $LealtadDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }

        }

    }

    /**
    * Realizar una consulta en la tabla de detalles de lealtads 'LealtadDetalle'
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
    * @throws Exception si los lealtads no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getLealtadDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();

        $lealtads = $LealtadDetalleMySqlDAO->queryLealtadDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($lealtads != null && $lealtads != "") 
        {
            return $lealtads;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


}
