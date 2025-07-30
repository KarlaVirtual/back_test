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
 * Obtener los detalles de la cuenta de un usuario administrador.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param $params->MaxRows (int): Número máximo de filas a devolver.
 * @param $params->OrderedItem (int): Elemento por el cual ordenar los resultados.
 * @param $params->SkeepRows (int): Número de filas a omitir.
 *
 *
 * @return array $response Respuesta de la operación:
 *  - HasError (boolean): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta (success, danger, etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Datos del usuario.
 *
 * @throws Exception Si el usuario no pertenece a la red o si no tiene permisos para realizar la operación.
 */


/* ejecuta un script PHP con parámetros de sesión y consulta de cliente. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$id = $_GET["id"];


exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'CONSULTA GetClientSpById " . ' ID ' . $id . "  " . $_SESSION['usuario'] . "  " . $_SESSION["win_perfil"] . "  " . $_SESSION["nombre"] . "  " . session_id() . "' '#virtualsoft-cron2' > /dev/null & ");


/* valida roles de usuario y registra acciones sospechosas en un sistema. */
$seguir = true;
if ($_SESSION["win_perfil"] == "CAJERO") {
//$seguir = false;
}

if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

    $Concesionario = new Concesionario($id, '0');

    if ($Concesionario->usupadreId != $_SESSION["usuario"]) {
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'Sospechoso " . $id . " " . $_SESSION["win_perfil"] . " " . $_SESSION["usuario"] . "' '#alertas-integraciones' > /dev/null & ");
        syslog(LOG_WARNING, "SOSPECHOSO :" . $_SESSION['usuario'] . " " . $dir_ipG . json_encode($_SERVER) . json_encode($_REQUEST));

        throw new Exception("Error General. " . "Usuario SUB no pertenece a la red", "100000");

    }

}


/* Verifica el perfil y alerta si el usuario no es el padre autorizado. */
if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

    $Concesionario = new Concesionario($id, '0');

    if ($Concesionario->usupadre2Id != $_SESSION["usuario"]) {
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'Sospechoso " . $id . ' ' . $_SESSION["win_perfil"] . " " . $_SESSION["usuario"] . "' '#alertas-integraciones' > /dev/null & ");
        syslog(LOG_WARNING, "SOSPECHOSO :" . $_SESSION['usuario'] . " " . $dir_ipG . json_encode($_SERVER) . json_encode($_REQUEST));

        throw new Exception("Error General. " . "Usuario SUB no pertenece a la red", "100000");

    }

}

/* Verifica si un usuario pertenece a un concesionario y genera alertas en caso contrario. */
if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {

    $Concesionario = new Concesionario($id, '0');

    if ($Concesionario->usupadre3Id != $_SESSION["usuario"]) {
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'Sospechoso " . $id . ' ' . $_SESSION["win_perfil"] . " " . $_SESSION["usuario"] . "' '#alertas-integraciones' > /dev/null & ");
        syslog(LOG_WARNING, "SOSPECHOSO :" . $_SESSION['usuario'] . " " . $dir_ipG . json_encode($_SERVER) . json_encode($_REQUEST));

        throw new Exception("Error General. " . "Usuario SUB no pertenece a la red", "100000");

    }

}


/* Se crea una nueva instancia de la clase ConfigurationEnvironment en el espacio de nombres Backend\dto. */
$ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

