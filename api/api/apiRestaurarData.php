<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */

use Backend\cron\ResumenesCron;
use Backend\dto\Clasificador;
use Backend\dto\CuentaCobro;
use Backend\dto\Mandante;
use Backend\dto\Concesionario;
use Backend\dto\CupoLog;
use Backend\dto\FlujoCaja;
use Backend\dto\ProductoMandante;
use Backend\dto\BonoInterno;
use Backend\dto\LealtadInterna;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\SorteoInterno;
use Backend\dto\Template;
use Backend\dto\Usuario;
use Backend\dto\TransaccionProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLealtad;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioNota;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioRecarga;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\integrations\casino\Game;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\LealtadInternaMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotaMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use \Exception;


error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require('/home/home2/backend/api/vendor/autoload.php');

$cont=0;


$entityBody = file_get_contents(__DIR__.'/transaccion_api.json');
$entityBody=json_decode($entityBody);



$entityBody = file_get_contents(__DIR__.'/transaccion_api.json');
$entityBody=json_decode($entityBody);

foreach ($entityBody as $key => $value) {
    print_r($value);

    }

exit();
foreach ($entityBody as $key => $value) {
    print_r($value);



    $UsuarioMandante = new UsuarioMandante($value->puntoventa_id);
    $UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
    $UsuarioPerfil2 = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());


    $Amount = $value->valor;
