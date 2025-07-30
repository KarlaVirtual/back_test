<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\CategoriaProducto;
use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;
use Backend\dto\ProductoMandante;


/**
 * Procesa y obtiene los detalles de definición de lealtad.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->StartTimeLocal Fecha de inicio en formato local.
 * @param string $params->EndTimeLocal Fecha de fin en formato local.
 * @param string $params->LealtadId ID de lealtad.
 * @param int $params->MaxRows Número máximo de filas a recuperar.
 * @param int $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->SkeepRows Número de filas a omitir.
 *
 * @return array $response Respuesta estructurada con:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Resultado procesado con los detalles de lealtad.
 */

/* captura datos JSON y asigna fechas y un ID de lealtad. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$LealtadId = $_REQUEST["Id"];


/* Asignación de parámetros desde el objeto `$params` a variables locales en PHP. */
$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;


/* Se agregan reglas basado en el valor de LealtadId no vacío. */
$rules = [];


if ($LealtadId != "") {
    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "$LealtadId", "op" => "eq"));
}


// Si el usuario esta condicionado por País

/* Condicional que añade reglas si el usuario no es global y está condicionado. */
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* Se establece un filtro y valores predeterminados para filas y elementos ordenados. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* asigna un valor predeterminado y codifica un filtro en JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$lealtadinterna = new lealtadinterna();


/* obtiene y decodifica datos de lealtad interna en formato JSON. */
$lealtad = $lealtadinterna->getlealtadCustom(" lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", $SkeepRows, $MaxRows, $json, true);

$lealtad = json_decode($lealtad);

$final = [];

