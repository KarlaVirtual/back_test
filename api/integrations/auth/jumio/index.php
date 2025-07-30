<?php

/**
 * Este archivo contiene un script para procesar y gestionar integraciones de autenticación
 * con servicios externos como Jumio y Auco, incluyendo la verificación de usuarios y flujos de trabajo.
 *
 * @category   Seguridad
 * @package    Auth
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-05
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $json                        Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $UsuarioMandante             Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Usuario                     Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Clasificador                Esta variable se utiliza para clasificar información dentro del sistema.
 * @var mixed $MandanteDetalle             Esta variable contiene detalles específicos del mandante, utilizados en la configuración del sistema.
 * @var mixed $Proveedor                   Esta variable representa la información del proveedor, utilizada para operaciones comerciales o logísticas.
 * @var mixed $UsuarioVerificacion         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $ClasificadorPro             Esta variable se utiliza para clasificar información de manera profesional, diferenciando criterios avanzados.
 * @var mixed $rules                       Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                      Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $jsonbetshop                 Esta variable contiene datos en formato JSON específicos para la integración con Betshop.
 * @var mixed $Verificaciones              Esta variable almacena resultados o datos relacionados con procesos de verificación.
 * @var mixed $JUMIOSERVICES               Esta variable se utiliza en la integración con Jumio Services para procesos de verificación de identidad.
 * @var mixed $token                       Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $accountId                   Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $Id                          Esta variable representa un identificador único en el sistema.
 * @var mixed $workflowExecutionId         Esta variable almacena el identificador de ejecución del flujo de trabajo, para seguimiento de procesos.
 * @var mixed $IdWorkflow                  Esta variable representa el identificador del flujo de trabajo en el sistema.
 * @var mixed $string                      Esta variable contiene una cadena de texto, utilizada para representar información textual.
 * @var mixed $Response                    Esta variable contiene la respuesta generada por una operación o solicitud.
 * @var mixed $_REQUEST                    Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $status                      Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $urlRenew                    Esta variable se utiliza para almacenar y manipular la URL de renovación.
 * @var mixed $url                         Esta variable se utiliza para almacenar y manipular una URL genérica.
 * @var mixed $response                    Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $UsuarioVerificacionMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMySqlDAO             Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Transaction                 Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $data                        Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $e                           Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $UsuarioVerificacionId       Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $AUCOSERVICES                Esta variable se utiliza en la integración de AUCOSERVICES para gestionar operaciones específicas.
 */

use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioVerificacion;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;