//$Amount = -$Amount;
    $Id = $value->cuenta_id;
    $Clave = '';
    $Description = '';
    $tipo = 'E';


    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $Id = $ConfigurationEnvironment->DepurarCaracteres($Id);
    $Clave = $ConfigurationEnvironment->DepurarCaracteres($Clave);


    if ($Id == "" || $Clave == "") {
        throw new Exception("Error en los parametros enviados", "100001");
    }

    $CuentaCobro = new CuentaCobro($Id, "", '');
    $Usuario = new  Usuario($CuentaCobro->getUsuarioId());

    if ($Usuario->paisId == $UsuarioPuntoVenta->paisId && $Usuario->mandante == $UsuarioPuntoVenta->mandante) {


        if($CuentaCobro->version =='2'){
            throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

        }

        if(in_array($UsuarioPuntoVenta->usuarioId,array(1211624,693978,1311554,853460,1784692,1022205))){
            throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

        }

        if ($UsuarioPuntoVenta->paisId == '173' && $UsuarioPuntoVenta->mandante == '0'  && ((date('H:i:s') >= '22:00:00' && date('H:i:s') <= '23:59:59')  || (date('H:i:s') >= '00:00:00'  && date('H:i:s') <= '06:59:59'))) {
            // throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");
        }

        if ($UsuarioPuntoVenta->mandante == '8' && $UsuarioPuntoVenta->mandante == '8'  && ((date('H:i:s') >= '22:00:00' && date('H:i:s') <= '23:59:59')  || (date('H:i:s') >= '00:00:00'  && date('H:i:s') <= '06:59:59'))) {
            throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");
        }

        if ($Usuario->estado != "A") {
            throw new Exception("Error en los parametros enviados", "100001");
        }

        if ($Usuario->contingencia == "A") {
            throw new Exception("Error en los parametros enviados", "100001");
        }

        if ($CuentaCobro->getEstado() != "A") {
            throw new Exception("Error en los parametros enviados", "100001");
        }

        if ($CuentaCobro->getMediopagoId() != "0" && $CuentaCobro->getMediopagoId() == "2") {
            if($CuentaCobro->getMediopagoId() != $UsuarioPuntoVenta->usuarioId){
                throw new Exception("Error en los parametros enviados", "100001");
            }
        }else{
            if ($CuentaCobro->getMediopagoId() != "0") {
                throw new Exception("Error en los parametros enviados", "100001");
            }

        }
        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
        }

        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
        }

        $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);
        $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

        if($UsuarioConfig->maxpagoRetiro != "" && $UsuarioConfig->maxpagoRetiro != "0"){
            if(floatval($UsuarioConfig->maxpagoRetiro) < floatval($CuentaCobro->getValor())){
                throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
            }
        }
        if($UsuarioPuntoVenta->usuarioId != $UsuarioPuntoVenta->puntoventaId ){

            $UsuarioConfigUsuario = new UsuarioConfig($UsuarioPuntoVenta->usuarioId);

            if($UsuarioConfigUsuario->maxpagoRetiro != "" && $UsuarioConfigUsuario->maxpagoRetiro != "0"){
                if(floatval($UsuarioConfigUsuario->maxpagoRetiro) < floatval($CuentaCobro->getValor())){
                    throw new Exception("No es permitido pagar notas de retiro por este valor", "100031");
                }
            }
        }


        $rowsUpdate = 0;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $Transaction = $CuentaCobroMySqlDAO->getTransaction();
        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

        $dirIp = substr($ConfigurationEnvironment->get_client_ip(),0,50);
        $CuentaCobro->setDiripCambio($dirIp);

        $CuentaCobro->setEstado('I');
        $CuentaCobro->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
        $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
        $CuentaCobro->setObservacion($Description);

        $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='A' ");

        if ($rowsUpdate == null || $rowsUpdate <= 0) {
            throw new Exception("Error General", "100000");
        }

        $rowsUpdate = 0;
        $valor = $CuentaCobro->getValorAPagar();
        $FlujoCaja = new FlujoCaja();
        $FlujoCaja->setFechaCrea(date('Y-m-d'));
        $FlujoCaja->setHoraCrea(date('H:i'));
        $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
        $FlujoCaja->setTipomovId('S');
        $FlujoCaja->setValor($valor);
        $FlujoCaja->setTicketId('');
        $FlujoCaja->setCuentaId($CuentaCobro->getCuentaId());
        $FlujoCaja->setMandante($CuentaCobro->getMandante());
        $FlujoCaja->setTraslado('N');
        $FlujoCaja->setRecargaId(0);

        if ($FlujoCaja->getFormapago1Id() == "") {
            $FlujoCaja->setFormapago1Id(0);
        }

        if ($FlujoCaja->getFormapago2Id() == "") {
            $FlujoCaja->setFormapago2Id(0);
        }

        if ($FlujoCaja->getValorForma1() == "") {
            $FlujoCaja->setValorForma1(0);
        }

        if ($FlujoCaja->getValorForma2() == "") {
            $FlujoCaja->setValorForma2(0);
        }

        if ($FlujoCaja->getCuentaId() == "") {
            $FlujoCaja->setCuentaId(0);
        }

        if ($FlujoCaja->getPorcenIva() == "") {
            $FlujoCaja->setPorcenIva(0);
        }

        if ($FlujoCaja->getValorIva() == "") {
            $FlujoCaja->setValorIva(0);
        }
        $FlujoCaja->setDevolucion('');

        $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
        $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

        if ($rowsUpdate == null || $rowsUpdate <= 0) {
            throw new Exception("Error General", "100000");
        }


        $rowsUpdate = 0;
        if ($UsuarioPerfil2->perfilId == "CONCESIONARIO" or $UsuarioPerfil2->perfilId == "CONCESIONARIO2" or $UsuarioPerfil2->perfilId == "CONCESIONARIO3" or $UsuarioPerfil2->perfilId == "PUNTOVENTA" or $UsuarioPerfil2->perfilId == "CAJERO") {

            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($CuentaCobro->getValor(), $Transaction);

        }


        if ($rowsUpdate > 0) {

            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(40);
            $UsuarioHistorial->setValor($CuentaCobro->getValor());
            $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial,'1');


            $Transaction->commit();
        } else {
            throw new Exception("Error General", "100000");
        }
        
    } else {
        throw new Exception("Error General", "100000");
    }

    exit();

}

exit();
foreach ($entityBody as $key => $value) {
    print_r($value);


    if($value->tipo =='0' ){
        $stringg='https://operatorapi.virtualsoft.tech/user/deposit';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $stringg,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $value->t_value,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: AdminID=admin2clone'
            ),
        ));

        $response = curl_exec($curl);
        print_r($response);

        curl_close($curl);
    }
    if($value->tipo =='1' ){
        $stringg='https://operatorapi.virtualsoft.tech/user/withdraw';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $stringg,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $value->t_value,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: AdminID=admin2clone'
            ),
        ));

        $response = curl_exec($curl);
        print_r($response);

    }

}
exit();

