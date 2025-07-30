<?php
/**
* Actualizar el valor de transacción
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* @date 20.09.18
* 
*/
use Backend\dto\BonoInterno;
use Backend\dto\Usuario;
use Backend\integrations\payment\SafetyPay;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;

error_reporting(E_ALL);

ini_set("display_errors","ON");

require_once __DIR__ . '../../vendor/autoload.php';

$UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
$BonoInterno = new BonoInterno();

$isdebit=false;
$iscredit=false;
$isRdebit=false;
$isRcredit=true;

if($isdebit){
    $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT transapi_id,t_value,proveedor_id FROM transaccion_api WHERE  tipo='DEBIT' AND valor=0 and proveedor_id =13  LIMIT 1000");

}

if($iscredit){
    $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT transapi_id,t_value,proveedor_id FROM transaccion_api WHERE  tipo='CREDIT' AND valor=0  and proveedor_id =13  LIMIT 1000");

}
if($isRdebit){
    $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT transapi_id,t_value,proveedor_id FROM transaccion_api WHERE  tipo='RDEBIT' AND valor=0 and proveedor_id =13 LIMIT 1000");

}

if($isRcredit){
    $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT transapi_id,t_value,proveedor_id FROM transaccion_api WHERE  tipo='RCREDIT' AND valor=0 and proveedor_id =13 LIMIT 1000");

}
$Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
foreach ($detalleDepositos as $detalleDeposito) {


    switch ($detalleDeposito[2]){
        case "15":
            if($isdebit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->minus . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($iscredit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->plus . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($isRdebit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->minus . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($isRcredit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->plus . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            break;

        case "13":
            if($isdebit) {

                $json = json_decode(($detalleDeposito[1]));

                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->data->amount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($iscredit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->data->amount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($isRdebit) {

                $json = json_decode(($detalleDeposito[1]));

                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->data->amount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($isRcredit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->data->amount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }
            break;

        case "12":

            $detalleDeposito[1]=str_replace('"{','{',$detalleDeposito[1]);
            $detalleDeposito[1]=str_replace('}"','}',$detalleDeposito[1]);


            if($isdebit) {

                $json = json_decode(($detalleDeposito[1]));

                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->debitAmount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($iscredit) {

                $json = json_decode(($detalleDeposito[1]));


                if($json->creditAmount >0){
                    $strsql = ("UPDATE transaccion_api SET valor = '" . $json->creditAmount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");
                    $resp = $BonoInterno->execUpdate($Transaction, $strsql);

                }


            }

            if($isRdebit) {

                $json = json_decode(($detalleDeposito[1]));

                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->debitAmount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($isRcredit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->creditAmount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }
            break;


        case "18":
            $detalleDeposito[1]=str_replace('"{','{',$detalleDeposito[1]);
            $detalleDeposito[1]=str_replace('}"','}',$detalleDeposito[1]);

            if($isdebit) {


                $json = json_decode($detalleDeposito[1]);

                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->BetAmount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($iscredit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->WinAmount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($isRdebit) {

                $json = json_decode(($detalleDeposito[1]));

                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->BetAmount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            if($isRcredit) {

                $json = json_decode(($detalleDeposito[1]));
                $strsql = ("UPDATE transaccion_api SET valor = '" . $json->WinAmount . "' WHERE transapi_id='" . $detalleDeposito[0] . "'");

                $resp = $BonoInterno->execUpdate($Transaction, $strsql);

            }

            break;

    }

}

print_r($strsql);
$Transaction->commit();



