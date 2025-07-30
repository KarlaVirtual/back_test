<?php namespace Backend\dto;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioSorteo'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioSorteo'
* 
* Ejemplo de uso: 
* $PreUsuarioSorteo = new PreUsuarioSorteo();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PreUsuarioSorteo


{
    /**
     * Representación de la columna 'ususorteoId' de la tabla 'UsuarioSorteo'
     *
     * @var string
     */
    var $preususorteoId;



    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'sorteoId' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $sorteoId;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'posicion' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $posicion;

    /**
    * Representación de la columna 'valorBase' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $valorBase;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'errorId' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $errorId;

    /**
    * Representación de la columna 'idExterno' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $idExterno;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'version' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $version;

    /**
    * Representación de la columna 'apostado' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $apostado;

    /**
    * Representación de la columna 'codigo' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $codigo;

    /**
    * Representación de la columna 'externoId' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $externoId;

    /**
    * Representación de la columna 'valorPremio' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $valorPremio;

    /**
     * Representación de la columna 'ususorteoId' de la tabla 'UsuarioSorteo'
     *
     * @var string
     */
    var $ususorteoId;

    var $tipo;


    /**
    * Constructor de clase
    *
    *
    * @param String $ususorteoId ususorteoId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioSorteo no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($presorteoId = '', $usuarioId = '') {
        if ($presorteoId != '') {
            
            $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO();

            $PreUsuarioSorteo = $PreUsuarioSorteoMySqlDAO->load($presorteoId);

            if ($PreUsuarioSorteo != null && $PreUsuarioSorteo != "") {
            
                $this->preususorteoId = $PreUsuarioSorteo->preususorteoId;
                $this->usuarioId = $PreUsuarioSorteo->usuarioId;
                $this->sorteoId = $PreUsuarioSorteo->sorteoId;
                $this->valor = $PreUsuarioSorteo->valor;
                $this->posicion = $PreUsuarioSorteo->posicion;
                $this->valorBase = $PreUsuarioSorteo->valorBase;
                $this->fechaCrea = $PreUsuarioSorteo->fechaCrea;
                $this->usucreaId = $PreUsuarioSorteo->usucreaId;
                $this->fechaModif = $PreUsuarioSorteo->fechaModif;
                $this->usumodifId = $PreUsuarioSorteo->usumodifId;
                $this->estado = $PreUsuarioSorteo->estado;
                $this->errorId = $PreUsuarioSorteo->errorId;
                $this->idExterno = $PreUsuarioSorteo->idExterno;
                $this->mandante = $PreUsuarioSorteo->mandante;
                $this->version = $PreUsuarioSorteo->version;
                $this->apostado = $PreUsuarioSorteo->apostado;
                $this->codigo = $PreUsuarioSorteo->codigo;
                $this->externoId = $PreUsuarioSorteo->externoId;
                $this->valorPremio = $PreUsuarioSorteo->valorPremio;
                $this->ususorteoId = $PreUsuarioSorteo->ususorteoId;
                $this->tipo = $PreUsuarioSorteo->tipo;


            } else {
                throw new Exception("No existe " . get_class($this), "115");
            }
        
        }
        elseif ($usuarioId != '')  {
            $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO();

            $PreUsuarioSorteo = $PreUsuarioSorteoMySqlDAO->queryByUsuarioId($usuarioId);
            $PreUsuarioSorteo = $PreUsuarioSorteo[0];

            if ($PreUsuarioSorteo != null && $PreUsuarioSorteo != "") {

                $this->ususorteoId = $PreUsuarioSorteo->ususorteoId;
                $this->usuarioId = $PreUsuarioSorteo->usuarioId;
                $this->sorteoId = $PreUsuarioSorteo->sorteoId;
                $this->valor = $PreUsuarioSorteo->valor;
                $this->posicion = $PreUsuarioSorteo->posicion;
                $this->valorBase = $PreUsuarioSorteo->valorBase;
                $this->fechaCrea = $PreUsuarioSorteo->fechaCrea;
                $this->usucreaId = $PreUsuarioSorteo->usucreaId;
                $this->fechaModif = $PreUsuarioSorteo->fechaModif;
                $this->usumodifId = $PreUsuarioSorteo->usumodifId;
                $this->estado = $PreUsuarioSorteo->estado;
                $this->errorId = $PreUsuarioSorteo->errorId;
                $this->idExterno = $PreUsuarioSorteo->idExterno;
                $this->mandante = $PreUsuarioSorteo->mandante;
                $this->version = $PreUsuarioSorteo->version;
                $this->apostado = $PreUsuarioSorteo->apostado;
                $this->codigo = $PreUsuarioSorteo->codigo;
                $this->externoId = $PreUsuarioSorteo->externoId;
                $this->valorPremio = $PreUsuarioSorteo->valorPremio;
                $this->tipo = $PreUsuarioSorteo->tipo;

            } else {
                throw new Exception('No existe ' . get_class($this), 115);
            }
        }

    }


    /**
     * Realizar una insercción
     *
     *
     * @param Objeto Transaction transacción
     *
     * @return boolean $ resultado de la inserción
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function insert($transaction)
    {

        $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($transaction);
        return $PreUsuarioSorteoMySqlDAO->insert($this);

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
     * Obtener el campo sorteoId de un objeto
     *
     * @return String sorteoId sorteoId
     * 
     */
    public function getSorteoId()
    {
        return $this->sorteoId;
    }

    /**
     * Modificar el campo 'sorteoId' de un objeto
     *
     * @param String $sorteoId sorteoId
     *
     * @return no
     *
     */
    public function setSorteoId($sorteoId)
    {
        $this->sorteoId = $sorteoId;
    }

    /**
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     * 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtener el campo posicion de un objeto
     *
     * @return String posicion posicion
     * 
     */
    public function getValorSorteo()
    {
        return $this->posicion;
    }

    /**
     * Modificar el campo 'posicion' de un objeto
     *
     * @param String $posicion posicion
     *
     * @return no
     *
     */
    public function setValorSorteo($posicion)
    {
        $this->posicion = $posicion;
    }

    /**
     * Obtener el campo valorBase de un objeto
     *
     * @return String valorBase valorBase
     * 
     */
    public function getValorBase()
    {
        return $this->valorBase;
    }

    /**
     * Modificar el campo 'valorBase' de un objeto
     *
     * @param String $valorBase valorBase
     *
     * @return no
     *
     */
    public function setValorBase($valorBase)
    {
        $this->valorBase = $valorBase;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     * 
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     * 
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     * 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo errorId de un objeto
     *
     * @return String errorId errorId
     * 
     */
    public function getErrorId()
    {
        return $this->errorId;
    }
    
    /**
     * Modificar el campo 'errorId' de un objeto
     *
     * @param String $errorId errorId
     *
     * @return no
     *
     */
    public function setErrorId($errorId)
    {
        $this->errorId = $errorId;
    }

    /**
     * Obtener el campo idExterno de un objeto
     *
     * @return String idExterno idExterno
     * 
     */
    public function getIdExterno()
    {
        return $this->idExterno;
    }

    /**
     * Modificar el campo 'idExterno' de un objeto
     *
     * @param String $idExterno idExterno
     *
     * @return no
     *
     */
    public function setIdExterno($idExterno)
    {
        $this->idExterno = $idExterno;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     * 
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
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
     * Obtener el campo apostado de un objeto
     *
     * @return String apostado apostado
     * 
     */
    public function getApostado()
    {
        return $this->apostado;
    }

    /**
     * Modificar el campo 'apostado' de un objeto
     *
     * @param String $apostado apostado
     *
     * @return no
     *
     */
    public function setApostado($apostado)
    {
        $this->apostado = $apostado;
    }

    /**
     * Obtener el campo codigo de un objeto
     *
     * @return String codigo codigo
     * 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Modificar el campo 'codigo' de un objeto
     *
     * @param String $codigo codigo
     *
     * @return no
     *
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Obtener el campo externoId de un objeto
     *
     * @return String externoId externoId
     * 
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Modificar el campo 'externoId' de un objeto
     *
     * @param String $externoId externoId
     *
     * @return no
     *
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }

    /**
     * Obtener el campo ususorteoId de un objeto
     *
     * @return String ususorteoId ususorteoId
     * 
     */
    public function getUsusorteoId()
    {
        return $this->ususorteoId;
    }

    /**
     * Obtener el campo valorPremio de un objeto
     *
     * @return String valorPremio valorPremio
     * 
     */
    public function getValorPremio()
    {
        return $this->valorPremio;
    }

    /**
     * Modificar el campo 'valorPremio' de un objeto
     *
     * @param String $valorPremio valorPremio
     *
     * @return no
     *
     */
    public function setValorPremio($valorPremio)
    {
        $this->valorPremio = $valorPremio;
    }

    /**
     * @return string
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * @param string $posicion
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;
    }


    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String ususorteoId usuarioId
     *
     */
    public function getUsuarioSorteoId()
    {
        return $this->ususorteoId;
    }

    /**
     * Modificar el campo 'ususorteoId' de un objeto
     *
     * @param String $ususorteoId ususorteoId
     *
     * @return no
     *
     */
    public function setUsuarioSorteoId($ususorteoId)
    {
        $this->usuarioId = $ususorteoId;
    }





    /**
    * Realizar una consulta en la tabla de UsuarioSorteo 'UsuarioSorteo'
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
    * @throws Exception si los deportes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getPreusuarioSorteoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withPosition=false)
    {

        $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO();

        $Productos = $PreUsuarioSorteoMySqlDAO->queryUsuarioSorteosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withPosition);

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
     * Obtiene una lista de sorteos de usuarios personalizados sin posición.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @return array Lista de sorteos de usuarios personalizados.
     * @throws Exception Si no existen sorteos de usuarios personalizados.
     */
    public function getUsuarioSorteosCustomWithoutPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO();

        $Productos = $PreUsuarioSorteoMySqlDAO->queryUsuarioSorteosCustomWithoutPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
