<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\CategoriaProducto;
use Backend\dto\ProductoMandante;
use Backend\dto\PromocionalLog;

/**
 * GetBonusDetails
 * 
 * Obtiene los detalles de un bono específico incluyendo su configuración y reglas
 *
 * @param object $params {
 *   "StartTimeLocal": string,   // Fecha inicial en formato "YYYY-MM-DD HH:mm:ss"
 *   "EndTimeLocal": string,     // Fecha final en formato "YYYY-MM-DD HH:mm:ss"
 *   "MaxRows": int,            // Número máximo de registros
 *   "OrderedItem": string,     // Campo de ordenamiento
 *   "SkeepRows": int          // Número de registros a omitir
 * }
 * @param int $_REQUEST['Id']   // ID del bono a consultar
 * 
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success/danger)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Errores del modelo
 *   "Count": int,             // Total de registros
 *   "Data": array {           // Detalles del bono
 *     "Id": int,             // ID del bono
 *     "Name": string,        // Nombre del bono
 *     "Description": string, // Descripción del bono
 *     "BeginDate": string,   // Fecha de inicio
 *     "EndDate": string,     // Fecha de fin
 *     "Priority": int,       // Prioridad del bono
 *     "CodeGlobal": string,  // Código global del bono
 *     "Type": string,        // Tipo de bono (0: privado, 1: público)
 *     "TypeId": int,         // ID del tipo de bono
 *     "PartnerBonus": object, // Configuración para socios
 *     "TriggerDetails": object, // Detalles del disparador
 *     "DepositDefinition": array, // Definición de depósitos
 *     "GamesByCategories": array, // Categorías de juegos permitidas
 *     "ForeignRule": object    // Reglas específicas
 *   }
 * }
 *
 * @throws Exception           // Errores de procesamiento
 */

// Obtiene y decodifica los parámetros de entrada desde el request
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$BonusId = $_REQUEST["Id"];

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

// Configura los parámetros de paginación
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

// Inicializa el array de reglas para el filtro
$rules = [];

if ($BonusId != "") {
    array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$BonusId", "op" => "eq"));
}

// Construye el objeto de filtro y establece valores por defecto para la paginación
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

// Prepara la consulta y obtiene los bonos internos
$json = json_encode($filtro);

$BonoInterno = new BonoInterno();

$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, true);

$bonos = json_decode($bonos);

$final = [];

