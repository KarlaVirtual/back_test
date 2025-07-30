<?php namespace Backend\dto;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Exception;
/**
* Clase 'DatosProveedore'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'DatosProveedore'
*
* Ejemplo de uso:
* $DatosProveedore = new DatosProveedore();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class DocumentoUsuario
{

    /**
    * Representación de la columna 'docusuarioId' de la tabla 'DocumentoUsuario'
    *
    * @var string
    */
	var $docusuarioId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'DocumentoUsuario'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'documentoId' de la tabla 'DocumentoUsuario'
    *
    * @var string
    */
    var $documentoId;

    /**
    * Representación de la columna 'version' de la tabla 'DocumentoUsuario'
    *
    * @var string
    */
    var $version;

    /**
    * Representación de la columna 'estadoAprobacion' de la tabla 'DocumentoUsuario'
    *
    * @var string
    */
    var $estadoAprobacion;

    /**
     *
     * representacion de la columna fecha_cre de la tabla 'documento_usuario'
     *
     * @var string
     */

    var $fecha_crea;

     /**
     *
     * representacion de la columna fecha_modif de la tabla 'documento_usuario'
     *
     * @var string
     */

     var $fecha_modif;
     var $externalId;



    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId id del usuario
    * @param String $documentoId id del documento
    * @param String $docusuarioId id de la relación documento, usuario
    *
    * @return no
    * @throws Exception si el DocumentoUsuario no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuarioId="",$documentoId="",$docusuarioId="",$externalId="")
    {

        if ($usuarioId != "" && $documentoId != "")
        {

            $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();

            $DocumentoUsuario = $DocumentoUsuarioMySqlDAO->queryByUsuarioIdAndDocumentoId($usuarioId,$documentoId);
            $DocumentoUsuario=$DocumentoUsuario[0];

            if ($DocumentoUsuario != null && $DocumentoUsuario != "")
            {
                    $this->docusuarioId = $DocumentoUsuario->docusuarioId;
                    $this->usuarioId = $DocumentoUsuario->usuarioId;
                    $this->documentoId = $DocumentoUsuario->documentoId;
                    $this->version = $DocumentoUsuario->version;
                    $this->estadoAprobacion = $DocumentoUsuario->estadoAprobacion;
                    $this->fecha_crea = $DocumentoUsuario->fecha_crea;
                    $this->fecha_modif = $DocumentoUsuario->fecha_modif;
                    $this->externalId = $DocumentoUsuario->externalId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "30");
            }
        }
        elseif ($usuarioId != "")
        {

            $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();

            $DocumentoUsuario = $DocumentoUsuarioMySqlDAO->queryByUsuarioId( $usuarioId);
                $DocumentoUsuario=$DocumentoUsuario[0];

            if ($DocumentoUsuario != null && $DocumentoUsuario != "")
            {
                $this->docusuarioId = $DocumentoUsuario->docusuarioId;
                $this->usuarioId = $DocumentoUsuario->usuarioId;
                $this->documentoId = $DocumentoUsuario->documentoId;
                $this->version = $DocumentoUsuario->version;
                $this->estadoAprobacion = $DocumentoUsuario->estadoAprobacion;
                $this->fecha_crea = $DocumentoUsuario->fecha_crea;
                $this->fecha_modif = $DocumentoUsuario->fecha_modif;
                $this->externalId = $DocumentoUsuario->externalId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "30");
            }
        }
        elseif ($docusuarioId != "")
        {

            $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();

            $DocumentoUsuario = $DocumentoUsuarioMySqlDAO->load( $docusuarioId);

            if ($DocumentoUsuario != null && $DocumentoUsuario != "")
            {
                $this->docusuarioId = $DocumentoUsuario->docusuarioId;
                $this->usuarioId = $DocumentoUsuario->usuarioId;
                $this->documentoId = $DocumentoUsuario->documentoId;
                $this->version = $DocumentoUsuario->version;
                $this->estadoAprobacion = $DocumentoUsuario->estadoAprobacion;
                $this->fecha_crea = $DocumentoUsuario->fecha_crea;
                $this->fecha_modif = $DocumentoUsuario->fecha_modif;
                $this->externalId = $DocumentoUsuario->externalId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "30");
            }
        }

        elseif ($externalId != "")
        {

            $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();

            $DocumentoUsuario = $DocumentoUsuarioMySqlDAO->externalId($externalId);

            if ($DocumentoUsuario != null && $DocumentoUsuario != "")
            {
                $this->docusuarioId = $DocumentoUsuario->docusuarioId;
                $this->usuarioId = $DocumentoUsuario->usuarioId;
                $this->documentoId = $DocumentoUsuario->documentoId;
                $this->version = $DocumentoUsuario->version;
                $this->estadoAprobacion = $DocumentoUsuario->estadoAprobacion;
                $this->fecha_crea = $DocumentoUsuario->fecha_crea;
                $this->fecha_modif = $DocumentoUsuario->fecha_modif;
                $this->externalId = $DocumentoUsuario->externalId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "30");
            }
        }
    }


    /**
     * Obtener la información de descarga pertinente al usuario
     *
     * @return String usuarioId usuarioId
     *
     */
    public function getDocumentosNoProcesados($plataforma=0)
    {
        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
        $UsuarioAdminInfo = $DocumentoUsuarioMySqlDAO->queryForDocumentosNoProcesados($this->usuarioId,$plataforma);
        return $UsuarioAdminInfo;
    }

    /**
     * Obtiene los documentos no procesados para PRO.
     *
     * @param int $plataforma Plataforma en la que se buscan los documentos (opcional, por defecto 0).
     * @param mixed $perfilId ID del perfil del usuario.
     * @return array Información de los documentos no procesados.
     */
    public function getDocumentosNoProcesadosPRO($plataforma=0,$perfilId)
    {
        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
        $UsuarioAdminInfo = $DocumentoUsuarioMySqlDAO->queryForDocumentosNoProcesadosPRO($this->usuarioId,$plataforma,$perfilId);
        return $UsuarioAdminInfo;
    }

    /**
     * Obtiene los documentos pendientes por procesar.
     *
     * @param int $plataforma Plataforma en la que se buscan los documentos (opcional, por defecto 0).
     * @param mixed $perfilId ID del perfil del usuario.
     * @return array Información de los documentos pendientes por procesar.
     */
    public function getDocumentosPorProcesar($plataforma=0,$perfilId)
    {
        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
        $UsuarioAdminInfo = $DocumentoUsuarioMySqlDAO->queryForDocumentosPendientesPorProcesar($this->usuarioId,$plataforma,$perfilId);
        return $UsuarioAdminInfo;
    }




    /**
    * Realizar una consulta en la tabla de DocumentoUsuario 'DocumentoUsuario'
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
    public function getDocumentosUsuarioCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();

        $Productos = $DocumentoUsuarioMySqlDAO->queryDocumentosUsuarioCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "")
        {
            return $Productos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param mixed $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }


    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     *
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo documentoId de un objeto
     *
     * @return String documentoId documentoId
     *
     */
    public function getDocumentoId()
    {
        return $this->documentoId;
    }

    /**
     * Modificar el campo 'documentoId' de un objeto
     *
     * @param String $documentoId documentoId
     *
     * @return no
     *
     */
    public function setDocumentoId($documentoId)
    {
        $this->documentoId = $documentoId;
    }

    /**
     * Obtener el campo version de un objeto
     *
     * @return String version version
     *
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Modificar el campo 'version' de un objeto
     *
     * @param String $version version
     *
     * @return no
     *
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Obtener el campo estadoAprobacion de un objeto
     *
     * @return String estadoAprobacion estadoAprobacion
     *
     */
    public function getEstadoAprobacion()
    {
        return $this->estadoAprobacion;
    }

    /**
     * Modificar el campo 'estadoAprobacion' de un objeto
     *
     * @param String $estadoAprobacion estadoAprobacion
     *
     * @return no
     *
     */
    public function setEstadoAprobacion($estadoAprobacion)
    {
        $this->estadoAprobacion = $estadoAprobacion;
    }

    /**
     * Obtener el campo docusuarioId de un objeto
     *
     * @return String docusuarioId docusuarioId
     *
     */
    public function getDocusuarioId()
    {
        return $this->docusuarioId;
    }


    /**
     * Obtiene la fecha de creación del documento del usuario.
     *
     * @return string La fecha de creación del documento.
     */
    public function getFechaCrea(){
        return $this->fecha_crea;
    }

    /**
     * Establecer la fecha de creación del documento.
     *
     * @param string $fecha_crea La fecha de creación a establecer.
     */
    public function setFechaCrea($fecha_crea){
        $this->fecha_crea = $fecha_crea;
    }

    /**
     * Obtiene la fecha de modificación del documento del usuario.
     *
     * @return mixed La fecha de modificación.
     */
    public function getFechaModif(){
        return $this->fecha_modif;
    }

    /**
     * Establece la fecha de modificación del documento del usuario.
     *
     * @param mixed $fecha_modif La nueva fecha de modificación.
     */
    public function setFechaModif($fecha_modif){
        $this->fecha_modif = $fecha_modif;
    }

}

?>