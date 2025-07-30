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
 * @param string $CSV : Descripción: Archivo CSV codificado en base64 que contiene los datos de los depósitos masivos.
 *
 * @Description Procesar depósitos masivos a través de un archivo CSV este recurso permite cargar por medio de id los depositos atravez de un csv y asi aprobarlos de manera mas efectiva y masiva
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en el procesamiento.
 * - *AlertType* (string): Tipo de alerta ('success' o 'error').
 * - *AlertMessage* (string): Mensaje de alerta.
 * - *ModelErrors* (array): Errores del modelo, si los hay.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'error';
 * $response['AlertMessage'] = 'Mensaje de error';
 * $response['ModelErrors'] = [];
 *
 * @throws Exception Permiso denegado
 */


/* verifica permisos de usuario antes de ejecutar un depósito masivo. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);


$ConfigurationEnvironment = new ConfigurationEnvironment();
if (!$ConfigurationEnvironment->checkUserPermission('Financial/MassiveDeposit', $_SESSION['win_perfil'], $_SESSION['usuario'])) {
    try {
        syslog(LOG_WARNING, "DEPOSITOAPROB :" . file_get_contents('php://input') . json_encode($_SERVER));
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'DEPOSITOAPROB " . $Usuario->login . " " . $_SESSION["win_perfil"] . " " . $_SESSION["usuario"] . json_encode($_SERVER) . "' '#alertas-integraciones' > /dev/null & ");

    } catch (Exception $e) {

    }

    throw new Exception('Permiso denegado', 100035);
}


/* obtiene y decodifica un CSV de productos en base64 desde una solicitud. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$ProductsCsv = $params->CSV;

$ProductsCsv = explode("base64,", $ProductsCsv);
$ProductsCsv = $ProductsCsv[1];

/* Decodifica un CSV en base64 y separa sus líneas. */
$ProductsCsv = base64_decode($ProductsCsv);

$lines = explode(PHP_EOL, $ProductsCsv);
$lines = preg_split('/\r\n|\r|\n/', $ProductsCsv);

if (isset($ProductsCsv) && $ProductsCsv != '') {


    /* convierte un CSV en un array bidimensional usando delimitadores específicos. */
    $line = array();
    $i = 0;

    $linee = str_getcsv($ProductsCsv, "\n");

    //CSV: one line is one record and the cells/fields are seperated by ";"
    //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]

    foreach ($linee as $line) {

        if ($i > 0) {
            $dsatz[$i] = array();
            $dsatz[$i] = explode(";", $line);
        }

        $i++;
    }

    foreach ($dsatz as $fila) {
        // Reiniciar $k a 0 para cada fila

        /* asigna valores de una fila a variables usando índices. */
        $k = 0;

        // Acceder a los valores de cada columna en la fila actual
        $Id = $fila[$k];
        $k++;

        $State = $fila[$k];
        $k++;


        /* inicializa variables para usuarios y transacciones a partir de datos de sesiones. */
        $Reference = $fila[$k];
        $k++;

        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        $TransaccionProducto = new TransaccionProducto($Id);

        /* instancia objetos de Usuario, Producto y Proveedor a partir de un TransaccionProducto. */
        $Usuario = new Usuario($TransaccionProducto->usuarioId);
        $Producto = new Producto ($TransaccionProducto->getProductoId());
        $Proveedor = new Proveedor($Producto->getProveedorId());


        if ($Proveedor->getTipo() == "PAYMENT") {

            switch ($State) {
                case "0":
                    /* aprueba un depósito bajo ciertas condiciones específicas del producto y usuario. */

                    // Aprobar Deposito
                    $comentario = "Aprobado manualmente por " . $UsuarioMandante->getUsuarioMandante();

                    //|| $TransaccionProducto->getEstadoProducto() == "R" ||
                    if (($TransaccionProducto->getEstadoProducto() == "R" && $TransaccionProducto->getProductoId() == "5503") || ($_SESSION["win_perfil2"] == "SA") || ($_SESSION["usuario"] == "96946") || $TransaccionProducto->getEstadoProducto() == "E" || ($TransaccionProducto->getEstadoProducto() == "A" && ($TransaccionProducto->getFinalId() == "" || $TransaccionProducto->getFinalId() == "0"))) {
                        $respuesta = $TransaccionProducto->setAprobada($TransaccionProducto->transproductoId, "M", "A", $comentario, "{}", $Reference);
                    }

                    break;

                case "1":
                    // Rechazar Deposito


                    /* Código que maneja el rechazo manual de una transacción de producto. */
                    $comentario = "Rechazado manualmente por " . $UsuarioMandante->getUsuarioMandante();

                    if ($TransaccionProducto->getEstadoProducto() == "E" || $TransaccionProducto->getEstadoProducto() == "A") {
                        if ($Proveedor->getAbreviado() == "PAYMENTEZ") {

                            $PAYMENTEZSERVICES = new \Backend\integrations\payment\PAYMENTEZSERVICES();

                            $PAYMENTEZSERVICES->refund($Usuario, $TransaccionProducto->externoId, $TransaccionProducto->getValor() + $TransaccionProducto->getImpuesto());

                            $respuesta = $TransaccionProducto->setRechazadaManualmente($TransaccionProducto->transproductoId, "M", "A", $comentario, "{}", "");


                        } else {

                            $respuesta = $TransaccionProducto->setRechazadaManualmente($TransaccionProducto->transproductoId, "M", "A", $comentario, "{}", "");
                        }
                    }


                    break;
            }
        }


    }


    /* Código PHP inicializa un arreglo para manejar respuestas de éxito y errores. */
    $response = [];
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];


} else {
    /* crea una respuesta de error en caso de falla. */

    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}


?>