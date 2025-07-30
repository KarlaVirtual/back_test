<?php

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioLog;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\sql\Transaction;
use Backend\dto\Usuario;
use Backend\dto\ItTicketEnc;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;



/**
 * Clase 'CronJobUsuariosQueNoSeHanLogueado'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobUsuariosQueNoSeHanLogueado
{


    public function __construct()
    {
    }

    public function execute()
    {


        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();


//----------------------------------------------------------

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $sql = "SELECT u.usuario_id, d.ultimo_inicio_sesion fecha_ult, usuario_perfil.perfil_id
FROM usuario u
         INNER JOIN usuario_perfil ON (u.usuario_id = usuario_perfil.usuario_id)
         inner join usuario_mandante on usuario_mandante.usuario_mandante = u.usuario_id
         inner join data_completa2 d on usuario_mandante.usumandante_id = d.usuario_id
WHERE d.ultimo_inicio_sesion <= DATE_SUB(NOW(), INTERVAL 20 DAY) and u.eliminado='N' and u.estado='A'
  AND usuario_perfil.perfil_id NOT IN ('USUONLINE', 'AFILIADOR')";

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();

        $total = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($total as $value) {
            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($UsuarioMySqlDAO->getTransaction());
            $IdUsuario = $value->{"u.usuario_id"};
            $fechaUltimaActualizacion = $value->{'u.fecha_ult'};

            $Usuario = new Usuario($IdUsuario);
            $Usuario->setEstado("I");
            $Usuario->contingencia = "A";


            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp('');

            $UsuarioLog->setTipo("ESTADOUSUARIOAUTOMATICO");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes("A");
            $UsuarioLog->setValorDespues('I');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);


            $UsuarioLogMySqlDAO->insert($UsuarioLog);

            $UsuarioMySqlDAO->update($Usuario);
            $UsuarioMySqlDAO->getTransaction()->commit();

        }


    }
}