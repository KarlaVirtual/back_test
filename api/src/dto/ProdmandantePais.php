<?php namespace Backend\dto;
use Backend\mysql\ProdmandantePaisMySqlDAO;

/**
* Clase 'ProdmandantePais'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ProdmandantePais'
* 
* Ejemplo de uso: 
* $ProdmandantePais = new ProdmandantePais();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProdmandantePais
{

    /**
    * Representación de la columna 'pmandantepaisId' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 		
	var $pmandantepaisId;

    /**
    * Representación de la columna 'productoId' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $productoId;

    /**
    * Representación de la columna 'mandante' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $mandante;

    /**
    * Representación de la columna 'paisId' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $paisId;

    /**
    * Representación de la columna 'estado' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $estado;

    /**
    * Representación de la columna 'verifica' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $verifica;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $fechaModif;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $usumodifId;

    /**
    * Representación de la columna 'max' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $max;

    /**
    * Representación de la columna 'min' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $min;

    /**
    * Representación de la columna 'string' de la tabla 'ProdmandantePais'
    *
    * @var string
    */ 
	var $tiempoProcesamiento;

    /**
    * Representación de la columna 'extraInfo' de la tabla 'ProdmandantePais'
    *
    * @var string
    */
	var $extraInfo;


    /**
     * Constructor de la clase ProdmandantePais.
     *
     * @param string $pmandantepaisId El ID del ProdmandantePais. Si se proporciona, se cargará la información correspondiente desde la base de datos.
     * @throws Exception Si no se encuentra un registro con el ID proporcionado.
     */
    public function __construct($pmandantepaisId="")
    {
        if ($pmandantepaisId != "")
        {

            $this->pmandantepaisId = $pmandantepaisId;

            $ProdmandantePaisMySqlDAO= new ProdmandantePaisMySqlDAO();

            $ProdmandatePais = $ProdmandantePaisMySqlDAO->load($pmandantepaisId);

            if ($ProdmandatePais != null && $ProdmandatePais != "")
            {
                $this->pmandantepaisId = $ProdmandatePais->pmandantepaisId;
                $this->productoId = $ProdmandatePais->productoId;
                $this->mandante = $ProdmandatePais->mandante;
                $this->paisId = $ProdmandatePais->paisId;
                $this->estado = $ProdmandatePais->estado;
                $this->verifica = $ProdmandatePais->verifica;
                $this->fechaCrea = $ProdmandatePais->fechaCrea;
                $this->fechaModif = $ProdmandatePais->fechaModif;
                $this->usucreaId = $ProdmandatePais->usucreaId;
                $this->usumodifId = $ProdmandatePais->usumodifId;
                $this->max = $ProdmandatePais->max;
                $this->min = $ProdmandatePais->min;
                $this->tiempoProcesamiento = $ProdmandatePais->tiempoProcesamiento;
                $this->extraInfo = $ProdmandatePais->extraInfo;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "67");
            }
        }

    }

    /**
     * obtener el ID del ProdmandantePais
     * @return string
     */
    public function getPmandantepaisId()
    {
        return $this->pmandantepaisId;
    }

    /**
     * Definir el ID del ProdmandantePais
     * @param string $pmandantepaisId
     */
    public function setPmandantepaisId($pmandantepaisId)
    {
        $this->pmandantepaisId = $pmandantepaisId;
    }

    /**
     * Obtener el ID del Producto
     * @return string
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Definir el ID del Producto
     * @param string $productoId
     */
    public function setProductoId(string $productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * Obtener el mandante
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Definir el mandante
     * @param string $mandante
     */
    public function setMandante(string $mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el ID del país
     * @return string
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Definir el ID del país
     * @param string $paisId
     */
    public function setPaisId(string $paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtener el estado
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Definir el estado
     * @param string $estado
     */
    public function setEstado(string $estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el verifica
     * @return string
     * 
     */
    public function getVerifica()
    {
        return $this->verifica;
    }

    /**
     * Definir el verifica
     * @param string $verifica
     */
    public function setVerifica(string $verifica)
    {
        $this->verifica = $verifica;
    }

    /**
     * Obtener la fecha de creación
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Definir la fecha de creación
     * @param string $fechaCrea
     */
    public function setFechaCrea(string $fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener la fecha de modificación
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Definir la fecha de modificación
     * @param string $fechaModif
     */
    public function setFechaModif(string $fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el ID del usuario que creó el registro
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Definir el ID del usuario que creó el registro
     * @param string $usucreaId
     */
    public function setUsucreaId(string $usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el ID del usuario que modificó el registro
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Definir el ID del usuario que modificó el registro
     * @param string $usumodifId
     */
    public function setUsumodifId(string $usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el max
     * @return string
     */
    public function getMax()
    {
        return $this->max;
    }


    /**
     * Definir el max
     * @param string $max
     */
    public function setMax(string $max)
    {
        $this->max = $max;
    }

    /**
     * Obtener el min
     * @return string
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Definir el min
     * @param string $min
     */
    public function setMin(string $min)
    {
        $this->min = $min;
    }

    /**
     * Obtener el tiempo de procesamiento
     * @return string
     */
    public function getTiempoProcesamiento()
    {
        return $this->tiempoProcesamiento;
    }

    /**
     * Definir el tiempo de procesamiento
     * @param string $tiempoProcesamiento
     */
    public function setTiempoProcesamiento(string $tiempoProcesamiento)
    {
        $this->tiempoProcesamiento = $tiempoProcesamiento;
    }

    /**
     * Obtener la información extra
     * @return mixed
     */
    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * Definir la información extra
     * @param mixed $extraInfo
     */
    public function setExtraInfo($extraInfo)
    {
        $this->extraInfo = $extraInfo;
    }



    /**
     * Ejecuta una consulta SQL utilizando la transacción proporcionada.
     *
     * @param mixed $transaccion La transacción a utilizar para la consulta.
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la consulta SQL, decodificado como un objeto.
     */
    public function execQuery($transaccion, $sql)
    {

        $ProdmandantePaisMySqlDAO = new ProdmandantePaisMySqlDAO($transaccion);
        $return = $ProdmandantePaisMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }

}
?>
