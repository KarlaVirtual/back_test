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
 * Client/Segments
 *
 * Segmentos utilizados por la plataforma propia de chat para el envío de marketing.
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param string $params ->field Campo a evaluar.
 * @param string $params ->operator Operador lógico para la evaluación.
 * @param mixed $params ->value Valor a comparar.
 *
 *
 *
 * @return array $response Respuesta con los siguientes atributos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta generada.
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - data (array): Datos procesados.
 */


/* Asignación de valores de parámetros a variables y creación de un array vacío. */
$field = $params->field;
$operator = $params->operator;
$value = $params->value;

$data = array();

switch ($field) {

    case "doradobetData.apiCondSportsBetsToday":

        /* Se inicializan reglas, una condición y un indicador de inclusión de nulos. */
        $rules = [];
        $having = "";
        $withNull = false;


        switch ($operator) {
            case 'e':
            case 'et':
            default:
                // array_push($rules, array("field" => "valor", "data" => "$value", "op" => "eq"));

                /* Código conditional que construye cláusulas SQL 'HAVING' según diferentes operadores. */
                $having = " valor = '" . $value . "'";
                break;
            case 'dne':
                // array_push($rules, array("field" => "valor", "data" => "$value", "op" => "ne"));
                $having = " valor != '" . $value . "'";

                break;
            case 'c':
                //array_push($rules, array("field" => "valor", "data" => "$value", "op" => "cn"));
                $having = " valor  LIKE '%" . $value . "%'";

                break;

            case 'dnc':
                //array_push($rules, array("field" => "valor", "data" => "$value", "op" => "nc"));

                /* construye condiciones SQL para filtrar datos según criterios específicos. */
                $having = " valor NOT LIKE '%" . $value . "%'";
                break;

            case 'igt':
                // array_push($rules, array("field" => "valor", "data" => "$value", "op" => "gt"));
                $having = " valor > '" . $value . "'";
                break;
            case 'ilt':
                //array_push($rules, array("field" => "valor", "data" => "$value", "op" => "lt"));

                /* genera condiciones SQL basadas en valores y operadores específicos. */
                $having = " valor < '" . $value . "'";
                $withNull = true;

                break;
            case 'it':
                //array_push($rules, array("field" => "valor", "data" => "true", "op" => "eq"));
                $having = " valor = 'true' ";
                break;
            case 'if':
                //  array_push($rules, array("field" => "valor", "data" => "false", "op" => "eq"));

                /* agrega reglas de comparación de fechas a un array según condiciones específicas. */
                $having = " valor = 'false'";
                break;
            case 'wlt':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "le"));
                break;

            case 'wmt':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                break;

            case 'wow':

                /* Agrega condiciones de comparación de fechas a un array de reglas. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                break;

            case 'woa':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                break;

            case 'is':
                // array_push($rules, array("field" => "valor", "data" => '', "op" => "ne"));
                // array_push($rules, array("field" => "valor", "data" => $value, "op" => "eq"));

                /* Define condiciones para filtrar datos según el valor de la variable. */
                $having = " valor != ''";
                $having = " valor = '$value'";
                break;

            case 'ins':
                //array_push($rules, array("field" => "valor", "data" => $value, "op" => "ne"));

                $having = " valor != '$value'";

                break;
        }

        /* Se generan reglas de filtro en formato JSON para consultas de tickets. */
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => date('Y-m-d'), "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);


        /* obtiene y procesa datos de tickets asociados a usuarios. */
        $ItTicketEnc = new ItTicketEnc();

        $tickets = $ItTicketEnc->getTicketsCustom2(" usuario.usuario_id,(CASE WHEN SUM(vlr_apuesta) IS NULL THEN 0 ELSE SUM(vlr_apuesta) END )valor   ", "usuario.usuario_id", "asc", 0, 10000, $json, true, "usuario.usuario_id", $having, $withNull);
        $tickets = json_decode($tickets);

        foreach ($tickets->data as $datum) {
            array_push($data, $datum->{"usuario.usuario_id"});
        }


        break;

    case "doradobetData.apiCondSportsGGRToday":


        $rules = [];
        $having = "";


        switch ($operator) {
            case 'e':
            case 'et':
            default:
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "eq"));

                /* Construye condiciones SQL basadas en diferentes operadores de comparación. */
                $having = " valor = '" . $value . "'";
                break;
            case 'dne':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "ne"));
                $having = " valor != '" . $value . "'";

                break;
            case 'c':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "cn"));
                $having = " valor  LIKE '%" . $value . "%'";

                break;

            case 'dnc':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "nc"));

                /* genera condiciones SQL basadas en diferentes operadores y valores. */
                $having = " valor NOT LIKE '%" . $value . "%'";
                break;

            case 'igt':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "gt"));
                $having = " valor > '" . $value . "'";
                break;
            case 'ilt':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "lt"));

                /* Condicional para definir la cláusula HAVING en SQL según diferentes casos. */
                $having = " valor < '" . $value . "'";
                break;
            case 'it':
