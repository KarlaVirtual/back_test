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
 * Agent/GetAgentsTwoLevels2
 *
 * Obtener la red completa de el partner versión 2
 *
 * @param no
 *
 * @return no
 * {"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * @OA\Post(path="apipv/Agent/GetAgentTwoLevels2", tags={"Agent"}, description = "",
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="roleId",
 *                   description="Identificador nuemrico del usuario",
 *                   type="integer",
 *                   example= 469
 *               ),
 *               @OA\Property(
 *                   property="Type",
 *                   description="",
 *                   type="string",
 *                   example= 469
 *               ),
 *               @OA\Property(
 *                   property="start",
 *                   description="indice del registro",
 *                   type="integer",
 *                   example= 469
 *               ),
 *               @OA\Property(
 *                   property="count",
 *                   description="Total registros",
 *                   type="integer",
 *                   example= 34
 *               ),
 *             )
 *         ),
 *     ),
 *
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="pos",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="total_account",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *             )
 *         ),
 *         )
 * ),
 * @OA\Response (response="404", description="Not found")
 * )
 */


/**
 * Agent/GetAgentsTwoLevels2
 *
 * Obtener la red completa de el partner versión 2
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud:
 * @param int $params ->maxRows Número máximo de filas a devolver.
 * @param int $params ->orderedItem Elemento ordenado.
 * @param int $params ->skeepRows Filas omitidas.
 *
 *
 * @return array $response Respuesta de la API:
 *                         - HasError: boolean Indica si hubo un error.
 *                         - AlertType: string Tipo de alerta.
 *                         - AlertMessage: string Mensaje de alerta.
 *                         - ModelErrors: array Errores del modelo.
 *                         - Data: array Datos de la respuesta:
 *                           - Children: array Hijos del agente.
 *                           - DownStreamChildrenCount: int Conteo de hijos en el downstream.
 *                           - DownStreamChildrenBalanceSum: float Suma de balances de hijos en el downstream.
 *                           - DownStreamPlayerCount: int Conteo de jugadores en el downstream.
 *                           - DownStreamPlayerBalanceSum: float Suma de balances de jugadores en el downstream.
 *                         - pos: int Posición de las filas omitidas.
 *                         - total_count: int Conteo total de usuarios.
 *                         - data: array Datos de los usuarios.
 * @throws Exception Si ocurre un error durante la ejecución.
 */

/* Se crean instancias de clases para gestionar perfil y mandante de usuario. */
$UsuarioPerfil = new UsuarioPerfil();
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

