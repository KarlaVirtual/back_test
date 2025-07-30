<?php
/**
 * Resúmen cronométrico de comisiones
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
use Backend\mysql\BonoDetalleMySqlDAO;



/**
 * Clase 'CronJobResumenesComisiones2AM'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobResumenesComisiones2AM
{


    public function __construct()
    {
    }

    public function execute()
    {

        $ResumenesCron = new ResumenesCron();

        $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

        $_ENV["TIMEZONE"] = "-03:00";

//$ResumenesCron->generateResumenes();

        $fechaSoloDia = date("Y-m-d", strtotime('-1 days'));
        $fecha1 = date("Y-m-d 00:00:00", strtotime('-1 days'));
        $fecha2 = date("Y-m-d 23:59:59", strtotime('-1 days'));

        if ($_REQUEST["diaSpc"] != "") {
            $fechaSoloDia = date("Y-m-d", strtotime($_REQUEST["diaSpc"]));
            $fecha1 = date("Y-m-d 00:00:00", strtotime($_REQUEST["diaSpc"]));
            $fecha2 = date("Y-m-d 23:59:59", strtotime($_REQUEST["diaSpc"]));

        }

        $message = "*CRON: (Inicio) * " . " ResumenesComisiones-2AM - Fecha: " . date("Y-m-d H:i:s");

        if ($ConfigurationEnvironment->isDevelopment()) {

        } else {
            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");
        }

//BETWEEN '".$fecha1."' AND '".$fecha2."'

        $strEliminado = "DELETE FROM usuario_comision WHERE fecha_crea >= '" . $fecha1 . "' AND fecha_crea <= '" . $fecha2 . "';
DELETE FROM usucomision_resumen WHERE  fecha_crea >= '" . $fecha1 . "' AND fecha_crea <= '" . $fecha2 . "';

";


        /* SPORTAFF*/
        $sqlSPORTAFFDia1CONPRODUCTO = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)




SELECT producto_interno.productointerno_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenhijo/100) comision,it_ticket_enc.fecha_cierre fecha,0,0
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN producto_interno ON (producto_interno.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = producto_interno.productointerno_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2  AND concesionario.usuhijo_id !=0 
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT producto_interno.productointerno_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre1/100) comision,it_ticket_enc.fecha_cierre fecha,0,0
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN producto_interno ON (producto_interno.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = producto_interno.productointerno_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT producto_interno.productointerno_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre2/100) comision,it_ticket_enc.fecha_cierre fecha,0,0
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN producto_interno ON (producto_interno.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = producto_interno.productointerno_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT producto_interno.productointerno_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre3/100) comision,it_ticket_enc.fecha_cierre fecha,0,0
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN producto_interno ON (producto_interno.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = producto_interno.productointerno_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre4/100) comision,it_ticket_enc.fecha_cierre fecha,0,0
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre4_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
ORDER BY fecha, usuario

";

        /* SPORTAFF*/
        $sqlSPORTAFFDia1OLD = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenhijo/100) comision,it_ticket_enc.fecha_cierre fecha,0,0,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2  AND concesionario.usuhijo_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre1/100) comision,it_ticket_enc.fecha_cierre fecha,0,0,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre2/100) comision,it_ticket_enc.fecha_cierre fecha,0,0,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre3/100) comision,it_ticket_enc.fecha_cierre fecha,0,0,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre4/100) comision,it_ticket_enc.fecha_cierre fecha,0,0,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre4_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
ORDER BY fecha, usuario

";


        $sqlSPORTAFFDia1CON_IT_TICKETENC = "


SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)   - SUM(bonos))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usuhijo_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usuhijo_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                usuario.usuario_id                                                                    usuario,
                0                                                                                     apuestas,
                SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
                concesionario.porcenhijo,
                it_transaccion.fecha_crea                                                             fecha,
                0                                                                                     usucrea_id,
                0                                                                                     usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usuhijo_id != 0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id

UNION
         SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre1,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usupadre_id != 0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id


)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id

UNION
         SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre2,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usupadre2_id !=0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id


)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id


UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre3,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usupadre3_id !=0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
         
)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";


        $sqlSPORTNGRAFFDia1CON_IT_TICKETENC = "


SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usuhijo_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usuhijo_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                usuario.usuario_id                                                                    usuario,
                0                                                                                     apuestas,
                SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
                concesionario.porcenhijo,
                it_transaccion.fecha_crea                                                             fecha,
                0                                                                                     usucrea_id,
                0                                                                                     usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usuhijo_id != 0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
         UNION
         SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenhijo,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('F','D','ND') and bono_log.estado='L'
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND registro.afiliador_id != 0
           AND concesionario.usuhijo_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id

UNION
         SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre1,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usupadre_id != 0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
         UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre1,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('F','D','ND') and bono_log.estado='L'
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND registro.afiliador_id != 0
           AND concesionario.usupadre_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id


)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id

UNION
         SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre2,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usupadre2_id !=0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
         UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre2,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('F','D','ND') and bono_log.estado='L'
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND registro.afiliador_id != 0
           AND concesionario.usupadre2_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id


)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,''
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=2 AND concesionario.usupadre3_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id


UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre3,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usupadre3_id !=0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
         
         UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre3,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('F','D','ND') and bono_log.estado='L'
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND registro.afiliador_id != 0
           AND concesionario.usupadre3_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id

         
)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";


        $sqlSPORTNGRAFFDia1 = "

SELECT clasificador_id,
       concesionario_id,
       usuhijo_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenhijo / 100) comision,
       fecha,
       0                                                   usucrea_id,
       0                                                   usumodif_id,
       ''                                                  externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,

                concesionario.porcenhijo,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usuhijo_id != 0
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
         
                  UNION
         SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenhijo,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('F','D','ND') and bono_log.estado='L'
           AND mandante_detalle.valor = 2
           AND registro.afiliador_id != 0
           AND concesionario.usuhijo_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id

     ) a
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre1 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre1,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
         
         UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre1,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('F','D','ND') and bono_log.estado='L'
           AND mandante_detalle.valor = 2
           AND registro.afiliador_id != 0
           AND concesionario.usupadre_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
     ) b
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre2_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre2 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre2_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre2,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre2_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
         UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre2,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('F','D','ND') and bono_log.estado='L'
           AND mandante_detalle.valor = 2
           AND registro.afiliador_id != 0
           AND concesionario.usupadre2_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
     ) c
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre3_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre3 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre3_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre3,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre3_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
         
         UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre3,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTNGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('F','D','ND') and bono_log.estado='L'
           AND mandante_detalle.valor = 2
           AND registro.afiliador_id != 0
           AND concesionario.usupadre3_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
     ) d
