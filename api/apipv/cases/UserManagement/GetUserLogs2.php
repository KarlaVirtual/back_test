<?php

use Backend\dto\UsuarioVerificacion;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;

/* Función que mapea valores numéricos a estados y viceversa. */
/**
 * Mapea valores numéricos a estados y viceversa.
 *
 * @param mixed $value El valor a mapear, puede ser numérico o un estado.
 * @return mixed El estado correspondiente si el valor es numérico, o el valor numérico si el estado es una cadena.
 */
function getLogState($value)
{
    $states = ['1' => 'A', '2' => 'R', '3' => 'P', 'NE' => 'NE', 'I' => 'I'];
    return is_numeric($value) ? $states[$value] : array_search($value, $states);
}

/**
 * Obtiene registros de verificación de usuarios según los parámetros proporcionados.
 *
 * @param array $_REQUEST Parámetros de entrada:
 * @param int $_REQUEST['Id'] ID del usuario.
 * @param string $_REQUEST['UserName'] Nombre del usuario.
 * @param int $_REQUEST['CountrySelect'] País seleccionado.
 * @param string $_REQUEST['dateFrom'] Fecha inicial para filtrar registros (formato Y-m-d).
 * @param string $_REQUEST['dateTo'] Fecha final para filtrar registros (formato Y-m-d).
 * @param mixed $_REQUEST['State'] Estado del registro.
 * @param int $_REQUEST['start'] Número de filas a omitir para la paginación.
 * @param int $_REQUEST['count'] Número máximo de filas a devolver.
 * @param int $_REQUEST['TypeVerification'] Tipo de verificación (0 o 1).
 * @param int $_REQUEST['TypeDecision'] Tipo de decisión (0 o 1).
 * @param int $_REQUEST['Type'] Tipo de registro (0 o 1).
 * @param int $_REQUEST['Overall'] Indicador de resumen general (1 para activar).
 * @param int $_REQUEST['Note'] Nota específica para filtrar.
 * @param int $_REQUEST['VerificationProvider'] Proveedor de verificación.
 *
 * @param array $_SESSION Variables de sesión utilizadas:
 * @param int $_SESSION['mandante'] Mandante actual.
 * @param string $_SESSION['PaisCond'] Condición del país ('N' o 'S').
 * @param int $_SESSION['pais_id'] ID del país de sesión.
 * @param int $_SESSION['PaisCondS'] País alternativo de sesión.
 *
 * @return array $response Respuesta estructurada:
 * - HasError: Indica si ocurrió un error (false si no hay errores).
 * - AlertTypeVerification: Tipo de alerta ('success' si no hay errores).
 * - AlertMessage: Mensaje de alerta.
 * - ModelErrors: Errores del modelo (vacío si no hay errores).
 * - data: Datos de los registros obtenidos.
 * - pos: Posición inicial de los datos devueltos.
 * - total_count: Conteo total de registros que cumplen con los filtros.
 */

$Id = $_REQUEST['Id'];

/* Código que procesa datos de entrada y asigna valores a variables específicas. */
$UserName = $_REQUEST['UserName'];
$Country = $_REQUEST['CountrySelect'] ?: $_REQUEST['CountrySelect2'];
$DateFrom = !empty($_REQUEST['dateFrom']) ? date('Y-m-d 00:00:00', strtotime($_REQUEST['dateFrom'])) : '';
$DateTo = !empty($_REQUEST['dateTo']) ? date('Y-m-d 23:59:59', strtotime($_REQUEST['dateTo'])) : '';
$State = in_array($_REQUEST['State'], [1, 2, 3, 'NE', 'I']) ? getLogState($_REQUEST['State']) : '';
$Start = $_REQUEST['start'] ?: 0;

/* valida y asigna valores de parámetros de entrada o sesión. */
$Count = $_REQUEST['count'] ?: 1000;
$Partner = $_SESSION['mandante'] == -1 ? '' : $_SESSION['mandante'];
$TypeVerification = in_array($_REQUEST['TypeVerification'], [0, 1]) ? $_REQUEST['TypeVerification'] : '';
$TypeDecision = in_array($_REQUEST['TypeDecision'], [0, 1]) ? $_REQUEST['TypeDecision'] : '';
$Type = in_array($_REQUEST['Type'], [0, 1]) ? $_REQUEST['Type'] : '';
$Overrall = ($_REQUEST['Overall'] == 1) ? 1 : '';

