<?php

use Backend\dto\AuditoriaGeneral;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CriptoRed;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\CriptoRedMySqlDAO;
use Backend\dto\RedBlockchain;
use Backend\dto\Criptomoneda;


/**
  * Obtiene los parámetros necesarios para actualizar la asociación entre una criptomoneda y una blockchain.
  *
  * @param string $Id              ID de la asociación entre criptomoneda y blockchain.
  * @param string $redBlockchainId ID de la blockchain asociada.
  * @param string $criptoMonedaId  ID de la criptomoneda asociada.
  * @param string $State           Estado de la asociación (A: Activo, I: Inactivo).
  */

$Id = $params->Id;
$redBlockchainId = $params->BlockchainIdNetwork;
$criptoMonedaId = $params->CryptoCurrencyId;
$State = $params->State;


/**
 * Intenta obtener el estado de una blockchain específica.
 *
 * @param string $redBlockchainId ID de la blockchain asociada.
 * @throws Exception              Si ocurre un error al instanciar el objeto RedBlockChain.
 * @var string   $estadoRed       Estado actual de la blockchain.
 */
try {
    $redBlochain = new RedBlockchain($redBlockchainId);
    $estadoRed = $redBlochain->estado;
} catch (Exception $e) {
    // Manejo de excepciones (puede incluir registro de errores o acciones específicas).
}

/**
 * Intenta obtener el estado de una criptomoneda específica.
 *
 * @param  $criptoMonedaId       Id de la criptomoneda asociada.
 * @throws Exception             Si ocurre un error al instanciar el objeto Criptomoneda.
 * @var    string $estadoCripto  Estado actual de la criptomoneda.
 */
try {
    $criptomoneda = new Criptomoneda($criptoMonedaId);
    $estadoCripto = $criptomoneda->getEstado();
} catch (Exception $e) {
    // Manejo de excepciones (puede incluir registro de errores o acciones específicas).
}



if($estadoCripto == "I"){
    throw new Exception ("La Criptomoneda no se encuentra activa",300181);
}

if($estadoRed == "I"){
    throw new Exception ("La Red Blockchain no se encuentra activa",300182);
}

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];

$ConfigurationEnvironment = new ConfigurationEnvironment();


if($estadoCripto != "" and $estadoRed != "" and $estadoCripto == "A" and $estadoRed == "A") {


    try {
        $CriptoRed = new CriptoRed($Id);
        // Configura los valores de la asociación.


        $beforeStatus = $CriptoRed->getEstado();

        if($State != $beforeStatus){
           $update = true;
        }

        $newStatus = $State;


        $CriptoRed->setEstado($State);


        if($update){

            $CriptoRedMySqlDAO = new CriptoRedMySqlDAO();
            $transaction = $CriptoRedMySqlDAO->getTransaction();
            $CriptoRedMySqlDAO->update($CriptoRed);
            $transaction->commit();

            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsuarioIp($ip);
            $AuditoriaGeneral->setUsuariosolicitaId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsuariosolicitaIp($ip);
            $AuditoriaGeneral->setUsuarioaprobarId(0);
            $AuditoriaGeneral->setUsuarioaprobarIp(0);
            $AuditoriaGeneral->setTipo("ACTUALIZACIONASOCIACIONCRIPTOMONEDAS");
            $AuditoriaGeneral->setEstado("A");
            $AuditoriaGeneral->setValorAntes($beforeStatus);
            $AuditoriaGeneral->setValorDespues($newStatus);
            $AuditoriaGeneral->setUsucreaId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsumodifId($_SESSION['usuario']);
            $AuditoriaGeneral->setData(json_encode($params));

            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
            $id = $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
            $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

        }

    } catch (Exception $e) {

    }


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}


