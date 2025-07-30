<?php

namespace Backend\dto;

use Backend\mysql\TasaCambioMySqlDAO;
use Backend\sql\Transaction;
use Exception;

/**
 * Clase 'TasaCambio'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'tasa_cambio'
 *
 * Ejemplo de uso:
 * $tasaCambio = new TasaCambio();
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025-02-03
 * @access public
 *
 */
class TasaCambio
{

  /**
   * Representación de la columna 'id' de la tabla 'tasa_cambio'
   * @var int
   */
  var $id;

  /**
   * Representación de la columna 'mandante' de la tabla 'tasa_cambio'
   * @var string
   */
  var $mandante;

  /**
   * Representación de la columna 'moneda_origen' de la tabla 'tasa_cambio'
   * @var string
   */
  var $monedaOrigen;

  /**
   * Representación de la columna 'moneda_destino' de la tabla 'tasa_cambio'
   * @var string
   */
  var $monedaDestino;

  /**
   * Representación de la columna 'tasa_cambio' de la tabla 'tasa_cambio'
   * @var string
   */
  var $tasaCambio;

  /**
   * Representación de la columna 'estado' de la tabla 'tasa_cambio'
   * @var string
   */
  var $estado;

  /**
   * Representación de la columna 'fecha_crea' de la tabla 'tasa_cambio'
   * @var string
   */
  var $fechaCrea;

  /**
   * Representación de la columna 'fecha_modif' de la tabla 'tasa_cambio'
   * @var string
   */
  var $fechaModif;

  /**
   * Representación de un objeto de transacción a la base de datos
   * @var Transaction
   */
  private $transaction;

  /**
   * Get the value of transaction
   */ 
  public function getTransaction()
  {
    return $this->transaction;
  }

  /**
   * Set the value of transaction
   * @return void
   */ 
  public function setTransaction($transaction)
  {
    $this->transaction = $transaction;
  }

  public function __construct(int $id = null)
  {
    if (!empty($id)) {

      $this->id = $id;
      $tasaCambioMySqlDAO = new TasaCambioMySqlDAO();
      $tasaCambio = $tasaCambioMySqlDAO->load($id);

      if (!empty($tasaCambio)) {
        $this->mandante = $tasaCambio->mandante;
        $this->monedaOrigen = $tasaCambio->monedaOrigen;
        $this->monedaDestino = $tasaCambio->monedaDestino;
        $this->tasaCambio = $tasaCambio->tasaCambio;
        $this->estado = $tasaCambio->estado;
        $this->fechaCrea = $tasaCambio->fechaCrea;
        $this->fechaModif = $tasaCambio->fechaModif;
      } else {
        throw new Exception("No existe " . get_class($this), 400);
      }
    }

    $this->transaction = new Transaction();
  }

  public function save()
  {
    if (empty($this->id)) {
      $this->insert();
    } else {
      $this->update();
    }
  }

  public function insert()
  {
    $tasaCambioMySqlDAO = new TasaCambioMySqlDAO();
    $this->id = $tasaCambioMySqlDAO->insert($this);
  }

  public function update()
  {
    $tasaCambioMySqlDAO = new TasaCambioMySqlDAO();
    $tasaCambioMySqlDAO->update($this);
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
   * @throws Exception si el TRM no existe
   * @access public
   */
  public function getTRMCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
  {

    $tasaCambioMySqlDAO = new TasaCambioMySqlDAO();

    $trm = $tasaCambioMySqlDAO->queryTRMCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

    if (!empty($trm)) {
      return $trm;
    } else {
      throw new Exception("No existe " . get_class($this), 200);
    }
  }
}
