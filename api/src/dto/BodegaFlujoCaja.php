<?php namespace Backend\dto;

use Backend\mysql\BodegaFlujoCajaMySqlDAO;
use Exception;

/**
 * Object represents table 'producto'
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @date: 2017-09-06 18:54
 * @category    No
 * @package     No
 * @version     1.0
 */
class BodegaFlujoCaja
{

    /**
     * BodegaFlujoCaja constructor.
     * @param $usurecresumeId
     */
    public function __construct($usurecresumeId = "")
    {
        if ($usurecresumeId != "") {

        }

    }


    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getBodegaFlujoCajaCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "",$innProducto=false,$innConcesionario=false)
    {

        $BodegaFlujoCajaMySqlDAO = new BodegaFlujoCajaMySqlDAO();

        $Productos = $BodegaFlujoCajaMySqlDAO->queryBodegaFlujoCajasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping,$innProducto,$innConcesionario);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "104");
        }

    }


}

?>
