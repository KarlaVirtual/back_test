<?php

namespace Backend\dto;

use Backend\dto\PaisMandante;
use Backend\mysql\LogroReferidoMySqlDAO;
use Backend\dto\Usuario;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\BonoInterno;
use Backend\sql\Transaction;

class LogroReferido
{
    /** @var int Representación de columna 'logroreferido_id' en la tabla 'logro_referido' */
    var $logroreferidoId;

    /** @var int Representación de columna 'usuid_referido' en la tabla 'logro_referido' */
    var $usuidReferido;

    /** @var int Representación de columna 'usuid_referente' en la tabla 'logro_referido' */
    var $usuidReferente;

    /** @var int Representación de columna 'usuid_ganador' en la tabla 'logro_referido' */
    var $usuidGanador;

    /** @var string Representación de columna 'tipo_condicion' en la tabla 'logro_referido' */
    var $tipoCondicion;

    /** @var string Representación de columna 'valor_condicion' en la tabla 'logro_referido' */
    var $valorCondicion;

    /** @var string Representación de columna 'tipo_premio' en la tabla 'logro_referido' */
    var $tipoPremio;

    /** @var string Representación de columna 'valor_premio' en la tabla 'logro_referido' */
    var $valorPremio;

    /** @var string Representación de columna 'fecha_uso' en la tabla 'logro_referido' */
    var $fechaUso;

    /** @var string Representación de columna 'estado' en la tabla 'logro_referido' */
    var $estado;

    /** @var string Representación de columna 'estado_grupal' en la tabla 'logro_referido' */
    var $estadoGrupal;

    /** @var int Representación de columna 'usucrea_id' en la tabla 'logro_referido' */
    var $usucreaId;

    /** @var int Representación de columna 'usumodif_id' en la tabla 'logro_referido' */
    var $usumodifId;

    /** @var string Representación de columna 'fecha_crea' en la tabla 'logro_referido' */
    var $fechaCrea;

    /** @var string Representación de columna 'fecha_modif' en la tabla 'logro_referido' */
    var $fechaModif;

    /** @var string Representación de columna 'fecha_expira' en la tabla 'logro_referido' */
    var $fechaExpira;

    /** @var string Representación de columna 'fecha_expira_premio' en la tabla 'logro_referido' */
    var $fechaExpiraPremio;

