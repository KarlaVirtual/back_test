<?php

namespace Backend\dto;

use Backend\mysql\UsuarioReferenteResumenMySqlDAO;
use Backend\sql\Transaction;

/**
 * Clase 'UsuarioReferenteResumen'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'usuario_referente_resumen'
 *
 * @author David Torres Rendón <david.torres@virtualsoft.tech>
 * @package ninguno
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioReferenteResumen
{
    /**
     * @var string Representación de la columna 'usurefresum_id' en la tabla 'usuario_referente_resumen'
     */
    var $usurefresumId;

    /**
     * @var string Representación de la columna 'tipo_usuario' en la tabla 'usuario_referente_resumen'
     */
    var $tipoUsuario;

    /**
     * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_referente_resumen'
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'valor' en la tabla 'usuario_referente_resumen'
     */
    var $valor;

    /**
     * @var string Representación de la columna 'fecha_crea' en la tabla 'usuario_referente_resumen'
     */
    var $fechaCrea;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_referente_resumen'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'fecha_modif' en la tabla 'usuario_referente_resumen'
     */
    var $fechaModif;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_referente_resumen'
     */
    var $usumodifId;

    /**
     * @var string Representación de la columna 'tipo' en la tabla 'usuario_referente_resumen'
     */
    var $tipo;

    /**
     * @var string Representación de la columna 'cantidad' en la tabla 'usuario_referente_resumen'
     */
    var $cantidad;

    /**
     * @var string Representación de la columna 'tipo_bono' en la tabla 'usuario_referente_resumen'
     */
    var $tipoBono;

    /**
     * @var string Representación de la columna 'tipo_condicion' en la tabla 'usuario_referente_resumen'
     */
    var $tipoCondicion;

    /**
     * Constructor de la clase UsuarioReferenteResumen.
     *
     * @param string $usurefresumId ID del resumen del usuario referente.
     * @param string $tipoUsuario Tipo de usuario.
     * @param string $usuarioId ID del usuario.
     * @param string $fechaCrea Fecha de creación.
     * @param string $tipo Tipo.
     * @param string $tipoCondicion Tipo de condición (opcional).
     *
     * @throws Exception Si no existe el UsuarioReferenteResumen.
     *
     * @return void
     */
    public function __construct($usurefresumId = '', $tipoUsuario = '', $usuarioId = '', $fechaCrea = '', $tipo = '', $tipoCondicion = '') {
        $UsuarioReferenteResumen = '';

        if ($usurefresumId) {
            $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO();
            $UsuarioReferenteResumen = $UsuarioReferenteResumenMySqlDAO->load($usurefresumId);

            if (!$UsuarioReferenteResumen) throw new Exception('No existe ' . get_class($this), 4020);
        }

        /** $tipoCondicion es nulo en la mayoría de registros por lo cual no se pide en el condicional pero sí se envía en la función de carga del objeto*/
        if ($tipoUsuario && $usuarioId && $fechaCrea && $tipo) {
            $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO();
            $UsuarioReferenteResumen = $UsuarioReferenteResumenMySqlDAO->loadByUserAndData($tipoUsuario, $usuarioId, $fechaCrea, $tipo, $tipoCondicion);

            if (!$UsuarioReferenteResumen) throw new Exception('No existe ' . get_class($this), 4020);
        }

        /** Toda propiedad que se agregue a la función readRow de MySqlDAO y se defina en el dto, es incializada por el foreach */
        foreach ($UsuarioReferenteResumen as $propiedad => $valor) {
            $this->$propiedad = $valor;
        }
    }


    /**
     * Obtiene el ID del resumen del usuario referente.
     *
     * @return string
     */
    public function getUsurefresumId()
    {
        return $this->usurefresumId;
    }

    /**
     * Establece el ID del resumen del usuario referente.
     *
     * @param string $usurefresumId
     * @return void
     */
    public function setUsurefresumId($usurefresumId): void
    {
        $this->usurefresumId = $usurefresumId;
    }

    /**
     * Obtiene el tipo de usuario.
     *
     * @return string
     */
    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    /**
     * Establece el tipo de usuario.
     *
     * @param string $tipoUsuario
     * @return void
     */
    public function setTipoUsuario($tipoUsuario): void
    {
        $this->tipoUsuario = $tipoUsuario;
    }

    /**
     * Obtiene el ID del usuario.
     *
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param string $usuarioId
     * @return void
     */
    public function setUsuarioId($usuarioId): void
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el valor.
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor.
     *
     * @param string $valor
     * @return void
     */
    public function setValor($valor): void
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene la fecha de creación.
     *
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece la fecha de creación.
     *
     * @param string $fechaCrea
     * @return void
     */
    public function setFechaCrea($fechaCrea): void
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene el ID del usuario que creó el registro.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que creó el registro.
     *
     * @param string $usucreaId
     * @return void
     */
    public function setUsucreaId($usucreaId): void
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene la fecha de modificación.
     *
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece la fecha de modificación.
     *
     * @param string $fechaModif
     * @return void
     */
    public function setFechaModif($fechaModif): void
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene el ID del usuario que modificó el registro.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modificó el registro.
     *
     * @param string $usumodifId
     * @return void
     */
    public function setUsumodifId($usumodifId): void
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo.
     *
     * @param string $tipo
     * @return void
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene la cantidad.
     *
     * @return string
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Establece la cantidad.
     *
     * @param string $cantidad
     * @return void
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * Obtiene el tipo de bono.
     *
     * @return string
     */
    public function getTipoBono()
    {
        return $this->tipoBono;
    }

    /**
     * Establece el tipo de bono.
     *
     * @param string $tipoBono
     * @return void
     */
    public function setTipoBono($tipoBono): void
    {
        $this->tipoBono = $tipoBono;
    }

    /**
     * Obtiene el tipo de condición.
     *
     * @return string
     */
    public function getTipoCondicion()
    {
        return $this->tipoCondicion;
    }

    /**
     * Establece el tipo de condición.
     *
     * @param string $tipoCondicion
     * @return void
     */
    public function setTipoCondicion($tipoCondicion): void
    {
        $this->tipoCondicion = $tipoCondicion;
    }



    /**
     * Obtiene una colección de Usuario Referente resumen.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Inicio de los registros a obtener.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $groupBy Indica si se debe agrupar por algún campo (opcional).
     * @param bool $onlyCount Indica si solo se debe obtener el conteo de registros (opcional).
     * @return array|bool Registros obtenidos de la consulta o false en caso de error.
     * @throws Exception Si no existen registros, lanza una excepción con el mensaje correspondiente.
     */
    public function getUsuarioReferenteResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $groupBy = false, $onlyCount = false)
    {
        $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO();
        $registros = $UsuarioReferenteResumenMySqlDAO->queryUsuarioReferenteResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $groupBy, $onlyCount);

        if ($registros) return $registros;
        else throw new Exception('No existe ' . get_class($this), 4020);
    }


    /**
     * Registra una apuesta en el resumen del día correspondiente
     *
     * @param Transaction $Transaction La transacción actual.
     * @param float $apostado El monto apostado.
     * @param string $tipoUsuario El tipo de usuario.
     * @param int $usuarioId El ID del usuario.
     * @param string $tipo El tipo de apuesta.
     * @param int $cantidad La cantidad de apuestas.
     *
     * @return bool Retorna true si el resumen de apuesta se registró correctamente.
     *
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function registrarResumenApuesta (Transaction $Transaction, $apostado, $tipoUsuario, $usuarioId, $tipo, $cantidad)
    {
        $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO($Transaction);
        $UsuarioReferenteResumen = new UsuarioReferenteResumen();

        //Verificando la existencia de un resumen vigente para el día en curso
        try {
            $UsuarioReferenteResumen = new UsuarioReferenteResumen('', $tipoUsuario, $usuarioId, date('Y-m-d'), $tipo);

            $apostado += $UsuarioReferenteResumen->getValor();
            $UsuarioReferenteResumen->setValor($apostado);
            $operation = $UsuarioReferenteResumen->getCantidad();
            $UsuarioReferenteResumen->setCantidad((int) $operation + $cantidad);

            $UsuarioReferenteResumenMySqlDAO->update($UsuarioReferenteResumen);
        }
        catch (Exception $e) {
            if ($e->getCode() != 4020) throw $e;

            $UsuarioReferenteResumen->setTipoUsuario($tipoUsuario);
            $UsuarioReferenteResumen->setUsuarioId($usuarioId);
            $UsuarioReferenteResumen->setValor($apostado);
            $UsuarioReferenteResumen->setTipo($tipo);
            $UsuarioReferenteResumen->setCantidad($cantidad);

            $UsuarioReferenteResumenMySqlDAO->insert($UsuarioReferenteResumen);
        }

        return true;
    }
}