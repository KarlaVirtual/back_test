<?php
/**
* Test
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
error_reporting(E_ALL);
ini_set("display_errors","on");

use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioRecarga;
use Backend\dto\Usuario;
use Backend\imports\Facebook\Facebook;
use Backend\mysql\BonoInternoMySqlDAO;

require_once __DIR__ . '../../vendor/autoload.php';

$Usuarios = array(6440918,6466151,6473147,6523547,6611756,6615796,6664489,6761785,6794230,6801193,6813640,6820533,6822343,6826210,6827296,6833865,6834640,6838705,6839321,6840577,6840750,6843077,6843638,6844376,6844860,6847972,6848418,6848660,6848764,6851677,6852654,6852782,6853053,6853122,6853412,6853692,6854403,6854646,6855035);
foreach ($Usuarios as $usuario) {
    $bonoIdd=23763;
    $Usuario = new Usuario($usuario);
    $Registro = new \Backend\dto\Registro('',$usuario);
    $detalles = array(
        "Depositos" => 0,
        "DepositoEfectivo" => false,
        "MetodoPago" => 0,
        "ValorDeposito" => 0,
        "PaisPV" => 0,
        "DepartamentoPV" => 0,
        "CiudadPV" => 0,
        "PuntoVenta" => 0,
        "PaisUSER" => $Usuario->paisId,
        "DepartamentoUSER" => 0,
        "CiudadUSER" => $Registro->ciudadId,
        "MonedaUSER" => $Usuario->moneda,
        "CodePromo" => ''
    );

    $detalles = json_decode(json_encode($detalles));

    $BonoInterno = new BonoInterno();
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $Transaction = $BonoInternoMySqlDAO->getTransaction();

    $responseBonus = $BonoInterno->agregarBonoFree($bonoIdd, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, "", $Transaction);
print_r($responseBonus);
    if ($responseBonus->WinBonus) {
        $Transaction->commit();
    }
}
exit();
$Usuario = new Usuario(6845828);
$UsuarioRecarga = new UsuarioRecarga(82932057);
$detalles = array(
    "Depositos" => 0,
    "DepositoEfectivo" => true,
    "MetodoPago" => 16437,
    "ValorDeposito" => $UsuarioRecarga->getValor(),
    "PaisPV" => 0,
    "DepartamentoPV" => 0,
    "CiudadPV" => 0,
    "PuntoVenta" => 0,
    "PaisUSER" => $Usuario->paisId,
    "DepartamentoUSER" => 0,
    "CiudadUSER" => 0,
    "MonedaUSER" => $Usuario->moneda,

);

$BonoInterno = new BonoInterno();
$detalles = json_decode(json_encode($detalles));
$BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
$Transaction = $BonoInternoMySqlDAO->getTransaction();

$respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);
print_r($respuestaBono);
$Transaction->commit();



exit();