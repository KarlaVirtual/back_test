<?php


use Backend\dto\AuditoriaGeneral;
use Backend\dto\FranquiciaMandante;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\FranquiciaMySqlDAOMySqlDAO;
use Backend\mysql\FranquiciaMandanteMySqlDAO;

/**
 * SaveGroupPratnersFranchises
 *
 * Guarda las franquicias asociadas a un partner y un país. Este recurso permite activar o desactivar franquicias específicas para un socio (partner)
 * determinado en un país específico. Además, registra auditorías de los cambios realizados en las tablas correspondientes.
 *
 * @param object $params : Objeto con los parámetros enviados desde el frontend. Contiene:
 *   - *Partner* (string): Código del partner al cual se asociarán o excluirán franquicias.
 *   - *CountrySelect* (string): Código del país sobre el cual se aplicará la acción.
 *   - *ExcludedFranchisesList* (string): Lista separada por comas de franquicias a desactivar.
 *   - *IncludedFranchisesList* (string): Lista separada por comas de franquicias a activar.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista (ej. "success", "danger").
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Lista de errores específicos del modelo si existen.
 *  - *Data* (array): Contiene información adicional del resultado (en este caso, se devuelve vacío).
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 * $response["Data"] = [];
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

try {
    //Verificamos data enviada por Frontend

    /* Extrae información de parámetros para configurar exclusiones de Franquicias según país y socio. */
    $Partner = $params->Partner;
    $CountrySelect = $params->CountrySelect;

    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

    //Inicializamos listas para la activacion o inactivacion de la Franquicia para el partner y pais
    $ExcludedFrancList = ($params->ExcludedFranchisesList != "") ? explode(",", $params->ExcludedFranchisesList) : array();

    /* Separa una lista de Franquicias y la inicializa; además, define una variable booleana. */
    $IncludedFrancList = ($params->IncludedFranchisesList != "") ? explode(",", $params->IncludedFranchisesList) : array();

    $insertOrUpdate = false;

    // Si sabemos a que partner y pais se van a enlazar
    if ($Partner != '' && $CountrySelect != '') {


        /* Verifica si $Partner está en la lista; lanza excepción si no lo está. */
        if (!in_array($Partner, explode(',', $_SESSION["mandanteLista"]))) {
            throw new Exception("Inusual Detected", "11");
        }

        //Si tenemos Franquicias a desactivar
        if (oldCount($ExcludedFrancList) > 0) {

            // Instanciamos la clase y cambios el estado en la tabla Franquicia mandante a 'I' (Inactivo)
            // ademas de que creamos data de seguimiento en la tabla auditoria general

            /* Se crea un objeto DAO y se obtiene una transacción de la base de datos. */
            $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO();
            $Transaction = $FranquiciaMandanteMySqlDAO->getTransaction();

            foreach ($ExcludedFrancList as $key => $value) {
                try {

                    /* Actualiza el estado de un objeto FranquiciaMandante en la base de datos. */
                    $FranquiciaMandante = new FranquiciaMandante($value, $Partner, '', $CountrySelect);
                    $estadoAntes = $FranquiciaMandante->estado;
                    $FranquiciaMandante->estado = 'I';
                    $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO($Transaction);
                    $FranquiciaMandanteMySqlDAO->update($FranquiciaMandante);
                    $insertOrUpdate = true;


                    /* Se crea una auditoría general con información de usuario e IP. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);

                    /* configura una auditoría para la desactivación de un Franquicia. */
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("DESACTIVACIONDEFRANQUICIA");
                    $AuditoriaGeneral->setValorAntes($estadoAntes);
                    $AuditoriaGeneral->setValorDespues("I");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);

                    /* Se establece el estado, dispositivo y observación en Auditoría General con MySQL. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion($FranquiciaMandante->FranquiciamandanteId);


                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);

                    /* Inserta un registro de auditoría general en la base de datos MySQL. */
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


                } catch (Exception $e) {
                    /* captura excepciones y verifica si el código de error es "27". */

                    if ($e->getCode() == "27") {

                    }

                }

            }
            //Generamos commit de la SQL

            /* Confirma y guarda permanentemente los cambios realizados en la base de datos. */
            $Transaction->commit();

        }

        //Si tenemos Franquicias a activar
        if (oldCount($IncludedFrancList) > 0) {

            // Instanciamos la clase y cambios el estado en la tabla Franquicia mandante a 'A' (Activo)
            // ademas de que creamos data de seguimiento en la tabla auditoria general

            /* Se crea un objeto DAO para acceder a transacciones en la base de datos MySQL. */
            $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO();
            $Transaction = $FranquiciaMandanteMySqlDAO->getTransaction();

            foreach ($IncludedFrancList as $key => $value) {
                try {

                    /* actualiza el estado de un objeto FranquiciaMandante en la base de datos. */
                    $FranquiciaMandante = new FranquiciaMandante($value, $Partner, '', $CountrySelect);
                    $estadoAntes = $FranquiciaMandante->estado;
                    $FranquiciaMandante->estado = 'A';
                    $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO($Transaction);
                    $FranquiciaMandanteMySqlDAO->update($FranquiciaMandante);
                    $insertOrUpdate = true;


                    /* Código que inicializa una auditoría general con información del usuario y su IP. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);

                    /* Configura una auditoría de estado para la activación de un Franquicia en el sistema. */
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("ACTIVACIONDEFranquicia");
                    $AuditoriaGeneral->setValorAntes($estadoAntes);
                    $AuditoriaGeneral->setValorDespues("A");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);

                    /* Configura y registra una auditoría en la base de datos utilizando MySQL. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion($FranquiciaMandante->FranquiciamandanteId);

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


                } catch (Exception $e) {

                    // Si en la tabla Franquicia mandante no existe el Franquicia a activar, generamos un insert para añadirlño en la tabla
                    // ademas de que creamos data de seguimiento en la tabla auditoria general
                    if ($e->getCode() == "27") {


                        /* Inicializa un objeto FranquiciaMandante con datos de un socio y usuario. */
                        $FranquiciaMandante = new FranquiciaMandante();

                        $FranquiciaMandante->mandante = $Partner;
                        $FranquiciaMandante->franquiciaId = $value;
                        $FranquiciaMandante->estado = 'A';
                        $FranquiciaMandante->usucreaId = $_SESSION["usuario"];

                        /* Asignación de variables y creación de auditoría con información del usuario y su IP. */
                        $FranquiciaMandante->usumodifId = $_SESSION["usuario"];
                        $FranquiciaMandante->paisId = $CountrySelect;
                        $FranquiciaMandante->verifica = 'I';
                        $FranquiciaMandante->detalle = 'agregadoInicial';


                        $AuditoriaGeneral = new AuditoriaGeneral();
                        $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuarioIp($ip);

                        /* Establece parámetros de auditoría relacionados con la activación de un Franquicia. */
                        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId(0);
                        $AuditoriaGeneral->setUsuarioaprobarIp(0);
                        $AuditoriaGeneral->setTipo("ACTIVACIONDEFRANQUICIA");
                        $AuditoriaGeneral->setValorAntes("I");

                        /* Se configura un objeto AuditoriaGeneral con diversos atributos para registrar acciones. */
                        $AuditoriaGeneral->setValorDespues("A");
                        $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsumodifId(0);
                        $AuditoriaGeneral->setEstado("A");
                        $AuditoriaGeneral->setDispositivo(0);
                        $AuditoriaGeneral->setObservacion($FranquiciaMandante->FranquiciamandanteId);


                        /* Se insertan registros en base de datos usando dos DAOs y una transacción. */
                        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

                        $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO($Transaction);
                        $FranquiciaMandanteMySqlDAO->insert($FranquiciaMandante);
                        $insertOrUpdate = true;


                    }

                }

            }


            /* finaliza una transacción, confirmando todos los cambios realizados. */
            $Transaction->commit();


        }

//        if($insertOrUpdate){
//            $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO','',$Partner,$CountrySelect);
//            $CMSProveedor->updateDatabaseCasino();
//        }


        /* establece una respuesta exitosa con mensajes y datos vacíos. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    } else {
        /* maneja una respuesta sin errores, estableciendo mensajes y tipos de alerta. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

    }
} catch (Exception $e) {
    /* maneja excepciones en PHP, capturando errores sin imprimir detalles. */

//    print_r($e);
}
