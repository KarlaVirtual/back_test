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
 * Menus/GetMenuUser
 *
 * Obtener menú del usuario.
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la operación:
 * @param int $params->id Identificador del usuario.
 * @param string $params->Menu Tipo de menú solicitado.
 * 
 * @return array $response Respuesta estructurada con las siguientes claves:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., success, danger).
 * - AlertMessage (string): Mensaje descriptivo de la operación.
 * - ModelErrors (array): Lista de errores de validación.
 * - data (array): Menús disponibles para el usuario.
 */


/* Código verifica sesión de usuario y genera respuesta en caso de error de logueo. */
$id = $params->id;

//Verifica si ya hubo un logueo
if (!$_SESSION['logueado']) {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
    $response["ModelErrors"] = [];

    $response["data"] = array();

} else {


    try {

        /* asigna un menú y obtiene el perfil de un usuario específico. */
        $Menu = $params->Menu;

        $UsuarioPerfil = new UsuarioPerfil($id);
        $Perfil_id = $UsuarioPerfil->perfilId;
        $menus = "";


        switch ($Menu) {
            case "betShop":

                /* decodifica un JSON que define un menú con opciones para gestión. */
                $menus = json_decode('[
								{ "id":"betShopManagement.informationBetShop", "value": "Información" },
								{ "id":"betShopManagement.cashier.betShopCashier", "value": "Cajeros" },
								{ "id":"betShopManagement.comissions/betShopManagement.comissions.comissions", "value": "Comisiones" },
								{ "id":"betShopManagement.reportscustomerBetShop/betShopManagement.depositReportBetShop", "value": "Reportes" },
								{ "id":"betShopManagement.configurationBetShop/betShopManagement.configuration.changePassword", "value": "Configuración" }
							]');


                /* decodifica un JSON que contiene información sobre diferentes secciones de un sistema. */
                $menus = json_decode('[
								{ "id":"betShopManagement.informationBetShop", "value": "Información" },{
 "id": "betShopManagement.financescustomerBetShop/betShopManagement.financial.bankAccountsBetShop", "value": "Finanzas"
},
								{ "id":"betShopManagement.cashier.betShopCashier", "value": "Cajeros" },
								{ "id":"betShopManagement.comissions/betShopManagement.comissions.comissions", "value": "Comisiones" },
								{ "id":"betShopManagement.reportscustomerBetShop/betShopManagement.depositReportBetShop", "value": "Reportes" },
								{ "id":"betShopManagement.configurationBetShop/betShopManagement.configuration.changePassword", "value": "Configuración" }
							]');


                /* asigna menús específicos basados en el perfil de usuario "CAJERO". */
                switch ($Perfil_id) {

                    case "CAJERO":

                        $menus = json_decode('[
								{ "id":"betShopManagement.informationBetShop", "value": "Información" },
								{ "id":"betShopManagement.reportscustomerBetShop/betShopManagement.depositReportBetShop", "value": "Reportes" },
								{ "id":"betShopManagement.configurationBetShop/betShopManagement.configuration.changePassword", "value": "Configuración" }
							]');
                        break;
                }

                break;

            case "AgentList":

                //								{ "id":"agent.financescustomerAgentList/agent.financial.comisiones", "value":_("Finanzas"), },

                /* decodifica un JSON en un array de menús en PHP. */
                $menus = json_decode('[
								{ "id":"agent.informationAgentList", "value":"Información" },
								{ "id":"agent.reportscustomerAgentList/agent.depositReportAgentList", "value":"Reportes" },
								{ "id":"agent.configurationAgentList/agent.configuration.changePassword", "value":"Configuración" },
								

							]');
//{ "id":"agent.statisticsAgentList", "value":"Estadisticas" },


                /* Decodifica un JSON que contiene un menú con distintas opciones para agentes. */
                $menus = json_decode('[
								{ "id":"agent.informationAgentList", "value":"Información" },
								{ "id":"agent.comissions/agent.comissions.comissions", "value":"Comisiones" },
								{ "id":"agent.reportscustomerAgentList/agent.reports.userAfilliatesReport", "value":"Reportes" },
								{ "id":"agent.configurationAgentList/agent.configuration.changePassword", "value":"Configuración" }
						

							]');
                //								{ "id":"agent.statisticsAgentList", "value":"Estadisticas" }
                break;


            default:

                /* Carga un menú en formato JSON con identidades y valores para una aplicación. */
                $menus = json_decode('[
								{ "id":"agent.informationAgentList", "value":"Información" },
								{ "id":"agent.reportscustomerAgentList/agent.depositReportAgentList", "value":"Reportes" },
								{ "id":"agent.configurationAgentList/agent.configuration.changePassword", "value":"Configuración" }

							]');
                //								{ "id":"agent.statisticsAgentList", "value":"Estadisticas" },
                break;
        }


        /* Código configura una respuesta exitosa sin errores, incluyendo datos de menús. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["data"] = $menus;


    } catch (Exception $e) {
        /* Manejo de excepciones: captura errores y genera una respuesta estructurada de error. */


        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
        $response["ModelErrors"] = [];

        $response["Data"] = array();

    }

}