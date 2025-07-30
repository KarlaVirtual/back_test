<?php

use Backend\dto\GeneralLog;
use Backend\dto\CategoriaMandante;
use Backend\mysql\GeneralLogMySqlDAO;
use Backend\mysql\CategoriaMandanteMySqlDAO;

/**
 * Crear una nueva categoría asociada a un partner.
 *
 * Este script permite la creación de una categoría asociada a un partner, 
 * definiendo parámetros como descripción, tipo, estado, país, entre otros. 
 * Además, registra un log de la operación realizada.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param string $params->Slug Identificador único de la categoría.
 * @param int $params->State Estado de la categoría (1 para inactivo, otro valor para activo).
 * @param int $params->Country ID del país asociado.
 * @param string $params->Description Descripción de la categoría.
 * @param int $params->Order Orden de la categoría.
 * @param int $params->Type Tipo de categoría (0: CASINO, 1: LIVECASINO, 2: VIRTUAL, etc.).
 * @param string $params->Icon URL del ícono asociado a la categoría.
 * @param int|string $params->Partner ID del partner asociado o vacío.
 * 
 *
 * @return array $response Respuesta con los siguientes valores:
 *     - bool $response['HasError'] Indica si hubo un error.
 *     - string $response['AlertType'] Tipo de alerta (success o mensaje de error).
 *     - string $response['AlertMessage'] Mensaje de alerta.
 *     - array $response['ModelErrors'] Errores del modelo (vacío si no hay errores).
 *
 * @throws Exception Si no se especifica un partner o país, o si ocurre un error al crear la categoría.
 */

try {
    /*Obtención de parámetros*/
    $params = file_get_contents('php://input');
    $params = base64_decode($params);
    $params = json_decode($params);

    $Slug = $params->Slug;
    $State = $params->State == 1 ? 'I' : 'A';
    $Country = $params->Country;
    $Description = $params->Description;
    $Order = $params->Order;
    $Type = $params->Type;
    $Icon = $params->Icon;
    $Partner = $params->Partner;

    /*Definición y delimitación del partner*/
    if ($Partner == '' && $_SESSION['mandante'] == -1) {
        $Partner = -1;
        $Country = 0;
    }

    if ($Partner == '' && $Country == '') throw new Exception('Error al crear la categoria por mandante y pais', '01');

    /*Definición del tipo basado en el type*/
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

    $CategoriaMandanteMySqlDAO = new CategoriaMandanteMySqlDAO();
    $Transaction = $CategoriaMandanteMySqlDAO->getTransaction();

    $Global_dispositivo = "";

    /*Generación de categoría mandante*/
    $CategoriaMandante = new CategoriaMandante();

    $CategoriaMandante->setDescripcion($Description);
    $CategoriaMandante->setTipo($Type);
    $CategoriaMandante->setMandante($Partner);
    $CategoriaMandante->setSlug($Slug);
    $CategoriaMandante->setEstado('A');
    $CategoriaMandante->setImangen($Icon);
    $CategoriaMandante->setUsucreaId($_SESSION['usuario2']);
    $CategoriaMandante->setUsumodifId(0);
    $CategoriaMandante->setOrden($Order);
    $CategoriaMandante->setPaisId($Country);

    $CategoriaMandanteMySqlDAO = new CategoriaMandanteMySqlDAO($Transaction);
    $ID = $CategoriaMandanteMySqlDAO->insert($CategoriaMandante);

    /*Registro log de creación*/
    $GeneralLog = new GeneralLog();
    $GeneralLog->setUsuarioId($_SESSION['usuario2']);
    $GeneralLog->setUsuarioIp($_SESSION['dir_ip']);
    $GeneralLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $GeneralLog->setUsuariosolicitaIp($_SESSION['dir_ip']);
    $GeneralLog->setUsuarioaprobarId(0);
    $GeneralLog->setUsuarioaprobarIp('');
    $GeneralLog->setTipo('CREATECATEGORY');
    $GeneralLog->setUsucreaId(0);
    $GeneralLog->setUsumodifId(0);
    $GeneralLog->setEstado('A');
    $GeneralLog->setSoperativo('');
    $GeneralLog->setDispositivo($Global_dispositivo);
    $GeneralLog->setSversion('');
    $GeneralLog->setImagen('');
    $GeneralLog->setExternoId($CategoriaMandante->getCatmandanteId());
    $GeneralLog->setCampo('');
    $GeneralLog->setTabla('categoria_mandante');
    $GeneralLog->setExplicacion('Se creo la categoria ' . $ID . '-' . $Description);
    $GeneralLog->setMandante($Partner);
    $GeneralLog->setValorAntes('');
    $GeneralLog->setValorDespues('');

    $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
    $GeneralLogMySqlDAO->insert($GeneralLog);

    $Transaction->commit();

    /*Formato de respuesta*/
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];

} catch (Exception $ex) {

    /*Formato respuesta de error*/
    $response['HasError'] = true;
    $response['AlertType'] = 'Error al crear la categoria';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}
?>