GROUP BY fecha, usuario

ORDER BY fecha, usuario;
";

        $sqlCASINONGRAFFDia1 = "

SELECT clasificador_id,
       concesionario_id,
       usuhijo_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenhijo / 100) comision,
       fecha,
       0                                                   usucrea_id,
       0                                                   usumodif_id,
       ''                                                  externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                usuario.usuario_id usuario,
                SUM(CASE
                        WHEN usuario_casino_resumen.tipo IN ('1') THEN usuario_casino_resumen.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN usuario_casino_resumen.tipo IN ('2') THEN usuario_casino_resumen.valor
                        ELSE 0 END)       premios, 0 bonos,

                concesionario.porcenhijo,
                date_format(usuario_casino_resumen.fecha_crea, '%Y-%m-%d')fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM usuario_casino_resumen
                INNER JOIN usuario_mandante ON (usuario_casino_resumen.usuario_id = usuario_mandante.usumandante_id)

                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario_mandante.usuario_mandante)
                  INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
                    INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'CASINONGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE  concesionario.usuhijo_id != 0
           and usuario_casino_resumen.fecha_crea LIKE '%" . $fechaSoloDia . "%' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  
           AND usuario_perfil.perfil_id = 'USUONLINE'
         GROUP BY usuario_casino_resumen.fecha_crea, usuario_mandante.usuario_mandante
         
                  UNION
         SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenhijo,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'CASINONGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('DC', 'DL', 'DV', 'NC', 'NL', 'NV') and bono_log.estado='L'
           AND registro.afiliador_id != 0
           AND concesionario.usuhijo_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id

     ) a
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre1 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre_id,
                usuario.usuario_id usuario,
                SUM(CASE
                        WHEN usuario_casino_resumen.tipo IN ('1') THEN usuario_casino_resumen.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN usuario_casino_resumen.tipo IN ('2') THEN usuario_casino_resumen.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre1,
                date_format(usuario_casino_resumen.fecha_crea, '%Y-%m-%d')fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM usuario_casino_resumen
                INNER JOIN usuario_mandante ON (usuario_casino_resumen.usuario_id = usuario_mandante.usumandante_id)

                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario_mandante.usuario_mandante)
                  INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
                    INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'CASINONGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE  usuario_casino_resumen.fecha_crea LIKE '%" . $fechaSoloDia . "%' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre_id != 0
         GROUP BY usuario_casino_resumen.fecha_crea, usuario_mandante.usuario_mandante
        
         UNION
         
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre1,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'CASINONGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('DC', 'DL', 'DV', 'NC', 'NL', 'NV') and bono_log.estado='L'
           AND registro.afiliador_id != 0
           AND concesionario.usupadre_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
     ) b
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre2_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre2 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre2_id,
                usuario.usuario_id usuario,
                SUM(CASE
                        WHEN usuario_casino_resumen.tipo IN ('1') THEN usuario_casino_resumen.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN usuario_casino_resumen.tipo IN ('2') THEN usuario_casino_resumen.valor
                        ELSE 0 END)       premios, 0 bonos,

                concesionario.porcenpadre2,
                date_format(usuario_casino_resumen.fecha_crea, '%Y-%m-%d')fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM usuario_casino_resumen
                INNER JOIN usuario_mandante ON (usuario_casino_resumen.usuario_id = usuario_mandante.usumandante_id)

                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario_mandante.usuario_mandante)
                  INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
                    INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'CASINONGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE   usuario_casino_resumen.fecha_crea LIKE '%" . $fechaSoloDia . "%' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre2_id != 0
         GROUP BY usuario_casino_resumen.fecha_crea, usuario_mandante.usuario_mandante
         UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre2,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'CASINONGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('DC', 'DL', 'DV', 'NC', 'NL', 'NV') and bono_log.estado='L'
           AND registro.afiliador_id != 0
           AND concesionario.usupadre2_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
     ) c
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre3_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre3 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre3_id,
                usuario.usuario_id usuario,
                SUM(CASE
                        WHEN usuario_casino_resumen.tipo IN ('1') THEN usuario_casino_resumen.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN usuario_casino_resumen.tipo IN ('2') THEN usuario_casino_resumen.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre3,
                date_format(usuario_casino_resumen.fecha_crea, '%Y-%m-%d')fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM usuario_casino_resumen
                INNER JOIN usuario_mandante ON (usuario_casino_resumen.usuario_id = usuario_mandante.usumandante_id)

                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario_mandante.usuario_mandante)
                  INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
                    INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'CASINONGRAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE  usuario_casino_resumen.fecha_crea LIKE '%" . $fechaSoloDia . "%' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre3_id != 0
         GROUP BY usuario_casino_resumen.fecha_crea, usuario_mandante.usuario_mandante
         
         UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas, 0 premios,SUM(bono_log.valor) bonos,concesionario.porcenpadre3,bono_log.fecha_crea fecha,0 usucrea_id,0 usumodif_id,''
         FROM bono_log
                  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id)
                         INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'CASINONGRAFF')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               registro.afiliador_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE date_format(bono_log.fecha_crea, '%Y-%m-%d') ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  and bono_log.tipo IN ('DC', 'DL', 'DV', 'NC', 'NL', 'NV') and bono_log.estado='L'
           AND registro.afiliador_id != 0
           AND concesionario.usupadre3_id !=0
         GROUP BY date_format(bono_log.fecha_crea, '%Y-%m-%d'), usuario.usuario_id
     ) d
GROUP BY fecha, usuario

