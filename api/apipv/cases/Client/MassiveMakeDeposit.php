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
use Backend\dto\SitioTracking;
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
 * UserManagement/MakeDeposit
 *
 * Este script permite realizar depósitos masivos a usuarios.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params ->CSV Cadena codificada en base64 con los datos de los depósitos.
 * @param string $params ->Note Nota asociada al depósito.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Resultados de los depósitos procesados.
 *
 * @throws Exception Si los parámetros enviados son incorrectos.
 * @throws Exception Si el punto de venta no tiene cupo disponible.
 * @throws Exception Si el depósito excede los límites permitidos.
 */


/* Decodifica un CSV base64, reemplaza caracteres y lo separa en líneas. */
$ClientIdCsv = $params->CSV;
$ClientIdCsv = explode("base64,", $ClientIdCsv);
$ClientIdCsv = $ClientIdCsv[1];
$ClientIdCsv = base64_decode($ClientIdCsv);
$ClientIdCsv = str_replace(";", ",", $ClientIdCsv);

$lines = explode(PHP_EOL, $ClientIdCsv);

/* divide un CSV en líneas y las convierte a un array. */
$lines = preg_split('/\r\n|\r|\n/', $ClientIdCsv);

$AmountCVS = $ClientIdCsv[1];
$array = array();
foreach ($lines as $line) {
    $array[] = str_getcsv($line);

}


/* extrae columnas del arreglo $array y las almacena en $arrayfinal. */
$countArray = oldCount($array[0]);

for ($i = 0; $i <= $countArray; $i++) {
    $arrayfinal = array();
    $arrayfinal = array_column($array, $i);
}


/* obtiene claves de un array y elimina la última si está vacía. */
$posiciones = array_keys($array);

$ultima = strval(end($posiciones));
$arrayfinal = json_decode(json_encode($array));


if ($arrayfinal[$ultima][0] == "") {

    unset($arrayfinal[$ultima]);
}

/* Se inicializa un array vacío en PHP llamado "$Table". */
$Table = array();

