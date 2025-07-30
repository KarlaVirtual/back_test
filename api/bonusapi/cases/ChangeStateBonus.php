<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\integrations\crm\Optimove;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\dto\UsuarioBono;
use Backend\mysql\UsuarioBonoMySqlDAO;

/**
 * bonusapi/cases/ChangeStateBonus
 *
 * Este recurso permite actualizar el estado de un bono interno en el sistema. Cuando se recibe una solicitud
 * con un ID válido y un estado, el recurso verifica el estado actual del bono y, si es necesario, cambia su
 * estado al valor solicitado. Si el bono está siendo desactivado (estado 'I'), también actualiza el estado de
 * los usuarios asociados a dicho bono. Además, realiza una consulta a la base de datos para recuperar información
 * relacionada con el bono y su correspondiente detalle, y procesa la información relacionada a proveedores, si es necesario.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para la operación:
 *   - *Id* (string): ID del bono que se desea actualizar.
 *   - *State* (string): Nuevo estado del bono ('A' para activo, 'I' para inactivo).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista (success, error, etc.).
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Contiene errores específicos si los hubo.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "error",
 * "AlertMessage" => "Mensaje de error",
 * "ModelErrors" => array(),
 *
 * @throws Exception Error general en la ejecución de la operación, como problemas con la base de datos,
 *                   errores al actualizar el bono o cualquier excepción que se pueda generar durante el proceso.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables y verifica una condición de sesión. */
$Id = $params->Id;
$State = $params->State;
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}
$response = array();

/* Se inicializa una variable llamada $error como false para indicar ausencia de errores. */
$error = false;
if ($Id != '' && ($State == 'A' || $State == 'I')) {


    /* Se crea una nueva instancia de la clase BonoInterno utilizando un identificador específico. */
    $BonoInterno = new BonoInterno($Id);

    if ($BonoInterno->estado != $State) {

        /* Actualiza el estado de los bonos de usuarios si el estado es 'I'. */
        $BonoInterno->estado = $State;

        /* Actualiza un objeto en la base de datos y confirma la transacción. */
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $BonoInternoMySqlDAO->update($BonoInterno);
        $BonoInternoMySqlDAO->getTransaction()->commit();


        $SkeepRows = 0;

        /* establece reglas para filtrar datos relacionados con bonos internos. */
        $MaxRows = 2;
        $rules = [];

        array_push($rules, array("field" => "bono_interno.bono_id", "data" => $BonoInterno->bonoId, "op" => "eq"));
        array_push($rules, array("field" => "bono_interno.pertenece_crm", "data" => "S", "op" => "eq"));
        array_push($rules, array("field" => "bono_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));


        /* Crea un filtro JSON para obtener detalles de bono con paginación. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);


        $BonoDetalle = new BonoDetalle();
        $Productos = $BonoDetalle->getBonoDetallesCustom2("bono_detalle.*", "bono_detalle.bonodetalle_id", "desc", $SkeepRows, $MaxRows, $json2, true);

        /* Decodifica un JSON de productos y extrae un valor según condiciones específicas. */
        $Productos = json_decode($Productos);
        $PaisId = "";
        $IsCRM = false;

        foreach ($Productos->data as $key1 => $value1) {


            if ($value1->{"bono_detalle.tipo"} == "CONDPAISUSER") {

                $PaisId = $value1->{"bono_detalle.valor"};


            }

        }


        /*  if($BonoInterno->perteneceCrm == 'S'){



              $Clasificador = new Clasificador("","PROVCRM");


              //$BonoDetalle = new BonoDetalle("",$BonoInterno->bonoId,"CONDPAISUSER");

              $MandanteDetalle = new MandanteDetalle('', $mandanteUsuario, $Clasificador->clasificadorId, $PaisId, 'A');

              $Proveedor = new \Backend\dto\Proveedor($MandanteDetalle->valor);

              switch ($Proveedor->abreviado){
                  case "OPTIMOVE":


                      $BonoId = array(
                          "B".$BonoInterno->bonoId
                      );


                      $Optimove = new Optimove();
                      //$Token = $Optimove->Login();

                      //$Token = $Token->response;
                      $respon = $Optimove->DeletePromotions($mandanteUsuario,$PaisId,$BonoId);

                      break;

                  case "FASTTRACK":

                      break;

                  case "CRMPROPIO":


                      break;
              }


          }*/


        /* Inicializa una respuesta sin errores y con un mensaje de éxito. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    } else {
        /* Verifica una condición; si falla, establece la variable de error como verdadera. */

        $error = true;

    }


} else {
    /* establece una variable de error si no se cumple una condición. */

    $error = true;

}

/* maneja errores, configurando una respuesta de error en una variable. */
if ($error) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}