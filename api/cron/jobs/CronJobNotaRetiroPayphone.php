<?php

/**
 *
 *
 * @package ninguno
 * @author Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

use Backend\dto\Usuario;
use Backend\dto\Proveedor;
use Backend\dto\CuentaCobro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\integrations\payout\PAYPHONESERVICES;



/**
 * Clase 'CronJobNotaRetiroPayphone'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobNotaRetiroPayphone
{


    public function __construct()
    {
    }

    public function execute()
    {


        $CuentaCobro = new CuentaCobro();

//$CountFaileds=0;

        $SkeepRows = 0;
        $MaxRows = 100000;

        $Proveedor = new Proveedor("", "PAYPHONEOUT");

        $rules = [];

        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "X", "op" => "eq"));
        array_push($rules, array("field" => "producto.proveedor_id", "data" => "$Proveedor->proveedorId", "op" => "neq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $select = "cuenta_cobro.cuenta_id, cuenta_cobro.transproducto_id";
        $grouping = "cuenta_cobro.cuenta_id";

        $daydimensionFechaPorPago = false;

        $cuentas = $CuentaCobro->getCuentasCobroCustom($select, "cuenta_cobro.cuenta_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping, '', true, $daydimensionFechaPorPago);

        $cuentas = json_decode($cuentas);

        $CuentasCobro = $cuentas->data;

        $PAYPHONESERVICES = new PAYPHONESERVICES();

        foreach ($CuentasCobro as $key => $Id2) {

            $CuentaCobro = new CuentaCobro($CuentasCobro[$key]->{'cuenta_cobro.cuenta_id'});
            $TransId = $CuentasCobro[$key]->{'cuenta_cobro.transproducto_id'};
            $TransProducto = new TransaccionProducto($TransId);

            if ($TransProducto->getEstado() != 'I') {

                $Usuario = new Usuario($CuentaCobro->usuarioId);
                $mandante = $Usuario->mandante;
                $pais = $Usuario->paisId;

                $observacion = $CuentaCobro->observacion;
                $data = json_decode($observacion);
                $batchId = $data->batchId;

                $path = '/api/StorePayments?batchId=' . $batchId . '&page=1';

                $response = $PAYPHONESERVICES->getStatusPayout($path, $mandante, $pais);
                $response = json_decode($response);

                foreach ($response->listPayments as $data) {

                    $TransactionId = $data->reference;
                    $identifier = $data->identifier;
                    $comment = $data->message;

                    $status = 'P';
                    if ($data->status == '') {
                        $status = $data->message;
                    } else {
                        $status = $data->status;
                    }

                    $estado = 'P';
                    switch ($status) {
                        case "Aprobado":
                            $estado = 'A';
                            break;
                        case "P":
                            $estado = 'P';
                            break;
                        case "InvalidPhoneNumber":
                            $estado = 'R';
                            break;
                        case "Número de teléfono inválido":
                            $estado = 'R';
                            break;
                    }

                    if ($estado != "P") {
                        $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();
                        $TransaccionProducto = new TransaccionProducto($TransactionId);

                        if ($TransaccionProducto->getEstado() != 'I') {

                            $Transaction = $TransprodLogMysqlDAO->getTransaction();

                            $TransaccionProducto->setEstado("I");
                            $TransaccionProducto->setEstadoProducto($estado);
                            $TransaccionProducto->setExternoId($identifier);
                            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                            $TransprodLog = new TransprodLog();
                            $TransprodLog->setTransproductoId($TransactionId);

                            if ($comment != "" && $estado == "R") {
                                $TransprodLog->setComentario('Rechazado Solicitud de pago. Respuesta Proveedor: ' . $comment);
                            } elseif ($estado == "A") {
                                //$TransprodLog->setComentario(json_encode($respuesta));
                            } else {
                                $TransprodLog->setComentario('Rechazado Solicitud de pago. Respuesta Proveedor: ' . $comment);
                            }

                            $TransprodLog->setEstado($estado);
                            $TransprodLog->setTipoGenera('A');
                            $TransprodLog->setTValue(json_encode($data));
                            $TransprodLog->setUsucreaId(0);
                            $TransprodLog->setUsumodifId(0);

                            $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

                            $rowsUpdate = 0;
                            $CuentaCobro = new CuentaCobro("", $TransactionId);

                            if ($CuentaCobro->getEstado() == "X") {
                                if ($estado == "A") {
                                    $CuentaCobro->setEstado("I");
                                    $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
                                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                                    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'X') ");
                                }
                                if ($estado == "R") {
                                    $CuentaCobro->setEstado("R");
                                    $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                                    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'X') ");
                                }

                                if ($estado == "R" && $rowsUpdate > 0) {
                                    $Usuario = new Usuario($TransaccionProducto->usuarioId);
                                    $Usuario->creditWin(floatval($CuentaCobro->getValor()) + floatval($CuentaCobro->getImpuesto()), $Transaction);

                                    $UsuarioHistorial = new UsuarioHistorial();
                                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioHistorial->setDescripcion('');
                                    $UsuarioHistorial->setMovimiento('E');
                                    $UsuarioHistorial->setUsucreaId(0);
                                    $UsuarioHistorial->setUsumodifId(0);
                                    $UsuarioHistorial->setTipo(40);
                                    $UsuarioHistorial->setValor($TransaccionProducto->getValor());
                                    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                                }

                                $Transaction->commit();

                                if ($estado == "A") {
                                    $useragent = $_SERVER['HTTP_USER_AGENT'];
                                    $jsonServer = json_encode($_SERVER);
                                    $serverCodif = base64_encode($jsonServer);

                                    $ismobile = '';
                                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                                        $ismobile = '1';
                                    }

                                    $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                                    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                                    $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                                    $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                                    $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

                                    if ($iPod || $iPhone) {
                                        $ismobile = '1';
                                    } else if ($iPad) {
                                        $ismobile = '1';
                                    } else if ($Android) {
                                        $ismobile = '1';
                                    }

                                    //exec("php -f " . __DIR__ . "/../../../src/integrations/crm/AgregarCrm.php " . $CuentaCobro->usuarioId . " " . "RETIROPAGADOCRM" . " " . $CuentaCobro->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");
                                }

                            }

                            print_r("OK");
                        }
                    }
                }
            }

        }
    }
}