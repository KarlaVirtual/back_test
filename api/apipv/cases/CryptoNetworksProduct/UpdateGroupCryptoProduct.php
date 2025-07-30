<?php

use Backend\dto\AuditoriaGeneral;
use Backend\dto\CriptoredProdmandante;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\CriptoredProdmandanteMySqlDAO;

try {

     /**
     * Descripcion: Este recurso permite actualizar el estado de una asociacion de un producto a una criptored
     *
     * @param int $Id Identificador único de la asociacion del producto a la criptored.
     * @param string $State Estado actual del producto de criptomonedas (activo o inactivo).
     */

    $Id = $params->Id;
    $State = $params->State;


    /**
     * Instanciamos la clase `CriptoredProdmandante` utilizando el identificador único de la asociación del producto a la criptored.
     * Obtenemos el estado actual de la asociación mediante el método `getEstado()`.
     */
    $CriptoProveedorProdMandante = new CriptoredProdmandante($Id);

    $beforeState = $CriptoProveedorProdMandante->getEstado();

    /**
     * Verifica si el estado proporcionado es diferente al estado actual de la asociación.
     * Si es diferente, actualiza el estado de la asociación en la base de datos.
     */
    if($State != $beforeState){
        $CriptoProveedorProdMandante->setEstado($State);
    }


    $CriptoProveedorProdMandanteMySqlDAO = new CriptoredProdmandanteMySqlDAO();
    $transaction = $CriptoProveedorProdMandanteMySqlDAO->getTransaction();
    $CriptoProveedorProdMandanteMySqlDAO->update($CriptoProveedorProdMandante);
    $CriptoProveedorProdMandanteMySqlDAO->getTransaction()->commit();


    /*Obtenemos la direccion ip del operador que edito la asociacion*/
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

     /**
     * Se registra un log en la base de datos para auditar la actualización del estado de la asociación de un producto a una criptored.
     */


    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("ACTUALIZACIONASOCIACIONPRODUCTOCRIPTO");
    $AuditoriaGeneral->setValorAntes($beforeState);
    $AuditoriaGeneral->setValorDespues($State);
    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsumodifId($_SESSION["usuario"]);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setCampo("estado");

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

    /*Finalmente, se devuelve una respuesta al frontend indicando el éxito de la operación.*/

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Producto de criptomonedas actualizado correctamente.";
    $response["ModelErrors"] = [];

}catch (Exception $e){
    // Si ocurre una excepción, se captura y se prepara la respuesta de error.
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Error";
    $response["ModelErrors"] = [];
}

