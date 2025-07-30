<?php

/**
 * Clase Crm
 *
 * Esta clase maneja los movimientos de CRM, incluyendo login, registro, apuestas, retiros, depósitos, bonos, ajustes
 * de saldo, etc.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-19
 */

namespace Backend\integrations\crm;

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\GeneralLog;
use Backend\dto\ItTicketEnc;
use Backend\dto\ItTransaccion;
use Backend\dto\JackpotInterno;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\PuntoVenta;
use Backend\dto\RuletaDetalle;
use Backend\dto\RuletaInterno;
use Backend\dto\SitioTracking;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioAutomation2;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuariojackpotGanador;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRuleta;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\Pais;
use Backend\dto\PreUsuarioSorteo;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\UsuarioJackpot;
use Backend\dto\UsuarioLealtad;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioSorteo;
use Backend\dto\UsuarioTorneo;
use Backend\dto\VerificacionLog;
use Backend\integrations\poker\EVENBETSERVICES;
use Backend\integrations\virtual\GOLDENRACESERVICES;
use Backend\integrations\virtual\XPRESSSERVICES;
use Backend\mysql\GeneralLogMySqlDAO;
use Backend\mysql\RuletaDetalleMySqlDAO;
use Backend\mysql\TransaccionApiMandanteMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Backend\websocket\WebsocketUsuario;

use Backend\integrations\poker\ESAGAMINGSERVICES;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Esta clase maneja los movimientos de CRM, incluyendo login, registro, apuestas, retiros, depósitos, bonos, ajustes
 * de saldo, etc.
 */
class Crm
{

