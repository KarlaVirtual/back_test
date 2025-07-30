<?php

use Backend\dto\Etiqueta;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\EtiquetaProducto;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\EtiquetaProductoMySqlDAO;
use Backend\mysql\EtiquetaMySqlDAO;

/**
 * Guardar etiquetas de grupo para productos.
 *
 * Este script permite crear y asociar etiquetas a productos, registrando auditorías y manejando transacciones.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param array $params->ProductId Lista de IDs de productos.
 * @param string $params->Partner Identificador del socio.
 * @param string $params->Country Código del país.
 * @param string $params->TagImage Imagen de la etiqueta en base64.
 * @param string $params->TagText Texto de la etiqueta en base64.
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success' o 'error').
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos adicionales en caso de error.
 */

/* asigna parámetros a variables, decodificando el texto de una etiqueta. */
$Product = $params->ProductId;
$Partner = $params->Partner;
$Country = $params->Country;
$TagImage = $params->TagImage;
$TagText = base64_decode($params->TagText);

try {
    if (is_array($Product) && !empty($Product) && !empty($Country) && (!empty($TagImage) || !empty($TagText)) && $Partner != "" && $Partner !== null) {

        /* Crea una etiqueta basada en texto o imagen, según disponibilidad. */
        $Etiqueta = new Etiqueta();
        if (empty($TagImage) && !empty($TagText)) {
            $Etiqueta->setNombre($TagText);
        } else {
            $Etiqueta->setNombre($TagImage);
        }

        /* establece propiedades de un objeto utilizando sesiones y datos actuales. */
        $Etiqueta->setUsucreaId($_SESSION['usuario']);
        $Etiqueta->setUsumodifId($_SESSION['usuario']);
        $Etiqueta->setEstado("A");
        $Etiqueta->setFechaCrea(date('Y-m-d H:i:s'));
        $Etiqueta->setFechaModif(date('Y-m-d H:i:s'));
        if (empty($TagImage) && !empty($TagText)) {
            $Etiqueta->setDescripcion($TagImage);
        } else {
            /* Establece la descripción de $Etiqueta con el valor de $TagImage si no se cumple la condición. */

            $Etiqueta->setDescripcion($TagImage);
        }

        /* configura propiedades de un objeto "Etiqueta" y crea un DAO para MySQL. */
        $Etiqueta->setMandante($Partner);
        $Etiqueta->setPaisId($Country);
        $Etiqueta->setTipo("N");


        $EtiquetaMySqlDAO = new EtiquetaMySqlDAO();

        /* Inserta una etiqueta en la base de datos y confirma la transacción. */
        $Transaction = $EtiquetaMySqlDAO->getTransaction();
        $IdEtiqueta = $EtiquetaMySqlDAO->insert($Etiqueta);
        $Transaction->commit();
        foreach ($Product as $Id) {

            /* Actualiza el estado de una etiqueta de producto a "Inactivo" en la base de datos. */
            $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
            try {
                $EtiquetaProducto = new EtiquetaProducto("", $Id, "", $Country, $Partner, "A");
                $EtiquetaAnterior = $EtiquetaProducto->text;
                $EtiquetaProducto->estado = "I";
                $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO($Transaction);
                $EtiquetaProductoMySqlDAO->update($EtiquetaProducto);
            } catch (Exception $e) {
                /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del script. */


            }


            /* Crea un objeto EtiquetaProducto y asigna valores de identificación y estado. */
            $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
            $EtiquetaProducto = new EtiquetaProducto();

            $EtiquetaProducto->etiquetaId = $IdEtiqueta;
            $EtiquetaProducto->productoId = $Id;
            $EtiquetaProducto->estado = "A";

            /* Asigna valores a propiedades de un objeto "EtiquetaProducto". */
            $EtiquetaProducto->mandante = $Partner;
            $EtiquetaProducto->paisId = $Country;
            $EtiquetaProducto->usucreaId = $_SESSION['usuario'];
            $EtiquetaProducto->usumodifId = $_SESSION['usuario'];
            $EtiquetaProducto->image = $TagImage;
            $EtiquetaProducto->text = $TagText;

            /* Código para insertar un producto y detectar la dirección IP del usuario. */
            $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO($Transaction);
            $EtiquetaProductoMySqlDAO->insert($EtiquetaProducto);

            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $ip = explode(",", $ip)[0];

            function detectarDispositivo()
            {

                /* analiza el User-Agent para identificar dispositivos móviles mediante palabras clave. */
                $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

                // Lista de palabras clave comunes en User-Agent de dispositivos móviles
                $movilKeywords = [
                    'android', 'iphone', 'ipad', 'ipod', 'blackberry', 'windows phone', 'opera mini', 'mobile', 'silk'
                ];

                // Verificar si alguna palabra clave está en el User-Agent

                /* determina si un dispositivo es móvil o PC basado en el user agent. */
                foreach ($movilKeywords as $keyword) {
                    if (strpos($userAgent, $keyword) !== false) {
                        return 'Móvil';
                    }
                }

                return 'PC';
            }

            $dispositivo = detectarDispositivo();


            /* identifica el sistema operativo a partir del agente de usuario HTTP. */
            $userAgent = $_SERVER['HTTP_USER_AGENT'];

            function getOS($userAgent)
            {
                $os = "Desconocido";

                if (stripos($userAgent, 'Windows') !== false) {
                    $os = 'Windows';
                } elseif (stripos($userAgent, 'Linux') !== false) {
                    /* verifica si el userAgent contiene 'Linux' y establece el sistema operativo. */

                    $os = 'Linux';
                } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
                    /* Verifica si el agente de usuario corresponde a un sistema operativo Mac. */

                    $os = 'Mac';
                } elseif (stripos($userAgent, 'Android') !== false) {
                    /* Detecta si el agente de usuario corresponde a un dispositivo Android. */

                    $os = 'Android';
                } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
                    /* Verifica si el agente de usuario es un iPhone o iPad para asignar 'iOS'. */

                    $os = 'iOS';
                }

                return $os;
            }


            /* obtiene el sistema operativo y registra información del usuario en auditoría. */
            $so = getOS($userAgent);


            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsuarioIp($ip);

            /* registra auditoría de actualización de configuración con datos del usuario. */
            $AuditoriaGeneral->setUsuariosolicitaId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsuariosolicitaIp($ip);
            $AuditoriaGeneral->setUsuarioaprobarId(0);
            $AuditoriaGeneral->setUsuarioaprobarIp(0);
            $AuditoriaGeneral->setTipo("ACTUALIZACION_CONFIGURACION");
            $AuditoriaGeneral->setValorAntes($EtiquetaAnterior);

            /* Código que configura propiedades de un objeto AuditoriaGeneral basándose en datos específicos. */
            $AuditoriaGeneral->setValorDespues($TagText);
            $AuditoriaGeneral->setUsucreaId($_SESSION['usuario']);
            $AuditoriaGeneral->setUsumodifId(0);
            $AuditoriaGeneral->setEstado("A");
            $AuditoriaGeneral->setDispositivo($dispositivo);
            $AuditoriaGeneral->setSoperativo($so);

            /* Registra una auditoría de cambios en etiquetas de productos en una base de datos. */
            $AuditoriaGeneral->setObservacion("cambio en etiqueta de producto o creacion de etiqueta para un producto");
            $AuditoriaGeneral->setData('');
            $AuditoriaGeneral->setCampo('');

            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
            $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


            /* finaliza una transacción en una base de datos, guardando todos los cambios realizados. */
            $Transaction->commit();
        }


        /* Se inicializan parámetros de respuesta sin errores para una operación exitosa. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    } elseif (is_array($Product) && !empty($Product) && empty($TagImage) && empty($TagText)) {
        /* Actualiza el estado de productos en la base de datos cuando no hay etiquetas. */


        foreach ($Product as $Id) {
            $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
            try {
                $EtiquetaProducto = new EtiquetaProducto("", $Id, "", $Country, $Partner, "A");
                $EtiquetaProducto->estado = "I";
                $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
                $EtiquetaProductoMySqlDAO->update($EtiquetaProducto);
                $EtiquetaProductoMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {

            }
        }
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    }
} catch (Exception $e) {
    /* Manejo de excepciones que registra errores en un arreglo de respuesta. */

    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = [];
}