foreach ($lealtad->data as $key => $value) {


    /* agrega una regla de comparación si $LealtadId no está vacío. */
    $rules = [];


    if ($LealtadId != "") {
        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $LealtadId, "op" => "eq"));
    }

    /* if($PlayerExternalId != ""){
         array_push($rules, array("field" => "promocional_log.usuario_id", "data" => $PlayerExternalId , "op" => "eq"));
     }*/


    /* define un filtro y asigna valores predeterminados a variables vacías. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Inicializa $MaxRows a 30 si está vacío y prepara datos para registro. */
    if ($MaxRows == "") {
        $MaxRows = 30;
    }

    $json = json_encode($filtro);

    // $PromocionalLog = new PromocionalLog();

    // $lealtadlog = $PromocionalLog->getPromocionalLogsCustom(" count(promocional_log.promolog_id) count, sum(promocional_log.valor) valor ", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json, true);

    // $lealtadlog = json_decode($lealtadlog);

    $rules = [];


    /* Crea un filtro JSON con reglas basadas en la lealtad, si está definida. */
    if ($LealtadId != "") {
        array_push($rules, array("field" => "lealtad_detalle.lealtad_id", "data" => "$LealtadId", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    /* Crea un objeto y obtiene detalles de lealtad en formato JSON. */
    $lealtadDetalle = new lealtadDetalle();

    $lealtaddetalles = $lealtadDetalle->getlealtadDetallesCustom(" lealtad_detalle.* ", "lealtad_detalle.lealtad_detalle_id", "asc", $SkeepRows, $MaxRows, $json, true);


    $lealtaddetalles = json_decode($lealtaddetalles);


    /* Crea un array asociativo con datos de "lealtad_interna" de un objeto. */
    $array = [];

    $array["Id"] = $value->{"lealtad_interna.lealtad_id"};
    $array["Name"] = $value->{"lealtad_interna.nombre"};
    $array["Description"] = $value->{"lealtad_interna.descripcion"};
    $array["BeginDate"] = str_replace(" ", "T", $value->{"lealtad_interna.fecha_inicio"});

    /* asigna valores del objeto a un array con formato específico. */
    $array["BeginDate"] = str_replace(" ", " ", $value->{"lealtad_interna.fecha_inicio"});

    $array["EndDate"] = str_replace(" ", "T", $value->{"lealtad_interna.fecha_fin"});
    $array["EndDate"] = str_replace(" ", " ", $value->{"lealtad_interna.fecha_fin"});
    $array["Priority"] = $value->{"lealtad_interna.orden"};
    $array["CodeGlobal"] = $value->{"lealtad_interna.codigo"};

    /* inicializa un arreglo en PHP con varios valores predeterminados. */
    $array["WinLealtadId"] = 0;
    $array["TypeLealtadDeposit"] = 0;
    $array["TypeMaxRollover"] = 0;
    $array["TypeSaldo"] = 0;
    $array["UserRepeatLealtad"] = false;

    $array["TypeId"] = 2;

    /* define un arreglo con información sobre tipo y lealtad de un socio. */
    $array["Type"] = array(
        "Id" => 2,
        "Name" => "Primer Deposito",
        "TypeId" => 2
    );
    $array["PartnerLealtad"] = array(
        "StartDate" => "2018-02-09T00:00:00",
        "EndDate" => "2018-02-09T00:00:00",
        "ExpirationDays" => 6,
        "LealtadDetails" => array(
            array(
                "CurrencyId" => "USD"

            )
        )
    );


    /* Se define un array con diferentes categorías de depósitos y pagos. */
    $array["MaximumDeposit"] = array();
    $array["MinimumDeposit"] = array();
    $array["MaxPayout"] = array();
    $array["MoneyRequirement"] = array();
    $array["MaxPayout"] = array();
    $array["Points"] = array();


    /* Se estructura un arreglo con detalles de disparadores, depósitos y categorías de juegos. */
    $array["TriggerDetails"] = array();
    $array["TriggerDetails"]["PaymentSystemIds"] = array();
    $array["TriggerDetails"]["Regions"] = array();
    $array["TriggerDetails"]["ConditionProduct"] = $value->{"lealtad_interna.condicional"};
    $array["DepositDefinition"] = array();
    $array["GamesByCategories"] = array();


    /* Se estructura un arreglo para gestionar reglas de lealtad en deportes. */
    $array["ForeignRule"] = array();
    $array["ForeignRule"]["Info"] = array();
    $array["ForeignRule"]["Info"]["SportLealtadRules"] = array();
    $array["ForeignRule"]["Info"]["LiveOrPreMatch"] = 0;
    // $array["CurrentCost"] = $lealtadlog->data[0]->{".valor"};
    // $array["PlayersCount"] = $lealtadlog->data[0]->{".count"};


    $array["IsVisibleForAllplayers"] = true;

    /* Se asignan valores a un array y se declaran múltiples arrays vacíos. */
    $array["BonoId"] = $value->{"lealtad_interna.bono_id"};
    $sports = array();
    $matches = array();
    $competitions = array();
    $regions = array();
    $markets = array();

    foreach ($lealtaddetalles->data as $lealtaddetalle) {
        //Expiracion
        switch ($lealtaddetalle->{'lealtad_detalle.tipo'}) {
            case "EXPDIA":
                /* Asigna valor de expiración y tipo en un arreglo basado en "EXPDIA". */

                $array["ExpirationDays"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                $array["TypeDateExpiration"] = 1;

                break;

            case "TIPOPRODUCTO":
                /* Asigna el ID de tipo de producto a un arreglo desde un objeto JSON. */

                $array["ProductTypeId"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "CANTDEPOSITOS":
                /* asigna un valor entero a un contador en un arreglo. */

                $array["TriggerDetails"]["Count"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "PAISESPERMITIDOS":
                /* asigna un valor a una clave específica en un arreglo. */

                $array["TriggerDetails"]["AreAllowed"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "CONDEFECTIVO":
                /* asigna un valor booleano a un array basado en una condición específica. */

                $array["TriggerDetails"]["IsFromCashDesk"] = boolval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "EXPFECHA":
                /* Asigna valores de expiración en un array basado en un caso específico. */

                $array["ExpirationDate"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                $array["TypeDateExpiration"] = 0;
                break;

            case "PORCENTAJE":
                /* Asigna un porcentaje de lealtad a un arreglo basado en un valor específico. */

                $array["DepositDefinition"]["LealtadPercent"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                $array["TypeLealtadDeposit"] = 1;
                break;

            case "WFACTORlealtad":
                /* Asigna un valor entero a "LealtadWFactor" del array "DepositDefinition". */

                $array["DepositDefinition"]["LealtadWFactor"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "WFACTORDEPOSITO":
                /* Asigna un valor entero a DepositWFactor basado en lealtad_detalle.valor. */

                $array["DepositDefinition"]["DepositWFactor"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "NUMERODEPOSITO":
                /* Asigna un número de depósito convertido a entero en un array. */

                $array["DepositDefinition"]["DepositNumber"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "MAXJUGADORES":
                /* Asigna el límite de jugadores y visibilidad en un array según un valor específico. */

                $array["MaxplayersCount"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                $array["IsVisibleForAllplayers"] = false;
                break;


            case "MAXPAGO":
                /* Agrega un valor al array "MaxPayout" con moneda y monto especificados. */

                array_push($array["MaxPayout"],

                    array(
                        "CurrencyId" => $lealtaddetalle->{'lealtad_detalle.moneda'},
                        "Amount" => intval($lealtaddetalle->{'lealtad_detalle.valor'})
                    )

                );

                break;
            case "PUNTOS":
                /* Agrega un nuevo punto con moneda y valor a un arreglo específico. */

                array_push($array["Points"],

                    array(
                        "CurrencyId" => $lealtaddetalle->{'lealtad_detalle.moneda'},
                        "Amount" => intval($lealtaddetalle->{'lealtad_detalle.valor'})
                    )

                );

                break;

            case "MAXDEPOSITO":
                /* Se agrega un nuevo depósito máximo al array con moneda y cantidad. */


                array_push($array["MaximumDeposit"],

                    array(
                        "CurrencyId" => $lealtaddetalle->{'lealtad_detalle.moneda'},
                        "Amount" => intval($lealtaddetalle->{'lealtad_detalle.valor'})
                    )

                );
                break;

            case "MINDEPOSITO":
                /* Añade un depósito mínimo en un array, con moneda y cantidad. */


                array_push($array["MinimumDeposit"],

                    array(
                        "CurrencyId" => $lealtaddetalle->{'lealtad_detalle.moneda'},
                        "Amount" => intval($lealtaddetalle->{'lealtad_detalle.valor'})
                    )

                );
                break;

            case "VALORlealtad":
                /* Agrega requerimientos monetarios a un arreglo basado en detalles de lealtad. */

                array_push($array["MoneyRequirement"],

                    array(
                        "CurrencyId" => $lealtaddetalle->{'lealtad_detalle.moneda'},
                        "Amount" => intval($lealtaddetalle->{'lealtad_detalle.valor'})
                    )

                );
                break;

            case "CONDPAYMENT":
                /* Agrega un valor a un array basado en una condición específica de pago. */


                array_push($array["TriggerDetails"]["PaymentSystemIds"],
                    $lealtaddetalle->{'lealtad_detalle.valor'}

                );
                break;

            case "CONDPAISPV":
                /* Agrega un valor específico a un array dentro de "TriggerDetails" en un caso. */


                array_push($array["TriggerDetails"]["Regions"],
                    $lealtaddetalle->{'lealtad_detalle.valor'}

                );
                break;

            case "CONDDEPARTAMENTOPV":
                /* añade un valor a un array dentro de "TriggerDetails". */


                array_push($array["TriggerDetails"]["Departments"],
                    $lealtaddetalle->{'lealtad_detalle.valor'}

                );
                break;

            case "CONDCIUDADPV":
                /* Agrega un valor de lealtad a un array de detalles de ciudades. */


                array_push($array["TriggerDetails"]["Cities"],
                    $lealtaddetalle->{'lealtad_detalle.valor'}

                );
                break;

            case "CONDPAISUSER":
                /* Añade un valor a la lista de regiones del usuario en un array. */


                array_push($array["TriggerDetails"]["RegionsUser"],
                    $lealtaddetalle->{'lealtad_detalle.valor'}

                );
                break;

            case "CONDDEPARTAMENTOUSER":
                /* Agrega un valor al array "DepartmentsUser" en función de un case específico. */


                array_push($array["TriggerDetails"]["DepartmentsUser"],
                    $lealtaddetalle->{'lealtad_detalle.valor'}

                );
                break;

            case "CONDCIUDADUSER":
                /* Agrega un valor a la lista de ciudades de usuario en un array. */


                array_push($array["TriggerDetails"]["CitiesUser"],
                    $lealtaddetalle->{'lealtad_detalle.valor'}

                );
                break;

            case "CONDPUNTOVENTA":
                /* Añade un valor a la lista de detalles en el caso "CONDPUNTOVENTA". */


                array_push($array["TriggerDetails"]["CashDesk"],
                    $lealtaddetalle->{'lealtad_detalle.valor'}

                );
                break;


            case "LIVEORPREMATCH":
                /* Asigna el valor de "lealtad_detalle" a "LiveOrPreMatch" en un array. */

                $array["ForeignRule"]["Info"]["LiveOrPreMatch"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "MINSELCOUNT":
                /* Asigna un valor de lealtad a un arreglo en función de una condición. */

                $array["ForeignRule"]["Info"]["MinSelCount"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;


            case "MINSELPRICE":
                /* Asigna un valor mínimo de selección a un arreglo usando un caso específico. */

                $array["ForeignRule"]["Info"]["MinSelPrice"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "MINBETPRICE":
                /* Asigna el valor de "lealtad_detalle.valor" a "MinBetPrice" en un array. */

                $array["ForeignRule"]["Info"]["MinBetPrice"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;


            case "MINSELPRICETOTAL":
                /* Asigna el valor de 'lealtad_detalle.valor' a MinSelPriceTotal en un array. */

                $array["ForeignRule"]["Info"]["MinSelPriceTotal"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;


            case "REPITEMERCADOS":
                /* Asigna un valor a la clave "RepeatMercados" en el array según un detalle. */

                $array["ForeignRule"]["Info"]["RepeatMercados"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;


            case "REPITEPARTIDOS":
                /* Asignación de un valor a la regla "RepeatMatches" en un array basado en condiciones. */

                $array["ForeignRule"]["Info"]["RepeatMatches"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "WINlealtadID":
                /* asigna un valor de un objeto a un array en PHP. */

                $array["WinLealtadId"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "TIPOSALDO":
                /* Asigna el valor de "lealtad_detalle.valor" a "TypeSaldo" en el array. */

                $array["TypeSaldo"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "PUNTOS":
                /* Asigna el valor de "lealtad_detalle.valor" a "Points" como un entero. */

                $array["Points"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;
            case "IMGPPALURL":
                /* Asigna el valor de 'lealtad_detalle.valor' a 'MainImageURL' en un arreglo. */

                $array["MainImageURL"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;
            case "REPETIRlealtad":
                /* Verifica si el valor de lealtad es 1 o verdadero y lo almacena. */

                if (($lealtaddetalle->{'lealtad_detalle.valor'}) == 1 || ($lealtaddetalle->{'lealtad_detalle.valor'}) == true) {
                    $array["UserRepeatLealtad"] = true;

                }
                break;

            case "TIPOMAXAPUESTAROLLOVER":
                /* Asigna un valor a "TypeMaxRollover" desde el objeto "lealtaddetalle". */

                $array["TypeMaxRollover"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;


            case "FROZEWALLET":
                /* Asignación de valor booleano a "UseFrozeWallet" desde "lealtad_detalle.valor". */

                $array["DepositDefinition"]["UseFrozeWallet"] = boolval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "SUPPRESSWITHDRAWAL":
                /* Asignación de un valor booleano a la clave "SuppressWithdrawal" en el array. */

                $array["DepositDefinition"]["SuppressWithdrawal"] = boolval($lealtaddetalle->{'lealtad_detalle.valor'});

                break;

            case "SCHEDULECOUNT":
                /* Asigna un valor entero a "Count" en el array "Schedule". */

                $array["Schedule"]["Count"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "SCHEDULENAME":
                /* Asigna un valor al nombre del horario en un arreglo basado en una condición. */

                $array["Schedule"]["Name"] = ($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            case "SCHEDULEPERIOD":
                /* Asigna un valor entero a "Period" en el arreglo "Schedule" según input. */

                $array["Schedule"]["Period"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;


            case "SCHEDULEPERIODTYPE":
                /* Asigna un valor entero a PeriodType en el arreglo Schedule. */

                $array["Schedule"]["PeriodType"] = intval($lealtaddetalle->{'lealtad_detalle.valor'});
                break;

            default:

                if (stristr($lealtaddetalle->{'lealtad_detalle.tipo'}, 'CONDGAME')) {

                    /* procesa datos para obtener productos basados en un ID específico. */
                    $id = str_replace("CONDGAME", "", $lealtaddetalle->{'lealtad_detalle.tipo'});
                    $json = '{"rules" : [{"field" : "producto_mandante.prodmandante_id", "data": "' . $id . '","op":"eq"}] ,"groupOp" : "AND"}';
                    $ProductoMandante = new ProductoMandante();
                    $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", 0, 1, $json, true);

                    $productos = json_decode($productos);

                    /* crea una categoría de producto con información de juegos específicos. */
                    $producto = $productos->data[0];
                    $CategoriaProducto = new CategoriaProducto("", $producto->{'producto.producto_id'});
                    array_push($array["GamesByCategories"],
                        array(
                            "Id" => $CategoriaProducto->categoriaId,
                            "Name" => "",
                            "Games" =>
                                array(array(
                                    "Id" => $id,
                                    "WageringPercent" => intval($lealtaddetalle->{'lealtad_detalle.valor'}),
                                    "Name" => $producto->{'producto.descripcion'},
                                    "ProviderId" => $producto->{'producto.proveedor_id'},
                                    "selected" => true
                                ))
                        )

                    );
                }

                if (stristr($lealtaddetalle->{'lealtad_detalle.tipo'}, 'ITAINMENT') && false) {

                    /* Carga deportes y sus datos asociados si no hay conteo previo. */
                    if (oldCount($sports) == 0) {
                        $sportstmp = getSports();

                        foreach ($sportstmp as $sport) {
                            $sport["Markets"] = getMarketTypes($sport["Id"]);
                            $sport["Regions"] = getRegions($sport["Id"]);
                            foreach ($sport["Regions"] as $region) {
                                $sport["Regions"]["Competitions"] = getCompetitions($sport["Id"], $region["Id"]);
                                foreach ($sport["Regions"]["Competitions"] as $competition) {
                                    $sport["Regions"]["Competitions"]["Matches"] = getMatches($sport["Id"], $region["Id"], $competition["Id"]);
                                }
                            }
                            array_push($sports, $sport);

                        }

                    }


                    /* Convierte y organiza datos de 'lealtaddetalle' en un array. */
                    $tipo = intval(str_replace("ITAINMENT", "", $lealtaddetalle->{'lealtad_detalle.tipo'}));
                    $id = intval($lealtaddetalle->{'lealtad_detalle.valor'});

                    $data = array(
                        "Id" => $id,

                        "ObjectTypeId" => $tipo,
                        "ObjectId" => $id
                    );

                    switch ($tipo) {
                        case 1:
                            /* Busca un deporte por ID y almacena su nombre en un array de datos. */


                            foreach ($sports as $sport) {

                                if ($id == $sport["Id"]) {
                                    $data["Name"] = $sport["Name"];
                                }
                            }

                            break;

                        case 3:
                            /* busca competiciones por ID en deportes y regiones, almacenando datos relevantes. */


                            foreach ($sports as $sport) {

                                foreach ($sport["Regions"] as $region) {
                                    foreach ($sport["Regions"]["Competitions"] as $competition) {
                                        if ($id == $competition["Id"]) {
                                            $data["Name"] = $competition["Name"];
                                            $data["SportName"] = $sport["Name"];
                                        }
                                    }
                                }
                            }

                            break;


                        case 4:
                            /* Recorre deportes, regiones, competiciones y partidos para buscar un ID específico. */


                            foreach ($sports as $sport) {

                                foreach ($sport["Regions"] as $region) {
                                    foreach ($sport["Regions"]["Competitions"] as $competition) {
                                        foreach ($sport["Regions"]["Competitions"]["Matches"] as $match) {
                                            if ($id == $match["Id"]) {
                                                $data["Name"] = $match["Name"];
                                                $data["SportName"] = $sport["Name"];
                                            }
                                        }
                                    }
                                }
                            }

                            break;


                        case 5:
                            /* busca un mercado específico basado en un ID y extrae datos. */

                            $id = ($lealtaddetalle->{'lealtad_detalle.valor'});

                            foreach ($sports as $sport) {

                                foreach ($sport["Markets"] as $market) {
                                    if ($id == $market["Id"]) {
                                        $data["Name"] = $market["Name"];
                                        $data["SportName"] = $sport["Name"];
                                        $data["Id"] = $market["Id"];
                                        $data["ObjectId"] = $market["Id"];
                                    }
                                }
                            }

                            break;
                    }

                    /* Agrega $data al array "SportLealtadRules" dentro de "ForeignRule". */
                    array_push($array["ForeignRule"]["Info"]["SportLealtadRules"], $data


                    );
                }
                break;


        }


    }


    /* Agrega el contenido de $array al final del array $final en PHP. */
    array_push($final, $array);
}


/* asigna valores a un arreglo de respuesta para manejo de errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $array;