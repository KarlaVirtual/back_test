<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;



require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/

ini_set('memory_limit', '-1');

$message = "*CRON: (cronMensajes) * " . " - Fecha: " . date("Y-m-d H:i:s");
$ConfigurationEnvironment = new ConfigurationEnvironment();

if (!$ConfigurationEnvironment->isDevelopment()) {
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}


$hour = date('H');
if(intval($hour)==0){
    sleep(900);
}

$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
$transaccion->getConnection()->beginTransaction();

$sql = "
SELECT *
        FROM usuario_mensajecampana
        WHERE fecha_expiracion > now() and estado='I' and fecha_envio < now()
";

$dataParaEstadoA = $BonoInterno->execQuery($transaccion, $sql);
foreach ($dataParaEstadoA as $datanum) {
    $sql = "UPDATE usuario_mensajecampana  SET estado = 'A' WHERE usumencampana_id = '" . $datanum->{'usuario_mensajecampana.usumencampana_id'} . "' ";
    $BonoInterno->execQuery($transaccion, $sql);

}

$sql = "
SELECT *
        FROM usuario_mensajecampana
        WHERE fecha_expiracion < now() and estado='A'
";


$dataParaEstadoI = $BonoInterno->execQuery($transaccion, $sql);
foreach ($dataParaEstadoI as $datanum) {
    $sql = "UPDATE usuario_mensajecampana  SET estado = 'I' WHERE usumencampana_id = '" . $datanum->{'usuario_mensajecampana.usumencampana_id'} . "' ";
    $BonoInterno->execQuery($transaccion, $sql);

}
$transaccion->commit();

$message = "*CRON: FIN (cronMensajes) * " . " - Fecha: " . date("Y-m-d H:i:s");

if (!$ConfigurationEnvironment->isDevelopment()) {
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}

$hour = date('H');
if(false) {


//DORADOBET PERU


// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'DoradoBet. BONO DE BIENVENIDA DE HASTA S/500! Recarga ahora desde S/30 y recibe un bono del 100% de tu primer deposito. Aplican TyC: ';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Primer_Deposito_PE_Marzo2023&utm_term=Bono&utm_content=Bono_Primer_Deposito_PE_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,'173');


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'DoradoBet. BONO DE BIENVENIDA DE HASTA S/500! Recarga ahora desde S/30 y recibe un bono del 100% de tu primer deposito. Aplican TyC: ';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Primer_Deposito_PE_Marzo2023&utm_term=Bono&utm_content=Bono_Primer_Deposito_PE_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,'173');


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'DoradoBet. BONO DE BIENVENIDA DE HASTA S/500! Recarga ahora desde S/30 y recibe un bono del 100% de tu primer deposito. Aplican TyC: ';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Primer_Deposito_PE_Marzo2023&utm_term=Bono&utm_content=Bono_Primer_Deposito_PE_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,'173');


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 14 and usuario.pais_id = 33
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Lotosports dobra seu deposito ate R$1000 para apostar no seu time do coracao. Aplicam-se TeC. Jogue agora:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://lotosports.bet/deportes?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bonus_Primeiro_Deposito_BRA_Marco2023&amp;utm_term=Bonus&amp;utm_content=Bonus_Primeiro_Deposito_BRA_Marco2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,'33');


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 14 and usuario.pais_id = 33
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Lotosports dobra seu deposito ate R$1000 para apostar no seu time do coracao. Aplicam-se TeC. Jogue agora:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://lotosports.bet/deportes?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bonus_Primeiro_Deposito_BRA_Marco2023&amp;utm_term=Bonus&amp;utm_content=Bonus_Primeiro_Deposito_BRA_Marco2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,'33');


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 14 and usuario.pais_id = 33
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Lotosports dobra seu deposito ate R$1000 para apostar no seu time do coracao. Aplicam-se TeC. Jogue agora:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://lotosports.bet/deportes?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bonus_Primeiro_Deposito_BRA_Marco2023&amp;utm_term=Bonus&amp;utm_content=Bonus_Primeiro_Deposito_BRA_Marco2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,'33');


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }



// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8 and usuario.pais_id = 66
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Ecuabet. Recibe hasta $300 realizando tu primer deposito; recarga y comienza a experimentar toda la emocion de ganar aqui:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Primer_Deposito_ECU_Marzo2023&utm_term=Recarga&utm_content=Bono_Primer_Deposito_ECU_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,66);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8 and usuario.pais_id = 66
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. Recibe hasta $300 realizando tu primer deposito; recarga y comienza a experimentar toda la emocion de ganar aqui:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Primer_Deposito_ECU_Marzo2023&utm_term=Recarga&utm_content=Bono_Primer_Deposito_ECU_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,66);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8 and usuario.pais_id = 66
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. Recibe hasta $300 realizando tu primer deposito; recarga y comienza a experimentar toda la emocion de ganar aqui:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Primer_Deposito_ECU_Marzo2023&utm_term=Recarga&utm_content=Bono_Primer_Deposito_ECU_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,66);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 94
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'En DoradoBet, haz tu primer deposito y te damos un bono del 100% hasta Q3000, registrate y gana:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_GTM_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_GTM_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,94);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 94
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);



        $messagesms = 'En DoradoBet, haz tu primer deposito y te damos un bono del 100% hasta Q3000, registrate y gana:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_GTM_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_GTM_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,94);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 94
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'En DoradoBet, haz tu primer deposito y te damos un bono del 100% hasta Q3000, registrate y gana:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_GTM_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_GTM_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,94);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 66
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'DoradoBet. Haz tu primer deposito y recibe un bono del 100% hasta $250. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_DECU_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_DECU_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,66);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 66
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);



        $messagesms = 'DoradoBet. Haz tu primer deposito y recibe un bono del 100% hasta $250. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_DECU_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_DECU_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,66);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 66
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'DoradoBet. Haz tu primer deposito y recibe un bono del 100% hasta $250. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_DECU_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_DECU_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,66);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 46
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Doradobet. Duplicamos tu primer deposito, recarga y recibe un bono del 100% hasta CLP/ 200.000. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_CL_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_CL_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,46);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 46
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);



        $messagesms = 'Doradobet. Duplicamos tu primer deposito, recarga y recibe un bono del 100% hasta CLP/ 200.000. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_CL_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_CL_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,46);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 46
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Duplicamos tu primer deposito, recarga y recibe un bono del 100% hasta CLP/ 200.000. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_CL_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_CL_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,46);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }




// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 60
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'DoradoBet. Aumenta tus ganancias y vivi la emocion del deporte! Deposita y recibi un bono del 100% hasta CRC125.000. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_CR_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_CR_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,60);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 60
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);



        $messagesms = 'DoradoBet. Aumenta tus ganancias y vivi la emocion del deporte! Deposita y recibi un bono del 100% hasta CRC125.000. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_CR_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_CR_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,60);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 60
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'DoradoBet. Aumenta tus ganancias y vivi la emocion del deporte! Deposita y recibi un bono del 100% hasta CRC125.000. Aplica TyC:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_CR_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_CR_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,60);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }



// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 2
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'DoradoBet. Aumenta tus ganancias y vive toda la emocion del deporte! Deposita y recibe un bono del 100% hasta $100. Aplica TyC: ';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_NIC_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_NIC_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,2);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 2
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);



        $messagesms = 'DoradoBet. Aumenta tus ganancias y vive toda la emocion del deporte! Deposita y recibe un bono del 100% hasta $100. Aplica TyC: ';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_NIC_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_NIC_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,2);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-03') !== false || true
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 2
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'DoradoBet. Aumenta tus ganancias y vive toda la emocion del deporte! Deposita y recibe un bono del 100% hasta $100. Aplica TyC: ';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_NIC_Marzo2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_NIC_Marzo2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes,2);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 1 hora antes
    if (strpos(date("Y-m-d"), '2023-02') !== false
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 13 and usuario.pais_id = 146
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Vive toda la emocion de comenzar ganando con Eltribet, Realiza tu primer deposito y recibe hasta MXN 4.000 + 30 giros en casino:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://eltribet.com/deportes?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_TRI_Febrero2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_TRI_Febrero2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


// 1. Bono Primer Deposito Peru 2 dias antes
    if (strpos(date("Y-m-d"), '2023-02') !== false
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 13 and usuario.pais_id = 146
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-48 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-47 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-48 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-47 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);



        $messagesms = 'Vive toda la emocion de comenzar ganando con Eltribet, Realiza tu primer deposito y recibe hasta MXN 4.000 + 30 giros en casino:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://eltribet.com/deportes?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_TRI_Febrero2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_TRI_Febrero2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

// 1. Bono Primer Deposito Peru 7 dias antes
    if (strpos(date("Y-m-d"), '2023-02') !== false
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 13 and usuario.pais_id = 146
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-168 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-167 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-168 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-167 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Vive toda la emocion de comenzar ganando con Eltribet, Realiza tu primer deposito y recibe hasta MXN 4.000 + 30 giros en casino:';

        $linkGlobal = 'https://bit.ly/3QXPUPl';
        $linkGlobal = 'https://eltribet.com/deportes?utm_source=Intico&amp;utm_medium=SMS&amp;utm_campaign=Bono_Primer_Deposito_TRI_Febrero2023&amp;utm_term=Deposito&amp;utm_content=Bono_Primer_Deposito_TRI_Febrero2023';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('121');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


//DORADOBET PERU
// 1. Bono Primer Deposito Peru
    if (strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Doradobet. Realiza tu primer deposito y recibe tu bono de bienvenida, hasta S/.500 para ganar. Aplica TyC. Deposita ya';

        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Primer_Deposito_Aut_PE_Agosto2022&utm_term=Apuestas&utm_content=Bono_Primer_Deposito_Aut_PE_Agosto2022';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('67');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//DORADOBET PERU
// 2. Solicitudes de primer deposito Doradobet
    if (strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false
    ) {

        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id)
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d H:59:59", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. No olvides recargar tu cuenta de Doradobet.com y aprovecha las increibles promos que tenemos para ti! Aqui nunca dejas de ganar';

        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Recordatorio_Bono_Primer_Deposito_Aut_PE_Agosto2022&utm_term=Apuestas&utm_content=Recordatorio_Bono_Primer_Deposito_Aut_PE_Agosto2022';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' ';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' ';
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('69');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA3) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


//DORADOBET PERU -- INACTIVADO
// 3. Solicitud Automatización promociones de la semana SOLO JUEVES A LA 9 AM
    if ((
            strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false

        ) && date('G') == 12 && false
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id and
                                             usuario_recarga.fecha_crea > '" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'
    and usuario_recarga.fecha_crea < '" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "')
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'";

        $sql = $sql . ' ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. No olvides recargar tu cuenta de Doradobet.com y aprovecha las increibles promos que tenemos para ti! Aqui nunca dejas de ganar';
        // $messagesms .= ' https://bit.ly/3tw5yaJ';

        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&utm_medium=SMS&utm_campaign=Recordatorio_Deposito_Aut_PE_Mayo2022&utm_term=Apostar&utm_content=Recordatorio_Deposito_Aut_PE_Mayo2022';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('71');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//DORADOBET PERU
// 5. Saldo Billetera Doradobet A LA 1PM
    if ((
            strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false

        ) && date('G') == 13
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,
       registro.celular,
       usuario_mandante.usumandante_id,
       registro.creditos + registro.creditos_base saldo
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 75


where usuario_automation.usuautomation_id is NULL
  and usuario.mandante = 0 and usuario.pais_id = 173
  and (registro.creditos + registro.creditos_base) > 5
    AND usuario.fecha_ult <= '" . date("Y-m-d 23:59:59", strtotime('-4 day')) . "'
order by usuario.fecha_ult desc
LIMIT 0,1000
 ";


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Disfruta de tu saldo! Juega en el casino y apuesta a tus deportes favoritos para que multipliques tus ganancias. Gana ya!';


        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Saldo_Billetera_Aut_PE_Agosto2022&utm_term=Apostar&utm_content=Saldo_Billetera_Aut_PE_Agosto2022';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('75');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//DORADOBET PERU
// 7. Automatización Recordatorio Primer Deposito
    if ((
            strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false

        ) && date('G') == 12
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id)
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173 ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-7 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id ASC LIMIT 0,5000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Empieza a ganar! Haz tu primer deposito y recibe el 100% adicional hasta S/.500 para que multipliques tus ganancias. Aplica tyc';


        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Record_Primer_Deposito_Sem_Aut_PE_Agosto2022&utm_term=Apuestas&utm_content=Record_Primer_Deposito_Sem_Aut_PE_Agosto2022';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('79');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


//DORADOBET PERU
// 8. Aumentar las ventas de casino a traves de sus promociones / Freespins Back
    if ((
             strpos(date("Y-m-d"), '2022-07') !== false

        ) && date('G') == 14 && (date('N')=='1' || date('N')=='3')
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from data_completa2
         inner join usuario on usuario.usuario_id = data_completa2.usuario_id
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
        left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 81


where usuario_automation.usuautomation_id is NULL and  usuario.mandante = 0 and usuario.pais_id = 173 ";

        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino >= ' . "'" . date("Y-m-d 00:00:00", strtotime('-2 days')) . "'";
        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-2 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY data_completa2.ultimo_inicio_sesion DESC LIMIT 0,1000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'DoradoBet. Aqui Juegas tranquilo! Diviertete en las tragamonedas y si no ganas, te devolveremos el 30% hasta S/.600. Aplica TyC.';


        $linkGlobal = 'https://doradobet.com/new-casino?utm_source=Intico&utm_medium=SMS&utm_campaign=Freespins_Back_Aut_PE_Agosto&utm_term=Casino&utm_content=Freespins_Back_Aut_PE_Agosto';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('81');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//DORADOBET PERU
// 9. Automatización Doradobet PE -  Miercoles Habanero
    if ((
             strpos(date("Y-m-d"), '2022-07') !== false

        ) && date('G') == 11 && (date('N')=='3') && false
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from data_completa2
         inner join usuario on usuario.usuario_id = data_completa2.usuario_id
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
        left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 84


where usuario_automation.usuautomation_id is NULL and  usuario.mandante = 0 and usuario.pais_id = 173 ";

        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino >= ' . "'" . date("Y-m-d 00:00:00", strtotime('-5 days')) . "'";
        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-5 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY data_completa2.ultimo_inicio_sesion DESC LIMIT 0,1000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Miercoles de Habanero! Juega en las tragamonedas y si no ganas, te devolvemos hasta S/.300. No te lo pierdas! Aplica tyc';


        $linkGlobal = 'https://doradobet.com/new-casino/proveedor/HABANERO?utm_source=Intico&utm_medium=SMS&utm_campaign=Miercoles_De_Habanero_Aut_PE_Julio&utm_term=Tragamonedas&utm_content=Miercoles_De_Habanero_Aut_PE_Juio';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('81');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//DORADOBET PERU
// 10. Automatización Doradobet PE -  Cashback / Cashback Life in Vegas
    if ((
             strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false

        ) && date('G') == 12 && (date('N')=='1' || date('N')=='3')
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id,
       registro.celular,
       usuario_mandante.usumandante_id,
       SUM(usucasino_detalle_resumen.valor) valorTotal
from usucasino_detalle_resumen
         inner join producto_mandante on producto_mandante.prodmandante_id = usucasino_detalle_resumen.producto_id
         inner join producto on producto_mandante.producto_id = producto.producto_id
         inner join subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = usucasino_detalle_resumen.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 87
         inner join data_completa2 on data_completa2.usuario_id = usuario_mandante.usumandante_id


where usuario_automation.usuautomation_id is NULL
     and subproveedor.tipo = 'CASINO'
  and usucasino_detalle_resumen.tipo IN ('DEBIT', 'DEBITFREECASH')
  and usuario.mandante = 0
  and usuario.pais_id = 173 ";

        $sql = $sql . ' and usucasino_detalle_resumen.fecha_crea >= ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 days')) . "'";
        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasinovivo < ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id HAVING valorTotal >= 100 ORDER BY data_completa2.ultimo_inicio_sesion DESC  LIMIT 0,1500 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'DoradoBet. Nosotros te cuidamos! Juega en nuestro casino en vivo y si no ganas, te devolveremos el 30% hasta S/.800. Aplica tyc';


        $linkGlobal = 'https://doradobet.com/live-casino-vivo?utm_source=Intico&utm_medium=SMS&utm_campaign=Cashback_Life_In_Vegas_Aut_PE_Agosto&utm_term=Casino&utm_content=Cashback_Life_In_Vegas_Aut_PE_Agosto';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('87');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//DORADOBET PERU
// 11. Golden Days Isoftbet
    if ((
             strpos(date("Y-m-d"), '2022-07') !== false ||  strpos(date("Y-m-d"), '2022-08') !== false

        ) && date('G') == 13 && (date('N')=='5' || date('N')=='6')
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "
select usuario.usuario_id,
       registro.celular,
       usuario_mandante.usumandante_id,
       SUM(usucasino_detalle_resumen.valor) valorTotal
from usucasino_detalle_resumen
         inner join producto_mandante on producto_mandante.prodmandante_id = usucasino_detalle_resumen.producto_id
         inner join producto on producto_mandante.producto_id = producto.producto_id
         inner join subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = usucasino_detalle_resumen.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 90
         inner join data_completa2 on data_completa2.usuario_id = usuario_mandante.usumandante_id

INNER JOIN (

select usuario.usuario_id,
       registro.celular,
       usuario_mandante.usumandante_id,
       SUM(usucasino_detalle_resumen.valor) GGR
from usucasino_detalle_resumen
         inner join usuario_mandante on usuario_mandante.usumandante_id = usucasino_detalle_resumen.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id



where  usucasino_detalle_resumen.fecha_crea >= '" .  date("Y-m-01 00:00:00") . "'
group by usuario.usuario_id
 HAVING GGR > 0
LIMIT 0,1000

) ggr on usuario_mandante.usumandante_id = ggr.usumandante_id


where usuario_automation.usuautomation_id is NULL
     and subproveedor.tipo = 'CASINO'
  and usucasino_detalle_resumen.tipo IN ('DEBIT', 'DEBITFREECASH')
  and usuario.mandante = 0
  and usuario.pais_id = 173 ";

        $sql = $sql . ' and usucasino_detalle_resumen.fecha_crea >= ' . "'" . date("Y-m-d 00:00:00", strtotime('-7 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id HAVING valorTotal >= 100 ORDER BY data_completa2.ultimo_inicio_sesion DESC  LIMIT 0,1000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Gana ya! disfruta de los Golden Days de Isoftbet, apuesta desde S/.100 en las tragamonedas y recibe 10 giros gratis. Aplica tyc';


        $linkGlobal = 'https://doradobet.com/new-casino/proveedor/ISOFTBET?utm_source=Intico&utm_medium=SMS&utm_campaign=Golden_Days_Isoftbet_Aut_PE_Agosto&utm_term=Casino&utm_content=Golden_Days_Isoftbet_Aut_PE_Agosto';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('90');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


//DORADOBET PERU
// 12. Bono Casino Dorado
    if ((
            strpos(date("Y-m-d"), '2022-07') !== false

        ) && date('G') == 12  && (date('N')=='6' || date('N')=='7') && false
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id, registro.celular, usuario_mandante.usumandante_id
from data_completa2
         inner join usuario on usuario.usuario_id = data_completa2.usuario_id
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 92


where usuario_automation.usuautomation_id is NULL
  and usuario.mandante = 0
  and usuario.pais_id = 173 ";

        $sql = $sql . ' and data_completa2.fecha_ultima_apuestadeportivas >= ' . "'" . date("Y-m-d 00:00:00", strtotime('-7 days')) . "'";
        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino < ' . "'" . date("Y-m-d 00:00:00", strtotime('-7 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id  ORDER BY data_completa2.ultimo_inicio_sesion DESC  LIMIT 0,5000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Disfruta del Bono Casino Dorado! Recarga este fin de semana y recibe un bono del 30% para que ganes en el casino. Aplica tyc';


        $linkGlobal = 'https://doradobet.com/apuestas?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Casino_Dorado_Aut_PE_Julio&utm_term=Tragamonedas&utm_content=Bono_Casino_Dorado_Aut_PE_Julio';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('92');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


//DORADOBET PERU -- INACTIVADO
// 13. Solicitud de Automatización Doradobet PERÚ / Sábados de Aviator
    if ((
            strpos(date("Y-m-d"), '2022-06') !== false

        ) && date('G') == 10  && (date('N')=='6') && false
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "
        select usuario.usuario_id,
       registro.celular,
       usuario_mandante.usumandante_id
from  data_completa2

        LEFT OUTER JOIN (select data_completa2.usuario_id
                         from data_completa2

                                  LEFT OUTER JOIN usucasino_detalle_resumen
                                                  on data_completa2.usuario_id = usucasino_detalle_resumen.usuario_id

                                  LEFT OUTER JOIN producto_mandante on producto_mandante.prodmandante_id =
                                                                       usucasino_detalle_resumen.producto_id
                                  LEFT OUTER JOIN producto on producto_mandante.producto_id = producto.producto_id
                                  LEFT OUTER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id



                         where subproveedor.proveedor_id = 170
                           and usucasino_detalle_resumen.tipo IS NOT NULL
                           and usucasino_detalle_resumen.fecha_crea >= '" . date("Y-m-01 00:00:00") . "'

                         group by data_completa2.usuario_id) a on a.usuario_id = data_completa2.usuario_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = data_completa2.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 95


where usuario_automation.usuautomation_id is NULL
     and a.usuario_id IS NULL
  and usuario.mandante = 0
  and usuario.pais_id = 173 ";

        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino >= ' . "'" . date("Y-m-01 00:00:00") . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY data_completa2.ultimo_inicio_sesion DESC  LIMIT 0,1000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Sabados de Aviator! Disfruta del mejor casino y te devolvemos lo que juegues en giros gratis para que sigas ganando. Aplica tyc';


        $linkGlobal = 'https://doradobet.com/new-casino/proveedor/SPRIBE?utm_source=Intico&utm_medium=SMS&utm_campaign=Sabados_De_Aviator_Aut_PE_Junio&utm_term=Casino&utm_content=Sabados_De_Aviator_Aut_PE_Junio';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('95');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

    //DORADOBET PERU
// 14. Lunes de Safetypay
    if ((strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false  || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false )
        && date('G') == 11  && (date('N')=='1')
    ) {

        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id
              AND usuario_recarga.fecha_crea >= '" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'
        AND usuario_recarga.fecha_crea <=  '" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'
        
             )
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Hoy es Lunes Safetypay. Recarga tu cuenta desde S/.50 y recibe 10 giros gratis para que ganes con las tragamonedas.  Aplica tyc';

        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Lunes_Safetypay_Aut_PE_Agosto&utm_term=Recarga&utm_content=Lunes_Safetypay_Aut_PE_Agosto';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' ';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' ';
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('98');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA3) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


    //DORADOBET PERU
// 14. Martes de Visa
    if ((strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false  || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false )
        && date('G') == 11 && (date('N')=='2')
    ) {

        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id
              AND usuario_recarga.fecha_crea >= '" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'
        AND usuario_recarga.fecha_crea <=  '" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'
        
             )
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Hoy es Martes de Visa. Recarga tu cuenta desde S/.100 y recibe 20 giros gratis para que ganes con las tragamonedas. Aplica tyc';

        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Martes_De_Visa_Aut_PE_Agosto&utm_term=Recarga&utm_content=Martes_De_Visa_Aut_PE_Agosto';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' ';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' ';
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('101');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA3) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

    //DORADOBET PERU
// 15. Jueves deportivo Pago efectivo
    if ((strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false  || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false )
        && date('G') == 11 && (date('N')=='4')
    ) {

        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id
              AND usuario_recarga.fecha_crea >= '" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'
        AND usuario_recarga.fecha_crea <=  '" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'
        
             )
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. Jueves de Pago Efectivo. Recarga tu cuenta desde S/.30 y recibe un freebet de S/.5 para que ganes ya.  Aplica tyc';

        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Jueves_Deportivo_Pagoefectivo_Aut_PE_Agosto&utm_term=Recarga&utm_content=Jueves_Deportivo_Pagoefectivo_Aut_PE_Agosto';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' ';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' ';
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('104');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA3) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

    //DORADOBET PERU
// 16. Viernes 2X1 de Payvalida y Astropay
    if ((strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false )
        && date('G') == 11 && (date('N')=='5')
    ) {

        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id
              AND usuario_recarga.fecha_crea >= '" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'
        AND usuario_recarga.fecha_crea <=  '" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'
        
             )
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 0 and usuario.pais_id = 173
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Doradobet. No te pierdas el viernes 2x1! Recarga hoy por Payvalida o Astropay y recibe un freebet de S/.2 y 20 giros gratis. Aplica tyc';

        $linkGlobal = 'https://doradobet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Viernes_2x1_De_Payvalida_Astropay_Aut_PE_Agosto&utm_term=Recarga&utm_content=Viernes_2x1_De_Payvalida_Astropay_Aut_PE_Agosto';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' ';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' ';
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('107');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '0', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA3) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }




//ECUABET
// 1. Bono Primer Deposito Ecuador
    if (strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false
    ) {
        $message = "*CRON: INICIO (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_recarga on usuario.usuario_id = usuario_recarga.usuario_id
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-2 hour')) . "'";
        $sql = $sql . ' AND usuario.fecha_crea <= ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,10000  ';
        print_r(date("Y-m-d H:00:00", strtotime('-2 hour')));
        print_r(date("Y-m-d H:00:00", strtotime('-1 hour')));

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Ecuabet. Que esperas! Haz tu primer deposito de $10 o mas y recibe bono del 100% hasta USD250 para que tu pasion sea oro. Aplica TyC';

        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Primer_Deposito_Aut_ECU_Agosto2022&utm_term=Pronosticos&utm_content=Bono_Primer_Deposito_Aut_ECU_Agosto2022';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' https://bit.ly/3qEEC6A';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' https://bit.ly/3MrrPwK';
        }
        print_r($returnData);

        print_r($messagesms);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            // $envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('49');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//ECUABET
// 2. Solicitudes de primer deposito Ecuador
    if (strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false
    ) {

        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id)
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-1 hour')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d H:59:59", strtotime('-1 hour')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. No olvides recargar tu cuenta de Ecuabet.com y aprovecha las increibles promos que tenemos para ti! Aqui nunca dejas de ganar';

        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Recordatorio_Bono_Primer_Deposito_Aut_ECU_Agosto2022&utm_term=Pronosticos&utm_content=Recordatorio_Bono_Primer_Deposito_Aut_ECU_Agosto2022';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' ';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' ';
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('52');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA3) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


//ECUABET
// 3. Solicitud Automatización promociones de la semana Lunes y Miercoles a las 11AM
    if ((
            strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false

        ) && date('G') == 11 && (date('N') == 3 || date('N') == 1)
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id and
                                             usuario_recarga.fecha_crea > '" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'
    and usuario_recarga.fecha_crea < '" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "')
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'";

        $sql = $sql . ' ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);

        $messagesms = 'Ecuabet. Recarga desde $20 por Facilito, Western Union, Bemovil, Pagament y Bakan y recibe el 10% adicional para que ganes ya. Aplica tyc';

        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Redes_Aliadas_Aut_ECU_Agosto2022&utm_term=Bonos&utm_content=Bono_Redes_Aliadas_Aut_ECU_Agosto2022';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('55');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//ECUABET
// 5. Saldo Billetera Ecuabet A LA 1PM
    if ((
            strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false

        ) && date('G') == 13
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,
       registro.celular,
       usuario_mandante.usumandante_id,
       registro.creditos + registro.creditos_base saldo
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 60


where usuario_automation.usuautomation_id is NULL
  and usuario.mandante = 8
  and (registro.creditos + registro.creditos_base) > 5

order by saldo desc
LIMIT 0,1000
 ";


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. Disfruta de tu saldo! Juega en el casino y pronostica a tus deportes favoritos para que multipliques tus ganancias. Gana ya!';


        $linkGlobal = 'https://ecuabet.com/new-casino/proveedor/PLAYNGO?utm_source=Intico&utm_medium=SMS&utm_campaign=Premios_Misteriosos_Aut_ECU_Agosto2022&utm_term=Tragamonedas&utm_content=Premios_Misteriosos_Aut_ECU_Agosto2022';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('60');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }

//ECUABET
// 7. Automatización Recordatorio Primer Deposito
    if ((
            strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false || strpos(date("Y-m-d"), '2022-08') !== false

        ) && date('G') == 12
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id)
where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8 ";

        $sql = $sql . ' AND usuario.fecha_crea > ' . "'" . date("Y-m-d H:00:00", strtotime('-7 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id ASC LIMIT 0,5000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


            $messagesms = 'Ecuabet. Es momento de empezar a ganar! Realiza tu primer deposito y recibe el 100% hasta $250. convierte tu pasion en oro. Aplica tyc';


        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Record_Primer_Deposito_Sem_Aut_ECU_Agosto2022&utm_term=Pronosticos&utm_content=Record_Primer_Deposito_Sem_Aut_ECU_Agost2022';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('66');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }



//ECUABET
// 8. Jueves de Casino con Bwise
    if ((strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false
        )  && date('G') == 11 && date('N') == 4 && false
    ) {

        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id and
                                             usuario_recarga.fecha_crea > '" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'
    and usuario_recarga.fecha_crea < '" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "')

where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. Gana 20 giros gratis! Recarga desde $20 por Bakan y disfruta de lo mejor de nuestras tragamonedas . Aplica tyc';

        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Jueves_De_Casino_Aut_ECU_Julio&utm_term=Recarga&utm_content=Jueves_De_Casino_Aut_ECU_Julio';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' ';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' ';
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('108');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA3) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


//ECUABET
// 9. Martes Deportivo de Pago Efectivo
    if ((strpos(date("Y-m-d"), '2022-04') !== false || strpos(date("Y-m-d"), '2022-05') !== false || strpos(date("Y-m-d"), '2022-06') !== false || strpos(date("Y-m-d"), '2022-07') !== false
        )  && date('G') == 11 && date('N') == 2 && false
    ) {

        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from usuario
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
             inner join transaccion_producto on usuario.usuario_id = transaccion_producto.usuario_id
    
         left outer join usuario_recarga on (usuario.usuario_id = usuario_recarga.usuario_id and
                                             usuario_recarga.fecha_crea > '" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'
    and usuario_recarga.fecha_crea < '" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "')

where ( usuario_recarga.recarga_id is null)
  and usuario.mandante = 8
                and perfil_id = 'USUONLINE' ";

        $sql = $sql . ' AND transaccion_producto.fecha_crea > ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 day')) . "'";
        $sql = $sql . ' AND transaccion_producto.fecha_crea <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-1 day')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY usuario.usuario_id DESC LIMIT 0,5000 ';

        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. Disfruta el Martes Deportivo! Recarga hoy desde $20 por Pago Efectivo y recibe un freebet de $2 para que sigas ganando. Aplica tyc';

        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Martes_Deportivo_Aut_ECU_Julio&utm_term=Recarga&utm_content=Martes_Deportivo_Aut_ECU_Julio';
        $messagesms = $messagesms . ' {var16}';
        if (strpos(date("Y-m-d"), '2022-04') !== false) {
            // $messagesms.= ' ';
        }
        if (strpos(date("Y-m-d"), '2022-05') !== false) {
            // $messagesms.= ' ';
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('111');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }

        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);


        $message = "*CRON: FIN (CAMPANA3) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }



//ECUABET
// 10. Bono Lucky Gold
    if ((
            strpos(date("Y-m-d"), '2022-07') !== false

        ) && date('G') == 12  && (date('N')=='5' || date('N')=='7') && false
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id, registro.celular, usuario_mandante.usumandante_id
from data_completa2
         inner join usuario on usuario.usuario_id = data_completa2.usuario_id
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 114


where usuario_automation.usuautomation_id is NULL
  and usuario.mandante = 8 ";

        $sql = $sql . ' and data_completa2.fecha_ultima_apuestadeportivas >= ' . "'" . date("Y-m-d 00:00:00", strtotime('-7 days')) . "'";
        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino < ' . "'" . date("Y-m-d 00:00:00", strtotime('-7 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id  ORDER BY data_completa2.ultimo_inicio_sesion DESC  LIMIT 0,5000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. Recarga de viernes a domingo de junio y recibe un bono del 80% hasta $100 para que ganes con lo mejor del casino. Aplica tyc';


        $linkGlobal = 'https://ecuabet.com/deportes?utm_source=Intico&utm_medium=SMS&utm_campaign=Bono_Lucky_Gold_Aut_ECU_Julio&utm_term=Bonos&utm_content=Bono_Lucky_Gold_Aut_ECU_Julio';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('114');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }



//ECUABET
// 11. Cashback Golden Spins
    if ((
            strpos(date("Y-m-d"), '2022-06') !== false

        ) && date('G') == 12 && (date('N')=='6' || date('N')=='3') && false
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id,
       registro.celular,
       usuario_mandante.usumandante_id,
       SUM(usucasino_detalle_resumen.valor) valorTotal
from usucasino_detalle_resumen
         inner join producto_mandante on producto_mandante.prodmandante_id = usucasino_detalle_resumen.producto_id
         inner join producto on producto_mandante.producto_id = producto.producto_id
         inner join subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = usucasino_detalle_resumen.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
         left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 117
         inner join data_completa2 on data_completa2.usuario_id = usuario_mandante.usumandante_id


where usuario_automation.usuautomation_id is NULL
     and subproveedor.tipo = 'CASINO'
  and usucasino_detalle_resumen.tipo IN ('DEBIT', 'DEBITFREECASH')
  and usuario.mandante = 8
  and usuario.pais_id = 66 ";

        $sql = $sql . ' and usucasino_detalle_resumen.fecha_crea >= ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 days')) . "'";
        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasinovivo < ' . "'" . date("Y-m-d 00:00:00", strtotime('-1 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id HAVING valorTotal >= 100 ORDER BY data_completa2.ultimo_inicio_sesion DESC  LIMIT 0,1500 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. En Julio juega de miercoles a domingo en nuestro casino en vivo, si no ganas te devolvemos el 40% de tus perdidas. Aplica tyc';


        $linkGlobal = 'https://ecuabet.com/live-casino-vivo?utm_source=Intico&utm_medium=SMS&utm_campaign=Cashback_Golden_Spins_Aut_ECU_Julio&utm_term=Casino&utm_content=Cashback_Golden_Spins_Aut_ECU_Julio';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('117');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }


