<?php

namespace Backend\dto;
use Backend\mysql\CriptoRedMySqlDAO;
use Exception;

/**
 * Clase 'Criptored'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'cripto_red'
 *
 * Ejemplo de uso:
 * $Contacto = new Criptored();
 *
 *
 * @package ninguno
 * @author  Juan David Taborda <juan.taborda@virtualsoft.tech>
 * @version ninguna
 * @access  public
 * @see     no
 *
 */



class CriptoRed
{
    /**
     * Representación de la columna 'criptored_id' de la tabla 'cripto_red'
     *
     * @var string
     */
    var $criptoredId;

    /**
     * Representación de la columna 'criptomoneda_id' de la tabla 'cripto_red'
     *
     * @var string
     */

    var $criptomonedaId;

    /**
     * Representación de la columna 'redblockchain_id' de la tabla 'cripto_red'
     *
     * @var string
     */

    var $redblockchainId;

    /**
     * Representación de la columna 'estado' de la tabla 'cripto_red'
     *
     * @var string
     */

    var $estado;

    /**
     * Representación de la columna 'fecha_crea' de la tabla 'cripto_red'
     *
     * @var string
     */


    var $fechaCrea;


    /**
     * Representación de la columna 'fecha_modif' de la tabla 'cripto_red'
     *
     * @var string
     */

    var $fechaModif;

    /**
     * Representación de la columna 'usucrea_id' de la tabla 'cripto_red'
     *
     * @var string
     */

    var $usucreaId;

    /**
     * Representación de la columna 'usumodif_id' de la tabla 'cripto_red'
     *
     * @var string
     */

    var $usumodifId;

    /**
     * Representación de la columna 'bancoId' de la tabla 'cripto_red'
     * @var string $bancoId id del banco
     */
     var $bancoId;

