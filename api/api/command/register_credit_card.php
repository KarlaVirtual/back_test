<?php

use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioMandante;

/**
 * Registra una tarjeta de crédito y procesa el pago.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la tarjeta de crédito y otros datos necesarios.
 * @param $json->params->num_tarjeta string Número de tarjeta de crédito.
 * @param $json->params->expiry_month string Mes de vencimiento de la tarjeta de crédito.
 * @param $json->params->expiry_year string Año de vencimiento de la tarjeta de crédito.
 * @param $json->params->cvv string Código de seguridad de la tarjeta de crédito.
 * @param $json->params->amount string Monto de la transacción.
 * @param $json->params->productId string Identificador del producto.
 * @param $json->params->saveCard string Indicador para guardar la tarjeta de crédito.
 * @param $json->params->requestId string Identificador de la solicitud.
 * @param $json->params->referenceId string Identificador de referencia.
 *
 * @return array Respuesta en formato JSON con el resultado del procesamiento de la tarjeta.
 * @throws Exception Si el procesamiento de la tarjeta no está permitido.
 * @throws Exception Si se detecta un caso inusual donde el proveedor es nulo.
 */

/* crea una respuesta JSON con un código y un identificador. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);


/* Código para crear objetos de usuario y extraer parámetros de tarjeta desde un JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$numTarjeta = $json->params->num_tarjeta;
$expiry_month = $json->params->expiry_month;
$expiry_year = $json->params->expiry_year;

$cvv = $json->params->cvv;

/* Extrae parámetros de un objeto JSON y limpia el número de tarjeta. */
$valor = $json->params->amount;
$productId = $json->params->productId;
$saveCard = $json->params->saveCard;
$requestId = $json->params->requestId;
$referenceId = $json->params->referenceId;


$numTarjeta = str_replace(' ', '', $numTarjeta);


/* Asigna los parámetros del objeto JSON a la variable $datos en PHP. */
$datos = $json->params;

