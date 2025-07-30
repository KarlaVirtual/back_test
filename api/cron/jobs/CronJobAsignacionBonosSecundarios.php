<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author  Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access  public
 * @see     no
 * @date    18.10.17 *
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioSession;
use Backend\dto\BonoLog;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\PreUsuarioTorneo;
use Backend\dto\TorneoDetalle;
use Backend\dto\CategoriaProducto;
use Backend\dto\TorneoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\TransjuegoInfo;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioTorneo;
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\PreUsuarioTorneoMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;
use Backend\websocket\WebsocketUsuario;

use Backend\dao\TransjuegoInfoDAO;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Exception;


/**
 * Clase 'CronJobAsignacionBonosSecundarios'
 *
 * Ejemplo de uso:
 *
 * @package ninguno
 * @author  Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access  public
 * @see     no *
 */
class CronJobAsignacionBonosSecundarios
{


    public function __construct()
    {
    }

    /**
     * Método que se ejecuta para asignar bonos secundarios a los usuarios.
     *
     * Este método verifica si hay un proceso en ejecución, actualiza la fecha del último proceso,
     * y asigna bonos secundarios a los usuarios según ciertas reglas.
     *
     * @throws Exception Si ya hay un proceso en ejecución.
     */
    public function execute()
    {
        $message = "*CRON: (cronTorneos) * " . " - Fecha: " . date("Y-m-d H:i:s");

        $responseEnable = file_get_contents(__DIR__ . '/../../logSit/enabled');

        if ($responseEnable == 'BLOCKED') {
            exit();
        }

        $argv1 = $argv[1];
        $filename = __DIR__ . '/lastrunCronBonosSecundarios';
        $datefilename = date("Y-m-d H:i:s", filemtime($filename));

        if ($datefilename <= date("Y-m-d H:i:s", strtotime('-12 hour'))) {
            unlink($filename);
        }
        if (file_exists($filename)) {
            throw new Exception("There is a process currently running", "1");
            exit();
        }
        $BonoInterno = new BonoInterno();

        $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='ASIGNACIONBONOS'
";


        $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
        $data = $data[0];

        $line = $data->{'proceso_interno2.fecha_ultima'};

        if ($line == '') {
            exit();
        }


        $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
        $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));


        if ($fechaL2 >= date('Y-m-d H:i:00', strtotime('-5 minute'))) {
            unlink($filename);
            exit();
        }


        file_put_contents($filename, 'RUN');


        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='ASIGNACIONBONOS';
