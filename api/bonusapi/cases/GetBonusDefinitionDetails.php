<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\CategoriaProducto;
use Backend\dto\ProductoMandante;

/**
 * GetBonusDefinitionDetails
 * 
 * Obtiene los detalles y definiciones de un bono específico
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
 *     "Rules": string,      // Reglas del bono
 *     "Status": string,     // Estado del bono
 *     "Type": object {      // Tipo de bono
 *       "Id": int,         // ID del tipo
 *       "Name": string,    // Nombre del tipo
 *       "TypeId": int     // ID de clasificación
 *     }
 *   }
 * }
 *
 * @throws Exception         // Errores de procesamiento
 */


// Obtiene y decodifica los parámetros de entrada desde el request
$params = file_get_contents('php://input');
$params = json_decode($params);

// Extrae los parámetros de fechas y el ID del bono
$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$BonusId = $_REQUEST["Id"];

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

// Configura los parámetros de paginación
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

// Inicializa el array de reglas y agrega el filtro por ID de bono
$rules = [];

if ($BonusId != "") {
    array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$BonusId", "op" => "eq"));
}

// Aplica filtros según las condiciones del usuario
// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
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

// Codifica el filtro a JSON y obtiene los bonos
$json = json_encode($filtro);

$BonoInterno = new BonoInterno();

$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, true);

$bonos = json_decode($bonos);

// Inicializa el array final y procesa cada bono
$final = [];