//ECUABET
// 12. Freespin Back
    if ((
            strpos(date("Y-m-d"), '2022-07') !== false

        ) && date('G') == 14 && (date('N')=='1' || date('N')=='4') && false
    ) {
        $message = "*CRON: INICIO (CAMPANA2) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $sql = "select usuario.usuario_id,registro.celular,usuario_mandante.usumandante_id
from data_completa2
         inner join usuario on usuario.usuario_id = data_completa2.usuario_id
         inner join registro on usuario.usuario_id = registro.usuario_id
         inner join usuario_mandante on usuario_mandante.usuario_mandante = usuario.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id
        left outer join usuario_automation
                         on usuario_mandante.usumandante_id = usuario_automation.usuario_id and automation_id = 120


where usuario_automation.usuautomation_id is NULL and  usuario.mandante = 8 and usuario.pais_id = 66 ";

//        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino >= ' . "'" . date("Y-m-d 00:00:00", strtotime('-2 days')) . "'";
        $sql = $sql . ' and data_completa2.fecha_ultima_apuestacasino <= ' . "'" . date("Y-m-d 23:59:59", strtotime('-2 days')) . "'";

        $sql = $sql . ' group by usuario.usuario_id ORDER BY data_completa2.ultimo_inicio_sesion DESC LIMIT 0,1000 ';


        $usuariosSeleccionados = array();

        $BonoInterno = new BonoInterno();
        $returnData = $BonoInterno->execQuery('', $sql);


        $messagesms = 'Ecuabet. Freespinback! Juega en las tragamonedas de lunes a jueves y si no ganas, te devolvemos el 30% de tus perdidas. Aplica tyc';


        $linkGlobal = 'https://ecuabet.com/new-casino?utm_source=Intico&utm_medium=SMS&utm_campaign=Freespinback_Aut_ECU_Julio&utm_term=Tragamonedas&utm_content=Freespinback_Aut_ECU_Julio';
        $messagesms = $messagesms . ' {var16}';
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioMensajes = array();

        foreach ($returnData as $datanum) {

            $UsuarioMandante = new UsuarioMandante($datanum->{'usuario_mandante.usumandante_id'});

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($messagesms, '', $datanum->{'registro.celular'}, $UsuarioMandante->mandante, $UsuarioMandante);

            $varArray = array();
            $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
            $varArray['tophone'] = $datanum->{'registro.celular'};
            $varArray['link'] = $linkGlobal;

            array_push($UsuarioMensajes, $varArray);

            $UsuarioAutomationMySqlDAO2 = new UsuarioAutomationMySqlDAO();
            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomation->setUsuarioId($UsuarioMandante->usumandanteId);
            $UsuarioAutomation->setTipo('forever');
            $UsuarioAutomation->setValor(0);
            $UsuarioAutomation->setUsucreaId(0);
            $UsuarioAutomation->setUsumodifId(0);
            $UsuarioAutomation->setEstado('I');
            $UsuarioAutomation->setAutomationId('120');
            $UsuarioAutomation->setNivel(0);
            $UsuarioAutomation->setObservacion('');
            $UsuarioAutomation->setUsuaccionId(0);
            $UsuarioAutomation->setFechaAccion('');
            $UsuarioAutomation->setExternoId(0);

            $automationId = $UsuarioAutomationMySqlDAO2->insert($UsuarioAutomation);
            $UsuarioAutomationMySqlDAO2->getTransaction()->commit();

        }


        $envio = $ConfigurationEnvironment->EnviarMensajeTextoMasivoLink($messagesms, '', '8', $UsuarioMensajes);

        $message = "*CRON: FIN (CAMPANA) * " . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
    }



}

