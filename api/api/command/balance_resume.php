<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Se instancia un objeto UsuarioMandante utilizando la información del usuario de la sesión.
 * Se obtiene el identificador del cliente asociado al usuario mandante.
 * Se finaliza la ejecución del script.
 * Se instancia un objeto Usuario con el identificador de cliente obtenido.
 * Se recuperan los movimientos totales resumidos para el usuario.
 * Se decodifican los movimientos obtenidos en formato JSON.
 * Inicializa un array para almacenar datos de movimientos.
 * Inicializa la variable para el saldo en cero.
 */

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ClientId = $UsuarioMandante->getUsuarioMandante();
exit();
$Usuario = new Usuario($ClientId);
$movimientos = $Usuario->getMovimientosTotalResume();

$movimientos = json_decode($movimientos);

$movimientosData = array();
$saldo = 0;

foreach ($movimientos->data as $key => $value) {

/**
 * Array que contiene diferentes tipos de transacciones y sus descripciones asociadas.
 * Las claves representan códigos específicos para cada tipo de transacción.
 *
 * Ejemplo de tipos de transacciones:
 * '0': 'New Bets' - Nuevas apuestas
 * '1': 'Winning Bets' - Apuestas ganadoras
 * '2': 'Returned Bet' - Apuesta devuelta
 * '3': 'Deposit' - Depósito
 * '4': 'Card Deposit' - Depósito con tarjeta
 * '5': 'Bonus' - Bono
 * '6': 'Bonus Bet' - Apuesta con bono
 * '7': 'Commission' - Comisión
 * '8': 'Withdrawal' - Retiro
 * '9': 'Correction Up' - Corrección hacia arriba
 * '302': 'Correction Down' - Corrección hacia abajo
 * '10': 'Deposit by payment system' - Depósito por sistema de pago
 * '12': 'Withdrawal request' - Solicitud de retiro
 * '13': 'Authorized Withdrawal' - Retiro autorizado
 * '14': 'Withdrawal denied' - Retiro denegado
 * '15': 'Withdrawal paid' - Retiro pagado
 * '16': 'Pool Bet' - Apuesta en grupo
 * '17': 'Pool Bet Win' - Ganancia de apuesta en grupo
 * '18': 'Pool Bet Return' - Devolución de apuesta en grupo
 * '23': 'In the process of revision' - En proceso de revisión
 * '24': 'Removed for recalculation' - Eliminado para recálculo
 * '29': 'Free Bet Bonus received' - Bono de apuesta gratuita recibido
 * '30': 'Wagering Bonus received' - Bono de apuestas recibido
 * '31': 'Transfer from Gaming Wallet' - Transferencia desde la billetera de juego
 * '32': 'Transfer to Gaming Wallet' - Transferencia a la billetera de juego
 * '37': 'Declined Superbet' - Superapuesta declinada
 * '39': 'Bet on hold' - Apuesta en espera
 * '40': 'Bet cashout' - Retiro de apuesta
 * '19': 'Fair' - Justo
 * '20': 'Fair Win' - Ganancia justa
 * '21': 'Fair Commission' - Comisión justa
 */

    $array = array();

    switch ($value->{"movimientos.tipo"}) {
        /* Este código maneja una transacción de tipo "DEBIT", asigna un valor de operación de 0 y actualiza el saldo restando el valor de la transacción. */
        case "DEBIT":
            $array["operation"] = 0;
            $saldo = $saldo - ($value->{".valor"});
            break;

        /* Este código maneja una transacción de tipo "CREDIT", asigna un valor de operación de 1 y actualiza el saldo sumando el valor de la transacción.  */
        case "CREDIT":
            $array["operation"] = 1;
            $saldo = $saldo + ($value->{".valor"});
            break;

        /*Este código maneja una transacción de tipo `ROLLBACK`, asigna un valor de operación de 2 y actualiza el saldo sumando el valor de la transacción.*/
        case "ROLLBACK":
            $array["operation"] = 2;
            $saldo = $saldo + ($value->{".valor"});
            break;

        /*Este código maneja una transacción de tipo "BET", asigna un valor de operación de 0 y actualiza el saldo restando el valor de la transacción.*/
        case "BET":
            $array["operation"] = 0;
            $saldo = $saldo - ($value->{".valor"});

            break;

        /*Asigna la operación de disminución de apuesta y actualiza el saldo sumando el valor de la transacción.*/
        case "STAKEDECREASE":
            $array["operation"] = 2;
            $saldo = $saldo + ($value->{".valor"});
            break;

        /*Este código maneja una transacción de tipo `REFUND`, asigna un valor de operación de 2 y actualiza el saldo sumando el valor de la transacción.*/
        case "REFUND":
            $array["operation"] = 2;
            $saldo = $saldo + ($value->{".valor"});
            break;
// Maneja una transacción de tipo "WIN", asigna un valor de operación de 1 y actualiza el saldo sumando el valor de la transacción.
            case "WIN":
                $array["operation"] = 1;
                $saldo = $saldo + ($value->{".valor"});
                break;

            // Maneja una transacción de tipo "NEWCREDIT", asigna un valor de operación de 2 y actualiza el saldo sumando el valor de la transacción.
            case "NEWCREDIT":
                $array["operation"] = 2;
                $saldo = $saldo + ($value->{".valor"});
                break;

            // Maneja una transacción de tipo "CASHOUT", asigna un valor de operación de 40, actualiza el saldo sumando el valor de la transacción y cambia el tipo de movimiento a "WIN".
            case "CASHOUT":
                $array["operation"] = 40;
                $saldo = $saldo + ($value->{".valor"});
                $value->{"movimientos.tipo"}="WIN";
                break;

            // Maneja una transacción de tipo "NEWDEBIT", asigna un valor de operación de 2 y actualiza el saldo restando el valor de la transacción.
            case "NEWDEBIT":
                $array["operation"] = 2;
                $saldo = $saldo - ($value->{".valor"});
                break;

            // Maneja una transacción de tipo "Apuestas", asigna un valor de operación de 0 y actualiza el saldo restando el valor de la transacción.
            case "Apuestas":
                $array["operation"] = 0;
                $saldo = $saldo - ($value->{".valor"});
                break;

            // Maneja una transacción de tipo "Ganadoras", asigna un valor de operación de 1 y actualiza el saldo sumando el valor de la transacción.
            case "Ganadoras":
                $array["operation"] = 1;
                $saldo = $saldo + ($value->{".valor"});
                break;

            // Maneja una transacción de tipo "Depositos", asigna un valor de operación de 3 y actualiza el saldo sumando el valor de la transacción.
            case "Depositos":
                $array["operation"] = 3;
                $saldo = $saldo + ($value->{".valor"});
                break;

            // Maneja una transacción de tipo "Retiros", asigna un valor de operación de 15 y actualiza el saldo restando el valor de la transacción.
            case "Retiros":
                $array["operation"] = 15;
                $saldo = $saldo - ($value->{".valor"});
                break;

            // Maneja una transacción de tipo "RetirosPendientes", asigna un valor de operación de 12 y actualiza el saldo restando el valor de la transacción.
            case "RetirosPendientes":
                $array["operation"] = 12;
                $saldo = $saldo - ($value->{".valor"});
                break;
    }

    /**
     * Se asignan valores a un arreglo utilizando la información extraída del objeto $value.
     *
     * - amount: se asigna el valor de ".valor" del objeto $value.
     * - balance: se asigna el mismo valor que en amount, también de ".valor".
     * - operation_name: se asigna el tipo de movimiento desde "movimientos.tipo".
     * - product_category: se establece en 0, posiblemente como valor por defecto.
     * - transaction_id: se establece en 0, posiblemente como valor por defecto.
     */
    $array["amount"] = ($value->{".valor"});
    $array["balance"] = ($value->{".valor"});
    $array["operation_name"] = ($value->{"movimientos.tipo"});
    $array["product_category"] = 0;
    $array["transaction_id"] = 0;


    array_push($movimientosData, $array);


}

$array = array();

$array["amount"] = $saldo; // Monto de la operación
$array["balance"] = $saldo; // Saldo actual
$array["operation_name"] = "Balance"; // Nombre de la operación
$array["product_category"] = 0; // Categoría del producto
$array["transaction_id"] = 0; // ID de la transacción

// Agrega el array de la operación a la lista de movimientos
array_push($movimientosData, $array);

$response = array(); // Inicializa el array de respuesta
$response["code"] = 0; // Código de estado de la respuesta
$response["rid"] = $json->rid; // Identificador de la solicitud
$response["data"] = array( // Datos de la respuesta
    "resume" => $movimientosData // Resumen de los movimientos
);