if ($id != "" && $seguir) {



    /* Se crea una nueva instancia de la clase UsuarioPerfil utilizando un identificador específico. */
    $UsuarioPerfil = new UsuarioPerfil($id);


    if ($UsuarioPerfil->perfilId == 'USUONLINE') {

        $permission = $ConfigurationEnvironment->checkUserPermission('Client/GetClientSpById', $_SESSION['win_perfil'], $_SESSION['usuario'], 'customers');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    } elseif (in_array($UsuarioPerfil->perfilId, array('PUNTOVENTA', 'CAJERO'))) {

        $permission = $ConfigurationEnvironment->checkUserPermission('Client/GetClientSpById', $_SESSION['win_perfil'], $_SESSION['usuario'], 'betShopManagement');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    } elseif (in_array($UsuarioPerfil->perfilId, array('CONCESIONARIO', 'CONCESIONARIO2', 'AFILIADOR'))) {

        $permission = $ConfigurationEnvironment->checkUserPermission('Client/GetClientSpById', $_SESSION['win_perfil'], $_SESSION['usuario'], 'agentListManagement');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    } else {

        $permission = $ConfigurationEnvironment->checkUserPermission('Client/GetClientSpById', $_SESSION['win_perfil'], $_SESSION['usuario'], 'adminUserManagement');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    }


    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 10;
    }

    $rules = [];
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $id, "op" => "eq"));

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    }

    if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    }



    /* Se construye un filtro y se obtienen usuarios de una base de datos en JSON. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $usuarios = $Usuario->getUsuariosSuperCustom("DISTINCT (usuario.usuario_id), usuario.verifcedula_ant,  usuario.verifcedula_post,usuario.retirado, usuario.observ,usuario.creditos_afiliacion,usuario.arrastra_negativo,usuario.usumodif_id,usuario.token_google,usuario.token_local,usuario.ubicacion_longitud,usuario.ubicacion_latitud,usuario.restriccion_ip,usuario.usuario_ip,usuario.permite_activareg,usuario.bloqueo_ventas,usuario.nombre,usuario.idioma,usuario_config.*,usuario.documento_validado,usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,ciudad.*,departamento.*,pais.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,usuario_perfil.*,punto_venta.*,concesionario.*,usuario.contingencia,usuario.contingencia_deportes,usuario.contingencia_casino,usuario.contingencia_casvivo,usuario.contingencia_virtuales,usuario.contingencia_poker,usuario.contingencia_retiro,usuario.contingencia_deposito,f.*,punto_venta.facebook,punto_venta.facebook_verificacion,punto_venta.instagram,punto_venta.instagram_verificacion,punto_venta.whatsApp,punto_venta.whatsApp_verificacion,punto_venta.otraredessociales,punto_venta.otraredessociales_verificacion,punto_venta.cedula,punto_venta.identificacion_ip,usuario.pago_comisiones ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
    $usuarios = json_decode($usuarios);



    /* Genera un filtro en formato JSON para una consulta de usuario. */
    $rulesUinfo = [];
    array_push($rulesUinfo, array("field" => "usuario.usuario_id", "data" => $id, "op" => "eq"));

    $filtroUinfo = array("rules" => $rulesUinfo, "groupOp" => "AND");
    $jsonUinfo = json_encode($filtroUinfo);



    /* obtiene información de usuarios y la decodifica en formato JSON. */
    try {
        $UsuarioInformacion = new \Backend\dto\UsuarioInformacion();

        $usuariosInfo = $UsuarioInformacion->getusuarioInformacionCustom("usuario_informacion.*,clasificador.abreviado ", "usuario_informacion.usuinformacion_id", "asc", 0, 1000, $jsonUinfo, true, 'usuario_informacion.usuinformacion_id');
        $usuariosInfo = json_decode($usuariosInfo);


    } catch (Exception $e) {
        /* Bloque para manejar excepciones en PHP, permitiendo capturar errores sin interrumpir el flujo. */


    }

    /* Código que inicializa un clasificador y obtiene configuración de usuario para límites de retiro. */
    try {
        $Clasificador = new Clasificador('', 'DAYLILIMITPV');
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 0);
        $IsActivateWithdrawalLimit = $UsuarioConfiguracion->getEstado();
        $WithdrawalLimit = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {
        /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


    }

    /* inicializa un clasificador y obtiene configuración de usuario relacionada con límites de depósito. */
    try {
        $Clasificador = new Clasificador('', 'DAYLILIMITPV');
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId(), 1);
        $IsActivateDepositLimit = $UsuarioConfiguracion->getEstado();
        $DepositLimit = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {
        /* Captura cualquier excepción en PHP y permite manejar errores sin interrumpir el script. */


    }


    /* Código que intenta inicializar objetos y captura excepciones potenciales. */
    try {
        $Clasificador = new Clasificador('', 'CODEMINCETUR');
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId());
        $CodeMinceturPv = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {

    }


    /* Código intenta crear configuraciones de usuario y clasificador, manejando excepciones. */
    try {
        $Clasificador = new Clasificador('', 'CODEMINCETUR');
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, 'A', $Clasificador->getClasificadorId());
        $CodeMinceturPv = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {

    }


    /* Se instancia un clasificador y se obtienen IDs de concesionarios permitidos. */
    try {
        $Clasificador = new Clasificador("", "CONCESIONARIOSALLOWED");
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, "A", $Clasificador->getClasificadorId());
        $IdsAllowed = $UsuarioConfiguracion->getValor();

        $idsConcesionarios = explode(",", $IdsAllowed);

    } catch (Exception $e) {
        /* Captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución del script. */


    }


    /* obtiene una configuración de usuario relacionada con pagos y retiros. */
    try {
        $Clasificador = new Clasificador("", "ISACTIVATEPAYWITHDRAWALALLIES");
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, "A", $Clasificador->getClasificadorId());
        $IsActivatePayWithdrawalAllies = $UsuarioConfiguracion->getValor();

    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, permite capturar y procesar errores. */


    }


    /* Código que inicializa un clasificador y obtiene un valor de configuración de usuario. */
    try {
        $Clasificador = new Clasificador("", "DAILYWITHDRAWALPOINTLIMIT");
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, "A", $Clasificador->getClasificadorId());
        $DAILYWITHDRAWALPOINTLIMIT = $UsuarioConfiguracion->getValor();

    } catch (Exception $e) {
        /* Bloque para manejar excepciones en PHP, evitando interrupciones del script. */


    }


    /* Código que inicializa un clasificador y obtiene un valor de configuración de usuario. */
    try {
        $Clasificador = new Clasificador("", "LIMITDAILYDEPOSITSPERPOINTSOFSALE");
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, "A", $Clasificador->getClasificadorId());
        $LIMITDAILYDEPOSITSPERPOINTSOFSALE = $UsuarioConfiguracion->getValor();

    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, se captura cualquier error sin realizar acciones. */


    }

