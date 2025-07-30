<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\SitioTracking;
use Backend\dto\SorteoInterno;
use Backend\dto\Submenu;
use Backend\dto\Template;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNota;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotaMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Dompdf\Dompdf;

/**
 * Client/MakeDeposit
 *
 * Este script realiza un depósito a un usuario desde un punto de venta o cajero.
 *
 * @param object $params Objeto JSON decodificado con las siguientes propiedades:
 * @param float $params ->Amount Monto del depósito.
 * @param int $params ->Id ID del usuario al que se realizará el depósito.
 * @param string $params ->Note Nota adicional sobre el depósito.
 * @param string $params ->Description Descripción del depósito.
 *
 *
 *
 * @return array $response Respuesta en formato JSON con las siguientes propiedades:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta o error.
 * - ModelErrors (array): Lista de errores de validación.
 * - Pdf (string): Archivo PDF codificado en base64 con el comprobante de la operación.
 * - PdfPOS (string): Archivo PDF para impresión en punto de venta.
 * - id (int): ID del depósito generado.
 *
 * @throws Exception Si ocurre un error durante el depósito o si se violan las reglas de negocio.
 */


/* Se crean objetos de usuario y configuración, verificando roles según perfil. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
$UsuarioPerfil2 = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());
$PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);
$UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);

if ($UsuarioPerfil2->perfilId != 'PUNTOVENTA' && $UsuarioPerfil2->perfilId != 'CAJERO') {
    throw new Exception("Error en los parametros enviados", "100001");

}


/* asigna valores de parámetros a variables específicas para su uso posterior. */
$Amount = $params->Amount;
//$Amount = -$Amount;
$Id = $params->Id;
$Note = $params->Note;
$Description = $params->Description;
$tipo = 'E';


//verificamos si tiene limite de depositos diario configurado

/* inicializa objetos para gestionar configuraciones de usuario y clasificador. */
$Clasificador = new Clasificador("", "DAYLILIMITPV");
try {
    $UsuarioMandante = new UsuarioMandante("", $_SESSION['usuario'], $_SESSION['mandante']);
    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), 'A', $Clasificador->getClasificadorId(), 1);
    $limitDeposit = $UsuarioConfiguracion->getValor();

} catch (Exception $e) {
    /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */

}


// verificamos si hay transaccion sin comision activo



try {

    $Clasificador = new Clasificador("","COMMISIONSFREETRANSACTION"); /*realizamos una instancia a la clase clasificador para obtener el clasificador id */
    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A'); /*Realizamos una instancia a la clase MandanteDetalle para conocer si hay transaccion sin comision en el partner y en el pais*/
    $Valor = $MandanteDetalle->getValor(); /*obtenermos el resultado si esta activo o inactivo transaccion sin comision*/


}catch (Exception $e){

}



/**
 * Verifica el estado de la contingencia para depósitos en puntos de venta.
 *
 * Este bloque de código intenta obtener el estado de la contingencia para depósitos
 * en puntos de venta del usuario. Si ocurre una excepción, se establece el estado como inactivo ("I").
 * en caso de tener una contingencia activa se deja el intento fallido en auditoria general
 */


try {
    $Clasificador = new Clasificador('', 'CONTINGENCYRETAIL');
    $UsuarioConfiguracion = new UsuarioConfiguracion($Id, 'A', $Clasificador->getClasificadorId());
    $Contingencia = $UsuarioConfiguracion->getEstado();
} catch (Exception $e) {
    $Contingencia = "I";
}

if($Contingencia == "A"){


    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($Id);
    $AuditoriaGeneral->setUsuarioIp("");
    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuariosolicitaIp("");
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("FALLOENDEPOSITOPV");
    $AuditoriaGeneral->setValorAntes(0);
    $AuditoriaGeneral->setValorDespues(0);
    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsumodifId(0);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setObservacion('intento fallido por punto de venta para depositar ');

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();


    // Si la contingencia está activa, lanzamos una excepción indicando que la cuenta tiene una restricción.
    throw new Exception("Esta cuenta tiene una restricción activa. El usuario debe comunicarse con soporte para más información.", "300167");
}



try{
    $Clasificador = new Clasificador("","RESTRICTIONTIME"); /*realizamos una instancia a la clase clasificador para obtener el clasificador id */
    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A'); /* Realizamo una instancia a la clase MandanteDetalle  para conocer si hay restriccion de tiempo en el partner y en el pais*/
    $tiempo = $MandanteDetalle->getValor(); /*obtenermos el resultado si esta activo o inactivo restriccion de tiempo*/
}catch (Exception $e){

}





/*verificamos si el usuario ha excedido el tiempo limite desde su ultima nota de retiro*/

if($tiempo != "" and $tiempo != null and $Valor == "A") {

    $usuarioId = $Id;

    $rules = [];
    array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $usuarioId, "op" => "eq"));
    array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $CuentaCobro2 = new CuentaCobro();
    $datos = $CuentaCobro2->getCuentasCobroCustom("cuenta_cobro.cuenta_id,cuenta_cobro.fecha_pago,cuenta_cobro.fecha_crea", "cuenta_cobro.cuenta_id", "desc", 0, 1, $json, true, "");


    $datos = json_decode($datos);


    $final = [];

    foreach ($datos->data as $key => $value) {
        $array = [];
        $array["CreatedDate"] = $value->{"cuenta_cobro.fecha_pago"};
        array_push($final, $array);
    }


    if (!empty($final)) {
        $fecha1 = $final[0]["CreatedDate"];

        $fechaRetiro = new DateTime($fecha1);
        $ahora = new DateTime();
        $comision = false;

        /* Calculamos la diferencia en minutos*/
        $diferencia = $ahora->getTimestamp() - $fechaRetiro->getTimestamp();
        $diferenciaEnMinutos = $diferencia / 60;

        $diferenciaEnMinutos = round($diferencia / 60);

        /* Verificamos si ya pasaron los minutos definidos*/
        if ($diferenciaEnMinutos >= $tiempo) {
            $comision = true;
        }

    }
}


