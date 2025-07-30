<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\AuditoriaGeneral;
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
use Backend\mysql\AuditoriaGeneralMySqlDAO;
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
 * Client/UpdateClientDetails
 *
 * Actualiza los detalles de la cuenta de un cliente.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param int $params ->Id Identificador del cliente.
 * @param string $params ->Address Dirección del cliente.
 * @param string $params ->BirthCity Ciudad de nacimiento.
 * @param string $params ->BirthDate Fecha de nacimiento en formato 'Y-m-d'.
 * @param string $params ->Email Correo electrónico.
 * @param string $params ->FirstName Primer nombre.
 * @param string $params ->LastName Primer apellido.
 * @param string $params ->MiddleName Segundo nombre.
 * @param string $params ->Phone Teléfono.
 * @param string $params ->MobilePhone Teléfono móvil.
 * @param string $params ->gender Género.
 * @param string $params ->DocumentType Tipo de documento ('E', 'C', 'P').
 * @param int $params ->DaysChangeLimitDeposit Límite de días para cambio de depósito.
 * @param int $params ->DaysChangeLimitSelfExclusion Límite de días para autoexclusión.
 * @param boolean $params ->ChangesToApproval Indica si hay cambios pendientes de aprobación.
 * @param string $params ->Note Observaciones.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta.
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Información adicional.
 */


/* Se crean instancias de Usuario y UsuarioPerfil utilizando un ClientId específico. */
$ClientId = $params->Id;
$Usuario = new Usuario($ClientId);


$UsuarioPerfil = new UsuarioPerfil($ClientId);

$ConfigurationEnvironment = new ConfigurationEnvironment();



/* Condición que verifica si el perfil del usuario es 'USUONLINE'. */
if ($UsuarioPerfil->perfilId == 'USUONLINE') {

    $permission = $ConfigurationEnvironment->checkUserPermission('Client/UpdateClientDetails', $_SESSION['win_perfil'], $_SESSION['usuario'], 'customers');

    if (!$permission) throw new Exception('Permiso denegado', 100035);

} elseif (in_array($UsuarioPerfil->perfilId, array('PUNTOVENTA', 'CAJERO'))) {
    /* lanza una excepción si el perfil del usuario es PUNTOVENTA o CAJERO. */


    throw new Exception('Permiso denegado', 100035);

} elseif (in_array($UsuarioPerfil->perfilId, array('CONCESIONARIO', 'CONCESIONARIO2', 'AFILIADOR'))) {
    /* verifica perfiles y lanza excepción si acceso es no permitido. */


    throw new Exception('Permiso denegado', 100035);

} else {
    /* lanza una excepción indicando que el acceso no está permitido. */


    throw new Exception('Permiso denegado', 100035);

}



/* Se crean instancias de registros y usuario utilizando parámetros proporcionados. */
$Registro = new Registro("", $ClientId);
$UsuarioOtrainfo = new UsuarioOtrainfo($ClientId);


$Address = $params->Address;
$BirthCity = $params->BirthCity;

/* asigna variables basadas en parámetros de entrada, formateando la fecha de nacimiento. */
$BirthDate = ($params->BirthDate != '') ? date('Y-m-d', strtotime($params->BirthDate)) : '';
$BirthDateLocal = $params->BirthDateLocal;
$BirthRegionCode2 = $params->BirthRegionCode2;
$BirthRegionId = $params->BirthRegionId;
$City = $params->City;
$CountryName = $params->CountryName;

/* extrae datos de un objeto $params a variables específicas. */
$CreatedLocalDate = $params->CreatedLocalDate;
$CurrencyId = $params->CurrencyId;
$DocNumber = $params->DocNumber;
$Email = $params->Email;
$Estado = $params->Estado[0];
$EstadoEspecial = $params->EstadoEspecial;

/* asigna valores de parámetros a variables en un script. */
$FirstName = $params->FirstName;
$Gender = $params->Gender;
$Id = $params->Id;
$Idioma = $params->Idioma;
$Intentos = $params->Intentos;
$Ip = $params->Ip;

