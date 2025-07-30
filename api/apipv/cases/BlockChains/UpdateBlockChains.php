<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\RedBlockchain;
use Backend\dto\CriptoRed;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\RedBlockchainMySqlDAO;

/**
 * UpdateRedBlockchain
 *
 * Actualiza la información de una RedBlockchain, verificando permisos de usuario,
 * aplicando cambios solo si los datos han sido modificados, y registrando auditorías cuando corresponde.
 *
 * @param object $params         Objeto que contiene los datos de entrada para la actualización.
 * @param int $params->Id        Identificador del RedBlockchain a actualizar.
 * @param string $params->state  Estado de la RedBlockchain ('A' para activo, 'I' para inactivo).
 * @param string $params->name   Nombre o descripción de la RedBlockchain.
 * @param string $params->code   Código abreviado de la RedBlockchain.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool)        Indica si ocurrió un error en la operación.
 *  - *AlertType* (string)     Tipo de alerta que se debe mostrar ('success' en caso exitoso).
 *  - *AlertMessage* (string)  Mensaje a mostrar en la alerta.
 *  - *ModelErrors* (array)    Lista de errores del modelo (si existen).
 *
 * Ejemplo de respuesta en caso exitoso:
 *
 * $response["HasError"] = false;
 * $response["AlertType"] = "success";
 * $response["AlertMessage"] = "";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Permiso denegado o Red asociada a una criptomoneda (al intentar cambiar el código).
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/* Se reciben parámetros para crear un objeto RedBlockchain con ID y detalles asociados. */
$Id = $params->Id; // se recibe el id del RedBlockchain

$State = $params->state; // se recibe el estado de la RedBlockchain
$Name = $params->name; // se recibe el nombre de la red
$Code = $params->code; // se recibe el codigo de la red
$band1 = false; // bandera que activa el insert de auditoria general de edición de multiples datos
$count = 0; // Contador de alteraciones a la base de datos

$ConfigurationEnvironment = new ConfigurationEnvironment();

$permission = $ConfigurationEnvironment->checkUserPermission('BlockChains/UpdateBlockChains', $_SESSION['win_perfil'], $_SESSION['usuario'], "BlockchainNetworksEdit");
if (!$permission) Throw new Exception("Permiso denegado", 100035);

