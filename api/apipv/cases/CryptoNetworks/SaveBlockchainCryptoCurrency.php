<?php

use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Criptomoneda;
use Backend\dto\CriptoRed;
use Backend\dto\RedBlockchain;
use Backend\dto\UsuarioBanco;
use Backend\mysql\BancoMySqlDAO;
use Backend\mysql\CriptoRedMySqlDAO;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * Este archivo maneja la inserción de datos relacionados con criptomonedas y redes blockchain,
 * además de registrar auditorías de las acciones realizadas.
 *
 * @package Backend
 */

// Obtención de parámetros de la solicitud

/**
 * @param string $params CriptoMonedaId ID de la criptomoneda.
 * @param string $params redBlockchainId ID de la red blockchain.
 * @param string $params state Estado de la criptomoneda (A: Activo, I: Inactivo).
 */

$CriptoMonedaId = $params->CryptoCurrencyId;
$redBlockchainId = $params->BlockchainIdNetwork;
$state = $params->State;



/**
 * Obtiene la dirección IP del cliente.
 *
 * @var string $ip Dirección IP del cliente.
 */

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


$ConfigurationEnvironment = new ConfigurationEnvironment();



// consulta para saber si ya existe una cripto moneda asociada a esa red y esta activa

$Rules = [];
array_push($Rules,array("field"=>"cripto_red.criptomoneda_id","data"=>$CriptoMonedaId,"op"=>"eq"));
array_push($Rules,array("field"=>"cripto_red.redblockchain_id","data"=>$redBlockchainId,"op"=>"eq"));
array_push($Rules, array("field" => "cripto_red.estado", "data" => "'A','I'", "op" => "in"));
$Rules = json_encode(array("rules"=>$Rules,"groupOp"=>"AND"));

$CriptoRed = new CriptoRed();
$datos = $CriptoRed->getCriptoRedCustom("cripto_red.*,red_blockchain.nombre,red_blockchain.codigo_red,criptomoneda.nombre", "cripto_red.criptored_id", "asc", 0, 100, $Rules, true);

$datos = json_decode($datos);


$final = [];
foreach ($datos->data as $key => $value) {
    $array = [];
    $array["State"] = $value->{"cripto_red.estado"};
    array_push($final, $array);
}

if($final != ""){
    $estadoValidado = $final[0]["State"];
}

if ($estadoValidado == "A" || $estadoValidado == "I") {
    throw new exception ("Ya existe una criptomoneda asociada a esta red blockchain y está activa.",300180);
}


try {
    $redBlochain = new RedBlockchain($redBlockchainId);
    $estadoRed = $redBlochain->estado;
    $nombreRed = $redBlochain->nombre;
} catch (Exception $e) {
    // Manejo de excepciones (puede incluir registro de errores o acciones específicas).
}

/**
 * Intenta obtener el estado de una criptomoneda específica.
 *
 * @param  string    $criptoMonedaId ID de la criptomoneda asociada.
 * @throws Exception Si ocurre un error al instanciar el objeto Criptomoneda.
 * @var    string    $estadoCripto Estado actual de la criptomoneda.
 */

try {
    $criptomoneda = new Criptomoneda($CriptoMonedaId);
    $estadoCripto = $criptomoneda->getEstado();
    $nombreCripto = $criptomoneda->getNombre();
} catch (Exception $e) {
    // Manejo de excepciones (puede incluir registro de errores o acciones específicas).
}



if($estadoCripto == "I"){
    throw new Exception ("La Criptomoneda no se encuentra activa",300181);
}

if($estadoRed == "I"){
    throw new Exception ("La Red Blockchain no se encuentra activa",300182);
}


try {
    /**
     * Inserta los datos de la criptomoneda y la red blockchain en la base de datos.
     *
     * @var CriptoRed         $CriptoRed         Instancia del objeto Criptored.
     * @var CriptoRedMySqlDAO $CriptoRedMySqlDAO DAO para manejar la base de datos de CriptoRed.
     */



    $parte1 = substr($nombreCripto, 0, 3);
    $parte2 = substr($nombreRed, 0, 3);
    $NombreBanco = $parte1 . "-" . $parte2;


    $Banco = new Banco();
    $Banco->descripcion = $NombreBanco;
    $Banco->paisId = $_SESSION['pais_id'];
    $Banco->estado = "A";
    $Banco->productoPago = 0;
    $Banco->tipo = "CRYPTO";
    $BancoMySqlDAO = new BancoMySqlDAO();
    $transaction = $BancoMySqlDAO->getTransaction();
    $BancoId=$BancoMySqlDAO->insert($Banco);



    $CriptoRed = new CriptoRed();
    $CriptoRed->setCriptomonedaId($CriptoMonedaId);
    $CriptoRed->setRedBlockchain($redBlockchainId);
    $CriptoRed->setEstado($state);
    $CriptoRed->setUsuCreaId($_SESSION['usuario']);
    $CriptoRed->setUsuModifId($_SESSION['usuario']);
    $CriptoRed->setBancoId($BancoId);

    $CriptoRedMySqlDAO = new CriptoRedMySqlDAO($transaction);
    $CriptoRedMySqlDAO->insert($CriptoRed);

    /**
     * Registra una auditoría de la acción realizada.
     *
     * @var AuditoriaGeneral         $AuditoriaGeneral         Instancia del objeto AuditoriaGeneral.
     * @var AuditoriaGeneralMySqlDAO $AuditoriaGeneralMySqlDAO DAO para manejar la base de datos de AuditoriaGeneral.
     */

    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION['usuario']);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
    $AuditoriaGeneral->setUsuarioaprobarId(0);
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("ASOCIACIONCRIPTOMONEDAS");
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setValorAntes("");
    $AuditoriaGeneral->setValorDespues($state);
    $AuditoriaGeneral->setUsucreaId($_SESSION['usuario']);
    $AuditoriaGeneral->setUsumodifId($_SESSION['usuario']);
    $AuditoriaGeneral->setData(json_encode($params));

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaction);
    $id = $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

    /**
     * Construye la respuesta para el cliente.
     *
     * @var array $response Respuesta estructurada para el cliente.
     */

    $transaction->commit();


    $cambios = true;

} catch (Exception $e) {
    $cambios = false;
}

if($cambios){
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Criptomoneda asociada a la red blockchain correctamente.";
    $response["ModelErrors"] = [];
}else{
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Error";
    $response["ModelErrors"] = [];
}