<?php namespace Backend\dto;

use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioAutomation2MySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Exception;

/**
 * Clase 'UsuarioAutomation'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioAutomation'
 *
 * Ejemplo de uso:
 * $UsuarioAutomation = new UsuarioAutomation();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioAutomation2
{

    /**
     * Representación de la columna 'usuautomationId' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $usuautomationId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'tipo' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'valor' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'accion' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $accion;

    /**
     * Representación de la columna 'nombre' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'descripcion' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'fecha_expiracion' de la tabla 'UsuarioAutomation'
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
     * Representación de la columna 'tipo_tiempo' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $tipoTiempo;

    /**
     * Representación de la columna 'usuario_repite' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $usuarioRepite;

    /**
     * Representación de la columna 'automation_id' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $automationId;


    /**
     * Constructor de clase
     *
     *
     * @param String $usuautomationId usuautomationId
     * @param String $usuarioId usuarioId
     *
     * @return no
     * @throws Exception si UsuarioAutomation no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($usuautomationId = "", $usuarioId = "")
    {
        if ($usuautomationId != "") {

            $this->usuautomationId = $usuautomationId;

            $UsuarioAutomationMySqlDAO = new UsuarioAutomation2MySqlDAO();

            $UsuarioAutomation = $UsuarioAutomationMySqlDAO->load($usuautomationId);

            if ($UsuarioAutomation != null && $UsuarioAutomation != "") {
                $this->usuautomationId = $UsuarioAutomation->usuautomationId;
                $this->usuarioId = $UsuarioAutomation->usuarioId;
                $this->tipo = $UsuarioAutomation->tipo;
                $this->valor = $UsuarioAutomation->valor;
                $this->fechaCrea = $UsuarioAutomation->fechaCrea;
                $this->usucreaId = $UsuarioAutomation->usucreaId;
                $this->fechaModif = $UsuarioAutomation->fechaModif;
                $this->usumodifId = $UsuarioAutomation->usumodifId;
                $this->estado = $UsuarioAutomation->estado;
                $this->accion = $UsuarioAutomation->accion;
                $this->nombre = $UsuarioAutomation->nombre;
                $this->descripcion = $UsuarioAutomation->descripcion;
                $this->fechaFin = $UsuarioAutomation->fechaFin;
                $this->tipoTiempo = $UsuarioAutomation->tipoTiempo;
                $this->usuarioRepite = $UsuarioAutomation->usuarioRepite;
                $this->automationId = $UsuarioAutomation->automationId;

            } else {
                throw new Exception("No existe " . get_class($this), "95");
            }
        }

    }

    /**
     * Realizar una consulta en la tabla de UsuarioAutomations 'UsuarioAutomations'
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
    public function getUsuarioAutomationsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioAutomationMySqlDAO = new UsuarioAutomation2MySqlDAO();

        $Productos = $UsuarioAutomationMySqlDAO->queryUsuarioAutomationsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "95");
        }

    }

    /**
     * Realizar una consulta en la tabla de UsuarioAutomations 'UsuarioAutomations'
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
    public function getUsuarioAutomations2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$usuario='')
    {

        $UsuarioAutomationMySqlDAO = new UsuarioAutomation2MySqlDAO();

        $Productos = $UsuarioAutomationMySqlDAO->queryUsuarioAutomations2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$usuario);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "95");
        }

    }


    /**
     * Obtener el campo usuautomationId de un objeto
     *
     * @return String usuautomationId usuautomationId
     *
     */
    public function getUsuautomationId()
    {
        return $this->usuautomationId;
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
     * Obtener el campo usuarioRepite de un objeto
     *
     * @return String usuarioRepite
     *
     */
    public function getUsuarioRepite()
    {
        return $this->usuarioRepite;
    }


    /**
     * Modificar el campo 'usuarioRepite' de un objeto
     *
     * @param String $usuarioRepite usuarioRepite
     *
     * @return no
     *
     */
    public function setUsuarioRepite($usuarioRepite)
    {
        $this->usuarioRepite = $usuarioRepite;
    }

    /**
     * @return string
     */
    public function getAutomationId()
    {
        return $this->automationId;
    }

    /**
     * @param string $automationId
     */
    public function setAutomationId($automationId)
    {
        $this->automationId = $automationId;
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
    public function CheckAutomation($objectBase, $tipo, $usuario, $message)
    {

        $response = array(
            "success" => true,
            "needApprobation" => false,
            "isRejected" => false
        );

        $response = json_decode(json_encode($response));

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($tipo != "") {
            $MaxRows = 100;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usuario_automation.tipo", "data" => "$tipo", "op" => "eq"));
            //  array_push($rules, array("field" => "usuario_alerta.usuario_id", "data" => "'$usuario','0'", "op" => "IN"));
            array_push($rules, array("field" => "usuario_automation.estado", "data" => "A", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $this->getUsuarioAutomations2Custom("  usuario_automation.* ", "usuario_automation.usuautomation_id", "asc", $SkeepRows, $MaxRows, $json, true,$usuario);

            $usuarios = json_decode($usuarios);

            $UsuarioMandante = new UsuarioMandante($usuario);

            foreach ($usuarios->data as $value) {
                $array = [];

                $array["Id"] = $value->{"usuario_automation.usuautomation_id"};
                $array["PlayerId"] = $value->{"usuario_automation.usuario_id"};
                $array["Type"] = $value->{"usuario_automation.tipo"};
                $array["Query"] = json_decode($value->{"usuario_automation.valor"});
                $array["Action"] = json_decode($value->{"usuario_automation.accion"});
                $array["State"] = $value->{"usuario_automation.estado"};
                $array["ColumnsQ"] = array();
                $array["OperationsQ"] = ['>', '<', '<=', '>=', '==', '=', 'is'];
                $array["ColumnsA"] = array();
                $array["OperationsA"] = ['>', '<', '<=', '>=', '==', '=', 'is'];


                $queries = ($array["Query"]);


                $cumple = $this->AnalizarQuery($objectBase,$queries,$tipo,$UsuarioMandante);

                if($cumple){

                    $externoId = 0;
                    $nivel = 0;
                    $valorAuto = 0;
                    $estado = 'I';

                    if ($objectBase->amount != '') {
                        $valorAuto = $objectBase->amount;
                    }

                    if ($objectBase->externalId != '') {
                        $externoId = $objectBase->externalId;
                    }

                    $UsuarioAutomationDetalleMySqlDAO = new UsuarioAutomationMySqlDAO();

                    $Transaction = $UsuarioAutomationDetalleMySqlDAO->getTransaction();


                    $queries = ($array["Action"]);

                    $emblue_event ="";
                    $token_emblue = "";
                    foreach ($queries->rules as $query) {

                        switch ($query->field) {
                            case "message_slack":
                                $message = "*Automation:* " . $value->{"usuario_automation.usuautomation_id"} . " - " . $message;

                                if ($tipo != '') {
                                    $message = $message . " *Tipo:* " . $tipo;
                                }
                                if ($valorAuto != '') {
                                    $message = $message . " *Valor:* " . $valorAuto;
                                }
                                if ($externoId != '') {
                                    $message = $message . " *ID Externo:* " . $externoId;
                                }

                                if($ConfigurationEnvironment->isDevelopment()){
                                    exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                }else{
                                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                }


                                break;
                            case "event_emblue":
                                $emblue_event = $query->value;
                                break;
                            case "token_emblue":
                                $token_emblue = $query->value;
                                break;
                            case "priority":
                                $nivel = $query->value;

                                break;
                            case "state_action":
                                $estado = $query->value;

                                break;
                            case "state_action_user":

                                if ($query->value == "I") {
                                    $Mandante = new Mandante($UsuarioMandante->getMandante());

                                    if ($Mandante->propio == "S") {
                                        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                                        $Usuario->estado = 'I';

                                        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                        $UsuarioMySqlDAO->update($Usuario);
                                    }
                                }

                                break;
                            case "message_email":

                                //Arma el mensaje para el usuario que se registra
                                $mensaje_txt = "Mensaje Automation";


                                $email = 'danielftg@hotmail.com';
                                //Destinatarios
                                $destinatarios = $query->value;

                                //Envia el mensaje de correo
                                $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Recuperacion de clave de afiliados', 'mail_registro.php', 'Recuperaci&#243;n de clave de afiliados', $mensaje_txt, $dominio, $compania, $color_email);


                                break;
                            case "43":


                                if($ConfigurationEnvironment->isDevelopment()){
                                    exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                }else{
                                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                }

                                break;
                        }

                    }

                    if($emblue_event != "" && $token_emblue != ""){
                        $email="tecnologiatemp3@gmail.com";
                        $nombre="Dorado";
                        $apellido="test";

                        $data = array(
                            "email"=>$email,
                            "eventName"=>$emblue_event,
                            "attributes"=>array(
                                "nombre"=>$nombre,
                                "apellido"=>$apellido
                            )
                        );

                        $response = $this->emblueRequest("/event",$data,array("Authorization: Basic " . $token_emblue, "Content-Type: application/json"));

                    }

                    if ($estado == "A") {
                        $response->needApprobation = true;
                    }

                    if ($estado == "R") {
                        $response->isRejected = true;
                    }


                    $UsuarioAutomationDetalle = new UsuarioAutomation();

                    $UsuarioAutomationDetalle->setUsuarioId($usuario);
                    $UsuarioAutomationDetalle->setTipo($tipo);
                    $UsuarioAutomationDetalle->setValor($valorAuto);
                    $UsuarioAutomationDetalle->setUsucreaId(0);
                    $UsuarioAutomationDetalle->setUsumodifId(0);
                    $UsuarioAutomationDetalle->setEstado($estado);
                    $UsuarioAutomationDetalle->setUsuautomationId($value->{"usuario_automation.usuautomation_id"});
                    $UsuarioAutomationDetalle->setNivel($nivel);
                    $UsuarioAutomationDetalle->setObservacion('');
                    $UsuarioAutomationDetalle->setUsuaccionId(0);
                    $UsuarioAutomationDetalle->setFechaAccion('');
                    $UsuarioAutomationDetalle->setExternoId($externoId);

                    $UsuarioAutomationDetalleMySqlDAO->insert($UsuarioAutomationDetalle);
                    $Transaction->commit();
                }
            }

        }

        return $response;

    }

    public function AnalizarQuery($objectBase,$queries,$tipo,$UsuarioMandante){



        $cumple = true;

        $condition = $queries->condition;


        if($condition == "or"){
            $cumple = false;
        }

        $cant_deposit = array();

        $sum_deposit = array();

        $prom_deposit = array();

        $cant_bet_sportbook = array();

        $sum_bet_sportbook = array();

        $prom_bet_sportbook = array();

        $cant_win_sportbook = array();

        $sum_win_sportbook = array();

        $prom_win_sportbook = array();

        $cant_bet_casino = array();

        $sum_bet_casino = array();

        $prom_bet_casino = array();

        $cant_win_casino = array();


        $sum_win_casino = array();


        $prom_win_casino = array();


        $ggr_global = array();


        $ggr_sport = array();


        $ggr_casino = array();


        $cant_withdraw = array();


        $sum_withdraw = array();

        $prom_withdraw = array();


        $user_date_created = array();


        $user_birthday = array();


        $deposit_date_created = array();


        $withdraw_date_created = array();


        $withdraw_date_paid = array();


        $bet_sportbook_date_created = array();


        $win_sportbook_date_created = array();


        $bet_casino_date_created = array();


        $win_casino_date_created = array();


        $ggr_global_date_created = array();


        $ggr_sport_date_created = array();


        $ggr_casino_date_created = array();


        $user_country = array();

        $withdraw_state = array();

        $deposit_amount = array();

        $bet_sportbook_amount = array();

        $win_sportbook_amount = array();

        $bet_casino_amount = array();

        $win_casino_amount = array();

        $ggr_global_amount = array();

        $ggr_sport_amount = array();

        $ggr_casino_amount = array();

        $withdraw_amount = array();

        $deposit_amount_sum = array();

        $bet_sportbook_amount_sum = array();

        $win_sportbook_amount_sum = array();

        $bet_casino_amount_sum = array();

        $win_casino_amount_sum = array();

        $ggr_global_amount_sum = array();

        $ggr_sport_amount_sum = array();

        $ggr_casino_amount_sum = array();

        $withdraw_amount_sum = array();


        $dep_sum_deposit_vsamount = array();

        $dep_sum_bet_sportbook_vsamount = array();

        $dep_sum_win_sportbook_vsamount = array();

        $dep_sum_bet_casino_vsamount = array();

        $dep_sum_win_casino_vsamount = array();

        $dep_ggr_global_vsamount = array();

        $dep_ggr_sport_vsamount = array();

        $dep_ggr_casino_vsamount = array();

        $dep_sum_withdraw_vsamount = array();

        $dep_prom_deposit_vsamount = array();

        $dep_prom_bet_sportbook_vsamount = array();

        $dep_prom_win_sportbook_vsamount = array();

        $dep_prom_bet_casino_vsamount = array();

        $dep_prom_win_casino_vsamount = array();

        $dep_prom_withdraw_vsamount = array();


        foreach ($queries->rules as $query) {

            if ($query->field != "") {
                switch ($query->field) {
                    case "cant_deposit":
                        array_push($cant_deposit, $query);
                        break;
                    case "sum_deposit":
                        array_push($sum_deposit, $query);
                        break;
                    case "prom_deposit":
                        array_push($prom_deposit, $query);
                        break;
                    case "cant_bet_sportbook":
                        array_push($cant_bet_sportbook, $query);
                        break;
                    case "sum_bet_sportbook":
                        array_push($sum_bet_sportbook, $query);
                        break;
                    case "prom_bet_sportbook":
                        array_push($prom_bet_sportbook, $query);
                        break;
                    case "cant_win_sportbook":
                        array_push($cant_win_sportbook, $query);
                        break;
                    case "sum_win_sportbook":
                        array_push($sum_win_sportbook, $query);
                        break;
                    case "prom_win_sportbook":
                        array_push($prom_win_sportbook, $query);
                        break;
                    case "cant_bet_casino":
                        array_push($cant_bet_casino, $query);
                        break;

                    case "sum_bet_casino":
                        array_push($sum_bet_casino, $query);
                        break;

                    case "prom_bet_casino":
                        array_push($prom_bet_casino, $query);
                        break;
                    case "cant_win_casino":
                        array_push($cant_win_casino, $query);
                        break;

                    case "sum_win_casino":
                        array_push($sum_win_casino, $query);
                        break;

                    case "prom_win_casino":
                        array_push($prom_win_casino, $query);
                        break;


                    case "ggr_global":
                        array_push($ggr_global, $query);
                        break;

                    case "ggr_sport":
                        array_push($ggr_sport, $query);
                        break;

                    case "ggr_casino":
                        array_push($ggr_casino, $query);
                        break;

                    case "cant_withdraw":
                        array_push($cant_withdraw, $query);
                        break;

                    case "sum_withdraw":
                        array_push($sum_withdraw, $query);
                        break;

                    case "prom_withdraw":
                        array_push($sum_withdraw, $query);
                        break;

                    case "user_date_created":
                        array_push($user_date_created, $query);
                        break;

                    case "user_birthday":
                        array_push($user_birthday, $query);
                        break;

                    case "deposit_date_created":
                        array_push($deposit_date_created, $query);
                        break;

                    case "withdraw_date_created":
                        array_push($withdraw_date_created, $query);
                        break;

                    case "withdraw_date_paid":
                        array_push($withdraw_date_paid, $query);
                        break;

                    case "bet_sportbook_date_created":
                        array_push($bet_sportbook_date_created, $query);
                        break;

                    case "win_sportbook_date_created":
                        array_push($win_sportbook_date_created, $query);
                        break;

                    case "bet_casino_date_created":
                        array_push($bet_casino_date_created, $query);
                        break;

                    case "win_casino_date_created":
                        array_push($win_casino_date_created, $query);
                        break;

                    case "ggr_global_date_created":
                        array_push($ggr_global_date_created, $query);
                        break;

                    case "ggr_sport_date_created":
                        array_push($ggr_sport_date_created, $query);
                        break;


                    case "ggr_casino_date_created":
                        array_push($ggr_casino_date_created, $query);
                        break;


                    case "user_country":
                        array_push($user_country, $query);
                        break;


                    case "withdraw_state":
                        array_push($withdraw_state, $query);
                        break;


                    case "deposit_amount":
                        array_push($deposit_amount, $query);
                        break;


                    case "bet_sportbook_amount":
                        array_push($bet_sportbook_amount, $query);
                        break;


                    case "win_sportbook_amount":
                        array_push($win_sportbook_amount, $query);
                        break;


                    case "bet_casino_amount":
                        array_push($bet_casino_amount, $query);
                        break;


                    case "win_casino_amount":
                        array_push($win_casino_amount, $query);
                        break;


                    case "ggr_global_amount":
                        array_push($ggr_global_amount, $query);
                        break;


                    case "ggr_sport_amount":
                        array_push($ggr_sport_amount, $query);
                        break;


                    case "ggr_casino_amount":
                        array_push($ggr_casino_amount, $query);
                        break;


                    case "withdraw_amount":
                        array_push($withdraw_amount, $query);
                        break;


                    case "deposit_amount_sum":
                        array_push($deposit_amount_sum, $query);
                        break;


                    case "bet_sportbook_amount_sum":
                        array_push($bet_sportbook_amount_sum, $query);
                        break;


                    case "win_sportbook_amount_sum":
                        array_push($win_sportbook_amount_sum, $query);
                        break;


                    case "bet_casino_amount_sum":
                        array_push($bet_casino_amount_sum, $query);
                        break;


                    case "win_casino_amount_sum":
                        array_push($win_casino_amount_sum, $query);
                        break;


                    case "ggr_global_amount_sum":
                        array_push($ggr_global_amount_sum, $query);
                        break;


                    case "ggr_sport_amount_sum":
                        array_push($ggr_sport_amount_sum, $query);
                        break;


                    case "ggr_casino_amount_sum":
                        array_push($ggr_casino_amount_sum, $query);
                        break;


                    case "withdraw_amount_sum":
                        array_push($withdraw_amount_sum, $query);
                        break;


                    case "dep_sum_deposit_vsamount":
                        array_push($dep_sum_deposit_vsamount, $query);
                        break;


                    case "dep_sum_bet_sportbook_vsamount":
                        array_push($dep_sum_bet_sportbook_vsamount, $query);
                        break;


                    case "dep_sum_win_sportbook_vsamount":
                        array_push($dep_sum_win_sportbook_vsamount, $query);
                        break;


                    case "dep_sum_bet_casino_vsamount":
                        array_push($dep_sum_bet_casino_vsamount, $query);
                        break;


                    case "dep_sum_win_casino_vsamount":
                        array_push($dep_sum_win_casino_vsamount, $query);
                        break;


                    case "dep_ggr_global_vsamount":
                        array_push($dep_ggr_global_vsamount, $query);
                        break;


                    case "dep_ggr_sport_vsamount":
                        array_push($dep_ggr_sport_vsamount, $query);
                        break;


                    case "dep_ggr_casino_vsamount":
                        array_push($dep_ggr_casino_vsamount, $query);
                        break;


                    case "dep_sum_withdraw_vsamount":
                        array_push($dep_sum_withdraw_vsamount, $query);
                        break;


                    case "dep_prom_deposit_vsamount":
                        array_push($dep_prom_deposit_vsamount, $query);
                        break;


                    case "dep_prom_bet_sportbook_vsamount":
                        array_push($dep_prom_bet_sportbook_vsamount, $query);
                        break;


                    case "dep_prom_win_sportbook_vsamount":
                        array_push($dep_prom_win_sportbook_vsamount, $query);
                        break;


                    case "dep_prom_bet_casino_vsamount":
                        array_push($dep_prom_bet_casino_vsamount, $query);
                        break;


                    case "dep_prom_win_casino_vsamount":
                        array_push($dep_prom_win_casino_vsamount, $query);
                        break;


                    case "dep_prom_withdraw_vsamount":
                        array_push($dep_prom_withdraw_vsamount, $query);
                        break;


                    case "48":

                        if (!$this->compareValues($query->operator, $query->value, $objectBase->productoId)) {
                            $cumple = false;
                        }
                        break;
                }
            }

            if ($query->rules != ""){
                $cumple= $this->AnalizarQuery($objectBase,$query,$tipo,$UsuarioMandante);

            }
        }

        foreach ($user_country as $item) {

            if ($item->value != $UsuarioMandante->getPaisId() && $condition == "and") {
                $cumple = false;
            }elseif($condition == "or"){
                $cumple = true;
            }
        }

        if($cumple) {


            if (oldCount($cant_deposit) > 0 || oldCount($sum_deposit) > 0 || oldCount($prom_deposit) > 0 || oldCount($dep_sum_deposit_vsamount) > 0 || oldCount($dep_prom_deposit_vsamount) > 0) {

                $rules = [];

                if (oldCount($deposit_date_created) > 0) {
                    foreach ($deposit_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($deposit_amount) > 0) {
                    foreach ($deposit_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "usuario_recarga.valor", "data" => $item->value, "op" => $oper));
                    }
                }
                if (oldCount($deposit_amount_sum) > 0) {
                    foreach ($deposit_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "SUM(usuario_recarga.valor)", "data" => $item->value, "op" => $oper));
                    }
                }


                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioRecarga = new UsuarioRecarga();

                $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) sum, COUNT(usuario_recarga.recarga_id) cont ", "usuario_recarga.recarga_id", "asc", 0, 100, $json, true);


                foreach ($data as $datum) {
                    foreach ($cant_deposit as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($sum_deposit as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($prom_deposit as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    $amount = $objectBase->amount;
                    foreach ($dep_sum_deposit_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_deposit_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                }

            } else {
                if ($tipo == "deposit_created") {
                    if ($objectBase->amount != '') {
                        if (oldCount($deposit_amount) > 0) {
                            foreach ($deposit_amount as $amount) {
                                if (!$this->compareValues($amount->operator, $amount->value, $objectBase->amount) && $condition == "and") {
                                    $cumple = false;
                                }elseif($condition == "or"){
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($cant_bet_sportbook) > 0 || oldCount($sum_bet_sportbook) > 0 || oldCount($prom_bet_sportbook) > 0 || oldCount($dep_sum_bet_sportbook_vsamount) > 0 || oldCount($dep_prom_bet_sportbook_vsamount) > 0) {

                $rules = [];

                if (oldCount($bet_sportbook_date_created) > 0) {
                    foreach ($bet_sportbook_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($bet_sportbook_amount) > 0) {
                    foreach ($bet_sportbook_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "it_ticket_enc.valor_apuesta", "data" => $item->value, "op" => $oper));
                    }
                }


                if (oldCount($bet_sportbook_amount_sum) > 0) {
                    foreach ($bet_sportbook_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "SUM(it_ticket_enc.vlr_apuesta)", "data" => $item->value, "op" => $oper));
                    }
                }

                array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "S", "op" => "ne"));
                array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $ItTicketEnc = new ItTicketEnc();

                $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_apuesta) sum, COUNT(it_ticket_enc.it_ticket_id) cont ", "it_ticket_enc.usuario_id", "asc", 0, 100, $json, true);


                foreach ($data as $datum) {
                    foreach ($cant_bet_sportbook as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($sum_bet_sportbook as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($prom_bet_sportbook as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    $amount = $objectBase->amount;
                    foreach ($dep_sum_bet_sportbook_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_bet_sportbook_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                }

            } else {
                if ($tipo == "bet_sportbook_placed") {
                    if ($objectBase->amount != '') {
                        if (oldCount($bet_sportbook_amount) > 0) {
                            foreach ($bet_sportbook_amount as $amount) {
                                if (!$this->compareValues($amount->operator, $amount->value, $objectBase->amount) && $condition == "and") {
                                    $cumple = false;
                                }elseif($condition == "or"){
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($cant_win_sportbook) > 0 || oldCount($sum_win_sportbook) > 0 || oldCount($prom_win_sportbook) > 0 || oldCount($dep_prom_win_sportbook_vsamount) > 0 || oldCount($dep_sum_win_sportbook_vsamount) > 0) {

                $rules = [];

                if (oldCount($win_sportbook_date_created) > 0) {
                    foreach ($win_sportbook_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "it_ticket_enc.fecha_cierre", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($win_sportbook_amount) > 0) {
                    foreach ($win_sportbook_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "it_ticket_enc.vlr_premio", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($win_sportbook_amount_sum) > 0) {
                    foreach ($win_sportbook_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => " SUM(it_ticket_enc.vlr_premio)", "data" => $item->value, "op" => $oper));
                    }
                }

                array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "S", "op" => "ne"));
                array_push($rules, array("field" => "it_ticket_enc.premiado", "data" => "S", "op" => "eq"));
                array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $ItTicketEnc = new ItTicketEnc();

                $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_premio) sum, COUNT(it_ticket_enc.it_ticket_id) cont ", "it_ticket_enc.usuario_id", "asc", 0, 100, $json, true);


                foreach ($data as $datum) {
                    foreach ($cant_win_sportbook as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($sum_win_sportbook as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($prom_win_sportbook as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    $amount = $objectBase->amount;
                    foreach ($dep_sum_win_sportbook_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_win_sportbook_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }


                }

            } else {
                if ($tipo == "bet_sportbook_win") {
                    if ($objectBase->amount != '') {
                        if (oldCount($win_sportbook_amount) > 0) {
                            foreach ($win_sportbook_amount as $amount) {
                                if (!$this->compareValues($amount->operator, $amount->value, $objectBase->amount) && $condition == "and") {
                                    $cumple = false;
                                }elseif($condition == "or"){
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($cant_bet_casino) > 0 || oldCount($sum_bet_casino) > 0 || oldCount($prom_bet_casino) > 0 || oldCount($dep_sum_bet_casino_vsamount) > 0 || oldCount($dep_prom_bet_casino_vsamount) > 0) {

                $rules = [];

                if (oldCount($bet_casino_date_created) > 0) {
                    foreach ($bet_casino_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($bet_casino_amount) > 0) {
                    foreach ($bet_casino_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "transaccion_juego.valor_ticket", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($bet_casino_amount_sum) > 0) {
                    foreach ($bet_casino_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "SUM(transaccion_juego.valor_ticket)", "data" => $item->value, "op" => $oper));
                    }
                }
                array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $TransaccionJuego = new TransaccionJuego();

                $data = $TransaccionJuego->getTransaccionesCustom("  SUM(transaccion_juego.valor_ticket) sum, COUNT(transaccion_juego.transjuego_id) cont ", "transaccion_juego.usuario_id", "asc", 0, 100, $json, true);


                foreach ($data as $datum) {
                    foreach ($cant_bet_casino as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($sum_bet_casino as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($prom_bet_casino as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }


                    $amount = $objectBase->amount;
                    foreach ($dep_sum_bet_casino_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_bet_casino_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }
                }

            } else {
                if ($tipo == "bet_casino_created") {
                    if ($objectBase->amount != '') {
                        if (oldCount($bet_casino_amount) > 0) {
                            foreach ($bet_casino_amount as $amount) {
                                if (!$this->compareValues($amount->operator, $amount->value, $objectBase->amount) && $condition == "and") {
                                    $cumple = false;
                                }elseif($condition == "or"){
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($cant_win_casino) > 0 || oldCount($sum_win_casino) > 0 || oldCount($prom_win_casino) > 0 || oldCount($dep_sum_win_casino_vsamount) > 0 || oldCount($dep_prom_win_casino_vsamount) > 0) {

                $rules = [];

                if (oldCount($win_casino_date_created) > 0) {
                    foreach ($win_casino_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "transaccion_juego.fecha_modif", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($win_casino_amount) > 0) {
                    foreach ($win_casino_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "transaccion_juego.valor_premio", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($win_casino_amount_sum) > 0) {
                    foreach ($win_casino_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "SUM(transaccion_juego.valor_premio)", "data" => $item->value, "op" => $oper));
                    }
                }
                array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
                array_push($rules, array("field" => "transaccion_juego.premiado", "data" => "S", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $TransaccionJuego = new TransaccionJuego();

                $data = $TransaccionJuego->getTransaccionesCustom("  SUM(transaccion_juego.valor_premio) sum, COUNT(transaccion_juego.transjuego_id) cont ", "transaccion_juego.usuario_id", "asc", 0, 100, $json, true);


                foreach ($data as $datum) {
                    foreach ($cant_win_casino as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($sum_win_casino as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($prom_win_casino as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }


                    $amount = $objectBase->amount;
                    foreach ($dep_sum_win_casino_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_win_casino_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                }

            } else {
                if ($tipo == "bet_casino_win") {
                    if ($objectBase->amount != '') {
                        if (oldCount($win_casino_amount) > 0) {
                            foreach ($win_casino_amount as $amount) {
                                if (!$this->compareValues($amount->operator, $amount->value, $objectBase->amount) && $condition == "and") {
                                    $cumple = false;
                                }elseif($condition == "or"){
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($ggr_sport) > 0) {

                $rules = [];

                if (oldCount($ggr_sport_date_created) > 0) {
                    foreach ($ggr_sport_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($ggr_sport_amount) > 0) {
                    foreach ($ggr_sport_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => " (SUM(it_ticket_enc.vlr_apuesta) - SUM(it_ticket_enc.vlr_premio))", "data" => $item->value, "op" => $oper));
                    }
                }
                array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "S", "op" => "ne"));
                array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $ItTicketEnc = new ItTicketEnc();

                $data = $ItTicketEnc->getTicketsCustom("  (SUM(it_ticket_enc.vlr_apuesta) - SUM(it_ticket_enc.vlr_premio)) sum, COUNT(it_ticket_enc.it_ticket_id) cont ", "it_ticket_enc.usuario_id", "asc", 0, 100, $json, true);


                foreach ($data as $datum) {


                    foreach ($ggr_casino as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                }

            }


            if (oldCount($ggr_casino) > 0) {

                $rules = [];

                if (oldCount($ggr_casino_date_created) > 0) {
                    foreach ($ggr_casino_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($ggr_casino_amount) > 0) {
                    foreach ($ggr_casino_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => " (SUM(transaccion_juego.valor_ticket)-SUM(transaccion_juego.valor_premio))", "data" => $item->value, "op" => $oper));
                    }
                }
                array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $TransaccionJuego = new TransaccionJuego();

                $data = $TransaccionJuego->getTransaccionesCustom("  (SUM(transaccion_juego.valor_ticket)-SUM(transaccion_juego.valor_premio)) sum, COUNT(transaccion_juego.transjuego_id) cont ", "transaccion_juego.usuario_id", "asc", 0, 100, $json, true);


                foreach ($data as $datum) {


                    foreach ($ggr_casino as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                }

            }


            if (oldCount($cant_withdraw) > 0 || oldCount($sum_withdraw) > 0 || oldCount($prom_withdraw) > 0 || oldCount($dep_sum_withdraw_vsamount) > 0 || oldCount($dep_prom_withdraw_vsamount) > 0) {

                $rules = [];

                if (oldCount($withdraw_date_created) > 0) {
                    foreach ($withdraw_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($withdraw_date_paid) > 0) {
                    foreach ($withdraw_date_paid as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($withdraw_state) > 0) {
                    foreach ($withdraw_state as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($withdraw_amount) > 0) {
                    foreach ($withdraw_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "cuenta_cobro.valor", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($withdraw_amount_sum) > 0) {
                    foreach ($withdraw_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "SUM(cuenta_cobro.valor)", "data" => $item->value, "op" => $oper));
                    }
                }


                array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $CuentaCobro = new CuentaCobro();

                $data = $CuentaCobro->getCuentasCobroCustom("  SUM(cuenta_cobro.valor) sum, COUNT(cuenta_cobro.cuenta_id) cont ", "cuenta_cobro.cuenta_id", "asc", 0, 100, $json, true);


                foreach ($data as $datum) {
                    foreach ($cant_withdraw as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($sum_withdraw as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($prom_withdraw as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }


                    $amount = $objectBase->amount;
                    foreach ($dep_sum_withdraw_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_withdraw_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        }elseif($condition == "or"){
                            $cumple = true;
                        }
                    }


                }

            } else {
                if ($tipo == "withdraw_created") {
                    if ($objectBase->amount != '') {
                        if (oldCount($withdraw_amount) > 0) {
                            foreach ($withdraw_amount as $amount) {
                                if (!$this->compareValues($amount->operator, $amount->value, $objectBase->amount) && $condition == "and") {
                                    $cumple = false;
                                }elseif($condition == "or"){
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }
        }


        return $cumple;

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
    public function CheckAlert($objectBase, $tipo, $usuario, $message)
    {

        if ($tipo != "") {
            $MaxRows = 100;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usuario_alerta.tipo", "data" => "$tipo", "op" => "eq"));
            //  array_push($rules, array("field" => "usuario_alerta.usuario_id", "data" => "'$usuario','0'", "op" => "IN"));
            array_push($rules, array("field" => "usuario_alerta.estado", "data" => "A", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $this->getUsuarioAutomationsCustom("  usuario_alerta.* ", "usuario_alerta.usualerta_id", "asc", $SkeepRows, $MaxRows, $json, true);

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


                $queries = ($array["Query"]);
                $cumple = true;

                foreach ($queries->operands as $query) {

                    if ($query->colName != "") {
                        switch ($query->colName->Id) {
                            case "48":

                                if (!$this->compareValues($query->operation, $query->value, $objectBase->productoId)) {
                                    $cumple = false;
                                }
                                break;
                        }
                    }


                }

                if ($cumple) {

                    $queries = ($array["Action"]);

                    foreach ($queries->operands as $query) {

                        switch ($query->colName->Id) {
                            case "43":

                                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#virtualsoft-soporte' > /dev/null & ");

                                break;
                        }

                    }

                }


            }

        }

    }

    /**
     * Comparar dos valores y devolver el resultado
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
    public function compareValues($oper, $value2, $value1)
    {

        switch ($oper) {

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


    /**
     * Hacer una petición emblue mediante CURL
     *
     * @param String $text text
     * @return array $result result
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function emblueRequest($string, $array_tmp, $header = array())
    {
        $data = array(
        );

        $data = array_merge($data, $array_tmp);
        $data=json_encode($data);

        $ch = curl_init("https://track.embluemail.com/contacts" . $string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        //$rs = curl_exec($ch);
        $result = (curl_exec($ch));

        return ($result);

    }

    /**
     * Convertir operador de sql to funcion
     *
     *
     * @param String $value1 value1
     * @param String $value2 value2
     *
     * @return string
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function convertOperator($value1, $value2 = "")
    {
        switch ($value2) {
            case "lastmonth":
                return ">=";
                break;
            case "lastmonth_hours":
                return ">=";
                break;
            case "lastday":
                return ">=";
                break;
            case "lastday_hours":
                return ">=";
                break;
            case "last2month":
                return ">=";
                break;
            case "last2month_hours":
                return ">=";
                break;
            case "last3month":
                return ">=";
                break;
            case "last3month_hours":
                return ">=";
                break;
            case "last4month":
                return ">=";
                break;
            case "last4month_hours":
                return ">=";
                break;
            case "last5month":
                return ">=";
                break;
            case "last5month_hours":
                return ">=";
                break;
            case "last6month":
                return ">=";
                break;
            case "last6month_hours":
                return ">=";
                break;
            case "today":
                return ">=";
                break;
            case "today_hours":
                return ">=";
                break;
            case "now":
                return ">=";
                break;
            case "now_hours":
                return ">=";
                break;
            default:

                break;
        }

        switch ($value1) {
            case "=":
                return "eq";
                break;
            case "!=":
                return "ne";
                break;
            case "<":
                return "lt";
                $fieldOperation = " < '" . $fieldData . "'";
                break;
            case ">":
                return "gt";
                $fieldOperation = " > '" . $fieldData . "'";
                break;
            case "<=":
                return "le";
                $fieldOperation = " <= '" . $fieldData . "'";
                break;
            case ">=":
                return "ge";
                $fieldOperation = " >= '" . $fieldData . "'";
                break;
            case "=":
                return "nu";
                $fieldOperation = " = ''";
                break;
            case "!=":
                return "nn";
                $fieldOperation = " != ''";
                break;
            case "in":
                return "in";
                $fieldOperation = " IN (" . $fieldData . ")";
                break;
            case "ni":
                return "ni";
                break;
            case "bw":
                return "bw";
                break;
            case "bn":
                return "bn";
                break;
            case "ew":
                return "ew";
                break;
            case "en":
                return "en";
                break;
            case "cn":
                return "cn";
                break;
            case "nc":
                return "nc";
                break;
            default:
                return "ne";
                break;
        }

    }


    /**
     * Convertir valor de sql to funcion
     *
     *
     * @param String $value1 value1
     * @param String $value2 value2
     *
     * @return string
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function convertValue($value1)
    {
        switch ($value1) {
            case "lastmonth":
                $date = date("Y-m-d", strtotime("-1 month"));
                return $date;
                break;
            case "lastmonth_hours":
                $date = date("Y-m-d H:i:s", strtotime("-1 month"));
                return $date;
                break;
            case "lastday":
                $date = date("Y-m-d", strtotime("-1 day"));
                return $date;
                break;
            case "lastday_hours":
                $date = date("Y-m-d H:i:s", strtotime("-1 day"));
                return $date;
                break;
            case "last2month":
                $date = date("Y-m-d", strtotime("-2 month"));
                return $date;
                break;
            case "last2month_hours":
                $date = date("Y-m-d H:i:s", strtotime("-2 month"));
                return $date;
                break;
            case "last3month":
                $date = date("Y-m-d", strtotime("-3 month"));
                return $date;
                break;
            case "last3month_hours":
                $date = date("Y-m-d H:i:s", strtotime("-3 month"));
                return $date;
                break;
            case "last4month":
                $date = date("Y-m-d", strtotime("-4 month"));
                return $date;
                break;
            case "last4month_hours":
                $date = date("Y-m-d H:i:s", strtotime("-4 month"));
                return $date;
                break;
            case "last5month":
                $date = date("Y-m-d", strtotime("-5 month"));
                return $date;
                break;
            case "last5month_hours":
                $date = date("Y-m-d H:i:s", strtotime("-5 month"));
                return $date;
                break;
            case "last6month":
                $date = date("Y-m-d", strtotime("-6 month"));
                return $date;
                break;
            case "last6month_hours":
                $date = date("Y-m-d H:i:s", strtotime("-6 month"));
                return $date;
                break;
            case "today":
                $date = date("Y-m-d");
                return $date;
                break;
            case "today_hours":
                $date = date("Y-m-d H:i:s");
                return $date;
                break;
            case "now":
                $date = date("Y-m-d");
                return $date;
                break;
            case "now_hours":
                $date = date("Y-m-d H:i:s");
                return $date;
                break;
            default:
                return $value1;
                break;
        }

    }


}

?>