//boton de restriccion de retiro


    /* intenta configurar un usuario con restricciones de retiro, manejando excepciones. */
    try {
        $Clasificador = new Clasificador("", "ACTIVATESECONDLEVELPOINTOFSALEWITHDRAWALS");
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, "A", $Clasificador->getClasificadorId());
        $WithdrawalRestriction = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {

    }

// restriccion de deposito para redes aliadas


    /* Se intenta obtener restricciones de depósito para un usuario usando un clasificador. */
    try {
        $Clasificador = new Clasificador("", "ALLOWSDEPOSITTOALLIEDNETWORKS");
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, "A", $Clasificador->getClasificadorId());
        $DepositRestriction = $UsuarioConfiguracion->getValor();
    } catch (Exception $e) {

    }



    /* verifica si la opción de pago con aliados está activada para un usuario. */
    try {
        $Clasificador = new Clasificador("", "ISACTIVATEPAYWITHDRAWALALLIES");
        $UsuarioConfiguracion = new UsuarioConfiguracion($id, "A", $Clasificador->getClasificadorId());
        $IsActivatePayWithdrawalAllies = $UsuarioConfiguracion->getValor();

    } catch (Exception $e) {
        /* Maneja excepciones en PHP, permitiendo continuar sin interrumpir el flujo del programa. */


    }


    /* Se inicializa un array vacío llamado 'usuariosFinal' para almacenar datos de usuarios. */
    $usuariosFinal = [];

    foreach ($usuarios->data as $key => $value) {

        /* Crea un arreglo vacío en PHP para almacenar datos posteriormente. */
        $array = [];

        if ($value->{"usuario_perfil.perfil_id"} != "USUONLINE") {

            /* crea un array asociativo con información de un usuario. */
            $array["CodeMinceturPv"] = $CodeMinceturPv;
            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["id"] = $value->{"usuario.usuario_id"};
            $array["Ip"] = $value->{"usuario.dir_ip"};
            $array["Login"] = $value->{"usuario.login"};
            $array["Estado"] = array($value->{"usuario.estado"});

            /* Asignación de variables en un array con datos de usuario y preferencias. */
            $array["State"] = array($value->{"usuario.estado"});
            $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
            $array["Idioma"] = $value->{"a.idioma"};
            $array["PreferredLanguage"] = $value->{"usuario.idioma"};
            $array["Name"] = $value->{"usuario.nombre"};
            $array["FirstName"] = $value->{"registro.nombre1"};

            /* asigna valores a un array asociativo en PHP usando propiedades de un objeto. */
            $array["MiddleName"] = $value->{"registro.nombre2"};
            $array["LastName"] = $value->{"registro.apellido1"};
            $array["Email"] = $value->{"punto_venta.email"};
            $array["IdsAllowed"] = $idsConcesionarios;
            $array["IsActivatePayWithdrawalAllies"] = $IsActivatePayWithdrawalAllies;
            $array["WithdrawalLimitAllies"] = $DAILYWITHDRAWALPOINTLIMIT;

            /* asigna valores a un array basado en propiedades de un objeto. */
            $array["DepositLimitAllies"] = $LIMITDAILYDEPOSITSPERPOINTSOFSALE;
            $array["IsActiveWithdrawalLimitAllies"] = $WithdrawalRestriction;
            $array["IsActiveDepositLimitAllies"] = $DepositRestriction;
            $array["Address"] = $value->{"punto_venta.direccion"};
            $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
            $array["Intentos"] = $value->{"usuario.intentos"};

            /* Asignación de valores a un arreglo basado en propiedades de un objeto. */
            $array["Observaciones"] = $value->{"usuario.observ"};
            $array["Note"] = $value->{"usuario.observ"};
            $array["Moneda"] = $value->{"usuario.moneda"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["Type"] = $value->{"usuario_perfil.perfil_id"};
            if ($value->{"usuario.retirado"} == "S") {
                $array["IsRetired"] = "A";
            } else {
                /* Asigna "I" a "IsRetired" si la condición del bloque anterior no se cumple. */

                $array["IsRetired"] = "I";
            }



            /* Asigna el balance y define el tipo según el perfil del usuario. */
            $array["BalanceAssigned"] = $value->{"usuario.creditos_afiliacion"};


            if ($value->{"usuario_perfil.perfil_id"} == "AFILIADOR") {
                $array["Type"] = 1;
            } elseif (strpos($value->{"usuario_perfil.perfil_id"}, "CONCESIONARIO") !== FALSE) {
                /* Verifica si el perfil del usuario contiene "CONCESIONARIO" en su identificador. */

                /* asigna valores a un arreglo con información de usuario, país y ciudad. */
                $array["Type"] = 0;
            }

            $array["Pais"] = $value->{"usuario.pais_id"};
            $array["City"] = $value->{"g.ciudad_nom"};

            $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};


            /* asigna valores a un array desde un objeto `$value`. */
            $array["IsLocked"] = false;
            $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
            $array["BirthDate"] = $value->{"c.fecha_nacim"};

            $array["BirthDepartment"] = $value->{"g.depto_id"};
            $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};

            /* asocia valores de un objeto a un arreglo asociativo en PHP. */
            $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
            $array["Balance"] = $value->{"registro.creditos"} + $value->{"registro.creditos_base"};

            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["DocNumber"] = $value->{"registro.cedula"};
            $array["Gender"] = $value->{"registro.sexo"};

            /* Asigna valores a un array desde un objeto usando propiedades específicas. */
            $array["Language"] = $value->{"usuario.idioma"};
            $array["MobilePhone"] = $value->{"registro.celular"};
            $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};
            $array["Province"] = $value->{"registro.ciudad_id"};
            $array["RegionId"] = $value->{"usuario.pais_id"};
            $array["RegionId"] = $value->{"ciudad.depto_id"};

            /* asigna valores a un array usando propiedades de un objeto. */
            $array["CountryId"] = $value->{"usuario.pais_id"};
            $array["CountryName"] = $value->{"usuario.pais_id"};
            $array["ZipCode"] = $value->{"registro.codigo_postal"};
            $array["IsVerified"] = true;
            $array["IsActivate"] = ($value->{"usuario.estado"});
            $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});

            /* asigna valores de un objeto a un array con condiciones específicas. */
            $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});
            $array["IsRetired"] = (($value->{"usuario.retirado"}) == 'S' ? 'A' : 'N');

            $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};
            $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
            $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);



            /* asigna valores de un objeto a un arreglo asociativo en PHP. */
            $array["PrizePaymentTax"] = ($value->{"punto_venta.impuesto_pagopremio"});

            $array["ContactName"] = ($value->{"punto_venta.nombre_contacto"});
            $array["Description"] = $value->{"punto_venta.descripcion"};
            $array["Phone"] = $value->{"punto_venta.telefono"};
            $array["TypeEstablishment"] = $value->{"punto_venta.clasificador3_id"};

            /* Asigna valores de un objeto a un array asociativo en PHP. */
            $array["Zone"] = $value->{"punto_venta.clasificador4_id"};
            $array["RegionId"] = $value->{"departamento.depto_id"};
            $array["City"] = $value->{"ciudad.ciudad_id"};
            $array["CityId"] = $value->{"ciudad.ciudad_id"};
            $array["Pinagent"] = $value->{"usuario_config.pinagent"};
            $array["Lockedsales"] = $value->{"usuario.bloqueo_ventas"};

            /* Asigna valores de configuración del usuario a un array en PHP. */
            $array["PrintReceiptBox"] = $value->{"usuario_config.recibo_caja"};
            $array["AllowsRecharges"] = $value->{"usuario_config.permite_recarga"};
            $array["ActivateRegistration"] = $value->{"usuario.permite_activareg"};
            $array["District"] = $value->{"punto_venta.barrio"};
            $array["LastModifiedUser"] = $value->{"usuario.usumodif_id"};
            $array["LastIPaddress"] = $value->{"usuario.dir_ip"};

            /* Asigna valores de usuario a un array según condiciones específicas en PHP. */
            $array["IP"] = $value->{"usuario.usuario_ip"};
            $array["IsRestrictionIP"] = $value->{"usuario.restriccion_ip"};
            $array["IsTokenGoogle"] = $value->{"usuario.token_google"};
            $array["IsTokenLocal"] = $value->{"usuario.token_local"};
            $array["AllowDeposits"] = ($value->{"usuario_config.permite_recarga"} == "S") ? "A" : "I";
            $array["Longitud"] = $value->{"usuario.ubicacion_longitud"};

            /* Asigna valores de un objeto a un array asociativo en PHP. */
            $array["Latitud"] = $value->{"usuario.ubicacion_latitud"};
            $array["UserCountry"] = $value->{"usuario_perfil.pais"};
            $array["UserGlobal"] = $value->{"usuario_perfil.global"};

            $array["SpecialStatus"] = $value->{"usuario.estado_esp"};

            $array["IsActivateContingency"] = $value->{"usuario.contingencia"};

            /* Asigna valores de contingencia de usuario a un arreglo en PHP. */
            $array["IsActivateContingencyDeportivas"] = $value->{"usuario.contingencia_deportes"};
            $array["IsActivateContingencyCasino"] = $value->{"usuario.contingencia_casino"};
            $array["IsActivateContingencyCasinoVivo"] = $value->{"usuario.contingencia_casvivo"};
            $array["IsActivateContingencyVirtuales"] = $value->{"usuario.contingencia_virtuales"};
            $array["IsActivateContingencyPoker"] = $value->{"usuario.contingencia_poker"};
            $array["IsActivateContingencyWithdrawals"] = ($value->{"usuario.contingencia_retiro"});



            /* Asigna valores a un arreglo basado en propiedades de un objeto. */
            $array["IsActivateContingencyWithdrawal"] = ($value->{"usuario.contingencia_retiro"});
            $array["IsActivateContingencyDeposit"] = ($value->{"usuario.contingencia_deposito"});

            $array["DragNegatives"] = ($value->{"usuario.arrastra_negativo"} == '1') ? 'S' : 'N';

            if ($value->{"concesionario.usupadre_id"} != "") {
                $array["Concessionaire"] = $value->{"concesionario.usupadre_id"};
            }


            /* Asigna valores a un arreglo basándose en condiciones específicas de concesionarios. */
            if ($value->{"concesionario.usupadre2_id"} != "") {
                $array["Subconcessionaire"] = $value->{"concesionario.usupadre2_id"};
            }
            if ($value->{"concesionario.usupadre3_id"} != "") {
                $array["Subconcessionaire2"] = $value->{"concesionario.usupadre3_id"};
            }


            /* Asignación de valores a un array basado en propiedades de un objeto. */
            $array["MinimumBet"] = ($value->{"f.apuesta_min"});
            $array["DailyLimit"] = ($value->{"f.valor_diario"});

            $array["DailyQuotaReloads"] = ($value->{"punto_venta.valor_cupo2"});
            $array["BetShopOwn"] = ($value->{"punto_venta.propio"});

            $array["CustomCostCenter"] = ($value->{"punto_venta.codigo_personalizado"});

            /* Asigna valores de un objeto a un arreglo para formato de recibos de pago. */
            $array["Account"] = ($value->{"punto_venta.cuentacontable_id"});
            $array["AccountClose"] = ($value->{"punto_venta.cuentacontablecierre_id"});

            $array["HeaderPrintPrizePaymentReceipt"] = ($value->{"punto_venta.header_recibopagopremio"});
            $array["FooterPrintPrizePaymentReceipt"] = ($value->{"punto_venta.footer_recibopagopremio"});
            $array["HeaderPrintRetirementPaymentReceipt"] = ($value->{"punto_venta.header_recibopagoretiro"});

            /* Asigna valores de un objeto a un array y define un identificador IP. */
            $array["FooterPrintRetirementPaymentReceipt"] = ($value->{"punto_venta.footer_recibopagoretiro"});
            $array["BetShopType"] = ($value->{"punto_venta.tipo_tienda"});
            $array["Document"] = $value->{"punto_venta.cedula"};
            switch ($value->{"punto_venta.identificacion_ip"}) {
                case '0':
                    $IPIdentification = "N";
                    break;
                case '1':
                    $IPIdentification = "S";
                    break;
            }

            /* Código asigna valores a un array y verifica condición de Facebook. */
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

            /* Asignación de valores de verificación de redes sociales en un arreglo basado en condiciones. */
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

            /* asigna valores de verificación de WhatsApp y Instagram a un arreglo. */
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

            /* asigna variables de verificación para WhatsApp y redes sociales. */
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

            /* asigna valores y realiza verificación según la identificación de IP. */
            $array["OtherSocialMediaVerification"] = $OtherSocialMediaVerification;
            $array["ComissionsPayment"] = $value->{"usuario.pago_comisiones"};
            $array["Document"] = $value->{"punto_venta.cedula"};
            switch ($value->{"punto_venta.identificacion_ip"}) {
                case '0':
                    $IPIdentification = "N";
                    break;
                case '1':
                    $IPIdentification = "S";
                    break;
            }

            /* asigna valores a un array basado en condiciones específicas. */
            $array["IPIdentification"] = $IPIdentification;

            $array["Facebook"] = $value->{"punto_venta.facebook"};
            $array["PhysicalPrize"] = $value->{"punto_venta.premiofisico"};
            switch ($value->{"punto_venta.facebook_verificacion"}) {
                case '0':
                    $FacebookVerification = "N";
                    break;
                case '1':
                    $FacebookVerification = "S";
                    break;
            }

            /* asigna valores de verificación de Facebook e Instagram a un array. */
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

            /* asigna verificaciones de Instagram y WhatsApp a un arreglo. */
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

            /* Asigna valores de verificación de redes sociales a un arreglo en PHP. */
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

            /* Configura un array con datos de usuario y verifica documentación según condiciones específicas. */
            $array["OtherSocialMediaVerification"] = $OtherSocialMediaVerification;
            $array["ComissionsPayment"] = $value->{"usuario.pago_comisiones"};

            $array["MaximumWithdrawalAmount"] = $value->{"usuario_config.maxpago_retiro"};
            $array["MaximumPrizePaymentAmount"] = $value->{"usuario_config.maxpago_premio"};

            if ($value->{"usuario.verifcedula_ant"} == "S") {
                $array["IsActivateDniAnterior"] = "A";


                $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'A' . '.png');
                $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'A' . '.png');

                $array["DNIA"] = $filename;
            }

            /* Verifica el estado de usuario y genera un enlace a la imagen del documento. */
            if ($value->{"usuario.verifcedula_post"} == "S") {
                $array["IsActivateDniPosterior"] = "A";

                $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'P' . '.png');
                $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'P' . '.png');

                $array["DNIP"] = $filename;
            }

            /* procesa datos de usuarios clasificándolos en un arreglo específico. */
            foreach ($usuariosInfo->data as $datum) {

                switch ($datum->{"clasificador.abreviado"}) {
                    case "VISITREAL":
                        $array["VisitMade"] = $datum->{"usuario_informacion.valor"};
                        break;

                    case "DOCUMRECIB":
                        $array["DocumentationReceived"] = $datum->{"usuario_informacion.valor"};
                        break;
                }

            }

            /* Asigna valores a un arreglo asociativo sobre límites de retiro y depósito. */
            $array["IsActivateWithdrawalLimit"] = $IsActivateWithdrawalLimit;
            $array["WithdrawalLimit"] = $WithdrawalLimit;
            $array["IsActivateDepositLimit"] = $IsActivateDepositLimit;
            $array["DepositLimit"] = $DepositLimit;
        } else {



            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["id"] = $value->{"usuario.usuario_id"};
            $array["Ip"] = $value->{"ausuario.dir_ip"};
            $array["Login"] = $value->{"usuario.login"};
            $array["Estado"] = array($value->{"usuario.estado"});
            $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};

            /* Asigna valores de un objeto a un array asociativo en PHP. */
            $array["Idioma"] = $value->{"a.idioma"};
            $array["Nombre"] = $value->{"a.nombre"};
            $array["FirstName"] = $value->{"registro.nombre1"};
            $array["MiddleName"] = $value->{"registro.nombre2"};
            $array["LastName"] = $value->{"registro.apellido1"};
            $array["SecondLastName"] = $value->{"registro.apellido2"};

            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["Email"] = $value->{"registro.email"};
            $array["Address"] = $value->{"registro.direccion"};
            $array["Affiliate"] = $value->{"registro.afiliador_id"};
            $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
            $array["Intentos"] = $value->{"usuario.intentos"};
            $array["Observaciones"] = $value->{"usuario.observ"};

            /* Se asignan valores a un arreglo a partir de propiedades de un objeto. */
            $array["Moneda"] = $value->{"usuario.moneda"};
            $array["LoyaltyPoints"] = intval($value->{"usuario.puntos_lealtad"});
            $array["Pais"] = $value->{"usuario.pais_id"};
            $array["City"] = $value->{"g.ciudad_nom"};

            $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};


            /* asigna valores a un array a partir de un objeto $value. */
            $array["IsLocked"] = false;
            $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
            $array["BirthDate"] = $value->{"c.fecha_nacim"};

            $array["BirthDepartment"] = $value->{"g.depto_id"};
            $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};

            /* asigna valores a un array basado en propiedades de un objeto. */
            $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
            $array["Balance"] = ((round($value->{"registro.creditos"} + $value->{"registro.creditos_base"}, 2)));


            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["Currency"] = $value->{"usuario.moneda"};

            /* asigna valores a un arreglo a partir de un objeto. */
            $array["DocNumber"] = $value->{"registro.cedula"};
            $array["Gender"] = $value->{"registro.sexo"};
            $array["Language"] = $value->{"usuario.idioma"};
            $array["Phone"] = $value->{"registro.telefono"};
            $array["MobilePhone"] = $value->{"registro.celular"};
            $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};

            /* asigna valores a claves en un array desde un objeto. */
            $array["RegionId"] = $value->{"ciudad.depto_id"};
            $array["Province"] = $value->{"registro.ciudad_id"};
            $array["CityId"] = $value->{"registro.ciudad_id"};
