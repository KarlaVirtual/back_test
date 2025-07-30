<?php

use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;


/** 
 * Envía la colección de bonos activos de un usuario.
 * @return object $response
 *   - code (int) Código de respuesta
 *  - rid (string) ID de la solicitud
 *  - data (array) Colección de bonos activos
 */
if ($json->session->logueado) {

/**
 * Crea una instancia de UsuarioMandante y Usuario, y establece reglas para filtrar bonos de usuario.
 * 
 * Se inicializan las variables y se configura un filtro en formato JSON 
 * para obtener los bonos del usuario de manera personalizada. 
 * 
 * Se comentan las líneas que recuperan los bonos y se procesan 
 * los datos obtenidos para formatear la información en un arreglo.
 */

    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $bono_headerf = array();

$MaxRows = 1; // Número máximo de filas a recuperar
$OrderedItem = 1; // Item a ordenar
$SkeepRows = 0; // Filas a omitir
$rules = []; // Reglas de filtrado
array_push($rules, array("field" => "bono_interno.tipo", "data" => "5,6,2,3", "op" => "in"));
array_push($rules, array("field" => "usuario_bono.estado", "data" => 'A', "op" => "eq"));
array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND"); // Filtro de reglas
$json2 = json_encode($filtro); // Conversión de filtro a formato JSON

    $UsuarioBono = new UsuarioBono();

    /*$bonos = $UsuarioBono->getUsuarioBonosCustom("   usuario.moneda,
                     bono_interno.nombre,
                     (bono_detalle.valor-usuario_bono.valor) valor,
                     bono_detalle.valor valor_total,
                     usuario_bono.valor valor_actual,
                     usuario_bono.usuario_id,usuario_bono.usubono_id,
                          CASE bono_detalle2.tipo WHEN 'EXPDIA' THEN  DATE_FORMAT((DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s'), INTERVAL bono_detalle2.valor DAY)),'%Y-%m-%d %H:%i:%s') ELSE DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s') END AS fecha_expiracion  ", "usuario_bono.usubono_id", "desc", $SkeepRows, $MaxRows, $json2, true, '', 'MINAMOUNT', "'EXPDIA','EXPFECHA'");
    $bonos = json_decode($bonos);


    foreach ($bonos->data as $key => $value) {

        $bono_header = array();

        $bono_header["id"] = $value->{'usuario_bono.usubono_id'};
        $bono_header["name"] = $value->{'bono_interno.nombre'};
        $bono_header["type"] = '';
        $bono_header["porcent"] = (floatval($value->{".valor_actual"}) / floatval($value->{".valor_total"})) * 100;
        $bono_header["progress_text"] = " de Saldo Gratis Casino Gastado";

        $bono_header['valor_bono'] = $value->{".valor_total"} . ' ' . $Usuario->moneda;
        $bono_header['expireDate'] = $value->{".fecha_expiracion"};
        $bono_header["state"] = 'Activo';

        array_push($bono_headerf, ($bono_header));

    }*/


    /**
     * Consulta SQL para obtener los bonos de un usuario en función de condiciones específicas.
     * 
     * Esta consulta se basa en la relación entre las tablas `usuario`, `usuario_bono`, 
     * `bono_interno` y `bono_detalle`, filtrando los registros según el estado del bono, 
     * la fecha de creación y el ID del usuario. También determina la fecha de expiración 
     * en función de ciertos tipos de bonos ('EXPDIA' o 'EXPFECHA').
     */
    $apmin2Sql = "SELECT
                        usuario.moneda,usuario_bono.usubono_id,
                     bono_interno.nombre,
                     usuario_bono.valor,
                     usuario_bono.apostado,
                     usuario_bono.rollower_requerido,
                     usuario_bono.usuario_id,
                      CASE bd.tipo WHEN 'EXPDIA' THEN  DATE_FORMAT((DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s'), INTERVAL bd.valor DAY)),'%Y-%m-%d %H:%i:%s') ELSE DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s') END AS fecha_expiracion

                   FROM usuario_bono
                     INNER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
                     INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)                      
                      INNER JOIN bono_detalle bd ON (bono_interno.bono_id = bd.bono_id AND (bd.tipo='EXPDIA' OR bd.tipo='EXPFECHA' ) )

                     
                   WHERE usuario_bono.estado = 'A' AND usuario_bono.rollower_requerido >0   AND ((bd.tipo = 'EXPDIA' AND DATE_FORMAT(
                                                 (DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s'),
                                                           INTERVAL bd.valor DAY)),
                                                 '%Y-%m-%d %H:%i:%s') >= now()) OR
       (bd.tipo = 'EXPFECHA' AND bd.valor >= now()))
                   AND usuario_bono.usuario_id=" . $Usuario->usuarioId;

    if($Usuario->usuarioId==886){

        /**
         * Consulta SQL específica para el usuario con ID 886, similar a la consulta anterior
         * pero puede tener particularidades que pueden ser relevantes solo para este usuario.
         */
        $apmin2Sql = "SELECT
                        usuario.moneda,usuario_bono.usubono_id,
                     bono_interno.nombre,
                     usuario_bono.valor,
                     usuario_bono.apostado,
                     usuario_bono.rollower_requerido,
                     usuario_bono.usuario_id,
                      CASE bd.tipo WHEN 'EXPDIA' THEN  DATE_FORMAT((DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s'), INTERVAL bd.valor DAY)),'%Y-%m-%d %H:%i:%s') ELSE DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s') END AS fecha_expiracion

                   FROM usuario_bono
                     INNER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
                     INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)                      
                      INNER JOIN bono_detalle bd ON (bono_interno.bono_id = bd.bono_id AND (bd.tipo='EXPDIA' OR bd.tipo='EXPFECHA' ) )

                     
                   WHERE usuario_bono.estado = 'A' AND usuario_bono.rollower_requerido >0   AND ((bd.tipo = 'EXPDIA' AND DATE_FORMAT(
                                                 (DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s'),
                                                           INTERVAL bd.valor DAY)),
                                                 '%Y-%m-%d %H:%i:%s') >= now()) OR
       (bd.tipo = 'EXPFECHA' AND bd.valor >= now()))
                   AND usuario_bono.usuario_id=" . $Usuario->usuarioId;
    }
    $BonoInterno = new \Backend\dto\BonoInterno();
    $apmin2_RS = $BonoInterno->execQuery("", $apmin2Sql);

    /**
     * Itera sobre el conjunto de datos $apmin2_RS para construir un array $bono_header con información de bonos.
     *
     * @param array $apmin2_RS Conjunto de datos de bonos
     * @param object $Usuario Objeto que contiene información del usuario, incluyendo idioma y moneda
     * @return void No retorna ningún valor, modifica el array $bono_headerf directamente
     */
    foreach ($apmin2_RS as $key => $value) {
        $bono_header = array();

        /**
         * Este bloque de código llena el array $bono_header con información sobre un bono activo del usuario.
         *
         * - "id": El identificador único del bono del usuario.
         * - "name": El nombre interno del bono.
         * - "type": Una cadena vacía, presumiblemente para ser llenada con el tipo de bono.
         * - "porcent": El porcentaje del requisito de rollover que se ha cumplido, calculado como (cantidad apostada / rollover requerido) * 100.
         * - "progress_text": Una cadena que indica el progreso hacia la redención del bono.
         * - "valor_bono": El valor del bono junto con la moneda del usuario.
         * - "expireDate": La fecha de expiración del bono.
         * - "state": El estado actual del bono, que se establece en 'Activo'.
         */
        $bono_header["id"] = $value->{'usuario_bono.usubono_id'};
        $bono_header["name"] = $value->{'bono_interno.nombre'};
        $bono_header["type"] = '';
        $bono_header["porcent"] = (floatval($value->{"usuario_bono.apostado"}) / floatval($value->{"usuario_bono.rollower_requerido"})) * 100;
        $bono_header["progress_text"] = " de Rollover para redimidir el bono";

        $bono_header['valor_bono'] = $value->{"usuario_bono.valor"} . ' ' . $Usuario->moneda;
        $bono_header['expireDate'] = $value->{"usuario_bono.fecha_expiracion"};
        $bono_header["state"] = 'Activo';

        /**
         * Este bloque de código maneja la lógica para establecer el estado y el texto de progreso de un bono 
         * basado en el idioma del usuario. Dependiendo del idioma del usuario (EN para inglés, PT para portugués),
         * se asignan diferentes valores a las claves "state" y "progress_text" del array $bono_header.
         *
         * - 'EN': 
         *   - "state" se establece en 'Activo'.
         *   - "progress_text" se establece en " for Rollover to redeem the bonus".
         *
         * - 'PT': 
         *   - "state" se establece en 'Ativo'.
         *   - "progress_text" se establece en " para Rollover para resgatar o bônus".
         */
        switch (strtoupper($Usuario->idioma)){
            case 'EN':
                $bono_header["state"] = 'Activo';
                $bono_header["progress_text"] = " for Rollover to redeem the bonus";
                break;

            case "PT":
                $bono_header["state"] = 'Ativo';
                $bono_header["progress_text"] = " para Rollover para resgatar o bônus";

                break;
        }

        array_push($bono_headerf, ($bono_header));


    }

    //Generando respuesta
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = $bono_headerf;
}
