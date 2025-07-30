<?php

namespace Backend\mysql;

use Backend\dao\TasaCambioDAO;
use Backend\dto\TasaCambio;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/** 
 * Clase 'TasaCambioMySqlDAO'
 * 
 * Esta clase provee las consultas del modelo o tabla 'tasa_cambio'
 * 
 * Ejemplo de uso: 
 * $tasaCambioMySqlDAO = new TasaCambioMySqlDAO();
 * 
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025/02/04
 * @access public
 */

class TasaCambioMySqlDAO implements TasaCambioDAO
{

  /**
   * Atributo Transaction transacción
   *
   * @var \Backend\sql\Transaction
   */
  private $transaction;

  /**
   * Obtener la transacción de un objeto
   *
   * @return Objeto Transaction transacción
   *
   */
  public function getTransaction()
  {
    return $this->transaction;
  }

  /**
   * Modificar el atributo transacción del objeto
   *
   * @param \Backend\sql\Transaction $transaction
   * @return void
   */
  public function setTransaction($transaction)
  {
    $this->transaction = $transaction;
  }

  /**
   * Obtiene la tasa de cambio por el id que se pasa como parámetro
   *
   * @param int $id
   * @return TasaCambio
   *
   */
  public function load($id)
  {
    $sql = 'SELECT * FROM tasa_cambio WHERE id = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->setNumber($id);
    return $this->getRow($sqlQuery);
  }

  /**
   * Realizar una consulta en la tabla de 'tasa_cambio' de una manera personalizada
   *
   * @param string $select campos de consulta
   * @param string $sidx columna para ordenar
   * @param string $sord orden los datos asc | desc
   * @param string $start inicio de la consulta
   * @param string $limit limite de la consulta
   * @param string $filters condiciones de la consulta 
   * @param boolean $searchOn utilizar los filtros o no
   *
   * @return string resultado de la consulta
   *
   */
  public function queryTRMCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
  {
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
        if (oldCount($whereArray) > 0) {
          $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
        } else {
          $where = "";
        }
      }
    }


    $sql = 'SELECT count(*) count FROM tasa_cambio ' . $where;

    $sqlQuery = new SqlQuery($sql);

    $count = $this->execute2($sqlQuery);

    $sql = 'SELECT ' . $select . '  FROM tasa_cambio ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

    $sqlQuery = new SqlQuery($sql);

    $result = $this->execute2($sqlQuery);

    $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

    return $json;
  }

  /**
   * Obtiene todas las monedas registradas para los diferentes países
   * @return array
   */
  public function getAllCurrency()
  {
    $sql = 'SELECT pm.pais_id AS id, pm.moneda, p.pais_nom AS pais FROM pais_moneda pm 
		INNER JOIN pais p on p.pais_id = pm.paismoneda_id ';
    $sqlQuery = new SqlQuery($sql);
    return $this->execute2($sqlQuery);
  }

  /**
   * Insertar un registro en la base de datos
   *
   * @param TasaCambio $tasaCambio
   * @return int $id del registro insertado
   */
  public function insert($tasaCambio)
  {
    $sql = 'INSERT INTO tasa_cambio (moneda_origen, moneda_destino, tasa_cambio, mandante, estado, fecha_crea) VALUES (?, ?, ?, ?, ?, ?)';
    $sqlQuery = new SqlQuery($sql);

    $sqlQuery->set($tasaCambio->monedaOrigen);
    $sqlQuery->set($tasaCambio->monedaDestino);
    $sqlQuery->setNumber($tasaCambio->tasaCambio);
    $sqlQuery->setNumber($tasaCambio->mandante);
    $sqlQuery->set($tasaCambio->estado);
    $sqlQuery->set($tasaCambio->fechaCrea);
    return $this->executeInsert($sqlQuery);
  }

  /**
   * Editar un registro en la base de datos
   *
   * @param TasaCambio $tasaCambio
   * @return boolean resultado de la consulta
   */
  public function update($tasaCambio)
  {
    $sql = 'UPDATE tasa_cambio SET moneda_origen = ?, moneda_destino = ?, tasa_cambio = ?, mandante = ?, estado = ?, fecha_modif = ? WHERE id = ?';
    $sqlQuery = new SqlQuery($sql);

    $sqlQuery->set($tasaCambio->monedaOrigen);
    $sqlQuery->set($tasaCambio->monedaDestino);
    $sqlQuery->set($tasaCambio->tasaCambio);
    $sqlQuery->setNumber($tasaCambio->mandante);
    $sqlQuery->set($tasaCambio->estado);
    $sqlQuery->set($tasaCambio->fechaModif);
    $sqlQuery->setNumber($tasaCambio->id);
    return $this->executeUpdate($sqlQuery);
  }

  /**
   * Función para validar si existen tasas de cambios comparando las monedas de origen y destino
   * @param string $sourceCurrency
   * @param string $destinationCurrency
   * @return array
   */
  public function validateIfExist($sourceCurrency, $destinationCurrency)
  {
    $sql = "SELECT id FROM tasa_cambio WHERE moneda_origen = '$sourceCurrency' AND moneda_destino = '$destinationCurrency';"; 
    $sqlQuery = new SqlQuery($sql);
    return $this->execute2($sqlQuery);
  }

  /**
   * Ejecutar una consulta sql y devolver el resultado como un arreglo
   * 
   * @param string $sqlQuery consulta sql
   * @return TasaCambio
   * @access protected
   */
  protected function getRow($sqlQuery)
  {
    $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
    if (oldCount($tab) == 0) {
      return null;
    }
    return $this->readRow($tab[0]);
  }

  /**
   * Procesa y devuelve un objeto del tipo TasaCambio
   * con los valores de una consulta sql
   *
   * @param array $row arreglo asociativo
   * @return TasaCambio $tasaCambio
   * @access protected
   */
  protected function readRow($row)
  {
    $tasaCambio = new TasaCambio();

    $tasaCambio->id = $row['id'];
    $tasaCambio->monedaOrigen = $row['moneda_origen'];
    $tasaCambio->monedaDestino = $row['moneda_destino'];
    $tasaCambio->tasaCambio = $row['tasa_cambio'];
    $tasaCambio->mandante = $row['mandante'];
    $tasaCambio->estado = $row['estado'];
    $tasaCambio->fechaCrea = $row['fecha_crea'];
    $tasaCambio->fechaModif = $row['fecha_modif'];

    return $tasaCambio;
  }

  /**
   * Ejecutar una consulta sql como insert
   * 
   * @param string $sqlQuery consulta sql
   * @return int id del registro insertado
   * @access protected
   */
  protected function executeInsert($sqlQuery)
  {
    return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
  }

  /**
   * Ejecutar una consulta sql como update
   *
   * @param string $sqlQuery consulta sql
   * @return bool resultado de la ejecución
   * @access protected
   */
  protected function executeUpdate($sqlQuery)
  {
    return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
  }

  /**
   * Ejecutar una consulta sql
   * 
   * @param string $sqlQuery consulta sql
   * @return bool resultado de la ejecución
   * @access protected
   */
  protected function execute2($sqlQuery)
  {
    return QueryExecutor::execute2($this->transaction, $sqlQuery);
  }
}
