<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Banco;
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
use Backend\integrations\payment\MONNETSERVICES;
use Backend\integrations\payout\GLOBOKASSERVICES;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\Integrations\payout\PAYBROKERSSERVICES;
use Backend\integrations\payout\WEPAY4USERVICES;
use Backend\integrations\payment\ASTROPAYCARDSERVICES;


/**
 * CancelClientRequests
 *
 * Rechaza solicitudes de retiro o recarga de clientes.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param int $params ->Id Identificador de la solicitud.
 * @param string $params ->RejectReason Razón del rechazo.
 * @param string $params ->ClientNotes Notas del cliente.
 * @param boolean $params ->IsDeposit Indica si la solicitud es un depósito.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - "HasError" (boolean): Indica si ocurrió un error.
 * - "AlertType" (string): Tipo de alerta (e.g., "success", "error").
 * - "AlertMessage" (string): Mensaje de alerta.
 * - "ModelErrors" (array): Lista de errores del modelo, si los hay.
 * - "Data" (array): Datos adicionales relacionados con la operación.
 */

if (true) {

    // Captura los parámetros de entrada
    $Id = $params->Id; // ID de la recarga
    $RejectReason = $params->RejectReason; // Razón del rechazo
    $ClientNotes = $params->ClientNotes; // Notas del cliente
    $IsDeposit = $params->IsDeposit; // Indica si es un depósito

    // Si no se proporciona razón de rechazo, se toma la descripción
    if($RejectReason==''){
        $RejectReason = $params->Description;
    }

    // Verificación si es un depósito
    if ($IsDeposit) {
exit();
        // Obtiene el escritorio de caja de los parámetros
        $FromCashDesk = $param->FromCashDesk;

        // Condición si proviene del escritorio de caja
        if ($FromCashDesk) {

            $UsuarioRecarga = new UsuarioRecarga($Id);


            // Verifica el estado de la recarga
            if ($UsuarioRecarga->getEstado() == "A") {
                $UsuarioRecarga->setEstado('I');


                // Inicializa el DAO de usuario recarga
                $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();

                // Actualiza la recarga en la base de datos
                $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);

                // Obtiene el punto de venta del usuario recarga
                $puntoventa_id = $UsuarioRecarga->getPuntoventaId();
                $UsuarioPuntoVenta = new Usuario($puntoventa_id);

                // Obtiene el valor de la recarga
                $valor = $UsuarioRecarga->getValor();

                // Crea un nuevo objeto Usuario
                $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

                // Inicializa el flujo de caja
                $FlujoCaja = new FlujoCaja();
                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                $FlujoCaja->setHoraCrea(date('H:i'));
                $FlujoCaja->setUsucreaId($puntoventa_id);
                $FlujoCaja->setTipomovId('S');
                $FlujoCaja->setValor($valor);
                $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

                // Se asegura que los métodos de pago y valores no sean nulos
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

                // Inserta el flujo de caja en la base de datos
                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                $FlujoCajaMySqlDAO->insert($FlujoCaja);

                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);

                // Verifica si la actualización fue exitosa
                if ($rowsUpdate == null || $rowsUpdate <= 0) {
                    throw new Exception("Error General", "100000");
                }


                // Actualiza el punto de venta
                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

                $PuntoVentaMySqlDAO->update($PuntoVenta);


                // Crea un ajuste en el saldo de Usuonline
                $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                $SaldoUsuonlineAjuste->setTipoId('S');
                $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                $SaldoUsuonlineAjuste->setValor($valor);
                $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                $SaldoUsuonlineAjuste->setUsucreaId($_SESSION["usuario"]);
                $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                $SaldoUsuonlineAjuste->setObserv("Reversion recarga " . $UsuarioRecarga->getRecargaId());
                if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                    $SaldoUsuonlineAjuste->setMotivoId(0);
                }

                // Obtiene la dirección IP del cliente
                $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());


                // Inserta el ajuste en la base de datos
                $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                // Debita el valor de la recarga al usuario
                $Usuario->debit($UsuarioRecarga->getValor(), $Transaction);


                // Crea un historial de usuario para la recarga
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('S');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(10);
                $UsuarioHistorial->setValor($valor);
                $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                // Inserta el historial en la base de datos
                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                // Crea un historial de usuario para el punto de venta
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(10);
                $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');



                // Confirma la transacción
                $Transaction->commit();

                // Prepara la respuesta de éxito
                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = '';
                $response["ModelErrors"] = [];
                $response["Data"] = [];
            } else {
                // Respuesta en caso de que no se pueda cambiar el estado
                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
                $response["ModelErrors"] = [];
                $response["Data"] = [];

            }
        } else {
            // Manejo de cuentas que no provienen del escritorio de caja
            $CuentaCobro = new UsuarioRecarga($Id);

            // Verifica el estado de la cuenta
            if ($CuentaCobro->getEstado() != "I" && $CuentaCobro->getEstado() != "R") {
                /*$CuentaCobro->setEstado('R');
                $CuentaCobro->setUsurechazaId($_SESSION['usuario2']);
                $CuentaCobro->setMensajeUsuario($ClientNotes);
                $CuentaCobro->setObservacion($RejectReason);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);

                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroMySqlDAO->getTransaction());

                $CuentaCobroMySqlDAO->getTransaction()->commit();

                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = '';
                $response["ModelErrors"] = [];
                $response["Data"] = [];*/
            } else {
                // Respuesta en caso de error al cambiar el estado
                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
                $response["ModelErrors"] = [];
                $response["Data"] = [];

            }
        }


    } else {

        // Manejo de cuenta de cobro
        $CuentaCobro = new CuentaCobro($Id);

        if ($CuentaCobro->mediopagoId == 2088007) {

            try {
                $Proveedor = new Proveedor('', 'GLOBOKASRETIROS');
                $Producto = new Producto('', 'GlobokasRetiros', $Proveedor->proveedorId);


                // Intenta eliminar en GLOBOKAS
                if ($Proveedor->getAbreviado() == "GLOBOKASRETIROS") {
                    try {
                        $GLOBOKAS = new GLOBOKASSERVICES();
                        $respon = $GLOBOKAS->Delete($CuentaCobro);
                    } catch (Exception $e) {
                        if ($e->getCode() == 100000) {

                        }
                    }
                }
            } catch (Exception $e) {

            }
        } else {

            try {

                if ($CuentaCobro->productoPagoId != 0 && $CuentaCobro->productoPagoId != null && $CuentaCobro->productoPagoId != " ") {


                    $Producto = new Producto($CuentaCobro->productoPagoId);
                    $Proveedor = new Proveedor($Producto->getProveedorId());

                    // Intenta eliminar en GLOBOKAS
                    if ($Proveedor->getAbreviado() == "GLOBOKASRETIROS") {
                        try {
                            $GLOBOKAS = new GLOBOKASSERVICES();
                            $respon = $GLOBOKAS->Delete($CuentaCobro);
                        } catch (Exception $e) {
                            if ($e->getCode() == 100000) {

                                // Manejo de excepciones
                            }
                        }
                    }
                }
            } catch (Exception $e) {

            }
        }
        if (($CuentaCobro->getEstado() != "I" || ($CuentaCobro->getEstado() == "I" && $CuentaCobro->getPuntoventaId() == "0") || ($CuentaCobro->getEstado() == "I" && $CuentaCobro->getMediopagoId() == "2894342")) && $CuentaCobro->getEstado() != "R" && $CuentaCobro->getEstado() != "E") {
            if ($CuentaCobro->getEstado() == "I") {
                $CuentaCobro->setEstado('D');
            } else {
                $CuentaCobro->setEstado('R');
            }
            $CuentaCobro->setUsurechazaId($_SESSION['usuario2']);
            //$CuentaCobro->setFechaCambio(date("Y-m-d H:i:s"));
            //$CuentaCobro->setDiripCambio((new ConfigurationEnvironment())->get_client_ip());
            $CuentaCobro->setMensajeUsuario($ClientNotes);
            $CuentaCobro->setObservacion($RejectReason);
            $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));

            if ($CuentaCobro->getUsupagoId() == "") {
                $CuentaCobro->setUsupagoId(0);
            }

            if ($CuentaCobro->getFechaAccion() == "") {
                $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
            }

            if ($CuentaCobro->getFechaCambio() == "") {
                $CuentaCobro->setFechaCambio(date("Y-m-d H:i:s"));
            }


            // Actualiza la cuenta en la base de datos
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado!='R' AND estado!='E')");
            if ($rowsUpdate <= 0) throw new Exception('No se puede realizar la cancelacion', '21001');

            // Actualiza el balance del usuario
            $Usuario = new Usuario($CuentaCobro->getUsuarioId());
            $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());

            // Manejo para usuarios con perfil USUONLINE
            if ($UsuarioPerfil->getPerfilId() == "USUONLINE") {
                $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroMySqlDAO->getTransaction());

                // Crea un historial para el usuario
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(40);
                $UsuarioHistorial->setValor($CuentaCobro->getValor());
                $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                // Inserta el historial en la base de datos
                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


            } else {

                if ($CuentaCobro->version == '3') {

                    $Usuario->creditosAfiliacion = "creditos_afiliacion + " . $CuentaCobro->getValor();
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                    $UsuarioMySqlDAO->update($Usuario);

                } elseif ($CuentaCobro->version == '4') {

                    $Amount = $CuentaCobro->valor;
                    $tipo = 'E'; //Tipo Entrada
                    $ClientId = $Usuario->usuarioId;
                    $Note = " ";
                    $tipoCupo = 'A'; //Apuesta
                    $Type = 1;

                    // Crea un nuevo log de cupo
                    $CupoLog = new CupoLog();
                    $CupoLog->setUsuarioId($ClientId);
                    $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
                    $CupoLog->setTipoId($tipo);
                    $CupoLog->setValor($Amount);
                    $CupoLog->setUsucreaId($ClientId);
                    $CupoLog->setMandante($_SESSION['mandante']);
                    $CupoLog->setTipocupoId($tipoCupo);
                    $CupoLog->setObservacion($Note);

                    // Inserta el log de cupo en la base de datos
                    $CupoLogMySqlDAO = new CupoLogMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                    $Transaction = $CupoLogMySqlDAO->getTransaction();

                    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                    $CupoLogMySqlDAO->insert($CupoLog);



                    // Actualiza el balance en el punto de venta
                    $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);
                    //$cant = $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);
                    $cant2 = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);


                    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();
                    $UsuarioClave = $PuntoVentaMySqlDAO->update($PuntoVenta);
                    //$PuntoVentaMySqlDAO->getTransaction()->commit();

                }


            }

            $CuentaCobroMySqlDAO->getTransaction()->commit();

            try {
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                $jsonServer = json_encode($_SERVER);
                $serverCodif = base64_encode($jsonServer);


                $ismobile = '';

                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

                    $ismobile = '1';

                }
//Detect special conditions devices
                $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
                if ($iPod || $iPhone) {
                    $ismobile = '1';
                } else if ($iPad) {
                    $ismobile = '1';
                } else if ($Android) {
                    $ismobile = '1';
                }

                exec("php -f " . __DIR__ . "/../../../src/integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "RETIROELIMINADOCRM" . " " . $CuentaCobro->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

            } catch (Exception $e) {

            }

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = '';
            $response["ModelErrors"] = [];
            $response["Data"] = [];
        } else {
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
            $response["ModelErrors"] = [];
            $response["Data"] = [];

        }
    }
}