ORDER BY fecha, usuario;
";

        $sqlSPORTAFFDia1CON_IT_TICKETENC33 = "

SELECT clasificador_id,
       concesionario_id,
       usuhijo_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenhijo / 100) comision,
       fecha,
       0                                                   usucrea_id,
       0                                                   usumodif_id,
       ''                                                  externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('LOSS','WIN','REFUND') THEN it_ticket_enc.vlr_apuesta
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,

                concesionario.porcenhijo,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usuhijo_id != 0
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) a
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre1 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre1,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) b
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre2_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre2 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre2_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre2,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre2_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) c
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre3_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre3 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre3_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre3,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre3_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) d
GROUP BY fecha, usuario

ORDER BY fecha, usuario;
";

        $sqlSPORTAFFDia1 = "

SELECT clasificador_id,
       concesionario_id,
       usuhijo_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenhijo / 100) comision,
       fecha,
       0                                                   usucrea_id,
       0                                                   usumodif_id,
       ''                                                  externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,

                concesionario.porcenhijo,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           AND concesionario.usuhijo_id != 0
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) a
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre1 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre1,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) b
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre2_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre2 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre2_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre2,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre2_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) c
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre3_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre3 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''                                                    externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre3_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre3,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTAFF')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_ticket_enc.beneficiario_id != 0 AND mandante_detalle.valor = 2
           AND it_ticket_enc.tipo_beneficiario = 2
           and it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           AND usuario_perfil.perfil_id = 'USUONLINE'
           AND concesionario.usupadre3_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) d
GROUP BY fecha, usuario

ORDER BY fecha, usuario;
";

        /* DEPOSITOAFF*/
        $sqlDEPOSITOAFFDia1 = "



SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenhijo/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usuhijo_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre1/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre2/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre2_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre3/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre3_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre4/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOAFF')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND registro.afiliador_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre4_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

ORDER BY fecha, usuario

";


        /* SPORTPVIP*/

        $sqlSPORTPVIPDia1OLD = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenhijo/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1  AND concesionario.usuhijo_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre1/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre2/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre2_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre3/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre3_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre4/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre4_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
ORDER BY fecha, usuario

";

        $sqlSPORTPVIPDia1_CON_IT_TICKET_ENC = "

SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0   AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usuhijo_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0   AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usuhijo_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id


UNION
        SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenhijo,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,'' externo_id
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario=1
            AND concesionario.usuhijo_id !=0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id


)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0   AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0   AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id



UNION
 SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre1,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,'' externo_id
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario=1
            AND concesionario.usupadre_id !=0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
         
)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0   AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0   AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre2,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,'' externo_id
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario=1
            AND concesionario.usupadre2_id !=0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
)   c
      GROUP BY fecha, usuario

UNION
SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0   AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre3_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPVIP')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0   AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND it_ticket_enc.beneficiario_id != 0 AND it_ticket_enc.tipo_beneficiario=1 AND concesionario.usupadre3_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.fecha_cierre, usuario.usuario_id

UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,concesionario.porcenpadre3,it_transaccion.fecha_crea fecha,0 usucrea_id,0 usumodif_id,'' externo_id
         FROM it_transaccion
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
                  LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')
         WHERE it_transaccion.fecha_crea ='" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
           /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
           AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != ''/* OR
                (it_transaccion.fecha_crea = it_ticket_enc.fecha_cierre AND
                 it_transaccion.hora_crea > it_ticket_enc.hora_crea)*/)
           AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario=1
            AND concesionario.usupadre3_id !=0
         GROUP BY it_transaccion.fecha_crea, usuario.usuario_id
         
)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";


        $sqlSPORTPVIPDia1 = "

SELECT clasificador_id,
       concesionario_id,
       usuhijo_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenhijo / 100) comision,
       fecha,
       0                                                   usucrea_id,
       0                                                   usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                it_transaccion.usuario_id  usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)        apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)        premios, 0 bonos,
                concesionario.porcenhijo,
                it_transaccion.fecha_crea fecha,
                0                          usucrea_id,
                0                          usumodif_id,
                ''                         externo_id
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 1
           AND concesionario.usuhijo_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) a
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre1 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre1,
                it_transaccion.fecha_crea fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''                        externo_id
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 1
           AND concesionario.usupadre_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) b
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre2_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre2 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre2_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre2,
                it_transaccion.fecha_crea fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''                        externo_id
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 1
           AND concesionario.usupadre2_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) c
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre3_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre3 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre3_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre3,
                it_transaccion.fecha_crea fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''                        externo_id
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND it_ticket_enc.beneficiario_id != 0
           AND it_ticket_enc.tipo_beneficiario = 1
           AND concesionario.usupadre3_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) d
GROUP BY fecha, usuario

ORDER BY fecha, usuario

";


        /* SPORTPV*/

        $sqlSPORTPVDia1OLD = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)





SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenhijo/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND concesionario.usuhijo_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre1/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND concesionario.usupadre_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre2/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre3/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND concesionario.usupadre3_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(SUM(vlr_apuesta) - SUM(vlr_premio)) valor,(SUM(vlr_apuesta) - SUM(vlr_premio))*(concesionario.porcenpadre4/100) comision,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,it_ticket_enc.ticket_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND concesionario.usupadre4_id !=0
GROUP BY it_ticket_enc.ticket_id, usuario.usuario_id
ORDER BY fecha, usuario

";

        /* SPORTPV*/

        $sqlSPORTPVDia1_CON_IT_TICKET_ENCANTIGUA = "
SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenhijo,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N'  AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usuhijo_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos ,concesionario.porcenhijo,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usuhijo_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id

UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usuhijo_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenhijo,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usuhijo_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/


)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre1,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre1,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id


UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre1,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre2,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre2,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre2_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id


UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre2_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre2,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre2_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre3,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre3_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre3,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre3_id !=0 AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id


UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre3_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre3,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre3_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";

        $sqlSPORTPVDia1_CON_IT_TICKET_ENCCONFECHACREA = "
SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_crea fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.eliminado='N' ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usuhijo_id !=0
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(-vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ( (it_ticket_enc.eliminado='S'  AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.fecha_crea != it_ticket_enc.fecha_cierre ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usuhijo_id !=0
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) )) AND concesionario.usuhijo_id !=0 AND it_ticket_enc.premiado='S'
GROUP BY fecha, usuario.usuario_id

UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usuhijo_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenhijo,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usuhijo_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/


)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_crea fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.eliminado='N' ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre_id !=0
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(-vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ( (it_ticket_enc.eliminado='S'  AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.fecha_crea != it_ticket_enc.fecha_cierre ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre_id !=0
GROUP BY fecha, usuario.usuario_id
UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) )) AND concesionario.usupadre_id !=0 AND it_ticket_enc.premiado='S'
GROUP BY fecha, usuario.usuario_id


UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre1,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM( vlr_apuesta ) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_crea fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.eliminado='N' ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre2_id !=0
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM( -vlr_apuesta ) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_cierre  fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ( (it_ticket_enc.eliminado='S'  AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.fecha_crea != it_ticket_enc.fecha_cierre ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre2_id !=0
GROUP BY fecha, usuario.usuario_id
UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre2, it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) )) AND concesionario.usupadre2_id !=0 AND it_ticket_enc.premiado='S'
GROUP BY fecha, usuario.usuario_id


UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre2_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre2,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre2_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_crea fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.eliminado='N' ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre3_id !=0
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM( -vlr_apuesta ) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.eliminado='S'  AND it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.fecha_crea != it_ticket_enc.fecha_cierre ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre3_id !=0
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) )) AND concesionario.usupadre3_id !=0 AND it_ticket_enc.premiado='S'
GROUP BY fecha, usuario.usuario_id


UNION

SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre3_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre3,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre3_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";


        $sqlSPORTPVDia1_CON_IT_TICKET_ENC = "
SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.eliminado='N' ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usuhijo_id !=0
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenhijo,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) )) AND concesionario.usuhijo_id !=0 AND it_ticket_enc.premiado='S'
GROUP BY fecha, usuario.usuario_id

UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usuhijo_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenhijo,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usuhijo_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/


)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.eliminado='N' ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre_id !=0
GROUP BY fecha, usuario.usuario_id
UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre1,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) )) AND concesionario.usupadre_id !=0 AND it_ticket_enc.premiado='S'
GROUP BY fecha, usuario.usuario_id


UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre1,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM( vlr_apuesta ) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre2,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.eliminado='N' ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre2_id !=0
GROUP BY fecha, usuario.usuario_id
UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre2, it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) )) AND concesionario.usupadre2_id !=0 AND it_ticket_enc.premiado='S'
GROUP BY fecha, usuario.usuario_id


UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre2_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre2,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre2_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,(SUM(apuestas) - SUM(premios)  - SUM(bonos))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE   ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND it_ticket_enc.eliminado='N' ) ) AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) AND concesionario.usupadre3_id !=0
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre3,it_ticket_enc.fecha_cierre fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'SPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL)) )) AND concesionario.usupadre3_id !=0 AND it_ticket_enc.premiado='S'
GROUP BY fecha, usuario.usuario_id


UNION

SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre3_id,
       usuario.usuario_id                      usuario,
       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('NEWCREDIT','WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios, 0 bonos,
       concesionario.porcenpadre3,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre3_id != 0 /*AND it_transaccion.bet_status='A' AND it_ticket_enc.bet_status='A'*/
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";

        $sqlSPORTPVDia1 = "
SELECT clasificador_id,
       concesionario_id,
       usuhijo_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenhijo / 100) comision,
       fecha,
       0                                                   usucrea_id,
       0                                                   usumodif_id,
       ''                                                  externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                it_transaccion.usuario_id                         usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)                               apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)                               premios, 0 bonos,
                concesionario.porcenhijo,
                it_transaccion.fecha_crea fecha,
                0                                                 usucrea_id,
                0                                                 usumodif_id,
                ''                                                externo_id

         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE
           it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND concesionario.usuhijo_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) a
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre1 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre_id,
                it_transaccion.usuario_id                         usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)                               apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)                               premios, 0 bonos,
                concesionario.porcenpadre1,
                it_transaccion.fecha_crea fecha,
                0                                                 usucrea_id,
                0                                                 usumodif_id,
                ''                                                externo_id
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE
           it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND concesionario.usupadre_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) b
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre2_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre2 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre2_id,
                it_transaccion.usuario_id                         usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)                               apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)                               premios, 0 bonos,
                concesionario.porcenpadre2,
                it_transaccion.fecha_crea fecha,
                0                                                 usucrea_id,
                0                                                 usumodif_id,
                ''                                                externo_id
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE
           it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND concesionario.usupadre2_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) c
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre3_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas) - SUM(premios)  - SUM(bonos)) valor,
       (SUM(apuestas) - SUM(premios)  - SUM(bonos)) * (porcenpadre3 / 100) comision,
       fecha,
       0                                                     usucrea_id,
       0                                                     usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre3_id,
                it_transaccion.usuario_id                         usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)                               apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)                               premios, 0 bonos,
                concesionario.porcenpadre3,
                it_transaccion.fecha_crea fecha,
                0                                                 usucrea_id,
                0                                                 usumodif_id,
                ''                                                externo_id
         FROM it_transaccion
                  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                  INNER JOIN clasificador ON (clasificador.abreviado = 'SPORTPV')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
                  INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                               it_ticket_enc.beneficiario_id = concesionario.usuhijo_id AND
                                               concesionario.estado = 'A')

         WHERE
           it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND concesionario.usupadre3_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) d
GROUP BY fecha, usuario

ORDER BY fecha, usuario;

";


        /* BETSPORTPV*/

        $sqlBETSPORTPVDia1_IT_TICKET_ENC = "
SELECT clasificador_id,concesionario_id,usuhijo_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas)) valor,(SUM(apuestas))*(porcenhijo/100) comision,fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenhijo,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'BETSPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usuhijo_id !=0  AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenhijo,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'BETSPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usuhijo_id !=0  AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id



UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usuhijo_id,
       usuario.usuario_id                      usuario,
              SUM(CASE
                   WHEN it_transaccion.tipo IN ('NEWDEBIT')  and it_ticket_enc.premiado='N' AND it_transaccion.bet_status != 'A' then it_transaccion.valor
               WHEN it_transaccion.tipo IN ('NEWCREDIT')  AND it_ticket_enc.bet_status = 'A' then -it_transaccion.valor
               else 0 END)       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('WIN','REFUND','CASHOUT') then it_transaccion.valor
               WHEN it_transaccion.tipo = 'NEWDEBIT' and it_ticket_enc.premiado='S' AND it_ticket_enc.bet_status = 'A' then -it_transaccion.valor
               WHEN it_transaccion.tipo = 'NEWCREDIT' AND it_ticket_enc.bet_status != 'A' then it_transaccion.valor
               else 0 END)       premios, 0 bonos,

       concesionario.porcenhijo,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'BETSPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usuhijo_id != 0 /*AND it_transaccion.bet_status='A' */ AND it_ticket_enc.bet_status!='A'
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/

)   a
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas)) valor,(SUM(apuestas))*(porcenpadre1/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre1,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'BETSPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre_id !=0  AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre1,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'BETSPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre_id !=0  AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id






UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre_id,
       usuario.usuario_id                      usuario,
              SUM(CASE
                   WHEN it_transaccion.tipo IN ('NEWDEBIT')  and it_ticket_enc.premiado='N' AND it_transaccion.bet_status != 'A' then it_transaccion.valor
               WHEN it_transaccion.tipo IN ('NEWCREDIT')  AND it_ticket_enc.bet_status = 'A' then -it_transaccion.valor
               else 0 END)       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('WIN','REFUND','CASHOUT') then it_transaccion.valor
               WHEN it_transaccion.tipo = 'NEWDEBIT' and it_ticket_enc.premiado='S' AND it_ticket_enc.bet_status = 'A' then -it_transaccion.valor
               WHEN it_transaccion.tipo = 'NEWCREDIT' AND it_ticket_enc.bet_status != 'A' then it_transaccion.valor
               else 0 END)       premios, 0 bonos,

/*       0       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('WIN','REFUND','CASHOUT') then it_transaccion.valor else -it_transaccion.valor END) premios,*/
       concesionario.porcenpadre1,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'BETSPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre_id != 0 /*AND it_transaccion.bet_status='A' */ AND it_ticket_enc.bet_status!='A'
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/


)   b
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre2_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas)) valor,(SUM(apuestas))*(porcenpadre2/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre2,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'BETSPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre2_id !=0  AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre2,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'BETSPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre2_id !=0  AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id




UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre2_id,
       usuario.usuario_id                      usuario,
              SUM(CASE
                   WHEN it_transaccion.tipo IN ('NEWDEBIT')  and it_ticket_enc.premiado='N' AND it_transaccion.bet_status != 'A' then it_transaccion.valor
               WHEN it_transaccion.tipo IN ('NEWCREDIT')  AND it_ticket_enc.bet_status = 'A' then -it_transaccion.valor
               else 0 END)       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('WIN','REFUND','CASHOUT') then it_transaccion.valor
               WHEN it_transaccion.tipo = 'NEWDEBIT' and it_ticket_enc.premiado='S' AND it_ticket_enc.bet_status = 'A' then -it_transaccion.valor
               WHEN it_transaccion.tipo = 'NEWCREDIT' AND it_ticket_enc.bet_status != 'A' then it_transaccion.valor
               else 0 END)       premios, 0 bonos,

       concesionario.porcenpadre2,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'BETSPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre2_id != 0 /*AND it_transaccion.bet_status='A' */ AND it_ticket_enc.bet_status!='A'
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/


)   c
      GROUP BY fecha, usuario

UNION

SELECT clasificador_id,concesionario_id,usupadre3_id,usuario,SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas)) valor,(SUM(apuestas))*(porcenpadre3/100) comision,fecha,0 usucrea_id,0 usumodif_id,''
FROM (
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,SUM(vlr_apuesta) apuestas, 0 premios, 0 bonos,concesionario.porcenpadre3,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'BETSPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre3_id !=0  AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id
UNION
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,0 apuestas,SUM(vlr_premio)  premios, 0 bonos,concesionario.porcenpadre3,CASE WHEN mandante_detalle.valor = 1 THEN it_ticket_enc.fecha_cierre WHEN it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I' THEN it_ticket_enc.fecha_cierre ELSE fecha_pago END fecha,0 usucrea_id,0 usumodif_id,'' externo_id
FROM it_ticket_enc
       INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       INNER JOIN clasificador ON (clasificador.abreviado =  'BETSPORTPV')
       LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  it_ticket_enc.eliminado='N' AND ((it_ticket_enc.fecha_cierre = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor = 1 OR (it_ticket_enc.premiado = 'N' AND it_ticket_enc.estado = 'I')) ) OR (it_ticket_enc.fecha_pago = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND (mandante_detalle.valor IS NULL OR mandante_detalle.valor = 0) )) AND concesionario.usupadre3_id !=0  AND it_ticket_enc.bet_status != 'A'
GROUP BY fecha, usuario.usuario_id




UNION
SELECT clasificador.clasificador_id,
       concesionario.concesionario_id,
       concesionario.usupadre3_id,
       usuario.usuario_id                      usuario,
              SUM(CASE
                   WHEN it_transaccion.tipo IN ('NEWDEBIT')  and it_ticket_enc.premiado='N' AND it_transaccion.bet_status != 'A' then it_transaccion.valor
               WHEN it_transaccion.tipo IN ('NEWCREDIT')  AND it_ticket_enc.bet_status = 'A' then -it_transaccion.valor
               else 0 END)       apuestas,
       SUM(CASE WHEN it_transaccion.tipo IN ('WIN','REFUND','CASHOUT') then it_transaccion.valor
               WHEN it_transaccion.tipo = 'NEWDEBIT' and it_ticket_enc.premiado='S' AND it_ticket_enc.bet_status = 'A' then -it_transaccion.valor
               WHEN it_transaccion.tipo = 'NEWCREDIT' AND it_ticket_enc.bet_status != 'A' then it_transaccion.valor
               else 0 END)       premios, 0 bonos,

       concesionario.porcenpadre3,
       it_transaccion.fecha_crea               fecha,
       0                                       usucrea_id,
       0                                       usumodif_id,
       ''                                      externo_id
       FROM it_transaccion
         INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
         INNER JOIN clasificador ON (clasificador.abreviado = 'BETSPORTPV')
         LEFT OUTER JOIN clasificador clasificador2
                         ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
         LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                              mandante_detalle.pais_id = usuario.pais_id)
         INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                      usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado = 'A')
WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
  /*AND it_transaccion.tipo IN ('NEWCREDIT', 'NEWDEBIT')*/
    AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre AND it_ticket_enc.fecha_cierre != '' AND (it_ticket_enc.fecha_pago IS NULL OR (it_ticket_enc.fecha_pago > it_transaccion.fecha_crea OR (it_ticket_enc.fecha_pago = it_transaccion.fecha_crea AND it_ticket_enc.hora_pago > it_transaccion.hora_crea))))
  AND (mandante_detalle.valor = 1 OR (mandante_detalle.valor = 0 OR mandante_detalle.valor IS NULL))
  AND concesionario.usupadre3_id != 0 /*AND it_transaccion.bet_status='A' */ AND it_ticket_enc.bet_status!='A'
GROUP BY /*it_transaccion.ticket_id,*/ it_transaccion.fecha_crea, usuario.usuario_id
/*HAVING COUNT(*) >= 2*/



)   d
      GROUP BY fecha, usuario

ORDER BY fecha, usuario

";

        $sqlBETSPORTPVDia1 = "

SELECT clasificador_id,
       concesionario_id,
       usuhijo_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas)) valor,
       (SUM(apuestas)) * (porcenhijo / 100) comision,
       fecha,
       0                                    usucrea_id,
       0                                    usumodif_id,
       ''                                   externo_id
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usuhijo_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenhijo,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''                        externo_id

         FROM it_transaccion
               INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
               INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
               INNER JOIN clasificador ON (clasificador.abreviado = 'BETSPORTPV')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
               INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                            usuario.puntoventa_id = concesionario.usuhijo_id AND
                                            concesionario.estado = 'A')

         WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND concesionario.usuhijo_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) a
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas)) valor,
       (SUM(apuestas)) * (porcenpadre1 / 100) comision,
       fecha,
       0                                      usucrea_id,
       0                                      usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre1,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''                        externo_id
         FROM it_transaccion
               INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
               INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
               INNER JOIN clasificador ON (clasificador.abreviado = 'BETSPORTPV')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
               INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                            usuario.puntoventa_id = concesionario.usuhijo_id AND
                                            concesionario.estado = 'A')

         WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND concesionario.usupadre_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) b
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre2_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas)) valor,
       (SUM(apuestas)) * (porcenpadre2 / 100) comision,
       fecha,
       0                                      usucrea_id,
       0                                      usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre2_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre2,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''                        externo_id
         FROM it_transaccion
               INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
               INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
               INNER JOIN clasificador ON (clasificador.abreviado = 'BETSPORTPV')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
               INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                            usuario.puntoventa_id = concesionario.usuhijo_id AND
                                            concesionario.estado = 'A')

         WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND concesionario.usupadre2_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) c
GROUP BY fecha, usuario

UNION

SELECT clasificador_id,
       concesionario_id,
       usupadre3_id,
       usuario,
       SUM(apuestas) valor1,SUM(premios) valor2,SUM(bonos) valor3,(SUM(apuestas)) valor,
       (SUM(apuestas)) * (porcenpadre3 / 100) comision,
       fecha,
       0                                      usucrea_id,
       0                                      usumodif_id,
       ''
FROM (
         SELECT clasificador.clasificador_id,
                concesionario.concesionario_id,
                concesionario.usupadre3_id,
                it_transaccion.usuario_id usuario,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
                        ELSE 0 END)       apuestas,
                SUM(CASE
                        WHEN it_transaccion.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
                        WHEN it_transaccion.tipo IN ('NEWDEBIT') THEN -it_transaccion.valor
                        ELSE 0 END)       premios, 0 bonos,
                concesionario.porcenpadre3,
                it_transaccion.fecha_crea  fecha,
                0                         usucrea_id,
                0                         usumodif_id,
                ''                        externo_id

         FROM it_transaccion
               INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
               INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
               INNER JOIN clasificador ON (clasificador.abreviado = 'BETSPORTPV')
               LEFT OUTER JOIN clasificador clasificador2
                                  ON (clasificador2.abreviado = 'TYPECALCCOM' AND clasificador2.tipo = 'MT')
                                  LEFT OUTER JOIN mandante_detalle ON (clasificador2.clasificador_id = mandante_detalle.tipo AND
                                                       mandante_detalle.pais_id = usuario.pais_id)
               INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                            usuario.puntoventa_id = concesionario.usuhijo_id AND
                                            concesionario.estado = 'A')

         WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor = 2
           AND concesionario.usupadre3_id != 0
         GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
     ) d
GROUP BY fecha, usuario

ORDER BY fecha, usuario



";


        /* DEPOSITOPV*/
        $sqlDEPOSITOPVDia1 = "
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenhijo/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usuhijo_id !=0 
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre1/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre_id !=0 
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre2/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre2_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre3/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre3_id !=0 
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,(usuario_recarga.valor)*(concesionario.porcenpadre4/100) comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre4_id !=0 
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,(-usuario_recarga.valor)*(concesionario.porcenhijo/100) comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usuhijo_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,(-usuario_recarga.valor)*(concesionario.porcenpadre1/100) comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,(-usuario_recarga.valor)*(concesionario.porcenpadre2/100) comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre2_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,(-usuario_recarga.valor)*(concesionario.porcenpadre3/100) comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre3_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,(-usuario_recarga.valor)*(concesionario.porcenpadre4/100) comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre4_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id


ORDER BY fecha, usuario

