<?php

namespace Backend\dto;

use Backend\mysql\JackpotDetalleMySqlDAO;
use Backend\sql\Transaction;

/**
 * Clase JackpotDetalle.
 *
 *@author David Torres <david.torres@virtualsoft.tech>
 * @package No
 *@category No
 *@version 1.0
 *@since Desconocido
 */
class JackpotDetalle
{
    /** @var mixed Representación de la columna 'jackpot_detalleid' de la tabla 'jackpot_detalle' */
    public $jackpotDetalleId;

    /** @var mixed Representación de la columna 'jackpot_id' de la tabla 'jackpot_detalle' */
    public $jackpotId;

    /** @var mixed Representación de la columna 'tipo' de la tabla 'jackpot_detalle' */
    public $tipo;

    /** @var mixed Representación de la columna 'moneda' de la tabla 'jackpot_detalle' */
    public $moneda;

    /** @var mixed Representación de la columna 'valor' de la tabla 'jackpot_detalle' */
    public $valor;

    /** @var mixed Representación de la columna 'fecha_crea' de la tabla 'jackpot_detalle' */
    public $fechaCrea;

    /** @var mixed Representación de la columna 'fecha_modif' de la tabla 'jackpot_detalle' */
    public $fechaModif;

    /** @var mixed Representación de la columna 'usucrea_id' de la tabla 'jackpot_detalle' */
    public $usucreaId;

    /** @var mixed Representación de la columna 'usumodif_id' de la tabla 'jackpot_detalle' */
    public $usumodifId;


    /**
     * Constructor de la clase JackpotDetalle.
     *
     * @param int|null $jackpotDetalleId El ID del detalle del jackpot (opcional).
     *
     * @throws Exception Si no existe un detalle de jackpot con el ID proporcionado.
     *
     * Este constructor inicializa un objeto JackpotDetalle. Si se proporciona un ID de detalle de jackpot,
     * intenta cargar los datos correspondientes desde la base de datos utilizando la clase jackpotDetalleMySqlDAO.
     * Si no se encuentra un detalle de jackpot con el ID proporcionado, lanza una excepción.
     * Si se encuentran datos, inicializa las propiedades del objeto con los valores obtenidos.
     */
    public function __construct($jackpotDetalleId = null) {
        $JackpotDetalle = null;

         if (!empty($jackpotDetalleId)) {
             $JackpotDetalleMySqlDAO = new jackpotDetalleMySqlDAO();
             $JackpotDetalle = $JackpotDetalleMySqlDAO->load($jackpotDetalleId);

             if (empty($JackpotDetalle)) Throw new Exception('No existe' . get_class($this), 300039);
         }

        if (!empty($JackpotDetalle)) {
            /** Toda propiedad que se agregue a la función readRow de MySqlDAO y se defina en el dto, es incializada por el foreach*/
            foreach ($JackpotDetalle as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
        }
    }

    /**
     * Obtiene el jackpot_detalleId del objeto JackpotDetalle.
     * @return mixed
     */
    public function getJackpotDetalleId()
    {
        return $this->jackpotDetalleId;
    }

    /**
     * Establece el jackpot_detalleId del objeto JackpotDetalle.
     * @param mixed $jackpotDetalleId
     */
    public function setJackpotDetalleId($jackpotDetalleId): void
    {
        $this->jackpotDetalleId = $jackpotDetalleId;
    }

    /**
     * Obtiene el jackpotId del objeto JackpotDetalle.
     * @return mixed
     */
    public function getJackpotId()
    {
        return $this->jackpotId;
    }

    /**
     * Establece el jackpotId del objeto JackpotDetalle.
     * @param mixed $jackpotId
     */
    public function setJackpotId($jackpotId): void
    {
        $this->jackpotId = $jackpotId;
    }

    /**
     * Obtiene el tipo del objeto JackpotDetalle.
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo del objeto JackpotDetalle.
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene la moneda del objeto JackpotDetalle.
     * @return mixed
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Establece la moneda del objeto JackpotDetalle.
     * @param mixed $moneda
     */
    public function setMoneda($moneda): void
    {
        $this->moneda = $moneda;
    }

    /**
     * Obtiene el valor del objeto JackpotDetalle.
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor del objeto JackpotDetalle.
     * @param mixed $valor
     */
    public function setValor($valor): void
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene la fechaCrea del objeto JackpotDetalle.
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece la fechaCrea del objeto JackpotDetalle.
     * @param mixed $fechaCrea
     */
    public function setFechaCrea($fechaCrea): void
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene la fechaModif del objeto JackpotDetalle.
     * @return mixed
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece la fechaModif del objeto JackpotDetalle.
     * @param mixed $fechaModif
     */
    public function setFechaModif($fechaModif): void
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene el usucreaId del objeto JackpotDetalle.
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el usucreaId del objeto JackpotDetalle.
     * @param mixed $usucreaId
     */
    public function setUsucreaId($usucreaId): void
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el usumodifId del objeto JackpotDetalle.
     * @return mixed
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * 
     * Establece el usumodifId del objeto JackpotDetalle.
     * @param mixed $usumodifId
     */
    public function setUsumodifId($usumodifId): void
    {
        $this->usumodifId = $usumodifId;
    }


    /**
     * Obtiene detalles personalizados del Jackpot.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param mixed $start Inicio del límite de la consulta.
     * @param mixed $limit Límite de registros a obtener.
     * @param string $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $onlyCount Indica si solo se debe contar los registros (opcional, por defecto false).
     * @param array $joins Joins adicionales a incluir en la consulta (opcional).
     * @return mixed Resultado de la consulta personalizada.
     */
    public function getJackpotDetalleCustom(string $select, string $sidx, string $sord,mixed $start,mixed $limit,string $filters,bool $searchOn,bool $onlyCount = false, array $joins = [])
    {
        $JackpotDetalleMySqlDAO = new jackpotDetalleMySqlDAO();

        $respuestaCustom = $JackpotDetalleMySqlDAO->queryJackpotDetalleCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $onlyCount, $joins);

        return $respuestaCustom;
    }