foreach ($entityBody as $key => $value) {
    print_r($value);

    try{

        if(in_array($value->puntoventa_id,array(2894342, 853460, 2088007)) ){
            continue;
        }

        $Usuario222 = new Usuario($value->puntoventa_id);
        $UsuarioMandante = new UsuarioMandante('',$Usuario222->usuarioId,$Usuario222->mandante);
        $UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
        $UsuarioPerfil2 = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());
        $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);
        $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

        if($UsuarioPerfil2->perfilId != 'PUNTOVENTA' && $UsuarioPerfil2->perfilId != 'CAJERO'){
            throw new Exception("Error en los parametros enviados", "100001");

        }

        $Amount = $value->valor;
//$Amount = -$Amount;
        $Id = $value->usuario_id;
        $Note = '';
        $Description = '';
        $tipo = 'E';

        $UsuarioPerfil = new UsuarioPerfil($Id);
        $UsuarioPerfil2 = new UsuarioPerfil($Usuario222->usuarioId);
        $Usuario = new Usuario($Id);


        if ($UsuarioPerfil->getPerfilId() == "USUONLINE" && $Usuario->mandante == $UsuarioPuntoVenta->mandante) {

            if($UsuarioConfig->permiteRecarga == "N"){
                throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

            }


            if($Usuario->contingenciaDeposito =='A'){
                throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
            }

            if(in_array($UsuarioPuntoVenta->usuarioId,array(1211624,693978,1311554,853460,1784692,1022205))){
                throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

            }

            if ($UsuarioPuntoVenta->usuarioId != '6290' ) {


                if ($UsuarioPuntoVenta->paisId == '173' && $UsuarioPuntoVenta->mandante == '0' && ((date('H:i:s') >= '22:00:00' && date('H:i:s') <= '23:59:59') || (date('H:i:s') >= '00:00:00' && date('H:i:s') <= '06:59:59'))) {
                    // throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");
                }
            }
            if ($Amount <= 0) {
                throw new Exception("Error en los parametros enviados", "100001");
            }



            if ($Amount < 1 && $UsuarioPuntoVenta->moneda == 'PEN') {
                throw new Exception("No se puede realizar un deposito menor a 1 PEN", "100001");
            }



            if ($Amount < 1 && $UsuarioPuntoVenta->moneda == 'USD') {
                throw new Exception("No se puede realizar un deposito menor a 1 USD", "100001");
            }
            if ($Amount > 10000 && $UsuarioPuntoVenta->moneda == 'USD' && $UsuarioPuntoVenta->mandante == '8') {
                throw new Exception("No se puede realizar un deposito mayor a 100 USD", "21027");
            }

            if ( $UsuarioPuntoVenta->moneda == 'USD' && $UsuarioPuntoVenta->mandante == '8') {

                $recargadoHoy = 0;

                $rules = [];

                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Id, "op" => "eq"));

                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
                //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
                //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioRecarga = new UsuarioRecarga();

                $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_recarga.usuario_id ", "usuario_recarga.usuario_id", "asc", 0  , 5, $json, true, "", "", false);

                $data = json_decode($data);


                foreach ($data->data as $key => $value) {
                    if ($value->{".total"} == "") {
                        $value->{".total"} = 0;
                    }
                    $recargadoHoy = floatval($value->{".total"});
                }

                if (($recargadoHoy + $Amount) > 5000) {
                    throw new Exception("El usuario excedio el valor maximo permitido para recargas por día", "21028");

                }


                $recargadoHoy = 0;

                $rules = [];

                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Id, "op" => "eq"));

                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
                //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
                //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioRecarga = new UsuarioRecarga();

                $data = $UsuarioRecarga->getUsuarioRecargasCustom("  count(usuario_recarga.recarga_id) total, usuario_recarga.usuario_id ", "usuario_recarga.usuario_id", "asc", 0  , 5, $json, true, "", "", false);

                $data = json_decode($data);


                foreach ($data->data as $key => $value) {
                    if ($value->{".total"} == "") {
                        $value->{".total"} = 0;
                    }
                    $recargadoHoy = floatval($value->{".total"});
                }

                if (($recargadoHoy + 1) > 5) {
                    throw new Exception("El usuario excedio la cantidad maxima permitido para recargas por día", "21029");

                }
            }

            if (($UsuarioPerfil2->perfilId == "PUNTOVENTA" || $UsuarioPerfil2->perfilId == "CAJERO") && floatval($PuntoVenta->valorCupo2)>0 ) {

                $recargadoHoy = 0;

                $rules = [];

                array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
                //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
                //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioRecarga = new UsuarioRecarga();

                $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", 0  , 5, $json, true, "", "", false);

                $data = json_decode($data);


                foreach ($data->data as $key => $value) {
                    if ($value->{".total"} == "") {
                        $value->{".total"} = 0;
                    }
                    $recargadoHoy = floatval($value->{".total"});
                }

                if (($recargadoHoy + $Amount) > floatval($PuntoVenta->valorCupo2)) {
                    throw new Exception("Excedio el cupo maximo permitido de recarga. Consulte con su administrador", "100005");

                }

            }


            if (floatval($PuntoVenta->getCreditosBase()) - floatval($Amount) < 0) {
                throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100002");
            }

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {

                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($Id);
                $result = $UsuarioConfiguracion->verifyLimitesDeposito($Amount);

                if ($result != '0') {
                    throw new Exception("Limite de deposito", $result);
                }
            }

            /*  $Consecutivo = new Consecutivo("", "REC", "");


              $consecutivo_recarga = $Consecutivo->numero;*/

            /**
             * Actualizamos consecutivo Recarga
             */

            /*  $consecutivo_recarga++;

              $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

              $Consecutivo->setNumero($consecutivo_recarga);


              $ConsecutivoMySqlDAO->update($Consecutivo);

              $ConsecutivoMySqlDAO->getTransaction()->commit();*/

            $rowsUpdate = 0;

            $UsuarioRecarga = new UsuarioRecarga();
            //$UsuarioRecarga->setRecargaId($consecutivo_recarga);
            $UsuarioRecarga->setUsuarioId($Id);
            $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
            $UsuarioRecarga->setPuntoventaId($UsuarioMandante->getUsuarioMandante());
            $UsuarioRecarga->setValor($Amount);
            $UsuarioRecarga->setPorcenRegaloRecarga(0);
            $dirIp = substr($ConfigurationEnvironment->get_client_ip(),0,40);
            $UsuarioRecarga->setDirIp($dirIp);
            $UsuarioRecarga->setPromocionalId(0);
            $UsuarioRecarga->setValorPromocional(0);
            $UsuarioRecarga->setHost(0);
            $UsuarioRecarga->setMandante($Usuario->mandante);
            $UsuarioRecarga->setPedido(0);
            $UsuarioRecarga->setPorcenIva(0);
            $UsuarioRecarga->setMediopagoId(0);
            $UsuarioRecarga->setValorIva(0);
            $UsuarioRecarga->setEstado('A');

            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
            $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);


            $Registro = new Registro('', $Usuario->usuarioId);

            $CiudadMySqlDAO = new CiudadMySqlDAO();

            $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
            $CiudadPuntoVenta = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);


            $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $Usuario->usuarioId . "'");

            $detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];


            $detalles = array(
                "Depositos" => $detalleDepositos,
                "DepositoEfectivo" => true,
                "MetodoPago" => 0,
                "ValorDeposito" => $UsuarioRecarga->getValor(),
                "PaisPV" => $UsuarioPuntoVenta->paisId,
                "DepartamentoPV" => $CiudadPuntoVenta->deptoId,
                "CiudadPV" => $PuntoVenta->ciudadId,
                "PuntoVenta" => $UsuarioPuntoVenta->puntoventaId,
                "PaisUSER" => $Usuario->paisId,
                "DepartamentoUSER" => $Ciudad->deptoId,
                "CiudadUSER" => $Registro->ciudadId,
                "MonedaUSER" => $Usuario->moneda,

            );

            $BonoInterno = new BonoInterno();
            $detalles = json_decode(json_encode($detalles));

            $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);




            $rowsUpdate = $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

            //$UsuarioRecarga->setRecargaId($consecutivo_recarga);

            $consecutivo_recarga=$UsuarioRecarga->recargaId;

            $rowsUpdate = 0;

            $rowsUpdate = $Usuario->credit($Amount, $Transaction);

            if ($rowsUpdate == null || $rowsUpdate <= 0) {
                throw new Exception("Error General", "100000");
            }


            $rowsUpdate = 0;

            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(10);
            $UsuarioHistorial->setValor($Amount);
            $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $rowsUpdate = $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

            if ($rowsUpdate == null || $rowsUpdate <= 0) {
                throw new Exception("Error General", "100000");
            }

            if($Note != ''){
                $UsuarioMandanteUsuarioOnline = new UsuarioMandante('',$Usuario->usuarioId,$Usuario->mandante);

                $UsuarioNota = new UsuarioNota();
                $UsuarioNota->setTipo(10);
                $UsuarioNota->setDescripcion($Note);
                $UsuarioNota->setUsufromId($_SESSION['usuario2']);
                $UsuarioNota->setUsutoId($UsuarioMandanteUsuarioOnline->usumandanteId);
                $UsuarioNota->setMandante($UsuarioMandanteUsuarioOnline->mandante);
                $UsuarioNota->setPaisId($UsuarioMandanteUsuarioOnline->paisId);
                $UsuarioNota->setRefId($UsuarioRecarga->getRecargaId());
                $UsuarioNota->setUsucreaId($_SESSION['usuario2']);

                $UsuarioNotaMySqlDAO = new UsuarioNotaMySqlDAO($Transaction);
                $UsuarioNotaMySqlDAO->insert($UsuarioNota);
            }

            if($Description != ''){
                $UsuarioMandanteUsuarioOnline = new UsuarioMandante('',$Usuario->usuarioId,$Usuario->mandante);

                $UsuarioNota = new UsuarioNota();
                $UsuarioNota->setTipo(10);
                $UsuarioNota->setDescripcion($Description);
                $UsuarioNota->setUsufromId($_SESSION['usuario2']);
                $UsuarioNota->setUsutoId($UsuarioMandanteUsuarioOnline->usumandanteId);
                $UsuarioNota->setMandante($UsuarioMandanteUsuarioOnline->mandante);
                $UsuarioNota->setPaisId($UsuarioMandanteUsuarioOnline->paisId);
                $UsuarioNota->setRefId($UsuarioRecarga->getRecargaId());
                $UsuarioNota->setUsucreaId($_SESSION['usuario2']);

                $UsuarioNotaMySqlDAO = new UsuarioNotaMySqlDAO($Transaction);
                $UsuarioNotaMySqlDAO->insert($UsuarioNota);
            }

            $rowsUpdate = 0;
            if ($UsuarioPerfil2->perfilId == "CONCESIONARIO" or $UsuarioPerfil2->perfilId == "CONCESIONARIO2"  or $UsuarioPerfil2->perfilId == "CONCESIONARIO3" or $UsuarioPerfil2->perfilId == "PUNTOVENTA" or $UsuarioPerfil2->perfilId == "CAJERO") {

                if ($tipo == "S") {
                    $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);

                } else {
                    $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
                }

                //$PuntoVenta->update($PuntoVenta);

            }

            if ($rowsUpdate == null || $rowsUpdate <= 0) {
                throw new Exception("Error General", "100000");
            }

            $rowsUpdate = 0;

            $FlujoCaja = new FlujoCaja();
            $FlujoCaja->setFechaCrea(date('Y-m-d'));
            $FlujoCaja->setHoraCrea(date('H:i'));
            $FlujoCaja->setUsucreaId($UsuarioMandante->getUsuarioMandante());
            $FlujoCaja->setTipomovId('E');
            $FlujoCaja->setValor($UsuarioRecarga->getValor());
            $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
            $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
            $FlujoCaja->setTraslado('N');
            $FlujoCaja->setFormapago1Id(1);
            $FlujoCaja->setCuentaId('0');

            if ($FlujoCaja->getFormapago2Id() == "") {
                $FlujoCaja->setFormapago2Id(0);
            }

            if ($FlujoCaja->getValorForma1() == "") {
                $FlujoCaja->setValorForma1(0);
            }

            if ($FlujoCaja->getValorForma2() == "") {
                $FlujoCaja->setValorForma2(0);
            }

            if ($FlujoCaja->getCuentaId() == "") {
                $FlujoCaja->setCuentaId('');
            }

            if ($FlujoCaja->getPorcenIva() == "") {
                $FlujoCaja->setPorcenIva(0);
            }

            if ($FlujoCaja->getValorIva() == "") {
                $FlujoCaja->setValorIva(0);
            }

            $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


            $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

            if ($rowsUpdate > 0) {

                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($PuntoVenta->getUsuarioId());
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('S');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(10);
                $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

                if($Usuario->fechaPrimerdeposito == ""){
                    $Usuario->fechaPrimerdeposito=date('Y-m-d H:i:s');
                    $Usuario->montoPrimerdeposito=$UsuarioRecarga->getValor();
                    $UsuarioMySqlDAO2 = new UsuarioMySqlDAO($Transaction);
                    $UsuarioMySqlDAO2->update($Usuario);
                }


                $Transaction->commit();
            } else {
                throw new Exception("Error General", "100000");
            }


            if($Usuario->paisId == "173" && $Usuario->mandante == "0" && $Usuario->test == 'S') {
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

                exec("php -f " . __DIR__ . "/../../../src/integrations/casino/ActivacionRuletaMetodosPagos.php " . $UsuarioMandante->paisId . " " . $UsuarioMandante->usumandanteId . " " . $UsuarioRecarga->valor . " " . 5 . " " . "" . " > /dev/null &");
            }

            if($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 8 || ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == 173)){
                exec("php -f " . __DIR__ . "/../../../src/integrations/casino" . "/AsignarPuntosLealtad.php " . "DEPOSITO" . " " . $UsuarioRecarga->getRecargaId() . " " . 10 . " > /dev/null &");
            }

            $Mandante = new Mandante($Usuario->mandante);
            $pdf = '<head>
    <style>
        body {
            font-family: \'Roboto\', sans-serif;
            text-decoration: none;
            font-size: 14px;
        }

        tr td:first-child {
            text-align: left;
        }

        tr td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>