foreach ($bonos->data as $key => $value) {

    // Reinicia las reglas y agrega filtro por ID de bono
    $rules = [];

    if ($BonusId != "") {
        array_push($rules, array("field" => "bono_interno.bono_id", "data" => $BonusId, "op" => "eq"));
    }

    /* if($PlayerExternalId != ""){
         array_push($rules, array("field" => "promocional_log.usuario_id", "data" => $PlayerExternalId , "op" => "eq"));
     }*/

    // Configura el filtro y la paginación para los detalles
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

    $json = json_encode($filtro);

    // Código comentado para logs promocionales
    // $PromocionalLog = new PromocionalLog();
    // $bonolog = $PromocionalLog->getPromocionalLogsCustom(" count(promocional_log.promolog_id) count, sum(promocional_log.valor) valor ", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json, true);
    // $bonolog = json_decode($bonolog);

    // Obtiene los detalles del bono
    $rules = [];

    if ($BonusId != "") {
        array_push($rules, array("field" => "bono_detalle.bono_id", "data" => "$BonusId", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);

    $BonoDetalle = new BonoDetalle();

    $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.* ", "bono_detalle.bonodetalle_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $bonodetalles = json_decode($bonodetalles);

    // Construye el array con la información del bono
    $array = [];

    $array["Id"] = $value->{"bono_interno.bono_id"};
    $array["Name"] = $value->{"bono_interno.nombre"};
    $array["Description"] = $value->{"bono_interno.descripcion"};
    $array["BeginDate"] = str_replace(" ", "T", $value->{"bono_interno.fecha_inicio"});
    $array["BeginDate"] = str_replace(" ", " ", $value->{"bono_interno.fecha_inicio"});

    // Configura fechas, prioridad y códigos
    $array["EndDate"] = str_replace(" ", "T", $value->{"bono_interno.fecha_fin"});
    $array["EndDate"] = str_replace(" ", " ", $value->{"bono_interno.fecha_fin"});
    $array["Priority"] = $value->{"bono_interno.orden"};
    $array["CodeGlobal"] = $value->{"bono_interno.codigo"};
    $array["WinBonusId"] = 0;
    $array["TypeBonusDeposit"] = 0;
    $array["TypeMaxRollover"] = 0;
    $array["TypeSaldo"] = 0;
    $array["UserRepeatBonus"] = false;

    // Configura el tipo de bono y su visibilidad
    $array["TypeId"] = 2;
    if($value->{"bono_interno.publico"} == 'A'){
        $array["Type"] = "1";
    }else{
        $array["Type"] = "0";
    }

    // Define la estructura del bono para socios
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

    // Inicializa arrays para diferentes tipos de depósitos y límites
    $array["MaximumDeposit"] = array();
    $array["MinimumDeposit"] = array();
    $array["MaxPayout"] = array();
    $array["MoneyRequirement"] = array();
    $array["MaxPayout"] = array();

    // Configura los detalles del disparador y condiciones
    $array["TriggerDetails"] = array();
    $array["TriggerDetails"]["PaymentSystemIds"] = array();
    $array["TriggerDetails"]["Regions"] = array();
    $array["TriggerDetails"]["Departments"] = array();
    $array["TriggerDetails"]["Cities"] = array();
    $array["TriggerDetails"]["CashDesk"] = array();
    $array["TriggerDetails"]["CashDesksNot"] = array();
    $array["TriggerDetails"]["PaymentSystemIds"] = array();
    $array["TriggerDetails"]["RegionsUser"] = array();
    $array["TriggerDetails"]["DepartmentsUser"] = array();
    $array["TriggerDetails"]["CitiesUser"] = array();
    $array["TriggerDetails"]["ConditionProduct"] = $value->{"bono_interno.condicional"};
    $array["DepositDefinition"] = array();
    $array["GamesByCategories"] = array();

    // Configura reglas específicas y permisos
    $array["ForeignRule"] = array();
    $array["ForeignRule"]["Info"] = array();
    $array["ForeignRule"]["Info"]["SportBonusRules"] = array();
    $array["ForeignRule"]["Info"]["LiveOrPreMatch"] = 0;
    // $array["CurrentCost"] = $bonolog->data[0]->{".valor"};
    // $array["PlayersCount"] = $bonolog->data[0]->{".count"};

    $array["PermCodeGlobal"] = 0;
    $array["AgenteSelect"] = 0;
    $array["LinkSelect"] = 0;
    $array["IsVisibleForAllplayers"] = true;

    // Inicializa arrays para categorías de juegos
    $sports = array();
    $matches = array();
    $competitions = array();
    $regions = array();
    $markets = array();

    // Itera sobre los detalles del bono para procesar cada configuración
    foreach ($bonodetalles->data as $bonodetalle) {
        //Expiracion
        switch ($bonodetalle->{'bono_detalle.tipo'}) {
            case "EXPDIA":
                $array["ExpirationDays"] = intval($bonodetalle->{'bono_detalle.valor'});
                $array["TypeDateExpiration"] = 1;
                break;

            // Configura el tipo de producto y código promocional
            case "TIPOPRODUCTO":
                $array["ProductTypeId"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;
            case "CODEPROMO":
                $array["CodePromo"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;
            case "CANTDEPOSITOS":
                $array["TriggerDetails"]["Count"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            // Establece configuraciones de países y efectivo permitidos
            case "PAISESPERMITIDOS":
                $array["TriggerDetails"]["AreAllowed"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "CONDEFECTIVO":
                $array["TriggerDetails"]["IsFromCashDesk"] = boolval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "EXPFECHA":
                $array["ExpirationDate"] = ($bonodetalle->{'bono_detalle.valor'});
                $array["TypeDateExpiration"] = 0;
                break;

            // Configura los porcentajes y factores del bono
            case "PORCENTAJE":
                $array["DepositDefinition"]["BonusPercent"] = intval($bonodetalle->{'bono_detalle.valor'});
                $array["TypeBonusDeposit"] = 1;
                break;

            case "WFACTORBONO":
                $array["DepositDefinition"]["BonusWFactor"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "WFACTORDEPOSITO":
                $array["DepositDefinition"]["DepositWFactor"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            // Establece límites de depósitos y jugadores
            case "NUMERODEPOSITO":
                $array["DepositDefinition"]["DepositNumber"] = intval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "MAXJUGADORES":
                $array["MaxplayersCount"] = intval($bonodetalle->{'bono_detalle.valor'});
                $array["IsVisibleForAllplayers"] = false;
                break;

            // Configura los límites de pago y depósitos por moneda
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

            // Define montos mínimos y requisitos monetarios
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

            // Configura sistemas de pago y restricciones geográficas
            case "CONDPAYMENT":
                array_push($array["TriggerDetails"]["PaymentSystemIds"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            case "CONDPAISPV":
                array_push($array["TriggerDetails"]["Regions"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            // Establece restricciones por departamento y ciudad
            case "CONDDEPARTAMENTOPV":
                array_push($array["TriggerDetails"]["Departments"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            case "CONDCIUDADPV":
                array_push($array["TriggerDetails"]["Cities"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            // Configura restricciones geográficas para usuarios
            case "CONDPAISUSER":
                array_push($array["TriggerDetails"]["RegionsUser"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            case "CONDDEPARTAMENTOUSER":
                array_push($array["TriggerDetails"]["DepartmentsUser"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            case "CONDCIUDADUSER":
                array_push($array["TriggerDetails"]["CitiesUser"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            // Define restricciones de puntos de venta
            case "CONDPUNTOVENTA":
                array_push($array["TriggerDetails"]["CashDesk"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            case "CONDNOPUNTOVENTA":
                array_push($array["TriggerDetails"]["CashDesksNot"],
                    $bonodetalle->{'bono_detalle.valor'}
                );
                break;

            // Configura reglas para apuestas en vivo y pre-partido
            case "LIVEORPREMATCH":
                $array["ForeignRule"]["Info"]["LiveOrPreMatch"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "MINSELCOUNT":
                $array["ForeignRule"]["Info"]["MinSelCount"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "MINSELPRICE":
                $array["ForeignRule"]["Info"]["MinSelPrice"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            // Establece precios mínimos para apuestas
            case "MINBETPRICE":
                $array["ForeignRule"]["Info"]["MinBetPrice"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "MINSELPRICETOTAL":
                $array["ForeignRule"]["Info"]["MinSelPriceTotal"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            // Configura reglas de repetición para mercados y partidos
            case "REPITEMERCADOS":
                $array["ForeignRule"]["Info"]["RepeatMercados"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "REPITEPARTIDOS":
                $array["ForeignRule"]["Info"]["RepeatMatches"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            // Define IDs y tipos de bonos
            case "WINBONOID":
                $array["WinBonusId"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            case "TIPOSALDO":
                $array["TypeSaldo"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            // Configura opciones de repetición y rollover
            case "REPETIRBONO":
                if (($bonodetalle->{'bono_detalle.valor'}) == 1 || ($bonodetalle->{'bono_detalle.valor'}) == true) {
                    $array["UserRepeatBonus"] = true;
                }
                break;

            case "TIPOMAXAPUESTAROLLOVER":
                $array["TypeMaxRollover"] = ($bonodetalle->{'bono_detalle.valor'});
                break;

            // Establece configuraciones de billetera y retiros
            case "FROZEWALLET":
                $array["DepositDefinition"]["UseFrozeWallet"] = boolval($bonodetalle->{'bono_detalle.valor'});
                break;

            case "SUPPRESSWITHDRAWAL":
                $array["DepositDefinition"]["SuppressWithdrawal"] = boolval($bonodetalle->{'bono_detalle.valor'});
                break;

            // Configura programación del bono
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
                // Procesa condiciones especiales para juegos y deportes
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

                // Procesa reglas especiales para deportes y apuestas
                if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'ITAINMENT') && false) {
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

// Prepara la respuesta final con los datos procesados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $array;
