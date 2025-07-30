<?php

use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioVerificacion;
use Backend\integrations\auth\SUMSUBSERVICES;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;

/**
 * command/verifica_jumio
 *
 * Proceso de verificación de usuario según proveedor
 *
 * Este código gestiona el proceso de verificación de usuarios mediante distintos proveedores (Jumio, AUCO, SUMSUB),
 * dependiendo de las características del usuario. Los flujos contemplan la verificación de la identidad,
 * la obtención de un token de acceso y la validación de la verificación mediante la conexión con los servicios del proveedor.
 *
 * @param object  $json : Objeto que contiene la información de la solicitud
 * @param string  $json ->session->usuario : Usuario que realiza la solicitud.
 * @param boolean $json ->session->logueado : Si el usuario se encuentra logeado en la plataforma o no.
 *
 * El objeto `$response` es un array con los siguientes atributos:
 *  - *code* (int): Código de respuesta del sistema. 0 para éxito, cualquier otro valor indica un error.
 *  - *rid* (string): Identificador de la solicitud para seguimiento.
 *  - *data* (array): Información adicional sobre la verificación. En caso de éxito, contiene el URL de redirección o el estado de la verificación.
 *
 * @throws Exception tienes una verificacion pendiente (8791)
 * @throws Exception No cuentas con proveedor para verificaciones (300005)
 *
 * @access     public
 * @see        no
 * @since      no
 * @deprecated no
 */


