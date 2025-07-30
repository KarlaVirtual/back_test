<?php namespace Backend\dto;
use Backend\mysql\DepartamentoMySqlDAO;
use Exception;

/**
* Clase 'DatosProveedore'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'DatosProveedore'
* 
* Ejemplo de uso: 
* $DatosProveedore = new DatosProveedore();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Departamento
{

    /**
    * Representación de la columna 'deptoId' de la tabla 'Departamento'
    *
    * @var string
    */ 
	var $deptoId;
	
    /**
    * Representación de la columna 'deptoCod' de la tabla 'Departamento'
    *
    * @var string
    */ 
	var $deptoCod;
	
    /**
    * Representación de la columna 'deptoNom' de la tabla 'Departamento'
    *
    * @var string
    */ 
	var $deptoNom;
	
    /**
    * Representación de la columna 'paisId' de la tabla 'Departamento'
    *
    * @var string
    */ 
	var $paisId;
	var $longitud;
	var $latitud;

    /**
    * Constructor de clase
    *
    *
    * @param no         
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($departamentoId='')
    {
        if ($departamentoId != "")
        {


            $DepartamentoMySqlDAO = new DepartamentoMySqlDAO();

            $Departamento = $DepartamentoMySqlDAO->load($departamentoId);


            if ($Departamento != null && $Departamento != "")
            {


                $this->deptoId = $Departamento->deptoId;
                $this->deptoCod = $Departamento->deptoCod;
                $this->deptoNom = $Departamento->deptoNom;
                $this->paisId = $Departamento->paisId;
                $this->longitud = $Departamento->longitud;
                $this->latitud = $Departamento->latitud;


            } else {
                throw new Exception("No existe " . get_class($this), "19");

            }

        }


    }

    /**
     * Obtiene una lista personalizada de departamentos.
     *
     * @param string $sidx El índice de ordenación.
     * @param string $sord El orden de clasificación (ascendente o descendente).
     * @param int $start El índice de inicio para la paginación.
     * @param int $limit El número máximo de registros a devolver.
     * @param array $filters Filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $mandante (Opcional) El mandante para filtrar los resultados.
     * @return array Los datos de los departamentos personalizados.
     * @throws Exception Si no se encuentran datos.
     */
    public function getDartementosCustom($sidx, $sord, $start, $limit, $filters, $searchOn, $mandante = '') {
        $DepartamentoMySqlDAO = new DepartamentoMySqlDAO();
        $data = $DepartamentoMySqlDAO->queryDepartamentosCustom($sidx, $sord, $start, $limit, $filters, $searchOn, $mandante = '');

        if($data == '') throw new Exception('No existe ' . get_class($this), 01);
        return $data;
    }

    /**
     * Obtiene el ID del departamento.
     *
     * @return string
     */
    public function getDeptoId() {
        return $this->deptoId;
    }

    /**
     * Establece el ID del departamento.
     *
     * @param string $deptoId
     */
    public function setDeptoId($deptoId) {
        $this->deptoId = $deptoId;
    }

    /**
     * Obtiene el código del departamento.
     *
     * @return string
     */
    public function getDeptoCod() {
        return $this->deptoCod;
    }

    /**
     * Establece el código del departamento.
     *
     * @param string $deptoCod
     */
    public function setDeptoCod($deptoCod) {
        $this->deptoCod = $deptoCod;
    }

    /**
     * Obtiene el nombre del departamento.
     *
     * @return string
     */
    public function getDeptoNom() {
        return $this->deptoNom;
    }

    /**
     * Establece el nombre del departamento.
     *
     * @param string $deptoNom
     */
    public function setDeptoNom($deptoNom) {
        $this->deptoNom = $deptoNom;
    }

    /**
     * Obtiene el ID del país.
     *
     * @return string
     */
    public function getPaisId() {
        return $this->paisId;
    }

    /**
     * Establece el ID del país.
     *
     * @param string $paisId
     */
    public function setPaisId($paisId) {
        $this->paisId = $paisId;
    }
}
?>