    /**
     * Maneja los movimientos de CRM.
     *
     * Este metodo procesa diferentes tipos de movimientos en el CRM, como login, registro, apuestas, retiros,
     * depósitos, etc. Según el tipo de movimiento, realiza acciones específicas y emite datos a través de WebSocket.
     *
     * @param string       $UsuarioId    ID del usuario.
     * @param Clasificador $Clasificador Clasificador del movimiento.
     * @param int          $IdMovimiento ID del movimiento.
     * @param string       $Server       Servidor desde el cual se realiza la operación.
     * @param bool         $IsMobile     Indica si la operación se realiza desde un dispositivo móvil.
     *
     * @return void
     */
    function CrmMovements($UsuarioId = '', $Clasificador, $IdMovimiento, $Server, $IsMobile)
    {
        // Verifica si el ID de usuario está vacío
        if (empty($UsuarioId)) {
            // Maneja el caso de apuestas deportivas
            if ($Clasificador->abreviado === 'BETSPORTSBOOKCRM') {
                $ItTransaccion = new ItTransaccion($IdMovimiento);
                if ($ItTransaccion->tipo === 'WIN') {
                    $Clasificador->abreviado = 'WINSPORTSBOOKCRM';
                } elseif ($ItTransaccion->tipo !== 'BET') {
                    $Clasificador->abreviado = '';
                }
            }

            // Maneja el caso de apuestas de casino
            if ($Clasificador->abreviado === 'BETCASINOCRM') {
                $TransaccionApi = new TransaccionApi($IdMovimiento);
                if ($TransaccionApi->tipo === 'CREDIT' && $TransaccionApi->valor > 0) {
                    $Clasificador->abreviado = 'WINCASINOCRM';
                } else {
                    if ($TransaccionApi->tipo !== 'DEBIT') {
                        $Clasificador->abreviado = '';
                    }
                }
            }

            // Maneja el caso de retiros creados
            if ($Clasificador->abreviado === 'RETIROCREADOCRM') {
                $CuentaCobro = new CuentaCobro($IdMovimiento);
                if ($CuentaCobro->estado === 'I') {
                    $Clasificador->abreviado = 'RETIROPAGADOCRM';
                } else {
                    if ( ! in_array($CuentaCobro->estado, ['M', 'P', 'A'])) {
                        $Clasificador->abreviado = '';
                    }
                }
            }
        } else {
            // Crea instancias de Usuario y UsuarioMandante si el ID de usuario no está vacío
            $Usuario = new Usuario($UsuarioId);
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        }

        $emitData = [
            'action' => '',
            'sevice' => $IsMobile == '1' ? 'mobile' : 'descktop',
            'userID' => '',
            'type' => 'modal'
        ];

        switch ($Clasificador->abreviado) {
            case "LOGINCRM":
                // Maneja el caso de inicio de sesión
                $UsuarioLog = new UsuarioLog($IdMovimiento);
                $Usuario = new Usuario($UsuarioLog->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                $emitData['action'] = 'onNextLogin';

                if (true) {
                    if (empty($UsuarioId)) {
                        $UsuarioLog = new UsuarioLog($IdMovimiento);
                        $Usuario = new Usuario($UsuarioLog->usuarioId);
                        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                        $rules = [];
                        array_push(
                            $rules,
                            ['field' => 'usuario_log.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']
                        );
                        array_push($rules, ['field' => 'usuario_log.tipo', 'data' => 'LOGIN', 'op' => 'eq']);

                        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                        $queryUserLog = (string)$UsuarioLog->getUsuarioLogsCustom(
                            'MIN(usuario_log.usuariolog_id) usuariolog_id',
                            'usuario_log.usuariolog_id',
                            'ASC',
                            0,
                            1,
                            $filters,
                            true
                        );
                        $queryUserLog = json_decode($queryUserLog, true);

                        sleep(2);

                        if ($queryUserLog['cont'][0]['.count'] === 0 || $queryUserLog['data'][0]['.usuariolog_id'] === $IdMovimiento) {
                            $emitData['action'] = 'onFirstLogin';
                        } else {
                            $emitData['action'] = 'onNextLogin';
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventLogin($Usuario, $Server, $IsMobile);
                                break;
                            case "FASTTRACK":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de registro en el CRM.
             * Si el ID de usuario está vacío, crea un nuevo registro y emite la acción 'onRegister'.
             * Si el ID de usuario no está vacío, maneja el registro según el proveedor.
             */
            case "REGISTROCRM":
                $emitData['action'] = 'onRegister';
                $Registro = new Registro($IdMovimiento);
                $Usuario = new Usuario($Registro->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                if (false) {
                    if (empty($UsuarioId)) {
                        $Registro = new Registro($IdMovimiento);
                        $Usuario = new Usuario($Registro->usuarioId);
                        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventRegistros($Usuario, $Server, $IsMobile);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de apuestas deportivas en el CRM.
             * Si el ID de usuario está vacío, verifica la primera apuesta o la siguiente.
             * Si el ID de usuario no está vacío, maneja la apuesta según el proveedor.
             */
            case "BETSPORTSBOOKCRM":

                $emitData['action'] = 'onNextSportbookBet';
                $Usuario = new Usuario($ItTransaccion->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                if (false) {
                    if (empty($UsuarioId)) {
                        $Usuario = new Usuario($ItTransaccion->usuarioId);
                        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                        $rules = [];
                        array_push(
                            $rules,
                            ['field' => 'it_transaccion.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']
                        );
                        array_push(
                            $rules,
                            ['field' => 'it_transaccion.mandante', 'data' => $Usuario->mandante, 'op' => 'eq']
                        );
                        array_push($rules, ['field' => 'it_transaccion.tipo', 'data' => 'BET', 'op' => 'eq']);

                        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                        $ItTicketEnc = new ItTicketEnc();
                        $queryItTransaction = (string)$ItTicketEnc->getTicketTransactionsCustom(
                            'MIN(it_transaccion.it_cuentatrans_id) it_cuentatrans_id',
                            'it_transaccion.it_cuentatrans_id',
                            'ASC',
                            0,
                            1,
                            $filters,
                            true,
                            false
                        );
                        $queryItTransaction = json_decode($queryItTransaction, true);

                        if ($queryItTransaction['count'][0]['.count'] === 0 || $queryItTransaction['data'][0]['.it_cuentatrans_id'] === $IdMovimiento) {
                            $emitData['action'] = 'onFirstSportbookBet';
                        } else {
                            $emitData['action'] = 'onNextSportbookBet';
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $ItTicketEnc = new ItTicketEnc($IdMovimiento);
                                $Valor = $ItTicketEnc->vlrApuesta;
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventApuestasDeportivas($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de ganancias deportivas en el CRM.
             * Si el ID de usuario está vacío, verifica la primera ganancia o la siguiente.
             * Si el ID de usuario no está vacío, maneja la ganancia según el proveedor.
             */
            case "WINSPORTSBOOKCRM":
                $emitData['action'] = 'onNextSportbookWin';
                $Usuario = new Usuario($ItTransaccion->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                if (false) {
                    if (empty($UsuarioId)) {
                        $Usuario = new Usuario($ItTransaccion->usuarioId);
                        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                        $rules = [];
                        array_push(
                            $rules,
                            ['field' => 'it_transaccion.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']
                        );
                        array_push(
                            $rules,
                            ['field' => 'it_transaccion.mandante', 'data' => $Usuario->mandante, 'op' => 'eq']
                        );
                        array_push($rules, ['field' => 'it_transaccion.tipo', 'data' => 'BET', 'op' => 'eq']);

                        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                        $ItTicketEnc = new ItTicketEnc();
                        $queryItTransaction = (string)$ItTicketEnc->getTicketTransactionsCustom(
                            'MIN(it_transaccion.it_cuentatrans_id) it_cuentatrans_id',
                            'it_transaccion.it_cuentatrans_id',
                            'ASC',
                            0,
                            1,
                            $filters,
                            true,
                            false
                        );
                        $queryItTransaction = json_decode($queryItTransaction, true);

                        if ($queryItTransaction['count'][0]['.count'] === 0 || $queryItTransaction['data'][0]['.it_cuentatrans_id'] === $IdMovimiento) {
                            $emitData['action'] = 'onFirstSportbookWin';
                        } else {
                            $emitData['action'] = 'onNextSportbookWin';
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $ItTicketEnc = new ItTicketEnc($IdMovimiento);
                                $Valor = $ItTicketEnc->vlrPremio;
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventGananciasDeportivas($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de apuestas de casino en el CRM.
             * Si el ID de usuario está vacío, verifica la primera apuesta o la siguiente.
             * Si el ID de usuario no está vacío, maneja la apuesta según el proveedor.
             */
            case "BETCASINOCRM":
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);

                if (false) {
                    if (empty($UsuarioId)) {
                        $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);

                        $rules = [];

                        array_push(
                            $rules,
                            [
                                'field' => 'producto_mandante.prodmandante_id',
                                'data' => $TransaccionApi->productoId,
                                'op' => 'eq'
                            ]
                        );
                        array_push(
                            $rules,
                            [
                                'field' => 'producto_mandante.mandante',
                                'data' => $UsuarioMandante->mandante,
                                'op' => 'eq'
                            ]
                        );
                        array_push(
                            $rules,
                            ['field' => 'producto_mandante.pais_id', 'data' => $UsuarioMandante->paisId, 'op' => 'eq']
                        );

                        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                        $Producto = new Producto();
                        $queryProvider = $Producto->getProductosCustomMandante(
                            'subproveedor.tipo',
                            'producto_mandante.prodmandante_id',
                            'ASC',
                            0,
                            1,
                            $filters,
                            true,
                            $Usuario->mandante
                        );
                        $queryProvider = json_decode($queryProvider, true)['data'][0];

                        if (oldCount($queryProvider) === 0) {
                            throw new Exception('Error al encontrar los parametros', 300001);
                        }

                        $actions = [
                            'first' => '',
                            'previous' => ''
                        ];

                        switch ($queryProvider['subproveedor.tipo']) {
                            case 'CASINO':
                                $actions['first'] = 'onFirstBetCasino';
                                $actions['previous'] = 'onNextBetCasino';
                                break;
                            case 'LIVECASINO':
                                $actions['first'] = 'onFirstBetLiveCasino';
                                $actions['previous'] = 'onNextBetLiveCasino';
                                break;
                            case 'VIRTUAL':
                                $actions['first'] = 'onFirstBetVirtual';
                                $actions['previous'] = 'onNextBetVirtual';
                                break;
                        }

                        $rules = [];

                        array_push(
                            $rules,
                            [
                                'field' => 'transaccion_api.usuario_id',
                                'data' => $UsuarioMandante->usumandanteId,
                                'op' => 'eq'
                            ]
                        );
                        array_push($rules, ['field' => 'transaccion_api.tipo', 'data' => 'DEBIT', 'op' => 'eq']);

                        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                        $queryTransactionGame = (string)$TransaccionApi->getTransaccionesCustom(
                            'MIN(transaccion_api.transapi_id) transapi_id',
                            'transaccion_api.transapi_id',
                            'ASC',
                            0,
                            1,
                            $filters,
                            true,
                            false
                        );
                        $queryTransactionGame = json_decode($queryTransactionGame, true);

                        if ($queryTransactionGame['count'][0]['.count'] === 0 || $queryTransactionGame['data'][0]['.transapi_id'] === $IdMovimiento) {
                            $emitData['action'] = $actions['first'];
                        } else {
                            $emitData['action'] = $actions['previous'];
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $TransJuegoLog = new TransjuegoLog($IdMovimiento);
                                $Valor = $TransJuegoLog->valor;
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventApuestasCasino($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }

                break;
            /**
             * Maneja el caso de ganancias de casino en el CRM.
             * Si el ID de usuario está vacío, verifica la primera ganancia o la siguiente.
             * Si el ID de usuario no está vacío, maneja la ganancia según el proveedor.
             */
            case "WINCASINOCRM":
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);

                if (false) {
                    if (empty($UsuarioId)) {
                        $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);

                        $rules = [];

                        array_push(
                            $rules,
                            [
                                'field' => 'producto_mandante.prodmandante_id',
                                'data' => $TransaccionApi->productoId,
                                'op' => 'eq'
                            ]
                        );
                        array_push(
                            $rules,
                            [
                                'field' => 'producto_mandante.mandante',
                                'data' => $UsuarioMandante->mandante,
                                'op' => 'eq'
                            ]
                        );
                        array_push(
                            $rules,
                            ['field' => 'producto_mandante.pais_id', 'data' => $UsuarioMandante->paisId, 'op' => 'eq']
                        );

                        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                        $Producto = new Producto();
                        $queryProvider = $Producto->getProductosCustomMandante(
                            'subproveedor.tipo',
                            'producto_mandante.prodmandante_id',
                            'ASC',
                            0,
                            1,
                            $filters,
                            true,
                            $Usuario->mandante
                        );
                        $queryProvider = json_decode($queryProvider, true)['data'][0];

                        if (oldCount($queryProvider) === 0) {
                            throw new Exception('Error al encontrar los parametros', 300001);
                        }

                        $actions = [
                            'first' => '',
                            'previous' => ''
                        ];

                        switch ($queryProvider['subproveedor.tipo']) {
                            case 'CASINO':
                                $actions['first'] = 'onFirstWinCasino';
                                $actions['previous'] = 'onNextWinCasino';
                                break;
                            case 'LIVECASINO':
                                $actions['first'] = 'onFirstWinLiveCasino';
                                $actions['previous'] = 'onNextWinLiveCasino';
                                break;
                            case 'VIRTUAL':
                                $actions['first'] = 'onFirstWinVirtual';
                                $actions['previous'] = 'onNextWinVirtual';
                                break;
                        }

                        $rules = [];

                        array_push(
                            $rules,
                            [
                                'field' => 'transaccion_api.usuario_id',
                                'data' => $UsuarioMandante->usumandanteId,
                                'op' => 'eq'
                            ]
                        );
                        array_push($rules, ['field' => 'transaccion_api.tipo', 'data' => 'CREDIT', 'op' => 'eq']);
                        array_push($rules, ['field' => 'transaccion_api.valor', 'data' => 0, 'op' => 'gt']);

                        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                        $queryTransactionGame = (string)$TransaccionApi->getTransaccionesCustom(
                            'MIN(transaccion_api.transapi_id) transapi_id',
                            'transaccion_api.transapi_id',
                            'ASC',
                            0,
                            1,
                            $filters,
                            true,
                            false
                        );
                        $queryTransactionGame = json_decode($queryTransactionGame, true);

                        if ($queryTransactionGame['count'][0]['.count'] === 0 || $queryTransactionGame['data'][0]['.transapi_id'] === $IdMovimiento) {
                            $emitData['action'] = $actions['first'];
                        } else {
                            $emitData['action'] = $actions['previous'];
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $TransJuegoLog = new TransjuegoLog($IdMovimiento);
                                $Valor = $TransJuegoLog->valor;
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventGananciasCasino($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de retiro pagado en el CRM.
             * Si el ID de usuario está vacío, crea una instancia de Usuario y UsuarioMandante y emite la acción 'onPaidWithdrawal'.
             * Si el ID de usuario no está vacío, maneja el retiro pagado según el proveedor.
             */
            case "RETIROPAGADOCRM":
                $emitData['action'] = 'onPaidWithdrawal';
                $Usuario = new Usuario($CuentaCobro->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                if (false) {
                    if (empty($UsuarioId)) {
                        $Usuario = new Usuario($CuentaCobro->usuarioId);
                        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                        $emitData['action'] = 'onPaidWithdrawal';
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $CuentaCobro = new CuentaCobro($IdMovimiento);
                                $Valor = $CuentaCobro->valor;
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventRetiroPagado($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de creación de retiro en el CRM.
             * Si el ID de usuario está vacío, crea una instancia de Usuario y UsuarioMandante y emite la acción 'onFirstWithdraw' o 'onNextWithdraw'.
             * Si el ID de usuario no está vacío, maneja el retiro creado según el proveedor.
             */
            case "RETIROCREADOCRM":
                $emitData['action'] = 'onNextWithdraw';
                $Usuario = new Usuario($CuentaCobro->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                if (false) {
                    if (empty($UsuarioId)) {
                        $Usuario = new Usuario($CuentaCobro->usuarioId);
                        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                        $rules = [];

                        array_push(
                            $rules,
                            ['field' => 'cuenta_cobro.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']
                        );
                        array_push(
                            $rules,
                            ['field' => 'cuenta_cobro.mandante', 'data' => $Usuario->mandante, 'op' => 'eq']
                        );

                        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                        $queryWithdraw = (string)$CuentaCobro->getCuentasCobroCustom(
                            'MIN(cuenta_cobro.cuenta_id) cuenta_id',
                            'cuenta_cobro.cuenta_id',
                            'ASC',
                            0,
                            1,
                            $filters,
                            true
                        );
                        $queryWithdraw = json_decode($queryWithdraw, true);

                        if ($queryWithdraw['count'][0]['.count'] === 0 || $queryWithdraw['data'][0]['.cuenta_id'] === $IdMovimiento) {
                            $emitData['action'] = 'onFirstWithdraw';
                        } else {
                            $emitData['action'] = 'onNextWithdraw';
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $CuentaCobro = new CuentaCobro($IdMovimiento);
                                $Valor = $CuentaCobro->valor;
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventRetiroCreado($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de eliminación de retiro en el CRM.
             * Crea una instancia de Clasificador y MandanteDetalle, y maneja la eliminación del retiro según el proveedor.
             */
            case "RETIROELIMINADOCRM":
                $Clasificador = new Clasificador("", "PROVCRM");
                $MandanteDetalle = new MandanteDetalle(
                    '',
                    $UsuarioMandante->mandante,
                    $Clasificador->clasificadorId,
                    $UsuarioMandante->paisId,
                    'A'
                );
                $Proveedor = new Proveedor($MandanteDetalle->valor);
                switch ($Proveedor->abreviado) {
                    case "OPTIMOVE":
                        $CuentaCobro = new CuentaCobro($IdMovimiento);
                        $Valor = $CuentaCobro->valor;
                        $EventsOptimove = new EventsOptimove();
                        $EventsOptimove->EventRetiroEliminado($Usuario, $Server, $IsMobile, $Valor);
                        break;
                    case "FastTrack":
                        break;
                    case "CRMPROPIO":
                        break;
                }
                break;
            /**
             * Maneja el caso de solicitud de depósito en el CRM.
             * Si el ID de usuario está vacío, verifica el estado de la transacción y emite la acción 'onFirstDeposit' o 'onNextDeposit'.
             * Si el ID de usuario no está vacío, maneja la solicitud de depósito según el proveedor.
             */
            case "SOLICITUDDEPOSITOCRM":
                $emitData['action'] = 'onNextDeposit';
                $TransaccionProducto = new TransaccionProducto($IdMovimiento);

                $Usuario = new Usuario($TransaccionProducto->usuarioId);
                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

                if (false) {
                    if (empty($UsuarioId)) {
                        $TransaccionProducto = new TransaccionProducto($IdMovimiento);
                        if ($TransaccionProducto->estado === 'A') {
                            $Usuario = new Usuario($TransaccionProducto->usuarioId);
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

                            if ($Usuario->fechaPrimerdeposito == "") {
                                $emitData['action'] = 'onFirstDeposit';
                            } else {
                                $emitData['action'] = 'onNextDeposit';
                            }
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        $TransaccionProducto = new TransaccionProducto($IdMovimiento);
                        $Valor = $TransaccionProducto->valor;
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventSolicitudDeposito($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de depósito en el CRM.
             * Si el ID de usuario está vacío, verifica el estado de la recarga y emite la acción 'onDepositedBalence'.
             * Si el ID de usuario no está vacío, maneja el depósito según el proveedor.
             */
            case "DEPOSITOCRM":
                $emitData['action'] = 'onDepositedBalence';
                $UsuarioRecarga = new UsuarioRecarga($IdMovimiento);
                $Usuario = new Usuario($UsuarioRecarga->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                if (false) {
                    if (empty($UsuarioId)) {
                        $UsuarioRecarga = new UsuarioRecarga($IdMovimiento);
                        if ($UsuarioRecarga->estado === 'A') {
                            $Usuario = new Usuario($UsuarioRecarga->usuarioId);
                            $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                            $emitData['action'] = 'onDepositedBalence';
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);

                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $UsuarioRecarga = new UsuarioRecarga($IdMovimiento);
                                $Valor = $UsuarioRecarga->valor;
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventDeposito($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                $TransaccionProducto = new TransaccionProducto($IdMovimiento);
                                if ($TransaccionProducto->valor >= 1500 && $Usuario->paisId == '173' && false) {
                                    $UsuarioAlerta = new UsuarioAlerta();
                                    $msg = "*Usuario:* " . $Usuario->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $TransaccionProducto->valor . " - *Producto:* " . $TransaccionProducto->productoId;
                                    $UsuarioAlerta->CheckAlert($TransaccionProducto, 45, $Usuario->usuarioId, $msg);
                                }

                                if ($TransaccionProducto->valor >= 600 && $Usuario->paisId == '66' && false) {
                                    $UsuarioAlerta = new UsuarioAlerta();
                                    $msg = "*Usuario:* " . $Usuario->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $TransaccionProducto->valor . " - *Producto:* " . $TransaccionProducto->productoId;
                                    $UsuarioAlerta->CheckAlert($TransaccionProducto, 45, $Usuario->usuarioId, $msg);
                                }

                                if ($TransaccionProducto->valor >= 10000 && $Usuario->paisId == '146' && false) {
                                    $UsuarioAlerta = new UsuarioAlerta();
                                    $msg = "*Usuario:* " . $Usuario->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $TransaccionProducto->valor . " - *Producto:* " . $TransaccionProducto->productoId;
                                    $UsuarioAlerta->CheckAlert($TransaccionProducto, 45, $Usuario->usuarioId, $msg);
                                }

                                if ($TransaccionProducto->valor >= 3500 && $Usuario->paisId == '173') {
                                    try {
                                        $message = "Deposito *Usuario:* " . $Usuario->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $TransaccionProducto->valor . " - *Producto:* " . $TransaccionProducto->productoId;
                                        exec(
                                            "php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-peru' > /dev/null & "
                                        );
                                    } catch (Exception $e) {
                                    }
                                }

                                if ($TransaccionProducto->valor >= 700 && $Usuario->paisId == '66') {
                                    try {
                                        $message = "Deposito *Usuario:* " . $Usuario->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $TransaccionProducto->valor . " - *Producto:* " . $TransaccionProducto->productoId;
                                        exec(
                                            "php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-ecuabet' > /dev/null & "
                                        );
                                    } catch (Exception $e) {
                                    }
                                }

                                if ($TransaccionProducto->valor >= 625 && $Usuario->paisId == '60') {
                                    try {
                                        $message = "Deposito *Usuario:* " . $Usuario->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $TransaccionProducto->valor . " - *Producto:* " . $TransaccionProducto->productoId;
                                        exec(
                                            "php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-doradobet-costarica' > /dev/null & "
                                        );
                                    } catch (Exception $e) {
                                    }
                                }
                                break;
                        }
                    }
                }
                break;
            /**
             * Maneja el caso de bono en el CRM.
             * Si el ID de usuario está vacío, verifica el estado del bono y emite la acción correspondiente.
             * Si el ID de usuario no está vacío, maneja el bono según el proveedor.
             */
            case "BONOCRM":
                $typeBonus = [
                    'A' => 'onActiveBonus',
                    'E' => 'onExpireBonus',
                    'I' => 'onCancelBonus',
                    'P' => 'onPendingBonus'
                ];
                $UsuarioBono = new UsuarioBono($IdMovimiento);
                if (in_array($UsuarioBono->estado, array_keys($typeBonus))) {
                    $Usuario = new Usuario($UsuarioBono->usuarioId);
                    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                    $emitData['action'] = $typeBonus[$UsuarioBono->estado];
                }
                if (false) {
                    if (empty($UsuarioId)) {
                        $typeBonus = [
                            'A' => 'onActiveBonus',
                            'E' => 'onExpireBonus',
                            'I' => 'onCancelBonus',
                            'P' => 'onPendingBonus'
                        ];
                        $UsuarioBono = new UsuarioBono($IdMovimiento);
                        if (in_array($UsuarioBono->estado, array_keys($typeBonus))) {
                            $Usuario = new Usuario($UsuarioBono->usuarioId);
                            $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                            $emitData['action'] = $typeBonus[$UsuarioBono->estado];
                        }
                    } else {
                        $Clasificador = new Clasificador("", "PROVCRM");
                        $MandanteDetalle = new MandanteDetalle(
                            '',
                            $UsuarioMandante->mandante,
                            $Clasificador->clasificadorId,
                            $UsuarioMandante->paisId,
                            'A'
                        );
                        $Proveedor = new Proveedor($MandanteDetalle->valor);
                        switch ($Proveedor->abreviado) {
                            case "OPTIMOVE":
                                $BonoLog = new BonoLog($IdMovimiento);
                                $Valor = $BonoLog->valor;
                                $EventsOptimove = new EventsOptimove();
                                $EventsOptimove->EventBono($Usuario, $Server, $IsMobile, $Valor);
                                break;
                            case "FastTrack":
                                break;
                            case "CRMPROPIO":
                                break;
                        }
                    }
                }
                break;
            case "LEALTADCRM":
                $Clasificador = new Clasificador("", "PROVCRM");
                $MandanteDetalle = new MandanteDetalle(
                    '',
                    $UsuarioMandante->mandante,
                    $Clasificador->clasificadorId,
                    $UsuarioMandante->paisId,
                    'A'
                );
                $Proveedor = new Proveedor($MandanteDetalle->valor);
                switch ($Proveedor->abreviado) {
                    case "OPTIMOVE":
                        $BonoLog = new BonoLog($IdMovimiento);
                        $Valor = $BonoLog->valor;
                        $EventsOptimove = new EventsOptimove();
                        $EventsOptimove->EventLealtad($Usuario, $Server, $IsMobile, $Valor, $IdMovimiento);
                        break;
                    case "FastTrack":
                        break;
                    case "CRMPROPIO":
                        break;
                }

                break;
            /**
             * Maneja el caso de ajuste de saldo en el CRM.
             * Si el ID de usuario está vacío, crea una instancia de Usuario y UsuarioMandante y emite la acción 'onAdjustedBalance'.
             * Si el ID de usuario no está vacío, maneja el ajuste de saldo según el proveedor.
             */
            case "SALDOAJUSTECRM":
                $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste($IdMovimiento);
                $Usuario = new Usuario($SaldoUsuonlineAjuste->usuarioId);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                if (empty($UsuarioId)) {
                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste($IdMovimiento);
                    $Usuario = new Usuario($SaldoUsuonlineAjuste->usuarioId);
                    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                    $emitData['action'] = 'onAdjustedBalance';
                } else {
                    $Clasificador = new Clasificador("", "PROVCRM");
                    $MandanteDetalle = new MandanteDetalle(
                        '',
                        $UsuarioMandante->mandante,
                        $Clasificador->clasificadorId,
                        $UsuarioMandante->paisId,
                        'A'
                    );
                    $Proveedor = new Proveedor($MandanteDetalle->valor);
                    switch ($Proveedor->abreviado) {
                        case "OPTIMOVE":
                            $EventsOptimove = new EventsOptimove();
                            $EventsOptimove->EventSaldoAjuste($Usuario, $Server, $IsMobile);
                            break;
                        case "FastTrack":
                            break;
                        case "CRMPROPIO":
                            break;
                    }


                    $emitData['action'] = 'onAdjustment';
                }
                break;
            /**
             * Maneja el caso de cierre de sesión en el CRM.
             * Si el estado del token de usuario es 'I', emite la acción 'onLogout'.
             */
            case 'LOGOUTCRM':
                $UsuarioToken = new UsuarioToken($IdMovimiento);
                if ($UsuarioToken->estado === 'I') {
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    $emitData['action'] = 'onLogout';
                }
                break;
            case 'REDEEMEDBONUSCRM':
                $typeBonus = [
                    'AF' => 'onOtherBonus',
                    'CC' => 'onCashoutCasinoBonus',
                    'D' => 'onDepositBonus',
                    'DC' => 'onDepositCasinoBonus',
                    'F' => 'onFreebetBonus',
                    'F2' => 'onOtherBonus',
                    'FC' => 'onFreecasinoBonus',
                    'FS' => 'onFreespin',
                    'JC' => 'onOtherBonus',
                    'JD' => 'onJackpotSportbookBonus',
                    'JL' => 'onJackpotLiveCasinoBonus',
                    'JS' => 'onJackpotCasinoBonus',
                    'NC' => 'onNoDepositCasinoBonus',
                    'ND' => 'onNoDepositSportbookBonus',
                    'NL' => 'onNoDepositLiveCasinoBonus',
                    'PD' => 'onFirstDepositBonus',
                    'RC' => 'onRouletteCasinoBonus',
                    'S' => 'onRaffleSportbookBonus',
                    'SC' => 'onRaffleCasinoBonus',
                    'SL' => 'onRaffleLiveCasinoBonus',
                    'SV' => 'onRaffleVirtualesBonus',
                    'TC' => 'onTournamentCasinoBonus',
                    'TD' => 'onTournamentSportbookBonus',
                    'TL' => 'onTournamentLiveCasinoBonus',
                    'TV' => 'onTouranmentVistualesBonus'
                ];

                $BonoLog = new BonoLog($IdMovimiento);
                if (in_array($BonoLog->tipo, array_keys($typeBonus))) {
                    $Usuario = new Usuario($BonoLog->usuarioId);
                    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                    $emitData['action'] = $typeBonus[$BonoLog->tipo];
                }
                break;
            /**
             * Maneja el caso de redención de bono en el CRM.
             * Verifica el tipo de bono y emite la acción correspondiente.
             */
            case 'SUBTORURNAMENTCRM':
                $UsuarioTorneo = new UsuarioTorneo($IdMovimiento);
                $UsuarioMandante = new UsuarioMandante($UsuarioTorneo->usuarioId);

                $rules = [];

                array_push(
                    $rules,
                    ['field' => 'usuario_torneo.usuario_id', 'data' => $UsuarioTorneo->usuarioId, 'op' => 'eq']
                );
                array_push(
                    $rules,
                    ['field' => 'usuario_torneo.torneo_id', 'data' => $UsuarioTorneo->torneoId, 'op' => 'eq']
                );

                $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                $queryUserTornament = (string)$UsuarioTorneo->getUsuarioTorneosCustom(
                    'usuario_torneo.usuario_id',
                    'usuario_torneo.usuario_id',
                    'ASC',
                    0,
                    1000000,
                    $filters,
                    true
                );
                $queryUserTornament = json_decode($queryUserTornament, true);

                if ($queryUserTornament['count'][0]['.count'] > 1) {
                    $emitData['action'] = 'onSumTournament';
                } else {
                    if ($queryUserTornament['count'][0]['.count'] === 1) {
                        $emitData['action'] = 'onTournamentSubscription';
                    }
                }
                break;
            /**
             * Maneja el caso de suscripción a sorteo en el CRM.
             * Crea una instancia de UsuarioSorteo y UsuarioMandante.
             * Verifica si el usuario ya está suscrito al sorteo y emite la acción correspondiente.
             */
            case 'SUBRAFFLECRM':
                $UsuarioSorteo = new UsuarioSorteo($IdMovimiento);
                $UsuarioMandante = new UsuarioMandante($UsuarioSorteo->usuarioId);
                $rules = [];

                array_push(
                    $rules,
                    ['field' => 'usuario_sorteo.usuario_id', 'data' => $UsuarioSorteo->usuarioId, 'op' => 'eq']
                );
                array_push(
                    $rules,
                    ['field' => 'usuario_sorteo.sorteo_id', 'data' => $UsuarioSorteo->sorteoId, 'op' => 'eq']
                );

                $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                $queryUserRaffles = (string)$UsuarioSorteo->getUsuarioSorteosCustom(
                    'usuario_sorteo.ususorteo_id',
                    'usuario_sorteo.ususorteo_id',
                    'ASC',
                    0,
                    1000000,
                    $filters,
                    true
                );
                $queryUserRaffles = json_decode($queryUserRaffles, true);

                if ($queryUserRaffles['count'][0]['.count'] > 1) {
                    $emitData['action'] = 'onSumRaffleSticker';
                } else {
                    if ($queryUserRaffles['count'][0]['.count'] === 1) {
                        $emitData['action'] = 'onRaffleSubscription';
                    }
                }
                break;
            /**
             * Maneja el caso de suma de stickers de sorteo en el CRM.
             * Crea una instancia de PreUsuarioSorteo y UsuarioMandante.
             * Emite la acción 'onSumRaffleSticker'.
             */
            case 'SUMSTICKERCRM':
                $PreUsuarioSorteo = new PreUsuarioSorteo($IdMovimiento);
                $UsuarioMandante = new UsuarioMandante($PreUsuarioSorteo->usuarioId);
                $emitData['action'] = 'onSumRaffleSticker';
                break;
            /**
             * Maneja el caso de suma de jackpot en el CRM.
             * Crea una instancia de UsuarioJackpot y UsuarioMandante.
             * Emite la acción 'onSumJackpot'.
             */
            case 'SUMJACKPOTCRM':
                $UsuarioJackpot = new UsuarioJackpot($IdMovimiento);
                $UsuarioMandante = new UsuarioMandante($UsuarioJackpot->usuarioId);
                $emitData['action'] = 'onSumJackpot';
                break;
            /**
             * Maneja el caso de redención de regalo en el CRM.
             * Si el estado del usuario lealtad es 'R', crea una instancia de Usuario y UsuarioMandante,
             * y emite la acción 'onRedemGiftLoyalty'.
             */
            case 'REDEEMGIFTCRM':
                $UsuarioLealtad = new UsuarioLealtad($IdMovimiento);
                if ($UsuarioLealtad->estado === 'R') {
                    $Usuario = new Usuario($UsuarioLealtad->usuarioId);
                    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                    $emitData['action'] = 'onRedemGiftLoyalty';
                }
                break;
            /**
             * Maneja el caso de apertura de juego en el CRM.
             * Crea una instancia de UsuarioToken y verifica si el estado es 'A'.
             * Si el estado es 'A', crea una instancia de UsuarioMandante y emite la acción 'onOpenGame'.
             */
            case 'OPENINGGAMECRM':
                $UsuarioToken = new UsuarioToken('', '', '', '', '', '', $IdMovimiento);
                if ($UsuarioToken->estado === 'A') {
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    $emitData['action'] = 'onOpenGame';
                }
                break;
            /**
             * Maneja el caso de cambio de contraseña en el CRM.
             * Crea una instancia de UsuarioLog y verifica si el tipo es 'CAMBIOCLAVE' y el estado es 'A'.
             * Si se cumplen las condiciones, crea una instancia de Usuario y UsuarioMandante,
             * y emite la acción 'onChangePassword'.
             */
            case 'CHANGEPASSWORDCRM':
                $UsuarioLog = new UsuarioLog($IdMovimiento);
                if ($UsuarioLog->tipo === 'CAMBIOCLAVE' && $UsuarioLog->estado === 'A') {
                    $Usuario = new Usuario($UsuarioLog->usuarioId);
                    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                    $emitData['action'] = 'onChangePassword';
                }
                break;
            /**
             * Maneja el caso de actualización de información en el CRM.
             * Crea una instancia de UsuarioLog2 y verifica si el estado es 'A'.
             * Si el estado es 'A', verifica el tipo de cambio y emite la acción correspondiente.
             */
            case 'UPDATEINFOCRM':
                $logType = [
                    'USUNOMBRE' => 'onChangeUserName',
                    'USUEMAIL' => 'onChangeUserEmail',
                    'USUCELULAR' => 'onChangeUserPhone',
                    'USUCIUDAD' => 'onChangeUserCity',
                    'USUCIUDADID' => 'onChangeUserCity',
                    'USUCEDULA' => 'onChangeUserDocument',
                    'USUFECHANACIM' => 'onChangeUserBirthDate',
                    'USUAPELLIDO1' => 'onChangeLastName'
                ];
                $UsuarioLog2 = new UsuarioLog2($IdMovimiento);
                if ($UsuarioLog2->estado === 'A' && in_array($UsuarioLog2->tipo, array_keys($logType))) {
                    $emitData['action'] = $logType[$UsuarioLog2->tipo];
                }
                break;

            /**
             * Maneja el caso de actualización de información en el CRM.
             * Activa la actualización de la información para el evento Jackpot caido
             * con la información del Jackpot caido y el Usuario Ganador
             */
            case "FALLJACKPOTCRM":

                $Clasificador = new Clasificador("", "PROVCRM");
                $MandanteDetalle = new MandanteDetalle(
                    '',
                    $UsuarioMandante->mandante,
                    $Clasificador->clasificadorId,
                    $UsuarioMandante->paisId,
                    'A'
                );
                $Proveedor = new Proveedor($MandanteDetalle->valor);
                switch ($Proveedor->abreviado) {
                    case "OPTIMOVE":
                        $UsuarioJackpotGanador = new UsuariojackpotGanador($IdMovimiento);
                        $Jackpot = new JackpotInterno($UsuarioJackpotGanador->jackpotId);
                        $EventsOptimove = new EventsOptimove();
                        $EventsOptimove->EventJackpotCaido($UsuarioJackpotGanador, $Server, $IsMobile, $Jackpot);
                        break;
                    case "FastTrack":
                        break;
                    case "CRMPROPIO":
                        break;
                }

                break;
        }

        /**
         * Emite datos a través de WebSocket si hay una acción definida.
         *
         * @param array            $emitData         Datos a emitir.
         * @param string           $emitData         ['action'] Acción a realizar.
         * @param string           $emitData         ['userID'] ID del usuario.
         * @param string           $emitData         ['sevice'] Servicio (mobile o desktop).
         * @param string           $emitData         ['type'] Tipo de emisión (modal).
         * @param UsuarioMandante  $UsuarioMandante  Instancia de UsuarioMandante.
         * @param WebsocketUsuario $WebSocketUsuario Instancia de WebsocketUsuario.
         *
         * @return void
         */
        if ( ! empty($emitData['action'])) {
            if ($UsuarioMandante != null) {
                $emitData['userID'] = $UsuarioMandante->usuarioMandante;
                $WebSocketUsuario = new WebsocketUsuario('', '');
                $WebSocketUsuario->sendWSPieSocket($UsuarioMandante, $emitData, true);
            }
        }
    }

}
