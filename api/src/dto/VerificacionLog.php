<?php namespace Backend\dto;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\VerificacionLogMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioPerfil'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioPerfil'
* 
* Ejemplo de uso: 
* $VerificacionLog = new UsuarioPerfil();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class VerificacionLog
{

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioPerfil'
    *
    * @var string
    */
    public $verificacionId;

    /**
    * Representación de la columna 'perfilId' de la tabla 'UsuarioPerfil'
    *
    * @var string
    */
    public $usuverificacionId;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioPerfil'
    *
    * @var string
    */
    public $json;




    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId usuarioId
    * @param String $perfilId perfilId
    *
    * @return no
    * @throws Exception si UsuarioPerfil no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($verificacionId="",$usuverificacionId="")
    {
        if ($verificacionId != "")
        {
            $this->verificacionId = $verificacionId;

            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO();
            $VerificacionLog = $VerificacionLogMySqlDAO->load($verificacionId);

            if ($VerificacionLog != "" && $VerificacionLog != null) 
            {

                $this->verificacionId = $VerificacionLog->verificacionId;
                $this->usuverificacionId = $VerificacionLog->usuverificacionId;
                $this->json = $VerificacionLog->json;



            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");

            }

        }

        elseif($usuverificacionId != "")
        {
            $this->usuverificacionId = $usuverificacionId;

            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO();
            $VerificacionLog = $VerificacionLogMySqlDAO->queryByUsuverificacionId($usuverificacionId);
            $VerificacionLog=$VerificacionLog[0];
            if ($VerificacionLog != "" && $VerificacionLog != null)
            {
                $this->verificacionId = $VerificacionLog->verificacionId;
                $this->usuverificacionId = $VerificacionLog->usuverificacionId;
                $this->json = $VerificacionLog->json;



            }
            else
            {
                throw new Exception("No existe " . get_class($this), "01");

            }

        }
    }

    /**
     * Obtiene el ID de verificación.
     *
     * @return string
     */
    public function getVerificacionId()
    {
        return $this->verificacionId;
    }

    /**
     * Establece el ID de verificación.
     *
     * @param string $verificacionId
     */
    public function setVerificacionId($verificacionId)
    {
        $this->verificacionId = $verificacionId;
    }

    /**
     * Obtiene el ID de usuario de verificación.
     *
     * @return string
     */
    public function getUsuverificacionId()
    {
        return $this->usuverificacionId;
    }

    /**
     * Establece el ID de usuario de verificación.
     *
     * @param string $usuverificacionId
     */
    public function setUsuverificacionId($usuverificacionId)
    {
        $this->usuverificacionId = $usuverificacionId;
    }

    /**
     * Obtiene el JSON.
     *
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * Establece el JSON.
     *
     * @param string $json
     */
    public function setJson($json)
    {
        $this->json = $json;
    }

    /**
     * Obtiene el tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo.
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene un registro de verificación personalizado.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio del límite de registros.
     * @param int $limit Número de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupamiento de resultados (opcional).
     * @return array|null Registros de verificación obtenidos.
     * @throws Exception Si no existen registros de verificación.
     */
    public function getVerificacionLogCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $VerificacionLogMySqlDAO= new VerificacionLogMySqlDAO();

        $ruletas = $VerificacionLogMySqlDAO->queryVerificacionLogCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

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