/* Se obtienen parámetros y se crea un objeto Proveedor con la verificación. */
$Note = $_REQUEST['Note'];
$VerificationProvider = $_REQUEST['VerificationProvider'];


$Proveedor = new Proveedor($VerificationProvider);
$nameProvider = $Proveedor->descripcion;


/* selecciona un clasificador basado en el proveedor de verificación. */
switch ($nameProvider) {
    case 'JUMIO':
        $Clasificador = new Clasificador("", "VERIFICAJUMIO");
        $VerificationProvider = $Clasificador->clasificadorId;
        break;
    case 'SUMSUB':
        $Clasificador = new Clasificador("", "VERIFICASUMSUB");
        $VerificationProvider = $Clasificador->clasificadorId;
        break;
}


if (empty($Country)) $Country = $_SESSION['PaisCond'] === 'N' ? $_SESSION['PaisCondS'] : $_SESSION['pais_id'];

if (!empty($DateFrom) && !empty($DateTo) && $DateFrom > $DateTo) $DateTo = date('Y-m-d 23:59:59', strtotime($DateFrom . ' + 1 days'));

$rules = [];

//   array_push($rules, ['field' => 'usuario_verificacion.estado', 'data' => 'NA', 'op' => 'ne']);

if (!empty($State)) array_push($rules, ['field' => 'usuario_verificacion.estado', 'data' => $State, 'op' => 'eq']);
if (!empty($Id)) array_push($rules, ['field' => 'usuario_verificacion.usuario_id', 'data' => $Id, 'op' => 'eq']);
if (!empty($UserName)) array_push($rules, ['field' => 'usuario.login', 'data' => $UserName, 'op' => 'cn']);
if (!empty($Country)) array_push($rules, ['field' => 'usuario_verificacion.pais_id', 'data' => $Country, 'op' => 'eq']);
if (!empty($DateFrom)) array_push($rules, ['field' => 'usuario_verificacion.fecha_crea', 'data' => $DateFrom, 'op' => 'ge']);
if (!empty($DateTo)) array_push($rules, ['field' => 'usuario_verificacion.fecha_crea', 'data' => $DateTo, 'op' => 'le']);
if (!empty($VerificationProvider)) array_push($rules, ['field' => 'usuario_verificacion.clasificador_id', 'data' => $VerificationProvider, 'op' => 'eq']);


/* Añade reglas de verificación según el tipo especificado en la variable. */
if ($TypeVerification != '') {

    if ($TypeVerification == 1) {

        array_push($rules, ['field' => 'clasificador.abreviado', 'data' => '"VERIFICAJUMIO", "VERIFICAAUCO"', 'op' => 'in']);
    } else {
        array_push($rules, ['field' => 'clasificador.abreviado', 'data' => 'VERIFICAMANUAL', 'op' => 'eq']);
    }
}


/* Añade reglas a un array basado en el valor de $TypeDecision. */
if ($TypeDecision != '') {

    if ($TypeDecision == 1) {

        array_push($rules, ['field' => 'usuario_verificacion.usumodif_id', 'data' => '0', 'op' => 'eq']); //Automatico
    } else {
        array_push($rules, ['field' => 'usuario_verificacion.usumodif_id', 'data' => '0', 'op' => 'ne']); // Operador
    }


}
if ($Partner != '') array_push($rules, ['field' => 'usuario_verificacion.mandante', 'data' => $Partner, 'op' => 'eq']);
if ($Type != '') array_push($rules, ['field' => 'usuario_verificacion.tipo', 'data' => $Type == 0 ? 'USUVERIFICACION' : 'USUACTUALIZACIONDATOS', 'op' => 'eq']);

/* define reglas de validación y un mapa de notas de rechazo. */
array_push($rules, ['field' => 'usuario_verificacion.estado', 'data' => 'NA', 'op' => 'ne']);
array_push($rules, ['field' => "verificacion_log.tipo", "data" => "FINALDECISION", "op" => "eq"]);
$mapaNotas = [
    0 => 'Rechazado por extraccion de datos',
    1 => 'Rechazado por datos',
    2 => 'Rechazado por imagen',
    3 => 'Rechazado por usabilidad',
    4 => 'Rechazado por semejanza',
    5 => 'Rechazado por vivacidad',
];


