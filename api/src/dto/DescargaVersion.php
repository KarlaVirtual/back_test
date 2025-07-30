<?php

namespace Backend\dto;

use Backend\mysql\DescargaVersionMySqlDAO;

use Exception;

/**
 * clase descarga version
 * 
 * esta clase provee una manera de instaciar a la tabla descarga_Version
 * 
 * ejemplo de uso:
 * 
 * $descarga_version = new descarga_version();
 * 
 * @package ninguno 
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public 
 * @see no
 * 
 * 
 */

class DescargaVersion
{
    /**
     * representacion de la columna descarga_version_id de la tabla descarga_version
     * @var string
     */
    var $descargaVersionId;

    /**
     * 
     * representacion de la columna usuario_id
     * @var string
     * 
     * 
     */

    var $usuarioId;

    /**
     * 
     * representacion de la columna documento_id
     * @var string
     * 
     * 
     */

    var $documentoId;


    /**
     * 
     * representacion de la columna version
     * @var string
     * 
     * 
     */

    var $version;


    /**
     * 
     * representacion de la columna fecha_crea
     * @var string
     * 
     * 
     */

    var $fechaCrea;

    /**
     * 
     * representacion de la columna fecha_modif
     * @var string
     * 
     * 
     */

    var $fechaModif;


    /**
     * 
     *representacion de la columna url
     * @var string
     *
     *
     */

    var $url;

    /**
     * 
     * representacion de la columna encriptacion
     * @var string
     * 
     * 
     * 
     */

    var $encriptacion;

    /**
     * Constructor de la clase DescargaVersion.
     *
     * @param string $descargaVersionId ID de la versión de descarga.
     * @param string $usuarioId ID del usuario.
     * @param string $version Versión del documento.
     * @param string $documentoId ID del documento.
     * 
     * @throws Exception Si no se encuentra la versión de descarga correspondiente.
     */
    public function __construct($descargaVersionId = "", $usuarioId = "", $version = "", $documentoId = "")
    {
        if ($descargaVersionId != "") {
            $DescargaVersionMySqlDAO = new DescargaVersionMySqlDAO();
            $descargaVersion = $DescargaVersionMySqlDAO->load($descargaVersionId);
            if($descargaVersion != "" and $descargaVersionId != null){
                $this->descargaVersionId = $descargaVersion->descargaVersionId;
                $this->usuarioId = $descargaVersion->usuarioId;
                $this->documentoId = $descargaVersion->documentoId;
                $this->version = $descargaVersion->version;
                $this->fechaCrea = $descargaVersion->fechaCrea;
                $this->fechaModif = $descargaVersion->fechaModif;
                $this->url = $descargaVersion->url;
                $this->encriptacion = $descargaVersion->encriptacion;

            }else{
                throw new Exception("No existe".get_class($this),"30");
            }
        }else if ($usuarioId != ""){
            $DescargaVersionMySqlDAO = new DescargaVersionMySqlDAO();

            $descargaV = $DescargaVersionMySqlDAO->queryByusuario($usuarioId);
            $descargaV =$descargaV[0];

            if($descargaV != null and $descargaV != ""){
                $this->descargaVersionId = $descargaV->descargaVersionId;
                $this->usuarioId = $descargaV->usuarioId;
                $this->documentoId = $descargaV->documentoId;
                $this->version = $descargaV->version;
                $this->fechaCrea = $descargaV->fechaCrea;
                $this->fechaModif = $descargaV->fechaModif;
                $this->url = $descargaV->url;
                $this->encriptacion = $descargaV->encriptacion;
            }else{
                throw new Exception("No existe".get_class($this),"30");
            }
        
        }else if ($version != "" && $documentoId != ""){
            $DescargaVersionMySqlDAO = new DescargaVersionMySqlDAO();

            $descargaV = $DescargaVersionMySqlDAO->queryByDocumentAndVersion($documentoId,$version);
            $descargaV =$descargaV[0];

            if($descargaV != null and $descargaV != ""){
                $this->descargaVersionId = $descargaV->descarga_version_id;
                $this->usuarioId = $descargaV->usuarioId;
                $this->documentoId = $descargaV->documentoId;
                $this->version = $descargaV->version;
                $this->fechaCrea = $descargaV->fechaCrea;
                $this->fechaModif = $descargaV->fechaModif;
                $this->url = $descargaV->url;
                $this->encriptacion = $descargaV->encriptacion;
            }else{
                throw new Exception("No existe".get_class($this),30);
            }


        }else if ($version != ""){
            $DescargaVersionMySqlDAO = new DescargaVersionMySqlDAO();

            $descargaV = $DescargaVersionMySqlDAO->queryByVersion($version);
            $descargaV =$descargaV[0];

            if($descargaV != null and $descargaV != ""){
                $this->descargaVersionId = $descargaV->descargaVersionId;
                $this->usuarioId = $descargaV->usuarioId;
                $this->documentoId = $descargaV->documentoId;
                $this->version = $descargaV->version;
                $this->fechaCrea = $descargaV->fechaCrea;
                $this->fechaModif = $descargaV->fechamodif;
                $this->url = $descargaV->url;
                $this->encriptacion = $descargaV->encriptacion;
            }else{
                throw new Exception("No existe".get_class($this),30);
            }


        }else if ($documentoId = ""){
            $DescargaVersionMySqlDAO = new DescargaVersionMySqlDAO();
            $descargaV = $DescargaVersionMySqlDAO->queryBydocument($documentoId);
            $descargaV[0];

           if($descargaV != "" and $descargaV != null){
                $this->descargaVersionId = $descargaV->descargaVersionId;
                $this->usuarioId = $descargaV->usuarioId;
                $this->documentoId = $descargaV->documentoId;
                $this->version = $descargaV->version;
                $this->fechaCrea = $descargaV->fechaCrea;
                $this->fechaModif = $descargaV->fechaModif;
                $this->url = $descargaV->url;
                $this->encriptacion = $descargaV->encriptacion;
           }else{
                throw new Exception("No existe".get_class($this),30);
            }
        }
    }


