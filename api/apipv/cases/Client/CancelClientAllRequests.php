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
 * CancelClientAllRequests
 *
 * Rechaza múltiples solicitudes de retiro o recarga de clientes.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param array $params ->Ids Lista de identificadores de las solicitudes.
 * @param string $params ->RejectReason Razón del rechazo.
 * @param string $params ->ClientNotes Notas del cliente.
 * @param boolean $params ->IsDeposit Indica si las solicitudes son depósitos.
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


    /* Extrae el valor de 'Ids' de la variable '$params'. */
    $Ids = $params->Ids;

    foreach ($Ids as $Id) {

        try {

            /* Se asignan valores de parámetros y se establece un motivo de rechazo si está vacío. */
            $RejectReason = $params->RejectReason;
            $ClientNotes = $params->ClientNotes;
            $IsDeposit = $params->IsDeposit;

            if ($RejectReason == '') {
                $RejectReason = $params->Description;
            }

            if ($IsDeposit) {

                /* termina la ejecución y obtiene el valor de FromCashDesk. */
                exit();
                $FromCashDesk = $param->FromCashDesk;

                if ($FromCashDesk) {


                    /* Se crea una instancia de la clase UsuarioRecarga utilizando el identificador proporcionado. */
                    $UsuarioRecarga = new UsuarioRecarga($Id);


                    if ($UsuarioRecarga->getEstado() == "A") {

                        /* Actualiza el estado de un usuario en la base de datos a 'Inactivo'. */
                        $UsuarioRecarga->setEstado('I');


                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                        $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();

                        $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);


                        /* Se obtienen datos de usuario y punto de venta en el contexto de recargas. */
                        $puntoventa_id = $UsuarioRecarga->getPuntoventaId();
                        $UsuarioPuntoVenta = new Usuario($puntoventa_id);

                        $valor = $UsuarioRecarga->getValor();

                        $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());


                        /* Crea una instancia de FlujoCaja y establece propiedades como fecha, hora y usuario. */
                        $FlujoCaja = new FlujoCaja();
                        $FlujoCaja->setFechaCrea(date('Y-m-d'));
                        $FlujoCaja->setHoraCrea(date('H:i'));
                        $FlujoCaja->setUsucreaId($puntoventa_id);
                        $FlujoCaja->setTipomovId('S');
                        $FlujoCaja->setValor($valor);

                        /* configura propiedades de flujo de caja basadas en un usuario de recarga. */
                        $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                        $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

                        if ($FlujoCaja->getFormapago1Id() == "") {
                            $FlujoCaja->setFormapago1Id(0);
                        }


                        /* Verifica campos vacíos y asigna valor 0 a propiedades de $FlujoCaja. */
                        if ($FlujoCaja->getFormapago2Id() == "") {
                            $FlujoCaja->setFormapago2Id(0);
                        }

                        if ($FlujoCaja->getValorForma1() == "") {
                            $FlujoCaja->setValorForma1(0);
                        }


                        /* asigna valores predeterminados si ciertos campos están vacíos. */
                        if ($FlujoCaja->getValorForma2() == "") {
                            $FlujoCaja->setValorForma2(0);
                        }

                        if ($FlujoCaja->getCuentaId() == "") {
                            $FlujoCaja->setCuentaId(0);
                        }


                        /* Establece valores por defecto de IVA a cero si no están definidos. */
                        if ($FlujoCaja->getPorcenIva() == "") {
                            $FlujoCaja->setPorcenIva(0);
                        }

                        if ($FlujoCaja->getValorIva() == "") {
                            $FlujoCaja->setValorIva(0);
                        }


                        /* inserta datos de flujo de caja y actualiza el balance en un punto de venta. */
                        $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                        $FlujoCajaMySqlDAO->insert($FlujoCaja);

                        $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

                        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);


                        /* Verifica si no hay filas actualizadas y lanza una excepción en caso afirmativo. */
                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                            throw new Exception("Error General", "100000");
                        }


                        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();


                        /* Actualiza un registro de punto de venta y establece un nuevo saldo online. */
                        $PuntoVentaMySqlDAO->update($PuntoVenta);


                        $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                        $SaldoUsuonlineAjuste->setTipoId('S');

                        /* ajusta el saldo del usuario con información de recarga y fecha. */
                        $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                        $SaldoUsuonlineAjuste->setValor($valor);
                        $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                        $SaldoUsuonlineAjuste->setUsucreaId($_SESSION["usuario"]);
                        $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                        $SaldoUsuonlineAjuste->setObserv("Reversion recarga " . $UsuarioRecarga->getRecargaId());

                        /* asigna un ID y dirección IP a un objeto si están vacíos. */
                        if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                            $SaldoUsuonlineAjuste->setMotivoId(0);
                        }
                        $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                        $SaldoUsuonlineAjuste->setDirIp($dir_ip);

                        /* asigna un mandante y registra ajustes de saldo en MySQL. */
                        $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());


                        $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                        $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                        /* Código para debitar un monto y registrar historial de usuario. */
                        $Usuario->debit($UsuarioRecarga->getValor(), $Transaction);


                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
                        $UsuarioHistorial->setDescripcion('');

                        /* Se establecen propiedades del objeto UsuarioHistorial para registrar un movimiento específico. */
                        $UsuarioHistorial->setMovimiento('S');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($valor);
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());


                        /* Código para insertar un historial de usuario en una base de datos MySQL. */
                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
                        $UsuarioHistorial->setDescripcion('');

                        /* Se configura el historial del usuario con datos de una recarga específica. */
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());


                        /* Inserta un historial de usuario en la base de datos y confirma la transacción. */
                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


                        $Transaction->commit();

                        $response["HasError"] = false;

                        /* Código para estructurar una respuesta exitosa con mensajes y datos vacíos. */
                        $response["AlertType"] = "success";
                        $response["AlertMessage"] = '';
                        $response["ModelErrors"] = [];
                        $response["Data"] = [];
                    } else {
                        /* Código que maneja un error al intentar cambiar un estado de retiro procesado. */

                        $response["HasError"] = true;
                        $response["AlertType"] = "danger";
                        $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
                        $response["ModelErrors"] = [];
                        $response["Data"] = [];

                    }
                } else {

                    /* Se crea una nueva instancia de UsuarioRecarga utilizando el identificador proporcionado. */
                    $CuentaCobro = new UsuarioRecarga($Id);

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
                        /* Código que maneja un error al intentar cambiar un retiro procesado. */

                        $response["HasError"] = true;
                        $response["AlertType"] = "danger";
                        $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
                        $response["ModelErrors"] = [];
                        $response["Data"] = [];

                    }
                }


            } else {


                /* Se crea una nueva instancia de la clase CuentaCobro usando el identificador proporcionado. */
                $CuentaCobro = new CuentaCobro($Id);

                if (($CuentaCobro->getEstado() != "I" || ($CuentaCobro->getEstado() == "I" && $CuentaCobro->getPuntoventaId() == "0")) && $CuentaCobro->getEstado() != "R" && $CuentaCobro->getEstado() != "E") {

                    /* ajusta el estado de "CuentaCobro" según su estado actual y usuario. */
                    if ($CuentaCobro->getEstado() == "I") {
                        $CuentaCobro->setEstado('D');
                    } else {
                        $CuentaCobro->setEstado('R');
                    }
                    $CuentaCobro->setUsurechazaId($_SESSION['usuario2']);
                    //$CuentaCobro->setFechaCambio(date("Y-m-d H:i:s"));
                    //$CuentaCobro->setDiripCambio((new ConfigurationEnvironment())->get_client_ip());

                    /* establece mensajes, observaciones y fecha en objeto CuentaCobro. */
                    $CuentaCobro->setMensajeUsuario($ClientNotes);
                    $CuentaCobro->setObservacion($RejectReason);
                    $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));

                    if ($CuentaCobro->getUsupagoId() == "") {
                        $CuentaCobro->setUsupagoId(0);
                    }


                    /* Asigna fechas actuales si las propiedades están vacías en objeto CuentaCobro. */
                    if ($CuentaCobro->getFechaAccion() == "") {
                        $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
                    }

                    if ($CuentaCobro->getFechaCambio() == "") {
                        $CuentaCobro->setFechaCambio(date("Y-m-d H:i:s"));
                    }


                    /* Actualiza el estado de 'CuentaCobro', lanzando excepciones en caso de error. */
                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (cuenta_cobro.estado != 'I' OR (cuenta_cobro.estado = 'I' AND cuenta_cobro.puntoventa_id = '0') ) AND cuenta_cobro.estado != 'R' AND cuenta_cobro.estado != 'E' ");

                    if ($rowsUpdate == null || $rowsUpdate <= 0) {
                        throw new Exception("Error General", "100000");
                    }


                    /* Se verifica el perfil del usuario y se registra un movimiento de crédito. */
                    $Usuario = new Usuario($CuentaCobro->getUsuarioId());
                    $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());

                    if ($UsuarioPerfil->getPerfilId() == "USUONLINE") {
                        $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroMySqlDAO->getTransaction());

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(40);
                        $UsuarioHistorial->setValor($CuentaCobro->getValor());
                        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                    } else {
                        /* actualiza los créditos de un usuario en la base de datos. */

                        /*$Usuario->creditosAfiliacion = "creditos_afiliacion + " . $CuentaCobro->getValor();

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

                        $UsuarioMySqlDAO->update($Usuario);*/

                    }


                    /* Confirma transacción y establece respuesta sin errores ni mensajes de alerta. */
                    $CuentaCobroMySqlDAO->getTransaction()->commit();

                    $response["HasError"] = false;
                    $response["AlertType"] = "success";
                    $response["AlertMessage"] = '';
                    $response["ModelErrors"] = [];

                    /* Inicializa un arreglo vacío llamado "Data" dentro de la respuesta. */
                    $response["Data"] = [];
                } else {
                    /* Código que maneja un error al intentar cambiar un retiro ya procesado. */

                    $response["HasError"] = true;
                    $response["AlertType"] = "danger";
                    $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
                    $response["ModelErrors"] = [];
                    $response["Data"] = [];

                }
            }
        } catch (Exception $e) {
            /* Bloque para manejar excepciones en PHP sin realizar ninguna acción específica. */


        }
    }
}
