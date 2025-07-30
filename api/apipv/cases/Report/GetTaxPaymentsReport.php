<?php

/**
 * Reporte "Impuestos sobre ganancias pagadas - Doradobet Guatemala"
 * Es desarrollado solo para el partner Doradobet - Guatemala
 * 
 * @author juan.alvarez@virtualsoft.tech
 * @since 2025-30-01
 */

use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\mysql\BonoInternoMySqlDAO;

try {
    
    $limit = ($_REQUEST["count"] != "") ? $_REQUEST["count"] : 1000;
    $start = ($_REQUEST["start"] != "") ? $_REQUEST["start"] : 0;
    $dateFrom = isset($_REQUEST['dateFrom']) ? $_REQUEST['dateFrom'] : null;
    $dateTo = isset($_REQUEST['dateTo']) ? $_REQUEST['dateTo'] : null;
    $country = isset($_REQUEST['CountrySelect']) ? $_REQUEST['CountrySelect'] : null;
    $mandante = isset($_REQUEST['Partner']) ? $_REQUEST['Partner'] : null;

    $response = [];    
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['pos'] = $start;
    $response['total_count'] = 0;
    $response['data'] = [];

    if ($mandante == 0 && $country == 94) {

        $filters = [];
        
        $BonointernoMySqlDAO = new BonoInternoMySqlDAO();
        $transaccion = $BonointernoMySqlDAO->getTransaction();
        
        $date1 = date('Y-m-d 00:00:00', strtotime($dateFrom));
        $date2 = date('Y-m-d 23:59:59', strtotime($dateTo));
        
        $mandanteDetalle = new MandanteDetalle();
        
        $rules = array();
        
        $clasificador = new Clasificador('','PRIZEPAYMENTTAX');

        array_push($rules, array("field" => "mandante_detalle.mandante", "data" => $mandante, "op" => "eq"));
        array_push($rules, array("field" => "mandante_detalle.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "mandante_detalle.pais_id", "data" => $country, "op" => "eq"));
        array_push($rules, array("field" => "mandante_detalle.tipo", "data" => $clasificador->getClasificadorId(), "op" => "eq"));
        array_push($rules, array("field" => "clasificador.abreviado", "data" => "PRIZEPAYMENTTAX", "op" => "eq"));
        
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);
        
        $mandanteDetalle = $mandanteDetalle->getMandanteDetallesCustom(" mandante_detalle.valor", "mandante_detalle.manddetalle_id", "asc", 0, 1, $json2, true);
        
        $mandanteDetalle = json_decode((string) $mandanteDetalle);
        
        $tax = $mandanteDetalle->data[0]->{'mandante_detalle.valor'};
        
        if (!is_numeric($tax)) throw new Exception("El impuesto debe de ser un numero", 400);
        
        $tax = (int) $tax / 100;
        
        $baseQuery = "WITH 
            Depositos AS (
                SELECT 
                    DATE(urr.fecha_crea) AS fecha,
                    COUNT(*) AS cantidad_depositos,
                    SUM(urr.valor) AS valor_depositado
                FROM usuario_recarga urr
                INNER JOIN usuario us ON us.usuario_id = urr.usuario_id 
                WHERE urr.estado = 'A' AND us.pais_id = $country AND us.mandante = $mandante
                AND urr.fecha_crea BETWEEN '$date1' AND '$date2'
                GROUP BY DATE(urr.fecha_crea)
            ),
            DepositosDevueltos AS (
                SELECT 
                    DATE(ur.fecha_crea) AS fecha,
                    SUM(tp.valor) AS valor_depositos_devuelto
                FROM usuario_recarga ur
                INNER JOIN usuario us ON us.usuario_id = ur.usuario_id
                INNER JOIN transaccion_producto tp ON tp.final_id = ur.recarga_id
                WHERE tp.estado_producto = 'R' AND us.pais_id = $country AND us.mandante = $mandante
                AND ur.fecha_crea BETWEEN '$date1' AND '$date2'
                GROUP BY DATE(ur.fecha_crea)
            ),
            RetirosPagados AS (
                SELECT 
                    DATE(urr.fecha_crea) AS fecha,
                    SUM(urr.cantidad) AS cantidad_retiros_pagados,
                    SUM(urr.valor) AS valor_retiros_pagados
                FROM usuario_retiro_resumen urr
                INNER JOIN usuario us ON us.usuario_id = urr.usuario_id
                INNER JOIN usuario_perfil up ON up.usuario_id = urr.usuario_id
                WHERE urr.estado = 'I' AND up.perfil_id='USUONLINE' AND us.pais_id = $country AND us.mandante = $mandante
                AND urr.puntoventa_id = 0 AND urr.fecha_crea BETWEEN '$date1' AND '$date2'
                GROUP BY DATE(urr.fecha_crea)
            )
            SELECT 
                fechas.fecha,
                COALESCE(d.cantidad_depositos, 0) AS cantidad_depositos,
                COALESCE(d.valor_depositado, 0) AS valor_depositado,
                COALESCE(dd.valor_depositos_devuelto, 0) AS valor_depositos_devuelto,
                COALESCE(rp.cantidad_retiros_pagados, 0) AS cantidad_retiros_pagados,
                COALESCE(rp.valor_retiros_pagados, 0) AS valor_retiros_pagados,
                (COALESCE(d.valor_depositado, 0) - COALESCE(dd.valor_depositos_devuelto, 0) 
                - COALESCE(rp.valor_retiros_pagados, 0)) AS base_imponible,
                (COALESCE(d.valor_depositado, 0) 
                - COALESCE(dd.valor_depositos_devuelto, 0) 
                - COALESCE(rp.valor_retiros_pagados, 0)) * $tax AS impuesto
            FROM (
                SELECT fecha FROM Depositos
                UNION 
                SELECT fecha FROM DepositosDevueltos
                UNION 
                SELECT fecha FROM RetirosPagados
            ) AS fechas
            LEFT JOIN Depositos d ON fechas.fecha = d.fecha
            LEFT JOIN DepositosDevueltos dd ON fechas.fecha = dd.fecha
            LEFT JOIN RetirosPagados rp ON fechas.fecha = rp.fecha
        ";
    
        $query = "$baseQuery ORDER BY fechas.fecha DESC LIMIT $start, $limit;";
    
        $count = "SELECT COUNT(*) as count FROM ($baseQuery) AS subquery;";
    
    
        $Bonointerno = new BonoInterno();
    
        $payments = $Bonointerno->execQuery($transaccion,$query);
        $count = $Bonointerno->execQuery($transaccion,$count);
        
        $responseData = [];
        foreach ($payments as $payment) {
            $data = [
                'Date' => $payment->{'fechas.fecha'},
                'NumberDeposist' => $payment->{'.cantidad_depositos'},
                'ValueDeposist' => $payment->{'.valor_depositado'},
                'ValueReturnDeposits' => $payment->{'.valor_depositos_devuelto'},
                'NumberWithdrawalPaid' => $payment->{'.cantidad_retiros_pagados'},
                'ValuePaidWithdrawals' => $payment->{'.valor_retiros_pagados'},
                'TaxableBase' => $payment->{'.base_imponible'},
                'Tax' => $payment->{'.impuesto'},
            ];
            array_push($responseData, $data);
        }

        $response['total_count'] = $count[0]->{'.count'};
        $response['data'] = $responseData;
    }
} catch (Exception $e) {
    $response = [];
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = $e->getMessage();
    $response["CodeError"] = $e->getCode();
}