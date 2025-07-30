<?php

use Backend\dto\Registro2;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoDetalle2;
use Backend\dto\SorteoInterno2;
use Backend\dto\UsuarioSorteo2;
use Backend\mysql\UsuarioSorteo2MySqlDAO;
use Backend\mysql\SorteoDetalle2MySqlDAO;
use Backend\mysql\SorteoInterno2MySqlDAO;


/**
 * Obtiene los detalles de una lotería y construye la respuesta con la información obtenida.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param int $json->params->lotteryId ID de la lotería.
 * @param int $json->params->site_id ID del sitio.
 * @param bool $json->params->isMobile Indica si la solicitud se realiza desde un dispositivo móvil.
 * @return array Respuesta con los detalles de la lotería.
 * @throws Exception Si ocurre un error al obtener los detalles de la lotería.
 */

// Se obtienen los parámetros del objeto JSON
$params = $json->params;
$lotteryId = $params->lotteryId;
$site_id = $params->site_id;
$isMobile = $params->isMobile;

$rules = [];

// Se agrega una regla que verifica el id de la lotería
array_push($rules, array("field" => "sorteo_detalle2.sorteo2_id", "data" => $lotteryId, "op" => "eq"));

// Se codifican las reglas en formato JSON para el filtro
$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$sorteoDetalles2 = new SorteoDetalle2();

// Se obtienen los detalles del sorteo utilizando el filtro creado anteriormente y se convierten a string
$datos1 = (string)$sorteoDetalles2->getSorteoDetalles2Custom('sorteo_detalle2.*', 'sorteo_detalle2.sorteodetalle2_id', 'desc', 0, 100, $filter, true);

$datos1 = json_decode($datos1);

// Se instancia un objeto de SorteoInterno2 con el id de la lotería
$sorteoInterno2 = new SorteoInterno2($lotteryId);
$nombre = $sorteoInterno2->nombre;
$descripcion = $sorteoInterno2->descripcion;
$reglas = $sorteoInterno2->reglas;

// Se construye el array final con la información obtenida
$final = array(
    "code"=>0,
    "data"=>array(
        "title"=>$nombre,
        "description"=>$descripcion,
        "rules"=>$reglas,
        "img"=>'',
        'background'=>'',
        'awards' => array()
    )
);

/*Almacenamiento y envío de las configuraciones de la lotería*/
foreach($datos1->data as $key=>$value){
    $datos2 = [];
    $seguir = true;
    switch ($value->{"sorteo_detalle2.tipo"}){
        case 'IMGPPALURL':
            $final["data"]["img"] = $value->{"sorteo_detalle2.valor"};
            break;
        case 'BACKGROUNDURL':
            $final["data"]['background'] = $value->{"sorteo_detalle2.valor"};
            break;
        case 'RANKAWARDMAT':
            $award = array(
                "position" =>$value->{"sorteo_detalle2.valor"},
                "img"=>$value->{"sorteo_detalle2.valor3"} ?: '',
                "description" => $value->{"sorteo_detalle2.descripcion"},
                "fixedTime" => $value->{"sorteo_detalle2.fecha_sorteo"},
                "userWin" => ''
            );

            // Obtiene el ID del premio desde el valor
            $idPremio = $value->{"sorteo_detalle2.sorteodetalle2_id"};

            // Inicializa un arreglo de reglas para el filtrado de usuarios
            $rules = [];

            array_push($rules,array("field"=>"usuario_sorteo2.premio_id","data"=>$idPremio,"op"=>"eq"));

            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $usuarioSorteo2 = new UsuarioSorteo2();

            // Obtiene los datos de usuario sorteos personalizados
            $datos = $usuarioSorteo2->getusuarioSorteoCustom('usuario_sorteo2.registro2_id,usuario_sorteo2.premio', 'usuario_sorteo2.ususorteo2_id','asc',0,100,$filter,true);

            $datos = json_decode($datos);

            // Recorre los datos obtenidos para asignar el ganador
            foreach ($datos->data as $key => $value) {
                if($value->{"usuario_sorteo2.premio"} != ""){
                    // Obtiene el ID de registro del usuario que ganó
                    $RegistroId = $value->{"usuario_sorteo2.registro2_id"};
                    $registro2 = new Registro2($RegistroId);

                    // Asigna el nombre y apellido del ganador al premio
                    $award["userWin"] = $registro2->nombre." ".$registro2->apellido;
                }
            }

            // Agrega el premio a la lista final de premios
            array_push($final['data']['awards'],$award);

            break;
        default:
            // Establece la bandera de continuar en falso si no se cumple ningún caso
            $seguir = false;
            break;
    }
}

/*Generación de la respuesta*/
$response = [];
// $response['code'] = 0;
$response['rid'] = $json->rid;
$response = $final;

?>