    /**
     * Obtiene el conjunto de versiones descarga personalizadas basada en los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param string $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $grouping Indica el criterio de agrupación.
     * @return object Datos obtenidos de la consulta.
     * @throws Exception Si no se encuentran datos.
     */
    public function getdescargaVersionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping)
    {


        $descargaVersionMysqlDAO = new DescargaVersionMySqlDAO();

        $datos = $descargaVersionMysqlDAO->queryDescargaVersionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($datos != "" and $datos != "null") {

            return $datos;
        } else {
            throw new Exception("No existe" . get_class($this), "30");
        }
    }


    /**
     * Obtiene el ID del usuario.
     *
     * @return int El ID del usuario.
     */
    public function getUserId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param int $usuarioId El ID del usuario.
     */
    public function setUserId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el ID del documento.
     *
     * @return int El ID del documento.
     */
    public function getDocumentoId()
    {
        return $this->documentoId;
    }

    /**
     * Establece el ID del documento.
     *
     * @param int $documentoId El ID del documento.
     */
    public function setDocumentoId($documentoId)
    {
        $this->documentoId = $documentoId;
    }

    /**
     * Obtiene la versión.
     *
     * @return string La versión.
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Establece la versión.
     *
     * @param string $version La versión.
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Obtiene la fecha de creación.
     *
     * @return mixed La fecha de creación.
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece la fecha de creación.
     *
     * @param mixed $fecha La fecha de creación.
     */
    public function setFechaCrea($fecha)
    {
        $this->fechaCrea = $fecha;
    }

    /**
     * Obtiene la fecha de modificación.
     *
     * @return mixed La fecha de modificación.
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece la fecha de modificación.
     *
     * @param mixed $fechaModif La fecha de modificación.
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene la URL.
     *
     * @return mixed La URL.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Establece la URL.
     *
     * @param mixed $url La URL.
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Obtiene la encriptación.
     *
     * @return mixed La encriptación.
     */
    public function getEncriptacion()
    {
        return $this->encriptacion;
    }

    /**
     * Establece la encriptación.
     *
     * @param mixed $encriptacion La encriptación.
     */
    public function setEncriptacion($encriptacion)
    {
        $this->encriptacion = $encriptacion;
    }
}
