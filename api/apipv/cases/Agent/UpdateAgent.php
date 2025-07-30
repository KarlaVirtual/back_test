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
use Backend\mysql\PuntoventadimMySqlDAO;
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
 * BetShop/UpdateBetShop
 *
 * Actualizar un punto de venta
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud:
 * @param string $params ->CityId Id de la ciudad
 * @param string $params ->Address Dirección
 * @param string $params ->CountryId Id del país
 * @param string $params ->CurrencyId Id de la moneda
 * @param string $params ->PreferredLanguage Idioma preferido
 * @param string $params ->DocumentLegalID Documento legal
 * @param string $params ->Email Correo electrónico
 * @param string $params ->GroupId Id del grupo
 * @param string $params ->IP Dirección IP
 * @param string $params ->Latitud Latitud
 * @param string $params ->Longitud Longitud
 * @param string $params ->ManagerDocument Documento del gerente
 * @param string $params ->ManagerName Nombre del gerente
 * @param string $params ->ContactName Nombre de contacto
 * @param string $params ->ManagerPhone Teléfono del gerente
 * @param string $params ->MobilePhone Teléfono móvil
 * @param string $params ->Name Nombre
 * @param string $params ->Description Descripción
 * @param string $params ->Login Usuario
 * @param string $params ->Phone Teléfono
 * @param string $params ->RegionId Id de la región
 * @param string $params ->District Barrio
 * @param string $params ->AllowsRecharges Permite recargas
 * @param string $params ->PrintReceiptBox Imprimir recibo
 * @param string $params ->ActivateRegistration Activar registro
 * @param string $params ->Lockedsales Bloqueo de ventas
 * @param string $params ->Pinagent Pin de agente
 * @param string $params ->BetShopOwn Propio
 * @param string $params ->RepresentLegalDocument Documento legal del representante
 * @param string $params ->RepresentLegalName Nombre del representante
 * @param string $params ->RepresentLegalPhone Teléfono del representante
 * @param string $params ->Partner Socio
 * @param string $params ->CustomCostCenter Centro de costos personalizado
 * @param string $params ->Account Cuenta
 * @param string $params ->AccountClose Cuenta de cierre
 * @param string $params ->Id Id
 * @param string $params ->MinimumBet Apuesta mínima
 * @param string $params ->DailyLimit Límite diario
 * @param string $params ->DailyQuotaReloads Recargas diarias
 * @param string $params ->Concessionaire Concesionario
 * @param string $params ->Subconcessionaire Subconcesionario
 * @param string $params ->Subconcessionaire2 Subconcesionario 2
 * @param string $params ->MaximumWithdrawalAmount Monto máximo de retiro
 * @param string $params ->MaximumPrizePaymentAmount Monto máximo de pago de premio
 * @param string $params ->Document Documento
 * @param string $params ->Note Nota
 * @param string $params ->IPIdentification Identificación de IP
 * @param string $params ->DragNegatives Arrastra negativos
 *
 * @return array $response Arreglo con la respuesta de la operación:
 *  - bool $HasError
 *  - string $AlertType
 *  - string $AlertMessage
 *  - array $ModelErrors
 *
 * @throws Exception Si se detecta un permiso denegado o una operación inusual.
 * @throws Exception Si se produce un error al realizar la operación.
 * @throws Exception Inusual Detected
 */


