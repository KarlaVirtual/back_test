<?php

use Backend\dto\Clasificador;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioVerificacion;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;
use Backend\dto\SitioTracking;
use Backend\dto\BonoInterno;
use Backend\dto\MandanteDetalle;

/**
 * Verifica y actualiza el estado de una cuenta de usuario en función de los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params ->Id Identificador de la verificación.
 * @param int $params ->UserId Identificador del usuario.
 * @param string $params ->Note Observación o nota sobre la verificación.
 * @param string $params ->State Estado de la verificación ('I' para iniciar, 'R' para rechazar, 'A' para aprobar).
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success' o 'error').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si ocurre un error en la verificación o en el proceso de actualización.
 */


/* captura parámetros y determina el estado y la dirección IP del usuario. */
$Id = $params->Id;
$UserId = $params->UserId;
$Note = $params->Note;
$State = $params->State === 'I' ? 'R' : 'A';
$remote = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']) : explode(',', $_SERVER['REMOTE_ADDR']);
$ip = $remote[0];

$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];

try {
    $Usuario = new Usuario($UserId);
    $UsuarioVerificacion = new UsuarioVerificacion($Id);

    if ($UsuarioVerificacion->getEstado() !== 'P') throw new Exception('Error en la verificacion', 1000);

    $Registro = new Registro('', $Usuario->usuarioId);
    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
    $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);
    $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
    $Transaction = $UsuarioVerificacionMySqlDAO->getTransaction();

    $Usuario->verificado = $State === 'R' ? '' : 'S';
    $Usuario->fechaVerificado = $State === 'A' ? date('Y-m-d H:i:s') : null;

    $UsuarioVerificacion->setEstado($State);
    $UsuarioVerificacion->setObservacion($Note);
    $UsuarioVerificacion->setUsumodifId($_SESSION['usuario']);

    $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);

    $rules = [];

    // $log_types = "'USUDNIANTERIOR', 'USUDNIPOSTERIOR', 'USUNOMBRE2', 'USUAPELLIDO2', 'USUFECHANACIM', 'USUNOMBRE1', 'USUAPELLIDO1', 'USUCEDULA', 'USUVERFOTO', 'FVSPORT', 'FVCASINO', 'USUEMAIL', 'USUCELULAR', 'USUDIRECCION', 'USUCIUDAD'";

    array_push($rules, ['field' => 'usuario_log2.estado', 'data' => 'P', 'op' => 'eq']);
    // array_push($rules, ['field' => 'usuario_log2.tipo', 'data' => $log_types, 'op' => 'in']);
    array_push($rules, ['field' => 'usuario.mandante', 'data' => $Usuario->mandante, 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_log2.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_log2.sversion', 'data' => "'" . $Id . "'", 'op' => 'in']);

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $UsuarioLog2 = new UsuarioLog2();
    $query = (string)$UsuarioLog2->getUsuarioLog2sCustom('usuario_log2.usuariolog2_id, usuario_log2.tipo, usuario_log2.valor_despues', 'usuario_log2.usuariolog2_id', 'asc', 0, 1000, $filter, true);

    $query = json_decode($query, true);

    $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);

    $changesUser = false;
    $changesUsupartner = false;
    $changesRegister = false;
    $changesOtrainfo = false;

    foreach ($query['data'] as $key => $value) {
        $UsuarioLog2 = new UsuarioLog2($value['usuario_log2.usuariolog2_id']);

        if ($value['usuario_log2.tipo'] === 'USUDNIANTERIOR') {

            $Usuario->verifcedulaAnt = $State === 'R' ? 'N' : 'S';
            $Usuario->estadoJugador = $State === 'R' || ($State === 'A' && $Usuario->verifcedulaPost === 'N') ? 'NN' : 'AA';

            $data = $UsuarioLog2->imagen;
            $filename = "c" . $UsuarioLog2->usuarioId;

            $filename = $filename . 'A';

            $filename = $filename . '.png';

            if (!file_exists('/tmp/')) {
                mkdir('/tmp/', 0755, true);
            }

            $dirsave = '/tmp/' . $filename;
            file_put_contents($dirsave, $data);

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');
            if ($Usuario->usuarioId == 73818) {
                print_r('entro');
                print_r($dirsave);
            }

            $changesUser = true;
        } elseif ($value['usuario_log2.tipo'] === 'USUDNIPOSTERIOR') {
            $Usuario->verifcedulaPost = $State === 'R' ? 'N' : 'S';
            $Usuario->estadoJugador = $State === 'R' || ($State === 'A' && $Usuario->verifcedulaAnt === 'N') ? 'NN' : 'AA';

            $data = $UsuarioLog2->imagen;
            $filename = "c" . $UsuarioLog2->usuarioId;

            $filename = $filename . 'P';


            $filename = $filename . '.png';

            if (!file_exists('/tmp/')) {
                mkdir('/tmp/', 0755, true);
            }

            $dirsave = '/tmp/' . $filename;
            file_put_contents($dirsave, $data);

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');


            $changesUser = true;
        } elseif ($State === 'A') {
            switch ($value['usuario_log2.tipo']) {
                // Actualiza el email del usuario y registros relacionados
                case 'USUEMAIL':
                    $Usuario->login = $value['usuario_log2.valor_despues'];
                    $Registro->email = $value['usuario_log2.valor_despues'];
                    $UsuarioMandante->email = $value['usuario_log2.valor_despues'];

                    $changesUser = true;
                    $changesUsupartner = true;
                    $changesRegister = true;
                    break;

                // Actualiza el primer nombre del usuario y registros relacionados
                case 'USUNOMBRE1':
                    $Registro->nombre1 = $value['usuario_log2.valor_despues'];
                    $Registro->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                    $Usuario->nombre = $Registro->nombre;
                    $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
                    $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;

                    $changesUser = true;
                    $changesRegister = true;
                    $changesUsupartner = true;
                    break;

                // Actualiza el segundo nombre del usuario y registros relacionados
                case 'USUNOMBRE2':
                    $Registro->nombre2 = $value['usuario_log2.valor_despues'];
                    $fullName = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                    $Usuario->nombre = $fullName;
                    $Registro->nombre = $fullName;
                    $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
                    $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;

                    $changesUser = true;
                    $changesRegister = true;
                    $changesUsupartner = true;
                    break;

                // Actualiza el primer apellido del usuario y registros relacionados
                case 'USUAPELLIDO1':
                    $Registro->apellido1 = $value['usuario_log2.valor_despues'];
                    $Registro->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                    $Usuario->nombre = $Registro->nombre;
                    $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
                    $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;

                    $changesUser = true;
                    $changesRegister = true;
                    $changesUsupartner = true;
                    break;
                case 'USUAPELLIDO2':

                    /* asigna nombres y apellidos a diferentes objetos de usuario. */
                    $Registro->apellido2 = $value['usuario_log2.valor_despues'];
                    $fullName = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                    $Usuario->nombre = $fullName;
                    $Registro->nombre = $fullName;
                    $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
                    $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;


                    /* Código que actualiza información del usuario y detecta cambios en sus datos. */
                    $changesUser = true;
                    $changesRegister = true;
                    $changesUsupartner = true;
                    break;
                case 'USUFECHANACIM':
                    $UsuarioOtrainfo->fechaNacim = $value['usuario_log2.valor_despues'];

                    $changesOtrainfo = true;
                    break;
                case 'USUCEDULA':
                    /* Asigna el valor de 'cedula' de un registro basado en un caso específico. */

                    $Registro->cedula = $value['usuario_log2.valor_despues'];

                    $changesRegister = true;
                    break;
                case 'USUCELULAR':
                    /* Asigna el valor del celular a registros y usuario, marcando cambios. */

                    $Registro->celular = $value['usuario_log2.valor_despues'];
                    $Usuario->celular = $value['usuario_log2.valor_despues'];

                    $changesUser = true;
                    $changesRegister = true;
                    break;
                case 'USUDIRECCION':
                    /* Asigna una dirección a registros y marca cambios en un caso específico. */

                    $Registro->direccion = $value['usuario_log2.valor_despues'];
                    $UsuarioOtrainfo->direccion = $value['usuario_log2.valor_despues'];

                    $changesRegister = true;
                    $changesOtrainfo = true;
                    break;
                case 'FVSPORT':
                    /* Asigna un deporte favorito al objeto según el valor de entrada y marca cambios. */

                    $UsuarioOtrainfo->deporteFavorito = $value['usuario_log2.valor_despues'];

                    $changesOtrainfo = true;
                    break;
                case 'FVCASINO':
                    /* Asigna el valor de 'casinoFavorito' y marca cambios en el usuario. */

                    $UsuarioOtrainfo->casinoFavorito = $value['usuario_log2.valor_despues'];

                    $changesOtrainfo = true;
                    break;
                case 'USUCIUDAD';

                    /* asigna valores a propiedades de objetos según condiciones específicas. */
                    $Registro->ciudadId = $value['usuario_log2.valor_despues'];

                    $changesRegister = true;
                    break;
                case 'FVTEAM':
                    $Usuario->equipoId = $value['usuario_log2.valor_despues'];

                    $changesUser = true;
                    break;
                case 'ACCOUNTIDJUMIO':
                    /* Asigna un valor de cuenta a un usuario y marca cambios como verdaderos. */

                    $Usuario->accountIdJumio = $value['usuario_log2.valor_despues'];

                    $changesUser = true;
                    break;
            }
        }

        /* Actualiza el estado de un usuario en la base de datos usando DAO. */
        $UsuarioLog2->estado = $State;
        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->update($UsuarioLog2);
        //$UsuarioLog2MySqlDAO->getTransaction()->commit();

        $codigoBD = $Registro->getCodpromocionalId();

        if ($codigoBD == '2898') {


            /* crea un array con detalles de un depósito y usuario. */
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
                "DepartamentoUSER" => 0,
                "CiudadUSER" => $Registro->ciudadId,
                "MonedaUSER" => $Usuario->moneda,
                "CodePromo" => $codigoBD
            );


            /* Se decodifica un JSON y se agrega un bono usando detalles y usuario. */
            $detalles = json_decode(json_encode($detalles));

            $BonoInterno = new BonoInterno();
            $responseBonus = $BonoInterno->agregarBono("", $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);

        }

    }

    /* Actualiza usuario y registro en base de datos si se detectan cambios. */
    if ($changesUser) {
        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
        $UsuarioMySqlDAO->update($Usuario);
    }

    if ($changesRegister) {
        $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
        $RegistroMySqlDAO->update($Registro);
    }


    /* Actualiza datos de usuario según cambios en dos entidades distintas. */
    if ($changesUsupartner) {
        $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaction);
        $UsuarioMandanteMySqlDAO->update($UsuarioMandante);
    }

    if ($changesOtrainfo) {
        $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);
        $UsuarioOtrainfoMySqlDAO->update($UsuarioOtrainfo);
    }

    /**Asignación de bonos elegidos mediante landing*/



    /* Verifica condiciones para obtener datos de seguimiento del usuario en formato JSON. */
    if ( $State == 'A' && $Usuario->verificado == 'S') {
        $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro_type_gift","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

        $SitioTracking = new SitioTracking();
        $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
        $sitiosTracking = json_decode($sitiosTracking);

        $type_gift = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

        if ($type_gift != '') {
            $bonoIdd = null;

            //Verificando existencia de bonos dinámicos
            try {
                $tipoBonoSeleccionado = null;
                $Clasificador = new Clasificador('', 'BONUSFORLANDING');
                $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                $patronesBono = [
                    3 => '#deportiva#', //No deposito
                    5 => '#casino#', //FreeCasino
                    6 => '#deportiva#', //FreeBet
                    8 => '#casino#' //FreeSpin
                ];

                foreach ($patronesBono as $tipoBonoId => $patronBono) {
                    //Identificando bono seleccionado por el usuario
                    if (preg_match($patronBono, $type_gift)) {
                        $tipoBonoSeleccionado = $tipoBonoId;
                        break;
                    }
                }

                //Verificando que haya un bono del tipo seleccionado por el usuario
                $ofertaBonos = explode(',', $MandanteDetalle->valor);
                if (empty($ofertaBonos)) throw new Exception('', 34);

                foreach ($ofertaBonos as $bonoOfertado) {
                    $BonoInterno = new BonoInterno($bonoOfertado);
                    foreach ($patronesBono as $tipoBonoId => $patronBono) {
                        //Identificando bono seleccionado por el usuario
                        if (preg_match($patronBono, $type_gift)) {
                            $tipoBonoSeleccionado = $tipoBonoId;
                            if ($BonoInterno->tipo == $tipoBonoSeleccionado) {

                                $bonoIdd = $bonoOfertado;
                            }
                        }
                    }


                }
                if (empty($bonoIdd)) throw new Exception('', 34);


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
                    "DepartamentoUSER" => 0,
                    "CiudadUSER" => $Registro->ciudadId,
                    "MonedaUSER" => $Usuario->moneda,
                    "CodePromo" => ''
                );

                $detalles = json_decode(json_encode($detalles));

                $BonoInterno = new BonoInterno();
                $responseBonus = $BonoInterno->agregarBonoFree($bonoIdd, $Usuario->usuarioId, $Usuario->mandante, $detalles, false, "", $Transaction);
            } catch (Exception $e) {
            }
        }
    }


    $Transaction->commit();

} catch (Exception $ex) {
    $response['HasError'] = true;
    $response['AlertType'] = $ex->getCode() == 1000 ? $ex->getMessage() : 'Error General';
    $response['ModelErrors'] = [];
}

?>