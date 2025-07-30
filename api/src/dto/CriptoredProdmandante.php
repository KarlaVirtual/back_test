<?php
namespace Backend\dto;

use Backend\mysql\CriptoredProdmandanteMySqlDAO;

/**
 * Clase 'CriptoredProdmandante'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'criptored_prodmandate'
 *
 * Ejemplo de uso:
 * $Criptored = new Criptored();
 *
 *
 *
 * @package ninguno
 * @author  Juan David Taborda <juan.taborda@virtualsoft.tech>
 * @version ninguna
 * @access  public
 * @see     no
 *
 */


class CriptoredProdmandante{
    /**
     * Representación de la columna 'criptored_prodmandante_id' de la tabla 'criptored_prodmandate'
     *
     * @var string
     */

    var $criptoredProdmandanteId;

    /**
     * Representación de la columna 'criptored_id' de la tabla 'criptored_prodmandate'
     *
     * @var string
     */

    var $criptoredId;

    /**
     * Representación de la columna 'prodmandante_id' de la tabla 'criptored_prodmandate'
     *
     * @var string
     */


    var $prodmandanteId;

    /**
     * Representación de la columna 'estado' de la tabla 'criptored_prodmandate'
     *
     * @var string
     */

    var $estado;

    /**
     * Representación de la columna 'fecha_crea' de la tabla 'criptored_prodmandate'
     *
     * @var string
     */

    var $fechaCrea;


    /**
     * Representación de la columna 'fecha_modif' de la tabla 'criptored_prodmandate'
     *
     * @var string
     */

    var $fechaModif;

    /**
     * Representación de la columna 'usumodif_id' de la tabla 'criptored_prodmandate'
     *
     * @var string
     */


    var $usumodifId;


    var $usucreaId;


    /**
     * Constructor de clase
     *
     *
     * @param String criptored_prodmandante_id id de la asociacion de una criptomoneda a un mandante
     * @param String $criptoredId id de la asociacion
     * @param String $prodmandanteId  id del producto asociado a un mandante
     *
     * @return no
     * @throws Exception si criptored_prodmandante no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */

    public function __construct($criptoredProdmandanteId = "", $criptoredId = "", $prodmandanteId = "") {
       if($criptoredProdmandanteId != ""){
           $CriptoredProdmandanteMySqlDAO = new CriptoredProdmandanteMySqlDAO();
           $CriptoredProdmandante = $CriptoredProdmandanteMySqlDAO->load($criptoredProdmandanteId);
           if($CriptoredProdmandante != "" and $CriptoredProdmandante != null){
               $this->criptoredProdmandanteId = $CriptoredProdmandante->criptoredProdmandanteId;
               $this->criptoredId = $CriptoredProdmandante->criptoredId;
               $this->prodmandanteId = $CriptoredProdmandante->prodmandanteId;
               $this->estado = $CriptoredProdmandante->estado;
               $this->fechaCrea = $CriptoredProdmandante->fechaCrea;
               $this->fechaModif = $CriptoredProdmandante->fechaModif;
               $this->usumodifId = $CriptoredProdmandante->usumodifId;
               $this->usucreaId = $CriptoredProdmandante->usucreaId;
           }else{
               throw new Exception("No existe " . get_class($this), "289");
           }
       }else if ($criptoredId != "" and $prodmandanteId != ""){
           $CriptoredProdmandanteMySqlDAO = new CriptoredProdmandanteMySqlDAO();
           $CriptoredProdmandante = $CriptoredProdmandanteMySqlDAO->queryByCriptoredIdAndProdmandanteId($criptoredId, $prodmandanteId);
           if($CriptoredProdmandante != "" and $CriptoredProdmandante != null){
               $this->criptoredProdmandanteId = $CriptoredProdmandante->criptoredProdmandanteId;
               $this->criptoredId = $CriptoredProdmandante->criptoredId;
               $this->prodmandanteId = $CriptoredProdmandante->prodmandanteId;
               $this->estado = $CriptoredProdmandante->estado;
               $this->fechaCrea = $CriptoredProdmandante->fechaCrea;
               $this->fechaModif = $CriptoredProdmandante->fechaModif;
               $this->usumodifId = $CriptoredProdmandante->usumodifId;
               $this->usucreaId = $CriptoredProdmandante->usucreaId;
           }else{
                throw new Exception("No existe " . get_class($this), "289");
           }

       }
    }


