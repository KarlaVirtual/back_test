<?php namespace Backend\dto;
use Backend\mysql\PaisMySqlDAO;
use Exception;
/**
* Clase 'Pais'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Pais'
*
* Ejemplo de uso:
* $Pais = new Pais();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class Pais
{

    /**
    * Representación de la columna 'paisId' de la tabla 'Pais'
    *
    * @var string
    */
	var $paisId;

    /**
    * Representación de la columna 'iso' de la tabla 'Pais'
    *
    * @var string
    */
	var $iso;

    /**
    * Representación de la columna 'paisNom' de la tabla 'Pais'
    *
    * @var string
    */
	var $paisNom;

    /**
    * Representación de la columna 'moneda' de la tabla 'Pais'
    *
    * @var string
    */
	var $moneda;

    /**
    * Representación de la columna 'estado' de la tabla 'Pais'
    *
    * @var string
    */
	var $estado;

    /**
    * Representación de la columna 'utc' de la tabla 'Pais'
    *
    * @var string
    */
	var $utc;

    /**
    * Representación de la columna 'idioma' de la tabla 'Pais'
    *
    * @var string
    */
	var $idioma;

    /**
    * Representación de la columna 'reqCheque' de la tabla 'Pais'
    *
    * @var string
    */
	var $reqCheque;

    /**
    * Representación de la columna 'reqDoc' de la tabla 'Pais'
    *
    * @var string
    */
	var $reqDoc;

    /**
    * Representación de la columna 'codigoPath' de la tabla 'Pais'
    *
    * @var string
    */
	var $codigoPath;

    /**
    * Representación de la columna 'prefijoCelular' de la tabla 'Pais'
    *
    * @var string
    */
    var $prefijoCelular;


    /**
     * Constructor de clase
     *
     *
     * @param String paisId id del pais
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
	public function __construct($paisId="",$iso="")
	{
		if ($paisId != "")
		{


			$PaisMySqlDAO = new PaisMySqlDAO();

			$Pais = $PaisMySqlDAO->load($paisId);


			if ($Pais != null && $Pais != "")
			{

				$this->paisId = $Pais->paisId;
				$this->iso = $Pais->iso;
				$this->paisNom = $Pais->paisNom;
				$this->moneda = $Pais->moneda;
				$this->estado = $Pais->estado;
				$this->utc = $Pais->utc;
				$this->idioma = $Pais->idioma;
				$this->reqCheque = $Pais->reqCheque;
				$this->reqDoc = $Pais->reqDoc;
                $this->codigoPath = $Pais->codigoPath;
                $this->prefijoCelular = $Pais->prefijoCelular;

			} else {
				throw new Exception("No existe " . get_class($this), "30");

			}

		}elseif ($iso != "") {


			$PaisMySqlDAO = new PaisMySqlDAO();

			$Pais = $PaisMySqlDAO->loadbyIso($iso);


			if ($Pais != null && $Pais != "") {

				$this->paisId = $Pais->paisId;
				$this->iso = $Pais->iso;
				$this->paisNom = $Pais->paisNom;
				$this->moneda = $Pais->moneda;
				$this->estado = $Pais->estado;
				$this->utc = $Pais->utc;
				$this->idioma = $Pais->idioma;
				$this->reqCheque = $Pais->reqCheque;
				$this->reqDoc = $Pais->reqDoc;
				$this->codigoPath = $Pais->codigoPath;
				$this->prefijoCelular = $Pais->prefijoCelular;

			}
			else
			{
				throw new Exception("No existe " . get_class($this), "30");
			}

		}


	}

    /**
    * Realizar una consulta en la tabla de paises 'Pais'
    * de una manera personalizada
    *
    *
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si los paises no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function getPaises($sidx,$sord,$start,$limit,$filters,$searchOn,$mandante='',$grouping='')
	{

	    $PaisMysqlDAO = new PaisMysqlDAO();

	    $Paises = $PaisMysqlDAO->queryPaises($sidx,$sord,$start,$limit,$filters,$searchOn,$mandante,$grouping);

	    if ($Paises != null && $Paises != "")
	    {
	        return $Paises;
	    }
	    else
	    {
	        // throw new Exception("No existe " . get_class($this), "01");
	    }

	}




	/**
	 * Realizar una consulta en la tabla de paises 'Pais' con codigos postales
	 * de una manera personalizada
	 *
	 *
	 * @param String $sidx columna para ordenar
	 * @param String $sord orden los datos asc | desc
	 * @param String $start inicio de la consulta
	 * @param String $limit limite de la consulta
	 * @param String $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
	 *
	 * @return Array resultado de la consulta
	 * @throws Exception si los paises no existen
	 *
	 * @access public
	 * @see no
	 * @since no
	 * @deprecated no
	 */
	public function getPaisesCodigosPostales($sidx,$sord,$start,$limit,$filters,$searchOn)
	{

		$PaisMysqlDAO = new PaisMysqlDAO();

		$Paises = $PaisMysqlDAO->queryPaisesCodigosPostales($sidx,$sord,$start,$limit,$filters,$searchOn);

		if ($Paises != null && $Paises != "")
		{
			return $Paises;
		}
		else
		{
			// throw new Exception("No existe " . get_class($this), "01");
		}

	}


	/**
	 * Obtiene una lista personalizada de países según los parámetros proporcionados.
	 *
	 * @param string $sidx Índice de ordenación.
	 * @param string $sord Orden de clasificación (ascendente o descendente).
	 * @param int $start Índice de inicio para la paginación.
	 * @param int $limit Límite de registros a obtener.
	 * @param string $filters Filtros aplicados a la consulta.
	 * @param bool $searchOn Indica si la búsqueda está activada.
	 * @param string $mandante (Opcional) Mandante para filtrar los resultados.
	 * 
	 * @return array|null Lista de países obtenida según los parámetros proporcionados, o null si no se encontraron resultados.
	 */
	public function getPaisesCustom($sidx,$sord,$start,$limit,$filters,$searchOn,$mandante='')
	{

		$PaisMysqlDAO = new PaisMysqlDAO();

		$Paises = $PaisMysqlDAO->queryPaisesCustom($sidx,$sord,$start,$limit,$filters,$searchOn,$mandante);

		if ($Paises != null && $Paises != "") {

			return $Paises;


		}
		else {
			// throw new Exception("No existe " . get_class($this), "01");
		}


	}

	/**
	 * Obtiene una lista de países personalizada según los parámetros proporcionados.
	 *
	 * @param string $sidx El índice de ordenación.
	 * @param string $sord El orden de clasificación (ascendente o descendente).
	 * @param int $start El índice de inicio para la paginación.
	 * @param int $limit El número máximo de registros a devolver.
	 * @param array $filters Filtros aplicados a la consulta.
	 * @param bool $searchOn Indica si la búsqueda está activada.
	 * 
	 * @return array|null Lista de países que coinciden con los criterios de búsqueda, o null si no se encuentran resultados.
	 */
	public function getPaisesCustom2($sidx,$sord,$start,$limit,$filters,$searchOn)
	{

		$PaisMysqlDAO = new PaisMysqlDAO();

		$Paises = $PaisMysqlDAO->queryPaisesCustom2($sidx,$sord,$start,$limit,$filters,$searchOn);

		if ($Paises != null && $Paises != "") {

			return $Paises;


		}
		else {
			// throw new Exception("No existe " . get_class($this), "01");
		}


	}


}
?>
