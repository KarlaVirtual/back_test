<?php namespace Backend\dto;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Exception;

/**
 * Clase 'UsuarioRecarga'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioRecarga'
 *
 * Ejemplo de uso:
 * $UsuarioRecarga = new UsuarioRecarga();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioRecarga
{

    /**
     * Representación de la columna 'recargaId' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $recargaId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $fechaCrea;


    /**
     * Representación de la columna 'fechaElimina' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $fechaElimina;


    /**
     * Representación de la columna 'usueliminaId' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $usueliminaId;

    /**
     * Representación de la columna 'puntoventaId' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $puntoventaId;

    /**
     * Representación de la columna 'valor' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'impuesto' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $impuesto;

    /**
     * Representación de la columna 'porcenRegaloRecarga' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $porcenRegaloRecarga;

    /**
     * Representación de la columna 'mandante' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'pedido' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $pedido;

    /**
     * Representación de la columna 'dirIp' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $dirIp;

    /**
     * Representación de la columna 'promocionalId' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $promocionalId;

    /**
     * Representación de la columna 'valorPromocional' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $valorPromocional;

    /**
     * Representación de la columna 'host' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $host;

    /**
     * Representación de la columna 'porcenIva' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $porcenIva;

    /**
     * Representación de la columna 'mediopagoId' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $mediopagoId;

    /**
     * Representación de la columna 'valorIva' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $valorIva;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */
    var $version;

    /**
     * Representación de la columna 'tiene_comision' de la tabla 'UsuarioRecarga'
     *
     * @var string
     */

    var $tieneComision;

    public $transaction;


    /**
     * Constructor de clase
     *
     *
     * @param String $recargaId recargaId
     * @param String $usuarioId usuarioId
     *
     * @return no
     * @throws Exception si UsuarioRecarga no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($recargaId="", $usuarioId="", $transaction = "")
    {
        $this->transaction = $transaction;

        if ($recargaId != "")
        {

            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($this->transaction);

            $UsuarioRecarga = $UsuarioRecargaMySqlDAO->load($recargaId);

            $this->success = false;

            if ($UsuarioRecarga != null && $UsuarioRecarga != "")
            {

                $this->recargaId = $UsuarioRecarga->recargaId;
                $this->usuarioId = $UsuarioRecarga->usuarioId;
                $this->fechaCrea = $UsuarioRecarga->fechaCrea;
                $this->puntoventaId = $UsuarioRecarga->puntoventaId;
                $this->valor = $UsuarioRecarga->valor;
                $this->impuesto = $UsuarioRecarga->impuesto;
                $this->porcenRegaloRecarga = $UsuarioRecarga->porcenRegaloRecarga;
                $this->mandante = $UsuarioRecarga->mandante;
                $this->pedido = $UsuarioRecarga->pedido;
                $this->dirIp = $UsuarioRecarga->dirIp;
                $this->promocionalId = $UsuarioRecarga->promocionalId;
                $this->valorPromocional = $UsuarioRecarga->valorPromocional;
                $this->host = $UsuarioRecarga->host;
                $this->porcenIva = $UsuarioRecarga->porcenIva;
                $this->mediopagoId = $UsuarioRecarga->mediopagoId;
                $this->valorIva = $UsuarioRecarga->valorIva;
                $this->estado = $UsuarioRecarga->estado;
                $this->version = $UsuarioRecarga->version;
                $this->tieneComision = $UsuarioRecarga->tieneComision;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "44");
            }

        }
        elseif ($usuarioId != "")
        {

        }
    }





    /**
     * Obtener el campo recargaId de un objeto
     *
     * @return String recargaId recargaId
     *
     */
    public function getRecargaId()
    {
        return $this->recargaId;
    }

    /**
     * Modificar el campo 'recargaId' de un objeto
     *
     * @param String $recargaId recargaId
     *
     * @return no
     *
     */
    public function setRecargaId($recargaId)
    {
        $this->recargaId = $recargaId;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     *
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     *
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo puntoventaId de un objeto
     *
     * @return String puntoventaId puntoventaId
     *
     */
    public function getPuntoventaId()
    {
        return $this->puntoventaId;
    }

    /**
     * Modificar el campo 'puntoventaId' de un objeto
     *
     * @param String $puntoventaId puntoventaId
     *
     * @return no
     *
     */
    public function setPuntoventaId($puntoventaId)
    {
        $this->puntoventaId = $puntoventaId;
    }

    /**
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     *
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtener el campo impuesto de un objeto
     *
     * @return String impuesto impuesto
     *
     */
    public function getImpuesto()
    {
        return $this->impuesto;
    }

    /**
     * Modificar el campo 'impuesto' de un objeto
     *
     * @param String $impuesto impuesto
     *
     * @return no
     *
     */
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }

    /**
     * Obtener el campo porcenRegaloRecarga de un objeto
     *
     * @return String porcenRegaloRecarga porcenRegaloRecarga
     *
     */
    public function getPorcenRegaloRecarga()
    {
        return $this->porcenRegaloRecarga;
    }

    /**
     * Modificar el campo 'porcenRegaloRecarga' de un objeto
     *
     * @param String $porcenRegaloRecarga porcenRegaloRecarga
     *
     * @return no
     *
     */
    public function setPorcenRegaloRecarga($porcenRegaloRecarga)
    {
        $this->porcenRegaloRecarga = $porcenRegaloRecarga;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     *
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo pedido de un objeto
     *
     * @return String pedido pedido
     *
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * Modificar el campo 'pedido' de un objeto
     *
     * @param String $pedido pedido
     *
     * @return no
     *
     */
    public function setPedido($pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Obtener el campo dirIp de un objeto
     *
     * @return String dirIp dirIp
     *
     */
    public function getDirIp()
    {
        return $this->dirIp;
    }

    /**
     * Modificar el campo 'dirIp' de un objeto
     *
     * @param String $dirIp dirIp
     *
     * @return no
     *
     */
    public function setDirIp($dirIp)
    {
        $this->dirIp = $dirIp;
    }

    /**
     * Obtener el campo promocionalId de un objeto
     *
     * @return String promocionalId promocionalId
     *
     */
    public function getPromocionalId()
    {
        return $this->promocionalId;
    }

    /**
     * Modificar el campo 'promocionalId' de un objeto
     *
     * @param String $promocionalId promocionalId
     *
     * @return no
     *
     */
    public function setPromocionalId($promocionalId)
    {
        $this->promocionalId = $promocionalId;
    }

    /**
     * Obtener el campo valorPromocional de un objeto
     *
     * @return String valorPromocional valorPromocional
     *
     */
    public function getValorPromocional()
    {
        return $this->valorPromocional;
    }

    /**
     * Modificar el campo 'valorPromocional' de un objeto
     *
     * @param String $valorPromocional valorPromocional
     *
     * @return no
     *
     */
    public function setValorPromocional($valorPromocional)
    {
        $this->valorPromocional = $valorPromocional;
    }

    /**
     * Obtener el campo host de un objeto
     *
     * @return String host host
     *
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Modificar el campo 'host' de un objeto
     *
     * @param String $host host
     *
     * @return no
     *
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Obtener el campo porcenIva de un objeto
     *
     * @return String porcenIva porcenIva
     *
     */
    public function getPorcenIva()
    {
        return $this->porcenIva;
    }

    /**
     * Modificar el campo 'porcenIva' de un objeto
     *
     * @param String $porcenIva setPorcenIva
     *
     * @return no
     *
     */
    public function setPorcenIva($porcenIva)
    {
        $this->porcenIva = $porcenIva;
    }

    /**
     * Obtener el campo mediopagoId de un objeto
     *
     * @return String mediopagoId mediopagoId
     *
     */
    public function getMediopagoId()
    {
        return $this->mediopagoId;
    }

    /**
     * Modificar el campo 'mediopagoId' de un objeto
     *
     * @param String $mediopagoId mediopagoId
     *
     * @return no
     *
     */
    public function setMediopagoId($mediopagoId)
    {
        $this->mediopagoId = $mediopagoId;
    }

    /**
     * Obtener el campo valorIva de un objeto
     *
     * @return String valorIva valorIva
     *
     */
    public function getValorIva()
    {
        return $this->valorIva;
    }

    /**
     * Modificar el campo 'valorIva' de un objeto
     *
     * @param String $valorIva valorIva
     *
     * @return no
     *
     */
    public function setValorIva($valorIva)
    {
        $this->valorIva = $valorIva;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     *
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo version de un objeto
     *
     * @return String version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Modificar el campo 'version' de un objeto
     *
     * @param String $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Obtener el campo fechaElimina de un objeto
     *
     * @return String fechaElimina
     */
    public function getFechaElimina()
    {
        return $this->fechaElimina;
    }

    /**
     * Modificar el campo 'fechaElimina' de un objeto
     *
     * @param String $fechaElimina
     */
    public function setFechaElimina($fechaElimina)
    {
        $this->fechaElimina = $fechaElimina;
    }

    /**
     * Obtener el campo usueliminaId de un objeto
     *
     * @return String usueliminaId
     */
    public function getUsueliminaId()
    {
        return $this->usueliminaId;
    }

    /**
     * Modificar el campo 'usueliminaId' de un objeto
     *
     * @param String $usueliminaId
     */
    public function setUsueliminaId($usueliminaId)
    {
        $this->usueliminaId = $usueliminaId;
    }

    /**
     * Obtener el campo comision de un objeto
     *
     * @return String  comision
     *
     */

    public function getTieneComision(){
        return $this->tieneComision;
    }

    /**
     * enviar el campo comision de un objeto
     *
     * @return String comision
     *
     */

    public function setTieneComision($comision){
        $this->tieneComision = $comision;
    }



    /**
     * Realizar una consulta en la tabla de UsuarioBanner 'UsuarioBanner'
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
     *
     * @return Array resultado de la consulta
     * @throws Exception si los productos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarioRecargas($sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

        $Usuarios = $UsuarioRecargaMySqlDAO->queryUsuarioRecargas($sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($Usuarios != null && $Usuarios != "")
        {
            return $Usuarios;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "44");
        }


    }

    /**
     * Realizar una consulta en la tabla de UsuarioBanner 'UsuarioBanner'
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
     * @throws Exception si los productos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarioRecargasCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="",$having="",$withCount=true)
    {

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

        $Usuarios = $UsuarioRecargaMySqlDAO->queryUsuarioRecargasCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having,$withCount);


        if ($Usuarios != null && $Usuarios != "")
        {
            return $Usuarios;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "44");
        }


    }

    /**
     * Obtener recargas de usuarios personalizadas
     *
     * Realiza una consulta personalizada a la tabla `UsuarioBanner` mediante parámetros dinámicos
     * como filtros, ordenamientos, agrupamientos y joins.
     *
     * @param string $select : Campos que se desean consultar.
     * @param string $sidx : Columna por la cual se ordenará la consulta.
     * @param string $sord : Orden de los datos ('asc' o 'desc').
     * @param string $start : Índice inicial para paginación.
     * @param string $limit : Límite de resultados para paginación.
     * @param string $filters : Condiciones de filtrado en formato JSON o SQL.
     * @param boolean $searchOn : Indica si se deben aplicar los filtros.
     * @param string $grouping : Columna por la cual se agruparán los resultados.
     * @param string $having : Condiciones para cláusula HAVING (opcional).
     * @param boolean $withCount : Indica si se debe incluir el conteo total (por defecto `true`).
     * @param string $innerJoin : Sentencias INNER JOIN adicionales para la consulta.
     *
     * @return array $response Resultado de la consulta con las filas encontradas.
     *
     * @throws Exception Si no se encuentran resultados en la consulta.
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */

    public function getUsuarioRecargasCustomPRO($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="",$having="",$withCount=true,$innerJoin ="")
    {

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

        $Usuarios = $UsuarioRecargaMySqlDAO->queryUsuarioRecargasCustomPRO($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having,$withCount,$innerJoin);


        if ($Usuarios != null && $Usuarios != "")
        {
            return $Usuarios;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "44");
        }


    }


    /**
     * Obtiene una lista de recargas de usuario personalizadas con varios parámetros de filtrado y ordenación.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de resultados (opcional).
     * @param string $having Condiciones de agrupación (opcional).
     * @param bool $withCount Indica si se debe incluir el conteo total de registros.
     * @return array Lista de recargas de usuario que cumplen con los criterios especificados.
     * @throws Exception Si no se encuentran recargas de usuario.
     */
    public function getUsuarioRecargasCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="",$having="",$withCount=true)
    {

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

        $Usuarios = $UsuarioRecargaMySqlDAO->queryUsuarioRecargasCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having,$withCount);

        if ($Usuarios != null && $Usuarios != "")
        {
            return $Usuarios;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "44");
        }


    }


    /**
     * Obtiene recargas de usuario personalizadas con los parámetros especificados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param string $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento a aplicar en la consulta.
     * @param string $having (Opcional) Condiciones HAVING a aplicar en la consulta.
     * @return array|null Lista de recargas de usuario que coinciden con los parámetros especificados.
     * @throws Exception Si no existen recargas de usuario que coincidan con los parámetros especificados.
     */
    public function getUsuarioRecargasCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="",$having="")
    {

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();

        $Usuarios = $UsuarioRecargaMySqlDAO->queryUsuarioRecargasCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having);

        if ($Usuarios != null && $Usuarios != "")
        {
            return $Usuarios;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "44");
        }


    }

    /**
     * Obtiene las recargas de usuario con automatización personalizada.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los resultados.
     * @param int $limit Límite de resultados.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento de resultados.
     * @param string $having (Opcional) Condiciones de agrupamiento.
     *
     * @return array Resultados de la consulta de recargas de usuario.
     * @throws Exception Si no existen resultados.
     */
    public function getUsuarioRecargasCustomAutomation($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="",$having="")
    {

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($this->transaction);

        $Usuarios = $UsuarioRecargaMySqlDAO->queryUsuarioRecargasCustomAutomation($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having);

        if ($Usuarios != null && $Usuarios != "")
        {
            return $Usuarios;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "44");
        }


    }

    /**
     * Ejecuta una consulta SQL utilizando la transacción proporcionada.
     *
     * @param mixed $transaccion La transacción a utilizar para la consulta.
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la consulta SQL.
     */
    public function execQuery($transaccion, $sql)
    {

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($transaccion);
        $return = $UsuarioRecargaMySqlDAO->querySQL($sql);

        //$return = json_decode(json_encode($return), FALSE);

        return $return;

    }
}

?>
