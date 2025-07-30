<?php
    namespace Backend\mysql;
    use Backend\dto\ReporteDinamico;
    use Backend\sql\QueryExecutor;
    use Backend\sql\SqlQuery;
    use Backend\sql\Transaction;

    /** 
     * Clase ReporteDinamicoMySqlDAO
     * Provee las consultas vinculadas a la tabla reporte_dinamico de la base de datos
     * @author Desconocido
     * @since: Desconocido
     * @category No
     * @package No
     * @version     1.0
     */
    class ReporteDinamicoMySlqDAO {
        /** Objeto vincula una conexión de la base de datos con el objeto correspondiente
         * @var Transaction $transaction
         */
        private $transaction;

        /**
         * Constructor de la clase.
         *
         * @param Transaction|null $transaction Objeto de transacción opcional.
         */
        public function __construct($transaction = null)
        {
            if (empty($transaction)) {
                $this->transaction = new Transaction();
            } else {
                $this->transaction = $transaction;
            }
        }

        /**
         * Obtiene la transacción actual.
         *
         * @return Transaction La transacción actual.
         */
        public function getTransaction()
        {
            return $this->transaction;
        }

        /**
         * Establece una nueva transacción.
         *
         * @param Transaction $transaction La nueva transacción.
         */
        public function setTransaction($transaction)
        {
            $this->transaction = $transaction;
        }

        /**
         * Lee una fila de la base de datos y la convierte en un objeto ReporteDinamico.
         *
         * @param array $row La fila de la base de datos.
         * @return ReporteDinamico El objeto ReporteDinamico.
         */
        private function readRow($row)
        {
            $ReporteDinamico = new ReporteDinamico();

            $ReporteDinamico->setReporteDinamicoId($row['reportedinamico_id']);
            $ReporteDinamico->setSubmenuId($row['submenu_id']);
            $ReporteDinamico->setColumnas($row['columnas']);
            $ReporteDinamico->setMandante($row['mandate']);
            $ReporteDinamico->setUsucreaId($row['usucrea_id']);
            $ReporteDinamico->setUsumodifId($row['usumodif_id']);
            $ReporteDinamico->setPaisId($row['pais_id']);
            $ReporteDinamico->setFechacrea($row['fecha_crea']);
            $ReporteDinamico->setFechamodif($row['fecha_modif']);

            return $ReporteDinamico;
        }

        /**
         * Ejecuta una consulta SQL.
         *
         * @param SqlQuery $sqlQuery La consulta SQL.
         * @return mixed El resultado de la consulta.
         */
        private function execute($sqlQuery)
        {
            return QueryExecutor::execute($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una consulta SQL alternativa.
         *
         * @param SqlQuery $sqlQuery La consulta SQL.
         * @return mixed El resultado de la consulta.
         */
        private function execute2($sqlQuery)
        {
            return QueryExecutor::execute2($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una actualización SQL.
         *
         * @param SqlQuery $sqlQuery La consulta SQL.
         * @return mixed El resultado de la actualización.
         */
        private function executeUpdate($sqlQuery)
        {
            return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
        }

        /**
         * Obtiene una fila de la base de datos.
         *
         * @param SqlQuery $sqlQuery La consulta SQL.
         * @return ReporteDinamico|null El objeto ReporteDinamico o null si no se encuentra.
         */
        private function getRow($sqlQuery)
        {
            $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
            if (oldCount($tab) == 0) {
                return null;
            }
            return $this->readRow($tab[0]);
        }

        /**
         * Obtiene una lista de filas de la base de datos.
         *
         * @param SqlQuery $sqlQuery La consulta SQL.
         * @return array La lista de objetos ReporteDinamico.
         */
        private function getList($sqlQuery)
        {
            $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
            $ret = array();
            for ($i = 0; $i < oldCount($tab); $i++) {
                $ret[$i] = $this->readRow($tab[$i]);
            }
            return $ret;
        }

        /**
         * Ejecuta una inserción SQL.
         *
         * @param SqlQuery $sqlQuery La consulta SQL.
         * @return mixed El resultado de la inserción.
         */
        private function executeInsert($sqlQuery)
        {
            return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
        }

        /**
         * Carga un reporte dinámico por su ID.
         *
         * @param int $reporteDinamicoId El ID del reporte dinámico.
         * @return ReporteDinamico|null El objeto ReporteDinamico o null si no se encuentra.
         */
        public function load($reporteDinamicoId)
        {
            $sql = 'SELECT * FROM reporte_dinamico WHERE reportedinamico_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($reporteDinamicoId);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un reporte dinámico por su submenu ID, mandante y país ID.
         *
         * @param int $submenuId El ID del submenu.
         * @param string $mandante El mandante.
         * @param int $paisId El ID del país.
         * @return ReporteDinamico|null El objeto ReporteDinamico o null si no se encuentra.
         */
        public function loadBySubmenuId($submenuId, $mandante, $paisId)
        {
            $sql = 'SELECT * FROM reporte_dinamico WHERE submenu_id = ? AND mandante = ? AND pais_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($submenuId);
            $sqlQuery->set($mandante);
            $sqlQuery->set($paisId);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un reporte dinámico por su mandante.
         *
         * @param string $mandante El mandante.
         * @return ReporteDinamico|null El objeto ReporteDinamico o null si no se encuentra.
         */
        public function loadByManadeteId($mandante)
        {
            $sql = 'SELECT * FROM reporte_dinamico WHERE mandante = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($mandante);
            return $this->getRow($sqlQuery);
        }

        /**
         * Consulta todos los reportes dinámicos.
         *
         * @return array La lista de objetos ReporteDinamico.
         */
        public function queryAll()
        {
            $sql = 'SELECT * FROM reporte_dinamico';
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Consulta los reportes dinámicos por submenu ID.
         *
         * @param int $submenuId El ID del submenu.
         * @return array La lista de objetos ReporteDinamico.
         */
        public function queryBySubmenuId($submenuId)
        {
            $sql = 'SELECT * FROM reporte_dinamico where submenu_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($submenuId);
            return $this->getList($sqlQuery);
        }

        /**
         * Consulta los reportes dinámicos por mandante.
         *
         * @param string $mandate El mandante.
         * @return array La lista de objetos ReporteDinamico.
         */
        public function queryByMandante($mandate)
        {
            $sql = 'SELECT * FROM reporte_dinamico where mandante = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($mandate);
            return $this->getList($sqlQuery);
        }

        /**
         * Consulta todos los reportes dinámicos ordenados por una columna.
         *
         * @param string $orderColumn La columna por la cual se ordenará.
         * @return array La lista de objetos ReporteDinamico.
         */
        public function queryAllOrderBy($orderColumn)
        {
            $sql = 'SELECT * FROM reporte_dinamico ORDER BY ' . $orderColumn;
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Inserta un nuevo reporte dinámico.
         *
         * @param ReporteDinamico $reporteDinamico El objeto ReporteDinamico a insertar.
         * @return int El ID del reporte dinámico insertado.
         */
        public function insert($reporteDinamico)
        {
            $sql = 'INSERT INTO reporte_dinamico (submenu_id, columnas, mandante, pais_id, usucrea_id) VALUES (?, ?, ?, ?, ?)';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->setNumber($reporteDinamico->getSubmenuId());
            $sqlQuery->set($reporteDinamico->getColumnas());
            $sqlQuery->setNumber($reporteDinamico->getMandante());
            $sqlQuery->setNumber($reporteDinamico->getPaisId());
            $sqlQuery->setNumber($reporteDinamico->getUsucreaId());
            $id = $this->executeInsert($sqlQuery);
            $reporteDinamico->reporteDinamicoId = $id;
            return $id;
        }

        /**
         * Actualiza un reporte dinámico existente.
         *
         * @param ReporteDinamico $reporteDinamico El objeto ReporteDinamico a actualizar.
         * @return mixed El resultado de la actualización.
         */
        public function update($reporteDinamico)
        {
            if ($reporteDinamico->getReporteDinamicoId() != '') {
                $sql = 'UPDATE reporte_dinamico SET submenu_id = ?, columnas = ?, mandante = ?, pais_id = ?, usumodif_id = ? WHERE reportedinamico_id = ?';
                $sqlQuery = new SqlQuery($sql);
                $sqlQuery->setNumber($reporteDinamico->getSubmenuId());
                $sqlQuery->set($reporteDinamico->getColumnas());
                $sqlQuery->set($reporteDinamico->getMandante());
                $sqlQuery->set($reporteDinamico->getPaisId());
                $sqlQuery->setNumber($reporteDinamico->getUsumodifId());
                $sqlQuery->setNumber($reporteDinamico->getReporteDinamicoId());

                return $this->executeUpdate($sqlQuery);
            } else {
                $sql = 'UPDATE reporte_dinamico SET columnas = ?, usumodif_id = ? WHERE submenu_id = ? AND mandante = ?';
                $sqlQuery = new SqlQuery($sql);
                $sqlQuery->set($reporteDinamico->getColumnas());
                $sqlQuery->setNumber($reporteDinamico->getUsumodifId());
                $sqlQuery->setNumber($reporteDinamico->getSubmenuId());
                $sqlQuery->set($reporteDinamico->getMandante());

                return $this->executeUpdate($sqlQuery);
            }
        }

        /**
         * Elimina un reporte dinámico por su ID.
         *
         * @param int $reporteDinamicoId El ID del reporte dinámico.
         * @return mixed El resultado de la eliminación.
         */
        public function delete($reporteDinamicoId)
        {
            $sql = 'DELETE FROM reporte_dinamico WHERE reportedinamico_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($reporteDinamicoId);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Elimina reportes dinámicos por submenu ID.
         *
         * @param int $submenuId El ID del submenu.
         * @return mixed El resultado de la eliminación.
         */
        public function deleteBySubmenuId($submenuId)
        {
            $sql = 'DELETE FROM reporte_dinamico WHERE submenu_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($submenuId);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Elimina reportes dinámicos por mandante.
         *
         * @param string $mandante El mandante.
         * @return mixed El resultado de la eliminación.
         */
        public function deleteByMandate($mandante)
        {
            $sql = 'DELETE FROM reporte_dinamico WHERE mandante = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($mandante);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Elimina todos los reportes dinámicos.
         *
         * @return mixed El resultado de la eliminación.
         */
        public function clean()
        {
            $sql = 'DELETE FROM reporte_dinamico';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Consulta personalizada para el reporte dinámico.
         *
         * @param string $select Columnas a seleccionar en la consulta.
         * @param string $sidx Columna por la cual se ordenará.
         * @param string $sord Orden de la columna (ASC o DESC).
         * @param int $start Inicio del límite de la consulta.
         * @param int $limit Número de registros a obtener.
         * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
         * @param bool $searchOn Indica si se deben aplicar los filtros.
         * @return string JSON con el conteo de registros y los datos obtenidos.
         */
        public function queryReporteDinamicoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $where = " where 1=1 ";

            if ($searchOn) {
                // Construye el where
                $filters = json_decode($filters);
                $whereArray = array();
                $rules = $filters->rules;
                $groupOperation = $filters->groupOp;
                $cont = 0;

                foreach ($rules as $rule) {
                    $fieldName = $rule->field;
                    $fieldData = $rule->data;
                    switch ($rule->op) {
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
                    if ($fieldOperation != "") {
                        $whereArray[] = $fieldName . $fieldOperation;
                    }

                    if (oldCount($whereArray) > 0) {
                        $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                    } else {
                        $where = "";
                    }
                }

            }

            $sql = "SELECT count(*) count FROM reporte_dinamico RIGHT JOIN submenu ON (submenu.submenu_id = reporte_dinamico.submenu_id) " . $where;
            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $sql = "SELECT " . $select . " FROM reporte_dinamico RIGHT JOIN submenu ON (submenu.submenu_id = reporte_dinamico.submenu_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
            $sqlQuery = new SqlQuery($sql);

            $result = $this->execute2($sqlQuery);

            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

            return $json;
        } 
    }
?>
