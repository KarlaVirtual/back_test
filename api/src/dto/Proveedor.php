<?php namespace Backend\dto;
use Backend\mysql\ProveedorMySqlDAO;
use Exception;
/** 
* Clase 'Proveedor'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Proveedor'
* 
* Ejemplo de uso: 
* $Proveedor = new Proveedor();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Proveedor
{

    /**
    * Representación de la columna 'proveedorId' de la tabla 'Proveedor'
    *
    * @var string
    */
    var $proveedorId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Proveedor'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'tipo' de la tabla 'Proveedor'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'estado' de la tabla 'Proveedor'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'verifica' de la tabla 'Proveedor'
    *
    * @var string
    */
    var $verifica;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Proveedor'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Proveedor'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'abreviado' de la tabla 'Proveedor'
    *
    * @var string
    */
    var $abreviado;

    /**
     * Representación de la columna 'imagen' de la tabla 'Proveedor'
     *
     * @var string
     */
    var $imagen;

    /**
    * Constructor de clase
    *
    *
    * @param String $proveedorId id del proveedor
    * @param String $abreviado abreviado
    *
    * @return no
    * @throws Exception si el proveedor no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($proveedorId="", $abreviado="")
    {
        if ($proveedorId != "") 
        {

            $this->proveedorId = $proveedorId;

            $ProveedorMySqlDAO = new ProveedorMySqlDAO();

            $Proveedor = $ProveedorMySqlDAO->load($proveedorId);

            if ($Proveedor != null && $Proveedor != "") 
            {
                $this->descripcion = $Proveedor->descripcion;
                $this->tipo = $Proveedor->tipo;
                $this->estado = $Proveedor->estado;
                $this->verifica = $Proveedor->verifica;
                $this->abreviado = $Proveedor->abreviado;
                $this->usucreaId = $Proveedor->usucreaId;
                $this->usumodifId = $Proveedor->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "25");
            }

        }
        elseif ($abreviado != "") 
        {

            $this->abreviado = $abreviado;

            $ProveedorMySqlDAO = new ProveedorMySqlDAO();

            $Proveedor = $ProveedorMySqlDAO->queryByAbreviado($abreviado);

            $Proveedor = $Proveedor[0];

            if ($Proveedor != null && $Proveedor != "") 
            {
                $this->proveedorId = $Proveedor->proveedorId;
                $this->tipo = $Proveedor->tipo;
                $this->estado = $Proveedor->estado;
                $this->verifica = $Proveedor->verifica;
                $this->abreviado = $Proveedor->abreviado;
                $this->usucreaId = $Proveedor->usucreaId;
                $this->usumodifId = $Proveedor->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "25");
            }
        }

    }





    /**
     * Obtener el campo proveedorId de un objeto
     *
     * @return String proveedorId proveedorId
     * 
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Modificar el campo 'proveedorId' de un objeto
     *
     * @param String $proveedorId proveedorId
     *
     * @return no
     *
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }

    /**
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
     * 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar el campo 'descripcion' de un objeto
     *
     * @param String $descripcion descripcion
     *
     * @return no
     *
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
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
     * Obtener el campo verifica de un objeto
     *
     * @return String verifica verifica
     * 
     */
    public function getVerifica()
    {
        return $this->verifica;
    }

    /**
     * Modificar el campo 'verifica' de un objeto
     *
     * @param String $verifica verifica
     *
     * @return no
     *
     */
    public function setVerifica($verifica)
    {
        $this->verifica = $verifica;
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
     * Obtener el campo abreviado de un objeto
     *
     * @return String abreviado abreviado
     * 
     */
    public function getAbreviado()
    {
        return $this->abreviado;
    }

    /**
     * Modificar el campo 'abreviado' de un objeto
     *
     * @param String $abreviado abreviado
     *
     * @return no
     *
     */
    public function setAbreviado($abreviado)
    {
        $this->abreviado = $abreviado;
    }

    /**
     * Modificar el campo 'imagen' de un objeto
     *
     * @param String $imagen imagen
     *
     * @return no
     *
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }




    /**
    * Realizar una insercción en la base de datos
    *
    *
    * @param Objecto Transaccion transaccion
    *
    * @return boolean resultado de la insercción
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function insert($transaction)
    {

        $ProveedorMySqlDAO = new ProveedorMySqlDAO($transaction);

        return $ProveedorMySqlDAO->insert($this);

    }

    /**
    * Obtener productos
    *
    *
    * @param String $category categoria
    * @param String $provider proveedor
    * @param String $offset offset
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param String $search search
    * @param String $partnerId id del partner
    * @param String $isMobile isMobile
    *
    * @return Array resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getProductosTipo($category="", $provider="", $offset="", $limit="", $search="", $partnerId="",$isMobile=""  )
    {

        $ProveedorMySqlDAO = new ProveedorMySqlDAO($transaction);

        return $ProveedorMySqlDAO->getProductosTipo($this->tipo, $category, $provider, $offset, $limit, $search, $partnerId,$isMobile);

    }

    /**
    * Realizar una consulta 
    *
    *
    * @param String $category category category
    * @param String $partnerId partnerId id del partner
    * @param String $isMobile isMobile isMobile
    * @param String $provider provider provider
    *
    * @return Array resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function countProductosTipo($category="",$partnerId="",$isMobile="",$provider="")
    {

        $ProveedorMySqlDAO = new ProveedorMySqlDAO($transaction);

        return $ProveedorMySqlDAO->countProductosTipo($this->tipo,$category,$partnerId,$isMobile,$provider);

    }

    /**
    * Realizar una consulta
    *
    *
    * @param no
    *
    * @return Array resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getProveedores($partner=0,$estadoProveedorMandante='',$estadoProveedor='')
    {

        $ProveedorMySqlDAO = new ProveedorMySqlDAO($transaction);

        return $ProveedorMySqlDAO->queryByTipo($this->tipo,$partner,$estadoProveedorMandante,$estadoProveedor);

    }


    /**
    * Realizar una consulta en la tabla de proveedores 'Proveedor'
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
    * @throws Exception si los proveedores no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getProveedoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProveedorMySqlDAO = new ProveedorMySqlDAO();

        $proveedores = $ProveedorMySqlDAO->queryProveedoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($proveedores != null && $proveedores != "") 
        {
            return $proveedores;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }


  
}
?>
