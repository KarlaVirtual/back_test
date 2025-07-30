<?php namespace Backend\dto;

use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\AutomationMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\sql\Transaction;
use Exception;


/**
 * Clase 'Automation'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'Automation'
 *
 * Ejemplo de uso:
 * $Automation = new Automation();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class Automation
{

    /**
     * Representación de la columna 'automationId' de la tabla 'Automation'
     *
     * @var string
     */
    var $automationId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'Automation'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'tipo' de la tabla 'Automation'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'valor' de la tabla 'Automation'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'Automation'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'Automation'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'Automation'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'Automation'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'estado' de la tabla 'Automation'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'accion' de la tabla 'Automation'
     *
     * @var string
     */
    var $accion;

    /**
     * Representación de la columna 'nombre' de la tabla 'Automation'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'descripcion' de la tabla 'Automation'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'fecha_expiracion' de la tabla 'Automation'
     *
     * @var string
     */
    var $fechaFin;

    /**
     * Representación de la columna 'fecha_inicio' de la tabla 'Automation'
     *
     * @var string
     */
    var $fechaInicio;

    /**
     * Representación de la columna 'tipo_tiempo' de la tabla 'Automation'
     *
     * @var string
     */
    var $tipoTiempo;

    /**
     * Representación de la columna 'usuario_repite' de la tabla 'Automation'
     *
     * @var string
     */
    var $usuarioRepite;

    /**
     * Representación de la columna 'valor_tipo' de la tabla 'Automation'
     *
     * @var string
     */
    var $valorTipo;

    private $transaction;
    /**
     * Constructor de clase
     *
     *
     * @param String $automationId automationId
     * @param String $usuarioId usuarioId
     *
     * @return no
     * @throws Exception si Automation no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($automationId = "", $usuarioId = "")
    {
        if ($automationId != "") {

            $this->automationId = $automationId;

            $AutomationMySqlDAO = new AutomationMySqlDAO();

            $Automation = $AutomationMySqlDAO->load($automationId);

            if ($Automation != null && $Automation != "") {
                $this->automationId = $Automation->automationId;
                $this->usuarioId = $Automation->usuarioId;
                $this->tipo = $Automation->tipo;
                $this->valor = $Automation->valor;
                $this->fechaCrea = $Automation->fechaCrea;
                $this->usucreaId = $Automation->usucreaId;
                $this->fechaModif = $Automation->fechaModif;
                $this->usumodifId = $Automation->usumodifId;
                $this->estado = $Automation->estado;
                $this->accion = $Automation->accion;
                $this->nombre = $Automation->nombre;
                $this->descripcion = $Automation->descripcion;
                $this->fechaFin = $Automation->fechaFin;
                $this->tipoTiempo = $Automation->tipoTiempo;
                $this->usuarioRepite = $Automation->usuarioRepite;
                $this->valorTipo = $Automation->valorTipo;

            } else {
                throw new Exception("No existe " . get_class($this), "95");
            }
        }

        $this->transaction = new Transaction();
    }

    /**
     * Realizar una consulta en la tabla de Automations 'Automations'
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
    public function getAutomationsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $AutomationMySqlDAO = new AutomationMySqlDAO();

        $Productos = $AutomationMySqlDAO->queryAutomationsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "95");
        }

    }

    /**
     * Realizar una consulta en la tabla de Automations 'Automations'
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
    public function getAutomations2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $usuario = '')
    {

        $AutomationMySqlDAO = new AutomationMySqlDAO($this->transaction);
        $Productos = $AutomationMySqlDAO->queryAutomations2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $usuario);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "95");
        }

    }


    /**
     * Obtener el campo automationId de un objeto
     *
     * @return String automationId automationId
     *
     */
    public function getUsuautomationId()
    {
        return $this->automationId;
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
    public function getValorTipo()
    {
        return $this->valorTipo;
    }

    /**
     * @param string $valorTipo
     */
    public function setValorTipo($valorTipo)
    {
        $this->valorTipo = $valorTipo;
    }

    /**
     * @return string
     */
    public function getAutomationId()
    {
        return $this->automationId;
    }

    private $usuariosProcesados = array();
    private $usuariosPuedenRepetir = 0;

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

        $confUsuarioMandante = true;

        $response = array(
            "success" => true,
            "needApprobation" => false,
            "isRejected" => false,
            "riskId" => 0
        );

        $cont =0;
        $response = json_decode(json_encode($response));
        $response2 = json_decode(json_encode($response));

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($tipo != "") {
            $MaxRows = 100;
            // $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "automation.tipo", "data" => "$tipo", "op" => "eq"));
            //  array_push($rules, array("field" => "usuario_alerta.usuario_id", "data" => "'$usuario','0'", "op" => "IN"));
            array_push($rules, array("field" => "automation.estado", "data" => "A", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            // $msc = microtime(true);
            $usuarios = $this->getAutomations2Custom("  automation.* ", "automation.automation_id", "asc", $SkeepRows, $MaxRows, $json, true, $usuario);
            // $msc = microtime(true)-$msc;
            // echo("automations: ". $msc).PHP_EOL;

            $usuarios = json_decode($usuarios);

            // $msc = microtime(true);
            $UsuarioMandante = new UsuarioMandante($usuario, "","", $this->transaction);
            // $msc = microtime(true)-$msc;
            // echo("usuario mandante: ". $msc).PHP_EOL;

            foreach ($usuarios->data as $value) {
                $array = [];

                $array["Id"] = $value->{"automation.automation_id"};
                // $array["Id"] = $value->{"automation.usuautomation_id"};
                $array["AutomationId"] = $value->{"automation.automation_id"};
                $array["PlayerId"] = $value->{"automation.usuario_id"};
                $array["Type"] = $value->{"automation.tipo"};
                $array["Query"] = json_decode($value->{"automation.valor"});
                $array["Action"] = json_decode($value->{"automation.accion"});
                $array["State"] = $value->{"automation.estado"};
                $array["ColumnsQ"] = array();
                $array["OperationsQ"] = ['>', '<', '<=', '>=', '==', '=', 'is'];
                $array["ColumnsA"] = array();
                $array["OperationsA"] = ['>', '<', '<=', '>=', '==', '=', 'is'];

                $usuarioRepite = $value->{"automation.usuario_repite"};


                $this->usuariosPuedenRepetir = $usuarioRepite;

                if ($usuarioRepite == 0) {

                    $UsuarioAutomation = new UsuarioAutomation();

                    $rules = array();
                    array_push($rules, array("field" => "usuario_automation.automation_id", "data" => $array["AutomationId"], "op" => 'eq'));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    // $msc = microtime(true);
                    $usuariosData = $UsuarioAutomation->getUsuarioAutomationsCustom(" usuario_mandante.usuario_mandante,usuario_mandante.usumandante_id ", "usuario_automation.usuario_id", "asc", 0, 100000, $json, true);
                    // $msc = microtime(true)-$msc;
                    // echo("getUsuarioAutomationsCustom: ". $msc).PHP_EOL;

                    $usuariosData = json_decode($usuariosData);


                    foreach ($usuariosData->data as $usuario) {

                        if ($confUsuarioMandante) {
                            array_push($this->usuariosProcesados, $usuario->{'usuario_mandante.usumandante_id'});

                        } else {
                            array_push($this->usuariosProcesados, $usuario->{'usuario_mandante.usuario_mandante'});

                        }


                    }


                }

                $queries = ($array["Query"]);

                // $msc = microtime(true);
                $usuarios = $this->AnalizarQuery($objectBase, $queries, $tipo, $UsuarioMandante);
                // $msc = microtime(true)-$msc;
                // echo("AnalizarQuery: ".$msc).PHP_EOL;



                $usuariosSeleccionados = $usuarios->Users;
                $usuariosNoSeleccionados = $usuarios->NoUsers;

                $paisProcesado = $usuarios->paisProcesado;
                $paisCondicional = $usuarios->paisCondicional;

                if (!$paisProcesado) {
                    if ($paisCondicional != '0') {

                        $Usuario = new Usuario();


                        if ($confUsuarioMandante) {
                            $rules = array();
                            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $paisCondicional, "op" => 'in'));
                            array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $usuariosSeleccionados, "op" => 'in'));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);

                            $usuarios = $UsuarioMandante->getUsuariosMandantesCustom(" usuario_mandante.usumandante_id ", "usuario_mandante.usumandante_id", "asc", 0, count(explode(",", $usuariosSeleccionados)), $json, true);
                        } else {
                            $rules = array();
                            array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => $usuariosSeleccionados, "op" => 'in'));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);

                            $usuarios = $Usuario->getUsuariosCustom(" usuario.usuario_id ", "usuario.usuario_id", "asc", 0, count(explode(",", $usuariosSeleccionados)), $json, true);
                        }

                        $usuarios = json_decode($usuarios);

                        $usuariosSeleccionados = "0";

                        foreach ($usuarios->data as $usuario) {
                            if ($confUsuarioMandante) {
                                $usuariosSeleccionados .= ',' . $usuario->{'usuario_mandante.usumandante_id'};

                            } else {
                                $usuariosSeleccionados .= ',' . $usuario->{'usuario.usuario_id'};

                            }

                        }


                    }
                }


                $usuariosFinal = array();
                if ($usuariosSeleccionados != "") {
                    $usuarios1 = explode(",", $usuariosSeleccionados);
                    $usuarios2 = explode(",", $usuariosNoSeleccionados);

                    foreach ($usuarios1 as $item) {

                        if (!in_array($item, $usuarios2)) {
                            array_push($usuariosFinal, $item);
                        }
                    }

                }
                $usuarios = $usuariosFinal;

                //print_r($usuarios);

                /*if ($usuarioRepite == 0) {

                    $UsuarioAutomation = new UsuarioAutomation();

                    $rules = array();
                    array_push($rules, array("field" => "usuario_automation.automation_id", "data" => $array["AutomationId"], "op" => 'eq'));

                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => implode(', ', $usuarios), "op" => 'in'));
                    } else {
                        array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => implode(', ', $usuarios), "op" => 'in'));
                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);
                    print_r($json);

                    $usuariosData = $UsuarioAutomation->getUsuarioAutomationsCustom(" usuario_mandante.usuario_mandante,usuario_mandante.usumandante_id ", "usuario_automation.usuario_id", "asc", 0, oldCount($usuarios), $json, true);

                    $usuariosData = json_decode($usuariosData);


                    print_r($usuariosData);
                    foreach ($usuariosData->data as $usuario) {

                        if ($confUsuarioMandante) {
                            if (($key = array_search($usuario->{'usuario_mandante.usumandante_id'}, $usuarios)) !== false) {
                                unset($usuarios[$key]);
                            }
                        } else {
                            if (($key = array_search($usuario->{'usuario_mandante.usuario_mandante'}, $usuarios)) !== false) {
                                unset($usuarios[$key]);
                            }
                        }


                    }


                }*/


                if (oldCount($usuarios) > 0) {

                    if (!$confUsuarioMandante) {
                        $rules = [];
                        array_push($rules, array("field" => "usuario.usuario_id", "data" => implode(', ', $usuarios), "op" => "in"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $Usuario = new Usuario();
                        $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),pais.prefijo_celular,registro.nombre1,registro.nombre2,usuario_mandante.*,registro.celular", "usuario.fecha_crea", "desc", $SkeepRows, 100000, $json, true);

                        $usuarios = json_decode($usuarios);
                    } else {
                        $rules = [];
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => implode(', ', $usuarios), "op" => "in"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $Usuario = new UsuarioMandante("","","", $this->transaction);


                        $usuarios = $Usuario->getUsuariosMandantesCustom("usuario_mandante.*,pais.prefijo_celular,registro.nombre1,registro.nombre2,registro.celular,mandante.*,usuario.login", "usuario_mandante.usumandante_id", "desc", $SkeepRows, 100000, $json, true, "");

                        $usuarios = json_decode($usuarios);
                    }


                    $seguir = false;
                    foreach ($usuarios->data as $usuario) {

                        /*if (!$seguir && $usuario->{'usuario_mandante.usuario_mandante'} == "886") {
                            $seguir = true;

                        }*/
                        $seguir = true;

                        if ($seguir) {


                            $externoId = 0;
                            $nivel = 0;
                            $valorAuto = 0;
                            $estado = 'I';

                            if ($objectBase->amount != '') {
                                $valorAuto = $objectBase->amount;
                            }

                            if (empty($objectBase->externalId)) {
                                $externoId = $objectBase->externalId;
                            }
                            $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO($this->transaction);

                            $Transaction = $UsuarioAutomationMySqlDAO->getTransaction();


                            $queries = ($array["Action"]);

                            $emblue_event = "";
                            $token_emblue = "";


                            $bonoValor = 0;

                            $USERMANDANTE = 0;
                            $USER = 0;
                            $PaisUSER = 0;
                            $DepartamentoUSER = 0;
                            $CiudadUSER = 0;
                            $MonedaUSER = '';

                            $nombreUSER = '';
                            $emailUSER = '';
                            $celularUSER = '';

                            if ($confUsuarioMandante) {

                                $USER = $usuario->{'usuario_mandante.usuario_mandante'};
                                $USERMANDANTE = $usuario->{'usuario_mandante.usumandante_id'};

                                $nombreUSER = $usuario->{'usuario_mandante.nombres'};
                                $emailUSER = $usuario->{'usuario_mandante.email'};
                                $celularUSER = '';
                                $PaisUSER = $usuario->{'usuario_mandante.pais_id'};
                                $MonedaUSER = $usuario->{'usuario_mandante.moneda'};

                                if ($usuario->{'mandante.propio'} == "S") {
                                    $nombreUSER = $usuario->{'registro.nombre1'} . '' . $usuario->{'registro.nombre2'};
                                    $emailUSER = $usuario->{'usuario.login'};
                                    $celularUSER = $usuario->{'pais.prefijo_celular'} . $usuario->{'registro.celular'};

                                }

                            } else {


                                $USER = $usuario->{'usuario.usuario_id'};
                                $USERMANDANTE = $usuario->{'usuario_mandante.usumandante_id'};

                                $PaisUSER = $usuario->{'usuario.pais_id'};
                                $nombreUSER = $usuario->{'registro.nombre1'} . '' . $usuario->{'registro.nombre2'};
                                $emailUSER = $usuario->{'usuario.login'};
                                $celularUSER = $usuario->{'pais.prefijo_celular'} . $usuario->{'registro.celular'};
                                $MonedaUSER = $usuario->{'usuario_mandante.moneda'};

                            }


                                try {


                                    foreach ($queries->rules as $query) {
                                        switch ($query->field) {
                                            case "message_slack":

                                                $message = "*Automation:* " . $array["AutomationId"] . " - " . $message;
                                                $message = "*Usuario:* " . $USER . " - " . $message;
                                                $message = "*UsuarioCasino:* " . $USERMANDANTE . " - " . $message;

                                                if ($tipo != '') {
                                                    $message = $message . " *Tipo:* " . $tipo;
                                                }
                                                if ($valorAuto != '') {
                                                    $message = $message . " *Valor:* " . $valorAuto;
                                                }
                                                if ($externoId != '') {
                                                    $message = $message . " *ID Externo:* " . $externoId;
                                                }

                                                if ($bonoValor != '') {
                                                    $message = $message . " *Valor Bono:* " . $bonoValor;
                                                }

                                                if ($ConfigurationEnvironment->isDevelopment()) {
                                                    exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                                } else {
                                                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                                }


                                                break;
                                            case "event_emblue":
                                                $emblue_event = $query->value;
                                                break;
                                            case "send_sms":
                                                $Usuario = new Usuario($USER);

                                                if ($Usuario->paisId == "173" && $Usuario->mandante == 0) {
                                                    $Registro = new Registro('', $USER);
                                                    $UsuarioMandante = new UsuarioMandante($USERMANDANTE);
                                                    $mensaje_txt = $query->value;

                                                    //Envia el mensaje de correo
                                                    $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);

                                                }

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
                                                $dominio = '';
                                                $compania = '';
                                                $color_email = '';
                                                //Envia el mensaje de correo
                                                $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Recuperacion de clave de afiliados', 'mail_registro.php', 'Recuperaci&#243;n de clave de afiliados', $mensaje_txt, $dominio, $compania, $color_email);


                                                break;
                                            case "bonus_freebet":


                                                $detalles = array(
                                                    "PaisUSER" => $PaisUSER,
                                                    "DepartamentoUSER" => $DepartamentoUSER,
                                                    "CiudadUSER" => $CiudadUSER,
                                                    "MonedaUSER" => $MonedaUSER

                                                );
                                                $detalles = json_decode(json_encode($detalles));
                                                $ganaBonoId = $query->value;

                                                $BonoInterno = new BonoInterno();
                                                $respuesta2 = $BonoInterno->agregarBonoFree($ganaBonoId, $USER, "0", $detalles, true);

                                                if ($respuesta2 != null) {
                                                    if ($respuesta2->WinBonus) {
                                                        $bonoValor = $respuesta2->Valor;
                                                    }
                                                }

                                                break;
                                            case "43":


                                                if ($ConfigurationEnvironment->isDevelopment()) {
                                                    exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                                } else {
                                                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                                }

                                                break;
                                        }

                                    }

                                    if ($emblue_event != "" && $token_emblue != "") {
                                        $email = "tecnologiatemp3@gmail.com";
                                        $nombre = "Dorado";
                                        $apellido = "test";

                                        $data = array(
                                            "email" => $emailUSER,
                                            "eventName" => $emblue_event,
                                            "attributes" => array(
                                                "name" => $nombreUSER,
                                                "bonus_amount" => $bonoValor,
                                                "phone" => $celularUSER
                                            )
                                        );
                                        $token_emblue = base64_encode($token_emblue);

                                        $response = $this->emblueRequest("/event", $data, array("Authorization: Basic " . $token_emblue, "Content-Type: application/json"));

                                    }
                                }catch (Exception $e){

                                }
                            if ($estado == "P") {
                                $response->needApprobation = true;
                            }

                            if ($estado == "R") {
                                $response->isRejected = true;
                            }

                            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
                            $UsuarioAutomation = new UsuarioAutomation();

                            $UsuarioAutomation->setUsuarioId($USERMANDANTE);
                            $UsuarioAutomation->setTipo($tipo);
                            $UsuarioAutomation->setValor($valorAuto);
                            $UsuarioAutomation->setUsucreaId(0);
                            $UsuarioAutomation->setUsumodifId(0);
                            $UsuarioAutomation->setEstado($estado);
                            $UsuarioAutomation->setAutomationId($array["AutomationId"]);
                            $UsuarioAutomation->setNivel($nivel);
                            $UsuarioAutomation->setObservacion('');
                            $UsuarioAutomation->setUsuaccionId(0);
                            $UsuarioAutomation->setFechaAccion('');
                            $UsuarioAutomation->setExternoId($externoId);

                            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
                            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();
                            $response->riskId = $automationId;

                            $seguir = false;
                        }
                    }

                }

                /*if (oldCount($usuarios) > 1 && false) {


                    $seguir = true;
                    foreach ($usuarios->data as $usuario) {
                        if ($seguir) {


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

                            $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO();

                            $Transaction = $UsuarioAutomationMySqlDAO->getTransaction();


                            $queries = ($array["Action"]);

                            $emblue_event = "";
                            $token_emblue = "";


                            $bonoValor = 0;

                            $USER = 0;
                            $PaisUSER = 0;
                            $DepartamentoUSER = 0;
                            $CiudadUSER = 0;
                            $MonedaUSER = 0;

                            $nombreUSER = '';
                            $emailUSER = '';
                            $celularUSER = '';

                            if ($confUsuarioMandante) {
                                $UsuarioMandante = new UsuarioMandante($usuario);
                                $Mandante = new Mandante($UsuarioMandante->getMandante());

                                $PaisUSER = $UsuarioMandante->getPaisId();
                                $MonedaUSER = $UsuarioMandante->getMoneda();

                                $nombreUSER = $UsuarioMandante->getNombres();
                                $emailUSER = $UsuarioMandante->getEmail();
                                $celularUSER = '';

                                if ($Mandante->propio == "S") {
                                    $Pais = new Pais($PaisUSER);
                                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                                    $Registro = new Registro($UsuarioMandante->getUsuarioMandante());
                                    $nombreUSER = $Registro->getNombre1() . " " . $Registro->getNombre2();
                                    $emailUSER = $Usuario->login;
                                    $celularUSER = $Pais->prefijoCelular . $Registro->celular;

                                }

                                $USER = $UsuarioMandante->getUsuarioMandante();
                            } else {
                                $Pais = new Pais($PaisUSER);
                                $Usuario = new Usuario($usuario);
                                $Registro = new Registro($UsuarioMandante->getUsuarioMandante());
                                $PaisUSER = $Usuario->paisId;
                                $MonedaUSER = $Usuario->moneda;

                                $nombreUSER = $Usuario->nombre();
                                $emailUSER = $Usuario->login();
                                $celularUSER = $Pais->prefijoCelular . $Registro->celular;

                                $USER = $usuario;

                            }

                            foreach ($queries->rules as $query) {

                                switch ($query->field) {
                                    case "message_slack":
                                        $message = "*Automation:* " . $array["AutomationId"] . " - " . $message;

                                        if ($tipo != '') {
                                            $message = $message . " *Tipo:* " . $tipo;
                                        }
                                        if ($valorAuto != '') {
                                            $message = $message . " *Valor:* " . $valorAuto;
                                        }
                                        if ($externoId != '') {
                                            $message = $message . " *ID Externo:* " . $externoId;
                                        }

                                        if ($bonoValor != '') {
                                            $message = $message . " *Valor Bono:* " . $bonoValor;
                                        }

                                        if ($ConfigurationEnvironment->isDevelopment()) {
                                            exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                        } else {
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
                                        $dominio = '';
                                        $compania = '';
                                        $color_email = '';
                                        //Envia el mensaje de correo
                                        $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Recuperacion de clave de afiliados', 'mail_registro.php', 'Recuperaci&#243;n de clave de afiliados', $mensaje_txt, $dominio, $compania, $color_email);


                                        break;
                                    case "bonus_freebet":


                                        $detalles = array(
                                            "PaisUSER" => $PaisUSER,
                                            "DepartamentoUSER" => $DepartamentoUSER,
                                            "CiudadUSER" => $CiudadUSER,
                                            "MonedaUSER" => $MonedaUSER

                                        );
                                        $detalles = json_decode(json_encode($detalles));
                                        $ganaBonoId = $query->value;

                                        $BonoInterno = new BonoInterno();
                                        $respuesta2 = $BonoInterno->agregarBonoFree($ganaBonoId, $USER, "0", $detalles, true);

                                        if ($respuesta2 != null) {
                                            if ($respuesta2->WinBonus) {
                                                $bonoValor = $respuesta2->Valor;
                                            }
                                        }

                                        break;
                                    case "43":


                                        if ($ConfigurationEnvironment->isDevelopment()) {
                                            exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                        } else {
                                            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                        }

                                        break;
                                }

                            }

                            if ($emblue_event != "" && $token_emblue != "") {
                                /*$email="tecnologiatemp3@gmail.com";
                                $nombre="Dorado";
                                $apellido="test";

                                $data = array(
                                    "email"=>$emailUSER,
                                    "eventName"=>$emblue_event,
                                    "attributes"=>array(
                                        "name"=>$nombreUSER,
                                        "bonus_amount"=>$bonoValor,
                                        "phone"=>$phoneUSER
                                    )
                                );

                                $response = $this->emblueRequest("/event",$data,array("Authorization: Basic " . $token_emblue, "Content-Type: application/json"));

                            }


                            $UsuarioAutomation = new UsuarioAutomation();

                            $UsuarioAutomation->setUsuarioId($USERMANDANTE);
                            $UsuarioAutomation->setTipo($tipo);
                            $UsuarioAutomation->setValor($valorAuto);
                            $UsuarioAutomation->setUsucreaId(0);
                            $UsuarioAutomation->setUsumodifId(0);
                            $UsuarioAutomation->setEstado($estado);
                            $UsuarioAutomation->setAutomationId($array["AutomationId"]);
                            $UsuarioAutomation->setNivel($nivel);
                            $UsuarioAutomation->setObservacion('');
                            $UsuarioAutomation->setUsuaccionId(0);
                            $UsuarioAutomation->setFechaAccion('');
                            $UsuarioAutomation->setExternoId($externoId);

                            $automationId = $UsuarioAutomationMySqlDAO->insert($UsuarioAutomation);
                            $Transaction->commit();


                            if ($estado == "P") {
                                $response->needApprobation = true;
                            }

                            if ($estado == "R") {
                                $response->isRejected = true;
                            }
                            $response->riskId = $automationId;

                            $seguir = false;
                        }
                    }

                }*/

                if($cont == 0){

                    $response2 = $response;

                }else{
                    if(!$response2->isRejected ){
                        if($response->isRejected){
                            $response2 = $response;
                        }else{
                            if(!$response2->needApprobation ){
                                if($response->needApprobation){
                                    $response2 = $response;
                                }
                            }
                        }

                    }
                }

                $cont++;
            }

        }

        if($this->transaction->getConnection()->isBeginTransaction==2){
            $this->transaction->commit();
        }

        return $response2;

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
    public function CheckAutomationExterno($objectBase, $tipo, $usuario, $message, $queries, $UsuarioMandante)
    {
        $confUsuarioMandante = true;

        $SkeepRows = 0;
        $response = array(
            "success" => true,
            "needApprobation" => false,
            "isRejected" => false
        );

        $response = json_decode(json_encode($response));

        $ConfigurationEnvironment = new ConfigurationEnvironment();


        $usuarios = $this->AnalizarQuery($objectBase, $queries, $tipo, $UsuarioMandante);

        $usuariosSeleccionados = $usuarios->Users;
        $usuariosNoSeleccionados = $usuarios->NoUsers;

        $paisProcesado = $usuarios->paisProcesado;
        $paisCondicional = $usuarios->paisCondicional;
        $procesos = $usuarios->procesos;


        if (!$paisProcesado) {
            if ($paisCondicional != '0') {

                $Usuario = new Usuario();


                if ($confUsuarioMandante) {
                    $rules = array();
                    array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $paisCondicional, "op" => 'in'));
                    if ($procesos != 0) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $usuariosSeleccionados, "op" => 'in'));

                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $usuarios = $UsuarioMandante->getUsuariosMandantesCustom(" usuario_mandante.usumandante_id ", "usuario_mandante.usumandante_id", "asc", 0, count(explode(",", $usuariosSeleccionados)), $json, true);
                } else {
                    $rules = array();
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                    if ($procesos != 0) {
                        array_push($rules, array("field" => "usuario.usuario_id", "data" => $usuariosSeleccionados, "op" => 'in'));
                    }
                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $usuarios = $Usuario->getUsuariosCustom(" usuario.usuario_id ", "usuario.usuario_id", "asc", 0, count(explode(",", $usuariosSeleccionados)), $json, true);
                }

                $usuarios = json_decode($usuarios);

                $usuariosSeleccionados = "0";

                foreach ($usuarios->data as $usuario) {
                    if ($confUsuarioMandante) {
                        $usuariosSeleccionados .= ',' . $usuario->{'usuario_mandante.usumandante_id'};

                    } else {
                        $usuariosSeleccionados .= ',' . $usuario->{'usuario.usuario_id'};

                    }

                }


            }
        }

        $usuariosFinal = array();
        if ($usuariosSeleccionados != "") {
            $usuarios1 = explode(",", $usuariosSeleccionados);
            $usuarios2 = explode(",", $usuariosNoSeleccionados);

            foreach ($usuarios1 as $item) {

                if (!in_array($item, $usuarios2)) {
                    array_push($usuariosFinal, $item);
                }
            }

        }
        $usuarios = $usuariosFinal;
        //print_r($usuarios);

        $response->users = $usuarios;

        if (oldCount($usuarios) > 1) {

            if (!$confUsuarioMandante) {
                $rules = [];
                array_push($rules, array("field" => "usuario.usuario_id", "data" => implode(', ', $usuarios), "op" => "in"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);


                $Usuario = new Usuario();
                $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),pais.prefijo_celular,registro.nombre1,registro.nombre2,usuario_mandante.*,registro.celular", "usuario.fecha_crea", "desc", $SkeepRows, 100000, $json, true);

                $usuarios = json_decode($usuarios);
            } else {
                $rules = [];
                array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => implode(', ', $usuarios), "op" => "in"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);


                $Usuario = new UsuarioMandante();

                $usuarios = $Usuario->getUsuariosMandantesCustom("usuario_mandante.*,pais.prefijo_celular,registro.nombre1,registro.nombre2,registro.celular,mandante.*,usuario.login", "usuario_mandante.usumandante_id", "desc", $SkeepRows, 100000, $json, true);

                $usuarios = json_decode($usuarios);
            }


            $seguir = false;
            foreach ($usuarios->data as $usuario) {
                /*if (!$seguir && $usuario->{'usuario_mandante.usuario_mandante'} == "886") {
                    $seguir = true;

                }*/
                $seguir = true;

                if ($seguir) {


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

                    $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO();

                    $Transaction = $UsuarioAutomationMySqlDAO->getTransaction();


                    $queries = ($array["Action"]);

                    $emblue_event = "";
                    $token_emblue = "";


                    $bonoValor = 0;

                    $USERMANDANTE = 0;
                    $USER = 0;
                    $PaisUSER = 0;
                    $DepartamentoUSER = 0;
                    $CiudadUSER = 0;
                    $MonedaUSER = '';

                    $nombreUSER = '';
                    $emailUSER = '';
                    $celularUSER = '';

                    if ($confUsuarioMandante) {

                        $USER = $usuario->{'usuario_mandante.usuario_mandante'};
                        $USERMANDANTE = $usuario->{'usuario_mandante.usumandante_id'};

                        $nombreUSER = $usuario->{'usuario_mandante.nombres'};
                        $emailUSER = $usuario->{'usuario_mandante.email'};
                        $celularUSER = '';
                        $PaisUSER = $usuario->{'usuario_mandante.pais_id'};
                        $MonedaUSER = $usuario->{'usuario_mandante.moneda'};

                        if ($usuario->{'mandante.propio'} == "S") {
                            $nombreUSER = $usuario->{'registro.nombre1'} . '' . $usuario->{'registro.nombre2'};
                            $emailUSER = $usuario->{'usuario.login'};
                            $celularUSER = $usuario->{'pais.prefijo_celular'} . $usuario->{'registro.celular'};

                        }

                    } else {


                        $USER = $usuario->{'usuario.usuario_id'};
                        $USERMANDANTE = $usuario->{'usuario_mandante.usumandante_id'};

                        $PaisUSER = $usuario->{'usuario.pais_id'};
                        $nombreUSER = $usuario->{'registro.nombre1'} . '' . $usuario->{'registro.nombre2'};
                        $emailUSER = $usuario->{'usuario.login'};
                        $celularUSER = $usuario->{'pais.prefijo_celular'} . $usuario->{'registro.celular'};
                        $MonedaUSER = $usuario->{'usuario_mandante.moneda'};

                    }

                    foreach ($queries->rules as $query) {

                        switch ($query->field) {
                            case "message_slack":
                                $message = "*Automation:* " . $array["AutomationId"] . " - " . $message;

                                if ($tipo != '') {
                                    $message = $message . " *Tipo:* " . $tipo;
                                }
                                if ($valorAuto != '') {
                                    $message = $message . " *Valor:* " . $valorAuto;
                                }
                                if ($externoId != '') {
                                    $message = $message . " *ID Externo:* " . $externoId;
                                }

                                if ($bonoValor != '') {
                                    $message = $message . " *Valor Bono:* " . $bonoValor;
                                }

                                if ($ConfigurationEnvironment->isDevelopment()) {
                                    exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                } else {
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
                                $dominio = '';
                                $compania = '';
                                $color_email = '';
                                //Envia el mensaje de correo
                                $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Recuperacion de clave de afiliados', 'mail_registro.php', 'Recuperaci&#243;n de clave de afiliados', $mensaje_txt, $dominio, $compania, $color_email);


                                break;
                            case "bonus_freebet":


                                $detalles = array(
                                    "PaisUSER" => $PaisUSER,
                                    "DepartamentoUSER" => $DepartamentoUSER,
                                    "CiudadUSER" => $CiudadUSER,
                                    "MonedaUSER" => $MonedaUSER

                                );
                                $detalles = json_decode(json_encode($detalles));
                                $ganaBonoId = $query->value;

                                $BonoInterno = new BonoInterno();
                                $respuesta2 = $BonoInterno->agregarBonoFree($ganaBonoId, $USER, "0", $detalles, true);

                                if ($respuesta2 != null) {
                                    if ($respuesta2->WinBonus) {
                                        $bonoValor = $respuesta2->Valor;
                                    }
                                }

                                break;
                            case "43":


                                if ($ConfigurationEnvironment->isDevelopment()) {
                                    exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                } else {
                                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                }

                                break;
                        }

                    }

                    if ($emblue_event != "" && $token_emblue != "") {
                        $email = "tecnologiatemp3@gmail.com";
                        $nombre = "Dorado";
                        $apellido = "test";

                        $data = array(
                            "email" => $emailUSER,
                            "eventName" => $emblue_event,
                            "attributes" => array(
                                "name" => $nombreUSER,
                                "bonus_amount" => $bonoValor,
                                "phone" => $celularUSER
                            )
                        );
                        $token_emblue = base64_encode($token_emblue);

                        $response = $this->emblueRequest("/event", $data, array("Authorization: Basic " . $token_emblue, "Content-Type: application/json"));

                    }

                    if ($estado == "A") {
                        $response->needApprobation = true;
                    }

                    if ($estado == "R") {
                        $response->isRejected = true;
                    }


                    /*                    $UsuarioAutomation = new UsuarioAutomation();

                                        $UsuarioAutomation->setUsuarioId($USERMANDANTE);
                                        $UsuarioAutomation->setTipo($tipo);
                                        $UsuarioAutomation->setValor($valorAuto);
                                        $UsuarioAutomation->setUsucreaId(0);
                                        $UsuarioAutomation->setUsumodifId(0);
                                        $UsuarioAutomation->setEstado($estado);
                                        $UsuarioAutomation->setAutomationId($array["AutomationId"]);
                                        $UsuarioAutomation->setNivel($nivel);
                                        $UsuarioAutomation->setObservacion('');
                                        $UsuarioAutomation->setUsuaccionId(0);
                                        $UsuarioAutomation->setFechaAccion('');
                                        $UsuarioAutomation->setExternoId($externoId);
                                        print_r($UsuarioAutomation);

                                        $UsuarioAutomationMySqlDAO->insert($UsuarioAutomation);
                                        $Transaction->commit();*/

                    $seguir = false;
                }
            }

        }

        if (oldCount($usuarios) > 1 && false) {


            $seguir = true;
            foreach ($usuarios->data as $usuario) {
                if ($seguir) {


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

                    $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO();

                    $Transaction = $UsuarioAutomationMySqlDAO->getTransaction();


                    $queries = ($array["Action"]);

                    $emblue_event = "";
                    $token_emblue = "";


                    $bonoValor = 0;

                    $USER = 0;
                    $PaisUSER = 0;
                    $DepartamentoUSER = 0;
                    $CiudadUSER = 0;
                    $MonedaUSER = 0;

                    $nombreUSER = '';
                    $emailUSER = '';
                    $celularUSER = '';

                    if ($confUsuarioMandante) {
                        $UsuarioMandante = new UsuarioMandante($usuario);
                        $Mandante = new Mandante($UsuarioMandante->getMandante());

                        $PaisUSER = $UsuarioMandante->getPaisId();
                        $MonedaUSER = $UsuarioMandante->getMoneda();

                        $nombreUSER = $UsuarioMandante->getNombres();
                        $emailUSER = $UsuarioMandante->getEmail();
                        $celularUSER = '';

                        if ($Mandante->propio == "S") {
                            $Pais = new Pais($PaisUSER);
                            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                            $Registro = new Registro($UsuarioMandante->getUsuarioMandante());
                            $nombreUSER = $Registro->getNombre1() . " " . $Registro->getNombre2();
                            $emailUSER = $Usuario->login;
                            $celularUSER = $Pais->prefijoCelular . $Registro->celular;

                        }

                        $USER = $UsuarioMandante->getUsuarioMandante();
                    } else {
                        $Pais = new Pais($PaisUSER);
                        $Usuario = new Usuario($usuario);
                        $Registro = new Registro($UsuarioMandante->getUsuarioMandante());
                        $PaisUSER = $Usuario->paisId;
                        $MonedaUSER = $Usuario->moneda;

                        $nombreUSER = $Usuario->nombre();
                        $emailUSER = $Usuario->login();
                        $celularUSER = $Pais->prefijoCelular . $Registro->celular;

                        $USER = $usuario;

                    }

                    foreach ($queries->rules as $query) {

                        switch ($query->field) {
                            case "message_slack":
                                $message = "*Automation:* " . $array["AutomationId"] . " - " . $message;

                                if ($tipo != '') {
                                    $message = $message . " *Tipo:* " . $tipo;
                                }
                                if ($valorAuto != '') {
                                    $message = $message . " *Valor:* " . $valorAuto;
                                }
                                if ($externoId != '') {
                                    $message = $message . " *ID Externo:* " . $externoId;
                                }

                                if ($bonoValor != '') {
                                    $message = $message . " *Valor Bono:* " . $bonoValor;
                                }

                                if ($ConfigurationEnvironment->isDevelopment()) {
                                    exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                } else {
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
                                $dominio = '';
                                $compania = '';
                                $color_email = '';
                                //Envia el mensaje de correo
                                $ConfigurationEnvironment->EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Recuperacion de clave de afiliados', 'mail_registro.php', 'Recuperaci&#243;n de clave de afiliados', $mensaje_txt, $dominio, $compania, $color_email);


                                break;
                            case "bonus_freebet":


                                $detalles = array(
                                    "PaisUSER" => $PaisUSER,
                                    "DepartamentoUSER" => $DepartamentoUSER,
                                    "CiudadUSER" => $CiudadUSER,
                                    "MonedaUSER" => $MonedaUSER

                                );
                                $detalles = json_decode(json_encode($detalles));
                                $ganaBonoId = $query->value;

                                $BonoInterno = new BonoInterno();
                                $respuesta2 = $BonoInterno->agregarBonoFree($ganaBonoId, $USER, "0", $detalles, true);

                                if ($respuesta2 != null) {
                                    if ($respuesta2->WinBonus) {
                                        $bonoValor = $respuesta2->Valor;
                                    }
                                }

                                break;
                            case "43":


                                if ($ConfigurationEnvironment->isDevelopment()) {
                                    exec("php -f /home/devadmin/api/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                } else {
                                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#" . $query->value . "' > /dev/null & ");

                                }

                                break;
                        }

                    }

                    if ($emblue_event != "" && $token_emblue != "") {
                        /*$email="tecnologiatemp3@gmail.com";
                        $nombre="Dorado";
                        $apellido="test";

                        $data = array(
                            "email"=>$emailUSER,
                            "eventName"=>$emblue_event,
                            "attributes"=>array(
                                "name"=>$nombreUSER,
                                "bonus_amount"=>$bonoValor,
                                "phone"=>$phoneUSER
                            )
                        );

                        $response = $this->emblueRequest("/event",$data,array("Authorization: Basic " . $token_emblue, "Content-Type: application/json"));
                */
                    }

                    if ($estado == "A") {
                        $response->needApprobation = true;
                    }

                    if ($estado == "R") {
                        $response->isRejected = true;
                    }


                    $UsuarioAutomation = new UsuarioAutomation();

                    $UsuarioAutomation->setUsuarioId($USERMANDANTE);
                    $UsuarioAutomation->setTipo($tipo);
                    $UsuarioAutomation->setValor($valorAuto);
                    $UsuarioAutomation->setUsucreaId(0);
                    $UsuarioAutomation->setUsumodifId(0);
                    $UsuarioAutomation->setEstado($estado);
                    $UsuarioAutomation->setAutomationId($array["AutomationId"]);
                    $UsuarioAutomation->setNivel($nivel);
                    $UsuarioAutomation->setObservacion('');
                    $UsuarioAutomation->setUsuaccionId(0);
                    $UsuarioAutomation->setFechaAccion('');
                    $UsuarioAutomation->setExternoId($externoId);

                    $UsuarioAutomationMySqlDAO->insert($UsuarioAutomation);
                    $Transaction->commit();

                    $seguir = false;
                }
            }

        }


        return $response;

    }

    /** 
     * Obtiene un consolidado de usuarios que cumplen con las condiciones de un query respecto a joins provisionados
     * @param object $objectBase objectBase
     * @param object $queries queries
     * @param string $tipo tipo
     * @param UsuarioMandante $UsuarioMandante UsuarioMandante
     * @param string $usuarios usuarios
     * @param string $noUsuarios noUsuarios
     * @param bool $paisProcesado paisProcesado
     * 
     * @return object Objeto con los siguientes parámetros
     * @return object->Users Usuarios que cumplen con las condiciones
     * @return object->NoUsers Usuarios que no cumplen con las condiciones
     * @return object->paisProcesado Indica si el pais fue procesado
     * @return object->paisCondicional Pais condicional
     * @return object->procesos Procesos
     * 
     */
    public function AnalizarQuery($objectBase, $queries, $tipo, $UsuarioMandante, $usuarios = '', $noUsuarios = '', $paisProcesado = true)
    {

        $procesos = 0;

        $confUsuarioMandante = true;

        $usuariosSeleccionados = $usuarios;
        $usuariosNoSeleccionados = $noUsuarios;

        $cumple = true;

        $condition = $queries->condition;


        if ($condition == "or") {
            $cumple = false;
        }

        $CustomCant_depositZero = array();



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


        $product = array();

        $provider = array();

        $product_prev = array();

        $provider_prev = array();


        $user_birthday = array();


        $deposit_date_created = array();


        $withdraw_date_created = array();


        $withdraw_date_paid = array();


        $bet_sportbook_date_created = array();


        $bet_sportbook_date_created_value = array();

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
                    case "CustomCant_depositZero":
                        array_push($CustomCant_depositZero, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "cant_deposit":
                        array_push($cant_deposit, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "sum_deposit":
                        array_push($sum_deposit, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "prom_deposit":
                        array_push($prom_deposit, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "cant_bet_sportbook":
                        array_push($cant_bet_sportbook, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "sum_bet_sportbook":
                        array_push($sum_bet_sportbook, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "prom_bet_sportbook":
                        array_push($prom_bet_sportbook, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "cant_win_sportbook":
                        array_push($cant_win_sportbook, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "sum_win_sportbook":
                        array_push($sum_win_sportbook, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "prom_win_sportbook":
                        array_push($prom_win_sportbook, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "cant_bet_casino":
                        array_push($cant_bet_casino, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "sum_bet_casino":
                        array_push($sum_bet_casino, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "prom_bet_casino":
                        array_push($prom_bet_casino, $query);
                        $procesos = $procesos + 1;
                        break;
                    case "cant_win_casino":
                        array_push($cant_win_casino, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "sum_win_casino":
                        array_push($sum_win_casino, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "prom_win_casino":
                        array_push($prom_win_casino, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "ggr_global":
                        array_push($ggr_global, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "ggr_sport":
                        array_push($ggr_sport, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "ggr_casino":
                        array_push($ggr_casino, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "cant_withdraw":
                        array_push($cant_withdraw, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "sum_withdraw":
                        array_push($sum_withdraw, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "prom_withdraw":
                        array_push($sum_withdraw, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "product":
                        array_push($product, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "provider":
                        array_push($provider, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "product_prev":
                        array_push($product_prev, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "provider_prev":
                        array_push($provider_prev, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "user_date_created":
                        array_push($user_date_created, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "user_birthday":
                        array_push($user_birthday, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "deposit_date_created":
                        array_push($deposit_date_created, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "withdraw_date_created":
                        array_push($withdraw_date_created, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "withdraw_date_paid":
                        array_push($withdraw_date_paid, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "bet_sportbook_date_created":
                        array_push($bet_sportbook_date_created, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "bet_sportbook_date_created_value":
                        array_push($bet_sportbook_date_created_value, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "win_sportbook_date_created":
                        array_push($win_sportbook_date_created, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "bet_casino_date_created":
                        array_push($bet_casino_date_created, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "win_casino_date_created":
                        array_push($win_casino_date_created, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "ggr_global_date_created":
                        array_push($ggr_global_date_created, $query);
                        $procesos = $procesos + 1;
                        break;

                    case "ggr_sport_date_created":
                        array_push($ggr_sport_date_created, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "ggr_casino_date_created":
                        array_push($ggr_casino_date_created, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "user_country":
                        array_push($user_country, $query);
                        break;


                    case "withdraw_state":
                        array_push($withdraw_state, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "deposit_amount":
                        array_push($deposit_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "bet_sportbook_amount":
                        array_push($bet_sportbook_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "win_sportbook_amount":
                        array_push($win_sportbook_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "bet_casino_amount":
                        array_push($bet_casino_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "win_casino_amount":
                        array_push($win_casino_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "ggr_global_amount":
                        array_push($ggr_global_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "ggr_sport_amount":
                        array_push($ggr_sport_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "ggr_casino_amount":
                        array_push($ggr_casino_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "withdraw_amount":
                        array_push($withdraw_amount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "deposit_amount_sum":
                        array_push($deposit_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "bet_sportbook_amount_sum":
                        array_push($bet_sportbook_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "win_sportbook_amount_sum":
                        array_push($win_sportbook_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "bet_casino_amount_sum":
                        array_push($bet_casino_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "win_casino_amount_sum":
                        array_push($win_casino_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "ggr_global_amount_sum":
                        array_push($ggr_global_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "ggr_sport_amount_sum":
                        array_push($ggr_sport_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "ggr_casino_amount_sum":
                        array_push($ggr_casino_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "withdraw_amount_sum":
                        array_push($withdraw_amount_sum, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_sum_deposit_vsamount":
                        array_push($dep_sum_deposit_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_sum_bet_sportbook_vsamount":
                        array_push($dep_sum_bet_sportbook_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_sum_win_sportbook_vsamount":
                        array_push($dep_sum_win_sportbook_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_sum_bet_casino_vsamount":
                        array_push($dep_sum_bet_casino_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_sum_win_casino_vsamount":
                        array_push($dep_sum_win_casino_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_ggr_global_vsamount":
                        array_push($dep_ggr_global_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_ggr_sport_vsamount":
                        array_push($dep_ggr_sport_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_ggr_casino_vsamount":
                        array_push($dep_ggr_casino_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_sum_withdraw_vsamount":
                        array_push($dep_sum_withdraw_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_prom_deposit_vsamount":
                        array_push($dep_prom_deposit_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_prom_bet_sportbook_vsamount":
                        array_push($dep_prom_bet_sportbook_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_prom_win_sportbook_vsamount":
                        array_push($dep_prom_win_sportbook_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_prom_bet_casino_vsamount":
                        array_push($dep_prom_bet_casino_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_prom_win_casino_vsamount":
                        array_push($dep_prom_win_casino_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "dep_prom_withdraw_vsamount":
                        array_push($dep_prom_withdraw_vsamount, $query);
                        $procesos = $procesos + 1;
                        break;


                    case "48":

                        if (!$this->compareValues($query->operator, $query->value, $objectBase->productoId)) {
                            $cumple = false;
                        }
                        break;
                }
            }

            if ($query->rules != "") {
                $usuarios = $this->AnalizarQuery($objectBase, $query, $tipo, $UsuarioMandante, $usuariosSeleccionados, $usuariosNoSeleccionados);
                $usuariosSeleccionados = $usuarios->Users;
                $usuariosNoSeleccionados = $usuarios->NoUsers;

                $paisProcesado = $usuarios->paisProcesado;
                $paisCondicional = $usuarios->paisCondicional;

                if (!$paisProcesado) {
                    if ($paisCondicional != '0') {

                        $Usuario = new Usuario();

                        array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                        array_push($rules, array("field" => "usuario.usuario_id", "data" => $usuariosSeleccionados, "op" => 'in'));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $usuarios = $Usuario->getUsuariosCustom(" usuario.usuario_id ", "usuario.usuario_id", "asc", 0, count(explode(",", $usuariosSeleccionados)), $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosSeleccionados = "0";

                        foreach ($usuarios as $usuario) {
                            $usuariosSeleccionados .= ',' . $usuario->{'usuario.usuario_id'};
                        }


                    }
                }
            }
        }


        $paisCondicional = '0';
        $cumple = true;
        foreach ($user_country as $item) {
            $paisCondicional .= ',' . $item->value;
            /*if ($item->value != $UsuarioMandante->getPaisId() && $condition == "and") {
                $cumple = false;
            }elseif($condition == "or"){
                $cumple = true;
            }*/
            $paisProcesado = false;

        }

        foreach ($provider_prev as $item) {

            if ($item->value != $objectBase->provider && $condition == "and") {
                $cumple = false;
            } elseif ($condition == "or") {
                $cumple = true;
            }

        }

        foreach ($product_prev as $item) {
            if ($item->value != $objectBase->product && $condition == "and") {
                $cumple = false;
            } elseif ($condition == "or") {
                $cumple = true;
            }

        }

        if ($cumple) {
            if (oldCount($CustomCant_depositZero) > 0 ) {
                /*select usuario.usuario_id,registro.celular
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_saldoresumen on usuario.usuario_id = usuario_saldoresumen.usuario_id
where (usuario_saldoresumen.saldo_recarga = 0 or usuario_saldoresumen.saldo_recarga is null)
  and pais_id = 173
                and perfil_id = 'USUONLINE'
                and usuario.fecha_crea >= '2021-10-01 00:00:00'*/
                $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_saldoresumen on usuario.usuario_id = usuario_saldoresumen.usuario_id
where (usuario_saldoresumen.saldo_recarga = 0 or usuario_saldoresumen.saldo_recarga is null)
  and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        $sql = $sql . ' AND usuario_mandante.usumandante_id NOT IN ('.implode(', ', $this->usuariosProcesados).')';
                    } else {
                        $sql = $sql . ' AND usuario.usuario_id NOT IN ('.implode(', ', $this->usuariosProcesados).')';
                    }

                }

                if (oldCount($user_date_created) > 0) {
                    foreach ($user_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $oper = $this->convertOperator($oper);
                        $item->value = $this->convertValue($item->value);
                        $fieldOperation=$oper;
                        $fieldData=$item->value;
                        switch ($fieldOperation) {
                            case "eq":
                                $fieldOperation = " = '" . $fieldData . "'";
                                break;
                            case "ne":
                                $fieldOperation = " != '" . $fieldData . "'";
                                break;
                            case "lt":
                                $fieldOperation = " < '" . $fieldData . "'";
                                break;
                            case "gt":
                                $fieldOperation = " > '" . $fieldData . "'";
                                break;
                            case "le":
                                $fieldOperation = " <= '" . $fieldData . "'";
                                break;
                            case "ge":
                                $fieldOperation = " >= '" . $fieldData . "'";
                                break;
                            case "nu":
                                $fieldOperation = " = ''";
                                break;
                            case "nn":
                                $fieldOperation = " != ''";
                                break;
                            case "in":
                                $fieldOperation = " IN (" . $fieldData . ")";
                                break;
                            case "ni":
                                $fieldOperation = " NOT IN (" . $fieldData . ")";
                                break;
                            case "bw":
                                $fieldOperation = " LIKE '" . $fieldData . "%'";
                                break;
                            case "bn":
                                $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                                break;
                            case "ew":
                                $fieldOperation = " LIKE '%" . $fieldData . "'";
                                break;
                            case "en":
                                $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                                break;
                            case "cn":
                                $fieldOperation = " LIKE '%" . $fieldData . "%'";
                                break;
                            case "nc":
                                $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                                break;
                            default:
                                $fieldOperation = "";
                                break;
                        }


                        $sql = $sql . ' AND usuario.fecha_crea '. $fieldOperation;
                        //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }
                $sql = $sql . ' ORDER BY usuario.usuario_id DESC LIMIT 0,10000 ';

                $usuariosSeleccionados=array();

                $BonoInterno = new BonoInterno();
                $returnData = $BonoInterno->execQuery('', $sql);
                foreach ($returnData as $datanum) {
                                        if ($confUsuarioMandante) {

                                            array_push($usuariosSeleccionados, $datanum->{'usuario_mandante.usumandante_id'});
                                        }else{
                                            array_push($usuariosSeleccionados, $datanum->{'usuario.usuario_id'});

                                        }

                }
                $usuariosSeleccionados = implode(',',$usuariosSeleccionados);





            }

            if (oldCount($cant_deposit) > 0 || oldCount($sum_deposit) > 0 || oldCount($prom_deposit) > 0 || oldCount($dep_sum_deposit_vsamount) > 0 || oldCount($dep_prom_deposit_vsamount) > 0) {

                $rules = [];
                $rulesHaving = [];

                $ConUsuariosNoIncluidos = false;

                if (oldCount($deposit_date_created) > 0) {
                    foreach ($deposit_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $oper = $this->convertOperator($oper);
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


                if ($UsuarioMandante->getUsuarioMandante() != null && $UsuarioMandante->getUsuarioMandante() != '') {

                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
                    } else {
                        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    }
                }


                if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {

                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $usuariosSeleccionados, "op" => "in"));
                    } else {
                        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $usuariosSeleccionados, "op" => "in"));
                    }

                } else {
                    $usuariosSeleccionados = '0';
                }

                if ($paisCondicional != '0') {
                    $paisProcesado = true;
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                }

                if (oldCount($product) > 0) {
                    foreach ($product as $item) {
                        array_push($rules, array("field" => "transaccion_producto.producto_id", "data" => $item->value, "op" => 'eq'));

                    }
                }

                if (oldCount($provider) > 0) {
                    foreach ($provider as $item) {

                        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $item->value, "op" => 'eq'));
                    }
                }


                /*if (oldCount($deposit_amount_sum) > 0) {
                    foreach ($deposit_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "SUM(usuario_recarga.valor)", "data" => $item->value, "op" => $oper));
                    }
                }*/

                if (oldCount($deposit_amount_sum) > 0) {
                    foreach ($deposit_amount_sum as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }


                        array_push($rulesHaving, array("field" => "SUM(usuario_recarga.valor)", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($sum_deposit) > 0) {
                    foreach ($sum_deposit as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }


                        array_push($rulesHaving, array("field" => "SUM(usuario_recarga.valor)", "data" => $item->value, "op" => $oper));
                    }
                }


                if (oldCount($cant_deposit) > 0) {
                    foreach ($cant_deposit as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }


                        array_push($rulesHaving, array("field" => "count(usuario_recarga.recarga_id)", "data" => $item->value, "op" => $oper));
                    }
                }


                if (!$ConUsuariosNoIncluidos) {

                    if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {
                        array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $usuariosSeleccionados, "op" => "in"));
                    } else {
                        $usuariosSeleccionados = '0';
                    }
                }


                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    } else {
                        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    }

                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $filtroHaving = array("rules" => $rulesHaving, "groupOp" => "AND");
                $filtroHaving = json_encode($filtroHaving);

                $UsuarioRecarga = new UsuarioRecarga("", "", $this->transaction);

                $data = $UsuarioRecarga->getUsuarioRecargasCustomAutomation("  SUM(usuario_recarga.valor) sum, COUNT(usuario_recarga.recarga_id) cont,usuario_mandante.usumandante_id,usuario_recarga.usuario_id ", "usuario_recarga.recarga_id", "asc", 0, 1000000, $json, true, 'usuario_recarga.usuario_id', $filtroHaving);
                $data = json_decode($data)->data;


                if ($ConUsuariosNoIncluidos) {
                    $usuariosNoSeleccionados = '0';

                } else {
                    $usuariosSeleccionados = '0';

                }


                foreach ($data as $datum) {

                    if ($ConUsuariosNoIncluidos) {

                        if ($confUsuarioMandante) {
                            if ($datum->{'usuario_mandante.usumandante_id'} != '') {
                                $usuariosNoSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                            }
                        } else {
                            $usuariosNoSeleccionados .= ',' . $datum->{'usuario_recarga.usuario_id'};
                        }

                    } else {

                        if ($confUsuarioMandante) {
                            if ($datum->{'usuario_mandante.usumandante_id'} != '') {
                                $usuariosSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                            }
                        } else {
                            $usuariosSeleccionados .= ',' . $datum->{'usuario_recarga.usuario_id'};
                        }

                    }

                    foreach ($cant_deposit as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($sum_deposit as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($prom_deposit as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    $amount = $objectBase->amount;
                    foreach ($dep_sum_deposit_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_deposit_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
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
                                } elseif ($condition == "or") {
                                    $cumple = true;
                                }

                                if ($cumple) {
                                    if ($confUsuarioMandante) {

                                        $usuariosSeleccionados = $UsuarioMandante->usumandanteId;
                                    } else {
                                        $usuariosSeleccionados = $UsuarioMandante->usuarioMandante;

                                    }

                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($cant_bet_sportbook) > 0 || oldCount($sum_bet_sportbook) > 0 || oldCount($bet_sportbook_amount_sum) > 0 || oldCount($prom_bet_sportbook) > 0 || oldCount($dep_sum_bet_sportbook_vsamount) > 0 || oldCount($dep_prom_bet_sportbook_vsamount) > 0) {

                $rules = [];
                $rulesHaving = [];

                $ConUsuariosNoIncluidos = false;

                if (oldCount($bet_sportbook_date_created) > 0) {
                    foreach ($bet_sportbook_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($bet_sportbook_date_created_value) > 0) {
                    foreach ($bet_sportbook_date_created_value as $item) {
                        $item->value = $this->convertValue2($item->value);
                        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => $item->value, "op" => 'bt'));
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

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }


                        array_push($rulesHaving, array("field" => "SUM(it_ticket_enc.vlr_apuesta)", "data" => $item->value, "op" => $oper));
                    }
                }


                if (oldCount($sum_bet_sportbook) > 0) {
                    foreach ($sum_bet_sportbook as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "SUM(it_ticket_enc.vlr_apuesta)", "data" => $item->value, "op" => $oper));
                    }
                }


                //array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "S", "op" => "ne"));
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

                if ($UsuarioMandante->getUsuarioMandante() != null && $UsuarioMandante->getUsuarioMandante() != '') {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    } else {
                        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    }

                }

                if (!$ConUsuariosNoIncluidos) {

                    if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {

                        if ($confUsuarioMandante) {
                            array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        } else {
                            array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        }

                    } else {
                        $usuariosSeleccionados = '0';
                    }
                }


                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    } else {
                        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    }

                }


                if ($paisCondicional != '0') {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                }


                $filtroHaving = array("rules" => $rulesHaving, "groupOp" => "AND");
                $filtroHaving = json_encode($filtroHaving);

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);
                $ItTicketEnc = new ItTicketEnc();

                $data = $ItTicketEnc->getTicketsCustomAutomation("  SUM(it_ticket_enc.vlr_apuesta) sum, COUNT(it_ticket_enc.it_ticket_id) cont,it_ticket_enc.usuario_id,usuario_mandante.usumandante_id ", "it_ticket_enc.usuario_id", "asc", 0, 1000000, $json, true, '', $filtroHaving);
                $data = json_decode($data)->data;

                if ($ConUsuariosNoIncluidos) {
                    $usuariosNoSeleccionados = '0';

                } else {
                    $usuariosSeleccionados = '0';

                }


                foreach ($data as $datum) {

                    if ($ConUsuariosNoIncluidos) {
                        if ($confUsuarioMandante) {
                            $usuariosNoSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                        } else {
                            $usuariosNoSeleccionados .= ',' . $datum->{'it_ticket_enc.usuario_id'};
                        }
                    } else {
                        if ($confUsuarioMandante) {
                            $usuariosSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                        } else {
                            $usuariosSeleccionados .= ',' . $datum->{'it_ticket_enc.usuario_id'};
                        }
                    }

                    foreach ($cant_bet_sportbook as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($sum_bet_sportbook as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($prom_bet_sportbook as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    $amount = $objectBase->amount;
                    foreach ($dep_sum_bet_sportbook_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_bet_sportbook_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
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
                                } elseif ($condition == "or") {
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($cant_win_sportbook) > 0 || oldCount($sum_win_sportbook) > 0 || oldCount($prom_win_sportbook) > 0 || oldCount($dep_prom_win_sportbook_vsamount) > 0 || oldCount($dep_sum_win_sportbook_vsamount) > 0) {

                $rules = [];
                $rulesHaving = [];

                $ConUsuariosNoIncluidos = false;

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
                //array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));

                array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "S", "op" => "ne"));
                array_push($rules, array("field" => "it_ticket_enc.premiado", "data" => "S", "op" => "eq"));
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


                if ($UsuarioMandante->getUsuarioMandante() != null && $UsuarioMandante->getUsuarioMandante() != '') {
                    array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                }

                if ($paisCondicional != '0') {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                }


                /*if (oldCount($win_sportbook_amount_sum) > 0) {
                    foreach ($win_sportbook_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => " SUM(it_ticket_enc.vlr_premio)", "data" => $item->value, "op" => $oper));
                    }
                }*/

                if (oldCount($win_sportbook_amount_sum) > 0) {
                    foreach ($win_sportbook_amount_sum as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "SUM(it_ticket_enc.vlr_premio)", "data" => $item->value, "op" => $oper));
                    }
                }


                if (oldCount($sum_win_sportbook) > 0) {
                    foreach ($sum_win_sportbook as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "SUM(it_ticket_enc.vlr_premio)", "data" => $item->value, "op" => $oper));
                    }
                }


                if (!$ConUsuariosNoIncluidos) {

                    if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {

                        if ($confUsuarioMandante) {
                            array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        } else {
                            array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        }
                    } else {
                        $usuariosSeleccionados = '0';
                    }
                }


                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    } else {
                        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    }

                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $filtroHaving = array("rules" => $rulesHaving, "groupOp" => "AND");
                $filtroHaving = json_encode($filtroHaving);


                $ItTicketEnc = new ItTicketEnc();

                $data = $ItTicketEnc->getTicketsCustomAutomation("  SUM(it_ticket_enc.vlr_premio) sum, COUNT(it_ticket_enc.it_ticket_id) cont,it_ticket_enc.usuario_id,usuario_mandante.usumandante_id ", "it_ticket_enc.usuario_id", "asc", 0, 1000000, $json, true, '', $filtroHaving);
                $data = json_decode($data)->data;

                if ($ConUsuariosNoIncluidos) {
                    $usuariosNoSeleccionados = '0';

                } else {
                    $usuariosSeleccionados = '0';

                }


                foreach ($data as $datum) {

                    if ($ConUsuariosNoIncluidos) {
                        if ($confUsuarioMandante) {
                            $usuariosNoSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                        } else {
                            $usuariosNoSeleccionados .= ',' . $datum->{'it_ticket_enc.usuario_id'};
                        }
                    } else {
                        if ($confUsuarioMandante) {
                            $usuariosSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                        } else {
                            $usuariosSeleccionados .= ',' . $datum->{'it_ticket_enc.usuario_id'};
                        }
                    }

                    foreach ($cant_win_sportbook as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($sum_win_sportbook as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($prom_win_sportbook as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    $amount = $objectBase->amount;
                    foreach ($dep_sum_win_sportbook_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_win_sportbook_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
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
                                } elseif ($condition == "or") {
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($cant_bet_casino) > 0 || oldCount($sum_bet_casino) > 0 || oldCount($prom_bet_casino) > 0 || oldCount($dep_sum_bet_casino_vsamount) > 0 || oldCount($dep_prom_bet_casino_vsamount) > 0) {

                $rules = [];
                $rulesHaving = [];

                $ConUsuariosNoIncluidos = false;

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

                if ($UsuarioMandante->getUsuarioMandante() != null && $UsuarioMandante->getUsuarioMandante() != '') {
                    array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
                }


                /*if (oldCount($bet_casino_amount_sum) > 0) {
                    foreach ($bet_casino_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "SUM(transaccion_juego.valor_ticket)", "data" => $item->value, "op" => $oper));
                    }
                }*/

                if (oldCount($bet_casino_amount_sum) > 0) {
                    foreach ($bet_casino_amount_sum as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "SUM(transaccion_juego.valor_ticket)", "data" => $item->value, "op" => $oper));
                    }
                }

                if (oldCount($sum_bet_casino) > 0) {
                    foreach ($sum_bet_casino as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "SUM(transaccion_juego.valor_ticket)", "data" => $item->value, "op" => $oper));
                    }
                }


                if (!$ConUsuariosNoIncluidos) {

                    if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {

                        if ($confUsuarioMandante) {
                            array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        } else {
                            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $usuariosSeleccionados, "op" => "in"));
                        }

                    } else {
                        $usuariosSeleccionados = '0';
                    }
                }


                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    } else {
                        array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    }

                }


                if ($paisCondicional != '0') {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                }


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);


                $filtroHaving = array("rules" => $rulesHaving, "groupOp" => "AND");
                $filtroHaving = json_encode($filtroHaving);


                $TransaccionJuego = new TransaccionJuego("", "", "", $this->transaction);

                $data = $TransaccionJuego->getTransaccionesCustomAutomation("  SUM(transaccion_juego.valor_ticket) sum, COUNT(transaccion_juego.transjuego_id) cont, transaccion_juego.usuario_id,usuario_mandante.usuario_mandante ", "transaccion_juego.usuario_id", "asc", 0, 1000000, $json, true, '', $filtroHaving);
                $data = json_decode($data)->data;

                if ($ConUsuariosNoIncluidos) {
                    $usuariosNoSeleccionados = '0';

                } else {
                    $usuariosSeleccionados = '0';

                }


                foreach ($data as $datum) {

                    if ($ConUsuariosNoIncluidos) {
                        if ($confUsuarioMandante) {
                            $usuariosNoSeleccionados .= ',' . $datum->{'transaccion_juego.usuario_id'};
                        } else {
                            $usuariosNoSeleccionados .= ',' . $datum->{'usuario_mandante.usuario_mandante'};
                        }

                    } else {
                        if ($confUsuarioMandante) {
                            $usuariosSeleccionados .= ',' . $datum->{'transaccion_juego.usuario_id'};
                        } else {
                            $usuariosSeleccionados .= ',' . $datum->{'usuario_mandante.usuario_mandante'};
                        }
                    }

                    foreach ($cant_bet_casino as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($sum_bet_casino as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($prom_bet_casino as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }


                    $amount = $objectBase->amount;
                    foreach ($dep_sum_bet_casino_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_bet_casino_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
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
                                } elseif ($condition == "or") {
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($cant_win_casino) > 0 || oldCount($sum_win_casino) > 0 || oldCount($prom_win_casino) > 0 || oldCount($dep_sum_win_casino_vsamount) > 0 || oldCount($dep_prom_win_casino_vsamount) > 0) {

                $rules = [];
                $rulesHaving = [];

                $ConUsuariosNoIncluidos = false;

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
                array_push($rules, array("field" => "transaccion_juego.premiado", "data" => "S", "op" => "eq"));

                if ($UsuarioMandante->getUsuarioMandante() != null && $UsuarioMandante->getUsuarioMandante() != '') {
                    array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
                }

                /*if (oldCount($win_casino_amount_sum) > 0) {
                    foreach ($win_casino_amount_sum as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "SUM(transaccion_juego.valor_premio)", "data" => $item->value, "op" => $oper));
                    }
                }*/


                if (oldCount($win_casino_amount_sum) > 0) {
                    foreach ($win_casino_amount_sum as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "SUM(transaccion_juego.valor_premio)", "data" => $item->value, "op" => $oper));
                    }
                }


                if (oldCount($sum_win_casino) > 0) {
                    foreach ($sum_win_casino as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "SUM(transaccion_juego.valor_premio)", "data" => $item->value, "op" => $oper));
                    }
                }

                if (!$ConUsuariosNoIncluidos) {

                    if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {
                        if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {
                            if ($confUsuarioMandante) {
                                array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $usuariosSeleccionados, "op" => "in"));
                            } else {
                                array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $usuariosSeleccionados, "op" => "in"));
                            }
                        } else {
                            $usuariosSeleccionados = '0';
                        }


                    } else {
                        $usuariosSeleccionados = '0';
                    }
                }


                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $usuariosSeleccionados, "op" => "ni"));
                    } else {
                        array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $usuariosSeleccionados, "op" => "ni"));
                    }

                }


                if ($paisCondicional != '0') {
                    array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $paisCondicional, "op" => 'in'));
                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);


                $filtroHaving = array("rules" => $rulesHaving, "groupOp" => "AND");
                $filtroHaving = json_encode($filtroHaving);

                $TransaccionJuego = new TransaccionJuego("", "", "", $this->transaction);

                $data = $TransaccionJuego->getTransaccionesCustomAutomation("  SUM(transaccion_juego.valor_premio) sum, COUNT(transaccion_juego.transjuego_id) cont,transaccion_juego.usuario_id,usuario_mandante.usuario_mandante ", "transaccion_juego.usuario_id", "asc", 0, 1000000, $json, true, '', $filtroHaving);
                $data = json_decode($data)->data;

                if ($ConUsuariosNoIncluidos) {
                    $usuariosNoSeleccionados = '0';

                } else {
                    $usuariosSeleccionados = '0';

                }


                foreach ($data as $datum) {

                    if ($ConUsuariosNoIncluidos) {
                        if ($confUsuarioMandante) {
                            $usuariosNoSeleccionados .= ',' . $datum->{'transaccion_juego.usuario_id'};
                        } else {
                            $usuariosNoSeleccionados .= ',' . $datum->{'usuario_mandante.usuario_mandante'};
                        }

                    } else {
                        if ($confUsuarioMandante) {
                            $usuariosSeleccionados .= ',' . $datum->{'transaccion_juego.usuario_id'};
                        } else {
                            $usuariosSeleccionados .= ',' . $datum->{'usuario_mandante.usuario_mandante'};
                        }
                    }

                    foreach ($cant_win_casino as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($sum_win_casino as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($prom_win_casino as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }


                    $amount = $objectBase->amount;
                    foreach ($dep_sum_win_casino_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_win_casino_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
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
                                } elseif ($condition == "or") {
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }


            if (oldCount($ggr_sport) > 0) {

                $rules = [];
                $rulesHaving = [];

                $ConUsuariosNoIncluidos = false;

                if (oldCount($ggr_sport_date_created) > 0) {
                    foreach ($ggr_sport_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                /*if (oldCount($ggr_sport_amount) > 0) {
                    foreach ($ggr_sport_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => " (SUM(it_ticket_enc.vlr_apuesta) - SUM(it_ticket_enc.vlr_premio))", "data" => $item->value, "op" => $oper));
                    }
                }*/

                if (oldCount($ggr_sport_amount) > 0) {
                    foreach ($ggr_sport_amount as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "(SUM(it_ticket_enc.vlr_apuesta) - SUM(it_ticket_enc.vlr_premio))", "data" => $item->value, "op" => $oper));
                    }
                }

                //array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));

                array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "S", "op" => "ne"));
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


                if ($UsuarioMandante->getUsuarioMandante() != null && $UsuarioMandante->getUsuarioMandante() != '') {
                    array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                }


                if (!$ConUsuariosNoIncluidos) {

                    if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {
                        if ($confUsuarioMandante) {
                            array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        } else {
                            array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        }
                    } else {
                        $usuariosSeleccionados = '0';
                    }

                }


                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    } else {
                        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    }

                }


                if ($paisCondicional != '0') {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                }


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $filtroHaving = array("rules" => $rulesHaving, "groupOp" => "AND");
                $filtroHaving = json_encode($filtroHaving);

                $ItTicketEnc = new ItTicketEnc("", $this->transaction);

                $data = $ItTicketEnc->getTicketsCustomAutomation("  (SUM(it_ticket_enc.vlr_apuesta) - SUM(it_ticket_enc.vlr_premio)) sum, COUNT(it_ticket_enc.it_ticket_id) cont,it_ticket_enc.usuario_id,usuario_mandante.usumandante_id ", "it_ticket_enc.usuario_id", "asc", 0, 1000000, $json, true, '', $filtroHaving);
                $data = json_decode($data)->data;

                if ($ConUsuariosNoIncluidos) {
                    $usuariosNoSeleccionados = '0';

                } else {
                    $usuariosSeleccionados = '0';

                }


                foreach ($data as $datum) {

                    if ($ConUsuariosNoIncluidos) {
                        if ($confUsuarioMandante) {
                            $usuariosNoSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                        } else {
                            $usuariosNoSeleccionados .= ',' . $datum->{'it_ticket_enc.usuario_id'};
                        }
                    } else {
                        if ($confUsuarioMandante) {
                            $usuariosSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                        } else {
                            $usuariosSeleccionados .= ',' . $datum->{'it_ticket_enc.usuario_id'};
                        }
                    }

                    foreach ($ggr_casino as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                }

            }


            if (oldCount($ggr_casino) > 0) {

                $rules = [];
                $rulesHaving = [];

                $ConUsuariosNoIncluidos = false;

                if (oldCount($ggr_casino_date_created) > 0) {
                    foreach ($ggr_casino_date_created as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => $item->value, "op" => $oper));
                    }
                }

                /*if (oldCount($ggr_casino_amount) > 0) {
                    foreach ($ggr_casino_amount as $item) {
                        $oper = $this->convertOperator($item->operator, $item->value);
                        $item->value = $this->convertValue($item->value);
                        array_push($rules, array("field" => " (SUM(transaccion_juego.valor_ticket)-SUM(transaccion_juego.valor_premio))", "data" => $item->value, "op" => $oper));
                    }
                }*/

                if (oldCount($ggr_casino_amount) > 0) {
                    foreach ($ggr_casino_amount as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "(SUM(transaccion_juego.valor_ticket)-SUM(transaccion_juego.valor_premio))", "data" => $item->value, "op" => $oper));
                    }
                }


                if ($UsuarioMandante->getUsuarioMandante() != null && $UsuarioMandante->getUsuarioMandante() != '') {
                    array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
                }

                if (!$ConUsuariosNoIncluidos) {

                    if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {

                        if ($confUsuarioMandante) {
                            array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        } else {
                            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $usuariosSeleccionados, "op" => "in"));
                        }

                    } else {
                        $usuariosSeleccionados = '0';
                    }
                }


                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    } else {
                        array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    }

                }


                if ($paisCondicional != '0') {
                    array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $paisCondicional, "op" => 'in'));
                }


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);


                $filtroHaving = array("rules" => $rulesHaving, "groupOp" => "AND");
                $filtroHaving = json_encode($filtroHaving);


                $TransaccionJuego = new TransaccionJuego("", "", "", $this->transaction);

                $data = $TransaccionJuego->getTransaccionesCustomAutomation("  (SUM(transaccion_juego.valor_ticket)-SUM(transaccion_juego.valor_premio)) sum, COUNT(transaccion_juego.transjuego_id) cont, transaccion_juego.usuario_id,usuario_mandante.usuario_mandante ", "transaccion_juego.usuario_id", "asc", 0, 1000000, $json, true, '', $filtroHaving);
                $data = json_decode($data)->data;

                if ($ConUsuariosNoIncluidos) {
                    $usuariosNoSeleccionados = '0';

                } else {
                    $usuariosSeleccionados = '0';

                }


                foreach ($data as $datum) {

                    if ($ConUsuariosNoIncluidos) {
                        if ($confUsuarioMandante) {
                            $usuariosNoSeleccionados .= ',' . $datum->{'transaccion_juego.usuario_id'};
                        } else {
                            $usuariosNoSeleccionados .= ',' . $datum->{'usuario_mandante.usuario_mandante'};
                        }

                    } else {
                        if ($confUsuarioMandante) {
                            $usuariosSeleccionados .= ',' . $datum->{'transaccion_juego.usuario_id'};
                        } else {
                            $usuariosSeleccionados .= ',' . $datum->{'usuario_mandante.usuario_mandante'};
                        }
                    }


                    foreach ($ggr_casino as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                }

            }


            if (oldCount($cant_withdraw) > 0 || oldCount($sum_withdraw) > 0 || oldCount($prom_withdraw) > 0 || oldCount($dep_sum_withdraw_vsamount) > 0 || oldCount($dep_prom_withdraw_vsamount) > 0) {

                $rules = [];
                $rulesHaving = [];

                $ConUsuariosNoIncluidos = false;

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

                /* if (oldCount($withdraw_amount_sum) > 0) {
                     foreach ($withdraw_amount_sum as $item) {
                         $oper = $this->convertOperator($item->operator, $item->value);
                         $item->value = $this->convertValue($item->value);
                         array_push($rules, array("field" => "SUM(cuenta_cobro.valor)", "data" => $item->value, "op" => $oper));
                     }
                 }*/

                if (oldCount($withdraw_amount_sum) > 0) {
                    foreach ($withdraw_amount_sum as $item) {

                        if ($item->value == 0 && $item->operator == '=') {
                            $ConUsuariosNoIncluidos = true;

                            $oper = 'gt';
                            $item->value = 0;

                        } else {
                            $oper = $this->convertOperator($item->operator, $item->value);
                            $item->value = $this->convertValue($item->value);
                        }
                        array_push($rulesHaving, array("field" => "SUM(cuenta_cobro.valor)", "data" => $item->value, "op" => $oper));
                    }
                }


                if ($UsuarioMandante->getUsuarioMandante() != null && $UsuarioMandante->getUsuarioMandante() != '') {
                    array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                }


                if (!$ConUsuariosNoIncluidos) {


                    if ($usuariosSeleccionados != null && $usuariosSeleccionados != '' && $usuariosSeleccionados != '0') {

                        if ($confUsuarioMandante) {
                            array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        } else {
                            array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $usuariosSeleccionados, "op" => "in"));
                        }
                    } else {
                        $usuariosSeleccionados = '0';
                    }
                }


                if ($this->usuariosPuedenRepetir == 0 && oldCount($this->usuariosProcesados) > 0) {
                    if ($confUsuarioMandante) {
                        array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    } else {
                        array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => implode(', ', $this->usuariosProcesados), "op" => "ni"));
                    }

                }

                if ($paisCondicional != '0') {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $paisCondicional, "op" => 'in'));
                }


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $filtroHaving = array("rules" => $rulesHaving, "groupOp" => "AND");
                $filtroHaving = json_encode($filtroHaving);

                $CuentaCobro = new CuentaCobro("", "", "", $this->transaction);

                $data = $CuentaCobro->getCuentasCobroCustomAutomation("  SUM(cuenta_cobro.valor) sum, COUNT(cuenta_cobro.cuenta_id) cont ", "cuenta_cobro.cuenta_id,cuenta_cobro.usuario_id,usuario_mandante.usumandante_id", "asc", 0, 1000000, $json, true, '', $filtroHaving);
                $data = json_decode($data)->data;

                if ($ConUsuariosNoIncluidos) {
                    $usuariosNoSeleccionados = '0';

                } else {
                    $usuariosSeleccionados = '0';

                }


                foreach ($data as $datum) {

                    if ($ConUsuariosNoIncluidos) {
                        if ($confUsuarioMandante) {
                            $usuariosNoSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                        } else {
                            $usuariosNoSeleccionados .= ',' . $datum->{'cuenta_cobro.usuario_id'};
                        }
                    } else {
                        if ($confUsuarioMandante) {
                            $usuariosSeleccionados .= ',' . $datum->{'usuario_mandante.usumandante_id'};
                        } else {
                            $usuariosSeleccionados .= ',' . $datum->{'cuenta_cobro.usuario_id'};
                        }
                    }

                    foreach ($cant_withdraw as $cant) {
                        if (!$this->compareValues($cant->operator, $cant->value, $datum->{'.cont'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($sum_withdraw as $sum) {
                        if (!$this->compareValues($sum->operator, $sum->value, $datum->{'.sum'}) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($prom_withdraw as $prom) {
                        if (!$this->compareValues($prom->operator, $prom->value, ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }


                    $amount = $objectBase->amount;
                    foreach ($dep_sum_withdraw_vsamount as $sum) {
                        if (!$this->compareValues($sum->operator, ($sum->value * $amount), ($datum->{'.sum'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
                            $cumple = true;
                        }
                    }

                    foreach ($dep_prom_withdraw_vsamount as $prom) {
                        if (!$this->compareValues($prom->operator, ($prom->value * $amount), ($datum->{'.sum'} / $datum->{'.cont'})) && $condition == "and") {
                            $cumple = false;
                        } elseif ($condition == "or") {
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
                                } elseif ($condition == "or") {
                                    $cumple = true;
                                }
                            }

                        }
                    }
                }
            }
        }


        return json_decode(json_encode(array(
            "Users" => $usuariosSeleccionados,
            "NoUsers" => $usuariosNoSeleccionados,
            "paisProcesado" => $paisProcesado,
            "paisCondicional" => $paisCondicional,
            "procesos" => $procesos
        )));

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


            $usuarios = $this->getAutomationsCustom("  usuario_alerta.* ", "usuario_alerta.usualerta_id", "asc", $SkeepRows, $MaxRows, $json, true);

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
        $data = array();

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

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
        $value1 = (string)$value1;
        $value2 = (string)$value2;
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
                return $value1;
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
        $value1 = (string)$value1;
        switch ($value1) {
            case "lastmonth":
                $date = date("Y-m-d", strtotime("-1 month"));

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

        return $date;


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
    public function convertOperator2($value1, $value2 = "")
    {
        $value1 = (string)$value1;
        $value2 = (string)$value2;
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
    public function convertValue2($value1)
    {
        $valueFirst = explode(" ", $value1)[0];
        $valueFirstH = false;

        $valueSecond = explode(" ", $value1)[1];
        $valueSecondH = false;

        if (strpos($valueFirst, 'h') == true) {
            $valueFirstH = true;
        }

        if (strpos($valueSecond, 'h') == true) {
            $valueSecondH = true;
        }

        $valueFirst = str_replace(array("d", "h"), "", $valueFirst);
        $valueSecond = str_replace(array("d", "h"), "", $valueSecond);

        $str = "";

        if ($valueFirstH) {
            $str = $str . " '" . date("Y-m-d H:i:s", strtotime($valueFirst . " hour")) . "' ";
        } else {
            $str = $str . " '" . date("Y-m-d", strtotime($valueFirst . " day")) . "' ";

        }

        if ($valueSecond != '') {
            if ($valueSecondH) {
                $str = $str . " AND '" . date("Y-m-d H:i:s", strtotime($valueSecond . " hour")) . "' ";
            } else {
                $str = $str . " AND '" . date("Y-m-d", strtotime($valueSecond . " day")) . "' ";

            }
        } else {
            if ($valueFirstH) {
                $str = $str . " AND  '" . date("Y-m-d H:i:s", time()) . "' ";
            } else {
                $str = $str . " AND  '" . date("Y-m-d", time()) . "' ";

            }
        }

        return $str;


    }


}

?>
