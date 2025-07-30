<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\LealtadInterna;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;


ini_set('display_errors', 'OFF');
//date_default_timezone_set('America/Lima');
date_default_timezone_set('America/Bogota');
require_once __DIR__ . '../../vendor/autoload.php';
///home/devadmin/api/api/

ini_set('memory_limit', '-1');
header('Content-Type: application/json');
try {
    syslog(LOG_WARNING, "cronACEPSA :" . ' - '  .  json_encode($_SERVER) .  json_encode($_REQUEST) );

    $dateFrom = date('Y-m-d 00:00:00',strtotime('-1 day'));

    $dateTo = date('Y-m-d 23:59:59',strtotime('-1 day'));

    $sql= "SELECT Partner,
Pais,
IDUsuario,
Nombre,
TipoIdentificacion,
Identificacion,
Telefono,
Correo,
#Ciudad,
#Colonia,
Calle,
Fecha,
Tipo,
TransaccionID,
Ingreso,
Egreso,
Egreso AS Premio,
CASE WHEN Egreso = 0 THEN 0 ELSE 1 END AS 'EgresoPremio',
CASE WHEN Devolucion = 0 THEN 0 ELSE Egreso / Devolucion END AS 'EgresoDevolucion',
SaldoPromocion AS Saldo_Promocion
FROM (SELECT m.nombre AS Partner,
ps.pais_nom AS Pais,
u.usuario_id AS IDUsuario,
u.nombre AS Nombre,
CASE
WHEN r.tipo_doc = 'C' THEN 'INE'
WHEN r.tipo_doc = 'P' THEN 'PASAPORTE'
WHEN r.tipo_doc = 'E' THEN 'CURP'
ELSE r.tipo_doc END AS TipoIdentificacion,
r.cedula AS Identificacion,
r.celular AS Telefono,
u.login AS Correo,
#cd.ciudad_nom AS Ciudad,
#dp.depto_nom AS Colonia,
r.direccion AS Calle,
tjl.fecha_crea AS Fecha,
s.tipo AS Tipo,
tjl.transjuego_id AS TransaccionID,
SUM(CASE
WHEN tjl.tipo LIKE 'DEBIT%' AND tjl.tipo NOT LIKE '%FREESPIN' THEN tjl.valor
ELSE 0 END) AS Ingreso,
SUM(CASE
WHEN (tjl.tipo LIKE 'CREDIT%' OR tjl.tipo LIKE '%ROLLBACK%') AND tjl.tipo NOT LIKE '%FREESPIN'
THEN tjl.valor
ELSE 0 END) AS Egreso,
SUM(CASE
WHEN tjl.tipo LIKE 'ROLLBACK%' AND tjl.tipo NOT LIKE '%FREESPIN' THEN tj.valor_premio
ELSE 0 END) AS 'Devolucion',
SUM(CASE
WHEN tjl.tipo LIKE '%FREESPIN' AND tjl.tipo NOT LIKE 'DEBIT%' THEN tjl.valor
WHEN tjl.tipo LIKE 'DEBITFREESPIN' THEN -tjl.valor
ELSE 0 END) AS SaldoPromocion
FROM transjuego_log tjl
INNER JOIN transaccion_juego tj ON tjl.transjuego_id = tj.transjuego_id
INNER JOIN producto_mandante pm ON tjl.producto_id = pm.prodmandante_id
INNER JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
INNER JOIN usuario u ON um.usuario_mandante = u.usuario_id
INNER JOIN producto p ON pm.producto_id = p.producto_id
INNER JOIN subproveedor s ON p.subproveedor_id = s.subproveedor_id
INNER JOIN proveedor p2 ON s.proveedor_id = p2.proveedor_id
INNER JOIN registro r ON u.usuario_id = r.usuario_id
#INNER JOIN ciudad cd ON r.ciudad_id = cd.ciudad_id
#INNER JOIN departamento dp ON cd.depto_id = dp.depto_id
INNER JOIN mandante m ON u.mandante = m.mandante
INNER JOIN pais ps ON u.pais_id = ps.pais_id
WHERE 1 = 1
AND ((tj.fecha_crea)) >= '$dateFrom'
AND ((tj.fecha_crea)) <= '$dateTo'
AND u.mandante = 18
AND u.pais_id = 146
GROUP BY tjl.transjuego_id) x";


    $TransjuegoLog = new TransjuegoLog();
    $TransaccionJuegoMySqlDAO = new \Backend\mysql\TransaccionJuegoMySqlDAO();
    $transaccion = $TransaccionJuegoMySqlDAO->getTransaction();

    $movimientos = $TransjuegoLog->execQuery($transaccion, $sql);


    function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

    $Data = new  SimpleXMLElement("<Transacciones></Transacciones>");
    foreach ($movimientos as $key => $value) {
        //$Casino = new  SimpleXMLElement("<Transaccion></Transaccion>");
        $Casino = $Data->addChild("Transaccion");
        $Casino->addChild('Ingreso', round($value["x.Ingreso"], 2));
        $Casino->addChild('Egreso', round($value["x.Egreso"], 2));
        $Casino->addChild('EgresoPremio', round($value[".EgresoPremio"], 2));
        $Casino->addChild('EgresoDevolucion', round($value[".EgresoDevolucion"], 2));
        $Casino->addChild('SaldoPromocion', round($value["x.SaldoPromocion"], 2));
        $Casino->addChild('Nombre', $value["x.Nombre"]);
        $Casino->addChild('NumeroTicket', $value["x.TransaccionID"]);
        $Casino->addChild('Fecha', $value["x.Fecha"]);
        $Casino->addChild('Tipo', $value["x.Tipo"]);
        $Casino->addChild('Calle', quitar_tildes($value["x.Calle"]));
        $Casino->addChild('Colonia', quitar_tildes($value["x.Colonia"]));
        $Casino->addChild('Ciudad', quitar_tildes($value["x.Ciudad"]));
        $Casino->addChild('Pais', quitar_tildes($value["x.Pais"]));
        $Casino->addChild('Nacionalidad',quitar_tildes($value["x.Pais"]));
        $Casino->addChild('TipoIdentificacion', $value["x.TipoIdentificacion"]);
        $Casino->addChild('NumeroIdentificacion', $value["x.Identificacion"]);
        $Casino->addChild('NumeroTelefono', $value["x.Telefono"]);
        $Casino->addChild('CorreoElectronico', $value["x.Correo"]);

    }
    $Data->asXML(__DIR__.'/casinodb_' . date("Y-m-d") . '.xml');

    $server = '201.139.111.131';

    $usuario = 'gangabet';
    $pass = '4c3g4ng42023';
    $connection = ftp_connect($server);

    //$connection = ssh2_connect($server, 21);
    if (!$connection) die('Conexión fallido');

    $authe = ftp_login($connection,$usuario,$pass);
    //$authe = ssh2_auth_password($connection,$usuario, $pass);
    if (!$authe) die('Autenticación fallida');
    ftp_pasv($connection,true);


    $Envio =  ftp_put( $connection, 'casinodb_'.date("Y-m-d").'.xml', __DIR__.'/casinodb_'.date("Y-m-d").'.xml', FTP_BINARY);
    //$Envio = ssh2_scp_send($connection,__DIR__.'/casinodb_'.date("Y-m-d").'.xml', '201.139.111.131:21/casinodb_'.date("Y-m-d").'.xml', 0644);
    if (!$Envio) die('Envio fallido');
    ftp_close($connection);
    //print_r("llego");


    $dateFrom = date('Y-m-d 00:00:00',strtotime('-1 day'));

    $dateTo = date('Y-m-d 23:59:59',strtotime('-1 day'));

    $sql= "SELECT CASE
           WHEN ite.eliminado = 'S' AND ite.fecha_crea != fecha_cierre
               THEN -ite.vlr_apuesta
           WHEN (ite.eliminado = 'S' AND ite.fecha_crea = fecha_cierre)
               THEN 0
           ELSE ite.vlr_apuesta END                                AS Ingreso,
       CASE WHEN ite.premiado = 'S' THEN ite.vlr_premio ELSE 0 END AS Egreso,
       ite.vlr_premio / ite.vlr_premio                             AS 'Egreso/Premio',
       CASE
           WHEN ite.bet_status IN ('A', 'J', 'M') THEN ite.vlr_premio
           ELSE 0 END                  AS 'EgresoDevolucion',
       ite.freebet                     AS Saldo_Promocion,
       u.nombre                        AS Nombre,
       ite.ticket_id                   AS Ticket,
       ite.fecha_cierre_time           AS FechaCierre,
       ite.fecha_crea_time             AS FechaCrea,
       r.direccion                     AS Calle,
#       dp.depto_nom                    AS Colonia,
       n.pais_nom                      AS Nacionalidad,
#       c.ciudad_nom                    AS Ciudad,
       p.pais_nom                      AS Pais,
       r.cedula                        AS Identificacion,
       CASE
           WHEN r.tipo_doc = 'C' THEN 'INE'
           WHEN r.tipo_doc = 'P' THEN 'PASAPORTE'
           WHEN r.tipo_doc = 'E' THEN 'CURP'
           ELSE r.tipo_doc END         AS TipoIdentificacion,
       r.celular                       AS Telefono,
       u.login                         AS Correo
FROM it_ticket_enc ite
         JOIN usuario u ON ite.usuario_id = u.usuario_id
         JOIN registro r on u.usuario_id = r.usuario_id
         JOIN pais p on u.pais_id = p.pais_id
         JOIN pais n ON r.nacionalidad_id = n.pais_id
         JOIN ciudad c on r.ciudad_id = c.ciudad_id
         JOIN departamento dp ON c.depto_id = dp.depto_id

WHERE 1 = 1
  AND ((ite.fecha_cierre_time)) >= '$dateFrom'
  AND ((ite.fecha_cierre_time)) <= '$dateTo'
  AND u.pais_id = 146
  AND u.mandante = 18";

    $TransjuegoLog = new TransjuegoLog();
    $TransaccionJuegoMySqlDAO = new \Backend\mysql\TransaccionJuegoMySqlDAO();
    $transaccion = $TransaccionJuegoMySqlDAO->getTransaction();

    $movimientos = $TransjuegoLog->execQuery($transaccion, $sql);



    $Data = new  SimpleXMLElement("<Transacciones></Transacciones>");
    foreach ($movimientos as $key => $value) {
        //$Deporte = new  SimpleXMLElement("<Transaccion></Transaccion>");
        $Deporte= $Data->addChild("Transaccion");
        $Deporte->addChild('Ingreso', round($value[".Ingreso"], 2));
        $Deporte->addChild('Egreso', round($value[".Egreso"], 2));
        $Deporte->addChild('EgresoPremio', round($value[".EgresoPremio"], 2));
        $Deporte->addChild('EgresoDevolucion', round($value[".EgresoDevolucion"], 2));
        $Deporte->addChild('SaldoPromocion', round($value["ite.Saldo_Promocion"], 2));
        $Deporte->addChild('Nombre', $value["u.Nombre"]);
        $Deporte->addChild('NumeroTicket', $value["ite.Ticket"]);
        $Deporte->addChild('FechaCierre', $value["ite.FechaCierre"]);
        $Deporte->addChild('FechaCrea', $value["ite.FechaCrea"]);
        $Deporte->addChild('Calle', quitar_tildes($value["r.Calle"]));
        $Deporte->addChild('Colonia', quitar_tildes($value["dp.Colonia"]));
        $Deporte->addChild('Ciudad', quitar_tildes($value["c.Ciudad"]));
        $Deporte->addChild('Pais', quitar_tildes($value["p.Pai"]));;
        $Deporte->addChild('Nacionalidad',quitar_tildes($value["n.Nacionalidad"]));
        $Deporte->addChild('TipoIdentificacion', $value[".TipoIdentificacion"]);
        $Deporte->addChild('NumeroIdentificacion', $value["r.Identificacion"]);
        $Deporte->addChild('NumeroTelefono', $value["r.Telefono"]);
        $Deporte->addChild('CorreoElectronico', $value["u.Correo"]);

    }
    $Data->asXML(__DIR__.'/deportesdb_' . date("Y-m-d") . '.xml');


    $server = '201.139.111.131';
    $usuario = 'gangabet';
    $pass = '4c3g4ng42023';
    $connection = ftp_connect($server);

    //$connection = ssh2_connect($server, 21);
    if (!$connection) die('Conexión fallido');

    $authe = ftp_login($connection,$usuario,$pass);
    //$authe = ssh2_auth_password($connection,$usuario, $pass);
    if (!$authe) die('Autenticación fallida');
    ftp_pasv($connection,true);
    $Envio =  ftp_put( $connection, 'deportesdb_'.date("Y-m-d").'.xml', __DIR__.'/deportesdb_'.date("Y-m-d").'.xml', FTP_BINARY);
    //$Envio = ssh2_scp_send($connection,__DIR__.'/casinodb_'.date("Y-m-d").'.xml', '201.139.111.131:21/casinodb_'.date("Y-m-d").'.xml', 0644);
    if (!$Envio) die('Envio fallido');
    ftp_close($connection);
    //print_r("llego");


}catch (Exception $e){
print_r($e);
}
