<?php
/**
 * Created by PhpStorm.
 * User: danielfelipetamayogarcia
 * Date: 18/10/17
 * Time: 11:40 PM
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\mysql\BonoDetalleMySqlDAO;


require(__DIR__ . '../../vendor/autoload.php');

$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime( '-1 days' ) );
$fecha1 = date("Y-m-d 00:00:00", strtotime( '-1 days' ) );
$fecha2 = date("Y-m-d 23:59:59",strtotime( '-1 days' )  );

if($_REQUEST["diaSpc"] != ""){
    $fechaSoloDia = date("Y-m-d", strtotime( $_REQUEST["diaSpc"] ) );
    $fecha1 = date("Y-m-d 00:00:00", strtotime( $_REQUEST["diaSpc"] ) );
    $fecha2 = date("Y-m-d 23:59:59",strtotime( $_REQUEST["diaSpc"] )  );

}
//BETWEEN '".$fecha1."' AND '".$fecha2."'

$strEliminado="DELETE FROM usuario_deporte_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."';
DELETE FROM usuario_casino_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."';
DELETE FROM usucasino_detalle_resumen WHERE date_format(fecha_crea, '%Y-%m-%d')= '".$fechaSoloDia."';
DELETE FROM usuario_retiro_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."';
DELETE FROM usuario_recarga_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."';
";


/* SPORTAFF*/
$sqlSPORTAFFDia1CONPRODUCTO = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)




SELECT producto_interno.productointerno_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenhijo/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN producto_interno ON (producto_interno.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = producto_interno.productointerno_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2  AND concesionario.usuhijo_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT producto_interno.productointerno_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre1/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN producto_interno ON (producto_interno.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = producto_interno.productointerno_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT producto_interno.productointerno_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre2/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN producto_interno ON (producto_interno.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = producto_interno.productointerno_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT producto_interno.productointerno_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre3/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN producto_interno ON (producto_interno.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = producto_interno.productointerno_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre4/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre4_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
ORDER BY fecha, usuario

";

/* SPORTAFF*/
$sqlSPORTAFFDia1OLD = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenhijo/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2  AND concesionario.usuhijo_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre1/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre2/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre3/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre4/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre4_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
ORDER BY fecha, usuario

";


$sqlSPORTAFFDia1 = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenhijo,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usuhijo_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenhijo,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usuhijo_id !=0
GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), usuario.usuario_id

)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre1,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre1,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0
GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), usuario.usuario_id

)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre2,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre2,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0
GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), usuario.usuario_id

)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre3,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre3,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0
GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), usuario.usuario_id

)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";


/* DEPOSITOAFF*/
$sqlDEPOSITOAFFDia1 = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenhijo/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN casino.registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usuhijo_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre1/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN casino.registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre2/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN casino.registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre2_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre3/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN casino.registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre3_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre4/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN casino.registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre4_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

ORDER BY fecha, usuario

";




/* SPORTPVIP*/

$sqlSPORTPVIPDia1OLD = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenhijo/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1  AND concesionario.usuhijo_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre1/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre2/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre2_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre3/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre3_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre4/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre4_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
ORDER BY fecha, usuario

";

$sqlSPORTPVIPDia1 = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenhijo,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usuhijo_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenhijo,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usuhijo_id !=0
GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), usuario.usuario_id

)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre1,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre1,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre_id !=0
GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), usuario.usuario_id

)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre2,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre2_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre2,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre2_id !=0
GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), usuario.usuario_id

)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre3,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre3_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre3,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "'
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre3_id !=0
GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), usuario.usuario_id

)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";



/* SPORTPV*/

$sqlSPORTPVDia1OLD = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenhijo/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."' AND concesionario.usuhijo_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre1/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."' AND concesionario.usupadre_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre2/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."' AND concesionario.usupadre2_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre3/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."' AND concesionario.usupadre3_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre4/100) comision,date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d')fecha,0,0,it_ticket_enc.ticket_id
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."' AND concesionario.usupadre4_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
ORDER BY fecha, usuario

";

/* SPORTPV*/

