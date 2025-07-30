<?php

/**
 * Este archivo contiene un script para verificar sorteos de usuarios en el sistema.
 * Realiza operaciones relacionadas con usuarios, transacciones y sorteos,
 * interactuando con la base de datos y generando detalles específicos.
 *
 * @category Red
 * @package  API
 * @version  1.0.0
 * @since    2025-04-25
 */

/**
 * Variables que contiene:
 *
 * @var mixed $arg1                   Esta variable se utiliza para almacenar y manipular el valor de 'arg1' en el contexto actual.
 * @var mixed $argv                   Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $UsuarioMandante        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $arg2                   Esta variable se utiliza para almacenar y manipular el valor de 'arg2' en el contexto actual.
 * @var mixed $UsuarioRecarga         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $arg3                   Esta variable se utiliza para almacenar y manipular el valor de 'arg3' en el contexto actual.
 * @var mixed $TransaccionProducto    Variable que almacena información sobre una transacción de producto.
 * @var mixed $Usuario2               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Registro               Variable que almacena información sobre un registro.
 * @var mixed $UsuarioRecargaMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $CiudadMySqlDAO         Esta variable se utiliza para almacenar y manipular el valor de 'CiudadMySqlDAO' en el contexto actual.
 * @var mixed $Ciudad                 Variable que almacena el nombre de una ciudad.
 * @var mixed $detalleDepositos       Esta variable se utiliza para almacenar y manipular el valor de 'detalleDepositos' en el contexto actual.
 * @var mixed $detalles               Variable que almacena detalles adicionales o información más específica sobre un proceso o elemento.
 * @var mixed $BonoInterno            Variable que representa un bono interno en el sistema.
 * @var mixed $SorteoInterno          Esta variable se utiliza para almacenar y manipular el valor de 'SorteoInterno' en el contexto actual.
 * @var mixed $respuestaSorteo        Esta variable se utiliza para almacenar y manipular el valor de 'respuestaSorteo' en el contexto actual.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\SorteoInterno;
use Backend\dto\Usuario;
use Backend\dto\TransaccionProducto;
use Backend\dto\BonoInterno;
use Backend\dto\Registro;
use Backend\dto\RuletaDetalle;
use Backend\dto\RuletaInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRuleta;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\RuletaDetalleMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;

ini_set('display_errors', 'OFF');

sleep(2);

/**
 * Obtiene los argumentos pasados al script y los asigna a variables específicas.
 */
$arg1 = $argv[1]; //$UsuarioMandante->usumandanteId 
$arg2 = $argv[2]; //$UsuarioRecarga->recargaId
$arg3 = $argv[3]; //$TransaccionProducto->transproductoId

/**
 * Inicializa objetos relacionados con usuarios, transacciones y registros.
 */
$UsuarioMandante = new UsuarioMandante($arg1);
$UsuarioRecarga = new \Backend\dto\UsuarioRecarga($arg2);
$Usuario2 = new Usuario($UsuarioMandante->usuarioMandante);
$TransaccionProducto = new TransaccionProducto($arg3);

$Registro = new Registro('', $Usuario2->usuarioId);

$UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
$CiudadMySqlDAO = new CiudadMySqlDAO();

/**
 * Carga la información de la ciudad asociada al registro.
 */
$Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);

/**
 * Consulta la cantidad de depósitos realizados por el usuario.
 */
$detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $Usuario2->usuarioId . "'");
$detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];

/**
 * Genera un arreglo con los detalles del usuario y su transacción.
 */
$detalles = array(
    "Depositos" => $detalleDepositos,
    "DepositoEfectivo" => false,
    "MetodoPago" => $TransaccionProducto->productoId,
    "ValorDeposito" => $UsuarioRecarga->valor,
    "PaisPV" => 0,
    "DepartamentoPV" => 0,
    "CiudadPV" => 0,
    "PuntoVenta" => 0,
    "PaisUSER" => $Usuario2->paisId,
    "DepartamentoUSER" => $Ciudad->deptoId,
    "CiudadUSER" => $Registro->ciudadId,
    "MonedaUSER" => $Usuario2->moneda,
);

$BonoInterno = new BonoInterno();
$detalles = json_decode(json_encode($detalles));

/**
 * Verifica si el usuario es elegible para un sorteo basado en los detalles proporcionados.
 */
$SorteoInterno = new SorteoInterno();
$respuestaSorteo = $SorteoInterno->verificarSorteoUsuario($Usuario2->usuarioId, $detalles, 'DEPOSIT', $UsuarioRecarga->recargaId);
