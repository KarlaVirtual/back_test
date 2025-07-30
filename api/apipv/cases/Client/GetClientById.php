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
 * Obtener los detalles de un cliente por su ID.
 *
 * Este script consulta y devuelve información detallada sobre un cliente específico,
 * incluyendo datos personales, configuraciones y límites.
 *
 * @param object $params
 * @param int $params ->MaxRows Número máximo de filas a devolver.
 * @param int $params ->OrderedItem Orden de los elementos.
 * @param int $params ->SkeepRows Número de filas a omitir.
 *
 *
 * @return array $response
 *   - HasError: boolean Indica si ocurrió un error.
 *   - AlertType: string Tipo de alerta (por ejemplo, "success").
 *   - AlertMessage: string Mensaje de alerta.
 *   - ModelErrors: array Lista de errores del modelo (vacío si no hay errores).
 *   - Data: array Contiene los detalles del cliente.
 *
 * @throws Exception Si el usuario no tiene permisos para acceder a los datos del cliente.
 */


/* ejecuta un script PHP con parámetros desde una solicitud JSON y variables de sesión. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$id = $_GET["id"];


exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'CONSULTA GetClientById " . ' ID ' . $id . "  " . $_SESSION['usuario'] . "  " . $_SESSION["win_perfil"] . "  " . $_SESSION["nombre"] . "' '#virtualsoft-cron2' > /dev/null & ");


/* Declaración clase del entorno de ejecución */
$ConfigurationEnvironment = new ConfigurationEnvironment();

////
//$permission = $ConfigurationEnvironment->checkUserPermission('Jugadores Internos', 'customers', $_SESSION['win_perfil'], $_SESSION['usuario']);
//if(!$permission) throw new Exception('Permiso denegado', 5000);