try {

    /* Código instancia clases para obtener configuraciones y límites de depósitos de un usuario. */
    $Clasificador = new Clasificador("", "LIMITEDEPOSITOSIMPLE");
    $ClasificadorId = $Clasificador->getClasificadorId();
    $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId, '', '');
    $limitDeposit = $UsuarioConfiguracion->getValor();
    $FromDateLocal = $UsuarioConfiguracion->getFechaCrea();
    $ToDateLocal = $UsuarioConfiguracion->getFechaFin();

    /* Crea un filtro de reglas en formato JSON para consultas de base de datos. */
    $rules = [];
    array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Id, "op" => "eq"));
    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    /* Suma los valores de recargas de usuario y verifica límite de depósito. */
    $select = " SUM(usuario_recarga.valor) valor ";
    $UsuarioRecarga = new UsuarioRecarga();
    $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", 0, 1, $json, true);
    $transacciones = json_decode($transacciones);

    if ((floatval($transacciones->data[0]->{'.valor'}) + $Amount) > floatval($limitDeposit)) {
        throw new Exception("Limite de deposito.", '20008');
    }
} catch (Exception $e) {
    /* Manejo de excepciones que re-lanza si el código de error es 20008. */

    if ($e->getCode() == 20008) {
        throw $e;
    }
}


/* Verifica si el usuario excede el límite diario de depósitos en un punto de venta. */
if ($UsuarioConfiguracion) {

    $BonoInterno = new BonoInterno();
    $sqlSum = "SELECT SUM(valor) AS suma_del_dia
    FROM usuario_recarga
    WHERE DATE(fecha_crea) = CURDATE()
    AND puntoventa_id = {$UsuarioMandante->getUsuarioMandante()}";

    $sumDailyDeposit = $BonoInterno->execQuery('', $sqlSum);
    if (floatval($sumDailyDeposit[0]->{".suma_del_dia"}) + $Amount > floatval($limitDeposit)) {
        throw new Exception("Limite de depositos diario para punto de venta superado", 300019);
    }
}


/* valida un depósito y lanza una excepción si no es permitido. */
$UsuarioPerfil = new UsuarioPerfil($Id);
$Usuario = new Usuario($Id);

try {
    $Clasificador = new Clasificador("", "CASHIERDEPOSIT");

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor == "A") {
        throw new Exception("No es posible realizar depositos en este momento", "300006");
    }

} catch (Exception $e) {
    /* maneja excepciones, ignorando códigos de error 34 y 41. */

    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
} //validacion de depositos cajero


