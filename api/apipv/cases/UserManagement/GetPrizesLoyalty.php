<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioLealtad;
use Backend\dto\UsuarioMandante;
use Backend\dto\LealtadInterna;
use Backend\dto\Registro;
use Backend\dto\PuntoVenta;
use Backend\dto\Ciudad;
use Backend\dto\Departamento;

/**
 * Obtener premios de lealtad
 *
 * Este script procesa solicitudes para obtener información sobre premios de lealtad
 * basándose en el ID del regalo y la contraseña proporcionados.
 *
 * @param array $_REQUEST
 * @param string $_REQUEST["Id"]
 * @param string $_REQUEST["Password"]
 *
 * @return array $response
 *   - HasError: boolean, indica si ocurrió un error.
 *   - AlertType: string, tipo de alerta (e.g., "success", "error").
 *   - AlertMessage: string, mensaje de alerta.
 *   - ModelErrors: array, lista de errores del modelo.
 *   - pos: int, posición inicial de los datos.
 *   - total_count: int, número total de registros.
 *   - data: array, información de los usuarios con premios de lealtad.
 *
 * @throws Exception
 *   - "Field: Password", código "50001" si la contraseña está vacía o no coincide.
 *   - "Field: Gift Id", código "50001" si el ID del regalo no es válido.
 *   - "Error BetShop", código "50010" si el usuario no coincide con el punto de venta.
 */

/* Validación de un campo de contraseña en una solicitud; lanza excepción si está vacío. */
$IdGift = $_REQUEST["Id"];
$Password = $_REQUEST["Password"];

if ($Password == "") {
    throw new Exception("Field: Password", "50001");
}


if (is_numeric($IdGift) && $IdGift != "") {


    /* Se crean instancias de clases relacionadas con usuarios y lealtad en un sistema. */
    $configurationEnvironment = new ConfigurationEnvironment();
    $UsuariosLealtad = new UsuarioLealtad($IdGift);
    $LealtadInterna = new LealtadInterna($UsuariosLealtad->getLealtadId());
    $Registro = new Registro("", $UsuariosLealtad->getUsuarioId());

    $Puntoventaentrega = $UsuariosLealtad->puntoventaentrega;


    /* Verifica si el perfil es "PUNTOVENTA" y lanza excepción si no coincide el usuario. */
    if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());
        if ($PuntoVenta->usuarioId !== $Puntoventaentrega) {
            throw new Exception("Error BetShop", "50010");
        }
    }


    /* verifica la coincidencia de un ID con un password descifrado. */
    $prize = $LealtadInterna->descripcion;
    $cedula = $Registro->cedula;

    $Password = $configurationEnvironment->decryptCusNum($Password);
    $Password2 = explode("_", $Password);


    if ($Password2[0] != $UsuariosLealtad->getUsulealtadId()) {

        throw new Exception("Field: Password", "50001");

    } else {


        /* Código que define condiciones para filtrar elementos ordenados por lealtad de usuario. */
        $OrderedItem = "usuario_lealtad.usulealtad_id";
        $OrderType = "desc";

        $rules = [];

        if ($IdGift != "") {

            array_push($rules, array("field" => "usuario_lealtad.usulealtad_id", "data" => $IdGift, "op" => "eq"));
        }


        /* Se define un filtro y se inicializan variables para paginación de datos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $SkeepRows = "";
        $MaxRows = "";

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }


        /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 1000;
        }


        /* se encarga de obtener y decodificar datos de usuarios leales en formato JSON. */
        $json = json_encode($filtro);

        $UsuarioLealtad = new UsuarioLealtad();
        $data = $UsuarioLealtad->getUsuarioLealtadCustom("usuario_lealtad.*,usuario.nombre,usuario_mandante.usumandante_id,lealtad_interna.nombre,lealtad_interna.tipo_premio, lealtad_interna.puntoventa_propio", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, '');

        $data = json_decode($data);


        /* recopila datos y crea un arreglo final con información de usuarios. */
        $final = [];

        foreach ($data->data as $key => $value) {

            $array = [];

            $array["PlayerName"] = $value->{"usuario.nombre"};//Nombre
            $array["State"] = $value->{"usuario_lealtad.estado"};//Estado
            $array["Identification"] = $cedula; //Cedula
            $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"}; //Id de Usuario
            $array["Description"] = $prize; //Descripcion

            array_push($final, $array);
        }


        /* Código que configura una respuesta de éxito sin errores y con conteo de datos. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["pos"] = $SkeepRows;
        $response["total_count"] = $data->count[0]->{".count"};

        /* Asigna el valor de $final a la clave "data" del arreglo $response. */
        $response["data"] = $final;

    }

} else {
    /* Lanza una excepción con mensaje y código si se cumple una condición. */


    throw new Exception("Field: Gift Id ", "50001");

}

















