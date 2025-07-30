<?php
/**
 * Este script proporciona los tipos de cambios históricos de usuarios.
 * 
 * @return array $response Arreglo que contiene:
 * - HasError: Indica si ocurrió un error (true/false).
 * - AlertType: Tipo de alerta (success).
 * - AlertMessage: Mensaje de alerta.
 * - ModelErrors: Lista de errores del modelo.
 * - Data: Lista de campos y sus traducciones asociadas.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


/* Código inicializa una respuesta sin errores para una operación exitosa. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$final = [];


/* Se crea un arreglo con campos y traducciones que se añaden a un arreglo final. */
$array = [];

$array["Fieldname"] = "FirstName";
$array["FieldTranslation"] = "_FirstNameLabel_";
array_push($final, $array);

$array["Fieldname"] = "LastName";

/* Se agrega un campo y su traducción a un arreglo final en PHP. */
$array["FieldTranslation"] = "_FirstNameLabel_";
array_push($final, $array);

$array["Fieldname"] = "Address";
$array["FieldTranslation"] = "_AddressLabel_";
array_push($final, $array);


/* crea un array con nombres y traducciones de campos. */
$array["Fieldname"] = "PasswordHash";
$array["FieldTranslation"] = "_PasswordLabel_";
array_push($final, $array);

$array["Fieldname"] = "EMail";
$array["FieldTranslation"] = "_EmailLabel_";

/* agrega elementos a un arreglo final utilizando arrays asociativos. */
array_push($final, $array);

$array["Fieldname"] = "Phone";
$array["FieldTranslation"] = "_PhoneLabel_";
array_push($final, $array);

$array["Fieldname"] = "IsSuspended";

/* Se crea un array con un campo y se añade a otra colección. */
$array["FieldTranslation"] = "_IsSuspendedLabel_";
array_push($final, $array);


$response["Data"] = $array;