//$array["RegionId"] = $value->{"usuario.pais_id"};
            $array["CountryId"] = $value->{"usuario.pais_id"};
            $array["RegionId"] = $value->{"d.depto_id"};

            /* asigna datos de un objeto a un array asociativo en PHP. */
            $array["DocumentType"] = ($value->{"registro.tipo_doc"});

            $array["CountryName"] = $value->{"usuario.pais_id"};
            $array["ZipCode"] = $value->{"registro.codigo_postal"};
            $array["IsVerified"] = true;
            $array["IsActivate"] = ($value->{"usuario.estado"});

            /* Se asignan valores a un arreglo basado en propiedades de un objeto. */
            $array["IsRegisterActivate"] = ($value->{"registro.estado_valida"});
            $array["IsDocumentActivate"] = ($value->{"usuario.documento_validado"});


// if(retirado == 'S'){
//     IsRetired = 'A'
// }else{
//     IsRetired = 'I'

// }

            $array["DaysChangeLimitDeposit"] = $value->{"usuario.tiempo_limitedeposito"};

            /* Asignación de valores a un arreglo basados en propiedades de un objeto. */
            $array["DaysChangeLimitSelfExclusion"] = $value->{"usuario.tiempo_autoexclusion"};
            $array["ChangesToApproval"] = ($value->{"usuario.cambios_aprobacion"} == "S" ? true : false);


            $array["PrizePaymentTax"] = ($value->{"punto_venta.impuesto_pagopremio"});

            $array["CreatedLocalDate"] = ($value->{"usuario.fecha_crea"});


            /* Asigna valores de contingencia de usuario a un array asociativo en PHP. */
            $array["IsActivateContingency"] = ($value->{"usuario.contingencia"});
            $array["IsActivateContingencyDeportivas"] = ($value->{"usuario.contingencia_deportes"});
            $array["IsActivateContingencyCasino"] = ($value->{"usuario.contingencia_casino"});
            $array["IsActivateContingencyCasinoVivo"] = ($value->{"usuario.contingencia_casvivo"});
            $array["IsActivateContingencyVirtuales"] = ($value->{"usuario.contingencia_virtuales"});
            $array["IsActivateContingencyPoker"] = ($value->{"usuario.contingencia_poker"});

            /* Se asignan valores a un array basado en propiedades de un objeto. */
            $array["IsActivateRegistroUsuario"] = ($value->{"registro.estado_valida"});

            $array["IsActivateDniAnterior"] = "I";
            $array["IsActivateDniPosterior"] = "I";

            $array["MinimumBet"] = ($value->{"f.apuesta_min"});

            /* Asigna valores a un array desde un objeto con propiedades específicas. */
            $array["DailyLimit"] = ($value->{"f.valor_diario"});
            $array["DailyQuotaReloads"] = ($value->{"punto_venta.cupo_recarga"});
            $array["Concessionaire"] = ($value->{"concesionario_punto.usupadre1_id"});
            $array["Subconcessionaire"] = ($value->{"concesionario_punto.usupadre2_id"});

            $array["StateDniFront"] = ($value->{"usuario.verifcedula_ant"});

            /* Asigna datos de verificación y genera URL para documento basado en condiciones específicas. */
            $array["StateDniBack"] = ($value->{"usuario.verifcedula_post"});
            $array["StateAddress"] = ($value->{"usuario.verifdomicilio"});

            if ($value->{"usuario.verifcedula_ant"} == "S") {
                $array["IsActivateDniAnterior"] = "A";


                $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'A' . '.png');
                $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'A' . '.png');

                $array["DNIA"] = $filename;
            }

            /* verifica un usuario y genera URLs de imagen según su estado. */
            if ($value->{"usuario.verifcedula_post"} == "S") {
                $array["IsActivateDniPosterior"] = "A";

                $filename = "https://backofficeapi.virtualsoft.tech/es/Image/Document?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'P' . '.png');
                $filename = "https://affiliatesapi.virtualsoft.tech/Bonus/Image/getDocument?r=" . $ConfigurationEnvironment->encrypt('c' . $value->{"usuario.usuario_id"} . 'P' . '.png');

                $array["DNIP"] = $filename;
            }


            /* asigna datos de un objeto a un array asociativo en PHP. */
            $array["Partners"] = ($value->{"usuario_perfil.mandante_lista"});

            $array["Info1"] = ($value->{"c.info1"});
            $array["Info2"] = ($value->{"c.info2"});
            $array["Info3"] = ($value->{"c.info3"});
            $array["Note"] = ($value->{"usuario.observ"});

            /* Se asigna el valor de "usuario.clave_tv" a la clave "Categorization" del array. */
            $array["Categorization"] = ($value->{"usuario.clave_tv"});


        }


        /* Clase para clasificar y obtener configuración de usuario desde una base de datos. */
        try {
            $clasificador = new Clasificador('', 'INDIVIDUALGGR');
            $tipo = $clasificador->clasificadorId;
            $UsuarioConfiguracionMySqlDAo = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAo = $UsuarioConfiguracionMySqlDAo->queryByUsuarioIdAndTipo($id, $tipo);
            if ($UsuarioConfiguracionMySqlDAo) {
                $array["IndividualCalculation"] = ($UsuarioConfiguracionMySqlDAo[0]->{"estado"} === 'A') ? 'S' : 'N';
            } else {
                $array["IndividualCalculation"] = 'N';
            }

        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


        }



        /* Se asigna el contenido de `$array` a la variable `$usuariosFinal`. */
        $usuariosFinal = $array;

    }

    /* asigna una respuesta exitosa si hay usuarios finales. */
    if ($usuariosFinal) {

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response = [$usuariosFinal];

    }
}
