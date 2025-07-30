<?php namespace Backend\dto; namespace Backend\dto;
use Backend\mysql\TransprodLogMySqlDAO;
use Exception;
/** 
* Clase 'TransprodLog'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TransprodLog'
* 
* Ejemplo de uso: 
* $TransprodLog = new TransprodLog();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransprodLog
{

    /**
    * Representación de la columna 'transprodlogId' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $transprodlogId;

    /**
    * Representación de la columna 'transproductoId' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $transproductoId;

    /**
    * Representación de la columna 'estado' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'tipoGenera' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $tipoGenera;

    /**
    * Representación de la columna 'comentario' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $comentario;

    /**
    * Representación de la columna 'tValue' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $tValue;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TransprodLog'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Constructor de clase
    *
    *
    * @param String $transprodlogId transprodlogId
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transprodlogId="", $transproducto_id="", $comentario="")
    {
        if ($transproducto_id != "" && $comentario != "")
        {
            $this->transproductoId = $transproducto_id;
            $this->comentario = $comentario;

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO();

            $TransprodLog = $TransprodLogMySqlDAO->queryByTransproductoIdAndComentario($transproducto_id,$comentario);
            $TransprodLog = $TransprodLog[0];

            if ($TransprodLog != null && $TransprodLog != "")
            {
                $this->transproductoId = $TransprodLog->transproductoId;
                $this->comentario = $TransprodLog->comentario;
                $this->estado = $TransprodLog->estado;
                $this->tValue = $TransprodLog->tValue;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "103");
            }
        }elseif($transprodlogId != "")
        {
            $this->transprodlogId = $transprodlogId;
        }
    }




    /**
     * Obtener el campo transproductoId de un objeto
     *
     * @return String transproductoId transproductoId
     * 
     */
    public function getTransproductoId()
    {
        return $this->transproductoId;
    }

    /**
     * Modificar el campo 'transproductoId' de un objeto
     *
     * @param String $transproductoId transproductoId
     *
     * @return no
     *
     */
    public function setTransproductoId($transproductoId)
    {
        $this->transproductoId = $transproductoId;
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
     * Obtener el campo tipoGenera de un objeto
     *
     * @return String tipoGenera tipoGenera
     * 
     */
    public function getTipoGenera()
    {
        return $this->tipoGenera;
    }

    /**
     * Modificar el campo 'tipoGenera' de un objeto
     *
     * @param String $tipoGenera tipoGenera
     *
     * @return no
     *
     */
    public function setTipoGenera($tipoGenera)
    {
        $this->tipoGenera = $tipoGenera;
    }

    /**
     * Obtener el campo comentario de un objeto
     *
     * @return String comentario comentario
     * 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Modificar el campo 'comentario' de un objeto
     *
     * @param String $comentario comentario
     *
     * @return no
     *
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    /**
     * Obtener el campo tValue de un objeto
     *
     * @return String tValue tValue
     * 
     */
    public function getTValue()
    {
        return $this->tValue;
    }

    /**
     * Modificar el campo 'tValue' de un objeto
     *
     * @param String $tValue tValue
     *
     * @return no
     *
     */
    public function setTValue($tValue)
    {
        $this->tValue = $tValue;
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
    * Insertar un registro en la base de datos 
    *
    *
    * @param Objeto $transaction transacción
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function insert($transaction)
    {
        $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($transaction);

        return $TransprodLogMySqlDAO -> insert($this);

    }

    
    }

?>