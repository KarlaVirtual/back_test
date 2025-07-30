<?php
use Backend\dto\Criptomoneda;
use Backend\dto\UsuarioMandante;
use Backend\dto\CriptoRed;
use Backend\dto\RedBlockchain;
use Backend\dto\UsuarioBanco;
use Backend\dto\Clasificador;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\MandanteDetalle;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;


/**
 * Descripcion: Este recurso permite registrar una nueva wallet de criptomonedas asociada a un usuario.
 * @param object $json->session->usuario [Objeto que contiene la información del usuario en la sesión actual].
 * @param int $json->params->cryptocurrency_id [Identificador único de la criptomoneda seleccionada].
 * @param int $json->params->network_id [Identificador único de la red asociada a la criptomoneda].
 * @param string $json->params->account [Dirección de la cuenta de criptomoneda que se desea registrar].
 * @param string $json->params->verification_account [Dirección de verificación para confirmar la cuenta ingresada].
 */

try {
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $UsuarioId = $UsuarioMandante->getUsuarioMandante();




    $cryptoCurrency_id = $json->params->cryptocurrency_id;
    $network_id = $json->params->network_id;
    $account = $json->params->account;
    $verification_account = $json->params->verification_account;



    /**
     * Se realiza instancia de la clase CriptoRed para obtener la direccion asociada a la criptomoneda y red seleccionada.
     */

    $CriptoRed = new CriptoRed('', $cryptoCurrency_id, $network_id, 'A');
    $BancoId = $CriptoRed->getBancoId(); // Obtenemos el banco asociado a la criptomoneda y red seleccionada

    if($account == "" || $account == null){ // Se verifica que la cuenta no este vacia
        throw new Exception("La cuenta no puede estar vacía", 721820);
    }

    if($account != $verification_account){ // Se verifica que la cuenta de verificacion sea igual a la cuenta ingresada
        throw new Exception("Las direcciones ingresadas no coinciden.", 721821);
    }

    //-----------------------------------------------------------------------------------------------------------------------------------

    /*Se agregan las reglas por las cuales se desea filtrar y se verifica si el usuario ya tiene la misma combinacion registrada*/

    $rules = [];
    array_push($rules, array("field" => "usuario_banco.cuenta", "data" => $account, "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.tipo_cuenta", "data" => "Crypto", "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioId, "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    // Se crea una instancia de UsuarioBanco para verificar si la cuenta ya existe para el usuario actual

    $UsuarioBanco = new UsuarioBanco();
    $existeCuenta2 = $UsuarioBanco->getUsuarioBancosCustom("usuario_banco.*", "usuario_banco.usuario_id", "desc", 0, 100, $jsonfiltro, true);

    $existeCuenta2 = json_decode($existeCuenta2);

    /*Se verifica que la combinacion de la direccion no este ya registrada*/

    if( isset($existeCuenta2->data) && count($existeCuenta2->data) > 0) {
        throw new Exception("Ya has registrado esta dirección para esta cripto y red", 721818);
    }



    //----------------------------------------------------------------------------------------------------------------------
    /*Verificacion de que la direccion no este registrada en otra cuenta */

    $rules = [];
    array_push($rules, array("field" => "usuario_banco.cuenta", "data" => $account, "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    // Se crea una instancia de UsuarioBanco para verificar si la cuenta ya existe

    $UsuarioBanco = new UsuarioBanco();
    $existeCuenta = $UsuarioBanco->getUsuarioBancosCustom("usuario_banco.*", "usuario_banco.usuario_id", "desc", 0, 100, $jsonfiltro, true);

    $existeCuenta = json_decode($existeCuenta);

    // Si la cuenta ya existe, se lanza una excepción o lo que seria el codigo de error
    if (!empty($existeCuenta->data)) {
        throw new Exception("Esta dirección ya fue registrada por otro usuario.", 721819);
    }



    /*Se realiza la verificacion de cual es el maximo de combinaciones posibles por el partner y el pais*/
    try {
        $Clasificador = new Clasificador("","WALT");
        $MandanteDetalle = new MandanteDetalle("",$UsuarioMandante->mandante,$Clasificador->getClasificadorId(),$UsuarioMandante->paisId,"A");
        $valorMaximo = $MandanteDetalle->valor; /*Se esta obtiene el maximo de combinaciones*/


    }catch (Exception $e){

    }

    /*Se definen las condiciones con las cuales se realizara el filtrado para conocer si el usuario esta superando el maximo de combinaciones */

    $rules = [];
    array_push($rules, array("field" => "usuario_banco.tipo_cuenta", "data" => "Crypto", "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioId, "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.banco_id", "data" => $BancoId, "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    $UsuarioBanco = new UsuarioBanco();
    $totalCuentas = $UsuarioBanco->getUsuarioBancosCustom("usuario_banco.*", "usuario_banco.usuario_id", "desc", 0, 100, $jsonfiltro, true);

    $totalCuentas = json_decode($totalCuentas);

    $totalCuentas = $totalCuentas->count[0]->{".count"}; /*Se verifica cual es el total de cuentas que tiene el usuario*/

    /*Se realiza validacion de cuantas cuentas wallet tiene el usuario definidas y que no supere el limite permitido por el partner*/

    if ($valorMaximo !== null && $valorMaximo !== "" && $valorMaximo > 0) {
        if ($totalCuentas >= $valorMaximo) {
            throw new Exception("“Solo puedes registrar hasta $valorMaximo direcciones para esta criptomoneda y red.”", 721817);
        }
    }


//-----------------------------------------------------------------------------------------------------------------------------------


    /*Se realiza instancia de la clase UsuarioBanco para registrar la wallet es un banco*/

    $UsuarioBanco = new UsuarioBanco();
    $UsuarioBanco->setUsuarioId($UsuarioId);
    $UsuarioBanco->setBancoId($BancoId);
    $UsuarioBanco->setCuenta($account);
    $UsuarioBanco->setTipoCuenta("CRYPTO");
    $UsuarioBanco->setTipoCliente("PERSONA");
    $UsuarioBanco->setEstado("A");
    $UsuarioBanco->setUsucreaId($UsuarioId);
    $UsuarioBanco->setUsumodifId($UsuarioId);
    $UsuarioBanco->setToken("");
    $UsuarioBanco->setProductoId(0);


    $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();
    $Transaction = $UsuarioBancoMySqlDAO->getTransaction();
    $UsuarioBancoMySqlDAO->insert($UsuarioBanco);


    /*Se deja registros de las creciacion de wallet en el cual se guardaria toda la informacion solicitada*/

    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($UsuarioMandante->getUsuarioMandante());
    $AuditoriaGeneral->setUsuariosolicitaIp("");
    $AuditoriaGeneral->setUsuariosolicitaId($UsuarioMandante->getUsuarioMandante());
    $AuditoriaGeneral->setUsuarioaprobarIp($_SERVER['REMOTE_ADDR']);
    $AuditoriaGeneral->setTipo("REGISTROWALLET");
    $AuditoriaGeneral->setValorAntes("");
    $AuditoriaGeneral->setValorDespues("La criptomoneda es: $cryptoCurrency_id, la redBlockchain es: $network_id y la dirección es: $account");
    $AuditoriaGeneral->setUsucreaId($UsuarioMandante->getUsuarioMandante());    $AuditoriaGeneral->setUsucreaId($UsuarioMandante->getUsuarioMandante());
    $AuditoriaGeneral->setUsumodifId($UsuarioMandante->getUsuarioMandante());
    $AuditoriaGeneral->setEstado("A");


    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

    $Transaction->commit();
    $response = array();
    $response['code'] = 0;

}catch (EXception $e){
    $response = array();
    $response['code'] = $e->getCode();
    $response['message'] = $e->getMessage();
}