/* valida y agrega una nota a un filtro en formato JSON. */
if ($Note !== '' && $Note !== null) {
    $Note = $mapaNotas[$Note];
    array_push($rules, ['field' => 'usuario_verificacion.observacion', 'data' => $Note, 'op' => 'eq']);
}


$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

/* configura consultas SQL según la variable $Overrall. */
$select = '';
$groupby = '';
if ($Overrall == 1) {
    $select = 'COUNT(*) count, usuario_verificacion.estado,clasificador.abreviado, pais.iso';
    $groupby = 'usuario_verificacion.estado';
    $Count = 100;
} else {
    /* Selecciona datos de verificación de usuarios junto con clasificador y país. */

    $select = 'usuario_verificacion.*, clasificador.abreviado, pais.iso';
}

/* Se crea un objeto para verificar usuarios y se obtiene información en formato JSON. */
$UsuarioVerificacion = new UsuarioVerificacion();
$query = $UsuarioVerificacion->getUsuarioVerificacionCustom($select, 'usuario_verificacion.usuverificacion_id', 'desc', $Start, $Count, $filter, true, $groupby);
$query = json_decode($query, true);

$log = [];
if ($Overrall == 1) {

    /* Clasifica y cuenta datos según el estado de verificación del usuario. */
    $data = [];
    foreach ($query['data'] as $value) {
        $estado = $value['usuario_verificacion.estado'];

        if ($value['usuario_verificacion.estado'] == 'A') {
            $data['Approved'] = $value['.count'];
        }
        if ($value['usuario_verificacion.estado'] == 'R') {
            $data['Rejected'] = $value['.count'];
        }
        if ($value['usuario_verificacion.estado'] == 'NE') {
            $data['NotExecuted'] = $value['.count'];
        }
        if ($value['usuario_verificacion.estado'] == 'P') {
            $data['Pending'] = $value['.count'];
        }
        if ($value['usuario_verificacion.estado'] == 'I') {
            $data['Initiated'] = $value['.count'];
        }
    }

    /* Agrega el elemento $data al final del arreglo $log en PHP. */
    array_push($log, $data);

} else {
    foreach ($query['data'] as $key => $value) {

        /* asigna datos de verificación a un array, incluyendo ID y estado. */
        $data = [];
        $data['Id'] = $value['usuario_verificacion.usuverificacion_id'];
        $data['StateId'] = getLogState($value['usuario_verificacion.estado']);
        $data['Time'] = $value['usuario_verificacion.fecha_crea'];
        if ($value['clasificador.abreviado'] == 'VERIFICASUMSUB') {
            $data['VerificationProvider'] = "SUMSUB";
        } else if ($value['clasificador.abreviado'] == 'VERIFICAJUMIO') {
            /* Condición que asigna "JUMIO" a VerificationProvider si el clasificador es VERIFICAJUMIO. */

            $data['VerificationProvider'] = "JUMIO";
        }

        /* Asigna valores a un arreglo basado en datos de usuario y país. */
        $data['UserName'] = $value['usuario_verificacion.usuario_id'];
        $data['UserNameApproval'] = $value['usuario_verificacion.usumodif_id'];
        $data['UserGenerated'] = $value['usuario_verificacion.usucrea_id'];
        $data['Country'] = strtolower($value['pais.iso']);
        $data['Note'] = $value['usuario_verificacion.observacion'];
        $data['TypeVerification'] = $value['clasificador.abreviado'] === 'Proveedor' ? 1 : 0;

        /* Evalúa condiciones para establecer tipos y las agrega a un registro de log. */
        $data['TypeDecision'] = $value['usuario_verificacion.usumodif_id'] === 'Automatico' ? 1 : 0;
        $data['Type'] = $value['usuario_verificacion.tipo'] === 'USUACTUALIZACIONDATOS' ? 1 : 0;

        array_push($log, $data);
    }
}


/* configura una respuesta sin errores con datos y mensajes específicos. */
$response['HasError'] = false;
$response['AlertTypeVerification'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $log;
$response['pos'] = $Start;

/* Asigna el total a 1 si $Overrall es 1, o al conteo de la consulta. */
$response['total_count'] = ($Overrall == 1) ? 1 : $query['count'][0]['.count'];
?>