 /**
 * Realiza una consulta personalizada en la base de datos para obtener datos de la tabla `criptored_prodmandante`.
 *
 * @param string $select Columnas que se desean seleccionar en la consulta.
 * @param string $sidx Columna por la cual se ordenarán los resultados.
 * @param string $sord Dirección de ordenamiento (ascendente o descendente).
 * @param int $start Índice inicial para la paginación de resultados.
 * @param int $limit Número máximo de registros a obtener.
 * @param string $filters Filtros en formato JSON para aplicar a la consulta.
 * @param bool $searchOn Indica si se deben aplicar los filtros de búsqueda.
 *
 * @return mixed Datos obtenidos de la consulta en formato JSON.
 * @throws Exception Si no se encuentran datos en la tabla `criptored_prodmandante`.
 */

public function getCriptoProdmandanteCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn){
    $CriptoredPromandanteMySqlDAO = new CriptoredProdmandanteMySqlDAO();
    $datos = $CriptoredPromandanteMySqlDAO->queryCriptoredProdmandanteCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);
    if($datos != "" and $datos != null){
        return $datos;
    }else{
        throw new Exception("No existe CriptoredProdmandante", "289");
    }
}


     /**
     * Obtiene el identificador único de la asociación de un producto a una criptored.
     *
     * @return string Identificador único de la asociación.
     */

    public function getCriptoredProdmandanteId() {
        return $this->criptoredProdmandanteId;
    }


    /**
     * Establece el identificador único de la asociación de la criptored.
     *
     * @param string $value Identificador único de la criptored.
     * @return void
     */

    public function setCriptoredId($value){
        $this->criptoredId = $value;
    }


    /**
     * Obtiene el identificador único de la criptored asociada al producto.
     *
     * @return string Identificador único de la criptored.
     */

    public function getCriptoredId() {
        return $this->criptoredId;
    }

    /**
     * Establece el identificador único del producto asociado a un mandante.
     *
     * @param string $value Identificador único del producto asociado.
     * @return void
     */
    public function setProdmandanteId($value){
        $this->prodmandanteId = $value;
    }

    /**
     * Obtiene el identificador único del producto asociado a un mandante.
     *
     * @return string Identificador único del producto asociado.
     */

    public function getProdmandanteId() {
        return $this->prodmandanteId;
    }

    /**
     * Establece el estado de la asociación de un producto a una criptored.
     *
     * @param string $value Estado de la asociación (activo o inactivo).
     * @return void
     */

    public function setEstado($value){
        $this->estado = $value;
    }

    /**
     * Obtiene el estado de la asociación de un producto a una criptored.
     *
     * @return string Estado de la asociación (activo o inactivo).
     */

    public function getEstado() {
        return $this->estado;
    }

    /**
    * Obtiene la fecha de creación de la asociación de un producto a una criptored.
    *
    * @return string Fecha de creación de la asociación.
    */
   public function getFechaCrea(){
        return $this->fechaCrea;
   }

   /**
    * Obtiene la fecha de modificación de la asociación de un producto a una criptored.
    *
    * @return string Fecha de modificación de la asociación.
    */
   public function getFechaModif(){
        return $this->fechaModif;
   }


/**
 * Establece el identificador único del usuario que creó la asociación.
 *
 * @param string $value Identificador único del usuario creador.
 * @return void
 */
public function setUsucreaId($value){
     $this->usucreaId = $value;
}

/**
 * Obtiene el identificador único del usuario que creó la asociación.
 *
 * @return string Identificador único del usuario creador.
 */
public function getUsucreaId(){
       return $this->usucreaId;
}

/**
 * Establece el identificador único del usuario que modificó la asociación.
 *
 * @param string $value Identificador único del usuario modificador.
 * @return void
 */
public function setUsumodifId($value)
{
     $this->usumodifId = $value;
}

/**
 * Obtiene el identificador único del usuario que modificó la asociación.
 *
 * @return string Identificador único del usuario modificador.
 */

    public function getUsumodifId(){
             return $this->usumodifId;
    }

}