    /**
     * Constructor de la clase LogroReferido.
     *
     * @param string $logroReferidoId El ID del logro referido. Si se proporciona, se cargará el logro referido desde la base de datos.
     * @throws Exception Si no existe el logro referido con el ID proporcionado.
     */
    public function __construct($logroReferidoId = "")
    {
        if ($logroReferidoId != null && $logroReferidoId != "") {
            $LogroReferidoMySqlDAO = new LogroReferidoMySqlDAO();
            $LogroReferido = $LogroReferidoMySqlDAO->load($logroReferidoId);

            if ($LogroReferido == null || $LogroReferido == "") throw new Exception('No existe ' . get_class($this), 4011);

            /** Toda propiedad que se agregue a la función readRow de MySqlDAO y se defina en el dto, es incializada por el foreach */
            foreach ($LogroReferido as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
        }
    }


    /**
     * Obtiene el valor de la propiedad logroreferidoId para el objeto LogroReferido.
     * @return mixed
     */
    public function getLogroreferidoId()
    {
        return $this->logroreferidoId;
    }

    /**
     * Establece el valor de la propiedad logroreferidoId para el objeto LogroReferido.
     * @param mixed $logroreferidoId
     */
    public function setLogroreferidoId($logroreferidoId): void
    {
        $this->logroreferidoId = $logroreferidoId;
    }

    /**
     * Obtiene el valor de la propiedad usuidReferido para el objeto LogroReferido.
     * @return mixed
     */
    public function getUsuidReferido()
    {
        return $this->usuidReferido;
    }

    /**
     * Establece el valor de la propiedad usuidReferido para el objeto LogroReferido.
     * @param mixed $usuidReferido
     */
    public function setUsuidReferido($usuidReferido): void
    {
        $this->usuidReferido = $usuidReferido;
    }

    /**
     * Obtiene el valor de la propiedad usuidReferente para el objeto LogroReferido.
     * @return mixed
     */
    public function getUsuidReferente()
    {
        return $this->usuidReferente;
    }

    /**
     * Establece el valor de la propiedad usuidReferente para el objeto LogroReferido.
     * @param mixed $usuidReferente
     */
    public function setUsuidReferente($usuidReferente): void
    {
        $this->usuidReferente = $usuidReferente;
    }

    /**
     * Obtiene el valor de la propiedad usuidGanador para el objeto LogroReferido.
     * @return mixed
     */
    public function getUsuidGanador()
    {
        return $this->usuidGanador;
    }

    /**
     * Establece el valor de la propiedad usuidGanador para el objeto LogroReferido.
     * @param mixed $usuidGanador
     */
    public function setUsuidGanador($usuidGanador): void
    {
        $this->usuidGanador = $usuidGanador;
    }

    /**
     * Obtiene el valor de la propiedad tipoCondicion para el objeto LogroReferido.
     * @return mixed
     */
    public function getTipoCondicion()
    {
        return $this->tipoCondicion;
    }

    /**
     * Establece el valor de la propiedad tipoCondicion para el objeto LogroReferido.
     * @param mixed $tipoCondicion
     */
    public function setTipoCondicion($tipoCondicion): void
    {
        $this->tipoCondicion = $tipoCondicion;
    }

    /**
     * Obtiene el valor de la propiedad valorCondicion para el objeto LogroReferido.
     * @return mixed
     */
    public function getValorCondicion()
    {
        return $this->valorCondicion;
    }

    /**
     * Establece el valor de la propiedad valorCondicion para el objeto LogroReferido.
     * @param mixed $valorCondicion
     */
    public function setValorCondicion($valorCondicion): void
    {
        $this->valorCondicion = $valorCondicion;
    }

    /**
     * Obtiene el valor de la propiedad tipoPremio para el objeto LogroReferido.
     * @return mixed
     */
    public function getTipoPremio()
    {
        return $this->tipoPremio;
    }

    /**
     *  Establece el valor de la propiedad tipoPremio para el objeto LogroReferido.
     * @param mixed $tipoPremio
     */
    public function setTipoPremio($tipoPremio): void
    {
        $this->tipoPremio = $tipoPremio;
    }

    /**
     * Obtiene el valor de la propiedad valorPremio para el objeto LogroReferido.
     * @return mixed
     */
    public function getValorPremio()
    {
        return $this->valorPremio;
    }

    /**
     * Establece el valor de la propiedad valorPremio para el objeto LogroReferido.
     * @param mixed $valorPremio
     */
    public function setValorPremio($valorPremio): void
    {
        $this->valorPremio = $valorPremio;
    }

    /**
     * Obtiene el valor de la propiedad fechaUso para el objeto LogroReferido.
     * @return mixed
     */
    public function getFechaUso()
    {
        return $this->fechaUso;
    }

    /**
     * Establece el valor de la propiedad fechaUso para el objeto LogroReferido.
     * @param mixed $fechaUso
     */
    public function setFechaUso($fechaUso): void
    {
        $this->fechaUso = $fechaUso;
    }

    /**
     * Obtiene el valor de la propiedad estado para el objeto LogroReferido.
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }


    /**
     * Establece el valor de la propiedad estado para el objeto LogroReferido.
     * @param mixed $estado
     */
    public function setEstado($estado): void
    {
        $estado = strtoupper($estado);

        $estadosAceptados = [
            'P', //Pendiente
            'C', //Cumplido
            'F', //Fallido
            'R', //Redimido
            'CE', //Condición expirada
        ];

        if (in_array($estado, $estadosAceptados)) $this->estado = $estado;
    }

    /**
     *  Obtiene el valor de la propiedad estadoGrupal para el objeto LogroReferido.
     * @return mixed
     */
    public function getEstadoGrupal()
    {
        return $this->estadoGrupal;
    }

    /**
     * Establece el valor de la propiedad estadoGrupal para el objeto LogroReferido.
     * @param mixed $estadoGrupal
     */
    public function setEstadoGrupal($estadoGrupal): void
    {
        $estadoGrupal = strtoupper($estadoGrupal);

        $estadosAceptados = [
            'P', //Pendiente
            'C', //Cumplido
            'F', //Fallido
            'R', //Redimido - Causado por el referente
            'PE', //Premio expirado - Causado por el referente
            'CA' //Premio cancelado
        ];

        if (in_array($estadoGrupal, $estadosAceptados)) $this->estadoGrupal = $estadoGrupal;
    }


    /**
     * Obtiene el valor de la propiedad usucreaId para el objeto LogroReferido.
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el valor de la propiedad usucreaId para el objeto LogroReferido.
     * @param mixed $usucreaId
     */
    public function setUsucreaId($usucreaId): void
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el valor de la propiedad usumodifId para el objeto LogroReferido.
     * @return mixed
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el valor de la propiedad usumodifId para el objeto LogroReferido.
     * @param mixed $usumodifId
     */
    public function setUsumodifId($usumodifId): void
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el valor de la propiedad fechaCrea para el objeto LogroReferido.
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece el valor de la propiedad fechaCrea para el objeto LogroReferido.
     * @param mixed $fechaCrea
     */
    public function setFechaCrea($fechaCrea): void
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene el valor de la propiedad fechaModif para el objeto LogroReferido.
     * @return mixed
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece el valor de la propiedad fechaModif para el objeto LogroReferido.
     * @param mixed $fechaModif
     */
    public function setFechaModif($fechaModif): void
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene el valor de la propiedad fechaExpira para el objeto LogroReferido.
     * @return mixed
     */
    public function getFechaExpira()
    {
        return $this->fechaExpira;
    }

    /**
     * Establece el valor de la propiedad fechaExpira para el objeto LogroReferido.
     * @param mixed $fechaExpira
     * 
     */
    public function setFechaExpira($fechaExpira)
    {
        $this->fechaExpira = $fechaExpira;
    }

    /**
     * Obtiene el valor de la propiedad fechaExpiraPremio para el objeto LogroReferido.
     * @return mixed
     */
    public function getFechaExpiraPremio()
    {
        return $this->fechaExpiraPremio;
    }

    /**
     * Establece el valor de la propiedad fechaExpiraPremio para el objeto LogroReferido.
     * @param mixed $fechaExpiraPremio
     */
    public function setFechaExpiraPremio($fechaExpiraPremio)
    {
        $this->fechaExpiraPremio = $fechaExpiraPremio;
    }


    /** Retorna el total de días de disponibilidad de un premio antes de expirar y dejar de estar disponible
     *@param int $mandante Mandante bajo el cual se consulta el detalle
     * @param int $paisId PaisId bajo el cual se consulta el detalle
     * @throws \Exception
     * @return int Retorna un valor entero positivo o -1 si no hay un límite configurado o si el valor configurado es <= 0
     */
    public function getDiasExpiracionPremio(int $mandante, int $paisId)  {
        try {
            $Clasificador = new Clasificador('', 'LIMITDAYSAWARDCHOICINGREFERENT');
            $MandanteDetalle = new MandanteDetalle('', (string) $mandante, $Clasificador->getClasificadorId(), $paisId, 'A');
            $limitDaysAwardChoicing = $MandanteDetalle->getValor();
        }
        catch (Exception $e) {
            if ($e->getCode() != 34) throw $e;
        }
        finally {
            if (empty($limitDaysAwardChoicing) || $limitDaysAwardChoicing < 0) $limitDaysAwardChoicing = -1;
        }

        return $limitDaysAwardChoicing;
    }

    /**
     *Retorna el total de días de disponibilidad de una condición para ser completada por el referido
     * @param int $mandante Mandante bajo el cual se consulta el detalle
     * @param int $paisId PaisId bajo el cual se consulta el detalle
     * @throws \Exception
     * @return int Retorna un valor entero positivo o -1 si no hay un límite configurado o si el valor configurado es <= 0
     */
    public function getDiasExpiracionCondiciones(int $mandante, int $paisId)  {
        try {
            $Clasificador = new Clasificador('', 'LIMITDAYSCONDSFULFILLMENTREFERRED');
            $MandanteDetalle = new MandanteDetalle('', (string) $mandante, $Clasificador->getClasificadorId(), $paisId, 'A');
            $expirationPeriod = $MandanteDetalle->getValor();
        }
        catch (Exception $e) {
            if ($e->getCode() != 34) throw $e;
        }
        finally {
            if (empty($expirationPeriod) || $expirationPeriod < 0) $expirationPeriod = -1;
        }

        return $expirationPeriod;
    }

    /**
     *Trae logros de la tabla logro_referido agrupados por referido y tipo de premio (Un premio)
     * @param int $usuarioIdReferido UsuarioId del referido
     * @param int $tipoPremio Tipo premio por el cual agrupar
     * @param string|null $select Parámetro opcional para definir las columnas de la tabla logro_referido consultadas y retornadas mediante el array
     * @return array Array de objetos con las columnas solicitadas
     */
    public function getLogrosAgrupados(int $usuarioIdReferido, ?int $tipoPremio = null, ?string $select = null) {
        $officialSelect = $select ?? 'logro_referido.logroreferido_id';

        $rules = [];
        array_push($rules, ['field' => 'logro_referido.usuid_referido', 'data' => $usuarioIdReferido, 'op' => 'eq']);
        if (!empty($tipoPremio)) array_push($rules, ['field' => 'logro_referido.tipo_premio', 'data' => $tipoPremio, 'op' => 'eq']);
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $filters = json_encode($filters);

        $returnedData = $this->getLogroReferidoCustom($officialSelect, 'logro_referido.logroreferido_id', 'ASC', 0, 100, $filters, true);
        $returnedData = json_decode($returnedData)->data;

        if (!is_array($returnedData)) $returnedData = [];

        return $returnedData;
    }


    /** Retorna el plan de premios utilizado para la implementación de un programa de
     *referidos
     * @param PaisMandante $paisMandante El pais_mandante del programa de referidos correspondiente
     * @return array Retorna el plan de premios correspondiente
     * @throws Exception Lanza Error 4016 si no encuentra un plan de premios
     */
    public function getPlanPremiosReferidos(PaisMandante $paisMandante)
    {
        $Clasificador = new Clasificador('', 'AWARDSPLANREFERRED');
        try {
            $MandanteDetalle = new MandanteDetalle('', $paisMandante->getMandante(), $Clasificador->getClasificadorId(), $paisMandante->getPaisId(), 'A');
        }
        catch (Exception $e) {
            if ($e->getCode() != 34) throw $e;
            throw new Exception('Ocurrio un error, comuniquese con soporte', 4016);
        }

        $awardsPlan = $MandanteDetalle->getValor();
        return json_decode($awardsPlan);
    }


    /** Inserta los logros o metas que puede completar un nuevo referido
     * @param Transaction $Transaction
     * @param Usuario $referente
     * @param Usuario $referido
     * @return void
     */
    public function insertarLogrosNuevoReferido($Transaction, Usuario $referente, Usuario $referido)
    {
        /** Recuperando plan de premios del programa */
        $PaisMandante = new PaisMandante('', $referente->mandante, $referente->paisId);
        $awardsPlan = $this->getPlanPremiosReferidos($PaisMandante);

        /** Recuperando plazo máximo para cumplimiento del logro/meta*/
        $expirationPeriod = $this->getDiasExpiracionCondiciones((int) $referente->mandante, (int) $referente->paisId);

        /** Insertando logros/metas */
        foreach ($awardsPlan->awardsplanreferred as $awardPlan) {
            foreach ($awardPlan->conditions as $condition) {
                $MandanteDetalle = new MandanteDetalle($condition);
                $this->setUsuidReferido($referido->getUsuarioId());
                $this->setUsuidReferente($referente->getUsuarioId());
                $this->setTipoCondicion($MandanteDetalle->getManddetalleId());
                $this->setTipoPremio($awardPlan->award);
                $this->setEstado('P');
                $this->setEstadoGrupal('P');
                $this->setUsucreaId($referido->getUsuarioId());
                $this->setUsumodifId($referido->getUsuarioId());
                if (!is_numeric($expirationPeriod) || $expirationPeriod <= 0) $this->setFechaExpira(null);
                else $this->setFechaExpira(date('Y-m-d 23:59:59', strtotime("+ $expirationPeriod DAYS")));

                $LogroReferidoMySqlDAO = new LogroReferidoMySqlDAO($Transaction);
                $LogroReferidoMySqlDAO->insert($this);
            }
        }
    }


    /** Valida cuál es el estado de cumplimiento para las condiciones de un premio
     *perteneciente al programa de referidos
     * @param $transaction Transaccion para ejecución de la solicitud
     * @param int $premioId El id del premio a consultar
     * @param int $usuidReferido El usuarioId del referido que debe cumplir las condiciones
     * @param bool $adjuntarLogros Indicativo booleano para retornar el estado de las condiciones presente en base de datos
     * @return array $awardStatus Si premio se encuentra disponible
     * @return false Si el premio no se encuentra disponible
     * @throws Exception
     */
    public function getEstadoPremio($transaction, int $tipoPremio, int $usuidReferido, bool $adjuntarLogros = false)
    {
        $referido = new Usuario($usuidReferido);

        /** Solicitando logros/condiciones para el tipoPremio */
        $sql = "select clasificador.abreviado, logro_referido.logroreferido_id, logro_referido.tipo_condicion, logro_referido.estado, logro_referido.estado_grupal, mandante_detalle.valor, logro_referido.fecha_modif, logro_referido.fecha_uso, logro_referido.fecha_crea, logro_referido.fecha_expira, logro_referido.fecha_expira_premio, if (logro_referido.estado = 'P' AND logro_referido.fecha_expira IS NOT NULL AND logro_referido.fecha_expira < now(), true, false) as condicionExpirada, if (logro_referido.estado_grupal = 'C' AND logro_referido.fecha_expira_premio IS NOT NULL AND logro_referido.fecha_expira_premio < now(), true, false) as premioExpirado from logro_referido inner join mandante_detalle on (logro_referido.tipo_condicion = mandante_detalle.manddetalle_id) inner join clasificador on (mandante_detalle.tipo = clasificador.clasificador_id) where logro_referido.tipo_premio = " . $tipoPremio . " and usuid_referido = " . $usuidReferido;
        $BonoInterno = new BonoInterno();
        $referredAchievements = $BonoInterno->execQuery($transaction, $sql);

        /** Posibles estados de un premio de referidos */
        $globalStatePossibleValues = [
            'C' => 0, //Cumplido
            'R' => 1, //Redimido
            'PE' => 2, //Premio expirado
            'P' => 3, //Pendiente
            'CE' => 4, //Condiciones expiradas
            'F' => 5, //Premio fallido
            'CA' => 5 //Los premios cancelados se envían como fallidos a front
        ];
        $globalState = null;
        $globalStateCode = null;

        /** Verificando si el logro está pendiente, si fue exitoso o si fue fallido*/
        $targetValues = []; //Array con los valores objetivo de las condiciones
        $achievements = []; //Array con el status de las condiciones
        $dbStatus = []; //Array con el estado de las condiciones
        $redeemable = true; //Booleano indica si el premio ha sido redimido o se puede redimir
        $newAward = true; //Booleano indica si premio está disponible para ser reclamado
        $expiredAward = false; //Booleano indica si el premio ha expirado (Incluye expiración de condiciones y expiración de premio)
        $expiredConditionTime = null; //Almacena la fecha en la cual expiran las condiciones, dado el caso de que estas expiren
        $expiredAwardTime = null; //Almacena la fecha en la cual expira el premio
        $futureExpiredAwardTime = null; //Futura fecha de expiración de un premio en estado Cumplido
        foreach ($referredAchievements as $achievement) {
            //Iterando cada una de las condiciones o logros por cumplir

            //Definiendo estado del logro (0 = Cumplido o redimido, 2 = Pendiente, 1 = Fallido)
            $status = '';
            if (in_array($achievement->{'logro_referido.estado'}, ['C', 'R']) &&
                $achievement->{'logro_referido.estado_grupal'} != 'CA') $status = 0; //status 0 significa que se cumplieron las condiciones
            elseif ($achievement->{'logro_referido.estado'} == 'P') $status = 2; //status 2 significa que la condición está pendiente
            elseif ($achievement->{'logro_referido.estado'} == 'F') $status = 1; //status 1 significa que la condición está fallida
            else $status = 1;
            $achievements[$achievement->{'clasificador.abreviado'}] = $status;
            $dbStatus[$achievement->{'clasificador.abreviado'}] = $achievement->{'logro_referido.estado'};

            //Definiendo valor objetivo para la condición en iteración
            $targetValues[$achievement->{'clasificador.abreviado'}] = $achievement->{'mandante_detalle.valor'};

            //Si redeemable es true, el premio está listo para ser reclamado o ya fue reclamado
            //Si redeemable es false, hay condiciones pendientes por cumplimiento, fallidas o el premio/ condiciones expiraron
            if ($status != 0) $redeemable = false;
            if ($achievement->{'logro_referido.estado'} != 'C') $newAward = false;

            //Verificando si el premio o alguna de las condiciones expiró
            if ($achievement->{'logro_referido.estado'} == 'CE' || $achievement->{'.condicionExpirada'}) $expiredAward = true;
            elseif ($achievement->{'logro_referido.estado_grupal'} == 'PE' || $achievement->{'.premioExpirado'}) $expiredAward = true;

            //Almacenando fecha de expiración de las condiciones y del premio
            #Comprobado que una de las condiciones tiene una fecha de expiración concreta esta se almacena para el premio en su conjunto
            if ($expiredAward && empty($expiredConditionTime)) $expiredConditionTime = $achievement->{'logro_referido.fecha_expira'};
            if ($expiredAward && empty($expiredAwardTime)) $expiredAwardTime = $achievement->{'logro_referido.fecha_expira_premio'};

            //Definiendo el estado global del premio para retornar a Front
            $globalState = empty($globalState) ? $achievement->{'logro_referido.estado_grupal'} : $globalState;

            //Redefiniendo estado en caso de que las condiciones hayan expirado
            if ($achievement->{'logro_referido.estado'} == 'CE' && $globalState != 'CE') {
                $globalState = $achievement->{'logro_referido.estado'};
            }

            //Almacenando futura fecha de expiración del premio cumplido
             if ($globalState == 'C') $futureExpiredAwardTime = $achievement->{'logro_referido.fecha_expira_premio'};

            //Verificando expiración del premio o de las condiciones en el estado global
            if ($expiredConditionTime != null && $globalState != 'CE') $globalState = 'CE';
            if ($expiredAwardTime != null && $globalState != 'PE') $globalState = 'PE';

        }

        //Validando que estado global sea uno de los valores aceptados, caso contrario se setea -1
        if (!in_array($globalState, array_keys($globalStatePossibleValues))) $globalStateCode = -1;
        else $globalStateCode = $globalStatePossibleValues[$globalState];


        $awardStatus['tipoPremio'] = $tipoPremio;
        $awardStatus['logros'] = $achievements;
        $awardStatus['valoresObjetivo'] = $targetValues;
        $awardStatus['dbStatus'] = $dbStatus;
        $awardStatus['redimible'] = $redeemable;
        $awardStatus['estado'] = $globalStateCode;
        $awardStatus['nuevoPremio'] = $newAward;
        $awardStatus['premioExpirado'] = $expiredAward;
        $awardStatus['fechaExpiraCondicion'] = $expiredConditionTime;
        $awardStatus['fechaExpiraPremio'] = $expiredAwardTime ?: $futureExpiredAwardTime;
        if ($adjuntarLogros) $awardStatus['data'] = $referredAchievements;
        return $awardStatus;
    }


    /**
     * Obtiene logros referidos personalizados según los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $onlyCount Indica si solo se debe contar los registros.
     * @return array|null Resultados de la consulta de logros referidos.
     * @throws Exception Si no existen logros referidos.
     */
    public function getLogroReferidoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $onlyCount = false)
    {
        $LogroReferidoMySqlDAO = new LogroReferidoMySqlDAO();
        $logros = $LogroReferidoMySqlDAO->queryLogrosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $onlyCount);