/* Asignación de variables a partir de parámetros para gestionar información de usuario. */
$IsLocked = $params->IsLocked;
$IsTest = $params->IsTest;
$IsVerified = $params->IsVerified;
$Language = $params->Language;
$LastLoginLocalDate = $params->LastLoginLocalDate;
$LastName = $params->LastName;

/* Asignación de valores de parámetros a variables en un contexto de programación. */
$SecondLastName = $params->SecondLastName;
$Login = $params->Login;
$MiddleName = $params->MiddleName;
$MobilePhone = $params->MobilePhone;
$Moneda = $params->Moneda;
$Nombre = $params->Nombre;

/* asigna valores de parámetros a variables para su posterior uso. */
$Pais = $params->Pais;
$Phone = $params->Phone;
$Province = $params->Province;
$RegionId = $params->RegionId;
$TipoUsuario = $params->TipoUsuario;
$ZipCode = $params->ZipCode;

/* asigna valores de parámetros a variables para su uso posterior. */
$CityId = $params->CityId;
$Affiliate = $params->Affiliate;
$IsUserTest = $params->IsUserTest;


$UserTestProvider = $params->UserTestProvider;

switch ($UserTestProvider) {
    case 0:
        /* Asignación de tipo de usuario "Ninguno" si la condición del caso es 0. */

        $TypeUser = "Ninguno";
        break;
    case 1:
        /* Asignación de un tipo de usuario específico en una estructura de control `case`. */

        $TypeUser = "USUARIO DE PRUEBAS DE TECNOLOGIA";
        break;
    case 2:
        /* asigna el valor "USUARIO DE PRUEBAS DE RIESGO" a la variable $TypeUser. */

        $TypeUser = "USUARIO DE PRUEBAS DE RIESGO";
        break;
    case 3:
        /* Asignación de un tipo de usuario específico en un bloque de código de caso. */

        $TypeUser = "USUARIO DE PRUEBAS PRODUCTO";
        break;
    case 4:
        /* Asigna el tipo de usuario como "USUARIO DE PRUEBAS SAC" en un switch. */

        $TypeUser = "USUARIO DE PRUEBAS  SAC";
        break;
    case 5:
        /* Asignación del tipo de usuario como "USUARIO DE PRUEBAS COMERCIAL" en el caso 5. */

        $TypeUser = "USUARIO DE PRUEBAS COMERCIAL";
        break;
    case 6:
        /* Asignación de texto a la variable $TypeUser según un caso específico. */

        $TypeUser = "USUARIO DE PRUEBAS PARTNER";
        break;
    case 7:
        /* asigna un tipo de usuario específico para el caso 7 en un switch. */

        $TypeUser = "USUARIO DE PRUEBAS DE QUOTA";
        break;
    case 8:
        /* Define un tipo de usuario como "USUARIO DE PRUEBAS DE FINANCIERA" en el caso 8. */

        $TypeUser = " USUARIO DE PRUEBAS DE FINANCIERA";
        break;
    case 9:
        /* Asignación de tipo de usuario para pruebas de Opercomercial en caso 9. */

        $TypeUser = "USUARIO DE PRUEBAS DE OPERCOMERCIAL";
        break;
    case 10:
        /* Asignación de tipo de usuario para un caso específico en el código. */

        $TypeUser = " USUARIO DE PRUEBAS ACCOUNT";
        break;
}



/* asigna valores de parámetros a variables, validando el tipo de documento. */
$Categorization = $params->Categorization;

$Gender = $params->Gender;

$DocumentType = ($params->DocumentType != 'E' && $params->DocumentType != 'C' && $params->DocumentType != 'P') ? '' : $params->DocumentType;

$DaysChangeLimitDeposit = $params->DaysChangeLimitDeposit;

/* Asigna valores de parámetros a variables específicas en un código. */
$DaysChangeLimitSelfExclusion = $params->DaysChangeLimitSelfExclusion;
$ChangesToApproval = $params->ChangesToApproval;

$EmailC = $params->Email;

$Info1 = $params->Info1;

