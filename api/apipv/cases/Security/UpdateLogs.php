<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\CuentaAsociada;
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
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioOtrainfo;
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
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Security/UpdateLogs
 * 
 * Actualiza el estado de un registro de log de usuario, marcándolo como aprobado o rechazado
 *
 * @param object $params {
 *   "Id": int,           // ID del registro de log a actualizar
 *   "State": string     // Estado a asignar (A: Aprobado, R: Rechazado)
 * }
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertType": string,     // Tipo de alerta (success, warning, error)
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "url": string,          // URL de redirección (opcional)
 *   "success": string,      // Estado de la operación
 *   "response": {           // Datos de respuesta
 *     "id": int,           // ID del registro actualizado
 *     "estado": string,    // Nuevo estado asignado
 *     "usuario": string    // Usuario que realizó la actualización
 *   }
 * }
 *
 * @throws Exception         // Error si no se puede actualizar el registro
 */

// Obtiene la IP del cliente, ya sea desde X-Forwarded-For o REMOTE_ADDR
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];

// Obtiene los parámetros de entrada y configura el estado
$Id = $params->Id;
$State = ($params->State == 0) ? 'A' : 'R'; 
$State = $params->State;

// Obtiene información del usuario y su perfil
$UsuarioLog2 = new UsuarioLog2($Id);
$UserId=$UsuarioLog2->usuarioId;
$UsuarioPerfil=new UsuarioPerfil($UserId);

// Determina el tipo de usuario basado en su perfil
if($UsuarioPerfil->perfilId=="PUNTOVENTA"){
    $TypeUser=1;
}elseif ($UsuarioPerfil->perfilId!="PUNTOVENTA"){
    $TypeUser=0;
}

// Inicializa variable de control para actualización WebSocket
$updateWs = false;

