<?php

use Backend\dto\Pais;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioSorteo;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;


/**
 * Ejecuta una consulta SQL mientras obtiene el resultado de la solicitud de adición al sorteo
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param int $json->params->id Identificador del sorteo.
 * @param string $json->rid Identificador de la solicitud.
 * @return array Respuesta en formato JSON con el código de estado y el identificador de la solicitud.
 *  - code: Código de estado de la respuesta, 0 indica éxito.
 *  - rid: Identificador de la solicitud.
 *
 * @throws Exception Si no se puede participar en el sorteo.
 */

/* Función que ejecuta una consulta SQL y convierte el resultado a objeto. */
/**
 * Ejecuta una consulta SQL y convierte el resultado a un objeto.
 *
 * @param string $sql La consulta SQL a ejecutar.
 * @return object El resultado de la consulta convertido a un objeto.
 */
function execQuery($sql)
{
    $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO();
    $return = $SorteoInternoMySqlDAO->querySQL($sql);
    $return = json_decode(json_encode($return), FALSE);

    return $return;
}


/* obtiene y asigna parámetros de un objeto JSON a variables específicas. */
$params = $json->params;

$id = $params->id;

$usuarioId = $UsuarioMandanteSite->usumandanteId;

$SorteoInterno = new SorteoInterno($id);

$sorteoid=0;
$ususorteo_id=0;
$valorASumar=0;


$sqlSorteo = "select a.ususorteo_id,a.sorteo_id,a.apostado,a.fecha_crea,sorteo_interno.condicional,sorteo_interno.tipo from usuario_sorteo a INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = a.sorteo_id) where a.estado = 'A' AND (sorteo_interno.tipo = 2 OR sorteo_interno.tipo = 5) AND a.sorteo_id='" . $SorteoInterno->sorteoId . " 'AND a.usuario_id='" . $usuarioId . "'";


$SorteoDisponible = execQuery($sqlSorteo);

$tipoProducto = "";


