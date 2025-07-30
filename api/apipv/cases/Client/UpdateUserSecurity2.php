<?php

use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\dto\Clasificador;
use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;

/**
 * Actualiza la configuración de seguridad de un usuario.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param int $params ->ClientId ID del cliente.
 * @param int|null $params ->startDate Fecha de inicio en formato timestamp.
 * @param int|null $params ->endDate Fecha de fin en formato timestamp.
 * @param string $params ->IsActivate Estado de activación ("0" o "1").
 * @param int $params ->Fraud Indicador de fraude.
 * @param int $params ->Abuse_bonuses Indicador de abuso de bonos.
 * @param int $params ->Rider Indicador de rider.
 * @param int $params ->Self_exclusion Indicador de autoexclusión.
 * @param int $params ->Counter_charges Indicador de contracargos.
 * @param int $params ->Under_revision Indicador de revisión.
 * @param int $params ->Activated Indicador de activación.
 * @param string $params ->Comment Comentario de contingencia.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta ("success" o "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si los parámetros son inválidos o si ocurre un error en la operación.
 */


/* Obtiene la dirección IP del cliente, considerando proxies y almacena un ClientId. */
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];

$ClientId = $params->ClientId;


if ($ClientId > 0) {


    /* verifica si las fechas de inicio y fin están definidas y lanzan excepción si no. */
    $startDate = $params->startDate;
    $endDate = $params->endDate;

    if ($startDate == "" || $endDate == "" || $startDate == NULL || $startDate == "null" || $endDate == NULL || $endDate == "null") {
        throw new exception ("elija un periodo para la contingencia", 300021);
    }


    /* verifica fechas y formatea la fecha de inicio en un formato específico. */
    if ($startDate == $endDate) {
        $endDate = "";
    }


    $startDateFormatted = date("Y-m-d H:i:s", $startDate / 1000);

    /* Formatea la fecha y establece el estado de activación según el parámetro. */
    if ($endDate != "") {
        $endDateFormatted = date("Y-m-d H:i:s", $endDate / 1000);
    }


    $IsActivate = ($params->IsActivate == "0") ? "0" : "1";

    /* Asigna valores de parámetros a variables relacionadas con fraudes y restricciones. */
    $fraud = $params->Fraud;
    $abuse_bonuses = $params->Abuse_bonuses;
    $rider = $params->Rider;
    $self_exclusion = $params->Self_exclusion;
    $counter_charges = $params->Counter_charges;
    $under_revision = $params->Under_revision;

    /* verifica si el comentario está vacío y lanza una excepción si lo está. */
    $activated = $params->Activated;
    $Comentario = $params->Comment;
    $ActivateComentario = false;

    if ($Comentario == "" || $Comentario == "NULL" || $Comentario == NULL) {
        throw new exception ("Comentario de contingencia no puede ser vacio", 300022);
    }


    if (true) {

        /* asigna "A" o "I" a la variable $fraud según una condición. */
        if ($fraud == 1) {
            $fraud = "A";
            $ActivateComentario = true;
        } else {
            $fraud = "I";
        }
        try {

            /* Crea configuraciones de usuario y clasificador en un sistema de clasificación de fraude. */
            $Clasificador = new Clasificador('', 'FRAUD');
            $clasificadorId = $Clasificador->clasificadorId;

            $UsuarioConfiguracion2 = new UsuarioConfiguracion($ClientId, 'A', $clasificadorId);
            $UsuarioConfig = $UsuarioConfiguracion2->usuconfigId;

            $UsuarioConfiguracion = new UsuarioConfiguracion('', '', '', '', $UsuarioConfig);


            /* configura valores y estado de usuario, opcionalmente agrega un comentario. */
            $UsuarioConfiguracion->setValor($fraud);
            $UsuarioConfiguracion->setEstado($fraud);


            if ($ActivateComentario) {
                $UsuarioConfiguracion->setNota($Comentario);
                $ActivateComentario = false;
            }


            /* Actualiza la configuración de usuario y registra la transacción en MySQL. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

            $UsuarioLog = new UsuarioLog();

            /* registra información relacionada con usuarios y posibles fraudes en un sistema. */
            $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
            $UsuarioLog->setUsuarioIp($ip);
            $UsuarioLog->setUsuariosolicitaIp(0);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo("FRAUD");
            $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));

            /* inicializa un objeto UsuarioLog con valores predeterminados y configura su estado. */
            $UsuarioLog->setValorDespues(0);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setEstado("A");

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* Inserta un registro en la base de datos y obtiene la transacción actual. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();


        } catch (exception $e) {
            if ($e->getCode() == 46 && $fraud != 'I') {


                /* Se crea un clasificador de fraude y se configura para un usuario específico. */
                $Clasificador = new Clasificador('', 'FRAUD');
                $clasificadorId = $Clasificador->clasificadorId;

                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($clasificadorId);


                /* Configura valores y fechas de usuario, opcionalmente añade un comentario. */
                $UsuarioConfiguracion->setValor($fraud);
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                if ($ActivateComentario) {
                    $UsuarioConfiguracion->setNota($Comentario);
                    $ActivateComentario = false;
                }


                /* configura propiedades de un objeto UsuarioConfiguracion y prepara su almacenamiento en MySQL. */
                $UsuarioConfiguracion->setUsucreaId($ClientId);
                $UsuarioConfiguracion->setUsumodifId(0);
                $UsuarioConfiguracion->setProductoId(0);

                $UsuarioConfiguracion->setEstado($fraud);

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

                /* Inserta configuración de usuario y registra el log con ID e IP del usuario. */
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
                $UsuarioLog->setUsuarioIp($ip);

                /* establece propiedades para un registro de log de usuario fraudulento. */
                $UsuarioLog->setUsuariosolicitaIp(0);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("FRAUD");
                $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));
                $UsuarioLog->setValorDespues(0);
                $UsuarioLog->setUsucreaId(0);

                /* Registra un usuario en la base de datos con estado "A" y modificador 0. */
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setEstado("A");

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();


            }
        }
    }

    //-------------------------------------------------------------------------------

    if (true) {

        /* Condiciona el valor de $rider y $ActivateComentario según su estado inicial. */
        if ($rider == 1 and $rider != "") {
            $rider = "A";
            $ActivateComentario = true;
        } else {
            $rider = "I";
            $ActivateComentario = false;
        }
        try {

            /* Código para inicializar un clasificador y configurar un usuario en el sistema. */
            $Clasificador = new Clasificador('', 'RIDER');
            $clasificadorId = $Clasificador->clasificadorId;
            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $clasificadorId);
            $UsuConfig = $UsuarioConfiguracion->usuconfigId;
            $UsuarioConfiguracion2 = new UsuarioConfiguracion('', '', '', '', $UsuConfig);

            $UsuarioConfiguracion2->setValor($rider);

            /* establece el estado de un usuario y opcionalmente agrega un comentario. */
            $UsuarioConfiguracion2->setEstado($rider);


            if ($ActivateComentario) {
                $UsuarioConfiguracion2->setNota($Comentario);
                $ActivateComentario = false;
            }


            /* gestiona transacciones y actualiza configuraciones de usuario en MySQL. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion2);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

            $UsuarioLog = new UsuarioLog();

            /* Código para registrar información de un usuario en sesión y sus acciones relacionadas. */
            $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
            $UsuarioLog->setUsuarioIp($ip);
            $UsuarioLog->setUsuariosolicitaIp(0);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo("RIDER");
            $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));

            /* Se inicializan valores de un objeto UsuarioLog y se instancia su DAO correspondiente. */
            $UsuarioLog->setValorDespues(0);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setEstado("A");

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* inserta un registro de usuario y obtiene una transacción de la base de datos. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

        } catch (Exception $e) {
            if ($e->getCode() == 46 && $rider != 'I') {

                /* Se crea un clasificador y se asigna su id a la configuración del usuario. */
                $Clasificador = new Clasificador('', 'RIDER');
                $ClasificadorId = $Clasificador->getClasificadorId();

                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($ClasificadorId);


                /* Configura usuario con valores, fechas y opcionalmente añade un comentario. */
                $UsuarioConfiguracion->setValor($rider);
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                if ($ActivateComentario) {
                    $UsuarioConfiguracion->setNota($Comentario);
                    $ActivateComentario = false;
                }

                /* Configura un objeto de usuario y establece varios identificadores y estado. */
                $UsuarioConfiguracion->setUsucreaId($ClientId);
                $UsuarioConfiguracion->setUsumodifId(0);
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setEstado($rider);

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

                /* Inserta configuración de usuario y registra un log de usuario en la sesión. */
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);

                /* establece propiedades de un objeto UsuarioLog relacionado con un usuario. */
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaIp(0);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("RIDER");
                $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));
                $UsuarioLog->setValorDespues(0);

                /* Se establece un nuevo registro de usuario con estado activo en la base de datos. */
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setEstado("A");

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

                /* Confirma y guarda los cambios realizados en la transacción de la base de datos. */
                $UsuarioLogMySqlDAO->getTransaction()->commit();
            }
        }
    }

    if (true) {

        /* asigna valores a variables según el estado de 'activated'. */
        if ($activated == 1) {
            $activated = "A";
            $ActivateComentario = true;
        } else {
            $activated = "I";
            $ActivateComentario = false;
        }
        try {

            /* Se crean instancias de clasificador y configuración de usuario con IDs específicos. */
            $Clasificador = new Clasificador("", "ACTIVE");
            $ClasificadorId = $Clasificador->clasificadorId;
            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $ClasificadorId);
            $UsuConfig = $UsuarioConfiguracion->usuconfigId;

            $UsuarioConfiguracion2 = new UsuarioConfiguracion('', '', '', '', $UsuConfig);


            /* Configura el estado y valor del usuario, añadiendo un comentario si está activado. */
            $UsuarioConfiguracion2->setValor($activated);
            $UsuarioConfiguracion2->setEstado($activated);


            if ($ActivateComentario) {
                $UsuarioConfiguracion2->setNota($Comentario);
                $ActivateComentario = false;
            }

            /* Se gestionan transacciones y actualizaciones de usuario en una base de datos MySQL. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion2);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


            $UsuarioLog = new UsuarioLog();

            /* Se asignan valores a un objeto de registro de usuario en sesión activa. */
            $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
            $UsuarioLog->setUsuarioIp($ip);
            $UsuarioLog->setUsuariosolicitaIp(0);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo("ACTIVE");
            $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));

            /* Se establece un nuevo registro de usuario con valores iniciales en la base de datos. */
            $UsuarioLog->setValorDespues(0);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setEstado("A");

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* Inserta un registro de usuario y confirma la transacción en la base de datos. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

            $Usuario = new Usuario($ClientId);
            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $transaction = $UsuarioMySqlDAO->getTransaction();

            //contingencia deportiva
            if ($activated == "A" and $Usuario->contingenciaDeportes == "A") {


                /* Se registra un log de usuario con información relevante de sesión e IP. */
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaIp(0);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("CONTINGENCIADEPORTEUSUARIO");

                /* Código que actualiza estados e información de un usuario en la base de datos. */
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->contingenciaDeportes);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

                /* Inserta un registro en la base de datos y confirma la transacción, actualizando el usuario. */
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();

                $Usuario->contingenciaDeportes = "I";