if ($UsuarioPerfil->getPerfilId() == "USUONLINE" && $Usuario->mandante == $UsuarioPuntoVenta->mandante) {


    /* verifica permisos y lanza excepciones si no están autorizados. */
    if ($Usuario->bloqueoVentas == "S") {
        throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

    }
    if ($UsuarioConfig->permiteRecarga == "N") {
        throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

    }


    /* verifica condiciones de exclusión y autorización del usuario, lanzando excepciones en caso contrario. */
    if ($Usuario->contingenciaDeposito == 'A') {
        throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
    }

    if (in_array($UsuarioPuntoVenta->usuarioId, array(1211624, 693978, 1311554, 853460, 1784692, 1022205))) {
        throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

    }


    /* bloquea operaciones específicas según usuario, país y horario. */
    if ($UsuarioPuntoVenta->usuarioId != '6290') {


        if ($UsuarioPuntoVenta->paisId == '173' && $UsuarioPuntoVenta->mandante == '0' && ((date('H:i:s') >= '00:00:00' && date('H:i:s') <= '06:59:59'))) {
            throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");
        }
    }

    /* verifica condiciones de monto y lanza excepciones si no son válidas. */
    if ($Amount <= 0) {
        throw new Exception("Error en los parametros enviados", "100001");
    }


    if ($Amount < 1 && $UsuarioPuntoVenta->moneda == 'PEN') {
        throw new Exception("No se puede realizar un deposito menor a 1 PEN", "100001");
    }


    /* Valida montos de depósito en USD según condiciones específicas y lanza excepciones. */
    if ($Amount < 1 && $UsuarioPuntoVenta->moneda == 'USD') {
        throw new Exception("No se puede realizar un deposito menor a 1 USD", "100001");
    }
    if ($Amount > 10000 && $UsuarioPuntoVenta->moneda == 'USD' && $UsuarioPuntoVenta->mandante == '8') {
        throw new Exception("No se puede realizar un deposito mayor a 100 USD", "21027");
    }

    if ($UsuarioPuntoVenta->moneda == 'USD' && $UsuarioPuntoVenta->mandante == '8') {


        /* Código para establecer reglas que filtran recargas de usuario por fecha y ID. */
        $recargadoHoy = 0;

        $rules = [];

        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Id, "op" => "eq"));

        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
        //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
        //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


        /* crea un filtro JSON y recupera datos de recargas de usuario. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $UsuarioRecarga = new UsuarioRecarga();

        $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_recarga.usuario_id ", "usuario_recarga.usuario_id", "asc", 0, 5, $json, true, "", "", false);


        /* Decodifica JSON y asigna 0 si ".total" está vacío, luego convierte a float. */
        $data = json_decode($data);


        foreach ($data->data as $key => $value) {
            if ($value->{".total"} == "") {
                $value->{".total"} = 0;
            }
            $recargadoHoy = floatval($value->{".total"});
        }


        /* verifica si la suma de recargas diarias excede 5000, lanzando una excepción. */
        if (($recargadoHoy + $Amount) > 5000) {
            throw new Exception("El usuario excedio el valor maximo permitido para recargas por día", "21028");

        }


        $recargadoHoy = 0;


        /* Se crean reglas de filtro para consultas basadas en condiciones específicas. */
        $rules = [];

        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Id, "op" => "eq"));

        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
        //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
        //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* obtiene y decodifica datos de recargas de usuarios en formato JSON. */
        $json = json_encode($filtro);

        $UsuarioRecarga = new UsuarioRecarga();

        $data = $UsuarioRecarga->getUsuarioRecargasCustom("  count(usuario_recarga.recarga_id) total, usuario_recarga.usuario_id ", "usuario_recarga.usuario_id", "asc", 0, 5, $json, true, "", "", false);

        $data = json_decode($data);


        /* Itera sobre datos, asignando cero si ".total" está vacío, y convierte a float. */
        foreach ($data->data as $key => $value) {
            if ($value->{".total"} == "") {
                $value->{".total"} = 0;
            }
            $recargadoHoy = floatval($value->{".total"});
        }


        /* Lanza una excepción si las recargas diarias superan el límite permitido de 5. */
        if (($recargadoHoy + 1) > 5) {
            throw new Exception("El usuario excedio la cantidad maxima permitido para recargas por día", "21029");

        }
    }

    if (($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") && floatval($PuntoVenta->valorCupo2) > 0) {


        /* Define reglas para filtrar recargas de usuario según fecha y punto de venta. */
        $recargadoHoy = 0;

        $rules = [];

        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
        //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
        //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


        /* crea un filtro JSON y obtiene recargas de usuarios con ciertas condiciones. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $UsuarioRecarga = new UsuarioRecarga();

        $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", 0, 5, $json, true, "", "", false);


        /* Decodifica JSON y asigna cero a totales vacíos, convirtiéndolos a float. */
        $data = json_decode($data);


        foreach ($data->data as $key => $value) {
            if ($value->{".total"} == "") {
                $value->{".total"} = 0;
            }
            $recargadoHoy = floatval($value->{".total"});
        }


        /* Verifica si la recarga supera el límite permitido y lanza una excepción si es así. */
        if (($recargadoHoy + $Amount) > floatval($PuntoVenta->valorCupo2)) {
            throw new Exception("Excedio el cupo maximo permitido de recarga. Consulte con su administrador", "100005");

        }

    }


    /* Verifica créditos y límites de depósito antes de permitir recarga en el punto de venta. */
    if (floatval($PuntoVenta->getCreditosBase()) - floatval($Amount) < 0) {
        throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100002");
    }

    $ConfigurationEnvironment = new ConfigurationEnvironment();

    if (true) {

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


    /* Inicializa objeto UsuarioRecarga y establece el ID de usuario y fecha de creación. */
    $rowsUpdate = 0;

    $UsuarioRecarga = new UsuarioRecarga();
    //$UsuarioRecarga->setRecargaId($consecutivo_recarga);
    $UsuarioRecarga->setUsuarioId($Id);
    $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));

    /* establece atributos para un objeto de recarga de usuario. */
    $UsuarioRecarga->setPuntoventaId($UsuarioMandante->getUsuarioMandante());
    $UsuarioRecarga->setValor($Amount);
    $UsuarioRecarga->setPorcenRegaloRecarga(0);
    $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 40);
    $UsuarioRecarga->setDirIp($dirIp);
    $UsuarioRecarga->setPromocionalId(0);

    /* Se configuran parámetros de un objeto UsuarioRecarga para una transacción específica. */
    $UsuarioRecarga->setValorPromocional(0);
    $UsuarioRecarga->setHost(0);
    $UsuarioRecarga->setMandante($Usuario->mandante);
    $UsuarioRecarga->setPedido(0);
    $UsuarioRecarga->setPorcenIva(0);
    $UsuarioRecarga->setMediopagoId(0);

    /* configura un usuario para recarga y establece transacciones en relación a puntos de venta. */
    $UsuarioRecarga->setValorIva(0);
    $UsuarioRecarga->setEstado('A');
    if($comision === false){ /*verificamos si comision es false en caso de estar en si entonces asignamos N*/
        $UsuarioRecarga->setTieneComision("N");
    }

    $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
    $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);


    /* Crea registros y carga datos de ciudad usando un DAO de MySQL. */
    $Registro = new Registro('', $Usuario->usuarioId);

    $CiudadMySqlDAO = new CiudadMySqlDAO();

    $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
    $CiudadPuntoVenta = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);


    /* Consulta la cantidad de depósitos de un usuario específico en la base de datos. */
    $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $Usuario->usuarioId . "'");

    $detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];


    try {


        /* Se crea un arreglo con detalles de depósitos y datos del usuario. */
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


        /* Se crea un bono interno y se agrega con detalles y transacción específica. */
        $BonoInterno = new BonoInterno();
        $detalles = json_decode(json_encode($detalles));

        $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);


    } catch (Exception $e) {
        /* captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución. */


    }


    /* Inserta un registro de recarga de usuario y obtiene su ID consecutivo. */
    $rowsUpdate = $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

    //$UsuarioRecarga->setRecargaId($consecutivo_recarga);

    $consecutivo_recarga = $UsuarioRecarga->recargaId;

    $rowsUpdate = 0;


    /* Valida el resultado de una transacción de crédito y lanza excepción si falla. */
    $rowsUpdate = $Usuario->credit($Amount, $Transaction);

    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }


    $rowsUpdate = 0;


    /* crea un historial de usuario con datos iniciales específicos. */
    $UsuarioHistorial = new UsuarioHistorial();
    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
    $UsuarioHistorial->setDescripcion('');
    $UsuarioHistorial->setMovimiento('E');
    $UsuarioHistorial->setUsucreaId(0);
    $UsuarioHistorial->setUsumodifId(0);

    /* inserta un registro de historial de usuario en la base de datos. */
    $UsuarioHistorial->setTipo(10);
    $UsuarioHistorial->setValor($Amount);
    $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
    $rowsUpdate = $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


    /* Validación y creación de una nota de usuario en base de datos si corresponde. */
    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }

    if ($Note != '') {
        $UsuarioMandanteUsuarioOnline = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

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


    /* Crea y guarda una nota de usuario si la descripción está presente. */
    if ($Description != '') {
        $UsuarioMandanteUsuarioOnline = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

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


    /* Actualiza el balance de créditos basado en el perfil y tipo de transacción. */
    $rowsUpdate = 0;
    if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3" or $_SESSION["win_perfil"] == "PUNTOVENTA" or $_SESSION["win_perfil"] == "CAJERO") {

        if ($tipo == "S") {
            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);

        } else {
            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
        }

        //$PuntoVenta->update($PuntoVenta);

    }


    /* Verifica si se actualizó alguna fila; si no, lanza una excepción. */
    if ($rowsUpdate == null || $rowsUpdate <= 0) {
        throw new Exception("Error General", "100000");
    }

    $rowsUpdate = 0;

    $FlujoCaja = new FlujoCaja();

    /* establece propiedades en un objeto FlujoCaja, registrando detalles de una transacción. */
    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($UsuarioMandante->getUsuarioMandante());
    $FlujoCaja->setTipomovId('E');
    $FlujoCaja->setValor($UsuarioRecarga->getValor());
    $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());

    /* Configura los detalles del flujo de caja según el usuario y condiciones específicas. */
    $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setFormapago1Id(1);
    $FlujoCaja->setCuentaId('0');

    if ($FlujoCaja->getFormapago2Id() == "") {
        $FlujoCaja->setFormapago2Id(0);
    }


    /* Asigna un valor de 0 si las formas están vacías en FlujoCaja. */
    if ($FlujoCaja->getValorForma1() == "") {
        $FlujoCaja->setValorForma1(0);
    }

    if ($FlujoCaja->getValorForma2() == "") {
        $FlujoCaja->setValorForma2(0);
    }


    /* Verifica campos vacíos y establece valores predeterminados en el objeto FlujoCaja. */
    if ($FlujoCaja->getCuentaId() == "") {
        $FlujoCaja->setCuentaId('');
    }

    if ($FlujoCaja->getPorcenIva() == "") {
        $FlujoCaja->setPorcenIva(0);
    }


    /* establece el IVA a cero si está vacío y lo inserta en la base de datos. */
    if ($FlujoCaja->getValorIva() == "") {
        $FlujoCaja->setValorIva(0);
    }

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

    if ($rowsUpdate > 0) {


        /* Se crea un historial de usuario con datos básicos en un sistema. */
        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($PuntoVenta->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('S');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);

        /* inserta un historial de usuario con información de recarga y tipo. */
        $UsuarioHistorial->setTipo(10);
        $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


        /* Actualiza la fecha y monto del primer depósito si aún no se ha registrado. */
        if ($Usuario->fechaPrimerdeposito == "") {
            $Usuario->fechaPrimerdeposito = date('Y-m-d H:i:s');
            $Usuario->montoPrimerdeposito = $UsuarioRecarga->getValor();
            $UsuarioMySqlDAO2 = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO2->update($Usuario);
        }


        /* Actualiza el estado de contingenciaRetiro de un usuario si cumple ciertos criterios. */
        $Usuario2 = $Usuario;


        if ($Usuario2->fechaCrea >= '2023-03-01 08:00:00' && $Usuario2->mandante == 14 && $Usuario2->contingenciaRetiro == 'A') {
            $Usuario2->contingenciaRetiro = 'I';
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario2);
        }

        /* Actualiza la propiedad 'contingenciaRetiro' de usuarios según condiciones específicas. */
        if ($Usuario2->fechaCrea >= '2023-04-01 00:00:00' && $Usuario2->mandante == 0 && $Usuario2->paisId == 2 && $Usuario2->contingenciaRetiro == 'A') {
            $Usuario2->contingenciaRetiro = 'I';
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario2);
        }
        if ($Usuario2->fechaCrea >= '2023-05-29 00:00:00' && $Usuario2->mandante == 0 && $Usuario2->paisId == 46 && $Usuario2->contingenciaRetiro == 'A') {
            $Usuario2->contingenciaRetiro = 'I';
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario2);
        }

        /* Actualiza el estado de contingenciaRetiro para ciertos usuarios basados en condiciones específicas. */
        if ($Usuario2->fechaCrea >= '2024-01-17 00:00:00' && $Usuario2->mandante == 0 && $Usuario2->paisId == 46 && $Usuario2->contingenciaRetiro == 'A') {
            $Usuario2->contingenciaRetiro = 'I';
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario2);
        }
        if ($Usuario2->fechaCrea >= '2024-01-17 00:00:00' && $Usuario2->mandante == 0 && $Usuario2->paisId == 66 && $Usuario2->contingenciaRetiro == 'A') {
            $Usuario2->contingenciaRetiro = 'I';
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario2);
        }

        /* verifica si un usuario está en una lista y actualiza su estado. */
        if (in_array($Usuario2->usuarioId, array(7225068, 7224912, 7224893, 7224846, 7224817, 7224793, 7224776, 7224744, 7224680, 7224645, 7224618, 7224546, 7224528, 7224518, 7224516, 7224391, 7224301, 7224256, 7224225, 7224222, 7224163, 7224143, 7224107, 7223981, 7223927, 7223924, 7223880, 7223855, 7223852, 7223848, 7223831, 7223754, 7223690, 7223635, 7223267, 7223066, 7220882, 7218333, 7217063, 7216587, 7216467, 7215802, 7215111, 7213809, 7213775, 7213758, 7212469, 7211836, 7210482, 7209908, 7208604, 7207953, 7207073, 7198770, 7198659, 7195559, 7195429, 7191787, 7190758, 7189917, 7189648, 7186234, 7185308, 7183464, 7181587, 7180425, 7179726, 7178581, 7177942, 7177205, 7175647, 7175446, 7173468, 7171914, 7171024, 7170865, 7169220, 7167744, 7167644, 7167558, 7167500, 7167136, 7166924, 7166601, 7165672, 7165468, 7165062, 7164125, 7163945, 7163410, 7163369, 7163189, 7162964, 7162924, 7162886, 7162759, 7161606, 7160601, 7160479, 7160312, 7140970, 7137814, 7137757, 7136391, 7135072, 7126680, 7124894, 7124879, 7124773, 7124674, 7124640, 7123508, 7121997, 7118902, 7118039, 7116613, 7115930, 7115833, 7115616, 7115259, 7115150, 7110595, 7106847, 7105918, 7103189, 7102377, 7102253, 7101484, 7101231, 7101217, 7101177, 7101034, 7100994, 7100959, 7100937, 7100753, 7100329, 7099911, 7099807, 7099746, 7098474, 7097936, 7097551, 7096475, 7095271, 7091624, 7087555, 7085940, 7085683, 7084160, 7080468, 7079230, 7079174, 7079117, 7078551, 7078193, 7077838, 7077564, 7074257, 7074130, 7074095, 7073686, 7073527, 7072597, 7072569, 7072555, 7072512, 7072489, 7072419, 7072412, 7072229, 7072189, 7071226, 7070948, 7070412, 7069471, 7069154, 7065984, 7065481, 7065257, 7064911, 7060814, 7060414, 7060383, 7059604, 7058500, 7057199, 7056807, 7056788, 7056086, 7055045, 7054795, 7053926, 7051124, 7047812, 7045459, 7042405, 7041258, 7040912, 7040818, 7040702, 7040683, 7040480, 7040386, 7040378, 7040299, 7040280, 7040278, 7040271, 7040149, 7040125, 7040112, 7040110, 7040040, 7040038, 7037166, 7026693, 7024159, 7021740, 7020951, 7013284, 7009905, 7009552, 7008369, 7007625, 7007593, 7006748, 7005118, 7004636, 7002175, 6988410, 6971935, 6969668, 6968375, 6967477, 6962713, 6960131, 6957507, 6945459, 6939680, 6937980, 6932179, 6931953, 6919139, 6916336, 6914385, 6913599, 6913014, 6912413, 6909235, 6892340, 6891825, 6890851, 6890826, 6890791, 6889993, 6889074, 6886737, 6870419, 6869430, 6866987, 6856064, 6855608, 6822389, 6802738, 6783577, 6776956, 6766489, 6766081, 6764095, 6763453, 6736579, 6726421, 6718171, 6716833, 6705817, 6700714, 6688300, 6651271, 6607338, 6606804, 6599743, 6599608, 6599455, 6597946, 6585379, 6555214, 6545653, 6534607, 6493283, 6463079, 6453140, 6449936, 6449914, 6442084, 6440750, 6440732, 6425154, 6418934, 6383159, 6333255, 6308813, 6263505, 6259354, 6234067, 6227236, 6191074, 6187468, 6142516, 6117624, 6018773, 6016771, 5889792, 5877843, 5872242, 5844219, 5732285, 5731859, 5724950, 5717471, 5713216, 5710717, 5683593, 5643692, 5643002, 5641103, 5640941, 5635673, 5635652, 5616506, 5608124, 5587481, 5567930, 5552751, 5549970, 5549307, 5548545, 5547324, 5547009, 5546985, 5542155, 5521218, 5507100, 5504421, 5498391, 5484024, 5461905, 5461206, 5461188, 5460594, 5458854, 5456958, 5456043, 5454108, 5441535, 5413990, 5405476, 5403835, 5403724, 5403631, 5401459, 5398561, 5380849, 5339672, 5339495, 5337929, 5337152, 5337149, 5336975, 5336867, 5336609, 5336597, 5336564, 5336405, 5335910, 5335601, 5335532, 5335487, 5335229, 5335073, 5334995, 5334716, 5334257, 5334167, 5334023, 5333741, 5333729, 5291624, 5289167, 5288927, 5288687, 5288642, 5288363, 5288192, 5288081, 5287910, 5282528, 5277578, 5277530, 5277524, 5258399, 5198702, 5196998, 5194946, 5193215, 5193047, 5188985, 5187587, 5173681, 5141098, 5121667, 5069863, 5054179, 5043757, 5034016, 4953456, 4945954, 4867198, 4852930, 4745157, 4717865, 4537249, 4517716, 4516036, 4514134, 4513786, 4513285, 4511779, 4510012, 4506691, 4505662, 4501948, 4501678, 4501279, 4499563, 4499113, 4497922, 4497031, 4495693, 4493815, 4491496, 4491442, 4490089, 4489360, 4485817, 4485175, 4484314, 4483906, 4483645, 4481011, 4475155, 4473112, 4469653, 4468231, 4467940, 4467481, 4466620, 4464850, 4464001, 4463452, 4462426, 4461790, 4460590, 4460068, 4459825, 4459741, 4459732, 4459468, 4458811, 4457824, 4457074, 4456777, 4456579, 4455934, 4455136, 4453489, 4452556, 4449103, 4449043, 4444804, 4440661, 4440073, 4439173, 4438348, 4436245, 4436116, 4436092, 4434964, 4434073, 4432273, 4429225, 4428385, 4425334, 4424977, 4423543, 4422454, 4421692, 4420681, 4420378, 4419871, 4419850, 4415848, 4415620, 4413730, 4413469, 4410379, 4409731, 4409167, 4408555, 4405420, 4404511, 4401922, 4397131, 4396057, 4395478, 4394125, 4392745, 4392403, 4391434, 4389814, 4388938, 4388890, 4388764, 4388419, 4384753, 4384444, 4384318, 4384282, 4382539, 4382050, 4382035, 4381144, 4378717, 4377133, 4377103, 4377031, 4375093, 4374394, 4373014, 4372927, 4369549, 4368241, 4366831, 4364329, 4363681, 4363324, 4362193, 4360807, 4360597, 4358863, 4357486, 4357327, 4356349, 4356334, 4355698, 4355122, 4354894, 4354528, 4353913, 4352656, 4352653, 4351912, 4351870, 4351585, 4348858, 4348357, 4348171, 4348024, 4347925, 4347826, 4346860, 4345522, 4344850, 4344718, 4344658, 4343026, 4342981, 4342330, 4342249, 4341832, 4341724, 4341181, 4340359, 4338673, 4336903, 4335943, 4333801, 4333561, 4332409, 4330654, 4330090, 4329766, 4329700, 4328743, 4328431, 4327243, 4326604, 4326289, 4325008, 4324573, 4324258, 4323493, 4322488, 4322098, 4320904, 4320418, 4320313, 4319890, 4319590, 4318261, 4318150, 4318129, 4317298, 4316512, 4316203, 4316023, 4315840, 4315534, 4315156, 4314952, 4314469, 4313533, 4313404, 4313119, 4312180, 4311874, 4311622, 4311619, 4310887, 4310737, 4310620, 4310443, 4310371, 4309819, 4309702, 4309519, 4309306, 4309294, 4309288, 4309051, 4308751, 4308736, 4308703, 4308604, 4308028, 4307929, 4307725, 4307524, 4307464, 4307458, 4307290, 4307227, 4307056, 4306426, 4306249, 4305355, 4305178, 4304872, 4304770, 4304764, 4304263, 4304209, 4304203, 4303909, 4298802, 4297607, 4289765, 4286847, 4282919, 4281677, 4253953, 4242181, 4218751, 4217671, 4212088, 4143733, 4141618, 4049693, 4043753, 3990971, 3945722, 3779136, 3683531, 3680852, 3541009, 3442465, 3403258, 3246871, 3167422, 3144832, 3087133, 3073147, 2968112, 2960414, 2947106, 2921831, 2916179, 2762847, 2746651, 2745209, 2699681, 2586397, 2586209, 2586145, 2572677, 2564074, 2488780, 2488582, 2432465, 2430956, 2424671, 2336045, 2334680, 2333912, 2294498, 2284892, 2218452, 2184162, 2109970, 2048262, 2040867, 2025371, 2022604, 2015062, 2012488, 1997400, 1820944, 1818166, 1690597, 1561261, 1152165, 1151461, 1122486, 1076148, 1025523, 960586, 833986, 809869, 724362, 489209, 400039, 350657, 318429, 125666)) && $Usuario2->contingenciaRetiro == 'A') {
            $Usuario2->contingenciaRetiro = 'I';
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario2);
        }


        $Transaction->commit();


        /* verifica si el entorno no es de desarrollo antes de ejecutar acciones. */
        try {

            if (!$ConfigurationEnvironment->isDevelopment()) {


            }

        } catch (Exception $e) {
            /* Captura excepciones y registra advertencias en el sistema sobre errores en API. */

            syslog(LOG_WARNING, "ERRORPROVEEDORAPI :" . $e->getCode() . ' - ' . $e->getMessage());
        }

        try {


            /* Captura el agente de usuario y codifica información del servidor en base64. */
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);


            $ismobile = '';


            /* verifica si un usuario está usando un dispositivo móvil. */
            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

                $ismobile = '1';

            }
