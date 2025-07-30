<?php namespace Backend\dto;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\dto\CategoriaProducto;
use Exception;
/**
 * Clase 'ProductoMandante'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'ProductoMandante'
 *
 * Ejemplo de uso:
 * $ProductoMandante = new ProductoMandante();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class ProductoMandante
{

    /**
     * Representación de la columna 'prodmandanteId' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $prodmandanteId;

    /**
     * Representación de la columna 'productoId' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $productoId;

    /**
     * Representación de la columna 'mandante' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'estado' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'verifica' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $verifica;

    /**
     * Representación de la columna 'filtroPais' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $filtroPais;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'max' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $max;

    /**
     * Representación de la columna 'min' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $min;

    /**
     * Representación de la columna 'tiempoProcesamiento' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $tiempoProcesamiento;

    /**
     * Representación de la columna 'orden' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $orden;

    /**
     * Representación de la columna 'ordenDestacado' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $ordenDestacado;

    /**
     * Representación de la columna 'numFila' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $numFila;

    /**
     * Representación de la columna 'numColumna' de la tabla 'ProductoMandante'
     *
     * @var string
     */
    var $numColumna;
    var $paisId;

    /**
     * Representacion de la columna enviar_mensaje
     *
     * @var string
     *
     */
    var $enviarMensaje;

    /**
     * Representacion de la columna ImagenUrl
     *
     * @var string
     *
     */
    var $Image;

    /**
     * Representacion de la columna ImagenUrl2
     *
     * @var string
     *
     */

    /**
     * Representacion de la columna habilitacion
     *
     * @var string
     *
     */
    var $habilitacion;

    /**
     * Representacion de la columna codigoMinsetur
     *
     * @var string
     *
     */
    var $ImageUrl2;

    /**
     * Representacion de la columna extrainfo
     *
     * @var string
     *
     */
    var $codigoMinsetur;

    /**
     * Representacion de la columna extrainfo
     *
     * @var string
     *
     */
    var $extrainfo;

    /**
     * Representacion de la columna valor
     *
     * @var string
     *
     */
    var $valor;


    /**
     * Constructor de clase
     *
     *
     * @param String $productoId id del producto
     * @param String $mandante mandante
     * @param String $prodmandanteId prodmandanteId
     *
     * @return no
     * @throws Exception si ProductoMandante no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($productoId="", $mandante="", $prodmandanteId="", $paisId="",$estado='')
    {
        if ($productoId != "" && $mandante != "" && $paisId != "")
        {


            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

            $ProductoMandante = $ProductoMandanteMySqlDAO->queryByProductoIdAndMandanteAndPaisId($productoId, $mandante,$paisId,$estado);
            $ProductoMandante = $ProductoMandante[0];

            if ($ProductoMandante != null && $ProductoMandante != "")
            {
                $this->prodmandanteId = $ProductoMandante->prodmandanteId;
                $this->productoId = $ProductoMandante->productoId;
                $this->mandante = $ProductoMandante->mandante;
                $this->estado = $ProductoMandante->estado;
                $this->verifica = $ProductoMandante->verifica;
                $this->filtroPais = $ProductoMandante->filtroPais;
                $this->max = $ProductoMandante->max;
                $this->min = $ProductoMandante->min;
                $this->tiempoProcesamiento = $ProductoMandante->tiempoProcesamiento;
                $this->usucreaId = $ProductoMandante->usucreaId;
                $this->usumodifId = $ProductoMandante->usumodifId;
                $this->orden = $ProductoMandante->orden;
                $this->numFila = $ProductoMandante->numFila;
                $this->numColumna = $ProductoMandante->numColumna;
                $this->ordenDestacado = $ProductoMandante->ordenDestacado;
                $this->paisId = $ProductoMandante->paisId;
                $this->Image = $ProductoMandante->Image;
                $this->ImageUrl2 = $ProductoMandante->ImageUrl2;
                $this->habilitacion = $ProductoMandante->habilitacion;
                $this->codigoMinsetur = $ProductoMandante->codigoMinsetur;
                $this->extrainfo = $ProductoMandante->extrainfo;
                $this->valor = $ProductoMandante->valor;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "27");
            }

        }
        elseif ($productoId != "" && $mandante != "")
        {

            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

            $ProductoMandante = $ProductoMandanteMySqlDAO->queryByProductoIdAndMandante($productoId, $mandante,$estado);
            $ProductoMandante = $ProductoMandante[0];

            if ($ProductoMandante != null && $ProductoMandante != "")
            {
                $this->prodmandanteId = $ProductoMandante->prodmandanteId;
                $this->productoId = $ProductoMandante->productoId;
                $this->mandante = $ProductoMandante->mandante;
                $this->estado = $ProductoMandante->estado;
                $this->verifica = $ProductoMandante->verifica;
                $this->filtroPais = $ProductoMandante->filtroPais;
                $this->max = $ProductoMandante->max;
                $this->min = $ProductoMandante->min;
                $this->tiempoProcesamiento = $ProductoMandante->tiempoProcesamiento;
                $this->usucreaId = $ProductoMandante->usucreaId;
                $this->usumodifId = $ProductoMandante->usumodifId;
                $this->orden = $ProductoMandante->orden;
                $this->numFila = $ProductoMandante->numFila;
                $this->numColumna = $ProductoMandante->numColumna;
                $this->ordenDestacado = $ProductoMandante->ordenDestacado;
                $this->paisId = $ProductoMandante->paisId;
                $this->enviarMensaje = $ProductoMandante->enviarMensaje;
                $this->Image = $ProductoMandante->Image;
                $this->ImageUrl2 = $ProductoMandante->ImageUrl2;
                $this->habilitacion = $ProductoMandante->habilitacion;
                $this->codigoMinsetur = $ProductoMandante->codigoMinsetur;
                $this->extrainfo = $ProductoMandante->extrainfo;
                $this->valor = $ProductoMandante->valor;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "27");
            }

        }else if($prodmandanteId != "" and $mandante != "" and $paisId !=""){


            $ProductoMandanteMysqlDAO = new ProductoMandanteMySqlDAO();
            $ProductoMandante = $ProductoMandanteMysqlDAO->dataByCountryMandante($mandante,$prodmandanteId,$paisId,$estado);
            $ProductoMandante = $ProductoMandante[0];

            if($ProductoMandante != ""){
                $this->prodmandanteId =  $ProductoMandante->prodmandanteId;
                $this->productoId =  $ProductoMandante->productoId;
                $this->mandante =  $ProductoMandante->mandante;
                $this->estado =  $ProductoMandante->estado;
                $this->verifica =  $ProductoMandante->verifica;
                $this->filtroPais =  $ProductoMandante->filtroPais;
                $this->max =  $ProductoMandante->max;
                $this->min =  $ProductoMandante->min;
                $this->tiempoProcesamiento =  $ProductoMandante->tiempoProcesamiento;
                $this->usucreaId =  $ProductoMandante->usucreaId;
                $this->usumodifId =  $ProductoMandante->usumodifId;
                $this->orden =  $ProductoMandante->orden;
                $this->numFila =  $ProductoMandante->numFila;
                $this->numColumna =  $ProductoMandante->numColumna;
                $this->ordenDestacado =  $ProductoMandante->ordenDestacado;
                $this->paisId =  $ProductoMandante->paisId;
                $this->Image =  $ProductoMandante->Image;
                $this->ImageUrl2 = $ProductoMandante->ImageUrl2;
                $this->habilitacion = $ProductoMandante->habilitacion;
                $this->codigoMinsetur = $ProductoMandante->codigoMinsetur;
                $this->extrainfo = $ProductoMandante->extrainfo;
                $this->valor = $ProductoMandante->valor;

            }else{
                throw new Exception("No existe".get_class($this),"27");
            }

        }elseif ($prodmandanteId != "") {

            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

            $ProductoMandante = $ProductoMandanteMySqlDAO->load($prodmandanteId);

            if ($ProductoMandante != null && $ProductoMandante != "")
            {
                $this->prodmandanteId = $ProductoMandante->prodmandanteId;
                $this->productoId = $ProductoMandante->productoId;
                $this->mandante = $ProductoMandante->mandante;
                $this->estado = $ProductoMandante->estado;
                $this->verifica = $ProductoMandante->verifica;
                $this->filtroPais = $ProductoMandante->filtroPais;
                $this->max = $ProductoMandante->max;
                $this->min = $ProductoMandante->min;
                $this->tiempoProcesamiento = $ProductoMandante->tiempoProcesamiento;
                $this->usucreaId = $ProductoMandante->usucreaId;
                $this->usumodifId = $ProductoMandante->usumodifId;
                $this->orden = $ProductoMandante->orden;
                $this->numFila = $ProductoMandante->numFila;
                $this->numColumna = $ProductoMandante->numColumna;
                $this->ordenDestacado = $ProductoMandante->ordenDestacado;
                $this->paisId = $ProductoMandante->paisId;
                $this->Image = $ProductoMandante->Image;
                $this->ImageUrl2 = $ProductoMandante->ImageUrl2;
                $this->habilitacion = $ProductoMandante->habilitacion;
                $this->codigoMinsetur = $ProductoMandante->codigoMinsetur;
                $this->extrainfo = $ProductoMandante->extrainfo;
                $this->valor = $ProductoMandante->valor;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "27");
            }
        }

    }


    /**
     * Establecer la URL de la segunda imagen.
     *
     * @param string $Url2 La URL de la segunda imagen.
     */
    public function setImageUrl2($Url2){
        $this->ImageUrl2 = $Url2;
    }

    /**
     * Obtener la URL de la segunda imagen.
     *
     * @return string La URL de la segunda imagen.
     */
    public function getImageUrl2(){
        return $this->ImageUrl2;
    }

    /**
     * Establecer la imagen.
     *
     * @param string $Image La imagen.
     */
    public function setImage($Image){
        $this->Image = $Image;
    }

    /**
     * Obtener la imagen.
     *
     * @return string La imagen.
     */
    public function getImage(){
        return $this->Image;
    }

    /**
     * Establecer el mandante.
     *
     * @param string $mandante El mandante.
     */
    public function setMandante($mandante){
        $this->mandante = $mandante;
    }

    /**
     * Obtener el mandante.
     *
     * @return string El mandante.
     */
    public function getMandante(){
        return $this->mandante;
    }

    /**
     * Establecer el ID del país.
     *
     * @param int $Country El ID del país.
     */
    public function setPais($Country){
        $this->paisId = $Country;
    }

    /**
     * Establecer la habilitación.
     *
     * @param string $Habilitacion La habilitación.
     */
    public function setHabilitacion($Habilitacion){
        $this->habilitacion = $Habilitacion;
    }

    /**
     * Establecer el ID del producto.
     *
     * @param int $ProductoId El ID del producto.
     */
    public function setProductoId($ProductoId){
        $this->productoId = $ProductoId;
    }

    /**
     * Establecer el ID de creación del usuario.
     *
     * @param int $id El ID de creación del usuario.
     */
    public function setUsuCreaId($id){
        $this->usucreaId = $id;
    }

    /**
     * Establecer el estado.
     *
     * @param string $estado El estado.
     */
    public function setEstado($estado){
        $this->estado = $estado;
    }

    /**
     * Establecer el ID de verificación.
     *
     * @param int $id El ID de verificación.
     */
    public function setVerifica($id){
        $this->verifica = $id;
    }

    /**
     * Establecer el ID del filtro de país.
     *
     * @param int $id El ID del filtro de país.
     */
    public function setFiltroPais($id){
        $this->filtroPais = $id;
    }

    /**
     * Establecer el ID de modificación del usuario.
     *
     * @param int $id El ID de modificación del usuario.
     */
    public function setUsuModifId($id){
        $this->usumodifId = $id;
    }

    /**
     * Establecer el valor máximo.
     *
     * @param int $id El valor máximo.
     */
    public function setMax($id){
        $this->max = $id;
    }

    /**
     * Establecer el valor mínimo.
     *
     * @param int $id El valor mínimo.
     */
    public function setMin($id){
        $this->min = $id;
    }

    /**
     * Establecer el tiempo de procesamiento.
     *
     * @param int $id El tiempo de procesamiento.
     */
    public function setTiempoProcesamiento($id){
        $this->tiempoProcesamiento = $id;
    }

    /**
     * Establecer el orden.
     *
     * @param int $id El orden.
     */
    public function setOrden($id){
        $this->orden = $id;
    }

    /**
     * Establecer el número de fila.
     *
     * @param int $estado El número de fila.
     */
    public function setNumFila($estado){
        $this->numFila = $estado;
    }

    /**
     * Establecer el número de columna.
     *
     * @param int $estado El número de columna.
     */
    public function setNumColumn($estado){
        $this->numColumna = $estado;
    }

    /**
     * Establecer el orden destacado.
     *
     * @param int $estado El orden destacado.
     */
    public function setOrdenDestacado($estado){
        $this->ordenDestacado = $estado;
    }

    /**
     * Obtener el ID del país.
     *
     * @return int El ID del país.
     */
    public function getPais(){
        return $this->paisId;
    }

    /**
     * Establecer el ID del producto mandante.
     *
     * @param int $value El ID del producto mandante.
     */
    public function setProdmandanteId($value){
        $this->prodmandanteId = $value;
    }

    /**
     * Obtener el ID del producto mandante.
     *
     * @return int El ID del producto mandante.
     */
    public function getProdmandanteId(){
        return $this->prodmandanteId;
    }

    /**
     * Obtener el código Minsetur.
     *
     * @return string El código Minsetur.
     */
    public function getCodigoMinsetur(){
        return $this->codigoMinsetur;
    }

    /**
     * Establecer el código Minsetur.
     *
     * @param string $codigoMinsetur El código Minsetur.
     */
    public function setCodigoMinsetur($codigoMinsetur){
        $this->codigoMinsetur = $codigoMinsetur;
    }

    /**
     * Establecer la información extra.
     *
     * @param string $extrainfo La información extra.
     */
    public function setextrainfo($extrainfo){
        $this->extrainfo = $extrainfo;
    }

    /**
     * Obtener la información extra.
     *
     * @return string La información extra.
     */
    public function getextrainfo(){
        return $this->extrainfo;
    }

    /**
     * Establecer el valor.
     *
     * @param float $valor El valor.
     */
    public function setValor($valor){
        $this->valor = $valor;
    }

    /**
     * Obtener el valor.
     *
     * @return float El valor.
     */
    public function getValor(){
        return $this->valor;
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
    public function getProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

        $Productos = $ProductoMandanteMySqlDAO->queryProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
    public function getProductosMandanteCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

        $Productos = $ProductoMandanteMySqlDAO->queryProductosMandanteCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
     * Realizar una consulta en la tabla de ProductoMandante 'ProductoMandante'
     * de una manera personalizada con otras tablas importantes como prodmandantepais para el tema de los productos de un pais en especifico
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
    public function getProductosMandantePaisCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId=0)
    {

        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

        $Productos = $ProductoMandanteMySqlDAO->queryProductosMandantePaisCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId);

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
     * Obtiene una lista de productos mandante filtrados por país con opciones de búsqueda y paginación.
     *
     * @param string $select Columnas a seleccionar en la consulta.
     * @param string $sidx Columna por la cual se ordenarán los resultados.
     * @param string $sord Dirección de ordenamiento (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Número máximo de registros a devolver.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param int $paisId ID del país para filtrar los productos (opcional, por defecto es 0).
     * @return array Lista de productos mandante filtrados.
     * @throws Exception Si no se encuentran productos mandante.
     */
    public function getProductosMandantePaisCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId=0)
    {

        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

        $Productos = $ProductoMandanteMySqlDAO->queryProductosMandantePaisCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId);

        if ($Productos != null && $Productos != "")
        {
            return $Productos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /** Función creada para obtener las categorías de un producto y validar las apuestas que sumarán a un jackpot, método se crea con base en la verificación
     * de CONDCATEGORY en Bonointerno->verificarBonoRollower
     * @param ProductoMandante $ProductoMandante
     * @return array
     */
    public function getCategoriasIds ($ProductoMandante) {
        $rules = [];
        array_push($rules, array("field" => "categoria_producto.mandante", "data" => $ProductoMandante->mandante, "op" => "eq"));
        array_push($rules, array("field" => "producto.producto_id", "data" => $ProductoMandante->productoId, "op" => "eq"));
        array_push($rules, array("field" => "categoria_producto.pais_id", "data" => $ProductoMandante->paisId, "op" => "eq"));
        array_push($rules, array("field" => "categoria_producto.estado", "data" => "A", "op" => "eq"));

        $orden = "producto.descripcion";
        $ordenTipo = "asc";

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonfiltro = json_encode($filtro);

        $CategoriaProducto = new CategoriaProducto();
        $categories = $CategoriaProducto->getCategoriaProductosCustom("categoria_producto.categoria_id", $orden, $ordenTipo, 0, 10000, $jsonfiltro, true);
        $categories = json_decode($categories)->data;

        $finalCategories = array_map(function ($category) {
            return $category->{'categoria_producto.categoria_id'};
        }, $categories);

        return $finalCategories;
    }
}
?>