//array_push($rules, array("field" => "valor", "data" => "true", "op" => "eq"));
                $having = " valor = 'true' ";
                break;
            case 'if':
//  array_push($rules, array("field" => "valor", "data" => "false", "op" => "eq"));
                $having = " valor = 'false'";
                break;
            case 'wlt':

                /* añade reglas de comparación de fechas a un array. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "le"));
                break;

            case 'wmt':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                break;

            case 'wow':

                /* Agrega reglas a un array usando fechas y operadores lógicos. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                break;

            case 'woa':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                break;

            case 'is':
// array_push($rules, array("field" => "valor", "data" => '', "op" => "ne"));
// array_push($rules, array("field" => "valor", "data" => $value, "op" => "eq"));

                /* establece condiciones para la cláusula HAVING en SQL. */
                $having = " valor != ''";
                $having = " valor = '$value'";
                break;

            case 'ins':
//array_push($rules, array("field" => "valor", "data" => $value, "op" => "ne"));

                $having = " valor != '$value'";

                break;
        }

        /* Construye un filtro en JSON con reglas para consultas de tickets. */
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => date('Y-m-d'), "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);


        /* Código que obtiene y procesa datos de tickets para usuarios en formato JSON. */
        $ItTicketEnc = new ItTicketEnc();

        $tickets = $ItTicketEnc->getTicketsGGRCustom(" usuario_id,(SUM(apuestas) - SUM(premios)) valor   ", "it_ticket_enc.usuario_id", "asc", 0, 10000, $json, true, "it_ticket_enc.usuario_id", $having);
        $tickets = json_decode($tickets);

        foreach ($tickets->data as $datum) {
            array_push($data, $datum->{"c.usuario_id"});
        }


        break;

    case "doradobetData.apiCondCasinoBetsToday":


        $rules = [];
        $having = "";


        switch ($operator) {
            case 'e':
            case 'et':
            default:
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "eq"));

                /* Código construye condiciones SQL según el tipo de comparación especificado. */
                $having = " valor = '" . $value . "'";
                break;
            case 'dne':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "ne"));
                $having = " valor != '" . $value . "'";

                break;
            case 'c':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "cn"));
                $having = " valor  LIKE '%" . $value . "%'";

                break;

            case 'dnc':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "nc"));

                /* Construye condiciones SQL para filtrar registros basados en valores específicos. */
                $having = " valor NOT LIKE '%" . $value . "%'";
                break;

            case 'igt':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "gt"));
                $having = " valor > '" . $value . "'";
                break;
            case 'ilt':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "lt"));

                /* asigna condiciones SQL basadas en diferentes casos de una variable. */
                $having = " valor < '" . $value . "'";
                break;
            case 'it':
//array_push($rules, array("field" => "valor", "data" => "true", "op" => "eq"));
                $having = " valor = 'true' ";
                break;
            case 'if':
//  array_push($rules, array("field" => "valor", "data" => "false", "op" => "eq"));
                $having = " valor = 'false'";
                break;
            case 'wlt':

                /* Agrega reglas al array, utilizando fechas y operadores para condiciones específicas. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "le"));
                break;

            case 'wmt':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                break;

            case 'wow':

                /* Se añaden condiciones a un arreglo utilizando fechas y operaciones de comparación. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                break;

            case 'woa':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                break;

            case 'is':
// array_push($rules, array("field" => "valor", "data" => '', "op" => "ne"));
// array_push($rules, array("field" => "valor", "data" => $value, "op" => "eq"));

                /* establece condiciones para filtrar resultados basados en el valor de "valor". */
                $having = " valor != ''";
                $having = " valor = '$value'";
                break;

            case 'ins':