        if ($logros != null && $logros != '') {
            return $logros;
        }
        else {
            throw new Exception('No existe ' . get_class($this), "01");
        }
    }

    /** Actualiza los estados grupales del premio de un referido y actualiza el valor condición y el estado de un
     * logro_referido específico. Método hecho específicamente para la actualización de los logros una vez ejecutado un CRON
     * @param Transaction $Transaction Transacción para ejecución de la solicitud
     * @param LogroReferido $LogroReferido LogroReferido que actualiza los logros agrupados
     * @return boolean Retorna TRUE si el premio bajo actualización ha pasado a estado cumplido 'C'
     * 
     * */
    public function actualizarLogrosAgrupados(Transaction $Transaction, LogroReferido $LogroReferido) : bool
    {
        //Solicitando total de logros por actualizar
        $rules = [];
        $select = 'logro_referido.logroreferido_id, logro_referido.tipo_premio, logro_referido.tipo_condicion, logro_referido.valor_condicion, logro_referido.estado, logro_referido.estado_grupal, usuario.mandante, usuario.pais_id';
        array_push($rules, ['field' => 'logro_referido.usuid_referido', 'data' => $LogroReferido->getUsuidReferido(), 'op' => 'eq']);
        array_push($rules, ['field' => 'logro_referido.tipo_premio', 'data' => $LogroReferido->getTipoPremio(), 'op' => 'eq']);
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $logroReferidoCustomResult = $this->getLogroReferidoCustom($select, 'logro_referido.logroreferido_id', 'ASC', 0, 50, json_encode($filters), true);
        $groupAchievements = json_decode($logroReferidoCustomResult)->data;

        //Definiendo estado grupal ¡IMPORTANTE!
        $groupStatus = array_reduce($groupAchievements, function ($carry, $achievement) use ($LogroReferido) {
            $provisionalState = '';
            if ($LogroReferido->getLogroreferidoId() == $achievement->{'logro_referido.logroreferido_id'}) {
                $provisionalState = $LogroReferido->getEstado();
            }
            elseif ($LogroReferido->getLogroreferidoId() != $achievement->{'logro_referido.logroreferido_id'}) {
                $provisionalState = $achievement->{'logro_referido.estado'};
            }

            if ($provisionalState != 'C' && $carry != 'F') {
                $carry = $provisionalState;
            }
            return $carry;
        }, 'C');

        //Solicitando fecha de expiración del premio
        $awardExpirationDate = null;
        if ($groupStatus == 'C') {
            try {
                //Obteniendo días hábiles
                $partner = $groupAchievements[0]->{'usuario.mandante'};
                $country = $groupAchievements[0]->{'usuario.pais_id'};
                $Clasificador = new Clasificador("", "LIMITDAYSAWARDCHOICINGREFERENT");
                $MandanteDetalle = new MandanteDetalle(null, $partner, $Clasificador->clasificadorId, $country, 'A');
                $totalDays = (int)$MandanteDetalle->getValor();

                //Almacenando fecha de vencimiento del premio
                $awardExpirationDate = date('Y-m-d 23:59:59', strtotime("+{$totalDays} days"));
            } catch (Exception $e) {
            }
        }

        //Actualizando Logros Referido
        $LogroReferidoMySqlDAO = new LogroReferidoMySqlDAO($Transaction);
        foreach ($groupAchievements as $achievement) {
            $newLogroReferido = new LogroReferido($achievement->{'logro_referido.logroreferido_id'});
            if ($achievement->{'logro_referido.logroreferido_id'} == $LogroReferido->getLogroreferidoId()) {
                //Estos atributos sólo se actualizan en el logro pasado como parámetro, pues se supone es el que está actualizando el CRON
                $newLogroReferido->setValorCondicion($LogroReferido->getValorCondicion());
                $newLogroReferido->setFechaUso($LogroReferido->getFechaUso());
                $newLogroReferido->setEstado($LogroReferido->getEstado());
            }
            $newLogroReferido->setEstadoGrupal($groupStatus);
            if (!empty($awardExpirationDate)) $newLogroReferido->setFechaExpiraPremio($awardExpirationDate);
            $LogroReferidoMySqlDAO->update($newLogroReferido);
        }
        return $groupStatus == 'C';
    }

    /** Cancela todas aquellas apuestas en estado pendiente vinculadas al ID del usuario del logro pasado como parámetro
     * @param Transaction $Transaction Transacción para ejecución de la solicitud
     * @param int $usuidReferido UsuarioId del referido
     * @param array $tuplasExcluidas Tuplas de logros que no serán canceladas
     * @return bool Retorna TRUE si se cancelaron los logros pendientes
    */
    public function cancelarLogrosPendientes(Transaction $Transaction, int $usuidReferido, array $tuplasExcluidas): bool{
        //Solicitando todos los logros vinculados
        $select = 'logro_referido.estado, logro_referido.estado_grupal, logro_referido.tipo_premio, logro_referido.logroreferido_id';
        $referredAchievements = $this->getLogrosAgrupados($usuidReferido, null, $select);

        //Cancelando logros pendientes ¡Importante! No cancelarán los logros agrupados con el logro pasado como parámetro,
        // pues se supone es este el que causa la cancelación del resto de logros y debe quedar como fallido
        $awardsIds = [];
        $LogroReferidoMySqlDAO = new LogroReferidoMySqlDAO($Transaction);
        foreach($referredAchievements as $achievement) {
            $awardId = $achievement->{'logro_referido.logroreferido_id'};
            $grupalState = $achievement->{'logro_referido.estado_grupal'};
            $awardType = $achievement->{'logro_referido.tipo_premio'};

            //Verificando si alguno de los premios por cancelar está excluído del proceso
            $currentTuple = $usuidReferido . '_' . $awardType;
            if (in_array($currentTuple, $tuplasExcluidas)) continue;

            //Acumulando logros que serán cancelados
            if ($grupalState == 'P') {
                $LogroReferidoToCancel = new LogroReferido($awardId);
                $LogroReferidoToCancel->setEstadoGrupal('CA');
                $LogroReferidoMySqlDAO->update($LogroReferidoToCancel);
            }
        }

        return true;
    }


    /**
     * Redime los logros referidos de un usuario.
     *
     * @param Transaction $transaction La transacción actual.
     * @param int $tipoPremio El tipo de premio a redimir.
     * @param int $usuidReferido El ID del usuario referido.
     * @param int $bonoId El ID del bono a asignar como premio.
     * @return bool Retorna true si los logros fueron redimidos exitosamente.
     * @throws Exception Si el premio no es redimible.
     */
    public function redimirLogros(Transaction $transaction, int $tipoPremio, int $usuidReferido, int $bonoId) {
        $awardStatus = $this->getEstadoPremio($transaction, $tipoPremio, $usuidReferido, true);
        if ((!$awardStatus['nuevoPremio']) || count($awardStatus['data']) == 0 || $awardStatus['premioExpirado']) throw new Exception('Premio no redimible', 4017);

        //Actualizando estado de los logros
        $referredAchievements = $awardStatus['data'];
        $LogroReferidoMySqlDAO = new LogroReferidoMySqlDAO($transaction);
        foreach ($referredAchievements as $achievement) {
            $LogroReferido = new LogroReferido($achievement->{'logro_referido.logroreferido_id'});
            $LogroReferido->setUsuidGanador($LogroReferido->getUsuidReferente());
            $LogroReferido->setValorPremio($bonoId);
            $LogroReferido->setEstado('R');
            $LogroReferido->setEstadoGrupal('R');
            $LogroReferidoMySqlDAO->update($LogroReferido);
        }

        return true;
    }
}