";


        $sqlDEPOSITOPVXTRDia1 = "
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,concesionario.porcenhijo comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usuhijo_id !=0 
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,concesionario.porcenpadre1 comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre_id !=0 
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,concesionario.porcenpadre2 comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre2_id !=0
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,concesionario.porcenpadre3 comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre3_id !=0 
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(usuario_recarga.valor) valor,concesionario.porcenpadre4 comision,date_format(usuario_recarga.fecha_crea, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre4_id !=0 
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,-concesionario.porcenhijo comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usuhijo_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,-concesionario.porcenpadre1 comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,-concesionario.porcenpadre2 comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre2_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,-concesionario.porcenpadre3 comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre3_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(-usuario_recarga.valor) valor,-concesionario.porcenpadre4 comision,date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,usuario_recarga.recarga_id
FROM usuario_recarga
       INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'DEPOSITOPVXTR')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre4_id !=0 AND usuario_recarga.estado='I'
GROUP BY usuario_recarga.recarga_id, usuario.usuario_id


ORDER BY fecha, usuario

";

        /* RETIROSPV*/
        $sqlRETIROSPVVDia1 = "
SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usuhijo_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenhijo/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,cuenta_cobro.cuenta_id
FROM cuenta_cobro
       INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usuhijo_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenpadre1/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,cuenta_cobro.cuenta_id
FROM cuenta_cobro
       INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre2_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenpadre2/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,cuenta_cobro.cuenta_id
FROM cuenta_cobro
       INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre2_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre3_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenpadre3/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,cuenta_cobro.cuenta_id
FROM cuenta_cobro
       INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre3_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id

UNION

SELECT clasificador.clasificador_id,concesionario.concesionario_id,concesionario.usupadre4_id,usuario.usuario_id usuario,(cuenta_cobro.valor) valor,(cuenta_cobro.valor)*(concesionario.porcenpadre4/100) comision,date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d')fecha,0 usucrea_id,0 usumodif_id,cuenta_cobro.cuenta_id
FROM cuenta_cobro
       INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.puntoventa_id)
       LEFT OUTER JOIN clasificador ON (clasificador.abreviado =  'RETIROSPV')
       INNER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND usuario.puntoventa_id = concesionario.usuhijo_id AND concesionario.estado='A')
WHERE  date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
    AND concesionario.usupadre4_id !=0
GROUP BY cuenta_cobro.cuenta_id, usuario.usuario_id
ORDER BY fecha, usuario

";

        $sqlRESUMENCOMISIONES = "
SELECT usuario_comision.tipo,
       date_format(
           usuario_comision.fecha_crea,
           '%Y-%m-%d') fecha,
       usuario_comision.usuario_id,
       usuario_comision.usuarioref_id,
       SUM(usuario_comision.valor)    valor,
       SUM(usuario_comision.comision) comision,
       
        SUM(usuario_comision.valor1)    valor1,
       SUM(usuario_comision.valor2)    valor2,
       SUM(usuario_comision.valor3)    valor3,
       SUM(usuario_comision.valor4)    valor4,
       SUM(usuario_comision.valor5)    valor5,
       SUM(usuario_comision.valor6)    valor6

FROM usuario_comision
INNER JOIN usuario ON (usuario.usuario_id=usuario_comision.usuario_id)

