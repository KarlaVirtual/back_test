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
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\SubproveedorTercero;
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
use Backend\mysql\SubproveedorMandanteMySqlDAO;
use Backend\mysql\SubproveedorMySqlDAO;
use Backend\mysql\SubproveedorTerceroMySqlDAO;
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
 * PartnersSubProviders/SaveGroupPartnersSubProviders
 *
 * Gestión de Subproveedores
 *
 * Este recurso permite la activación y desactivación de subproveedores asociados a un `Partner`.
 * Se registra la auditoría de cada cambio realizado en los estados de los subproveedores.
 * Si se realizan cambios, se actualiza la base de datos de casinos mediante `CMSProveedor`.
 *
 * @param object $params : Objeto con los parámetros de entrada.
 *     - *Partner* (string): Identificador del partner.
 *     - *CountrySelect* (string): País seleccionado.
 *     - *ExcludedProvidersList* (string): Lista de proveedores a excluir, separados por comas.
 *     - *IncludedProvidersList* (string): Lista de proveedores a incluir, separados por comas.
 *     - *Note* (string): Observación o comentario sobre el cambio.
 *
 *
 *
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors*  (array): Lista de errores generados, vacío si no hay errores.
 *  - *Data* (array): Contiene el resultado de la operación, vacío si no hay datos específicos a devolver.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Ocurrió un error en la operación.";
 * $response["ModelErrors"] = [$e->getMessage()];
 * ```
 *
 * @throws Exception Si ocurre un error en la actualización de datos o auditoría.
 * @throws Exception Si la nota es vacía o nula.
 *
 *
 *
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

try {


    /* maneja listas de proveedores y captura la dirección IP del usuario. */
    $insertOrUpdate = false;
    $Partner = $params->Partner;
    $CountrySelect = $params->CountrySelect;
    $ExcludedProvidersList = ($params->ExcludedProvidersList != "") ? explode(",", $params->ExcludedProvidersList) : array();
    $IncludedProvidersList = ($params->IncludedProvidersList != "") ? explode(",", $params->IncludedProvidersList) : array();
    $Note = $params->Note;


    if($Note == '' || $Note == null) { /*Se verifica si la nota o la observacion es vacia o null*/
        throw new exception("La observacion es obligatoria", 300162); /*Se lanza la excepcion al estar la nota vacia*/
    }

    $ip = !empty($_SERVER['HTTP_X_FORWADED_']) ? $_SERVER['HTTP_X_FORWADED_FOR'] : $SERVER['REMOTE_ADDR'];

    /* extrae la primera IP y define una función para detectar dispositivos móviles. */
    $ip = explode(",", $ip)[0];

    /**
     * Detecta el tipo de dispositivo basado en el User Agent.
     *
     * @param string $userAgent El User Agent del navegador del cliente.
     * @return string Devuelve 'mobile' si es un dispositivo móvil, 'desktop' de lo contrario.
     */
    function detectarTipoDispositivo($userAgent)
    {
        $dispositivosMoviles = array(
            '/iphone/i',
            '/ipod/i',
            '/ipad/i',
            '/android/i',
            '/blackberry/i',
            '/webos/i'
        );


        /* determina si el usuario utiliza un dispositivo móvil o escritorio. */
        foreach ($dispositivosMoviles as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return 'mobile';
            }
        }
        return 'desktop';
    }


    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    /* Detecta el tipo de dispositivo utilizando la información del agente de usuario. */
    $tipoDispositivo = detectarTipoDispositivo($userAgent);

    if ($Partner != '') {


        if (oldCount($ExcludedProvidersList) > 0) {


            /* Código que inicializa un DAO y obtiene una transacción asociada. */
            $SubproveedorMandanteMySqlDAO = new SubproveedorMandanteMySqlDAO();
            $Transaction = $SubproveedorMandanteMySqlDAO->getTransaction();

            foreach ($ExcludedProvidersList as $key => $value) {
                try {

                    /* Se crea y actualiza un registro de subproveedor y se inicializa auditoría. */
                    $SubproveedorMandante = new SubproveedorMandante($value, $Partner);
                    $SubproveedorMandante->estado = 'I';
                    $SubproveedorMandanteMySqlDAO = new SubproveedorMandanteMySqlDAO($Transaction);
                    $SubproveedorMandanteMySqlDAO->update($SubproveedorMandante);
                    $insertOrUpdate = true;

                    $AuditoriaGeneral = new AuditoriaGeneral();

                    /* Código que configura datos de usuario e IP para una auditoría general. */
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);

                    /* Registro de auditoría para desactivación de subproveedor, indicando cambios y usuario responsable. */
                    $AuditoriaGeneral->setTipo("DESACTIVACIONDESUBPROVEEDORMANDANTE");
                    $AuditoriaGeneral->setValorAntes("A");
                    $AuditoriaGeneral->setValorDespues("I");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);
                    $AuditoriaGeneral->setEstado("A");

                    /* Configura un dispositivo y observa, luego inserta auditoría en MySQL. */
                    $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                    $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/
                    $AuditoriaGeneral->setData($SubproveedorMandante->provmandanteId);


                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {
                    /* Captura excepciones y verifica si el código de error es "107". */

                    if ($e->getCode() == "107") {

                    }

                }

            }


            /* Confirma una transacción en una base de datos, haciendo los cambios permanentes. */
            $Transaction->commit();

        }

        if (oldCount($IncludedProvidersList) > 0) {

            /* Se crea un DAO para obtener transacciones de un subproveedor en MySQL. */
            $SubproveedorMandanteMySqlDAO = new SubproveedorMandanteMySqlDAO();
            $Transaction = $SubproveedorMandanteMySqlDAO->getTransaction();

            foreach ($IncludedProvidersList as $key => $value) {
                try {

                    /* Se actualiza estado de SubproveedorMandante y se registra auditoría general. */
                    $SubproveedorMandante = new SubproveedorMandante($value, $Partner);
                    $SubproveedorMandante->estado = 'A';
                    $SubproveedorMandanteMySqlDAO = new SubproveedorMandanteMySqlDAO($Transaction);
                    $SubproveedorMandanteMySqlDAO->update($SubproveedorMandante);
                    $insertOrUpdate = true;

                    $AuditoriaGeneral = new AuditoriaGeneral();

                    /* Código establece identificadores de usuario y IP para auditoría general. */
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);

                    /* Código para registrar una auditoría de activación de subproveedor en el sistema. */
                    $AuditoriaGeneral->setTipo("ACTIVACIONDESUBPROVEEDORMANDANTE");
                    $AuditoriaGeneral->setValorAntes("I");
                    $AuditoriaGeneral->setValorDespues("A");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);
                    $AuditoriaGeneral->setEstado("A");

                    /* Código que establece parámetros y registra auditoría en base de datos. */
                    $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                    $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/
                    $AuditoriaGeneral->setData($SubproveedorMandante->provmandanteId);

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
                } catch (Exception $e) {
                    if ($e->getCode() == "107") {


                        /* Se crea un objeto SubproveedorMandante y se asignan valores a sus propiedades. */
                        $SubproveedorMandante = new SubproveedorMandante();

                        $SubproveedorMandante->subproveedorId = $value;
                        $SubproveedorMandante->mandante = $Partner;

                        $SubproveedorMandante->estado = 'A';


                        /* Se inicializan propiedades de un objeto SubproveedorMandante con valores específicos. */
                        $SubproveedorMandante->verifica = 'I';

                        $SubproveedorMandante->filtroPais = 'I';

                        $SubproveedorMandante->max = 0;

                        $SubproveedorMandante->min = 0;


                        /* Se asignan valores a propiedades de un objeto `$SubproveedorMandante`. */
                        $SubproveedorMandante->detalle = '';

                        $SubproveedorMandante->orden = 100000;

                        $SubproveedorMandante->usucreaId = $_SESSION["usuario"];
                        $SubproveedorMandante->usumodifId = $_SESSION["usuario"];



                        $AuditoriaGeneral = new AuditoriaGeneral();

                        /* Código establece identificadores de usuario y IP para auditoría general. */
                        $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuarioIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId(0);
                        $AuditoriaGeneral->setUsuarioaprobarIp(0);

                        /* Código para registrar una auditoría de activación de subproveedor en el sistema. */
                        $AuditoriaGeneral->setTipo("ACTIVACIONDESUBPROVEEDORMANDANTE");
                        $AuditoriaGeneral->setValorAntes("I");
                        $AuditoriaGeneral->setValorDespues("A");
                        $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsumodifId(0);
                        $AuditoriaGeneral->setEstado("A");

                        /* Código que establece parámetros y registra auditoría en base de datos. */
                        $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                        $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/
                        $AuditoriaGeneral->setData($SubproveedorMandante->provmandanteId);

                        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                        $AuditoriaGeneralMySqlDAO->getTransaction()->commit();


                        /* Se crea un DAO para insertar un subproveedor en la base de datos. */
                        $SubproveedorMandanteMySqlDAO = new SubproveedorMandanteMySqlDAO($Transaction);
                        $SubproveedorMandanteMySqlDAO->insert($SubproveedorMandante);
                        $insertOrUpdate = true;


                    }

                }

            }


            /* Finaliza una transacción en la base de datos, guardando todos los cambios realizados. */
            $Transaction->commit();


        }


        /* actualiza una base de datos y genera una respuesta exitosa. */
        if ($insertOrUpdate) {
            $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO', '', $Partner, $CountrySelect);
            $CMSProveedor->updateDatabaseCasino();
        }
        $response["HasError"] = false;
        $response["AlertType"] = "success";

        /* Asignación de variables en un arreglo de respuesta para manejar errores y mensajes. */
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    } else {
        /* asigna valores a una respuesta en caso de error. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

    }
} catch (Exception $e) {
    /* Captura y muestra información sobre excepciones en PHP. */

    print_r($e);
}
