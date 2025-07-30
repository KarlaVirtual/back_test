<?php 
namespace Backend\dto;
use Backend\mysql\UsuarioTarjetaDetalleMySqlDAO;
use Backend\mysql\EquipoFavoritoMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioTarjeta'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioTarjeta'
* 
* Ejemplo de uso: 
* $EquipoFavorito = new UsuarioTarjeta();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class EquipoFavorito
{

    /**
    * Representación de la columna 'usutarjetacreditoId' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $equipoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $nombre;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $liga;

    /**
    * Representación de la columna 'cuenta' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'cvv' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $paisId;

    /**
    * Representación de la columna 'fechaExpiracion' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $escudo;

   
    /**
    * Constructor de clase
    *
    *
    * @param String $usutarjetacreditoId usutarjetacreditoId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioTarjeta no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($equipoFavoritoId="")
    {
        if ($equipoFavoritoId != "") 
        {

            $this->equipoId = $equipoFavoritoId;

            $EquipoFavoritoMySqlDAO = new EquipoFavoritoMySqlDAO();

            $EquipoFavorito = $EquipoFavoritoMySqlDAO->load($equipoFavoritoId);

            if ($EquipoFavorito != null && $EquipoFavorito != "") 
            {
                $this->equipoId = $EquipoFavorito->equipoId;
                $this->nombre = $EquipoFavorito->nombre;
                $this->liga = $EquipoFavorito->liga;
                $this->mandante = $EquipoFavorito->mandante;
                $this->paisId = $EquipoFavorito->paisId;
                $this->escudo = $EquipoFavorito->escudo;
               
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "67");
            }
        }

    }

    /**
    * Realizar una consulta en la tabla de UsuarioTarjeta 'UsuarioTarjeta'
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
    * @throws Exception si las los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */

    public function getEquipoFavorito($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $EquipoFavoritoMySqlDAO = new EquipoFavoritoMySqlDAO();

        $Productos = $EquipoFavoritoMySqlDAO->queryEquipoFavorito( $sidx, $sord, $start, $limit, $filters, $searchOn);

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
     * Obtiene una lista personalizada de equipos favoritos según los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Número máximo de registros a devolver.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * 
     * @return object Lista de equipos favoritos que cumplen con los criterios especificados.
     * @throws Exception Si no se encuentran equipos favoritos.
     */
    public function getEquipoFavoritoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $EquipoFavoritoMySqlDAO = new EquipoFavoritoMySqlDAO();

        $Productos = $EquipoFavoritoMySqlDAO->queryEquipoFavoritoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
     * Obtiene el equipoId
     * @return string
     */
    public function getEquipoId()
    {
        return $this->equipoId;
    }

    /**
     * Establece el equipoId
     * @param string $equipoId
     */
    public function setEquipoId($equipoId)
    {
        $this->equipoId = $equipoId;
    }

    /**
     * Obtiene el nombre
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Establece el nombre
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtiene la liga
     * @return string
     * 
     */
    public function getLiga()
    {
        return $this->liga;
    }

    /**
     * Establece la liga
     * @param string $liga
     */
    public function setLiga($liga)
    {
        $this->liga = $liga;
    }

    /**
     * Obtiene el mandante
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Establece el mandante
     * @param string $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el paísId
     * @return string
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Establece el paísId
     * @param string $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtiene el escudo
     * @return string
     * 
     */
    public function getEscudo()
    {
        return $this->escudo;
    }

    /**
     * Establece el escudo
     * @param string $escudo
     */
    public function setEscudo($escudo)
    {
        $this->escudo = $escudo;
    }


    /**
     * Ejecuta una consulta SQL utilizando el DAO de EquipoFavoritoMySql.
     *
     * @param mixed $transaccion La transacción a utilizar para la consulta.
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la consulta SQL, decodificado como un objeto.
     */
    public function execQuery($transaccion, $sql)
    {

        $EquipoFavoritoMySqlDAO = new EquipoFavoritoMySqlDAO($transaccion);
        $return = $EquipoFavoritoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }


}

?>
