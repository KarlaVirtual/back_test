<?php

namespace Backend\dto;

use Backend\mysql\ProductoDetalleMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Exception;

/**
 * Clase 'Producto'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'Producto'
 *
 * Ejemplo de uso:
 * $Producto = new Producto();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class Producto
{

    /**
     * Representación de la columna 'productoId' de la tabla 'Producto'
     *
     * @var string
     */
    var $productoId;

    /**
     * Representación de la columna 'proveedorId' de la tabla 'Producto'
     *
     * @var string
     */
    var $proveedorId;

    /**
     * Representación de la columna 'descripcion' de la tabla 'Producto'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'imageUrl' de la tabla 'Producto'
     *
     * @var string
     */
    var $imageUrl;
    var $imageUrl2;



    /**
     * Representación de la columna 'estado' de la tabla 'Producto'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'verifica' de la tabla 'Producto'
     *
     * @var string
     */
    var $verifica;

    /**
     * Representación de la columna 'externoId' de la tabla 'Producto'
     *
     * @var string
     */
    var $externoId;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'Producto'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'Producto'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'mostrar' de la tabla 'Producto'
     *
     * @var string
     */
    var $mostrar;

    /**
     * Representación de la columna 'orden' de la tabla 'Producto'
     *
     * @var string
     */
    var $orden;

    /**
     * Representación de la columna 'mobile' de la tabla 'Producto'
     *
     * @var string
     */
    var $mobile;

    /**
     * Representación de la columna 'desktop' de la tabla 'Producto'
     *
     * @var string
     */
    var $desktop;

    /**
     * Representación de la columna 'proveedorId' de la tabla 'Producto'
     *
     * @var string
     */
    var $subproveedorId;

    /**
     * Representación de la columna 'pagoTerceros' de la tabla 'Producto'
     *
     * @var string
     */
    var $pagoTerceros;

    /**
     * Representación de la columna 'rtpTeorico' de la tabla 'Producto'
     *
     * @var string
     */
    var $rtpTeorico;

    /**
     * Representación de la columna 'categoriaId' de la tabla 'Producto'
     *
     * @var string
     */
    var $categoriaId;


    /**
     * Constructor de clase
     *
     *
     * @param String productoId id del producto
     * @param String externoId externoId
     * @param String proveedorId proveedorId
     *
     *
     * @return no
     * @throws Exception si el producto no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($productoId = "", $externoId = "", $proveedorId = "")
    {
        if ($productoId != "") {

            $this->productoId = $productoId;

            $ProductoMySqlDAO = new ProductoMySqlDAO();

            $Producto = $ProductoMySqlDAO->load($productoId);

            if ($Producto != null && $Producto != "") {
                $this->proveedorId = $Producto->proveedorId;
                $this->descripcion = $Producto->descripcion;
                $this->imageUrl = $Producto->imageUrl;

                $this->estado = $Producto->estado;
                $this->verifica = $Producto->verifica;
                $this->externoId = $Producto->externoId;
                $this->usucreaId = $Producto->usucreaId;
                $this->usumodifId = $Producto->usumodifId;
                $this->mostrar = $Producto->mostrar;
                $this->orden = $Producto->orden;
                $this->mobile = $Producto->mobile;
                $this->desktop = $Producto->desktop;
                $this->rtpTeorico = $Producto->rtpTeorico;
                $this->categoriaId = $Producto->categoriaId;

                $this->subproveedorId = $Producto->subproveedorId;
                $this->imageUrl2 = $Producto->imageUrl2;
                $this->pagoTerceros = $Producto->pagoTerceros;
            } else {
                throw new Exception("No existe " . get_class($this), "26");
            }
        } elseif ($externoId != "" && $proveedorId != "") {

            $this->externoId = $externoId;

            $ProductoMySqlDAO = new ProductoMySqlDAO();

            $Producto = $ProductoMySqlDAO->queryByExternoIdAndProveedorId($externoId, $proveedorId);
            $Producto = $Producto[0];

            if ($Producto != null && $Producto != "") {
                $this->productoId = $Producto->productoId;
                $this->proveedorId = $Producto->proveedorId;
                $this->descripcion = $Producto->descripcion;
                $this->imageUrl = $Producto->imageUrl;

                $this->estado = $Producto->estado;
                $this->verifica = $Producto->verifica;
                $this->externoId = $Producto->externoId;
                $this->usucreaId = $Producto->usucreaId;
                $this->usumodifId = $Producto->usumodifId;
                $this->mostrar = $Producto->mostrar;
                $this->orden = $Producto->orden;
                $this->mobile = $Producto->mobile;
                $this->desktop = $Producto->desktop;
                $this->rtpTeorico = $Producto->rtpTeorico;
                $this->categoriaId = $Producto->categoriaId;

                $this->subproveedorId = $Producto->subproveedorId;
                $this->imageUrl2 = $Producto->imageUrl2;
                $this->pagoTerceros = $Producto->pagoTerceros;
            } else {
                throw new Exception("No existe " . get_class($this), "26");
            }
        }
    }


    /**
     * Verifica si un ID externo existe en la base de datos.
     *
     * @param string $value El ID externo a verificar.
     * @return bool Retorna true si el ID externo existe, de lo contrario retorna false.
     */
    public function verifyIdExterno($value)
    {
        $producto = new ProductoMySqlDAO();
        $verificarIdExterno = $producto->queryByExternoId($value);
        $total = $verificarIdExterno[0];

        if ($total != "") {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Obtener el campo productoId de un objeto
     *
     * @return String productoId productoId
     *
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Modificar el campo 'productoId' de un objeto
     *
     * @param String $productoId productoId
     *
     * @return no
     *
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
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
     * Obtener el campo imageUrl de un objeto
     *
     * @return String imageUrl imageUrl
     *
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Modificar el campo 'imageUrl' de un objeto
     *
     * @param String $imageUrl imageUrl
     *
     * @return no
     *
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * Obtener el campo imageUrl2 de un objeto
     *
     * @return string imageUrl2
     */
    public function getImageUrl2()
    {
        return $this->imageUrl2;
    }

    /**
     * Modificar el campo 'imageUrl' de un objeto
     *
     * @param String $imageUrl imageUrl
     *
     * @return no
     *
     */
    public function setImageUrl2($imageUrl2)
    {
        $this->imageUrl2 = $imageUrl2;
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
     * Obtener el campo mostrar de un objeto
     *
     * @return String mostrar mostrar
     *
     */
    public function getMostrar()
    {
        return $this->mostrar;
    }

    /**
     * Modificar el campo 'mostrar' de un objeto
     *
     * @param String $mostrar mostrar
     *
     * @return no
     *
     */
    public function setMostrar($mostrar)
    {
        $this->mostrar = $mostrar;
    }

    /**
     * Obtener el campo orden de un objeto
     *
     * @return String orden orden
     *
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Modificar el campo 'orden' de un objeto
     *
     * @param String $orden orden
     *
     * @return no
     *
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    }

    /**
     * Obtener el campo mobile de un objeto
     *
     * @return String mobile mobile
     *
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Modificar el campo 'mobile' de un objeto
     *
     * @param String $mobile mobile
     *
     * @return no
     *
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }


    /**
     * Obtener el campo desktop de un objeto
     *
     * @return String desktop desktop
     *
     */
    public function getDesktop()
    {
        return $this->desktop;
    }

    /**
     * Modificar el campo 'desktop' de un objeto
     *
     * @param String $desktop desktop
     *
     * @return no
     *
     */
    public function setDesktop($desktop)
    {
        $this->desktop = $desktop;
    }

    /**
     * Obtener el campo subproveedorId de un objeto Producto
     * @return string
     */
    public function getSubproveedorId()
    {
        return $this->subproveedorId;
    }

    /**
     * Modificar el campo 'subproveedorId' de un objeto Producto
     * @param string $subproveedorId
     */
    public function setSubproveedorId($subproveedorId)
    {
        $this->subproveedorId = $subproveedorId;
    }

    /**
     * Obtener el campo pagoTerceros de un objeto Producto
     * @return mixed
     */
    public function getPagoTerceros()
    {
        return $this->pagoTerceros;
    }

    /**
     * Modificar el campo 'pagoTerceros' de un objeto Producto
     * @param mixed $pagoTerceros
     */
    public function setPagoTerceros($pagoTerceros)
    {
        $this->pagoTerceros = $pagoTerceros;
    }

    /**
     * Obtener el campo rtpTeorico de un objeto Producto
     * @return mixed
     */
    public function setRtpTeorico($rtpTeorico)
    {
        $this->rtpTeorico = $rtpTeorico;
    }

    /**
     * Modificar el campo 'rtpTeorico' de un objeto Producto
     * @param mixed $rtpTeorico
     */
    public function getRtpTeorico()
    {
        return $this->rtpTeorico;
    }

    /**
     * Modificar el campo 'categoriaId' de un objeto Producto
     * @param mixed $categoriaId
     */
    public function setCategoriaId($categoriaId)
    {
        $this->categoriaId = $categoriaId;
    }

    /**
     * Obtener el campo categoriaId de un objeto Producto
     * @return mixed
     */
    public function getCategoriaId()
    {
        return $this->categoriaId;
    }







    /**
     * Realizar un insert en la base de datos
     *
     *
     * @param Objeto $transaction transacción
     *
     * @return Array resultado de la consulta
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function insert($transaction)
    {

        $ProductoMySqlDAO = new ProductoMySqlDAO($transaction);

        return $ProductoMySqlDAO->insert($this);
    }

    /**
     * Obtener si el campo externoId existe y no está vacío
     *
     * @return boolean result result
     *
     */
    public function existsExternoId()
    {

        $ProductoMySqlDAO = new ProductoMySqlDAO();

        $Producto = $ProductoMySqlDAO->queryByExternoId($this->externoId);

        if (oldCount($Producto) > 0) {
            return true;
        }

        return false;
    }


    /**
     * Obtener los detalles de un producto
     *
     * @return Array detalles detalles
     *
     */
    public function getDetalles()
    {

        $ProductoMysqlDAO = new ProductoMySqlDAO();

        return $ProductoMysqlDAO->getDetalles();
    }


    /**
     * Realizar una consulta en la tabla de productos 'Producto'
     * de una manera personalizada
     *
     *
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
    public function getProductos($sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoMySqlDAO = new ProductoMySqlDAO();

        $miperfil = "SA";
        $Productos = $ProductoMySqlDAO->queryProductos($sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Realizar una consulta en la tabla de productos 'Producto'
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
    public function getProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoMySqlDAO = new ProductoMySqlDAO();

        $Productos = $ProductoMySqlDAO->queryProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }
    public function getProductosCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $mandante, $paisId = '')
    {
        $ProductoMySqlDAO = new ProductoMySqlDAO();

        $Productos = $ProductoMySqlDAO->queryProductosCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $mandante, $paisId);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /** ------------------------- @FuncionesReporteDePoker ----------------------------------------- */

    /**
     * Retorna el conjunto de externos vinculados a productos de poker,
     *facilitando la diferenciación de los mismos respecto a productos estándar de casino.
     *
     * @return array Externos aplicables a Poker
     */
    public static function getCollectablePokerExternals(): array
    {
        return [
            "ps",
            "EvenBetPoker"
        ];
    }


    /**
     * Obtener el reporte de Poker por sesiones.
     *
     * @param int &$totalCount Referencia al total de conteo de sesiones.
     * @param array $filters Filtros a aplicar en la consulta.
     * @return array Colección de objetos de respuesta
     */
    public function getPokerReportBySessions(int &$totalCount, array $filters = [])
    {
        $ProductoMySqlDAO = new ProductoMySqlDAO();
        return $ProductoMySqlDAO->queryPokerReportBySessions($totalCount, $filters);
    }


    /**
     * Obtener el reporte de Poker por proveedor y usuario.
     *
     * @param int &$totalCount Referencia al total de conteo de sesiones.
     * @param array $filters Filtros a aplicar en la consulta.
     * @return array Colección de objetos de respuesta
     */
    public function getPokerReportBySupplierByUser(int &$totalCount, array $filters = [])
    {
        $ProductoMySqlDAO = new ProductoMySqlDAO();
        return $ProductoMySqlDAO->queryPokerReportBySupplierByUser($totalCount, $filters);
    }


    /**
     * Obtener el reporte de Poker por proveedor, usuario y tipo de juego.
     *
     * @param int &$totalCount Referencia al total de conteo de sesiones.
     * @param array $filters Filtros a aplicar en la consulta.
     * @return array Colección de objetos de respuesta
     */
    public function getPokerReportBySupplierByUserByGameType(int &$totalCount, array $filters = [])
    {
        $ProductoMySqlDAO = new ProductoMySqlDAO();
        return $ProductoMySqlDAO->queryPokerReportBySupplierByGameType($totalCount, $filters);
    }

    /** ------------------------- @FuncionesReporteDePoker @FIN ----------------------------------------- */


    /**
     *Función desarrollada para su implementación en el reporte de productos NO deportivos
     *Retorna un array de objetos que identifican las verticales no deportivas a un ID específico para su implementación en selects e identificadores
     *a través de las múltiples vistas del BO.
     *
     *@return array Colección de verticales NO deportivas
     */
    static function getCasinoVerticals(): array
    {
        $verticals = [
            [
                "id" => "1",
                "value" => "Casino"
            ],
            [
                "id" => "2",
                "value" => "Casino en vivo"
            ],
            [
                "id" => "3",
                "value" => "Virtuales"
            ],
            [
                "id" => "4",
                "value" => "Lotería"
            ]
        ];

        return $verticals;
    }
}
