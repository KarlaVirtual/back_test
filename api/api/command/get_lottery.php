<?php

use Backend\dto\Pais;
use Backend\dto\PreUsuarioSorteo;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioSorteo;

/**
 * Obtiene el tipo de premio basado en el tipo proporcionado.
 *
 * @param string $type El tipo de premio.
 * @return string El nombre del premio correspondiente.
 */
function getTypeWards($type)
{
    $types = ['RANKAWARDMAT' => 'Fisico', 'BONO' => 'Bono', 'RANKAWARD' => 'Efectivo'];
    return isset($types[$type]) ? $types[$type] : '';
}

/*Obtenicón de parámetros*/
$params = $json->params;

$site_id = $params->site_id;
$StateType = $params->StateType;
$Country = $params->Country;
$State = in_array($params->State, ['A', 'I']) ? $params->State : '';

$Moneda = '';

try {
    $UsuarioMandante = $UsuarioMandanteSite; // Obtener el usuario mandante del sitio
} catch (Exception $ex) {
}

/*Obtención país del usuario*/
$Pais = new Pais($UsuarioMandante->paisId ?: '', strtoupper($Country));
$Moneda = $Pais->moneda;

$rules = []; // Inicializar reglas

if(!empty($Country)) {
     //$Pais = new Pais();
     array_push($rules, ['field' => 'sorteo_detalle.tipo', 'data' => 'CONDPAISUSER', 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_detalle.valor', 'data' => $Pais->paisId, 'op' => 'eq']);
 }

// Se agregan reglas de filtrado a un array llamado $rules
array_push($rules, ['field' => 'sorteo_interno.estado', 'data' => $State, 'op' => 'eq']);
array_push($rules, ['field' => 'sorteo_interno.pegatinas', 'data' => "1" ,'op' => 'eq']);
array_push($rules, array('field' => 'sorteo_interno.mandante', 'data' => $site_id, 'op' => 'eq'));

// Se convierte el array de reglas a formato JSON para su uso en una consulta
$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$SorteoInterno = new SorteoInterno();

// Se realiza una consulta para obtener sorteos con las reglas y orden especificado
$queryCampaign = (string)$SorteoInterno->getSorteosCustom('sorteo_interno.*', 'sorteo_interno.sorteo_id', 'asc', 0, 10000, $filter, true, 'sorteo_interno.sorteo_id');

$queryCampaign = json_decode($queryCampaign, true);

$rules = [];

// Se extraen los IDs de campaña de los resultados de la consulta
$campaingIds = array_map(function ($item) {
    return $item['sorteo_interno.sorteo_id'];
}, $queryCampaign['data']);

if (oldCount($campaingIds) > 0) {

    // CAMPAING WARDS //

    $rules = [];

    array_push($rules, ['field' => 'sorteo_interno.mandante', 'data' => $site_id, 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_interno.estado', 'data' => $State, 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_detalle.sorteo_id', 'data' => implode(',', $campaingIds), 'op' => 'in']);
    array_push($rules, ['field' => 'sorteo_detalle.tipo', 'data' => '"RANKAWARDMAT","BONO","RANKAWARD","BACKGROUNDURL","IMGPPALURL","CARDBACKGROUNDURL","USERSUBSCRIBE"', 'op' => 'in']);
    //array_push($rules, ['field' => 'sorteo_detalle.moneda', 'data' => $Moneda, 'op' => 'eq']);

    // Convierte las reglas en formato JSON para el filtrado
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    // Crea una instancia de SorteoDetalle para consultar la base de datos
    $SorteoDetalle = new SorteoDetalle();

    // Realiza la consulta a la base de datos para obtener los detalles de sorteo
    $queryWards = (string)$SorteoDetalle->getSorteoDetallesCustom('sorteo_detalle.*', 'sorteo_interno.sorteo_id', 'asc', 0, 10000, $filter, true);

    $queryWards = json_decode($queryWards, true);

    //  STICKERS INFO //
    /*Inicializa un arreglo para almacenar las reglas de filtrado para la información de stickers.*/
    $rules = [];

    array_push($rules, ['field' => 'sorteo_interno.mandante', 'data' => $site_id, 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_interno.estado', 'data' => $State, 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_detalle.moneda', 'data' => $Moneda, 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_detalle.sorteo_id', 'data' => implode(',', $campaingIds), 'op' => 'in']);
    array_push($rules, ['field' => 'sorteo_detalle.tipo', 'data' => '"MINBETPRICECASINO", "MINBETPRICE2CASINO", "MINBETPRICEDEPOSIT", "MINBETPRICE2DEPOSIT", "MINBETPRICESPORTSBOOK", "MINBETPRICE2SPORTSBOOK","USERSUBSCRIBE"', 'op' => 'in']);

    // Convierte las reglas en formato JSON para el filtrado
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    // Crea una nueva instancia de SorteoDetalle para la consulta
    $SorteoDetalle = new SorteoDetalle();

    // Realiza la consulta a la base de datos para obtener la información de stickers
    $queryInfo = (string)$SorteoDetalle->getSorteoDetallesCustom('sorteo_detalle.*', 'sorteo_interno.sorteo_id', 'asc', 0, 10000, $filter, true);

    // Decodifica la respuesta JSON en un arreglo
    $queryInfo = json_decode($queryInfo, true);
}

$allCampaign = [];

foreach ($queryCampaign['data'] as $key => $value) {

    $data = [];

    /*Llenado objetos de respuesta*/
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
    $data['lotteryWards'] = []; // Arreglo para almacenar las unidades de la rifa
    $data['raffleBackground'] = ""; // Fondo de la rifa
    $data['characterBg'] = "https://images.virtualsoft.tech/m/msjT1689632387.png"; // URL de la imagen de fondo
    $data['bgContainer'] = ""; // Contenedor de fondo

    $wards = array_filter($queryWards['data'], function ($item) use ($value) {
        if ($item['sorteo_detalle.sorteo_id'] == $value['sorteo_interno.sorteo_id']) return $item;
    }) ?: [];


    /*Objeto para el llenado de imágenes vinculadas al sorteo*/
    $ImagenFinal = array();
    foreach ($wards as $key2=> $valor){
        $imagens = [];
        switch ($valor["sorteo_detalle.tipo"]) {
            /*Asignación imágenes background*/
            case 'BACKGROUNDURL':
                $data['raffleBackground'] = $valor['sorteo_detalle.valor'];
                break;
            /*Asignación imagen PAL*/
            case 'IMGPPALURL':
                $data['characterBg'] = $valor['sorteo_detalle.valor'];
                break;
            /*Asignación imagen */
            case 'CARDBACKGROUNDURL':
                $data['bgContainer'] = $valor['sorteo_detalle.valor'];
                break;
            case 'USERSUBSCRIBE':
                /*verifica si un usuario está suscrito a un sorteo específico y actualiza el estado de userjoin en consecuencia.*/
                if($valor['sorteo_detalle.valor'] == 1 ) {
                    $data['userjoin'] = false;

                    $rules = [];
                    array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $valor['sorteo_detalle.sorteo_id'], 'op' => 'eq']);
                    array_push($rules, ['field' => 'usuario_sorteo.usuario_id', 'data' => $UsuarioMandante->usumandanteId, 'op' => 'eq']);

                    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                    $UsuarioSorteo2 = new UsuarioSorteo();
                    $allCoupons = (string)$UsuarioSorteo2->getUsuarioSorteosCustom('COUNT(distinct(usuario_sorteo.usuario_id)) countUsers,COUNT((usuario_sorteo.ususorteo_id)) countStickers', 'usuario_sorteo.ususorteo_id', 'asc', 0, 1000000, $filter, true);

                    $allCoupons = json_decode($allCoupons, true);


                    if( $allCoupons['count'][0]['.count'] > 0) {
                        $data['userjoin'] = true;
                    }
                }

                break;
        }

    }

    if (false) {
        foreach ($wards as $key => $wardsValue) {
            /*Este código inicializa un arreglo $wardsData con detalles de un sorteo, incluyendo posición, ID, descripción, valor, tipo, imagen, fecha y estado.*/
            $wardsData = [];

            $wardsData = [];
            $wardsData['position'] = $wardsValue['sorteo_detalle.valor'];
            $wardsData['detailId'] = $wardsValue['sorteo_detalle.sorteodetalle_id'];
            $wardsData['description'] = $wardsValue['sorteo_detalle.descripcion'];
            $wardsData['value'] = str_replace(' ', '', str_replace('	', '', $wardsValue['sorteo_detalle.valor3']));
            $wardsData['type'] = getTypeWards($wardsValue['sorteo_detalle.tipo']);
            $wardsData['image'] = $wardsValue['sorteo_detalle.imagen_url'];
            $wardsData['date'] = strtotime($wardsValue['sorteo_detalle.fecha_sorteo']) * 1000;
            $wardsData['state'] = $wardsValue['sorteo_detalle.estado'];


            /*El código crea un conjunto de reglas de filtrado para consultar la base de datos sobre el estado de los sorteos de usuarios, dependiendo de si se permite
             un ganador o no, y convierte estas reglas a formato JSON.*/
            $rules = [];
            if ($wardsValue['sorteo_detalle.permite_ganador'] == 1) array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => '\'A\', \'R\'', 'op' => 'in']);
            else array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'A', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $wardsValue['sorteo_detalle.sorteo_id'], 'op' => 'eq']);

            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $UsuarioSorteo = new UsuarioSorteo();
            //$allCoupons = (string)$UsuarioSorteo->getUsuarioSorteosCustom('COUNT(distinct(usuario_sorteo.usuario_id)) countUsers,COUNT((usuario_sorteo.ususorteo_id)) countStickers', 'usuario_sorteo.ususorteo_id', 'asc', 0, 1000000, $filter, true);

            //$allCoupons = json_decode($allCoupons, true);
            //$players = array_unique($allCoupons['data'], SORT_REGULAR);

            //$wardsData['totalCoupons'] = intval($allCoupons['data'][0]['.countStickers']);
            //$wardsData['totalPlayers'] = intval($allCoupons['data'][0]['.countUsers']);
            $wardsData['totalCoupons'] = 4231274;
            $wardsData['totalPlayers'] = 43234;

            $wardsData['userWin'] = (object)[];

            if ($wardsValue['sorteo_detalle.estado'] === 'R') {

                /*El código seleccionado crea un conjunto de reglas de filtrado para consultar la base de datos sobre el estado de los sorteos de usuarios,
                convierte estas reglas a formato JSON y realiza la consulta utilizando la clase UsuarioSorteo.*/
                $rules = [];

                array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'R', 'op' => 'eq']);
                array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $wardsValue['sorteo_detalle.sorteo_id'], 'op' => 'eq']);
                array_push($rules, ['field' => 'usuario_sorteo.premio_id', 'data' => $wardsValue['sorteo_detalle.sorteodetalle_id'], 'op' => 'eq']);

                $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                $UsuarioSorteo = new UsuarioSorteo();
                $queryUserWin = (string)$UsuarioSorteo->getUsuarioSorteosCustom('usuario_sorteo.*, usuario_mandante.usuario_mandante, usuario_mandante.nombres', 'usuario_sorteo.ususorteo_id', 'asc', 0, 1000, $filter, true);

                $queryUserWin = json_decode($queryUserWin, true);

                $couponDigits = '0000000';

                foreach ($queryUserWin['data'] as $key => $winnerValue) {
                    /*Inicializa y almacena información sobre el usuario ganador*/
                    $winner = [];

                    $winner['coupon_id'] = $winnerValue['usuario_sorteo.ususorteo_id'];
                    $winner['code'] = substr($couponDigits . $winnerValue['usuario_sorteo.ususorteo_id'], -7);
                    $winner['user_id'] = $winnerValue['usuario_mandante.usuario_mandante'] . '**' . $winnerValue['usuario_mandante.nombres'];
                    $wardsData['userWin'] = $winner;
                }
            }

            array_push($data['lotteryWards'], $wardsData);
        }

    }

    /*Filtra los detalles de los stickers que coinciden con el ID del sorteo actual.*/
    $detailStickers = array_filter($queryInfo['data'], function ($item) use ($value) {
        if ($value['sorteo_interno.sorteo_id'] == $item['sorteo_detalle.sorteo_id']) return $item;
    });
    

    foreach ($detailStickers as $key => $detailValue) {
        /*imprime el valor de detailValue si la variable de entorno debug está activada.*/
        if($_ENV['debug']){
            print_r($detailValue);
        }

        /*Almacena condiciones y configuraciones del sorteo*/
        switch ($detailValue['sorteo_detalle.tipo']) {
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
            case 'BACKGROUNDURL':
                $data['raffleBackground'] = $detailValue['sorteo_detalle.valor'];
                break;
            case 'USERSUBSCRIBE':
                if($detailValue['sorteo_detalle.valor'] == 1 ) {
                    $data['userjoin'] = true;
                }
                break;
            case 'IMGPPALURL':
                $data['characterBg'] = $detailValue['sorteo_detalle.valor'];
                break;
            case 'CARDBACKGROUNDURL':
                $data['bgContainer'] = $detailValue['sorteo_detalle.valor'];
                break;
        }
    }

    array_push($allCampaign, $data);
}
$response = [];
$response['valueStickers'] = 0;
$response['valueCoupons'] = 0;

