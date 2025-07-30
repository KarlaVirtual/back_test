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
 * Financial/ActionDepositUser
 *
 *  Realizar el deposito a un usuario
 *
 * @param int $Id :       Identificador único de la recarga que se desea anular.
 *                        Este ID se utiliza para instanciar el objeto UsuarioRecarga y realizar las operaciones necesarias sobre la recarga específica.
 * @param string $State :  Estado de la recarga. Este parámetro se utiliza para determinar si la recarga se encuentra activa o inactiva.
 *
 * Descripcion: Permite realizar o anular un depósito a un usuario en el sistema.
 *              Las anulaciones están sujetas a múltiples validaciones, como:
 *              - Permisos del usuario.
 *              - Tiempo transcurrido desde la creación del depósito.
 *              - Límite de anulaciones permitidas en un periodo determinado.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *                          Response es un array que contiene los siguientes atributos:
 *                          - HasError: booleano que indica si hubo un error en la operación.
 *                          - AlertType: string que indica el tipo de alerta que se mostrará en la vista.
 *                          - AlertMessage: string que contiene el mensaje que se mostrará en la vista.
 *                          - ModelErrors: array que contiene los errores de validación del modelo.
 *
 */


/* Verifica permisos de usuario antes de continuar con la acción de depósito. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
if (!$ConfigurationEnvironment->checkUserPermission('Financial/ActionDepositUser', $_SESSION['win_perfil'], $_SESSION['usuario'])) {
    throw new Exception('Permiso denegado', 100035);
}
$Id = $params->Id;
$State = $params->State;

if ($State == "1") {

    /* Se crean instancias de UsuarioMandante y UsuarioRecarga usando datos de sesión y ID. */
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


    $UsuarioRecarga = new UsuarioRecarga($Id);


    if ($UsuarioRecarga->getMediopagoId() == 0 || $UsuarioRecarga->getMediopagoId() == "") {

        if ($UsuarioRecarga->getEstado() == "A") {

            /* Se crea una instancia de la clase Usuario usando un ID específico. */
            $UsuarioDeRecarga = new Usuario($UsuarioRecarga->usuarioId);

            try {


                /* Se crean instancias de usuario y clasificador para manejar transacciones y validaciones. */
                $UsuarioPuntoVenta = new Usuario($UsuarioRecarga->getPuntoventaId());
                $MandantePerfil = new UsuarioPerfil($UsuarioDeRecarga->usuarioId);
                $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

                //Solicitud mandante_detalle necesario para la validación de abajo
                $clasificador = new Clasificador("", "LIMITEHORASPARAANULARDEPOSITO");

                /* Código que valida si una recarga se puede anular dentro de 24 horas. */
                $horasLimite = new MandanteDetalle("", $UsuarioDeRecarga->mandante, $clasificador->clasificadorId, $UsuarioDeRecarga->paisId, "A");

                $horasLimite = $horasLimite->valor;
                $horasLimiteUnix = $horasLimite * 3600;

                //Validación plazo máximo de anulación de una recarga por PV
                /**Se especifica que una recarga no puede tener una antigüdad superior a 24 horas para ser anulada */
                $fechaActualUnix = getdate()[0];

                /* valida la antigüedad de una recarga antes de permitir su anulación. */
                $fechaCreaUnix = strtotime($UsuarioRecarga->getFechaCrea());

                if (($fechaActualUnix - $fechaCreaUnix) > $horasLimiteUnix) {
                    throw new Exception('No es posible anular esta recarga por antigüedad', 110006);
                }
                //FINAL Validación del plazo máximo de anulación de una recarga

                //Solicitud mandante_detalle necesarios para la validación del límite de anulaciones por periodo
                $clasificador = new Clasificador("", "LIMITEANULACIONDEPOSITOSPORPERIODO");

                /* Configura límites de anulaciones de depósitos basados en periodos y mandantes. */
                $limiteAnulacionesDepositoPorPeriodo = new MandanteDetalle("", $UsuarioDeRecarga->mandante, $clasificador->clasificadorId, $UsuarioDeRecarga->paisId, "A");
                $limiteAnulacionesDepositoPorPeriodo = $limiteAnulacionesDepositoPorPeriodo->valor;

                //-- Esta opción fue cancelada No se borra a espera de posible uso DTR --Solicitud mandante_detalle necesarios para la validación del periodo (dias) límite para límite de anulaciones por periodo
                // $clasificador = new Clasificador("", "PERIODOPARALIMITEANULACIONDEPOSITOSPORPERIODO");
                // $diasLimite = new MandanteDetalle("", $UsuarioDeRecarga->mandante, $clasificador->clasificadorId, $UsuarioDeRecarga->paisId, "A");
                // $diasLimite = $diasLimite->valor;
                // $fechaMinima = date('Y-m-d 00:00:00', strtotime('-' . $diasLimite . ' day'));

                //Validación cantidad máxima de anulaciones dentro de un plazo establecido para PV
                $fechaMinima = date('Y-m-d 00:00:00');

                /* Se define un conjunto de filtros y reglas para consultar datos de recargas. */
                $filters = array();
                $rules = array();
                $select = "usuario_recarga.recarga_id, usuario_recarga.usuario_id, usuario_recarga.usuelimina_id, usuario_recarga.fecha_elimina, usuario_recarga.valor";
                array_push($rules, array("field" => "usuario_recarga.estado", "data" => "I", "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.mandante", "data" => $UsuarioDeRecarga->mandante, "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.puntoventa_id", "data" => $UsuarioRecarga->puntoventaId, "op" => "eq"));

                /* verifica y limita las cancelaciones de depósitos por día, lanzando una alerta. */
                array_push($rules, array("field" => "usuario_recarga.fecha_elimina", "data" => $fechaMinima, "op" => "gt"));
                $filters = json_encode(array("rules" => $rules, "groupOp" => "AND"));
                $totalDepositosCancelados = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.recarga_id", "asc", 0, 10000, $filters, true, "", "", true);
                $totalDepositosCancelados = json_decode($totalDepositosCancelados, true);

                /**Si la cantidad de registros cancelados es igual o superior a la cantidad entregada
                 * por mandante_detalle se lanzará la alerta*/
                if ($totalDepositosCancelados['count'][0][0] >= $limiteAnulacionesDepositoPorPeriodo) {
                    throw new Exception('Has alcanzado el máximo de cancelaciones de depósitos por día', 110007);
                }
                //FINAL Validación cantidad máxima de anulaciones dentro de un plazo establecido para PV

            } catch (Exception $e) {
                /* Maneja excepciones específicas, relanzando solo ciertos códigos de error. */


                if ($e->getCode() == '110007' || $e->getCode() == '110006') {
                    throw $e;
                }
            }

            /* Se obtienen datos de usuario y transacciones desde una base de datos MySQL. */
            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
            $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();

            $valor = $UsuarioRecarga->getValor();

            $puntoventa_id = $UsuarioRecarga->getPuntoventaId();

            /* Código para actualizar el estado y fecha de eliminación de un usuario en un sistema. */
            $UsuarioPuntoVenta = new Usuario($puntoventa_id);
            $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

            $UsuarioRecarga->setEstado('I');
            $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
            $UsuarioRecarga->setUsueliminaId($UsuarioMandante->getUsuarioMandante());


            /* actualiza un usuario y registra datos de flujo de caja. */
            $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);

            $FlujoCaja = new FlujoCaja();
            $FlujoCaja->setFechaCrea(date('Y-m-d'));
            $FlujoCaja->setHoraCrea(date('H:i'));
            $FlujoCaja->setUsucreaId($UsuarioRecarga->getPuntoventaId());

            /* configura un objeto de flujo de caja con datos de recarga y pago. */
            $FlujoCaja->setTipomovId('S');
            $FlujoCaja->setValor($valor);
            $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
            $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

            if ($FlujoCaja->getFormapago1Id() == "") {
                $FlujoCaja->setFormapago1Id(0);
            }


            /* verifica y establece valores predeterminados en propiedades del objeto $FlujoCaja. */
            if ($FlujoCaja->getFormapago2Id() == "") {
                $FlujoCaja->setFormapago2Id(0);
            }

            if ($FlujoCaja->getValorForma1() == "") {
                $FlujoCaja->setValorForma1(0);
            }


            /* Asigna valores predeterminados a propiedades vacías de un objeto FlujoCaja. */
            if ($FlujoCaja->getValorForma2() == "") {
                $FlujoCaja->setValorForma2(0);
            }

            if ($FlujoCaja->getCuentaId() == "") {
                $FlujoCaja->setCuentaId(0);
            }


            /* asigna cero a impuestos si los valores son cadenas vacías. */
            if ($FlujoCaja->getPorcenIva() == "") {
                $FlujoCaja->setPorcenIva(0);
            }

            if ($FlujoCaja->getValorIva() == "") {
                $FlujoCaja->setValorIva(0);
            }

            /* Se establece una devolución y se inserta un flujo de caja en la base de datos. */
            $FlujoCaja->setDevolucion('S');

            $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
            $FlujoCajaMySqlDAO->insert($FlujoCaja);


            $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);


            /* Se actualiza el balance de créditos en el sistema de punto de venta. */
            $PuntoVenta->setBalanceCreditosBase($valor);


            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

            $PuntoVentaMySqlDAO->update($PuntoVenta);


            /* Se crea y configura un objeto de ajuste de saldo para un usuario específico. */
            $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

            $SaldoUsuonlineAjuste->setTipoId('S');
            $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
            $SaldoUsuonlineAjuste->setValor($valor);
            $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));

            /* ajusta el saldo de un usuario y establece observaciones sobre la recarga. */
            $SaldoUsuonlineAjuste->setUsucreaId($UsuarioMandante->getUsuarioMandante());
            $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
            $SaldoUsuonlineAjuste->setObserv("Reversion recarga " . $UsuarioRecarga->getRecargaId());
            if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                $SaldoUsuonlineAjuste->setMotivoId(0);
            }

            /* captura la dirección IP y configura un objeto de saldo ajustado. */
            $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

            $SaldoUsuonlineAjuste->setDirIp($dir_ip);
            $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());
            $SaldoUsuonlineAjuste->setTipo(10);


            $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);


            /* Inserta ajuste de saldo, debita usuario y registra historial de transacciones. */
            $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


            $Usuario->debit($valor, $Transaction);


            $UsuarioHistorial = new UsuarioHistorial();

            /* Configura un historial de usuario con datos específicos en un sistema. */
            $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('S');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(10);

            /* Inserta el historial de usuario con datos de recarga en la base de datos. */
            $UsuarioHistorial->setValor($valor);
            $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

            $UsuarioHistorial = new UsuarioHistorial();

            /* Código para configurar un historial de usuario en un sistema de ventas. */
            $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(10);

            /* inserta un historial de usuario en la base de datos y confirma la transacción. */
            $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
            $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

            $Transaction->commit();


            /* Inicializa un arreglo de respuesta sin errores y con mensaje de éxito. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];


        }
    }


}