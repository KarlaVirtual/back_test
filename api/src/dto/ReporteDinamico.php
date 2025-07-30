<?php

namespace Backend\dto;

use Backend\mysql\ReporteDinamicoMySlqDAO;

class ReporteDinamico
{

    /**
     * @var string Representación de la columna 'reporte_dinamico_id' en la tabla 'reporte_dinamico'
     */
    public $reporteDinamicoId;

    /**
     * @var string Representación de la columna 'submenu_id' en la tabla 'reporte_dinamico'
     */
    public $submenuId;

    /**
     * @var string Representación de la columna 'columnas' en la tabla 'reporte_dinamico'
     */
    public $columnas;

    /**
     * @var string Representación de la columna 'mandante' en la tabla 'reporte_dinamico'
     */
    public $mandante;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'reporte_dinamico'
     */
    public $usucreaId;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'reporte_dinamico'
     */
    public $usumodifId;

    /**
     * @var string Representación de la columna 'pais_id' en la tabla 'reporte_dinamico'
     */
    public $paisId;

    /**
     * @var string Representación de la columna 'fechacrea' en la tabla 'reporte_dinamico'
     */
    public $fechacrea;

    /**
     * @var string Representación de la columna 'fechamodif' en la tabla 'reporte_dinamico'
     */
    public $fechamodif;

    /**
     * Constructor de la clase ReporteDinamico.
     *
     * @param string $reporteDinamicoId ID del reporte dinámico (opcional).
     * @param string $submenuId ID del submenú (opcional).
     * @param string $mandante Mandante (opcional).
     * @param string $paisId ID del país (opcional).
     *
     * @throws \Exception Si no existe el reporte dinámico con el ID proporcionado.
     */
    public function __construct($reporteDinamicoId = '', $submenuId = '', $mandante = '', $paisId = '')
    {
        if ($reporteDinamicoId != '') {
            $ReporteDinamicoMySlqDAO = new ReporteDinamicoMySlqDAO();
            $ReporteDinamico = $ReporteDinamicoMySlqDAO->load($reporteDinamicoId);

            if ($ReporteDinamico != '') {
                $this->reporteDinamicoId = $ReporteDinamico->getReporteDinamicoId();
                $this->submenuId = $ReporteDinamico->getSubmenuId();
                $this->columnas = $ReporteDinamico->getColumnas();
                $this->partner = $ReporteDinamico->getMandante();
                $this->usucreaId = $ReporteDinamico->getUsucreaId();
                $this->usumodifId = $ReporteDinamico->getUsumodifId();
                $this->paisId = $ReporteDinamico->getPaisId();
                $this->fechacrea = $ReporteDinamico->getFechacrea();
                $this->fechamodif = $ReporteDinamico->getFechamodif();
            } else {
                throw new \Exception('No existe' . get_class($this), 109);
            }
        } elseif ($submenuId != '' && $mandante != '' && $paisId != '') {
            $ReporteDinamicoMySlqDAO = new ReporteDinamicoMySlqDAO();
            $ReporteDinamico = $ReporteDinamicoMySlqDAO->loadBySubmenuId($submenuId, $mandante, $paisId);

            if ($ReporteDinamico != '') {
                $this->reporteDinamicoId = $ReporteDinamico->getReporteDinamicoId();
                $this->submenuId = $ReporteDinamico->getSubmenuId();
                $this->columnas = $ReporteDinamico->getColumnas();
                $this->partner = $ReporteDinamico->getMandante();
                $this->usucreaId = $ReporteDinamico->getUsucreaId();
                $this->usumodifId = $ReporteDinamico->getUsumodifId();
                $this->paisId = $ReporteDinamico->getPaisId();
                $this->fechacrea = $ReporteDinamico->getFechacrea();
                $this->fechamodif = $ReporteDinamico->getFechamodif();
            }
        }
    }

    /**
         * Obtiene el ID del reporte dinámico.
         *
         * @return string
         */
        public function getReporteDinamicoId()
        {
            return $this->reporteDinamicoId;
        }

