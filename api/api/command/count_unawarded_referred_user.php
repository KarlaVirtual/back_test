<?php
use Backend\dto\UsuarioMandante;
use Backend\dto\BonoInterno;
use Backend\sql\Transaction;
use Backend\dto\Usuario;
use Backend\dto\LogroReferido;

/**
 * Obtiene el total de referidos sin premios disponibles respecto a un referente
 * @param $json, objeto json con la información de la sesión y el usuario referente
 *
 * @return $response, objeto json con el código de éxito y el conteo de referidos sin premios
 *  -code : int Estado de la solicitud
 *  -data : array Conteo de referidos sin premios
 */

$Transaction = new Transaction();
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

/** Se solicita el id de los referidos que tienen algún logro pendiente*/
$sql = "select count(pendientes.referidoPendiente) as referidoPendiente from (select usuid_referido as referidoPendiente from logro_referido where usuid_referente = ".$Usuario->usuarioId." and (logro_referido.fecha_expira is null or logro_referido.fecha_expira >= now()) group by usuid_referido having sum(if(estado_grupal = 'P', 1, 0)) > 0 order by usuid_referido desc) as pendientes";

$BonoInterno = new BonoInterno();
$unawardedUsers = $BonoInterno->execQuery($Transaction, $sql);
$countUnawardedUsers = $unawardedUsers[0]->{'.referidoPendiente'};
$countUnawardedUsers = $countUnawardedUsers ?? '0';

/**
 * Se prepara la respuesta para la solicitud, con el código de éxito
 * y el conteo de usuarios no premiados.
 */
$response["code"] = 0;
$response["data"]["Count"] = $countUnawardedUsers;
?>