$sqlSPORTPVDia1 = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenhijo,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND concesionario.usuhijo_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenhijo,date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND concesionario.usuhijo_id !=0
GROUP BY date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d'), usuario.usuario_id

)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre1,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND concesionario.usupadre_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre1,date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND concesionario.usupadre_id !=0
GROUP BY date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d'), usuario.usuario_id

)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre2,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND concesionario.usupadre2_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre2,date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND concesionario.usupadre2_id !=0
GROUP BY date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d'), usuario.usuario_id

)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,(SUM(apuestas) - SUM(premios)) valor,(SUM(apuestas) - SUM(premios))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios,concesionario.porcenpadre3,date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND concesionario.usupadre3_id !=0
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios,concesionario.porcenpadre3,date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,''
FROM casino.it_ticket_enc
       INNER JOIN casino.usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND concesionario.usupadre3_id !=0
GROUP BY date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d'), usuario.usuario_id

)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";



/* DEPOSITOPV*/
$sqlDEPOSITOPVDia1 = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)


SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenhijo/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usuhijo_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre1/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre2/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre2_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre3/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre3_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre4/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0,0,usuario_recarga.recarga_id
FROM casino.usuario_recarga
       INNER JOIN casino.usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre4_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id
ORDER BY fecha, usuario

";

/* RETIROSPV*/
$sqlRETIROSPVVDia1 = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)


SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenhijo/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0,0,cuenta_cobro.cuenta_id
FROM casino.cuenta_cobro
       INNER JOIN casino.usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usuhijo_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenpadre1/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0,0,cuenta_cobro.cuenta_id
FROM casino.cuenta_cobro
       INNER JOIN casino.usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenpadre2/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0,0,cuenta_cobro.cuenta_id
FROM casino.cuenta_cobro
       INNER JOIN casino.usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre2_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenpadre3/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0,0,cuenta_cobro.cuenta_id
FROM casino.cuenta_cobro
       INNER JOIN casino.usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre3_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenpadre4/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0,0,cuenta_cobro.cuenta_id
FROM casino.cuenta_cobro
       INNER JOIN casino.usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.usuario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '".$fechaSoloDia."'
    AND concesionario.usupadre4_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id
ORDER BY fecha, usuario

";

$sqlRESUMENCOMISIONES="INSERT INTO usucomision_resumen (tipo, fecha_crea,usuario_id, usuarioref_id, valor, comision)
SELECT usuario_comision.tipo,
       date_format(
           usuario_comision.fecha_crea,
           '%Y-%m-%d') fecha,
       usuario_comision.usuario_id,
       usuario_comision.usuarioref_id,
       SUM(usuario_comision.valor)    valor,
       SUM(
           usuario_comision.comision) comision
FROM usuario_comision
WHERE  date_format(usuario_comision.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
GROUP BY usuario_comision.tipo,
         usuario_comision.usuarioref_id,
         usuario_comision.usuario_id,
         date_format(
             usuario_comision.fecha_crea,
             '%Y-%m-%d')
ORDER BY fecha";



$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();


$BonoInterno->execQuery($transaccion,$strEliminado);

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."Eliminado: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

$BonoInterno->execQuery($transaccion,$sqlSPORTAFFDia1);


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."SPORTAFFDia: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

$BonoInterno->execQuery($transaccion,$sqlDEPOSITOAFFDia1);


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."DEPOSITOAFFDia: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);



$BonoInterno->execQuery($transaccion,$sqlSPORTPVIPDia1);


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."SPORTPVIPDia: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);



$BonoInterno->execQuery($transaccion,$sqlSPORTPVDia1);


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."SPORTPVDia: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);



$BonoInterno->execQuery($transaccion,$sqlDEPOSITOPVDia1);


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."DEPOSITOPVDia: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

$BonoInterno->execQuery($transaccion,$sqlRETIROSPVVDia1);


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."RETIROSPVDia: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


$BonoInterno->execQuery($transaccion,$sqlRESUMENCOMISIONES);


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."ResumenComisiones: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);




$transaccion->commit();

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."Terminacion: ". date('Y-m-d H:i:s');
fwriteCustom('log_' . date("Y-m-d") . '.log',$log);