    /**
     * Constructor de clase
     *
     * @param string $criptoRed id de la asociacion
     * @param string $criptomonedaId     id de la criptomoneda
     * @param string $redblockchainId    id de la red blockchain
     * @param string $estado estado de la cripto_red
     * @param string $bancoId id del banco
     * @throws Exception si la cripto_red no existe
     * @access public
     * @return void
     */
    public function __construct($criptoredId = "", $criptomonedaId = "", $redblockchainId = "", $estado = "",$bancoId=""){
        if($criptoredId != ""){
            $CriptoRedMySqlDAO = new CriptoRedMySqlDAO();
            $CriptoRed = $CriptoRedMySqlDAO->load($criptoredId);

            if($CriptoRed != "" and $CriptoRed != ""){
                $this->criptoredId = $CriptoRed->criptoredId;
                $this->criptomonedaId = $CriptoRed->criptomonedaId;
                $this->redblockchainId = $CriptoRed->redblockchainId;
                $this->estado = $CriptoRed->estado;
                $this->fechaCrea = $CriptoRed->fechaCrea;
                $this->usucreaId = $CriptoRed->usucreaId;
                $this->fechaModif = $CriptoRed->fechaModif;
                $this->usumodifId = $CriptoRed->usumodifId;
                $this->bancoId = $CriptoRed->bancoId;
            }
            else{
                throw new Exception("CriptoRed no existe");
            }


        }else if($criptomonedaId != "" and $redblockchainId != null and $estado != null){
            $CriptoRedMySqlDAO = new CriptoRedMySqlDAO();
            $CriptoRed = $CriptoRedMySqlDAO->queryByMonedaIdAndRedBlochainIdAndEstado($criptomonedaId, $redblockchainId,$estado);
            if($CriptoRed != ""){
                $this->criptoredId = $CriptoRed->criptoredId;
                $this->criptomonedaId = $CriptoRed->criptomonedaId;
                $this->redblockchainId = $CriptoRed->redblockchainId;
                $this->estado = $CriptoRed->estado;
                $this->fechaCrea = $CriptoRed->fechaCrea;
                $this->usucreaId = $CriptoRed->usucreaId;
                $this->fechaModif = $CriptoRed->fechaModif;
                $this->usumodifId = $CriptoRed->usumodifId;
                $this->bancoId = $CriptoRed->bancoId;
            }
        }else if($redblockchainId != ""){
            $CriptoRedMySqlDAO = new CriptoRedMySqlDAO();
            $CriptoRed = $CriptoRedMySqlDAO->queryByRedBlockchainId($redblockchainId);
            if($CriptoRed != "" and $CriptoRed != ""){
                $this->criptoredId = $CriptoRed->criptoredId;
                $this->criptomonedaId = $CriptoRed->criptomonedaId;
                $this->redblockchainId = $CriptoRed->redblockchainId;
                $this->estado = $CriptoRed->estado;
                $this->fechaCrea = $CriptoRed->fechaCrea;
                $this->usucreaId = $CriptoRed->usucreaId;
                $this->fechaModif = $CriptoRed->fechaModif;
                $this->usumodifId = $CriptoRed->usumodifId;
                $this->bancoId = $CriptoRed->bancoId;
            }

        }else if($criptomonedaId != "" and $redblockchainId != "" and $bancoId!= ""){
            $CriptoRedMySqlDAO = new CriptoRedMySqlDAO();
            $CriptoRed = $CriptoRedMySqlDAO->queryByMonedaIdAndRedBlochainIdAndBancoId($criptomonedaId, $redblockchainId,$bancoId);
            if($CriptoRed != "" and $CriptoRed != null){
                $this->criptoredId = $CriptoRed->criptoredId;
                $this->criptomonedaId = $CriptoRed->criptomonedaId;
                $this->redblockchainId = $CriptoRed->redblockchainId;
                $this->estado = $CriptoRed->estado;
                $this->fechaCrea = $CriptoRed->fechaCrea;
                $this->usucreaId = $CriptoRed->usucreaId;
                $this->fechaModif = $CriptoRed->fechaModif;
                $this->usumodifId = $CriptoRed->usumodifId;
                $this->bancoId = $CriptoRed->bancoId;
            }
        } else if ($bancoId != "") {
            $CriptoRedMySqlDAO = new CriptoRedMySqlDAO();
            $CriptoRed = $CriptoRedMySqlDAO->queryByBancoId($bancoId);
            if($CriptoRed != "" and $CriptoRed != null){
                $this->criptoredId = $CriptoRed->criptoredId;
                $this->criptomonedaId = $CriptoRed->criptomonedaId;
                $this->redblockchainId = $CriptoRed->redblockchainId;
                $this->estado = $CriptoRed->estado;
                $this->fechaCrea = $CriptoRed->fechaCrea;
                $this->usucreaId = $CriptoRed->usucreaId;
                $this->fechaModif = $CriptoRed->fechaModif;
                $this->usumodifId = $CriptoRed->usumodifId;
                $this->bancoId = $CriptoRed->bancoId;
            }
        }

    }


    public function getCriptoRedCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn, $joins = []){
        $CriptoRedMySqlDAO = new CriptoRedMySqlDAO();

        $datos = $CriptoRedMySqlDAO->queryCriptoRedCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn, $joins);

        if($datos != "" and $datos != null){
            return $datos;
        }else{
            throw new Exception("No existe criptoRed", "35");
        }

    }


    public function setCriptomonedaId($CriptomonedaId){
        $this->criptomonedaId = $CriptomonedaId;
    }

    public function getCriptomonedaId(){
        return $this->criptomonedaId;
    }

    public function setRedBlockchain($RedBlockchain){
        $this->redblockchainId = $RedBlockchain;
    }


    public function getRedBlockchain(){
        return $this->redblockchainId;
    }

    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function setUsuCreaId($usucreaId){
        $this->usucreaId = $usucreaId;
    }

    public function getUsuCreaId(){
        return $this->usucreaId;
    }

    public function setUsuModifId($usumodifId){
        $this->usumodifId = $usumodifId;
    }

    public function getUsuModifId(){
        return $this->usumodifId;
    }

    /**
     * Obtiene el id del banco
     * @return string id del banco
     */
    public function getBancoId(){
        return $this->bancoId;
    }

    /**
     * Establece el id del banco
     * @param string $value id del banco
     * @return void
     */
    public function setBancoId($value){
        $this->bancoId = $value;
    }
}