<div style="width:330px; border:1px solid grey; padding: 15px;">
    <table style="width:100%;height: 355px;">
        <tbody>
        <tr style="width: 50%; display: inline-block;">
            <td align="center" valign="top"><img style="width: 140px; padding-left: 20px;"
                                                 src="' . $Mandante->logoPdf . '" alt="logo">
            </td>
            <td align="center" valign="top" style="display: block;text-align:center;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO<br>DE RECARGA</font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Recibo de Recarga No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $consecutivo_recarga . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioRecarga->getFechaCrea() . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Punto de Venta:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioPuntoVenta->nombre . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->usuarioId . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->nombre . '</font>
            </td>
        </tr>';

            if($UsuarioPuntoVenta->paisId=='2' and $UsuarioPuntoVenta->mandante=="0"){
                $pdf = $pdf .'<tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Cedula Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Registro->cedula . '</font>
            </td>
        </tr>';
            }


            $pdf = $pdf .'
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Email: </font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->login . ' </font>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor recarga :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . '</font></td>
        </tr>
        
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
                </tbody>
    </table>
 <div style="text-align:center;font-size:12px;">' . $Mandante->descripcion . '</font>
        </div>
        <div style="text-align:center;font-size:12px;">Disfruta del juego en vivo</font>
        </div>
        ';


            if ($Usuario->paisId == 173 && $Usuario->mandante == '0') {
                $pdf .= '
        <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
        </div>';

            }

            $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
      <div class="barcodecell" style="  text-align: center;"><barcode code="' . $UsuarioRecarga->getRecargaId() . '" type="I25" class="barcode" /></div>
  </div>
    </div>
