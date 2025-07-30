<?php namespace Backend\dto;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Exception;
use stdClass;

/** 
* Clase 'UsuarioRuleta'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioRuleta'
* 
* Ejemplo de uso: 
* $UsuarioRuleta = new UsuarioRuleta();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioRuleta
{

    /**
    * Representación de la columna 'usuruletaId' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $usuruletaId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'ruletaId' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $ruletaId;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'posicion' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $posicion;

    /**
    * Representación de la columna 'valorBase' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $valorBase;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'errorId' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $errorId;

    /**
    * Representación de la columna 'idExterno' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $idExterno;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'version' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $version;

    /**
    * Representación de la columna 'apostado' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $apostado;

    /**
    * Representación de la columna 'codigo' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $codigo;

    /**
    * Representación de la columna 'externoId' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $externoId;

    /**
    * Representación de la columna 'valorPremio' de la tabla 'UsuarioRuleta'
    *
    * @var string
    */
    var $valorPremio;

    /**
     * Representación de la columna 'premio' de la tabla 'UsuarioRuleta'    
     * @var string
     */
    var $premio;

    /**
    * Representación de la columna 'fecha_expiracion' de la tabla 'UsuarioRuleta'
    * @var string
    */
    var $fechaExpiracion; 

    /**
    * Constructor de clase
    *
    *
    * @param String $usuruletaId usuruletaId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioRuleta no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuruletaId="", $usuarioId="")
    {
        if ($usuruletaId != "") 
        {

            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();

            $UsuarioRuleta = $UsuarioRuletaMySqlDAO->load($usuruletaId);

            if ($UsuarioRuleta != null && $UsuarioRuleta != "") 
            {
            
                $this->usuruletaId = $UsuarioRuleta->usuruletaId;
                $this->usuarioId = $UsuarioRuleta->usuarioId;
                $this->ruletaId = $UsuarioRuleta->ruletaId;
                $this->valor = $UsuarioRuleta->valor;
                $this->posicion = $UsuarioRuleta->posicion;
                $this->valorBase = $UsuarioRuleta->valorBase;
                $this->fechaCrea = $UsuarioRuleta->fechaCrea;
                $this->usucreaId = $UsuarioRuleta->usucreaId;
                $this->fechaModif = $UsuarioRuleta->fechaModif;
                $this->usumodifId = $UsuarioRuleta->usumodifId;
                $this->estado = $UsuarioRuleta->estado;
                $this->errorId = $UsuarioRuleta->errorId;
                $this->idExterno = $UsuarioRuleta->idExterno;
                $this->mandante = $UsuarioRuleta->mandante;
                $this->version = $UsuarioRuleta->version;
                $this->apostado = $UsuarioRuleta->apostado;
                $this->codigo = $UsuarioRuleta->codigo;
                $this->externoId = $UsuarioRuleta->externoId;
                $this->valorPremio = $UsuarioRuleta->valorPremio;
                $this->premio = $UsuarioRuleta->premio;
                $this->fechaExpiracion = $UsuarioRuleta->fechaExpiracion;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        
        }
        elseif ($usuarioId != "" && $usuruletaId != "")
        {

            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();

            $UsuarioRuleta = $UsuarioRuletaMySqlDAO->queryByUsuarioIdAndRuletaId($usuruletaId,$usuarioId);
            $UsuarioRuleta = $UsuarioRuleta[0];

            if ($UsuarioRuleta != null && $UsuarioRuleta != "")
            {

                $this->usuruletaId = $UsuarioRuleta->usuruletaId;
                $this->usuarioId = $UsuarioRuleta->usuarioId;
                $this->ruletaId = $UsuarioRuleta->ruletaId;
                $this->valor = $UsuarioRuleta->valor;
                $this->posicion = $UsuarioRuleta->posicion;
                $this->valorBase = $UsuarioRuleta->valorBase;
                $this->fechaCrea = $UsuarioRuleta->fechaCrea;
                $this->usucreaId = $UsuarioRuleta->usucreaId;
                $this->fechaModif = $UsuarioRuleta->fechaModif;
                $this->usumodifId = $UsuarioRuleta->usumodifId;
                $this->estado = $UsuarioRuleta->estado;
                $this->errorId = $UsuarioRuleta->errorId;
                $this->idExterno = $UsuarioRuleta->idExterno;
                $this->mandante = $UsuarioRuleta->mandante;
                $this->version = $UsuarioRuleta->version;
                $this->apostado = $UsuarioRuleta->apostado;
                $this->codigo = $UsuarioRuleta->codigo;
                $this->externoId = $UsuarioRuleta->externoId;
                $this->valorPremio = $UsuarioRuleta->valorPremio;
                $this->premio = $UsuarioRuleta->premio;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "33");
            }

        }

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
     * Obtener el campo ruletaId de un objeto
     *
     * @return String ruletaId ruletaId
     * 
     */
    public function getRuletaId()
    {
        return $this->ruletaId;
    }

    /**
     * Modificar el campo 'ruletaId' de un objeto
     *
     * @param String $ruletaId ruletaId
     *
     * @return no
     *
     */
    public function setRuletaId($ruletaId)
    {
        $this->ruletaId = $ruletaId;
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
     * Obtener el campo posicion de un objeto
     *
     * @return String posicion posicion
     * 
     */
    public function getValorRuleta()
    {
        return $this->posicion;
    }

    /**
     * Modificar el campo 'posicion' de un objeto
     *
     * @param String $posicion posicion
     *
     * @return no
     *
     */
    public function setValorRuleta($posicion)
    {
        $this->posicion = $posicion;
    }

    /**
     * Obtener el campo valorBase de un objeto
     *
     * @return String valorBase valorBase
     * 
     */
    public function getValorBase()
    {
        return $this->valorBase;
    }

    /**
     * Modificar el campo 'valorBase' de un objeto
     *
     * @param String $valorBase valorBase
     *
     * @return no
     *
     */
    public function setValorBase($valorBase)
    {
        $this->valorBase = $valorBase;
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
     * Obtener el campo errorId de un objeto
     *
     * @return String errorId errorId
     * 
     */
    public function getErrorId()
    {
        return $this->errorId;
    }
    
    /**
     * Modificar el campo 'errorId' de un objeto
     *
     * @param String $errorId errorId
     *
     * @return no
     *
     */
    public function setErrorId($errorId)
    {
        $this->errorId = $errorId;
    }

    /**
     * Obtener el campo idExterno de un objeto
     *
     * @return String idExterno idExterno
     * 
     */
    public function getIdExterno()
    {
        return $this->idExterno;
    }

    /**
     * Modificar el campo 'idExterno' de un objeto
     *
     * @param String $idExterno idExterno
     *
     * @return no
     *
     */
    public function setIdExterno($idExterno)
    {
        $this->idExterno = $idExterno;
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
     * Obtener el campo version de un objeto
     *
     * @return String version version
     * 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Modificar el campo 'version' de un objeto
     *
     * @param String $version version
     *
     * @return no
     *
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Obtener el campo apostado de un objeto
     *
     * @return String apostado apostado
     * 
     */
    public function getApostado()
    {
        return $this->apostado;
    }

    /**
     * Modificar el campo 'apostado' de un objeto
     *
     * @param String $apostado apostado
     *
     * @return no
     *
     */
    public function setApostado($apostado)
    {
        $this->apostado = $apostado;
    }

    /**
     * Obtener el campo codigo de un objeto
     *
     * @return String codigo codigo
     * 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Modificar el campo 'codigo' de un objeto
     *
     * @param String $codigo codigo
     *
     * @return no
     *
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Obtener el campo externoId de un objeto
     *
     * @return String externoId externoId
     * 
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Modificar el campo 'externoId' de un objeto
     *
     * @param String $externoId externoId
     *
     * @return no
     *
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }

    /**
     * Obtener el campo usuruletaId de un objeto
     *
     * @return String usuruletaId usuruletaId
     * 
     */
    public function getUsuruletaId()
    {
        return $this->usuruletaId;
    }

    /**
     * Obtener el campo valorPremio de un objeto
     *
     * @return String valorPremio valorPremio
     * 
     */
    public function getValorPremio()
    {
        return $this->valorPremio;
    }

    /**
     * Modificar el campo 'valorPremio' de un objeto
     *
     * @param String $valorPremio valorPremio
     *
     * @return no
     *
     */

    public function setValorPremio($valorPremio)
    {
        $this->valorPremio = $valorPremio;
    }


/**
 * Modificar el campo 'premio' de un objeto
 *
 * @param string $premio premio
 *
 * @return void
 */
public function setPremio($premio)
{
    $this->premio = $premio;
}

/**
 * Obtener el campo 'premio' de un objeto
 *
 * @return string premio
 */
public function getPremio()
{
    return $this->premio;
}
    /**
     * Modificar el campo 'valorPremio' de un objeto
     *
     * @param String $valorPremio valorPremio
     *
     * @return no
     *
     */



    /**
    * Realizar una consulta en la tabla de UsuarioRuleta 'UsuarioRuleta'
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

    public function getUsuarioRuletaCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $UsuarioRuletaMySqlDAO= new UsuarioRuletaMySqlDAO();

        $ruletas = $UsuarioRuletaMySqlDAO->queryUsuarioRuletaCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($ruletas != null && $ruletas != "")
        {
            return $ruletas;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


    /**
     * Obtiene una lista personalizada de UsuarioRuleta.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param string $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento de resultados.
     * @return array Lista de resultados de UsuarioRuleta.
     * @throws Exception Si no se encuentran resultados.
     */
    public function getUsuarioRuletaCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $UsuarioRuletaMySqlDAO= new UsuarioRuletaMySqlDAO();

        $ruletas = $UsuarioRuletaMySqlDAO->queryUsuarioRuletaCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($ruletas != null && $ruletas != "")
        {
            return $ruletas;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Obtiene una lista personalizada de UsuarioRuletas.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $withPosition Indica si se debe incluir la posición en los resultados.
     * @return array Lista de UsuarioRuletas obtenidos.
     * @throws Exception Si no se encuentran resultados.
     */
    public function getUsuarioRuletasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withPosition=false)
    {

        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();

        $Productos = $UsuarioRuletaMySqlDAO->queryUsuarioRuletasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withPosition);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }


    /**
     * Obtiene una lista personalizada de UsuarioRuletas sin posición.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @return array Lista de UsuarioRuletas obtenidas.
     * @throws Exception Si no se encuentran registros.
     */
    public function getUsuarioRuletasCustomWithoutPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();

        $Productos = $UsuarioRuletaMySqlDAO->queryUsuarioRuletasCustomWithoutPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "")
        {
            return $Productos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Obtiene la ruleta activa de un usuario mandante
     * @param UsuarioMandante $usuarioMandante
     * @return array
     */
    public function getRuletasUsuarioMandante(UsuarioMandante $usuarioMandante,$usuruletaId='') {

        $SkeepRows = 0;
        $MaxRows = 1;

        $UsuarioRuleta = new UsuarioRuleta();
        $rules = [];
        if($usuruletaId !=''){
            array_push($rules, array("field" => "usuario_ruleta.usuruleta_id", "data" => $usuruletaId, "op" => "eq"));

        }
        array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));
        array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => date("Y-m-d H:i:s"), "op" => "ge"));
        array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $usuarioMandante->usumandanteId, "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);
        $Ruletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.ruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);

        $Ruletas = json_decode((string) $Ruletas);

        if ($Ruletas->count[0]->{".count"} != "0") {
            $final = array();
            foreach ($Ruletas->data as $key => $value) {
                $array = [];
                $array["ruletaId"] = $value->{"usuario_ruleta.usuruleta_id"};
                $array["usuarioId"] = $value->{"usuario_ruleta.usuario_id"};

                array_push($final, $array);
            }

            foreach ($final as $key => $value) {
                $ruletaId = $value["ruletaId"];
            }

            $usuruletaId = $ruletaId;

            $rules = [];

            array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $usuarioMandante->usumandanteId, "op" => "eq"));
            array_push($rules, array("field" => "usuario_ruleta.usuruleta_id", "data" => $usuruletaId, "op" => "eq"));
            array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "'RANKAWARDMAT', 'RANKAWARD','BONO', 'RANKAWARDFREESPIN'", "op" => "in"));
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $RuletaDetalle = new RuletaDetalle();
            $Productos = $RuletaDetalle->getRuletaDetallesCustom3("ruleta_detalle.*,ruleta_interno.*,usuario_ruleta.*", "ruleta_detalle.ruletadetalle_id", "desc", 0, 100, $json2, true, 'ruleta_detalle.ruletadetalle_id');
            $Productos = json_decode($Productos);
            $roulette = array();

            if ($Productos->count[0]->{".count"} != "0") {

                foreach ($Productos->data as $key => $value) {

                    $Id = $value->{"usuario_ruleta.usuruleta_id"};
                    $Name = $value->{"ruleta_interno.descripcion"};
                    $BackgroundURL = $value->{"ruleta_interno.codigo"};

                    if (isset($roulette[$Id])) {
                        $id = $value->{"ruleta_detalle.ruletadetalle_id"};
                        $image = $value->{"ruleta_detalle.valor2"};
                        $prizeWinImageURL = $value->{"ruleta_detalle.valor3"};

                        if ($value->{"ruleta_detalle.tipo"} == "RANKAWARDMAT") {
                            $description = $value->{"ruleta_detalle.descripcion"};
                        } elseif ($value->{"ruleta_detalle.tipo"} == "BONO") {
                            $description = $value->{"ruleta_detalle.descripcion"};
                        } elseif ($value->{"ruleta_detalle.tipo"} == "RANKAWARDFREESPIN") {
                            $description = $value->{"ruleta_detalle.descripcion"};
                        } else {

                            $description = $value->{"ruleta_detalle.valor"} . " " . $value->{"ruleta_detalle.moneda"} . " " . $value->{"ruleta_detalle.descripcion"};
                        }
                        if (!isset($categoriasData[$Id]->prizes[$id])) {
                            $prizes = new stdClass();
                            $prizes->id = $id;
                            $prizes->image = $image;
                            $prizes->prizeWinImageURL = $prizeWinImageURL;
                            $prizes->text = $description;
                        }
                    } else {
                        $roulette[$Id] = new stdClass();
                        $roulette[$Id]->Id = $Id;
                        $roulette[$Id]->Name = $Name;
                        $roulette[$Id]->prizes = array();
                        $id = $value->{"ruleta_detalle.ruletadetalle_id"};
                        $image = $value->{"ruleta_detalle.valor2"};
                        $prizeWinImageURL = $value->{"ruleta_detalle.valor3"};
                        if ($value->{"ruleta_detalle.tipo"} == "RANKAWARDMAT") {
                            $description = $value->{"ruleta_detalle.descripcion"};
                        } elseif ($value->{"ruleta_detalle.tipo"} == "BONO") {
                            $description = $value->{"ruleta_detalle.descripcion"};
                        } elseif ($value->{"ruleta_detalle.tipo"} == "RANKAWARDFREESPIN") {
                            $description = $value->{"ruleta_detalle.descripcion"};
                        } else {

                            $description = $value->{"ruleta_detalle.valor"} . " " . $value->{"ruleta_detalle.moneda"} . " " . $value->{"ruleta_detalle.descripcion"};
                        }

                        $prizes = new stdClass();
                        $prizes->id = $id;
                        $prizes->image = $image;
                        $prizes->prizeWinImageURL = $prizeWinImageURL;
                        $prizes->text = $description;
                    }

                    array_push($roulette[$Id]->prizes, $prizes);
                }

                $rouletteDatanew = array();
                foreach ($roulette as $c) {
                    $cnew = array();
                    $cnew['Id'] = $c->Id;
                    $cnew['Name'] = $c->Name;
                    $cnew['BackgroundURL'] = $BackgroundURL;
                    $cnew['prizes'] = array();
                    foreach ($c->prizes as $subc) {
                        array_push($cnew['prizes'], $subc);
                    }
                    array_push($rouletteDatanew, $cnew);
                }

                return $rouletteDatanew;
            }
        }
        return [];
    }

    /**
     * Get representación de la columna 'fecha_expiracion' de la tabla 'UsuarioRuleta'
     *
     * @return  string
     */ 
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

    /**
     * Set representación de la columna 'fecha_expiracion' de la tabla 'UsuarioRuleta'
     *
     * @param  string  $fechaExpiracion  Representación de la columna 'fecha_expiracion' de la tabla 'UsuarioRuleta'
     *
     * @return  self
     */ 
    public function setFechaExpiracion(string $fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;

        return $this;
    }
}

?>