try {

    /**
     * Inicializa un array para almacenar cambios.
     * Se extraen varias propiedades del objeto $params para ser utilizadas posteriormente.
     */

    $cambiosG = array();

    $CityId = $params->CityId;

    $Address = $params->Address;
    $CityId = $params->CityId;
    if ($CityId == '') {
        $CityId = $params->City;

    }
    $CountryId = $params->CountryId;
    $CurrencyId = $params->CurrencyId;
    $PreferredLanguage = $params->PreferredLanguage;
    $DocumentLegalID = $params->DocumentLegalID;
    $Email = $params->Email;
    $GroupId = $params->GroupId;
    $IP = $params->IP;
    $Latitud = $params->Latitud;
    $Longitud = $params->Longitud;
    $ManagerDocument = $params->ManagerDocument;
    $ManagerName = $params->ManagerName;
    $ManagerName = $params->ContactName;

    $ManagerPhone = $params->ManagerPhone;
    $MobilePhone = $params->MobilePhone;
    $Name = $params->Name;
    $Description = $params->Description;
    $Login = $params->Login;
    $Phone = $params->Phone;
    $RegionId = $params->RegionId;

    $District = $params->District;

    $CanDeposit = $params->AllowsRecharges;
    $CanReceipt = $params->PrintReceiptBox;
    $CanActivateRegister = $params->ActivateRegistration;
    $Lockedsales = $params->Lockedsales;
    $Pinagent = $params->Pinagent;

    $BetShopOwn = $params->BetShopOwn;

    $RepresentLegalDocument = $params->RepresentLegalDocument;
    $RepresentLegalName = $params->RepresentLegalName;
    $RepresentLegalPhone = $params->RepresentLegalPhone;
    $Partner = $params->Partner;

    $CustomCostCenter = $params->CustomCostCenter;
    $Account = $params->Account;
    $AccountClose = $params->AccountClose;

    $Address = $Address;
    $CurrencyId = $CurrencyId;
    $Email = $Email;
    $FirstName = $Name;
    $Id = $params->Id;
    $IsSuspended = false;
    $LastLoginIp = "";
    $LastLoginLocalDate = "";
    $LastName = "";
    $clave = '';
    $SystemName = '';
    $UserId = '';
    $UserName = $params->UserName;
    $Phone = $params->Phone;

    $MinimumBet = $params->MinimumBet;
    $DailyLimit = $params->DailyLimit;
    $DailyQuotaReloads = $params->DailyQuotaReloads;
    $Concessionaire = $params->Concessionaire;
    $Subconcessionaire = $params->Subconcessionaire;
    $Subconcessionaire2 = $params->Subconcessionaire2;

    $MaximumWithdrawalAmount = $params->MaximumWithdrawalAmount;
    $MaximumPrizePaymentAmount = $params->MaximumPrizePaymentAmount;
    $Document = $params->Document;
    $Note = $params->Note;

    $IPIdentification = $params->IPIdentification;
    $IPIdentification = ($IPIdentification == "S") ? "1" : "0";
    if ($Subconcessionaire == "") {
        $Subconcessionaire = '0';
    }

    if ($Concessionaire == "") {
        $Concessionaire = '0';
    }

    if ($Subconcessionaire2 == "") {
        $Subconcessionaire2 = '0';
    }

    /*Procesa y valida parámetros relacionados con el usuario y su configuración.*/
    $DragNegatives = $params->DragNegatives;
    $DragNegatives = ($DragNegatives == 'S') ? '1' : '0';
// Valores por defecto no requeridos

    $Pinagent = "N";


    /* Cambiamos a valores de base de datos */
    $CanReceipt = ($CanReceipt == "S") ? "S" : "N";
    $CanDeposit = ($CanDeposit == "S") ? "S" : "N";
    $CanActivateRegister = ($CanActivateRegister == "S") ? "S" : "N";
    $Lockedsales = ($Lockedsales == "S") ? "S" : "N";


    $BetShopOwn = ($BetShopOwn == "N") ? "N" : "S";


    $Id = $params->Id;

    if ($Id == "") {
        $Id = $params->id;
    }

    $Usuario = new Usuario($Id);


    $UsuarioPerfil = new UsuarioPerfil($Id);

    $ConfigurationEnvironment = new ConfigurationEnvironment();


    if ($UsuarioPerfil->perfilId == 'USUONLINE') {
        throw new Exception('Permiso denegado', 100035);

    } elseif (in_array($UsuarioPerfil->perfilId, array('PUNTOVENTA', 'CAJERO'))) {
        throw new Exception('Permiso denegado', 100035);

    } elseif (in_array($UsuarioPerfil->perfilId, array('CONCESIONARIO', 'CONCESIONARIO2', 'CONCESIONARIO3', 'AFILIADOR'))) {

        $permission = $ConfigurationEnvironment->checkUserPermission('Agent/UpdateAgent', $_SESSION['win_perfil'], $_SESSION['usuario'], 'agentListManagement');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    } else {
        // Lanzar una excepción si se niega el permiso
        throw new Exception('Permiso denegado', 100035);

    }

    // Crear una nueva instancia de UsuarioPremiomax con el ID proporcionado
    $UsuarioPremiomax = new UsuarioPremiomax($Id);

// Crear una nueva instancia de UsuarioPerfil con el ID proporcionado
    $UsuarioPerfil = new UsuarioPerfil($Id);

    $Concesionario = new Concesionario($Id, '0');

    $Concesionario2 = new Concesionario($Id, '0');

    try {
        // Intentar crear una nueva instancia de PuntoVenta con un nombre vacío y el ID proporcionado
        $PuntoVenta = new PuntoVenta("", $Id);

    } catch (Exception $e) {
// Manejar excepciones si la creación de PuntoVenta falla
    }

// Crear una nueva instancia de UsuarioConfig con el ID proporcionado
    $UsuarioConfig = new UsuarioConfig($Id);

// Inicializar variables de cambios a false
    $UsuarioPremiomaxCambios = false;
    $PuntoVentaCambios = false;
    $UsuarioCambios = false;
    $UsuarioConfigCambios = false;
    $ConcesionarioCambios = false;
    $UsuarioPerfilCambios = false;

// Verificar si el nombre de contacto en PuntoVenta es diferente del nombre del manager
    if ($PuntoVenta->getNombreContacto() != $ManagerName) {

        // Agregar los cambios al array de cambios si el nombre de contacto ha cambiado
        array_push(
            $cambiosG,
            array(
                'field' => 'nombreContacto',
                'old' => $PuntoVenta->nombreContacto,
                'new' => $ManagerName
            )
        );

        // Actualizar el nombre de contacto en PuntoVenta
        $PuntoVenta->nombreContacto = $ManagerName;

        // Marcar que se han realizado cambios en PuntoVenta
        $PuntoVentaCambios = true;
    }

    // Verifica si la descripción del Punto de Venta es diferente de la proporcionada
    if ($PuntoVenta->getDescripcion() != $Description) {
        $PuntoVenta->descripcion = $Description;

        $PuntoVentaCambios = true;
    }

    // Verifica si el teléfono del Punto de Venta es diferente del proporcionado
    if ($PuntoVenta->getTelefono() != $Phone) {

        array_push(
            $cambiosG,
            array(
                'field' => 'telefono',
                'old' => $PuntoVenta->telefono,
                'new' => $Phone
            )
        );

        // Actualiza el teléfono del Punto de Venta
        $PuntoVenta->telefono = $Phone;

        // Marca que hubo cambios en el Punto de Venta
        $PuntoVentaCambios = true;
    }

    // Verifica si el email del Punto de Venta es diferente del proporcionado
    if ($PuntoVenta->getEmail() != $Email) {

        array_push(
            $cambiosG,
            array(
                'field' => 'email',
                'old' => $PuntoVenta->email,
                'new' => $Email
            )
        );

        $PuntoVenta->email = $Email;

        $PuntoVentaCambios = true;
    }

    if ($PuntoVenta->getDireccion() != $Address) {

        array_push(
            $cambiosG,
            array(
                'field' => 'direccion',
                'old' => $PuntoVenta->direccion,
                'new' => $Address
            )
        );
        $PuntoVenta->direccion = $Address;

        $PuntoVentaCambios = true;
    }

    if ($PuntoVenta->getBarrio() != $District) {

        array_push(
            $cambiosG,
            array(
                'field' => 'barrio',
                'old' => $PuntoVenta->barrio,
                'new' => $District
            )
        );
        $PuntoVenta->barrio = $District;

        $PuntoVentaCambios = true;
    }


    if ($Usuario->nombre != $Name) {

        // Agrega el cambio de nombre a la lista de cambios
        array_push(
            $cambiosG,
            array(
                'field' => 'nombre', // Campo que se está modificando
                'old' => $Usuario->nombre, // Valor antiguo del nombre
                'new' => $Name // Nuevo valor del nombre
            )
        );
        $Usuario->nombre = $Name; // Actualiza el nombre del usuario
        $UsuarioCambios = true; // Marca que hubo cambios en el usuario
    }


// Comprueba si el atributo 'arrastraNegativo' del objeto $Usuario es diferente de $DragNegatives y si $DragNegatives no está vacío
    if ($Usuario->arrastraNegativo != $DragNegatives && $DragNegatives != "") {

        array_push(
            $cambiosG,
            array(
                'field' => 'arrastraNegativo',
                'old' => $Usuario->arrastraNegativo,
                'new' => $DragNegatives
            )
        );
        $Usuario->arrastraNegativo = $DragNegatives;
        $UsuarioCambios = true;
    }

    if ($Usuario->bloqueoVentas != $Lockedsales && $Lockedsales != "") {

        array_push(
            $cambiosG,
            array(
                'field' => 'bloqueoVentas',
                'old' => $Usuario->bloqueoVentas,
                'new' => $Lockedsales
            )
        );
        $Usuario->bloqueoVentas = $Lockedsales;
        $UsuarioCambios = true;
    }

// Comprueba si el 'usupadre2Id' del objeto $Concesionario es diferente de $Subconcessionaire
    if ($Concesionario->getUsupadre2Id() != $Subconcessionaire) {
        if ($Subconcessionaire != '' && $Subconcessionaire != '0') {
            $UsuarioSub = new Usuario($Subconcessionaire);
        }

        array_push(
            $cambiosG,
            array(
                'field' => 'usupadre2Id',
                'old' => $Concesionario2->getUsupadre2Id(),
                'new' => $Subconcessionaire
            )
        );

        // Actualiza el 'usupadre2Id' del objeto $Concesionario2
        $Concesionario2->setUsupadre2Id($Subconcessionaire);
        $ConcesionarioCambios = true;
    }

    if ($Concesionario->getUsupadre3Id() != $Subconcessionaire2) {
        if ($Subconcessionaire2 != '' && $Subconcessionaire2 != '0') {
            $UsuarioSub = new Usuario($Subconcessionaire2);
        }
        array_push(
            $cambiosG,
            array(
                'field' => 'usupadre23Id',
                'old' => $Concesionario2->getUsupadre3Id(),
                'new' => $Subconcessionaire2
            )
        );

        // Establece el nuevo ID del padre del concesionario
        $Concesionario2->setusupadre3Id($Subconcessionaire2);
        $ConcesionarioCambios = true;
    }

// Verifica si el ID del padre del concesionario es diferente del concesionario
    if ($Concesionario->getUsupadreId() != $Concessionaire) {
        if ($Concessionaire != '' && $Concessionaire != '0') {
            $UsuarioConce = new Usuario($Concessionaire);
        }
        array_push(
            $cambiosG,
            array(
                'field' => 'usupadre23Id',
                'old' => $Concesionario2->getUsupadreId(),
                'new' => $Concessionaire
            )
        );

// Establece el nuevo ID del padre del concesionario
        $Concesionario2->setUsupadreId($Concessionaire);
        $ConcesionarioCambios = true;
    }

    if ($ConcesionarioCambios) {

        if ($Usuario->mandante == 8 && $_SESSION['usuario'] != 449 && $_SESSION['usuario'] != 2267885 && $_SESSION['usuario'] != 2725463) {
            throw new Exception("Inusual Detected", "11");
        }
        if ($Concesionario2->getUsupadreId() != "" && $Concesionario2->getUsupadre2Id() == "" && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO' && $UsuarioPerfil->getPerfilId() != 'PUNTOVENTA') {
            $UsuarioPerfil->setPerfilId('CONCESIONARIO');
            $UsuarioPerfilCambios = true;

        }

        // Cambia el perfil del usuario a 'CONCESIONARIO2' si cumple con ciertas condiciones
        if ($Concesionario2->getUsupadre2Id() != "" && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO2' && $UsuarioPerfil->getPerfilId() != 'PUNTOVENTA') {
            $UsuarioPerfil->setPerfilId('CONCESIONARIO2');
            $UsuarioPerfilCambios = true;
        }

// Cambia el perfil del usuario a 'CONCESIONARIO3' si cumple con ciertas condiciones
        if ($Concesionario2->getUsupadre3Id() != "" && $UsuarioPerfil->getPerfilId() != 'CONCESIONARIO3' && $UsuarioPerfil->getPerfilId() != 'PUNTOVENTA') {
            $UsuarioPerfil->setPerfilId('CONCESIONARIO3');
            $UsuarioPerfilCambios = true;
        }

        // Cambia el estado del concesionario a 'I'
        $Concesionario->estado = 'I';

        // Crea una instancia de ConcesionarioMySqlDAO y actualiza el concesionario
        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $ConcesionarioMySqlDAO->update($Concesionario);
        $Transaction = $ConcesionarioMySqlDAO->getTransaction();

        $ConcesionarioMySqlDAO2 = new ConcesionarioMySqlDAO($Transaction);
        $ConcesionarioMySqlDAO2->insert($Concesionario2);
        $Transaction->commit();

    }


    // Verificamos si el usuario en la sesión es '98007'
    if ($_SESSION['usuario'] == '98007') {
        if ($Login != $Usuario->login) {

            // Agregamos un cambio al registro de cambios
            array_push(
                $cambiosG,
                array(
                    'field' => 'usupadre23Id', // Campo que ha cambiado
                    'old' => $Usuario->login, // Valor antiguo
                    'new' => $Login // Nuevo valor
                )
            );

            // Actualizamos el login del usuario
            $Usuario->login = $Login;
            /* Verificamos si existe el email para el partner */
            $checkLogin = $Usuario->exitsLogin();
            if ($checkLogin) {
                throw new Exception("Inusual Detected", "11");

            }

            // Marcamos que hubo cambios en el usuario
            $UsuarioCambios = true;

        }
    }

    // Si hay cambios en el perfil del usuario, actualizamos en la base de datos
    if ($UsuarioPerfilCambios) {

        $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
        $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);
        $UsuarioPerfilMySqlDAO->getTransaction()->commit();

    }

    // Comprobamos si la cédula del punto de venta ha cambiado
    if ($PuntoVenta->cedula != $Document && $Document != '' && $Document != null) {
        $PuntoVenta->cedula = $Document; // Actualizamos la cédula
        $PuntoVentaCambios = true; // Marcamos que hubo cambios
    }

    if ($IPIdentification != '' && $IPIdentification != null && $PuntoVenta->identificacionIp != $IPIdentification) {
        $PuntoVenta->identificacionIp = $IPIdentification;
        $PuntoVentaCambios = true;
    }


    if ($Usuario->observ != $Note && $Note != '') {

        $Usuario->observ = $Note;
        $UsuarioCambios = true;
    }


    if ($PuntoVentaCambios) {
        // Crea una instancia del DAO de Punto de Venta
        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();
        $PuntoVentaMySqlDAO->update($PuntoVenta);
        $PuntoVentaMySqlDAO->getTransaction()->commit();

    }
    if ($UsuarioCambios) {
        $Usuario->fechaActualizacion = date('Y-m-d H:i:s');

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $UsuarioMySqlDAO->update($Usuario);
        $UsuarioMySqlDAO->getTransaction()->commit();

    }

    // Intenta registrar los cambios generales
    try {
        foreach ($cambiosG as $item) {

            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->setUsuarioIp("");

            $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario2"]);
            $AuditoriaGeneral->setUsuarioSolicitaIp($Global_IP);

            $AuditoriaGeneral->setTipo("agent_edit");
            $AuditoriaGeneral->setEstado("A");
            $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
            $AuditoriaGeneral->setValorAntes($item['old']);
            $AuditoriaGeneral->setValorDespues($item['new']);
            $AuditoriaGeneral->setData(json_encode($params));
            $AuditoriaGeneral->setCampo($item['field']);
            $AuditoriaGeneral->setData(json_encode($params));

            $AuditoriaGeneral->setUsucreaId(0);
            $AuditoriaGeneralMysqlDao = new AuditoriaGeneralMySqlDAO();
            $AuditoriaGeneralMysqlDao->insert($AuditoriaGeneral);
            $AuditoriaGeneralMysqlDao->getTransaction()->commit();
        }
    } catch (Exception $e) {
        // Manejo de excepciones vacío
    }


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


} catch (Exception $e) {
    /*Manejo de excepciones*/

    throw ($e);
}
