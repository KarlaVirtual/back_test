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
use Backend\dto\AuditoriaGeneral;
use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CategoriaMandante;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\CupoLog;
use Backend\dto\ItTicketEnc;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\JackpotInterno;
use Backend\dto\LealtadInterna;
use Backend\dto\MandanteDetalle;
use Backend\dto\PreUsuarioSorteo;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\SitioTracking;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\Subproveedor;
use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\TransjuegoInfo;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioJackpot;
use Backend\dto\UsuarioLealtad;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioMensajecampana;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioRecarga;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\dto\ProductoMandante;
use Backend\dto\UsuarioTorneo;
use Backend\dto\WebsocketNotificacion;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\payment\MONNETSERVICES;
use Backend\integrations\payout\WEPAY4USERVICES;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\LealtadInternaMySqlDAO;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\sql\Transaction;
use Backend\utils\BackgroundProcessVS;
use Backend\websocket\WebsocketUsuario;
use phpseclib3\Crypt\RSA;
use phpseclib3\Net\SFTP;

use Backend\dto\Pais;
use Backend\sql\ConnectionProperty;
use \Backend\utils\RedisConnectionTrait;


use PDO;
use Backend\mysql\WebsocketNotificacionMySqlDAO;

require(__DIR__ . '/../vendor/autoload.php');
///home/devadmin/api/api/
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
print_r('entro2');
ini_set('max_execution_time', 0);

try {
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();



    $BonoInterno = new BonoInterno();
    $fechaSoloDia = date("Y-m-d", strtotime('-1 days'));

    $sqlApuestasCasinoAJBTDia = "
SELECT 
       transaccion_juego.usuario_id usuarioId,
       sum(transaccion_juego.valor_ticket) valor_ticket,
       sum(transaccion_juego.valor_premio) valor_premio,
       SUM(CASE
               when transaccion_juego.valor_ticket > transaccion_juego.valor_premio THEN
                   0

               ELSE
                   CASE
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.99) >= 0.1
                           THEN  transaccion_juego.valor_ticket * 0.99
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.98) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.98
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.97) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.97
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.96) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.96
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.95) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.95
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.94) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.94
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.93) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.93
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.92) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.92
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.91) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.91
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.90) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.90
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.89) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.89
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.88) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.88
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.87) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.87
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.86) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.86
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.85) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.85
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.84) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.84
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.83) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.83
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.82) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.82
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.81) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.81
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.80) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.80
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.79) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.79
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.78) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.78
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.77) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.77
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.76) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.76
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.75) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.75
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.74) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.74
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.73) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.73
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.72) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.72
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.71) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.71
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.70) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.70
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.69) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.69
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.68) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.68
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.67) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.67
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.66) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.66
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.65) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.65
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.64) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.64
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.63) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.63
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.62) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.62
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.61) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.61
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.60) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.60
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.59) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.59
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.58) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.58
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.57) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.57
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.56) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.56
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.55) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.55
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.54) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.54
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.53) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.53
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.52) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.52
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.51) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.51
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.50) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.50
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.49) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.49
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.48) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.48
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.47) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.47
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.46) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.46
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.45) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.45
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.44) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.44
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.43) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.43
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.42) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.42
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.41) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.41
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.40) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.40
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.39) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.39
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.38) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.38
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.37) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.37
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.36) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.36
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.35) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.35
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.34) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.34
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.33) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.33
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.32) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.32
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.31) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.31
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.30) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.30
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.29) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.29
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.28) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.28
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.27) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.27
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.26) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.26
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.25) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.25
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.24) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.24
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.23) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.23
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.22) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.22
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.21) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.21
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.20) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.20
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.19) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.19
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.18) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.18
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.17) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.17
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.16) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.16
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.15) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.15
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.14) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.14
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.13) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.13
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.12) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.12
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.11) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.11
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.10) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.10
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.09) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.09
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.08) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.08
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.07) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.07
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.06) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.06
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.05) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.05
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.04) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.04
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.03) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.03
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.02) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.02
                       WHEN transaccion_juego.valor_premio - (transaccion_juego.valor_ticket * 0.01) >= 0.1
                           THEN transaccion_juego.valor_ticket * 0.01


                       ELSE
                           0
                       END
           END
       )                                   valor
FROM transaccion_juego
          inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = transaccion_juego.fecha_crea)

         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
where usuario_mandante.mandante = 0
  and usuario_mandante.pais_id = 173
  and time_dimension.dbdate = '" . $fechaSoloDia . "'
;
";

    $data = $BonoInterno->execQuery('', $sqlApuestasCasinoAJBTDia);


    foreach ($data as $datanum) {
        $sql = "INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'transaccion_juego.usuarioId'} . "','" . $datanum->{'.valor'} . "','0','" . $datanum->{'.fecha_crea'} . "','0','0','A','" . 10 . "','0')";
        $BonoInterno->execQuery($transaccion, $sql);
    }
    $transaccion->commit();
}catch(Exception $e){
    print_r($e);
}

exit();

$ConfigurationEnvironment= new ConfigurationEnvironment();
$btag='88a07caf87951c08a339e3b58bc98148qfT3ZBXc9GJ qsvSfTpmgLm';
$vvv=$ConfigurationEnvironment->decrypt($btag, "");
$linkid = explode("__", $ConfigurationEnvironment->decrypt($btag, ""))[1];

print_r($linkid);
exit();
$array = array();

foreach ($array as $item) {

    $UsuarioMandante = new UsuarioMandante($item);

    $BonoInterno = new BonoInterno(76585);
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $Transaction = $BonoInternoMySqlDAO->getTransaction();

    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
//$transaccion = $BonoDetalleMySqlDAO->getTransaction();
//$transaccion->getConnection()->beginTransaction();
    $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante,usuario.moneda FROM registro
    INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN pais ON (pais.pais_id = usuario.pais_id)
    LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  LEFT OUTER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
   WHERE registro.usuario_id='" . $UsuarioMandante->getUsuarioMandante() . "'";

    $Usuario = $BonoInterno->execQuery($Transaction, $usuarioSql);


    $dataUsuario = $Usuario;

    $detalles = array(
        "PaisUSER" => $dataUsuario[0]->{'pais.pais_id'},
        "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
        "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},
        "MonedaUSER" => $dataUsuario[0]->{'usuario.moneda'}

    );

    $detalles = json_decode(json_encode($detalles));

    $respuesta = $BonoInterno->agregarBonoFree($BonoInterno->bonoId, $UsuarioMandante->getUsuarioMandante(), $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, "", $Transaction);

    if ($BonoInterno->bonoId == 17336) {
        $respuesta = $BonoInterno->agregarBonoFree('17330', $UsuarioMandante->getUsuarioMandante(), $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, "", $Transaction);

    }
    $Transaction->commit();
    print_r($respuesta);
}
exit();
$sqlApuestasDeportivasUsuarioDiaCierre = "
        SELECT * FROM log_cron WHERE estado='PREPROCESS'   and tipo ='ActivacionRuletaSportBook'
        LIMIT 1000
        ";


// Control de procesos simultáneos
$maxConcurrentProcesses = 50; // Máximo de 10 procesos a la vez


function updateLog($logcron_id, $estado, $valor1 = '', $valor2 = '')
{
    $BonoInterno = new BonoInterno();
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $transaction = $BonoInternoMySqlDAO->getTransaction();

    $sql = "
            UPDATE log_cron SET estado='$estado'
    ";
    if ($valor1 != '') {
        $sql .= ",valor1='" . str_replace("'", '"', $valor1) . "' ";
    }
    if ($valor2 != '') {
        $sql .= ",valor2='" . str_replace("'", '"', $valor2) . "' ";

    }
    $sql .= " WHERE logcron_id=$logcron_id; ";
    $resultsql = $BonoInterno->execUpdate($transaction, $sql);
    $transaction->commit();
    return $resultsql;
}


$activeProcesses = [];
$startTimes = [];

$time = time();
$BonoInterno = new BonoInterno();

$data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);



$cont = 0;
$logsIN=array();
foreach ($data as $datanum) {
    $logID = $datanum->{'log_cron.logcron_id'};
    array_push($logsIN,$logID);

}
if(oldCount($logsIN)>0){

    $BonoInterno = new BonoInterno();
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $transaction = $BonoInternoMySqlDAO->getTransaction();

    $sql = "
            UPDATE log_cron SET estado='ERROR'
    ";

    $sql .= " WHERE logcron_id IN (".implode(',',$logsIN)."); ";
    $resultsql = $BonoInterno->execUpdate($transaction, $sql);
    $transaction->commit();
}

exit();

$ClientIdCsv = array(
);
/* Se crea una nueva instancia de la clase Transaction. */
$transaction = new Transaction();
try {
    foreach ($ClientIdCsv as $id) {
        print_r($id);

        /* Se crean instancias de CuentaCobro, Usuario y UsuarioPerfil usando identificadores específicos. */
        $CuentaCobro = new CuentaCobro($id);
        $Usuario = new Usuario($CuentaCobro->getUsuarioId());
        $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());
        if ($UsuarioPerfil->getPerfilId() == 'USUONLINE') {
            if ($CuentaCobro->getEstado() != 'R' && $CuentaCobro->getEstado() != "E") {
                $beforeState = $CuentaCobro->getEstado();

                /* Modifica el estado de CuentaCobro basado en su estado actual y asigna un usuario. */
                if ($CuentaCobro->getEstado() == "I") {
                    $CuentaCobro->setEstado('D');
                } else {
                    $CuentaCobro->setEstado('R');
                }
                $CuentaCobro->setUsurechazaId(0);

                /* Actualiza la cuenta de cobro y acredita una ganancia al usuario correspondiente. */
                $CuentaCobro->setObservacion('Rechazo masivo peticion Deivys');
                $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($transaction);
                $CuentaCobroMySqlDAO->update($CuentaCobro);

                $Usuario->creditWin($CuentaCobro->getValor(), $transaction);

                /* Se crea un nuevo historial de usuario con ID y movimiento específicos. */
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setUsucreaId(0);

                /* Se registra un historial de usuario vinculando cuentas y valores en MySQL. */
                $UsuarioHistorial->setTipo(40);
                $UsuarioHistorial->setValor($CuentaCobro->getValor());
                $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                // ** Auditoría: registrar en la tabla `auditoria_general` **
                $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $ip = explode(",", $ip)[0];

                /* Se crea una auditoría general registrando información del usuario y la solicitud. */
                $AuditoriaGeneral = new AuditoriaGeneral();
                $AuditoriaGeneral->setUsuarioId(0);
                $AuditoriaGeneral->setUsuarioIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId(0);
                $AuditoriaGeneral->setUsuariosolicitaIp("");
                $AuditoriaGeneral->setUsuarioaprobarId(0);

                /* registra una auditoría de aprobación de notas de retiro en un sistema. */
                $AuditoriaGeneral->setUsuarioaprobarIp($ip);
                $AuditoriaGeneral->setTipo("RECHAZO MASIVO NOTA DE RETIRO");
                $AuditoriaGeneral->setValorAntes($beforeState);
                $AuditoriaGeneral->setValorDespues($CuentaCobro->getEstado());
                $AuditoriaGeneral->setUsucreaId(0);
                $AuditoriaGeneral->setUsumodifId(0);

                /* Se configuran atributos de un objeto AuditoriaGeneral con datos específicos. */
                $AuditoriaGeneral->setEstado("A");
                $AuditoriaGeneral->setDispositivo('');
                $AuditoriaGeneral->setSoperativo('');
                $AuditoriaGeneral->setSversion(0);
                $AuditoriaGeneral->setImagen("");
                $AuditoriaGeneral->setObservacion('Rechazo Masivo peticion Deivys' ); // Asigna un valor por defecto si está vacío

                /* Inserta datos vacíos en la auditoría utilizando un DAO en MySQL. */
                $AuditoriaGeneral->setData($CuentaCobro->getCuentaId());
                $AuditoriaGeneral->setCampo('');

                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaction);
                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

            } else {
                /* Lanza una excepción si se intenta modificar un retiro ya procesado. */

                throw new Exception('No puedes cambiar el estado de un retiro ya procesado.');
            }
        } else {
            /* Lanza una excepción si el usuario no está en línea. */

            throw new Exception('No es Usuario Online');
        }
    }

    /* Finaliza la transacción, guardando todos los cambios realizados en la base de datos. */
    $transaction->commit();

    /* Inicializa una respuesta estructurada sin errores y lista para datos y mensajes. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = '';
    $response["ModelErrors"] = [];
    $response["Data"] = [];
} catch (Exception $e) {
    /* Manejo de excepciones: muestra errores en debug y realiza un rollback si es necesario. */

    if ($_ENV['debug']) {
        print_r($e);
    }
    if ($_ENV["debugFixed2"] == '1') {
        print_r($e);
    }
    if ($transaction->getConnection()->isBeginTransaction == 2) {
        $transaction->rollback();
    }
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = $e->getMessage();
    $response["ModelErrors"] = [];
    $response["Data"] = [];
}
exit();
$array = array(

);
foreach ($array as $item) {
    print_r($item);
    print_r(PHP_EOL);
    $UsuarioRecarga = new UsuarioRecarga($item);
    $Usuario = new Usuario($UsuarioRecarga->usuarioId);

    $detalles = array(
        "Depositos" => 0,
        "DepositoEfectivo" => true,
        "MetodoPago" => 0,
        "ValorDeposito" => $UsuarioRecarga->getValor(),
        "PaisPV" => $Usuario->paisId,
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
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $Transaction = $BonoInternoMySqlDAO->getTransaction();

    $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);
    print_r($respuestaBono);

    $Transaction->commit();

}
exit();
try {
    $key='F15BACK+UIDphp -f /home/home2/backendprodfinal/api/cron/jobs/../../src/integrations/casino/VerificarRollower.php CASINO  2057979  > /dev/null & ';
    if (strpos($key, 'VerificarRollower.php') !== false) {
        $keyvalue = explode('VerificarRollower.php', $key)[1];
        $keyvalue = ltrim($keyvalue);
        $keyvalue = explode(' ', $keyvalue);
        print_r($keyvalue);
        exit();
        $arg1 = $keyvalue[0];
        $arg2 = $keyvalue[1];
        $arg3 = $keyvalue[2];
        $arg4 = $keyvalue[3];


        $detalles2 = array(
            "JuegosCasino" => array(array(
                "Id" => 2
            )

            ),
            "ValorApuesta" => 0
        );

        $BonoInterno = new BonoInterno();
        $respuesta = $BonoInterno->verificarBonoRollower($arg3, $detalles2, $arg1, $arg2, $arg4);

    }


} catch (Exception $e) {
}

exit();
$fechaSoloDia='2025-07-02';
$fechaHoy='2025-07-02';

$sqlApuestasDeportivasUsuarioDiaFECHACIERREBET = "
SELECT
  it_transaccion.usuario_id usuarioId, 
  SUM(it_transaccion.valor) valor,
  '" . $fechaSoloDia . "' fecha_crea,
      0 usucrea_id,
      0 usumodif_id,
      'A' estado,
      'CLOSEDBET' tipo,
  COUNT(*) cantidad
FROM it_transaccion
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
  INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                        inner join time_dimension4
                                        on (time_dimension4.datestr = it_ticket_enc.fecha_cierre and
                                            time_dimension4.hour2str = it_ticket_enc.hora_cierre)

    WHERE    it_transaccion.tipo='BET'  AND
    time_dimension4.dbdate1 = '".$fechaSoloDia."'
    AND usuario_perfil.perfil_id = \"USUONLINE\"  AND usuario_perfil.mandante  IN (3,4,5,6,7,10,22,25)
GROUP BY '" . $fechaSoloDia . "',it_transaccion.tipo, it_transaccion.usuario_id
ORDER BY '" . $fechaSoloDia . "',it_transaccion.tipo,it_transaccion.usuario_id;";
print_r($sqlApuestasDeportivasUsuarioDiaFECHACIERREBET);
flush();
ob_flush();
$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
$transaccion->getConnection()->beginTransaction();

$data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaFECHACIERREBET);

foreach ($data as $datanum) {
    $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'it_transaccion.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
    $BonoInterno->execQuery($transaccion, $sql);
}

$transaccion->commit();

exit();
$UsuarioId=1043559;
$UserId=1043559;
    $BonoId=64306;
$BonoInterno = new BonoInterno();
$Usuario = new Usuario($UserId);
$BonoInterno = new BonoInterno($BonoId);
$BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

$Registro = new Registro("", $Usuario->usuarioId);
$CiudadMySqlDAO = new CiudadMySqlDAO();
$CONDSUBPROVIDER = array();
$CONDGAME = array();
//Bonos Freespin
if ($BonoInterno->tipo == "8") {
    $Transaction = $BonoInternoMySqlDAO->getTransaction();
    $sqlDetalleBono = "select * from bono_detalle a  where a.bono_id='" . $BonoId . "' AND (moneda='' OR moneda='" . $Usuario->moneda . "') ";

    $bonoDetalles = $BonoInterno->execQuery($Transaction, $sqlDetalleBono);

    foreach ($bonoDetalles as $bonoDetalle) {


        if (stristr($bonoDetalle->{'a.tipo'}, 'CONDSUBPROVIDER')) {

            $idSub = explode("CONDSUBPROVIDER", $bonoDetalle->{'a.tipo'})[1];

            array_push($CONDSUBPROVIDER, $idSub);

        }

        if (stristr($bonoDetalle->{'a.tipo'}, 'CONDGAME')) {

            $idGame = explode("CONDGAME", $bonoDetalle->{'a.tipo'})[1];
            if ($idGame == '') {
                if ($bonoDetalle->{'a.valor'} != '') {
                    $idGame = $bonoDetalle->{'a.valor'};
                }
            }
            array_push($CONDGAME, $idGame);

        }


        if (stristr($bonoDetalle->{'a.tipo'}, 'PREFIX')) {

            $Prefix = explode("PREFIX", $bonoDetalle->{'a.tipo'})[1];
            if ($Prefix == '') {
                if ($bonoDetalle->{'a.valor'} != '') {
                    $Prefix = $bonoDetalle->{'a.valor'};
                }
            }

        }
        if (stristr($bonoDetalle->{'a.tipo'}, 'MAXJUGADORES')) {

            $MaxplayersCount = explode("MAXJUGADORES", $bonoDetalle->{'a.tipo'})[1];
            if ($MaxplayersCount == '') {
                if ($bonoDetalle->{'a.valor'} != '') {
                    $MaxplayersCount = $bonoDetalle->{'a.valor'};
                }
            }

        }
    }


    try {
        $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'REPETIRBONO');
        $repetirBono = $BonoDetalleROUNDSFREE->valor;
    } catch (Exception $e) {
        $repetirBono = 0;
    }
    if (!$repetirBono) {
        try {
            $VerifUsuarioBono = new UsuarioBono('', $UsuarioId, $BonoId);
            exit();
        } catch (Exception $e) {

        }
    }

    print_r('CAMPA OPTIMOVE 4');

    $Subproveedor = new Subproveedor($idSub);
    $Proveedor = new Proveedor($Subproveedor->proveedorId);
    print_r($Subproveedor);
    if($Subproveedor->abreviado =='PLAYTECH' || $Subproveedor->abreviado =='PRAGMATIC' || $Subproveedor->abreviado =='PLATIPUS'){
        $Prefix = 'ADMIN2';
        if ($Usuario->mandante == '0' && $Usuario->paisId == 173) {
            $Prefix = 'ADMIN2';
        } elseif ($Usuario->mandante != '8') {
            $Prefix = 'ADMIN3';
        }

        $redisParam = ['ex' => 18000];

        $UserId = $argv[1];
        $BonoId = $argv[2];
        $CampaignID = $argv[3];
        $UsuarioId=$UserId;

        $redisPrefix = $Prefix . $Subproveedor->abreviado."F3BACK+AgregarBonoBackground+UID" . $UsuarioId . '+' . $BonoId . '+' . $CampaignID. '+' . $logID;

        $redis = RedisConnectionTrait::getRedisInstance(true);

        if ($redis != null) {

            $redis->set($redisPrefix, json_encode($argv), $redisParam);
        }
        exit();

    }
    if (in_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)) {
        print_r('entro');
        $_ENV['debug']=true;
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $responseBonoGlobal = $BonoInterno->bonoGlobal($Proveedor, $BonoId, $CONDGAME, $Usuario->mandante, $Usuario->usuarioId, $Transaction, 0, false, 0, $BonoInterno->nombre, $Prefix, $MaxplayersCount);
        $Transaction->commit();
        print_r($responseBonoGlobal);
    }else{

    }

}

exit();

//"SPORT " . $datanum->{'it_ticket_enc.ticket_id'} . " " . $datanum->{'it_ticket_enc.usuario_id'}
$arg1 = "SPORT";
$arg2 = "3777048710";
$arg3 = "7481954";
$arg4 = "";


$detalles2 = array(
    "JuegosCasino" => array(array(
        "Id" => 2
    )

    ),
    "ValorApuesta" => 0
);

$BonoInterno = new BonoInterno();
$respuesta = $BonoInterno->verificarBonoRollower($arg3, $detalles2, $arg1, $arg2, $arg4);

exit();
$array = array(

);
foreach ($array as $item) {
    print_r($item);
    print_r(PHP_EOL);
    $UsuarioRecarga = new UsuarioRecarga($item);
    $Usuario = new Usuario($UsuarioRecarga->usuarioId);

    $detalles = array(
        "Depositos" => 0,
        "DepositoEfectivo" => true,
        "MetodoPago" => 0,
        "ValorDeposito" => $UsuarioRecarga->getValor(),
        "PaisPV" => $Usuario->paisId,
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
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $Transaction = $BonoInternoMySqlDAO->getTransaction();

    $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);
    print_r($respuestaBono);

    $Transaction->commit();

}
exit();
$sql = "
SELECT usuario.usuario_id, usuario_mandante.usumandante_id,usuario_mandante.mandante
FROM  usuario_mandante
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante

WHERE usumandante_id in (6866121,4917667,7823447,8198901,8640745,1420193,8811591,1775057,2023100,8496333,2905672,3620839,1587575,597672,2797477,767556,9534554,616662,8088337,9831086,2670316,6564099,5382185,1410116,9834815,8590166,9693167,5165623,9062441,1353675,6231655,3581392,7459085,10294774,5322662,9476677,782304,1826416,1525097,1619186,10086535,1199926,3038522,9252379,2543255,3514834,9246993,1122593,1944634,2125158,1567571,2938648,7608826,2586203,6033516,4041314,7747333,2937475,8067888,3091970,6926694,913089,1652777,7893915,6302313,6982239,596799,2335422,9500215,2303997,2626174,1355469,8735839,3020402,3308851,1301292,9665606,8790067,8771019,8296984,4624573,9580218,756458,1091131,9978291,3822852,4234146,4221238,1516845,3319132,8712641,790710,5476843,8783308,8634767,2583521,7585354,9293933,7978044,946338,4359544,7789031,6484996,7966422,2142198,2048974,2290015,8508240,1874257,7972671,8350375,2696635,3730928,7867732,2445797,1508097,3696842,5140570,1701896,6860798,4123763,5145868,8535879,3230965,3471913,756949,2810963,2229525,2873733,7009953,9939861,7569739,736933,9989688,4041500,5428321,3483946,2995541,7943376,7574677,1318995,2827339,9453809,9190724,9423332,8391896,1020333,4880878,8277308,8665850,7155320,1629299,1429085,9458640,9549872,9889316,296826,846996,7746132,1128619,1467069,2022806,842624,7829876,6438630,7974073,10118796,7868639,2801245,2380599,394328,10264941,9930062,7248238,5022363,8038092,7692897,1301964,8083027,4181692,7257392,2522795,8135356,2286733,8407905,714433,8405814,2411780,4382422,2479139,9461719,8372757,6852883,9423126,1695914,901863,8507928,7661075,188763,2428694,7569366,1530404,2878935,1334802,9293014,4793051,1707271,8265031,6990022,9524018,1318188,5018982,1315236,10200197,7608190,392135,1688906,8492864,3003653,1467657,3018788,10325875,7549328,1622366,9547667,7838968,1171069,646141,6868236,4915348,835293,1500567,1655591,3537607,9585465,8497889,8965232,7244086,1932550,8177648,4877791,3631696,8210795,4605757,3133136,3037511,6222484,633361,8842717,1639703,5701877,2448602,1528931,2034631,3491413,4409284,8598413,1032381,8203089,8519208,2173804,8640953,809414,10352836,2166058,9644660,3017867,669473,4434235,1458866,10146853,2019197,5125927,4533115,7385365,3778361,5681702,2377842,7723118,8035262,8150764,8400364,5629382,3176731,7640841,1890798,2613442,3036098,8362194,2925499,1952209,7001893,5352803,2731753,9818344,5450743,5597634,5589363,2988329,6726409,2572709,3200287,7309062,8688783,8797980,8897766,7865469,9760536,8232991,5303246,8968692,1433397,5595072,7902871,8462848,6221494,8055592,7569583,5068862,7641329,2707911,2614267,8527189,945770,9877730,611592,8913287,5103652,8872376,6860477,6641070,7424393,9071740,8664484,2764282,7306567,9136046,3678791,6982586,6301904,5745595,3672224,7022198,4936312,2149524,7087012,5614347,2322110,5341613,1435818,3224671,1611941,10156724,1057690,1800278,5067443,10078374,1710694,6906398,2124174,5124745,7232162)
  group by usuario.usuario_id
  ";

$BonoInterno = new BonoInterno();
$data = $BonoInterno->execQuery('', $sql);


foreach ($data as $valueUltimoMinuto) {
    $bannerInv = [];
    $depositPopup = array();
    $loyalty_price = array();
    $bonusesDatanew = array();

    $Usuario = new \Backend\dto\Usuario($valueUltimoMinuto->{"usuario.usuario_id"});
    $UsuarioMandante = new \Backend\dto\UsuarioMandante($valueUltimoMinuto->{"usuario_mandante.usumandante_id"});
    $Mandante = new \Backend\dto\Mandante($valueUltimoMinuto->{"usuario_mandante.mandante"});

    #BANNER DE USUARIOS SIN SALDO

    if (true) {
        $UsuarioPerfil = new \Backend\dto\UsuarioPerfil($Usuario->usuarioId);

        #MENSAJES PARA USUARIOS DE CUMPLEAÑOS
        if ($UsuarioPerfil->perfilId == 'USUONLINE' && $Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S') {
            // if ( $UsuarioMandante->getUsuarioMandante() =='73818') {
            $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);
            //   if ( $UsuarioMandante->getUsuarioMandante() =='73818') {
            if (true) {


                $arrayCampanasCumple = array(
                    '8_66' => 24749
                , '0_173' => 48095
                , '0_66' => 135650
                , '0_60' => 135650
                , '0_68' => 135651
                , '0_46' => 135651
                , '0_94' => 135651
                , '23_102' => 157073
                , '21_232' => 157111
                , '27_94' => 157110
                , '27_68' => 157109
                );
                $arrayBonoCumple = array(
                    '8_66' => 67638
                , '0_173' => 62982
                , '0_66' => 46860
                , '0_60' => 59255
                , '0_68' => 60352
                , '0_46' => 63136
                , '0_94' => 68613
                , '23_102' => 66999
                , '21_232' => 74599
                , '27_94' => 74522
                , '27_68' => 74519
                );

                foreach ($arrayCampanasCumple as $keyCampana => $valueCampana) {
                    $partner = explode('_', $keyCampana)[0];
                    $pais = explode('_', $keyCampana)[1];
                    if (!($Usuario->mandante == $partner && $Usuario->paisId == $pais)) {
                        continue;
                    }
                    if ($arrayBonoCumple[$keyCampana] == null) {
                        continue;
                    }

                    try {
                        if (true) {

                            $bonoId = $arrayBonoCumple[$keyCampana];


                            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();

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

                            $BonoInterno = new BonoInterno();
                            $BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();

                            $Transaction = $BonoInternoMySqlDAO->getTransaction();

                            $detalles = json_decode(json_encode($detalles));

                            $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction);
                            $Transaction->commit();
                            print_r($responseBonus);
                        }

                    } catch (Exception $e) {

                    }
                }
            }
        }
    }

}

exit();


$array = array(
);
foreach ($array as $item) {
    $UsuarioLealtad= new UsuarioLealtad($item);
    $Usuario= new Usuario($UsuarioLealtad->usuarioId);


    $LealtadInterna = new LealtadInterna($UsuarioLealtad->lealtadId);
    $bonoId = $LealtadInterna->bonoId;

    $premio = "Id de Bono:" . $bonoId;


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

    $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();
    $Transaction = $LealtadInternaMySqlDAO->getTransaction();

    $BonoInterno = new BonoInterno();

    $detalles = json_decode(json_encode($detalles));

    if($bonoId != '' && $bonoId != '0'){
        $UsuarioBono = new UsuarioBono();

        $UsuarioBono->setUsuarioId(0);
        $UsuarioBono->setBonoId($bonoId);
        $UsuarioBono->setValor(0);
        $UsuarioBono->setValorBono(0);
        $UsuarioBono->setValorBase(0);
        $UsuarioBono->setEstado('L');
        $UsuarioBono->setErrorId(0);
        $UsuarioBono->setIdExterno(0);
        $UsuarioBono->setMandante($Usuario->mandante);
        $UsuarioBono->setUsucreaId(0);
        $UsuarioBono->setUsumodifId(0);
        $UsuarioBono->setApostado(0);
        $UsuarioBono->setRollowerRequerido(0);
        $UsuarioBono->setCodigo('');
        $UsuarioBono->setVersion('2');
        $UsuarioBono->setExternoId(0);

        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

        $UsuarioBonoMysqlDAO->insert($UsuarioBono);

    }
    //print_r($bonoId);
    $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction,false,true);
    $Transaction->commit();
}
exit();
$array = array(
);
foreach ($array as $item) {
    $UsuarioLealtad= new UsuarioLealtad($item);
    $Usuario= new Usuario($UsuarioLealtad->usuarioId);


    $LealtadInterna = new LealtadInterna($UsuarioLealtad->lealtadId);
    $bonoId = $LealtadInterna->bonoId;

    $premio = "Id de Bono:" . $bonoId;


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

    $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();
    $Transaction = $LealtadInternaMySqlDAO->getTransaction();

    $BonoInterno = new BonoInterno();

    $detalles = json_decode(json_encode($detalles));

    if($bonoId != '' && $bonoId != '0'){
        $UsuarioBono = new UsuarioBono();

        $UsuarioBono->setUsuarioId(0);
        $UsuarioBono->setBonoId($bonoId);
        $UsuarioBono->setValor(0);
        $UsuarioBono->setValorBono(0);
        $UsuarioBono->setValorBase(0);
        $UsuarioBono->setEstado('L');
        $UsuarioBono->setErrorId(0);
        $UsuarioBono->setIdExterno(0);
        $UsuarioBono->setMandante($Usuario->mandante);
        $UsuarioBono->setUsucreaId(0);
        $UsuarioBono->setUsumodifId(0);
        $UsuarioBono->setApostado(0);
        $UsuarioBono->setRollowerRequerido(0);
        $UsuarioBono->setCodigo('');
        $UsuarioBono->setVersion('2');
        $UsuarioBono->setExternoId(0);

        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

        $UsuarioBonoMysqlDAO->insert($UsuarioBono);

    }
    //print_r($bonoId);
    $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction,false,true);
    $Transaction->commit();
}
exit();
$array = array(

);
foreach ($array as $item) {
    $Usuario= new Usuario($item);
    $UsuarioBono= new UsuarioBono();
    $UsuarioBono->cancelarBonoActivoAltenar($Usuario->usuarioId, $Usuario->mandante);

}
exit();
$array = array(
    2106808
);
foreach ($array as $item) {


    $Usuario = new Usuario($item);
    $UsuarioMandante = new UsuarioMandante('',$Usuario->usuarioId,$Usuario->mandante);
    print_r($Usuario);
    if ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S') {
        // if ( $UsuarioMandante->getUsuarioMandante() =='73818') {
        $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);
        //   if ( $UsuarioMandante->getUsuarioMandante() =='73818') {

        $arrayCampanasCumple = array(
            '8_66' => 24749
        , '0_173' => 48095
        , '0_66' => 135650
        , '0_60' => 135650
        , '0_68' => 135651
        , '0_46' => 135651
        );
        $arrayBonoCumple = array(
            '8_66' => 67638
        , '0_173' => 62982
        , '0_66' => 46860
        , '0_60' => 59255
        , '0_68' => 60352
        , '0_46' => 63136
        );

        foreach ($arrayCampanasCumple as $keyCampana => $valueCampana) {
            $partner = explode('_', $keyCampana)[0];
            $pais = explode('_', $keyCampana)[1];
            if (!($Usuario->mandante == $partner && $Usuario->paisId == $pais)) {
                continue;
            }
            if ($arrayBonoCumple[$keyCampana] == null) {
                continue;
            }

            $rulesM = array();
            array_push($rulesM, array("field" => "usuario_mensaje.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "in"));
            array_push($rulesM, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensaje.fecha_crea", "data" => date('Y-m-d'), "op" => "cn"));
            array_push($rulesM, array("field" => "usuario_mensaje.usumencampana_id", "data" => $valueCampana, "op" => "eq"));

            print_r($rulesM);

            $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
            $json2 = json_encode($filtroM);

            $UsuarioMensaje = new UsuarioMensaje();
            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

            $usuarios = json_decode($usuarios);

            $mensajeEnviado = true;

            if (intval($usuarios->count[0]->{".count"}) == 0) {
                $mensajeEnviado = false;
            }


            try {
                if (!$mensajeEnviado) {

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = '';
                    $UsuarioMensaje->msubject = 'Campaña Cumpleaños';
                    $UsuarioMensaje->tipo = "MESSAGEINV";
                    $UsuarioMensaje->parentId = 151931894;
                    $UsuarioMensaje->proveedorId = $Usuario->mandante;
                    $UsuarioMensaje->setExternoId(0);
                    $UsuarioMensaje->usumencampanaId = $valueCampana;


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $bonoId = $arrayBonoCumple[$keyCampana];

                    $UsuarioBono = new UsuarioBono();

                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

                    $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                    $UsuarioBono->setUsuarioId(0);
                    $UsuarioBono->setBonoId($bonoId);
                    $UsuarioBono->setValor(0);
                    $UsuarioBono->setValorBono(0);
                    $UsuarioBono->setValorBase(0);
                    $UsuarioBono->setEstado("L");
                    $UsuarioBono->setErrorId(0);
                    $UsuarioBono->setIdExterno(0);
                    $UsuarioBono->setMandante($Usuario->mandante);
                    $UsuarioBono->setUsucreaId(0);
                    $UsuarioBono->setUsumodifId(0);
                    $UsuarioBono->setApostado(0);
                    $UsuarioBono->setRollowerRequerido(0);
                    $UsuarioBono->setCodigo("");
                    $UsuarioBono->setVersion(0);
                    $UsuarioBono->setExternoId(0);


                    //print_r($UsuarioBono);

                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                    $transaccion5->commit();

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

                    $BonoInterno = new BonoInterno();
                    $BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();

                    $Transaction = $BonoInternoMySqlDAO->getTransaction();

                    $detalles = json_decode(json_encode($detalles));

                    $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction);
                    $Transaction->commit();

                }

            } catch (Exception $e) {

            }
        }
    }
}
exit();

$Usuario= new Usuario(1532408);


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
    "CodePromo" => 'kmewuYR6S'

);
print_r('entro2');

$bonuscode='kmewuYR6S';
$BonoInterno = new BonoInterno();
$responseBonus = $BonoInterno->agregarBonoFree('72448', $Usuario->usuarioId, $Usuario->mandante, $detalles, '', $bonuscode,'');
print_r($responseBonus);
exit();

$ConfigurationEnvironment= new ConfigurationEnvironment();
$btag='f6447b39b7fb73deed220d24eb0aae28u3BXNOUQd4Hm0QHPkBY=';
$vvv=$ConfigurationEnvironment->decrypt($btag, "");

print_r($vvv);
exit();


$TypeDate = 'h';
$redisParam = ['ex' => 18000000];

$redis = RedisConnectionTrait::getRedisInstance(true);
$redis->set('111', $dateFrom . '###' . $dateTo, $redisParam);

exit();


$BonoInterno = new BonoInterno();
$Usuario= new Usuario(8175146);
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

$BonoInterno = new BonoInterno();
$BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();

$Transaction = $BonoInternoMySqlDAO->getTransaction();

$detalles = json_decode(json_encode($detalles));

//Hacemos llamado a la funcion agregarBonoFree
$resp = $BonoInterno->agregarBonoFree('71771', '8175146', '0', $detalles, '', '', $Transaction);
print_r($resp);
exit();
$array1=array();
$array2=array();

foreach ($array1 as $key=>$usuario){
    $Usuario = new Usuario($usuario);
    $Registro = new Registro('', $Usuario->usuarioId);

    $Registro->setCedula($array2[$key]);

    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $transaccion = $BonoInternoMySqlDAO->getTransaction();

    $RegistroMySqlDAO = new \Backend\mysql\RegistroMySqlDAO();
    $RegistroMySqlDAO = new $RegistroMySqlDAO($transaccion);
    $RegistroMySqlDAO->update($Registro);
    $transaccion->commit();
}

exit();
$fechaSoloDia = '2025-05-01';

$fecha1 = "2025-05-01 00:00:00";
$fecha2 = "2025-05-01 23:59:59" ;


$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
$transaccion->getConnection()->beginTransaction();

$sqlRESUMEN_TOTAL_COMISIONES = "
INSERT INTO usucomisionusuario_resumen
(valor1, valor2, valor3, usuario_id, fecha_crea, valor, comision, valor_pagado, tipo, estado)
SELECT SUM(usucomision_resumen.valor1)       valor1,
       SUM(usucomision_resumen.valor2)       valor2,
       SUM(usucomision_resumen.valor3)       valor3,
       usucomision_resumen.usuario_id,
       usucomision_resumen.fecha_crea,
       SUM(usucomision_resumen.valor)        valor,
       SUM(usucomision_resumen.comision)     comision,
       SUM(usucomision_resumen.valor_pagado) valor_pagado,
       clasificador.clasificador_id,
       'A'
FROM usucomision_resumen
         INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id)
         INNER JOIN clasificador ON (clasificador.clasificador_id = usucomision_resumen.tipo)
where 1 = 1
  AND ((usucomision_resumen.fecha_crea)) >= '" . $fecha1 . "'  
  AND ((usucomision_resumen.fecha_crea)) <= '" . $fecha2 . "'
GROUP BY usucomision_resumen.usuario_id, usucomision_resumen.fecha_crea, usucomision_resumen.tipo
";
$data = $BonoInterno->execQuery($transaccion, $sqlRESUMEN_TOTAL_COMISIONES);

$transaccion->commit();

exit();
$sqlRESUMENCOMISIONES = "
SELECT usuario_comision.tipo,
       date_format(
           usuario_comision.fecha_crea,
           '%Y-%m-%d') fecha,
       usuario_comision.usuario_id,
       usuario_comision.usuarioref_id,
       SUM(usuario_comision.valor)    valor,
       SUM(usuario_comision.comision) comision,
       
        SUM(usuario_comision.valor1)    valor1,
       SUM(usuario_comision.valor2)    valor2,
       SUM(usuario_comision.valor3)    valor3,
       SUM(usuario_comision.valor4)    valor4,
       SUM(usuario_comision.valor5)    valor5,
       SUM(usuario_comision.valor6)    valor6

FROM usuario_comision
INNER JOIN usuario ON (usuario.usuario_id=usuario_comision.usuario_id)

WHERE  date_format(usuario_comision.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'  AND usuario.maxima_comision >=0 	AND  (usuario_comision.tiene_comision != 'N' OR usuario_comision.tiene_comision IS NULL)
GROUP BY usuario_comision.tipo,
         usuario_comision.usuarioref_id,
         usuario_comision.usuario_id,
         date_format(
             usuario_comision.fecha_crea,
             '%Y-%m-%d')
ORDER BY fecha";


$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
$transaccion->getConnection()->beginTransaction();

$data = $BonoInterno->execQuery('', $sqlRESUMENCOMISIONES);

foreach ($data as $datanum) {
    $sql = "INSERT INTO usucomision_resumen (tipo, fecha_crea,usuario_id, usuarioref_id, valor, comision, valor1, valor2, valor3, valor4, valor5, valor6)
    VALUES ('" . $datanum->{'usuario_comision.tipo'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'usuario_comision.usuario_id'} . "','" . $datanum->{'usuario_comision.usuarioref_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.comision'} . "','" . $datanum->{'.valor1'} . "','" . $datanum->{'.valor2'} . "','" . $datanum->{'.valor3'} . "','" . $datanum->{'.valor4'} . "','" . $datanum->{'.valor5'} . "','" . $datanum->{'.valor6'} . "')";
    $BonoInterno->execQuery($transaccion, $sql);
}

$transaccion->commit();

exit();

exit();


function actualizarUsuarios($idUsuarios)
{
    $idUsuariosStr = implode(",", $idUsuarios);

    $apmin2Sql = "
    UPDATE IGNORE usuario
        JOIN usuario_copy ON usuario_copy.usuario_id = usuario.usuario_id
    SET usuario.login = TO_BASE64(CONCAT(@iv := UNHEX('c4d2e1f89a7b6503f2d4a9c8e6b1fd32'),
                                         AES_ENCRYPT(
                                                 CONVERT(LOWER(usuario_copy.login) USING utf8mb4) COLLATE
                                                 utf8mb4_unicode_ci,
                                                 'L14m3O1r25c2e4hg', UNHEX('c4d2e1f89a7b6503f2d4a9c8e6b1fd32')
                                         )
                                  ))
    WHERE usuario_copy.login COLLATE latin1_bin REGEXP '[A-Z]'
      AND usuario_copy.usuario_id IN ({$idUsuariosStr});
    ";
    $BonoInterno = new \Backend\dto\BonoInterno();
    $BonoInterno->execQuery("", $apmin2Sql);

    print_r(PHP_EOL . "Procesado lote de " . oldCount($idUsuarios) . " usuarios" . PHP_EOL);

}

$array =  array(
    2169274,1159928,1740222,229478,4283061,7549447,7215430,551399,4717883,4718453,8568819,2028954,1887352,7730156,4965753,7162547,9380547,7566291,6938141,7096705,592388,1157321,168161,768008,8818730,19518,6165952,6741595,4080432,589436,2541271,31684,2983700,3382363,8022323,2215950,625238,7276094,6827578,7886774,1362296,1821876,8852385,9356813,884456,225557,4975869,4411240,1361621,8726235,493481,772654,4896493,8011258,981877,7731218,7796882,8123214,3583793,7860256,8656702,491126,7983430,5152367,7319819,541448,3889673,8889460,257125,1609376,8299423,917764,7921263,7703531,4605236,8489922,7033005,2209725,3884405,3276094,6896180,2833258,8303355,971527,227162,1669090,1370051,1189394,207796,8501497,731595,2281907,4160574,7267521,9114954,8513046,8159425,1396454,959512,6854036,5911650,6506867,7218617,3605288,5867004,1899718,1996785,6984879,9325469,8819563,9390566,859279,8645646,8279470,3898214,692016,2007865,288499,351065,4153352,8372370,3505786,5384998,8718188,4036733,7371090,8015933,5733059,7539381,7983209,9407799,8707373,8703967,7175084,7409452,6172963,8333182,2149077,7763567,6591889,2720749,60846,681918,1033533,5358815,7828034,8119198,468920,522333,7835064,1227798,9231145,7194990,214865,968956,280961,7593357,8348731,5288765,2680279,1899088,7323794,1348253,7811134,8968151,890459,2915471,8359884,9185956,8501154,5525871,2345567,1871819,2335232,454750,3499123,7265507,9458041,7542899,55501,6744868,9101384,3804801,8042237,672609,9269717,9308323,176412,2697813,937061,332366,317382,2123911,9313972,9103585,727938,285226,8925529,5127478,8115563,584004,179771,9179931,4741086,341027,7997700,7642961,8274,4636553,5255159,1662472,7619559,6880715,8709100,2369099,8893021,466151,2119096,8787372,5971320,2033799,6923434,637407,3137557,7795283,3644558,8054155,3072715,8687690,8995667,80947,3504508,1152771,4442035,169565,9119616,8692749,2057367,62868,6417912,823847,7680377,93700,5419150,7693573,9424133,450508,5171233,7667298,6529088,2514190,5462685,7001184,2037858,3201526,8464536,336328,139689,7892514,2279579,9550648,8841848,8841849,3854918,7553988,7193864,559358,5594990,8106947,7311039,2166071,7110967,2940083,1018407,8448627,2176634,4563133,3042760,7681280,2147157,1379927,3277246,1825296,1721038,9232541,3382789,295452,7711174,7502229,7479640,3458710,8199660,4257262,4109024,3525898,8925293,8874989,2816341,7138265,7573883,8791605,226843,389802,2271155,7417591,4740738,7008661,327203,4597525,191007,8626949,8963192,7925375,9311212,1937482,9436852,5667665,2159802,719787,8689364,9110138,6904800,57452,6238654,8067538,8849914,8199925,5672844,9076500,229481,6296341,7596139,8989472,2332610,220939,3297262,911824,587435,7294060,4948399,6304957,306089,8021734,5058088,1963059,1864790,6022865,7881262,8190196,9268399,14797,160681,5192513,6144253,3792258,6638182,3912230,9074925,625013,2359064,4425625,1367492,7274812,1173363,1783909,4128709,1549495,4756058,1401146,3141514,579174,8527184,7662489,2101885,8891756,8701408,9492128,7407008,7801369,5003873,7665017,6076247,5131759,7090332,6869893,9397984,8351461,6310145,8428323,73452,2756033,541253,5071270,7278858,8950255,7722388,3254785,7642592,1056924,7173843,7173298,6909585,7712462,7149023,7149024,949802,8149568,9267798,8672457,7823189,6929493,3710769,3304399,760700,9518213,755893,4823998,2406500,7580712,4488148,1403027,173056,8122364,1621001,2094889,3387862,1935130,6020477,7931420,3783606,7303627,2370170,6967435,1884199,7304084,4505122,4397344,8539937,6745066,871355,210669,9330722,646584,148386,1689772,1640957,4861201,8239243,8558247,970135,3256093,6852829,7614489,8235688,4038899,8680099,263215,9467626,62189,1018884,5829768,4041284,5910603,972586,621909,7162123,7162124,671130,7867873,7867859,2814610,6728365,9483959,8453592,836128,318679,8556141,6988986,52817,200062,7813926,329429,70239,9250545,7727546,355870,8002770,3366112,7804421,5021740,386470,7774398,726696,8117739,188773,8602902,920161,309517,423872,391309,478472,6310947,7909873,64937,8751324,1538077,6378183,994044,8384693,340790,7964879,624446,2889185,8430735,5819604,9023928,8927913,4628126,8836520,343671,2318777,2481520,5512641,9291924,7242095,5799771,3932024,4158115,6026585,6947622,8603377,6687370,8951959,1929656,917557,2966714,7287806,3566196,8583140,4054856,8312973,9298442,8510722,6490331,1279289,6914106,5765536,8306173,4032890,3904166,1514744,622832,4205878,582687,5014172,887090,7042172,1888279,6813145,6808342,7876363,6399658,6491417,7034114,1057287,685701,8111887,814121,15078,5273684,7109491,7813090,3131929,7823998,6927446,6800104,9116953,9485597,8970836,7129137,150388,5583272,5214631,3942347,229315,6028805,4792567,3691625,679101,8442485,8692317,2329808,8549605,3362737,4498237,7111044,132468,8826661,57019,500762,9350911,9236381,1907773,247854,8266941,7315326,4490203,5367729,81100,1907131,9003424,2828869,7703967,2317334,3835880,7461478,1528067,374680,5835012,3816915,200786,7208937,7185858,8661352,4284182,939937,5974071,7548421,8954536,431140,8350326,6187774,4328425,715128,3807426,1324763,8691472,1069692,8207644,8348414,2673927,265566,8433956,3291862,9425918,5335241,520053,7706746,7798694,1080420,3861662,3177751,3864641,239768,5963748,227974,3570738,1835202,3003098,6295433,3050701,7312412,3355102,7107654,2526172,833128,8693344,2300132,884230,8899246,6898512,4818736,8189738,1677334,8277322,6882320,8124799,1257494,7060756,9231977,1922946,9201492,8007063,1808563,1304126,8182781,910454,460577,9376190,6582424,2762501,6451648,38995,870793,3939125,7713359,151473,1132721,3507700,3277894,855265,9511741,7386947,7302036,8708110,9403521,8209490,8276353,2888549,934892,2724865,8886239,7574681,234126,596403,652269,3112558,6265833,6282607,7729329,7178856,1936916,1826967,9240067,6906623,2356973,9030120,1259396,396240,8252832,7680193,6337883,7519819,7735577,345017,7134674,3186943,3186946,9358189,7556827,8181328,2036355,2005108,5580446,7488543,7476977,8784866,8886535,166060,5171803,8029251,9216615,4254592,251200,9236523,9148858,8508684,3738615,3272707,8303068,8202949,8656818,418141,6026765,7851794,9287720,1029303,3197926,1177462,8593266,3530977,464460,6382263,775259,9209302,2665129,82739,8710862,2842195,361285,8096891,3757917,6465332,6465362,1115835,7460838,4429552,8060271,8683544,9217018,5347727,3949703,3219943,5994732,2049039,2743637,9090394,8278196,947680,557063,451216,172439,646935,922978,777532,7201200,4241074,7884513,5238851,148358,7648196,3234907,561818,6362387,7718578,8794887,4160930,32410,7719666,1806907,4818325,8323486,2919689,2970398,8636459,7428237,583778,5812677,5423833,1531439,295724,240179,8102258,438096,4655747,8274781,6945987,1798834,9089802,68672,1001187,7396340,5154308,7830094,4310683,2294348,274058,1037385,8864039,7445863,1091838,4061078,67085,9000866,4737657,7391792,9526866,7191522,8792474,607124,551726,8529114,1079109,9209355,9209594,4034978,7289520,8221980,4605174,6891841,8877088,6908125,5694187,7536408,4180951,228293,6911774,7451690,8962726,8189483,8945642,3718899,5812032,7828946,8435939,4857157,4196056,1087137,4147843,7744699,4148375,7990980,9372278,3121504,4034213,9434443,2370023,8236946,6551308,870062,3178852,4862056,5515689,9340383,4624517,284772,7774666,4316644,1858697,3809706,9274519,617418,7252556,8441879,7181676,3449329,2439416,8750505,3295876,123526,5127637,76248,100042,7095450,8456819,284756,7732189,26638,8131137,7152572,7152573,3305044,5795001,2420099,1396394,965134,7505268,2709071,285149,1748886,5228863,4211755,8478558,6373305,3119131,100896,8962246,6018755,7713066,3386410,1022541,417601,7452351,242036,192608,8696615,304622,1337018,494867,9432394,137420,4238476,7008126,9024454,8667571,5308934,8805399,461555,266685,1255841,9271289,73136,2078057,8896952,278849,166319,7163342,6399704,6888988,5293496,8261852,866920,120913,116360,355787,2083491,8997746,3814239,512804,7036283,4657760,7097625,7111743,200461,9100792,2230737,4559797,7089284,494528,9214625,1633394,936172,6829344,6474143,4828717,8556507,1715506,246951,3127147,1002339,7003981,2142778,7286909,1346444,7837594,539462,8079482,108696,6847355,7673627,7817403,2823433,144707,8732853,3214639,2100268,7440790,8676405,6578779,9491535,5966160,8302024,4166123,6312795,2225391,576755,538907,8329205,9075142,7325190,3007451,628586,9009724,8896533,3228445,9317420,8097653,9189920,8996367,6994524,4946371,1997379,8918282,905608,905353,833047,2486575,6843654,7111692,7530133,7634555,1625831,676596,4291398,135792,8319449,8110543,587294,6194599,6351039,8314215,4158273,5734436,2601709,2967440,1149935,187949,9271491,8513524,2194566,4060469,8375715,9135822,3898415,162929,8627053,734304,7528813,3843239,3094465,7365435,7559777,2180201,7882266,2479318,8359127,4989335,8983823,1002513,9403512,7638569,8874762,4185997,8018018,2064270,1539337,8532219,8065181,6905630,7259351,7310185,2996915,5958426,4275853,1039617,4966326,2310461,8744510,7364231,1808794,501512,299991,3460129,7159456,9102593,5530245,6897861,811795,697839,6036881,1376804,8967326,9271863,140346,9394431,6547897,7183339,8262564,311647,1282049,6062489,3798729,8850650,9227574,1143492,4854823,633222,2732815,574664,8979300,9054489,8951365,1004385,331280,3330370,4628165,7102472,1121664,576146,1914346,417805,771044,6504761,2893511,2892731,1910196,9220655,1873054,7001997,9140016,3759732,1036245,326907,326898,2584951,1971087,7498693,7094201,1599032,9480563,6808474,6178639,2323475,7163192,3822516,4064094,6393151,7626923,15675,9337928,9414094,9062139,9096889,2203620,166562,9023527,5089507,8813434,192343,9340703,227356,1170250,6154330,6843662,785306,136323,7136227,101216,8014815,1146959,8313033,7094907,6124239,7145937,7145938,5948799,9363781,353663,533243,248112,920944,5230957,3599162,5640782,112063,1177552,481865,9280473,7452168,5963766,1755375,9549987,9445780,6772000,8265827,4513747,554081,537782,8399439,953704,3714123,8523745,8765620,2609935,69126,2820235,4551460,2306393,8388762,8855300,107513,765028,9323494,5596568,2783711,1170034,4680197,8158152,8183457,6520759,8133009,922618,2581945,710383,8020171,8921483,7344423,8382674,6562732,6504311,5430400,1069296,459285,8240039,852040,6534435,4187821,220337,9308774,5993064,6457583,7179984,3089632,72839,220548,8284196,5497581,8091103,7302725,9353151,491714,9053480,6724780,178434,3402496,1399478,6892158,8225515,9920,7415547,8733568,5912127,7858290,7093989,9189658,7202487,4295805,1071282,8396955,9379918,8202950,448366,7800603,978991,3682004,2865883,8589629,3129301,9544369,7955395,2931197,2344430,7731463,3849650,62433,5619923,8935567,346525,1760302,6721180,2959799,2005351,7986782,1388321,3973955,104991,6838727,8114175,7118107,7095520,6969071,6446382,2127946,2160966,9082194,688014,7565212,8444856,4529920,1782892,7136161,9027134,71401,419899,3643148,1077006,109317,6040436,1055811,115940,7678080,7022582,9477465,7508074,862747,9555764,6028505,6869552,4522663,4123990,993459,2024272,134219,8124684,6053552,1921450,282969,9409110,6525761,4611042,8578019,4045466,7006413,9448833,9057433,7379627,373867,5142070,7793103,958591,8358581,7554213,7095790,7477167,9429943,2153586,8172323,6418274,172133,6926580,490721,1968180,258903,149963,7216433,270305,8028992,7577383,7765050,311824,9219339,1542991,7231808,3997541,1055376,7586818,2550148,9014706,5846064,1637798,4110308,2584203,2563465,5361972,2845483,5815491,7403923,6891593,7634873,8946834,3937490,104631,1321856,5858793,8333927,4760911,9321689,2478391,9074793,8835199,1152029,2296286,2145136,8158381,138876,9242696,979156,6850085,3839846,1507280,3544258,2469191,146999,8430093,9550568,8917191,110212,77928,9282023,7649279,6024509,7294658,7000512,8918509,2950571,9309508,821677,635547,7131988,505227,327427,308775,1087560,7125680,93114,6946104,6460157,2185833,3913787,888793,575498,7802675,7045539,2835613,8661182,8159210,8848950,7637668,15674,8649191,201676,8612379,9178273,353951,9226262,6872991,8644025,7233836,1951362,8987759,9514068,8566476,6278312,9522641,1073622,102476,57863,2254757,8745512,9429966,8900678,71630,4677854,4198297,7744376,7065616,1749900,372658,2177584,6066110,810130,6321425,9445065,5668995,4143082,1013184,5652461,5740933,7683457,3261202,2434448,7892418,3271795,8630108,8095590,2934524,2062419,182164,8623070,4573390,1787050,679929,9182922,7045466,7045495,9280290,7115132,974149,2635363,1072947,7892726,58064,8876800,7305855,1218396,7197352,7433235,801355,9225272,9287612,3880598,1859639,8537217,3307597,8473380,1928136,4385137,4166327,9287514,5439729,7784013,9252320,7447037,2295050,9433017,9315851,305273,9460051,6892453,2712349,1559026,6166189,5553081,2145142,7501346,5410513,6357709,6845564,580472,113298,7517607,9436747,7705361,7230781,8619168,4586101,7538363,7536544,7024688,575300,4656797,9290030,96752,759404,7838588,3104953,7209857,4966908,892223,1272977,2803720,1893718,592559,4699016,6630037,6990371,4251535,3861089,7311380,6842275,3857630,7741316,6381451,3102541,8005508,4637528,251382,3682433,2993831,6939445,5562888,1029918,1820362,1035906,234076,8659653,79407,1207834,8656319,9048505,802547,3590582,9033491,8167516,2753341,8753810,2207019,387874,1932904,9364639,8842037,7310978,21142,7269056,3528214,3464593,403024,7823949,2647246,6662713,778564,8602506,4702859,2933069,8931514,8474257,528077,4639613,7488675,9161899,4275352,3685046,9443226,4789624,139932,1990266,387643,9368590,687021,7924420,9312443,8309854,786208,3530350,8227331,9145606,8232641,1871894,7634952,3778032,367375,9009672,7498784,20788,650292,678975,7044001,7190815,7813157,9010168,2551954,7260602,1565143,3931574,1281191,9186731,8057190,7121552,7662891,8804885,9228630,9130509,9130559,1302710,790654,3054232,2665049,6840127,5789670,494462,7678266,8502234,8064774,7762765,8551555,5352437,7579095,3822372,4280572,304891,1188674,4018490,8133333,156100,7318039,191123,326398,6970789,2311193,4121473,6495857,7543857,8871303,5743417,230253,7304240,7472792,4790131,2444120,610329,7887628,349943,8485338,6908133,6532805,3882830,9480618,6384149,8632938,6165082,1028838,2511658,8870644,8760607,329098,2098819,9431260,8985994,4713146,7707682,7471698,9164556,6135759,1868066,6864801,347120,7245009,79884,8311911,7165124,1262429,8780524,5823747,714645,2054241,3884891,6864205,650976,2933894,1000635,1168005,196526,7650659,1057737,7127679,2575107,9303799,5758750,9090610,9093141,1079316,6410930,629461,8642319,6608607,2460365,7896939,193707,7577329,5471328,2144123,9452042,7191647,1136088,1348742,3720933,16429,7837247,7764803,5534136,9389036,7360665,6390443,4068456,4265995,9556908,8608630,1366553,5481210,6993908,8053540,1964922,7201417,2592818,246401,8515665,8739711,8671998,1932808,4558009,4305970,3139159,3416968,4154431,8841269,3485539,6517928,2274404,8780376,9174160,6276614,7902553,3452038,5717891,1076475,7731623,4298837,1645883,3760926,8711358,223740,2099935,6527660,7786860,9067435,5218534,8283205,1401437,7044802,2638486,6889485,8363218,118728,8255181,24822,7755588,76091,7842723,2575955,60550,8769772,5043766,8535704,7800536,2895047,9533295,131727,9300073,7974245,8847784,7811350,9093087,4282012,5963682,7167384,9043814,9043816,7572842,7048294,2574755,896830,9085555,5557155,9017983,8546586,8166158,149146,6755932,8097234,8097131,6383481,271633,7595412,205192,1144665,7116492,6562453,6611409,3838445,7323916,986134,172275,2570953,1107492,5275439,8359719,1409267,2167757,877666,1596461,13338,2882081,4675169,216836,238591,6114067,6106324,6106366,6106336,6113452,6114007,6113674,6107110,6106354,1967358,9403690,7878787,6500455,6496967,82482,7306323,157499,3727275,6919033,6464837,2581119,7002152,3855728,5071423,9184079,164872,944968,3863297,6296359,9223906,7869082,8066368,9290513,7475961,4607858,3324028,5095987,513410,8033512,1896235,4296562,6775108,209178,8695911,6781756,3467365,7114084,2182185,7157270,7157271,7148577,7148578,5699890,6386373,5815632,231499,7822796,536363,808406,7715489,8727617,8579816,149599,4320145,8443678,4125154,6703591,4093123,6389513,6007169,1883815,2198391,8104860,7293763,8668307,7367814,8608372,2936765,8779879,61157,1648951,8829673,1936442,169951,563408,745990,2312534,9477170,7744451,255625,8548227,8806283,76619,2218452,7412811,1730592,5086123,573515,9285205,3165772,7332880,6869106,917689,7623753,4587175,6503565,6012601,6011726,1690564,9107312,9080598,7939880,751264,3886499,801916,671574,8885677,3633095,7679191,6078989,9258828,7073506,7423040,7423236,8482031,155406,162926,460170,3933950,7407877,2586605,9107937,2945477,301546,7349656,8860158,57561,5954718,9247408,7650900,8306107,6020265,9484303,7002474,225237,8413939,3727791,9529854,19295,6880163,8949459,9097722,7487649,9329252,5924709,4230535,8621194,6328283,638595,1591742,1172989,7880027,8763596,204673,1245144,1830657,6951852,387885,7901655,9272881,8550797,68665,8937121,9395316,956077,6414314,1762327,7843298,8086565,7556990,3531586,1903564,188886,130921,1153731,113571,1598168,10274,6304189,1014579,7770856,2072466,1299905,7675931,104409,7818625,6333395,145127,971533,3509203,4042877,8546346,3354952,5277191,21996,9408095,8010990,9437637,7204231,3629810,7097347,8715762,2267807,8756686,8757523,9292546,4574365,229278,2276486,5046907,8505221,263792,7539353,9272237,1792348,2416106,812617,6403676,1562707,6981944,5626325,1147721,7565826,3209383,9314748,9004080,9095760,230530,8874174,7799954,120333,4435918,2735071,1064670,7752702,549684,645186,106504,8205572,772508,285223,8237579,4625360,8252894,6480095,8474576,5778069,8358404,1660363,1174944,6872577,1555480,2792905,7343453,7912456,12434,6662875,1934546,1150877,562673,7886475,2860183,156720,9433585,9347940,6819915,2728777,996763,1312082,8030245,7566536,3420328,5490726,2465852,2519950,8762468,8387071,7177521,3109963,8681157,932564,8583577,1776637,4186117,7096513,7039935,7543927,7327963,7185930,5306045,8118671,3511492,8257157,9209334,7729246,1761,337970,530210,1540633,8751368,7015048,2117443,4390516,2735471,1254338,1266002,2234493,1397372,373243,9201611,169783,521516,4896658,3569247,2289014,4805461,8465927,2145568,99082,8470333,8507696,6187843,9010856,291199,2137795,5463534,581021,1736391,585561,159063,4019855,2069460,259900,895757,1093626,4157690,914878,7414310,3288226,8295611,492878,2024584,5552109,2205213,1684657,4632371,976921,1820620,8759693,2138293,7017229,575126,8401498,710299,6791914,6655777,9074929,552125,7473475,613668,3630572,492563,6565954,6347985,8396777,8262167,7189978,1256459,3653531,8166354,8758380,8691533,9474093,8931841,1535453,488525,1943012,559532,806776,8943839,8221895,43321,5809158,6286091,7874573,9291923,1916026,2539315,6856635,9015704,115309,6043523,6426792,8253170,8701922,7879719,9140997,1122861,1293734,5287412,3670016,62850,6817135,6945585,7456358,6680527,9006977,849337,7022822,13257,498234,6794005,9354986,993225,8853938,7483690,1556608,519792,9029068,4763611,5464554,3348079,6724390,8927162,2934059,8578575,9108375,3866639,1102506,3416716,115110,7597744,4263700,7876856,9397369,7837803,7252616,5789844,4622849,9320059,7294351,76805,7334724,9420253,1199556,3584342,7676452,7010345,2672217,80521,7577747,7997142,5104876,7202382,5170045,18951,423898,8114867,8844479,1198377,2337554,9523743,8432146,7470203,7851138,478643,6826334,2641459,7340092,9171857,1050411,2356778,338295,97914,5849655,3144574,8518782,8034033,203160,1757799,1733259,8857102,917824,6842069,535652,8048002,492488,1373009,75111,339102,8671126,3575669,2169498,8532818,1365842,6210202,6410172,2963492,2878448,3705549,1323476,2997785,9387730,8464099,9276749,5931000,3571500,868622,7664746,4886923,2560906,25429,395524,8641816,731665,2485429,466985,394987,7004514,7744974,8330595,7102132,2892044,8161709,825799,7952309,4814518,8898716,534602,6542547,1568377,7969424,8695050,219329,2828998,6957779,5396515,7117970,8111225,226481,6006548,6944684,6818313,2708533,7397462,2697773,59204,7139012,7587717,680349,193520,8124806,6851125,939088,6955531,2096251,113628,7131967,2413559,1334303,253274,8936928,7928455,3272713,118501,7416726,264189,592847,936355,9528333,8858447,6254222,6851075,7128627,7751031,7652825,2971754,7295259,8619934,6841414,2293871,189403,3094732,497180,7827334,7276798,4805443,4843267,6982653,543956,12754,5600903,6024787,7409343,9501344,5921697,1926320,8527343,496394,6411676,6348191,682404,8555377,591521,9476028,165892,872377,6883801,8407872,8789982,152611,7272229,52279,1956273,8899163,334882,1510607,7302987,4536892,9323221,4449523,4832575,1213952,7726411,1827015,837202,7951569,6922087,8652030,7594980,7165980,128185,9486885,7564196,6446394,70484,69491,4396909,5507325,6826128,2325407,7207302,4471042,8112373,7533209,241454,4254886,1513139,9352817,8352790,4097605,6023077,2926679,2478703,8382440,4138642,7460573,123996,4521547,4169237,6433408,8134628,5012282,8964702,201668,9398162,5447679,9034073,7780709,8700477,8619340,2846590,9226411,1559314,1600298,5755699,9481629,3733986,9361653,7613763,4900396,5027233,3927695,192678,69590,7910343,205470,1162767,976042,4619231,9271717,6520873,543927,9110970,3840038,5392129,207404,88661,1097832,2660057,352531,7021928,6919680,1564978,9394860,6455918,7075458,7140194,1682233,6991598,2028291,1715107,8957035,7215816,6788071,3685528,8625739,6306787,2256305,1999441,880009,9353863,8211494,7092963,2887814,8964046,2790388,416121,523979,2802217,1120188,570482,9276458,2367347,150767,5557179,6903039,7718721,735477,419365,3122005,8736478,72678,6736093,7379317,8848963,8182454,706750,8521276,48881,4197097,7874508,9255300,901528,7310754,7988017,4380055,9020539,386425,6865033,1158115,9153848,3993083,398683,8221206,246390,8868907,6546757,9384799,2706755,1754049,9534017,191975,6693652,6556387,162205,550274,384571,206234,3839702,2036061,1259744,4109627,5977572,7575188,7646523,177078,1335407,2180049,6020745,743770,2115952,6468782,3718200,3272728,530451,1778188,2680949,7730370,1928252,2670101,6962015,1100946,2852167,525347,6535651,381180,7778942,2841148,3425260,4738653,8651020,165097,3228373,2069796,4093354,129114,3069631,293690,8326898,1180857,5009126,1357892,9054843,1015427,119339,7953943,3397237,125472,6113179,1962696,8424791,371407,1589258,7407525,31493,3890279,7121244,7129354,9362585,1313831,2530867,8835939,8185187,9322514,9413911,8959166,4769446,1870541,2343167,61352,8725754,9458318,5342801,7943429,4456312,381493,1979118,391999,5264420,8591210,4802746,4609230,225998,9355454,4527697,9500860,5992854,8726442,4261204,1180788,7095416,9047326,8092554,5793852,8099859,1949850,1910754,1919966,7191425,3394009,8644991,2581645,73848,4465924,2095285,7396940,4588888,7718494,610742,5454018,1156211,9562514,7543479,8520104,9217534,1079142,6980591,8713805,9553251,5067889,9475584,710061,3184156,8931622,8327137,8327138,6718609,7209600,7700464,7462023,7516117,2394095,8053090,5564172,8954078,2727623,8113911,8113942,4527655,2891735,2769021,555215,7060242,319470,1384409,708547,7383239,617045,418420,9271370,317369,7580392,6443890,7528519,3013133,466529,8527328,5298281,5816370,1156799,4324600,7760738,6293717,174954,7925873,1097733,1205216,2141479,5949162,3028910,4484014,1161720,9123248,3560585,7223983,6935526,5681856,7627048,284812,6467813,2716755,4767622,6449198,5507448,8038394,625220,5054359,868156,4131289,9151600,8044735,9463679,6251264,9233578,7620740,9431351,1588259,8980618,8467599,64806,6459323,8859005,4071861,7714974,267554,3731757,9226966,9470054,1284452,4997636,1393064,8583839,2160348,4103542,8465847,2955359,8042065,4717745,8035649,1633187,8760139,7535561,7733229,8665707,437581,3853964,486170,118641,8910879,6335723,8311839,7242896,716073,726018,8620576,3663293,8450808,283729,8027220,7431363,6019907,5515161,6972683,6981758,9198223,6912379,8158569,8020421,1859447,602546,8556271,8183092,2658521,9213236,9114567,1703020,1638434,168964,9385059,8912307,8413831,2836624,3552352,7049156,8764357,9482042,4956051,234166,7879160,6912410,7675857,1324358,1340234,6991634,6827446,60943,5064727,6128280,2214177,5149064,441412,73648,2291138,2995238,7220275,8722342,7231319,9038826,8489678,2644978,2365550,1989162,9125259,1728615,4588744,8990564,9158169,6483395,4206430,7489005,226036,5209693,94812,8660993,491723,7657886,6644725,7320160,5463522,5246162,8252998,8805164,2626120,6747511,8072657,5845833,3895208,7585762,6923369,6361255,6478919,5350703,3441769,941275,2373254,199074,3193513,6009716,5776467,5241428,8064197,8861512,2670509,516407,2053629,8065761,6844110,2411468,4740816,8731470,7754120,3493459,174248,238566,7806054,1006221,8868098,7403479,7219266,367246,8114888,996693,7024921,8530513,9152895,39112,2683987,1824420,5146070,9427555,699651,293499,5604569,1260359,846373,580803,4268539,7740680,344556,506216,2673201,3049633,71734,2333744,2798374,2593817,4540549,9371588,6828731,1121901,1518368,5534994,3929753,1037055,206937,1051179,5657219,7135879,7411747,7393379,3178294,7398595,2682557,2355515,8822367,8507483,8199046,1668910,8285031,2175359,1746645,7298641,7421161,4290045,7738141,6060761,60352,5468859,7601668,8167517,6980295,4283888,7816891,2038968,4635341,7873011,7425147,7327520,311365,7548379,9007968,1569397,6021773,7173586,7834824,2880521,6834664,5333426,8884304,3724728,2655838,215880,6526619,8842881,22541,6720682,7921649,7121397,7194062,7881387,2969783,2340044,7321992,9424866,8943492,395466,9297657,7304629,2516614,7842237,6047375,7989892,8133944,3747621,2696827,8136062,8308567,7860917,2098474,728403,3354052,898444,5810400,4282000,45522,7202445,717288,190129,69985,8039654,146424,386794,1125324,300802,7042155,3599171,284988,1047807,7693168,7398877,7308788,5522976,5602100,471935,6864107,1687114,258230,1214297,4881508,8296308,1911126,566699,7882165,5165494,5165458,183147,2177484,4146522,5931642,9071459,1549486,7940822,7728098,49132,6017657,119106,904801,7346842,7335166,3266911,9560748,4723160,4664306,4574194,1692136,3319987,1758696,5597402,7921127,6336325,8379199,64507,3869903,4365262,6837926,8787696,8396930,2916671,207922,2685557,7896615,9312470,9284081,6325077,5379673,9045507,9035395,926480,1887286,7683747,7838342,9122095,4721843,7626021,5141350,7389986,969271,123755,8182522,7186646,1155787,7524599,9410986,6215092,1654258,3648722,337906,1794757,3609341,8218929,7211152,1087335,7483908,8717788,117867,6870869,8346199,5070730,5171176,7758377,8280046,2119384,7652289,158249,8479416,181997,5433214,594413,171464,8548518,1819231,6362973,7367246,275886,6337747,1017383,4155296,8448257,7741654,4732668,7571226,7742189,2598286,5359706,8006115,8592500,8002987,1010802,1672999,6313551,7320395,8288542,8773837,8980302,7825221,4170412,2059443,8874367,8497198,7347878,1746654,6264600,8504495,4084203,263953,8858135,9386445,8638529,7929025,645762,1217847,4723718,7440286,922819,8677688,7869343,7634756,1778659,7196909,8493903,3878930,3255949,9312710,309063,1860500,9149215,319642,8995205,6267150,7311852,7976215,9330824,5442594,8747623,4803448,9101819,6938496,9178619,8614865,9353347,4162234,2059689,7242184,3244729,9499423,5840613,7129962,634533,4898956,4747974,6246869,6891837,2060316,44308,97684,3562182,729144,489893,1862117,437430,3120049,4900108,7202120,639174,8234606,4996919,3760758,7057677,6300447,74576,9286177,5947998,1088628,8110921,1121472,4422208,9349125,8973374,3165931,24419,77035,7026536,8635510,7599057,8248465,3949019,754277,7063924,4168066,2131681,7550771,1948875,4032047,8671952,676509,512114,4588768,7137921,9273263,9075655,361060,8117557,5305916,1239516,8032593,3261901,7027176,8518687,6598174,8681496,7740385,8538889,8856885,158066,141897,7400240,103945,2932343,4969224,1710178,6668566,1272182,132620,3096823,3279202,4247779,6341943,270286,8231365,746041,5528442,537980,5418550,7114241,953161,6502437,4322329,820997,6914320,1741389,4119730,183083,106437,2141287,7296661,3553690,317069,6940329,7246422,1017702,83795,1056240,8168062,2200683,6933996,5802120,7715999,66447,557313,8517355,70815,8391817,302298,3599420,7554404,4992503,5783718,8521259,107377,4808614,4884640,7454620,8279097,3578936,7645793,1634705,3624557,9526375,49546,7099031,2055966,2469251,2762099,1784260,8747640,1091295,763399,352649,8089298,3785133,4156122,7102777,2119444,743887,6862081,4231114,9562206,1834404,4593370,1931444,1988820,7274692,7307778,3274222,4476937,4419166,8760049,7566051,8235107,7119602,8647692,730839,6870320,6945287,3260008,9379334,7717015,6401528,7064718,7219888,8117619,1139982,7603010,78441,9139756,2303348,8801658,8867959,8582664,5522427,310134,5942679,7512747,2644489,4715246,8286983,8540761,7597642,8615077,8688874,73673,753661,7925161,2481691,269910,6860715,2954423,7869085,264127,7402498,7248929,6948750,4116137,7743714,9282619,7698795,2600956,9028245,1134306,4086460,1795108,4383379,157027,8568840,1215861,5246087,5328503,2365004,1158969,258105,3438778,4732572,132707,1608272,9258216,8461041,873565,5211748,5436300,7245905,22646,8713350,6028405,5765434,9522412,6529580,4742484,8212570,717246,7918740,7483074,9554215,7256658,1347434,112690,9065332,3078361,302858,9118322,3445240,237710,109731,7211972,8361785,225761,8956048,588344,8649639,7152109,7152110,4823854,9063353,4049105,3646937,8618072,8764404,2723327,6629686,8193124,4802362,9199177,160401,9214217,778567,5017799,1522073,6651181,7873876,1870610,130171,923320,3444253,3071176,7905592,1952448,8105040,9328020,390403,1859516,3709131,4292941,3672734,8163574,6832874,8134430,231098,4896709,6689755,236153,538262,8890117,9239107,522677,3432961,1082484,4287984,7757787,169022,6446314,8502362,2180215,6368877,8492907,630709,8952433,7283812,8706685,3166696,8263296,9248560,8502981,3918404,269743,3691898,8626996,4189318,121777,6745984,5432029,1204775,7719044,2366462,4046273,7942859,6891636,597672,8578784,8510430,9381233,8404360,2724075,1091772,2691975,4537414,7466513,2183220,1025703,1013,3940418,5508327,7689261,2263613,9067496,1802227,7390680,4278997,8562828,3842756,8199881,7021933,8675968,4902937,1939518,7169195,7735255,7700287,3038365,78222,1742457,9474611,6691978,2243984,5626805,9559625,637119,4349716,7338751,7945213,1271654,2253113,6850908,668373,1089393,9263954,9259836,7320463,2962370,94527,74490,8606572,9358852,949966,4466494,2899358,3861695,8873334,669930,8931266,883285,1136472,3736305,198541,4102357,81398,6985544,8024867,889570,8305598,9368545,4888669,3617153,578315,1030410,338674,7734784,8428327,551652,2717637,3077155,486572,2313866,9161788,5188580,9321413,2461562,6169126,226644,7710094,8718234,9441739,319343,9053082,292495,8928127,771323,8676968,8526600,991542,7623443,7970196,8166125,7091768,7638127,389868,2570269,8088266,2515840,2606377,2757471,8124256,5839,8880104,786946,228652,5854773,2077253,7441675,141364,983131,8610328,3179122,5526996,1539574,6481547,147811,9039735,7826907,7825288,3085714,3083950,7554677,9252163,6864085,104311,9102308,6358339,7946160,1279610,3894806,634320,7574694,9266594,1867241,4129684,8149167,5675679,59185,5029909,4611839,1710244,2905490,2871787,4593109,7212328,8955191,4808752,9392100,6951928,3437230,8511120,6704599,5571272,136525,7148311,7148312,4949331,6237061,7538075,4829944,7890144,2270609,7139170,6956535,8130625,7472816,7570148,65985,2738107,9086106,9313206,9306559,913900,297325,125953,712365,4120471,302384,7218993,6972404,8035085,8795104,961543,7800696,7800697,7142005,48434,7186478,6315927,7345603,3268108,8446254,1978464,9255685,7341001,3527341,981520,6922452,859502,916066,7442951,6947830,7943742,6117039,8270369,7320066,6626086,7990466,5708431,2392415,4797727,7462309,1765477,8926971,7998903,8678901,7358498,6340897,460817,7282684,213543,7724624,324606,2553001,1603538,1778848,2491786,1133795,194738,743686,7796963,9229899,683376,8795255,8497293,8924146,1317872,5032051,4038134,1530101,6753718,7312575,7025005,9364207,7063291,1338974,6394744,4290504,4856236,908035,8753914,1271948,7014910,6293837,3129454,1827366,7015633,8325615,7271942,6042926,3442531,2501170,9237411,4664126,4563514,8758021,8566485,700908,1313837,7738734,8396902,57016,7341470,2584843,6924268,4726346,326144,4944925,7743348,2632996,2196567,7123999,7661779,51261,748192,75939,79656,8912165,9233363,5917014,8767047,1301414,7820467,1043046,9174222,4326712,7107355,7350399,5341748,9180133,2741853,7080998,7482288,1096974,5576552,3046846,31014,39997,2087041,6329045,688890,2243430,2160006,9035293,5153,1230117,8638930,7125075,1301135,2470367,7961771,5235542,8203501,8873449,40965,9525518,3054436,7952636,7897053,1202718,3881465,9531610,3032729,1375877,7756334,1141701,3063745,4542208,7326635,9294138,6464096,224834,3394828,8978655,120876,1575505,5211049,7703702,6805462,7021896,1539382,4190197,7380689,4098403,7665774,4128418,3440884,5238698,325754,7161513,2223858,305237,305230,2105905,6921169,77054,7286587,8016889,8430340,183111,2007313,6496937,1149837,7140130,8186278,8604555,931510,4285384,9425724,2791567,3588884,1368983,711679,7666401,7666402,241309,3641138,3012890,8487543,7081253,7074868,7629222,8958371,7100656,6255818,1136072,2740589,2305151,6308323,7215683,2781849,4036526,6420904,2855269,828551,1235673,8718978,6976485,2120806,968983,2511289,2291477,7310229,801415,2285036,7920999,231796,9271924,8221134,8565115,7602917,6160591,415464,8670213,9059313,577409,2635786,4805614,3599645,113971,8191317,2391323,6991649,7037608,8134216,8776473,6419960,8978756,3253702,8154419,6438828,7165261,627858,4919815,3257413,6363019,1786543,731751,8543312,8543279,8172513,1844927,453306,7087979,5928960,7301802,4329628,6366053,6894449,8367498,98008,118260,2584647,2218077,7309193,4243825,4400005,4390198,8428790,120814,346906,4860610,317706,9009766,9302363,191348,164944,3846476,6144403,7694070,493713,8486348,490697,7977537,8868049,7561528,7806965,298963,7571760,6756379,203532,7672964,6968085,7164876,5305178,7263028,8268931,1735149,7576184,1365056,5462304,8726285,1778728,2146617,394594,634242,9390466,112702,2472515,468266,7626297,7775831,245762,8372010,121136,4879426,8965598,88365,8574796,4403107,4403434,5902794,2226831,9404245,111925,5580425,8849091,901957,975364,2382815,6991093,6940566,1828515,8549657,6094715,100640,1951398,4160023,4275205,2398844,8236967,9252538,6019217,832510,54885,3933401,3891434,7854300,856655,8092396,43094,6525877,7075539,96123,1539232,1082415,9432641,162136,3895787,1607789,7702940,5794170,7762851,7142870,4730336,9499644,3827534,247009,535823,6341915,2370425,4169368,5458368,7236814,45473,184629,239591,3548809,1542277,7567381,307133,7043833,7423793,906199,2414411,6151369,9415082,6898717,9300643,8738814,9270473,487854,1170883,3492052,4109270,3267520,672027,7224277,9227274,8937691,7147675,7147676,4152264,7797267,3840140,9035145,5839854,7279032,1286201,2946335,1146674,7686661,3957983,2547268,5475501,2045775,7030180,5490549,2084895,8019020,4746400,6846697,2397461,6546451,2709287,2172437,7522915,290473,2433062,100610,8665829,6772282,182614,208339,4082400,4794853,3809712,8042602,1348577,7551244,2406512,386284,2381777,1796767,9368842,378004,5672685,5953890,1145096,4169133,481196,8411983,2869165,8001763,8614484,8446343,7318416,121322,9177703,3708720,4347187,4306819,8712401,272750,1598105,3156115,7107126,1132992,147292,9052630,133693,772486,7591384,2320523,8260774,9141151,74819,8643079,8661960,9094310,8671552,3001568,303139,3856610,2430185,6817015,7382301,868411,7165816,1630772,171451,9434299,3033470,7293621,9427055,5872773,3886367,5163739,157055,2892002,6026375,7005666,191923,7387929,7288349,8067161,5191631,8600981,6897396,435531,8145673,6953808,9417645,6354055,6267201,154327,7285096,778789,2203128,7017653,2794045,8414438,6336849,612236,6800371,7467059,2129389,221753,1165020,7449499,8354546,8022364,160630,4031387,1756212,6342241,8169369,3302683,4672628,4564453,8889076,7737092,2432789,8370898,7834810,983476,2403860,8764755,4558996,240303,6889829,9253593,8839584,9386570,9212593,7641311,7082817,8430695,6418732,3892607,7590233,2296355,8825856,243138,7482122,6950651,2587521,3994136,351269,2398400,7573604,8087349,510666,3085720,6136149,5154146,8594665,8594892,8594522,4228570,735216,3525916,8356821,8948756,8405757,8089259,6371969,9411328,6906180,6551017,8796899,6309617,486824,7234903,1924432,625676,489581,843697,9300385,8007881,7789173,9474077,3099778,8981551,8380213,8519745,4962174,2007607,6060833,274396,6028089,7410350,318279,576497,7808740,3059095,7569923,6959855,201305,8483938,8705588,8716738,9186577,2695053,8294197,1908567,495077,3865976,106431,7689238,9549192,3255010,7651361,153854,195936,7446652,7467688,3782505,5464578,2326679,330721,9347943,169357,8122954,8325470,6077912,6885636,907210,5268734,62097,8704169,9447044,5461005,7159144,7159145,4155895,9236985,284092,7100479,9246462,8126429,6391913,188725,2306189,1357982,3425215,274468,6616810,6012921,2345765,9549003,7326071,1786099,1584632,8577086,143531,555218,3867020,805261,4697711,2543461,7551620,3226225,675600,1145552,7154039,7154040,1031175,3556360,102361,8298460,2266178,2914811,4139371,6286100,9188015,9343491,5705347,1886734,6842367,8556739,7521103,4402252,9131698,3098533,580356,101917,769342,5820924,1098048,8491773,2841979,7690855,557711,1520282,8791576,1794238,7085909,3642071,2656741,322712,5708575,3002849,6482648,8630704,534632,2685035,7102598,888584,7826299,6936380,1868510,5937720,73862,1195722,558404,5535405,53217,8354939,551807,551831,8865289,5169535,2401400,2564527,1991808,8327737,597887,2534806,2243786,9075218,7557163,8570071,8570073,7847323,8260473,196547,8403598,1711339,76293,8346399,386076,325747,2365691,4712084,4295350,7646355,3063694,4281144,7718952,7541620,2259563,1798237,3060451,6892914,362065,3821607,9214806,8986467,1127064,7867930,2902337,2480221,4773232,4609482,7024634,8089299,174551,7927610,8300185,2238453,6727441,2572481,3882623,3852140,222588,3374599,7329461,8309693,5944242,5946549,7933246,7513444,423815,8654187,8654211,9111801,9277404,1996704,7885321,579101,2006269,71329,9371063,338807,8750546,9394249,1768888,9462862,7208280,2660768,7233974,2866687,6021141,9152326,6831578,3309247,7837872,2417069,1164777,7668089,8312126,5291465,2166113,9482325,4455211,8932258,131158,8581796,8101690,5040598,7058202,7999103,45347,2587005,9003685,2011894,9059433,7569926,1711192,7017835,2603461,352958,5496330,2267357,7481742,6206710,597818,5599592,1589810,3534727,3507553,8950493,135763,9393470,9194396,1394891,6551851,8911165,294914,9357428,5500935,71189,5208403,8221001,1198479,2239176,8685184,8792946,1369706,7399224,7580232,8770006,7214949,148848,4720283,4573666,4542157,8253522,1914142,8295418,2250644,5089696,9127094,9114626,9416421,9115890,2217615,4950300,857155,9095627,8106409,246670,6150748,2123530,2578213,3188770,8598781,7879340,9265363,1063662,2272964,6022015,616836,2790445,8632784,2545411,9306280,8499235,8210311,8264386,761104,2270741,2869135,6305103,3174097,4155190,8351072,8048984,6797260,5232241,7900794,273910,7249354,4977467,8870398,509522,3851198,3435913,7406819,8348699,6608309,7614689,78916,1973406,6092033,4207672,1092447,8726787,24826,8783966,809192,3899390,238031,6737074,7894245,1627856,3919976,9444864,596555,2420069,1057365,724725,49842,3555217,1306385,8588075,6931625,6011831,7559203,89925,559061,7118895,2681935,7914652,1006637,4299930,8099745,5646011,3700311,6873205,3901097,4948908,7865178,5232562,7376457,7685399,137473,7969381,8750380,8762452,7611521,8673977,8765131,8198658,3842978,2340023,4704791,3670043,4166002,4294869,8754194,9159058,6676363,2587325,2177084,356861,6579037,4532968,7642131,53673,2184831,7453606,6390585,9046150,639726,3740112,2133436,200511,287395,7118193,1737516,7330977,2255774,3696864,277131,1278467,1325966,796864,7264807,5295701,9453570,8917960,3647546,8890949,4351390,701499,1184671,9378818,548615,3848684,51095,4867789,8534172,6319217,6415570,7653882,1317167,3906299,3530764,4234915,6359755,1310456,6893744,7575952,8326514,5221549,9560834,5249564,8170660,337335,1385237,1135374,3622676,7360442,132841,1115043,1986270,7348469,8537723,2942819,6865298,8719451,6498633,9224296,2516827,1532900,173226,677475,6875728,229586,8479764,8455093,165466,8601854,8886736,633975,9476672,6697849,3199054,4236047,6356735,8836141,5746351,9056128,8897132,9044503,7058401,3001985,2498194,3229303,106476,1844576,1157505,9345514,7524583,4294486,9278525,9268625,8902962,8471774,678459,6322679,533171,2686809,1207934,1887385,5793006,2098135,303151,7344814,2939168,7785377,632727,2456309,8863828,6863583,956875,1925194,5302148,8561134,4061377,2811559,1505582,1141316,1207865,7126541,8011606,2384159,1295138,9452988,9217340,7552889,3847373,7319375,6847429,4089310,7647781,6384503,3345853,4036586,9221036,7571662,9385466,2085283,2056584,4874086,8612327,5083018,7857868,2837635,7744594,5521815,1815643,7014588,9112866,8176262,9438267,7360490,7279643,5416561,9310529,383449,7701829,439395,6181384,569042,6844249,9209836,456816,470387,8593792,9548970,2033409,1150755,6328355,309340,7265811,879907,6197857,7017194,6332569,1564633,2429201,7481918,8211368,8210280,3582863,8614718,7353416,3062437,6187117,9315267,2476640,9334748,8098083,7456897,3785673,1509107,1203483,8574500,6684772,5196545,957817,3999914,2012539,6690406,2065119,7844360,7932635,4290291,8096406,6759613,211640,7197823,7252978,7603819,4709829,8697948,7096938,3850565,6925090,1888375,216368,8447779,2864665,103077,318540,185251,7190182,8256831,6799318,825247,6590914,8880467,4113962,3880832,7062760,4194613,8076882,679977,941518,2854081,1738662,9288344,9233369,6536383,238964,8899270,5767803,2299046,7774920,7604943,3795327,3890618,1643492,7117157,2467676,6993205,1036911,3838589,7295717,8280780,8972869,8486124,7188874,9003717,1385075,3354622,2504971,7288012,553235,380776,4241272,941338,1591997,3389746,5019527,7736954,6960995,8550593,1547275,8000219,9097192,7168569,221487,781459,2341775,8826927,8910876,449469,7019978,9167599,2958005,2085113,114208,8750735,7087664,5754517,8568131,346824,5050378,8235141,1768726,3259954,4254577,1969827,8038321,3709350,1920380,7054361,7635626,524153,7263618,7253686,5586821,4634336,8754008,5083156,7377473,5468055,1753797,8833387,5896812,875569,537740,73190,7774413,2603710,7277462,5263097,6514256,7806742,7345214,4201471,6277931,6471386,8324379,8642110,8030995,3787983,5922912,154190,8611856,8549274,7497002,9356494,7720034,9537566,7723470,7531327,7696837,7757030,4643447,6981093,8661125,7654697,4754613,1650676,5708524,7973178,3763341,7355109,8038390,325395,3839849,1003074,7932893,2994095,6033548,858202,8620215,7647505,9121864,819350,7655610,380752,6467741,3318580,1941272,6371545,1047780,6335581,8485987,2487865,8208258,7647354,8704718,5954592,6189091,8571304,8385135,2085265,3991370,463131,2938604,2065959,4643054,62821,7020146,4193641,264675,8281770,869170,856331,9530121,347428,2281004,1229067,8596597,9100811,8675690,7327619,1666297,3630953,5327003,1346870,59010,918457,8309719,8805661,7353950,2507092,3875726,7234330,4295933,112606,8507757,1109889,4129360,8083164,1292006,3001811,7677145,8154715,4737186,2825878,3500461,9029744,6079676,6397068,8804956,6503321,5965755,189012,733617,5570165,1154913,7226201,7534093,7728097,76311,7790311,1120812,5909271,5332796,9395624,8752254,2138602,8315535,5786058,7755043,95698,3849257,3753243,3368254,1087299,5402407,757297,1296200,282645,8746367,4112165,5535318,5275709,7835937,8713340,3464269,544832,4159312,5416780,9175003,9174973,919438,5580845,591890,8264297,2373686,5709949,5023195,7068307,37404,78656,730107,9441630,8491862,1027626,2306246,4118776,2791297,6704263,44464,4316287,1121805,5934138,8776360,413080,5547339,1881904,273797,7269043,5749909,4090414,7103899,9447377,7255185,7660283,128301,2299013,6129708,3847544,6620650,1439927,6429242,2187573,4260889,9337024,2179489,569633,4991222,9543866,12928,7872541,1095960,7253291,6810247,6441950,8700379,4117187,9388260,9089046,6171895,6942759,7834818,8413120,6583330,3727008,2092519,2332334,1981329,7854836,7063437,5990409,1851980,7258412,7778938,7902805,6484550,4870066,9225038,1010522,8008272,4897327,289842,289861,2443895,8818692,2155230,7089386,580841,9064074,9371306,7848522,672570,7241865,8701921,259619,7773082,6845003,936352,7842491,5454591,7292914,2552677,516725,4353658,4619219,9160680,119111,8105526,210056,8496083,6104023,8519934,7617147,4096258,4087531,8533531,9154245,1538689,9435660,2961293,126290,31653,7973077,1689826,3024227,8213953,172576,741322,6997041,2826628,1763731,7941297,7379453,2286746,6831695,8232726,4436104,8582140,6224848,5268254,933521,2050503,8743655,8513784,71568,8542178,8021779,6905148,2598232,69325,9160599,926530,8622568,6331385,60740,7919582,2967143,4997231,6127407,1014860,6928757,1923890,554177,433932,1777009,1095462,2314697,88970,7039547,1163013,2800633,8188674,9079554,1121010,7280454,5062054,8438066,1241991,7358925,271555,7082306,8932009,441417,550490,3707787,5024737,4060130,4157647,813026,7547843,2931155,8988853,2074953,8468877,96944,146135,9171161,7150489,7150490,1346204,6358719,9493167,9068647,281056,281046,66313,3100393,6976745,548891,986524,5272961,7961180,9058824,9331962,7038507,8411948,4625969,6608967,2760641,6027343,7675772,4398511,2692989,8557156,7835833,253816,9445720,9288757,66654,6867958,63793,2939237,8539244,9196851,7915371,1904554,1338557,36530,7538350,5799543,65612,2237652,2242389,2699377,1979673,7992302,6539935,7402253,309527,154684,7115821,954301,1191705,2664739,9186033,178879,2112055,8081518,2299586,922294,817501,1582355,4299305,7578875,53427,6952860,2534314,2410862,8353961,5988312,9202513,1523603,713532,4562509,8030158,8740306,777505,7498152,6100363,2672331,8174499,5939733,2184060,7262411,5986455,760313,1762936,9431482,2950460,2076621,9045979,7353354,8499533,9535945,219276,64673,8906936,6967071,5086582,8616993,64253,326886,7846907,7199800,7981585,9374988,2088259,1759104,7018175,8034579,446790,9344852,2504695,2066535,2958425,9045327,8052960,8584956,8270497,6540113,4637723,2010955,228616,7975362,64720,7959390,2498608,7933050,7230196,1784089,7956750,8252026,6257678,8062511,251010,7656202,7777490,1966560,6104575,7871842,7403705,7307683,8066565,5111083,8823888,4288307,8027832,5622422,78902,230294,548390,4317985,8438719,2697991,9153741,8162754,311224,8413716,1161310,8194593,108473,8830450,2511157,4306774,8234988,8234989,4830202,4526500,1904650,8132379,9443820,9075981,2554231,3418474,5927925,1201143,7867231,8755707,2948594,3664334,1038282,8719943,9429732,6853314,1279211,4273273,288677,8373083,6611823,8250543,8930659,3914366,1521176,4020812,7861181,7653231,1627994,9117595,179932,8601321,3013148,8525609,6023479,6994826,7849005,1193079,827323,7405289,5988591,1965726,354842,605270,175616,7903958,544334,7789511,1895890,7972578,7449112,1272119,7726048,1296137,939068,9466529,9464935,9055748,3528283,7996134,628491,8510903,6817123,1178835,2982047,8644778,1128944,4492084,459244,8700099,1104969,1092600,7848581,5291915,8980879,9439961,5027866,5652644,8539827,8226768,8907637,3387901,1716613,1970616,172944,8974644,2541727,8320101,2588303,3640682,4237273,2106796,151948,6922131,4554511,243317,7083951,9263486,8989709,9366080,7019327,8882304,480962,158824,836372,6193765,9020516,4280863,636264,277932,2005528,7042996,1914804,209412,6070700,5754022,1593239,1026789,1599245,106151,7473847,4611,1385222,768739,4637507,7421303,8831735,8098226,267167,8161935,6892370,4653233,3782994,17401,1538041,7510819,9198248,2680421,277863,9157111,6313189,3306166,2714589,463415,6847035,5050879,4578424,8171564,273180,9120008,7193019,290639,7686483,6936541,4685498,1201389,7657512,3081697,1160674,913507,6794842,2341997,8891334,9010009,2210496,9496405,4284555,5256572,7404255,5344802,7318288,5326169,2800042,5932242,5012129,24842,5715797,1901404,9045884,7867498,5602892,1221222,3966248,7164252,1827336,7028963,1173276,4949910,7206872,7213109,6882229,7030859,3585086,4294628,565530,4281660,8258912,845273,390451,2655289,9005268,4152809,1213715,2692581,7512958,3902624,8504665,2168628,541277,2899436,7653559,8552357,6929766,21513,2496169,3615335,2858956,8536840,8921721,8383945,7326925,8963125,5113045,7803163,6764362,5525940,4763599,3648254,7071633,2668059,2777423,229857,4629623,6346485,7711542,6848057,739502,5901504,7327670,8511760,5977584,6819067,1348022,107532,1401512,9428750,115240,6320381,1903504,8489684,324575,8551131,6901302,9505255,7789676,13133,888388,4898914,2601829,74496,208640,5686843,8887228,8637771,2494363,2730101,76529,6106333,7103352,1511969,1178370,6938574,6943610,3533956,1361228,163943,519009,9203987,7302357,6864538,4185073,8751227,915976,8587498,6803641,3115915,1181872,8463848,8102708,674133,8948439,714658,8910458,3707904,8539746,1670470,7752391,6091631,1512299,9206347,2794147,541325,541347,40551,6511160,4852642,2665635,5108029,1972527,2097829,6019795,6965970,4121587,4300444,2104435,4024724,7070719,7201829,8293311,1378217,3761631,2535130,8791241,1638137,9449882,156507,2625922,9228459,8862527,6004226,60039,7819725,5713660,9531018,7714460,8744989,55565,9165909,7410649,8642260,7536158,7301844,2622261,1043025,503903,6610298,9126221,2336762,8257719,7869447,7723191,1808758,5911032,1082844,8778490,9149191,8863001,21960,238159,8522681,7491601,9376127,7797861,9385573,927613,146354,6875352,2366342,7897926,4534633,1749042,9408818,7726803,1664575,9496846,773696,6182545,930064,2407652,461834,9123996,6940833,6545469,8619280,5636678,8479868,9427783,1006017,9381106,1398485,1548802,59843,742517,8005991,2890556,9550025,3822936,5687985,2044500,8974480,8986007,1222386,136526,9507637,9255660,7636035,6651046,1286330,8741497,5650265,6388879,2327135,8947600,8106507,8691287,310206,8850517,9494985,6600316,4281006,1914758,7413753,2663513,2283839,4424593,8038688,9082160,108720,128198,5478612,4170195,7424063,151160,3350647,252599,1018932,1778362,768077,8646729,1813912,6003224,785104,6020905,5771427,4778281,427859,4737177,5074723,8750715,5829366,3573687,229981,6800527,4633910,8480080,515075,3146227,5227942,8853973,3823020,6080252,215109,3765672,6038858,6365649,4603604,853900,2434679,2727587,7669324,5373124,9514545,8320039,5922453,3403765,95039,5407942,7747440,1771099,330526,9105519,8264853,2879234,2117293,4493722,2799991,7788026,7514752,732948,6978721,6503815,135357,7283009,4168087,5834640,7891395,6454358,2798482,2941274,9220209,6426778,7597236,9469322,9106919,7746156,7651340,123641,1946123,8318641,972340,2964494,4823926,8143638,5159161,6938097,5602910,7692467,7796562,82676,4845427,7675291,2485594,9118148,8545267,78463,7032751,9217605,5107561,327239,6914480,6863731,6646849,2312618,3605585,289284,5092459,2464802,9374875,7193438,2357297,1200033,9356845,2318231,8954897,340300,1263482,4272184,1736409,2551243,8894733,1945553,2878976,999735,4982846,7103867,141191,498614,464558,8830624,3772392,8102240,4138924,7667286,1861283,309053,4265212,1727085,771337,1392107,5000318,296754,8194377,722758,8853270,9121361,9377470,2129140,6568513,73171,8128871,306076,5795448,7895274,8426341,6479552,6373683,7967863,8094898,297487,7010365,2139292,8709178,8463919,7610710,8959918,7292075,1576078,8913798,5877699,1768828,8952883,1205707,5658224,568247,951019,6525203,7966137,9369225,345254,420037,1734939,5133760,9182310,32905,76064,110906,7394703,8157177,1636295,4471207,4639169,8282603,8692004,4720538,4085681,2239491,5411509,8957350,5294231,4572970,1655782,8106741,2175251,8678362,5512308,3169441,6459785,9178089,4169788,7943806,9540526,7025845,8715156,2743761,4298864,4795114,756430,5096818,1102530,1037127,542933,2013103,4377109,1364210,2749895,1337555,6924419,7101129,6017505,1530134,329029,5356631,3387820,2233212,6873717,860212,164717,1315859,8915313,6325393,8207445,96004,1182153,9392372,5147237,6387255,6628231,9533704,5477616,7770885,580187,6932423,8665259,3276265,98656,3806118,6382311,1292147,9317165,6263196,9042483,7741661,73096,819547,1232061,7504899,5558976,8223441,944467,380221,7730621,6819987,9440055,4011794,5387401,727537,426267,8677487,7380330,8610219,2222529,721000,7326359,336028,2990732,7590205,9383315,4133980,3664748,116010,1705111,818524,7120316,9123000,3481807,2202981,4493893,224088,5271722,7363279,9378966,7539901,1071414,2815432,8945979,8303317,8988176,1206103,8088659,6994620,9387785,8409721,395229,4642691,3695496,94206,3762048,133629,7970921,4207444,8922657,1540978,8220420,8111476,6635086,9090416,1867733,7475723,1730352,8440516,2407244,7025218,891077,1836945,4775650,207118,6900091,7496295,8147902,9455366,9013030,7525719,7345990,6092216,8462943,1967757,1878775,4858054,8506795,8693072,4405330,256865,6996627,156820,6540687,1314722,1623878,8175014,8537332,7252885,5329055,5328956,4452112,9082736,1330364,8730969,803663,3678359,8952268,7661704,2429810,9063325,7118097,336138,8176769,465789,8079724,9229094,5506500,5427298,3476047,7711479,4296210,7092507,5317388,5175734,613617,392464,7899017,6153097,5435077,5352269,247035,8216659,1082622,9322840,8660584,6586627,4130353,7044359,9226614,5987607,2097139,1967514,6458549,23930,7002384,6417100,4967508,7011002,70879,7871729,2621661,6389723,305535,3844520,8449111,7262328,6704314,6961474,7767672,9494055,9429459,9322183,8629626,3569160,8877300,8892284,59334,6689776,9197964,1110570,1167951,7039915,1952244,683391,1271915,7692384,3462886,7804629,2736875,8103023,4372312,4374256,9149415,7911168,7927728,1982445,405211,105973,4322770,1383611,271291,690732,7640470,3571452,1281536,8006487,6308589,5496123,8655610,9433683,4761292,6914219,107770,355952,577892,8888432,5992656,1621511,7072544,2019472,1070481,7968096,5590655,57083,101231,7896843,2576879,3873203,8262845,5390437,8220122,8036316,4596547,495932,5869548,6383443,4555396,7247313,2809828,3614543,370111,3991517,4714598,9452511,8706749,7833137,6607662,1062507,9553666,6962852,4013618,7825145,7825223,575114,107088,6431112,9197070,556134,2266298,7072696,54449,5650448,3646268,8581662,8558039,7701895,9277325,2026924,6878852,8730521,7278845,826834,5310470,9086414,108268,6525799,7075559,955078,8682367,1273943,7206319,6906931,1121922,7046170,5373211,6883659,9142310,373348,3499762,3072673,4471258,7348409,7001014,7814460,8870052,6329241,70056,7756520,4100989,7210664,940868,2612083,2958323,7678754,8310817,1856663,9541768,8852949,7893964,6828889,7244871,1810693,1839744,467072,5136763,8034650,6822177,8333662,8381326,2210604,477332,7042540,61595,9433414,2773067,7756204,7162534,7863844,3034367,8159983,452712,9472790,539271,8560184,2090050,6417834,2203305,760264,7457526,3291958,7945489,345968,7432217,9391818,1782961,4883464,7355559,7301589,515780,3118444,7307247,9217656,9217657,186027,261386,8910741,871396,7471629,3896429,1787830,3216220,7631881,265165,7985972,7359430,5417383,998229,901576,8504636,9342718,1812808,7343361,3274213,8826295,9233130,57597,606773,3365773,8884174,7192454,3649298,12456,5955414,5164855,9313242,5312462,1850873,6938609,2790196,7309473,7710687,6772228,6878087,7040539,3053737,2052804,3421495,7095730,8962161,8371103,7700849,5556360,4105507,7609541,4803055,9148826,5948721,6608925,6873571,3353074,7696782,941773,7552404,1000077,5995431,8770978,6954567,2002921,7070520,1915988,2092798,6935480,1103427,255343,980443,1054338,4411084,8495719,4247797,1087290,3903329,7692654,6772531,5714488,9297401,5817864,7433940,100026,7278072,8272770,4604140,7030129,90186,9118145,990358,4151195,8410306,8672819,222650,988773,1018800,960355,8366797,5930508,7630764,6468542,7927345,8130176,3985649,4381030,6952615,139951,6971179,2163971,3024713,915511,63626,1667755,6893294,8341355,2679379,184635,945892,9261232,8832448,3051238,7090281,7575283,1820437,332348,933251,2398787,4281100,4268965,1290926,2921261,6662386,6990160,994896,7773640,9525125,4044605,6740791,8226702,8791319,7330829,6851748,8031081,8349703,7178247,8042557,8715109,4885393,4531957,2402654,7543497,6937557,7710730,6494963,7927296,8277333,1842072,8472435,2082947,8403084,107255,4111238,6588847,5799930,8596687,2791441,7065377,135809,8399728,4021742,9461045,7336158,509585,8184565,2327702,1319897,2092609,9286099,8341744,618030,7134420,5263160,4641845,9515264,5519613,550676,3725973,6836046,173409,7442855,6880836,6828968,7122674,6222109,3454663,8204394,8572668,5954196,88893,112781,7926824,1677175,3216328,1240668,2997065,3900260,7014073,157955,7865364,2358881,49592,7771098,2765955,232694,561567,7461988,2558398,7255683,3915845,9163632,8754876,7960991,2054958,681870,7748916,670062,8036902,5750011,9500707,7706636,3469678,2125126,496097,94856,2096566,9417642,1630295,2997377,6080873,102021,2424878,754084,868856,922633,9409866,2583977,8776915,1780639,6825814,278015,5290490,3344002,4762267,258839,6311717,7449825,1951344,5923398,9076041,1269401,482382,3920165,1841568,679656,463838,6974907,4238170,6826951,4863001,2079575,2606533,7346318,3720147,4540939,2713379,3123400,8917533,119390,2114506,8614648,3555652,1507391,2027673,6855315,9070435,2219979,4028687,124601,3953879,681465,8698243,8700847,112730,7703109,9520233,328062,8373958,6561202,6391055,4160114,785425,8615913,7276844,7289355,7993185,5627984,7867389,255920,511094,8281726,7467124,6745207,7603532,8154672,7867501,6709963,3925391,1735305,8733494,1522952,8312457,8675204,2313536,3428251,8661319,8661266,8677712,2053617,444189,8421631,401988,594330,8198717,8207656,3773436,8541512,4143583,3241822,6263985,8475828,7419952,8770010,6803764,7773327,8925809,2070471,59343,96144,80281,8484600,5265338,9222708,1268372,8261287,4291395,1082847,9141035,5288921,3429286,4374532,7940331,5565441,7353363,2760293,9515071,1722412,6208720,6723763,2102488,3293314,7734462,7734454,7734504,932374,1985010,63215,7882478,511286,8430919,2397143,8088816,8840432,2712809,7198471,9117116,1726254,6351795,7084297,7125228,199969,23750,2601511,52978,3778926,1827513,611567,7667907,3832574,1396961,7166605,5574830,7153727,7153728,8151622,9374492,8651634,697188,209509,7374289,8745859,759931,206171,5840526,5864391,994842,8174521,8695942,3718572,2235315,7794391,4582054,307991,9265867,5683788,2273894,3624479,4042655,3550495,2327132,7554993,5652812,9544585,4813192,8473497,1150837,309187,303942,1072677,4158761,9555934,8487126,3500656,7830385,7337084,4166491,2391086,840278,8519896,6705457,5174882,869441,2506492,1967394,999135,5521314,8463921,7791165,4228846,7878046,6383025,74341,6377409,6918129,4799089,5674911,491649,2165852,7469253,8945821,6909244,2372471,3757404,2191926,256533,3839798,5879493,7965940,9355789,2136802,5989920,6273075,8723599,7087445,2766109,1395323,8892127,8704357,9328615,1724118,2965685,7699672,8705675,8448050,4114073,6748948,222010,3451546,2635717,6936677,9262774,5428180,8408980,8006539,8959333,9254780,4868101,1827816,7515997,8440879,3098983,3442600,233124,4219180,226749,351062,193877,8529545,8771665,8231455,515516,1765900,211076,444708,787093,3110536,9469456,600119,478298,1980171,379522,3547090,8605705,3951290,81448,5944560,9300195,3578924,601383,2254949,7887996,5532744,5082106,179861,2641420,31576,8579921,1543870,9358759,1622135,1004870,9184665,1161283,7664585,8898266,2759261,9245866,3825112,288536,952942,8407492,7044486,7278325,3847730,7814031,3024440,9007588,7683378,7513531,8157192,548144,7888898,224585,594765,6029959,1822515,9121020,7641060,1171815,3914156,140035,1800028,8657657,8774550,350540,5234942,2886140,88483,8515866,7527513,81412,853663,1219485,7123845,1780636,8651826,8219509,9183870,6393953,6067463,4295100,7717802,8790638,2130553,864724,1100718,1249743,8448772,9124074,105269,1103010,6267891,5116492,593672,247297,7527711,211290,9456344,6429334,7161634,8877160,120488,6876455,64488,9275368,8100421,7556556,2716481,9288738,1818163,948265,110816,172063,7965724,321969,541889,8885366,8672697,8754695,4952145,7869245,1086009,8058779,8187783,106867,256048,1240179,996672,8365562,6827786,7724794,7320812,282062,397428,8302265,7216643,4290129,6633697,207204,7028105,3861536,1341056,4268824,2511604,328307,471338,244582,7589554,7604950,8039745,61082,4383373,3667688,4410253,4478083,4035080,8196294,6166708,8318039,7271295,14335,9210365,9397939,9308363,9333170,4363867,2178566,858646,7962934,7754965,345718,8387039,249506,7857474,838699,8978841,65613,3259579,6947481,4759387,9045552,125266,2993756,7715692,8677923,7313555,8713407,3161998,7724301,9364651,7305620,9467614,1527722,5015720,3374935,9182294,7535439,9155004,7119606,2006761,143596,2530900,70104,8977314,7356273,4158348,9228674,467294,8513479,988258,9469571,9008502,346309,8733642,7719352,8888561,7178787,2552575,1764730,6377383,8162653,5789841,7143784,7143785,6026631,1206430,8050871,8928024,7920291,8701532,531813,2837443,4761898,1953165,3148705,730530,2233029,9002628,993084,8439076,1368896,3600167,1919166,9255764,1116126,1599311,7656910,617424,3850580,1936142,4595314,8276255,6209467,3105916,2918009,7331549,2800216,190983,446844,2148792,342557,221078,7281362,8286316,7700776,2705115,8670779,8380853,6684979,694929,3877910,2328644,8180515,3348268,7992306,7549842,6130494,209511,8981645,22475,6649933,29057,8040135,8040126,7643915,4132021,124884,487836,354251,5936949,8113250,2085295,6978285,2749071,6987356,8767304,8768013,2537956,3932573,3442876,3578429,7014578,6027875,2569960,3220333,8726210,9206742,623697,208086,8436009,6846828,2290982,9525500,8311669,3696069,7592339,9279915,166918,5125132,3930692,6730627,8564006,2919596,63732,8284716,5826693,8881640,953743,1779310,70125,6875425,8029288,1335545,8851172,9325725,2870005,8268873,7126066,1172460,8279140,8836986,6832662,201508,965467,620537,7838510,9487441,8123948,1149771,2112622,7332753,8979792,2614678,7919366,7753554,7184887,3826394,8223638,8223447,9214787,9102477,9138816,8522923,7975612,9193342,7512236,112794,6990151,280590,6373319,8859646,386806,5755735,5033497,2577319,7380640,145094,3535447,6377851,257806,7072016,195549,8662465,3083932,721341,4358635,1382552,7843237,7992103,4964376,8131576,7815744,8614877,2280287,6624955,205833,922615,6263406,4802884,581477,4169061,8627323,1211300,7112760,8322766,6890579,7893642,5775270,2462927,112360,612672,9130284,9351557,1302746,6874843,9038492,8417000,5873880,4721444,8884430,6685255,7785635,7948037,109310,5784696,8905969,581708,7341932,1079586,8424238,9110065,9415828,731775,52270,9520334,59399,1894336,1928888,3290308,7521786,6596377,8634980,7565621,9455302,7592839,7234044,460689,1525346,7763161,6320875,6886262,8013423,2570317,188182,1344197,7700511,1223283,225520,138520,8570393,5243306,4540894,4850608,8721702,6867665,9041824,5266304,8193274,7527682,109213,9480378,1388441,5499453,558527,6338609,1706101,151354,7174559,763645,8162507,8276204,7763313,8749546,53534,59057,61267,2649151,9048128,6833521,8308777,16132,1214831,2775209,582506,6820423,1170694,8447608,1707688,7095382,4163739,9368987,8907272,2354996,7140163,7623227,7455146,4224820,7572511,1344527,58031,8816770,1156471,6024033,7209268,3895637,7502205,9552564,7832647,8621066,5157230,2458112,1629611,343194,6500069,8002339,3501646,336223,2346194,81005,5774346,3814635,350603,7497033,6061895,8919275,9149475,4953339,1903687,1566322,3275503,65856,7639996,330755,3748752,770773,8321881,7716733,1074624,6096113,370018,4512493,9452597,128515,1062051,3831560,4735914,2138056,8173753,2908865,8180170,8179576,8179731,5103742,8335425,5903613,7803288,116506,65139,2205459,4499002,5712679,3136687,9119977,286717,1584332,1820464,9046915,521384,2665331,8561264,4157392,136428,472973,8658710,471848,2774539,16584,6512195,1900207,1012902,221532,987124,6383171,3450556,125369,177734,2125351,8573592,2121916,136975,6113656,3903494,2715823,7929033,353894,2683243,8286597,9356963,8405675,8306072,950063,8497605,7306253,3363034,8476904,603701,2901782,9358117,7976733,527718,1132307,4138954,9551982,8236609,8647523,6222058,6951207,6967034,1164789,451114,1219200,8202155,8858953,330100,9370734,7288357,4516069,140700,7009080,4092724,7530988,3998795,1225680,1385276,8813378,9426338,7443958,3669965,2767105,1560370,4168774,2407496,5359286,9024432,2049648,2607577,5093743,123326,7660452,8697175,9369951,4387846,4355497,5040025,177922,8489643,9363484,8146864,2799178,9080111,7192106,8688849,3719661,202131,8334799,8034490,9153869,9153870,1210766,2959499,9259009,1299722,717075,8900732,113407,8282558,7393172,5263688,7090313,6986739,7201403,9495018,7891575,6609163,7210665,7512303,152798,9023391,1243635,8713260,6448526,7160863,7177638,7789832,7796782,6843545,2610,6193384,7598042,1908524,8501746,5715899,1101972,8021672,4323193,541334,2729749,419146,3889094,7004030,9022403,8308205,5527482,1085406,1796209,7750551,8390895,8354522,2864002,19561,4577863,9017331,9200672,7674253,9180858,174128,5405218,2486503,2486515,7893583,7247566,7016828,2277185,2377460,9525999,4297625,7573701,6220024,9480863,6022289,80580,5368158,5421916,7551178,6024303,1596152,1824591,887311,540716,6961607,9555823,5114149,8892945,6542817,268394,9184701,9184702,7042682,61260,7808331,7221402,1186775,8871245,8917829,8926417,3382981,5303351,8499378,1674607,7793929,634137,279333,8902081,7246009,6832965,7887704,4611488,68654,5219275,8791413,2637340,8435502,4202053,6020725,81668,7954710,9284685,9285644,6026459,707173,7957533,3065752,7946656,4748072,3509041,6876368,9397809,6491156,2337077,331610,8523698,4643486,2578979,9270018,1724948,73532,719700,9124441,8628900,746392,8418658,2751395,1376357,7410777,6615328,5424052,7206977,2716653,1742538,2165252,93337,8384130,2144151,483605,5820090,505565,9335207,8750593,8898880,4497553,8736140,206397,596042,4506874,3205729,828202,7755803,5289587,4567126,932131,79203,7339131,7548048,4693691,7654221,8294032,127300,1326467,2122606,7761183,8135262,8135150,9521562,9193185,4107169,2429552,9274548,9267220,2803795,7823693,7365855,3381760,2110093,6608873,383068,1156601,1647760,6205552,8348943,7186703,496331,8096010,4438372,1849709,8603431,8105490,2706429,1191459,7988411,7977376,5958003,8963985,110670,6827937,653691,8094495,7496311,4156827,5661737,2450855,7398005,1878244,7275893,6055238,910775,8306932,8306969,7363341,7076678,6909353,8017799,517655,6581299,4281819,6451276,50855,4941658,7780590,1841073,846946,7861710,8817097,191145,601647,455532,9521327,7561186,915910,7159050,7159051,1077759,150308,2409662,7845415,7008003,1510586,3337141,4192114,7903235,8002729,4183291,6849172,7763356,1318433,920125,2429852,412621,9309104,9460414,2740409,1873381,484904,789593,7391123,9203973,3637184,2387378,6784228,4283711,70764,3942440,8142052,952006,537173,7067048,1374761,7558016,2232198,7289319,286315,5127739,7026352,178583,8977293,9191365,4112255,125662,8463625,8804348,6782863,6631279,6926661,6907424,5604041,6964484,274024,8497336,9119925,8683542,2707567,1548025,105130,8227503,2233200,6228454,9017831,8964682,5338772,4401562,3732684,8291553,115777,9333572,8939085,1876312,2029017,5343890,525884,570221,1681381,4968996,8577101,4217389,2370194,8164089,391015,7757149,7754734,7535503,7378498,644814,6569359,7053724,4030601,240946,7421,3836390,240777,285394,9016799,2288876,59901,1110993,1939964,4358380,1002194,8754184,8833947,2621505,7628723,7607531,111498,516107,6486161,1201485,5303492,1629194,9253812,8229342,7344736,8842080,8995199,8773482,8715348,9467050,2424764,2579201,1595102,7642553,7226709,2177692,7881776,8413043,5410309,2362523,7689827,8995940,5799165,3020639,6964505,3469072,4282024,234733,8348116,8093870,7014984,2144210,7977250,2100199,396490,907261,103666,7052203,3505270,4740090,6794023,9445165,7817570,3248581,5649836,6808780,7995224,1300166,1324721,6821279,8440133,2409428,7854248,7663431,139304,6859722,7100971,1004790,2362823,9379749,27584,8588970,6491066,7228008,100942,9467669,5242874,720177,129208,9349388,7340823,5333258,7843254,3051154,251203,245704,7168219,4112012,4252978,1225860,5172904,785122,63673,2820187,5687381,9272832,6999576,2386145,9323658,2564290,4446823,2620303,2918000,2316914,2315960,192373,7919346,7461070,538938,6484508,432015,8037752,7413444,7429949,7429950,2844097,10394,10401,5772498,7245155,1902463,9397963,5791932,2832514,212124,5422769,7519083,1724916,6929179,6846259,7786996,8727826,7720250,6531667,5049892,103383,3345727,6748606,1624697,6346625,8034654,495123,6893474,7715294,7279615,347715,1001427,4138858,7025804,233423,9356458,6367109,132300,2953568,9470532,8335284,3081220,4004282,8269611,1141740,8010098,5493006,1558624,6107845,1040691,31327,7031356,2763599,7646133,2156187,1321763,149115,1004820,5184734,921352,5965518,314576,1680046,5057683,172289,1833150,201763,8980779,1304105,2095207,7983943,294275,1771768,8461087,7574476,1330883,5748439,110001,2324279,6587953,376018,9124909,3483454,4795258,5251631,8839871,7137995,375679,221319,19286,5910984,5484456,7318640,614691,7687113,5149388,9369887,130680,323200,6408716,5939718,7349759,8453447,7878452,5105638,7289209,7865293,1777261,2718729,894737,9393309,4461901,7066407,9231081,7514765,283266,8760608,253002,9379067,308352,9219074,4600258,592704,1103730,1294655,8499330,9442437,9442569,4160745,8525962,144837,4362556,496142,7575159,9454962,8033398,2935658,81781,8738415,8188682,1667020,5923395,124245,636927,4566376,6432334,8275110,7976024,8831634,9483375,2565760,7993580,7755495,2126989,7398884,2047668,8927690,7929044,4329523,7081020,3670169,8353825,24411,8130933,5987772,7585043,1141545,5813628,5023255,7288164,7386827,1393262,1593425,1258427,4295480,9103903,8848323,7085719,7553523,163309,8488320,9037402,4857211,7026800,7941114,4287398,4958001,309632,375211,1080234,7392467,4739241,620451,620439,340085,7197485,5199482,6938649,1321388,8361218,3795450,7392370,7352749,3792372,5366892,503922,2718389,1807729,9237364,2168820,2093776,2834737,3813303,2523052,2836960,1859843,542894,4857604,4230562,8433461,4209637,7033344,1079532,7815617,4786687,4355638,1123761,7057959,7207555,5766495,1626764,7676134,442782,8110725,3422296,9516009,846097,9468865,3413248,4497682,558746,6193042,7078598,7078518,6414354,1920568,4024196,7710691,6703582,7715335,3372874,1638758,3006026,134848,7997726,329886,66044,4655294,2104744,6322837,75668,3286477,9069030,7188281,2335865,8878237,7615964,253837,60298,8914534,7378594,73913,1563097,8006699,8094108,930988,7316571,4752879,9075472,7888436,9167769,7776823,8878891,2048088,7794130,622373,1553653,8076570,171154,3469591,3707271,242955,2233911,7733075,3302815,322002,238014,7607680,8126623,2101399,1244718,990861,9273198,99332,4525630,7440993,4242094,141201,8902636,453201,217555,9114898,6894726,275794,1894069,4597636,5202031,1016096,8391781,7266891,7268709,4164827,7650291,7859230,2339963,2885039,3564273,511268,6975803,7645310,7292521,4801528,8594251,1570027,4361938,492302,7741141,616749,9326001,3348295,7072354,2220075,8784833,420967,363394,4600198,9490283,2009329,7294039,8557175,9543624,6222982,8584268,6025005,5104207,7629643,8666452,9019707,2692949,574289,180138,9105874,7688442,439701,3892280,2174423,7722908,2655712,856537,7195525,5514537,5224174,8957073,4171234,6916535,9169751,6839159,4942660,2699077,5169505,8299618,70996,1153271,7040495,5575304,7756527,7865717,7780815,1687177,3411931,7018048,4290150,8671034,6672148,9217187,1170112,4149777,1650568,873190,3890321,9364071,8823075,4067112,3878588,2206221,7197059,7820600,559137,160611,9495323,4005812,2415293,7133614,344196,6854699,257809,8992662,2186766,8217398,6157288,5951853,8617344,950533,2710797,3773679,6996656,279598,8960957,6361149,7447335,6832253,1265312,6502135,139876,7225604,2130400,2210232,1918326,9330633,1795837,6913323,3862382,2507794,7720559,8358949,813313,8785008,7075103,302668,651447,5717606,4218004,9227263,1716124,2510764,1110018,8822473,9330331,1182652,9507198,8419208,7329489,332156,77859,4988447,7071525,1015922,4271623,3358996,6610401,645354,167511,2863582,7559072,9201251,1932748,7575064,4125175,8725001,2276810,8514062,6029925,9254816,2417198,1899160,950873,2316656,670536,7667486,295756,1340126,1186655,7758472,8127679,8148044,1163043,8291392,7080322,7925487,9507311,7007592,7913577,295693,3880925,7833016,8218579,2827357,9468827,6946751,5499000,7268642,7071546,711540,7642736,6275786,7889216,2192241,6750358,3151195,7396882,8535590,3538717,347576,8978555,137875,7534114,141040,5758279,1185689,7149425,7149426,5732744,74221,2307545,2077955,8142228,88774,6352267,8689542,101721,1137767,493670,887534,407595,5585936,7769777,8571346,7905895,8431864,5519604,4169340,3714432,3184288,5777445,8522160,1857305,8613766,8854394,7712358,712977,8280127,200047,3673997,7487503,191053,1934062,6448508,1888906,7741564,9400826,6512000,6125139,25127,3468409,48325,7664176,3812646,4112531,5069572,143828,666933,545135,1516565,63307,891022,9207917,7191559,7806032,7805852,1523093,9185279,1330292,1175361,4699724,182212,849634,9349441,9300390,1732,7527434,872354,7191601,6792571,1202814,860834,9219387,5445963,3901898,8663286,7702759,5353931,9380634,1559356,5670369,805361,1039935,3916685,8574927,111651,6991940,7018945,893569,1264277,302719,5228761,7407450,209791,374140,8337855,8868411,3716703,2240445,714939,8497962,2877557,9237121,8691872,6007979,8169089,3792840,3156166,5922774,2998340,1768195,2754521,7875976,1714585,2244833,8339839,785377,7001857,175606,7596685,8495913,448473,216253,6962412,8812956,7679196,353342,5878410,67209,1381874,209924,801941,73216,7619707,6026347,6590356,322029,11146,8099907,898957,184575,1970154,6842476,7952664,176059,7882410,373927,9242613,6001634,6525249,990810,266069,2217636,234198,9414917,8000408,7961785,7101768,9356145,5586593,2806489,8537946,314574,7317021,7914417,6170701,6894364,1552507,8575841,6886952,565640,5999219,8665964,9348455,9368826,1792132,2977745,5457636,7575798,5895219,8018461,4681394,5714275,2092819,479990,9005923,9184952,64238,293447,7886349,863557,7664867,384048,8057008,1046766,9297356,2308247,7170347,9399393,93739,1278548,1194729,2680793,5482830,110847,1848623,5852115,4333942,8413420,8624637,8300059,2935697,248974,255805,4381993,7471436,3754866,7412196,1061760,6027301,1854056,577890,1267955,951038,2233239,5908806,7050185,4157060,3300040,64982,9344834,630781,8763730,2333501,4974915,7530219,1173585,9316305,8704162,6574819,245462,6383211,1319159,409347,7162759,5373529,1953537,1542607,1003149,1954182,5394055,6295238,316590,7633777,2016796,9474691,7289785,312440,1118727,1288793,405820,5340926,8210247,7276809,6025359,2468030,8806721,5554560,9114737,7371103,7219440,5319500,4798846,1093149,8358225,8589161,183014,1670419,2108212,250591,6914181,9446060,1390304,3532333,7053489,9526078,1278641,9481342,2136694,5757856,9008896,8674101,391603,8300008,3803496,9240669,146508,6569728,1664,6966051,4138744,3284374,4361284,8972511,4571821,145041,9455668,7337067,7034908,8110007,1045755,3636947,8584775,6370177,5789631,7669792,273937,8380790,5989743,6841738,4842074,4162202,4107127,7005646,2903276,5437455,1166535,8154869,3761220,7959611,89977,7696801,690909,2164598,6055625,2287394,5500875,233229,1883995,163482,574136,7296561,7118607,1787509,7224385,3722871,165167,6470291,361782,3284872,18937,4356661,5140189,793172,7364979,6537457,8128400,1209950,1323902,3457480,3895931,2941991,5981676,8111101,7865124,7868080,9526608,6443078,7343245,215049,110097,7766315,6906550,347678,6139155,25880,8966378,9080384,6498619,9454915,9464187,7437848,1070727,6606338,8772703,427079,3087994,2299055,4326349,486278,7004598,7782902,9204273,7154731,7154732,3430102,7124289,3087943,9284145,1522310,1178661,2575767,8009724,2236806,811954,9021121,1215252,1717138,2685233,59745,1697500,5338124,8062689,7508365,2136550,7367503,4189933,8433624,169444,6662560,6885696,8562069,7117160,8116562,511334,229199,5660477,1908409,228753,1328738,318590,9370524,242619,4371172,1383641,270693,8712067,8307565,2441420,6538312,79016,7427559,2918912,7209115,6922939,9420941,2494549,3231424,9061005,9200486,7878772,3660911,7122401,7912762,7957656,9242081,5478003,2059941,8426019,3953921,4721180,7827698,7976069,206028,1225110,832669,1908419,7366792,5637056,7664125,1143309,1792765,2412776,943744,8108494,161944,8135381,6192964,6641377,7339388,7399188,9402139,8024194,8986853,8553122,732214,2301737,9364328,7376501,7018279,3205417,2745755,8448698,3827234,7061474,4092691,4785640,4143040,1897396,9314089,852886,4675838,482981,2523496,60815,1161470,484094,2224428,9063162,4726448,6989921,2299526,6579085,595334,9025004,7828242,5477817,8018187,3678770,7802095,151092,1320773,5521233,1230843,2476310,4194550,4657484,7271353,6106423,7920265,9413354,67188,8056928,1109271,604334,1673482,7314271,2926703,9489318,3758199,7273816,8722401,5746045,1649983,2974457,5435608,7405378,8997045,7222480,9067565,3889907,9154653,921928,46662,8476998,1721431,1375913,2170703,9053371,17250,2581677,5408866,1654957,108198,6972792,2915723,1300007,4541911,6615181,1594526,7928807,3826169,3666314,2263202,7942212,8619411,886381,956812,8285610,229398,4635290,125977,101094,288367,8126525,4166525,4078539,8456205,4632689,318602,7027659,8076463,2714559,108596,7617597,7505906,4192720,5316791,4793752,7323823,1743591,8334103,4782484,7939286,9229117,4735326,1116627,552509,9083297,1689724,6920929,855128,5529390,2296901,4222792,1680136,8298958,550286,636120,7489218,6172939,4366570,1299464,7280086,7081953,2264609,439419,4409722,551969,7820393,7312704,65486,8656369,6901538,5641208,8762130,2911625,7027322,606273,17254,8732903,5801787,4622654,9079786,1350983,4611208,7388573,278051,808585,6008897,1861919,6891173,3681251,8203698,1574017,533102,5601479,9390439,6515228,4207780,107616,9271369,126343,5414755,413137,8386560,8907785,3696579,811939,7054806,2764207,3915161,7382822,4333753,9489046,127160,1133147,5242997,225879,674814,166967,1643663,630865,9310262,2552980,5599211,2186700,3518005,103784,3475444,7184986,1984164,959152,954646,7719683,151789,4756993,3008021,6826358,231591,5845830,2268896,9041361,2389985,2194950,3835547,8224046,7433586,8261469,7066118,947260,1176495,3112996,2101888,2174117,2429042,8045628,6755965,8841238,5018765,5957991,7136119,7203172,909451,8540276,1120269,2775241,3034667,5801763,164002,5439543,8905834,3135736,133609,7673701,1111197,857557,3437521,8120445,3923195,3151549,7479950,9518488,6020487,2308043,3278752,8686222,4748822,9483632,764041,8074914,1785688,8463811,8623949,9171371,8369874,7272152,7836808,7521367,5150621,2872159,8693587,71580,7432950,9128687,7055302,927571,8394085,1802602,3565806,687060,4946696,4623419,7773102,294817,593579,6860888,3331273,1657720,212754,7597517,7575685,8966058,5708845,5057287,4661240,92979,2926388,550400,8392163,841223,8702321,8742304,8151808,8609547,7695004,2842654,1779586,738940,2475263,7716295,8690181,6915789,2055492,3600041,466496,9040698,6890677,3091444,9379772,681771,7698021,9330120,8280042,8905101,2012086,8485573,7626415,8723977,88965,5846730,2902112,4105903,7887091,1868144,9415452,7155525,7155526,4442881,7579976,3858815,3722037,869318,151190,6055298,1303922,2547700,1597802,6398456,7727312,9050661,2091772,3705609,8460197,6762022,3048136,8641846,365269,714846,6852528,6316103,10187,8830101,537098,1811005,5282666,3980996,3771417,2588531,4050914,6022547,839200,7090256,4292538,7939544,2739595,841132,5647949,238076,9386156,9386410,4605166,6254795,6914599,1630508,9288042,8416740,3097507,7252886,8518050,3275419,2607865,8144252,2278118,1376255,182822,8115990,207066,336442,174610,7845243,6396270,7786813,6056315,9149305,1692937,7812434,779170,101436,1319816,1010234,8412969,8277664,5700202,1111626,750484,1310318,3913619,237607,9189594,8449748,3841961,8037307,8837987,122047,5273831,3916658,7120385,2620817,8706059,9561049,864145,8688800,7212802,7760832,6902255,190695,124622,2516518,7267825,7770226,5581214,6727891,7508764,8088252,6611151,870775,7378097,104761,354092,7222327,4902385,8664929,8481838,8953036,2761369,7004595,1755078,3151810,3619208,6029407,9316539,7845366,140671,8090327,6161500,607382,930527,9325460,7487205,7179657,7180710,554900,139417,5571608,778063,383190,7246955,2922119,9202726,6020143,4429183,8055029,670935,5513814,5202934,3663680,7482631,787012,9329514,18315,8444439,7266713,8837450,6451222,8049598,8055493,1034160,6533551,1698769,8678651,9275977,8862145,4329283,9212871,4475053,4504264,273344,272809,898630,7942507,7196364,7116778,7378916,8694978,1772959,8995297,8503256,6634900,4651187,6326947,8839805,8271813,2609917,7525867,8528756,8727530,1269485,7938116,1009794,6702889,8276934,6404834,288502,9261245,189180,20523,81937,758299,6780889,3912353,3470671,2103784,9221509,4163709,7040157,1211060,1927410,8966673,2459384,2430572,8887555,2670949,7332139,4694453,4688330,4682762,4678619,4683725,4576045,4689032,4599628,4148282,7721566,6870325,6260652,8934939,2110114,4604306,245702,7791210,2236965,3128674,2857951,8473859,8444238,3436873,5502189,974311,8144875,8294815,7117093,9244906,1927770,1592003,9284941,5252804,8251067,9150519,2546713,434743,976993,4096072,4649444,7045217,4046888,6993522,7855736,62820,9191059,844597,5971350,2842102,73933,1729854,4121362,210852,3711084,6963482,6963491,8747232,4052996,221159,8850121,8091353,7790324,8981345,7945090,4269271,4550308,861190,9327006,1258748,6901311,1997148,5397733,9246483,2369453,2968490,343369,1707799,6218212,7352924,8022197,8136180,8457630,7247139,8601812,64164,1049088,6271809,3561313,516114,1173972,8255831,4575736,3092074,8077442,2133616,1622345,6713698,2009137,562337,9311901,7040527,6911486,8143112,4881610,8736299,1376291,8708186,9433708,5238158,914050,5894433,7504878,5240714,7115816,4056596,112347,302945,6194719,25182,9016263,7604082,3268756,406537,7493800,79875,6729916,8260107,1543633,8146987,8452066,1308494,6336101,6245843,8151213,7276457,2957804,487122,898222,3726183,896917,8885355,4717238,4299108,9340362,7740894,8726674,5336006,7733363,5712823,8433785,5847249,7438615,1084509,8469451,7611837,2885480,5698465,7012436,219312,806275,7606693,8871941,627137,1114998,569507,3895016,1601699,6777250,9297245,3860906,7681284,8905220,6891261,7484756,8673761,5225218,7060130,7678008,981229,8023767,2101072,2194743,6371715,2917511,8796892,1322213,1152819,9164513,8311084,7160315,6919402,6024931,9153578,7172198,1385468,7179422,6596848,3373960,553001,7991925,692319,4992365,5993001,8269591,7335049,2803870,8869355,9314642,295533,7968407,4132447,7567604,8696684,6861242,2124187,6539467,5021494,835045,1176918,1370267,9249183,8514151,3846947,8391268,8652616,3681239,2694553,9204613,5846229,6974709,5709385,8147399,720489,4051568,188902,8979025,7681167,6980495,57463,2112379,1592219,496274,6869825,3809847,17131,8522731,7501720,277937,4067181,418765,288861,128450,2498236,8798180,5850570,4800172,8640306,668514,229308,7759002,2280740,8905549,3144037,1820638,3653471,99518,572081,8134690,7470458,6861660,6967282,6497025,8380719,4032248,1161655,7579579,5428030,9023425,6181537,6238063,8784,3723708,6434130,348260,3846812,1993827,5107681,8751270,2276225,9397771,9425868,2364407,8884258,9284277,1638149,8812966,5999486,2740983,2639971,1655293,9368676,52757,6884628,6336047,281257,1776148,142499,54433,6960804,7463102,8510689,4366288,7494502,2729901,203533,8369547,7674732,7949753,7295519,933103,8678172,7132676,7261366,1603460,7280421,7242593,7074930,7693260,3869513,80835,301002,9057458,8218827,218132,7139836,857608,5004263,2263424,1322174,6025739,9501492,2874431,7091408,6760468,1372427,7720037,6095933,7298314,5245946,252426,7907742,4138090,2660432,5934417,2742819,2251376,1914082,9351331,172819,2373491,4219468,2411531,4286643,4156288,5058349,7749215,2086341,8638269,7875882,6830873,6830878,5558802,6512069,6991663,8257618,226816,7446235,9293195,1215624,1528991,1900843,9063657,1079913,957598,7123379,7522317,7950193,8596288,8907136,2172161,7525269,2852887,1063398,4956816,4124059,4469515,7021280,7851906,308000,1942868,340874,1516127,173842,6407568,1350701,159955,2338769,2687105,8039096,110964,9271701,1744491,3317974,2533387,7796918,4252861,6081431,3670943,2909195,3573600,9195986,2086495,6247976,8722728,234807,768379,1935464,237628,1385186,7148704,7148705,5175068,3551416,6985757,3697200,364636,68395,9045005,7461337,7626481,9237444,9051837,7640416,7840750,7803222,9034366,7641862,7205874,8006471,6669619,6937587,5986542,2495920,3057811,6572482,7431924,8049014,9524643,8297107,136319,9308617,900190,1971501,7976842,7850646,6852011,553901,1050438,6798805,5086180,7164672,9084054,4519726,4526215,5462832,9299825,1860488,761890,7328653,6845836,81296,2679125,7016257,3340954,5110240,8691439,776593,7042036,6994147,312326,9096357,6986491,162965,9104799,5513793,2181213,1121817,2678745,3413914,4754586,436498,4781638,5915895,227509,8194571,7428143,9011464,652182,9069926,1514837,8886660,6464042,7205657,8916455,147483,4857739,3521284,81548,328305,692073,7091751,4242790,6023395,6923929,9559442,4364608,3237247,8730095,2131789,7316251,605693,2394365,6261504,746374,1290899,5106421,3543946,898432,1836285,1133018,8614524,9558241,6788986,413209,506090,5728376,5328896,1054686,3055243,1983792,1913340,2001535,5260637,5250170,7665382,50053,2789536,6921948,7053328,258303,7722597,7769377,6870688,6399154,1799212,6583777,9328289,6075179,7184082,7536673,4801267,9405872,6874719,75936,9330448,7174227,9120887,8016602,6064484,1817395,1080045,8530624,6867888,3623216,7934917,8208232,5075248,5800128,8507113,4069944,5639282,3041788,6870025,8504261,6181270,8922367,9453476,3508039,7738551,2421176,853519,430623,575372,8048697,3446365,7589805,8277356,8308551,7659062,5303960,8841389,4267090,1176681,5772033,167157,6328563,364936,7162203,6615115,6252428,4628852,7767875,7122133,8207298,136968,7241389,8223499,8413145,411756,4747614,4738533,29269,4219558,4154649,1898554,6420864,57422,7880237,773719,3859373,9347669,9370853,760393,6615991,3510097,9419007,9335979,8449175,7083154,634569,7496297,1071774,2169614,3090895,7807928,5897556,5961255,8734342,7195190,455391,1687831,58630,7693754,7691210,5186666,680172,7587290,1819831,2360981,4284206,959608,8911985,1870676,9512240,1959426,7087484,692178,2774715,5972763,613155,4215727,6319553,3482338,213668,2095531,1963794,3548245,2225586,5827887,9237905,6157669,9172148,8699353,57944,7588615,388423,9321787,9265721,6024569,6919805,6975711,8525083,7187965,7529555,2035881,1721887,574742,6929893,7443136,1893295,4128394,74788,552737,47157,632898,488823,704455,9292948,4899829,8262617,5403253,2963933,1855793,5302736,6534783,8775921,8775890,7484110,5991990,7964294,8370433,9117680,1887610,1225200,585794,9385132,763933,8525525,2398688,8496531,4485,8741904,5773956,2784625,886093,8215700,4430518,4184059,3577616,442632,54938,3416203,3324328,53821,5755036,547823,486411,946345,2648749,7299058,1742151,8041681,2178805,132558,7254968,212290,8583551,7184474,2514325,5603267,8921127,5569937,1591652,8737963,3032144,7582813,6322803,52727,211738,7043509,8219798,5781105,2032851,7891160,8948352,5041090,5834505,2667099,2974436,8052911,5296577,8608456,244006,8877424,2763377,4665500,6462908,7304914,115969,4151011,5219890,7670480,6993520,2384558,8412674,7305784,8826260,5956056,7077115,2897504,196050,9399399,7816695,8876470,8420645,9266607,1165209,6104929,631015,2689043,8700944,80094,415707,3572565,6902479,180013,1886314,7306099,9068067,8697313,7769475,9164963,8423153,7630246,2886698,7134841,6024729,228002,1545955,8378483,172386,6481148,7389446,7600719,7090047,4023020,46439,715201,4072104,1212647,9785,97050,2051919,3908084,5207779,4986638,1228659,7611128,5817447,9054460,5380108,8489857,1322114,9043721,260199,5258000,7598969,415348,2737437,9114720,617453,6778339,2808166,4453567,7523635,8529415,7372695,8126458,7224873,6797884,1162749,7793517,6609753,8810317,5872671,5069533,8975462,193748,7996598,1911327,6894812,8447177,5766459,6939761,5289869,7597957,7619732,3418078,9469498,155581,4271815,8514305,2866180,8755577,342768,4364308,8792950,570443,1847171,2933900,6230365,4396558,5454624,2876330,1922818,8978228,698571,8222344,7691050,8622365,4269838,188918,7934406,2394089,268181,7104265,8082105,6734158,57317,2910356,7035283,9265414,2063919,8765095,367333,806917,2395985,8974728,6896725,5976858,9185292,6844068,6481622,1906018,4649807,2752625,4252021,6978775,2653462,8799463,2014540,4745610,4771840,4229425,1254818,1211885,509553,890956,3550255,7250751,3787116,117454,5769864,4530106,8850205,719298,8367841,7667493,6972638,3350869,4734159,8853000,7114569,3378832,9195909,2244515,6118032,3382870,387346,9556359,678489,1595738,7375785,8683545,212660,972217,132629,7024693,161590,7451894,606848,781705,620960,5158016,8495436,7191697,6490529,9507670,9504295,2814022,6352987,4175344,3754689,2983127,9425161,8165528,237552,8762595,3838967,9488669,2688937,7827965,2005525,3581174,6117006,3264157,9239246,6498085,1015608,1074660,7217147,239439,1139372,3521173,9302408,2391440,115565,8345140,53528,88516,2864419,5985456,8608392,6540413,8048188,1166733,4511713,840904,1977420,7558470,254768,623951,9077070,2745931,3390085,2280803,5512314,207343,529032,6611542,1162143,4282189,1075839,6676150,576194,7347450,8367863,36887,8631013,352829,850534,8864201,3234340,5316761,5366457,2494327,107783,3215980,69109,1162626,6262842,10186,4823872,8608790,6437492,8686049,8497063,569081,6830585,586728,337163,8862491,9168802,8762118,8649979,9034919,9404452,75038,16456,9175308,1033116,7420366,5658626,3557513,4171513,8552419,493335,4612826,7152481,7152482,2910578,2225439,1189424,172062,787666,707020,7468339,2269376,452541,8637598,8637712,9055251,9256049,5632415,6612569,615695,1106556,6862891,7530485,2780361,3257647,7564299,8193923,6948495,4139458,1916744,9180456,8021848,3680759,7839916,727332,3912566,8585314,5997998,8681246,608738,6385681,3543667,9079652,2269685,5519553,504026,160471,3561855,2053176,521708,838435,62720,129452,7052063,62004,1360133,2629894,6746422,7990218,7171779,8250773,317029,7718530,116828,8486730,3855353,7587360,1179211,8642435,4284235,1978932,6939835,102344,4862389,1303544,8127758,8122721,7408231,7515288,7516869,8092399,3710406,9162113,64944,5973006,5973330,6817053,4292044,8713970,625703,4014056,7786931,6281207,4008224,9421583,4757533,7593544,8581570,6648340,6022841,56304,3850097,3903134,7072740,4111034,748603,3274051,7483701,7399650,8329724,62460,5534505,506558,635883,4861060,6411644,7321629,3779463,305710,1519526,4184830,8775562,6414490,3054826,9392085,3623,5444679,2771293,763589,7749260,1896934,8365095,257170,8736690,6312181,2831011,7275641,7244136,934877,7894888,6739933,8284679,5033548,616622,3317140,3184873,715482,7778961,342198,762166,7335974,8134897,8914374,59697,5174807,7202017,5997071,5819787,254006,8185615,486993,292699,3045127,8951107,9285649,1870478,5519538,4165440,1201923,4450897,7946392,1401452,7683348,8595215,1936400,709773,7534300,8542881,135494,8007000,2811520,1025766,3492904,3075232,720525,3934736,2599747,9289626,7195273,1576099,2409374,7714611,3649727,462959,8836266,6608161,8047740,4193197,4902055,6593011,4593571,6618369,9014599,5441916,3127678,5160706,6954215,7534180,9458889,3418264,771878,6479537,9129544,8308399,8115178,3414493,1329185,5877612,3714882,7428869,1989174,9421548,8057826,5589191,9419122,2696869,5545248,8444931,7476383,5326829,222629,7071838,9251139,1665304,436569,7384173,9515167,2766077,3017402,7060255,304158,7228879,71791,9020488,4297289,8172546,4157615,1567117,5213248,894352,8943771,8859760,3558891,3449254,8537804,8515905,7896159,990150,7318596,9094451,2908502,8956573,6099499,3700956,145228,762019,1064946,7079368,5675517,1243809,7373316,7115045,2692599,2481748,7203024,9491182,1181301,5992422,4723805,2911301,2188710,7180529,6365847,1787278,8346284,2671281,9259268,322806,6427288,4653371,531482,179596,4149664,7196967,5543955,3939929,2113093,7616312,7695816,7695838,8913908,865237,1238889,3780507,3350518,7981214,7577163,596709,238266,73901,876202,2544013,7677784,7507648,9291595,9222655,7614231,52002,2379017,4619441,7797630,8737562,6935644,4621004,7224298,9376032,7869034,2837053,7734797,2905277,7044618,5071342,15270,1300655,1658017,9037081,7936604,7833609,8369249,322045,9176228,104507,8577430,2500558,4744215,57979,1014156,7130337,77919,488882,1148961,7498685,6717478,7998953,5774277,1294064,8498306,9113068,4533148,6005978,7573621,8527284,8190736,5538807,7792967,2521069,8653642,4976412,749683,6027331,7395957,8185971,1062705,35415,3097432,6994800,7970802,9477511,7602197,288247,9323292,1021047,3436210,1236999,7420874,8328049,6981649,357593,7351561,8106520,651636,8386417,877870,4740966,2320229,838615,21376,164136,195829,7577494,108387,1726725,7400847,6435960,8289871,6783757,9421988,8885185,417223,2864227,3771621,8093577,355562,9004539,8442077,4456777,7969172,7151557,7151558,6754816,8893848,9443417,723867,3092941,9446170,200708,2413355,7078619,6188089,8157777,8828893,5892102,7227978,7650051,7650004,4838284,6921075,8342136,8269086,2177836,2063877,9029608,2052312,3708867,962920,6030641,8395541,1971681,2414927,8075204,2395121,58389,181779,5172442,3462118,9393982,54257,3195253,7399856,7842039,7742970,7703048,7479168,4039085,6383227,8894432,7219187,7354977,6857332,494703,192733,180892,1081587,1357925,2496436,9304408,8352336,1987284,1130273,1211648,8181278,4008791,8968527,9209492,1506839,7075141,2040204,2024503,3534142,6742015,2483680,8031838,7427239,8306036,8306037,577625,7939302,6915818,4711445,5549682,5444439,7931411,60127,92581,3532222,1652098,4116581,316498,9171938,229484,4573162,5905737,2868643,731769,3711846,9235760,8929879,6220543,2282795,315269,5795436,8537117,884200,8407718,201580,4115570,869443,8741634,1553869,6267132,7537466,7889654,1679119,5543238,8817996,3649925,1913916,7248947,1360349,7176572,5937102,1662166,3243457,172466,8046004,2836549,3080041,4465903,6417514,1674994,7710744,8026456,415363,1322795,1663501,7210089,2804941,7874405,1926832,75198,267557,3629081,7034030,9520071,6627316,7500822,6842443,9493368,7757847,8488432,8525735,228827,9243315,877445,678327,7023918,3867701,253612,9455113,2894573,8878937,1541611,2373302,8278416,8829947,5204818,7365944,7112086,7971725,7742498,302399,1189908,9460178,238413,1296689,58114,4210141,9367481,9286559,4191796,7452961,8490184,6356543,8915996,2203407,65603,12505,8608141,2993015,570101,22430,4045868,466850,4402051,3039181,4290414,829816,25294,7498463,4189006,9071980,7201648,22434,506738,9548487,1032960,1534265,3169588,123274,4394821,968974,245048,8498581,2938031,9433199,8294746,930779,2032686,6838252,4724606,2043186,8949751,68347,1028142,9102319,8155497,4854970,4026305,4155008,3939455,1791523,7665600,181585,1338323,734320,3365029,1160141,9369180,9196644,8411785,798901,2883587,3394846,6624280,7527347,7344113,6072614,4149781,8543102,6639007,9409203,2382014,7174233,8869719,7503120,8163209,2299073,3025043,2154015,9324042,2585259,747311,7267212,7789759,8164340,4177639,7328886,9289297,8680351,9023857,9539594,1040814,6861050,6761815,8506565,9514305,8621943,1569949,4388284,2054478,4151087,3461323,8517764,7541645,9075216,9067773,56996,2142913,1345616,4729889,213588,6429764,2412713,1927850,7026215,116048,9452488,2109286,8058806,1914516,8862601,5886159,9349915,900727,829060,810487,546776,8603050,3837770,6325665,7081439,8527708,1330247,932420,321704,6406382,3206626,3904082,634389,124230,2367209,8622390,4332898,8129471,9357410,8529288,7532351,4816522,4034999,1765888,7665035,8231441,7951580,3675368,8701092,7999359,7545022,18155,8419493,4130188,8070398,8007964,9342195,9478677,7547982,7263356,8011128,2292587,7506038,3459130,94849,188151,8497742,1854956,5957562,5467077,7908690,7563564,187512,1756569,257954,2988971,8649196,265542,67734,2161227,8418398,103959,5537913,9327292,5172991,2229744,3831287,8265659,8265660,2238888,708228,3927476,1856246,8664117,9550300,2460386,5402887,1556836,1156201,579608,9401002,170306,5240642,1577329,672012,613146,6874139,367891,6962957,5186066,8955559,6974405,6974659,1317401,7525888,908927,8365896,3490594,3121909,7256944,7544946,7307932,7258681,742741,5679696,6487424,3222166,9237451,554492,206624,2585417,3405445,8052401,838967,8728316,337011,1719520,2116846,9202822,7741342,2135719,1655518,2764635,874700,8964770,758906,7181447,7715518,8523533,7306462,2417453,821254,291669,9523650,9523614,9433203,7461867,900739,6356893,1273961,425433,7099828,2201295,6655060,8875068,2933618,753922,122777,1393055,9191894,7977420,6611888,2917931,613769,4143559,6506015,1814218,7059854,7814556,8508520,9454413,9444912,748414,7595982,7187215,8226910,6822789,6321239,120489,3852179,8670754,6444658,8852481,3089722,9385031,8127306,139784,7134599,3571401,8488707,9093422,17197,9012528,6545167,6892690,4793587,7843819,8899302,3475633,5244230,6954304,1330382,3495925,130454,7004656,4606964,4159381,8312812,532649,1681219,4285805,8535886,9530683,7898158,67277,1253243,8947065,538562,1173414,1918594,7214091,9494709,2257946,74962,720642,251472,8148532,13037,7572780,7567632,192527,3425638,8831641,8031358,7027212,3155674,5160097,2727197,8940577,4076787,839219,205502,116600,3741618,4657400,831058,7128479,7568623,4870651,881365,8155795,5028910,8010847,7298836,939202,9323353,7166767,2884490,983383,7863475,1736490,7928796,1134114,8560492,8095895,6555046,4653503,6121113,6121995,48290,4032917,111764,7574752,725365,4999262,5936961,2370161,6285302,120197,914561,4170351,3426148,8175221,6552814,6940117,7061110,194664,9256467,7125542,8042926,8737724,1649173,3994880,7403214,9495166,225149,5191010,4235889,8206821,8782158,771554,4620935,9067888,8780430,555200,1070739,405178,63186,6021851,9094884,1909404,4168035,615954,1882099,94502,9041446,8299266,1045914,3419878,3904073,1266086,4026683,2972117,4824646,9432806,9473724,5822007,7848800,8732247,1368821,1317029,7833613,7462046,7518728,6886295,4156732,4730096,8357533,8050813,8321015,836710,9170257,5602223,6456923,1672945,3887108,238405,8011372,9082011,9081618,2680377,8303439,2000530,3915656,9460738,184471,727275,996942,8746215,1112784,5757805,10439,2596603,7093318,8358874,9373987,423707,9365199,5073979,9026714,8412738,1331429,2201706,8509484,7152207,7152208,6912063,8932293,9144734,965782,4281679,990915,1749492,5986452,6489551,2548918,3335725,146270,964228,7101284,8780043,6967607,7852713,7075869,765391,4011416,7883305,5991459,2373035,1676497,5157677,2349212,5845605,3496462,7427347,5091652,4539268,7579334,4123645,281275,7521665,8835653,6258616,7774667,6123618,8771454,644673,56068,8616127,25599,7638888,2240487,192607,9205104,8279645,8831951,5536896,7207496,2822215,7307849,9518500,8153927,7899547,7184911,8173326,208781,9453025,548291,690873,8437341,6402394,9532885,4723454,5344343,8295802,8453938,2008786,8831532,6425960,8343265,7166483,3262270,1103568,7550769,8328549,172338,4103773,244801,8076080,8914488,2332679,7415478,5977707,2174777,1391483,3823299,4820194,3272848,1125294,1837740,7640928,7970238,8641501,4128790,8180296,9516831,111579,1573612,5914065,8267529,8360962,5268839,1642106,438153,2927987,1945589,4483105,8805484,8485176,4114295,8711958,6960698,188191,1928390,3423700,5230039,4971879,2089150,6178966,7103299,4850200,5036509,72695,8498985,8080159,168101,2925674,2266574,7008213,2899202,966475,9260532,956224,818641,7502082,7492671,411762,8422458,8186260,432421,2901485,6828151,7569423,9557468,2400878,9024051,7033564,6255095,3768846,6728095,9498315,2799778,1048590,8664188,1138068,2174735,9298662,8059800,537939,4720397,1639289,3718227,56637,8691073,9098905,8966676,3083251,8512680,162983,7336922,8265284,7438194,697212,925171,2622151,172288,6883419,191009,907378,1324241,7853874,504948,4270522,5477025,7405341,5248142,8885639,642561,88547,3401659,8692055,4039124,7158834,7158835,7054732,9558260,8502806,8772273,2330996,7639830,2811613,3059725,8039202,2850985,553646,1543777,8627588,997008,6939897,365488,8404180,4805182,7686502,1301630,8266197,2110534,4636211,288016,9402132,3720405,2405234,106759,8525429,7232963,9409382,6403238,3260806,8679497,5482836,9240265,7338587,244963,3377296,6505475,173985,8245293,1317281,1164795,7174091,850273,1390085,3245917,7109615,9454467,5859681,6391209,1351892,3731457,7960798,3882398,2710101,3461236,7989473,9283802,738338,9375971,7364594,9067692,8481320,8295042,8317419,2922236,69472,6179905,9346848,2339816,3356002,1165254,1994643,167744,8744679,8314244,7219962,1080225,1731219,901705,7521301,2753491,7277025,7277026,7745071,9466271,265133,975052,1135638,7160081,864076,7251430,1169809,8767710,7904935,531206,6028325,5022169,6031027,7092764,8990751,2707453,9319035,4293129,7268416,1536148,4796836,5542950,9402175,5592974,7307832,7637063,4637768,8872528,827731,9288928,9140966,8654574,7770135,9363747,8543797,8989448,1858919,9118175,3192445,2269547,3547330,621605,2264810,2889497,7307466,4740171,130399,1732908,4710413,619770,8117853,8860105,4604071,948706,5636216,6027497,7523596,8421719,6719554,8624110,7502277,6166399,2136910,7544384,3816705,6202549,1893925,8726831,3378289,1158379,402532,6986053,7098984,9536947,8304039,1881574,1541935,917146,201748,7199406,1279655,7649880,909619,8688958,7648338,7668624,7213875,6223567,6689674,9327795,7699655,3412702,7610283,9124475,3389704,8691225,7501610,4736451,9500977,7037650,2639749,8191583,8131571,6192100,8134585,2285891,6686074,8429382,5418307,5255516,7923572,4998644,896294,4889119,19715,992211,7360342,1757694,4220092,752719,7648693,5635007,7203341,1120236,2302172,6898021,1221552,58646,9270768,7343598,9197735,88382,3176851,4421065,8913301,135127,381231,2608633,230660,9225253,7390197,4216180,2151339,614726,8183699,265591,247382,986584,8222035,70645,8244754,4771924,902854,6098983,7081866,7041051,8058521,283548,4361308,2468300,3435088,959470,8448895,8278841,5033362,1368746,1944422,1588178,928667,8291681,78314,4208611,8976033,4068507,178530,178547,1656844,8426412,9275630,1179939,8880088,1759764,5113147,5834337,251044,5322194,774596,2983586,6815,8187647,633993,4263460,924511,8068322,1962294,721290,5142082,2567032,8038243,9510214,7057754,4038623,2453849,9409848,8794883,3529297,1067043,56189,7302445,7175770,7768699,7865754,8013096,4677968,2512900,2489698,2059965,5382346,5478927,1561219,12008,3940721,8543413,9215291,1586318,4976705,8736608,4450498,4060628,3507403,8975334,1301597,6487208,4076154,8909530,177758,6980055,8980731,7730481,1571986,648435,580818,1745856,7417916,2453144,2867539,6863721,9511934,7674052,8865343,8260426,6840264,5612432,9095000,426090,2006164,9018333,7152387,7152388,6319487,4066686,9030747,5960727,1834719,9411459,5034112,2298179,64903,1389791,8583130,8476867,7588260,3474193,4132894,240039,7375854,3401065,1804621,7526018,4381858,7140202,1867409,7123742,93884,357530,3268729,6713269,200027,6030891,3714759,1991832,4415137,8959426,1336097,4675976,5806905,6348285,7348064,1187354,4900123,3712869,8266060,2958449,7127166,568382,8173591,5216068,9527320,2522389,7083927,6860034,993246,680148,2432309,1054818,3612887,6285995,6851292,8938963,64316,7642445,8741933,9007920,1750971,4477498,3612845,8096139,8519004,9014598,110960,5222053,7647783,2690653,451221,9236836,5054035,4216576,4030142,9463534,6890184,8573980,528920,5792001,2584711,4489276,7884530,6406138,48827,938378,4192441,4566289,5904312,7236401,3643796,6420508,3872858,117733,424228,422992,1312262,7560302,8108708,987433,7366585,1544164,7898465,5264606,9475272,1799677,6552091,4154259,7764243,9203806,7490745,5303786,4583032,708654,9407140,7082449,8915609,7246388,7492762,7540320,3873209,7851905,6875396,9399103,7881096,445548,8203630,8266752,1586516,6644758,1946326,2581741,176731,3080275,7359367,7309403,3018395,3018449,2403704,7223734,7251577,9183164,9549663,5084017,93550,1276322,6365937,5758174,3337126,6443074,6953966,2088487,9455016,8202281,8262647,8038280,5237318,998629,7254404,240203,7186347,2273165,1820722,8565816,171511,7167256,9084462,8734257,8735067,3329929,8861004,4980968,228890,2995229,2297399,260565,1681687,511316,3538408,7010601,7698980,8930645,1578214,2111125,2287121,7985238,2659760,7465328,7787169,7373600,5258978,7207541,7134744,8729860,2904140,2550826,6900015,1986930,2795200,7691343,8716938,4643525,3272143,8596084,7184878,2368874,4957140,9176920,8928474,277560,4116335,2292737,7956700,6376969,9345360,9385481,7334379,8337554,7510847,2610238,1161884,6383869,6028953,9408328,9558564,2920772,3427120,4778611,5437734,9181697,2004781,3503116,8098741,8203713,48859,7227877,2721721,9406068,6365911,7814806,1723414,8750135,8763424,3890636,1041081,120257,7309831,8002471,8195995,1182822,5109223,982123,8949137,8792050,53734,7369843,4250326,1721686,1790980,3121489,879532,9056123,7212101,1012874,8935067,7718650,6846956,139982,678933,7159096,7159097,1778812,9004502,6279710,4710224,5977302,6639442,8777903,6569449,6158494,139224,423949,7611894,8885406,7750676,8597252,783962,8723454,557801,100179,7759417,7043053,5789295,996810,5749783,4724813,4824292,6995363,6679960,1171503,1884376,232811,668748,8741568,5155442,8056350,5823396,7047359,6909482,8277266,2175230,4169205,3883259,437347,2067339,6686008,4554907,7449847,4794856,2118079,6343027,6291395,4188949,4789330,9407483,9396640,8518057,6233767,579152,878338,1213445,5492790,1196082,641193,4879723,3847418,8311941,79615,9477538,3388204,8718730,2856250,321290,7933317,5477880,8324059,1871840,741028,2966651,7740344,208857,2500957,7936584,7422768,7147429,7147430,8595395,5219299,140013,6520881,373006,910549,3178954,960493,2980304,1332182,2091742,8876066,8876067,5926224,5393416,71144,7100221,4667990,4176772,8762497,4181200,7589411,7213396,1144932,285741,188854,6153733,7324629,7778618,6699526,8125606,4315411,514730,2528071,8882194,9170515,1734582,3480256,4597930,8606962,7208978,2092837,3659054,2319113,7218311,5321453,7925361,668121,3278467,9354668,8924014,5112451,7980672,159047,9125110,240365,6525677,1161450,1569319,1812688,5564070,534797,8688739,3745449,1195302,98288,2306579,2135308,1153937,1139088,518544,7030237,2026334,8890258,6608795,7451038,8394894,817541,521370,9168152,9249291,6564313,4203370,4126873,7852208,9334124,1527470,5170648,1356029,8977978,2273450,6922850,8981117,8969237,5020868,2574359,1833099,3086743,3233272,8445675,7154749,7154750,4162617,7418982,979414,506468,527076,1506152,3705657,6173887,8957759,5351297,8073354,640641,9532401,30620,8259376,7621200,791495,7733626,8634211,913066,4052441,7088468,1053666,4955709,8846538,9232941,8491324,6062990,8645920,1540975,9414532,7996287,226782,3875948,8207826,731928,9441412,7844496,1624568,8626792,1019613,613173,3849950,9379623,8899994,7740061,3940331,8876107,8892431,7009909,8073194,8027857,6021327,7906641,7478601,7535930,9329864,8201451,2132602,7539846,2733837,5627315,5809743,6478340,6525363,2540884,2769851,7212765,7903995,6279140,130139,1629038,4173617,2262020,4999211,2384807,7137090,3580076,139846,4893557,7213293,6304719,8001616,206799,7508427,8327313,331468,4842626,8556191,8902524,5902206,8252484,8899334,7081537,8942997,5935887,6910085,1921194,7595250,4896214,560714,1321001,9230439,4945390,1173318,7358880,1537549,8316764,1368581,166005,7955180,302740,8301519,5800257,5615435,4141399,8683317,6333865,3810636,217481,2133052,2580803,1927610,1759494,162108,7484697,2523337,949763,7418787,4865356,7114150,8052298,8158461,7444298,7161020,34418,6984747,3265660,5188982,7030473,277583,2026836,8490180,7459136,1334582,9072454,5208472,7809006,1160649,1155315,1632494,8892180,7112694,8299336,3554143,7633951,7414240,2945228,939646,9136665,3360523,1080063,1778878,8349762,8405141,4943386,7079498,1162383,714018,1711498,1809358,541409,101534,2105341,7374621,4761760,9160510,3620540,2087680,8816873,5501511,9176112,3458188,9072983,2060466,7097043,6888428,9293183,81816,619212,2785001,3880610,6831192,1777510,7723210,589836,728860,7635966,7197992,9252678,134279,180394,1024410,994405,8620910,6992627,827788,3132046,3828902,1306745,3900365,4591183,9192531,8080309,1268321,6185140,3044242,8973158,4275082,6347701,7783639,8059278,7722725,7055547,7995107,8060736,1575880,7604066,6593788,1909707,4535176,8708839,710779,2777159,6924215,2166209,7822994,228519,5120680,8858081,6848985,3268240,16473,8957687,3886340,4542703,2193000,6944226,141555,8218539,8895258,9480468,3434515,3695400,5699212,8383728,6382383,2850880,476948,6442806,2144271,6985399,877951,9254060,4286660,8736141,1582190,2300630,2576605,364849,1862339,798065,7532611,4545721,4409407,8773706,6772126,6770629,1304534,1368269,218159,9414470,7299488,8692076,8342991,4988885,181987,828184,7292963,8613479,2206977,8653968,9211871,109018,6897978,161140,1838337,6150889,4571602,994194,2864734,2177144,1173240,8129189,2936771,2330735,125239,5198357,8787283,5318225,8402464,60934,1277888,8066997,9028208,434332,8263570,8953569,9036749,9163623,7716806,9307339,2665965,8415846,3210685,7727050,2970581,1721419,15059,8470322,2064237,8629006,8441524,2747737,7937153,3779355,121318,6918977,20698,3275290,8182238,3152944,1927980,5561274,8136583,579725,7179664,5840127,1599698,8304529,9125411,7033999,3148054,7493753,8260356,5956476,8465895,4112894,6594358,8805543,8423320,1010826,1970835,23123,9379803,6523155,150900,73786,8982661,1263011,8581618,1029204,5464515,8158053,5782230,7721067,8898750,3451882,4984112,913927,5730122,97165,1597427,9308639,1028754,9208520,870470,8282429,6624850,8992264,7613023,335724,5271371,3353269,8112049,9130356,9446780,4416496,6641827,8374475,619481,6936169,58334,9006420,8228364,2638618,8954498,9391250,1722956,7766153,5054539,7860317,4175503,4154496,8059666,7983790,1655647,7088292,7544887,9449570,7879781,8383887,8447349,8184864,3938795,9501197,392697,3119188,6414028,420397,7589415,8584172,2573721,4885540,2268272,2798530,434718,7992647,9088993,738934,4789765,9262005,5608151,1763926,6252152,1313411,5639075,1690855,8485447,1263671,7625836,94178,9361776,7680656,145227,93704,5051626,2069172,5154782,89046,7437782,5050222,9086359,8281194,2106649,809845,8658946,1339100,8377989,6071144,1257074,6819147,557876,8242312,101220,8439325,4274722,3997106,8427566,62203,6059708,8329271,971953,9165459,67378,7434096,8971876,3477100,133249,8064274,2785345,7149191,7149192,9437077,787043,6296440,4411186,6340749,8830030,6351435,4895911,98642,99073,7100672,270934,397140,8133945,2320304,456522,449164,3743205,7447750,4326727,546257,9013265,9148048,7625449,8318453,7719360,2068860,7270880,7538635,6320483,9275375,183535,5255894,6308383,14014,7570642,19197,8221357,9087040,989127,2065218,4314484,1134990,2346812,119933,15377,8439179,7508534,4310173,9231498,8630066,3676127,139554,8678659,9016111,8744483,6940681,9220454,5760277,5890452,7411167,2721643,566849,525368,7626622,5363580,3526387,9121405,2108479,7735903,2934146,694437,5498037,6502813,6406792,3351415,4949925,273252,7733919,2184930,672795,59305,2044797,2917949,910801,1889848,8541947,7680061,1513106,4742478,139549,6956755,9482726,7728721,385587,203645,1089204,1006509,7386686,7930084,511731,8827437,7075890,7202548,5224984,257450,7848523,7624230,508721,3551092,7454149,1515629,1187936,515579,3795510,2238021,9103839,7153232,7153233,6380127,1523429,6898851,110890,268760,7639063,2599033,1911957,8042739,8545823,8966985,7082850,9050678,964372,7373036,7501241,4232461,9146597,7875815,7128224,5407918,9119363,8757711,528533,8880696,7310090,7860908,1713574,9451228,6583348,9259241,2757403,440673,2343968,7243844,2037708,6073574,9320360,7302738,3717486,7925898,8832853,7635390,7090847,7750653,6415278,1350755,6910871,4291419,3462259,7216445,8276416,6387465,8325050,387811,8067573,1176792,7259004,580811,7489759,195963,6789652,9313075,6250787,6606492,3370486,791452,7144037,1864274,2588595,8671343,77031,7901497,6300035,3604646,1246248,3099811,763420,2044785,1887835,2948837,2406548,9500169,3656024,1034256,506993,3615,7542725,7851845,15855,3869396,321718,8233483,192501,563972,1114524,8967219,299576,139419,3548269,9179308,8630961,8814341,3991094,3117628,9496021,1697692,7132761,8641723,2317034,1768642,7921168,8008066,66016,6525193,6317967,3077341,6837449,1841046,4560190,8470190,7689037,8878810,8435646,348335,8075188,574079,1707466,3229291,8556402,544775,904315,50187,5370016,8635502,251229,2937629,7772783,9026161,7662601,7038328,8345060,981565,8459968,4170871,9332284,932845,7315679,5895009,1593353,7132097,7664774,6601150,8701607,7867507,4023785,8517083,9209141,281134,901837,2000272,3135619,1524203,6209224,7328366,3785262,8700950,5837376,8032503,7037664,8834806,2728691,139272,144165,7157942,7157943,183848,823510,8112826,9117363,772141,426794,7050100,8053777,7459491,8607722,606249,570017,8864611,131955,4159987,8280417,1336946,4497424,3626204,1986981,8205957,8602439,8900700,2399186,996051,3442291,4838404,1330793,3461278,3755085,9492539,8735638,2203986,8279949,8216346,4825663,8229146,2954078,8347051,6251975,6471824,4218604,7831583,260162,2080477,8362423,2764003,8878556,8083568,4205779,6274476,1756734,2604832,3480343,6285359,7636058,8047575,4050638,5217673,8690842,130223,8256755,1954143,2801764,9492945,6412054,108253,8878700,40424,7128752,8904227,7098612,4671485,4571923,7792969,134736,9375966,59620,7638301,226604,6944501,804332,195180,4771237,483569,9206361,4962102,5566686,8496186,9003302,2314826,9487765,142786,173793,118105,1696390,9248434,9292904,857219,8027450,1886656,8392654,8049468,8169193,8129662,3275668,8408220,6900304,2145916,7946979,3993554,6633160,8764689,3208060,9551883,5740909,9440411,1118631,9254280,7622925,8053104,946951,291397,8109833,1252424,1791901,262142,3914642,8169342,8306296,8056823,2660498,8936966,7627742,1906441,171827,7497078,6802558,9348375,7434250,1200102,460814,4316899,3125374,7038814,1872059,4128355,1524269,7509272,2500183,9270640,2985698,324733,5363694,396906,4091827,585899,3635282,226416,1779907,6019677,9258784,9439134,238205,2586435,7221273,2399669,7390344,7269779,4126711,2244365,6348495,4755876,7727123,9095303,2961194,8596419,7365980,1793779,8749254,8105409,5427826,9002914,767242,5553645,4299029,7087263,5316032,8679913,9224073,133251,645603,4950825,235681,6196075,6841918,8463915,8108737,7370520,5603795,6877503,1728138,1161804,9350442,7098826,3880793,267246,9121456,1330445,6771688,9117244,1274336,7093454,322172,2071608,30714,840205,6246655,4290319,211896,8684740,7901108,8239413,9050032,149392,7718121,3140944,8727080,7379183,7304135,1662688,9421407,879883,1149727,175945,9421384,6475997,9182521,53724,2341517,704512,9232582,6973445,3562188,6332677,2603494,96632,2204550,9200899,4506445,7896908,110707,8110814,7165926,7779932,8199762,2181187,7292305,6413992,3045088,8454077,6026721,4659545,8308114,694695,5829969,7461419,9438088,585137,7614756,200296,9247438,7359332,3654731,767500,1059870,8479591,9005042,1767613,8583156,1940909,7628761,1246245,181172,6110029,185217,3265540,1183821,2675609,8931972,7031255,94305,8477927,1569013,8552652,6126849,4588567,825541,7763960,4211794,42351,4152499,1012431,542144,8357967,3515794,7314642,7778165,8602178,2369480,3112093,4161682,627974,6687382,3796041,9204972,9208317,8810312,9496027,7654372,2072853,249720,7090807,7621590,8708887,2335658,435717,7377139,9459991,1275902,2102503,188872,8882609,879535,4275373,10363,7146696,7146697,1582586,97093,193365,7944092,122068,3673994,109555,2326778,172186,9441596,603483,5648561,5749768,7373224,5230354,8869455,861335,1508534,7834599,8462277,5911989,729561,8097175,7671725,2408123,118429,5180595,7964662,118073,6605983,7128160,9528973,9094899,1126326,9351402,266668,262650,8207148,2400167,8399629,3250831,7000061,6999992,1096068,3194566,8346414,764158,7100814,9110808,3800412,7509048,3910688,8927745,7218402,135843,8103304,8540103,8749619,1091100,1037406,4012457,9097953,3381709,8172886,7217287,8312767,9311840,8247343,1870544,1076064,7784792,5680521,119508,3131836,1755978,6052673,8339297,74415,3271759,293815,3004208,9275624,5675028,9250567,985729,9000760,82500,9501877,3877544,1597163,6067499,2509684,7921923,9028760,182172,2860597,2553772,44430,9510916,1792786,7244751,616115,735225,6026659,6340033,6342337,8871708,1393202,1230423,7038256,75976,117222,274451,2404745,158779,7016209,248272,9081512,6897946,208994,7299091,289897,2240526,4201861,5889339,8890920,845078,7039292,9049921,1400897,6879598,6009239,357941,8600132,8320638,770680,7959914,8851770,4155388,7743258,3256636,2323076,439107,6877981,8015170,245107,504116,504143,1945433,9110665,190331,52159,58760,6029957,1099524,8467183,5723363,3870128,9449466,9513447,6040865,1738713,7931377,597233,7605858,116972,9171567,5621084,8619303,99682,2265449,7873338,1345550,6960749,403183,8056322,6379593,7959475,1166541,95443,738569,4271758,7180153,1762918,7808340,731466,6988932,9521189,8389752,4795033,4812487,5139277,8159475,1162764,7851342,190898,8563652,3864191,3877463,2785261,7035301,8944016,852964,310766,8024982,540896,7839617,3507517,2159721,3985529,1050945,949739,598500,1148159,7547249,1847108,4994501,7828215,494825,149821,9247095,8625146,9245045,2249102,984325,6398826,5293925,3300409,1729209,1534307,5800755,7792933,7399489,7140889,9313585,8606817,7104021,2318924,4184686,2714885,189482,7280418,8398288,6985687,9513439,7658266,1161595,9289677,8393233,4782001,4254190,1249476,3849224,5906193,6758362,1296572,6446748,6482537,6378117,225759,8301920,565808,8145414,6997562,7000972,9531126,9195124,7161955,7494319,8350227,8146253,6271338,477653,5274176,9501048,7534798,9407264,2665785,7845261,4246519,7331529,7896396,8848677,9396458,3374134,8352562,2597518,9401973,946966,7627262,4853380,3113101,9046035,1747428,29587,5951253,8055195,3456958,568931,3280264,7441801,5630684,7176651,7007988,408127,7528166,6352553,8949932,3927569,7673410,9562533,2086613,2943941,8831612,1396052,5757250,6944355,2079511,1764850,8423344,2769177,129127,7394858,138198,3256021,9143529,722142,1564579,1844990,8422577,7542156,79479,121916,8177232,4398043,9346384,4965192,1624412,5834169,9313444,3692600,7958352,6636940,6409198,7165530,7070678,5720741,1315277,6217342,1148033,7086241,9257951,7697011,3391849,8553994,1706590,7116244,5953206,9270885,1725116,4982195,346773,5613725,6551086,556,489149,97918,3900473,8642945,6685240,74065,1348433,7149773,7149774,8121672,8830067,3486055,3187756,4499497,5799447,8007875,8548845,2782147,9012550,41074,8981,8035638,8978157,204735,2363129,678192,7122806,5672184,1003335,7170310,3908282,7721841,46208,7158384,7158385,7155898,7155899,5927286,1119372,9185973,7309254,4152271,9251456,4158101,2375408,3180481,4458664,613086,5539794,5985999,6945547,8167182,7891143,1007720,1122084,1336505,8137850,7320736,8716654,3809604,7374973,1753239,8692571,3552400,8997985,6751393,7031512,8866477,8734509,8116061,9410019,1689379,1205476,2321582,8447668,8447503,9317461,5212528,1669753,1889986,2886326,6537895,9162739,9022622,8485442,238133,7328895,8322707,3591515,7102048,6473309,7051079,6399254,1985706,8134403,7949440,4965177,6972608,5811354,279178,5418649,3909128,977998,8551916,2784215,2295422,1290911,5559474,7913846,3038671,521343,8941786,151766,7727279,1508036,2147331,7744167,1291427,5031928,3510121,4775674,4580095,3325681,4206064,99374,9314647,5030683,8027156,8589231,998895,6696346,6701428,9406494,9520743,9389256,8839623,1287362,1711030,3783294,8010015,7417104,1063686,5334899,4895875,9250957,5290802,5333222,3491800,9482630,213893,8107383,3630410,619775,203662,1509014,9092966,89679,381408,4490989,6863926,3895634,320167,313807,5465961,2015545,125891,9047191,8675500,802006,160512,9352005,922807,7875752,8998051,8418001,8429411,9035502,1886563,1719691,392551,7397775,2075895,3865265,525210,522801,303427,2362685,8208292,1130909,6277301,7253371,9274394,9347004,8015167,9204245,1951146,2070792,1559059,179592,8869975,1928154,208811,1977066,7980075,2833543,9058151,7850072,245630,2669339,5576126,7475299,1381589,3022346,4461958,9346833,4056956,8316491,326748,3915671,4442929,433371,523001,513185,3877841,311757,4167169,719136,225837,1087518,5328281,8264812,242477,6018967,2299847,2293451,7599375,222910,9279318,7972683,3068932,1694266,4823518,6028197,75424,3509725,8653348,8653349,217076,2036805,7854858,2653003,8842704,9185181,5561255,5882883,3628577,8148128,648042,1793896,4975092,8609064,5357747,1664764,294424,2053470,2622401,8872386,80298,537770,8968529,7138480,7855668,4170645,939571,5042656,1386158,45338,109435,453823,207495,5973384,5151023,4203097,1218792,4020005,7641620,8690832,132713,8902300,8243463,1030014,7183331,953344,4098391,7023088,6024125,1133111,1688683,2697779,9523838,6494834,2689355,8449559,7098920,4391779,4393225,3741975,7279430,4159322,6831101,795922,5224579,7160206,7160207,9011837,2438615,4300126,8893680,5097673,167412,575570,6311831,733869,3027617,83544,7230347,7230481,2585403,1026354,7661266,1821313,10513,5979927,8691016,772765,7047583,1697761,4188979,2995946,5023693,9006270,9006271,7864719,7562538,8685692,274568,203166,8386510,2865064,2804590,7241694,8413229,7859784,7971367,9411080,9298500,7612887,7582809,6747802,2563669,4396555,7110820,6303473,6608015,867439,8476801,7673898,8570568,1528724,185196,7471587,7035234,4984472,100707,3367408,1011213,6737212,8652741,109669,7833223,7025187,145137,4159370,4585579,3229711,3487480,7884673,1690027,9066169,9301553,8978920,9475519,8213977,187365,6618239,5005811,8268558,49107,8413912,9264684,5763706,684843,7110864,1671163,8289775,7796383,8533015,1915358,556748,8044482,765962,62623,8579588,81411,8201199,325803,7287222,7595324,8830430,135704,2196255,100908,1905580,4523698,7580558,9145500,5751607,9278370,1157771,7635959,5006486,3670454,579938,6642028,21541,7271053,459126,8035758,8616370,8282388,8596094,8865036,7371114,583925,6268263,2236089,7884635,2330900,8970458,9369386,1717210,1842585,4647776,7351933,7913231,8049578,5858394,2800534,7202603,8830855,131958,7723599,8433750,7434744,7464606,5629811,4645274,7233438,107812,317086,71154,8288582,3453355,7140259,63011,7438035,9285171,75217,2045376,7256692,5058133,198141,520991,7687853,605352,695280,6476492,556640,7367366,3489061,6561046,1315721,5181485,332663,8902077,7116476,81484,7199688,3120598,904199,9133686,8204599,7829163,7759629,4832863,7808015,705001,6465176,9338737,7840390,6734302,2399642,8137874,6707290,1634957,6801607,2692929,9534469,8216556,9065292,4694528,470876,4121968,430689,59255,7002300,104785,119172,4031627,7620433,4834162,4105489,729585,69022,9136769,7365137,2857039,8490737,1364972,4102084,71509,30289,2573589,8035728,5773479,9173235,593831,963004,8281247,8301840,6384465,334530,8454321,9511733,7427427,419935,7220047,6442574,2082645,9429216,163103,925738,1875748,3737832,2050959,8176836,995992,50032,8262860,7111797,6447498,6521625,7733641,553676,761878,9480852,702915,7009404,6096386,5602550,916444,904579,8456853,7637232,8106475,3496288,3593165,596249,7211779,314734,4377043,7252228,5718992,1531667,4164524,8761363,8763170,5710354,7108939,7196619,3095866,9176778,9348564,7780441,4792333,7235876,1530275,5531409,8913963,7181533,396015,56926,8106855,7564366,8490080,6897594,7237681,8433182,1884919,1650307,1072326,1149493,650238,4629944,2191281,7268089,8556510,410040,7306779,2392901,8463301,1622903,6877095,7137387,2357372,9353679,301755,7149457,7149458,6032963,7503237,437650,4607312,167180,2204955,2636737,7199369,4210105,4449325,8875040,5480874,6322007,6801259,6907654,211192,4172311,6289988,63668,7949504,7716558,5702962,9053210,8126935,2401856,7129571,9055514,1074396,5644343,2177862,7116774,5887092,114360,4886401,2181055,8180865,8806962,4253728,1165554,8334831,99295,8636062,2084153,9306867,8252698,7173172,7274557,148735,6698959,6030601,8572107,7330867,2847370,7000017,7239226,8531066,7818986,8107695,7522164,4619216,4830,4705814,9105734,260442,8373573,1782889,7184917,8372745,622037,7823350,4313485,2706865,2075790,136304,8163901,7284109,3872990,9553461,171889,3587576,1740399,8157370,9561712,4857496,63159,7382708,7382707,4129450,555242,3726234,6028785,8676356,8705641,8706891,160940,2511880,1172847,8606699,1011564,1642280,5946741,7342620,5279375,4010249,7965431,8846466,6390277,80029,75932,4488784,2517418,5104555,3259396,5977521,3400627,245677,9126050,7417245,4137142,6288311,7502695,8793097,7677756,1173822,5823489,7982609,451359,8878615,9124541,2533141,277789,3075784,5678790,632304,8003722,262891,5630087,4473187,2942015,6924372,7839603,4267630,9006760,6083345,3023648,6430656,7712495,4799038,284677,3840305,6338185,5970030,7314156,131999,120467,9031997,638265,7127143,5447148,4162635,2974835,7362974,3315949,7849062,711723,6027487,242513,3614297,5713057,9373969,3508315,214424,8086095,7044677,4992338,3007496,2235792,8628344,1133714,6759952,6830943,9121215,6539401,1275248,7822764,117965,2826475,8362833,208110,9051408,5971548,7840980,8429023,9164771,8209375,6978873,7752452,1598453,469046,2390465,6247184,8845088,5721527,6932853,7010791,7208943,6033398,7134596,2394392,3706926,3765744,496031,75516,6231172,3924134,3660287,3672410,7244570,71947,8731443,2181789,7799434,9239176,131420,296429,9113164,6827432,8316138,3929837,6918984,4160938,515118,1734912,1925472,200412,7384654,7384655,607958,2593655,8327842,5464656,4941733,7093887,9483948,1377617,435189,7136274,8524868,8583364,7859801,8219159,6848454,9264368,5382283,1765096,7213915,9235436,2774533,1149867,5992164,7555572,7525772,4981193,1652686,7032997,5179474,4433206,1545208,9318284,7027687,3381883,9202139,1543264,7614971,7711931,253225,1221480,5922789,1174659,9057735,1747242,232824,7169106,8005009,1388810,7557366,2742429,2585375,6964937,9470330,75428,3678629,5576996,3287932,7150249,7150250,9342560,1687555,7887588,6633619,4492375,7742002,94983,8212406,33818,2381801,1629119,5774715,7278040,1366613,1074702,2929541,8118243,1328783,7765450,8452989,742981,9333294,6046247,7252654,5442408,6591286,3587603,1556716,5486679,8432792,998469,5708089,9081799,7596420,1085853,735169,5824242,9000711,7027563,8863899,2646667,7119208,6030729,1088430,272930,8034409,8295519,9121228,3592829,6214312,3331666,2745495,173075,3813348,8198243,125111,7009214,748264,1147973,3559279,7623529,4289974,761656,1659436,1659427,4179523,8209931,8589467,218909,1524413,2440166,442363,8913631,8923819,923566,6896014,1572040,3920036,3589076,8017545,279288,4162238,7998848,7018874,2229444,4028228,2115970,81423,6913386,4017050,8097277,8833046,3535492,605475,57034,8070366,229061,6548017,4376248,6438042,8710999,454276,544316,1165203,4508941,1897516,9244649,9166773,5889951,589196,1818754,2239860,7959281,8125156,4104022,4883776,7536219,7176300,2415263,125747,7467719,7948930,7839115,7711842,390337,4715399,7984643,3887657,9389341,3372685,2577625,9518439,7042383,8488363,5645249,6192841,7563952,76561,2738995,7693432,6299491,207896,6517799,5258084,4511365,1868654,3892172,7770499,9301441,2366567,8568320,3001991,616908,579686,7665682,7313058,9074325,7074713,3516997,2312915,8794989,5117380,852406,8512357,5852379,2107447,8233743,7259170,2628433,7916893,5089795,302181,490163,4729343,501452,9206047,139077,8342850,113514,147273,8986538,7844768,7308740,2575867,6076301,8425489,230582,996234,8618317,2973071,9541546,8293151,2472383,8383996,7478149,7679033,1077690,6845115,6317811,869347,68749,9326041,7007509,7568592,3775086,534575,1889860,5607899,1695670,7779638,5869083,204593,8738126,1965876,7763642,5174804,9406188,4888312,3234346,7031738,8183972,3146581,4295080,9110716,7228231,3040357,1561288,530840,8569749,1101318,9192535,8598728,1009910,7721862,7939191,120182,9056580,7652230,2724771,8048383,8648313,9107755,8768052,9024778,2522629,5927880,6920710,7126918,2434568,1582745,2408681,6720589,6121347,2265893,7507791,7507867,7114261,7512592,94465,9017234,7741433,1161978,1949265,9159842,4818367,2118604,6847777,7322270,3862592,2069574,8153446,8036315,7310324,7780875,271091,9185807,9491505,7775453,2899112,9104545,9535283,2165894,9173842,8982855,569471,8456498,1655236,888097,244982,1315451,1517588,422491,7362113,782749,8042254,5115628,212955,3501601,9492127,9239124,3164389,1384304,5168416,6889768,597224,911276,965353,2866609,1032444,8437181,1793245,9319392,6760204,208949,7913213,7853285,9260124,8572203,6187996,120987,211895,7285130,4909594,8396754,708430,5786541,7727315,1158713,2708917,198322,8792479,1667860,9146182,8536301,9217081,4700639,8147616,9526983,5781408,8167764,4165230,7782446,8094704,1903036,9430925,7142661,3790926,4893205,6977119,3874406,5344181,7483888,7704970,9002281,1085109,7891455,1178122,4611028,1729053,3160222,3949697,1200219,8757633,1763950,353049,9244166,5900385,8870108,3731451,7777451,6907445,8739375,7977186,5772492,109615,3811785,8679198,2840410,9535715,57855,2168012,8653786,3140902,152130,8649849,1894213,6627769,7703971,6209437,8765920,8737972,8723913,126667,538359,442677,310109,3913862,4803565,4158965,3670925,3013970,9361094,6029099,7048976,8216465,2686963,4369714,773144,7389905,7535065,273640,776353,9129976,1016234,4953240,8849792,5201021,2758141,8783109,6438876,7652256,6275189,1864913,8586046,2811406,8492448,97284,6389523,55784,7251569,26691,8416887,1314152,2568544,7984289,58858,5707789,93107,6613546,7019211,8166643,249902,7941748,6039428,648855,7211398,1008950,7577589,1955139,8475728,7331226,7695213,2880470,4544980,7127770,4313572,869374,7289885,8162394,2833015,7751805,72304,3840125,173573,7550328,8816206,1754733,129158,2290742,8347206,4072716,9503553,7804141,7832022,8947974,283186,7159487,4890055,7902890,488069,432753,9407186,4732632,1743588,3917480,5112826,8687386,3870539,526439,9128159,7563409,989157,2537992,7698749,1897633,9007338,8969090,9274190,111002,8267459,155683,5091208,8863554,1683961,9450527,9203753,9263213,2454674,1239849,8359203,8608437,4171485,2055747,8434905,6703333,7512816,5788431,8967429,1764664,8770007,7886535,6225961,1057275,6873386,3632759,1874347,7695212,8147828,9418175,4751295,1883215,8564365,7275112,7601979,8039824,1645538,7811783,275572,7305598,4166514,2997353,7304569,8281542,8815430,3733089,8692619,6853992,9153940,8208398,577346,188744,1285028,4291353,7980814,6572074,7968907,5171236,9166267,5434882,6866221,9329017,1554823,2168916,4201357,6710314,4828708,8279502,1276721,9212804,8089054,2137657,8932231,9255970,8425544,1067343,484334,5318171,2663258,6031640,860743,7361616,5987073,2694325,1719763,3648551,997711,5121802,8293517,8955340,132898,8048315,649800,1271495,6900617,7800815,6216034,1910043,9553014,9009784,8836027,9210383,7016176,7427768,8293819,2461916,7061608,2278703,346487,5261156,2917991,6479819,1329731,7098872,2905367,8592615,8596552,3012635,8258046,3554308,4262077,1150835,1935142,2583207,8807827,7260907,7776877,1779034,1125033,7088188,7602593,7271690,5981106,6284693,2125063,920509,743890,6845385,265603,480869,7450611,4748074,124974,3930515,7172883,992970,6461324,6522165,1647199,6589279,163999,6963293,7051272,8731059,5803386,1823250,1897864,2025190,6877432,1902424,424641,69778,2847430,2712215,427430,6029047,2648029,8855468,3011570,4158041,30553,5820135,8390001,2160480,579150,7028897,9215640,7507142,627207,5991207,2241711,8333898,9494780,8297269,7112185,2646637,2649205,214841,2629831,1304918,1393982,1090701,8613383,7196471,3895571,8985577,172202,1360979,7708815,4778419,8828355,1089897,8036720,8285479,8751678,7335554,4696646,1159458,7745730,442644,2070474,3290254,8787821,9452419,8444996,627365,9432120,8470993,5623808,9082537,234766,710178,1868684,8888265,8227924,6863715,8829952,211521,7593833,9540248,130544,8266582,6246515,788611,8414402,301410,132893,1091319,4238215,1566310,1772560,8511524,3910097,5570447,7475758,8513708,8801083,8348554,9269855,6278144,8876250,3432289,112707,162778,7283635,7629728,8940080,7171506,5784774,750889,7850325,7193375,7193376,9246610,7069610,1142453,7089379,295179,2877071,9229224,8204721,2022610,2450528,1914426,3200713,2738419,4316113,2943857,3506701,7076996,881131,8992284,1573261,6739912,4839886,8955611,7788831,193166,8839137,6951029,4256440,8055564,1298525,3783366,189514,7947737,7548945,9221614,323402,3481210,52456,8225306,7375477,1511645,8781158,5710486,9037045,6387719,7261097,9099151,8450548,2612458,173323,8769360,3697647,315544,8163348,1394372,8551294,1681432,4810531,6070565,640749,4655813,120988,1548748,781606,8304317,2761505,2421155,8611138,7524630,7533521,4164482,6318529,2737837,5068141,2530684,8421690,5908587,7056105,7778909,8872428,8567696,1642592,1054506,9332338,5309654,993864,4650344,7120145,2680903,2240382,9388725,55210,533255,8677889,3569511,9368965,4170658,7799092,972442,8795501,7733102,274718,8659937,1169776,2253440,4265545,9483189,9231067,3228538,1165317,7567082,7367036,9256779,9098802,3195211,251652,5404564,7314978,6446106,7263523,7263653,7846835,4016699,2905313,738857,536972,1114506,3308050,9246506,6235384,3129421,210782,7242546,9271738,1336469,497690,1015152,8499967,2665705,96898,7544980,2575215,1854308,7297707,7026335,6843501,7653517,1042479,869761,8441111,7481267,6950225,9457807,7564693,88237,1233879,8949918,9277482,301206,1188872,3027521,405006,6496247,9334702,4866487,942973,261882,6051668,8338202,1201824,1857473,276651,3919784,8660287,2246417,1114101,3111397,2078277,4293547,903148,9547078,1855097,4422475,633429,4587091,5112472,2841235,7430420,4583443,510656,7224486,2787832,9052536,7143600,7143601,2596393,183168,183182,4008845,7233207,7371860,2770829,3411025,3440128,270402,454942,898726,7090841,2013040,21410,306165,4341691,7079689,9336232,3673430,8264969,199804,824138,7210438,1314602,4240804,4623497,8229967,7722905,7064132,9141351,1126140,5618177,5981265,8894270,8907627,8673895,2694989,8549748,6545461,2939792,6987718,2115961,7808453,6243367,7324935,7324282,280351,236335,2123767,2316896,930328,6299465,5274476,8694025,1330856,9527019,7811241,5036770,9013744,9458139,1219617,612920,9011534,8180259,122035,2312159,2757927,618368,7167403,7242621,3643442,64310,7130743,5464251,8188054,5431252,5918193,7004920,6942143,1101201,1899541,7862341,4040666,141544,2028513,305440,3234274,5673198,283018,1128989,1928004,874801,6553552,146762,7830742,6998569,2358938,1972290,256161,6996893,8623781,1569223,8242407,8848892,219275,4089340,41694,4734489,4170726,7496934,709288,2364791,4123378,7280307,7481234,4762195,61861,4983557,2572473,7912228,8594534,8133949,4881478,7664966,9030221,5449611,7768507,8843975,7684428,8225484,8714693,8592001,1904320,7968667,4758793,7030121,6515558,1671853,8753180,5555109,8747651,5331269,8847058,9347932,1807051,7150671,7150672,5661431,7029933,7385264,7458925,580926,1400459,835099,7084753,7089841,5738318,215130,8875280,7865067,9452824,7810108,171913,231918,7591765,6877922,589100,8552643,317837,9063281,7259654,1626458,3476698,5332202,177038,122340,8340109,3740121,8330616,4610072,4166963,2730541,7290226,17760,8443604,756650,2580495,6610494,7306524,423621,8699906,247916,7307412,9133496,3997022,4478653,904378,9481370,9388222,8014182,775585,8509382,95611,8050246,8317423,6051656,4177483,8336977,75763,3815061,7756459,7860102,8895870,9382033,4867810,6579775,7977421,7305494,2795956,7183087,8152526,8716512,8319984,7402749,103629,3513325,7429728,269689,7331951,8981983,9018133,9531161,756311,3265204,9423006,7396992,630724,1575781,6610049,7197078,4621661,8640696,7524838,6995952,553682,8959754,2058162,2947103,4640492,9015140,7251008,7446022,251711,4492093,9377658,230356,6156055,2333597,6848773,98242,7108018,5086189,7859391,8144047,1120935,8329895,7945949,9529090,7763921,8069133,4323007,5809074,8585102,8683725,7578882,9195516,8977939,8163397,760729,2143674,4584037,7958182,8757776,9489059,6222475,3859166,2721271,120301,8438360,6849159,7925776,284632,4197811,4197748,6988938,115168,8659485,8050067,1059324,209331,2412269,3215953,8713527,7642908,4516264,8373354,619149,3514627,3008744,3124867,1307315,169600,7616825,2446790,9156124,5306141,3245368,5064448,6213697,6200808,1965297,8176835,9422427,369400,9484156,7425634,8886479,8813258,8104503,9086470,5321093,7674762,9361917,7894673,7008192,7814955,5033563,443305,313607,57807,4725983,7715074,7278296,8616093,2672585,45054,230804,1649332,763855,7137953,6118953,7870797,6517175,300233,227286,7459064,1546174,1773787,8976308,6994343,2084535,4776508,2667287,4750767,8706221,5751949,2840131,7845547,2272526,8203263,4150549,840421,8269930,2991275,314899,4602901,9334974,6837575,5052943,1064427,1916268,440266,2294912,136566,5915718,3661214,1145516,870007,3707142,179326,5347685,1228308,5468217,8874390,1303604,7554240,4684826,8554320,2621185,229979,122005,693195,1592129,171799,7076658,7899103,1073613,8848871,6680905,1696465,8914080,827641,8829183,2227680,7179870,4136545,2389487,63712,8582145,2786962,3029129,8545136,4020716,5447349,258749,8660447,492746,1113621,8830226,9184496,3078712,4008758,1913628,9081838,1332851,1712074,1776736,2101843,7191461,8218532,100510,8310249,7742469,259120,8053441,5115280,1163061,6036716,8099124,2080817,5765704,2814700,7713695,4667135,8995673,7007021,1593086,4051928,2336729,2944439,9476646,4249348,4542784,3661949,4520638,9273344,8594380,6261339,1359212,4854088,40096,3853931,7994260,9223390,7723977,5905311,214431,7613263,6799294,29555,8028314,8209953,4900582,4088380,2524132,8527638,8600572,7973649,8351052,7710979,323295,3898571,6875783,1020090,6634852,3701010,206379,8277455,8948229,9270622,3811248,9232016,5916075,4617044,97985,3580799,8961726,2722771,8818420,6965946,2987663,315862,3861839,53747,7073746,3985253,4070187,4070226,1153333,8844352,7323587,4116542,9510422,1863323,61470,6966599,7004411,8720301,9486994,821497,8106745,355727,6341201,8867747,683070,8208087,3137476,6610429,1947141,8135003,9444633,4481536,7856272,3096106,7041888,8998547,5279183,7337316,215574,111271,6977053,7438735,5205184,1305446,1679347,83471,8833714,2400140,5213335,2391656,1596278,8278030,7831038,8547006,9162028,7330248,8557460,7237877,6938329,9374386,6720640,4257355,2860594,6070346,2907020,9505108,5636936,7731359,8435837,78531,8218227,7940617,9107371,6159928,2429618,1324277,3434536,6028583,8776446,5838522,7233671,3777021,3362998,8088487,2704753,8508310,808519,322898,1083810,7839317,71138,7888085,7343988,9415059,758861,3888704,8997412,822604,8683135,4165376,4608250,8818690,9369018,8463020,8029516,8309214,4824607,748376,76158,1983567,613641,8383102,1006121,113543,1114818,8813636,244045,6964289,9018106,7284095,2404064,8208491,2732537,8516827,7917596,8662665,2687221,597770,223434,58573,7160481,9032982,9492876,307174,485438,3348697,6801613,7121373,7597181,7302133,9490201,488045,7810707,7774444,8450902,8908416,9406704,5707471,249663,7132254,748189,7405172,7800944,8967100,7230519,7092023,8282435,4061655,8761697,2632336,8876140,6917813,3227956,2099005,327100,8822755,5323226,5829711,4279702,3498097,2205018,9160293,322645,5173801,6826230,1139856,9018290,1162266,2237916,556493,8097032,8994933,1526762,6678250,9139285,7597672,2121898,4129717,9536255,8829255,3263347,1740777,8540590,444366,997758,5146319,1612283,8333189,8727580,6815635,1176786,4295972,8271792,7037613,7675865,7718409,4080618,9266546,6896918,523739,7002702,7144248,7144249,4602628,7755532,7125949,1257686,8547558,7941169,68420,75494,5973447,2604994,4896091,7902624,8761580,8203334,3192010,4739190,305743,6882735,8030844,8151602,8852896,4899025,231654,5876757,2492830,5064556,1099308,1755735,7884926,7965480,1873474,7377133,5797824,7952771,1158055,6027099,222456,392557,33265,2681509,6363915,8191452,5312096,907018,7725219,3775281,9205774,7100866,130040,2691267,187591,7595303,5943873,7693457,4899784,8543323,6921679,7319085,3684902,4498015,8060537,8253000,4586638,7086534,2692953,3540805,7466165,22054,53356,1932374,4645970,236181,5261423,4229689,8277571,8016918,303876,7894460,8930724,7514039,6606953,4234159,8292867,7073986,90618,8553701,3907811,1305620,2171321,898747,4832119,7051276,386685,772258,7997919,434658,7573732,785914,57252,8484219,6726940,7322327,7048322,2620335,398211,7129855,6512840,1197528,5112538,116862,115176,2022547,1788469,2584243,4132015,8187696,8245758,6613103,1693306,7602382,217201,123569,2336459,7723299,238981,7740983,248926,119299,6980346,9093787,8457899,1152175,7618752,5729021,8655451,7662429,3435199,2714001,1871279,8244059,7946479,8567248,124484,8094552,1224855,9455555,2446247,7158314,7158315,7380682,6816965,807911,7448882,3994331,826312,2500222,4995917,1725322,5971308,203531,8380524,8009386,9073524,9373793,2671791,5688851,6163780,8765236,688518,9405255,2305364,7486764,5876247,2976731,4412278,3000065,8424312,9007992,8706445,4221649,6018993,7282014,4210921,9255598,9475895,2200755,7695336,154111,9394755,2717497,8736714,7343546,2221218,8082746,8775913,716227,8985167,3886394,1109619,6046502,4164881,78721,8471106,4809625,6358321,7351746,7771597,7565114,1830378,1328603,4173167,8923073,1222743,7093979,98157,5293136,8817751,4705367,7564664,7855387,8698297,9349401,7801210,7498974,6584176,7527833,37880,7303981,649605,9090302,7140108,216906,2185374,2468147,285443,8749700,916070,435606,8016651,5592914,8520868,7308979,7840918,7623383,7584378,6953206,2287757,9234100,8465993,2546743,2617876,4711532,1581701,7124048,8150668,3055471,1798429,5516319,1961805,3279328,7590451,7651450,8181477,4234816,989461,1076229,1083225,7206081,1676956,330583,6568537,7118482,2356508,9012125,3775596,198615,8682240,6365939,6763813,2368727,4751994,7149011,7149012,1583846,7452217,1396970,8528631,6419518,208715,687588,1587758,4819885,7340854,3255292,44849,6946632,7297053,8640802,8551532,7084289,1646261,767212,9226693,7669388,5997800,254596,713889,7764769,2988314,2102521,8461564,5236331,6957016,343405,9000512,7850065,396546,7699895,3409807,2408855,225258,781948,2225442,9430803,8198931,2132905,7896272,8403453,8978209,635028,9296938,132843,6888679,783361,6352175,8280779,5960535,7010683,1507190,1556017,70769,6394806,9532325,2554042,4070826,7589682,1135739,9279555,7579368,8840943,8874639,9157081,1800616,9300005,9189320,7239515,892979,9315803,7459997,30347,7461265,1954578,8458405,1138952,6906694,2236851,4093396,9409581,170187,8910618,1752963,8904923,8320836,1246965,745888,2425283,125942,8860865,7887311,8094146,2221866,577394,4160152,1603877,7519719,7502141,6631141,6059690,8273007,8685709,1717717,9113269,3938501,1128830,6113616,3274594,3007511,1506581,6919865,9104129,8724049,8484498,6559348,6377597,9002056,1016757,7451014,9473666,639519,3994349,8720485,1935490,8692909,2992301,5798559,9206103,250168,5787993,8690906,8207279,442464,2689477,7437168,3161284,6874151,3938198,194383,6629359,1508978,3604790,1181859,8952346,663078,9257668,4306534,8870256,2612599,4604044,7417683,534176,9271559,4337695,5126983,7155784,7155785,94012,7591423,136272,10480,8344116,2952050,2958191,67282,4090981,3495394,1647457,1278449,8742545,256653,3644294,468368,1785919,1054731,228204,2295908,7408722,8574415,6124959,476240,1945349,1269629,8237200,7756653,439786,9089409,5030056,7681796,5439651,4774195,451722,4266760,1205899,135463,8999996,130036,2187666,749566,1715359,7184693,1918612,7088888,8085594,7661693,3785097,4354375,2690555,9243119,5165659,9520419,1607594,192111,7827043,162969,9146084,5879952,525077,3640982,1956543,8599821,1326164,109825,3212185,7134225,5161444,9259634,8766361,1302263,7363277,4586521,897067,6275186,8297480,139870,569615,6021185,7637505,9347774,8616072,7080951,4088959,217449,7577039,7307222,2353526,8837833,7923423,6173143,604034,7389516,8074546,8704382,8999250,8031795,8764806,117249,3927083,7942149,2930273,47317,8214727,5575823,7648763,4061090,9210270,9238502,8630172,8562257,1891195,8000314,3506512,7330279,3896876,2023636,5216830,8776767,6760243,1755819,7131064,1765552,9173959,7663083,8001378,5470302,234440,8436157,7493400,8098621,167690,8668110,1515569,8732352,8057837,8932349,2223081,7632439,4273255,5672436,1255859,174534,8883222,36547,142012,3848969,114876,8979115,5021881,9293096,5305121,1784221,8343512,4641452,8123881,7985536,236474,6027665,2358965,3164092,7174780,8117381,1952967,9248662,94536,6952668,341959,5172790,9176679,8181050,18622,7742126,8994699,7479054,3461017,1603805,7191439,6298753,7386788,3286456,1017348,9512861,5685621,230320,7943378,8693748,6270741,4583623,4634357,4052699,2082885,3118198,125128,4028972,6365057,7639728,152267,9494574,6952983,2464439,9121467,1177296,7122542,7712778,250373,7194014,7356914,5571782,9249932,4729130,6350049,7983923,390150,6502337,8856105,7322732,6988071,9313177,1018463,1316066,3836105,3917393,1350872,6612507,8427332,8289269,1246593,8309575,7053178,1824693,2130601,117045,2131717,6892612,6981097,9333520,9371304,1583057,8280974,2241372,6378611,1631453,2059251,1885333,5218666,509561,6731035,9126002,2189895,7269904,2850907,253636,174214,7730004,865144,7603346,4795396,8327528,208636,8042552,7229640,8423785,7837623,116711,2186169,1889506,6525419,6995239,2076738,9103298,2874410,8343523,27165,226886,4324420,7418308,7637456,1753740,2119882,4170604,9516772,9280731,719590,6949418,7670386,9445603,7756210,675087,8547959,1019400,2621893,240198,8919360,5513055,308823,7777533,3281038,8379061,6820649,8976771,8885736,7971170,395880,6970654,8230538,3020843,3021524,371971,9537604,1043958,8506225,7346194,8710205,4322578,743482,8191576,573954,348872,170178,69730,7683867,1148705,7466711,3420289,3490030,348711,8477544,1066389,7361722,3859733,8980730,4173843,1967595,1856954,7181295,4400311,5404849,4273057,3695340,8399436,8853852,8138981,8005629,233735,8976486,9197145,1784449,7115481,3835772,9480795,7156484,7156485,2666397,5619464,8662359,210744,7319888,4548049,7280170,6862786,6974127,9415390,4799299,9744,1132500,6868833,8658084,4170667,68834,2886035,7775213,8465664,6642277,8795155,5118892,3912668,621797,2860918,5758240,6468236,8281815,8812659,3578966,1248597,5200025,6826182,4880887,7466438,753064,694548,149164,8643684,9286999,2331770,2600851,4200991,4170079,9024191,2342771,7420704,4089817,8454565,982582,1828065,7586810,5522850,6988004,1751883,7544245,2563066,9539736,60124,5246297,7282777,9370365,9464459,2930798,1682911,6575359,510377,7538452,277963,2893589,1393511,7317274,98373,8855760,8398361,6936706,4781350,644478,1908717,7858489,5912904,9023951,4266481,7468491,631617,6058511,7690978,9122692,1178034,164479,3511375,479300,433845,8472892,190691,3876545,67124,273415,2052726,11596,7453084,8869491,8657984,8643713,6150649,3933260,1026699,7295505,4534048,3413296,251795,472424,8207465,7373318,450075,3888890,6054827,395793,98098,4700096,8378938,8103583,974230,3437950,7093489,4295455,6451324,7094128,7201184,5077363,216951,4604326,318760,67055,739072,5856051,9107871,2918033,7744495,9261852,7686362,4838398,524915,2195196,6164254,8487955,7855298,8822881,166599,9008864,8460622,8130095,1160569,4097401,4422676,3589721,6426270,2165624,7686902,6442874,1113171,4523500,2969789,8949646,171499,2492629,6941793,873910,7081454,5668185,5991477,8871574,1811122,8137370,176801,1524872,7410452,2097310,7742612,2987141,6227296,2092210,796147,4982789,5616056,7543428,7181655,5022184,8146577,8531172,1976073,6188590,6274596,6726679,1531607,8912800,1671814,4261078,4123303,616118,7025224,7108141,7168801,3727758,7076180,7182321,8779565,99255,5678514,7412160,2272364,4604101,9188466,9058525,7155033,7155034,541959,7595176,5095753,9034712,8932166,5921202,9395057,568245,6491084,5152685,5375758,7295366,6372227,8088493,439368,4043144,4776196,704713,5537421,3703905,7772098,7495869,4467646,7512275,9039833,3085234,437604,509456,7712922,6865774,8042944,5753347,6986624,6869325,9232924,4282795,7427572,3822063,2715863,4273189,6233875,3020954,184945,3013337,1150957,24211,3483838,715719,7773034,4296871,9022871,8999727,111961,847858,73195,6025313,7173666,9433579,8662501,2405213,7808852,7335703,8070443,1321640,8930028,169436,1831077,3599903,8328930,66421,5472777,207501,726357,607316,7950158,3145675,2580357,7447269,9283468,1794574,7993363,298962,5658857,7604693,1259420,4133734,1079421,6985995,1175589,120872,6430618,4568722,8600558,2523529,70594,4151486,788795,78410,8880897,4162257,7558805,304757,6613444,1922860,4992323,8693485,8867253,8085193,1321832,9208491,2383004,7142833,9014423,1032333,1062159,7510136,2503825,111395,9223737,9479213,865585,7102684,178549,2474378,6501569,718752,8089362,1687468,8101878,1777333,9163423,5718395,768463,8407090,1030746,628443,7182329,9073505,7830022,271893,7896605,7807085,6964835,7423542,7259700,8900408,1896298,2696843,8413674,5749900,6028535,4110128,5917344,530015,5021803,564542,8967121,9255856,8700051,1158383,3469399,8013526,7575767,6916768,8920892,431745,135700,1522421,9341654,2136538,9171140,9409585,8789607,8668961,8812368,9115398,4273876,205026,1682572,494171,43957,8352380,668886,2405441,8596464,1051041,7647665,7647472,2188764,7053275,3894701,6911156,9347621,7279015,1922938,6924080,9178835,1291784,4866409,6579565,7503879,3891611,732424,113560,134471,7171984,2062779,3919577,8386737,3192184,2592902,6023237,3620528,8664648,107655,8659286,4997162,8417992,199236,164358,9105255,3400777,2087206,8344672,627167,246316,5007389,8281126,5237321,4695044,8687984,5944224,8349723,9037878,9119402,619659,7742912,2344958,7643841,754819,9299098,7851299,1941128,8219739,6812980,598328,8946109,4314376,2306987,1342118,7393838,225089,123470,7267353,9337863,5433163,7602249,8663482,1214003,2233149,1670905,3315616,4976912,7839211,67225,1075890,8562108,6105229,1144391,4741464,4138678,2890610,24187,2988221,5771760,729375,7499197,7002552,759688,8424671,3817884,6995626,8535113,3241486,9524969,1818235,6793918,249538,4567525,1307318,3564162,996757,9149104,9145246,826514,33685,7455200,1170634,3834641,57342,63048,64474,6948834,7663809,7079336,1250186,2644879,6432932,6370227,2743251,4608230,5829633,8802568,7066760,112689,2153346,2874416,8987897,7698916,5864955,2764555,186377,970816,6829810,3080578,1852865,9516115,8224944,134995,7613487,9177196,180968,955009,3721452,56961,70689,2062950,2490397,4689605,5428129,6030549,2283806,1380299,1842168,5305997,5133076,9354340,1250206,1208756,8441039,8790182,8484907,441285,8201382,8201473,8548839,4009766,6858148,8451005,8293400,7616353,9043258,9141135,2272166,8044781,8689934,523784,7257718,3954497,7883513,9241629,5196032,8905294,8104770,1744383,5993031,7940150,7940151,7896794,7866023,2819266,9110602,7681256,6886207,2191212,8329301,7078645,8715953,114260,9386859,573929,7849809,1337624,7687532,8119045,511112,2482606,9080327,9164285,45017,8136333,7964488,238287,8416769,7520847,9018050,4793743,6500201,208703,8667509,631117,7982517,7282329,6917411,1822551,9015218,350438,7564212,4470520,5381563,571217,2642593,6480149,7313273,1070052,8680617,2865880,9261475,9155711,8741058,7841182,2991116,9556105,7905696,8098400,6791224,7001611,8505766,8254310,60442,106531,2445647,785183,7778818,7486178,4264381,3075166,350075,7461925,9449150,941149,45544,8817643,6921296,207470,685203,8002248,9504190,9450182,2458643,7295098,2082087,318404,1899205,8147896,8499652,8689703,164980,8942137,7102776,450735,400405,2228313,9057260,236714,3345160,8223165,3573291,9326066,7816736,920200,8022737,6836888,6929665,7646390,8422256,5439510,4337413,8748368,7529085,7681820,5644355,234232,6013131,4744521,9294951,255646,8282537,1229772,7508831,346681,8490397,7920793,7130970,5656121,8367403,707160,565851,5877057,8966188,4167186,7680175,8923712,2776319,633354,1161682,8526950,3390445,186576,3146638,709738,7919648,9212101,6883251,8999030,2488249,7015884,6877370,61112,9244149,78740,6824899,2812195,871169,9399792,32835,7556611,595343,7331031,2071056,6650692,7233010,6871102,3622604,6013561,3530086,123570,1269191,4810417,9316610,4124125,8129739,3058261,418911,418906,423109,9185507,187099,9118413,6691405,195853,7288908,8263339,7743226,6577231,1089708,7901691,6332087,7735656,9345818,6369071,3652544,8158524,6979180,9101058,4365469,8046558,8182506,8874300,6432970,7927172,8027911,1815475,100899,6578845,4467316,7949614,8958413,8821799,368941,456448,8830112,8021922,7654359,8058657,6901737,225724,6727261,1591430,8394647,2299064,9279145,8341696,7613731,8619558,3426964,160015,62235,1178274,6440220,8346003,7901864,7413048,5040433,9562516,7364491,2044446,677427,1922040,5437047,7576311,8792415,5111164,107861,6769558,7674442,2934872,156307,7087576,7088113,7150043,7150044,672216,7829446,7605448,7619561,7684401,7515163,7488846,7522518,8608474,7605484,7594229,7524668,7383623,7833864,7515222,7514225,925141,2357150,860920,4152410,7502849,7469159,5255510,6829583,9000772,3453739,2029629,8263780,8977639,7376043,1698130,3178798,1913047,8611929,1395464,8243630,132858,7499900,7499901,1918874,7877916,854923,138147,6126882,7842181,4119706,5188589,9117009,9395937,8514659,104177,1021968,2494060,1019463,4462624,1965105,9366357,1013627,4056983,2325686,537038,8288912,3732585,4171377,908131,4787332,5203414,2572315,2734683,5768706,4245034,1659187,7781509,807839,6964551,5635391,7894900,7737235,7368858,1195116,7174585,7072882,7057906,8809862,8598036,233775,8995190,7422996,170393,7654486,2912714,7898622,1889419,7272798,3124354,9217905,7778717,64379,7559792,9288983,2389652,4250632,5321501,1158339,8686158,9535835,8035376,8010975,756997,778978,9245678,2588031,5290067,4569472,8285118,8336366,7560719,159520,9374655,8081197,162519,3888083,9352316,7645024,5328233,8623514,60805,7934796,6963239,484826,3798054,8515780,7892917,4275100,9056859,8634867,5509185,9296389,8223090,8638582,6293939,7190577,7884319,6404878,1147169,6852130,68273,141801,8545805,2887391,1714210,9053155,2842240,1835205,8488194,2238897,6362995,7001702,6971051,6820747,4284474,1398035,950075,6777007,3301417,9455914,9384838,4969500,104956,8809772,1290503,3774987,7230770,7792144,7037583,2490811,7010753,235326,7542455,7665080,6331783,6818095,317422,4054055,4011815,16066,504470,7690419,7355592,8801901,6847951,5909397,7509436,2334158,7407350,8855747,8164267,3903776,8074471,2287238,8167761,9064848,4732602,8083047,8949460,2301587,1095264,6333753,7494429,2783659,9077288,164390,159119,395196,5251985,2669527,2662262,2211390,2601727,4054703,608388,9307887,7159110,7159111,7159108,7159109,2791192,7603141,880831,7702898,8944450,8596602,4099588,3536053,7224395,41335,1400456,8262529,4726931,5690725,6183835,1881964,69627,7172687,543371,7884373,162250,9265430,7624631,1162677,3273034,2921366,8270980,3374266,4974681,334339,4148346,682200,6177283,7619261,4756276,6366823,307677,7013458,7134872,1378250,7104064,2354585,2304740,238398,7733907,6302929,3478456,71596,2264711,3159187,8983078,623115,7882608,7117203,946750,8753953,56940,3540256,4206931,7176037,264914,1305674,3195814,8611387,2031621,5479197,8723689,5178677,384970,802073,1207889,8385311,3104950,4217779,6071906,6390983,72822,7267659,107785,940294,3874331,7327087,7531948,7748094,3840314,7135712,7871904,6360471,1933140,648492,6641722,9200953,9209868,3056758,4163088,9103386,2941736,4980887,2047206,4974387,48196,1065174,8248296,8133378,7545319,7412555,2565262,7129622,601742,3657203,18349,5617223,500501,8345190,5389333,8964390,6311589,112373,9132580,350969,9336162,2070906,2713649,638469,8738842,9450136,8346524,6545383,8501546,2252333,8151006,966874,5239037,493034,9048326,7647353,176014,6995218,8803521,3409783,8367573,8617138,3780600,1922622,2595511,4288864,8917201,489836,8653312,9140144,6607630,4066662,6344961,8250192,553106,1694515,5363868,516473,6486407,9152232,5506800,4070172,9027746,4561471,8714938,2327765,2333594,374323,8947039,2487691,1186391,9472322,3064879,9001010,7399483,928942,136382,1186604,991855,9122447,5883639,8351625,1349066,7942906,4867822,71336,185814,4404874,6301891,8694191,2041470,3675206,201509,8785700,569808,6768280,1097922,1993023,8881780,2023021,4296747,1545505,1550404,7063142,7062048,518540,6664885,6955168,4087189,7680835,9341879,4141504,9161653,7100962,8874095,5553714,3382150,2148543,7191007,8450118,8453501,7081319,8573729,8153359,4285804,8844131,3159691,21490,6998896,6976481,6141334,8134079,7666191,3229618,8182484,1941074,123679,5657777,199195,9069382,9522775,7339856,7885806,3701886,7006229,1209041,2311625,1071030,122815,1271069,1282739,4019549,8380893,123762,2127796,1317869,9042111,8454567,4041479,5489472,7746782,9212081,181143,5650502,1977204,125898,6533431,8537686,7787142,2944622,7951523,1977345,9491559,7064929,3376477,7318080,9309008,131771,8229117,527021,6438060,6407532,1574680,4339582,2073429,711399,3704286,5024089,4095169,5251382,1518056,7123837,7273041,1107516,233176,9356916,1920914,4108169,9042498,9246943,145509,700431,8215496,8872614,3262507,1285085,9262191,6073034,7535338,8552257,5117461,3988535,343588,4213630,5724848,9522988,144374,9363314,5634824,9318257,4806310,2949302,5680353,403189,7132752,6862696,230523,6371061,7273893,9301515,1281428,9046163,5253686,2562622,9158290,9419641,8685197,8898446,4994231,8403955,7535724,7578768,3044521,9101248,107388,6404090,9327070,1931376,9514593,16342,9477436,2599282,1047285,3643760,1008423,292202,9234630,1990833,1830771,7934830,9323860,8251371,8874698,640143,6924430,1563355,8312561,7820795,79741,7631155,3149821,1062579,8069910,9008196,3234307,9348796,6491660,2523370,6403716,6838194,7341544,7770313,2513353,9416224,9416225,1671631,9137138,566409,5378515,2742407,7421925,458287,5011475,828470,7173715,172394,9462949,1299566,9100547,2963597,997741,2576699,997719,8088384,482427,288405,6098779,6548044,8440000,8553584,1927744,448900,7949878,36158,8543145,7180423,8376099,2403827,7096716,3999536,7684972,9532257,8391254,395845,8315044,9326120,8452203,7851113,4151197,4762063,8427157,9149947,3413347,9154156,455688,958948,7007236,8922379,8247083,7005207,8551227,6933626,7601461,4963128,7002332,7830123,80424,4958691,6439550,9407846,281320,2898860,8325585,8325602,9448798,2358242,251303,1145591,9404577,1964397,831923,6345557,133492,8730410,8670607,3016229,100165,1628762,244627,8047135,3943310,427241,6964485,3304741,4029521,1994271,7443655,7786384,4861876,175994,261330,4471450,653172,1227408,8848390,2089651,7368879,74616,8940774,6697084,1911069,5439723,3307324,8050250,4236117,8989095,2715071,138296,5047966,378574,3092707,7957135,1628594,6223054,5497755,5022595,784174,5471502,2090974,212651,9250048,8303364,2636932,283519,7368481,7843548,6554395,6532027,5047939,1385213,597584,339023,7022721,7188625,1396436,6524965,7943773,7906340,9040233,2122759,513683,3331354,4096762,8235562,4157368,84111,241682,360900,709542,7603245,9407732,1012863,6346707,6973952,8200748,8811194,7691087,3257038,4162367,7545568,3235063,6210271,5179706,8592088,3064159,8543244,6683845,8011074,8279872,2908838,7685222,78696,266587,414316,576896,7210633,718737,2754505,5018666,4279327,7064168,137489,3243484,2423954,8025367,3883202,1524119,1396073,7400627,1222482,9377290,2097592,2099686,6643129,996832,969598,1035918,6730549,2861050,8246947,8688166,9003498,2977982,2102197,2089519,8178434,5339180,6021309,308158,2832277,3764292,150810,713313,5162392,418678,7175343,8229855,3633839,4156607,1338083,1829613,1733940,7641376,9134992,6540807,1242507,427514,7493126,3903983,6880268,2227668,2915969,569969,8507556,2593526,2112205,4127329,318328,123695,9098427,1994466,5364252,9464963,1552675,3470848,6209152,9216812,2445281,6409370,2806627,71643,569267,9063001,6029489,6793537,9218250,9543417,1036887,1160933,412852,611127,3172084,3621887,9208287,490562,8427970,5980977,8130617,7753449,7241461,205335,9242400,7308651,3910457,5254013,761425,1158719,3400495,8282171,7741045,2054739,1886956,8290333,2423078,5349365,8894587,7494190,8969497,5439897,277058,2351132,1811680,62472,126469,6196249,1709392,95207,597747,5587778,1216722,9436528,9136457,7439476,6509543,7658381,7212557,3555508,433770,7607816,6306965,9446513,2623707,5921412,9255645,2826844,164803,782635,2369309,7095630,9219078,781570,23218,398223,7069199,7567869,8135435,496308,3317494,1755570,6946711,6874313,9361854,3914210,5083114,5923761,132919,8351449,9096228,6859763,8363505,9314328,8374538,1322039,110244,8550533,2177754,8092317,7973406,7408406,9387465,5080444,6584926,6512003,8919619,1920260,6209464,6936182,7172394,6888095,9252494,3532507,8799541,643959,7950936,4154960,2282000,6270978,4605978,8811442,1580545,448414,538667,7410400,1378100,8938270,4540978,2309417,6854337,2707155,1585988,8031748,8629099,5245742,4148993,251255,158165,748198,7255725,7472224,8884023,2830417,5340608,5945946,293899,1013577,2089903,6649021,7194967,6554221,4640828,5145197,7324954,959458,7527822,2299649,2732183,4877512,4039265,3232399,3302080,8797079,8263040,8828613,1670800,8943601,420702,7361582,1548631,6311299,4152236,6403124,5968221,8792517,755255,8150273,7996547,126361,8793758,9000072,8652565,8192947,3800463,8009471,2523298,7273636,2447861,9190052,7789358,8618288,60439,186963,293164,5301257,8432271,5170984,7503089,6973332,6964351,4994228,8280279,2024407,5134993,2085965,1136913,9449022,7727471,7271270,7200245,3027245,8577314,320006,2713665,3839387,7359031,1076586,9035499,539667,760969,1127750,9439582,930602,5993790,2454455,890495,2911421,1830297,860470,9478217,179808,2315660,8841802,4210813,8645175,6059144,270834,238775,3026990,6723184,4151033,7858407,2952887,2482219,50372,3018455,7267090,3424183,7736634,8260579,6883169,8725985,380821,9238037,1365269,1039911,1514261,66967,3916475,3424969,2967764,142075,9054176,4080102,9486419,2123065,3471838,7243523,8005166,8356105,7068309,9429388,6029679,6926862,80180,9207080,8719249,7155137,7155138,5682582,1640672,5415172,1572259,8888472,7288344,8275128,527963,3903473,7929416,67349,59348,9459547,1674136,9132632,5608676,270283,3295627,5807364,6794488,2188494,7678475,7152828,7152829,508550,8943168,5444844,2434943,6941699,2917964,534306,2483194,2205660,3494539,9359298,2049987,9356118,8852647,634047,8621698,328957,870223,548825,4241758,5335190,8497480,1602248,121060,8563199,1947387,8539656,246142,7384010,962512,4867669,8748609,6555472,7407368,770606,321029,7134705,4215799,2684347,5226919,4541368,342028,8204352,1772593,1049856,8252507,4297938,2810743,1120299,702402,2138164,8620392,832324,7121358,6318287,2558983,6582937,8646217,8370441,6880396,7274448,2361236,7136711,8957743,910802,3840914,610313,2654005,9497422,9051590,8471368,7304584,7124350,8191670,5168959,8729172,6050228,5519775,7888570,4897846,2244425,1162740,114262,9421181,151537,9451139,114885,9299264,1391480,7216450,709573,9458313,7688659,7153731,7153732,6839880,7165181,787556,5967222,7543228,817348,2315459,8756359,8929560,5024581,6850132,7002285,276173,3920684,7933468,4047542,2732649,182065,540248,7670315,169972,7290339,9151480,3016130,7512536,2260652,15684,218645,3610223,224125,2485573,2054061,997785,8072851,1292357,4605854,2272784,7978783,6980549,8638601,5231956,2530105,105325,854314,7388831,8761322,7757234,852016,507332,8833536,1151837,3835451,8561564,7612432,4273477,374281,6214609,3303133,6028809,6259280,7884284,5379598,8090051,9032393,266359,6971241,7234135,9314746,3821931,24380,8108823,5143772,9261189,7620236,7918730,461867,2316107,708838,9453189,676677,3122578,8585508,9096222,4166034,5305274,7717708,2185500,2345570,272651,1267316,262256,1747620,1112994,8856196,1073145,1261811,5122072,878257,3368635,3163726,100567,2773229,6818131,176754,7735419,147200,637155,79066,2089300,9433564,8134067,9350481,9228047,7226936,1245657,2181791,4033166,8373382,2107450,7818306,3920288,7121079,7784856,6727564,2822434,3622619,4893943,8408204,1531313,149342,263366,7023520,4109813,6490619,17738,1561624,9152932,5900025,8288550,3778536,7481640,8481856,1180156,8879500,2578403,2324825,6032381,4285160,1793386,8672665,8360525,269069,3573657,178111,6064013,8171666,1669204,1519700,8357680,515585,18953,1017420,2854978,5469225,7021952,6329313,1646878,8596261,834661,8851063,122257,6493733,7429775,7910941,1087086,6984424,3075718,9290210,1010847,428555,6152308,489878,157459,7348509,2706079,6153775,8776323,9549779,5700895,4632485,555348,640416,5564232,7162700,7188160,7015609,1896817,1755159,8306974,5564139,250594,7536984,2432483,9311486,4171953,8228508,6416650,5250029,6865070,8124330,3743562,8105652,1116633,530060,9023378,1328729,9549632,6013007,9348708,67022,63535,9061804,6946611,8619830,6999210,3749751,8252460,5066155,2993846,2453924,2807947,804748,9164867,4162027,7960573,6459515,2801449,266929,1161696,4377292,3918581,7380507,6386505,7379254,171320,544779,847763,1950690,8793233,8034733,7599323,2171591,7141012,2539672,8910991,531014,4461670,7225654,7907185,6763969,315306,7377455,6999361,8355300,8664884,8592121,2819254,146366,6958692,7201041,7188474,3088588,3913244,9037836,7625852,5047603,397975,1255145,2686881,3386113,2053254,6901159,5054275,1328765,7119974,5811750,2401463,2405315,1595828,2244446,1828056,172921,2592578,98677,3717963,7275375,7991329,8265025,6960017,7955261,3653585,302627,325952,5971920,6409246,3811374,1324688,3697827,4399264,6688099,7119913,7180252,2342555,7975799,7265926,342644,6984450,885817,8170278,2442257,2194131,923078,6995469,4375423,8467747,5197070,141947,5953308,1276259,8304845,7205960,5921733,8356700,2515291,6029313,1335884,71026,8931008,94728,651906,9529549,6944485,8766497,9459503,537506,9329946,26549,5860440,6935082,59609,7748459,141826,6193936,6857101,2313755,67759,5732399,8222586,8982027,4515469,7167627,8964224,7056333,1536826,1574398,702507,8096800,5338103,5281772,8334310,9520523,2303126,8361132,8226819,933109,3808377,279039,6158626,7872009,1580521,156018,8713738,1772524,8022107,9561400,7350256,4749822,2993453,7019720,7781767,361047,7550297,5291531,1548598,9098897,2251904,2815387,2696577,7285327,6833335,8230023,7165923,28591,153352,6979019,8949679,3030239,7846938,2377538,3108271,5923002,4157700,556313,103536,7106397,8540150,241700,7495289,7856078,174341,1868375,974215,7544835,7910995,357947,1031907,5229664,185990,3587144,7193525,4128406,7848358,8061231,1195134,489585,3085513,910463,2784181,7978078,7067497,7057691,7285357,8073030,8686643,5610662,7620934,48365,2191953,7671733,349507,538811,9066373,7363545,4365274,5052652,9408690,6019353,7529437,7196557,7591597,2392241,7901563,71023,7817105,3824106,9199317,3071236,7018555,7736510,7399885,9466829,9251024,3463087,1695529,6766003,9180660,8589046,2380244,4986938,2097088,206905,9094538,5723420,8732915,4081,7409921,2769117,9226590,7087250,6382915,415648,231515,7141646,8564802,2463674,8155870,126225,6066062,692694,6430808,7749194,4354804,191283,9320263,7961246,1170091,2959868,8939428,125383,4102474,8091802,1277603,8337010,1657909,3255352,5660411,7593025,7721809,2070657,557741,1857791,7929754,8597852,1779022,4474852,7486497,7958725,7034713,8898395,5335304,2397140,8932542,9042103,1208354,2725665,8294535,6844932,4045562,3454411,9238688,995928,1909749,1914192,8285321,560525,4080627,8643051,4632131,9188715,78284,7735452,9001118,5158810,7863742,8130859,9262808,7964542,7124346,8053907,7724305,8734599,596717,9103541,753820,9268086,3987482,325936,2263157,8412366,79807,3444382,5893608,3222769,692712,22291,8259161,3001946,7694109,2005783,8341143,1860254,8730064,3386632,1817263,391045,8120806,7019607,6980064,12134,6016911,9222264,2488675,7356510,1939310,7840787,6279500,8473172,7312430,7160594,7992785,7035586,454029,2440100,6661183,3008843,7893567,7644128,8529082,105941,3444352,4390738,279483,1793575,1217694,2086617,9439795,696123,2404277,9175162,3240052,7232867,8800616,8693042,6888106,3988163,8864198,6806521,7794431,8537666,386857,7227520,335932,7154251,7154252,8240264,3878714,2265959,9458823,6968761,2402111,2534785,3753570,8930193,228026,6964170,1955199,2415782,8161836,3768804,7634583,3469459,6155365,2821702,6851274,2540698,2284349,4994753,9194315,9258762,5613668,4221538,4650722,5971650,8235332,2717009,1293182,4964259,9515921,7984553,2815741,1009941,5087437,281963,324701,8203091,506156,239037,236170,8237996,8360420,5944761,2333315,6925528,6964647,9065231,5609417,2087422,7014710,8168171,7041630,7848160,8510472,8313284,8953023,9396764,9527264,103471,2858740,6866879,2639425,5833896,267585,3625019,8953415,79322,9122662,1603427,8168982,785053,7345456,9516686,7919274,8066841,7707970,8389568,7781287,8216272,6719899,8712247,7399884,6388805,3786000,2144153,8261367,7804517,835586,764332,1268348,4886047,4316761,393309,7487726,6935338,8503624,137331,6820165,8628865,5239880,1934890,5977428,974638,4091335,74347,174983,5574179,1059879,408546,627164,8729043,8471090,8075666,441655,8626397,9519495,6321999,9512688,3604556,749089,7324745,3564021,587942,7817609,8772153,5573954,6279191,166814,6841462,4036589,5315165,7154191,7154192,108634,7329016,5047747,7541524,7066888,9327608,7336247,516719,8279680,8152977,5642,3875159,5382196,7698424,4093690,7781751,6405324,6370567,2822074,2037651,7717028,2481199,170343,443313,6113227,641853,9043885,7122855,4797472,2381129,7415868,7688677,7006677,8786557,7089051,5873046,9115530,965089,2492032,6753991,117269,3915668,1847042,3213832,1638704,4081827,248562,909589,9335109,340421,8111121,190895,3743058,1639868,7810113,9309371,7264037,679518,9123642,8682876,971542,7982627,8621858,3299794,9029026,9029027,8217126,8892871,8899655,2874935,8742370,8742322,8866783,9488978,6830350,8274871,2215425,4767124,9381511,3546814,8353303,9244367,7000740,4754223,8849162,693726,5173588,7416830,8900421,4637147,9202557,4988144,5143598,3453379,3060907,4856518,9117337,5790300,6570913,7505276,4872508,1867871,3270127,4720157,7678113,9198411,7805258,1095210,8832973,7819429,8287418,252312,2687951,3352840,7684825,191232,9424405,9278136,4456390,4771795,8793788,7033514,2342324,682722,4960407,6468479,767111,7063680,1359272,6687718,8460349,5267333,9258348,9270611,9284098,9284122,4840783,2030553,6984685,7135332,9470967,4829170,2424686,782353,2620229,8095577,7946362,8127480,8795628,135292,8885441,3087616,255390,7812730,6839997,6061562,5491833,145583,7976916,8846017,8846018,1137737,3437653,209424,5576954,1764181,2163578,3434854,1311338,2599450,5961438,133236,8278934,5555298,8054508,8351568,73771,939059,8231038,178745,4994996,6462902,142743,6259918,1240113,9119282,2984333,627624,304156,7826882,8694692,465123,2424923,4139341,4771390,8702880,1506224,1078395,6615529,67213,8508783,9332674,866704,9061468,2049504,3764961,6915176,227211,7558828,4245445,2557324,1598201,5050624,8050863,5701138,9327469,188578,7364938,9247455,213838,7530765,6569542,3082951,8754763,201412,7199385,217376,5941671,7154383,7154384,9214449,230723,1939246,5997323,8891844,8688225,9268669,8986743,4584289,2297318,2300342,9286091,1579,1231182,6913489,3259903,7861762,2322092,8878628,1779166,3274066,8286371,4056665,4946485,7992845,4298280,8348866,3385255,8409470,82501,2664825,2890391,7275901,8907216,5430964,1936472,2692251,2185746,1038141,1283612,8159632,6022095,6624274,2109802,7088629,8513235,8478632,420142,6991977,8212345,8907838,3988505,3989186,7448798,7296844,7580155,8835685,4072959,1229064,6077387,9339608,6812518,5511261,4080501,8476238,3541438,7948841,7799600,7344742,347058,6120336,7907326,7123297,865003,7976508,7837858,1290296,4071138,4274464,7495941,495825,6650899,9041472,8293783,7175038,5479794,5158046,8320725,6972449,1181779,7758057,9351209,1212566,6509,451449,220881,8826546,3926252,7337396,8632042,7261409,7048904,4941778,8859264,176511,522278,1195656,9299581,4170646,8929121,144732,7098933,6709315,163689,694974,245461,5141074,8558025,1290089,990429,1546144,9509648,5703778,5704492,4134205,9024852,5998118,4494772,5568500,9324549,8037185,8436580,4677077,8946773,438897,8481101,6895468,7778118,4252951,2219982,9038211,1889134,7758569,4292404,7529878,7773601,3058864,7443510,426741,6833552,8863082,7784592,8621450,8571645,314789,6406222,8176968,3733422,7128621,565832,8432821,5306096,1506974,8089698,8272796,958051,6879119,1965504,2176673,5175122,567482,983296,5186984,1581167,9081943,2696903,5460618,1673041,2812765,185056,185458,4802191,836237,26224,755839,964429,8699522,5587625,616680,6908386,5935305,5661029,3113245,8806341,6417910,5568686,1760980,9516042,614106,3367243,7728370,5760271,2233311,809105,1191174,7285757,8224534,2173529,2570932,3518542,4076520,2962988,8162051,5710162,1741908,8369802,491100,9008686,6339291,5238077,5187392,7080807,7117504,7880362,7804286,7966461,567236,9561803,7481059,8425575,3217612,7999858,4792954,235815,8925266,9114851,6277034,7084739,7014067,9030139,123394,7767116,8122802,1133744,9410679,9386132,459070,7568233,629821,8882675,129274,9200449,6410432,194472,74706,7301873,8025584,8412471,9220999,9130414,8250708,7855647,7872536,7906348,7976361,7690852,2141377,5989590,1042938,3084250,9120441,7423556,4698230,4562476,1269656,3021884,9322881,585948,7234064,3098686,9010631,381477,6774748,235379,6914495,9533433,9383241,8754550,3086365,6695746,2003155,8595895,7801265,265151,9526408,310795,516182,1278269,5478630,8359951,7376979,212747,8115376,7885938,1920162,6504356,2920712,7635212,6077162,8092001,5001707,5918634,255476,594185,3596288,1767979,7963868,5274389,1143869,8889164,8602620,2026106,7241259,4432024,7703664,1798426,8238446,613046,9553894,225203,8324540,9305155,7201112,5982594,8662430,7461480,9404417,1351148,4332970,9356548,1305803,6940250,3393508,1748235,1388822,2272715,2050320,5588852,6830429,4553779,286940,5499312,5499321,8044250,104843,3230911,122377,5069221,305990,6881728,6204139,7484427,290090,3664394,2126872,1087044,9348492,1649182,9299524,8599115,8754041,6029865,7019389,8767247,7372626,8754825,3929036,4992212,119463,2375009,98911,8768584,2901647,5681523,5838384,521232,3226633,6559987,2725265,7696967,8864122,1078344,6752836,8839215,2755967,2478559,7213266,7719419,7071984,2134105,3128371,7418792,1557571,2683421,3122143,2243028,6432412,4241986,503427,103685,7141203,3120736,912529,1312691,177976,312551,4474510,9048381,6950682,8500633,7773151,9429481,6198112,4560121,5164291,3607322,382926,1898686,8002956,6919172,3443338,5677605,4279789,170607,8786533,8052891,4713230,7170976,3273868,5230492,4782643,7709492,5838177,8935351,8903508,8423081,9223946,717081,9491088,101029,517245,231724,1796341,134405,7969014,1794670,7270136,4149833,2218632,9346838,1553533,8569725,7653327,9479463,7295481,3093193,460739,7134170,9168324,4240240,2585037,9431136,8147933,8678096,7200328,9444383,7594097,8649613,7604461,2709309,508799,5360591,8536390,6154798,909697,520109,7479760,8627137,2216121,6853534,7124845,6023301,2579501,7388189,3287659,8546913,9203775,5201120,6434432,3604850,7862400,4732188,8272008,7973231,2611066,8966959,174124,4729271,453939,5709781,7113422,8438632,7963132,2823148,214527,871684,2236701,7949994,1208804,6002342,1730757,2902073,390462,9523245,7073604,390643,338340,9253854,3425383,8008795,211988,4254046,4554187,1002705,317004,910658,7633310,7617676,9272261,8840621,55510,5650781,9534913,9495188,7426588,8500946,2316161,8192159,7821810,4303816,3868055,8480915,8401947,8159681,205789,791677,8030235,7629567,8395471,3286474,6279872,4881541,2279750,2859826,2172851,2861152,554675,7128265,354245,8912054,6141760,228420,8081184,7641383,8900188,7106993,7094717,1110588,9149269,2658184,2085753,8898303,124779,4255114,4255108,7729740,4160072,948836,6940478,8403153,9473653,1873981,8038587,5816391,6259418,9243351,9477751,24194,414882,911036,4029257,7890302,7890303,5697862,2643925,357536,236553,7438811,8685322,9515759,187813,111376,104567,4153454,9562452,5420809,9036851,3208096,3738327,3128938,8103412,8594088,63563,1926490,9039806,252775,894703,667494,7452750,2012788,8666680,3018974,5201584,8574523,8228655,4737201,2590223,8695328,2200686,6605951,2357183,900499,8237592,4721846,1517894,9307669,4714592,2183310,7316473,7211129,1081731,7787963,9360030,9371930,7987723,4752399,8545641,672336,7437432,9363148,8855581,187142,5163145,5722301,4576678,5567055,7734066,4181041,271643,1157439,1567048,890731,4975353,6121761,8012328,7913334,3697608,1162773,7763342,822302,3469432,6023379,4211677,22337,5674809,7328025,997687,3838190,8466207,245517,8593585,9122399,2020858,7047554,4887547,160489,8075226,2311247,3568428,8408273,6574942,88252,8029045,7649268,431421,7723600,4330105,4271275,5734391,3986627,2876411,8149111,7979960,2472443,7099890,3337042,7021833,7179897,4294173,1651597,6869122,8170083,1065285,8409675,563601,1932630,8126546,385905,7185504,6914742,9064876,2528878,7379046,5322638,7875478,9252930,6929305,681066,3850976,7317996,2255579,7540614,2938211,7628646,9275814,2938820,6231619,4415014,8424962,5317811,5296172,5805003,7904508,7904262,2150226,6401866,4003874,2636746,8738564,8890191,9468997,2229432,1992396,458884,498029,7531898,7841366,217574,9298429,1550476,8438194,3921872,9456020,4704254,4157912,8075377,11992,253040,104814,138849,3511795,9336715,1919448,3255919,23328,8456956,4472386,3063139,7914802,6323575,5023519,2341295,1050432,6917584,3386026,8958520,6882383,7492813,152234,1136808,2892386,8162512,3719556,8121765,1997472,5687323,4391077,417837,1277150,3712188,1015794,6903260,7745363,1557259,670938,93778,4194997,4048190,1384544,8316707,8289658,8694011,1546822,3476980,9152282,8765404,288782,8212894,7876082,8303372,8165389,2536234,5161333,3933449,2210085,6958212,259905,241094,399799,8406170,7279180,8499142,154217,69640,9035739,6415776,88987,4389172,7950554,7095356,9063737,7561331,1561807,5306855,4078964,1805863,9335531,4506481,109973,6380503,8130172,9370827,9003560,4054289,2546092,1264373,8507165,768065,6356579,7654549,6785359,330772,451536,34362,4713014,8614208,6340713,821384,8267456,5361426,6029393,2792845,552848,221485,328770,9295176,4084410,7013862,6338791,663699,7911920,5998178,8356699,3311128,7334658,625184,8141461,8780797,2576371,6981146,5889693,2024665,8967864,152818,4482604,6158155,7072101,6584398,621636,7858792,2392082,5336678,261661,7261276,7304882,2379170,9456174,2115091,8147238,273329,6400820,9014826,1380764,9099540,2131177,5316953,2673557,8633897,9435807,7631847,618972,4139497,6377089,61063,4250218,7120224,8876813,2870014,4575130,7148141,7148142,8608975,21089,9339399,196124,8953837,128856,9211960,8639987,6989067,3306379,7882965,7625868,3804894,7260543,7200554,9261936,7675626,2103688,252491,2146291,2485750,7332335,8335898,1334624,1163520,8796748,8601409,4102204,1149687,898858,2498653,9325973,4902136,22767,8083907,4704686,8465825,1221963,2894903,7678610,8641963,803155,68560,739279,9298369,4187692,2574547,7622035,7622034,3539221,208077,16026,7206952,1114563,8504333,178861,8804197,9509430,7039667,8554168,9307445,8220520,4170802,2149371,7682481,8602359,7105812,7867855,8697743,6904863,4019126,472926,1723290,665487,93461,8873094,9331578,8385911,6998900,5388391,8726668,8681974,7360837,4071261,5995314,4797799,5120899,5526195,118514,2864098,7179875,6993747,6192091,3591281,240074,7142772,110556,841703,4241416,7283107,7282704,8755989,8733469,2777639,7116770,2482699,7474306,54485,2749511,9374450,6039620,7079250,1216653,7530261,8380458,5746339,7215576,300968,2091523,7400189,5054551,7363009,9556033,8816789,9393461,5857227,2273378,3255970,4656785,4765954,8621353,7500881,3748809,280717,4581121,4314283,6969170,571598,1262255,1122048,7083883,7084179,1637690,798044,4320109,2872030,4505077,6425706,339028,4289439,1555471,6755878,7604420,3059833,3923879,6023015,675675,7475073,7482851,2203569,8753557,4405261,9365293,2151249,507807,185077,8743687,2010766,7399133,6722146,3390781,9218347,3838712,7187346,2044971,3114196,4332109,6546934,7220628,4600426,8550419,764650,9560061,1053621,77156,2994218,627992,6903738,8755805,9221205,8475194,2437082,1099215,6795517,2283383,8906230,2505442,5837139,8531487,5570078,8286648,7605615,1210433,156258,952711,9041870,7648783,6262416,6192355,2743957,107167,4784662,8489127,5957298,6232825,6211465,7058610,7938072,242954,7366880,24389,2713639,9434417,6911583,2215896,463179,5568407,3300013,3699024,613563,473445,5174849,4780567,50494,17748,8981847,2987135,9021711,7646855,9546465,6329317,1979064,4293966,8540782,2620133,9081386,3825497,1596608,3460975,6394501,293541,4275397,9275972,94157,230706,5627444,914572,387295,4448242,2753525,9178677,3042310,3000152,931550,6008222,8521760,5257148,9545868,3541369,7982069,1052553,7374804,6023571,7743499,6026535,63892,3867089,8737027,4515244,5960139,6974539,391291,5272988,1858121,1321334,162928,8971562,7194812,2036442,9284251,9284182,2895851,8635608,1527707,9466324,607215,8767572,6287318,8395917,3684716,3700854,7475965,7355517,1046637,1712524,8094595,1151609,7534422,171581,9107902,674028,72141,9289491,4287218,7944790,1077030,163468,9138884,7529326,2591936,9426180,7606091,3885158,8611111,8851164,9115666,2328389,425619,8933099,5052715,4826812,937654,6855210,64594,816400,8516888,1659277,7583632,8893588,4281734,4551184,7818668,5711182,4263250,6038069,6088061,2037501,506540,2718975,7998344,8054289,1744023,5824,117031,7341596,8939890,2998376,6358473,8817872,216937,7047388,3914522,324591,8025868,8902371,4287972,4163433,768712,7199874,2230995,14019,3118594,1646591,7455987,201653,4957452,8652626,1718677,686490,7538077,2005159,108243,8147249,1393025,3071635,4239052,2265590,5251169,9444289,8920336,637044,4614080,41552,8505030,3651782,7079502,1864379,7980825,8199974,8987636,2299217,163523,8990337,5329256,7389529,8100823,7260063,2765967,8370953,1951026,7365537,1860332,1042551,9442054,4203478,1913005,9168235,4488577,3752775,58848,4189924,7253855,7471961,113116,3341008,4288343,7979900,8181962,2412140,6789157,7291719,2062833,6917378,7126363,1676593,24754,7448284,7288145,9153336,261739,5395687,1076760,533849,4740285,9381550,6739960,1394774,8382270,6671332,4106779,7218463,542048,284466,740689,6882538,792523,135649,6222187,2336894,8430525,500504,9496997,8484518,1363493,8378600,7694093,424416,6947773,4189930,8789870,2128594,7777809,8077060,3862817,185016,9256115,4850524,4427851,7679483,1159975,1073538,7666148,8132211,5320142,122955,1686271,9366534,2877437,7603405,4245739,1606793,1074891,1729284,6891811,2486200,8267065,2059341,7547317,7156914,7156915,8939076,8939077,2357432,7261704,82745,2269457,1300238,9560318,348848,23782,8909974,9473921,7252275,8814843,9300169,8947393,6789019,9121552,389389,7382721,593415,8181059,1127681,6919298,2958293,2994863,1893172,1742340,1261343,4634867,5916813,8729442,3713304,8733877,7472492,4046108,7663808,7020095,7929089,7907092,8326992,9481886,8897784,4622669,8998230,7355270,7744201,3753030,7711260,8141870,6466205,7892379,7202235,5827992,24658,2925839,6991285,2614108,9359423,1859888,8263526,1978905,9223917,7154757,7154758,7071939,9459785,3723642,8490072,8972494,8434759,8585364,8952001,2319554,616289,2189022,7391830,3657590,8788894,136203,1006905,7811865,880373,7182558,5430075,9118214,9092736,2655625,9417076,4509445,6990526,3374548,386307,7677730,2709515,128219,1922726,3873461,9189014,4531756,4745634,7211373,9280550,3930353,164950,1207609,4169969,1877653,4268155,8336052,3066370,8962794,8022280,2043828,7534443,7756344,5916312,8767653,8409521,7242614,6802585,3296716,6743362,7721285,8694594,9408613,9372574,145938,7720869,2739923,9116328,7091737,5541873,1289660,1691107,7203426,6278240,797701,7000728,397737,2512933,3862205,63965,4656755,5188823,1538308,9274891,8759340,4295646,169297,9536400,7959385,4757989,7757058,1208036,9494722,1195197,139708,5108287,9107153,5934405,8183242,8536431,9293712,1868456,9067267,6913124,1136148,9237575,4713206,195348,7056131,172300,325482,396301,7709461,2635231,147303,311603,5937852,848872,8539639,377848,9217278,7935752,1724040,7774280,909088,5283083,1812793,8999298,6867116,4143709,5542674,7071921,9375190,1283648,9052537,7970120,7395426,2737855,9526053,8526737,446586,2345513,3716310,868994,7096816,8638908,672099,1085865,76808,1557424,2291129,162460,183801,6170242,4530250,7628241,8343990,7982793,4298975,2595241,9381534,4866421,5244530,600144,7412217,104682,8318336,6925634,3960389,7175336,5695243,5067076,7202506,5713477,1370624,6181108,7008625,7760581,8652120,1171285,5054461,8978899,66114,3112444,3909866,1192929,4663460,8432117,7810836,7676286,2624545,5927091,3060988,527024,1042458,100288,7102988,7081233,7651754,393562,168675,3294223,4289975,2692187,4557418,3448366,767764,6949519,88906,8078990,40709,7221248,2097340,9319403,6954752,994806,8309378,1673764,8778378,7877887,1948524,1898482,2368427,7647452,1272107,2972825,2227974,124264,12519,1273196,1269887,5996813,7832226,66391,540836,4609912,4606772,1632572,6914498,8566104,460430,7830931,6966254,78683,6829295,8607595,1854146,2607718,2594870,5487663,5915508,216300,346958,988297,9269430,2333558,9300738,581795,8552549,6809821,591153,6387877,2055096,217495,7499345,8920914,2121478,1745664,8753834,161618,279784,9557585,8861729,1094661,9089675,6336529,8248443,5301275,1921618,8744019,6972514,3746310,131063,108918,1321451,6526503,247858,4959789,262890,7028797,1832436,7196178,9044113,9047982,8411447,7434179,8212509,362887,7807618,6986385,165379,3446614,7461644,4147988,3780675,1196211,8283729,6576766,9233222,1793443,5414311,889267,7614200,9149855,9140775,2954828,8782783,21093,1661320,7165033,5548350,3877637,9181849,2285552,3459469,4747956,7147555,7147556,3037900,68914,101743,4598833,1084293,2275862,4663493,641217,9200117,2708399,2055129,2520592,7760973,4170649,1943948,709848,1130580,9298574,3416632,6204880,274827,2161029,230129,8372553,325170,9111177,5635118,2201268,3584420,3082024,1923400,7129083,4535200,9384752,8605210,8637187,7671474,9272009,8664115,1599503,7842480,1330697,1976676,5299937,3954227,5876397,8515548,9418051,7719222,471386,6987398,8517007,1854779,3531307,7141379,9349138,737294,4718531,7108959,4041914,9495055,3773355,9505702,8909397,8623161,4281648,4946506,2099665,2637667,875716,3868232,496745,7922858,8981517,6470705,10724,7982007,4821706,2807473,562937,123503,7331422,9270582,208716,58093,7911243,130925,1311839,1095948,9427524,845926,7417093,2558539,5344853,295490,8610613,648195,8004195,9556407,439641,8104047,97912,8006537,4163855,6402672,7209964,1535138,8184381,1526540,41119,8303122,2252330,4191160,142056,8654695,7997064,8108119,4059068,2598052,8613222,8613223,7845706,7845557,1300847,7918778,8385192,767095,3083761,7845277,2661059,6786127,9124531,9075516,9308075,6646774,7382846,8116625,1893436,7063869,241068,7552182,8850972,4078986,7353507,2695241,1237869,7963170,4425439,5081146,7268807,7268772,4273603,6961172,9291999,100548,7604270,268581,1893094,4011245,7503587,7545179,9335579,2599201,213481,7732908,5892837,954817,2015113,7310899,8421595,8322897,3098371,9142100,31302,5655596,168583,1931456,8355197,1258541,7116548,9295787,5179787,587888,9418730,4841317,3123277,125788,755188,6741082,9027700,9459230,5432236,4142026,157787,4157158,3385219,543170,8398698,2962022,7405584,8868179,2122072,7938386,2514211,8762839,6320109,3373660,163944,7370787,9251708,9251709,256308,8714990,7011273,2288234,9000132,7552599,7414802,27428,8295555,2001499,7411382,2192502,3964,4715075,5453397,2425784,1600043,1833999,217203,6964950,2100241,924649,7777010,227970,7596276,9428967,1675933,3291760,8119812,222007,8456230,366232,8690723,6017313,6580273,778531,5102818,4281087,7683531,8943421,4063638,5323769,6272529,9325129,3318511,3634805,816313,7559035,851512,5727365,7156392,7156393,8995581,743542,7702429,499421,1691464,261617,6483956,4555936,761212,7151145,7151146,8857151,1218135,330382,9230927,6909723,8591818,23729,160697,8746462,4059437,6901550,1086160,6827515,6878799,5820963,5507802,202958,9102013,9003776,8988092,8465657,8320436,8087267,1247262,8160215,2953100,9336420,4619663,695145,6405346,33964,253229,8897228,8760386,6913435,460586,7182881,9398618,5283260,3997544,5827302,4912657,7361065,16892,3774228,1956765,6666844,8004203,8555078,1645289,95116,106178,1136828,6843485,7206623,136973,9405023,8144791,8504160,8281707,6934109,3768861,2650603,2101228,1975974,3253846,265255,4489849,1862531,8516871,306804,13934,9386826,9422769,2880779,2441768,8615956,9137510,8910308,3241069,806188,1013517,2157852,146814,6852154,1810174,4767541,6920268,2827882,7729665,1006497,7949862,9489883,2337296,121066,6416666,133443,879832,4901728,1261097,268363,268638,7824670,6668560,804970,2469284,8118933,1880620,6913355,35519,2431814,9329613,48555,8607195,48214,9430589,5117881,200983,3356533,9143682,125023,905695,1236654,3072043,3987506,9230079,7484374,9014203,6004481,3096076,3169528,731794,7757850,348410,2685841,166680,262335,6694390,4857418,8548065,4392373,4332937,4125172,101816,1546297,5114077,5386078,677859,164285,9319385,6224965,469763,2853130,7130821,24801,5574014,5730881,6993474,7773027,232775,115191,5784486,7751645,2745867,8940398,1984986,8303354,7165036,8504100,2234874,2298866,8208954,7430238,587538,557030,5042587,2681699,7715038,835595,2078959,2861188,4659326,2687291,7864118,3436423,5154929,8579429,3278080,2707545,4011419,6883498,7421646,5731061,6532597,8742893,66177,9332150,4104658,3247210,5793465,5806794,7625777,5669574,7100243,2730249,1113102,4855198,8559444,8475656,6856850,6275891,1174077,7355660,971482,1739976,63565,2096080,721812,820291,9348031,7129976,510071,7622351,9087964,9096219,9089320,9371201,238340,2079183,8954363,2997692,248046,9510645,8058825,7737667,6921129,793069,7129519,1760830,5025271,7278694,6743227,6420880,9363829,109847,1832187,7280830,2546635,4862221,1056,8292227,4791583,4039928,8306610,2386955,8665208,9477602,2410604,1950825,141403,74832,7362825,2469920,1345988,734668,506573,8330030,8682837,671955,6473480,4463815,483653,1368797,2975960,304483,4765729,344952,2066613,1127469,8662218,7158822,7158823,2745245,7001229,329193,3320548,1275272,8779602,3292597,8442488,8428940,828310,577868,7751950,765370,7497956,1228995,7403009,7403010,8162239,8012627,3755718,7940437,3657077,6412910,8155568,4355731,7988372,9101331,3263104,7618329,6075698,1655113,7958875,8134515,2086135,890593,8653021,514568,7106075,9356195,8682299,7899263,7823077,7561673,5146982,9018015,8901003,3723540,164833,3416701,1893820,8978897,5038861,7245814,7834215,1322222,5913378,5926527,8685956,9282372,6925262,7851127,1959249,3280561,1866278,4226791,3870230,7846467,7846457,8323065,1346915,4263994,7057964,461018,400260,6030759,514409,2197635,8682090,7684194,4314514,9658,7362433,7327200,3256930,5349437,6473510,7024698,6939121,1371857,7140876,9321290,8478935,6774784,8430719,9438746,2470598,2759325,1470,3773118,7435648,9404646,4315456,4212322,2047896,1814101,8775001,238743,6832268,8038819,4099765,9029240,6950629,2007742,246655,7243080,1380668,6332477,6839199,1834140,3181333,1907092,8405753,7997052,9432673,3025277,7659160,4473790,1018113,9291862,241360,6892820,9400352,9197396,8920034,1144277,397962,8660699,8981142,8737254,5377720,7633887,3027161,2180461,6906674,7709745,230330,3520504,4178479,1383071,4289963,7918835,9174916,619550,6815909,300821,8909118,7103040,173981,1563574,8597139,2238597,1681333,4790512,9328560,141650,2846926,8405995,2594894,7978856,6897469,7669262,7573982,7204619,6755485,5673141,7733074,7281150,3499669,615642,1782796,8597976,226449,156919,844622,16983,7349500,5258675,7286209,163590,7945280,8031880,9260348,1243926,2269964,668037,7632527,244217,7671619,8143372,8119789,9042844,3795384,5469288,8187709,8514312,8099305,8942081,4157156,4153825,2233062,7316124,7727190,7090850,7226365,391113,8197520,3833162,6584662,8045625,2042658,7866922,5504220,8645315,7154425,7154426,8969593,548939,8934421,393679,456247,112813,6209353,9399687,3429379,6902905,4385725,8007224,8013087,235534,5877360,1008077,1192791,153775,1118034,269649,3177031,489107,291020,3343684,9228476,4285459,404148,9379923,9237781,9304794,1255439,6645844,7897421,1061685,1315271,9258191,8935725,156465,2335301,5908305,2072820,9508985,4723949,16267,2796700,8072805,3218050,2872607,2861521,4803586,3740205,9393057,3157027,2663072,4299654,6422246,1109817,1008797,183654,7813893,64746,8674048,1293584,6361407,2237607,1720471,4947824,313897,7873088,9472267,8526560,251647,9020641,5936895,24574,6546388,8715312,62877,8795394,6945884,6372927,9255074,1885183,2932832,6360923,8189241,9092366,5546898,8760229,8759470,2010928,22447,9302442,3590297,8813224,1000521,6842905,303927,4096477,8674757,7330818,2277995,1859384,8169967,3983690,5566533,300407,8307702,2156922,1128611,3080923,612200,8604971,7731291,4083897,8734921,7209148,7003961,7716364,8679042,391872,180911,8857657,6995818,5303459,756895,211999,309980,389944,7599874,4340113,8948017,9473721,6384955,6384219,9358447,1050192,959950,4023059,7014715,1321634,139737,2324225,9429662,3808881,595500,7283153,2774143,8501381,7983613,947576,9112092,1755222,216858,1721785,8919703,5057515,6026191,3057904,7762967,7240936,1506800,8593947,633741,2241516,6988444,2687773,9512256,7178881,7209882,2183649,3915194,2827720,7084943,3887363,4120810,2754079,7353176,6966970,1647862,8224353,1798894,5480835,6927370,8034693,642540,8376244,1563784,2571028,5976897,2598373,1062732,6953674,8271327,9404918,7232614,9026958,416565,7864430,1307876,75759,4204516,6627355,8988753,7050774,8698283,6634774,176465,7076602,9508073,226285,9463065,3819732,3741999,6924996,8589,5364231,3800193,6979714,5466768,1639208,5292149,3714210,8263029,6418412,8536678,6428046,6318835,6998920,8353791,4139668,2718361,703299,9440311,9371052,4171204,1630553,7133550,1016385,7515285,7312312,3309523,8965515,7262919,9216421,3208444,176574,6366645,2325434,7155311,7155312,4754445,4199137,5611706,2456897,6840793,6731470,2103196,6941706,6019769,1509068,1371089,4213678,645429,7562362,7600668,4293259,476132,3904625,9371644,115867,6623110,7580527,4611284,1552624,1622504,9453398,8924001,8637529,5943228,4092316,3989978,8847810,1368851,2507866,7755117,369745,3919790,2693511,2411417,4441552,995037,94397,5274380,4160053,7933488,432024,9045273,1536106,3476371,7798151,1277819,8642295,163629,4159722,2227533,108732,6951785,8422066,5633102,6376085,9247643,8498062,1911141,7603839,7632245,2345237,1049601,8853348,4844869,7557160,1066722,2869942,7120040,2670419,2072388,7089702,554984,8683756,7914098,587889,5516355,507398,1937938,106979,8088916,9287775,6947995,1563364,8988761,5576138,2045274,9247903,7753662,2241318,1842090,8217547,8222809,5084371,8740829,8270769,306176,721903,8550992,8041905,8836864,7171369,6690523,619436,7656868,2065722,8845763,1524443,4273681,6707350,1817545,2132569,9322658,345633,2044056,3660857,7594548,7801644,9382839,7080233,2961854,8898682,3024716,6715483,5753242,3752661,1357697,1125102,2742039,4283097,7696297,3604982,6478544,7240764,5700373,7720082,5439531,8160828,3840362,8346248,3910895,2401880,7716461,5928021,7830864,7340022,190964,1260341,6026893,7538766,9467419,25124,216720,65412,2141926,2231499,9378999,6088259,1173756,2794,6011630,7146865,7146866,3868574,6517643,7934722,1535830,6285683,802379,6852279,5435853,7493212,3652022,8584218,2895518,8285952,152838,6955156,8697,2461136,5699962,7402496,5991150,560660,5906010,8898993,5586374,159869,3835427,8000491,815656,7561982,4536565,2326880,3886577,7545575,8829971,9176171,6821421,1064193,4881445,316849,881308,3289963,8130660,455679,715668,9421426,815975,7914393,8735826,6644947,9046007,9365565,7605905,5458146,978964,3675554,6377323,6387771,345724,5883192,1948680,8781897,102863,8231262,9335389,319366,3728850,9096294,4712861,8035335,7359711,1273646,7675081,9400149,5976366,6847052,1827756,8281931,3416779,5129866,9423137,2709017,7620760,8437895,8218590,8922496,6982183,9333296,1007502,9162949,112688,1790263,548402,2689199,6922399,7301535,8549714,7008339,6978174,6898177,8110338,4068795,3298684,8307264,5424892,8002981,4846930,8326715,9081852,1075842,755359,2469110,9345269,5360294,4569844,7189525,9070909,344130,3571599,8072727,7958436,2377727,3293278,7920360,2416064,465171,6854599,6282663,3878432,9306920,6403218,222088,9383813,7833069,9277061,729693,2483437,2596906,3613364,6113707,251078,5737739,6678553,102044,8947879,7808816,8264452,1678261,127909,3039910,5573885,7662177,3013247,4437454,6396116,8990442,8610105,271854,5997704,609536,8122716,5269610,8858853,8788792,182875,436347,9537034,9311354,3274045,7531446,2638561,7253970,4493485,6886319,6989234,4968891,9173973,1780504,2435372,9288680,5975430,6370575,515717,9012412,8600853,1226124,6036020,6070112,123918,5794530,5807283,9259527,8618598,4466,7327532,7247692,8889748,7615545,9383420,1208837,346986,232375,7060958,3984554,6405010,8967936,9258463,5937774,822988,4715465,1217085,964555,7871681,9416066,270544,1115034,2861908,2269730,81926,131036,7636294,130336,1654543,9017287,2009296,5279693,7929113,1704409,7969701,5406871,2206392,1556956,8921715,5377924,3573033,8359321,9180498,8474300,3368326,915166,5419477,8234575,7827454,8926825,6781135,4996205,8386648,6789919,8124007,4083078,9237063,7772907,124857,9547678,7563119,2119681,6371105,361605,1692073,1831725,7417616,8950803,9452365,5032018,4957929,3724989,2992436,25957,103596,4777081,3265681,3982499,8611967,8879437,4480468,8355279,7605941,2764525,2780565,8851428,147952,6976985,5981817,3993020,187152,1765225,248959,874658,4154722,172296,6034904,9124711,8476249,8476231,6982393,7665451,205541,6894988,220167,2300243,57773,201773,9544052,5010599,3309019,1161651,6737134,697356,3490060,8157417,3665423,5295920,8901598,7177754,8148788,7324752,8698048,3657089,7956235,540344,2468957,6886051,145189,8073396,6327265,6533975,8868307,5461524,4885093,4625207,5151740,8346354,7768925,9418931,809923,1762093,2107549,2237880,6009560,2852080,7007386,2306804,2499559,2636197,118473,3588611,7298102,6954262,2781097,6024521,3489829,9341547,9433368,6971788,8134539,621032,4646711,7575445,262325,7992774,345768,4846393,64220,7678512,9420453,9448640,1997241,3894668,8315493,7132483,4793620,2729223,1537909,767371,3374821,175963,2904782,110495,7884846,890492,5648069,5800797,769522,4415908,4732172,971278,8777209,7162850,7579510,7341921,9080456,8608600,4266427,7758699,9076275,9481124,9469584,7540553,1298489,8275658,526487,620916,464675,1882258,7055932,7472716,4444195,584717,1903933,6831279,7914246,4556158,5663945,7386424,5652683,974749,6282425,5279333,2466581,4061308,4427275,8228539,6167968,4129765,7793133,8863722,126140,5548659,6146326,8585122,1102320,7037902,791161,4274317,1938728,7797491,7360312,2237352,248510,7172352,858863,168910,7479266,789083,66156,4440475,9330118,6109138,4552147,3743079,7958877,1525187,8510970,2878427,3430858,9087294,5234164,2765661,616434,5616836,113696,7477516,6790927,5615522,2683311,8669341,8675430,7720236,6426720,1319192,6304761,6074729,6010415,6084230,827629,6826072,6756106,301292,1779178,555719,9122797,8122899,2620387,6849017,511115,2453750,8641252,3038917,6296847,7215085,126215,5558250,113361,8065114,7862949,3901019,1344287,941639,9139200,2260517,7995341,7105724,8471101,539573,7281736,9318868,1160125,1005609,6410070,7750511,8820701,72139,6801133,1371428,8837072,5990607,6937776,9142422,1545322,4684082,4567093,13160,8326168,938173,9491774,4982324,7241351,9232569,2840059,9120146,8066345,8055427,723364,9514833,1153129,8760506,7980528,4267276,1130360,6391379,449688,1713304,9234558,218009,7450368,4067244,9028305,233110,9060865,6851907,6598855,8275130,7897434,7029105,3658760,6909733,7742606,5484453,1083897,8268845,9437364,2750603,2918243,1333121,357548,5411731,1650970,370645,8532132,1087608,8691165,6640087,6802744,9400341,284931,2978963,2183085,8254478,7291771,2109271,997678,4289201,3131923,9331858,2670109,9018388,7346781,1543039,7551813,2095342,2157942,8122937,66237,2169866,8603917,831280,7650262,8409673,5958390,3046210,7982194,7745474,8167063,3932210,7624972,229959,3894818,7922373,9520916,2678813,3277648,1379549,8635076,4295851,7939594,6872157,8514408,6401058,323148,1877659,1814773,7464238,8677135,4286525,6635806,3499387,7817339,9438697,8733789,8118456,424300,683472,8046797,7914355,3588869,7207629,9446453,5970873,7594476,4375675,7109165,4392196,8951013,8756069,6847497,6844069,621986,3541501,3342430,4199434,5807190,5563365,5159146,228343,7926688,7538751,1509806,3931334,3382489,776224,501029,9553361,4097236,8472762,3725826,5479017,8988738,7711272,101074,3582884,9408776,739162,7290441,8054786,175607,2947325,7830017,9103748,3163882,5758276,393258,8145621,568775,7303714,6853767,7840336,6029859,1120272,109140,717307,9424061,1254860,4995347,1934346,171501,7916308,7858538,2310965,7558,1214852,210434,7849384,9560172,101040,7629464,2198814,8745592,4596307,7916842,413644,186986,186967,143256,4823992,3121345,1243776,280851,8331761,7395280,733548,6359357,5847357,1106010,964714,3773985,9459291,1796869,1064691,66006,8588372,5987397,3545803,3217450,7724448,7692629,1871783,9280104,7096375,6151468,3627548,1897753,5522346,8647432,4649693,4634582,7628454,5828100,3521146,9441240,5991804,6092909,5456697,2174894,9525044,75803,1664467,6120654,5765802,9411083,7448791,8930222,7830079,4032419,7141178,7197575,4062276,8958036,6525581,4317577,6298521,8163304,7997510,1525133,4619303,8987896,8141141,7307315,8950336,5318285,4572310,3382018,6027267,8126513,8823547,7575096,350345,860305,1843418,8202953,6826286,5649725,4047782,6335811,7254063,6899770,6929397,3762942,7278848,4135717,7193658,9225767,1264409,8217443,8217444,7408525,5071159,3837098,8753722,1965450,7593057,3873575,9137728,7689131,870098,1303781,179047,9045718,9001547,9053583,194932,5465436,9385577,7911742,823340,7270137,3560375,6791755,114495,8046819,342508,8771101,8382243,9335482,5270660,8966409,9223567,5771595,40513,232662,611354,5648789,6998951,7346602,9187987,8568838,9394546,674673,7195851,9395774,302373,5506335,1789114,2082675,3261817,7746060,9253019,8901862,7163001,3448309,3933374,19522,5791644,2169754,4800112,3478777,7618936,2667001,1392146,5477769,7892247,3346894,7615557,7388148,5272556,740233,188569,58511,5921472,6102439,6207355,4661996,1138337,9392166,7003176,8930099,8542026,134747,9362243,7656878,6393525,580751,101923,17909,183370,1514720,71346,7179015,1506932,2274197,8974305,5361804,8448956,597563,6361945,3700488,726828,3070942,8881706,2337266,3860564,1871402,7925613,5890338,995739,8599854,2275094,5170192,7302809,329183,7256819,1512434,5735648,8976155,1698856,8189198,8229259,148007,7493292,9326990,9362652,9097194,9164570,4348738,2985773,5614571,1945439,5141875,509471,509489,268519,120138,9517051,7065412,1778914,1693426,8144993,385936,228718,233631,6486320,876241,202865,209281,2943365,8974111,7129858,1922312,199103,8406194,1972053,918803,4494202,7018503,8673652,2894717,129104,2084815,7295383,6939234,1929322,2565580,545750,1223655,4979615,6863097,63100,7804420,2989691,1930150,9471714,1201038,7293727,1964106,594071,7206554,7952584,3027551,9515981,552923,226607,7196583,2273597,337971,5800899,9008792,221182,5325833,89620,2224530,163827,933470,8120691,7556771,6967695,8642300,5119441,8182387,4100209,8130530,7489416,7041268,6367715,3848918,8201924,62456,2274041,7757648,1998163,5284460,145365,8791982,7984217,8470163,2954804,446355,9078147,7689378,2317262,3098293,1207844,1026165,8684902,616662,2108488,8976332,1835613,8992874,7426592,8440443,76878,608126,101441,9487691,6847913,7291550,51027,6019975,7267896,1164906,5697925,6169414,3329158,9288400,6490718,8722416,9401271,9401116,297167,5847282,274775,8789854,6013595,8115978,4241353,9475291,438786,3469345,516170,8467617,1768492,9039841,6777544,6388081,9239990,84821,7194065,1953711,8095452,618162,440869,8750619,6698626,6966063,709788,3197731,5336900,3065431,3353647,5431231,9378290,2500636,2028657,1660789,8875982,648756,3677084,2893475,8099654,542201,1002134,7047885,7706168,7371147,7675756,5560907,3712941,3282475,7673183,238454,3350848,8989142,9542486,8838810,7168535,477032,390286,7779129,7698022,6101194,9185413,5706466,4337923,486659,4278397,8702917,5913534,3183604,886015,5496855,6831238,7791711,4441489,507293,2594936,7871734,8872791,7849967,6712561,635190,8851939,967549,3625496,9308409,2140333,7019132,7134387,7889356,1650964,924175,608612,4089313,530633,8374450,8286592,8109696,515427,8335119,219006,5271311,693225,3079102,6313195,8304454,5330537,4673564,9560804,612257,9014305,301746,1165329,4571572,9096253,936967,7263003,139818,5229739,9222156,4823374,8390687,4122904,7716534,11996,7924405,8499407,5172775,6243112,7835305,238198,8614342,9277740,8271223,4690106,6935514,217337,2886083,2665765,550800,7974942,8533063,9301180,8541526,132646,688065,4768213,8577765,8302547,9185003,717864,8858282,4158927,6450350,7710984,863473,645285,7150483,7150484,8984515,6360443,9515941,1742463,7785159,7711085,8641363,1787206,3564771,3811734,9312798,3011,575238,7668779,7264905,8496543,7874893,8863405,9127197,7406761,6874580,9074626,402001,6499633,9088909,2189439,8235954,3674333,337363,2320208,7118932,9181528,3137851,4997444,5363421,6906203,9301222,9300455,3480928,3584354,8007522,919432,3944411,9382666,9034867,5295893,1268996,7224957,2945645,4721696,202643,6326409,9249366,3478840,3934466,760921,8019445,2010469,1310579,2004421,989865,7751463,1300172,4815673,3050671,1160047,5830518,8413141,2444747,2665755,7244913,7919113,7006869,2056302,8205168,8650685,2800150,7004233,8964981,1221180,8630913,1742193,4778689,6997239,7979158,140166,8841395,1365188,6973310,9090525,8216553,4127023,683613,6829655,7797700,115227,2517031,2612362,7036520,8519875,2852509,9554749,3584825,1168530,344639,7849549,9249371,5035543,7873801,1257779,4963758,3809562,5528430,8942641,6981976,4828030,3385156,7080502,1301048,6957786,9228608,7461533,8512082,8411489,122666,4841466,2941934,5486037,1070751,7490817,2241024,3940367,131901,6925491,6028743,4100275,3022631,1646724,1001787,277041,9457520,8231966,7943939,2368265,8529753,5964489,7344306,2706127,83702,272268,196162,7359280,7277849,9325096,8594432,1036176,8690372,7236515,5390980,7563306,25002,6757450,9380185,1330484,2307218,4164078,8062798,2437997,211885,7083560,4061676,1709131,1190091,2967620,37144,575426,7154621,7154622,6369771,8587152,445500,3565227,4821115,4508317,4508818,4598260,7721606,5214172,169220,6945411,2862289,8490045,8889648,9340969,3921083,5618027,5432056,7863692,1120194,8356349,597702,7022152,8383199,504692,3531127,185317,6063377,7957343,3001784,1148875,1273136,2400182,3133285,3664292,7631611,3747585,2766563,6955012,3451405,7890834,301633,1005459,9003482,5890062,4244368,8521887,934535,8942167,195906,5384899,3918056,7380744,6827652,736884,8135509,3532702,8893483,5372260,8029209,7097046,75381,8826640,2318195,2311835,8287097,5646506,7480448,5413297,363574,326160,8831346,8390769,5812020,4227733,7870198,1337132,9517141,7976974,9294782,38993,9381874,5529690,2738463,9524989,6925807,6111658,8476245,4166682,2920508,123772,8355923,3544663,1920818,7045238,8834697,6188584,872342,5899047,3711630,37304,8023067,8023068,7718788,6524191,2235123,5096035,5096158,7586467,7342700,8529475,7474917,2346329,2025882,6107893,4149737,2870605,5235323,1758447,8963252,7497948,1781011,3731976,1527998,3533785,8465751,7640851,4718708,640263,7024288,188233,4953840,9429584,8562271,7492163,1937852,128235,2543377,3115876,8299982,8739671,8641324,834077,3864428,5779773,554754,9341765,9395515,6469730,1016760,625523,3278344,8769577,3456325,4363021,7963171,7226348,8475205,1801987,5460372,3270,2849170,7900257,6030589,1867199,8075993,8440250,75994,7650837,59537,302799,6992636,7908891,8957143,161687,6926098,9141924,2609041,6239269,9493317,5870046,6084509,866599,1164528,9328129,9053091,3099877,1994379,5477778,1056267,2407415,1264682,216643,9550643,7400785,9467791,2182710,7650012,45599,470558,8505076,482279,6055733,3772062,8356764,8639535,9530187,8209881,1395521,8056152,194312,5194574,9075770,8130384,6480455,3338131,1070547,3272518,8192404,281170,8861403,6575461,720658,5191190,5722823,1857440,6297615,2814976,148941,8470044,2095102,5425285,256559,8728135,4638980,8414859,2750737,61703,3897713,2635357,9390954,8538411,3905561,5229718,2776347,8860378,8697568,590828,3167782,8829934,8502437,5196749,7479040,1947894,6504350,9407415,58932,1141050,8121612,5875512,7632727,428019,83389,7163184,1042539,7395716,1927358,167315,1398845,797221,8661536,503894,909257,8031492,7730554,8133008,6708775,9392187,9043694,458697,5455287,9382676,9132263,1161784,2893598,4126747,690822,686046,7344844,1775203,8718337,3622412,2689805,3679832,1796533,7015742,8377778,5566581,4133128,8169306,7246037,6778699,1001433,8668173,8564855,4698032,8808400,7107330,7788956,6465365,7852215,1213028,5835006,2861074,1141010,7127043,9532800,7830050,7573710,7745578,2058909,427998,7561583,7136487,2128102,6535927,1976775,8553248,2074242,7268199,217629,2917154,1141736,216476,6547327,6936167,311737,4799770,2387084,4374349,6487850,2787475,1835871,211828,7120094,3317833,2281757,6882817,4794379,7919273,4163518,341777,2928431,7210092,1064307,5283788,7730786,7893679,6354985,4872334,6026605,2667447,3189274,8058551,5176247,6423528,8228811,8100484,2713081,7603044,988435,1045197,8684132,6897894,6927992,1068453,4622303,121958,64788,369286,1132256,6838859,4883092,1936066,1385747,8226896,5642363,345748,4363615,4055681,8834026,8589588,8458339,3090319,1272485,6914522,9161354,2248589,245370,699306,7159441,549080,4964298,8198925,10651,9539987,9266333,3692015,7342141,1598492,174290,6012287,7203559,78280,9033538,8088226,7056948,5592938,213304,1280183,4626341,5209009,4471072,760601,792697,3040060,147258,1979898,7097616,514877,8697630,4159067,223405,7941811,415422,7582939,262626,850624,8910817,7213514,4537867,8965693,9434860,8022124,9544397,2413646,8950093,7947698,3216082,2893676,2053620,9536758,7646625,203084,7249205,732982,8956651,2239701,8034342,2095558,6177223,5911806,225901,5850699,4184149,3979493,7270480,420178,889508,5400304,9535052,490902,5642618,766892,191903,7593267,8609895,8920339,9298348,2206035,6863513,7367020,7179838,8890144,8132609,5294462,5463573,2720247,2720285,1897249,6875405,2807650,5368419,310463,2487676,5129032,5061793,576797,9040203,2297276,6344097,491192,8501219,936637,1262963,103752,3259183,997585,6294932,6169264,6433122,2707731,6287780,3825740,9384398,6021357,835106,8784373,8301470,4103344,396186,7772954,669666,687687,9422428,122077,2753591,5431903,9513260,2962091,7291846,313054,6875493,8083282,8078405,597092,1578817,224492,2202135,582467,6018885,9381677,7999126,687471,8633389,9282012,3110251,8062312,771280,1706695,8639579,731920,7112645,7915148,8743297,8971257,5175854,5260439,1043244,7466822,2078939,7918398,5895087,4749897,4868452,3081187,3590024,3695211,799051,7340210,9513600,5404453,9238470,9238471,6837987,8012388,786406,4153524,226777,7455160,2875982,8011821,2450228,9506010,8983866,5941146,7496454,8070986,4716686,6872918,5835375,1310870,8477008,7157662,7157663,1548346,2512972,4797229,8997126,1541503,8956603,7766773,71168,5928762,7208099,8371368,7179520,9455745,3295762,5682726,7311876,6084893,342052,2329742,4657403,4873846,6906117,9077147,4123969,2898638,140740,1293410,2306801,2980718,922882,8176135,7595350,6810097,7181227,4589254,2152695,2366123,8756399,675852,7002567,7208605,1161872,5774967,964618,8965623,6209833,9188702,7330529,2473229,7224664,842687,7028380,511247,9286731,1587482,627113,5960592,3672776,143584,3299362,2554255,2534392,9328077,1252790,8542214,8671921,3220792,345363,8299807,7740664,9042119,9371135,8023927,8441931,2538853,7713172,354586,1397594,69228,7997407,7769768,8918841,8801957,8654384,1165518,879922,169292,7480983,1818109,6856898,5371681,8978096,985891,934466,238424,9155775,9147166,5379094,5262269,4138882,131192,310649,9457606,5634356,7214533,9208225,4793464,240464,2141413,7232500,7231744,3219382,8110003,6839513,8324261,6632632,8524018,6364921,6848705,7398720,6729451,627891,788701,4513852,6885175,7533746,7044655,1047840,1594337,6925233,231846,6976372,5900772,8088224,3911525,7368509,5509737,9452152,6836622,126945,5615156,7761720,3546943,6547867,1119777,6883758,3669929,4278259,752989,1334417,9145339,1399367,7691759,5968320,6377681,4130119,5401102,1388204,4238638,7099558,7362917,8473898,8758135,6926718,8568336,8406011,4363198,772936,145256,6605146,9380112,1300595,161653,8850562,3070708,8851945,326856,7963742,9118245,8767476,391044,8841246,983194,3363385,102179,8431917,3439600,338963,4069908,1823871,5463237,7951499,1716796,7190816,1355420,157670,4771696,240880,1561471,2695757,6987176,3655589,6915948,7393091,3028694,1162221,596979,7494827,7049356,6648214,8878466,5149658,2181655,9237962,98290,6981209,3780804,8863124,1332407,2396711,8516487,105339,8298471,2594582,8802303,8001390,7018059,8420699,1820251,4075218,9099624,7007243,1905655,9102364,98018,8107273,9251608,9510536,8459082,8019,6540705,8971836,9434047,6057005,2968928,710391,165123,9311565,128839,1346855,106604,9326643,4169904,7849777,7700198,1542214,9076181,2664175,755320,7914937,9465905,9527680,8701718,6680302,8797867,9374283,5181482,2769163,684063,387615,2676045,833824,4618421,8729289,6086543,2743961,6003068,7534510,7483134,8989280,8474011,3418972,858023,4167336,9153463,3284035,92965,3671555,2913908,8454571,614945,4265500,6627391,9474321,8048754,7188124,8982334,7738721,7616340,8468071,994380,343535,7132365,487236,1001123,3616133,9312599,1268966,6358981,1030833,7382072,8778300,2191359,1064643,331597,7265478,1516937,2593139,292830,120118,1750398,5974773,4845883,6411274,3022547,886058,9104031,2424767,6937348,2326712,754150,7633484,6954102,9007268,1814749,9224499,168763,8995021,1123770,302661,8077049,6889191,7527431,4490974,4649993,7695014,7195146,8338966,4467484,1320527,590970,955603,3659564,730318,1004934,2632186,2945597,275169,7900290,221605,7833204,8149316,7341848,4748672,6212332,2894114,5584019,3854060,7150429,7150430,8440304,8460610,2053863,7260282,8666988,1770340,7437152,1096044,3872189,579882,3701019,9097805,255060,9232604,7484524,6966998,8005260,842686,6751555,6122578,4900327,1651882,1061784,8871757,7797967,5914356,8863840,730963,7583245,443578,7906426,8079928,9381560,385603,9435935,4155866,7815764,271275,8987448,4874080,8019059,2271389,5756233,2474285,1572349,8450186,2126215,1576000,7045141,8789904,8978786,8484095,733093,7661050,7753016,2104561,4711085,958444,1114239,8107128,7222984,1169704,9443958,9387257,2793853,1260356,2237376,4138027,119292,6156307,3837362,2390657,98664,5218219,722181,7852378,9326652,9326684,9335065,2502151,291034,8218597,6053837,7822866,1953990,5550243,5192309,6458333,9396810,8930400,9320964,1266404,2076009,9519588,9489293,6120960,2139307,8191129,8378585,4283373,1942340,6912394,7181205,1641500,1884499,8644156,2535322,7417487,6988350,304961,5523036,9054491,969436,1684387,9453948,3075454,5157869,6449924,4091041,869081,1554793,1554841,8327262,2017834,8567300,8880433,9209798,9227394,5265905,8955039,7224548,5587955,4661,6532847,7085593,9217838,4712429,7897489,7564283,146431,6687,4104646,6881080,2024350,8669274,6533843,7559915,155556,205434,1373195,5803332,469283,2513473,7718961,521211,9157972,5608691,1073685,2920973,3676292,7424472,4533235,7656726,9331876,56583,6365021,8489554,8429796,5108518,8253882,382635,2918636,3794829,2268725,2833369,635778,9377038,8353307,5135488,7250328,1852718,8576016,6608413,3745605,7460331,9342914,2513518,3042475,7378618,7940189,6824263,7339089,9411642,595943,1082880,7114778,782197,8904970,3499105,8303672,623867,143412,5619197,9219553,235686,7043304,2818285,5389081,6122322,5794677,6737311,4598893,2254031,3853127,8055862,7284006,2484871,7208583,6168652,8265315,5989383,151352,4941901,1300244,7420674,1669147,520436,8414188,4796668,4395940,8327976,9508648,5687999,1350599,6560671,6530728,6531076,1629839,2311388,8583255,7952310,7874122,7443640,700662,7621076,9378227,7981299,773465,4287618,746731,16074,2178759,8320346,6524153,6952788,3231661,8653537,3138727,5731430,17433,6515831,4798213,2153880,2973671,147036,9510337,3358777,243416,5300813,2438765,7845751,9183863,9345023,6856546,648738,616893,802339,9037978,8717878,8446846,1782070,4430446,66488,3817218,73814,3832778,2197506,4830721,1772371,8704672,8676330,8645283,345673,724026,722299,2405447,1330388,5429713,4826767,7870440,2572707,8413125,1554217,8510812,4282458,2273714,2243798,441924,1238703,2921873,4413901,4607986,923047,9297712,8801380,5262545,8752869,7012609,6842470,5447838,148027,2884949,4493644,938950,7977386,4007669,958567,8246207,6882792,5980938,6296362,8916783,4802164,5390,8728069,7085436,480026,5405086,112539,8530599,9077351,3662417,4044917,1176312,1000408,7905901,9359850,7689456,1012848,82916,399103,8881484,9462538,917884,6877555,9559973,5005163,4944718,105015,9318329,150512,4851685,7597575,625089,250395,8939379,2172254,877789,1291160,8376541,8207632,3075028,302411,8756854,9300419,573405,4751040,7034944,7921338,157821,2548057,7193893,4749482,7638597,5755675,3927365,5360108,8487727,2070636,3055078,5845617,5468565,5993325,7476941,948262,8409273,6427770,2756827,7834645,8458221,8224404,7164012,7130974,63319,7120517,6024769,2468681,5834754,2760171,738548,7643160,244544,7707912,4283030,1103109,183155,615128,6786187,4718855,6748903,2411303,2435738,128970,3408895,4738563,2242839,69827,9345790,7969265,235890,2916884,3241708,5358101,2073186,9064236,7720459,122070,2079901,4737936,2136457,1769710,1205752,7189443,60077,6192151,2492323,8859636,8416389,9001045,8689291,8539855,6184060,2212605,9048696,409587,3666965,3085999,2667045,8806154,6863129,1580155,7348182,2974238,8874205,8854554,8076332,2037438,8911133,7290247,924124,8126739,7306358,542138,5380192,7512541,5315042,9078861,8099279,9153280,4859242,431767,2668867,8752784,9101892,3559477,3874217,4412494,8887212,9067779,2833921,2766445,460033,491924,776086,867728,5112889,1105500,9412078,8400594,8055498,1081794,8692196,8775619,563093,9402779,9345876,71993,2948318,238273,103236,2792290,63739,412998,5141482,536174,348764,2657959,6772552,212079,2831470,212731,2755639,8583120,4175761,2848429,2173253,6598462,3199450,8356284,2223024,669195,915013,9462764,8250075,3858593,8045858,26381,739349,7541198,6872179,5253644,3111271,9133506,5194190,137628,9226867,6867058,9399783,1705798,3314494,1299296,4664747,3884012,1057269,9101202,764416,7456898,6468530,6482192,2245289,1161814,9448495,4960968,681369,298738,507572,4283927,8037711,8303352,2316242,6065147,4854220,6696046,7171917,7535934,5031733,8516682,7226761,8627530,6756154,8950453,694455,8112884,5953344,9404229,798884,3441712,6046172,7122636,7176597,8291662,2711113,4345336,391774,439638,8299496,7978868,6970314,6816777,5605742,294436,6581449,105569,7963447,7191092,4609994,7259196,66303,7802743,3417394,6606778,3279100,4127458,8631282,6548815,5725847,6706240,695616,8712672,6325937,2765359,7092914,4235989,5334434,2029542,1671802,2830927,3905264,858944,8008877,4394467,9552780,3891353,9483196,1073526,4164784,900707,3339898,2267717,2581511,9418098,8513076,6170356,2308553,994692,7849312,538287,1530737,770723,1953207,7746812,424691,8012920,6474521,105312,4430797,4424800,1365392,8020550,1818445,2603140,5234891,1522712,8846904,4236241,5262323,3019463,7583468,3625994,6024829,7593554,3362764,2493154,5763172,8135960,9485861,552485,6859223,6451618,8733260,1092369,9083279,1179927,8153938,9488107,8126385,3197296,4060574,2964419,4688342,215417,7259641,5779035,2394122,4870036,158415,5869941,4664912,2709609,2940887,4793818,9252672,6241894,1118601,8797371,6753478,581000,8923839,2771353,1210742,8611542,553550,5233153,7286299,9460450,3745668,7476967,7476872,1986396,7823667,1737594,639984,1527488,1776706,1991586,1900588,1314668,7382755,6809707,2611714,7568621,3612362,8379468,8213493,7263401,7095797,3397135,4832968,223535,7628678,1362914,130976,4092376,4187740,3591113,8117363,7535878,6708781,5966946,2780905,3568083,1630577,763648,4324150,3110854,5158394,2648149,8884225,2028333,6785212,8286968,7066570,1364189,277385,39229,423869,868057,1319846,5621522,120658,716743,1293278,6942442,1244850,7400480,6996687,8830734,961480,1834062,1675255,6720643,4531438,536696,8357118,4971747,789359,3051826,1293377,5203672,8149277,8157490,515225,2530279,7850853,2230242,1983168,1575646,5698270,7751849,784810,8359315,6969176,6933193,9226795,9344689,9196692,1018419,8346502,8478085,3911153,8928712,4537447,4269382,9420809,7040144,8007877,5082277,2408912,8577277,3693758,410437,7344297,6521645,5651117,7512699,8317267,6108526,2945843,9272998,6534667,8392008,3918098,8220951,4595827,8671190,80333,3905120,2903144,7082568,8189817,3530272,8046886,9392586,3661295,1127406,1354487,88989,603534,5395609,8745519,7932508,5758354,5758456,1995648,1549960,6557311,2764475,3818649,7195997,487794,7846744,6855811,686652,3387652,9302619,1963140,3861164,9558343,162257,510336,1031850,8000837,6698473,5310680,6522249,5223613,405391,1758135,4766077,6321921,3444736,9365564,815012,5696095,7436533,9133929,7685738,7529325,3555631,6365303,6838507,92717,7898979,4519207,216950,193344,9160273,8089529,2401640,7525334,1150535,1191564,7047395,8150646,6911303,1599338,7533029,4161759,804883,3268885,5126302,8633716,2988866,9241304,3622499,6596452,8319175,8834743,1059135,6608473,4273321,7684038,7684039,4796578,7250685,7214300,1937466,133529,9280365,5358659,8693463,7949656,8117859,7882474,7677761,1806853,2709901,103796,4805455,6999057,7125563,7040875,7697386,1706224,129549,7099670,8597055,8175704,2720743,7837971,6799501,6830556,7531939,7576753,8865989,47784,4680137,6385977,1528574,586328,7155664,7155665,8920686,2348963,534419,8461486,7133741,7251596,2476205,3589985,7811368,6615565,670074,7129590,7984691,1177801,4015448,8556160,9108883,2457587,852277,2142718,6621679,7309219,8365233,7816936,2316755,1748649,5862552,7578573,7047468,331683,5565774,6661045,6993777,8617912,126272,2670385,2700005,6694510,9426166,8473228,7754,2194977,8123971,32876,107668,7667490,411292,774301,9237946,7685090,5439186,8279290,238862,1829742,5563089,2002990,3020309,9055923,9055924,1544368,285276,5264066,1341323,9266088,6030929,6025403,7449127,6396696,7638344,4169868,503648,7871972,8032690,1665670,2600563,7046326,8679246,53730,5844822,5699944,6434740,6423092,114722,6664888,7731891,9499579,117133,2434103,935846,9354296,4792870,5034091,8721005,8831459,8351442,5335109,8259194,2569255,8258311,1856552,231836,1630082,7448429,7989589,7634536,4500295,251612,67361,5576897,6877803,7836031,6121413,184226,7369432,4452769,5434483,343882,1519028,518631,3418021,3915611,9160583,121608,5002763,628636,4039334,2218275,9346088,8801592,8846671,8239238,7992761,5472576,8943912,8804765,1958547,7138865,7038834,7028494,7198106,832003,3789147,101258,7639925,158351,4330423,2520964,7899951,7222508,719428,3130831,8115804,934598,3138010,226682,914161,5358440,6294839,9278592,4462444,9346048,7818866,3662747,2278496,195757,7612249,6488612,8066814,5367462,1103082,8843347,8996834,9108316,8617369,6389267,921724,8972037,6792091,56422,6995634,7710817,899959,8251817,128742,6686266,9180909,5187839,7258514,1129217,6893562,1401422,8923333,848359,6210283,716178,7717098,8418461,7352665,3573918,1401182,9424717,2073447,4129426,7195818,6876117,9045954,121885,9160959,8440071,122744,5668836,9298229,2497636,1628330,397992,9090316,5809752,7389077,4796071,756238,6362713,1292954,8542389,7900560,543047,9103497,591584,7102242,6397208,464240,7317882,9379056,1711129,1243077,5836053,8183970,7106600,8188862,141488,9070199,2056551,3091558,8049528,9002400,7321580,3348202,4484941,2478613,3804918,7340445,7330579,2518444,4201900,7288516,8790251,8993388,8717142,8555218,4060907,2201517,3113197,2709895,636864,890921,3425989,2185572,672858,603686,9043188,8176241,7166094,7166095,5997233,229327,1714336,106148,9291446,724122,8286139,7145745,7145746,6586030,8227921,8682000,7090964,4395073,8243447,7504234,6331485,8440309,9456968,2499556,2503045,2171123,2662580,9357401,2046831,7685560,3045214,8850399,1380461,1015890,7614387,7002534,9152048,223907,8208782,8208783,8051082,8898654,9423472,9541360,286418,7588227,8997559,6410146,563564,2716581,8740234,6420276,9051012,2747601,284132,7103601,5771907,8603473,6422328,88327,618573,1570612,1309418,1154393,102064,8986986,8844078,7518982,6021571,1692046,361596,7208407,8354706,2200050,420444,7809650,1797280,9185208,1850309,559757,75742,1031961,3163234,3826907,7641184,69329,8175400,97299,8072411,5185400,7168825,6376765,3551350,8370429,68945,7769014,8503863,2413727,8007286,286392,188034,8217656,8106929,218769,8196651,7085597,2369792,8028093,950638,9171822,3524869,3058903,6445932,1765411,9245247,1342382,41739,1006830,9385437,1824165,627936,7627499,5118100,5118355,6895811,9063513,8764725,8173043,1914492,2489209,2562277,1016174,4952841,75642,3612374,7444893,1242600,6930099,239262,9334077,6525733,8629628,9142659,5398153,17027,2850094,2712425,6385659,8934581,3106747,6213703,7012332,7339114,8131694,7439964,9135974,5608877,8195162,9508458,1009010,1590437,9318714,6877042,6249671,6249017,7401963,3001967,806182,736629,5385517,1159546,9505712,1664083,1152455,6912026,8965394,9131473,7025028,2009578,4268848,1538839,4722725,1917202,13673,45776,8231573,8267172,973798,6666640,6355057,1694608,2649982,6909729,6325651,7727916,9022684,4181548,7389541,2735917,63511,1094949,4403953,4342408,8590355,4947130,5442780,4478572,5775600,558140,5961441,9089065,71760,244453,287327,6968969,7537066,5686965,4154037,7117862,989778,9373590,423965,1730973,286756,2996405,5472,773285,5309480,994092,136103,7366519,8572544,8398378,3725943,9147720,323782,1831731,8693821,6911900,8147522,1197519,545564,893812,7508660,9559295,533174,4221100,6598747,2192472,8443459,2190939,7270530,8579358,439929,1217004,6882931,9550074,7485219,1199679,495689,895165,9380438,4163752,6533205,5347742,2461400,9449756,6663496,7100892,5901684,3356833,8416691,7382915,1161530,101009,578798,5825952,113792,7539317,202915,7096577,3827009,5270111,9302999,6986399,504785,8213087,7071293,1685569,8620101,4825048,722941,3887513,6386417,7904308,467333,8905449,147505,2155446,6349785,133573,7901537,7880338,8820802,6709459,1394849,7187016,8950053,219749,368095,1932646,7153547,7153548,141816,4874107,7099710,8866355,2144681,1921182,6973110,1858490,8934236,2444762,6880711,7143378,65759,987865,942997,8841070,3270688,7993614,2322443,7327575,7321399,2871187,2439062,5230444,2458517,127599,250380,886567,9245243,9052116,4867933,3913577,7845745,7894386,7175772,6026915,8851170,8873553,7336775,552419,8964027,346991,9076593,5421121,850192,3288379,89162,7494835,433396,8464596,6954680,8292421,5370664,7712648,1687882,3890054,9237345,6368739,2589926,29727,8555960,5620085,898729,6894874,7108489,8450901,2513623,6871313,8961568,7005369,2340857,243582,7182990,2085723,8578826,7818769,4397605,7066587,424633,7853046,1603556,2546851,6077498,8433788,7132655,7839235,5258369,7585576,4315555,8075153,7282780,6872285,56083,1046550,740062,1361564,2113348,3545059,4634801,5630183,1209413,3439612,196560,424718,3715851,3913445,2025104,8960079,7351879,997654,1078647,7241934,9383520,6792274,9042413,8759964,275312,2767579,4150240,8431257,521642,6470960,7669433,7458599,6515444,9244423,6023101,1326278,9453625,9011361,353564,8137829,573095,1163151,9087130,424105,5258843,3437404,3772053,7201120,8962989,9230777,6988881,9376297,2106400,8053799,7173807,9296998,1122210,9368719,7648139,7840928,919306,6809503,6019743,2905424,9321764,9474569,2115274,2052909,6938398,7111995,7232131,3092143,2369288,6366555,9041720,9154544,4588042,590517,6946295,2023600,194407,7336895,7288011,8908757,9161213,3403933,12410,254508,4811119,8130922,237388,7845679,7629283,410119,7458778,8127375,9088906,9390328,4285603,1972035,8867053,7917927,9226429,7440564,1708936,8508930,4345411,5569880,6030439,6539593,74719,1909197,8958556,225658,70798,1040793,7716691,5839749,4712972,3748839,7880002,7452927,441847,69405,9200995,872654,4310815,2835193,951892,9279354,3864116,8048090,6721078,6746491,68419,2390786,7779542,1552522,2420420,7926842,72740,248013,9022773,1902526,6938854,8985772,8357478,1759059,9373233,7909022,5908875,3882518,219864,845690,208600,4247326,143983,7955193,2345318,3631094,7923304,4283536,6014223,5812941,571181,705031,200645,981769,3227437,7537675,9236721,397713,5060962,8424751,7865483,953119,6602176,6359457,7887479,204249,8724040,8595546,4338169,8276023,8822975,8890594,8424902,286943,1881037,7747165,238620,9361266,8741068,8838955,7212650,8853914,2658596,6503101,7368759,6923708,573206,6424722,1287695,9146242,3539983,7548690,7424234,352598,4169077,6471374,1000824,7552545,7552546,4198348,2563840,6379121,1326125,9185889,59375,2050311,184192,8479569,3564969,8018256,1102923,682293,2365787,5443899,8026365,8402890,9553718,1244952,4991180,6923736,3367978,4274668,580764,6839116,8922339,1844444,978895,5684535,5426818,312249,2282543,8729538,1930236,3880505,3672590,4296488,8747450,2190915,9290682,6851937,115362,70920,9502473,569183,9427646,1896781,1878733,6301371,4491580,3214507,7594732,442375,48278,7602054,3096382,193879,344254,6584185,7555193,8217905,7969308,5714299,4275346,17045,2512558,6480020,7457036,2060934,3593015,21325,2866252,294235,2945921,49635,3016343,8033574,8213015,984094,5285318,7552568,7699943,922672,7755508,8418126,1338761,4316398,5333894,1594079,2266196,6853174,7161174,8029400,2953496,7985288,4081575,1924968,1784314,9509243,27350,1902640,8278178,8040815,5369197,160788,8411876,795142,4966179,440205,812114,144193,8272355,2099668,4152490,7457524,9236743,8665863,6942241,344580,5002832,701280,1511363,2129113,5164924,1076577,6559771,2734187,9000558,9002351,7837795,7886200,8294714,2083869,269871,3191155,8678168,5996106,415390,224810,7025721,8717295,1579630,5146604,339269,3086197,1327634,9207560,7085078,2040441,6933134,7269983,6430344,2211696,7313644,7099942,8069805,8546381,6025431,7512550,4099399,397332,7331768,7000100,3856472,8876014,2924120,7705172,8886163,2758571,2499076,751000,8777625,3208831,8007016,2091286,694287,6975056,9444251,5299040,1129193,2802520,4164383,9123722,1076685,1248612,2334410,1104063,8146275,7779481,278118,2105758,3039394,2276990,8134008,4960548,5517237,520376,7296273,190272,2170136,7676886,9155486,135739,1027611,1672021,3794100,611510,1365551,3538225,6986944,3566151,4816537,9090939,45781,7525751,3619136,2227143,4661657,4562167,4362928,722109,682344,426363,255802,545867,9488458,4728995,4864870,7482163,8488579,1988619,8306023,5911392,884618,7303218,8885275,227076,5929110,6023435,6179938,6433930,7470734,6398182,3880385,6946158,5430997,8438353,9349274,2973614,2542369,8726815,8897459,166112,8178045,718417,8825277,2575317,7676541,7994829,204688,7073606,4715954,8405229,1544554,3141619,3653525,6931333,1198419,105168,5682723,429807,823543,8217210,9337610,6965732,58837,8339010,7855962,412471,8601542,8358405,8659192,801539,7726151,6003827,8151345,5976927,7686262,6898675,7008606,3343141,182028,1790350,1862861,202967,1303253,2459579,7242316,8306039,8323350,1968135,230754,7356331,1343513,5015018,8652580,6444608,8995209,2872063,6637450,1323383,733641,3584804,6564232,8514523,5022931,7708098,2028903,4897552,286314,677220,7399645,24562,3899144,172570,7342052,7520482,8644588,4891525,1766119,6274176,8983049,7712601,8124516,5071330,8661717,3112327,9460714,9460308,8790088,583112,2250731,153128,4564054,8307598,2873693,42238,7604968,2336837,2870161,2543956,8478,5669076,6905616,3951332,3379930,472988,1161498,5802504,125076,7191870,8502732,1829412,4042184,3739743,7219955,9094247,1005221,9310864,383658,8885388,387612,3260014,3524881,8145309,205319,9423525,6943922,1174683,4322344,9307838,1606271,8315418,9511501,3477922,2054442,466458,2911499,9404150,7592703,1115871,5096416,9106013,7536391,2841247,273216,4132519,8210359,8712038,6450422,6978532,8380544,6583603,2099347,151053,945340,5925288,8920145,3785790,8883002,8856624,6308389,5874750,6544673,2290337,751099,3895910,5339501,2664565,4145914,4623302,999024,4263004,7744809,2780125,1637525,569891,2382983,7045223,9154103,9162832,7895384,313443,4170611,1300112,9038137,8141650,138373,8561361,5768526,7700980,9200979,4164161,323866,3989264,8524374,7147911,7149137,7147912,7149138,3088330,7915228,298079,330414,4468126,7845773,8031939,2735415,3997517,148839,4450234,8384176,8445192,6862735,5872797,8744841,7366912,5622362,4205875,200548,7106434,7698317,6969123,4194025,68794,5229205,1048434,2737965,8975189,966889,6847096,4770223,1129295,6021547,7223407,382998,4652291,4580401,9148829,9216228,6310261,6769738,8283871,4538032,2340155,8200630,8206791,2834500,7595882,25297,4479427,6597175,7819481,1955736,8549307,9529311,8820167,1237998,6974016,65084,1841766,2621519,7422109,4633469,182103,8789490,4052393,8781734,130473,8885906,272430,8094559,540035,9100658,6333133,4280989,3216760,146266,6026265,4729904,31909,5913906,1870022,6907680,7426580,7426581,7229086,9435153,5382037,5472840,3170185,6853123,1112499,7612956,8751591,8608070,5024872,7439050,450021,6098896,8851967,1909026,9464842,9146258,6710110,9368656,8004694,1378319,4381456,603162,5070838,233474,3307729,6019577,7019645,3486418,7575400,1768027,9475837,694914,1133067,5462493,8624381,9125503,4715621,7264459,5170780,495285,2716205,7949865,121221,1846925,201641,1116939,6990790,7368011,102055,8747277,3648848,1558621,4273042,9497156,2550517,3760317,415723,8335297,7466103,251633,685647,737503,8786542,7929746,9559277,7704708,7834823,5220139,6027839,1996641,381148,7298458,2698889,368752,989178,521982,2256671,7543283,296069,6918467,2865544,2505796,5917062,6837091,110382,8440482,2499067,2886197,6670957,1837638,123883,8499043,8964775,45653,9063691,8547543,4854109,7937308,459267,2694417,3008417,9550241,7488849,9206733,248872,6523193,6624763,541152,5516397,1277486,8868308,7633070,4162817,8900800,9423016,1769212,9310258,9310259,2716359,233977,4261954,7994083,803575,7522410,7175918,7622671,920608,3768699,122201,4079535,6933961,94982,7880294,5716238,9306063,7397050,4669643,4196641,9129032,1238664,8007734,3851114,8139322,830779,4803328,6612394,2646277,440998,8389032,7264068,8450635,4235281,9043915,3861275,183245,5942973,5658779,7768292,8118148,1829391,105444,7263046,1058772,2769461,9227826,410572,198746,7912184,906223,997582,5326745,4993964,2192547,525650,5515698,7629575,7890610,1346198,95735,6584158,8102223,8580962,6449726,7178578,4321561,5310260,4129288,8206647,7165399,145261,381540,253377,938536,4206637,5885496,7869499,1018458,6019915,7253839,2191002,4289503,8964773,8058084,7353605,7557182,6339505,1980969,5200682,7294937,9269037,7225058,7075616,8949695,9281379,8823232,5000861,6631252,6721015,9333721,6534041,8550576,8223020,7910352,9375044,7010571,145131,7349592,142447,7414094,9419965,905677,9126542,1902238,9030475,4133257,7062453,4287486,4188238,6896297,3205999,8560348,196558,5626409,8944302,6933632,3166240,7502700,1923530,5524854,6330431,2876093,7394346,935459,6926535,9046804,112090,2241321,946108,7417270,5782206,3538357,2632399,7788639,8052896,7495808,8930768,8405102,8097179,6328637,3470230,9355487,780970,2076129,8648930,8727488,7341495,8850583,9411576,59115,8820081,5247290,6959308,7532507,7519328,5788881,9286272,2782171,4579651,9176677,9387301,146580,7944497,6383597,2008531,9035160,410323,5271767,2543155,8587506,1520621,7797034,65959,5415037,2990528,3217675,7598023,4011764,2337920,137963,6924641,5154098,6913359,7892671,611202,4800799,9533293,176003,3950807,1675222,6505856,7703554,988488,2842618,174531,9160892,4190467,9231931,3772290,5509299,6429210,1717438,132609,2982320,4723751,3451531,1738593,4426993,9312866,7561590,2409233,7253884,2894210,1304384,8762408,6200929,3695379,196619,176808,3817587,2412503,7724247,4445704,14770,7341308,3803400,7262654,8741030,2378120,6626986,2300549,7834418,458680,798406,37711,8745828,7483127,2114974,9300456,2392205,574871,1014836,5496753,2312243,5784246,138648,8056995,1985940,129536,743137,5823363,7254636,9381885,143602,8884652,8839831,9128352,8308715,261585,6445654,3525244,7668685,9371387,560993,9520415,564680,8525638,1542766,8326125,5312048,4568140,5076616,2373803,473450,140821,3165073,5263568,5317745,8246581,9320299,5876496,420048,2743805,42552,9399182,9463026,7832745,21314,9065395,431454,7997664,9477243,6774490,9549083,6173416,8160567,310955,7264189,4126471,2823910,7066967,7010134,7407684,7408268,1519133,1543699,8650810,2605225,721995,8332038,1188239,752159,942566,5974953,2629369,491982,3842888,1565896,4280224,7195890,2423363,1622483,3007772,4280404,8245040,7368212,2298581,1606772,8723399,16503,7579142,3808029,6219211,4832632,97682,6747739,6864650,5457768,8527217,8454975,7419978,7444768,2820514,7009772,8800097,8746079,4295749,3904634,7455355,7437322,2862451,9444983,7826275,6411748,7236872,2988065,1567291,79119,6502531,2669589,7788482,6520783,7945771,1099377,383130,8987707,7844242,9080670,7678626,184733,69450,6971237,6753802,2871445,8887493,8648739,278607,7326749,8808415,7561063,8270719,7791808,9207678,2297540,7051936,8032553,5791221,1512119,7062398,7985030,7132275,4162039,8805732,8278979,680943,5082721,1013609,7983898,8994578,575423,9518244,128852,8791548,896530,3708765,114229,7868306,5227444,9317115,464438,6969960,499892,2858059,1628708,94939,8046864,5749864,4143310,8641321,177189,7705282,7906577,7484989,6054269,9430219,6627121,6626812,2969171,489146,2254583,8655979,6940691,8812701,100818,9204773,948892,1747344,9056765,8990532,4522198,1013238,8485840,6752497,2284733,575060,56979,2690239,5744836,1325951,7582976,133871,2465576,1069401,7298486,276465,922996,8791375,711843,606392,349340,5378767,9215425,2904032,1039134,4301896,1689061,6316283,1931776,6296745,1960599,6368861,5206942,4652717,8122226,6931713,1892581,2994182,2568046,6868718,5993730,7776655,8360719,923524,126054,1374734,8294392,2349944,854590,2207163,734697,570797,7398903,6132369,1541803,6792442,7244930,2760481,1020066,2992640,6063197,7390542,7323424,9417416,8919811,8851993,1065150,6909242,479624,7307561,4637504,111922,3838394,428601,6866062,2878097,1076295,8192,8313375,683589,71850,6830559,3878630,99014,8049174,4873264,4953777,5705827,7929127,1142793,2278952,8456218,3383770,1025607,9219988,426992,3679316,5371912,1233651,4415527,8202670,1218186,6889425,4086292,6613822,1001231,9370145,8826548,26078,1554070,2876054,1087065,9516180,7748081,4115537,504437,1376735,745873,7336201,9276055,7577517,6852745,9396208,4604368,208789,710148,3476041,7696992,234419,5571119,3558237,575786,2594630,323002,101238,2585205,7954864,6843685,62936,4161566,4257937,7427450,1980144,9270113,7745958,9119782,44892,1731618,6510122,4257376,1596290,1333745,125759,1830195,8891489,122851,6846318,7584294,8627746,2367962,43395,2098492,6050066,8483934,1017390,3195337,6859973,8489970,9168984,8343515,415485,9024047,6554962,8099122,6218164,4087834,558506,4962120,9031767,9491745,1824117,2751601,9127751,46471,1588730,4866640,851944,8817000,9138886,6882182,6670300,9413817,7882864,8040717,7519343,8718122,2420843,6520447,1345757,146346,1358429,1268174,3534925,7161014,550964,9240819,8287652,101950,8606271,8795685,6031235,7744692,8589564,9017339,462971,4658459,6619837,205995,9218490,1928394,7166558,344785,584540,6613241,972127,3357601,78098,683487,6579031,8219078,8358720,8866928,7111534,2584727,5471367,8904651,5626382,8444738,5017301,5243426,7386784,7313728,51867,8689913,8441264,3800529,3482860,224263,137264,8890782,8023810,7509762,482654,6415056,6464024,6121377,8366945,7327519,736644,9380832,6123852,8965767,7032941,4421962,8538042,8318469,269873,1323017,1092738,8599616,8540277,1201572,7229188,6399664,1521251,292742,9554900,7334528,8381341,2504137,6596824,8068941,1810987,639219,53117,8382230,8269907,9334612,6914770,6967801,7096192,7402051,7093620,3993959,2395223,8339503,8359040,4258972,776390,1379219,6272856,1693036,6706933,6728785,1804564,9341830,8744700,2774521,4799386,5821971,7482152,8508365,3924857,5967267,5914896,8662591,3110797,7709639,7722896,8803144,6599230,3438010,4209868,5067199,4155498,1552957,227351,2304839,8238786,2360312,1517006,3386074,3909098,839153,149551,9140407,1909920,1840353,5663141,4778752,5457222,9107496,8346626,1983153,9021451,4976580,8147639,7058434,3060268,1724292,8266500,8364446,160649,7041849,8751523,4242760,7998509,8638320,755944,3449476,2800384,1134203,6370451,5819298,9467367,4500127,4816951,9268080,7842584,5440746,9232338,7802056,3120451,2143997,4598944,7449609,1961211,4134058,3272458,650913,7439839,2719435,78483,2959925,2227692,1184905,2795524,2979710,8276429,8931259,7955463,305589,7076911,5240954,319910,8433007,6110611,652368,8787987,6792361,81944,7171050,794518,566363,2334233,7026934,1938382,2855959,9237134,8652416,838591,1594652,717990,8816938,1281296,2116015,1641623,7646871,740935,7860435,7034716,3721920,216434,7095193,3882137,206210,7779523,8826084,6028217,8641992,4992413,272441,490079,2327045,710700,4310296,4526899,200467,873610,9303475,686049,5954697,7209821,7523956,228312,8650145,6288074,7064795,1927964,5207818,4417126,465797,7166974,62337,8234358,1601741,7681421,8730656,167470,869977,190481,5106277,183823,1891117,8941879,8664002,8368275,4221178,8453045,7578774,9096445,7555105,3551131,799366,7346351,1827441,386619,7797577,2916704,4297070,5073898,5073856,1401173,8187098,194252,6154981,9460695,8622423,1963239,157758,1090734,8203286,2742877,5650892,7893166,6426768,7663169,6667921,8150248,8493744,467867,3579512,7019095,5304668,416668,5462562,9280248,7779665,7764594,5542746,8275908,7301316,7300142,5047639,3696765,7667426,6837555,172274,7189229,2747963,9476661,540428,9370509,3287905,9526091,1046265,3461227,5964000,8909961,1745091,2238075,7205092,4032095,2006494,7245712,7260767,7260768,7846688,6468206,1630250,2402540,8670937,1648696,9164363,6303049,7397393,8262691,1313906,7324466,6975317,7595107,7884900,8049912,2645722,6189910,2948141,247891,950524,1976484,7452913,4753287,5648651,6819903,8749394,4950783,6027905,5571110,613028,3096619,7954843,7288552,9471381,1882377,697818,953752,8428070,7806006,463328,7448642,9053204,4758460,2894993,8063473,9314461,2578659,7537347,8154682,1893433,101514,5203486,7535668,430006,4142284,4105144,2987171,7696945,3189376,8566824,1393160,4373059,6559444,8137976,2813131,1691842,972262,932603,9168800,1716013,4120750,2047077,6506060,1299734,4869895,9485971,336783,7928452,895178,32795,7728381,9437700,8631714,8306569,8494618,6863657,8657508,4141405,7274947,8904899,8765601,5992518,1378928,7407974,6340283,2193162,343553,4845178,8173160,2182548,2300006,8152042,7004505,9167194,4165759,6029329,123447,346596,7806566,3444157,7944503,7288688,7838269,8014433,8707431,21264,52970,836170,7124077,824881,6946027,7219618,1624313,3674312,1305581,4846501,490937,72034,140712,5592296,7104764,7626443,7275,551852,1850633,4667972,4603030,6336919,480875,1366352,179902,1382219,7940833,235056,7396369,948845,465116,2671831,2415611,6902549,3109333,331504,5688629,8640687,6824972,910456,322992,7924435,6193027,5408254,5315849,508871,781996,6927404,6950171,53159,7271728,58586,1062978,7016277,6849381,1343114,7166120,3456502,1889041,9485786,9010425,7731129,7152774,7152775,3390937,8301350,4776838,4582126,336941,2675575,8421473,7135231,3865538,4322065,8040471,7855676,8195302,8902486,2762321,706720,8271072,7751376,9044080,320268,8561896,2359481,4896475,9483882,7940431,8906915,7790442,1137287,6837556,9137261,7453660,1900630,9127541,2923568,9042232,1331396,247323,7226361,9257504,9355610,7261752,7213619,7579060,2302352,2689159,5333663,4564987,129531,8267404,913192,2763765,6882434,301984,8108664,8982858,6326941,148904,4568176,2785186,2188941,57988,7720677,127068,5787030,8281377,137558,9313654,9005251,8126148,7904842,8997695,340717,2979269,8067214,6900119,8211323,8530990,9190593,1776082,8132112,4755,123579,7169771,1257380,7018877,873670,3731307,4155254,8264183,6860186,7564687,1358354,5360153,1221417,957532,576114,8904071,7155886,7155887,3791736,4501288,5211646,7252666,5520108,8195008,4120051,586278,4235623,3118024,1821361,7412200,437118,7532485,2648263,8280533,1860512,63530,2178953,1902124,8855244,2730247,8984726,4552108,8226046,6965562,3772980,6280145,8693998,8582032,159225,3462628,7234225,7120038,9004034,8525832,1134458,7719658,1603310,7389287,2201106,8854752,6879508,8878012,8611467,9385673,3210025,2139358,7204917,449004,5507679,2961335,7452338,670149,769967,4313176,8366221,8076141,4157529,7177631,9281262,3059122,8688978,1833396,4191550,3377809,1599749,3110866,6881939,7188758,3259468,7837967,7800918,8261569,558809,5886750,7950637,7138344,1637945,6476306,200702,9068433,192435,8841458,2181331,3879491,626436,7604567,6873463,8622670,7277286,9157169,8113171,966376,5268692,2127838,725745,6997626,7373270,8477919,9273031,8908692,5144891,7450901,2399348,3256615,3157819,1153657,4316497,8325210,6536733,1770310,8135981,7514676,507162,7724840,64090,2187234,7953392,8535511,7292919,1163292,2361644,4138696,3797553,7647643,2735785,2694279,9468603,261621,7834744,6585826,9452020,5352614,269332,6982417,542073,2949470,6155062,5701588,4707281,3912776,519344,16745,8951268,7326572,106471,9040351,5105875,6915225,441771,7284725,1902721,1219863,132331,77984,8673949,4816558,1013838,4846246,3542191,7047749,106855,8248933,9497540,9331397,7016670,2055927,8337680,9066120,1244328,729168,236067,178395,614444,8096518,306486,2138104,7721514,3979,78063,7492830,7817624,1210952,2020099,5967504,8880610,4129108,7149433,7149434,234752,7938126,1032225,4813552,2206020,9237616,9427070,2456987,272421,3827063,4130572,4100398,4455718,5472111,3173065,434533,3247888,8334525,9453798,262442,924859,8761098,4138825,8031013,3795405,6585118,6358,2563522,2460323,4414081,2846800,7455588,5035027,7052678,8882771,1231578,7946233,3581093,581291,8188660,572834,8620680,51912,64284,5189309,8647158,2625538,433491,44329,7164445,596456,3529942,2367647,9277928,6065483,1824399,191912,7750391,7750392,8438081,8438082,8287321,7127161,2062884,1588520,6553423,198131,4464811,116794,8549305,8566676,9024236,8180616,5319596,80952,5575238,7450191,667380,3253243,1888183,7781781,5755732,254370,6385969,7736214,7884622,3886400,2222292,9012701,7693255,7247423,4193779,1923326,8719938,8251464,8914443,8919535,2586539,8481161,8267985,8951458,5662550,998901,3871619,3310861,1114704,8978607,8877715,8375204,7594531,7898784,961687,7530166,6483050,491342,9423041,2747609,7768058,9127140,8974690,8434664,8627948,9240635,702414,8302360,2460293,8962598,8316096,2494333,9128787,3413179,7276688,6985006,7706204,7656700,9422952,9208250,7446826,8842141,2576845,446766,4814041,7740135,2194176,186334,2303588,7524715,2756507,7237627,5174768,6274881,8081592,1395974,5024833,8892430,673518,1066491,5054179,323487,2391251,217827,9227072,1208075,8543712,8148097,233534,689649,8091686,927166,8087175,5322191,6380631,7954742,8773129,9187312,161130,4725866,7915088,242466,7829538,8158741,6802231,2947880,5674779,8564697,9535092,8036102,2636329,7550797,8748591,9204081,5547849,2937311,7592055,529616,8408030,7833006,6934700,4657649,9519187,8944465,8671139,7235873,7237277,617736,9248341,9300242,158940,807929,9482323,198691,4549803,6103810,3505636,3280648,3573036,831469,9275856,6854895,6946961,85370,8586298,7445436,7520512,3107803,1531475,8751736,7191842,7096099,4332550,7250626,5671248,780799,3936248,2523856,4084605,3685744,8718573,1274780,7213481,444558,9360060,7134976,7135074,7135007,3924386,1692598,5979729,990192,95288,7788910,4743288,7077360,9321016,6465329,4160936,6220642,2540377,114476,8217011,7906782,7334041,7334042,6874979,4272694,8931943,6619119,7449175,5640422,7172736,9123599,5515500,1828119,319160,7848920,6247328,9560442,9021942,709074,114424,2079233,7807880,7905759,929105,5783679,3457063,8310189,692187,160095,8341798,165614,795094,7669310,9407336,6020063,1337912,76589,3767967,6625645,6799252,7241597,7295089,35104,4625294,8364790,2211120,160491,7042283,6750061,8430771,8992269,9073336,451512,1318388,8681074,9273517,9493644,308128,4687565,1524524,8639728,7671325,684435,5442993,1554046,6493058,9301786,7768977,6879009,9255525,8132120,2069199,95463,8257861,6050846,1305080,1165341,253698,219885,2469794,9006600,1004880,8025971,996036,6985801,1748847,9227284,8052288,8409958,674163,132541,1784848,4833673,8608922,6931132,8440334,976021,9193867,1585625,5565042,2384234,7214661,8796052,2242002,3770307,3446878,6461195,2693213,1904791,1983282,8003335,4127377,1991445,9341697,1783918,4421908,61685,6786925,7169488,8631568,7497040,2217270,5180768,8070081,482477,1641239,3250198,3462454,100447,6906848,5779893,8284586,1009169,36856,7526647,2364011,1813651,8305662,9147120,7877731,3329860,33166,8915025,615942,7233613,8151073,8848667,579983,8505073,234909,6826239,900589,8297653,8974604,8018729,9418809,1165509,7056164,3728811,2505145,8158253,4844995,8098585,164501,9108034,9108012,1831122,2175401,7546498,1991400,5510085,7600953,1786633,7507313,6981592,923485,2733293,2733235,211704,8755568,9272696,8321917,8954812,4136848,8057319,8240852,6270117,5301896,4536727,2552728,139324,9003978,7236576,1108143,1605686,7234877,136423,9437126,147728,714090,896531,3926459,8009927,8961222,3096250,8290664,8956941,9558467,2079071,3200572,8970843,940540,5364759,8355630,8299998,1278761,3274495,5072248,8100822,5128459,8541757,59621,273615,4945528,8397088,8150103,4083498,8869932,2600098,8074337,678258,2919122,8887732,5947017,9516812,585150,5703775,6885611,2317802,8332273,9163417,8439982,9243432,2821201,1718872,9144848,919795,6379067,2824264,179010,178747,7839939,1362191,7724072,6790648,8048136,9212502,9445549,758059,9159456,99860,4153145,4528420,4378447,624107,6310953,6739921,648579,8925961,9490850,9060870,3269473,1030494,3870740,136424,9529560,7408468,7917705,2106196,739303,7280441,8961334,7295986,195088,1073949,1086143,2331470,8909878,6860515,4723313,9528174,322718,6717211,4073298,9153623,56782,5744713,124985,4004669,2186358,2399690,3064537,7566224,7566225,4353286,8783415,6792883,328802,9468315,2194248,2553997,4850767,8210501,3069442,1683073,1768213,1878988,5957370,226754,8594883,8150124,7447140,9444432,347916,224053,282080,6880010,7112464,2834020,5924055,7212764,3045901,8962124,509600,7622754,65128,1338953,8880214,1507142,8625373,388509,2705567,6901526,4260409,6220765,4640627,161623,5995023,7517961,238472,7067015,1335530,4891603,7521197,5866383,5939523,2477167,498281,7633612,5269262,6779194,175490,1751673,7253059,326755,8410682,5364543,6836699,9210641,4277686,8975896,134330,6946808,2657497,79385,8690108,7565,9037697,68333,9081553,2156643,1584563,3953633,5094706,252051,64717,7608604,111274,6847958,3559605,7339770,522089,6940176,9224936,6445388,8038795,9228078,5643071,9032931,889744,8844023,3930923,2238675,5623520,1722940,73912,8190571,4726571,7124464,9307322,3448825,1527050,7148973,7148974,9037319,1104525,2784415,9141203,7004794,71074,7544203,7275881,76967,956218,1546264,8448853,865528,2140567,8211069,7784465,7504165,1509959,3405589,6392949,8826718,7194947,3284812,240265,5353895,183533,2263619,7869863,49814,9199489,9042349,841802,8957329,2532436,1262159,2349734,5636543,5904342,1161105,1291238,2508847,8664414,8208721,7893825,8138324,7008739,995683,4732764,7897427,6739888,4564696,3385498,5250788,8502199,8222588,6171577,604350,4597984,88370,8192626,1941086,7529689,175959,4439716,4820917,8259283,3154450,1618211,4013255,1768522,6961401,708627,9425038,2195052,6102682,2088169,8525727,2506927,818647,8370333,383919,8776847,7703139,248553,2054418,561692,2107198,7232896,9403809,8637152,3114130,2758319,4281545,1903999,312918,2360465,6633856,1204970,644142,2058867,7197065,6453030,9222397,8731754,5869158,7723384,7998130,64710,8649721,1167564,7351511,2499835,798532,8153476,8653068,18757,1064139,3036152,1049814,5271245,8275673,1761976,1210058,9284264,7159072,7159073,2723185,7843686,6863364,2267360,557255,3888701,6604594,869410,9096625,839603,2413205,1686658,4088746,3279085,5716976,106462,9004711,8511474,6863200,7442870,8170839,7173101,1128906,3396118,3992642,7728979,7964424,1555789,7126631,8601059,6991224,490380,490415,498794,7848727,8795890,9466486,877957,6648415,169397,7010297,9412053,6309451,6121425,8331833,6087596,6967946,9146140,4548979,4653545,4191172,7478286,7481420,249968,6127497,7828517,8190683,8096644,153012,9350868,4042616,8382360,7515133,3934067,4538038,516746,5059933,2811121,5295527,3284665,449190,7784378,475781,8799430,7578664,2863834,22524,1386602,7394097,7110426,1874215,4981802,3911162,698382,6961997,2926637,5460624,5337560,5460609,142638,8074556,3985517,1001922,2946146,9457985,994908,457689,2006563,8179644,5259530,2116951,8575296,5328893,240091,4484518,8142914,4974768,5435335,1065129,8939674,1103355,7492310,7754496,4214308,5993463,1092201,7095819,1171039,7755459,8201482,3803025,7676443,9163482,651867,6311527,6874601,302742,9463949,7517967,2049984,2367197,7955051,5772111,7791918,8539255,2224131,6202225,5243468,3860708,58432,3979808,10152,6431674,361536,1823775,6479141,7723403,4877866,4728578,7389644,271129,504830,8693492,9442175,9115632,8130218,6982140,7615479,295137,1120512,7175854,7896831,8668404,431044,3855230,5652554,1107660,6266541,9116127,67060,4226038,1034643,1552468,6053468,237096,2019685,6387879,2120566,7538217,50971,8459837,8323703,7943864,741185,8262346,5208448,8622137,7076743,318419,9104091,9464696,875942,6389801,968290,2408888,282777,8239481,915454,7117489,6812653,5529567,3417274,2198799,7654879,2461913,157766,248825,788971,6274416,8980719,7744082,8152014,2387207,7256777,4523107,3215731,5653004,9306465,6803233,9197614,6169708,1909299,8033528,1533689,7764719,73398,7661664,3004934,873538,6166126,2803294,7895670,4709,2760101,9162179,2339297,5476530,2023630,3750558,3058177,8582120,9083033,3716739,7039119,3001994,1365425,2444138,4856794,7220649,4293486,5236070,5235995,5235968,828466,136159,7185753,8029734,227391,8145549,8731833,2916281,215471,7686866,146898,8126822,1361387,60789,494324,9512630,6144313,4334143,9193862,870799,5684763,8003951,6282519,8371415,2079371,724761,905203,7319173,968,1977981,784333,1140408,190351,4768927,394969,104398,6047033,6161191,3584870,2973770,234204,5938326,6938531,3409696,9265827,7836069,9551917,313598,2216025,3910736,632712,6798421,18660,8729667,3713757,567485,8683370,735076,7248690,2796979,5145020,9109792,8911870,281745,56391,1034394,9420531,8016598,4426894,8651221,7873539,331392,2390519,202908,5798766,7475677,8536508,3255916,9360630,7535864,13101,8098186,1588388,4874305,2773139,9201234,1115082,3537469,2894099,4826890,7814548,9254906,1164453,9456510,2002489,7960637,3617417,9216276,7897929,8895591,6099688,3751299,105215,1094673,4156306,9449676,4305481,4812451,6553921,387691,4236741,7402166,7351598,7001793,5880537,2282924,4233073,9382419,4472434,9059202,376750,7884502,5061769,2795170,7316327,964345,2881235,5955435,2624309,7900162,499647,8164124,1679653,9208375,632109,2074779,8164237,9043128,6521099,3444676,7840493,9228840,8804678,390589,7011475,830086,5024584,5185292,7141524,6758845,828413,13494,8296804,8411922,3222106,8720815,869839,2191179,67385,5120635,8631565,7220569,5174837,853960,8841924,7828678,3825149,449647,4136227,188428,6704707,6943244,5477982,9092605,1756413,1650178,8785902,7774055,7710553,8981113,3512284,324674,4384723,6383653,4039745,80585,9547383,7729333,1853762,2051742,3118255,8362351,3404605,1695007,1925274,867470,5276963,7800429,7391409,9537135,2224809,1394699,707269,7962337,735382,7210697,7211033,2576337,7635705,9252454,3115168,9442082,8100983,1220310,334562,1919300,935969,110068,7749516,3088720,7887911,8653862,3544819,8654345,3164128,1772305,6949638,5081062,6672319,2201232,7795303,7233606,276940,2064747,2246057,7935083,7201656,4833730,7896837,8490250,483764,945928,7253305,4395385,8734515,632832,3355735,2711123,4504393,7175129,350198,612203,8701380,4821229,6477107,2794840,758311,7352812,8891696,6030469,1339115,2695329,7334052,740662,2440997,1022856,8187145,8749143,1689871,229267,2737343,8543730,273760,2745827,8798471,9078771,9278514,8120161,3757152,449331,1656556,235374,7737629,8052225,5270504,6607331,2769825,7724819,1991499,8711006,158395,7216688,5505909,448896,9145850,8694356,2085227,2063157,8546925,8960937,169596,7739825,487697,6624577,1139747,2608390,5420947,65872,6885608,7061901,7386447,7714153,2639344,6854996,4003646,7046073,2287013,2369303,9118084,825067,3340456,9183016,7982844,324512,75842,5649416,6786205,8998544,7154609,7154610,8672384,1232526,8416287,8621404,2363582,5960256,4361419,2323895,894475,3862058,60813,104314,703182,6990390,6205021,2877275,8978076,470627,650931,7338615,7096318,5772381,2348648,7182445,7857139,2198562,8455632,2558059,7466127,8345546,9157647,2326937,6982217,9366970,7573440,965095,3776883,7652053,9000954,9077224,6977189,8529300,64747,329528,8906559,571257,9125517,6428146,2127334,1218621,6903415,597533,4849060,3533953,8007798,8954902,8352709,8879956,1924656,9347955,9347956,1627742,7164366,2844562,7728198,5653721,7979267,8844031,1219383,1086238,7894591,8388448,137505,9350960,9477053,3063832,347262,700422,2032728,7849120,2144533,905566,6327021,428486,1105593,4956546,9318886,172818,847184,8017183,409615,1935180,7732337,7845163,1107363,5909466,959887,8178435,6866393,978265,4582963,447579,229229,1379594,9491441,6444028,8882108,1059933,2615641,53680,1664416,854362,3731280,6504923,7482372,1744026,6305291,7767461,2230746,1578229,2873672,7411022,577820,4108673,6423290,1827342,9147422,2425820,1185397,8990394,9004875,8772360,1004250,9047994,434943,231260,570500,457095,5385511,667452,1297031,3902903,17927,4167199,3292933,4120105,5974569,7218404,7710851,5509209,4475584,4501225,3793371,5310785,970162,2289155,2928845,6161605,7846864,3381385,6692098,9033692,9033657,32568,8391819,6113716,8743485,783553,2523082,7450980,7249952,8715062,8652628,6170050,8570069,6835501,8311950,7346603,8622010,7544344,603239,7805397,5857755,4728938,2435099,3916115,4228924,3317521,7970424,1900105,551945,776206,136021,4058801,287142,8433421,58954,5564055,2112532,6255062,6445010,8084417,5922651,7601815,1300781,5258582,9027971,2788288,567288,9285658,8312386,6060236,7249526,9225950,8773820,8084011,9087135,2310,3765918,8188686,628433,1197768,1920870,1528082,209320,7255616,2184252,465885,3051031,8331878,1274774,7246290,342599,2939627,1537108,8039323,4535365,7342125,7279916,5751403,1104432,7045917,138557,7998649,1999642,3921467,9033412,2687607,2671723,6894606,1509674,925810,7498809,8545324,2640736,7133096,9234837,4252984,1785874,3209956,7850297,6613531,5261468,8653938,5511351,9440075,7135496,7186447,586955,218247,5860269,1389413,5668107,7243808,5446494,1126167,7673910,4976046,5815008,9250492,5263199,3210238,7499114,6790321,4316803,1282112,7364543,8337749,7466829,8053202,7730247,3235720,9208242,7537702,8972114,739336,7491367,1102719,8790402,5060317,8663677,134410,2217315,1774726,8617502,139332,1161758,650667,810641,8585514,6918099,8486682,857282,3859376,1270088,3873965,4966566,3560986,870784,226513,2770441,1250244,4064529,8931252,2282927,118690,8386933,8466764,710995,8535967,5944911,7464191,8660096,7796421,7874498,7052239,4882954,8111343,8811609,5546283,6403764,803620,1681063,4127512,4286543,8299111,7025609,2431070,684285,5500440,8045317,3429307,1054419,9058040,2345702,8279031,5036803,5199614,247581,2118115,6418932,7952305,318571,9314653,6518648,6861206,4145100,559706,2760187,4055921,7744603,2106514,9017173,4965750,8247788,9132584,5174431,4299913,7463731,1006476,7248969,7284629,4169286,4200247,944248,4711670,6849619,1355276,8592354,6972871,6175201,4604946,7845414,7950117,7084565,7130893,6107746,1359122,5123179,118805,1282076,4717142,8623319,8657200,8508969,9282546,4252726,2679167,1376993,8497895,9245532,122648,21378,8700664,9083073,9378727,9354089,9353110,8853753,5093605,8505818,157112,536747,5831778,259278,2925158,6452868,1873690,17740,2581113,2069397,8839332,7375930,8355665,6934425,2718209,8694710,4102666,589397,2243727,5107252,5687043,7205055,4823605,9518692,3224020,1081194,8739245,2096311,3810819,2883815,8982591,8129168,749749,492672,4734786,7390281,9293170,3627509,4984625,3689438,2188224,8861876,7084296,94394,6833204,7607025,8901266,4754956,8497206,9511608,5401957,7856666,8281086,3777660,7677285,7132052,2874140,4170630,6548311,4420411,3111379,9541923,9373956,7916062,4587496,62657,2054163,7793848,7750571,2889713,6588541,7966217,3684408,435460,7141903,6529493,4273729,1082559,7872101,2686749,123533,187357,4068354,8156838,490286,4956177,6625939,8541571,1214615,4196362,177008,252114,3363295,8748755,7976174,1164969,1161603,7070711,7201645,8715931,7821659,9500452,293756,1854113,3715182,8088423,1845710,9175334,9494840,2393330,7314210,7288610,3881045,8627488,7981963,4145130,2014189,9249904,4273354,3880274,2317601,9222538,496056,2310857,7454253,181401,9096673,7287793,4750050,590162,7392356,3528730,1906510,9180809,6797587,8452325,3295306,8288516,7587967,357854,9086930,8994219,109676,142297,218406,7740514,4530730,169010,8663878,6289661,6503387,6503276,9029875,8513756,5513430,4701371,9375825,859159,723208,8056311,7400634,9547941,5418646,1280531,8026658,896468,4672136,4580464,8198538,934772,1849409,5937522,4137676,7368477,8917984,7074404,8483748,7102748,7251229,7107350,6052385,207642,1130855,6074594,72021,1517543,7233322,2386508,108406,6895751,8850858,5799150,9491186,290299,2829523,6903078,6030483,6496391,348600,6960396,1340621,6977665,7791286,4642199,6886616,7652119,8733680,1129070,972310,634092,5343806,7354579,7585083,7584113,7161554,5759038,202673,8878595,3601184,9185307,2684923,3440896,7643660,6418348,136620,126449,5789583,7823109,7004833,8088061,8274782,6298591,6445326,6986719,8015801,8566130,9348842,8542600,7877079,4055819,2581081,1205399,4839340,5059591,9215868,6044432,834347,3842282,5317118,3005567,4075002,8785767,9532635,9557406,68970,5698192,9239452,7917107,8710003,7313956,7801833,7629670,9176526,8545895,71829,5511921,7139590,9549149,564311,564416,7046658,94136,2462000,7981612,4268011,8019357,1510475,8382792,6172354,7109205,7648094,210919,5401984,991065,1925102,3524797,5163733,7566594,7726019,5657687,2771099,668313,3293344,3846065,3846284,9398549,4606332,4874713,1598360,7090558,7940433,7393073,9405394,8955037,331268,44045,1199505,1213670,9037624,2446376,7916779,8935889,1752840,7104050,6811063,8497194,7112240,6341733,30440,671730,4283989,1082808,424645,3924221,9141355,8637272,8029063,4808620,4557664,1824855,1569127,2104810,687387,8560731,7635902,6810544,7129336,969331,7047142,1238382,789692,6361127,6192079,1141409,7185066,4258978,4180303,125109,2712367,1787452,4241371,8428044,3666443,9286080,9371645,4863349,204060,6200500,160552,98537,2641663,6914643,225909,3496333,320599,3366379,2217084,6866394,3574479,8430691,9380474,3582113,247304,4891192,2866714,9181415,3141970,2235840,1694593,947692,1133070,702495,7025883,6936390,6983302,8651144,1676077,4621862,494000,9483373,7096108,9705,1709194,6865966,1052238,718917,1505255,1844132,5983095,4550779,8139860,186126,8716859,6836002,595001,5459637,2828983,8859596,597905,8985938,9369249,6678322,674025,7264821,135557,1382228,758236,9446068,6364569,169850,4872202,1152275,2769701,6091391,2861917,1042035,2085027,8026254,228129,8824129,698016,4272943,2385962,9075817,695751,9502335,1934110,729133,3755106,7874253,137987,693246,1070397,6187000,7223261,7667196,4529797,341338,894601,5837835,6339755,8841321,3271750,1343912,7786123,1069443,2181223,7873211,5831343,4960884,8376806,6708523,9160847,722016,3111814,2982227,5637413,7712324,7207647,9228076,7066889,9104867,7831503,9058061,7652827,2417771,6380773,5453679,1053681,5042557,7517078,4495243,142371,5724899,8700026,3232000,4044953,9550502,1334975,9260842,8770076,7885842,227055,7739867,550923,3659531,7615681,6972419,4361992,230099,89299,1030863,6705232,6782596,8513143,1195848,7723906,3809526,5215105,4148171,2760139,8433171,7819428,3920690,4798699,7615579,4755562,3225295,3814833,8822204,1379009,4007558,775847,75235,6368983,6365485,1182463,418549,2880425,7799369,4868122,912166,3758712,604050,1101198,142148,2672133,8454996,2509324,2858452,458256,253179,2121988,270895,1277447,9287946,9286517,4540078,6421998,7400921,373861,6877416,284759,8080519,6465254,3415822,7487163,7416105,429919,187662,2040660,5811846,8571865,11786,2126482,2707435,7819641,2369795,4790563,215632,4639199,5163238,7678776,5441658,5174744,7334011,9533251,9398412,4745403,1172871,2411861,7624544,2784109,4146426,6191116,9006366,718521,8450656,410773,6910844,606044,119691,5047204,4064181,3344842,7941522,4063941,7986709,2283311,3058225,4422079,4416925,7253854,2906507,4152196,5618099,8357998,8228985,3598409,6941893,452101,1645187,6954254,1003130,237895,3715503,5613809,123188,539382,606173,1516166,8779143,3362890,9120379,212309,7299509,8042140,813118,8539449,8366666,5580848,7029355,79780,703236,959575,56690,6396058,7093826,2261723,7361419,905665,8355883,4793530,1163919,1798099,1927758,9229071,124384,8466552,3679598,4150751,7965252,6795700,1191915,9034204,8351615,7317361,9355219,3484075,2481280,6935763,8401675,4384402,6883894,8847766,6839676,7144304,7144305,8920962,8920951,9506541,1798819,6909302,7164517,4141912,8150797,7845981,1268885,7239667,8155173,4517602,7339859,8369324,804916,4077339,3266242,115557,8298005,8097381,8408531,9286090,9467977,5550669,7453724,9542430,487856,437967,7217907,6047492,259379,139881,2820046,1966773,93477,9230456,8436214,3016856,8541944,7309329,8516542,5884386,7873127,5184461,9024756,6384949,189046,9337584,3089119,8682582,1745772,5389594,2108971,1043406,873076,2549548,856945,225639,4580026,1093824,2160726,914194,6177607,5976765,4526872,909893,6010718,7426460,8571560,1171345,8676357,5058220,1033659,6954544,2295470,2179039,4531624,8613196,9276531,216957,6186979,1267685,9186953,3248005,4060748,92229,1808263,2284646,7818098,7390,7544729,1379615,2924150,2334707,337470,7330981,6940436,158874,50553,9224016,4172419,4332577,7855691,3497311,7699100,7046264,1703593,8021256,1214645,9108952,5598338,3887426,1777267,1279394,9331738,4576750,874127,3341176,72300,2721543,4290827,1784761,6993354,441409,7495517,9064093,9496954,1715749,1761250,8590493,9094485,147261,7821175,58933,233445,6634252,4961271,3671834,329042,7909274,3112249,7060309,4621001,8410652,5662211,9438594,700557,163384,141213,2888522,3016487,7929705,4230250,1001865,6336523,7977628,6744808,8404487,8800794,6738655,6730804,8416229,1178410,673602,2692025,2574659,3421177,1660528,7619044,7207860,13304,248476,1936362,748630,7763746,148751,356321,521039,6611920,8003921,1296683,2002117,7732748,6084314,2385848,7004266,4949517,7761619,7793969,4062465,8195702,7594268,6717328,7727946,1719091,9014396,1232106,5658287,8414814,71463,3736683,1923508,8285727,7709197,7160819,7055698,4736316,171142,993072,2609878,7988967,506471,709852,3642752,8261138,2723711,712656,7346802,7209025,7207707,4100929,7182455,227371,7697540,7044841,7810576,798418,3138433,2199543,7150471,7150472,7150473,7150474,335839,8442399,9322064,7767761,9333842,5113150,8874001,5999024,1108203,4276981,9204966,2559442,7065174,6550549,2312120,45590,7320543,6468812,6275318,5458536,1032096,8524431,6956467,2035386,7038884,7829164,1079337,4139137,6183133,377422,377458,6937338,8614284,612329,7178995,3671141,9081275,98559,8197368,8534583,188429,4257970,128465,5229310,4767712,8486467,1555951,8382814,4400308,7756076,6921440,8822862,2611393,2211948,6841235,9492315,3524113,1873231,6326731,6111607,9550168,2838034,5176553,9426177,6976435,735325,9125497,3262093,3901319,6416464,7322075,119686,5434144,8813968,1092741,873694,9342941,3104074,3855770,7863081,5661680,2402495,7574516,283058,4102687,9239343,6225916,6028883,8185739,9332198,3293176,6458948,8506288,4992263,8447790,8757525,4285625,3078781,4375966,8386958,111214,273370,7909301,7388751,7310402,3055108,214370,8890053,1237950,59409,8457522,6684592,7760841,7546582,2194404,9361436,967069,59646,1031019,5288279,9172555,227169,2392454,7393802,3130327,8392980,1534379,9115424,9393457,8038607,6067241,6997232,1841118,3008222,7675002,2275637,7117565,7120970,7376528,180106,206027,206086,9405719,5991876,72653,7222155,6029883,1590548,600674,999879,6986340,5319845,299130,3232909,9269124,9249663,7925868,8141370,7014499,9470362,212410,3536005,9417476,8148034,169294,1324832,635700,1034589,4281603,4118725,6969070,7114583,6760555,7157856,7157857,1210709,1072623,7781615,4532635,7722656,7722810,7729014,7729589,7722541,7722927,7723073,7729465,7734084,7729485,7729024,7722942,7723004,7722625,7722867,7729521,7729522,7722721,7722956,7729530,7729529,7729476,7729039,6901094,2782763,8251686,9458668,9128668,9128669,5977125,5986962,929647,2962766,8607126,1152605,8672364,168177,1770367,430761,1134558,347536,4246087,7513758,2185389,6961424,1698763,4333567,6258734,3427627,7381670,9488009,6890797,9139141,7666326,9290947,1122663,5674395,2116159,6926773,7820603,9462077,2422115,976615,7920947,8604475,7903200,2431658,3724434,8902589,8563218,8767278,260454,4211518,8181201,756758,20565,3992603,8025673,249721,9312735,8862289,532463,415386,8360964,2797048,810148,7352664,8982173,1332767,1266209,6094853,6864039,2618248,8102777,6173896,1789465,7842658,5855808,3877490,671505,7987188,7100842,5347322,4010747,3905648,5459973,2713147,9026004,8703515,1113687,262584,9250377,7165328,137333,6977455,5579822,6109006,495878,8409887,58788,8813105,6066533,40274,8034641,7894649,1803757,8676305,2978372,9078633,2252171,405009,296992,4465207,6462266,5180715,828967,7076651,7987091,7690347,1732461,1103604,7049278,7957437,8163728,2594951,5077894,8112223,3058759,1142126,2190909,8473217,8473300,7166351,2332532,7751708,7250078,8585665,7166383,1050921,3936896,7331100,915436,7940807,7733786,7983895,8147377,8649185,8774823,162645,9394146,842714,9336508,2237,8068559,172710,3999431,6195334,7499220,1856585,4372894,111600,8804855,120731,7954029,8997752,9018186,3337084,7352962,9401698,1990179,8190355,8411797,4965123,7197578,9160970,6323729,8185635,9115889,2880752,6875467,6715594,8336640,2178973,2214696,2412050,5662307,9266306,508520,609383,2372228,1101930,24863,6967110,109906,8298,7167787,112244,4269379,7272998,7336804,6575398,3664781,4948277,8881542,7425974,8019993,4166930,6840805,5963898,898732,2148966,7147687,7147688,2003452,9352099,1137545,1871234,6453239,78870,3280519,93654,107286,9407259,4621055,7858781,7999349,131464,129438,9034475,7057979,7264404,348494,7754854,9043853,7960830,4410463,2256080,6333447,8379379,2323925,456087,1813387,5206522,2321771,298594,297917,2373092,285097,2559253,915914,8536621,6960160,425184,106846,221045,2148384,3859367,9424181,2776123,9349356,993661,2099926,3139285,354641,148398,6023673,8645299,4681148,974734,2828389,6437268,8614264,698868,3610352,7949958,72008,4352812,3471370,9189613,8358852,8296424,3482455,1890736,7919914,8293637,2914472,7478694,6976164,3505999,278533,5566365,838742,9207457,3274,2151936,2232891,967495,5651276,3452161,6376205,8080667,1908435,71108,8046718,8581474,2584115,4160707,8623658,1121628,6397652,4795999,2009215,280566,1323755,5500140,7887816,3169807,2607622,8772266,7486577,7422110,7485249,6993560,3550366,2073669,9473375,7789926,3802986,7178717,3231781,3785691,283780,7050257,1104801,7190410,8548915,4824421,1546459,7519915,7572124,4770634,8033020,164014,1297238,994911,8789472,7957655,8446468,6990331,7729817,7140785,7684348,7809691,5022943,111606,2765201,5036494,3110455,1833696,1513697,6517931,316683,566463,515135,211488,2809660,2217369,4966581,9053105,3418840,1374533,7722071,7590584,8054924,8243862,7966306,5710570,9376688,4207042,217800,8384243,1575028,1736463,5123146,2989697,8866434,778723,6960330,8519806,9523406,7196492,169737,8554370,4142293,6845183,7338017,4091656,6961041,136908,4298823,2478976,3997139,9561704,4952460,8149506,8411098,8098328,1786111,4140376,7165403,289522,8096795,8498728,6987001,7659795,3028367,4604068,746230,1056834,3146041,116923,3910580,9276620,9358771,6455798,6456620,8864492,679959,306701,2635903,1743054,261811,6406558,3224035,5147108,69651,2588869,1951821,9484668,7656394,4061123,8923981,8757308,1239129,4083171,6830740,7010674,7555387,3598436,7227310,1550683,2193939,6451740,8241827,319660,2527258,7458978,7250479,118124,9325534,7829778,193280,6914121,7666720,7137715,2723639,3048001,2645026,2666203,117467,2005084,7046901,690075,1653517,6856572,2378678,3137098,5129365,7169046,6075131,8130720,1156355,8041340,8260866,8673634,7619959,330852,7154849,7154850,115375,9009717,9355435,9361836,9360815,1329125,2548954,4523482,8134859,4621388,4176349,7506169,5766654,5258429,7725741,9058727,8806825,3081823,5513697,2488303,632400,7393509,130237,2584227,8595728,210072,6836308,7679217,7127055,451945,128256,3519196,2159982,8560029,5528094,175610,1973784,6947802,5529810,2029854,5689645,8289373,8083509,431319,1391495,7462975,8249862,4983494,4017215,1036512,7576961,7446672,3458533,4080165,3691337,6742291,8516269,137576,214611,7454142,7553709,7238305,8741870,8085403,2377334,6683788,106818,1078284,8191173,2961995,4645781,5739260,8861716,4640921,1591871,5399062,993876,129917,8949542,2637259,7080297,926341,6376719,5168656,8556016,7037895,4736391,8977248,9216763,1081521,1178139,49794,5164294,8161570,9379637,4252042,1019637,4957665,8589423,9119819,4194514,5726681,7764711,7504022,1206334,9462715,6733183,1668298,2044086,9361624,7239176,8410727,9222348,6917838,428802,8296143,6979637,6240193,2862598,6254549,1068789,8256087,1911150,7467898,866902,2869207,2817226,404328,70687,9419209,6499599,9393012,3641399,8926510,9331547,1628294,1877326,8178928,898268,7043973,1233336,7527295,1965873,1962684,8546710,3583145,970756,5167342,9416741,8853678,8670046,7106909,342082,8057274,2029869,4339738,8600547,2626576,2282591,1771105,1112016,75984,7136572,7091515,7289300,2833189,7883404,8918677,975214,2916308,1346210,7834437,9442338,9019231,5033185,6024705,8862612,7149895,7149896,7557329,7723618,7449283,7635399,6216688,693531,19244,7892806,6836669,9265025,1358090,156030,4860994,933043,1320758,3436960,4202950,9031631,691296,8385970,5287856,5668083,2143687,7233754,2055771,7543344,3522532,929066,4220161,5367297,7690844,8697860,228755,537126,3594653,3574712,506288,8938055,8433170,6831430,157672,734586,9531694,2652316,7184362,257877,1734207,205101,8355243,8109382,8581215,65642,5465349,9180698,5378962,860818,5803824,475650,7556866,2688995,8670095,1524893,5290667,2992115,8278954,9250696,3929780,256617,9512432,9071240,3402985,1601492,7403882,4074954,191701,7523847,303528,2673573,3305431,9136361,7424385,249097,456154,2901551,6423632,6144328,4002512,7327333,7794416,7803406,858572,6829899,6094544,93524,8649698,8260071,6203725,7436799,1338614,2665891,963496,482612,4013699,7148605,7148606,4942405,7191227,945670,516305,26315,132480,2114023,142143,9214342,4206751,7614801,336584,3250738,3424105,167849,5414440,4251907,848621,1355879,806140,8202844,2723785,9265015,2117248,7732681,7525996,3148198,9192914,8746359,1103772,5875815,4181341,6426694,7542066,9169462,9233406,3745680,9341796,3902897,275581,7333814,7991982,854662,1206994,172071,942898,5793102,7690145,7593119,177393,7175674,7175675,6160909,8377814,7252380,246728,7511313,2227767,1933112,9261401,6099541,1515782,8958391,2070681,1774534,8164990,7685623,8637842,8816068,7079243,7948700,850015,151647,9527597,8166836,119585,852055,605711,9184941,8299339,1881010,9280231,7359232,2249834,2178809,8531453,1265603,2078237,2739315,8247820,1726782,919513,2899013,9427065,8325715,8427919,2307152,7191365,2035584,1866074,7532797,1140548,7727701,8520584,3526516,5461569,1709683,7902953,8248038,859768,3861470,4228561,5173606,6988372,113918,6340511,5112466,7834698,3570693,9198714,7682575,8165881,345922,1938988,512126,727059,1934654,62794,9103303,530570,6515861,4869253,5846226,70207,3991445,9248345,8538671,8128511,6399952,57312,324539,3530905,7348451,6850609,2026072,1970940,73400,337013,4880023,1399175,2064678,2261219,428835,1657330,641316,1846457,6892272,2857447,138579,4731245,1806013,9549468,284877,5013707,3090643,8325557,7235658,792844,8384019,7870221,7480964,7682593,8934188,717297,8943747,9148760,849670,9334083,6378693,9396258,6882920,3575981,2013676,6280394,2575385,5112232,1367855,468707,4404262,900568,1073007,3768117,548423,4946791,8506201,1047195,6363031,2380094,7681338,1786585,7940868,1903228,1153837,818737,8483820,118259,303590,8859091,3855986,290463,7539617,8623533,7209593,8188385,7041954,7673920,3768396,8749867,8864858,8411161,4962615,5187095,1106061,1208171,7449529,63342,2037519,8773861,6866781,8972996,7378087,5476122,3448270,8825644,3707457,4302928,8930890,855593,3695634,164038,9102871,185295,7870433,4997285,6608987,5291147,8293150,8145331,8361100,7076717,2912405,940067,3883607,541199,26752,6525447,3919175,7170692,322833,9100800,5701762,9283841,8234928,4295188,3557883,7447235,6023777,9485099,6956634,6026071,1573354,7284672,9202715,6991966,3551320,8991371,6901194,160902,7956583,7284267,9498209,7499140,316523,4075302,4166817,1384472,789934,337745,4281651,8463637,8084420,7127325,2760037,8795096,8237566,699492,1184377,5883951,3766704,7835951,1157647,8976442,1922664,1667059,548741,5581607,4652264,716680,1314941,3595061,2664159,1386422,4132027,2553496,8134560,81935,62499,4458241,5193338,9169654,1215045,1264490,1886827,2053917,6700111,5906862,1188869,5370310,6699637,7209183,3939785,4153820,99799,4578151,8660970,9417267,6444490,4385320,400362,8941210,8466194,3065542,5521050,9332973,1774468,9551717,1002894,8682167,8843859,6104623,5560831,8557689,120238,1160328,1877611,6655579,941422,3246556,2896847,8999462,1818868,3794439,4314712,176936,1078104,7302522,9523782,3676010,550395,8310335,6233401,7039189,8483966,2456279,7682578,1217823,6784042,1165335,5567169,1267286,1108398,7140141,759973,1284554,206362,8340248,8067412,9369784,5333690,8735253,4060667,1048464,651189,8128806,7516841,8815057,8050959,7356789,6138873,8096327,9105741,2430521,4583416,2753785,1256825,8478837,2087449,693576,214467,5950659,4542724,3874616,7017156,449044,7692586,2620477,1709287,8739070,8081758,6864135,125129,580370,6577879,7115200,3649406,2270693,8047592,684021,8800974,9349362,4547482,2299409,6463061,1807651,9219109,5003912,5300918,8069625,3681962,6390183,8868095,8103592,9409056,6992848,2952359,6832437,8355249,1533965,109154,2784463,8209208,230725,1130267,5238734,3531610,4165989,8376420,221872,6302227,2361215,9258568,110963,9488540,6608776,460913,8808587,8808705,2591678,698982,7917668,2234007,9096095,6904539,7406604,9091323,844,2025748,8521024,2047476,9119401,189377,768790,280573,8729789,3659039,8735097,4167138,9119006,446772,1880335,8962873,9178401,1721770,1529357,5762830,7882070,2051025,1779637,1236087,9430950,7681666,8002172,3825122,113603,214729,7908003,6387649,4088953,4281529,8361822,1121376,2183652,279786,231216,1245063,1940960,7185793,1666948,2147253,8928668,997600,1361471,7673513,8335324,7785049,8892413,8616575,8239014,7077475,8515569,636570,6946294,9520703,5445912,4165245,216359,126568,7291637,5173720,207296,7658354,9068424,9279102,341175,8715789,62195,5973540,5234377,8545062,2191620,478622,531528,8943927,3280861,9194320,7322378,3618590,2078945,8008375,6270921,7478369,4773655,65893,1187369,2440232,221952,9243649,2023000,737875,3705567,4204372,567806,78393,2745211,6365645,8808509,578025,2654857,1400102,126374,4208881,8517045,1817551,7950915,7562924,1571785,3663272,8637726,2748861,8442846,7585714,9220792,7012054,381426,1185418,7301172,644931,8778470,8468946,7644050,3904598,9409932,7759361,6931512,4659335,4514404,7678669,8897191,9402717,9135510,8389966,8877030,1570966,1633511,498128,5542986,7947692,932633,135802,1357784,917482,1994166,1040838,514769,3872495,5284439,2682101,8209585,7908340,9551223,8286930,8329935,7406214,223056,278746,1809097,810028,1182265,8761548,700029,6049652,9520851,281483,7328920,8328394,4223992,362767,299242,6613864,68103,7456353,6499423,6478,4533736,4459981,8213495,5814291,4779037,543629,828661,465686,7754752,6332300,8402749,3431923,2251745,5923578,9218673,968620,129695,1649704,500402,9223648,851020,987577,6339175,6358705,4183228,6343937,6634177,6637783,3880544,3880553,7836507,3079984,3152020,2599756,8184764,1260131,8703227,3582386,4227670,7891393,272640,9178248,2361407,1036614,201024,7884729,1676293,8853698,9436590,6368275,6503229,8625377,128749,7987169,9451246,5550102,5089234,63795,9239385,203590,2455937,7187180,7013637,184245,8077083,7974028,207782,2772585,346534,3698832,4201984,9522448,5266340,6643351,6506141,8564022,7161087,115975,209840,5452605,7626768,1262336,8101503,1323200,6773707,8488545,3225031,3055825,9488210,1156835,165826,9253468,4622510,8822783,4241473,4899031,2018971,29253,235512,6934314,7129087,4852690,4604041,1382195,7081801,7024555,4067937,9177336,5179130,6971919,9380405,3677921,3059074,26189,6448740,2023171,8595657,3884528,8412510,269284,7222127,3710904,8260257,8874134,640008,7316303,979297,6424306,7722492,8551657,2774035,2949863,2855314,9232854,2722307,8793596,337367,9076936,6592819,6935171,9235971,2144755,2386076,4947261,7600057,9553492,611990,4273633,7001688,7740079,101797,9207933,7077567,7436429,8671507,4067922,1865435,3116275,178084,7463574,4765501,1293671,602132,5053753,5992983,4694159,7971602,7734758,2735245,9131657,1161194,6443514,1688164,4124266,8691536,8203187,4782094,1367552,8561121,204393,8605996,8605257,2091319,7601909,6767590,271402,5051578,3553747,6884363,7064859,5777154,4164351,7847600,223945,32261,1066188,7448092,7853388,9129274,235656,9060208,7944341,2673355,8366417,4089445,5061,67653,622658,2419763,6395750,8457363,6222430,7726112,9168203,3339247,8299806,1862036,1000554,9327287,7606128,2779091,123905,3351424,7547898,8225643,459388,313120,3635744,5728727,774866,2514868,832372,8593337,4766140,190412,4480774,7775525,2652817,5917206,212263,6881292,9004406,2994956,6889036,62959,398287,497027,88892,414958,865681,5299898,4562981,3302563,3167377,6562279,7870089,9174834,7495057,7186035,111375,2177868,8962117,5685555,7548361,69164,7166607,2891345,6483548,2876501,4746916,8383753,8440561,8111199,2571616,6868324,3555154,1334540,3226780,7695074,2504314,2268470,7664637,7841941,1787107,4297196,8731238,7828586,8228807,9038297,6584215,1712614,7133982,6436770,4201246,9010906,8280696,3717042,424333,7480888,5575757,3132025,9317746,2711913,7098320,2875106,4332547,71893,2292815,5890146,9072174,8993832,49060,942460,726073,5047366,8407378,6280364,3819090,1397840,7360136,8003219,5704384,4056359,2380979,9560999,6762157,9223351,3388471,9459350,4275577,3909164,1110888,2799325,7091022,8980787,8900777,5332367,7670251,9215702,7617322,7657312,9228536,3865358,2422304,8383112,9217229,3255412,2229030,7081522,1723596,2153646,3493087,4466929,751114,4665047,6186031,9317137,4030571,5919930,2820505,169524,261961,2876645,5738207,5896383,7790810,2388317,6871661,3295033,9089943,2588641,2774589,1840548,7850741,3764550,3057298,8302766,2715997,3742392,7928592,7050823,2541652,7988790,7094539,9023218,94843,5456652,8427647,6841550,4598119,8219141,2856979,589017,6744931,5179745,7847155,7859868,1243878,8792575,569975,57322,3878699,2886659,9043653,7660423,874196,108608,5370655,7295334,1510439,486419,5511687,499700,8158707,8042855,8187919,8195335,8187953,8195362,8179591,7841212,988821,7179921,4593274,4373860,297845,209358,6435560,269869,7380835,9351535,1910847,5672943,8506713,567375,8906233,2630161,80572,8485720,4982117,4394071,163268,4125331,510872,1081566,8061677,8684034,1977525,4124173,4083462,6464357,4043828,701373,446649,4781338,3860777,2366363,3811521,5954784,2158161,8653031,2896883,8474333,214491,4788292,7186302,9407956,7381808,2068773,1334447,1385138,8949672,1716658,488771,8453872,158442,1825308,9243807,576423,4378774,4332295,7092213,1192722,1539541,2713047,3188989,3239215,2039994,6372965,2980802,3699609,6874784,3262894,143500,5377873,7811789,110593,719226,7489966,9024822,9514387,5208544,6223,9107150,4272805,2052894,3636029,8888928,7016708,9488355,668613,8739963,3112936,1960608,8638724,7372070,6952698,9042872,9337608,1745013,6831616,6031039,590864,110872,3684222,2835421,9521276,8186041,7347585,4272292,7916036,9232612,7157842,7157843,7596705,5569760,7859493,8332879,7261127,3594161,7682646,4291342,8351817,176174,342490,97304,3738651,6228772,9172376,2306603,250706,669156,4641308,7233244,1020570,241221,203452,891118,1272677,6697462,1642232,271567,9448656,6899261,8851089,3187312,7922062,1170901,427902,2232696,8724538,875167,9218384,4504648,339843,119382,216389,8243800,8852523,8097880,9303504,117459,7120885,3295462,129751,1262636,62124,7401851,7507924,2203824,6877476,2129086,1356626,8215287,8725249,1145882,7772775,7759246,221554,349188,47993,6037850,9083431,6023827,237737,5183783,8557797,7984908,9351277,5847063,8284971,1172265,2533123,7561404,7239794,7266649,6027873,1345304,2063001,108691,1307045,8082912,8440574,5117440,8338015,1992762,3664646,7106489,7089002,5073826,138969,9094687,1817758,7258250,6151972,8792357,2924222,968482,4605488,6296801,1845854,9102372,8229053,8264490,7776790,6766108,7716146,2647060,4855705,7362719,4598908,9125840,8702220,3905531,640764,5589119,1725006,7584830,5543688,6771508,154598,1585070,136153,3278818,8122283,9209556,4120777,8129555,2141068,9155224,3427453,5586911,7633338,1762522,460992,118017,7901430,5798046,7870719,7696012,1778983,5145845,5452998,132781,7003451,7260748,634530,812851,170002,8485906,7457671,4529866,1365812,5577665,8956721,9496042,9393324,1693555,538199,7424674,7073348,578066,988938,1839072,23203,2323172,8299669,5174683,8492827,4030766,939859,9369676,189298,2047629,7646977,4737267,1018179,4080531,7714262,229475,7605361,496979,2007376,3805734,6992349,2763251,9148518,6066467,5615702,6042251,6629266,308165,7009637,6886092,7376024,7909222,8265928,227743,1923728,8205516,2846275,1073295,4064415,5449698,7765588,8358508,1086960,9501370,5953611,2794924,9072479,56735,8168710,3663308,5652638,4761583,2163440,3347566,8540247,7164764,8720179,414031,9106397,4803064,6581752,7916104,2736391,3352753,4122433,9481232,8894023,103009,7349383,9318154,7622434,1898359,8088122,7367095,9403376,3554230,5282594,63943,2188503,8176915,5047192,6920546,501908,2584415,204177,6015769,7520263,7037040,2114197,374518,1155311,7561272,9418685,8924137,2522167,893543,3005195,1005666,949397,4574677,8128211,1920748,8164492,3785583,5124547,3795360,106652,7310791,9271361,8096150,8477207,1935096,2949971,4612904,7803492,8349947,2100085,4545580,5413636,1267427,6344285,6025219,1741704,1701184,8936355,8278147,2098186,9334021,261164,1105710,2177240,3945848,7783574,8398834,7716827,6819087,4139086,2161032,3112246,2220744,7413080,5006933,2830507,7134384,8861585,261663,8699706,8338964,1545943,168940,9179848,8984300,1930308,2181959,7577967,8129011,7637118,9008584,4166621,644496,8418008,1108965,60521,8813663,9238804,7244899,6989454,6858049,5696047,5741383,6793471,7865658,9171385,2189238,7810035,7228600,1356107,8539222,4604656,7150045,7150046,3418192,231368,9120152,3132136,7021690,311820,2595730,1663168,916819,7722807,8784471,9308022,1262738,7669423,4338244,8884059,6414160,7476351,8964045,330181,613737,148679,2725935,3333922,752632,7135413,8296575,1250054,8413481,7961159,6343459,6916213,7219644,8476185,347153,5973549,6020409,7360683,5857302,7380193,7342694,8650672,1023513,8212810,7581093,6113653,7486149,9444695,755167,8831449,598871,239070,4318705,119650,6831505,8841105,765571,4190959,118261,7711053,3303487,8414207,6172639,161643,161983,8139234,619521,192938,725326,4766413,9375703,939307,9433101,7631674,8513810,8126663,8611764,2781215,9118981,4382584,9245970,5870985,1071495,7994855,8881303,2961509,101627,1338644,8475624,2532148,1306055,2797441,4186810,9430438,5819214,9181043,202634,7517384,5359751,1569166,7358738,7237642,6206440,28953,8224739,9105590,9378331,494030,685575,1385525,9388688,543611,7133520,3934001,4243570,4254880,3885320,3885287,2274530,6882122,8336584,2351630,2512891,4216894,2934527,6311141,7836776,209814,2911916,8558196,6467897,7419105,9129875,8892027,206143,6505439,6647983,2546170,7530746,3810087,366643,1369649,6280634,3597977,7347607,9254618,8994829,7852512,6219442,5506953,9112193,2199804,8788822,667449,4270858,7394707,1165647,60267,4439923,9244389,3256675,331199,5325455,405949,1389911,598379,7886293,1970733,8543968,2394911,7326132,3104701,438445,549245,2331437,7080908,8677356,7336861,8675759,8651094,5505336,1159624,4155185,531329,142234,9309092,8487626,2888225,3057211,8785682,932059,9071012,7803426,7917437,461528,1844255,1083510,6892348,6412048,8134816,8203460,7370016,4484461,8118264,8514727,2686065,2213160,5271977,376399,263717,960949,4230934,8254428,7593620,6685678,738727,7601192,1094736,64240,9054255,7237553,430299,287730,9424468,391251,2082223,713772,4169586,7553738,75067,3906392,2827324,3835997,2024704,3640613,7571459,6200710,3422674,1662760,7926387,4051421,2309774,110547,237177,2415938,710559,8224931,1395125,1652122,20983,8033914,203953,6898134,89217,9184307,6504371,8349300,2581199,7108436,7591155,165393,7340832,9475708,9459393,6831973,8417743,9001815,239383
);

foreach ($array as $item) {
    try{



        $apmin2Sql = "
    UPDATE usuario
SET login   = REPLACE(REPLACE(login, CHAR(13), ''), CHAR(10), '')
WHERE usuario_id='{$item}'; 
    ";
        $BonoInterno = new \Backend\dto\BonoInterno();
        $BonoInterno->execQuery("", $apmin2Sql);
    }catch (Exception $e){}
}

exit();
try {


    $sql = "SELECT usuario_id
FROM casino.usuario_copy t

WHERE login COLLATE latin1_bin REGEXP '[A-Z]'";
    $BonoInterno = new \Backend\dto\BonoInterno();
    $apmin2_RS = $BonoInterno->execQuery("", $sql);
    $batchSize = 5000;
    $idUsuarios = [];
    print_r(oldCount($apmin2_RS));
    flush();
    ob_flush();
    foreach ($apmin2_RS as $key => $value) {

        if($value->{'t.usuario_id'} != 9196617){
            $idUsuarios[] = $value->{'t.usuario_id'};
        }

        // Procesar en lotes de 10,000
        if (count($idUsuarios) >= $batchSize) {
            print_r(PHP_EOL);
            print_r($key);
            flush();
            ob_flush();
            actualizarUsuarios($idUsuarios);
            $idUsuarios = []; // Reiniciar el array después de la actualización
        }
    }

// Procesar cualquier remanente que no haya alcanzado el tamaño del lote
    if (!empty($idUsuarios)) {
        actualizarUsuarios($idUsuarios);
    }

} catch (Exception $e) {
    print_r($e);
}
exit();
exit();
$sql = "
SELECT usuario.usuario_id, usuario_mandante.usumandante_id,usuario_mandante.mandante,
       max(usuario_token.fecha_modif) fecha_modif
FROM casino.usuario_token
         inner join usuario_mandante on usuario_mandante.usumandante_id = usuario_token.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante

WHERE usuario_token.fecha_modif >= '2025-03-20 00:00:00' and usuario.usuario_id=4171521
  group by usuario.usuario_id
  ";
print_r($sql);
$BonoInterno = new BonoInterno();
$data = $BonoInterno->execQuery('', $sql);

try {


    foreach ($data as $valueUltimoMinuto) {
        print_r($data);
        $Usuario = new \Backend\dto\Usuario($valueUltimoMinuto->{"usuario.usuario_id"});
        $UsuarioMandante = new \Backend\dto\UsuarioMandante($valueUltimoMinuto->{"usuario_mandante.usumandante_id"});
        $Mandante = new \Backend\dto\Mandante($valueUltimoMinuto->{"usuario_mandante.mandante"});
        $bannerInv = array();
        #BANNER DE USUARIOS SIN SALDO
        if (false) {
            if ($Usuario->usuarioId == 886 && $Usuario->mandante == 0) {

                $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->getUsumandanteId() . '","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensaje.fecha_crea", "data": "' . date('Y-m-d') . '","op":"cn"}] ,"groupOp" : "AND"}';
                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true, $UsuarioMandante->getUsumandanteId());

                $usuarios = json_decode($usuarios);

                if (intval($usuarios->count[0]->{".count"}) > 0 && floatval($Usuario->getBalance()) < 1) {

                    $array = [];
                    array_push($array, 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png');
                    array_push($array, '¡ CLICK PARA DEPOSITAR !');
                    array_push($array, ':star: ¿TE QUEDASTE SIN SALDO? :moneybag: DEPOSITA Y SIGUE GANANDO ! :credit_card: ¡ Ha llegado VISA ! :smiley: :credit_card: :point_right: ¡HAZ CLICK AQUI! :point_left:');
                    array_push($array, 'https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv');
                    array_push($array, '_self');
                    array_push($array, '');
                    array_push($array, "isMessage");
                    array_push($array, "¡ CLICK PARA DEPOSITAR !");

                    $bannerInv = $array;

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                    $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                    $UsuarioMensaje->tipo = "MESSAGEINV";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->setExternoId(0);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                }


            }
        }
        if (true) {
            #MENSAJES PARA USUARIOS DE CUMPLEAÑOS
            if ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S') {
                // if ( $UsuarioMandante->getUsuarioMandante() =='73818') {
                $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);
                //   if ( $UsuarioMandante->getUsuarioMandante() =='73818') {
                if (strpos(substr($UsuarioOtraInfo->fechaNacim, -5), date('-m-d')) !== false) {


                    $arrayCampanasCumple = array(
                        '8_66' => 67638
                    , '0_173' => 48095
                    , '0_66' => 135650
                    , '0_60' => 135650
                    , '0_68' => 135651
                    , '0_46' => 135651
                    );
                    $arrayBonoCumple = array(
                        '8_66' => 42738
                    , '0_173' => 62982
                    , '0_66' => 46860
                    , '0_60' => 59255
                    , '0_68' => 60352
                    , '0_46' => 63136
                    );

                    foreach ($arrayCampanasCumple as $keyCampana => $valueCampana) {
                        $partner = explode('_', $keyCampana)[0];
                        $pais = explode('_', $keyCampana)[1];
                        if (!($Usuario->mandante == $partner && $Usuario->paisId == $pais)) {
                            continue;
                        }
                        if ($arrayBonoCumple[$keyCampana] == null) {
                            continue;
                        }
                        $bannerInv = [];
                        $depositPopup = array();
                        $dropedJackpotData = array();
                        $bonusesDatanew = array();

                        $rulesM = array();
                        array_push($rulesM, array("field" => "usuario_mensaje.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "in"));
                        array_push($rulesM, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                        array_push($rulesM, array("field" => "usuario_mensaje.fecha_crea", "data" => date('Y-m-d'), "op" => "cn"));
                        array_push($rulesM, array("field" => "usuario_mensaje.usumencampana_id", "data" => $valueCampana, "op" => "eq"));


                        $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                        $json2 = json_encode($filtroM);

                        $UsuarioMensaje = new UsuarioMensaje();
                        $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

                        $usuarios = json_decode($usuarios);


                        if (intval($usuarios->count[0]->{".count"}) == 0) {
                            $mensajeEnviado = false;
                        }


                        try {
                            if (!$mensajeEnviado) {

                                $UsuarioMensaje = new UsuarioMensaje();
                                $UsuarioMensaje->usufromId = 0;
                                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                $UsuarioMensaje->isRead = 0;
                                $UsuarioMensaje->body = '';
                                $UsuarioMensaje->msubject = 'Campaña Cumpleaños';
                                $UsuarioMensaje->tipo = "MESSAGEINV";
                                $UsuarioMensaje->parentId = 151931894;
                                $UsuarioMensaje->proveedorId = $Usuario->mandante;
                                $UsuarioMensaje->setExternoId(0);
                                $UsuarioMensaje->usumencampanaId = $valueCampana;


                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                                $bonoId = $arrayBonoCumple[$keyCampana];

                                $UsuarioBono = new UsuarioBono();

                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

                                $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                                $UsuarioBono->setUsuarioId(0);
                                $UsuarioBono->setBonoId($bonoId);
                                $UsuarioBono->setValor(0);
                                $UsuarioBono->setValorBono(0);
                                $UsuarioBono->setValorBase(0);
                                $UsuarioBono->setEstado("L");
                                $UsuarioBono->setErrorId(0);
                                $UsuarioBono->setIdExterno(0);
                                $UsuarioBono->setMandante($Usuario->mandante);
                                $UsuarioBono->setUsucreaId(0);
                                $UsuarioBono->setUsumodifId(0);
                                $UsuarioBono->setApostado(0);
                                $UsuarioBono->setRollowerRequerido(0);
                                $UsuarioBono->setCodigo("");
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);


                                //print_r($UsuarioBono);

                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                $transaccion5->commit();

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

                                $BonoInterno = new BonoInterno();
                                $BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();

                                $Transaction = $BonoInternoMySqlDAO->getTransaction();

                                $detalles = json_decode(json_encode($detalles));

                                $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction);
                                $Transaction->commit();

                            }

                        } catch (Exception $e) {

                        }
                    }
                }
            }
        }

        #Mensajes invasivos

        if (true) {


            //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA
            $currentDateTime = date('Y-m-d H:i:s');


            $rulesM = array();
            array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.usuto_id", "data" => "0", "op" => "in"));
            array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
            array_push($rulesM, array("field" => "usuario_mensaje2.usumensaje_id", "data" => "NULL", "op" => "isnull"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "MESSAGEINV", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));

            print_r($rulesM);

            $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
            $json2 = json_encode($filtroM);

            $UsuarioMensajecampana = new UsuarioMensajecampana();
            $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

            $usuarios = json_decode($usuarios);


            print_r('entrooo');
            print_r($usuarios);
            foreach ($usuarios->data as $key => $value) {

                $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Image;
                $URL = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                $target = json_decode($value->{"usuario_mensajecampana.t_value"})->Target;

                $body = $value->{"usuario_mensajecampana.body"};
                $botonTexto = json_decode($value->{"usuario_mensajecampana.t_value"})->ButtonText;

                $array = [];
                array_push($array, $imagen);
                array_push($array, $botonTexto);
                array_push($array, $body);
                array_push($array, $URL);
                array_push($array, $target);
                array_push($array, '');
                array_push($array, "isMessage");
                array_push($array, $value->{"usuario_mensajecampana.parent_id"});

                $bannerInv = $array;


                if (false) {
                    $bannerInv = [];
                    $bannerInv['image'] = $array[0];
                    $bannerInv['buttonText'] = $array[1];
                    $bannerInv['body'] = $array[2];
                    $bannerInv['url'] = $array[3];
                    $bannerInv['target'] = $array[4];
                    $bannerInv['target2'] = $array[5];
                    $bannerInv['type'] = 'bannerInvasive';
                    $bannerInv['isMessage'] = true;
                    $bannerInv['parentId'] = $value->{"usuario_mensajecampana.parent_id"};
                }

                /*                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                    $UsuarioMensaje->isRead = 1;
                                    $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                                    $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = 0;
                                    $UsuarioMensaje->setExternoId(0);




                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/


                if ($value->{"usuario_mensajecampana.usuto_id"} == '0') {
                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = $value->{"usuario_mensajecampana.body"};
                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);
                    $UsuarioMensaje->msubject = $value->{"usuario_mensajecampana.msubject"};
                    $UsuarioMensaje->tipo = "MESSAGEINV";
                    $UsuarioMensaje->parentId = $value->{"usuario_mensajecampana.usumensaje_id"};
                    $UsuarioMensaje->usumencampanaId = $value->{"usuario_mensajecampana.usumencampana_id"};
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->setExternoId(0);
                    $UsuarioMensaje->setUsucreaId(0);
                    $UsuarioMensaje->setUsumodifId(0);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                }

                $seguirBanner = false;

            }


            print_r('entrooo');
            print_r($bannerInv);
            if (oldCount($bannerInv) == 0) {
                print_r('entrooo2bannerInv');

                //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA para un USUARIO EN ESPECIFICO
                $currentDateTime = date('Y-m-d H:i:s');

                $rulesM = array();
                array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
                array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensaje2.is_read", "data" => "0", "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));


                $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                $json2 = json_encode($filtroM);
                print_r($rulesM);

                print_r('aquiii');

                $UsuarioMensajecampana = new UsuarioMensajecampana();
                $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());
                print_r('aquiii2');

                print_r('entrooo2');
                print_r($usuarios);
                $usuarios = json_decode($usuarios);

                foreach ($usuarios->data as $key => $value) {

                    $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Image;
                    if ($imagen == "") {
                        $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Imagen;

                    }
                    $URL = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                    $target = json_decode($value->{"usuario_mensajecampana.t_value"})->Target;


                    $body = $value->{"usuario_mensajecampana.body"};
                    $nombre = $value->{"usuario_mensajecampana.nombre"};

                    /**
                     * Propósito: devolver las notificaciones
                     * $Url: trae la url que se envia de pop ups
                     */


                    if ($nombre == "JACKPOT POPUP") {
                        $URL = $value->{"usuario_mensajecampana.t_value"};
                    }

                    $botonTexto = json_decode($value->{"usuario_mensajecampana.t_value"})->ButtonText;

                    $array = [];
                    array_push($array, $imagen);
                    array_push($array, $botonTexto);
                    array_push($array, $body);
                    array_push($array, $URL);
                    array_push($array, $target);
                    array_push($array, '');
                    array_push($array, "isMessage");
                    array_push($array, $value->{"usuario_mensajecampana.parent_id"});

                    $bannerInv = $array;

                    if (false) {
                        $bannerInv = [];
                        $bannerInv['image'] = $array[0];
                        $bannerInv['buttonText'] = $array[1];
                        $bannerInv['body'] = $array[2];
                        $bannerInv['url'] = $array[3];
                        $bannerInv['target'] = $array[4];
                        $bannerInv['target2'] = $array[5];
                        $bannerInv['type'] = 'bannerInvasive';
                        $bannerInv['parentId'] = $value->{"usuario_mensajecampana.parent_id"};
                        $bannerInv['isMessage'] = true;
                    }
                    $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje2.usumensaje_id"});
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $seguirBanner = false;

                }
            }

            if (oldCount($bannerInv) == 0) {
                //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA para un USUARIO EN ESPECIFICO
                $currentDateTime = date('Y-m-d H:i:s');

                $rulesM = array();
                array_push($rulesM, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensaje.usuto_id", "data" => $UsuarioMandante->getUsumandanteId() . "", "op" => "in"));
                array_push($rulesM, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                array_push($rulesM, array("field" => "usuario_mensaje.fecha_expiracion", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                array_push($rulesM, array("field" => "usuario_mensaje.is_read", "data" => "0", "op" => "eq"));


                $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                $json2 = json_encode($filtroM);

                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

                $usuarios = json_decode($usuarios);

                foreach ($usuarios->data as $key => $value) {


                    $URL = $value->{"usuario_mensaje.msubject"};
                    $target = '';

                    $body = $value->{"usuario_mensaje.body"};

                    $array = [];
                    array_push($array, $imagen);
                    array_push($array, $botonTexto);
                    array_push($array, $body);
                    array_push($array, $URL);
                    array_push($array, $target);
                    array_push($array, '');
                    array_push($array, "isMessage");
                    array_push($array, $value->{"usuario_mensaje.parent_id"});

                    $bannerInv = $array;

                    if (false) {
                        $bannerInv = [];
                        $bannerInv['image'] = $array[0];
                        $bannerInv['buttonText'] = $array[1];
                        $bannerInv['body'] = $array[2];
                        $bannerInv['url'] = $array[3];
                        $bannerInv['target'] = $array[4];
                        $bannerInv['target2'] = $array[5];
                        $bannerInv['type'] = 'bannerInvasive';
                        $bannerInv['parentId'] = $value->{"usuario_mensaje.parent_id"};
                    }
                    $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $seguirBanner = false;

                }
            }
        }

        #Verificaciones de cuenta
        if (true) {
            try {


                if (($Usuario->mandante == 8 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-08 00:00:00'))) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 0 && $Usuario->paisId == 2) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 23) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 0 && $Usuario->paisId == 46) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 0 && $Usuario->paisId == 66) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 0 && $Usuario->paisId == 94) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 14 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-27 08:00:00'))) && $Usuario->verifCorreo == "N" && false) {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 17 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-27 08:00:00'))) && $Usuario->verifCorreo == "N") {
                    //$ConfigurationEnvironment = new ConfigurationEnvironment();
                    //$ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if ($Usuario->usuarioId == 85 && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if ($Usuario->usuarioId == 1508705 && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }

                if ($Usuario->usuarioId == 1243479 && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }

                if ($Usuario->mandante == 8 && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
            } catch (Exception $e) {
            }
        }

        #BANNER INVASIVO CON MASCOTA

        if (true) {

            // BANNERINV Banner invasivo - Mascota para usuario en especifico


            $rulesM = array();
            array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.usuto_id", "data" => "0", "op" => "in"));
            array_push($rulesM, array("field" => "usuario_mensaje2.usumensaje_id", "data" => "NULL", "op" => "isnull"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "BANNERINV", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));


            $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
            $json2 = json_encode($filtroM);

            $UsuarioMensajecampana = new UsuarioMensajecampana();
            $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

            $usuarios = json_decode($usuarios);

            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["title"] = $value->{"usuario_mensaje.body"};
                $array["url"] = $value->{"usuario_mensaje.msubject"};
                /* $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                 $array["open"] = false;
                 $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
                 $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
                 $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};*/

                /*$UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                $UsuarioMensaje->setIsRead(1);

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/

                $urlFrame = explode('##URL##', $value->{"usuario_mensaje.msubject"})[0];
                $url = explode('##URL##', $value->{"usuario_mensaje.msubject"})[1];

                $seguirProducto = true;
                $proveedorReq = 0;

                if (strpos($urlFrame, 'GAME') !== false) {

                    $gameid = explode('GAME', $urlFrame)[1];

                    if (is_numeric($gameid)) {
                        try {
                            $Producto = new Producto($gameid);
                            $ProductoMandante = new ProductoMandante($gameid, $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);
                            $Proveedor = new Proveedor($Producto->getProveedorId());
                            $urlFrame = 'https://casino.virtualsoft.tech/game/play/?gameid=' . $ProductoMandante->prodmandanteId . '&mode=real&provider=' . $Proveedor->getAbreviado() . '&lan=es&mode=real&partnerid=' . $UsuarioMandante->getMandante();
                            $url = $Mandante->baseUrl . '/' . 'casino' . '/' . $ProductoMandante->prodmandanteId;
                            $proveedorReq = $Proveedor->getProveedorId();
                        } catch (Exception $e) {
                            $seguirProducto = false;

                        }
                    }
                } else {
                    $proveedorReq = $value->{"usuario_mensaje.proveedor_id"};
                }

                if ($seguirProducto) {
                    if ($proveedorReq != 0 && $proveedorReq != '') {

                        $token = '';


                        try {

                            //$UsuarioToken = new UsuarioToken("", $proveedorReq, $UsuarioMandante->getUsumandanteId());
                            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $token = $UsuarioToken->getToken();
                        } catch (Exception $e) {

                            if ($e->getCode() == "21" && false) {

                                $UsuarioToken = new UsuarioToken();

                                $UsuarioToken->setRequestId('');
                                $UsuarioToken->setProveedorId($proveedorReq);
                                $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                                $UsuarioToken->setToken($UsuarioToken->createToken());
                                //$UsuarioToken->setCookie(encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
                                $UsuarioToken->setUsumodifId(0);
                                $UsuarioToken->setUsucreaId(0);
                                $UsuarioToken->setSaldo(0);

                                $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                                $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                                $UsuarioTokenMySqlDAO->getTransaction()->commit();
                                $token = $UsuarioToken->getToken();


                            }
                        }
                        $urlFrame = $urlFrame . '&token=' . $token;
                        // $url = $url . '&token='.$token;

                    }

                    //'https://demogamesfree.pragmaticplay.net/gs2c/openGame.do?lang=en&cur=USD&gameSymbol=vs40beowulf&lobbyURL=https://doradobet.com/new-casino'
                    $array = [];

                    if ($Usuario->mandante == 18) {
                        array_push($array, 'https://images.virtualsoft.tech/m/msjT1683652624.png');

                    } else {
                        array_push($array, 'https://images.virtualsoft.tech/site/doradobet/pet/pet-doradobet.png');

                    }
                    array_push($array, 'Hola');
                    array_push($array, $value->{"usuario_mensaje.body"});
                    array_push($array, $url);
                    array_push($array, '_self');
                    array_push($array, $urlFrame);
                    array_push($array, "isInvasive");

                    // $array["title"] = $value->{"usuario_mensaje.body"};
                    // $array["url"] = $value->{"usuario_mensaje.msubject"};

                    $bannerInv = $array;

                    if ($value->{"usuario_mensaje.usuto_id"} == '0') {
                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                        $UsuarioMensaje->isRead = 1;
                        $UsuarioMensaje->body = str_replace("'", '"', $value->{"usuario_mensaje.body"});
                        $UsuarioMensaje->msubject = $value->{"usuario_mensaje.msubject"};
                        $UsuarioMensaje->tipo = "BANNERINV";
                        $UsuarioMensaje->parentId = $value->{"usuario_mensaje.usumensaje_id"};
                        $UsuarioMensaje->proveedorId = 0;
                        $UsuarioMensaje->setExternoId(0);
                        $UsuarioMensaje->setUsucreaId(0);
                        $UsuarioMensaje->setUsumodifId(0);


                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    } else {
                        $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                        $UsuarioMensaje->isRead = 1;
                        $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    }
                }

            }


        }


        #POPUPS DE DEPOSITAR PARA LOS USUARIOS QUE TIENEN REGALOS
        if (true) {
            $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro_type_gift","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

            $SitioTracking = new SitioTracking();
            $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
            $sitiosTracking = json_decode($sitiosTracking);

            $type_gift = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

            if ($type_gift != '' && $type_gift != 'no_quiero_regalos') {
                $Registro = new Registro('', $Usuario->usuarioId);

                $arrayimgPromotion = array(
                    '0_173' => "https://images.virtualsoft.tech/m/msj0212T1712692456.png", // Doradobet, paisId == '173' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '0_173_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557765.png", // Doradobet, saldo bajo
                    '0_94' => "https://images.virtualsoft.tech/m/msj0212T1714577075.png", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "https://images.virtualsoft.tech/m/msj212T1708556935.png", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557733.png", // Ecuabet, saldo bajo
                    '23_N' => "https://images.virtualsoft.tech/m/msj0212T1714507606.png" // Paniplay, verifcedulaAnt == 'N'
                );

                $arrayimgDecoration = array(
                    '0_173' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '173'
                    '0_173_saldo' => "https://images.virtualsoft.tech/m/msj212T1708556742.png", // Doradobet, saldo bajo
                    '0_94' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '94'
                    '8_66' => "https://images.virtualsoft.tech/m/msj212T1708556953.png", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557116.png", // Ecuabet, saldo bajo
                    '23_102' => "https://images.virtualsoft.tech/m/msj0212T1708557280.png" // Paniplay, verifcedulaAnt == 'N'
                );

                $arraytextButton = array(
                    '0_173' => "¡Deposita Ya!", // Doradobet, paisId == '173' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '0_173_saldo' => "¡Deposita Ya!", // Doradobet, saldo bajo
                    '0_94' => "¡Deposita Ya!", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "¡Deposita Ya!", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66_saldo' => "¡Deposita Ya!", // Ecuabet, saldo bajo
                    '23_102' => "¡Ingresa Ya!" // Paniplay, verifcedulaAnt == 'N'
                );

                $arrayurlButton = array(
                    '0_173' => "/gestion/deposito", // Doradobet, saldo bajo
                    '0_94' => "/gestion/deposito", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "/gestion/deposito", // Ecuabet, saldo bajo
                    '23_102' => "/gestion/verificar_cuenta" // Paniplay, verifcedulaAnt == 'N'
                );
                $arrayimgPromotionSaldo = array(
                    '0_173' => "https://images.virtualsoft.tech/m/msj0212T1708557765.png", // Doradobet, saldo bajo
                    '0_94' => "https://images.virtualsoft.tech/m/msj0212T1714577075.png", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "https://images.virtualsoft.tech/m/msj0212T1708557733.png", // Ecuabet, saldo bajo
                    '23_N' => "https://images.virtualsoft.tech/m/msj0212T1714507606.png" // Paniplay, verifcedulaAnt == 'N'
                );

                $arrayimgDecorationSaldo = array(
                    '0_173' => "https://images.virtualsoft.tech/m/msj212T1708556742.png", // Doradobet, saldo bajo
                    '0_94' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '94'
                    '8_66' => "https://images.virtualsoft.tech/m/msj0212T1708557116.png", // Ecuabet, saldo bajo
                    '23_102' => "https://images.virtualsoft.tech/m/msj0212T1708557280.png" // Paniplay, verifcedulaAnt == 'N'
                );

                $arraytextButtonSaldo = array(
                    '0_173' => "¡Deposita Ya!", // Doradobet, saldo bajo
                    '0_94' => "¡Deposita Ya!", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "¡Deposita Ya!", // Ecuabet, saldo bajo
                    '23_102' => "¡Ingresa Ya!" // Paniplay, verifcedulaAnt == 'N'
                );

                $arrayurlButtonSaldo = array(
                    '0_173' => "/gestion/deposito", // Doradobet, saldo bajo
                    '0_94' => "/gestion/deposito", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "/gestion/deposito", // Ecuabet, saldo bajo
                    '23_102' => "/gestion/verificar_cuenta" // Paniplay, verifcedulaAnt == 'N'
                );
                if ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S' && $Usuario->fechaPrimerdeposito == '') {
                    array_push($depositPopup,
                        array(
                            "imgPromotion" => $arrayimgPromotion[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "imgDecoration" => $arrayimgDecoration[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "textButton" => $arraytextButton[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "urlButton" => $arrayurlButton[$Usuario->mandante . '_' . $Usuario->paisId]
                        ));
                } elseif ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S' && $Usuario->fechaPrimerdeposito != '' &&
                    round((floatval(($Registro->getCreditosBase() * 100)) + floatval(($Registro->getCreditos() * 100))) / 100, 2) < 0.5) {
                    array_push($depositPopup,
                        array(
                            "imgPromotion" => $arrayimgPromotionSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "imgDecoration" => $arrayimgDecorationSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "textButton" => $arraytextButtonSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "urlButton" => $arrayurlButtonSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                        ));
                }


            }
        }


        /** Verificación caídas de Jackpots */
        if (true) {
            $rules = [];
            array_push($rules, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje2.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "JACKPOTWINNER", "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => 0, "op" => "cn"));
            $filtroM = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtroM);

            $UsuarioMensaje = new UsuarioMensaje();
            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());
            $usuarios = json_decode($usuarios)->data;

            $messagesToDesactivate = [];
            foreach ($usuarios as $jackpotWinnerMessage) {
                try {
                    $JackpotInterno = new JackpotInterno($jackpotWinnerMessage->{'usuario_mensaje.body'});
                } catch (Exception $e) {
                    break;
                }

                $dropedJackpotData = [[
                    'uid' => $jackpotWinnerMessage->{'usuario_mensaje.usumensaje_id'},
                    'id' => $JackpotInterno->jackpotId,
                    'videoMobile' => $JackpotInterno->videoMobile,
                    'video' => $JackpotInterno->videoDesktop,
                    'gif' => $JackpotInterno->gif,
                    'imagen' => $JackpotInterno->imagen,
                    'imagen2' => $JackpotInterno->imagen2,
                    'monto' => round($JackpotInterno->valorActual, 2)
                ]];
                $messagesToDesactivate[] = $jackpotWinnerMessage->{'usuario_mensaje.usumensaje_id'};
            }

            if (count($messagesToDesactivate) > 0) {
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->updateReadForID(implode(',', $messagesToDesactivate));
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
            }
        }

        if (false) {
            /*
                            // ----- NOTIFICACIONES PUSH -----
                            $currentDateTime = date('Y-m-d H:i:s');
                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => '-1', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.usufrom_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.usuto_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => $UsuarioMandante->getPaisId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $massiveForCountry = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $massiveForCountry = json_decode($massiveForCountry, true);

                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => '-1', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.usufrom_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.usuto_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                            $massiveForNotCountry = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $massiveForNotCountry = json_decode($massiveForNotCountry, true);

                            $allMassiveMessages = array_merge($massiveForCountry['data'], $massiveForNotCountry['data']);

                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => $UsuarioMandante->getUsumandanteId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.is_read', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => $UsuarioMandante->getPaisId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                            $userMessages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $userMessages = json_decode($userMessages, true);

                            $userMessages['data'] = array_merge($allMassiveMessages, $userMessages['data']);

                            $assignedMessages = [];

                            if (oldCount($allMassiveMessages) > 0) {
                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $ids = array_map(function ($value) {
                                    return $value['usuario_mensaje.usumensaje_id'];
                                }, $allMassiveMessages);
                                $query = $UsuarioMensajeMySqlDAO->queryByParent(implode(',', $ids), $UsuarioMandante->getUsumandanteId(), $currentDateTime);

                                $assignedMessages = array_unique(array_map(function ($value) {
                                    return $value['parent_id'];
                                }, $query));
                            }


                            $pushMessages = [];
                            $updatedMessages = '';

                            foreach ($userMessages['data'] as $key => $value) {
                                $data = [];

                                if ($value['usuario_mensaje.usuto_id'] != $UsuarioMandante->getUsumandanteId() && in_array($value['usuario_mensaje.usumensaje_id'], $assignedMessages) == false) {
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->setUsufromId(0);
                                    $UsuarioMensaje->setUsutoId($UsuarioMandante->getUsumandanteId());
                                    $UsuarioMensaje->setIsRead(1);
                                    $UsuarioMensaje->setMsubject($value['usuario_mensaje.msubject']);
                                    $UsuarioMensaje->setBody($value['usuario_mensaje.body']);
                                    $UsuarioMensaje->setParentId($value['usuario_mensaje.usumensaje_id']);
                                    $UsuarioMensaje->setUsucreaId($value['usuario_mensaje.usucrea_id']);
                                    $UsuarioMensaje->setUsumodifId(0);
                                    $UsuarioMensaje->setTipo($value['usuario_mensaje.tipo']);
                                    $UsuarioMensaje->setExternoId(0);
                                    $UsuarioMensaje->setProveedorId(0);
                                    $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                                    $UsuarioMensaje->setFechaExpiracion($value['usuario_mensaje.fecha_expiracion']);
                                    $UsuarioMensaje->setUsumencampanaId($value['usuario_mensajecampana.usumencampana_id']);

                                    $UsuarioMensaje->setValor1(0);
                                    $UsuarioMensaje->setValor2(0);
                                    $UsuarioMensaje->setValor3(0);

                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $messageID = $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                                    $value['usuario_mensaje.usuto_id'] = $UsuarioMandante->getUsumandanteId();
                                    $value['usuario_mensaje.usumensaje_id'] = $messageID;
                                }

                                if ($value['usuario_mensaje.is_read'] == 0 && !in_array($value['usuario_mensaje.usuto_id'], [-1, 0])) {
                                    $tval = json_decode($value['usuario_mensajecampana.t_value'], true);
                                    $link = "<a href=\"#\" style=\"text-decoration: none; font-size: 1.2em; color: black;\">{$value['usuario_mensaje.body']}</a>";


                                    $data['id'] = $value['usuario_mensaje.usumensaje_id'];
                                    $data['body'] = '<div>' . $link . '</div>';
                                    $updatedMessages .= $value['usuario_mensaje.usumensaje_id'] . ',';

                                    array_push($pushMessages, $data);
                                }
                            }

                            if (!empty($updatedMessages)) {
                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $UsuarioMensajeMySqlDAO->updateReadForID(trim($updatedMessages, ','));
                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                            }


                            $response['data']['messages'] = array_merge($pushMessages, $response['data']['messages']);*/

        }

        ##Franja de Bonos
        if ($Usuario->mandante == 17 || $Usuario->mandante == 19) {


            $MaxRows = 100;
            $OrderedItem = 1;
            $SkeepRows = 0;
            $rules = [];
            array_push($rules, array("field" => "bono_interno.tipo", "data" => '5,6,2,3', "op" => "in"));
            array_push($rules, array("field" => "bono_interno.estado", "data" => 'A', "op" => "eq"));
            array_push($rules, array("field" => "bono_interno.mandante", "data" => $Usuario->mandante, "op" => "eq"));
            array_push($rules, array("field" => "usuario_bono.estado", "data" => 'Q', "op" => "eq"));
            array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);


            $UsuarioBono = new UsuarioBono();


            $UsuarioBonos = $UsuarioBono->getUsuarioBonosCustom(" usuario_bono.*,bono_interno.* ", "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json2, true);


            $bonos = json_decode($UsuarioBonos);


            if ($bonos->count[0]->{".count"} != "0") {
                $bonusesDatanew = array();
                foreach ($bonos->data as $key => $value) {
                    $array = [];
                    $array["bonoId"] = $value->{"usuario_bono.bono_id"};
                    $array["descripcion"] = $value->{"bono_interno.descripcion"};
                    $array["usubonoId"] = $value->{"usuario_bono.usubono_id"};
                    $array["valor"] = $value->{"usuario_bono.valor_bono"};
                    $array["description"] = $value->{"bono_interno.descripcion"};
                    $array["detailId"] = $value->{"usuario_bono.usubono_id"};
                    $array["value"] = $value->{"usuario_bono.valor_bono"};
                    $array["image"] = $value->{"bono_interno.imagen"};

                    array_push($bonusesDatanew, $array);
                }
            }

        }


        ##ENVIO DE MENSAJE PARA USUARIO UNICO

        if (oldCount($depositPopup) > 0) {
            $dataSend = array(
                "depositPopup" => $depositPopup,

            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

        }

        if (oldCount($bannerInv) > 0) {

            $websocketNotification = new WebsocketNotificacion();
            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO();

            $websocketNotification->setTipo('bannerinv');
            $websocketNotification->setCanal('');
            $websocketNotification->setEstado('P');
            $websocketNotification->setUsuarioId($Usuario->usuarioId);
            $websocketNotification->setValor(0);
            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+1 hour')));
            $websocketNotification->setMensaje(json_encode(array(
                "bannerInv" => $bannerInv
            )));

            $websocketNotification->setMensaje(json_encode($bannerInv));
            $websocketNotificacionMySqlDAO->insert($websocketNotification);
            $websocketNotificacionMySqlDAO->getTransaction()->commit();


        }
        if (oldCount($dropedJackpotData) > 0) {
            $dataSend = array(
                "dropedJackpotData" => $dropedJackpotData
            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

        }
        if (oldCount($bonusesDatanew) > 0) {
            $dataSend = array(
                "bonuses" => $bonusesDatanew
            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

        }


    }
} catch (Exception $e) {
    print_r($e);
}

exit();
#REFRESCAR EN EL ULTIMO MINUTO

$sql = "
SELECT usuario.usuario_id,
       usuario_mandante.usumandante_id,
       usuario_mandante.mandante,
       max(usuario_otrainfo.fecha_nacim) fecha_nacim,
       max(usuario_token.fecha_modif) fecha_modif
FROM casino.usuario_token
         inner join usuario_mandante on usuario_mandante.usumandante_id = usuario_token.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante
         inner join usuario_otrainfo on usuario.usuario_id = usuario_otrainfo.usuario_id

WHERE usuario_token.fecha_modif >= '2025-03-17 00:00:00'
  AND usuario_token.fecha_modif < '2025-03-19 23:00:00'

  and usuario.verifcedula_ant = 'S'
  and usuario.verifcedula_post = 'S'
  and usuario.mandante in (0, 8)
  AND (usuario_otrainfo.fecha_nacim like '%-03-19%' OR usuario_otrainfo.fecha_nacim like '%-03-18%' OR
       usuario_otrainfo.fecha_nacim like '%-03-17%')
group by usuario.usuario_id;
  ";

$BonoInterno = new BonoInterno();
$data = $BonoInterno->execQuery('', $sql);


foreach ($data as $valueUltimoMinuto) {

    $Usuario = new \Backend\dto\Usuario($valueUltimoMinuto->{"usuario.usuario_id"});
    $UsuarioMandante = new \Backend\dto\UsuarioMandante($valueUltimoMinuto->{"usuario_mandante.usumandante_id"});
    $Mandante = new \Backend\dto\Mandante($valueUltimoMinuto->{"usuario_mandante.mandante"});

    if (true) {
        #MENSAJES PARA USUARIOS DE CUMPLEAÑOS
        if ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S') {


            $arrayCampanasCumple = array(
                '8_66' => 24749
            , '0_173' => 48095
            , '0_66' => 135650
            , '0_60' => 135650
            , '0_68' => 135651
            , '0_46' => 135651
            );
            $arrayBonoCumple = array(
                '8_66' => 42738
            , '0_173' => 62982
            , '0_66' => 46860
            , '0_60' => 59255
            , '0_68' => 60352
            , '0_46' => 63136
            );

            foreach ($arrayCampanasCumple as $keyCampana => $valueCampana) {
                $partner = explode('_', $keyCampana)[0];
                $pais = explode('_', $keyCampana)[1];
                if (!($Usuario->mandante == $partner && $Usuario->paisId == $pais)) {
                    continue;
                }
                if ($arrayBonoCumple[$keyCampana] == null) {
                    continue;
                }
                $bannerInv = [];
                $depositPopup = array();
                $dropedJackpotData = array();
                $bonusesDatanew = array();

                $rulesM = array();
                array_push($rulesM, array("field" => "usuario_mensaje.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "in"));
                array_push($rulesM, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensaje.fecha_crea", "data" => date('Y-m-d'), "op" => "cn"));
                array_push($rulesM, array("field" => "usuario_mensaje.usumencampana_id", "data" => $valueCampana, "op" => "eq"));


                $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                $json2 = json_encode($filtroM);

                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

                $usuarios = json_decode($usuarios);


                if (intval($usuarios->count[0]->{".count"}) == 0) {
                    $mensajeEnviado = false;
                }


                try {
                    if (!$mensajeEnviado) {

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = '';
                        $UsuarioMensaje->msubject = 'Campaña Cumpleaños';
                        $UsuarioMensaje->tipo = "MESSAGEINV";
                        $UsuarioMensaje->parentId = 151931894;
                        $UsuarioMensaje->proveedorId = $Usuario->mandante;
                        $UsuarioMensaje->setExternoId(0);
                        $UsuarioMensaje->usumencampanaId = $valueCampana;


                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                        $bonoId = $arrayBonoCumple[$keyCampana];

                        $UsuarioBono = new UsuarioBono();

                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

                        $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                        $UsuarioBono->setUsuarioId(0);
                        $UsuarioBono->setBonoId($bonoId);
                        $UsuarioBono->setValor(0);
                        $UsuarioBono->setValorBono(0);
                        $UsuarioBono->setValorBase(0);
                        $UsuarioBono->setEstado("L");
                        $UsuarioBono->setErrorId(0);
                        $UsuarioBono->setIdExterno(0);
                        $UsuarioBono->setMandante($Usuario->mandante);
                        $UsuarioBono->setUsucreaId(0);
                        $UsuarioBono->setUsumodifId(0);
                        $UsuarioBono->setApostado(0);
                        $UsuarioBono->setRollowerRequerido(0);
                        $UsuarioBono->setCodigo("");
                        $UsuarioBono->setVersion(0);
                        $UsuarioBono->setExternoId(0);


                        //print_r($UsuarioBono);

                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                        $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                        $transaccion5->commit();

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

                        $BonoInterno = new BonoInterno();
                        $BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();

                        $Transaction = $BonoInternoMySqlDAO->getTransaction();

                        $detalles = json_decode(json_encode($detalles));

                        $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction);
                        $Transaction->commit();

                    }

                } catch (Exception $e) {

                }
            }

        }
    }


}


exit();

#REFRESCAR EN EL ULTIMO MINUTO

$sql = "
SELECT usuario.usuario_id, usuario_mandante.usumandante_id,usuario_mandante.mandante
FROM casino.usuario_token
         inner join usuario_mandante on usuario_mandante.usumandante_id = usuario_token.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante

WHERE usuario_token.fecha_modif >= '2025-03-17 17:09:00' and usuario_token.usuario_id=4249949
  AND usuario_token.fecha_modif < '2025-03-17 17:37:03' ";

$BonoInterno = new BonoInterno();
$data = $BonoInterno->execQuery('', $sql);

try {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

    foreach ($data as $valueUltimoMinuto) {
        print_r($valueUltimoMinuto);
        $Usuario = new \Backend\dto\Usuario($valueUltimoMinuto->{"usuario.usuario_id"});
        $UsuarioMandante = new \Backend\dto\UsuarioMandante($valueUltimoMinuto->{"usuario_mandante.usumandante_id"});
        $Mandante = new \Backend\dto\Mandante($valueUltimoMinuto->{"usuario_mandante.mandante"});

        #BANNER DE USUARIOS SIN SALDO
        if (false) {
            if ($Usuario->usuarioId == 886 && $Usuario->mandante == 0) {

                $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->getUsumandanteId() . '","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensaje.fecha_crea", "data": "' . date('Y-m-d') . '","op":"cn"}] ,"groupOp" : "AND"}';
                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true, $UsuarioMandante->getUsumandanteId());

                $usuarios = json_decode($usuarios);

                if (intval($usuarios->count[0]->{".count"}) > 0 && floatval($Usuario->getBalance()) < 1) {

                    $array = [];
                    array_push($array, 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png');
                    array_push($array, '¡ CLICK PARA DEPOSITAR !');
                    array_push($array, ':star: ¿TE QUEDASTE SIN SALDO? :moneybag: DEPOSITA Y SIGUE GANANDO ! :credit_card: ¡ Ha llegado VISA ! :smiley: :credit_card: :point_right: ¡HAZ CLICK AQUI! :point_left:');
                    array_push($array, 'https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv');
                    array_push($array, '_self');
                    array_push($array, '');
                    array_push($array, "isMessage");
                    array_push($array, "¡ CLICK PARA DEPOSITAR !");

                    $bannerInv = $array;

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                    $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                    $UsuarioMensaje->tipo = "MESSAGEINV";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->setExternoId(0);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                }


            }
        }
        if (false) {
            #MENSAJES PARA USUARIOS DE CUMPLEAÑOS
            if ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S') {


                $arrayCampanasCumple = array(
                    '8_66' => 24749
                , '0_173' => 48095
                , '0_66' => 135650
                , '0_60' => 135650
                , '0_68' => 135651
                , '0_46' => 135651
                );
                $arrayBonoCumple = array(
                    '8_66' => 42738
                , '0_173' => 62982
                , '0_66' => 46860
                , '0_60' => 59255
                , '0_68' => 60352
                , '0_46' => 63136
                );

                foreach ($arrayCampanasCumple as $keyCampana => $valueCampana) {
                    if ($arrayBonoCumple[$keyCampana] == null) {
                        continue;
                    }
                    $bannerInv = [];
                    $depositPopup = array();
                    $dropedJackpotData = array();
                    $bonusesDatanew = array();

                    $rulesM = array();
                    array_push($rulesM, array("field" => "usuario_mensaje.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "in"));
                    array_push($rulesM, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                    array_push($rulesM, array("field" => "usuario_mensaje.fecha_crea", "data" => date('Y-m-d'), "op" => "cn"));
                    array_push($rulesM, array("field" => "usuario_mensaje.usumencampana_id", "data" => $valueCampana, "op" => "eq"));


                    $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                    $json2 = json_encode($filtroM);

                    $UsuarioMensaje = new UsuarioMensaje();
                    $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

                    $usuarios = json_decode($usuarios);


                    if (intval($usuarios->count[0]->{".count"}) == 0) {
                        $mensajeEnviado = false;
                    }


                    try {
                        if (!$mensajeEnviado) {

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = '';
                            $UsuarioMensaje->msubject = 'Campaña Cumpleaños';
                            $UsuarioMensaje->tipo = "MESSAGEINV";
                            $UsuarioMensaje->parentId = 151931894;
                            $UsuarioMensaje->proveedorId = $Usuario->mandante;
                            $UsuarioMensaje->setExternoId(0);
                            $UsuarioMensaje->usumencampanaId = $valueCampana;


                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                            $bonoId = $arrayBonoCumple[$keyCampana];

                            $UsuarioBono = new UsuarioBono();

                            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

                            $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                            $UsuarioBono->setUsuarioId(0);
                            $UsuarioBono->setBonoId($bonoId);
                            $UsuarioBono->setValor(0);
                            $UsuarioBono->setValorBono(0);
                            $UsuarioBono->setValorBase(0);
                            $UsuarioBono->setEstado("L");
                            $UsuarioBono->setErrorId(0);
                            $UsuarioBono->setIdExterno(0);
                            $UsuarioBono->setMandante($Usuario->mandante);
                            $UsuarioBono->setUsucreaId(0);
                            $UsuarioBono->setUsumodifId(0);
                            $UsuarioBono->setApostado(0);
                            $UsuarioBono->setRollowerRequerido(0);
                            $UsuarioBono->setCodigo("");
                            $UsuarioBono->setVersion(0);
                            $UsuarioBono->setExternoId(0);


                            //print_r($UsuarioBono);

                            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                            $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                            $transaccion5->commit();

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

                            $BonoInterno = new BonoInterno();
                            $BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();

                            $Transaction = $BonoInternoMySqlDAO->getTransaction();

                            $detalles = json_decode(json_encode($detalles));

                            $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction);
                            $Transaction->commit();

                        }

                    } catch (Exception $e) {

                    }
                }

            }
        }

        #Mensajes invasivos

        if (true) {


            //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA
            $currentDateTime = date('Y-m-d H:i:s');


            $rulesM = array();
            array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.usuto_id", "data" => "0", "op" => "in"));
            array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
            array_push($rulesM, array("field" => "usuario_mensaje2.usumensaje_id", "data" => "NULL", "op" => "isnull"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "MESSAGEINV", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));
            print_r($rulesM);


            $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
            $json2 = json_encode($filtroM);

            $UsuarioMensajecampana = new UsuarioMensajecampana();
            $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

            $usuarios = json_decode($usuarios);
            print_r($usuarios);

            foreach ($usuarios->data as $key => $value) {
                print_r($value);

                $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Image;
                $URL = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                $target = json_decode($value->{"usuario_mensajecampana.t_value"})->Target;

                $body = $value->{"usuario_mensajecampana.body"};
                $botonTexto = json_decode($value->{"usuario_mensajecampana.t_value"})->ButtonText;

                $array = [];
                array_push($array, $imagen);
                array_push($array, $botonTexto);
                array_push($array, $body);
                array_push($array, $URL);
                array_push($array, $target);
                array_push($array, '');
                array_push($array, "isMessage");
                array_push($array, $value->{"usuario_mensajecampana.parent_id"});

                $bannerInv = $array;


                if (false) {
                    $bannerInv = [];
                    $bannerInv['image'] = $array[0];
                    $bannerInv['buttonText'] = $array[1];
                    $bannerInv['body'] = $array[2];
                    $bannerInv['url'] = $array[3];
                    $bannerInv['target'] = $array[4];
                    $bannerInv['target2'] = $array[5];
                    $bannerInv['type'] = 'bannerInvasive';
                    $bannerInv['parentId'] = $value->{"usuario_mensajecampana.parent_id"};
                }

                /*                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                    $UsuarioMensaje->isRead = 1;
                                    $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                                    $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = 0;
                                    $UsuarioMensaje->setExternoId(0);




                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/


                if ($value->{"usuario_mensajecampana.usuto_id"} == '0') {
                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = $value->{"usuario_mensajecampana.body"};
                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);
                    $UsuarioMensaje->msubject = $value->{"usuario_mensajecampana.msubject"};
                    $UsuarioMensaje->tipo = "MESSAGEINV";
                    $UsuarioMensaje->parentId = $value->{"usuario_mensajecampana.usumensaje_id"};
                    $UsuarioMensaje->usumencampanaId = $value->{"usuario_mensajecampana.usumencampana_id"};
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->setExternoId(0);
                    $UsuarioMensaje->setUsucreaId(0);
                    $UsuarioMensaje->setUsumodifId(0);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                }

                $seguirBanner = false;

            }

            if (oldCount($bannerInv) == 0) {

                //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA para un USUARIO EN ESPECIFICO
                $currentDateTime = date('Y-m-d H:i:s');

                $rulesM = array();
                array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
                array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensaje2.is_read", "data" => "0", "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));


                $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                $json2 = json_encode($filtroM);


                $UsuarioMensajecampana = new UsuarioMensajecampana();
                $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", $SkeepRows, $MaxRows, $json2, true, $UsuarioMandante->getUsumandanteId());

                $usuarios = json_decode($usuarios);

                foreach ($usuarios->data as $key => $value) {

                    $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Image;
                    if ($imagen == "") {
                        $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Imagen;

                    }
                    $URL = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                    $target = json_decode($value->{"usuario_mensajecampana.t_value"})->Target;


                    $body = $value->{"usuario_mensajecampana.body"};
                    $nombre = $value->{"usuario_mensajecampana.nombre"};

                    /**
                     * Propósito: devolver las notificaciones
                     * $Url: trae la url que se envia de pop ups
                     */


                    if ($nombre == "JACKPOT POPUP") {
                        $URL = $value->{"usuario_mensajecampana.t_value"};
                    }

                    $botonTexto = json_decode($value->{"usuario_mensajecampana.t_value"})->ButtonText;

                    $array = [];
                    array_push($array, $imagen);
                    array_push($array, $botonTexto);
                    array_push($array, $body);
                    array_push($array, $URL);
                    array_push($array, $target);
                    array_push($array, '');
                    array_push($array, "isMessage");
                    array_push($array, $value->{"usuario_mensajecampana.parent_id"});

                    $bannerInv = $array;

                    if (false) {
                        $bannerInv = [];
                        $bannerInv['image'] = $array[0];
                        $bannerInv['buttonText'] = $array[1];
                        $bannerInv['body'] = $array[2];
                        $bannerInv['url'] = $array[3];
                        $bannerInv['target'] = $array[4];
                        $bannerInv['target2'] = $array[5];
                        $bannerInv['type'] = 'bannerInvasive';
                        $bannerInv['parentId'] = $value->{"usuario_mensajecampana.parent_id"};
                    }
                    $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje2.usumensaje_id"});
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $seguirBanner = false;

                }
            }

            if (oldCount($bannerInv) == 0) {
                //MESSAGEINV Mensajes invasivos para todos CON CAMPAÑA para un USUARIO EN ESPECIFICO
                $currentDateTime = date('Y-m-d H:i:s');

                $rulesM = array();
                array_push($rulesM, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                array_push($rulesM, array("field" => "usuario_mensaje.usuto_id", "data" => $UsuarioMandante->getUsumandanteId() . "", "op" => "in"));
                array_push($rulesM, array("field" => "usuario_mensaje.tipo", "data" => "MESSAGEINV", "op" => "eq"));
                array_push($rulesM, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                array_push($rulesM, array("field" => "usuario_mensaje.fecha_expiracion", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
                array_push($rulesM, array("field" => "usuario_mensaje.is_read", "data" => "0", "op" => "eq"));


                $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
                $json2 = json_encode($filtroM);

                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true, $UsuarioMandante->getUsumandanteId());

                $usuarios = json_decode($usuarios);

                foreach ($usuarios->data as $key => $value) {


                    $URL = $value->{"usuario_mensaje.msubject"};
                    $target = '';

                    $body = $value->{"usuario_mensaje.body"};

                    $array = [];
                    array_push($array, $imagen);
                    array_push($array, $botonTexto);
                    array_push($array, $body);
                    array_push($array, $URL);
                    array_push($array, $target);
                    array_push($array, '');
                    array_push($array, "isMessage");
                    array_push($array, $value->{"usuario_mensaje.parent_id"});

                    $bannerInv = $array;

                    if (false) {
                        $bannerInv = [];
                        $bannerInv['image'] = $array[0];
                        $bannerInv['buttonText'] = $array[1];
                        $bannerInv['body'] = $array[2];
                        $bannerInv['url'] = $array[3];
                        $bannerInv['target'] = $array[4];
                        $bannerInv['target2'] = $array[5];
                        $bannerInv['type'] = 'bannerInvasive';
                        $bannerInv['parentId'] = $value->{"usuario_mensaje.parent_id"};
                    }
                    $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $seguirBanner = false;

                }
            }
        }

        #Verificaciones de cuenta
        if (true) {
            try {


                if (($Usuario->mandante == 8 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-08 00:00:00'))) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 0 && $Usuario->paisId == 2) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 23) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 0 && $Usuario->paisId == 46) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 0 && $Usuario->paisId == 66) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 0 && $Usuario->paisId == 94) && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 14 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-27 08:00:00'))) && $Usuario->verifCorreo == "N" && false) {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if (($Usuario->mandante == 17 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-03-27 08:00:00'))) && $Usuario->verifCorreo == "N") {
                    //$ConfigurationEnvironment = new ConfigurationEnvironment();
                    //$ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if ($Usuario->usuarioId == 85 && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
                if ($Usuario->usuarioId == 1508705 && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }

                if ($Usuario->usuarioId == 1243479 && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }

                if ($Usuario->mandante == 8 && $Usuario->verifCorreo == "N") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
                }
            } catch (Exception $e) {
            }
        }

        #BANNER INVASIVO CON MASCOTA

        if (true) {

            // BANNERINV Banner invasivo - Mascota para usuario en especifico


            $rulesM = array();
            array_push($rulesM, array("field" => "usuario_mensajecampana.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.usuto_id", "data" => "0", "op" => "in"));
            array_push($rulesM, array("field" => "usuario_mensaje2.usumensaje_id", "data" => "NULL", "op" => "isnull"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.pais_id", "data" => $UsuarioMandante->paisId . ",0", "op" => "in"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.tipo", "data" => "BANNERINV", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.is_read", "data" => "0", "op" => "eq"));
            array_push($rulesM, array("field" => "usuario_mensajecampana.estado", "data" => "A", "op" => "eq"));


            $filtroM = array("rules" => $rulesM, "groupOp" => "AND");
            $json2 = json_encode($filtroM);

            $UsuarioMensajecampana = new UsuarioMensajecampana();
            $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" * ", "usuario_mensajecampana.usumencampana_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());

            $usuarios = json_decode($usuarios);

            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["title"] = $value->{"usuario_mensaje.body"};
                $array["url"] = $value->{"usuario_mensaje.msubject"};
                /* $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                 $array["open"] = false;
                 $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
                 $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
                 $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};*/

                /*$UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                $UsuarioMensaje->setIsRead(1);

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/

                $urlFrame = explode('##URL##', $value->{"usuario_mensaje.msubject"})[0];
                $url = explode('##URL##', $value->{"usuario_mensaje.msubject"})[1];

                $seguirProducto = true;
                $proveedorReq = 0;

                if (strpos($urlFrame, 'GAME') !== false) {

                    $gameid = explode('GAME', $urlFrame)[1];

                    if (is_numeric($gameid)) {
                        try {
                            $Producto = new Producto($gameid);
                            $ProductoMandante = new ProductoMandante($gameid, $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);
                            $Proveedor = new Proveedor($Producto->getProveedorId());
                            $urlFrame = 'https://casino.virtualsoft.tech/game/play/?gameid=' . $ProductoMandante->prodmandanteId . '&mode=real&provider=' . $Proveedor->getAbreviado() . '&lan=es&mode=real&partnerid=' . $UsuarioMandante->getMandante();
                            $url = $Mandante->baseUrl . '/' . 'casino' . '/' . $ProductoMandante->prodmandanteId;
                            $proveedorReq = $Proveedor->getProveedorId();
                        } catch (Exception $e) {
                            $seguirProducto = false;

                        }
                    }
                } else {
                    $proveedorReq = $value->{"usuario_mensaje.proveedor_id"};
                }

                if ($seguirProducto) {
                    if ($proveedorReq != 0 && $proveedorReq != '') {

                        $token = '';


                        try {

                            //$UsuarioToken = new UsuarioToken("", $proveedorReq, $UsuarioMandante->getUsumandanteId());
                            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $token = $UsuarioToken->getToken();
                        } catch (Exception $e) {

                            if ($e->getCode() == "21" && false) {

                                $UsuarioToken = new UsuarioToken();

                                $UsuarioToken->setRequestId('');
                                $UsuarioToken->setProveedorId($proveedorReq);
                                $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                                $UsuarioToken->setToken($UsuarioToken->createToken());
                                //$UsuarioToken->setCookie(encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
                                $UsuarioToken->setUsumodifId(0);
                                $UsuarioToken->setUsucreaId(0);
                                $UsuarioToken->setSaldo(0);

                                $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                                $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                                $UsuarioTokenMySqlDAO->getTransaction()->commit();
                                $token = $UsuarioToken->getToken();


                            }
                        }
                        $urlFrame = $urlFrame . '&token=' . $token;
                        // $url = $url . '&token='.$token;

                    }

                    //'https://demogamesfree.pragmaticplay.net/gs2c/openGame.do?lang=en&cur=USD&gameSymbol=vs40beowulf&lobbyURL=https://doradobet.com/new-casino'
                    $array = [];

                    if ($Usuario->mandante == 18) {
                        array_push($array, 'https://images.virtualsoft.tech/m/msjT1683652624.png');

                    } else {
                        array_push($array, 'https://images.virtualsoft.tech/site/doradobet/pet/pet-doradobet.png');

                    }
                    array_push($array, 'Hola');
                    array_push($array, $value->{"usuario_mensaje.body"});
                    array_push($array, $url);
                    array_push($array, '_self');
                    array_push($array, $urlFrame);
                    array_push($array, "isInvasive");

                    // $array["title"] = $value->{"usuario_mensaje.body"};
                    // $array["url"] = $value->{"usuario_mensaje.msubject"};

                    $bannerInv = $array;

                    if ($value->{"usuario_mensaje.usuto_id"} == '0') {
                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                        $UsuarioMensaje->isRead = 1;
                        $UsuarioMensaje->body = str_replace("'", '"', $value->{"usuario_mensaje.body"});
                        $UsuarioMensaje->msubject = $value->{"usuario_mensaje.msubject"};
                        $UsuarioMensaje->tipo = "BANNERINV";
                        $UsuarioMensaje->parentId = $value->{"usuario_mensaje.usumensaje_id"};
                        $UsuarioMensaje->proveedorId = 0;
                        $UsuarioMensaje->setExternoId(0);
                        $UsuarioMensaje->setUsucreaId(0);
                        $UsuarioMensaje->setUsumodifId(0);


                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    } else {
                        $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                        $UsuarioMensaje->isRead = 1;
                        $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    }
                }

            }


        }


        #POPUPS DE DEPOSITAR PARA LOS USUARIOS QUE TIENEN REGALOS
        if (true) {
            $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro_type_gift","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

            $SitioTracking = new SitioTracking();
            $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
            $sitiosTracking = json_decode($sitiosTracking);

            $type_gift = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

            if ($type_gift != '' && $type_gift != 'no_quiero_regalos') {
                $Registro = new Registro('', $Usuario->usuarioId);

                $arrayimgPromotion = array(
                    '0_173' => "https://images.virtualsoft.tech/m/msj0212T1712692456.png", // Doradobet, paisId == '173' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '0_173_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557765.png", // Doradobet, saldo bajo
                    '0_94' => "https://images.virtualsoft.tech/m/msj0212T1714577075.png", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "https://images.virtualsoft.tech/m/msj212T1708556935.png", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557733.png", // Ecuabet, saldo bajo
                    '23_N' => "https://images.virtualsoft.tech/m/msj0212T1714507606.png" // Paniplay, verifcedulaAnt == 'N'
                );

                $arrayimgDecoration = array(
                    '0_173' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '173'
                    '0_173_saldo' => "https://images.virtualsoft.tech/m/msj212T1708556742.png", // Doradobet, saldo bajo
                    '0_94' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '94'
                    '8_66' => "https://images.virtualsoft.tech/m/msj212T1708556953.png", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66_saldo' => "https://images.virtualsoft.tech/m/msj0212T1708557116.png", // Ecuabet, saldo bajo
                    '23_102' => "https://images.virtualsoft.tech/m/msj0212T1708557280.png" // Paniplay, verifcedulaAnt == 'N'
                );

                $arraytextButton = array(
                    '0_173' => "¡Deposita Ya!", // Doradobet, paisId == '173' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '0_173_saldo' => "¡Deposita Ya!", // Doradobet, saldo bajo
                    '0_94' => "¡Deposita Ya!", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "¡Deposita Ya!", // Ecuabet, verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66_saldo' => "¡Deposita Ya!", // Ecuabet, saldo bajo
                    '23_102' => "¡Ingresa Ya!" // Paniplay, verifcedulaAnt == 'N'
                );

                $arrayurlButton = array(
                    '0_173' => "/gestion/deposito", // Doradobet, saldo bajo
                    '0_94' => "/gestion/deposito", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "/gestion/deposito", // Ecuabet, saldo bajo
                    '23_102' => "/gestion/verificar_cuenta" // Paniplay, verifcedulaAnt == 'N'
                );
                $arrayimgPromotionSaldo = array(
                    '0_173' => "https://images.virtualsoft.tech/m/msj0212T1708557765.png", // Doradobet, saldo bajo
                    '0_94' => "https://images.virtualsoft.tech/m/msj0212T1714577075.png", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "https://images.virtualsoft.tech/m/msj0212T1708557733.png", // Ecuabet, saldo bajo
                    '23_N' => "https://images.virtualsoft.tech/m/msj0212T1714507606.png" // Paniplay, verifcedulaAnt == 'N'
                );

                $arrayimgDecorationSaldo = array(
                    '0_173' => "https://images.virtualsoft.tech/m/msj212T1708556742.png", // Doradobet, saldo bajo
                    '0_94' => "https://images.virtualsoft.tech/m/msj212T1708556372.png", // Doradobet, paisId == '94'
                    '8_66' => "https://images.virtualsoft.tech/m/msj0212T1708557116.png", // Ecuabet, saldo bajo
                    '23_102' => "https://images.virtualsoft.tech/m/msj0212T1708557280.png" // Paniplay, verifcedulaAnt == 'N'
                );

                $arraytextButtonSaldo = array(
                    '0_173' => "¡Deposita Ya!", // Doradobet, saldo bajo
                    '0_94' => "¡Deposita Ya!", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "¡Deposita Ya!", // Ecuabet, saldo bajo
                    '23_102' => "¡Ingresa Ya!" // Paniplay, verifcedulaAnt == 'N'
                );

                $arrayurlButtonSaldo = array(
                    '0_173' => "/gestion/deposito", // Doradobet, saldo bajo
                    '0_94' => "/gestion/deposito", // Doradobet, paisId == '94' y verifcedulaAnt == 'S' && verifcedulaPost == 'S' && fechaPrimerdeposito == ''
                    '8_66' => "/gestion/deposito", // Ecuabet, saldo bajo
                    '23_102' => "/gestion/verificar_cuenta" // Paniplay, verifcedulaAnt == 'N'
                );
                if ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S' && $Usuario->fechaPrimerdeposito == '') {
                    array_push($depositPopup,
                        array(
                            "imgPromotion" => $arrayimgPromotion[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "imgDecoration" => $arrayimgDecoration[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "textButton" => $arraytextButton[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "urlButton" => $arrayurlButton[$Usuario->mandante . '_' . $Usuario->paisId]
                        ));
                } elseif ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S' && $Usuario->fechaPrimerdeposito != '' &&
                    round((floatval(($Registro->getCreditosBase() * 100)) + floatval(($Registro->getCreditos() * 100))) / 100, 2) < 0.5) {
                    array_push($depositPopup,
                        array(
                            "imgPromotion" => $arrayimgPromotionSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "imgDecoration" => $arrayimgDecorationSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "textButton" => $arraytextButtonSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                        , "urlButton" => $arrayurlButtonSaldo[$Usuario->mandante . '_' . $Usuario->paisId]
                        ));
                }


            }
        }


        /** Verificación caídas de Jackpots */
        if (true) {
            $rules = [];
            array_push($rules, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje2.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "JACKPOTWINNER", "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => 0, "op" => "cn"));
            $filtroM = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtroM);

            $UsuarioMensaje = new UsuarioMensaje();
            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->getUsumandanteId());
            $usuarios = json_decode($usuarios)->data;

            $messagesToDesactivate = [];
            foreach ($usuarios as $jackpotWinnerMessage) {
                try {
                    $JackpotInterno = new JackpotInterno($jackpotWinnerMessage->{'usuario_mensaje.body'});
                } catch (Exception $e) {
                    break;
                }

                $dropedJackpotData = [[
                    'uid' => $jackpotWinnerMessage->{'usuario_mensaje.usumensaje_id'},
                    'id' => $JackpotInterno->jackpotId,
                    'videoMobile' => $JackpotInterno->videoMobile,
                    'video' => $JackpotInterno->videoDesktop,
                    'gif' => $JackpotInterno->gif,
                    'imagen' => $JackpotInterno->imagen,
                    'imagen2' => $JackpotInterno->imagen2,
                    'monto' => round($JackpotInterno->valorActual, 2)
                ]];
                $messagesToDesactivate[] = $jackpotWinnerMessage->{'usuario_mensaje.usumensaje_id'};
            }

            if (count($messagesToDesactivate) > 0) {
                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->updateReadForID(implode(',', $messagesToDesactivate));
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
            }
        }

        if (false) {
            /*
                            // ----- NOTIFICACIONES PUSH -----
                            $currentDateTime = date('Y-m-d H:i:s');
                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => '-1', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.usufrom_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.usuto_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => $UsuarioMandante->getPaisId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $massiveForCountry = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $massiveForCountry = json_decode($massiveForCountry, true);

                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => '-1', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.usufrom_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.usuto_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                            $massiveForNotCountry = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $massiveForNotCountry = json_decode($massiveForNotCountry, true);

                            $allMassiveMessages = array_merge($massiveForCountry['data'], $massiveForNotCountry['data']);

                            $rules = [];

                            array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => $UsuarioMandante->getUsumandanteId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.is_read', 'data' => '0', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'PUSHNOTIFICACION', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensaje.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $currentDateTime, 'op' => 'le']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => $currentDateTime, 'op' => 'ge']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => 'A', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => $UsuarioMandante->getPaisId(), 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $UsuarioMandante->getMandante(), 'op' => 'eq']);

                            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                            $userMessages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usuario_mensajecampana.t_value, usuario_mensajecampana.usumencampana_id', 'usuario_mensaje.usumensaje_id', 'asc', 0, 1000, $filters, true);

                            $userMessages = json_decode($userMessages, true);

                            $userMessages['data'] = array_merge($allMassiveMessages, $userMessages['data']);

                            $assignedMessages = [];

                            if (oldCount($allMassiveMessages) > 0) {
                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $ids = array_map(function ($value) {
                                    return $value['usuario_mensaje.usumensaje_id'];
                                }, $allMassiveMessages);
                                $query = $UsuarioMensajeMySqlDAO->queryByParent(implode(',', $ids), $UsuarioMandante->getUsumandanteId(), $currentDateTime);

                                $assignedMessages = array_unique(array_map(function ($value) {
                                    return $value['parent_id'];
                                }, $query));
                            }


                            $pushMessages = [];
                            $updatedMessages = '';

                            foreach ($userMessages['data'] as $key => $value) {
                                $data = [];

                                if ($value['usuario_mensaje.usuto_id'] != $UsuarioMandante->getUsumandanteId() && in_array($value['usuario_mensaje.usumensaje_id'], $assignedMessages) == false) {
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->setUsufromId(0);
                                    $UsuarioMensaje->setUsutoId($UsuarioMandante->getUsumandanteId());
                                    $UsuarioMensaje->setIsRead(1);
                                    $UsuarioMensaje->setMsubject($value['usuario_mensaje.msubject']);
                                    $UsuarioMensaje->setBody($value['usuario_mensaje.body']);
                                    $UsuarioMensaje->setParentId($value['usuario_mensaje.usumensaje_id']);
                                    $UsuarioMensaje->setUsucreaId($value['usuario_mensaje.usucrea_id']);
                                    $UsuarioMensaje->setUsumodifId(0);
                                    $UsuarioMensaje->setTipo($value['usuario_mensaje.tipo']);
                                    $UsuarioMensaje->setExternoId(0);
                                    $UsuarioMensaje->setProveedorId(0);
                                    $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                                    $UsuarioMensaje->setFechaExpiracion($value['usuario_mensaje.fecha_expiracion']);
                                    $UsuarioMensaje->setUsumencampanaId($value['usuario_mensajecampana.usumencampana_id']);

                                    $UsuarioMensaje->setValor1(0);
                                    $UsuarioMensaje->setValor2(0);
                                    $UsuarioMensaje->setValor3(0);

                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                    $messageID = $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                                    $value['usuario_mensaje.usuto_id'] = $UsuarioMandante->getUsumandanteId();
                                    $value['usuario_mensaje.usumensaje_id'] = $messageID;
                                }

                                if ($value['usuario_mensaje.is_read'] == 0 && !in_array($value['usuario_mensaje.usuto_id'], [-1, 0])) {
                                    $tval = json_decode($value['usuario_mensajecampana.t_value'], true);
                                    $link = "<a href=\"#\" style=\"text-decoration: none; font-size: 1.2em; color: black;\">{$value['usuario_mensaje.body']}</a>";


                                    $data['id'] = $value['usuario_mensaje.usumensaje_id'];
                                    $data['body'] = '<div>' . $link . '</div>';
                                    $updatedMessages .= $value['usuario_mensaje.usumensaje_id'] . ',';

                                    array_push($pushMessages, $data);
                                }
                            }

                            if (!empty($updatedMessages)) {
                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $UsuarioMensajeMySqlDAO->updateReadForID(trim($updatedMessages, ','));
                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                            }


                            $response['data']['messages'] = array_merge($pushMessages, $response['data']['messages']);*/

        }

        ##Franja de Bonos
        if ($Usuario->mandante == 17 || $Usuario->mandante == 19) {


            $MaxRows = 100;
            $OrderedItem = 1;
            $SkeepRows = 0;
            $rules = [];
            array_push($rules, array("field" => "bono_interno.tipo", "data" => '5,6,2,3', "op" => "in"));
            array_push($rules, array("field" => "bono_interno.estado", "data" => 'A', "op" => "eq"));
            array_push($rules, array("field" => "bono_interno.mandante", "data" => $Usuario->mandante, "op" => "eq"));
            array_push($rules, array("field" => "usuario_bono.estado", "data" => 'Q', "op" => "eq"));
            array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);


            $UsuarioBono = new UsuarioBono();


            $UsuarioBonos = $UsuarioBono->getUsuarioBonosCustom(" usuario_bono.*,bono_interno.* ", "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json2, true);


            $bonos = json_decode($UsuarioBonos);


            if ($bonos->count[0]->{".count"} != "0") {
                $bonusesDatanew = array();
                foreach ($bonos->data as $key => $value) {
                    $array = [];
                    $array["bonoId"] = $value->{"usuario_bono.bono_id"};
                    $array["descripcion"] = $value->{"bono_interno.descripcion"};
                    $array["usubonoId"] = $value->{"usuario_bono.usubono_id"};
                    $array["valor"] = $value->{"usuario_bono.valor_bono"};
                    $array["description"] = $value->{"bono_interno.descripcion"};
                    $array["detailId"] = $value->{"usuario_bono.usubono_id"};
                    $array["value"] = $value->{"usuario_bono.valor_bono"};
                    $array["image"] = $value->{"bono_interno.imagen"};

                    array_push($bonusesDatanew, $array);
                }
            }

        }


        ##ENVIO DE MENSAJE PARA USUARIO UNICO

        if (oldCount($depositPopup) > 0) {
            $dataSend = array(
                "depositPopup" => $depositPopup,

            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

        }

        if (oldCount($bannerInv) > 0) {
            $dataSend = array(
                "bannerInv" => $bannerInv
            );
            print_r($dataSend);
            print_r($UsuarioMandante);
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

        }
        if (oldCount($dropedJackpotData) > 0) {
            $dataSend = array(
                "dropedJackpotData" => $dropedJackpotData
            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

        }
        if (oldCount($bonusesDatanew) > 0) {
            $dataSend = array(
                "bonuses" => $bonusesDatanew
            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);

        }


    }

} catch (Exception $e) {
    print_r($e);
}


exit();
try {

    /*
     *
     * $TypeGroup:'',Partner,PartnerCountry
     * $TypeValue: COUNT,SUM
     * $TypeTotalDay: 1,''
     */
    function sendData($Type, $TypeGroup, $TypeValue, $TypeTotalDay)
    {
        $TypeDate = 'h';
        $redisParam = ['ex' => 18000000];

        $NameReportTotal = ($TypeTotalDay == '1' ? 'XDIA-' : '') . $TypeValue . '-' . $Type . '-' . $TypeGroup;
        $NameReportTotalDATA = ($TypeTotalDay == '1' ? 'XDIA-' : '') . $TypeValue . '-' . $Type;
        $redisPrefix = "GetReportTotalV2+UID" . $NameReportTotal;
        print_r(PHP_EOL);
        print_r($redisPrefix);
        print_r(PHP_EOL);

        $redis = RedisConnectionTrait::getRedisInstance(true);

        $color = 'green';
        $TypeSplit = '900';

        $dateFrom = date("Y-m-d 00:00:00", strtotime('-1 hour '));
        $dateTo = date("Y-m-d H:00:00");

        $updateKey = true;
        if ($TypeDate == 'h') {

            if ($redis != null) {

                $cachedValue = ($redis->get($redisPrefix));
                if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
                    $dateFrom = explode('###', $cachedValue)[0];
                    $dateTo = explode('###', $cachedValue)[1];

                    $dateFrom2 = date("Y-m-d H:i:00", strtotime($dateTo));
                    if (date("i") < 15) {
                        $dateTo2 = date("Y-m-d H:00:00");

                    } elseif (date("i") < 30) {
                        $dateTo2 = date("Y-m-d H:15:00");

                    } elseif (date("i") < 45) {
                        $dateTo2 = date("Y-m-d H:30:00");

                    } else {
                        $dateTo2 = date("Y-m-d H:45:00");

                    }

                    if ($dateTo2 < date("Y-m-d H:i:00", strtotime('-5 minutes '))) {
                        $dateFrom = $dateFrom2;
                        $dateTo = $dateTo2;

                    } else {
                        $updateKey = false;
                    }
                } else {
                    $dateFrom = date("Y-m-d 00:00:00");
                    $dateTo = date("Y-m-d H:15:00");

                }

            } else {
                $dateFrom = date("Y-m-d H:00:00", strtotime('-1 hour '));
                $dateTo = date("Y-m-d H:00:00");

            }
        } else {
            $cachedValue = ($redis->get($redisPrefix));
            if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
                $dateFrom = explode('###', $cachedValue)[0];
                $dateTo = explode('###', $cachedValue)[1];

                $dateFrom2 = date("Y-m-d H:i:00", strtotime($dateTo));
                if (date("i") < 15) {
                    $dateTo2 = date("Y-m-d H:00:00");

                } elseif (date("i") < 30) {
                    $dateTo2 = date("Y-m-d H:15:00");

                } elseif (date("i") < 45) {
                    $dateTo2 = date("Y-m-d H:30:00");

                } else {
                    $dateTo2 = date("Y-m-d H:45:00");

                }

                if ($dateTo2 < date("Y-m-d H:i:00", strtotime('-5 minutes '))) {
                    $updateKey = false;
                    $dateFrom = $dateFrom2;
                    $dateTo = $dateTo2;

                }
            } else {
                $dateFrom = date("Y-m-d H:00:00");
                $dateTo = date("Y-m-d H:15:00");

            }
        }

        if (date("Y-m-d H:i:s") < '2025-02-03 12:45:00' && ($Type == 'CantTotalAmountBetsCasino')) {
            if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
                $dateFrom = explode('###', $cachedValue)[0];
                $dateTo = explode('###', $cachedValue)[1];

                if (strpos($dateTo, '02-02') !== false) {
                    $dateFrom = '2025-02-02 00:00:00';
                    $dateTo = '2025-02-03 00:00:00';
                } elseif (strpos($dateTo, '02-03') !== false) {
                    $dateFrom = '2025-02-03 00:00:00';
                    $dateTo = date("Y-m-d H:15:00");
                } else {
                    $dateFrom = '2025-02-01 00:00:00';
                    $dateTo = '2025-02-02 00:00:00';

                }

                $TypeSplit = '86400';
            }

        }
        if (date("Y-m-d H:i:s") < '2025-02-03 12:45:00' && ($Type == 'CantTotalAmountWinsCasino')) {
            if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
                $dateFrom = explode('###', $cachedValue)[0];
                $dateTo = explode('###', $cachedValue)[1];

                if (strpos($dateTo, '02-02') !== false) {
                    $dateFrom = '2025-02-02 00:00:00';
                    $dateTo = '2025-02-03 00:00:00';
                } elseif (strpos($dateTo, '02-03') !== false) {
                    $dateFrom = '2025-02-03 00:00:00';
                    $dateTo = date("Y-m-d H:15:00");
                } else {
                    $dateFrom = '2025-02-01 00:00:00';
                    $dateTo = '2025-02-02 00:00:00';

                }

                $TypeSplit = '86400';
            }

        }

        if (date("Y-m-d H:i:s") < '2025-02-03 11:45:00' && ($Type == 'TotalRegisters' || $Type == 'Registers')) {
            if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
                $dateFrom = explode('###', $cachedValue)[0];
                $dateTo = explode('###', $cachedValue)[1];

                if (strpos($dateTo, '02-02') !== false) {
                    $dateFrom = '2025-02-02 00:00:00';
                    $dateTo = '2025-02-03 00:00:00';
                } elseif (strpos($dateTo, '02-03') !== false) {
                    $dateFrom = '2025-02-03 00:00:00';
                    $dateTo = date("Y-m-d H:15:00");
                } else {
                    $dateFrom = '2025-02-01 00:00:00';
                    $dateTo = '2025-02-02 00:00:00';

                }

                $TypeSplit = '86400';
            }

        }
        if (date("Y-m-d H:i:s") < '2025-02-03 11:45:00' && ($Type == 'GGRSportCurrency')) {
            if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
                $dateFrom = explode('###', $cachedValue)[0];
                $dateTo = explode('###', $cachedValue)[1];

                if (strpos($dateTo, '02-02') !== false) {
                    $dateFrom = '2025-02-02 00:00:00';
                    $dateTo = '2025-02-03 00:00:00';
                } elseif (strpos($dateTo, '02-03') !== false) {
                    $dateFrom = '2025-02-03 00:00:00';
                    $dateTo = date("Y-m-d H:15:00");
                } else {
                    $dateFrom = '2025-02-01 00:00:00';
                    $dateTo = '2025-02-02 00:00:00';

                }

                $TypeSplit = '86400';
            }

        }
        if (date("Y-m-d H:i:s") < '2025-02-03 13:45:00' && ($Type == 'GGRCasinoCurrency')) {
            if ($cachedValue != null && strpos($cachedValue, '###') !== false) {
                $dateFrom = explode('###', $cachedValue)[0];
                $dateTo = explode('###', $cachedValue)[1];

                if (strpos($dateTo, '02-02') !== false) {
                    $dateFrom = '2025-02-02 00:00:00';
                    $dateTo = '2025-02-03 00:00:00';
                } elseif (strpos($dateTo, '02-03') !== false) {
                    $dateFrom = '2025-02-03 00:00:00';
                    $dateTo = date("Y-m-d H:15:00");
                } else {
                    $dateFrom = '2025-02-01 00:00:00';
                    $dateTo = '2025-02-02 00:00:00';

                }

                $TypeSplit = '86400';
            }

        }
        if ($redis != null) {
            if ($_REQUEST['wset'] != '1' && $updateKey) {
                $redis->set($redisPrefix, $dateFrom . '###' . $dateTo, $redisParam);

            }
        } else {

        }
        $dateFrom = '2025-03-11 00:00:00';
        $dateTo = '2025-03-12 13:00:00';

        $arrayRedis = array(
            "TotalUserBetsSports",
            "TotalUserBetsSportsFREEBET",
            "TotalUserBetsSportsPartnerCountry",
            "TotalUserBetsSportsPartnerCountryFREEBET",
            "TotalUserBetsCasinoNORMAL",
            "TotalUserBetsCasinoFREESPIN",
            "TotalUserBetsCasinoFREECASH",
            "TotalUserBetsCasinoPartnerCountryNORMAL",
            "TotalUserBetsCasinoPartnerCountryFREESPIN",
            "TotalUserBetsCasinoPartnerCountryFREECASH",
            "TotalUserPings",
            "TotalUserPingsPartner",
            "TotalUserPingsPartnerCountry"

        );
        if (!in_array($Type, $arrayRedis)) {

            $sqlWhere = '';

            $name = "UPPER('General')";
            $TypeG = 'Counter';
            if ($TypeGroup == 'PartnerCountry') {
                $TypeG = 'ListFunel';

                $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))";
                $value = "count(*)";
                $groupby = "group by mandante.mandante,pais.pais_id";
            }
            if ($TypeGroup == 'Partner') {
                $TypeG = 'ListFunel';

                $name = "UPPER(mandante.descripcion)";
                $value = "count(*)";
                $groupby = "group by mandante.mandante";
            }
            switch ($Type) {
                case "Registers":
                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario.fecha_crea";
                    $byMoneda = '';

                    $sql = "
                    FROM registro
                    inner join usuario on usuario.usuario_id = registro.usuario_id
                    inner join pais on usuario.pais_id = pais.pais_id
                    inner join mandante on usuario.mandante = mandante.mandante
                    left outer join usuario_link on usuario_link.usulink_id = registro.link_id";

                    break;

                case "FirstDeposits":
                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "ur.fecha_crea";
                    $byMoneda = '';
                    $sqlWhere .= " AND ur2.usuario_id IS NULL ";

                    $sql = "
                    FROM usuario u
         JOIN pais pais ON u.pais_id = pais.pais_id
         JOIN mandante  ON u.mandante = mandante.mandante
         INNER JOIN usuario_recarga ur ON ur.usuario_id = u.usuario_id
         LEFT JOIN usuario_recarga ur2 ON u.usuario_id = ur2.usuario_id AND ur2.fecha_crea < ur.fecha_crea
                    
                    ";

                    break;
                case "FirstDepositsAmount":
                    $valueSUM = "ur.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "ur.fecha_crea";
                    $byMoneda = 'u.moneda';

                    $sql = "
                    FROM usuario u
         JOIN pais pais ON u.pais_id = pais.pais_id
         JOIN mandante  ON u.mandante = mandante.mandante
         INNER JOIN usuario_recarga ur ON ur.usuario_id = u.usuario_id
         LEFT JOIN usuario_recarga ur2 ON u.usuario_id = ur2.usuario_id AND ur2.fecha_crea < ur.fecha_crea
                    
                    ";

                    break;


                case "Deposits":
                    $valueSUM = "usuario_recarga.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_recarga.fecha_crea";
                    $sqlWhere .= " AND usuario_recarga.estado = 'A' ";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM usuario_recarga
         inner join usuario on usuario.usuario_id = usuario_recarga.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;


                case "WithdrawsOTP":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "";
                    $sqlWhere .= " AND cuenta_cobro.estado ='O' ";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;

                case "WithdrawsPaid":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "cuenta_cobro.fecha_pago";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;

                case "WithdrawsAction":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "cuenta_cobro.fecha_accion";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "WithdrawsDelete":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "cuenta_cobro.fecha_accion";
                    $byMoneda = 'usuario.moneda';
                    $sqlWhere .= " AND cuenta_cobro.estado ='E' ";

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;

                case "WithdrawsCreated":

                    $valueSUM = "cuenta_cobro.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "cuenta_cobro.fecha_crea";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;

                case "LogCronBONOTOTAL":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "usuario_bono.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_bono.fecha_crea";

                    $sql = "
                    FROM usuario_bono
                        INNER JOIN log_cron on (log_cron.valor_id2 = usuario_bono.bono_id and log_cron.estado = 'TOTAL' and
                                 log_cron.tipo = 'agregarBonoBackground')

";
                    $TypeGroup = 'ValorId1';

                    $groupby = ' GROUP BY valor_id1,valor_id2';
                    break;
                case "LogCronBONOENVIADO":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "log_cron.valor1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                    $sql = "
                    FROM log_cron

";
                    $TypeGroup = 'ValorId1';

                    $groupby = ' GROUP BY valor_id1,valor_id2';
                    break;


                case "LogCronID1TOTAL":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                    $sql = "
                    FROM log_cron
";
                    $TypeGroup = 'ValorId1';

                    $groupby = ' GROUP BY valor_id1';
                    break;

                case "LogCronID2TOTAL":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='TOTAL' ";

                    $sql = "
                    FROM log_cron
";
                    $TypeGroup = 'ValorId2';
                    $groupby = ' GROUP BY valor_id2';

                    break;

                case "LogCronID1OK":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='OK' ";

                    $sql = "
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    $TypeGroup = 'ValorId1';
                    $groupby = ' GROUP BY valor_id1';

                    break;

                case "LogCronID2OK":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='OK' ";

                    $sql = "
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    $TypeGroup = 'ValorId2';
                    $groupby = ' GROUP BY valor_id2';

                    break;
                case "LogCronID1ERROR":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='ERROR' ";

                    $sql = "
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    $TypeGroup = 'ValorId1';
                    $groupby = ' GROUP BY valor_id1';

                    break;

                case "LogCronID2ERROR":
                    $TypeG = 'ListFunel';
                    $name = "UPPER(CONCAT(log_cron.valor_id1,'-',log_cron.valor_id2))";

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "log_cron.fecha_crea";
                    $sqlWhere .= " AND log_cron.estado='ERROR' ";

                    $sql = "
                    FROM log_cron
         inner join usuario on usuario.usuario_id = log_cron.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    $TypeGroup = 'ValorId2';

                    $groupby = ' GROUP BY valor_id2';

                    break;

                case "Bonus":

                    $valueSUM = "bono_log.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "bono_log.fecha_crea";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= " AND bono_log.estado ='L' ";
                    $sql = "
                    FROM bono_log
         inner join usuario on usuario.usuario_id = bono_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "BonusTypeState":

                    $TypeG = 'ListFunel';
                    $valueSUM = "bono_log.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "bono_log.fecha_crea";
                    $groupby = "group by bono_log.tipo,bono_log.estado";
                    $byMoneda = 'usuario.moneda';

                    $name = "CONCAT(UPPER('General'),'-',bono_log.tipo,'-',bono_log.estado)";

                    if ($TypeGroup == 'PartnerCountry') {
                        $TypeG = 'ListFunel';

                        $name = "CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom),'-',bono_log.tipo,'-',bono_log.estado)";
                        $groupby = "group by mandante.mandante,pais.pais_id,bono_log.tipo,bono_log.estado";
                    }
                    if ($TypeGroup == 'Partner') {
                        $TypeG = 'ListFunel';

                        $name = "CONCAT(UPPER(mandante.descripcion),'-',bono_log.tipo,'-',bono_log.estado)";
                        $groupby = "group by mandante.mandante,bono_log.tipo,bono_log.estado";
                    }

                    $sql = "
                    FROM bono_log
         inner join usuario on usuario.usuario_id = bono_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;


                case "UserBonusCreated":

                    $valueSUM = "usuario_bono.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_bono.fecha_modif";
                    $byMoneda = 'usuario.moneda';
                    $sql = "
                    FROM usuario_bono
         inner join usuario on usuario.usuario_id = usuario_bono.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;

                case "UserBonusRedimed":

                    $valueSUM = "usuario_bono.valor";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_bono.fecha_modif";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= " AND usuario_bono.estado ='R' ";
                    $sql = "
                    FROM usuario_bono
         inner join usuario on usuario.usuario_id = usuario_bono.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "Logins":

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_log.fecha_crea";

                    $sqlWhere .= " AND (usuario_log.tipo LIKE 'LOGIN%' AND usuario_log.tipo != 'LOGININCORRECTO') ";
                    $sql = "
                   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;


                case "LoginsError":

                    $valueSUM = "1";
                    $valueCOUNT = "*";
                    $groupbyDateField = "usuario_log.fecha_crea";

                    $sqlWhere .= " AND ( usuario_log.tipo = 'LOGININCORRECTO') ";
                    $sql = "
                   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;

                case "LoginsUnique":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(usuario_log.usuario_id)";
                    $groupbyDateField = "usuario_log.fecha_crea";

                    $sqlWhere .= " AND (usuario_log.tipo LIKE 'LOGIN%' AND usuario_log.tipo != 'LOGININCORRECTO') ";
                    $sql = "
                   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;


                case "LoginsErrorUnique":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(usuario_log.usuario_id)";
                    $groupbyDateField = "usuario_log.fecha_crea";

                    $sqlWhere .= " AND ( usuario_log.tipo = 'LOGININCORRECTO') ";
                    $sql = "
                   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";
                    break;

                case "BetsSport":

                    $valueSUM = "it_ticket_enc.vlr_apuesta";
                    $valueCOUNT = "*";
                    $groupbyDateField = "it_ticket_enc.fecha_crea_time";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "BetsSportDateClosed":

                    $valueSUM = "it_ticket_enc.vlr_apuesta";
                    $valueCOUNT = "*";
                    $groupbyDateField = "it_ticket_enc.fecha_cierre_time";
                    $byMoneda = 'usuario.moneda';

                    $sql = "
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;

                case "WinsSport":

                    $valueSUM = "it_ticket_enc.vlr_premio";
                    $valueCOUNT = "*";
                    $groupbyDateField = "it_ticket_enc.fecha_cierre_time";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= "  AND it_ticket_enc.eliminado = 'N' ";
                    $sql = "
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";

                    break;
                case "UserBetsSports":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(it_ticket_enc.usuario_id)";
                    $groupbyDateField = "it_ticket_enc.fecha_crea_time";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= "  AND it_ticket_enc.eliminado = 'N' ";
                    $sql = "
                   FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;
                case "UserBetsSportsDateClosed":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(it_ticket_enc.usuario_id)";
                    $groupbyDateField = "it_ticket_enc.fecha_cierre_time";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= "  AND it_ticket_enc.eliminado = 'N' ";
                    $sql = "
                   FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;
                case "UserBetsCasinoNORMAL":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(transaccion_juego.usuario_id)";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%DEBIT%' AND transaccion_juego.tipo ='NORMAL' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
";


                    break;
                case "UserBetsCasinoFREESPIN":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(transaccion_juego.usuario_id)";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%DEBIT%' AND transaccion_juego.tipo ='FREESPIN' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
";


                    break;
                case "UserBetsCasinoFREECASH":

                    $valueSUM = "1";
                    $valueCOUNT = "DISTINCT(transaccion_juego.usuario_id)";
                    $groupbyDateField = "transjuego_log.fecha_crea";
                    $byMoneda = 'usuario_mandante.moneda';

                    $sqlWhere .= "   AND transjuego_log.tipo LIKE '%DEBIT%' AND transaccion_juego.tipo ='FREECASH' ";

                    $sql = "
                     FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
";


                    break;


                case "BetsCasinoNORMAL":

                    $valueSUM = "reporte_casino_resumen.valor";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='NORMAL' ";

                    $sql = "
                     FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;
                case "BetsCasinoFREESPIN":

                    $valueSUM = "reporte_casino_resumen.valor";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='FREESPIN' ";

                    $sql = "
                     FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;
                case "BetsCasinoFREECASH":

                    $valueSUM = "reporte_casino_resumen.valor";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='FREECASH' ";

                    $sql = "
                     FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;

                case "GGRCasinoNORMAL":

                    $valueSUM = "
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END
            ";
                    $valueCOUNT = "*";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transaccion_juego_tipo ='NORMAL' ";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "GGRCasinoFREESPIN":

                    $valueSUM = "
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END
            ";
                    $valueCOUNT = "*";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transaccion_juego_tipo ='FREESPIN' ";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "GGRCasinoFREECASH":

                    $valueSUM = "
            CASE 
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' THEN -reporte_casino_resumen.valor
            WHEN reporte_casino_resumen.transjuego_log_tipo LIKE '%DEBIT%' THEN reporte_casino_resumen.valor
            ELSE 0
            END
            ";
                    $valueCOUNT = "*";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= "  AND reporte_casino_resumen.transaccion_juego_tipo ='FREECASH' ";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "WinsCasinoNORMAL":


                    $valueSUM = "
            reporte_casino_resumen.valor
            ";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= " 
                    AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='NORMAL'

";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "WinsCasinoFREESPIN":


                    $valueSUM = "
            reporte_casino_resumen.valor
            ";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= " 
                    AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='FREESPIN'

";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;


                case "WinsCasinoFREECASH":


                    $valueSUM = "
            reporte_casino_resumen.valor
            ";
                    $valueCOUNT = "SUM(reporte_casino_resumen.cantidad)";
                    $groupbyDateField = "reporte_casino_resumen.fecha_crea";
                    $byMoneda = 'reporte_casino_resumen.moneda';

                    $sqlWhere .= " 
                    AND reporte_casino_resumen.transjuego_log_tipo LIKE '%CREDIT%' AND reporte_casino_resumen.transaccion_juego_tipo ='FREECASH'

";

                    $sql = "
                    FROM reporte_casino_resumen
         inner join pais on reporte_casino_resumen.pais_id = pais.pais_id
         inner join mandante on reporte_casino_resumen.mandante = mandante.mandante
";


                    break;

                case "GGRSportCurrency":

                    $valueSUM = "
            it_ticket_enc.vlr_apuesta - it_ticket_enc.vlr_premio
            ";
                    $valueCOUNT = "*";
                    $groupbyDateField = "it_ticket_enc.fecha_cierre_time";
                    $byMoneda = 'usuario.moneda';

                    $sqlWhere .= " and it_ticket_enc.eliminado = 'N' ";

                    $sql = "
                    FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
";


                    break;

                default:
                    continue;
            }
            if ($_ENV['debug']) {
                print_r($sql);
            }

            if ($groupbyDateField != '') {

                $date = "DATE_FORMAT({$groupbyDateField}, '%Y-%m-%d %H:%i:%s')";
            } else {
                $date = "DATE_FORMAT(now(), '%Y-%m-%d %H:%i:%s')";

            }

            if ($TypeValue == 'COUNT') {
                $value = "COUNT(" . $valueCOUNT . ")";
                if (strpos($valueCOUNT, 'SUM') !== false) {
                    $value = $valueCOUNT;
                }
                $byMoneda = '';
            }
            if ($TypeValue == 'SUM') {
                if ($byMoneda != '') {
                    $value = "SUM(" . $valueSUM . ")";

                } else {
                    $value = "SUM(" . $valueSUM . ")";

                }
            }

            if ($TypeTotalDay == '1') {
                if ($groupbyDateField != '') {
                    $sqlWhere .= " AND {$groupbyDateField} LIKE '" . date("Y-m-d", strtotime($dateFrom)) . "%' ";

                }

            } else {
                if ($groupbyDateField != '') {
                    $sqlWhere .= " AND {$groupbyDateField} >= '{$dateFrom}' ";
                    $sqlWhere .= " AND {$groupbyDateField} < '{$dateTo}' ";
                }
                if ($groupby != '') {
                    $groupby .= ",FLOOR(UNIX_TIMESTAMP({$groupbyDateField}) / " . $TypeSplit . ")";

                } else {
                    if ($groupbyDateField != '') {

                        $groupby .= " GROUP BY FLOOR(UNIX_TIMESTAMP({$groupbyDateField}) / " . $TypeSplit . ")";
                    }
                }

            }
            if ($byMoneda != '') {
                $byMoneda = ',UPPER(' . $byMoneda . ') moneda';
            }
            $sql = "SELECT {$name} name, {$value} value, {$date} date{$byMoneda}"
                .
                $sql
                . "    
                    WHERE   1=1
                    {$sqlWhere}
                    {$groupby}
                    order by value desc;
                    ";


            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
            $Transaction = $BonoInternoMySqlDAO->getTransaction();

            $BonoInterno = new BonoInterno();
            $Resultado = $BonoInterno->execQuery($Transaction, $sql);

            if ($_ENV['debug']) {
                print_r('Resultado');
                print_r($Resultado);
            }
        } else {
            $TypeG = 'Counter';

            // Definir el array de mappings
            $cases = [
                "TotalUserBetsSports" => 'CantTotalUserBetsSportsTotalTotal' . '+' . '',
                "TotalUserBetsSportsFREEBET" => 'CantTotalUserBetsSportsTotalTotal' . '+' . 'FREEBET',
                "TotalUserBetsSportsPartnerCountry" => 'CantTotalUserBetsSportsCountryTotalTotal' . '+' . '',
                "TotalUserBetsSportsPartnerCountryFREEBET" => 'CantTotalUserBetsSportsCountryTotalTotal' . '+' . 'FREEBET',
                "TotalUserBetsCasinoNORMAL" => 'CantTotalUserBetsCasinoTotalTotal' . '+' . 'NORMAL',
                "TotalUserBetsCasinoFREESPIN" => 'CantTotalUserBetsCasinoTotalTotal' . '+' . 'FREESPIN',
                "TotalUserBetsCasinoFREECASH" => 'CantTotalUserBetsCasinoTotalTotal' . '+' . 'FREECASH',
                "TotalUserBetsCasinoPartnerCountryNORMAL" => 'CantTotalUserBetsCasinoCountryTotalTotal' . '+' . 'NORMAL',
                "TotalUserBetsCasinoPartnerCountryFREESPIN" => 'CantTotalUserBetsCasinoCountryTotalTotal' . '+' . 'FREESPIN',
                "TotalUserBetsCasinoPartnerCountryFREECASH" => 'CantTotalUserBetsCasinoCountryTotalTotal' . '+' . 'FREECASH',
                "TotalUserPings" => 'CantTotalUserPingsTotalTotal' . '+' . '',
                "TotalUserPingsPartner" => 'CantTotalUserPingsPartnerTotalTotal' . '+' . '',
                "TotalUserPingsPartnerCountry" => 'CantTotalUserPingsPartnerCountryTotalTotal' . '+' . ''
            ];

// Obtener el valor correspondiente usando el tipo $Type
            if (array_key_exists($Type, $cases)) {
                $key = $cases[$Type];
                $Resultado = $redis->get($key);
                $Resultado = json_decode($Resultado);
            } else {
                // Si no existe el tipo, manejar el caso de error
                $Resultado = null;  // O el valor que prefieras
            }
        }

        if (oldCount($Resultado) > 0) {
            if ($TypeGroup == '') {
                $TypeGroup = 'General';
            }
            switch ($TypeG) {
                case "ListFunel":
                    $array = [];

                    foreach ($Resultado as $index => $value) {

                        $item2 = new stdClass();

                        $item2->key = $NameReportTotalDATA;
                        $item2->value = $value->{".value"};
                        $item2->date = date('Y-m-d\TH:i:sP', strtotime($value->{".date"}));
                        $item2->unit = $value->{".moneda"};
                        $item3 = new stdClass();
                        $item3->key = $TypeGroup;
                        $item3->value = $value->{".name"};
                        $item2->attributes = array(
                            $item3
                        );
                        $item2->timestamp = strtotime($value->{".date"});

                        array_push($array, $item2);
                    }


                    if (oldCount($array) > 0) {


                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_USERPWD => '2uyhe2ia489apvmhawgipj1uismlj0fkyg0wf8d9piex1n07jg:',

                            CURLOPT_URL => 'https://push.databox.com/data',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($array),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Accept: application/vnd.databox.v2+json'

                            ),
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                        print_r($NameReportTotal);
                        print_r(PHP_EOL);

                        echo $response;

                    }
                    break;
                case "Counter":

                    $array = [];

                    foreach ($Resultado as $index => $value) {

                        $item2 = new stdClass();

                        $item2->key = $NameReportTotalDATA;
                        $item2->value = $value->{".value"};
                        $item2->date = date('Y-m-d\TH:i:sP', strtotime($value->{".date"}));
                        $item2->unit = $value->{".moneda"};
                        $item3 = new stdClass();
                        $item3->key = $TypeGroup;
                        $item3->value = 'Data';
                        $item2->attributes = array(
                            $item3
                        );
                        $item2->timestamp = strtotime($value->{".date"});

                        array_push($array, $item2);
                    }

                    if (oldCount($array) > 0) {


                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_USERPWD => '2uyhe2ia489apvmhawgipj1uismlj0fkyg0wf8d9piex1n07jg:',

                            CURLOPT_URL => 'https://push.databox.com/data',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($array),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Accept: application/vnd.databox.v2+json',

                            ),
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                        print_r($NameReportTotal);
                        print_r(PHP_EOL);

                        echo $response;
                    }
            }

        }


    }

    //$NameReportTotal,$TypeG,$TypeDate2,$State,$IsUnique,$TypeDate,$TypeDate3,$TypeData,$TypeState,$Type2,$TypeBets,$TotalTotal22


    /*
     *
     * $TypeGroup:'',Partner,PartnerCountry
     * $TypeValue: COUNT,SUM
     * $TypeTotalDay: 1,''
     */
    $Types = array(

        "FirstDeposits" => array('&COUNT&', 'Partner&COUNT&', 'PartnerCountry&COUNT&'),
        "FirstDepositsAmount" => array('PartnerCountry&SUM&')

    );


    foreach ($Types as $Type => $Values) {
        if (oldCount($Values) == 0) {
            sendData($Type, '', '', '');
        } else {
            foreach ($Values as $value) {
                $valueexplode = explode("&", $value);
                sendData($Type, $valueexplode[0], $valueexplode[1], $valueexplode[2]);

            }
        }


    }

} catch (Exception $e) {
    print_r($e);
}
print_r('entro');
exit();


$fechaL1 = '2025-03-01 00:00:00';
$fechaL2 = '2025-03-11 12:00:00';


$redis = RedisConnectionTrait::getRedisInstance(true);
$comandos = array();

$datetie = date('s');

print_r('ENTRO1');

$_ENV["NEEDINSOLATIONLEVEL"] = '1';
$_ENV["debug"] = true;


$ConfigurationEnvironment = new ConfigurationEnvironment();

$BonoInterno = new BonoInterno();


$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();


//$this->SlackVS->sendMessage("*CRON: (CronJobRollover) * " . " - Fecha: " . date("Y-m-d H:i:s"));

$ActivacionOtros = true;
$ActivacionSleepTime = false;


if ($ActivacionOtros) {
    $debug = false;

    $BonoInterno = new BonoInterno();

    $sqlApuestasDeportivasUsuarioDiaCierre = "

select transjuego_log.transjuegolog_id,producto.subproveedor_id,transaccion_juego.usuario_id,transjuego_log.valor,
       producto.producto_id,
       producto.proveedor_id,
       producto.subproveedor_id,
       producto_mandante.prodmandante_id,
       subproveedor.tipo,
       
       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id
       
from transjuego_log
INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id

         INNER JOIN
     (select usuario_bono.usuario_id, bono_interno.bono_id, bd1.tipo tipo1, bd2.tipo tipo2, bd3.tipo tipo3
      from usuario_bono
               INNER JOIN bono_interno
                          ON (bono_interno.bono_id = usuario_bono.bono_id)
                INNER JOIN bono_detalle
                    ON (bono_interno.bono_id = bono_detalle.bono_id and bono_detalle.tipo = 'TIPOPRODUCTO' AND
                        bono_detalle.valor != 2)
               LEFT OUTER JOIN bono_detalle bd1
                               ON (bono_interno.bono_id = bd1.bono_id and bd1.tipo LIKE 'CONDGAME%')
               LEFT OUTER JOIN bono_detalle bd2
                               ON (bono_interno.bono_id = bd2.bono_id and bd2.tipo LIKE 'CONDSUBPROVIDER%')
               LEFT OUTER JOIN bono_detalle bd3
                               ON (bono_interno.bono_id = bd3.bono_id and bd3.tipo LIKE 'CONDPROVIDER%')
      where bono_interno.tipo in (2, 3)
         and usuario_bono.rollower_requerido > 0 and usuario_bono.fecha_crea <= '" . $fechaL2 . "'
        and usuario_bono.estado = 'A') x on (
         x.usuario_id = usuario_mandante.usuario_mandante
             AND ((tipo1 = CONCAT('CONDGAME', producto_mandante.prodmandante_id) or
                   tipo2 = CONCAT('CONDSUBPROVIDER', producto.subproveedor_id) or
                   tipo3 = CONCAT('CONDPROVIDER', producto.proveedor_id)) or
                  (tipo1 is null and tipo2 is null and tipo3 is null))
         )


    WHERE transjuego_log.fecha_crea >= '" . $fechaL1 . "' AND transjuego_log.fecha_crea < '" . $fechaL2 . "'
    
    AND usuario_mandante.usuario_mandante in (7236069,8978264,9200700,7149439,7149107,7145765,8199848,9324241,8836977,7144478,8681341,8531009,7153106,7144340,7148724,7147217,7594818,7144448,7151467,7239952,7144148,7184284,9177482,7144314,7504726,7153132,7148225,7143934,7152399,7158288,7144516,7158044,7143830,7147521,7146810,7145200,7334772,7146983,8348449,7143570,9317076,7146365,8939455,7146479,8816077,8769165,7151167,7144978,7144940,7214485,7156198,7143554,9043417,8816231,7151911,8251538,7157170,7145813,7143956,7144620,7144852,7144154,7145180,7260512,7148903,7148445,9204422,9183584,7147759,7144800,7147715,7143962,9213561,7146917,7150241,7144774,7461682,7148931,7180283,8940301,8572809,7153440,7151867,7146255,7158406,8029821,9114516,7157722,7145971,7143658,7144672,7144766,7249971,7145817,7143750,7144470,7148511,9254092,7146431,7145652,7146590,7148633,7165719,7145496,7148103,7144768,7144560,8017862,7145236,7146630,7144748,7144976,7150139,7144022,9351287,7150267,7150993,7155788,7152473,7156888,7143764,8770513,8280386,7150443,7952785,9180025,7198825,9266179,7148748,7162303,7144116,7146353,7144698,9015689,7147369,7147003,7510048,7226388,7149229,7251621,7293109,7144068,7146612,7146967,7147577,7149829,7144472,7150589,7735724,7153819,7143656,7154303,7149753,7144576,7143890,7158216,9053799,7154413,7145921,7144854,8551083,7156156,7149031,9223699,7153408,7143846,7147671,7144528,7158130,9241270,7152387,7149919,8934005,7147309,7144782,7145144,7143640,7149477,7149565,7149629,7153803,7158714,7146075,7149473,8090453,7482841,7147823,7160733,8834563,9130279,7143630,7149973,7146035,9239668,7608790,7144684,7152814,9118281,7147379,7144212,9106716,7255156,7145186,7144822,7144930,8738267,8824694,7145312,7148007,7145488,7700780,7152367,7144524,8974563,7150947,7144306,9330436,7153583,7145789,7144162,7592427,9091437,7144298,7147799,8499396,7144414,9006755,7146696,7152267,7148129,7155007,7145697,7338877,7145969,9259367,7153903,7168705,8695353,7152495,7153573,7145074,7144986,8431188,7143910,7154365,7147797,7145556,7147921,9052207,7144832,7143944,7651936,7155339,7152690,7150681,7148195,9125398,7152568,7143882,7143592,9166016,7144344,9215944,7158976,9052940,7148700,7144586,8772453,7146013,7150221,7144458,8261531,7149865,7145484,7250628,7145574,8484760,9280718,7145654,7144446,7887763,7156142,7904511,7149221,7143624,9089350,7144326,7148399,7145687,7148271,9145333,8400637,8829867,8665962,7144384,7144202,7145258,7143530,8539645,9218017,7145406,7147073,7145168,8830971,7144690,7152534,7145811,7144948,7402859,8541783,7144368,7144104,7159088,7147113,9232730,7144120,7148387,7150257,9273126,7152457,7148839,7148347,7154923,8715286,7150289,7327323,7143508,7569060,7144476,7146863,7143485,7156276,7145711,7144750,7143912,7146770,7146572,7145520,8732314,7144674,9315282,7149337,9220317,9074179,7144608,7234568,7144796,7144242,7143582,7145941,7144526,7151079,7144928,7145444,7145070,8135409,7146666,7149675,7149085,9170706,7144702,7143866,7144328,7149743,9245436,7408029,7143638,7151491,7144352,7144744,8655380,7157802,7148967,7147151,7158000,7144186,9357175,7144040,7147321,7144140,7146853,7150449,7146592,7148692,8148656,7144792,7145364,7145572,7144746,7150717,7382189,7151493,7144030,7144132,7150571,7149619,7556677,7143550,9333083,7150265,7172956,7154275,7144302,7216635,7219284,7144430,8021141,7942003,7144492,7144856,7146604,7145741,7145807,7155900,7157500,8723180,7147683,7143998,7144438,7148281,7146253,7608306,7150829,7144042,7144388,7407659,7148627,7146652,9357041,7148161,7144394,7149183,7143514,7149503,7694987,7144642,7146183,7146269,7145755,7146187,7796870,7145102,7149323,7144112,7156204,7145660,9015949,7143574,7144634,7143650,7145612,7146335,8779620,8360004,7146788,7144798,7144288,7146125,7793332,7144522,7151659,7144936,7144920,7149761,7144614,7144402,7143702,7144346,8481663,7144872,8131164,9274384,7148245,8680690,7147125,7146568,7153012,7145156,7146800,7147905,7156446,7240135,7145841,7144810,7146562,7153577,7152123,8487962,7150311,7144604,7547647,7143820,7155091,7147021,8527005,7307666,7149297,7143914,7149735,7144356,7149159,7149961,7156150,7144026,8608820,7150751,7150259,7147743,8816494,7148623,9179379,8305221,7149933,7158778,8554731,7559949,7143822,7149591,8978573,8772777,8053018,7144966,7153801,7143498,7150879,8979711,7144606,7150135,7149413,9107947,7144760,7514847,7153104,7942463,7149979,7916310,7145534,7890302,7912558,7144380,7149577,7151903,7145991,8508065,7165587,7146560,7151193,7155333,7152630,9306003,7242901,8973446,7143552,7144658,7145012,7147915,7144754,9043955,7158398,7707810,7145050,9164759,7144018,7157316,7143644,8140072,7146389,8122908,7146379,7539602,7143780,7149595,7252702,7147165,8541120,7144406,7144836,7143886,7144332,7147437,7143864,7144416,7143660,7144624,7145749,7146622,7158792,8501107,7148463,7145951,7147007,7156304,7148279,7147983,7143900,7268067,7170746,8533345,7145899,9203329,9066818,7147067,7153086,8746554,7144828,7147545,9285332,7149387,7144172,7148728,7157066,8909782,7145606,7154807,7148881,7143542,9127299,7145879,7155698,7147193,7146905,7146227,8393079,7651464,7144012,7595296,8012359,7561792,9055261,7143748,7145476,7148895,7144830,7158732,7148813,7143758,8641571,9203234,8638545,7144482,7145648,7149843,7148603,8022554,7148107,7144428,7143926,7147231,8286689,7143596,7155089,7151117,7149321,7149697,7149951,7147251,7143510,8437350,9219507,7144694,7155263,7148415,7144670,7146177,7144912,7148287,7962632,7144432,7144720,7144858,7147459,7144274,7143948,7143652,7149351,7144106,8495045,7154149,7143802,7147185,7148969,7145598,7717409,7526535,7151869,9193211,7153004,7149277,9326111,7144282,7322646,8826830,8351745,7147445,7145222,7143548,7159238,9372812,9372272,9371561,9371410,9370956,9370729,9370595,9370061,9369853,9367858,9367615,9366720,9360330,9360103,9352421,9340677,9333014,9324241,9324158,9285332,9274384,9259367,9258988,9245674,9214616,9211655,9204644,9203789,9203329,9200700,9192487,9185042,9179379,9178425,9172313,9166016,9164759,9162288,9158752,9136218,9127299,9125398,9120205,9106716,9100116,9096701,9089350,9084287,9083692,9075223,9074564,9066818,9055261,9053799,9052207,9043242,9026515,9022195,9010330,8979711,8978573,8978264,8974563,8973446,8940301,8929981,8918476,8908147,8905866,8836700,8835715,8834563,8826830,8816077,8772777,8772453,8770513,8769165,8681341,8655380,8635194,8608820,8590428,8579726,8533345,8531009,8508065,8501107,8487962,8478855,8464036,8449923,8437350,8423271,8412800,8412217,8393079,8387554,8367667,8360004,8351745,8328159,8309632,8305221,8286689,8280386,8269775,8251538,8199848,8173558,8155847,8122908,8091142,8090453,8062787,8032674,8029821,8022554,8021141,8017862,8015516,7991517,7949523,7942463,7942003,7904511,7887763,7793332,7771880,7746531,7740597,7735724,7711509,7707810,7694987,7657353,7651464,7641157,7608306,7595296,7594818,7592427,7587889,7569060,7547647,7539602,7538026,7510048,7503490,7482841,7461682,7450149,7442104,7433150,7429949,7408029,7407659,7345940,7322646,7293109,7266795,7260512,7252702,7249971,7240135,7236065,7233813,7216635,7214485,7191856,7189258,7180283,7180218,7172956,7170746,7168705,7168660,7167175,7165719,7164477,7164173,7162303,7161522,7159194,7159088,7159050,7158976,7158962,7158732,7158398,7158292,7158216,7158072,7158040,7157802,7157570,7157500,7157420,7157170,7157066,7156962,7156640,7156304,7156156,7156044,7155900,7155788,7155744,7155732,7155698,7155263,7155259,7155123,7155089,7154915,7154365,7153801,7153573,7153440,7153394,7153330,7153194,7153180,7153106,7153086,7152958,7152816,7152726,7152710,7152630,7152473,7152457,7152399,7152387,7152179,7151977,7151883,7151869,7151525,7151467,7151315,7151249,7151193,7151117,7151079,7151017,7150947,7150909,7150905,7150879,7150763,7150753,7150751,7150717,7150711,7150681,7150571,7150501,7150461,7150311,7150289,7150221,7150009,7149979,7149973,7149951,7149933,7149919,7149843,7149829,7149823,7149789,7149761,7149687,7149675,7149619,7149591,7149577,7149565,7149529,7149503,7149477,7149473,7149449,7149433,7149413,7149351,7149337,7149321,7149277,7149229,7149183,7149179,7149159,7149107,7149085,7149077,7149031,7148931,7148929,7148895,7148748,7148700,7148633,7148627,7148623,7148603,7148559,7148463,7148415,7148399,7148353,7148347,7148333,7148307,7148287,7148283,7148279,7148245,7148225,7148161,7148139,7148129,7148007,7147993,7147983,7147945,7147933,7147915,7147905,7147799,7147797,7147787,7147773,7147759,7147703,7147577,7147545,7147445,7147391,7147309,7147293,7147291,7147247,7147217,7147193,7147189,7147185,7147177,7147165,7147155,7147151,7147113,7147097,7147073,7147009,7147007,7146979,7146967,7146877,7146863,7146853,7146824,7146812,7146800,7146718,7146666,7146652,7146622,7146612,7146604,7146590,7146582,7146572,7146451,7146415,7146409,7146389,7146379,7146365,7146361,7146353,7146347,7146335,7146321,7146311,7146297,7146291,7146253,7146187,7146183,7146125,7146085,7146035,7145989,7145971,7145921,7145899,7145879,7145813,7145811,7145807,7145765,7145749,7145741,7145719,7145697,7145683,7145654,7145626,7145612,7145600,7145574,7145572,7145556,7145534,7145520,7145514,7145504,7145498,7145488,7145476,7145432,7145422,7145420,7145414,7145406,7145404,7145400,7145312,7145296,7145270,7145236,7145200,7145156,7145128,7145096,7145070,7145050,7144994,7144988,7144986,7144976,7144966,7144956,7144948,7144936,7144928,7144912,7144888,7144872,7144862,7144856,7144852,7144844,7144836,7144830,7144828,7144822,7144810,7144804,7144798,7144792,7144782,7144774,7144768,7144760,7144754,7144720,7144694,7144690,7144684,7144670,7144660,7144654,7144634,7144624,7144622,7144614,7144608,7144604,7144590,7144586,7144576,7144558,7144528,7144524,7144522,7144518,7144516,7144492,7144488,7144482,7144478,7144476,7144446,7144442,7144438,7144432,7144430,7144428,7144410,7144394,7144388,7144384,7144368,7144346,7144340,7144332,7144328,7144326,7144308,7144300,7144288,7144252,7144242,7144202,7144186,7144148,7144140,7144132,7144130,7144120,7144116,7144112,7144104,7144094,7144086,7144068,7144052,7144042,7144040,7144030,7144026,7144022,7144016,7144012,7143996,7143956,7143944,7143930,7143926,7143914,7143910,7143900,7143890,7143850,7143846,7143830,7143822,7143820,7143802,7143798,7143762,7143748,7143714,7143710,7143688,7143674,7143660,7143658,7143652,7143638,7143624,7143606,7143596,7143592,7143582,7143554,7143552,7143542,7143536,7143530,7143528,7143524,7143510,7143508,7143498,9398776,9398776,9395128,9388360,9388360,9388122,9388122,9385503,9374915,9374915,9365455,9365164,9360671,9360504,9359207,9359207,9359207,9356761,9348641,9346670,9346670,9346670,9341173,9333253,9329584,9329584,9329584,9322300,9322154,9314969,9314314,9314314,9311122,9291051,9280931,9269413,9254988,9254988,9254988,9247242,9238184,9236576,9236576,9235724,9203494,9192941,9182751,9179184,9155138,9135656,9094573,9071854,9024090,9013294,9005358,8956062,8892143,8862419,8855074,8853360,8816166,8810566,8764779,8748698,8689359,8670507,8664272,8608682,8580280,8530309,8525545,8525545,8525083,8521837,8459364,8430691,8413435,8360105,8244650,8217081,8217081,8217081,8144766,8135976,8126176,8106141,8104292,8098779,8097185,8073712,8037041,8005466,7913524,7908827,7884793,7816993,7786504,7742746,7711141,7703086,7695703,7681405,7680905,7676341,7650711,7645004,7627891,7605271,7601341,7594611,7587033,7582174,7579271,7543726,7505908,7484343,7440952,7363393,7352448,7341618,7338925,7332573,7332573,7332573,7320784,7302933,7287917,7282128,7234366,7183411,7124902,7085942,7056488,7053603,7010907,6990582,6988467,6971420,6952502,6948709,6906415,6899033,6884552,6873350,6872489,6841898,6814311,6653092,6566659,6566317,6534395,6525677,6506009,6474707,6445828,6429270,6428984,6423362,6409888,6404606,6402544,6396026,6386469,6285995,6274848,6262392,6218971,6216913,6209602,6209158,6209158,6173707,6031760,6005840,5980746,5897217,5857734,5838672,5823363,5776800,5773116,5752198,5678103,5633846,5617193,5535099,5512197,5506545,5398066,5389843,5379820,5374918,5247350,5242082,5198948,5190803,5180713,5161165,5156921,5143271,5087539,5067475,4999043,4986464,4973790,4883080,4878625,4831342,4827646,4811137,4779151,4762123,4710899,4691687,4656359,4652102,4652102,4651613,4552408,4272517,4256155,4253218,4225483,4200616,4172511,4171717,4166427,4159424,4092127,4082370,4061691,4051625,3986753,3946235,3919517,3918521,3918104,3915980,3913694,3909233,3907148,3903485,3901478,3901430,3894842,3894152,3891011,3889967,3883997,3881300,3875687,3867068,3866306,3863354,3849497,3845756,3838853,3784326,3712428,3632921,3564858,3549451,3545977,3503698,3478606,3463831,3448399,3439162,3420064,3407311,3395617,3339625,3337855,3261319,3234751,3219589,3192712,3177412,3158011,3157933,3156907,3155872,3147229,3146695,3144430,3142399,3140314,3140068,3139546,3139243,3138790,3138559,3137377,3133516,3130510,3127459,3126997,3124438,3122083,3121339,3115834,3114067,3112252,3111052,3110074,3104293,3103681,3102115,3099178,3098566,3096337,3095995,3095338,3095116,3094999,3094954,3094948,3094726,3094723,3094498,3094411,3094363,3094189,3093487,3092866,3092788,3092689,3092665,3092383,3091993,3091705,3091486,3091231,3090925,3090913,3090853,3090712,3090466,3090160,3090097,3089803,3089671,3089587,3089416,3089281,3088657,3088528,3087949,3087796,3087457,3087208,3086806,3086788,3086773,3086440,3086377,3086251,3086143,3085915,3085555,9375724,9375117,9374301,9372849,9371521,9370901,9368552,9368361,9366011,9365455,9365217,9365164,9364173,9362652,9362485,9362367,9360671,9360504,9360141,9359792,9357439,9356761,9354945,9342891,9341989,9341173,9337041,9334053,9304731,9155138,3867608)
and transjuego_log.tipo LIKE 'DEBIT%'
";
    print_r($sqlApuestasDeportivasUsuarioDiaCierre);
    $time = time();

    $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

    print_r(oldCount($data));
    $cont = 0;
    foreach ($data as $datanum) {

        if ($datanum->{'usuario_mandante.usuario_mandante'} == '9002180') {
            $this->SlackVS->sendMessage("*CRON: (CronJobRollover) * U" . $datanum->{'usuario_mandante.usuario_mandante'} . ' ' . $datanum->{'transjuego_log.transjuegolog_id'});
        }

        //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
        $amount = floatval($datanum->{'transjuego_log.valor'});

        $typeP = "CASINO";

        if ($datanum->{'subproveedor.tipo'} == 'CASINO') {
            $typeP = "CASINO";
        } elseif ($datanum->{'subproveedor.tipo'} == 'LIVECASINO') {
            $typeP = "LIVECASINO";
        } elseif ($datanum->{'subproveedor.tipo'} == 'VIRTUAL') {
            $typeP = "VIRTUALES";
        }

        $comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/VerificarRollower.php", "CASINO '' " . $datanum->{'usuario_mandante.usuario_mandante'} . " " . $datanum->{'transjuego_log.transjuegolog_id'});

        if (($cont % 50) == 0) {
            //exec(implode(' ', $comandos), $output);
            //$comandos=array();
        }

        $cont++;

    }

    if (oldCount($comandos) > 0) {
        //exec(implode(' ', $comandos), $output);
        //$comandos=array();
    }
}


print_r('OK2');
print_r(PHP_EOL);
if ($ActivacionOtros) {

    $BonoInterno = new BonoInterno();

    $sqlApuestasDeportivasUsuarioDiaCierre = "
        SELECT
        it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.usuario_id
        FROM it_ticket_enc
            
    INNER JOIN
     (select usuario_bono.usuario_id
      from usuario_bono
               INNER JOIN bono_interno
                          ON (bono_interno.bono_id = usuario_bono.bono_id)
                INNER JOIN bono_detalle
                    ON (bono_interno.bono_id = bono_detalle.bono_id and bono_detalle.tipo = 'TIPOPRODUCTO' AND
                        bono_detalle.valor = 2)
      where bono_interno.tipo in (2, 3)
        and usuario_bono.estado = 'A' and usuario_bono.rollower_requerido > 0 and usuario_bono.fecha_crea <= '" . $fechaL2 . "'
        ) x on x.usuario_id = it_ticket_enc.usuario_id
            
        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
        INNER JOIN registro ON (registro.usuario_id = it_ticket_enc.usuario_id)
        WHERE (it_ticket_enc.fecha_cierre_time) >= '" . $fechaL1 . "' AND (it_ticket_enc.fecha_cierre_time) < '" . $fechaL2 . "' 
        
            AND it_ticket_enc.usuario_id in (7236069,8978264,9200700,7149439,7149107,7145765,8199848,9324241,8836977,7144478,8681341,8531009,7153106,7144340,7148724,7147217,7594818,7144448,7151467,7239952,7144148,7184284,9177482,7144314,7504726,7153132,7148225,7143934,7152399,7158288,7144516,7158044,7143830,7147521,7146810,7145200,7334772,7146983,8348449,7143570,9317076,7146365,8939455,7146479,8816077,8769165,7151167,7144978,7144940,7214485,7156198,7143554,9043417,8816231,7151911,8251538,7157170,7145813,7143956,7144620,7144852,7144154,7145180,7260512,7148903,7148445,9204422,9183584,7147759,7144800,7147715,7143962,9213561,7146917,7150241,7144774,7461682,7148931,7180283,8940301,8572809,7153440,7151867,7146255,7158406,8029821,9114516,7157722,7145971,7143658,7144672,7144766,7249971,7145817,7143750,7144470,7148511,9254092,7146431,7145652,7146590,7148633,7165719,7145496,7148103,7144768,7144560,8017862,7145236,7146630,7144748,7144976,7150139,7144022,9351287,7150267,7150993,7155788,7152473,7156888,7143764,8770513,8280386,7150443,7952785,9180025,7198825,9266179,7148748,7162303,7144116,7146353,7144698,9015689,7147369,7147003,7510048,7226388,7149229,7251621,7293109,7144068,7146612,7146967,7147577,7149829,7144472,7150589,7735724,7153819,7143656,7154303,7149753,7144576,7143890,7158216,9053799,7154413,7145921,7144854,8551083,7156156,7149031,9223699,7153408,7143846,7147671,7144528,7158130,9241270,7152387,7149919,8934005,7147309,7144782,7145144,7143640,7149477,7149565,7149629,7153803,7158714,7146075,7149473,8090453,7482841,7147823,7160733,8834563,9130279,7143630,7149973,7146035,9239668,7608790,7144684,7152814,9118281,7147379,7144212,9106716,7255156,7145186,7144822,7144930,8738267,8824694,7145312,7148007,7145488,7700780,7152367,7144524,8974563,7150947,7144306,9330436,7153583,7145789,7144162,7592427,9091437,7144298,7147799,8499396,7144414,9006755,7146696,7152267,7148129,7155007,7145697,7338877,7145969,9259367,7153903,7168705,8695353,7152495,7153573,7145074,7144986,8431188,7143910,7154365,7147797,7145556,7147921,9052207,7144832,7143944,7651936,7155339,7152690,7150681,7148195,9125398,7152568,7143882,7143592,9166016,7144344,9215944,7158976,9052940,7148700,7144586,8772453,7146013,7150221,7144458,8261531,7149865,7145484,7250628,7145574,8484760,9280718,7145654,7144446,7887763,7156142,7904511,7149221,7143624,9089350,7144326,7148399,7145687,7148271,9145333,8400637,8829867,8665962,7144384,7144202,7145258,7143530,8539645,9218017,7145406,7147073,7145168,8830971,7144690,7152534,7145811,7144948,7402859,8541783,7144368,7144104,7159088,7147113,9232730,7144120,7148387,7150257,9273126,7152457,7148839,7148347,7154923,8715286,7150289,7327323,7143508,7569060,7144476,7146863,7143485,7156276,7145711,7144750,7143912,7146770,7146572,7145520,8732314,7144674,9315282,7149337,9220317,9074179,7144608,7234568,7144796,7144242,7143582,7145941,7144526,7151079,7144928,7145444,7145070,8135409,7146666,7149675,7149085,9170706,7144702,7143866,7144328,7149743,9245436,7408029,7143638,7151491,7144352,7144744,8655380,7157802,7148967,7147151,7158000,7144186,9357175,7144040,7147321,7144140,7146853,7150449,7146592,7148692,8148656,7144792,7145364,7145572,7144746,7150717,7382189,7151493,7144030,7144132,7150571,7149619,7556677,7143550,9333083,7150265,7172956,7154275,7144302,7216635,7219284,7144430,8021141,7942003,7144492,7144856,7146604,7145741,7145807,7155900,7157500,8723180,7147683,7143998,7144438,7148281,7146253,7608306,7150829,7144042,7144388,7407659,7148627,7146652,9357041,7148161,7144394,7149183,7143514,7149503,7694987,7144642,7146183,7146269,7145755,7146187,7796870,7145102,7149323,7144112,7156204,7145660,9015949,7143574,7144634,7143650,7145612,7146335,8779620,8360004,7146788,7144798,7144288,7146125,7793332,7144522,7151659,7144936,7144920,7149761,7144614,7144402,7143702,7144346,8481663,7144872,8131164,9274384,7148245,8680690,7147125,7146568,7153012,7145156,7146800,7147905,7156446,7240135,7145841,7144810,7146562,7153577,7152123,8487962,7150311,7144604,7547647,7143820,7155091,7147021,8527005,7307666,7149297,7143914,7149735,7144356,7149159,7149961,7156150,7144026,8608820,7150751,7150259,7147743,8816494,7148623,9179379,8305221,7149933,7158778,8554731,7559949,7143822,7149591,8978573,8772777,8053018,7144966,7153801,7143498,7150879,8979711,7144606,7150135,7149413,9107947,7144760,7514847,7153104,7942463,7149979,7916310,7145534,7890302,7912558,7144380,7149577,7151903,7145991,8508065,7165587,7146560,7151193,7155333,7152630,9306003,7242901,8973446,7143552,7144658,7145012,7147915,7144754,9043955,7158398,7707810,7145050,9164759,7144018,7157316,7143644,8140072,7146389,8122908,7146379,7539602,7143780,7149595,7252702,7147165,8541120,7144406,7144836,7143886,7144332,7147437,7143864,7144416,7143660,7144624,7145749,7146622,7158792,8501107,7148463,7145951,7147007,7156304,7148279,7147983,7143900,7268067,7170746,8533345,7145899,9203329,9066818,7147067,7153086,8746554,7144828,7147545,9285332,7149387,7144172,7148728,7157066,8909782,7145606,7154807,7148881,7143542,9127299,7145879,7155698,7147193,7146905,7146227,8393079,7651464,7144012,7595296,8012359,7561792,9055261,7143748,7145476,7148895,7144830,7158732,7148813,7143758,8641571,9203234,8638545,7144482,7145648,7149843,7148603,8022554,7148107,7144428,7143926,7147231,8286689,7143596,7155089,7151117,7149321,7149697,7149951,7147251,7143510,8437350,9219507,7144694,7155263,7148415,7144670,7146177,7144912,7148287,7962632,7144432,7144720,7144858,7147459,7144274,7143948,7143652,7149351,7144106,8495045,7154149,7143802,7147185,7148969,7145598,7717409,7526535,7151869,9193211,7153004,7149277,9326111,7144282,7322646,8826830,8351745,7147445,7145222,7143548,7159238,9372812,9372272,9371561,9371410,9370956,9370729,9370595,9370061,9369853,9367858,9367615,9366720,9360330,9360103,9352421,9340677,9333014,9324241,9324158,9285332,9274384,9259367,9258988,9245674,9214616,9211655,9204644,9203789,9203329,9200700,9192487,9185042,9179379,9178425,9172313,9166016,9164759,9162288,9158752,9136218,9127299,9125398,9120205,9106716,9100116,9096701,9089350,9084287,9083692,9075223,9074564,9066818,9055261,9053799,9052207,9043242,9026515,9022195,9010330,8979711,8978573,8978264,8974563,8973446,8940301,8929981,8918476,8908147,8905866,8836700,8835715,8834563,8826830,8816077,8772777,8772453,8770513,8769165,8681341,8655380,8635194,8608820,8590428,8579726,8533345,8531009,8508065,8501107,8487962,8478855,8464036,8449923,8437350,8423271,8412800,8412217,8393079,8387554,8367667,8360004,8351745,8328159,8309632,8305221,8286689,8280386,8269775,8251538,8199848,8173558,8155847,8122908,8091142,8090453,8062787,8032674,8029821,8022554,8021141,8017862,8015516,7991517,7949523,7942463,7942003,7904511,7887763,7793332,7771880,7746531,7740597,7735724,7711509,7707810,7694987,7657353,7651464,7641157,7608306,7595296,7594818,7592427,7587889,7569060,7547647,7539602,7538026,7510048,7503490,7482841,7461682,7450149,7442104,7433150,7429949,7408029,7407659,7345940,7322646,7293109,7266795,7260512,7252702,7249971,7240135,7236065,7233813,7216635,7214485,7191856,7189258,7180283,7180218,7172956,7170746,7168705,7168660,7167175,7165719,7164477,7164173,7162303,7161522,7159194,7159088,7159050,7158976,7158962,7158732,7158398,7158292,7158216,7158072,7158040,7157802,7157570,7157500,7157420,7157170,7157066,7156962,7156640,7156304,7156156,7156044,7155900,7155788,7155744,7155732,7155698,7155263,7155259,7155123,7155089,7154915,7154365,7153801,7153573,7153440,7153394,7153330,7153194,7153180,7153106,7153086,7152958,7152816,7152726,7152710,7152630,7152473,7152457,7152399,7152387,7152179,7151977,7151883,7151869,7151525,7151467,7151315,7151249,7151193,7151117,7151079,7151017,7150947,7150909,7150905,7150879,7150763,7150753,7150751,7150717,7150711,7150681,7150571,7150501,7150461,7150311,7150289,7150221,7150009,7149979,7149973,7149951,7149933,7149919,7149843,7149829,7149823,7149789,7149761,7149687,7149675,7149619,7149591,7149577,7149565,7149529,7149503,7149477,7149473,7149449,7149433,7149413,7149351,7149337,7149321,7149277,7149229,7149183,7149179,7149159,7149107,7149085,7149077,7149031,7148931,7148929,7148895,7148748,7148700,7148633,7148627,7148623,7148603,7148559,7148463,7148415,7148399,7148353,7148347,7148333,7148307,7148287,7148283,7148279,7148245,7148225,7148161,7148139,7148129,7148007,7147993,7147983,7147945,7147933,7147915,7147905,7147799,7147797,7147787,7147773,7147759,7147703,7147577,7147545,7147445,7147391,7147309,7147293,7147291,7147247,7147217,7147193,7147189,7147185,7147177,7147165,7147155,7147151,7147113,7147097,7147073,7147009,7147007,7146979,7146967,7146877,7146863,7146853,7146824,7146812,7146800,7146718,7146666,7146652,7146622,7146612,7146604,7146590,7146582,7146572,7146451,7146415,7146409,7146389,7146379,7146365,7146361,7146353,7146347,7146335,7146321,7146311,7146297,7146291,7146253,7146187,7146183,7146125,7146085,7146035,7145989,7145971,7145921,7145899,7145879,7145813,7145811,7145807,7145765,7145749,7145741,7145719,7145697,7145683,7145654,7145626,7145612,7145600,7145574,7145572,7145556,7145534,7145520,7145514,7145504,7145498,7145488,7145476,7145432,7145422,7145420,7145414,7145406,7145404,7145400,7145312,7145296,7145270,7145236,7145200,7145156,7145128,7145096,7145070,7145050,7144994,7144988,7144986,7144976,7144966,7144956,7144948,7144936,7144928,7144912,7144888,7144872,7144862,7144856,7144852,7144844,7144836,7144830,7144828,7144822,7144810,7144804,7144798,7144792,7144782,7144774,7144768,7144760,7144754,7144720,7144694,7144690,7144684,7144670,7144660,7144654,7144634,7144624,7144622,7144614,7144608,7144604,7144590,7144586,7144576,7144558,7144528,7144524,7144522,7144518,7144516,7144492,7144488,7144482,7144478,7144476,7144446,7144442,7144438,7144432,7144430,7144428,7144410,7144394,7144388,7144384,7144368,7144346,7144340,7144332,7144328,7144326,7144308,7144300,7144288,7144252,7144242,7144202,7144186,7144148,7144140,7144132,7144130,7144120,7144116,7144112,7144104,7144094,7144086,7144068,7144052,7144042,7144040,7144030,7144026,7144022,7144016,7144012,7143996,7143956,7143944,7143930,7143926,7143914,7143910,7143900,7143890,7143850,7143846,7143830,7143822,7143820,7143802,7143798,7143762,7143748,7143714,7143710,7143688,7143674,7143660,7143658,7143652,7143638,7143624,7143606,7143596,7143592,7143582,7143554,7143552,7143542,7143536,7143530,7143528,7143524,7143510,7143508,7143498,9398776,9398776,9395128,9388360,9388360,9388122,9388122,9385503,9374915,9374915,9365455,9365164,9360671,9360504,9359207,9359207,9359207,9356761,9348641,9346670,9346670,9346670,9341173,9333253,9329584,9329584,9329584,9322300,9322154,9314969,9314314,9314314,9311122,9291051,9280931,9269413,9254988,9254988,9254988,9247242,9238184,9236576,9236576,9235724,9203494,9192941,9182751,9179184,9155138,9135656,9094573,9071854,9024090,9013294,9005358,8956062,8892143,8862419,8855074,8853360,8816166,8810566,8764779,8748698,8689359,8670507,8664272,8608682,8580280,8530309,8525545,8525545,8525083,8521837,8459364,8430691,8413435,8360105,8244650,8217081,8217081,8217081,8144766,8135976,8126176,8106141,8104292,8098779,8097185,8073712,8037041,8005466,7913524,7908827,7884793,7816993,7786504,7742746,7711141,7703086,7695703,7681405,7680905,7676341,7650711,7645004,7627891,7605271,7601341,7594611,7587033,7582174,7579271,7543726,7505908,7484343,7440952,7363393,7352448,7341618,7338925,7332573,7332573,7332573,7320784,7302933,7287917,7282128,7234366,7183411,7124902,7085942,7056488,7053603,7010907,6990582,6988467,6971420,6952502,6948709,6906415,6899033,6884552,6873350,6872489,6841898,6814311,6653092,6566659,6566317,6534395,6525677,6506009,6474707,6445828,6429270,6428984,6423362,6409888,6404606,6402544,6396026,6386469,6285995,6274848,6262392,6218971,6216913,6209602,6209158,6209158,6173707,6031760,6005840,5980746,5897217,5857734,5838672,5823363,5776800,5773116,5752198,5678103,5633846,5617193,5535099,5512197,5506545,5398066,5389843,5379820,5374918,5247350,5242082,5198948,5190803,5180713,5161165,5156921,5143271,5087539,5067475,4999043,4986464,4973790,4883080,4878625,4831342,4827646,4811137,4779151,4762123,4710899,4691687,4656359,4652102,4652102,4651613,4552408,4272517,4256155,4253218,4225483,4200616,4172511,4171717,4166427,4159424,4092127,4082370,4061691,4051625,3986753,3946235,3919517,3918521,3918104,3915980,3913694,3909233,3907148,3903485,3901478,3901430,3894842,3894152,3891011,3889967,3883997,3881300,3875687,3867068,3866306,3863354,3849497,3845756,3838853,3784326,3712428,3632921,3564858,3549451,3545977,3503698,3478606,3463831,3448399,3439162,3420064,3407311,3395617,3339625,3337855,3261319,3234751,3219589,3192712,3177412,3158011,3157933,3156907,3155872,3147229,3146695,3144430,3142399,3140314,3140068,3139546,3139243,3138790,3138559,3137377,3133516,3130510,3127459,3126997,3124438,3122083,3121339,3115834,3114067,3112252,3111052,3110074,3104293,3103681,3102115,3099178,3098566,3096337,3095995,3095338,3095116,3094999,3094954,3094948,3094726,3094723,3094498,3094411,3094363,3094189,3093487,3092866,3092788,3092689,3092665,3092383,3091993,3091705,3091486,3091231,3090925,3090913,3090853,3090712,3090466,3090160,3090097,3089803,3089671,3089587,3089416,3089281,3088657,3088528,3087949,3087796,3087457,3087208,3086806,3086788,3086773,3086440,3086377,3086251,3086143,3085915,3085555,9375724,9375117,9374301,9372849,9371521,9370901,9368552,9368361,9366011,9365455,9365217,9365164,9364173,9362652,9362485,9362367,9360671,9360504,9360141,9359792,9357439,9356761,9354945,9342891,9341989,9341173,9337041,9334053,9304731,9155138,3867608)

        AND  it_ticket_enc.eliminado='N' AND it_ticket_enc.bet_status NOT IN ('T')";
    $time = time();
    print_r($sqlApuestasDeportivasUsuarioDiaCierre);

    $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);
    print_r(oldCount($data));
    //$comandos=array();

    $cont = 0;
    foreach ($data as $datanum) {

        if ($ActivacionSleepTime) {
            usleep(5 * 1000);
        }

        //$this->SlackVS->sendMessage("*CRON: (CronJobRollover) * " . $datanum->{'it_ticket_enc.ticket_id'}.' '.__DIR__);
        //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
        $amount = floatval($datanum->{'it_ticket_enc.vlr_apuesta'});

        $typeP = "SPORT";


        $comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/VerificarRollower.php", "SPORT " . $datanum->{'it_ticket_enc.ticket_id'} . " " . $datanum->{'it_ticket_enc.usuario_id'});


        if (($cont % 50) == 0) {
            //exec(implode(' ', $comandos), $output);
            //$comandos=array();
        }

        $cont++;

    }
    $this->SlackVS->sendMessage("*CRON: (CronJobRollover) Cant Casino: * " . $fechaL1 . ' - ' . date('Y-m-d H:i:s') . ' - ' . $cont . ' ' . __DIR__);

    if (oldCount($comandos) > 0) {
        //exec(implode(' ', $comandos), $output);
        //$comandos=array();
    }
    print_r('OK1');
    print_r(PHP_EOL);
}


$this->SlackVS->sendMessage("*CRON: (CronJobRollover) Cant Sports: * " . $fechaL1 . ' - ' . date('Y-m-d H:i:s') . ' - ' . $cont . ' ' . __DIR__);
$redisPrefixPrefix = 'F10BACK'; // Valor por defecto
$minute = date('i');

// Calcular el grupo basado en unidades de 10 minutos con el desplazamiento que especificaste
if ($minute % 10 == 1) {
    $redisPrefixPrefix = 'F10BACK';
} elseif ($minute % 10 == 2) {
    $redisPrefixPrefix = 'F11BACK';
} elseif ($minute % 10 == 3) {
    $redisPrefixPrefix = 'F12BACK';
} elseif ($minute % 10 == 4) {
    $redisPrefixPrefix = 'F13BACK';
} elseif ($minute % 10 == 5) {
    $redisPrefixPrefix = 'F14BACK';
} elseif ($minute % 10 == 6) {
    $redisPrefixPrefix = 'F15BACK';
} elseif ($minute % 10 == 7) {
    $redisPrefixPrefix = 'F16BACK';
} elseif ($minute % 10 == 8) {
    $redisPrefixPrefix = 'F17BACK';
} elseif ($minute % 10 == 9) {
    $redisPrefixPrefix = 'F18BACK';
} elseif ($minute % 10 == 0) {
    $redisPrefixPrefix = 'F19BACK';
} elseif ($minute % 10 == 1) {
    $redisPrefixPrefix = 'F20BACK';
}

$redisParam = ['ex' => 18000];
foreach ($comandos as $comando) {
    if ($redis != null) {
        $redisPrefix = $redisPrefixPrefix . "+UID" . $comando;

        $argv = explode($comando, ' ');

        $redis->set($redisPrefix, json_encode($argv), $redisParam);
    }

}


exit();


$redis = RedisConnectionTrait::getRedisInstance(true);
$comandos = array();

$datetie = date('s');

print_r('ENTRO1');

$BackgroundProcessVS = new BackgroundProcessVS();

$ActivacionOtros = true;
$ActivacionSleepTime = false;


if ($ActivacionOtros) {
    $debug = false;

    $BonoInterno = new BonoInterno();

    $sqlApuestasDeportivasUsuarioDiaCierre = "

select transjuego_log.transjuegolog_id,producto.subproveedor_id,transaccion_juego.usuario_id,transjuego_log.valor,
       producto.producto_id,
       producto.proveedor_id,
       producto.subproveedor_id,
       producto_mandante.prodmandante_id,
       subproveedor.tipo,
       
       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id
       
from transjuego_log
INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id

         INNER JOIN
     (select usuario_bono.usuario_id, bono_interno.bono_id, bd1.tipo tipo1, bd2.tipo tipo2, bd3.tipo tipo3
      from usuario_bono
               INNER JOIN bono_interno
                          ON (bono_interno.bono_id = usuario_bono.bono_id)
                INNER JOIN bono_detalle
                    ON (bono_interno.bono_id = bono_detalle.bono_id and bono_detalle.tipo = 'TIPOPRODUCTO' AND
                        bono_detalle.valor != 2)
               LEFT OUTER JOIN bono_detalle bd1
                               ON (bono_interno.bono_id = bd1.bono_id and bd1.tipo LIKE 'CONDGAME%')
               LEFT OUTER JOIN bono_detalle bd2
                               ON (bono_interno.bono_id = bd2.bono_id and bd2.tipo LIKE 'CONDSUBPROVIDER%')
               LEFT OUTER JOIN bono_detalle bd3
                               ON (bono_interno.bono_id = bd3.bono_id and bd3.tipo LIKE 'CONDPROVIDER%')
      where bono_interno.tipo in (2, 3)
         and usuario_bono.rollower_requerido > 0 and usuario_bono.fecha_crea <= '" . $fechaL2 . "'
        and usuario_bono.estado = 'A') x on (
         x.usuario_id = usuario_mandante.usuario_mandante
             AND ((tipo1 = CONCAT('CONDGAME', producto_mandante.prodmandante_id) or
                   tipo2 = CONCAT('CONDSUBPROVIDER', producto.subproveedor_id) or
                   tipo3 = CONCAT('CONDPROVIDER', producto.proveedor_id)) or
                  (tipo1 is null and tipo2 is null and tipo3 is null))
         )


    WHERE transjuego_log.fecha_crea >= '" . $fechaL1 . "' AND transjuego_log.fecha_crea < '" . $fechaL2 . "'
and transjuego_log.tipo LIKE 'DEBIT%' AND usuario_mandante.usuario_mandant
         in (7236069,8978264,9200700,7149439,7149107,7145765,8199848,9324241,8836977,7144478,8681341,8531009,7153106,7144340,7148724,7147217,7594818,7144448,7151467,7239952,7144148,7184284,9177482,7144314,7504726,7153132,7148225,7143934,7152399,7158288,7144516,7158044,7143830,7147521,7146810,7145200,7334772,7146983,8348449,7143570,7146977,9317076,7146365,8939455,7146479,8816077,8769165,7151167,7144978,7144940,7214485,7156198,7143554,9043417,8816231,7151911,8251538,7157170,7587889,7145813,7143956,7144620,7144852,7144154,7145180,7260512,7148903,7148445,9204422,9183584,7147759,7150905,7144800,7147715,7143962,9213561,7146917,7150241,7144774,7461682,7148931,8715382,7180283,8940301,8572809,7153440,7151867,7146255,7158406,8029821,9114516,7157722,7145971,7143658,7144672,7144766,7249971,7145817,7143750,7144470,7148511,9254092,7146431,7145652,7146590,7148633,7165719,7145496,7148103,7144768,7144560,8017862,7145236,7146630,7144748,7144976,7150139,7144022,9351287,7150267,7150993,7155788,7152473,7156888,7143764,8770513,8280386,7150443,7952785,9180025,7198825,9266179,7148748,7162303,7149433,7144116,7146353,7144698,9015689,7147369,7147003,7510048,7226388,7149229,7251621,7293109,7144068,7146612,7146967,7147577,7149829,7144472,7150589,7735724,7153819,7143656,7154303,7149753,7144576,7143890,7158216,9053799,7154413,7145921,7144854,8551083,7156156,7149031,7143674,9223699,7153408,7143846,7147671,7144528,7158130,9241270,7152387,7149919,8934005,7147309,7144782,7145144,7143640,7149477,7149565,7149629,7153803,7158714,7146075,7149473,8090453,7482841,7147823,7160733,8834563,9130279,7143630,7149973,7146035,9239668,7608790,7144684,7152814,9118281,7147379,7144212,9106716,7255156,7145186,7144822,7144930,8738267,8824694,7145312,7148007,7145488,7700780,7152367,7144524,8974563,7150947,7144306,9330436,7153583,7145789,7144162,7592427,9091437,7144298,7147799,8499396,7144414,9006755,7146696,7152267,7148129,7155007,7145697,7338877,7145969,9259367,7153903,7168705,8695353,7152495,7153573,7145074,7144986,8431188,7143910,7154365,7147797,7145556,7147921,9052207,7144832,7143944,7651936,7147097,7155339,7152690,7150681,7148195,9125398,7152568,7143882,7143592,9166016,7144344,9215944,7158976,9052940,7148700,7144586,8772453,7146013,7150221,7144458,8261531,7149865,7145484,7250628,7145574,8484760,9280718,7145654,7144052,7144446,7887763,7156142,7904511,7149221,7143624,9089350,7144326,7143740,7148399,7145687,7148271,9145333,8400637,8829867,8665962,7144384,7144202,7145258,7143530,8539645,9218017,7145406,7147073,7145168,8830971,7144690,7152534,7145811,7144948,7402859,8541783,7144368,7144104,7159088,7147113,9232730,7144120,7148387,7150257,9273126,7152457,7148839,7148347,7154923,8715286,7150289,7327323,7143508,7569060,7144476,7146863,7143485,7156276,7145711,7144750,7143912,7146770,7146572,7145520,8732314,7144674,9315282,7149337,9220317,9074179,7144608,7234568,7144796,7144242,7143582,7145941,7144526,7151079,7144928,7145444,7145070,8135409,7146666,7149675,7149085,9170706,7144702,7143866,7144328,7149743,9245436,7408029,7143638,7148533,7151491,7144352,7144744,8655380,7157802,7148967,7147151,7158000,7144186,9357175,7144040,7147321,7144140,7146853,7150449,7146592,7158962,7148692,8148656,7144792,7145364,7145572,7144746,7150717,7382189,7151493,7144030,7144132,7150571,7149619,7556677,7143550,9333083,7150265,7172956,7154275,7144302,7216635,7219284,7144430,8021141,7942003,7144492,7144856,7146604,7145741,7149699,7145807,7155900,7157500,8723180,7147683,7143998,7144438,7148281,7146253,7608306,7150829,7144042,7144388,7407659,7148627,7146652,9357041,7148161,7144394,7149183,7143514,7149503,7694987,7144642,7146183,7146269,7145755,7146187,7796870,7145102,7149323,7144112,7156204,7145660,9015949,7143574,7144634,7143650,7145612,7146335,8779620,8360004,7146788,7144798,7144288,7146125,7793332,7144522,7151659,7144936,7144518,7144920,7149761,7144614,7144402,7143702,7144346,8481663,7144872,8131164,9274384,7148245,8680690,7147125,7146568,7153012,7145156,7146800,7147905,7156446,7240135,7145841,7144810,7146562,7153577,7152123,8487962,7150311,7144604,7547647,7143820,7155091,7147021,8527005,7307666,9043242,7149297,7143914,7149735,7144356,7149159,7149961,7156150,7145498,7144026,8608820,7150751,7150259,7147743,8816494,7148623,9179379,8305221,7149933,7158778,8554731,7559949,7143822,7149591,8978573,8772777,8053018,7144966,7153801,7143498,7150879,8979711,7144606,7150135,7149413,9107947,7144760,7514847,7153104,7942463,7149979,7916310,7145534,7890302,7912558,7144380,7149577,7151903,7145991,8508065,7165587,7146560,7151193,7155333,7152630,9306003,7242901,8973446,7143552,7144658,7145012,7147915,7144754,9043955,7158398,7707810,7145050,9164759,7144018,7157316,7143644,8140072,7146389,8122908,7146379,7539602,7143780,7149595,7252702,7147165,8541120,7144406,7144836,7143886,7144332,7147437,7143864,7144416,7143660,7144624,7145749,7146622,7158792,8501107,7148463,7145951,7147007,7156304,7236065,7148279,7147983,7143900,7268067,7170746,8533345,7145899,9203329,9066818,7147067,7153086,8746554,7144828,7147545,9285332,7149387,7144172,7148728,7157066,7147993,8909782,7145606,7154807,7148881,7143542,9127299,7145879,7155698,7147193,7146905,7149449,7146227,8393079,7651464,7144012,7595296,8012359,7561792,9055261,7143748,7145476,7148895,7144830,7158732,7148813,7143758,8641571,9203234,8638545,7144482,7145648,7149843,7148603,8022554,7148107,7144428,7143926,7147231,8286689,7143596,7155089,7151117,7149321,7149697,7149951,7147251,7143510,8437350,7168660,9219507,7144694,7155263,7148415,7144670,7146177,7144912,7148287,7962632,7144432,7144720,7144858,7147459,7144274,7143948,7143652,7149351,7144106,8495045,7154149,7143802,7147185,7148969,7145598,7717409,7526535,7151869,9193211,7153004,7149277,9326111,7144282,7144464,7322646,8826830,8351745,7147445,7145222,7143548,7159238,174374,1940,7115366,9217412,368443,9109646,8541260,3285886,8003165,8803190,8110038,9046116,3625805,7762318,4972470,8536806,3056299,5646647,4162,2750725,6847381,7025077,9329057,7764771,9339807,9343809,8539466,881881,3522562,3150313,9320343,5265701,2874587,8260913,8871070,5859030,291502,9016160,8562960,2172884,5036401,193716,5994072,22672,95845,8782264,235644,851656,495914,163603,8805463,7753463,385242,212255,7317686,1021509,9323300,6817523,241415,6786208,2615296,4207747,239218,1070334,591113,1640036,7618315,3634,7539223,8746571,979324,164393,7589640,2752889,4981634,4893,8714090,9222137,291645,9094263,301680,8356121,620241,8053550,184536,8416385,5093815,8280735,3044614,4735062,8041603,4941496,2026316,9261665,3790920,26133,8968546,8721446,8532981,6630601,4285073,776771,9265435,7846817,251008,4217158,3810717,8813536,1837536,4059986,5078209,7905330,1229,2716147,6179701,1158245,6820235,9176726,2600896,871291,55058,6314647,300795,8128133,168221,7531275,9267421,5270930,611249,249662,4637213,98009,624857,1028,5474529,8834225,9145670,9154491,7758467,8019865,9051121,7215528,2837176,47104,129410,338132,3008423,7362919,5778147,8736134,3827774,5694841,4874056,161,255551,799,5638580,9212638,5692453,9653,327333,1819675,672441,7167977,111062,305660,2445686,9004020,7738821,8644504,4148596,6169450,7144410,9350576,7322646,7143636,7155007,7150901,7149473,7143930,7145941,9183584,8351745,8661531,7151467,7144448,7605762,7608306,8836977,7149855,7146503,7144388,7144530,7145807,7144614,7143944,7147189,7158216,7147049,7803502,8572809,9340677,9335094,7152387,9254092,7153440,7155165,9204422,7143748,7143998,7144940,7158044,7144182,7143656,8484760,7539602,8868007,7146917,8978264,7156648,7147683,7146630,7148003,9136218,7151867,7157030,8681341,7155788,7145626,8755601,7151167,8770513,9259367,7149619,8980833,9130279,9210302,7149307,8527005,7144472,7143882,7144154,7796870,7144782,9003431,7156304,7145755,7149117,7143890,8997254,7862312,7144560,7144266,7151193,7151335,8858934,7145921,7148903,7146335,7952785,7146297,7589735,7149753,7145488,8723180,7157570,9107947,7147411,8094635,7144016,7150693,7147671,7198825,7143604,7144946,9043955,8817487,7143674,7153394,7156204,7143846,9008780,7156964,9053799,7148333,7145296,8908147,8834563,7147763,7144468,7375710,7146013,7402859,7144732,7152630,8715286,7180218,7146255,7145414,7148839,7144670,9166016,7147915,7147165,7147577,8029821,7251621,7149277,7145274,7145564,7149031,9125398,7143582,8655380,8464036,7143758,7145250,7226388,7942463,7144274,7144856,7151357,7145777,7150717,9043242,7146355,7144750,9250146,7145400,8328159,9089350,7143648,7144972,8718196,7143910,7144746,7147151,7144414,9348714,7152409,7145498,7144476,7147007,9186560,7149337,7961263,8746554,7322593,8732314,7264445,7145148,7145284,7146973,7408029,7157072,7149241,7148967,7595296,7145406,7147567,7711509,7144798,9290276,7771880,7151911,7149183,7158320,7148161,7904511,7152534,7148445,7157444,9324241,7471760,7144778,7143578,7143710,7153517,7148859,7149027,7148245,7234568,8543003,7214073,8681092,7149565,9177206,7145598,7242901,7150753,7144328,7148748,7151491,8740186,7143818,9180585,7143660,8032674,7145971,7158778,7149503,7583815,7510048,7145558,8702289,7147391,7542222,7143716,7147375,7144892,7149323,7145773,7148129,7145480,7144620,7428522,7147379,7145534,7145775,9274537,8869159,7144642,7146967,7233562,7172956,7145444,7144068,9336071,7144936,9274384,8769165,9306003,7144346,7168660,7150891,8940301,7151389,9234292,7144132,7144438,7735795,9347907,7144094,7843909,7149961,7149457,7143914,7240135,7694987,8721761,7144442,7144288,7144948,7144974,7144242,7219284,7144326,7144466,7156320,7149735,9343526,7144624,7429949,7152457,7144332,7735724,8836690,7143652,7148461,7149697,8978573,7165719,7146389,7145719,7647510,7147113,7144042,7216635,7144432,8022554,7145741,7143498,8499396,7151931,7162645,7156156,7146770,7153212,7149159,7152473,7156210,9218017,7145789,8131164,7149843,7144352,7145654,7146177,7144792,7144104,7145070,7152924,9183127,2320961,9286454,8237409,8975320,9208037,8909620,9268376,6184183,8296672,8020773,5133322,8611504,2312276,8355223,7813378,7203301,5431690,9260965,4047455,9205774,8674420,8425797,7148241,8934005,7155588,7154807,7144086,8487962,7145404,7547647,7147185,7148347,7147193,7143552,7150589,8546699,7148700,7700780,9074179,7143650,7148627,7504726,7144558,9280718,7153573,7144120,7144838,7151087,7144014,7150879,8666703,7143640,9042225,7145504,7150443,8922253,9043417,7450481,7153801,7144684,8140072,9178425,7144522,7145574,7143780,8091142,7146361,7149823,9106637,7147107,7157316,8826830,7146540,7143530,7143762,7155900,7149699,7147009,7149933,7152592,7144384,7143542,7707810,7143958,7162344,7144252,7148623,7143580,7148279,7433150,9106716,7146989,7144428,7150215,7144646,8070660,7912558,9125947,7143702,8467449,7143802,7149979,7144912,7144784,7143926,9058907,7853965,7144008,7151069,7184284,8053018,9309462,7148603,7144528,9215944,8412217,7151171,8909782,7236069,9021835,7145729,7150221,7651464,7145572,7143624,9326111,7150265,7147759,8979711,7156780,8772453,8694032,8122908,7144116,7144586,7233813,7146762,9127299,7158292,7154275,7152568,9092337,7144394,7144212,7144140,7144022,9313689,7501444,7158714,9211655,8779620,9096701,7526535,9303211,7144828,9114516,9033983,9341714,7150617,7382189,7147461,7144966,7164477,7145811,7144172,7146800,7148387,7144368,7145476,7144810,9315282,7145102,9023199,7144720,7145418,7145749,8829867,7145360,7145392,7657245,7143548,7143866,8305221,7144478,7144836,7147067,7158792,7167175,8481663,7143658,7158732,7145612,7147293,7144608,7144830,7149761,9203234,7149593,7149687,7144156,8772777,9164759,9179327,7144264,8470211,7144740,7158766,7153004,7145620,7152499,7940150,7145608,7146975,7144492,7159238,7338877,9015689,7146604,7148007,7145879,8816231,7150515,9015949,7157170,7148728,7143822,9219507,7746531,7144314,7147231,9075223,7144576,7146187,7147823,7149675,7149477,7147773,7150025,7144604,7144660,7144822,7145709,7148425,7156142,9277202,7144854,7154915,7149229,7147545,7146863,7144852,7143900,7153132,7594818,7144148,7144112,7144430,7148511,7143886,8635194,7150829,7147177,7147125,7147217,7144634,7144774,7143508,7144550,7144694,8531009,7149581,8973446,7151665,9054466,7793332,9052207,7144518,9192487)


";
    print_r($sqlApuestasDeportivasUsuarioDiaCierre);
    $time = time();

    $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

    print_r(oldCount($data));
    $cont = 0;
    foreach ($data as $datanum) {

        if ($datanum->{'usuario_mandante.usuario_mandante'} == '9002180') {
        }

        //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
        $amount = floatval($datanum->{'transjuego_log.valor'});

        $typeP = "CASINO";

        if ($datanum->{'subproveedor.tipo'} == 'CASINO') {
            $typeP = "CASINO";
        } elseif ($datanum->{'subproveedor.tipo'} == 'LIVECASINO') {
            $typeP = "LIVECASINO";
        } elseif ($datanum->{'subproveedor.tipo'} == 'VIRTUAL') {
            $typeP = "VIRTUALES";
        }

        $comandos[] = $BackgroundProcessVS->getCommandExecute("/home/home2/backendprodfinal/api/src/integrations/casino/VerificarRollower.php", "CASINO '' " . $datanum->{'usuario_mandante.usuario_mandante'} . " " . $datanum->{'transjuego_log.transjuegolog_id'});

        if (($cont % 50) == 0) {
            //exec(implode(' ', $comandos), $output);
            //$comandos=array();
        }

        $cont++;

    }

    if (oldCount($comandos) > 0) {
        //exec(implode(' ', $comandos), $output);
        //$comandos=array();
    }
}

print_r('OK2');
print_r(PHP_EOL);
if ($ActivacionOtros) {

    $BonoInterno = new BonoInterno();

    $sqlApuestasDeportivasUsuarioDiaCierre = "
        SELECT
        it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.usuario_id
        FROM it_ticket_enc
            
    INNER JOIN
     (select usuario_bono.usuario_id
      from usuario_bono
               INNER JOIN bono_interno
                          ON (bono_interno.bono_id = usuario_bono.bono_id)
                INNER JOIN bono_detalle
                    ON (bono_interno.bono_id = bono_detalle.bono_id and bono_detalle.tipo = 'TIPOPRODUCTO' AND
                        bono_detalle.valor = 2)
      where bono_interno.tipo in (2, 3)
        and usuario_bono.estado = 'A' and usuario_bono.rollower_requerido > 0 and usuario_bono.fecha_crea <= '" . $fechaL2 . "'
        ) x on x.usuario_id = it_ticket_enc.usuario_id
            
        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
        INNER JOIN registro ON (registro.usuario_id = it_ticket_enc.usuario_id)
        WHERE (it_ticket_enc.fecha_cierre_time) >= '" . $fechaL1 . "' AND (it_ticket_enc.fecha_cierre_time) < '" . $fechaL2 . "' AND  it_ticket_enc.eliminado='N' AND it_ticket_enc.bet_status NOT IN ('T')
         AND usuario.usuario_id
         in (7236069,8978264,9200700,7149439,7149107,7145765,8199848,9324241,8836977,7144478,8681341,8531009,7153106,7144340,7148724,7147217,7594818,7144448,7151467,7239952,7144148,7184284,9177482,7144314,7504726,7153132,7148225,7143934,7152399,7158288,7144516,7158044,7143830,7147521,7146810,7145200,7334772,7146983,8348449,7143570,7146977,9317076,7146365,8939455,7146479,8816077,8769165,7151167,7144978,7144940,7214485,7156198,7143554,9043417,8816231,7151911,8251538,7157170,7587889,7145813,7143956,7144620,7144852,7144154,7145180,7260512,7148903,7148445,9204422,9183584,7147759,7150905,7144800,7147715,7143962,9213561,7146917,7150241,7144774,7461682,7148931,8715382,7180283,8940301,8572809,7153440,7151867,7146255,7158406,8029821,9114516,7157722,7145971,7143658,7144672,7144766,7249971,7145817,7143750,7144470,7148511,9254092,7146431,7145652,7146590,7148633,7165719,7145496,7148103,7144768,7144560,8017862,7145236,7146630,7144748,7144976,7150139,7144022,9351287,7150267,7150993,7155788,7152473,7156888,7143764,8770513,8280386,7150443,7952785,9180025,7198825,9266179,7148748,7162303,7149433,7144116,7146353,7144698,9015689,7147369,7147003,7510048,7226388,7149229,7251621,7293109,7144068,7146612,7146967,7147577,7149829,7144472,7150589,7735724,7153819,7143656,7154303,7149753,7144576,7143890,7158216,9053799,7154413,7145921,7144854,8551083,7156156,7149031,7143674,9223699,7153408,7143846,7147671,7144528,7158130,9241270,7152387,7149919,8934005,7147309,7144782,7145144,7143640,7149477,7149565,7149629,7153803,7158714,7146075,7149473,8090453,7482841,7147823,7160733,8834563,9130279,7143630,7149973,7146035,9239668,7608790,7144684,7152814,9118281,7147379,7144212,9106716,7255156,7145186,7144822,7144930,8738267,8824694,7145312,7148007,7145488,7700780,7152367,7144524,8974563,7150947,7144306,9330436,7153583,7145789,7144162,7592427,9091437,7144298,7147799,8499396,7144414,9006755,7146696,7152267,7148129,7155007,7145697,7338877,7145969,9259367,7153903,7168705,8695353,7152495,7153573,7145074,7144986,8431188,7143910,7154365,7147797,7145556,7147921,9052207,7144832,7143944,7651936,7147097,7155339,7152690,7150681,7148195,9125398,7152568,7143882,7143592,9166016,7144344,9215944,7158976,9052940,7148700,7144586,8772453,7146013,7150221,7144458,8261531,7149865,7145484,7250628,7145574,8484760,9280718,7145654,7144052,7144446,7887763,7156142,7904511,7149221,7143624,9089350,7144326,7143740,7148399,7145687,7148271,9145333,8400637,8829867,8665962,7144384,7144202,7145258,7143530,8539645,9218017,7145406,7147073,7145168,8830971,7144690,7152534,7145811,7144948,7402859,8541783,7144368,7144104,7159088,7147113,9232730,7144120,7148387,7150257,9273126,7152457,7148839,7148347,7154923,8715286,7150289,7327323,7143508,7569060,7144476,7146863,7143485,7156276,7145711,7144750,7143912,7146770,7146572,7145520,8732314,7144674,9315282,7149337,9220317,9074179,7144608,7234568,7144796,7144242,7143582,7145941,7144526,7151079,7144928,7145444,7145070,8135409,7146666,7149675,7149085,9170706,7144702,7143866,7144328,7149743,9245436,7408029,7143638,7148533,7151491,7144352,7144744,8655380,7157802,7148967,7147151,7158000,7144186,9357175,7144040,7147321,7144140,7146853,7150449,7146592,7158962,7148692,8148656,7144792,7145364,7145572,7144746,7150717,7382189,7151493,7144030,7144132,7150571,7149619,7556677,7143550,9333083,7150265,7172956,7154275,7144302,7216635,7219284,7144430,8021141,7942003,7144492,7144856,7146604,7145741,7149699,7145807,7155900,7157500,8723180,7147683,7143998,7144438,7148281,7146253,7608306,7150829,7144042,7144388,7407659,7148627,7146652,9357041,7148161,7144394,7149183,7143514,7149503,7694987,7144642,7146183,7146269,7145755,7146187,7796870,7145102,7149323,7144112,7156204,7145660,9015949,7143574,7144634,7143650,7145612,7146335,8779620,8360004,7146788,7144798,7144288,7146125,7793332,7144522,7151659,7144936,7144518,7144920,7149761,7144614,7144402,7143702,7144346,8481663,7144872,8131164,9274384,7148245,8680690,7147125,7146568,7153012,7145156,7146800,7147905,7156446,7240135,7145841,7144810,7146562,7153577,7152123,8487962,7150311,7144604,7547647,7143820,7155091,7147021,8527005,7307666,9043242,7149297,7143914,7149735,7144356,7149159,7149961,7156150,7145498,7144026,8608820,7150751,7150259,7147743,8816494,7148623,9179379,8305221,7149933,7158778,8554731,7559949,7143822,7149591,8978573,8772777,8053018,7144966,7153801,7143498,7150879,8979711,7144606,7150135,7149413,9107947,7144760,7514847,7153104,7942463,7149979,7916310,7145534,7890302,7912558,7144380,7149577,7151903,7145991,8508065,7165587,7146560,7151193,7155333,7152630,9306003,7242901,8973446,7143552,7144658,7145012,7147915,7144754,9043955,7158398,7707810,7145050,9164759,7144018,7157316,7143644,8140072,7146389,8122908,7146379,7539602,7143780,7149595,7252702,7147165,8541120,7144406,7144836,7143886,7144332,7147437,7143864,7144416,7143660,7144624,7145749,7146622,7158792,8501107,7148463,7145951,7147007,7156304,7236065,7148279,7147983,7143900,7268067,7170746,8533345,7145899,9203329,9066818,7147067,7153086,8746554,7144828,7147545,9285332,7149387,7144172,7148728,7157066,7147993,8909782,7145606,7154807,7148881,7143542,9127299,7145879,7155698,7147193,7146905,7149449,7146227,8393079,7651464,7144012,7595296,8012359,7561792,9055261,7143748,7145476,7148895,7144830,7158732,7148813,7143758,8641571,9203234,8638545,7144482,7145648,7149843,7148603,8022554,7148107,7144428,7143926,7147231,8286689,7143596,7155089,7151117,7149321,7149697,7149951,7147251,7143510,8437350,7168660,9219507,7144694,7155263,7148415,7144670,7146177,7144912,7148287,7962632,7144432,7144720,7144858,7147459,7144274,7143948,7143652,7149351,7144106,8495045,7154149,7143802,7147185,7148969,7145598,7717409,7526535,7151869,9193211,7153004,7149277,9326111,7144282,7144464,7322646,8826830,8351745,7147445,7145222,7143548,7159238,174374,1940,7115366,9217412,368443,9109646,8541260,3285886,8003165,8803190,8110038,9046116,3625805,7762318,4972470,8536806,3056299,5646647,4162,2750725,6847381,7025077,9329057,7764771,9339807,9343809,8539466,881881,3522562,3150313,9320343,5265701,2874587,8260913,8871070,5859030,291502,9016160,8562960,2172884,5036401,193716,5994072,22672,95845,8782264,235644,851656,495914,163603,8805463,7753463,385242,212255,7317686,1021509,9323300,6817523,241415,6786208,2615296,4207747,239218,1070334,591113,1640036,7618315,3634,7539223,8746571,979324,164393,7589640,2752889,4981634,4893,8714090,9222137,291645,9094263,301680,8356121,620241,8053550,184536,8416385,5093815,8280735,3044614,4735062,8041603,4941496,2026316,9261665,3790920,26133,8968546,8721446,8532981,6630601,4285073,776771,9265435,7846817,251008,4217158,3810717,8813536,1837536,4059986,5078209,7905330,1229,2716147,6179701,1158245,6820235,9176726,2600896,871291,55058,6314647,300795,8128133,168221,7531275,9267421,5270930,611249,249662,4637213,98009,624857,1028,5474529,8834225,9145670,9154491,7758467,8019865,9051121,7215528,2837176,47104,129410,338132,3008423,7362919,5778147,8736134,3827774,5694841,4874056,161,255551,799,5638580,9212638,5692453,9653,327333,1819675,672441,7167977,111062,305660,2445686,9004020,7738821,8644504,4148596,6169450,7144410,9350576,7322646,7143636,7155007,7150901,7149473,7143930,7145941,9183584,8351745,8661531,7151467,7144448,7605762,7608306,8836977,7149855,7146503,7144388,7144530,7145807,7144614,7143944,7147189,7158216,7147049,7803502,8572809,9340677,9335094,7152387,9254092,7153440,7155165,9204422,7143748,7143998,7144940,7158044,7144182,7143656,8484760,7539602,8868007,7146917,8978264,7156648,7147683,7146630,7148003,9136218,7151867,7157030,8681341,7155788,7145626,8755601,7151167,8770513,9259367,7149619,8980833,9130279,9210302,7149307,8527005,7144472,7143882,7144154,7796870,7144782,9003431,7156304,7145755,7149117,7143890,8997254,7862312,7144560,7144266,7151193,7151335,8858934,7145921,7148903,7146335,7952785,7146297,7589735,7149753,7145488,8723180,7157570,9107947,7147411,8094635,7144016,7150693,7147671,7198825,7143604,7144946,9043955,8817487,7143674,7153394,7156204,7143846,9008780,7156964,9053799,7148333,7145296,8908147,8834563,7147763,7144468,7375710,7146013,7402859,7144732,7152630,8715286,7180218,7146255,7145414,7148839,7144670,9166016,7147915,7147165,7147577,8029821,7251621,7149277,7145274,7145564,7149031,9125398,7143582,8655380,8464036,7143758,7145250,7226388,7942463,7144274,7144856,7151357,7145777,7150717,9043242,7146355,7144750,9250146,7145400,8328159,9089350,7143648,7144972,8718196,7143910,7144746,7147151,7144414,9348714,7152409,7145498,7144476,7147007,9186560,7149337,7961263,8746554,7322593,8732314,7264445,7145148,7145284,7146973,7408029,7157072,7149241,7148967,7595296,7145406,7147567,7711509,7144798,9290276,7771880,7151911,7149183,7158320,7148161,7904511,7152534,7148445,7157444,9324241,7471760,7144778,7143578,7143710,7153517,7148859,7149027,7148245,7234568,8543003,7214073,8681092,7149565,9177206,7145598,7242901,7150753,7144328,7148748,7151491,8740186,7143818,9180585,7143660,8032674,7145971,7158778,7149503,7583815,7510048,7145558,8702289,7147391,7542222,7143716,7147375,7144892,7149323,7145773,7148129,7145480,7144620,7428522,7147379,7145534,7145775,9274537,8869159,7144642,7146967,7233562,7172956,7145444,7144068,9336071,7144936,9274384,8769165,9306003,7144346,7168660,7150891,8940301,7151389,9234292,7144132,7144438,7735795,9347907,7144094,7843909,7149961,7149457,7143914,7240135,7694987,8721761,7144442,7144288,7144948,7144974,7144242,7219284,7144326,7144466,7156320,7149735,9343526,7144624,7429949,7152457,7144332,7735724,8836690,7143652,7148461,7149697,8978573,7165719,7146389,7145719,7647510,7147113,7144042,7216635,7144432,8022554,7145741,7143498,8499396,7151931,7162645,7156156,7146770,7153212,7149159,7152473,7156210,9218017,7145789,8131164,7149843,7144352,7145654,7146177,7144792,7144104,7145070,7152924,9183127,2320961,9286454,8237409,8975320,9208037,8909620,9268376,6184183,8296672,8020773,5133322,8611504,2312276,8355223,7813378,7203301,5431690,9260965,4047455,9205774,8674420,8425797,7148241,8934005,7155588,7154807,7144086,8487962,7145404,7547647,7147185,7148347,7147193,7143552,7150589,8546699,7148700,7700780,9074179,7143650,7148627,7504726,7144558,9280718,7153573,7144120,7144838,7151087,7144014,7150879,8666703,7143640,9042225,7145504,7150443,8922253,9043417,7450481,7153801,7144684,8140072,9178425,7144522,7145574,7143780,8091142,7146361,7149823,9106637,7147107,7157316,8826830,7146540,7143530,7143762,7155900,7149699,7147009,7149933,7152592,7144384,7143542,7707810,7143958,7162344,7144252,7148623,7143580,7148279,7433150,9106716,7146989,7144428,7150215,7144646,8070660,7912558,9125947,7143702,8467449,7143802,7149979,7144912,7144784,7143926,9058907,7853965,7144008,7151069,7184284,8053018,9309462,7148603,7144528,9215944,8412217,7151171,8909782,7236069,9021835,7145729,7150221,7651464,7145572,7143624,9326111,7150265,7147759,8979711,7156780,8772453,8694032,8122908,7144116,7144586,7233813,7146762,9127299,7158292,7154275,7152568,9092337,7144394,7144212,7144140,7144022,9313689,7501444,7158714,9211655,8779620,9096701,7526535,9303211,7144828,9114516,9033983,9341714,7150617,7382189,7147461,7144966,7164477,7145811,7144172,7146800,7148387,7144368,7145476,7144810,9315282,7145102,9023199,7144720,7145418,7145749,8829867,7145360,7145392,7657245,7143548,7143866,8305221,7144478,7144836,7147067,7158792,7167175,8481663,7143658,7158732,7145612,7147293,7144608,7144830,7149761,9203234,7149593,7149687,7144156,8772777,9164759,9179327,7144264,8470211,7144740,7158766,7153004,7145620,7152499,7940150,7145608,7146975,7144492,7159238,7338877,9015689,7146604,7148007,7145879,8816231,7150515,9015949,7157170,7148728,7143822,9219507,7746531,7144314,7147231,9075223,7144576,7146187,7147823,7149675,7149477,7147773,7150025,7144604,7144660,7144822,7145709,7148425,7156142,9277202,7144854,7154915,7149229,7147545,7146863,7144852,7143900,7153132,7594818,7144148,7144112,7144430,7148511,7143886,8635194,7150829,7147177,7147125,7147217,7144634,7144774,7143508,7144550,7144694,8531009,7149581,8973446,7151665,9054466,7793332,9052207,7144518,9192487)
        
        ";
    $time = time();
    print_r($sqlApuestasDeportivasUsuarioDiaCierre);

    $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);
    print_r(oldCount($data));
    //$comandos=array();

    $cont = 0;
    foreach ($data as $datanum) {

        if ($ActivacionSleepTime) {
            usleep(5 * 1000);
        }

        //$this->SlackVS->sendMessage("*CRON: (CronJobRollover) * " . $datanum->{'it_ticket_enc.ticket_id'}.' '.__DIR__);
        //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
        $amount = floatval($datanum->{'it_ticket_enc.vlr_apuesta'});

        $typeP = "SPORT";


        $comandos[] = $BackgroundProcessVS->getCommandExecute("/home/home2/backendprodfinal/api/src/integrations/casino/VerificarRollower.php", "SPORT " . $datanum->{'it_ticket_enc.ticket_id'} . " " . $datanum->{'it_ticket_enc.usuario_id'});

        if (($cont % 50) == 0) {
            //exec(implode(' ', $comandos), $output);
            //$comandos=array();
        }

        $cont++;

    }

    if (oldCount($comandos) > 0) {
        //exec(implode(' ', $comandos), $output);
        //$comandos=array();
    }
    print_r('OK1');
    print_r(PHP_EOL);
}

$redisPrefixPrefix = 'F10BACK'; // Valor por defecto
$minute = date('i');

// Calcular el grupo basado en unidades de 10 minutos con el desplazamiento que especificaste
if ($minute % 10 == 1) {
    $redisPrefixPrefix = 'F10BACK';
} elseif ($minute % 10 == 2) {
    $redisPrefixPrefix = 'F11BACK';
} elseif ($minute % 10 == 3) {
    $redisPrefixPrefix = 'F12BACK';
} elseif ($minute % 10 == 4) {
    $redisPrefixPrefix = 'F13BACK';
} elseif ($minute % 10 == 5) {
    $redisPrefixPrefix = 'F14BACK';
} elseif ($minute % 10 == 6) {
    $redisPrefixPrefix = 'F15BACK';
} elseif ($minute % 10 == 7) {
    $redisPrefixPrefix = 'F16BACK';
} elseif ($minute % 10 == 8) {
    $redisPrefixPrefix = 'F17BACK';
} elseif ($minute % 10 == 9) {
    $redisPrefixPrefix = 'F18BACK';
} elseif ($minute % 10 == 0) {
    $redisPrefixPrefix = 'F19BACK';
} elseif ($minute % 10 == 1) {
    $redisPrefixPrefix = 'F20BACK';
}

$redisParam = ['ex' => 18000];
foreach ($comandos as $comando) {
    if ($redis != null) {
        $redisPrefix = $redisPrefixPrefix . "+UID" . $comando;
        print_r($redisPrefix);

        $argv = explode($comando, ' ');

        $redis->set($redisPrefix, json_encode($argv), $redisParam);
    }

}
print_r('OK3');
print_r(PHP_EOL);


exit();

$sql = "
            SELECT usuario.usuario_id
            FROM registro
            INNER JOIN usuario ON registro.usuario_id = usuario.usuario_id
            WHERE                  
                
                usuario.mandante =8 AND

                usuario.fecha_crea >= '2025-03-05 10:11:00' 
              AND usuario.fecha_crea < '2025-03-05 11:44:00'
            ";

$data = $BonoInterno->execQuery('', $sql);

foreach ($data as $datanum) {

    $usuarioId = $datanum->{'usuario.usuario_id'};

    processRegister($usuarioId);
}

function Unaccent($string)
{
    return preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
}

function encrypt($data, $encryption_key = "")
{
    $passEncryt = 'li1296-151.members.linode.com|3232279913';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', $passEncryt, 0, $iv);
    $encrypted_string = str_replace("/", "vSfTp", $encrypted_string);
    return $encrypted_string;
}

function processRegister($usuarioId)
{

    $user = $usuarioId;

    $Usuario = new \Backend\dto\Usuario($user);
    $Registro = new \Backend\dto\Registro('', $Usuario->usuarioId);
    if ($Usuario->mandante == '0') {

        try {
            $campaignName = '';
            $campaignSource = '';
            $campaignContent = '';

            $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

            $SitioTracking = new SitioTracking();
            $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
            $sitiosTracking = json_decode($sitiosTracking);

            $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

            if ($tvalue != '') {
                $tvalue = json_decode($tvalue);

                if ($tvalue->vs_utm_campaign != '') {
                    $campaignName = $tvalue->vs_utm_campaign;
                }
                if ($tvalue->vs_utm_source != '') {
                    $campaignSource = $tvalue->vs_utm_source;
                }
                if ($tvalue->vs_utm_content != '') {
                    $campaignContent = $tvalue->vs_utm_content;
                }

            }

            if ($campaignName != '' && strpos($campaignName, '_BQ_') !== false) {
                $campaignName = explode('_BQ_', $campaignName)[1];

            }


        } catch (Exception $e) {

        }
        try {
            $Pais = new Pais($Usuario->paisId);


            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper('https://' . ('ctag.containermedia.net/api/s2s/secure/?id=64beb6049b64263a88b18743&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . Unaccent(str_replace(' ', '+', $Pais->paisNom)) . '&campaign=' . $campaignName));

            // Configurar opciones
            $curl->setOptionsArray([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = $curl->execute();


        } catch (Exception $e) {

        }
    }
    if ($Usuario->mandante == '8') {

        try {
            $Pais = new Pais($Usuario->paisId);

            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper(
                'https://ctag.containermedia.net/api/s2s/secure/?id=64da9663633b177007960afb&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $Pais->paisNom
            );

            // Configurar opciones
            $curl->setOptionsArray([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = $curl->execute();


        } catch (Exception $e) {

        }
    }

    if ($Usuario->mandante == '23') {

        try {
            $Pais = new Pais($Usuario->paisId);


            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper(
                'https://ctag.containermedia.net/api/s2s/secure/?id=65e71e4b74551688a6435fbb&uuid=' . $Usuario->usuarioId . '&afid=' . $Registro->afiliadorId . '&country=' . $Pais->paisNom
            );

            // Configurar opciones
            $curl->setOptionsArray([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = $curl->execute();

        } catch (Exception $e) {

        }
    }


    if ($Usuario->mandante == '0') {
        $Pais = new Pais($Usuario->paisId);
        $ENCRYPTION_KEY = "D!@#$%^&*";

        $user_id = $Usuario->usuarioId;
        $email = $Usuario->login;
        $first_name = urlencode(trim(str_replace(" ", "", $Registro->nombre1)));
        $last_name = urlencode(trim(str_replace(" ", "", $Registro->apellido1)));
        $phone = urlencode(trim(str_replace(" ", "", $Registro->celular)));
        $affiliate = $Registro->afiliadorId;
        $btag = urlencode(encrypt($affiliate . "__" . $Registro->linkId, $ENCRYPTION_KEY));

        $country = $Pais->iso;


        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper(
            'https://load.t.doradobet.com/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate
        );

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POSTFIELDS => 'user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            )
        ]);

        $response = $curl->execute();


    }
    if ($Usuario->mandante == '8') {
        $Pais = new Pais($Usuario->paisId);
        $ENCRYPTION_KEY = "D!@#$%^&*";

        $user_id = $Usuario->usuarioId;
        $email = $Usuario->login;
        $first_name = urlencode(trim(str_replace(" ", "", $Registro->nombre1)));
        $last_name = urlencode(trim(str_replace(" ", "", $Registro->apellido1)));
        $phone = urlencode(trim(str_replace(" ", "", $Registro->celular)));
        $affiliate = $Registro->afiliadorId;
        $btag = urlencode(encrypt($affiliate . "__" . $Registro->linkId, $ENCRYPTION_KEY));

        $country = $Pais->iso;


        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper(
            'https://load.t.ecuabet.com/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate
        );

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POSTFIELDS => 'user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            )
        ]);

        $response = $curl->execute();


    }

    if ($Usuario->mandante == '23') {
        $Pais = new Pais($Usuario->paisId);
        $ENCRYPTION_KEY = "D!@#$%^&*";

        $user_id = $Usuario->usuarioId;
        $email = $Usuario->login;
        $first_name = urlencode(trim(str_replace(" ", "", $Registro->nombre1)));
        $last_name = urlencode(trim(str_replace(" ", "", $Registro->apellido1)));
        $phone = urlencode(trim(str_replace(" ", "", $Registro->celular)));
        $affiliate = $Registro->afiliadorId;
        $btag = urlencode(encrypt($affiliate . "__" . $Registro->linkId, $ENCRYPTION_KEY));

        $country = $Pais->iso;


        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper(
            'https://load.t.paniplay.com/lead-complete?user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate
        );

        // Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POSTFIELDS => 'user_id=' . $user_id . '&email=' . $email . '&first_name=' . $first_name . '&last_name=' . $last_name . '&phone=' . $phone . '&country=' . $country . '&btag=' . $btag . '&affiliate=' . $affiliate,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            )
        ]);

        $response = $curl->execute();

    }

}

exit();
$Usuario = new Usuario(886);
print_r($Usuario);
exit();

$redis = RedisConnectionTrait::getRedisInstance(true);

// Obtener todas las claves que coinciden con el patrón
$keys = $redis->keys('ADMIN2F3BACK*');
$keys = array_slice($keys, 0, 10000); // Obtiene solo las primeras 10,000 claves

//print_r($keys);
$arrayCampaign = array();
$tiempos = [];
$tiempos2 = [];

foreach ($keys as $key) {
    $inicio = microtime(true);

    if (strpos($key, 'AgregarBonoBackground') !== false) {
        $argg = explode('+', $key);
        $UsuarioId = str_replace('UID', '', $argg[2]);
        $BonoId = $argg[3];
        $CampaignID = $argg[4];

        if ($arrayCampaign[$CampaignID] == null) {
            $arrayCampaign[$CampaignID] = 0;
        }

        exec("php -f  /home/home2/backendprodfinal/api/cron/agregarBonoExec.php " . $UsuarioId . " " . $BonoId . " " . $CampaignID . " " . $logID . " " . ($arrayCampaign[$CampaignID] * 1) . " > /dev/null &");

        $arrayCampaign[$CampaignID]++;


    } else {
        // Obtener el valor de la clave
        $value = $redis->get($key);

        $value = json_decode($value, true);


    }
    if (strpos($key, 'AgregarMensajeTextoBackground') !== false) {
        $argv = $value;
        $UsuarioId = $argv[1];
        $TemplateId = $argv[2];
        $CampaignId = $argv[3];
        exec("php -f " . __DIR__ . "/agregarEnviarSMS.php " . $UsuarioId . "  " . $TemplateId . " > /dev/null &");


    }
    $fin = microtime(true);
    $tiempos[$key] = $fin - $inicio;

    // Eliminar la clave
    //echo "Clave eliminada: $key\n";
    $redis->del($key);
    $fin = microtime(true);
    $tiempos2[$key] = $fin - $inicio;

}
//print_r($tiempos);
print_r(PHP_EOL);
print_r(PHP_EOL);
//print_r($tiempos2);
// Identificar la iteración más lenta
$maxTiempo = max($tiempos);
$indiceMasLento = array_search($maxTiempo, $tiempos);

echo "La iteración más lenta fue la número $indiceMasLento con un tiempo de " . number_format($maxTiempo, 6) . " segundos.\n";

exit();

$usuarios = array();
$bonos = array();

foreach ($usuarios as $key => $usuario) {
    $Usuario = new Usuario($usuario);
    $Registro = new Registro('', $Usuario->usuarioId);
    $bonoId = $bonos[$key];

    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $Transaction = $BonoDetalleMySqlDAO->getTransaction();

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

    $BonoInterno = new BonoInterno();

    $detalles = json_decode(json_encode($detalles));

//print_r($bonoId);
    $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction, false, true);
    $Transaction->commit();
    print_r($key);
    print_r($responseBonus);
}


exit();

$dsatz = array();

$dsatz2 = array();
foreach ($dsatz as $key => $number) {

    $UsuarioID = $number;
    $saldodeposito = -$dsatz2[$key];

    try {
        $mandante = $_SESSION['mandante'];
        $tipo = 'E';

        if (($saldodeposito != '' && floatval($saldodeposito) > 0) || ($saldodeposito != '' && floatval($saldodeposito) < 0) || ($saldoretiro != '' && floatval($saldoretiro) > 0) || ($saldoretiro != '' && floatval($saldoretiro) < 0)) {


            $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO();

            $Transaction = $SaldoUsuonlineAjusteMysql->getTransaction();


            if ($UsuarioID != '' && is_numeric($UsuarioID)) {
                $Usuario = new Usuario($UsuarioID);
                $paisId = $Usuario->paisId;
                $login = $Usuario->login;
                $mandante = $Usuario->mandante;

            } else {
                $Usuario = new Usuario("", $login, '0', $mandante, $paisId);
            }
            $UserId = $Usuario->usuarioId;


            if ($saldodeposito != '' && floatval($saldodeposito) > 0) {
                $TypeBalance = 0;
                $Amount = floatval($saldodeposito);

                $Usuario->credit($Amount, $Transaction);

                $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                $SaldoUsuonlineAjuste->setTipoId($tipo);
                $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                $SaldoUsuonlineAjuste->setValor($Amount);
                $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                $SaldoUsuonlineAjuste->setUsucreaId(0);
                $SaldoUsuonlineAjuste->setSaldoAnt(0);
                $SaldoUsuonlineAjuste->setObserv('Ajuste de Saldo por duplicidad de bonos');
                if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                    $SaldoUsuonlineAjuste->setMotivoId(0);
                }
                $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


            }


            if ($saldoretiro != '' && floatval($saldoretiro) > 0) {
                $TypeBalance = 1;
                $Amount = floatval($saldoretiro);

                $Usuario->creditWin($Amount, $Transaction);

                $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                $SaldoUsuonlineAjuste->setTipoId($tipo);
                $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                $SaldoUsuonlineAjuste->setValor($Amount);
                $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                $SaldoUsuonlineAjuste->setUsucreaId(0);
                $SaldoUsuonlineAjuste->setSaldoAnt(0);
                $SaldoUsuonlineAjuste->setObserv('Ajuste de Saldo por duplicidad de bonos');
                if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                    $SaldoUsuonlineAjuste->setMotivoId(0);
                }
                $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


            }

            if ($saldodeposito != '' && floatval($saldodeposito) < 0) {
                $TypeBalance = 0;
                $tipo = 'S';
                $Amount = floatval($saldodeposito);

                $Usuario->credit($Amount, $Transaction);

                $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                $SaldoUsuonlineAjuste->setTipoId($tipo);
                $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                $SaldoUsuonlineAjuste->setValor(-$Amount);
                $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                $SaldoUsuonlineAjuste->setUsucreaId(0);
                $SaldoUsuonlineAjuste->setSaldoAnt(0);
                $SaldoUsuonlineAjuste->setObserv('Ajuste de Saldo por duplicidad de bonos');
                if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                    $SaldoUsuonlineAjuste->setMotivoId(0);
                }
                $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


            }


            if ($saldoretiro != '' && floatval($saldoretiro) < 0) {
                $TypeBalance = 1;
                $tipo = 'S';
                $Amount = floatval($saldoretiro);

                $Usuario->creditWin($Amount, $Transaction);

                $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                $SaldoUsuonlineAjuste->setTipoId($tipo);
                $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                $SaldoUsuonlineAjuste->setValor($Amount);
                $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                $SaldoUsuonlineAjuste->setUsucreaId(0);
                $SaldoUsuonlineAjuste->setSaldoAnt(0);
                $SaldoUsuonlineAjuste->setObserv('Ajuste de Saldo por duplicidad de bonos');
                if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                    $SaldoUsuonlineAjuste->setMotivoId(0);
                }
                $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


            }

            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento($tipo);
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(15);
            $UsuarioHistorial->setValor($Amount);
            $UsuarioHistorial->setExternoId($ajusteId);

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

            $Transaction->commit();


        }


    } catch (Exception $e) {
        print_r($e);
    }

}


exit();


ini_set('memory_limit', '-1');
header('Content-Type: application/json');

print_r('Resultado');
print_r('Resultado');

date_default_timezone_set('America/Bogota');

$payload = escapeshellarg(json_encode([
    'url' => '',
    'options' => json_encode(array()),
    'info' => '{}',
    'response' => '',
    'execution_time' => 0,
    'created_at' => date('Y-m-d H:i:s')
]));
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

$command = "curl -X POST 'https://uruqgfsfodmdxlcysvuw.supabase.co/rest/v1/curl_logs' \
            -H 'Content-Type: application/json' \
            -H 'apikey: $supabase_key' \
            -H 'Authorization: Bearer $supabase_key' \
            -d $payload > /dev/null 2>&1 &";

print_r($command);

shell_exec($command);
print_r($command);
exit();

$ch = curl_init();

// URL para obtener la IP pública
$url = "https://api64.ipify.org?format=json";

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_PROXY => "ddc.oxylabs.io:8001",
    CURLOPT_PROXYUSERPWD => "vsproxy_jRkf7:5DdZ8r035PJj=", // Usuario:Contraseña del proxy
    CURLOPT_SSL_VERIFYPEER => false, // Opción para evitar problemas con SSL
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Error en cURL: " . curl_error($ch);
} else {
    echo "Respuesta: " . $response;
}

curl_close($ch);

exit();


// 🚀 Configuración de Supabase
$supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
$today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
$url = "$supabase_url/rest/v1/rpc/get_unique_users_by_partner_country";
// Inicializar cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $supabase_key",
    "Authorization: Bearer $supabase_key"
]);

// Ejecutar la petición
$response = curl_exec($ch);
curl_close($ch);
print_r($response);
// 🔍 Verificar si la respuesta es válida
if ($response === false) {
    die("Error obteniendo los datos.");
}

$Resultado = array();
// 📌 Convertir JSON a un array PHP
$data = json_decode($response, true);
foreach ($data as $row) {
    array_push($Resultado,
        array(
            '.name' => strtoupper($row['partner']) . ' ' . strtoupper($row['country']),
            '.value' => $row['unique_users'],
            '.date' => date("Y-m-d H:i:00")
        )
    );


}
$redisPrefix = 'CantTotalUserPingsPartnerCountryTotalTotal+'; // Valor por defecto

$redis = RedisConnectionTrait::getRedisInstance(true);
$redisParam = ['ex' => 18000];
if ($redis != null) {
    $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
}


// 🚀 Configuración de Supabase
$supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
$today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
$url = "$supabase_url/rest/v1/rpc/get_unique_users_by_partner";
// Inicializar cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $supabase_key",
    "Authorization: Bearer $supabase_key"
]);

// Ejecutar la petición
$response = curl_exec($ch);
curl_close($ch);

// 🔍 Verificar si la respuesta es válida
if ($response === false) {
    die("Error obteniendo los datos.");
}

$Resultado = array();
// 📌 Convertir JSON a un array PHP
$data = json_decode($response, true);
foreach ($data as $row) {
    array_push($Resultado,
        array(
            '.name' => strtoupper($row['partner']),
            '.value' => $row['unique_users'],
            '.date' => date("Y-m-d H:i:00")
        )
    );


}
$redisPrefix = 'CantTotalUserPingsPartnerTotalTotal+'; // Valor por defecto

$redis = RedisConnectionTrait::getRedisInstance(true);
$redisParam = ['ex' => 18000];
if ($redis != null) {
    $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
}


// 🚀 Configuración de Supabase
$supabase_url = "https://uruqgfsfodmdxlcysvuw.supabase.co"; // Reemplaza con tu URL de Supabase
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVydXFnZnNmb2RtZHhsY3lzdnV3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzkzOTc1NTgsImV4cCI6MjA1NDk3MzU1OH0.MTa_Y4kBImWIOCq8rEtFR0uxFltc1xP8L75LgBJc2Sw"; // Reemplaza con tu API Key

// 📅 Obtener la fecha de hoy en formato YYYY-MM-DD
$today = gmdate("Y-m-d");

// 🔄 Hacer la petición a Supabase
$url = "$supabase_url/rest/v1/rpc/get_unique_users";
// Inicializar cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $supabase_key",
    "Authorization: Bearer $supabase_key"
]);

// Ejecutar la petición
$response = curl_exec($ch);
curl_close($ch);

// 🔍 Verificar si la respuesta es válida
if ($response === false) {
    die("Error obteniendo los datos.");
}

$Resultado = array();
// 📌 Convertir JSON a un array PHP
$data = json_decode($response, true);
foreach ($data as $row) {
    array_push($Resultado,
        array(
            '.name' => strtoupper($row['partner']),
            '.value' => $row['unique_users'],
            '.date' => date("Y-m-d H:i:00")
        )
    );


}
$redisPrefix = 'CantTotalUserPingsTotalTotal+' . $type; // Valor por defecto

$redis = RedisConnectionTrait::getRedisInstance(true);
$redisParam = ['ex' => 18000];
if ($redis != null) {
    $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
}

exit();

$Types = array('FREEBET', '');
foreach ($Types as $type) {
    $sql = "
       
SELECT CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))                                 name,
       COUNT(DISTINCT (it_ticket_enc.usuario_id))              value,
       DATE_FORMAT(MAX(it_ticket_enc.fecha_crea_time), '%Y-%m-%d %H:%i:%s') date
FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
WHERE 1 = 1
  " . (($type != '') ? "AND it_ticket_enc.freebet != '0'" : '') . "

  AND it_ticket_enc.fecha_crea_time >= '" . date("Y-m-d") . " 00:00:00'
  AND it_ticket_enc.fecha_crea_time <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante,pais.pais_id
order by value desc;
        ";


    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $Transaction = $BonoInternoMySqlDAO->getTransaction();

    $BonoInterno = new BonoInterno();
    $Resultado = $BonoInterno->execQuery($Transaction, $sql);
    $redisPrefix = 'CantTotalUserBetsSportsCountryTotalTotal+' . $type; // Valor por defecto

    $redis = RedisConnectionTrait::getRedisInstance(true);
    $redisParam = ['ex' => 18000];
    if ($redis != null) {
        $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
    }
}
exit();
$redisParam = ['ex' => 18000000];

$redisPrefix = "CantTotalUserBetsCasinoTotalTotal+FREESPIN";

$redis = RedisConnectionTrait::getRedisInstance(true);
print_r($redis);

$Resultado = $redis->get($redisPrefix);
print_r('Resultado');
print_r(json_encode($Resultado));
exit();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$Transaction = $BonoDetalleMySqlDAO->getTransaction();

$JackpotInterno = new JackpotInterno(2026);
$UsuarioJackpot = new UsuarioJackpot(4111482);
$JackpotInterno->pagarJackpot($JackpotInterno->jackpotId, $UsuarioJackpot->usujackpotId, $Transaction);
$Transaction->commit();
try {
    /** Se verifica si es necesario reiniciar el jackpot La función utilizada lanza excepciones*/
    if ($JackpotInterno->reinicio) $JackpotInterno->clonarJakcpotNextSerie($JackpotInterno->jackpotId);
} catch (Exception $e) {
}

exit();
$array = array();

foreach ($array as $sql) {

    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $BonoInterno = new BonoInterno();

    $data = $BonoInterno->execQuery($transaccion, $sql);

    $transaccion->commit();
}
exit();

$UsuarioMandante = new UsuarioMandante(8060078);
print_r('entro');

$dataSend = array();
$dataSend["loyalty_price22"] = array();
print_r($dataSend);
print_r($UsuarioMandante);
$WebsocketUsuario = new WebsocketUsuario('', '');

$WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);
exit();
$UsuarioId = '2097874';
$TemplateId = '148044';
$CampaignId = '0';


$BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

$Usuario = new Usuario($UsuarioId);
$Registro = new Registro('', $UsuarioId);

$UsuarioMensajecampana = new UsuarioMensajecampana($TemplateId);
$UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
$Contenido = $UsuarioMensajecampana->body;


$ConfigurationEnvironment = new ConfigurationEnvironment();

$UsuarioMensajes = array();
$varArray = array();
$varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
$varArray['tophone'] = $Registro->celular;
$varArray['link'] = '';

array_push($UsuarioMensajes, $varArray);
$envio = $ConfigurationEnvironment->EnviarMensajeTexto($Contenido, '', $Registro->celular, 0, $UsuarioMandante, $UsuarioMensajecampana->usumencampanaId);


$array = array();
foreach ($array as $item) {


    $apmin2Sql = "
SELECT usuario_id,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               SUM(valor)                                   saldo_notaret_eliminadas
        FROM casino.usuario_retiro_resumen
        WHERE (estado = 'R' OR estado = 'E' OR estado = 'D' OR estado = 'Z' OR estado = 'W') AND  (fecha_crea)   ='" . $item . "'
        GROUP BY usuario_id
";

    $BonoInterno = new \Backend\dto\BonoInterno();
    $apmin2_RS = $BonoInterno->execQuery("", $apmin2Sql);
    foreach ($apmin2_RS as $apmin2_R) {

        $nameTable = 'usuario_saldo_' . date('Y_m_d', strtotime($apmin2_R->{'.fecha'}));
        if ($nameTable == 'usuario_saldo_2024_12_26') {
            $nameTable = 'usuario_saldo';

        }
        $sqlUpdate = 'UPDATE ' . $nameTable . '
SET saldo_notaret_eliminadas = "' . $apmin2_R->{'.saldo_notaret_eliminadas'} . '"
WHERE usuario_id = ' . $apmin2_R->{'usuario_retiro_resumen.usuario_id'} . ';

';

        $apmin2_RS22 = $BonoInterno->execQuery("", $sqlUpdate);

    }
}

exit();
$sslCert = '/home/home2/backendprodfinal/api/src/imports/playtech/ssl/seamless/certLOPROD.pem';
$sslKey = '/home/home2/backendprodfinal/api/src/imports/playtech/ssl/seamless/clientLOPROD.key.pem';
$data = '{"requestId":"61194","username":"7192398","templateCode":"200989","count":"10","value":0,"remoteBonusCode":"103724723","remoteBonusDate":"2024-12-20 23:36:56.307","gameCodeNames":["gpas_ccsahara_pop"],"winWageringMultiplier":2,"playerData":{"currencyCode":"HNL","countryCode":"HN"}}';

$headers = [
    "Content-Type: application/json",
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://gameslink-cur.techonlinecorp.com/product/gameslink/service/givefreespins');
curl_setopt($ch, CURLOPT_SSLCERT, $sslCert);
curl_setopt($ch, CURLOPT_SSLKEY, $sslKey);
curl_setopt($ch, CURLOPT_SSLCERTPASSWD, 'FSCDZv70Dw4m5k64');
curl_setopt($ch, CURLOPT_ENCODING, '');  // Corregido
curl_setopt($ch, CURLOPT_TIMEOUT, 30);  // Timeout de 30 segundos
curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Redirecciones máximas
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
$info = curl_errno($ch) > 0 ? array("curl_error_" . curl_errno($ch) => curl_error($ch)) : curl_getinfo($ch);

print_r($result);
print_r($info);
exit();

$ConfigurationEnvironment = new ConfigurationEnvironment();
$couponscode = $ConfigurationEnvironment->decryptCusNum('2F0P42VfUElJ3jwS');
print_r($couponscode);
$ENCRYPTION_KEY = "D!@#$%^&*";
function decrypt($data, $encryption_key = "")
{
    $data = str_replace("vSfTp", "/", $data);
    $passEncryt = 'li1296-151.members.linode.com|3232279913';

    if ($data == "a17627d4cddfa7086c831da71e08701fMiw%209oHr3wpioQ%3D%3D" || $data == "a17627d4cddfa7086c831da71e08701fMiw 9oHr3wpioQ==" || $data == "a17627d4cddfa7086c831da71e08701fMiw 9oHr3wpioQ%3D%3D") {
        $data = "73543a712cc0221442390a0d05f508ebYV5AXzWRDutCdw==";

    }


    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', $passEncryt, 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return FALSE;
    }
}

try {
    $UsuarioMandante = new UsuarioMandante(4330261);
    $rules = [];
    array_push($rules, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
    array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "JACKPOTWINNER", "op" => "eq"));
    array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => 0, "op" => "cn"));
    $filtroM = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtroM);

    $UsuarioMensaje = new UsuarioMensaje();
    $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->usumandanteId);
    $usuarios = json_decode($usuarios)->data;

    $messagesToDesactivate = [];
    foreach ($usuarios as $jackpotWinnerMessage) {
        try {
            $JackpotInterno = new JackpotInterno($jackpotWinnerMessage->{'usuario_mensaje.body'});
        } catch (Exception $e) {
            break;
        }

        $dropedJackpotData = [[
            'id' => $JackpotInterno->jackpotId,
            'videoMobile' => $JackpotInterno->videoMobile,
            'video' => $JackpotInterno->videoDesktop,
            'gif' => $JackpotInterno->gif,
            'imagen' => $JackpotInterno->imagen,
            'imagen2' => $JackpotInterno->imagen2,
            'monto' => $JackpotInterno->valorActual
        ]];
    }


    $dataSend = array();
    $dataSend["loyalty_price"] = $dropedJackpotData;
    print_r($dataSend);
    print_r($UsuarioMandante);
    $WebsocketUsuario = new WebsocketUsuario('', '');
    $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);
} catch (Exception $e) {
}
exit();

// Establecer la fecha de inicio
$fechaInicio = new DateTime('2024-06-01');
// Establecer la fecha actual
$fechaFin = new DateTime();
// Crear un array para almacenar las fechas
$fechas = [];

// Generar fechas día a día
while ($fechaInicio <= $fechaFin) {
    $fechas[] = $fechaInicio->format('Y-m-d'); // Agregar la fecha al array
    $fechaInicio->modify('+1 day'); // Sumar un día
}
error_reporting(0);
ini_set('display_errors', 'OFF');

foreach ($fechas as $key => $value) {

    $apmin2Sql = "
select fecha, count(*) TotalesConJuegos, COUNT(DISTINCT usuario_id) UsuariosUnicos, COUNT(DISTINCT producto_id) JuegosUnicos
from (SELECT DATE(j.fecha_crea)                              fecha,
             j.usuario_id,
             j.producto_id,
             datediff(j.fecha_crea, ultimaJugada.fecha_crea) diferencia
      FROM usucasino_detalle_resumen j
               INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = j.usuario_id)
               INNER JOIN (SELECT j2.producto_id, j2.usuario_id, max(j2.fecha_crea) fecha_crea
                           FROM usucasino_detalle_resumen j2
                                    INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = j2.usuario_id)
                           WHERE usuario_mandante.mandante = 0 and usuario_mandante.pais_id = 173
                             AND j2.fecha_crea < DATE('" . $value . "') - INTERVAL 10 DAY
                           group by producto_id) ultimaJugada
                          on ultimaJugada.producto_id = j.producto_id and ultimaJugada.usuario_id = j.usuario_id

      WHERE usuario_mandante.mandante = 0 and usuario_mandante.pais_id = 173
        AND j.fecha_crea = DATE('" . $value . "')
      GROUP BY j.usuario_id, j.producto_id

      having diferencia >= 10) final
";

    $BonoInterno = new \Backend\dto\BonoInterno();
    $apmin2_RS = $BonoInterno->execQuery("", $apmin2Sql);

    print($value);
    print(' ' . $apmin2_RS[0]->{'.TotalesConJuegos'});
    print(' ' . $apmin2_RS[0]->{'.UsuariosUnicos'});
    print(' ' . $apmin2_RS[0]->{'.JuegosUnicos'});
    print(PHP_EOL);

}
exit();

$array = array();
foreach ($array as $item) {
    print_r($item);
    print_r(PHP_EOL);
    $UsuarioRecarga = new UsuarioRecarga($item);
    $Usuario = new Usuario($UsuarioRecarga->usuarioId);

    $detalles = array(
        "Depositos" => 0,
        "DepositoEfectivo" => true,
        "MetodoPago" => 0,
        "ValorDeposito" => $UsuarioRecarga->getValor(),
        "PaisPV" => $Usuario->paisId,
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
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $Transaction = $BonoInternoMySqlDAO->getTransaction();

    $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);
    print_r($respuestaBono);

    $Transaction->commit();

}


exit();


print_r(time());
print_r(PHP_EOL);
print_r(date('Y-m-d', time()));
print_r(PHP_EOL);
print_r(date('H:i:s', time()));
print_r(PHP_EOL);

print_r(date('Y-m-d H:i:s', time()));
exit();

function consultPayrollGET($idPayroll)
{

    $url = 'https://apipayout.monnetpayments.com' . '/ms-payroll-back/commerce/' . '67' . '/payroll/load/' . $idPayroll;

    $String = '67' . $idPayroll . 'ggRBx0vua5Tu0n6GmwPOS6L9GchWgrzmd/pP8AnfXZ0=';

    print_r($String);
    $verfication = hash('sha256', $String, false);

    $header = array(
        'verification: ' . $verfication
    );

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $header,
    ));

    $response = curl_exec($curl);

    try {
        syslog(10, 'CONSULTMONNETSERVICERESPONSE ' . $response);

    } catch (Exception $e) {

    }
    curl_close($curl);
    return $response;


}

$_ENV['debug'] = true;

$cuentas = array();
foreach ($cuentas as $Id) {
    $CuentaCobro = new CuentaCobro($Id);

    if ($CuentaCobro->getEstado() == "A") {

        $CuentaCobro->setEstado('I');
        $CuentaCobro->setUsupagoId(0);
        $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

        if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }
        if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }
        if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }
        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
        }

        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
        }

        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];
        $response["Data"] = [];
    } else {
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
        $response["ModelErrors"] = [];
        $response["Data"] = [];

    }
}

exit();


$dataD = array(
    "ExtUserId" => 1 . "U",
    "WalletCode" => "190582",
    "BonusPlanId" => 1,
    "Deposit" => floatval(1) * 100
);

$dataD = json_encode($dataD);


// Inicializar la clase CurlWrapper
$curl = new CurlWrapper('https://sb2bonus-integration-altenar2.biahosted.com/api/Bonus/CreateBonusByDeposit/json');

// Configurar opciones
$curl->setOptionsArray([
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 300,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $dataD,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
]);

// Ejecutar la solicitud
$response = $curl->execute();
print_r($response);

exit();