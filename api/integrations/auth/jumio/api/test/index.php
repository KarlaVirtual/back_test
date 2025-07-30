<?php

/**
 * Este archivo contiene un script para procesar y gestionar integraciones de autenticación
 * con Jumio Services, incluyendo la verificación de usuarios y manejo de transacciones.
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
 * @var mixed $body                         Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $UsuarioId                    Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $clasificadorId               Esta variable se utiliza para almacenar y manipular el identificador del clasificador.
 * @var mixed $UsuarioVerificacion          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $rules                        Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                       Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $json                         Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $data                         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $UsuarioVerificacionMySqlDAO  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Transaction                  Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $key                          Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value                        Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $UsuverificacionId            Esta variable se utiliza para almacenar y manipular el identificador del usuario de verificación.
 * @var mixed $e                            Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $jsonbetshop                  Esta variable contiene datos en formato JSON específicos para la integración con Betshop.
 * @var mixed $accountId                    Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $final                        Esta variable se utiliza para indicar si un proceso o estado es final.
 * @var mixed $UsuarioConfiguracion         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $ClasificadorFiltro           Esta variable se utiliza para almacenar y manipular el filtro del clasificador.
 * @var mixed $MandanteDetalle              Esta variable contiene detalles específicos del mandante, utilizados en la configuración del sistema.
 * @var mixed $Mandante                     Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $IsActivePopUp                Esta variable se utiliza para almacenar y manipular el estado de activación de un popup.
 * @var mixed $popup                        Esta variable se utiliza para almacenar y manipular información sobre un popup.
 * @var mixed $abreviado                    Esta variable se utiliza para almacenar y manipular valores abreviados.
 * @var mixed $MSG                          Esta variable se utiliza para almacenar y manipular mensajes en mayúsculas.
 * @var mixed $msg                          Esta variable se utiliza para almacenar y manipular mensajes en minúsculas.
 * @var mixed $UsuarioMandante              Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Usuario                      Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $this                         Esta variable hace referencia al contexto actual dentro de una clase u objeto.
 * @var mixed $clasificador                 Esta variable se utiliza para almacenar y manipular información de clasificación.
 * @var mixed $template                     Esta variable se utiliza para almacenar y manipular la plantilla utilizada.
 * @var mixed $UsuarioMySqlDAO              Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMensaje               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMensajeMySqlDAO       Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $expiryDate                   Esta variable se utiliza para almacenar y manipular la fecha de expiración.
 * @var mixed $Response2                    Esta variable se utiliza para almacenar y manipular una segunda respuesta.
 * @var mixed $UsuarioConfiguracionMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Clasificador                 Esta variable se utiliza para clasificar información dentro del sistema.
 * @var mixed $ClasificadorId               Esta variable se utiliza para almacenar y manipular el identificador del clasificador.
 * @var mixed $Verificaciones               Esta variable almacena resultados o datos relacionados con procesos de verificación.
 * @var mixed $Id                           Esta variable representa un identificador único en el sistema.
 * @var mixed $workflowExecutionId          Esta variable almacena el identificador de ejecución del flujo de trabajo, para seguimiento de procesos.
 * @var mixed $IdWorkflow                   Esta variable representa el identificador del flujo de trabajo en el sistema.
 * @var mixed $string                       Esta variable contiene una cadena de texto, utilizada para representar información textual.
 * @var mixed $JUMIOSERVICES                Esta variable se utiliza en la integración con Jumio Services para procesos de verificación de identidad.
 * @var mixed $token                        Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Response                     Esta variable contiene la respuesta generada por una operación o solicitud.
 */