if ($json->session->logueado) {
    print_r($json);
    exit();
    /* error_reporting(E_ALL);
     ini_set("display_errors","ON");*/
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $Clasificador = new Clasificador("", "PROVVERIFICA");
    $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->clasificadorId, $UsuarioMandante->paisId, 'A');
    $Proveedor = new Proveedor($MandanteDetalle->valor);
    /*switch ($Proveedor->abreviado) {
        case "JUMIO":
            try {

                $UsuarioVerificacion = new UsuarioVerificacion();
                $ClasificadorPro = new Clasificador("", "VERIFICAJUMIO");

                if ($Usuario->accountIdJumio != "") {
                    $rules = [];
                    array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                    array_push($rules, array("field" => "usuario_verificacion.estado", "data" => "P", "op" => "eq"));
                    array_push($rules, array("field" => "verificacion_log.tipo", "data" => "URLREDIRECTION", "op" => "eq"));
                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $jsonbetshop = json_encode($filtro);

                    $Verificaciones = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*, verificacion_log.json", "usuario_verificacion.usuverificacion_id", "asc", 0, 1, $jsonbetshop, true);
                    $Verificaciones = json_decode($Verificaciones);

                    $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();
                    $token = ($JUMIOSERVICES->accesToken($UsuarioMandante));

                    $accountId = $Verificaciones->data[0]->{'verificacion_log.json'};
                    $accountId = json_decode($accountId);
                    $Id = $accountId->account->id;

                    $workflowExecutionId = $Verificaciones->data[0]->{'verificacion_log.json'};
                    $workflowExecutionId = json_decode($workflowExecutionId);
                    $IdWorkflow = $workflowExecutionId->workflowExecution->id;

                    $string = $Id . "/workflow-executions/" . $IdWorkflow . "/status";


                    $Response = $JUMIOSERVICES->connectionGET($string);
                    $Response = json_decode($Response);

                    if ($_REQUEST['test'] == '1') {
                        print_r($Response);
                    }

                    $status = $Response->workflowExecution->status;

                    if ($status == 'INITIATED') {

                        $urlRenew = $Verificaciones->data[0]->{'verificacion_log.json'};
                        $urlRenew = json_decode($urlRenew);
                        $url = $urlRenew->web->href;

                        $response = array();
                        $response["url"] = $url;
                    }else{

                        $UsuarioVerificacion = new UsuarioVerificacion();
                        $UsuarioVerificacion->setUsuarioId($Usuario->usuarioId);
                        $UsuarioVerificacion->setMandante($Usuario->mandante);
                        $UsuarioVerificacion->setPaisId($Usuario->paisId);
                        $UsuarioVerificacion->setEstado('P');
                        $UsuarioVerificacion->setTipo('USUVERIFICACION');
                        $UsuarioVerificacion->setObservacion('Pendiente Verificación JUMIO');
                        $UsuarioVerificacion->setUsucreaId(0);
                        $UsuarioVerificacion->setUsumodifId(0);
                        $UsuarioVerificacion->setClasificadorId($ClasificadorPro->clasificadorId);

                        $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                        $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
                        $UsuarioVerificacionMySqlDAO->getTransaction()->commit();


                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $Transaction = $UsuarioMySqlDAO->getTransaction();
                        $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();

                        $token = ($JUMIOSERVICES->accesToken());

                        $data = $JUMIOSERVICES->AccountUpdate($token, $UsuarioMandante, $Usuario->accountIdJumio);
                        $response = array();
                        $response["code"] = 0;
                        $response["rid"] = $json->rid;
                        $response["data"] = $data;
                    }

                } else {

                    $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();
                    $token = ($JUMIOSERVICES->accesToken());

                    $data = $JUMIOSERVICES->AccountCreation($token, $UsuarioMandante);

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = $data;


                }


            } catch
            (Exception $e) {
                if ($e->getCode() == 14) {

                    $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();
                    $token = ($JUMIOSERVICES->accesToken());

                    $data = $JUMIOSERVICES->AccountCreation($token, $UsuarioMandante);

                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = $data;
                }
            }
            break;

        case "AUCO":

            try {

                $UsuarioVerificacion = new UsuarioVerificacion();
                $ClasificadorPro = new Clasificador("", "VERIFICAAUCO");
                $rules = [];
                array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                array_push($rules, array("field" => "usuario_verificacion.estado", "data" => "P", "op" => "eq"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonbetshop = json_encode($filtro);

                $Verificaciones = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*", "usuario_verificacion.usuverificacion_id", "asc", 0, 1, $jsonbetshop, true);
                $Verificaciones = json_decode($Verificaciones);

                if ($Verificaciones->count[0]->{".count"} == 0) {

                    $UsuarioVerificacion = new UsuarioVerificacion();
                    $UsuarioVerificacion->setUsuarioId($Usuario->usuarioId);
                    $UsuarioVerificacion->setMandante($Usuario->mandante);
                    $UsuarioVerificacion->setPaisId($Usuario->paisId);
                    $UsuarioVerificacion->setEstado('P');
                    $UsuarioVerificacion->setTipo('USUVERIFICACION');
                    $UsuarioVerificacion->setObservacion('Pendiente Verificación AUCO');
                    $UsuarioVerificacion->setUsucreaId(0);
                    $UsuarioVerificacion->setUsumodifId(0);
                    $UsuarioVerificacion->setClasificadorId($ClasificadorPro->clasificadorId);

                    $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                    $UsuarioVerificacionId = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
                    $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                    if ($Verificaciones->count[0]->{".count"} > 0) {
                        throw new Exception("tienes una verificacion pendiente", 8791);
                    }

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                    $AUCOSERVICES = new \Backend\integrations\auth\AUCOSERVICES();

                    $data = $AUCOSERVICES->validate($Usuario, $UsuarioVerificacionId);
                    $response = array();
                    $response["code"] = 0;
                    $response["rid"] = $json->rid;
                    $response["data"] = array(
                        "code" => $data->code,
                        "key" => $data->key

                    );

                } else {
                    $response = array();
                    $response["code"] = 1;
                    $response["rid"] = $json->rid;
                    $response["data"] = "Tienes una verificación pendiente por procesar";
                }

            } catch (Exception $e) {

            }

            break;
    }*/
}
