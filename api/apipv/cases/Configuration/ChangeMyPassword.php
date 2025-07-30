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
 * ChangeMyPassword
 *
 * Este script permite a un usuario cambiar su contraseña actual, verificando la contraseña anterior
 * y registrando el cambio en la base de datos.
 *
 * @param object $json JSON recibido desde la entrada que contiene los datos de la solicitud.
 * @param string $oldPassword Contraseña actual del usuario.
 * @param string $newPassword Nueva contraseña del usuario.
 * @param string $confirmPassword Confirmación de la nueva contraseña.
 * 
 * 
 * @return array $response Respuesta con el estado de la operación.
 *                         - HasError: bool Indica si ocurrió un error.
 *                         - AlertType: string Tipo de alerta (success, error, etc.).
 *                         - AlertMessage: string Mensaje de alerta.
 *                         - ModelErrors: array Lista de errores del modelo.
 * @throws Exception Si ocurre un error al verificar la contraseña, registrar el cambio o actualizar la base de datos.
 */

$FromId = $_SESSION["usuario"];

if ($FromId != "" && is_numeric($FromId)) {
    /**
     * Cambia la contraseña del usuario si la nueva coincide con la confirmación.
     */
    $Usuario = new Usuario($FromId);
    $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

    $OldPassword = $params->OldPassword;
    $NewPassword = $params->NewPassword;
    $ConfirmPassword = $params->ConfirmPassword;

    if ($NewPassword == $ConfirmPassword) {
        /*Verifica la contraseña del usuario y obtiene la transacción y la dirección IP del cliente.*/
        $Usuario->checkClave($OldPassword);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $ip = explode(",", $ip)[0];

        if (in_array($UsuarioPerfil->perfilId, ['PUNTOVENTA', 'CONCESIONARIO', 'CONCESIONARIO2', 'CONCESIONARIO3']) && $UsuarioPerfil->mandante == 8) {
            /*Consulta registros de logs de usuario con filtros específicos y los decodifica en JSON.*/
            $rules = [];

            array_push($rules, ['field' => 'usuario_log.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_log.tipo', 'data' => 'CAMBIOCLAVEEMAIL', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_log.estado', 'data' => 'P', 'op' => 'eq']);

            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $UsuarioLog = new UsuarioLog();
            $query = $UsuarioLog->getUsuarioLogsCustom('usuario_log.usuariolog_id', 'usuario_log.usuariolog_id', 'DESC', 0, 1000, $filters, true);

            $query = json_decode($query, true);

            /*Actualiza el estado de los logs de usuario a 'A' si existen registros.*/
            try {
                if (oldCount($query['data']) == 0) throw new Exception('No existen logs', 100);
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                foreach ($query['data'] as $key => $value) {
                    $UsuarioLog = new UsuarioLog($value['usuario_log.usuariolog_id']);

                    $UsuarioLog->estado = 'A';
                    $UsuarioLogMySqlDAO->update($UsuarioLog);
                }
                $UsuarioLogMySqlDAO->getTransaction()->commit();
            } catch (Exception $ex) {
                if ($ex->getCode() == 100) {
                    /*Verifica si la nueva contraseña ya ha sido utilizada por el usuario anteriormente.*/
                    $rules = [];
                    array_push($rules, ['field' => 'usuario_log.tipo', 'data' => 'CAMBIOCLAVE', 'op' => 'eq']);
                    array_push($rules, ['field' => 'usuario_log.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);

                    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                    $query = $UsuarioLog->getUsuarioLogsCustom('usuario_log.valor_despues', 'usuario_log.usuariolog_id', 'DESC', 0, 3, $filters, true);
                    $query = json_decode($query, true);

                    $consideration = array_filter($query['data'], function ($item) use ($NewPassword) {
                        if (strval($item['usuario_log.valor_despues']) === strval(base64_encode(md5($NewPassword)))) return $item;
                    });

                    if (oldCount($consideration) > 0) throw new Exception('Ingrese una clave que no alla utilizado', 100090);

                    /*Registra el cambio de contraseña del usuario en la base de datos y confirma la transacción.*/
                    $UsuarioLog = new UsuarioLog();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                    $UsuarioLog->setUsuariosolicitaIp($ip);

                    $UsuarioLog->setTipo('CAMBIOCLAVE');
                    $UsuarioLog->setEstado('A');
                    $UsuarioLog->setValorAntes(base64_encode(md5($OldPassword)));
                    $UsuarioLog->setValorDespues(base64_encode(md5($NewPassword)));
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);

                    $UsuarioLogMySqlDAO->insert($UsuarioLog);
                    $Transaction->commit();
                }
            }

        } else {
            /*Registra el cambio de contraseña del usuario en la base de datos.*/
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp($ip);

            $UsuarioLog->setTipo("CAMBIOCLAVE");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues("");
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);

            // Establecer el tipo "FORCEPASS" adicional
            $UsuarioLogForcePass = clone $UsuarioLog;
            $UsuarioLogForcePass->setTipo("CAMBIOCLAVEEMAIL");

            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes("");
            $UsuarioLog->setValorDespues("");
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);

            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->insert($UsuarioLogForcePass); // Insertar el registro con tipo "FORCEPASS"

            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

            $Google = new GoogleAuthenticator();
            $Usuario->tokenGoogle = "I";
            $Usuario->saltGoogle = $Google->createSecret();
            $Usuario->fechaClave = date('Y-m-d H:i:s');
            $UsuarioMySqlDAO->update($Usuario);
            $Transaction->commit();


        }

        $Usuario->changeClave($NewPassword);

        /*Envía un mensaje a Slack y actualiza la respuesta con el estado del cambio de contraseña.*/
        try {

            $message = "*CRON: (CAMBIO CLAVE UN USUARIO EN BO) * " . $Usuario->usuarioId . " " . $_SESSION['usuario'] . " - Fecha: " . date("Y-m-d H:i:s");

            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
        } catch (Exception $e) {

        }

        $response["HasError"] = false;
        $response["AlertType"] = "Success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


    } else {
        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    }

} else {
    $response["HasError"] = true;
    $response["AlertType"] = "Error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}
