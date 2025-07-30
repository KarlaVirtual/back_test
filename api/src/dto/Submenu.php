<?php namespace Backend\dto;
use \Backend\mysql\SubmenuMySqlDAO;
use Exception;

/**
 * Clase 'Submenu'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'Submenu'
 *
 * Ejemplo de uso:
 * $Submenu = new Submenu();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class Submenu
{

    /**
     * Representación de la columna 'submenuId' de la tabla 'Submenu'
     *
     * @var string
     */
    public $submenuId;

    /**
     * Representación de la columna 'descripcion' de la tabla 'Submenu'
     *
     * @var string
     */
    public $descripcion;

    /**
     * Representación de la columna 'pagina' de la tabla 'Submenu'
     *
     * @var string
     */
    public $pagina;

    /**
     * Representación de la columna 'menuId' de la tabla 'Submenu'
     *
     * @var string
     */
    public $menuId;

    /**
     * Representación de la columna 'orden' de la tabla 'Submenu'
     *
     * @var string
     */
    public $orden;

    /**
     * Representación de la columna 'version' de la tabla 'Submenu'
     *
     * @var string
     */
    public $version;

    /**
     * Establece si el submenú es el principal
     *
     * @var bool
     */
    public $menuPrincipal;

    public $parent;

    /**
     * Constructor de clase
     *
     *
     * @param String $submenuId id del submenu
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($submenuId="",$pagina="", $version = ""){

        if ($submenuId != "")
        {

            $SubmenuMySqlDAO = new SubmenuMySqlDAO();

            $Submenu = $SubmenuMySqlDAO->load($submenuId);


            if ($Submenu != null && $Submenu != "")
            {
                $this->submenuId = $Submenu->submenuId;
                $this->descripcion = $Submenu->descripcion;
                $this->pagina = $Submenu->pagina;
                $this->menuId = $Submenu->menuId;
                $this->orden = $Submenu->orden;
                $this->version = $Submenu->version;
                $this->parent = $Submenu->parent;
                $this->menuPrincipal = $Submenu->menu_principal;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "109");
            }

        }elseif ($pagina != "" and $version != "")
        {

            $SubmenuMySqlDAO = new SubmenuMySqlDAO();

            $Submenu = $SubmenuMySqlDAO->loadByPagina($pagina, $version);


            if ($Submenu != null && $Submenu != "")
            {
                $this->submenuId = $Submenu->submenuId;
                $this->descripcion = $Submenu->descripcion;
                $this->pagina = $Submenu->pagina;
                $this->menuId = $Submenu->menuId;
                $this->orden = $Submenu->orden;
                $this->version = $Submenu->version;
                $this->menuPrincipal = $Submenu->menu_principal;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "109");
            }

        } elseif($pagina != '') {
            $SubmenuMySqlDAO = new SubmenuMySqlDAO();

            $Submenu = $SubmenuMySqlDAO->loadByPagina($pagina);


            if ($Submenu != null && $Submenu != "")
            {
                $this->submenuId = $Submenu->submenuId;
                $this->descripcion = $Submenu->descripcion;
                $this->pagina = $Submenu->pagina;
                $this->menuId = $Submenu->menuId;
                $this->orden = $Submenu->orden;
                $this->version = $Submenu->version;
                $this->menuPrincipal = $Submenu->menu_principal;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "109");
            }
        }
    }

    /**
     * Obtiene el ID del submenú
     *
     * @return string
     */
    public function getSubmenuId()
    {
        return $this->submenuId;
    }

    /**
     * Obtiene la descripción del submenú
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Establece la descripción del submenú
     *
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtiene la página del submenú
     *
     * @return string
     */
    public function getPagina()
    {
        return $this->pagina;
    }

    /**
     * Establece la página del submenú
     *
     * @param string $pagina
     */
    public function setPagina($pagina)
    {
        $this->pagina = $pagina;
    }

    /**
     * Obtiene el ID del menú al que pertenece el submenú
     *
     * @return string
     */
    public function getMenuId()
    {
        return $this->menuId;
    }

    /**
     * Establece el ID del menú al que pertenece el submenú
     *
     * @param string $menuId
     */
    public function setMenuId($menuId)
    {
        $this->menuId = $menuId;
    }

    /**
     * Obtiene el orden del submenú
     *
     * @return string
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Establece el orden del submenú
     *
     * @param string $orden
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    }

    /**
     * Obtiene la versión del submenú
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Establece la versión del submenú
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Establece el submenú padre
     *
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Obtiene el submenú padre
     *
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Obtiene si el submenú es el principal
     *
     * @return bool
     */
    public function getMenuPrincipal()
    {
        return $this->menuPrincipal;
    }

    /**
     * @param bool $menuPrincipal Establece si el submenú es el principal
     */
    public function setMenuPrincipal(bool $menuPrincipal)
    {
        $this->menuPrincipal = $menuPrincipal;
    }

    /**
     * Realizar una consulta en la tabla de submenús 'Submenu'
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
     * @throws Exception si los submenús no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getSubMenusCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $SubmenuMySqlDAO = new SubmenuMySqlDAO();

        $menus = $SubmenuMySqlDAO->querySubMenusCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($menus != null && $menus != "")
        {
            return $menus;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "109");
        }

    }

    public function getAllMenusCustom($select, $sidx, $sort, $start, $limit, $filters, $searchOn = false) {
        $SubmenuMySqlDAO = new SubmenuMySqlDAO();

        $menus = $SubmenuMySqlDAO->queryAllMenusCustom($select, $sidx, $sort, $start, $limit, $filters, $searchOn);

        if(empty($menus)) throw new \Exception('No existe ' . get_class($this), 109);

        return $menus;
    }

    public function getAllSubmenusCustom($select, $sidx, $sort, $start, $limit, $filters, $searchOn = false) {
        $SubmenuMySqlDAO = new SubmenuMySqlDAO();

        $menus = $SubmenuMySqlDAO->queryAllSubmenusCustom($select, $sidx, $sort, $start, $limit, $filters, $searchOn);

        if(empty($menus)) throw new \Exception('No existe ' . get_class($this), 109);

        return $menus;
    }

}