exit();

try {

    $message = "*CRON: (Eliminamos Ezugi RROLLBACK) * " . " - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    $rules = [];
    array_push($rules, array("field" => "transaccion_api.tipo", "data" => "RROLLBACK", "op" => "eq"));
    array_push($rules, array("field" => "transaccion_api.proveedor_id", "data" => "12", "op" => "eq"));
    array_push($rules, array("field" => "(transaccion_api.fecha_crea)", "data" => date("Y-m-d H:00:00", strtotime('-1 hours')), "op" => "ge"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $select = "transaccion_api.*";
    $grouping = "";


    $TransaccionApiMandante = new TransaccionApi();
    $data = $TransaccionApiMandante->getTransaccionesCustom($select, "transaccion_api.transapi_id", "asc", 0, 1000, $json, true, $grouping);
    $data = json_decode($data);

    $procesadas = array();
    foreach ($data->data as $key => $value) {
        try {
            if (!in_array($value->{'transaccion_api.identificador'}, $procesadas)) {
                array_push($procesadas, $value->{'transaccion_api.identificador'});
                $TransaccionJuego = new TransaccionJuego("", $value->{'transaccion_api.identificador'});
                if ($TransaccionJuego->getEstado() == "A") {


                    $rules = [];
                    array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $select = "transjuego_log.*";
                    $grouping = "transjuego_log.transjuegolog_id";


                    $TransjuegoLog = new TransjuegoLog();
                    $data = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
                    $data = json_decode($data);


                    if (oldCount($data->data) == 1) {
                        $value = $data->data[0];
                        if (strpos($value->{"transjuego_log.tipo"}, "DEBIT") !== false) {

                            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


                            //  Creamos el log de la transaccion juego para auditoria
                            $TransjuegoLog2 = new TransjuegoLog();
                            $TransjuegoLog2->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                            $TransjuegoLog2->setTransaccionId("ROLLBACK" . $value->{'transjuego_log.transaccion_id'});
                            $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
                            $TransjuegoLog2->setTValue(json_encode(array()));
                            $TransjuegoLog2->setUsucreaId(0);
                            $TransjuegoLog2->setUsumodifId(0);
                            $TransjuegoLog2->setValor($value->{'transjuego_log.valor'});

                            $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


                            $TransaccionJuego->setValorPremio($value->{'transjuego_log.valor'});
                            $TransaccionJuego->setEstado('I');

                            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $Usuario->creditWin($value->{'transjuego_log.valor'}, $TransjuegoLogMySqlDAO->getTransaction());

                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('C');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(30);
                            $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
                            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                            $TransaccionJuegoMySqlDAO->update($TransaccionJuego);


                            $TransjuegoLogMySqlDAO->getTransaction()->commit();


                        }
                    }
                }

            }
        } catch (Exception $e) {

        }
    }


} catch (Exception $e) {

}


exit();
try {

    $message = "*CRON: (Eliminamos Ezugi RROLLBACK) * " . " - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    $rules = [];
    array_push($rules, array("field" => "transaccion_api.tipo", "data" => "RROLLBACK", "op" => "eq"));
    array_push($rules, array("field" => "transaccion_api.proveedor_id", "data" => "12", "op" => "eq"));
    array_push($rules, array("field" => "(transaccion_api.fecha_crea)", "data" => date("Y-m-d H:00:00", strtotime('-1 hours')), "op" => "ge"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $select = "transaccion_api.*";
    $grouping = "";


    $TransaccionApiMandante = new TransaccionApi();
    $data = $TransaccionApiMandante->getTransaccionesCustom($select, "transaccion_api.transapi_id", "asc", 0, 1000, $json, true, $grouping);
    $data = json_decode($data);

    $procesadas = array();
    foreach ($data->data as $key => $value) {
        try {
            if (!in_array($value->{'transaccion_api.identificador'}, $procesadas)) {
                array_push($procesadas, $value->{'transaccion_api.identificador'});
                $TransaccionJuego = new TransaccionJuego("", $value->{'transaccion_api.identificador'});
                if ($TransaccionJuego->getEstado() == "A") {


                    $rules = [];
                    array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $select = "transjuego_log.*";
                    $grouping = "transjuego_log.transjuegolog_id";


                    $TransjuegoLog = new TransjuegoLog();
                    $data = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
                    $data = json_decode($data);


                    if (oldCount($data->data) == 1) {
                        $value = $data->data[0];
                        if (strpos($value->{"transjuego_log.tipo"}, "DEBIT") !== false) {

                            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


                            //  Creamos el log de la transaccion juego para auditoria
                            $TransjuegoLog2 = new TransjuegoLog();
                            $TransjuegoLog2->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                            $TransjuegoLog2->setTransaccionId("ROLLBACK" . $value->{'transjuego_log.transaccion_id'});
                            $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
                            $TransjuegoLog2->setTValue(json_encode(array()));
                            $TransjuegoLog2->setUsucreaId(0);
                            $TransjuegoLog2->setUsumodifId(0);
                            $TransjuegoLog2->setValor($value->{'transjuego_log.valor'});

                            $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


                            $TransaccionJuego->setValorPremio($value->{'transjuego_log.valor'});
                            $TransaccionJuego->setEstado('I');

                            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $Usuario->creditWin($value->{'transjuego_log.valor'}, $TransjuegoLogMySqlDAO->getTransaction());

                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('C');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(30);
                            $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
                            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                            $TransaccionJuegoMySqlDAO->update($TransaccionJuego);


                            $TransjuegoLogMySqlDAO->getTransaction()->commit();


                        }
                    }
                }

            }
        } catch (Exception $e) {

        }
    }


} catch (Exception $e) {

}


$message = "*CRON: (Analisis) * " . " - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime('0 days'));
$fecha1 = date("Y-m-d H:i:s", strtotime('-1 hour'));
$fecha2 = date("Y-m-d H:i:s", strtotime('0 hour'));

$usuario = "";

if ($_REQUEST["diaSpc"] != "" && $_REQUEST["diaSpc2"] != "") {


    $fechaSoloDia = date("Y-m-d", strtotime($_REQUEST["diaSpc"]));
    $fecha1 = date("Y-m-d H:i:s", strtotime($_REQUEST["diaSpc"]));
    $fecha2 = date("Y-m-d H:i:s", strtotime($_REQUEST["diaSpc2"]));

    exit();
} else {
    $arg1 = $argv[1];
    $arg2 = $argv[2];
    $arg3 = $argv[3];
    if ($arg1 != "" && $arg2 != "") {
        $fechaSoloDia = date("Y-m-d", strtotime($arg1));
        $fecha1 = date("Y-m-d H:i:s", strtotime($arg1));
        $fecha2 = date("Y-m-d H:i:s", strtotime($arg2));

        if ($arg3 != '') {
            $usuario = " AND usuario.usuario_id='" . $arg3 . "' ";
        }

    } else {
        //exit();
    }

}
try {


    /* OBTENER TODOS LOS USUARIOS EN LA FECHA A ANALIZAR*/
    $sqlUsuariosTabla = "    
 select usuario_historial.usuario_id from usuario_historial  INNER JOIN usuario ON (usuario_historial.usuario_id = usuario.usuario_id)   INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
 where usuario.mandante not in (1,2) and usuario_perfil.perfil_id IN ('USUONLINE','PUNTOVENTA') and usuario_historial.fecha_crea >= '" . $fecha1 . "' " . $usuario . " and usuario_historial.fecha_crea <= '" . $fecha2 . "' group by usuario_historial.usuario_id
";

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia Analisis: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . $UsuarioSaldoFinal . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $paso = true;

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $resultados = array();


    $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlUsuariosTabla);


    foreach ($dataSaldoInicial as $datanum) {

        print_r($datanum);

        $sqlUsuariosTablaDetalle = " 
 select * from usuario_historial 
 where fecha_crea >= '" . $fecha1 . "' and fecha_crea <= '" . $fecha2 . "' AND usuario_id = '" . $datanum->{'usuario_historial.usuario_id'} . "'";


        $dataSaldoInicialDetalle = $BonoInterno->execQuery($transaccion, $sqlUsuariosTablaDetalle);

        $cont = 0;
        $valor = 0;
        $saldoCreditosBaseInicial = 0;
        $saldoCreditosInicial = 0;


        foreach ($dataSaldoInicialDetalle as $datanum2) {

            if ($cont > 0) {

                if ($datanum2->{'usuario_historial.movimiento'} == 'E') {

                    $rest = (($saldoCreditosInicial + $saldoCreditosBaseInicial + floatval($datanum2->{'usuario_historial.valor'})) - (floatval($datanum2->{'usuario_historial.creditos'}) + floatval($datanum2->{'usuario_historial.creditos_base'})));
                    if (abs($rest) > 0.1) {
                        $resultado = ' Error: *Diferencia:*' . $rest . ' *ID:*' . $datanum2->{'usuario_historial.usuhistorial_id'};
                        array_push($resultados, $resultado);
                    }
                }
                if ($datanum2->{'usuario_historial.movimiento'} == 'S') {
                    $rest = (($saldoCreditosInicial + $saldoCreditosBaseInicial - floatval($datanum2->{'usuario_historial.valor'})) - (floatval($datanum2->{'usuario_historial.creditos'}) + floatval($datanum2->{'usuario_historial.creditos_base'})));
                    if (abs($rest) > 0.1) {
                        $resultado = ' Error: *Diferencia:*' . $rest . ' *ID:*' . $datanum2->{'usuario_historial.usuhistorial_id'};
                        array_push($resultados, $resultado);
                    }
                }


            }

            $saldoCreditosInicial = floatval($datanum2->{'usuario_historial.creditos'});
            $saldoCreditosBaseInicial = floatval($datanum2->{'usuario_historial.creditos_base'});
            $valor = floatval($datanum2->{'usuario_historial.valor'});
            $cont++;

        }

    }


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $cont = 0;
    foreach ($resultados as $item) {
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $item . "' '#virtualsoft-cron' > /dev/null & ");
        $cont++;
        if ($cont == oldCount($resultados)) {
            $message = "*CRON: (Fin) * " . " Fin Analisis - Fecha: " . date("Y-m-d H:i:s");
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
        sleep(0.2);

    }
    if (oldCount($resultados) == 0) {

        $message = "*CRON: (Fin) * " . " Fin Analisis - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
    }

    try {

        $message = "*CRON: (Eliminamos Ezugi RROLLBACK) * " . " - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        $rules = [];
        array_push($rules, array("field" => "transaccion_api.tipo", "data" => "RROLLBACK", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_api.proveedor_id", "data" => "12", "op" => "eq"));
        array_push($rules, array("field" => "(transaccion_api.fecha_crea)", "data" => date("Y-m-d H:00:00", strtotime('-1 hours')), "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $select = "transaccion_api.*";
        $grouping = "";


        $TransaccionApiMandante = new TransaccionApi();
        $data = $TransaccionApiMandante->getTransaccionesCustom($select, "transaccion_api.transapi_id", "asc", 0, 1000, $json, true, $grouping);
        $data = json_decode($data);

        $procesadas = array();
        foreach ($data->data as $key => $value) {
            try {
                if (!in_array($value->{'transaccion_api.identificador'}, $procesadas)) {
                    array_push($procesadas, $value->{'transaccion_api.identificador'});
                    $TransaccionJuego = new TransaccionJuego("", $value->{'transaccion_api.identificador'});
                    if ($TransaccionJuego->getEstado() == "A") {


                        $rules = [];
                        array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $select = "transjuego_log.*";
                        $grouping = "transjuego_log.transjuegolog_id";


                        $TransjuegoLog = new TransjuegoLog();
                        $data = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
                        $data = json_decode($data);


                        if (oldCount($data->data) == 1) {
                            $value = $data->data[0];
                            if (strpos($value->{"transjuego_log.tipo"}, "DEBIT") !== false) {

                                $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


                                //  Creamos el log de la transaccion juego para auditoria
                                $TransjuegoLog2 = new TransjuegoLog();
                                $TransjuegoLog2->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                                $TransjuegoLog2->setTransaccionId("ROLLBACK" . $value->{'transjuego_log.transaccion_id'});
                                $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
                                $TransjuegoLog2->setTValue(json_encode(array()));
                                $TransjuegoLog2->setUsucreaId(0);
                                $TransjuegoLog2->setUsumodifId(0);
                                $TransjuegoLog2->setValor($value->{'transjuego_log.valor'});

                                $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


                                $TransaccionJuego->setValorPremio($value->{'transjuego_log.valor'});
                                $TransaccionJuego->setEstado('I');

                                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                $Usuario->creditWin($value->{'transjuego_log.valor'}, $TransjuegoLogMySqlDAO->getTransaction());

                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('C');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(30);
                                $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
                                $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                                $TransaccionJuegoMySqlDAO->update($TransaccionJuego);


                                $TransjuegoLogMySqlDAO->getTransaction()->commit();


                            }
                        }
                    }

                }
            } catch (Exception $e) {

            }
        }


    } catch (Exception $e) {

    }

} catch (Exception $e) {
    print_r($e);
    /*    $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


        $message = "*CRON: (ERROR) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");*/

}





