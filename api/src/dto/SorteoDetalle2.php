<?php namespace Backend\dto;

use backend\dto\SorteoInterno2;
use Backend\mysql\SorteoDetalle2MySqlDAO;
use Exception;


/**
 * Clase 'SorteoDetalle2'
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
class SorteoDetalle2
{
    /**
     * Representación de la columna 'sorteodetalleId' de la tabla 'SorteoDetalle'
     *
     * @var string
     */

    public $sorteodetalle2Id;

    /**
     * Representación de la columna 'sorteoId' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */


    public $sorteo2Id;

    /**
     * Representación de la columna 'tipo' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $tipo;


    /**
     * Representación de la columna 'moneda' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $moneda;

    /**
     * Representación de la columna 'valor' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $valor;

    /**
     * Representación de la columna 'fecha_crea' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $fechaCrea;

    /**
     * Representación de la columna 'valor2' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */


    public $valor2;


    /**
     * Representación de la columna 'valor3' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $valor3;

    /**
     * Representación de la columna 'usucrea_id' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */


    public $usucreaId;


    /**
     * Representación de la columna 'usumodif_id' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */


    public $usumodifId;

    /**
     * Representación de la columna 'fecha_modif' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $fechaModif;

    /**
     * Representación de la columna 'descripcion' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $descripcion;

    /**
     * Representación de la columna 'fecha_sorteo' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $fechaSorteo;

    /**
     * Representación de la columna 'imagen_url' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $imagenUrl;


    /**
     * Representación de la columna 'estado' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $estado;


    /**
     * Representación de la columna 'permite_ganador' de la tabla 'SorteoDetalle2'
     *
     * @var string
     */

    public $permiteGanador;


    /**
     * Representación de la columna $jugador_excluido' de la tabla 'SorteoDetalle2'
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


    public function __construct($sorteodetalle2Id = '', $sorteo2Id = '', $tipo = '', $moneda = '')
    {
        if ($sorteodetalle2Id != "") {
            $sorteoDetalle2MySqlDAO = new SorteoDetalle2MySqlDAO();
            $sorteoDetalle2 = $sorteoDetalle2MySqlDAO->load($sorteodetalle2Id);

            $this->success = false;

            if ($sorteoDetalle2 != null && $sorteoDetalle2 != "") {

                $this->sorteodetalle2Id = $sorteoDetalle2->sorteodetalle2Id;
                $this->sorteo2Id = $sorteoDetalle2->sorteo2Id;
                $this->tipo = $sorteoDetalle2->tipo;
                $this->moneda = $sorteoDetalle2->moneda;
                $this->valor = $sorteoDetalle2->valor;
                $this->fechaCrea = $sorteoDetalle2->fechaCrea;
                $this->usucreaId = $sorteoDetalle2->usucreaId;
                $this->fechaModif = $sorteoDetalle2->fechaModif;
                $this->usumodifId = $sorteoDetalle2->usumodifId;
                $this->descripcion = $sorteoDetalle2->descripcion;
                $this->valor2 = $sorteoDetalle2->valor2;
                $this->valor3 = $sorteoDetalle2->valor3;
                $this->estado = $sorteoDetalle2->estado;
                $this->fechaSorteo = $sorteoDetalle2->fechaSorteo;
                $this->permiteGanador = $sorteoDetalle2->permiteGanador;
                $this->imagenUrl = $sorteoDetalle2->imagenUrl;
            } else {
                throw new Exception("No existe " . get_class($this), "100097");
            }
        } else if ($sorteo2Id and $tipo != "") {
            $SorteoDetalle2MySqlDAO = new SorteoDetalle2MySqlDAO();
            $sorteoDetalle2 = $SorteoDetalle2MySqlDAO->querybySorteoIdAndTipo($sorteo2Id, $tipo);

            $sorteoDetalle2 = $sorteoDetalle2[0];

            $this->success = false;

            if ($sorteoDetalle2 != "" and $sorteoDetalle2 != "Null" and $sorteoDetalle2 != null) {
                $this->sorteodetalle2Id = $sorteoDetalle2->sorteodetalle2Id;
                $this->sorteo2Id = $sorteoDetalle2->sorteo2Id;
                $this->tipo = $sorteoDetalle2->tipo;
                $this->moneda = $sorteoDetalle2->moneda;
                $this->valor = $sorteoDetalle2->valor;
                $this->fechaCrea = $sorteoDetalle2->fechaCrea;
                $this->usucreaId = $sorteoDetalle2->usucreaId;
                $this->fechaModif = $sorteoDetalle2->fechaModif;
                $this->usumodifId = $sorteoDetalle2->usumodifId;
                $this->descripcion = $sorteoDetalle2->descripcion;
                $this->valor2 = $sorteoDetalle2->valor2;
                $this->valor3 = $sorteoDetalle2->valor3;
                $this->estado = $sorteoDetalle2->estado;
                $this->fechaSorteo = $sorteoDetalle2->fechaSorteo;
                $this->permiteGanador = $sorteoDetalle2->permiteGanador;
                $this->imagenUrl = $sorteoDetalle2->imagenUrl;
            } else {
                throw new Exception("No existe " . get_class($this), "100097");
            }

        } else if ($sorteo2Id != '') {
            $sorteoDetalle2 = new SorteoDetalle2MySqlDAO();
            $datos = $sorteoDetalle2->queryBySorteoId($sorteo2Id);
            $datos = $datos[0];


            if ($datos != "" && $datos != null and $datos != "null") {
                $this->sorteodetalle2Id = $datos->sorteodetalle2Id;
                $this->sorteo2Id = $datos->sorteo2Id;
                $this->tipo = $datos->tipo;
                $this->moneda = $datos->moneda;
                $this->valor = $datos->valor;
                $this->fechaCrea = $datos->fechaCrea;
                $this->usucreaId = $datos->usucreaId;
                $this->fechaModif = $datos->fechaModif;
                $this->usumodifId = $datos->usumodifId;
                $this->descripcion = $datos->descripcion;
                $this->valor2 = $datos->valor2;
                $this->valor3 = $datos->valor3;
                $this->estado = $datos->estado;
                $this->fechaSorteo = $datos->fechaSorteo;
                $this->imagenUrl = $datos->imagenUrl;
                $this->permiteGanador = $datos->permiteGanador;
            } else {
                throw new Exception("No existe " . get_class($this), "100097");
            }
        } else if ($sorteo2Id != '' && $tipo != "") {
            $sorteoDetalle2 = new SorteoDetalle2MySqlDAO();
            $datos1 = $sorteoDetalle2->queryBytipo($tipo);
            if ($datos1 != "" and $datos1 != null and $datos1 != "null") {
                $this->sorteodetalle2Id = $datos1->sorteodetalle2Id;
                $this->sorteo2Id = $datos1->sorteo2Id;
                $this->tipo = $datos1->tipo;
                $this->moneda = $datos1->moneda;
                $this->valor = $datos1->valor;
                $this->fechaCrea = $datos1->fechaCrea;
                $this->usucreaId = $datos1->usucreaId;
                $this->fechaModif = $datos1->fechaModif;
                $this->usumodifId = $datos1->usumodifId;
                $this->descripcion = $datos1->descripcion;
                $this->valor2 = $datos1->valor2;
                $this->valor3 = $datos1->valor3;
                $this->estado = $datos1->estado;
                $this->fechaSorteo = $datos1->fechaSorteo;
                $this->imagenUrl = $datos1->imagenUrl;
                $this->permiteGanador = $datos1->permiteGanador;
                $this->jugadorExcluido = $datos1->jugadorExcluido;


            } else {
                throw new Exception("No existe " . get_class($this), "100097");
            }
        }
    }

    /**
     * Obtiene los detalles personalizados del sorteo.
     *
     * @param string $select Columnas a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los resultados.
     * @param int $limit Límite de resultados.
     * @param array $filters Filtros a aplicar.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de resultados (opcional).
     * @return array Datos del sorteo.
     * @throws Exception Si no existen datos del sorteo.
     */
    public function getSorteoDetalles2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $sorteoDetalle2MySqlDAO = new SorteoDetalle2MySqlDAO();
        $datos = $sorteoDetalle2MySqlDAO->querySorteoDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($datos != "" and $datos != "null" and $datos != null) {
            return $datos;
        } else {
            throw new Exception("No existe " . get_class($this), "100097");
        }

    }

    /**
     * Establece el ID del sorteo.
     *
     * @param string $valor ID del sorteo.
     */
    public function setSorteoId($valor)
    {
        $this->sorteo2Id = $valor;
    }

    /**
     * Obtiene el ID del sorteo.
     *
     * @return string ID del sorteo.
     */
    public function getSorteoId()
    {
        return $this->sorteo2Id;
    }

    /**
     * Establece el tipo.
     *
     * @param string $tipo Tipo.
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el tipo.
     *
     * @return string Tipo.
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece la moneda.
     *
     * @param string $moneda Moneda.
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    }

    /**
     * Obtiene la moneda.
     *
     * @return string Moneda.
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Establece el valor.
     *
     * @param string $valor Valor.
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene el valor.
     *
     * @return string Valor.
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Obtiene la fecha de creación.
     *
     * @return string Fecha de creación.
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece el ID del usuario creador.
     *
     * @param string $value ID del usuario creador.
     */
    public function setUsucreaId($value)
    {
        $this->usucreaId = $value;
    }

    /**
     * Obtiene el ID del usuario creador.
     *
     * @return string ID del usuario creador.
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Obtiene la fecha de modificación.
     *
     * @return string Fecha de modificación.
     */
    public function getfechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece el ID del usuario que modificó.
     *
     * @param string $valor ID del usuario que modificó.
     */
    public function setUsumodifId($valor)
    {
        $this->usumodifId = $valor;
    }

    /**
     * Obtiene el ID del usuario que modificó.
     *
     * @return string ID del usuario que modificó.
     */
    public function getUsumodif()
    {
        return $this->usumodifId;
    }

    /**
     * Establece la descripción.
     *
     * @param string $descripcion Descripción.
     */
    public function setDescription($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Establece el valor 2.
     *
     * @param string $value Valor 2.
     */
    public function setValor2($value)
    {
        $this->valor2 = $value;
    }

    /**
     * Obtiene el valor 2.
     *
     * @return string Valor 2.
     */
    public function getValor2()
    {
        return $this->valor2;
    }

    /**
     * Establece el valor 3.
     *
     * @param string $valor3 Valor 3.
     */
    public function setValor3($valor3)
    {
        $this->valor3 = $valor3;
    }

    /**
     * Obtiene el valor 3.
     *
     * @return string Valor 3.
     */
    public function getValor3()
    {
        return $this->valor3;
    }

    /**
     * Establece el estado.
     *
     * @param string $estado Estado.
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene el estado.
     *
     * @return string Estado.
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece la fecha del sorteo.
     *
     * @param string $date Fecha del sorteo.
     */
    public function setFechaSorteo($date)
    {
        $this->fechaSorteo = $date;
    }

    /**
     * Obtiene la fecha del sorteo.
     *
     * @return string Fecha del sorteo.
     */
    public function getFechaSorteo()
    {
        return $this->fechaSorteo;
    }

    /**
     * Establece la URL de la imagen.
     *
     * @param string $image URL de la imagen.
     */
    public function setImageUrl($image)
    {
        $this->imagenUrl = $image;
    }

    /**
     * Obtiene la URL de la imagen.
     *
     * @return string URL de la imagen.
     */
    public function getImage()
    {
        return $this->imagenUrl;
    }


}