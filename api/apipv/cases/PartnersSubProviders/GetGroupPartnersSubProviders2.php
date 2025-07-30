<?php

use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use FontLib\Table\Type\head;

/**
 * PartnersSubProviders/GetGroupPartnersSubProviders2
 *
 * Obtención de subproveedores y proveedores asociados.
 *
 * Este recurso permite recuperar una lista de subproveedores y proveedores asociados en función de múltiples filtros,
 * como identificador, nombre, estado y relación con mandantes. Se utiliza paginación y ordenamiento para estructurar los resultados.
 *
 * @param string $_REQUEST ['Country'] : País de filtrado.
 * @param string $_REQUEST ['Partner'] : Identificador del socio comercial.
 * @param int $_REQUEST ['ProviderId'] : Identificador del proveedor.
 * @param int $_REQUEST ['SubProviderId'] : Identificador del subproveedor.
 * @param int $_REQUEST ['Type'] : Tipo de subproveedor (por defecto, '1').
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si ocurrió un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada.
 *  - *AlertMessage* (string): Mensaje de alerta en caso de error.
 *  - *ModelErrors* (array): Lista de errores de validación.
 *  - *Data* (array): Contiene los resultados de la consulta, incluyendo:
 *      - *ExcludedProvidersList* (array): Lista de subproveedores excluidos.
 *      - *IncludedProvidersList* (string): Lista de subproveedores incluidos.
 *  - *total_count* (int): Total de registros encontrados en la consulta.
 *  - *data* (array): Contiene la lista de subproveedores recuperados.
 *
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* recoge datos de una solicitud y crea un objeto de Subproveedor. */
$Country = $_REQUEST['Country'];
$Partner = $_REQUEST['Partner'];
$ProviderId = $_REQUEST['ProviderId'];
$SubProviderId = $_REQUEST['SubProviderId'];
$Type = $_REQUEST['Type'] ?: '1';

$Subproveedor = new Subproveedor();

/* Instancia de la clase SubproveedorMandante en el espacio de nombres Backend\dto. */
$SubproveedorMandante = new \Backend\dto\SubproveedorMandante();

switch ($Type) {
    case '1':
        /* Asigna el valor 'CASINO' a la variable $Type si el caso es '1'. */

        $Type = 'CASINO';
        break;
    case '2':
        /* asigna 'LIVECASINO' a la variable $Type cuando se cumple un caso específico. */

        $Type = 'LIVECASINO';
        break;
    case '3':
        /* asigna el tipo 'PAYMENT' cuando el caso es '3'. */

        $Type = 'PAYMENT';
        break;
    case '4':
        /* asigna "VIRTUAL" a la variable $Type si se cumple la condición '4'. */

        $Type = 'VIRTUAL';
        break;
    case '5':
        /* asigna 'PAYOUT' a la variable $Type cuando se recibe el caso '5'. */

        $Type = 'PAYOUT';
        break;
    case '6':
        /* asigna 'VERIFICATION' a la variable $Type si el caso es '6'. */

        $Type = 'VERIFICATION';
        break;
    case '7':
        /* asigna 'CRM' a la variable $Type si el caso es '7'. */

        $Type = 'CRM';
        break;
    case '8':
        /* Asigna el valor 'FIRMA' a la variable $Type si el caso es '8'. */

        $Type = 'FIRMA';
        break;
    case '9':
        /* asigna el tipo 'MENSAJERIA' si el caso es '9'. */

        $Type = 'MENSAJERIA';
        break;
    case '10':
        /* asigna el tipo 'SPORTS' si el caso es '10'. */

        $Type = 'SPORT';
        break;
    default:
}


/* Generación filtrado para posterior consulta */
$rules = [];

if (!empty($ProviderId)) array_push($rules, ['field' => 'proveedor.proveedor_id', 'data' => $ProviderId, 'op' => 'eq']);

/* Solicitud subproveedor respecto su ID */
if (!empty($SubProviderId)) array_push($rules, ['field' => 'subproveedor.subproveedor_id', 'data' => $SubProviderId, 'op' => 'eq']);

/* Se agregan reglas de filtrado y se obtienen subproveedores con condiciones específicas. */

array_push($rules, ['field' => 'subproveedor.tipo', 'data' => $Type, 'op' => 'eq']);

array_push($rules, ['field' => 'subproveedor_mandante.mandante', 'data' => $Partner, 'op' => 'eq']);
array_push($rules, ['field' => 'subproveedor_mandante.estado', 'data' => 'A', 'op' => 'eq']);

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$subproviders = $SubproveedorMandante->getSubproveedoresMandanteCustom('subproveedor.*, proveedor.descripcion', 'subproveedor.descripcion', 'asc', 0, 1000, $filter, true);


/* decodifica JSON y crea un array de proveedores. */
$subproviders = json_decode($subproviders);

$exclude_providers = [];

foreach ($subproviders->data as $key => $value) {
    $data['id'] = $value->{'subproveedor.subproveedor_id'};
    $data['value'] = $value->{'subproveedor.descripcion'} . ' (' . $value->{'proveedor.descripcion'} . ')';

    array_push($exclude_providers, $data);
}


/* Solicitud proveedores por partner y país */
$rules = [];

if ($Partner != '') array_push($rules, ['field' => 'subproveedor_mandante_pais.mandante', 'data' => $Partner, 'op' => 'eq']);
if (!empty($Country)) array_push($rules, ['field' => 'subproveedor_mandante_pais.pais_id', 'data' => $Country, 'op' => 'eq']);

/* Se agregan reglas de filtro y se codifican en formato JSON. */
array_push($rules, ['field' => 'subproveedor.tipo', 'data' => $Type, 'op' => 'eq']);
array_push($rules, ['field' => 'subproveedor_mandante_pais.estado', 'data' => 'A', 'op' => 'eq']);
array_push($rules, ['field' => 'subproveedor_mandante.estado', 'data' => 'A', 'op' => 'eq']);

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$SubproveedorMandantePais = new SubproveedorMandantePais();


/* obtiene y procesa datos de subproveedores para crear una cadena de IDs. */
$subproviders_partner = $SubproveedorMandantePais->getSubproveedoresMandantePaisCustom('subproveedor_mandante_pais.*, subproveedor.*', 'subproveedor_mandante_pais.orden', 'asc', 0, 1000, $filter, true);

$subproviders_partner = json_decode($subproviders_partner);

$providers_select = '';

foreach ($subproviders_partner->data as $key => $value) {
    $providers_select .= $value->{'subproveedor.subproveedor_id'} . ',';
}


/* configura una respuesta exitosa sin errores y lista de proveedores. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = [
    'ExcludedProvidersList' => $exclude_providers,
    'IncludedProvidersList' => rtrim($providers_select, ',')
];


/* cuenta elementos y asigna datos a un array de respuesta. */
$response['total_count'] = $subproviders->count[0]->{'.count'};
$response['data'] = [];
?>