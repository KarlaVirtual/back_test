<?php 
namespace Backend\dto;
use \Backend\mysql\MenuMySqlDAO;
/** 
* Clase 'Menu'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Menu'
* 
* Ejemplo de uso: 
* $Menu = new Menu();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Menu
{

    /**
    * Representación de la columna 'menuId' de la tabla 'Menu'
    *
    * @var string
    */  
    public $menuId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Menu'
    *
    * @var string
    */  
    public $descripcion;

    /**
    * Representación de la columna 'pagina' de la tabla 'Menu'
    *
    * @var string
    */  
    public $pagina;

    /**
    * Representación de la columna 'orden' de la tabla 'Menu'
    *
    * @var string
    */  
    public $orden;

    /**
    * Representación de la columna 'texto' de la tabla 'Menu'
    *
    * @var string
    */  
    public $texto;

    /**
    * Representación de la columna 'version' de la tabla 'Menu'
    *
    * @var string
    */  
    public $version;

    /**
    * Representación de la columna 'icon' de la tabla 'Menu'
    *
    * @var string
    */
    public $icon;

    /**
    * Constructor de clase
    *
    *
    * @param String $menuId id del menu
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($menuId="")
    {
        if ($menuId != "")
        {

            $MenuMySqlDAO = new MenuMySqlDAO();

            $Menu = $MenuMySqlDAO->load($menuId);


            if ($Menu != null && $Menu != "")
            {
                $this->menuId = $Menu->menuId;
                $this->descripcion = $Menu->descripcion;
                $this->pagina = $Menu->pagina;
                $this->orden =  $Menu->pagina;
                $this->texto = $Menu->texto;
                $this->version = $Menu->version;
                $this->icon = $Menu->icon;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "109");
            }

        }
    }

    /**
    * Realizar una consulta en la tabla de detalles de menus 'Menu'
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
    * @throws Exception si los menús no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getMenusCustom($select="", $sidx="", $sord="", $start="", $limit="", $filters="", $searchOn="")
    {

        $MenuMySqlDAO = new MenuMySqlDAO();

        $menus = $MenuMySqlDAO->queryMenusCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($menus != null && $menus != "") 
        {
            return $menus;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

}
