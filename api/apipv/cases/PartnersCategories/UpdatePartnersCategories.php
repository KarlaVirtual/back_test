<?php

use Backend\dto\GeneralLog;
use Backend\dto\CategoriaMandante;
use Backend\mysql\GeneralLogMySqlDAO;
use Backend\mysql\CategoriaMandanteMySqlDAO;

/**
 * Actualiza la información de una categoría de socios en el sistema.
 *
 * Este script procesa una solicitud para actualizar una categoría de socios,
 * decodificando los datos de entrada, asignando valores a un objeto de categoría,
 * y registrando los cambios en un log general.
 *
 * @param string $params Datos de entrada en formato JSON codificado en base64.
 *                        Contiene los siguientes valores:
 * @param int $params->Id Identificador de la categoría.
 * @param string $params->Description Descripción de la categoría.
 * @param int $params->Type Tipo de categoría (0: CASINO
 * @param string $params->Slug Identificador único de la categoría.
 * @param int $params->State Estado de la categoría (1: Inactivo, 0: Activo).
 * @param string $params->Icon URL del ícono de la categoría.
 * @param int $params->Order Orden de la categoría.
 * @param int $params->Country Identificador del país asociado.
 *
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta (success o error).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Captura cualquier excepción durante la actualización de la categoría
 *                   o el registro en el log, devolviendo un error en la respuesta.
 */

try {

    /* obtiene, decodifica y analiza datos JSON desde una entrada base64. */
        $params = file_get_contents('php://input');
        $params = base64_decode($params);
        $params = json_decode($params);

        $Id = $params->Id;
        $Description = html_entity_decode($params->Description);
    $Type = $params->Type;
        $Slug = html_entity_decode($params->Slug);
    $State = $params->State == 1 ? 'I' : 'A';
    $Icon = $params->Icon;
    $Order = $params->Order;
    $Country = $params->Country;


    /* asigna una cadena descriptiva a la variable $Type según su valor numérico. */
    switch ($Type) {
        case 0:
            $Type = 'CASINO';
            break;
        case 1:
            $Type = 'LIVECASINO';
            break;
        case 2:
            $Type = 'VIRTUAL';
            break;
        case 3:
            $Type = 'MINIGAMES';
            break;
        case 4:
            $Type = 'PAYMENT';
            break;
        case 5:
            $Type = 'BINGO';
            break;
    }


    /* Crea una instancia, obtiene la transacción y define el tipo de categoría. */
    $CategoriaMandanteMySqlDAO = new CategoriaMandanteMySqlDAO();
    $Transaction = $CategoriaMandanteMySqlDAO->getTransaction();

    $CategoriaMandante = new CategoriaMandante($Id);

    if ($Type == '') {
        $Type = $CategoriaMandante->getTipo();
    }

    /* Se configura un objeto "CategoriaMandante" con diversos atributos y sus valores. */
    $CategoriaMandante->setDescripcion($Description);
    $CategoriaMandante->setTipo($Type);
    $CategoriaMandante->setSlug($Slug);
    $CategoriaMandante->setEstado($State);
    $CategoriaMandante->setImangen($Icon);
    $CategoriaMandante->setOrden($Order);

    /* Actualiza información de categoría y registra la acción en un log general. */
    $CategoriaMandante->setPaisId($Country);
    $CategoriaMandante->setFechaModif(date('Y-m-d H:i:s'));
    $CategoriaMandante->setUsumodifId($_SESSION['usuario2']);

    $CategoriaMandanteMySqlDAO = new CategoriaMandanteMySqlDAO($Transaction);
    $CategoriaMandanteMySqlDAO->update($CategoriaMandante);

    $GeneralLog = new GeneralLog();

    /* Establece valores de sesión para usuarios y IP en un registro general. */
    $GeneralLog->setUsuarioId($_SESSION['usuario2']);
    $GeneralLog->setUsuarioIp($_SESSION['dir_ip']);
    $GeneralLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $GeneralLog->setUsuariosolicitaIp($_SESSION['dir_ip']);
    $GeneralLog->setUsuarioaprobarId(0);
    $GeneralLog->setUsuarioaprobarIp('');

    /* Configura un registro general para un cambio de categoría en el sistema. */
    $GeneralLog->setTipo('CHANGECATEGORY');
    $GeneralLog->setUsucreaId(0);
    $GeneralLog->setUsumodifId(0);
    $GeneralLog->setEstado('A');
    $GeneralLog->setSoperativo('');
    $GeneralLog->setDispositivo($Global_dispositivo);

    /* Configura un registro general con datos sobre la edición de una categoría específica. */
    $GeneralLog->setSversion('');
    $GeneralLog->setImagen('');
    $GeneralLog->setExternoId($CategoriaMandante->getCatmandanteId());
    $GeneralLog->setCampo('');
    $GeneralLog->setTabla('categoria_mandante');
    $GeneralLog->setExplicacion('Se edito la categortia ' . $CategoriaMandante->getCatmandanteId() . '-' . $CategoriaMandante->getDescripcion());

    /* Se configura y se inserta un registro de log en la base de datos. */
    $GeneralLog->setMandante($CategoriaMandante->getMandante());
    $GeneralLog->setValorAntes('');
    $GeneralLog->setValorDespues('');

    $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
    $GeneralLogMySqlDAO->insert($GeneralLog);


    /* finaliza una transacción y establece una respuesta exitosa sin errores. */
    $Transaction->commit();

    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];

} catch (Exception $ex) {
    /* Manejo de excepciones que captura errores al actualizar una categoría y genera respuesta. */


    $response['HasError'] = true;
    $response['AlertType'] = 'Error al actualizar la categoria';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}
?>