</div>
</body>

';

            if(strtolower($UsuarioPuntoVenta->idioma) == 'en'){
                $pdf = str_replace("RECIBO","RECEIPT",$pdf);
                $pdf = str_replace("DE RECARGA","OF DEPOSIT",$pdf);

                $pdf = str_replace("Recibo de Recarga","Deposit Receipt",$pdf);
                $pdf = str_replace("Fecha","Date",$pdf);
                $pdf = str_replace("Punto de Venta","Betshop",$pdf);
                $pdf = str_replace("No. de Cliente","No. of User",$pdf);

                $pdf = str_replace("Nombre Cliente","Name of User",$pdf);
                $pdf = str_replace("Valor recarga","Amount",$pdf);
                $pdf = str_replace("Disfruta del juego en vivo","Enjoy the games",$pdf);

            }




            try{

                if($Usuario->paisId == 173 && $Usuario->mandante =='0' && floatval($UsuarioRecarga->getValor()) >= 500 && $PuntoVenta->propio != 'S' ){
                    try {

                        $message='*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' .$Usuario->moneda . ' '. $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-peru' > /dev/null & ");
                    }catch (Exception $e){

                    }
                }

                if($Usuario->paisId == 66 && $Usuario->mandante =='8' && floatval($UsuarioRecarga->getValor()) >= 125 && $PuntoVenta->propio != 'S' ){
                    try {

                        $message='*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' .$Usuario->moneda . ' '. $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-ecuabet' > /dev/null & ");
                    }catch (Exception $e){

                    }
                }


                if($Usuario->paisId == 60 && $Usuario->mandante =='0' && floatval($UsuarioRecarga->getValor()) >= 80300 && $PuntoVenta->propio != 'S' ){
                    try {

                        $message='*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' .$Usuario->moneda . ' '. $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-costarica' > /dev/null & ");
                    }catch (Exception $e){

                    }
                }


                if($Usuario->paisId == 146 && floatval($UsuarioRecarga->getValor()) >= 2578 && $PuntoVenta->propio != 'S' ){
                    try {

                        $message='*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' .$Usuario->moneda . ' '. $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-netabet' > /dev/null & ");
                    }catch (Exception $e){

                    }
                }


                if($Usuario->paisId == 46 && $Usuario->mandante =='0' && floatval($UsuarioRecarga->getValor()) >= 110000 && $PuntoVenta->propio != 'S' ){
                    try {

                        $message='*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' .$Usuario->moneda . ' '. $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-chile' > /dev/null & ");
                    }catch (Exception $e){

                    }
                }


            }catch (Exception $e){

            }

            try{
                if($Usuario->mandante == "8") {

                    $SorteoInterno = new SorteoInterno();
                    $respuestaSorteo = $SorteoInterno->verificarSorteoUsuario($UsuarioRecarga->usuarioId, $detalles, 'DEPOSIT', $UsuarioRecarga->recargaId);

                }
            }catch (Exception $e){

            }

            if($Usuario->test =='S' && $Usuario->mandante =='0' && $Usuario->paisId =='94' ){
                $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
                $UsuarioMandante = new \Backend\dto\UsuarioMandante('',$Usuario->usuarioId,$Usuario->mandante);
                $mensaje_txt=$Mandante->nombre.' le informa deposito a su cuenta por '.$Usuario->moneda.' '.$UsuarioRecarga->valor.' ID del depósito ('.$UsuarioRecarga->recargaId.')';

                $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
                //Envia el mensaje de correo
                $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0,$UsuarioMandante);
                $cambios=true;
            }

        } else {
            throw new Exception("Error General", "100000");
        }

    }catch (\Exception $e){
        print_r($e);

    }
}
exit();