    /**
     * Obtiene los tipos generales heredables para el Jackpot.
     *
     * Este método devuelve un array asociativo donde las claves son los tipos de condiciones
     * y los valores son los métodos correspondientes para clonar o calcular dichas condiciones.
     *
     * @return array Un array asociativo de tipos generales heredables y sus métodos correspondientes.
     */
    public function getTiposGeneralesHeredablesJackpot () : array
    {
        $inheritableTypes = [
            'CONDPAISUSER' => 'clonarCondicionStandard',
            'TOTALSERIES' => 'clonarCondicionStandard',
            'CURRENTSERIE' => 'clonarCurrentSerie',
            'SHOWCURRENCYSIGN' => 'clonarCondicionStandard',
            'COUNTERSTYLE' => 'clonarCondicionStandard',
            'JACKPOTINITVALUE_CASINO' => 'clonarInitialValueVertical',
            'JACKPOTINITVALUE_LIVECASINO' => 'clonarInitialValueVertical',
            'JACKPOTINITVALUE_VIRTUAL' => 'clonarInitialValueVertical',
            'JACKPOTINITVALUE_SPORTBOOK' => 'clonarInitialValueVertical',
            'CONDCATEGORY' => 'clonarCondicionStandard',
            'CONDPROVIDER' => 'clonarCondicionStandard',
            'CONDGAME' => 'clonarCondicionStandard',
            'EXCLUDED_CONDCATEGORY' => 'clonarCondicionStandard',
            'EXCLUDED_CONDPROVIDER'=> 'clonarCondicionStandard',
            'EXCLUDED_CONDGAME'=> 'clonarCondicionStandard',
            'ITAINMENT1' => 'clonarCondicionStandard',
            'ITAINMENT3' => 'clonarCondicionStandard',
            'ITAINMENT4' => 'clonarCondicionStandard',
            'ITAINMENT5' => 'clonarCondicionStandard',
            'EXCLUDED_ITAINMENT1' => 'clonarCondicionStandard',
            'EXCLUDED_ITAINMENT3' => 'clonarCondicionStandard',
            'EXCLUDED_ITAINMENT4' => 'clonarCondicionStandard',
            'EXCLUDED_ITAINMENT5' => 'clonarCondicionStandard',
            'MINAMOUNT_CASINO' => 'clonarCondicionStandard',
            'MINAMOUNT_LIVECASINO' => 'clonarCondicionStandard',
            'MINAMOUNT_VIRTUAL' => 'clonarCondicionStandard',
            'MINAMOUNT_SPORTBOOK' => 'clonarCondicionStandard',
            'MAXAMOUNT_CASINO' => 'clonarCondicionStandard',
            'MAXAMOUNT_LIVECASINO' => 'clonarCondicionStandard',
            'MAXAMOUNT_VIRTUAL' => 'clonarCondicionStandard',
            'MAXAMOUNT_SPORTBOOK' => 'clonarCondicionStandard',
            'JACKPOTPERCENTAGE_CASINO' => 'clonarCondicionStandard',
            'JACKPOTPERCENTAGE_LIVECASINO' => 'clonarCondicionStandard',
            'JACKPOTPERCENTAGE_VIRTUAL' => 'clonarCondicionStandard',
            'JACKPOTPERCENTAGE_SPORTBOOK' => 'clonarCondicionStandard',
            'FALLCRITERIA_MINBETQUANTITY' => 'clonarCondicionStandard',
            'FALLCRITERIA_MAXBETQUANTITY' => 'clonarCondicionStandard',
            'FALLCRITERIA_WINNERBET' => 'calcularApuestaGanadora', //Este item debe ser el último de los FallCriteria en el array
            'TIPOSALDO' => 'clonarCondicionStandard',
        ];

        return $inheritableTypes;
    }

