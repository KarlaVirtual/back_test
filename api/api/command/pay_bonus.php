<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\RuletaDetalle;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensajecampana;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRuleta;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Procesa el pago de un bono.
 *
 * @param object $json Objeto JSON que contiene los parámetros necesarios para el procesamiento del bono.
 * - params: Objeto que contiene:
 *   - bonusId: ID del bono a procesar.
 *   - detailId: ID del detalle del bono.
 * - session: Objeto que contiene:
 *   - usuario: Información del usuario en sesión.
 *
 * @return array Respuesta con el código de estado y datos adicionales.
 * - code: Código de estado de la operación.
 * - rid: Identificador de la solicitud.
 * - data: Datos adicionales de la respuesta.
 *   - reason: Razón del estado de la operación.
 *
 * @throws Exception Si no se puede activar el bono.
 * @throws Exception Si no se puede realizar la transacción.
 */

/* Asignación de valores de JSON y creación de objetos UsuarioMandante y Usuario.  */
$bonusId = $json->params->bonusId;
$usubonoId = $json->params->detailId;


$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

/* Se crea un registro y se establece un límite de 100 filas para bonos. */
$Registro = new Registro('', $Usuario->usuarioId);
$UsuarioId = $Usuario->usuarioId;

$bonusesDatanew = array();

$MaxRows = 100;

/* Define reglas de filtrado para un conjunto de datos relacionados con bonos internos. */
$OrderedItem = 1;
$SkeepRows = 0;
$rules = [];

array_push($rules, array("field" => "bono_interno.tipo", "data" => '5,6,2,3', "op" => "in"));
array_push($rules, array("field" => "bono_interno.mandante", "data" => $Usuario->mandante, "op" => "eq"));

/* construye reglas de filtrado para usuarios en formato JSON. */
array_push($rules, array("field" => "usuario_bono.estado", "data" => 'Q', "op" => "eq"));
array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioBono = new UsuarioBono();


