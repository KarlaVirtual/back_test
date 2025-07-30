<?php

/**
 * Script para analizar código PHP y generar documentación PHPDoc automáticamente.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 *
 * @var mixed $request                 Variable que representa la solicitud HTTP, conteniendo datos como parámetros y
 *                                     encabezados.
 * @var mixed $Conn                    Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'Conn', afectando la lógica de dicha función.
 * @var mixed $bd_servidor             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'bd_servidor', afectando la lógica de dicha función.
 * @var mixed $bd_usuario              Esta variable representa la información del usuario, empleada para identificarlo
 *                                     dentro del sistema.
 * @var mixed $bd_clave                Esta variable guarda la clave o contraseña para autenticación y acceso seguro al
 *                                     sistema (generalmente encriptada).
 * @var mixed $bd_nombre               Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'bd_nombre', afectando la lógica de dicha función.
 * @var mixed $req                     Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'req', afectando la lógica de dicha función.
 * @var mixed $obj                     Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'obj', afectando la lógica de dicha función.
 * @var mixed $chequeo                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'chequeo', afectando la lógica de dicha función.
 * @var mixed $clave_ticket            Esta variable guarda la clave o contraseña para autenticación y acceso seguro al
 *                                     sistema (generalmente encriptada).
 * @var mixed $respuesta               Esta variable se utiliza para almacenar y manipular la respuesta de una
 *                                     operación.
 * @var mixed $seguir                  Variable que indica si se debe continuar con una operación o proceso.
 * @var mixed $key_winplay             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'key_winplay', afectando la lógica de dicha función.
 * @var mixed $tipo                    Variable que indica el tipo de elemento o evento (por ejemplo, tipo de apuesta o
 *                                     evento).
 * @var mixed $key_usuario             Esta variable representa la información del usuario, empleada para identificarlo
 *                                     dentro del sistema.
 * @var mixed $trans_id                Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'trans_id', afectando la lógica de dicha función.
 * @var mixed $valor                   Variable que almacena un valor monetario o numérico.
 * @var mixed $ticket_id               Variable que almacena el identificador único de un boleto o ticket.
 * @var mixed $game_reference          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'game_reference', afectando la lógica de dicha función.
 * @var mixed $bet_status              Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'bet_status', afectando la lógica de dicha función.
 * @var mixed $cant_lineas             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'cant_lineas', afectando la lógica de dicha función.
 * @var mixed $cant_banker             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'cant_banker', afectando la lógica de dicha función.
 * @var mixed $premio_proy             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'premio_proy', afectando la lógica de dicha función.
 * @var mixed $bonus_id                Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'bonus_id', afectando la lógica de dicha función.
 * @var mixed $bonusplan_id            Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'bonusplan_id', afectando la lógica de dicha función.
 * @var mixed $dir_ip                  Variable que almacena la dirección IP de un usuario o servidor.
 * @var mixed $extuser_id              Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'extuser_id', afectando la lógica de dicha función.
 * @var mixed $key_ref                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'key_ref', afectando la lógica de dicha función.
 * @var mixed $key_casino_ref          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'key_casino_ref', afectando la lógica de dicha función.
 * @var mixed $auth_casino             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'auth_casino', afectando la lógica de dicha función.
 * @var mixed $datosSql                Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'datosSql', afectando la lógica de dicha función.
 * @var mixed $winplay                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'winplay', afectando la lógica de dicha función.
 * @var mixed $mandante                Variable que almacena el mandante o entidad responsable de una operación.
 * @var mixed $datos_RS_query          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'datos_RS_query', afectando la lógica de dicha función.
 * @var mixed $datos_RS                Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'datos_RS', afectando la lógica de dicha función.
 * @var mixed $strSql                  Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'strSql', afectando la lógica de dicha función.
 * @var mixed $contSql                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'contSql', afectando la lógica de dicha función.
 * @var mixed $perfil                  Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'perfil', afectando la lógica de dicha función.
 * @var mixed $usuario_id              Esta variable representa la información del usuario, empleada para identificarlo
 *                                     dentro del sistema.
 * @var mixed $nombre                  Variable que almacena el nombre de un elemento, objeto o entidad.
 * @var mixed $dias_expira             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'dias_expira', afectando la lógica de dicha función.
 * @var mixed $req_cheque_usu          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'req_cheque_usu', afectando la lógica de dicha función.
 * @var mixed $pais_id_usu             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'pais_id_usu', afectando la lógica de dicha función.
 * @var mixed $validaSql               Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'validaSql', afectando la lógica de dicha función.
 * @var mixed $valida_RS_query         Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valida_RS_query', afectando la lógica de dicha función.
 * @var mixed $valida_RS               Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valida_RS', afectando la lógica de dicha función.
 * @var mixed $ticket_existe           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'ticket_existe', afectando la lógica de dicha función.
 * @var mixed $premio_pagado           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'premio_pagado', afectando la lógica de dicha función.
 * @var mixed $freebet                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'freebet', afectando la lógica de dicha función.
 * @var mixed $estado_ticket           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'estado_ticket', afectando la lógica de dicha función.
 * @var mixed $item                    Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $fecha_tmp               Esta variable almacena una fecha, que puede indicar la creación, modificación o
 *                                     vencimiento de un registro.
 * @var mixed $hora_tmp                Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'hora_tmp', afectando la lógica de dicha función.
 * @var mixed $nueva_fecha             Esta variable almacena una fecha, que puede indicar la creación, modificación o
 *                                     vencimiento de un registro.
 * @var mixed $fecha                   Esta variable almacena una fecha, que puede indicar la creación, modificación o
 *                                     vencimiento de un registro.
 * @var mixed $hora                    Variable que almacena una hora específica.
 * @var mixed $evento                  Variable que almacena información sobre un evento específico.
 * @var mixed $evento_id               Variable que almacena el identificador único de un evento.
 * @var mixed $agrupador               Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'agrupador', afectando la lógica de dicha función.
 * @var mixed $agrupador_id            Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'agrupador_id', afectando la lógica de dicha función.
 * @var mixed $opcion                  Variable que almacena una opción seleccionada en un evento o apuesta.
 * @var mixed $logro                   Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'logro', afectando la lógica de dicha función.
 * @var mixed $sportid                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'sportid', afectando la lógica de dicha función.
 * @var mixed $matchid                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'matchid', afectando la lógica de dicha función.
 * @var mixed $ligaid                  Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'ligaid', afectando la lógica de dicha función.
 * @var mixed $creditos_base           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'creditos_base', afectando la lógica de dicha función.
 * @var mixed $saldo_actual            Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'saldo_actual', afectando la lógica de dicha función.
 * @var mixed $baseSql                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'baseSql', afectando la lógica de dicha función.
 * @var mixed $base_RS_query           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'base_RS_query', afectando la lógica de dicha función.
 * @var mixed $base_RS                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'base_RS', afectando la lógica de dicha función.
 * @var mixed $baseSqlFreebet          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'baseSqlFreebet', afectando la lógica de dicha función.
 * @var mixed $base_RS_queryFreebet    Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'base_RS_queryFreebet', afectando la lógica de dicha
 *                                     función.
 * @var mixed $bonoid                  Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'bonoid', afectando la lógica de dicha función.
 * @var mixed $usubono_id              Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'usubono_id', afectando la lógica de dicha función.
 * @var mixed $array                   Variable que almacena una lista o conjunto de datos.
 * @var mixed $detalles                Variable que almacena detalles adicionales o información más específica sobre un
 *                                     proceso o elemento.
 * @var mixed $detalleValorApuesta     Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'detalleValorApuesta', afectando la lógica de dicha
 *                                     función.
 * @var mixed $detallesFinal           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'detallesFinal', afectando la lógica de dicha función.
 * @var mixed $detalleSelecciones      Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'detalleSelecciones', afectando la lógica de dicha función.
 * @var mixed $row                     Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'row', afectando la lógica de dicha función.
 * @var mixed $sqlDetalleBono          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'sqlDetalleBono', afectando la lógica de dicha función.
 * @var mixed $base_RS_queryDetalle    Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'base_RS_queryDetalle', afectando la lógica de dicha
 *                                     función.
 * @var mixed $cumplecondicion         Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'cumplecondicion', afectando la lógica de dicha función.
 * @var mixed $cumplecondicionproducto Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'cumplecondicionproducto', afectando la lógica de dicha
 *                                     función.
 * @var mixed $condicionesproducto     Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'condicionesproducto', afectando la lógica de dicha
 *                                     función.
 * @var mixed $valorapostado           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valorapostado', afectando la lógica de dicha función.
 * @var mixed $valorrequerido          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valorrequerido', afectando la lógica de dicha función.
 * @var mixed $valorASumar             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valorASumar', afectando la lógica de dicha función.
 * @var mixed $tipocomparacion         Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'tipocomparacion', afectando la lógica de dicha función.
 * @var mixed $row2                    Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'row2', afectando la lógica de dicha función.
 * @var mixed $tipoProducto            Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'tipoProducto', afectando la lógica de dicha función.
 * @var mixed $fechaBono               Esta variable almacena una fecha, que puede indicar la creación, modificación o
 *                                     vencimiento de un registro.
 * @var mixed $fecha_actual            Esta variable almacena una fecha, que puede indicar la creación, modificación o
 *                                     vencimiento de un registro.
 * @var mixed $betmode                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'betmode', afectando la lógica de dicha función.
 * @var mixed $tipo_apuesta            Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'tipo_apuesta', afectando la lógica de dicha función.
 * @var mixed $referido_id             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'referido_id', afectando la lógica de dicha función.
 * @var mixed $punto_usuario_venta     Esta variable representa la información del usuario, empleada para identificarlo
 *                                     dentro del sistema.
 * @var mixed $valor_apostado          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valor_apostado', afectando la lógica de dicha función.
 * @var mixed $valor_base              Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valor_base', afectando la lógica de dicha función.
 * @var mixed $valor_adicional         Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valor_adicional', afectando la lógica de dicha función.
 * @var mixed $valor_especial          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valor_especial', afectando la lógica de dicha función.
 * @var mixed $clave_encrypt           Esta variable guarda la clave o contraseña para autenticación y acceso seguro al
 *                                     sistema (generalmente encriptada).
 * @var mixed $bonusamount             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'bonusamount', afectando la lógica de dicha función.
 * @var mixed $valorUsuario            Esta variable representa la información del usuario, empleada para identificarlo
 *                                     dentro del sistema.
 * @var mixed $valorFreeBet            Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valorFreeBet', afectando la lógica de dicha función.
 * @var mixed $validaSql2              Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'validaSql2', afectando la lógica de dicha función.
 * @var mixed $valida_RS_query2        Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valida_RS_query2', afectando la lógica de dicha función.
 * @var mixed $valida_RS2              Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'valida_RS2', afectando la lógica de dicha función.
 * @var mixed $strPremiado             Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'strPremiado', afectando la lógica de dicha función.
 * @var mixed $strFechaCierre          Esta variable almacena una fecha, que puede indicar la creación, modificación o
 *                                     vencimiento de un registro.
 * @var mixed $validabonoSql           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'validabonoSql', afectando la lógica de dicha función.
 * @var mixed $validabono_RS_query     Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'validabono_RS_query', afectando la lógica de dicha
 *                                     función.
 * @var mixed $validabono_RS           Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'validabono_RS', afectando la lógica de dicha función.
 * @var mixed $x                       Variable de propósito general.
 * @var mixed $r                       Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'r', afectando la lógica de dicha función.
 * @var mixed $d                       Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'd', afectando la lógica de dicha función.
 * @var mixed $token_nuevo             Esta variable contiene el token de autenticación, utilizado para verificar y
 *                                     autorizar peticiones de forma segura.
 * @var mixed $retorno                 Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'retorno', afectando la lógica de dicha función.
 * @var mixed $saldo                   Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'saldo', afectando la lógica de dicha función.
 * @var mixed $saldoSql                Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'saldoSql', afectando la lógica de dicha función.
 * @var mixed $saldo_RS_query          Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'saldo_RS_query', afectando la lógica de dicha función.
 * @var mixed $saldo_RS                Esta variable se utiliza en la función 'ws_itainment_procesar' para almacenar y
 *                                     procesar el valor de 'saldo_RS', afectando la lógica de dicha función.
 */

