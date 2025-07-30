<?php

use Backend\dto\AuditoriaGeneral;
use Backend\dto\Criptomoneda;
use Backend\dto\CuentaCobro;
use Backend\dto\UsuarioMandante;
use Backend\dto\CriptoRed;
use Backend\dto\RedBlockchain;
use Backend\dto\UsuarioBanco;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

 /**
 * Descripcion: Este recurso permite eliminar una wallet asociada a un usuario.
 * @param int $json->params->crypto_id Identificador único de la dirección de wallet que se desea eliminar.
 */

$Id = $json->params->crypto_id;


/*Validacion de que no haya un retiro pendiente con esa wallet*/

$rules = [];

array_push($rules,array("field"=>"cuenta_cobro.mediopago_id","data"=>$Id,"op"=>"eq"));
array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'P','M','A'", "op" => "in"));

$rules = json_encode(array("rules" => $rules, "groupOp" => "AND"));

$CuentaCobro = new CuentaCobro();
$pendientes = $CuentaCobro->getCuentasCobroCustom("cuenta_cobro.cuenta_id", "cuenta_cobro.cuenta_id", "desc", 0, 10, $rules, true);

$pendientes = json_decode($pendientes);


/*Se verifica si ya tiene un retiro pendiente y en caso de que si se procede a indicar que no se puede eliminar*/

if($pendientes->count[0]->{".count"} > 0){

    $response = array();
    $response["code"] = 12;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => "No puedes eliminar esta dirección porque está asociada a un retiro en curso."
    );
    $response["result"] = "No puedes eliminar esta dirección porque está asociada a un retiro en curso.";
    return;
}


/*Se realiza la inactivacion de la wallet modificando el estado*/

try {
    $UsuarioBanco = new UsuarioBanco($Id);
    $UsuarioBanco->setEstado('I');

    $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();
    $transaction = $UsuarioBancoMySqlDAO->getTransaction();
    $UsuarioBancoMySqlDAO->update($UsuarioBanco);
    $UsuarioBancoMySqlDAO->getTransaction()->commit();


    /*Se realiza registro de auditoria en el cual se deja constancia de la eliminacion de la wallet del usuario*/


    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($UsuarioMandante->getUsuarioMandante());
    $AuditoriaGeneral->setUsuariosolicitaIp("");
    $AuditoriaGeneral->setUsuariosolicitaId($UsuarioMandante->getUsuarioMandante());
    $AuditoriaGeneral->setUsuarioaprobarIp($_SERVER['REMOTE_ADDR']);
    $AuditoriaGeneral->setTipo("ELIMINACIONWALLET");
    $AuditoriaGeneral->setValorAntes("La wallet eliminada es: $Id");
    $AuditoriaGeneral->setValorDespues("");
    $AuditoriaGeneral->setUsucreaId($UsuarioMandante->getUsuarioMandante());
    $AuditoriaGeneral->setUsumodifId($UsuarioMandante->getUsuarioMandante());
    $AuditoriaGeneral->setEstado("A");

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();


    $response = array();
    $response['code'] = 0;

}catch (Exception $e){
    $response = array();
    $response['code'] = 1;
}