/* Obtiene y decodifica información de bonos de usuario desde la base de datos. */
$UsuarioBonos = $UsuarioBono->getUsuarioBonosCustom(" usuario_bono.*,bono_interno.*", "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json2, true);


$bonos = json_decode($UsuarioBonos);


$UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

/* Obtención de la transacción vinculada a la base de datos */
$Transaction = $UsuarioBonoMySqlDAO->getTransaction();


if ($bonos->count[0]->{".count"} != "0") {

    $bonusesDatanew = array();
    foreach ($bonos->data as $key => $value) {


        if ($value->{"usuario_bono.bono_id"} == $bonusId && $value->{"usuario_bono.usubono_id"} == $usubonoId) {


            $UsuarioBono = new UsuarioBono($usubonoId);
            $BonoInterno = new BonoInterno($bonusId);

            if ($BonoInterno->tipo == '2') {
                // Se verifica si el rol requerido para el usuario bono es mayor que 0
                if ($UsuarioBono->getRollowerRequerido() > 0) {

                    // Se establece el estado del usuario bono a "A"
                    $UsuarioBono->setEstado("A");
                    $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                    $UsuarioBonoMySqlDAO->update($UsuarioBono);

                    // Si el rol requerido es igual a 0
                } elseif ($UsuarioBono->getRollowerRequerido() == 0) {

                    // Se crea una nueva instancia de BonoInterno
                    $BonoInterno = new BonoInterno();

                    // Obtenemos todos los detalles del bono
                    $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $bonusId . "' ";
                    // Se ejecuta la consulta para obtener los detalles del bono
                    $bonoDetalles = $BonoInterno->execQuery('', $sqlDetalleBono);

                    // Se obtiene el valor del bono del objeto $value
                    $valor_bono = $value->{"usuario_bono.valor_bono"};

                    // Se recorren los detalles del bono
                    foreach ($bonoDetalles as $bonoDetalle) {

                        // Se asignan valores del detalle a propiedades del objeto
                        $bonoDetalle->tipo = $bonoDetalle->{'a.tipo'};
                        $bonoDetalle->valor = $bonoDetalle->{'a.valor'};
                        $bonoDetalle->moneda = $bonoDetalle->{'a.moneda'};

                        // Se evalúa el tipo de bono
                        switch ($bonoDetalle->tipo) {

                            case "TIPOSALDO":
                                // Se guarda el valor del tipo de saldo
                                $tiposaldo = $bonoDetalle->valor;
                                break;

                            case "WINBONOID":
                                // Se guarda el ID del bono ganador y se establece el tipo de bono
                                $ganaBonoId = $bonoDetalle->valor;
                                $tipobono = "WINBONOID";

                                break;
                        }
                    }

                    // Se actualiza el valor del bono con el valor del usuario bono
                    $valor_bono = $UsuarioBono->valor;
                    // Verifica si el tipo de saldo no es nulo o vacío
                    if ($tiposaldo != null || $tiposaldo != "") {

                        // Selecciona la acción a realizar según el tipo de saldo
                        switch ($tiposaldo) {
                            case 0:
                                // Actualiza los créditos base en la tabla registro
                                $strSql = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_bono . " where mandante=" . $Usuario->mandante . " and usuario_id=" . $UsuarioId;

                                // Se suma el valor del bono a la variable correspondiente
                                $SumoSaldoValor = $valor_bono;

                                break;

                            case 1:
                                // Actualiza los créditos en la tabla registro
                                $strSql = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_bono . " where mandante=" . $Usuario->mandante . " and usuario_id=" . $UsuarioId;

                                // Se suma el valor del bono a la variable correspondiente
                                $SumoSaldoValor = $valor_bono;

                                break;

                            case 2:
                                // Actualiza el saldo especial en la tabla registro
                                $strSql = "update registro set saldo_especial=saldo_especial+" . $valor_bono . " where mandante=" . $Usuario->mandante . " and usuario_id=" . $UsuarioId;

                                // Se suma el valor del bono a la variable correspondiente
                                $SumoSaldoValor = $valor_bono;
                                break;

                        }

                        // Ejecuta la consulta SQL
                        $BonoInterno->execQuery('', $strSql);

                    }


                    if (($tipobono != null || $tipobono != "") and $tipobono == "WINBONOID") {


                        $Registro = new Registro('', $Usuario->usuarioId);

                        // Instancia el DAO para cargar la ciudad
                        $CiudadMySqlDAO = new CiudadMySqlDAO();
                        $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);

                        // Se inicializan los detalles del bono
                        $detalles = array(
                            "Depositos" => 0,
                            "DepositoEfectivo" => false,
                            "MetodoPago" => 0,
                            "ValorDeposito" => 0,
                            "PaisPV" => 0,
                            "DepartamentoPV" => 0,
                            "CiudadPV" => 0,
                            "PuntoVenta" => 0,
                            "PaisUSER" => $Usuario->paisId,
                            "DepartamentoUSER" => $Ciudad->deptoId,
                            "CiudadUSER" => $Registro->ciudadId,
                            "MonedaUSER" => $Usuario->moneda,

                        );

                        // Llama a la función para agregar el bono
                        $respuesta = $BonoInterno->agregarBonoFree($ganaBonoId, $UsuarioMandante->getUsuarioMandante(), $UsuarioMandante->mandante, $detalles, true, "", $Transaction);

                        // Verifica la respuesta del bono
                        if ($respuesta->Bono == '0' && ($respuesta->WinBonus == null || $respuesta->WinBonus == '')) {
                            throw new Exception("No se puede activar el bono", "300001");
                        }

                    }

                    // Inserta un registro en la tabla bono_log
                    $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $UsuarioId . ",'" . 'D' . "','" . $valor_bono . "','L','" . '1' . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                    $resp2 = $BonoInterno->execQuery('', $sql2);

                    // Crea un nuevo objeto UsuarioBono con el ID del usuario bono
                    $UsuarioBono = new UsuarioBono($usubonoId);
                    $UsuarioBono->setEstado("R");
                    $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                    $UsuarioBonoMySqlDAO->update($UsuarioBono);

                }

            } elseif ($BonoInterno->tipo == '3') {

                if ($UsuarioBono->getRollowerRequerido() > 0) {


                    $UsuarioBono->setEstado("A");
                    $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                    $UsuarioBonoMySqlDAO->update($UsuarioBono);


                } elseif ($UsuarioBono->getRollowerRequerido() == 0) {

                    // Creamos una nueva instancia de BonoInterno
                    $BonoInterno = new BonoInterno();

                    //Obtenemos todos los detalles del bono
                    $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $bonusId . "' ";
                    $bonoDetalles = $BonoInterno->execQuery('', $sqlDetalleBono);

                    // Obtenemos el valor del bono del objeto
                    $valor_bono = $value->{"usuario_bono.valor_bono"};

                    // Iteramos sobre los detalles del bono
                    foreach ($bonoDetalles as $bonoDetalle) {

                        // Asignamos tipo, valor y moneda del detalle del bono
                        $bonoDetalle->tipo = $bonoDetalle->{'a.tipo'};
                        $bonoDetalle->valor = $bonoDetalle->{'a.valor'};
                        $bonoDetalle->moneda = $bonoDetalle->{'a.moneda'};

                        switch ($bonoDetalle->tipo) {

                            case "TIPOSALDO":

                                $tiposaldo = $bonoDetalle->valor;
                                break;

                            case "WINBONOID":

                                $ganaBonoId = $bonoDetalle->valor;
                                $tipobono = "WINBONOID";

                                break;
                        }
                    }

                    $valor_bono = $UsuarioBono->valor;


                    if ($tiposaldo != null || $tiposaldo != "") {

                        switch ($tiposaldo) {
                            case 0:
                                $strSql = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_bono . " where mandante=" . $Usuario->mandante . " and usuario_id=" . $UsuarioId;

                                $SumoSaldoValor = $valor_bono;

                                break;

                            case 1:
                                $strSql = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_bono . " where mandante=" . $Usuario->mandante . " and usuario_id=" . $UsuarioId;

                                $SumoSaldoValor = $valor_bono;

                                break;

                            case 2:
                                $strSql = "update registro set saldo_especial=saldo_especial+" . $valor_bono . " where mandante=" . $Usuario->mandante . " and usuario_id=" . $UsuarioId;


                                $SumoSaldoValor = $valor_bono;
                                break;

                        }

                        $BonoInterno->execQuery('', $strSql);

                    }


                    if (($tipobono != null || $tipobono != "") and $tipobono == "WINBONOID") {


                        $Registro = new Registro('', $Usuario->usuarioId);

                        $CiudadMySqlDAO = new CiudadMySqlDAO();
                        $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);

                        $detalles = array(
                            "Depositos" => 0,
                            "DepositoEfectivo" => false,
                            "MetodoPago" => 0,
                            "ValorDeposito" => 0,
                            "PaisPV" => 0,
                            "DepartamentoPV" => 0,
                            "CiudadPV" => 0,
                            "PuntoVenta" => 0,
                            "PaisUSER" => $Usuario->paisId,
                            "DepartamentoUSER" => $Ciudad->deptoId,
                            "CiudadUSER" => $Registro->ciudadId,
                            "MonedaUSER" => $Usuario->moneda,

                        );

                        $respuesta = $BonoInterno->agregarBonoFree($ganaBonoId, $UsuarioMandante->getUsuarioMandante(), $UsuarioMandante->mandante, $detalles, true, "", $Transaction);

                        if ($respuesta->Bono == '0' && ($respuesta->WinBonus == null || $respuesta->WinBonus == '')) {
                            throw new Exception("No se puede activar el bono", "300001");
                        }

                    }

                    // Inserta un registro en la tabla bono_log
                    $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $UsuarioId . ",'" . 'D' . "','" . $valor_bono . "','L','" . '1' . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                    $resp2 = $BonoInterno->execQuery('', $sql2);

                    $UsuarioBono = new UsuarioBono($usubonoId);
                    $UsuarioBono->setEstado("R");
                    $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                    $UsuarioBonoMySqlDAO->update($UsuarioBono);

                }

            } elseif ($BonoInterno->tipo == '5') {

                $UsuarioBono->setEstado("A");
                $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                $UsuarioBonoMySqlDAO->update($UsuarioBono);
            } elseif ($BonoInterno->tipo == '6') {

                $UsuarioBono->setEstado("A");
                $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                $UsuarioBonoMySqlDAO->update($UsuarioBono);
            }


        } else {

            // Creación de un objeto UsuarioBono a partir del ID proporcionado
            $UsuarioBono = new UsuarioBono($value->{"usuario_bono.usubono_id"});

            $UsuarioBono->setEstado("E");
            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
            $UsuarioBonoMySqlDAO->update($UsuarioBono);


        }

// Inicializa un array para almacenar los datos del bono
        $array = [];
        $array["bonoId"] = $value->{"usuario_bono.bono_id"}; // Asigna el ID del bono al array
        $array["descripcion"] = $value->{"bono_interno.descripcion"}; // Asigna la descripción del bono al array
        $array["valor"] = $value->{"usuario_bono.valor_bono"}; // Asigna el valor del bono al array

// Agrega el array de datos del bono a la colección bonosDatanew
        array_push($bonusesDatanew, $array);
    }

    $Transaction->commit();


}


$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "reason" => "Bono activado."
);


