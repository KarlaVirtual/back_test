<?php

use Backend\dto\PaisMandante;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\PaisMandanteMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * Guardar tasas de cambio de monedas.
 *
 * Este script permite insertar o actualizar las tasas de cambio de monedas para un partner específico.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param int $params->id Identificador del registro.
 * @param string $params->baseCurrency Moneda base.
 * @param float $params->trmNio Tasa de cambio de moneda NIO.
 * @param float $params->trmPen Tasa de cambio de moneda PEN.
 * @param float $params->trmClp Tasa de cambio de moneda CLP.
 * @param float $params->trmGtq Tasa de cambio de moneda GTQ.
 * @param float $params->trmJmd Tasa de cambio de moneda JMD.
 * @param float $params->value Valor asociado.
 * @param float $params->trmUsd Tasa de cambio de moneda USD.
 * @param float $params->trmMxn Tasa de cambio de moneda MXN.
 * @param float $params->trmBrl Tasa de cambio de moneda BRL.
 * @param float $params->trmCrc Tasa de cambio de moneda CRC.
 * @param float $params->trmGyd Tasa de cambio de moneda GYD.
 * @param float $params->trmVes Tasa de cambio de moneda VES.
 * @param string $params->Partner Identificador del partner.
 * 
 *
 * @return array Respuesta estructurada:
 *  - hasError: boolean Indica si ocurrió un error.
 *  - AlertType: string Tipo de alerta.
 *  - AlertMessage: string Mensaje de alerta.
 *  - ModelErrors: array Errores del modelo.
 *  - Data: array Datos adicionales.
 *
 * @throws Exception Si ocurre un error durante la transacción o actualización.
 */

try {


    /* asigna valores desde parámetros a variables para insertar o actualizar registros. */
    $insertOrUpdate = false;


    $id = $params->id;
    $baseCurrency = $params->baseCurrency;
    $trmNio = $params->trmNio;

    /* Asignación de variables extraídas de un objeto $params en PHP. */
    $trmPen = $params->trmPen;
    $trmClp = $params->trmClp;
    $trmGtq = $params->trmGtq;
    $trmJmd = $params->trmJmd;
    $value = $params->value;
    $trmUsd = $params->trmUsd;

    /* Código que asigna valores de parámetros a variables específicas para su uso posterior. */
    $trmMxn = $params->trmMxn;
    $trmBrl = $params->trmBrl;
    $trmCrc = $params->trmCrc;
    $trmGyd = $params->trmGyd;
    $trmVes = $params->trmVes;
    $Partner = $params->Partner;


    if ($Partner !== '') {


        /* Se crea una instancia de un DAO y se obtiene una transacción. */
        $PaisMandanteMysqlDao = new PaisMandanteMySqlDAO();
        $Transaction = $PaisMandanteMysqlDao->getTransaction();

        try {

            /* Se crea un objeto PaisMandante y se actualizan sus propiedades. */
            $PaisMandante = new PaisMandante('', $Partner, $id);
            $PaisMandante->estado = "A";
            $PaisMandante->fechaModif = date("Y-m-d H:i:s");
            $PaisMandante->usumodifId = $_SESSION["usuario"];
            $PaisMandante->trmNio = $trmNio;
            $PaisMandante->trmMxn = $trmMxn;

            /* Se asignan valores de tasas de cambio a propiedades del objeto $PaisMandante. */
            $PaisMandante->trmPen = $trmPen;
            $PaisMandante->trmBrl = $trmBrl;
            $PaisMandante->trmClp = $trmClp;
            $PaisMandante->trmCrc = $trmCrc;
            $PaisMandante->trmUsd = $trmUsd;
            $PaisMandante->trmGtq = $trmGtq;

            /* Actualiza datos de un objeto PaísMandante en la base de datos MySQL. */
            $PaisMandante->trmGyd = $trmGyd;
            $PaisMandante->trmJmd = $trmJmd;
            $PaisMandante->trmVes = $trmVes;
            $PaisMandanteMysqlDao = new PaisMandanteMySqlDAO($Transaction);
            $PaisMandanteMysqlDao->update($PaisMandante);
            $insertOrUpdate = true;

        } catch (Exception $e) {
            /* Maneja excepciones capturando errores específicos con el código 30 en PHP. */

            if ($e->Getcode() == "30") {

            }
        }


        /* finaliza y guarda los cambios en una transacción en una base de datos. */
        $Transaction->commit();

    }


    /* gestiona respuestas exitosas para inserciones o actualizaciones de datos. */
    if ($insertOrUpdate) {
        $response["hasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = null;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

    } else {
        /* maneja un error, estableciendo propiedades en un arreglo de respuesta. */

        $response["HasError"] = true;
        $reponse["AlertType"] = "success";
        $response["alertMessage"] = null;
        $response["ModelsError"] = [];
    }
} catch (Exception $e) {
    /* captura excepciones en PHP y evita errores en la ejecución. */

    //print_r($e);

}