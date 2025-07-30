<?php namespace Backend\dto;
use Backend\mysql\RegistroRapidoMySqlDAO;

/** 
* Clase 'RegistroRapido'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'RegistroRapido'
* 
* Ejemplo de uso: 
* $RegistroRapido = new RegistroRapido();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class RegistroRapido
{

    /**
    * Representación de la columna 'registroId' de la tabla 'RegistroRapido'
    *
    * @var string
    */		
	var $registroId;

    /**
    * Representación de la columna 'tipoDoc' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $tipoDoc;

    /**
    * Representación de la columna 'cedula' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $cedula;

    /**
    * Representación de la columna 'paisId' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $paisId;

    /**
    * Representación de la columna 'moneda' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $moneda;

    /**
    * Representación de la columna 'nombre1' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $nombre1;

    /**
    * Representación de la columna 'nombre2' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $nombre2;

    /**
    * Representación de la columna 'apellido1' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $apellido1;

    /**
    * Representación de la columna 'apellido2' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $apellido2;

    /**
    * Representación de la columna 'mandante' de la tabla 'RegistroRapido'
    *
    * @var string
    */
	var $mandante;

    /**
     * RegistroRapido constructor.
     * @param string $registroId ID Registro
     */
    public function __construct($registroId = null)
    {
        $this->registroId = $registroId;
    }


    /**
     * Obtiene el valor de la columna 'tipoDoc' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getTipoDoc()
    {
        return $this->tipoDoc;
    }

    /**
     * Define el valor de la columna 'tipoDoc' de la tabla 'RegistroRapido'.
     * @param string $tipoDoc Tipo Documento
     * @return void
     */
    public function setTipoDoc($tipoDoc)
    {
        $this->tipoDoc = $tipoDoc;
    }

    /**
     * Obtiene el valor de la columna 'cedula' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Define el valor de la columna 'cedula' de la tabla 'RegistroRapido'.
     * @param string $cedula Cedula
     * @return void
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    }

    /**
     * Obtiene el valor de la columna 'paisId' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Define el valor de la columna 'paisId' de la tabla 'RegistroRapido'.
     * @param string $paisId ID Pais
     * @return void
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtiene el valor de la columna 'moneda' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Define el valor de la columna 'moneda' de la tabla 'RegistroRapido'.
     * @param string $moneda Moneda
     * @return void
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    }

    /**
     * Obtiene el valor de la columna 'nombre1' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * Define el valor de la columna 'nombre1' de la tabla 'RegistroRapido'.
     * @param string $nombre1 Valor nombre 1
     * @return void
     */
    public function setNombre1($nombre1)
    {
        $this->nombre1 = $nombre1;
    }

    /**
     * Obtiene el valor de la columna 'nombre2' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * Define el valor de la columna 'nombre2' de la tabla 'RegistroRapido'.
     * @param string $nombre2 Nombre 2
     * @return void
     */
    public function setNombre2($nombre2)
    {
        $this->nombre2 = $nombre2;
    }

    /**
     * Obtiene el valor de la columna 'apellido1' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Define el valor de la columna 'apellido1' de la tabla 'RegistroRapido'.
     * @param string $apellido1 Apellido 1
     * @return void
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;
    }

    /**
     * Obtiene el valor de la columna 'apellido2' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Define el valor de la columna 'apellido2' de la tabla 'RegistroRapido'.
     * @param string $apellido2 Apellido 2
     * @return void
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;
    }

    /**
     * Obtiene el valor de la columna 'mandante' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Define el valor de la columna 'mandante' de la tabla 'RegistroRapido'.
     * @param string $mandante Madante
     * @return void
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el valor de la columna 'registroId' de la tabla 'RegistroRapido'.
     * @return string
     */
    public function getRegistroId()
    {
        return $this->registroId;
    }

    /**
     * Obtiene registros rápidos personalizados según los parámetros proporcionados.
     *
     * @param string $select Columnas a seleccionar en la consulta.
     * @param string $sidx Índice de la columna para ordenar.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento de los resultados.
     * 
     * @return mixed Registros rápidos obtenidos de la consulta.
     * @throws Exception Si no se encuentran registros.
     */
    public function getRegistrosRapidosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $RegistroRapidoMySqlDAO = new RegistroRapidoMySqlDAO();

        $Usuario = $RegistroRapidoMySqlDAO->queryRegistroRapidoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($Usuario != null && $Usuario != "") {

            return $Usuario;

        } else {
            throw new Exception("No existe " . get_class($this), "112");
        }

    }
		
}
?>