<?php namespace Backend\dto;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioBloqueado'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioBloqueado'
* 
* Ejemplo de uso: 
* $UsuarioBloqueado = new UsuarioBloqueado();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioBloqueado
{

    /**
    * Representación de la columna 'usubloqueadoId' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
	var $usubloqueadoId;

    /**
    * Representación de la columna 'cedula' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
	var $cedula;

    /**
    * Representación de la columna 'primerNombre' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
	var $primerNombre;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'segundoNombre' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
	var $segundoNombre;

    /**
    * Representación de la columna 'primerApellido' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
	var $primerApellido;

    /**
    * Representación de la columna 'segundoApellido' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
    var $segundoApellido;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'tipoDocumento' de la tabla 'UsuarioBloqueado'
    *
    * @var string
    */
    var $tipoDocumento;

    /**
    * Constructor de clase
    *
    *
    * @param String $usubloqueadoId usubloqueadoId
    * @param String $cedula cedula
    *
    * @return no
    * @throws Exception si UsuarioBloqueado no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function __construct($usubloqueadoId="",$cedula="")
	{
		if ($usubloqueadoId != "") 
        {

			$UsuarioBloqueadoMySqlDAO = new UsuarioBloqueadoMySqlDAO();
			$UsuarioBloqueado = $UsuarioBloqueadoMySqlDAO->load($usubloqueadoId);

			$UsuarioBloqueado = $UsuarioBloqueado[0];


			if ($UsuarioBloqueado != null && $UsuarioBloqueado != "") 
            {

				$this->usubloqueadoId = $UsuarioBloqueado->usubloqueadoId;
				$this->cedula = $UsuarioBloqueado->cedula;
				$this->primerNombre = $UsuarioBloqueado->primerNombre;
				$this->mandante = $UsuarioBloqueado->mandante;
				$this->segundoNombre = $UsuarioBloqueado->segundoNombre;
                $this->primerApellido = $UsuarioBloqueado->primerApellido;
                $this->segundoApellido = $UsuarioBloqueado->segundoApellido;
                $this->tipo = $UsuarioBloqueado->tipo;
                $this->estado = $UsuarioBloqueado->estado;
                $this->usucreaId = $UsuarioBloqueado->usucreaId;
                $this->usumodifId = $UsuarioBloqueado->usumodifId;
                $this->tipoDocumento = $UsuarioBloqueado->tipoDocumento;

			}
			else 
            {
				throw new Exception("No existe " . get_class($this), "56");

			}

		}
        elseif ($cedula != "") 
        {

            $UsuarioBloqueadoMySqlDAO = new UsuarioBloqueadoMySqlDAO();
            $UsuarioBloqueado = $UsuarioBloqueadoMySqlDAO->queryByCedula($cedula);

            $UsuarioBloqueado = $UsuarioBloqueado[0];


            if ($UsuarioBloqueado != null && $UsuarioBloqueado != "") 
            {

                $this->usubloqueadoId = $UsuarioBloqueado->usubloqueadoId;
                $this->cedula = $UsuarioBloqueado->cedula;
                $this->primerNombre = $UsuarioBloqueado->primerNombre;
                $this->mandante = $UsuarioBloqueado->mandante;
                $this->segundoNombre = $UsuarioBloqueado->segundoNombre;
                $this->primerApellido = $UsuarioBloqueado->primerApellido;
                $this->segundoApellido = $UsuarioBloqueado->segundoApellido;
                $this->tipo = $UsuarioBloqueado->tipo;
                $this->estado = $UsuarioBloqueado->estado;
                $this->usucreaId = $UsuarioBloqueado->usucreaId;
                $this->usumodifId = $UsuarioBloqueado->usumodifId;
                $this->tipoDocumento = $UsuarioBloqueado->tipoDocumento;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "56");

            }
        }


    }





    /**
     * Obtener el campo usubloqueadoId de un objeto
     *
     * @return String usubloqueadoId usubloqueadoId
     * 
     */
    public function getUsubloqueadoId()
    {
        return $this->usubloqueadoId;
    }

    /**
     * Obtener el campo cedula de un objeto
     *
     * @return String cedula cedula
     * 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Modificar el campo 'cedula' de un objeto
     *
     * @param String $cedula cedula
     *
     * @return no
     *
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    }
    
    /**
     * Obtener el campo primerNombre de un objeto
     *
     * @return String primerNombre primerNombre
     * 
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * Modificar el campo 'primerNombre' de un objeto
     *
     * @param String $primerNombre primerNombre
     *
     * @return no
     *
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     * 
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo segundoNombre de un objeto
     *
     * @return String segundoNombre segundoNombre
     * 
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * Modificar el campo 'segundoNombre' de un objeto
     *
     * @param String $segundoNombre segundoNombre
     *
     * @return no
     *
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;
    }

    /**
     * Obtener el campo primerApellido de un objeto
     *
     * @return String primerApellido primerApellido
     * 
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Modificar el campo 'primerApellido' de un objeto
     *
     * @param String $primerApellido primerApellido
     *
     * @return no
     *
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;
    }

    /**
     * Obtener el campo segundoApellido de un objeto
     *
     * @return String segundoApellido segundoApellido
     * 
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Modificar el campo 'segundoApellido' de un objeto
     *
     * @param String $segundoApellido segundoApellido
     *
     * @return no
     *
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;
    }

    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
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
     * Obtener el campo tipoDocumento de un objeto
     *
     * @return String tipoDocumento tipoDocumento
     * 
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Modificar el campo 'tipoDocumento' de un objeto
     *
     * @param String $tipoDocumento tipoDocumento
     *
     * @return no
     *
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;
    }




    /**
    * Realizar una consulta en la tabla de UsuarioBloqueado 'UsuarioBloqueado'
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
    * @throws Exception si el usuario no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuariosBloqueadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioBloqueadoMySqlDAO = new UsuarioBloqueadoMySqlDAO();

        $Usuario = $UsuarioBloqueadoMySqlDAO->queryUsuariosBloqueadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuario != null && $Usuario != "") 
        {
            return $Usuario;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "56");
        }

    }


}
?>