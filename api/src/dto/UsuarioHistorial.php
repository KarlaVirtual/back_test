<?php namespace Backend\dto;

use Backend\mysql\UsuarioHistorialMySqlDAO;
use Exception;
/**
 * Object represents table 'clasificador'
 *
 * @author: DT
 * @date: 2017-09-06 18:52
 */
class UsuarioHistorial
{

    /**
     * @var string Representación de la columna 'usu_historial_id' en la tabla 'usuario_historial'
     */
    var $usuHistorialId;

    /**
     * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_historial'
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'descripcion' en la tabla 'usuario_historial'
     */
    var $descripcion;

    /**
     * @var string Representación de la columna 'movimiento' en la tabla 'usuario_historial'
     */
    var $movimiento;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_historial'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_historial'
     */
    var $usumodifId;

    /**
     * @var string Representación de la columna 'tipo' en la tabla 'usuario_historial'
     */
    var $tipo;

    /**
     * @var string Representación de la columna 'valor' en la tabla 'usuario_historial'
     */
    var $valor;

    /**
     * @var string Representación de la columna 'externo_id' en la tabla 'usuario_historial'
     */
    var $externoId;

    /**
     * @var string Representación de la columna 'customs' en la tabla 'usuario_historial'
     */
    var $customs;

    /**
     * UsuarioHistorial constructor.
     * @param $usuHistorialId

     * @param $codigo
     */
    public function __construct($usuHistorialId = "", $codigo = "")
    {

        if ($usuHistorialId != "") {

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();

            $UsuarioHistorial = $UsuarioHistorialMySqlDAO->load($usuHistorialId);


            if ($UsuarioHistorial != null && $UsuarioHistorial != "") {
                $this->usuHistorialId = $UsuarioHistorial->usuHistorialId;
                $this->usuarioId = $UsuarioHistorial->usuarioId;
                $this->descripcion = $UsuarioHistorial->descripcion;
                $this->movimiento = $UsuarioHistorial->movimiento;
                $this->usucreaId = $UsuarioHistorial->usucreaId;
                $this->usumodifId = $UsuarioHistorial->usumodifId;
                $this->tipo = $UsuarioHistorial->tipo;
                $this->valor = $UsuarioHistorial->valor;
                $this->externoId = $UsuarioHistorial->externoId;
                $this->customs = $UsuarioHistorial->customs;

            } else {
                throw new Exception("No existe " . get_class($this), "80");
            }

        } elseif ($codigo != "") {
            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();

            $UsuarioHistorial = $UsuarioHistorialMySqlDAO->queryByAbreviado($codigo);

            $UsuarioHistorial = $UsuarioHistorial[0];

            if ($UsuarioHistorial != null && $UsuarioHistorial != "") {
                $this->usuHistorialId = $UsuarioHistorial->usuHistorialId;
                $this->usuarioId = $UsuarioHistorial->usuarioId;
                $this->descripcion = $UsuarioHistorial->descripcion;
                $this->movimiento = $UsuarioHistorial->movimiento;
                $this->usucreaId = $UsuarioHistorial->usucreaId;
                $this->usumodifId = $UsuarioHistorial->usumodifId;
                $this->tipo = $UsuarioHistorial->tipo;
                $this->valor = $UsuarioHistorial->valor;
                $this->externoId = $UsuarioHistorial->externoId;
                $this->customs = $UsuarioHistorial->customs;
            } else {
                throw new Exception("No existe " . get_class($this), "80");
            }
        }
    }


/**
     * Obtiene el ID del usuario.
     *
     * @return string El ID del usuario.
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param string $usuarioId El ID del usuario.
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene la descripción.
     *
     * @return string La descripción.
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Establece la descripción.
     *
     * @param string $descripcion La descripción.
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtiene el movimiento.
     *
     * @return string El movimiento.
     */
    public function getMovimiento()
    {
        return $this->movimiento;
    }

    /**
     * Establece el movimiento.
     *
     * @param string $movimiento El movimiento.
     */
    public function setMovimiento($movimiento)
    {
        $this->movimiento = $movimiento;
    }

    /**
     * Obtiene el ID del usuario que creó el registro.
     *
     * @return string El ID del usuario que creó el registro.
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que creó el registro.
     *
     * @param string $usucreaId El ID del usuario que creó el registro.
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el ID del usuario que modificó el registro.
     *
     * @return string El ID del usuario que modificó el registro.
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modificó el registro.
     *
     * @param string $usumodifId El ID del usuario que modificó el registro.
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el tipo.
     *
     * @return string El tipo.
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo.
     *
     * @param string $tipo El tipo.
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el valor.
     *
     * @return string El valor.
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor.
     *
     * @param string $valor El valor.
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene el ID externo.
     *
     * @return string El ID externo.
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Establece el ID externo.
     *
     * @param string $externoId El ID externo.
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }

    /**
     * Obtiene el ID del historial de usuario.
     *
     * @return string El ID del historial de usuario.
     */
    public function getUsuHistorialId()
    {
        return $this->usuHistorialId;
    }

    /**
     * Obtiene los datos personalizados.
     *
     * @return string Los datos personalizados.
     */
    public function getCustoms()
    {
        return $this->customs;
    }

    /**
     * Establece los datos personalizados.
     *
     * @param string $customs Los datos personalizados.
     */
    public function setCustoms($customs)
    {
        $this->customs = $customs;
    }


    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioHistorialsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withTimestamp=false,$fechaInicio="",$fechaFinal="")
    {

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();

        $clasificadores = $UsuarioHistorialMySqlDAO->queryUsuarioHistorialesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withTimestamp,$fechaInicio,$fechaFinal);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "80");
        }

    }

    
    /**
     * Ejecuta una consulta SQL utilizando el DAO de UsuarioHistorialMySql.
     *
     * @param mixed $transaccion La transacción a utilizar para la consulta.
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la consulta SQL, decodificado como un objeto.
     */
    public function execQuery($transaccion, $sql)
    {

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaccion);
        $return = $UsuarioHistorialMySqlDAO->querySQL($sql);

        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }



}

?>