if ($Id !=""){
    $RedBlockchainMySqlDAO = new RedBlockchainMySqlDAO();
    $RedBlockchain = new RedBlockchain($Id); // se realiza la instancia a la tabla RedBlockchain y se le asignan las propiedades
    /* Crea el array y agrega el primer dato: id de la red */
    $dataArrayAntes = [];
    $dataArrayDespues = [];
    $dataArrayAntes['RedAfectada'] = $Id;
    $dataArrayDespues['RedAfectada'] = $Id;

    /* asigna valores a un objeto y obtiene una transacción de la base de datos. */
    if ($Code != ""){
        $codigoInicial = $RedBlockchain->codigoRed;
        if ($codigoInicial != $Code) {
            $existCode = $RedBlockchainMySqlDAO->queryByCodigoRed($Code);
            $Criptored = new CriptoRed("","",$Id,"");
            if ($Criptored->criptoredId != "" || $Criptored->criptoredId != null){
                throw new Exception("Red asociada a una criptomoneda, el codigo de red no se puede cambiar", 300172);

            }elseif(!empty($existCode)){
                throw new Exception("El código de red ingresado ya existe en el sistema.", 300173);
            }else{
                $count++;
                $band1 = true;
                $dataArrayAntes['Codigo_red'] = $codigoInicial;
                $RedBlockchain->codigoRed = $Code;
                $dataArrayDespues['Codigo_red'] = $Code;
            }
        }
    }
    if ($State != ""){
        $estadoInicial = $RedBlockchain->estado;
        if ($estadoInicial != $State){
            $count++;
            //Nuevos arrays para log de inactivación
            $dataArrayAntes2 = [];
            $dataArrayDespues2 = [];
            $dataArrayAntes2['RedAfectada'] = $Id;
            $dataArrayDespues2['RedAfectada'] = $Id;
            $dataArrayAntes2['Estado'] = $estadoInicial;
            $RedBlockchain->estado = $State;
            $dataArrayDespues2['Estado'] = $State;
            if ($State == 'I'){
                $dataArrayDespues2 = json_encode($dataArrayDespues2);
                $dataArrayDespues2 = substr($dataArrayDespues2, 0, 250);
                $dataArrayAntes2 = json_encode($dataArrayAntes2);
                $dataArrayAntes2 = substr($dataArrayAntes2, 0, 250);
                /* Se crea una auditoría general con información de usuario e IP. */
                $AuditoriaGeneral = new AuditoriaGeneral();
                $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $ip = explode(",", $ip)[0];
                $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsuarioIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);

                /* configura una auditoría para la desactivación de un banco. */
                $AuditoriaGeneral->setUsuarioaprobarIp(0);
                $AuditoriaGeneral->setTipo("INACTIVAR_REDBLOCKCHAIN");
                $AuditoriaGeneral->setValorAntes("$dataArrayAntes2");
                $AuditoriaGeneral->setValorDespues("$dataArrayDespues2");
                $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsumodifId(0);

                /* Se establece el estado, dispositivo y observación en Auditoría General con MySQL. */
                $AuditoriaGeneral->setEstado("A");
                $AuditoriaGeneral->setDispositivo(0);
                $AuditoriaGeneral->setObservacion("");


                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

                /* Inserta un registro de auditoría general en la base de datos MySQL. */
                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                $RedBlockchainMySqlDAO->getTransaction()->commit();

            }
            else{
                $band1 = true;
                $dataArrayAntes['Estado'] = $estadoInicial;
                $RedBlockchain->estado = $State;
                $dataArrayDespues['Estado'] = $State;
            }
        }
    }

    if ($Name != ""){
        $nombreInicial = $RedBlockchain->nombre;
        if ($nombreInicial != $Name) {
            $count++;
            $band1 = true;
            $dataArrayAntes['Nombre'] = $nombreInicial;
            $RedBlockchain->nombre = $Name;
            $dataArrayDespues['Nombre'] = $Name;
        }
    }

    $RedBlockchain->setUsumodifId($_SESSION['usuario2']);


    if ($band1){ // Se activa si se crean mas de 2 casos de modificación
        /* Convierte el array a JSON y corta a 250 caracteres */
        $dataArrayDespues = json_encode($dataArrayDespues);
        $dataArrayDespues = substr($dataArrayDespues, 0, 250);

        $dataArrayAntes = json_encode($dataArrayAntes);
        $dataArrayAntes = substr($dataArrayAntes, 0, 250);

        /* Se crea una auditoría general con información de usuario e IP. */
        $AuditoriaGeneral = new AuditoriaGeneral();
        $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $ip = explode(",", $ip)[0];
        $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
        $AuditoriaGeneral->setUsuarioIp($ip);
        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);

        /* configura una auditoría para la desactivación de un banco. */
        $AuditoriaGeneral->setUsuarioaprobarIp(0);
        $AuditoriaGeneral->setTipo("EDITAR_REDBLOCKCHAIN");
        $AuditoriaGeneral->setValorAntes("$dataArrayAntes");
        $AuditoriaGeneral->setValorDespues("$dataArrayDespues");
        $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
        $AuditoriaGeneral->setUsumodifId(0);

        /* Se establece el estado, dispositivo y observación en Auditoría General con MySQL. */
        $AuditoriaGeneral->setEstado("A");
        $AuditoriaGeneral->setDispositivo(0);
        $AuditoriaGeneral->setObservacion("");


        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

        /* Inserta un registro de auditoría general en la base de datos MySQL. */
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    }


    if ($count != 0){ //Bloquea Updates innecesarios en la base de datos.

        /* Actualiza información del RedBlockchain, confirma transacción y prepara respuesta exitosa. */
        $RedBlockchainMySqlDAO->update($RedBlockchain);
        $RedBlockchainMySqlDAO->getTransaction()->commit(); // se deja la transaccion y se hace un commit para guardar la nueva informacion del RedBlockchain
    }

}


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* Inicializa un arreglo vacío para almacenar errores del modelo en la respuesta. */
$response["ModelErrors"] = [];