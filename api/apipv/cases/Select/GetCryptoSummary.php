<?php

use Backend\dto\Criptomoneda;
use Backend\dto\ConfigurationEnvironment;

/**
 * Obtiene un resumen de criptomonedas disponibles.
 *
 * @param array $params Parámetros de entrada (no utilizados directamente en este script, pero pueden incluir información de sesión como 'win_perfil' y 'usuario').
 *
 * @return array Retorna un arreglo asociativo con las siguientes claves:
 *   - HasError (bool): Indica si ocurrió un error.
 *   - AlertType (string): Tipo de alerta ('success' en caso exitoso).
 *   - AlertMessage (string): Mensaje descriptivo del resultado.
 *   - data (array): Listado de criptomonedas con 'id' y 'value'.
 *   - Data (array): Alias de 'data'.
 *   - ModelErrors (array): Errores de modelo (vacío si no hay errores).
 *
 * @throws Exception Si el usuario no tiene permiso para consultar las criptomonedas (código 100035).
 */

$ConfigurationEnvironment = new ConfigurationEnvironment();
$permission = $ConfigurationEnvironment->checkUserPermission('CryptoCurrency/GetCurrencies', $_SESSION['win_perfil'], $_SESSION['usuario'], "Cryptos");
if (!$permission) Throw new Exception("Permiso denegado", 100035);

$Criptomoneda = new Criptomoneda();

$select = "criptomoneda.criptomoneda_id, criptomoneda.codigo_iso, criptomoneda.nombre";

$data = $Criptomoneda->getCriptomonedaCustom($select, "criptomoneda.criptomoneda_id", "DESC", 0, 100, "");

$data = json_decode($data);

$data = $data->data;

$currencies = [];
foreach ($data as $value) {
    $currency = [];
    $currency['id'] = $value->{"criptomoneda.criptomoneda_id"};
    $currency['value'] = $value->{"criptomoneda.codigo_iso"} . ' - ' . $value->{"criptomoneda.nombre"};

    $currencies[] = $currency;
}


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Consulta exitosa";
$response["data"] = $currencies;
$response["Data"] = $currencies;
$response["ModelErrors"] = [];