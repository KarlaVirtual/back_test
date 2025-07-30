<?php

use Backend\dto\PuntoVenta;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\PuntoVentaMySqlDAO;

/**
 * Configuration/ChangeMyId
 *
 * Cambiar el ID, correo electrónico y teléfono de un usuario.
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param string $params->Id ID del usuario.
 * @param string $params->Email Correo electrónico del usuario.
 * @param string $params->Phone Número de teléfono del usuario.
 * 
 *
 * @return array $response Respuesta con los siguientes atributos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta generada.
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si los datos son inválidos o el usuario no tiene permisos.
 */

/** Inicializa variables de parámetros y configura un arreglo de respuesta.*/
$ID = $params->Id;
$Email = $params->Email;
$Phone = $params->Phone;
$response = [];

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];
$UsuarioLog2MySqlDAO = new \Backend\mysql\UsuarioLog2MySqlDAO();

try {

    // Verifica si los parámetros ID, Email o Phone están vacíos. Si es así, lanza una excepción de dato inválido.
    if (empty($ID) || empty($Email) || empty($Phone)) throw new Exception('Invalid Data', 10000);

    // Comprueba si el perfil del usuario en sesión es uno de los permitidos. Si no lo es, lanza una excepción de usuario rechazado.
    if (!in_array($_SESSION['win_perfil'], ['PUNTOVENTA', 'CONCESIONARIO', 'CONCESIONARIO2', 'CONCESIONARIO3'])) throw new Exception('User Rejected', 20000);

    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
    $PuntoVenta = new PuntoVenta('', $UsuarioMandante->usuarioMandante);
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    // Crea un nuevo objeto UsuarioLog2 para registrar la acción del usuario.
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($PuntoVenta->usuarioId);
    $UsuarioLog->setUsuarioIp('');

    // Establece el ID del usuario que solicita en el log.
    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    // Establece el tipo de operación en el log.
    $UsuarioLog->setTipo("USUCEDULA");
    $UsuarioLog->setEstado("A");
    $UsuarioLog->setValorAntes($PuntoVenta->cedula);
    $UsuarioLog->setValorDespues($ID);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);

    // Inserta el registro de log en la base de datos mediante el DAO correspondiente.
    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    // Depura los caracteres del ID y lo asigna a la propiedad cedula del PuntoVenta.
    $PuntoVenta->cedula = $ConfigurationEnvironment->DepurarCaracteres($ID);

    // Crea un nuevo objeto UsuarioLog2 para registrar otra acción del usuario.
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($PuntoVenta->usuarioId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUEMAIL");
    $UsuarioLog->setEstado("A");
    $UsuarioLog->setValorAntes($PuntoVenta->email);
    $UsuarioLog->setValorDespues($Email);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);

    // Inserta el registro de log del usuario en la base de datos
    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    $PuntoVenta->email = $Email;

    // Crea un nuevo registro de log para el número de teléfono
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($PuntoVenta->usuarioId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUEMAIL");
    $UsuarioLog->setEstado("A");
    $UsuarioLog->setValorAntes($PuntoVenta->telefono);
    $UsuarioLog->setValorDespues($Phone);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);

    // Inserta el registro de log del usuario en la base de datos
    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

    $PuntoVenta->telefono = $Phone;

    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioLog2MySqlDAO->getTransaction());
    $PuntoVentaMySqlDAO->update($PuntoVenta);

    // Confirma la transacción en la base de datos
    $UsuarioLog2MySqlDAO->getTransaction()->commit();

    // Actualiza la sesión con la fecha y hora del último inicio
    $_SESSION['ultimo_inicio'] = date('Y-m-d H:i:s');

    // Configura la respuesta para el cliente
    $response['HasError'] = false;
    $response['AlertType'] = 'Success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];

} catch (Exception $ex) {
    $response['HasError'] = true;
    $response['AlertType'] = 'Error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}
?>