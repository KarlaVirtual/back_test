<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Registro;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;

/**
 * Activar registro de usuario
 *
 * Este script permite buscar y activar registros de usuarios con base en su cédula o correo electrónico.
 *
 * @param string $Cedula Número de cédula del usuario.
 * @param string $Email Correo electrónico del usuario.
 *
 * @return array $response Respuesta en formato JSON con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta o error.
 * - ModelErrors (array): Lista de errores de validación.
 * - Data (array): Información del usuario encontrado, incluyendo:
 *   - Id (string): Identificador del usuario.
 *   - Name (string): Nombre del usuario.
 *   - Country (string): País del usuario.
 *   - Currency (string): Moneda asociada al usuario.
 */

/* obtiene datos del formulario y crea objetos de usuario y configuración. */
$Cedula = $_REQUEST["Cedula"];
$Email = $_REQUEST["Email"];
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

$ConfigurationEnvironment = new ConfigurationEnvironment();


/* depura caracteres de cédula y correo, inicializa variables y un arreglo. */
$Cedula = $ConfigurationEnvironment->DepurarCaracteres($Cedula);
$Email = $ConfigurationEnvironment->DepurarCaracteres($Email);

$SkeepRows = 0;
$MaxRows = 1;

$Data = array();

/* Se crea una nueva instancia de la clase Usuario en la variable $Usuario. */
$Usuario = new Usuario();

if ($Cedula != "") {


    /* Se define un array de reglas para validar condiciones en registros de usuarios. */
    $rules = array();
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));
    array_push($rules, array("field" => "registro.estado_valida", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "registro.cedula", "data" => $Cedula, "op" => "eq"));
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$UsuarioPuntoVenta->paisId", "op" => "eq"));
    array_push($rules, array("field" => "usuario.mandante", "data" => "$UsuarioPuntoVenta->mandante", "op" => "eq"));


    /* Aplica reglas de filtrado a una consulta de usuarios y codifica en JSON. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id,usuario.nombre,pais.pais_nom,usuario.moneda ", "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);


    /* decodifica un JSON y extrae información de usuarios si hay registros. */
    $usuarios = json_decode($usuarios);

    if (intval($usuarios->count[0]->{".count"}) > 0) {

        $Data["Id"] = $usuarios->data[0]->{'usuario.usuario_id'};
        $Data["Name"] = $usuarios->data[0]->{'usuario.nombre'};
        $Data["Country"] = $usuarios->data[0]->{'pais.pais_nom'};
        $Data["Currency"] = $usuarios->data[0]->{'usuario.moneda'};
    }
} elseif ($Email != "") {


    /* Se definen reglas de validación para comparar campos con distintos operadores. */
    $rules = array();
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));
    array_push($rules, array("field" => "registro.estado_valida", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "registro.email", "data" => $Email, "op" => "eq"));
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$UsuarioPuntoVenta->paisId", "op" => "eq"));
    array_push($rules, array("field" => "usuario.mandante", "data" => "$UsuarioPuntoVenta->mandante", "op" => "eq"));


    /* define filtros para obtener usuarios personalizados desde una base de datos. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id,usuario.nombre,pais.pais_nom,usuario.moneda ", "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);


    /* Decodifica datos JSON y asigna información de usuarios a un array. */
    $usuarios = json_decode($usuarios);

    if (intval($usuarios->count[0]->{".count"}) > 0) {
        $Data["Id"] = $usuarios->data[0]->{'usuario.usuario_id'};
        $Data["Name"] = $usuarios->data[0]->{'usuario.nombre'};
        $Data["Country"] = $usuarios->data[0]->{'pais.pais_nom'};
        $Data["Currency"] = $usuarios->data[0]->{'usuario.moneda'};

    }


}


/* asigna valores a un arreglo de respuesta basado en el conteo de datos. */
$response["HasError"] = (oldCount($Data) > 0) ? false : true;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $Data;

/* Asigna el valor de $Data al índice "data" del arreglo $response. */
$response["data"] = $Data;
