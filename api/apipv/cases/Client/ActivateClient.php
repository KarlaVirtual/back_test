<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\CuentaAsociada;
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
use Backend\mysql\CuentaAsociadaMySqlDAO;
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
 * Client/ActivateClient
 *
 * Activa un cliente actualizando su estado y registros asociados.
 *
 * @param object $params Contiene los siguientes valores:
 * @param int $params ->Id El ID del cliente que se activará.
 *
 *
 *
 * @return array $response Contiene los siguientes valores:
 *  - bool $HasError: Indica si hubo un error.
 *  - string $AlertType: Tipo de alerta (por ejemplo, success).
 *  - string $AlertMessage: Mensaje que describe el resultado.
 *  - array $ModelErrors: Lista de errores del modelo, si los hay.
 *  - array $Data: Datos adicionales, si los hay.
 *
 * @throws Exception Si ocurre un problema con la transacción en la base de datos.
 */

/* Se crean instancias de Usuario y Registro usando el identificador del cliente. */
$ClientId = $params->Id;
$Usuario = new Usuario($ClientId);
$Registro = new Registro("", $ClientId);

if ($Usuario->mandante == 21) {

    try {


        /* Código crea un objeto UsuarioMySqlDAO y obtiene una transacción asociada a un usuario. */
        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Transaction = $UsuarioMySqlDAO->getTransaction();


        $CuentaAsociada = new CuentaAsociada("", $ClientId);
        $IdUsuario = $CuentaAsociada->usuarioId;

        /* Se asigna el valor de usuarioId2 de CuentaAsociada a IdUsuario2. */
        $IdUsuario2 = $CuentaAsociada->usuarioId2;


        if ($Registro->estadoValida != "A") {

            /* asigna valores a estados y valida un documento del usuario. */
            $Registro->estadoValida = "A";
            $Usuario->estado = "A";
            $Usuario->estadoEsp = "A";
            $Usuario->fechaDocvalido = date('Y-m-d H:i:s');


            if ($Usuario->usuDocvalido == "" || $Usuario->usuDocvalido == "null" || $Usuario->usuDocvalido == null) {
                $Usuario->usuDocvalido = 0;
            }


            /* verifica valores nulos y asigna valores predeterminados a variables. */
            if ($Registro->usuvalidaId == "" || $Registro->usuvalidaId == "null" || $Registro->usuvalidaId == null) {
                $Registro->usuvalidaId = 0;
            }

            if ($Registro->fechaValida == "" || $Registro->fechaValida == "null" || $Registro->fechaValida == null) {
                $Registro->fechaValida = date('Y-m-d H:i:s');
            }


            /* Actualiza un registro y un usuario en la base de datos usando transacciones. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);

            $RegistroMySqlDAO->update($Registro);
            $UsuarioMySqlDAO->update($Usuario);

        }


        /* Se crean instancias de las clases Usuario y Registro con el ID proporcionado. */
        $Usuario = $Usuario = new Usuario($IdUsuario2);
        $Registro = new Registro("", $IdUsuario2);

        if ($Registro->estadoValida != "A") {

            /* Se establece el estado de usuario y su fecha de documento, verificando valor nulo. */
            $Registro->estadoValida = "A";
            $Usuario->estado = "A";
            $Usuario->estadoEsp = "A";
            $Usuario->fechaDocvalido = date('Y-m-d H:i:s');


            if ($Usuario->usuDocvalido == "" || $Usuario->usuDocvalido == "null" || $Usuario->usuDocvalido == null) {
                $Usuario->usuDocvalido = 0;
            }


            /* Asigna valores predeterminados si campos de $Registro están vacíos o nulos. */
            if ($Registro->usuvalidaId == "" || $Registro->usuvalidaId == "null" || $Registro->usuvalidaId == null) {
                $Registro->usuvalidaId = 0;
            }

            if ($Registro->fechaValida == "" || $Registro->fechaValida == "null" || $Registro->fechaValida == null) {
                $Registro->fechaValida = date('Y-m-d H:i:s');
            }


            /* Actualiza registros de usuario y registro en una base de datos con transacción. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);

            $RegistroMySqlDAO->update($Registro);
            $UsuarioMySqlDAO->update($Usuario);


            $Transaction->commit();

        }


    } catch (Exception $e) {
        if ($e->getCode() == 110008) {

            /* Se crea un objeto de transacción y se obtiene el ID del usuario asociado. */
            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();


            $CuentaAsociada = new CuentaAsociada("", "", $ClientId);
            $IdUsuario = $CuentaAsociada->usuarioId;

            /* Asignación del identificador de usuario desde la cuenta asociada a una variable. */
            $IdUsuario2 = $CuentaAsociada->usuarioId2;

            if ($Registro->estadoValida != "A") {

                /* inicializa estados y valida un documento del usuario. */
                $Registro->estadoValida = "A";
                $Usuario->estado = "A";
                $Usuario->estadoEsp = "A";
                $Usuario->fechaDocvalido = date('Y-m-d H:i:s');


                if ($Usuario->usuDocvalido == "" || $Usuario->usuDocvalido == "null" || $Usuario->usuDocvalido == null) {
                    $Usuario->usuDocvalido = 0;
                }


                /* Asigna valores predeterminados si ciertos campos del registro están vacíos o nulos. */
                if ($Registro->usuvalidaId == "" || $Registro->usuvalidaId == "null" || $Registro->usuvalidaId == null) {
                    $Registro->usuvalidaId = 0;
                }

                if ($Registro->fechaValida == "" || $Registro->fechaValida == "null" || $Registro->fechaValida == null) {
                    $Registro->fechaValida = date('Y-m-d H:i:s');
                }


                /* Actualiza registros de usuario y registro usando objetos DAO en una transacción. */
                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);

                $RegistroMySqlDAO->update($Registro);
                $UsuarioMySqlDAO->update($Usuario);

            }


            /* Se crean instancias de las clases Usuario y Registro con un identificador específico. */
            $Usuario = $Usuario = new Usuario($IdUsuario);
            $Registro = new Registro("", $IdUsuario);


            if ($Registro->estadoValida != "A") {

                /* establece estados y valida un documento de usuario en PHP. */
                $Registro->estadoValida = "A";
                $Usuario->estado = "A";
                $Usuario->estadoEsp = "A";
                $Usuario->fechaDocvalido = date('Y-m-d H:i:s');


                if ($Usuario->usuDocvalido == "" || $Usuario->usuDocvalido == "null" || $Usuario->usuDocvalido == null) {
                    $Usuario->usuDocvalido = 0;
                }


                /* Asigna valores por defecto si `usuvalidaId` o `fechaValida` son nulos o vacíos. */
                if ($Registro->usuvalidaId == "" || $Registro->usuvalidaId == "null" || $Registro->usuvalidaId == null) {
                    $Registro->usuvalidaId = 0;
                }

                if ($Registro->fechaValida == "" || $Registro->fechaValida == "null" || $Registro->fechaValida == null) {
                    $Registro->fechaValida = date('Y-m-d H:i:s');
                }


                /* Actualiza usuario y registro en MySQL, luego confirma la transacción. */
                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);

                $RegistroMySqlDAO->update($Registro);
                $UsuarioMySqlDAO->update($Usuario);


                $Transaction->commit();

            }

        }
    }

} else {

    if ($Registro->estadoValida != "A") {

        /* Se inicializan estados y se valida el documento del usuario. */
        $Registro->estadoValida = "A";
        $Usuario->estado = "A";
        $Usuario->estadoEsp = "A";
        $Usuario->fechaDocvalido = date('Y-m-d H:i:s');


        if ($Usuario->usuDocvalido == "" || $Usuario->usuDocvalido == "null" || $Usuario->usuDocvalido == null) {
            $Usuario->usuDocvalido = 0;
        }


        /* Asigna valores predeterminados a propiedades si están vacías o nulas. */
        if ($Registro->usuvalidaId == "" || $Registro->usuvalidaId == "null" || $Registro->usuvalidaId == null) {
            $Registro->usuvalidaId = 0;
        }

        if ($Registro->fechaValida == "" || $Registro->fechaValida == "null" || $Registro->fechaValida == null) {
            $Registro->fechaValida = date('Y-m-d H:i:s');
        }


        /* actualiza registros de usuario y transacciones en una base de datos MySQL. */
        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $RegistroMySqlDAO = new RegistroMySqlDAO($UsuarioMySqlDAO->getTransaction());

        $RegistroMySqlDAO->update($Registro);
        $UsuarioMySqlDAO->update($Usuario);

        $UsuarioMySqlDAO->getTransaction()->commit();
    }
}


/* crea una respuesta JSON con éxito, mensaje y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = $msg . " - " . $ClientId;
$response["ModelErrors"] = [];

$response["Data"] = [];


/* Envía un correo de verificación de cuenta si es producción y mandante es '2'. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isProduction()) {
    if ($Usuario->mandante == '2') {
        $destinatarios = $Usuario->login;
        $msubjetc = 'Justbetja account validation';
        $mtitle = 'Justbetja account validation';
        $mensaje_txt = '';

        $mensaje_txt = 'Successful Account Verification:<br><br>Greetings, your account has been successfully verified. You may now log on and game.';

        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $mensaje_txt, "", "", "", $Usuario->mandante);

    }

}
