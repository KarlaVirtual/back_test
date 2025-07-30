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
 * Obtener los premios vinculados a los puntos de venta
 *
 * Obtener los puntos de ventas
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param string $json->params->site_id Identificador del sitio.
 * @param int $SkeepRows Número de filas a omitir.
 * @param int $MaxRows Número máximo de filas a procesar.
 *
 * @return array Respuesta con el código, rid y los datos finales.
 *   int $response["code"] Código de respuesta.
 *   string $response["rid"] Identificador de la solicitud.
 *   array $response["data"] Datos de los puntos de venta.
 *   array $response["data2"] Datos adicionales (vacío).
 *
 * @throws Exception Si ocurre un error durante el procesamiento.
 */

$seguir = true; // Variable para controlar la ejecución del proceso

$SkeepRows = 0; // Contador de filas a omitir
$MaxRows = 1000000; // Número máximo de filas a procesar

$State = 'A'; // Estado inicial

$mandante=$_SESSION["mandante"]; // Obtener el mandante de la sesión
$mandante=$json->params->site_id; // Asignar el site_id del JSON al mandanteif ($seguir) {

/**
 * Inicializa las reglas para la consulta de puntos de venta.
 * Se utilizan condiciones basadas en el estado y otros parámetros.
 */
if ($seguir) {

    $rules = [];

    if ($State != "") {
        array_push($rules, array("field" => "usuario.estado", "data" => $State, "op" => "eq"));
    }

    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

    array_push($rules, array("field" => "punto_venta.premiofisico", "data" => "S", "op" => "eq"));

    array_push($rules, array("field" => "punto_venta.mandante", "data" => $mandante, "op" => "eq"));

    // Crea un filtro con las reglas definidas y la operación de agrupamiento
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $order = "punto_venta.puntoventa_id"; // Definir el campo por el cual se ordenará la consulta
    $orderType = "asc"; // Definir el tipo de orden (ascendente)

    $PuntoVenta = new PuntoVenta(); // Instancia de la clase PuntoVenta

    // Obtiene los puntos de venta personalizados utilizando los criterios de orden y filtro
    $mandantes = $PuntoVenta->getPuntoVentasCustom("clasificador4.descripcion,ciudad.ciudad_nom,departamento.depto_nom,usuario.nombre,usuario.login,usuario.mandante,usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*,pais.*,concesionario.usupadre_id,concesionario.usupadre2_id", $order, $orderType, $SkeepRows, $MaxRows, $json, true);

    $mandantes = json_decode($mandantes); // Decodifica el JSON obtenido de la consulta

    $final = []; // Inicializa un array final para ser utilizado más adelante

    foreach ($mandantes->data as $key => $value) {
        // Recorre cada elemento de la data perteneciente a mandantes

        $array = [];
        $array2 = array();

        // Asigna el nombre de la ciudad y el id de la ciudad al arreglo principal
        $array["Name"] = $value->{"ciudad.ciudad_nom"};
        $array["Id"] = $value->{"punto_venta.ciudad_id"};
        $array2["Id"]=$value->{"punto_venta.usuario_id"};
        $array2["Name"]=$value->{"punto_venta.direccion"};
        $array["BetShops"]=array($array2);

        $nuevo = true; // Variable que indica si el elemento es nuevo

        foreach($final as $key2 => $value2) {
            $array2 = array();

            // Verifica si el nombre de la ciudad ya existe en el arreglo final
            if($value2['Name']==$array['Name']){

                $array2["Id"]=$value->{"punto_venta.usuario_id"};
                $array2["Name"]=$value->{"punto_venta.direccion"};

                array_push($final[$key2]["BetShops"], $array2);
                //array_push($final[$key2]["BetShops"]["Name"], $value->{"punto_venta.direccion"});
                $nuevo=false;
            }
        }

        // Si el elemento es nuevo, lo agrega al arreglo final
        if($nuevo){
            array_push($final, $array);
        }
    }

    // Prepara la respuesta con el código, el rid y los datos finales
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = $final;
    $response["data2"] = [];
    //Objects

}



