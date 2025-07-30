<?php

use Backend\dto\Submenu;
use Backend\mysql\ReporteDinamicoMySlqDAO;
use Backend\dto\ReporteDinamico;
use Backend\dto\UsuarioMandante;

/**
 * ReporteDinamico/GuardarConfiguracion
 *
 * Guarda o actualiza la configuración de un reporte dinámico para un usuario autenticado.
 *
 * Este recurso permite almacenar o modificar la configuración de un reporte dinámico asociado a un usuario y su país
 * de operación. Si el usuario no está autenticado, se devuelve un error de autenticación.
 *
 * @param string $table : Nombre de la tabla a la que pertenece el reporte dinámico.
 * @param array $columns : Listado de columnas que serán configuradas en el reporte dinámico.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna un array vacío en caso de éxito.
 *
 * Objeto en caso de error por falta de autenticación:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Not authenticated";
 * $response["Data"] = [
 *      "AuthenticationStatus" => 0,
 *      "PermissionList" => [],
 * ];
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Valida si un usuario está autenticado y prepara un mensaje de error. */
if (!$_SESSION['logueado']) {
    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'danger';
    $response['AlertMessage'] = 'Not authenticated';
    $response['Data'] = [
        'AuthenticationStatus' => 0,
        'PermissionList' => [],
    ];
} else {

    /* Crea objetos de Submenu y UsuarioMandante utilizando parámetros y sesión del usuario. */
    $table = $params->table;
    $columns = $params->columns;
    $Submenu = new Submenu('', $table, 3);
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    if ($_SESSION['PaisCond'] === 'S') {
        $pais_id = $UsuarioMandante->getPaisId();
    } else {
        /* asigna un ID de país basado en la sesión o un valor por defecto. */

        $pais_id = !empty($_SESSION['PaisCondS']) ? $_SESSION['PaisCondS'] : '0';
    }


    /* Actualiza un reporte dinámico en la base de datos con nuevas configuraciones. */
    $ReporteDinamico = new ReporteDinamico('', $Submenu->submenuId, $_SESSION['mandante'], $pais_id);

    if ($ReporteDinamico->getReporteDinamicoId() != '') {
        $ReporteDinamico->setReporteDinamicoId('');
        $ReporteDinamico->setColumnas(json_encode($columns));
        $ReporteDinamico->setSubmenuId($Submenu->getSubmenuId());
        $ReporteDinamico->setMandante($_SESSION['mandante']);
        $ReporteDinamico->setUsumodifId($_SESSION['usuario']);

        $ReporteDinamicoMySqlDAO = new ReporteDinamicoMySlqDAO();
        $ReporteDinamicoMySqlDAO->update($ReporteDinamico);
        $ReporteDinamicoMySqlDAO->getTransaction()->commit();
    } else {
        /* crea un reporte dinámico y lo inserta en la base de datos. */

        $ReporteDinamico = new ReporteDinamico();

        $ReporteDinamico->setColumnas(json_encode($columns));
        $ReporteDinamico->setSubmenuId($Submenu->getSubmenuId());
        $ReporteDinamico->setMandante(strval($_SESSION['mandante']));
        $ReporteDinamico->setUsucreaId($_SESSION['usuario']);
        $ReporteDinamico->setPaisId($pais_id);

        $ReporteDinamicoMySqlDAO = new ReporteDinamicoMySlqDAO();
        $ReporteDinamicoMySqlDAO->insert($ReporteDinamico);
        $ReporteDinamicoMySqlDAO->getTransaction()->commit();
    }


    /* Inicializa un arreglo de respuesta con estado de éxito y sin errores. */
    $response = [];

    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}
?> 