/* activa el modo de depuración si se cumple una condición específica. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

if ($json->session->logueado) {
    /* crea instancias de clases usando datos de sesión y configuración de entorno. */
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
    $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    try {
        /* Condicionales que instancian un objeto Proveedor según el ID del usuario. */
        if ($Usuario->usuarioId == 93504) {
            $Proveedor = new Proveedor('', 'AUCO');
        }
        if ($Usuario->usuarioId == 5398768) {
            $Proveedor = new Proveedor('', 'AUCO');
        } else {
            /* Se crean instancias de clases para manejar información de clasificador y proveedor. */

            $Clasificador = new Clasificador("", "PROVVERIFICA");
            $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->clasificadorId, $UsuarioMandante->paisId, 'A');
            $Proveedor = new Proveedor($MandanteDetalle->valor);
        }


        /* Asigna un proveedor basado en un ID de usuario específico. */
        if ($Usuario->usuarioId == 5398768) {
            $Proveedor = new Proveedor('', 'AUCO');
        }

        switch ($Proveedor->abreviado) {
            case "JUMIO":
                try {
                    /* Se crean instancias de verificación de usuario y clasificador en el sistema. */
                    $UsuarioVerificacion = new UsuarioVerificacion();
                    $ClasificadorPro = new Clasificador("", "VERIFICAJUMIO");

                    if ($Usuario->accountIdJumio != "") {
                        /* Construye un filtro JSON basado en reglas para verificar usuarios. */
                        $rules = [];
                        array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                        array_push($rules, array("field" => "usuario_verificacion.estado", "data" => "I", "op" => "eq"));
                        array_push($rules, array("field" => "verificacion_log.tipo", "data" => "URLREDIRECTION", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $jsonbetshop = json_encode($filtro);


                        /* obtiene verificaciones de usuario y un token de acceso para servicios. */
                        $Verificaciones = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*, verificacion_log.json", "usuario_verificacion.usuverificacion_id", "desc", 0, 1, $jsonbetshop, true);
                        $Verificaciones = json_decode($Verificaciones);

                        $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();
                        $token = ($JUMIOSERVICES->accesToken($UsuarioMandante));

                        $accountId = $Verificaciones->data[0]->{'verificacion_log.json'};


                        /* Decodifica un JSON de accountId y lo imprime si está en modo depuración. */
                        $accountId = json_decode($accountId);

                        if ($_ENV['debug']) {
                            print_r(' accountId ');
                            print_r($accountId);
                            print_r(' accountId2 ');
                        }

                        /* Obtiene el ID de la cuenta y el estado de ejecución del flujo de trabajo. */
                        $Id = $accountId->account->id;

                        $workflowExecutionId = $Verificaciones->data[0]->{'verificacion_log.json'};
                        $workflowExecutionId = json_decode($workflowExecutionId);
                        $IdWorkflow = $workflowExecutionId->workflowExecution->id;

                        $string = $Id . "/workflow-executions/" . $IdWorkflow . "/status";


                        /* realiza una conexión GET y, si está en modo debug, imprime datos. */
                        $Response = $JUMIOSERVICES->connectionGET($string);
                        if ($_ENV['debug']) {
                            print_r($string);
                            print_r(' STATUSPREV ');
                            print_r($Response);
                        }

                        /* verifica el estado de una ejecución y obtiene una URL si está iniciada. */
                        $Response = json_decode($Response);


                        $status = $Response->workflowExecution->status;

                        if ($status == 'INITIATED') {
                            $urlRenew = $Verificaciones->data[0]->{'verificacion_log.json'};
                            $urlRenew = json_decode($urlRenew);
                            $url = $urlRenew->web->href;

                            $response = [
                                "code" => 0,
                                "rid" => $json->rid,
                                "data" => [
                                    "success" => true,
                                    "url" => $url
                                ]
                            ];
                        } else {
                            /* Crea una instancia de usuario de verificación con datos específicos del usuario. */
                            $UsuarioVerificacion = new UsuarioVerificacion();
                            $UsuarioVerificacion->setUsuarioId($Usuario->usuarioId);
                            $UsuarioVerificacion->setMandante($Usuario->mandante);
                            $UsuarioVerificacion->setPaisId($Usuario->paisId);
                            $UsuarioVerificacion->setEstado('I');
                            $UsuarioVerificacion->setTipo('USUVERIFICACION');

                            /* Se establece la observación y los identificadores de usuario para la verificación. */
                            $UsuarioVerificacion->setObservacion('Verificación Iniciada');
                            $UsuarioVerificacion->setUsucreaId(0);
                            $UsuarioVerificacion->setUsumodifId(0);
                            $UsuarioVerificacion->setClasificadorId($ClasificadorPro->clasificadorId);

                            $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();

                            /* Se insertan datos y se confirman transacciones en una base de datos. */
                            $idVer = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
                            $UsuarioVerificacionMySqlDAO->getTransaction()->commit();


                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();

                            /* Código que obtiene un token y actualiza la cuenta usando JUMIOSERVICES. */
                            $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();

                            $token = ($JUMIOSERVICES->accesToken($UsuarioMandante));

                            $data = $JUMIOSERVICES->AccountUpdate($token, $UsuarioMandante, $Usuario->accountIdJumio, $UsuarioVerificacion);
                            $response = array();

                            /* asigna valores a un array de respuesta en formato JSON. */
                            $response["code"] = 0;
                            $response["rid"] = $json->rid;
                            $response["data"] = $data;
                        }
                    } else {
                        /* Se crea un objeto UsuarioVerificacion con datos del usuario y estado inicial. */
                        $UsuarioVerificacion = new UsuarioVerificacion();
                        $UsuarioVerificacion->setUsuarioId($Usuario->usuarioId);
                        $UsuarioVerificacion->setMandante($Usuario->mandante);
                        $UsuarioVerificacion->setPaisId($Usuario->paisId);
                        $UsuarioVerificacion->setEstado('I');
                        $UsuarioVerificacion->setTipo('USUVERIFICACION');

                        /* Se inicializa un objeto UsuarioVerificacion con observaciones y parámetros específicos. */
                        $UsuarioVerificacion->setObservacion('Verificación Iniciada');
                        $UsuarioVerificacion->setUsucreaId(0);
                        $UsuarioVerificacion->setUsumodifId(0);
                        $UsuarioVerificacion->setClasificadorId($ClasificadorPro->clasificadorId);

                        $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();

                        /* Inserta un usuario y crea una cuenta utilizando el servicio JUMIO. */
                        $idVer = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
                        $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                        $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();
                        $token = ($JUMIOSERVICES->accesToken($UsuarioMandante));

                        $data = $JUMIOSERVICES->AccountCreation($token, $UsuarioMandante, $UsuarioVerificacion);


                        /* crea un arreglo de respuesta con un código y datos específicos. */
                        $response = array();
                        $response["code"] = 0;
                        $response["rid"] = $json->rid;
                        $response["data"] = $data;
                    }
                } catch
                (Exception $e) {
                    /* solicita una explicación o resumen en 14 palabras. */

                    if ($e->getCode() == 14) {
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
                        $idVer = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
                        $UsuarioVerificacionMySqlDAO->getTransaction()->commit();
                        $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();
                        $token = ($JUMIOSERVICES->accesToken($UsuarioMandante));

                        $data = $JUMIOSERVICES->AccountCreation($token, $UsuarioMandante, $UsuarioVerificacion);

                        $response = array();
                        $response["code"] = 0;
                        $response["rid"] = $json->rid;
                        $response["data"] = $data;
                    }
                }
                break;

            case "AUCO":

                try {
                    /**
                     * Se crea una nueva instancia de UsuarioVerificacion y de Clasificador.
                     * Se preparan las reglas de filtrado para la verificación del usuario.
                     */
                    $UsuarioVerificacion = new UsuarioVerificacion();
                    $ClasificadorPro = new Clasificador("", "VERIFICAAUCO");
                    $rules = [];
                    array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                    array_push($rules, array("field" => "usuario_verificacion.estado", "data" => "P", "op" => "eq"));
                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $jsonbetshop = json_encode($filtro);

                    // Se obtienen las verificaciones del usuario según el filtro definido.
                    $Verificaciones = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*", "usuario_verificacion.usuverificacion_id", "asc", 0, 1, $jsonbetshop, true);
                    $Verificaciones = json_decode($Verificaciones);

                    // Si no hay verificaciones pendientes, se procede a crear una nueva verificación.
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

                        // Se inserta la nueva verificación en la base de datos.
                        $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                        $UsuarioVerificacionId = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
                        $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                        // Se verifica nuevamente si hay verificaciones pendientes.
                        if ($Verificaciones->count[0]->{".count"} > 0) {
                            throw new Exception("tienes una verificacion pendiente", 8791);
                        }

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $Transaction = $UsuarioMySqlDAO->getTransaction();
                        $AUCOSERVICES = new \Backend\integrations\auth\AUCOSERVICES();

                        // Se valida el usuario mediante el servicio AUCOSERVICES.
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

            case "SUMSUB":
                try {
                    /**
                     * Inicializa la verificación de usuario y clasificador para el proceso
                     * de verificación con SumSub.
                     */
                    $UsuarioVerificacion = new UsuarioVerificacion();
                    $ClasificadorPro = new Clasificador("", "VERIFICASUMSUB");

                    // Verifica si el campo accountIdJumio del usuario no está vacío.
                    if ($Usuario->accountIdJumio != "") {
                        $rules = []; // Inicializa las reglas para el filtro.
                        // Agrega una regla para verificar el id de usuario.
                        array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                        array_push($rules, array("field" => "usuario_verificacion.estado", "data" => "I", "op" => "eq"));
                        array_push($rules, array("field" => "verificacion_log.tipo", "data" => "URLREDIRECTION", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $jsonbetshop = json_encode($filtro);

                        $Verificaciones = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*, verificacion_log.json", "usuario_verificacion.usuverificacion_id", "desc", 0, 1, $jsonbetshop, true);
                        $Verificaciones = json_decode($Verificaciones);

                        $SUMSUBSERVICES = new SUMSUBSERVICES(); // Inicializa el servicio de SumSub.
                        $data = $SUMSUBSERVICES->connectionUrl($UsuarioMandante, $Verificaciones->data[0]); // Obtiene la URL de conexión con SumSub.
                        $url = $data['url'];

                        // Prepara la respuesta con el código, id de referencia y datos de éxito.
                        $response = [
                            "code" => 0,
                            "rid" => $json->rid,
                            "data" => [
                                "success" => true,
                                "url" => $url
                            ]
                        ];
                    } else {
                        $UsuarioVerificacion = new UsuarioVerificacion();
                        $UsuarioVerificacion->setUsuarioId($Usuario->usuarioId);
                        $UsuarioVerificacion->setMandante($Usuario->mandante);
                        $UsuarioVerificacion->setPaisId($Usuario->paisId);
                        $UsuarioVerificacion->setEstado('I');
                        $UsuarioVerificacion->setTipo('USUVERIFICACION');
                        $UsuarioVerificacion->setObservacion('Verificación Iniciada');
                        $UsuarioVerificacion->setUsucreaId(0);
                        $UsuarioVerificacion->setUsumodifId(0);
                        $UsuarioVerificacion->setClasificadorId($ClasificadorPro->clasificadorId);

                        /**
                         * Se crea una instancia de UsuarioVerificacionMySqlDAO para realizar operaciones en la base de datos.
                         */
                        $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                        $idVer = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
                        $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                        $SUMSUBSERVICES = new SUMSUBSERVICES();
                        $data = $SUMSUBSERVICES->connectionUrl($UsuarioMandante, $UsuarioVerificacion);

                        $response = array();
                        $response["code"] = 0;
                        $response["rid"] = $json->rid;
                        $response["data"] = $data;
                    }
                } catch
                (Exception $e) {
                    if ($e->getCode() == 14) {
                        $UsuarioVerificacion = new UsuarioVerificacion();
                        $UsuarioVerificacion->setUsuarioId($Usuario->usuarioId);
                        $UsuarioVerificacion->setMandante($Usuario->mandante);
                        $UsuarioVerificacion->setPaisId($Usuario->paisId);
                        $UsuarioVerificacion->setEstado('I');
                        $UsuarioVerificacion->setTipo('USUVERIFICACION');
                        $UsuarioVerificacion->setObservacion('Verificación Iniciada');
                        $UsuarioVerificacion->setUsucreaId(0);
                        $UsuarioVerificacion->setUsumodifId(0);
                        $UsuarioVerificacion->setClasificadorId($ClasificadorPro->clasificadorId);

                        $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                        $idVer = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
                        $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                        $SUMSUBSERVICES = new SUMSUBSERVICES();
                        $data = $SUMSUBSERVICES->connectionUrl($UsuarioMandante, $UsuarioVerificacion);

                        $response = array();
                        $response["code"] = 0;
                        $response["rid"] = $json->rid;
                        $response["data"] = $data;
                    }
                }
                break;

            default:
                /** Lanza una excepción cuando no se encuentra un proveedor para las verificaciones.
                 * Código de error: 300005*/
                throw new Exception("No cuentas con proveedor para verificaciones.", "300005");
                break;
        }
    } catch (Exception $e) {
    }
}