if($_ENV['debug']){
    print_r($UsuarioMandante);
}

if ($UsuarioMandante != null && $UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == 66) {

    /*Generación filtros para consulta dinámica del usuario*/
    $rules = [];

    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));
    array_push($rules, array("field" => "usuario_sorteo.usuario_id", "data" => $UsuarioMandante->usumandanteId, "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.estado", "data" => 'A', "op" => "eq"));
    array_push($rules, array("field" => "usuario_sorteo.estado", "data" => 'A', "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $SkeepRows = 0;
    $OrderedItem = 1;
    $MaxRows = 1000000000;

    $json = json_encode($filtro);

    $select = "COUNT(*) sorteos,
        sorteo_interno.fecha_inicio,
        sorteo_interno.fecha_fin
        ";

    /*Otención listado dinámico respecto a solicitud del usuario*/
    $UsuarioSorteo = new UsuarioSorteo();
    $data = $UsuarioSorteo->getUsuarioSorteosCustomWithoutPosition($select, "usuario_sorteo.ususorteo_id", "asc", $SkeepRows, $MaxRows, $json, true, '');

    /*El código verifica si $data no es null, lo decodifica desde JSON y asigna el valor de ".sorteos" a $response['valueCoupons'].*/
    if($data != null){
        //Almacenamiento valor de los cupones
        $data = json_decode($data);

        $value = $data->data[0];


        $response['valueCoupons']=$value->{".sorteos"};
    }


    /*Generación solicitud para stickers del usuario*/
    $rules = [];

    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));
    array_push($rules, array("field" => "preusuario_sorteo.usuario_id", "data" => $UsuarioMandante->usumandanteId, "op" => "eq"));
    array_push($rules, array("field" => "sorteo_interno.estado", "data" => 'A', "op" => "eq"));
    array_push($rules, array("field" => "preusuario_sorteo.estado", "data" => 'A', "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $SkeepRows = 0;
    $OrderedItem = 1;
    $MaxRows = 1000000000;

    $json = json_encode($filtro);

    $select = "COUNT(*) sorteos,
        sorteo_interno.fecha_inicio,
        sorteo_interno.fecha_fin
        ";

    /*Obtención stickers del usuario*/
    $PreUsuarioSorteo = new PreUsuarioSorteo();
    $data = $PreUsuarioSorteo->getPreusuarioSorteoCustom($select, "preusuario_sorteo.preususorteo_id", "asc", $SkeepRows, $MaxRows, $json, true, '');

    if($data != null){

        $data = json_decode($data);

        $value = $data->data[0];


        $response['valueStickers']=$value->{".sorteos"};
    }

    /*Generación formato de respuesta*/
    $response['timeGlobal'] = 1696154400000;
    $response['nameWinner'] = '';
    $response['idCouponGlobal'] = '1340';
    $response['awardGlobal'] = '';

    if (date('Y-m-d H:i:s') >='2023-09-30 23:59:59') {
        $response['nameWinner'] = 'Esther   Rodriguez';
        $response['idCouponGlobal'] = '71831742';
        $response['code'] = '71831742';
        $response['coupon_id'] = '71831742';
        $response['awardGlobal'] = 'https://images.virtualsoft.tech/m/msjT1696179906.png';
        $response['user_id'] = '4446529**Esther Rodriguez';

    }

}



$response['code'] = 0;
$response['rid'] = $json->rid;
$response['data'] = $allCampaign;
?>