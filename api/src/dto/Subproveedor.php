<?php namespace Backend\dto;
use Backend\mysql\SubproveedorMySqlDAO;
use Exception;
/** 
* Clase 'Subproveedor'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Subproveedor'
* 
* Ejemplo de uso: 
* $Subproveedor = new Subproveedor();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Subproveedor
{

    /**
    * Representación de la columna 'subproveedorId' de la tabla 'Subproveedor'
    *
    * @var string
    */
    var $subproveedorId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Subproveedor'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'tipo' de la tabla 'Subproveedor'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'estado' de la tabla 'Subproveedor'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'verifica' de la tabla 'Subproveedor'
    *
    * @var string
    */
    var $verifica;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Subproveedor'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Subproveedor'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'abreviado' de la tabla 'Subproveedor'
    *
    * @var string
    */
    var $abreviado;

    /**
     * Representación de la columna 'proveedorId' de la tabla 'Subproveedor'
     *
     * @var string
     */
    var $proveedorId;

    /**
     * Representación de la columna 'credentials' de la tabla 'Subproveedor'
     *
     * @var string
     */
    var $credentials;

    var $image;

    /**
    * Constructor de clase
    *
    *
    * @param String $subproveedorId id del subproveedor
    * @param String $abreviado abreviado
    *
    * @return no
    * @throws Exception si el subproveedor no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($subproveedorId="", $abreviado="")
    {
        if ($subproveedorId != "") 
        {

            $this->subproveedorId = $subproveedorId;

            $SubproveedorMySqlDAO = new SubproveedorMySqlDAO();

            $Subproveedor = $SubproveedorMySqlDAO->load($subproveedorId);

            if ($Subproveedor != null && $Subproveedor != "") 
            {
                $this->descripcion = $Subproveedor->descripcion;
                $this->tipo = $Subproveedor->tipo;
                $this->estado = $Subproveedor->estado;
                $this->verifica = $Subproveedor->verifica;
                $this->abreviado = $Subproveedor->abreviado;
                $this->usucreaId = $Subproveedor->usucreaId;
                $this->usumodifId = $Subproveedor->usumodifId;
                $this->proveedorId = $Subproveedor->proveedorId;
                $this->image = $Subproveedor->image;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "106");
            }

        }
        elseif ($abreviado != "") 
        {

            $this->abreviado = $abreviado;

            $SubproveedorMySqlDAO = new SubproveedorMySqlDAO();

            $Subproveedor = $SubproveedorMySqlDAO->queryByAbreviado($abreviado);

            $Subproveedor = $Subproveedor[0];

            if ($Subproveedor != null && $Subproveedor != "") 
            {
                $this->subproveedorId = $Subproveedor->subproveedorId;
                $this->tipo = $Subproveedor->tipo;
                $this->estado = $Subproveedor->estado;
                $this->verifica = $Subproveedor->verifica;
                $this->abreviado = $Subproveedor->abreviado;
                $this->usucreaId = $Subproveedor->usucreaId;
                $this->usumodifId = $Subproveedor->usumodifId;
                $this->proveedorId = $Subproveedor->proveedorId;
                $this->image = $Subproveedor->image;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "106");
            }
        }

    }





    /**
     * Obtener el campo subproveedorId de un objeto
     *
     * @return String subproveedorId subproveedorId
     * 
     */
    public function getSubproveedorId()
    {
        return $this->subproveedorId;
    }

    /**
     * Modificar el campo 'subproveedorId' de un objeto
     *
     * @param String $subproveedorId subproveedorId
     *
     * @return no
     *
     */
    public function setSubproveedorId($subproveedorId)
    {
        $this->subproveedorId = $subproveedorId;
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
     * @return string
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * @param string $proveedorId
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }

    /**
     * @return string|null
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @param string|null $credentials
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

    public function setImage($value){
        $this->image = $value;
    }

    public function getImage(){
        return $this->image;
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

        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO($transaction);

        return $SubproveedorMySqlDAO->insert($this);

    }

    /**
    * Obtener productos
    *
    *
    * @param String $category categoria
    * @param String $provider subproveedor
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

        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO($transaction);

        return $SubproveedorMySqlDAO->getProductosTipo($this->tipo, $category, $provider, $offset, $limit, $search, $partnerId,$isMobile);

    }

    public function getProductosTipoMandante($category="", $provider="", $offset="", $limit="", $search="", $partnerId="",$isMobile="",$CountrySelect='' ,$userId = null, &$getCount = false)
    {
        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO($transaction);

        return $SubproveedorMySqlDAO->getProductosTipoMandante($this->tipo, $category, $provider, $offset, $limit, $search, $partnerId,$isMobile,$CountrySelect, $userId, $getCount);

    }

    public function getProductosTipoMandante2($category = '', $provider = '', $subprovider = '', $offset = '', $limit = '', $search = '', $partnerId = '', $isMobile = '', $name = '', $CountrySelect = '')
    {
        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO();
        return $SubproveedorMySqlDAO->getProductosTipoMandante2($this->tipo, $category, $provider, $subprovider, $offset, $limit, $search, $partnerId, $isMobile, $name, $CountrySelect);
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
    public function countProductosTipo($category="",$partnerId="",$isMobile="",$provider="",$CountrySelect='',$search='' )
    {

        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO($transaction);

        return $SubproveedorMySqlDAO->countProductosTipo($this->tipo,$category,$partnerId,$isMobile,$provider,$CountrySelect,$search);

    }


    /**
     * Cuenta los productos por tipo de mandante.
     *
     * @param string $category Categoría de los productos.
     * @param string $partnerId ID del socio.
     * @param string $isMobile Indica si es móvil.
     * @param string $provider Proveedor de los productos.
     * @param string $countrySelected País seleccionado.
     * @return int Número de productos por tipo de mandante.
     */
    public function countProductosTipoMandante($category = "", $partnerId = "", $isMobile = "", $provider = "", $countrySelected = "")
    {
        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO($transaction);
        return $SubproveedorMySqlDAO->countProductosTipoMandante($this->tipo, $category, $partnerId, $isMobile, $provider, $countrySelected);
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
    public function getSubproveedores($partner='0',$estadoSubproveedorMandante='',$estadoSubproveedor='',$country='')
    {

        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO($transaction);

        return $SubproveedorMySqlDAO->queryByTipo($this->tipo,$partner,$estadoSubproveedorMandante,$estadoSubproveedor,$country);

    }

    /**
     * Obtiene una lista de subproveedores filtrados por país.
     *
     * @param string $partner Identificador del socio. Valor por defecto es '0'.
     * @param string $estadoSubproveedorMandante Estado del subproveedor mandante. Valor por defecto es una cadena vacía.
     * @param string $estadoSubproveedor Estado del subproveedor. Valor por defecto es una cadena vacía.
     * @param string $country País del subproveedor. Valor por defecto es una cadena vacía.
     * @return array Lista de subproveedores filtrados.
     */
    public function getSubproveedoresPais($partner='0',$estadoSubproveedorMandante='',$estadoSubproveedor='',$country='')
    {

        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO($transaction);

        return $SubproveedorMySqlDAO->queryByTipo($this->tipo,$partner,$estadoSubproveedorMandante,$estadoSubproveedor,$country);

    }


    /**
    * Realizar una consulta en la tabla de subproveedores 'Subproveedor'
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
    * @throws Exception si los subproveedores no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getSubproveedoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandanteSelect="")
    {

        $SubproveedorMySqlDAO = new SubproveedorMySqlDAO();

        $subproveedores = $SubproveedorMySqlDAO->querySubproveedoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandanteSelect);

        if ($subproveedores != null && $subproveedores != "") 
        {
            return $subproveedores;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }


  
}
?>
