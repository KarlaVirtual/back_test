<?php 
namespace Backend\dto;
use Backend\mysql\DescargaMySqlDAO;
use Exception;
/** 
* Clase 'Descarga'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Descarga'
* 
* Ejemplo de uso: 
* $Descarga = new Descarga();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Descarga
{
		
    /**
    * Representación de la columna 'descarga_id' de la tabla 'Descarga'
    *
    * @var string
    */ 
	var $descargaId;
        
    /**
    * Representación de la columna 'descripcion' de la tabla 'Descarga'
    *
    * @var string
    */ 
    var $descripcion;
        
    /**
    * Representación de la columna 'tipo' de la tabla 'Descarga'
    *
    * @var string
    */ 
    var $tipo;
        
    /**
    * Representación de la columna 'ruta' de la tabla 'Descarga'
    *
    * @var string
    */ 
    var $ruta;
        
    /**
    * Representación de la columna 'version' de la tabla 'Descarga'
    *
    * @var string
    */ 
    var $version;
        
    /**
    * Representación de la columna 'estado' de la tabla 'Descarga'
    *
    * @var string
    */ 
    var $estado;

    /**
     * Representación de la columna 'mandante' de la tabla 'Descarga'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'plataforma' de la tabla 'Descarga'
     *
     * @var string
     */
    var $plataforma;

    /**
     * Representación de la columna 'encriptacion_metodo' de la tabla 'Descarga'
     *
     * @var string
     */
    var $encriptacionMetodo;

    /**
     * Representación de la columna 'encriptacion_valor' de la tabla 'Descarga'
     *
     * @var string
     */
    var $encriptacionValor;

    var $externalId;
    var $paisId;
    var $proveedorId;
    var $json;
    var $perfilId;


    /**
    * Constructor de clase
    *
    *
    * @param String $descargaId id de la descarga
    * @param String $tipo tipo
    *
    * @return no
    * @throws Exception si la descarga no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */

    public function __construct($descargaId="",$tipo="")
    {


        if ($descargaId != "") {

            $DescargaMySqlDAO = new DescargaMySqlDAO();

            $Descarga = $DescargaMySqlDAO->load($descargaId);

            if ($Descarga != null && $Descarga != "") 
            {

                $this->descargaId = $Descarga->descargaId;
                $this->descripcion = $Descarga->descripcion;
                $this->tipo = $Descarga->tipo;
                $this->ruta = $Descarga->ruta;
                $this->version = $Descarga->version;
                $this->estado = $Descarga->estado;
                $this->mandante = $Descarga->mandante;
                $this->plataforma = $Descarga->plataforma;
                $this->encriptacionMetodo = $Descarga->encriptacionMetodo;
                $this->encriptacionValor = $Descarga->encriptacionValor;
                $this->externalId = $Descarga->externalId;
                $this->paisId = $Descarga->paisId;
                $this->proveedorId = $Descarga->proveedorId;
                $this->json = $Descarga->json;
                $this->perfilId = $Descarga->perfilId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "30");
            }
        }
        elseif ($tipo != "") 
        {


            $DescargaMySqlDAO = new DescargaMySqlDAO();

            $Descarga = $DescargaMySqlDAO->queryByTipo( $tipo);
            $Descarga=$Descarga[0];

            if ($Descarga != null && $Descarga != "") 
            {
                $this->descargaId = $Descarga->descargaId;
                $this->descripcion = $Descarga->descripcion;
                $this->tipo = $Descarga->tipo;
                $this->ruta = $Descarga->ruta;
                $this->version = $Descarga->version;
                $this->estado = $Descarga->estado;
                $this->mandante = $Descarga->mandante;
                $this->plataforma = $Descarga->plataforma;
                $this->encriptacionMetodo = $Descarga->encriptacionMetodo;
                $this->encriptacionValor = $Descarga->encriptacionValor;
                $this->externalId = $Descarga->externalId;
                $this->paisId = $Descarga->paisId;
                $this->proveedorId = $Descarga->proveedorId;
                $this->json = $Descarga->json;
                $this->perfilId = $Descarga->perfilId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "30");
            }
        }

    }


    /**
    * Realizar una consulta en la tabla de descargas 'Descarga'
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
    * @throws Exception si la descarga no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getDescargasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $DescargaMySqlDAO = new DescargaMySqlDAO();

        $Descarga = $DescargaMySqlDAO->queryDescargasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Descarga != null && $Descarga != "") 
        {
            return $Descarga;    
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "30");
        }

    }


    /**
     * Obtiene el ID de la descarga.
     *
     * @return mixed El ID de la descarga.
     */
    public function getDescargaId() {
        return $this->descargaId;
    }

    /**
     * Establece el ID de la descarga.
     *
     * @param mixed $descargaId El ID de la descarga.
     */
    public function setDescargaId($descargaId) {
        $this->descargaId = $descargaId;
    }

    /**
     * Obtiene la descripción.
     *
     * @return mixed La descripción.
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Establece la descripción.
     *
     * @param mixed $descripcion La descripción.
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtiene la ruta.
     *
     * @return string La ruta.
     */
    public function getRuta(){
        return $this->ruta;
    }

    /**
     * Establece la ruta.
     *
     * @param string $ruta La ruta a establecer.
     */
    public function setRuta($ruta){
        $this->ruta = $ruta;
    }

    /**
     * Obtiene la versión.
     *
     * @return string La versión.
     */
    public function getVersion(){
        return $this->version;
    }

    /**
     * Establece la versión.
     *
     * @param string $version La versión a establecer.
     */
    public function setVersion($version){
        $this->version = $version;
    }

    /**
     * Obtiene el tipo.
     *
     * @return mixed El tipo.
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Establece el tipo.
     *
     * @param mixed $tipo El tipo a establecer.
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el estado.
     *
     * @return mixed El estado.
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Establece el estado.
     *
     * @param mixed $estado El estado a establecer.
     */
    public function setEstado($estado) {
        $this->estado = $estado;
    }

    /**
     * Obtiene la plataforma.
     *
     * @return mixed La plataforma.
     */
    public function getPlataforma() {
        return $this->plataforma;
    }


    /**
     * Establece la plataforma.
     *
     * @param string $plataforma La plataforma a establecer.
     */
    public function setPlataforma($plataforma){
        $this->plataforma = $plataforma;
    }

    /**
     * Obtiene el valor de mandante.
     *
     * @return mixed El valor de mandante.
     */
    public function getMandante(){
        return $this->mandante;
    }

    /**
     * Establece el valor del mandante.
     *
     * @param mixed $mandante El valor del mandante a establecer.
     */
    public function setMandante($mandante){
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el valor de encriptación.
     *
     * @return mixed El valor de encriptación.
     */
    public function getEncriptacionValor(){
        return $this->encriptacionValor;
    }

    /**
     * Establece el valor de encriptación.
     *
     * @param mixed $encriptacionValor El valor de encriptación a establecer.
     */
    public function setEncriptacionValor($encriptacionValor){
        $this->encriptacionValor = $encriptacionValor;
    }

    /**
     * Obtiene el método de encriptación.
     *
     * @return mixed El método de encriptación.
     */
    public function getEncriptacionMetodo(){
        return $this->encriptacionMetodo;
    }

    /**
     * Establece el método de encriptación.
     *
     * @param mixed $encriptacionMetodo El método de encriptación a establecer.
     */
    public function setEncriptacionMetodo($encriptacionMetodo){
        $this->encriptacionMetodo = $encriptacionMetodo;
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
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param mixed $json
     */
    public function setJson($json)
    {
        $this->json = $json;
    }

    /**
     * @return mixed
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * @param mixed $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * @return mixed
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * @param mixed $proveedorId
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }

    /**
     * @return mixed
     */
    public function getPerfilId()
    {
        return $this->perfilId;
    }

    /**
     * @param mixed $perfilId
     */
    public function setPerfilId($perfilId)
    {
        $this->perfilId = $perfilId;
    }

}

?>