//Detect special conditions devices
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");

            /* Identifica dispositivos móviles mediante el análisis del agente de usuario HTTP. */
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
            if ($iPod || $iPhone) {
                $ismobile = '1';
            } else if ($iPad) {
                /* verifica si es un iPad y establece una variable de móvil. */

                $ismobile = '1';
            } else if ($Android) {
                /* verifica si es un dispositivo Android y establece `$ismobile` a '1'. */

                $ismobile = '1';
            }


                //exec("php -f ". __DIR__ ."/../../../src/integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "DEPOSITOCRM" . " " . $UsuarioRecarga->recargaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");
        } catch (Exception $e) {
            /* Es un bloque que captura excepciones en PHP para manejar errores sin interrumpir la ejecución. */


        }
    } else {
        /* Lanza una excepción con un mensaje de error y un código específico. */

        throw new Exception("Error General", "100000");
    }


    /* Ejecuta scripts PHP basados en condiciones específicas del usuario y entorno. */
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        exec("php -f " . __DIR__ . "/../../../src/integrations/payment/ActivacionRuletaMetodosPagos.php " . $UsuarioMandante->paisId . " " . $UsuarioMandante->usumandanteId . " " . $UsuarioRecarga->valor . " " . 5 . " " . '""' . " " . $consecutivo_recarga ." > /dev/null &");


    if ($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 8 || ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == 173)) {
        exec("php -f " . __DIR__ . "/../../../src/integrations/casino" . "/AsignarPuntosLealtad.php " . "DEPOSITO" . " " . $UsuarioRecarga->getRecargaId() . " " . 10 . " > /dev/null &");
    }


    /* Se crea un nuevo objeto 'Mandante' utilizando la propiedad 'mandante' del objeto 'Usuario'. */
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


    /* Genera una fila en un PDF si se cumplen condiciones específicas de usuario y país. */
    if ($UsuarioPuntoVenta->paisId == '2' and $UsuarioPuntoVenta->mandante == "0") {
        $pdf = $pdf . '<tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Cedula Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Registro->cedula . '</font>
            </td>
        </tr>';
    }


    $pdf = $pdf . '
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


    /* Se genera un bloque HTML si el usuario pertenece a un país y mandante específicos. */
    if ($Usuario->paisId == 173 && $Usuario->mandante == '0') {
        $pdf .= '
        <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
        </div>';

    }


    /* Genera un código HTML para mostrar un código de barras basado en un ID. */
    $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
      <div class="barcodecell" style="  text-align: center;"><barcode code="' . $UsuarioRecarga->getRecargaId() . '" type="I25" class="barcode" /></div>
  </div>
    </div>
