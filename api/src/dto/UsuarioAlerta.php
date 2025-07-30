<?php 
namespace Backend\dto;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioAlerta'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioAlerta'
* 
* Ejemplo de uso: 
* $UsuarioAlerta = new UsuarioAlerta();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioAlerta
{

    /**
    * Representación de la columna 'usualertaId' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $usualertaId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'accion' de la tabla 'UsuarioAlerta'
    *
    * @var string
    */
    var $accion;

    /**
     * Representación de la columna 'nombre' de la tabla 'UsuarioAlerta'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'descripcion' de la tabla 'UsuarioAlerta'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'fecha_expiracion' de la tabla 'UsuarioAlerta'
     *
     * @var string
     */
    var $fechaFin;

    /**
     * Representación de la columna 'fecha_inicio' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $fechaInicio;

    /**
     * Representación de la columna 'tipo_tiempo' de la tabla 'UsuarioAlerta'
     *
     * @var string
     */
    var $tipoTiempo;
    

    /**
    * Constructor de clase
    *
    *
    * @param String $usualertaId usualertaId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioAlerta no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usualertaId="", $usuarioId="")
    {
        if ($usualertaId != "") 
        {

            $this->usualertaId = $usualertaId;

            $UsuarioAlertaMySqlDAO = new UsuarioAlertaMySqlDAO();

            $UsuarioAlerta = $UsuarioAlertaMySqlDAO->load($usualertaId);

            if ($UsuarioAlerta != null && $UsuarioAlerta != "") 
            {
                $this->usualertaId = $UsuarioAlerta->usualertaId;
                $this->usuarioId = $UsuarioAlerta->usuarioId;
                $this->tipo = $UsuarioAlerta->tipo;
                $this->valor = $UsuarioAlerta->valor;
                $this->fechaCrea = $UsuarioAlerta->fechaCrea;
                $this->usucreaId = $UsuarioAlerta->usucreaId;
                $this->fechaModif = $UsuarioAlerta->fechaModif;
                $this->usumodifId = $UsuarioAlerta->usumodifId;
                $this->estado = $UsuarioAlerta->estado;
                $this->accion = $UsuarioAlerta->accion;
                $this->nombre = $UsuarioAlerta->nombre;
                $this->descripcion = $UsuarioAlerta->descripcion;
                $this->fechaFin = $UsuarioAlerta->fechaFin;
                $this->tipoTiempo = $UsuarioAlerta->tipoTiempo;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "45");
            }
        }

    }

    /**
    * Realizar una consulta en la tabla de UsuarioAlertas 'UsuarioAlertas'
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
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioAlertasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioAlertaMySqlDAO = new UsuarioAlertaMySqlDAO();

        $Productos = $UsuarioAlertaMySqlDAO->queryUsuarioAlertasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "45");
        }

    }





    /**
     * Obtener el campo usualertaId de un objeto
     *
     * @return String usualertaId usualertaId
     * 
     */
    public function getUsualertaId()
    {
        return $this->usualertaId;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     * 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
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
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     * 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     * 
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
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
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     * 
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
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
     * Obtener el campo accion de un objeto
     *
     * @return String accion accion
     * 
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Modificar el campo 'accion' de un objeto
     *
     * @param String $accion accion
     *
     * @return no
     *
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;
    }

    /**
     * Obtener el campo nombre de un objeto
     *
     * @return String nombre
     *
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Modificar el campo 'nombre' de un objeto
     *
     * @param String $nombre nombre
     *
     * @return no
     *
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion
     *
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar el campo 'descripcion' de un objeto
     *
     * @param String $descripcion descripcion
     *
     * @return no
     *
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener el campo fechaFin de un objeto
     *
     * @return String fechaFin
     *
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Modificar el campo 'fechaFin' de un objeto
     *
     * @param String $fechaFin fechaFin
     *
     * @return no
     *
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * Obtener el campo tipoTiempo de un objeto
     *
     * @return String tipoTiempo
     *
     */
    public function getTipoTiempo()
    {
        return $this->tipoTiempo;
    }

    /**
     * Modificar el campo 'tipoTiempo' de un objeto
     *
     * @param String $tipoTiempo tipoTiempo
     *
     * @return no
     *
     */
    public function setTipoTiempo($tipoTiempo)
    {
        $this->tipoTiempo = $tipoTiempo;
    }

    /**
     * Obtener el campo fechaInicio de un objeto
     *
     * @return String fechaInicio
     *
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Modificar el campo 'fechaInicio' de un objeto
     *
     * @param String $fechaInicio fechaInicio
     *
     * @return no
     *
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }






    /**
    * Consulta las alertas de un usuario
    *
    *
    * @param String $objectBase objectBase
    * @param Objeto $usuario usuario
    * @param String $tipo tipo
    * @param String $message message
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function CheckAlert($objectBase,$tipo,$usuario,$message){

        if($tipo !=""){
            $MaxRows = 100;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usuario_alerta.tipo", "data" => "$tipo", "op" => "eq"));
          //  array_push($rules, array("field" => "usuario_alerta.usuario_id", "data" => "'$usuario','0'", "op" => "IN"));
            array_push($rules, array("field" => "usuario_alerta.estado", "data" => "A", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $this->getUsuarioAlertasCustom("  usuario_alerta.* ", "usuario_alerta.usualerta_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $usuarios = json_decode($usuarios);

            foreach ($usuarios->data as $value) {
                $array = [];

                $array["Id"] = $value->{"usuario_alerta.usualerta_id"};
                $array["PlayerId"] = $value->{"usuario_alerta.usuario_id"};
                $array["Type"] = $value->{"usuario_alerta.tipo"};
                $array["Query"] = json_decode($value->{"usuario_alerta.valor"});
                $array["Action"] = json_decode($value->{"usuario_alerta.accion"});
                $array["State"] = $value->{"usuario_alerta.estado"};
                $array["ColumnsQ"] = array();
                $array["OperationsQ"] = ['>', '<', '<=', '>=', '==', '=', 'is'];
                $array["ColumnsA"] = array();
                $array["OperationsA"] = ['>', '<', '<=', '>=', '==', '=', 'is'];


                $queries =($array["Query"]);
                $cumple = true;

                foreach ($queries->operands as $query) {

                    if($query->colName != ""){
                        switch ($query->colName->Id){
                            case "48":

                                if(!$this->compareValues($query->operation,$query->value,$objectBase->productoId)){
                                    $cumple=false;
                                }
                                break;
                        }
                    }


                }

                if($cumple){

                    $queries =($array["Action"]);

                    foreach ($queries->operands as $query) {

                        switch ($query->colName->Id){
                            case "43":

                                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-soporte' > /dev/null & ");

                                break;
                        }

                    }

                }


            }
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-soporte' > /dev/null & ");

        }

    }

    /**
    * Comprar dos valores y devolver el resultado
    *
    *
    * @param String $oper oper
    * @param String $value1 value1
    * @param String $value2 value2
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function compareValues($oper,$value1,$value2){

        switch ($oper){

            case ">":
                return $value1 > $value2;
                break;

            case "<":
                return $value1 < $value2;
                break;

            case ">=":
                return $value1 >= $value2;
                break;

            case "<=":
                return $value1 <= $value2;
                break;

            case "==":
                return $value1 == $value2;
                break;

            case "=":
                return $value1 = $value2;
                break;

            case "is":
                return $value1 = $value2;
                break;
        }

    }
    


}

?>
