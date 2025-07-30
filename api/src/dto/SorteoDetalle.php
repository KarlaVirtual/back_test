<?php namespace Backend\dto;

use Backend\mysql\SorteoDetalleMySqlDAO;
use Exception;

/**
 * Clase 'SorteoDetalle'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'SorteoDetalle'
 *
 * Ejemplo de uso:
 * $SorteoDetalle = new SorteoDetalle();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class SorteoDetalle
{

    /**
     * Representación de la columna 'sorteodetalleId' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $sorteodetalleId;

    /**
     * Representación de la columna 'sorteoId' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $sorteoId;

    /**
     * Representación de la columna 'tipo' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $tipo;

    /**
     * Representación de la columna 'moneda' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $moneda;

    /**
     * Representación de la columna 'valor' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $valor;

    /**
     * Representación de la columna 'valor2' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $valor2;

    /**
     * Representación de la columna 'valor3' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $valor3;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $usucreaId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $fechaCrea;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $usumodifId;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'SorteoDetalle'
     *
     * @var string
     */
    public $fechaModif;

    /**
     * Representación de la columna 'descripcion' de la tabla 'SorteoDetalle'
     *
     * @var string
     */

    public $descripcion;

    /**
     * Representación de la columna 'fecha_sorteo' de la tabla 'SorteoDetalle'
     *
     * @var string
     */

    public $fechaSorteo;


    /**
     * Representación de la columna '$imagen_url' de la tabla 'SorteoDetalle'
     *
     * @var string
     */

    public $imagenUrl;

    /**
     * Representación de la columna '$imagen_url' de la tabla 'SorteoDetalle'
     *
     * @var string
     */

    public $estado;

    /**
     * Representación de la columna '$imagen_url' de la tabla 'SorteoDetalle'
     *
     * @var string
     */

    public $permiteGanador;

    /**
     * Representación de la columna '$imagen_url' de la tabla 'SorteoDetalle'
     *
     * @var string
     */

    public $jugadorExcluido;

    /**
     * Constructor de clase
     *
     *
     * @param String $sorteodetalleId id de sorteodetalle
     * @param String $sorteoId id del sorteo
     * @param String $tipo tipo
     * @param String $moneda moneda
     *
     * @return no
     * @throws Exception si SorteoDetalle no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($sorteodetalleId = "", $sorteoId = "", $tipo = "", $moneda = "", $valor = "")
    {

        if ($sorteodetalleId != "") {


            $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();

            $SorteoDetalle = $SorteoDetalleMySqlDAO->load($sorteodetalleId);


            $this->success = false;

            if ($SorteoDetalle != null && $SorteoDetalle != "") {
                $this->sorteodetalleId = $SorteoDetalle->sorteodetalleId;
                $this->sorteoId = $SorteoDetalle->sorteoId;
                $this->valor = $SorteoDetalle->valor;
                $this->valor2 = $SorteoDetalle->valor2;
                $this->valor3 = $SorteoDetalle->valor3;
                $this->tipo = $SorteoDetalle->tipo;
                $this->moneda = $SorteoDetalle->moneda;
                $this->descripcion = $SorteoDetalle->descripcion;

                $this->usucreaId = $SorteoDetalle->usucreaId;
                $this->fechaCrea = $SorteoDetalle->fechaCrea;
                $this->usumodifId = $SorteoDetalle->usumodifId;
                $this->fechaModif = $SorteoDetalle->fechaModif;
                $this->fechaSorteo = $SorteoDetalle->fechaSorteo;
                $this->imagenUrl = $SorteoDetalle->imagenUrl;
                $this->estado = $SorteoDetalle->estado;
                $this->permiteGanador = $SorteoDetalle->permiteGanador;
                $this->jugadorExcluido = $SorteoDetalle->jugadorExcluido;

            } else {
                throw new Exception("No existe " . get_class($this), "21");
            }
        }

        elseif ($sorteoId != "" AND $tipo!="" AND $moneda!="")
        {

            $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();

            $SorteoDetalle = $SorteoDetalleMySqlDAO->querybySorteoIdAndTipoAndMoneda($sorteoId, $tipo, $moneda);

            $SorteoDetalle = $SorteoDetalle[0];

            $this->success = false;

            if ($SorteoDetalle != null && $SorteoDetalle != "") {

                $this->sorteodetalleId = $SorteoDetalle->sorteodetalleId;
                $this->sorteoId = $SorteoDetalle->sorteoId;
                $this->valor = $SorteoDetalle->valor;
                $this->valor2 = $SorteoDetalle->valor2;
                $this->valor3 = $SorteoDetalle->valor3;

                $this->tipo = $SorteoDetalle->tipo;
                $this->moneda = $SorteoDetalle->moneda;

                $this->descripcion = $SorteoDetalle->descripcion;
                $this->usucreaId = $SorteoDetalle->usucreaId;
                $this->fechaCrea = $SorteoDetalle->fechaCrea;
                $this->usumodifId = $SorteoDetalle->usumodifId;
                $this->fechaModif = $SorteoDetalle->fechaModif;
                $this->fechaSorteo = $SorteoDetalle->fechaSorteo;
                $this->imagenUrl = $SorteoDetalle->imagenUrl;
                $this->estado = $SorteoDetalle->estado;
                $this->permiteGanador = $SorteoDetalle->permiteGanador;
                $this->jugadorExcluido = $SorteoDetalle->jugadorExcluido;


            } else {
                throw new Exception("No existe " . get_class($this), "21");
            }

        } else if ($sorteoId != "" and $tipo != "" and $valor != "") {


            $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();
            $SorteoDetalle = $SorteoDetalleMySqlDAO->queryBySorteoIdAndTipoAndValor($sorteoId, $tipo, $valor);
            $SorteoDetalle = $SorteoDetalle[0];

            $this->success = false;

            if ($SorteoDetalle != null && $SorteoDetalle != "") {


                $this->sorteodetalleId = $SorteoDetalle->sorteodetalleId;
                $this->sorteoId = $SorteoDetalle->sorteoId;
                $this->valor = $SorteoDetalle->valor;
                $this->valor2 = $SorteoDetalle->valor2;
                $this->valor3 = $SorteoDetalle->valor3;

                $this->tipo = $SorteoDetalle->tipo;
                $this->moneda = $SorteoDetalle->moneda;
                $this->descripcion = $SorteoDetalle->descripcion;

                $this->usucreaId = $SorteoDetalle->usucreaId;
                $this->fechaCrea = $SorteoDetalle->fechaCrea;
                $this->usumodifId = $SorteoDetalle->usumodifId;
                $this->fechaModif = $SorteoDetalle->fechaModif;
                $this->fechaSorteo = $SorteoDetalle->fechaSorteo;
                $this->imagenUrl = $SorteoDetalle->imagenUrl;
                $this->estado = $SorteoDetalle->estado;
                $this->permiteGanador = $SorteoDetalle->permiteGanador;
                $this->jugadorExcluido = $SorteoDetalle->jugadorExcluido;

            } else {
                throw new Exception("No existe " . get_class($this), "21");
            }

        } elseif ($sorteoId != "" and $tipo != "") {
            $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();

            $SorteoDetalle = $SorteoDetalleMySqlDAO->querybySorteoIdAndTipo($sorteoId, $tipo);

            $SorteoDetalle = $SorteoDetalle[0];

            $this->success = false;

            if ($SorteoDetalle != null && $SorteoDetalle != "") {
                $this->sorteodetalleId = $SorteoDetalle->sorteodetalleId;
                $this->sorteoId = $SorteoDetalle->sorteoId;
                $this->valor = $SorteoDetalle->valor;
                $this->valor2 = $SorteoDetalle->valor2;
                $this->valor3 = $SorteoDetalle->valor3;

                $this->tipo = $SorteoDetalle->tipo;
                $this->moneda = $SorteoDetalle->moneda;
                $this->descripcion = $SorteoDetalle->descripcion;

                $this->usucreaId = $SorteoDetalle->usucreaId;
                $this->fechaCrea = $SorteoDetalle->fechaCrea;
                $this->usumodifId = $SorteoDetalle->usumodifId;
                $this->fechaModif = $SorteoDetalle->fechaModif;
                $this->fechaSorteo = $SorteoDetalle->fechaSorteo;
                $this->imagenUrl = $SorteoDetalle->imagenUrl;
                $this->estado = $SorteoDetalle->estado;
                $this->permiteGanador = $SorteoDetalle->permiteGanador;
                $this->jugadorExcluido = $SorteoDetalle->jugadorExcluido;


            } else {
                throw new Exception("No existe " . get_class($this), "21");
            }

        }

    }

    /**
     * Obtiene el ID del detalle del sorteo.
     *
     * @return string
     */
    public function getSorteodetalleId()
    {
        return $this->sorteodetalleId;
    }

    /**
     * Establece el ID del detalle del sorteo.
     *
     * @param string $sorteodetalleId
     */
    public function setSorteodetalleId($sorteodetalleId)
    {
        $this->sorteodetalleId = $sorteodetalleId;
    }

    /**
     * Obtiene el ID del sorteo.
     *
     * @return string
     */
    public function getSorteoId()
    {
        return $this->sorteoId;
    }

    /**
     * Establece el ID del sorteo.
     *
     * @param string $sorteoId
     */
    public function setSorteoId($sorteoId)
    {
        $this->sorteoId = $sorteoId;
    }

    /**
     * Obtiene el tipo del detalle del sorteo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo del detalle del sorteo.
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene la moneda del detalle del sorteo.
     *
     * @return string
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Establece la moneda del detalle del sorteo.
     *
     * @param string $moneda
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    }

    /**
     * Obtiene el valor del detalle del sorteo.
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor del detalle del sorteo.
     *
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene el valor2 del detalle del sorteo.
     *
     * @return string
     */
    public function getValor2()
    {
        return $this->valor2;
    }

    /**
     * Establece el valor2 del detalle del sorteo.
     *
     * @param string $valor2
     */
    public function setValor2($valor2)
    {
        $this->valor2 = $valor2;
    }

    /**
     * Obtiene el valor3 del detalle del sorteo.
     *
     * @return string
     */
    public function getValor3()
    {
        return $this->valor3;
    }

    /**
     * Establece el valor3 del detalle del sorteo.
     *
     * @param string $valor3
     */
    public function setValor3($valor3)
    {
        $this->valor3 = $valor3;
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
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene el ID del usuario que modificó.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modificó.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
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
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene la descripción del detalle del sorteo.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Establece la descripción del detalle del sorteo.
     *
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtiene la fecha del sorteo.
     *
     * @return string
     */
    public function getFechaSorteo()
    {
        return $this->fechaSorteo;
    }

    /**
     * Establece la fecha del sorteo.
     *
     * @param string $fechaSorteo
     */
    public function setFechaSorteo($fechaSorteo)
    {
        $this->fechaSorteo = $fechaSorteo;
    }

    /**
     * Obtiene la URL de la imagen del detalle del sorteo.
     *
     * @return string
     */
    public function getImagenUrl()
    {
        return $this->imagenUrl;
    }

    /**
     * Establece la URL de la imagen del detalle del sorteo.
     *
     * @param string $imagenUrl
     */
    public function setImagenUrl($imagenUrl)
    {
        $this->imagenUrl = $imagenUrl;
    }

    /**
     * Obtiene el estado del detalle del sorteo.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el estado del detalle del sorteo.
     *
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene si se permite ganador en el detalle del sorteo.
     *
     * @return string
     */
    public function getPermiteGanador()
    {
        return $this->permiteGanador;
    }

    /**
     * Establece si se permite ganador en el detalle del sorteo.
     *
     * @param string $permiteGanador
     */
    public function setPermiteGanador($permiteGanador)
    {
        $this->permiteGanador = $permiteGanador;
    }


    /**
     * Realizar una consulta en la tabla de detalles de sorteos 'SorteoDetalle'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     *
     * @return Array resultado de la consulta
     * @throws Exception si los sorteos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getSorteoDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();

        $sorteos = $SorteoDetalleMySqlDAO->querySorteoDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($sorteos != null && $sorteos != "") {
            return $sorteos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


}