/* obtiene información y la dirección IP del cliente desde parámetros y cabeceras. */
$Info2 = $params->Info2;
$Observ = $params->Note;


$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];



/* Código que establece estados de usuario y gestiona cambios en base a condiciones. */
$estadoUsuario = ($IsLocked == "false" ? "A" : "I");
$testUsuario = ($IsTest == "false" ? "N" : "S");
$verificadoUsuario = ($IsVerified == "false" ? "A" : "I");
$cambiosAprobacion = (!$ChangesToApproval ? "N" : "S");

$UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();

/* Se obtiene la transacción del usuario a través de un DAO específico. */
$Transaction = $UsuarioLog2MySqlDAO->getTransaction();


/* registra un cambio de dirección de usuario en la base de datos. */
if ($Address != $Registro->getDireccion()) {

    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUDIRECCION");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getDireccion());
    $UsuarioLog->setValorDespues($Address);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}

// print_r($Registro->getCiudadId());
// exit;


/* Registra un cambio de ciudad de usuario si el ID de ciudad es diferente. */
if ($CityId != $Registro->getCiudadId()) {

    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUCIUDADID");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getCiudadId());
    $UsuarioLog->setValorDespues($CityId);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);

    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}



/* Registra un cambio en afiliador ID si no coincide con el registro existente. */
if ($Affiliate != $Registro->getAfiliadorId()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("AFILIADORID");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getCiudadId());
    $UsuarioLog->setValorDespues($Affiliate);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);

    $UsuarioLog2MySqlDAO->insert($UsuarioLog);
}


/* registra un cambio de nombre de usuario en la base de datos. */
if ($FirstName != $Registro->getNombre1()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUNOMBRE1");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getNombre1());
    $UsuarioLog->setValorDespues($FirstName);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/* Registra un cambio de nombre en el log si el nombre medio es diferente. */
if ($MiddleName != $Registro->getNombre2()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUNOMBRE2");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getNombre2());
    $UsuarioLog->setValorDespues($MiddleName);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/* Registra un cambio de apellido en el log si es diferente al original. */
if ($LastName != $Registro->getApellido1()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUAPELLIDO1");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getApellido1());
    $UsuarioLog->setValorDespues($LastName);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/* Registra cambios en el segundo apellido del usuario si hay discrepancias. */
if ($SecondLastName != $Registro->getApellido2()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUAPELLIDO2");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getApellido2());
    $UsuarioLog->setValorDespues($SecondLastName);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}



/* Registra cambios en el teléfono del usuario si es diferente al actual. */
if ($Phone != $Registro->getTelefono()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUTELEFONO");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getTelefono());
    $UsuarioLog->setValorDespues($Phone);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/* Registra un cambio en el celular del usuario si es diferente al actual. */
if ($MobilePhone != $Registro->getCelular()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUCELULAR");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getCelular());
    $UsuarioLog->setValorDespues($MobilePhone);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/* Registra un cambio de email en el log de usuario si hay diferencia. */
if ($Email != $Registro->getEmail()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUEMAIL");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getEmail());
    $UsuarioLog->setValorDespues($EmailC);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}



/* Registra un cambio en el tiempo límite de depósito del usuario si hay diferencia. */
if ($DaysChangeLimitDeposit != $Usuario->tiempoLimitedeposito) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("TIEMPOLIMITEDEPOSITO");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Usuario->tiempoLimitedeposito);
    $UsuarioLog->setValorDespues($DaysChangeLimitDeposit);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/* Registra cambios en el tiempo de autoexclusión del usuario en un log. */
if ($DaysChangeLimitSelfExclusion != $Usuario->tiempoAutoexclusion) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("TIEMPOLIMITEAUTOEXCLUSION");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Usuario->tiempoAutoexclusion);
    $UsuarioLog->setValorDespues($DaysChangeLimitSelfExclusion);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/* Registra cambios en la fecha de nacimiento de un usuario en la base de datos. */
if ($BirthDate != $UsuarioOtrainfo->getFechaNacim()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUFECHANACIM");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($UsuarioOtrainfo->getFechaNacim());
    $UsuarioLog->setValorDespues($BirthDate);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


if (strtolower($DocNumber) !== strtolower($Registro->getCedula())) {



    /* Código para registrar un log de usuario con ID y dirección IP. */
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);


    /* Registro de cambios de usuario, marcando tipo, estado y valores antes y después. */
    $UsuarioLog->setTipo("USUCEDULA");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getCedula());
    $UsuarioLog->setValorDespues($DocNumber);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);



    /* Inserta un registro de usuario en la base de datos MySQL usando el DAO correspondiente. */
    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}



/* Registra un cambio de tipo de documento en el sistema de logs de usuarios. */
if ($DocumentType != $Registro->getTipoDoc()) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("USUTIPODOC");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->getTipoDoc());
    $UsuarioLog->setValorDespues($DocumentType);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/*if ($estadoUsuario != $Usuario->estado) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("ESTADOUSUARIO");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Usuario->estado);
    $UsuarioLog->setValorDespues($estadoUsuario);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}*/


