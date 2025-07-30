<?php

/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Mandante            Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $Country             Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $AffiliateId         Variable que almacena el identificador de un afiliado.
 * @var mixed $BonoInternoMySqlDAO Objeto que maneja operaciones de base de datos para bonos internos en MySQL.
 * @var mixed $Transaction         Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $sql                 Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $BonoInterno         Variable que representa un bono interno en el sistema.
 * @var mixed $Resultado           Variable que almacena el resultado de una operación o consulta.
 * @var mixed $array               Variable que almacena una lista o conjunto de datos.
 * @var mixed $index               Variable que representa un índice en una estructura de datos.
 * @var mixed $value               Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $item                Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $response            Esta variable almacena la respuesta generada por una operación o petición.
 */

use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;

if ($_REQUEST["Sign"] == "8eWGT3838M8ihOfiX4pA5vd8acrWJMYu") {
    $Mandante = $_REQUEST["Partner"];
    $Country = $_REQUEST["Country"];
    $AffiliateId = $_REQUEST["AffiliateId"];

    if ($Mandante == '') {
        throw new Exception("Error en los parametros enviados", "100001");
    }

    if ($Country == '') {
        throw new Exception("Error en los parametros enviados", "100001");
    }

    if ($AffiliateId == '') {
        throw new Exception("Error en los parametros enviados", "100001");
    }


    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $Transaction = $BonoInternoMySqlDAO->getTransaction();

    $sql = "SELECT m.nombre                                                                                         Partner,
       pais.pais_nom  AS Pais,
       ID_Usuario,
       SUM( CASE WHEN u.fecha_crea LIKE CONCAT(DATE(NOW() - INTERVAL 6 DAY),'%') THEN 1 ELSE 0 END)                                                                                     Fecha_Registro,
       SUM( CASE WHEN dc2.fecha_primer_deposito LIKE CONCAT(DATE(NOW() - INTERVAL 6 DAY),'%') THEN 1 ELSE 0 END)                                                                     AS Fecha_Primer_Deposito,
       r.link_id      AS Link,
       ul.nombre                                                                                        Usuario_Link,
       ul.fecha_crea                                                                                    Fecha_Creacion_Link,
       r.afiliador_id                                                                                   ID_Afiliador,
       u2.nombre                                                                                        Nombre_Afiliador,
       dc2.monto_primer_deposito                                                                     AS Valor_de_Depositos,
       SUM(Cantidad_de_Depositos)                                                                    AS Cantidad_de_Depositos,
       ROUND(SUM(Valor_de_Retiros_Pagados), 2)                                                       AS Valor_de_Retiros_Pagados,
       SUM(Cantidad_de_Retiros_Pagados)                                                              AS Cantidad_de_Retiros_Pagados,
       ROUND(SUM(Valor_primer_Deposito), 2)                                                          AS Valor_primer_Deposito,
       SUM(Cantidad_Spin)                                                                            AS Cantidad_Spin,
       ROUND(SUM(Apuestas_Casino), 2)                                                                AS Valor_Apostado_Casino,
       ROUND(SUM(Premios_Casino), 2)                                                                 AS Valor_Premios_Casino,
       ROUND(SUM(Premios_Bonos), 2)                                                                  AS Valor_Premios_Bonos,
       ROUND(SUM(Bonos_Casino), 2)                                                                   AS Valor_Bonos_Casino,
       ROUND(SUM(Apuestas_Casino) - SUM(Premios_Casino) - SUM(Premios_Bonos) - SUM(Bonos_Casino), 2) AS GGR_Casino,
       SUM(CantidadTickets)                                                                          AS Cantidad_Ticket,
       ROUND(SUM(Apuestas_Deportivas), 2)                                                            AS Valor_Apuestas_Deporte,
       ROUND(SUM(Premios_Deportivos), 2)                                                             AS Valor_Premios_Deporte,
       ROUND(SUM(Bonos_Deportivas), 2)                                                               AS Valor_Bonos_Deporte,
       ROUND(SUM(Apuestas_Deportivas) - SUM(Premios_Deportivos) - SUM(Bonos_Deportivas), 2)          AS GGR_Deportiva
FROM (SELECT u.usuario_id AS ID_Usuario,
             0            AS Valor_de_Depositos,
             0            AS Cantidad_de_Depositos,
             0            AS Valor_de_Retiros_Pagados,
             0            AS Cantidad_de_Retiros_Pagados,
             0            AS Valor_primer_Deposito,
             0            AS Cantidad_Spin,
             0            AS CantidadTickets,
             0            AS Apuestas_Deportivas,
             0            AS Premios_Deportivos,
             0            AS Bonos_Deportivas,
             0            AS Apuestas_Casino,
             0            AS Premios_Casino,
             0            AS Premios_Bonos,
             0            AS Bonos_Casino
      FROM usuario u
               JOIN registro r ON u.usuario_id = r.usuario_id
               JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
               JOIN pais pais ON u.pais_id = pais.pais_id
               JOIN mandante m ON u.mandante = m.mandante
      WHERE 1 = 1
        AND up.perfil_id = 'USUONLINE'
        AND u.mandante IN ($Mandante)
        AND u.pais_id in ($Country)
        AND r.afiliador_id in ($AffiliateId)
        AND u.fecha_crea >= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 00:00:00')
        AND u.fecha_crea <= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 23:59:59')
      GROUP BY u.usuario_id
      UNION
      SELECT u.usuario_id                  AS ID_Usuario,
             0                             AS Valor_de_Depositos,
             0                             AS Cantidad_de_Depositos,
             0                             AS Valor_de_Retiros_Pagados,
             0                             AS Cantidad_de_Retiros_Pagados,
             0                             AS Valor_primer_Deposito,
             0                             AS Cantidad_Spin,
             0                             AS CantidadTickets,
             0                             AS Apuestas_Deportivas,
             0                             AS Premios_Deportivos,
             ROUND(SUM(CASE
                           WHEN (pl.estado = 'L') AND pl.valor THEN IFNULL(pl.valor, 0)
                           WHEN (pl.estado = 'E') AND pl.valor THEN -IFNULL(pl.valor, 0)
                           ELSE 0 END), 2) AS Bonos_Deportivas,
             0                             AS Apuestas_Casino,
             0                             AS Premios_Casino,
             0                             AS Premios_Bonos,
             0                             AS Bonos_Casino
      FROM bono_log pl
               JOIN usuario u ON pl.usuario_id = u.usuario_id
               JOIN usuario_mandante um ON um.usuario_mandante = u.usuario_id
               JOIN registro r ON u.usuario_id = r.usuario_id
               JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
               JOIN pais pais ON u.pais_id = pais.pais_id
               JOIN mandante m ON u.mandante = m.mandante
      WHERE 1 = 1
        AND up.perfil_id = 'USUONLINE'
        AND (pl.estado = 'L' OR pl.estado = 'E')
        AND pl.tipo NOT IN ('TC', 'TL', 'SC', 'SCV', 'SL', 'TV', 'FC', 'DC', 'DL', 'DV', 'NC', 'NL', 'NV')
        AND u.mandante IN ($Mandante)
        AND u.pais_id in ($Country)
        AND r.afiliador_id in ($AffiliateId)
        AND pl.fecha_crea >= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 00:00:00')
        AND pl.fecha_crea <= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 23:59:59')
      GROUP BY u.usuario_id
      UNION
      SELECT u.usuario_id                  AS ID_Usuario,
             0                             AS Valor_de_Depositos,
             0                             AS Cantidad_de_Depositos,
             0                             AS Valor_de_Retiros_Pagados,
             0                             AS Cantidad_de_Retiros_Pagados,
             0                             AS Valor_primer_Deposito,
             0                             AS Cantidad_Spin,
             SUM(CASE
                     WHEN udr.tipo IN ('BET')
                         THEN udr.cantidad
                     WHEN udr.tipo IN ('STAKEDECREASE')
                         THEN - udr.cantidad
                     ELSE 0 END)           AS CantidadTickets,
             ROUND(SUM(CASE
                           WHEN udr.tipo LIKE '%BET%' AND
                                udr.tipo NOT IN ('WIN', 'NEWCREDIT',
                                                 'CASHOUT', 'REFUND',
                                                 'STAKEDECREASE')
                               THEN udr.valor
                           ELSE 0 END), 2) AS Apuestas_Deportivas,
             ROUND(SUM(CASE
                           WHEN udr.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT', 'REFUND', 'STAKEDECREASE')
                               THEN udr.valor
                           ELSE 0 END), 2) AS Premios_Deportivos,
             0                             AS Bonos_Deportivas,
             0                             AS Apuestas_Casino,
             0                             AS Premios_Casino,
             0                             AS Premios_Bonos,
             0                             AS Bonos_Casino
      FROM usuario_deporte_resumen udr
               JOIN usuario u ON udr.usuario_id = u.usuario_id
               JOIN registro r ON u.usuario_id = r.usuario_id
               JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
               JOIN pais pais ON u.pais_id = pais.pais_id
               JOIN mandante m ON u.mandante = m.mandante
      WHERE 1 = 1
        AND up.perfil_id = 'USUONLINE'
        AND u.mandante IN ($Mandante)
        AND u.pais_id in ($Country)
        AND r.afiliador_id in ($AffiliateId)
        AND udr.fecha_crea >= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 00:00:00')
        AND udr.fecha_crea <= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 23:59:59')
      GROUP BY u.usuario_id
      UNION
      SELECT u.usuario_id                                                       AS ID_Usuario,
             0                                                                  AS Valor_de_Depositos,
             0                                                                  AS Cantidad_de_Depositos,
             0                                                                  AS Valor_de_Retiros_Pagados,
             0                                                                  AS Cantidad_de_Retiros_Pagados,
             0                                                                  AS Valor_primer_Deposito,
             SUM(CASE WHEN udr.tipo LIKE 'DEBIT%' THEN udr.cantidad ELSE 0 END) AS Cantidad_Spin,
             0                                                                  AS CantidadTickets,
             0                                                                  AS Apuestas_Deportivas,
             0                                                                  AS Premios_Deportivos,
             0                                                                  AS Bonos_Deportivas,
             ROUND(SUM(CASE
                           WHEN udr.tipo LIKE 'DEBIT%' AND udr.tipo NOT LIKE '%FREESPIN'
                               THEN udr.valor + udr.valor_premios
                           ELSE 0 END), 2)                                      AS Apuestas_Casino,
             ROUND(SUM(CASE
                           WHEN (udr.tipo LIKE 'CREDIT%' OR udr.tipo LIKE 'ROLLBACK') AND
                                udr.tipo NOT LIKE '%FREESPIN' THEN udr.valor
                           ELSE 0 END), 2)                                      AS Premios_Casino,
             ROUND(SUM(CASE
                           WHEN udr.tipo LIKE '%FREESPIN' AND udr.tipo NOT LIKE 'DEBIT%' THEN udr.valor
                           WHEN udr.tipo LIKE 'DEBITFREESPIN' THEN -udr.valor
                           ELSE 0 END), 2)                                      AS Premios_Bonos,
             0                                                                  AS Bonos_Casino
      FROM usucasino_detalle_resumen udr
               JOIN usuario_mandante um on udr.usuario_id = um.usumandante_id
               JOIN usuario u ON um.usuario_mandante = u.usuario_id
               JOIN registro r ON u.usuario_id = r.usuario_id
               JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
               JOIN pais pais ON u.pais_id = pais.pais_id
               JOIN mandante m ON u.mandante = m.mandante
      WHERE 1 = 1
        AND up.perfil_id = 'USUONLINE'
        AND u.mandante IN ($Mandante)
        AND u.pais_id in ($Country)
        AND r.afiliador_id in ($AffiliateId)
        AND udr.fecha_crea >= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 00:00:00')
        AND udr.fecha_crea <= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 23:59:59')
      GROUP BY u.usuario_id
      UNION
      SELECT u.usuario_id                  AS ID_Usuario,
             0                             AS Valor_de_Depositos,
             0                             AS Cantidad_de_Depositos,
             0                             AS Valor_de_Retiros_Pagados,
             0                             AS Cantidad_de_Retiros_Pagados,
             0                             AS Valor_primer_Deposito,
             0                             AS Cantidad_Spin,
             0                             AS CantidadTickets,
             0                             AS Apuestas_Deportivas,
             0                             AS Premios_Deportivos,
             0                             AS Bonos_Deportivas,
             0                             AS Apuestas_Casino,
             0                             AS Premios_Casino,
             0                             AS Premios_Bonos,
             ROUND(SUM(CASE
                           when (pl.estado = 'L' AND
                                 (pl.tipo IN ('TC', 'TL', 'SC', 'SCV', 'SL', 'TV', 'DC', 'DL', 'DV', 'NC', 'NL', 'NV')))
                               then pl.valor
                           when (pl.estado = 'E' AND
                                 (pl.tipo IN ('TC', 'TL', 'SC', 'SCV', 'SL', 'TV', 'DC', 'DL', 'DV', 'NC', 'NL', 'NV')))
                               then -pl.valor
                           else 0 end), 2) AS Bonos_Casino
      FROM bono_log pl
               JOIN usuario u ON pl.usuario_id = u.usuario_id
               JOIN registro r ON u.usuario_id = r.usuario_id
               JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
               JOIN pais pais ON u.pais_id = pais.pais_id
               JOIN mandante m ON u.mandante = m.mandante
      WHERE 1 = 1
        AND up.perfil_id = 'USUONLINE'
        AND u.mandante IN ($Mandante)
        AND u.pais_id in ($Country)
        AND r.afiliador_id in ($AffiliateId)
        AND pl.fecha_crea >= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 00:00:00')
        AND pl.fecha_crea <= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 23:59:59')
      GROUP BY u.usuario_id
      UNION
      SELECT u.usuario_id             AS ID_Usuario,
             ROUND(SUM(urr.valor), 2) AS Valor_de_Depositos,
             COUNT(urr.recarga_id)    AS Cantidad_de_Depositos,
             0                        AS Valor_de_Retiros_Pagados,
             0                        AS Cantidad_de_Retiros_Pagados,
             0                        AS Valor_primer_Deposito,
             0                        AS Cantidad_Spin,
             0                        AS CantidadTickets,
             0                        AS Apuestas_Deportivas,
             0                        AS Premios_Deportivos,
             0                        AS Bonos_Deportivas,
             0                        AS Apuestas_Casino,
             0                        AS Premios_Casino,
             0                        AS Premios_Bonos,
             0                        AS Bonos_Casino
      FROM usuario_recarga urr
               JOIN usuario u ON urr.usuario_id = u.usuario_id
               JOIN registro r ON u.usuario_id = r.usuario_id
               JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
               JOIN pais pais ON u.pais_id = pais.pais_id
               JOIN mandante m ON u.mandante = m.mandante
      WHERE 1 = 1
        AND up.perfil_id = 'USUONLINE'
        AND urr.estado = 'A'
        AND u.mandante IN ($Mandante)
        AND u.pais_id in ($Country)
        AND r.afiliador_id in ($AffiliateId)
        AND urr.fecha_crea >= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 00:00:00')
        AND urr.fecha_crea <= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 23:59:59')
      GROUP BY u.usuario_id
      UNION
      SELECT u.usuario_id             AS ID_Usuario,
             0                        AS Valor_de_Depositos,
             0                        AS Cantidad_de_Depositos,
             ROUND(SUM(urr.valor), 2) AS Valor_de_Retiros_Pagados,
             COUNT(urr.cuenta_id)     AS Cantidad_de_Retiros_Pagados,
             0                        AS Valor_primer_Deposito,
             0                        AS Cantidad_Spin,
             0                        AS CantidadTickets,
             0                        AS Apuestas_Deportivas,
             0                        AS Premios_Deportivos,
             0                        AS Bonos_Deportivas,
             0                        AS Apuestas_Casino,
             0                        AS Premios_Casino,
             0                        AS Premios_Bonos,
             0                        AS Bonos_Casino
      FROM cuenta_cobro urr
               JOIN usuario u ON urr.usuario_id = u.usuario_id
               JOIN registro r ON u.usuario_id = r.usuario_id
               JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
               JOIN pais pais ON u.pais_id = pais.pais_id
               JOIN mandante m ON u.mandante = m.mandante
      WHERE 1 = 1
        AND up.perfil_id = 'USUONLINE'
        AND urr.estado = 'I'
        AND u.mandante IN ($Mandante)
        AND u.pais_id in ($Country)
        AND r.afiliador_id in ($AffiliateId)
        AND urr.fecha_pago >= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 00:00:00')
        AND urr.fecha_pago <= CONCAT(DATE(NOW() - INTERVAL 1 DAY), ' 23:59:59')
      GROUP BY u.usuario_id) x

         JOIN usuario u ON u.usuario_id = x.ID_Usuario
         JOIN usuario_mandante um on (u.usuario_id = um.usuario_mandante)
         JOIN data_completa2 dc2 ON um.usumandante_id = dc2.usuario_id

         JOIN registro r ON r.usuario_id = u.usuario_id
         LEFT JOIN usuario u2 ON r.afiliador_id = u2.usuario_id
         LEFT JOIN usuario_link ul ON r.link_id = ul.usulink_id
         JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
         JOIN pais pais ON u.pais_id = pais.pais_id
         JOIN mandante m ON u.mandante = m.mandante

GROUP BY r.link_id
";
    if ($_ENV['debug']) {
        print_r($sql);
    }
    $BonoInterno = new BonoInterno();
    $Resultado = $BonoInterno->execQuery($Transaction, $sql);

    $array = [];

    foreach ($Resultado as $index => $value) {
        $item = new stdClass();

        $item->Mandante = $value->{"m.Partner"};
        $item->Country = $value->{"pais.Pais"};
        $item->RegisterDate = $value->{".Fecha_Registro"};
        $item->FirstDepositDate = $value->{".Fecha_Primer_Deposito"};
        $item->Link = $value->{"r.Link"};
        $item->UserLink = $value->{"ul.Usuario_Link"};
        $item->CreationDateLink = $value->{"ul.Fecha_Creacion_Link"};
        $item->AfiliatorId = $value->{"r.ID_Afiliador"};
        $item->AfiliatorName = $value->{"u2.Nombre_Afiliador"};
        $item->ValueOfFirstDeposit = $value->{".Valor_primer_Deposito"};
        $item->DepositAmount = $value->{".Cantidad_de_Depositos"};
        $item->DepositValue = $value->{"dc2.Valor_de_Depositos"};
        $item->AmountOfWithdrawalsPaid = $value->{".Cantidad_de_Retiros_Pagados"};
        $item->ValueOfWithdrawalsPaid = $value->{".Valor_de_Retiros_Pagados"};
        $item->CantSpin = $value->{".Cantidad_Spin"};
        $item->CasinoBetValue = $value->{".Valor_Apostado_Casino"};
        $item->ValuePrizeCasino = $value->{".Valor_Premios_Casino"};
        $item->ValuePrizeBonus = $value->{".Valor_Premios_Bonos"};
        $item->ValueCasinoBonus = $value->{".Valor_Bonos_Casino"};
        $item->GGRCasino = $value->{".GGR_Casino"};
        $item->NumberTickets = $value->{".Cantidad_Ticket"};
        $item->ValueBetSports = $value->{".Valor_Apuestas_Deporte"};
        $item->ValueSportsAward = $value->{".Valor_Premios_Deporte"};
        $item->ValueBonusSport = $value->{".Valor_Bonos_Deporte"};
        $item->GGRSport = $value->{".GGR_Deportiva"};

        array_push($array, $item);
    }


    $response["code"] = 0;
    $response["msg"] = "";
    $response["count"] = count($array);
    $response["data"] = $array;
} else {
    $response["code"] = 0;
    $response["msg"] = '';
    $response["count"] = 0;
    $response["data"] = [];
}