";


        $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
        $transaccion->commit();


        $rules = [];

        $debug = false;

        $BonoInterno = new BonoInterno();


        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if (true) {
            $sql = "

select usuario_bono.usuario_id,
       usuario_bono.bono_id,
       case
           when usuario_bono.bono_id = 69237 then 69238
           when usuario_bono.bono_id = 65939 then 65949
           when usuario_bono.bono_id = 52658 then 65944
           when usuario_bono.bono_id = 57832 then 58837
           when usuario_bono.bono_id = 76620 then 76619
           when usuario_bono.bono_id = 57562 then 58838
           when usuario_bono.bono_id = 59299 then 52711
           when usuario_bono.bono_id = 50310 then 50340
           when usuario_bono.bono_id = 46195 then 46187
           when usuario_bono.bono_id = 46185 then 46188
           when usuario_bono.bono_id = 46053 then 59859
           when usuario_bono.bono_id = 42481 then 42479
           when usuario_bono.bono_id = 45741 then 45743
           when usuario_bono.bono_id = 45742 then 45744
           when usuario_bono.bono_id = 45481 then 45483
           when usuario_bono.bono_id = 45482 then 45484
           when usuario_bono.bono_id = 63955 then 63956
                when usuario_bono.bono_id = 67550 then 67548
                when usuario_bono.bono_id = 70248 then 70252

           when usuario_bono.bono_id = 43037 then 43038

           when usuario_bono.bono_id = 43187 then 53257
           when usuario_bono.bono_id = 62181 then 62223

           when usuario_bono.bono_id = 44849 then 44852

           when usuario_bono.bono_id = 45208 then 45212
           when usuario_bono.bono_id = 45209 then 45213

           when usuario_bono.bono_id = 44848 then 44850

           when usuario_bono.bono_id = 28996 then 53256

           when usuario_bono.bono_id = 44327 then 53254

           when usuario_bono.bono_id = 43017 then 42964

           when usuario_bono.bono_id = 44599 then 44602
           when usuario_bono.bono_id = 44600 then 44603

           when usuario_bono.bono_id = 43002 then 42805
           when usuario_bono.bono_id = 43207 then 65221
           when usuario_bono.bono_id = 42647 then 42479
           when usuario_bono.bono_id = 8157 then 20354
           when usuario_bono.bono_id = 42900 then 42940
           when usuario_bono.bono_id = 42979 then 42980
           when usuario_bono.bono_id = 12001 then 20357
           when usuario_bono.bono_id = 18615 then 21820
           when usuario_bono.bono_id = 17045 then 21823
           when usuario_bono.bono_id = 1347 then 23901
           when usuario_bono.bono_id = 64000 then 63986
           when usuario_bono.bono_id = 64259 then 64261
           when usuario_bono.bono_id = 68540 then 68539
           when usuario_bono.bono_id = 71459 then 71455
           when usuario_bono.bono_id = 72490 then 72491
           when usuario_bono.bono_id = 72486 then 72487
           when usuario_bono.bono_id = 72505 then 72507
           when usuario_bono.bono_id = 72522 then 72527
           when usuario_bono.bono_id = 74140 then 74141
           when usuario_bono.bono_id = 75491 then 75483
           when usuario_bono.bono_id = 74295 then 74297
           
           
           
           end bonoId2
from usuario_bono
         left outer join usuario_bono u2 on
            u2.bono_id =
            case
                when usuario_bono.bono_id = 69237 then 69238
                when usuario_bono.bono_id = 65939 then 65949
                when usuario_bono.bono_id = 52658 then 65944
                when usuario_bono.bono_id = 57832 then 58837
                when usuario_bono.bono_id = 76620 then 76619
                when usuario_bono.bono_id = 57562 then 58838
                when usuario_bono.bono_id = 59299 then 52711
                when usuario_bono.bono_id = 50310 then 50340
                when usuario_bono.bono_id = 46195 then 46187
                when usuario_bono.bono_id = 46185 then 46188
                when usuario_bono.bono_id = 46053 then 59859
                when usuario_bono.bono_id = 42481 then 42479
                when usuario_bono.bono_id = 45741 then 45743
                when usuario_bono.bono_id = 45742 then 45744
                when usuario_bono.bono_id = 45481 then 45483
                when usuario_bono.bono_id = 45482 then 45484
                when usuario_bono.bono_id = 63955 then 63956
                when usuario_bono.bono_id = 67550 then 67548
                when usuario_bono.bono_id = 70248 then 70252

                when usuario_bono.bono_id = 43037 then 43038

                when usuario_bono.bono_id = 43187 then 53257
                when usuario_bono.bono_id = 62181 then 62223

                when usuario_bono.bono_id = 44849 then 44852

                when usuario_bono.bono_id = 45208 then 45212
                when usuario_bono.bono_id = 45209 then 45213

                when usuario_bono.bono_id = 44848 then 44850

                when usuario_bono.bono_id = 28996 then 53256

                when usuario_bono.bono_id = 44327 then 53254

                when usuario_bono.bono_id = 43017 then 42964

                when usuario_bono.bono_id = 44599 then 44602
                when usuario_bono.bono_id = 44600 then 44603

                when usuario_bono.bono_id = 43002 then 42805
                when usuario_bono.bono_id = 43207 then 65221
                when usuario_bono.bono_id = 42647 then 42479
                when usuario_bono.bono_id = 8157 then 20354
                when usuario_bono.bono_id = 42900 then 42940
                when usuario_bono.bono_id = 42979 then 42980
                when usuario_bono.bono_id = 12001 then 20357
                when usuario_bono.bono_id = 18615 then 21820
                when usuario_bono.bono_id = 17045 then 21823
                when usuario_bono.bono_id = 1347 then 23901
                when usuario_bono.bono_id = 64000 then 63986
                when usuario_bono.bono_id = 64259 then 64261
                when usuario_bono.bono_id = 68540 then 68539
                when usuario_bono.bono_id = 71459 then 71455
           when usuario_bono.bono_id = 72490 then 72491
           when usuario_bono.bono_id = 72486 then 72487
           when usuario_bono.bono_id = 72505 then 72507
           when usuario_bono.bono_id = 72522 then 72527
           when usuario_bono.bono_id = 74140 then 74141
           when usuario_bono.bono_id = 75491 then 75483
           when usuario_bono.bono_id = 74295 then 74297

                end
        and u2.usuario_id = usuario_bono.usuario_id
where usuario_bono.bono_id in
      (69237,65949,59299,65939,57562,57832,76620,50310,46195, 46185, 46053, 42481, 45741, 45742, 45481, 45482,63955,67550,70248, 43037, 43187, 62181, 44849, 45208, 45209, 44848, 28996,
       44327, 43017, 44599, 44600, 43002, 43207, 42647, 42900, 42979, 12001, 18615, 17045, 1347,8157,64000,64259,68540,71459,72490,72486,72505,72522,74140,75491,74295)

  and usuario_bono.fecha_crea >= '" . date('Y-m-d H:i:s', strtotime($fechaL1 . '-120 minutes')) . "'
  and usuario_bono.fecha_crea < '" . $fechaL2 . "'
  and u2.usubono_id is null



";

            $execSQl = new BonoInterno();
            $datos = $execSQl->execQuery('', $sql);

            foreach ($datos as $value) {
                $bonoId2 = $value->{'.bonoId2'};

                $Usuario = new Usuario($value->{'usuario_bono.usuario_id'});
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
                    "CiudadUSER" => 0,
                    "MonedaUSER" => $Usuario->moneda,

                );

                $BonoInterno = new BonoInterno();
                $detalles = json_decode(json_encode($detalles));

                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                $Transaction = $TransprodLogMysqlDAO->getTransaction();

                $respuestaBono2 = $BonoInterno->agregarBonoFree($bonoId2, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, "", $Transaction);


                $Transaction->commit();
            }
        }


        print_r('PROCCESS OK');

        unlink($filename);
    }
}