<?php namespace Backend\dto;
use Backend\mysql\BancoMySqlDAO;
use Exception;
/** 
* Clase 'Banco'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Banco'
* 
* Ejemplo de uso: 
* $Banco = new Banco();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
*/
class Banco
{
		
    /**
    * Representación de la columna 'bancoId' de la tabla 'Banco'
    *
    * @var string
    */        
	var $bancoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Banco'
    *
    * @var string
    */   
	var $descripcion;

    /**
    * Representación de la columna 'paisId' de la tabla 'Banco'
    *
    * @var string
    */   
    var $paisId;

    /**
    * Representación de la columna 'estado' de la tabla 'Banco'
    *
    * @var string
    */   
    var $estado;

    /**
    * Representación de la columna 'productoPago' de la tabla 'Banco'
    *
    * @var string
    */   
    var $productoPago;

    /**
    * Representación de la columna 'tipo' de la tabla 'Banco'
    *
    * @var string
    */   
    var $tipo;

    /**
    * Constructor de clase
    *
    *
    * @param string $bancoId id del banco
    *
    * @throws Exception si el banco no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($bancoId="")
    {

        if($bancoId != "")
        {

            $BancoMySqlDAO = new BancoMySqlDAO();

            $Banco = $BancoMySqlDAO->load($bancoId);

            $this->success = false;

            if ($Banco != null && $Banco != "") 
            {
                $this->descripcion = $Banco->descripcion;
                $this->paisId = $Banco->paisId;
                $this->bancoId = $Banco->bancoId;
                $this->estado = $Banco->estado;
                $this->productoPago = $Banco->productoPago;
                $this->tipo = $Banco->tipo;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "35");
            }

        }

    }


    /**
    * Realizar una consulta en la tabla de banco 'Banco'
    * de una manera personalizada
    *
    *
    * @param string $select campos de consulta
    * @param string $sidx columna para ordenar
    * @param string $sord orden los datos asc | desc
    * @param string $start inicio de la consulta
    * @param string $limit limite de la consulta
    * @param string $filters condiciones de la consulta
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return array resultado de la consulta
    * @throws Exception si el bono no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getBancosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $BancoMySqlDAO = new BancoMySqlDAO();

        $bonos = $BancoMySqlDAO->queryBancosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($bonos != null && $bonos != "") 
        {
            return $bonos;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "35");
        }


    }


    /**
     * Realiza una consulta personalizada en la tabla 'Banco' utilizando el método queryBancosCustom2.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Columna por la cual ordenar los resultados.
     * @param string $sord Orden de los resultados (ascendente o descendente).
     * @param string $start Índice de inicio para la consulta (paginación).
     * @param string $limit Límite de resultados a retornar.
     * @param string $filters Condiciones o filtros para la consulta.
     * @param boolean $searchOn Indica si se deben aplicar los filtros.
     *
     * @return array           Resultados de la consulta.
     * @throws Exception       Si no existen resultados para la consulta.
     */
    public function getBancosCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $BancoMySqlDAO = new BancoMySqlDAO();

        $bonos = $BancoMySqlDAO->queryBancosCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($bonos != null && $bonos != "") {
            return $bonos;
        } else {
            throw new Exception("No existe " . get_class($this), "35");
        }

    }
    /**
     * Realizar una consulta en la tabla de banco 'Banco'
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
     * @throws Exception si el bono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getBancosCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $BancoMySqlDAO = new BancoMySqlDAO();

        $bonos = $BancoMySqlDAO->queryBancosCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($bonos != null && $bonos != "")
        {
            return $bonos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "35");
        }


    }
}
?>