</div>
</body>

';


    /* Traduce términos en un PDF de español a inglés según idioma del usuario. */
    if (strtolower($UsuarioPuntoVenta->idioma) == 'en') {
        $pdf = str_replace("RECIBO", "RECEIPT", $pdf);
        $pdf = str_replace("DE RECARGA", "OF DEPOSIT", $pdf);

        $pdf = str_replace("Recibo de Recarga", "Deposit Receipt", $pdf);
        $pdf = str_replace("Fecha", "Date", $pdf);
        $pdf = str_replace("Punto de Venta", "Betshop", $pdf);
        $pdf = str_replace("No. de Cliente", "No. of User", $pdf);

        $pdf = str_replace("Nombre Cliente", "Name of User", $pdf);
        $pdf = str_replace("Valor recarga", "Amount", $pdf);
        $pdf = str_replace("Disfruta del juego en vivo", "Enjoy the games", $pdf);

    }

    if (($UsuarioPuntoVenta->mandante == 2 || $UsuarioPuntoVenta->mandante == 6 || $UsuarioPuntoVenta->mandante == 18) && false) {

        try {

            /* Se crea un clasificador y un template, generando código HTML para un código de barras. */
            $Clasificador = new Clasificador("", "TEMRECRE");

            $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
            $html_barcode = $Template->templateHtml;
            if ($html_barcode != '') {

                /* Reemplaza marcadores en una cadena HTML con datos de usuario y recarga. */
                $html_barcode = str_replace("#depositnumber#", $UsuarioRecarga->recargaId, $html_barcode);

                $html_barcode = str_replace("#userid#", $UsuarioRecarga->usuarioId, $html_barcode);
                $html_barcode = str_replace("#login#", $Usuario->login, $html_barcode);
                $html_barcode = str_replace("#name#", $Usuario->nombre, $html_barcode);
                $html_barcode = str_replace("#idpointsale#", $UsuarioPuntoVenta->usuarioId, $html_barcode);

                /* Se reemplazan marcadores en una plantilla HTML con datos específicos del usuario. */
                $html_barcode = str_replace("#namepointsale#", $UsuarioPuntoVenta->nombre, $html_barcode);
                $html_barcode = str_replace("#value#", $UsuarioRecarga->valor, $html_barcode);
                $html_barcode = str_replace("#creationdate#", $UsuarioRecarga->fechaCrea, $html_barcode);

                $html_barcode = str_replace("#tax#", '0', $html_barcode);

                $html_barcode = str_replace("#totalvalue#", $UsuarioRecarga->valor, $html_barcode);


                /* Se crea un código PDF usando Dompdf y un HTML de plantilla. */
                $pdf = $html_barcode;


                $html_barcode .= $Template->templateHtmlCSSPrint;
// instantiate and use the dompdf class
                $dompdf = new Dompdf();

                /* Carga HTML y configura tamaño y orientación del papel para imprimir un código de barras. */
                $dompdf->loadHtml($html_barcode);

// (Optional) Setup the paper size and orientation
                $width = 80; //mm!
                $height = 150; //mm!

                //convert mm to points
                $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);

                /* configura un formato de papel y genera un PDF desde HTML. */
                $dompdf->setPaper($paper_format);

// Render the HTML as PDF
                $dompdf->render();

// Output the generated PDF to Browser


                $data = $dompdf->output();


                /* codifica datos PDF en Base64 y los organiza en un arreglo de respuesta. */
                $base64 = 'data:application/pdf;base64,' . base64_encode($data);

                $response["Pdf"] = base64_encode($data);
                $response["PdfPOS"] = base64_encode($data);
                $response["id"] = $UsuarioRecarga->recargaId;
                $response["Id"] = $UsuarioRecarga->recargaId;


            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución del código. */


        }
    } else {


        /* Código para crear un documento PDF con Mpdf, especificando formato y márgenes. */
        $mpdf = new \Mpdf\Mpdf(['format' => array(80, 150), 'tempDir' => '/tmp']);

        //$mpdf = new mPDF('c', array(80, 150), 10, 10, 10, 10);
//$mpdf = new mPDF('c', 'A4-L');

        $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)


        /* Configura el modo de visualización del PDF en página completa y en doble página. */
        $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

        try {

            /* Se crea un clasificador y un template HTML usando datos del usuario. */
            $Clasificador = new Clasificador("", "TEMRECRE");

            $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
            $html_barcode = $Template->templateHtml;
            if ($html_barcode != '') {

                /* reemplaza marcadores en una cadena HTML con información del usuario y transacción. */
                $html_barcode = str_replace("#depositnumber#", $UsuarioRecarga->recargaId, $html_barcode);

                $html_barcode = str_replace("#userid#", $UsuarioRecarga->usuarioId, $html_barcode);
                $html_barcode = str_replace("#login#", $Usuario->login, $html_barcode);
                $html_barcode = str_replace("#name#", $Usuario->nombre, $html_barcode);
                $html_barcode = str_replace("#idpointsale#", $UsuarioPuntoVenta->usuarioId, $html_barcode);

                /* reemplaza marcadores en una plantilla HTML con datos específicos. */
                $html_barcode = str_replace("#namepointsale#", $UsuarioPuntoVenta->nombre, $html_barcode);
                $html_barcode = str_replace("#value#", $UsuarioRecarga->valor, $html_barcode);
                $html_barcode = str_replace("#creationdate#", $UsuarioRecarga->fechaCrea, $html_barcode);

                $html_barcode = str_replace("#tax#", '0', $html_barcode);

                $html_barcode = str_replace("#totalvalue#", $UsuarioRecarga->valor, $html_barcode);

                /* Condiciona el cálculo de un valor basado en propiedades del objeto Usuario. */
                if ($Usuario->mandante == 0 && $Usuario->paisId == 2) {
                    $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
                    $totalvalue2 = ($UsuarioRecarga->valor * $PaisMandante->trmNio);
                    $html_barcode = str_replace("#totalvalue2#", $totalvalue2, $html_barcode);
                }

                $pdf = $html_barcode;


            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para evitar errores durante la ejecución del código. */


        }


        /* genera un PDF usando mPDF y obtiene un identificador de usuario. */
        $mpdf->WriteHTML($pdf);

        $complement = '';

        if (true) {
            $complement = $UsuarioMandante->usumandanteId;
        }


        /* Genera y guarda un archivo PDF en una ruta temporal específica. */
        $mpdf->Output('/tmp' . "/mpdf" . $complement . ".pdf", "F");

        $path = '/tmp' . '/mpdf' . $complement . '.pdf';

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        /* Codifica datos en base64 y crea una respuesta con identificadores y archivos PDF. */
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

        $response["Pdf"] = base64_encode($data);
        $response["PdfPOS"] = base64_encode($data);
        $response["id"] = $UsuarioRecarga->recargaId;
        $response["Id"] = $UsuarioRecarga->recargaId;


        /* gestiona usuarios y envía mensajes WebSocket en entornos de desarrollo. */
        if (true) {
            unlink($path);
        }

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {

            try {
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                /*$data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();*/

            } catch (Exception $e) {

            }
        }
    }


    try {


        /* Envía un mensaje a Slack sobre recargas de puntos de venta específicos si se cumplen condiciones. */
        if ($Usuario->paisId == 173 && $Usuario->mandante == '0' && floatval($UsuarioRecarga->getValor()) >= 500 && $PuntoVenta->propio != 'S') {
            try {

                $message = '*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-peru' > /dev/null & ");
            } catch (Exception $e) {

            }
        }


        /* Envía un mensaje a Slack sobre recargas de usuarios en condiciones específicas. */
        if ($Usuario->paisId == 66 && $Usuario->mandante == '8' && floatval($UsuarioRecarga->getValor()) >= 125 && $PuntoVenta->propio != 'S') {
            try {

                $message = '*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-ecuabet' > /dev/null & ");
            } catch (Exception $e) {

            }
        }


        /* Condición para enviar un mensaje a Slack sobre recargas en puntos de venta. */
        if ($Usuario->paisId == 60 && $Usuario->mandante == '0' && floatval($UsuarioRecarga->getValor()) >= 80300 && $PuntoVenta->propio != 'S') {
            try {

                $message = '*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-costarica' > /dev/null & ");
            } catch (Exception $e) {

            }
        }


        /* Envía un mensaje a Slack sobre recargas en puntos de venta específicos. */
        if ($Usuario->paisId == 146 && floatval($UsuarioRecarga->getValor()) >= 2578 && $PuntoVenta->propio != 'S') {
            try {

                $message = '*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-netabet' > /dev/null & ");
            } catch (Exception $e) {

            }
        }


        /* Envía un mensaje a Slack sobre recargas específicas de usuarios en un punto de venta. */
        if ($Usuario->paisId == 46 && $Usuario->mandante == '0' && floatval($UsuarioRecarga->getValor()) >= 110000 && $PuntoVenta->propio != 'S') {
            try {

                $message = '*Recarga Punto de Venta Tercero:* - *Usuario:* ' . $Usuario->usuarioId . ' - *Valor:* ' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . ' - *ID Punto Venta:* ' . $UsuarioPuntoVenta->usuarioId . ' - *Punto Venta:* ' . $UsuarioPuntoVenta->nombre;

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-chile' > /dev/null & ");
            } catch (Exception $e) {

            }
        }


    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, captura de errores sin procesamiento adicional. */


    }


    /* Se ejecuta un script PHP para verificar un sorteo, manejando posibles excepciones. */
    try {
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        //exec("php -f " . __DIR__ . "/../../../src/integrations/payment/VerificarSorteo.php " . $UsuarioMandante->usumandanteId . " " . $consecutivo_recarga . "   > /dev/null &");

    } catch (Exception $e) {

    }


    /* Condicional que envía un mensaje de depósito si se cumplen ciertos criterios de usuario. */
    if ($Usuario->test == 'S' && $Usuario->mandante == '0' && $Usuario->paisId == '94' && false) {
        $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
        $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
        $mensaje_txt = $Mandante->nombre . ' le informa deposito a su cuenta por ' . $Usuario->moneda . ' ' . $UsuarioRecarga->valor . ' ID del depósito (' . $UsuarioRecarga->recargaId . ')';

        $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
        $cambios = true;
    }

} else {
    /* Lanza una excepción con mensaje y código al ocurrir un error general. */

    throw new Exception("Error General", "100000");
}


/* Código que establece una respuesta exitosa sin errores para una operación. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];
