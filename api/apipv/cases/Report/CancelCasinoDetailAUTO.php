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
 * Report/CancelCasinoDetailAUTO
 *
 * Rollback de transacción de juego
 *
 * Este recurso permite realizar un rollback manual de una transacción de juego
 * cuando el estado de la transacción es "A" (Activo). Se genera un nuevo registro
 * en el log de transacciones y se actualiza la transacción de juego correspondiente.
 *
 * @param int $id : Identificador de la transacción de juego a revertir.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Si ocurre un error en la ejecución del rollback.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* crea una instancia de "Usuario" y obtiene el "id" de la solicitud. */
$Usuario = new Usuario();

$Id = ($_REQUEST["id"]);
$Id = $params->Id;
try {


    /* inicializa un arreglo vacío y finaliza la ejecución del script. */
    $ids = array();
    exit();
    foreach ($ids as $Id) {

        /* imprime el valor de la variable $Id en PHP. */
        print_r($Id);
        if (is_numeric($Id)) {


            /* Se crean instancias de `TransjuegoLog` y `TransaccionJuego` utilizando IDs específicos. */
            $TransjuegoLog = new TransjuegoLog($Id);
            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());

            if ($TransaccionJuego->getEstado() == "A") {


                /* Crea un registro de auditoría para transacciones de juego en MySQL. */
                $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


                //  Creamos el log de la transaccion juego para auditoria
                $TransjuegoLog2 = new TransjuegoLog();
                $TransjuegoLog2->setTransjuegoId($TransjuegoLog->getTransjuegoId());

                /* Código para registrar una transacción de rollback manual en un log. */
                $TransjuegoLog2->setTransaccionId("ROLLBACK" . $TransjuegoLog->getTransaccionId());
                $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
                $TransjuegoLog2->setTValue(json_encode(array()));
                $TransjuegoLog2->setUsucreaId(0);
                $TransjuegoLog2->setUsumodifId(0);
                $TransjuegoLog2->setValor($TransjuegoLog->getValor());


                /* Insertar registro y establecer estado y valor en transacción de juego. */
                $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


                $TransaccionJuego->setValorPremio($TransjuegoLog->getValor());
                $TransaccionJuego->setEstado('I');

                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                /* crea un usuario y registra su historial de créditos ganados. */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Usuario->creditWin($TransjuegoLog->getValor(), $TransjuegoLogMySqlDAO->getTransaction());

                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);

                /* Código que establece atributos de un historial de usuario en una aplicación. */
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('C');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(30);
                $UsuarioHistorial->setValor($TransjuegoLog2->getValor());

                /* Se insertan datos de usuario en historial y se manejan transacciones en MySQL. */
                $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());

                /* Actualiza una transacción y confirma cambios en la base de datos, mostrando un mensaje. */
                $TransaccionJuegoMySqlDAO->update($TransaccionJuego);


                $TransjuegoLogMySqlDAO->getTransaction()->commit();


                print_r("BIENNN");
            }

            /* Código inicializa una respuesta sin errores, con tipo y mensaje de éxito. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];


        } else {
            /* maneja una respuesta sin errores, configurando alertas y mensajes vacíos. */

            $response["HasError"] = true;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];


        }
    }

} catch (Exception $e) {
    /* Captura excepciones en PHP y muestra su información utilizando print_r. */

    print_r($e);
}