/* Registra cambios de aprobación de usuario si son diferentes a los actuales. */
if ($cambiosAprobacion != $Usuario->cambiosAprobacion) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("CAMBIOSAPROBACION");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Usuario->cambiosAprobacion);
    $UsuarioLog->setValorDespues($cambiosAprobacion);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}



/* Registra cambios en la información del usuario comparando datos antes y después de actualizar. */
if ($Info1 != $UsuarioOtrainfo->info1) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("UINFO1");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($UsuarioOtrainfo->info1);
    $UsuarioLog->setValorDespues($Info1);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}



/* Registra un cambio de género en el historial de usuario si hay diferencias. */
if ($Gender != '' && $Gender != $Registro->sexo) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("GENDER");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Registro->sexo);
    $UsuarioLog->setValorDespues($Gender);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}



/* Registra cambios en la información del usuario si la condición se cumple. */
if ($Info2 != $UsuarioOtrainfo->info2) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("UINFO1");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($UsuarioOtrainfo->info2);
    $UsuarioLog->setValorDespues($Info2);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLog2MySqlDAO->insert($UsuarioLog);

}


/* Registra cambios en el estado de un usuario si el test es diferente. */
if ($IsUserTest != '' && $IsUserTest != $Usuario->test) {
    $UsuarioLog = new UsuarioLog2();
    $UsuarioLog->setUsuarioId($ClientId);
    $UsuarioLog->setUsuarioIp('');
    $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
    $UsuarioLog->setUsuariosolicitaIp($ip);
    $UsuarioLog->setTipo("USUFORTEST");
    $UsuarioLog->setEstado("P");
    $UsuarioLog->setValorAntes($Usuario->test);
    $UsuarioLog->setValorDespues($IsUserTest);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);

    $UsuarioLog2MySqlDAO->insert($UsuarioLog);
}



/* Actualiza la observación del usuario si es diferente y no está vacía. */
$updateUsuario = false;
if ($Observ != $Usuario->observ && $Observ != '') {

    $Usuario->observ = $Observ;

    $updateUsuario = true;
}

/* Actualiza la claveTv del usuario si es diferente y no está vacía. */
if ($Categorization != $Usuario->claveTv && $Categorization != '') {

    $Usuario->claveTv = $Categorization;

    $updateUsuario = true;
}


/* Actualiza usuario en la base de datos y confirma la transacción si la actualización es exitosa. */
if ($updateUsuario) {
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    $UsuarioMySqlDAO->update($Usuario);

    $UsuarioMySqlDAO->getTransaction()->commit();

}



/* confirma una transacción en una base de datos utilizando MySQL. */
$UsuarioLog2MySqlDAO->getTransaction()->commit();


