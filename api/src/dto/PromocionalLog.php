<?php namespace Backend\dto;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;
/** 
* Clase 'PromocionalLog'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'PromocionalLog'
* 
* Ejemplo de uso: 
* $PromocionalLog = new PromocionalLog();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PromocionalLog
{

    /**
    * Representación de la columna 'promologId' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $promologId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'promocionalId' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $promocionalId;

    /**
    * Representación de la columna 'valor' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'valorPromocional' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $valorPromocional;

    /**
    * Representación de la columna 'valorBase' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $valorBase;

    /**
    * Representación de la columna 'estado' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'errorId' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $errorId;

    /**
    * Representación de la columna 'idExterno' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $idExterno;

    /**
    * Representación de la columna 'mandante' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'version' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $version;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'apostado' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $apostado;

    /**
    * Representación de la columna 'rollowerRequerido' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $rollowerRequerido;

    /**
    * Representación de la columna 'codigo' de la tabla 'PromocionalLog'
    *
    * @var string
    */
    var $codigo;



    /**
    * Constructor de clase
    *
    *
    * @param String $promocionalId promocionalId
    * @param String $version version
    * @param String $promologId promologId
    *
    * @return no
    * @throws Exception si PromocionalLog no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($promocionalId="",$version="",$promologId="")
    {
        if ($promocionalId != "" && $version != "") 
        {


            $PromocionalLogMySqlDAO = new PromocionalLogMySqlDAO();

            $PromocionalLog = $PromocionalLogMySqlDAO->queryByPromocionalIdAndVersion($promocionalId, $version);
            $PromocionalLog=$PromocionalLog[0];

            if ($PromocionalLog != null && $PromocionalLog != "") 
            {
                $this->promologId = $PromocionalLog->promologId;
                $this->usuarioId = $PromocionalLog->usuarioId;
                $this->promocionalId = $PromocionalLog->promocionalId;
                $this->tipo = $PromocionalLog->tipo;
                $this->valor = $PromocionalLog->valor;
                $this->valorPromocional = $PromocionalLog->valorPromocional;
                $this->valorBase = $PromocionalLog->valorBase;
                $this->estado = $PromocionalLog->estado;
                $this->errorId = $PromocionalLog->errorId;
                $this->idExterno = $PromocionalLog->idExterno;
                $this->mandante = $PromocionalLog->mandante;
                $this->version = $PromocionalLog->version;
                $this->apostado = $PromocionalLog->apostado;
                $this->rollowerRequerido = $PromocionalLog->rollowerRequerido;
                $this->codigo = $PromocionalLog->codigo;

                $this->usucreaId = $PromocionalLog->usucreaId;
                $this->usumodifId = $PromocionalLog->usumodifId;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "30");
            }

        }
        else if($promologId != "")
        {
        
            $PromocionalLogMySqlDAO = new PromocionalLogMySqlDAO();

            $PromocionalLog = $PromocionalLogMySqlDAO->load($promologId);

            if ($PromocionalLog != null && $PromocionalLog != "") 
            {
            
                $this->promologId = $PromocionalLog->promologId;
                $this->usuarioId = $PromocionalLog->usuarioId;
                $this->promocionalId = $PromocionalLog->promocionalId;
                $this->tipo = $PromocionalLog->tipo;
                $this->valor = $PromocionalLog->valor;
                $this->valorPromocional = $PromocionalLog->valorPromocional;
                $this->valorBase = $PromocionalLog->valorBase;
                $this->estado = $PromocionalLog->estado;
                $this->errorId = $PromocionalLog->errorId;
                $this->idExterno = $PromocionalLog->idExterno;
                $this->mandante = $PromocionalLog->mandante;
                $this->version = $PromocionalLog->version;
                $this->apostado = $PromocionalLog->apostado;
                $this->rollowerRequerido = $PromocionalLog->rollowerRequerido;
                $this->codigo = $PromocionalLog->codigo;

                $this->usucreaId = $PromocionalLog->usucreaId;
                $this->usumodifId = $PromocionalLog->usumodifId;
            
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "30");
            }

        }

    }


    /**
    * Obtener el campo log mediante el id promocional y la version
    *
    *
    * @param no
    *
    * @return Array resultado de la consulta
    * @throws Exception si PromocionalLog no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getLogsByPromocionalIdAndVersion()
    {


        $PromocionalLogMySqlDAO = new PromocionalLogMySqlDAO();

        $PromocionalLog = $PromocionalLogMySqlDAO->queryByPromocionalIdAndVersion($this->promocionalId,$this->version);

        if ($PromocionalLog != null && $PromocionalLog != "") 
        {
            return $PromocionalLog;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
    * Realizar una consulta en la tabla de PromocionalLog 'PromocionalLog'
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
    * @throws Exception si los deportes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getPromocionalLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $PromocionalLogMySqlDAO = new PromocionalLogMySqlDAO();

        $deportes = $PromocionalLogMySqlDAO->queryDeportesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($deportes != null && $deportes != "") 
        {
            return $deportes;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
    * Verificar rollower
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
    public function verifyRollower(){

        $PromocionalLogMySqlDAO = new PromocionalLogMySqlDAO();

        $PromocionalLogMySqlDAO->verifyRollower($this);
        $PromocionalLogMySqlDAO->getTransaction()->commit();


        $promocional= new PromocionalLog("","",$this->promologId);


        if($promocional->estado == "I")
        {

            $UsuarioMandante= new UsuarioMandante("",$this->usuarioId,"0");

            $UsuarioToken2 = new UsuarioToken("",'1', $UsuarioMandante->getUsumandanteId());

            $data = array(
                "7040" . $UsuarioToken2->getRequestId() . "5" => array(
                    "notifications" => array(
                        array(
                            "type"=>"bono",
                            "title"=>"BONUS",
                            "content"=>$promocional->valor
                        )
                    ),
                ),

            );

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            //$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
            /*$WebsocketUsuario = new WebsocketUsuario($UsuarioToken2->getRequestId(), $data);
            $WebsocketUsuario->sendWSMessage();*/


        }

    }


}

?>
