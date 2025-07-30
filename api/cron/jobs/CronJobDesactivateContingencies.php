<?php

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Clasificador;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\websocket\WebsocketUsuario;


require(__DIR__ . '/../vendor/autoload.php');
///home/devadmin/api/api/

ini_set('memory_limit', '-1');

date_default_timezone_set('America/Bogota');


$message = "*CRON: (cronDesactivateContingencies) * " . " - Fecha: " . date("Y-m-d H:i:s");
$ConfigurationEnvironment = new ConfigurationEnvironment();

if (!$ConfigurationEnvironment->isDevelopment()) {
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
}


$UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
$Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();

$fechaActual = date("Y-m-d H:i:s");

$Clasificador = new Clasificador();

$rules = [];

array_push($rules,array("field"=>"clasificador.tipo","data"=>"AB","op"=>"eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);


$data = $Clasificador->getClasificadoresCustom("clasificador.clasificador_id,clasificador.tipo","clasificador.clasificador_id","asc",0,100,$json,true);

$data = json_decode($data);

$final = [];

foreach ($data->data as $key => $value) {
    $array = [];

    $array["ClasificadorId"] = $value->{"clasificador.clasificador_id"};

    array_push($final,$array);
}


$firstType = $final[0]["ClasificadorId"];


$sql = "SELECT * FROM usuario_configuracion uc 
        INNER JOIN clasificador ON (uc.tipo = clasificador.clasificador_id) 
        WHERE uc.tipo >= $firstType
        AND fecha_fin<= NOW() and fecha_fin is not null and fecha_fin != ''
        AND uc.estado = 'A' 
        AND clasificador.tipo = 'AB'";

$BonoInterno = new BonoInterno();
$Contingencies = $BonoInterno->execQuery($Transaction,$sql);

$usuconfigIds = [];

foreach ($Contingencies as $obj) {
    if (isset($obj->{'uc.usuconfig_id'})) {
        $usuconfigIds[] = $obj->{'uc.usuconfig_id'};
    }
}





foreach ($usuconfigIds as $id){

    $UsuarioConfiguracion = new UsuarioConfiguracion("","","","",$id);
    $UsuarioConfiguracion->setEstado("I");
    $UsuarioConfiguracion->setValor("I");
    $UsuarioConfiguracion->setNota("Liberado");
    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
}


$sql3 = "SELECT
	uc.usuconfig_id,
	u.usuario_id,
	uc.tipo,
	uc.estado,
	uc.fecha_crea,
	uc.valor,
	uc.fecha_inicio,
	uc.fecha_fin,
	uc.nota
FROM
	usuario_configuracion uc
INNER JOIN usuario u ON
	(uc.usuario_id = u.usuario_id)
INNER JOIN clasificador c ON
	(uc.tipo = c.clasificador_id)
where
	c.tipo = 'RESTRIC'
	and uc.fecha_fin < NOW() and uc.fecha_fin is not null and uc.fecha_fin != '' and uc.estado = 'A' and uc.valor = 'A'";




$BonoInterno = new BonoInterno();
$ContingenciasActivas = $BonoInterno->execQuery($Transaction,$sql3);

$usuconfigIds2 = [];

foreach ($ContingenciasActivas as $obj) {
    if (isset($obj->{'uc.usuconfig_id'})) {
        $array = [];
        $array["usuconfigId"] = $obj->{'uc.usuconfig_id'};
        $array["id"] = $obj->{"u.usuario_id"};
        $array["tipo"] = $obj->{"uc.tipo"};

        array_push($usuconfigIds2, $array);
    }
}


foreach ($usuconfigIds2 as $item) {
    if (isset($item['usuconfigId'])) {
        $usuconfigId = $item['usuconfigId'];
        $sql4 = "UPDATE usuario_configuracion set valor = 'I',estado = 'I' where usuconfig_id = $usuconfigId";

        $BonoInterno = new BonoInterno();
        $ActualizacionUsuarioConfiguracion = $BonoInterno->execQuery($Transaction,$sql4);
    }


    if (isset($item['tipo']) && !empty($item['id'])) {

        $id = $item['id'];
        $tipo = $item['tipo'];

        $Clasificador = new Clasificador($tipo);
        $Abreviado = $Clasificador->abreviado;

        switch ($Abreviado){
            case "CONTINGENCIADEPORTIVAS":
                $sqlUpdate = "UPDATE usuario set contingencia_deportes = 'I' where usuario_id = $id";
                break;
            case "CONTINGENCIAVIRTUALES":
                $sqlUpdate = "UPDATE usuario set contingencia_virtuales = 'I' where usuario_id = $id";
                break;
            case "CONTINGENCIAPOKER":
                $sqlUpdate = "UPDATE usuario set contingencia_poker = 'I' where usuario_id = $id";
                break;
            case "CONTINGENCIACASENVIVO":
                $sqlUpdate = "UPDATE usuario set contingencia_casvivo = 'I' where usuario_id = $id";
                break;
            case "CONTINGENCIACASINO":
                $sqlUpdate = "UPDATE usuario set contingencia_casino = 'I' where usuario_id = $id";
                break;
            case "CONTINGENCIARETIROS":
                $sqlUpdate = "UPDATE usuario set contingencia_retiro = 'I' where usuario_id = $id";
                break;
            case "CONTINGENCIADEPOSITOS":
                $sqlUpdate = "UPDATE usuario set contingencia_deposito = 'I' where usuario_id = $id";
                break;
            case "CONTINGENCIA":
                $sqlUpdate = "UPDATE usuario set contingencia = 'I' where usuario_id = $id";
                break;

        }

        $BonoInterno = new BonoInterno();
        $ActualizacionUsuarioConfiguracion = $BonoInterno->execQuery($Transaction,$sqlUpdate);

    }
}


$Transaction->commit();
$message = "*CRON: FIN (cronDesactivateContingencies) * " . " - Fecha: " . date("Y-m-d H:i:s");

if (!$ConfigurationEnvironment->isDevelopment()) {
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
}