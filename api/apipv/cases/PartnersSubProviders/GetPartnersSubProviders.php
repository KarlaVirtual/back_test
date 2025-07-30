<?php

use Backend\dto\SubproveedorMandantePais;

/**
 * PartnersSubProviders/GetPartnersSubProviders
 *
 * Obtener listado de Subproveedores Mandantes por País
 *
 * Permite obtener un listado de subproveedores mandantes filtrando por diferentes criterios como proveedor, país, estado, entre otros.
 *
 * @param int $start : Índice de inicio para la paginación de resultados.
 * @param int $count : Cantidad de registros a obtener.
 * @param int $ProviderId : ID del proveedor asociado.
 * @param string $Partner : Identificador del mandante asociado.
 * @param string $PartnerReference : Referencia del mandante.
 * @param int $CountrySelect : ID del país seleccionado.
 * @param int $Id : ID del subproveedor mandante país.
 * @param string $IsActivate : Estado del subproveedor ("A" para activo, "I" para inactivo).
 * @param float $Maximum : Valor máximo permitido.
 * @param float $Minimum : Valor mínimo permitido.
 * @param string $IsVerified : Estado de verificación ("A" para verificado, "I" para no verificado).
 * @param string $FilterCountry : Filtro de país aplicado.
 * @param int $SubProviderId : ID del subproveedor asociado.
 * @param bool $BonusSystem : Indica si el subproveedor cuenta con sistema de bonificación (true o false).
 * @param bool $TestUsers : Indica si el subproveedor es de prueba (true o false).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna un array vacío.
 *  - *data* (array): Contiene la lista de subproveedores obtenidos con sus respectivos atributos.
 *
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene parámetros de solicitud, estableciendo valores por defecto si están vacíos. */
$start = $_REQUEST['start'] ?: 0;
$count = $_REQUEST['count'] ?: 100;
$ProviderId = $_REQUEST['ProviderId'];
$Partner = $_REQUEST['Partner'];
$PartnerReference = $_REQUEST['PartnerReference'];
$CountrySelect = $_REQUEST['CountrySelect'];

/* recoge parámetros de una solicitud HTTP para su posterior procesamiento. */
$Id = $_REQUEST['Id'];
$IsActivate = $_REQUEST['IsActivate'];
$Maximum = $_REQUEST['Maximum'];
$Minimum = $_REQUEST['Minimum'];
$IsVerified = $_REQUEST['IsVerified'];
$FilterCountry = $_REQUEST['FilterCountry'];

/* Captura y filtra datos de SubProviderId, BonusSystem y TestUsers en PHP. */
$SubProviderId = $_REQUEST['SubProviderId'];
$BonusSystem = $_REQUEST['BonusSystem']; /* descripcion de la variable: permite capturar si se desea filtrar por BonusSystem */
$TestUsers = $_REQUEST['TestUsers']; /* descripcion de la variable: permite capturar si se desea filtrar por TestUsers */

$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.provmandante_id", "data" => $Id, "op" => "eq"));
}


/* añade reglas a un arreglo basándose en condiciones específicas. */
if ($Maximum != "") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.max", "data" => $Maximum, "op" => "eq"));
}

if ($SubProviderId != "") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.subproveedor_id", "data" => $SubProviderId, "op" => "eq"));
}


/* Agrega reglas a un arreglo si los valores no están vacíos. */
if ($ProviderId != "") {
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $ProviderId, "op" => "eq"));
}

if ($Minimum != "") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.min", "data" => $Minimum, "op" => "eq"));
}

// Si el usuario esta condicionado por el mandante y no es de Global

/* Condiciona la inclusión de reglas basadas en variables de sesión relacionadas con "mandante". */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {
    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "subproveedor_mandante_pais.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }
}


/* Agrega reglas al arreglo según el estado de verificación y activación. */
if ($IsVerified == "A") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.verifica", "data" => "A", "op" => "eq"));
} else if ($IsVerified == "I") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.verifica", "data" => "I", "op" => "eq"));
}

if ($IsActivate == "A") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
} else if ($IsActivate == "I") {
    /* Condiciona la adición de reglas basadas en el estado de activación. */

    array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "I", "op" => "eq"));
}


/* agrega reglas para filtrar por país y sistema de bonificación. */
if ($CountrySelect != "") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.pais_id", "data" => $CountrySelect, "op" => "eq"));
}

/*proposito de la regla nueva filtrar por si tiene o no bonusSystem*/
if ($BonusSystem == "true") {
    array_push($rules, array("field" => "subproveedor_mandante_pais.bonus_system", "data" => "S", "op" => "eq"));
} else if ($BonusSystem == "false") {
    /* Agrega una regla si el sistema de bonificación está desactivado. */

    array_push($rules, array("field" => "subproveedor_mandante_pais.bonus_system", "data" => "S", "op" => "ne"));
}


/* crea un filtro en formato JSON y obtiene subproveedores personalizadamente. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$SubproveedorMandantePais = new SubproveedorMandantePais();

$subproviders_partner = $SubproveedorMandantePais->getSubproveedoresMandantePaisCustom('subproveedor_mandante_pais.*, subproveedor.*,proveedor.descripcion', 'subproveedor_mandante_pais.orden', 'asc', 0, 1000, $json, true);


/* Decodifica un JSON y lo almacena en una variable para procesar proveedores. */
$query = json_decode($subproviders_partner);

$providers = [];

foreach ($query->data as $key => $value) {

    /* Crea un array asociativo con datos de un proveedor y su estado de verificación. */
    $data = [];
    $data['Id'] = $value->{'subproveedor_mandante_pais.provmandante_id'};
    $data['Provider'] = $value->{"proveedor.descripcion"};
    $data['Partner'] = $value->{"subproveedor_mandante_pais.mandante"};
    $data['State'] = $value->{"subproveedor_mandante_pais.estado"};
    $data['isVerify'] = $value->{"subproveedor_mandante_pais.verifica"};

    /* Asigna valores de un objeto a un array usando claves específicas relacionadas con países. */
    $data['filterCountry'] = $value->{"subproveedor_mandante_pais.filtro_pais"};
    $data['Maximum'] = $value->{"subproveedor_mandante_pais.max"};
    $data['Minimum'] = $value->{"subproveedor_mandante_pais.min"};
    $data['Detail'] = $value->{'subproveedor_mandante_pais.detalle'};
    $data['Image'] = $value->{"subproveedor_mandante_pais.imagen"};
    $data["BonusSystem"] = $value->{"subproveedor_mandante_pais.bonus_system"};  /* proposito: obtener el valor del campo BonusSystem*/

    /* asigna datos y convierte valores a booleanos según condiciones específicas. */
    $data["TestUsers"] = $value->{"subproveedor_mandante_pais.usuarios_prueba"};  /* proposito: obtener el valor del campo TestUsers*/

    if ($data["BonusSystem"] == "S") {  /* descripcion de la variable: permite convertir la variable en boolean en caso de ser si sera true */
        $data["BonusSystem"] = true;
    } else {
        $data["BonusSystem"] = false;  /* descripcion de la variable: permite convertir la variable en boolean en caso de ser no sera false */
    }

    /* Conversion de TestUsers a S o N */

    /* Convierte el valor "S" a verdadero; de lo contrario, lo establece en falso. */
    if ($data["TestUsers"] == "S") {
        $data["TestUsers"] = true;
    } else {
        $data["TestUsers"] = false;
    }

    array_push($providers, $data);
}


/* Código que define una respuesta exitosa con datos y sin errores. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $providers;
