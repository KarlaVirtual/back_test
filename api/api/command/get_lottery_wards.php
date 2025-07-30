<?php

    use Backend\dto\Pais;
    use Backend\dto\SorteoDetalle;
    use Backend\dto\SorteoInterno;
    use Backend\dto\UsuarioMandante;
    use Backend\dto\UsuarioSorteo;

    /**
     * Obtiene el tipo de premio basado en el tipo proporcionado.
     *
     * @param string $type El tipo de premio.
     * @return string El tipo de premio en formato legible o una cadena vacía si no se encuentra.
     */
    function getTypeWards($type) {
        $types = ['RANKAWARDMAT' => 'Fisico', 'BONO' => 'Bono', 'RANKAWARD' => 'Efectivo'];
        return isset($types[$type]) ? $types[$type] : '';
    }

    /**
     * Obtiene los premios de la lotería basados en los parámetros proporcionados.
     *
     * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
     * @param int $json->params->Id ID de la lotería.
     * @param int $json->params->site_id ID del sitio.
     * @param string $json->params->StateType Tipo de estado.
     * @param string $json->params->Country País.
     * @param string $json->params->State Estado.
     * @param object $json->session->usuario_id ID del usuario en la sesión.
     * @return array Respuesta con los premios de la lotería.
     */

    /*Recepción de parámetros*/
    $params = $json->params;
    $cupones =array();
    $Id = $params->Id;
    $site_id = $params->site_id;
    $StateType = $params->StateType;
    $Country = $params->Country;
    $State = in_array($params->State, ['A', 'I']) ? $params->State : '';

    $Moneda = '';

    try {
        $UsuarioMandante = new UsuarioMandante($json->session->usuario_id);
    } catch (Exception $ex) { }

    $Pais = new Pais($UsuarioMandante->paisId ?: '', strtoupper($Country));
    $Moneda = $Pais->moneda;

    $rules = [];

    // if(!empty($Country)) {
    //     $Pais = new Pais();
    //     array_push($rules, ['field' => 'sorteo_detalle.tipo', 'data' => 'CONDPAISUSER', 'op' => 'eq']);
    //     array_push($rules, ['field' => 'sorteo_detalle.valor', 'data' => $Pais->paisId, 'op' => 'eq']);
    // }

    array_push($rules, ['field' => 'sorteo_interno.sorteo_id', 'data' => $Id, 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_interno.pegatinas', 'data' => 1, 'op' => 'eq']);
    array_push($rules, array('field' => 'sorteo_interno.mandante', 'data' => $site_id, 'op' => 'eq'));

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $SorteoInterno = new SorteoInterno();

    // Realiza una consulta para obtener los sorteos personalizados
    $queryCampaign = (string)$SorteoInterno->getSorteosCustom('sorteo_interno.*', 'sorteo_interno.sorteo_id', 'asc', 0, 10000, $filter, true, 'sorteo_interno.sorteo_id');

    $queryCampaign = json_decode($queryCampaign, true);

    $rules = [];

    $campaingIds = array_map(function($item) {
        return $item['sorteo_interno.sorteo_id'];
    }, $queryCampaign['data']);

    if(oldCount($queryCampaign['data']) > 0) {

        // CAMPAING WARDS //

        $rules = [];

        array_push($rules, ['field' => 'sorteo_interno.mandante', 'data' => $site_id, 'op' => 'eq']);
        array_push($rules, ['field' => 'sorteo_detalle.tipo', 'data' => '"RANKAWARDMAT","BONO","RANKAWARD"', 'op' => 'in']);
        array_push($rules, ['field' => 'sorteo_interno.sorteo_id', 'data' => $Id, 'op' => 'eq']);
        if($Id == 389){
            array_push($rules, ['field' => 'sorteo_detalle.fecha_sorteo', 'data' => '2023-09-30 23:59:59', 'op' => 'ge']);

        }
        $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $SorteoDetalle = new SorteoDetalle();

        // Realiza una consulta para obtener los detalles del sorteo
        $queryWards = (string)$SorteoDetalle->getSorteoDetallesCustom('sorteo_detalle.*', 'sorteo_interno.sorteo_id', 'asc', 0, 1000, $filter, true);

        $queryWards = json_decode($queryWards, true);

        //  STICKERS INFO //

        $rules = [];

        array_push($rules, ['field' => 'sorteo_interno.mandante', 'data' => $site_id, 'op' => 'eq']);
        array_push($rules, ['field' => 'sorteo_interno.estado', 'data' => $State, 'op' => 'eq']);
        array_push($rules, ['field' => 'sorteo_detalle.moneda', 'data' => $Moneda, 'op' => 'eq']);
        array_push($rules, ['field' => 'sorteo_interno.sorteo_id', 'data' => $Id, 'op' => 'eq']);
        array_push($rules, ['field' => 'sorteo_detalle.tipo', 'data' => '"MINBETPRICECASINO", "MINBETPRICE2CASINO", "MINBETPRICEDEPOSIT", "MINBETPRICE2DEPOSIT", "MINBETPRICESPORTSBOOK", "MINBETPRICE2SPORTSBOOK"', 'op' => 'in']);


        $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $SorteoDetalle = new SorteoDetalle();

        // Obtiene información de los stickers del sorteo
        $queryInfo = (string)$SorteoDetalle->getSorteoDetallesCustom('sorteo_detalle.*', 'sorteo_interno.sorteo_id', 'asc', 0, 1000, $filter, true);

        $queryInfo = json_decode($queryInfo, true);
    }

    $allCampaign = [];

    foreach ($queryCampaign['data'] as $key => $value) {
        /*Asignación de valores a objetos de respuesta*/
        $data = [];

        $data['id'] = $value['sorteo_interno.sorteo_id'];
        $data['name'] = $value['sorteo_interno.nombre'];
        $data['description'] = $value['sorteo_interno.descripcion'];
        $data['startDate'] = strtotime($value['sorteo_interno.fecha_inicio']) * 1000;
        $data['endDate'] = strtotime($value['sorteo_interno.fecha_fin']) * 1000;
        $data['state'] = $value['sorteo_interno.estado'];
        $data['rules'] = $value['sorteo_interno.reglas'];
        $data['minAmountDetails'] = [
            'casinoMin' => 0,
            'casinoMax' => 0,
            'sportbookMin' => 0,
            'sportbookMax' => 0,
            'depositMin' => 0,
            'depositMax' => 0,
        ];
        $data['lotteryWards'] = [];

        $lotteryJsonTemp = $value['sorteo_interno.json_temp'];
        $awardsInfo = json_decode($lotteryJsonTemp);
        $awardsInfo = $awardsInfo->RanksPrize[0]->Amount;

        $wards = array_filter($queryWards['data'], function($item) use ($value) {
            if($item['sorteo_detalle.sorteo_id'] == $value['sorteo_interno.sorteo_id']) return $item;
        }) ?: [];


        $rules = [];

        // Agrega reglas para filtrar a los ganadores
        array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'R', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $Id, 'op' => 'eq']);

        $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']); // Convierte las reglas a formato JSON
        $UsuarioSorteo = new UsuarioSorteo(); // Crea una nueva instancia de UsuarioSorteo
        $queryUserWin = (string)$UsuarioSorteo->getUsuarioSorteosCustom('usuario_sorteo.*, usuario_mandante.usuario_mandante, usuario_mandante.nombres', 'usuario_sorteo.ususorteo_id', 'asc', 0, 10000, $filter, true);

        $queryUserWin = json_decode($queryUserWin, true); // Decodifica la respuesta JSON


        /*Envío de información inherente a los premios del sorteo*/
        foreach($wards as $key => $wardsValue) {
            $wardsData = [];

            $wardsData = [];
            $wardsData['position'] = $wardsValue['sorteo_detalle.valor'];
            $wardsData['detailId'] = $wardsValue['sorteo_detalle.sorteodetalle_id'];
            $wardsData['description'] = $wardsValue['sorteo_detalle.descripcion'];
            $wardsData['value'] = str_replace(' ', '', str_replace('	', '', $wardsValue['sorteo_detalle.valor3']));
            $wardsData['type'] = getTypeWards($wardsValue['sorteo_detalle.tipo']);
            $wardsData['image'] = str_contains($wardsValue['sorteo_detalle.imagen_url'], 'http') ? $wardsValue['sorteo_detalle.imagen_url'] : '';
            $wardsData['date'] = strtotime($wardsValue['sorteo_detalle.fecha_sorteo']) * 1000;
            $wardsData['state'] = $wardsValue['sorteo_detalle.estado'];


            if($value['sorteo_interno.sorteo_id'] == '320' && $wardsValue['sorteo_detalle.tipo'] !='RANKAWARDMAT'){
                $wardsData['image'] = 'https://images.virtualsoft.tech/m/msjT1686155980.png';
                $wardsData['type'] = 'Fisico';

            }

            // Verifica si el sorteo interno es 334, 337 o 340 y si el tipo de sorteo no es 'RANKAWARDMAT'
            if($value['sorteo_interno.sorteo_id'] == '334' && $wardsValue['sorteo_detalle.tipo'] !='RANKAWARDMAT'){
                $wardsData['image'] = 'https://images.virtualsoft.tech/m/msjT1686155980.png';
                $wardsData['type'] = 'Fisico';  // Define el tipo de premio como 'Fisico'

            }
            if($value['sorteo_interno.sorteo_id'] == '337' && $wardsValue['sorteo_detalle.tipo'] !='RANKAWARDMAT'){
                $wardsData['image'] = 'https://images.virtualsoft.tech/m/msjT1686155980.png';
                $wardsData['type'] = 'Fisico';
            }
            if($value['sorteo_interno.sorteo_id'] == '340' && $wardsValue['sorteo_detalle.tipo'] !='RANKAWARDMAT'){
                $wardsData['image'] = 'https://images.virtualsoft.tech/m/msjT1686155980.png';
                $wardsData['type'] = 'Fisico';
            }
            $rules = [];

            // Agrega condiciones a las reglas para filtrar los usuarios que pueden ganar
            if($wardsValue['sorteo_detalle.permite_ganador'] == 1) array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => '\'A\', \'R\'', 'op' => 'in']);
            else array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'A', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $wardsValue['sorteo_detalle.sorteo_id'], 'op' => 'eq']);

            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $UsuarioSorteo = new UsuarioSorteo();

            // Verifica si hay información de premios
            if($awardsInfo != null){

                //Solicitando total de jugadores y cupones que participaron por el premio
                $currentAwardInfo = array_filter($awardsInfo, function ($award) use($wardsData) {
                    // Filtra para encontrar la información del premio actual
                    if ($award->detailId == $wardsData['detailId']) return true;
                    else return false;
                });
                $currentAwardInfo = array_filter($currentAwardInfo);
                $currentAwardInfo = array_values($currentAwardInfo);

                // Asigna el total de cupones y jugadores al $wardsData
                $wardsData['totalCoupons'] = (int) $currentAwardInfo[0]->engagedCoupons ?? '0';
                $wardsData['totalPlayers'] = (int) $currentAwardInfo[0]->engagedUsers ?? '0';
            }else{
                // Valores por defecto si no hay información de premios
                $wardsData['totalCoupons'] = 4231274;
                $wardsData['totalPlayers'] = 43234;
            }

            $wardsData['userWin'] = (object)[];

            // Verifica si el estado del sorteo es 'R'
            if($wardsValue['sorteo_detalle.estado'] === 'R') {


                /**Seleccionando el cupón que obtuvo el premio*/
                $queryUserWindata = array_filter($queryUserWin["data"], function ($coupon) use ($wardsValue) {
                    $pattern = '#,{1}#'; // Define un patrón de búsqueda
                    $awardedCoupons = preg_split($pattern, $coupon["usuario_sorteo.premio_id"]); // Separa los cupones ganadores
                    if (in_array($wardsValue["sorteo_detalle.sorteodetalle_id"], $awardedCoupons)) return $coupon; // Verifica si el cupón está en la lista de ganadores
                });


                $couponDigits = '0000000';

                foreach($queryUserWindata as $key => $winnerValue) {

                    $winner = [];

                    // Asigna el ID del cupón al ganador
                    $winner['coupon_id'] = $winnerValue['usuario_sorteo.ususorteo_id'];
                    array_push($cupones,$winner['coupon_id']); // Agrega el cupón a la lista
                    $winner['code'] = substr($couponDigits . $winnerValue['usuario_sorteo.ususorteo_id'], -10); // Genera un código de cupón
                    $winner['user_id'] = $winnerValue['usuario_mandante.usuario_mandante'] . '**' . $winnerValue['usuario_mandante.nombres']; // Crea el identificador del usuario
                    $wardsData['userWin'] = $winner; // Asigna el ganador al $wardsData
                }
            }



            array_push($data['lotteryWards'], $wardsData);
        }
        $detailStickers = array_filter($queryInfo['data'], function($item) use ($value) {
            if($value['sorteo_interno.sorteo_id'] == $item['sorteo_detalle.sorteo_id']) return $item;
        });

        /*Alamcenamiento configuraciones del sorteo*/
        foreach($detailStickers as $key => $detailValue) {
            switch($detailValue['sorteo_detalle.tipo']) {
                case 'MINBETPRICECASINO':
                    $data['minAmountDetails']['casinoMax'] = floatval($detailValue['sorteo_detalle.valor']);
                    break;
                case 'MINBETPRICE2CASINO':
                    $data['minAmountDetails']['casinoMin'] = floatval($detailValue['sorteo_detalle.valor']);
                    break;
                case 'MINBETPRICESPORTSBOOK':
                    $data['minAmountDetails']['sportbookMax'] = floatval($detailValue['sorteo_detalle.valor']);
                    break;
                case 'MINBETPRICE2SPORTSBOOK':
                    $data['minAmountDetails']['sportbookMin'] = floatval($detailValue['sorteo_detalle.valor']);
                    break;
                case 'MINBETPRICEDEPOSIT':
                    $data['minAmountDetails']['depositMax'] = floatval($detailValue['sorteo_detalle.valor']);
                    break;
                case 'MINBETPRICE2DEPOSIT':
                    $data['minAmountDetails']['depositMin'] = floatval($detailValue['sorteo_detalle.valor']);
                    break;
            }
        }

        array_push($allCampaign, $data);
    }

    /*Generación formato de respuesta*/
    $response = [];
    $response['code'] = 0;
    $response['rid'] = $json->rid;
    $response['cupones'] = $cupones;
    $response['data'] = array(
        'lotteryWards'=>$data['lotteryWards']
    );
?>