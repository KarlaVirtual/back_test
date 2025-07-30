<?php
namespace Backend\cms;
use Backend\dto\Producto;
use Backend\mysql\ProductoMySqlDAO;
use Exception;
/** 
* Clase 'CMSProducto'
* 
* Esta clase provee datos de CMSProducto
* 
* Ejemplo de uso: 
* $CMSProducto = new CMSProducto();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* @date 21.09.17
* 
*/
class CMSProducto
{

    /**
    * Representación de 'tipo'
    *
    * @var string
    */
    private $tipo;

    /**
    * Representación de 'productoId'
    *
    * @var string
    */
    private $productoId;

    /**
    * Constructor de clase
    *
    *
    * @param String $categoriaId categoriaId
    * @param String $tipo tipo
    * 
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */ 
    public function __construct($tipo, $productoId)
    {
        $this->tipo = $tipo;
        $this->productoId = $productoId;
    }

    /**
     * Obtener e imprimir los productos
     * 
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */ 
    public function getProductos(){
        try
        {
            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $Productos = $ProductoMySqlDAO ->queryByProveedorId(7);

            print_r($Productos);

        }
        catch (Exception $e){

        }

    }


}