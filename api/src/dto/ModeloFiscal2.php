<?php

namespace Backend\dto;

use Backend\mysql\ModeloFiscal2MySqlDAO;

class ModeloFiscal2
{
    /** @var mixed Representación de la columna 'modelofiscal_id' en la tabla 'modelo_fiscal2' */
    private $modeloFiscalId;

    /** @var mixed Representación de la columna 'reporte' en la tabla 'modelo_fiscal2' */
    private $reporte;

    /** @var mixed Representación de la columna 'mandante' en la tabla 'modelo_fiscal2' */
    private $mandante;

    /** @var mixed Representación de la columna 'pais_id' en la tabla 'modelo_fiscal2' */
    private $paisId;

    /** @var mixed Representación de la columna 'mes' en la tabla 'modelo_fiscal2' */
    private $mes;

    /** @var mixed Representación de la columna 'anio' en la tabla 'modelo_fiscal2' */
    private $anio;

    /** @var mixed Representación de la columna 'columnas' en la tabla 'modelo_fiscal2' */
    private $columnas;

    /** @var mixed Representación de la columna 'tipo' en la tabla 'modelo_fiscal2' */
    private $tipo;

    /** @var mixed Representación de la columna 'usucrea_id' en la tabla 'modelo_fiscal2' */
    private $usucreaId;

    /** @var mixed Representación de la columna 'usumodif_id' en la tabla 'modelo_fiscal2' */
    private $usumodifId;


    /**
     * Constructor de la clase ModeloFiscal2.
     *
     * @param string $modeloFiscalId ID del modelo fiscal.
     * @param string $reporte Reporte del modelo fiscal.
     * @param string $mandante Mandante del modelo fiscal.
     * @param string $paisId ID del país del modelo fiscal.
     * @param string $mes Mes del modelo fiscal.
     * @param string $anio Año del modelo fiscal.
     * @param string $tipo Tipo del modelo fiscal.
     *
     * @throws \Exception Si no existe el modelo fiscal con los parámetros proporcionados.
     */
    public function __construct($modeloFiscalId = '', $reporte = '', $mandante = '', $paisId = '', $mes = '', $anio = '', $tipo = '')
    {
        if (!empty($modeloFiscalId)) {
            $ModeloFiscal2MySqlDAO = new ModeloFiscal2MySqlDAO();
            $ModeloFiscal2 = $ModeloFiscal2MySqlDAO->load($modeloFiscalId);

            if ($ModeloFiscal2 !== '') {
                $this->setModeloFiscalId($ModeloFiscal2->getModeloFiscalId());
                $this->setReporte($ModeloFiscal2->getReporte());
                $this->setMandante($ModeloFiscal2->getMandante());
                $this->setPaisId($ModeloFiscal2->getPaisId());
                $this->setMes($ModeloFiscal2->getMes());
                $this->setAnio($ModeloFiscal2->getAnio());
                $this->setColumnas($ModeloFiscal2->getColumnas());
                $this->setTipo($ModeloFiscal2->getTipo());
                $this->setUsucreaId($ModeloFiscal2->getUsucreaId());
                $this->setUsumodifId($ModeloFiscal2->getUsumodifId());
            }
        } else if (!empty($reporte) && $mandante !== '' && !empty($paisId) && !empty($mes) && !empty($anio) && !empty($tipo)) {
            $ModeloFiscal2MySqlDAO = new ModeloFiscal2MySqlDAO();

            $ModeloFiscal2 = $ModeloFiscal2MySqlDAO->loadByReporteMandantePaisConMesAnioTipo($reporte, $mandante, $paisId, $mes, $anio, $tipo);

            if ($ModeloFiscal2 != '') {
                $this->setModeloFiscalId($ModeloFiscal2->getModeloFiscalId());
                $this->setReporte($ModeloFiscal2->getReporte());
                $this->setMandante($ModeloFiscal2->getMandante());
                $this->setPaisId($ModeloFiscal2->getPaisId());
                $this->setMes($ModeloFiscal2->getMes());
                $this->setAnio($ModeloFiscal2->getAnio());
                $this->setColumnas($ModeloFiscal2->getColumnas());
                $this->setTipo($ModeloFiscal2->getTipo());
                $this->setUsucreaId($ModeloFiscal2->getUsucreaId());
                $this->setUsumodifId($ModeloFiscal2->getUsumodifId());
            } else {
                throw new \Exception('No existe', 34);
            }
        }
    }

    /**
     * Establece el ID del Modelo Fiscal.
     *
     * @param mixed $modeloFiscalId
     */
    public function setModeloFiscalId($modeloFiscalId)
    {
        $this->modeloFiscalId = $modeloFiscalId;
    }

    /**
     * Obtiene el ID del Modelo Fiscal.
     *
     * @return mixed
     */
    public function getModeloFiscalId()
    {
        return $this->modeloFiscalId;
    }

    /**
     * Establece el Reporte.
     *
     * @param mixed $reporte
     */
    public function setReporte($reporte)
    {
        $this->reporte = $reporte;
    }

    /**
     * Obtiene el Reporte.
     *
     * @return mixed
     */
    public function getReporte()
    {
        return $this->reporte;
    }

    /**
     * Establece el Mandante.
     *
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el Mandante.
     *
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Establece el ID del País.
     *
     * @param mixed $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtiene el ID del País.
     *
     * @return mixed
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Establece el Mes.
     *
     * @param mixed $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * Obtiene el Mes.
     *
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Establece el Año.
     *
     * @param mixed $anio
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;
    }

    /**
     * Obtiene el Año.
     *
     * @return mixed
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Establece el Tipo.
     *
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el Tipo.
     *
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece las Columnas.
     *
     * @param mixed $columnas
     */
    public function setColumnas($columnas)
    {
        $this->columnas = $columnas;
    }

    /**
     * Obtiene las Columnas.
     *
     * @return mixed
     */
    public function getColumnas()
    {
        return $this->columnas;
    }

    /**
     * Establece el ID del Usuario Creador.
     *
     * @param mixed $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el ID del Usuario Creador.
     *
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del Usuario Modificador.
     *
     * @param mixed $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el ID del Usuario Modificador.
     *
     * @return mixed
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }


    /**
     * Obtiene una colección de modelos fiscal personalizado basado en los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * 
     * @return mixed Respuesta de la consulta personalizada del modelo fiscal.
     * @throws \Exception Si no existe el modelo fiscal.
     */
    public function getModeloFiscal2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {
        $ModeloFiscal2MySqlDAO = new ModeloFiscal2MySqlDAO();

        $response = $ModeloFiscal2MySqlDAO->queryModeloFiscal2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($response === '') throw new \Exception('No existe' . get_class($this), 34);

        return $response;
    }
}
?>