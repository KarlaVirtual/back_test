<?php

use Backend\dto\Clasificador;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Usuario;

/**
 * Account/LogoutRelationshipUser
 *
 * Manejo de sesión y registro de cierre de sesión para perfiles de Punto de Venta y Cajero.
 *
 * Este recurso gestiona la configuración de sesión para usuarios con perfil "PUNTOVENTA" o "CAJERO". Si el usuario tiene una configuración activa, se procede a actualizar su estado, registrar su cierre de sesión y almacenar un log con los datos de la transacción.
 *
 * @param string $_SESSION["win_perfil"] : Perfil del usuario autenticado (PUNTOVENTA o CAJERO).
 * @param int $_SESSION["usuario"] : Identificador del usuario en sesión.
 *
 * @returns no
 *
 * @throws Exception Si ocurre un error durante la actualización de la configuración del usuario o el registro del log de sesión.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {

    /* Se crea una instancia de la clase Clasificador para el tipo 'RELATIONSHIPUSERONLINE'. */
    $Clasificador = new Clasificador('', 'RELATIONSHIPUSERONLINE');
    try {

        /* Se crea un objeto de configuración de usuario y se obtiene la plataforma del navegador. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($_SESSION['usuario'], 'A', $Clasificador->getClasificadorId());

        $plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
        $plaform = str_replace('"', "", $plaform);
        $Usuario = new Usuario($_SESSION['usuario']);

        if (intval($UsuarioConfiguracion->getValor()) !== 0) {

            /* gestiona la configuración de un usuario y registra transacciones. */
            $Usuarioantes = intval($UsuarioConfiguracion->getValor());
            $UsuarioConfiguracion->setValor(0);
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $Transaccion = $UsuarioConfiguracionMySqlDAO->getTransaction();

            $UsuarioLog = new UsuarioLog();

            /* Registra la información del usuario durante el proceso de cierre de sesión. */
            $UsuarioLog->setUsuarioId($_SESSION["usuario"]);
            $UsuarioLog->setUsuarioIp($Usuario->dirIp);
            $UsuarioLog->setUsuariosolicitaId($Usuarioantes);
            $UsuarioLog->setUsuariosolicitaIp($Usuario->dirIp);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo('LOGOUTPV');

            /* Registro de cambios de estado y datos de usuario en el sistema. */
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->dirIp);
            $UsuarioLog->setValorDespues($Usuario->dirIp);

            $UsuarioLog->setSoperativo($plaform);
            $UsuarioLog->setSversion($plaform);


            /* inserta un registro de usuario y confirma la transacción en la base de datos. */
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaccion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $Transaccion->commit();
        }
    } catch (Exception $e) {
        /* Captura excepciones en PHP sin ejecutar acciones dentro del bloque catch. */

    }

}

