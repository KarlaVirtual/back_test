<?php namespace Backend\dto;
use Backend\mysql\ChequeMySqlDAO;

/**
* Clase 'Cheque'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'cheque'
* 
* Ejemplo de uso: 
* $Cheque = new Cheque();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Cheque
{

    /**
    * Representación de la columna 'id' de la tabla 'Cheque'
    *
    * @var string
    */  		
	var $id;
	
    /**
    * Representación de la columna 'nroCheque' de la tabla 'Cheque'
    *
    * @var string
    */ 
	var $nroCheque;

    /**
    * Representación de la columna 'paisId' de la tabla 'Cheque'
    *
    * @var string
    */  
	var $paisId;

    /**
    * Representación de la columna 'origen' de la tabla 'Cheque'
    *
    * @var string
    */  
	var $origen;

    /**
    * Representación de la columna 'documentoId' de la tabla 'Cheque'
    *
    * @var string
    */  
	var $documentoId;

    /**
    * Representación de la columna 'mandante' de la tabla 'Cheque'
    *
    * @var string
    */  
	var $mandante;

    /**
    * Representación de la columna 'ticketId' de la tabla 'Cheque'
    *
    * @var string
    */  
	var $ticketId;

    /**
     * Cheque constructor.
     * @param string $id
     */
    public function __construct($id="")
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNroCheque()
    {
        return $this->nroCheque;
    }

    /**
     * @param string $nroCheque
     */
    public function setNroCheque($nroCheque)
    {
        $this->nroCheque = $nroCheque;
    }

    /**
     * @return string
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * @param string $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * @return string
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * @param string $origen
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;
    }

    /**
     * @return string
     */
    public function getDocumentoId()
    {
        return $this->documentoId;
    }

    /**
     * @param string $documentoId
     */
    public function setDocumentoId($documentoId)
    {
        $this->documentoId = $documentoId;
    }

    /**
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param string $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * @return string
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }

    /**
     * @param string $ticketId
     */
    public function setTicketId($ticketId)
    {
        $this->ticketId = $ticketId;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Obtiene una lista de cheques personalizados según los parámetros proporcionados.
     *
     * @param int $usuarioId El ID del usuario.
     * @param string $select La selección de columnas para la consulta.
     * @param string $sidx El índice de ordenación.
     * @param string $sord El orden de clasificación (ascendente o descendente).
     * @param int $start El índice de inicio para la paginación.
     * @param int $limit El número máximo de resultados a devolver.
     * @param string $filters Los filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param int $paisId El ID del país.
     * @return array La lista de cheques personalizados.
     * @throws Exception Si no se encuentran cheques.
     */
    public function getChequesCustom($usuarioId,$select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId)
    {

        $ChequeMySqlDAO = new ChequeMySqlDAO();

        $Productos = $ChequeMySqlDAO->queryChequesCustom($usuarioId,$select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId);

        if ($Productos != null && $Productos != "")
        {
            return $Productos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "111");
        }

    }
}

?>