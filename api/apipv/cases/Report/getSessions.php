<?php



use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;

/**
 * Report/getSessions
 * 
 * Obtiene el historial de sesiones de un usuario
 *
 * @param object $params {
 *   "PlayerId": int,               // ID del jugador
 *   "dateTo": string,              // Fecha final (Y-m-d)
 *   "dateFrom": string,            // Fecha inicial (Y-m-d)
 *   "MaxRows": int,                // Cantidad máxima de registros
 *   "SkeepRows": int               // Registros a omitir (paginación)
 * }
 *
 * @return array {
 *   "HasError": boolean,           // Indica si hubo error
 *   "AlertType": string,           // Tipo de alerta (success, error)
 *   "AlertMessage": string,        // Mensaje descriptivo
 *   "ModelErrors": array,          // Errores del modelo
 *   "Data": array {
 *     "Objects": array[],          // Lista de sesiones
 *     "Count": int                 // Total de registros
 *   }
 * }
 */


// Inicializa el objeto Usuario y obtiene los parámetros de entrada
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);

$PlayerId = $_GET['PlayerId'];
$MaxRows = $_GET['count'];
$SkeepRows = $_GET['start'];
$ToDateLocal =$_GET['dateTo'];
$FromDateLocal =$_GET['dateFrom'] ;

// Crea objeto UsuarioMandante con el ID del jugador
$UsuarioMandante = new UsuarioMandante($PlayerId);

// Establece valores por defecto para paginación
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}

// Construye las reglas de filtrado según los parámetros recibidos
$rules = [];

if ($FromDateLocal != "") {
    array_push($rules, array("field" => "usuario_token.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
}

if ($ToDateLocal != "") {
    array_push($rules, array("field" => "usuario_token.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
}

if ($PlayerId != "") {
    array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
}

array_push($rules, array("field" => "usuario_token.proveedor_id", "data" => 0, "op" => "eq"));

// Prepara y ejecuta la consulta para obtener el historial de sesiones
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "usuario_historial.*,usuario_token.*";
$grouping = "usuario_historial.usuario_id";
$UsuarioHistorial= new UsuarioHistorial();
$data = $UsuarioHistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "desc", $SkeepRows, $MaxRows, $json,true,$grouping);
$data = json_decode($data);

// Procesa los resultados y construye el array de sesiones
$arrayfinal = array();

//ID,TOKEN,IDJUGADOR, HORA INICIO,HORA FIN, MOTIVO CIERRE, SALDO FINAL
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["SessionId"] = $value->{"usuario_token.usutoken_id"};
        $array["Token"] = $value->{"usuario_token.token"};
        $array["PlayerId"] = $value->{"usuario_token.usuario_id"};
        $array["StartTime"] = $value->{"usuario_token.fecha_crea"};
        $array["EndTime"] = $value->{"usuario_token.fecha_modif"};
        $array["PromCreditsReceived"] = 0;
        $array["PromCreditsWagered"] = 0;
        $array["RemainingFunds"] = 0;
        $array["TypeClosed"] = "A";
        $array["SaldoEnd"] = intval(round($data->data[1]->{"usuario_historial.creditos"}, 2) * 100);

        array_push($arrayfinal, $array);
    }
}else{
    $array["SessionId"] = 0;
    $array["Token"] = 0;
    $array["PlayerId"] = 0;
    $array["StartTime"] = 0;
    $array["EndTime"] = 0;
    $array["PromCreditsReceived"] = 0;
    $array["PromCreditsWagered"] = 0;
    $array["RemainingFunds"] = 0;
    $array["TypeClosed"] = "A";
    $array["SaldoEnd"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma los depósitos del usuario en el período especificado
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 10, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as Deposit";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de depósitos
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
foreach ($data->data as $key => $value) {
    $array["Deposit"] = floatval($data->data[0]->{".Deposit"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["Deposit"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma las apuestas deportivas
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 20, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as SportsSum";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de apuestas deportivas
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["SportsSum"] = floatval($data->data[0]->{".SportsSum"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["SportsSum"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma los premios deportivos
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 20, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as SportsAwards";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de premios deportivos
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["SportsAwards"] = floatval($data->data[0]->{".SportsAwards"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["SportsAwards"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma las cancelaciones deportivas
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 20, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "C", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as SportsCancellation";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de cancelaciones deportivas
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["SportsCancellation"] = floatval($data->data[0]->{".SportsCancellation"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["SportsCancellation"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma las apuestas de casino
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 30, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as BetCasino";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de apuestas de casino
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["BetCasino"] = floatval($data->data[0]->{".BetCasino"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["BetCasino"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma los premios de casino
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 30, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as CasinoAward";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de premios de casino
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["CasinoAward"] = floatval($data->data[0]->{".CasinoAward"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["CasinoAward"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma las cancelaciones de casino
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 30, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "C", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as CasinoCancellation";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de cancelaciones de casino
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["CasinoCancellation"] = floatval($data->data[0]->{".CasinoCancellation"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["CasinoCancellation"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma los retiros realizados
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 40, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "S", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as WithdrawalsMade";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de retiros realizados
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["WithdrawalsMade"] = floatval($data->data[0]->{".WithdrawalsMade"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["WithdrawalsMade"]= 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma los retiros cancelados
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 40, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "C", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as CanceledWithdrawals";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de retiros cancelados
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["CanceledWithdrawals"] = floatval($data->data[0]->{".CanceledWithdrawals"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["CanceledWithdrawals"] = 0;
    array_push($arrayfinal, $array);
}

// Consulta y suma los bonos redimidos
$rules = [];
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
array_push($rules, array("field" => "usuario_historial.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
array_push($rules, array("field" => "usuario_historial.tipo", "data" => 50, "op" => "eq"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => "E", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$select = "SUM(usuario_historial.valor) as BonusRedeemed";
$grouping = "usuario_historial.usuario_id";

$Usuariohistorial = new UsuarioHistorial();
$data = $Usuariohistorial->getUsuarioHistorialCustom2($select, "usuario_historial.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);

// Procesa los resultados de bonos redimidos
$arrayfinal =array();
if($data->data != "" && $data->data != 0 && $data->data != null){
    foreach ($data->data as $key => $value) {
        $array["BonusRedeemed"] = floatval($data->data[0]->{".BonusRedeemed"});
    }
    array_push($arrayfinal, $array);
}else{
    $array["BonusRedeemed"] = 0;
    array_push($arrayfinal, $array);
}

// Prepara la respuesta final
$response["AlertMessage"] = "";
$response["AlertType"] = "success";
$response["HasError"] = false;
$response["ModelErrors"] = [];
$response["data"] = $arrayfinal[0];