    /** Retorna todos los tipos aplicables en el jackpot y que pueden existir en la tabla 
     * jackpot_detalle, agregar nuevos tipos al array en caso de agregar nuevos tipos en
     * la tabla detalles
     * @return array tipos aplicables existentes en la tabla jackpot_detalle*/
    public function getTiposGeneralesJackpot() : array
    {
        $types = [
            'CONDPAISUSER',
            'TOTALSERIES',
            'CURRENTSERIE',
            'SHOWCURRENCYSIGN',
            'COUNTERSTYLE',
            'JACKPOTINITVALUE_CASINO',
            'JACKPOTINITVALUE_LIVECASINO',
            'JACKPOTINITVALUE_VIRTUAL',
            'JACKPOTINITVALUE_SPORTBOOK',
            'JACKPOTINITVALUE_CASINO_NEXTSERIE',
            'JACKPOTINITVALUE_LIVECASINO_NEXTSERIE',
            'JACKPOTINITVALUE_VIRTUAL_NEXTSERIE',
            'JACKPOTINITVALUE_SPORTBOOK_NEXTSERIE',
            'EXCLUDED_CONDCATEGORY',
            'EXCLUDED_CONDPROVIDER',
            'EXCLUDED_CONDGAME',
            'CONDCATEGORY',
            'CONDPROVIDER',
            'CONDGAME',
            'EXCLUDED_ITAINMENT1', //Deporte excluidos
            'EXCLUDED_ITAINMENT3', //Liga excluidos
            'EXCLUDED_ITAINMENT4', //Evento excluidos
            'EXCLUDED_ITAINMENT5', //Mercado excluidos
            'ITAINMENT1', //Deporte
            'ITAINMENT3', //Liga
            'ITAINMENT4', //Evento
            'ITAINMENT5', //Mercado
            'MINAMOUNT_CASINO',
            'MINAMOUNT_LIVECASINO',
            'MINAMOUNT_VIRTUAL',
            'MINAMOUNT_SPORTBOOK',
            'MAXAMOUNT_CASINO',
            'MAXAMOUNT_LIVECASINO',
            'MAXAMOUNT_VIRTUAL',
            'MAXAMOUNT_SPORTBOOK',
            'JACKPOTPERCENTAGE_CASINO',
            'JACKPOTPERCENTAGE_LIVECASINO',
            'JACKPOTPERCENTAGE_VIRTUAL',
            'JACKPOTPERCENTAGE_SPORTBOOK',
            'FALLCRITERIA_MINBETQUANTITY',
            'FALLCRITERIA_MAXBETQUANTITY',
            'FALLCRITERIA_WINNERBET',
            'FALLCRITERIA_LASTDAYMINBETQUANTITY',
            'FALLCRITERIA_LASTDAYMAXBETQUANTITY',
            'FALLCRITERIA_LASTDAYWINNERBET',
            'TIPOSALDO'
        ];

        return $types;
    }


