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
use Backend\mysql\TransjuegoLogMySqlDAO;
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
 * Report/CancelCasinoDetail
 *
 * Procesar reversión de transacciones de juego
 *
 * Este recurso permite revertir transacciones de juego según su tipo (DEBIT, DEBITCALL, CREDIT, ROLLBACK),
 * generando un log de auditoría y ajustando los valores en las cuentas de los usuarios.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *Id* (int): Identificador de la transacción a revertir.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada ("success" si la operación es exitosa).
 *  - *AlertMessage* (string): Mensaje de alerta generado por el proceso.
 *  - *ModelErrors* (array): Retorna array vacío si no hay errores en el modelo.
 *
 * Ejemplo de respuesta en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "danger",
 *  - *AlertMessage* => "[Mensaje de error]",
 *  - *ModelErrors* => array(),
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* crea un nuevo objeto Usuario y obtiene un ID de la solicitud. */
$Usuario = new Usuario();

$Id = ($_REQUEST["id"]);
$Id = $params->Id;


if (is_numeric($Id)) {


    /* Se crea una nueva instancia de 'TransjuegoLog' utilizando el identificador '$Id'. */
    $TransjuegoLog = new TransjuegoLog($Id);

    switch ($TransjuegoLog->getTipo()) {

        case "DEBIT":


            /* Se crea un log de transacción para auditoría en MySQL. */
            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


            //  Creamos el log de la transaccion juego para auditoria
            $TransjuegoLog2 = new TransjuegoLog();
            $TransjuegoLog2->setTransjuegoId($TransjuegoLog->getTransjuegoId());

            /* Registra una transacción de rollback manual en el sistema. */
            $TransjuegoLog2->setTransaccionId("ROLLBACK" . $TransjuegoLog->getTransaccionId());
            $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
            $TransjuegoLog2->setTValue(json_encode(array()));
            $TransjuegoLog2->setUsucreaId($_SESSION['usuario']);
            $TransjuegoLog2->setUsumodifId(0);
            $TransjuegoLog2->setValor($TransjuegoLog->getValor());


            /* Insertar registro, crear objetos y acreditar ganancias al usuario correspondiente. */
            $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);

            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Usuario->creditWin($TransjuegoLog->getValor(), $TransjuegoLogMySqlDAO->getTransaction());


            /* Crea un historial de usuario con datos iniciales y movimientos específicos. */
            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('C');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);

            /* inserta un registro de historial de usuario en la base de datos. */
            $UsuarioHistorial->setTipo(30);
            $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


            /* Actualiza el estado y valor del premio en una transacción de juego. */
            $TransaccionJuego->setValorPremio($TransjuegoLog->getValor());
            $TransaccionJuego->setEstado('I');

            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
            $TransaccionJuegoMySqlDAO->update($TransaccionJuego);

            $TransjuegoLogMySqlDAO->getTransaction()->commit();


            break;

        case "DEBITCALL":


            /* Se crea un log de transacciones de juego para auditoría en MySQL. */
            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


            //  Creamos el log de la transaccion juego para auditoria
            $TransjuegoLog2 = new TransjuegoLog();
            $TransjuegoLog2->setTransjuegoId($TransjuegoLog->getTransjuegoId());

            /* Registra un rollback manual de transacción en el log. */
            $TransjuegoLog2->setTransaccionId("ROLLBACK" . $TransjuegoLog->getTransaccionId());
            $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
            $TransjuegoLog2->setTValue(json_encode(array()));
            $TransjuegoLog2->setUsucreaId($_SESSION['usuario']);
            $TransjuegoLog2->setUsumodifId(0);
            $TransjuegoLog2->setValor($TransjuegoLog->getValor());


            /* Se inserta un registro de juego y se actualiza el crédito del usuario. */
            $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);

            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Usuario->creditWin($TransjuegoLog->getValor(), $TransjuegoLogMySqlDAO->getTransaction());


            /* Se crea un registro de historial para un usuario con movimientos específicos. */
            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('C');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);

            /* Se establece y guarda un historial de usuario en la base de datos. */
            $UsuarioHistorial->setTipo(30);
            $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


            /* Actualiza el estado y valor de una transacción de juego en la base de datos. */
            $TransaccionJuego->setValorPremio($TransjuegoLog->getValor());
            $TransaccionJuego->setEstado('I');

            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
            $TransaccionJuegoMySqlDAO->update($TransaccionJuego);

            $TransjuegoLogMySqlDAO->getTransaction()->commit();


            break;
        case "CREDIT":

            /* Se crea un registro de auditoría para la transacción de juego. */
            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

            //  Creamos el log de la transaccion juego para auditoria
            $TransjuegoLog2 = new TransjuegoLog();
            $TransjuegoLog2->setTransjuegoId($TransjuegoLog->getTransjuegoId());
            $TransjuegoLog2->setTransaccionId("RMCREDIT" . $TransjuegoLog->getTransaccionId());

            /* Código para registrar un rollback manual en una transacción de juego. */
            $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
            $TransjuegoLog2->setTValue(json_encode(array()));
            $TransjuegoLog2->setUsucreaId($_SESSION['usuario']);
            $TransjuegoLog2->setUsumodifId(0);
            $TransjuegoLog2->setValor($TransjuegoLog->getValor());

            $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


            /* Código que gestiona una transacción y actualiza el saldo de un usuario. */
            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Usuario->debit($TransjuegoLog->getValor(), $TransjuegoLogMySqlDAO->getTransaction());


            $UsuarioHistorial = new UsuarioHistorial();

            /* Se configura un historial de usuario con propiedades específicas y valores iniciales. */
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('C');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(30);

            /* Código que inserta un historial de usuario en la base de datos. */
            $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


            $TransjuegoLogMySqlDAO->getTransaction()->commit();


            break;


        case "ROLLBACK":

            /* Se crea un registro de auditoría para transacciones de juego en MySQL. */
            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

            //  Creamos el log de la transaccion juego para auditoria
            $TransjuegoLog2 = new TransjuegoLog();
            $TransjuegoLog2->setTransjuegoId($TransjuegoLog->getTransjuegoId());
            $TransjuegoLog2->setTransaccionId("RMROLLBACK" . $TransjuegoLog->getTransaccionId());

            /* Registra un rollback manual en la base de datos con datos específicos. */
            $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
            $TransjuegoLog2->setTValue(json_encode(array()));
            $TransjuegoLog2->setUsucreaId($_SESSION['usuario']);
            $TransjuegoLog2->setUsumodifId(0);
            $TransjuegoLog2->setValor($TransjuegoLog->getValor());

            $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


            /* Crea transacciones y actualiza historial de usuario tras debitar una cantidad. */
            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Usuario->debit($TransjuegoLog->getValor(), $TransjuegoLogMySqlDAO->getTransaction());


            $UsuarioHistorial = new UsuarioHistorial();

            /* Se configura el historial de un usuario con diversos atributos y valores. */
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('C');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(30);

            /* Inserta un historial de usuario utilizando datos de una transacción de juego. */
            $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

            $TransjuegoLogMySqlDAO->getTransaction()->commit();

            break;
    }


    /* Se establece una respuesta sin errores, con aviso de éxito y sin mensajes de error. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


} else {
    /* maneja una respuesta indicando éxito sin errores en el modelo. */

    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


}
