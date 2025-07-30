<?php namespace Backend\dto;
use Backend\mysql\UsuarioSaldoMySqlDAO;/**
* Clase 'UsuarioSaldo'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioSaldo'
*
* Ejemplo de uso:
* $UsuarioSaldo = new UsuarioSaldo();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class UsuarioSaldo
{

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $usuarioId;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'fecha' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $fecha;

    /**
    * Representación de la columna 'saldoRecarga' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoRecarga;

    /**
    * Representación de la columna 'saldoApuestas' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoApuestas;

    /**
    * Representación de la columna 'saldoPremios' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoPremios;

    /**
    * Representación de la columna 'saldoNotaretPagadas' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoNotaretPagadas;

    /**
    * Representación de la columna 'saldoNotaretPend' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoNotaretPend;

    /**
    * Representación de la columna 'saldoAjustesEntrada' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoAjustesEntrada;

    /**
    * Representación de la columna 'saldoAjustesSalida' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoAjustesSalida;

    /**
    * Representación de la columna 'saldoInicial' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoInicial;

    /**
    * Representación de la columna 'saldoFinal' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoFinal;

    /**
    * Representación de la columna 'saldoBono' de la tabla 'UsuarioSaldo'
    *
    * @var string
    */
	var $saldoBono;


    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioSaldosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $puntoventas = '', $conRecargas = false, $conCount = false, $fechaInicio = "", $fechaFinal = "")
    {

        $UsuarioHistorialMySqlDAO = new UsuarioSaldoMySqlDAO();

        $clasificadores = $UsuarioHistorialMySqlDAO->queryUsuarioSaldosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $puntoventas, $conRecargas, $conCount, $fechaInicio, $fechaFinal);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "80");
        }

    }

    /**
     * Obtiene una lista de saldos de usuario personalizados para bodega.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los resultados.
     * @param int $limit Límite de resultados.
     * @param array $filters Filtros a aplicar.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de resultados (opcional).
     * @param string $puntoventas Puntos de venta (opcional).
     * @param bool $conRecargas Indica si se incluyen recargas (opcional).
     * @return array|null Resultados de la consulta.
     * @throws Exception Si no existen resultados.
     */
    public function getUsuarioSaldosCustomBodega($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $puntoventas = '', $conRecargas = false)
    {

        $UsuarioHistorialMySqlDAO = new UsuarioSaldoMySqlDAO();

        $clasificadores = $UsuarioHistorialMySqlDAO->queryUsuarioSaldosCustomBodega($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $puntoventas, $conRecargas);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "80");
        }

    }



}
?>