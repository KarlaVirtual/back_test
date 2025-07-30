<?php

    use Backend\dto\UsuarioMandante;
    use Backend\dto\PreUsuarioSorteo;
    use Backend\dto\SorteoDetalle;

    // Obtener parámetros del JSON y definir el ID de la lotería
    $params = $json->params;
    $lottery_id = $params->lottery_id ?: 0;
    if(isset($json->session->usuario)) {
    // Crear una instancia de UsuarioMandante utilizando el usuario de la sesión actual
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    
    // Inicializar reglas para filtrar los preusuarios del sorteo
    $rules = [];

    array_push($rules, ['field' => 'sorteo_interno.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'preusuario_sorteo.estado', 'data' => '"A", "P"', 'op' => 'in']);
    array_push($rules, ['field' => 'preusuario_sorteo.sorteo_id', 'data' => $lottery_id, 'op' => 'eq']);
    array_push($rules, ['field' => 'preusuario_sorteo.usuario_id', 'data' => $UsuarioMandante->usumandanteId, 'op' => 'eq']);
    array_push($rules, ['field' => 'preusuario_sorteo.mandante', 'data' => $UsuarioMandante->mandante, 'op' => 'eq']);
    
    // Convertir las reglas en un filtro JSON
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
    
    // Obtener los preusuarios del sorteo
    $PreusuarioSorteo = new PreUsuarioSorteo();
    $query = (string)$PreusuarioSorteo->getPreusuarioSorteoCustom('preusuario_sorteo.*', 'preusuario_sorteo.preususorteo_id', 'asc', 0, 100, $filter, true);
    
    // Decodificar el resultado de la consulta
    $query = json_decode($query);

    // Inicializar el arreglo para los stickers
    $stickers = [
        'casino' => [],
        'sportbook' => [],
        'deposit' => []
    ];
    
    // Procesar cada resultado de preusuario_sorteo
    foreach($query->data as $key => $value) {
        $data = [];
        switch($value->{'preusuario_sorteo.tipo'}) {
            case 1: // Tipo 'casino'
                $data['sticker_id'] = $value->{'preusuario_sorteo.preususorteo_id'};
                $data['percent'] = $value->{'preusuario_sorteo.estado'} === 'P' ? ($value->{'preusuario_sorteo.apostado'} * 100) / $value->{'preusuario_sorteo.valor_base'} : 100;
                array_push($stickers['casino'], $data);
                break;
            case 2: // Tipo 'sportbook'
                $data['sticker_id'] = $value->{'preusuario_sorteo.preususorteo_id'};
                $data['percent'] = $value->{'preusuario_sorteo.estado'} === 'P' ? ($value->{'preusuario_sorteo.apostado'} * 100) / $value->{'preusuario_sorteo.valor_base'} : 100;
                array_push($stickers['sportbook'], $data);
                break;
            case 3: // Tipo 'deposit'
                $data['sticker_id'] = $value->{'preusuario_sorteo.preususorteo_id'};
                $data['percent'] = $value->{'preusuario_sorteo.estado'} === 'P' ? ($value->{'preusuario_sorteo.apostado'} * 100) / $value->{'preusuario_sorteo.valor_base'} : 100;
                array_push($stickers['deposit'], $data);
                break;
        }
    }

    // Crear una nueva instancia de SorteoDetalle
    $SorteoDetalle = new SorteoDetalle();

    // Inicializar reglas para la consulta de detalles del sorteo
    $rules = [];
    array_push($rules, ['field' => 'sorteo_interno.sorteo_id', 'data' => $lottery_id, 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_interno.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_detalle.tipo', 'data' => '"NUMBERCASINOSTICKERS", "NUMBERSPORTSBOOKSTICKERS", "NUMBERDEPOSITSTICKERS"', 'op' => 'in']);

    // Convertir las reglas en un filtro JSON
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
    $query = (string)$SorteoDetalle->getSorteoDetallesCustom('sorteo_detalle.*', 'sorteo_detalle.sorteodetalle_id', 'asc', 0, 100, $filter, true);

    // Decodificar el resultado de la consulta
    $query = json_decode($query);

    // Procesar cada resultado de sorteo_detalle
    foreach($query->data as $key => $value) {
        switch($value->{'sorteo_detalle.tipo'}) {
            case 'NUMBERCASINOSTICKERS':
                // Ajustar stickers de casino según el valor recibido
                if(oldCount($stickers['casino']) > $value->{'sorteo_detalle.valor'}) $stickers['casino'] = array_slice($stickers['casino'], (oldCount($stickers['casino']) - $value->{'sorteo_detalle.valor'}) - 1, $value->{'sorteo_detalle.valor'});
                if(oldCount($stickers['casino']) < $value->{'sorteo_detalle.valor'}) {
                    $stickers['casino'] = array_merge($stickers['casino'], array_fill(0, $value->{'sorteo_detalle.valor'} - oldCount($stickers['casino']), ['sticker_id' => 0, 'percent' => 0]));
                }
                break;
            case 'NUMBERSPORTSBOOKSTICKERS':
                // Ajustar stickers de sportbook según el valor recibido
                if(oldCount($stickers['sportbook']) < $value->{'sorteo_detalle.valor'}) {
                    $stickers['sportbook'] = array_merge($stickers['sportbook'], array_fill(0, $value->{'sorteo_detalle.valor'} - oldCount($stickers['sportbook']), ['sticker_id' => 0, 'percent' => 0]));
                }
                break;
            case 'NUMBERDEPOSITSTICKERS':
                // Ajustar stickers de deposit según el valor recibido
                if(oldCount($stickers['deposit']) < $value->{'sorteo_detalle.valor'}) {
                    $stickers['deposit'] = array_merge($stickers['deposit'], array_fill(0, $value->{'sorteo_detalle.valor'} - oldCount($stickers['deposit']), ['sticker_id' => 0, 'percent' => 0]));
                }
                break;
        }
    }

    } else {
    // Crear una nueva instancia de SorteoDetalle para obtener los detalles finales
    $SorteoDetalle = new SorteoDetalle();

    // Inicializar un nuevo conjunto de reglas para la consulta de detalles del sorteo
    $rules = [
        'casino' => [],
        'sportbook' => [],
        'deposit' => []
    ];

    array_push($rules, ['field' => 'sorteo_interno.sorteo_id', 'data' => $lottery_id, 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_interno.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'sorteo_detalle.tipo', 'data' => '"NUMBERCASINOSTICKERS", "NUMBERSPORTSBOOKSTICKERS", "NUMBERDEPOSITSTICKERS"', 'op' => 'in']);

    // Convertir las reglas en un filtro JSON
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
    $query = (string)$SorteoDetalle->getSorteoDetallesCustom('sorteo_detalle.*', 'sorteo_detalle.sorteodetalle_id', 'asc', 0, 100, $filter, true);

    // Decodificar el resultado de la consulta
    $query = json_decode($query);

    // Inicializar el arreglo para los stickers
    $stickers = [];

        // Procesar cada resultado de sorteo_detalle para establecer el número de stickers
        foreach($query->data as $key => $value) {
            switch($value->{'sorteo_detalle.tipo'}) {
                case 'NUMBERCASINOSTICKERS':
                    $stickers['casino'] = array_fill(0, $value->{'sorteo_detalle.valor'}, ['sticker_id' => 0, 'percent' => 0]);
                    break;
                case 'NUMBERSPORTSBOOKSTICKERS':
                    $stickers['sportbook'] = array_fill(0, $value->{'sorteo_detalle.valor'}, ['sticker_id' => 0, 'percent' => 0]);
                    break;
                case 'NUMBERDEPOSITSTICKERS':
                    $stickers['deposit'] = array_fill(0, $value->{'sorteo_detalle.valor'}, ['sticker_id' => 0, 'percent' => 0]);
                    break;
            }
        }
    }
    
    $response = [];
    $response['code'] = 0;
    $response['rid'] = $json->rid;
    $response['data'] = $stickers;
?>  