//array_push($rules, array("field" => "valor", "data" => $value, "op" => "ne"));

                $having = " valor != '$value'";

                break;
        }

        /* Se define un filtro JSON para validar una fecha en una transacción de juego. */
        array_push($rules, array("field" => "DATE_FORMAT(transaccion_juego.fecha_crea,'%Y-%m-%d')", "data" => date('Y-m-d'), "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);

        $TransaccionJuego = new TransaccionJuego();

        /* Consulta transacciones agrupadas por usuario, sumando valores de tickets y decodificando JSON. */
        $select = "usuario_mandante.usuario_mandante,usuario_mandante.usumandante_id,(SUM(valor_ticket)) valor";
        $grouping = "usuario_mandante.usumandante_id";

        $transacciones = $TransaccionJuego->getTransaccionesCustom($select, "usuario_mandante.usuario_mandante", "desc", 0, 10000, $json, true, $grouping, $having);
        $transacciones = json_decode($transacciones);

        foreach ($transacciones->data as $datum) {
            array_push($data, $datum->{"usuario_mandante.usuario_mandante"});
        }


        break;

    case "doradobetData.apiCondCasinoGGRToday":


        $rules = [];
        $having = "";


        switch ($operator) {
            case 'e':
            case 'et':
            default:
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "eq"));

                /* genera condiciones de filtrado basadas en el valor y operadores. */
                $having = " valor = '" . $value . "'";
                break;
            case 'dne':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "ne"));
                $having = " valor != '" . $value . "'";

                break;
            case 'c':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "cn"));
                $having = " valor  LIKE '%" . $value . "%'";

                break;

            case 'dnc':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "nc"));

                /* Código que construye condiciones SQL para filtrar resultados según reglas. */
                $having = " valor NOT LIKE '%" . $value . "%'";
                break;

            case 'igt':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "gt"));
                $having = " valor > '" . $value . "'";
                break;
            case 'ilt':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "lt"));

                /* Condición que asigna valores SQL basados en diferentes casos. */
                $having = " valor < '" . $value . "'";
                break;
            case 'it':
//array_push($rules, array("field" => "valor", "data" => "true", "op" => "eq"));
                $having = " valor = 'true' ";
                break;
            case 'if':
//  array_push($rules, array("field" => "valor", "data" => "false", "op" => "eq"));
                $having = " valor = 'false'";
                break;
            case 'wlt':

                /* Se agregan reglas de comparación de fechas a un arreglo en PHP. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "le"));
                break;

            case 'wmt':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                break;

            case 'wow':

                /* Se añaden reglas con fechas y operadores a un array en PHP. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                break;

            case 'woa':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                break;

            case 'is':
// array_push($rules, array("field" => "valor", "data" => '', "op" => "ne"));
// array_push($rules, array("field" => "valor", "data" => $value, "op" => "eq"));

                /* define condiciones SQL para filtrar resultados en consultas. */
                $having = " valor != ''";
                $having = " valor = '$value'";
                break;

            case 'ins':
//array_push($rules, array("field" => "valor", "data" => $value, "op" => "ne"));

                $having = " valor != '$value'";

                break;
        }

        /* Se crea un filtro JSON para validar fechas en transacciones de juegos. */
        array_push($rules, array("field" => "DATE_FORMAT(transaccion_juego.fecha_crea,'%Y-%m-%d')", "data" => date('Y-m-d'), "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);

        $TransaccionJuego = new TransaccionJuego();

        /* Consulta datos de usuarios y suma de transacciones en una estructura JSON. */
        $select = "usuario_mandante.usuario_mandante,usuario_mandante.usumandante_id,(SUM(valor_ticket)-SUM(valor_premio)) valor";
        $grouping = "usuario_mandante.usumandante_id";

        $transacciones = $TransaccionJuego->getTransaccionesCustom($select, "usuario_mandante.usuario_mandante", "desc", 0, 10000, $json, true, $grouping, $having);
        $transacciones = json_decode($transacciones);

        foreach ($transacciones->data as $datum) {
            array_push($data, $datum->{"usuario_mandante.usuario_mandante"});
        }


        break;

    case "doradobetData.apiCondCasinoBetsMonth":


        $rules = [];
        $having = "";


        switch ($operator) {
            case 'e':
            case 'et':
            default:
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "eq"));

                /* Genera condiciones SQL basadas en las entradas para construir cláusulas HAVING. */
                $having = " valor = '" . $value . "'";
                break;
            case 'dne':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "ne"));
                $having = " valor != '" . $value . "'";

                break;
            case 'c':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "cn"));
                $having = " valor  LIKE '%" . $value . "%'";

                break;

            case 'dnc':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "nc"));

                /* Condiciones para filtrar datos en SQL según comparación de valores. */
                $having = " valor NOT LIKE '%" . $value . "%'";
                break;

            case 'igt':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "gt"));
                $having = " valor > '" . $value . "'";
                break;
            case 'ilt':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "lt"));

                /* Genera condiciones SQL según el valor para filtrar resultados. */
                $having = " valor < '" . $value . "'";
                break;
            case 'it':
//array_push($rules, array("field" => "valor", "data" => "true", "op" => "eq"));
                $having = " valor = 'true' ";
                break;
            case 'if':
//  array_push($rules, array("field" => "valor", "data" => "false", "op" => "eq"));
                $having = " valor = 'false'";
                break;
            case 'wlt':

                /* Agrega condiciones temporales a un array de reglas en PHP. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "le"));
                break;

            case 'wmt':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                break;

            case 'wow':

                /* Agrega reglas con fechas y operadores a un array en PHP. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                break;

            case 'woa':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                break;

            case 'is':
// array_push($rules, array("field" => "valor", "data" => '', "op" => "ne"));
// array_push($rules, array("field" => "valor", "data" => $value, "op" => "eq"));

                /* establece condiciones para filtrar valores en una consulta. */
                $having = " valor != ''";
                $having = " valor = '$value'";
                break;

            case 'ins':
