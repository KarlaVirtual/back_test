<?php

namespace Backend\mysql;

use Backend\dao\WebsocketNotificacionesDAO;
use Backend\dto\WebsocketNotificacion;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\sql\Transaction;

class WebsocketNotificacionMySqlDAO implements WebsocketNotificacionesDAO
{

  /**
   * Representación de un objeto de transacción a la base de datos
   * @var Transaction|string
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


  public function __construct($transaction = "")
  {
    if ($transaction == "") {
      $transaction = new Transaction();
      $this->transaction = $transaction;
    } else {
      $this->transaction = $transaction;
    }
  }

  /**
   * Obtener el registro condicionado por la llave primaria que se pasa como parámetro
   * @param int $websocketnotificacion_id llave primaria
   * @return WebsocketNotificacion
   */
  public function load($id)
  {
    $sql = 'SELECT * FROM websocket_notificacion WHERE websocketnotificacion_id = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($id);
    return $this->getRow($sqlQuery);
  }

  /**
   * Realizar una consulta en la tabla de websocket_notificacion de una manera personalizada
   *
   * @param string $select campos de consulta
   * @param string $sidx columna para ordenar
   * @param string $sord orden los datos asc | desc
   * @param string $start inicio de la consulta
   * @param string $limit limite de la consulta
   * @param string $filters condiciones de la consulta 
   * @param boolean $searchOn utilizar los filtros o no
   *
   * @return array $json resultado de la consulta
   *
   */
  public function queryWebsocketNotificacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
  {

    $where = " where 1=1 ";

    if ($searchOn) {
      $filters = json_decode($filters);
      $whereArray = array();
      $rules = $filters->rules;
      $groupOperation = $filters->groupOp;

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

    $sql = 'SELECT count(*) count FROM websocket_notificacion ' . $where;

    $sqlQuery = new SqlQuery($sql);

    $count = $this->execute2($sqlQuery);

    $sql = 'SELECT ' . $select . '  FROM websocket_notificacion ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

    $sqlQuery = new SqlQuery($sql);

    $result = $this->execute2($sqlQuery);

    $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

    return $json;
  }

  /**
   * Insertar un registro en la base de datos
   * @param WebsocketNotificacion $websocketNotificacion
   */
  public function insert($websocketNotificacion)
  {
    $sql = 'INSERT INTO websocket_notificacion (tipo, canal, mensaje, estado, usuario_id, valor, fecha_exp) VALUES (?, ?, ?, ?, ?, ?, ?)';
    $sqlQuery = new SqlQuery($sql);

    $sqlQuery->set($websocketNotificacion->getTipo());
    $sqlQuery->set($websocketNotificacion->getCanal());
    $sqlQuery->set($websocketNotificacion->getMensaje());
    $sqlQuery->set($websocketNotificacion->getEstado());
    $sqlQuery->setNumber($websocketNotificacion->getUsuarioId());
    $sqlQuery->setNumber($websocketNotificacion->getValor());
    $sqlQuery->set($websocketNotificacion->getFecha_exp());
    return $this->executeInsert($sqlQuery);
  }

  /**
   * Editar un registro en la base de datos
   * @param WebsocketNotificacion $websocketNotificacion
   */
  public function update($websocketNotificacion, $where = "")
  {
    $sql = "UPDATE websocket_notificacion SET tipo = ?, canal = ?, mensaje = ?, estado = ?, usuario_id = ?, valor = ?, fecha_exp = ? WHERE websocketnotificacion_id = ?  $where ;";
    $sqlQuery = new SqlQuery($sql);

    $sqlQuery->set($websocketNotificacion->getTipo());
    $sqlQuery->set($websocketNotificacion->getCanal());
    $sqlQuery->set($websocketNotificacion->getMensaje());
    $sqlQuery->set($websocketNotificacion->getEstado());
    $sqlQuery->setNumber($websocketNotificacion->getUsuarioId());
    $sqlQuery->setNumber($websocketNotificacion->getValor());
    $sqlQuery->set($websocketNotificacion->getFecha_exp());
    $sqlQuery->setNumber($websocketNotificacion->getWebsocketnotificacionId());
    return $this->executeUpdate($sqlQuery);
  }

  /**
   * Ejecutar una consulta sql
   * 
   * @param string $sqlQuery consulta sql
   * @return array $resultado de la ejecución
   * @access protected
   *
   */
  protected function execute2($sqlQuery)
  {
    return QueryExecutor::execute2($this->transaction, $sqlQuery);
  }

  /**
   * Ejecutar una consulta sql y devolver el resultado como un arreglo
   * 
   * @access protected
   * @param string $sqlQuery consulta sql
   * @return WebsocketNotificacion
   *
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
   * Crear y devolver un objeto del tipo WebsocketNotificacion con los valores de una consulta sql
   * 
   * @access protected
   * @param array $row arreglo asociativo
   * @return WebsocketNotificacion $websocketNotification
   */
  protected function readRow($row)
  {
    $websocketNotification = new WebsocketNotificacion();

    $websocketNotification->setWebsocketnotificacionId($row['websocketnotificacion_id']);
    $websocketNotification->setTipo($row['tipo']);
    $websocketNotification->setCanal($row['canal']);
    $websocketNotification->setMensaje($row['mensaje']);
    $websocketNotification->setEstado($row['estado']);
    $websocketNotification->setUsuarioId($row['usuario_id']);
    $websocketNotification->setValor($row['valor']);
    $websocketNotification->setFecha_exp($row['fecha_exp']);

    return $websocketNotification;
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
}
