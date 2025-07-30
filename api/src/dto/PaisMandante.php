<?php namespace Backend\dto;
use Backend\mysql\PaisMandanteMySqlDAO;
use Exception;
/**
* Clase 'PaisMandante'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'PaisMandante'
*
* Ejemplo de uso:
* $PaisMandante = new PaisMandante();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class PaisMandante
{

    /**
    * Representación de la columna 'paismandanteId' de la tabla 'PaisMandante'
    *
    * @var string
    */
	var $paismandanteId;

    /**
    * Representación de la columna 'mandante' de la tabla 'PaisMandante'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'paisId' de la tabla 'PaisMandante'
    *
    * @var string
    */
	var $paisId;

    /**
    * Representación de la columna 'estado' de la tabla 'PaisMandante'
    *
    * @var string
    */
	var $estado;

	/**
	 * Representación de la columna 'fechaCrea' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
    var $fechaCrea;

	/**
	 * Representación de la columna 'fechaModif' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $fechaModif;


	/**
	 * Representación de la columna 'usucreaId' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $usucreaId;

	/**
	 * Representación de la columna 'usumodifId' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $usumodifId;


	/**
	 * Representación de la columna 'trm_nio' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmNio;

	/**
	 * Representación de la columna 'trm_mxn' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmMxn;

	/**
	 * Representación de la columna 'trm_pen' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmPen;

	/**
	 * Representación de la columna 'trm_brl' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmBrl;

	/**
	 * Representación de la columna 'trm_clp' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmClp;

	/**
	 * Representación de la columna 'trm_crc' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmCrc;


	/**
	 * Representación de la columna 'trm_usd' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmUsd;

	/**
	 * Representación de la columna 'trm_gtq' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmGtq;

	/**
	 * Representación de la columna 'trm_gyd' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmGyd;

	/**
	 * Representación de la columna 'trm_jmd' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmJmd;

	/**
	 * Representación de la columna 'trm_ves' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $trmVes;

	/**
	 * Representación de la columna 'url_skinitainment' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $urlSkinitainment;

	/**
	 * Representación de la columna 'skin_itainment' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $skinItainment;

	/**
	 * Representación de la columna 'moneda' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $moneda;

	/**
	 * Representación de la columna 'base_url' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $baseUrl;

	/**
	 * Representación de la columna 'email_noreply' de la tabla 'PaisMandante'
	 *
	 * @var string
	 */
	var $emailNoreply;


	/**
     * Constructor de clase
     *
     *
     * @param String paismandanteId id del pais
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
	public function __construct($paismandanteId="",$mandante="",$paisId="")
	{
		if ($paismandanteId != "")
		{


			$PaisMandanteMySqlDAO = new PaisMandanteMySqlDAO();

			$PaisMandante = $PaisMandanteMySqlDAO->load($paismandanteId);


			if ($PaisMandante != null && $PaisMandante != "")
			{

				$this->paismandanteId = $PaisMandante->paismandanteId;
				$this->mandante = $PaisMandante->mandante;
				$this->paisId = $PaisMandante->paisId;
				$this->estado = $PaisMandante->estado;
				$this->fechaCrea = $PaisMandante->fechaCrea;
				$this->fechaModif = $PaisMandante->fechaModif;
				$this->usucreaId = $PaisMandante->usucreaId;
				$this->usumodifId = $PaisMandante->usumodifId;
				$this->trmNio = $PaisMandante->trmNio;
				$this->trmMxn = $PaisMandante->trmMxn;
				$this->trmPen = $PaisMandante->trmPen;
				$this->trmBrl = $PaisMandante->trmBrl;
				$this->trmClp = $PaisMandante->trmClp;
				$this->trmCrc = $PaisMandante->trmCrc;
				$this->trmUsd = $PaisMandante->trmUsd;
				$this->trmGtq = $PaisMandante->trmGtq;
				$this->trmGyd = $PaisMandante->trmGyd;
				$this->trmJmd = $PaisMandante->trmJmd;
				$this->trmVes = $PaisMandante->trmVes;
				$this->urlSkinitainment = $PaisMandante->urlSkinitainment;
				$this->skinItainment = $PaisMandante->skinItainment;
				$this->moneda = $PaisMandante->moneda;
				$this->baseUrl = $PaisMandante->baseUrl;
				$this->emailNoreply = $PaisMandante->emailNoreply;



			} else {
				throw new Exception("No existe " . get_class($this), "30");

			}

		}elseif ($mandante != "" && $paisId != "") {

			$PaisMandanteMySqlDAO = new PaisMandanteMySqlDAO();

			$PaisMandante = $PaisMandanteMySqlDAO->loadByMandanteAndPaisId($mandante,$paisId);

			if ($PaisMandante != null && $PaisMandante != "") {

				$this->paismandanteId = $PaisMandante->paismandanteId;
				$this->mandante = $PaisMandante->mandante;
				$this->paisId = $PaisMandante->paisId;
				$this->estado = $PaisMandante->estado;
				$this->fechaCrea = $PaisMandante->fechaCrea;
				$this->fechaModif = $PaisMandante->fechaModif;
				$this->usucreaId = $PaisMandante->usucreaId;
				$this->usumodifId = $PaisMandante->usumodifId;
				$this->trmNio = $PaisMandante->trmNio;
				$this->trmMxn = $PaisMandante->trmMxn;
				$this->trmPen = $PaisMandante->trmPen;
				$this->trmBrl = $PaisMandante->trmBrl;
				$this->trmClp = $PaisMandante->trmClp;
				$this->trmCrc = $PaisMandante->trmCrc;
				$this->trmUsd = $PaisMandante->trmUsd;
				$this->trmGtq = $PaisMandante->trmGtq;
				$this->trmGyd = $PaisMandante->trmGyd;
				$this->trmJmd = $PaisMandante->trmJmd;
				$this->trmVes = $PaisMandante->trmVes;
				$this->urlSkinitainment = $PaisMandante->urlSkinitainment;
				$this->skinItainment = $PaisMandante->skinItainment;
				$this->moneda = $PaisMandante->moneda;
				$this->baseUrl = $PaisMandante->baseUrl;
				$this->emailNoreply = $PaisMandante->emailNoreply;

			}
			else
			{
				throw new Exception("No existe " . get_class($this), "30");
			}

		}elseif ($mandante != "" ) {

			$PaisMandanteMySqlDAO = new PaisMandanteMySqlDAO();

			$PaisMandante = $PaisMandanteMySqlDAO->loadByMandante($mandante);
			$PaisMandante=$PaisMandante[0];

			if ($PaisMandante != null && $PaisMandante != "") {

				$this->paismandanteId = $PaisMandante->paismandanteId;
				$this->mandante = $PaisMandante->mandante;
				$this->paisId = $PaisMandante->paisId;
				$this->estado = $PaisMandante->estado;
				$this->fechaCrea = $PaisMandante->fechaCrea;
				$this->fechaModif = $PaisMandante->fechaModif;
				$this->usucreaId = $PaisMandante->usucreaId;
				$this->usumodifId = $PaisMandante->usumodifId;
				$this->trmNio = $PaisMandante->trmNio;
				$this->trmMxn = $PaisMandante->trmMxn;
				$this->trmPen = $PaisMandante->trmPen;
				$this->trmBrl = $PaisMandante->trmBrl;
				$this->trmClp = $PaisMandante->trmClp;
				$this->trmCrc = $PaisMandante->trmCrc;
				$this->trmUsd = $PaisMandante->trmUsd;
				$this->trmGtq = $PaisMandante->trmGtq;
				$this->trmGyd = $PaisMandante->trmGyd;
				$this->trmJmd = $PaisMandante->trmJmd;
				$this->trmVes = $PaisMandante->trmVes;
				$this->urlSkinitainment = $PaisMandante->urlSkinitainment;
				$this->skinItainment = $PaisMandante->skinItainment;
				$this->moneda = $PaisMandante->moneda;
				$this->baseUrl = $PaisMandante->baseUrl;
				$this->emailNoreply = $PaisMandante->emailNoreply;

			}
			else
			{
				throw new Exception("No existe " . get_class($this), "30");
			}

		}


	}

	/**
	 * Obtiene el mandante del objeto PaisMandante
	 * @return string
	 */
	public function getMandante()
	{
		return $this->mandante;
	}

	/**
	 * Establece el mandante del objeto PaisMandante
	 * @param string $mandante
	 */
	public function setMandante($mandante)
	{
		$this->mandante = $mandante;
	}

	/**
	 * Obtiene el paisId del objeto PaisMandante
	 * @return string
	 */
	public function getPaisId()
	{
		return $this->paisId;
	}

	/**
	 * Establece el paisId del objeto PaisMandante
	 * @param string $paisId
	 */
	public function setPaisId($paisId)
	{
		$this->paisId = $paisId;
	}

	/**
	 * Obtiene el estado del objeto PaisMandante
	 * @return string
	 */
	public function getEstado()
	{
		return $this->estado;
	}

	/**
	 * Establece el estado del objeto PaisMandante
	 * @param string $estado
	 */
	public function setEstado($estado)
	{
		$this->estado = $estado;
	}

	/**
	 * Obtiene el paisMandante del objeto PaisMandante 
	 * @return string
	 */
	public function getPaismandanteId()
	{
		return $this->paismandanteId;
	}

	/**
	 * Obtiene la UrlSkinitainment del objeto PaisMandante
	 * @return mixed
	 */
	public function getUrlSkinitainment()
	{
		return $this->urlSkinitainment;
	}

	/**
	 * Establece la UrlSkinitainment del objeto PaisMandante
	 * @param mixed $urlSkinitainment
	 */
	public function setUrlSkinitainment($urlSkinitainment)
	{
		$this->urlSkinitainment = $urlSkinitainment;
	}

	/**
	 * Obtiene el skinItainment del objeto PaisMandante
	 * @return mixed
	 */
	public function getSkinItainment()
	{
		return $this->skinItainment;
	}

	/**
	 * Establece el skinItainment del objeto PaisMandante
	 * @param mixed $skinItainment
	 */
	public function setSkinItainment($skinItainment)
	{
		$this->skinItainment = $skinItainment;
	}

	/**
	 * Obtiene la moneda del objeto PaisMandante
	 * @return mixed
	 */
	public function getMoneda()
	{
		return $this->moneda;
	}

	/**
	 * Establece la moneda del objeto PaisMandante
	 * @param mixed $moneda
	 */
	public function setMoneda($moneda)
	{
		$this->moneda = $moneda;
	}

	/**
	 * Obtiene la baseUrl del objeto PaisMandante
	 * @return mixed
	 */

	public function getBaseUrl()
	{
		return $this->baseUrl;
	}

	/**
	 * Establece la baseUrl del objeto PaisMandante
	 * @param mixed $baseUrl
	 */
	public function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}


	/**
	 * Obtiene la baseUrl del objeto PaisMandante
	 * @return mixed
	 */

	public function getEmailNoreply()
	{
		return $this->emailNoreply;
	}

	/**
	 * Establece la baseUrl del objeto PaisMandante
	 * @param mixed $emailNoreply
	 */
	public function setEmailNoreply($emailNoReply)
	{
		$this->emailNoreply = $emailNoReply;
	}


    /**
    * Realizar una consulta en la tabla de paises 'PaisMandante'
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
	public function getPaisMandantes($sidx,$sord,$start,$limit,$filters,$searchOn)
	{

	    $PaisMandanteMysqlDAO = new PaisMandanteMysqlDAO();

	    $PaisMandantes = $PaisMandanteMysqlDAO->queryPaisMandantes($sidx,$sord,$start,$limit,$filters,$searchOn);

	    if ($PaisMandantes != null && $PaisMandantes != "")
	    {
	        return $PaisMandantes;
	    }
	    else
	    {
	        // throw new Exception("No existe " . get_class($this), "01");
	    }

	}


	/**
	 * Obtiene una lista personalizada de Países Mandantes.
	 *
	 * @param string $select Campos a seleccionar en la consulta.
	 * @param string $sidx Índice de ordenación.
	 * @param string $sord Orden de clasificación (ascendente o descendente).
	 * @param int $start Posición inicial para la consulta.
	 * @param int $limit Número máximo de registros a devolver.
	 * @param array $filters Filtros a aplicar en la consulta.
	 * @param bool $searchOn Indica si la búsqueda está activada.
	 * @return array|null Lista de Países Mandantes o null si no se encuentran resultados.
	 */
	public function getPaisMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
	{

		$PaisMandanteMysqlDAO = new PaisMandanteMysqlDAO();

		$PaisMandantes = $PaisMandanteMysqlDAO->queryPaisMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

		if ($PaisMandantes != null && $PaisMandantes != "") {

			return $PaisMandantes;


		}
		else {
			// throw new Exception("No existe " . get_class($this), "01");
		}


	}


	/**
	 * Obtiene una lista de Países Mandantes personalizada según los parámetros dados.
	 *
	 * @param string $select Campos a seleccionar en la consulta.
	 * @param string $sidx Índice de ordenación.
	 * @param string $sord Orden de clasificación (ascendente o descendente).
	 * @param int $start Posición inicial para la consulta.
	 * @param int $limit Número máximo de registros a devolver.
	 * @param string $filters Filtros a aplicar en la consulta.
	 * @param bool $searchOn Indica si la búsqueda está activada.
	 * 
	 * @return array|null Lista de Países Mandantes si existen, de lo contrario null.
	 */
	public function getPaisMandantesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
	{

		$PaisMandanteMysqlDAO = new PaisMandanteMysqlDAO();

		$PaisMandantes = $PaisMandanteMysqlDAO->queryPaisMandantesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

		if ($PaisMandantes != null && $PaisMandantes != "") {

			return $PaisMandantes;


		}
		else {
			// throw new Exception("No existe " . get_class($this), "01");
		}


	}

	/** Verifica si hay un programa de referidos activo por parte del pais_mandante
	 * @throws Exception Lanza error 4008 si marca país no cuenta con programa de referidos o el programa está inactivo.
	 *@return int Retorna 1 si marca pais sí cuenta con programa de referidos.
	 */
	public function progReferidosDisponible() {
		if(($this->getMandante() == null || $this->getMandante() == "") && ($this->getPaisId() == null || $this->getPaisId() == "")) {
            //ERROR PaisMandante NO cuenta con los campos mandante y pais_id necesarios para realizar la consulta
			throw new Exception("Pais o mandante con valor nulo, no fue posible consultar disponibilidad del programa referidos", 4001);
		}

        //Verificando si PaisMandante cuenta con un programa de referidos activa
		$Clasificador = new Clasificador("", "ACEPTAREFERIDO");
		try {
            $MandanteDetalle = new MandanteDetalle("", $this->getMandante(), $Clasificador->getClasificadorId(), $this->getPaisId(), "A");
		}
        catch (Exception $e) {
			if($e->getCode() != 34) throw $e;
            //Programa referidos inexistente para el PaisMandante
            throw new Exception('Programa de referidos no disponible', 4008);
		}

        if(!$MandanteDetalle->getValor()) {
			//Programa referidos existente e inactivo
			throw new Exception('Programa de referidos existente pero inactivo', 4019);
        }
		//Programa referidos existente y activo
		return 1;
    }


	/** Genera una URL funcional que apunta a la landing o página de registro del PaisMandante cargado en el objeto
	 *concatenado con el link o identificador de un usuario referente
	 *
	 *@param UsuarioOtrainfo
	 *@return String Url página de registro con el link de un referente
	 */
	public function getUrlReferentePersonalizado(UsuarioOtrainfo $UsuarioOtrainfo, String $identificadorMensaje = '') {
		if(!$this->paismandanteId) {
			throw new Exception('Objeto ' . get_class($this) . ' no presenta un paisMandanteId definido', 4007);
		}
		$Clasificador = new Clasificador('', 'URLLANDING');
		$MandanteDetalle = new MandanteDetalle("", $this->getMandante(), $Clasificador->getClasificadorId(), $this->getPaisId(), 'A');
		$urlBase = $MandanteDetalle->getValor();
		$url = $urlBase . '?rfdo=' . $UsuarioOtrainfo->generarLinkReferente($identificadorMensaje);
		return $url;
	}



	/** Verifica que la marca país del link de referido corresponda con la marca país del nuevo usuario
	 * @param Usuario $Usuario El nuevo usuario en proceso de registro
	 * @param string $linkReferido El link enviado desde el programa de referidos
	 * @throws Exception Lanza error 4013 si link de referido no es compatible con el sitio de registro
	 * @return int usuarioId del referente o dueño del link
	 */
	public function validarLinkReferenteCompatible(Usuario $nuevoUsuario, string $linkReferente) {
		$UsuarioOtrainfo = new UsuarioOtrainfo();
		$linkInfo = $UsuarioOtrainfo->identificarLinkReferente($linkReferente);
		$usuidReferente = $linkInfo['usuarioId'];
		try {
			$Usuario = new Usuario($usuidReferente);
		}
		catch(Exception $e) {
			if($e->getCode() == 24) Throw new Exception('Link de registro no pertenece a ningún usuario', 4015);
			Throw $e;
		}

		$linkCompatible = intval(($Usuario->paisId == $nuevoUsuario->paisId) && ($Usuario->mandante == $nuevoUsuario->mandante));
		if(!$linkCompatible){
				throw new Exception('Link del programa referidos no compatible con sitio de registro', 4013);
		}
		return $usuidReferente;
	}
}
?>
