<?php

namespace Backend\mysql;

use Backend\dao\CriptomonedaDAO;
use Backend\dto\Criptomoneda;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;

/**
 * Implementación mySql para la obtención de información de la tabla 'criptomoneda'.
 *
 * @author David Torres Rendon david.torres@virtualsoft.tech
 * @category    No
 * @package     No
 * @version     1.0
 * @since       2025-06-04
 */
class CriptomonedaMySqlDAO implements CriptomonedaDAO
{
    /**
     * Transacción bajo la cual opera el DAO.
     */
    public Transaction $transaction;

    /**
     * Constructor de la clase CriptomonedaMySqlDAO.
     *
     * Inicializa la transacción bajo la cual operará el DAO. Si no se proporciona una transacción,
     * se crea una nueva instancia de Transaction por defecto.
     *
     * @param Transaction|null $transaction Transacción opcional a utilizar.
     */
    public function __construct(?Transaction $transaction = null)
    {
        $this->transaction = $transaction ?? new Transaction();
    }

    /**
     *Obtener el dto de una criptomoneda.
     * @param mixed $criptomonedaId Identificador de la criptomoneda.
     * @return Criptomoneda|null Retorna el dto de la criptomoneda o null si no existe.
     */
    public function load(mixed $criptomonedaId)
    {
        $loadStatement = "SELECT * FROM criptomoneda WHERE criptomoneda_id = ?";
        $sqlQuery = new SqlQuery($loadStatement);
        $sqlQuery->setNumber($criptomonedaId);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtiene el DTO de una criptomoneda a partir de su código ISO.
     *
     * @param string $codigoIso Código ISO de la criptomoneda.
     * @return Criptomoneda|null Retorna el DTO de la criptomoneda o null si no existe.
     */
    public function loadByCodigoIso(string $codigoIso)
    {
        $loadStatement = "SELECT * FROM criptomoneda WHERE codigo_iso = ?";
        $sqlQuery = new SqlQuery($loadStatement);
        $sqlQuery->set($codigoIso);
        return $this->getRow($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Criptomoneda $Criptomoneda Dto de Criptomoneda a ser insertado.
     * @return int Retorna el ID del registro insertado.
     */
    public function insert(Criptomoneda $Criptomoneda): int
    {
        /* Definición sentencia de inserción */
        $insertStatement = "INSERT INTO criptomoneda (codigo_iso, nombre, icono, nivel_estabilidad, estado, usucrea_id, usumodif_id) VALUE (?, ?, ?, ?, ?, ?, ?)";
        $sqlQuery = new SqlQuery($insertStatement);

        /* Asignación valores de inserción */
        $sqlQuery->set($Criptomoneda->getCodigoIso());

        $sqlQuery->set($Criptomoneda->getNombre());

        if (empty($Criptomoneda->getIcono())) $sqlQuery->setSIN("null");
        else $sqlQuery->set($Criptomoneda->getIcono());

        $sqlQuery->set($Criptomoneda->getNivelEstabilidad());

        $sqlQuery->set($Criptomoneda->getEstado());

        if (empty($Criptomoneda->getUsucreaId())) $sqlQuery->setSIN("null");
        else $sqlQuery->setNumber($Criptomoneda->getUsucreaId());

        if (empty($Criptomoneda->getUsumodifId())) $sqlQuery->setSIN("null");
        else $sqlQuery->setNumber($Criptomoneda->getUsumodifId());

        $id = $this->executeInsert($sqlQuery);
        $Criptomoneda->criptomonedaId = $id;
        return $Criptomoneda->criptomonedaId;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Criptomoneda $Criptomoneda Dto de criptomoneda a ser actualizada.
     * @return int Retorna el número de filas afectadas.
     */
    public function update(Criptomoneda $Criptomoneda): int
    {
        /* Definición sentencia de actualización */
        $updateStatement = "UPDATE criptomoneda set codigo_iso = ?, nombre = ?, icono = ?, nivel_estabilidad = ?, estado = ?, usucrea_id = ?, usumodif_id = ? WHERE criptomoneda_id = ?";
        $sqlQuery = new SqlQuery($updateStatement);

        /* Asignación valores de actualización */
        $sqlQuery->set($Criptomoneda->getCodigoIso());

        $sqlQuery->set($Criptomoneda->getNombre());

        if (empty($Criptomoneda->getIcono())) $sqlQuery->setSIN("null");
        else $sqlQuery->set($Criptomoneda->getIcono());

        $sqlQuery->set($Criptomoneda->getNivelEstabilidad());

        $sqlQuery->set($Criptomoneda->getEstado());

        if (empty($Criptomoneda->getUsucreaId())) $sqlQuery->setSIN("null");
        else $sqlQuery->setNumber($Criptomoneda->getUsucreaId());

        if (empty($Criptomoneda->getUsumodifId())) $sqlQuery->setSIN("null");
        else $sqlQuery->setNumber($Criptomoneda->getUsumodifId());

        /*Definiendo registro objetivo a actualizar*/
        $sqlQuery->setNumber($Criptomoneda->getCriptomonedaId());

        return $this->executeInsert($sqlQuery);
    }

    /**
     *Consultar una colección personalizada de criptomonedas.
     * @param string $select Campos a seleccionar, separados por comas.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta ('asc' o 'desc').
     * @param mixed $start Índice de inicio para la paginación.
     * @param mixed $limit Límite de registros a retornar.
     * @param string $filters Filtros en formato JSON para aplicar a la consulta.
     * @param array|null $joins Arreglo de objetos que definen los joins a realizar.
     * @return object Retorna un objeto con los resultados de la consulta.
     */
    public function queryCriptomonedaCustom(string $select, string $sidx, string $sord, mixed $start, mixed $limit, string $filters, ?array $joins = null): string
    {
        // Construye el where
        $filters = json_decode($filters);
        $whereArray = array();
        $rules = $filters->rules;
        $groupOperation = $filters->groupOp;
        $cont = 0;
        $where = "";

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
                    $fieldOperation = " NOT IN '" . $fieldData . "'";
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
            if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
            if (count($whereArray) > 0) {
                $where =  $where . " " . ((!empty($where)) ? $groupOperation : " WHERE ") . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
            } else {
                $where = "";
            }
        }

        /* Construyendo cadena de joins solicitados fuera de la petición JOINS DINÁMICOS */
        $strJoins = " ";
        if (!empty($joins)) {
            foreach ($joins as $join) {
                /**
                 *Ejemplo estructura $join
                 *{
                 *     "type": "INNER" | "LEFT" | "RIGHT",
                 *     "table": "usuario_puntoslealtad"
                 *     "on": "usuario.usuario_id = usuario_puntoslealtad.usuario_id"
                 *}
                 */
                $allowedJoins = ["INNER", "LEFT", "RIGHT"];
                if (in_array($join->type, $allowedJoins)) {
                    //Estructurando cadena de joins
                    $strJoins .= " " . strtoupper($join->type) . " JOIN " . $join->table . " ON (" . $join->on . ") ";
                }
            }
        }

        $sql = "select count(*) as count from criptomoneda " . $strJoins . $where;
        $sqlQuery = new SqlQuery($sql);
        $count = $this->execute2($sqlQuery);

        $sql = "select " . $select . " from criptomoneda  " . $strJoins . $where . " order by " . $sidx . " " . $sord . " limit " . $start . "," . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    protected function readRow($row)
    {
        $CriptomonedaObj = (object)[
            "criptomonedaId" => $row["criptomoneda_id"],
            "codigoIso" => $row["codigo_iso"],
            "nombre" => $row["nombre"],
            "icono" => $row["icono"],
            "nivelEstabilidad" => $row["nivel_estabilidad"],
            "estado" => $row["estado"],
            "fechaCrea" => $row["fecha_crea"],
            "fechaModif" => $row["fecha_modif"],
            "usucreaId" => $row["usucrea_id"],
            "usumodifId" => $row["usumodif_id"],
        ];

        $Criptomoneda = new Criptomoneda();
        $Criptomoneda->forceAttributesSetting($CriptomonedaObj);

        return $Criptomoneda;
    }

    /**
     * Ejecutar una consulta sql y devolver el resultado como un arreglo
     *
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     */
    protected function getRow($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        if (count($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }


    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ret arreglo asociativo
     */
    protected function getList($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $ret[$i] = $this->readRow($tab[$i]);
        }
        return $ret;
    }


    /**
     * Ejecutar una consulta sql
     *
     *
     * @param string $sql consulta sql
     *
     * @return array $ resultado de la ejecución
     */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }


    /**
     * Ejecutar una consulta sql
     *
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }


    /**
     * Ejecutar una consulta sql como insert
     *
     *
     * @param string $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     */
    protected function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
    }


    /**
     * Ejecutar una consulta sql como update
     *
     * @param string $sqlQuery consulta sql
     * @return array $ resultado de la ejecución
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }
}
