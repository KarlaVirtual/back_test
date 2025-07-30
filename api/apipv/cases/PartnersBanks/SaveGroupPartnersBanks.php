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
use Backend\dto\BancoMandante;
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
use Backend\mysql\BancoMySqlDAOMySqlDAO;
use Backend\mysql\BancoMandanteMySqlDAO;
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
 * PartnersBanks/SaveGroupPratnersBanks
 *
 * Guardar bancos asociados a partner y pais
 *
 * @param no
 *
 * @return no
 * {"HasError":boolean,"AlertType": string,"AlertMessage": string,"ModelErros": string,"Data": array}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

try {
    //Verificamos data enviada por Frontend

    /* Extrae información de parámetros para configurar exclusiones de bancos según país y socio. */
    $Partner = $params->Partner;
    $CountrySelect = $params->CountrySelect;

    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

    //Inicializamos listas para la activacion o inactivacion del banco para el partner y pais
    $ExcludedBanksList = ($params->ExcludedBanksList != "") ? explode(",", $params->ExcludedBanksList) : array();

    /* Separa una lista de bancos y la inicializa; además, define una variable booleana. */
    $IncludedBanksList = ($params->IncludedBanksList != "") ? explode(",", $params->IncludedBanksList) : array();

    $insertOrUpdate = false;

    // Si sabemos a que partner y pais se van a enlazar
    if ($Partner != '' && $CountrySelect != '') {


        /* Verifica si $Partner está en la lista; lanza excepción si no lo está. */
        if (!in_array($Partner, explode(',', $_SESSION["mandanteLista"]))) {
            throw new Exception("Inusual Detected", "11");
        }

        //Si tenemos bancos a desactivar
        if (oldCount($ExcludedBanksList) > 0) {

            // Instanciamos la clase y cambios el estado en la tabla banco mandante a 'I' (Inactivo)
            // ademas de que creamos data de seguimiento en la tabla auditoria general

            /* Se crea un objeto DAO y se obtiene una transacción de la base de datos. */
            $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO();
            $Transaction = $BancoMandanteMySqlDAO->getTransaction();

            foreach ($ExcludedBanksList as $key => $value) {
                try {

                    /* Actualiza el estado de un objeto BancoMandante en la base de datos. */
                    $BancoMandante = new BancoMandante($value, $Partner, '', $CountrySelect);
                    $estadoAntes = $BancoMandante->estado;
                    $BancoMandante->estado = 'I';
                    $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO($Transaction);
                    $BancoMandanteMySqlDAO->update($BancoMandante);
                    $insertOrUpdate = true;


                    /* Se crea una auditoría general con información de usuario e IP. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);

                    /* configura una auditoría para la desactivación de un banco. */
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("DESACTIVACIONDEBANCO");
                    $AuditoriaGeneral->setValorAntes($estadoAntes);
                    $AuditoriaGeneral->setValorDespues("I");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);

                    /* Se establece el estado, dispositivo y observación en Auditoría General con MySQL. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion($BancoMandante->bancomandanteId);


                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);

                    /* Inserta un registro de auditoría general en la base de datos MySQL. */
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


                } catch (Exception $e) {
                    /* captura excepciones y verifica si el código de error es "27". */

                    if ($e->getCode() == "27") {

                    }

                }

            }
            //Generamos commit de la SQL

            /* Confirma y guarda permanentemente los cambios realizados en la base de datos. */
            $Transaction->commit();

        }

        //Si tenemos bancos a activar
        if (oldCount($IncludedBanksList) > 0) {

            // Instanciamos la clase y cambios el estado en la tabla banco mandante a 'A' (Activo)
            // ademas de que creamos data de seguimiento en la tabla auditoria general

            /* Se crea un objeto DAO para acceder a transacciones en la base de datos MySQL. */
            $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO();
            $Transaction = $BancoMandanteMySqlDAO->getTransaction();

            foreach ($IncludedBanksList as $key => $value) {
                try {

                    /* actualiza el estado de un objeto BancoMandante en la base de datos. */
                    $BancoMandante = new BancoMandante($value, $Partner, '', $CountrySelect);
                    $estadoAntes = $BancoMandante->estado;
                    $BancoMandante->estado = 'A';
                    $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO($Transaction);
                    $BancoMandanteMySqlDAO->update($BancoMandante);
                    $insertOrUpdate = true;


                    /* Código que inicializa una auditoría general con información del usuario y su IP. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);

                    /* Configura una auditoría de estado para la activación de un banco en el sistema. */
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("ACTIVACIONDEBANCO");
                    $AuditoriaGeneral->setValorAntes($estadoAntes);
                    $AuditoriaGeneral->setValorDespues("A");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);

                    /* Configura y registra una auditoría en la base de datos utilizando MySQL. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion($BancoMandante->bancomandanteId);

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


                } catch (Exception $e) {

                    // Si en la tabla banco mandante no existe el banco a activar, generamos un insert para añadirlño en la tabla
                    // ademas de que creamos data de seguimiento en la tabla auditoria general
                    if ($e->getCode() == "27") {


                        /* Inicializa un objeto BancoMandante con datos de un socio y usuario. */
                        $BancoMandante = new BancoMandante();

                        $BancoMandante->mandante = $Partner;
                        $BancoMandante->bancoId = $value;
                        $BancoMandante->estado = 'A';
                        $BancoMandante->usucreaId = $_SESSION["usuario"];

                        /* Asignación de variables y creación de auditoría con información del usuario y su IP. */
                        $BancoMandante->usumodifId = $_SESSION["usuario"];
                        $BancoMandante->paisId = $CountrySelect;

                        $AuditoriaGeneral = new AuditoriaGeneral();
                        $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuarioIp($ip);

                        /* Establece parámetros de auditoría relacionados con la activación de un banco. */
                        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId(0);
                        $AuditoriaGeneral->setUsuarioaprobarIp(0);
                        $AuditoriaGeneral->setTipo("ACTIVACIONDEBANCO");
                        $AuditoriaGeneral->setValorAntes("I");

                        /* Se configura un objeto AuditoriaGeneral con diversos atributos para registrar acciones. */
                        $AuditoriaGeneral->setValorDespues("A");
                        $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsumodifId(0);
                        $AuditoriaGeneral->setEstado("A");
                        $AuditoriaGeneral->setDispositivo(0);
                        $AuditoriaGeneral->setObservacion($BancoMandante->bancomandanteId);


                        /* Se insertan registros en base de datos usando dos DAOs y una transacción. */
                        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

                        $BancoMandanteMySqlDAO = new BancoMandanteMySqlDAO($Transaction);
                        $BancoMandanteMySqlDAO->insert($BancoMandante);
                        $insertOrUpdate = true;


                    }

                }

            }


            /* finaliza una transacción, confirmando todos los cambios realizados. */
            $Transaction->commit();


        }

//        if($insertOrUpdate){
//            $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO','',$Partner,$CountrySelect);
//            $CMSProveedor->updateDatabaseCasino();
//        }


        /* establece una respuesta exitosa con mensajes y datos vacíos. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    } else {
        /* maneja una respuesta sin errores, estableciendo mensajes y tipos de alerta. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

    }
} catch (Exception $e) {
    /* maneja excepciones en PHP, capturando errores sin imprimir detalles. */

//    print_r($e);
}
