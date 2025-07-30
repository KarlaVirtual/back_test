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
 * Guarda o actualiza la asociación de proveedores con un mandante específico
 * 
 * Este endpoint permite activar o desactivar proveedores para un mandante dado:
 * - Procesa listas de proveedores a excluir e incluir
 * - Actualiza el estado de los proveedores (activo/inactivo)
 * - Registra auditoría de los cambios realizados
 * 
 * @param object $params Parámetros de entrada
 * @param string $params->Partner ID del mandante
 * @param string $params->ExcludedProvidersList Lista de IDs de proveedores a excluir (separados por coma)
 * @param string $params->IncludedProvidersList Lista de IDs de proveedores a incluir (separados por coma)
 * @param string $params->Note Nota o comentario del cambio
 *
 *
 *
 * @return array Estructura de respuesta JSON con el siguiente formato:
 * {
 *   "HasError": boolean,      // Indica si hubo error en la operación
 *   "AlertType": string,      // Tipo de alerta (success/error)
 *   "AlertMessage": string,   // Mensaje descriptivo
 *   "ModelErrors": array,     // Array de errores del modelo
 *   "Data": {                 // Datos de respuesta
 *     "insertOrUpdate": boolean  // Indica si se realizaron cambios
 *   }
 * }
 * @throws Exception Si ocurre un error durante el proceso
 * @throws Exception Si la nota es vacía o nula
 */

try {


    /* Se inicializan variables y se procesan listas de proveedores excluidos e incluidos. */
    $insertOrUpdate=false;

    $Partner = $params->Partner;

    $ExcludedProvidersList = ($params->ExcludedProvidersList != "") ? explode(",", $params->ExcludedProvidersList) : array();
    $IncludedProvidersList = ($params->IncludedProvidersList != "") ? explode(",", $params->IncludedProvidersList) : array();
    $Note = $params->Note;



    if($Note == '' || $Note == null) {    /*Verificamos si nota es vacio o es null*/
        throw new exception("La observacion es obligatoria", 300160); /*lanzamos la excepcion en caso de nota ser vacio o null */
    }


    /* Obtiene la dirección IP del usuario desde cabeceras HTTP o conexión remota. */
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

    if ($Partner != '') {


        if (oldCount($ExcludedProvidersList) > 0) {



    /* Se crea un objeto DAO y se obtiene una transacción de la base de datos. */
            $ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO();
            $Transaction = $ProveedorMandanteMySqlDAO->getTransaction();

            foreach ($ExcludedProvidersList as $key => $value) {
                try {

                    /* actualiza el estado de un objeto ProveedorMandante e inicia auditoría. */
                    $ProveedorMandante = new ProveedorMandante($value, $Partner);
                    $ProveedorMandante->estado = 'I';
                    $ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO($Transaction);
                    $ProveedorMandanteMySqlDAO->update($ProveedorMandante);
                    $insertOrUpdate=true;

                    $AuditoriaGeneral = new AuditoriaGeneral();

                    /* establece información de usuario y IP en auditoría general. */
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);

                    /* Código para registrar auditoría de desactivación de proveedor en un sistema. */
                    $AuditoriaGeneral->setTipo("DESACTIVACIONDEPROVEEDORMANDANTE");
                    $AuditoriaGeneral->setValorAntes("A");
                    $AuditoriaGeneral->setValorDespues("I");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);
                    $AuditoriaGeneral->setEstado("A");

                    /* Se registra una auditoría general asociada a un proveedor mandante. */
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion($Note);
                    $AuditoriaGeneral->setData($ProveedorMandante->provmandanteId);

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

                } catch (Exception $e) {
                    /* Captura excepciones, verifica el código de error y ejecuta acciones específicas. */

                    if ($e->getCode() == "27") {

                    }

                }

            }

            
            /* finaliza una transacción, asegurando que todos los cambios se guarden. */
            $Transaction->commit();

        }

        if (oldCount($IncludedProvidersList) > 0) {

            /* Código crea instancia de DAO y obtiene una transacción de base de datos. */
            $ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO();
            $Transaction = $ProveedorMandanteMySqlDAO->getTransaction();

            foreach ($IncludedProvidersList as $key => $value) {
                try {

                    /* Actualiza el estado de un proveedor utilizando un DAO en una transacción. */
                    $ProveedorMandante = new ProveedorMandante($value, $Partner);

                    $ProveedorMandante->estado = 'A';
                    $ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO($Transaction);
                    $ProveedorMandanteMySqlDAO->update($ProveedorMandante);
                    $insertOrUpdate=true;


                    /* Se inicializa un objeto de auditoría y se configuran sus propiedades. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);

                    /* configura parámetros para auditar la activación de un proveedor en el sistema. */
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("ACTIVACIONDEPROVEEDORMANDANTE");
                    $AuditoriaGeneral->setValorAntes("I");
                    $AuditoriaGeneral->setValorDespues("A");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);

                    /* Código para configurar y registrar auditoría, incluyendo estado, dispositivo y observación. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion($Note);
                    $AuditoriaGeneral->setData($ProveedorMandante->provmandanteId);

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

                } catch (Exception $e) {
                    if ($e->getCode() == "27") {


                        /* Se crea un objeto ProveedorMandante y se inicializan sus propiedades. */
                        $ProveedorMandante = new ProveedorMandante();

                        $ProveedorMandante->proveedorId = $value;
                        $ProveedorMandante->mandante = $Partner;

                        $ProveedorMandante->estado = 'A';


                        /* Se establecen propiedades de un objeto ProveedorMandante con valores iniciales. */
                        $ProveedorMandante->verifica = 'I';

                        $ProveedorMandante->filtroPais = 'I';

                        $ProveedorMandante->max = 0;

                        $ProveedorMandante->min = 0;


                        /* Asignación de valores y creación de un objeto DAO para gestionar proveedores. */
                        $ProveedorMandante->detalle = '';

                        $ProveedorMandante->usucreaId = $_SESSION["usuario"];
                        $ProveedorMandante->usumodifId = $_SESSION["usuario"];


                        $ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO($Transaction);
                        
                        /* Inserta un nuevo registro de proveedor en la base de datos y marca como actualizado. */
                        $ProveedorMandanteMySqlDAO->insert($ProveedorMandante);
                        $insertOrUpdate=true;


                    }

                }

            }

            
            /* finaliza una transacción en una base de datos, guardando cambios realizados. */
            $Transaction->commit();


        }


        /* configura una respuesta con éxito y sin errores, incluyendo un mensaje. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    } else {
        /* maneja errores, configurando respuesta de éxito y mensaje correspondiente. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

    }
} catch (Exception $e) {
    /* Captura excepciones en PHP y muestra información del error en formato legible. */

    print_r($e);
}