/**
 * Procesa una solicitud HTTP para realizar diversas operaciones relacionadas con apuestas.
 *
 * @param mixed $request La solicitud HTTP que contiene los datos necesarios para procesar la operación.
 *
 * @return string Respuesta indicando el resultado de la operación (éxito o error).
 */
function ws_itainment_procesar($request)
{
    // Archivo que incluye todas las variables globales necesarias
    include "global.php";
    include "funciones.php";
    date_default_timezone_set('America/Bogota');

    //Abre la conexion a la base de datos
    $Conn = ConectarBD($bd_servidor, $bd_usuario, $bd_clave, $bd_nombre);

    //Recibe los parametros pasados en la URL
    $req = $request;
    $obj = json_decode($req);
    $chequeo = "_FALSE";
    $clave_ticket = "0";

    //Inicializa la respuesta
    $respuesta = "";

    //Valida los campos ingresados
    $seguir = true;
    if (oldCount($obj) <= 0) {
        $seguir = false;
    }

    //Formatea las variables separadas
    if ($seguir) {
        //Captura las variables
        $key_winplay = $obj->{'KeyWinplay'};
        $tipo = DepurarCaracteres($obj->{'TypeWinplay'});
        $key_usuario = DepurarCaracteres($obj->{'Token'});
        $trans_id = DepurarCaracteres($obj->{'TransactionID'});
        $valor = DepurarCaracteres($obj->{'valor'});
        $ticket_id = DepurarCaracteres($obj->{'ticketid'});
        $game_reference = DepurarCaracteres($obj->{'GameReference'});
        $bet_status = DepurarCaracteres($obj->{'BetStatus'});
        $cant_lineas = DepurarCaracteres($obj->{'EventCount'});
        $cant_banker = DepurarCaracteres($obj->{'BankerCount'});
        $premio_proy = DepurarCaracteres($obj->{'PremioProyectado'});
        $bonus_id = DepurarCaracteres($obj->{'BonusId'});
        $bonusplan_id = DepurarCaracteres($obj->{'BonusPlanId'});
        $dir_ip = DepurarCaracteres($obj->{'ClienteIP'});
        $extuser_id = DepurarCaracteres($obj->{'usuarioid'});

        //Valida los campos pasados
        if ( ! ValidarCampo($key_winplay, "S", "T", 500)) {
            $seguir = false;
        }
        if ( ! ValidarCampo($tipo, "S", "T", 15)) {
            $seguir = false;
        } else {
            if ($tipo != "BET" and $tipo != "WIN" and $tipo != "REFUND" and $tipo != "LOSS" and $tipo != "BETCHECK" and $tipo != "NEWDEBIT" and $tipo != "NEWCREDIT" and $tipo != "CASHOUT" and $tipo != "STAKEDECREASE" and $tipo != "WINBONUS" and $tipo != "CODEBONUS") {
                $seguir = false;
            }
        }
        if ( ! ValidarCampo($key_usuario, "S", "N", 20)) {
            $seguir = false;
        }
        if ( ! ValidarCampo($valor, "S", "N", 20)) {
            $seguir = false;
        }
        if ( ! ValidarCampo($ticket_id, "S", "T", 15)) {
            $seguir = false;
        }
        if ( ! ValidarCampo($game_reference, "S", "T", 50)) {
            $seguir = false;
        }
        if ( ! ValidarCampo($bet_status, "S", "T", 1)) {
            $seguir = false;
        }
        if ( ! ValidarCampo($trans_id, "S", "T", 20)) {
            $seguir = false;
        }
        if ( ! ValidarCampo($extuser_id, "S", "N", 20)) {
            $seguir = false;
        }
    }

    //Verifica si aun se puede continuar luego de validacion previa de campos
    if ($seguir) {
        //Verifica si la clave de integracion suministrada es correcta
        $key_ref = hash_hmac("sha256", $key_casino_ref, $auth_casino, false);
        if ($key_winplay != $key_ref) {
            $respuesta = "ERROR_99_No dispone de los privilegios suficientes para acceder a este servicio.";
        } else {
            //Valida que el usuario suministrado exista
            if ($tipo == "CODEBONUS") {
                $datosSql = "select a.usuario_id,a.nombre,case when c.tipo='U' then 'USER' when c.tipo='A' then 'ADMIN' else 'COMERCIAL' end perfil,d.dias_expira,e.req_cheque,a.pais_id from " . $winplay . ".usuario a inner join " . $winplay . ".usuario_perfil b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) inner join " . $winplay . ".perfil c on (b.perfil_id=c.perfil_id) inner join " . $winplay . ".configuracion d on (a.mandante=d.mandante and d.config_id=1) inner join " . $winplay . ".pais e on (a.pais_id=e.pais_id) where a.mandante=" . $mandante . " and a.usuario_id=" . $key_usuario;
            } else {
                $datosSql = "select a.usuario_id,a.nombre,case when c.tipo='U' then 'USER' when c.tipo='A' then 'ADMIN' else 'COMERCIAL' end perfil,d.dias_expira,e.req_cheque,a.pais_id from " . $winplay . ".usuario a inner join " . $winplay . ".usuario_perfil b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) inner join " . $winplay . ".perfil c on (b.perfil_id=c.perfil_id) inner join " . $winplay . ".configuracion d on (a.mandante=d.mandante and d.config_id=1) inner join " . $winplay . ".pais e on (a.pais_id=e.pais_id) where a.mandante=" . $mandante . " and a.usuario_id=" . $extuser_id;
            }
            $datos_RS_query = ProcesarConsulta($datosSql, $Conn);
            $datos_RS = mysql_fetch_array($datos_RS_query);
            if (($datos_RS == 0)) {
                $respuesta = "ERROR_90_El usuario suministrado no se encuentra registrado en la base de datos.";
            } else {
                //Armar la nueva descripcion
                $strSql = array();
                $contSql = 0;

                //Captura los valores necesarios
                $perfil = $datos_RS["perfil"];
                $usuario_id = $datos_RS["usuario_id"];
                $nombre = $datos_RS["nombre"];
                $dias_expira = $datos_RS["dias_expira"];
                $req_cheque_usu = $datos_RS["req_cheque"];
                $pais_id_usu = $datos_RS["pais_id"];

                //Actualiza el saldo
                $valor = $valor / 100;

                //Valida el numero de la transaccion
                $validaSql = "select a.transaccion_id from " . $winplay . ".it_transaccion a where a.mandante=" . $mandante . " and a.transaccion_id='" . $trans_id . "'";
                $valida_RS_query = ProcesarConsulta($validaSql, $Conn);
                $valida_RS = mysql_fetch_array($valida_RS_query);
                if (($valida_RS == 0)) {
                    //Valida  el numero del ticket
                    $ticket_existe = false;
                    $premio_pagado = "N";
                    $validaSql = "select a.it_ticket_id,a.premio_pagado from " . $winplay . ".it_ticket_enc a LEFT OUTER JOIN it_ticket_enc_info1 b ON (a.ticket_id = b.ticket_id AND tipo='FREEBET') where a.mandante=" . $mandante . " and a.ticket_id='" . $ticket_id . "'";
                    $valida_RS_query = ProcesarConsulta($validaSql, $Conn);
                    $valida_RS = mysql_fetch_array($valida_RS_query);
                    if ( ! ($valida_RS == 0)) {
                        $ticket_existe = true;
                        $premio_pagado = $valida_RS["premio_pagado"];
                        $freebet = $valida_RS["freebet"]; //Campo para FreeBet

                        if ($freebet == "" || ! is_numeric($freebet)) {
                            $freebet = 0;
                        }
                    }

                    //Valida que estado le debe poner al ticket
                    $estado_ticket = "I";
                    if ($bet_status == "C" or $bet_status == "R" or $bet_status == "W") {
                        $estado_ticket = "A";
                    }

                    //Verifica cual es el tipo de informacion requerida
                    switch ($tipo) {
                        //Tipo cuando realizan la grabacion de una apuesta
                        case "BET":
                            //Valida los campos pasados
                            if ( ! ValidarCampo($cant_lineas, "S", "N", 3)) {
                                $seguir = false;
                            }
                            if ( ! ValidarCampo($cant_banker, "S", "N", 3)) {
                                $seguir = false;
                            }
                            if ( ! ValidarCampo($premio_proy, "S", "N", 15)) {
                                $seguir = false;
                            }

                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ($ticket_existe) {
                                    $seguir = false;
                                    $respuesta = "ERROR_03_" . $valida_RS["it_ticket_id"] . "_El numero de ticket ya se encuentra registrado en la base de datos.";
                                } else {
                                    //Recorre todas las lineas del ticket para grabar el detalle
                                    foreach ($obj->{'EventsDescription'} as $item) {
                                        //Edita la fecha y la graba en hora colombiana
                                        $fecha_tmp = DepurarCaracteres($item->{'fecha'});
                                        $hora_tmp = DepurarCaracteres($item->{'hora'});
                                        $nueva_fecha = strtotime('-5 hour', strtotime($fecha_tmp . " " . $hora_tmp));
                                        $nueva_fecha = date('Y-m-d H:i:s', $nueva_fecha);
                                        $fecha = substr($nueva_fecha, 0, 10);
                                        $hora = substr($nueva_fecha, 11, 5);

                                        //Captura los elementos del detalle
                                        $evento = DepurarCaracteres($item->{'evento'});
                                        $evento_id = DepurarCaracteres($item->{'eventoid'});
                                        $agrupador = DepurarCaracteres($item->{'agrupador'});
                                        $agrupador_id = DepurarCaracteres($item->{'agrupadorid'});
                                        $opcion = DepurarCaracteres($item->{'opcion'});
                                        $logro = DepurarCaracteres($item->{'logro'});

                                        $sportid = DepurarCaracteres($item->{'sportid'});
                                        $matchid = DepurarCaracteres($item->{'matchid'});
                                        $ligaid = DepurarCaracteres($item->{'ligaid'});

                                        if (empty($matchid)) {
                                            $matchid = $evento_id;
                                        }

                                        $premio_proy = $premio_proy / 100;

                                        //Valida que los campos del detalle esten correctos
                                        if ( ! ValidarCampo($evento, "S", "T", 100)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($fecha, "S", "F", 10)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($hora, "S", "H", 5)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($evento_id, "S", "N", 20)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($agrupador, "S", "T", 100)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($agrupador_id, "S", "T", 15)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($opcion, "S", "T", 100)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($logro, "S", "N", 15)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($sportid, "S", "N", 15)) {
                                            $seguir = false;
                                            break;
                                        }
                                        if ( ! ValidarCampo($matchid, "S", "N", 15)) {
                                            $seguir = false;
                                            break;
                                        }

                                        if ( ! is_numeric($ligaid)) {
                                            $ligaid = 0;
                                        }

                                        //Inserta la cabecera del ticket
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "insert into " . $winplay . ".it_ticket_det (ticket_id,apuesta,apuesta_id,agrupador,agrupador_id,opcion,logro,fecha_evento,hora_evento,mandante) values ('" . $ticket_id . "','" . $evento . "'," . $evento_id . ",'" . $agrupador . "','" . $agrupador_id . "','" . $opcion . "'," . $logro . ",'" . $fecha . "','" . $hora . "'," . $mandante . ")";
                                    }

                                    //Verifica si el detalle pudo ser procesado
                                    if ($seguir) {
                                        //Trae el valor de creditos base actual
                                        $creditos_base = 0;
                                        $saldo_actual = 0;

                                        //Verifica que perfil tiene
                                        if ($perfil == "USER") {
                                            $baseSql = "select a.creditos_base,round(a.creditos_base+a.creditos) saldo_actual from " . $winplay . ".registro a where a.mandante=" . $mandante . " and a.usuario_id=" . $usuario_id;
                                        } else {
                                            $baseSql = "select a.creditos_base,round(a.creditos_base+a.creditos) saldo_actual from " . $winplay . ".punto_venta a where a.mandante=" . $mandante . " and a.usuario_id=" . $usuario_id;
                                        }
                                        $base_RS_query = ProcesarConsulta($baseSql, $Conn);
                                        $base_RS = mysql_fetch_array($base_RS_query);
                                        if ( ! ($base_RS == 0)) {
                                            $creditos_base = $base_RS["creditos_base"];
                                            $saldo_actual = $base_RS["saldo_actual"];
                                        }

                                        //Verificamos si tiene algun freebet Disponible

                                        $baseSqlFreebet = "select a.usubono_id,a.bono_id from " . $winplay . ".usuario_bono a INNER JOIN bono_interno bi ON (a.bono_id=bi.bono_id) where bi.tipo=6 AND a.estado='A' AND a.usuario_id='" . $usuario_id . "'";
                                        $base_RS_queryFreebet = ProcesarConsulta($baseSqlFreebet, $Conn);

                                        $bonoid = 0;
                                        $usubono_id = 0;

                                        $array = array();

                                        foreach ($obj->{'EventsDescription'} as $item) {
                                            $detalles = array(
                                                "Deporte" => $item->{'sportid'},
                                                "Liga" => $item->{'ligaid'},
                                                "Evento" => $item->{'eventoid'},
                                                "Cuota" => $item->{'logro'},
                                                "TipoApuesta" => $item->{'logro'}

                                            );
                                            $detalleValorApuesta = $valor;


                                            array_push($array, $detalles);
                                        }
                                        $detallesFinal = json_decode(json_encode($array));

                                        $detalleSelecciones = $detallesFinal;


                                        while ($row = mysql_fetch_assoc($base_RS_queryFreebet)) {
                                            if ($usubono_id == 0) {
                                                //Obtenemos todos los detalles del bono
                                                $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $row["bono_id"] . "'";
                                                $base_RS_queryDetalle = ProcesarConsulta($sqlDetalleBono, $Conn);

                                                //Inicializamos variables
                                                $cumplecondicion = true;
                                                $cumplecondicionproducto = false;
                                                $condicionesproducto = 0;
                                                $bonoid = 0;
                                                $valorapostado = 0;
                                                $valorrequerido = 0;
                                                $valorASumar = 0;

                                                if ($row["condicional"] == 'NA' || $row["condicional"] == '') {
                                                    $tipocomparacion = "OR";
                                                } else {
                                                    $tipocomparacion = $row["condicional"];
                                                }


                                                while ($row2 = mysql_fetch_assoc($base_RS_queryDetalle)) {
                                                    switch ($row2["tipo"]) {
                                                        case "TIPOPRODUCTO":

                                                            $tipoProducto = $row2["valor"];
                                                            break;

                                                        case "EXPDIA":
                                                            $fechaBono = date(
                                                                'Y-m-d H:i:ss',
                                                                strtotime(
                                                                    $row["fecha_crea"] . ' + ' . $row2["valor"] . ' days'
                                                                )
                                                            );
                                                            $fecha_actual = date("Y-m-d H:i:ss", time());

                                                            if ($fechaBono < $fecha_actual) {
                                                                $cumplecondicion = false;
                                                            }

                                                            break;

                                                        case "EXPFECHA":
                                                            $fechaBono = date(
                                                                'Y-m-d H:i:ss',
                                                                strtotime($row2["valor"])
                                                            );
                                                            $fecha_actual = strtotime(date("Y-m-d H:i:ss", time()));

                                                            if ($fechaBono < $fecha_actual) {
                                                                $cumplecondicion = false;
                                                            }
                                                            break;


                                                        case "LIVEORPREMATCH":

                                                            if ($row2["valor"] == 2) {
                                                                if ($betmode == "PreLive") {
                                                                    $cumplecondicionproducto = true;
                                                                } else {
                                                                    $cumplecondicionproducto = false;
                                                                }
                                                            }

                                                            if ($row2["valor"] == 1) {
                                                                if ($betmode == "Live") {
                                                                    $cumplecondicionproducto = true;
                                                                } else {
                                                                    $cumplecondicionproducto = false;
                                                                }
                                                            }

                                                            if ($row2["valor"] == 0) {
                                                            }

                                                            break;

                                                        case "MINSELCOUNT":

                                                            if ($row2["valor"] > oldCount($detalleSelecciones)) {
                                                                $cumplecondicion = false;
                                                            }

                                                            break;

                                                        case "MINSELPRICE":

                                                            foreach ($detalleSelecciones as $item) {
                                                                if ($row2["valor"] > $item->Cuota) {
                                                                    $cumplecondicion = false;
                                                                }
                                                            }


                                                            break;


                                                        case "MINBETPRICE":


                                                            if ($row2["valor"] > $detalleValorApuesta) {
                                                                $cumplecondicion = false;
                                                            }

                                                            break;

                                                        case "MINAMOUNT":

                                                            if ($row2["valor"] > $detalleValorApuesta) {
                                                                $cumplecondicion = false;
                                                            }

                                                            break;

                                                        case "MAXAMOUNT":

                                                            if ($row2["valor"] < $detalleValorApuesta) {
                                                                $cumplecondicion = false;
                                                            }

                                                            break;
                                                        case "FROZEWALLET":

                                                            break;

                                                        case "SUPPRESSWITHDRAWAL":

                                                            break;

                                                        case "SCHEDULECOUNT":

                                                            break;

                                                        case "SCHEDULENAME":

                                                            break;

                                                        case "SCHEDULEPERIOD":

                                                            break;


                                                        case "SCHEDULEPERIODTYPE":

                                                            break;

                                                        case "ITAINMENT1":

                                                            foreach ($detalleSelecciones as $item) {
                                                                if ($tipocomparacion == "OR") {
                                                                    if ($row2["valor"] == $item->Deporte) {
                                                                        $cumplecondicionproducto = true;
                                                                    }
                                                                } elseif ($tipocomparacion == "AND") {
                                                                    if ($row2["valor"] != $item->Deporte) {
                                                                        $cumplecondicionproducto = false;
                                                                    }

                                                                    if ($condicionesproducto == 0) {
                                                                        if ($row2["valor"] == $item->Deporte) {
                                                                            $cumplecondicionproducto = true;
                                                                        }
                                                                    } else {
                                                                        if ($row2["valor"] == $item->Deporte && $cumplecondicionproducto) {
                                                                            $cumplecondicionproducto = true;
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $condicionesproducto++;
                                                            break;

                                                        case "ITAINMENT3":


                                                            foreach ($detalleSelecciones as $item) {
                                                                if ($tipocomparacion == "OR") {
                                                                    if ($row2["valor"] == $item->Liga) {
                                                                        $cumplecondicionproducto = true;
                                                                    }
                                                                } elseif ($tipocomparacion == "AND") {
                                                                    if ($row2["valor"] != $item->Liga) {
                                                                        $cumplecondicionproducto = false;
                                                                    }

                                                                    if ($condicionesproducto == 0) {
                                                                        if ($row2["valor"] == $item->Liga) {
                                                                            $cumplecondicionproducto = true;
                                                                        }
                                                                    } else {
                                                                        if ($row2["valor"] == $item->Liga && $cumplecondicionproducto) {
                                                                            $cumplecondicionproducto = true;
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $condicionesproducto++;

                                                            break;
                                                        case "ITAINMENT4":


                                                            foreach ($detalleSelecciones as $item) {
                                                                if ($tipocomparacion == "OR") {
                                                                    if ($row2["valor"] == $item->Evento) {
                                                                        $cumplecondicionproducto = true;
                                                                    }
                                                                } elseif ($tipocomparacion == "AND") {
                                                                    if ($row2["valor"] != $item->Evento) {
                                                                        $cumplecondicionproducto = false;
                                                                    }

                                                                    if ($condicionesproducto == 0) {
                                                                        if ($row2["valor"] == $item->Evento) {
                                                                            $cumplecondicionproducto = true;
                                                                        }
                                                                    } else {
                                                                        if ($row2["valor"] == $item->Evento && $cumplecondicionproducto) {
                                                                            $cumplecondicionproducto = true;
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $condicionesproducto++;

                                                            break;
                                                        case "ITAINMENT5":


                                                            foreach ($detalleSelecciones as $item) {
                                                                if ($tipocomparacion == "OR") {
                                                                    if ($row2["valor"] == $item->DeporteMercado) {
                                                                        $cumplecondicionproducto = true;
                                                                    }
                                                                } elseif ($tipocomparacion == "AND") {
                                                                    if ($row2["valor"] != $item->DeporteMercado) {
                                                                        $cumplecondicionproducto = false;
                                                                    }

                                                                    if ($condicionesproducto == 0) {
                                                                        if ($row2["valor"] == $item->DeporteMercado) {
                                                                            $cumplecondicionproducto = true;
                                                                        }
                                                                    } else {
                                                                        if ($row2["valor"] == $item->DeporteMercado && $cumplecondicionproducto) {
                                                                            $cumplecondicionproducto = true;
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $condicionesproducto++;

                                                            break;

                                                        default:


                                                            break;
                                                    }
                                                }


                                                if ($cumplecondicion && $cumplecondicionproducto) {
                                                    $bonoid = $row["bono_id"];
                                                    $usubono_id = $row["usubono_id"];
                                                }
                                            }
                                        }

                                        if ($usubono_id != 0) {
                                            $tipo_apuesta = "N";
                                            $referido_id = 0;
                                            $punto_usuario_venta = 0;

                                            $valor_apostado = $valor;
                                            $valor_base = 0;
                                            $valor_adicional = 0;
                                            $valor_especial = 0;

                                            //Inserta la cabecera del ticket
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "insert into " . $winplay . ".it_ticket_enc (transaccion_id,ticket_id,vlr_apuesta,vlr_premio,usuario_id,game_reference,bet_status,cant_lineas,fecha_crea,hora_crea,clave,mandante,dir_ip) values ('" . $trans_id . "','" . $ticket_id . "'," . $valor . "," . $premio_proy . "," . $usuario_id . ",'" . $game_reference . "','" . $bet_status . "'," . $cant_lineas . ",'" . date(
                                                    'Y-m-d'
                                                ) . "','" . date(
                                                    'H:i:s'
                                                ) . "',aes_encrypt('" . $clave_ticket . "','" . $clave_encrypt . "')," . $mandante . ",'" . $dir_ip . "')";

                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "INSERT INTO it_ticket_enc_info1 (ticket_id,tipo,valor)  VALUES ( " . $ticket_id . ",'FREEBET'," . $usubono_id . ") ";

                                            //Inserta el log de auditoria de la apuesta
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                                    'Y-m-d'
                                                ) . "','" . date(
                                                    'H:i:s'
                                                ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                            //Actualiza el Bono Freebet Como redimido
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update " . $winplay . ".usuario_bono set estado='R',valor='" . $valor . "',externo_id='" . $ticket_id . "' where usubono_id=" . $usubono_id . " and estado='A'";

                                            $bonusamount = $valor;
                                        } else {
                                            //Verifica si tiene saldo para ejecutar la transaccion
                                            if (floatval($saldo_actual) >= floatval($valor)) {
                                                //General la clave del ticket
                                                $clave_ticket = GenerarClaveTicket2(6);

                                                //Inserta la cabecera del ticket
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql] = "insert into " . $winplay . ".it_ticket_enc (transaccion_id,ticket_id,vlr_apuesta,vlr_premio,usuario_id,game_reference,bet_status,cant_lineas,fecha_crea,hora_crea,clave,mandante,dir_ip) values ('" . $trans_id . "','" . $ticket_id . "'," . $valor . "," . $premio_proy . "," . $usuario_id . ",'" . $game_reference . "','" . $bet_status . "'," . $cant_lineas . ",'" . date(
                                                        'Y-m-d'
                                                    ) . "','" . date(
                                                        'H:i:s'
                                                    ) . "',aes_encrypt('" . $clave_ticket . "','" . $clave_encrypt . "')," . $mandante . ",'" . $dir_ip . "')";

                                                //Inserta el log de auditoria de la apuesta
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                                        'Y-m-d'
                                                    ) . "','" . date(
                                                        'H:i:s'
                                                    ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                                //Calcula el valor de creditos base que tiene que restar de acuerdo al valor de la apuesta
                                                if ($valor > $creditos_base) {
                                                    $valor_base = $creditos_base;
                                                    $valor_adicional = $valor - $creditos_base;
                                                } else {
                                                    $valor_base = $valor;
                                                    $valor_adicional = 0;
                                                }

                                                //Resta el valor de la apuesta del valor del credito disponible
                                                $contSql = $contSql + 1;
                                                if ($perfil == "USER") {
                                                    $strSql[$contSql] = "update " . $winplay . ".registro set creditos_base_ant=creditos_base,creditos_ant=creditos,creditos_base=creditos_base-" . $valor_base . ",creditos=creditos-" . $valor_adicional . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;
                                                } else {
                                                    //Actualiza el saldo del punto de venta
                                                    $strSql[$contSql] = "update " . $winplay . ".punto_venta set creditos_base_ant=creditos_base,creditos_ant=creditos,creditos_base=creditos_base-" . $valor_base . ",creditos=creditos-" . $valor_adicional . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;

                                                    //Inserta el flujo de caja por defecto
                                                    $contSql = $contSql + 1;
                                                    $strSql[$contSql] = "insert into " . $winplay . ".flujo_caja (fecha_crea,hora_crea,usucrea_id,tipomov_id,valor,ticket_id,valor_forma1,mandante) values ('" . date(
                                                            'Y-m-d'
                                                        ) . "','" . date(
                                                            'H:i'
                                                        ) . "'," . $usuario_id . ",'E'," . $valor . ",'" . $ticket_id . "'," . $valor . "," . $mandante . ")";
                                                }
                                            } else {
                                                $seguir = false;
                                                $respuesta = "ERROR_10_El usuario no dispone del saldo suficiente para ejecutar estar esta operacion.";
                                            }
                                        }
                                    } else {
                                        $seguir = false;
                                        $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando hay un ticket ganador
                        case "WIN":
                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ( ! $ticket_existe) {
                                    $seguir = false;
                                    $respuesta = "ERROR_02_El numero de ticket suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    if ($premio_pagado == "S") {
                                        $seguir = false;
                                        $respuesta = "ERROR_04_El numero de ticket suministrado ya fue pagado previamente.";
                                    } else {
                                        //Inserta el log de auditoria del ticket ganador
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                                'Y-m-d'
                                            ) . "','" . date(
                                                'H:i:s'
                                            ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                        $valorUsuario = $valor;
                                        /* Adicionado por Daniel: Revisamos si la apuesta es un freebet para poder hacer las acciones pertinentes */
                                        if ($freebet != 0) {
                                            $valorFreeBet = 0;
                                            $validaSql2 = "select a.* from " . $winplay . ".usuario_bono a where a.usubono_id='" . $freebet . "' ";
                                            $valida_RS_query2 = ProcesarConsulta($validaSql2, $Conn);
                                            $valida_RS2 = mysql_fetch_array($valida_RS_query2);
                                            if ( ! ($valida_RS2 == 0)) {
                                                $valorFreeBet = $valida_RS["valor"];
                                            }
                                            $valorUsuario = $valor;

                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "INSERT INTO bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,'F','" . $valor . "','L',a.usubono_id,0,'0',4,now(),now()  FROM  usuario_bono a   WHERE a.usubono_id = " . $freebet . " AND a.estado='R'";
                                        }

                                        //Actualiza el estado del ticket como ganador
                                        $contSql = $contSql + 1;
                                        if ($perfil == "USER") {
                                            $strPremiado = ",premio_pagado='S',fecha_pago='" . date(
                                                    'Y-m-d'
                                                ) . "',hora_pago='" . date(
                                                    'H:i:s'
                                                ) . "',vlr_premio=" . $valor . ",estado='I'";
                                            $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set premiado='S',bet_status='" . $bet_status . "'" . $strPremiado . ",fecha_cierre='" . date(
                                                    'Y-m-d'
                                                ) . "',hora_cierre='" . date(
                                                    'H:i:s'
                                                ) . "' where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";
                                        } else {
                                            $strPremiado = ",premio_pagado='N',vlr_premio=" . $valor . ",fecha_maxpago=cast(date(date_add(now(), interval " . $dias_expira . " day)) as char(10)),estado='I'";
                                            $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set premiado='S',bet_status='" . $bet_status . "'" . $strPremiado . ",fecha_cierre='" . date(
                                                    'Y-m-d'
                                                ) . "',hora_cierre='" . date(
                                                    'H:i:s'
                                                ) . "' where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "' and premio_pagado='N'";

                                            //Actualiza el saldo del punto de venta

                                            //Inserta el nuevo cheque
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "insert into cheque (nro_cheque,pais_id,origen,documento_id,ticket_id,mandante) select max(a.nro_cheque)+1," . $pais_id_usu . ",'TK'," . $ticket_id . "," . $ticket_id . "," . $mandante . " from " . $winplay . ".cheque a where a.mandante=" . $mandante . " and a.pais_id=" . $pais_id_usu;
                                        }

                                        //Actualiza el saldo del usuario
                                        if ($perfil == "USER") {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update " . $winplay . ".registro set creditos=creditos+" . $valorUsuario . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;
                                        }
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando hay un ticket perdedor
                        case "LOSS":
                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ( ! $ticket_existe) {
                                    $seguir = false;
                                    $respuesta = "ERROR_02_El numero de ticket suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    //Inserta el log de auditoria de la apuesta perdida
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                            'Y-m-d'
                                        ) . "','" . date(
                                            'H:i:s'
                                        ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                    //Actualiza el estado del ticket como ganador
                                    $contSql = $contSql + 1;
                                    if ($perfil == "USER") {
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set vlr_premio=0,premiado='N',premio_pagado='N',fecha_pago=null,hora_pago=null,vlr_premio=0,estado='I',bet_status='" . $bet_status . "',fecha_cierre='" . date(
                                                'Y-m-d'
                                            ) . "',hora_cierre='" . date(
                                                'H:i:s'
                                            ) . "' where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";
                                    } else {
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set vlr_premio=0,premiado='N',premio_pagado='N',fecha_pago=null,hora_pago=null,vlr_premio=0,estado='I',bet_status='" . $bet_status . "',fecha_cierre='" . date(
                                                'Y-m-d'
                                            ) . "',hora_cierre='" . date(
                                                'H:i:s'
                                            ) . "' where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "' and premio_pagado='N'";
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando hay un reembolso
                        case "REFUND":
                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ( ! $ticket_existe) {
                                    $seguir = false;
                                    $respuesta = "ERROR_02_El numero de ticket suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    //Inserta el log de auditoria del reembolso del dinero
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                            'Y-m-d'
                                        ) . "','" . date(
                                            'H:i:s'
                                        ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                    //Actualiza el ticket como eliminado
                                    $contSql = $contSql + 1;
                                    if ($perfil == "USER") {
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set eliminado='S',estado='I',bet_status='" . $bet_status . "',fecha_cierre='" . date(
                                                'Y-m-d'
                                            ) . "',hora_cierre='" . date(
                                                'H:i:s'
                                            ) . "' where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";
                                    } else {
                                        //Elimina el flujo de caja
                                        $strSql[$contSql] = "delete from " . $winplay . ".flujo_caja where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "' and tipomov_id='E'";

                                        //Actualiza el ticket
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set premiado='N',estado='I',vlr_premio=vlr_premio+" . $valor . ",bet_status='" . $bet_status . "',fecha_cierre='" . date(
                                                'Y-m-d'
                                            ) . "',hora_cierre='" . date(
                                                'H:i:s'
                                            ) . "' where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";

                                        //Devuelve el cupo al punto de venta
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update " . $winplay . ".punto_venta set creditos_base=creditos_base+" . $valor . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;
                                    }

                                    //Actualiza el saldo del usuario
                                    if ($perfil == "USER") {
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update " . $winplay . ".registro set creditos=creditos+" . $valor . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando hay un chequeo de apuesta valida
                        case "BETCHECK":
                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ($ticket_existe) {
                                    $chequeo = "_TRUE";
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando realizan un debito
                        case "NEWDEBIT":
                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ( ! $ticket_existe) {
                                    $seguir = false;
                                    $respuesta = "ERROR_02_El numero de ticket suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    //Inserta el log de auditoria del new debit
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                            'Y-m-d'
                                        ) . "','" . date(
                                            'H:i:s'
                                        ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                    //Actualiza el estado del ticket como ganador
                                    $contSql = $contSql + 1;
                                    if ($perfil == "USER") {
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set estado='" . $estado_ticket . "',bet_status='" . $bet_status . "',premiado=case when vlr_premio-" . $valor . "=0 then 'N' else premiado end,premio_pagado=case when vlr_premio-" . $valor . "=0 then 'N' else premio_pagado end,vlr_premio=vlr_premio-" . $valor . " where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";
                                    } else {
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set estado='" . $estado_ticket . "',bet_status='" . $bet_status . "',premiado=case when vlr_premio-" . $valor . "=0 then 'N' else premiado end,premio_pagado=case when vlr_premio-" . $valor . "=0 then 'N' else premio_pagado end,vlr_premio=vlr_premio-" . $valor . " where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "' and premio_pagado='N'";
                                    }

                                    //Actualiza el saldo del usuario
                                    if ($perfil == "USER") {
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update " . $winplay . ".registro set creditos=creditos-" . $valor . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando realizan un credito
                        case "NEWCREDIT":
                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ( ! $ticket_existe) {
                                    $seguir = false;
                                    $respuesta = "ERROR_02_El numero de ticket suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    //Inserta el log de auditoria del new credit
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                            'Y-m-d'
                                        ) . "','" . date(
                                            'H:i:s'
                                        ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                    //Actualiza el estado del ticket como ganador
                                    $contSql = $contSql + 1;
                                    if ($perfil == "USER") {
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set estado='" . $estado_ticket . "',bet_status='" . $bet_status . "',premiado='S',premio_pagado='S',fecha_pago='" . date(
                                                'Y-m-d'
                                            ) . "',hora_pago='" . date(
                                                'H:i:s'
                                            ) . "',vlr_premio=vlr_premio+" . $valor . " where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";
                                    } else {
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set estado='" . $estado_ticket . "',bet_status='" . $bet_status . "',premiado='S',vlr_premio=vlr_premio+" . $valor . " where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "' and premio_pagado='N'";
                                    }

                                    //Actualiza el saldo del usuario
                                    if ($perfil == "USER") {
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update " . $winplay . ".registro set creditos=creditos+" . $valor . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando realizan un credito
                        case "STAKEDECREASE":
                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ( ! $ticket_existe) {
                                    $seguir = false;
                                    $respuesta = "ERROR_02_El numero de ticket suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    //Inserta el log de auditoria del stake decrease
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                            'Y-m-d'
                                        ) . "','" . date(
                                            'H:i:s'
                                        ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                    //Valida que estado le debe poner al ticket
                                    $estado_ticket = "I";
                                    $strFechaCierre = ",fecha_cierre='" . date('Y-m-d') . "',hora_cierre='" . date(
                                            'H:i:s'
                                        ) . "'";
                                    if ($bet_status == "R" or $bet_status == "W") {
                                        $estado_ticket = "A";
                                        $strFechaCierre = "";
                                    }

                                    //Actualiza el estado del ticket como ganador
                                    $contSql = $contSql + 1;
                                    if ($perfil == "USER") {
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set vlr_apuesta=vlr_apuesta-" . $valor . ",estado='" . $estado_ticket . "',bet_status='" . $bet_status . "'" . $strFechaCierre . " where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";
                                    } else {
                                        //Actualiza el valor apostado
                                        $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set vlr_apuesta=vlr_apuesta-" . $valor . ",estado='" . $estado_ticket . "',bet_status='" . $bet_status . "'" . $strFechaCierre . " where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "' and premio_pagado='N'";

                                        //Inserta el flujo de caja por defecto
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "insert into " . $winplay . ".flujo_caja (fecha_crea,hora_crea,usucrea_id,tipomov_id,valor,ticket_id,valor_forma1,mandante) values ('" . date(
                                                'Y-m-d'
                                            ) . "','" . date(
                                                'H:i'
                                            ) . "'," . $usuario_id . ",'E',-" . $valor . ",'" . $ticket_id . "'," . $valor . "," . $mandante . ")";
                                    }

                                    //Actualiza el saldo del usuario
                                    if ($perfil == "USER") {
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update " . $winplay . ".registro set creditos=creditos+" . $valor . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando realizan un credito
                        case "CASHOUT":
                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida que el numero del ticket exista
                                if ( ! $ticket_existe) {
                                    $seguir = false;
                                    $respuesta = "ERROR_02_El numero de ticket suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    /* Adicionado por Daniel: Revisamos si la apuesta es un freebet para poder hacer las acciones pertinentes */
                                    if ($freebet != 0) {
                                        $seguir = false;

                                        $respuesta = "ERROR_10_El cierre no es posible para esta apuesta.";
                                    } else {
                                        //Inserta el log de auditoria del cashout
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "insert into " . $winplay . ".it_transaccion (fecha_crea,hora_crea,tipo,ticket_id,game_reference,usuario_id,bet_status,valor,transaccion_id,mandante) values ('" . date(
                                                'Y-m-d'
                                            ) . "','" . date(
                                                'H:i:s'
                                            ) . "','" . $tipo . "','" . $ticket_id . "','" . $game_reference . "','" . $usuario_id . "','" . $bet_status . "'," . $valor . ",'" . $trans_id . "'," . $mandante . ")";

                                        //Actualiza el estado del ticket como ganador
                                        $contSql = $contSql + 1;
                                        if ($perfil == "USER") {
                                            $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set bet_status='" . $bet_status . "',estado='I',premiado='S',premio_pagado='S',vlr_premio=" . $valor . ",fecha_cierre='" . date(
                                                    'Y-m-d'
                                                ) . "',hora_cierre='" . date(
                                                    'H:i:s'
                                                ) . "' where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";
                                        } else {
                                            $strSql[$contSql] = "update " . $winplay . ".it_ticket_enc set bet_status='" . $bet_status . "',estado='I',premiado='S',vlr_premio=" . $valor . ",fecha_maxpago=cast(date(date_add(now(), interval " . $dias_expira . " day)) as char(10)),fecha_cierre='" . date(
                                                    'Y-m-d'
                                                ) . "',hora_cierre='" . date(
                                                    'H:i:s'
                                                ) . "' where mandante=" . $mandante . " and ticket_id='" . $ticket_id . "'";

                                            //Inserta el nuevo cheque
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "insert into cheque (nro_cheque,pais_id,origen,documento_id,ticket_id,mandante) select count(a.id)+1," . $pais_id_usu . ",'TK'," . $ticket_id . "," . $ticket_id . "," . $mandante . " from " . $winplay . ".cheque a where a.mandante=" . $mandante . " and a.pais_id=" . $pais_id_usu;
                                        }

                                        //Actualiza el saldo del usuario
                                        if ($perfil == "USER") {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update " . $winplay . ".registro set creditos=creditos+" . $valor . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;
                                        } else {
                                        }
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando se libera un bono
                        case "WINBONUS":
                            //Valida los campos obligatorios
                            if ( ! ValidarCampo($bonus_id, "S", "N", 20)) {
                                $seguir = false;
                            }
                            if ( ! ValidarCampo($bonusplan_id, "S", "N", 20)) {
                                $seguir = false;
                            }

                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida si el bono existe y no ha sido cerrado
                                $validabonoSql = "select a.id_externo,a.estado from " . $winplay . ".bono_log a where a.mandante=" . $mandante . " and a.id_externo=" . $bonus_id;
                                $validabono_RS_query = ProcesarConsulta($validabonoSql, $Conn);
                                $validabono_RS = mysql_fetch_array($validabono_RS_query);
                                if (($validabono_RS == 0)) {
                                    $seguir = false;
                                    $respuesta = "ERROR_15_El numero de bono suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    if ($validabono_RS["estado"] != "A") {
                                        $seguir = false;
                                        $respuesta = "ERROR_16_No se pudo procesar el bono porque ya fue procesado previamente o su estado actual no admite la liberacion.";
                                    } else {
                                        //Verifica el perfil del usuario
                                        if ($perfil == "USER") {
                                            //Actualiza el saldo de creditos de participacion y el saldo de bonos
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update " . $winplay . ".registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor . ",creditos_bono_ant=creditos_bono,creditos_bono=creditos_bono-" . $valor . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;

                                            //Actualiza el estado del log de bonos
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update " . $winplay . ".bono_log set estado='L',fecha_cierre='" . date(
                                                    'Y-m-d H:i:s'
                                                ) . "',transaccion_id='" . $trans_id . "' where mandante=" . $mandante . " and id_externo=" . $bonus_id;
                                        }
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;

                        // Tipo cuando se libera un bono
                        case "CODEBONUS":
                            //Valida los campos obligatorios
                            if ( ! ValidarCampo($bonus_id, "S", "N", 20)) {
                                $seguir = false;
                            }
                            if ( ! ValidarCampo($bonusplan_id, "S", "N", 20)) {
                                $seguir = false;
                            }

                            //Verifica si debe continuar
                            if ($seguir) {
                                //Valida si el bono existe y no ha sido cerrado
                                $validabonoSql = "select a.bonusplanid from " . $winplay . ".bono a where a.mandante=" . $mandante . " and a.bonusplanid=" . $bonusplan_id;
                                $validabono_RS_query = ProcesarConsulta($validabonoSql, $Conn);
                                $validabono_RS = mysql_fetch_array($validabono_RS_query);
                                if (($validabono_RS == 0)) {
                                    $seguir = false;
                                    $respuesta = "ERROR_15_El numero de bono suministrado no se encuentra registrado en la base de datos.";
                                } else {
                                    //Verifica el perfil del usuario
                                    if ($perfil == "USER") {
                                        //Actualiza el saldo de creditos de participacion y el saldo de bonos
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update " . $winplay . ".registro set creditos_bono_ant=creditos_bono,creditos_bono=creditos_bono+" . $valor . " where mandante=" . $mandante . " and usuario_id=" . $usuario_id;

                                        //Actualiza el estado del log de bonos
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "insert into bono_log (usuario_id,tipo,valor,fecha_crea,estado,error_id,id_externo,mandante) values (" . $usuario_id . ",'F'," . $valor . ",'" . date(
                                                'Y-m-d H:i:s'
                                            ) . "','A','0'," . $bonus_id . "," . $mandante . ")";
                                    }
                                }
                            } else {
                                $seguir = false;
                                $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                            }

                            break;
                    }
                } else {
                    $chequeo = "_TRUE";
                }

                //Si debe continuar el proceso
                if ($seguir) {
                    //Se arma el nuevo token de itainment
                    $x = strlen($usuario_id);
                    $r = 15;
                    $d = $r - $x;
                    $token_nuevo = $usuario_id . GenerarClaveTicket2($d);

                    //Adiciona el query para actualizar el nuevo token del usuario
                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE " . $winplay . ".usuario SET token_itainment=" . $token_nuevo . " WHERE usuario_id=" . $usuario_id;

                    //cierra conexiones de bases de datos
                    mysql_close($Conn);
                    $Conn = null;

                    //Ejecuta las instrucciones SQL
                    $retorno = EjecutarQuery2($bd_servidor, $bd_usuario, $bd_clave, $bd_nombre, $contSql, $strSql);

                    //Devuelve la respuesta de acuerdo al retorno
                    if ( ! $retorno) {
                        //Abre la conexion a la base de datos
                        $Conn = ConectarBD($bd_servidor, $bd_usuario, $bd_clave, $bd_nombre);

                        //Trae el saldo del usuario
                        $saldo = 0;
                        $saldoSql = "select round(a.creditos+a.creditos_base,0) saldo from " . $winplay . ".registro a where a.mandante=" . $mandante . " and a.usuario_id=" . $usuario_id;
                        $saldo_RS_query = ProcesarConsulta($saldoSql, $Conn);
                        $saldo_RS = mysql_fetch_array($saldo_RS_query);
                        if ( ! ($saldo_RS == 0)) {
                            $saldo = $saldo_RS["saldo"];

                            $respuesta = "OK_" . $perfil . "_" . $key_ref . "_" . $usuario_id . "_" . $nombre . "_" . $saldo . "00" . $chequeo . "_" . $clave_ticket . "_" . $token_nuevo . "_INI";

                            if ($tipo == 'BET') {
                                $respuesta = "OK_" . $perfil . "_" . $key_ref . "_" . $usuario_id . "_" . $nombre . "_" . $saldo . "00" . $chequeo . "_" . $token_nuevo . "_" . $bonusamount . "_INI";
                            }
                        }
                    } else {
                        $respuesta = "ERROR_01_Ocurrio un error inesperado y no se pudo procesar la solicitud.(" . $retorno . ")";
                    }
                } else {
                    if (strlen($respuesta) <= 0) {
                        $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
                    }
                }
            }
        }
    } else {
        $respuesta = "ERROR_99_No se pudo procesar su solicitud debido a inconsistencias halladas en los parametros suministrados.";
    }

    //cierra conexiones de bases de datos
    mysql_close($Conn);
    $Conn = null;

    //Libera la memoria del query
    mysql_free_result($datos_RS_query);

    //Devuelve la respuesta
    return $respuesta;
}

?>