//array_push($rules, array("field" => "valor", "data" => $value, "op" => "ne"));

                $having = " valor != '$value'";

                break;
        }

        /* define reglas de filtro y las convierte a formato JSON para una consulta. */
        array_push($rules, array("field" => "DATE_FORMAT(transaccion_juego.fecha_crea,'%Y-%m-%d')", "data" => date('Y-m-d', strtotime('-30 days')), "op" => "ge"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);

        $TransaccionJuego = new TransaccionJuego();

        /* Consulta datos de transacciones, agrupando por usuario y acumulando valores. */
        $select = "usuario_mandante.usuario_mandante,usuario_mandante.usumandante_id,(SUM(valor_ticket)-SUM(valor_premio)) valor";
        $grouping = "usuario_mandante.usumandante_id";

        $transacciones = $TransaccionJuego->getTransaccionesCustom($select, "usuario_mandante.usuario_mandante", "desc", 0, 10000, $json, true, $grouping, $having);
        $transacciones = json_decode($transacciones);

        foreach ($transacciones->data as $datum) {
            array_push($data, $datum->{"usuario_mandante.usuario_mandante"});
        }


        break;

    case "doradobetData.apiCondBonusSpecificRedToday":


        $rules = [];
        $having = "";


        switch ($operator) {
            case 'e':
            case 'et':
            default:

                /* Se añaden reglas de comparación y condiciones SQL basadas en el valor proporcionado. */
                array_push($rules, array("field" => "usuario_bono.bono_id", "data" => "$value", "op" => "eq"));
                $having = " valor = '" . $value . "'";
                break;
            case 'dne':
                array_push($rules, array("field" => "usuario_bono.bono_id", "data" => "$value", "op" => "ne"));
                $having = " valor != '" . $value . "'";

                break;
            case 'c':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "cn"));

                /* Genera condiciones SQL para filtrar valores que incluyen o excluyen un término específico. */
                $having = " valor  LIKE '%" . $value . "%'";

                break;

            case 'dnc':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "nc"));
                $having = " valor NOT LIKE '%" . $value . "%'";
                break;

            case 'igt':
// array_push($rules, array("field" => "valor", "data" => "$value", "op" => "gt"));

                /* genera condiciones SQL según operadores lógicos y valores proporcionados. */
                $having = " valor > '" . $value . "'";
                break;
            case 'ilt':
//array_push($rules, array("field" => "valor", "data" => "$value", "op" => "lt"));
                $having = " valor < '" . $value . "'";
                break;
            case 'it':
//array_push($rules, array("field" => "valor", "data" => "true", "op" => "eq"));
                $having = " valor = 'true' ";
                break;
            case 'if':
//  array_push($rules, array("field" => "valor", "data" => "false", "op" => "eq"));

                /* añade reglas basadas en fechas y condiciones específicas a un array. */
                $having = " valor = 'false'";
                break;
            case 'wlt':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "le"));
                break;

            case 'wmt':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                break;

            case 'wow':

                /* Agrega reglas con fechas y operaciones de comparación al array $rules. */
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "le"));
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                break;

            case 'woa':
                array_push($rules, array("field" => "valor", "data" => date('Y-m-d H:i:s', $value), "op" => "ge"));
                break;

            case 'is':
// array_push($rules, array("field" => "valor", "data" => '', "op" => "ne"));
// array_push($rules, array("field" => "valor", "data" => $value, "op" => "eq"));

                /* asigna condiciones SQL basadas en el valor ingresado. */
                $having = " valor != ''";
                $having = " valor = '$value'";
                break;

            case 'ins':
//array_push($rules, array("field" => "valor", "data" => $value, "op" => "ne"));

                $having = " valor != '$value'";

                break;
        }

        /* Crea un filtro JSON con reglas para comparar fechas y estado de usuario. */
        array_push($rules, array("field" => "DATE_FORMAT(usuario_bono.fecha_modif,'%Y-%m-%d')", "data" => date('Y-m-d'), "op" => "eq"));
        array_push($rules, array("field" => "usuario_bono.estado", "data" => "R", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);


        /* Consulta y decodifica datos de bonos de usuario en formato JSON. */
        $select = " usuario_bono.* ";


        $UsuarioBono = new UsuarioBono();
        $databono = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", 0, 100000, $json, true, $grouping);

        $databono = json_decode($databono);


        /* crea un array con IDs de usuarios desde datos de bonificación. */
        $final = array();
        $totalAmount = 0;
        foreach ($databono->data as $value) {
            array_push($data, $value->{"usuario_bono.usuario_id"});
        }


        break;

}

/*Generación formato de respueta*/
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["data"] = $data;
