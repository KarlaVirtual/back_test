<?php namespace Backend\dto;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\sql\Transaction;
use Exception;
/** 
* Clase 'MandanteDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'MandanteDetalle'
* 
* Ejemplo de uso: 
* $MandanteDetalle = new MandanteDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class MandanteDetalle
{

    /**
    * Representación de la columna 'manddetalleId' de la tabla 'MandanteDetalle'
    *
    * @var string
    */  
	var $manddetalleId;

    /**
    * Representación de la columna 'mandante' de la tabla 'MandanteDetalle'
    *
    * @var string
    */  
	var $mandante;

    /**
    * Representación de la columna 'tipo' de la tabla 'MandanteDetalle'
    *
    * @var string
    */  
	var $tipo;

    /**
    * Representación de la columna 'valor' de la tabla 'MandanteDetalle'
    *
    * @var string
    */  
	var $valor;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'MandanteDetalle'
    *
    * @var string
    */  
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'MandanteDetalle'
    *
    * @var string
    */  
	var $usumodifId;

    /**
    * Representación de la columna 'paisId' de la tabla 'MandanteDetalle'
    *
    * @var string
    */  
    var $paisId;

    /**
    * Representación de la columna 'estado' de la tabla 'MandanteDetalle'
    *
    * @var string
    */  
    var $estado;

    protected $arrayAll;

    /**
     * Constructor de clase
     *
     *
     * @param String manddetalleId id del mandante
     * @param String mandante mandante
     * @param String tipo tipo
     * @param String pais pais
     * @param String estado estado
     *
     *
    * @return no
    * @throws Exception si MandanteDetalle no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($manddetalleId="", $mandante="", $tipo="", $pais="", $estado="", $valor = "")
    {

        if ($mandante !== "" && $tipo !== "" && $pais !== "" && $valor!== "") {


            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();

            $MandanteDetalle = $MandanteDetalleMySqlDAO->queryByMandanteAndTipoAndPaisAndValor($mandante, $tipo, $pais,$valor);

            $MandanteDetalle = $MandanteDetalle[0];


            if ($MandanteDetalle != null && $MandanteDetalle != "") {
                $this->manddetalleId = $MandanteDetalle->manddetalleId;
                $this->mandante = $MandanteDetalle->mandante;
                $this->tipo = $MandanteDetalle->tipo;
                $this->valor = $MandanteDetalle->valor;
                $this->usucreaId = $MandanteDetalle->usucreaId;
                $this->usumodifId = $MandanteDetalle->usumodifId;
                $this->paisId = $MandanteDetalle->paisId;
                $this->estado = $MandanteDetalle->estado;
            } else {
                throw new Exception("No existe " . get_class($this), "34");
            }
        }elseif ($mandante !== "" && $tipo !== "" && $pais !== "" && $estado !== "") {


            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();

            $MandanteDetalle = $MandanteDetalleMySqlDAO->queryByMandanteAndTipoAndPaisAndEstado($mandante,$tipo,$pais,$estado);
            
            $MandanteDetalle = $MandanteDetalle[0];

            if ($MandanteDetalle != null && $MandanteDetalle != "") 
            {
                    $this->manddetalleId = $MandanteDetalle->manddetalleId;
                    $this->mandante = $MandanteDetalle->mandante;
                    $this->tipo = $MandanteDetalle->tipo;
                    $this->valor = $MandanteDetalle->valor;
                    $this->usucreaId = $MandanteDetalle->usucreaId;
                    $this->usumodifId = $MandanteDetalle->usumodifId;
                    $this->paisId = $MandanteDetalle->paisId;
                    $this->estado = $MandanteDetalle->estado;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "34");
            }
        }
        elseif ($mandante !== "" && $tipo !== "" && $pais !== "")
        {


            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();

            $MandanteDetalle = $MandanteDetalleMySqlDAO->queryByMandanteAndTipoAndPais($mandante,$tipo,$pais);
            $MandanteDetalle = $MandanteDetalle[0];

            if ($MandanteDetalle != null && $MandanteDetalle != "") 
            {
                    $this->manddetalleId = $MandanteDetalle->manddetalleId;
                    $this->mandante = $MandanteDetalle->mandante;
                    $this->tipo = $MandanteDetalle->tipo;
                    $this->valor = $MandanteDetalle->valor;
                    $this->usucreaId = $MandanteDetalle->usucreaId;
                    $this->usumodifId = $MandanteDetalle->usumodifId;
                    $this->paisId = $MandanteDetalle->paisId;
                    $this->estado = $MandanteDetalle->estado;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "34");
            }
        }elseif ($mandante != "" && $pais != "" && $estado != "")
        {
            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
            $MandanteDetalles = $MandanteDetalleMySqlDAO->queryByMandanteAndPaisAndEstado($mandante,$pais,$estado);


            foreach($MandanteDetalles as $MandanteDetalle){
                $this->arrayAll[$MandanteDetalle->tipo] = $MandanteDetalle;
            }

        }elseif ($manddetalleId !== '') {
            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
            $MandanteDetalle = $MandanteDetalleMySqlDAO->load($manddetalleId);

            if ($MandanteDetalle != null && $MandanteDetalle != "") {
                $this->manddetalleId = $MandanteDetalle->manddetalleId;
                $this->mandante = $MandanteDetalle->mandante;
                $this->tipo = $MandanteDetalle->tipo;
                $this->valor = $MandanteDetalle->valor;
                $this->usucreaId = $MandanteDetalle->usucreaId;
                $this->usumodifId = $MandanteDetalle->usumodifId;
                $this->paisId = $MandanteDetalle->paisId;
                $this->estado = $MandanteDetalle->estado;
            } else {
                throw new Exception("No existe " . get_class($this), "34");
            }
        }

    }

    
    /**
     * Obtiene todos los elementos del array.
     *
     * @return array Retorna el array completo.
     */
    public function getAllArray()
    {
        return $this->arrayAll;
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
     * Obtener el campo nombre de un objeto
     *
     * @return String nombre nombre
     * 
     */ 
    public function getManddetalleId()
    {
        return $this->manddetalleId;
    }

    /**
     * Obtener el campo paisId de un objeto
     *
     * @return String paisId paisId
     * 
     */ 
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Modificar el campo 'paisId' de un objeto
     *
     * @param String $paisId paisId
     *
     * @return no
     *
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
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
    * Realizar una consulta en la tabla de MandanteDetalle 'MandanteDetalle'
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
    * @throws Exception si los detalles no existen  
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getMandanteDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();

        $detalles = $MandanteDetalleMySqlDAO->queryMandanteDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($detalles != null && $detalles != "")
        {
            return $detalles;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "34");
        }

    }

    /**
     * Obtiene los detalles personalizados del mandante con base en los filtros entregados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Inicio de los resultados.
     * @param int $limit Límite de resultados.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @return array|null Detalles del mandante.
     * @throws Exception Si no existen detalles del mandante.
     */
    public function getMandanteDetallesCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();

        $detalles = $MandanteDetalleMySqlDAO->queryMandanteDetallesCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($detalles != null && $detalles != "")
        {
            return $detalles;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "34");
        }

    }



    /**
     * Obtiene los ajustes de referidos para un socio y país específicos.
     *
     * @param int $partner El ID del socio.
     * @param int $country El ID del país.
     * @param bool $getAwardJson Indica si se debe incluir el JSON del premio en la respuesta.
     * @return array Un arreglo de objetos que representan los premios referidos y sus condiciones.
     * @throws Exception Si ocurre un error al validar la disponibilidad del programa de referidos.
     */
    public function getPartnerAjustesReferidos(int $partner, int $country, bool $getAwardJson = false)
    {
        $ReferredAwards = [];

        //Validando disponibilidad del programa de referidos para el PaisMandante
        $PaisMandate = new PaisMandante('', (string)$partner, (string)$country);
        try {
            $PaisMandate->progReferidosDisponible();
        } catch (Exception $e) {
            //Si mandante no cuenta con campaña de referidos configurada se retornará un array nulo
            if ($e->getCode() != 4008 && $e->getCode() != 4019) throw $e;
            if ($e->getCode() == 4008) {
                return $ReferredAwards;
            }
        }

        //Solicitando plan de premios para el país mandante
        $rules = [];
        array_push($rules, ['field' => 'clasificador.abreviado', 'data' => 'AWARDSPLANREFERRED', 'op' => 'eq']);
        array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($rules, ['field' => 'mandante_detalle.mandante', 'data' => $partner, 'op' => 'eq']);
        array_push($rules, ['field' => 'mandante_detalle.pais_id', 'data' => $country, 'op' => 'eq']);
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $select = 'mandante_detalle.manddetalle_id,mandante_detalle.valor';
        $awardsPlanReferred = $this->getMandanteDetallesCustom($select, 'mandante_detalle.manddetalle_id', 'ASC', 0, 1, json_encode($filters), true);
        $awardsPlanReferred = json_decode($awardsPlanReferred)->data[0];
        $awardsPlanReferred = $awardsPlanReferred->{'mandante_detalle.valor'};
        $awardsPlanReferred = json_decode($awardsPlanReferred)->awardsplanreferred;

        //Solicitando condiciones aplicables en la campaña de referidos para el paisMandante
        $rules = [];
        array_push($rules, ['field' => 'clasificador.abreviado', 'data' => 'DEFCONDREFERRED', 'op' => 'eq']);
        array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($rules, ['field' => 'mandante_detalle.mandante', 'data' => $partner, 'op' => 'eq']);
        array_push($rules, ['field' => 'mandante_detalle.pais_id', 'data' => $country, 'op' => 'eq']);
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $select = 'mandante_detalle.manddetalle_id,mandante_detalle.valor';
        $implementedConditions = $this->getMandanteDetallesCustom($select, 'mandante_detalle.manddetalle_id', 'ASC', 0, 1, json_encode($filters), true);
        $implementedConditions = json_decode($implementedConditions)->data[0];
        $implementedConditions = json_decode($implementedConditions->{'mandante_detalle.valor'});

        //Construyendo objetos retornados a BackOffice para el programa de referidos
        foreach ($awardsPlanReferred as $awardPlan) {
            $award = (object) [];
            $award->AwardId = $awardPlan->award;
            $award->type = $awardPlan->tp;

            //Definiendo nombre del premio
            $MandanteDetalle = new MandanteDetalle($awardPlan->name);
            $award->AwardName = $MandanteDetalle->valor;
            $award->IsVerified = false;

            //Consultando etiquetas/labels, valores y abreviados de las condiciones utilizadas
            $currentConditions = array_map(function ($condition) {
                $MandanteDetalle = new MandanteDetalle($condition);
                $finalCondition['tipo'] = $MandanteDetalle->getTipo();
                $finalCondition['id'] = $condition;
                return $finalCondition;
            }, $awardPlan->conditions);

            $award->Conditions = array_map(function ($implementedCondition) use ($currentConditions, $award, $partner, $country) {
                //Consultando Label y Condition
                $MandanteDetalleLabel = new MandanteDetalle($implementedCondition->label);
                $Clasificador = new Clasificador($implementedCondition->cond);

                if ($Clasificador->abreviado != 'CONDVERIFIEDREFERRED') {
                    $condition = (object)[];
                    $condition->Label = $MandanteDetalleLabel->getValor();
                    $condition->Condition = $Clasificador->getAbreviado();
                }

                //Consultando Value
                if (!in_array($implementedCondition->cond, array_column($currentConditions, 'tipo'))) {
                    //Asignando valor por defecto a condición no utilizada
                    if ($Clasificador->abreviado != 'CONDVERIFIEDREFERRED') $condition->Value = 0;
                    else $award->IsVerified = 0;
                } else {
                    //Asignando valor utilizado a condición en uso
                    $manddetalle_id = array_reduce($currentConditions, function ($carry, $currentCondition) use ($implementedCondition) {
                        if ($currentCondition['tipo'] == $implementedCondition->cond) return $carry = $currentCondition['id'];
                        elseif ($carry != 0) return $carry;
                    }, 0);
                    $rules = [];
                    array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
                    array_push($rules, ['field' => 'mandante_detalle.mandante', 'data' => $partner, 'op' => 'eq']);
                    array_push($rules, ['field' => 'mandante_detalle.pais_id', 'data' => $country, 'op' => 'eq']);
                    array_push($rules, ['field' => 'mandante_detalle.manddetalle_id', 'data' => $manddetalle_id, 'op' => 'eq']);
                    $filters = ['rules' => $rules, 'groupOp' => 'AND'];
                    $select = 'mandante_detalle.manddetalle_id,mandante_detalle.valor';
                    $currentCondValue = $this->getMandanteDetallesCustom($select, 'mandante_detalle.manddetalle_id', 'ASC', 0, 1, json_encode($filters), true);
                    $currentCondValue = json_decode($currentCondValue)->data[0];

                    if ($Clasificador->abreviado != 'CONDVERIFIEDREFERRED') $condition->Value = $currentCondValue->{'mandante_detalle.valor'};
                    else $award->IsVerified = (int) $currentCondValue->{'mandante_detalle.valor'};
                }
                if ($award->type == 1 && $condition->Condition == 'CONDMINFIRSTDEPOSITREFERRED') return $condition;
                if ($award->type == 2 && $condition->Condition == 'CONDMINBETREFERRED') return $condition;
            }, $implementedConditions->conditions);

            $award->Conditions = array_filter($award->Conditions, function ($condition) {
                if ($condition != null) return $condition;
            });

            $award->Conditions = array_values($award->Conditions);

            //Consultando bonos ofrecidos con el premio
            $rules = [];
            array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
            array_push($rules, ['field' => 'mandante_detalle.mandante', 'data' => $partner, 'op' => 'eq']);
            array_push($rules, ['field' => 'mandante_detalle.pais_id', 'data' => $country, 'op' => 'eq']);
            array_push($rules, ['field' => 'mandante_detalle.manddetalle_id', 'data' => $awardPlan->award, 'op' => 'eq']);
            $filters = ['rules' => $rules, 'groupOp' => 'AND'];
            $select = 'mandante_detalle.manddetalle_id,mandante_detalle.valor';
            $currentBonusIds = $this->getMandanteDetallesCustom($select, 'mandante_detalle.manddetalle_id', 'ASC', 0, 1, json_encode($filters), true);
            $currentBonusIds = json_decode($currentBonusIds)->data[0];
            $currentBonusIds = explode(',', $currentBonusIds->{'mandante_detalle.valor'});

            $award->OfertedBonuses = $currentBonusIds;
            if ($getAwardJson) $award->AwardJson = $awardPlan;
            array_push($ReferredAwards, $award);
        }

        return $ReferredAwards;
    }


    /**
     * Crea un programa de referidos.
     *
     * @param Transaction $Transaction La transacción actual.
     * @param mixed $partner El socio asociado al programa de referidos.
     * @param mixed $country El país asociado al programa de referidos.
     * @param Usuario $Usuario El usuario que crea el programa de referidos.
     * @return mixed El plan de premios del programa de referidos.
     *
     * Este método realiza las siguientes acciones:
     * 1. Consulta las condiciones por defecto y sus etiquetas correspondientes.
     * 2. Crea etiquetas para las condiciones que serán insertadas.
     * 3. Inserta las condiciones por defecto para PaisMandate.
     * 4. Consulta las categorías excluidas de casino por defecto.
     * 5. Crea el detalle de categorías excluidas de casino.
     * 6. Consulta el plan de premios por defecto del programa de referidos.
     * 7. Crea el detalle del plan de premios.
     * 8. Retorna el plan de premios del programa de referidos.
     */
    public function createProgramaReferidos(Transaction $Transaction, $partner, $country, Usuario $Usuario)
    {
        $BonoInterno = new BonoInterno();
        $AuditoriaGeneral = new AuditoriaGeneral();
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);

        //Consultando condiciones por defecto y sus etiquetas correspondientes
        $Clasificador = new Clasificador('', 'DEFCONDREFERRED');
        $sql = "select mandante_detalle.manddetalle_id, mandante_detalle.tipo, mandante_detalle.valor from mandante_detalle where tipo = " . $Clasificador->getClasificadorId() . " and mandante = -1 and pais_id = -1";
        $defaultConditions = $BonoInterno->execQuery($Transaction, $sql);
        $defaultConditions = $defaultConditions[0];
        $defaultConditionsValue = json_decode($defaultConditions->{'mandante_detalle.valor'});

        //Creando labels para las condiciones que serán insertadas
        $defaultConditionsValue->conditions = array_map(function ($condition) use ($partner, $country, $Usuario, $Transaction, $AuditoriaGeneral, $MandanteDetalleMySqlDAO) {
            $MandanteDetalle = new MandanteDetalle($condition->label);
            $this->estado = 'A';
            $this->usucreaId = $Usuario->getUsuarioId();
            $this->usumodifId = $Usuario->getUsuarioId();
            $this->mandante = $partner;
            $this->paisId = $country;
            $this->tipo = $MandanteDetalle->tipo;
            $this->valor = $MandanteDetalle->valor;

            $manddetalleId = $MandanteDetalleMySqlDAO->insert($this);
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, '', $this->valor, $this->tipo);

            $condition->label = (int)$manddetalleId;
            return $condition;
        }, $defaultConditionsValue->conditions);


        //Creando condiciones por defecto para PaisMandate
        $defaultConditionsReferredMD = new MandanteDetalle();
        $defaultConditionsReferredMD->estado = 'A';
        $defaultConditionsReferredMD->usucreaId = $Usuario->getUsuarioId();
        $defaultConditionsReferredMD->usumodifId = $Usuario->getUsuarioId();
        $defaultConditionsReferredMD->mandante = $partner;
        $defaultConditionsReferredMD->paisId = $country;
        $defaultConditionsReferredMD->tipo = $Clasificador->getClasificadorId();
        $defaultConditionsReferredMD->valor = json_encode($defaultConditionsValue);

        $MandanteDetalleMySqlDAO->insert($defaultConditionsReferredMD);
        $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, '', json_encode($defaultConditionsValue), $defaultConditionsReferredMD->tipo);


        //Consultando categorías excluídas de casino por defecto
        $Clasificador = new Clasificador('', 'EXCLUDEDCASINOCATEGORYREFERS');
        $sql = "select mandante_detalle.manddetalle_id, mandante_detalle.tipo, mandante_detalle.valor from mandante_detalle where tipo = " . $Clasificador->getClasificadorId() . " and mandante = -1 and pais_id = -1";
        $excludedCasinoCategories = $BonoInterno->execQuery($Transaction, $sql);
        $excludedCasinoCategories = $excludedCasinoCategories[0];

        //Creando detalle de categorías excluídas de casino
        $excludedCasinoCategoriesMD = new MandanteDetalle();
        $excludedCasinoCategoriesMD->estado = 'A';
        $excludedCasinoCategoriesMD->usucreaId = $Usuario->getUsuarioId();
        $excludedCasinoCategoriesMD->usumodifId = $Usuario->getUsuarioId();
        $excludedCasinoCategoriesMD->mandante = $partner;
        $excludedCasinoCategoriesMD->paisId = $country;
        $excludedCasinoCategoriesMD->tipo = $Clasificador->getClasificadorId();
        $excludedCasinoCategoriesMD->valor = $excludedCasinoCategories->{'mandante_detalle.valor'};
        $MandanteDetalleMySqlDAO->insert($excludedCasinoCategoriesMD);
        $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, '', $excludedCasinoCategoriesMD->valor, $excludedCasinoCategoriesMD->tipo);


        //Consultando plan de premios por defecto del programa de referidos
        $Clasificador = new Clasificador('', 'AWARDSPLANREFERRED');
        $sql = "select mandante_detalle.manddetalle_id, mandante_detalle.tipo, mandante_detalle.valor from mandante_detalle where tipo = " . $Clasificador->getClasificadorId() . " and mandante = -1 and pais_id = -1";
        $awardsPlan = $BonoInterno->execQuery($Transaction, $sql);
        $awardsPlan = $awardsPlan[0];

        //Creando detalle del plan de premios
        $awardsPlanMD = new MandanteDetalle();
        $awardsPlanMD->estado = 'A';
        $awardsPlanMD->usucreaId = $Usuario->getUsuarioId();
        $awardsPlanMD->usumodifId = $Usuario->getUsuarioId();
        $awardsPlanMD->mandante = $partner;
        $awardsPlanMD->paisId = $country;
        $awardsPlanMD->tipo = $Clasificador->getClasificadorId();
        $awardsPlanMD->valor = $awardsPlan->{'mandante_detalle.valor'};
        $MandanteDetalleMySqlDAO->insert($awardsPlanMD);
        $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, '', $awardsPlanMD->valor, $awardsPlanMD->tipo);

        return json_decode($awardsPlan->{'mandante_detalle.valor'});
    }


    /**
     * Crea un nuevo premio para el programa de referidos.
     *
     * @param Transaction $Transaction La transacción actual.
     * @param mixed $partner El socio o mandante.
     * @param mixed $country El país del socio o mandante.
     * @param Usuario $Usuario El usuario que realiza la acción.
     * @param mixed $currentAwardsPlan El plan de premios actual.
     * @param mixed $newAward Los detalles del nuevo premio.
     * @param int $newProgramState El estado del nuevo programa.
     * @return mixed El plan de premios actualizado.
     * @throws Exception Si ocurre un error durante la creación del premio.
     */
    public function createNuevoPremioReferidos(Transaction $Transaction, $partner, $country, Usuario $Usuario, $currentAwardsPlan, $newAward, int $newProgramState)
    {

        //Verificando existencia del programa de referidos para el PaisMandante
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
        $AuditoriaGeneral = new AuditoriaGeneral();
        $PaisMandante = new PaisMandante('', (string)$partner, $country);

        try {
            $PaisMandante->progReferidosDisponible();
        } catch (Exception $e) {
            if ($e->getCode() != 4019) throw $e;

            if (empty((array) $currentAwardsPlan)) {
                //Si no existe programa de referidos para el PaisMandate se crea el programa
                $progRefTransaction = new Transaction();
                $currentAwardsPlan = $this->createProgramaReferidos($progRefTransaction, $partner, $country, $Usuario);
                if ($currentAwardsPlan) $progRefTransaction->commit();
            }
        }


        //Creando ID del nuevo premio
        $Clasificador = new Clasificador('', 'AWARDIDREFERS');

        $newAwardIdMD = new MandanteDetalle();
        $newAwardIdMD->estado = 'A';
        $newAwardIdMD->usucreaId = $Usuario->getUsuarioId();
        $newAwardIdMD->usumodifId = $Usuario->getUsuarioId();
        $newAwardIdMD->mandante = $partner;
        $newAwardIdMD->paisId = $country;
        $newAwardIdMD->tipo = $Clasificador->getClasificadorId();
        $newAwardIdMD->valor = implode(',', $newAward->OfertedBonuses);

        $awardId = $MandanteDetalleMySqlDAO->insert($newAwardIdMD);
        $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, '', $newAwardIdMD->valor, $newAwardIdMD->tipo);


        //Creando nombre del nuevo premio
        $Clasificador = new Clasificador('', 'AWARDNAMEREFERS');

        $newAwarNameMD = new MandanteDetalle();
        $newAwarNameMD->estado = 'A';
        $newAwarNameMD->usucreaId = $Usuario->getUsuarioId();
        $newAwarNameMD->usumodifId = $Usuario->getUsuarioId();
        $newAwarNameMD->mandante = $partner;
        $newAwarNameMD->paisId = $country;
        $newAwarNameMD->tipo = $Clasificador->getClasificadorId();
        $newAwarNameMD->valor = $newAward->AwardName;

        $awardName = $MandanteDetalleMySqlDAO->insert($newAwarNameMD);
        $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, '', $newAwarNameMD->valor, $newAwarNameMD->tipo);

        //Validando condiciones del premio
        if ($newProgramState) {
            //Validando que al menos una de las condiciones tenga un valor superior a cero (O sea que va a ser evaluada por el CRON)
            $activeCondition = array_reduce($newAward->Conditions, function ($carry, $condition) {
                if ($condition->Value > 0) return $carry += 1;
                else return $carry;
            }, 0);
            if ($newAward->IsVerified) $activeCondition += 1;
        }


        //Creando condiciones del premio
        $conditionIds = [];
        foreach ($newAward->Conditions as $condition) {
            $Clasificador = new Clasificador('', $condition->Condition);

            $newConditionMD = new MandanteDetalle();
            $newConditionMD->estado = 'A';
            $newConditionMD->usucreaId = $Usuario->getUsuarioId();
            $newConditionMD->usumodifId = $Usuario->getUsuarioId();
            $newConditionMD->mandante = $partner;
            $newConditionMD->paisId = $country;
            $newConditionMD->tipo = $Clasificador->getClasificadorId();
            $newConditionMD->valor = $condition->Value;

            $conditionId = $MandanteDetalleMySqlDAO->insert($newConditionMD);
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, '', $newConditionMD->valor, $newConditionMD->tipo);

            array_push($conditionIds, (int)$conditionId);
        }

        if ($newAward->IsVerified) {
            $Clasificador = new Clasificador('', 'CONDVERIFIEDREFERRED');

            $newConditionMD = new MandanteDetalle();
            $newConditionMD->estado = 'A';
            $newConditionMD->usucreaId = $Usuario->getUsuarioId();
            $newConditionMD->usumodifId = $Usuario->getUsuarioId();
            $newConditionMD->mandante = $partner;
            $newConditionMD->paisId = $country;
            $newConditionMD->tipo = $Clasificador->getClasificadorId();
            $newConditionMD->valor = $newAward->IsVerified;

            $conditionId = $MandanteDetalleMySqlDAO->insert($newConditionMD);
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, '', $newConditionMD->valor, $newConditionMD->tipo);

            array_push($conditionIds, (int)$conditionId);
        }

        //Creando Json correspondiente al premio
        $newAwardJson = (object) [];
        $newAwardJson->name = (int)$awardName;
        $newAwardJson->conditions =  $conditionIds;
        $newAwardJson->award = (int)$awardId;
        $newAwardJson->tp = (int) $newAward->Type;

        //Modificando Json de AwardsPlan
        array_push($currentAwardsPlan->awardsplanreferred, $newAwardJson);

        //Inactivando y generando nuevo registro en MandanteDetalle
        // $awardsPlanMD->setEstado('I');
        // $awardsPlanMD->setUsumodifId($Usuario->usuarioId);
        // $MandanteDetalleMySqlDAO->update($awardsPlanMD);

        // $Clasificador = new Clasificador('', 'AWARDSPLANREFERRED');
        // $newAwardsPlanMD = new MandanteDetalle();
        // $newAwardsPlanMD->estado = 'A';
        // $newAwardsPlanMD->usucreaId = $Usuario->getUsuarioId();
        // $newAwardsPlanMD->usumodifId = $Usuario->getUsuarioId();
        // $newAwardsPlanMD->mandante = $partner;
        // $newAwardsPlanMD->paisId = $country;
        // $newAwardsPlanMD->tipo = $Clasificador->getClasificadorId();
        // $newAwardsPlanMD->valor = $awardsPlanJson;

        // $MandanteDetalleMySqlDAO->insert($newAwardsPlanMD);
        // $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $oldAwardsPlanJson, $awardsPlanJson, $newAwardsPlanMD->tipo);

        return $currentAwardsPlan;
    }


    /**
     * Actualiza los premios referidos en el plan de premios actual.
     *
     * @param Transaction $Transaction La transacción actual.
     * @param mixed $partner El socio asociado.
     * @param mixed $country El país asociado.
     * @param Usuario $Usuario El usuario que realiza la actualización.
     * @param array $oldReferredAwards Los premios referidos antiguos.
     * @param mixed $currentAwardsPlan El plan de premios actual.
     * @param mixed $updatedAward El premio actualizado.
     * @param int $newProgramState El nuevo estado del programa.
     * @return mixed El plan de premios actualizado.
     * @throws Exception Si ocurre un error durante la actualización.
     */
    public function updatePremioReferidos(Transaction $Transaction, $partner, $country, Usuario $Usuario, $oldReferredAwards, $currentAwardsPlan, $updatedAward, int $newProgramState)
    {
        //Verificando existencia del programa de referidos para el PaisMandante
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
        $MandanteDetalle = new MandanteDetalle();
        $AuditoriaGeneral = new AuditoriaGeneral();
        $PaisMandante = new PaisMandante('', (string)$partner, $country);
        $activeCondition = 0;

        try {
            $PaisMandante->progReferidosDisponible();
        } catch (Exception $e) {
            if ($e->getCode() != 4019) throw $e;
        }

        //Seleccionando versión anterior del premio bajo análisis
        $analizedAward = array_filter($oldReferredAwards, function ($oldReferredAward) use ($updatedAward) {
            if ($updatedAward->AwardId == $oldReferredAward->AwardId) return $oldReferredAward;
        });
        $analizedAward = (object)(array_values($analizedAward)[0]);
        if (empty($analizedAward)) return $currentAwardsPlan;

        /*Verificando y almacenando cambios*/
        //Evaluando cambios en el nombre del premio
        if ($analizedAward->AwardName != $updatedAward->AwardName) {


            //Cambiar estado última inserción
            $Clasificador = new Clasificador('', 'AWARDNAMEREFERS');

            try {
                $MandanteDetalle = new MandanteDetalle($analizedAward->AwardJson->name);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setEstado('I');
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                throw new Exception('', 34);
            } catch (Exception $e) {
                if ($e->getCode() != 34) throw $e;
                //Inserción nuevo valor
                $NewMandanteDetalle = new MandanteDetalle();
                $NewMandanteDetalle->setEstado('A');
                $NewMandanteDetalle->setMandante($partner);
                $NewMandanteDetalle->setPaisId($country);
                $NewMandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $NewMandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $NewMandanteDetalle->setTipo($MandanteDetalle->getTipo());
                $NewMandanteDetalle->setValor($updatedAward->AwardName);
                $manddetalleId = $MandanteDetalleMySqlDAO->insert($NewMandanteDetalle);

                //Actualización json base
                $analizedAward->AwardJson->name = (int)$manddetalleId;

                //Registro de auditoria
                $valorAntes = $MandanteDetalle == null || $MandanteDetalle == '' ?? $MandanteDetalle->getValor();
                $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $valorAntes, $NewMandanteDetalle->getValor(), $NewMandanteDetalle->getTipo());
            }
        }


        //Evaluando cambios en la condición IsVerified
        if ($analizedAward->IsVerified != $updatedAward->IsVerified) {

            $Clasificador = new Clasificador('', 'CONDVERIFIEDREFERRED');

            try {
                if (!$updatedAward->IsVerified) {
                    //Identificando el MandanteDetalle correspondiente
                    $possibleIds = implode(',', $analizedAward->AwardJson->conditions);
                    $rules = [];
                    array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
                    array_push($rules, ['field' => 'mandante_detalle.tipo', 'data' => $Clasificador->getClasificadorId(), 'op' => 'eq']);
                    array_push($rules, ['field' => 'mandante_detalle.manddetalle_id', 'data' => $possibleIds, 'op' => 'in']);
                    $select = 'mandante_detalle.manddetalle_id';
                    $filters = ['rules' => $rules, 'groupOp' => 'AND'];
                    $detail = $MandanteDetalle->getMandanteDetallesCustom($select, 'mandante_detalle.manddetalle_id', 'ASC', 0, 1, json_encode($filters), true);
                    $detail = json_decode($detail)->data[0];

                    //Cambiar estado última inserción
                    $MandanteDetalle = new MandanteDetalle($detail->{'mandante_detalle.manddetalle_id'});
                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                }
                throw new Exception('', 34);
            } catch (Exception $e) {
                if ($e->getCode() != 34) throw $e;

                if ($updatedAward->IsVerified) {
                    //Inserción nuevo valor
                    $NewMandanteDetalle = new MandanteDetalle();
                    $NewMandanteDetalle->setEstado('A');
                    $NewMandanteDetalle->setMandante($partner);
                    $NewMandanteDetalle->setPaisId($country);
                    $NewMandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $NewMandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $NewMandanteDetalle->setTipo($Clasificador->getClasificadorId());
                    $NewMandanteDetalle->setValor($updatedAward->IsVerified);
                    $manddetalleId = $MandanteDetalleMySqlDAO->insert($NewMandanteDetalle);

                    //Marcando condición agregada para el premio
                    $activeCondition += 1;
                }

                //Actualizando json base
                if ($updatedAward->IsVerified) {
                    array_push($analizedAward->AwardJson->conditions, (int)$manddetalleId);
                } else {
                    $analizedAward->AwardJson->conditions = array_filter($analizedAward->AwardJson->conditions, function ($condition) use ($MandanteDetalle) {
                        if ($condition != $MandanteDetalle->getManddetalleId()) return (int)$condition;
                    });
                }
                $analizedAward->AwardJson->conditions = array_values($analizedAward->AwardJson->conditions);

                //Registro de auditoria
                $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $analizedAward->IsVerified, $updatedAward->IsVerified, $Clasificador->getClasificadorId());
            }
        }


        //Evaluando cambios en las condiciones Conditions
        $countNewReferredAwardConditions = count($updatedAward->Conditions);
        for ($i = 0; $i < $countNewReferredAwardConditions; $i += 1) {
            if ($analizedAward->Conditions[$i]->Value == $updatedAward->Conditions[$i]->Value) continue;
            else {
                $Clasificador = new Clasificador('', $updatedAward->Conditions[$i]->Condition);

                try {
                    if ($updatedAward->Conditions[$i]->Value >= 0) {
                        //Identificando el MandanteDetalle correspondiente
                        $possibleIds = implode(',', $analizedAward->AwardJson->conditions);
                        $possibleIds = !empty($possibleIds) ? $possibleIds : 0;

                        $rules = [];
                        array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
                        array_push($rules, ['field' => 'mandante_detalle.tipo', 'data' => $Clasificador->getClasificadorId(), 'op' => 'eq']);
                        array_push($rules, ['field' => 'mandante_detalle.manddetalle_id', 'data' => $possibleIds, 'op' => 'in']);
                        $select = 'mandante_detalle.manddetalle_id';
                        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
                        $detail = $MandanteDetalle->getMandanteDetallesCustom($select, 'mandante_detalle.manddetalle_id', 'ASC', 0, 1, json_encode($filters), true);
                        $detail = json_decode($detail)->data[0];

                        //Cambiar estado última inserción
                        $MandanteDetalle = new MandanteDetalle($detail->{'mandante_detalle.manddetalle_id'});
                        $MandanteDetalle->setEstado('I');
                        $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                        $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                    }
                    throw new Exception('', 34);
                } catch (Exception $e) {
                    if ($e->getCode() != 34) throw $e;
                    $afterValue = 0;
                    if ($updatedAward->Conditions[$i]->Value > 0) {
                        //Inserción nuevo valor
                        $NewMandanteDetalle = new MandanteDetalle();
                        $NewMandanteDetalle->setEstado('A');
                        $NewMandanteDetalle->setMandante($partner);
                        $NewMandanteDetalle->setPaisId($country);
                        $NewMandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                        $NewMandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                        $NewMandanteDetalle->setTipo($Clasificador->getClasificadorId());
                        $NewMandanteDetalle->setValor($updatedAward->Conditions[$i]->Value);
                        $manddetalleId = $MandanteDetalleMySqlDAO->insert($NewMandanteDetalle);

                        $afterValue = $updatedAward->Conditions[$i]->Value;
                    }

                    //Actualizando json base
                    if ($updatedAward->Conditions[$i]->Value >= 0) {
                        //Removiendo condición anterior -- Si la condición es 0 se remueve del plan y no se agrega una nueva
                        $analizedAward->AwardJson->conditions = array_filter($analizedAward->AwardJson->conditions, function ($condition) use ($MandanteDetalle) {
                            if ($condition != $MandanteDetalle->getManddetalleId()) return (int)$condition;
                        });

                        //Insertando nueva condición para el caso en que el clasificador vaya a seguir utilizándose (La condición sigue vigente sólo cambió su valor a un x número mayor a cero)
                        if ($updatedAward->Conditions[$i]->Value > 0) {
                            array_push($analizedAward->AwardJson->conditions, (int)$manddetalleId);
                            $activeCondition += 1;
                        }
                    }
                    $analizedAward->AwardJson->conditions = array_values($analizedAward->AwardJson->conditions);

                    //Registro de auditoria
                    $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $analizedAward->Conditions[$i]->Value, $afterValue, $Clasificador->getClasificadorId());
                }
            }
        }


        //Evaluando cambios en los bonos vinculados al premio
        sort($analizedAward->OfertedBonuses);
        sort($updatedAward->OfertedBonuses);
        $sameArray = array_reduce($analizedAward->OfertedBonuses, function ($carry, $oldBonusId) use ($updatedAward) {
            if ($carry) return in_array($oldBonusId, $updatedAward->OfertedBonuses) ? true : false;
        }, true);

        if (!$sameArray || count($analizedAward->OfertedBonuses) != count($updatedAward->OfertedBonuses)) {

            //Actualizando bonos ofertados --Este no se desactiva, sólo se actualiza pues es el ID del premio !!
            $MandanteDetalle = new MandanteDetalle($analizedAward->AwardJson->award);
            $MandanteDetalle->valor = implode(',', $updatedAward->OfertedBonuses);
            $MandanteDetalleMySqlDAO->update($MandanteDetalle);

            //Actualziación json base
            $analizedAward->AwardJson->award = (int)$MandanteDetalle->getManddetalleId();

            //Registro de auditoria
            $valorAntes = implode(',', $analizedAward->OfertedBonuses);
            $valorDespues = implode(',', $updatedAward->OfertedBonuses);
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $valorAntes, $valorDespues, $MandanteDetalle->getTipo());
        }


        //Actualizando el plan de premios actual (currentAwardsPlan)
        $finalAwardsPlan = [];
        array_filter($currentAwardsPlan->awardsplanreferred, function ($award) use($analizedAward, &$finalAwardsPlan) {
            if ($award->award == $analizedAward->AwardJson->award) array_push($finalAwardsPlan, $analizedAward->AwardJson);
            else array_push($finalAwardsPlan, $award);
        });
        $currentAwardsPlan->awardsplanreferred = array_values($finalAwardsPlan);

        return $currentAwardsPlan;
    }


    /**
     * Elimina premios referidos del plan de premios actual.
     *
     * @param Transaction $Transaction La transacción actual.
     * @param mixed $partner El socio asociado.
     * @param mixed $country El país asociado.
     * @param Usuario $Usuario El usuario que realiza la operación.
     * @param array $NewReferredAwards Los nuevos premios referidos.
     * @param mixed $currentAwardsPlan El plan de premios actual.
     * @param array $oldReferredAwards Los premios referidos antiguos.
     * @param int $newProgramState El nuevo estado del programa.
     *
     * @return mixed El plan de premios actualizado.
     *
     * @throws Exception Si ocurre un error durante la verificación de la disponibilidad del programa de referidos.
     */
    public function deletePremiosReferidos(Transaction $Transaction, $partner, $country, Usuario $Usuario, $NewReferredAwards, $currentAwardsPlan, $oldReferredAwards, int $newProgramState)
    {
        //Verificando existencia del programa de referidos para el PaisMandante
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
        $MandanteDetalle = new MandanteDetalle();
        $AuditoriaGeneral = new AuditoriaGeneral();
        $PaisMandante = new PaisMandante('', (string)$partner, $country);

        try {
            $PaisMandante->progReferidosDisponible();
        } catch (Exception $e) {
            if ($e->getCode() != 4019) throw $e;
        }


        //Recolectando Ids de todos los premios antigüos
        $ids = array_map(function ($award) {
            if (!$award->IsNew) return $award->AwardId;
        }, $NewReferredAwards);
        $ids = array_filter($ids);
        $ids = array_values($ids);


        //Recolectando Ids de los premios eliminados
        $deletedIds = array_map(function ($oldAward) use($ids) {
            if (!in_array($oldAward->AwardId , $ids)) return $oldAward->AwardId;
        }, $oldReferredAwards);
        $deletedIds = array_filter($deletedIds);
        $deletedIds = array_values($deletedIds);

        //Removiendo premios del plan de premios
        $deactivationAwards = [];
        $currentAwardsPlan->awardsplanreferred = array_map(function ($award) use($deletedIds, &$deactivationAwards) {
            if (!in_array($award->award, $deletedIds)) return $award;
            else array_push($deactivationAwards, $award);
        }, $currentAwardsPlan->awardsplanreferred);
        $currentAwardsPlan->awardsplanreferred = array_filter($currentAwardsPlan->awardsplanreferred);
        $currentAwardsPlan->awardsplanreferred = array_values($currentAwardsPlan->awardsplanreferred);

        //Inactivando data de los premios eliminados -- El ID NO se desactiva
        foreach ($deactivationAwards as $deactivatedAward) {
            $MandanteDetalle = new MandanteDetalle($deactivatedAward->name);
            $MandanteDetalle->setEstado('I');
            $MandanteDetalle->setUsumodifId($Usuario->getUsuarioId());
            $MandanteDetalleMySqlDAO->update($MandanteDetalle);

            foreach ($deactivatedAward->conditions as $condition) {
                $MandanteDetalle = new MandanteDetalle($condition);
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($Usuario->getUsuarioId());
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        }

        return $currentAwardsPlan;
    }


}
?>