<?php

namespace Backend\dto;

/**
 * Clase 'WebsocketNotificacion'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'websocket_notificaciones'
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025-02-19
 * @access public
 *
 */

use Backend\mysql\WebsocketNotificacionMySqlDAO;
use Backend\sql\Transaction;
use Exception;

class WebsocketNotificacion
{

  private int $websocketnotificacionId;

  private string $tipo;

  private string $canal;

  private string $mensaje;

  private string $estado;

  private string $usuarioId;

  private string $valor;

  private string $fecha_exp;

  /**
   * Get the value of websocketnotificacionId
   */
  public function getWebsocketnotificacionId()
  {
    return $this->websocketnotificacionId;
  }

  /**
   * Set the value of websocketnotificacionId
   *
   * @return  self
   */
  public function setWebsocketnotificacionId($websocketnotificacionId)
  {
    $this->websocketnotificacionId = $websocketnotificacionId;

    return $this;
  }

  /**
   * Get the value of tipo
   */
  public function getTipo()
  {
    return $this->tipo;
  }

  /**
   * Set the value of tipo
   *
   * @return  self
   */
  public function setTipo($tipo)
  {
    $this->tipo = $tipo;

    return $this;
  }

  /**
   * Get the value of canal
   */
  public function getCanal()
  {
    return $this->canal;
  }

  /**
   * Set the value of canal
   *
   * @return  self
   */
  public function setCanal($canal)
  {
    $this->canal = $canal;

    return $this;
  }

  /**
   * Get the value of mensaje
   */
  public function getMensaje()
  {
    return $this->mensaje;
  }

  /**
   * Set the value of mensaje
   *
   * @return  self
   */
  public function setMensaje($mensaje)
  {
    $this->mensaje = $mensaje;

    return $this;
  }

  /**
   * Get the value of estado
   */
  public function getEstado()
  {
    return $this->estado;
  }

  /**
   * Set the value of estado
   *
   * @return  self
   */
  public function setEstado($estado)
  {
    $this->estado = $estado;

    return $this;
  }

  /**
   * Get the value of usuarioId
   */
  public function getUsuarioId()
  {
    return $this->usuarioId;
  }

  /**
   * Set the value of usuarioId
   *
   * @return  self
   */
  public function setUsuarioId($usuarioId)
  {
    $this->usuarioId = $usuarioId;

    return $this;
  }

  /**
   * Get the value of valor
   */ 
  public function getValor()
  {
    return $this->valor;
  }

  /**
   * Set the value of valor
   *
   * @return  self
   */ 
  public function setValor($valor)
  {
    $this->valor = $valor;

    return $this;
  }

  /**
   * Get the value of fecha_exp
   */ 
  public function getFecha_exp()
  {
    return $this->fecha_exp;
  }

  /**
   * Set the value of fecha_exp
   *
   * @return  self
   */ 
  public function setFecha_exp($fecha_exp)
  {
    $this->fecha_exp = $fecha_exp;

    return $this;
  }

  public function __construct(int $id = null)
  {
    if (!empty($id)) {
      $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO();
      $websocketNotificacion = $websocketNotificacionMySqlDAO->load($id);
      if (!empty($websocketNotificacion)) {
        $this->websocketnotificacionId = $websocketNotificacion->getWebsocketnotificacionId();
        $this->tipo = $websocketNotificacion->getTipo();
        $this->canal = $websocketNotificacion->getCanal();
        $this->mensaje = $websocketNotificacion->getMensaje();
        $this->estado = $websocketNotificacion->getEstado();
        $this->usuarioId = $websocketNotificacion->getUsuarioId();
        $this->valor = $websocketNotificacion->getValor();
        $this->fecha_exp = $websocketNotificacion->getFecha_exp();
      } else {
        throw new Exception("No existe " . get_class($this), 400);
      }
    }
  }

  public function queryWebsocketNotificacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
  {
    $WebsocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO();

    $WebsocketNotifacion = $WebsocketNotificacionMySqlDAO->queryWebsocketNotificacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

    if ($WebsocketNotifacion != null && $WebsocketNotifacion != "") {
      return $WebsocketNotifacion;
    } else {
      throw new Exception("No existe " . get_class($this), "01");
    }
  }
}
