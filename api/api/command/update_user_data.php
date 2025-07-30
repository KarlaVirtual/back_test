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
 * command/update_user_data
 *
 * Actualizar un usuario en la plataforma discriminado por plataforma
 *
 * @param string $CityId : id de la ciudad del usuario a actualizar
 * @param string $RegionId : Region usuario a actualizar
 * @param string $Cedula :  usuario DNI
 * @param string $Celular : Numero de celular del usuario
 * @param string $Direccion : direccion del usuario a actualizar
 * @param string $ciudad_id : ciudad id del usuario a actualizar
 * @param string $ciudad_name : nombre de la ciudad del usuario
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Contiene modal para lanzar en caso de error
 *
 * Objeto en caso de error:
 *
 *  $response["HasError"] = true;
 *  $response["AlertType"] = "error";
 *  $response["AlertMessage"] = " ";
 *  $response["ModelErrors"] = [];
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Extrae e inicializa variables de un objeto JSON con datos de ubicación y contacto. */
$CityId = $json->params->values->city_id->Id;
$RegionId = $json->params->values->department_id->Id;
////Actualizacion parametros sivarbet
$Cedula = $json->params->values->docnumber;
$Celular = $json->params->values->phone;
$Direccion = $json->params->values->direccion;

/* Extrae información de un JSON y obtiene la IP del usuario. */
$ciudad_id = $json->params->values->city_id->Id;
$ciudad_name = $json->params->values->city_id->Name;
/////////////////////////////
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* extrae el primer IP, obtiene el mandante y crea un registro. */
$ip = explode(",", $ip)[0];

$mandante = $UsuarioMandante->mandante;

$usuarioId = $UsuarioMandante->getUsuarioMandante();


$Registro = new Registro('', $usuarioId);

/* Se crea una instancia de la clase RegistroMySqlDAO para gestionar registros en MySQL. */
$RegistroMySqlDAO = new RegistroMySqlDAO();

//ACTUALIZACION DE DATOS PARA SIVARBET MANDANTE 20 Y PANIPLAY MANDANTE 23
if ($mandante == 20 || $mandante == 23) {
    /**
     * Se obtiene la transacción actual desde el objeto RegistroMySqlDAO.
     * Se verifica si la cédula no está vacía y si es diferente de la registrada.
     * Si es así, se crea un log de usuario para el cambio de cédula.
     */
    $Transaction = $RegistroMySqlDAO->getTransaction();
    if (!empty($Cedula) && $Registro->cedula !== $Cedula) {

        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($usuarioId);
        $UsuarioLog->setUsuarioIp('');

        $UsuarioLog->setUsuariosolicitaId($usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($ip);

        $UsuarioLog->setTipo("USUCEDULA");
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($Registro->cedula);
        $UsuarioLog->setValorDespues($Cedula);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $Usuariolog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $Usuariolog2MySqlDAO->insert($UsuarioLog);
        $Registro->cedula = $Cedula;
    }
    /**
     * Se verifica si el celular no está vacío y si es diferente del registrado.
     * Si es así, se crea un log de usuario para el cambio de celular.
     */
    if (!empty($Celular) && $Registro->celular !== $Celular) {

        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($usuarioId);
        $UsuarioLog->setUsuarioIp('');

        $UsuarioLog->setUsuariosolicitaId($usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($ip);

        $UsuarioLog->setTipo("USUCELULAR");
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($Registro->celular);
        $UsuarioLog->setValorDespues($Celular);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $Usuariolog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $Usuariolog2MySqlDAO->insert($UsuarioLog);
        $Registro->celular = $Celular;
    }
    /**
     * Verifica si la dirección no está vacía y si ha cambiado.
     * Si es así, registra el cambio en la tabla de logs de usuario.
     */
    if (!empty($Direccion) && $Registro->direccion !== $Direccion) {

        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($usuarioId);
        $UsuarioLog->setUsuarioIp('');

        $UsuarioLog->setUsuariosolicitaId($usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($ip);

        $UsuarioLog->setTipo("USUDIRECCION");
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($Registro->direccion);
        $UsuarioLog->setValorDespues($Direccion);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $Usuariolog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $Usuariolog2MySqlDAO->insert($UsuarioLog);
        $Registro->direccion = $Direccion;

    }
    /**
     * Verifica si el ID de la ciudad no está vacío y si ha cambiado.
     * Si es así, registra el cambio en la tabla de logs de usuario.
     */
    if (!empty($ciudad_id) && $Registro->ciudadId !== $ciudad_id) {

        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($usuarioId);
        $UsuarioLog->setUsuarioIp('');

        $UsuarioLog->setUsuariosolicitaId($usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($ip);

        $UsuarioLog->setTipo("USUCIUDADID");
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($Registro->ciudadId);
        $UsuarioLog->setValorDespues($ciudad_id);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $Usuariolog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $Usuariolog2MySqlDAO->insert($UsuarioLog);
        $Registro->ciudadId = $ciudad_id;

        $Registro->setCiudadId($UsuarioLog->getValorDespues());

    }
    /**
     * Actualiza el registro en la base de datos y maneja la respuesta.
     *
     * En caso de que se detecte un cambio en la ciudad, se registra un log con la
     * información correspondiente. Se prepara una respuesta basada en si hubo o no error.
     */
    $RegistroMySqlDAO->update($Registro);
    $Transaction->commit();
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
} elseif ($RegionId != "" and $CityId != "" && $mandante = 18) {
    if ($CityId != $Registro->getCiudadId()) {

        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($usuarioId);
        $UsuarioLog->setUsuarioIp('');

        $UsuarioLog->setUsuariosolicitaId($usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($ip);

        $UsuarioLog->setTipo("USUCIUDADID");
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($Registro->getCiudadId());
        $UsuarioLog->setValorDespues($CityId);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $Usuariolog2MySqlDAO = new UsuarioLog2MySqlDAO();
        $Usuariolog2MySqlDAO->insert($UsuarioLog);
        $Usuariolog2MySqlDAO->getTransaction()->commit();

        $Registro->setCiudadId($UsuarioLog->getValorDespues());

        $RegistroMySqlDAO = new RegistroMySqlDAO();
        $RegistroMySqlDAO->update($Registro);
        $RegistroMySqlDAO->getTransaction()->commit();
    }


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = $msg . " - " . $ClientId;
    $response["ModelErrors"] = [];

    $response["Data"] = [];


} else {

    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = " ";
    $response["ModelErrors"] = [];
}


?>