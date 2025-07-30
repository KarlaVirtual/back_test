<?php

namespace Backend\dto;

use Backend\mysql\EtiquetaProductoMySqlDAO;

/**
 * Clase 'TagsProducto'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'tags_producto'
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 */




class EtiquetaProducto
{


    /**
     * Representación de la columna 'etiqprod_id' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $etiqprodId;

    /**
     * Representación de la columna 'producto_id' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $productoId;

    /**
     * Representación de la columna 'etiqueta_id' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $etiquetaId;

    /**
     * Representación de la columna 'fecha_crea' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $fechaCrea;

    /**
     * Representación de la columna 'fecha_modif' de la tabla 'etiqueta_producto'
     *
     * @var int
     */

    public $fechaModif;

    /**
     * Representación de la columna 'usucrea_id' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $usucreaId;

    /**
     * Representación de la columna 'usumodif_id' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $usumodifId;

    /**
     * Representación de la columna 'estado' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $estado;
    /**
     * Representación de la columna 'mandante' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $mandante;
    /**
     * Representación de la columna 'pais_id' de la tabla 'etiqueta_producto'
     *
     * @var int
     */
    public $pais_id;
    /**
     * Representación de la columna 'image' de la tabla 'etiqueta_producto'
     *
     * @var string
     */
    public $image;
    /**
     * Representación de la columna 'text' de la tabla 'etiqueta_producto'
     *
     * @var string
     */
    public $text;


    /**
     * Constructor de clase
     *
     * @param int $etiqprodId ID del tag_producto
     * @param int $productoId ID del tag
     * @param int $etiquetaId ID del producto
     * @param int $fechaCrea ID del producto
     * @param int $fechaModif ID del producto
     * @param int $usucreaId ID del producto
     * @param int $usumodifId ID del producto
     * @param int $estado Estado del producto
     * @param int $mandante Mandane del producto
     * @param int $paisId Pais del producto
     * @param string $image Imagen del producto

     */
    public function __construct($etiquetaId = "", $productoId = "", $etiqprodId = "", $paisId = "", $mandante = "", $estado = "")
    {
        if ($etiquetaId != "" && $productoId != "") {


            $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();

            $EtiquetaProducto = $EtiquetaProductoMySqlDAO->queryByEtiquetaIdAndProductoId($productoId, $etiquetaId);
            $EtiquetaProducto = $EtiquetaProducto[0];

            if ($EtiquetaProducto != null && $EtiquetaProducto != "")
            {

                $this->etiqprodId = $EtiquetaProducto->etiqprodId;
                $this->productoId = $EtiquetaProducto->productoId;
                $this->etiquetaId = $EtiquetaProducto->etiquetaId;
                $this->fechaCrea = $EtiquetaProducto->fechaCrea;
                $this->fechaModif = $EtiquetaProducto->fechaModif;
                $this->usucreaId = $EtiquetaProducto->usucreaId;
                $this->usumodifId = $EtiquetaProducto->usumodifId;
                $this->estado = $EtiquetaProducto->estado;
                $this->text = $EtiquetaProducto->text;


            }
            else
            {
                throw new Exception("No existe " . get_class($this), "27");
            }

        }
        else if($etiqprodId != "" and $productoId != "" ){


            $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
            $EtiquetaProducto = $EtiquetaProductoMySqlDAO->queryByEtiqprodIdAndProductoId($productoId, $etiqprodId);
            $EtiquetaProducto = $EtiquetaProducto[0];

            if ($EtiquetaProducto != "") {

                $this->etiqprodId = $EtiquetaProducto->etiqprodId;
                $this->productoId = $EtiquetaProducto->productoId;
                $this->etiquetaId = $EtiquetaProducto->etiquetaId;
                $this->fechaCrea = $EtiquetaProducto->fechaCrea;
                $this->fechaModif = $EtiquetaProducto->fechaModif;
                $this->usucreaId = $EtiquetaProducto->usucreaId;
                $this->usumodifId = $EtiquetaProducto->usumodifId;
                $this->estado = $EtiquetaProducto->estado;
                $this->text = $EtiquetaProducto->text;


            }else{
                throw new Exception("No existe".get_class($this),"27");
            }

        }elseif ($etiqprodId != "") {

            $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();

            $EtiquetaProducto = $EtiquetaProductoMySqlDAO->load($etiqprodId);

            if ($EtiquetaProducto != null && $EtiquetaProducto != "")
            {
                $this->etiqprodId = $EtiquetaProducto->etiqprodId;
                $this->productoId = $EtiquetaProducto->productoId;
                $this->etiquetaId = $EtiquetaProducto->etiquetaId;
                $this->fechaCrea = $EtiquetaProducto->fechaCrea;
                $this->fechaModif = $EtiquetaProducto->fechaModif;
                $this->usucreaId = $EtiquetaProducto->usucreaId;
                $this->usumodifId = $EtiquetaProducto->usumodifId;
                $this->estado = $EtiquetaProducto->estado;
                $this->text = $EtiquetaProducto->text;


            }
            else
            {
                throw new Exception("No existe " . get_class($this), "27");
            }
        }elseif($productoId != "" && $paisId != "" && $mandante != "" && $estado != ""){
            $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
            $EtiquetaProducto = $EtiquetaProductoMySqlDAO->queryByProductoIdAndPaisIdAndMandanteAndEstado($productoId,$paisId,$mandante,$estado);

            $EtiquetaProducto = $EtiquetaProducto[0];

            if($EtiquetaProducto != ""){

                $this->etiqprodId = $EtiquetaProducto->etiqprodId;
                $this->productoId = $EtiquetaProducto->productoId;
                $this->etiquetaId = $EtiquetaProducto->etiquetaId;
                $this->fechaCrea = $EtiquetaProducto->fechaCrea;
                $this->fechaModif = $EtiquetaProducto->fechaModif;
                $this->usucreaId = $EtiquetaProducto->usucreaId;
                $this->usumodifId = $EtiquetaProducto->usumodifId;
                $this->estado = $EtiquetaProducto->estado;
                $this->text = $EtiquetaProducto->text;


            }else{
                throw new Exception("No existe".get_class($this),"27");
            }
        }

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
     * Define la etiquetaId
     *
     * @param mixed $etiquetaId El ID de la etiqueta
     */
    public function setEtiquetaId($etiquetaId)
    {
        $this->etiquetaId = $etiquetaId;
    }

    /**
     * Obtiene el ID de la etiqueta
     *
     * @return mixed El ID de la etiqueta
     */
    public function getEtiquetaId()
    {
        return $this->etiquetaId;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado Estado
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }


    /**
     * Establece el ID del producto.
     *
     * @param mixed $productoId El ID del producto.
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }


    /**
     * Obtiene el mandante.
     *
     * @return mixed El mandante.
     */
    public function getMandante()
    {
        return $this->productoId;
    }

    /**
     * Establece el ID de la etiqueta del producto.
     *
     * @param mixed $value El ID de la etiqueta del producto.
     */
    public function setEtiqprodId($value)
    {
        $this->etiqprodId = $value;
    }

    /**
     * Obtiene el ID de la etiqueta del producto.
     *
     * @return mixed El ID de la etiqueta del producto.
     */
    public function getEtiqprodId()
    {
        return $this->etiqprodId;
    }

    /**
     * Realizar una consulta en la tabla de ProductoMandante 'ProductoMandante'
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
    public function getEtiquetaProductoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();

        $Etiquetas = $EtiquetaProductoMySqlDAO->queryEtiquetaProductoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Etiquetas != null && $Etiquetas != "") {
            return $Etiquetas;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }
}
?>