// Itera sobre cada bono para obtener sus detalles
foreach ($bonos->data as $key => $value) {

    $rules = [];

    // Agrega filtros de fecha si están especificados
    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "promocional_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    }
    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "promocional_log.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
    }

    // Agrega filtro por ID de bono
    if ($BonusId != "") {
        array_push($rules, array("field" => "promocional_log.promocional_id", "data" => $BonusId, "op" => "eq"));
    }

    // Configura el filtro y la paginación para los logs promocionales
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 30;
    }

    // Obtiene los logs promocionales
    $json = json_encode($filtro);

    $PromocionalLog = new PromocionalLog();

    $bonolog = $PromocionalLog->getPromocionalLogsCustom(" count(promocional_log.promolog_id) count, sum(promocional_log.valor) valor ", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $bonolog = json_decode($bonolog);

    // Prepara la consulta para obtener los detalles del bono
    $rules = [];

    if ($BonusId != "") {
        array_push($rules, array("field" => "bono_detalle.bono_id", "data" => "$BonusId", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);

    $BonoDetalle = new BonoDetalle();

    $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.* ", "bono_detalle.bonodetalle_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $bonodetalles = json_decode($bonodetalles);

    // Construye el array con la información básica del bono
    $array = [];

    $array["Id"] = $value->{"bono_interno.bono_id"};
    $array["Name"] = $value->{"bono_interno.nombre"};
    $array["Description"] = $value->{"bono_interno.descripcion"};
    $array["BeginDate"] = str_replace(" ", "T", $value->{"bono_interno.fecha_inicio"});

    // Configura los detalles del tipo de bono y configuración para socios
    $array["EndDate"] = str_replace(" ", "T", $value->{"bono_interno.fecha_fin"});
    $array["TypeId"] = 2;
    $array["Type"] = array(
        "Id" => 2,
        "Name" => "Primer Deposito",
        "TypeId" => 2
    );
    $array["PartnerBonus"] = array(
        "StartDate" => "2018-02-09T00:00:00",
        "EndDate" => "2018-02-09T00:00:00",
        "ExpirationDays" => 6,
        "BonusDetails" => array(
            array(
                "CurrencyId" => "USD"
            )
        )
    );

    // Inicializa los arrays de configuración del bono
    $array["MaximumDeposit"] = array();
    $array["MinimumDeposit"] = array();
    $array["MaxPayout"] = array();
    $array["MoneyRequirement"] = array();
    $array["MaxPayout"] = array();

    $array["TriggerDetails"] = array();
    $array["TriggerDetails"]["PaymentSystemIds"] = array();
    $array["TriggerDetails"]["Regions"] = array();
    $array["DepositDefinition"] = array();
    $array["GamesByCategories"] = array();

    // Configura los detalles de costos y contadores
    $array["ForeignRule"] = array();
    $array["ForeignRule"]["Info"] = array();
    $array["ForeignRule"]["Info"]["SportBonusRules"] = array();
    $array["ForeignRule"] = array();
    $array["CurrentCost"] = $bonolog->data[0]->{".valor"};
    $array["PlayersCount"] = $bonolog->data[0]->{".count"};

    $array["IsVisibleForAllplayers"] = true;

    // Inicializa arrays para reglas deportivas
    $sports = array();
    $matches = array();
    $competitions = array();
    $regions = array();
    $markets = array();

    // Procesa cada detalle del bono según su tipo
    foreach ($bonodetalles->data as $bonodetalle) {
        //Expiracion
        switch ($bonodetalle->{'bono_detalle.tipo'}) {
            case "EXPDIA":
                $array["ExpirationDays"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "TIPOPRODUCTO":
                $array["ProductTypeId"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "CANTDEPOSITOS":
                $array["TriggerDetails"]["Count"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "PAISESPERMITIDOS":
                $array["TriggerDetails"]["AreAllowed"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "CONDEFECTIVO":
                $array["TriggerDetails"]["IsFromCashDesk"] = boolval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "EXPFECHA":
                $array["ExpirationDate"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "PORCENTAJE":
                $array["DepositDefinition"]["BonusPercent"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "WFACTORBONO":
                $array["DepositDefinition"]["BonusWFactor"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "WFACTORDEPOSITO":
                $array["DepositDefinition"]["DepositWFactor"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "NUMERODEPOSITO":
                $array["DepositDefinition"]["DepositNumber"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "MAXJUGADORES":
                $array["MaxplayersCount"] = intval($bonodetalle->{'bono_detalle.valor'});
                $array["IsVisibleForAllplayers"] = false;
                break;

            case "MAXPAGO":
                array_push($array["MaxPayout"],
                    array(
                        "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                        "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                    )
                );
                break;

            case "MAXDEPOSITO":
                array_push($array["MaximumDeposit"],
                    array(
                        "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                        "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                    )
                );
                break;

            case "MINDEPOSITO":
                array_push($array["MinimumDeposit"],
                    array(
                        "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                        "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                    )
                );
                break;

            case "VALORBONO":
                array_push($array["MoneyRequirement"],
                    array(
                        "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                        "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                    )
                );
                break;

            case "CONDPAYMENT":
                array_push($array["TriggerDetails"]["PaymentSystemIds"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            case "CONDPAIS":
                array_push($array["TriggerDetails"]["Regions"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            case "LIVEORPREMATCH":
                $array["ForeignRule"]["Info"]["LiveOrPreMatch"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "MINSELCOUNT":
                $array["ForeignRule"]["Info"]["MinSelCount"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "LIVEORPREMATCH":
                $array["MinSelCount"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "MINSELPRICE":
                $array["MinSelPrice"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "MINBETPRICE":
                $array["MinBetPrice"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "FROZEWALLET":
                $array["DepositDefinition"]["UseFrozeWallet"] = boolval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "SUPPRESSWITHDRAWAL":
                $array["DepositDefinition"]["SuppressWithdrawal"] = boolval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "SCHEDULECOUNT":
                $array["Schedule"]["Count"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "SCHEDULENAME":
                $array["Schedule"]["Name"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "SCHEDULEPERIOD":
                $array["Schedule"]["Period"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "SCHEDULEPERIODTYPE":
                $array["Schedule"]["PeriodType"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            default:
                // Procesa reglas especiales para juegos y deportes
                if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'CONDGAME')) {
                    $id = str_replace("CONDGAME", "", $bonodetalle->{'bono_detalle.tipo'});
                    $json = '{"rules" : [{"field" : "producto_mandante.prodmandante_id", "data": "' . $id . '","op":"eq"}] ,"groupOp" : "AND"}';
                    $ProductoMandante = new ProductoMandante();
                    $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", 0, 1, $json, true);

                    $productos = json_decode($productos);
                    $producto = $productos->data[0];
                    $CategoriaProducto = new CategoriaProducto("", $producto->{'producto.producto_id'});
                    array_push($array["GamesByCategories"],
                        array(
                            "Id" => $CategoriaProducto->categoriaId,
                            "Name" => "",
                            "Games" =>
                                array(array(
                                    "Id" => $id,
                                    "WageringPercent" => intval($bonodetalle->{'bono_detalle.valor'}),
                                    "Name" => $producto->{'producto.descripcion'},
                                    "ProviderId" => $producto->{'producto.proveedor_id'},
                                    "selected" => true
                                ))
                        )
                    );
                }

                if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'ITAINMENT')) {
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

                    $tipo = intval(str_replace("ITAINMENT", "", $bonodetalle->{'bono_detalle.tipo'}));
                    $id = intval($bonodetalle->{'bono_detalle.valor'});

                    $data = array(
                        "Id" => $id,
                        "ObjectTypeId" => $tipo,
                        "ObjectId" => $id
                    );

                    switch ($tipo) {
                        case 1:
                            foreach ($sports as $sport) {
                                if ($id == $sport["Id"]) {
                                    $data["Name"] = $sport["Name"];
                                }
                            }
                            break;

                        case 3:
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
                            $id = ($bonodetalle->{'bono_detalle.valor'});
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
                    array_push($array["ForeignRule"]["Info"]["SportBonusRules"], $data);
                }
                break;
        }
    }

    array_push($final, $array);
}

// Prepara la respuesta final
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $array;
