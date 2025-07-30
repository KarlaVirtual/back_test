<?php namespace Backend\dto;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Exception;
/** 
* Clase 'ProveedorMandante'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ProveedorMandante'
* 
* Ejemplo de uso: 
* $ProveedorMandante = new ProveedorMandante();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProveedorMandante
{

    /**
    * Representación de la columna 'provmandanteId' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $provmandanteId;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $proveedorId;

    /**
    * Representación de la columna 'mandante' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'estado' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $estado;

    /**
    * Representación de la columna 'verifica' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $verifica;

    /**
    * Representación de la columna 'filtroPais' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $filtroPais;

    /**
    * Representación de la columna 'filtroPais' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $fechaModif;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $usumodifId;

    /**
    * Representación de la columna 'max' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $max;

    /**
    * Representación de la columna 'min' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $min;

    /**
    * Representación de la columna 'detalle' de la tabla 'ProveedorMandante'
    *
    * @var string
    */
	var $detalle;

    /**
    * Constructor de clase
    *
    *
    * @param String $proveedorId id del proveedor
    * @param String $mandante mandante
    * @param String $provmandanteId provmandanteId
    *
    * @return no
    * @throws Exception si ProveedorMandante no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function __construct($proveedorId="", $mandante="", $provmandanteId="")
	{
		if ($proveedorId != "" && $mandante != "") {

			$ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO();

			$ProveedorMandante = $ProveedorMandanteMySqlDAO->queryByProveedorIdAndMandante($proveedorId, $mandante);
			$ProveedorMandante = $ProveedorMandante[0];
			if ($ProveedorMandante != null && $ProveedorMandante != "") {
				$this->provmandanteId = $ProveedorMandante->provmandanteId;
				$this->proveedorId = $ProveedorMandante->proveedorId;
				$this->mandante = $ProveedorMandante->mandante;
				$this->estado = $ProveedorMandante->estado;
				$this->verifica = $ProveedorMandante->verifica;
				$this->filtroPais = $ProveedorMandante->filtroPais;
				$this->max = $ProveedorMandante->max;
				$this->min = $ProveedorMandante->min;
				$this->detalle = $ProveedorMandante->detalle;
				$this->usucreaId = $ProveedorMandante->usucreaId;
				$this->usumodifId = $ProveedorMandante->usumodifId;
			}
			else {
				throw new Exception("No existe " . get_class($this), "27");
			}
		}
		elseif ($provmandanteId != "") {
			$ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO();

			$ProveedorMandante = $ProveedorMandanteMySqlDAO->load($provmandanteId);

			if ($ProveedorMandante != null && $ProveedorMandante != "") {
				$this->provmandanteId = $ProveedorMandante->provmandanteId;
				$this->proveedorId = $ProveedorMandante->proveedorId;
				$this->mandante = $ProveedorMandante->mandante;
				$this->estado = $ProveedorMandante->estado;
				$this->verifica = $ProveedorMandante->verifica;
				$this->filtroPais = $ProveedorMandante->filtroPais;
				$this->max = $ProveedorMandante->max;
				$this->min = $ProveedorMandante->min;
				$this->detalle = $ProveedorMandante->detalle;
				$this->usucreaId = $ProveedorMandante->usucreaId;
				$this->usumodifId = $ProveedorMandante->usumodifId;
			}
			else {
				throw new Exception("No existe " . get_class($this), "27");
			}
		}

	}

    /**
    * Realizar una consulta en la tabla de ProveedorMandante 'ProveedorMandante'
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
    public function getProveedoresMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO();

        $Productos = $ProveedorMandanteMySqlDAO->queryProveedoresMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }
}
?>