if ($id != "") {

    $UsuarioPerfil = new UsuarioPerfil($id);



    // Verifica el ID del perfil del usuario y asigna permisos según corresponda.
    if($UsuarioPerfil->perfilId == 'USUONLINE'){

        // Comprueba si el usuario tiene permiso para obtener clientes por su ID.
        $permission = $ConfigurationEnvironment->checkUserPermission('Client/GetClientById', $_SESSION['win_perfil'], $_SESSION['usuario'], 'customers');

        // Lanza una excepción si no tiene permiso.
        if(!$permission) throw new Exception('Permiso denegado', 100035);

    } elseif (in_array($UsuarioPerfil->perfilId, array('PUNTOVENTA', 'CAJERO'))) {

        // Comprueba permiso para la gestión de puntos de venta y cajeros.
        $permission = $ConfigurationEnvironment->checkUserPermission('Client/GetClientById', $_SESSION['win_perfil'], $_SESSION['usuario'], 'betShopManagement');

        // Lanza una excepción si no tiene permiso.
        if(!$permission) throw new Exception('Permiso denegado', 100035);

    } elseif (in_array($UsuarioPerfil->perfilId, array('CONCESIONARIO', 'CONCESIONARIO2', 'AFILIADOR'))) {

        // Comprueba permiso para la gestión de concesionarios y afiliadores.
        $permission = $ConfigurationEnvironment->checkUserPermission('Client/GetClientById', $_SESSION['win_perfil'], $_SESSION['usuario'], 'agentListManagement');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    } else {

        // Comprueba permiso para la gestión de usuarios administradores.
        $permission = $ConfigurationEnvironment->checkUserPermission('Client/GetClientById', $_SESSION['win_perfil'], $_SESSION['usuario'], 'adminUserManagement');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    }
//
    // Si la variable Global de sesión es "N", se inicializa el objeto Mandante.
    if ($_SESSION["Global"] == "N") {
        if ($_SESSION["mandante"] == 0) {
            $_SESSION["mandante"] = '0';
        }
        $Mandante = new Mandante($_SESSION["mandante"]);

    }


    // Se asignan los parámetros de paginación.
    $MaxRows = $params->MaxRows; // Número máximo de filas a recuperar.
    $OrderedItem = $params->OrderedItem; // Ítem por el cual se ordenarán los resultados.
    $SkeepRows = $params->SkeepRows; // Número de filas a omitir en la consulta.

    // Si no se especifica el número de filas a omitir, se establece en 0.
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 10;
    }


    if ($Mandante->propio == "S" || $_SESSION["Global"] == "S") {

        /**
         * Inicializa un arreglo de reglas para filtrar usuarios.
         */
        $rules = [];
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $id, "op" => "eq"));



        // Verifica el perfil del usuario y agrega reglas según corresponda.
        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            array_push($rules, array("field" => "concesionario_punto.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
            array_push($rules, array("field" => "concesionario_punto.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
            array_push($rules, array("field" => "concesionario_punto.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        // Si el usuario está condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario está condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {

            // Agrega regla si hay una lista de mandantes disponibles.
            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        // Define el filtro como un arreglo con las reglas y operación de agrupamiento.
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);



        // Obtiene una lista de usuarios basados en las reglas y el filtro definido.
        $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario.verifdomicilio,data_completa2.ultimo_inicio_sesion,usuario.mandante,usuario_perfil.consulta_agente,usuario.retirado,usuario.verifdomicilio,registro.estado_valida,usuario.verifcedula_ant,usuario.observ,usuario.clave_tv,usuario.verifcedula_post,usuario.nombre,usuario.contingencia,usuario.contingencia_deportes,usuario.contingencia_casino,usuario.contingencia_casvivo,usuario.contingencia_virtuales,usuario.contingencia_poker,usuario.contingencia_retiro,usuario.contingencia_deposito,usuario.idioma,usuario.documento_validado,usuario.permite_enviopublicidad,usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.estado_valida,registro.ciudad_id,registro.nombre1,registro.nombre2,registro.apellido1,registro.afiliador_id,registro.apellido2,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,registro.tipo_doc,c.*,d.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base,usuario_perfil.*, usuario_puntoslealtad.puntos_lealtad, usuario_puntoslealtad.puntos_aexpirar, punto_venta.facebook,punto_venta.facebook_verificacion,punto_venta.instagram,punto_venta.instagram_verificacion,punto_venta.whatsApp,punto_venta.whatsApp_verificacion,punto_venta.otraredessociales,punto_venta.otraredessociales_verificacion,punto_venta.cedula,punto_venta.identificacion_ip,usuario.pago_comisiones, usuario.account_id_jumio,usuario.verif_celular, usuario.test ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        $ComentarioFinal = '';
        try {

            // Se intenta obtener la configuración del usuario y el valor correspondiente.
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($id);
            $valor = $UsuarioConfiguracion->valor;

            // Se verifica si hay fechas de inicio y fin disponibles, y se convierten a formato timestamp.
            if ($UsuarioConfiguracion->fechaInicio != "" and $UsuarioConfiguracion->fechaFin != ""){
                $fechaInicio = strtotime($UsuarioConfiguracion->fechaInicio);
                $fechaFin = strtotime($UsuarioConfiguracion->fechaFin);
            }

            // Se valida el valor y se obtiene el comentario final si el valor es 'A'.
            if ($valor == 'A') {
                $valor = 'A';
                $ComentarioFinal = $UsuarioConfiguracion->nota;
            }


        } catch (Exception $e) {
            // Se maneja la excepción en caso de que el código de error sea 46.
            if ($e->getCode() == 46) {
            }
        }
        try {

            // Se intenta obtener la configuración del usuario con un nuevo clasificador para fraude.
            $UsuarioConfiguracion = new UsuarioConfiguracion($id);
            $Clasificador = new Clasificador('', 'FRAUD');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $ClasificadorId);
            $valorFraud = $UsuarioConfiguracion->valor;
            if ($UsuarioConfiguracion->fechaInicio != "" && $UsuarioConfiguracion->fechaFin != "") {
                $fechaInicio = strtotime($UsuarioConfiguracion->fechaInicio);
                $fechaFin = strtotime($UsuarioConfiguracion->fechaFin);
            } else {
                $fechaInicio = "";
                $fechaFin = "";
            }

            // Se valida el valor relacionado con el fraude y se obtiene el comentario final si el valor es 'A'.
            if($valorFraud =='A'){
                $ComentarioFinal =$UsuarioConfiguracion->nota;
            }


        }catch (Exception $e){
            // Se maneja la excepción en caso de que el código de error sea 46, asignando el valor de fraude a 'I'.
            if($e->getCode() == 46){
                $valorFraud = "I";
            }
        }

        // nuevo bloque para consultar los comentarios pasados

        try {
            // Inicializa un arreglo para las reglas de filtrado
            $rules = [];

            array_push($rules, array("field" => "usuario_configuracion.usuario_id", "data" => $id, "op" => "eq"));
            array_push($rules, array("field" => "clasificador.tipo", "data" => "AB", "op" => "eq"));
            array_push($rules, array("field" => "usuario_configuracion.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "usuario_configuracion.valor", "data" => "I", "op" => "eq"));

            // Crea un filtro combinando las reglas y la operación entre grupos
            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $UsuarioConfiguracion = new UsuarioConfiguracion();

            // Obtiene configuraciones de usuario con filtros aplicados
            $data = $UsuarioConfiguracion->getUsuarioConfiguracionesCustom("usuario_configuracion.nota,usuario_configuracion.fecha_inicio,usuario_configuracion.fecha_fin,clasificador.tipo","usuario_configuracion.usuconfig_id","asc",0,20,$json,true);

            // Decodifica la respuesta JSON
            $data = json_decode($data);

            // Inicializa un arreglo para almacenar el historial de comentarios
            $CommentHistory = [];

            foreach ($data->data as $key => $value) {
                $array = [];
                $array["startDate"] = $value->{"usuario_configuracion.fecha_inicio"};
                $array["endDate"] = $value->{"usuario_configuracion.fecha_fin"};
                $array["CommentHistory"] = $value->{"usuario_configuracion.nota"};

                // Agrega el arreglo de comentario al historial
                array_push($CommentHistory, $array);
            }

        } catch (Exception $e) {

        }


        try {
            $Clasificador = new Clasificador('', 'RIDER');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $ClasificadorId);
            $valorRider = $UsuarioConfiguracion->valor;
            if ($UsuarioConfiguracion->fechaInicio != "" and $UsuarioConfiguracion->fechaFin != "") {
                $fechaInicio = strtotime($UsuarioConfiguracion->fechaInicio);
                $fechaFin = strtotime($UsuarioConfiguracion->fechaFin);
            } else {
                $fechaInicio = "";
                $fechaFin = "";
            }

            if ($valorRider == "A") {
                $ComentarioFinal = $UsuarioConfiguracion->nota;
            }

        } catch (Exception $e) {
            if ($e->getCode() == 46) {
                $valorRider = "I";
            }
        }

        try {
            $Clasificador = new Clasificador('', 'UNDERREVIEW');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $ClasificadorId);
            $valorUnderreview = $UsuarioConfiguracion->valor;
            if ($UsuarioConfiguracion->fechaInicio != "" && $UsuarioConfiguracion->fechaFin != "") {
                $fechaInicio = strtotime($UsuarioConfiguracion->fechaInicio);
                $fechaFin = strtotime($UsuarioConfiguracion->fechaFin);
            } else {
                $fechaInicio = "";
                $fechaFin = "";
            }

            if ($valorUnderreview == "A") {
                $ComentarioFinal = $UsuarioConfiguracion->nota;
            }

        } catch (Exception $e) {
            if ($e->getCode() == 46) {
                $valorUnderreview = "I";
            }
        }

        /**
         * Bloque de código que intenta obtener información de configuraciones de usuarios
         * relacionadas con "AGAINCHARGES" y "ACTIVE". Se maneja la fecha de inicio y fin,
         * así como la obtención de comentarios finales según los valores obtenidos.
         */
        try {
            $Clasificador = new Clasificador('', 'AGAINCHARGES');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $ClasificadorId);
            $valorAgaincharges = $UsuarioConfiguracion->valor;

            if ($UsuarioConfiguracion->fechaInicio != "" && $UsuarioConfiguracion->fechaFin != "") {
                $fechaInicio = strtotime($UsuarioConfiguracion->fechaInicio);
                $fechaFin = strtotime($UsuarioConfiguracion->fechaFin);
            } else {
                $fechaInicio = "";
                $fechaFin = "";
            }


            if ($valorAgaincharges == "A") {
                $ComentarioFinal = $UsuarioConfiguracion->nota;
            }

        } catch (Exception $e) {
            if ($e->getCode() == 46) {
                $valorAgaincharges = "I";
            }
        }

        /**
         * Bloque de código que intenta obtener información de configuraciones de usuarios
         * relacionadas con "ACTIVE". Se maneja la fecha de inicio y fin, así como la
         * obtención de comentarios finales según los valores obtenidos.
         */
        try {
            $Clasificador = new Clasificador('', 'ACTIVE');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $ClasificadorId);
            $ValorActive = $UsuarioConfiguracion->valor;
            if ($UsuarioConfiguracion->fechaInicio != "" and $UsuarioConfiguracion->fechaFin != "") {
                $fechaInicio = strtotime($UsuarioConfiguracion->fechaInicio);
                $fechaFin = strtotime($UsuarioConfiguracion->fechaFin);
            } else {
                $fechaInicio = "";
                $fechaFin = "";
            }


            if ($ValorActive == "A") {
                $ComentarioFinal = $UsuarioConfiguracion->nota;
            }


        } catch (Exception $e) {
            if ($e->getCode() == 46) {
                $ValorActive = "I";
            }
        }

        /**
         * Intenta obtener la configuración del usuario para la autoexclusión, fecha de expiración
         * y límites de juego en casino y casino en vivo mediante diferentes clasificadores.
         */
        try {
            $Clasificador = new Clasificador('', 'SELFEXCLUSION');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $ClasificadorId);
            $valorSelftExclusion = $UsuarioConfiguracion->valor;
            if ($UsuarioConfiguracion->fechaInicio != "" and $UsuarioConfiguracion->fechaFin) {
                $fechaInicio = strtotime($UsuarioConfiguracion->fechaInicio);
                $fechaFin = strtotime($UsuarioConfiguracion->fechaFin);
            } else {
                $fechaInicio = "";
                $fechaFin = "";
            }

            if ($valorSelftExclusion == "A") {
                $ComentarioFinal = $UsuarioConfiguracion->nota;
            }


        } catch (Exception $e) {
            if ($e->getCode() == 46) {
                $valorSelftExclusion = "I";
            }
        }


        try {
            $Clasificador = new Clasificador('', 'EXPIRYDATE');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $ClasificadorId);
            $valorExpiryDate = $UsuarioConfiguracion->valor;

        } catch (Exception $e) {
        }
        try {
            $Clasificador = new Clasificador('', 'LIMITPERCLIENTTEST');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 3);
            $LimitCasino = $UsuarioConfiguracion->getValor();
        } catch (Exception $e) {

        }
        try {
            $Clasificador = new Clasificador('', 'LIMITPERCLIENTTEST');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 2);
            $LimitLiveCasino = $UsuarioConfiguracion->getValor();
        } catch (Exception $e) {

        }
        try {
            $Clasificador = new Clasificador('', 'LIMITPERCLIENTTEST');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 0);
            $LimitSportbook = $UsuarioConfiguracion->getValor();
        } catch (Exception $e) {

        }
        try {
            $Clasificador = new Clasificador('', 'LIMITPERCLIENTTEST');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 1);
            $LimitVirtuals = $UsuarioConfiguracion->getValor();
        } catch (Exception $e) {

        }

        try {
            $Clasificador = new Clasificador('', 'LIMITPERCLIENTTEST');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 4);
            $LimitDeposits = $UsuarioConfiguracion->getValor();
        } catch (Exception $e) {

        }
        try {
            $Clasificador = new Clasificador('', 'LIMITPERCLIENTTEST');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 5);
            $LimitWithdrawals = $UsuarioConfiguracion->getValor();
        } catch (Exception $e) {

        }
        try {
            $Clasificador = new Clasificador('', 'DAYLILIMITPV');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 0);
            $IsActivateWithdrawalLimit = $UsuarioConfiguracion->getEstado();
            $WithdrawalLimit = $UsuarioConfiguracion->getValor();
        } catch (Exception $e) {

        }
        try {
            $Clasificador = new Clasificador('', 'DAYLILIMITPV');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 1);
            $IsActivateDepositLimit = $UsuarioConfiguracion->getEstado();
            $DepositLimit = $UsuarioConfiguracion->getValor();
        } catch (Exception $e) {

        }
        try {
            $Clasificador = new Clasificador('', 'CONTINGENCIARETIROSRETAIL');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId());
            $IsActivateContingencyRetailWithdrawals = $UsuarioConfiguracion->getEstado();
        } catch (Exception $e) {
            $IsActivateContingencyRetailWithdrawals = "I";
        }



        /**
         * Verifica el estado de la contingencia para depósitos en pasarelas de pago.
         *
         * Este bloque de código intenta obtener el estado de la contingencia para depósitos
         * en pasarelas de pago del usuario. Si ocurre una excepción, se establece el estado como inactivo ("I").
         */

        try {
            $Clasificador = new Clasificador('', 'PAYMENTGATEWAYCONTINGENCY');
            $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId());
            $IsActivateContingencyDepositUsuOnline = $UsuarioConfiguracion->getEstado();
        } catch (Exception $e) {
            $IsActivateContingencyDepositUsuOnline = "I";
        }




        /**
         * Gestiona la contingencia de puntos de venta para un usuario.
         *
         * Este bloque de código permite habilitar o deshabilitar la contingencia de puntos de venta
         * para un usuario específico. Se actualiza o inserta la configuración del usuario en la base de datos
         * y se registra una auditoría de la acción realizada.
         */

          try {
              $Clasificador = new Clasificador('', 'CONTINGENCYRETAIL');
              $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId());
              $IsActivateContingencyDepositRetail = $UsuarioConfiguracion->getEstado();
          } catch (Exception $e) {
              $IsActivateContingencyDepositRetail = "I";
          }






        foreach ($usuarios->data as $key => $value) {

            $array = [];
            if ($value->{"usuario_perfil.perfil_id"} != "USUONLINE") {
                /**
                 * Asignación de valores del objeto $value al array $array.
                 * Se están extrayendo datos relacionados con el usuario y su perfil.
                 */
                $array["Id"] = $value->{"usuario.usuario_id"};
                $array["id"] = $value->{"usuario.usuario_id"};
                $array["ip"] = $value->{"usuario.dir_ip"};
                $array["LastIPaddress"] = $value->{"usuario.dir_ip"};
                $array["Login"] = $value->{"usuario.login"};
                $array["Estado"] = array($value->{"usuario.estado"});
                $array["State"] = array($value->{"usuario.estado"});
                $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
                $array["Idioma"] = $value->{"a.idioma"};
                $array["PreferredLanguage"] = $value->{"usuario.idioma"};
                $array["Name"] = $value->{"usuario.nombre"};
                $array["FirstName"] = $value->{"registro.nombre1"};
                $array["MiddleName"] = $value->{"registro.nombre2"};
                $array["LastName"] = $value->{"registro.apellido1"};
                $array["Affiliate"] = $value->{"registro.afiliador_id"};
                $array["SecondLastName"] = $value->{"registro.apellido2"};
                $array["Email"] = $value->{"punto_venta.email"};
                $array["Address"] = $value->{"registro.direccion"};
                $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
                $array["Intentos"] = $value->{"usuario.intentos"};
                $array["Observaciones"] = $value->{"usuario.observ"};
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["LoyaltyPoints"] = intval($value->{"usuario_puntoslealtad.puntos_lealtad"} + $value->{"usuario_puntoslealtad.puntos_aexpirar"});


                $UsuarioConfig = new UsuarioConfig($value->{"usuario.usuario_id"});

                $array["UserProfile"] = $value->{"usuario_perfil.perfil_id"};
                $array["LastLoginDate"] = $value->{"data_completa2.ultimo_inicio_sesion"};
                $array["LastPasswordChangeDate"] = $UsuarioConfig->getFechaModifPassword();
                $array["AccountActivatedDate"] = $UsuarioConfig->getFechaModifEstadoUsuario();

                $array["Type"] = "";

                if ($value->{"usuario_perfil.perfil_id"} == "AFILIADOR") {
                    $array["Type"] = "1";
                } elseif (strpos($value->{"usuario_perfil.perfil_id"}, "CONCESIONARIO")) {
                    $array["Type"] = "0";
                }


                $array["Pais"] = $value->{"usuario.pais_id"};
                $array["City"] = $value->{"g.ciudad_nom"};

                $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

                $array["IsLocked"] = false;
                $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
                $array["BirthDate"] = $value->{"c.fecha_nacim"};
                $array["UserTestProvider"] = $value->{"c.tipo_cuenta"};

                $array["BirthDepartment"] = $value->{"g.depto_id"};
                $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
                $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
                $array["Balance"] = ((round($value->{"registro.creditos"} + $value->{"registro.creditos_base"}, 2)));

                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["DocNumber"] = $value->{"registro.cedula"};
                $array["Gender"] = $value->{"registro.sexo"};
                $array["Language"] = $value->{"usuario.idioma"};
                $array["MobilePhone"] = $value->{"registro.celular"};
                $array["LastLoginLocalDate"] = $value->{"data_completa2.ultimo_inicio_sesion"};
                $array["Province"] = $value->{"registro.ciudad_id"};
                $array["RegionId"] = $value->{"usuario.pais_id"};
                $array["CountryId"] = $value->{"usuario.pais_id"};
                $array["CountryName"] = $value->{"usuario.pais_id"};
                $array["ZipCode"] = $value->{"registro.codigo_postal"};
                $array["IsVerified"] = true;
                $array["IsActivate"] = ($value->{"usuario.estado"});
                $array["IsRetired"] = (($value->{"usuario.retirado"}) == 'S' ? 'A' : 'N');
                $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});
                $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});

                $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};
                $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
                $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);


                $array["ContactName"] = ($value->{"punto_venta.nombre_contacto"});
                $array["Description"] = $value->{"punto_venta.descripcion"};
                $array["Phone"] = $value->{"punto_venta.telefono"};
                $array["RegionId"] = $value->{"punto_venta.telefono"};
                $array["Concessionaire"] = 0;

                $array["IsActivateContingency"] = ($value->{"usuario.contingencia"});
                $array["IsActivateContingencyDeportivas"] = ($value->{"usuario.contingencia_deportes"});
                $array["IsActivateContingencyCasino"] = ($value->{"usuario.contingencia_casino"});
                $array["IsActivateContingencyCasinoVivo"] = ($value->{"usuario.contingencia_casvivo"});
                $array["IsActivateContingencyVirtuales"] = ($value->{"usuario.contingencia_virtuales"});
                $array["IsActivateContingencyPoker"] = ($value->{"usuario.contingencia_poker"});
                $array["IsActivateContingencyRetirosRetail"] = $IsActivateContingencyRetailWithdrawals; // Contingencia de retiros retail
                $array["IsActivateRegistroUsuario"] = ($value->{"registro.estado_valida"});
                $array["IsActivateContingencyDepositUsuOnline"] = $IsActivateContingencyDepositUsuOnline;
                $array["IsActivateContingencyDepositRetail"] = $IsActivateContingencyDepositRetail;

                $array["IsActivateContingencyWithdrawal"] = ($value->{"usuario.contingencia_retiro"});
                $array["IsActivateContingencyDeposit"] = ($value->{"usuario.contingencia_deposito"});


                $array["Id"] = $value->{"usuario.usuario_id"};
                $array["id"] = $value->{"usuario.usuario_id"};
                $array["ip"] = $value->{"usuario.dir_ip"};
                $array["Login"] = $value->{"usuario.login"};
                $array["Estado"] = array($value->{"usuario.estado"});
                $array["State"] = array($value->{"usuario.estado"});
                $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
                $array["Idioma"] = $value->{"a.idioma"};
                $array["PreferredLanguage"] = $value->{"usuario.idioma"};
                $array["Name"] = $value->{"usuario.nombre"};
                $array["FirstName"] = $value->{"registro.nombre1"};
                $array["MiddleName"] = $value->{"registro.nombre2"};
                $array["LastName"] = $value->{"registro.apellido1"};
                $array["Affiliate"] = $value->{"registro.afiliador_id"};
                $array["Email"] = $value->{"punto_venta.email"};
                $array["Address"] = $value->{"punto_venta.direccion"};
                $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
                $array["Intentos"] = $value->{"usuario.intentos"};
                $array["Observaciones"] = $value->{"usuario.observ"};
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["Type"] = $value->{"usuario_perfil.perfil_id"};

                if ($value->{"usuario_perfil.perfil_id"} == "AFILIADOR") {
                    $array["Type"] = 1;
                } elseif (strpos($value->{"usuario_perfil.perfil_id"}, "CONCESIONARIO") !== FALSE) {
                    $array["Type"] = 0;
                }

                // Asignación de propiedades de un objeto a un array asociativo
                $array["Pais"] = $value->{"usuario.pais_id"};
                $array["City"] = $value->{"g.ciudad_nom"};

                $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

                $array["IsLocked"] = false;
                $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
                $array["BirthDate"] = $value->{"c.fecha_nacim"};
                $array["UserTestProvider"] = $value->{"c.tipo_cuenta"};

                $array["BirthDepartment"] = $value->{"g.depto_id"};
                $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
                $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
                $array["Balance"] = ((round($value->{"registro.creditos"} + $value->{"registro.creditos_base"}, 2)));

                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["DocNumber"] = $value->{"registro.cedula"};
                $array["Gender"] = $value->{"registro.sexo"};
                $array["Language"] = $value->{"usuario.idioma"};
                $array["MobilePhone"] = $value->{"registro.celular"};
                $array["LastLoginLocalDate"] = $value->{"data_completa2.ultimo_inicio_sesion"};
                $array["Province"] = $value->{"registro.ciudad_id"};
                $array["RegionId"] = $value->{"usuario.pais_id"};
                $array["CountryId"] = $value->{"usuario.pais_id"};
                $array["CountryName"] = $value->{"usuario.pais_id"};
                $array["ZipCode"] = $value->{"registro.codigo_postal"};
                $array["IsVerified"] = true;
                $array["IsActivate"] = ($value->{"usuario.estado"});
                $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});
                $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});

                $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};
                $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
                $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);


                $array["ContactName"] = ($value->{"punto_venta.nombre_contacto"});
                $array["Description"] = $value->{"punto_venta.descripcion"};
                $array["Phone"] = $value->{"punto_venta.telefono"};
                $array["RegionId"] = $value->{"departamento.depto_id"};
                $array["RegionId"] = $value->{"ciudad.depto_id"};
                $array["City"] = $value->{"ciudad.ciudad_id"};
                $array["CityId"] = $value->{"ciudad.ciudad_id"};
                $array["IsActivateAdvertising"] = $value->{"usuario.permite_enviopublicidad"};
                $array["Pinagent"] = $value->{"usuario_config.pinagent"};
                $array["Lockedsales"] = $value->{"usuario.bloqueo_ventas"};
                $array["PrintReceiptBox"] = $value->{"usuario_config.recibo_caja"};
                $array["AllowsRecharges"] = $value->{"usuario_config.permite_recarga"};
                $array["ActivateRegistration"] = $value->{"usuario.permite_activareg"};
                $array["District"] = $value->{"punto_venta.barrio"};
                $array["LastModifiedUser"] = $value->{"usuario.usumodif_id"};
                $array["LastIPaddress"] = $value->{"usuario.dir_ip"};
                $array["IP"] = $value->{"usuario.usuario_ip"};
                $array["IsRestrictionIP"] = $value->{"usuario.restriccion_ip"};
                $array["IsTokenGoogle"] = $value->{"usuario.token_google"};
                $array["IsTokenLocal"] = $value->{"usuario.token_local"};
                $array["AllowDeposits"] = ($value->{"usuario_config.permite_recarga"} == "S") ? "A" : "I";
                $array["Longitud"] = $value->{"usuario.ubicacion_longitud"};
                $array["Latitud"] = $value->{"usuario.ubicacion_latitud"};
                $array["UserCountry"] = $value->{"usuario_perfil.pais"};
                $array["UserGlobal"] = $value->{"usuario_perfil.global"};

                $array["CustomCostCenter"] = ($value->{"punto_venta.codigo_personalizado"});
                $array["Account"] = ($value->{"punto_venta.cuentacontable_id"});
                $array["AccountClose"] = ($value->{"punto_venta.cuentacontablecierre_id"});

                $array["HeaderPrintPrizePaymentReceipt"] = ($value->{"punto_venta.header_recibopagopremio"});
                $array["FooterPrintPrizePaymentReceipt"] = ($value->{"punto_venta.footer_recibopagopremio"});
                $array["HeaderPrintRetirementPaymentReceipt"] = ($value->{"punto_venta.header_recibopagoretiro"});
                $array["FooterPrintRetirementPaymentReceipt"] = ($value->{"punto_venta.footer_recibopagoretiro"});
                $array["BetShopType"] = ($value->{"punto_venta.tipo_tienda"});

                $array["PrizePaymentTa"] = ($value->{"punto_venta.impuesto_pagopremio"});


                $array["MaximumWithdrawalAmount"] = $value->{"usuario_config.maxpago_retiro"};
                $array["MaximumPrizePaymentAmount"] = $value->{"usuario_config.maxpago_premio"};
                $array["AgentId"] = $value->{"usuario_perfil.consulta_agente"};
                $array["RegionPerfil"] = $value->{"usuario_perfil.region"};


                if ($value->{"concesionario.usupadre_id"} != "") {
                    $array["Concessionaire"] = $value->{"concesionario.usupadre_id"};
                }

                if ($value->{"concesionario.usupadre2_id"} != "") {
                    $array["Subconcessionaire"] = $value->{"concesionario.usupadre2_id"};
                }
                $array["Partners"] = ($value->{"usuario_perfil.mandante_lista"});
                $array["Partner"] = ($value->{"usuario.mandante"});

                $array["Info1"] = ($value->{"c.info1"});
                $array["Info2"] = ($value->{"c.info2"});
                $array["Info3"] = ($value->{"c.info3"});
                $array["Note"] = ($value->{"usuario.observ"});
                $array["Categorization"] = ($value->{"usuario.clave_tv"});

                $array["DragNegatives"] = ($value->{"usuario.arrastra_negativo"} == '1') ? 'S' : 'N';

                $array["Document"] = $value->{"punto_venta.cedula"};
                switch ($value->{"punto_venta.identificacion_ip"}) {
                    case '0':
                        $IPIdentification = "N";
                        break;
                    case '1':
                        $IPIdentification = "S";
                        break;
                }

                /**
                 * Asigna valores de identificación y redes sociales a un array.
                 * Incluye la identificación de IP, enlaces a Facebook, Instagram, WhatsApp y otras redes sociales,
                 * así como sus respectivos estados de verificación.
                 */

                $array["IPIdentification"] = $IPIdentification;

                $array["Facebook"] = $value->{"punto_venta.facebook"};
                switch ($value->{"punto_venta.facebook_verificacion"}) {
                    case '0':
                        $FacebookVerification = "N";
                        break;
                    case '1':
                        $FacebookVerification = "S";
                        break;
                }
                $array["FacebookVerification"] = $FacebookVerification;

                $array["Instagram"] = $value->{"punto_venta.instagram"};
                switch ($value->{"punto_venta.instagram_verificacion"}) {
                    case '0':
                        $InstagramVerification = "N";
                        break;
                    case '1':
                        $InstagramVerification = "S";
                        break;
                }
                $array["InstagramVerification"] = $InstagramVerification;

                $array["WhatsApp"] = $value->{"punto_venta.whatsApp"};
                switch ($value->{"punto_venta.whatsApp_verificacion"}) {
                    case '0':
                        $WhatsAppVerification = "N";
                        break;
                    case '1':
                        $WhatsAppVerification = "S";
                        break;
                }
                $array["WhatsAppVerification"] = $WhatsAppVerification;

                $array["OtherSocialMedia"] = $value->{"punto_venta.otraredessociales"};
                switch ($value->{"punto_venta.otraredessociales_verificacion"}) {
                    case '0':
                        $OtherSocialMediaVerification = "N";
                        break;
                    case '1':
                        $OtherSocialMediaVerification = "S";
                        break;
                }

                /**
                 * Se asignan varios valores a un arreglo que se utiliza para almacenar
                 * información relacionada con el usuario y sus configuraciones de
                 * consentimiento y límites de operaciones.
                 */
                $array["OtherSocialMediaVerification"] = $OtherSocialMediaVerification;
                $array["ComissionsPayment"] = $value->{"usuario.pago_comisiones"};
                $array["IsActivateAllowSendSms"] = $value->{"usuario_perfil.consentimiento_sms"} === 'S' ? 'A' : 'I';
                $array["IsActivateAllowSendEmail"] = $value->{"usuario_perfil.consentimiento_email"} === 'S' ? 'A' : 'I';
                $array["IsActivateAllowSendPhone"] = $value->{"usuario_perfil.consentimiento_telefono"} === 'S' ? 'A' : 'I';
                $array["IsActivateAllowSendPush"] = $value->{"usuario_perfil.consentimiento_push"} === 'S' ? 'A' : 'I';
                $array["Comment"] = $ComentarioFinal;
                $array["CommentHistory"] = $HistoryComment;
                $array["IsActivatePhoneVerification"] = $value->{"usuario.verif_celular"} == 'S' ? 'A' : 'I';
                $array["IsUserTest"] = $value->{'usuario.test'} != '' ? $value->{'usuario.test'} : 'N';
                $array["LimitCasino"] = $LimitCasino;
                $array["LimitLiveCasino"] = $LimitLiveCasino;
                $array["LimitSportbook"] = $LimitSportbook;
                $array["LimitVirtuals"] = $LimitVirtuals;
                $array["LimitDeposits"] = $LimitDeposits;
                $array["LimitWithdrawals"] = $LimitWithdrawals;
                $array["IsActivateWithdrawalLimit"] = $IsActivateWithdrawalLimit;
                $array["WithdrawalLimit"] = $WithdrawalLimit;
                $array["IsActivateDepositLimit"] = $IsActivateDepositLimit;
                $array["DepositLimit"] = $DepositLimit;
            } else {
                $array["LimitCasino"] = $LimitCasino;
                $array["LimitLiveCasino"] = $LimitLiveCasino;
                $array["LimitSportbook"] = $LimitSportbook;
                $array["LimitVirtuals"] = $LimitVirtuals;
                $array["LimitDeposits"] = $LimitDeposits;
                $array["LimitWithdrawals"] = $LimitWithdrawals;
                $array["ExpiryDate"] = $valorExpiryDate;
                $array["Id"] = $value->{"usuario.usuario_id"};
                $array["id"] = $value->{"usuario.usuario_id"};
                $array["ip"] = $value->{"usuario.dir_ip"};
                $array["LastIPaddress"] = $value->{"usuario.dir_ip"};
                $array["Login"] = $value->{"usuario.login"};
                $array["Estado"] = array($value->{"usuario.estado"});
                $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
                $array["AccountIdJumio"] = $value->{"usuario.account_id_jumio"};
                $array["Idioma"] = $value->{"a.idioma"};
                $array["Nombre"] = $value->{"a.nombre"};
                $array["FirstName"] = $value->{"registro.nombre1"};
                $array["MiddleName"] = $value->{"registro.nombre2"};
                $array["LastName"] = $value->{"registro.apellido1"};
                $array["SecondLastName"] = $value->{"registro.apellido2"};
                $array["Email"] = $value->{"registro.email"};
                $array["Address"] = $value->{"registro.direccion"};
                $array["Affiliate"] = $value->{"registro.afiliador_id"};
                $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
                $array["Intentos"] = $value->{"usuario.intentos"};
                $array["Observaciones"] = $value->{"usuario.observ"};
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["LoyaltyPoints"] = intval($value->{"usuario_puntoslealtad.puntos_lealtad"} + $value->{"usuario_puntoslealtad.puntos_aexpirar"});
                $array["Pais"] = $value->{"usuario.pais_id"};
                $array["City"] = $value->{"g.ciudad_nom"};
                $array["IsActivateAdvertising"] = $value->{"usuario.permite_enviopublicidad"};

                $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

                $array["IsLocked"] = false;
                $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
                $array["BirthDate"] = $value->{"c.fecha_nacim"};
                $array["UserTestProvider"] = $value->{"c.tipo_cuenta"};

                $array["BirthDepartment"] = $value->{"g.depto_id"};
                $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
                $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
                $array["Balance"] = ((round($value->{"registro.creditos"} + $value->{"registro.creditos_base"}, 2)));


                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["Currency"] = $value->{"usuario.moneda"};
                $array["DocNumber"] = $value->{"registro.cedula"};
                $array["Gender"] = $value->{"registro.sexo"};
                $array["Language"] = $value->{"usuario.idioma"};
                $array["Phone"] = $value->{"registro.telefono"};
                $array["MobilePhone"] = $value->{"registro.celular"};
                $array["LastLoginLocalDate"] = $value->{"data_completa2.ultimo_inicio_sesion"};
                $array["RegionId"] = $value->{"ciudad.depto_id"};
                $array["Province"] = $value->{"registro.ciudad_id"};
                $array["CityId"] = $value->{"registro.ciudad_id"};
                //$array["RegionId"] = $value->{"usuario.pais_id"};
                $array["CountryId"] = $value->{"usuario.pais_id"};
                $array["RegionId"] = $value->{"d.depto_id"};
                $array["DocumentType"] = ($value->{"registro.tipo_doc"});

                $array["CountryName"] = $value->{"usuario.pais_id"};
                $array["ZipCode"] = $value->{"registro.codigo_postal"};
                $array["IsVerified"] = true;
                $array["IsActivate"] = ($value->{"usuario.estado"});
                $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});
                $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});

                $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};
                $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
                $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);


                $array["CreatedLocalDate"] = ($value->{"usuario.fecha_crea"});

                $array["IsActivateContingency"] = ($value->{"usuario.contingencia"});
                $array["IsActivateContingencyDeportivas"] = ($value->{"usuario.contingencia_deportes"});
                $array["IsActivateContingencyCasino"] = ($value->{"usuario.contingencia_casino"});
                $array["IsActivateContingencyCasinoVivo"] = ($value->{"usuario.contingencia_casvivo"});
                $array["IsActivateContingencyVirtuales"] = ($value->{"usuario.contingencia_virtuales"});
                $array["IsActivateContingencyPoker"] = ($value->{"usuario.contingencia_poker"});
                $array["IsActivateContingencyRetirosRetail"] = $IsActivateContingencyRetailWithdrawals; // Contingencia de retiros retail
                $array["IsActivateRegistroUsuario"] = ($value->{"registro.estado_valida"});
                $array["IsActivateContingencyDepositUsuOnline"] = $IsActivateContingencyDepositUsuOnline;
                $array["IsActivateContingencyDepositRetail"] = $IsActivateContingencyDepositRetail;

                $array["IsActivateContingencyWithdrawal"] = ($value->{"usuario.contingencia_retiro"});
                $array["IsActivateContingencyDeposit"] = ($value->{"usuario.contingencia_deposito"});
                $array["IsActivateDniAnterior"] = "I";
                $array["IsActivateDniPosterior"] = "I";

                $array["MinimumBet"] = ($value->{"f.apuesta_min"});
                $array["DailyLimit"] = ($value->{"f.valor_diario"});

                $array["DailyQuotaReloads"] = ($value->{"punto_venta.cupo_recarga"});
                $array["Concessionaire"] = ($value->{"concesionario_punto.usupadre1_id"});
                $array["Subconcessionaire"] = ($value->{"concesionario_punto.usupadre2_id"});

                //Información del usuario en el programa de referidos
                $UsuarioOtraInfo = new UsuarioOtrainfo($id);
                $PaisMandante = new PaisMandante('', $value->{'usuario.mandante'}, $value->{'usuario.pais_id'});
                $RefersLink = '';
                try {
                    if ($UsuarioOtraInfo->getReferenteAvalado() == 1) {
                        $RefersLink = $PaisMandante->getUrlReferentePersonalizado($UsuarioOtraInfo);
                    } else {
                        $RefersLink = 'No aplica';
                    }
                } catch (Exception $e) {
                    /**
                     * Captura de excepciones que ocurren en el bloque de código.
                     * Si el código de la excepción es diferente de 34, se vuelve a lanzar la excepción.
                     * De lo contrario, se define una URL predeterminada para el enlace de referencia.
                     *
                     * @param Exception $e La excepción que se está manejando.
                     */
                    if ($e->getCode() != 34) throw $e;
                    $RefersLink = 'URL no definida en el PaisMandate';
                }
                $array['RefersLink'] = $RefersLink;
                $array["UsuidReferent"] = $value->{'c.usuid_referente'} ?? 0;


                $array["StateDniFront"] = ($value->{"usuario.verifcedula_ant"});
                $array["StateDniBack"] = ($value->{"usuario.verifcedula_post"});
                $array["StateAddress"] = ($value->{"usuario.verifdomicilio"});

                if ($value->{"usuario.verifcedula_ant"} == "S") {
                    $array["IsActivateDniAnterior"] = "A";


                    /**
                     * Genera el nombre de archivo para la imagen del DNI anterior
                     * utilizando el ID de usuario y lo encripta para su acceso.
                     */
                    $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('c'.$value->{"usuario.usuario_id"} . 'A' . '.png');
                    $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('c'.$value->{"usuario.usuario_id"} . 'A' . '.png');

                    $array["DNIA"] = $filename;
                }
                if ($value->{"usuario.verifcedula_post"} == "S") {
                    $array["IsActivateDniPosterior"] = "A";

                    $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'P' . '.png');
                    $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'P' . '.png');

                    $array["DNIP"] = $filename;
                }


                if ($value->{"usuario.verifdomicilio"} == "S") {
                    $array["IsActivateDNIN"] = "A";

                    $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('v' . $value->{"usuario.usuario_id"} . '.png');
                    $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('v' . $value->{"usuario.usuario_id"} . '.png');

                    $array["DNIN"] = $filename;
                }

                $array["Partners"] = ($value->{"usuario_perfil.mandante_lista"});

                $array["Partner"] = ($value->{"usuario.mandante"});
                $array["Info1"] = ($value->{"c.info1"});
                $array["Info2"] = ($value->{"c.info2"});
                $array["Info3"] = ($value->{"c.info3"});
                $array["Note"] = ($value->{"usuario.observ"});
                /**
                 * Se evalúa el valor de la clave de TV del usuario y se asigna un estado de riesgo correspondiente.
                 * Se utilizan tres estados posibles: B (bajo), M (medio) y A (alto).
                 */
                switch ($value->{"usuario.clave_tv"}){
                    case 1:
                        $array["RiskStatus"] = "B";
                        break;
                    case 2:
                        $array["RiskStatus"] = "M";
                        break;
                    case 3:
                        $array["RiskStatus"] = "A";
                        break;
                    default:
                        $array["RiskStatus"] = "";
                        break;

                }
                /**
                 * Se asigna la clave de TV del usuario a la categoría.
                 */
                $array["Categorization"] = ($value->{"usuario.clave_tv"});
                $array['IsActivateSelfie'] = $value->{'usuario.veriffoto_usuario'} === 'S' ? 'A' : 'I';
                $array["IsActivateAllowSendSms"] = $value->{"usuario_perfil.consentimiento_sms"} === 'S' ? 'A' : 'I';
                $array["IsActivateAllowSendEmail"] = $value->{"usuario_perfil.consentimiento_email"} === 'S' ? 'A' : 'I';
                $array["IsActivateAllowSendPhone"] = $value->{"usuario_perfil.consentimiento_telefono"} === 'S' ? 'A' : 'I';
                $array["IsActivateAllowSendPush"] = $value->{"usuario_perfil.consentimiento_push"} === 'S' ? 'A' : 'I';
                $array["Comment"] = $ComentarioFinal;
                $array["CommentHistory"] = $CommentHistory;

                $array["IsActivatePhoneVerification"] = $value->{"usuario.verif_celular"} == 'S' ? 'A' : 'I';
                $array["IsUserTest"] = $value->{'usuario.test'} != '' ? $value->{'usuario.test'} : 'N';
            }

            $array["Comment"] = $ComentarioFinal;


            if ($valor == 'A') {
                $array['IsActivateAbusadorBonos'] = 'A';

            } else {
                $array['IsActivateAbusadorBonos'] = 'I';

            }
            if ($valorFraud == "" || $valorFraud == "NULL") {
                $array['IsActivateFraud'] = "I";
            } else {
                $array['IsActivateFraud'] = $valorFraud;
                $array["startDate"] = $fechaInicio ?? '';
                $array["endDate"] = $fechaFin ?? '';
            }

            if ($valorAgaincharges == "" || $valorAgaincharges == "NULL") {
                $array['IsActivateAgainCharges'] = "I";
            } else {
                $array['IsActivateAgainCharges'] = $valorAgaincharges;
                $array["startDate"] = $fechaInicio ?? '';
                $array["endDate"] = $fechaFin ?? '';
            }

            // Se verifica el valor de revisión para determinar la activación.
            if ($valorUnderreview == "" || $valorUnderreview == "NULL") {
                $array['IsActivateUnderReview'] = "I";
            } else {
                $array['IsActivateUnderReview'] = $valorUnderreview;
                $array["startDate"] = $fechaInicio ?? '';
                $array["endDate"] = $fechaFin ?? '';
            }

            // Se verifica el valor del conductor para determinar la activación.
            if ($valorRider == "" || $valorRider == "NULL") {
                $array['IsActivateRider'] = "I";
            } else {
                $array['IsActivateRider'] = $valorRider;
                $array["startDate"] = $fechaInicio ?? '';
                $array["endDate"] = $fechaFin ?? '';
            }

            // Se verifica el valor de autoexclusión para determinar la activación.
            if ($valorSelftExclusion == "" || $valorSelftExclusion == "NULL") {
                $array['IsActivateSelftExclusion'] = "I";
            } else {
                $array['IsActivateSelftExclusion'] = $valorSelftExclusion;
                $array["startDate"] = $fechaInicio ?? '';
                $array["endDate"] = $fechaFin ?? '';
            }


            if (($valor == "I" || $valor == "") && ($valorFraud == "I" || $valorFraud == "") && ($valorRider == "I" || $valorRider == "") && ($valorUnderreview == "I" || $valorUnderreview == "") && ($valorAgaincharges == "I" || $valorAgaincharges == "") && ($valorSelftExclusion == "I" || $valorSelftExclusion == "")) {
                $array['IsActivateActive'] = "A";
            } else {
                $array['IsActivateActive'] = $ValorActive;
                $array["startDate"] = $fechaInicio ?? '';
                $array["endDate"] = $fechaFin ?? '';
            }


            // Crea un nuevo objeto Clasificador con tipo 'INDIVIDUALGGR'
            $clasificador = new Clasificador('','INDIVIDUALGGR');
            $tipo = $clasificador->clasificadorId;
            $UsuarioConfiguracionMySqlDAo = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAo = $UsuarioConfiguracionMySqlDAo->queryByUsuarioIdAndTipo($id, $tipo);
            if ($UsuarioConfiguracionMySqlDAo) {
                $array["IndividualCalculation"] = ($UsuarioConfiguracionMySqlDAo[0]->{"estado"} === 'A') ? 'S' : 'N';
            } else {
                $array["IndividualCalculation"] = 'N';
            }

            // Asigna el arreglo final
            $usuariosFinal = $array;

        }
    } else {

        $UsuarioMandante = new UsuarioMandante();
        $rules = [];

        array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$id", "op" => "in"));

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }

        array_push($rules, array("field" => "usuario_mandante.propio", "data" => "N", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $usuarios = $UsuarioMandante->getUsuariosMandantesCustom(" usuario_mandante.* ", "usuario_mandante.usumandante_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];


        foreach ($usuarios->data as $key => $value) {

            $Islocked = false;

            $array = [];

            $array["id"] = $value->{"usuario_mandante.usuario_mandante"};
            $array["Id"] = $value->{"usuario_mandante.usuario_mandante"};
            $array["Ip"] = $value->{"usuario.dir_ip"};
            //$array["Login"] = $value->{"usuario.login"};
            // $array["Estado"] = array($value->{"usuario.estado"});
            // $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
            //  $array["Idioma"] = $value->{"a.idioma"};
            $array["Name"] = $value->{"usuario_mandante.nombres"};
            $array["FirstName"] = $value->{"usuario_mandante.nombres"};
            // $array["MiddleName"] = $value->{"registro.nombre2"};
            $array["LastName"] = $value->{"usuario_mandante.apellidos"};
            $array["Email"] = $value->{"usuario_mandante.email"};
            $array["Currency"] = $value->{"usuario_mandante.moneda"};
            $array["CreatedLocalDate"] = $value->{"usuario_mandante.fecha_crea"};


            $clasificador = new Clasificador('', 'INDIVIDUALGGR');
            $tipo = $clasificador->clasificadorId;
            $UsuarioConfiguracionMySqlDAo = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAo = $UsuarioConfiguracionMySqlDAo->queryByUsuarioIdAndTipo($id, $tipo);
            if ($UsuarioConfiguracionMySqlDAo) {
                $array["IndividualCalculation"] = ($UsuarioConfiguracionMySqlDAo[0]->{"estado"} === 'A') ? 'S' : 'N';
            } else {
                $array["IndividualCalculation"] = 'N';
            }

            $usuariosFinal = $array;

        }

    }


    if ($usuariosFinal) {

        $response["HasError"] = false;  // Indica que no hay errores en la respuesta.
        $response["AlertType"] = "success";  // Tipo de alerta que se envía, en este caso 'éxito'.
        $response["AlertMessage"] = "";  // Mensaje de alerta que se puede enviar al cliente.
        $response["ModelErrors"] = [];  // Errores del modelo, en este caso, una matriz vacía.

        $response = [$usuariosFinal];  // Asigna la lista de usuarios finales a la respuesta.

    }
}
