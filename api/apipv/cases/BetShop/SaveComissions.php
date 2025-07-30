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
 * BetShop/SaveComissions
 *
 * Guardar las comisiones de un usuario.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params->Id Identificador del usuario.
 * @param int $params->ProductId Identificador del producto.
 * @param string $params->ProductName Nombre del producto.
 * @param int $params->FromId Identificador del remitente.
 * @param float $params->ComissionLevel1 Nivel de comisión 1.
 * @param float $params->ComissionLevel2 Nivel de comisión 2.
 * @param float $params->ComissionLevel3 Nivel de comisión 3.
 * @param float $params->ComissionLevel4 Nivel de comisión 4.
 * @param float $params->ComissionLevelBetShop Nivel de comisión para BetShop.
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError: boolean Indica si ocurrió un error.
 * - AlertType: string Tipo de alerta.
 * - AlertMessage: string Mensaje de alerta.
 * - ModelErrors: array Errores del modelo.
 * - Data: array Datos adicionales.
 *
 * @throws Exception Si se supera el límite de comisión permitido.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Obtención parámetros de la solicitud*/
$param = $params;
//foreach ($params as $param) {
if (true) {
    // Asignación de valores del objeto $param a variables locales
    $Id = $param->Id;
    $ProductId = $param->ProductId;
    $ProductId = $param->Id;

    $ProductName = $param->ProductName;
    $FromId = $param->FromId;

    // Niveles de comisión
    $ComissionLevel1 = $param->ComissionLevel1; // Nivel de comisión 1
    $ComissionLevel2 = $param->ComissionLevel2; // Nivel de comisión 2
    $ComissionLevel3 = $param->ComissionLevel3; // Nivel de comisión 3
    $ComissionLevel4 = $param->ComissionLevel4; // Nivel de comisión 4
    $ComissionLevelBetShop = $param->ComissionLevelBetShop; // Nivel de comisión de la casa de apuestas

    // Creación de instancias de Usuario y UsuarioPerfil
    $Usuario = new Usuario($FromId); // Instancia de Usuario con ID de origen
    $UsuarioPerfil = new UsuarioPerfil($FromId); // Instancia de UsuarioPerfil con ID de origen

    /**
     * Función para obtener límites de comisión
     *
     * @param array $clasificadorAbreviados Abreviados de clasificador
     * @param float $ComissionLevel1 Nivel de comisión 1
     * @param float $ComissionLevel2 Nivel de comisión 2
     * @param float $ComissionLevelBetShop Nivel de comisión de la casa de apuestas
     * @param Usuario $Usuario Instancia de Usuario
     * @param UsuarioPerfil $UsuarioPerfil Instancia de UsuarioPerfil
     * @return array|mixed Resultado de la consulta a clasificadores
     */
    function obtenerLimitesComision($clasificadorAbreviados, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil)
    {
        $Clasificador = new Clasificador(); // Instancia de Clasificador
        $rules = []; // Inicialización de reglas de filtro
        // Adición de regla de filtro para clasificador
        array_push($rules, array("field" => "clasificador.abreviado", "data" => $clasificadorAbreviados, "op" => "in"));
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);
        $IdsClasificador = $Clasificador->getClasificadoresCustom("clasificador_id,abreviado", "clasificador_id", "asc", 0, 1000, $json, true);
        $IdsClasificador = json_decode($IdsClasificador);
        foreach ($IdsClasificador->data as $value) {
            switch ($value->{"clasificador.abreviado"}) {

                /**
                 * Manejo de límites de comisiones para concesionarios y subconcesionarios.
                 * Dependiendo del tipo de límite solicitado (especificado en los casos), se crea un objeto
                 * MandanteDetalle y se obtiene el valor correspondiente a ese límite.
                 */

                case "LIMITCOMISIONCONCESIONARIOSBNGRAFILIADOS":
                case "LIMITCOMISIONCONCESIONARIOSBGGRPVIP":
                case "LIMITCOMISIONCONCESIONARIOSBGGRPV":
                case "LIMITCOMISIONCONCESIONARIODEPOSITPV":
                case "LIMITCOMISIONCONCESIONARIOAPSPV":
                case "LIMITCOMISIONCONCESIONARIONOTESPV":
                case "LIMITCOMISIONCONCESIONARIOSBGGRAFILIADOS":
                case "LIMITCOMISIONCONCESIONARIOCASINONGRAFILIADOS":
                case  "LIMITCOMISIONCONCESIONARIODEPOSITCT":
                case "LIMITCOMISIONCONCESIONARIODEPOSITAFILIADOS":
                    try {
                        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $value->{"clasificador.clasificador_id"}, $Usuario->paisId, 'A');
                        $LimitConcesionario = $MandanteDetalle->getValor();
                    } catch (Exception $e) {

                    }

                    break;

                case "LIMITCOMISIONSUBCONCESIONARIOSBNGRAFILIADOS":
                case "LIMITCOMISIONSUBCONCESIONARIOSBGGRPVIP":
                case "LIMITCOMISIONSUBCONCESIONARIOSBGGRPV":
                case "LIMITCOMISIONSUBCONCESIONARIODEPOSITPV":
                case "LIMITCOMISIONSUBCONCESIONARIOAPSPV":
                case "LIMITCOMISIONSUBCONCESIONARIONOTESPV":
                case "LIMITCOMISIONSUBCONCESIONARIOSBGGRAFILIADOS":
                case "LIMITCOMISIONSUBCONCESIONARIOCASINONGRAFILIADOS":
                case  "LIMITCOMISIONSUBCONCESIONARIODEPOSITCT":
                case "LIMITCOMISIONSUBCONCESIONARIODEPOSITAFILIADOS":
                    try {
                        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $value->{"clasificador.clasificador_id"}, $Usuario->paisId, 'A');
                        $LimitSubConcesionario = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    break;

                case "LIMITCOMISIONPVSBNGRAFILIADOS":
                case "LIMITCOMISIONPVSBGGRPVIP":
                case "LIMITCOMISIONPVSBGGRPV":
                case "LIMITCOMISIONPVDEPOSITPV":
                case "LIMITCOMISIONPVAPSPV":
                case "LIMITCOMISIONPVNOTESPV":
                case "LIMITCOMISIONPVSBGGRAFILIADOS":
                case "LIMITCOMISIONPVCASINONGRAFILIADOS":
                case  "LIMITCOMISIONPVDEPOSITCT":
                case "LIMITCOMISIONPVDEPOSITAFILIADOS":
                    try {
                        // Se crea una instancia de MandanteDetalle con los parámetros específicos del usuario y el clasificador
                        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $value->{"clasificador.clasificador_id"}, $Usuario->paisId, 'A');
                        // Se obtiene el valor correspondiente al límite de PV
                        $LimitPv = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }
                    break;

                case "LIMITCOMISIONAFILIADORSBNGRAFILIADOS":
                case "LIMITCOMISIONAFILIADORSBGGRPVIP":
                case "LIMITCOMISIONAFILIADORSBGGRPV":
                case "LIMITCOMISIONAFILIADORDEPOSITPV":
                case "LIMITCOMISIONAFILIADORAPSPV":
                case "LIMITCOMISIONAFILIADORNOTESPV":
                case "LIMITCOMISIONAFILIADORSBGGRAFILIADOS":
                case "LIMITCOMISIONAFILIADORCASINONGRAFILIADOS":
                case  "LIMITCOMISIONAFILIADORDEPOSITCT":
                case "LIMITCOMISIONAFILIADORDEPOSITAFILIADOS":
                    try {
                        // Se crea una instancia de MandanteDetalle con los parámetros específicos del usuario y el clasificador
                        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $value->{"clasificador.clasificador_id"}, $Usuario->paisId, 'A');
                        $LimitAfiliador = $MandanteDetalle->getValor();

                    } catch (Exception $e) {
                    }
                    break;
            }
        }
        /*
         * Verifica si los niveles de comisión superan los límites establecidos para diferentes perfiles.
         * Lanza una excepción si se supera el límite de comisión para concesionarios, sub-concesionarios,
         * puntos de venta o afiliadores.
         **/
        if ($LimitConcesionario != '' && $LimitConcesionario != 0 && $ComissionLevel1 > $LimitConcesionario) {
            throw new Exception("No puede superar el límite de comisión", "6969");
        }
        if ($LimitSubConcesionario != '' && $LimitSubConcesionario != 0 && $ComissionLevel2 > $LimitSubConcesionario) {
            throw new Exception("No puede superar el límite de comisión", "6969");
        }
        if ($LimitPv != '' && $LimitPv != 0 && $UsuarioPerfil->getPerfilId() == 'PUNTOVENTA' && $ComissionLevelBetShop > $LimitPv) {
            throw new Exception("No puede superar el límite de comisión", "6969");
        }
        if ($LimitAfiliador != '' && $LimitAfiliador != 0 && $UsuarioPerfil->getPerfilId() == 'AFILIADOR' && $ComissionLevelBetShop > $LimitAfiliador) {
            throw new Exception("No puede superar el límite de comisión", "6969");
        }
    }

    $clasificadorAbreviadosNGRAfiliados = "'LIMITCOMISIONCONCESIONARIOSBNGRAFILIADOS','LIMITCOMISIONSUBCONCESIONARIOSBNGRAFILIADOS','LIMITCOMISIONPVSBNGRAFILIADOS','LIMITCOMISIONAFILIADORSBNGRAFILIADOS'";
    $clasificadorAbreviadosBGGRPVIP = "'LIMITCOMISIONCONCESIONARIOSBGGRPVIP','LIMITCOMISIONSUBCONCESIONARIOSBGGRPVIP','LIMITCOMISIONPVSBGGRPVIP', 'LIMITCOMISIONAFILIADORSBGGRPVIP'";
    $clasificadorAbreviadoSBGGRPV = "'LIMITCOMISIONCONCESIONARIOSBGGRPV','LIMITCOMISIONSUBCONCESIONARIOSBGGRPV','LIMITCOMISIONPVSBGGRPV','LIMITCOMISIONAFILIADORSBGGRPV'";
    $clasificadorAbreviadosDepositoPV = "'LIMITCOMISIONCONCESIONARIODEPOSITPV','LIMITCOMISIONSUBCONCESIONARIODEPOSITPV','LIMITCOMISIONPVDEPOSITPV','LIMITCOMISIONAFILIADORDEPOSITPV'";
    $clasificadorApuestaSportPV = "'LIMITCOMISIONCONCESIONARIOAPSPV','LIMITCOMISIONSUBCONCESIONARIOAPSPV','LIMITCOMISIONPVAPSPV','LIMITCOMISIONAFILIADORAPSPV'";
    $clasificadorNotesPv = "'LIMITCOMISIONCONCESIONARIONOTESPV','LIMITCOMISIONSUBCONCESIONARIONOTESPV','LIMITCOMISIONPVNOTESPV','LIMITCOMISIONAFILIADORNOTESPV'";
    $clasificadorSportBGgrAfiliados = "'LIMITCOMISIONCONCESIONARIOSBGGRAFILIADOS','LIMITCOMISIONSUBCONCESIONARIOSBGGRAFILIADOS','LIMITCOMISIONPVSBGGRAFILIADOS','LIMITCOMISIONAFILIADORSBGGRAFILIADOS'";
    $clasificadorCasinoNgrAfiliados = "'LIMITCOMISIONCONCESIONARIOCASINONGRAFILIADOS','LIMITCOMISIONSUBCONCESIONARIOCASINONGRAFILIADOS','LIMITCOMISIONPVCASINONGRAFILIADOS','LIMITCOMISIONAFILIADORCASINONGRAFILIADOS'";
    $clasificadorDepositComisionT = "'LIMITCOMISIONCONCESIONARIODEPOSITCT','LIMITCOMISIONSUBCONCESIONARIODEPOSITCT','LIMITCOMISIONPVDEPOSITCT','LIMITCOMISIONAFILIADORDEPOSITCT'";
    $clasificadoDepositAfiliados = "'LIMITCOMISIONCONCESIONARIODEPOSITAFILIADOS','LIMITCOMISIONSUBCONCESIONARIODEPOSITAFILIADOS','LIMITCOMISIONPVDEPOSITAFILIADOS','LIMITCOMISIONAFILIADORDEPOSITAFILIADOS'";
    if ($ProductName == 'Sportbook NGR Afiliados') {
        obtenerLimitesComision($clasificadorAbreviadosNGRAfiliados, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Sportbook GGR Punto venta IP') {
        obtenerLimitesComision($clasificadorAbreviadosBGGRPVIP, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Sportbook GGR Punto venta') {
        obtenerLimitesComision($clasificadorAbreviadoSBGGRPV, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Deposito Punto venta') {
        obtenerLimitesComision($clasificadorAbreviadosDepositoPV, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Apuesta Sport Punto venta') {
        obtenerLimitesComision($clasificadorApuestaSportPV, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Notas de retiro Punto venta') {
        obtenerLimitesComision($clasificadorNotesPv, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Sportbook GGR Afiliados') {
        obtenerLimitesComision($clasificadorSportBGgrAfiliados, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Casino NGR Afiliados') {
        obtenerLimitesComision($clasificadorCasinoNgrAfiliados, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Deposito Comision por Transaccion') {
        obtenerLimitesComision($clasificadorDepositComisionT, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }
    if ($ProductName == 'Deposito Afiliados') {
        obtenerLimitesComision($clasificadoDepositAfiliados, $ComissionLevel1, $ComissionLevel2, $ComissionLevelBetShop, $Usuario, $UsuarioPerfil);
    }


    try {
        // Crea un nuevo objeto de tipo Concesionario con el FromId y un estado inicial
        $ConcesionarioU = new Concesionario($FromId, '0');
        $ConcesionarioAntes = new Concesionario($FromId, strtolower($ProductId));
        $Concesionario = new Concesionario();

        // Establece el estado del concesionario anterior como 'Inactivo'
        $ConcesionarioAntes->setEstado('I');

        // Configura los IDs y porcentajes del nuevo concesionario a partir del concesionario existente
        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($ProductId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId($_SESSION['usuario']);

        // Crea un DAO para gestionar operaciones con MySQL para el objeto Concesionario
        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioAntes->setUsumodifId($_SESSION['usuario']);

        // Inicia una transacción en el DAO
        $ConcesionarioMySqlDAO->getTransaction();
        $ConcesionarioMySqlDAO->update($ConcesionarioAntes);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        $ConcesionarioMySqlDAO->getTransaction()->commit();

    } catch (Exception $e) {
        // Verifica si el código de excepción es "48"
        if ($e->getCode() == "48") {
            $ConcesionarioU = new Concesionario($FromId, '0');
            $Concesionario = new Concesionario($FromId);

            // Establece los ID de usuario en la nueva instancia de Concesionario
            $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
            $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
            $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
            $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
            $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
            $Concesionario->setPorcenhijo($ComissionLevelBetShop);
            $Concesionario->setPorcenpadre1($ComissionLevel1);
            $Concesionario->setPorcenpadre2($ComissionLevel2);
            $Concesionario->setPorcenpadre3($ComissionLevel3);
            $Concesionario->setPorcenpadre4($ComissionLevel4);
            $Concesionario->setProdinternoId($ProductId);
            $Concesionario->setMandante(0);
            $Concesionario->setUsucreaId(0);
            $Concesionario->setUsumodifId(0);
            $Concesionario->setEstado('A');
            $Concesionario->setMandante(0);
            $Concesionario->setUsucreaId($_SESSION['usuario']);
            $Concesionario->setUsumodifId(0);

        // Crea una instancia del DAO para Concesionario
        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();

        // Inicia una transacción
        $ConcesionarioMySqlDAO->getTransaction();
        // Inserta el nuevo Concesionario en la base de datos
        $ConcesionarioMySqlDAO->insert($Concesionario);

            $ConcesionarioMySqlDAO->getTransaction()->commit();
        } else {
            // Lanza la excepción
            throw $e;

        }
    }

}


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];


$response["Data"] = array();