/*

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\Template;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioVerificacion;
use Backend\integrations\auth\JUMIOSERVICES;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;

error_reporting(E_ALL);
ini_set('display_errors', 'OFF');
header('Content-type: application/json; charset=utf-8');
//Script para rechazar procesos de verificación de identidad


try {

    $body = file_get_contents('php://input');
    $body = json_decode($body);
    $UsuarioId = $body->userId;
    $clasificadorId = $body->clasificadorId;

    $UsuarioVerificacion = new  UsuarioVerificacion();
    $rules = [];
    array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => "$UsuarioId", "op" => "eq"));
    array_push($rules, array("field" => "usuario_verificacion.estado", "data" => "P", "op" => "eq"));
    array_push($rules, array("field" => "usuario_verificacion.clasificador_id", "data" => $clasificadorId, "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $data = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*", "usuario_verificacion.usuverificacion_id", "DESC", 0, 1000, $json, true, '');

    $data = json_decode($data);
    $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
    $Transaction = $UsuarioVerificacionMySqlDAO->getTransaction();
    foreach ($data->data as $key => $value){


        $UsuverificacionId = $value->{"usuario_verificacion.usuverificacion_id"};

        $UsuverificacionId = intval($UsuverificacionId);
        $UsuarioVerificacion = new  UsuarioVerificacion($UsuverificacionId);

        $UsuarioVerificacion->setEstado("R");
        $UsuarioVerificacionMySqlDAO= new UsuarioVerificacionMySqlDAO($Transaction);
        $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
    }
    $Transaction->commit();
    print_r("Guardado");
}catch (Exception $e){
    print_r($e);
}







/*print_r('HOLA SEBAS');
exit();*/
/*
$UsuarioVerificacion = new UsuarioVerificacion();

$rules = [];
array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => 22381, "op" => "eq"));
array_push($rules, array("field" => "usuario_verificacion.estado", "data" => "P", "op" => "eq"));
array_push($rules, array("field" => "verificacion_log.tipo", "data" => "URLREDIRECTION", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonbetshop = json_encode($filtro);

$accountId = json_decode($jsonbetshop);
$final = $accountId->rules[0]->data;

$UsuarioConfiguracion = new UsuarioConfiguracion($final);
$final = 22381;

//Funcion para llamar popups
//ENVIO POPUP ESTADO RECHAZADO POR DATOS - TEMPLATE
$ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
$MandanteDetalle = new MandanteDetalle("", 0, $ClasificadorFiltro->getClasificadorId(), $final, "A");
$Mandante = new Mandante(0);

if ($MandanteDetalle->valor == 1) {
    $IsActivePopUp = "A";
} else {
    $IsActivePopUp = "I";
}

if ($IsActivePopUp == "A") {

    $popup = '';

    $abreviado = "RECPOPUPJUMIOEXTRACCION";
    $MSG = 'Respuesta de Verificación Rechazada por Extracción de Datos';

    $msg = '';
    $UsuarioMandante='';
    $Usuario='';

$this->llamarPopup($abreviado, $msg, $UsuarioMandante,$Usuario);
public function llamarpopup($abreviado, $msg,$UsuarioMandante,$Usuario){

    $clasificador = new Clasificador("",$abreviado);

    $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

    $popup .= $template->templateHtml;
    $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $Transaction = $UsuarioMySqlDAO->getTransaction();
    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
    $UsuarioMensaje = new UsuarioMensaje();
    $UsuarioMensaje->usufromId = 0;
    $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
    $UsuarioMensaje->isRead = 0;
    $UsuarioMensaje->body = $popup;
    $UsuarioMensaje->msubject = $msg;
    $UsuarioMensaje->parentId = 0;
    $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
    $UsuarioMensaje->tipo = "MESSAGEINV";
    $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
    $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
    $Transaction->commit();

}

try {
        $ClasificadorFiltro = new Clasificador("", "EXPIRYDATE");
        //$expiryDate = $Response2->capabilities->extraction[0]->data->expiryDate;
        try {

            $UsuarioConfiguracion = new UsuarioConfiguracion($final, "A", $ClasificadorFiltro->getClasificadorId());

        }catch(Exception $e){

            print_r($e->getCode());

            if ($e->getCode() == 46) {

                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->tipo = $ClasificadorFiltro->clasificadorId;
                $UsuarioConfiguracion->valor = 'lo metio';
                $UsuarioConfiguracion->usuarioId = $final;
                $UsuarioConfiguracion->estado = 'A';

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
            }
        }

} catch
(Exception $e) {
}


try {
    //$Usuario = new Usuario();
    //$UsuarioConfiguracion = new UsuarioConfiguracion($final, 'A',405);

    $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
    $ClasificadorId = $Clasificador->getClasificadorId();
    $UsuarioConfiguracion = new UsuarioConfiguracion($final, 'A', $ClasificadorId);

    print_r('try');

    if ($UsuarioConfiguracion->valor < 3){

        $UsuarioConfiguracion->valor +=  1;

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Transaction = $UsuarioMySqlDAO->getTransaction();
        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $Transaction->commit();

    }else{
        print_r('Ya entro aca');

        if ($UsuarioConfiguracion->valor >= 3) {

            print_r('insertar mensaje');

            $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($final, 'A', $ClasificadorId);

            print_r('vamos bien');
            print_r($UsuarioConfiguracion);

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioConfiguracion->usuarioId;
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = '<div style="margin:50px;">¡Hola, ' . $final . '
                                la verificación de cuenta no ha sido exitosa,
                                por este motivo su cuenta queda inactivada.
                                Por favor contactarse con nuestro equipo de soporte</div>';
            $UsuarioMensaje->msubject = 'Respuesta de Verificación';
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = 0;
            $UsuarioMensaje->tipo = "MESSAGEINV";
            $UsuarioMensaje->paisId = "";
            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();


            print_r('Se inserto');
        }

    }

} catch (Exception $e) {
print_r('catch');
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $Transaction = $UsuarioMySqlDAO->getTransaction();
    $UsuarioConfiguracion = new UsuarioConfiguracion();
    $UsuarioConfiguracion->setUsuarioId($final);
    $UsuarioConfiguracion->setEstado('A');
    $UsuarioConfiguracion->setTipo(410);
    $UsuarioConfiguracion->setValor(1);
    $UsuarioConfiguracion->setUsucreaId(0);
    $UsuarioConfiguracion->setUsumodifId(0);
    $UsuarioConfiguracion->setNota("");
    $UsuarioConfiguracion->setProductoId(0);

    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
    $Transaction->commit();

}

$Verificaciones = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*, verificacion_log.json", "usuario_verificacion.usuverificacion_id", "asc", 0, 1, $jsonbetshop, true);

$Verificaciones = json_decode($Verificaciones);

//$accountId = $Verificaciones->data[0]->{'verificacion_log.json'};
$accountId = $Verificaciones->data[0]->{'usuario_verificacion.usuario_id'};
$accountId = json_decode($accountId);
$Id = $accountId->account->id;

$workflowExecutionId = $Verificaciones->data[0]->{'verificacion_log.json'};
$workflowExecutionId = json_decode($workflowExecutionId);
$IdWorkflow = $workflowExecutionId->workflowExecution->id;

$string = $Id."/workflow-executions/".$IdWorkflow."/status";


$JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();
$token = ($JUMIOSERVICES->accesToken());

$Response = $JUMIOSERVICES->connectionGET($string,$token);
$Response = json_decode($Response);*/