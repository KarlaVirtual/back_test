<?php namespace Backend\dto;

use Backend\mysql\PaisMonedaMySqlDAO;
use Exception;

/**
 * Clase 'PaisMoneda'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'PaisMoneda'
 *
 * Ejemplo de uso:
 * $PaisMoneda = new PaisMoneda();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class PaisMoneda
{

    /**
     * Representación de la columna 'paismonedaId' de la tabla 'PaisMoneda'
     *
     * @var string
     */
    var $paismonedaId;

    /**
     * Representación de la columna 'paisId' de la tabla 'PaisMoneda'
     *
     * @var string
     */
    var $paisId;

    /**
     * Representación de la columna 'moneda' de la tabla 'PaisMoneda'
     *
     * @var string
     */
    var $moneda;


    /**
     * Constructor de clase
     *
     *
     * @param String pais pais
     *
     *
     * @return no
     * @throws Exception si el paismoneda no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($pais = "", $moneda = "")
    {
        if ($pais != "" && $moneda != "") {

            $PaisMonedaMySqlDAO = new PaisMonedaMySqlDAO();

            $PaisMoneda = $PaisMonedaMySqlDAO->loadByPaisAndMoneda($pais, $moneda);

            $this->success = false;

            if ($PaisMoneda != null && $PaisMoneda != "") {

                $this->paismonedaId = $PaisMoneda->paismonedaId;
                $this->paisId = $PaisMoneda->paisId;
                $this->moneda = $PaisMoneda->moneda;

            } else {
                throw new Exception("No existe " . get_class($this), "97");

            }

        } else
            if ($pais != "") {

                $PaisMonedaMySqlDAO = new PaisMonedaMySqlDAO();

                $PaisMoneda = $PaisMonedaMySqlDAO->loadByPais($pais);

                $this->success = false;

                if ($PaisMoneda != null && $PaisMoneda != "") {

                    $this->paismonedaId = $PaisMoneda->paismonedaId;
                    $this->paisId = $PaisMoneda->paisId;
                    $this->moneda = $PaisMoneda->moneda;

                } else {
                    throw new Exception("No existe " . get_class($this), "97");

                }

            }


    }

}

?>