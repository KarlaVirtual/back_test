<?php namespace Backend\dto;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Exception;
/** 
* Clase 'ProdMandanteTipo'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ProdMandanteTipo'
* 
* Ejemplo de uso: 
* $ProdMandanteTipo = new ProdMandanteTipo();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProdMandanteTipo
{

    /**
    * Representación de la columna 'prodmandtipoId' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $prodmandtipoId;

    /**
    * Representación de la columna 'tipo' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $tipo;

    /**
    * Representación de la columna 'mandante' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $mandante;

    /**
    * Representación de la columna 'estado' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $estado;

    /**
    * Representación de la columna 'siteId' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $siteId;

    /**
    * Representación de la columna 'siteKey' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $siteKey;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $fechaModif;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $usumodifId;

    /**
    * Representación de la columna 'urlApi' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */ 
	var $urlApi;

    /**
    * Representación de la columna 'contingencia' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */
    var $contingencia;

    /**
    * Representación de la columna 'tipoIntegracion' de la tabla 'ProdMandanteTipo'
    *
    * @var string
    */
    var $tipoIntegracion;


    /**
     * Constructor de clase
     *
     *
     * @param String tipo tipo
     * @param String mandante mandante
     * @param String prodmandtipoId prodmandtipoId
     * @param String siteId siteId
     * @param String siteKey siteKey
     *
     *
    * @return no
    * @throws Exception si ProdMandanteTipo no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function __construct($tipo="", $mandante="", $prodmandtipoId="",$siteId="",$siteKey="")
	{
        if ($siteId != ""&& $siteKey != "") 
        {

            $ProdMandanteTipoMySqlDAO = new ProdMandanteTipoMySqlDAO();

            $ProdMandanteTipo = $ProdMandanteTipoMySqlDAO->queryBySiteAndKey($siteId, $siteKey);
            $ProdMandanteTipo = $ProdMandanteTipo[0];
            
            if ($ProdMandanteTipo != null && $ProdMandanteTipo != "") 
            {
                $this->prodmandtipoId = $ProdMandanteTipo->prodmandtipoId;
                $this->tipo = $ProdMandanteTipo->tipo;
                $this->mandante = $ProdMandanteTipo->mandante;
                $this->estado = $ProdMandanteTipo->estado;
                $this->siteId = $ProdMandanteTipo->siteId;
                $this->siteKey = $ProdMandanteTipo->siteKey;
                $this->urlApi = $ProdMandanteTipo->urlApi;
                $this->usucreaId = $ProdMandanteTipo->usucreaId;
                $this->usumodifId = $ProdMandanteTipo->usumodifId;
                $this->contingencia = $ProdMandanteTipo->contingencia;
                $this->tipoIntegracion = $ProdMandanteTipo->tipoIntegracion;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "61");
            }

        }
        elseif ($siteId != "") 
        {

            $ProdMandanteTipoMySqlDAO = new ProdMandanteTipoMySqlDAO();

            $ProdMandanteTipo = $ProdMandanteTipoMySqlDAO->queryBySite($siteId);
            $ProdMandanteTipo = $ProdMandanteTipo[0];

            if ($ProdMandanteTipo != null && $ProdMandanteTipo != "") 
            {
                $this->prodmandtipoId = $ProdMandanteTipo->prodmandtipoId;
                $this->tipo = $ProdMandanteTipo->tipo;
                $this->mandante = $ProdMandanteTipo->mandante;
                $this->estado = $ProdMandanteTipo->estado;
                $this->siteId = $ProdMandanteTipo->siteId;
                $this->siteKey = $ProdMandanteTipo->siteKey;
                $this->urlApi = $ProdMandanteTipo->urlApi;
                $this->usucreaId = $ProdMandanteTipo->usucreaId;
                $this->usumodifId = $ProdMandanteTipo->usumodifId;
                $this->contingencia = $ProdMandanteTipo->contingencia;
                $this->tipoIntegracion = $ProdMandanteTipo->tipoIntegracion;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "61");
            }

        }
        elseif ($tipo != "" && $mandante != "") 
        {

			$ProdMandanteTipoMySqlDAO = new ProdMandanteTipoMySqlDAO();

			$ProdMandanteTipo = $ProdMandanteTipoMySqlDAO->queryByTipoAndMandante($tipo, $mandante);
			$ProdMandanteTipo = $ProdMandanteTipo[0];

			if ($ProdMandanteTipo != null && $ProdMandanteTipo != "") 
			{
				$this->prodmandtipoId = $ProdMandanteTipo->prodmandtipoId;
				$this->tipo = $ProdMandanteTipo->tipo;
				$this->mandante = $ProdMandanteTipo->mandante;
				$this->estado = $ProdMandanteTipo->estado;
				$this->siteId = $ProdMandanteTipo->siteId;
				$this->siteKey = $ProdMandanteTipo->siteKey;
				$this->urlApi = $ProdMandanteTipo->urlApi;
				$this->usucreaId = $ProdMandanteTipo->usucreaId;
				$this->usumodifId = $ProdMandanteTipo->usumodifId;
                $this->contingencia = $ProdMandanteTipo->contingencia;
                $this->tipoIntegracion = $ProdMandanteTipo->tipoIntegracion;
			}
			else 
			{
				throw new Exception("No existe " . get_class($this), "61");
			}

		}
		elseif ($prodmandtipoId != "") 
		{
			$ProdMandanteTipoMySqlDAO = new ProdMandanteTipoMySqlDAO();

			$ProdMandanteTipo = $ProdMandanteTipoMySqlDAO->load($prodmandtipoId);

			if ($ProdMandanteTipo != null && $ProdMandanteTipo != "") 
			{
				$this->prodmandtipoId = $ProdMandanteTipo->prodmandtipoId;
				$this->tipo = $ProdMandanteTipo->tipo;
				$this->mandante = $ProdMandanteTipo->mandante;
				$this->estado = $ProdMandanteTipo->estado;
				$this->siteId = $ProdMandanteTipo->siteId;
				$this->siteKey = $ProdMandanteTipo->siteKey;
				$this->urlApi = $ProdMandanteTipo->urlApi;
				$this->usucreaId = $ProdMandanteTipo->usucreaId;
				$this->usumodifId = $ProdMandanteTipo->usumodifId;
                $this->contingencia = $ProdMandanteTipo->contingencia;
                $this->tipoIntegracion = $ProdMandanteTipo->tipoIntegracion;
			}
			else 
			{
				throw new Exception("No existe " . get_class($this), "61");
			}
		}

	}

    /**
    * Realizar una consulta en la tabla de ProdMandanteTipo 'ProdMandanteTipo'
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
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProdMandanteTipoMySqlDAO = new ProdMandanteTipoMySqlDAO();

        $Productos = $ProdMandanteTipoMySqlDAO->queryProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }
}
?>