    /** Inserta detalles para un nuevo jackpot con base en los detalles pasados mediante el array mediante una copia simple del objeto
     * @param JackpotInterno $ClonJackpotInterno Objeto JackpotInterno que será clonado
     * @param array $JackpotDetallesAClonar Array con los detalles a clonar mediante el método standard
     * @param Transaction $Transaction Objeto Transaction para la inserción de los detalles
     * @return array Con los detalles clonados
     */
    public function clonarCondicionesStandard(JackpotInterno $ClonJackpotInterno, array $JackpotDetallesAClonar, Transaction $Transaction) {
        $notCloneableProperties = [
            'jackpotDetalleId' => null,
            'jackpotId' => $ClonJackpotInterno->jackpotId,
            'fechaCrea' => null,
            'fechaModif' => null,
            'usucreaId' => 0,
            'usumodifId' => 0
        ];

        $JackpotDetalleMySqlDAO = new JackpotDetalleMySqlDAO($Transaction);
        $newJackpotDetailsStack = [];
        foreach ($JackpotDetallesAClonar as $jackpotDetail) {
            $NewJackpotDetalle = new JackpotDetalle();
            foreach ($jackpotDetail as $property => $value) {
                if (in_array($property, array_keys($notCloneableProperties))) {
                    $NewJackpotDetalle->$property = $notCloneableProperties[$property];
                    continue;
                }

                $NewJackpotDetalle->$property = $value;
            }

            $JackpotDetalleMySqlDAO->insert($NewJackpotDetalle);
            $newJackpotDetailsStack[] = $NewJackpotDetalle;
        }

        return $newJackpotDetailsStack;
    }


    /** Inserción de detalles bajo un comportamiento especial
     * @param JackpotInterno $JackpotInterno Objeto JackpotInterno que será clonado
     * @param array $detallesParaClonadoEspecial Array con los detalles a clonar mediante un método especial
     * @param array $detallesAnteriorJackpot Array con los detalles del jackpot anterior
     * @param array $detallesActualJackpot Array con los detalles del jackpot clonado
     * @param Transaction $Transaction Objeto Transaction para la inserción de los detalles
     * @return array Con los detalles especiales clonados
     */
    public function clonarCondicionEspecial (JackpotInterno $JackpotInterno, array $detallesParaClonadoEspecial, array $detallesAnteriorJackpot, array $detallesActualJackpot, Transaction $Transaction) {

        $detailsToCreate = [];
        foreach ($detallesParaClonadoEspecial as $type => $method) {
            switch ($method) {
                case 'clonarCurrentSerie':
                    //No hay lógica, el detalle de tipo CURRENTSERIE se clona desde la función clonarJackpotNextSerie de JackpotInterno
                    continue 2;
                    break;
                case 'clonarInitialValueVertical':
                    $requiredType = match ($type) {
                        'JACKPOTINITVALUE_CASINO' => 'JACKPOTINITVALUE_CASINO_NEXTSERIE',
                        'JACKPOTINITVALUE_LIVECASINO' => 'JACKPOTINITVALUE_LIVECASINO_NEXTSERIE',
                        'JACKPOTINITVALUE_VIRTUAL' => 'JACKPOTINITVALUE_VIRTUAL_NEXTSERIE',
                        'JACKPOTINITVALUE_SPORTBOOK' => 'JACKPOTINITVALUE_SPORTBOOK_NEXTSERIE'
                    };

                    $verticalDetails = $this->encontrarDetalle($detallesAnteriorJackpot, $requiredType);
                    if (empty($verticalDetails)) continue 2;
                    $detailsToCreate[] = ['type' => $type, 'value' => $verticalDetails[0]->valor];
                    break;
                case 'calcularApuestaGanadora':
                    $minBetDetail = $this->encontrarDetalle($detallesActualJackpot, 'FALLCRITERIA_MINBETQUANTITY');
                    $maxBetDetail = $this->encontrarDetalle($detallesActualJackpot, 'FALLCRITERIA_MAXBETQUANTITY');
                    $winnerBet = rand($minBetDetail[0]->valor, $maxBetDetail[0]->valor);
                    $detailsToCreate[] = ['type' => $type, 'value' => $winnerBet];
                    break;
            }
        }

        $createdDetails = [];
        $detailsToCreate = json_decode(json_encode($detailsToCreate));
        $this->insertarDetallesJackpot($JackpotInterno->jackpotId, $detailsToCreate, $detallesActualJackpot[0]->moneda, $Transaction, 0, $createdDetails);

        return $createdDetails;
    }


