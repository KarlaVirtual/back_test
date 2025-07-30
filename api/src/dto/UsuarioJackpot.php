<?php namespace Backend\dto;

use Backend\mysql\UsuarioJackpotMySqlDAO;
use Exception;
/**
 * Object represents table 'clasificador'
 *
 * @author: DT
 * @date: 2017-09-06 18:52
 */
class UsuarioJackpot
{

    /**
     * @var string Representación de la columna 'usujackpot_id' en la tabla 'usuario_jackpot'
     */
    var $usujackpotId;

    /**
     * @var string Representación de la columna 'jackpot_id' en la tabla 'usuario_jackpot'
     */
    var $jackpotId;

    /**
     * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_jackpot'
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'valor' en la tabla 'usuario_jackpot'
     */
    var $valor;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_jackpot'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_jackpot'
     */
    var $usumodifId;

    /**
     * @var string Representación de la columna 'externo_id' en la tabla 'usuario_jackpot'
     */
    var $externoId;

    /**
     * @var string Representación de la columna 'apostado' en la tabla 'usuario_jackpot'
     */
    var $apostado;

    /**
     * @var string Representación de la columna 'valor_premio' en la tabla 'usuario_jackpot'
     */
    var $valorPremio;


    /**
     * UsuarioJackpot constructor.
     * @param $usujackpotId

     * @param $codigo
     */
    public function __construct($usujackpotId = "", $jackpotId = "", $usuarioId = "")
    {

        $UsuarioJackpotMySqlDAO = new UsuarioJackpotMySqlDAO();
        if ($usujackpotId != "") {
            $UsuarioJackpot = $UsuarioJackpotMySqlDAO->load($usujackpotId);


            if ($UsuarioJackpot != null && $UsuarioJackpot != "") {
                $this->usujackpotId = $UsuarioJackpot->usujackpotId;
                $this->jackpotId = $UsuarioJackpot->jackpotId;
                $this->usuarioId = $UsuarioJackpot->usuarioId;
                $this->valor = $UsuarioJackpot->valor;
                $this->usucreaId = $UsuarioJackpot->usucreaId;
                $this->usumodifId = $UsuarioJackpot->usumodifId;
                $this->externoId = $UsuarioJackpot->externoId;
                $this->apostado = $UsuarioJackpot->apostado;
                $this->valorPremio = $UsuarioJackpot->valorPremio;
            } else {
                throw new Exception("No existe " . get_class($this), "80");
            }

        }
        elseif (!empty($jackpotId) && !empty($usuarioId)) {
            $UsuarioJackpot = $UsuarioJackpotMySqlDAO->loadByJackpotIdAndUserId($jackpotId, $usuarioId);

            if (!empty($UsuarioJackpot)) {
                foreach ($UsuarioJackpot as $propiedad => $valor) {
                    $this->$propiedad = $valor;
                }
            }
            else throw new Exception("No existe " . get_class($this), "80");
        }
    }


/**
     * Obtiene el ID del usuario jackpot.
     *
     * @return string
     */
    public function getUsujackpotId()
    {
        return $this->usujackpotId;
    }

    /**
     * Establece el ID del usuario jackpot.
     *
     * @param string $usujackpotId
     */
    public function setUsujackpotId($usujackpotId)
    {
        $this->usujackpotId = $usujackpotId;
    }

    /**
     * Obtiene el ID del jackpot.
     *
     * @return string
     */
    public function getJackpotId()
    {
        return $this->jackpotId;
    }

    /**
     * Establece el ID del jackpot.
     *
     * @param string $jackpotId
     */
    public function setJackpotId($jackpotId)
    {
        $this->jackpotId = $jackpotId;
    }

    /**
     * Obtiene el valor apostado.
     *
     * @return string
     */
    public function getApostado()
    {
        return $this->apostado;
    }

    /**
     * Establece el valor apostado.
     *
     * @param string $apostado
     */
    public function setApostado($apostado)
    {
        $this->apostado = $apostado;
    }

    /**
     * Obtiene el valor del premio.
     *
     * @return string
     */
    public function getValorPremio()
    {
        return $this->valorPremio;
    }

    /**
     * Establece el valor del premio.
     *
     * @param string $valorPremio
     */
    public function setValorPremio($valorPremio)
    {
        $this->valorPremio = $valorPremio;
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
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
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
     * Obtiene el ID del usuario modificador.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario modificador.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
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
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene el ID externo.
     *
     * @return string
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Establece el ID externo.
     *
     * @param string $externoId
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }


    /**
     * Obtiene un conjunto personalizado de datos de UsuarioJackpot.
     *
     * @param string $select Columnas a seleccionar en la consulta.
     * @param string $sidx Índice de la columna para ordenar.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de los resultados.
     * @param array $joins Joins adicionales para la consulta.
     * @return array Conjunto de datos de UsuarioJackpot.
     * @throws Exception Si no se encuentran resultados.
     */
    public function getUsuarioJackpotCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping, $joins = [])
    {

        $UsuarioJackpotMySqlDAO = new UsuarioJackpotMySqlDAO();

        $clasificadores = $UsuarioJackpotMySqlDAO->queryUsuarioJackpotCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping, $joins);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "80");
        }

    }

    /**
     * Obtiene una lista personalizada de usuarios de Jackpot.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Número máximo de registros a devolver.
     * @param string $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de resultados.
     * 
     * @return array Lista de usuarios de Jackpot que cumplen con los criterios especificados.
     * @throws Exception Si no se encuentran resultados.
     */
    public function getUsuarioJackpotCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping)
    {

        $UsuarioJackpotMySqlDAO = new UsuarioJackpotMySqlDAO();

        $clasificadores = $UsuarioJackpotMySqlDAO->queryUsuarioJackpotCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "80");
        }

    }


}

?>