if (!strpos($_SESSION["win_perfil"], "CONCESIONARIO")) {

    if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {
        if (true) {


            /* Asigna valores de parámetros y establece filas a omitir como cero si están vacías. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100000000;
            }


            /* asigna usuarios según el perfil "CONCESIONARIO" en la sesión. */
            $UserIdAgent = '';
            $UserIdAgent2 = '';
            $UserIdAgent3 = '';
            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UserIdAgent = $_SESSION["usuario"];

            }

            /* Verifica el perfil de usuario y asigna un concesionario específico. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UserIdAgent2 = $_SESSION["usuario"];

                $Concesionario = new Concesionario($UserIdAgent2, '0');
                $UserIdAgent = $Concesionario->usupadreId;

            }

            /* Verifica el perfil de usuario y asigna IDs de concesionario en variables. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
                $UserIdAgent3 = $_SESSION["usuario"];

                $Concesionario = new Concesionario($UserIdAgent3, '0');
                $UserIdAgent = $Concesionario->usupadreId;
                $UserIdAgent2 = $Concesionario->usupadre2Id;

            }


            /* inicializa un arreglo y define reglas para filtrar datos de concesionarios. */
            $arrayfinal = array();
            $balanceAgent = 0;

            $rules3 = array();
            array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2','CONCESIONARIO3','CONCESIONARIO4'", "op" => "in"));

            array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

            /* Agrega reglas de filtrado basadas en estado y usuario al array $rules3. */
            array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

            array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

            if ($Login != "") {
                array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

            }


            /* Agrega reglas a un arreglo basado en condiciones de usuario y tienda de apuestas. */
            if ($UserId != "") {
                array_push($rules3, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

            }

            if ($BetShopId != "") {
                array_push($rules3, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

            }


            /* Agrega condiciones a un array si los identificadores de usuario no están vacíos. */
            if ($UserIdAgent != "") {
                array_push($rules3, array("field" => "concesionario.usupadre_id", "data" => $UserIdAgent, "op" => "eq"));

            }

            if ($UserIdAgent2 != "") {
                array_push($rules3, array("field" => "concesionario.usupadre2_id", "data" => $UserIdAgent2, "op" => "eq"));

            }


            /* Condiciona reglas según el usuario y el país en un arreglo. */
            if ($UserIdAgent3 != "") {
                array_push($rules3, array("field" => "concesionario.usupadre3_id", "data" => $UserIdAgent3, "op" => "eq"));

            }

            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules3, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* Condicional que asigna reglas basadas en el estado de la sesión y mandante. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            /* Se crea un filtro para obtener detalles de usuarios desde una base de datos. */
            $filtro = array("rules" => $rules3, "groupOp" => "AND");
            $json3 = json_encode($filtro);

            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" concesionario.*,usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
            $usuariosdetalle = json_decode($usuariosdetalle);
            foreach ($usuariosdetalle->data as $key3 => $value3) {


                /* Crea un array asociativo con datos del usuario extraídos de un objeto. */
                $array3 = [];
                $array3["Id"] = $value3->{"usuario.usuario_id"};
                $array3["id"] = $value3->{"usuario.usuario_id"};
                $array3["UserId"] = $value3->{"usuario.usuario_id"};
                $array3["UserName"] = $value3->{"usuario.nombre"};
                $array3["Currency"] = $value3->{"usuario.moneda"};

                /* asigna valores a un array basado en propiedades de un objeto. */
                $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};
                $array3["Name"] = $value3->{"usuario.nombre"};
                $array3["SystemName"] = 22;
                $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};

                /* asigna valores a un array basado en propiedades de un objeto. */
                $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};
                $array3["PlayerCount"] = 0;
                $array3["Partner"] = $value3->{"usuario.mandante"};

                $array3["flag"] = strtolower($value3->{"pais.iso"});
                switch ($value3->{"usuario_perfil.perfil_id"}) {
                    case "CONCESIONARIO":
                        $array3["icon"] = "icon-user-secret";
                        break;
                    case "CONCESIONARIO2":
                        $array3["icon"] = "icon-user-secret";
                        break;
                    case "CONCESIONARIO3":
                        $array3["icon"] = "icon-user-secret";
                        break;
                    case "PUNTOVENTA":
                        $array3["icon"] = "icon-shop";
                        break;
                }


                /* Crea un arreglo multidimensional usando identificadores de concesionarios como claves. */
                $arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})] = $array3;

            }


            if ($UserIdAgent != "") {


                /* Se definen reglas de validación para filtrar concesionarios activos y no eliminados. */
                $rules3 = array();
                array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO'", "op" => "in"));

                array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

                array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


                /* Añade reglas de validación para login y user ID en un array. */
                if ($Login != "") {
                    array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

                }

                if ($UserId != "") {
                    array_push($rules3, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

                }


                /* Agrega reglas de filtrado basadas en los identificadores de apuestas y agentes. */
                if ($BetShopId != "") {
                    array_push($rules3, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

                }


                if ($UserIdAgent != "") {
                    array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $UserIdAgent, "op" => "in"));

                }


                // Si el usuario esta condicionado por País

                /* agrega reglas basadas en condiciones de sesión del usuario. */
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules3, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }
                // Si el usuario esta condicionado por el mandante y no es de Global
                if ($_SESSION['Global'] == "N") {
                    array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                } else {
                    /* agrega condiciones a un arreglo si la sesión tiene valores válidos. */


                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }

                }


                /* Se crea un filtro JSON para obtener detalles de usuarios desde una base de datos. */
                $filtro = array("rules" => $rules3, "groupOp" => "AND");
                $json3 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" concesionario.*,usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
                $usuariosdetalle = json_decode($usuariosdetalle);

                foreach ($usuariosdetalle->data as $key3 => $value3) {


                    /* crea un array con información de un usuario específico. */
                    $array3 = [];
                    $array3["Id"] = $value3->{"usuario.usuario_id"};
                    $array3["id"] = $value3->{"usuario.usuario_id"};
                    $array3["UserId"] = $value3->{"usuario.usuario_id"};
                    $array3["UserName"] = $value3->{"usuario.nombre"};
                    $array3["Currency"] = $value3->{"usuario.moneda"};

                    /* Asigna valores a un array con información del usuario y su balance. */
                    $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};
                    $array3["Name"] = $value3->{"usuario.nombre"};
                    $array3["SystemName"] = 22;
                    $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                    $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                    $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};

                    /* Se asignan valores a un arreglo basado en el perfil del usuario. */
                    $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};
                    $array3["PlayerCount"] = 0;
                    $array3["Partner"] = $value3->{"usuario.mandante"};

                    $array3["flag"] = strtolower($value3->{"pais.iso"});
                    switch ($value3->{"usuario_perfil.perfil_id"}) {
                        case "CONCESIONARIO":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "CONCESIONARIO2":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "CONCESIONARIO3":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "PUNTOVENTA":
                            $array3["icon"] = "icon-shop";
                            break;
                    }


                    /* Asigna el contenido de $array3 a una estructura multidimensional usando identificadores jerárquicos. */
                    $arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})] = $array3;

                }


            }
            if ($UserIdAgent2 != "") {


                /* Se definen reglas de filtrado para datos de concesionarios y usuarios. */
                $rules3 = array();
                array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO2'", "op" => "in"));

                array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

                array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


                /* Condiciona reglas de búsqueda basadas en Login y UserId no vacíos. */
                if ($Login != "") {
                    array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

                }

                if ($UserId != "") {
                    array_push($rules3, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

                }


                /* Agrega condiciones a un array si las variables no están vacías. */
                if ($BetShopId != "") {
                    array_push($rules3, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

                }


                if ($UserIdAgent2 != "") {
                    array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $UserIdAgent2, "op" => "in"));

                }

                // Si el usuario esta condicionado por País

                /* Agrega reglas basadas en la sesión del usuario para validación de datos. */
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules3, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }
                // Si el usuario esta condicionado por el mandante y no es de Global
                if ($_SESSION['Global'] == "N") {
                    array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                } else {
                    /* Condicional que verifica y agrega un filtro a la lista de reglas si aplica. */


                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }

                }


                /* Se crea un filtro para obtener detalles de usuarios mediante una consulta personalizada. */
                $filtro = array("rules" => $rules3, "groupOp" => "AND");
                $json3 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" concesionario.*,usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
                $usuariosdetalle = json_decode($usuariosdetalle);

                foreach ($usuariosdetalle->data as $key3 => $value3) {


                    /* Se crea un array con datos del usuario extraídos de un objeto. */
                    $array3 = [];
                    $array3["Id"] = $value3->{"usuario.usuario_id"};
                    $array3["id"] = $value3->{"usuario.usuario_id"};
                    $array3["UserId"] = $value3->{"usuario.usuario_id"};
                    $array3["UserName"] = $value3->{"usuario.nombre"};
                    $array3["Currency"] = $value3->{"usuario.moneda"};

                    /* asigna valores a un array asociativo basado en propiedades de un objeto. */
                    $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};
                    $array3["Name"] = $value3->{"usuario.nombre"};
                    $array3["SystemName"] = 22;
                    $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                    $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                    $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};

                    /* Asigna valores a un arreglo basado en el perfil de usuario y país. */
                    $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};
                    $array3["PlayerCount"] = 0;
                    $array3["Partner"] = $value3->{"usuario.mandante"};

                    $array3["flag"] = strtolower($value3->{"pais.iso"});
                    switch ($value3->{"usuario_perfil.perfil_id"}) {
                        case "CONCESIONARIO":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "CONCESIONARIO2":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "CONCESIONARIO3":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "PUNTOVENTA":
                            $array3["icon"] = "icon-shop";
                            break;
                    }


                    /* Asigna un valor a un array multidimensional utilizando identificadores de concesionarios. */
                    $arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})] = $array3;

                }


            }
            if ($UserIdAgent3 != "") {


                /* Reglas para validar condiciones específicas en un sistema de concesionarios de usuarios. */
                $rules3 = array();
                array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO3'", "op" => "in"));

                array_push($rules3, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules3, array("field" => "concesionario.estado", "data" => "A", "op" => "eq"));

                array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


                /* Se añaden reglas de filtrado según los valores de $Login y $UserId. */
                if ($Login != "") {
                    array_push($rules3, array("field" => "usuario.login", "data" => $Login, "op" => "cn"));

                }

                if ($UserId != "") {
                    array_push($rules3, array("field" => "usuario.usuario_id", "data" => $UserId, "op" => "eq"));

                }


                /* Condiciona la inclusión de reglas basadas en BetShopId y UserIdAgent3. */
                if ($BetShopId != "") {
                    array_push($rules3, array("field" => "usuario.usuario_id", "data" => $BetShopId, "op" => "eq"));

                }


                if ($UserIdAgent3 != "") {
                    array_push($rules3, array("field" => "concesionario.usuhijo_id", "data" => $UserIdAgent3, "op" => "in"));

                }

                // Si el usuario esta condicionado por País

                /* añade reglas de filtrado basadas en condiciones de sesión del usuario. */
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules3, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }
                // Si el usuario esta condicionado por el mandante y no es de Global
                if ($_SESSION['Global'] == "N") {
                    array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                } else {
                    /* Condicional que agrega reglas basadas en la sesión del mandante si son válidas. */


                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules3, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }

                }


                /* Se construye un filtro y se obtienen detalles de usuarios en formato JSON. */
                $filtro = array("rules" => $rules3, "groupOp" => "AND");
                $json3 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" concesionario.*,usuario.mandante,usuario.usuario_id,usuario.moneda,usuario.nombre,punto_venta.creditos_base,punto_venta.cupo_recarga,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,pais.* ", "usuario.login", "asc", $SkeepRows, $MaxRows, $json3, true);
                $usuariosdetalle = json_decode($usuariosdetalle);

                foreach ($usuariosdetalle->data as $key3 => $value3) {


                    /* Crea un arreglo con información del usuario usando sus atributos. */
                    $array3 = [];
                    $array3["Id"] = $value3->{"usuario.usuario_id"};
                    $array3["id"] = $value3->{"usuario.usuario_id"};
                    $array3["UserId"] = $value3->{"usuario.usuario_id"};
                    $array3["UserName"] = $value3->{"usuario.nombre"};
                    $array3["Currency"] = $value3->{"usuario.moneda"};

                    /* Crea un arreglo asociativo con datos de un usuario y su estado. */
                    $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};
                    $array3["Name"] = $value3->{"usuario.nombre"};
                    $array3["SystemName"] = 22;
                    $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                    $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                    $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};

                    /* asigna valores a un array basado en perfil de usuario y país. */
                    $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};
                    $array3["PlayerCount"] = 0;
                    $array3["Partner"] = $value3->{"usuario.mandante"};

                    $array3["flag"] = strtolower($value3->{"pais.iso"});
                    switch ($value3->{"usuario_perfil.perfil_id"}) {
                        case "CONCESIONARIO":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "CONCESIONARIO2":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "CONCESIONARIO3":
                            $array3["icon"] = "icon-user-secret";
                            break;
                        case "PUNTOVENTA":
                            $array3["icon"] = "icon-shop";
                            break;
                    }


                    /* Asigna un valor a un arreglo multidimensional basado en identificadores de concesionario. */
                    $arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})] = $array3;

                }


            }


            /* Suma créditos de venta al balance del agente desde una propiedad del objeto $value3. */
            $balanceAgent = $balanceAgent + $value3->{"punto_venta.creditos_base"};

        }


        /* Se inicializa un array vacío llamado `$arrayfinal2`. */
        $arrayfinal2 = array();

        // Concesionarios


        foreach ($arrayfinal[0][0][0][0] as $item) {

            /* inicializa arrays y agrega elementos de un array final a ellos. */
            $item["Children"] = array();
            $item["data"] = array();


            foreach ($arrayfinal[$item["Id"]][0][0][0] as $itemH) {
                array_push($item["Children"], $itemH);
                array_push($item["data"], $itemH);

            }


            foreach ($item["Children"] as $keyHH2 => $itemHH2) {

                /* Copia un valor de clave y inicializa estructuras vacías en un array. */
                $keyHH2G = $keyHH2;
                $keyHH2 = $itemHH2["Id"];


                $itemHH2["Children"] = array();
                $itemHH2["data"] = array();


                /* Recorre un array y agrega elementos a subarrays, excluyendo un ID específico. */
                foreach ($arrayfinal[$item["Id"]][$keyHH2][0][0] as $itemH) {
                    if ($itemH["Id"] != $keyHH2) {
                        array_push($itemHH2["Children"], $itemH);
                        array_push($itemHH2["data"], $itemH);

                    }

                }

                foreach ($itemHH2["Children"] as $keyHH3 => $itemHH3) {

                    /* Asigna valores a variables y organiza datos en estructuras anidadas. */
                    $keyHH3G = $keyHH3;
                    $keyHH3 = $itemHH3["Id"];

                    $itemHH3["Children"] = array();
                    $itemHH3["data"] = array();

                    foreach ($arrayfinal[$item["Id"]][$keyHH2][$keyHH3][0] as $itemH) {
                        array_push($itemHH3["Children"], $itemH);
                        array_push($itemHH3["data"], $itemH);

                    }


                    /* Itera sobre un array, organiza elementos y agrega hijos a estructuras anidadas. */
                    foreach ($itemHH3["Children"] as $keyHH => $itemHH) {
                        $keyHHG = $keyHH;
                        $keyHH = $itemHH["Id"];
                        $itemHH["Children"] = array();
                        $itemHH["data"] = array();

                        foreach ($arrayfinal[$item["Id"]][$keyHH2][$keyHH3][$keyHH] as $itemH) {
                            array_push($itemHH["Children"], $itemH);
                            array_push($itemHH["data"], $itemH);

                        }

                        if (oldCount($itemHH["Children"]) > 0) {
                            $itemHH3["Children"][$keyHHG]["Children"] = $itemHH["Children"];
                            $itemHH3["Children"][$keyHHG]["data"] = $itemHH["Children"];

                        }

                    }

                    /* Asigna datos de 'Children' si 'oldCount' es mayor que cero. */
                    if (oldCount($itemHH3["Children"]) > 0) {

                        $itemHH2["Children"][$keyHH3G]["Children"] = $itemHH3["Children"];
                        $itemHH2["Children"][$keyHH3G]["data"] = $itemHH3["Children"];
                    }
                }

                /* verifica si hay hijos y los asigna a diferentes estructuras. */
                if (oldCount($itemHH2["Children"]) > 0) {

                    $item["Children"][$keyHH2G]["Children"] = $itemHH2["Children"];
                    $item["Children"][$keyHH2G]["data"] = $itemHH2["Children"];
                    $item["data"][$keyHH2G]["Children"] = $itemHH2["Children"];
                    $item["data"][$keyHH2G]["data"] = $itemHH2["Children"];
                }
            }


            /* Añade un elemento $item al final del array $arrayfinal2. */
            array_push($arrayfinal2, $item);

        }

        /* Asigna el contenido de `$arrayfinal2` a la variable `$arrayf`. */
        $arrayf = $arrayfinal2;

        if (false) {
            foreach ($arrayfinal[0][0][0][0] as $item) {

                /* agrega elementos de un arreglo a la lista "Children" de un ítem. */
                $item["Children"] = array();


                foreach ($arrayfinal[$item["Id"]][0][0][0] as $itemH) {
                    array_push($item["Children"], $itemH);

                }

                if ($item["Id"] != "0") {
                    //SubConcesionarios
                    foreach ($arrayfinal[$item["Id"]] as $key2 => $item2) {


                        /* Se inicializa un array vacío "Children" en la variable $item2. */
                        $item2["Children"] = array();


                        if ($key2 != "0") {
                            //SubConcesionarios2

                            foreach ($arrayfinal[$item["Id"]][$key2] as $key3 => $item3) {

                                /* Inicializa un array vacío llamado "Children" en el elemento "item3". */
                                $item3["Children"] = array();

                                if ($key3 != "0") {
                                    //SubConcesionario3
                                    foreach ($arrayfinal[$item["Id"]][$key2][$key3] as $key4 => $item4) {

                                        /* Se inicializa el elemento "Children" del array $item4 como un array vacío. */
                                        $item4["Children"] = array();


                                        /* Navega estructuras anidadas, modifica hijos y agrega elementos a un arreglo. */
                                        foreach ($item["Children"] as $keyHH2 => $itemHH2) {
                                            foreach ($itemHH2["Children"] as $keyHH3 => $itemHH3) {
                                                foreach ($itemHH3["Children"] as $keyHH => $itemHH) {
                                                    if ($itemHH["Id"] == $key4) {
                                                        $itemHH["Children"] = array();

                                                        foreach ($arrayfinal[$item["Id"]][$key2][$key3][$key4] as $itemH) {
                                                            array_push($itemHH["Children"], $itemH);

                                                        }
                                                        unset($item4['0']);
                                                        array_push($itemHH["Children"], $item4);


                                                    }
                                                    $item3["Children"] = $itemHH;
                                                }
                                            }
                                        }

                                    }
                                }


                                /* recorre elementos anidados y actualiza la estructura de "Children". */
                                foreach ($item["Children"] as $keyHH2 => $itemHH2) {
                                    foreach ($itemHH2["Children"] as $keyHH => $itemHH) {
                                        if ($itemHH["Id"] == $key3) {
                                            $itemHH["Children"] = array();

                                            foreach ($arrayfinal[$item["Id"]][$key2][$key3][0] as $itemH) {
                                                array_push($itemHH["Children"], $itemH);

                                            }

                                            unset($item3['0']);
                                            array_push($itemHH["Children"], $item3);

                                            unset($itemHH['0']);

                                        }
                                        $item3["Children"] = $itemHH;
                                    }
                                }
                            }
                        }


                        /* Recorre elementos y actualiza la estructura de hijos en un arreglo. */
                        foreach ($item["Children"] as $keyHH => $itemHH) {
                            if ($itemHH["Id"] == $key2) {
                                $itemHH["Children"] = array();

                                foreach ($arrayfinal[$item["Id"]][$key2][0][0] as $itemH) {
                                    array_push($itemHH["Children"], $itemH);

                                }

                                unset($item2['0']);
                                array_push($itemHH["Children"], $item2);

                                unset($itemHH['0']);

                            }
                            $item["Children"][$keyHH] = $itemHH;
                        }
                    }
                }

                /* añade $item al final del array $arrayfinal2. */
                array_push($arrayfinal2, $item);

            }

        }


    } elseif ($_SESSION["win_perfil"] == "PUNTOVENTA") {

        /* Define un array de reglas y asigna parámetros de configuración para un proceso. */
        $rules = array();


        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;


        /* Asigna valores predeterminados a variables si están vacías en PHP. */
        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }


        /* Establece un valor por defecto y agrega una regla de filtrado para perfiles. */
        if ($MaxRows == "") {
            $MaxRows = 100000000;
        }

        if ($Type == "1") {
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

        } else {
            /* Agrega una regla para verificar si "perfil_id" es igual a "CONCESIONARIO". */

            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO", "op" => "eq"));
        }

        // Si el usuario esta condicionado por País

        /* agrega reglas basadas en condiciones de sesión del usuario. */
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            /* Verifica si "mandanteLista" tiene valor y lo agrega a las reglas. */


            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }

        /* Se crean reglas de filtrado para usuarios, excluyendo reportes de Colombia. */
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        // Inactivamos reportes para el país Colombia
        array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);


        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.moneda,usuario.fecha_ult,usuario.fecha_crea,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        /* Convierte una cadena JSON de usuarios a un objeto PHP. */
        $usuariosdetalle = json_decode($usuarios);
        foreach ($usuariosdetalle->data as $key3 => $value3) {


            /* Crea un array asociativo con propiedades de un objeto "usuario". */
            $array3 = [];
            $array3["Id"] = $value3->{"usuario.usuario_id"};
            $array3["id"] = $value3->{"usuario.usuario_id"};
            $array3["UserId"] = $value3->{"usuario.usuario_id"};
            $array3["UserName"] = $value3->{"usuario.nombre"};
            $array3["Currency"] = $value3->{"usuario.moneda"};

            /* asigna datos a un array utilizando propiedades de un objeto. */
            $array3["Country"] = $value3->{"usuario.mandante"} . '-' . $value3->{"pais.pais_nom"};
            $array3["Name"] = $value3->{"usuario.nombre"};
            $array3["SystemName"] = 22;
            $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
            $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
            $array3["AgentBalance2"] = $value3->{"punto_venta.cupo_recarga"};

            /* Se asignan valores a un array basado en condiciones de perfil de usuario. */
            $array3["Type"] = $value3->{"usuario_perfil.perfil_id"};
            $array3["PlayerCount"] = 0;
            $array3["Partner"] = $value3->{"usuario.mandante"};

            $array3["flag"] = strtolower($value3->{"pais.iso"});
            switch ($value3->{"usuario_perfil.perfil_id"}) {
                case "CONCESIONARIO":
                    $array3["icon"] = "icon-user-secret";
                    break;
                case "CONCESIONARIO2":
                    $array3["icon"] = "icon-user-secret";
                    break;
                case "CONCESIONARIO3":
                    $array3["icon"] = "icon-user-secret";
                    break;
                case "PUNTOVENTA":
                    $array3["icon"] = "icon-shop";
                    break;
            }

            //$arrayfinal[($value3->{"concesionario.usupadre_id"})][($value3->{"concesionario.usupadre2_id"})][($value3->{"concesionario.usupadre3_id"})][($value3->{"concesionario.usupadre4_id"})][($value3->{"concesionario.usuhijo_id"})]=$array3;

            /* Asigna el contenido de $array3 a una clave específica del array $arrayfinal. */
            $arrayfinal[($value3->{"concesionario.usuhijo_id"})] = $array3;

        }

        /* Se asigna el valor de $arrayfinal a la variable $arrayf. */
        $arrayf = $arrayfinal;


    } else {


        /* obtiene parámetros de una solicitud GET y los asigna a variables. */
        $Perfil_id = $_GET["roleId"];
        $Type = $_GET["Type"];
        $tipoUsuario = "";

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;

        /* establece cuántas filas omitir basándose en parámetros de entrada. */
        $SkeepRows = $params->SkeepRows;

        $MaxRows = $_REQUEST["count"];
        $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }


        /* inicializa variables si están vacías, asignando valores predeterminados. */
        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 100000000;
        }


        /* Se inicializan variables para almacenar menús y reglas en un código. */
        $mismenus = "0";

        $rules = [];


        /* define reglas de filtrado para concesionarios en una sesión específica. */
        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

            if ($Type == "1") {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
            /* Código que define reglas para filtrar usuarios basados en perfil de concesionario. */

            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

            if ($Type == "1") {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*   ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
            /* define reglas para filtrar usuarios según su perfil en un concesionario específico. */

            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

            if ($Type == "1") {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*   ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        } else {


            /* asigna reglas según el tipo de perfil de usuario: "AFILIADOR" o "CONCESIONARIO". */
            if ($Type == "1") {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO", "op" => "eq"));
            }

            // Si el usuario esta condicionado por País

            /* Condiciona reglas basadas en la sesión del usuario y su país o mandante. */
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                /* Agrega reglas de validación si "mandanteLista" no está vacía ni es "-1". */


                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            /* crea reglas de filtrado para usuarios, específicamente para Colombia. */
            array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte un filtro en JSON y obtiene usuarios personalizados de una base de datos. */
            $json2 = json_encode($filtro);


            $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.moneda,usuario.fecha_ult,usuario.fecha_crea,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        }


        /* Se decodifica un JSON de usuarios y se inicializa un arreglo y un saldo. */
        $usuarios = json_decode($usuarios);
        $arrayf = [];

        $balanceAgent = 0;

        foreach ($usuarios->data as $key => $value) {

            /* Código que asigna datos de un objeto a un arreglo asociativo en PHP. */
            $array = [];
            $array["id"] = $value->{"usuario.usuario_id"};
            $array["Id"] = $value->{"usuario.usuario_id"};

            $array["UserName"] = $value->{"usuario.login"};
            $array["Name"] = $value->{"usuario.nombre"};

            /* Asigna datos a un array desde un objeto con propiedades específicas. */
            $array["Email"] = $value->{"punto_venta.email"};
            $array["Phone"] = $value->{"punto_venta.telefono"};
            $array["Address"] = $value->{"punto_venta.direccion"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["RegionName"] = $value->{"pais.pais_nom"};
            $array["DepartmentName"] = $value->{"departamento.depto_nom"};

            /* asigna valores a un array basado en propiedades de un objeto. */
            $array["CityName"] = $value->{"ciudad.ciudad_nom"};
            $array["SystemName"] = 22;
            $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
            $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};
            $array["PlayerCount"] = 0;
            $array["LastLoginDateLabel"] = $value->{"usuario.fecha_ult"};

            /* Se asigna la fecha de creación del usuario a un array y se inicializa un subarray. */
            $array["CreatedDate"] = $value->{"usuario.fecha_crea"};
            $array["Children"] = array();

            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

                /* Se definen reglas para filtrar datos de concesionarios y usuarios en la base. */
                $rules2 = array();

                array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                array_push($rules2, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

                /* Genera un filtro, obtiene detalles de usuarios y estructura los datos en un array. */
                $filtro = array("rules" => $rules2, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                $usuariosdetalle = json_decode($usuariosdetalle);


                foreach ($usuariosdetalle->data as $key2 => $value2) {
                    $array2 = [];

                    $array2["Id"] = $value2->{"usuario.usuario_id"};
                    $array2["id"] = $value2->{"usuario.usuario_id"};

                    $array2["UserName"] = $value2->{"usuario.login"};
                    $array2["Name"] = $value2->{"usuario.nombre"};

                    $array2["SystemName"] = 22;
                    $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
                    $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                    $array2["PlayerCount"] = 0;
                    array_push($array["Children"], $array2);


                }

            } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

                /* Se crean reglas para filtrar datos basados en condiciones específicas. */
                $rules2 = array();

                array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                array_push($rules2, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

                /* Se procesa información de usuarios, creando un array con detalles específicos. */
                $filtro = array("rules" => $rules2, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                $usuariosdetalle = json_decode($usuariosdetalle);


                foreach ($usuariosdetalle->data as $key2 => $value2) {
                    $array2 = [];

                    $array2["Id"] = $value2->{"usuario.usuario_id"};
                    $array2["id"] = $value2->{"usuario.usuario_id"};

                    $array2["UserName"] = $value2->{"usuario.login"};
                    $array2["Name"] = $value2->{"usuario.nombre"};

                    $array2["SystemName"] = 22;
                    $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
                    $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                    $array2["PlayerCount"] = 0;
                    array_push($array["Children"], $array2);


                }

            } else {

                /* Se definen reglas para validar condiciones de concesionarios y usuarios en un sistema. */
                $rules2 = array();

                array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                array_push($rules2, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

                /* Código que filtra y obtiene detalles de usuarios desde una base de datos. */
                $filtro = array("rules" => $rules2, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                $usuariosdetalle = json_decode($usuariosdetalle);

                foreach ($usuariosdetalle->data as $key2 => $value2) {

                    /* Se crea un arreglo asociativo con datos de usuario a partir de $value2. */
                    $array2 = [];

                    $array2["Id"] = $value2->{"usuario.usuario_id"};
                    $array2["id"] = $value2->{"usuario.usuario_id"};
                    $array2["UserName"] = $value2->{"usuario.login"};
                    $array2["Name"] = $value2->{"usuario.nombre"};


                    /* Asigna valores en un arreglo basado en propiedades de un objeto. */
                    $array2["SystemName"] = 22;
                    $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : true);
                    $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                    $array2["PlayerCount"] = 0;
                    $array["LastLoginDateLabel"] = $value2->{"usuario.fecha_ult"};
                    $array2["Children"] = array();

                    if (true) {

                        /* Se crean reglas de validación para filtrar concesionarios y usuarios activos. */
                        $rules3 = array();

                        array_push($rules3, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                        array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO3", "op" => "eq"));
                        array_push($rules3, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

                        /* Genera un filtro y obtiene perfiles de usuario para estructurarlos en un array. */
                        $filtro = array("rules" => $rules3, "groupOp" => "AND");
                        $json3 = json_encode($filtro);

                        $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json3, true);
                        $usuariosdetalle = json_decode($usuariosdetalle);


                        foreach ($usuariosdetalle->data as $key3 => $value3) {
                            $array3 = [];

                            $array3["Id"] = $value3->{"usuario.usuario_id"};
                            $array3["id"] = $value3->{"usuario.usuario_id"};
                            $array3["UserName"] = $value3->{"usuario.login"};
                            $array3["Name"] = $value3->{"usuario.nombre"};
                            $array3["SystemName"] = 22;
                            $array3["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : true);
                            $array3["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                            $array3["PlayerCount"] = 0;
                            array_push($array2["Children"], $array3);


                        }

                    }


                    /* Añade el contenido de `$array2` al final del array `$array["Children"]`. */
                    array_push($array["Children"], $array2);


                }


            }


            /* Agrega un elemento a un arreglo y suma un valor a un balance. */
            array_push($arrayf, $array);

            $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
        }
    }
}


/* Código que establece un objeto de respuesta sin errores y un mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] ["Children"] = $arrayf;


/* asigna valores a un array $response relacionado con jugadores y balances. */
$response["Data"]["DownStreamChildrenCount"] = oldCount($arrayf);
$response["Data"]["DownStreamChildrenBalanceSum"] = $balanceAgent;
$response["Data"]["DownStreamPlayerCount"] = 10;
$response["Data"]["DownStreamPlayerBalanceSum"] = 10;

$response["pos"] = $SkeepRows;

/* Asigna el conteo de usuarios y datos a un arreglo de respuesta. */
$response["total_count"] = $usuarios->count[0]->{".count"};
$response["data"] = $arrayf;


/*
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "DownStreamChildrenCount"=>100,
                "DownStreamChildrenBalanceSum"=>1000,
                "DownStreamPlayerCount"=>100,
                "DownStreamPlayerBalanceSum"=>100,
                "Children"=>array(
                    array(
                        "UserName"=>"test",
                        "AgentId"=>1,
                        "SystemName"=>1,
                        "PlayerCount"=>100,
                        "AgentBalance"=>1000,
                        "Children"=>array(
                            array(
                                "UserName"=>"test2",
                                "SystemName"=>1,

                                "PlayerCount"=>100,
                                "AgentBalance"=>1000,

                            )
                        )
                    )
                )
            );
*/