if (count($SorteoDisponible) == 0) {


    if ($sorteoid == 0 || NULL) {
        // obtener los detalles del sorteo
        /**
         * Consulta SQL para obtener los detalles del sorteo filtrados por ID de sorteo y moneda.
         */
        $sqlSorteoDetalle = "select * from sorteo_detalle a where a.sorteo_id='".$SorteoInterno->sorteoId."' AND (moneda='' OR moneda='".$UsuarioMandanteSite->moneda."')";

        $sorteodetalles = execQuery($sqlSorteoDetalle);


        $cumplecondicion = true; // Indica si se cumple la condición general
        $cumplecondicionProducto = false; // Indica si se cumple la condición para el producto
        $condicionesProducto = 0; // Contador de condiciones del producto
        $sorteoId = 0; // ID del sorteo
        $valorApostado = 0; // Valor apostado
        $valorAsumar = 0; // Valor a sumar

        $sePuedeSimples=0; // Indica si se pueden hacer apuestas simples
        $sePuedeCombinadas=0; // Indica si se pueden hacer apuestas combinadas
        $minselcount=0; // Conteo mínimo de selecciones

        $ganaSorteoId=0; // ID del sorteo ganador
        $tiposorteo=""; // Tipo de sorteo
        $ganaSorteoId=0; // Inicializa nuevamente el ID del sorteo ganador
        //


        /**
         * Determina el tipo de comparación basado en la condición del sorteo.
         */
        if($SorteoInterno->condicional = 'NA'|| $SorteoInterno->condicional == ''){
            $tipocomparacion = "OR"; // Se establece OR si la condición es NA o vacía
        }else{
            $tipocomparacion = $SorteoInterno->condicional; // Se utiliza la condición especificada
        }


        foreach ($sorteodetalles as $key => $value) {
            switch ($sorteodetalles->{"a.tipo"}) {
                case 'TIPOPRODUCTO':
                    // Se asigna el valor del tipo de producto desde los detalles del sorteo
                    $tipoProducto = $sorteoDetalles->{"a.valor"};
                    break;
                case "EXPDIA":
                    // Se calcula la fecha del sorteo sumando días a la fecha de creación
                    $fechaSorteo = date("Y-m-d H:i:s",strtotime($SorteoInterno->fechaCrea.' + '.$sorteodetalles->{"a.valor"}. 'days'));
                    // Se obtiene la fecha y hora actual
                    $fecha_actual = date("Y-m-d H:i:s",time());

                    // Se verifica si la fecha del sorteo es anterior a la fecha actual
                    if ($fechaSorteo < $fecha_actual) {
                        $cumplecondicion = false; // Si es así, no cumple la condición
                    }

                    break;
                    case "LIVEORPREMATCH":
                        // Esta opción no tiene implementación
                        break;

                    case "MINSELCOUNT":
                        // Esta opción no tiene implementación
                        break;

                    case "MINSELPRICE":
                        // Esta opción no tiene implementación
                        break;

                    case "MINSELPRICETOTAL":
                        // Esta opción no tiene implementación
                        break;

                    case "MINBETPRICE":
                        // Esta opción no tiene implementación
                        break;

                    case "WINBONOID":
                        // Se asigna el id del sorteo ganado y se define el tipo de sorteo
                        $ganaSorteoId = $sorteodetalles->{"a.valor"};
                        $tiposorteo = "WINBONOID";
                        $valor_sorteo=0; // Se inicializa valor del sorteo
                        break;

                    case "TIPOSALDO":
                        // Se asigna el valor del tipo de saldo desde los detalles del sorteo
                        $tiposaldo = $sorteodetalles->{"a.valor"};
                        break;

                case "FROZEWALLET":
                    break;

                case "SUPPRESSWITHDRAWAL":
                    break;

                case "SCHEDULECOUNT":
                    break;

                case "SCHEDULENAME":
                    break;

                case "SCHEDULEPERIOD":
                    break;


                case "SCHEDULEPERIODTYPE":
                    break;

                case "ITAINMENT1":
                    break;

                case "ITAINMENT3":
                    break;

                case "ITAINMENT4":
                    break;

                case "ITAINMENT5":
                    break;

                case "ITAINMENT82":
                    if ($sorteodetalles->{"a.valor"} == 1) {
                        $sePuedeSimples = 1;

                    }
                    if ($sorteodetalles->{"a.valor"} == 2) {
                        $sePuedeCombinadas = 1;

                    }
                    break;

                case "NUMBERCASINOSTICKERS":
                    $numstikerscasino = $sorteodetalles->{"a.valor"};
                    break;

                case "MINBETPRICE2CASINO":
                    $minbetpricecasino2 = $sorteodetalles->{"a.valor"};
                    break;

                case "USERSUBSCRIBE":
                    if ($sorteodetalles->{"a.valor"} == 1) {
                        $cumplecondicion = true;
                    }

                default:
                    # code...
                    break;
            }

            if ($cumplecondicion && ($cumplecondicionProducto || $condicionesProducto == 0)) {
                $sorteoId = $SorteoInterno->sorteoId;

            }

        }


    }

    if ($sorteoId != 0) {
        /** Se crea una nueva instancia de UsuarioSorteo y se inicializan sus propiedades.*/
        $UsuarioSorteo = new UsuarioSorteo();
        $UsuarioSorteo->usuarioId = $UsuarioMandanteSite->usumandanteId;
        $UsuarioSorteo->sorteoId = $SorteoInterno->sorteoId;
        $UsuarioSorteo->valor = 0;
        $UsuarioSorteo->posicion = 0;
        $UsuarioSorteo->valorBase = 0;
        $UsuarioSorteo->usucreaId = 0;
        $UsuarioSorteo->usumodifId = 0;
        $UsuarioSorteo->estado = "I";
        $UsuarioSorteo->errorId = 0;
        $UsuarioSorteo->idExterno = 0;
            $UsuarioSorteo->mandante = $SorteoInterno->mandante;
        $UsuarioSorteo->version = 0;
        $UsuarioSorteo->apostado = 0;
        $UsuarioSorteo->codigo = 0;
        $UsuarioSorteo->externoId = 0;
        $UsuarioSorteo->valor = 0;
        $UsuarioSorteo->valorBase = 0;

        $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
        $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);
        $UsuarioSorteoMySqlDAO->getTransaction()->commit();
    }
} else {
    /*Caso donde no se obtuvo el ID del sorteo*/
    throw new Exception("No puedes participar en el sorteo. ", "100099");
}

/**
 * Asigna valores a la respuesta.
 *
 * @var array $response Array que contiene el código y el identificador de la respuesta.
 * @var int $response['code'] Código de estado de la respuesta, 0 indica éxito.
 * @var mixed $response['rid'] Identificador de la respuesta extraído del objeto JSON.
 */
$response["code"] = 0;
$response["rid"] = $json->rid;


?>