foreach ($entityBody as $key => $value) {


        print_r($value);
        $Type='1';
        $Usuario = new Usuario($value->usucrea_id);
        $UsuarioMandante = new UsuarioMandante('',$Usuario->usuarioId,$Usuario->mandante);
        $userfrom = $UsuarioMandante->getUsuarioMandante();
        $userto = $value->usuario_id;

        $Id = $value->usuario_id;
    $Amount=$value->valor;
    $Note='';
    $tipo=$value->tipo_id;

        if ($Type == 0) {
            $tipoCupo = 'R';
        } else {
            $tipoCupo = 'A';

        }


        $CupoLog = new CupoLog();
        $CupoLog->setUsuarioId($userto);
        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
        $CupoLog->setTipoId($tipo);
        $CupoLog->setValor($Amount);
        $CupoLog->setUsucreaId($userfrom);
        $CupoLog->setMandante(0);
        $CupoLog->setTipocupoId($tipoCupo);
        $CupoLog->setObservacion($Note);


        $CupoLogMySqlDAO = new CupoLogMySqlDAO();
        $Transaction = $CupoLogMySqlDAO->getTransaction();
        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

        $CupoLogMySqlDAO->insert($CupoLog);

        $SaldoRecargas = 0;
        $SaldoJuego = 0;

        $ConcesionarioU = new Concesionario($Id,'0');
        $ConcesionarioUUU = new \Backend\dto\UsuarioPerfil($UsuarioMandante->getUsuarioMandante());

       



        if ($ConcesionarioUUU->perfilId == "CONCESIONARIO" or $ConcesionarioUUU->perfilId == "CONCESIONARIO2" or $ConcesionarioUUU->perfilId == "CONCESIONARIO3") {

            $PuntoVentaSuper = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

            if (($PuntoVentaSuper->getCreditosBase() < $Amount && $Type == 1 && $tipo != "S") || ($PuntoVentaSuper->getCupoRecarga() < $Amount && $Type == 0 && $tipo != "S")) {
                throw new Exception("No tiene saldo para transferir", "111");
            } else {

            }


            $PuntoVenta = new PuntoVenta("", $Id);

            if ($tipo == "S") {
                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga(-$Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCupoRecarga($Amount, $Transaction);
                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCreditosBase($Amount, $Transaction);

                }

            } else {
                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCupoRecarga(-$Amount, $Transaction);

                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);
                    $cant2 = $PuntoVentaSuper->setBalanceCreditosBase(-$Amount, $Transaction);

                }

            }

            if ($cant == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }


            if ($cant2 == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }
            //$PuntoVentaMySqlDAO->update($PuntoVenta);
            // $PuntoVentaMySqlDAO->update($PuntoVentaSuper);
        } else {
            $PuntoVenta = new PuntoVenta("", $Id);

            if ($tipo == "S") {
                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga(-$Amount, $Transaction);
                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);

                }

            } else {
                if ($Type == 0) {
                    $cant = $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);
                } else {
                    $cant = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);

                }

            }


            if ($cant == 0) {
                throw new Exception("No tiene saldo para transferir", "111");
            }

            //$PuntoVentaMySqlDAO->update($PuntoVenta);

        }

        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor($CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

        if ($ConcesionarioUUU->perfilId == "CONCESIONARIO" or $ConcesionarioUUU->perfilId == "CONCESIONARIO2" or $ConcesionarioUUU->perfilId == "CONCESIONARIO3") {

            $tipoConce = 'E';

            if ($CupoLog->getTipoId() == "E") {
                $tipoConce = 'S';
            }
            $UsuarioHistorial2 = new UsuarioHistorial();
            $UsuarioHistorial2->setUsuarioId($CupoLog->getUsucreaId());
            $UsuarioHistorial2->setDescripcion('');
            $UsuarioHistorial2->setMovimiento($tipoConce);
            $UsuarioHistorial2->setUsucreaId(0);
            $UsuarioHistorial2->setUsumodifId(0);
            $UsuarioHistorial2->setTipo(60);
            $UsuarioHistorial2->setValor($CupoLog->getValor());
            $UsuarioHistorial2->setExternoId($CupoLog->getCupologId());

            $UsuarioHistorialMySqlDAO2 = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO2->insert($UsuarioHistorial2, '1');
        }
        print_r($CupoLog);
    $Transaction->commit();
    $cont++;

    if($cont>1){
        //exit();
    }

    print_r($cont);

}


