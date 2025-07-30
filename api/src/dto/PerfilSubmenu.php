<?php namespace Backend\dto;
use Exception;
use \Backend\mysql\PerfilSubmenuMySqlDAO;
/**
* Clase 'PerfilSubmenu'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'PerfilSubmenu'
*
* Ejemplo de uso:
* $PerfilSubmenu = new PerfilSubmenu();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class PerfilSubmenu
{

    /**
    * Representación de la columna 'perfilId' de la tabla 'PerfilSubmenu'
    *
    * @var string
    */
    public $perfilId;

    /**
    * Representación de la columna 'submenuId' de la tabla 'PerfilSubmenu'
    *
    * @var string
    */
    public $submenuId;

    /**
    * Representación de la columna 'adicionar' de la tabla 'PerfilSubmenu'
    *
    * @var string
    */
    public $adicionar;

    /**
    * Representación de la columna 'editar' de la tabla 'PerfilSubmenu'
    *
    * @var string
    */
    public $editar;

    /**
    * Representación de la columna 'eliminar' de la tabla 'PerfilSubmenu'
    *
    * @var string
    */
    public $eliminar;

    /**
    * Representación de la columna 'pais' de la tabla 'PerfilSubmenu'
    *
    * @var string
    */
    public $pais;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'PerfilSubmenu'
    *
    * @var string
    */
    public $usuarioId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'PerfilSubmenu'
     *
     * @var string
     */
    public $mandante;



    /**
     * Constructor de clase
     *
     *
     * @param String perfilId id del perfil
     * @param String submenuId submenuId
     * @param String usuarioId usuarioId
     *
     *
    * @return no
    * @throws Exception si PerfilSubmenu no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($perfilId="", $submenuId="", $usuarioId="")
    {
        if ($perfilId != "" && $submenuId != "" && $usuarioId != "")
        {

            $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
            $PerfilSubmenu = $PerfilSubmenuMySqlDAO->loadByUsuarioId($perfilId, $submenuId,$usuarioId);
            if ($PerfilSubmenu != "" && $PerfilSubmenu != null)
            {
                $this->perfilId = $PerfilSubmenu->perfilId;
                $this->submenuId = $PerfilSubmenu->submenuId;
                $this->adicionar = $PerfilSubmenu->adicionar;
                $this->editar = $PerfilSubmenu->editar;
                $this->eliminar = $PerfilSubmenu->eliminar;
                $this->pais = $PerfilSubmenu->pais;
                $this->usuarioId = $PerfilSubmenu->usuarioId;
                $this->mandante = $PerfilSubmenu->mandante;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "01");
            }

        }
        else if ($perfilId != "" && $submenuId != "")
        {

            $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
            $PerfilSubmenu = $PerfilSubmenuMySqlDAO->load($perfilId, $submenuId);
            if ($PerfilSubmenu != "" && $PerfilSubmenu != null)
            {
                $this->perfilId = $PerfilSubmenu->perfilId;
                $this->submenuId = $PerfilSubmenu->submenuId;
                $this->adicionar = $PerfilSubmenu->adicionar;
                $this->editar = $PerfilSubmenu->editar;
                $this->eliminar = $PerfilSubmenu->eliminar;
                $this->pais = $PerfilSubmenu->pais;
                $this->usuarioId = $PerfilSubmenu->usuarioId;
                $this->mandante = $PerfilSubmenu->mandante;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "01");
            }
        }

    }


    /**
    * Realizar una consulta en la tabla de submenús de los perfiles 'PerfilSubmenu'
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
    public function getPerfilSubmenusCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {
        $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
        $menus = $PerfilSubmenuMySqlDAO->queryPerfilSubmenusCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);
        if ($menus != null && $menus != "")
        {
            return $menus;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Obtiene los submenús de perfil personalizados según los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @return array Lista de submenús de perfil personalizados.
     * @throws Exception Si no existen submenús de perfil personalizados.
     */
    public function getPerfilSubmenusRecursoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
        $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
        $menus = $PerfilSubmenuMySqlDAO->queryPerfilSubmenusRecursoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);
        if ($menus != '') return $menus;
        else throw new Exception('No existe ' . get_class($this), '01');
    }


    /**
     * Obtiene un perfil genérico personalizado basado en los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @return array Lista de menús obtenidos según los criterios especificados.
     * @throws Exception Si no se encuentran menús que coincidan con los criterios.
     */
    public function getPerfilGenericCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
        $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
        $menus = $PerfilSubmenuMySqlDAO->queryPerfilGenericCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);
        if(empty($menus)) throw new Exception("No existe " . get_class($this), "01");

        return $menus;
    }

}
