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
 * Servicio para guardar y actualizar grupos de productos asociados a socios comerciales
 * 
 * @author Desarrollador
 * @version 1.0
 * @package Backend\cases\PartnersProducts
 * 
 * @param object $params Parámetros de entrada
 * @param string $params->Partner ID del socio comercial
 * @param string $params->CountrySelect País seleccionado
 * @param string $params->ExcludedProductsList Lista de productos excluidos (separados por coma)
 * @param string $params->IncludedProductsList Lista de productos incluidos (separados por coma) 
 * @param string $params->permAdd Permisos para agregar (separados por coma)
 * @param string $params->permDelete Permisos para eliminar (separados por coma)
 * 
 * @return object Respuesta del servicio
 * @return boolean $response->success Indica si la operación fue exitosa
 * @return string $response->message Mensaje descriptivo del resultado
 * @return array $response->data Datos adicionales de la respuesta
 * @throws Exception Si ocurre un error durante el proceso
 */

try {



    /* asigna parámetros y genera listas de productos excluidos/incluidos. */
    $Partner = $params->Partner;
    $CountrySelect = $params->CountrySelect;


    $ExcludedProductsList = ($params->ExcludedProductsList != "") ? explode(",", $params->ExcludedProductsList) : array();
    $IncludedProductsList = ($params->IncludedProductsList != "") ? explode(",", $params->IncludedProductsList) : array();


    /* asigna permisos a arreglos, o arrays vacíos si están vacíos. */
    $permAdd = ($params->permAdd != "") ? explode(",", $params->permAdd) : array();
    $permDelete = ($params->permDelete != "") ? explode(",", $params->permDelete) : array();

    $insertOrUpdate=false;

    if ($Partner != '' && $CountrySelect != '') {


        if (count($ExcludedProductsList) > 0) {



            /* Actualiza el estado de productos excluidos en una base de datos MySQL. */
            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();

            foreach ($ExcludedProductsList as $key => $value) {
                try {
                    $ProductoMandante = new ProductoMandante($value, $Partner,'',$CountrySelect);

                    $ProductoMandante->estado = 'I';
                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);
                    $insertOrUpdate=true;

                } catch (Exception $e) {
                    if ($e->getCode() == "27") {

                    }

                }

            }

            
            /* Finaliza una transacción en la base de datos, guardando todos los cambios realizados. */
            $Transaction->commit();

        }

        if (count($IncludedProductsList) > 0) {

            /* Se crea una instancia de DAO y se obtiene una transacción asociada. */
            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();

            foreach ($IncludedProductsList as $key => $value) {
                
                /* intenta actualizar un producto en la base de datos. */
                try {
                    $ProductoMandante = new ProductoMandante($value, $Partner,'',$CountrySelect);

                    $ProductoMandante->estado = 'A';
                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);
                    $insertOrUpdate=true;

                } catch (Exception $e) {
                    if ($e->getCode() == "27") {


                        /* Crea una instancia de ProductoMandante y establece sus propiedades relacionadas. */
                        $ProductoMandante = new ProductoMandante();

                        $ProductoMandante->mandante = $Partner;
                        $ProductoMandante->productoId = $value;
                        $ProductoMandante->estado = 'A';
                        $ProductoMandante->verifica = 'I';

                        /* Se configuran propiedades del objeto ProductoMandante para filtrar y establecer límites. */
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->max = 0;
                        $ProductoMandante->min = 0;
                        $ProductoMandante->detalle = '';
                        $ProductoMandante->orden = 100000;

                        /* Se asignan valores a las propiedades de un objeto ProductoMandante. */
                        $ProductoMandante->numFila = 1;
                        $ProductoMandante->numColumna = 1;
                        $ProductoMandante->ordenDestacado = 0;
                        $ProductoMandante->usucreaId = $_SESSION["usuario"];
                        $ProductoMandante->usumodifId = $_SESSION["usuario"];
                        $ProductoMandante->paisId = $CountrySelect;



                        /* Se inserta un producto mandante en la base de datos usando DAO. */
                        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                        $ProductoMandanteMySqlDAO->insert($ProductoMandante);
                        $insertOrUpdate=true;


                    }

                }

            }

            
            /* Confirma y guarda cambios en la transacción actual en una base de datos. */
            $Transaction->commit();


        }

            
            /* actualiza la base de datos de un proveedor si se cumple una condición. */
            if($insertOrUpdate){
                $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO','',$Partner);
                $CMSProveedor->updateDatabaseCasino();
            }


        $response["HasError"] = false;

        /* Código PHP para construir una respuesta exitosa con mensajes y datos vacíos. */
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    }else{
        /* define una respuesta de éxito en un sistema de gestión de errores. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

    }
} catch (Exception $e) {
    /* Captura excepciones en PHP y muestra información sobre el error. */

    print_r($e);
}
