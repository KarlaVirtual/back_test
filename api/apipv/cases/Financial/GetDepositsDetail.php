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
use Backend\dto\Consecutivo;use Backend\dto\ConfigurationEnvironment;
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
 * Obtiene el detalle de un depósito específico
 *
 * Este endpoint permite obtener la información detallada de un depósito
 * a partir de su ID, incluyendo datos de la transacción, producto y usuario.
 *
 * @param int $id ID del depósito a consultar
 *
 * @return array {
 *   "HasError": boolean,      // Indica si hubo errores en el proceso
 *   "AlertType": string,      // Tipo de alerta (success, error, warning)
 *   "AlertMessage": string,   // Mensaje descriptivo del resultado
 *   "Data": {
 *     "Transaction": {        // Datos de la transacción
 *       "Id": int,           // ID de la transacción
 *       "Amount": float,     // Monto del depósito
 *       "Currency": string,  // Moneda
 *       "Status": string,    // Estado del depósito
 *       "CreatedDate": string, // Fecha de creación
 *       "Product": {         // Información del producto
 *         "Id": int,
 *         "Name": string
 *       },
 *       "User": {           // Información del usuario
 *         "Id": string,
 *         "Currency": string
 *       }
 *     }
 *   }
 * }
 *
 * @throws Exception Si hay errores en la consulta a la base de datos
 *
 * @access public
 */



/* obtiene el valor del parámetro "id" de solicitudes HTTP. */
$Id = $_REQUEST["id"];

if (is_numeric($Id)) {

    /* Se crea un objeto y se definen reglas para validación de recarga de usuario. */
    $UsuarioRecarga = new UsuarioRecarga();

    $rules = [];

    array_push($rules, array("field" => "usuario_recarga.recarga_id", "data" => "$Id", "op" => "eq"));


    $IsDetails = true;

    
    /* maneja condiciones para agrupar y calcular valores de transacciones. */
    if ($IsDetails) {

    } else {
        $grouping = " usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    /* Se configura un filtro con reglas y opciones de agrupación; establece valores predeterminados. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    
    /* verifica un límite de filas y obtiene datos de recargas de usuario. */
    if ($MaxRows == "") {
        $MaxRows = 100;
    }

    $json = json_encode($filtro);

    $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);


    /* decodifica transacciones JSON y inicializa variables para procesamiento. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    $cont = 0;
    foreach ($transacciones->data as $key => $value) {

        /* Acumula valores en `$totalm` según la condición de `$IsDetails`. */
        $array = [];
        if ($IsDetails) {
            $totalm = $totalm + $value->{"transaccion_producto.valor"};

        } else {
            $totalm = $totalm + $value->{".valoru"};
        }
        
        /* verifica si una descripción está vacía y registra una recarga. */
        if ($value->{"producto.descripcion"} == "") {

            $array["Date"] = $value->{"usuario_recarga.fecha_crea"};
            $array["UserId"] = $value->{"usuario_recarga.usuario_id"};
            $array["TransactionId"] = $value->{"usuario_recarga.recarga_id"};
            $array["Description"] = "Recarga por medio Efectivo";
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Description"] = "Cash Deposit";
            }


            array_push($final, $array);
            $cont++;
        } else {

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO();

            $objects = $TransprodLogMySqlDAO->queryByTransproductoId($value->{"transaccion_producto.transproducto_id"});

            foreach ($objects as $object) {
                $array = [];

                $array["Date"] = $object->fechaCrea;
                $array["UserId"] = $object->usucreaId;
                $array["TransactionId"] = $object->transprodlogId;
                $array["Description"] = $object->comentario;
                
                /* Traduce descripciones del español al inglés según la configuración de idioma del usuario. */
                if(strtolower($_SESSION["idioma"])=="en"){
                    $array["Description"] = str_replace("Envio solicitud de deposito","Deposit request sent",$array["Description"]);
                    $array["Description"] = str_replace("Envio Solicitud de deposito","Deposit request sent",$array["Description"]);
                    $array["Description"] = str_replace("Aprobada por Sagicor","Approved by Sagicor",$array["Description"]);
                    $array["Description"] = str_replace("Aprobada por","Approved by",$array["Description"]);
                    $array["Description"] = str_replace("Auto aprobado por el proveedor","Auto Approved by the provider",$array["Description"]);
                    $array["Description"] = str_replace("Aprobado automaticamente y se genera recarga","Auto Approved and recharge generated",$array["Description"]);
                    $array["Description"] = str_replace("Aprobado automaticamente y se genera la recarga","Auto Approved and recharge generated",$array["Description"]);
                }

                
                /* añade el contenido de `$array` al final de `$final`. */
                array_push($final, $array);
                $cont++;

            }

        }
    }



    /* asigna valores a un array de respuesta en PHP. */
    $response["pos"] = 0;
    $response["total_count"] = $cont;
    $response["data"] = $final;


}