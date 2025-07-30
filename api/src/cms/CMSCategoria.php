<?php
namespace Backend\cms;
use Backend\dto\Categoria;
use Backend\dto\CategoriaMandante;
/**
* Clase 'CMSCategoria'
* 
* Esta clase provee datos de CMSCategoria
* 
* Ejemplo de uso: 
* $CMSCategoria = new CMSCategoria();
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
class CMSCategoria
{

    /**
    * Representación de 'categoriaId'
    *
    * @var string
    */  
    private $categoriaId;

    /**
    * Representación de 'tipo'
    *
    * @var string
    */
    private $tipo;

    /**
     * Representación de 'partner'
     *
     * @var string
     */
    private $partner;
    private $paisId;

    /**
    * Constructor de clase
    *
    *
    * @param String $categoriaId categoriaId
    * @param String $tipo tipo
    * 
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */ 
    public function __construct($categoriaId, $tipo, $partner="",$paisId="")
    {
        $this->categoriaId = $categoriaId;
        $this->tipo = $tipo;
        $this->partner = $partner;
        $this->paisId = $paisId;
    }

    /**
     * Obtener las categorias
     *
     * @return array categorias categorias
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */ 
    public function getCategorias()
    {
        $Categoria = new Categoria();
        $Categoria->setTipo($this->tipo);

        $Categorias = $Categoria->getCategoriasTipo($this->partner,$this->paisId);

        $data = array();

        foreach ($Categorias as $categoria) {
            $array = array(
                "id" => $categoria->categoriaId,
                "descripcion" => $categoria->descripcion,
                "estado" => $categoria->estado,
                "slug" => $categoria->slug,
                "imagen" => $categoria->imagen

            );

            array_push($data, $array);
        }
        $result = array();

        $result["data"] = $data;
        $result["total"] = oldCount($Categorias);


        return json_encode($result);

    }

    public function getCategoriasMandante() {

        $categoriaMandante = new CategoriaMandante();
        $categories_mandante = $categoriaMandante->getCategoriasTipo($this->tipo, $this->partner, $this->paisId);
        $categories = [];

        foreach($categories_mandante as $value) {
            $data = [];
            $data['id'] = $value->getCatmandanteId();
            $data['descripcion'] = $value->getDescripcion();
            $data['estado'] = $value->getEstado();
            $data['slug'] = $value->getSlug();
            $data['imagen'] = $value->getImagen();

            array_push($categories, $data);
        }

        return json_encode(['data' => $categories, 'count' => oldCount($categories)]);
    }

}
