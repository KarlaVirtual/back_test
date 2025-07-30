<?php namespace Backend\dto;
use Backend\mysql\RedBlockchainMySqlDAO;
use Exception;
/**
 * Clase 'RedBlockchain'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'RedBlockchain'
 *
 * Ejemplo de uso:
 * $RedBlockChain = new RedBlockchain();
 *
 *
 * @package ninguno
 * @author Juan Salazar <juan.salazar@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class RedBlockchain
{

    /**
     * Representación de la columna 'RedBlockChainId' de la tabla 'red_blockchain'
     *
     * @var string
     */
    var $redblockchainId;

    /**
     * Representación de la columna 'nombre' de la tabla 'red_blockchain'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'codigo_red' de la tabla 'red_blockchain'
     *
     * @var string
     */
    var $codigoRed;

    /**
     * Representación de la columna 'estado' de la tabla 'red_blockchain'
     *
     * @var string
     */
    var $estado;


    /**
     * Representación de la columna 'usucrea_id' de la tabla 'red_blockchain'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'red_blockchain'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Constructor de clase
     *
     *
     * @param String $redblockchainId id de la red_blockchain
     *
     * @throws Exception si el redblockchain no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($redblockchainId="")
    {

        if($redblockchainId != "")
        {

            $RedBlockchainMySqlDAO = new RedBlockchainMySqlDAO();

            $RedBlockChain = $RedBlockchainMySqlDAO->load($redblockchainId);


            if ($RedBlockChain != null && $RedBlockChain != "")
            {
                $this->nombre = $RedBlockChain->nombre;
                $this->redblockchainId = $RedBlockChain->redblockchainId;
                $this->estado = $RedBlockChain->estado;
                $this->codigoRed = $RedBlockChain->codigoRed;
                $this->usumodifId = $RedBlockChain->usumodifId;
                $this->usucreaId = $RedBlockChain->usucreaId;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "35");
            }

        }

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
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Modificar el campo 'nombre' de un objeto
     *
     * @param String $nombre usumodifId
     *
     * @return no
     *
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Realizar una consulta en la tabla de red_blockchain 'RedBlockChain'
     * de una manera personalizada
     *
     *
     * @param String  $select    campos de consulta
     * @param String  $sidx      columna para ordenar
     * @param String  $sord      orden los datos asc | desc
     * @param String  $start     inicio de la consulta
     * @param String  $limit     limite de la consulta
     * @param String  $filters   condiciones de la consulta
     * @param boolean $searchOn  utilizar los filtros o no
     *
     * @return Array resultado de la consulta
     * @throws Exception si el bono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getRedBlockchainsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $RedBlockchainMySqlDAO = new RedBlockchainMySqlDAO();

        $redes = $RedBlockchainMySqlDAO->queryRedBlockchainsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($redes != null && $redes != "")
        {
            return $redes;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "35");
        }


    }



}
?>