if ($valor == '' || $valor == false || $valor == null) {
    throw new Exception("Inusual Detected", "100001");
} else {

    if ($UsuarioMandante->mandante == 2) {


        /* verifica si un número de tarjeta coincide con un rango permitido. */
        $seguirTC = false;

        $CreditCardFromAllow = 5357920000;
        $CreditCardToAllow = 5357929999;

        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si una tarjeta pertenece a un rango específico de números. */
        $CreditCardFromAllow = 5430090000;
        $CreditCardToAllow = 5430099999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Valida si un número de tarjeta pertenece a un rango específico permitido. */
        $CreditCardFromAllow = 5365860000;
        $CreditCardToAllow = 5365869999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5549440000;
        $CreditCardToAllow = 5549449999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5249395000;
        $CreditCardToAllow = 5249399999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta coincide con un rango permitido. */
        $CreditCardFromAllow = 5524540000;
        $CreditCardToAllow = 5524549179;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si el número de tarjeta coincide con un rango permitido. */
        $CreditCardFromAllow = 5524549180;
        $CreditCardToAllow = 5524549189;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está en un rango específico. */
        $CreditCardFromAllow = 5524549200;
        $CreditCardToAllow = 5524549999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5524550000;
        $CreditCardToAllow = 5524558179;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si una tarjeta de crédito se encuentra en un rango permitido. */
        $CreditCardFromAllow = 5524558190;
        $CreditCardToAllow = 5524558199;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5524558200;
        $CreditCardToAllow = 5524559999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si una tarjeta pertenece a un rango permitido de números. */
        $CreditCardFromAllow = 5437200000;
        $CreditCardToAllow = 5437209999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* valida si un número de tarjeta está dentro de un rango específico. */
        $CreditCardFromAllow = 5195340000;
        $CreditCardToAllow = 5195349999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta coincide con un rango permitido. */
        $CreditCardFromAllow = 5405220000;
        $CreditCardToAllow = 5405229999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Valida si un número de tarjeta coincide dentro de un rango permitido. */
        $CreditCardFromAllow = 5383930000;
        $CreditCardToAllow = 5383939999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5419240000;
        $CreditCardToAllow = 5419249999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Valida si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5524870000;
        $CreditCardToAllow = 5524870099;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5434300000;
        $CreditCardToAllow = 5434309999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Comprueba si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 2330070000;
        $CreditCardToAllow = 2330079999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si el número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 2320230000;
        $CreditCardToAllow = 2320239999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango específico. */
        $CreditCardFromAllow = 2320120000;
        $CreditCardToAllow = 2320129999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Compara el inicio de un número de tarjeta con un rango permitido. */
        $CreditCardFromAllow = 5474410000;
        $CreditCardToAllow = 5474419999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si el número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5249390000;
        $CreditCardToAllow = 5249394999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Valida si un número de tarjeta pertenece a un rango permitido. */
        $CreditCardFromAllow = 5123220000;
        $CreditCardToAllow = 5123229999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5524549190;
        $CreditCardToAllow = 5524549199;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5524558180;
        $CreditCardToAllow = 5524558189;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta coincide con un rango específico. */
        $CreditCardFromAllow = 5420940000;
        $CreditCardToAllow = 5420949999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si el número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5341710000;
        $CreditCardToAllow = 5341719999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Valida si un número de tarjeta pertenece a un rango permitido. */
        $CreditCardFromAllow = 5253940000;
        $CreditCardToAllow = 5253949999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Valida si un número de tarjeta está dentro de un rango específico. */
        $CreditCardFromAllow = 5232800000;
        $CreditCardToAllow = 5232809999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta coincide con un rango permitido. */
        $CreditCardFromAllow = 5201780000;
        $CreditCardToAllow = 5201789999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está dentro de un rango específico. */
        $CreditCardFromAllow = 5466580000;
        $CreditCardToAllow = 5466589999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Valida si un número de tarjeta está dentro de un rango específico permitido. */
        $CreditCardFromAllow = 5123230000;
        $CreditCardToAllow = 5123239999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta de crédito está en un rango permitido. */
        $CreditCardFromAllow = 5201290000;
        $CreditCardToAllow = 5201299999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5443110000;
        $CreditCardToAllow = 5443119999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta pertenece a un rango específico. */
        $CreditCardFromAllow = 5201760000;
        $CreditCardToAllow = 5201769999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango específico. */
        $CreditCardFromAllow = 5535870000;
        $CreditCardToAllow = 5535879999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5202280000;
        $CreditCardToAllow = 5202289999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta pertenece a un rango específico. */
        $CreditCardFromAllow = 5443100000;
        $CreditCardToAllow = 5443109999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta de crédito está permitido en un rango específico. */
        $CreditCardFromAllow = 5406710000;
        $CreditCardToAllow = 5406719999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta pertenece a un rango específico. */
        $CreditCardFromAllow = 5489590000;
        $CreditCardToAllow = 5489599999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si el número de tarjeta está dentro de un rango específico. */
        $CreditCardFromAllow = 5489580000;
        $CreditCardToAllow = 5489589999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5358560000;
        $CreditCardToAllow = 5358569999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está en un rango permitido. */
        $CreditCardFromAllow = 5156930000;
        $CreditCardToAllow = 5156939999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta coincide con un rango permitido. */
        $CreditCardFromAllow = 5124160000;
        $CreditCardToAllow = 5124169999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango permitido. */
        $CreditCardFromAllow = 5578380000;
        $CreditCardToAllow = 5578389999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si una tarjeta de crédito está dentro de un rango permitido. */
        $CreditCardFromAllow = 5450160000;
        $CreditCardToAllow = 5450169999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Valida si un número de tarjeta está dentro de un rango específico de tarjetas permitidas. */
        $CreditCardFromAllow = 5401790000;
        $CreditCardToAllow = 5401799999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está dentro de un rango específico. */
        $CreditCardFromAllow = 5520590000;
        $CreditCardToAllow = 5520599999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango específico. */
        $CreditCardFromAllow = 5496080000;
        $CreditCardToAllow = 5496089999;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta pertenece a un rango permitido. */
        $CreditCardFromAllow = 5390054100;
        $CreditCardToAllow = 5390054199;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta coincide con un rango específico. */
        $CreditCardFromAllow = 5595387600;
        $CreditCardToAllow = 5595387699;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si un número de tarjeta está dentro del rango permitido. */
        $CreditCardFromAllow = 5531978500;
        $CreditCardToAllow = 5531978599;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si una tarjeta de crédito está dentro de un rango permitido. */
        $CreditCardFromAllow = 5595231400;
        $CreditCardToAllow = 5595231499;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango específico permitido. */
        $CreditCardFromAllow = 5590450500;
        $CreditCardToAllow = 5590450599;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Verifica si un número de tarjeta está dentro de un rango específico permitido. */
        $CreditCardFromAllow = 5454707600;
        $CreditCardToAllow = 5454707699;


        for ($i = $CreditCardFromAllow; $i <= $CreditCardToAllow; $i++) {
            $tc1 = substr($numTarjeta, 0, 10);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* verifica si el inicio de una tarjeta coincide con valores en un array. */
        $arrayTC = array(411931, 417975, 417976, 417977, 424115, 425626, 426367, 426368, 430196, 430390, 430498, 430998, 434481, 434566, 434771, 435094, 443944, 443945, 448793, 448794, 449582, 451483, 459071, 459072, 462809, 462810, 463467, 469272, 469757, 473868, 474490, 479470, 491431, 499953, 999999);

        foreach ($arrayTC as $i) {

            $tc1 = substr($numTarjeta, 0, 6);
            if ($i == $tc1) {
                $seguirTC = true;
            }
        }


        /* Lanza una excepción si no se permite el procesamiento de la tarjeta. */
        if (!$seguirTC) {
            throw new Exception("Procesamiento de tarjeta no permitido", "100032");
        }
    }


    /* verifica el mandante y crea instancias de Producto y Proveedor. */
    $datos = $json->params;


    if ($Usuario->mandante != "2") {

        $Producto = new Producto($productId);

        $Proveedor = new Proveedor($Producto->proveedorId);
    } else {
        /* Crea un nuevo objeto "Proveedor" con el nombre 'SAGICOR' si no se cumple la condición. */

        $Proveedor = new Proveedor('', 'SAGICOR');
    }

    if (true) {
        if ($Proveedor != null) {
            switch ($Proveedor->getAbreviado()) {

                case 'PAGADITO':
                    /* Se integra un pago con tarjeta mediante el servicio PAGADITO para un producto. */


                    $Producto = new Producto($productId);

                    $Proveedor = new Proveedor($Producto->proveedorId);

                    $PAGADITOSERVICES = new Backend\integrations\payment\PAGADITOSERVICES();

                    $data = $PAGADITOSERVICES->addCard($Usuario, $Producto, $numTarjeta, $UsuarioMandante->getNombres(), $expiry_month, $expiry_year, $cvv, $Proveedor->getProveedorId(), $valor, $saveCard, $requestId, $referenceId);

                    break;

                case 'VISANET':
                    /* Proceso de integración de pago con tarjeta VISANET en el sistema. */


                    $Producto = new Producto($productId);

                    $Proveedor = new Proveedor($Producto->proveedorId);

                    $VISANETSERVICES = new Backend\integrations\payment\VISANETSERVICES();

                    $data = $VISANETSERVICES->addCard($Usuario, $Producto, $numTarjeta, $UsuarioMandante->getNombres(), $expiry_month, $expiry_year, $cvv, $Proveedor->getProveedorId(), $valor, $saveCard, $requestId, $referenceId);

                    break;

                case 'GREENPAY':
                    /* Implementa el servicio de pago GREENPAY para agregar una tarjeta y procesar transacciones. */


                    $Producto = new Producto($productId);

                    $Proveedor = new Proveedor($Producto->proveedorId);

                    $GREENPAYSERVICES = new Backend\integrations\payment\GREENPAYSERVICES();

                    $data = $GREENPAYSERVICES->addCard($Usuario, $Producto, $UsuarioMandante->getNombres(), $Proveedor->getProveedorId(), $saveCard, $requestId, $referenceId, $valor, $datos);

                    break;

                case 'PAYGATE':
                    /* Código para procesar pagos adicionando una tarjeta a un servicio específico. */


                    $Producto = new Producto($productId);

                    $Proveedor = new Proveedor($Producto->proveedorId);

                    $PAYGATESERVICES = new Backend\integrations\payment\PAYGATESERVICES();

                    $data = $PAYGATESERVICES->addCard($Usuario, $Producto, $numTarjeta, $UsuarioMandante->getNombres(), $expiry_month, $expiry_year, $cvv, $Proveedor->getProveedorId(), $valor, $saveCard, $requestId, $referenceId);

                    break;

                case 'N1CO':
                    /* Añade una tarjeta de pago usando el servicio N1CO para un usuario específico. */


                    $N1COSERVICES = new Backend\integrations\payment\N1COSERVICES();

                    $data = $N1COSERVICES->addCard($Usuario, $Producto, $numTarjeta, $UsuarioMandante->getNombres(), $expiry_month, $expiry_year, $cvv, $Proveedor->getProveedorId(), $valor, $saveCard);

                    break;


                case 'PAYMENTEZ':
                    /* Añade una tarjeta de pago utilizando los servicios de PAYMENTEZ en el sistema. */


                    $PAYMENTEZSERVICES = new Backend\integrations\payment\PAYMENTEZSERVICES();

                    $data = $PAYMENTEZSERVICES->addCard($Usuario, $numTarjeta, $UsuarioMandante->getNombres(), $expiry_month, $expiry_year, $cvc, $Proveedor->getProveedorId());

                    break;

                case 'NUVEI':
                    /* Código para agregar una tarjeta de pago mediante el servicio NUVEI. */


                    $NUVEISERVICES = new Backend\integrations\payment\NUVEISERVICES();

                    $data = $NUVEISERVICES->addCard($Usuario, $numTarjeta, $UsuarioMandante->getNombres(), $expiry_month, $expiry_year, $cvv, $Proveedor->getProveedorId());

                    break;


                case 'INSWITCH':
                    /* Inicializa el servicio INS_SWITCH en el caso 'INSWITCH' de un switch. */

                    $INSWITCSERVICES = new \Backend\Integrations\payment\INSWITCHSERVICES();

                    break;

                case 'SAGICOR':
                    /* Se crea un servicio de pago y se realiza una transacción con un producto. */


                    $SAGICORSERVICES = new Backend\integrations\payment\SAGICORSERVICES();

                    //Como obtener el producto para la creación de la transacción ?

                    $Producto = new Producto("", "Tarjetas", $Proveedor->getProveedorId());
                    $data = $SAGICORSERVICES->createRequestPayment2($Usuario, $Producto, $Proveedor->getProveedorId(), $datos);

                    break;
            }
        } else {
            /* Lanza una excepción si el proveedor es nulo, indicando un caso inusual. */

            // Manejar el caso donde $Proveedor es nulo
            throw new Exception("Inusual Detected", "100001");
        }
    }
}

//$ConfigurationEnvironment = new ConfigurationEnvironment();

/* Se verifica el éxito de una operación y se prepara una respuesta estructurada. */
if ($data->success == "true") {
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message,
        "id" => $data->Id,
        "code" => $data->code,
        "token" => $data->token,
        "requestId" => $data->requestId,
        "referenceId" => $data->referenceId,
        "stepUpUrl" => $data->stepUpUrl,
        "idTransaction" => $data->idTransaction,
        "transactionOriginal" => $data->transactionOriginal
    );
} else {
    /* crea una respuesta en formato JSON con datos específicos. */
    $response = array();
    $response["code"] = 1;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message
    );
}