WHERE  date_format(usuario_comision.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0 
GROUP BY usuario_comision.tipo,
         usuario_comision.usuarioref_id,
         usuario_comision.usuario_id,
         date_format(
             usuario_comision.fecha_crea,
             '%Y-%m-%d')
ORDER BY fecha";

        $sqlRESUMENCOMISIONESAUTOMATICAS = "
SELECT usucomision_resumen.usucomresumen_id,
        usucomision_resumen.tipo,
       usucomision_resumen.fecha_crea,
       usucomision_resumen.usuario_id,
       usucomision_resumen.usuarioref_id,
       usucomision_resumen.valor,
       usucomision_resumen.comision
FROM usucomision_resumen
INNER JOIN usuario ON (usuario.usuario_id=usucomision_resumen.usuario_id)
INNER JOIN clasificador ON (clasificador.tipo = 'MT' AND clasificador.abreviado='LIQUIDAFF')
INNER JOIN mandante_detalle ON (usuario.mandante=mandante_detalle.mandante AND mandante_detalle.pais_id=usuario.pais_id AND mandante_detalle.tipo = clasificador.clasificador_id)
WHERE mandante_detalle.estado='A' AND date_format(usucomision_resumen.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario.mandante  IN (17) AND usuario.maxima_comision >=0  AND mandante_detalle.valor='1'";

        if (true) {
            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();


//$BonoInterno->execQuery($transaccion, $strEliminado);

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Eliminado: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
            print_r($sqlSPORTAFFDia1);
            $data = $BonoInterno->execQuery('', $sqlSPORTAFFDia1);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "SPORTAFFDia: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $data = $BonoInterno->execQuery('', $sqlSPORTAFFDia1CON_IT_TICKETENC);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "SPORTAFFDia1CON_IT_TICKETENC: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlSPORTNGRAFFDia1);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "SPORTNGRAFFDia1: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlSPORTNGRAFFDia1CON_IT_TICKETENC);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "SPORTNGRAFFDia1CON_IT_TICKETENC: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlCASINONGRAFFDia1);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "CASINONGRAFFDia1: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlDEPOSITOAFFDia1);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.recarga_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "DEPOSITOAFFDia: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlSPORTPVIPDia1);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "SPORTPVIPDia: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlSPORTPVIPDia1_CON_IT_TICKET_ENC);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "SPORTPVIPDia1_CON_IT_TICKET_ENC: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $data = $BonoInterno->execQuery('', $sqlSPORTPVDia1);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "SPORTPVDia: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlSPORTPVDia1_CON_IT_TICKET_ENC);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "SPORTPVDia1_CON_IT_TICKET_ENC: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $data = $BonoInterno->execQuery('', $sqlBETSPORTPVDia1);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "BETSPORTPVDia: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlBETSPORTPVDia1_IT_TICKET_ENC);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor1, valor2, valor3, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
              VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.externo_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "BETSPORTPVDia1_IT_TICKET_ENC: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $data = $BonoInterno->execQuery('', $sqlDEPOSITOPVDia1);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
    VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.recarga_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "DEPOSITOPVDia: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $data = $BonoInterno->execQuery('', $sqlDEPOSITOPVXTRDia1);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
    VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.recarga_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "DEPOSITOPVXTRDia: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $data = $BonoInterno->execQuery('', $sqlRETIROSPVVDia1);
            print_r($sqlRETIROSPVVDia1);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_comision (tipo, concesionario_id ,usuario_id, usuarioref_id, valor, comision,fecha_crea,usucrea_id,usumodif_id,externo_id)
    VALUES ('" . $datanum->{'.clasificador_id'} . "','" . $datanum->{'.concesionario_id'} . "','" . $datanum->{'.usuhijo_id'} . "','" . $datanum->{'.usuario'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.cuenta_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RETIROSPVDia: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
            $procesoInterno = $BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesComisiones2AM','" . date("Y-m-d 00:00:00") . "','0');");

            $transaccion->commit();

        }

        sleep(60);

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();

        $data = $BonoInterno->execQuery('', $sqlRESUMENCOMISIONES);

        foreach ($data as $datanum) {
            $sql = "INSERT INTO usucomision_resumen (tipo, fecha_crea,usuario_id, usuarioref_id, valor, comision, valor1, valor2, valor3, valor4, valor5, valor6)
    VALUES ('" . $datanum->{'usuario_comision.tipo'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'usuario_comision.usuario_id'} . "','" . $datanum->{'usuario_comision.usuarioref_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor4'} . "','" . $datanum->{'.valor5'} . "','" . $datanum->{'.valor6'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "ResumenComisiones: " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        $procesoInterno = $BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesComisiones2','" . date("Y-m-d 00:00:00") . "','0');");

        $transaccion->commit();


        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();


        if (false) {
            if (intval(date("d")) == 1 || date("d") == "01") {

                $sqlRESUMENCOMISIONESAUTOMATICASMES = "
SELECT usucomision_resumen.usucomresumen_id,
        usucomision_resumen.tipo,
       usucomision_resumen.fecha_crea,
       usucomision_resumen.usuario_id,
       usucomision_resumen.usuarioref_id,
       usucomision_resumen.valor,
       usucomision_resumen.comision
FROM usucomision_resumen
INNER JOIN usuario ON (usuario.usuario_id=usucomision_resumen.usuario_id)
INNER JOIN clasificador ON (clasificador.tipo = 'MT' AND clasificador.abreviado='LIQUIDAFF')
INNER JOIN mandante_detalle ON (usuario.mandante=mandante_detalle.mandante AND mandante_detalle.pais_id=usuario.pais_id AND mandante_detalle.tipo = clasificador.clasificador_id)
WHERE mandante_detalle.estado='A' AND date_format(usucomision_resumen.fecha_crea, '%Y-%m') = '" . date("Y-m", strtotime('-1 days')) . "' AND mandante_detalle.valor='1'";


                $data = $BonoInterno->execQuery('', $sqlRESUMENCOMISIONESAUTOMATICASMES);

                foreach ($data as $datanum) {
                    $sql = "UPDATE  usucomision_resumen,usuario SET
          usuario.creditos_afiliacion = usuario.creditos_afiliacion + '" . $datanum->{'usucomision_resumen.comision'} . "',
          usucomision_resumen.estado = 'I',
          usucomision_resumen.valor_pagado = '" . $datanum->{'usucomision_resumen.comision'} . "'
          
          WHERE usuario.usuario_id=usucomision_resumen.usuario_id AND usucomision_resumen.usucomresumen_id='" . $datanum->{'usucomision_resumen.usucomresumen_id'} . "';";
                    $BonoInterno->execQuery($transaccion, $sql);
                }

                $sqlRESUMENCOMISIONESARRASTRENEGATIVO = "
SELECT usuario.arrastra_negativo,usuario.creditos_afiliacion,usuario.usuario_id
FROM usuario
WHERE usuario.creditos_afiliacion <0 AND arrastra_negativo=0;";


                $data = $BonoInterno->execQuery('', $sqlRESUMENCOMISIONESARRASTRENEGATIVO);

                foreach ($data as $datanum) {
                    $sql = "UPDATE  usuario SET
          usuario.creditos_afiliacion = 0
          WHERE usuario.usuario_id='" . $datanum->{'usuario.usuario_id'} . "';";
                    $BonoInterno->execQuery($transaccion, $sql);
                }


            }
        }


        $transaccion->commit();
        /*
        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();

        $sqlRESUMEN_TOTAL_COMISIONES = "
        INSERT INTO usucomisionusuario_resumen
        (valor1, valor2, valor3, usuario_id, fecha_crea, valor, comision, valor_pagado, tipo, estado)
        SELECT SUM(usucomision_resumen.valor1)       valor1,
               SUM(usucomision_resumen.valor2)       valor2,
               SUM(usucomision_resumen.valor3)       valor3,
               usucomision_resumen.usuario_id,
               usucomision_resumen.fecha_crea,
               SUM(usucomision_resumen.valor)        valor,
               SUM(usucomision_resumen.comision)     comision,
               SUM(usucomision_resumen.valor_pagado) valor_pagado,
               clasificador.clasificador_id,
               'A'
        FROM usucomision_resumen
                 INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id)
                 INNER JOIN clasificador ON (clasificador.clasificador_id = usucomision_resumen.tipo)
        where 1 = 1
          AND ((usucomision_resumen.fecha_crea)) >= '" . $fecha1 . "'
          AND ((usucomision_resumen.fecha_crea)) <= '" . $fecha2 . "'
        GROUP BY usucomision_resumen.usuario_id, usucomision_resumen.fecha_crea, usucomision_resumen.tipo
        ";
        $data = $BonoInterno->execQuery($transaccion, $sqlRESUMEN_TOTAL_COMISIONES);

        $procesoInterno=$BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesComisionesTotales','".date("Y-m-d 00:00:00")."','0');");

        $transaccion->commit();*/


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "Terminacion: " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        $message = "*CRON: (Fin) * " . " ResumenesComisiones-2AM - Fecha: " . date("Y-m-d H:i:s");


        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");
        }
    }
}