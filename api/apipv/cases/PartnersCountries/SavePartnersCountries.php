<?php
use Backend\dto\PaisMandante;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\PaisMandanteMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * Guardar países asociados a un mandante.
 *
 * Este script permite guardar la lista de países incluidos y excluidos para un mandante.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $params->Partner Identificador del mandante.
 * @param string $params->ExcludedCountriesList Lista de países excluidos, separados por comas.
 * @param string $params->IncludedCountriesList Lista de países incluidos, separados por comas.
 * 
 *
 * @return array $response Respuesta con los siguientes índices:
 *  - hasError (bool): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Datos procesados.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */


try {

    /* obtiene la IP del usuario desde encabezados HTTP o la dirección remota. */
    $ip = !empty($_SERVER['HTTP_X_FORWADED_']) ? $_SERVER['HTTP_X_FORWADED_FOR'] : $SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];
    $insertOrUpdate = false;


    $Partner = "" . $params->Partner;


    /* Separa cadenas de países excluidos e incluidos en listas con función explode. */
    $ExcludedCountriesList = ($params->ExcludedCountriesList != "") ? explode(",", $params->ExcludedCountriesList) : array();
    $IncludedCountriesList = ($params->IncludedCountriesList != "") ? explode(",", $params->IncludedCountriesList) : array();


    if ($Partner !== '') {


        /* Creación de un objeto DAO y obtención de una transacción de base de datos. */
        $PaisMandanteMysqlDao = new PaisMandanteMySqlDAO();
        $Transaction = $PaisMandanteMysqlDao->getTransaction();


        foreach ($ExcludedCountriesList as $key => $value) {
            try {

                /* Crea un objeto 'PaisMandante', actualiza su estado y registra auditoría general. */
                $PaisMandante = new PaisMandante('', $Partner, $value);
                $PaisMandante->estado = "I";
                $PaisMandanteMysqlDao = new PaisMandanteMySqlDAO($Transaction);
                $PaisMandanteMysqlDao->update($PaisMandante);
                $insertOrUpdate = true;

                $AuditoriaGeneral = new AuditoriaGeneral();

                /* establece datos de auditoría relacionados con un usuario y una acción específica. */
                $AuditoriaGeneral->setUsuarioId($_SESSION["usuario2"]);
                $AuditoriaGeneral->setUsuarioIp("");

                $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario2"]);
                $AuditoriaGeneral->setUsuarioSolicitaIp($ip);

                $AuditoriaGeneral->setTipo("PAIS NO AGREGADO");

                /* Se establece el estado y se guarda un objeto en la base de datos. */
                $AuditoriaGeneral->setEstado("A");
                $AuditoriaGeneral->setValorAntes("");

                $AuditoriaGeneral->setUsucreaId(0);
                $AuditoriaGeneralMysqlDao = new AuditoriaGeneralMySqlDAO($Transaction);
                $AuditoriaGeneralMysqlDao->insert($AuditoriaGeneral);


            } catch (Exception $e) {
                /* Captura excepciones y verifica si el código es "30" para manejar errores específicos. */


                if ($e->Getcode() == "30") { // este 30 lo obtiene de el constructor y proviene de la excepcion

                }
            }
        }


        /* confirma una transacción en una base de datos, guardando los cambios realizados. */
        $Transaction->commit();

        if (oldCount($IncludedCountriesList) > 0) {

            /* Se crea una instancia de un DAO y se obtiene una transacción. */
            $PaisMandanteMysqlDao = new PaisMandanteMySqlDAO();
            $Transaction = $PaisMandanteMysqlDao->getTransaction();

            foreach ($IncludedCountriesList as $key => $value) {
                try {

                    /* actualiza un registro de PaisMandante y crea una auditoría. */
                    $PaisMandante = new PaisMandante('', $Partner, $value);
                    $PaisMandante->estado = 'A';
                    $PaisMandanteMysqlDao = new PaisMandanteMySqlDAO($Transaction);
                    $PaisMandanteMysqlDao->update($PaisMandante);
                    $insertOrUpdate = true;

                    $AuditoriaGeneral = new AuditoriaGeneral();

                    /* Código para registrar auditoría sobre país no agregado con información del usuario. */
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario2"]);
                    $AuditoriaGeneral->setUsuarioIp("");

                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario2"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);


                    $AuditoriaGeneral->setTipo("PAIS NO AGREGADO");

                    /* Configura propiedades de la auditoría general en un sistema de base de datos. */
                    $AuditoriaGeneral->setEstado("I");
                    $AuditoriaGeneral->setValorAntes("");
                    $AuditoriaGeneral->setValorDespues("");
                    $AuditoriaGeneral->setUsucreaId(0);
                    $AuditoriaGeneral->setUsumodifId(0);
                    //$auditoriaGeneral->setObservacion($nota); tengo una pregunta la variable nota sirve para capturar un dato mandando desde el front

                    $AuditoriaGeneralMysqlDao = new AuditoriaGeneralMySqlDAO($Transaction);

                    /* Inserta un registro de auditoría general en la base de datos MySQL. */
                    $AuditoriaGeneralMysqlDao->insert($AuditoriaGeneral);

                } catch (exception $e) {
                    /* Maneja excepciones específicas, creando e insertando un objeto en la base de datos. */


                    if ($e->getCode() == "30") {
                        $PaisMandante = new PaisMandante();
                        $PaisMandante->paisId = $value;
                        $PaisMandante->mandante = $Partner;

                        $PaisMandante->estado = "A";
                        $PaisMandante->usucreaId = $_SESSION["usuario"];
                        $PaisMandante->usumodifId = $_SESSION["usuario"];

                        $PaisMandanteMysqlDao = new PaisMandanteMySqlDAO($Transaction);
                        $PaisMandanteMysqlDao->insert($PaisMandante);
                        $insertOrUpdate = true;


                    }
                }
            }

            /* Finaliza con éxito una transacción en una base de datos, guardando cambios realizados. */
            $Transaction->commit();

        }
    }


    /* maneja una respuesta sin errores tras insertar o actualizar datos. */
    if ($insertOrUpdate) {
        $response["hasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = null;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

    } else {
        /* Establece una respuesta de error sin mensajes ni modelos específicos. */

        $response["HasError"] = true;
        $reponse["AlertType"] = "success";
        $response["alertMessage"] = null;
        $response["ModelsError"] = [];
    }
} catch (Exception $e) {
    /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */


}