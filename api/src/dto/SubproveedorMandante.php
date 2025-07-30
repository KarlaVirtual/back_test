<?php namespace Backend\dto;
use Backend\mysql\SubproveedorMandanteMySqlDAO;
use Exception;
/** 
* Clase 'SubproveedorMandante'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'SubproveedorMandante'
* 
* Ejemplo de uso: 
* $SubproveedorMandante = new SubproveedorMandante();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class SubproveedorMandante
{

    /**
    * Representación de la columna 'provmandanteId' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $provmandanteId;

    /**
    * Representación de la columna 'subproveedorId' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $subproveedorId;

    /**
    * Representación de la columna 'mandante' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'estado' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $estado;

    /**
    * Representación de la columna 'verifica' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $verifica;

    /**
    * Representación de la columna 'filtroPais' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $filtroPais;

    /**
    * Representación de la columna 'filtroPais' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $fechaModif;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $usumodifId;

    /**
    * Representación de la columna 'max' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $max;

    /**
    * Representación de la columna 'min' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $min;

    /**
    * Representación de la columna 'detalle' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $detalle;

    /**
    * Representación de la columna 'orden' de la tabla 'SubproveedorMandante'
    *
    * @var string
    */
	var $orden;

    /**
    * Constructor de clase
    *
    *
    * @param String $subproveedorId id del proveedor
    * @param String $mandante mandante
    * @param String $provmandanteId provmandanteId
    *
    * @return no
    * @throws Exception si SubproveedorMandante no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function __construct($subproveedorId="", $mandante="", $provmandanteId="")
	{
		if ($subproveedorId != "" && $mandante != "") {

			$SubproveedorMandanteMySqlDAO = new SubproveedorMandanteMySqlDAO();

			$SubproveedorMandante = $SubproveedorMandanteMySqlDAO->queryBySubproveedorIdAndMandante($subproveedorId, $mandante);
			$SubproveedorMandante = $SubproveedorMandante[0];
			if ($SubproveedorMandante != null && $SubproveedorMandante != "") {
				$this->provmandanteId = $SubproveedorMandante->provmandanteId;
				$this->subproveedorId = $SubproveedorMandante->subproveedorId;
				$this->mandante = $SubproveedorMandante->mandante;
				$this->estado = $SubproveedorMandante->estado;
				$this->verifica = $SubproveedorMandante->verifica;
				$this->filtroPais = $SubproveedorMandante->filtroPais;
				$this->max = $SubproveedorMandante->max;
				$this->min = $SubproveedorMandante->min;
				$this->detalle = $SubproveedorMandante->detalle;
				$this->usucreaId = $SubproveedorMandante->usucreaId;
				$this->usumodifId = $SubproveedorMandante->usumodifId;
				$this->orden = $SubproveedorMandante->orden;
			}
			else {
				throw new Exception("No existe " . get_class($this), "107");
			}
		}
		elseif ($provmandanteId != "") {
			$SubproveedorMandanteMySqlDAO = new SubproveedorMandanteMySqlDAO();

			$SubproveedorMandante = $SubproveedorMandanteMySqlDAO->load($provmandanteId);

			if ($SubproveedorMandante != null && $SubproveedorMandante != "") {
				$this->provmandanteId = $SubproveedorMandante->provmandanteId;
				$this->subproveedorId = $SubproveedorMandante->subproveedorId;
				$this->mandante = $SubproveedorMandante->mandante;
				$this->estado = $SubproveedorMandante->estado;
				$this->verifica = $SubproveedorMandante->verifica;
				$this->filtroPais = $SubproveedorMandante->filtroPais;
				$this->max = $SubproveedorMandante->max;
				$this->min = $SubproveedorMandante->min;
				$this->detalle = $SubproveedorMandante->detalle;
				$this->usucreaId = $SubproveedorMandante->usucreaId;
				$this->usumodifId = $SubproveedorMandante->usumodifId;
                $this->orden = $SubproveedorMandante->orden;
			}
			else {
				throw new Exception("No existe " . get_class($this), "107");
			}
		}

	}

    /**
    * Realizar una consulta en la tabla de SubproveedorMandante 'SubproveedorMandante'
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
    public function getSubproveedoresMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $SubproveedorMandanteMySqlDAO = new SubproveedorMandanteMySqlDAO();

        $Productos = $SubproveedorMandanteMySqlDAO->querySubproveedoresMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }
}
?>