// Procesa el rechazo de documentos para usuarios que no son punto de venta
if($TypeUser==null || $TypeUser=="" || $TypeUser!=1){
    if ($State == 'R') {
        $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $ip = explode(",", $ip)[0];

        // Obtiene y actualiza el registro de log
        $UsuarioLog2 = new UsuarioLog2($Id);
        $UsuarioLog2->setEstado("NA");
        $UsuarioLog2->setUsuarioaprobarId($_SESSION['usuario2']);
        $UsuarioLog2->setUsuarioaprobarIp($ip);

        // Configura la transacción y obtiene información adicional
        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
        $Transaction = $UsuarioLog2MySqlDAO->getTransaction();

        $tipo = $UsuarioLog2->getTipo();
        if (is_numeric($tipo)) {
            $Clasificador = new Clasificador($tipo);
            $tipo = $Clasificador->getAbreviado();
        }
        $verifDNI = false;

        $Usuario = new Usuario($UsuarioLog2->getUsuarioId());

        // Procesa diferentes tipos de documentos según el caso
        switch ($tipo) {
            case "USUDNIANTERIOR":
                // Maneja el caso especial para mandante 21
                if($Usuario->mandante == 21){
                    try {
                        // Actualiza estado de jugador para cuenta asociada
                        $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                        $Usuario->estadoJugador = 'N'.substr($Usuario->estadoJugador, 1, 1);

                        $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                        $Usuario2->estadoJugador = 'N'.substr($Usuario->estadoJugador, 1, 1);

                        // Realiza la actualización en base de datos
                        $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
                        $transaction = $UsuarioMySqlDAO2->getTransaction();
                        $UsuarioMySqlDAO2->update($Usuario2);
                        $UsuarioMySqlDAO2->getTransaction()->commit();
                        $verifDNI = true;

                    }catch (Exception $e){
                        // Maneja el caso de error 110008
                        if($e->getCode() == "110008"){
                            $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                            $Usuario->estadoJugador = 'N'.substr($Usuario->estadoJugador, 1, 1);

                            $Usuario2 = new Usuario($CuentaAsociada->usuarioId);
                            $Usuario2->estadoJugador = 'N'.substr($Usuario2->estadoJugador, 1, 1);

                            $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
                            $transaction = $UsuarioMySqlDAO2->getTransaction();
                            $UsuarioMySqlDAO2->update($Usuario2);
                            $UsuarioMySqlDAO2->getTransaction()->commit();
                            $verifDNI = true;
                        }
                    }
                }else{
                    // Actualiza estado para usuarios no mandante 21
                    $Usuario->estadoJugador = 'N'.substr($Usuario->estadoJugador, 1, 1);
                    $verifDNI = true;
                }
                $verifDNI = true;
                break;

            case "USUDNIPOSTERIOR":
                // Procesa documento posterior similar al anterior
                if($Usuario->mandante == 21){
                    try {
                        $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                        $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1).'N';

                        $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                        $Usuario2->estadoJugador = substr($Usuario2->estadoJugador, 0, 1).'N';

                        $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
                        $transaction = $UsuarioMySqlDAO2->getTransaction();
                        $UsuarioMySqlDAO2->update($Usuario2);
                        $UsuarioMySqlDAO2->getTransaction()->commit();
                        $verifDNI = true;

                    }catch (Exception $e){
                        if($e->getCode() == "110008"){
                            $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                            $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1).'N';

                            $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                            $Usuario2->estadoJugador = substr($Usuario2->estadoJugador, 0, 1).'N';

                            $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
                            $transaction = $UsuarioMySqlDAO2->getTransaction();
                            $UsuarioMySqlDAO2->update($Usuario2);
                            $UsuarioMySqlDAO2->getTransaction()->commit();
                            $verifDNI = true;
                        }
                    }
                }else{
                    $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1).'N';
                    $verifDNI = true;
                }
                break;

            case "USUVERDOM":
                break;
        }

        // Actualiza el usuario si la verificación de DNI es exitosa
        if ($verifDNI) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario);
        }

        // Actualiza el log y confirma la transacción
        $UsuarioLog2MySqlDAO->update($UsuarioLog2);
        $Transaction->commit();

        // Prepara la respuesta exitosa
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Data"] = [];
    } else {

        // Obtiene información del log del usuario
        $UsuarioLog2 = new UsuarioLog2($Id);

        // Valida si existe el usuario asociado al log
        if ($UsuarioLog2->getUsuarioId() == "") {
            $response["HasError"] = true;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        } else {

            // Obtiene información completa del usuario y registros asociados
            $Usuario = new Usuario($UsuarioLog2->getUsuarioId());
            $Registro = new Registro("", $UsuarioLog2->getUsuarioId());
            $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioLog2->getUsuarioId());
            try{
                $UsuarioMandanteUsuario = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            }catch (Exception $e){
            }

            $Registro2MySqlDAO = new RegistroMySqlDAO();

            // Inicializa valores por defecto para campos vacíos
            if ($Registro->getCreditosBase() == "") {
                $Registro->setCreditosBase(0);
            }
            if ($Registro->getCreditos() == "") {
                $Registro->setCreditos(0);
            }
            if ($Usuario->intentos == "") {
                $Usuario->intentos = (0);
            }
            if ($Usuario->mandante == "") {
                $Usuario->mandante = (0);
            }
            if ($Usuario->usucreaId == "") {
                $Usuario->usucreaId = (0);
            }
            if ($Usuario->usumodifId == "") {
                $Usuario->usumodifId = (0);
            }
            if ($Usuario->usuretiroId == "") {
                $Usuario->usuretiroId = (0);
            }
            if ($Usuario->sponsorId == "") {
                $Usuario->sponsorId = (0);
            }
            if ($Usuario->tokenItainment == "") {
                $Usuario->tokenItainment = (0);
            }

            // Configura el log para aprobación
            $UsuarioLog2->setEstado("A");
            $UsuarioLog2->setUsuarioaprobarId($_SESSION['usuario2']);
            $UsuarioLog2->setUsuarioaprobarIp($ip);

            $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
            $Transaction = $UsuarioLog2MySqlDAO->getTransaction();

            // Obtiene y procesa el tipo de log
            $tipo = $UsuarioLog2->getTipo();

            if (is_numeric($tipo)) {
                $Clasificador = new Clasificador($tipo);
                $tipo = $Clasificador->getAbreviado();
            }

            // Inicializa banderas de verificación para diferentes tipos de documentos
            $verifDNIA = false;
            $verifDNIP = false;
            $verifDOMICILIO = false;

            // Procesa diferentes tipos de actualizaciones según el tipo de log
            switch ($tipo) {
                // Actualiza deporte favorito del usuario
                case 'FVSPORT':
                    $UsuarioOtrainfo->deporteFavorito = $UsuarioLog2->getValorDespues();
                    break;

                // Actualiza casino favorito del usuario  
                case 'FVCASINO':
                    $UsuarioOtrainfo->casinoFavorito = $UsuarioLog2->getValorDespues();
                    break;

                // Actualiza y activa cuenta bancaria del usuario
                case 'USUBANKACCOUNT':
                    $UsuarioBanco = new UsuarioBanco($UsuarioLog2->getValorDespues());
                    $UsuarioBanco->setEstado('A');

                    $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO($Transaction);
                    $UsuarioBancoMySqlDAO->update($UsuarioBanco);
                    break;

                // Actualiza dirección del usuario y su cuenta asociada si es mandante 21
                case "USUDIRECCION":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setDireccion($UsuarioLog2->getValorDespues());

                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setDireccion($UsuarioLog2->getValorDespues());

                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();

                        }catch (Exception $e){
                            // Manejo de excepción específica para código 110008
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setDireccion($UsuarioLog2->getValorDespues());

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Registro2->setDireccion($UsuarioLog2->getValorDespues());

                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();
                            }
                        }
                    }else{
                        $Registro->setDireccion($UsuarioLog2->getValorDespues());
                    }
                    break;

                // Actualiza género del usuario y su cuenta asociada si es mandante 21    
                case "USUGENERO":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setSexo($UsuarioLog2->getValorDespues());

                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setSexo($UsuarioLog2->getValorDespues());

                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();
                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setSexo($UsuarioLog2->getValorDespues());

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Registro2->setSexo($UsuarioLog2->getValorDespues());

                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();

                            }
                        }
                    }else{
                        $Registro->setSexo($UsuarioLog2->getValorDespues());
                    }

                    break;

                // Actualiza RFC (origen de fondos) del usuario y cuenta asociada si es mandante 21
                case "RFC":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setOrigenFondos($UsuarioLog2->getValorDespues());

                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setOrigenFondos($UsuarioLog2->getValorDespues());

                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();
                        }catch (Exception $e){
                            if($e->getCode()== "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setOrigenFondos($UsuarioLog2->getValorDespues());

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Registro2->setOrigenFondos($UsuarioLog2->getValorDespues());

                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();

                            }
                        }
                    }


                    $Registro->setOrigenFondos($UsuarioLog2->getValorDespues());

                    break;

                // Actualiza teléfono del usuario y cuenta asociada si es mandante 21
                case "USUTELEFONO":

                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setTelefono($UsuarioLog2->getValorDespues());

                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setTelefono($UsuarioLog2->getValorDespues());

                            $Registro2MySqlDAO = new RegistroMySqlDAO();
                            $transaction = $Registro2MySqlDAO->getTransaction();
                            $Registro2MySqlDAO->update($Registro2);
                            $Registro2MySqlDAO->getTransaction()->commit();
                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setTelefono($UsuarioLog2->getValorDespues());

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Registro2->setTelefono($UsuarioLog2->getValorDespues());

                                $Registro2MySqlDAO = new RegistroMySqlDAO();
                                $transaction = $Registro2MySqlDAO->getTransaction();
                                $Registro2MySqlDAO->update($Registro2);
                                $Registro2MySqlDAO->getTransaction()->commit();
                            }
                        }
                    }

                    $Registro->setTelefono($UsuarioLog2->getValorDespues());


                    break;

                // Actualiza RFC en información adicional del usuario y cuenta asociada
                case "USURFC":

                    if($Usuario->usuarioId == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $UsuarioOtrainfo->setInfo2($UsuarioLog2->getValorDespues());

                            $UsuarioOtrainfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId2);
                            $UsuarioOtrainfo2->setInfo2($UsuarioLog2->getValorDespues());

                            $UsuarioOtrainfoMySqlDAO2 = new UsuarioOtrainfoMySqlDAO();
                            $transaction = $UsuarioOtrainfoMySqlDAO2->getTransaction();
                            $UsuarioOtrainfoMySqlDAO2->update($UsuarioOtrainfo2);
                            $UsuarioOtrainfoMySqlDAO2->getTransaction()->commit();
                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $UsuarioOtrainfo->setInfo2($UsuarioLog2->getValorDespues());

                                $UsuarioOtrainfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId);
                                $UsuarioOtrainfo2->setInfo2($UsuarioLog2->getValorDespues());

                                $UsuarioOtrainfoMySqlDAO2 = new UsuarioOtrainfoMySqlDAO();
                                $transaction = $UsuarioOtrainfoMySqlDAO2->getTransaction();
                                $UsuarioOtrainfoMySqlDAO2->update($UsuarioOtrainfo2);
                                $UsuarioOtrainfoMySqlDAO2->getTransaction()->commit();
                            }
                        }

                    }else{
                        $UsuarioOtrainfo->setInfo2($UsuarioLog2->getValorDespues());
                    }
                    break;


                // Actualiza primer nombre del usuario y actualiza nombres completos en registros relacionados
                case "USUNOMBRE1":

                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setNombre1($UsuarioLog2->getValorDespues());
                            $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                            $Registro->nombre = $Usuario->nombre;
                            $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                            $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                            $UsuarioMandante2 = new UsuarioMandante("",$Usuario2->usuarioId,$Usuario2->mandante);
                            $Registro2->setNombre1($UsuarioLog2->getValorDespues());
                            $Usuario2->nombre = $Registro2->nombre1 . ' ' . $Registro2->nombre2 . ' ' . $Registro2->apellido1 . ' ' . $Registro2->apellido2;
                            $Registro2->nombre = $Usuario2->nombre;
                            $UsuarioMandante2->setNombres($Registro2->nombre1 . ' ' . $Registro2->nombre2);

                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();

                            $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                            $transaction = $Usuario2MySqlDAO->getTransaction();
                            $Usuario2MySqlDAO->update($Usuario2);
                            $Usuario2MySqlDAO->getTransaction()->commit();

                            $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                            $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                            $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);

                        }catch (Exception $e) {
                            if ($e->getCode() == "110008") {
                                $CuentaAsociada = new CuentaAsociada("", "", $Usuario->usuarioId);
                                $Registro->setNombre1($UsuarioLog2->getValorDespues());
                                $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                                $Registro->nombre = $Usuario->nombre;
                                $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                                $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                                $Registro2 = new Registro("", $CuentaAsociada->usuarioId);
                                $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                                $UsuarioMandante2 = new UsuarioMandante("", $Usuario2->usuarioId, $Usuario2->mandante);
                                $Registro2->setNombre1($UsuarioLog2->getValorDespues());
                                $Usuario2->nombre = $Registro2->nombre1 . ' ' . $Registro2->nombre2 . ' ' . $Registro2->apellido1 . ' ' . $Registro2->apellido2;
                                $Registro2->nombre = $Usuario2->nombre;
                                $UsuarioMandante2->setNombres($Registro2->nombre1 . ' ' . $Registro2->nombre2);


                                $Registro2MySqlDAO = new RegistroMySqlDAO();
                                $transaction = $Registro2MySqlDAO->getTransaction();
                                $Registro2MySqlDAO->update($Registro2);
                                $Registro2MySqlDAO->getTransaction()->commit();

                                $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                                $transaction = $Usuario2MySqlDAO->getTransaction();
                                $Usuario2MySqlDAO->update($Usuario2);
                                $Usuario2MySqlDAO->getTransaction()->commit();

                                $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                                $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                                $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);
                            }
                        }
                    }else{
                        $Registro->setNombre1($UsuarioLog2->getValorDespues());
                        $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                        $Registro->nombre = $Usuario->nombre;
                        $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                        $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);
                    }

                    break;



                case "USUNOMBRE2":
                    // Este bloque maneja la actualización del segundo nombre del usuario
                    // y sincroniza los cambios entre cuentas asociadas si el mandante es 21

                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setNombre2($UsuarioLog2->getValorDespues());
                            $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                            $Registro->nombre = $Usuario->nombre;
                            $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                            $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                            // Actualiza la información en la cuenta asociada
                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                            $UsuarioMandante2 = new UsuarioMandante("",$Usuario2->usuarioId,$Usuario2->mandante);

                            $Registro2->setNombre2($UsuarioLog2->getValorDespues());
                            $Usuario2->nombre = $Registro2->nombre1 . ' ' . $Registro2->nombre2 . ' ' . $Registro2->apellido1 . ' ' . $Registro2->apellido2;
                            $Registro2->nombre = $Usuario2->nombre;
                            $UsuarioMandante2->setNombres($Registro2->nombre1 . ' ' . $Registro2->nombre2);
                            $UsuarioMandante2->setApellidos($Registro2->apellido1 . ' ' . $Registro2->apellido2);

                            // Persiste los cambios en la base de datos
                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();

                            $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                            $transaction = $Usuario2MySqlDAO->getTransaction();
                            $Usuario2MySqlDAO->update($Usuario2);
                            $Usuario2MySqlDAO->getTransaction()->commit();

                            $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                            $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                            $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);


                        }catch (Exception $e){
                            // Maneja el caso de error 110008 actualizando una cuenta asociada alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);

                                $Registro->setNombre2($UsuarioLog2->getValorDespues());
                                $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                                $Registro->nombre = $Usuario->nombre;
                                $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                                $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                                // Actualiza la información en la cuenta asociada alternativa
                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Usuario2 = new Usuario($CuentaAsociada->usuarioId);
                                $UsuarioMandante2 = new UsuarioMandante("",$Usuario2->usuarioId,$Usuario2->mandante);

                                $Registro2->setNombre2($UsuarioLog2->getValorDespues());
                                $Usuario2->nombre = $Registro2->nombre1 . ' ' . $Registro2->nombre2 . ' ' . $Registro2->apellido1 . ' ' . $Registro2->apellido2;
                                $Registro2->nombre = $Usuario2->nombre;
                                $UsuarioMandante2->setNombres($Registro2->nombre1 . ' ' . $Registro2->nombre2);
                                $UsuarioMandante2->setApellidos($Registro2->apellido1 . ' ' . $Registro2->apellido2);

                                // Persiste los cambios en la base de datos para la cuenta alternativa
                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();

                                $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                                $transaction = $Usuario2MySqlDAO->getTransaction();
                                $Usuario2MySqlDAO->update($Usuario2);
                                $Usuario2MySqlDAO->getTransaction()->commit();

                                $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                                $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                                $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);
                            }
                        }
                    }else{
                        // Si no es mandante 21, solo actualiza la información básica del usuario
                        $Registro->setNombre2($UsuarioLog2->getValorDespues());
                        $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                        $Registro->nombre = $Usuario->nombre;
                        $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                        $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);
                    }
                    break;

                case "USUAPELLIDO1":
                    // Este bloque maneja la actualización del primer apellido del usuario
                    // y sincroniza los cambios entre cuentas asociadas si el mandante es 21

                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setApellido1($UsuarioLog2->getValorDespues());
                            $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                            $Registro->nombre = $Usuario->nombre;
                            $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                            $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                            // Actualiza la información en la cuenta asociada
                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                            $UsuarioMandante2 = new UsuarioMandante("",$Usuario2->usuarioId,$Usuario2->mandante);
                            $Registro2->setApellido1($UsuarioLog2->getValorDespues());
                            $Usuario2->nombre = $Registro2->nombre1 . ' ' . $Registro2->nombre2 . ' ' . $Registro2->apellido1 . ' ' . $Registro2->apellido2;
                            $Registro2->nombre = $Usuario2->nombre;
                            $UsuarioMandante2->setNombres($Registro2->nombre1 . ' ' . $Registro2->nombre2);
                            $UsuarioMandante2->setApellidos($Registro2->apellido1 . ' ' . $Registro2->apellido2);

                            // Persiste los cambios en la base de datos
                            $Registro2MySqlDAO = new RegistroMySqlDAO();
                            $transaction = $Registro2MySqlDAO->getTransaction();
                            $Registro2MySqlDAO->update($Registro2);
                            $Registro2MySqlDAO->getTransaction()->commit();

                            $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                            $transaction = $Usuario2MySqlDAO->getTransaction();
                            $Usuario2MySqlDAO->update($Usuario2);
                            $Usuario2MySqlDAO->getTransaction()->commit();

                            $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                            $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                            $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);
                            $UsuarioMandanteMySqlDAO2->getTransaction()->commit();

                        }catch (Exception $e){
                            // Maneja el caso de error 110008 actualizando una cuenta asociada alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);

                                $Registro->setApellido1($UsuarioLog2->getValorDespues());
                                $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                                $Registro->nombre = $Usuario->nombre;
                                $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                                $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                                // Actualiza la información en la cuenta asociada alternativa
                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Usuario2 = new Usuario($CuentaAsociada->usuarioId);
                                $UsuarioMandante2 = new UsuarioMandante("",$Usuario2->usuarioId,$Usuario2->mandante);
                                $Registro2->setApellido1($UsuarioLog2->getValorDespues());
                                $Usuario2->nombre = $Registro2->nombre1 . ' ' . $Registro2->nombre2 . ' ' . $Registro2->apellido1 . ' ' . $Registro2->apellido2;
                                $Registro2->nombre = $Usuario2->nombre;
                                $UsuarioMandante2->setNombres($Registro2->nombre1 . ' ' . $Registro2->nombre2);
                                $UsuarioMandante2->setApellidos($Registro2->apellido1 . ' ' . $Registro2->apellido2);

                                // Persiste los cambios en la base de datos para la cuenta alternativa
                                $Registro2MySqlDAO = new RegistroMySqlDAO();
                                $transaction = $Registro2MySqlDAO->getTransaction();
                                $Registro2MySqlDAO->update($Registro2);
                                $Registro2MySqlDAO->getTransaction()->commit();

                                $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                                $transaction = $Usuario2MySqlDAO->getTransaction();
                                $Usuario2MySqlDAO->update($Usuario2);
                                $Usuario2MySqlDAO->getTransaction()->commit();

                                $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                                $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                                $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);
                                $UsuarioMandanteMySqlDAO2->getTransaction()->commit();
                            }
                        }
                    }else{
                        // Si no es mandante 21, solo actualiza la información básica del usuario
                        $Registro->setApellido1($UsuarioLog2->getValorDespues());
                        $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                        $Registro->nombre = $Usuario->nombre;
                        $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                        $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                    }
                    break;

                case "USUAPELLIDO2":
                    // Este bloque maneja la actualización del segundo apellido del usuario
                    // y sincroniza los cambios entre cuentas asociadas si el mandante es 21

                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setApellido2($UsuarioLog2->getValorDespues());
                            $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                            $Registro->nombre = $Usuario->nombre;
                            $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                            $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                            // Actualiza la información en la cuenta asociada
                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                            $UsuarioMandante2 = new UsuarioMandante("",$Usuario2->usuarioId,$Usuario2->mandante);
                            $Registro2->setApellido2($UsuarioLog2->getValorDespues());
                            $Usuario2->nombre = $Registro2->nombre1 . ' ' . $Registro2->nombre2 . ' ' . $Registro2->apellido1 . ' ' . $Registro2->apellido2;
                            $Registro2->nombre = $Usuario2->nombre;
                            $UsuarioMandante2->setNombres($Registro2->nombre1 . ' ' . $Registro2->nombre2);
                            $UsuarioMandante2->setApellidos($Registro2->apellido1 . ' ' . $Registro2->apellido2);

                            // Persiste los cambios en la base de datos
                            $Registro2MySqlDAO = new RegistroMySqlDAO();
                            $transaction = $Registro2MySqlDAO->getTransaction();
                            $Registro2MySqlDAO->update($Registro2);
                            $Registro2MySqlDAO->getTransaction()->commit();

                            $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                            $transaction = $Usuario2MySqlDAO->getTransaction();
                            $Usuario2MySqlDAO->update($Usuario2);
                            $Usuario2MySqlDAO->getTransaction()->commit();

                            $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                            $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                            $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);


                        }catch (Exception $e){
                            // Maneja el caso de error 110008 actualizando una cuenta asociada alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);

                                $Registro->setApellido2($UsuarioLog2->getValorDespues());
                                $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                                $Registro->nombre = $Usuario->nombre;
                                $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                                $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                                // Actualiza la información en la cuenta asociada alternativa
                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Usuario2 = new Usuario($CuentaAsociada->usuarioId);
                                $UsuarioMandante2 = new UsuarioMandante("",$Usuario2->usuarioId,$Usuario2->mandante);
                                $Registro2->setApellido2($UsuarioLog2->getValorDespues());
                                $Usuario2->nombre = $Registro2->nombre1 . ' ' . $Registro2->nombre2 . ' ' . $Registro2->apellido1 . ' ' . $Registro2->apellido2;
                                $Registro2->nombre = $Usuario2->nombre;
                                $UsuarioMandante2->setNombres($Registro2->nombre1 . ' ' . $Registro2->nombre2);
                                $UsuarioMandante2->setApellidos($Registro2->apellido1 . ' ' . $Registro2->apellido2);

                                // Persiste los cambios en la base de datos para la cuenta alternativa
                                $Registro2MySqlDAO = new RegistroMySqlDAO();
                                $transaction = $Registro2MySqlDAO->getTransaction();
                                $Registro2MySqlDAO->update($Registro2);
                                $Registro2MySqlDAO->getTransaction()->commit();

                                $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                                $transaction = $Usuario2MySqlDAO->getTransaction();
                                $Usuario2MySqlDAO->update($Usuario2);
                                $Usuario2MySqlDAO->getTransaction()->commit();

                                $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                                $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                                $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);

                            }
                        }
                    }else{
                        // Si no es mandante 21, solo actualiza la información básica del usuario
                        $Registro->setApellido2($UsuarioLog2->getValorDespues());
                        $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                        $Registro->nombre = $Usuario->nombre;
                        $UsuarioMandanteUsuario->setNombres($Registro->nombre1 . ' ' . $Registro->nombre2);
                        $UsuarioMandanteUsuario->setApellidos($Registro->apellido1 . ' ' . $Registro->apellido2);

                    }
                    break;

                case "AFILIADORID":
                    // Este bloque maneja la actualización del ID del afiliador
                    // y sincroniza los cambios entre cuentas asociadas si el mandante es 21

                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setAfiliadorId($UsuarioLog2->getValorDespues());

                            // Actualiza la información en la cuenta asociada
                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setAfiliadorId($UsuarioLog2->getValorDespues());
                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();

                        }catch (Exception $e){
                            // Maneja el caso de error 110008 actualizando una cuenta asociada alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setAfiliadorId($UsuarioLog2->getValorDespues());

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Registro2->setAfiliadorId($UsuarioLog2->getValorDespues());

                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();
                            }
                        }

                    }else{
                        // Si no es mandante 21, solo actualiza el ID del afiliador
                        $Registro->setAfiliadorId($UsuarioLog2->getValorDespues());
                    }

                    break;
                case "USUCIUDADID":
                    // Este bloque maneja la actualización del ID de la ciudad
                    // y sincroniza los cambios entre cuentas asociadas si el mandante es 21

                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);

                            $Registro->setCiudadId($UsuarioLog2->getValorDespues());

                            // Actualiza la información en la cuenta asociada
                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setCiudadId($UsuarioLog2->getValorDespues());

                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();
                        }catch (Exception $e){
                            // Maneja el caso de error 110008 actualizando una cuenta asociada alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setCiudadId($UsuarioLog2->getValorDespues());

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Registro2->setCiudadId($UsuarioLog2->getValorDespues());

                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();
                            }
                        }
                    }else{
                        // Si no es mandante 21, solo actualiza el ID de la ciudad
                        $Registro->setCiudadId($UsuarioLog2->getValorDespues());
                    }
                    break;


                case "USUCELULAR":
                    // Este bloque maneja la actualización del número celular
                    // y sincroniza los cambios entre cuentas asociadas si el mandante es 21

                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);

                            $Registro->setCelular($UsuarioLog2->getValorDespues());

                            // Actualiza la información en la cuenta asociada
                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setCelular($UsuarioLog2->getValorDespues());

                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();

                        }catch (Exception $e){
                            // Maneja el caso de error 110008 actualizando una cuenta asociada alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setCelular($UsuarioLog2->getValorDespues());

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Registro2->setCelular($UsuarioLog2->getValorDespues());

                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();
                            }
                        }

                    }else{
                        // Si no es mandante 21, intenta crear un UsuarioMandante y actualiza el celular
                        try{
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, "0");

                        }catch (Exception $e){

                        }
                        $Registro->setCelular($UsuarioLog2->getValorDespues());
                    }


                    /*

                    $UsuarioToken2 = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                    $data = array(
                        "7040" . $UsuarioToken2->getRequestId() . "5" => array(
                            "notifications" => array(
                                array(
                                    "type" => "notification",
                                    "title" => "Notificacion",
                                    "content" => "Se ha actualizado el celular correctamente.",
                                    "action" => "slider.userDataConfirm"
                                )
                            ),
                        ),

                    );

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                    /* $WebsocketUsuario = new WebsocketUsuario($UsuarioToken2->getRequestId(), $data);
                     $WebsocketUsuario->sendWSMessage();
    */

                    break;

                // Caso para actualizar el email del usuario
                case "USUEMAIL":

                    // Si el usuario pertenece al mandante 21, actualiza el email en múltiples registros relacionados
                    if($Usuario->mandante == 21){
                        try {
                            // Obtiene la cuenta asociada y actualiza el email en el usuario principal
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Usuario->login = $UsuarioLog2->getValorDespues();
                            $Registro->setEmail($UsuarioLog2->getValorDespues());
                            $UsuarioMandanteUsuario->email = $UsuarioLog2->getValorDespues();

                            // Actualiza el email en el usuario secundario asociado
                            $Usuario2 = new Usuario($CuentaAsociada->usuarioId2);
                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $UsuarioMandante2 = new UsuarioMandante("",$CuentaAsociada->usuarioId2,$Usuario2->mandante);

                            $Usuario2->login = $UsuarioLog2->getValorDespues();
                            $Registro2->setEmail($UsuarioLog2->getValorDespues());
                            $UsuarioMandante2->email = $UsuarioLog2->getValorDespues();

                            // Persiste los cambios en la base de datos
                            $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
                            $transaction = $UsuarioMySqlDAO2->getTransaction();
                            $UsuarioMySqlDAO2->update($Usuario2);
                            $UsuarioMySqlDAO2->getTransaction()->commit();


                        }catch (Exception $e){
                            // Si ocurre un error específico (110008), realiza un proceso alternativo
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Usuario->login = $UsuarioLog2->getValorDespues();
                                $Registro->setEmail($UsuarioLog2->getValorDespues());
                                $UsuarioMandanteUsuario->email = $UsuarioLog2->getValorDespues();

                                // Actualiza el email en registros alternativos
                                $Usuario2 = new Usuario($CuentaAsociada->usuarioId);
                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $UsuarioMandante2 = new UsuarioMandante("",$CuentaAsociada->usuarioId2,$Usuario2->mandante);

                                $Usuario2->login = $UsuarioLog2->getValorDespues();
                                $Registro2->setEmail($UsuarioLog2->getValorDespues());
                                $UsuarioMandante2->email = $UsuarioLog2->getValorDespues();

                                // Persiste los cambios en múltiples tablas
                                $Registro2MySqlDAO = new RegistroMySqlDAO();
                                $transaction = $Registro2MySqlDAO->getTransaction();
                                $Registro2MySqlDAO->update($Registro2);
                                $Registro2MySqlDAO->getTransaction()->commit();

                                $Usuario2MySqlDAO = new UsuarioMySqlDAO();
                                $transaction = $Usuario2MySqlDAO->getTransaction();
                                $Usuario2MySqlDAO->update($Usuario2);
                                $Usuario2MySqlDAO->getTransaction()->commit();

                                $UsuarioMandanteMySqlDAO2 = new UsuarioMandanteMySqlDAO();
                                $Transaction = $UsuarioMandanteMySqlDAO2->getTransaction();
                                $UsuarioMandanteMySqlDAO2->update($UsuarioMandante2);
                                $UsuarioMandanteMySqlDAO2->getTransaction()->commit();

                            }
                        }
                    }

                    // Actualiza el email en el usuario principal
                    $Usuario->login = $UsuarioLog2->getValorDespues();
                    $Registro->setEmail($UsuarioLog2->getValorDespues());
                    $UsuarioMandanteUsuario->email = $UsuarioLog2->getValorDespues();

                    break;


                // Caso para actualizar el límite de depósito simple
                case "LIMITEDEPOSITOSIMPLE":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');
                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de depósito diario
                case "LIMITEDEPOSITODIARIO":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de depósito semanal
                case "LIMITEDEPOSITOSEMANA":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de depósito mensual
                case "LIMITEDEPOSITOMENSUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de depósito anual
                case "LIMITEDEPOSITOANUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;


                // Caso para actualizar el límite de apuesta deportiva simple
                case "LIMAPUDEPORTIVASIMPLE":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de apuesta deportiva diaria
                case "LIMAPUDEPORTIVADIARIO":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de apuesta deportiva semanal
                case "LIMAPUDEPORTIVASEMANA":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta deportiva mensual
                case "LIMAPUDEPORTIVAMENSUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta deportiva anual
                case "LIMAPUDEPORTIVAANUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;


                // Caso para actualizar el límite de apuesta de casino simple
                case "LIMAPUCASINOSIMPLE":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de apuesta de casino diario
                case "LIMAPUCASINODIARIO":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de apuesta de casino semanal
                case "LIMAPUCASINOSEMANA":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta de casino mensual
                case "LIMAPUCASINOMENSUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta de casino anual
                case "LIMAPUCASINOANUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;


                // Caso para actualizar el límite de apuesta de casino en vivo simple
                case "LIMAPUCASINOVIVOSIMPLE":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");
                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de apuesta de casino en vivo diario
                case "LIMAPUCASINOVIVODIARIO":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de apuesta de casino en vivo semanal
                case "LIMAPUCASINOVIVOSEMANA":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta de casino en vivo mensual
                case "LIMAPUCASINOVIVOMENSUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta de casino en vivo anual
                case "LIMAPUCASINOVIVOANUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta de juegos virtuales simple
                case "LIMAPUVIRTUALESSIMPLE":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");
                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de apuesta de juegos virtuales diario
                case "LIMAPUVIRTUALESDIARIO":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }

                    break;

                // Caso para actualizar el límite de apuesta de juegos virtuales semanal
                case "LIMAPUVIRTUALESSEMANA":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta de juegos virtuales mensual
                case "LIMAPUVIRTUALESMENSUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Caso para actualizar el límite de apuesta de juegos virtuales anual
                case "LIMAPUVIRTUALESANUAL":
                    try {
                        // Intenta obtener y desactivar la configuración existente
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioLog2->getUsuarioId(), "A", $Clasificador->getClasificadorId(), "", "");
                        //$UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());
                        $UsuarioConfiguracion->setEstado('I');

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                        // Actualiza el estado de la configuración a inactivo
                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                        throw new Exception("", "46");

                    } catch (Exception $e) {
                        if ($e->getCode() == "46") {
                            // Crea una nueva configuración con el nuevo valor
                            $UsuarioConfiguracion = new UsuarioConfiguracion();

                            $UsuarioConfiguracion->setUsuarioId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setEstado("A");
                            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                            $UsuarioConfiguracion->setUsucreaId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setUsumodifId($UsuarioLog2->getUsuarioId());
                            $UsuarioConfiguracion->setProductoId(0);
                            $UsuarioConfiguracion->setValor($UsuarioLog2->getValorDespues());

                            // Inserta la nueva configuración en la base de datos
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                        } else {
                            throw $e;
                        }
                    }
                    break;

                // Bloque de casos para actualizar diferentes atributos del usuario
                case "TIEMPOLIMITEAUTOEXCLUSION":
                    $Usuario->tiempoAutoexclusion = $UsuarioLog2->getValorDespues();
                    break;

                case "CAMBIOSAPROBACION":
                    $Usuario->cambiosAprobacion = $UsuarioLog2->getValorDespues();
                    break;

                case "ESTADOUSUARIO":
                    $Usuario->estado = $UsuarioLog2->getValorDespues();
                    break;

                // Bloque para actualizar la verificación de cédula anterior y estado del jugador
                // Maneja la sincronización entre cuentas asociadas para mandante 21
                case "USUDNIANTERIOR":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Usuario->verifcedulaAnt = 'S';
                            $Usuario->estadoJugador = 'A'.substr($Usuario->estadoJugador, 1, 1);
                            $verifDNIA = true;
                            $updateWs = true;

                            $Usuario1 = new Usuario($CuentaAsociada->usuarioId2);

                            $Usuario1->verifcedulaAnt = 'S';
                            $Usuario1->estadoJugador = 'A'.substr($Usuario1->estadoJugador, 1, 1);
                            $verifDNIA = true;
                            $updateWs = true;

                            $UsuarioMySqlDAO1 = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO1->getTransaction();
                            $UsuarioMySqlDAO1->update($Usuario1);
                            $UsuarioMySqlDAO1->getTransaction()->commit();

                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Usuario->verifcedulaAnt = 'S';
                                $Usuario->estadoJugador = 'A'.substr($Usuario->estadoJugador, 1, 1);
                                $verifDNIA = true;
                                $updateWs = true;

                                $Usuario1 = new Usuario($CuentaAsociada->usuarioId);
                                $Usuario1->verifcedulaAnt = 'S';
                                $Usuario1->estadoJugador = 'A'.substr($Usuario1->estadoJugador, 1, 1);
                                $verifDNIA = true;
                                $updateWs = true;

                                $UsuarioMySqlDAO1 = new UsuarioMySqlDAO();
                                $Transaction = $UsuarioMySqlDAO1->getTransaction();
                                $UsuarioMySqlDAO1->update($Usuario1);
                                $UsuarioMySqlDAO1->getTransaction()->commit();

                            }
                        }
                    }else{
                        $Usuario->verifcedulaAnt = 'S';
                        $Usuario->estadoJugador = 'A'.substr($Usuario->estadoJugador, 1, 1);
                        $verifDNIA = true;
                        $updateWs = true;
                    }
                    break;

                // Bloque para actualizar la verificación de cédula posterior y estado del jugador
                // Similar al anterior pero actualiza diferentes campos
                case "USUDNIPOSTERIOR":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Usuario->verifcedulaPost = 'S';
                            $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1).'C';

                            $verifDNIP = true;
                            $updateWs = true;

                            $Usuario1 = new Usuario($CuentaAsociada->usuarioId2);
                            $Usuario1->verifcedulaPost = 'S';
                            $Usuario1->estadoJugador = substr($Usuario1->estadoJugador, 0, 1).'C';

                            $verifDNIP = true;
                            $updateWs = true;

                            $UsuarioMySqlDAO1 = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO1->getTransaction();
                            $UsuarioMySqlDAO1->update($Usuario1);
                            $UsuarioMySqlDAO1->getTransaction()->commit();

                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Usuario->verifcedulaPost = 'S';
                                $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1).'C';

                                $verifDNIP = true;
                                $updateWs = true;

                                $Usuario1 = new Usuario($CuentaAsociada->usuarioId);
                                $Usuario1->verifcedulaPost = 'S';
                                $Usuario1->estadoJugador = substr($Usuario1->estadoJugador, 0, 1).'C';

                                $verifDNIP = true;
                                $updateWs = true;

                                $UsuarioMySqlDAO1 = new UsuarioMySqlDAO();
                                $Transaction = $UsuarioMySqlDAO1->getTransaction();
                                $UsuarioMySqlDAO1->update($Usuario1);
                                $UsuarioMySqlDAO1->getTransaction()->commit();
                            }
                        }
                    }else{
                        $Usuario->verifcedulaPost = 'S';
                        $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1).'C';

                        $verifDNIP = true;
                        $updateWs = true;
                    }
                    break;

                // Bloque para manejar la verificación de domicilio
                // Guarda una imagen en Google Cloud Storage y actualiza el estado
                case "USUVERDOM":
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $filename = "v" . $UsuarioLog2->usuarioId .'.png';
                    $Image = $UsuarioLog2->getImagen();
                    $dirsave = '/tmp/' . $filename;
                    $result = file_put_contents($dirsave, $Image);
                    if ($result !== false) {
                        shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp '.$dirsave.' gs://cedulas-1/c/');
                    } else {
                        $response["HasError"] = true;
                    }

                    $Usuario->verifDomicilio = 'S';
                    $verifDOMICILIO = true;
                    $updateWs = true;
                    break;

                // Bloque para actualizar la ciudad del usuario
                // Sincroniza la información entre cuentas asociadas si es mandante 21
                case "USUCIUDAD":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setCiudadId($UsuarioLog2->getValorDespues());

                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setCiudadId($UsuarioLog2->getValorDespues());

                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();
                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setCiudadId($UsuarioLog2->getValorDespues());

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();
                            }
                        }
                    }else{
                        $Registro->setCiudadId($UsuarioLog2->getValorDespues());
                    }
                    break;

                // Bloque para actualizar el código postal
                // Mantiene sincronizados los registros asociados para mandante 21
                case "USUCODIGOPOSTAL":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setCodigoPostal($UsuarioLog2->getValorDespues());

                            $Registro2 = new Registro($CuentaAsociada->usuarioId2);
                            $Registro2->setCodigoPostal($UsuarioLog2->getValorDespues());

                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();

                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setCodigoPostal($UsuarioLog2->getValorDespues());
                                $Registro2 = new Registro($CuentaAsociada->usuarioId2);
                                $Registro2->setCodigoPostal($UsuarioLog2->getValorDespues());

                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();

                            }
                        }
                    }else{
                        $Registro->setCodigoPostal($UsuarioLog2->getValorDespues());
                    }
                    break;

                // Bloque para actualizar la fecha de nacimiento
                // Actualiza la información en múltiples tablas y mantiene la sincronización
                case "USUFECHANACIM":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $fechaNacim=substr($UsuarioLog2->getValorDespues(), 0, 10);
                            $UsuarioOtrainfo->setFechaNacim($fechaNacim);

                            $UsuarioOtrainfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId2);
                            $fechaNacim=substr($UsuarioLog2->getValorDespues(), 0, 10);
                            $UsuarioOtrainfo2->setFechaNacim($fechaNacim);

                            $UsuarioOtrainfo2MySqlDAO = new UsuarioOtrainfoMySqlDAO();
                            $transaction = $UsuarioOtrainfo2MySqlDAO->getTransaction();
                            $UsuarioOtrainfo2MySqlDAO->update($UsuarioOtrainfo2);
                            $UsuarioOtrainfo2MySqlDAO->getTransaction()->commit();
                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $fechaNacim=substr($UsuarioLog2->getValorDespues(), 0, 10);
                                $UsuarioOtrainfo->setFechaNacim($fechaNacim);

                                $UsuarioOtrainfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId);
                                $fechaNacim=substr($UsuarioLog2->getValorDespues(), 0, 10);
                                $UsuarioOtrainfo2->setFechaNacim($fechaNacim);

                                $UsuarioOtrainfo2MySqlDAO = new UsuarioOtrainfoMySqlDAO();
                                $transaction = $UsuarioOtrainfo2MySqlDAO->getTransaction();
                                $UsuarioOtrainfo2MySqlDAO->update($UsuarioOtrainfo2);
                                $UsuarioOtrainfo2MySqlDAO->getTransaction()->commit();

                            }
                        }
                    }else{
                        $fechaNacim=substr($UsuarioLog2->getValorDespues(), 0, 10);
                        $UsuarioOtrainfo->setFechaNacim($fechaNacim);
                    }
                    break;

                // Bloque para actualizar el número de cédula
                // Mantiene la sincronización entre cuentas asociadas
                case "USUCEDULA":
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setCedula(substr($UsuarioLog2->getValorDespues(),0,20));

                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $Registro2->setCedula(substr($UsuarioLog2->getValorDespues(),0,20));

                            $Registro2MySqlDAO = new RegistroMySqlDAO();
                            $transaction = $Registro2MySqlDAO->getTransaction();
                            $Registro2MySqlDAO->update($Registro2);
                            $Registro2MySqlDAO->getTransaction()->commit();

                        }catch (Exception $e){
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setCedula(substr($UsuarioLog2->getValorDespues(),0,20));

                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $Registro2MySqlDAO = new RegistroMySqlDAO();
                                $transaction=$Registro2MySqlDAO->getTransaction();
                                $Registro2MySqlDAO->update($Registro2);
                                $Registro2MySqlDAO->getTransaction()->commit();
                            }
                        }
                    }else{
                        $Registro->setCedula(substr($UsuarioLog2->getValorDespues(),0,20));
                    }
                    break;

                // Bloques simples para actualizar nacionalidad y tipo de documento
                case "USUNACIONALIDAD":
                    $Registro->setNacionalidadId($UsuarioLog2->getValorDespues());
                    break;

                case "USUTIPODOC":
                    $Registro->setTipoDoc($UsuarioLog2->getValorDespues());
                    break;

                case "GENDER":
                    // Bloque para actualizar el género del usuario
                    // Mantiene sincronización entre cuentas asociadas si el mandante es 21
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $Registro->setSexo($UsuarioLog2->getValorDespues());
                            $Registro2 = new Registro("",$CuentaAsociada->usuarioId2);
                            $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                            $transaction = $RegistroMySqlDAO2->getTransaction();
                            $RegistroMySqlDAO2->update($Registro2);
                            $RegistroMySqlDAO2->getTransaction()->commit();
                        }catch (Exception $e){
                            // Manejo de error específico para código 110008
                            // Actualiza el género en una cuenta asociada alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $Registro->setSexo($UsuarioLog2->getValorDespues());
                                $Registro2 = new Registro("",$CuentaAsociada->usuarioId);
                                $RegistroMySqlDAO2 = new RegistroMySqlDAO();
                                $transaction = $RegistroMySqlDAO2->getTransaction();
                                $RegistroMySqlDAO2->update($Registro2);
                                $RegistroMySqlDAO2->getTransaction()->commit();
                            }
                        }
                    }else{
                        $Registro->setSexo($UsuarioLog2->getValorDespues());
                    }
                    break;

                case "UINFO1":
                    // Bloque para actualizar la información adicional 1 del usuario
                    // Sincroniza los cambios entre cuentas asociadas para mandante 21
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $UsuarioOtrainfo->setInfo1($UsuarioLog2->getValorDespues());
                            $UsuarioOtrainfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId2);
                            $UsuarioOtrainfo2->setInfo1($UsuarioLog2->getValorDespues());

                            $UsuarioOtraInfoMySqlDAO2 = new UsuarioOtrainfoMySqlDAO();
                            $transaction = $UsuarioOtraInfoMySqlDAO2->getTransaction();
                            $UsuarioOtraInfoMySqlDAO2->update($UsuarioOtrainfo2);
                            $UsuarioOtraInfoMySqlDAO2->getTransaction()->commit();
                        }catch (Exception $e){
                            // Manejo de error para actualizar información en cuenta alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $UsuarioOtrainfo->setInfo1($UsuarioLog2->getValorDespues());
                                $UsuarioOtrainfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId);
                                $UsuarioOtrainfo2->setInfo1($UsuarioLog2->getValorDespues());
                                $UsuarioOtraInfoMySqlDAO2 = new UsuarioOtrainfoMySqlDAO();
                                $transaction = $UsuarioOtraInfoMySqlDAO2->getTransaction();
                                $UsuarioOtraInfoMySqlDAO2->update($UsuarioOtrainfo2);
                                $UsuarioOtraInfoMySqlDAO2->getTransaction()->commit();

                            }
                        }

                    }else{
                        $UsuarioOtrainfo->setInfo1($UsuarioLog2->getValorDespues());
                    }
                    break;

                case "UINFO2":
                    // Bloque para actualizar la información adicional 2 del usuario
                    // Mantiene sincronización de datos entre cuentas asociadas
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $UsuarioOtrainfo->setInfo2($UsuarioLog2->getValorDespues());

                            $UsuarioOtraInfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId2);
                            $UsuarioOtraInfo2->setInfo2($UsuarioLog2->getValorDespues());

                            $UsuarioOtraInfo2MySqlDAO = new UsuarioOtrainfoMySqlDAO();
                            $transaction = $UsuarioOtraInfo2MySqlDAO->getTransaction();
                            $UsuarioOtraInfo2MySqlDAO->update($UsuarioOtraInfo2);
                            $UsuarioOtraInfo2MySqlDAO->getTransaction()->commit();
                        }catch (Exception $e){
                            // Manejo de error específico actualizando cuenta alternativa
                            if($e->getCode() == "110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $UsuarioOtrainfo->setInfo2($UsuarioLog2->getValorDespues());

                                $UsuarioOtraInfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId);
                                $UsuarioOtraInfo2->setInfo2($UsuarioLog2->getValorDespues());

                                $UsuarioOtraInfo2MySqlDAO = new UsuarioOtrainfoMySqlDAO();
                                $transaction = $UsuarioOtraInfo2MySqlDAO->getTransaction();
                                $UsuarioOtraInfo2MySqlDAO->update($UsuarioOtraInfo2);
                                $UsuarioOtraInfo2MySqlDAO->getTransaction()->commit();
                            }
                        }

                    }else{
                        $UsuarioOtrainfo->setInfo2($UsuarioLog2->getValorDespues());
                    }
                    break;

                case "UINFO3":
                    // Bloque para actualizar la información adicional 3 del usuario
                    // Sincroniza información entre cuentas asociadas para mandante 21
                    if($Usuario->mandante == 21){
                        try {
                            $CuentaAsociada = new CuentaAsociada("",$Usuario->usuarioId);
                            $UsuarioOtrainfo->setInfo3($UsuarioLog2->getValorDespues());

                            $UsuarioOtraInfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId2);
                            $UsuarioOtraInfo2->setInfo3($UsuarioLog2->getValorDespues());

                            $UsuarioOtraInfo2MySqlDAO = new UsuarioOtrainfoMySqlDAO();
                            $transaction = $UsuarioOtraInfo2MySqlDAO->getTransaction();
                            $UsuarioOtraInfo2MySqlDAO->update($UsuarioOtraInfo2);
                            $UsuarioOtraInfo2MySqlDAO->getTransaction()->commit();
                        }catch (Exception $e){
                            // Manejo de error actualizando información en cuenta alternativa
                            if($e->getCode()=="110008"){
                                $CuentaAsociada = new CuentaAsociada("","",$Usuario->usuarioId);
                                $UsuarioOtrainfo->setInfo3($UsuarioLog2->getValorDespues());

                                $UsuarioOtraInfo2 = new UsuarioOtrainfo($CuentaAsociada->usuarioId);
                                $UsuarioOtraInfo2->setInfo3($UsuarioLog2->getValorDespues());

                                $UsuarioOtraInfo2MySqlDAO = new UsuarioOtrainfoMySqlDAO();
                                $transaction = $UsuarioOtraInfo2MySqlDAO->getTransaction();
                                $UsuarioOtraInfo2MySqlDAO->update($UsuarioOtraInfo2);
                                $UsuarioOtraInfo2MySqlDAO->getTransaction()->commit();
                            }
                        }

                    }
                    $UsuarioOtrainfo->setInfo3($UsuarioLog2->getValorDespues());
                    break;
                case "USUFORTEST":
                    $Usuario->test = $UsuarioLog2->getValorDespues();
                    break;


            }

            // Bloque final de actualización
            // Actualiza fechas y persiste cambios en múltiples tablas
            $Usuario->fechaActualizacion = date('Y-m-d H:i:s');

            $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
            $RegistroMySqlDAO->update($Registro);


            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario);

            $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);
            $UsuarioOtrainfoMySqlDAO->update($UsuarioOtrainfo);


            if($UsuarioMandanteUsuario->usumandanteId != ''){
                $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaction);
                $UsuarioMandanteMySqlDAO->update($UsuarioMandanteUsuario);
            }

            $UsuarioLog2MySqlDAO->update($UsuarioLog2);

            $Transaction->commit();


            // Bloque de manejo de verificación de documentos
            // Procesa y almacena imágenes de documentos en Google Cloud Storage
            if ($verifDNIP || $verifDNIA) {

                $data = $UsuarioLog2->imagen;
                $filename = "c" . $UsuarioLog2->usuarioId;

                if ($verifDNIP) {
                    $filename = $filename . 'P';

                } else {
                    $filename = $filename . 'A';

                }
                $filename = $filename . '.png';

                if (!file_exists('/tmp/')) {
                    mkdir('/tmp/', 0755, true);
                }

                $dirsave = '/tmp/' . $filename;
                file_put_contents($dirsave, $data);

                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp '.$dirsave.' gs://cedulas-1/c/');


            }

            if ($verifDNIA) {

            }


            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = [];

        }

    }

    // Bloque de actualización de WebSocket
    // Actualiza la información del usuario en tiempo real si está en ambiente de desarrollo
    if ($updateWs) {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $Usuario = new Usuario($UsuarioLog2->getUsuarioId());

            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
            $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
            $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            // $WebsocketUsuario->sendWSMessage();

        }
    }

}elseif($TypeUser==1){ //Aprobacion o rechazo de logs para puntos de venta
    // Este bloque maneja la aprobación o rechazo de logs para puntos de venta

    if ($State == 'R') {
        // Maneja el caso de rechazo de logs
        $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $ip = explode(",", $ip)[0];

        // Obtiene y actualiza el registro de log con estado rechazado
        $UsuarioLog2 = new UsuarioLog2($Id);

        $UsuarioLog2->setEstado("NA");
        $UsuarioLog2->setUsuarioaprobarId($_SESSION['usuario2']);
        $UsuarioLog2->setUsuarioaprobarIp($ip);

        // Inicia transacción y obtiene el tipo de log
        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
        $Transaction = $UsuarioLog2MySqlDAO->getTransaction();

        $tipo = $UsuarioLog2->getTipo();

        if (is_numeric($tipo)) {
            $Clasificador = new Clasificador($tipo);
            $tipo = $Clasificador->getAbreviado();
        }

        // Inicializa variables de control
        $verifDNI = false;

        $Usuario = new Usuario($UsuarioLog2->getUsuarioId());

        // Procesa diferentes tipos de logs según el caso
        switch ($tipo) {
            case "USUDNIANTERIOR":
                // Actualiza estado de verificación de DNI anterior
                $Usuario->estadoJugador = 'N'.substr($Usuario->estadoJugador, 1, 1);
                $Usuario->verifcedulaAnt = 'N';
                $verifDNI = true;
                $updateWs=true;
                break;

            case "USUDNIPOSTERIOR":
                // Actualiza estado de verificación de DNI posterior
                $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1).'N';
                $Usuario->verifcedulaPost = 'N';
                $verifDNI = true;
                $updateWs=true;
                break;
        }

        // Actualiza el usuario si hubo cambios en verificación de DNI
        if ($verifDNI) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario);
        }

        // Actualiza el log y confirma la transacción
        $UsuarioLog2MySqlDAO->update($UsuarioLog2);
        $Transaction->commit();

        // Prepara respuesta exitosa
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Data"] = [];

    }else {
        // Maneja el caso de aprobación de logs

        // Obtiene y valida el log del usuario
        $UsuarioLog2 = new UsuarioLog2($Id);

        if ($UsuarioLog2->getUsuarioId() == "") {
            // Maneja caso de usuario no encontrado
            $response["HasError"] = true;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        } else {
            // Procesa la aprobación del log

            // Inicializa objetos necesarios
            $Usuario = new Usuario($UsuarioLog2->getUsuarioId());
            $PuntoVenta = new PuntoVenta("", $UsuarioLog2->getUsuarioId());

            try{
                $UsuarioMandanteUsuario = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            }catch (Exception $e){
            }

            // Valida y establece valores por defecto
            if ($Usuario->intentos == "") {
                $Usuario->intentos = (0);
            }
            if ($Usuario->mandante == "") {
                $Usuario->mandante = (0);
            }
            if ($Usuario->usucreaId == "") {
                $Usuario->usucreaId = (0);
            }
            if ($Usuario->usumodifId == "") {
                $Usuario->usumodifId = (0);
            }
            if ($Usuario->usuretiroId == "") {
                $Usuario->usuretiroId = (0);
            }
            if ($Usuario->sponsorId == "") {
                $Usuario->sponsorId = (0);
            }
            if ($Usuario->tokenItainment == "") {
                $Usuario->tokenItainment = (0);
            }

            // Actualiza estado del log a aprobado
            $UsuarioLog2->setEstado("A");
            $UsuarioLog2->setUsuarioaprobarId($_SESSION['usuario2']);
            $UsuarioLog2->setUsuarioaprobarIp($ip);

            // Inicia transacción y procesa tipo de log
            $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
            $Transaction = $UsuarioLog2MySqlDAO->getTransaction();

            $tipo = $UsuarioLog2->getTipo();

            if (is_numeric($tipo)) {
                $Clasificador = new Clasificador($tipo);
                $tipo = $Clasificador->getAbreviado();
            }

            // Inicializa variables de control
            $verifDNIA = false;
            $verifDNIP = false;
            $verifDOMICILIO = false;

            // Procesa diferentes tipos de actualización según el caso
            switch ($tipo) {
                case "USUDIRECCION":
                    $PuntoVenta->setDireccion($UsuarioLog2->getValorDespues());
                    break;

                case "USUCELULAR":
                    $PuntoVenta->setTelefono($UsuarioLog2->getValorDespues());
                    break;

                case "USUCEDULA":
                    $PuntoVenta->setCedula($UsuarioLog2->getValorDespues());
                    break;

                case "USUFACEBOOK":
                    $PuntoVenta->setFacebook($UsuarioLog2->getValorDespues());
                    break;

                case "USUINSTAGRAM":
                    $PuntoVenta->setInstagram($UsuarioLog2->getValorDespues());
                    break;

                case "USUOTRAREDSOCIAL":
                    $PuntoVenta->setOtraRedesSocial($UsuarioLog2->getValorDespues());
                    break;

                case "PVNOMBRE":
                    $PuntoVenta->setDescripcion($UsuarioLog2->getValorDespues());
                    break;

                case "USUDNIANTERIOR":
                    $Usuario->verifcedulaAnt = 'S';
                    $Usuario->estadoJugador = 'A'.substr($Usuario->estadoJugador, 1, 1);
                    $verifDNIA = true;
                    $updateWs = true;
                    break;

                case "USUDNIPOSTERIOR":
                    $Usuario->verifcedulaPost = 'S';
                    $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1).'C';
                    $verifDNIP = true;
                    $updateWs = true;
                    break;
            }

            // Actualiza punto de venta y usuario
            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);
            $PuntoVentaMySqlDAO->update($PuntoVenta);

            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario);

            // Actualiza usuario mandante si existe
            if($UsuarioMandanteUsuario->usumandanteId != ''){
                $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaction);
                $UsuarioMandanteMySqlDAO->update($UsuarioMandanteUsuario);
            }

            // Actualiza log y confirma transacción
            $UsuarioLog2MySqlDAO->update($UsuarioLog2);
            $Transaction->commit();

            // Procesa verificación de DNI si es necesario
            if ($verifDNIP || $verifDNIA) {
                // Maneja el almacenamiento de imágenes de DNI
                $data = $UsuarioLog2->imagen;
                $filename = "c" . $UsuarioLog2->usuarioId;

                if ($verifDNIP) {
                    $filename = $filename . 'P';
                } else {
                    $filename = $filename . 'A';
                }
                $filename = $filename . '.png';

                if (!file_exists('/tmp/')) {
                    mkdir('/tmp/', 0755, true);
                }

                $dirsave = '/tmp/' . $filename;
                file_put_contents($dirsave, $data);

                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp '.$dirsave.' gs://cedulas-1/c/');
            }

            // Prepara respuesta exitosa
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];
            $response["Data"] = [];
        }
    }
}