        /**
         * Establece el ID del reporte dinámico.
         *
         * @param string $reporteDinamicoId
         */
        public function setReporteDinamicoId($reporteDinamicoId)
        {
            $this->reporteDinamicoId = $reporteDinamicoId;
        }

        /**
         * Obtiene el ID del submenú.
         *
         * @return string
         */
        public function getSubmenuId()
        {
            return $this->submenuId;
        }

        /**
         * Establece el ID del submenú.
         *
         * @param string $submenuId
         */
        public function setSubmenuId($submenuId)
        {
            $this->submenuId = $submenuId;
        }

        /**
         * Obtiene las columnas del reporte dinámico.
         *
         * @return string
         */
        public function getColumnas()
        {
            return $this->columnas;
        }

        /**
         * Establece las columnas del reporte dinámico.
         *
         * @param string $columnas
         */
        public function setColumnas($columnas)
        {
            $this->columnas = $columnas;
        }

        /**
         * Obtiene el mandante del reporte dinámico.
         *
         * @return string
         */
        public function getMandante()
        {
            return $this->mandante;
        }

        /**
         * Establece el mandante del reporte dinámico.
         *
         * @param string $mandante
         */
        public function setMandante($mandante)
        {
            $this->mandante = $mandante;
        }

        /**
         * Obtiene el ID del usuario creador.
         *
         * @return string
         */
        public function getUsucreaId()
        {
            return $this->usucreaId;
        }

        /**
         * Establece el ID del usuario creador.
         *
         * @param string $usucreaId
         */
        public function setUsucreaId($usucreaId)
        {
            $this->usucreaId = $usucreaId;
        }

        /**
         * Obtiene el ID del usuario modificador.
         *
         * @return string
         */
        public function getUsumodifId()
        {
            return $this->usumodifId;
        }

        /**
         * Establece el ID del usuario modificador.
         *
         * @param string $usumodifId
         */
        public function setUsumodifId($usumodifId)
        {
            $this->usumodifId = $usumodifId;
        }

        /**
         * Obtiene el ID del país.
         *
         * @return string
         */
        public function getPaisId()
        {
            return $this->paisId;
        }

        /**
         * Establece el ID del país.
         *
         * @param string $paisId
         */
        public function setPaisId($paisId)
        {
            $this->paisId = $paisId;
        }

        /**
         * Obtiene la fecha de creación del reporte dinámico.
         *
         * @return string
         */
        public function getFechacrea()
        {
            return $this->fechacrea;
        }

        /**
         * Establece la fecha de creación del reporte dinámico.
         *
         * @param string $fechacrea
         */
        public function setFechacrea($fechacrea)
        {
            $this->fechacrea = $fechacrea;
        }

        /**
         * Obtiene la fecha de modificación del reporte dinámico.
         *
         * @return string
         */
        public function getFechamodif()
        {
            return $this->fechamodif;
        }

        /**
         * Establece la fecha de modificación del reporte dinámico.
         *
         * @param string $fechamodif
         */
        public function setFechamodif($fechamodif)
        {
            $this->fechamodif = $fechamodif;
        }

/**
         * Obtiene un reporte dinámico personalizado.
         *
         * @param string $select Columnas a seleccionar.
         * @param string $sidx Índice de ordenación.
         * @param string $sort Orden de clasificación (ascendente o descendente).
         * @param int $start Inicio de los resultados.
         * @param int $limit Límite de resultados.
         * @param array $filters Filtros a aplicar.
         * @param bool $searchOn Indica si la búsqueda está activada.
         *
         * @return array Resultados del reporte dinámico personalizado.
         * @throws \Exception Si no existen reportes dinámicos.
         */
        public function getReporteDinamicoCustom($select, $sidx, $sort, $start, $limit, $filters, $searchOn = false)
        {
            $ReporteDinamicoMySqlDAO = new ReporteDinamicoMySlqDAO();
            $reportes = $ReporteDinamicoMySqlDAO->queryReporteDinamicoCustom($select, $sidx, $sort, $start, $limit, $filters, $searchOn);

            if (empty($reportes)) throw new \Exception('No existe ' . get_class($this), 109);

            return $reportes;
        }
}

?>