//
//                    $UsuarioMySqlDAO= new UsuarioMySqlDAO();
//                    $transaction = $UsuarioMySqlDAO->getTransaction();
//                    $UsuarioMySqlDAO->update($Usuario);
//                    $UsuarioMySqlDAO->getTransaction()->commit();

            }

            if ($activated == "A" and $Usuario->contingencia == "A") {

                /* Se registra un nuevo log de usuario con identificaciones y dirección IP. */
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($ClientId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $UsuarioLog->setUsuariosolicitaIp($ip);


                /* Registra cambios en el tipo y estado del usuario en un log. */
                $UsuarioLog->setTipo("CONTINGENCIAUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->contingencia);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


                /* Se inserta un registro y se confirma la transacción en la base de datos. */
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();


                $Usuario->contingencia = "I";
            }


            if ($activated == "A" and $Usuario->contingenciaCasino == "A") {

                /* crea un registro de actividad de usuario con información de sesión y IP. */
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($ClientId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $UsuarioLog->setUsuariosolicitaIp($ip);


                /* Registro de cambios en el estado del usuario en el sistema de contingencia. */
                $UsuarioLog->setTipo("CONTINGENCIACASINOUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->contingenciaCasino);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


                /* Se inserta un registro de usuario y se actualiza su estado en contingencia. */
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();


                $Usuario->contingenciaCasino = "I";

            }


            if ($activated == "A" and $Usuario->contingenciaCasvivo == "A") {

                /* Se crea un objeto de registro de usuario y se asignan propiedades específicas. */
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($ClientId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $UsuarioLog->setUsuariosolicitaIp($ip);


                /* Registra cambios en el estado de un usuario en contingencia. */
                $UsuarioLog->setTipo("CONTINGENCIACASINOVIVOUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->contingenciaCasvivo);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


                /* Código que registra un log de usuario y actualiza estado en la base de datos. */
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();

                $Usuario->contingenciaCasvivo = "I";

            }


            /* Registra un log de un usuario al activar contingencias virtuales si se cumplen condiciones. */
            if ($activated == "A" and $Usuario->contingenciaVirtuales == "A") {
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($ClientId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $UsuarioLog->setUsuariosolicitaIp($ip);

                $UsuarioLog->setTipo("CONTINGENCIAVIRTUALESUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->contingenciaVirtuales);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);

                $Usuario->contingenciaVirtuales = "I";
            }


            /* Registra cambios en el estado de contingencia de un usuario si están activados. */
            if ($activated == "A" and $Usuario->contingenciaPoker == "A") {
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($ClientId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $UsuarioLog->setUsuariosolicitaIp($ip);

                $UsuarioLog->setTipo("CONTINGENCIAPOKERUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->contingenciaPoker);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


                $Usuario->contingenciaPoker = "I";

            }


            /* Registra un log de usuario al activar la contingencia de retiro. */
            if ($activated == "A" and $Usuario->contingenciaRetiro == "A") {
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($ClientId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $UsuarioLog->setUsuariosolicitaIp($ip);

                $UsuarioLog->setTipo("CONTINGENCIARETIROUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->contingenciaRetiro);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


                $Usuario->contingenciaRetiro = "I";
            }


            /* Registra un log de usuario si se cumplen ciertas condiciones de contingencia. */
            if ($activated == "A" and $Usuario->contingenciaDeposito == "A") {
                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($ClientId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $UsuarioLog->setUsuariosolicitaIp($ip);

                $UsuarioLog->setTipo("CONTINGENCIADEPOSITOUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->contingenciaDeposito);
                $UsuarioLog->setValorDespues($IsActivate);
                $UsuarioLog->setUsucreaId($_SESSION['usuario2']);
                $UsuarioLog->setUsumodifId($_SESSION['usuario2']);


                $Usuario->contingenciaDeposito = "I";
            }


            /* Actualiza un usuario en MySQL y confirma la transacción realizada. */
            $UsuarioMySqlDAO->update($Usuario);
            $transaction->commit();

        } catch (exception $e) {
            if ($e->getCode() == 46 && $activated != 'I') {


                /* Se crea un clasificador y se configura un usuario con su ID y tipo. */
                $Clasificador = new Clasificador("", "ACTIVE");
                $ClasificadorId = $Clasificador->clasificadorId;

                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($ClasificadorId);


                /* Configura valores de usuario, incluyendo fechas y IDs de creación y modificación. */
                $UsuarioConfiguracion->setValor($activated);
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                $UsuarioConfiguracion->setUsucreaId($ClientId);
                $UsuarioConfiguracion->setUsumodifId(0);

                /* configura un usuario, gestionando un comentario y su estado de activación. */
                $UsuarioConfiguracion->setProductoId(0);
                if ($ActivateComentario) {
                    $UsuarioConfiguracion->setNota($Comentario);
                    $ActivateComentario = false;
                }
                $UsuarioConfiguracion->setEstado($activated);


                /* Se inserta la configuración de usuario y se confirma la transacción en MySQL. */
                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


                $UsuarioLog = new UsuarioLog();

                /* establece propiedades del usuario en una sesión, incluyendo ID, IP y estado. */
                $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaIp(0);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("ACTIVE");
                $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));

                /* Se inicializan valores y propiedades en un objeto UsuarioLog para almacenamiento en base de datos. */
                $UsuarioLog->setValorDespues(0);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setEstado("A");

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

                /* Inserta un registro de usuario y obtiene la transacción actual en MySQL. */
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();
            }
        }
    }


    if (true) {

        /* asigna valores a variables según el estado de $under_revision. */
        if ($under_revision == 1) {
            $under_revision = "A";
            $ActivateComentario = true;
        } else {
            $under_revision = "I";
            $ActivateComentario = false;
        }
        try {

            /* crea instancias de clasificador y configuración de usuario usando identificadores. */
            $Clasificador = new Clasificador("", "UNDERREVIEW");
            $ClasificadorId = $Clasificador->clasificadorId;
            $UsuarioConfiguracion1 = new UsuarioConfiguracion($ClientId, 'A', $ClasificadorId);
            $UsuConfig = $UsuarioConfiguracion1->usuconfigId;

            $UsuarioConfiguracion = new UsuarioConfiguracion('', '', '', '', $UsuConfig);

//            if($UsuarioConfiguracion->getValor() != $under_revision
//                ||( $UsuarioConfiguracion->nota != $Comentario  && $ActivateComentario) ) {


            /* establece valores y estado, y opcionalmente agrega un comentario. */
            $UsuarioConfiguracion->setValor($under_revision);
            $UsuarioConfiguracion->setEstado($under_revision);


            if ($ActivateComentario) {
                $UsuarioConfiguracion->setNota($Comentario);
                $ActivateComentario = false;
            }

            /* Se realiza una transacción para actualizar la configuración de usuario en MySQL. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


            $UsuarioLog = new UsuarioLog();

            /* Código que registra información del usuario y su actividad en sesión. */
            $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
            $UsuarioLog->setUsuarioIp($ip);
            $UsuarioLog->setUsuariosolicitaIp(0);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo("UNDERREVIEW");
            $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));

            /* Se inicializan valores y estado de un objeto UsuarioLog en MySQL. */
            $UsuarioLog->setValorDespues(0);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setEstado("A");

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* inserta un registro y obtiene la transacción en MySQL. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

        } catch (Exception $e) {
            if ($e->getCode() == 46 && $under_revision != 'I') {


                /* Crea un clasificador en estado "UNDERREVIEW" y configura el usuario asociado. */
                $Clasificador = new Clasificador("", "UNDERREVIEW");
                $ClasificadorId = $Clasificador->clasificadorId;

                $UsuarioConfiguracion = new UsuarioConfiguracion;
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($ClasificadorId);


                /* Se establecen valores y fechas en la configuración de usuario. */
                $UsuarioConfiguracion->setValor($under_revision);
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);


                $UsuarioConfiguracion->setUsucreaId($ClientId);

                /* configura un usuario y asigna un comentario si está activo. */
                $UsuarioConfiguracion->setUsumodifId(0);
                $UsuarioConfiguracion->setProductoId(0);
                if ($ActivateComentario) {
                    $UsuarioConfiguracion->setNota($Comentario);
                    $ActivateComentario = false;
                }

                /* actualiza el estado y registra configuración de usuario en MySQL. */
                $UsuarioConfiguracion->setEstado($under_revision);


                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                /* commit de la transacción y registra la IP del usuario. */
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioIp($_SESSION["usuario2"]);
                $UsuarioLog->setUsuarioIp($ip);

                /* Código que establece parámetros para un registro de usuario bajo revisión. */
                $UsuarioLog->setUsuariosolicitaIp(0);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("UNDERREVIEW");
                $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));
                $UsuarioLog->setValorDespues(0);
                $UsuarioLog->setUsucreaId(0);

                /* Código que inserta un registro de usuario en la base de datos con estado activo. */
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setEstado("A");

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();

            }
        }
    }


    if (true) {

        /* asigna valores y activa comentarios según el contador de cargos. */
        if ($counter_charges == 1) {
            $counter_charges = "A";
            $ActivateComentario = true;
        } else {
            $counter_charges = "I";
            $ActivateComentario = false;
        }
        try {

            /* Se crea un clasificador y se configura un usuario con su ID. */
            $Clasificador = new Clasificador("", "AGAINCHARGES");
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $ClasificadorId);

            $UsuConfig = $UsuarioConfiguracion->usuconfigId;

            $UsuarioConfiguracion = new UsuarioConfiguracion('', '', '', '', $UsuConfig);


            /* Configura valores y estado del usuario, opcionalmente agrega un comentario. */
            $UsuarioConfiguracion->setValor($counter_charges);
            $UsuarioConfiguracion->setEstado($counter_charges);


            if ($ActivateComentario) {
                $UsuarioConfiguracion->setNota($Comentario);
                $ActivateComentario = false;
            }

            /* Se actualiza la configuración del usuario y se registran los cambios. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO;
            $transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

            $UsuarioLog = new UsuarioLog();

            /* Configura un objeto de log de usuario con datos de sesión y parámetros específicos. */
            $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
            $UsuarioLog->setUsuarioIp($ip);
            $UsuarioLog->setUsuariosolicitaIp(0);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo("AGAINCHARGES");
            $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));

            /* Se inicializan valores en un objeto UsuarioLog y se crea su DAO correspondiente. */
            $UsuarioLog->setValorDespues(0);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setEstado("A");

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* Inserta un registro de log de usuario en MySQL y obtiene la transacción actual. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

        } catch (Exception $e) {
            if ($e->getCode() == "46" and $counter_charges != "I") {


                /* Se crea un clasificador y se configura para un usuario específico. */
                $Clasificador = new Clasificador("", "AGAINCHARGES");
                $ClasificadorId = $Clasificador->getClasificadorId();
                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($ClasificadorId);

                $UsuarioConfiguracion->setValor($counter_charges);

                /* configura fechas y usuarios en un objeto de configuración de usuario. */
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                $UsuarioConfiguracion->setUsucreaId($ClientId);
                $UsuarioConfiguracion->setUsumodifId(0);
                $UsuarioConfiguracion->setProductoId(0);


                /* Se configuran estado y nota de usuario, inicializando un DAO para MySQL. */
                $UsuarioConfiguracion->setEstado($counter_charges);
                if ($ActivateComentario) {
                    $UsuarioConfiguracion->setNota($Comentario);
                    $ActivateComentario = false;
                }
                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

                /* inserta una configuración de usuario y registra la acción en el log. */
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);

                /* registra información del usuario y sus acciones en un log. */
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaIp(0);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("AGAINCHARGES");
                $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));
                $UsuarioLog->setValorDespues(0);

                /* Se crea un registro de usuario con estado activo en la base de datos. */
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setEstado("A");

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

                /* Inicia un proceso para confirmar cambios en la base de datos usando MySQL. */
                $UsuarioLogMySqlDAO->getTransaction()->commit();
            }
        }

    }


    if (true) {

        /* asigna valores y activa un comentario según la exclusión personal. */
        if ($self_exclusion == 1) {
            $self_exclusion = "A";
            $ActivateComentario = true;
        } else {
            $self_exclusion = "I";
            $ActivateComentario = false;
        }
        try {

            /* Se crea un clasificador y se configura un usuario basado en dicho clasificador. */
            $Clasificador = new Clasificador("", "SELFEXCLUSION");
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $ClasificadorId);

            $UsuConfig = $UsuarioConfiguracion->usuconfigId;
            $UsuarioConfiguracion = new UsuarioConfiguracion('', '', '', '', $UsuConfig);


            /* establece valores de configuración del usuario y opcionalmente agrega una nota. */
            $UsuarioConfiguracion->setValor($self_exclusion);
            $UsuarioConfiguracion->setEstado($self_exclusion);


            if ($ActivateComentario) {
                $UsuarioConfiguracion->setNota($Comentario);
                $ActivateComentario = false;
            }

            /* Código para actualizar la configuración de usuario en MySQL y registrar transacciones. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO;
            $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

            $UsuarioLog = new UsuarioLog();

            /* Registro de actividad de usuario con información sobre IP y tipo de acción. */
            $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
            $UsuarioLog->setUsuarioIp($ip);
            $UsuarioLog->setUsuariosolicitaIp(0);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo("SELFEXCLUSION");
            $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));

            /* Se inicializan propiedades del objeto `UsuarioLog` y se crea una instancia de `UsuarioLogMySqlDAO`. */
            $UsuarioLog->setValorDespues(0);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setEstado("A");

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* Inserta un registro de usuario en MySQL y gestiona la transacción correspondiente. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

        } catch (Exception $e) {
            if ($e->getCode() == 46 && $self_exclusion != 'I') {


                /* crea un clasificador y configura un usuario con su ID. */
                $Clasificador = new Clasificador("", "SELFEXCLUSION");
                $ClasificadorId = $Clasificador->getClasificadorId();

                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($ClasificadorId);


                /* configura valores y fechas para un objeto de usuario. */
                $UsuarioConfiguracion->setValor($self_exclusion);
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);

                $UsuarioConfiguracion->setUsucreaId($ClientId);
                $UsuarioConfiguracion->setUsumodifId(0);

                /* Configura un usuario con ID de producto, estado y opcionalmente una nota. */
                $UsuarioConfiguracion->setProductoId(0);


                $UsuarioConfiguracion->setEstado($self_exclusion);
                if ($ActivateComentario) {
                    $UsuarioConfiguracion->setNota($Comentario);
                    $ActivateComentario = false;
                }

                /* Se inserta configuración de usuario y se registra en el log como actividad. */
                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);

                /* Código para registrar un log de usuario en el sistema de autoexclusión. */
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaIp(0);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("SELFEXCLUSION");
                $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));
                $UsuarioLog->setValorDespues(0);

                /* Se establece un nuevo registro en la base de datos para UsuarioLog. */
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setEstado("A");

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

                /* realiza la confirmación de una transacción en una base de datos MySQL. */
                $UsuarioLogMySqlDAO->getTransaction()->commit();

            }
        }

    }


    if (true) {

        /* asigna valores a variables según la condición de $abuse_bonuses. */
        if ($abuse_bonuses == 1) {
            $abuse_bonuses = "A";
            $ActivateComentario = true;
        } else {
            $abuse_bonuses = "I";
            $ActivateComentario = false;
        }

        try {


            /* Se crea un clasificador y se configura el usuario relacionado con bonificaciones. */
            $Clasificador = new Clasificador("", "BONDABUSER");
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $ClasificadorId);
            $UsuConfig = $UsuarioConfiguracion->usuconfigId;
            $UsuarioConfiguracion = new UsuarioConfiguracion('', '', '', '', $UsuConfig);
            $UsuarioConfiguracion->setValor($abuse_bonuses);

            /* configura el estado y nota de usuario basándose en ciertos parámetros. */
            $UsuarioConfiguracion->setEstado($abuse_bonuses);


            if ($ActivateComentario) {
                $UsuarioConfiguracion->setNota($Comentario);
                $ActivateComentario = false;
            }

            /* Actualiza la configuración de usuario y registra la transacción en MySQL. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO;
            $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


            $UsuarioLog = new UsuarioLog();

            /* registra información de usuario y comentario para un sistema de seguimiento. */
            $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);
            $UsuarioLog->setUsuarioIp($ip);
            $UsuarioLog->setUsuariosolicitaIp(0);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo("BONDABUSER");
            $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));

            /* Configura valores iniciales para el objeto UsuarioLog y crea una instancia DAO. */
            $UsuarioLog->setValorDespues(0);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setEstado("A");

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* Se inserta un registro en la base de datos y se obtiene la transacción. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

        } catch (Exception $e) {

            if ($e->getCode() == 46 && $abuse_bonuses != 'I') {


                /* Se crea un clasificador y se configura un usuario con su ID y tipo. */
                $Clasificador = new Clasificador("", "BONDABUSER");
                $ClasificadorId = $Clasificador->getClasificadorId();

                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($ClientId);
                $UsuarioConfiguracion->setTipo($ClasificadorId);


                /* Configura las fechas y valores de usuario, asignando identificador del creador. */
                $UsuarioConfiguracion->setFechaInicio($startDateFormatted);
                $UsuarioConfiguracion->setFechaFin($endDateFormatted);
                $UsuarioConfiguracion->setValor($abuse_bonuses);


                $UsuarioConfiguracion->setUsucreaId($ClientId);

                /* Configura un usuario estableciendo identificadores, estado y comentario cuando corresponde. */
                $UsuarioConfiguracion->setUsumodifId(0);
                $UsuarioConfiguracion->setProductoId(0);


                $UsuarioConfiguracion->setEstado($abuse_bonuses);
                if ($ActivateComentario) {
                    $UsuarioConfiguracion->setNota($Comentario);
                    $ActivateComentario = false;
                }

                /* inserta una configuración de usuario en MySQL y registra la acción. */
                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($_SESSION["usuario2"]);

                /* Se establece un registro de usuario con datos y valores especificados. */
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaIp(0);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("BONDABUSER");
                $UsuarioLog->setValorAntes(($ActivateComentario ? $Comentario : ''));
                $UsuarioLog->setValorDespues(0);

                /* Código para crear un nuevo registro de usuario en una base de datos. */
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setEstado("A");

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->insert($UsuarioLog);

                /* indica que se confirma una transacción en una base de datos MySQL. */
                $UsuarioLogMySqlDAO->getTransaction()->commit();

            }
        }

    }


    /* define una respuesta exitosa sin errores ni mensajes de advertencia. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Datos correctos";
    $response["ModelErrors"] = [];

} else {
    /* Código para manejar errores y notificaciones en una respuesta de datos. */

    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "Datos incorrectos";
    $response["ModelErrors"] = [];
}



