<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
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
use Backend\dto\Submenu;
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
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
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
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Obtener el detalle de una solicitud de retiro.
 *
 * Este script permite obtener información detallada sobre una solicitud de retiro específica, incluyendo datos como
 * el estado, fecha de creación, monto, método de pago, observaciones, y otros detalles relevantes.
 *
 * @param int $id Identificador único de la solicitud de retiro.
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params ->OrderedItem (Opcional) Orden de los elementos. Por defecto, 1.
 * @param int $params ->SkeepRows (Opcional) Número de filas a omitir. Por defecto, 0.
 *
 *
 *
 * @return array $response Respuesta en formato JSON con la siguiente estructura:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Contiene los detalles de la solicitud de retiro.
 * - pos (int): Posición inicial de los datos.
 * - total_count (int): Número total de registros.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */


/* Crea una instancia de CuentaCobro y obtiene el ID de la solicitud. */
$CuentaCobro = new CuentaCobro();

$Id = $_REQUEST["id"];


$MaxRows = 1;

/* Inicializa variables para manejar elementos ordenados y controlar filas a omitir. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = 0;
$totalcount = 0;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}

if ($Id != "") {


    /* Crea un filtro en formato JSON para consultas, utilizando reglas de igualdad. */
    $rules = [];
    array_push($rules, array("field" => "cuenta_cobro.cuenta_id", "data" => "$Id", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* Obtiene y decodifica datos de cuentas de cobro en formato JSON. */
    $cuentas = $CuentaCobro->getCuentasCobroCustom("cuenta_cobro.cuenta_id,cuenta_cobro.fecha_pago,cuenta_cobro.fecha_cambio,cuenta_cobro.fecha_accion,cuenta_cobro.usucambio_id,cuenta_cobro.usupago_id,cuenta_cobro.usurechaza_id,cuenta_cobro.usuario_id,cuenta_cobro.usucambio_id,cuenta_cobro.observacion,cuenta_cobro.mensaje_usuario,usuario.login,cuenta_cobro.fecha_crea,cuenta_cobro.valor,cuenta_cobro.mediopago_id,usuario.moneda,cuenta_cobro.puntoventa_id,punto_venta.descripcion puntoventa,cuenta_cobro.mediopago_id, banco.descripcion banco_nombre,cuenta_cobro.estado,usuario_banco.cuenta,transaccion_producto.transproducto_id", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "cuenta_cobro.cuenta_id");

    $cuentas = json_decode($cuentas);

    $value = $cuentas->data[0];


    $final = array();


    /* Se crea un array con información sobre una cuenta de cobro. */
    $array = [];

    $array["Id"] = $value->{"cuenta_cobro.cuenta_id"};
    $array["ClientId"] = $value->{"cuenta_cobro.usuario_id"};
    $array["Date"] = $value->{"cuenta_cobro.fecha_crea"};
    $array["Type"] = "Creacion";

    /* Se crea un arreglo con datos de una solicitud de retiro, adaptando el idioma. */
    $array["Amount"] = $value->{"cuenta_cobro.valor"};
    $array["Description"] = "Creacion de Solicitud de retiro";
    $array["UserModifId"] = $value->{"cuenta_cobro.usuario_id"};

    if (strtolower($_SESSION["idioma"]) == "en") {
        $array["Description"] = str_replace("Creacion de Solicitud de retiro", "Withdrawal request created", $array["Description"]);
    }


    /* asigna un método de pago y verifica si hay un banco válido. */
    $nombreMetodoPago = 'Efectivo';
    $idMetodoPago = 0;

    $estado = 'Pendiente de Pago';
    $array["Action"] = "None";


    if ($value->{"banco.banco_nombre"} != '') {
        $nombreMetodoPago = $value->{"banco.banco_nombre"} . " - " . $value->{"usuario_banco.cuenta"};
    }


    /* asigna el ID de método de pago y su nombre a un array. */
    if ($value->{"cuenta_cobro.metodopago_id"} != '') {
        $idMetodoPago = $value->{"cuenta_cobro.metodopago_id"};
    }

    $array["PaymentSystemName"] = $nombreMetodoPago;
    $array["PaymentSystemId"] = $idMetodoPago;

    /* asigna valores de un objeto a un array asociativo en PHP. */
    $array["TypeName"] = "Payment";

    $array["CurrencyId"] = $value->{"cuenta_cobro.moneda"};
    $array["CashDeskId"] = $value->{"cuenta_cobro.puntoventa_id"};
    $array["CashDeskId"] = $value->{"cuenta_cobro.puntoventa_id"};
    $array["BetshopId"] = $value->{"cuenta_cobro.puntoventa_id"};

    /* Asigna valores a un arreglo desde un objeto basado en propiedades específicas. */
    $array["BetShopName"] = $value->{"punto_venta.puntoventa"};
    $array["RejectUserName"] = $value->{"cuenta_cobro.usurechaza_id"};
    $array["AllowUserName"] = $value->{"cuenta_cobro.usucambio_id"};
    $array["PaidUserName"] = $value->{"cuenta_cobro.usupago_id"};
    $array["Notes"] = $value->{"cuenta_cobro.mensaje_usuario"};
    $array["RejectReason"] = $value->{"cuenta_cobro.observacion"};

    /* asigna valores a un array desde un objeto y variables definidas. */
    $array["Description"] = $value->{"cuenta_cobro.observacion"};
    $array["StateName"] = $estado;
    $array["State"] = $value->{"cuenta_cobro.estado"};
    $array["StateId"] = $value->{"cuenta_cobro.estado"};
    $array["Note"] = "";
    $array["ExternalId"] = "";

    /* Se crea un array con ID de documento de pago y se añade a otro array. */
    $array["PaymentDocumentId"] = "";


    $array2["PaymentDocumentData"] = $array;

    array_push($final, $array);
    $totalcount++;


    /* Verifica si el pago es válido y organiza información sobre pagos realizados. */
    if ($value->{"cuenta_cobro.puntoventa_id"} != "" && $value->{"cuenta_cobro.puntoventa_id"} != 0) {
        $estado = 'Pagado';

        $array["Date"] = $value->{"cuenta_cobro.fecha_pago"};
        $array["Type"] = "Pagada";
        $array["Amount"] = $value->{"cuenta_cobro.valor"};
        $array["Description"] = "Paga la Solicitud de retiro";
        $array["UserModifId"] = $value->{"cuenta_cobro.puntoventa_id"};
        if (strtolower($_SESSION["idioma"]) == "en") {
            $array["Type"] = "Paid";
            $array["Description"] = str_replace("Paga la Solicitud de retiro", "Pay the withdrawal request", $array["Description"]);
        }
        if ($array["UserModifId"] == 0) {
            $array["UserModifId"] = $value->{"cuenta_cobro.usupago_id"};
        }

        array_push($final, $array);
        $totalcount++;

    }


    /* verifica una condición y crea un registro de solicitud aprobada. */
    if ($value->{"cuenta_cobro.usucambio_id"} != "0") {
        $array["Date"] = $value->{"cuenta_cobro.fecha_cambio"};
        $array["Type"] = "Aprobado";
        $array["Amount"] = $value->{"cuenta_cobro.valor"};
        $array["Description"] = "Aprobada la Solicitud de retiro";
        $array["UserModifId"] = $value->{"cuenta_cobro.usucambio_id"};

        if (strtolower($_SESSION["idioma"]) == "en") {
            $array["Type"] = 'Approved';
            $array["Description"] = str_replace("Aprobada la Solicitud de retiro", "Withdrawal Request approved", $array["Description"]);
        }

        array_push($final, $array);
        $totalcount++;

    }

    if ($value->{"cuenta_cobro.usupago_id"} != "0") {


        /* Se asignan datos de un pago a un array en formato específico. */
        $estado = 'Pagado';


        $array["Date"] = $value->{"cuenta_cobro.fecha_pago"};
        $array["Type"] = "Pagada";
        $array["Amount"] = $value->{"cuenta_cobro.valor"};

        /* asigna valores a un array basado en el idioma y otros parámetros. */
        $array["Description"] = "Paga la Solicitud de retiro";
        $array["UserModifId"] = $value->{"cuenta_cobro.puntoventa_id"};

        if (strtolower($_SESSION["idioma"]) == "en") {
            $array["Type"] = 'Paid';
            $array["Description"] = 'Withdrawal Request paid';
        }


        /* Asigna un ID de usuario si es cero y agrega el array a final. */
        if ($array["UserModifId"] == 0) {
            $array["UserModifId"] = $value->{"cuenta_cobro.usupago_id"};
        }

        array_push($final, $array);
        $totalcount++;

    }

    if ($value->{"cuenta_cobro.usurechaza_id"} != "0" || $value->{"cuenta_cobro.estado"} == "R") {

        /* asigna valores a un array para registrar un estado de rechazo. */
        $estado = 'Rechazado';

        $array["Date"] = $value->{"cuenta_cobro.fecha_accion"};
        $array["Type"] = "Rechazado";
        $array["Amount"] = $value->{"cuenta_cobro.valor"};
        $array["Description"] = "Rechazada la Solicitud de retiro";

        /* asigna valores a un arreglo según condiciones de idioma y estado de solicitud. */
        $array["Description"] = $value->{"cuenta_cobro.observacion"};
        $array["UserModifId"] = $value->{"cuenta_cobro.usurechaza_id"};

        if (strtolower($_SESSION["idioma"]) == "en") {
            $array["Type"] = 'Rejected';
            $array["Description"] = 'Withdrawal Request rejected';
        }


        /* Agrega el contenido de `$array` al final de `$final`. */
        array_push($final, $array);
        $totalcount++;


    }

    if ($value->{"transaccion_producto.transproducto_id"} != "") {


        /* asigna información a un array según el idioma de la sesión. */
        $array["Date"] = "DETALLE TRANSACCION EN PROVEEDOR";
        $array["Type"] = "";
        $array["Amount"] = "";
        $array["Description"] = "";
        $array["UserModifId"] = "";

        if (strtolower($_SESSION["idioma"]) == "en") {
            $array["Date"] = 'SUPPLIER TRANSACTION DETAIL';
        }


        /* Se añaden elementos a un arreglo y se consulta información de una base de datos. */
        array_push($final, $array);
        $totalcount++;


        $TransprodLogMySqlDAO = new TransprodLogMySqlDAO();

        $objects = $TransprodLogMySqlDAO->queryByTransproductoId($value->{"transaccion_producto.transproducto_id"});


        foreach ($objects as $object) {

            /* Crea un array con fecha y tipo de estado basado en condiciones. */
            $array = [];

            $array["Date"] = $object->fechaCrea;
            $array["Type"] = $object->estado == "E" ? "Enviado" : "";
            $array["Type"] = $object->estado == "A" ? "Aprobado" : $array["Type"];
            $array["Type"] = $object->estado == "R" ? "Rechazado" : $array["Type"];


            /* asigna valores a un arreglo y traduce descripciones según el idioma. */
            $array["Amount"] = $value->{"cuenta_cobro.valor"};

            $array["Description"] = $object->comentario;
            $array["UserModifId"] = "";

            if (strtolower($_SESSION["idioma"]) == "en") {
                $array["Description"] = str_replace("Envio solicitud de deposito", "Deposit request sent", $array["Description"]);
                $array["Description"] = str_replace("Envio Solicitud de deposito", "Deposit request sent", $array["Description"]);
                $array["Description"] = str_replace("Aprobada por Sagicor", "Approved by Sagicor", $array["Description"]);
                $array["Description"] = str_replace("Aprobada por", "Approved by", $array["Description"]);
                $array["Description"] = str_replace("Auto aprobado por el proveedor", "Auto Approved by the provider", $array["Description"]);
                $array["Description"] = str_replace("Aprobado automaticamente y se genera recarga", "Auto Approved and recharge generated", $array["Description"]);
                $array["Description"] = str_replace("Aprobado automaticamente y se genera la recarga", "Auto Approved and recharge generated", $array["Description"]);
            }


            /* Añade el contenido de `$array` al final de `$final`. */
            array_push($final, $array);
            $totalcount++;

        }


        /* Convierte "Creacion" a "Creation" si el idioma de la sesión es inglés. */
        if (strtolower($_SESSION["idioma"]) == "en") {

            $array["Type"] = str_replace("Creacion", "Creation", $array["Type"]);
        }

    }


    /* Define una respuesta estructurada sin errores, con mensaje de éxito y datos finales. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = $final;


    /* asigna valores a un array de respuesta estructurada. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $totalcount;
    $response["data"] = $final;

} else {
    /* inicializa una respuesta exitosa sin errores ni mensajes. */

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array();

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
