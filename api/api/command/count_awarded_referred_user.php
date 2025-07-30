<?php
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\PaisMandante;
use Backend\dto\LogroReferido;
use Backend\sql\Transaction;
use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;

/**
 *Obtiene el total de referidos con premios vinculados y disponibles
 * @return array
 *  - code: int Respuesta de la petición
 *  - data.Count: int Cantidad de premios vinculados
 */
$params = $json->params;

//El usuario instanciado es el referente
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$Transaction = new Transaction();

/** Recuperando premios redimibles*/
$sql = "select count(premios.awards) as awards from (select tipo_premio as awards from logro_referido where estado_grupal = 'C' and usuid_referente = ". $Usuario->usuarioId ." group by usuid_referido, tipo_premio) as premios";

/** obtiene la cifra con el total de premios vinculados */
$BonoInterno = new BonoInterno();
$redeemableAwards = $BonoInterno->execQuery($Transaction, $sql);
$countNewAwards = $redeemableAwards[0]->{'.awards'};
$countNewAwards = $countNewAwards ?? '0';


$response["code"] = 0;
$response["data"]["Count"] = $countNewAwards;
?>