    /** Inserta en la tabla Jackpot_detalle el conjunto de detalles correspondientes al jackpot en proceso de creación
     *@param int $jackpotId
     * @param array $jackpotDetails
     * @param Transaction $Transaction
     *
     * @return bool Retorna true si ningún detalle contiene error o false en caso de que algún detalle contenga error
     */
    public function insertarDetallesJackpot(int $jackpotId, array $jackpotDetails, string $moneda, Transaction $Transaction, int $usuarioId = 0, array &$detallesCreados = []) {
        $errors = 0;
        $JackpotDetalleMySqlDAO = new JackpotDetalleMySqlDAO($Transaction);

        foreach ($jackpotDetails as $jackpotDetail) {
            //Verificando inserción de errores
            $type = $jackpotDetail->type;
            $value = $jackpotDetail->value;
            $detailAndValue = $type . '_' . $value;
            if (str_contains($detailAndValue, 'ERROR')) $errors++;

            $JackpotDetalle = new JackpotDetalle();

            $JackpotDetalle->setJackpotId($jackpotId);
            $JackpotDetalle->setTipo($type);
            $JackpotDetalle->setMoneda($moneda);
            $JackpotDetalle->setValor($value);
            $JackpotDetalle->setUsucreaId($usuarioId);
            $JackpotDetalle->setUsumodifId($usuarioId);

            $JackpotDetalleMySqlDAO->insert($JackpotDetalle);
            $detallesCreados[] = $JackpotDetalle;
        }

        return $errors < 1;
    }

    /**
     * Carga los detalles del jackpot especificado desde la base de datos.
     *
     * @param int $jackpotId El ID del jackpot.
     * @param string $tipo (Opcional) El tipo de detalle del jackpot.
     * @return array Un arreglo de objetos JackpotDetalle.
     */
    public function cargarDetallesJackpot(int $jackpotId, string $tipo = '') {
        $details = [];
        if ($tipo != '') $rules[] = ['field' => 'jackpot_detalle.tipo', 'data' => $tipo, 'op' => 'eq'];
        $rules[] = ['field' => 'jackpot_detalle.jackpot_id', 'data' => $jackpotId, 'op' => 'eq'];
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $select = 'jackpotdetalle_id';
        $sidx = 'jackpotdetalle_id';

        //Solicitando total de detalles
        $totalDetails = $this->getJackpotDetalleCustom($select, $sidx, 'ASC', 0, 1, json_encode($filters), true, true);
        $totalDetails = json_decode($totalDetails)->count;
        $totalDetails = $totalDetails[0]->{'.count'};

        //Solicitando detalles
        $detailsIds = $this->getJackpotDetalleCustom($select, $sidx, 'ASC', 0, $totalDetails, json_encode($filters), true);
        $detailsIds = json_decode($detailsIds)->data;
        foreach ($detailsIds as $detailId) {
            $JackpotDetalle = new JackpotDetalle($detailId->{'jackpot_detalle.jackpotdetalle_id'});
            $details[] = $JackpotDetalle;
        }

        return $details;
    }


    /** Retorna en un array los objetos JackpotDetalle que cumplan con el tipo requerido,
     * si también se envía un regexValor, se buscará en el valor del objeto que coincida con el patrón pasado mediante el parámetro
     *
     *@param array $detalles Array con objetos de tipo JackpotDetalle
     *@param string $tipo El tipo de detalle solicitado
     *@param string $regexValor Patrón regex para búsqueda en la columna valor, el patrón debe ser ingresado con los delimitadores de inicio y finalización
     *@return array Con los objetos que cumplen las condiciones
     */
    public function encontrarDetalle (array $detalles, string $tipo, string $regexValor = '') {
        $result = array_filter($detalles, function ($detail) use ($tipo, $regexValor) {
            if ($detail->getTipo() == $tipo) {
                if ($regexValor == '') return $detail;
                else {
                    $regexSearchResult = preg_match($regexValor, $detail->getValor());
                    if ($regexSearchResult) return $detail;
                }
            }
        });
        $result = array_values($result);

        return $result;
    }
}

