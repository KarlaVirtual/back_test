<?php namespace Backend\dto;
use Backend\mysql\RuletaDetalleMySqlDAO;
use Exception;
/** 
* Clase 'RuletaDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'RuletaDetalle'
* 
* Ejemplo de uso: 
* $RuletaDetalle = new RuletaDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class RuletaDetalle
{

    /**
    * Representación de la columna 'ruletadetalleId' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $ruletadetalleId;

    /**
    * Representación de la columna 'ruletaId' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $ruletaId;

    /**
    * Representación de la columna 'tipo' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $tipo;

    /**
    * Representación de la columna 'moneda' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $moneda;

    /**
    * Representación de la columna 'valor' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $valor;
    
    /**
    * Representación de la columna 'valor2' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $valor2;

    /**
    * Representación de la columna 'valor3' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $valor3;

    public $porcentaje;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $usucreaId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $fechaCrea;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $usumodifId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'RuletaDetalle'
    *
    * @var string
    */
    public $fechaModif;

    public $descripcion;

    /**
    * Constructor de clase
    *
    *
    * @param String $ruletadetalleId id de ruletadetalle
    * @param String $ruletaId id del ruleta
    * @param String $tipo tipo
    * @param String $moneda moneda 
    *
    * @return no
    * @throws Exception si RuletaDetalle no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($ruletadetalleId="",$ruletaId="",$tipo="",$moneda="")
    {

        if ($ruletadetalleId != "") 
        {


            $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();

            $RuletaDetalle = $RuletaDetalleMySqlDAO->load($ruletadetalleId);


            $this->success = false;

            if ($RuletaDetalle != null && $RuletaDetalle != "") 
            {
                $this->ruletadetalleId = $RuletaDetalle->ruletadetalleId;
                $this->ruletaId = $RuletaDetalle->ruletaId;
                $this->valor = $RuletaDetalle->valor;
                $this->valor2 = $RuletaDetalle->valor2;
                $this->valor3 = $RuletaDetalle->valor3;
                $this->porcentaje = $RuletaDetalle->porcentaje;
                $this->tipo = $RuletaDetalle->tipo;
                $this->moneda = $RuletaDetalle->moneda;
                $this->descripcion = $RuletaDetalle->descripcion;

                $this->usucreaId = $RuletaDetalle->usucreaId;
                $this->fechaCrea = $RuletaDetalle->fechaCrea;
                $this->usumodifId = $RuletaDetalle->usumodifId;
                $this->fechaModif = $RuletaDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }
        }
        elseif ($ruletaId != "" AND $tipo!="")
        {
        
            $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();

            $RuletaDetalle = $RuletaDetalleMySqlDAO->querybyRuletaIdAndTipo($ruletaId,$tipo);

            $RuletaDetalle = $RuletaDetalle[0];

            $this->success = false;

            if ($RuletaDetalle != null && $RuletaDetalle != "") 
            {
                $this->ruletadetalleId = $RuletaDetalle->ruletadetalleId;
                $this->ruletaId = $RuletaDetalle->ruletaId;
                $this->valor = $RuletaDetalle->valor;
                $this->valor2 = $RuletaDetalle->valor2;
                $this->valor3 = $RuletaDetalle->valor3;
                $this->porcentaje = $RuletaDetalle->porcentaje;
                $this->tipo = $RuletaDetalle->tipo;
                $this->moneda = $RuletaDetalle->moneda;
                $this->descripcion = $RuletaDetalle->descripcion;

                $this->usucreaId = $RuletaDetalle->usucreaId;
                $this->fechaCrea = $RuletaDetalle->fechaCrea;
                $this->usumodifId = $RuletaDetalle->usumodifId;
                $this->fechaModif = $RuletaDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }

        }
        elseif ($ruletaId != "" AND $tipo!="" AND $moneda!="")
        {
        
            $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();

            $RuletaDetalle = $RuletaDetalleMySqlDAO->querybyRuletaIdAndTipoAndMoneda($ruletaId,$tipo,$moneda);

            $RuletaDetalle = $RuletaDetalle[0];

            $this->success = false;

            if ($RuletaDetalle != null && $RuletaDetalle != "") 
            {
            
                $this->ruletadetalleId = $RuletaDetalle->ruletadetalleId;
                $this->ruletaId = $RuletaDetalle->ruletaId;
                $this->valor = $RuletaDetalle->valor;
                $this->valor2 = $RuletaDetalle->valor2;
                $this->valor3 = $RuletaDetalle->valor3;
                $this->porcentaje = $RuletaDetalle->porcentaje;
                $this->tipo = $RuletaDetalle->tipo;
                $this->moneda = $RuletaDetalle->moneda;

                $this->descripcion = $RuletaDetalle->descripcion;
                $this->usucreaId = $RuletaDetalle->usucreaId;
                $this->fechaCrea = $RuletaDetalle->fechaCrea;
                $this->usumodifId = $RuletaDetalle->usumodifId;
                $this->fechaModif = $RuletaDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }

        }

    }

    /**
    * Realizar una consulta en la tabla de detalles de ruletas 'RuletaDetalle'
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
    * @throws Exception si los ruletas no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getRuletaDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();

        $ruletas = $RuletaDetalleMySqlDAO->queryRuletaDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($ruletas != null && $ruletas != "") 
        {
            return $ruletas;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


    /**
     * Obtiene los detalles personalizados de la ruleta.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento de resultados.
     * 
     * @return array|null Resultados de la consulta.
     * @throws Exception Si no existen resultados.
     */
    public function getRuletaDetallesCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();

        $ruletas = $RuletaDetalleMySqlDAO->queryRuletaDetallesCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($ruletas != null && $ruletas != "")
        {
            return $ruletas;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    
/**
     * Obtiene los detalles personalizados de la ruleta.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento de resultados.
     *
     * @return array|null Resultados de la consulta.
     * @throws Exception Si no existen resultados.
     */
    public function getRuletaDetallesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();

        $ruletas = $RuletaDetalleMySqlDAO->queryRuletaDetallesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($ruletas != null && $ruletas != "")
        {
            return $ruletas;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }
}
