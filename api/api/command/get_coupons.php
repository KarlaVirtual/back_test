<?php
/**
     * Recurso que obtiene los cupones de un usuario para un sorteo específico.
     *
     * @param int $json->session->usuario ID del usuario que opera la sesión.
     * @param int $params->lottery_id ID del sorteo.
     * @param int $params->limit Límite de registros a devolver.
     * @param int $params->offset Desplazamiento para paginación.
     *
     * @return array Respuesta en formato JSON:
     * - code (int) Código de respuesta.
     * - rid (int) ID de la transacción.
     * - totalCoupons (int) Total de cupones obtenidos.
     * - data (array) Datos de los cupones:
     *   - cupone_id (int) ID del cupón.
     *   - code (string) Código del cupón.
     *   - award (mixed) Premio asociado al cupón (si aplica).
     */

    use Backend\dto\UsuarioMandante;
    use Backend\dto\UsuarioSorteo;

    /**
     * Recurso que obtiene los cupones de un usuario para un sorteo específico.
     *
     * @param int $json->session->usuario ID del usuario que opera la sesión.
     * @param int $params->lottery_id ID del sorteo.
     *
     * @return array Respuesta en formato JSON:
     * - code (int) Código de respuesta.
     * - rid (int) ID de la transacción.
     * - data (array) Datos de los cupones:
     *   - cupone_id (int) ID del cupón.
     *   - code (string) Código del cupón.
     *   - award (mixed) Premio asociado al cupón (si aplica).
     *   - totalCoupons (int) Total de cupones obtenidos.
     */


    /*El código obtiene los cupones de un usuario para un sorteo específico, aplicando filtros y formateando los datos de los cupones en una respuesta JSON.*/
    $params = $json->params;
    $lottery_id = $params->lottery_id ?: 0;
    $limit = $params->limit ?? 100;
    $offset = $params->offset ?? 0;

    $UsuarioMandante = new UsuarioMandante($json->session->usuario);


    $rules = [];
    
    /*El código obtiene los cupones de un usuario para un sorteo específico, aplicando filtros y formateando los datos de los cupones en una respuesta JSON.*/
    array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $lottery_id, 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_sorteo.usuario_id', 'data' => $UsuarioMandante->usumandanteId, 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_sorteo.mandante', 'data' => $UsuarioMandante->mandante, 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => '"A", "R"', 'op' => 'in']);

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $UsuarioSorteo = new UsuarioSorteo();
    $query = (string) $UsuarioSorteo->getUsuarioSorteosCustom('usuario_sorteo.*', 'usuario_sorteo.ususorteo_id', 'asc', $offset, $limit, $filter, true);

    /*El código decodifica una consulta JSON, filtra los cupones de usuario según su estado y formatea los datos en un array.*/
    $query = json_decode($query);
    
    $coupons = [];
    $totalCoupons = ($query->count[0])->{'.count'} ?? 0;
    $code = '0000000';

    foreach ($query->data as $key => $value) {
        if($value->{'usuario_sorteo.estado'} === 'I') continue;

        $data = [];
        $data['cupone_id'] = $value->{'usuario_sorteo.ususorteo_id'};
        $data['code'] = substr($code . $value->{'usuario_sorteo.ususorteo_id'}, -10);
        if(!empty($value->{'usuario_sorteo.premio'}) && $value->{'usuario_sorteo.estado'} === 'R') $data['award'] = json_decode($value->{'usuario_sorteo.premio'});

        array_push($coupons, $data);
    }

    //Formatea la respuesta
    $response = [];
    $response['code'] = 0;
    $response['rid'] = $json->rid;
    $response['data'] = $coupons;
    $response['totalCoupons'] = $totalCoupons;
?>