exit();
foreach ($entityBody as $key => $value) {
    try{


    print_r($value);
    $UsuarioMandante = new UsuarioMandante($value->usuario_id);
        /*  Obtenemos el producto con el gameId  */
        
    $ProductoMandante = new ProductoMandante('','',$value->producto_id);
    $Producto = new Producto($ProductoMandante->productoId);

    $Proveedor = new Proveedor($Producto->proveedorId);



    /*  Creamos la Transaccion API  */
    $transaccionApi = new TransaccionApi();
    $transaccionApi->setTransaccionId($value->transaccion_id);
    $transaccionApi->setTipo($value->tipo);
    $transaccionApi->setProveedorId($value->proveedor_id);
    $transaccionApi->setTValue($value->t_value);
    $transaccionApi->setUsucreaId(0);
    $transaccionApi->setUsumodifId(0);
    $transaccionApi->setValor($value->valor);
    $transaccionApi->setIdentificador($value->identificador);

    $Game = new Game();

    $isfreeSpin=false;

    if(strpos($value->tipo,'ROLLBACK') !== false){
        $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $transaccionApi);
    }elseif(strpos($value->tipo,'DEBIT') !== false){
        $responseG = $Game->debit($UsuarioMandante, $Producto, $transaccionApi,false);

    }elseif(strpos($value->tipo,'CREDIT') !== false){
        $responseG = $Game->credit($UsuarioMandante, $Producto, $transaccionApi, true,false,false);

    }
    $transaccionApi = $responseG->transaccionApi;

    $saldo = $responseG->saldo;

    $respuesta = json_encode(array(
        "transactionId" => $responseG->transaccionId,
        "balance" => $saldo
    ));
        print_r($respuesta);

    }catch (Exception $e){

    }
    $cont++;

    if($cont>10){
        //exit();
    }

    print_r($cont);

}
