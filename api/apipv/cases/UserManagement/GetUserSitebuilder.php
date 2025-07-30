<?php

use Backend\dto\UsuarioSitebuilder;

/**
 * Obtiene información de usuarios de Sitebuilder según los parámetros proporcionados.
 *
 * @param array $_GET Parámetros de entrada:
 * @param int $_GET['UserId'] ID del usuario.
 * @param string $_GET['Name'] Nombre del usuario.
 * @param string $_GET['Login'] Login del usuario.
 * @param int $_GET['CountrySelect'] País seleccionado.
 * @param int $_GET['State'] Estado del usuario (1 para activo, 2 para inactivo).
 * @param string $_GET['dateFrom'] Fecha inicial para filtrar usuarios (formato Y-m-d).
 * @param string $_GET['dateTo'] Fecha final para filtrar usuarios (formato Y-m-d).
 * @param int $_GET['start'] Número de filas a omitir para la paginación.
 * @param int $_GET['count'] Número máximo de filas a devolver.
 *
 * @param array $_SESSION Variables de sesión utilizadas:
 * @param string $_SESSION['Global'] Indica si se usa un mandante global ('N' para no global).
 * @param int $_SESSION['mandante'] Mandante actual.
 * @param string $_SESSION['mandanteLista'] Lista de mandantes.
 * @param string $_SESSION['PaisCond'] Condición del país ('S' para usar el país de sesión).
 * @param int $_SESSION['pais_id'] ID del país de sesión.
 * @param int $_SESSION['PaisCondS'] País alternativo de sesión.
 *
 * @return array $response Respuesta estructurada:
 * - HasError: Indica si ocurrió un error (false si no hay errores).
 * - AlertType: Tipo de alerta ('success' si no hay errores).
 * - AlertMessage: Mensaje de alerta.
 * - ModelErrors: Errores del modelo (vacío si no hay errores).
 * - data: Datos de los usuarios obtenidos.
 * - pos: Posición inicial de los datos devueltos.
 * - total_count: Conteo total de usuarios que cumplen con los filtros.
 */

/* obtiene parámetros de una solicitud GET para procesar información del usuario. */
$UserId = $_GET['UserId'];
$Name = $_GET['Name'];
$Login = $_GET['Login'];
$CountrySelect = $_GET['CountrySelect'];
$State = $_GET['State'];
$DateFrom = $_GET['dateFrom'] ? date('Y-m-d 00:00:00', strtotime($_GET['dateFrom'])) : '';

/* procesa fechas y parámetros de paginación desde la URL y la sesión. */
$DateTo = $_GET['dateTo'] ? date('Y-m-d 23:59:59', strtotime($_GET['dateTo'])) : '';
$SkeepRows = $_GET['start'] ?: 0;
$MaxRows = $_GET['count'] ?: 10;
$Partner = $_SESSION['Global'] === 'N' ? $_SESSION['mandante'] : $_SESSION['mandanteLista'];

if ($DateFrom > $DateTo) $DateTo = date('Y-m-d 23:59:59', strtotime($DateFrom));

if ($_SESSION['PaisCond'] === 'S') $CountrySelect = $_SESSION['pais_id'];
else $CountrySelect = !empty($_SESSION['PaisCondS']) ? $_SESSION['PaisCondS'] : $CountrySelect;

$rules = [];

/* Se construyen reglas de filtrado para obtener usuarios específicos desde una base de datos. */
if (!empty($UserId)) array_push($rules, ['field' => 'usuario_sitebuilder.usuariositebuilder_id', 'data' => $UserId, 'op' => 'eq']);
if (!empty($Name)) array_push($rules, ['field' => 'usuario.nombre', 'data' => $Name, 'op' => 'cn']);
if (!empty($CountrySelect)) array_push($rules, ['field' => 'usuario.pais_id', 'data' => $CountrySelect, 'op' => 'eq']);
if (!empty($DateFrom)) array_push($rules, ['field' => 'usuario_sitebuilder.fecha_crea', 'data' => $DateFrom, 'op' => 'ge']);
if (!empty($DateTo)) array_push($rules, ['field' => 'usuario_sitebuilder.fecha_crea', 'data' => $DateTo, 'op' => 'le']);
if (!empty($State)) array_push($rules, ['field' => 'usuario_sitebuilder.estado', 'data' => $State == 1 ? 'A' : 'I', 'op' => 'eq']);
if (!empty($Login)) array_push($rules, ['field' => 'usuario_sitebuilder.login', 'data' => $Login, 'op' => 'cn']);
array_push($rules, ['field' => 'usuario.mandante', 'data' => $Partner, 'op' => 'in']);

$filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$UsuarioSitebuilder = new UsuarioSitebuilder();
$users = $UsuarioSitebuilder->getUsuarioSitebuilderCustom('usuario.usuario_id, usuario_sitebuilder.usuariositebuilder_id, usuario_sitebuilder.login, usuario_mandante.nombres, usuario_mandante.apellidos, pais.pais_nom, usuario_sitebuilder.estado, usuario.pais_id, usuario_sitebuilder.fecha_crea', 'usuario_sitebuilder.usuariositebuilder_id', 'ASC', $SkeepRows, $MaxRows, $filters, true);

/* Convierte datos de usuarios en JSON a un arreglo asociativo estructurado. */
$users = json_decode($users, true);

$data = [];

foreach ($users['data'] as $value) {
    $array = [];

    $array['Id'] = $value['usuario_sitebuilder.usuariositebuilder_id'];
    $array['UserBackofficeId'] = $value['usuario.usuario_id'];
    $array['Username'] = $value['usuario_mandante.nombres'] . ' ' . $value['usuario_mandante.apellidos'];
    $array['State'] = $value['usuario_sitebuilder.estado'] === 'A' ? 1 : 2;
    $array['Login'] = $value['usuario_sitebuilder.login'];
    $array['Country'] = $value['pais.pais_nom'];
    $array['DateCreated'] = $value['usuario_sitebuilder.fecha_crea'];

    array_push($data, $array);
}


/* Código que inicializa una respuesta sin errores y con datos a retornar. */
$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $data;

/* Se asignan valores a un array de respuesta: posición y conteo total de usuarios. */
$response['pos'] = $SkeepRows;
$response['total_count'] = $users['count'][0]['.count'];
?>