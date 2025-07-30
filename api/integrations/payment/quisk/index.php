<?php

/**
 * Este archivo implementa la integración con el sistema de pagos Quisk.
 * Procesa solicitudes HTTP entrantes, valida datos, y actualiza información
 * de usuarios en la base de datos utilizando las clases `Usuario` y `UsuarioMySqlDAO`.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log             Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_SERVER         Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body            Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI             Esta variable contiene el URI de la petición actual.
 * @var mixed $data            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $SkeepRows       Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $OrderedItem     Variable que representa un elemento ordenado en una lista.
 * @var mixed $MaxRows         Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $rules           Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro          Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $jsonfiltro      Variable que almacena un filtro en formato JSON.
 * @var mixed $Usuario         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $usuarios        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $key             Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value           Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $UsuarioMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 */

use Backend\dto\Usuario;
use Backend\mysql\UsuarioMySqlDAO;

require(__DIR__ . '/../../../vendor/autoload.php');

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];


if ($body != "") {
    $data = json_decode($body);


    if ($data->token != "") {
        if ($data->phoneNumber != "") {
            if ($data->phoneNumber->phoneNumber != "") {
                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 1;

                $rules = [];
                array_push($rules, array("field" => "usuario.mandante", "data" => 2, "op" => "eq"));

                array_push($rules, array("field" => "registro.celular", "data" => $data->phoneNumber->phoneNumber, "op" => "eq"));
                array_push($rules, array("field" => "usuario.eliminado", "data" => 'N', "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonfiltro = json_encode($filtro);

                $Usuario = new Usuario();

                $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);

                $usuarios = json_decode($usuarios);


                foreach ($usuarios->data as $key => $value) {
                    if ($value->{"usuario.usuario_id"} != "") {
                        $Usuario = new Usuario($value->{"usuario.usuario_id"});
                    }
                }
            }
        }

        if ($data->clientRefId != "") {
            $Usuario = new Usuario($data->clientRefId);
        }


        if ($Usuario->usuarioId != "") {
            $Usuario->tokenQuisk = $data->token;

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();

            $UsuarioMySqlDAO->update($Usuario);
            $UsuarioMySqlDAO->getTransaction()->commit();
            print_r("OK");
        }
    }
}