try {


    /* Crea un objeto AuditoriaGeneral y establece información del usuario y su IP. */
    $AuditoriaGeneral = new AuditoriaGeneral();

    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);

    /* Configuración de auditoría para cambios de tipo de cuenta de usuario. */
    $AuditoriaGeneral->setUsuariosolicitaId(0);
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("CATEGORIZACIONUSUARIOPRUEBAS");
    $AuditoriaGeneral->setValorAntes($UsuarioOtrainfo->tipoCuenta);
    $AuditoriaGeneral->setValorDespues($UserTestProvider);
    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);

    /* Se configura un objeto de auditoría con estado, dispositivo y observaciones específicas. */
    $AuditoriaGeneral->setUsumodifId(0);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setObservacion("CATEGORIZACION USUARIO");

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

    /* Inserta auditoría y realiza una transacción, luego establece tipo de cuenta para usuario. */
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();


    $UsuarioOtrainfo = new UsuarioOtrainfo($ClientId);
    $UsuarioOtrainfo->setTipoCuenta($UserTestProvider);

    /* actualiza información de usuario en una base de datos MySQL. */
    $UsuarioOtrainfo->setInfo1($TypeUser);

    $UsuarioOtraInfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();
    $Transaction = $UsuarioOtraInfoMySqlDAO->getTransaction();
    $UsuarioOtraInfoMySqlDAO->update($UsuarioOtrainfo);
    $UsuarioOtraInfoMySqlDAO->getTransaction()->commit();


} catch (Exception $e) {
    /* Captura excepciones para manejar errores sin interrumpir la ejecución del código. */


}

if ($RegionId != "") {


    /* Verifica si el departamento pertenece al AUSTRO según su nombre. */
    $Departamento = new \Backend\dto\Departamento($RegionId);


    if (strtoupper($Departamento->getDeptoNom()) == "AZUAY" || strtoupper($Departamento->getDeptoNom()) == "EL ORO" || strtoupper($Departamento->getDeptoNom()) == "CAÑAR" || strtoupper($Departamento->getDeptoNom()) == "LOJA" || strtoupper($Departamento->getDeptoNom()) == "ZAMORA" || strtoupper($Departamento->getDeptoNom()) == "MORONA SANTIAGO") {
        $Region = "AUSTRO";

    }

    /* Determina la región de un departamento según su nombre en Ecuador. */
    if (strtoupper($Departamento->getDeptoNom()) == "ORELLANA" || strtoupper($Departamento->getDeptoNom()) == "SUCUMBIOS" || strtoupper($Departamento->getDeptoNom()) == "PICHINCHA" || strtoupper($Departamento->getDeptoNom()) == "ESMERALDAS" || strtoupper($Departamento->getDeptoNom()) == "NAPO" || strtoupper($Departamento->getDeptoNom()) == "SANTO DOMINGO" || strtoupper($Departamento->getDeptoNom()) == "TUNGURAHUA" || strtoupper($Departamento->getDeptoNom()) == "COTOPAXI" || strtoupper($Departamento->getDeptoNom()) == "IMBABURA" || strtoupper($Departamento->getDeptoNom()) == "CARCHI" || strtoupper($Departamento->getDeptoNom()) == "CHIMBORAZO" || strtoupper($Departamento->getDeptoNom()) == "BOLIVAR") {

        $Region = "CENTRO";

    }

    if (strtoupper($Departamento->getDeptoNom()) === "GUAYAS" || strtoupper($Departamento->getDeptoNom()) == "SANTA ELENA" || strtoupper($Departamento->getDeptoNom()) == "MANABI" || strtoupper($Departamento->getDeptoNom()) == "LOS RIOS" || strtoupper($Departamento->getDeptoNom()) == "GALAPAGOS") {
        $Region = "COSTA";
    }


    /* Actualiza la región de un perfil de usuario en la base de datos. */
    $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);
    $UsuarioPerfil->region = $Region;


    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
    $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);

    /* confirma una transacción en la base de datos utilizando MySQL. */
    $UsuarioPerfilMySqlDAO->getTransaction()->commit();
}


//$UsuarioMySqlDAO = new UsuarioMySqlDAO();

//$UsuarioMySqlDAO->update($Usuario);

//$UsuarioMySqlDAO->getTransaction()->commit();


/* prepara una respuesta JSON sin errores, con mensaje de éxito y datos vacíos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = $msg . " - " . $ClientId;
$response["ModelErrors"] = [];

$response["Data"] = [];