if (!empty($arrayfinal)) {


    foreach ($arrayfinal as $key1 => $valueClient) {


        /* Se valida el perfil del usuario para asegurar que corresponde a tipos permitidos. */
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
        $UsuarioPerfil2 = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());
        $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);
        $UsuarioConfig = new UsuarioConfig($UsuarioPuntoVenta->puntoventaId);


        if ($UsuarioPerfil2->perfilId != 'PUNTOVENTA' && $UsuarioPerfil2->perfilId != 'CAJERO') {
            throw new Exception("Error en los parametros enviados", "100001");

        }


        /* limpia y formatea el identificador del cliente, eliminando caracteres no deseados. */
        $ClientId = $valueClient[0];

        $ClientId = preg_replace('/[\xE2\x80\xAF]/', '', $ClientId);
        $ClientId = str_replace(" ", '', $ClientId);
        $ClientId = preg_replace("/[^0-9.]/", "", $ClientId);

        if ($ClientId != "" && $ClientId != "0") {

            try {


                /* asigna valores de cliente a variables y crea un objeto UsuarioPerfil. */
                $Amount = $valueClient[1];

                $Id = $ClientId;
                $Note = $params->Note;
                $tipo = 'E';
                $UsuarioPerfil = new UsuarioPerfil($valueClient[0]);

                /* Se crea una nueva instancia de la clase Usuario utilizando un identificador. */
                $Usuario = new Usuario($Id);

                if ($UsuarioPerfil->getPerfilId() == "USUONLINE" && $Usuario->mandante == $UsuarioPuntoVenta->mandante && $Usuario->paisId == $UsuarioPuntoVenta->paisId) {


                    /* valida permisos y parámetros antes de permitir una recarga, lanzando excepciones si fallan. */
                    if ($UsuarioConfig->permiteRecarga == "N") {

                        throw new Exception("No dispone de autorizacion para ejecutar esta operacion", "100004");

                    }

                    if ($Amount <= 0) {

                        throw new Exception("Error en los parametros enviados", "100001");
                    }


                    /* Lanza una excepción si el monto es menor a 1 PEN en el sistema. */
                    if ($Amount < 1 && $UsuarioPuntoVenta->moneda == 'PEN') {

                        throw new Exception("No se puede realizar un deposito menor a 1 PEN", "100001");
                    }


                    if (($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") && floatval($PuntoVenta->valorCupo2) > 0) {


                        /* Se inicializa un contador y se establecen reglas de validación para datos. */
                        $recargadoHoy = 0;

                        $rules = [];

                        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

                        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
                        //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
                        //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


                        /* crea un filtro JSON y obtiene datos de recargas de usuarios. */
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $UsuarioRecarga = new UsuarioRecarga();

                        $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", 0, 5, $json, true, "", "", false);


                        /* Decodifica JSON y asigna 0 a ".total" si está vacío, luego extrae su valor. */
                        $data = json_decode($data);


                        foreach ($data->data as $key => $value) {
                            if ($value->{".total"} == "") {
                                $value->{".total"} = 0;
                            }
                            $recargadoHoy = floatval($value->{".total"});
                        }


                        /* Verifica si la recarga supera el cupo máximo y lanza una excepción si es así. */
                        if (($recargadoHoy + $Amount) > floatval($PuntoVenta->valorCupo2)) {
                            throw new Exception("Excedio el cupo maximo permitido de recarga. Consulte con su administrador", "100005");

                        }

                    }


                    /* Calcula el cupo disponible y lanza excepción si es insuficiente para la recarga. */
                    $cupo = floatval($PuntoVenta->getCreditosBase()) - floatval($Amount);


                    if ($cupo <= 0) {
                        throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100002");
                    }


                    /* Verifica límites de depósito en un entorno de desarrollo y lanza excepción si excede. */
                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    if ($ConfigurationEnvironment->isDevelopment()) {

                        $UsuarioConfiguracion = new UsuarioConfiguracion();

                        $UsuarioConfiguracion->setUsuarioId($Id);
                        $result = $UsuarioConfiguracion->verifyLimitesDeposito($Amount);

                        if ($result != '0') {
                            throw new Exception("Limite de deposito", $result);
                        }
                    }


                    /* Crea un nuevo registro de recarga para un usuario con fecha y hora actuales. */
                    $rowsUpdate = 0;
                    $Usuario = new Usuario($ClientId);
                    $UsuarioRecarga = new UsuarioRecarga();
                    //$UsuarioRecarga->setRecargaId($consecutivo_recarga);
                    $UsuarioRecarga->setUsuarioId($Usuario->usuarioId);
                    $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));

                    /* Se configura un objeto de recarga de usuario con datos específicos. */
                    $UsuarioRecarga->setPuntoventaId($UsuarioMandante->getUsuarioMandante());
                    $UsuarioRecarga->setValor($Amount);
                    $UsuarioRecarga->setPorcenRegaloRecarga(0);
                    $dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 40);
                    $UsuarioRecarga->setDirIp($dirIp);
                    $UsuarioRecarga->setPromocionalId(0);

                    /* Se están configurando atributos de un objeto UsuarioRecarga con valores iniciales. */
                    $UsuarioRecarga->setValorPromocional(0);
                    $UsuarioRecarga->setHost(0);
                    $UsuarioRecarga->setMandante($Usuario->mandante);
                    $UsuarioRecarga->setPedido(0);
                    $UsuarioRecarga->setPorcenIva(0);
                    $UsuarioRecarga->setMediopagoId(0);

                    /* Se establecen valores y se inicializan DAO para gestionar transacciones de usuario. */
                    $UsuarioRecarga->setValorIva(0);
                    $UsuarioRecarga->setEstado('A');

                    $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                    $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
                    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);


                    /* Se crean registros y se cargan datos de ciudades desde una base de datos. */
                    $Registro = new Registro('', $Usuario->usuarioId);

                    $CiudadMySqlDAO = new CiudadMySqlDAO();

                    $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
                    $CiudadPuntoVenta = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);


                    /* Consulta el número de depósitos del usuario y estructura detalles en un array. */
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


                    /* agrega un bono interno y actualiza la base de datos del usuario. */
                    $BonoInterno = new BonoInterno();
                    $detalles = json_decode(json_encode($detalles));

                    $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);

                    $rowsUpdate = $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);
                    {
                    }

                    //$UsuarioRecarga->setRecargaId($consecutivo_recarga);


                    /* realiza una recarga de crédito y maneja errores en la actualización. */
                    $consecutivo_recarga = $UsuarioRecarga->recargaId;

                    $rowsUpdate = 0;

                    $rowsUpdate = $Usuario->credit($Amount, $Transaction);

                    if ($rowsUpdate == null || $rowsUpdate <= 0) {
                        throw new Exception("Error General", "100000");
                    }


                    /* Crea un historial de usuario registrando un movimiento específico en el sistema. */
                    $rowsUpdate = 0;

                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');

                    /* Se establece un historial de usuario con valores y referencias específicas en la base de datos. */
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(10);
                    $UsuarioHistorial->setValor($Amount);
                    $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);

                    /* Inserta historial de usuario y maneja errores con excepciones si la inserción falla. */
                    $rowsUpdate = $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                    if ($rowsUpdate == null || $rowsUpdate <= 0) {
                        throw new Exception("Error General", "100000");
                    }

                    $rowsUpdate = 0;

                    /* Verifica el perfil de sesión y actualiza el balance de créditos según tipo. */
                    if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3" or $_SESSION["win_perfil"] == "PUNTOVENTA" or $_SESSION["win_perfil"] == "CAJERO") {

                        if ($tipo == "S") {
                            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);

                        } else {
                            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
                        }

                        //$PuntoVenta->update($PuntoVenta);

                    }


                    /* verifica si no hay filas actualizadas y lanza una excepción. */
                    if ($rowsUpdate == null || $rowsUpdate <= 0) {
                        throw new Exception("Error General", "100000");
                    }

                    $rowsUpdate = 0;

                    $FlujoCaja = new FlujoCaja();

                    /* Se establecen datos para un flujo de caja, incluyendo fecha, hora y valores. */
                    $FlujoCaja->setFechaCrea(date('Y-m-d'));
                    $FlujoCaja->setHoraCrea(date('H:i'));
                    $FlujoCaja->setUsucreaId($UsuarioMandante->getUsuarioMandante());
                    $FlujoCaja->setTipomovId('E');
                    $FlujoCaja->setValor($UsuarioRecarga->getValor());
                    $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());

                    /* Se configuran propiedades del objeto $FlujoCaja dependiendo del usuario y condiciones. */
                    $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
                    $FlujoCaja->setTraslado('N');
                    $FlujoCaja->setFormapago1Id(1);
                    $FlujoCaja->setCuentaId('0');

                    if ($FlujoCaja->getFormapago2Id() == "") {
                        $FlujoCaja->setFormapago2Id(0);
                    }


                    /* verifica valores vacíos y los reemplaza por cero en FlujoCaja. */
                    if ($FlujoCaja->getValorForma1() == "") {
                        $FlujoCaja->setValorForma1(0);
                    }

                    if ($FlujoCaja->getValorForma2() == "") {
                        $FlujoCaja->setValorForma2(0);
                    }


                    /* valida y establece valores predeterminados para cuenta y porcentaje de IVA. */
                    if ($FlujoCaja->getCuentaId() == "") {
                        $FlujoCaja->setCuentaId('');
                    }

                    if ($FlujoCaja->getPorcenIva() == "") {
                        $FlujoCaja->setPorcenIva(0);
                    }


                    /* verifica el IVA y lo inserta en la base de datos. */
                    if ($FlujoCaja->getValorIva() == "") {
                        $FlujoCaja->setValorIva(0);
                    }

                    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


                    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                    if ($rowsUpdate > 0) {


                        /* Crea un historial de usuario con movimientos y datos inicializados. */
                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($PuntoVenta->getUsuarioId());
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('S');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);

                        /* Se registra un historial de usuario con datos de una recarga. */
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


                        /* Actualiza la fecha del primer depósito si está vacía y confirma la transacción. */
                        if ($Usuario->fechaPrimerdeposito == "") {
                            $Usuario->fechaPrimerdeposito = date('Y-m-d H:i:s');
                            $UsuarioMySqlDAO2 = new UsuarioMySqlDAO($Transaction);
                            $UsuarioMySqlDAO2->update($Usuario);
                        }


                        $Transaction->commit();


                    } else {
                        /* Se lanza una excepción general con un mensaje y un código específico. */

                        throw new Exception("Error General", "100000");
                    }
                } else {
                    /* Lanza una excepción si los parámetros enviados son incorrectos, con un mensaje específico. */

                    throw new Exception("Error en los parametros enviados", "100001");

                }
            } catch (Exception $e) {
                /* Captura excepciones, registra error y continúa con la siguiente iteración del proceso. */

                array_push($Table, array("UserId" => $ClientId, "Amount" => $Amount, "State" => "Incorrecto"));
                continue;
            }


            /* Agrega un nuevo elemento a la matriz $Table con datos del usuario y estado. */
            array_push($Table, array("UserId" => $ClientId, "Amount" => $Amount, "State" => "Correcto"));
        }

    }
}


/* establece una respuesta de éxito para una operación realizada. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];
$response["Data"] = $Table;
