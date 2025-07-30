<?php


use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Registro;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\UsuarioTorneo;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;


/**
 * Este script gestiona la distribución de premios en un torneo y actualiza los estados correspondientes.
 *
 * @param object $params Objeto JSON con los siguientes valores:
 * - @property int $Id ID del torneo.
 * @return array $response Respuesta en formato JSON que incluye:
 * - @property bool $HasError Indica si hubo un error.
 * - @property string $AlertType Tipo de alerta (success o danger).
 * - @property string $AlertMessage Mensaje de alerta.
 * - @property array $ModelErrors Lista de errores del modelo.
 * - @property array $Data Información sobre los premios distribuidos.
 */

/* obtiene datos JSON, decodifica y extrae el ID para un torneo. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$Id = $params->Id;

$TorneoInterno = new TorneoInterno();

/* Se crea una nueva instancia de la clase TorneoDetalle en PHP. */
$TorneoDetalle = new TorneoDetalle();


if ($Id != "") {


    /* Define variables y reglas para filtrar torneos en una aplicación de programación. */
    $tipoTorneo = '0';

    $arrayfinal = [];
    $rules = [];


    array_push($rules, array("field" => "usuario_torneo.torneo_id", "data" => $Id, "op" => "eq"));


    /* Se define un filtro con reglas, desplazamiento y configuración de orden y límites de filas. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $SkeepRows = 0;

    $OrderedItem = 1;

    $MaxRows = 50;


    /* convierte un filtro a JSON y obtiene datos de usuarios en un torneo. */
    $jsonfiltrodetalles = json_encode($filtro);

    $final = [];
    $UsuarioTorneo = new UsuarioTorneo();
    $usuariosTorneos = $UsuarioTorneo->getUsuarioTorneosCustom("usuario_torneo.valor,torneo_interno.*,usuario_torneo.posicion,usuario_mandante.usumandante_id,usuario_mandante.usuario_mandante,usuario_mandante.nombres ", "usuario_torneo.valor", "desc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE);

    $usuariosTorneos = json_decode($usuariosTorneos);

    /* crea una lista con información de usuarios en torneos. */
    $pos = 1;

    foreach ($usuariosTorneos->data as $key2 => $value2) {
        $tipoTorneo = $value2->{'torneo_interno.tipo'};

        $lista_temp2 = [];
        $lista_temp2['Position'] = $pos;
        $lista_temp2['UserName'] = $value2->{'usuario_mandante.nombres'};
        $lista_temp2['UserId'] = $value2->{'usuario_mandante.usuario_mandante'};
        $lista_temp2['Points'] = $value2->{'usuario_torneo.valor'};
        $lista_temp2['Awards'] = array();
        $lista_temp2['CasinoId'] = $value2->{'usuario_mandante.usumandante_id'};
        $lista_temp2['TorneoId'] = $value2->{'torneo_interno.torneo_id'};
        $lista_temp2['Mandante'] = $value2->{'torneo_interno.mandante'};

        array_push($final, ($lista_temp2));
        $pos++;

    }

    //array_push($final, $lista_temp2);
    //array_push($final, $lista_temp2);


    /* Define reglas para validar campos en un torneo utilizando condiciones específicas. */
    $rules = [];


    array_push($rules, array("field" => "torneo_detalle.torneo_id", "data" => $Id, "op" => "eq"));
    array_push($rules, array("field" => "torneo_detalle.moneda", "data" => "", "op" => "nn"));
    array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "'RANKAWARD','RANKAWARDMAT', 'RANKAWARDBONUS'", "op" => "in"));

    /* Agrega una regla de filtro para excluir registros con "ENTREGADO" en torneo_detalle. */
    array_push($rules, array("field" => "torneo_detalle.descripcion", "data" => "ENTREGADO", "op" => "ne"));


    //array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");


    /* inicializa variables si no tienen un valor asignado. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un valor por defecto para $MaxRows y codifica $filtro en JSON. */
    if ($MaxRows == "") {
        $MaxRows = 10;
    }
    $MaxRows = 10000;

    $jsonfiltro = json_encode($filtro);


    /* Se obtienen y cuentan detalles de torneos en formato JSON desde la base de datos. */
    $torneos = $TorneoDetalle->getTorneoDetallesCustom("torneo_detalle.* ", "torneo_detalle.torneo_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);

    $torneos = json_decode($torneos);

    $count = ($torneos->count[0]->{".count"});

    if ($count > 0) {


        /* Se inicializan dos arreglos vacíos en PHP, listos para almacenar datos. */
        $final2 = [];


        $arrayAux = array();
        foreach ($torneos->data as $key2 => $value2) {


            switch ($value2->{"torneo_detalle.tipo"}) {

                case "RANKAWARDMAT":
                    /* crea un arreglo con detalles de premios en un torneo. */


                    $array2 = [];
                    $array2['pos'] = $value2->{"torneo_detalle.valor"};
                    $array2['desc'] = $value2->{"torneo_detalle.valor2"};
                    $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));
                    $array2['type'] = 0;
                    //array_push($lista_temp2['premios'], ($array2));
                    $arrayAux[] = $array2;

                    break;


                case "RANKAWARD":
                    /* Actualiza detalles de torneo y prepara información de premios en un array. */


                    $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();
                    $transaccion = $TorneoDetalleMySqlDAO->getTransaction();
                    $TorneoDetalleUpdate = new TorneoDetalle($value2->{"torneo_detalle.torneodetalle_id"});
                    $TorneoDetalleUpdate->descripcion = "ENTREGADO";
                    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
                    //$TorneoDetalleMysqlDAO->update($TorneoDetalleUpdate);
                    //$transaccion->commit();

                    $array2 = [];
                    $array2['pos'] = $value2->{"torneo_detalle.valor"};
                    $array2['desc'] = $value2->{"torneo_detalle.valor2"};
                    $array2['value'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));
                    $array2['type'] = 1;

                    //array_push($lista_temp2['premios'], ($array2));
                    $arrayAux[] = $array2;
                    break;


                case "RANKAWARDBONUS":
                    /* Actualiza el estado de un torneo y almacena datos en un arreglo temporal. */


                    $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();
                    $transaccion = $TorneoDetalleMySqlDAO->getTransaction();
                    $TorneoDetalleUpdate = new TorneoDetalle($value2->{"torneo_detalle.torneodetalle_id"});
                    $TorneoDetalleUpdate->descripcion = "ENTREGADO";
                    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
                    $TorneoDetalleMysqlDAO->update($TorneoDetalleUpdate);
                    $transaccion->commit();

                    $array2 = [];
                    $array2['pos'] = $value2->{"torneo_detalle.valor"};
                    $array2['desc'] = $value2->{"torneo_detalle.valor2"};
                    $array2['value'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));
                    $array2['type'] = 3;

                    //array_push($lista_temp2['premios'], ($array2));
                    $arrayAux[] = $array2;
                    break;


            }

        }


        /* Agrupa elementos de `$arrayAux` por la clave 'pos' en `$arrayAux2`. */
        $arrayAux2 = array();
        foreach ($arrayAux as $key) {

            $arrayAux2[$key['pos']][] = $key;
        }

        $aFinal2 = array();
        foreach ($final as $key => $value) {


            /* Clasifica premios en dinero y físicos según la posición del participante. */
            $PremiosDinero = array();
            $PremiosFisico = array();

            if (isset($arrayAux2[$value["Position"]])) {

                foreach ($arrayAux2[$value["Position"]] as $premio) {
                    if ($premio["type"] == 0) {
                        array_push($PremiosFisico, $premio["desc"]);
                    }
                    if ($premio["type"] == 1) {

                        array_push($PremiosDinero, $premio["value"] . ' ' . $premio["desc"]);
                    }
                }
                $final[$key]["Awards"] = $arrayAux2[$value["Position"]];
                //$final[$key]["PhysicalAward"] = implode(',',$PremiosFisico);
                //$final[$key]["MoneyAward"] = implode(',',$PremiosDinero);
            }

        }

        foreach ($final as $key => $value) {


            /* Variable que indica si un correo electrónico ha sido enviado o no. */
            $emailSended = false;

            foreach ($value['Awards'] as $key2 => $value2) {

                /* Verifica si un usuario tiene configuraciones específicas y envía un correo de alerta. */
                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($value['UserId']);
                if (!empty($UsuarioConfiguracion->usuconfigId)) {

                    if (!$emailSended) {
                        $emailSended = true;
                        $BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                        VALUES ('{$value['UserId']}','TORNEO','{$value['TorneoId']}','SPORTBOOK','0','Contingencia activa: Abusador de bonos')";
                        $BonoInterno->execQuery($transaccion, $sqlLog);

                        $usuario = new Usuario($value['UserId']);
                        $Mandante = new Mandante($value['Mandante']);
                        $ConfigurationEnvironment = new ConfigurationEnvironment();
                        $subject = $Mandante->nombre . ' - Información importante sobre tu cuenta';
                        $mensaje_txt = 'Hola, gracias por participar en nuestros torneos. Actualmente estamos realizando una revisión en tu cuenta, por lo que los premios del torneo no han sido cargados automáticamente. Si tienes dudas o necesitas más información, por favor comunícate con nuestro equipo de soporte. Estaremos encantados de asistirte.';
                        $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($usuario->login, '', $Mandante->descripcion, $subject, '', $subject, $mensaje_txt, '', '', '', $usuario->mandante);
                    }

                    continue;
                }

                if ($value2['type'] == 1) {


                    /* Asigna un tipo de torneo basado en su valor y condición específica. */
                    $TypeBalance = $value2['desc'];
                    $Type = $tipoTorneo;

                    if ($Type == '2') {
                        $Type = "TC";
                    }


                    /* Condicionales que asignan valores de tipo según condiciones específicas. */
                    if ($Type == '1') {
                        $Type = "TD";
                    }


                    if ($Type == '4') {
                        $Type = "TV";
                    }


                    /* asigna "TL" a $Type si es igual a '3' y obtiene $ClientId. */
                    if ($Type == '3') {
                        $Type = "TL";
                    }


                    $ClientId = $value['UserId'];

                    /* Se asigna el valor de 'value' a la variable $Amount. */
                    $Amount = $value2['value'];

                    if ($ClientId != "" && $ClientId != "0") {

                        try {


                            /* Se crea un usuario y un registro de bono con su información correspondiente. */
                            $Usuario = new Usuario($ClientId);

                            $BonoLog = new BonoLog();
                            $BonoLog->setUsuarioId($Usuario->usuarioId);
                            $BonoLog->setTipo($Type);
                            $BonoLog->setValor($Amount);

                            /* registra datos de un bono, incluyendo fecha y estado. */
                            $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                            $BonoLog->setEstado('L');
                            $BonoLog->setErrorId(0);
                            $BonoLog->setIdExterno($Id);
                            $BonoLog->setMandante($Usuario->mandante);
                            $BonoLog->setFechaCierre('');

                            /* Código que configura un objeto BonoLog y lo prepara para operaciones en la base de datos. */
                            $BonoLog->setTransaccionId('');
                            $BonoLog->setTipobonoId(4);
                            $BonoLog->setTiposaldoId($TypeBalance);


                            $BonoLogMySqlDAO = new BonoLogMySqlDAO();


                            /* gestiona una transacción y acredita un monto al usuario según balance. */
                            $Transaction = $BonoLogMySqlDAO->getTransaction();

                            $bonologId = $BonoLogMySqlDAO->insert($BonoLog);


                            if ($TypeBalance == 0) {

                                $Usuario->credit($Amount, $Transaction); //Creditos

                            } elseif ($TypeBalance == 1) {
                                /* processa retiros incrementando el crédito del usuario según la transacción. */

                                $Usuario->creditWin($Amount, $Transaction); //Retiros

                            }


                            /* Se crea un historial de usuario con identificación y movimiento especificados. */
                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);

                            /* inserta un historial de usuario en la base de datos tras configurar sus atributos. */
                            $UsuarioHistorial->setTipo(50);
                            $UsuarioHistorial->setValor($Amount);
                            $UsuarioHistorial->setExternoId($bonologId);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                            /* maneja un compromiso de transacción y configura una respuesta sin errores. */
                            $Transaction->commit();

                            $response["HasError"] = false;
                            $response["AlertType"] = "danger";
                            $response["AlertMessage"] = "";
                            $response["ModelErrors"] = [];

                            /* Inicializa un array vacío para almacenar resultados en la respuesta. */
                            $response["Result"] = array();


                        } catch (Exception $e) {
                            /* Captura excepciones y guarda el mensaje del error en la variable $msg. */


                            $msg = $e->getMessage();

                        }

                    }

                }
                if ($value2['type'] == 3) {


                    /* Asigna valores de 'UserId' y 'value' a las variables $ClientId y $bonoId. */
                    $ClientId = $value['UserId'];
                    $bonoId = $value2['value'];

                    if ($ClientId != "" && $ClientId != "0") {

                        try {


                            /* Se crean instancias de DAO, usuario y registro través de transacciones en MySQL. */
                            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

                            $Transaction = $BonoInternoMySqlDAO->getTransaction();

                            $Usuario = new Usuario($ClientId);
                            $Registro = new Registro('', $Usuario->usuarioId);


                            /* carga detalles de una ciudad y usuarios en un array asociativo. */
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


                            /* Se crea un bono interno y se agrega un bono gratuito utilizando detalles decodificados. */
                            $BonoInterno = new BonoInterno();

                            $detalles = json_decode(json_encode($detalles));


                            $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction);


                            /* Finaliza la transacción actual, guardando todos los cambios realizados en la base de datos. */
                            $Transaction->commit();


                        } catch (Exception $e) {
                            /* captura excepciones y almacena el mensaje de error en una variable. */


                            $msg = $e->getMessage();

                        }

                    }

                }

            }

            /*if($value['PhysicalAward'] =='' && $value['MoneyAward'] =='' ){
                unset($final[$key]);
            }*/

        }


        /* Se crea un array de respuesta inicializando estado y mensajes de alerta. */
        $response = array();


        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        /* Inicializa un arreglo vacío para almacenar errores de un modelo en la respuesta. */


        /*$response["Data"] = "$final";
        $response["Count"] = oldCount($final);*/

    } else {
        /* crea una respuesta sin errores con datos vacíos y conteo cero. */

        $response = array();
        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Data"] = "";
        $response["Count"] = 0;
    }


} else {
    /* crea una respuesta estructurada indicando que hubo un error, pero es exitoso. */


    $response = array();
    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Data"] = "";
    $response["Count"] = 0;

}



